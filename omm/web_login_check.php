<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/db_config.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/common_func.php";
$phoneNumber = trim($_REQUEST["num"]);
$memID = $_REQUEST["id"];
$token = trim($_REQUEST["token"]);
$telecome = trim($_REQUEST["telecom"]);
$model = trim($_REQUEST["model"]);
$ver = trim($_REQUEST['ver']); // ver / 앱버젼
$format_date = date("Y-m-d");
$number = str_replace("-", "", $phoneNumber);
if ($memID != "") {
	$sql = "select mem_id, mem_pass, mem_phone, mem_token from Gn_Member where mem_id='$memID' and mem_token <> ''";
} else {
	$sql = "select mem_id, mem_pass, mem_phone, mem_token from Gn_Member where mem_phone='$phoneNumber' and mem_token <> ''";
	exit;
}
$result = mysqli_query($self_con, $sql);
$row = mysqli_fetch_array($result);

$ret = array(
	'status' => '',
	'mem_id' => '',
	'mem_pass' => '',
	'mem_phone' => '',
	'mem_token' => '',
	'card_link' => ''
);

if ($row['mem_id']) {
	$sql_pay = "select end_date from tjd_pay_result where end_status='Y' and buyer_id='$memID' order by no desc ";
	$res_pay = mysqli_query($self_con, $sql_pay);
	if (mysqli_num_rows($res_pay) == 0) {
		$sql_pay = "select now() as end_date";
		$res_pay = mysqli_query($self_con, $sql_pay);
	}
	$row_pay = mysqli_fetch_array($res_pay);

	if ($row['mem_token'] != '') {
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
		$query = "select * from Gn_MMS_Number where mem_id = '$memID' and sendnum='$number'";
		$resul_c = mysqli_query($self_con, $query);
		$row_c = mysqli_fetch_array($resul_c);
		if ($row_c[0] != "") {
			$query = "update Gn_MMS_Number set pkey='$token' where sendnum='$number'";
			mysqli_query($self_con, $query);
		} else {
			$sql_in = "insert into Gn_MMS_Number set sendnum = '$number', pkey='$token', mem_id = '$memID', reg_date = now() , end_status='Y' , end_date='{$row_pay['end_date']}',format_date='{$format_date}' $addQuery"; //신규등록
			mysqli_query($self_con, $sql_in);
		}

		$sql = "select mem_code, mem_id, is_leave, mem_leb, iam_leb,site, site_iam,mem_phone,mem_name,mem_email,mem_add1,mem_add2 from Gn_Member use index(login_index) where mem_leb>0 and mem_id = '$memID' ";
		$resul = mysqli_query($self_con, $sql);
		$srow = mysqli_fetch_array($resul);
		if ($srow['mem_code'] and $srow['is_leave'] == 'N') {
			$mem_code = $srow['mem_code'];
			$site = $srow['site'];
			$site_iam = $srow['site_iam'];

			//$site = explode(".", $HTTP_HOST);
			// 관리자 권한이 있으면 관리자 세션 추가 Add Cooper

			if ($srow['site'] != "") {
				$_SESSION['one_member_id'] = $memID;
				$_SESSION['one_mem_lev'] = $srow['mem_leb'];
				$service_sql = "select mem_id,sub_domain from Gn_Service where mem_id= '$memID'";
				$service_result = mysqli_query($self_con, $service_sql);
				$service_row = mysqli_fetch_array($service_result);
				if ($service_row['mem_id'] != "") {
					$url = parse_url($service_row['sub_domain']);
					$_SESSION['one_member_subadmin_id'] = $memID;
					$_SESSION['one_member_subadmin_domain'] = $url['host'];
				}
			}
			if ($srow['site_iam'] != "") {
				$_SESSION['iam_member_id'] = $memID;;
				$_SESSION['iam_member_leb'] = $srow['iam_leb'];
				$iam_sql = "select mem_id,sub_domain from Gn_Iam_Service where mem_id= '$memID'";
				$iam_result = mysqli_query($self_con, $iam_sql);
				$iam_row = mysqli_fetch_array($iam_result);
				if ($iam_row['mem_id'] != "") {
					$url = parse_url($iam_row['sub_domain']);
					$_SESSION['iam_member_subadmin_id'] = $memID;
					$_SESSION['iam_member_subadmin_domain'] = $url['host'];
				}
			}
			// 마지막 접속 시간 기록 Add Cooper
			$memToken = generateRandomString(10);
			$sql = "update Gn_Member set login_date=now(),ext_recm_id='$site[0]', mem_token='$memToken' where mem_id= '$memID'";
			$resul = mysqli_query($self_con, $sql);

			$sql_chk_num = "select token from gn_mms_token where phone_num='{$number}'";
			$res_chk_num = mysqli_query($self_con, $sql_chk_num);
			$cnt_num = mysqli_num_rows($res_chk_num);
			if ($cnt_num) {
				$sql_update = "update gn_mms_token set token='{$memToken}' where phone_num='{$number}'";
				mysqli_query($self_con, $sql_update);
			} else {
				$sql_insert = "insert into gn_mms_token set mem_id='{$memID}', token='{$memToken}', phone_num='{$number}', reg_date=NOW()";
				mysqli_query($self_con, $sql_insert);
			}
		}
		$check_sql = "select count(idx) from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$memID}'";
		$check_result = mysqli_query($self_con, $check_sql);
		$check_comment_row = mysqli_fetch_array($check_result);
		if (!$check_comment_row[0]) { //네임카드가 하나도 없으면 자동으로 한개 생성한다
			$site_name = $srow['site'];
			$card_name = $srow['mem_name'];
			$card_title = $srow['mem_name'] . " 소개";
			$card_phone = $srow['mem_phone'];
			$card_email = $srow['mem_email'];
			$card_addr1 = $srow['mem_add1'];
			$card_addr2 = $srow['mem_add2'];
			if (!strpos($card_phone, "-")) {
				if (strlen($card_phone) > 10) {
					$card_phone1 = substr($card_phone, 0, 3);
					$card_phone2 = substr($card_phone, 3, 4);
					$card_phone3 = substr($card_phone, 7, 4);
					$card_phone = $card_phone1 . "-" . $card_phone2 . "-" . $card_phone3;
				} else {
					$card_phone1 = substr($card_phone, 0, 3);
					$card_phone2 = substr($card_phone, 3, 3);
					$card_phone3 = substr($card_phone, 6, 4);
					$card_phone = $card_phone1 . "-" . $card_phone2 . "-" . $card_phone3;
				}
			}
			$card_addr = $card_addr1 . " " . $card_addr2;
			$card_url = generateRndString();
			$img_url = "/iam/img/common/logo-2.png";
			$name_card_sql = "insert into Gn_Iam_Name_Card set mem_id = '{$memID}', 
														card_short_url = '{$card_url}', 
														card_title = '{$card_title}',
														card_name = '{$card_name}', 
														card_phone = '{$card_phone}', 
														card_email = '{$card_email}', 
														card_addr = '{$card_addr}', 
														profile_logo = '{$img_url}', 
														req_data = now(),
														up_data = now()";
			mysqli_query($self_con, $name_card_sql) or die(mysqli_error($self_con));
		} else {
			$query = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$memID}' order by req_data limit 0,1";
			$result = mysqli_query($self_con, $query);
			$row = mysqli_fetch_array($result);
			$card_url = $row['card_short_url'];
		}
		$card_url .= $mem_code;
		$ret['mem_id'] = $row['mem_id'];
		$ret['mem_code'] = $srow['mem_code'];
		$ret['site'] = $srow['site'];
		$ret['site_iam'] = $srow['site_iam'];
		$ret['mem_pass'] = "";
		$ret['mem_phone'] = "";
		$ret['mem_token'] = $row['mem_token'];
		$ret['status'] = '0';
	} else {
		$ret["status"] = '1';
	}
} else {
	$ret["status"] = '2';
}
echo json_encode($ret);
?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>

</html>