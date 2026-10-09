<?php

use Illuminate\Support\Facades\DB;
use Webkul\Core\Concerns\SyncsPostgresSequences;
use Webkul\Core\Enums\SupportedDatabaseEnum;

/**
 * A caller of the trait, which exposes its protected entry point.
 */
function sequenceSyncer(): object
{
    return new class
    {
        use SyncsPostgresSequences;

        public function syncEverything(): void
        {
            $this->syncPostgresSequences();
        }

        public function syncOnly(array $tables): void
        {
            $this->syncPostgresSequences($tables);
        }
    };
}

/**
 * Every sequence owned by a table column, read from the catalogue.
 */
function ownedSequenceColumns(): array
{
    return DB::select("
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
}

/**
 * The id a table's sequence would hand out next.
 */
function nextSequenceValue(string $sequence): int
{
    return (int) DB::selectOne("
        SELECT CASE WHEN is_called THEN last_value + 1 ELSE last_value END AS value
        FROM {$sequence}
    ")->value;
}

/**
 * The qualified name of the sequence behind a table's id column.
 */
function sequenceBehind(string $table): string
{
    return DB::selectOne(
        'SELECT pg_get_serial_sequence(?, ?) AS name',
        [DB::getTablePrefix().$table, 'id']
    )->name;
}

/**
 * The tables whose sequence would hand out an id that is already taken.
 */
function tablesWithExhaustedSequence(): array
{
    $exhausted = [];

    foreach (ownedSequenceColumns() as $column) {
        $sequence = DB::selectOne(
            'SELECT pg_get_serial_sequence(?, ?) AS name',
            [$column->table_name, $column->column_name]
        );

        if (! $sequence?->name) {
            continue;
        }

        $maxId = (int) DB::selectOne("
            SELECT COALESCE(MAX(\"{$column->column_name}\"), 0) AS value
            FROM \"{$column->table_name}\"
        ")->value;

        if (nextSequenceValue($sequence->name) <= $maxId) {
            $exhausted[] = $column->table_name;
        }
    }

    return $exhausted;
}

// ============================================================================
// Syncing Every Sequence
// ============================================================================

it('should leave no sequence handing out an id that is already taken', function () {
    sequenceSyncer()->syncEverything();

    expect(tablesWithExhaustedSequence())->toBeEmpty();
})->skip(
    fn () => ! SupportedDatabaseEnum::isPostgres(),
    'Sequences are a PostgreSQL concern; AUTO_INCREMENT needs no sync.'
);

// ============================================================================
// Syncing Named Tables
// ============================================================================

it('should not move a sequence backwards when it is already ahead', function () {
    $sequence = sequenceBehind('categories');

    $original = nextSequenceValue($sequence);

    DB::statement("SELECT setval('{$sequence}', 100000, false)");

    sequenceSyncer()->syncOnly(['categories']);

    $next = nextSequenceValue($sequence);

    DB::statement("SELECT setval('{$sequence}', ?, false)", [$original]);

    expect($next)->toBe(100000);
})->skip(
    fn () => ! SupportedDatabaseEnum::isPostgres(),
    'Sequences are a PostgreSQL concern; AUTO_INCREMENT needs no sync.'
);
