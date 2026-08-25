<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The option keys that hold an authored document rather than a stored value.
     */
    protected const DOCUMENT_KEYS = ['html', 'css'];

    /**
     * The directories a section has ever kept its uploads in.
     */
    protected const MEDIA_DIRECTORIES = 'themes|theme|section';

    /**
     * Run the migrations.
     *
     * A link stored as a whole url does not survive a change of domain, and an
     * upload addressed relative to the page does not survive a nested url.
     */
    public function up(): void
    {
        $this->rewrite(
            fn (string $value) => $this->asPath($value),
            fn (string $markup) => $this->fromRoot($markup)
        );
    }

    /**
     * Reverse the migrations.
     *
     * A link is put back as a url on the current domain. The markup keeps its
     * root-relative addresses, which are correct on any page it is rendered on.
     */
    public function down(): void
    {
        $this->rewrite(
            fn (string $value) => $this->asUrl($value),
            fn (string $markup) => $markup
        );
    }

    /**
     * Apply the callbacks to every stored value a section records, live and drafted.
     */
    protected function rewrite(callable $value, callable $document): void
    {
        if (! Schema::hasTable('theme_section_translations')) {
            return;
        }

        $columns = ['options'];

        if (Schema::hasColumn('theme_section_translations', 'draft_options')) {
            $columns[] = 'draft_options';
        }

        DB::table('theme_section_translations')
            ->select(array_merge(['id'], $columns))
            ->orderBy('id')
            ->each(function ($row) use ($columns, $value, $document) {
                $changes = [];

                foreach ($columns as $column) {
                    $decoded = json_decode((string) $row->{$column}, true);

                    if (! is_array($decoded)) {
                        continue;
                    }

                    $rewritten = $this->walk($decoded, $value, $document);

                    if ($rewritten === $decoded) {
                        continue;
                    }

                    $changes[$column] = json_encode($rewritten);
                }

                if (empty($changes)) {
                    return;
                }

                DB::table('theme_section_translations')->where('id', $row->id)->update($changes);
            });
    }

    /**
     * Walk the option tree, sending documents and plain values to their own callback.
     */
    protected function walk(array $options, callable $value, callable $document): array
    {
        foreach ($options as $key => $item) {
            if (is_array($item)) {
                $options[$key] = $this->walk($item, $value, $document);

                continue;
            }

            if (! is_string($item)) {
                continue;
            }

            $options[$key] = in_array($key, self::DOCUMENT_KEYS, true)
                ? $document($item)
                : $value($item);
        }

        return $options;
    }

    /**
     * Reduce a url on this site to the path it points at.
     *
     * A link somewhere else keeps its host, and anything that is not a url — a mail
     * address, an anchor, a path already — is left as it is.
     */
    protected function asPath(string $value): string
    {
        $host = parse_url((string) config('app.url'), PHP_URL_HOST);

        if (
            empty($host)
            || parse_url($value, PHP_URL_HOST) !== $host
        ) {
            return $value;
        }

        $path = ltrim((string) parse_url($value, PHP_URL_PATH), '/');

        foreach (['query' => '?', 'fragment' => '#'] as $part => $prefix) {
            $piece = parse_url($value, $part === 'query' ? PHP_URL_QUERY : PHP_URL_FRAGMENT);

            if (! empty($piece)) {
                $path .= $prefix.$piece;
            }
        }

        return $path;
    }

    /**
     * Put a path back as a url on the current domain.
     *
     * An upload reads the same as a relative link, so the directories a section
     * keeps its uploads in are how the two are told apart.
     */
    protected function asUrl(string $value): string
    {
        if (
            $value === ''
            || parse_url($value, PHP_URL_SCHEME)
            || str_starts_with($value, '#')
            || str_starts_with($value, '//')
            || preg_match('#^/?('.self::MEDIA_DIRECTORIES.')/#', $value)
        ) {
            return $value;
        }

        return rtrim((string) config('app.url'), '/').'/'.ltrim($value, '/');
    }

    /**
     * Address every upload in the markup from the site root.
     */
    protected function fromRoot(string $markup): string
    {
        return preg_replace(
            '#(src|data-src|href|poster)=(["\'])storage/('.self::MEDIA_DIRECTORIES.')/#',
            '$1=$2/storage/$3/',
            $markup
        ) ?? $markup;
    }
};
