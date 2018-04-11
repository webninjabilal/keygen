<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBlockSerialNumberFieldToMachineUserCodesTable extends Migration
{
    protected $table = 'machine_user_codes';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(Schema::hasTable($this->table)) {
            Schema::table($this->table, function (Blueprint $table) {
                $table->tinyInteger('block_serial_number')->default(0);
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
                $table->dropColumn(['block_serial_number']);
            });
        }
    }
}
