# CHANGELOG for master

This changelog consists of the bug & security fixes and new features being included in the releases listed below.

## Unreleased

- Added PostgreSQL support with database grammar abstraction layer, model boolean casts, and cross-database query compatibility.

- Upgraded to Laravel 13 on PHP 8.4 with Pest 5 / PHPUnit 13, refreshed config and skeleton files, and major bumps across `nestedset`, `l5-repository`, `concord`, `tinker` and `debugbar`.

- Upgraded the Admin, Shop and Installer frontends to Tailwind CSS 4, moving configuration into each `app.css` under `@theme` and replacing the JavaScript config and PostCSS pipeline.

- Reworked the icon fonts as Tailwind utilities declared in `@theme`, renaming number-named glyphs (`icon-cancel-1` → `icon-close`), dropping unused ones, and adding a test that guards every icon.

- Moved image processing onto Laravel's `Illuminate\Image` component, dropping the Intervention wrapper and moving the driver setting from `config/image.php` to `config/images.php`.

- Published production Docker images for PostgreSQL alongside MySQL as `<version>-<server>-<database>`, such as `2.5.0-nginx-postgres`; existing MySQL tags are unchanged.

- Refactored core search architecture to engine-agnostic design using Strategy + Manager patterns.

- Added Amazon S3 and Cloudflare R2 as storage drivers, chosen in Configuration → File Management; the local disk stays the default.

- Added a Search Engines configuration section, gathering the engine, the per-context search modes and the Elasticsearch host, credentials and index prefix — which had been settable only in the environment file — into one place with a connection test, and a migration carrying the stored values over.

- Enhanced the Admin → Configuration sections, reordered into General, Magic AI, Sales, Catalog, Customer, Email, Search Engines, File Management and Cache Management with a clean sort sequence, and moved the Buy Now toggle and the My Cart settings to the pages they belong to, with a migration carrying the stored values over.

- #11209 [feature] - Added Omnibus package for EU Omnibus Directive compliance, recording per-channel lowest-price snapshots and showing the 30-day historical low for discounted products.

- #11423 [fixed] - Fixed the storefront order details tabs staying on the loading shimmer, the same discarded slot that left the product page tabs empty.

- #11422 [fixed] - Fixed the product page tabs staying on the loading shimmer. The component emitted a Vue slot in place of the Blade slot holding them, so every tab item was dropped.

- #11442 [fixed] - Fixed the blank space below the product view page, caused by a height class that Tailwind 4 resolves and Tailwind 3 ignored.

- #10816 [fixed] - Fixed theme section images breaking when the store domain changed or a remote disk was used; paths are now stored bare and resolved when rendered.