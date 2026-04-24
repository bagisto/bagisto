# CHANGELOG for master

This changelog consists of the bug & security fixes and new features being included in the releases listed below.

## **v2.3.18 (24th of April 2026)** - *Release*

- Refactored core search architecture to engine-agnostic design using Strategy + Manager patterns.

- Added PostgreSQL support with database grammar abstraction layer, model boolean casts, and cross-database query compatibility.

- #11209 [feature] - Added Omnibus package for EU Omnibus Directive compliance — records per-channel, per-currency lowest-price snapshots, exposes the 30-day historical low on the storefront for discounted products, and provides per-type providers for simple, configurable, grouped, and bundle products.
