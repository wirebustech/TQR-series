<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MediaLibrary;

class MediaLibraryController extends Controller
{
    // List all media assets
    public function index()
    {
        return response()->json(MediaLibrary::all());
    }

    // Show a single media asset
    public function show($id)
    {
        $media = MediaLibrary::findOrFail($id);
        return response()->json($media);
    }

    // Create a new media asset
    public function store(Request $request)
    {
        $validated = $request->validate([
            'file_name' => 'required|string|max:255',
            'file_path' => 'required|string|max:255',
            'file_type' => 'required|string|max:50',
            'file_size' => 'required|integer',
            'mime_type' => 'required|string|max:100',
            'alt_text' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'uploaded_by' => 'nullable|exists:users,id',
        ]);
        $media = MediaLibrary::create($validated);
        return response()->json($media, 201);
    }

    // Update a media asset
    public function update(Request $request, $id)
    {
        $media = MediaLibrary::findOrFail($id);
        $validated = $request->validate([
            'file_name' => 'sometimes|required|string|max:255',
            'file_path' => 'sometimes|required|string|max:255',
            'file_type' => 'sometimes|required|string|max:50',
            'file_size' => 'sometimes|required|integer',
            'mime_type' => 'sometimes|required|string|max:100',
            'alt_text' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'uploaded_by' => 'nullable|exists:users,id',
        ]);
        $media->update($validated);
        return response()->json($media);
    }

    // Delete a media asset
    public function destroy($id)
    {
        $media = MediaLibrary::findOrFail($id);
        $media->delete();
        return response()->json(['message' => 'Media asset deleted successfully.']);
    }
}
