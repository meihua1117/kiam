<?php
//header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

$userId = $_POST["id"];
$send_mms_count = $_POST["num_mms"];
$result=0;
$today_date=date("Y-m-d");
$sql_mms="select idx from Gn_MMS where result=1 and send_num = '{$userId}' and reg_date like '$today_date%' order by idx desc limit 0,1";
$sql_mms = "select idx from Gn_MMS where 1 and result > 0 and send_num = '{$userId}' and reg_date like '$today_date%' and (now() < adddate(reg_date,INTERVAL 30 Minute))  and (reservation is null or reservation <= now())  order by idx asc limit 1";
$resul_mms=mysql_query($sql_mms);
$row_mms=mysql_fetch_array($resul_mms);
if($row_mms[idx])
$result=1;
else
{
	$sql="select idx,result from Gn_MMS_Number where sendnum='{$userId}' ";
	$resul=mysql_query($sql);
	$row=mysql_fetch_array($resul);
	if($row[idx])
	{
		$sql_u="update Gn_MMS_Number set month_cnt='{$send_mms_count}' where sendnum='{$userId }' ";
		mysql_query($sql_u);
		$result=$row[result];	
	}		
}
$phone_num = $userId;
if(strlen($phone_num) > 0)
{
	$time = date("Y-m-d H:i:s");
	$sql="select idx from call_api_log where phone_num='$phone_num'";
	$res=mysql_query($sql) or die(mysql_error());
	$row=mysql_fetch_array($res);
	if($row['idx'] != "") {
		$sql="update call_api_log set check_task='$time' where idx='$row[idx]'";
		mysql_query($sql);	
	}
	else{
		$sql ="insert into call_api_log set check_task='$time', phone_num='$phone_num'";
		mysql_query($sql);	
	}
}
echo "{\"result\":\"$result\"}";
?>