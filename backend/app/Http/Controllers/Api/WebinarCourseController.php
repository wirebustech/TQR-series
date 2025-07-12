<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebinarCourse;

class WebinarCourseController extends Controller
{
    // List all webinars/courses
    public function index()
    {
        return response()->json(WebinarCourse::all());
    }

    // Show a single webinar/course
    public function show($id)
    {
        $webinar = WebinarCourse::findOrFail($id);
        return response()->json($webinar);
    }

    // Create a new webinar/course
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:webinar_courses',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:50',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'video_url' => 'nullable|string|max:255',
            'thumbnail_url' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
        $webinar = WebinarCourse::create($validated);
        return response()->json($webinar, 201);
    }

    // Update a webinar/course
    public function update(Request $request, $id)
    {
        $webinar = WebinarCourse::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'slug' => 'sometimes|required|string|max:255|unique:webinar_courses,slug,' . $id,
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:50',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            'video_url' => 'nullable|string|max:255',
            'thumbnail_url' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
        $webinar->update($validated);
        return response()->json($webinar);
    }

    // Delete a webinar/course
    public function destroy($id)
    {
        $webinar = WebinarCourse::findOrFail($id);
        $webinar->delete();
        return response()->json(['message' => 'Webinar/Course deleted successfully.']);
    }
}
