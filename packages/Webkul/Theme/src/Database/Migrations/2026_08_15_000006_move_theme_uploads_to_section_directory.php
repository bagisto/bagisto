<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Section uploads move out of the theme directory
|--------------------------------------------------------------------------
|
| Moves section uploads from `theme/{id}` to `section/{id}`, rewriting the paths recorded
| in the translated options in step with the files.
|
*/
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->moveDirectory('theme', 'section');

        $this->rewritePaths('storage/theme/', 'storage/section/');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->moveDirectory('section', 'theme');

        $this->rewritePaths('storage/section/', 'storage/theme/');
    }

    /**
     * Move every child directory across, leaving anything already at the destination
     * untouched so that a re-run cannot clobber newer uploads.
     */
    protected function moveDirectory(string $from, string $to): void
    {
        if (! Storage::exists($from)) {
            return;
        }

        foreach (Storage::allFiles($from) as $file) {
            $target = $to.substr($file, strlen($from));

            if (Storage::exists($target)) {
                continue;
            }

            Storage::move($file, $target);
        }

        Storage::deleteDirectory($from);
    }

    /**
     * Rewrite the upload paths recorded in the translated section options.
     */
    protected function rewritePaths(string $from, string $to): void
    {
        if (! Schema::hasTable('section_translations')) {
            return;
        }

        DB::table('section_translations')
            ->where('options', 'like', '%'.$from.'%')
            ->orderBy('id')
            ->each(function ($row) use ($from, $to) {
                DB::table('section_translations')
                    ->where('id', $row->id)
                    ->update(['options' => str_replace($from, $to, $row->options)]);
            });
    }
};
