<?
include_once "../lib/rlatjd_fun.php";
$headers = array('Accept: application/json, text/plain, */*', 'Cache_Control: no-cache', 'content-type:multipart/form-data;');
date_default_timezone_set('Asia/Seoul');
if (isset($_POST['mem_name']) || isset($_POST['mem_phone']) || isset($_POST['mem_id'])) {
	$mem_name = $_POST['mem_name'];
	$mem_phone = $_POST['mem_phone'];
	$mem_id = $_POST['mem_id'];
	$mem_id = strtolower(trim($mem_id));
	$pwd = $_POST['mem_pwd'];
	$site = $_POST['site'];
	$site_iam = $_POST['site_iam'];
	$recom_id = $_POST['recom_id'];
	$card_short_url = $_POST['card_short_url'];
	$event_id = $_POST['event_id'];
	$exp_mem = $_POST['exp_mem'];
	$short_url = array();

	if ($HTTP_HOST != "kiam.kr")
		$query = "select * from Gn_Iam_Service where sub_domain like '%" . $HTTP_HOST . "'";
	else
		$query = "select * from Gn_Iam_Service where sub_domain like '%www.kiam.kr%'";
	$res = mysqli_query($self_con, $query);
	$domainData = mysqli_fetch_array($res);

	$exp_date = date("Y-m-d H:i:s", strtotime("+{$domainData['service_price']} days"));

	if (strpos($mem_phone, "-") !== false) {
		$mem_phone = str_replace("-", "", $mem_phone);
	}
	//$pwd = substr($mem_phone, -4);

	// echo $pwd;exit;
	if (strpos($card_short_url, ",") !== false) {
		$short_url = explode(",", $card_short_url);
	} else {
		$short_url[0] = $card_short_url;
	}

	if ($domainData['service_type'] == 3) {
		$card_cnt = 5;
		$share_cnt = 1000;
	} else {
		$card_cnt = $domainData['iamcard_cnt'];
		$share_cnt = $domainData['send_content'];
	}
	// echo count($short_url); exit;
	if ($exp_mem == "true") {
		//password()
		$sql = "insert into Gn_Member set mem_id='{$mem_id}',
                                    mem_leb=22,
                                    web_pwd=md5('{$pwd}'),
                                    mem_pass=md5('{$pwd}'),
                                    mem_name='{$mem_name}',
                                    mem_nick='{$mem_name}',
                                    mem_phone='{$mem_phone}',
                                    first_regist=now() ,
									last_modify=now() ,
									last_regist=now() ,
									fujia_date1 = now(),
									fujia_date2 = now(),
									mem_check=now(),
									mem_type='A',
                                    recommend_id = '{$recom_id}',
                                    site = '{$site}',
									site_iam = '{$site_iam}',
									event_id={$event_id},
									exp_start_status=1,
									exp_mid_status=1,
									exp_limit_status=1,
									exp_limit_date='{$exp_date}',
									iam_type=1,
									iam_card_cnt={$card_cnt},
									iam_share_cnt={$share_cnt}";
	} else {
		//password()
		$sql = "insert into Gn_Member set mem_id='{$mem_id}',
						mem_leb=22,
						web_pwd=md5('{$pwd}'),
						mem_pass=md5('{$pwd}'),
						mem_name='{$mem_name}',
						mem_nick='{$mem_name}',
						mem_phone='{$mem_phone}',
						first_regist=now() ,
						last_modify=now() ,
						last_regist=now() ,
						fujia_date1 = now(),
						fujia_date2 = now(),
						mem_check=now(),
						mem_type='A',
						recommend_id = '{$recom_id}',
						site = '{$site}',
						site_iam = '{$site_iam}',
						event_id={$event_id},
						iam_card_cnt={$card_cnt},
						iam_share_cnt={$share_cnt}";
	}
	mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
	$mem_code = mysqli_insert_id($self_con);

	if ($_POST['join_type'] == "iam") {
		if ($card_short_url != null) {
			for ($i = 0; $i < count($short_url); $i++) {
				$short_url_db = generateRandomString();
				$sql_name = "INSERT INTO Gn_Iam_Name_Card(mem_id, card_short_url, card_title, card_name, card_company, card_position, card_phone, phone_display, card_email, card_addr, card_map, card_keyword, profile_logo, favorite, story_title1, story_myinfo, story_title2, story_company, story_title3, story_career, story_title4, story_online1_text, story_online1, online1_check, story_online2_text, story_online2, online2_check, iam_click, iam_story, iam_friends, iam_iamicon, iam_msms, iam_sms, iam_facebook, iam_kakao, iam_share, iam_mystory, req_data, up_data, sample_click, sample_order, main_img1, main_img2, main_img3, next_iam_link, card_show) 
					(SELECT '{$mem_id}', '{$short_url_db}', card_title, card_name, card_company, card_position, card_phone, phone_display, card_email, card_addr, card_map, card_keyword, profile_logo, favorite, story_title1, story_myinfo, story_title2, story_company, story_title3, story_career, story_title4, story_online1_text, story_online1, online1_check, story_online2_text, story_online2, online2_check, iam_click, iam_story, iam_friends, iam_iamicon, iam_msms, iam_sms, iam_facebook, iam_kakao, iam_share, iam_mystory, now(), now(), sample_click, sample_order, main_img1, main_img2, main_img3, next_iam_link, card_show FROM Gn_Iam_Name_Card WHERE card_short_url='{$short_url[$i]}')";
				mysqli_query($self_con, $sql_name) or die(mysqli_error($self_con));
				$card_idx = mysqli_insert_id($self_con);

				$sql_con = "INSERT INTO Gn_Iam_Contents(mem_id, contents_type, contents_img, contents_title, contents_url, contents_url_title, contents_iframe, source_iframe, contents_price, contents_sell_price, contents_desc, contents_display, contents_user_display, contents_type_display, contents_footer_display, contents_temp, contents_like, contents_share_text, contents_share_count, req_data, up_data, card_short_url, contents_westory_display, westory_card_url, public_display, card_idx, except_keyword, reduce_val) 
					(SELECT '{$mem_id}', contents_type, contents_img, contents_title, contents_url, contents_url_title, contents_iframe, source_iframe, contents_price, contents_sell_price, contents_desc, contents_display, contents_user_display, contents_type_display, contents_footer_display, contents_temp, contents_like, '', contents_share_count, now(), now(), '{$short_url_db}', contents_westory_display, '{$short_url_db}', public_display, {$card_idx}, except_keyword, reduce_val FROM Gn_Iam_Contents WHERE card_short_url='{$short_url[$i]}')";
				mysqli_query($self_con, $sql_con) or die(mysqli_error($self_con));
				$cont_idx = mysqli_insert_id($self_con);

				$sql_con = "select idx from Gn_Iam_Contents where card_idx = $card_idx";
				$res_con = mysqli_query($self_con, $sql_con);
				while ($row_con = mysqli_fetch_array($res_con)) {
					$sql2 = "insert into Gn_Iam_Con_Card set cont_idx={$row_con['idx']},card_idx=$card_idx,main_card=$card_idx";
					mysqli_query($self_con, $sql2) or die(mysqli_error($self_con));
				}
			}
		}

		$date = date("Y-m-d");
		$short_url_db1 = generateRandomString();
		$card_title = $mem_name . " 소개";
		$img_url = "/iam/img/common/logo-2.png";

		$sql2 = "insert into Gn_Iam_Name_Card (mem_id, card_title, card_short_url, card_name, card_company, card_position, card_phone, card_email, card_addr, card_map, card_keyword, profile_logo, favorite, story_title4, story_online1_text," .
			"story_online1, online1_check, story_online2_text, story_online2, online2_check,req_data,main_img1,main_img2,main_img3,story_title1,story_title2,story_title3,story_myinfo,story_company,story_career)" .
			"values ('{$mem_id}', '{$card_title}', '{$short_url_db1}', '{$mem_name}', '', '', '{$mem_phone}', '@', '', '', '', '{$img_url}', 0, '온라인정보','','', '', '', '', '', '{$date}','','','','','','','','','')";
		$result2 = mysqli_query($self_con, $sql2) or die(mysqli_error($self_con));

		//added by amigo
		$ipcheck = $_SERVER['REMOTE_ADDR'];
		$mobile = $mem_phone;
		$sql = "insert into Gn_event_request set event_idx='{$event_id}',
												m_id='{$mem_id}',
												name='{$mem_name}',
												mobile='{$mobile}',
												ip_addr='{$ipcheck}',
												regdate=now(),
												step_end_time=now(),
												target='2'";
		$res1 = mysqli_query($self_con, $sql);
		$request_idx = mysqli_insert_id($self_con);

		$sql = "select * from Gn_event where event_idx='{$event_id}'";
		$result = mysqli_query($self_con, $sql);
		$event_data = mysqli_fetch_array($result);
		$service_id = $event_data['m_id'];

		$recv_num = $mem_phone;
		$sql = "select * from gn_automem_sms_reserv where auto_event_id='{$event_id}' and allow_state=1";
		$lresult = mysqli_query($self_con, $sql);
		if (mysqli_num_rows($lresult) > 0) {
			$row = mysqli_fetch_array($lresult);
			if ($row['reserv_sms_id'] != "") {
				$sms_idx = $row['reserv_sms_id'];
				$send_num = $row['send_num'];

				$sql = "select * from Gn_event_sms_step_info where sms_idx='{$sms_idx}'";
				$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
				$step_end_time = date("Y-m-d H:i:s");
				while ($row = mysqli_fetch_array($result)) {
					// 시간 확인
					$send_day = $row['send_day'];
					$send_time = $row['send_time'];
					if ($send_time == "") $send_time = "09:30";
					if ($send_time == "00:00") $send_time = "09:30";
					if ($send_day == 0) {
						$reservation = "";
						$jpg = $jpg1 = $jpg2 = '';
						if ($row['image'])
							$jpg = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image'];
						if ($row['image1'])
							$jpg1 = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image1'];
						if ($row['image2'])
							$jpg2 = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image2'];
						$row['content'] = htmlspecialchars($row['content'], ENT_QUOTES);
						sendmms(2, $service_id, $send_num, $recv_num, $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, 'Y', $row['sms_idx'], $row['sms_detail_idx'], $request_idx, "", $row['send_deny']);
					} else {
						$reservation = date("Y-m-d $send_time:00", strtotime("+$send_day days"));
						if ($step_end_time < $reservation)
							$step_end_time = $reservation;
					}
				}
				$sql = "update Gn_event_request set step_end_time='{$step_end_time}' where request_idx = {$request_idx}";
				mysqli_query($self_con, $sql);
			}
		}
	} else {
		$sql = "select * from Gn_MMS_Group where mem_id='{$mem_id}' and grp='아이엠'";
		$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
		$data = mysqli_fetch_array($result);
		if ($data['idx'] == "") {
			$query = "insert into Gn_MMS_Group set mem_id='{$mem_id}', grp='아이엠', reg_date=NOW()";
			mysqli_query($self_con, $query);
		}
	}
	echo json_encode(array("mem_id" => $mem_id, "mem_pass" => $pwd, "mem_code" => $mem_code, "status" => 1, "short_url" => $short_url_db1));
}
