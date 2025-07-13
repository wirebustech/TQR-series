<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use App\Models\Webinar;
use App\Models\SupportDonation;
use App\Models\User;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create payment intent for webinar registration
     */
    public function createWebinarPayment(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'webinar_id' => 'required|exists:webinars,id',
                'payment_method_id' => 'required|string',
                'amount' => 'required|numeric|min:0.50',
                'currency' => 'string|in:usd,eur,gbp|default:usd'
            ]);

            $webinar = Webinar::findOrFail($validated['webinar_id']);
            $user = $request->user();

            // Check if user already registered
            if ($webinar->registrations()->where('user_id', $user->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already registered for this webinar'
                ], 400);
            }

            // Create or get customer
            $customer = $this->getOrCreateCustomer($user);

            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $validated['amount'] * 100, // Convert to cents
                'currency' => $validated['currency'] ?? 'usd',
                'customer' => $customer->id,
                'payment_method' => $validated['payment_method_id'],
                'confirmation_method' => 'manual',
                'confirm' => true,
                'metadata' => [
                    'webinar_id' => $webinar->id,
                    'user_id' => $user->id,
                    'type' => 'webinar_registration'
                ],
                'description' => "Registration for: {$webinar->title}"
            ]);

            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'] ?? 'usd',
                'payment_method' => 'stripe',
                'payment_intent_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
                'metadata' => [
                    'webinar_id' => $webinar->id,
                    'webinar_title' => $webinar->title
                ]
            ]);

            return response()->json([
                'success' => true,
                'payment_intent' => $paymentIntent,
                'payment_id' => $payment->id
            ]);

        } catch (CardException $e) {
            Log::error('Payment card error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Payment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed'
            ], 500);
        }
    }

    /**
     * Create donation payment
     */
    public function createDonation(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|numeric|min:1',
                'currency' => 'string|in:usd,eur,gbp|default:usd',
                'payment_method_id' => 'required|string',
                'donor_name' => 'nullable|string|max:255',
                'donor_email' => 'nullable|email|max:255',
                'message' => 'nullable|string',
                'anonymous' => 'boolean'
            ]);

            $user = $request->user();

            // Create or get customer
            $customer = $this->getOrCreateCustomer($user);

            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $validated['amount'] * 100,
                'currency' => $validated['currency'] ?? 'usd',
                'customer' => $customer->id,
                'payment_method' => $validated['payment_method_id'],
                'confirmation_method' => 'manual',
                'confirm' => true,
                'metadata' => [
                    'user_id' => $user->id,
                    'type' => 'donation',
                    'anonymous' => $validated['anonymous'] ?? false
                ],
                'description' => 'Donation to TQRS'
            ]);

            // Create donation record
            $donation = SupportDonation::create([
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'] ?? 'usd',
                'payment_method' => 'stripe',
                'transaction_id' => $paymentIntent->id,
                'donor_name' => $validated['anonymous'] ? 'Anonymous' : ($validated['donor_name'] ?? $user->name),
                'donor_email' => $validated['donor_email'] ?? $user->email,
                'message' => $validated['message'],
                'status' => $paymentIntent->status === 'succeeded' ? 'completed' : 'pending'
            ]);

            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'currency' => $validated['currency'] ?? 'usd',
                'payment_method' => 'stripe',
                'payment_intent_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
                'metadata' => [
                    'donation_id' => $donation->id,
                    'type' => 'donation'
                ]
            ]);

            return response()->json([
                'success' => true,
                'payment_intent' => $paymentIntent,
                'donation_id' => $donation->id,
                'payment_id' => $payment->id
            ]);

        } catch (CardException $e) {
            Log::error('Donation payment error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Donation failed: ' . $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Donation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Donation processing failed'
            ], 500);
        }
    }

    /**
     * Create subscription for premium content
     */
    public function createSubscription(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'plan_id' => 'required|string',
                'payment_method_id' => 'required|string',
                'plan_type' => 'required|string|in:monthly,yearly'
            ]);

            $user = $request->user();

            // Check if user already has active subscription
            if ($user->subscriptions()->where('status', 'active')->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have an active subscription'
                ], 400);
            }

            // Create or get customer
            $customer = $this->getOrCreateCustomer($user);

            // Attach payment method to customer
            $customer->attachPaymentMethod($validated['payment_method_id']);

            // Create subscription
            $subscription = Subscription::create([
                'customer' => $customer->id,
                'items' => [['price' => $validated['plan_id']]],
                'payment_behavior' => 'default_incomplete',
                'payment_settings' => ['save_default_payment_method' => 'on_subscription'],
                'expand' => ['latest_invoice.payment_intent'],
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_type' => $validated['plan_type']
                ]
            ]);

            // Create subscription record
            $user->subscriptions()->create([
                'stripe_subscription_id' => $subscription->id,
                'plan_id' => $validated['plan_id'],
                'plan_type' => $validated['plan_type'],
                'status' => $subscription->status,
                'current_period_start' => date('Y-m-d H:i:s', $subscription->current_period_start),
                'current_period_end' => date('Y-m-d H:i:s', $subscription->current_period_end)
            ]);

            return response()->json([
                'success' => true,
                'subscription' => $subscription,
                'client_secret' => $subscription->latest_invoice->payment_intent->client_secret
            ]);

        } catch (\Exception $e) {
            Log::error('Subscription error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Subscription creation failed'
            ], 500);
        }
    }

    /**
     * Confirm payment intent
     */
    public function confirmPayment(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'payment_intent_id' => 'required|string'
            ]);

            $paymentIntent = PaymentIntent::retrieve($validated['payment_intent_id']);
            $payment = Payment::where('payment_intent_id', $validated['payment_intent_id'])->first();

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            if ($paymentIntent->status === 'succeeded') {
                $payment->update(['status' => 'completed']);

                // Handle different payment types
                $metadata = $paymentIntent->metadata;
                if (isset($metadata['type'])) {
                    switch ($metadata['type']) {
                        case 'webinar_registration':
                            $this->handleWebinarRegistration($metadata['webinar_id'], $metadata['user_id']);
                            break;
                        case 'donation':
                            $this->handleDonationCompletion($paymentIntent->id);
                            break;
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Payment confirmed successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Payment not completed'
            ], 400);

        } catch (\Exception $e) {
            Log::error('Payment confirmation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Payment confirmation failed'
            ], 500);
        }
    }

    /**
     * Get payment history for user
     */
    public function getPaymentHistory(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $payments = Payment::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $payments
            ]);

        } catch (\Exception $e) {
            Log::error('Payment history error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load payment history'
            ], 500);
        }
    }

    /**
     * Get payment methods for user
     */
    public function getPaymentMethods(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $customer = $this->getOrCreateCustomer($user);

            $paymentMethods = \Stripe\PaymentMethod::all([
                'customer' => $customer->id,
                'type' => 'card'
            ]);

            return response()->json([
                'success' => true,
                'data' => $paymentMethods->data
            ]);

        } catch (\Exception $e) {
            Log::error('Payment methods error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load payment methods'
            ], 500);
        }
    }

    /**
     * Add payment method
     */
    public function addPaymentMethod(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'payment_method_id' => 'required|string'
            ]);

            $user = $request->user();
            $customer = $this->getOrCreateCustomer($user);

            $paymentMethod = \Stripe\PaymentMethod::retrieve($validated['payment_method_id']);
            $paymentMethod->attach(['customer' => $customer->id]);

            return response()->json([
                'success' => true,
                'message' => 'Payment method added successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Add payment method error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add payment method'
            ], 500);
        }
    }

    /**
     * Remove payment method
     */
    public function removePaymentMethod(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'payment_method_id' => 'required|string'
            ]);

            $paymentMethod = \Stripe\PaymentMethod::retrieve($validated['payment_method_id']);
            $paymentMethod->detach();

            return response()->json([
                'success' => true,
                'message' => 'Payment method removed successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Remove payment method error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove payment method'
            ], 500);
        }
    }

    /**
     * Webhook handler for Stripe events
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            Log::error('Webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        try {
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentSucceeded($event->data->object);
                    break;
                case 'payment_intent.payment_failed':
                    $this->handlePaymentFailed($event->data->object);
                    break;
                case 'customer.subscription.created':
                    $this->handleSubscriptionCreated($event->data->object);
                    break;
                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event->data->object);
                    break;
                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;
                case 'invoice.payment_succeeded':
                    $this->handleInvoicePaymentSucceeded($event->data->object);
                    break;
                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event->data->object);
                    break;
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Webhook processing error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Helper methods
     */
    private function getOrCreateCustomer(User $user): Customer
    {
        if ($user->stripe_customer_id) {
            return Customer::retrieve($user->stripe_customer_id);
        }

        $customer = Customer::create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id
            ]
        ]);

        $user->update(['stripe_customer_id' => $customer->id]);

        return $customer;
    }

    private function handleWebinarRegistration(int $webinarId, int $userId): void
    {
        DB::transaction(function () use ($webinarId, $userId) {
            $webinar = Webinar::find($webinarId);
            $user = User::find($userId);

            if ($webinar && $user) {
                $webinar->registrations()->create([
                    'user_id' => $userId,
                    'registered_at' => now(),
                    'status' => 'confirmed'
                ]);

                // Send confirmation email
                // Mail::to($user->email)->send(new WebinarRegistrationConfirmation($webinar, $user));
            }
        });
    }

    private function handleDonationCompletion(string $paymentIntentId): void
    {
        $donation = SupportDonation::where('transaction_id', $paymentIntentId)->first();
        if ($donation) {
            $donation->update(['status' => 'completed']);
        }
    }

    private function handlePaymentSucceeded($paymentIntent): void
    {
        $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();
        if ($payment) {
            $payment->update(['status' => 'completed']);
        }
    }

    private function handlePaymentFailed($paymentIntent): void
    {
        $payment = Payment::where('payment_intent_id', $paymentIntent->id)->first();
        if ($payment) {
            $payment->update(['status' => 'failed']);
        }
    }

    private function handleSubscriptionCreated($subscription): void
    {
        $user = User::where('stripe_customer_id', $subscription->customer)->first();
        if ($user) {
            $user->subscriptions()->create([
                'stripe_subscription_id' => $subscription->id,
                'plan_id' => $subscription->items->data[0]->price->id,
                'status' => $subscription->status,
                'current_period_start' => date('Y-m-d H:i:s', $subscription->current_period_start),
                'current_period_end' => date('Y-m-d H:i:s', $subscription->current_period_end)
            ]);
        }
    }

    private function handleSubscriptionUpdated($subscription): void
    {
        $userSubscription = \App\Models\Subscription::where('stripe_subscription_id', $subscription->id)->first();
        if ($userSubscription) {
            $userSubscription->update([
                'status' => $subscription->status,
                'current_period_start' => date('Y-m-d H:i:s', $subscription->current_period_start),
                'current_period_end' => date('Y-m-d H:i:s', $subscription->current_period_end)
            ]);
        }
    }

    private function handleSubscriptionDeleted($subscription): void
    {
        $userSubscription = \App\Models\Subscription::where('stripe_subscription_id', $subscription->id)->first();
        if ($userSubscription) {
            $userSubscription->update(['status' => 'cancelled']);
        }
    }

    private function handleInvoicePaymentSucceeded($invoice): void
    {
        // Handle successful subscription payment
        Log::info('Invoice payment succeeded: ' . $invoice->id);
    }

    private function handleInvoicePaymentFailed($invoice): void
    {
        // Handle failed subscription payment
        Log::warning('Invoice payment failed: ' . $invoice->id);
    }
} 