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
        Schema::table('product_flat', function (Blueprint $table) {
            $table->integer('quantity')->nullable()->after('weight');
            $table->integer('images_count')->default(0)->after('quantity');
            $table->boolean('manage_stock')->nullable()->after('images_count');
            $table->string('base_image')->nullable()->after('manage_stock');
            $table->text('category_name')->nullable()->after('base_image');
            $table->string('attribute_family_name')->nullable()->after('category_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_flat', function (Blueprint $table) {
            $table->dropColumn([
                'quantity',
                'images_count',
                'manage_stock',
                'base_image',
                'category_name',
                'attribute_family_name',
            ]);
        });
    }
};
