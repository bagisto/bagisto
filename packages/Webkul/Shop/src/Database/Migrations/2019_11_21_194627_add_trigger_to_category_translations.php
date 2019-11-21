<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class AddTriggerToCategoryTranslations extends Migration
{
    private const TRIGGER_NAME_INSERT = 'trig_category_translations_insert';
    private const TRIGGER_NAME_UPDATE = 'trig_category_translations_update';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $insertTriggerBody = $this->getTriggerBody('insert');
        $insertTrigger = <<< SQL
            CREATE TRIGGER %s
            BEFORE INSERT ON category_translations
            FOR EACH ROW 
            BEGIN
                $insertTriggerBody
            END;
SQL;

        $updateTriggerBody = $this->getTriggerBody();
        $updateTrigger = <<< SQL
            CREATE TRIGGER %s
            BEFORE UPDATE ON category_translations
            FOR EACH ROW 
            BEGIN
                $updateTriggerBody
            END;
SQL;

        DB::unprepared(sprintf('DROP TRIGGER IF EXISTS %s;', self::TRIGGER_NAME_INSERT));
        DB::unprepared(sprintf($insertTrigger, self::TRIGGER_NAME_INSERT));

        DB::unprepared(sprintf('DROP TRIGGER IF EXISTS %s;', self::TRIGGER_NAME_UPDATE));
        DB::unprepared(sprintf($updateTrigger, self::TRIGGER_NAME_UPDATE));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared(sprintf('DROP TRIGGER IF EXISTS %s;', self::TRIGGER_NAME_INSERT));
        DB::unprepared(sprintf('DROP TRIGGER IF EXISTS %s;', self::TRIGGER_NAME_UPDATE));
    }

    /**
     * Returns trigger body as string based on type ('update' or 'insert').
     *
     * @param string $type
     *
     * @return string
     */
    private function getTriggerBody(string $type = 'update'): string
    {
        $addOnInsert = ($type === 'update') ? '' : <<< SQL
            ELSE
                SELECT CONCAT(urlPath, '/', NEW.slug) INTO urlPath;
SQL;

        return <<< SQL
            DECLARE urlPath varchar(255);

            IF NEW.category_id != 1
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
                    AND node.id = NEW.category_id
                    AND parent.id <> 1
                GROUP BY
                    node.id;
                    
                IF urlPath IS NULL 
                THEN
                    SET urlPath = NEW.slug;
                    $addOnInsert
                END IF;
                
                SET NEW.url_path = urlPath;
            END IF;
SQL;
    }
}
