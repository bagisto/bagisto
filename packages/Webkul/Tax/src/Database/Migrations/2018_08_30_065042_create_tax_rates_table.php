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
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->increments('id');

            $table->string('identifier')->unique();

            $table->boolean('is_zip')->default(0);

            $table->string('zip_code')->nullable();

            $table->string('zip_from')->nullable();

            $table->string('zip_to')->nullable();

            $table->string('state');

            $table->string('country');

            $table->decimal('tax_rate', 12, 4);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_rates');
    }
};
