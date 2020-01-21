<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBgdataDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bgdata_district', function (Blueprint $table) {
            $table->integer('idDistrict')->autoIncrement();
            $table->string("IDCode",50)->unique();
            $table->string("District",225);
            $table->integer("idProvinces")->default(0)->index();
            $table->mediumText("Notes")->nullable();
            $table->integer("idUserAdd")->default(0)->index();
            $table->integer("idUserUpdate")->default(0)->index();
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
        Schema::dropIfExists('bgdata_district');
    }
}
