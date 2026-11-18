<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Give a group an admin name beside its name and start each locale's name from it. The name column stays,
     * holding the admin name, for code written against it.
     */
    public function up(): void
    {
        Schema::table('attribute_groups', function (Blueprint $table) {
            $table->string('admin_name')->nullable()->after('name');
        });

        DB::table('attribute_groups')->update(['admin_name' => DB::raw('name')]);

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
     * Drop the per-locale names and the admin name, leaving the name column as it was.
     */
    public function down(): void
    {
        Schema::dropIfExists('attribute_group_translations');

        Schema::table('attribute_groups', function (Blueprint $table) {
            $table->dropColumn('admin_name');
        });
    }

    /**
     * Give every existing group its name in each of the store's locales.
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
                        'name' => $group->name,
                    ];
                }
            }

            if ($rows) {
                DB::table('attribute_group_translations')->insert($rows);
            }
        });
    }
};
