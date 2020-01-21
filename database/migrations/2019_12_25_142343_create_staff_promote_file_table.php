<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffPromoteFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_promote_file', function (Blueprint $table) {
            $table->integer('idPromoteFile')->autoIncrement();
            $table->integer('idPromote')->default(0)->index();
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
        Schema::dropIfExists('staff_promote_file');
    }
}
