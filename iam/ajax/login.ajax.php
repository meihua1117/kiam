<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
if ($_POST['one_id'] && $_POST['one_pwd']) {
	$_POST['one_id'] = strtolower(trim($_POST['one_id']));
	$site = explode(".", $HTTP_HOST);
	$sql = "select stop_yn,orderNumber,end_status from tjd_pay_result where buyer_id='{$_POST['one_id']}' and gwc_cont_pay=0 and end_status in ('Y','A') and stop_yn='N' and
        (member_type like '%standard%' or member_type like '%professional%' or member_type like '%enterprise%' or member_type='베스트상품' or ((iam_pay_type = '' or iam_pay_type = '0' or iam_pay_type = '전문가') and member_type != '포인트충전')) and 
		payMethod <> 'POINT' order by end_date desc";
	$res_result = mysqli_query($self_con, $sql);
	$pay_data = mysqli_fetch_array($res_result);
	if ($pay_data == null) {
		$sql = "select stop_yn,orderNumber,end_status from tjd_pay_result where buyer_id='{$_POST['one_id']}' and gwc_cont_pay=0 and 
        (member_type like '%standard%' or member_type like '%professional%' or member_type like '%enterprise%' or member_type='베스트상품' or ((iam_pay_type = '' or iam_pay_type = '0' or iam_pay_type = '전문가') and member_type != '포인트충전')) and 
		payMethod <> 'POINT' order by end_date desc";
		$res_result = mysqli_query($self_con, $sql);
		$pay_data = mysqli_fetch_array($res_result);
		if ($pay_data['stop_yn'] == "Y" || $pay_data['end_status'] == "N" || $pay_data['end_status'] == "E") {
			echo json_encode(array("result" => "fail", "message" => "payment_error", "code" => $pay_data['orderNumber']));
			exit;
		}
	}
	$mem_pass = $_POST['one_pwd'];
	$sql = "select mem_code, mem_id, is_leave, mem_leb, iam_leb,site, site_iam from Gn_Member use index(login_index) where mem_leb>0 and ((mem_id = '{$_POST['one_id']}' or mem_email = '{$_POST['one_id']}') and (mem_pass=md5('$mem_pass') or web_pwd=md5('$mem_pass')))";
	$resul = mysqli_query($self_con, $sql);
	$row = mysqli_fetch_array($resul);
	if ($row['mem_code'] && $row['is_leave'] == 'N') {
		// 관리자 권한이 있으면 관리자 세션 추가 Add Cooper
		$admin_sql = "select mem_id from Gn_Admin where mem_id= '{$_POST['one_id']}'";
		$admin_result = mysqli_query($self_con, $admin_sql);
		$admin_row = mysqli_fetch_array($admin_result);
		if ($admin_row[0] != "") {
			$_SESSION['one_member_admin_id'] = $_POST['one_id'];
		}
		if ($row['site'] != "") {
			$_SESSION['one_member_id'] = $_POST['one_id'];
			$_SESSION['one_mem_lev'] = $row['mem_leb'];
			$_SESSION['site'] = $row['site'];
			$service_sql = "select mem_id,sub_domain from Gn_Service where mem_id= '{$_POST['one_id']}'";
			$service_result = mysqli_query($self_con, $service_sql);
			$service_row = mysqli_fetch_array($service_result);
			if ($service_row['mem_id'] != "") {
				$url = parse_url($service_row['sub_domain']);
				$_SESSION['one_member_subadmin_id'] = $_POST['one_id'];
				$_SESSION['one_member_subadmin_domain'] = $url['host'];
			}
		}
		if ($row['site_iam'] != "") {
			$_SESSION['iam_member_id'] = $_POST['one_id'];
			$_SESSION['iam_member_leb'] = $row['iam_leb'];
			$_SESSION['site_iam'] = $row['site_iam'];
			$iam_sql = "select mem_id,sub_domain from Gn_Iam_Service where mem_id= '{$_POST['one_id']}'";
			$iam_result = mysqli_query($self_con, $iam_sql);
			$iam_row = mysqli_fetch_array($iam_result);
			if ($iam_row['mem_id'] != "") {
				$url = parse_url($iam_row['sub_domain']);
				$_SESSION['iam_member_subadmin_id'] = $_POST['one_id'];
				$_SESSION['iam_member_subadmin_domain'] = $url['host'];
			}
		}
		//login이력을 기록한다.
		$sql = "select idx from gn_hist_login where userid='{$_POST['one_id']}' and ip='{$_SERVER['REMOTE_ADDR']}' and success='N' order by idx desc limit 0,1";
		$resul = mysqli_query($self_con, $sql);
		$hrow = mysqli_fetch_array($resul);
		if ($hrow[0] != "") {
			$sql = "update gn_hist_login set success='Y' where idx='$hrow[0]'";
			$resul = mysqli_query($self_con, $sql);
		} else {
			$sql = "insert into gn_hist_login (domain,userid,position,ip,success) values('$site[0]', '{$_POST['one_id']}', 'iam', '{$_SERVER['REMOTE_ADDR']}', 'Y')";
			$resul = mysqli_query($self_con, $sql);
		}

		// 마지막 접속 시간 기록 Add Cooper
		// $memToken = generateRandomString(10);
		$sql = "update Gn_Member set login_date=now(),ext_recm_id='$site[0]' where mem_id= '{$_POST['one_id']}'";
		$resul = mysqli_query($self_con, $sql);
		echo json_encode(array("result" => "success"));
	} else if ($row['mem_code'] && $row['is_leave'] == 'Y') {
		echo json_encode(array("result" => "fail", "message" => "leave_error"));
	} else {
		//login이력을 기록한다.
		$message = "login_error";
		$code = "아이디 혹은 비밀번호가 틀렸습니다.";
		$sql = "select idx,count from gn_hist_login where userid='{$_POST['one_id']}' and ip='{$_SERVER['REMOTE_ADDR']}' and success='N' order by idx desc limit 0,1";
		$resul = mysqli_query($self_con, $sql);
		$hrow = mysqli_fetch_array($resul);
		if ($hrow[0] != "") {
			$sql = "update gn_hist_login set count=count+1 where idx='$hrow[0]'";
			$resul = mysqli_query($self_con, $sql);

			$try_count = intval($hrow[1]) + 1;
			if ($try_count >= 5) {
				$message = "login_error_over";
				$code = "아이디/비밀번호 찾기로 전환됩니다.";
			} else if ($try_count >= 3) {
				$code = '귀하의 계정 정보가 현재 ' . $try_count . '회 오류입니다.\n5회 오류가 발생할 경우 계정찾기로 전환됩니다.\n다시한번 확인하시고 계정정보입력바랍니다.\n감사합니다.';
			}
		} else {
			$sql = "insert into gn_hist_login (domain,userid,position,ip,count) values('$site[0]', '{$_POST['one_id']}', 'iam', '{$_SERVER['REMOTE_ADDR']}', count+1)";
			$resul = mysqli_query($self_con, $sql);
		}
		echo json_encode(array("result" => "fail", "message" => $message, "code"=>$code));
	}
}?>
