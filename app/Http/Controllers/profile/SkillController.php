<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Skill::where('user_id', Auth::id())->latest()->get();
            return datatables()->of($data)->make(true);
        }

        $skills = Skill::where('user_id', Auth::id())->latest()->get();
        return view('content.profile.skills', compact('skills'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'skill_name' => 'required|string|max:255',
        ]);

        Skill::create($data + ['user_id' => Auth::id()]);

        return response()->json(['message' => 'Skill added successfully']);
    }

    public function show($id)
    {
        $skill = Skill::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($skill);
    }

    public function update(Request $request, $id)
    {
        $skill = Skill::where('user_id', Auth::id())->findOrFail($id);

        $data = $request->validate([
            'skill_name' => 'required|string|max:255',
        ]);

        $skill->update($data);

        return response()->json(['message' => 'Skill updated successfully']);
    }

    public function destroy($id)
    {
        Skill::where('user_id', Auth::id())->findOrFail($id)->delete();
        return response()->json(['message' => 'Skill deleted successfully']);
    }
}
