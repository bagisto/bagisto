<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsletterQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('newsletter_queues', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->string('sender_name');
            $table->string('sender_email');
            $table->text('content');
            $table->timestamp('queue_datetime');
            $table->boolean('is_delivered')->default(0);
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
        Schema::dropIfExists('newsletter_queues');
    }
}
