<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reference;
use Illuminate\Support\Facades\Auth;

class ReferenceController extends Controller
{
    public function index()
    {
        $references = Reference::where('user_id', Auth::id())->get();
        return view('content.profile.references', compact('references'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
        ]);

        Reference::create($request->all() + ['user_id' => Auth::id()]);

        return response()->json(['message' => 'Reference added successfully']);
    }

    public function update(Request $request, $id)
    {
        $reference = Reference::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
        ]);

        $reference->update($request->all());

        return response()->json(['message' => 'Reference updated successfully']);
    }

    public function destroy($id)
    {
        Reference::findOrFail($id)->delete();
        return response()->json(['message' => 'Reference deleted successfully']);
    }
}
