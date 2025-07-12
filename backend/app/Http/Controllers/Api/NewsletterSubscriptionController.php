<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsletterSubscription;

class NewsletterSubscriptionController extends Controller
{
    public function index()
    {
        return response()->json(NewsletterSubscription::all());
    }

    public function show($id)
    {
        $subscription = NewsletterSubscription::findOrFail($id);
        return response()->json($subscription);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions',
            'name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'subscribed_at' => 'nullable|date',
            'unsubscribed_at' => 'nullable|date',
        ]);
        $subscription = NewsletterSubscription::create($validated);
        return response()->json($subscription, 201);
    }

    public function update(Request $request, $id)
    {
        $subscription = NewsletterSubscription::findOrFail($id);
        $validated = $request->validate([
            'email' => 'sometimes|required|email|unique:newsletter_subscriptions,email,' . $id,
            'name' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'subscribed_at' => 'nullable|date',
            'unsubscribed_at' => 'nullable|date',
        ]);
        $subscription->update($validated);
        return response()->json($subscription);
    }

    public function destroy($id)
    {
        $subscription = NewsletterSubscription::findOrFail($id);
        $subscription->delete();
        return response()->json(['message' => 'Newsletter subscription deleted successfully.']);
    }
}
