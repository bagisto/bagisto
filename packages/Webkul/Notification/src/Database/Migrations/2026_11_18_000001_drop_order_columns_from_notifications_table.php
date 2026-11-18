<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop the order and read columns the 2.5 line kept on notifications, leaving the table a fresh install builds.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('notifications', 'order_id')) {
            return;
        }

        $hasOrderForeignKey = collect(Schema::getForeignKeys('notifications'))
            ->contains(fn ($foreignKey) => $foreignKey['columns'] === ['order_id']);

        Schema::table('notifications', function (Blueprint $table) use ($hasOrderForeignKey) {
            if ($hasOrderForeignKey) {
                $table->dropForeign(['order_id']);
            }
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(Schema::hasColumn('notifications', 'read') ? ['order_id', 'read'] : ['order_id']);
        });
    }

    /**
     * Restore nothing, since only a store upgraded through the 2.5 line had the columns and a fresh install never does.
     */
    public function down(): void {}
};
