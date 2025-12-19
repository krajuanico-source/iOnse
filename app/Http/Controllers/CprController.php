<?php

namespace App\Http\Controllers;

use App\Models\Cpr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CprActivatedNotificationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf; // 👈 Use this instead of PDF

class CprController extends Controller
{
  /**
   * Display a list of CPRs.
   */

  public function index()
  {
    $cprs = Cpr::with('employees.user')->get();
    $user = Auth::user();

    // ✅ Get employee_id from related employee table
    $employeeId = $user->employee?->employee_id ?? '';

    return view('forms.cpr.index', compact('cprs', 'employeeId'));
  }


  /**
   * Show modal/form to create a CPR.
   */
  public function create()
  {
    return view('forms.cpr.create');
  }

  /**
   * Store a new CPR record.
   */
  public function store(Request $request)
  {
    $validated = $request->validate([
      'rating_period_start' => 'required',
      'semester' => 'required',
    ]);

    Cpr::create([
      'rating_period_start' => substr($request->rating_period_start, 0, 7), // keeps only YYYY-MM
      'semester' => $request->semester,
      'status' => 'Pending',
    ]);

    return back()->with('success', 'CPR created successfully.');
  }

  public function update(Request $request, $id)
  {
    $validated = $request->validate([
      'rating_period_start' => 'required',
      'semester' => 'required',
      'status' => 'required',
    ]);

    $cpr = Cpr::findOrFail($id);

    $oldStatus = $cpr->status; // store previous status

    // Update CPR
    $cpr->update([
      'rating_period_start' => substr($request->rating_period_start, 0, 7), // fixes SQL error
      'semester'            => $request->semester,
      'status'              => $request->status
    ]);

    // ✅ Send email if status changed to Active
    if ($oldStatus !== 'Active' && $cpr->status === 'Active' && $cpr->requestor_id) {

      // Get the requestor user
      $requestor = $cpr->requestor; // assumes Cpr model has requestor() relation

      if ($requestor) {
        Mail::to($requestor->email)->send(
          new CprActivatedNotificationMail(
            $cpr,
            $requestor->name,
            $requestor->email
          )
        );
      }
    }

    return back()->with('success', 'CPR updated successfully.');
  }

  /**
   * Show a single CPR record.
   */
  public function show(Cpr $cpr)
  {
    return view('forms.cpr.show', compact('cpr'));
  }

  /**
   * Show form for editing a CPR.
   */
  public function edit(Cpr $cpr)
  {
    return view('forms.cpr.edit', compact('cpr'));
  }

  /**
   * Delete a CPR record.
   */
  public function destroy(Cpr $cpr)
  {
    $cpr->delete();

    return redirect()->route('forms.cpr.index')
      ->with('success', 'CPR deleted successfully.');
  }

  public function updateRatings(Cpr $cpr)
  {
    // Load related employees and their ratings
    $employees = $cpr->employees; // requires employees() relationship in Cpr model

    // Return a view (Blade) to update ratings
    return view('forms.cpr.update_ratings', compact('cpr', 'employees'));
  }

  public function employeeList()
  {
    $cprs = Cpr::with(['employees.user'])
      ->latest()
      ->get()
      ->map(function ($cpr) {
        // Filter only employees with rating or file
        $cpr->employees = $cpr->employees->filter(function ($emp) {
          return $emp->rating !== null || $emp->cpr_file !== null;
        });
        return $cpr;
      })
      ->filter(function ($cpr) {
        // Remove CPRs that now have no employees
        return $cpr->employees->isNotEmpty();
      });

    return view('forms.cpr.employee', compact('cprs'));
  }
  public function validateAndGenerate(Request $request, Cpr $cpr)
  {
    // ✅ Validate request
    $validated = $request->validate([
      'employee_id' => 'required|exists:users,id',
      'rating'      => 'required|numeric|min:0|max:100',
    ]);

    // ✅ Find CPR employee record
    $cprEmployee = $cpr->employees()
      ->where('employee_id', $validated['employee_id'])
      ->first();

    if (!$cprEmployee) {
      return response()->json([
        'error' => 'Employee not assigned to this CPR.'
      ], 404);
    }

    // ✅ Update rating AND status
    $cprEmployee->update([
      'rating' => $validated['rating'],
      'status' => 'Validated', // 🔥 set status as Validated
    ]);

    // ✅ Get USER (employee_id === users.id)
    $user = User::findOrFail($validated['employee_id']);

    // ✅ Generate PDF
    $pdf = Pdf::loadView('forms.cpr.certificate', [
      'cpr'      => $cpr,
      'employee' => $cprEmployee,
      'user'     => $user, // 🔥 first_name & last_name available
    ]);

    // ✅ Store PDF
    $path = "cpr_certificates/cpr_{$validated['employee_id']}.pdf";
    Storage::disk('public')->put($path, $pdf->output());

    return response()->json([
      'success' => true,
      'message' => 'CPR updated and validated successfully.',
      'pdf_url' => asset("storage/{$path}")
    ]);
  }
}
