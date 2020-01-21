<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_users', function (Blueprint $table) {
            $table->integer('idUser')->autoIncrement();
            $table->string("IDCode",50)->unique();
            $table->string('Email',225)->unique();
            $table->string('Password',100);
            $table->string('FullName',225);
            $table->integer('idCompany')->default(0)->index();
            $table->integer('idDepartment')->default(0)->index();
            $table->integer('idPosition')->default(0)->index();
            $table->integer('idLevel')->default(0)->index();
            $table->integer('idTypeOfWork')->default(0)->index(); // loại hình công việc
            $table->string('Phone',20)->nullable();
            $table->date('Birthday')->nullable();
            $table->integer('Birth')->default(0)->index();
            $table->string('IDcard',100)->nullable(); // cmnd
            $table->date('IDcardDate')->nullable(); // ngày cấp
            $table->integer('IDcardAddress')->default(0)->index(); // nơi cấp
            $table->string('Address',300)->nullable(); // thường khú
            $table->string('AddressStaying',300)->nullable(); // Tạm trú
            $table->mediumText("Qualification")->nullable(); // trình độ chuyên môn
            $table->mediumText("Description")->nullable();
            $table->mediumText("Notes")->nullable();
            $table->tinyInteger("Sex")->default(0); // giới tính
            $table->tinyInteger("Married")->default(0); // hôn nhân
            $table->string('Image',225)->nullable(); // Hình ảnh dại diện
            $table->string('HealthInsuranceNo',225)->nullable(); // Số bảo hiểm
            $table->string('BankName',225)->nullable(); // Số bảo hiểm
            $table->string('AccountNumber',225)->nullable(); // Số bảo hiểm
            $table->date("DayProbationaryFrom")->nullable();
            $table->date("DayProbationaryTo")->nullable();
            $table->date("DayWork")->nullable();
            $table->date("DayWorkOff")->nullable();
            $table->string('remember_token',225)->nullable();
            $table->integer("idUserAdd")->default(0)->index(); 
            $table->integer("idUserUpdate")->default(0)->index();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->tinyInteger('isWork')->default(0);
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
        Schema::dropIfExists('staff_users');
    }
}
