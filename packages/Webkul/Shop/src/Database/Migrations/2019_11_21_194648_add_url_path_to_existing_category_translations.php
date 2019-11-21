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
        $sql = <<< SQL
            SELECT
                GROUP_CONCAT(parent_translations.slug SEPARATOR '/') AS url_path
            FROM
                categories AS node,
                categories AS parent
                JOIN category_translations AS parent_translations ON parent.id = parent_translations.category_id
            WHERE
                node._lft >= parent._lft
                AND node._rgt <= parent._rgt
                AND node.id = :category_id
                AND node.id <> 1
                AND parent.id <> 1
            GROUP BY
                node.id
SQL;

        $categoryTranslationsTableName = app(CategoryTranslation::class)->getTable();

        foreach (DB::table($categoryTranslationsTableName)->get() as $categoryTranslation) {
            $urlPathQueryResult = DB::selectOne($sql, ['category_id' => $categoryTranslation->category_id]);
            $url_path = $urlPathQueryResult ? $urlPathQueryResult->url_path : '';

            DB::table($categoryTranslationsTableName)
                ->where('id', $categoryTranslation->id)
                ->update(['url_path' => $url_path]);
        }
    }
}
