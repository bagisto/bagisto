<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTriggerOnCategories extends Migration
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

        $insertTrigger = <<< SQL
            CREATE TRIGGER %s 
            AFTER INSERT ON categories 
            FOR EACH ROW
            BEGIN
                $triggerBody
            END;
SQL;

        $updateTrigger = <<< SQL
            CREATE TRIGGER %s 
            AFTER UPDATE ON categories 
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
    private function getTriggerBody(): string
    {
        return <<< SQL
            DECLARE urlPath VARCHAR(255);
            DECLARE localeCode VARCHAR(255);
            DECLARE done INT;
            DECLARE curs CURSOR FOR (SELECT category_translations.locale
                    FROM category_translations 
                    WHERE category_id = NEW.id);
            DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

            
            IF EXISTS (
                SELECT *
                FROM category_translations 
                WHERE category_id = NEW.id
            )
            THEN
            
                OPEN curs;
            
            	SET done = 0;
                REPEAT 
                	FETCH curs INTO localeCode;
                    
                    SELECT get_url_path_of_category(NEW.id, localeCode) INTO urlPath;
                    
                    IF NEW.parent_id IS NULL 
                    THEN
                        SET urlPath = '';
                    END IF;
                    
                    UPDATE category_translations 
                    SET url_path = urlPath 
                    WHERE 
                        category_translations.category_id = NEW.id
                        AND category_translations.locale = localeCode;
                
                UNTIL done END REPEAT;
                
                CLOSE curs;
                
            END IF;
SQL;
    }
}
