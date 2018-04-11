<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotificationEmailFieldToMachineUsersTable extends Migration
{
    protected $table = 'machine_users';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable($this->table)) {
            Schema::table($this->table, function (Blueprint $table) {
                $table->tinyInteger('notification_email')->default(0);
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
                $table->dropColumn('notification_email');
            });
        }
    }
}
