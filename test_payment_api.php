<?php
// Test Payment API Endpoints

$baseUrl = 'http://localhost:8000/api';
$token = 'your_admin_token_here'; // Replace with actual admin token

echo "Testing Payment API Endpoints\n";
echo "=============================\n\n";

// Test endpoints
$endpoints = [
    'payments/webinar' => 'Create Webinar Payment',
    'payments/donation' => 'Create Donation',
    'payments/subscription' => 'Create Subscription',
    'payments/confirm' => 'Confirm Payment',
    'payments/history' => 'Get Payment History',
    'payments/methods' => 'Get Payment Methods',
    'webhooks/stripe' => 'Stripe Webhook'
];

foreach ($endpoints as $endpoint => $description) {
    echo "Testing: $description\n";
    echo "Endpoint: $endpoint\n";
    
    $url = "$baseUrl/$endpoint";
    $headers = [
        'Authorization: Bearer ' . $token,
        'Accept: application/json',
        'Content-Type: application/json'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    // Set method based on endpoint
    if (in_array($endpoint, ['payments/webinar', 'payments/donation', 'payments/subscription', 'payments/confirm', 'payments/methods'])) {
        curl_setopt($ch, CURLOPT_POST, true);
        
        // Sample data for different payment types
        $postData = [];
        switch ($endpoint) {
            case 'payments/webinar':
                $postData = [
                    'webinar_id' => 1,
                    'payment_method_id' => 'pm_test_payment_method_id',
                    'amount' => 29.99,
                    'currency' => 'usd'
                ];
                break;
                
            case 'payments/donation':
                $postData = [
                    'amount' => 25.00,
                    'currency' => 'usd',
                    'payment_method_id' => 'pm_test_payment_method_id',
                    'donor_name' => 'John Doe',
                    'donor_email' => 'john@example.com',
                    'message' => 'Supporting TQRS research',
                    'anonymous' => false
                ];
                break;
                
            case 'payments/subscription':
                $postData = [
                    'plan_id' => 'price_monthly_plan_id',
                    'payment_method_id' => 'pm_test_payment_method_id',
                    'plan_type' => 'monthly'
                ];
                break;
                
            case 'payments/confirm':
                $postData = [
                    'payment_intent_id' => 'pi_test_payment_intent_id'
                ];
                break;
                
            case 'payments/methods':
                $postData = [
                    'payment_method_id' => 'pm_test_payment_method_id'
                ];
                break;
        }
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "HTTP Code: $httpCode\n";
    echo "Response: " . substr($response, 0, 500) . "\n";
    echo "---\n\n";
}

// Test specific payment scenarios
echo "Testing Payment Scenarios\n";
echo "=========================\n\n";

// Test 1: Webinar Registration Payment
echo "Scenario 1: Webinar Registration Payment\n";
$webinarData = [
    'webinar_id' => 1,
    'payment_method_id' => 'pm_test_webinar_payment',
    'amount' => 49.99,
    'currency' => 'usd'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$baseUrl/payments/webinar");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Accept: application/json',
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webinarData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: " . substr($response, 0, 500) . "\n\n";

// Test 2: Donation Payment
echo "Scenario 2: Donation Payment\n";
$donationData = [
    'amount' => 100.00,
    'currency' => 'usd',
    'payment_method_id' => 'pm_test_donation_payment',
    'donor_name' => 'Jane Smith',
    'donor_email' => 'jane@example.com',
    'message' => 'Supporting qualitative research initiatives',
    'anonymous' => false
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$baseUrl/payments/donation");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Accept: application/json',
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($donationData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: " . substr($response, 0, 500) . "\n\n";

// Test 3: Subscription Payment
echo "Scenario 3: Subscription Payment\n";
$subscriptionData = [
    'plan_id' => 'price_yearly_plan_id',
    'payment_method_id' => 'pm_test_subscription_payment',
    'plan_type' => 'yearly'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$baseUrl/payments/subscription");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Accept: application/json',
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($subscriptionData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: " . substr($response, 0, 500) . "\n\n";

// Test 4: Payment History
echo "Scenario 4: Payment History\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$baseUrl/payments/history");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: " . substr($response, 0, 500) . "\n\n";

// Test 5: Payment Methods
echo "Scenario 5: Payment Methods\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$baseUrl/payments/methods");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: " . substr($response, 0, 500) . "\n\n";

// Test 6: Stripe Webhook (simulation)
echo "Scenario 6: Stripe Webhook Simulation\n";
$webhookData = [
    'id' => 'evt_test_webhook',
    'object' => 'event',
    'type' => 'payment_intent.succeeded',
    'data' => [
        'object' => [
            'id' => 'pi_test_payment_intent',
            'object' => 'payment_intent',
            'amount' => 4999,
            'currency' => 'usd',
            'status' => 'succeeded',
            'metadata' => [
                'webinar_id' => '1',
                'user_id' => '1',
                'type' => 'webinar_registration'
            ]
        ]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$baseUrl/webhooks/stripe");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json',
    'Stripe-Signature: test_signature'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhookData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: " . substr($response, 0, 500) . "\n\n";

echo "Payment API Testing Complete!\n";
echo "=============================\n\n";

echo "Notes:\n";
echo "- Replace 'your_admin_token_here' with actual authentication token\n";
echo "- Replace Stripe test IDs with actual test payment method IDs\n";
echo "- Configure Stripe webhook endpoint in your Stripe dashboard\n";
echo "- Set up proper Stripe API keys in your .env file\n";
echo "- Test with Stripe test cards for safe testing\n\n";

echo "Common Stripe Test Cards:\n";
echo "- Visa: 4242424242424242\n";
echo "- Visa (debit): 4000056655665556\n";
echo "- Mastercard: 5555555555554444\n";
echo "- American Express: 378282246310005\n";
echo "- Any future date for expiry\n";
echo "- Any 3-digit CVC\n"; 