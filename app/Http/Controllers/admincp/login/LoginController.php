<?php

namespace App\Http\Controllers\admincp\login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\functions\Helpers;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function checkLogin(Request $request)
    {
        $Email = $request["listUser"]["Email"];
        $Password = md5($request["listUser"]["Password"]);
        $data = DB::table('staff_users')
        ->where("Email",$Email)
        ->where("Password",$Password)
        ->where("Active",1)
        ->first();
        if (!empty($data)) {
            session(['session_user' => $data]);
            return response(["Status" => true], 200);
        }else{
            return response(["Status" => false], 200);
        }
    }
}
