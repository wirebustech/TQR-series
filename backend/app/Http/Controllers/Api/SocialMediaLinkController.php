<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SocialMediaLink;

class SocialMediaLinkController extends Controller
{
    public function index()
    {
        return response()->json(SocialMediaLink::all());
    }

    public function show($id)
    {
        $link = SocialMediaLink::findOrFail($id);
        return response()->json($link);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|string|max:100',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
        $link = SocialMediaLink::create($validated);
        return response()->json($link, 201);
    }

    public function update(Request $request, $id)
    {
        $link = SocialMediaLink::findOrFail($id);
        $validated = $request->validate([
            'platform' => 'sometimes|required|string|max:100',
            'url' => 'sometimes|required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);
        $link->update($validated);
        return response()->json($link);
    }

    public function destroy($id)
    {
        $link = SocialMediaLink::findOrFail($id);
        $link->delete();
        return response()->json(['message' => 'Social media link deleted successfully.']);
    }
}
