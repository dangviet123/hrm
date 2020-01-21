<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BgdataListpermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bgdata_listpermission', function (Blueprint $table) {
            $table->integer('idList')->autoIncrement();
            $table->string("ListName",225);
            $table->string("Icon",100)->nullable();
            $table->integer("idUserAdd")->default(0);
            $table->integer("idUserUpdate")->default(0);
            $table->mediumText("Description")->nullable();
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
        Schema::dropIfExists('bgdata_listpermission');
    }
}
