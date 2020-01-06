<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class AlterTriggerCategoryTranslations extends Migration
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
        $triggerBody = $this->getTriggerBody();
        $insertTrigger = <<< SQL
            CREATE TRIGGER %s
            BEFORE INSERT ON category_translations
            FOR EACH ROW 
            BEGIN
                $triggerBody
            END;
SQL;

        $updateTrigger = <<< SQL
            CREATE TRIGGER %s
            BEFORE UPDATE ON category_translations
            FOR EACH ROW 
            BEGIN
                $triggerBody
            END;
SQL;

        $this->dropTriggers();

        DB::unprepared(sprintf($insertTrigger, self::TRIGGER_NAME_INSERT));
        DB::unprepared(sprintf($updateTrigger, self::TRIGGER_NAME_UPDATE));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropTriggers();
    }

    /**
     * Drop the triggers
     */
    private function dropTriggers()
    {
        DB::unprepared(sprintf('DROP TRIGGER IF EXISTS %s;', self::TRIGGER_NAME_INSERT));
        DB::unprepared(sprintf('DROP TRIGGER IF EXISTS %s;', self::TRIGGER_NAME_UPDATE));
    }

    /**
     * Returns trigger body as string
     *
     * @return string
     */
    private function getTriggerBody()
    {
        return <<<SQL
            DECLARE parentUrlPath varchar(255);
            DECLARE urlPath varchar(255);
            
            IF NOT EXISTS (
                SELECT id
                FROM categories
                WHERE
                    id = NEW.category_id
                    AND parent_id IS NULL
            )
            THEN
                
                SELECT
                    GROUP_CONCAT(parent_translations.slug SEPARATOR '/') INTO parentUrlPath
                FROM
                    categories AS node,
                    categories AS parent
                    JOIN category_translations AS parent_translations ON parent.id = parent_translations.category_id
                WHERE
                    node._lft >= parent._lft
                    AND node._rgt <= parent._rgt
                    AND node.id = (SELECT parent_id FROM categories WHERE id = NEW.category_id)
                    AND node.parent_id IS NOT NULL 
                    AND parent.parent_id IS NOT NULL
                    AND parent_translations.locale = NEW.locale
                GROUP BY
                    node.id;
                
                IF parentUrlPath IS NULL 
                THEN
                    SET urlPath = NEW.slug;
                ELSE
                    SET urlPath = concat(parentUrlPath, '/', NEW.slug);
                END IF;
                
                SET NEW.url_path = urlPath;
                
            END IF;
SQL;

    }
}
