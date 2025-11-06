<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\NonAcademic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NonAcademicController extends Controller
{
    public function index(Request $request)
    {
        // ✅ If AJAX request → return JSON for DataTables
        if ($request->ajax()) {
            $data = NonAcademic::where('user_id', Auth::id())->latest()->get();
            return datatables()->of($data)->make(true);
        }

        // 👇 Otherwise, pass $nonAcademics to the Blade view
        $nonAcademics = NonAcademic::where('user_id', Auth::id())->latest()->get();
        return view('content.profile.non-academic', compact('nonAcademics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'recognition' => 'required|string|max:255',
        ]);

        NonAcademic::create($data + ['user_id' => Auth::id()]);

        return response()->json(['message' => 'Non-Academic Distinction added successfully']);
    }

    public function show($id)
    {
        $nonAcademic = NonAcademic::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($nonAcademic);
    }

    public function update(Request $request, $id)
    {
        $nonAcademic = NonAcademic::where('user_id', Auth::id())->findOrFail($id);

        $data = $request->validate([
            'recognition' => 'required|string|max:255',
        ]);

        $nonAcademic->update($data);

        return response()->json(['message' => 'Non-Academic Distinction updated successfully']);
    }

    public function destroy($id)
    {
        NonAcademic::where('user_id', Auth::id())->findOrFail($id)->delete();
        return response()->json(['message' => 'Non-Academic Distinction deleted successfully']);
    }
}
