<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Rename a group's name to its admin name and start each locale's name from it.
     */
    public function up(): void
    {
        Schema::table('attribute_groups', function (Blueprint $table) {
            $table->renameColumn('name', 'admin_name');
        });

        Schema::create('attribute_group_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attribute_group_id')->unsigned();
            $table->string('locale');
            $table->text('name')->nullable();

            $table->unique(['attribute_group_id', 'locale'], 'attribute_group_translations_locale_unique');
            $table->foreign('attribute_group_id', 'attribute_group_translations_group_foreign')->references('id')->on('attribute_groups')->onDelete('cascade');
        });

        $this->translateExistingGroups();
    }

    /**
     * Drop the per-locale names and call the admin name the name again.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_group_translations');

        Schema::table('attribute_groups', function (Blueprint $table) {
            $table->renameColumn('admin_name', 'name');
        });
    }

    /**
     * Give every existing group its admin name in each of the store's locales.
     */
    protected function translateExistingGroups(): void
    {
        $locales = DB::table('locales')->pluck('code');

        DB::table('attribute_groups')->orderBy('id')->chunkById(500, function ($groups) use ($locales) {
            $rows = [];

            foreach ($groups as $group) {
                foreach ($locales as $locale) {
                    $rows[] = [
                        'attribute_group_id' => $group->id,
                        'locale' => $locale,
                        'name' => $group->admin_name,
                    ];
                }
            }

            if ($rows) {
                DB::table('attribute_group_translations')->insert($rows);
            }
        });
    }
};
