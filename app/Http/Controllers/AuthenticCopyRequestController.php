<?php

namespace App\Http\Controllers;

use App\Models\AuthenticCopyRequest;
use App\Models\Cpr;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\CprActivationRequestMail;

class AuthenticCopyRequestController extends Controller
{
    /* =========================
        LIST REQUESTS
    ========================== */
    public function index()
    {
        $requests = AuthenticCopyRequest::with(['user', 'cprs'])->latest()->get();
        return view('forms.cprrequest.index', compact('requests'));
    }

    /* =========================
        UPDATE STATUS (NO APPROVE)
    ========================== */
    public function updateStatus(Request $request, AuthenticCopyRequest $authenticCopyRequest)
    {
        $request->validate([
            'status' => 'required|in:Pending,For Signature,Rejected',
        ]);

        $authenticCopyRequest->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Status updated.');
    }

    /* =========================
        DIGITAL SIGN
    ========================== */
    public function digitalSign(Request $request, $id)
    {
        $req = AuthenticCopyRequest::with(['user', 'cprs'])->findOrFail($id);

        if ($req->status !== 'For Signature') {
            return back()->with('error', 'Request not ready for signing.');
        }

        // Prepare ratings
        $ratings = $req->cprs->map(fn ($cpr) => [
            'cpr_id' => $cpr->id,
            'rating' => $cpr->pivot->rating,
        ]);

        // Generate PDF
        $pdf = Pdf::loadView('pdf.authentic-copy', [
            'request' => $req,
            'ratings' => $ratings,
            'signature' => $request->signature ?? null,
        ]);

        Storage::disk('public')->makeDirectory('authentic-copies');

        $path = 'authentic-copies/authentic_copy_' . $req->id . '_signed.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        // Approve + save
        $req->update([
            'pdf_path' => $path,
            'status' => 'Approved',
        ]);

        // Send email
        Mail::to($req->user->email)->send(
            new CprActivationRequestMail($req->cprs->first(), $req->user, $ratings)
        );

        return back()->with('success', 'Request signed and approved.');
    }

    /* =========================
        WET SIGN
    ========================== */
    public function wetSign(Request $request, $id)
    {
        $req = AuthenticCopyRequest::with(['user', 'cprs'])->findOrFail($id);

        if ($req->status !== 'For Signature') {
            return back()->with('error', 'Request not ready for signing.');
        }

        $request->validate([
            'signature_image' => 'required|image|max:2048',
        ]);

        $signaturePath = $request->file('signature_image')
            ->store('signatures', 'public');

        $ratings = $req->cprs->map(fn ($cpr) => [
            'cpr_id' => $cpr->id,
            'rating' => $cpr->pivot->rating,
        ]);

        $pdf = Pdf::loadView('pdf.authentic-copy', [
            'request' => $req,
            'ratings' => $ratings,
            'signature_image' => $signaturePath,
        ]);

        Storage::disk('public')->makeDirectory('authentic-copies');

        $path = 'authentic-copies/authentic_copy_' . $req->id . '_signed.pdf';
        Storage::disk('public')->put($path, $pdf->output());

        $req->update([
            'pdf_path' => $path,
            'status' => 'Approved',
        ]);

        Mail::to($req->user->email)->send(
            new CprActivationRequestMail($req->cprs->first(), $req->user, $ratings)
        );

        return back()->with('success', 'Request signed and approved.');
    }

    /* =========================
        MARK AS CLAIMED
    ========================== */
    public function markClaimed($id)
    {
        $req = AuthenticCopyRequest::findOrFail($id);

        if ($req->status !== 'Approved') {
            return back()->with('error', 'Only approved requests can be claimed.');
        }

        $req->update(['status' => 'Claimed']);

        return back()->with('success', 'Request marked as claimed.');
    }

    /* =========================
        DOWNLOAD SIGNED PDF
    ========================== */
 public function downloadSignedPdf($id)
{
    $req = AuthenticCopyRequest::findOrFail($id);

    if (!$req->pdf_path || !Storage::disk('public')->exists($req->pdf_path)) {
        return back()->with('error', 'Signed PDF not found.');
    }

    $fullPath = storage_path('app/public/' . $req->pdf_path);

    return response()->download($fullPath, basename($fullPath));
}

}
