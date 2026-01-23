<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use  setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use Barryvdh\DomPDF\Facade\Pdf as DomPdf;


class TravelController extends Controller
{
  // List all travel orders requested by the logged-in user
  public function index()
  {
    $employeeId = Auth::user()->employee_id ?? null;
    $travels = Travel::where('travel_request_by', $employeeId)
      ->orderBy('date_requested', 'desc')
      ->get();

    return view('forms.travel.index', compact('travels'));
  }

  // Show create form
  public function create()
  {
    $employees = User::select('id', 'first_name', 'employee_id')->get();
    return view('forms.travel.create', compact('employees'));
  }

  // Store travel order
  public function store(Request $request)
  {
    $request->validate([
      'user_id.*' => 'required',
      'travel_date.*' => 'required|date',
      'travel_purpose.*' => 'required|string',
      'travel_destination.*' => 'required|string',
    ]);

    $count = count($request->user_id);
    $travelReferenceNumber = 'TR-' . date('YmdHis') . '-' . Str::upper(Str::random(4));
    $requestBy = Auth::user()->employee_id ?? null;

    for ($i = 0; $i < $count; $i++) {
      Travel::create([
        'user_id' => $request->user_id[$i],
        'travel_date' => $request->travel_date[$i],
        'travel_purpose' => $request->travel_purpose[$i],
        'travel_destination' => $request->travel_destination[$i],
        'date_requested' => now(),
        'status' => 'Pending',
        'travel_reference_number' => $travelReferenceNumber,
        'travel_request_by' => $requestBy,
      ]);
    }

    return redirect()->route('forms.travel.index')
      ->with('success', 'Travel order created successfully with reference number: ' . $travelReferenceNumber);
  }

  // Prepare unsigned PDF
  public function prepareSign($ref)
  {
    $travels = Travel::with(['employee.division', 'employee.section', 'employee.position'])
      ->where('travel_reference_number', $ref)->get();

    if ($travels->isEmpty()) {
      return redirect()->back()->with('error', 'Travel not found.');
    }

    $directory = storage_path('app/documents');
    if (!file_exists($directory)) mkdir($directory, 0777, true);

    $pdfPath = storage_path("app/documents/{$ref}.pdf");

    $pdf = Pdf::loadView('forms.travel.pdf', compact('travels'))
      ->setPaper('legal', 'portrait');
    $pdf->save($pdfPath);

    return redirect()->back()->with('openSignatureModal', true);
  }
  // Print PDF (use signed if exists)
  public function print($ref)
  {
    $signedPdf = storage_path("app/documents/{$ref}_signed.pdf");
    if (file_exists($signedPdf)) {
      return response()->file($signedPdf);
    }

    $travels = Travel::with(['employee.division', 'employee.section', 'employee.position'])
      ->where('travel_reference_number', $ref)->get();

    if ($travels->isEmpty()) {
      return redirect()->route('forms.travel.index')->with('error', 'Travel batch not found.');
    }

    $pdf = Pdf::loadView('forms.travel.pdf', compact('travels'))
      ->setPaper('legal', 'portrait');

    return $pdf->stream("Travel_Order_{$ref}.pdf");
  }

  public function digitalSignImage(Request $request, $ref)
  {
    $certificateFile = $request->file('certificate');
    $password        = $request->password;
    $signatureImage = $request->file('signature_image');

    if (!$certificateFile || !$password) {
      return back()->with('error', '❌ Certificate file or password not provided.');
    }

    // ---------------------------
    // Step 1: Validate P12/PFX
    // ---------------------------
    try {
      $pkcs12 = file_get_contents($certificateFile->getRealPath());
      $certs = [];
      if (!openssl_pkcs12_read($pkcs12, $certs, $password)) {
        return back()->with('error', '❌ Cannot read certificate. Check password.');
      }
      unset($pkcs12);

      $privateKey  = $certs['pkey'];
      $certificate = $certs['cert'];
    } catch (\Exception $e) {
      return back()->with('error', '❌ Failed to read certificate: ' . $e->getMessage());
    }

    // ---------------------------
    // Step 2: Prepare PDF
    // ---------------------------
    $pdfPath = storage_path("app/documents/{$ref}.pdf");
    if (!file_exists($pdfPath)) {
      $travels = Travel::with(['employee.division', 'employee.section', 'employee.position'])
        ->where('travel_reference_number', $ref)
        ->get();

      if ($travels->isEmpty()) {
        return back()->with('error', "❌ Travel order {$ref} not found.");
      }

      $pdf = DomPdf::loadView('forms.travel.pdf', compact('travels'))
        ->setPaper('legal', 'portrait');
      $pdf->save($pdfPath);
    }

    // ---------------------------
    // Step 3: Load PDF with FPDI and sign
    // ---------------------------
    $signedPdfPath = storage_path("app/documents/{$ref}_signed.pdf");

    try {
      //$pdf = new Fpdi(); // FPDI extends TCPDF
      $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();
      $pageCount = $pdf->setSourceFile($pdfPath);

      for ($i = 1; $i <= $pageCount; $i++) {
        $tplId = $pdf->importPage($i);
        $size = $pdf->getTemplateSize($tplId);
        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($tplId);
      }

      // Fetch the travel order with employee details
      $travel = Travel::with('employee')
        ->where('travel_reference_number', $ref)
        ->first();

      if (!$travel) {
        return back()->with('error', "Travel order {$ref} not found.");
      }

      // Construct full name
      $employee = $travel->employee;
      $certFullname = "{$employee->first_name} {$employee->middle_name} {$employee->last_name}";


      // Signature metadata
      $info = [
        'Name'        => $certFullname,
        'Location'    => 'Philippines',
        'Reason'      => 'Approved electronically',
        'ContactInfo' => 'email@dswd.gov.ph'
      ];
      // setSignature and setSignatureAppearance are TCPDF methods
      $pdf->setSignature($certificate, $privateKey, $certificate, '', 2, $info);
      for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
        $tplId = $pdf->importPage($pageNo);
        $size  = $pdf->getTemplateSize($tplId);

        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($tplId);

        if ($pageNo === 1) {
          $sigX = 120;
          $sigY = 185;
          $sigWidth = 60;
          $sigHeight = 20;

          if ($signatureImage && file_exists($signatureImage->getRealPath())) {
            $pdf->Image(
              $signatureImage->getRealPath(), // path to the uploaded image
              $sigX,
              $sigY, // position
              18,
              18,       // width & height
              '',
              '',
              '',
              false,
              200
            );
          }

          $pdf->SetFont('helvetica', '', 7);
          $pdf->SetXY($sigX + 22, $sigY + 2);
          $text  = "Digitally signed by $certFullname\n";
          $text .= "Date: " . date('Y.m.d H:i:s O');
          $pdf->MultiCell($sigWidth, $sigHeight, $text, 0, 'L', false, 1);

          $pdf->setSignatureAppearance($sigX, $sigY, $sigWidth, $sigHeight);
        }
      }

      $pdf->Output($signedPdfPath, 'F');
    } catch (\Exception $e) {
      return back()->with('error', '❌ Failed to sign PDF: ' . $e->getMessage());
    }

    // ---------------------------
    // Step 4: Trigger download
    // ---------------------------
    return response()->download($signedPdfPath, "TravelOrder_{$ref}_signed.pdf");
  }

  // Wet Signature (image)
  public function wetSign(Request $request, $ref)
  {
    $request->validate([
      'signature_image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
    ]);

    $image = $request->file('signature_image');

    $pdfPath = storage_path("app/documents/{$ref}.pdf");
    if (!file_exists($pdfPath) || filesize($pdfPath) === 0) {
      $travels = Travel::with(['employee.division', 'employee.section', 'employee.position'])
        ->where('travel_reference_number', $ref)->get();
      $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.travel.pdf', compact('travels'))
        ->setPaper('legal', 'portrait');
      $pdf->save($pdfPath);
    }

    $signedPdfPath = storage_path("app/documents/{$ref}_wet_signed.pdf");

    $pdf = new \setasign\Fpdi\Fpdi();
    $pageCount = $pdf->setSourceFile($pdfPath);

    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
      $tplId = $pdf->importPage($pageNo);
      $pdf->AddPage();
      $pdf->useTemplate($tplId, 1, 1, 1, 1);

      if ($pageNo == $pageCount) {
        $pdf->Image($image->getRealPath(), 150, 220, 40);
      }
    }

    $pdf->Output('F', $signedPdfPath); // saved permanently

    return response()->download($signedPdfPath);
  }

  public function electronicSignImage(Request $request, $ref)
  {
    $request->validate([
      'signature_image' => 'required|image|mimes:png,jpg,jpeg|max:2048',
    ]);

    $image = $request->file('signature_image');

    // Ensure PDF exists or generate
    $pdfPath = storage_path("app/documents/{$ref}.pdf");
    if (!file_exists($pdfPath) || filesize($pdfPath) === 0) {
      $travels = Travel::with(['employee.division', 'employee.section', 'employee.position'])
        ->where('travel_reference_number', $ref)->get();

      if ($travels->isEmpty()) {
        return back()->with('error', 'Travel batch not found. Cannot generate PDF.');
      }

      $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.travel.pdf', compact('travels'))
        ->setPaper('legal', 'portrait');
      $pdf->save($pdfPath);

      if (!file_exists($pdfPath) || filesize($pdfPath) === 0) {
        return back()->with('error', 'Failed to generate PDF.');
      }
    }

    $signedPdfPath = storage_path("app/documents/{$ref}_electronic_signed.pdf");

    // Save uploaded image temporarily with proper extension
    $ext = $image->getClientOriginalExtension();
    $tmpPath = storage_path("app/temp_signature_{$ref}.{$ext}");
    $image->move(dirname($tmpPath), basename($tmpPath));

    // Initialize FPDI
    $pdf = new \setasign\Fpdi\Fpdi();

    try {
      $pageCount = $pdf->setSourceFile($pdfPath);
    } catch (\setasign\Fpdi\PdfReader\PdfReaderException $e) {
      if (file_exists($tmpPath)) unlink($tmpPath);
      return back()->with('error', 'Failed to read PDF: ' . $e->getMessage());
    }

    // Import pages and place signature on last page
    for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
      $tplId = $pdf->importPage($pageNo);
      $size = $pdf->getTemplateSize($tplId);

      $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
      $pdf->useTemplate($tplId);

      if ($pageNo == $pageCount) {
        // Place signature above Regional Director's name
        $pdf->Image($tmpPath, 150, 190, 0, 30); // Adjust X, Y, width=auto, height
      }
    }

    // Save the signed PDF
    $pdf->Output('F', $signedPdfPath);

    // Cleanup temp image
    if (file_exists($tmpPath)) {
      unlink($tmpPath);
    }

    // Return signed PDF
    return response()->download($signedPdfPath);
  }
}
