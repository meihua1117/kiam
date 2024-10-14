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
define("GOOGLE_SERVER_KEY", "AAAAmvl-uQA:APA91bHP4S4L8-nMvfOJ9vcjYlTmiRjEfOcLbAm6ITDFo9Ky-ziKAowlZi0rWhO3c7jsZ50unqWabQCBAmtr9bOxUIbwyAMgRsxO1jeLKlJ9l_Gir_wc1sZ66VBtHVBSjeAZcRfffVwo7M2fBvrrt1d5vz5clf7PVQ");
$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
@header('Expires: 0'); // rfc2616 - Section 14.21
@header('Last-Modified: ' . $gmnow);
@header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
@header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
@header('Pragma: no-cache'); // HTTP/1.0
extract($_GET);
/*if($_GET['key'] && $_GET['key'] != @session_id()) {
	@session_id($_GET['key']);
	@session_start();
	$request_url = $_SERVER['REQUEST_URI'];
	$pos = strpos($request_url, "?");
	$param_url = substr($request_url, $pos + 1, strlen($request_url) - $pos - 1);
	$request_url = substr($request_url, 0, $pos);
	$param_arr = explode("&",$param_url);
	$temp_arr = array();
	for ($i = 0;$i < sizeof($param_arr);$i++){
	    if(substr($param_arr[$i],0,4) != "key="){
		array_push($temp_arr, $param_arr[$i]);
	    }
	}
	if(sizeof($temp_arr) > 0){
	    $request_url .= "?".implode("&",$temp_arr);
	}
	echo "<script>location.href='$request_url';</script>";
	exit;
}
else {
	@session_start();
}*/
//$mysql_host = 'localhost';
$mysql_host = '222.239.248.226';
//$mysql_user = 'kiam';
$mysql_user = 'root';
//$mysql_password = 'only12!@db';
$mysql_password = 'only12!@db';
$mysql_db = 'kiam';
$self_con = mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
if (!$self_con) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
//mysql_select_db($mysql_db) or die(mysqli_error($self_con));
mysqli_query($self_con, "set names utf8");
$whois_api_key = "2021030317024746733699";
$domain_url = "http://www.kiam.kr";
/*
if($_REQUEST[work])
$_SESSION[work]=1;
if(!$_SESSION[work])
header("location: /work.php");*/
$HTTP_HOST = str_replace("www.", "", $_SERVER['HTTP_HOST']);
$REQUEST_URI = $_SERVER['REQUEST_URI'];
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

$deny_type_arr = array("A" => "자동", "B" => "수동");
$pc_pay_method = array("obmms20151" => "신용카드", "obmms20152" => "자동결제");
$sign_arr = array("INIpayTest" => "SU5JTElURV9UUklQTEVERVNfS0VZU1RS", "IESobmms00" => "Z0RpL2daakxEWWdoZ3RXMThHNVlTQT09", "obmms20151" => "YmVFN2RtTldRR25zS2x2S055cnRxUT09", "obmms20152" => "T2ZJUkRxaE1IamtJMGhoTnlEMXlFdz09");
$mobile_pay_method = array("onlyvbank" => "무통장입금", "wcard" => "신용카드");
$pay_type = array("Card" => "직접결제", "CARD" => "직접결제", "Auto_Card" => "자동결제", "MONTH" => "통장정기", "BANK" => "무통장", "VCard" => "V카드", "MONTH_Card" => "카드정기");
$is_chrome = preg_match('/Chrome/i', $_SERVER['HTTP_USER_AGENT']) ? "disabled" : "";
$pay_phone_status = array("N" => "결제대기", "Y" => "결제완료", "M" => "본인폰");
$pay_result_status = array("N" => "결제대기", "Y" => "결제완료", "C" => "해지완료");
if ($_REQUEST['one_no']) {
	$cookie_name = "board_" . $_REQUEST['status'] . $_REQUEST['one_no'];
	if (!$_COOKIE[$cookie_name]) {
		setcookie($cookie_name, "ok", time() + 3600 * 24);
		$sql_view = "update tjd_board set view_cnt=view_cnt+1 where no='{$_REQUEST['one_no']}'";
		mysqli_query($self_con, $sql_view) or die(mysqli_error($self_con));
	}
	$sql_no = "select * from tjd_board where no='{$_REQUEST['one_no']}'";
	$resul_no = mysqli_query($self_con, $sql_no);
	$row_no = mysqli_fetch_array($resul_no);
	$phone = explode("-", $row_no['phone']);
	$email = explode("@", $row_no['email']);
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
	global $self_con;
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	if ($length == 32) {
		return $randomString;
	} else {
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
$time = time();
$sql_fujia_up = "update Gn_Member set fujia_date1='' , fujia_date2='' where  unix_timestamp(fujia_date2) < $time and unix_timestamp(fujia_date2)<>'0'";
mysqli_query($self_con, $sql_fujia_up);
$sql_pay_up = "update tjd_pay_result set  end_status='N' where timestamp(end_date) < $time and end_status='Y' ";
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
			$sql_d_result1 = "delete from Gn_MMS where result='1' and send_num='{$row_format['sendnum']}' ";
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
			$sql_d_result1 = "delete from Gn_MMS where result='1' and send_num='{$row_format['sendnum']}' ";
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
$title_mm = "<a href='/'>홈</a>";
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
	echo "</span></div><div style='float:right;'>" . $page . "/" . $intPageCount . "</div><p style='clear:both;'></p></div>";
}
function sub_page_f($page, $page2, $intPageCount, $frm)
{
	echo "<div style='height:50px; text-align:center;padding-top:10px;margin-top:30px;font-size:14px; word-spacing:5px;'>
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
function watermarking($file, $mark, $x = 0, $y = 0, $opacity = 60)
{
	$ext = strtolower(substr(strrchr($file, "."), 1));
	$mark_size = @getimagesize($mark);

	if ($ext == "jpg" || $ext == "jpeg") {
		$mark = imagecreatefromgif($mark);
		$dest = imagecreatefromjpeg($file);
		$size = getimagesize($file);

		imagecopymerge($dest, $mark, $x, $y, 0, 0, $mark_size[0], $mark_size[1], $opacity);
		imagejpeg($dest, $file);
	} elseif ($ext == "gif") {
		$mark = imagecreatefromgif($mark);
		$dest = imagecreatefromgif($file);
		$size = getimagesize($file);

		imagecopymerge($dest, $mark, $x, $y, 0, 0, $mark_size[0], $mark_size[1], $opacity);
		imagegif($dest, $file);
	} elseif ($ext == "png") {
		$mark = imagecreatefromgif($mark);
		$dest = imagecreatefrompng($file);
		$size = getimagesize($file);

		imagecopymerge($dest, $mark, $x, $y, 0, 0, $mark_size[0], $mark_size[1], $opacity);
		imagepng($dest, $file);
	}
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
		$result = @mysqli_query($self_con, $sql) or die("<p>$sql<p>" . mysqli_errno($self_con) . " : " .  mysqli_error($self_con) . "<p>error file : $_SERVER[PHP_SELF]");
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
	$no = preg_replace("/[^0-9]/", "", $str);

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
	$endPG		= "";

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

		if ($block > 1) $prev_b .= "<li class='paginate_button previous disabled' id='list_previous'><a href=\"javascript:" . $scriptName . "('" . $prevPG . "')\" >Prev</a></li>";
		else $prev_b .= "<li class='paginate_button previous disabled' id='list_previous'><a href=\"javascript:void(0)\">Prev</a></li>";

		//다음 페이지 block 보기
		if ($block < $total_block) $next_b .= "<li class='paginate_button next' id='list_next'><a href=\"javascript:" . $scriptName . "('" . $nextPG . "')\" >Next</a></li>";
		else $next_b .= "<li class='paginate_button next' id='list_next'><a href=\"javascript:void(0)\">Next</a></li>";

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
		//$query = "update Gn_MMS_Receive set recv_num='$now_num' where mem_id='{$_SESSION['one_member_id']}' and recv_num='$old_num'";
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
		//$query = "update Gn_MMS_Receive set recv_num='$now_num' where mem_id='{$_SESSION['one_member_id']}' and recv_num='$old_num'";
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
	//$file_name = $file["name"];
	//$file_tmp_name = $file['tmp_name'];
	//$file_size = $file['size'];
	if ($file_name) {
		$file_type = explode(".", $file_name);
		$file_type_size = count($file_type);
		$file_ext = $file_type[$file_type_size - 1];

		$newName = time() . "_" . sprintf("%04d", rand(1, 9999)) . "." . $file_ext;

		//$gConf['board_data'] = "/data";

		//echo "!".$file_tmp_name."<BR>";
		echo $_SERVER['DOCUMENT_ROOT'] . $gConf['board_data'] . "/$folder/$file_name";
		move_uploaded_file($file_tmp_name, $_SERVER['DOCUMENT_ROOT'] . $gConf['board_data'] . "/$folder/$file_name") or die('파일1 Upload에 실패했습니다.');
		rename($_SERVER['DOCUMENT_ROOT'] . $gConf['board_data'] . "/$folder/$file_name", $_SERVER['DOCUMENT_ROOT'] . $gConf['board_data'] . "/$folder/$newName") or die('파일 rename에 실패했습니다.');
		$path = $gConf['board_data'] . "/$folder";


		return "" . $path . "/" . $newName;
	} else {
		return "";
	}
}
define("GOOGLE_SERVER_KEY", "AAAAmvl-uQA:APA91bHP4S4L8-nMvfOJ9vcjYlTmiRjEfOcLbAm6ITDFo9Ky-ziKAowlZi0rWhO3c7jsZ50unqWabQCBAmtr9bOxUIbwyAMgRsxO1jeLKlJ9l_Gir_wc1sZ66VBtHVBSjeAZcRfffVwo7M2fBvrrt1d5vz5clf7PVQ");
function uploadFTP($file)
{
	$ftp_user_name = "obmms";
	$ftp_user_pass = "onlyone123!";
	// open some file for reading
	//$file = 'docs/2019_OnlyOneMMS_Manual_V01.pdf';
	$fp = fopen($file, 'r');
	$ftp_server = "goodhow.com";

	// set up basic connection
	$conn_id = ftp_connect($ftp_server, 8821);

	// login with username and password
	$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

	// try to upload $file
	if (ftp_fput($conn_id, $file, $fp, FTP_ASCII)) {
		//echo "Successfully uploaded $file\n";
	} else {
		//echo "There was a problem while uploading $file\n";
	}

	// close the connection and the file handler
	ftp_close($conn_id);
	fclose($fp);
}
