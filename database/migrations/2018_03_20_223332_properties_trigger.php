<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PropertiesTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER tr_properties_after AFTER INSERT ON `properties` FOR EACH ROW
            BEGIN
                INSERT INTO properties_buy_statuses (`properties_id`, `created_at`, `updated_at`) 
                VALUES (NEW.id, now(), null);
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
