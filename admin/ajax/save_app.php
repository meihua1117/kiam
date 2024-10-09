<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

if(is_array($_FILES)) {
    if($_FILES['app-release']['name'] != "") {
        $uploadName = gcUpload($_FILES['app-release'],$_FILES["app-release"][tmp_name],$_FILES["app-release"][size], "app", "app-release.apk");    
    }
    if($_FILES['app_link1']['name'] != "") {
        $uploadName = gcUpload($_FILES['app_link1'],$_FILES["app_link1"][tmp_name],$_FILES["app_link1"][size], "app", "app_link1.apk");    
    }
    if($_FILES['app_link2']['name'] != "") {
        $uploadName = gcUpload($_FILES['app_link2'],$_FILES["app_link2"][tmp_name],$_FILES["app_link2"][size], "app", "app_link2.apk");    
    }
    if($_FILES['app_link3']['name'] != "") {
        $uploadName = gcUpload($_FILES['app_link3'],$_FILES["app_link3"][tmp_name],$_FILES["app_link3"][size], "app", "app_link3.apk");    
    }
    if($_FILES['app_link4']['name'] != "") {
        $uploadName = gcUpload($_FILES['app_link4'],$_FILES["app_link4"][tmp_name],$_FILES["app_link4"][size], "app", "app_link4.apk");    
    }                
    if($_FILES['dber']['name'] != "") {
        $uploadName = gcUpload($_FILES['dber'],$_FILES["dber"][tmp_name],$_FILES["dber"][size], "app", "db.exe");    
    }                    
}

echo "<script>alert('저장되었습니다');location='/admin/basic_config.php';</script>";
?>