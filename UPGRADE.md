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

## Low Impact Changes

- [Theme Image Cache Templates](#theme-image-cache-templates)

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

A section type is now a class that extends `Webkul\Theme\Sections\SectionType`.
The class owns everything about the type — its code, title, icon, editor fields
and behaviour — so the Appearance editor no longer hardcodes any of it.

There are two kinds of section type, and each is always written the same way:

| | Core section type | Theme section type |
|---|---|---|
| **What it is** | One of the six types Bagisto ships | A type your theme package adds |
| **Defined by** | `Webkul\Theme\Enums\SectionTypeEnum` | Your class extending `SectionType` |
| **Written in `config/themes.php` as** | Its enum case — `SectionTypeEnum::PRODUCT_CAROUSEL` | Its class — `Lookbook::class` |
| **Stored in `theme_sections.type` as** | The case's value — `product_carousel` | The class's `$code` — `lookbook` |

The core section types:

| Enum case | Stored code | What it renders |
|---|---|---|
| `SectionTypeEnum::IMAGE_CAROUSEL` | `image_carousel` | A slider of linked images |
| `SectionTypeEnum::PRODUCT_CAROUSEL` | `product_carousel` | A strip of products, chosen by filters |
| `SectionTypeEnum::CATEGORY_CAROUSEL` | `category_carousel` | A strip of categories, chosen by filters |
| `SectionTypeEnum::FOOTER_LINKS` | `footer_links` | The footer's link columns, one per channel |
| `SectionTypeEnum::STATIC_CONTENT` | `static_content` | Author-supplied HTML and CSS |
| `SectionTypeEnum::SERVICES_CONTENT` | `services_content` | The service promises drawn on every page |

Code that referred to the old constants and helpers:

| Before | Now |
|---|---|
| `Section::IMAGE_CAROUSEL` and the other type constants | `SectionTypeEnum::IMAGE_CAROUSEL->value` — the constants remain as deprecated aliases |
| `Section::TYPES` | `SectionSchema::types($themeCode)` — the constant remains, deprecated |
| `SectionSchema::for($type)` | `SectionSchema::for($type, $themeCode)` — the theme is optional |
| `FPC\Listeners\Section::LAYOUT_TYPES` | Removed; a type draws on every page when it sets `$layout` |
| `SectionRepository::sanitizeStaticCss()` | Moved to `SectionType::sanitizeCss()` |

#### Adding sections to a theme

Everything below happens in your theme package and its entry in
`config/themes.php`. No core file — the enum, the controller or the editor —
changes when a theme adds, removes or reorders a section type. The examples use a
`fashion` theme whose classes live in `Webkul\Fashion\Sections`.

##### Step 1 — Choose the section types your theme offers

List them under `customize.sections` in the theme's entry. `customize` is where a
theme registers everything it customizes in the storefront — its section types
here, and its image cache templates beside them (see
[Theme Image Cache Templates](#theme-image-cache-templates)). The list decides both
**which** types the Add Section tiles offer and **the order** they appear in:

```php
use Webkul\Theme\Enums\SectionTypeEnum;

return [
    'shop' => [
        'fashion' => [
            'name' => 'Fashion',
            'assets_path' => 'public/themes/shop/fashion',
            'views_path' => 'resources/themes/fashion/views',

            'vite' => [
                'hot_file' => 'shop-fashion-vite.hot',
                'build_directory' => 'themes/shop/fashion/build',
                'package_assets_directory' => 'src/Resources/assets',
            ],

            'customize' => [
                'sections' => [
                    SectionTypeEnum::IMAGE_CAROUSEL,
                    SectionTypeEnum::PRODUCT_CAROUSEL,
                    SectionTypeEnum::CATEGORY_CAROUSEL,
                    SectionTypeEnum::FOOTER_LINKS,
                ],
            ],
        ],
    ],
];
```

This theme offers four core types; static content and service promises have no
tile. The rules the list follows:

- **The list order is the tile order.** A type that is not listed has no tile, and
  creating one is refused.
- **Without a `customize.sections` key** a theme offers every core type in enum order, so a
  theme — or a published `config/themes.php` — from before this release needs no
  change. The `default` theme lists all six explicitly.
- **The first entry for a code wins**, keeping both its position and its class. A
  later entry for the same code is ignored, so a type never gets two tiles.
- **An entry that is neither an enum case nor a `SectionType` class** is reported
  to the log and skipped; the rest of the list still loads.
- **The list does not affect sections already placed on a page.** They keep their
  own order, and one whose type the theme stops listing stays on the page: a core
  type remains editable, and a type whose class no longer exists opens with no
  fields.

If your theme only needs the core types, you are done. The remaining steps add a
section type of your own.

##### Step 2 — Create the section type class

Only `$code` is required. Every other property has a default: the title falls back
to the code in headline case, the icon to `icon-cms`, and the flags to `false`.

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
            [
                'key' => 'heading',
                'type' => SectionSchema::TEXT,
                'label' => trans('fashion::app.sections.lookbook.heading'),
            ],
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

What a section type can declare:

| Member | Default | Purpose |
|---|---|---|
| `$code` | *required* | The code stored in `theme_sections.type` |
| `$title` | The code in headline case | Translation key of the tile's name |
| `$icon` | `icon-cms` | Admin icon class drawn on the tile |
| `$singleton` | `false` | A channel may hold only one |
| `$pinned` | `false` | Fixed to the bottom of the list, not draggable |
| `$layout` | `false` | Drawn on every page, so editing it clears the whole page cache |
| `getFields()` | No fields | The fields the editor draws, from the kinds below |
| `sanitize()` | Unchanged | Cleans options on every draft save, publish and preview |
| `prepareForEditor()` / `prepareForStorage()` | Unchanged | Convert between the stored shape and the edited shape |

The field kinds `getFields()` can use:

| Kind | Control | Extra keys |
|---|---|---|
| `SectionSchema::TEXT` | A single line input | — |
| `SectionSchema::TEXTAREA` | A multi-line input | — |
| `SectionSchema::NUMBER` | A whole number input | — |
| `SectionSchema::IMAGE` | An upload, stored as the file's path | — |
| `SectionSchema::CODE` | A highlighted code editor | `language` (`html` or `css`) |
| `SectionSchema::REPEATER` | Repeating, draggable rows of nested fields | `fields`, `add_label`, `max` |
| `SectionSchema::FILTERS` | Key and value filter rows | `keys` — each with `value`, `label`, `options`, and `multiple` for a comma-separated list |

##### Step 3 — List the class in `customize.sections`

Add the class where its tile should appear, alongside the enum cases:

```php
use Webkul\Fashion\Sections\Lookbook;
use Webkul\Theme\Enums\SectionTypeEnum;

'customize' => [
    'sections' => [
        SectionTypeEnum::IMAGE_CAROUSEL,
        Lookbook::class,
        SectionTypeEnum::PRODUCT_CAROUSEL,
        SectionTypeEnum::CATEGORY_CAROUSEL,
        SectionTypeEnum::FOOTER_LINKS,
    ],
],
```

The Lookbook tile now appears second in the Add Section drawer for this theme, and
nowhere else.

##### Step 4 — Render it on the storefront

The home page receives `$sections` — the live sections in page order on the
storefront, and the drafts with `$preview` set in the Appearance preview. Match
each section on its stored code: the enum case's `->value` for a core type, your
class's `$code` for your own. While previewing, wrap each section in the two data
attributes so the editor can highlight it, and include the preview bridge once:

```blade
@foreach ($sections as $section)
    @php ($marks = ($preview ?? false) && ! $section->getTypeInstance()?->rendersInLayout())

    @if ($marks)
        <div
            data-section-id="{{ $section->id }}"
            data-section-name="{{ $section->name }}"
        >
    @endif

    @switch ($section->type)
        @case (\Webkul\Theme\Enums\SectionTypeEnum::PRODUCT_CAROUSEL->value)
            <x-shop::products.carousel
                :title="$section->options['title'] ?? ''"
                :src="route('shop.api.products.index', $section->options['filters'] ?? [])"
            />

            @break

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

A section is created before it has any content, so its partial must render
nothing, rather than fail, while its options are empty.

##### Step 5 — Translate its labels

Add every key the class uses — its `$title` and its field labels — to your theme's
own language files, in every locale the store runs.

#### Section type recipes

Each recipe builds on the steps above.

##### Reorder or remove core types

List only the core types you want, in the order you want them:

```php
'customize' => [
    'sections' => [
        SectionTypeEnum::PRODUCT_CAROUSEL,
        SectionTypeEnum::IMAGE_CAROUSEL,
        SectionTypeEnum::FOOTER_LINKS,
    ],
],
```

##### Put a few types first, keep the rest in enum order

List the types that lead, then spread every enum case after them. The cases
already listed are ignored the second time, so the rest follow in enum order:

```php
'customize' => [
    'sections' => [
        Lookbook::class,
        SectionTypeEnum::STATIC_CONTENT,
        ...SectionTypeEnum::cases(),
    ],
],
```

This offers Lookbook, Static Content, then Image, Product and Category Carousel,
Footer Links and Services Content.

##### Extend a core type under a new code

Extend the core class and give it a code of its own. It keeps everything the core
type does and gets its own tile, beside the core one. `ProductCarousel` and
`CategoryCarousel` expose `filterKeys()` for adding filters:

```php
namespace Webkul\Fashion\Sections;

use Webkul\Theme\Sections\ProductCarousel;

class SaleCarousel extends ProductCarousel
{
    /**
     * Code the section is stored under.
     */
    protected string $code = 'sale_carousel';

    /**
     * Translation key of the name the editor shows.
     */
    protected ?string $title = 'fashion::app.sections.sale-carousel.title';

    /**
     * The core product filters, plus one this theme's storefront reads.
     */
    protected function filterKeys(): array
    {
        return [
            ...parent::filterKeys(),
            ['value' => 'on_sale', 'label' => trans('fashion::app.sections.sale-carousel.on-sale'), 'options' => []],
        ];
    }
}
```

```php
'customize' => [
    'sections' => [
        SectionTypeEnum::PRODUCT_CAROUSEL,
        SaleCarousel::class,
    ],
],
```

##### Replace a core type, keeping its code

Extend the core class **without** changing its code, and list your class instead
of that enum case. Existing sections of that type are handled by your class from
then on. This theme's footer lays out at most four columns:

```php
namespace Webkul\Fashion\Sections;

use Webkul\Theme\Sections\FooterLinks as CoreFooterLinks;

class FooterLinks extends CoreFooterLinks
{
    /**
     * Most columns this theme's footer lays out.
     */
    protected ?int $maxColumns = 4;
}
```

```php
use Webkul\Fashion\Sections\FooterLinks;

'customize' => [
    'sections' => [
        FooterLinks::class,
        ...SectionTypeEnum::cases(),
    ],
],
```

Here the rest of the core types are spread after it. Your class is listed first,
so it wins over `SectionTypeEnum::FOOTER_LINKS` when the spread repeats that code.

##### Draw a section on every page

Set `$layout`, and render the section from a layout partial rather than the home
page. Editing it then clears the whole page cache instead of the home page alone:

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

`findOneOfType()` returns the live section on the storefront and its draft in the
preview. Use `findAllOfType()` when a channel may hold more than one.

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

Anything written into the page unescaped must be cleaned. Override `sanitize()`
with the `sanitizeHtml()` and `sanitizeCss()` helpers every section type inherits:

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

When the storefront reads a shape the editor cannot edit directly, convert between
the two. `prepareForEditor()` shapes the stored options for the editor's fields,
and `prepareForStorage()` shapes each saved draft back. The core `FooterLinks`
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
6. **Declare `customize.sections`** in a theme's `config/themes.php` entry if it adds,
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

### Theme Image Cache Templates

**Impact Probability: Low**

A theme can now register its own image cache templates — the resizes served from
`cache/{template}/{path}` — without touching `config/imagecache.php`. Nothing is
required on upgrade: a theme that registers none keeps using the core templates
exactly as before.

#### How the templates for a request are resolved

1. **Core templates** come from `imagecache.templates`: `small`, `medium` and
   `large`, which a published `config/imagecache.php` maps to
   `Webkul\Shop\CacheFilters\*`.
2. **The theme's templates** come from `customize.image_cache.templates` in its
   entry in `config/themes.php`. A name the core already has is overridden; a new name is
   added.
3. **The theme is the one the requesting channel runs**, found from the request's
   host, so every channel resolves its own templates. A channel whose theme is not
   installed uses the templates of `themes.shop-default`.

A theme's templates never leak into another theme, and `config/imagecache.php`
itself is never changed.

#### Adding a new image template

This walkthrough adds a `product_card` template — a 240 × 320 portrait crop for the
product cards of a `fashion` theme — without touching `config/imagecache.php`.

##### Step 1 — Create the template class

A template is a class with a public `applyFilter()` method. It receives the
original image and returns the processed one; any Intervention Image method can be
used. Put it in your theme package, for example
`packages/Webkul/Fashion/src/ImageTemplates/ProductCard.php`:

```php
namespace Webkul\Fashion\ImageTemplates;

use Intervention\Image\Interfaces\ImageInterface;

class ProductCard
{
    /**
     * The width of a product card image.
     */
    protected int $width = 240;

    /**
     * The height of a product card image.
     */
    protected int $height = 320;

    /**
     * Crop the image to a portrait product card and sharpen the downscale.
     */
    public function applyFilter(ImageInterface $image): ImageInterface
    {
        return $image
            ->cover($this->width, $this->height)
            ->sharpen(5);
    }
}
```

##### Step 2 — Register it for the theme

Map the template's name to its class under `customize.image_cache.templates` in
the theme's entry in `config/themes.php`. The name is what appears in the URL:

```php
use Webkul\Fashion\ImageTemplates\ProductCard;

'shop' => [
    'fashion' => [
        'name' => 'Fashion',
        'assets_path' => 'public/themes/shop/fashion',
        'views_path' => 'resources/themes/fashion/views',

        'customize' => [
            'image_cache' => [
                'templates' => [
                    'product_card' => ProductCard::class,
                ],
            ],
        ],
    ],
],
```

The effective templates for `fashion` are now the core `small`, `medium` and
`large`, plus `product_card`. Themes that do not register it have no
`product_card` template. If your configuration is cached, run
`php artisan optimize:clear`.

##### Step 3 — Use it in the theme's views

Build the URL the same way the core templates are used, with the template's name
and the image's stored path:

```blade
@php($image = $product->images->first())

@if ($image)
    <img
        src="{{ url('cache/product_card/'.$image->path) }}"
        alt="{{ $product->name }}"
        width="240"
        height="320"
    />
@endif
```

Any stored image path works the same way, such as a category's
`url('cache/product_card/'.$category->logo_path)`.

##### Step 4 — Check it

Open the URL on a channel that runs the theme, for example
`https://fashion.example.com/cache/product_card/product/1/front.webp`. It returns
the image cropped to 240 × 320. The same URL on a channel running another theme
answers with a 404, because that theme does not register `product_card`.

#### Overriding a core template

Register the class under a core name — `small`, `medium` or `large` — to replace
that template for the theme's channels only. Every other theme keeps the core one.

To give every image the same fixed size, extend the package's fixed-size template:

```php
namespace Webkul\Fashion\ImageTemplates;

use Webkul\ImageCache\Templates\Small as CoreSmall;

class Small extends CoreSmall
{
    /**
     * The width for small images.
     */
    protected int $width = 300;

    /**
     * The height for small images.
     */
    protected int $height = 200;
}
```

The published core `Webkul\Shop\CacheFilters\*` size by the kind of image —
product, category, attribute option or slider — from the request path. To change
one kind and keep the rest, extend that filter and hand everything else back to it:

```php
namespace Webkul\Fashion\ImageTemplates;

use Illuminate\Support\Str;
use Webkul\Shop\CacheFilters\Small as CoreSmall;

class ProductSmall extends CoreSmall
{
    /**
     * Crop product thumbnails to a portrait shape, leaving every other image to the core filter.
     */
    public function applyFilter($image)
    {
        if (Str::contains(url()->current(), '/product')) {
            return $image->cover(160, 200);
        }

        return parent::applyFilter($image);
    }
}
```

```php
'customize' => [
    'image_cache' => [
        'templates' => [
            'small' => ProductSmall::class,
        ],
    ],
],
```

Existing views keep requesting `cache/small/…`, so nothing else changes.

#### Templates that do more than resize

`applyFilter()` may run any sequence of Intervention Image calls. A greyscale
mobile banner, served at `cache/mobile_banner/{path}`:

```php
namespace Webkul\Fashion\ImageTemplates;

use Intervention\Image\Interfaces\ImageInterface;

class MobileBanner
{
    /**
     * Crop a banner for small screens and turn it greyscale.
     */
    public function applyFilter(ImageInterface $image): ImageInterface
    {
        return $image
            ->cover(768, 400)
            ->greyscale();
    }
}
```

```php
'customize' => [
    'image_cache' => [
        'templates' => [
            'product_card' => ProductCard::class,
            'mobile_banner' => MobileBanner::class,
            'small' => ProductSmall::class,
        ],
    ],
],
```

#### Rules

- **Only registered names resolve.** The name in the URL is only ever looked up
  among the registered templates; it is never used as a class. An unknown name
  answers with a 404, as before.
- **An invalid entry is skipped and reported to the log** — a class that does not
  exist, is abstract, or has no public `applyFilter()`. The core template of that
  name, if any, is used instead.
- **`original`, `download` and `logo` are reserved** and cannot be overridden.
- **Images are resized per request for the requesting channel**, and each response
  carries an ETag of its own bytes, so two themes never share an image. After a
  channel switches theme, browsers may keep images they already cached for up to
  `imagecache.lifetime` minutes.

`Webkul\ImageCache\Http\Controllers\ImageCacheController` now takes a
`Webkul\ImageCache\TemplateRegistry` in its constructor; a subclass that defines its
own constructor must pass it on.
