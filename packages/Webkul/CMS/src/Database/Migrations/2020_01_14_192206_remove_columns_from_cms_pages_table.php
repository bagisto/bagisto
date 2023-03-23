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
        Schema::table('cms_pages', function (Blueprint $table) {
            $table->dropForeign('cms_pages_locale_id_foreign');
            $table->dropForeign('cms_pages_channel_id_foreign');
            $table->dropColumn(['url_key', 'html_content', 'page_title', 'meta_title', 'meta_description', 'meta_keywords', 'content', 'locale_id', 'channel_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cms_pages', function (Blueprint $table) {
            //
        });
    }
};
