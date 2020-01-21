<?php

namespace App\Http\Controllers\admincp\salarys;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\functions\Helpers;
use Illuminate\Support\Facades\DB;
use App\admincp\salarys\Calculated;

class CalculatedController extends Controller
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
        $datas = DB::table('salarys_calculated AS bg_g')
        ->leftJoin('staff_users AS skt', 'skt.idUser', '=', 'bg_g.idUserSalary')
        ->select("bg_g.idCalculated","bg_g.created_at","bg_g.IDCode","bg_g.IDCodeUser","bg_g.idUserSalary","bg_g.Email","bg_g.MonthlySalary","bg_g.TotalSalaryDataAVG","bg_g.Active","bg_g.ManageCheck","bg_g.Locked","skt.FullName AS FullNameStaff",DB::raw('false AS checked'),"skt.IDCode AS IDCodeStaff",DB::raw('"" AS data_detail'))
        ->orderBy($filed,$orderby);
        // tìm kiếm
        $request->has("IDCode") ? $datas = $datas->where("bg_g.IDCode", "like", "%".$request["IDCode"]."%") : "";
        $request->has("Calculated") ? $datas = $datas->where("bg_g.Calculated", "like", "%".$request["Calculated"]."%") : "";
        $request->has("Notes") ? $datas = $datas->where("bg_g.Notes", "like", "%".$request["Notes"]."%") : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_g.Active", explode(',',$request["Active"])) : "";
        $request->has("Locked") ? $datas = $datas->whereIn("bg_g.Locked", explode(',',$request["Locked"])) : "";
        $request->has("ManageCheck") ? $datas = $datas->whereIn("bg_g.ManageCheck", explode(',',$request["ManageCheck"])) : "";
        $request->has("idDepartment") ? $datas = $datas->whereIn("bg_g.idDepartment", explode(',',$request["idDepartment"])) : "";
        $request->has("idPosition") ? $datas = $datas->whereIn("bg_g.idPosition", explode(',',$request["idPosition"])) : "";
        $request->has("idCompany") ? $datas = $datas->whereIn("bg_g.idCompany", explode(',',$request["idCompany"])) : "";
        $request->has("idUserSalary") ? $datas = $datas->whereIn("bg_g.idUserSalary", explode(',',$request["idUserSalary"])) : "";
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        $request->has("MonthlySalary") ? $datas = $datas->whereDate('bg_g.MonthlySalary', \DB::raw("'".$helpers->FormatDateSql('01-'.$request["MonthlySalary"])."'")) : "";
        // end tìm kiếm
        
        $datas = $datas->paginate($limit);

        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"calculated");

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
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $helpers = new Helpers();
        $IDCode = "L".gmdate("m").gmdate("Y") ."-".$helpers->OrderByIDCodeMonthYear( 'IDCode', "salarys_calculated", 5,$Today,"MonthlySalary");
        $DataUsers =  $helpers->getUsers("");
        $DataCompany    = $helpers->getCompany("");
        $DataDepartment = $helpers->getDepartment("");
        $DataPosition   = $helpers->getPosition("");
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"calculated");
        return response([
            "Status" => true,
            "IDCode" => $IDCode,
            "DataUsers" => $DataUsers,
            "Permission" => $Permission["idPermission"],
            "DataCompany" => $DataCompany,
            "DataDepartment" => $DataDepartment,
            "DataPosition" => $DataPosition,
            "MonthlySalary" => gmdate("m-Y", time() + 7 * 3600)
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
        $listGroup = new Calculated;
        $DateCheck = explode("-",$helpers->FormatDateSql("01-".$request["listGroup"]["MonthlySalary"]));
        $IDCode = "L".$DateCheck[1].$DateCheck[0] ."-".$helpers->OrderByIDCodeMonthYear( 'IDCode', "salarys_calculated", 5,"01-".$request["listGroup"]["MonthlySalary"],"MonthlySalary");
        $listGroup->idCalculated = $helpers->idOrderByKey("idCalculated","salarys_calculated");
        $listGroup->IDCode = $IDCode;
        $listGroup->Calculated = $request["listGroup"]["Calculated"];
        $listGroup->SalaryDataA = json_encode($request["DataSalaryDataA"]);
        $listGroup->SalaryDataB = json_encode($request["DataSalaryDataB"]);
        $listGroup->TotalSalaryDataA = $request["listGroup"]["TotalSalaryDataA"];
        $listGroup->TotalSalaryDataB = $request["listGroup"]["TotalSalaryDataB"];
        $listGroup->TotalSalaryDataAVG = $request["listGroup"]["TotalSalaryDataAVG"];
        $listGroup->NumberWorkdays = $request["listGroup"]["NumberWorkdays"];
        $listGroup->Notes = $request["listGroup"]["Notes"];
        $listGroup->idUserSalary = $request["listGroup"]["idUserSalary"];
        $listGroup->Email = $request["listGroup"]["Email"];
        $listGroup->IDCodeUser = $request["listGroup"]["IDCodeUser"];
        $listGroup->MonthlySalary = $helpers->FormatDateSql("01-".$request["listGroup"]["MonthlySalary"]);
        $listGroup->idDepartment = $request["listGroup"]["idDepartment"];   
        $listGroup->idPosition = $request["listGroup"]["idPosition"];
        $listGroup->idCompany = $request["listGroup"]["idCompany"];
        $listGroup->TotalSalaryWords = $request["listGroup"]["TotalSalaryWords"];
        $listGroup->Active = $request["listGroup"]["Active"];
        $listGroup->idUserAdd   = session()->get('session_user')->idUser;
        $listGroup->save();
        return response(["Status" => true,"idCalculated" => $listGroup->idCalculated], 200);
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
        $datas = Calculated::find($id);
        $helpers = new Helpers();
        $datas->MonthlySalary =   $helpers->FormatDateMonthYear($datas->MonthlySalary);

        $datas->SalaryDataA = json_decode($datas->SalaryDataA);
        $datas->SalaryDataB = json_decode($datas->SalaryDataB);
        $DataUsers =  $helpers->getUsers("");
        $DataCompany    = $helpers->getCompany("");
        $DataDepartment = $helpers->getDepartment("");
        $DataPosition   = $helpers->getPosition("");
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"calculated");
        return response([
            "Status" => true,
            "datas" => $datas,
            "DataUsers" => $DataUsers,
            "Permission" => $Permission["idPermission"],
            "DataCompany" => $DataCompany,
            "DataDepartment" => $DataDepartment,
            "DataPosition" => $DataPosition,
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
        $idCalculated          = $request["listGroup"]["idCalculated"];
        $listGroup               = Calculated::find($idCalculated);
        $listGroup->Calculated = $request["listGroup"]["Calculated"];
        $listGroup->SalaryDataA = json_encode($request["DataSalaryDataA"]);
        $listGroup->SalaryDataB = json_encode($request["DataSalaryDataB"]);
        $listGroup->TotalSalaryDataA = $request["listGroup"]["TotalSalaryDataA"];
        $listGroup->TotalSalaryDataB = $request["listGroup"]["TotalSalaryDataB"];
        $listGroup->TotalSalaryDataAVG = $request["listGroup"]["TotalSalaryDataAVG"];
        $listGroup->NumberWorkdays = $request["listGroup"]["NumberWorkdays"];
        $listGroup->Notes = $request["listGroup"]["Notes"];
        $listGroup->idUserSalary = $request["listGroup"]["idUserSalary"];
        $listGroup->Email = $request["listGroup"]["Email"];
        $listGroup->IDCodeUser = $request["listGroup"]["IDCodeUser"];
        $listGroup->idDepartment = $request["listGroup"]["idDepartment"];   
        $listGroup->idPosition = $request["listGroup"]["idPosition"];
        $listGroup->idCompany = $request["listGroup"]["idCompany"];
        $listGroup->TotalSalaryWords = $request["listGroup"]["TotalSalaryWords"];
        $listGroup->Active = $request["listGroup"]["Active"];
        $listGroup->idUserUpdate   = session()->get('session_user')->idUser;
        $listGroup->save();
        return response(["Status" => true,"idCalculated" => $idCalculated], 200);
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
        DB::table('salarys_calculated')->whereIn('idCalculated',$data_active)->where("Locked",0)->delete();
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

    public function loadInfoUser(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $idUserSalary = $request->idUserSalary;
        $idCompany = $request->idCompany;
        $UserInfo = DB::table('staff_users')->select("idCompany","idDepartment","idPosition","IDCode","FullName","Email","IDCode")
        ->where("idUser",$idUserSalary)->first();
        return response(["Status" => true,"UserInfo" => $UserInfo], 200);
    }
    public function getIDCodeMonthYear(Request $request)
    {
        $MonthlySalary	 = "01-".$request->MonthlySalary;

        $helpers = new Helpers();
        $DateCheck = explode("-",$helpers->FormatDateSql($MonthlySalary));
        $IDCode = "L".$DateCheck[1].$DateCheck[0] ."-".$helpers->OrderByIDCodeMonthYear( 'IDCode', "salarys_calculated", 5,$MonthlySalary,"MonthlySalary");
        return response(["Status" => true,"IDCode" => $IDCode,], 200);
    }

    public function loadingDataDetail($idCalculated)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $DataDetail = DB::table('salarys_calculated AS sca')
        ->leftJoin("staff_users AS su", 'su.idUser', '=', 'sca.idUserAdd')
        ->leftJoin("staff_users AS suc", 'suc.idUser', '=', 'sca.idManageCheck')
        ->leftJoin('staff_company AS sc', 'sc.idCompany', '=', 'sca.idCompany')
        ->leftJoin('staff_departments AS sd', 'sd.idDepartment', '=', 'sca.idDepartment')
        ->leftJoin('staff_positions AS sp', 'sp.idPosition', '=', 'sca.idPosition')
        ->select("sca.Calculated","sca.TotalSalaryDataA","sca.TotalSalaryDataB","sca.TotalSalaryDataAVG","sca.TotalSalaryWords","sca.NumberWorkdays","sca.Notes","sca.idUserSalary","sca.Email","sca.IDCodeUser",
        "sca.isSendEmail","sca.isEmailCheck","sca.DateCandel","sca.created_at","sca.ManageCheck","sca.DateManageCheck","sc.CompanyName","sd.DepartmentName","sp.PositionName","su.FullName AS FullNameAdd",
        "suc.FullName AS FullNameManageCheck")
        ->where('sca.idCalculated',$idCalculated)
        ->first();
        return response(["Status" => true,"data_detail" => $DataDetail], 200);
    }

    public function activeStatusOne(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $idCalculated = $request->idCalculated;
        $Active = ($request->Active == 1) ? 0 : 1;
        if ($Active == 0) {
            $listper = Calculated::where("idCalculated",$idCalculated)->where("ManageCheck",0)->where("Locked",0)
            ->update(['Active' => $Active,"DateCandel" => $Today,"Locked" => 1,"idUserCandel" => session()->get('session_user')->idUser]);
        }else{
            $listper = Calculated::where("idCalculated",$idCalculated)->where("ManageCheck",0)->where("Locked",0)
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
            $listper = Calculated::whereIn('idCalculated', $data_active)->where("ManageCheck",0)->where("Locked",0)
        ->update(['Active' => $Active,"DateCandel" => $Today,"Locked" => 1,"idUserCandel" => session()->get('session_user')->idUser]);
        }else{
            $listper = Calculated::whereIn('idCalculated', $data_active)->where("ManageCheck",0)->where("Locked",0)
        ->update(['Active' => $Active,"DateCandel" => $Today,"idUserCandel" => session()->get('session_user')->idUser]);
        }
        
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function LockedOne(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $idCalculated = $request->idCalculated;
        $Locked = $request->Locked;
        $listper = Calculated::find($idCalculated);
        $listper->Locked = $Locked;
        $listper->DateLocked = $Today;
        $listper->idUserLocked = session()->get('session_user')->idUser;
        $listper->save();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }

    public function ManageCheckOne(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $idCalculated = $request->idCalculated;
        $ManageCheck = $request->ManageCheck;
        $Calculated = Calculated::where("idCalculated",$idCalculated)
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
        $Calculated = Calculated::whereIn("idCalculated",$data_active)
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
    public function viewPrint($idCalculated)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}

        $DataDetail = DB::table('salarys_calculated AS sca')
        ->leftJoin("staff_users AS su", 'su.idUser', '=', 'sca.idUserSalary')
        ->leftJoin('staff_company AS sc', 'sc.idCompany', '=', 'sca.idCompany')
        ->leftJoin('staff_departments AS sd', 'sd.idDepartment', '=', 'sca.idDepartment')
        ->leftJoin('staff_positions AS sp', 'sp.idPosition', '=', 'sca.idPosition')
        ->select("sca.IDCode","sca.TotalSalaryDataA","sca.TotalSalaryDataB","sca.TotalSalaryDataAVG","sca.TotalSalaryWords","sca.NumberWorkdays","sca.idUserSalary","sca.Email","sca.IDCodeUser",
        "sc.CompanyName","sd.DepartmentName","sp.PositionName","su.FullName","sca.SalaryDataA","sca.SalaryDataB","sca.MonthlySalary")
        ->where('sca.idCalculated',$idCalculated)
        ->first();
        $DateCheck = explode("-",$DataDetail->MonthlySalary);
        $DataDetail->SalaryDataA = json_decode($DataDetail->SalaryDataA);
        $DataDetail->SalaryDataB = json_decode($DataDetail->SalaryDataB);
        $DataDetail->Month = $DateCheck[1];
        $DataDetail->Year = $DateCheck[0];
        return response(["Status" => true,"DataDetail" => $DataDetail], 200);
    }

    public function exportExcel(Request $request) // xuất excel
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $helpers = new Helpers();

        $orderby = $request->has("orderby") ? $request["orderby"] : "DESC";
        $filed   = $request->has("filed") ? $request["filed"] : "created_at";
        $datas = DB::table('salarys_calculated AS bg_g')
        ->leftJoin('staff_users AS sff', 'sff.idUser', '=', 'bg_g.idUserSalary')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->leftJoin('staff_users AS suc', 'suc.idUser', '=', 'bg_g.idManageCheck')
        ->leftJoin('staff_company AS sc', 'sc.idCompany', '=', 'bg_g.idCompany')
        ->leftJoin('staff_departments AS sd', 'sd.idDepartment', '=', 'bg_g.idDepartment')
        ->leftJoin('staff_positions AS spn', 'spn.idPosition', '=', 'bg_g.idPosition')
        ->select("spn.PositionName","sd.DepartmentName","bg_g.idCalculated","bg_g.Calculated","bg_g.TotalSalaryDataA","bg_g.NumberWorkdays","bg_g.TotalSalaryDataB","bg_g.TotalSalaryWords","bg_g.created_at","bg_g.IDCode","bg_g.IDCodeUser","bg_g.idUserSalary","bg_g.Email","bg_g.MonthlySalary","bg_g.TotalSalaryDataAVG","bg_g.Notes","bg_g.Active","bg_g.ManageCheck","sff.IDCode AS FullNameSffIDCode","sff.FullName AS FullNameSff","bg_g.DateManageCheck","suc.FullName AS FullNameCheck","suc.IDCode AS FullNameCheckIDCode","su.FullName AS FullNameAdd","su.IDCode AS FullNameAddIDCode","sc.IDCode AS CompanyNameIDCode","sc.CompanyName")
        ->orderBy("bg_g.".$filed,$orderby);
        // tìm kiếm
        $request->has("IDCode") ? $datas = $datas->where("bg_g.IDCode", "like", "%".$request["IDCode"]."%") : "";
        $request->has("Calculated") ? $datas = $datas->where("bg_g.Calculated", "like", "%".$request["Calculated"]."%") : "";
        $request->has("Notes") ? $datas = $datas->where("bg_g.Notes", "like", "%".$request["Notes"]."%") : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_g.Active", explode(',',$request["Active"])) : "";
        $request->has("Locked") ? $datas = $datas->whereIn("bg_g.Locked", explode(',',$request["Locked"])) : "";
        $request->has("ManageCheck") ? $datas = $datas->whereIn("bg_g.ManageCheck", explode(',',$request["ManageCheck"])) : "";
        $request->has("idDepartment") ? $datas = $datas->whereIn("bg_g.idDepartment", explode(',',$request["idDepartment"])) : "";
        $request->has("idPosition") ? $datas = $datas->whereIn("bg_g.idPosition", explode(',',$request["idPosition"])) : "";
        $request->has("idCompany") ? $datas = $datas->whereIn("bg_g.idCompany", explode(',',$request["idCompany"])) : "";
        $request->has("idUserSalary") ? $datas = $datas->whereIn("bg_g.idUserSalary", explode(',',$request["idUserSalary"])) : "";
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        $request->has("MonthlySalary") ? $datas = $datas->whereDate('bg_g.MonthlySalary', \DB::raw("'".$helpers->FormatDateSql('01-'.$request["MonthlySalary"])."'")) : "";
        $datas = $datas->get();
        return response(["Status" => true,"datas" => $datas], 200);
    }
    
}
