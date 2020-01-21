<?php

namespace App\Http\Controllers\admincp\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function getMenuLeft(Request $request)
    {
        $idGroup = DB::table('staff_users_group')->select(\DB::raw("GROUP_CONCAT(DISTINCT idGroup) AS idGroup"))->where("idUser",session()->get('session_user')->idUser)->get();
        $idGroup = $idGroup[0]->idGroup;
        
        $ListUser =  DB::table('bgdata_decentral_function_group')->select(\DB::raw("GROUP_CONCAT(DISTINCT idMenu) AS idMenu"))->whereIn("idGroup",explode(",",$idGroup))->get();

        $items = $this->Getmenu(1,$ListUser);
        $SSFullName = session()->get('session_user')->FullName;
        $SSImage = session()->get('session_user')->Image;
        return response(["Status" => true,"datas" => $items,"SSFullName" => $SSFullName,'SSImage' => $SSImage], 200);
        
    }

    public function Getmenu($idTypeMenu,$listMenuPer)
    {
        if (session()->get('session_user')->idUser == 1) {
            $ListMenu = DB::table('bgdata_menus')
            ->select('idMenu','idTypeMenu','id_parent','MenuName','Link','Slug','Target','Icon')
            ->orderBy('OrderBy','ASC')
            ->where('idTypeMenu',$idTypeMenu)
            ->where('Active',1)
            ->get();
        }else{
            $ListMenu = DB::table('bgdata_menus')
            ->select('idMenu','idTypeMenu','id_parent','MenuName','Link','Slug','Target','Icon')
            ->orderBy('OrderBy','ASC')
            ->where('idTypeMenu',$idTypeMenu)
            ->where('Active',1)->whereIn("idMenu",explode(",",$listMenuPer))
            ->get(); 
        }
        
        $ref   = [];
        $items = [];
        foreach ($ListMenu as $Menu) {
            $thisRef                = &$ref[$Menu->idMenu];
            $thisRef['idMenu']      = $Menu->idMenu;
            $thisRef['idTypeMenu']  = $Menu->idTypeMenu;
            $thisRef['id_parent']   = $Menu->id_parent;
            $thisRef['MenuName']    = $Menu->MenuName;
            $thisRef['Link']        = $Menu->Link;
            $thisRef['Slug']        = $Menu->Slug;
            $thisRef['Icon']        = $Menu->Icon;
            $thisRef['Display']     = 0;
            $thisRef['children']    = [];
            if($Menu->id_parent == 0) {
                $items[] = &$thisRef;
           } else {
                $ref[$Menu->id_parent]['children'][] = &$thisRef;
           }
        }
        return $items;
    }
    public function getHeader(Request $request)
    {
        return response(["Status" => true,"session_user" => session()->get('session_user')], 200);
    }
    public function checkLogout(Request $request)
    {
        \Session::flush();
    }
}
