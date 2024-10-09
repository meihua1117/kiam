<?php
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
set_time_limit(0);
//include_once $_SERVER['DOCUMENT_ROOT']."/admin/include_once/admin_header.inc.php";
/*
* Comment :
*/

extract($_POST);
$mem_id = $_POST['memid'];
$sel_type = $_POST['state'];
// echo $mem_id; exit;

$query = "select * from crawler_gm_seller_info where mem_id='{$mem_id}' order by id desc limit 1";
// echo $query; exit;
$res = mysql_query($query);
while ($row = mysql_fetch_array($res)) {
    $name = $row['daepyoja'];
    $birthday = "";
    $memid = $row['mem_id'];
    // $passwd = $row['password'];
    $phone_number = $row['phonenum'];
    $fax_num = $row['faxnum'];
    $email = $row['email'];
    $company = $row['sangho'];
    $address = $row['sojaeji'];
    $friend = 'onlyone';
    // $site = "obmms";
    // $site_iam = "obmms";
    $profile_logo = "/iam/img/common/logo-2.png";
    $profile_self_info = $row['intro_own'];

    $short_url = $row['iam_link'];

    $img_link = array();
    $query_img = "SELECT img_link FROM crawler_gm_contents_info where info_id={$row['id']} ORDER BY id LIMIT 3";
    $res_img = mysql_query($query_img);
    while($row_img = mysql_fetch_array($res_img)){
        array_push($img_link, $row_img['img_link']);
    }

    $profile_image1 = $img_link[0];
    $profile_image2 = $img_link[1];
    $profile_image3 = $img_link[2];

    $card_title = "상품소개해요.";
    $card_position=$profile_self_info;
    $card_map = '';
    $card_keyword = '';
    $favorite = 0;
    $story_title4 = '온라인정보';
    $story_online1_text = '';
    $homepage = $row['store_link'];
    $online1_check= 'Y';
    $story_online2_text= '';
    $story_online2= '';
    $online2_check= 'N';
    
    if($sel_type != 2){
        $query_info="insert into Gn_Iam_Info (mem_id,main_img1,main_img2,main_img3, reg_data) 
                        values ('$memid','$profile_image1','$profile_image2','$profile_image3', now())";
                        // echo $query_info; exit;
        mysql_query($query_info);
        $name_card_sql="insert into Gn_Iam_Name_Card (
                                    mem_id, 
                                    card_short_url, 
                                    card_title,
                                    card_name, 
                                    card_phone, 
                                    card_email, 
                                    card_addr, 
                                    profile_logo,
                                    story_company,
                                    main_img1,
                                    main_img2,
                                    main_img3,
                                    card_company,
                                    card_position,  
                                    card_map, 
                                    card_keyword,  
                                    favorite, 
                                    story_title4, 
                                    story_online1_text, 
                                    story_online1, 
                                    online1_check, 
                                    story_online2_text, 
                                    story_online2, 
                                    online2_check,
                                    phone_display, 
                                    req_data,
                                    up_data) 
                            values ('$memid', 
                                    '$short_url',
                                    '$card_title',
                                    '$name', 
                                    '$phone_number', 
                                    '$email', 
                                    '$address', 
                                    '$profile_logo',
                                    '$profile_self_info',
                                    '$profile_image1',
                                    '$profile_image2',
                                    '$profile_image3',
                                    '$company',
                                    '$card_position',  
                                    '$card_map', 
                                    '$card_keyword',  
                                    '$favorite', 
                                    '$story_title4', 
                                    '$story_online1_text',
                                    '$homepage', 
                                    '$online1_check', 
                                    '$story_online2_text', 
                                    '$story_online2', 
                                    '$online2_check',
                                    'Y',
                                    now(), 
                                    now())";
        // echo $name_card_sql; exit;
        mysql_query($name_card_sql);

        $card_idx = mysql_insert_id();
    }
    $contents_type = 1;
    $contents_url_title = 'url_title';
    $contents_iframe = '';
    // $contents_price = '';
    $contents_sell_price = 0;
    $contents_desc = '';
    $contents_display = 'Y';
    $contents_westory_display = 'N';
    $contents_user_display = 'Y';
    $contents_type_display = 'Y';
    $contents_footer_display = 'Y';
    $contents_temp = 1;
    $contents_share_id = 0;
    $card_short_url = $short_url;
    $westory_card_url = $short_url;
    if($sel_type == 2){
        $query = "select idx from Gn_Iam_Name_Card where card_short_url='{$card_short_url}'";
        $res = mysql_query($query);
        $row = mysql_fetch_array($res);
        $card_idx = $row['idx'];
    }
    $query_contents = "select * from crawler_gm_contents_info where info_id={$row['id']}";
    $res_contents = mysql_query($query_contents);
    while($row_contents = mysql_fetch_array($res_contents)){
        $profile_image = $row_contents['img_link'];
        $profile_title = $row_contents['product_name'];
        $profile_link = $row_contents['product_link'];
        $price1 = trim(str_replace("원", "", $row_contents['product_price']));
        $contents_price = str_replace(",", "", $price1);
        $sql2 = "insert into Gn_Iam_Contents (
                mem_id, 
                contents_type, 
                contents_img, 
                contents_title, 
                contents_url, 
                contents_url_title, 
                contents_iframe, 
                contents_price, 
                contents_sell_price, 
                contents_desc, 
                contents_display,
                contents_westory_display, 
                contents_user_display, 
                contents_type_display, 
                contents_footer_display, 
                contents_temp, 
                contents_share_text, 
                req_data, 
                up_data,
                card_short_url,
                westory_card_url,
                card_idx
                )values 
                ('$memid', 
                '$contents_type', 
                '$profile_image', 
                '$profile_title', 
                '$profile_link', 
                '$contents_url_title', 
                '$contents_iframe', 
                '$contents_price', 
                '$contents_sell_price', 
                '$contents_desc', 
                '$contents_display',
                '$contents_westory_display', 
                '$contents_user_display', 
                '$contents_type_display', 
                '$contents_footer_display', 
                '$contents_temp', 
                '$contents_share_id', 
                now(),
                now(),
                '$card_short_url',
                '$westory_card_url',
                '$card_idx'
        )";
        $result2 = mysql_query($sql2) or die(mysql_error());
        $contents_temp++;
        $contents_idx = mysql_insert_id();
        $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$contents_idx,card_idx=$card_idx,main_card=$card_idx";
        mysql_query($sql2) or die(mysql_error());

        $sql2 = "update Gn_Iam_Name_Card set up_data = now() where idx={$card_idx}";
        mysql_query($sql2) or die(mysql_error());
    }
}
$state = 1;
echo json_encode(array("mem_id"=>$memid,"state"=>$state));
