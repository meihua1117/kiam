<?
header("Content-type:text/html;charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
$mem_id = $_POST['userid'];
$phone = $_POST['phone'];
$log = $_POST['log'];
$sql = "insert into app_debug_log set mem_id='{$mem_id}',phone_number='{$phone}',log='{$log}'";
mysqli_query($self_con,$sql);
echo json_encode(array("result"=>'success'));
?>
