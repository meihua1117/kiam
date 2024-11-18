<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/db_config.php";
//include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/common_func.php";
$host = explode(".", $HTTP_HOST);
$ip = $_SERVER['REMOTE_ADDR'];

$token = "";

//로그인 id pw
$userId = strtolower(trim($_REQUEST["id"]));
$userPW = trim($_REQUEST["pw"]);
$userNum = trim($_REQUEST["num"]);
$pkey = trim($_REQUEST["pkey"]);

// 앱 변수 추가 Cooper 2016.03.02
$telecom = strtoupper(trim($_REQUEST['telecom']));  // telecom / 통신사(USIM 정보 그대로 포워딩 없을경우 NO USIM)
$model = trim($_REQUEST['model']); // model / 단말기 모델
$ver = trim($_REQUEST['ver']); // ver / 앱버젼

$format_date = date("Y-m-d");
$query = "insert into app_test (mem_id, sendnum, regdate) values('$userId', '$userNum', NOW())";
mysqli_query($self_con, $query);

$check1 = sql_password($userPW);
$sql = "select mem_id,mem_phone from Gn_Member where mem_id ='$userId' and mem_leb in (21, 22, 50, 60) ";
$resul = mysqli_query($self_con, $sql);
$row = mysqli_fetch_array($resul);
if ($row['mem_id']) {
	$sql_pay = "select end_date from tjd_pay_result where end_status='Y' and buyer_id='$userId' order by no desc ";
	$res_pay = mysqli_query($self_con, $sql_pay);
	if (mysqli_num_rows($res_pay) == 0) {
		$sql_pay = "select now() as end_date";
		$res_pay = mysqli_query($self_con, $sql_pay);
	}
	$row_pay = mysqli_fetch_array($res_pay);

	$sql_p = "select mem_code, mem_id, site, site_iam from Gn_Member where mem_id = '$userId' and mem_pass = '$check1'";
	//echo $sql_p;
	$resul_p = mysqli_query($self_con, $sql_p);
	$row_p = mysqli_fetch_array($resul_p);
	if ($row_p['mem_id']) {

		$mem_code = $row_p['mem_code'];
		$site = $row_p['site'];
		$site_iam = $row_p['site_iam'];

		// Cooper Add 앱 입력정보 추가 2016.03.02
		if ($telecom != "") {
			if (strstr(strtoupper($telecom), "SK")) {
				$telecom = "SK";
			} else if (strstr(strtoupper($telecom), "KT") || strstr(strtoupper($telecom), "OLLEH")) {
				$telecom = "KT";
			} else if (strstr(strtoupper($telecom), "LG")) {
				$telecom = "LG";
			} else {
				$telecom = "HL";
			}
			$addQuery = ",memo2='$telecom',device='$model',app_ver='$ver' ";
		}
		$addQuery .= " ,act_time=NOW() "; // 작동 시간 추가
		$time = time();
		$sql_fujia_up = "update Gn_Member set fujia_date2=fujia_date1 where  unix_timestamp(fujia_date2) < $time and unix_timestamp(fujia_date2)<>'0' and mem_id='$user_id'";
		mysqli_query($self_con, $sql_fujia_up);

		$query = "select * from Gn_MMS_Number where mem_id = '$userId' and sendnum='$userNum'";
		$resul_c = mysqli_query($self_con, $query);
		$row_c = mysqli_fetch_array($resul_c);
		if ($row_c[0] != "") {
			$query = "update Gn_MMS_Number set pkey='$pkey' where sendnum='$userNum'";
			mysqli_query($self_con, $query);
		} else {
			$sql_in = "insert into Gn_MMS_Number set sendnum = '$userNum', pkey='$pkey', mem_id = '$userId', reg_date = now() , end_status='Y' , end_date='{$row_pay['end_date']}',format_date='{$format_date}' $addQuery"; //신규등록
			mysqli_query($self_con, $sql_in);
		}

		$sql_n_c = "select idx,mem_id,end_status,end_date from Gn_MMS_Number where sendnum='$userNum' limit 0,1 ";
		$resul_n_c = mysqli_query($self_con, $sql_n_c);
		$row_n_c = mysqli_fetch_array($resul_n_c);
		if ($row_n_c['idx']) {

			$sql_u = "update Gn_MMS_Number set reg_date=now() $addQuery where sendnum = '$userNum' and  mem_id = '$userId' ";
			mysqli_query($self_con, $sql_u);
			$result = "0"; //0이 로그인 성공					

		} else {
			if (str_replace("-", "", $row['mem_phone']) == $userNum) {
				$sql_in = "insert into Gn_MMS_Number set sendnum = '$userNum', mem_id = '$userId', end_status='M',reg_date = now(),end_date='{$row_pay['end_date']}',format_date='{$format_date}' $addQuery"; //신규등록
				mysqli_query($self_con, $sql_in);
				$result = "0"; //0이 로그인 성공						
			} else {

				$sql_in = "insert into Gn_MMS_Number set sendnum = '$userNum', mem_id = '$userId', reg_date = now() , end_status='Y' , end_date='{$row_pay['end_date']}',format_date='{$format_date}' $addQuery"; //신규등록
				mysqli_query($self_con, $sql_in);
				$result = "0"; //0이 로그인 성공				
			}
		}
		$phone_num = $userNum;
		if (strlen($phone_num) > 0) {
			$time = date("Y-m-d H:i:s");
			$sql = "select idx from call_api_log where phone_num='$phone_num'";
			$res = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
			$row = mysqli_fetch_array($res);
			if ($row['idx'] != "") {
				$sql = "update call_api_log set app_login='$time' where idx='{$row['idx']}'";
				mysqli_query($self_con, $sql);
			} else {
				$sql = "insert into call_api_log set app_login='$time', phone_num='$phone_num'";
				mysqli_query($self_con, $sql);
			}
		}
	} else
		$result = "1"; //패스워드 에러
} else
	$result = "2"; //없는 아이디

//로그인 입력확인용(임시)
// $sql00 ="insert into testtext (test) values ('".$result."//".$userNum."//".$userId."//".$userPW."//".sql_password($userPW)."')";
// mysqli_query($self_con,$sql00);

if ($result == "0") { //로그인 성공

	//로그인(앱설치) 기록용
	$sql = "insert into sm_app_loginout set mem_id='$userId', phone_num='$userNum', event_type='L' ,  reg_date=now()";
	$query2 = mysqli_query($self_con, $sql);

	$sql_log = "insert into gn_hist_login (domain,userid,position,ip,success,count) values('$host[0]', '$userId', 'mobile', '$ip', 'Y', count+1)";
	$res = mysqli_query($self_con, $sql_log);

	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$memToken = '';
	for ($i = 0; $i < 10; $i++) {
		$memToken .= $characters[rand(0, $charactersLength - 1)];
	}
	$sql_token_update = "update Gn_Member set mem_token='{$memToken}' where mem_id='{$userId}'";
	$res_token = mysqli_query($self_con, $sql_token_update);
	$sql_chk_num = "select token from gn_mms_token where phone_num='{$userNum}'";
	$res_chk_num = mysqli_query($self_con, $sql_chk_num);
	$cnt_num = mysqli_num_rows($res_chk_num);
	if ($cnt_num) {
		$sql_update = "update gn_mms_token set token='{$memToken}', reg_date=NOW() where phone_num='{$userNum}'";
		mysqli_query($self_con, $sql_update);
	} else {
		$sql_insert = "insert into gn_mms_token set mem_id='{$userId}', token='{$memToken}', phone_num='{$userNum}', reg_date=NOW()";
		mysqli_query($self_con, $sql_insert);
	}
}


echo json_encode(array("result" => $result, "mem_code" => $mem_code, "site" => $site, "site_iam" => $site_iam, "mem_token" => $memToken));
?>
