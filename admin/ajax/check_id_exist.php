<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);
$mem_id = $_POST['mem_id'];
$sql = "select count(*) from Gn_Member where mem_id = '$mem_id'";
$result = mysqli_query($self_con, $sql);
$row = mysqli_fetch_array($result);
echo json_encode(array("count"=>$row[0]));
?>