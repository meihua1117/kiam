<?
include_once $path."lib/rlatjd_fun.php";
extract($_POST);
$mem_id = $_POST["mem_id"];
$solution_type = $_POST["solution_type"];
$solution_name = $_POST["solution_name"];
$sql = "update Gn_Member set ".$solution_type."='$solution_name' where mem_id = '$mem_id'";
mysqli_query($self_con,$sql);
echo "success";
?>
