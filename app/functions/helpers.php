<?php 
namespace App\functions;
use Illuminate\Support\Facades\DB;

class Helpers
{
    public function idOrderByKey($id,$table)
    {
        $data = DB::table($table)->select($id)->orderBy($id,'DESC')->first();
        $idKey = 1;
        if (!empty($data)) {
            $idKey += $data->$id;
        }
        return $idKey;
    }
    public function FormatDateSql($string) {
        if (!empty($string)) {
            date_default_timezone_set( 'Asia/Saigon' );
            $string = str_replace( " - ", " ", $string );
            $string = str_replace( "-", ".", $string );
            $string = str_replace( "/", ".", $string );
            $string = str_replace( ".", "-", $string );
            return date( "Y-m-d", strtotime( $string ) );
        }else{
            return NULL;
        }
    }
    public function FormatDateMonthYearSql($string) {
        if (!empty($string)) {
            date_default_timezone_set( 'Asia/Saigon' );
            $string = str_replace( " - ", " ", $string );
            $string = str_replace( "-", ".", $string );
            $string = str_replace( "/", ".", $string );
            $string = str_replace( ".", "-", $string );
            return date( "Y-m", strtotime( $string ) );
        }else{
            return NULL;
        }
    }
    public function FormatDate($string) {
        if (!empty($string)) {
            date_default_timezone_set( 'Asia/Saigon' );
            $string = str_replace( " - ", " ", $string );
            $string = str_replace( "-", ".", $string );
            $string = str_replace( "/", ".", $string );
            $string = str_replace( ".", "-", $string );
            return date( "d-m-Y", strtotime( $string ) );
        }else{
            return NULL;
        }
    }
    public function FormatDateMonthYear($string) {
        if (!empty($string)) {
            date_default_timezone_set( 'Asia/Saigon' );
            $string = str_replace( " - ", " ", $string );
            $string = str_replace( "-", ".", $string );
            $string = str_replace( "/", ".", $string );
            $string = str_replace( ".", "-", $string );
            return date( "m-Y", strtotime( $string ) );
        }else{
            return NULL;
        }
    }
    public function OrderByIDCode( $idKey, $tableName, $NumberLenght ) { //Hàm lấy mã IDCode của Table
        $Data = DB::table($tableName)->select(DB::raw('RIGHT('.$idKey.','.$NumberLenght.') AS '.$idKey.''))->orderBy($idKey,'DESC')->first();
        $Number = !empty($Data) ? $Data->$idKey : 0;
        settype( $Number, "int" );
        $Number = $Number+1;
		settype( $NumberLenght, "int" );
        $Lenght = strlen( $Number );
        $Zero = "";
		for ( $i = 0; $i < $NumberLenght - $Lenght; $i++ ) {
			$Zero .= 0;
		}
		return $Zero . $Number;
    }

    public function OrderByIDCodeMonthYear( $idKey, $tableName, $NumberLenght,$Date,$DateColum) { //Hàm lấy mã IDCode của Table
        $Data = DB::table($tableName)->select(DB::raw('RIGHT('.$idKey.','.$NumberLenght.') AS '.$idKey.''))
        ->whereDate($DateColum,\DB::raw("'".$this->FormatDateSql($Date)."'"))
        ->orderBy($idKey,'DESC')->first();
        $Number = !empty($Data) ? $Data->$idKey : 0;
        settype( $Number, "int" );
        $Number = $Number+1;
		settype( $NumberLenght, "int" );
        $Lenght = strlen( $Number );
        $Zero = "";
		for ( $i = 0; $i < $NumberLenght - $Lenght; $i++ ) {
			$Zero .= 0;
		}
		return $Zero . $Number;
    }
    public function getProvinces($idProvinces){
        $datas = DB::table('bgdata_provinces')->select("idProvinces","IDCode","Provinces")->where("Active",1)
        ->orderBy("idProvinces",'DESC');
        ($idProvinces != "") ? $datas = $datas->whereIn("idProvinces",explode(',',$idProvinces)) : "";
        $datas = $datas->get();
        return $datas;
    }

    public function getDistrict($idDistrict,$idProvinces){
        $datas = DB::table('bgdata_district')->select("idDistrict","IDCode","District")->where("Active",1)
        ->orderBy("idDistrict",'DESC');
        ($idDistrict != "") ? $datas = $datas->whereIn("idDistrict",explode(',',$idDistrict)) : "";
        ($idProvinces != "") ? $datas = $datas->whereIn("idProvinces",explode(',',$idProvinces)) : "";
        $datas = $datas->get();
        return $datas;
    }

    public function getCompany($idCompany){ // công ty
        $datas = DB::table('staff_company')->select("idCompany","IDCode","CompanyName")->where("Active",1)
        ->orderBy("idCompany",'DESC');
        ($idCompany != "") ? $datas = $datas->whereIn("idCompany",explode(',',$idCompany)) : "";
        $datas = $datas->get();
        return $datas;
    }

    public function getDepartment($idDepartment){ // phòng ban
        $datas = DB::table('staff_departments')->select("idDepartment","IDCode","DepartmentName")->where("Active",1)
        ->orderBy("idDepartment",'DESC');
        ($idDepartment != "") ? $datas = $datas->whereIn("idDepartment",explode(',',$idDepartment)) : "";
        $datas = $datas->get();
        return $datas;
    }

    public function getPosition($idPosition){ // vị trí
        $datas = DB::table('staff_positions')->select("idPosition","IDCode","PositionName")->where("Active",1)
        ->orderBy("idPosition",'DESC');
        ($idPosition != "") ? $datas = $datas->whereIn("idPosition",explode(',',$idPosition)) : "";
        $datas = $datas->get();
        return $datas;
    }

    public function getLevel($idLevel){ // trình độ
        $datas = DB::table('staff_levels')->select("idLevel","IDCode","LevelName")->where("Active",1)
        ->orderBy("idLevel",'DESC');
        ($idLevel != "") ? $datas = $datas->whereIn("idLevel",explode(',',$idLevel)) : "";
        $datas = $datas->get();
        return $datas;
    }

    public function getTypeOfWork($idTypeOfWork){ // loại công việc
        $datas = DB::table('staff_type_of_work')->select("idTypeOfWork","IDCode","TypeOfWorkName")->where("Active",1)
        ->orderBy("idTypeOfWork",'DESC');
        ($idTypeOfWork != "") ? $datas = $datas->whereIn("idTypeOfWork",explode(',',$idTypeOfWork)) : "";
        $datas = $datas->get();
        return $datas;
    }

    public function getGroup($idGroup){ // loại công việc
        $datas = DB::table('bgdata_group_permision')->select("idGroup","IDCode","GroupName")->where("Active",1)
        ->orderBy("idGroup",'DESC');
        ($idGroup != "") ? $datas = $datas->whereIn("idGroup",explode(',',$idGroup)) : "";
        $datas = $datas->get();
        return $datas;
    }
    //// phân quyền người dùng
    public function checkPermission($idUser,$Slug)
    {
        if ($idUser != 1) {
            $DataGroup = DB::table('staff_users_group')->select("idGroup")->where('idUser',$idUser)->get();
            $listGroup = [];
            $idPermission = [];
            $DataInfoPermission = [];
            if (count($DataGroup) > 0) {
                foreach ($DataGroup as $key => $Group) {
                    $listGroup[$key] = $Group->idGroup;
                }
                $PerGroup = DB::table('bgdata_decentral_function_group')
                ->select(DB::raw('GROUP_CONCAT(DISTINCT idPermission) AS idPermission'))
                ->where("Slug",$Slug)->where("Active",1)->first();
                if (!empty($PerGroup->idPermission)) {
                    $idPermission = array_unique(explode(",",$PerGroup->idPermission));
                    $DataList = DB::table('bgdata_listpermission')
                    ->select("idList","ListName","Icon")->whereIn('idList',$idPermission)->get();
                    
                    foreach ($DataList as $list) {
                        $DataInfoPermission[$list->idList] = $list;
                    }
                    foreach ($idPermission as $key => $value) {
                        settype($idPermission[$key],"int");
                    }
                }
            }
            return ["idPermission" => $idPermission,"DataInfoPermission" => $DataInfoPermission];
        }else{
            $idPermission = [];
            $DataInfoPermission = [];
            $DataGroup = DB::table('bgdata_listpermission')->select("idList","ListName","Icon")->where('Active',1)->get();
            if (count($DataGroup) > 0) {
                foreach ($DataGroup as $key => $Group) {
                    $idPermission[$key] = $Group->idList;
                    $DataInfoPermission[$Group->idList] = $Group;
                }
            }
            return ["idPermission" => $idPermission,"DataInfoPermission" => $DataInfoPermission];
        }
    }
    
    public function getUsers($idUser){ // nhân viên
        $datas = DB::table('staff_users')->select("idUser","IDCode","FullName")->where("Active",1)->where("idUser","<>",1)
        ->orderBy("idUser",'DESC');
        ($idUser != "") ? $datas = $datas->whereIn("idUser",explode(',',$idUser)) : "";
        $datas = $datas->get();

        if (count($datas) > 0) {
            foreach ($datas as $key => $Users) {
                $datas[$key]->IDCodeFullName = $Users->FullName . " (".$Users->IDCode.")";
            }
        }
        return $datas;
    }

    public function getAchievementsType($idAchievementsType){ // loại công việc
        $datas = DB::table('staff_achievements_type')->select("idAchievementsType","IDCode","AchievementsType")->where("Active",1)
        ->orderBy("idAchievementsType",'DESC');
        ($idAchievementsType != "") ? $datas = $datas->whereIn("idAchievementsType",explode(',',$idAchievementsType)) : "";
        $datas = $datas->get();
        return $datas;
    }
}

?>