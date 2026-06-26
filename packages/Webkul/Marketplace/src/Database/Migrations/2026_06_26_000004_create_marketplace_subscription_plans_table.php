<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');                           // Básico, Pro, Premium
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->enum('interval', ['monthly', 'yearly'])->default('monthly');
            $table->integer('max_products')->default(10);     // -1 = unlimited
            $table->decimal('commission_rate', 5, 2)->default(10.00); // override per plan
            $table->boolean('featured_listing')->default(false);
            $table->boolean('analytics_access')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('stripe_price_id')->nullable();    // Stripe Price ID for recurring billing
            $table->timestamps();
        });

        Schema::create('marketplace_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained('marketplace_sellers')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('marketplace_subscription_plans');
            $table->enum('status', ['active', 'cancelled', 'expired', 'trialing'])->default('trialing');
            $table->string('stripe_subscription_id')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_subscriptions');
        Schema::dropIfExists('marketplace_subscription_plans');
    }
};
