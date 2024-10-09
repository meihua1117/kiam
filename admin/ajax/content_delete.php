<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$idx = $_POST["idx"]; 
$query="delete from Gn_Iam_Contents WHERE idx='$idx'";
mysql_query($query);
$sql = "delete from Gn_Iam_Con_Card where cont_idx = $idx";
mysql_query($sql);
$sql="delete from Gn_Iam_Mall where card_idx = '$idx' and (mall_type = 3 or mall_type = 4)";
mysql_query($sql) or die(mysql_error());
?>