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
        $triggerBody = <<< SQL
            DECLARE urlPath VARCHAR(255);
            
            SELECT get_url_path_of_category(NEW.id) INTO urlPath;
            
            IF EXISTS (SELECT * FROM category_translations WHERE category_id = NEW.id)
            THEN
                UPDATE category_translations 
                SET url_path = urlPath 
                WHERE category_translations.category_id = NEW.id;
            END IF;
SQL;

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
}
