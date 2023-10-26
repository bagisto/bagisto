<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('timezone')->nullable();
            $table->string('theme')->nullable();
            $table->string('hostname')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->json('home_seo')->nullable();
            $table->boolean('is_maintenance_on')->default(0);
            $table->text('allowed_ips')->nullable();
            $table->integer('root_category_id')->nullable()->unsigned();
            $table->integer('default_locale_id')->unsigned();
            $table->integer('base_currency_id')->unsigned();
            $table->timestamps();

            $table->foreign('root_category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('default_locale_id')->references('id')->on('locales');
            $table->foreign('base_currency_id')->references('id')->on('currencies');
        });

        Schema::create('channel_locales', function (Blueprint $table) {
            $table->integer('channel_id')->unsigned();
            $table->integer('locale_id')->unsigned();

            $table->primary(['channel_id', 'locale_id']);
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->foreign('locale_id')->references('id')->on('locales')->onDelete('cascade');
        });

        Schema::create('channel_currencies', function (Blueprint $table) {
            $table->integer('channel_id')->unsigned();
            $table->integer('currency_id')->unsigned();

            $table->primary(['channel_id', 'currency_id']);
            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channel_currencies');

        Schema::dropIfExists('channel_locales');

        Schema::dropIfExists('channels');
    }
};
