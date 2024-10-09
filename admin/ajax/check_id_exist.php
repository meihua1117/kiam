<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);
$mem_id = $_POST['mem_id'];
$sql = "select count(*) from Gn_Member where mem_id = '$mem_id'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
echo "{\"count\":\"$row[0]\"}";
?>