<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/db_config.php";
//include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/common_func.php";
$_REQUEST['id'] = strtolower(trim($_REQUEST['id']));
$sql = "select mem_code, mem_id, is_leave, mem_leb, iam_leb,site, site_iam from Gn_Member use index(login_index) where mem_leb>0 and mem_id = '{$_REQUEST['id']}' and mem_code='{$_REQUEST['mem_code']}' ";
$resul = mysqli_query($self_con, $sql);
$row = mysqli_fetch_array($resul);
@ini_set('display_errors', false);
@ini_set("session.cache_expire", 60);
@ini_set("session.gc_maxlifetime", 86400);
$sess_path = $_SERVER['DOCUMENT_ROOT'] . "/_session";
@session_save_path($sess_path);
$cookieDomain = '.kiam.kr'; // 상위 도메인으로 설정. 도메인 앞에 점(.)이 붙는 것에 주의하세요.
session_set_cookie_params(array(
	'lifetime' => 0, // 브라우저 닫힐 때까지 유효
	'path' => '/',
	'domain' => $cookieDomain, // 상위 도메인 설정
	//'secure' => true, // HTTPS 사용 시 true로 설정
	//'httponly' => true, // JavaScript에서 접근 불가
	'samesite' => 'None' // 또는 'Lax'나 'Strict'
));
session_start();
if ($row['mem_code'] and $row['is_leave'] == 'N') {
	$mem_code = $row['mem_code'];
	$site = $row['site'];
	$site_iam = $row['site_iam'];
	// 관리자 권한이 있으면 관리자 세션 추가 Add Cooper

	if ($row['site'] != "") {
		$_SESSION['one_member_id'] = $_REQUEST['id'];
		$_SESSION['one_mem_lev'] = $row['mem_leb'];
		$service_sql = "select mem_id,sub_domain from Gn_Service where mem_id= '{$_REQUEST['id']}'";
		$service_result = mysqli_query($self_con, $service_sql);
		$service_row = mysqli_fetch_array($service_result);
		if ($service_row['mem_id'] != "") {
			$url = parse_url($service_row['sub_domain']);
			$_SESSION['one_member_subadmin_id'] = $_REQUEST['id'];
			$_SESSION['one_member_subadmin_domain'] = $url['host'];
		}
	}
	if ($row['site_iam'] != "") {
		$_SESSION['iam_member_id'] = $_REQUEST['id'];
		$_SESSION['iam_member_leb'] = $row['iam_leb'];
		$iam_sql = "select mem_id,sub_domain from Gn_Iam_Service where mem_id= '{$_REQUEST['id']}'";
		$iam_result = mysqli_query($self_con, $iam_sql);
		$iam_row = mysqli_fetch_array($iam_result);
		if ($iam_row['mem_id'] != "") {
			$url = parse_url($iam_row['sub_domain']);
			$_SESSION['iam_member_subadmin_id'] = $_REQUEST['id'];
			$_SESSION['iam_member_subadmin_domain'] = $url['host'];
		}
	}
	// 마지막 접속 시간 기록 Add Cooper
	// $memToken = generateRandomString(10);
	$sql = "update Gn_Member set login_date=now(),ext_recm_id='$site' where mem_id= '{$_REQUEST['id']}'";
	$resul = mysqli_query($self_con, $sql);
	if ($row['site_iam'] != "") {
		$site = $row['site_iam'];
		$site = "https://" . $site . ".kiam.kr/";
		if ($row['site_iam'] == "kiam")
			$site = "https://kiam.kr/";
	}
	if ($_REQUEST['url'] != "") {
?>
		<iframe src="<?= $site ?>/ajax/login.support.app.php?one_id=<?php echo $_REQUEST['id']; ?>&mem_code=<?php echo $_REQUEST['mem_code']; ?>" width="0" height="0"></iframe>
	<?
	} else {
	?>
		<iframe src="<?= $site ?>/ajax/login.support.app.php?one_id=<?php echo $_REQUEST['id']; ?>&mem_code=<?php echo $_REQUEST['mem_code']; ?>" width="0" height="0"></iframe>
	<?
	}
} else if ($row['mem_code'] and $row['is_leave'] == 'Y') { ?>
	<script language="javascript">
		alert('아이디 혹은 비밀번호가 틀렸습니다.');
		history.back(-1);
	</script>
<?	} else { ?>

<? } ?>