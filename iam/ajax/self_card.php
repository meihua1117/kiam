<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
if($_POST[join])
{
	$sql_auto = "select * from Gn_Member where mem_id='{$_SESSION[iam_member_id]}'";
	$res_auto = mysqli_query($self_con,$sql_auto);
	$row_auto = mysqli_fetch_array($res_auto);
	$e_id = $row_auto['site_iam'];
	$mem_code = $row_auto['mem_code'];
	$mem_phone = $row_auto['mem_phone'];
	$mem_name = $row_auto['mem_name'];

	if($e_id != "kiam"){
		$host = "http://".$e_id.".kiam.kr";
		$sql_id = "select mem_id from Gn_Iam_Service where sub_domain='$host'";
		$id_res = mysqli_query($self_con,$sql_id);
		$row_id = mysqli_fetch_array($id_res);
		$id = $row_id['mem_id'];
	}
	else{
		$id = "obmms02";
	}

	$sql_namecard = "select * from Gn_Iam_Service where mem_id='{$id}'";
	$res_ncard = mysqli_query($self_con,$sql_namecard);
	$row_ncard = mysqli_fetch_array($res_ncard);
	$card_idx = $row_ncard['self_share_card'];

	$card_url = "";
	if($card_idx != ""){
		if(strpos($card_idx, ",") !== false){
			$card_idx = explode(",", $card_idx);
			for($i = 0; $i < count($card_idx); $i++){
				$idx = $card_idx[$i] * 1 - 1;
				$sql_card_url = "select * from Gn_Iam_Name_Card where group_id is NULL and mem_id='{$id}' order by req_data asc limit ".$idx.", 1;";
				$res_url = mysqli_query($self_con,$sql_card_url);
				$row_url = mysqli_fetch_array($res_url);
				if($i == count($card_idx) - 1)
				$card_url .= $row_url['card_short_url'];
				else
				$card_url .= $row_url['card_short_url'] . ",";
			}
		}
		else{
			$idx = $card_idx * 1 - 1;
			$sql_card_url = "select * from Gn_Iam_Name_Card where group_id is NULL and mem_id='{$id}' order by req_data asc limit ".$idx.", 1;";
			$res_url = mysqli_query($self_con,$sql_card_url);
			$row_url = mysqli_fetch_array($res_url);
			$card_url .= $row_url['card_short_url'];
		}

		if(strpos($card_url, ",") !== false){
			$short_url = explode(",", $card_url);
		}
		else{
			$short_url[0] = $card_url;
		}

		for($i = 0; $i < count($short_url); $i++){
			$short_url_db = generateRandomString();
			$sql_name = "INSERT INTO Gn_Iam_Name_Card (mem_id, card_short_url, card_title, card_name, card_company, card_position, card_phone, phone_display, card_email, card_addr, card_map, 
										card_keyword, profile_logo, favorite, story_title1, story_myinfo, story_title2, story_company, story_title3, story_career, story_title4, story_online1_text, 
										story_online1, online1_check, story_online2_text, story_online2, online2_check, iam_click, iam_story, iam_friends, iam_iamicon, iam_msms, iam_sms, iam_facebook, 
										iam_kakao, iam_share, iam_mystory, req_data, up_data, sample_click, sample_order, main_img1, main_img2, main_img3, next_iam_link, card_show, reduce_val) 
										(SELECT '{$_SESSION[iam_member_id]}', '{$short_url_db}', card_title, card_name, card_company, card_position, '{$mem_phone}', phone_display, card_email, card_addr, card_map, 
										card_keyword, profile_logo, favorite, story_title1, story_myinfo, story_title2, story_company, story_title3, story_career, story_title4, story_online1_text, 
										story_online1, online1_check, story_online2_text, story_online2, online2_check, iam_click, iam_story, iam_friends, iam_iamicon, iam_msms, iam_sms, iam_facebook, 
										iam_kakao, iam_share, iam_mystory, now(), now(), sample_click, sample_order, main_img1, main_img2, main_img3, next_iam_link, card_show, reduce_val FROM Gn_Iam_Name_Card WHERE card_short_url='{$short_url[$i]}')";
			mysqli_query($self_con,$sql_name) or die(mysqli_error($self_con));
			$card_idx = mysqli_insert_id($self_con);
			
			$sql_con = "INSERT INTO Gn_Iam_Contents (mem_id, contents_type, contents_img, contents_title, contents_url, contents_url_title, contents_iframe, source_iframe, contents_price, 
										contents_sell_price, contents_desc, contents_display, contents_user_display, contents_type_display, contents_footer_display, contents_temp, contents_like, 
										contents_share_text, contents_share_count, req_data, up_data, card_short_url, contents_westory_display, westory_card_url, public_display, card_idx, except_keyword) 
										(SELECT '{$_SESSION[iam_member_id]}', contents_type, contents_img, contents_title, contents_url, contents_url_title, contents_iframe, source_iframe, contents_price, 
										contents_sell_price, contents_desc, contents_display, contents_user_display, contents_type_display, contents_footer_display, contents_temp, contents_like, 
										'', contents_share_count, now(), now(), '{$short_url_db}', contents_westory_display, '{$short_url_db}', public_display, {$card_idx}, except_keyword FROM Gn_Iam_Contents WHERE card_short_url='{$short_url[$i]}')";
			mysqli_query($self_con,$sql_con) or die(mysqli_error($self_con));
			$cont_idx = mysqli_insert_id($self_con);

			$sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$cont_idx,card_idx=$card_idx,main_card=$card_idx";
			mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
		}
	}	

	$date = date("Y-m-d");
	$short_url_db1 = generateRandomString();
	$card_title = $mem_name . " 소개";
	$img_url = "/iam/img/common/logo-2.png";

	$sql2 = "insert into Gn_Iam_Name_Card (mem_id, card_title, card_short_url, card_name, card_company, card_position, card_phone, card_email, card_addr, card_map, card_keyword, profile_logo, favorite, story_title4, story_online1_text,".
								"story_online1, online1_check, story_online2_text, story_online2, online2_check,req_data,up_data,main_img1,main_img2,main_img3,story_title1,story_title2,story_title3,story_myinfo,story_company,story_career)".
							"values ('{$_SESSION['iam_member_id']}', '$card_title', '$short_url_db1', '$mem_name', '', '', '{$mem_phone}', '@', '', '', '', '$img_url', 0, '온라인정보','','', '', '', '', '', '{$date}',now(),'','','','','','','','','')";
	$result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));

	echo json_encode(array("mem_code"=>$mem_code, "short_url"=>$short_url_db1));
}
?>
