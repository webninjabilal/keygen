<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachineUserCodesTable extends Migration
{
    protected $table = 'machine_user_codes';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable($this->table)) {
            Schema::create($this->table, function (Blueprint $table) {
                $table->increments('id');
                $table->integer('machine_user_id')->unsigned();
                $table->foreign('machine_user_id')->references('id')->on('machine_users')->onDelete('cascade');
                $table->string('code')->nullable();
                $table->string('serial_number')->nullable();
                $table->integer('uses')->default(1);
                $table->date('used_date')->nullable();
                $table->tinyInteger('status')->default(0);
                $table->integer('created_by')->unsigned();
                $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
                $table->timestamps();
                $table->softDeletes();
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
        Schema::dropIfExists($this->table);
    }
}
