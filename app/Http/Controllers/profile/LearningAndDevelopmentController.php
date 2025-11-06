<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LearningAndDevelopment;
use Illuminate\Support\Facades\Auth;

class LearningAndDevelopmentController extends Controller
{
    public function index()
    {
        $trainings = LearningAndDevelopment::where('user_id', Auth::id())->get();
        return view('content.profile.learning-development', compact('trainings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'inclusive_date_from' => 'required|date',
            'inclusive_date_to' => 'nullable|date',
            'number_of_hours' => 'nullable|integer',
            'type_of_ld' => 'nullable|string|max:255',
            'conducted_by' => 'nullable|string|max:255',
        ]);

        LearningAndDevelopment::create($request->all() + ['user_id' => Auth::id()]);

        return response()->json(['message' => 'Training added successfully']);
    }

    public function update(Request $request, $id)
    {
        $training = LearningAndDevelopment::findOrFail($id);
        $training->update($request->all());

        return response()->json(['message' => 'Training updated successfully']);
    }

    public function destroy($id)
    {
        LearningAndDevelopment::findOrFail($id)->delete();

        return response()->json(['message' => 'Training deleted successfully']);
    }
}
