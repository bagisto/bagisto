---
name: payment-method-development
description: "Payment gateway development in Bagisto. Activates when creating payment methods, integrating payment gateways like Stripe, PayPal, or any third-party payment processor; or when the user mentions payment, payment gateway, payment method, Stripe, PayPal, or needs to add a new payment option to the checkout."
license: MIT
metadata:
  author: bagisto
---

# Payment Method Development

## Overview

Creating custom payment methods in Bagisto allows you to integrate any payment gateway or processor with your store. Whether you need local payment methods, cryptocurrency payments, or specialized payment flows, custom payment methods provide the flexibility your business requires.

For our tutorial, we'll create a **Custom Stripe Payment** method that demonstrates all the essential concepts you need to build any type of payment solution.

## When to Apply

Activate this skill when:
- Creating new payment methods
- Integrating payment gateways (Stripe, PayPal, Razorpay, etc.)
- Adding payment options to checkout
- Modifying existing payment configurations
- Creating admin configuration for payment methods

## Bagisto Payment Architecture

Bagisto's payment system is built around a flexible method-based architecture that separates configuration from business logic.

### Core Components

| Component | Purpose | Location |
|-----------|---------|----------|
| **Payment Methods Configuration** | Defines payment method properties | `Config/payment-methods.php` |
| **Payment Classes** | Contains payment processing logic | `Payment/ClassName.php` |
| **System Configuration** | Admin interface forms | `Config/system.php` |
| **Service Provider** | Registers payment method | `Providers/ServiceProvider.php` |

### Key Features

- **Flexible Payment Processing**: Support for redirects, APIs, webhooks, or custom flows.
- **Configuration Management**: Admin-friendly settings interface.
- **Multi-channel Support**: Different settings per sales channel.
- **Security Ready**: Built-in CSRF protection and secure handling.
- **Extensible Architecture**: Easy integration with third-party gateways.

## Step-by-Step Guide

### Step 1: Create Package Directory Structure

```bash
mkdir -p packages/Webkul/CustomStripePayment/src/{Payment,Config,Providers}
```

### Step 2: Create Payment Method Configuration

**File:** `packages/Webkul/CustomStripePayment/src/Config/payment-methods.php`

```php
<?php

return [
    'custom_stripe_payment' => [
        'code'        => 'custom_stripe_payment',
        'title'       => 'Credit Card (Stripe)',
        'description' => 'Secure credit card payments powered by Stripe',
        'class'       => 'Webkul\CustomStripePayment\Payment\CustomStripePayment',
        'active'      => true,
        'sort'        => 1,
    ],
];
```

#### Configuration Properties Explained

| Property | Type | Purpose | Description |
|----------|------|---------|-------------|
| **`code`** | String | Unique identifier | Must match the array key and be used consistently across your payment method. |
| **`title`** | String | Default display name | Shown to customers during checkout (can be overridden in admin). |
| **`description`** | String | Payment method description | Brief explanation of the payment method. |
| **`class`** | String | Payment class namespace | Full path to your payment processing class. |
| **`active`** | Boolean | Default status | Whether the payment method is enabled by default. |
| **`sort`** | Integer | Display order | Lower numbers appear first in checkout (0 = first). |

> **Note:** The array key (`custom_stripe_payment`) must match the `code` property and be used consistently in your payment class `$code` property, system configuration key path, and route names and identifiers.

### Step 3: Create Payment Class

**File:** `packages/Webkul/CustomStripePayment/src/Payment/CustomStripePayment.php`

```php
<?php

namespace Webkul\CustomStripePayment\Payment;

use Webkul\Payment\Payment\Payment;

class CustomStripePayment extends Payment
{
    /**
     * Payment method code - must match payment-methods.php key.
     *
     * @var string
     */
    protected $code = 'custom_stripe_payment';

    /**
     * Get redirect URL for payment processing.
     *
     * Note: You need to create this route in your Routes/web.php file
     * or return null if you don't need a redirect.
     *
     * @return string|null
     */
    public function getRedirectUrl()
    {
        // return route('custom_stripe_payment.process');
        return null; // No redirect needed for this basic example
    }

    /**
     * Get additional details for frontend display.
     *
     * @return array
     */
    public function getAdditionalDetails()
    {
        return [
            'title' => $this->getConfigData('title'),
            'description' => $this->getConfigData('description'),
            'requires_card_details' => true,
        ];
    }

    /**
     * Get payment method configuration data.
     *
     * @param  string  $field
     * @return mixed
     */
    public function getConfigData($field)
    {
        return core()->getConfigData('sales.payment_methods.custom_stripe_payment.' . $field);
    }
}
```

### Step 4: Create System Configuration

**File:** `packages/Webkul/CustomStripePayment/src/Config/system.php`

```php
<?php

return [
    [
        'key'    => 'sales.payment_methods.custom_stripe_payment',
        'name'   => 'Custom Stripe Payment',
        'info'   => 'Custom Stripe Payment Method Configuration',
        'sort'   => 1,
        'fields' => [
            [
                'name'          => 'active',
                'title'         => 'Status',
                'type'          => 'boolean',
                'default_value' => true,
                'channel_based' => true,
            ],
            [
                'name'          => 'title',
                'title'         => 'Title',
                'type'          => 'text',
                'default_value' => 'Credit Card (Stripe)',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'description',
                'title'         => 'Description',
                'type'          => 'textarea',
                'default_value' => 'Secure credit card payments',
                'channel_based' => true,
                'locale_based'  => true,
            ],
            [
                'name'          => 'sort',
                'title'         => 'Sort Order',
                'type'          => 'text',
                'default_value' => '1',
            ],
        ],
    ],
];
```

#### System Configuration Field Properties

| Property | Purpose | Description |
|----------|---------|-------------|
| **`name`** | Field identifier | Used to store and retrieve configuration values. |
| **`title`** | Field label | Label displayed in the admin form. |
| **`type`** | Input type | `text`, `textarea`, `boolean`, `select`, `password`, etc. |
| **`default_value`** | Default setting | Initial value when first configured. |
| **`channel_based`** | Multi-store support | Different values per sales channel. |
| **`locale_based`** | Multi-language support | Translatable content per language. |
| **`validation`** | Field validation | Rules like `required`, `numeric`, `email`. |

### Step 5: Create Service Provider

**File:** `packages/Webkul/CustomStripePayment/src/Providers/CustomStripePaymentServiceProvider.php`

```php
<?php

namespace Webkul\CustomStripePayment\Providers;

use Illuminate\Support\ServiceProvider;

class CustomStripePaymentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // Merge payment method configuration.
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/payment-methods.php',
            'payment_methods'
        );

        // Merge system configuration.
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
```

### Step 6: Register Your Package

1. **Add to composer.json** (in Bagisto root directory):

```json
{
    "autoload": {
        "psr-4": {
            "Webkul\\CustomStripePayment\\": "packages/Webkul/CustomStripePayment/src"
        }
    }
}
```

2. **Update autoloader:**

```bash
composer dump-autoload
```

3. **Register service provider** in `bootstrap/providers.php`:

```php
<?php

return [
    App\Providers\AppServiceProvider::class,

    // ... other providers ...

    Webkul\CustomStripePayment\Providers\CustomStripePaymentServiceProvider::class,
];
```

4. **Clear caches:**

```bash
php artisan optimize:clear
```

## Base Payment Class Reference

**Location:** `packages/Webkul/Payment/src/Payment/Payment.php`

All payment methods extend `Webkul\Payment\Payment\Payment` abstract class:

```php
<?php

namespace Webkul\Payment\Payment;

use Webkul\Checkout\Facades\Cart;

abstract class Payment
{
    /**
     * Cart.
     *
     * @var \Webkul\Checkout\Contracts\Cart
     */
    protected $cart;

    /**
     * Checks if payment method is available.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->getConfigData('active');
    }

    /**
     * Get payment method code.
     *
     * @return string
     */
    public function getCode()
    {
        if (empty($this->code)) {
            // throw exception
        }

        return $this->code;
    }

    /**
     * Get payment method title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getConfigData('title');
    }

    /**
     * Get payment method description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getConfigData('description');
    }

    /**
     * Get payment method image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->getConfigData('image');
    }

    /**
     * Retrieve information from payment configuration.
     *
     * @param  string  $field
     * @return mixed
     */
    public function getConfigData($field)
    {
        return core()->getConfigData('sales.payment_methods.'.$this->getCode().'.'.$field);
    }

    /**
     * Abstract method to get the redirect URL.
     *
     * @return string The redirect URL.
     */
    abstract public function getRedirectUrl();

    /**
     * Set cart.
     *
     * @return void
     */
    public function setCart()
    {
        if (! $this->cart) {
            $this->cart = Cart::getCart();
        }
    }

    /**
     * Get cart.
     *
     * @return \Webkul\Checkout\Contracts\Cart
     */
    public function getCart()
    {
        if (! $this->cart) {
            $this->setCart();
        }

        return $this->cart;
    }

    /**
     * Return cart items.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCartItems()
    {
        if (! $this->cart) {
            $this->setCart();
        }

        return $this->cart->items;
    }

    /**
     * Get payment method sort order.
     *
     * @return string
     */
    public function getSortOrder()
    {
        return $this->getConfigData('sort');
    }

    /**
     * Get payment method additional information.
     *
     * @return array
     */
    public function getAdditionalDetails()
    {
        if (empty($this->getConfigData('instructions'))) {
            return [];
        }

        return [
            'title' => trans('admin::app.configuration.index.sales.payment-methods.instructions'),
            'value' => $this->getConfigData('instructions'),
        ];
    }
}
```

## Key Methods to Implement

| Method | Purpose | Required |
|--------|---------|----------|
| `getRedirectUrl()` | Return URL for redirect payment methods | Yes (abstract) |
| `getImage()` | Return payment method logo URL | No (uses default) |
| `getAdditionalDetails()` | Return additional info (instructions, etc.) | No (uses default) |
| `isAvailable()` | Override to add custom availability logic | No (uses default) |
| `getConfigData($field)` | Override if codes are not in convention | No (uses default) |

> **Implementation Note:** Usually, you don't need to explicitly set the `$code` property because if your codes are properly set, then config data can get properly. However, if codes are not in convention then you might need this property to override the default behavior.

## Built-in Payment Methods

- **CashOnDelivery**: `packages/Webkul/Payment/src/Payment/CashOnDelivery.php`
- **MoneyTransfer**: `packages/Webkul/Payment/src/Payment/MoneyTransfer.php`
- **PaypalStandard**: `packages/Webkul/Paypal/src/Payment/Standard.php`
- **PaypalSmartButton**: `packages/Webkul/Paypal/src/Payment/SmartButton.php`

## Best Practices for Payment Classes

### Error Handling

Always implement comprehensive error handling in your payment methods:

```php
/**
 * Handle payment errors gracefully.
 *
 * @param  \Exception  $e
 * @return array
 */
protected function handlePaymentError(\Exception $e)
{
    // Log the error for debugging.
    \Log::error('Payment error in ' . $this->code, [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString(),
    ]);

    // Return user-friendly error message.
    return [
        'success' => false,
        'error'   => 'Payment processing failed. Please try again or contact support.',
    ];
}
```

### Security Considerations

Always validate and sanitize data before processing payments to protect your application and customers:

```php
/**
 * Validate payment data before processing.
 *
 * @param  array  $data
 * @return bool
 *
 * @throws \InvalidArgumentException
 */
protected function validatePaymentData($data)
{
    $validator = validator($data, [
        'amount'        => 'required|numeric|min:0.01',
        'currency'      => 'required|string|size:3',
        'customer_email'=> 'required|email',
    ]);

    if ($validator->fails()) {
        throw new \InvalidArgumentException($validator->errors()->first());
    }

    return true;
}
```

### Logging and Debugging

Proper logging helps you track payment activities and troubleshoot issues without exposing sensitive information:

```php
/**
 * Log payment activities for debugging and audit.
 *
 * @param  string  $action
 * @param  array   $data
 * @return void
 */
protected function logPaymentActivity($action, $data = [])
{
    // Remove sensitive data before logging.
    $sanitizedData = array_diff_key($data, [
        'api_key'      => '',
        'secret_key'   => '',
        'card_number'  => '',
        'cvv'          => '',
    ]);

    \Log::info("Payment {$action} for {$this->code}", $sanitizedData);
}
```

> **Implementation Note:** The methods shown in this section are **demonstration examples** for best practices. In real-world applications, you need to implement these methods according to your specific payment gateway requirements and business logic. Use these examples as reference guides and adapt them to your particular use case.

## Example: PayPal Smart Button (Complex Integration)

For complex payment integrations like PayPal, see `packages/Webkul/Paypal/src/Payment/SmartButton.php`:

- Extends PayPal base class which extends Payment.
- Uses PayPal SDK for API calls.
- Implements createOrder, captureOrder, getOrder, refundOrder.
- Handles sandbox/live environment switching.

## Package Structure

```
packages
└── Webkul
    └── CustomStripePayment
        └── src
            ├── Payment
            │   └── CustomStripePayment.php                 # Payment processing logic
            ├── Config
            │   ├── payment-methods.php                     # Payment method definition
            │   └── system.php                              # Admin configuration
            └── Providers
                └── CustomStripePaymentServiceProvider.php  # Registration
```

## Testing

Payment methods can be tested using the checkout tests in `packages/Webkul/Shop/tests/Feature/Checkout/CheckoutTest.php`.

## Key Files Reference

| File | Purpose |
|------|---------|
| `packages/Webkul/Payment/src/Payment/Payment.php` | Base abstract class |
| `packages/Webkul/Payment/src/Payment.php` | Payment facade methods |
| `packages/Webkul/Payment/src/Config/paymentmethods.php` | Default payment methods config |
| `packages/Webkul/Paypal/src/Payment/SmartButton.php` | Complex payment example |
| `packages/Webkul/Paypal/src/Providers/PaypalServiceProvider.php` | Service provider example |
| `packages/Webkul/Payment/src/Payment/CashOnDelivery.php` | Simple payment example |
| `packages/Webkul/Payment/src/Payment/MoneyTransfer.php` | Payment with additional details |

## Common Pitfalls

- Forgetting to merge config in service provider
- Not matching `$code` property with config array key
- Not registering service provider in `bootstrap/providers.php`
- Forgetting to run `composer dump-autoload` after adding package
- Not clearing cache after configuration changes
- Not following PHPDoc conventions with proper punctuation
