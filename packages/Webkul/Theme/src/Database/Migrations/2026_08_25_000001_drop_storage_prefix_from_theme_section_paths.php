<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The option keys that hold an authored document rather than a stored path.
     *
     * Static content records markup, and that markup carries `/storage/` urls of its
     * own. They are addresses inside a document, not the path of an upload, so they
     * are left exactly as the operator wrote them.
     */
    protected const DOCUMENT_KEYS = ['html', 'css'];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->rewrite(fn (string $value) => $this->withoutPrefix($value));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->rewrite(fn (string $value) => $this->withPrefix($value));
    }

    /**
     * Apply the callback to every stored path a section records, live and drafted.
     */
    protected function rewrite(callable $callback): void
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
            ->each(function ($row) use ($columns, $callback) {
                $changes = [];

                foreach ($columns as $column) {
                    $decoded = json_decode((string) $row->{$column}, true);

                    if (! is_array($decoded)) {
                        continue;
                    }

                    $rewritten = $this->walk($decoded, $callback);

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
     * Walk the option tree, applying the callback to each stored path.
     */
    protected function walk(array $options, callable $callback): array
    {
        foreach ($options as $key => $value) {
            if (in_array($key, self::DOCUMENT_KEYS, true)) {
                continue;
            }

            if (is_array($value)) {
                $options[$key] = $this->walk($value, $callback);

                continue;
            }

            if (is_string($value)) {
                $options[$key] = $callback($value);
            }
        }

        return $options;
    }

    /**
     * Drop the `storage/` prefix a section upload was recorded with.
     *
     * Only the directories sections have ever stored uploads in are rewritten. A
     * field holding a link the operator typed is left alone, even where it happens
     * to address something else under `storage/`.
     */
    protected function withoutPrefix(string $value): string
    {
        $trimmed = ltrim($value, '/');

        if (! preg_match('#^storage/(themes|theme|section)/#', $trimmed)) {
            return $value;
        }

        return substr($trimmed, strlen('storage/'));
    }

    /**
     * Put back the `storage/` prefix, for the paths this migration took it off.
     */
    protected function withPrefix(string $value): string
    {
        if (! preg_match('#^(themes|theme|section)/#', $value)) {
            return $value;
        }

        return 'storage/'.$value;
    }
};
