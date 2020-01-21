<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffAchievementsFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_achievements_file', function (Blueprint $table) {
            $table->integer('idAchievementsFile')->autoIncrement();
            $table->integer('idAchievements')->default(0)->index();
            $table->string('FileNameBase64',225)->nullable();
            $table->mediumText("Description")->nullable();
            $table->string('Type',20)->nullable();
            $table->double("Size")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_achievements_file');
    }
}
