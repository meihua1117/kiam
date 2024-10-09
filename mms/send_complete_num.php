<?php
// header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
// include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
//include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";

$user_id = $_POST["user_id"];
$token = $_POST["mem_token"];
$phone_num = $_POST["phone_num"];

//if(!$token || !check_token($phone_num, $token)){
//	$result = 0;
//    $token_res = 0;
//}
//else{
	$token_res = 1;
	// request id 
	$reqid = $_POST["reqid"];
	$error = $_POST["error"];

	//$sql_err = "insert sm_log set dest = '".$error_r."', callback = '".$error_p."', msg_url = '".$error_g."' , mag_text = '".$error_s."', reservation_time = now()";
	//$query_err = sql_query($sql_err);
	$sql = "insert Gn_MMS_Result set suc_num = '{$snum}', fail_num = '{$error}', uni_id = '{$reqid}' , reg_date = now()";
	$query = mysql_query($sql);


	if($query){
		$result = 0;
	}else{
		$result = 1;
	}
//}



//echo "{\"result\":\"$result\",\"token_res\":\"$token_res\"}";
echo json_encode(array("result"=>$result,"token_res"=>$token_res));
?>