<?php

namespace App\Http\Controllers\admincp\bgdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\admincp\bgdata\Typemenusystem;
use Illuminate\Support\Facades\DB;
use App\functions\Helpers;
use Illuminate\Validation\Validator;

class TypemenusystemController extends Controller
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

        $datas = DB::table('bgdata_typemenusystem AS bg_type')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_type.idUserAdd')
        ->select("bg_type.*","su.FullName",DB::raw('false AS checked'))
        ->orderBy($filed,$orderby);

        // tìm kiếm
        $request->has("TypeMenuName") ? $datas = $datas->where("bg_type.TypeMenuName", "like", "%".$request["TypeMenuName"]."%") : "";
        $request->has("Description") ? $datas = $datas->where("bg_type.Description", "like", "%".$request["Description"]."%") : "";
        $request->has("FullName") ? $datas = $datas->where("su.FullName", "like", "%".$request["FullName"]."%") : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_type.Active", explode(',',$request["Active"])) : "";
        $request->has("date_from") ? $datas = $datas->whereDate('bg_type.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_type.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        // end tìm kiếm
        
        $datas = $datas->paginate($limit);
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"typemenusystem");

        
        
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
        $OrderBy = $helpers->idOrderByKey("idTypeMenu","bgdata_typemenusystem");
        return response([
            "Status" => true,
            "OrderBy" => $OrderBy
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
        $idTypeMenu = $helpers->idOrderByKey("idTypeMenu","bgdata_typemenusystem");
        $TypeMenu = new Typemenusystem;
        $TypeMenu->idTypeMenu = $idTypeMenu;
        $TypeMenu->TypeMenuName = $request["TypeMenu"]["TypeMenuName"];
        $TypeMenu->Description = $request["TypeMenu"]["Description"];
        $TypeMenu->idUserAdd = session()->get('session_user')->idUser;
        $TypeMenu->OrderBy = $request["TypeMenu"]["OrderBy"];
        $TypeMenu->Active = $request["TypeMenu"]["Active"];
        if ($TypeMenu->save()) {
            return response(["Status" => true,"idTypeMenu" => $idTypeMenu], 200);
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
        $datas = Typemenusystem::find($id);
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
        $idTypeMenu             = $request["TypeMenu"]["idTypeMenu"];
        $TypeMenu               = Typemenusystem::find($idTypeMenu);
        $TypeMenu->TypeMenuName = $request["TypeMenu"]["TypeMenuName"];
        $TypeMenu->Description  = $request["TypeMenu"]["Description"];
        $TypeMenu->idUserUpdate = session()->get('session_user')->idUser;
        $TypeMenu->OrderBy      = $request["TypeMenu"]["OrderBy"];
        $TypeMenu->Active       = $request["TypeMenu"]["Active"];
        $TypeMenu->save();
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
        DB::table('bgdata_typemenusystem')->whereIn('idTypeMenu',$data_active)->delete();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function activeStatusOne(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $idTypeMenu = $request->idTypeMenu;
        $Active = ($request->Active == 1) ? 0 : 1;
        $TypeMenu = Typemenusystem::find($idTypeMenu);
        $TypeMenu->Active = $Active;
        $TypeMenu->save();
        if ($TypeMenu->save()) {
            return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
        }
    }
    public function activeStatusAll(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $data_active = $request->data_active;
        $Active = $request->Active;
        $TypeMenu = Typemenusystem::whereIn('idTypeMenu', $data_active)
        ->update(['Active' => $Active]);
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);

    }
    public function sortBy(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        foreach ($request->idTypeMenu as $key => $idTypeMenu) {
            DB::table('bgdata_typemenusystem')->where('idTypeMenu', $idTypeMenu)
            ->update(['OrderBy' => $request->orderby[$key]]);
        }
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }

    public function checkSlug(Request $request){ // kiểm tra seo slug
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $idMenu = $request->has('idMenu') ? $request->idMenu : 0;
        $data = DB::table('bgdata_menus')->select('idMenu')
        ->where('idTypeMenu',$request->idTypeMenu);
        $idMenu > 0 ? $data = $data->where('idMenu','<>',$idMenu) : "";
        $data = $data->where('id_parent',($request->id_parent =='') ? 0 : $request->id_parent)
        ->where('Slug',$request->Slug)->first();
        if (!empty($data)) {
            return response(["Status" => false], 200);
        }else{
            return response(["Status" => true], 200);
        }
    }

    public function createdMenu(Request $request) // thêm mới menu
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $id_parent = ($request["listMenu"]["id_parent"] == "") ? 0 : $request["listMenu"]["id_parent"];
        $data = DB::table('bgdata_menus')->select('idMenu')
        ->where('idTypeMenu',$request["listMenu"]["idTypeMenu"])
        ->where('id_parent',$id_parent)
        ->where('Slug',$request["listMenu"]["Slug"])->first();
        if (!empty($data)) {
            return response(["Status" => false,"Massages" => "Trường Slug đã tồn tại trên hệ thống"], 200);
        }

        $helpers = new Helpers();
        $idMenu = $helpers->idOrderByKey("idMenu","bgdata_menus");
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $ArrMenu = $request["listMenu"];
        
        $ArrMenu["idMenu"] = $idMenu;
        $ArrMenu["OrderBy"] = $idMenu;
        $ArrMenu["id_parent"] = $id_parent;
        $ArrMenu["idUserAdd"] = session()->get('session_user')->idUser;
        $ArrMenu["created_at"] = $Today;
        DB::table('bgdata_menus')->insert($ArrMenu);
        return response(["Status" => true,"Massages" => "Thêm mới thành công"], 200);
    }


    public function Getmenu($idTypeMenu)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $ListMenu = DB::table('bgdata_menus')
        ->select('idMenu','idTypeMenu','id_parent','MenuName','Description','Link','Slug','Target','Icon','OrderBy','Active','idUserAdd')
        ->orderBy('OrderBy','ASC')->where('idTypeMenu',$idTypeMenu)->get();
        $ref   = [];
        $items = [];
        foreach ($ListMenu as $Menu) {
            $thisRef                = &$ref[$Menu->idMenu];
            $thisRef['id']          = $Menu->idMenu;
            $thisRef['idMenu']      = $Menu->idMenu;
            $thisRef['idTypeMenu']  = $Menu->idTypeMenu;
            $thisRef['id_parent']   = $Menu->id_parent;
            $thisRef['MenuName']    = $Menu->MenuName;
            $thisRef['Description'] = $Menu->Description;
            $thisRef['Link']        = $Menu->Link;
            $thisRef['Slug']        = $Menu->Slug;
            $thisRef['Icon']        = $Menu->Icon;
            $thisRef['OrderBy']     = $Menu->OrderBy;
            $thisRef['Active']      = $Menu->Active;
            $thisRef['idUserAdd']   = $Menu->idUserAdd;
            $thisRef['children']    = [];
            $thisRef["Permission"] = $this->inPermission($Menu->idMenu);
            if($Menu->id_parent == 0) {
                $items[] = &$thisRef;
           } else {
                $ref[$Menu->id_parent]['children'][] = &$thisRef;
           }
        }
        return $items;
    }
    // menu đệ quy
    public function listmenu(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $helpers = new Helpers();
        $items = $this->Getmenu($request->idTypeMenu);
        $listSelect = trim($this->listSelectMenu($items,""),"@@@");
        $arrSelect = [];
        if (!empty($listSelect)) {
            $listSelect = explode("@@@", $listSelect);
            foreach ($listSelect as $key => $value) {
                $sl_value = explode("||", $value);
                $arrSelect[$key]["idMenu"] = $sl_value[0];
                settype($arrSelect[$key]["idMenu"],'int');
                $arrSelect[$key]["MenuName"] = $sl_value[3] .$sl_value[1];
                $arrSelect[$key]["Icon"] = $sl_value[2];
            }
        }

        $ListMenu = DB::table('bgdata_typemenusystem')->select('TypeMenuName')->where('idTypeMenu',$request->idTypeMenu)->first();
        $TypeMenuName = $ListMenu->TypeMenuName;
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"typemenusystem");
        return response(["Status" => true,"datas" => $items,"menuSelect" => $arrSelect,'TypeMenuName' => $TypeMenuName,"Permission" => $Permission["idPermission"]], 200);
    }
    public function inPermission($idMenu)
    {
        
        $DataPermission = DB::table('bgdata_decentral_function')->where('idMenu',$idMenu)->first();
        $DataPermission = !empty($DataPermission) ? explode(',',$DataPermission->idPermission) : [];            
        $DataMenu = DB::table('bgdata_listpermission')->select('idList','ListName','Icon')
        ->whereIn('idList',$DataPermission)
        ->orderBy('OrderBy','ASC')
        ->get();
        return $DataMenu;
        
    }
    public function listSelectMenu($items,$Icon)
    {
        $html = '';
        foreach($items as $key=> $value) {
                $arrValue = [
                    'idMenu' => $value["idMenu"],
                    'MenuName' => $value["MenuName"],
                    'Icon' => $value["Icon"],
                    'LsTarget' => $Icon
                ];
                $html .= implode("||",$arrValue) ."@@@"; 
            if(count($value["children"]) > 0) {
                $html .= $this->listSelectMenu($value["children"],$Icon.'---|');
            }
        }
        return $html;
    }
    public function deleteMenu(Request $request, $id)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $ListMenu = DB::table('bgdata_menus')->select('idMenu')->where('id_parent',$id)->first();
        if (!empty($ListMenu)) {
            return response(["Status" => false,"Massages" => "Không thể xóa mục này"], 200);
        }else{
            DB::table('bgdata_menus')->where('idMenu',$id)->delete();
            return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
        }
    }
    public function activeStatusOneMenu(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $idMenu = $request->idMenu;
        $Active = ($request->Active == 1) ? 0 : 1;
        DB::table('bgdata_menus')->where('idMenu',$idMenu)->update(['Active' => $Active]);
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
        
    }
    public function editMenu($idMenu,$idTypeMenu)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $ListMenu = DB::table('bgdata_menus')
        ->select('idMenu','idTypeMenu','id_parent','MenuName','Description','Link','Slug','Icon','Target','Active')
        ->where('idMenu',$idMenu)->where('idTypeMenu',$idTypeMenu)->first();
        return response(["Status" => true,"datas" => $ListMenu], 200);
    }
    public function updateMenu(Request $request,$idMenu)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $ArrMenu = $request["listMenu"];
        $id_parent = ($request["listMenu"]["id_parent"] == "") ? 0 : $request["listMenu"]["id_parent"];
        $data = DB::table('bgdata_menus')->select('idMenu')
        ->where('idTypeMenu',$request["listMenu"]["idTypeMenu"])
        ->where('id_parent',$id_parent)->where('idMenu','<>',$ArrMenu["idMenu"])
        ->where('Slug',$request["listMenu"]["Slug"])->first();
        if (!empty($data)) {
            return response(["Status" => false,"Massages" => "Trường Slug đã tồn tại trên hệ thống"], 200);
        }
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $ArrMenu["id_parent"] = $id_parent;
        $ArrMenu["idUserUpdate"] = session()->get('session_user')->idUser;
        $ArrMenu["updated_at"] = $Today;
        DB::table('bgdata_menus')->where('idMenu',$ArrMenu["idMenu"])->update($ArrMenu);
        return response(["Status" => true,"Massages" => "Chỉnh sửa thành công"], 200);
    }
    public function parseJsonArray($jsonArray, $id_parent = 0) {

        $return = [];
        foreach ($jsonArray as $subArray) {
            $returnSubSubArray = array();
            if (isset($subArray["children"])) {
                $returnSubSubArray = $this->parseJsonArray($subArray["children"], $subArray["idMenu"]);
            }
            $return[] = array('idMenu' => $subArray["idMenu"], 'id_parent' => $id_parent);
            $return = array_merge($return, $returnSubSubArray);
        }
        return $return;
    }

    public function UpdateOrderByMenu(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $nestableItems = $request["nestableItems"];
        $DataMenu = $this->parseJsonArray($nestableItems);
        foreach ($DataMenu as $key => $value) {
            $key++;
            DB::table('bgdata_menus')->where('idMenu',$value["idMenu"])->update(['id_parent' => $value["id_parent"],'OrderBy' => $key]);
        }
        return response(["Status" => true,"Massages" => "Chỉnh sửa thành công"], 200);
    }

    public function loadPermission(Request $request, $idMenu)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $DataPermission = DB::table('bgdata_decentral_function')->where('idMenu',$idMenu)->first();
        $DataPermission = !empty($DataPermission) ? explode(',',$DataPermission->idPermission) : [];
        $DataMenu = DB::table('bgdata_listpermission')->select('idList','ListName','Icon',DB::raw('false AS checked'))
        ->orderBy('OrderBy','ASC')
        ->get();
        foreach ($DataMenu as $key => $listPm) {
            if (array_search($listPm->idList,$DataPermission) !== FALSE) {
                $DataMenu[$key]->checked = true;
            }
        }
        return response(["Status" => true,"dataPermission" => $DataMenu], 200);
    }

    public function savePermission(Request $request, $idMenu)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Permission = $request["Permission"];
        DB::table('bgdata_decentral_function')->where('idMenu',$idMenu)->delete();
        if (count($Permission) > 0) {
            $Permission = implode(",",$Permission);
            DB::table('bgdata_decentral_function')->insert(['idMenu' => $idMenu,'idPermission' => $Permission]);
        }
        return response(["Status" => true,"Massages" => "Chỉnh sửa thành công"], 200);
    }

    public function exportExcel(Request $request) // xuất excel
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $helpers = new Helpers();

        $orderby = $request->has("orderby") ? $request["orderby"] : "DESC";
        $filed   = $request->has("filed") ? $request["filed"] : "created_at";
        $datas = DB::table('bgdata_typemenusystem AS bg_type')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_type.idUserAdd')
        ->select("bg_type.TypeMenuName","bg_type.created_at","bg_type.Active","bg_type.Description","su.FullName","su.IDCode AS FullNameIDCode")
        ->orderBy($filed,$orderby);

        // tìm kiếm
        $request->has("TypeMenuName") ? $datas = $datas->where("bg_type.TypeMenuName", "like", "%".$request["TypeMenuName"]."%") : "";
        $request->has("Description") ? $datas = $datas->where("bg_type.Description", "like", "%".$request["Description"]."%") : "";
        $request->has("FullName") ? $datas = $datas->where("su.FullName", "like", "%".$request["FullName"]."%") : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_type.Active", explode(',',$request["Active"])) : "";
        $request->has("date_from") ? $datas = $datas->whereDate('bg_type.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_type.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        $datas = $datas->get();
        return response(["Status" => true,"datas" => $datas], 200);
    }
}
