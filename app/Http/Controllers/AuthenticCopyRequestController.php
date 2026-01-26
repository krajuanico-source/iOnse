<?php

namespace App\Http\Controllers;

use App\Models\AuthenticCopyRequest;
use App\Models\Cpr;
use Illuminate\Http\Request;
use App\Notifications\AuthenticCopyStatusUpdated;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tcpdf\Fpdi;

class AuthenticCopyRequestController extends Controller
{
  public function index()
  {
    $requests = AuthenticCopyRequest::with('user')->latest()->get();

     foreach ($requests as $req) {
        $ids = [];

        // Case 1: selections is already an array
        if (is_array($req->selections)) {
            $ids = $req->selections;
        }
        // Case 2: selections is JSON string
        elseif (is_string($req->selections)) {
            $decoded = json_decode($req->selections, true);
            $ids = is_array($decoded) ? $decoded : [];
        }
        // Case 3: selections is a single integer
        elseif (is_int($req->selections)) {
            $ids = [$req->selections];
        }

        // Fetch CPR records safely
        $req->cprRecords = Cpr::whereIn('id', $ids)->get();
      }

    return view('forms.cprrequest.index', compact('requests'));
  }

public function updateStatus(Request $request, AuthenticCopyRequest $authenticCopyRequest)
{
    // 1. Validate input (FIXED)
    $validated = $request->validate([
        'status' => 'required|in:Pending,For Signature,Rejected',
        'selections' => 'sometimes|array',
        'selections.*' => 'integer|exists:cprs,id',

    ]);

    // 2. Update request status (NOW WORKS)
    $authenticCopyRequest->update([
        'status' => $validated['status'],
    ]);

    // 3. Generate PDF only when approved
    // if ($validated['status'] === 'Approved') {

    //     $ratings = collect($authenticCopyRequest->selections ?? []);

    //     if ($ratings->isEmpty()) {
    //         return back()->with('error', 'No ratings found for this request.');
    //     }

    //     $ratings = Cpr::whereIn('id', $ratings)->get();

    //     $pdf = Pdf::loadView('pdf.authentic-copy', [
    //         'request' => $authenticCopyRequest,
    //         'ratings' => $ratings,
    //     ]);

    //     $path = 'authentic-copies/authentic_copy_' . $authenticCopyRequest->id . '.pdf';

    //     Storage::disk('public')->put($path, $pdf->output());

    //     $authenticCopyRequest->update([
    //         'pdf_path' => $path,
    //     ]);

    // }

    // 4. Notify user
    if ($authenticCopyRequest->user) {
        $authenticCopyRequest->user
            ->notify(new AuthenticCopyStatusUpdated($authenticCopyRequest));
    }

    return back()->with('success', 'Request status updated successfully.');
}

  public function digitalSign(Request $request, $id)
  {
    $request->validate([
      'certificate'     => 'required|file|mimes:p12,pfx',
      'password'        => 'required|string',
      'signature_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
    ]);

    $authReq = AuthenticCopyRequest::with('user')->findOrFail($id);

    if ($authReq->status !== 'For Signature') {
        return back()->with('error', 'Request is not ready for signing.');
    }
/* ======================
       1. GENERATE PDF FIRST
    ====================== */

    $ratings = collect($authReq->selections ?? []);

    if ($ratings->isEmpty()) {
        return back()->with('error', 'No ratings found.');
    }

    $ratings = Cpr::whereIn('id', $ratings)->get();

    $unsignedPdf = Pdf::loadView('pdf.authentic-copy', [
        'request' => $authReq,
        'ratings' => $ratings,
    ]);

    $unsignedPath = 'authentic-copies/authentic_copy_' . $authReq->id . '.pdf';
    Storage::disk('public')->put($unsignedPath, $unsignedPdf->output());

    /* ======================
       2. DIGITAL SIGN PDF
    ====================== */

    $signedPath = 'authentic-copies/authentic_copy_' . $authReq->id . '_signed.pdf';
    $signedPdf  = storage_path('app/public/' . $signedPath);

    try {
        $pkcs12 = file_get_contents($request->file('certificate')->getRealPath());
        $certs  = [];

        if (!openssl_pkcs12_read($pkcs12, $certs, $request->password)) {
            return back()->with('error', 'Invalid certificate or password.');
        }

        $pdf = new Fpdi();
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pageCount = $pdf->setSourceFile(storage_path('app/public/' . $unsignedPath));

        $employeeName = $authReq->user->first_name . ' ' . $authReq->user->last_name;

        $pdf->setSignature(
            $certs['cert'],
            $certs['pkey'],
            $certs['cert'],
            '',
            2,
            [
                'Name'     => $employeeName,
                'Location' => 'Philippines',
                'Reason'   => 'Authentic Copy Approval',
            ]
        );

        for ($page = 1; $page <= $pageCount; $page++) {
            $tpl = $pdf->importPage($page);
            $size = $pdf->getTemplateSize($tpl);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);

            if ($page === 1 && $request->hasFile('signature_image')) {
                $pdf->Image($request->file('signature_image')->getRealPath(), 120, 185, 18);
                $pdf->setSignatureAppearance(120, 185, 60, 20);
            }
        }

        $pdf->Output($signedPdf, 'F');

        /* ======================
           3. FINALIZE REQUEST
        ====================== */

        $authReq->update([
            'pdf_path'        => $unsignedPath,
            'signed_pdf_path' => $signedPath,
            'status'          => 'Approved',
        ]);

        return back()->with('success', 'PDF signed and approved successfully.');
    } catch (\Exception $e) {
        return back()->with('error', 'Signing failed: ' . $e->getMessage());
    }
}
  public function wetSign(Request $request, $id)
  {
    $request->validate([
      'signature_image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
    ]);

    $authReq = AuthenticCopyRequest::findOrFail($id);

    if (!$authReq->pdf_path || !Storage::disk('public')->exists($authReq->pdf_path)) {
      return back()->with('error', 'Unsigned PDF not found.');
    }

    try {
      $sourcePdf = storage_path('app/public/' . $authReq->pdf_path);
      $signedPath = 'authentic-copies/authentic_copy_' . $authReq->id . '_wet_signed.pdf';

      $pdf = new Fpdi();
      $pageCount = $pdf->setSourceFile($sourcePdf);

      for ($page = 1; $page <= $pageCount; $page++) {
        $tplId = $pdf->importPage($page);
        $size  = $pdf->getTemplateSize($tplId);

        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($tplId);

        if ($page === $pageCount) {
          $pdf->Image(
            $request->file('signature_image')->getRealPath(),
            150,
            220,
            40
          );
        }
      }

      Storage::disk('public')->put($signedPath, $pdf->Output('S'));

      $authReq->signed_pdf_path = $signedPath;
      $authReq->save();
    } catch (\Exception $e) {
      return back()->with('error', 'Wet signing failed: ' . $e->getMessage());
    }

    return back()->with('success', '✅ PDF successfully signed!');
  }
  public function wetSignDownload($id)
  {
    $authReq = AuthenticCopyRequest::findOrFail($id);

    if (! $authReq->signed_pdf_path || !Storage::disk('public')->exists($authReq->signed_pdf_path)) {
      return back()->with('error', 'Signed PDF not found.');
    }

    $signedPdf = storage_path('app/public/' . $authReq->signed_pdf_path);

    return response()->download($signedPdf);
  }
}
