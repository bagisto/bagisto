# CHANGELOG for master

This changelog consists of the bug & security fixes and new features being included in the releases listed below.

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
