# Payment API Documentation

## Overview

The TQRS Payment API provides comprehensive payment processing capabilities using Stripe as the payment processor. This system supports webinar registrations, donations, and premium subscriptions with secure payment handling, webhook processing, and user payment management.

## Features

- **Multiple Payment Types**: Webinar registrations, donations, and subscriptions
- **Secure Processing**: Stripe integration with PCI compliance
- **Webhook Handling**: Real-time payment status updates
- **Payment Methods**: Credit card processing with saved payment methods
- **User Management**: Payment history and method management
- **Error Handling**: Comprehensive error handling and logging

## Configuration

### Environment Variables

Add the following to your `.env` file:

```env
# Stripe Configuration
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
STRIPE_CURRENCY=usd

# Stripe Plan IDs (create these in your Stripe dashboard)
STRIPE_MONTHLY_PLAN_ID=price_monthly_plan_id
STRIPE_YEARLY_PLAN_ID=price_yearly_plan_id
```

### Composer Dependencies

```bash
composer require stripe/stripe-php
```

## API Endpoints

### Authentication

All payment endpoints require authentication using Bearer tokens:

```
Authorization: Bearer your_token_here
```

### 1. Create Webinar Payment

**Endpoint:** `POST /api/payments/webinar`

**Description:** Create a payment intent for webinar registration.

**Request Body:**
```json
{
    "webinar_id": 1,
    "payment_method_id": "pm_test_payment_method_id",
    "amount": 29.99,
    "currency": "usd"
}
```

**Response:**
```json
{
    "success": true,
    "payment_intent": {
        "id": "pi_test_payment_intent_id",
        "amount": 2999,
        "currency": "usd",
        "status": "requires_confirmation",
        "client_secret": "pi_test_secret"
    },
    "payment_id": 1
}
```

### 2. Create Donation

**Endpoint:** `POST /api/payments/donation`

**Description:** Create a payment intent for donations.

**Request Body:**
```json
{
    "amount": 25.00,
    "currency": "usd",
    "payment_method_id": "pm_test_payment_method_id",
    "donor_name": "John Doe",
    "donor_email": "john@example.com",
    "message": "Supporting TQRS research",
    "anonymous": false
}
```

**Response:**
```json
{
    "success": true,
    "payment_intent": {
        "id": "pi_test_payment_intent_id",
        "amount": 2500,
        "currency": "usd",
        "status": "requires_confirmation"
    },
    "donation_id": 1,
    "payment_id": 2
}
```

### 3. Create Subscription

**Endpoint:** `POST /api/payments/subscription`

**Description:** Create a subscription for premium content.

**Request Body:**
```json
{
    "plan_id": "price_monthly_plan_id",
    "payment_method_id": "pm_test_payment_method_id",
    "plan_type": "monthly"
}
```

**Response:**
```json
{
    "success": true,
    "subscription": {
        "id": "sub_test_subscription_id",
        "status": "active",
        "current_period_start": 1640995200,
        "current_period_end": 1643673600
    },
    "client_secret": "pi_test_secret"
}
```

### 4. Confirm Payment

**Endpoint:** `POST /api/payments/confirm`

**Description:** Confirm a payment intent after client-side processing.

**Request Body:**
```json
{
    "payment_intent_id": "pi_test_payment_intent_id"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Payment confirmed successfully"
}
```

### 5. Get Payment History

**Endpoint:** `GET /api/payments/history`

**Description:** Retrieve user's payment history.

**Response:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "amount": "29.99",
                "currency": "usd",
                "status": "completed",
                "payment_type": "webinar_registration",
                "description": "Webinar Registration: Introduction to Qualitative Research",
                "created_at": "2024-01-15T10:30:00Z"
            }
        ],
        "total": 1
    }
}
```

### 6. Get Payment Methods

**Endpoint:** `GET /api/payments/methods`

**Description:** Retrieve user's saved payment methods.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": "pm_test_payment_method_id",
            "type": "card",
            "card": {
                "brand": "visa",
                "last4": "4242",
                "exp_month": 12,
                "exp_year": 2025
            }
        }
    ]
}
```

### 7. Add Payment Method

**Endpoint:** `POST /api/payments/methods`

**Description:** Add a new payment method to user's account.

**Request Body:**
```json
{
    "payment_method_id": "pm_test_payment_method_id"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Payment method added successfully"
}
```

### 8. Remove Payment Method

**Endpoint:** `DELETE /api/payments/methods/{id}`

**Description:** Remove a payment method from user's account.

**Response:**
```json
{
    "success": true,
    "message": "Payment method removed successfully"
}
```

### 9. Stripe Webhook

**Endpoint:** `POST /api/webhooks/stripe`

**Description:** Handle Stripe webhook events (no authentication required).

**Headers:**
```
Stripe-Signature: whsec_signature_from_stripe
```

## Frontend Integration

### 1. Initialize Stripe

```javascript
const stripe = Stripe('pk_test_your_publishable_key');
const elements = stripe.elements();
```

### 2. Create Payment Method

```javascript
const { paymentMethod, error } = await stripe.createPaymentMethod({
    type: 'card',
    card: cardElement,
});

if (error) {
    console.error('Error:', error);
} else {
    // Use paymentMethod.id for API calls
}
```

### 3. Process Payment

```javascript
// Create payment intent
const response = await fetch('/api/payments/webinar', {
    method: 'POST',
    headers: {
        'Authorization': 'Bearer ' + token,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        webinar_id: 1,
        payment_method_id: paymentMethod.id,
        amount: 29.99,
        currency: 'usd'
    })
});

const data = await response.json();

if (data.success) {
    // Handle 3D Secure if required
    if (data.payment_intent.status === 'requires_action') {
        const { error } = await stripe.confirmCardPayment(
            data.payment_intent.client_secret
        );
        
        if (error) {
            console.error('Payment failed:', error);
        } else {
            // Confirm payment
            await fetch('/api/payments/confirm', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    payment_intent_id: data.payment_intent.id
                })
            });
        }
    }
}
```

## Webhook Events

The system handles the following Stripe webhook events:

- `payment_intent.succeeded` - Payment completed successfully
- `payment_intent.payment_failed` - Payment failed
- `customer.subscription.created` - New subscription created
- `customer.subscription.updated` - Subscription updated
- `customer.subscription.deleted` - Subscription cancelled
- `invoice.payment_succeeded` - Subscription payment succeeded
- `invoice.payment_failed` - Subscription payment failed

## Error Handling

### Common Error Responses

```json
{
    "success": false,
    "message": "Payment failed: Your card was declined."
}
```

### Error Codes

- `400` - Bad Request (invalid data)
- `401` - Unauthorized (invalid token)
- `402` - Payment Required (payment failed)
- `404` - Not Found (resource not found)
- `500` - Internal Server Error

## Security Considerations

### 1. PCI Compliance

- Never store credit card data directly
- Use Stripe Elements for secure card input
- All sensitive data handled by Stripe

### 2. Webhook Security

- Verify webhook signatures
- Use HTTPS for all webhook endpoints
- Validate webhook payloads

### 3. Authentication

- Require authentication for all payment endpoints
- Validate user permissions
- Log all payment activities

## Testing

### Test Cards

Use these Stripe test cards for testing:

- **Visa**: 4242424242424242
- **Visa (debit)**: 4000056655665556
- **Mastercard**: 5555555555664444
- **American Express**: 378282246310005

### Test Scenarios

1. **Successful Payment**: Use any test card with future expiry
2. **Declined Payment**: Use 4000000000000002
3. **3D Secure**: Use 4000002500003155
4. **Insufficient Funds**: Use 4000000000009995

## Database Schema

### Payments Table

```sql
CREATE TABLE payments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    payment_method VARCHAR(50) NOT NULL,
    payment_intent_id VARCHAR(255) UNIQUE NOT NULL,
    status ENUM('pending', 'completed', 'failed', 'cancelled') DEFAULT 'pending',
    metadata JSON NULL,
    processed_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_status (user_id, status),
    INDEX idx_payment_intent (payment_intent_id),
    INDEX idx_created_at (created_at)
);
```

### Subscriptions Table

```sql
CREATE TABLE subscriptions (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    stripe_subscription_id VARCHAR(255) UNIQUE NOT NULL,
    plan_id VARCHAR(255) NOT NULL,
    plan_type ENUM('monthly', 'yearly') NOT NULL,
    status ENUM('active', 'canceled', 'past_due', 'unpaid', 'trialing') DEFAULT 'active',
    current_period_start TIMESTAMP NOT NULL,
    current_period_end TIMESTAMP NOT NULL,
    canceled_at TIMESTAMP NULL,
    ended_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_status (user_id, status),
    INDEX idx_stripe_subscription (stripe_subscription_id),
    INDEX idx_current_period_end (current_period_end)
);
```

## Monitoring and Logging

### Payment Logs

All payment activities are logged with:
- User ID
- Payment amount and currency
- Payment method used
- Success/failure status
- Error messages (if any)
- Timestamp

### Webhook Logs

Webhook events are logged with:
- Event type
- Stripe event ID
- Processing status
- Error details (if any)

## Best Practices

### 1. Error Handling

- Always handle payment errors gracefully
- Provide clear error messages to users
- Log errors for debugging

### 2. Security

- Validate all input data
- Use HTTPS for all communications
- Implement rate limiting
- Monitor for suspicious activities

### 3. User Experience

- Show loading states during payment processing
- Provide clear success/failure feedback
- Save payment methods for convenience
- Support multiple payment options

### 4. Testing

- Test all payment scenarios
- Use Stripe test mode for development
- Test webhook handling
- Validate error conditions

## Troubleshooting

### Common Issues

1. **Payment Declined**
   - Check card details
   - Verify sufficient funds
   - Check for 3D Secure requirements

2. **Webhook Failures**
   - Verify webhook endpoint URL
   - Check webhook secret
   - Validate webhook signature

3. **Authentication Errors**
   - Verify API keys
   - Check token validity
   - Ensure proper headers

### Debug Mode

Enable debug logging in your `.env`:

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

## Support

For payment-related issues:

1. Check Stripe Dashboard for payment status
2. Review application logs
3. Verify webhook configurations
4. Test with Stripe test cards
5. Contact support with error details

---

**Note:** This documentation assumes you have a working Laravel application with proper authentication and database setup. Adjust the implementation according to your specific requirements and security policies. 