<?php

use Illuminate\Support\Facades\Storage;
use Webkul\Installer\Database\Seeders\Shop\SectionTableSeeder;
use Webkul\Theme\Models\Section;

/**
 * Reduce a recorded media value to the path it names on the disk.
 */
function storedMediaPath(string $value): string
{
    return preg_replace('#^(?:/?storage/|__media__/|/)#', '', trim($value));
}

/**
 * The section ids every seeded media path is filed under, as "section {id} -> sections/{directory}".
 */
function seededMediaOwnership(): array
{
    $ownership = [];

    foreach (Section::with('translations')->get() as $section) {
        foreach ($section->translations as $translation) {
            $options = (array) $translation->options;

            $directories = [];

            foreach ((array) ($options['images'] ?? []) as $image) {
                preg_match_all('#sections/(\d+)/#', (string) ($image['image'] ?? ''), $matches);

                $directories = array_merge($directories, $matches[1]);
            }

            foreach (['html', 'css'] as $key) {
                preg_match_all('#sections/(\d+)/#', (string) ($options[$key] ?? ''), $matches);

                $directories = array_merge($directories, $matches[1]);
            }

            foreach (array_unique($directories) as $directory) {
                $ownership[] = 'section '.$section->id.' -> sections/'.$directory;
            }
        }
    }

    return array_values(array_unique($ownership));
}

// ============================================================================
// Media Ownership
// ============================================================================

it('should file every seeded upload under the section that owns it', function () {
    Storage::fake();

    (new SectionTableSeeder)->run(['default_locale' => 'en', 'allowed_locales' => ['en']]);

    $misfiled = array_values(array_filter(
        seededMediaOwnership(),
        fn (string $entry) => ! preg_match('#^section (\d+) -> sections/\1$#', $entry),
    ));

    expect($misfiled)->toBeEmpty();
});

it('should store every seeded upload it records a path for', function () {
    Storage::fake();

    (new SectionTableSeeder)->run(['default_locale' => 'en', 'allowed_locales' => ['en']]);

    $missing = [];

    foreach (Section::with('translations')->get() as $section) {
        foreach ($section->translations as $translation) {
            $options = (array) $translation->options;

            foreach ((array) ($options['images'] ?? []) as $image) {
                $path = storedMediaPath((string) ($image['image'] ?? ''));

                if (
                    $path !== ''
                    && ! Storage::exists($path)
                ) {
                    $missing[] = $path;
                }
            }

            preg_match_all('#(?:__media__/|/storage/|/)?(themes/\S+?)["\s)]#', (string) ($options['html'] ?? ''), $matches);

            foreach ($matches[1] as $path) {
                if (! Storage::exists($path)) {
                    $missing[] = $path;
                }
            }
        }
    }

    expect($missing)->toBeEmpty();
});
