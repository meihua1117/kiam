<?php
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

@header('Content-Type: text/html; charset=utf-8');
//define("GOOGLE_SERVER_KEY", "AAAAmvl-uQA:APA91bHP4S4L8-nMvfOJ9vcjYlTmiRjEfOcLbAm6ITDFo9Ky-ziKAowlZi0rWhO3c7jsZ50unqWabQCBAmtr9bOxUIbwyAMgRsxO1jeLKlJ9l_Gir_wc1sZ66VBtHVBSjeAZcRfffVwo7M2fBvrrt1d5vz5clf7PVQ");
$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
@header('Expires: 0'); // rfc2616 - Section 14.21
@header('Last-Modified: ' . $gmnow);
@header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
@header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
@header('Pragma: no-cache'); // HTTP/1.0
/* 클래스 파일 로드 */
include_once "common_class.php";
include_once "common_var.php";
include_once "common_env.php";
// SQL Injection 대응 문자열 필터링
define('G5_ESCAPE_PATTERN',  '/(and|or).*(union|select|insert|update|delete|from|where|limit|create|drop).*/i');
define('G5_ESCAPE_REPLACE',  '');
define('G5_ESCAPE_FUNCTION', 'sql_escape_string');
function sql_escape_string($str)
{
	if (defined('G5_ESCAPE_PATTERN') && defined('G5_ESCAPE_REPLACE')) {
		$pattern = G5_ESCAPE_PATTERN;
		$replace = G5_ESCAPE_REPLACE;

		if ($pattern)
			$str = preg_replace($pattern, $replace, $str);
	}

	$str = call_user_func('addslashes', $str);

	return $str;
}
//==============================================================================
// SQL Injection 등으로 부터 보호를 위해 sql_escape_string() 적용
//------------------------------------------------------------------------------

// multi-dimensional array에 사용자지정 함수적용
function array_map_deep($fn, $array)
{
	if (is_array($array)) {
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$array[$key] = array_map_deep($fn, $value);
			} else {
				$array[$key] = call_user_func($fn, $value);
			}
		}
	} else {
		$array = call_user_func($fn, $array);
	}

	return $array;
}
// magic_quotes_gpc 에 의한 backslashes 제거
if (7.0 > (float)phpversion()) {
	if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
		$_POST    = array_map_deep('stripslashes',  $_POST);
		$_GET     = array_map_deep('stripslashes',  $_GET);
		$_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
		$_REQUEST = array_map_deep('stripslashes',  $_REQUEST);
	}
}

// sql_escape_string 적용
$_POST    = array_map_deep(G5_ESCAPE_FUNCTION,  $_POST);
$_GET     = array_map_deep(G5_ESCAPE_FUNCTION,  $_GET);
$_COOKIE  = array_map_deep(G5_ESCAPE_FUNCTION,  $_COOKIE);
$_REQUEST = array_map_deep(G5_ESCAPE_FUNCTION,  $_REQUEST);

extract($_GET);
//$cdn = "http://goodhow.com";
$kiam_ssl = "https://www.kiam.kr";
$cross_img = $kiam_ssl . "/lib/cross_image.php?src=";
$cross_page = ""; //$kiam_ssl."/lib/cross_page.php?src=";
$is_pay_version = true;
/*if ($_GET['site'])
	$_SESSION['site_iam'] = $_GET['site'];
if ($_GET['sess_mem_id'])
	$_SESSION['sess_mem_id'] = $_GET['sess_mem_id'];
*/

$whois_api_key = "2021030317024746733699";
$domain_url = "http://www.kiam.kr";
$HTTP_HOST = str_replace("www.", "", $_SERVER['HTTP_HOST']);
$REQUEST_URI = $_SERVER['REQUEST_URI'];
if ($_SESSION['iam_member_id']) {
	$query = "select card_mode from Gn_Member where mem_id = '{$_SESSION['iam_member_id']}'";
	$result = mysqli_query($self_con, $query);
	$row = mysqli_fetch_array($result);
	$card_mode = $row['card_mode'];
} else {
	$card_mode = "card_title";
}
$sql_settle = "select key_content from Gn_Search_Key where key_id= 'settle_btn_display'";
$res_settle = mysqli_query($self_con, $sql_settle);
$row_settle = mysqli_fetch_array($res_settle);
if ($row_settle['key_content'] == "Y") {
	$is_pay_version = true;
} else {
	$is_pay_version = false;
}
//site_config
$ip = $_SERVER['REMOTE_ADDR'];
$email_arr = array("" => "직접입력", "hanmail.net" => "hanmail.net", "daum.net" => "daum.net", "daum.com" => "daum.com", "naver.com" => "naver.com", "gmail.com" => "gmail.com", "paran.com" => "paran.com", "nate.com" => "nate.com", "yahoo.co.kr" => "yahoo.co.kr", "yahoo.com" => "yahoo.com", "hotmail.com" => "hotmail.com", "dreamwiz.com" => "dreamwiz.com", "chol.com" => "chol.com", "korea.com" => "korea.com", "freechal.com" => "freechal.com", "empal.com" => "empal.com", "hanafos.com" => "hanafos.com", "hanmir.com" => "hanmir.com", "hitel.net" => "hitel.net", "lycos.co.kr" => "lycos.co.kr", "netian.com" => "netian.com");
$agency_arr = array("SK" => "2800", "KT" => "2800", "LG" => "2800", "HL" => "2800");
$fujia_type = array("수신거부", "포토문자", "원북문자");
$onebook_type_arr = array("원북전체내용", "주제와요점", "저자이해", "저자의주장", "저술의도와 목적", "단락1", "단락2", "단락3");
$msg_flag_arr = array("3" => "수신불가", "2" => "없는번호", "1" => "번호변경");
$fileTypes = array('jpg', 'jpeg', 'gif', 'png', 'pdf', 'doc', 'xls', 'ppt');
$file_yx_arr = array("4831,4957");

$fl_arr = array("휴대폰관리" => "휴대폰관리", "문자발송" => "문자발송", "결제하기" => "결제하기", "사이트이용" => "사이트이용");
$iam_notice_arr = array("전체", "아이엠 업데이트", "교육", "홍보");
$deny_type_arr = array("A" => "자동", "B" => "수동");
//$mid1="obmms20151";//신용카드 //YmVFN2RtTldRR25zS2x2S055cnRxUT09
//$mid2="obmms20152";//정기과금	//T2ZJUkRxaE1IamtJMGhoTnlEMXlFdz09
//$mid3="IESobmms00";//에스크로	//Z0RpL2daakxEWWdoZ3RXMThHNVlTQT09
//$mid4="INIpayTest";//테스트	//SU5JTElURV9UUklQTEVERVNfS0VZU1RS
//$pc_pay_method=array("obmms20151"=>"신용카드");
$pc_pay_method = array("obmms20151" => "신용카드", "obmms20152" => "자동결제");
$sign_arr = array("INIpayTest" => "SU5JTElURV9UUklQTEVERVNfS0VZU1RS", "IESobmms00" => "Z0RpL2daakxEWWdoZ3RXMThHNVlTQT09", "obmms20151" => "YmVFN2RtTldRR25zS2x2S055cnRxUT09", "obmms20152" => "T2ZJUkRxaE1IamtJMGhoTnlEMXlFdz09");
$mobile_pay_method = array("onlyvbank" => "무통장입금", "wcard" => "신용카드");
$pay_type = array("Card" => "카드결제", "CARD" => "카드결제", "Auto_Card" => "자동결제", "MONTH" => "통장정기", "BANK" => "무통장", "VCard" => "V카드", "MONTH_Card" => "카드정기");
$is_chrome = preg_match('/Chrome/i', $_SERVER['HTTP_USER_AGENT']) ? "disabled" : "";
$pay_phone_status = array("N" => "결제대기", "Y" => "결제완료", "M" => "본인폰");
$pay_result_status = array("N" => "결제대기", "Y" => "결제완료", "C" => "해지완료", "A" => "후불결제", "E" => "기간만료");
$profile_colors = array("#99b433", "#00a300", "#1e7145", "#ff0097", "#9f00a7", "#7e3878", "#603cba", "#1d1d1d", "#00aba9", "#2d89ef", "#e3a21a", "#b91d47");
$profile_color = $_SESSION["profile_color"];
if ($profile_color == "") {
	$profile_color = $profile_colors[rand(0, count($profile_colors) - 1)];
	$_SESSION["profile_color"] = $profile_color;
}
if ($_REQUEST['one_no'] && strlen($_REQUEST['one_no']) < 4) {
	$cookie_name = "board_" . $_REQUEST['status'] . $_REQUEST['one_no'];
	if (!$_COOKIE[$cookie_name]) {
		setcookie($cookie_name, "ok", time() + 3600 * 24);
		$sql_view = "update tjd_board set view_cnt=view_cnt+1 where no='{$_REQUEST['one_no']}'";
		mysqli_query($self_con, $sql_view) or die(mysqli_error($self_con));
	}
	$sql_no = "select phone, email from tjd_board where no='{$_REQUEST['one_no']}'";
	$resul_no = mysqli_query($self_con, $sql_no);
	$row_no = mysqli_fetch_array($resul_no);
	$phone = explode("-", $row_no['phone']);
	$email = explode("@", $row_no['email']);
}
/* 
보안접속부분 추가
*/
$_SESSION['one_member_admin_id'] = "";
$admin_sql = "select mem_id from Gn_Admin where mem_id= '{$_SESSION['one_member_id']}'";
$admin_result = mysqli_query($self_con, $admin_sql);
if ($admin_result && mysqli_num_rows($admin_result) > 0) {
	$admin_row = mysqli_fetch_array($admin_result);
	$secure_sql = "select secure_connect from gn_conf";
	$secure_result = mysqli_query($self_con, $secure_sql);
	if ($secure_result && mysqli_num_rows($secure_result) > 0) {
		$secure_row = mysqli_fetch_array($secure_result);
		if ($secure_row[0] == 'Y') {
			$secure_sql = "select idx from gn_admin_allowip where mem_id='{$_SESSION['one_member_id']}' and ip='$ip'";
			$secure_result = mysqli_query($self_con, $secure_sql);
			$secure_row = mysqli_fetch_array($secure_result);
			if ($secure_row[0] != "")
				$_SESSION['one_member_admin_id'] = $_SESSION['one_member_id'];
		} else {
			$_SESSION['one_member_admin_id'] = $_SESSION['one_member_id'];
		}
	} else {
		$_SESSION['one_member_admin_id'] = $_SESSION['one_member_id'];
	}
}
if ($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == "test.kiam.kr") {
	$secure_sql = "select secure_connect from gn_conf";
	$secure_result = mysqli_query($self_con, $secure_sql);
	$secure_row = mysqli_fetch_array($secure_result);
	if ($secure_row[0] == 'Y') {
		$secure_sql = "select idx from gn_admin_allowip where mem_id='{$_SESSION['one_member_id']}' and ip='$ip'";
		$secure_result = mysqli_query($self_con, $secure_sql);
		$secure_row = mysqli_fetch_array($secure_result);
		if ($secure_row[0] == "")
			$_SESSION['one_member_subadmin_id'] = "";
	}
}

// 아이피 차단기능 추가
$admin_sql = "select idx from gn_block_ip where ip= '{$_SERVER['REMOTE_ADDR']}'";
$admin_result = mysqli_query($self_con, $admin_sql);
$admin_row = mysqli_fetch_array($admin_result);
if ($admin_row[0] != "") {
	$_SESSION['one_member_id'] = "";
	$_SESSION['iam_member_id'] = "";
	$_SESSION['one_member_admin_id'] = "";
	$_SESSION['one_member_subadmin_domain'] = "";
	$_SESSION['iam_member_subadmin_domain'] = "";
	$_SESSION['one_member_subadmin_id'] = "";
	$_SESSION['iam_member_subadmin_id'] = "";
	$_SESSION['one_mem_leb'] = "";
	$_SESSION['iam_member_leb'] = "";
	exit;
}
function number_format_youtube($n)
{
	if (strlen($n) < 4)
		return $n;
	else if (strlen($n) == 4)
		return substr($n, 0, 1) . "." . substr($n, 1, 2) . "천";
	else if (strlen($n) == 5)
		return substr($n, 0, 1) . "." . substr($n, 1, 2) . "만";
	else if (strlen($n) == 6)
		return substr($n, 0, 2) . "." . substr($n, 2, 1) . "만";
	else if (strlen($n) == 7)
		return substr($n, 0, 3) . "만";
	else if (strlen($n) == 8)
		return substr($n, 0, 1) . "," . substr($n, 1, 3) . "만";
	else if (strlen($n) == 9)
		return substr($n, 0, 1) . "," . substr($n, 1, 2) . "억";
	else
		return number_format($n);
}
function cross_image($url)
{
	global $cdn, $kiam_ssl;
	if (!strstr($url, "https")) {
		if (!strstr($url, "http"))
			return $cdn . $url;
		else {
			if (!strstr($url, "kiam") && !strstr($url, "gdimg"))
				return $kiam_ssl . "/lib/cross_image.php?src=" . urlencode($url);
			else
				return str_replace("http", "https", $url);
		}
	} else {
		return $url;
	}
}
function message_send($from, $to, $subject, $content, $return_msg)
{
	/******************** 인증정보 ********************/
	$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
	$sms['user_id'] = base64_encode("onlysms"); //SMS 아이디.
	$sms['secure'] = base64_encode("5248a8661ef2a3b97a39a710a1bf5b44"); //인증키
	$sms['msg'] = base64_encode(stripslashes($content));
	if ($_POST['smsType'] == "L") {
		$sms['subject'] =  base64_encode($subject);
	}

	$sphone1 = substr($from, 0, 3);
	$sphone2 = substr($from, 3, 4);
	$sphone3 = substr($from, 7, 4);
	$sms['rphone'] = base64_encode($to);
	$sms['sphone1'] = base64_encode($sphone1);
	$sms['sphone2'] = base64_encode($sphone2);
	$sms['sphone3'] = base64_encode($sphone3);
	$sms['rdate'] = base64_encode("");
	$sms['rtime'] = base64_encode("");
	$sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
	$sms['returnurl'] = base64_encode("");
	$sms['testflag'] = base64_encode("");
	$sms['destination'] = "";
	$returnurl = "";
	$sms['repeatFlag'] = base64_encode("");
	$sms['repeatNum'] = base64_encode("1");
	$sms['repeatTime'] = base64_encode("15");
	$sms['smsType'] = base64_encode("S"); // LMS일경우 L
	//$nointeractive = $_POST['nointeractive']; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략
	$nointeractive = ""; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략

	$host_info = explode("/", $sms_url);
	$host = $host_info[2];
	$path = $host_info[3] . "/" . $host_info[4];

	srand((float)microtime() * 1000000);
	$boundary = "---------------------" . substr(md5(rand(0, 32000)), 0, 10);
	// 헤더 생성
	$header = "POST /" . $path . " HTTP/1.0\r\n";
	$header .= "Host: " . $host . "\r\n";
	$header .= "Content-type: multipart/form-data, boundary=" . $boundary . "\r\n";
	// 본문 생성
	$data = "";
	foreach ($sms as $index => $value) {
		$data .= "--$boundary\r\n";
		$data .= "Content-Disposition: form-data; name=\"" . $index . "\"\r\n";
		$data .= "\r\n" . $value . "\r\n";
		$data .= "--$boundary\r\n";
	}
	$header .= "Content-length: " . strlen($data) . "\r\n\r\n";
	$fp = fsockopen($host, 80);
	if ($fp) {
		fputs($fp, $header . $data);
		$rsp = '';
		while (!feof($fp)) {
			$rsp .= fgets($fp, 8192);
		}
		fclose($fp);
		$msg = explode("\r\n\r\n", trim($rsp));
		$rMsg = explode(",", $msg[1]);
		$Result = $rMsg[0]; //발송결과
		$Count = $rMsg[1]; //잔여건수

		//발송결과 알림
		if ($Result == "success") {
			$alert = $return_msg . $to . "로 발송되었습니다.";
		} else if ($Result == "reserved") {
			$alert = "성공적으로 예약되었습니다.";
		} else if ($Result == "3205") {
			$alert = "잘못된 번호형식입니다.";
		} else if ($Result == "0044") {
			$alert = "스팸문자는발송되지 않습니다.";
		} else if ($Result == -101) {
			$alert = "발송정보가 있어 발송이 지연되고 있습니다.";
		} else {
			$alert = "[Error]" . $Result;
		}
	} else {
		$alert = "Connection Failed";
	}
	return $alert;
}
/*

    사용방법: IP 국가 조회
    - whois_ascc(API_KYE, IP)
    + '레지스트리' 및 '등록 국가 코드' Return('|' 구분)
*/
function whois_ascc($api_key, $assc_key)
{
	if (!$api_key && !$assc_key) {
		exit("Key Error!!");
	}

	// IP 토대로 국가 조회 기능
	$ch = curl_init();
	@curl_setopt($ch, CURLOPT_URL, "http://whois.kisa.or.kr/openapi/ipascc.jsp?query=" . $assc_key . "&key=" . $api_key . "&answer=xml");
	@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	@curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	$exec = curl_exec($ch);
	curl_close($ch);
	$xml = simplexml_load_string($exec);
	$registry = $xml->registry; #레지스트리(KRNIC이나 대륙별등록기관 혹은 특수 목적을 표기)를 value로 갖는다
	$countryCode = $xml->countryCode; #등록 국가 코드(특수 목적의 경우 'none'으로 표기)를 value로 갖는다.
	if ($registry && $countryCode) {

		//return $registry."|".$countryCode;
		return $countryCode;
	} // $registry && $countryCode end
} // whois_ascc
function generateRandomString($length = 10)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	if ($length == 32) {
		return $randomString;
	} else {
		global $self_con;
		$name_count_sql = "select count(idx) from Gn_Iam_Name_Card where card_short_url = '$randomString'";
		$name_count_result = mysqli_query($self_con, $name_count_sql);
		$name_count_row = mysqli_fetch_array($name_count_result);

		if ((int)$name_count_row[0]) {
			generateRandomString();
		} else {
			return $randomString;
		}
	}
}
function generateRandomCode($length = 10)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	if ($length == 32) {
		return $randomString;
	} else {
		global $self_con;
		$name_count_sql = "select count(idx) from Gn_Iam_Contents_Gwc where product_code = '$randomString'";
		$name_count_result = mysqli_query($self_con, $name_count_sql);
		$name_count_row = mysqli_fetch_array($name_count_result);

		if ((int)$name_count_row[0]) {
			generateRandomCode();
		} else {
			return $randomString;
		}
	}
}
//회원정보 내오기
function get_member($id, $column = "")
{
	global $self_con;
	$column = $column ? " mem_code , " . $column : " * ";
	$sql = "select $column from Gn_Member where mem_id='$id' ";
	$resul = mysqli_query($self_con, $sql);
	$row = mysqli_fetch_array($resul);
	if ($row['mem_code'])
		return $row;
}
function addWatermark($sourceFile, $watermarkText = NULL, $watermarkImage = NULL)
{
	if ($sourceFile == "")
		return false;
	if (strpos($sourceFile, "/") == 0)
		$sourceFile = substr($sourceFile, 1);
	// 원본 이미지 로드
	$dstFile = mb_substr($sourceFile, 0, mb_strrpos($sourceFile, "."));
	$ext = mb_substr($sourceFile, mb_strrpos($sourceFile, "."));
	$dstFile .= "wm" . $ext;
	$info = getimagesize($sourceFile);
	$type = $info[2];
	switch ($type) {
		case IMAGETYPE_JPEG:
			$image = imagecreatefromjpeg($sourceFile);
			break;
		case IMAGETYPE_PNG:
			$image = imagecreatefrompng($sourceFile);
			break;
		default:
			die("Unsupported image type.");
	}

	// 이미지 워터마크 추가
	if ($watermarkImage && file_exists($watermarkImage)) {
		$watermark = imagecreatefrompng($watermarkImage);
		$image_width = imagesx($image);
		$image_height = imagesy($image);
		$wx = imagesx($watermark);
		$wy = imagesy($watermark);
		$x_interval = 100;
		$y_interval = 100;
		for ($x = 0; $x < $image_width; $x += $x_interval) {
			for ($y = 0; $y < $image_height; $y += $y_interval) {
				imagecopy($image, $watermark, $x, $y, 0, 0, $wx, $wy);
			}
		}
	}
	// 텍스트 워터마크 추가
	if ($watermarkText) {
		$text_height = imagefontheight(5);
		$textColor = imagecolorallocatealpha($image, 13, 13, 13, 100); // 백색, 반투명
		$fontFile = '../../fonts/KoPubDotumLight.ttf'; // 글꼴 파일 경로 지정 필요
		$image_width = imagesx($image);
		$image_height = imagesy($image);
		$x_interval = 100;
		$y_interval = 100;
		for ($x = 0; $x < $image_width; $x += $x_interval) {
			for ($y = 0; $y < $image_height; $y += $y_interval) {
				imagettftext($image, 12, 0, $x + 10, $y + 5, $textColor, $fontFile, $watermarkText);
			}
		}
	}
	// 이미지 저장
	switch ($type) {
		case IMAGETYPE_JPEG:
			imagejpeg($image, $dstFile);
			break;
		case IMAGETYPE_PNG:
			imagepng($image, $dstFile);
			break;
	}

	// 리소스 해제
	imagedestroy($image);
	if (isset($watermark)) {
		imagedestroy($watermark);
	}
	return str_replace("../../", "", $dstFile);
}
$time = time();

// $sql_fujia_up="update Gn_Member set fujia_date1='' , fujia_date2='' where  unix_timestamp(fujia_date2) < $time and unix_timestamp(fujia_date2)<>'0'";
// mysqli_query($self_con,$sql_fujia_up);
/*$sql_pay_up = "update tjd_pay_result p left join Gn_Service s on p.buyer_id = s.mem_id left join Gn_Iam_Service i on p.buyer_id=i.mem_id
				set  end_status='N',s.status = 'N', i.status = 'N' where unix_timestamp(end_date) < $time and end_status='Y' ";*/
$sql_pay_up = "update tjd_pay_result set end_status='N',stop_yn='Y' where unix_timestamp(end_date) < $time and end_status='Y' ";
mysqli_query($self_con, $sql_pay_up);
$sql_num_up = "update Gn_MMS_Number set end_status='N' where unix_timestamp(end_date) < $time and end_status='Y' ";
mysqli_query($self_con, $sql_num_up);
if ($_SESSION['one_member_id']) {
	$member_1 = get_member($_SESSION['one_member_id']);
	if ($member_1['fujia_date2'] != "0000-00-00 00:00:00")
		$fujia_pay = true;

	$m_email_arr = explode("@", $member_1['mem_email']);
	$m_phone_arr = explode("-", $member_1['mem_phone']);
	$m_birth_arr = explode("-", $member_1['mem_birth']);

	$format_month = date("Y-m");
	$sql_format = "select idx,format_date,sendnum from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' order by idx desc ";
	$resul_format = mysqli_query($self_con, $sql_format);
	while ($row_format = mysqli_fetch_array($resul_format)) {
		if (!preg_match("/" . $format_month . "/i", $row_format['format_date'])) {
			$sql_format_u = "update Gn_MMS_Number set format_date=curdate(),cnt1=0,cnt2=0 where idx='{$row_format['idx']}' ";
			mysqli_query($self_con, $sql_format_u);
			$sql_d_result1 = "delete from Gn_MMS where result='1' and send_num='{$row_format['sendnum']}' and reservation < '$format_month'";
			mysqli_query($self_con, $sql_d_result1);
		}
	}

	$sql_cnt_s = "select idx,cnt1,cnt2,user_cnt from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' ";
	$resul_cnt_s = mysqli_query($self_con, $sql_cnt_s);
	while ($row_cnt_s = mysqli_fetch_array($resul_cnt_s)) {
		if (($row_cnt_s['cnt1'] == 10 && $row_cnt_s['cnt2'] == 20) || ($row_cnt_s['cnt1'] == 10 && $row_cnt_s['user_cnt'] > 200)) {
			//$sql_cnt_u=" update Gn_MMS_Number set user_cnt=0 where idx='$row_cnt_s['idx']' ";
			//mysqli_query($self_con,$sql_cnt_u);
		}
	}
}
if ($_SESSION['iam_member_id']) {
	$member_iam = get_member($_SESSION['iam_member_id']);
	if ($member_iam['fujia_date2'] != "0000-00-00 00:00:00")
		$fujia_pay = true;

	$iam_email_arr = explode("@", $member_iam['mem_email']);
	$iam_phone_arr = explode("-", $member_iam['mem_phone']);
	$iam_birth_arr = explode("-", $member_iam['mem_birth']);

	$format_month = date("Y-m");
	$sql_format = "select idx,format_date,sendnum from Gn_MMS_Number where mem_id='{$_SESSION['iam_member_id']}' order by idx desc ";
	$resul_format = mysqli_query($self_con, $sql_format);
	while ($row_format = mysqli_fetch_array($resul_format)) {
		if (!preg_match("/" . $format_month . "/i", $row_format['format_date'])) {
			$sql_format_u = "update Gn_MMS_Number set format_date=curdate(),cnt1=0,cnt2=0 where idx='{$row_format['idx']}' ";
			mysqli_query($self_con, $sql_format_u);
			$sql_d_result1 = "delete from Gn_MMS where result='1' and send_num='{$row_format['sendnum']}' and reservation < '$format_month'";
			mysqli_query($self_con, $sql_d_result1);
		}
	}

	$sql_cnt_s = "select idx,cnt1,cnt2,user_cnt from Gn_MMS_Number where mem_id='{$_SESSION['iam_member_id']}' ";
	$resul_cnt_s = mysqli_query($self_con, $sql_cnt_s);
	while ($row_cnt_s = mysqli_fetch_array($resul_cnt_s)) {
		if (($row_cnt_s['cnt1'] == 10 && $row_cnt_s['cnt2'] == 20) || ($row_cnt_s['cnt1'] == 10 && $row_cnt_s['user_cnt'] > 200)) {
			//$sql_cnt_u=" update Gn_MMS_Number set user_cnt=0 where idx='$row_cnt_s['idx']' ";
			//mysqli_query($self_con,$sql_cnt_u);
		}
	}
}
if (!$_SESSION['iam_member_id'] && !$_SESSION['one_member_id']) {
	if (!$_SESSION['guest']) {
		$guest = str_replace(".", "", $ip);
		$guest = substr($guest, 3, strlen($guest));
		$_SESSION['guest'] = "손님" . $guest;
	}
}
//타이틀부분
function return_radio_checkbox($jb_arr, $field, $field_arr)
{
	$arr1 = explode(",", $field_arr[$field]);
	$arr2 = array();
	foreach ($arr1 as $key => $v) {
		$v2 = $v || $v == '0' ? $jb_arr[$v] : "&nbsp;";
		array_push($arr2, $v2);
	}
	return implode(",", $arr2);
}
function return_nl($sl)
{
	$sl_all = explode("-", $sl);
	$age = date("Y") - $sl_all[0] + 1;
	if ($age < 5)
		return "A";
	else if ($age > 4 && $age < 8)
		return "B";
	else if ($age > 7 && $age < 15)
		return "C";
	else if ($age > 14 && $age < 18)
		return "D";
	else if ($age > 17 && $age < 20)
		return "E";
	else if ($age > 19 && $age < 23)
		return "F";
	else if ($age > 22 && $age < 30)
		return "G";
	else if ($age > 29 && $age < 40)
		return "H";
	else if ($age > 39 && $age < 50)
		return "I";
	else if ($age > 49 && $age < 60)
		return "J";
	else
		return "K";
}
function return_age($sl)
{
	$sl_all = explode("-", $sl);
	$age = date("Y") - $sl_all[0] + 1;
	return $age;
}
//페이지 넘기기
function page_f($page, $page2, $intPageCount, $frm)
{
	echo "<div style='height:45px; text-align:center;padding-top:10px;margin-top:10px;font-size:14px; word-spacing:5px;'>
				<div style='float:left;'>
					<span>";
	if ($page2 > 1) {
		echo  "<a href='javascript:void(0)' onClick=page_p(($page2-1)*10-9,$page2-1,$frm)><img src='/images/pre.gif' width='41' height='13' border='0' /></a>";
	}
	echo "</span><span style='width:150px; text-align:left;'>";
	for ($i = ($page2 * 10) - 9; $i <= $page2 * 10 && $i <= $intPageCount; $i++) {
		if ($i == $page) {
			echo "&nbsp;<a href='javascript:void(0)' class=\"bd_page bd_page_over\" onclick=page_p('$i','$page2',$frm)>$i</a>";
			continue;
		}
		echo "&nbsp;<a href='javascript:void(0)' class=\"bd_page\"  onclick=page_p('$i','$page2',$frm)>$i</a>";
	}
	echo "</span><span>";
	if ($page2 < $intPageCount / 10) {
		echo "&nbsp;<a href='javascript:void(0)' onClick=page_p($page2*10+1,$page2+1,$frm)><img src='/images/next.gif' border='0' /></a>";
	}
	echo "</span></div><div style='float:right;'>" . $page . "/" . $intPageCount . "</div><p style='clear:both;'></p></div>";
}
function page_f_deny($page, $page2, $intPageCount, $frm)
{
	echo "<div style='height:30px; text-align:center;padding-top:10px;margin-top:10px;font-size:14px; word-spacing:5px;'>
				<div style='float:left;'>
					<span>";
	if ($page2 > 1) {
		echo  "<a href='javascript:void(0)' onClick=page_p(($page2-1)*10-9,$page2-1,$frm)><img src='/images/pre.gif' width='41' height='13' border='0' /></a>";
	}
	echo "</span><span style='width:150px; text-align:left;'>";
	for ($i = ($page2 * 10) - 9; $i <= $page2 * 10 && $i <= $intPageCount; $i++) {
		if ($i == $page) {
			echo "&nbsp;<a href='javascript:void(0)' class=\"bd_page bd_page_over\" onclick=page_p('$i','$page2',$frm)>$i</a>";
			continue;
		}
		echo "&nbsp;<a href='javascript:void(0)' class=\"bd_page\"  onclick=page_p('$i','$page2',$frm)>$i</a>";
	}
	echo "</span><span>";
	if ($page2 < $intPageCount / 10) {
		echo "&nbsp;<a href='javascript:void(0)' onClick=page_p($page2*10+1,$page2+1,$frm)><img src='/images/next.gif' border='0' /></a>";
	}
	echo "</span></div><p style='clear:both;'></p></div>";
}
function sub_page_f($page, $page2, $intPageCount, $frm)
{
	echo "<div style='height:50px; text-align:center;padding-top:10px;margin-top:10px;font-size:14px; word-spacing:5px;'>
		<div style='float:left;'>
		<span>";
	if ($page2 > 1) {
		echo  "<a href='javascript:void(0)' onClick=sub_page_p(($page2-1)*10-9,$page2-1,$frm)><img src='/images/pre.gif' width='41' height='13' border='0' /></a>";
	}
	echo "</span><span style='width:150px; text-align:left;'>";
	for ($i = ($page2 * 10) - 9; $i <= $page2 * 10 && $i <= $intPageCount; $i++) {
		if ($i == $page) {
			echo "&nbsp;<a href='javascript:void(0)' class=\"bd_page bd_page_over\" onclick=sub_page_p('$i','$page2',$frm)>$i</a>";
			continue;
		}
		echo "&nbsp;<a href='javascript:void(0)' class=\"bd_page\"  onclick=sub_page_p('$i','$page2',$frm)>$i</a>";
	}
	echo "</span><span>";
	if ($page2 < $intPageCount / 10) {
		echo "&nbsp;<a href='javascript:void(0)' onClick=sub_page_p($page2*10+1,$page2+1,$frm)><img src='/images/next.gif' border='0' /></a>";
	}
	echo "</span></div><div style='float:right;'>" . $page . "/" . $intPageCount . "</div><p style='clear:both;'></p></div>";
}
function point_page_f($page, $page2, $intPageCount, $frm)
{
	echo "<div style='height:40px; text-align:center;padding-top:10px;margin-top:0;font-size:14px; word-spacing:5px;'>
		<div style='float:left;'>
		<span>";
	if ($page2 > 1) {
		echo  "<a href='javascript:void(0)' onClick=point_page_p(($page2-1)*10-9,$page2-1,$frm)><img src='/images/pre.gif' width='41' height='13' border='0' /></a>";
	}
	echo "</span><span style='width:150px; text-align:left;'>";
	for ($i = ($page2 * 10) - 9; $i <= $page2 * 10 && $i <= $intPageCount; $i++) {
		if ($i == $page) {
			echo "&nbsp;<a href='javascript:void(0)' class=\"bd_page bd_page_over\" onclick=point_page_p('$i','$page2',$frm)>$i</a>";
			continue;
		}
		echo "&nbsp;<a href='javascript:void(0)' class=\"bd_page\"  onclick=point_page_p('$i','$page2',$frm)>$i</a>";
	}
	echo "</span><span>";
	if ($page2 < $intPageCount / 10) {
		echo "&nbsp;<a href='javascript:void(0)' onClick=point_page_p($page2*10+1,$page2+1,$frm)><img src='/images/next.gif' border='0' /></a>";
	}
	echo "</span></div><div style='float:right;'>" . $page . "/" . $intPageCount . "</div><p style='clear:both;'></p></div>";
}
//페이지 넘기기
function page_ajax($page, $page2, $intPageCount, $frm)
{
	echo "<div style='height:50px; text-align:center;padding-top:10px;margin-top:30px;font-size:14px; word-spacing:5px;'>
		<div style='float:left;'>
		<span>";
	if ($page2 > 1) {
		echo  "<a href='javascript:void(0)' class=\"paging\" data-page='($page2-1)*10-9' ><img src='/images/pre.gif' width='41' height='13' border='0' /></a>";
	}
	echo "</span><span style='width:150px; text-align:left;'>";
	for ($i = $page2 * 10 - 9; $i <= $page2 * 10 && $i <= $intPageCount; $i++) {
		if ($i == $page) {
			echo "&nbsp;<a href='javascript:void(0)' class=\"bd_page bd_page_over paging\" data-page='$i'>$i</a>";
			continue;
		}
		echo "&nbsp;<a href='javascript:void(0)' class=\"bd_page  paging\"  data-page='$i'>$i</a>";
	}
	echo "</span><span>";
	if ($page2 < $intPageCount / 10) {
		echo "&nbsp;<a href='javascript:void(0)' class=\"paging\" data-page='$page2*10+1' ><img src='/images/next.gif' border='0' /></a>";
	}
	echo "</span></div><div style='float:right;'>" . $page . "/" . $intPageCount . "</div><p style='clear:both;'></p></div>";
}
//이미지 줄이기
function thumbnail($file, $fileTo, $width = "", $max_width = "", $height = "", $max_height = "")
{
	$file_ext = strtolower(substr(strrchr($file, "."), 1));
	switch ($file_ext) {
		case "jpg":
			$src_im = imagecreatefromjpeg($file);
			break;
		case "jpeg":
			$src_im = imagecreatefromjpeg($file);
			break;
		case "gif":
			$src_im = imagecreatefromgif($file);
			break;
		case "png":
			$src_im = imagecreatefrompng($file);
			break;
	}
	$bl = imagesx($src_im) / imagesy($src_im);
	if ($width && !$height) {
		$width = imagesx($src_im) < $width ? imagesx($src_im) : $width;
		$height = $width / $bl;
		if ($max_height) {
			if ($height > $max_height) {
				$height = $max_height;
				$width = $height * $bl;
			}
		}
	}
	if ($height && !$width) {
		$height = imagesy($src_im) < $height ? imagesy($src_im) : $height;
		$width = $height * $bl;
		if ($max_width) {
			if ($width > $max_width) {
				$width = $max_width;
				$height = $width / $bl;
			}
		}
	}
	if ($height && $width) {
		$height = imagesy($src_im) < $height ? imagesy($src_im) : $height;
		$width = imagesx($src_im) < $width ? imagesx($src_im) : $width;
	}
	$dst_im = imagecreatetruecolor($width, $height);
	imagecopyresampled($dst_im, $src_im, 0, 0, 0, 0, $width, $height, imagesx($src_im), imagesy($src_im));
	$file_ext = strtolower(substr(strrchr($fileTo, "."), 1));
	if ($file_ext == "jpg" || $file_ext == "jpeg")
		return imagejpeg($dst_im, $fileTo);
	else if ($file_ext == "gif")
		return imagegif($dst_im, $fileTo);
	else if ($file_ext == "png")
		return imagepng($dst_im, $fileTo);
}
//동시접속 제한
function user_session_check($no)
{
	global $sess_path;
	$dirlist = dir($sess_path);
	while (($file = $dirlist->read()) !== false) {
		if ($file != "." && $file != "..") {
			$fread = fopen($sess_path . "/" . $file, "r");
			$str = fread($fread, 100);
			$strlist = explode(";", $str);
			foreach ($strlist as $key => $v) {
				$strlist2 = explode("|", $strlist[$key]);
				if ($strlist2[0] == "one_member_id") {
					$strlist2 = explode(":", $strlist2[1]);
					if ($strlist2[2] == "\"$no\"")
						unlink($dirlist->path . DIRECTORY_SEPARATOR . $file);
				}
			}
		}
	}
	$dirlist->close();
}
//현재접속자
$current_id = array();
function return_current_id()
{
	global $sess_path, $current_id;
	$dirlist = dir($sess_path);
	while (($file = $dirlist->read()) !== false) {
		if ($file != "." && $file != "..") {
			$fread = fopen($sess_path . "/" . $file, "r");
			$str = fread($fread, 100);
			$strlist = explode(";", $str);
			foreach ($strlist as $key => $v) {
				$strlist2 = explode("|", $strlist[$key]);
				if ($strlist2[0] == "one_member_id" || $strlist2[0] == "guest") {
					$strlist2 = explode(":", $strlist2[1]);
					$id = str_replace('"', '', $strlist2[2]);
					if (!in_array($id, $current_id))
						array_push($current_id, $id);
				}
			}
		}
	}
	$dirlist->close();
}
function sql_query($sql, $error = TRUE)
{
	global $self_con;
	if ($error)
		$result = @mysqli_query($self_con, $sql) or die("<p>$sql<p>" . mysqli_errno($self_con) . " : " .  mysqli_error($self_con) . "<p>error file : {$_SERVER['PHP_SELF']}");
	else
		$result = @mysqli_query($self_con, $sql);
	return $result;
}
function sql_fetch($sql, $error = TRUE)
{
	$result = sql_query($sql, $error);
	$row = sql_fetch_array($result);
	return $row;
}
function sql_fetch_array($result)
{
	$row = @mysqli_fetch_assoc($result);
	return $row;
}
function sql_free_result($result)
{
	return mysqli_free_result($result);
}
function sql_password($value)
{
	$value = md5($value);
	//   $row = sql_fetch(" select MD5('$value') as pass ");
	//    return $row[pass];
	return $value;
}
function opb_text($db_value1, $db_value2, $db_value)
{
	$view_ok = strip_tags($db_value);
	$view_ok = str_replace("&nbsp;", "", $view_ok);
	$view_ok = str_replace(" ", "", $view_ok);
	if ($view_ok) {
		$db_value = nl2br($db_value);
		$db_value = str_replace("\"", "'", $db_value);
		$db_value = str_replace("[|*|]", "<br>", $db_value);
		$db_value = strip_tags($db_value, "<br>");
		$db_value = str_replace("<br><br>", "<br/>", $db_value);
		$db_value = str_replace("<br/><br/>", "<br/>", $db_value);
		$db_value = str_replace("<br /><br />", "<br/>", $db_value);
		$db_value = str_replace("<br/><br/>", "<br/>", $db_value);
		$return_value = "<br>";
		if ($db_value1) $return_value .= "{$db_value1}<br>";
		if ($db_value2) $return_value .= "{$db_value2}<br>";
		$return_value .= "{$db_value}";
	}
	return $return_value;
}
function in_array_fun($v, $arr)
{
	if ($v == "" || $v == 'false' || $v == 'true')
		return false;
	else
		return in_array($v, $arr);
}
//자르기
function str_substr($str, $star, $end, $charset = "utf-8")
{
	if (strlen($str) > $end)
		return mb_strcut($str, $star, $end, $charset) . "..";
	else
		return $str;
}

//폰번 검증
function check_cellno($str)
{
	// 넘어온 값의 숫자만 가지고 패턴 검사 
	$no = preg_replace("/[^0-9]/i", "", $str);

	if (substr($no, 0, 3) == "050" && (strlen($no) == 11 || strlen($no) == 12)) {
		if (substr($no, 0, 3) == "010" && strlen($no) < 12)
			return false;
		else
			return true;
	} else {
		if (preg_match("/^01[0-9]{8,9}$/", $no))
			return true;
		else
			return false;
	}
	/*
    if(substr($no,0,3) == "050" && (strlen($no) == 11 || strlen($no) == 12)){
    	return true;
    }else{
		// 0다음 1다음 016789 (예011)에 숫자3,4자리가 오고 마지막 자리가 4자리 인 패턴검사 
	    $pattern = "/([0]{1}[1]{1}[016789]{1})([0-9]{3,4})([0-9]{4})/"; 
	    // 패턴이 있으면 true 
	    $result = preg_match_all($pattern, $no, $matches); 

	    if(!$result || (substr($no,0,3) == "010" && strlen($no) != 11)){
	    	return false;
	    }else{
	    	return true;
	    }
    }
    */
}


// 페이징
//페이징 네비게이션 생성
function drawPagingAdminNavi($totalCnt, $nowPage, $pageCnt = "20", $naviSize = "10", $scriptName = "goPage")
{
	$pageStr = "";
	$firstPG = "";
	$finalPG = "";
	$startPG = "";
	$endPG	 = "";

	$totalPage = ceil($totalCnt / $pageCnt);
	$total_block = ceil($totalPage / $naviSize);
	$block = ceil($nowPage / $naviSize);

	$first_page = ($block - 1) * $naviSize;
	$last_page = $block * $naviSize;
	$go_page = $first_page + 1;

	$prevPG = $first_page;
	$nextPG = $last_page + 1;

	if ($totalPage > 1) {
		$firstPG = 1;
		$finalPG = $totalPage;
	}

	if ($total_block <= $block) $last_page = $totalPage;
	$pageStr .= '<div class="dataTables_paginate paging_simple_numbers" id="list_paginate">
                        <ul class="pagination">';

	//이전 페이지 block 보기
	if ($totalPage > 1) {

		if ($block > 1)
			$prev_b = "<li class='paginate_button previous' id='list_previous'><a href=\"javascript:" . $scriptName . "('" . $prevPG . "')\" >Prev</a></li>";
		else
			$prev_b = "<li class='paginate_button previous disabled' id='list_previous'><a href=\"javascript:void(0)\">Prev</a></li>";

		//다음 페이지 block 보기
		if ($block < $total_block)
			$next_b = "<li class='paginate_button next' id='list_next'><a href=\"javascript:" . $scriptName . "('" . $nextPG . "')\" >Next</a></li>";
		else
			$next_b = "<li class='paginate_button next' id='list_next'><a href=\"javascript:void(0)\">Next</a></li>";

		$pageStr .= $prev_b;

		for ($go_page = $go_page; $go_page <= $last_page; $go_page++) {
			if ($nowPage == $go_page) $pageStr .= "<li class='paginate_button active'><a href='#'>" . $go_page . "</a></li>";
			else  $pageStr .= "<li class='paginate_button'><a href=\"javascript:" . $scriptName . "('" . $go_page . "')\">" . $go_page . "</a></li>";
		}
		$pageStr .= $next_b;
	}
	$pageStr .= '                        </ul>
                   </div>';
	return $pageStr;
}
function drawPagingAdminNavi_iama($totalCnt, $nowPage, $pageCnt = "20", $naviSize = "10", $scriptName = "goPage", $list_type = "")
{
	$pageStr = "";
	$firstPG = "";
	$finalPG = "";
	$startPG = "";
	$endPG	 = "";

	$totalPage = ceil($totalCnt / $pageCnt);
	$total_block = ceil($totalPage / $naviSize);
	$block = ceil($nowPage / $naviSize);

	$first_page = ($block - 1) * $naviSize;
	$last_page = $block * $naviSize;
	$go_page = $first_page + 1;

	$prevPG = $first_page;
	$nextPG = $last_page + 1;

	if ($totalPage > 1) {
		$firstPG = 1;
		$finalPG = $totalPage;
	}

	if ($total_block <= $block) $last_page = $totalPage;
	$pageStr .= '<div class="dataTables_paginate paging_simple_numbers" id="list_paginate">
                        <ul class="pagination">';

	//이전 페이지 block 보기
	if ($totalPage > 1) {

		if ($block > 1)
			$prev_b = "<li class='paginate_button previous' id='list_previous'><a href=\"javascript:" . $scriptName . "('" . $prevPG . "', '" . $list_type . "')\" >Prev</a></li>";
		else
			$prev_b = "<li class='paginate_button previous disabled' id='list_previous'><a href=\"javascript:void(0)\">Prev</a></li>";

		//다음 페이지 block 보기
		if ($block < $total_block)
			$next_b = "<li class='paginate_button next' id='list_next'><a href=\"javascript:" . $scriptName . "('" . $nextPG . "', '" . $list_type . "')\" >Next</a></li>";
		else
			$next_b = "<li class='paginate_button next' id='list_next'><a href=\"javascript:void(0)\">Next</a></li>";

		$pageStr .= $prev_b;

		for ($go_page = $go_page; $go_page <= $last_page; $go_page++) {
			if ($nowPage == $go_page) $pageStr .= "<li class='paginate_button active'><a href='#'>" . $go_page . "</a></li>";
			else  $pageStr .= "<li class='paginate_button'><a href=\"javascript:" . $scriptName . "('" . $go_page . "', '" . $list_type . "')\">" . $go_page . "</a></li>";
		}
		$pageStr .= $next_b;
	}
	$pageStr .= '                        </ul>
                   </div>';
	return $pageStr;
}
// add Cooper 로그 추가
function cell_change_log($now_num, $old_num)
{
	global $self_con;
	global $_SESSION;
	/*
    DESC : STEP 1.변경 로그에 정보 있는지 확인 .(Gn_MMS_Receive_Change_Log)
           STEP 2.변경 로그에 없는 정보일시 변경 로그에 정보를 누적
           STEP 3.해당 사용자의 주소록 디비변경.(Gn_MMS_Receive)
           
    */
	if (check_cellno($now_num) === false || check_cellno($old_num) === false) {
		$return_result['code'] = false;
		$return_result['msg'] = "번호 형식에 문제가 있습니다.";
		return $return_result;
	}

	// [새 번호]가 변경로그에 있는지 확인
	$query = "select now_num, old_nums from Gn_MMS_Receive_Change_Log where old_nums like '$now_num%'";
	$result = mysqli_query($self_con, $query);
	$row = mysqli_fetch_array($result);
	if ($row[0]) {
		$return_result['code'] = false;
		$return_result['msg'] = "신규 번호가 이미 변경된 로그가있습니다.";
		return $return_result;
	}

	// [과거 번호]가 변경로그에 있는지 확인
	$query = "select now_num, old_nums from Gn_MMS_Receive_Change_Log where old_nums like '$old_num%'";
	$result = mysqli_query($self_con, $query);
	$row = mysqli_fetch_array($result);
	if ($row[0]) {
		$return_result['code'] = false;
		$return_result['msg'] = "과거 번호가 이미 변경된 로그가있습니다.";
		return $return_result;
	}

	// [새 번호] 변경 로그에 존재하는지 확인
	$query = "select now_num, old_nums from Gn_MMS_Receive_Change_Log where now_num = '$now_num'";
	$result = mysqli_query($self_con, $query);
	$row = mysqli_fetch_array($result);

	if ($row[0]) {
		// 변경 로그에 새 번호로 있을 경우 과거 번호로 변경후 
		$return_result['code'] = false;
		$return_result['msg'] = "이미 변경된 로그가있습니다.";
		return $return_result;
	}

	// [과거 번호] 변경 로그에 존재하는지 확인
	$query = "select now_num, old_nums from Gn_MMS_Receive_Change_Log where now_num = '$old_num'";
	$result = mysqli_query($self_con, $query);
	$row = mysqli_fetch_array($result);

	// 디비에 저장
	if ($row[0]) {
		// 업데이트
		$query = "update Gn_MMS_Receive_Change_Log set old_nums=concat(old_nums,',', '$old_num'), now_num='$now_num' where now_num='$old_num'";
		mysqli_query($self_con, $query);

		// 주소록 정보 변경. Gn_MMS_Receive
		// 주소록 정보 변경. 사용자 로그인 안되어있기때문에 사용자 정보 뺌.
		$query = "update Gn_MMS_Receive set recv_num='$now_num' where 1=1 and recv_num='$old_num'";
		mysqli_query($self_con, $query);

		// 주소록 데이터 변경  sm_data
		$query = "update sm_data set msg_url='$now_num' where msg_url='$old_num'";
		mysqli_query($self_con, $query);
	} else {
		// 입력
		$query = "insert into Gn_MMS_Receive_Change_Log set old_nums='$old_num', now_num='$now_num'";
		mysqli_query($self_con, $query);

		// 주소록 정보 변경. 사용자 로그인 안되어있기때문에 사용자 정보 뺌.
		$query = "update Gn_MMS_Receive set recv_num='$now_num' where 1=1 and recv_num='$old_num'";
		mysqli_query($self_con, $query);

		// 주소록 데이터 변경  sm_data
		$query = "update sm_data set msg_url='$now_num' where msg_url='$old_num'";
		mysqli_query($self_con, $query);
	}
	return true;
}
function gcUpload($file_name, $file_tmp_name, $file_size, $folder, $filename)
{
	global $gConf;
	//$file_name = $file["name"];
	//$file_tmp_name = $file['tmp_name'];
	//$file_size = $file['size'];
	$file_type = explode(".", $file_name);
	$file_type_size = count($file_type);
	$file_ext = $file_type[$file_type_size - 1];

	$newName = $filename;

	//$gConf['board_data'] = "/data";
	$gConf['board_data'] = "";

	//echo "!".$file_tmp_name."<BR>";
	//echo $_SERVER['DOCUMENT_ROOT'].$gConf['board_data']."/$folder/$file_name";
	move_uploaded_file($file_tmp_name, $_SERVER['DOCUMENT_ROOT'] . "/$folder/$file_name") or die('파일1 Upload에 실패했습니다.');
	rename($_SERVER['DOCUMENT_ROOT'] . $gConf['board_data'] . "/$folder/$file_name", $_SERVER['DOCUMENT_ROOT'] . $gConf['board_data'] . "/$folder/$newName") or die('파일 rename에 실패했습니다.');
	$path = $gConf['board_data'] . "/$folder";
	return $path . "/" . $newName;
}

function gcUploadRename($file_name, $file_tmp_name, $file_size, $folder)
{
	global $gConf;
	if ($file_name) {
		$file_type = explode(".", $file_name);
		$file_type_size = count($file_type);
		$file_ext = $file_type[$file_type_size - 1];
		$newName = time() . "_" . sprintf("%04d", rand(1, 9999)) . "." . $file_ext;
		//echo $_SERVER['DOCUMENT_ROOT'].$gConf['board_data']."/$folder/$file_name";
		move_uploaded_file($file_tmp_name, $_SERVER['DOCUMENT_ROOT'] . $gConf['board_data'] . "/$folder/$file_name") or die('파일1 Upload에 실패했습니다.');
		rename($_SERVER['DOCUMENT_ROOT'] . $gConf['board_data'] . "/$folder/$file_name", $_SERVER['DOCUMENT_ROOT'] . $gConf['board_data'] . "/$folder/$newName") or die('파일 rename에 실패했습니다.');
		$path = $gConf['board_data'] . "/$folder";
		return "" . $path . "/" . $newName;
	} else {
		return "";
	}
}
function uploadFTP($file)
{
	$ftp_user_name = "obmms";
	$ftp_user_pass = "onlyone123!";
	// open some file for reading
	$fp = fopen($file, 'r');
	$ftp_server = "goodhow.com";
	// set up basic connection
	$conn_id = ftp_connect($ftp_server, 8821);
	// login with username and password
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
	ftp_pasv($conn_id, true);
	// try to upload $file
	$file = iconv("UTF-8", "EUC-KR", $file);
	if (ftp_fput($conn_id, $file, $fp, FTP_BINARY)) {
		//echo "Successfully uploaded $file\n";
	} else {
		//echo "There was a problem while uploading $file\n";
	}
	// close the connection and the file handler
	ftp_close($conn_id);
	fclose($fp);
}

function cut_str($str, $len, $suffix = "…")
{
	$arr_str = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
	$str_len = count($arr_str);

	if ($str_len >= $len) {
		$slice_str = array_slice($arr_str, 0, $len);
		$str = join("", $slice_str);

		return $str . ($str_len > $len ? $suffix : '');
	} else {
		$str = join("", $arr_str);
		return $str;
	}
}

/* 문자전송함수 
type값을 다음과 같이 설정할수 있다.
1: 디폴트(현재 이용하지 않음)
2: 오토회원가입
3: 스텝문자 
4: 이벤트
5: 셀폰
6: 데일리 문자
7: app_check_process
8: 기존고객
9: 콜백
*/
function sendmms($type, $userid, $sendnum, $recvnum, $rserv_time, $title, $content, $img, $img1, $img2, $send_agreement_yn, $sms_idx = "", $sms_detail_idx = "", $request_idx = "", $gd_id = "", $send_deny_msg = "", $or_id = "")
{
	if ($userid == "" || $sendnum == "" || $recvnum == "") {
		return "fail";
	}
	global $self_con;
	global $HTTP_HOST;
	$query = "select * from Gn_MMS_Number where mem_id='$userid' and sendnum='$sendnum'";
	$result = mysqli_query($self_con, $query);
	$row = mysqli_fetch_array($result);
	if ($row['pkey'] == "")
		return "fail";

	//초기화 진행
	$send_go_num = array();
	$send_go_num[0] = $sendnum;
	// $send_num = array();
	// $send_num[0] = $recvnum;	

	$ch_mms = curl_init();

	//발송폰번호배열
	$fields['user_id'] = $userid;
	$fields['send_go_num'] = $send_go_num;
	//수신폰번호배열
	$fields['send_num'] = $recvnum;
	//예약발송시간
	if ($rserv_time != "")
		$fields['reserv_time'] = $rserv_time;
	//타이틀
	$fields['send_title'] = $title;
	//텍스트
	$fields['send_txt'] = $content;
	//첨부이미지	
	$fields['send_img'] = $img;
	$fields['send_img1'] = $img1;
	$fields['send_img2'] = $img2;
	//스텝문자 idx, 이벤트요청 idx
	if ($sms_idx != "")
		$fields['sms_idx'] = $sms_idx;
	if ($sms_detail_idx != "")
		$fields['sms_detail_idx'] = $sms_detail_idx;
	if ($request_idx != "")
		$fields['request_idx'] = $request_idx;
	if ($gd_id != "")
		$fields['gd_id'] = $gd_id;
	if ($or_id != "")
		$fields['or_id'] = $or_id;
	//수신거부
	$fields['send_deny_msg'] = $send_deny_msg;
	//동의상태
	$fields['send_agreement_yn'] = $send_agreement_yn;
	//제한값들
	$send_go_user_cnt = array();
	$send_go_user_cnt[0] = $row['user_cnt'];
	$fields['send_go_user_cnt'] = $send_go_user_cnt;
	$send_go_max_cnt = array();
	$send_go_max_cnt[0] = $row['daily_limit_cnt_user'];
	$fields['send_go_max_cnt'] = $send_go_max_cnt;
	$send_go_memo2 = array();
	$send_go_memo2[0] = $row['memo2'];
	$fields['send_go_memo2'] = $send_go_memo2;
	$send_go_cnt1 = array();
	$send_go_cnt1[0] = $row['cnt1'];
	$fields['send_go_cnt1'] = $send_go_cnt1;
	$send_go_cnt2 = array();
	$send_go_cnt2[0] = $row['cnt2'];
	$fields['send_go_cnt2'] = $send_go_cnt2;
	//문자발송타입
	$fields['send_type'] = $type;
	//기타
	$fields['key'] = "sendkey2022";
	$fields['send_chk'] = "";
	$fields['send_save_mms'] = "";
	$fields['send_agree_msg'] = "N";
	$fields['send_deny_wushi_0'] = "ok";
	$fields['send_deny_wushi_1'] = "ok";
	$fields['send_deny_wushi_2'] = "";
	$fields['send_deny_wushi_3'] = "";
	$fields['send_ssh_check'] = "";
	$fields['send_ssh_check2'] = "";
	$fields['send_ssh_check3'] = "";
	$fields['send_onebook_status'] = "N";
	$fields['send_go_remain_cnt'] = "";
	$fields['send_cnt'] = 1;
	$fields['free_use'] = "N";
	//딜레이
	$fields['send_delay'] = 5;
	$fields['send_delay2'] = 15;
	$fields['send_close'] = 24;

	$headers = array('Accept: application/json, text/plain, */*', 'Cache_Control: no-cache', 'content-type:multipart/form-data;');
	//$headers = array('Accept: application/json, text/plain, */*', 'Cache_Control: no-cache', 'content-type:application/json;');
	//curl_setopt($ch_mms, CURLOPT_URL, "https://kiam.kr/ajax/heineken.php");
	curl_setopt($ch_mms, CURLOPT_URL, "https://nm.kiam.kr/ajax/heineken.php");
	curl_setopt($ch_mms, CURLOPT_POST, true);
	curl_setopt($ch_mms, CURLOPT_HTTPHEADER, $headers );
	curl_setopt($ch_mms, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch_mms, CURLOPT_POSTFIELDS, http_build_query($fields));
	//curl_setopt($ch_mms, CURLOPT_POSTFIELDS, json_encode($fields));
	//curl_setopt($ch_mms, CURLOPT_VERBOSE, true);

	$mms_result = curl_exec($ch_mms);
	if ($mms_result === FALSE) {
		//die(curl_error($ch_mms));
		$ret = "fail=>".curl_error($ch_mms);
	} else {
		$result = json_decode($mms_result);
		$ret = "success=>".$result->msg;
	}
	curl_close($ch_mms);
	return $ret;
}

function sendemail($time, $to, $from, $subject, $body, $file = "", $gd_id = "", $cc_mail = false, $bcc_mail = false)
{
	if ($_SESSION['one_member_id'] == "")
		return;
	global $self_con;
	if ($time != "") {
		$now_date = date("Y-m-d H:i:s");
		if ($now_date < $time) {
			$query = "insert into gn_mail set mem_id='{$_SESSION['one_member_id']}',
												sender='$from',
												receiver='$to',
												title='$subject',
												content='$body',
												file='$file',
												reservation='$time',
												gd_id='$gd_id',
												reg_date=now()";
			mysqli_query($self_con, $query);
			return;
		}
	}

	$config = array(
		'host' => 'smtp.cafe24.com',
		'port' => 587,
		'smtp_id' => 'admin@ilearning11.cafe24.com',
		'smtp_pw' => '5614614a!@',
		'debug' => 1,
		'charset' => 'utf-8',
		'ctype' => 'text/plain'
	);
	$sendmail = new Sendmail($config);
	if ($file != "") {
		$realpath = $_SERVER['DOCUMENT_ROOT'] . "/" . $file;
		$name_arr = explode("__", $realpath);
		if (count($name_arr) > 1)
			$name = $name_arr[1];
		else {
			$name_arr = explode("/", $realpath);
			$name = $name_arr[count($name_arr) - 1];
		}
		$ctype = "application/octet-stream"; /* 첨부파일 추가 */
		$sendmail->attach($realpath, $name, $ctype);
	}
	$sendmail->send_mail($to, $from, $subject, $body, $cc_mail, $bcc_mail);
}

function get_date_time($con_time)
{
	$str_con_time = strtotime($con_time);
	$cur_time = time();
	$time_com_15min = time() - (900);
	$cur_time_com_15min = date("Y-m-d H:i:s", $time_com_15min);

	$time_com_1hour = time() - (3600);
	$cur_time_com_1hour = date("Y-m-d H:i:s", $time_com_1hour);

	$cur_date_today = date("Y-m-d") . " 00:00:00";
	$str_today = strtotime($cur_date_today);

	$date_yesterday = date("Y-m-d", $str_today - 86400);
	$date_doub_yesterday = date("Y-m-d", $str_today - 86400 * 2);
	$date_1year = date("Y", $str_today);

	if ($cur_time_com_15min <= $con_time) { //15분 이내
		return "방금";
	} else if ($cur_time_com_15min > $con_time && $con_time >= $cur_time_com_1hour) { //15분~1시간 이내
		$time_hour_in = $cur_time - $str_con_time;
		return date("i", $time_hour_in) . " 분";
	} else if ($cur_time_com_1hour > $con_time && $con_time >= $cur_date_today) { //1시간이후~오늘 00시 이내
		// $time_today_in = $cur_time - $str_con_time;
		if (date("ha", $str_con_time) == "12am") {
			$con_hour = 0;
		} else {
			$con_hour = (int)date("h", $str_con_time);
		}
		if (date("ha", $cur_time) == "12am") {
			$cur_hour = 0;
		} else {
			$cur_hour = (int)date("h", $cur_time);
		}
		$hour = abs($cur_hour - $con_hour);
		return $hour . " 시간";
		// return date("i", $time_today_in)." 시간";
	} else if ($cur_date_today > $con_time && $con_time > $date_yesterday) { //어제 올린 콘텐츠
		if (date("a", $str_con_time) == "am") {
			$half = "오전";
		} else {
			$half = "오후";
		}
		return "어제 " . $half . date(" h:i", $str_con_time);
	} else if ($date_yesterday > $con_time && $con_time > $date_doub_yesterday) { //그제 올린 콘텐츠
		if (date("a", $str_con_time) == "am") {
			$half = "오전";
		} else {
			$half = "오후";
		}
		return "그제 " . $half . date(" h:i", $str_con_time);
	} else if ($date_doub_yesterday > $con_time && $con_time > $date_1year) { //올해 이내
		if (date("a", $str_con_time) == "am") {
			$half = "오전";
		} else {
			$half = "오후";
		}
		return date("m월 d일 ", $str_con_time) . $half . date(" h:i", $str_con_time);
	} else {
		return date("Y년 m월 d일", $str_con_time);
	}
}

function get_join_link($host, $recommend = "", $type = "\iam")
{
	global $self_con;
	$sql_auto_service = "select auto_join_event_idx from Gn_Iam_Service where sub_domain = '{$host}'";
	$res_auto_service = mysqli_query($self_con, $sql_auto_service);
	$row_auto_service = mysqli_fetch_array($res_auto_service);

	if ($row_auto_service['auto_join_event_idx']) {
		$sql_event_link = "select short_url from Gn_event where event_idx={$row_auto_service['auto_join_event_idx']}";
		$res_event_link = mysqli_query($self_con, $sql_event_link);
		$row_event_link = mysqli_fetch_array($res_event_link);
		if ($row_event_link['short_url'] != "") {
			$join_link = $row_event_link['short_url'];
		} else {
			//$join_link = "/iam/join.php";
			$join_link = $type . "/join.php";
		}
	} else {
		//$join_link = "/iam/join.php";
		$join_link = $type . "/join.php";
	}
	if ($recommend != "") {
		if (strstr($join_link, "?"))
			$join_link .= "&recommend_id=$recommend";
		else
			$join_link .= "?recommend_id=$recommend";
	}
	if ($type != "") {
		if (strstr($join_link, "?"))
			$join_link .= "&join_type=iam";
		else
			$join_link .= "?join_type=iam";
	}
	return $join_link;
}

function set_service_mem_cnt($mem_id, $mem_cnt)
{
	// $sql_set_mem_cnt = "update Gn_Iam_Service set mem_cnt='{$mem_cnt}' where mem_id='{$mem_id}'";
	// mysqli_query($self_con,$sql_set_mem_cnt);
	global $self_con;
	$sql_seed_point = "update Gn_Member set mem_point=mem_point+1000000 where mem_id='{$mem_id}'";
	mysqli_query($self_con, $sql_seed_point);

	$sql_data = "select mem_phone, mem_point, mem_cash, mem_name from Gn_Member where mem_id='{$mem_id}'";
	$res_data = mysqli_query($self_con, $sql_data);
	$row_data = mysqli_fetch_array($res_data);

	$sql_res = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_id}', buyer_tel='{$row_data['mem_phone']}', item_name='씨드포인트충전', item_price=1000000, pay_percent=90, current_point={$row_data['mem_point']}, current_cash={$row_data['mem_cash']}, pay_status='Y', VACT_InputName='{$row_data['mem_name']}', type='buy', seller_id='{$mem_id}', pay_method='결제씨드충전', pay_date=now(), point_val=1";

	mysqli_query($self_con, $sql_res);
}

function check_token($mem_id, $token)
{
	global $self_con;
	$sql_chk = "select mem_token from Gn_Member where mem_id='{$mem_id}' and mem_token='{$token}'";
	$res_chk = mysqli_query($self_con, $sql_chk);
	$row_chk = mysqli_fetch_array($res_chk);
	if ($row_chk['mem_token'] != '') {
		return true;
	} else {
		return false;
	}
}

function get_search_key($key_id)
{
	global $self_con;
	$sql = "select key_content from Gn_Search_Key where key_id='{$key_id}'";
	$res = mysqli_query($self_con, $sql);
	$row = mysqli_fetch_array($res);
	return $row['key_content'];
}

function make_folder_month($n)
{
	$month = date('m');
	$year = date("Y");
	if ($n == 2) {
		$pre = '../../';
		$pre1 = '/';
	} else if ($n == 1) {
		$pre = '../';
		$pre1 = '/';
	} else {
		$pre = '';
		$pre1 = '';
	}
	$struct1 = $pre . 'naver_editor/upload_month/upload_' . $year . "_" . $month;
	$struct = '';
	if (!is_dir($struct1)) {
		if (mkdir($struct1, 0777, true)) {
			chmod($struct1, 0777);
			$struct = $pre1 . 'upload_month/upload_' . $year . "_" . $month . '/';
		} else {
			return '';
		}
	} else {
		$struct = $pre1 . 'upload_month/upload_' . $year . "_" . $month . '/';
	}
	return $struct;
}

// TEXT 형식으로 변환
function get_text($str, $html = 0, $restore = false)
{
	$source[] = "<";
	$target[] = "&lt;";
	$source[] = ">";
	$target[] = "&gt;";
	$source[] = "\"";
	$target[] = "&#034;";
	$source[] = "\'";
	$target[] = "&#039;";

	if ($restore)
		$str = str_replace($target, $source, $str);

	// 3.31
	// TEXT 출력일 경우 &amp; &nbsp; 등의 코드를 정상으로 출력해 주기 위함
	if ($html == 0) {
		$str = html_symbol($str);
	}

	if ($html) {
		$source[] = "\n";
		$target[] = "<br/>";
	}

	return str_replace($source, $target, $str);
}

function html_symbol($str)
{
	return preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str);
}

// 주소출력
function print_address($addr1, $addr2, $addr3, $addr4)
{
	$address = get_text(trim($addr1));
	$addr2   = get_text(trim($addr2));
	$addr3   = get_text(trim($addr3));

	if ($addr4 == 'N') {
		if ($addr2)
			$address .= ' ' . $addr2;
	} else {
		if ($addr2)
			$address .= ', ' . $addr2;
	}

	if ($addr3)
		$address .= ' ' . $addr3;

	return $address;
}

// alert 메세지 출력
function alert($msg, $move = 'back', $myname = '')
{
	if (!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

	switch ($move) {
		case "back":
			$url = "history.go(-1);void(1);";
			break;
		case "close":
			$url = "window.close();";
			break;
		case "parent":
			$url = "parent.document.location.reload();";
			break;
		case "replace":
			$url = "opener.document.location.reload();window.close();";
			break;
		case "no":
			$url = "";
			break;
		case "shash":
			$url = "location.hash='{$myname}';";
			break;
		case "thash":
			$url  = "opener.document.location.reload();";
			$url .= "opener.document.location.hash='{$myname}';";
			$url .= "window.close();";
			break;
		default:
			$url = "location.href='{$move}'";
			break;
	}

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
	echo "<script type=\"text/javascript\">alert(\"{$msg}\");{$url}</script>";
	exit;
}

// 메타태그를 이용한 URL 이동
// header("location:URL") 을 대체
function goto_url($url)
{
	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
	echo "<script type='text/javascript'>location.replace('{$url}');</script>";
	exit;
}

//송장번호 입력후 시간에 따라서 배송상태 자동설정
//24시간까지 >> “상품준비중“ 96시간까지 >> “배송중“ 96시간 이후 >>＂배송완료“
function set_gwc_delivery_state()
{
	global $self_con;
	$oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));
	$fourHourAgo = date('Y-m-d H:i:s', strtotime('-4 hour'));

	$sql_state = "select id, delivery, delivery_no, delivery_state, delivery_set_date from Gn_Gwc_Order where delivery_no <> '' and !ISNULL(delivery_set_date)";
	$res_state = mysqli_query($self_con, $sql_state);
	while ($row_state = mysqli_fetch_array($res_state)) {
		if ($row_state['delivery_set_date'] < $oneHourAgo && $row_state['delivery_set_date'] > $fourHourAgo) {
			$sql_update = "update Gn_Gwc_Order set delivery_state=2 where id='{$row_state['id']}'";
			mysqli_query($self_con, $sql_update);
		} else if ($row_state['delivery_set_date'] < $fourHourAgo) {
			$sql_update = "update Gn_Gwc_Order set delivery_state=3 where id='{$row_state['id']}'";
			mysqli_query($self_con, $sql_update);
		}
	}
}
function get_short_url($url, $shorten = false)
{
	if ($shorten)
		return file_get_contents("http://tinyurl.com/api-create.php?url=" . $url);
	else
		return $url;
}
function get_meta_image($url)
{
	if (strpos($url, "kiam.kr") === false) {
		return $url;
	} else {
		$url_arr = explode("kiam.kr", $url);
		return $url_arr[1];
	}
}
function getQueryParam($param, $url)
{
	$queryString = parse_url($url, PHP_URL_QUERY);
	parse_str($queryString, $params);
	return isset($params[$param]) ? $params[$param] : null;
}
