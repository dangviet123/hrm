<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalarysCalculatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salarys_calculated', function (Blueprint $table) {
            $table->integer('idCalculated')->autoIncrement();
            $table->string("IDCode",50)->unique();
            $table->string("Calculated",225)->nullable();
            $table->json("SalaryDataA")->nullable();
            $table->json("SalaryDataB")->nullable();

            $table->double("TotalSalaryDataA")->default(0); // tổng cộng
            $table->double("TotalSalaryDataB")->default(0); // tổng trừ
            $table->double("TotalSalaryDataAVG")->default(0); // tiền nhận
            $table->string("TotalSalaryWords",350)->nullable(); // tiền bằng chữ
            

            $table->mediumText("Notes")->nullable(); // ghi chú
            $table->integer("idUserSalary")->default(0)->index(); // nhân viên
            $table->double("NumberWorkdays")->default(0); // số ngày công
            $table->string("Email",225)->nullable(); // Email
            $table->string("IDCodeUser",50)->nullable(); // mã nhận viên
            $table->date("MonthlySalary")->nullable();
            $table->integer("idDepartment")->default(0)->index(); // bộ phận
            $table->integer("idPosition")->default(0)->index(); // vị trí
            $table->integer("idCompany")->default(0)->index(); // công ty
            $table->integer("idUserAdd")->default(0)->index(); // người lập
            $table->integer("idUserUpdate")->default(0)->index(); // người cập nhật
            $table->dateTime('created_at')->nullable(); // ngày tạo
            $table->dateTime('updated_at')->nullable(); // ngày cập nhật
            $table->tinyInteger("isSendEmail")->default(0); // tình trạng email
            $table->tinyInteger("isEmailCheck")->default(0); // tình trạng thành công hay k
            $table->tinyInteger("Active")->default(0); // tình trạng
            $table->integer("idUserCandel")->default(0); // Người hủy
            $table->dateTime("DateCandel")->nullable(); // ngày hủy
            $table->tinyInteger("ManageCheck")->default(0); // quản lý duyệt
            $table->integer("idManageCheck")->default(0); // người duyệt
            $table->dateTime("DateManageCheck")->nullable(); // ngày duyệt
            $table->tinyInteger("Locked")->default(0); // tình trạng look
            $table->dateTime("DateLocked")->nullable(); // ngày duyệt
            $table->integer("idUserLocked")->default(0)->index(); // Người khóa cuối
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salarys_calculated');
    }
}
