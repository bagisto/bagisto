# CHANGELOG for master

This changelog consists of the bug & security fixes and new features being included in the releases listed below.

## Unreleased

- Added PostgreSQL support with database grammar abstraction layer, model boolean casts, and cross-database query compatibility.

- Upgraded to Laravel 13 on PHP 8.4 with Pest 5 / PHPUnit 13, refreshed config and skeleton files, and major bumps across `nestedset`, `l5-repository`, `concord`, `tinker` and `debugbar`.

- Upgraded the Admin, Shop and Installer frontends to Tailwind CSS 4, moving configuration into each `app.css` under `@theme` and replacing the JavaScript config and PostCSS pipeline.

- Reworked the icon fonts as Tailwind utilities declared in `@theme`, renaming number-named glyphs (`icon-cancel-1` → `icon-close`), dropping unused ones, and adding a test that guards every icon.

- Published production Docker images for PostgreSQL alongside MySQL as `<version>-<server>-<database>`, such as `2.5.0-nginx-postgres`; existing MySQL tags are unchanged.

- Moved image processing onto Laravel's `Illuminate\Image` component, dropping the Intervention wrapper and moving the driver setting from `config/image.php` to `config/images.php`.

- Refactored core search architecture to engine-agnostic design using Strategy + Manager patterns.

- #11209 [feature] - Added Omnibus package for EU Omnibus Directive compliance, recording per-channel lowest-price snapshots and showing the 30-day historical low for discounted products.
