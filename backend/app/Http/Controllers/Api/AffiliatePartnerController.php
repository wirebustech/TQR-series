<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AffiliatePartner;

class AffiliatePartnerController extends Controller
{
    public function index()
    {
        return response()->json(AffiliatePartner::all());
    }

    public function show($id)
    {
        $partner = AffiliatePartner::findOrFail($id);
        return response()->json($partner);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'website' => 'nullable|string|max:255',
            'logo' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $partner = AffiliatePartner::create($validated);
        return response()->json($partner, 201);
    }

    public function update(Request $request, $id)
    {
        $partner = AffiliatePartner::findOrFail($id);
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'website' => 'nullable|string|max:255',
            'logo' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $partner->update($validated);
        return response()->json($partner);
    }

    public function destroy($id)
    {
        $partner = AffiliatePartner::findOrFail($id);
        $partner->delete();
        return response()->json(['message' => 'Affiliate partner deleted successfully.']);
    }
}
