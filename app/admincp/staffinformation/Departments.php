<?php

namespace App\admincp\staffinformation;

use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    protected $table = 'staff_departments';
    protected $primaryKey = 'idDepartment';
}
