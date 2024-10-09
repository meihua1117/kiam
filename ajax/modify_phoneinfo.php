<?
header("Content-Type: text/html; charset=UTF-8");
include_once "../lib/rlatjd_fun.php";

if($_SESSION[one_member_id]){

	$pno = substr(str_replace(array("-"," ",","),"",$_POST["pno"]),0,11);
	$name = substr($_POST["name"],0,20);
	$company = substr($_POST["company"],0,2);
	$rate = substr($_POST["rate"],0,3);

	if (strlen($pno) < 10){
		exit;
	}

	$sql_num="update Gn_MMS_Number set memo='$name',memo2='$company', donation_rate=$rate,daily_limit_cnt='500' where mem_id='$_SESSION[one_member_id]' and sendnum='$pno' ";

	mysql_query($sql_num);

}
mysql_close($self_con);
?>