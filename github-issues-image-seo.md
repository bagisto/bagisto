# GitHub Issues — Admin Image SEO feature

Five separate issues, each ready to paste into Bagisto's 🐛 Bug report form
(`.github/ISSUE_TEMPLATE/bug.yml`). One heading = one issue.

Shared preconditions for all five:

- Bagisto `2.5.x-dev`, PHP 8.4, Laravel 13, MySQL 8
- Feature commit `ec953d97d2` — *feat: image seo added and rma issue fixed*
- Admin → Catalog → Products → edit a product → **Images** panel → hover an image → **edit** (pencil) icon → **Image SEO** drawer

---

## Issue 1 — Alt text entered in a non-default locale disappears when reopening the image SEO drawer

**Bagisto Version(s) affected**

2.5.x-dev

**Issue Description**

Alt text entered in the Image SEO drawer while the product locale switcher is set to a non-default locale is saved to the database correctly, but is never shown again. Reopening the product on that same locale shows an empty Alt Text field, so the feature looks broken and the admin retypes the value every time.

The cause is a mismatch between the locale used to write and the locale used to read:

- **Write** — `packages/Webkul/Product/src/Repositories/ProductMediaRepository.php:169` saves to `core()->getRequestedLocaleCode()`, which is `request()->get('locale')`. The product edit form has no `action`, so it posts to the current URL including `?locale=ar`. This is the locale switcher's locale, which is correct.
- **Read** — the drawer is populated from `json_encode($product->images)`. `alt_text` is a translated attribute, so it resolves through `packages/Webkul/Core/src/Eloquent/TranslatableModel.php:29`, which returns `config('translatable.locale') ?: app()->getLocale()`. Nothing in the admin calls `app()->setLocale()` from the `?locale=` parameter — there is no admin locale middleware — so this is always `config('app.locale')`.

The locale switcher therefore steers the write and is ignored by the read.

**Preconditions**

The default channel must have more than one locale, so the locale switcher is visible in the product edit header. Tested with `en` (default) and `ar`.

**Steps to reproduce**

1. Go to Admin → Catalog → Products and edit any product that has at least one image.
2. Switch the locale dropdown in the page header to Arabic. The URL becomes `.../edit?locale=ar`.
3. Hover the image and click the edit (pencil) icon to open the Image SEO drawer.
4. Type `ARABIC ALT TEXT` into **Alt Text**, click **Done**, then **Save Product**.
5. Reopen the same product, still on `?locale=ar`, and open the drawer again.

**Expected Result**

The drawer shows `ARABIC ALT TEXT` — the alt text of the locale currently selected in the switcher.

**Actual Result**

The Alt Text field is empty. The database is correct: `product_image_translations` holds `ARABIC ALT TEXT` for `locale = ar`. The edit page renders the `en` row instead, or nothing when there is no `en` row.

**Additional context**

The same pattern appears in the other media SEO screens added by the same commit — every write uses the request locale while every read uses the application locale:

| Screen | Write | Read |
|---|---|---|
| Product images | `ProductMediaRepository.php:169` | `json_encode($product->images)` |
| Category logo / banner | `CategoryRepository.php:354` | `catalog/categories/edit.blade.php:205,228` |
| Channel logo | `ChannelRepository.php:143` | `settings/channels/edit.blade.php:307` |
| Attribute option swatch | `AttributeOptionRepository.php:141` | `catalog/attributes/edit.blade.php` |

Confirmed for categories: writing `AR LOGO ALT` under `?locale=ar` stores it correctly, and the category edit page on `?locale=ar` renders `NULL`.

The tests shipped with the feature (`packages/Webkul/Admin/tests/Feature/Catalog/Products/ProductImageSeoTest.php`) all pass, because the locale test uses `app()->setLocale()` — which is exactly what the real admin request never does.

---

## Issue 2 — Saving the product on another locale silently overwrites that locale's alt text

**Bagisto Version(s) affected**

2.5.x-dev

**Issue Description**

Because the edit page renders the **default** locale's alt text into the hidden metadata input, and the form posts that value back under the **switcher's** locale, simply pressing Save while a translated locale is selected copies the default locale's alt text over the translated one. No warning is shown, and the drawer never has to be opened for this to happen.

The metadata is submitted from a hidden input kept outside the drawer so it posts whether or not the drawer was opened — `packages/Webkul/Admin/src/Resources/views/components/media/images.blade.php:425`:

```blade
:value="image.alt_text ?? ''"
```

`image.alt_text` comes from the read path, i.e. the application locale. `ProductMediaRepository::saveAltText()` then writes it to the request locale, so every save on a non-default locale performs a copy from the default locale into it.

`config/translatable.php` has `use_fallback => true` with `fallback_locale => en`, which makes this worse: a locale with no alt text of its own reads the English one, so the first save on that locale writes English into it, and it can no longer be told apart from a real translation.

**Preconditions**

The default channel must have more than one locale. Tested with `en` (default) and `ar`.

**Steps to reproduce**

1. Edit a product with an image, locale switcher on English. Set the alt text to `ENGLISH ALT` and save.
2. Switch to Arabic, set the alt text to `ARABIC ALT`, and save. The `ar` row in `product_image_translations` now holds `ARABIC ALT`.
3. Reload the edit page on `?locale=ar`. Do not open the drawer. Do not change anything.
4. Press **Save Product**.
5. Inspect `product_image_translations` for that image.

**Expected Result**

Saving without editing anything leaves every locale exactly as it was. The `ar` row still holds `ARABIC ALT`.

**Actual Result**

The `ar` row holds `ENGLISH ALT`. The Arabic translation is gone, with no warning.

**Additional context**

Reproduced end to end against the real route, `PUT admin/catalog/products/{id}?locale=ar`. Same root cause as the disappearing-alt-text issue; both need fixing together, since fixing only the read would still leave the fallback copying English into an untranslated locale on first save.

---

## Issue 3 — Product image panel does not render when APP_LOCALE is French or Italian

**Bagisto Version(s) affected**

2.5.x-dev

**Issue Description**

On an install whose `APP_LOCALE` is `fr` or `it`, the uploaded-image tiles in the product edit screen do not render at all. Existing images cannot be seen, reordered, replaced, deleted, or SEO-edited.

The alt-text placeholder is interpolated straight into a Vue binding expression as a single-quoted JavaScript string, at `packages/Webkul/Admin/src/Resources/views/components/media/images.blade.php:468`:

```blade
:placeholder="'@lang('admin::app.components.media.images.seo.alt-text-placeholder')'"
```

`@lang` emits unescaped output, and two locales have an apostrophe in that string:

- `fr` — `Décrivez ce que montre l'image`
- `it` — `Descrivi ciò che è mostrato nell'immagine`

so the rendered attribute is:

```html
:placeholder="'Décrivez ce que montre l'image'"
```

which is not a valid JavaScript expression. The template lives in a `<script type="text/x-template">` block compiled by Vue's runtime compiler, so the `v-media-image-item` component throws a template compilation error and renders nothing.

**Preconditions**

`APP_LOCALE=fr` or `APP_LOCALE=it` in `.env`, followed by `php artisan optimize:clear`. A product with at least one saved image.

**Steps to reproduce**

1. Set `APP_LOCALE=fr` in `.env`.
2. Run `php artisan optimize:clear`.
3. Go to Admin → Catalog → Products and edit a product that has at least one image.
4. Look at the Images panel and open the browser console.

**Expected Result**

The image tiles render, with the apostrophe shown literally in the placeholder.

**Actual Result**

No image tiles are rendered. The browser console reports a Vue template compilation error on `v-media-image-item`.

**Additional context**

Suggested fix: pass the translated string through `json_encode()`, or bind it from a data property, instead of wrapping `@lang` in single quotes.

Three other call sites use the same unsafe pattern and are latent — the strings they interpolate happen to be apostrophe-free in all 30 locales today, but any future translation with an apostrophe would break them the same way:

- `packages/Webkul/Admin/src/Resources/views/components/media/videos.blade.php:191`
- `packages/Webkul/Admin/src/Resources/views/catalog/attributes/create.blade.php:268,275`
- `packages/Webkul/Admin/src/Resources/views/catalog/attributes/edit.blade.php:283,291`

---

## Issue 4 — Replace Image destroys the alt text of every other locale

**Bagisto Version(s) affected**

2.5.x-dev

**Issue Description**

Using **Replace Image** inside the Image SEO drawer keeps the alt text of the locale currently selected and silently drops it for every other locale. The image also silently gets a new primary key.

When SEO is enabled the file input is named per image id — `packages/Webkul/Admin/src/Resources/views/components/media/images.blade.php:407` — so a replacement arrives as `images[files][<id>]` holding an `UploadedFile`. `ProductMediaRepository::upload()` sends anything that is an `UploadedFile` down the **create** branch: a brand-new `product_images` row is inserted, and the old id — never removed from `$previousIds` — is deleted at the end of the method. The new row starts with no translations, and `saveAltText()` then fills in only `core()->getRequestedLocaleCode()`.

**Preconditions**

The default channel must have more than one locale. Tested with `en` (default) and `ar`.

**Steps to reproduce**

1. Edit a product with an image. Give it an alt text on English and save.
2. Switch to Arabic, give the same image a different alt text, and save. Both rows now exist in `product_image_translations`.
3. Switch back to English. Hover the image, open the Image SEO drawer and click **Replace Image**.
4. Pick a different file, click **Done**, then **Save Product**.
5. Inspect `product_image_translations` for that product.

**Expected Result**

Replacing the file swaps the file only. Alt text in every locale survives, and the image row keeps its id.

**Actual Result**

The `ar` row no longer exists — only the English alt text survives. The image has a new id, which quietly invalidates anything that referenced the old one.

**Additional context**

There is a related side effect in the same code path: because the replacement is written before the old row is deleted, `MediaFileName::resolve()` still sees the old file on disk and falls into its collision loop. An image whose SEO file name is `blue-shoe` comes back as `blue-shoe-1.webp`, then `blue-shoe.webp`, then `blue-shoe-1.webp`, alternating on each replace — so the public image URL changes on every replace, which is the opposite of what an SEO file-name feature should do. Happy to raise that separately if preferred.

---

## Issue 5 — An over-long alt text rejects the entire product save with nothing shown

**Bagisto Version(s) affected**

2.5.x-dev

**Issue Description**

Entering more than 255 characters of alt text causes the whole product save to be rejected. The admin is returned to the edit page with no error anywhere on screen, no flash message, and the typed alt text discarded. It looks as though the Save button did nothing.

`packages/Webkul/Admin/src/Http/Requests/ProductForm.php:86-87` validates `images.meta.*.alt_text` at `max:255` and `images.meta.*.file_name` at `max:150`, but:

- Neither drawer input carries a `maxlength`, so nothing stops the admin while typing.
- The error is handed to vee-validate as `:initial-errors` under the key `images.meta.<id>.alt_text`, and no control registers that name. The product edit page has only `<x-admin::form.control-group.error control-name='images.files[0]' />`. The message is present in the HTML source and rendered nowhere.

**Preconditions**

Any product with at least one image. Single locale is enough — this is not locale-dependent.

**Steps to reproduce**

1. Go to Admin → Catalog → Products and edit a product that has at least one image.
2. Hover the image and click the edit (pencil) icon to open the Image SEO drawer.
3. Paste 256 or more characters into **Alt Text**.
4. Click **Done**, then **Save Product**.

**Expected Result**

A visible validation error next to the Alt Text field, plus a `maxlength` on the input so the limit cannot be exceeded in the first place.

**Actual Result**

The page reloads on the edit screen with no visible error at all. The product is not saved and the typed alt text is lost. `session('errors')` contains `images.meta.<id>.alt_text`, but it only reaches the page inside the `:initial-errors` attribute of the form tag.

**Additional context**

The generated message is also raw and untranslated:

> The images.meta.363.alt_text field must not be greater than 255 characters.

It leaks the internal array path and the database id, and has no `:attribute` translation behind it, so it would still read wrong once it is displayed.

The `file_name` field has the same problem at `max:150`.
