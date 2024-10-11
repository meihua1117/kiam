<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 레벨 수정
*/
extract($_POST);
extract($_GET);
if($mode == "delete_name_card") {
    $sql_chk_use = "select org_use_state from Gn_Iam_Name_Card where idx={$card_idx}";
    $res_chk_use = mysqli_query($self_con,$sql_chk_use);
    $row_chk_use = mysqli_fetch_array($res_chk_use);
    if($row_chk_use[0]){
        echo 1;
        exit;
    }
    $result111 = array();
    $sql_short_url = "select card_short_url, mem_id from Gn_Iam_Name_Card where idx={$card_idx}";
    $res_url = mysqli_query($self_con,$sql_short_url);
    $row_url = mysqli_fetch_array($res_url);
    $short_url = $row_url['card_short_url'];
    $mem_id = $row_url['mem_id'];

    $sql_info = "select id, web_type from crawler_iam_info where iam_link='$short_url' and mem_id='{$mem_id}'";
    $res_info = mysqli_query($self_con,$sql_info);
    while($row = mysqli_fetch_array($res_info)){
        array_push($result111, $row);
    }
    foreach($result111 as $val){
        $info_id = $val['id'];
        $web_type = $val['web_type'];
        $sql_del_state = "delete from crawler_status_info where info_id={$info_id} and mem_id='{$mem_id}'";
        $res_state = mysqli_query($self_con,$sql_del_state) or die(mysqli_error($self_con));

        switch($web_type){
            case 'MAN':
                $sql_del_contents_id = 'crawler_people_contents_info';
                break;
            case 'MAP':
                $sql_del_contents_id = 'crawler_map_contents_info';
                break;
            case 'GMARKET':
                $sql_del_contents_id = 'crawler_gm_contents_info';
                break;
            case 'BLOG':
                $sql_del_contents_id = 'crawler_blog_contents_info';
                break;
            case 'YOUTUBE':
                $sql_del_contents_id = 'crawler_youtube_contents_info';
                break;
        }

        $sql_del_con = "delete from ".$sql_del_contents_id." where info_id={$info_id} and mem_id='{$mem_id}'";
        $res_con = mysqli_query($self_con,$sql_del_con) or die(mysqli_error($self_con));
    }
    $sql = "delete from Gn_Iam_Name_Card where idx ='$card_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

    $mall_sql = "select * from Gn_Iam_Contents where card_idx={$card_idx}";
    $mall_res = mysqli_query($self_con,$mall_sql);
    while($mall_row = mysqli_fetch_array($mall_res)){
        $m_idx = $mall_row[idx];
        $m_sql="delete from Gn_Iam_Mall where card_idx = '$m_idx' and (mall_type = 3 or mall_type = 4)";
        mysqli_query($self_con,$m_sql) or die(mysqli_error($self_con));
    }

    $sql_del_contents = "delete from Gn_Iam_Contents where card_idx=$card_idx and mem_id = '$mem_id'";
    $result3 = mysqli_query($self_con,$sql_del_contents) or die(mysqli_error($self_con));

    $sql = "select cont_idx from Gn_Iam_Con_Card where main_card={$card_idx}";
    $res = mysqli_query($self_con,$sql);
    while($row = mysqli_fetch_array($res)){
        $sql_contents = "delete from Gn_Iam_Con_Card where cont_idx = $row[cont_idx]";
        mysqli_query($self_con,$sql_contents);
    }
    $sql_contents = "delete from Gn_Iam_Con_Card where card_idx={$card_idx}";
    mysqli_query($self_con,$sql_contents);

    $sql="delete from Gn_Iam_Mall where card_idx = '$card_idx' and mall_type = 2";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

    echo "삭제되었습니다.";
} 

if($mode == "update_name_card - phone_display") {
    $sql="update Gn_Iam_Name_Card set phone_display ='$phone_display',updata=now() where idx ='$card_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $update_query = "update Gn_Iam_Contents set public_display = '$phone_display', up_data = now() where card_idx ='$card_idx'";
    mysqli_query($self_con,$update_query);
    echo "갱신되었습니다.";
} 

if($mode == "update_name_card - sample_click") {
    $sql="update Gn_Iam_Name_Card set sample_click ='$sample_click',up_data=now() where idx ='$card_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo "갱신되었습니다.";
}

if($mode == "update_name_card - call_click") {
    if($call_click == "Y"){
        $sql_shop_id = "select min(admin_shopping) from Gn_Iam_Name_Card where admin_shopping!=0 and admin_shopping>500000";
        $res_shop_id = mysqli_query($self_con,$sql_shop_id);
        $row_shop_id = mysqli_fetch_array($res_shop_id);

        if($row_shop_id[0] < 500000){
            $shop_id = 999999;
        }
        else{
            $shop_id = $row_shop_id[0] * 1 - 1;
        }

        $sql_card_gid = "select busi_time_edit from Gn_Iam_Name_Card where admin_shopping!=0 order by busi_time_edit desc limit 1";
        $res_gid = mysqli_query($self_con,$sql_card_gid);
        $row_gid = mysqli_fetch_array($res_gid);
        $card_gid = $row_gid['busi_time_edit'] * 1 + 1;

        $short_url_db = generateRandomString();
        $sql_name = "INSERT INTO Gn_Iam_Name_Card(mem_id, card_short_url, card_title, card_name, card_company, card_position, card_phone, phone_display, card_email, card_addr, card_map, card_keyword, profile_logo, favorite, story_title1, story_myinfo, story_title2, story_company, story_title3, story_career, story_title4, story_online1_text, story_online1, online1_check, story_online2_text, story_online2, online2_check, iam_click, iam_story, iam_friends, iam_iamicon, iam_msms, iam_sms, iam_facebook, iam_kakao, iam_share, iam_mystory, req_data, up_data, sample_click, sample_order, main_img1, main_img2, main_img3, next_iam_link, card_show, ai_map_gmarket, business_time, busi_time_edit, map_pos, admin_shopping) 
                                        (SELECT mem_id, '{$short_url_db}', '메뉴보기', card_name, card_company, card_position, card_phone, phone_display, card_email, card_addr, card_map, card_keyword, profile_logo, favorite, story_title1, story_myinfo, story_title2, story_company, story_title3, story_career, story_title4, story_online1_text, story_online1, online1_check, story_online2_text, story_online2, online2_check, iam_click, iam_story, iam_friends, iam_iamicon, iam_msms, iam_sms, iam_facebook, iam_kakao, iam_share, iam_mystory, now(), now(), sample_click, sample_order, main_img1, main_img2, main_img3, next_iam_link, card_show, '1', business_time, '{$card_gid}', map_pos, '{$shop_id}' FROM Gn_Iam_Name_Card WHERE idx='{$card_idx}')";
        mysqli_query($self_con,$sql_name) or die(mysqli_error($self_con));
        echo "설정되었습니다.";
    }
}

if($mode == "update_name_card_sample_order") {
    $sql="update Gn_Iam_Name_Card set sample_order ='$sample_order',up_data=now() where idx ='$card_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo "갱신되었습니다.";
}

if($mode == "delete_contents") {
    $sql = "delete from Gn_Iam_Contents where idx = $idx";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

    $sql = "delete from Gn_Iam_Con_Card where cont_idx = $idx";
    mysqli_query($self_con,$sql);

    $sql="delete from Gn_Iam_Mall where card_idx = '$idx' and (mall_type = 3 or mall_type = 4)";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo "삭제되었습니다.";
}
if($mode == "update-contents-sample-display") {
    $sql = "select card_idx from Gn_Iam_Contents where where idx ='$cont_idx'";
    $res = mysqli_query($self_con,$sql);
    $cont_row = mysqli_fetch_assoc($res);
    $sql="update Gn_Iam_Name_Card set up_data=now() where idx ={$cont_row['card_idx']}";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

    $sql="update Gn_Iam_Contents set sample_display ='$sample_display',up_data=now() where idx ='$cont_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo "갱신되었습니다.";
}
if($mode == "update_contents_sample_order") {
    $sql = "select card_idx from Gn_Iam_Contents where where idx ='$cont_idx'";
    $res = mysqli_query($self_con,$sql);
    $cont_row = mysqli_fetch_assoc($res);
    $sql="update Gn_Iam_Name_Card set up_data=now() where idx ={$cont_row['card_idx']}";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    
    $sql="update Gn_Iam_Contents set sample_order ='$sample_order',up_data=now() where idx ='$cont_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo "갱신되었습니다.";
}
if($mode == "update-gwc-contents-display"){
    $sql="update Gn_Iam_Contents_Gwc set public_display ='$sample_display',contents_westory_display='$sample_display',up_data=now() where idx ='$cont_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo 1;
}
if($mode == "delete_gwc_contents"){
    if(strpos($id, ",") !== false){
        $id_arr = explode(",", $id);
        for($i = 0; $i < count($id_arr); $i++){
            $sql_del = "delete from Gn_Iam_Contents_Gwc where idx='{$id_arr[$i]}'";
            mysqli_query($self_con,$sql_del);
        }
    }
    else{
        $sql_del = "delete from Gn_Iam_Contents_Gwc where idx='{$id}'";
        mysqli_query($self_con,$sql_del);
    }
    echo 1;
}
if($mode == "update-gwc-manage-price"){
    $sql = "update Gn_Member set gwc_manage_price='{$price}' where mem_code='{$mem_code}'";
    mysqli_query($self_con,$sql);
}
if($mode == "update-gwc-sehu-price"){
    $sql = "update Gn_Iam_Contents_Gwc set prod_sehu_price='{$price}' where idx='{$idx}'";
    mysqli_query($self_con,$sql);
}
if($mode == "goback_gwc_contents"){
    $id_arr = explode(",", $id);
    for($i = 0; $i < count($id_arr); $i++){
        $sql_del_ori = "delete from Gn_Iam_Contents_Gwc where ori_store_prod_idx='{$id_arr[$i]}'";
        mysqli_query($self_con,$sql_del_ori);
        
        $sql_ori_idx = "select ori_store_prod_idx from Gn_Iam_Contents_Gwc where idx='{$id_arr[$i]}'";
        $res_ori_idx = mysqli_query($self_con,$sql_ori_idx);
        $row_ori_idx = mysqli_fetch_array($res_ori_idx);

        $sql_up = "update Gn_Iam_Contents_Gwc set public_display='Y', contents_westory_display='Y' where idx='{$row_ori_idx[ori_store_prod_idx]}'";
        mysqli_query($self_con,$sql_up);

        $sql_del = "delete from Gn_Iam_Contents_Gwc where idx='{$id_arr[$i]}'";
        mysqli_query($self_con,$sql_del);
    }
    echo 1;
}
exit;

?>