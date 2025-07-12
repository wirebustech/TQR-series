<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExternalVideo;

class ExternalVideoController extends Controller
{
    public function index()
    {
        return response()->json(ExternalVideo::all());
    }

    public function show($id)
    {
        $video = ExternalVideo::findOrFail($id);
        return response()->json($video);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'platform' => 'required|string|max:100',
            'video_url' => 'required|string|max:255',
            'thumbnail_url' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'view_count' => 'nullable|integer',
        ]);
        $video = ExternalVideo::create($validated);
        return response()->json($video, 201);
    }

    public function update(Request $request, $id)
    {
        $video = ExternalVideo::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'platform' => 'sometimes|required|string|max:100',
            'video_url' => 'sometimes|required|string|max:255',
            'thumbnail_url' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'view_count' => 'nullable|integer',
        ]);
        $video->update($validated);
        return response()->json($video);
    }

    public function destroy($id)
    {
        $video = ExternalVideo::findOrFail($id);
        $video->delete();
        return response()->json(['message' => 'External video deleted successfully.']);
    }
}
