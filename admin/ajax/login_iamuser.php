<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
if ($_POST['one_id'] && $_POST['mem_pass']) {
	$mem_pass = $_POST['one_pwd'];
	$sql = "select mem_code,mem_leb from Gn_Member where mem_id = '{$_POST['one_id']}' and web_pwd=password('{$_POST['mem_pass']}') and mem_code='{$_POST['mem_code']}' ";
	$resul = mysqli_query($self_con, $sql);
	if (mysqli_num_rows($resul) == 0) {
	?>
		<script language="javascript">
			alert('아이디 혹은 비밀번호가 틀렸습니다.')
			window.parent.login_form.one_id.focus();
		</script>
	<?
		exit;
	}
	$srow = mysqli_fetch_array($resul);
	if ($srow['mem_code']) {
		if ($_POST['sub_domain']) {
			$sub_domain = $_POST['sub_domain'];
			if (strpos($sub_domain, "/iam") === false)
				$sub_domain .= "/iam";
		}
		// 관리자 권한이 있으면 관리자 세션 추가 Add Cooper
		$sql = "select mem_id from Gn_Admin where mem_id= '{$_POST['one_id']}'";
		$resul = mysqli_query($self_con, $sql);
		if(mysqli_num_rows($resul) > 0){
			$_SESSION['one_member_admin_id'] = $_POST['one_id'];
		} else {
			$_SESSION['one_member_admin_id'] = "";
			$_SESSION['one_member_id'] = "";
			$_SESSION['one_member_admin_id'] = "";
			if ($sub_domain) {
				$sql = "select mem_id from Gn_Service where mem_id= '{$_POST['one_id']}' and sub_domain like '%$sub_domain' ";
				$resul = mysqli_query($self_con, $sql);
				$row = mysqli_fetch_array($resul);
				if ($row[0] != "") {
					$_SESSION['one_member_subadmin_id'] = $_POST['one_id'];
					$_SESSION['one_member_subadmin_domain'] = $HTTP_HOST;
				}
			}
		}
		$_SESSION['one_member_id'] = $_POST['one_id'];
		$_SESSION['one_mem_lev'] = $srow['mem_leb'];
		$_SESSION['iam_member_id'] = $_POST['one_id'];
		$_SESSION['iam_member_leb'] = $srow['mem_leb'];

		// 마지막 접속 시간 기록 Add Cooper
		$sql = "update Gn_Member set login_date=now() where mem_id= '{$_POST['one_id']}'";
		$resul = mysqli_query($self_con, $sql);
	} else {
	?>
		<script language="javascript">
			alert('아이디 혹은 비밀번호가 틀렸습니다.')
			window.parent.login_form.one_id.focus();
		</script>
<?
	}
}
?>