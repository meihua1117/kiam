<?php
// header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";
// include_once $_SERVER['DOCUMENT_ROOT']."/lib/check_access_mms.php";

$userId = $_POST["id"];
$user_id = $_POST["user_id"];
$token = $_POST['mem_token'];
$phone_num = $_POST['phone_num'];
$result=0;

if(!$token || !check_token($phone_num, $token)){
    $token_res = 0;
}
else{
	$sql_chk = "select idx,result from Gn_MMS_Number where sendnum='{$userId}'";
	$res_chk = mysqli_query($self_con,$sql_chk);
	$cnt = mysqli_num_rows($res_chk);
	if(!$cnt || !$userId){
		$result=-1;
	}
	else{
		$send_mms_count = $_POST["num_mms"];
		$today_date=date("Y-m-d");
		$sql_mms="select idx from Gn_MMS where result=1 and send_num = '{$userId}' and reg_date like '$today_date%' order by idx desc limit 0,1";
		$sql_mms = "select idx from Gn_MMS where 1 and result > 0 and send_num = '{$userId}' and DATE(reg_date)='$today_date' and (now() < adddate(reg_date,INTERVAL 30 Minute))  and (reservation is null or reservation <= now()) limit 1";
		$resul_mms=mysqli_query($self_con,$sql_mms);
		$row_mms=mysqli_fetch_array($resul_mms);
		if($row_mms['idx'])
		$result=1;
		else
		{
			// $sql="select idx,result from Gn_MMS_Number where sendnum='{$userId}' ";
			// $resul=mysqli_query($self_con,$sql);
			$row=mysqli_fetch_array($res_chk);
			if($row['idx'])
			{
				$sql_u="update Gn_MMS_Number set month_cnt='{$send_mms_count}' where sendnum='{$userId }' ";
				mysqli_query($self_con,$sql_u);
				$result=$row['result'];	
			}		
		}
		
		$phone_num = $userId;
		if(strlen($phone_num) > 0)
		{
			$time = date("Y-m-d H:i:s");
			$sql="select idx from call_api_log where phone_num='$phone_num'";
			$res=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
			$row=mysqli_fetch_array($res);
			if($row['idx'] != "") {
				$sql="update call_api_log set check_task='$time' where idx='{$row['idx']}'";
				mysqli_query($self_con,$sql);	
			}
			else{
				$sql ="insert into call_api_log set check_task='$time', phone_num='$phone_num'";
				mysqli_query($self_con,$sql);	
			}
		}
	}

	$token_res = 1;
}

echo json_encode(array("result"=>$result,"token_res"=>$token_res));
?>