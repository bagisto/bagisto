<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eu_withdrawals', function (Blueprint $table) {
            $table->dropForeign(['order_id']);

            $table->dropIndex(['order_id']);

            $table->unique('order_id');

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('eu_withdrawals', function (Blueprint $table) {
            $table->dropForeign(['order_id']);

            $table->dropUnique(['order_id']);

            $table->index('order_id');

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }
};
