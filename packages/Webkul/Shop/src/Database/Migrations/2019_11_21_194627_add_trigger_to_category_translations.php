<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Webkul\Category\Models\CategoryTranslation;

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
        $dbPrefix = DB::getTablePrefix();

        $triggerBody = $this->getTriggerBody();
        $insertTrigger = <<< SQL
            CREATE TRIGGER %s
            BEFORE INSERT ON ${dbPrefix}category_translations
            FOR EACH ROW
            BEGIN
                $triggerBody
            END;
SQL;

        $updateTrigger = <<< SQL
            CREATE TRIGGER %s
            BEFORE UPDATE ON ${dbPrefix}category_translations
            FOR EACH ROW
            BEGIN
                $triggerBody
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
     * Returns trigger body as string
     *
     * @return string
     */
    private function getTriggerBody()
    {
        $dbPrefix = DB::getTablePrefix();

        return <<<SQL
            DECLARE parentUrlPath varchar(255);
            DECLARE urlPath varchar(255);

            -- Category with id 1 is root by default
            IF NEW.category_id <> 1
            THEN

                SELECT
                    GROUP_CONCAT(parent_translations.slug SEPARATOR '/') INTO parentUrlPath
                FROM
                    ${dbPrefix}categories AS node,
                    ${dbPrefix}categories AS parent
                    JOIN ${dbPrefix}category_translations AS parent_translations ON parent.id = parent_translations.category_id
                WHERE
                    node._lft >= parent._lft
                    AND node._rgt <= parent._rgt
                    AND node.id = (SELECT parent_id FROM categories WHERE id = NEW.category_id)
                    AND parent.id <> 1
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
