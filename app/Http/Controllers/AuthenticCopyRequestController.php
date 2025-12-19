<?php

namespace App\Http\Controllers;

use App\Models\AuthenticCopyRequest;
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
    return view('forms.cprrequest.index', compact('requests'));
  }

  public function updateStatus(Request $request, AuthenticCopyRequest $authenticCopyRequest)
  {
    // 1. Validate input
    $validated = $request->validate([
      'status' => 'required|in:Pending,Approved,Rejected',
    ]);

    // 2. Update request status
    $authenticCopyRequest->update([
      'status' => $validated['status'],
    ]);

    // 3. Generate PDF only when approved
    if ($validated['status'] === 'Approved') {

      // Selections are stored as JSON (array)
      $ratings = collect($authenticCopyRequest->selections ?? []);

      // Safety check
      if ($ratings->isEmpty()) {
        return back()->with('error', 'No ratings found for this request.');
      }

      $pdf = Pdf::loadView('pdf.authentic-copy', [
        'request' => $authenticCopyRequest,
        'ratings' => $ratings,
      ]);

      $path = 'authentic-copies/authentic_copy_' . $authenticCopyRequest->id . '.pdf';

      Storage::disk('public')->put($path, $pdf->output());

      // Save PDF path
      $authenticCopyRequest->update([
        'pdf_path' => $path,
      ]);
    }

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

    if (!$authReq->pdf_path || !Storage::disk('public')->exists($authReq->pdf_path)) {
      return back()->with('error', 'Unsigned PDF not found.');
    }

    $signedPath = 'authentic-copies/authentic_copy_' . $authReq->id . '_signed.pdf';
    $signedPdf = storage_path('app/public/' . $signedPath);

    try {
      $pkcs12 = file_get_contents($request->file('certificate')->getRealPath());
      $certs = [];
      if (!openssl_pkcs12_read($pkcs12, $certs, $request->password)) {
        return back()->with('error', '❌ Invalid certificate or password.');
      }

      $privateKey  = $certs['pkey'];
      $certificate = $certs['cert'];

      $sourcePdf = storage_path('app/public/' . $authReq->pdf_path);

      // <<< This is the key fix: use TCPDF FPDI
      $pdf = new Fpdi(); // OR new \setasign\Fpdi\Tcpdf\Fpdi();
      $pdf->setPrintHeader(false);
      $pdf->setPrintFooter(false);

      $pageCount = $pdf->setSourceFile($sourcePdf);
      $employeeName = $authReq->user->first_name . ' ' . $authReq->user->last_name;

      $pdf->setSignature($certificate, $privateKey, $certificate, '', 2, [
        'Name'     => $employeeName,
        'Location' => 'Philippines',
        'Reason'   => 'Authentic Copy Approval',
      ]);

      for ($page = 1; $page <= $pageCount; $page++) {
        $tplId = $pdf->importPage($page);
        $size = $pdf->getTemplateSize($tplId);

        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($tplId);

        if ($page === 1 && $request->hasFile('signature_image')) {
          $pdf->Image($request->file('signature_image')->getRealPath(), 120, 185, 18, 18);
          $pdf->SetFont('helvetica', '', 7);
          $pdf->SetXY(142, 187);
          $pdf->MultiCell(60, 20, "Digitally signed by {$employeeName}\nDate: " . now()->format('Y.m.d H:i:s'));
          $pdf->setSignatureAppearance(120, 185, 60, 20);
        }
      }

      $pdf->Output($signedPdf, 'F');

      $authReq->signed_pdf_path = $signedPath;
      $authReq->save();

      return back()->with([
        'success'    => '✅ PDF successfully signed!',
        'signed_pdf' => $signedPath
      ]);
    } catch (\Exception $e) {
      return back()->with('error', '❌ Digital signing failed: ' . $e->getMessage());
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
