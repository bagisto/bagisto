<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Webkul\Core\Helpers\StoredFile;

return new class extends Migration
{
    /**
     * The stored columns each top-level directory rename applies to.
     */
    public const DIRECTORIES = [
        ['product/', 'products/', 'public', [
            ['product_images', 'path'],
            ['product_videos', 'path'],
            ['product_attribute_values', 'text_value'],
        ]],
        ['category/', 'categories/', 'public', [
            ['categories', 'logo_path'],
            ['categories', 'banner_path'],
        ]],
        ['channel/', 'channels/', 'public', [
            ['channels', 'logo'],
            ['channels', 'favicon'],
        ]],
        ['review/', 'reviews/', 'public', [
            ['product_review_attachments', 'path'],
        ]],
        ['attribute_option/', 'attribute-options/', 'public', [
            ['attribute_options', 'swatch_value'],
        ]],
        ['configuration/', 'configurations/', 'public', [
            ['core_config', 'value'],
        ]],
        ['rma/', 'rmas/', 'private', [
            ['rma_images', 'path'],
            ['rma_messages', 'attachment_path'],
        ]],
    ];

    /**
     * The directories left behind by the move, dropped only when nothing remains in them.
     */
    public const EMPTIED = [
        ['public', 'product'],
        ['public', 'category'],
        ['public', 'channel'],
        ['public', 'review'],
        ['public', 'attribute_option'],
        ['public', 'configuration'],
        ['public', 'product_downloadable_links'],
        ['public', 'rma-conversation'],
        ['private', 'product_downloadable_links'],
        ['private', 'rma-conversation'],
        ['private', 'rma'],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach (self::DIRECTORIES as [$from, $to, $disk, $columns]) {
            $this->renameDirectory($from, $to, $disk, $columns);
        }

        $this->groupDownloadables('product_downloadable_links', 'downloadable-links');

        $this->groupDownloadables('product_downloadable_samples', 'downloadable-samples');

        $this->groupConversationAttachments();

        $this->pruneEmptied();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->scatterConversationAttachments();

        $this->scatterDownloadables('product_downloadable_links');

        $this->scatterDownloadables('product_downloadable_samples');

        foreach (array_reverse(self::DIRECTORIES) as [$from, $to, $disk, $columns]) {
            $this->renameDirectory($to, $from, $disk, $columns);
        }
    }

    /**
     * Move every file stored under one top-level directory to another, column by column.
     */
    protected function renameDirectory(string $from, string $to, string $disk, array $columns): void
    {
        foreach ($columns as [$table, $column]) {
            $this->relocate(
                $table,
                $column,
                $disk,
                $from.'%',
                fn ($value) => $to.substr($value, strlen($from)),
            );
        }
    }

    /**
     * Move the downloadable files a product owns out of their shared directory and under the product.
     */
    protected function groupDownloadables(string $table, string $directory): void
    {
        $this->relocate(
            $table,
            'file',
            'private',
            'product_downloadable_links/%',
            fn ($value, $row) => 'products/'.$row->product_id.'/'.$directory.'/'.basename($value),
        );
    }

    /**
     * Put the downloadable files of a product back in the single directory they used to share.
     */
    protected function scatterDownloadables(string $table): void
    {
        $this->relocate(
            $table,
            'file',
            'private',
            'products/%/downloadable-%',
            fn ($value, $row) => 'product_downloadable_links/'.$row->product_id.'/'.basename($value),
        );
    }

    /**
     * Move the attachments of a conversation under the return they belong to.
     */
    protected function groupConversationAttachments(): void
    {
        $this->relocate(
            'rma_messages',
            'attachment_path',
            'private',
            'rma-conversation/%',
            fn ($value, $row) => 'rmas/'.$row->rma_id.'/conversations/'.$row->id.'/'.basename($value),
        );
    }

    /**
     * Put the attachments of a conversation back in their own top-level directory.
     */
    protected function scatterConversationAttachments(): void
    {
        $this->relocate(
            'rma_messages',
            'attachment_path',
            'private',
            'rmas/%/conversations/%',
            fn ($value, $row) => 'rma-conversation/'.$row->id.'/'.basename($value),
        );
    }

    /**
     * Drop the directories the move emptied, keeping any that still hold a file of their own.
     */
    protected function pruneEmptied(): void
    {
        foreach (self::EMPTIED as [$name, $directory]) {
            $disk = Storage::disk($name);

            if (
                ! $disk->exists($directory)
                || $disk->allFiles($directory)
            ) {
                continue;
            }

            $disk->deleteDirectory($directory);
        }
    }

    /**
     * Move every file a column points at to wherever the callback places it, then record the new path.
     */
    protected function relocate(string $table, string $column, string $disk, string $pattern, callable $target): void
    {
        if (
            ! Schema::hasTable($table)
            || ! Schema::hasColumn($table, $column)
        ) {
            return;
        }

        DB::table($table)
            ->where($column, 'like', $pattern)
            ->orderBy('id')
            ->chunkById(200, function ($rows) use ($table, $column, $disk, $target) {
                foreach ($rows as $row) {
                    $path = $target($row->{$column}, $row);

                    app(StoredFile::class)->move($row->{$column}, $path, $disk);

                    DB::table($table)->where('id', $row->id)->update([$column => $path]);
                }
            });
    }
};
