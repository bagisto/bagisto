# Vietnamese Localization Guide

This document explains how Vietnamese localization was added to Bagisto and how to maintain it.

## Overview

Vietnamese translations are stored in `lang/vendor/` at the application level, not inside Bagisto packages. This ensures translations survive when packages are updated via Composer.

## How It Works

Laravel's translation system automatically checks `lang/vendor/{namespace}/{locale}/{group}.php` and merges those files on top of package translations. This is the official Laravel mechanism for overriding package language files.

### File Structure

```
lang/vendor/
  admin/vi/app.php          (Admin panel translations)
  shop/vi/app.php           (Storefront translations)
  installer/vi/app.php      (Installer wizard translations)
  core/vi/app.php           (Core translations)
  core/vi/validation.php    (Validation messages)
  product/vi/app.php        (Product translations)
  customer/vi/app.php       (Customer translations)
  attribute/vi/app.php      (Attribute translations)
  data_transfer/vi/app.php  (Data transfer translations)
  paypal/vi/app.php         (PayPal translations)
```

## Initial Setup

### 1. Install Translation Package

```bash
composer install
```

This installs `stichoza/google-translate-php` (already added to `composer.json`).

### 2. Generate Vietnamese Translations

Run the artisan command to auto-translate all English language files:

```bash
php artisan app:translate-locale --target=vi
```

This command:
- Discovers all 9 Webkul package namespaces
- Reads English translation files from each package
- Translates all string values to Vietnamese using Google Translate
- Preserves `:placeholder` tokens (e.g., `:name`, `:count`)
- Preserves HTML tags
- Writes results to `lang/vendor/{namespace}/vi/{group}.php`

### 3. Add Vietnamese Locale to Database

```bash
php artisan migrate
```

This runs the migration that adds Vietnamese (`vi`) to the `locales` table.

### 4. Format Code

```bash
vendor/bin/pint --dirty --format agent
```

## Using Vietnamese Locale

### In Admin Panel

1. Go to **Settings** → **Locales**
2. Vietnamese (`vi`) should already be listed (added by migration)
3. Go to **Settings** → **Channels** → Edit your channel
4. Add Vietnamese to **Allowed Locales**
5. Set Vietnamese as default locale if desired

### On Storefront

Once Vietnamese is enabled in a channel, customers can switch languages using the locale switcher.

## Maintaining Translations

### When Packages Update

When Bagisto packages are updated via Composer, the translation files in `packages/Webkul/*/src/Resources/lang/` may change. Your Vietnamese translations in `lang/vendor/` are **not affected** because they're at the application level.

### Updating Translations

If new translation keys are added to packages:

1. **Option 1: Auto-translate new keys**
   ```bash
   php artisan app:translate-locale --target=vi
   ```
   This will re-translate everything. Existing translations will be overwritten.

2. **Option 2: Manual translation**
   - Compare English files: `packages/Webkul/{Package}/src/Resources/lang/en/app.php`
   - Add missing keys to: `lang/vendor/{namespace}/vi/app.php`
   - Translate only the new keys manually

### Translating Specific Package

To translate only one package:

```bash
php artisan app:translate-locale --target=vi --namespace=shop
```

Available namespaces: `admin`, `shop`, `installer`, `core`, `product`, `customer`, `attribute`, `data_transfer`, `paypal`

## Adding More Locales

The translation command works for any locale:

```bash
# Translate to French
php artisan app:translate-locale --target=fr

# Translate to Japanese
php artisan app:translate-locale --target=ja
```

Then create a migration to add the locale to the database:

```php
// database/migrations/YYYY_MM_DD_HHMMSS_add_french_locale.php
DB::table('locales')->insertOrIgnore([
    'code' => 'fr',
    'name' => 'Français',
    'direction' => 'ltr',
    'created_at' => now(),
    'updated_at' => now(),
]);
```

## Translation Command Options

```bash
php artisan app:translate-locale
    --target=vi          # Target locale (default: vi)
    --source=en          # Source locale (default: en)
    --namespace=shop     # Specific namespace (optional, translates all if omitted)
```

## Manual Translation Tips

When manually editing translation files:

1. **Preserve placeholders**: Keep `:name`, `:count`, `:attribute`, etc. exactly as-is
2. **Preserve HTML**: Keep tags like `<b>`, `</b>`, `<a href="">` intact
3. **Array structure**: Maintain the exact same array structure as English files
4. **Keys**: Never change array keys, only translate values

Example:
```php
// English
'welcome' => 'Welcome, :name!',

// Vietnamese (correct)
'welcome' => 'Chào mừng, :name!',

// Vietnamese (wrong - placeholder changed)
'welcome' => 'Chào mừng, :ten!',  // ❌ Don't change :name
```

## Troubleshooting

### Translations Not Showing

1. **Clear cache**:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

2. **Check locale is enabled**: Settings → Channels → Edit → Allowed Locales

3. **Verify file structure**: Ensure files are in `lang/vendor/{namespace}/vi/`

### Google Translate Rate Limiting

If you see rate limit errors:
- The command includes a 0.1 second delay between requests
- For very large files, you may need to wait and retry
- Consider translating one namespace at a time: `--namespace=admin`

### Translation Quality

Auto-translated text may need manual review:
- Technical terms might need adjustment
- Context-specific phrases may need refinement
- Review customer-facing strings (Shop package) first

## Files Created

- `app/Console/Commands/TranslateLocale.php` - Translation command
- `database/migrations/2026_02_21_000001_add_vietnamese_locale.php` - Locale migration
- `lang/vendor/*/vi/*.php` - All translation files (generated by command)

## References

- [Laravel Package Translations](https://laravel.com/docs/localization#overriding-package-language-files)
- [Bagisto Localization](https://bagisto.com/en/ecommerce-language-translation-in-bagisto)
- [Google Translate PHP Package](https://github.com/Stichoza/google-translate-php)
