<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment :  코치정보 수정
*/
extract($_POST);

$coach_no = $_POST["coach_no"]; 



$query="delete from Gn_Ad_Manager WHERE cam_id='$cam_id'";


$query="update coach_apply set agreement='1' where no='$coach_no' ";

    mysqli_query($self_con, $query);  

//echo "<script>alert('저장되었습니다."+$coach_no+"');location='/admin/member_manager_request_coach.php';</script>";
exit;
?>