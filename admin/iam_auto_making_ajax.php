<?php
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
set_time_limit(0);
//include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
/*
* Comment :
*/
$ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36';
$header =array('Accept: application/json, text/plain, */*','Cache-Control: no-cache');
$query = "update Gn_Iam_automem set status = 1 where (status = 2 or image1 = '')";
mysqli_query($self_con,$query);
$query = "select * from Gn_Iam_automem where status = 1";
$res = mysqli_query($self_con,$query);
$phone_numbers = '';
$mem_ids = '';
$sendnum = '';
while($row=mysqli_fetch_array($res)){
    $name = $row['mem_name'];
    $pos = strpos($name,'(') ;
    if($pos > 0){
        $birthday = substr($name, $pos + 1, strlen($name) - $pos -2);
        $name = substr($name, 0, $pos);
    }
    $old_memid = $row['memid'];
    $memid = $row['db_source'].'_'.$row['memid'];
    $passwd = $row['db_source'].'_'.$row['password'];
    $phone_number = $row['profile_telno'];
    $email = $row['profile_email'];
    $company = $row['profile_company'];
    $address = $row['profile_address'];
    $friend = 'onlyone';
    $site = 'kiam';
    $profile_logo = "/iam/img/common/logo-2.png";
    $profile_self_info = $row['profile_self_info'];
    $query_automem = "update Gn_Iam_automem set memid ='$memid' where memid='$old_memid'";
    mysqli_query($self_con,$query_automem);
    //password()
    $query_join = "insert into Gn_Member set mem_id='$memid',
                                            mem_leb=22,
                                            web_pwd=md5('$passwd'),
                                            mem_pass=md5('$passwd'),
                                            mem_name='$name',
                                            mem_nick='$name',
                                            mem_phone='$phone_number',
                                            zy='$company',
                                            first_regist=now(),
                                            mem_check=now(),
                                            mem_add1='$address',
                                            mem_email='$email',
                                            recommend_id='$friend',
                                            site='$site',
                                            site_iam='$site',
                                            mem_birth='$birthday'";
    mysqli_query($self_con,$query_join);
    $homepage = $row['profile_homepage'];
    $homepage = preg_replace('/http/is', 'https', $homepage);

    if(strpos($homepage, 'http') === false)
        $homepage = 'https://'.$homepage;

    $strPos = strripos($homepage, '/');
    $strLen = strlen($homepage);
    $host = substr($homepage, 0,$strPos);
    $product_kind = substr($homepage, $strPos + 1, $strLen - $strPos - 1);
    $homepage = $homepage.'/category/ALL';
    $pattern = '/<a href=\"\/'.$product_kind.'\/products'.'(.*?)'.'\<img'.'(.*?)<\/a>/is';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_URL, $homepage);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_REFERER, $host);
    // curl_setopt($ch, CURLOPT_COOKIEFILE, $conf['cookie']['named']);
    // curl_setopt($ch, CURLOPT_COOKIEJAR, $conf['cookie']['named']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);

    $cr = @curl_exec($ch);
    $cerr = curl_error($ch);
    curl_close($ch);
    if ($cerr) {
        echo('Fail get named:' . $cerr);
        $query_contents = "update Gn_Iam_automem set status = 3,reg_date=now() where `No`='{$row['no']}'";
        mysqli_query($self_con,$query_contents);
    }else {
        $category = explode('CategoryProducts', $cr);
        $categoryProduct = $category[1];
        preg_match_all($pattern, $categoryProduct, $products);

        $result = $products[0];
        $count = count($result);
        $profile_title = array();
        $profile_image = array();
        $profile_link = array();
        if ($count > 0) {
            $count = 0;
            foreach ($result as $product) {
                $product = $result[$count];
                preg_match('/<a href=\"(.*?)\"/is', $product, $link);
                $profile_link[$count] = $link[1];
                $profile_link[$count] = $host . $profile_link[$count];
                preg_match('/src=\"(.*?)\"/is', $product, $image);
                $profile_image[$count] = $image[1];
                $pos = strpos($profile_image[$count], "?type");
                $profile_image[$count] = substr($profile_image[$count], 0, $pos);
                preg_match('/alt=\"(.*?)\"/is', $product, $title);
                $profile_title[$count] = $title[1];
                $count++;
            }
        }
        $short_url = generateRandomString();
        $iam_makingURL = '/?' . $short_url;
        $apply_link = '/admin/iam_auto_make_check.php?memid=' . $memid;
        $query_contents = "update Gn_Iam_automem set image1 ='$profile_image[0]',image2 ='$profile_image[1]',image3 ='$profile_image[2]',
                        iam_making = '$iam_makingURL', apply_link = '$apply_link', status = 0,reg_date=now() where `No`='{$row['no']}'";
        mysqli_query($self_con,$query_contents);
        $query_info = "insert into Gn_Iam_Info (mem_id,main_img1,main_img2,main_img3, reg_data) 
                    values ('$memid','$profile_image[0]','$profile_image[1]','$profile_image[2]', now())";
        mysqli_query($self_con,$query_info);
        $card_position = $profile_self_info;
        $card_map = '';
        $card_keyword = '';
        $favorite = 0;
        $story_title4 = '온라인정보';
        $story_online1_text = '';
        $online1_check = 'Y';
        $story_online2_text = '';
        $story_online2 = '';
        $online2_check = 'N';
        $name_card_sql = "insert into Gn_Iam_Name_Card (
                                mem_id, 
                                card_short_url, 
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
                                '$name', 
                                '$phone_number', 
                                '$email', 
                                '$address', 
                                '$profile_logo',
                                '$profile_self_info',
                                '$profile_image[0]',
                                '$profile_image[1]',
                                '$profile_image[2]',
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
        $contents_url_title = $profile_title[0];
        $contents_iframe = '';
        $contents_price = '';
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
        for ($i = 0; $i < $count; $i++) {
            $contents_order = $i + 1;
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
              req_data, 
              up_data,
              card_short_url,
              westory_card_url,
              card_idx,
              contents_order
              )values 
              ('$memid', 
              '$contents_type', 
              '$profile_image[$i]', 
              '$profile_title[$i]', 
              '$profile_link[$i]', 
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
              now(),
              now(),
              '$card_short_url',
              '$westory_card_url',
              '$card_idx',
              $contents_order
              )";
            mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
            $contents_idx = mysqli_insert_id($self_con);
            $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$contents_idx,card_idx=$card_idx,main_card=$card_idx";
            mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        }
    }
}
exit;
?>
