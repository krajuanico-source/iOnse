<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Module;

class ModuleController extends Controller
{
    // List all modules
    public function index()
    {
        $modules = Module::all();
        return view('content.planning.modules.index', compact('modules'));
    }

    // Create a new module
    public function store(Request $request)
    {
        $this->authorize('create.module'); // enforce permission

        $request->validate([
            'name' => 'required|string|unique:modules,name',
            'slug' => 'required|string|unique:modules,slug',
        ]);

        Module::create($request->only('name', 'slug'));
        return redirect()->back()->with('success', 'Module created successfully!');
    }

    // Update a module
    public function update(Request $request, $id)
    {
        $this->authorize('edit.module'); // enforce permission

        $module = Module::findOrFail($id);

        $request->validate([
            'name' => 'required|string|unique:modules,name,' . $id,
            'slug' => 'required|string|unique:modules,slug,' . $id,
        ]);

        $module->update($request->only('name', 'slug'));
        return redirect()->back()->with('success', 'Module updated successfully!');
    }
}
