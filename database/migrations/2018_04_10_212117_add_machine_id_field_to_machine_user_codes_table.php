<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMachineIdFieldToMachineUserCodesTable extends Migration
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
                $table->integer('machine_id')->default(0);
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
                $table->dropColumn('machine_id');
            });
        }
    }
}
