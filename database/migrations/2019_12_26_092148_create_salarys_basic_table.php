<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalarysBasicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salarys_basic', function (Blueprint $table) {
            $table->integer('idBacsic')->autoIncrement();
            $table->string("IDCode",50)->unique();
            $table->string("Bacsic",225); // tiêu đề
            $table->mediumText("Notes")->nullable(); // ghi chú
            $table->integer("idUserSalary")->default(0)->index(); // nhân viên
            $table->integer("idDepartment")->default(0)->index(); //
            $table->integer("idPosition")->default(0)->index(); // 
            $table->integer("idCompany")->default(0)->index(); // công ty
            $table->double("SalaryBacsic")->default(0); // lương căn bản
            $table->double("VAT")->default(0); // % tiền đóng bỏa hiểm xã hội
            $table->double("MoneyVAT")->default(0); // Phí đóng bỏa hiểm xã hội
            $table->double("MoneyKPI")->default(0); // tiền 1kpi
            $table->tinyInteger("isType")->default(0); // loại =0 áp dụng tất cả = 1 áp dụng cho từng nhân viên
            $table->date("DayActive")->nullable(); // Ngày hoạt động
            $table->integer("idUserAdd")->default(0)->index(); // người lập
            $table->integer("idUserUpdate")->default(0)->index(); // người cập nhật
            $table->dateTime('created_at')->nullable(); // ngày tạo
            $table->dateTime('updated_at')->nullable(); // ngày cập nhật
            $table->tinyInteger("Active")->default(0); // tình trạng
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salarys_basic');
    }
}
