<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->rewrite(
            '#storage/(?:theme|section)/(\d+)/#',
            fn ($row) => 'storage/themes/'.$row->theme_code.'/sections/'.$row->section_id.'/'
        );

        $this->forget('theme');

        $this->forget('section');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->rewrite(
            '#storage/themes/[^/]+/sections/(\d+)/#',
            fn ($row) => 'storage/theme/'.$row->section_id.'/'
        );

        $this->forget('themes');
    }

    /**
     * Point every recorded upload at the directory the callback names, moving the files
     * that sit behind those paths along with them.
     */
    protected function rewrite(string $pattern, callable $target): void
    {
        if (
            ! Schema::hasTable('theme_section_translations')
            || ! Schema::hasTable('theme_sections')
        ) {
            return;
        }

        DB::table('theme_section_translations as translations')
            ->join('theme_sections', 'theme_sections.id', '=', 'translations.section_id')
            ->select('translations.id', 'translations.options', 'theme_sections.id as section_id', 'theme_sections.theme_code')
            ->orderBy('translations.id')
            ->each(function ($row) use ($pattern, $target) {
                $options = (string) $row->options;

                if (! preg_match($pattern, $options)) {
                    return;
                }

                $destination = $target($row);

                preg_match_all($pattern, $options, $matches, PREG_SET_ORDER);

                foreach ($matches as $match) {
                    $this->moveDirectory(
                        trim(substr($match[0], strlen('storage/')), '/'),
                        trim(substr($destination, strlen('storage/')), '/')
                    );
                }

                DB::table('theme_section_translations')
                    ->where('id', $row->id)
                    ->update(['options' => preg_replace($pattern, $destination, $options)]);
            });
    }

    /**
     * Move every file across, leaving anything already at the destination untouched so
     * that a re-run cannot clobber a newer upload.
     */
    protected function moveDirectory(string $from, string $to): void
    {
        if (
            $from === $to
            || ! Storage::exists($from)
        ) {
            return;
        }

        foreach (Storage::files($from) as $file) {
            $target = $to.'/'.basename($file);

            if (Storage::exists($target)) {
                continue;
            }

            Storage::move($file, $target);
        }

        Storage::deleteDirectory($from);
    }

    /**
     * Drop a directory once nothing points at it any more.
     */
    protected function forget(string $directory): void
    {
        if (
            Storage::exists($directory)
            && empty(Storage::allFiles($directory))
        ) {
            Storage::deleteDirectory($directory);
        }
    }
};
