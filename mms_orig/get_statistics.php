<?
// header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
// include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";

// PARAM
$id = $_POST["id"];
$id = $_REQUEST["id"];

$reserv_cnt_thismonth=0;
$reserv_cnt_thisday=0;

// SET
$date_today=date("Y-m-d");
$date_month=date("Y-m");

// QUERY
$sql="select SUM(recv_num_cnt) as cnt from Gn_MMS where 1=1 and send_num='$id' and reg_date like '$date_today%' ";
// $sql="select recv_num from Gn_MMS where 1=1 and send_num='$id' and reg_date like '$date_today%' and result=0 and up_date!='' ";
$sub_resul=mysql_query($sql);	
$row_result = mysql_fetch_array($sub_resul);
// while($row_result = mysql_fetch_array($sub_resul))
// $reserv_cnt_thisday += count(explode(",",$row_result[recv_num]));
$reserv_cnt_thisday += $row_result[0] * 1;

// QUERY
$sql="select SUM(recv_num_cnt) as cnt from Gn_MMS where 1=1 and send_num='$id' and reg_date like '$date_month%' ";
// $sql="select recv_num from Gn_MMS where 1=1 and send_num='$id' and reg_date like '$date_month%' and result=0 and up_date!='' ";
$sub_resul=mysql_query($sql);				
$row_result = mysql_fetch_array($sub_resul);
// while($row_result = mysql_fetch_array($sub_resul))
// $reserv_cnt_thismonth += count(explode(",",$row_result[recv_num]));
$reserv_cnt_thismonth += $row_result[0] * 1;

$phone_num = $id;
if(strlen($phone_num) > 0)
{
	$time = date("Y-m-d H:i:s");
	$sql="select idx from call_api_log where phone_num='$phone_num'";
	$res=mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_array($res);
	if($row['idx'] != "") {
		$sql="update call_api_log set get_statistics='$time' where idx='$row[idx]'";
		mysql_query($sql);	
	}
	else{
		$sql ="insert into call_api_log set get_statistics='$time', phone_num='$phone_num'";
		mysql_query($sql);	
	}
}

echo "{\"today\":\"$reserv_cnt_thisday\",\"month\":\"$reserv_cnt_thismonth\"}";
?>