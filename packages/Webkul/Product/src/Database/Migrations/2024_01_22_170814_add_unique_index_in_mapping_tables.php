<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->unique(['product_id', 'category_id']);
        });

        Schema::table('product_relations', function (Blueprint $table) {
            $table->unique(['parent_id', 'child_id']);
        });

        Schema::table('product_cross_sells', function (Blueprint $table) {
            $table->unique(['parent_id', 'child_id']);
        });

        Schema::table('product_up_sells', function (Blueprint $table) {
            $table->unique(['parent_id', 'child_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropUnique(['product_id', 'category_id']);
        });

        Schema::table('product_relations', function (Blueprint $table) {
            $table->dropUnique(['parent_id', 'child_id']);
        });

        Schema::table('product_cross_sells', function (Blueprint $table) {
            $table->dropUnique(['parent_id', 'child_id']);
        });

        Schema::table('product_up_sells', function (Blueprint $table) {
            $table->dropUnique(['parent_id', 'child_id']);
        });
    }
};
