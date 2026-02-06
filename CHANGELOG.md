# CHANGELOG for master

This changelog consists of the bug & security fixes and new features being included in the releases listed below.

## **v2.4.0-beta1 (21st of January 2026)** - **Release**

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
