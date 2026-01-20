# UPGRADE Guide

- [Upgrading To v2.4 From v2.3](#upgrading-to-v24-from-v23)

## High Impact Changes

- [Laravel 12 Upgrade](#laravel-12-upgrade)

- [Google reCAPTCHA Enterprise Integration](#google-recaptcha-enterprise-integration)

- [PayPal SDK Upgrade](#paypal-sdk-upgrade)

## Upgrading To v2.4 From v2.3

> [!NOTE]
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.

### Updating Dependencies

**Impact Probability: High**

#### PHP 8.3 Required

Bagisto v2.4.x now requires PHP 8.3 or greater.

### Laravel 12 Upgrade

**Impact Probability: High**

Bagisto v2.4 has been upgraded to Laravel 12, which introduces stricter type checking and modernized date/time handling.

#### Carbon Type Strictness

Laravel 12 enforces stricter type checking for Carbon date/time operations. If your custom code uses Carbon methods, ensure you're passing the correct parameter types:

**Integer/Float Parameters Required**

Carbon methods like `addDays()`, `subDays()`, etc., now require integer or float values, not strings:

```diff
- Carbon::now()->addDays('1')
+ Carbon::now()->addDays(1)

- Carbon::now()->subDays('7')
+ Carbon::now()->subDays(7)
```

**Non-Null Timezones**

Methods that accept timezone parameters no longer accept `null` values. Use a fallback:

```diff
- $date->setTimezone($channel->timezone)
+ $date->setTimezone($channel->timezone ?: config('app.timezone'))
```

#### Date Function Modernization

If you're using legacy PHP date functions in your custom code, consider migrating to Carbon for better Laravel 12 compatibility:

```diff
- strtotime($date)
+ \Carbon\Carbon::parse($date)->timestamp

- date('Y-m-d H:i:s')
+ \Carbon\Carbon::now()->format('Y-m-d H:i:s')

- date('Y-m-d')
+ \Carbon\Carbon::now()->format('Y-m-d')

- date_default_timezone_set($timezone)
+ \Carbon\Carbon::now($timezone) // Isolated to instance
```

#### PDF Response Headers

Laravel 12 has updated the format for PDF response headers. If you're generating PDFs in your custom code, update the Content-Disposition header:

```diff
- 'Content-Disposition' => 'attachment; filename="'.$fileName.'"',
+ 'Content-Disposition' => 'attachment; filename='.$fileName,
```

#### Testing Updates

If you have custom test cases, ensure all test data includes required fields that may have been added in migrations. For example, if new foreign keys have been introduced, make sure your test factories and test data include these fields.

### Google reCAPTCHA Enterprise Integration

**Impact Probability: High**

Bagisto v2.4 has migrated from Google reCAPTCHA v2 to Google reCAPTCHA Enterprise, which introduces significant changes to the implementation and configuration.

#### Configuration Changes

The following configuration keys have changed:

**v2.3 Configuration:**
- `customer.captcha.credentials.site_key`
- `customer.captcha.credentials.secret_key`

**v2.4 Configuration:**
- `customer.captcha.credentials.site_key`
- `customer.captcha.credentials.project_id` (new)
- `customer.captcha.credentials.api_key` (replaces secret_key)
- `customer.captcha.credentials.score_threshold` (new)

#### API Endpoint Changes

**v2.3:**
- Used standard reCAPTCHA v2 verification endpoint.

**v2.4:**
- Now uses Google reCAPTCHA Enterprise API: `https://recaptchaenterprise.googleapis.com/v1/projects/{project_id}/assessments`.
- Requires a valid Google Cloud Project ID.

#### Form Field Changes

The captcha token field name has changed:

**v2.3:**
```php
'g-recaptcha-response' => 'required|captcha'
```

**v2.4:**
```php
'recaptcha_token' => 'required|captcha'
```

#### Migration Steps

1. **Update Configuration:**

   Here are the details for updating the configuration to support Google reCAPTCHA Enterprise in Bagisto v2.4. You will need to update the configuration with the new keys and values as described below.

   **Obtain Google Cloud Project ID:**
   - Visit [Google Cloud Console](https://console.cloud.google.com/).
   - Create a new project or select an existing one from the project dropdown.
   - Note your Project ID from the project dashboard (not the project name).

   **Generate API Key:**
   - In Google Cloud Console, navigate to **APIs & Services → Credentials**.
   - Click **Create Credentials → API Key**.
   - Copy the generated API key.

   **Create reCAPTCHA Site Key:**
   - Navigate to **Security → reCAPTCHA** in Google Cloud Console.
   - Click **Create Key**.
   - Enter a display name for your key.
   - Select **Website** as the platform type.
   - Choose **Score-based (reCAPTCHA v3)** as the reCAPTCHA type.
   - Add your domain(s) in the **Domains** section (e.g., `example.com`).
   - Click **Create** and copy the generated site key.

   **Configure in Bagisto Admin Panel:**
   - Log in to your Bagisto admin panel.
   - Navigate to **Configuration → Customer → Captcha**.
   - Set **Status** to **Yes** to enable captcha.
   - Enter your **Project ID** (from step 1).
   - Enter your **API Key** (from step 2).
   - Enter your **Site Key** (from step 3).
   - Set **Score Threshold** (0.0 to 1.0, recommended: 0.5 for balanced security).
   - Click **Save Configuration**.

2. **Update Form Submissions:**
   - Replace `g-recaptcha-response` field name with `recaptcha_token` in all forms using captcha.
   - Update any custom validation rules referencing the old field name.

3. **Update Frontend Implementation:**
   - The captcha now renders as a hidden field instead of a visible checkbox.
   - Update your frontend JavaScript to handle the new implementation.
   - The captcha client endpoint remains similar but uses the Enterprise version: `https://www.google.com/recaptcha/enterprise.js`.

4. **Review Validation Messages:**
   - Translation keys remain the same (`customer::app.validations.captcha.required` and `customer::app.validations.captcha.captcha`).
   - No changes required to translation files.

#### Behavioral Changes

**v2.3:**
- Used checkbox-based reCAPTCHA v2.
- Binary pass/fail validation.

**v2.4:**
- Uses invisible reCAPTCHA Enterprise.
- Risk-based scoring system (0.0 to 1.0).
- Validation passes only if score >= configured threshold.
- Enhanced logging for debugging.

#### Code Example

If you have custom implementations using the Captcha class:

**v2.3:**
```php
// Old implementation
$captcha->getSecretKey();

$rules = ['g-recaptcha-response' => 'required|captcha'];
```

**v2.4:**
```php
// New implementation
$captcha->getProjectId();
$captcha->getApiKey();
$captcha->getScoreThreshold();
$rules = ['recaptcha_token' => 'required|captcha'];
```

#### Troubleshooting

The new implementation includes comprehensive logging. Check your logs for:
- `reCAPTCHA: Validation failed.` - Configuration or token issues.
- `reCAPTCHA: Assessment response received.` - Successful API communication.
- `reCAPTCHA: Validation result.` - Score and threshold comparison.

Ensure your Google Cloud Project has:
- reCAPTCHA Enterprise API enabled.
- Valid API key with proper permissions.
- Site key configured for your domain.

### PayPal SDK Upgrade

**Impact Probability: High**

Bagisto v2.4 has upgraded from the abandoned `paypal/paypal-checkout-sdk` v1.0.1 to the modern `paypal/paypal-server-sdk` v2.0, which introduces significant changes to the implementation and improves reliability and security.

#### Dependency Changes

The PayPal SDK dependency has changed:

**v2.3 Dependency:**
```json
"paypal/paypal-checkout-sdk": "1.0.1"
```

**v2.4 Dependency:**
```json
"paypal/paypal-server-sdk": "^2.0"
```

#### Namespace Changes

If you have custom PayPal implementations, the following namespace imports need to be updated:

**v2.3 Imports:**
```php
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Payments\CapturesRefundRequest;
```

**v2.4 Imports:**
```php
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Environment;
```

#### Client Initialization Changes

The client initialization method has changed significantly:

**v2.3:**
```php
$environment = $isSandbox 
    ? new SandboxEnvironment($clientId, $clientSecret)
    : new ProductionEnvironment($clientId, $clientSecret);

$client = new PayPalHttpClient($environment);
```

**v2.4:**
```php
$environment = $isSandbox 
    ? Environment::SANDBOX 
    : Environment::PRODUCTION;

$client = PaypalServerSdkClientBuilder::init()
    ->clientCredentialsAuthCredentials(
        ClientCredentialsAuthCredentialsBuilder::init(
            $clientId,
            $clientSecret
        )
    )
    ->environment($environment)
    ->build();
```

#### API Method Changes

The way API requests are made has changed:

**v2.3:**
```php
// Create order
$request = new OrdersCreateRequest();
$request->headers["prefer"] = "return=representation";
$request->body = $orderData;
$response = $client->execute($request);

// Capture order
$request = new OrdersCaptureRequest($orderId);
$response = $client->execute($request);
```

**v2.4:**
```php
// Create order
$ordersController = $client->getOrdersController();
$response = $ordersController->createOrder([
    'body' => $orderData,
    'prefer' => 'return=representation'
]);

// Capture order
$response = $ordersController->captureOrder($orderId, [
    'prefer' => 'return=representation'
]);
```

#### Response Handling Changes

Response object access has changed:

**v2.3:**
```php
// Direct property access
$orderId = $response->result->id;
$status = $response->result->status;
$captureId = $response->result->purchase_units[0]->payments->captures[0]->id;
```

**v2.4:**
```php
// Getter methods
$result = $response->getResult();
$orderId = $result->getId();
$status = $result->getStatus();
$captureId = $result->getPurchaseUnits()[0]->getPayments()->getCaptures()[0]->getId();
```

#### Transaction Handling Architecture Change

**v2.3:**
- Used event-driven listener pattern.
- Transactions created via `sales.invoice.save.after` event.
- Required separate `Transaction.php` listener class.

**v2.4:**
- Uses direct controller-based transaction creation.
- Transactions created immediately after invoice/order creation.
- No event listeners required.

#### Migration Steps

1. **Update Dependencies:**

   ```bash
   composer remove paypal/paypal-checkout-sdk
   composer require paypal/paypal-server-sdk:^2.0
   ```

2. **Update Custom Implementations:**

   If you have custom PayPal integrations:
   
   - Update namespace imports to use `PaypalServerSdkLib\*`.
   - Replace client initialization with builder pattern.
   - Update API calls to use controller methods.
   - Replace direct property access with getter methods.
   - Remove any event-driven transaction listeners.

3. **Test PayPal Functionality:**

   - Test order creation in sandbox environment.
   - Verify order capture works correctly.
   - Test refund functionality.
   - Verify IPN/webhook notifications are processed.
   - Test production environment configuration.

4. **Review Configuration:**

   No changes required to PayPal configuration in admin panel. All existing settings (Client ID, Client Secret, sandbox mode) work as before.

#### Behavioral Changes

**v2.3:**
- Used older SDK with deprecated dependencies.
- Event-driven transaction handling.

**v2.4:**
- Modern SDK with active support and updates.
- Direct transaction handling for better reliability.
- Improved error handling and logging.
- OAuth 2.0 Client Credentials authentication.
- Built-in retry logic for API calls.
