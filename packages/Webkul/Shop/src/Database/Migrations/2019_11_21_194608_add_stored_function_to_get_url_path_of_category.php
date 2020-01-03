<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class AddStoredFunctionToGetUrlPathOfCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $functionSQL = <<< SQL
            DROP FUNCTION IF EXISTS `get_url_path_of_category`;
            CREATE FUNCTION get_url_path_of_category(
                categoryId INT,
                localeCode VARCHAR(255)
            )
            RETURNS VARCHAR(255)
            DETERMINISTIC
            BEGIN
            
                DECLARE urlPath VARCHAR(255);
                CREATE TEMPORARY TABLE IF NOT EXISTS root_categories AS (SELECT id FROM categories where parent_id IS NULL);

                IF categoryId NOT IN (SELECT id FROM root_categories)
                THEN
                    SELECT
                        GROUP_CONCAT(parent_translations.slug SEPARATOR '/') INTO urlPath
                    FROM
                        categories AS node,
                        categories AS parent
                        JOIN category_translations AS parent_translations ON parent.id = parent_translations.category_id
                    WHERE
                        node._lft >= parent._lft
                        AND node._rgt <= parent._rgt
                        AND node.id = categoryId
                        AND parent.id NOT IN (SELECT id FROM root_categories)
                        AND parent_translations.locale = localeCode
                    GROUP BY
                        node.id;
                        
                    IF urlPath IS NULL
                    THEN                    
                        SET urlPath = (
                                    SELECT slug 
                                    FROM category_translations 
                                    WHERE 
                                        category_translations.category_id = categoryId
                                        AND category_translations.locale = localeCode
                                    );
                    END IF;
                 ELSE
                    SET urlPath = '';
                 END IF;
                 
                 RETURN urlPath;
            END;
SQL;

        DB::unprepared($functionSQL);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS `get_url_path_of_category`;');
    }
}
