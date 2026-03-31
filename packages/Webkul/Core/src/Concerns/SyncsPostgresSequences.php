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
     * Pass specific table names for better performance. When no tables are provided,
     * all sequences in the public schema are synced as a fallback.
     *
     * On MySQL this is a no-op since AUTO_INCREMENT adjusts automatically.
     *
     * @param  array<string>  $tables  Table names to sync. Empty array syncs all tables.
     */
    protected function syncPostgresSequences(array $tables = []): void
    {
        if (! SupportedDatabaseEnum::isPostgres()) {
            return;
        }

        if (! empty($tables)) {
            $this->syncSpecificTables($tables);

            return;
        }

        $this->syncAllTables();
    }

    /**
     * Sync sequences for specific tables using pg_get_serial_sequence.
     *
     * This is the preferred path — one lookup per table instead of scanning
     * all sequences in the schema. Table names are automatically prefixed
     * with the configured database table prefix.
     */
    private function syncSpecificTables(array $tables): void
    {
        $prefix = DB::getTablePrefix();

        foreach ($tables as $table) {
            $prefixedTable = $prefix.$table;

            $sequence = DB::selectOne(
                "SELECT pg_get_serial_sequence(?, 'id') AS seq",
                [$prefixedTable]
            );

            if ($sequence?->seq) {
                DB::statement(
                    "SELECT setval(?, COALESCE((SELECT MAX(id) FROM \"{$prefixedTable}\"), 0) + 1, false)",
                    [$sequence->seq]
                );
            }
        }
    }

    /**
     * Sync all sequences in the public schema by scanning pg_sequences.
     *
     * This is the fallback when no specific tables are provided.
     */
    private function syncAllTables(): void
    {
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
