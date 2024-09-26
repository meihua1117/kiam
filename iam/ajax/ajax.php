<?
include_once "../../lib/rlatjd_fun.php";
include_once "../../lib/class.image.php";
//회원가입 1단계	
if ($_POST['id'] && $_POST['pwd']) {
	if (!$_POST['join_modify']) {
		$member_info['mem_id'] = htmlspecialchars($_POST['id']);
		$member_info['mem_leb'] = 22;
		$member_info['id_type'] = "hp";
		$member_info['join_ip'] = $ip;
		$member_info['join_way'] = "APP";
		$member_info['mem_leb'] = md5($_POST['pwd']);
		$member_info['web_pwd'] = $_POST['pwd'];
	}
	if ($_FILES['profile']) {
		$tempFile = $_FILES['profile']['tmp_name'];
		if ($tempFile) {
			$file_arr = explode(".", $_FILES['profile']['name']);
			$tmp_file_arr = explode("/", $tempFile);
			$file_name = date("Ymds") . "_" . $tmp_file_arr[count($tmp_file_arr) - 1] . "." . $file_arr[count($file_arr) - 1];
			$up_dir = make_folder_month(2);
			if ($up_dir != '') {
				$uploaddir = '../..' . $up_dir;
			} else {
				$uploaddir = '../../upload/';
				$up_dir = "/upload/";
			}
			$upload_file = $uploaddir . $file_name;
			if (move_uploaded_file($_FILES['profile']['tmp_name'], $upload_file)) {
				$handle = new Image($upload_file, 800);
				$handle->resize();
				uploadFTP($upload_file);
				$member_info['profile'] = $up_dir . $file_name;
			}
		}
	}
	if ($_POST['mobile_1'])
		$member_info['mem_phone'] = $_POST['mobile_1'] . "-" . $_POST['mobile_2'] . "-" . $_POST['mobile_3'];
	$member_info['mem_nick'] = htmlspecialchars($_POST['name']);
	$member_info['mem_name'] = htmlspecialchars($_POST['name']);
	$member_info['mem_email'] = $_POST['email_1'] . "@" . $_POST['email_2'];
	$member_info['mem_add1'] = $_POST['add1'];
	$member_info['zy'] = $_POST['zy'];
	$member_info['mem_birth'] = $_POST['birth_1'] . "-" . $_POST['birth_2'] . "-" . $_POST['birth_3'];
	$member_info['is_message'] = $_POST['is_message'];

	if ($_POST['site']) {
		$sql_service = "select count(*) FROM Gn_Service WHERE sub_domain like '%http://" . $HTTP_HOST . "'";
		$res_service = mysqli_query($self_con, $sql_service);
		$row_service = mysqli_fetch_array($res_service);
		if ($row_service[0] != 0)
			$member_info['site'] = $_POST['solution_name'];
		else
			$member_info['site'] = "kiam";
		$member_info['site_iam'] = $_POST['site_name'];
	}
	if ($_POST['recommend_id']) {
		$member_info['recommend_id'] = $_POST['recommend_id'];
	} else {
		if ($HTTP_HOST == 'kiam.kr')
			$member_info['recommend_id'] = 'onlyone';
		else {
			$sql = "select mem_id from Gn_Iam_Service where sub_domain like '%http://" . $HTTP_HOST . "%'";
			$res = mysqli_query($self_con, $sql);
			$row = mysqli_fetch_array($res);
			$member_info['recommend_id'] = $row['mem_id'];
		}
	}

	// Cooper AAdd
	if ($HTTP_HOST != "kiam.kr") {
		$query = "select * from Gn_Iam_Service where sub_domain like '%" . $HTTP_HOST . "'";
		$res = mysqli_query($self_con, $query);
		$domainData = mysqli_fetch_array($res);
		$parse = parse_url($domainData['sub_domain']);
		$site = explode(".", $parse['host']);

		$query = "select count(*) as cnt from Gn_Member where site='" . $site[0] . "'";
		$res = mysqli_query($self_con, $query);
		$data = mysqli_fetch_array($res);
		if ($domainData['mem_cnt'] <= $data[0] && !$_POST['join_modify']) {
			echo "<Script>alert('본 사이트에서는 더이상 회원가입이 되지 않습니다. 관리자에게 문의해주세요');hisotory.go(-1);</script>";
			exit;
		}
	}
	if ($_POST['recommend_branch'])
		$member_info['recommend_branch'] = $_POST['recommend_branch'];
	$member_info['mem_sex'] = $_POST['mem_sex'];
	if ($_POST['bank_name'])
		$member_info['bank_name'] = $_POST['bank_name'];
	if ($_POST['bank_account'])
		$member_info['bank_account'] = $_POST['bank_account'];
	if ($_POST['bank_owner'])
		$member_info['bank_owner'] = $_POST['bank_owner'];
	if ($_POST['join_modify'] == "") {
		if ($_POST['rnum'] != "") {
			$sql = "select * from Gn_Member_Check_Sms where mem_phone='$member_info[mem_phone]' and secret_key='$_POST[rnum]' and status='Y' order by idx desc";
			$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
			$data = $row = mysqli_fetch_array($result);
			if ($data['idx'] == "") {
?>
				<script language="javascript">
					alert('인증번호를 확인해주세요...');
				</script>
			<?
				exit;
			}
		}
	}
	if ($_POST['join_modify'])
		$sql = " update Gn_Member set ";
	else
		$sql = " insert into Gn_Member set ";

	$i = 0;
	foreach ($member_info as $key => $v) {
		$bd = $i == (count($member_info) - 1) ? "" : ",";
		$v = $key == "web_pwd" ? "password('$v')" : "'$v'";
		$sql .= " $key=$v $bd ";
		$i++;
	}

	if ($_POST['join_modify'])
		$sql .= " where mem_code='{$_POST['join_modify']}' ";
	else
		$sql .= " ,first_regist=now() , mem_check=now() ";
	if (strstr($sql, "update") == true && $_POST['join_modify'] == "")
		$sql .= " where mem_code='{$_POST['join_modify']}' ";
	if (mysqli_query($self_con, $sql) or die(mysqli_error($self_con))) {
		if ($_POST['join_modify']) {
			$_SESSION['iam_member_leb'] = 0;
			?>
			<script language="javascript">
				alert('수정완료되었습니다.');
				location = 'mypage.php';
			</script>
		<?
		} else {
			$sql = "select * from Gn_MMS_Group where mem_id='{$member_info['mem_id']}' and grp='아이엠'";
			$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
			$data = mysqli_fetch_array($result);
			if ($data['idx'] == "") {
				$query = "insert into Gn_MMS_Group set mem_id='{$member_info['mem_id']}', grp='아이엠', reg_date=NOW()";
				mysqli_query($self_con, $query);
			}
			$_SESSION['one_member_id'] = $_POST['id'];
			$content = $_POST['name'] . "님 온리원문자 회원이 되신걸 환영합니다.";
			$subject = "온리원문자 회원가입";
			sendemail("", $member_info['mem_email'], "admin@kiam.kr", $subject, $content);
		?>
			<script language="javascript">
				alert('<?= $_SESSION['one_member_id'] ?> 회원가입되었습니다.');
				location.href = '/';
			</script>
		<?
		}
	}
}
//아이디 패스워드찾기
if ($_POST['search_id_pw_mem_name'] && $_POST['search_id_pw_type']) {
	$s = 0;
	$conf_sql = "select * from gn_conf";
	$conf_result = mysqli_query($self_con, $conf_sql);
	$conf_row = mysqli_fetch_array($conf_result);
	$mem_id = $conf_row['phone_id'];
	$send_num = $conf_row['phone_num'];
	if ($_POST['search_id_pw_type'] == "phone") {
		$phone_num = str_replace("-", "", $_POST['search_id_pw_phone']);
		$sql_serch = " (mem_phone='{$_POST['search_id_pw_phone']}' or mem_phone='$phone_num')";
	} else if ($_POST['search_id_pw_type'] == "email")
		$sql_serch = " mem_email='$_POST[search_id_pw_email]' ";
	if ($_POST['search_id_pw_mem_id'])
		$sql_serch .= " and mem_id=trim('{$_POST['search_id_pw_mem_id']}') and mem_name=trim('{$_POST['search_id_pw_mem_name']}') ";
	else
		$sql_serch .= " and mem_name=trim('{$_POST['search_id_pw_mem_name']}') ";
	$sql = "select * from Gn_Member where $sql_serch ";
	$resul = mysqli_query($self_con, $sql);
	$row = mysqli_fetch_array($resul);
	if ($row['mem_code']) {
		// 수정 시작
		if ($row['is_leave'] == 'Y') {
		?>
			<script language="javascript">
				alert('탈퇴한 아이디 입니다.');
				//location.reload();
			</script>
		<?
		} else if ($_POST['search_id_pw_mem_id']) {
			$new_pwd = substr(md5(time()), 0, 10);
			$sql_u = "update Gn_Member set web_pwd=password('$new_pwd') where mem_code='$row[mem_code]' ";
			mysqli_query($self_con, $sql_u);

			if ($row['site_iam'] == "kiam" || $row['site_iam'] == "") {
				$site_iam = "";
			} else {
				$site_iam = $row['site_iam'] . ".";
			}
			$content = $row['mem_name'] . "님 온리원문자 비밀번호가[ " . $new_pwd . " ] 로 변경되었습니다. " . $site_iam . "kiam.kr (" . $row['mem_id'] . ")";
			$content1 = $row['mem_name'] . "님 온리원문자 비밀번호가[ " . $new_pwd . " ] 로 변경되었습니다.";
			$subject = "온리원문자 비밀번호찾기";
			if ($_POST['search_id_pw_type'] == "email") {
				sendemail("", $row['mem_email'], "admin@kiam.kr", $subject, $content);
				$msg = "회원님의 비밀번호가 변경되었습니다.이메일 " . $row['mem_email'] . "로 발송되었습니다.";
			} else {
				$s++;
				$sql_app_mem = "select * from Gn_MMS_Number where (sendnum='$phone_num' and sendnum is not null and sendnum != '')";
				$res_app_mem = mysqli_query($self_con, $sql_app_mem);
				if (mysqli_num_rows($res_app_mem)) {
					$number_row = mysqli_fetch_array($res_app_mem);
					sendmms(5, $number_row['mem_id'], $phone_num, $phone_num, "", $subject, $content, "", "", "", "Y");
				}
				else{
					//회사폰을 발송폰으로 설정해서 발송한다.
					sendmms(5, $mem_id, $send_num, $phone_num, "", $subject, $content, "", "", "", "Y");
				}
				$msg = "회원님의 비밀번호가 변경되었습니다.셀폰 " . $phone_num . "으로 발송되었습니다.";
			}
		?>
			<script language="javascript">
				var msg = "<?= $msg ?>";
				msg = msg.split(".");
				alert(msg[0] + ".\r\n" + msg[1]);
				location.reload();
			</script>
		<?
		} else {
			$content = "";
			if ($_POST['search_id_pw_type'] == "phone") {
				$sql_serch = " and REPLACE(mem_phone,'-','')='{$phone_num}'";
			} else if ($_POST['search_id_pw_type'] == "email")
				$sql_serch = " and mem_email='$_POST[search_id_pw_email]' ";
			$sql_doub_mem = "select * from Gn_Member where mem_name='{$_POST['search_id_pw_mem_name']}' " . $sql_serch;
			$res_doub_mem = mysqli_query($self_con, $sql_doub_mem);
			while ($row1 = mysqli_fetch_array($res_doub_mem)) {
				if ($row1['site_iam'] == "kiam" || $row1['site_iam'] == "") {
					$site_iam = "";
				} else {
					$site_iam = $row1['site_iam'] . ".";
				}
				$content .= $row1['mem_name'] . "님 온리원문자 아이디는[ " . $row1['mem_id'] . " ] 입니다. " . $site_iam . "kiam.kr (" . $row1['mem_id'] . ")\n";
				$subject = "온리원문자 아이디찾기";
			}
			if ($_POST['search_id_pw_type'] == "email") {
				sendemail("", $row['mem_email'], "admin@kiam.kr", $subject, $content);
				$msg = "회원님의 아이디가 이메일 " . $row['mem_email'] . "로 발송되었습니다.";
			} else {
				$s++;
				$sql_app_mem = "select * from Gn_MMS_Number where (sendnum='$phone_num' and sendnum is not null and sendnum != '')";
				$res_app_mem = mysqli_query($self_con, $sql_app_mem);
				if (mysqli_num_rows($res_app_mem)) {
					$number_row = mysqli_fetch_array($res_app_mem);
					sendmms(5, $number_row['mem_id'], $phone_num, $phone_num, "", $subject, $content, "", "", "", "Y");
				}
				else{
					//회사폰을 발송폰으로 설정해서 발송한다.
					sendmms(5, $mem_id, $send_num, $phone_num, "", $subject, $content, "", "", "", "Y");
				}
				$msg = "회원님의 아이디가 셀폰 " . $phone_num . "으로 발송되었습니다.";
			}
		?>
			<script language="javascript">
				var msg = "<?= $msg ?>";
				msg = msg.split(".");
				alert(msg[0] + ".\r\n" + msg[1]);
				location.reload();
			</script>
		<?
		}
	} else {
		?>
		<script language="javascript">
			alert('입력하신 정보가 틀렸습니다.\n카톡상담창을 통해 관리자에게 문의해주세요.');
		</script>
<?
		exit;
	}
}
?>