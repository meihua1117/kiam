#!/opt/php/bin/php
<?php
include_once "/home/kiam/lib/db_config.php";
require_once('../fcm/vendor/autoload.php');
/*$mysql_host = 'localhost';
$mysql_user = 'kiam';
$mysql_password = 'only12!@db';
$mysql_db = 'kiam';
$self_con = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_db);
if (!$self_con) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
mysqli_query($self_con, "set names utf8");*/

$headers = array('Accept: application/json, text/plain, */*', 'Cache_Control: no-cache', 'content-type:mutipart/form-data;');
date_default_timezone_set('Asia/Seoul');
$cur_time1 = date("Y-m-d H:i:s");
$cur_time = date("Y-m-d H:i");
$date_today = date("Y-m-d");
$cur_time_com_stamp = time() - (86400 * 30);
$cur_time_com_mms = time() - (86400 * 3);
$cur_time_com = date("Y-m-d H:i:s", $cur_time_com_stamp);
$cur_time_mms_com = date("Y-m-d H:i:s", $cur_time_com_mms);

$server = "http://www.goodhow.com:";

$push_url = 'https://fcm.googleapis.com/v1/projects/onepagebookmms5/messages:send';
putenv('GOOGLE_APPLICATION_CREDENTIALS=../fcm/onepagebookmms5.json');
$scope = 'https://www.googleapis.com/auth/firebase.messaging';
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
$client->setScopes($scope);
$auth_key = $client->fetchAccessTokenWithAssertion();
$push_headers = array(
	'Authorization: Bearer ' . $auth_key['access_token'],
	'Content-Type: application/json'
);
$mem_stop_phone = array();
$s = 0;

$sql_mem_auto = "select * from auto_update_contents group by mem_id";
$res_mem_auto = mysqli_query($self_con, $sql_mem_auto);
while ($row_mem_auto = mysqli_fetch_array($res_mem_auto)) {
	$mem_point = 0;
	$sql_point = "select mem_id, mem_point, mem_phone from Gn_Member where mem_id='{$row_mem_auto['mem_id']}'";
	$res_point = mysqli_query($self_con, $sql_point);
	$row_point = mysqli_fetch_array($res_point);

	$mem_id = $row_point['mem_id'];
	$mem_phone = str_replace('-', '', $row_point['mem_phone']);
	$point = (int)$row_point['mem_point'];
	if ($point <= 10000 && $point > 5000) $mem_point = 10000;
	if ($point <= 5000 && $point > 3000) $mem_point = 5000;
	if ($point <= 3000) $mem_point = 3000;

	if ($point < 1100) {
		$mem_point = 1100;
		$stop_state = true;
		$sql_chk_state = "select state_flag from auto_update_contents where mem_id='{$mem_id}' and state_flag=1";
		$res_chk = mysqli_query($self_con, $sql_chk_state);
		$row_chk = mysqli_fetch_array($res_chk);
		if ($row_chk['state_flag'] != null) {
			$sql_contents_stop = "update auto_update_contents set state_flag=2, update_date='{$cur_time1}' where mem_id='{$mem_id}'";
			mysqli_query($self_con, $sql_contents_stop);
		}
	}

	$s++;
	$uni_id = time() . sprintf("%02d", $s);
	if ($mem_point != 0) {
		echo $cur_time1 . " " . $mem_id . ">>" . $row_point['mem_point'] . ">>" . $mem_phone . PHP_EOL;
		$sql_mms_send = "select reg_date, recv_num from Gn_MMS where title='포인트 충전 안내' and content='" . $mem_id . ", 고객님의 잔여 포인트가 " . $mem_point . " 포인트 이하입니다. 포인트가 부족할 경우 현재 이용중이신 기능이 중지되오니 충전해주시길 바랍니다. 감사합니다.' and mem_id='{$mem_id}' order by idx desc limit 1";
		$res_mms_send = mysqli_query($self_con, $sql_mms_send);
		if (mysqli_num_rows($res_mms_send)) {
			while ($row_mms_send = mysqli_fetch_array($res_mms_send)) {
				$reg_date_msg = $row_mms_send['reg_date'];
				if ($reg_date_msg < $cur_time_mms_com) {
					send_mms($self_con, $mem_id, $mem_phone, $uni_id, $mem_point, $push_url, $push_headers);
				}
			}
		} else {
			send_mms($self_con, $mem_id, $mem_phone, $uni_id, $mem_point, $push_url, $push_headers);
		}
	}
}


/*function sendmms($self_con, $type, $userid, $sendnum, $recvnum, $rserv_time, $title, $content, $img, $img1, $img2, $send_agreement_yn, $sms_idx = "", $sms_detail_idx = "", $request_idx = "", $gd_id = "", $send_deny_msg = "")
{
	if ($userid == "" || $sendnum == "" || $recvnum == "")
		return;

	$query = "select * from Gn_MMS_Number where mem_id='$userid' and sendnum='$sendnum'";
	$result = mysqli_query($self_con, $query);
	$row = mysqli_fetch_array($result);
	if ($row['pkey'] == "")
		return;

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
	//수신거부
	$fields['send_deny_msg'] = $send_deny_msg;
	//동의상태
	$fields['send_agreement_yn'] = $send_agreement_yn;
	//제한값들
	$fields['send_go_user_cnt'] = $row['user_cnt'];
	$fields['send_go_max_cnt'] = $row['daily_limit_cnt_user'];
	$fields['send_go_memo2'] = $row['memo2'];
	$fields['send_go_cnt1'] = $row['cnt1'];
	$fields['send_go_cnt2'] = $row['cnt2'];
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

	$headers = array('Accept: application/json, text/plain, * /*', 'Cache_Control: no-cache', 'content-type:mutipart/form-data;');

	curl_setopt($ch_mms, CURLOPT_URL, "https://kiam.kr/ajax/heineken.php");
	curl_setopt($ch_mms, CURLOPT_POST, true);
	//curl_setopt($ch_mms, CURLOPT_HTTPHEADER, $headers );
	curl_setopt($ch_mms, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch_mms, CURLOPT_POSTFIELDS, http_build_query($fields));

	$mms_result = curl_exec($ch_mms);
	if ($mms_result === FALSE) {
		//die(curl_error($ch_mms));
		$ret = "MMS 발송시 오류가 발생하였습니다.";
	} else {
		$result = json_decode($mms_result);
		$ret = $result->msg;
	}
	curl_close($ch_mms);
	return $ret;
}*/

function send_mms($self_con, $mem_id, $mem_phone, $uni_id, $mem_point, $url, $headers)
{
	$title = "포인트 충전 안내";
	$txt = $mem_id . ", 고객님의 잔여 포인트가 " . $mem_point . " 포인트 이하입니다. 포인트가 부족할 경우 현재 이용중이신 기능이 중지되오니 충전해주시길 바랍니다. 감사합니다.";

	$query = "insert into Gn_MMS set mem_id='$mem_id',
									send_num='$mem_phone',
									recv_num='$mem_phone',
									uni_id='$uni_id',
									content='$txt',
									title='$title',
									delay='5',
									delay2='15',
									close='24',
									type='1',
									reg_date=NOW(),
									agreement_yn='Y',
									recv_num_cnt=1";

	mysqli_query($self_con, $query) or die(mysqli_error($self_con));
	$sidx = mysqli_insert_id($self_con);

	$query = "select * from Gn_MMS_Number where mem_Id='$mem_id' and sendnum='$mem_phone'";
	$result = mysqli_query($self_con, $query);
	$info = mysqli_fetch_array($result);
	$pkey = $info['pkey'];
	if ($pkey != "") {
		$send_type = $info['type'];
		sendPush($self_con, $url, $headers, $pkey, $sidx, $send_type, $mem_phone);
	}
}

function sendPush($self_con, $url, $headers, $pkey, $sidx, $send_type, $send_num)
{
	$title = '{"MMS Push"}';
	$message = '{"Send":"Start","idx":"' . $sidx . '","send_type":"' . $send_type . '"}';
	$fields = array(
		'data' => array(
			"body" => $message,
			"title" => $title
		)
	);

	$fields['token'] = $pkey;
	$fields['android'] = array("priority" => "high");
	$fields = json_encode(array('message' => $fields));
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

	$result = curl_exec($ch);
	if ($result === FALSE) {
		die('FCM Send Error: ' . curl_error($ch));
	}
	//print_R($result);
	$json = json_decode($result);
	$msg = "";
	$msg = $json->results[0]->error;
	curl_close($ch);

	$query = "insert into Gn_MMS_PUSH set send_num='{$send_num}',
										idx='{$sidx}',
										token='{$pkey}',
										error='{$msg}'";

	mysqli_query($self_con, $query) or die(mysqli_error($self_con));
}

$sql_contents = "select * from auto_update_contents where state_flag=1";
$res_contents = mysqli_query($self_con, $sql_contents);
while ($row_contents = mysqli_fetch_array($res_contents)) {
	$time = $row_contents['get_time'];
	$mem_id = $row_contents['mem_id'];
	$card_idx = $row_contents['card_idx'];
	$web_type = $row_contents['web_type'];
	$web_address = $row_contents['web_address'];
	$keyword = $row_contents['keyword'];
	$set_position = $row_contents['set_position'];
	$start_date = $row_contents['start_date'];
	$end_date = $row_contents['end_date'];
	//    var_dump($keyword);

	$sql_short_url = "select card_short_url from Gn_Iam_Name_Card where idx={$card_idx}";
	$res_url = mysqli_query($self_con, $sql_short_url);
	$row_url = mysqli_fetch_array($res_url);
	$short_url = $row_url['card_short_url'];

	$sql_service = "select * from Gn_Iam_Service where mem_id='{$mem_id}'"; //분양사아이디이면.
	$res_service = mysqli_query($self_con, $sql_service);
	if (mysqli_num_rows($res_service)) {
		$row = mysqli_fetch_array($res_service);
		if ($row['autodata_point_end'] < $date_today) {
			$min_point = 1100;
		} else {
			if ($row['autodata_point'] == '') {
				$min_point = 1100;
			} else if ($row['autodata_point'] == 0) {
				$min_point = 0;
			} else {
				$min_point = $row['autodata_point'] * 1;
			}
		}
	} else {
		$min_point = 1100;
	}

	if ($cur_time_com > $row_contents['reg_date']) { //매월 1100 포인트 차감하기
		$cur_time1 = date("Y-m-d H:i:s");
		$sql_release = "update Gn_Member set mem_point=mem_point-{$min_point} where mem_id='{$mem_id}'";
		mysqli_query($self_con, $sql_release);

		$sql_rest_point = "select * from Gn_Member where mem_id='{$mem_id}'";
		$res_rest_point = mysqli_query($self_con, $sql_rest_point);
		$row_rest_point = mysqli_fetch_array($res_rest_point);
		$rest_point = $row_rest_point['mem_point'];
		$rest_cash = $row_rest_point['mem_cash'];
		$mem_name = $row_rest_point['mem_name'];
		$mem_phone = $row_rest_point['mem_phone'];
		$method = $mem_id . "/" . $mem_name;

		$sql_pay_res = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_id}', buyer_tel='{$mem_phone}', item_name='오토데이트', item_price={$min_point}, pay_percent=90, current_point={$rest_point}, current_cash={$rest_cash}, pay_status='Y', VACT_InputName='{$mem_name}', type='buy', seller_id='', pay_method='{$method}', pay_date=now(), point_val=1";
		mysqli_query($self_con, $sql_pay_res);

		$sql_release = "update auto_update_contents set settle_cnt=settle_cnt+1, reg_date='{$cur_time1}' where id={$row_contents['id']}";
		mysqli_query($self_con, $sql_release);

		$sql_cnt_ori = "select * from contents_update_history where update_id={$row_contents['id']} order by id desc limit 1";
		$res_cnt_ori = mysqli_query($self_con, $sql_cnt_ori);
		$row_cnt_ori = mysqli_fetch_array($res_cnt_ori);
		$ori_cnt = $row_cnt_ori['settle_cnt'];
		$ori_used_point = $row_cnt_ori['used_point'];

		$sql_release_history = "insert into contents_update_history set mem_id='{$mem_id}', update_id={$row_contents['id']}, card_idx={$card_idx}, web_type='{$web_type}', settle_cnt={$ori_cnt}+1, state=2, point={$min_point}, used_point={$ori_used_point}+{$min_point}, rest_point={$rest_point}, reg_date='{$cur_time1}'";
		mysqli_query($self_con, $sql_release_history);
	}

	switch ($web_type) {
		case "peopleid":
			$url = $server . "8088";
			$port = "8088";
			break;
		case "newsid":
			$url = $server . "8085/index_news.php";
			$port = "8085";
			break;
		case "mapid":
			$url = $server . "8087";
			$port = "8087";
			break;
		case "gmarketid":
			$url = $server . "81";
			$port = "81";
			break;
		case "blogid":
			$url = $server . "8085";
			$port = "8085";
			break;
		case "youtubeid":
			$url = $server . "8084";
			$port = "8084";
			break;
	}

	if (strpos($time, ",") !== false) {
		$time_arr = explode(",", $time);
		for ($i = 0; $i < count($time_arr); $i++) {
			if ($time_arr[$i] < 10) {
				$time = "0" . $time_arr[$i];
			} else {
				$time = $time_arr[$i];
			}
			$db_time = date("Y-m-d ") . $time . ":00";
			if ($cur_time == $db_time) {
				// echo "match_time!!!>>".$db_time.PHP_EOL;
				// echo $web_type.PHP_EOL;
				// echo $keyword.PHP_EOL;
				send_request($web_address, $mem_id, $keyword, $short_url, $url, $headers, $port, $set_position, $start_date, $end_date);
			}
		}
	} else {
		if ($time < 10) {
			$time = "0" . $time;
		}
		$db_time = date("Y-m-d ") . $time . ":00";
		if ($cur_time == $db_time) {
			// echo "match_time!!!>>".$db_time.PHP_EOL;
			// echo $web_type.PHP_EOL;
			// echo $keyword.PHP_EOL;
			send_request($web_address, $mem_id, $keyword, $short_url, $url, $headers, $port, $set_position, $start_date, $end_date);
		}
	}
}

function send_request($address, $id, $key_word, $shorturl, $link, $header, $port, $position, $start, $end)
{
	$ch = curl_init(); //크롤링 요청 보내기

	$fields['auto'] = true;
	$fields['card_sel'] = true;
	$fields['address'] = $address;
	$fields['contents_cnt'] = 10;
	$fields['my_id'] = $id;
	$fields['contents_keyword'] = $key_word;
	$fields['short_url'] = $shorturl;
	$fields['position'] = $position;

	curl_setopt($ch, CURLOPT_URL, $link);
	curl_setopt($ch, CURLOPT_PORT, $port);
	curl_setopt($ch, CURLOPT_POST, 1);
	// curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$kresult = curl_exec($ch);

	//echo $kresult."----------->>>>>>>>>>>>>>".PHP_EOL;

	if ($kresult === FALSE) {
		die('FCM Send Error: ' . curl_error($ch));
	}
	curl_close($ch);
}
?>