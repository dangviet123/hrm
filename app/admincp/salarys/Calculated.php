<?php

namespace App\admincp\salarys;

use Illuminate\Database\Eloquent\Model;

class Calculated extends Model
{
    protected $table = 'salarys_calculated';
    protected $primaryKey = 'idCalculated';
}
