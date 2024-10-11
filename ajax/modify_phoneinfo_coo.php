<?
header("Content-Type: text/html; charset=UTF-8");
include_once "../lib/rlatjd_fun.php";
extract($_POST);
if($_SESSION[one_member_id]){

	$pno = substr(str_replace(array("-"," ",","),"",$_POST["pno"]),0,11);
	$name = substr($_POST["name"],0,20);
	$company = substr($_POST["company"],0,2);
	$rate = substr($_POST["rate"],0,3);

	if (strlen($pno) < 10){
		exit;
	}
	//$("#daily_limit_cnt").val(data.daily_limit_cnt);	
	//$("#daily_min_cnt").val(data.daily_min_cnt);	
	//$("#monthly_receive_cnt").val(data.monthly_receive_cnt);	
	//$("#daily_limit_cnt_user").val(data.daily_limit_cnt_user);	
	//$("#daily_min_cnt_user").val(data.daily_min_cnt_user);	
	//$("#monthly_receive_cnt_user").val(data.monthly_receive_cnt_user);	
	$sql_num="update Gn_MMS_Number set memo='$name',memo2='$company', daily_limit_cnt='500',daily_limit_cnt='$daily_limit_cnt', daily_min_cnt='$daily_min_cnt', monthly_receive_cnt='$monthly_receive_cnt', daily_limit_cnt_user='$daily_limit_cnt_user', daily_min_cnt_user='$daily_min_cnt_user',monthly_receive_cnt_user='$monthly_receive_cnt_user' where mem_id='$_SESSION[one_member_id]' and sendnum='$pno' ";

	mysqli_query($self_con,$sql_num);

}
mysqli_close($self_con);
?>