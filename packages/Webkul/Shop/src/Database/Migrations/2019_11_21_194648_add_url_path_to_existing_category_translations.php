<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Webkul\Category\Models\CategoryTranslation;

class AddUrlPathToExistingCategoryTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sqlStoredFunction = <<< SQL
            SELECT get_url_path_of_category(:category_id, :locale_code) AS url_path;
        SQL;


        $categoryTranslationsTableName = app(CategoryTranslation::class)->getTable();

        foreach (DB::table($categoryTranslationsTableName)->get() as $categoryTranslation) {
            $urlPathQueryResult = DB::selectOne($sqlStoredFunction, [
                'category_id' => $categoryTranslation->category_id,
                'locale_code' => $categoryTranslation->locale,
            ]);
            $url_path = $urlPathQueryResult ? $urlPathQueryResult->url_path : '';

            DB::table($categoryTranslationsTableName)
                ->where('id', $categoryTranslation->id)
                ->update(['url_path' => $url_path]);
        }
    }
}
