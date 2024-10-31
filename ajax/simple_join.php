<?
include_once "../lib/rlatjd_fun.php";
$headers = array('Accept: application/json, text/plain, */*', 'Cache_Control: no-cache', 'content-type:multipart/form-data;');
date_default_timezone_set('Asia/Seoul');
if(isset($_POST['mem_name']) || isset($_POST['mem_phone']) || isset($_POST['mem_id'])){
	$mem_name = $_POST['mem_name'];
	$mem_phone = $_POST['mem_phone'];
	$mem_id = $_POST['mem_id'];
	$mem_id = strtolower(trim($mem_id));
	$pwd = $_POST['mem_pwd'];
	$sql_exp = "select service_type from Gn_Iam_Service where sub_domain = 'http://".$HTTP_HOST."'";
	$res_exp = mysqli_query($self_con,$sql_exp);
	$row_exp = mysqli_fetch_array($res_exp);
	$exp_mem = $row_exp['service_type'];

	$card_sql = "select mem_id from Gn_Iam_Name_Card use index(idx) where idx = {$_POST['send_link']}";
	$card_res = mysqli_query($self_con,$card_sql);
	$card_row = mysqli_fetch_array($card_res);
	$recom_id = $card_row['mem_id'];

	$mem_sql = "select site,site_iam from Gn_Member where mem_id='$recom_id'";
	$mem_res = mysqli_query($self_con,$mem_sql);
	$mem_row = mysqli_fetch_array($mem_res);
	$site = $mem_row['site'];
	$site_iam = $mem_row['site_iam'];

  	if($HTTP_HOST != "kiam.kr")
		$query = "select * from Gn_Iam_Service where sub_domain like '%".$HTTP_HOST."'";
	else
		$query = "select * from Gn_Iam_Service where sub_domain like '%www.kiam.kr%'";
	$res = mysqli_query($self_con,$query);
	$domainData = mysqli_fetch_array($res);

	$exp_date = date("Y-m-d H:i:s",strtotime("+{$domainData['service_price']} days"));

	if(strpos($mem_phone, "-") !== false){
		$mem_phone = str_replace("-", "", $mem_phone);
	}
  	if($domainData['service_type'] == 3) {
		$card_cnt = 5;
		$share_cnt = 1000;
	}else{
		$card_cnt = $domainData['iamcard_cnt'];
		$share_cnt = $domainData['send_content'];
	}
	if($exp_mem == "true"){
		//password()
		$sql = "insert into Gn_Member set mem_id='{$mem_id}',
                                    mem_leb=22,
                                    web_pwd=md5('{$pwd}'),
                                    mem_pass=md5('{$pwd}'),
                                    mem_name='{$mem_name}',
                                    mem_nick='{$mem_name}',
                                    mem_phone='{$mem_phone}',
                                    first_regist=now() ,
									mem_check=now(),
									mem_type='A',
                                    recommend_id = '{$recom_id}',
                                    site = '{$site}',
									site_iam = '{$site_iam}',
									exp_start_status=1,
									exp_mid_status=1,
									exp_limit_status=1,
									exp_limit_date='{$exp_date}',
									iam_type=1,
									iam_card_cnt={$card_cnt},
									iam_share_cnt={$share_cnt}";
	}else{
		$sql = "insert into Gn_Member set mem_id='{$mem_id}',
										mem_leb=22,
										web_pwd=md5('{$pwd}'),
										mem_pass=md5('{$pwd}'),
										mem_name='{$mem_name}',
										mem_nick='{$mem_name}',
										mem_phone='{$mem_phone}',
										first_regist=now() ,
										mem_check=now(),
										mem_type='A',
										recommend_id = '{$recom_id}',
										site = '{$site}',
										site_iam = '{$site_iam}',
										iam_card_cnt={$card_cnt},
										iam_share_cnt={$share_cnt}";
	}
	mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
	$mem_code = mysqli_insert_id($self_con);
	$short_url = generateRandomString();

	$share_send_card = $_POST['send_link'];
	if($_POST["send_type"] == 'clone')
		$share_send_card = 0;
	$sql_card = "INSERT INTO Gn_Iam_Name_Card(mem_id, card_short_url, card_title, card_name, card_company, card_position, 
											card_phone, phone_display, card_email, card_addr, card_map, card_keyword, 
											profile_logo, favorite, story_title1, story_myinfo, story_title2, story_company, 
											story_title3, story_career, story_title4, story_online1_text, story_online1, online1_check, 
											story_online2_text, story_online2, online2_check, iam_click, iam_story, 
											iam_friends, iam_iamicon, iam_msms, iam_sms, iam_facebook, iam_kakao, iam_share, 
											iam_mystory, req_data, up_data, sample_click, sample_order, main_img1, main_img2, main_img3, 
											next_iam_link, card_show, share_send_card) 
											(SELECT '{$mem_id}', '{$short_url}', card_title, card_name, card_company, card_position, 
											card_phone, phone_display, card_email, card_addr, card_map, card_keyword, 
											profile_logo, favorite, story_title1, story_myinfo, story_title2, story_company, 
											story_title3, story_career, story_title4, story_online1_text, story_online1, online1_check, 
											story_online2_text, story_online2, online2_check, iam_click, iam_story, 
											iam_friends, iam_iamicon, iam_msms, iam_sms, iam_facebook, iam_kakao, iam_share, 
											iam_mystory, now(), now(), sample_click, sample_order, main_img1, main_img2, main_img3, 
											next_iam_link, card_show, $share_send_card FROM Gn_Iam_Name_Card use index(idx) WHERE idx='{$_POST['send_link']}')";
	// echo $sql_name; exit;
	mysqli_query($self_con,$sql_card) or die(mysqli_error($self_con));
	$card_idx = mysqli_insert_id($self_con);
	
	$sql_con = "INSERT INTO Gn_Iam_Contents(mem_id, contents_type, contents_img, contents_title, contents_url, contents_url_title, 
										contents_iframe, source_iframe, contents_price,contents_order, contents_sell_price, contents_desc, contents_display, 
										contents_user_display, contents_type_display, contents_footer_display, contents_temp, contents_like, 
										contents_share_text, contents_share_count, req_data, up_data, card_short_url, contents_westory_display, 
										westory_card_url, public_display, card_idx, except_keyword, share_send_cont, reduce_val) 
										(SELECT '{$mem_id}', contents_type, contents_img, contents_title, contents_url, contents_url_title, 
										contents_iframe, source_iframe, contents_price,contents_order, contents_sell_price, contents_desc, contents_display, 
										contents_user_display, contents_type_display, contents_footer_display, contents_temp, contents_like, 
										'', contents_share_count, now(), now(), '{$short_url}', contents_westory_display, 
										'{$short_url}', public_display, {$card_idx}, except_keyword, idx, reduce_val FROM Gn_Iam_Contents use index(card_idx) WHERE card_idx='{$_POST['send_link']}')";
	mysqli_query($self_con,$sql_con) or die(mysqli_error($self_con));

	$sql_con = "select idx from Gn_Iam_Contents where card_idx = $card_idx";
	$res_con = mysqli_query($self_con,$sql_con);
	while($row_con = mysqli_fetch_array($res_con)){
		$sql2 = "insert into Gn_Iam_Con_Card set cont_idx={$row_con['idx']},card_idx=$card_idx,main_card=$card_idx";
		mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
	}
	echo json_encode(array("result"=>"success","short_url"=>$short_url,"mem_code"=>$mem_code));
}
?>