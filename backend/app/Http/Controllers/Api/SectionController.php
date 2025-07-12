<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;

class SectionController extends Controller
{
    // List all sections
    public function index()
    {
        return response()->json(Section::all());
    }

    // Show a single section
    public function show($id)
    {
        $section = Section::findOrFail($id);
        return response()->json($section);
    }

    // Create a new section
    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_id' => 'required|exists:pages,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'content' => 'nullable|string',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);
        $section = Section::create($validated);
        return response()->json($section, 201);
    }

    // Update a section
    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);
        $validated = $request->validate([
            'page_id' => 'sometimes|required|exists:pages,id',
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:100',
            'content' => 'nullable|string',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);
        $section->update($validated);
        return response()->json($section);
    }

    // Delete a section
    public function destroy($id)
    {
        $section = Section::findOrFail($id);
        $section->delete();
        return response()->json(['message' => 'Section deleted successfully.']);
    }
}
