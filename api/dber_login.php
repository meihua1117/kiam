<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
/*
로그인처리
입력파라미터
 userid : 유저아이디
 user_pwd: 유저비번
 serial: 맥주소
출력파라미터
 result: 0-로그인 성공(token값을 돌려준다.),1-아이디 존재하지 않음,2-비번 정확하지 않음
*/
$user_id = trim($_POST["user_id"]);
$user_pwd = trim($_POST["user_pwd"]);
$serial = trim($_POST["serial"]);

$sql = "SELECT user_id, password, serial FROM crawler_member_real WHERE user_id = '$user_id'";
$result = mysql_query($sql);
$row=mysql_fetch_array($result);
if($row['user_id'] == "") {
    echo json_encode(array('result' => 1));
    exit;
}

if( md5($user_pwd) != $row['password']){
    echo json_encode(array('result' => 2));
    exit;
}

$memToken = generateRandomString(10);
$token = sha1($memToken);
$ip = $_SERVER['REMOTE_ADDR'];

//디비에 로그인시간과 ip를 저장한다.
$sql = "update crawler_member_real set serial='$serial', token='$token', login_ip='$ip',login_date=now() where user_id = '$user_id'";
$result = mysql_query($sql);

echo json_encode(array('result' => 0, 'token' => $token));


function generateRandomString($length = 10){
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	if($length == 32) {
		return $randomString;
	}
	else {
		$name_count_sql = "select count(idx) from Gn_Iam_Name_Card where card_short_url = '$randomString'";
		$name_count_result = mysql_query($name_count_sql);
		$name_count_row = mysql_fetch_array($name_count_result);

		if ((int)$name_count_row[0]) {
			generateRandomString();
		} else {
			return $randomString;
		}
	}
}
?>