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
        Schema::table('visits', function (Blueprint $table) {
            $table->integer('channel_id')->unsigned()->nullable()->after('visitor_id');

            $table->foreign('channel_id')->references('id')->on('channels')->onDelete('cascade');
        });

        $firstChannelId = DB::table('channels')->value('id');

        if (! $firstChannelId) {
            return;
        }

        DB::table('visits')->update(['channel_id' => $firstChannelId]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropForeign(['channel_id']);
            $table->dropColumn('channel_id');
        });
    }
};
