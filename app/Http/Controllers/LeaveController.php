<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Make sure barryvdh/laravel-dompdf is installed

class LeaveController extends Controller
{
  // 1️⃣ List all leaves
  public function index()
  {
    $leaves = Leave::with('employee')->latest()->get();
    return view('forms.leaves.index', compact('leaves'));
  }

  // 2️⃣ Show create leave form
  public function create()
  {
    $employees = User::all();
    return view('forms.leaves.create', compact('employees'));
  }

  // 3️⃣ Store a new leave
  public function store(Request $request)
  {
    $validated = $request->validate([
      'empid' => 'required',
      'leave_type' => 'required|string',
      'leave_type_specify' => 'nullable|string',

      // Vacation
      'leave_spent_vac_specify' => 'nullable|string',
      'abroad_specify' => 'nullable|string',

      // Sick
      'leave_spent_sick_specify' => 'nullable|string',
      'hospital_specify' => 'nullable|string',
      'at_home_specify' => 'nullable|string',

      // Study
      'leave_study_specify' => 'nullable|string',

      'leave_no_wdays' => 'required|numeric',
      'from_date' => 'required|date',
      'to_date' => 'required|date|after_or_equal:from_date',
      'other_purpose' => 'nullable|string',
      'datefiled' => 'required|date',
    ]);

    // Map leave_spent and leave_specify dynamically based on type
    $leaveData = $validated;

    switch ($validated['leave_type']) {
      case 'Vacation':
      case 'Special Privilege':
        $leaveData['leave_spent'] = $validated['leave_spent_vac_specify'] ?? null;
        $leaveData['leave_specify'] = $validated['abroad_specify'] ?? null;
        break;

      case 'Sick':
        $leaveData['leave_spent'] = $validated['leave_spent_sick_specify'] ?? null;
        $leaveData['leave_specify'] = $validated['hospital_specify'] ?? $validated['at_home_specify'] ?? null;
        break;

      case 'Study':
        $leaveData['leave_spent'] = $validated['leave_study_specify'] ?? null;
        $leaveData['leave_specify'] = null;
        break;

      case 'Others':
        $leaveData['leave_spent'] = null;
        $leaveData['leave_specify'] = $validated['leave_type_specify'] ?? null;
        break;

      default:
        $leaveData['leave_spent'] = null;
        $leaveData['leave_specify'] = null;
    }

    // Store in database
    Leave::create($leaveData);

    return redirect()->route('forms.leaves.index')->with('success', 'Leave applied successfully.');
  }


  // 4️⃣ Show a single leave
  public function show($id)
  {
    $leave = Leave::with('employee')->findOrFail($id);
    return view('forms.leaves.show', compact('leave'));
  }

  // 5️⃣ Show edit form
  public function edit($id)
  {
    $leave = Leave::findOrFail($id);
    $employees = User::all();
    return view('forms.leaves.edit', compact('leave', 'employees'));
  }

  // 6️⃣ Update leave with all fields
  public function update(Request $request, $id)
  {
    $leave = Leave::findOrFail($id);

    // Validate input
    $validated = $request->validate([
      'empid' => 'required',
      'leave_type' => 'required|string',
      'leave_type_specify' => 'nullable|string',
      'leave_no_wdays' => 'required|numeric',
      'from_date' => 'required|date',
      'to_date' => 'required|date|after_or_equal:from_date',
      'other_purpose' => 'nullable|string',
      // Optional fields depending on leave type
      'leave_spent_vac_specify' => 'nullable|string',
      'abroad_specify' => 'nullable|string',
      'leave_spent_sick_specify' => 'nullable|string',
      'hospital_specify' => 'nullable|string',
      'leave_study_specify' => 'nullable|string',
    ]);

    // Map extra fields to the database fields
    $leave->empid = $validated['empid'];
    $leave->leave_type = $validated['leave_type'];
    $leave->leave_type_specify = $validated['leave_type_specify'] ?? null;
    $leave->from_date = $validated['from_date'];
    $leave->to_date = $validated['to_date'];
    $leave->leave_no_wdays = $validated['leave_no_wdays'];
    $leave->other_purpose = $validated['other_purpose'] ?? null;

    // Handle Vacation / Special Privilege Leave
    if (in_array($validated['leave_type'], ['Vacation', 'Special Privilege'])) {
      $leave->leave_spent = $validated['leave_spent_vac_specify'] ?? null;
      $leave->leave_specify = $validated['abroad_specify'] ?? null;
    }

    // Handle Sick Leave
    if ($validated['leave_type'] === 'Sick') {
      $leave->leave_spent = $validated['leave_spent_sick_specify'] ?? null;
      $leave->leave_specify = $validated['hospital_specify'] ?? null;
    }

    // Handle Study Leave
    if ($validated['leave_type'] === 'Study') {
      $leave->leave_spent = $validated['leave_study_specify'] ?? null;
      $leave->leave_specify = null;
    }

    $leave->save();

    // Redirect with success message
    return redirect()->route('forms.leaves.index')->with('success', 'Leave updated successfully.');
  }

  // 7️⃣ Delete leave
  public function destroy($id)
  {
    $leave = Leave::findOrFail($id);
    $leave->delete();
    return redirect()->route('forms.leaves.index')->with('success', 'Leave deleted successfully.');
  }

  // 8️⃣ Approve leave
  public function approve($id)
  {
    $leave = Leave::findOrFail($id);
    $leave->status = 'Approved';
    $leave->leave_approved_by = Auth::id();
    $leave->date_approved = now();
    $leave->save();

    return redirect()->route('forms.leaves.index')->with('success', 'Leave approved.');
  }

  // 9️⃣ Reject leave
  public function reject(Request $request, $id)
  {
    $leave = Leave::findOrFail($id);
    $leave->status = 'Rejected';
    $leave->leave_remarks = $request->leave_remarks ?? 'No remarks';
    $leave->leave_approved_by = Auth::id();
    $leave->date_approved = now();
    $leave->save();

    return redirect()->route('forms.leaves.index')->with('success', 'Leave rejected.');
  }

  public function print($id)
  {
    $leave = Leave::with([
      'employee.division',
      'employee.section',
      'employee.position'
    ])->findOrFail($id);

    // If leave is approved, open signed PDF
    if ($leave->status === 'Approved') {
      $signedPdfPath  = storage_path("app/documents/leave_{$id}_signed.pdf");
      $eSignedPdfPath = storage_path("app/documents/leave_{$id}_e_signed.pdf");

      if (file_exists($signedPdfPath)) {
        $pdfPath = $signedPdfPath;
      } elseif (file_exists($eSignedPdfPath)) {
        $pdfPath = $eSignedPdfPath;
      } else {
        return back()->with('error', 'Signed PDF not found.');
      }

      return response()->file($pdfPath);
    }

    // If leave is pending, render the Blade view
    $status       = \App\Models\EmploymentStatus::all();
    $divisions    = \App\Models\Division::all();
    $sections     = \App\Models\Section::all();
    $salaryGrades = \App\Models\SalaryGrade::all();
    $positions    = \App\Models\Position::all();

    return view('forms.leaves.print', compact(
      'leave',
      'status',
      'divisions',
      'sections',
      'salaryGrades',
      'positions'
    ));
  }


  public function digitalSignImage(Request $request, $leave_no)
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
    $slip = Leave::with(['employee.division', 'employee.section', 'employee.employmentStatus'])->find($leave_no);

    if (!$slip) {
      return back()->with('error', "❌ Leave #{$leave_no} not found.");
    }

    // Step 3: Generate unsigned PDF (if not existing)
    $pdfPath = storage_path("app/documents/leave_{$leave_no}.pdf");

    if (!file_exists($pdfPath)) {
      try {
        $pdf = Pdf::loadView('forms.leaves.pdf', compact('slip'))
          ->setPaper('legal', 'portrait');
        $pdf->save($pdfPath);
      } catch (\Exception $e) {
        return back()->with('error', '❌ Failed to generate base PDF: ' . $e->getMessage());
      }
    }

    // Step 4: Sign PDF with FPDI/TCPDF
    $signedPdfPath = storage_path("app/documents/leave_{$leave_no}_signed.pdf");

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
        $leftX = 35;   // signature image X
        $sigY  = 236;   // same Y for image and text base

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
      }

      $pdfFpdi->Output($signedPdfPath, 'F');
      $slip->status = 'Approved';
      $slip->date_approved = now();
      $slip->save();
    } catch (\Exception $e) {
      return back()->with('error', '❌ Failed to sign PDF: ' . $e->getMessage());
    }

    // Step 5: Download signed PDF
    return response()->download($signedPdfPath, "Leave_{$leave_no}_signed.pdf");
  }

  public function electronicSignImage(Request $request, $leave_no)
  {
    $request->validate([
      'signature_image' => 'required|image|mimes:png,jpg,jpeg|max:4096',
    ]);

    $image = $request->file('signature_image');

    // ---------------------------------------------------------
    // STEP 1: Ensure base PDF exists
    // ---------------------------------------------------------
    $pdfPath = storage_path("app/documents/leave_{$leave_no}.pdf");

    if (!file_exists($pdfPath) || filesize($pdfPath) === 0) {

      $leave = Leave::with([
        'employee.division',
        'employee.section',
        'employee.employmentStatus'
      ])->find($leave_no);

      if (!$leave) {
        return back()->with('error', '❌ leave not found. Cannot generate PDF.');
      }

      try {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('forms.leaves.pdf', compact('leave'))
          ->setPaper('legal', 'portrait');

        $pdf->save($pdfPath);
      } catch (\Exception $e) {
        return back()->with('error', '❌ Failed to generate PDF.');
      }
    }

    // ---------------------------------------------------------
    // STEP 2: Temporary signature file
    // ---------------------------------------------------------
    $ext = $image->getClientOriginalExtension();
    $tmpPath = storage_path("app/temp_esig_{$leave_no}." . $ext);
    $image->move(dirname($tmpPath), basename($tmpPath));

    // ---------------------------------------------------------
    // STEP 3: FPDI import
    // ---------------------------------------------------------
    $signedPdfPath = storage_path("app/documents/leave_{$leave_no}_e_signed.pdf");
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
    $leave->status = 'Approved';
    $leave->date_approved = now();
    $leave->save();

    if (file_exists($tmpPath)) unlink($tmpPath);

    return response()->download($signedPdfPath);
  }
}
