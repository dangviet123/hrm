<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffPromoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_promote', function (Blueprint $table) {
            $table->integer('idPromote')->autoIncrement();
            $table->string("IDCode",50)->unique();
            $table->integer("idStaff")->index(); // nhân viên thăng cấp
            $table->string("Promote",450); // tiêu đề
            $table->mediumText("Notes")->nullable(); // ghi chú
            $table->integer("idCompany")->default(0)->index();
            $table->integer("idDepartment")->default(0)->index();
            $table->integer("idPosition")->default(0)->index();
            $table->integer("idPositionOl")->default(0)->index();
            $table->date("PromoteDay")->nullable();
            $table->integer("idUserAdd")->default(0)->index(); // người lập
            $table->integer("idUserUpdate")->default(0)->index(); // người cập nhật
            $table->dateTime('created_at')->nullable(); // ngày tạo
            $table->dateTime('updated_at')->nullable(); // ngày cập nhật
            $table->tinyInteger("Active")->default(0); // tình trạng
            $table->integer("idUserCandel")->default(0); // Người hủy
            $table->dateTime("DateCandel")->nullable(); // ngày hủy
            $table->tinyInteger("ManageCheck")->default(0); // quản lý duyệt
            $table->integer("idManageCheck")->default(0); // người duyệt
            $table->dateTime("DateManageCheck")->nullable(); // ngày duyệt
            $table->tinyInteger("Locked")->default(0); // tình trạng look
            $table->dateTime("DateLocked")->nullable(); // ngày duyệt
            $table->integer("idUserLocked")->default(0); // Người khóa cuối
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
        Schema::dropIfExists('staff_promote');
    }
}
