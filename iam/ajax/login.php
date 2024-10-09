<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
if ($_POST['one_id'] && $_POST['one_pwd']) {
	$_POST['one_id'] = strtolower(trim($_POST['one_id']));
	$site = explode(".", $HTTP_HOST);
	$sql = "select stop_yn,orderNumber,end_status from tjd_pay_result where buyer_id='{$_POST['one_id']}' and gwc_cont_pay=0 and end_status in ('Y','A') and stop_yn='N' and
        (member_type like '%standard%' or member_type like '%professional%' or member_type like '%enterprise%' or member_type='베스트상품' or ((iam_pay_type = '' or iam_pay_type = '0' or iam_pay_type = '전문가') and member_type != '포인트충전')) and 
		payMethod <> 'POINT' order by end_date desc";
	$res_result = mysql_query($sql);
	$pay_data = mysql_fetch_array($res_result);
	if ($pay_data == null) {
		$sql = "select stop_yn,orderNumber,end_status from tjd_pay_result where buyer_id='{$_POST['one_id']}' and gwc_cont_pay=0 and 
        (member_type like '%standard%' or member_type like '%professional%' or member_type like '%enterprise%' or member_type='베스트상품' or ((iam_pay_type = '' or iam_pay_type = '0' or iam_pay_type = '전문가') and member_type != '포인트충전')) and 
		payMethod <> 'POINT' order by end_date desc";
		$res_result = mysql_query($sql);
		$pay_data = mysql_fetch_array($res_result);
		if ($pay_data['stop_yn'] == "Y" || $pay_data['end_status'] == "N" || $pay_data['end_status'] == "E") {
			echo "<script>history.back(-1);window.parent.open(" . "'/payment_pop.php?index={$pay_data['orderNumber']}&type=user'" . ", \"notice_pop\", \"toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=600,height=350\");</script>";
			exit;
		}
	}
	$mem_pass = $_POST['one_pwd'];
	$sql = "select mem_code, mem_id, is_leave, mem_leb, iam_leb,site, site_iam from Gn_Member use index(login_index) where mem_leb>0 and ((mem_id = '{$_POST['one_id']}' and web_pwd=password('$mem_pass')) or (mem_email = '{$_POST['one_id']}' and web_pwd=password('$mem_pass'))) ";
	$resul = mysql_query($sql);
	$row = mysql_fetch_array($resul);
	if ($row['mem_code'] && $row['is_leave'] == 'N') {
		// 관리자 권한이 있으면 관리자 세션 추가 Add Cooper
		$admin_sql = "select mem_id from Gn_Admin where mem_id= '{$_POST['one_id']}'";
		$admin_result = mysql_query($admin_sql);
		$admin_row = mysql_fetch_array($admin_result);
		if ($admin_row[0] != "") {
			$_SESSION['one_member_admin_id'] = $_POST['one_id'];
		}
		if ($row['site'] != "") {
			$_SESSION['one_member_id'] = $_POST['one_id'];
			$_SESSION['one_mem_lev'] = $row['mem_leb'];
			$service_sql = "select mem_id,sub_domain from Gn_Service where mem_id= '{$_POST['one_id']}'";
			$service_result = mysql_query($service_sql);
			$service_row = mysql_fetch_array($service_result);
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
			$iam_result = mysql_query($iam_sql);
			$iam_row = mysql_fetch_array($iam_result);
			if ($iam_row['mem_id'] != "") {
				$url = parse_url($iam_row['sub_domain']);
				$_SESSION['iam_member_subadmin_id'] = $_POST['one_id'];
				$_SESSION['iam_member_subadmin_domain'] = $url['host'];
			}
		}
		//login이력을 기록한다.
		$sql = "select idx from gn_hist_login where userid='{$_POST['one_id']}' and ip='{$_SERVER['REMOTE_ADDR']}' and success='N' order by idx desc limit 0,1";
		$resul = mysql_query($sql);
		$hrow = mysql_fetch_array($resul);
		if ($hrow[0] != "") {
			$sql = "update gn_hist_login set success='Y' where idx='$hrow[0]'";
			$resul = mysql_query($sql);
		} else {
			$sql = "insert into gn_hist_login (domain,userid,position,ip,success) values('$site[0]', '{$_POST['one_id']}', 'iam', '$_SERVER[REMOTE_ADDR]', 'Y')";
			$resul = mysql_query($sql);
		}

		// 마지막 접속 시간 기록 Add Cooper
		// $memToken = generateRandomString(10);
		$sql = "update Gn_Member set login_date=now(),ext_recm_id='$site[0]' where mem_id= '{$_POST['one_id']}'";
		$resul = mysql_query($sql);

		$alert_sql = "SELECT NO FROM tjd_sellerboard WHERE pop_yn='Y' ORDER BY DATE DESC LIMIT 0, 3";
		$alret_res = mysql_query($alert_sql);
		while ($alret_row = mysql_fetch_array($alret_res)) {
			echo "<script>if(document.cookie.search('Memo_iam" . $alret_row['NO'] . "') == -1) window.open('/iam/notice_pop.php?id=" . $alret_row['NO'] . "', '','toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=800,height=400');</script>";
		}

		if ($row['site_iam'] == $site[0]) {
			if ($_POST['contents_idx']) { ?>
				<script language="javascript">
					var url = "/iam/contents.php?contents_idx=" + '<?= $_POST['contents_idx'] ?>';
					window.parent.location.href = url;
				</script>
			<? } else { ?>
				<script language="javascript">
					window.parent.location.replace('/');
				</script>
			<? }
		} else if ($row['site_iam'] != "") {
			// 세션 쿠키 파라미터 설정
			$site = $row['site_iam'] . ".";
			if ($row['site_iam'] == "kiam")
				$site = "";
			if ($_POST['contents_idx']) { ?>
				<script language="javascript">
					var url = "http://" + "<?= $site ?>" + "kiam.kr/iam/contents.php?contents_idx=" + '<?= $_POST['contents_idx'] ?>' /* + "&key=" + '<?= $sess_id ?>'*/ ;
					window.parent.location.href = url;
				</script>
			<? } else if ($_POST['for_report']) {?>
				<script language="javascript">
					var url = "http://" + "<?= $site ?>" + "kiam.kr/iam/mypage_report_list.php?for_report=true";
					window.parent.location.href = url;
				</script>
			<? } else { ?>
				<script language="javascript">
					var url = "http://" + "<?= $site ?>" + "kiam.kr" /*/?key=" + '<?= $sess_id ?>'*/ ;
					window.parent.location.href = url;
				</script>
			<? } ?>
		<? }
	} else if ($row['mem_code'] && $row['is_leave'] == 'Y') { ?>
		<script language="javascript">
			alert('탈퇴한 회원 아이디입니다.');
			window.parent.login_form.one_id.focus();
		</script>
		<?	} else {
		//login이력을 기록한다.
		$msg = "아이디 혹은 비밀번호가 틀렸습니다.";
		$sql = "select idx,count from gn_hist_login where userid='{$_POST['one_id']}' and ip='{$_SERVER['REMOTE_ADDR']}' and success='N' order by idx desc limit 0,1";
		$resul = mysql_query($sql);
		$hrow = mysql_fetch_array($resul);
		if ($hrow[0] != "") {
			$sql = "update gn_hist_login set count=count+1 where idx='$hrow[0]'";
			$resul = mysql_query($sql);

			$try_count = intval($hrow[1]) + 1;
			if ($try_count >= 5) { ?>
				<script language="javascript">
					<?
					if ($try_count == 5) { ?>
						alert("아이디/비밀번호 찾기로 전환됩니다.");
					<? } ?>
					window.parent.location.replace('/id_pw.php');
				</script>
		<?
				exit;
			} else if ($try_count >= 3) {
				$msg = '귀하의 계정 정보가 현재 ' . $try_count . '회 오류입니다.\n5회 오류가 발생할 경우 계정찾기로 전환됩니다.\n다시한번 확인하시고 계정정보입력바랍니다.\n감사합니다.';
			}
		} else {
			$sql = "insert into gn_hist_login (domain,userid,position,ip,count) values('$site[0]', '{$_POST['one_id']}', 'iam', '{$_SERVER['REMOTE_ADDR']}', count+1)";
			$resul = mysql_query($sql);
		}
		?>
		<script language="javascript">
			alert('<?= $msg ?>');
			history.back(-1);
		</script>
<? }
} ?>