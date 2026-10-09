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
     * every sequence owned by a column in the public schema is synced as a fallback.
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
     * Sync the sequence behind the id column of each of the given tables.
     *
     * Table names are automatically prefixed with the configured database table prefix.
     */
    private function syncSpecificTables(array $tables): void
    {
        $prefix = DB::getTablePrefix();

        foreach ($tables as $table) {
            $this->advanceSequence($prefix.$table, 'id');
        }
    }

    /**
     * Sync every sequence owned by a table column in the public schema.
     *
     * Ownership comes from the catalogue, so a sequence resolves to exactly the column
     * that feeds it rather than to any column whose default merely mentions its name.
     */
    private function syncAllTables(): void
    {
        $columns = DB::select("
            SELECT owner_table.relname AS table_name, owner_column.attname AS column_name
            FROM pg_class AS sequence_class
            JOIN pg_depend AS ownership
                ON ownership.objid = sequence_class.oid
                AND ownership.classid = 'pg_class'::regclass
                AND ownership.deptype IN ('a', 'i')
            JOIN pg_class AS owner_table
                ON owner_table.oid = ownership.refobjid
            JOIN pg_attribute AS owner_column
                ON owner_column.attrelid = owner_table.oid
                AND owner_column.attnum = ownership.refobjsubid
            JOIN pg_namespace AS sequence_schema
                ON sequence_schema.oid = sequence_class.relnamespace
            WHERE sequence_class.relkind = 'S'
                AND owner_table.relkind = 'r'
                AND sequence_schema.nspname = 'public'
        ");

        foreach ($columns as $column) {
            $this->advanceSequence($column->table_name, $column->column_name);
        }
    }

    /**
     * Point a table's sequence at the value after its largest id, never at a lower one.
     * pg_sequences cannot report that position: its last_value is null while is_called is false.
     */
    private function advanceSequence(string $table, string $column): void
    {
        $sequence = DB::selectOne('SELECT pg_get_serial_sequence(?, ?) AS name', [$table, $column]);

        if (! $sequence?->name) {
            return;
        }

        DB::statement("
            SELECT setval(
                '{$sequence->name}',
                GREATEST(
                    COALESCE((SELECT MAX(\"{$column}\") FROM \"{$table}\"), 0) + 1,
                    (
                        SELECT CASE WHEN is_called THEN last_value + 1 ELSE last_value END
                        FROM {$sequence->name}
                    )
                ),
                false
            )
        ");
    }
}
