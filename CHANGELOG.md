# CHANGELOG for master

This changelog consists of the bug & security fixes and new features being included in the releases listed below.

## Unreleased

- Added PostgreSQL support with database grammar abstraction layer, model boolean casts, and cross-database query compatibility.

- Upgraded to Laravel 13 on PHP 8.4. The dependencies moved with it — Pest 4 and PHPUnit 12 for the test suite, and majors of `nestedset`, `l5-repository`, `concord`, `tinker`, `debugbar`, `laravel-responsecache` and `laravel-sitemap`. `artisan` and `public/index.php` were rewritten to the current skeleton, which had stayed on the pre-Laravel-11 shape, and the config files were brought up to date: the keys Laravel renamed are now spelled the current way, and the options Laravel 13 added — deferred, background and failover queue connections, the storage and failover cache stores, redis retry backoff, a monthly log channel and explicit session serialisation — are present rather than implied. Session serialisation is pinned to `php` rather than the framework's new `json` default, so sessions written before the upgrade still read back.

- Upgraded the Admin, Shop and Installer frontends from Tailwind CSS 3 to 4. The JavaScript config and the PostCSS pipeline are gone — v4 reads its configuration from CSS, so each `app.css` now carries the breakpoints, colours and fonts in an `@theme` block and the build runs through the official Vite plugin. Utility classes were renamed to match v4 throughout the Blade templates, and the handful of behaviours v4 dropped from its base layer are restored explicitly, so borders, button cursors, placeholder colour and the body font render as they did before rather than shifting under everyone.

- Reworked the icon fonts as Tailwind utilities. Each glyph is declared once in `@theme` and emitted by a single functional utility, which keeps the variants the admin relies on — `peer-checked:`, `rtl:` — working. Icons named after a number rather than what they depict were renamed (`icon-cancel-1` is now `icon-close`, `icon-customer-2` is `icon-customer`, and in the storefront `icon-filter-1`, `icon-compare-1` and `icon-sort-1` are `icon-funnel`, `icon-swap` and `icon-sort`), glyphs no view referenced were dropped, and a dozen icons that had been silently rendering blank because nothing declared them now resolve. A test guards all of it: an icon used but never declared, or declared and never used, fails the suite instead of quietly painting nothing.

- Published production Docker images for PostgreSQL alongside MySQL. Every web server is now built against both databases, giving `<version>-<server>-<database>` names such as `2.5.0-nginx-postgres`. MySQL images keep answering to the shorter `<version>-<server>` name, and nginx on MySQL to the bare `<version>` and `latest`, so an existing pull is unaffected. Everything specific to a database sits behind a fixed contract in `docker/production/shared/db/<engine>/`, so the build and entrypoint scripts no longer mention one by name.

- Refactored core search architecture to engine-agnostic design using Strategy + Manager patterns.

- #11209 [feature] - Added Omnibus package for EU Omnibus Directive compliance — records per-channel, per-currency lowest-price snapshots, exposes the 30-day historical low on the storefront for discounted products, and provides per-type providers for simple, configurable, grouped, and bundle products.
