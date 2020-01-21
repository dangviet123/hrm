<?php

namespace App\admincp\staffinformation;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'staff_users';
    protected $primaryKey = 'idUser';
}
