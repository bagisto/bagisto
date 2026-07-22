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
        Schema::create('payglocal_transactions', function (Blueprint $table) {

            $table->increments('id');

            $table->integer('cart_id')->unsigned()->index();
            $table->integer('order_id')->unsigned()->nullable();

            $table->string('merchant_txn_id')->unique();
            $table->string('gid')->nullable()->index();

            $table->text('status_url')->nullable();

            $table->decimal('amount', 12, 4)->nullable();
            $table->string('currency', 3)->nullable();
            $table->string('status', 30)->default('pending');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payglocal_transactions');
    }
};
