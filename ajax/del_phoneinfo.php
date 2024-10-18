<?
include_once "../lib/rlatjd_fun.php";
if ($_SESSION['one_member_id']) {
	$pno = substr(str_replace(array("-", " ", ","), "", $_POST["pno"]), 0, 11);
	if (strlen($pno) < 10) {
		exit;
	}
	$sql_num = "delete from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' and sendnum='$pno' ";
	mysqli_query($self_con, $sql_num);
}
mysqli_close($self_con);
?>