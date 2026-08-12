# CHANGELOG for v2.4

This changelog consists of the bug & security fixes and new features being included in the releases listed below.

## Unreleased

- Added the PayGlocal payment gateway. Customers pay on PayGlocal's hosted checkout and return through a signed token, the outcome is confirmed with PayGlocal before an order is placed, and a webhook settles the payment if the customer never comes back.

- Fixed customer and cart reporting counting all guests as a single shopper, which understated unique customers and piled every guest's spend onto one row of the "most sales" and "most orders" listings.

- Fixed resending an EU withdrawal confirmation answering the admin in the customer's language, because the locale switched for the email was still in place when the message was built.

- Locales and currencies are now listed in one place, shared by the console and web installers, the seeders and the translation checker, so adding either takes one entry rather than five.

- Fixed the Bahraini Dinar being seeded with the text "BHD" in place of its symbol, which a repeated entry in the currency list had been overwriting.

- Fixed PayU and PhonePe starting a payment in any currency, when both settle only in Indian Rupees — a store on a currency without two decimal places was charged a hundred times over. Either now refuses a cart it cannot settle, so a store on another currency will find them declining at checkout rather than taking the wrong amount.

- Added the missing Romanian translations for PhonePe.

- Fixed the admin product datagrid ignoring search synonyms while the mega search honoured them, so the same word found different products in the two places.

- Fixed the mega search leaving you on an empty tab when another tab had results, which read as nothing being found. It now opens the first tab that matched.

- Products that keep no stock are now listed as "Stock Disabled" rather than "Out of Stock", read from the product's own manage-inventory setting rather than assumed from its type.

- The admin product datagrid no longer joins and groups over five tables to draw a page. Quantity, image count, base image, category names, family name and the manage-inventory flag are kept on the flat table and refreshed as their sources change — including on refunds, order cancellations, imports, category and family renames, and inventory-source deletion, which previously left the grid showing figures no longer true.

- Fixed the Category column showing one arbitrary category for a product filed under several; it now lists them all.

- Fixed a full Elasticsearch reindex leaving behind documents whose product had been deleted. The admin grid pages on the count Elasticsearch reports, so those documents claimed a total the grid could not fill and scattered blank pages through the listing.

- Fixed sorting the admin product datagrid by SKU or quantity failing outright in Elasticsearch mode, and sorting on a column an older index has never held now leaves the order alone instead of erroring.

## **v2.4.9 (5th of August 2026)** - *Release*

- Security fixes.

- Enhanced Data Transfer imports. Saving an import now runs it through to the end on its own — validate, fetch images, create, link, index — with a stepper and live progress for each phase, instead of a separate click per phase. Validation runs in windows, either browser-driven or dispatched across the queue workers, so a large file no longer has to be validated in one request. Product images can now come from links in the sheet, a ZIP uploaded with the import, or a directory on the server, saved on the import so reopening it restores the choice; links are fetched once each in a phase of their own before any row is written. The chosen image source is validated against the file, so a mismatch is reported during validation rather than silently importing every product without images. Also fixed a queued import re-dispatching its whole job chain on every poll, which ran the same rows two or three times over — booking the repeats as updates and deadlocking against itself — and fixed the runs that could stall for good in the image or create phase, which now complete what they can and report the batches that did not.

- Made the admin datagrid header consistent between the default grid and customized ones, and gave customized grids a proper card layout on small screens — the column header is dropped there, since filter and sort are reachable from the bar fixed to the foot of the screen. Loading placeholders were rebuilt to match the grid they stand in for, on both desktop and mobile, so the layout no longer shifts when the rows arrive.

- Fixed the admin product datagrid and storefront search failing when an Elasticsearch index is missing, by passing `ignore_unavailable` on the searches that address an index by name.

- Fixed `top-left`, `top-right` and the fallback position of the admin dropdown throwing at runtime — two adjacent template literals with no comma between them parse as a tagged template — and made the `fit-toggle` option apply to every position rather than only `bottom-left`.

- Fixed the WebMCP tool declarations rendering at the foot of every storefront page. The forms are read by agents rather than drawn, and only their submit buttons were hidden, so the two carrying a text input showed it; the whole block is hidden now, and the tools are still discovered exactly as before.

- Fixed the social-login icons sitting flush against the Sign In button on the customer login page. The partial is injected through an event, so it carries its own spacing rather than relying on whatever it lands under.

- Fixed the demo product descriptions showing `\r` and `\n` as text. The escapes had been written into the seed data as literal characters rather than line breaks; the paragraphs they were meant to mark are now real ones.

- Replaced the GDPR icon in the admin configuration listing, which was drawn as a leaf rather than a shield.

- Turned the speculation rules off by default. They have the browser fetch pages nobody has opened yet, which costs bandwidth and shows up as traffic on pages that were never visited, so a store now opts in.

- Fixed an inactive cart still being assigned from the session.

- Reworked the admin menu to support three levels, and tidied the category and role trees.

- Fixed assorted storefront UI issues on the product view and sub-category pages, back navigation on the RMA pages on mobile, and the position of the rule buttons on the cart-rule create page.

- Removed the operator selector from multiselect and checkbox conditions on cart rules, where it had no meaning.

- Refined Playwright testcases.

- #11400 [fixed] - Sanitized the product description in the JSON-LD rich-snippet output, which previously emitted it unescaped.

- #11387 [fixed] - Fixed the demo products being absent from the storefront when the install locale did not include English, while still listed in the admin. The seeder wrote each product's non-translatable attributes — status, visibility, price, sku and the variant options — only on the English pass, so choosing any other locale on its own left those values unwritten and every product failed the storefront's status and visibility checks.

- #11380 [fixed] - Fixed product duplication not carrying the customizable options, their labels, or their prices over to the copy. The label is a translated value held in its own table, so it needs replicating alongside the option rather than travelling with it — every locale's label is now copied.

## **v2.4.8 (8th of July 2026)** - *Release*

- Security fixes.

- Added a "remove item" affordance to the storefront quantity selector: on the cart and mini-cart, when a line item's quantity reaches the minimum (1) the minus icon is replaced with a trash icon that removes the item. Enabled via an opt-in `removable` prop on the `quantity-changer` component (default off), so add-to-cart quantity selectors on product, bundle, grouped, booking, and wishlist pages keep their existing behavior.

- Fixed the installer's `.env` parser (`EnvironmentManager::getEnvVariable()`) truncating environment values that contain an `=` (e.g. `DB_PASSWORD="examplePassword="`), which caused database authentication failures during `php artisan bagisto:install` even though the same `.env` worked with standard Laravel commands. Values are now split on the first `=` only; the parser also preserves spaces inside quoted values and matches keys exactly instead of by substring.

- Made sitemaps channel-wise: a sitemap can now be assigned to one or more channels (via a new `sitemap_channels` pivot table), and the sitemap files are generated per channel — each using that channel's hostname, root-category subtree, and channel-scoped products and CMS pages, written under `sitemaps/<channel-code>/`. The admin sitemap listing shows the associated channels and a per-channel "Link for Google" for each entry.

## **v2.4.7 (24th of June 2026)** - *Release*

- Security fixes.

- "Apply Tax On" setting (Before/After Discount) under Sales → Taxes → Calculation Settings to control whether tax is calculated on the original product price or on the discounted price when a cart-rule/coupon discount is applied, plus an optional per-product tax breakdown in the cart and checkout summary.

- #11344 [fixed] - Fixed incorrect payment amounts for currencies whose ISO 4217 minor units are not two decimals in the Stripe and PayPal gateways. Currencies are now seeded with a `decimal` value (e.g. `0` for JPY/KRW, `3` for KWD/BHD, `2` by default); Stripe converts amounts to the smallest currency unit using `10 ^ decimal` instead of a hardcoded `× 100`, and PayPal rounds to the currency's decimal places, preventing 100× overcharges on zero-decimal currencies and undercharges on three-decimal currencies.

- #11331 [fixed] - Replaced deprecated Venezuelan Bolivar currency code `VEF` with `VES` in seeders and installer configurations, ensuring correct exchange rate synchronization with modern exchange rate APIs.

- #11316 [fixed] - Fixed the rich-text (TinyMCE) sanitizer stripping tables and `class`/`style` attributes from product/category descriptions, CMS pages, and email templates on save, so tables and formatting inserted in the editor no longer disappear. The HTMLPurifier allowlist now permits table elements and `class`/`style` (with an expanded set of safe CSS properties) while still removing scripts, event handlers, `javascript:`/`data:` URLs, and unsafe CSS. Also fixed theme static-content custom CSS being corrupted by the HTML purifier — valid selectors such as the `>` child combinator were entity-encoded — by sanitizing it against `</style>` context breakout instead of running it through the HTML purifier.

- #10963 [fixed] - Fixed a required multiselect attribute on the product edit page failing validation on save until the field was manually re-interacted with, because the previously saved values were not registered with the form validator on page load.

## **v2.4.6 (5th of June 2026)** - *Release*

* Help and resources added.

* Security fixes.

## **v2.4.5 (2nd of June 2026)** - *Release*

* Added EU Withdrawal feature (Directive (EU) 2023/2673, Article 11a CRD) — customers and guests can withdraw from a contract online via a "Withdraw from contract" button on order pages and a public lookup form, with durable-medium confirmation emails, an admin datagrid + timeline view for managing withdrawals, and a per-channel enable toggle.

* Added PhonePe payment gateway integration.

* Refined Playwright testcases.

* Security fixes.

## **v2.4.4 (5th of May 2026)** - *Release*

* Fixed wrong "From" and "To" dates on the admin Bookings data grid and calendar view caused by the Carbon 3 timezone behavior change in the Laravel 12 upgrade. `Carbon::createFromTimestamp()` now returns UTC by default instead of the app timezone, so the booking timestamps are explicitly converted via `->timezone(config('app.timezone'))` in `BookingDataGrid` and `BookingController`.

* Optimized cart rule evaluation to reduce repeated database lookups during cart total calculation, improving cart and checkout performance.

* Refined the admin cart-rule create/edit pages with a clearer Coupon section, a context-aware Actions card, and a dedicated Generated Coupons datagrid with a modal-based bulk-code generator.

* Refined the storefront cart and onepage checkout summaries with `+` / `−` indicators, a collapsed dual tax-mode display, an expandable Discount breakdown, and a modernized applied-coupon pill.

* #10832 [feature] - Added a "Sales By Coupon" report to the admin sales reporting dashboard, with a coupon-code badge linking to the corresponding cart rule edit page and a drill-down "View Details" listing showing each order that used a coupon (order ID linking to the order detail, coupon code linking to the cart rule).

* #8738 [fixed] - Added column sorting on every reporting list page (Sales / Customers / Products) with sort direction indicators in the column header, fixing the previously non-functional click target.

## **v2.4.3 (24th of April 2026)** - *Release*

* Ported all booking product bug fixes from the 2.3 branch into 2.4. Key highlights:
  - Added admin-side order creation support for booking products across appointment, event, rental, default, and table sub-types.
  - Fixed booking slot overlap detection and corrected the calendar window generation for appointment bookings.
  - Fixed display pricing for rental and event sub-types with a "starting from" price on listings and corrected strike-through pricing.
  - Hardened cart handling for booking items (quantity updates, missing-ticket guards, inverted rental range checks).
  - Fixed booking product import by updating the data-transfer sample files and correcting the importer for booking attributes.

## **v2.4.2 (13th of April 2026)** - *Release*

* Added support for Romanian language.

* Fixed product 404 when locale-specific URL keys differ across locales by adding cross-locale fallback in product slug resolution and locale-aware URL rewrite redirects.

* Upgraded image search to support AI-powered analysis via Laravel AI SDK (MagicAI), with TensorFlow.js as the default fallback. Configurable under Magic AI > Storefront Features > AI Image Search.

* Added Base URL configuration field for Ollama provider in Magic AI settings.

* Fixed RMA rules issues where inactive rules were still selectable on the product create/edit form, and where the "Create" modal would update the last-edited rule after an edit modal had been opened.

* #11220 [fixed] - Fixed SQL injection in DataGrid sort column and unauthenticated path traversal via ImageCache.

* #11212 [fixed] - Fixed TypeError in Carbon when RESPONSE_CACHE_ENABLED is enabled.

* #11013 [fixed] - Fixed an issue where the order date range filter accepted a single date input and returned no results.

## **v2.4.1 (23rd of March 2026)** - **Release**

* Fixed an issue where the price slider was not displaying on the layered navigation.

* Fixed an issue where static content was pointing to demo categories and giving 404 errors when installed without sample products.

* #11207 [fixed] - Performed a major update and cleanup of Polish translations across both Admin and Shop sections.

* #10792 [feature] - Added Cache Management in Admin Configuration panel.

## **v2.4.0 (19th of March 2026)** - **Release**

### New Features

* **[Laravel 12 Upgrade]** Upgraded framework to Laravel 12 with comprehensive modernization:
  - Fixed Carbon date/time type strictness issues (int/float parameters, non-null timezones).
  - Modernized all legacy PHP date functions (`strtotime()`, `date()`, `date_default_timezone_set()`) to Carbon equivalents.
  - Implemented timezone fallback logic using `config('app.timezone')` for channel-based operations.
  - Updated PDF response headers to match Laravel 12 format (Content-Disposition).
  - Enhanced date handling methods in Core helper with proper Carbon integration.

* Implemented two-factor authentication (2FA) for admin users to enhance account security.

* Migrated from Google reCAPTCHA v2 to Google reCAPTCHA Enterprise for enhanced bot protection.

* Added Stripe payment gateway integration with secure checkout session.

* Added Razorpay payment gateway integration with drop-in UI checkout experience.

* Added PayU payment gateway integration with redirect-based checkout flow.

* Upgraded PayPal SDK from abandoned v1 to modern v2 with improved reliability and security. Refactored PayPal integration to use controller-based transaction handling and modernized IPN processing with Laravel HTTP client.

* Added comprehensive Return Merchandise Authorization (RMA) system with complete order return management.

* Integrated Laravel AI SDK for Magic AI, refactoring the provider and model layer into per-provider enums with a unified `AiProvider` entry point and updated AI model configurations.

* Added fresh demo products during the installation process with updated translations.

* Added Pest and Playwright test cases.

* #11126 [feature] - Added SMTP configuration support from the admin panel.

### Changes

* Removed `shetabit/visitor` package and all visitor tracking functionality including dashboard visitors widget, products with most visits reporting, customers traffic reporting, and purchase funnel visitor metrics.

### Bug Fixes

* Included all bug fix updates from version 2.3.

* Optimized RMA-related queries and introduced a return period column in the order items table.

* Fixed issues with language switching in the installation wizard and corrected PHP configuration texts.

* Fixed automatic application URL detection and automatic timezone selection during installation.

* Fixed backend validation and VeeValidate error handling to ensure proper integration with Laravel backend validation in the installer package.

* #11100 [fixed] - Fixed an issue where updating the return window rule affected previously placed orders.

### Documentation

* Updated the upgrade guide (UPGRADE.md) with breaking changes from v2.3 including Laravel 12, reCAPTCHA Enterprise, PayPal SDK upgrade, visitor tracking removal, and Magic AI SDK migration.

## **v2.4.0-beta6 (18th of March 2026)** - **Release**

* Removed `shetabit/visitor` package and all visitor tracking functionality including dashboard visitors widget, products with most visits reporting, customers traffic reporting, and purchase funnel visitor metrics.

* Updated the upgrade guide (UPGRADE.md) with breaking changes from v2.3 including Laravel 12, reCAPTCHA Enterprise, PayPal SDK upgrade, visitor tracking removal, and Magic AI SDK migration.

* Rewrote AGENTS.md with accurate codebase documentation covering architecture, conventions, commands, and development guidelines.

## **v2.4.0-beta5 (18th of March 2026)** - **Release**

* Included bug fix updates from version 2.3.

## **v2.4.0-beta4 (5th of March 2026)** - **Release**

* Enhanced the Laravel AI SDK integration for Magic AI and improved the related configuration sections.

* Updated all outdated AI models and image model configurations.

## **v2.4.0-beta3 (3rd of March 2026)** - **Release**

* Integrated Laravel AI SDK for Magic AI, refactoring the provider and model layer into per-provider enums with a unified `AiProvider` entry point.

* #11126 [feature] - Added SMTP configuration support from the admin panel.

* Merged all bug fixes and improvements from version 2.3.

* Added pest and playwright testcases.

## **v2.4.0-beta2 (17th of February 2026)** - **Release**

* Updated the translations for all the dummy products.

* Optimized RMA-related queries and introduced a return period column in the order items table.

* Fixed issues with language switching in the installation wizard and corrected PHP configuration texts.

* Fixed automatic application URL detection and automatic timezone selection during installation.

* Fixed backend validation and VeeValidate error handling to ensure proper integration with Laravel backend validation in the installer package.

* #11100 [fixed] - Fixed an issue where updating the return window rule affected previously placed orders.

## **v2.4.0-beta1 (9th of February 2026)** - **Release**

* **[Laravel 12 Upgrade]** Upgraded framework to Laravel 12 with comprehensive modernization:
  - Fixed Carbon date/time type strictness issues (int/float parameters, non-null timezones).
  - Modernized all legacy PHP date functions (`strtotime()`, `date()`, `date_default_timezone_set()`) to Carbon equivalents.
  - Implemented timezone fallback logic using `config('app.timezone')` for channel-based operations.
  - Updated PDF response headers to match Laravel 12 format (Content-Disposition).
  - Enhanced date handling methods in Core helper with proper Carbon integration.

* Implemented two-factor authentication (2FA) for admin users to enhance account security.

* Migrated from Google reCAPTCHA v2 to Google reCAPTCHA Enterprise for enhanced bot protection.

* Added Stripe payment gateway integration with secure checkout session.

* Added Razorpay payment gateway integration with drop-in UI checkout experience.

* Added PayU payment gateway integration with redirect-based checkout flow.

* Upgraded PayPal SDK from abandoned v1 to modern v2 with improved reliability and security. Refactored PayPal integration to use controller-based transaction handling and modernized IPN processing with Laravel HTTP client.

* Added comprehensive Return Merchandise Authorization (RMA) system with complete order return management.

* Added fresh demo products during the installation process.
