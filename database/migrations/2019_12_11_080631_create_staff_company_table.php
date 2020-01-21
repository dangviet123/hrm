<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_company', function (Blueprint $table) {
            $table->integer('idCompany')->autoIncrement();
            $table->string("IDCode",50)->unique();
            $table->string("CompanyName",225);
            $table->mediumText("Address")->nullable();
            $table->mediumText("Description")->nullable();
            $table->mediumText("Notes")->nullable();
            $table->string("Phone",20)->nullable();
            $table->string("TaxCode",100)->nullable();
            $table->string("Director",225)->nullable();
            $table->string("Manager",225)->nullable();
            $table->string("BankAccount",225)->nullable();
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
        Schema::dropIfExists('staff_company');
    }
}
