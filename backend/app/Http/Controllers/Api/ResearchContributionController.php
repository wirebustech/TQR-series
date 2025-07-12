<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ResearchContribution;

class ResearchContributionController extends Controller
{
    public function index()
    {
        return response()->json(ResearchContribution::all());
    }

    public function show($id)
    {
        $contribution = ResearchContribution::findOrFail($id);
        return response()->json($contribution);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file_url' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'in:pending,approved,rejected',
        ]);
        $contribution = ResearchContribution::create($validated);
        return response()->json($contribution, 201);
    }

    public function update(Request $request, $id)
    {
        $contribution = ResearchContribution::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'file_url' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'status' => 'in:pending,approved,rejected',
        ]);
        $contribution->update($validated);
        return response()->json($contribution);
    }

    public function destroy($id)
    {
        $contribution = ResearchContribution::findOrFail($id);
        $contribution->delete();
        return response()->json(['message' => 'Research contribution deleted successfully.']);
    }
}
