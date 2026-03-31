<?php

namespace Webkul\Core\Concerns;

use Illuminate\Support\Facades\DB;
use Webkul\Core\Enums\SupportedDatabaseEnum;

trait SyncsPostgresSequences
{
    /**
     * Sync PostgreSQL auto-increment sequences after inserting rows with explicit IDs.
     *
     * PostgreSQL sequences do not advance when explicit IDs are provided in INSERT
     * statements. This causes duplicate key errors on subsequent auto-generated inserts.
     *
     * On MySQL this is a no-op since AUTO_INCREMENT adjusts automatically.
     */
    protected function syncPostgresSequences(): void
    {
        if (! SupportedDatabaseEnum::isPostgres()) {
            return;
        }

        $sequences = DB::select(
            "SELECT sequencename FROM pg_sequences WHERE schemaname = 'public'"
        );

        foreach ($sequences as $seq) {
            $tableInfo = DB::selectOne("
                SELECT table_name, column_name
                FROM information_schema.columns
                WHERE column_default LIKE '%' || ? || '%'
                AND table_schema = 'public'
                LIMIT 1
            ", [$seq->sequencename]);

            if ($tableInfo) {
                DB::statement(
                    "SELECT setval(?, COALESCE((SELECT MAX(\"{$tableInfo->column_name}\") FROM \"{$tableInfo->table_name}\"), 0) + 1, false)",
                    [$seq->sequencename]
                );
            }
        }
    }
}
