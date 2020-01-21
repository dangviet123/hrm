<?php

namespace App\Http\Controllers\admincp\staffinformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\functions\Helpers;
use Illuminate\Support\Facades\DB;
use App\admincp\staffinformation\Departments;

class DepartmentsController extends Controller
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
        $datas = DB::table('staff_departments AS bg_g')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->select("bg_g.*","su.FullName",DB::raw('false AS checked'))
        ->orderBy($filed,$orderby);
        // tìm kiếm
        $request->has("DepartmentName") ? $datas = $datas->where("bg_g.DepartmentName", "like", "%".$request["DepartmentName"]."%") : "";
        $request->has("FullName") ? $datas = $datas->where("su.FullName", "like", "%".$request["FullName"]."%") : "";
        $request->has("Description") ? $datas = $datas->where("bg_g.Description", "like", "%".$request["Description"]."%") : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_g.Active", explode(',',$request["Active"])) : "";
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        // end tìm kiếm
        
        $datas = $datas->paginate($limit);

        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"departments");
        return response([
            "Status"     => true,
            "datas"      => $datas,
            "orderby"    => $orderby,
            "filed"      => $filed,
            'limit'      => $limit,
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
        $OrderBy = $helpers->idOrderByKey("idDepartment","staff_departments");
        $IDCode = "D".$helpers->OrderByIDCode( 'IDCode', "staff_departments", 5);
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
        $IDCode = "D".$helpers->OrderByIDCode( 'IDCode', "staff_departments", 5);
        $idDepartment = $helpers->idOrderByKey("idDepartment","staff_departments");
        $listGroup = new Departments;
        $listGroup->idDepartment     = $idDepartment;
        $listGroup->IDCode      = $IDCode;
        $listGroup->DepartmentName   = $request["listGroup"]["DepartmentName"];
        $listGroup->Description = $request["listGroup"]["Description"];
        $listGroup->idUserAdd   = session()->get('session_user')->idUser;
        $listGroup->OrderBy     = $request["listGroup"]["OrderBy"];
        $listGroup->Active      = $request["listGroup"]["Active"];
        if ($listGroup->save()) {
            return response(["Status" => true,"idDepartment" => $idDepartment], 200);
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
        $datas = Departments::find($id);
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
        $idDepartment            = $request["listGroup"]["idDepartment"];
        $listGroup               = Departments::find($idDepartment);
        $listGroup->DepartmentName    = $request["listGroup"]["DepartmentName"];
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
        DB::table('staff_departments')->whereIn('idDepartment',$data_active)->delete();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function activeStatusOne(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $idDepartment = $request->idDepartment;
        $Active = ($request->Active == 1) ? 0 : 1;
        $listper = Departments::find($idDepartment);
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
        $listper = Departments::whereIn('idDepartment', $data_active)
        ->update(['Active' => $Active]);
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function sortBy(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        foreach ($request->idDepartment as $key => $idDepartment) {
            DB::table('staff_departments')->where('idDepartment', $idDepartment)
            ->update(['OrderBy' => $request->orderby[$key]]);
        }
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }

    public function exportExcel(Request $request) // xuất excel
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $helpers = new Helpers();

        $orderby = $request->has("orderby") ? $request["orderby"] : "DESC";
        $filed   = $request->has("filed") ? $request["filed"] : "created_at";
        $datas = DB::table('staff_departments AS bg_g')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->select("bg_g.DepartmentName","bg_g.IDCode","bg_g.created_at","bg_g.Active","bg_g.Description","su.FullName","su.IDCode AS FullNameIDCode")
        ->orderBy($filed,$orderby);
        // tìm kiếm
        $request->has("DepartmentName") ? $datas = $datas->where("bg_g.DepartmentName", "like", "%".$request["DepartmentName"]."%") : "";
        $request->has("FullName") ? $datas = $datas->where("su.FullName", "like", "%".$request["FullName"]."%") : "";
        $request->has("Description") ? $datas = $datas->where("bg_g.Description", "like", "%".$request["Description"]."%") : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_g.Active", explode(',',$request["Active"])) : "";
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        $datas = $datas->get();
        return response(["Status" => true,"datas" => $datas], 200);
    }
}
