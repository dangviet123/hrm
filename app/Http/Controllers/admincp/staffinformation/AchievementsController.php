<?php

namespace App\Http\Controllers\admincp\staffinformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\functions\Helpers;
use Illuminate\Support\Facades\DB;
use App\admincp\staffinformation\Achievements;

class AchievementsController extends Controller
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
        $datas = DB::table('staff_achievements AS bg_g')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->leftJoin('staff_users AS skt', 'skt.idUser', '=', 'bg_g.idStaff')
        ->leftJoin('staff_achievements_type AS sat', 'sat.idAchievementsType', '=', 'bg_g.idAchievementsType')
        ->select("bg_g.*","su.FullName AS FullNameAdd","skt.FullName AS FullNameStaff",DB::raw('false AS checked'),"skt.IDCode AS IDCodeStaff",DB::raw('"" AS data_detail'),
        "sat.AchievementsType")
        ->orderBy($filed,$orderby);
        // tìm kiếm
        $request->has("IDCode") ? $datas = $datas->where("bg_g.IDCode", "like", "%".$request["IDCode"]."%") : "";
        $request->has("Achievements") ? $datas = $datas->where("bg_g.Achievements", "like", "%".$request["Achievements"]."%") : "";
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
        $request->has("idAchievementsType") ? $datas = $datas->whereIn("bg_g.idAchievementsType", explode(',',$request["idAchievementsType"])) : "";
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        // end tìm kiếm
        
        $datas = $datas->paginate($limit);

        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"achievements");

        $DataAchievementsType =  $helpers->getAchievementsType("");
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
            "DataAchievementsType" => $DataAchievementsType,
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
        $IDCode = "KT".$helpers->OrderByIDCode( 'IDCode', "staff_achievements", 5);
        $DataUsers =  $helpers->getUsers("");
        $DataCompany    = $helpers->getCompany("");
        $DataDepartment = $helpers->getDepartment("");
        $DataPosition   = $helpers->getPosition("");
        $DataAchievementsType =  $helpers->getAchievementsType("");
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"achievements");
        return response([
            "Status" => true,
            "IDCode" => $IDCode,
            "DataUsers" => $DataUsers,
            "Permission" => $Permission["idPermission"],
            "DataCompany" => $DataCompany,
            "DataDepartment" => $DataDepartment,
            "DataPosition" => $DataPosition,
            "DataAchievementsType" => $DataAchievementsType
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
        $listGroup = new Achievements;
        $listGroup->idAchievements = $helpers->idOrderByKey("idAchievements","staff_achievements");
        $listGroup->IDCode = "KT".$helpers->OrderByIDCode( 'IDCode', "staff_achievements", 5);
        $listGroup->Achievements = $request["listGroup"]["Achievements"];
        $listGroup->Notes = $request["listGroup"]["Notes"];
        $listGroup->idStaff = $request["listGroup"]["idStaff"];
        $listGroup->idAchievementsType = $request["listGroup"]["idAchievementsType"];
        $listGroup->Artifacts = $request["listGroup"]["Artifacts"];
        $listGroup->Bonus = $request["listGroup"]["Bonus"];
        $listGroup->BonusDay =  $helpers->FormatDateSql($request["listGroup"]["BonusDay"]);
        $listGroup->idCompany = $request["listGroup"]["idCompany"];
        $listGroup->idDepartment = $request["listGroup"]["idDepartment"];
        $listGroup->idPosition = $request["listGroup"]["idPosition"];
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
                $imageName = public_path("images/achievements/").$NameFile;
                file_put_contents($imageName, $data);
                DB::table('staff_achievements_file')->insert([
                    'idAchievements' => $listGroup->idAchievements,
                    'FileNameBase64' => $NameFile,
                    'Description' => $file["Description"],
                    'Type' => $file["Type"],
                    'Size' => $file["Size"]
                ]);
            }
        }
        $listGroup->save();
        return response(["Status" => true,"idAchievements" => $listGroup->idAchievements], 200);
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
        $helpers        = new Helpers();
        $datas          = Achievements::find($id);
        $datas->BonusDay = $helpers->FormatDate($datas->BonusDay);
        $DataUsers =  $helpers->getUsers("");
        $DataCompany    = $helpers->getCompany("");
        $DataDepartment = $helpers->getDepartment("");
        $DataPosition   = $helpers->getPosition("");
        $DataAchievementsType =  $helpers->getAchievementsType("");
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"achievements");

        $DataFiles = DB::table('staff_achievements_file')->select("*","FileNameBase64 AS FileNameBase64Ol",DB::raw('false AS isChange'))->where('idAchievements',$id)->get();
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
            "DataAchievementsType" => $DataAchievementsType,
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
        $idAchievements          = $request["listGroup"]["idAchievements"];
        $listGroup               = Achievements::find($idAchievements);
        $listGroup->Achievements = $request["listGroup"]["Achievements"];
        $listGroup->Notes = $request["listGroup"]["Notes"];
        $listGroup->idStaff = $request["listGroup"]["idStaff"];
        $listGroup->idAchievementsType = $request["listGroup"]["idAchievementsType"];
        $listGroup->Artifacts = $request["listGroup"]["Artifacts"];
        $listGroup->Bonus = $request["listGroup"]["Bonus"];
        $listGroup->BonusDay =  $helpers->FormatDateSql($request["listGroup"]["BonusDay"]);
        $listGroup->idCompany = $request["listGroup"]["idCompany"];
        $listGroup->idDepartment = $request["listGroup"]["idDepartment"];
        $listGroup->idPosition = $request["listGroup"]["idPosition"];
        $listGroup->idUserUpdate   = session()->get('session_user')->idUser;
        $DataFiles = $request["DataFiles"];
        foreach ($DataFiles as $key => $file) {
            $key++;
            if ($file["FileNameBase64"] !="" || $file["Description"] !="") {
                if ((isset($file["idAchievements"]) && $file["idAchievements"] > 0) && (isset($file["idAchievementsFile"]) && $file["idAchievementsFile"] > 0)) {
                    if ($file["isChange"] == true) {
                        $path = public_path("images/achievements/").$file["FileNameBase64Ol"];
                        if (\File::exists($path)) {
                            \File::delete($path);
                        }
                        $ImageAv = explode(";", $file["FileNameBase64"]);
                        $ImageAv1 = explode(",", $ImageAv[1]);
                        $data = base64_decode($ImageAv1[1]);
                        $NameFile = $request["listGroup"]["IDCode"]."-".uniqid().$key.'.'.$file["Type"];
                        $imageName = public_path("images/achievements/").$NameFile;
                        file_put_contents($imageName, $data);
                        DB::table('staff_achievements_file')->where('idAchievementsFile',$file["idAchievementsFile"])->update([
                            'idAchievements' => $idAchievements,
                            'FileNameBase64' => $NameFile,
                            'Description' => $file["Description"],
                            'Type' => $file["Type"],
                            'Size' => $file["Size"]
                        ]);
                    }else{
                        DB::table('staff_achievements_file')->where('idAchievementsFile',$file["idAchievementsFile"])->update([
                            'idAchievements' => $idAchievements,
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
                    $imageName = public_path("images/achievements/").$NameFile;
                    file_put_contents($imageName, $data);
                    DB::table('staff_achievements_file')->insert([
                        'idAchievements' => $idAchievements,
                        'FileNameBase64' => $NameFile,
                        'Description' => $file["Description"],
                        'Type' => $file["Type"],
                        'Size' => $file["Size"]
                    ]);
                }
            }
        }
        $listGroup->save();
        return response(["Status" => true,"idAchievements" => $idAchievements], 200);
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
        $achievements = DB::table('staff_achievements_file')->select("FileNameBase64")->whereIn("idAchievements",$data_active)->get();
        if (count($achievements) > 0) {
            foreach ($achievements as $ach) {
                $path = public_path("images/achievements/").$ach->FileNameBase64;
                if (\File::exists($path)) {
                    \File::delete($path);
                }
            }
        }
        DB::table('staff_achievements_file')->whereIn('idAchievements',$data_active)->delete();
        DB::table('staff_achievements')->whereIn('idAchievements',$data_active)->delete();
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
    public function activeStatusOne(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $idAchievements = $request->idAchievements;
        $Active = ($request->Active == 1) ? 0 : 1;
        if ($Active == 0) {
            $listper = Achievements::where("idAchievements",$idAchievements)->where("ManageCheck",0)->where("Locked",0)
            ->update(['Active' => $Active,"DateCandel" => $Today,"Locked" => 1,"idUserCandel" => session()->get('session_user')->idUser]);
        }else{
            $listper = Achievements::where("idAchievements",$idAchievements)->where("ManageCheck",0)->where("Locked",0)
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
            $listper = Achievements::whereIn('idAchievements', $data_active)->where("ManageCheck",0)->where("Locked",0)
        ->update(['Active' => $Active,"DateCandel" => $Today,"Locked" => 1,"idUserCandel" => session()->get('session_user')->idUser]);
        }else{
            $listper = Achievements::whereIn('idAchievements', $data_active)->where("ManageCheck",0)->where("Locked",0)
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
        $listper = Achievements::whereIn('idAchievements', $data_active)
        ->update(['Locked' => $Locked,"DateLocked" => $Today,"idUserLocked" => session()->get('session_user')->idUser]);
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }

    public function ManageCheckOne(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $idAchievements = $request->idAchievements;
        $ManageCheck = $request->ManageCheck;
        $Achievements = Achievements::where("idAchievements",$idAchievements)
        ->where("Active",1)
        ->where("Locked",0)
        ->where("ManageCheck",0)
        ->update([
            "ManageCheck" => $ManageCheck,
            "idManageCheck" => session()->get('session_user')->idUser,
            "DateManageCheck" => $Today,
            "Locked" => 1
        ]);
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function ManageCheckAll(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $data_active = $request->data_active;
        $ManageCheck = $request->ManageCheck;
        $Achievements = Achievements::whereIn("idAchievements",$data_active)
        ->where("Active",1)
        ->where("Locked",0)
        ->where("ManageCheck",0)
        ->update([
            "ManageCheck" => $ManageCheck,
            "idManageCheck" => session()->get('session_user')->idUser,
            "DateManageCheck" => $Today,
            "Locked" => 1
        ]);
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function LockedOne(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $idAchievements = $request->idAchievements;
        $Locked = $request->Locked;
        $listper = Achievements::find($idAchievements);
        $listper->Locked = $Locked;
        $listper->DateLocked = $Today;
        $listper->idUserLocked = session()->get('session_user')->idUser;
        $listper->save();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }

    public function getInfoAchievements($idAchievements)
    {
        $datas = DB::table('staff_achievements AS sa')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'sa.idUserAdd')
        ->leftJoin('staff_users AS suc', 'suc.idUser', '=', 'sa.idManageCheck')
        ->leftJoin('staff_company AS sc', 'sc.idCompany', '=', 'sa.idCompany')
        ->leftJoin('staff_departments AS sd', 'sd.idDepartment', '=', 'sa.idDepartment')
        ->leftJoin('staff_positions AS sp', 'sp.idPosition', '=', 'sa.idPosition')
        ->select("su.FullName AS FullNameAdd","sa.created_at","sc.CompanyName","sd.DepartmentName","sp.PositionName","sa.DateManageCheck","suc.FullName AS FullNameCheck",
        "sa.ManageCheck","sa.Artifacts")
        ->where("idAchievements",$idAchievements)
        ->first();
        return response(["Status" => true,"data_detail" => $datas], 200);
    }

    public function deleteListFile(Request $request,$idAchievements,$idAchievementsFile)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $FileNameBase64 = $request->FileNameBase64;
        $path = public_path("images/achievements/").$FileNameBase64;
        if (\File::exists($path)) {
            \File::delete($path);
        }
        DB::table('staff_achievements_file')->where("idAchievements",$idAchievements)->where("idAchievementsFile",$idAchievementsFile)->delete();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
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
        $datas = DB::table('staff_achievements AS bg_g')
        ->leftJoin('staff_users AS sff', 'sff.idUser', '=', 'bg_g.idStaff')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->leftJoin('staff_users AS suc', 'suc.idUser', '=', 'bg_g.idManageCheck')
        ->leftJoin('staff_company AS sc', 'sc.idCompany', '=', 'bg_g.idCompany')
        ->leftJoin('staff_departments AS sd', 'sd.idDepartment', '=', 'bg_g.idDepartment')
        ->leftJoin('staff_positions AS spn', 'spn.idPosition', '=', 'bg_g.idPosition')
        ->leftJoin('staff_achievements_type AS sat', 'sat.idAchievementsType', '=', 'bg_g.idAchievementsType')
        ->select("sff.IDCode AS FullNameSffIDCode","sff.FullName AS FullNameSff","spn.PositionName","bg_g.Achievements","bg_g.DateManageCheck","bg_g.Artifacts","bg_g.idManageCheck","bg_g.Notes","bg_g.IDCode","bg_g.Bonus","bg_g.BonusDay","bg_g.created_at","bg_g.Active","su.FullName AS FullNameAdd","su.IDCode AS FullNameAddIDCode","sc.IDCode AS CompanyNameIDCode","sc.CompanyName","sd.DepartmentName","bg_g.DateManageCheck","suc.IDCode AS FullNameCheckIDCode","suc.FullName AS FullNameCheck",
        "bg_g.ManageCheck","sat.AchievementsType")
        ->orderBy("bg_g.".$filed,$orderby);
        // tìm kiếm
        $request->has("IDCode") ? $datas = $datas->where("bg_g.IDCode", "like", "%".$request["IDCode"]."%") : "";
        $request->has("Achievements") ? $datas = $datas->where("bg_g.Achievements", "like", "%".$request["Achievements"]."%") : "";
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
        $request->has("idAchievementsType") ? $datas = $datas->whereIn("bg_g.idAchievementsType", explode(',',$request["idAchievementsType"])) : "";
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        $datas = $datas->get();
        return response(["Status" => true,"datas" => $datas], 200);
    }
}
