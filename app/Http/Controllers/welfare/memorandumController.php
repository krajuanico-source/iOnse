<?php

namespace App\Http\Controllers\Welfare;

use Illuminate\Http\Request;
use App\Models\Welfare\Memorandum;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Js;


class MemorandumController extends Controller
{
    public function index(Request $request)
    {
        $query = Memorandum::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('issuance_number', 'like', '%' . $request->search . '%')
                  ->orWhere('subject', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by award_type
        if ($request->has('award_type') && $request->award_type != 'all') {
            $query->where('award_type', $request->award_type);
        }


        $memorandums = $query->latest()->paginate(10);

        // 👇 Add the search and award_type to the view data
        $search = $request->search ?? '';
        $award_type = $request->award_type ?? 'all';
        $award_types = ['all' => 'All', 'character' => 'Character', 'praise' => 'Praise'];


        return view('content.welfare.memorandum', compact('memorandums'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'issuance_number' => 'required|string|max:255', 
            'subject' => 'required|string',
            'award_type' => 'required|in:character,praise',
            'date_of_issuance' => 'required|date',
            'file' => 'required|file|mimes:pdf|max:10240',
            'notes' => 'nullable|string'
        ]);

        // Store with original filename only
        $originalName = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs('memorandums', $originalName, 'public');

        Memorandum::create([
            'issuance_number' => $validated['issuance_number'],
            'subject' => $validated['subject'],
            'award_type' => $validated['award_type'],
            'date_of_issuance' => $validated['date_of_issuance'],
            'file_path' => $path,
            'file_version' => '1.0',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('welfare.memorandum')->with('success', 'Memorandum Added Successfully.');
    }


    // ✅ Add the update() method here
    public function update(Request $request, $id)
            {
                $request->validate([
                    'issuance_number' => 'required|string|max:255',
                    'subject' => 'required|string|max:255',
                    'award_type' => 'required|string',
                    'date_of_issuance' => 'required|date',
                    'notes' => 'nullable|string',
                    'file' => 'nullable|file|mimes:pdf|max:10240',
                ]);

                $memorandum = Memorandum::findOrFail($id);

                // Store original values for comparison
                $originalData = $memorandum->toArray();

                // Update fields
                $memorandum->issuance_number = $request->issuance_number;
                $memorandum->subject = $request->subject;
                $memorandum->award_type = $request->award_type;
                $memorandum->date_of_issuance = $request->date_of_issuance;
                $memorandum->notes = $request->notes;

                $fileChanged = false;

                if ($request->hasFile('file')) {
                    if ($memorandum->file_path && Storage::disk('public')->exists($memorandum->file_path)) {
                        Storage::disk('public')->delete($memorandum->file_path);
                    }

                    $filename = time() . '_' . $request->file('file')->getClientOriginalName();
                    $path = $request->file('file')->storeAs('memorandums', $filename, 'public');
                    $memorandum->file_path = $path;
                    $fileChanged = true;
                }

                // Check if anything has changed
                if (!$memorandum->isDirty() && !$fileChanged) {
                    return redirect()->back()->with('warning', 'No changes detected.');
                }

                $memorandum->save();

                return redirect()->route('welfare.memorandum')->with('success', 'Memorandum Updated Successfully.');
            }


    public function destroy($id)
    {
        $memorandum = Memorandum::findOrFail($id);
        $memorandum->delete();

        return redirect()->route('welfare.memorandum')->with('success', 'Memorandum Deleted Successfully.');
    }
}