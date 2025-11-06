<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VoluntaryWork;
use Illuminate\Support\Facades\Auth;

class VoluntaryWorkController extends Controller
{
    public function index()
    {
        $voluntaryWorks = VoluntaryWork::where('user_id', Auth::id())->get();
        return view('content.profile.voluntary-work', compact('voluntaryWorks'));
    }

    public function store(Request $request)
    {
        // If you have a validation rule, use $request->validate([...])
        VoluntaryWork::create($request->all() + ['user_id' => Auth::id()]);
        return response()->json(['message' => 'Voluntary work added successfully']);
    }

    public function update(Request $request, $id)
    {
        $work = VoluntaryWork::findOrFail($id);
        $work->update($request->all());
        return response()->json(['message' => 'Voluntary work updated successfully']);
    }

    public function destroy($id)
    {
        VoluntaryWork::findOrFail($id)->delete();
        return response()->json(['message' => 'Voluntary work deleted successfully']);
    }
}
