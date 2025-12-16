<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OutSlip;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Make sure barryvdh/laravel-dompdf is installed


class OutSlipController extends Controller
{
  // Show list/dashboard
  public function index()
  {
    // Only fetch out slips with status Pending or Approved
    $outSlips = OutSlip::whereIn('status', ['Pending', 'Approved'])
      ->latest()
      ->get();

    return view('forms.outslip.index', compact('outSlips'));
  }
  public function create()
  {
    // Get the logged-in user
    $user = Auth::user();

    // Get the employee ID
    $empid = $user->employee_id; // assuming the column in users table is 'empid'

    // Pass it to the Blade view
    return view('forms.outslip.form', compact('empid'));
  }

  // Store new out slip
  public function store(Request $request)
  {
    $validated = $request->validate([
      'date' => 'required|date',
      'empid' => 'required|string|max:20',
      'destination' => 'required|string|max:255',
      'type_of_slip' => 'required|string|max:100',
      'purpose' => 'nullable|string|max:500',
    ]);

    $validated['status'] = 'Pending';
    $validated['approved_by'] = null;

    OutSlip::create($validated);

    return response()->json(['message' => 'Out Slip submitted successfully!']);
  }

  // Approve out slip
  public function approve($id)
  {
    $outSlip = OutSlip::findOrFail($id);
    $outSlip->update([
      'status' => 'Approved',
      'approved_by' => Auth::id(),
    ]);

    return response()->json(['message' => 'Out Slip approved successfully!']);
  }

  // Reject out slip
  public function reject($id)
  {
    $outSlip = OutSlip::findOrFail($id);
    $outSlip->update([
      'status' => 'Rejected',
      'approved_by' => Auth::id(),
    ]);

    return response()->json(['message' => 'Out Slip rejected.']);
  }
  public function print($id)
  {
    // Load slip with employee + division + section
    $slip = OutSlip::with([
      'employee.division',
      'employee.section'
    ])->findOrFail($id);

    // If slip is approved, open signed PDF
    if ($slip->status === 'Approved') {
      $signedPdfPath  = storage_path("app/documents/outslip_{$id}_signed.pdf");
      $eSignedPdfPath = storage_path("app/documents/outslip_{$id}_e_signed.pdf");

      if (file_exists($signedPdfPath)) {
        $pdfPath = $signedPdfPath;
      } elseif (file_exists($eSignedPdfPath)) {
        $pdfPath = $eSignedPdfPath;
      } else {
        return back()->with('error', 'Signed PDF not found.');
      }

      return response()->file($pdfPath); // Opens PDF in browser
    }

    // If slip is pending, render the Blade view
    $status       = \App\Models\EmploymentStatus::all();
    $divisions    = \App\Models\Division::all();
    $sections     = \App\Models\Section::all();
    $salaryGrades = \App\Models\SalaryGrade::all();
    $positions    = \App\Models\Position::all();

    return view('forms.outslip.print', compact(
      'slip',
      'status',
      'divisions',
      'sections',
      'salaryGrades',
      'positions'
    ));
  }

  public function digitalSignImage(Request $request, $ref)
  {
    $certificateFile = $request->file('certificate');
    $password        = $request->password;
    $signatureImage  = $request->file('signature_image');

    if (!$certificateFile || !$password) {
      return back()->with('error', '❌ Certificate file or password not provided.');
    }

    // Step 1: Validate P12/PFX
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

    // Step 2: Load OutSlip
    $slip = OutSlip::with(['employee.division', 'employee.section', 'employee.employmentStatus'])->find($ref);

    if (!$slip) {
      return back()->with('error', "❌ OutSlip #{$ref} not found.");
    }

    // Step 3: Generate unsigned PDF (if not existing)
    $pdfPath = storage_path("app/documents/outslip_{$ref}.pdf");

    if (!file_exists($pdfPath)) {
      try {
        $pdf = Pdf::loadView('forms.outslip.pdf', compact('slip'))
          ->setPaper('legal', 'landscape');
        $pdf->save($pdfPath);
      } catch (\Exception $e) {
        return back()->with('error', '❌ Failed to generate base PDF: ' . $e->getMessage());
      }
    }

    // Step 4: Sign PDF with FPDI/TCPDF
    $signedPdfPath = storage_path("app/documents/outslip_{$ref}_signed.pdf");

    try {
      $pdfFpdi = new \setasign\Fpdi\Tcpdf\Fpdi();
      $pageCount = $pdfFpdi->setSourceFile($pdfPath);

      for ($i = 1; $i <= $pageCount; $i++) {
        $tplId = $pdfFpdi->importPage($i);
        $size  = $pdfFpdi->getTemplateSize($tplId);
        $pdfFpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdfFpdi->useTemplate($tplId);
      }

      $employee = $slip->employee;
      $certFullname = "{$employee->first_name} {$employee->middle_name} {$employee->last_name}";

      $info = [
        'Name'        => $certFullname,
        'Location'    => 'Philippines',
        'Reason'      => 'Approved electronically',
        'ContactInfo' => $employee->email ?? 'noreply@dswd.gov.ph'
      ];

      $pdfFpdi->setSignature($certificate, $privateKey, $certificate, '', 2, $info);

      // Step 4a: Place TWO signature images (left/right)
      // Step 4a: Place TWO signature blocks (image + text beside)

      if ($signatureImage && file_exists($signatureImage->getRealPath())) {

        // Signature image size
        $sigWidth  = 22;
        $sigHeight = 22;

        // LEFT COPY position
        $leftX = 50;   // signature image X
        $sigY  = 66;   // same Y for image and text base

        // RIGHT COPY X
        $rightX = $leftX + 157;

        // ==========================
        // 1) LEFT COPY
        // ==========================
        // Signature image
        $pdfFpdi->Image(
          $signatureImage->getRealPath(),
          $leftX,
          $sigY,
          $sigWidth,
          $sigHeight,
          '',
          '',
          '',
          false,
          200
        );

        // Signature text (to the right of the image)
        $textX = $leftX + $sigWidth + 3; // small padding

        $pdfFpdi->SetFont('helvetica', '', 8);
        $pdfFpdi->SetXY($textX, $sigY + 2);
        $pdfFpdi->Write(0, "Digitally signed by $certFullname");

        $pdfFpdi->SetXY($textX, $sigY + 6);
        $pdfFpdi->Write(0, "Date: " . now()->format('Y-m-d H:i:s O'));

        // ==========================
        // 2) RIGHT COPY
        // ==========================
        // Signature image
        $pdfFpdi->Image(
          $signatureImage->getRealPath(),
          $rightX,
          $sigY,
          $sigWidth,
          $sigHeight,
          '',
          '',
          '',
          false,
          200
        );

        // Signature text (right side copy)
        $textX2 = $rightX + $sigWidth + 3;

        $pdfFpdi->SetXY($textX2, $sigY + 2);
        $pdfFpdi->Write(0, "Digitally signed by $certFullname");

        $pdfFpdi->SetXY($textX2, $sigY + 6);
        $pdfFpdi->Write(0, "Date: " . now()->format('Y-m-d H:i:s O'));
      }

      $pdfFpdi->Output($signedPdfPath, 'F');
      $slip->status = 'Approved';
      $slip->updated_at = now();
      $slip->save();
    } catch (\Exception $e) {
      return back()->with('error', '❌ Failed to sign PDF: ' . $e->getMessage());
    }

    // Step 5: Download signed PDF
    return response()->download($signedPdfPath, "OutSlip_{$ref}_signed.pdf");
  }

  public function electronicSignImage(Request $request, $ref)
  {
    $request->validate([
      'signature_image' => 'required|image|mimes:png,jpg,jpeg|max:4096',
    ]);

    $image = $request->file('signature_image');

    // ---------------------------------------------------------
    // STEP 1: Ensure base PDF exists
    // ---------------------------------------------------------
    $pdfPath = storage_path("app/documents/outslip_{$ref}.pdf");

    if (!file_exists($pdfPath) || filesize($pdfPath) === 0) {

      $slip = OutSlip::with([
        'employee.division',
        'employee.section',
        'employee.employmentStatus'
      ])->find($ref);

      if (!$slip) {
        return back()->with('error', '❌ OutSlip not found. Cannot generate PDF.');
      }

      try {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.outslip.pdf', compact('slip'))
          ->setPaper('legal', 'landscape');

        $pdf->save($pdfPath);
      } catch (\Exception $e) {
        return back()->with('error', '❌ Failed to generate PDF.');
      }
    }

    // ---------------------------------------------------------
    // STEP 2: Temporary signature file
    // ---------------------------------------------------------
    $ext = $image->getClientOriginalExtension();
    $tmpPath = storage_path("app/temp_esig_{$ref}." . $ext);
    $image->move(dirname($tmpPath), basename($tmpPath));

    // ---------------------------------------------------------
    // STEP 3: FPDI import
    // ---------------------------------------------------------
    $signedPdfPath = storage_path("app/documents/outslip_{$ref}_e_signed.pdf");
    $pdf = new \setasign\Fpdi\Tcpdf\Fpdi();

    try {
      $pageCount = $pdf->setSourceFile($pdfPath);
    } catch (\Exception $e) {
      @unlink($tmpPath);
      return back()->with('error', '❌ Failed to read PDF.');
    }

    // ---------------------------------------------------------
    // STEP 4: Insert signature image (ONLY image)
    // ---------------------------------------------------------
    for ($page = 1; $page <= $pageCount; $page++) {

      $tplId = $pdf->importPage($page);
      $size  = $pdf->getTemplateSize($tplId);

      $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
      $pdf->useTemplate($tplId);

      if ($page == $pageCount) {

        // IMAGE SIZE
        $sigWidth  = 25;
        $sigHeight = 25;

        // LEFT COPY POSITION
        $leftX = 75;
        $sigY  = 59;

        // RIGHT COPY POSITION
        $rightX = 65 + 185;

        // LEFT SIGNATURE
        $pdf->Image($tmpPath, $leftX, $sigY, $sigWidth, $sigHeight);

        // RIGHT SIGNATURE
        $pdf->Image($tmpPath, $rightX, $sigY, $sigWidth, $sigHeight);
      }
    }

    // ---------------------------------------------------------
    // STEP 5: Save & cleanup
    // ---------------------------------------------------------
    $pdf->Output($signedPdfPath, 'F');
    $slip->status = 'Approved';
    $slip->updated_at = now();
    $slip->save();
    if (file_exists($tmpPath)) unlink($tmpPath);

    return response()->download($signedPdfPath);
  }
}
