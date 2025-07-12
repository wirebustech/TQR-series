<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BetaSignup;

class BetaSignupController extends Controller
{
    public function index()
    {
        return response()->json(BetaSignup::all());
    }

    public function show($id)
    {
        $signup = BetaSignup::findOrFail($id);
        return response()->json($signup);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:beta_signups',
            'organization' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'preferences' => 'nullable|string',
            'priority_access' => 'boolean',
            'confirmed_at' => 'nullable|date',
        ]);
        $signup = BetaSignup::create($validated);
        return response()->json($signup, 201);
    }

    public function update(Request $request, $id)
    {
        $signup = BetaSignup::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:beta_signups,email,' . $id,
            'organization' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'preferences' => 'nullable|string',
            'priority_access' => 'boolean',
            'confirmed_at' => 'nullable|date',
        ]);
        $signup->update($validated);
        return response()->json($signup);
    }

    public function destroy($id)
    {
        $signup = BetaSignup::findOrFail($id);
        $signup->delete();
        return response()->json(['message' => 'Beta signup deleted successfully.']);
    }
}
