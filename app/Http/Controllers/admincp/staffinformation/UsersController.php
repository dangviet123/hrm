<?php

namespace App\Http\Controllers\admincp\staffinformation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\functions\Helpers;
use Illuminate\Support\Facades\DB;
use App\admincp\staffinformation\Users;

class UsersController extends Controller
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
        $datas = DB::table('staff_users AS bg_g')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->leftJoin('staff_departments AS sd','sd.idDepartment', '=', 'bg_g.idDepartment');
        $request->has("idGroup") ? $datas = $datas->leftJoin('staff_users_group AS sg','sg.idUser', '=', 'bg_g.idUser') : "";
        $datas->select("bg_g.*","su.FullName AS FullNameAdd",DB::raw('false AS checked'),DB::raw('"" AS data_detail'),"sd.DepartmentName")
        ->orderBy($filed,$orderby);
        session()->get('session_user')->idUser !=1 ?  $datas = $datas->whereNotIn("bg_g.idUser", [1]) : '';
        // tìm kiếm
        $request->has("IDCode") ? $datas = $datas->where("bg_g.IDCode", "like", "%".$request["IDCode"]."%") : "";
        $request->has("Phone") ? $datas = $datas->where("bg_g.Phone", "like", "%".$request["Phone"]."%") : "";
        $request->has("FullName") ? $datas = $datas->where("bg_g.FullName", "like", "%".$request["FullName"]."%") : "";
        $request->has("Email") ? $datas = $datas->where("bg_g.Email", "like", "%".$request["Email"]."%") : "";
        $request->has("FullNameAdd") ? $datas = $datas->where("su.FullName", "like", "%".$request["FullNameAdd"]."%") : "";
        $request->has("Description") ? $datas = $datas->where("bg_g.Description", "like", "%".$request["Description"]."%") : "";
        $request->has("Birth") ? $datas = $datas->whereIn("bg_g.Birth", explode(',',$request["Birth"])) : "";
        $request->has("idLevel") ? $datas = $datas->whereIn("bg_g.idLevel", explode(',',$request["idLevel"])) : "";
        $request->has("idDepartment") ? $datas = $datas->whereIn("bg_g.idDepartment", explode(',',$request["idDepartment"])) : "";
        $request->has("idPosition") ? $datas = $datas->whereIn("bg_g.idPosition", explode(',',$request["idPosition"])) : "";
        $request->has("idTypeOfWork") ? $datas = $datas->whereIn("bg_g.idTypeOfWork", explode(',',$request["idTypeOfWork"])) : "";
        $request->has("idCompany") ? $datas = $datas->whereIn("bg_g.idCompany", explode(',',$request["idCompany"])) : "";
        $request->has("idGroup") ? $datas = $datas->whereIn("sg.idGroup", explode(',',$request["idGroup"])) : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_g.Active", explode(',',$request["Active"])) : "";
        $request->has("isWork") ? $datas = $datas->whereIn("bg_g.isWork", explode(',',$request["isWork"])) : "";
        $request->has("Sex") ? $datas = $datas->whereIn("bg_g.Sex", explode(',',$request["Sex"])) : "";
        
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        // end tìm kiếm

        $datas = $datas->paginate($limit);

        $DataBirth      = $helpers->getProvinces("");
        $DataCompany    = $helpers->getCompany("");
        $DataDepartment = $helpers->getDepartment("");
        $DataPosition   = $helpers->getPosition("");
        $DataLevel      = $helpers->getLevel("");
        $DataTypeOfWork = $helpers->getTypeOfWork("");
        $DataGroup =  $helpers->getGroup("");
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"users");

        return response([
            "Status"         => true,
            "datas"          => $datas,
            "orderby"        => $orderby,
            "filed"          => $filed,
            'limit'          => $limit,
            "DataBirth"      => $DataBirth,
            "DataCompany"    => $DataCompany,
            "DataDepartment" => $DataDepartment,
            "DataPosition"   => $DataPosition,
            "DataLevel"      => $DataLevel,
            "DataTypeOfWork" => $DataTypeOfWork,
            "DataGroup"      => $DataGroup,
            "Permission"     => $Permission["idPermission"],
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
        $helpers        = new Helpers();
        $IDCode         = "NV".$helpers->OrderByIDCode( 'IDCode', "staff_users", 5);
        $Provinces      = $helpers->getProvinces("");
        $DataCompany    = $helpers->getCompany("");
        $DataDepartment = $helpers->getDepartment("");
        $DataPosition   = $helpers->getPosition("");
        $DataLevel      = $helpers->getLevel("");
        $DataTypeOfWork = $helpers->getTypeOfWork("");
        $DataGroup =  $helpers->getGroup("");
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"users");
        return response([
            "Status"            => true,
            "IDCode"            => $IDCode,
            "DataIDcardAddress" => $Provinces,
            "DataBirth"         => $Provinces,
            "DataCompany"       => $DataCompany,
            "DataDepartment"    => $DataDepartment,
            "DataPosition"      => $DataPosition,
            "DataLevel"         => $DataLevel,
            "DataTypeOfWork"    => $DataTypeOfWork,
            "DataGroup" => $DataGroup,
            "Permission" => $Permission["idPermission"]
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
        $idUser = 0;
        $data = DB::table('staff_users')->select('idUser');
        $idUser > 0 ? $data = $data->where('idUser','<>',$idUser) : "";
        $data = $data->where('IDCode',$request["listGroup"]["IDCode"])->first();
        if (!empty($data)) {
            return response(["Status" => false,"Massages" => "Trường IDCode đã tồn tại trên hệ thống"], 200);
        }

        $dataEmail = DB::table('staff_users')->select('idUser');
        $idUser > 0 ? $dataEmail = $dataEmail->where('idUser','<>',$idUser) : "";
        $dataEmail = $dataEmail->where('Email',$request["listGroup"]["Email"])->first();
        if (!empty($dataEmail)) {
            return response(["Status" => false,"Massages" => "Trường Email đã tồn tại trên hệ thống"], 200);
        }

        $helpers = new Helpers();
        $idUser = $helpers->idOrderByKey("idUser","staff_users");
        $listGroup = new Users;
        $listGroup->idUser = $idUser;
        $listGroup->IDCode = $request["listGroup"]["IDCode"];
        $listGroup->FullName = $request["listGroup"]["FullName"];
        $listGroup->Email = $request["listGroup"]["Email"];
        $listGroup->Password = md5($request["listGroup"]["Password"]);
        $listGroup->Phone = $request["listGroup"]["Phone"];
        $listGroup->Birthday = $helpers->FormatDateSql($request["listGroup"]["Birthday"]);
        $listGroup->Birth = $request["listGroup"]["Birth"];
        $listGroup->IDcard = $request["listGroup"]["IDcard"];
        $listGroup->IDcardDate = $helpers->FormatDateSql($request["listGroup"]["IDcardDate"]);
        $listGroup->IDcardAddress = $request["listGroup"]["IDcardAddress"];
        $listGroup->Sex = $request["listGroup"]["Sex"];
        $listGroup->Married = $request["listGroup"]["Married"];
        $listGroup->Address = $request["listGroup"]["Address"];
        $listGroup->AddressStaying = $request["listGroup"]["AddressStaying"];
        $listGroup->Qualification = $request["listGroup"]["Qualification"];
        $listGroup->idDepartment = $request["listGroup"]["idDepartment"];
        $listGroup->idPosition = $request["listGroup"]["idPosition"];
        $listGroup->idTypeOfWork = $request["listGroup"]["idTypeOfWork"];
        $listGroup->idCompany = $request["listGroup"]["idCompany"];
        $listGroup->idLevel = $request["listGroup"]["idLevel"];
        $listGroup->DayProbationaryFrom = $helpers->FormatDateSql($request["listGroup"]["DayProbationary"][0]);
        $listGroup->DayProbationaryTo = $helpers->FormatDateSql($request["listGroup"]["DayProbationary"][1]);
        $listGroup->DayWork = $helpers->FormatDateSql($request["listGroup"]["DayWork"]);
        $listGroup->HealthInsuranceNo = $request["listGroup"]["HealthInsuranceNo"];
        $listGroup->BankName = $request["listGroup"]["BankName"];
        $listGroup->AccountNumber = $request["listGroup"]["AccountNumber"];
        $listGroup->Description = $request["listGroup"]["Description"];
        $listGroup->Active = $request["listGroup"]["Active"];
        $listGroup->isWork = $request["listGroup"]["isWork"];
        $listGroup->idUserAdd   = session()->get('session_user')->idUser;


        if ($request["listGroup"]["Image"] !="") {
            $ImageAv = explode(";", $request["listGroup"]["Image"]);
            $ImageAv1 = explode(",", $ImageAv[1]);
            $data = base64_decode($ImageAv1[1]);
            $NameImage = $request["listGroup"]["IDCode"]."-".uniqid().'.png';
            $imageName = public_path("images/users/").$NameImage;
            file_put_contents($imageName, $data);
        }else{
            $NameImage = '';
        }
        $listGroup->Image = $NameImage;
        if ($listGroup->save()) {
            $idGroup = $request["listGroup"]["idGroup"];
            if (!empty($idGroup)) {
                foreach ($idGroup as $Group) {
                    DB::table('staff_users_group')->insert(['idUser' => $idUser,'idGroup' => $Group]);
                }
            }

            $DataFiles = $request["DataFiles"];
            foreach ($DataFiles as $key => $file) {
                $key++;
                if ($file["FileNameBase64"] !="" || $file["Description"] !="") {
                    $ImageAv = explode(";", $file["FileNameBase64"]);
                    $ImageAv1 = explode(",", $ImageAv[1]);
                    $data = base64_decode($ImageAv1[1]);
                    $NameFile = $request["listGroup"]["IDCode"]."-".uniqid().'.'.$file["Type"];
                    $imageName = public_path("images/user_files/").$NameFile;
                    file_put_contents($imageName, $data);
                    DB::table('staff_users_file')->insert([
                        'idUser' => $idUser,
                        'FileNameBase64' => $NameFile,
                        'Description' => $file["Description"],
                        'Type' => $file["Type"],
                        'Size' => $file["Size"]
                    ]);
                }
            }
            return response(["Status" => true,"idUser" => $idUser], 200);
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
        $datas          = Users::find($id);
        $datas->Birthday = $helpers->FormatDate($datas->Birthday);
        $datas->IDcardDate = $helpers->FormatDate($datas->IDcardDate);
        $datas->DayWork = $helpers->FormatDate($datas->DayWork);
        $datas->DayWorkOff = $helpers->FormatDate($datas->DayWorkOff);
        $datas->DayProbationary = [$helpers->FormatDate($datas->DayProbationaryFrom),$helpers->FormatDate($datas->DayProbationaryTo)];
        $datas->Password = "";
        $datas->ImageOl = $datas->Image;
        $Provinces      = $helpers->getProvinces("");
        $DataCompany    = $helpers->getCompany("");
        $DataDepartment = $helpers->getDepartment("");
        $DataPosition   = $helpers->getPosition("");
        $DataLevel      = $helpers->getLevel("");
        $DataTypeOfWork = $helpers->getTypeOfWork("");
        $DataGroup =  $helpers->getGroup("");

        $DataFiles = DB::table('staff_users_file')->select("*","FileNameBase64 AS FileNameBase64Ol",DB::raw('false AS isChange'))->where('idUser',$id)->get();
        if (count($DataFiles) == 0) {
            $DataFiles = [["FileNameBase64" => "","Description" => "","Type" => "", "Size" => 0]];
        }

        $DataGroups = DB::table('staff_users_group')->select('idGroup')->where('idUser',$id)->get();
        if (count($DataGroups) > 0) {
           $arrGr = []; 
            foreach ($DataGroups as $key => $Gr) {
                $arrGr[$key] = $Gr->idGroup;
            }
            $datas->idGroup = $arrGr;
        }else{
            $datas->idGroup = "";
        }
        $Permission = $helpers->checkPermission(session()->get('session_user')->idUser,"users");
        return response([
            "Status" => true,
            "datas" => $datas,
            "DataIDcardAddress" => $Provinces,
            "DataBirth"         => $Provinces,
            "DataCompany"       => $DataCompany,
            "DataDepartment"    => $DataDepartment,
            "DataPosition"      => $DataPosition,
            "DataLevel"         => $DataLevel,
            "DataTypeOfWork"    => $DataTypeOfWork,
            "DataGroup"         => $DataGroup,
            "DataFiles"         => $DataFiles,
            "Permission"        => $Permission["idPermission"]
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
        $idUser = $request["listGroup"]["idUser"];
        $data = DB::table('staff_users')->select('idUser');
        $idUser > 0 ? $data = $data->where('idUser','<>',$idUser) : "";
        $data = $data->where('IDCode',$request["listGroup"]["IDCode"])->first();
        if (!empty($data)) {
            return response(["Status" => false,"Massages" => "Trường IDCode đã tồn tại trên hệ thống"], 200);
        }

        $dataEmail = DB::table('staff_users')->select('idUser');
        $idUser > 0 ? $dataEmail = $dataEmail->where('idUser','<>',$idUser) : "";
        $dataEmail = $dataEmail->where('Email',$request["listGroup"]["Email"])->first();
        if (!empty($dataEmail)) {
            return response(["Status" => false,"Massages" => "Trường Email đã tồn tại trên hệ thống"], 200);
        }
        $listGroup = Users::find($idUser);
        $listGroup->IDCode = $request["listGroup"]["IDCode"];
        $listGroup->FullName = $request["listGroup"]["FullName"];
        $listGroup->Email = $request["listGroup"]["Email"];
        ($request["listGroup"]["Password"] != "") ? $listGroup->Password = md5($request["listGroup"]["Password"]) : '';
        $listGroup->Phone = $request["listGroup"]["Phone"];
        $listGroup->Birthday = $helpers->FormatDateSql($request["listGroup"]["Birthday"]);
        $listGroup->Birth = $request["listGroup"]["Birth"];
        $listGroup->IDcard = $request["listGroup"]["IDcard"];
        $listGroup->IDcardDate = $helpers->FormatDateSql($request["listGroup"]["IDcardDate"]);
        $listGroup->IDcardAddress = $request["listGroup"]["IDcardAddress"];
        $listGroup->Sex = $request["listGroup"]["Sex"];
        $listGroup->Married = $request["listGroup"]["Married"];
        $listGroup->Address = $request["listGroup"]["Address"];
        $listGroup->AddressStaying = $request["listGroup"]["AddressStaying"];
        $listGroup->Qualification = $request["listGroup"]["Qualification"];
        $listGroup->idDepartment = $request["listGroup"]["idDepartment"];
        $listGroup->idPosition = $request["listGroup"]["idPosition"];
        $listGroup->idTypeOfWork = $request["listGroup"]["idTypeOfWork"];
        $listGroup->idCompany = $request["listGroup"]["idCompany"];
        $listGroup->idLevel = $request["listGroup"]["idLevel"];
        $listGroup->DayProbationaryFrom = $helpers->FormatDateSql($request["listGroup"]["DayProbationary"][0]);
        $listGroup->DayProbationaryTo = $helpers->FormatDateSql($request["listGroup"]["DayProbationary"][1]);
        $listGroup->DayWork = $helpers->FormatDateSql($request["listGroup"]["DayWork"]);
        $listGroup->BankName = $request["listGroup"]["BankName"];
        $listGroup->AccountNumber = $request["listGroup"]["AccountNumber"];
        $listGroup->HealthInsuranceNo = $request["listGroup"]["HealthInsuranceNo"];
        $listGroup->Description = $request["listGroup"]["Description"];
        $listGroup->Active = $request["listGroup"]["Active"];
        $listGroup->isWork = $request["listGroup"]["isWork"];
        $listGroup->idUserUpdate   = session()->get('session_user')->idUser;


        if ($request["listGroup"]["Image"] !="" && $request["listGroup"]["Image"] != $request["listGroup"]["ImageOl"]) {
            if (\File::exists(public_path("images/users/").$request["listGroup"]["ImageOl"])) {
                \File::delete(public_path("images/users/").$request["listGroup"]["ImageOl"]);
            }
            $ImageAv = explode(";", $request["listGroup"]["Image"]);
            $ImageAv1 = explode(",", $ImageAv[1]);
            $data = base64_decode($ImageAv1[1]);
            $NameImage = $request["listGroup"]["IDCode"]."-".uniqid().'.png';
            $imageName = public_path("images/users/").$NameImage;
            file_put_contents($imageName, $data);
            $listGroup->Image = $NameImage;
        }
        
        if ($listGroup->save()) {
            DB::table('staff_users_group')->where('idUser',$idUser)->delete();
            $idGroup = $request["listGroup"]["idGroup"];
            if (!empty($idGroup)) {
                foreach ($idGroup as $Group) {
                    DB::table('staff_users_group')->insert(['idUser' => $idUser,'idGroup' => $Group]);
                }
            }
            $DataFiles = $request["DataFiles"];
            foreach ($DataFiles as $key => $file) {
                $key++;
                if ($file["FileNameBase64"] !="" || $file["Description"] !="") {
                    if ((isset($file["idUser"]) && $file["idUser"] > 0) && (isset($file["idFile"]) && $file["idFile"] > 0)) {
                        if ($file["isChange"] == true) {
                            $path = public_path("images/user_files/").$file["FileNameBase64Ol"];
                            if (\File::exists($path)) {
                                \File::delete($path);
                            }
                            $ImageAv = explode(";", $file["FileNameBase64"]);
                            $ImageAv1 = explode(",", $ImageAv[1]);
                            $data = base64_decode($ImageAv1[1]);
                            $NameFile = $request["listGroup"]["IDCode"]."-".uniqid().'.'.$file["Type"];
                            $imageName = public_path("images/user_files/").$NameFile;
                            file_put_contents($imageName, $data);
                            DB::table('staff_users_file')->where('idFile',$file["idFile"])->update([
                                'idUser' => $idUser,
                                'FileNameBase64' => $NameFile,
                                'Description' => $file["Description"],
                                'Type' => $file["Type"],
                                'Size' => $file["Size"]
                            ]);
                        }else{
                            DB::table('staff_users_file')->where('idFile',$file["idFile"])->update([
                                'idUser' => $idUser,
                                'Description' => $file["Description"],
                                'Type' => $file["Type"],
                                'Size' => $file["Size"]
                            ]);
                        }
                    }else{
                        $ImageAv = explode(";", $file["FileNameBase64"]);
                        $ImageAv1 = explode(",", $ImageAv[1]);
                        $data = base64_decode($ImageAv1[1]);
                        $NameFile = $request["listGroup"]["IDCode"]."-".uniqid().'.'.$file["Type"];
                        $imageName = public_path("images/user_files/").$NameFile;
                        file_put_contents($imageName, $data);
                        DB::table('staff_users_file')->insert([
                            'idUser' => $idUser,
                            'FileNameBase64' => $NameFile,
                            'Description' => $file["Description"],
                            'Type' => $file["Type"],
                            'Size' => $file["Size"]
                        ]);
                    }
                }
            }
            return response(["Status" => true,"idUser" => $idUser], 200);
        }
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
        if (count($data_active) > 0) {
            foreach ($data_active as $idUser) {
                $users = DB::table('staff_users')->select("Image")->where("idUser",$idUser)->first();
                if ($users->Image != "") {
                    $path = public_path("images/users/").$users->Image;
                    if (\File::exists($path)) {
                        \File::delete($path);
                    }
                }
                $user_files = DB::table('staff_users_file')->select("FileNameBase64")->where("idUser",$idUser)->get();
                if (count($user_files) > 0) {
                    foreach ($user_files as $userf) {
                        $path = public_path("images/user_files/").$userf->FileNameBase64;
                        if (\File::exists($path)) {
                            \File::delete($path);
                        }
                    }
                }
                DB::table('staff_users')->where("idUser",$idUser)->delete();
                DB::table('staff_users_file')->where("idUser",$idUser)->delete();
                DB::table('staff_users_group')->where("idUser",$idUser)->delete();
            }
        }
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function checkIDCode(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $idUser = $request->has('idUser') ? $request->idUser : 0;
        $data = DB::table('staff_users')->select('idUser');
        $idUser > 0 ? $data = $data->where('idUser','<>',$idUser) : "";
        $data = $data->where('IDCode',$request->IDCode)->first();
        return response(["Status" => !empty($data) ? false : true], 200);
    }

    public function checkEmail(Request $request)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $idUser = $request->has('idUser') ? $request->idUser : 0;
        $data = DB::table('staff_users')->select('idUser');
        $idUser > 0 ? $data = $data->where('idUser','<>',$idUser) : "";
        $data = $data->where('Email',$request->Email)->first();
        return response(["Status" => !empty($data) ? false : true], 200);
    }
    public function activeStatusOne(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $idUser = $request->idUser;
        $Active = ($request->Active == 1) ? 0 : 1;
        $listper = Users::find($idUser);
        $listper->Active = $Active;
        $listper->save();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
        
    }
    public function activeStatusAll(Request $request){
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $data_active = $request->data_active;
        $Active = $request->Active;
        $listper = Users::whereIn('idUser', $data_active)
        ->update(['Active' => $Active]);
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }

    public function activeSisWorkOne(Request $request){ // tình trạng làm việc
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Today = gmdate("Y-m-d H:i:s", time() + 7 * 3600);
        $idUser = $request->idUser;
        $isWork = ($request->isWork == 1) ? 0 : 1;
        $DayWorkOff =  $isWork == 0 ? $Today : NULL; 
        $listper = Users::find($idUser);
        $listper->isWork = $isWork;
        $listper->DayWorkOff = $DayWorkOff;
        $listper->save();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
        
    }
    public function deleteListFile(Request $request, $idUser, $idFile)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $FileNameBase64 = $request->FileNameBase64;
        $path = public_path("images/user_files/").$FileNameBase64;
        if (\File::exists($path)) {
            \File::delete($path);
        }
        DB::table('staff_users_file')->where("idUser",$idUser)->where("idFile",$idFile)->delete();
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function deleteImage(Request $request, $idUser)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $Image = $request->Image;
        $path = public_path("images/users/").$Image;
        if (\File::exists($path)) {
            \File::delete($path);
        }
        DB::table('staff_users')->where("idUser",$idUser)->update(['Image' => ""]);
        return response(["Status" => true,"Massages" => "Cập nhật thành công"], 200);
    }
    public function loadingDataUser($idUser)
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $dataUser = DB::table('staff_users AS su')
        ->leftJoin('staff_company AS sc', 'sc.idCompany', '=', 'su.idCompany')
        ->leftJoin('staff_departments AS sd', 'sd.idDepartment', '=', 'su.idDepartment')
        ->leftJoin('staff_levels AS sl', 'sl.idLevel', '=', 'su.idLevel')
        ->leftJoin('staff_positions AS sp', 'sp.idPosition', '=', 'su.idPosition')
        ->leftJoin('staff_type_of_work AS sw', 'sw.idTypeOfWork', '=', 'su.idTypeOfWork')
        ->leftJoin('staff_users_group AS sgr', 'sgr.idUser', '=', 'su.idUser')
        ->leftJoin('bgdata_group_permision AS sg', 'sg.idGroup', '=', 'sgr.idGroup')
        ->leftJoin('bgdata_provinces AS bp', 'bp.idProvinces', '=', 'su.IDcardAddress')
        ->select("su.Phone","su.IDcard","su.IDcardDate","su.Address","su.AddressStaying","sc.CompanyName","sd.DepartmentName","sl.LevelName","sp.PositionName","sw.TypeOfWorkName",
        "su.Married","su.DayProbationaryFrom","su.DayProbationaryTo","su.DayWork","su.DayWorkOff","su.HealthInsuranceNo","su.AccountNumber","su.BankName",
        DB::raw('GROUP_CONCAT(DISTINCT sg.GroupName) AS GroupName'),"su.Qualification","bp.Provinces AS IDcardAddress")
        ->where('su.idUser',$idUser)
        ->first();
        return response(["Status" => true,"data_detail" => $dataUser], 200);
    }

    public function exportExcel(Request $request) // xuất excel
    {
        if (!session()->has('session_user')) {return response(["Status" => 2], 200);}
        $helpers = new Helpers();

        $orderby = $request->has("orderby") ? $request["orderby"] : "DESC";
        $filed   = $request->has("filed") ? $request["filed"] : "created_at";
        $datas = DB::table('staff_users AS bg_g')
        ->leftJoin('staff_users AS su', 'su.idUser', '=', 'bg_g.idUserAdd')
        ->leftJoin('staff_company AS sc', 'sc.idCompany', '=', 'bg_g.idCompany')
        ->leftJoin('staff_departments AS sd', 'sd.idDepartment', '=', 'bg_g.idDepartment')
        ->leftJoin('staff_levels AS sl', 'sl.idLevel', '=', 'bg_g.idLevel')
        ->leftJoin('staff_type_of_work AS sw', 'sw.idTypeOfWork', '=', 'bg_g.idTypeOfWork')
        ->leftJoin('staff_positions AS sp', 'sp.idPosition', '=', 'bg_g.idPosition')
        ->leftJoin('bgdata_provinces AS bir', 'bir.idProvinces', '=', 'bg_g.Birth')
        ->leftJoin('bgdata_provinces AS bp', 'bp.idProvinces', '=', 'bg_g.IDcardAddress');

        $request->has("idGroup") ? $datas = $datas->leftJoin('staff_users_group AS sg','sg.idUser', '=', 'bg_g.idUser') : "";
        $datas->select("bir.Provinces AS ProvincesBirth","bp.Provinces AS ProvincesIDcardAddress","sl.LevelName","sw.TypeOfWorkName","bg_g.*","su.IDCode AS FullNameAddIDCode","su.FullName AS FullNameAdd","sd.DepartmentName","sp.PositionName","sc.CompanyName","sc.IDCode AS CompanyNameIDCode")
        ->orderBy("bg_g.".$filed,$orderby);
        session()->get('session_user')->idUser !=1 ?  $datas = $datas->whereNotIn("bg_g.idUser", [1]) : '';
        // tìm kiếm
        $request->has("IDCode") ? $datas = $datas->where("bg_g.IDCode", "like", "%".$request["IDCode"]."%") : "";
        $request->has("Phone") ? $datas = $datas->where("bg_g.Phone", "like", "%".$request["Phone"]."%") : "";
        $request->has("FullName") ? $datas = $datas->where("bg_g.FullName", "like", "%".$request["FullName"]."%") : "";
        $request->has("Email") ? $datas = $datas->where("bg_g.Email", "like", "%".$request["Email"]."%") : "";
        $request->has("FullNameAdd") ? $datas = $datas->where("su.FullName", "like", "%".$request["FullNameAdd"]."%") : "";
        $request->has("Description") ? $datas = $datas->where("bg_g.Description", "like", "%".$request["Description"]."%") : "";
        $request->has("Birth") ? $datas = $datas->whereIn("bg_g.Birth", explode(',',$request["Birth"])) : "";
        $request->has("idLevel") ? $datas = $datas->whereIn("bg_g.idLevel", explode(',',$request["idLevel"])) : "";
        $request->has("idDepartment") ? $datas = $datas->whereIn("bg_g.idDepartment", explode(',',$request["idDepartment"])) : "";
        $request->has("idPosition") ? $datas = $datas->whereIn("bg_g.idPosition", explode(',',$request["idPosition"])) : "";
        $request->has("idTypeOfWork") ? $datas = $datas->whereIn("bg_g.idTypeOfWork", explode(',',$request["idTypeOfWork"])) : "";
        $request->has("idCompany") ? $datas = $datas->whereIn("bg_g.idCompany", explode(',',$request["idCompany"])) : "";
        $request->has("idGroup") ? $datas = $datas->whereIn("sg.idGroup", explode(',',$request["idGroup"])) : "";
        $request->has("Active") ? $datas = $datas->whereIn("bg_g.Active", explode(',',$request["Active"])) : "";
        $request->has("isWork") ? $datas = $datas->whereIn("bg_g.isWork", explode(',',$request["isWork"])) : "";
        $request->has("Sex") ? $datas = $datas->whereIn("bg_g.Sex", explode(',',$request["Sex"])) : "";
        
        $request->has("date_from") ? $datas = $datas->whereDate('bg_g.created_at','>=', \DB::raw("'".$helpers->FormatDateSql($request["date_from"])."'")) : "";
        $request->has("date_to") ? $datas = $datas->whereDate('bg_g.created_at','<=', \DB::raw("'".$helpers->FormatDateSql($request["date_to"])."'")) : "";
        $datas = $datas->get();
        return response(["Status" => true,"datas" => $datas], 200);
    }
}
