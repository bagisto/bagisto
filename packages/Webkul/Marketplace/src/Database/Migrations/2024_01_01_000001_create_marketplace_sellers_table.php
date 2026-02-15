<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_sellers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('customer_id');
            $table->string('shop_title');
            $table->string('url')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->decimal('commission_percentage', 5, 2)->nullable();
            $table->boolean('is_approved')->default(false);
            $table->boolean('status')->default(true);
            $table->string('tax_vat_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postcode')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_sellers');
    }
};
