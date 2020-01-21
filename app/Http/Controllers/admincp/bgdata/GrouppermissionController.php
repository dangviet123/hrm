<?php

namespace App\Http\Controllers\admincp\bgdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\admincp\bgdata\Grouppermission;
use Illuminate\Support\Facades\DB;
use App\functions\Helpers;
use Illuminate\Validation\Validator;

class GrouppermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $helpers = new Helpers();
        if ($request->has("limit")) {
            session(['limit' => $request["limit"]]);
        }else if (!session()->has('limit')) {
            session(['limit' => 25]);
        }
        $limit = session()->get('limit');
        $orderby = $request->has("orderby") ? $request["orderby"] : "DESC";
        $filed   = $request->has("filed") ? $request["filed"] : "created_at";
        $datas = DB::table('bgdata_group_permision AS bg_g')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->select("bg_g.*","su.FullName",DB::raw('false AS checked'))
        ->orderBy($filed,$orderby);
        // tìm kiếm
        $request->has("GroupName") ? $datas = $datas->where("bg_g.GroupName", "like", "%".$request["GroupName"]."%") : "";
        $request->has("FullName") ? $datas = $datas->where("su.FullName", "like", "%".$request["FullName"]."%") : "";
        $request->has("Description") ? $datas = $datas->where("bg_g.Description", "like", "%".$request["Description"]."%") : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_g.Active", explode(',',$request["Active"])) : "";
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        // end tìm kiếm
        
        $datas = $datas->paginate($limit);
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"grouppermission");
        return response([
            "Status" => true,
            "datas" => $datas,
            "orderby" => $orderby,
            "filed" => $filed,
            'limit' => $limit,
            "Permission" => $Permission["idPermission"],
            "DataInfoPermission" => $Permission["DataInfoPermission"]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $helpers = new Helpers();
        $OrderBy = $helpers->idOrderByKey("idGroup","bgdata_group_permision");
        $IDCode = "G".$helpers->OrderByIDCode( 'IDCode', "bgdata_group_permision", 5);
        return response([
            "Status" => true,
            "OrderBy" => $OrderBy,
            "IDCode" => $IDCode
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $helpers = new Helpers();
        $IDCode = "G".$helpers->OrderByIDCode( 'IDCode', "bgdata_group_permision", 5);
        $idGroup = $helpers->idOrderByKey("idGroup","bgdata_group_permision");
        $listGroup = new Grouppermission;
        $listGroup->idGroup     = $idGroup;
        $listGroup->IDCode      = $IDCode;
        $listGroup->GroupName   = $request["listGroup"]["GroupName"];
        $listGroup->Description = $request["listGroup"]["Description"];
        $listGroup->idUserAdd   = session()->get('session_user')->idUser;
        $listGroup->OrderBy     = $request["listGroup"]["OrderBy"];
        $listGroup->Active      = $request["listGroup"]["Active"];
        if ($listGroup->save()) {
            return response(["Status" => true,"idGroup" => $idGroup], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $datas = Grouppermission::find($id);
        return response([
            "Status" => true,
            "datas" => $datas
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $idGroup                 = $request["listGroup"]["idGroup"];
        $listGroup               = Grouppermission::find($idGroup);
        $listGroup->GroupName    = $request["listGroup"]["GroupName"];
        $listGroup->Description  = $request["listGroup"]["Description"];
        $listGroup->idUserUpdate = session()->get('session_user')->idUser;
        $listGroup->OrderBy      = $request["listGroup"]["OrderBy"];
        $listGroup->Active       = $request["listGroup"]["Active"];
        $listGroup->save();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $data_active = $request->data_active;
        DB::table('bgdata_decentral_function_group')->whereIn('idGroup',$data_active)->delete();
        DB::table('bgdata_group_permision')->whereIn('idGroup',$data_active)->delete();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }

    public function activeStatusOne(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $idGroup = $request->idGroup;
        $Active = ($request->Active == 1) ? 0 : 1;
        $listper = Grouppermission::find($idGroup);
        $listper->Active = $Active;
        $listper->save();
        if ($listper->save()) {
            return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
        }
    }
    public function activeStatusAll(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $data_active = $request->data_active;
        $Active = $request->Active;
        $listper = Grouppermission::whereIn('idGroup', $data_active)
        ->update(['Active' => $Active]);
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function sortBy(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        foreach ($request->idGroup as $key => $idGroup) {
            DB::table('bgdata_group_permision')->where('idGroup', $idGroup)
            ->update(['OrderBy' => $request->orderby[$key]]);
        }
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function Getmenu($idTypeMenu,$idGroup)
    {
        
        $ListMenu = DB::table('bgdata_menus')
        ->select('idMenu','idTypeMenu','id_parent','MenuName','Description','Icon','Active')
        ->orderBy('OrderBy','ASC')->where('idTypeMenu',$idTypeMenu)->get();
        $ref   = [];
        $items = [];
        foreach ($ListMenu as $Menu) {
            $Permission = $this->inPermission($Menu->idMenu,$idGroup);
            $thisRef                = &$ref[$Menu->idMenu];
            $thisRef['id']          = $Menu->idMenu;
            $thisRef['idMenu']      = $Menu->idMenu;
            $thisRef['idTypeMenu']  = $Menu->idTypeMenu;
            $thisRef['id_parent']   = $Menu->id_parent;
            $thisRef['MenuName']    = $Menu->MenuName;
            $thisRef['Description'] = $Menu->Description;
            $thisRef['Icon']        = $Menu->Icon;
            $thisRef['Active']      = $Permission["Active"];
            $thisRef['idGroup']      = $idGroup;
            $thisRef['children']    = [];
            $thisRef["Permission"]  = $Permission["DataMenu"];
            if($Menu->id_parent == 0) {
                $items[] = &$thisRef;
           } else {
                $ref[$Menu->id_parent]['children'][] = &$thisRef;
           }
        }
        return $items;
    }
    public function inPermission($idMenu,$idGroup)
    {
        $data = [];
        $DataPermission = DB::table('bgdata_decentral_function_group')->where('idMenu',$idMenu)->where('idGroup',$idGroup)->first();
        $Active = !empty($DataPermission) ? $DataPermission->Active : 0;
        $idPermission = !empty($DataPermission) ? explode(',',$DataPermission->idPermission) : [];
        $DataMenu = DB::table('bgdata_listpermission')->select('idList','ListName','Icon')
        ->whereIn('idList',$idPermission)
        ->orderBy('OrderBy','ASC')
        ->get();
        $data = ['DataMenu' => $DataMenu,'Active' => $Active];
        return $data;
        
    }
    public function permission($idGroup)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $helpers = new Helpers();
        $Group = DB::table('bgdata_group_permision')->select('GroupName')->where('idGroup',$idGroup)->first();
        $ListMenu = $this->Getmenu(1,$idGroup);
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"grouppermission");
        return response(["Status" => true,"datas" => $ListMenu,'Permission' => $Permission["idPermission"],'GroupName'=> $Group->GroupName], 200);
    }

    public function loadPermission($idMenu,$idGroup)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $listPermission = DB::table('bgdata_decentral_function')->select('idPermission')->where('idMenu',$idMenu)->first();
        
        $listPermission = !empty($listPermission) ? explode(',',$listPermission->idPermission) : [];
        $DataPermission = DB::table('bgdata_decentral_function_group')->where('idMenu',$idMenu)->where('idGroup',$idGroup)->first();
        $DataPermission = !empty($DataPermission) ? explode(',',$DataPermission->idPermission) : [];
        $DataMenu = DB::table('bgdata_listpermission')->select('idList','ListName','Icon',DB::raw('false AS checked'))
        ->whereIn('idList',$listPermission)
        ->orderBy('OrderBy','ASC')
        ->get();
        foreach ($DataMenu as $key => $listPm) {
            if (array_search($listPm->idList,$DataPermission) !== FALSE) {
                $DataMenu[$key]->checked = true;
            }
        }
        return response(["Status" => true,"dataPermission" => $DataMenu], 200);
    }
    public function savePermission(Request $request, $idMenu,$idGroup)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Permission = $request["Permission"];
        DB::table('bgdata_decentral_function_group')->where('idMenu',$idMenu)->where('idGroup',$idGroup)->delete();
        $Menu = DB::table('bgdata_menus')->select('Slug')->where('idMenu',$idMenu)->first();
        $Slug = $Menu->Slug;
        if (count($Permission) > 0) {
            $Permission = implode(",",$Permission);
            DB::table('bgdata_decentral_function_group')->insert([
                'idMenu'       => $idMenu,
                'idGroup'      => $idGroup,
                'idPermission' => $Permission,
                'Slug'         => $Slug,
                'Active'       => 1
            ]);
        }
        return response(["Status" => true,"Massages" => "Chỉnh sửa thành công"], 200);
    }
    public function activePermission(Request $request, $idMenu,$idGroup)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Active = $request["Active"];
        DB::table('bgdata_decentral_function_group')
        ->where('idMenu',$idMenu)->where('idGroup',$idGroup)
        ->update(['Active' => $Active]);
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function exportExcel(Request $request) // xuất excel
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $helpers = new Helpers();

        $orderby = $request->has("orderby") ? $request["orderby"] : "DESC";
        $filed   = $request->has("filed") ? $request["filed"] : "created_at";
        $datas = DB::table('bgdata_group_permision AS bg_g')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->select("bg_g.GroupName","bg_g.IDCode","bg_g.created_at","bg_g.Active","bg_g.Description","su.FullName","su.IDCode AS FullNameIDCode")
        ->orderBy($filed,$orderby);
        // tìm kiếm
        $request->has("GroupName") ? $datas = $datas->where("bg_g.GroupName", "like", "%".$request["GroupName"]."%") : "";
        $request->has("FullName") ? $datas = $datas->where("su.FullName", "like", "%".$request["FullName"]."%") : "";
        $request->has("Description") ? $datas = $datas->where("bg_g.Description", "like", "%".$request["Description"]."%") : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_g.Active", explode(',',$request["Active"])) : "";
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        $datas = $datas->get();
        return response(["Status" => true,"datas" => $datas], 200);
    }
}
