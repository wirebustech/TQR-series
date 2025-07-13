<?php
include_once __DIR__ . '/../includes/translate.php';

$lang = $_GET['lang'] ?? 'en';
$texts = [
    'title' => 'Payment Form - TQRS',
    'paymentTitle' => 'Payment',
    'paymentSubtitle' => 'Complete your transaction securely',
    'paymentSummary' => 'Payment Summary',
    'item' => 'Item:',
    'amount' => 'Amount:',
    'paymentOptions' => 'Payment Options',
    'creditCard' => 'Credit Card',
    'savedMethods' => 'Saved Methods',
    'paymentInformation' => 'Payment Information',
    'savedPaymentMethods' => 'Saved Payment Methods',
    'addNewCard' => 'Add New Card',
    'payNow' => 'Pay Now',
    'cancel' => 'Cancel',
    'donationAmounts' => 'Donation Amounts',
    'customAmount' => 'Custom Amount',
    'donorName' => 'Name (Optional)',
    'donorMessage' => 'Message (Optional)',
    'anonymousDonation' => 'Make this donation anonymous',
    'subscriptionPlans' => 'Choose Your Plan',
    'perMonth' => 'per month',
    'perYear' => 'per year',
    'planFeatures' => [
        'monthly' => [
            'Access to premium webinars',
            'Exclusive research content',
            'Priority support',
            'Early access to new features'
        ],
        'yearly' => [
            'All monthly features',
            '2 months free',
            'Exclusive yearly content',
            'VIP webinar access'
        ]
    ]
];
if ($lang !== 'en') {
    foreach ($texts as $k => $v) {
        if (is_array($v)) {
            foreach ($v as $subK => $subV) {
                if (is_array($subV)) {
                    foreach ($subV as $i => $item) {
                        $texts[$k][$subK][$i] = translateText($item, $lang, 'en');
                    }
                } else {
                    $texts[$k][$subK] = translateText($subV, $lang, 'en');
                }
            }
        } else {
            $texts[$k] = translateText($v, $lang, 'en');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($texts['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .payment-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .payment-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .payment-summary {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .payment-method {
            margin-bottom: 2rem;
        }
        .stripe-element {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            background: white;
        }
        .payment-options {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .payment-option {
            flex: 1;
            padding: 1rem;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .payment-option.active {
            border-color: #0d6efd;
            background: #f8f9ff;
        }
        .payment-option:hover {
            border-color: #0d6efd;
        }
        .lang-switcher {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }
    </style>
</head>
<body>
    <div class="lang-switcher">
        <form method="get">
            <select name="lang" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="en"<?= $lang=='en'?' selected':'' ?>>English</option>
                <option value="fr"<?= $lang=='fr'?' selected':'' ?>>Français</option>
                <option value="es"<?= $lang=='es'?' selected':'' ?>>Español</option>
            </select>
        </form>
    </div>
    
    <div class="payment-container">
        <!-- Payment Header -->
        <div class="payment-header">
            <h2 id="paymentTitle"><?= htmlspecialchars($texts['paymentTitle']) ?></h2>
            <p id="paymentSubtitle"><?= htmlspecialchars($texts['paymentSubtitle']) ?></p>
        </div>

        <!-- Payment Summary -->
        <div class="payment-summary" id="paymentSummary">
            <h5><?= htmlspecialchars($texts['paymentSummary']) ?></h5>
            <div class="row">
                <div class="col-6">
                    <strong><?= htmlspecialchars($texts['item']) ?></strong>
                    <span id="itemName">-</span>
                </div>
                <div class="col-6 text-end">
                    <strong><?= htmlspecialchars($texts['amount']) ?></strong>
                    <span id="itemAmount">-</span>
                </div>
            </div>
        </div>

        <!-- Payment Type Selection -->
        <div class="payment-options" id="paymentTypeSelector">
            <div class="payment-option active" data-type="card">
                <i class="bi bi-credit-card"></i>
                <div><?= htmlspecialchars($texts['creditCard']) ?></div>
            </div>
            <div class="payment-option" data-type="saved">
                <i class="bi bi-wallet2"></i>
                <div><?= htmlspecialchars($texts['savedMethods']) ?></div>
            </div>
        </div>

        <!-- New Card Form -->
        <div class="payment-method" id="newCardForm">
            <h6><?= htmlspecialchars($texts['paymentInformation']) ?></h6>
            <div id="card-element" class="stripe-element">
                <!-- Stripe Elements will be inserted here -->
            </div>
            <div id="card-errors" class="error-message"></div>
        </div>

        <!-- Action Buttons -->
        <div class="d-grid gap-2">
            <button class="btn btn-primary btn-lg" id="payButton" onclick="processPayment()">
                <span id="payButtonText"><?= htmlspecialchars($texts['payNow']) ?></span>
                <span class="spinner-border spinner-border-sm loading-spinner" id="paySpinner"></span>
            </button>
            <button class="btn btn-outline-secondary" onclick="cancelPayment()">
                <?= htmlspecialchars($texts['cancel']) ?>
            </button>
        </div>

        <!-- Messages -->
        <div id="paymentMessages"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Stripe configuration
        const stripe = Stripe('pk_test_your_publishable_key'); // Replace with your publishable key
        const elements = stripe.elements();
        
        // Payment state
        let paymentType = 'webinar';
        let selectedAmount = 0;
        let selectedPlan = null;
        let paymentMethodId = null;
        let cardElement = null;
        
        // Initialize payment form
        document.addEventListener('DOMContentLoaded', function() {
            initializePaymentForm();
        });
        
        function initializePaymentForm() {
            // Create card element
            cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#424770',
                        '::placeholder': {
                            color: '#aab7c4',
                        },
                    },
                    invalid: {
                        color: '#9e2146',
                    },
                },
            });
            
            cardElement.mount('#card-element');
            
            // Handle card errors
            cardElement.on('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
        }
        
        function processPayment() {
            // Payment processing logic here
            console.log('Processing payment...');
        }
        
        function cancelPayment() {
            window.history.back();
        }
    </script>
</body>
</html> 