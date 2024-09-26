<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$cam_id = $_POST["cam_id"]; 

    $query="delete from Gn_Ad_Manager WHERE cam_id='$cam_id'
                                 ";
    mysqli_query($self_con, $query);	

echo "<script>alert('저장되었습니다.');location='/admin/ad_mo_list.php';</script>";
exit;
?>