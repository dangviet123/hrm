<?php

namespace App\Http\Controllers\admincp\staffinformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\functions\Helpers;
use Illuminate\Support\Facades\DB;
use App\admincp\staffinformation\TypeofWork;

class TypeofWorkController extends Controller
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
        $datas = DB::table('staff_type_of_work AS bg_g')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->select("bg_g.*","su.FullName",DB::raw('false AS checked'))
        ->orderBy($filed,$orderby);
        // tìm kiếm
        $request->has("TypeOfWorkName") ? $datas = $datas->where("bg_g.TypeOfWorkName", "like", "%".$request["TypeOfWorkName"]."%") : "";
        $request->has("IDCode") ? $datas = $datas->where("bg_g.IDCode", "like", "%".$request["IDCode"]."%") : "";
        $request->has("FullName") ? $datas = $datas->where("su.FullName", "like", "%".$request["FullName"]."%") : "";
        $request->has("Description") ? $datas = $datas->where("bg_g.Description", "like", "%".$request["Description"]."%") : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_g.Active", explode(',',$request["Active"])) : "";
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        // end tìm kiếm
        
        $datas = $datas->paginate($limit);
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"typeofwork");
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
        $OrderBy = $helpers->idOrderByKey("idTypeOfWork","staff_type_of_work");
        $IDCode = "TW".$helpers->OrderByIDCode( 'IDCode', "staff_type_of_work", 5);
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
        $IDCode = "TW".$helpers->OrderByIDCode( 'IDCode', "staff_type_of_work", 5);
        $idTypeOfWork = $helpers->idOrderByKey("idTypeOfWork","staff_type_of_work");
        $listGroup = new TypeofWork;
        $listGroup->idTypeOfWork   = $idTypeOfWork;
        $listGroup->IDCode      = $IDCode;
        $listGroup->TypeOfWorkName   = $request["listGroup"]["TypeOfWorkName"];
        $listGroup->Description = $request["listGroup"]["Description"];
        $listGroup->idUserAdd   = session()->get('session_user')->idUser;
        $listGroup->OrderBy     = $request["listGroup"]["OrderBy"];
        $listGroup->Active      = $request["listGroup"]["Active"];
        $listGroup->save();
        return response(["Status" => true,"idTypeOfWork" => $idTypeOfWork], 200);
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
        $datas = TypeofWork::find($id);
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
        $idTypeOfWork            = $request["listGroup"]["idTypeOfWork"];
        $listGroup               = TypeofWork::find($idTypeOfWork);
        $listGroup->TypeOfWorkName    = $request["listGroup"]["TypeOfWorkName"];
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
    public function destroy(Request $request,$id)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $data_active = $request->data_active;
        DB::table('staff_type_of_work')->whereIn('idTypeOfWork',$data_active)->delete();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }

    public function activeStatusOne(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $idTypeOfWork = $request->idTypeOfWork;
        $Active = ($request->Active == 1) ? 0 : 1;
        $listper = TypeofWork::find($idTypeOfWork);
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
        $listper = TypeofWork::whereIn('idTypeOfWork', $data_active)
        ->update(['Active' => $Active]);
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function sortBy(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        foreach ($request->idTypeOfWork as $key => $idTypeOfWork) {
            DB::table('staff_type_of_work')->where('idTypeOfWork', $idTypeOfWork)
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
        $datas = DB::table('staff_type_of_work AS bg_g')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->select("bg_g.TypeOfWorkName","bg_g.IDCode","bg_g.created_at","bg_g.Active","bg_g.Description","su.FullName","su.IDCode AS FullNameIDCode")
        ->orderBy($filed,$orderby);
        // tìm kiếm
        $request->has("TypeOfWorkName") ? $datas = $datas->where("bg_g.TypeOfWorkName", "like", "%".$request["TypeOfWorkName"]."%") : "";
        $request->has("IDCode") ? $datas = $datas->where("bg_g.IDCode", "like", "%".$request["IDCode"]."%") : "";
        $request->has("FullName") ? $datas = $datas->where("su.FullName", "like", "%".$request["FullName"]."%") : "";
        $request->has("Description") ? $datas = $datas->where("bg_g.Description", "like", "%".$request["Description"]."%") : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_g.Active", explode(',',$request["Active"])) : "";
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        $datas = $datas->get();
        return response(["Status" => true,"datas" => $datas], 200);
    }
}
