# UPGRADE Guide

- [Upgrading To v2.4 From v2.3](#upgrading-to-v24-from-v23)

## High Impact Changes

- [Laravel 12 Upgrade](#laravel-12-upgrade)

- [Theme Customizations Are Now Appearance Sections](#theme-customizations-are-now-appearance-sections)

- [Google reCAPTCHA Enterprise Integration](#google-recaptcha-enterprise-integration)

- [PayPal SDK Upgrade](#paypal-sdk-upgrade)

- [Visitor Tracking Removed](#visitor-tracking-removed)

## Medium Impact Changes

- [Magic AI — Laravel AI SDK Migration](#magic-ai--laravel-ai-sdk-migration)

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

### Theme Customizations Are Now Appearance Sections

**Impact Probability: High**

Theme customizations have been renamed to **sections** and moved out of Settings
into their own **Appearance** area. The tables, models, repositories, routes and
ACL keys all changed, so custom code that touched any of them needs updating.

#### Database

Two tables and one column are renamed by migration. Existing data is preserved —
the migration renames rather than recreates:

| v2.3 | v2.4 |
|---|---|
| `theme_customizations` | `theme_sections` |
| `theme_customization_translations` | `theme_section_translations` |
| `theme_section_translations.theme_customization_id` | `theme_section_translations.section_id` |

Update any raw queries, custom migrations or reports that name the old tables or
that column.

#### Classes

| v2.3 | v2.4 |
|---|---|
| `Webkul\Theme\Contracts\ThemeCustomization` | `Webkul\Theme\Contracts\Section` |
| `Webkul\Theme\Models\ThemeCustomization` | `Webkul\Theme\Models\Section` |
| `Webkul\Theme\Models\ThemeCustomizationProxy` | `Webkul\Theme\Models\SectionProxy` |
| `Webkul\Theme\Models\ThemeCustomizationTranslation` | `Webkul\Theme\Models\SectionTranslation` |
| `Webkul\Theme\Models\ThemeCustomizationTranslationProxy` | `Webkul\Theme\Models\SectionTranslationProxy` |
| `Webkul\Theme\Repositories\ThemeCustomizationRepository` | `Webkul\Theme\Repositories\SectionRepository` |

If you registered a replacement model through Concord, update the contract you
bind against.

#### Routes

**Every `admin/settings/themes` route has been removed.** A link or redirect to
one now 404s.

| v2.3 | v2.4 |
|---|---|
| `admin/settings/themes` | `admin/appearance/themes` |
| `admin/settings/themes/edit/{id}` | `admin/appearance/themes/{code}/sections` |

Route names moved from `admin.settings.themes.*` to `admin.appearance.themes.*`
and `admin.appearance.sections.*`.

#### ACL

The `settings.themes.*` permission keys no longer exist. Roles are rebuilt around:

- `appearance` · `appearance.themes` · `appearance.themes.activate`
- `appearance.sections` · `appearance.sections.create` · `appearance.sections.edit` · `appearance.sections.delete`

**A custom role that held `settings.themes` loses access.** Re-grant the
appearance permissions after upgrading, and update any code calling
`bouncer()->hasPermission('settings.themes…')`.

#### Section types

A section's type is now a class that extends `Webkul\Theme\Sections\SectionType`
and carries its code, title, icon, editor fields and behaviour. The six core types
are named by `Webkul\Theme\Enums\SectionTypeEnum`, and each case maps to its class
under `Webkul\Theme\Sections`.

A theme decides which types it offers, and the order the Add Section tiles show
them in, with a `sections` list beside its entry in `config/themes.php`. No core
file — the enum, the controller or the editor — changes when a theme adds, removes
or reorders a type.

| Before | Now |
|---|---|
| `Section::IMAGE_CAROUSEL` and the other type constants | `SectionTypeEnum::IMAGE_CAROUSEL->value` — the constants remain as deprecated aliases |
| `Section::TYPES` | `SectionSchema::types($themeCode)` — the constant remains, deprecated |
| `SectionSchema::for($type)` | `SectionSchema::for($type, $themeCode)` — the theme is optional |
| `FPC\Listeners\Section::LAYOUT_TYPES` | Removed; a type draws on every page when it sets `$layout` |
| `SectionRepository::sanitizeStaticCss()` | Moved to `SectionType::sanitizeCss()` |

#### Adding sections to a theme

##### How the `sections` list is read

```php
use Webkul\Theme\Enums\SectionTypeEnum;

'shop' => [
    'default' => [
        // name, assets_path, views_path, vite ...

        'sections' => [
            SectionTypeEnum::IMAGE_CAROUSEL,
            SectionTypeEnum::PRODUCT_CAROUSEL,
            SectionTypeEnum::CATEGORY_CAROUSEL,
            SectionTypeEnum::FOOTER_LINKS,
            SectionTypeEnum::STATIC_CONTENT,
            SectionTypeEnum::SERVICES_CONTENT,
        ],
    ],
],
```

- **An entry** is a `SectionTypeEnum` case, a core type's value such as
  `'image_carousel'`, or the class name of a `SectionType`.
- **The list order is the tile order.** A type that is not listed is not offered,
  and creating one is refused.
- **No `sections` key** offers every core type in enum order, so a theme — or a
  published `config/themes.php` — from before this release needs no change.
- **The first entry for a code wins**, keeping both its position and its class; a
  later entry for the same code is ignored, so there are never duplicate tiles.
- **An entry that is none of the above** is reported to the log and skipped; the
  rest of the list still loads.
- **The list does not touch placed sections.** Sections already on a page keep
  their own order. One whose type the theme stops listing is still shown: a core
  type stays editable, and a type whose class is gone opens with no fields.

##### Offer the core types, in your own order

List only the ones you want, in the order you want them. Leaving a type out
removes its tile:

```php
'sections' => [
    'product_carousel',
    'image_carousel',
    'category_carousel',
    'footer_links',
],
```

##### Lead with a few types, keep the rest in enum order

Spread the enum after the types that should come first. The duplicates it brings
in are ignored, so the remaining core types follow in enum order:

```php
'sections' => [
    'static_content',
    'product_carousel',
    ...SectionTypeEnum::getValues(),
],
```

##### Add a new section type

A new type is a class in your theme package. Only `$code` is required; every
other property has a default — the title falls back to the code in headline case
and the icon to `icon-cms`:

```php
namespace Webkul\Fashion\Sections;

use Webkul\Theme\Sections\SectionType;
use Webkul\Theme\SectionSchema;

class Lookbook extends SectionType
{
    /**
     * Code the section is stored under.
     */
    protected string $code = 'lookbook';

    /**
     * Translation key of the name the editor shows.
     */
    protected ?string $title = 'fashion::app.sections.lookbook.title';

    /**
     * Icon class drawn on the type's tile in the editor.
     */
    protected string $icon = 'icon-image';

    /**
     * The fields the editor draws.
     */
    public function getFields(): array
    {
        return [
            ['key' => 'heading', 'type' => SectionSchema::TEXT, 'label' => trans('fashion::app.sections.lookbook.heading')],
            [
                'key' => 'looks',
                'type' => SectionSchema::REPEATER,
                'label' => trans('fashion::app.sections.lookbook.looks'),
                'add_label' => trans('fashion::app.sections.lookbook.add-look'),
                'max' => 6,
                'fields' => [
                    ['key' => 'image', 'type' => SectionSchema::IMAGE, 'label' => trans('fashion::app.sections.lookbook.image')],
                    ['key' => 'link', 'type' => SectionSchema::TEXT, 'label' => trans('fashion::app.sections.lookbook.link')],
                ],
            ],
        ];
    }
}
```

Then list it wherever its tile should appear:

```php
'sections' => [
    \Webkul\Fashion\Sections\Lookbook::class,
    ...SectionTypeEnum::getValues(),
],
```

and render it from your theme's home page. The storefront hands the view its live
sections in page order, and the preview hands it the drafts with `$preview` set —
wrap each section in the two data attributes so the editor can highlight it:

```blade
@foreach ($sections as $section)
    @php ($marks = ($preview ?? false) && ! $section->getTypeInstance()?->rendersInLayout())

    @if ($marks)
        <div data-section-id="{{ $section->id }}" data-section-name="{{ $section->name }}">
    @endif

    @switch ($section->type)
        @case ('lookbook')
            @include('fashion::sections.lookbook', ['options' => $section->options ?? []])

            @break
    @endswitch

    @if ($marks)
        </div>
    @endif
@endforeach

@if ($preview ?? false)
    @include('shop::home.preview-bridge')
@endif
```

A section is created before it has content, so the partial must render nothing,
rather than fail, when its options are empty. Add the translation keys in your
theme's own language files.

##### Field kinds

| Kind | Control | Extra keys |
|---|---|---|
| `SectionSchema::TEXT` | A single line input | — |
| `SectionSchema::TEXTAREA` | A multi-line input | — |
| `SectionSchema::NUMBER` | A whole number input | — |
| `SectionSchema::IMAGE` | An upload, stored as the file's path | — |
| `SectionSchema::CODE` | A highlighted code editor | `language` (`html` or `css`) |
| `SectionSchema::REPEATER` | Repeating, draggable rows | `fields`, `add_label`, `max` |
| `SectionSchema::FILTERS` | Key and value filter rows | `keys` — each `value`, `label`, `options`, and `multiple` for a comma-separated list |

##### Extend a core type under a new code

Extend the core class and give it its own code. It offers everything the core
type does, and its tile sits beside the core one:

```php
class PremiumProductCarousel extends \Webkul\Theme\Sections\ProductCarousel
{
    /**
     * Code the section is stored under.
     */
    protected string $code = 'premium_product_carousel';

    /**
     * Translation key of the name the editor shows.
     */
    protected ?string $title = 'fashion::app.sections.premium-product-carousel';

    /**
     * The core product filters, plus the one this theme adds.
     */
    protected function filterKeys(): array
    {
        return [
            ...parent::filterKeys(),
            ['value' => 'on_sale', 'label' => trans('fashion::app.sections.on-sale'), 'options' => []],
        ];
    }
}
```

`ProductCarousel` and `CategoryCarousel` both expose `filterKeys()` for this.

##### Replace a core type, keeping its code

Extend the core class without changing its code, and list your class before the
core types. Because the first entry wins, existing sections of that type are
handled by your class from then on:

```php
class FooterLinks extends \Webkul\Theme\Sections\FooterLinks
{
    /**
     * Most columns this theme's footer lays out.
     */
    protected ?int $maxColumns = 4;
}
```

```php
'sections' => [
    \Webkul\Fashion\Sections\FooterLinks::class,
    ...SectionTypeEnum::getValues(),
],
```

##### Draw a section on every page

Set `$layout` and render the section from a layout partial instead of the home
page. Editing it then clears the whole page cache rather than just the home page:

```php
/**
 * Whether the layout draws the section on every page.
 */
protected bool $layout = true;
```

```blade
@inject('sectionRepository', 'Webkul\Theme\Repositories\SectionRepository')

@php
    $channel = core()->getCurrentChannel();

    $section = $sectionRepository->findOneOfType('promo_bar', $channel->id, $channel->theme, app()->getLocale());
@endphp

@if (! empty($section?->options['message']))
    <div
        @if ($sectionRepository->isPreviewing())
            data-section-id="{{ $section->id }}"
            data-section-name="{{ $section->name }}"
        @endif
    >
        {{ $section->options['message'] }}
    </div>
@endif
```

Use `findAllOfType()` when a channel may hold more than one. Both return the live
section on the storefront and the draft in the preview.

##### Allow one per channel, or pin it to the bottom

```php
/**
 * Whether a channel may hold only one section of this type.
 */
protected bool $singleton = true;

/**
 * Whether the section is fixed to the bottom of the page.
 */
protected bool $pinned = true;
```

A singleton's tile is withdrawn once the channel has one, and a second is refused
on the server however it is reached. A pinned section stays at the end of the list
and cannot be dragged.

##### Accept author-supplied markup

Anything written into the page unescaped must be cleaned. Override `sanitize()`;
it runs on every draft save, publish and preview:

```php
/**
 * Clean the markup and styles, which are written into the page rather than escaped.
 */
public function sanitize(array $options): array
{
    if (array_key_exists('html', $options)) {
        $options['html'] = $this->sanitizeHtml($options['html']);
    }

    if (array_key_exists('css', $options)) {
        $options['css'] = $this->sanitizeCss($options['css']);
    }

    return $options;
}
```

##### Edit a different shape from the one you store

When the storefront reads a shape the editor cannot edit directly, convert
between the two: `prepareForEditor()` shapes what is stored for the editor's
fields, and `prepareForStorage()` shapes each saved draft back. `FooterLinks`
does this, editing a list of columns while storing `column_1`, `column_2`, ….
A section storing its tags as one comma-separated string, but editing them as
rows, would do:

```php
/**
 * Shape the stored tags as the rows the editor's repeater reads.
 */
public function prepareForEditor(array $options): array
{
    $options['tags'] = collect(explode(',', $options['tags'] ?? ''))
        ->filter()
        ->map(fn ($tag) => ['name' => $tag])
        ->values()
        ->all();

    return $options;
}

/**
 * Write the edited rows back as the comma-separated tags the storefront reads.
 */
public function prepareForStorage(array $options): array
{
    if (is_array($options['tags'] ?? null)) {
        $options['tags'] = collect($options['tags'])->pluck('name')->filter()->implode(',');
    }

    return $options;
}
```

#### Behavioural changes

- **A section type is offered only to the themes that declare it**, in the order
  the theme lists it, and creating one of a type the theme does not offer is refused.
- **Only a theme that a channel runs can be customized.** The gallery hides
  Customize for any other theme, and the editor, its actions and its sections
  answer with a 403 for a theme its channel no longer runs.
- **The preview renders the theme it is asked for**, through a `theme` parameter,
  and an installed theme can be previewed from the gallery before it is activated.
- **Footer links take any number of columns.** The editor edits a list of columns
  and stores them as the same `column_1`, `column_2`, … keys, so saved footers and
  theme footer views keep working. A theme caps the count by extending
  `FooterLinks` and setting `$maxColumns`.
- **Sections are edited beside a live storefront preview**, not on a form of their
  own. The six per-type pages are replaced by one panel built from the section's
  type.
- **Edits are staged, not live.** Content, on/off state and ordering are all held
  as drafts and reach the storefront only when published. New sections are created
  switched off with a pending change.
- **Each channel is previewed and edited on its own**, so a theme customised on
  two channels is two independent sets of sections.
- **Only one footer links section per channel** is allowed; the type is withdrawn
  once a channel has one.

#### Migration steps

1. **Run the migrations** — the renames are automatic and preserve data.
2. **Update custom code** that names the old tables, column, classes or routes.
3. **Re-grant appearance permissions** to any custom role that had
   `settings.themes`.
4. **Update bookmarks and links** pointing at `admin/settings/themes`.
5. **Replace `Section::*` type constants** with `SectionTypeEnum` in theme views
   and custom code — the constants still work, but are deprecated.
6. **Declare `sections`** in a theme's `config/themes.php` entry if it adds,
   removes or reorders section types; a theme that offers the core types as they
   are needs nothing.

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

### Visitor Tracking Removed

**Impact Probability: High**

Bagisto v2.4 has completely removed the `shetabit/visitor` package and all visitor tracking functionality. This is a breaking change if your custom code relies on visitor data.

#### What Was Removed

- The `shetabit/visitor` Composer dependency
- The `visitor()` helper function
- The `visits` database table (no longer created or used)
- The `Visitable` trait from Product and Category models
- The `Visitor` trait from the Customer model
- Dashboard "Total Visitors" widget
- Reporting "Products With Most Visits" section
- Reporting "Customers Traffic" section
- Purchase funnel "Total Visits" and "Product Views" metrics
- All visitor-related translation keys
- `config/visitor.php` configuration file
- `Webkul\Core\Visitor`, `Webkul\Core\Models\Visit`, `Webkul\Core\Repositories\VisitRepository`
- `Webkul\Core\Jobs\UpdateCreateVisitIndex`, `Webkul\Core\Jobs\UpdateCreateVisitableIndex`
- `Webkul\Core\Listeners\ResponseCacheHit`
- `Webkul\Admin\Helpers\Reporting\Visitor`

#### Migration Steps

1. **Remove custom visitor code:**

   If your custom code calls `visitor()->visit()` or uses the `Visitable`/`Visitor` traits, remove those references:

   ```diff
   - use Shetabit\Visitor\Traits\Visitable;

   - class MyModel extends Model {
   -     use Visitable;
   - }

   - visitor()->visit($model);
   ```

2. **Remove the Composer dependency (if separately required):**

   ```bash
   composer remove shetabit/visitor
   ```

3. **Drop the visits table (optional):**

   If you want to clean up the database, create a migration:

   ```php
   Schema::dropIfExists('visits');
   ```

4. **Update custom dashboards/reports:**

   If you built custom dashboards or reports using visitor data, replace them with an external analytics solution (e.g., Google Analytics, Plausible, Matomo).

5. **Remove the visitor config check:**

   If your code references `core()->getConfigData('general.general.visitor_options.enabled')`, remove it. The system configuration field no longer exists.

### Magic AI — Laravel AI SDK Migration

**Impact Probability: Medium**

Bagisto v2.4 has migrated the Magic AI feature from direct OpenAI integration to the Laravel AI SDK (`laravel/ai`), introducing a unified multi-provider architecture.

#### What Changed

**v2.3:**
- Direct OpenAI API integration only
- Configuration via `config/openai.php`

**v2.4:**
- Unified `laravel/ai` SDK supporting 8 providers: Anthropic, DeepSeek, Gemini, Groq, Mistral, Ollama, OpenAI, xAI
- Per-provider model enums in `Webkul\MagicAI\Enums\Models\`
- Unified entry point via `Webkul\MagicAI\AiProvider`
- Configuration via `config/ai.php`

#### Migration Steps

1. **Run Composer update** to install the `laravel/ai` dependency (handled automatically).

2. **If you have custom Magic AI code:**

   Update any direct references to OpenAI-specific classes to use the new `AiProvider` unified interface:

   ```diff
   - use OpenAI\Client;
   + use Webkul\MagicAI\AiProvider;
   ```

3. **Update AI configuration** in Admin > Configuration > Magic AI to select your preferred provider and model.
