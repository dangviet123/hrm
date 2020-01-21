<?php

namespace App\Http\Controllers\admincp\staffinformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\functions\Helpers;
use Illuminate\Support\Facades\DB;
use App\admincp\staffinformation\Promote;
class PromoteController extends Controller
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
        $datas = DB::table('staff_promote AS bg_g')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->leftJoin('staff_users AS skt', 'skt.idUser', '=', 'bg_g.idStaff')
        ->leftJoin('staff_positions AS sp', 'sp.idPosition', '=', 'bg_g.idPosition')
        ->select("bg_g.*","sp.PositionName","su.FullName AS FullNameAdd","skt.FullName AS FullNameStaff",DB::raw('false AS checked'),"skt.IDCode AS IDCodeStaff",DB::raw('"" AS data_detail'))
        ->orderBy($filed,$orderby);
        // tìm kiếm
        $request->has("IDCode") ? $datas = $datas->where("bg_g.IDCode", "like", "%".$request["IDCode"]."%") : "";
        $request->has("Promote") ? $datas = $datas->where("bg_g.Promote", "like", "%".$request["Promote"]."%") : "";
        $request->has("Notes") ? $datas = $datas->where("bg_g.Notes", "like", "%".$request["Notes"]."%") : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_g.Active", explode(',',$request["Active"])) : "";
        $request->has("Locked") ? $datas = $datas->whereIn("bg_g.Locked", explode(',',$request["Locked"])) : "";
        $request->has("ManageCheck") ? $datas = $datas->whereIn("bg_g.ManageCheck", explode(',',$request["ManageCheck"])) : "";
        $request->has("idDepartment") ? $datas = $datas->whereIn("bg_g.idDepartment", explode(',',$request["idDepartment"])) : "";
        $request->has("idPosition") ? $datas = $datas->whereIn("bg_g.idPosition", explode(',',$request["idPosition"])) : "";
        $request->has("idCompany") ? $datas = $datas->whereIn("bg_g.idCompany", explode(',',$request["idCompany"])) : "";
        $request->has("idStaff") ? $datas = $datas->whereIn("bg_g.idStaff", explode(',',$request["idStaff"])) : "";
        $request->has("idUserAdd") ? $datas = $datas->whereIn("bg_g.idUserAdd", explode(',',$request["idUserAdd"])) : "";
        $request->has("idManageCheck") ? $datas = $datas->whereIn("bg_g.idManageCheck", explode(',',$request["idManageCheck"])) : ""; 
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        // end tìm kiếm
        
        $datas = $datas->paginate($limit);

        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"promote");

        $DataUsers =  $helpers->getUsers("");
        $DataCompany    = $helpers->getCompany("");
        $DataDepartment = $helpers->getDepartment("");
        $DataPosition   = $helpers->getPosition("");
        return response([
            "Status" => true,
            "datas" => $datas,
            "orderby" => $orderby,
            "filed" => $filed,
            'limit' => $limit,
            "Permission" => $Permission["idPermission"],
            "DataInfoPermission" => $Permission["DataInfoPermission"],
            "DataUsers" => $DataUsers,
            "DataCompany" => $DataCompany,
            "DataDepartment" => $DataDepartment,
            "DataPosition" => $DataPosition
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
        $IDCode = "TC".$helpers->OrderByIDCode( 'IDCode', "staff_promote", 5);
        $DataUsers =  $helpers->getUsers("");
        $DataCompany    = $helpers->getCompany("");
        $DataDepartment = $helpers->getDepartment("");
        $DataPosition   = $helpers->getPosition("");
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"promote");
        return response([
            "Status" => true,
            "IDCode" => $IDCode,
            "DataUsers" => $DataUsers,
            "Permission" => $Permission["idPermission"],
            "DataCompany" => $DataCompany,
            "DataDepartment" => $DataDepartment,
            "DataPosition" => $DataPosition,
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
        $listGroup = new Promote;
        $listGroup->idPromote = $helpers->idOrderByKey("idPromote","staff_promote");
        $listGroup->IDCode = "TC".$helpers->OrderByIDCode( 'IDCode', "staff_promote", 5);
        $listGroup->Promote = $request["listGroup"]["Promote"];
        $listGroup->Notes = $request["listGroup"]["Notes"];
        $listGroup->idStaff = $request["listGroup"]["idStaff"];
        $listGroup->PromoteDay =  $helpers->FormatDateSql($request["listGroup"]["PromoteDay"]);
        $listGroup->idCompany = $request["listGroup"]["idCompany"];
        $listGroup->idDepartment = $request["listGroup"]["idDepartment"];
        $listGroup->idPosition = $request["listGroup"]["idPosition"];
        $listGroup->idPositionOl = $request["listGroup"]["idPositionOl"];
        $listGroup->Active = $request["listGroup"]["Active"];
        $listGroup->idUserAdd   = session()->get('session_user')->idUser;

        $DataFiles = $request["DataFiles"];
        foreach ($DataFiles as $key => $file) {
            $key++;
            if ($file["FileNameBase64"] !="" || $file["Description"] !="") {
                $ImageAv = explode(";", $file["FileNameBase64"]);
                $ImageAv1 = explode(",", $ImageAv[1]);
                $data = base64_decode($ImageAv1[1]);
                $NameFile = $request["listGroup"]["IDCode"]."-".uniqid().$key.'.'.$file["Type"];
                $imageName = public_path("images/promote/").$NameFile;
                file_put_contents($imageName, $data);
                DB::table('staff_promote_file')->insert([
                    'idPromote' => $listGroup->idPromote,
                    'FileNameBase64' => $NameFile,
                    'Description' => $file["Description"],
                    'Type' => $file["Type"],
                    'Size' => $file["Size"]
                ]);
            }
        }
        $listGroup->save();
        return response(["Status" => true,"idPromote" => $listGroup->idPromote], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
        $helpers        = new Helpers();
        $datas          = Promote::find($id);
        $datas->PromoteDay = $helpers->FormatDate($datas->PromoteDay);
        $DataUsers =  $helpers->getUsers("");
        $DataCompany    = $helpers->getCompany("");
        $DataDepartment = $helpers->getDepartment("");
        $DataPosition   = $helpers->getPosition("");
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"promote");

        $DataFiles = DB::table('staff_promote_file')->select("*","FileNameBase64 AS FileNameBase64Ol",DB::raw('false AS isChange'))->where('idPromote',$id)->get();
        if (count($DataFiles) == 0) {
            $DataFiles = [["FileNameBase64" => "","Description" => "","Type" => "", "Size" => 0]];
        }
        return response([
            "Status" => true,
            "datas" => $datas,
            "DataUsers" => $DataUsers,
            "Permission" => $Permission["idPermission"],
            "DataCompany" => $DataCompany,
            "DataDepartment" => $DataDepartment,
            "DataPosition" => $DataPosition,
            "DataFiles" => $DataFiles
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
        $helpers        = new Helpers();
        $idPromote          = $request["listGroup"]["idPromote"];
        $listGroup               = Promote::find($idPromote);
        $listGroup->Promote = $request["listGroup"]["Promote"];
        $listGroup->Notes = $request["listGroup"]["Notes"];
        $listGroup->idStaff = $request["listGroup"]["idStaff"];
        $listGroup->PromoteDay =  $helpers->FormatDateSql($request["listGroup"]["PromoteDay"]);
        $listGroup->idCompany = $request["listGroup"]["idCompany"];
        $listGroup->idDepartment = $request["listGroup"]["idDepartment"];
        $listGroup->idPosition = $request["listGroup"]["idPosition"];
        $listGroup->idPositionOl = $request["listGroup"]["idPositionOl"];
        $listGroup->idUserUpdate   = session()->get('session_user')->idUser;
        $DataFiles = $request["DataFiles"];
        foreach ($DataFiles as $key => $file) {
            $key++;
            if ($file["FileNameBase64"] !="" || $file["Description"] !="") {
                if ((isset($file["idPromote"]) && $file["idPromote"] > 0) && (isset($file["idPromoteFile"]) && $file["idPromoteFile"] > 0)) {
                    if ($file["isChange"] == true) {
                        $path = public_path("images/promote/").$file["FileNameBase64Ol"];
                        if (\File::exists($path)) {
                            \File::delete($path);
                        }
                        $ImageAv = explode(";", $file["FileNameBase64"]);
                        $ImageAv1 = explode(",", $ImageAv[1]);
                        $data = base64_decode($ImageAv1[1]);
                        $NameFile = $request["listGroup"]["IDCode"]."-".uniqid().$key.'.'.$file["Type"];
                        $imageName = public_path("images/promote/").$NameFile;
                        file_put_contents($imageName, $data);
                        DB::table('staff_promote_file')->where('idPromoteFile',$file["idPromoteFile"])->update([
                            'idPromote' => $idPromote,
                            'FileNameBase64' => $NameFile,
                            'Description' => $file["Description"],
                            'Type' => $file["Type"],
                            'Size' => $file["Size"]
                        ]);
                    }else{
                        DB::table('staff_promote_file')->where('idPromoteFile',$file["idPromoteFile"])->update([
                            'idPromote' => $idPromote,
                            'Description' => $file["Description"],
                            'Type' => $file["Type"],
                            'Size' => $file["Size"]
                        ]);
                    }
                }else{
                    $ImageAv = explode(";", $file["FileNameBase64"]);
                    $ImageAv1 = explode(",", $ImageAv[1]);
                    $data = base64_decode($ImageAv1[1]);
                    $NameFile = $request["listGroup"]["IDCode"]."-".uniqid().$key.'.'.$file["Type"];
                    $imageName = public_path("images/promote/").$NameFile;
                    file_put_contents($imageName, $data);
                    DB::table('staff_promote_file')->insert([
                        'idPromote' => $idPromote,
                        'FileNameBase64' => $NameFile,
                        'Description' => $file["Description"],
                        'Type' => $file["Type"],
                        'Size' => $file["Size"]
                    ]);
                }
            }
        }
        $listGroup->save();
        return response(["Status" => true,"idPromote" => $idPromote], 200);
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
        $achievements = DB::table('staff_promote_file')->select("FileNameBase64")->whereIn("idPromote",$data_active)->get();
        if (count($achievements) > 0) {
            foreach ($achievements as $ach) {
                $path = public_path("images/promote/").$ach->FileNameBase64;
                if (\File::exists($path)) {
                    \File::delete($path);
                }
            }
        }
        DB::table('staff_promote_file')->whereIn('idPromote',$data_active)->delete();
        DB::table('staff_promote')->whereIn('idPromote',$data_active)->delete();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function loadInfoUser(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $idStaff = $request->idStaff;
        $UserInfo = DB::table('staff_users')->select("idCompany","idDepartment","idPosition")
        ->where("idUser",$idStaff)->first();
        return response(["Status" => true,"UserInfo" => $UserInfo], 200);
    }

    public function deleteListFile(Request $request,$idPromote,$idPromoteFile)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $FileNameBase64 = $request->FileNameBase64;
        $path = public_path("images/promote/").$FileNameBase64;
        if (\File::exists($path)) {
            \File::delete($path);
        }
        DB::table('staff_promote_file')->where("idPromote",$idPromote)->where("idPromoteFile",$idPromoteFile)->delete();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }

    public function activeStatusOne(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $idPromote = $request->idPromote;
        $Active = ($request->Active == 1) ? 0 : 1;
        if ($Active == 0) {
            $listper = Promote::where("idPromote",$idPromote)->where("ManageCheck",0)->where("Locked",0)
            ->update(['Active' => $Active,"DateCandel" => $Today,"Locked" => 1,"idUserCandel" => session()->get('session_user')->idUser]);
        }else{
            $listper = Promote::where("idPromote",$idPromote)->where("ManageCheck",0)->where("Locked",0)
            ->update(['Active' => $Active,"DateCandel" => $Today,"idUserCandel" => session()->get('session_user')->idUser]);
        }
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
        
    }
    public function activeStatusAll(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);

        $data_active = $request->data_active;
        $Active = $request->Active;
        if ($Active == 0) {
            $listper = Promote::whereIn('idPromote', $data_active)->where("ManageCheck",0)->where("Locked",0)
        ->update(['Active' => $Active,"DateCandel" => $Today,"Locked" => 1,"idUserCandel" => session()->get('session_user')->idUser]);
        }else{
            $listper = Promote::whereIn('idPromote', $data_active)->where("ManageCheck",0)->where("Locked",0)
        ->update(['Active' => $Active,"DateCandel" => $Today,"idUserCandel" => session()->get('session_user')->idUser]);
        }
        
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }

    public function LockedAll(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);

        $data_active = $request->data_active;
        $Locked = $request->Locked;
        $listper = Promote::whereIn('idPromote', $data_active)
        ->update(['Locked' => $Locked,"DateLocked" => $Today,"idUserLocked" => session()->get('session_user')->idUser]);
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }

    public function ManageCheckOne(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $idPromote = $request->idPromote;
        $ManageCheck = $request->ManageCheck;
        $Promote = Promote::where("idPromote",$idPromote)
        ->where("Active",1)
        ->where("Locked",0)
        ->where("ManageCheck",0)
        ->update([
            "ManageCheck" => $ManageCheck,
            "idManageCheck" => session()->get('session_user')->idUser,
            "DateManageCheck" => $Today,
            "Locked" => 1
        ]);

        $getPromote = DB::table('staff_promote')->select("idStaff","idPosition")->where("idPromote",$idPromote)->first();
        DB::table('staff_users')->where("idUser",$getPromote->idStaff)->update(["idPosition" => $getPromote->idPosition]);

        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function ManageCheckAll(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $data_active = $request->data_active;
        $ManageCheck = $request->ManageCheck;
        $Promote = Promote::whereIn("idPromote",$data_active)
        ->where("Active",1)
        ->where("Locked",0)
        ->where("ManageCheck",0)
        ->update([
            "ManageCheck" => $ManageCheck,
            "idManageCheck" => session()->get('session_user')->idUser,
            "DateManageCheck" => $Today,
            "Locked" => 1
        ]);

        foreach ($data_active as $idPromote) {
            $getPromote = DB::table('staff_promote')->select("idStaff","idPosition")->where("idPromote",$idPromote)->first();
            DB::table('staff_users')->where("idUser",$getPromote->idStaff)->update(["idPosition" => $getPromote->idPosition]);
        }

        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function LockedOne(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $idPromote = $request->idPromote;
        $Locked = $request->Locked;
        $listper = Promote::find($idPromote);
        $listper->Locked = $Locked;
        $listper->DateLocked = $Today;
        $listper->idUserLocked = session()->get('session_user')->idUser;
        $listper->save();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }

    public function getInfoPromote($idPromote)
    {
        $datas = DB::table('staff_promote AS sa')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'sa.idUserAdd')
        ->leftJoin('staff_users AS suc', 'suc.idUser', '=', 'sa.idManageCheck')
        ->leftJoin('staff_company AS sc', 'sc.idCompany', '=', 'sa.idCompany')
        ->leftJoin('staff_departments AS sd', 'sd.idDepartment', '=', 'sa.idDepartment')
        ->leftJoin('staff_positions AS sp', 'sp.idPosition', '=', 'sa.idPositionOl')
        ->select("su.FullName AS FullNameAdd","sa.created_at","sc.CompanyName","sd.DepartmentName","sp.PositionName","sa.DateManageCheck","suc.FullName AS FullNameCheck",
        "sa.ManageCheck")
        ->where("sa.idPromote",$idPromote)
        ->first();
        return response(["Status" => true,"data_detail" => $datas], 200);
    }
    public function loadListUser(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $helpers = new Helpers();
        $idCompany = $request->idCompany;
        $idDepartment = $request->idDepartment;
        $listUser = DB::table('staff_users')->select(DB::raw('GROUP_CONCAT(DISTINCT idUser) AS idUser'))->where("idCompany",$idCompany)->where("idDepartment",$idDepartment)->first();
        $DataUsers = ($listUser->idUser != NULL)  ?  $helpers->getUsers($listUser->idUser) : [];
        return response(["Status" => true,"DataUsers" => $DataUsers], 200);
    }

    public function exportExcel(Request $request) // xuất excel
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $helpers = new Helpers();

        $orderby = $request->has("orderby") ? $request["orderby"] : "DESC";
        $filed   = $request->has("filed") ? $request["filed"] : "created_at";
        $datas = DB::table('staff_promote AS bg_g')
        ->leftJoin('staff_users AS sff', 'sff.idUser', '=', 'bg_g.idStaff')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->leftJoin('staff_users AS suc', 'suc.idUser', '=', 'bg_g.idManageCheck')
        ->leftJoin('staff_company AS sc', 'sc.idCompany', '=', 'bg_g.idCompany')
        ->leftJoin('staff_departments AS sd', 'sd.idDepartment', '=', 'bg_g.idDepartment')
        ->leftJoin('staff_positions AS spn', 'spn.idPosition', '=', 'bg_g.idPosition')
        ->leftJoin('staff_positions AS sp', 'sp.idPosition', '=', 'bg_g.idPositionOl')
        ->select("sff.IDCode AS FullNameSffIDCode","sff.FullName AS FullNameSff","spn.PositionName AS PositionNameNew","bg_g.PromoteDay","bg_g.DateManageCheck","bg_g.idManageCheck","bg_g.Notes","bg_g.Promote","bg_g.IDCode","bg_g.created_at","bg_g.Active","su.FullName AS FullNameAdd","su.IDCode AS FullNameAddIDCode","sc.IDCode AS CompanyNameIDCode","sc.CompanyName","sd.DepartmentName","sp.PositionName AS PositionNameOl","bg_g.DateManageCheck","suc.IDCode AS FullNameCheckIDCode","suc.FullName AS FullNameCheck",
        "bg_g.ManageCheck")
        ->orderBy("bg_g.".$filed,$orderby);
        // tìm kiếm
        $request->has("IDCode") ? $datas = $datas->where("bg_g.IDCode", "like", "%".$request["IDCode"]."%") : "";
        $request->has("Promote") ? $datas = $datas->where("bg_g.Promote", "like", "%".$request["Promote"]."%") : "";
        $request->has("Notes") ? $datas = $datas->where("bg_g.Notes", "like", "%".$request["Notes"]."%") : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_g.Active", explode(',',$request["Active"])) : "";
        $request->has("Locked") ? $datas = $datas->whereIn("bg_g.Locked", explode(',',$request["Locked"])) : "";
        $request->has("ManageCheck") ? $datas = $datas->whereIn("bg_g.ManageCheck", explode(',',$request["ManageCheck"])) : "";
        $request->has("idDepartment") ? $datas = $datas->whereIn("bg_g.idDepartment", explode(',',$request["idDepartment"])) : "";
        $request->has("idPosition") ? $datas = $datas->whereIn("bg_g.idPosition", explode(',',$request["idPosition"])) : "";
        $request->has("idCompany") ? $datas = $datas->whereIn("bg_g.idCompany", explode(',',$request["idCompany"])) : "";
        $request->has("idStaff") ? $datas = $datas->whereIn("bg_g.idStaff", explode(',',$request["idStaff"])) : "";
        $request->has("idUserAdd") ? $datas = $datas->whereIn("bg_g.idUserAdd", explode(',',$request["idUserAdd"])) : "";
        $request->has("idManageCheck") ? $datas = $datas->whereIn("bg_g.idManageCheck", explode(',',$request["idManageCheck"])) : ""; 
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        $datas = $datas->get();
        return response(["Status" => true,"datas" => $datas], 200);
    }
}
