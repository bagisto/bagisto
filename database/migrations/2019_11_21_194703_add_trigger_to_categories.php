<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class AddTriggerToCategories extends Migration
{
    private const TRIGGER_NAME_INSERT = 'trig_categories_insert';
    private const TRIGGER_NAME_UPDATE = 'trig_categories_update';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $triggerBody = $this->getTriggerBody();
        $dbPrefix = DB::getTablePrefix();

        $insertTrigger = <<< SQL
            CREATE TRIGGER %s
            AFTER INSERT ON ${dbPrefix}categories
            FOR EACH ROW
            BEGIN
                $triggerBody
            END;
SQL;

        $updateTrigger = <<< SQL
            CREATE TRIGGER %s
            AFTER UPDATE ON ${dbPrefix}categories
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
    private function getTriggerBody(): string
    {
        $dbPrefix = DB::getTablePrefix();

        return <<< SQL
            DECLARE urlPath VARCHAR(255);
            DECLARE localeCode VARCHAR(255);
            DECLARE done INT;
            DECLARE curs CURSOR FOR (SELECT ${dbPrefix}category_translations.locale
                    FROM ${dbPrefix}category_translations
                    WHERE category_id = NEW.id);
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;


            IF EXISTS (
                SELECT *
                FROM ${dbPrefix}category_translations
                WHERE category_id = NEW.id
            )
            THEN

                OPEN curs;

            	SET done = 0;
                REPEAT
                	FETCH curs INTO localeCode;

                    SELECT get_url_path_of_category(NEW.id, localeCode) INTO urlPath;

                    UPDATE ${dbPrefix}category_translations
                    SET url_path = urlPath
                    WHERE ${dbPrefix}category_translations.category_id = NEW.id;

                UNTIL done END REPEAT;

                CLOSE curs;

            END IF;
SQL;
    }
}
