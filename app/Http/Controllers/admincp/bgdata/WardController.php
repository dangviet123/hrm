<?php

namespace App\Http\Controllers\admincp\bgdata;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\admincp\bgdata\Ward;
use Illuminate\Support\Facades\DB;
use App\functions\Helpers;
use Illuminate\Validation\Validator;

class WardController extends Controller
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
        $datas = DB::table('bgdata_ward AS bg_g')
        ->leftJoin('bgdata_provinces AS p', 'p.idProvinces', '=', 'bg_g.idProvinces')
        ->leftJoin('bgdata_district AS d', 'd.idDistrict', '=', 'bg_g.idDistrict')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->select("p.Provinces","d.District","bg_g.*","su.FullName",DB::raw('false AS checked'))
        ->orderBy("bg_g.$filed",$orderby);
        // tìm kiếm
        $request->has("Ward") ? $datas = $datas->where("bg_g.Ward", "like", "%".$request["Ward"]."%") : "";
        $request->has("idProvinces") ? $datas = $datas->whereIn("bg_g.idProvinces", explode(',',$request["idProvinces"])) : "";
        $request->has("idDistrict") ? $datas = $datas->whereIn("bg_g.idDistrict", explode(',',$request["idDistrict"])) : "";
        $request->has("IDCode") ? $datas = $datas->where("bg_g.IDCode", "like", "%".$request["IDCode"]."%") : "";
        $request->has("FullName") ? $datas = $datas->where("su.FullName", "like", "%".$request["FullName"]."%") : "";
        $request->has("Notes") ? $datas = $datas->where("bg_g.Notes", "like", "%".$request["Notes"]."%") : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_g.Active", explode(',',$request["Active"])) : "";
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        // end tìm kiếm
        
        $datas = $datas->paginate($limit);
        $Provinces = $helpers->getProvinces("");
        $District = $helpers->getDistrict("",$request["idProvinces"]);
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"ward");
        return response([
            "Status" => true,
            "datas" => $datas,
            "Provinces" => $Provinces,
            "District" => $District,
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
        $OrderBy = $helpers->idOrderByKey("idWard","bgdata_ward");
        $IDCode = "WA".$helpers->OrderByIDCode( 'IDCode', "bgdata_ward", 5);
        $Provinces = $helpers->getProvinces("");
        $District = $helpers->getDistrict("","");
        return response([
            "Status" => true,
            "OrderBy" => $OrderBy,
            "IDCode" => $IDCode,
            "Provinces" => $Provinces,
            "District" => $District
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
        $IDCode = "DT".$helpers->OrderByIDCode( 'IDCode', "bgdata_ward", 5);
        $idWard = $helpers->idOrderByKey("idWard","bgdata_ward");
        $listGroup = new Ward;
        $listGroup->idWard     = $idWard;
        $listGroup->IDCode      = $IDCode;
        $listGroup->Ward   = $request["listGroup"]["Ward"];
        $listGroup->Notes = $request["listGroup"]["Notes"];
        $listGroup->idProvinces = $request["listGroup"]["idProvinces"];
        $listGroup->idDistrict = $request["listGroup"]["idDistrict"];
        $listGroup->idUserAdd   = session()->get('session_user')->idUser;
        $listGroup->OrderBy     = $request["listGroup"]["OrderBy"];
        $listGroup->Active      = $request["listGroup"]["Active"];
        $listGroup->save();
        return response(["Status" => true,"idWard" => $idWard], 200);
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
        $helpers = new Helpers();
        $datas = Ward::find($id);
        $Provinces = $helpers->getProvinces("");
        $District = $helpers->getDistrict("",$datas->idProvinces);
        return response(["Status" => true,"datas" => $datas,"Provinces"=> $Provinces,"District" => $District], 200);
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
        $idWard            = $request["listGroup"]["idWard"];
        $listGroup               = Ward::find($idWard);
        $listGroup->Ward   = $request["listGroup"]["Ward"];
        $listGroup->Notes  = $request["listGroup"]["Notes"];
        $listGroup->idProvinces = $request["listGroup"]["idProvinces"];
        $listGroup->idDistrict = $request["listGroup"]["idDistrict"];
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
        DB::table('bgdata_ward')->whereIn('idWard',$data_active)->delete();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function activeStatusOne(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $idWard = $request->idWard;
        $Active = ($request->Active == 1) ? 0 : 1;
        $listper = Ward::find($idWard);
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
        $listper = Ward::whereIn('idWard', $data_active)
        ->update(['Active' => $Active]);
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function sortBy(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        foreach ($request->idWard as $key => $idWard) {
            DB::table('bgdata_ward')->where('idWard', $idWard)
            ->update(['OrderBy' => $request->orderby[$key]]);
        }
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function loadDistrict(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $helpers = new Helpers();
        $idProvinces = is_array($request->idProvinces) ? implode(',',$request->idProvinces) : $request->idProvinces;
        $District = $helpers->getDistrict("",$idProvinces);
        return response(["Status" => true,"District" => $District], 200);
    }

    public function exportExcel(Request $request) // xuất excel
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $helpers = new Helpers();

        $orderby = $request->has("orderby") ? $request["orderby"] : "DESC";
        $filed   = $request->has("filed") ? $request["filed"] : "created_at";
        $datas = DB::table('bgdata_ward AS bg_g')
        ->leftJoin('bgdata_provinces AS p', 'p.idProvinces', '=', 'bg_g.idProvinces')
        ->leftJoin('bgdata_district AS d', 'd.idDistrict', '=', 'bg_g.idDistrict')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->select("bg_g.created_at","bg_g.Active","bg_g.IDCode","bg_g.Ward","bg_g.Notes","p.IDCode AS ProvincesIDCode","p.Provinces","p.IDCode AS ProvincesIDCode","d.District","d.IDCode AS DistrictIDCode","su.FullName","su.IDCode AS FullNameIDCode")
        ->orderBy("bg_g.$filed",$orderby);
        // tìm kiếm
        $request->has("Ward") ? $datas = $datas->where("bg_g.Ward", "like", "%".$request["Ward"]."%") : "";
        $request->has("idProvinces") ? $datas = $datas->whereIn("bg_g.idProvinces", explode(',',$request["idProvinces"])) : "";
        $request->has("idDistrict") ? $datas = $datas->whereIn("bg_g.idDistrict", explode(',',$request["idDistrict"])) : "";
        $request->has("IDCode") ? $datas = $datas->where("bg_g.IDCode", "like", "%".$request["IDCode"]."%") : "";
        $request->has("FullName") ? $datas = $datas->where("su.FullName", "like", "%".$request["FullName"]."%") : "";
        $request->has("Notes") ? $datas = $datas->where("bg_g.Notes", "like", "%".$request["Notes"]."%") : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_g.Active", explode(',',$request["Active"])) : "";
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        $datas = $datas->get();
        return response(["Status" => true,"datas" => $datas], 200);
    }
}
