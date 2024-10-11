<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
$query = "update  gn_conf set phone_id  ='$phone_id',phone_num ='$phone_num',web_phone ='$web_phone', regdate=NOW()";
mysqli_query($self_con,$query);
echo "<script>alert('저장되었습니다');location='/admin/admin_config.php';</script>";

?>