<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVelocityMetaData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('velocity_meta_data', function (Blueprint $table) {
            $table->increments('id');
            $table->text('home_page_content');
            $table->text('footer_left_content');
            $table->text('footer_middle_content');
            $table->boolean('slider')->default(0);
            $table->json('advertisement')->nullable();
            $table->integer('sidebar_category_count')->default(9);
            $table->integer('featured_product_count')->default(4);
            $table->integer('new_products_count')->default(4);
            $table->text('subscription_bar_content')->nullable();
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
        Schema::dropIfExists('velocity_meta_data');
    }
}
