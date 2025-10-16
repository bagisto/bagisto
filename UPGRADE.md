# UPGRADE Guide

- [Upgrading To v2.4 From v2.3](#upgrading-to-v24-from-v23)

## High Impact Changes

- [Google reCAPTCHA Enterprise Integration](#google-recaptcha-enterprise-integration)

## Medium Impact Changes

- Soon.

## Low Impact Changes

- Soon.

## Upgrading To v2.4 From v2.3

> [!NOTE]
> We strive to document every potential breaking change. However, as some of these alterations occur in lesser-known sections of Bagisto, only a fraction of them may impact your application.

### Updating Dependencies

**Impact Probability: High**

#### PHP 8.3 Required

Bagisto v2.4.x now requires PHP 8.3 or greater.

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
   - The captcha client endpoint remains similar but uses Enterprise version: `https://www.google.com/recaptcha/enterprise.js`.

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