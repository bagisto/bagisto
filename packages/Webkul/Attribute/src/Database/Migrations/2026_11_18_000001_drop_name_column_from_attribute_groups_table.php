<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop the name column the 2.5 line kept beside the admin name, leaving the table a fresh install builds.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('attribute_groups', 'name')) {
            return;
        }

        DB::table('attribute_groups')
            ->whereNull('admin_name')
            ->update(['admin_name' => DB::raw('name')]);

        Schema::table('attribute_groups', function (Blueprint $table) {
            $table->string('admin_name')->nullable(false)->change();
        });

        if (! $this->uniqueIndexOn(['attribute_family_id', 'admin_name'])) {
            Schema::table('attribute_groups', function (Blueprint $table) {
                $table->unique(['attribute_family_id', 'admin_name']);
            });
        }

        if ($nameIndex = $this->uniqueIndexOn(['attribute_family_id', 'name'])) {
            Schema::table('attribute_groups', function (Blueprint $table) use ($nameIndex) {
                $table->dropUnique($nameIndex);
            });
        }

        Schema::table('attribute_groups', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        $this->nameIndexAsAFreshInstall();
    }

    /**
     * Restore nothing, since only a store upgraded through the 2.5 line had the column and a fresh install never does.
     */
    public function down(): void {}

    /**
     * The name of the unique index on exactly these columns, if there is one.
     */
    protected function uniqueIndexOn(array $columns): ?string
    {
        return collect(Schema::getIndexes('attribute_groups'))
            ->first(fn ($index) => $index['unique'] && $index['columns'] === $columns)['name'] ?? null;
    }

    /**
     * Give the admin name index the name a fresh install's renamed column leaves it, so later migrations find it.
     */
    protected function nameIndexAsAFreshInstall(): void
    {
        $freshName = DB::getTablePrefix().'attribute_groups_attribute_family_id_name_unique';

        $current = $this->uniqueIndexOn(['attribute_family_id', 'admin_name']);

        if (
            $current === $freshName
            || Schema::hasIndex('attribute_groups', $freshName)
        ) {
            return;
        }

        Schema::table('attribute_groups', function (Blueprint $table) use ($current, $freshName) {
            $table->renameIndex($current, $freshName);
        });
    }
};
