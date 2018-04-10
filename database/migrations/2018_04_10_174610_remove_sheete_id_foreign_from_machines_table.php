<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveSheeteIdForeignFromMachinesTable extends Migration
{
    protected $table = 'machines';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable($this->table)) {
            Schema::table($this->table, function (Blueprint $table) {
                $table->dropForeign('machines_sheet_id_foreign');
                DB::statement("ALTER TABLE `machines` CHANGE `sheet_id` `sheet_id` INT(10) UNSIGNED NOT NULL DEFAULT '0';");
                DB::statement("ALTER TABLE `machines` CHANGE `sheet_id` `sheet_id` INT(10) NOT NULL DEFAULT '0';");
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable($this->table)) {
            Schema::table($this->table, function (Blueprint $table) {

            });
        }
    }
}
