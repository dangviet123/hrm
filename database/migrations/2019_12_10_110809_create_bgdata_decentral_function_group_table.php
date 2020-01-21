<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBgdataDecentralFunctionGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bgdata_decentral_function_group', function (Blueprint $table) {
            $table->integer("idGroup")->index();
            $table->integer("idMenu")->index();
            $table->string("Slug",150);
            $table->string("idPermission",300);
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
        Schema::dropIfExists('bgdata_decentral_function_group');
    }
}
