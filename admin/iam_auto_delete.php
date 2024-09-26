<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 아이엠 삭제
*/
extract($_POST);
$memid = $_POST['memid'];
// 정보 확인
$sql="delete from Gn_Iam_automem where memid='$memid'";
$result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
echo json_encode(array("result"=>$result));
?>