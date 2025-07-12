<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportDonation;

class SupportDonationController extends Controller
{
    public function index()
    {
        return response()->json(SupportDonation::all());
    }

    public function show($id)
    {
        $donation = SupportDonation::findOrFail($id);
        return response()->json($donation);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'amount' => 'required|numeric',
            'currency' => 'string|max:10',
            'payment_method' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'donor_name' => 'nullable|string|max:255',
            'donor_email' => 'nullable|email|max:255',
            'message' => 'nullable|string',
            'status' => 'in:pending,completed,failed',
        ]);
        $donation = SupportDonation::create($validated);
        return response()->json($donation, 201);
    }

    public function update(Request $request, $id)
    {
        $donation = SupportDonation::findOrFail($id);
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'amount' => 'sometimes|required|numeric',
            'currency' => 'string|max:10',
            'payment_method' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
            'donor_name' => 'nullable|string|max:255',
            'donor_email' => 'nullable|email|max:255',
            'message' => 'nullable|string',
            'status' => 'in:pending,completed,failed',
        ]);
        $donation->update($validated);
        return response()->json($donation);
    }

    public function destroy($id)
    {
        $donation = SupportDonation::findOrFail($id);
        $donation->delete();
        return response()->json(['message' => 'Support donation deleted successfully.']);
    }
}
