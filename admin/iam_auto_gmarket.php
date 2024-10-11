<?php
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
set_time_limit(0);
//include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
/*
* Comment :
*/

extract($_POST);
$round = $_POST['round'];
// echo $round; exit;

$query = "select * from crawler_gm_seller_info where round_num={$round}";
$res = mysqli_query($self_con,$query);
while($row=mysqli_fetch_array($res))
{
    $name = $row['daepyoja'];
    $birthday = "";
    $memid = "gmarket_".$row['store_id'];
    $passwd = $row['store_id'];
    $phone_number = $row['phonenum'];
    $fax_num = $row['faxnum'];
    $email = $row['email'];
    $company = $row['sangho'];
    $address = $row['sojaeji'];
    $friend = 'onlyone';
    $site = "kiam";
    $site_iam = "kiam";
    $profile_logo = "/iam/img/common/logo-2.png";
    $profile_self_info = $row['intro_own'];

    $check_dup = "SELECT mem_id from Gn_Member where mem_id='{$memid}'";
    $result_chk = mysqli_query($self_con,$check_dup);
    if(mysqli_num_rows($result_chk) != 0){
    echo 1;
    exit;
    
}
        $query_join = "insert into Gn_Member set mem_id='$memid',
                                                          mem_leb=22,
                                                          web_pwd=password('$passwd'),
                                                          mem_pass=md5('$passwd'),
                                                          mem_name='$name',
                                                          mem_nick='$name',
                                                          mem_phone='$phone_number',
                                                          mem_fax='$fax_num',
                                                          zy='$company',
                                                          first_regist=now() ,
                                                          mem_check=now(),
                                                          mem_add1='$address',
                                                          mem_email='$email',
                                                          recommend_id = '$friend',
                                                          site = '$site',
                                                          site_iam = '$site_iam',
                                                          mem_birth = '$birthday'
                      ";
                    //   echo $query_join; exit;
            mysqli_query($self_con,$query_join);

    $short_url = generateRandomString();
    $iam_makingURL = '/iam/?'.$short_url;
    $apply_link = '/admin/iam_auto_make_check.php?memid='.$row['store_id'];

    $iam_query = "UPDATE crawler_gm_seller_info SET iam_link='{$iam_makingURL}' WHERE store_id='{$row['store_id']}'";
    mysqli_query($self_con,$iam_query);

    $img_link = array();
    $query_img = "SELECT img_link FROM crawler_gm_contents_info where store_id='{$row['store_id']}' ORDER BY id LIMIT 3";
    $res_img = mysqli_query($self_con,$query_img);
    while($row_img = mysqli_fetch_array($res_img)){
        array_push($img_link, $row_img['img_link']);
    }

    $profile_image1 = $img_link[0];
    $profile_image2 = $img_link[1];
    $profile_image3 = $img_link[2];

    $query_info="insert into Gn_Iam_Info (mem_id,main_img1,main_img2,main_img3, reg_data)
                    values ('$memid','$profile_image1','$profile_image2','$profile_image3', now())";
                    // echo $query_info; exit;
    mysqli_query($self_con,$query_info);

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
                                'N',
                                now(),
                                now())";
    mysqli_query($self_con,$name_card_sql);

    $card_idx = mysqli_insert_id($self_con);
    $contents_type = 1;
    $contents_url_title = 'url_title';
    $contents_iframe = '';
    $contents_sell_price = 0;
    $contents_desc = '';
    $contents_display = 'Y';
    $contents_westory_display = 'N';
    $contents_user_display = 'Y';
    $contents_type_display = 'Y';
    $contents_footer_display = 'Y';
    $contents_temp = 0;
    $contents_share_id = 0;
    $card_short_url = $short_url;
    $westory_card_url = $short_url;
    $query_contents = "select * from crawler_gm_contents_info where store_id='{$row['store_id']}'";
    $res_contents = mysqli_query($self_con,$query_contents);
    while($row_contents = mysqli_fetch_array($res_contents)){
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
        mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        $contents_idx = mysqli_insert_id($self_con);
        $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$contents_idx,card_idx=$card_idx,main_card=$card_idx";
        mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
    }
}
echo 1;
?>