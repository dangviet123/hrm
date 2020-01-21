<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBgdataTypemenusystemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bgdata_typemenusystem', function (Blueprint $table) {
            $table->integer('idTypeMenu')->autoIncrement();
            $table->string("TypeMenuName",225);
            $table->mediumText("Description")->nullable();
            $table->integer("idUserAdd")->default(0);
            $table->integer("idUserUpdate")->default(0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer("OrderBy")->default(0);
            $table->tinyInteger("Active")->default(0);
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bgdata_typemenusystem');
    }
}
