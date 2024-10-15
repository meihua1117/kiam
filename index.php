<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include $_SERVER['DOCUMENT_ROOT'] . "/iam/inc/login_check.php";
$currentUrl = "https://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$_GET['preview'] = getQueryParam('preview', $currentUrl);
$_GET['smode'] = getQueryParam('smode', $currentUrl);
$_GET['slink'] = getQueryParam('slink', $currentUrl);
$_GET['smsg'] = getQueryParam('smsg', $currentUrl);

$language_index = $_COOKIE['language'];
if ($language_index == "") {
    $language_index = 1;
    @setcookie("language", $language_index, time() + 3600);
}
$language_sql = "select * from Gn_Iam_multilang where no = '$language_index'";
$language_res = mysqli_query($self_con,$language_sql);
$language_row = mysqli_fetch_array($language_res);
$lang = $_COOKIE['lang'] ? $_COOKIE['lang'] : "kr";
$sql = "select * from Gn_Iam_lang ";
$result = mysqli_query($self_con,$sql);
while ($row = mysqli_fetch_array($result)) {
    $MENU[$row['menu']][$row['pos']] = $row[$lang];
}

//added by amigo middle log 로그부분이므로 삭제하지 말것!!!!
$logs = new Logs("iamlog.txt", false);
//end
$is_artist = strstr($member_iam['special_type'], "6");

$Gn_contents_limit = get_search_key('people_except_count');

$Gn_contents_link_limit = get_search_key('contents_link_limit');

$point_ai = get_search_key('ai_card_making');

$group_card_point = get_search_key('group_card_point');

$new_open_url = get_search_key('cont_modal_new_open');

$jungo_market_view = get_search_key('jungo_market_view');

$cart_cnt = 0;

$mid = date("YmdHis") . rand(10, 99);
$Gn_point = $Gn_cash_point = 0;
if ($_GET['key1'] == 4) {
    $contents_count_per_page = 20;
} else {
    $contents_count_per_page = 6;
}
$pagination_count = 10;
$send_ids = "";
$send_ids_cnt = 0;
$alarm_time = get_search_key('content_alarm_time');
if ($alarm_time == "")
    $alarm_time = 10;
$contents_box_alarm = get_search_key('bring_web_address');

$mall_reg_ids = explode(",", get_search_key('mall_reg_menu_ids'));
if (in_array($_SESSION['iam_member_id'], $mall_reg_ids)) {
    $mall_reg_state = 1;
} else {
    $mall_reg_state = 0;
}

$card_url = "http://" . $HTTP_HOST . $_SERVER['REQUEST_URI'];
$card_title = $_SERVER['REQUEST_URI'];
$val1 = explode("?", $card_title);
$card_title = trim(substr(trim($val1[1]), 0, 10));

$card_send_point = get_search_key('card_send_point');

$contents_send_point = get_search_key('contents_send_point');

$contents_point_help = get_search_key('contents_point_help');

$wecon_zy_count = get_search_key('wecon_zy_count');

$open_domain = get_search_key('open_blank_cont_domain');

$user_ids = get_search_key('wecon_ids_show_con_count');
$id_arr = explode(",", $user_ids);
$is_show_con_count = false;
if (in_array($_SESSION['iam_member_id'], $id_arr))
    $is_show_con_count = true;

$gwc_exp_ids = get_search_key('gwc_exp_member');
$exp_id_arr = explode(",", $gwc_exp_ids);
$is_exp_mem = false;
if (in_array($_SESSION['iam_member_id'], $exp_id_arr)) {
    $is_exp_mem = true;
    $gwc_exp_con_cnt = get_search_key('gwc_exp_reg_contents_cnt');
}

$sql_service_gwc = "select gwc_name from Gn_Service where sub_domain like '%" . $HTTP_HOST . "'";
$res_service_gwc = mysqli_query($self_con,$sql_service_gwc);
$row_service_gwc = mysqli_fetch_array($res_service_gwc);

if ($member_iam['gwc_leb'] >= 1 && $member_iam['gwc_state'] == "1") {
    $gwc_mem = 1;
} else {
    $gwc_mem = 0;
}

$gwc_pay_mem = 0;

// $gwc_req_alarm = str_replace("\n", "<br>", get_search_key('gwc_req_alarm'));

$provider_arr = "'iamstore'";
if ($_GET['iamstore'] == "Y") {
    $sql_provider = "SELECT	mem_id FROM	Gn_Iam_Contents_Gwc WHERE provider_req_prod='Y' GROUP BY mem_id";
    $res_provider = mysqli_query($self_con,$sql_provider);
    while ($row_provider = mysqli_fetch_array($res_provider)) {
        $provider_arr .= ",'" . $row_provider['mem_id'] . "'";
    }
}
?>
<!DOCTYPE html>
<?
$card_num = $_GET['card_num'];
$request_url = $_SERVER['REQUEST_URI'];
$contents_anchor = $_GET['contents_anchor'];
if ($_GET['name_card'])
    $request_short_url = $_GET['name_card'];
if ($_GET['key1'] == "") //추천키워드
    $_GET['key1'] = 0;
if ($_GET['key2'] == "") //인기검색어
    $_GET['key2'] = 0;
if ($_GET['sort'] == "") //정렬기준
    $_GET['sort'] = 0;
if ($cur_win == "") {
    if ($card_owner == $_SESSION['iam_member_id'])
        $cur_win = "we_story"; //curwin이 주소에 없으면 my_info로 설정
    else
        $cur_win = "my_info"; //curwin이 주소에 없으면 my_info로 설정
}
if ($cur_win == "iam_mall") {
    $mall_type = $_GET['mall_type'];
    if (!$mall_type)
        $mall_type = "main_mall";
    $mall_type1 = $_GET['mall_type1'];
    if (!$mall_type1)
        $mall_type1 = "all";
}
//추천키워드
if ($cur_win == "we_story") {
    $rec_key = get_search_key('wecon_recom_keyword');
    if ($rec_key != '') {
        $rec_array = explode(",", $rec_key);
        $rec_count = count($rec_array) > 6 ? 6 : count($rec_array);
    } else {
        $rec_count = 0;
    }
    //인기검색어
    $interest_key = get_search_key('wecon_search_keyword');
    if ($interest_key != '') {
        $interest_array = explode(",", $interest_key);
        $interest_count = count($interest_array);
    } else {
        $interest_count = 0;
    }

    $post_display = 1; //댓글박스 보이기 1:보이기 0:감추기
    $card_sql = "select * from Gn_Iam_Name_Card where card_short_url = '$request_short_url'";
    $card_result = mysqli_query($self_con,$card_sql);
    $cur_card = mysqli_fetch_array($card_result);
}
$recent_post = 0;
if ($cur_win == "my_info") {
    if (strstr($request_url, '?')) { //카드링크가 있으면
        $request_short_url = explode('?', $request_url);
        if (strstr($request_short_url[1], '&')) { //링크에 다른 파라미터가 있을 경우
            $request_short_url_arr = explode('&', $request_short_url[1]);
            $request_short_url = $request_short_url_arr[0];
        } else {
            $request_short_url = $request_short_url[1]; //링크에 카드링크만 있을 경우
        }
        $request_short_url = substr($request_short_url, 0, 10);
    }
    $card_sql = "select * from Gn_Iam_Name_Card where card_short_url = '$request_short_url'";
    $card_result = mysqli_query($self_con,$card_sql);
    $cur_card = mysqli_fetch_array($card_result);

    $card_mem_sql = "select mem_id, site_iam,mem_name from Gn_Member where mem_code = '$card_owner_code'";
    $card_mem_result = mysqli_query($self_con,$card_mem_sql);
    $card_mem_row = mysqli_fetch_array($card_mem_result);
    $card_owner_site = $card_mem_row['site_iam']; //카드를 방문한 회원의 아이엠분양사명
    $card_owner = $card_mem_row['mem_id'];

    $video_upload_status = (get_search_key('content_modal_video_display') == "Y" || $member_iam['video_upload']) ? "Y" : "N";
} else if ($cur_win == "group-con") {
    if (strstr($request_url, '?')) { //카드링크가 있으면
        $group_card_url = explode('?', $request_url);
        if (strstr($group_card_url[1], '&')) { //링크에 다른 파라미터가 있을 경우
            $group_card_url_arr = explode('&', $group_card_url[1]);
            $group_card_url = $group_card_url_arr[0];
        } else {
            $group_card_url = $group_card_url[1]; //링크에 카드링크만 있을 경우
        }
        $group_card_url = substr($group_card_url, 0, 10);
        $card_sql = "select * from Gn_Iam_Name_Card where card_short_url = '$group_card_url'";
        $card_result = mysqli_query($self_con,$card_sql);
        $cur_card = mysqli_fetch_array($card_result);
    }
}

if ($HTTP_HOST != "kiam.kr") //분양사사이트이면
    $query = "select * from Gn_Iam_Service where sub_domain like 'http://" . $HTTP_HOST . "'";
else
    $query = "select * from Gn_Iam_Service where sub_domain like 'http://www.kiam.kr'";
$res = mysqli_query($self_con,$query);
$domainData = mysqli_fetch_array($res);
if ($_SESSION['iam_member_id']) {
    $sql = "select * from tjd_pay_result where buyer_id='{$_SESSION['iam_member_id']}' and end_status in ('Y','A')  and gwc_cont_pay=0 and 
            ((member_type like '%standard%' or member_type like '%professional%' or member_type like '%enterprise%') or 
            (((iam_pay_type = '' or iam_pay_type = '0' or iam_pay_type = '전문가') and member_type != '포인트충전')) or member_type='베스트상품') and payMethod <> 'POINT' order by end_date desc";
    /*$sql = "select * from tjd_pay_result where buyer_id='{$_SESSION['iam_member_id']}' and gwc_cont_pay=0 and 
            ((member_type like '%standard%' or member_type like '%professional%' or member_type like '%enterprise%') or 
            (((iam_pay_type = '' or iam_pay_type = '0' or iam_pay_type = '전문가') and member_type != '포인트충전')) or member_type='베스트상품') and payMethod <> 'POINT' order by end_date desc";*/
    $res_result = mysqli_query($self_con,$sql);
    $pay_data = mysqli_fetch_array($res_result);
}
if ($domainData['status'] == "N") {
    echo "<script>window.open(" . "'/payment_pop.php?index={$pay_data['orderNumber']}'" . ", \"notice_pop\", \"toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=600,height=350\");</script>";
}/*else if($pay_data['stop_yn'] == "Y" || $pay_data['end_status'] == "N"){
    echo "<script>window.open("."'/payment_pop.php?index={$pay_data['orderNumber']}&type=user'".", \"notice_pop\", \"toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=600,height=350\");</script>";
}*/

$auto_query = "select short_url from Gn_event where event_idx = '$domainData[auto_join_event_idx]'";
$auto_res = mysqli_query($self_con,$auto_query);
$auto_row = mysqli_fetch_array($auto_res);
$auto_link = $auto_row[0];
if (!$auto_link) {
    $auto_query = "select short_url from Gn_event where event_idx = (select auto_join_event_idx from Gn_Iam_Service where mem_id = 'iam1')";
    $auto_res = mysqli_query($self_con,$auto_query);
    $auto_row = mysqli_fetch_array($auto_res);
    $auto_link = $auto_row[0];
}
$first_card_idx = $domainData['profile_idx']; //분양사의 1번 카드아이디
$sql = "select mem_id, main_img1, main_img2, main_img3,video,video_status, card_short_url from Gn_Iam_Name_Card where idx = '$first_card_idx'";
$result = mysqli_query($self_con,$sql);
$main_card_row = mysqli_fetch_array($result);
$first_card_url = $main_card_row['card_short_url']; //분양사이트 1번 네임카드 url

$sql = "select site_iam,mem_code from Gn_Member where mem_id = '{$main_card_row['mem_id']}'";
$result = mysqli_query($self_con,$sql);
$row = mysqli_fetch_array($result);
$bunyang_site = $row['site_iam'];
$bunyang_site_manager_code = $row['mem_code'];

if ($HTTP_HOST != "kiam.kr") { //분양사사이트이면
    if ($domainData != null) {
        /*$curdate = strtotime(date('Y-m-d', time()));
        $startdate = strtotime($domainData['contract_start_date']);
        $enddate = strtotime($domainData['contract_end_date']);
        if (!($domainData['status'] == 'Y' && $curdate >= $startdate &&  $curdate <= $enddate)) {
            header('Content-Type: text/html; charset=UTF-8');
            echo "<script>alert('본 아이엠은 계약기간이 종료되었습니다. 관리자에게 문의해주세요.');history.go(-1);</script>";
            exit;
        }*/
    } else {
        header('Content-Type: text/html; charset=UTF-8');
        echo "<script>alert('아이엠 분양솔루션 사용권한이 없습니다. 담당자에게 확인바랍니다.');history.go(-1);</script>";
        exit;
    }
}
if ($_SESSION['iam_member_id']) {
    $sql_buy_prod_state = "select sum(TotPrice) from tjd_pay_result where gwc_cont_pay=1 and end_status='Y' and buyer_id='{$_SESSION['iam_member_id']}'";
    $res_buy_prod_state = mysqli_query($self_con,$sql_buy_prod_state);
    $row_buy_prod_state = mysqli_fetch_array($res_buy_prod_state);
    if ($row_buy_prod_state[0] >= 20000) {
        $gwc_pay_mem = 1;
    }
    $price_service = 0;
    $name_service = "";
    $sellerid_service = "";
    $Gn_mem_row = $member_iam;
    $Gn_point = $Gn_mem_row['mem_point'];
    $Gn_cash_point = $Gn_mem_row['mem_cash'];
    $user_site = $Gn_mem_row['site_iam']; //로긴한 회원의 아이엠분양사명
    $user_mem_code = $Gn_mem_row['mem_code'];
    $send_ids = $Gn_mem_row['send_ids']; //카드, 콘텐츠 전송한 아아디
    if ($send_ids != "") {
        $cnt = explode(",", $send_ids);
        $send_ids_cnt = count($cnt);
    }
    $date = date("Y-m-d H:i:s");
    $pay_query = "select count(*) from tjd_pay_result where (member_type like '%standard%' || member_type like '%professional%' || member_type like '%enterprise%') and buyer_id='{$_SESSION['iam_member_id']}' and end_status='Y' and `end_date` > '$date'";
    $pay_result = mysqli_query($self_con,$pay_query);
    $pay_row = mysqli_fetch_array($pay_result);
    $pay_status = $pay_row[0] + $Gn_mem_row['iam_type']; // 유료회원이면 true,무료회원이면 false
    $show_sql = "select show_iam_card,show_iam_like from Gn_Member where mem_id = '{$_SESSION['iam_member_id']}'";
    $show_result = mysqli_query($self_con,$show_sql);
    $show_row = mysqli_fetch_array($show_result);
    $show_my_iam_card = $show_row['show_iam_card'];

    $exp_status = 0;
    if ($domainData['service_type'] == 3 && !is_null($Gn_mem_row['exp_limit_date'])) {
        if ($Gn_mem_row['exp_start_status']) {
            $exp_status = 1;
        } else if ($Gn_mem_row['exp_mid_status']) {
            $limit_time = strtotime($Gn_mem_row['exp_limit_date']);
            $cur_time = strtotime(date("Y-m-d H:i:s", strtotime("+1 week")));
            if ($limit_time <= $cur_time)
                $exp_status = 2;
        } else if ($Gn_mem_row['exp_limit_status']) {
            $limit_time = strtotime($Gn_mem_row['exp_limit_date']);
            $cur_time = strtotime(date("Y-m-d H:i:s"));
            if ($limit_time <= $cur_time)
                $exp_status = 3;
        } else {
            $limit_time = strtotime($Gn_mem_row['exp_limit_date']);
            $cur_time = strtotime(date("Y-m-d H:i:s"));
            if ($limit_time <= $cur_time) {
                $exp_status = 4;
            }
        }
    }
    $sql_cart_cnt = "select count(*) from Gn_Gwc_Order where mem_id='{$_SESSION['iam_member_id']}' and page_type=1";
    $res_cart_cnt = mysqli_query($self_con,$sql_cart_cnt);
    $row_cart_cnt = mysqli_fetch_array($res_cart_cnt);
    $cart_cnt = $row_cart_cnt[0];
} else
    $pay_status = false;
if ($cur_win == "my_info") {
    $card_sql = "select * from Gn_Iam_Name_Card where card_short_url = '$request_short_url'";
    $card_result = mysqli_query($self_con,$card_sql);
    $cur_card = mysqli_fetch_array($card_result);
    $mem_sql = "select site_iam from Gn_Member where mem_id = '{$cur_card['mem_id']}'";
    $mem_res = mysqli_query($self_con,$mem_sql);
    $mem_row = mysqli_fetch_array($mem_res);
    $mem_site = $mem_row['site_iam'];
    $mem_site .= ($mem_site == "kiam" ? ".kr" : ".kiam.kr");
    if ($mem_site != $HTTP_HOST) {
        echo "<script>location.href='http://" . $mem_site . $_SERVER['REQUEST_URI'] . "';</script>";
    }
    if ($card_owner_code != $user_mem_code) { //로긴자가 다른 회원의 아이엠을 보기
        $share_sql = "select mem_id,iam_type from Gn_Member where mem_code = '$card_owner_code'";
        $share_res = mysqli_query($self_con,$share_sql);
        $share_row = mysqli_fetch_array($share_res);
        $share_member_id = $share_row['mem_id']; //로긴자가 방문하는 다른 회원
        $date = date("Y-m-d H:i:s");
        $pay_query = "select count(*) from tjd_pay_result where ( member_type like '%standard%' || member_type like '%professional%' || member_type like '%enterprise%') and buyer_id='$share_member_id' and end_status='Y' and `end_date` > '$date'";
        $pay_result = mysqli_query($self_con,$pay_query);
        $pay_row = mysqli_fetch_array($pay_result);
        $share_pay_status = $pay_row[0] + $share_row['iam_type']; //본사 유료회원이면 true,무료회원이면 false
        $share_admin_sql = "select count(*) from Gn_Iam_Service where mem_id = '$share_member_id'";
        $share_admin_res = mysqli_query($self_con,$share_admin_sql);
        $share_admin_row = mysqli_fetch_array($share_admin_res);
        $share_sub_admin = $share_admin_row[0];
        $show_sql = "select show_iam_card from Gn_Member where mem_id = '$share_member_id'";
        $show_result = mysqli_query($self_con,$show_sql);
        $show_row = mysqli_fetch_array($show_result);
        $show_share_iam_card = $show_row['show_iam_card'];
    } else {
        $share_pay_status = false;
        $show_share_iam_card = "N";
    }
    $_SESSION['recommender_code'] = $card_owner_code;
    @setcookie("recommender_code", $card_owner_code, time() + 3600);
    $_COOKIE['recommender_code'] = $card_owner_code;

    if ($cur_card['phone_display'] == 'N' && $card_owner != $_SESSION['iam_member_id'] && !isset($_GET['smode'])) {
        echo "<script>alert('비공개카드입니다.');history.go(-1);</script>";
        exit;
    }
    $myiam_count_sql = "update Gn_Iam_Name_Card set iam_click = iam_click + 1 where idx = {$cur_card['idx']}";
    mysqli_query($self_con,$myiam_count_sql) or die(mysqli_error($self_con));

    if ($domainData['sub_domain'] == "")
        $domainData['sub_domain'] = "http://kiam.kr/";
    if (!$card_owner) {
        $card_owner = trim($_SESSION['iam_member_id']);
    }
    $story_title1 = $cur_card['story_title1']; //내 소개
    $story_title2 = $cur_card['story_title2']; //현재 소속
    $story_title3 = $cur_card['story_title3']; //경력소개
    $story_myinfo = $cur_card['story_myinfo']; //나의 스토리 내소개
    $story_company = $cur_card['story_company']; //나의 스토리 소속
    $story_career = $cur_card['story_career']; //나의 스토리 경력
    $online1_check = $cur_card['online1_check']; //홈피1 체크
    $story_online1_text = $cur_card['story_online1_text']; //홈피1텍스트
    $story_online1 = $cur_card['story_online1']; //홈피1링크

    $online2_check = $cur_card['online2_check']; //홈피2 체크
    $story_online2_text = $cur_card['story_online2_text']; //홈피2텍스트
    $story_online2 = $cur_card['story_online2']; //홈피2링크
    $post_display = $cur_card['post_display']; //댓글박스 보이기 1:보이기 0:감추기

    $ai_map_gmarket = $cur_card['ai_map_gmarket']; //ai 생성 콘텐츠 1:지도 2:지마켓 0:일반
    $business_time = $cur_card['business_time']; //영업시간 설정

    //메인이미지
    //디폴트 이미지 꺼내기
    if ($cur_card['main_img1']) {
        $main_img1 = str_replace("http://www.kiam.kr", $cdn_ssl, $cur_card['main_img1']);
    } else {
        $main_img1 = str_replace("http://www.kiam.kr", $cdn_ssl, $main_card_row['main_img1']);
    }
    if ($cur_card['main_img2']) {
        $main_img2 = str_replace("http://www.kiam.kr", $cdn_ssl, $cur_card['main_img2']);
    } else {
        $main_img2 = str_replace("http://www.kiam.kr", $cdn_ssl, $main_card_row['main_img2']);
    }
    if ($cur_card['main_img3']) {
        $main_img3 = str_replace("http://www.kiam.kr", $cdn_ssl, $cur_card['main_img3']);
    } else {
        $main_img3 = str_replace("http://www.kiam.kr", $cdn_ssl, $main_card_row['main_img3']);
    }
    //로고
    if (is_null($cur_card['profile_logo'])) {
        $cur_card['profile_logo'] = "/iam/img/common/logo-2.png";
    }
} else if ($cur_win == "group-con") {
    if ($gkind == "" && $search_key == "")
        $gkind = "recommend";
    else if ($gkind == "" && $search_key != "")
        $gkind = "search_con";
    if ($gkind != "recommend" && $gkind != "mygroup" && $gkind != "search" && $gkind != "search_con") {
        $g_card_sql = "select * from Gn_Iam_Name_Card where card_short_url = '$group_card_url'";
        $g_card_res = mysqli_query($self_con,$g_card_sql);
        $group_card = mysqli_fetch_array($g_card_res);
        $post_display = $group_card['post_display']; //댓글박스 보이기 1:보이기 0:감추기
        $main_img1 = str_replace("http://www.kiam.kr", $cdn_ssl, $group_card['main_img1']);
        $main_img2 = str_replace("http://www.kiam.kr", $cdn_ssl, $group_card['main_img2']);
        $main_img3 = str_replace("http://www.kiam.kr", $cdn_ssl, $group_card['main_img3']);
        $visit_sql = "update gn_group_member set visit_date = now() where mem_id = '{$_SESSION['iam_member_id']}' and group_id=$gkind";
        mysqli_query($self_con,$visit_sql);
    }
    if ($_SESSION['iam_member_id']) {
        $sql = "select * from gn_group_member where mem_id = '{$_SESSION['iam_member_id']}'";
        $res = mysqli_query($self_con,$sql);
        $my_group = array();
        while ($row = mysqli_fetch_array($res)) {
            array_push($my_group, $row['group_id']);
        }
        $my_group = implode(",", $my_group);
    }
} else
    $post_display = 1;
$my_first_card = ($cur_win == "my_info" && $request_short_url == $first_card_url && $_SESSION['iam_member_id'] && !$_SESSION['iam_member_subadmin_id']) * 1;
$color = array(
    "#FA8258",
    "#8000FF",
    "#4B8A08",
    "#B404AE"
);
$cursor = 0;

if ($_GET['tutorial'])
    $end_link = str_replace("tutorial=Y", "tutorial=N&type=image", $_SERVER['REQUEST_URI']);
if ($cur_card['card_name'] == '') {
    //    $main_img1 = str_replace("http://www.kiam.kr", $cdn_ssl, $main_card_row['main_img1']);
}
$cur_card['card_position'] = str_replace(array("\r", "\n"), "", trim($cur_card['card_position']));

if (($_GET['key1'] == 4 && $_GET['key4'] != 3) || ($_SESSION['iam_member_id'] == 'iamstore' && $request_short_url != 'XSKvH4sRS4') || ($HTTP_HOST == "iamstore.kiam.kr" && (!$cur_win || $cur_win == "my_info") && $request_short_url != 'XSKvH4sRS4')) {
    $gwc_table = 1;
    $content_table_name = "Gn_Iam_Contents_Gwc";
} else {
    $gwc_table = 0;
    $content_table_name = "Gn_Iam_Contents";
}
header('X-Frame-Options: SAMEORIGIN');
function encodeKorean($matches)
{
    return urlencode($matches[0]);
}
?>

<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <? if (!$global_is_local) { ?>
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <? } ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <? if ($HTTP_HOST != "kiam.kr") { ?>
        <title>
            <?
            if ($domainData['web_theme'])
                echo $domainData['web_theme'];
            else
                echo $cur_card['card_title'];
            ?>
        </title>
    <? } else { ?>
        <title>아이엠 멀티명함 IAM Multicard</title>

    <? }
    $meta_img = preg_replace_callback('/[\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}]+/u', 'encodeKorean', get_meta_image(cross_image($main_img1)));
    ?>
    <meta name="naver-site-verification" content="90176b5d8f3b8ebed40060734107b11e6ecdd9d3" />
    <link rel="shortcut icon" href="/iam/img/common/icon-os.ico">
    <!--오픈그래프 (웹페이지 미리보기 -페이스북, 카카오톡)-->
    <meta property="og:title" content="아이엠 멀티명함 IAM Multicard">
    <!--meta name="description" content="아이엠으로 브랜딩하고 자동홍보하기,멀티명함,모바일명함,종이명함,자동콜백,리포트설문,멀티브랜딩"-->
    <!--제목-->
    <meta property="og:description" content="<?= $cur_card['card_name'] ? $cur_card['card_name'] : $domainData['mem_name'] ?>님의 명함 <?= $cur_card['card_company'] ?> <?= $cur_card['card_position'] ?>">
    <!--내용-->
    <!--meta property="og:video" content="<?= $cur_card['video'] ?>"-->
    <meta property="og:image" content="<?= $meta_img ?>">
    <!--이미지-->
    <meta property="og:type" content="website">
    <!--오픈그래프 끝-->

    <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
    <link rel="shortcut icon" href="/iam/img/common/iconiam.ico">
    <link rel="stylesheet" href="/iam/css/notokr.css">
    <link rel="stylesheet" href="/iam/css/font-awesome.min.css">
    <link rel="stylesheet" href="/iam/css/style.css">
    <link rel="stylesheet" href="/iam/css/new_style.css">
    <link rel="stylesheet" href="/iam/css/grid.min.css">
    <link rel="stylesheet" href="/iam/css/slick.min.css">
    <link rel="stylesheet" href="/iam/css/style_j.css">
    <link rel="stylesheet" href="/iam/css/iam.css">
    <link rel='stylesheet' href='/plugin/toastr/css/toastr.css' />
    <!-- ########## TODO COMMENT FOR TEST  패치할떄 해제해야함 ###########  -->
    <? if (!$global_is_local) { ?>
        <script src="//developers.kakao.com/sdk/js/kakao.min.js" charset="utf-8"></script>
    <? } ?>
    <script src="/iam/js/jquery-3.1.1.min.js"></script>
    <script src="/iam/js/slick.min.js"></script>
    <script src="/iam/js/main.js"></script>
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/rlatjd_fun.js"></script>
    <script src="/js/rlatjd.js"></script>
    <script src='/plugin/toastr/js/toastr.min.js'></script>
    <script src="/iam/js/layer.min.js" type="application/javascript"></script>
    <script src="/iam/js/chat.js"></script>
    <script src="/iam/js/qrcode.min.js"></script>
    <script>
        var shown = true;
        var sign_toggle = function() {
            if (shown) {
                $(".blink").css("color", "#0fc5b5");
                shown = false;
            } else {
                $(".blink").css("color", "#ec0e03");
                shown = true;
            }
        }
        setInterval(sign_toggle, 500);

        var contents_mode_gwc_ori = getCookie('contents_mode');
        var key1_gwc_show = '<?= $_GET['key1'] ?>';
        var wide_show = '<?= $_GET['wide'] ?>';
        var contents_mode_gwc_1 = '<?= $_GET['type'] ?>';
        jQuery(document).ready(
            function() {
                for (var i = 0; i < 3; i++) {
                    var id;
                    if ($('#alertid' + i).length)
                        id = $('#alertid' + i)[0].value;
                    if ($('#alertid' + i).length && document.cookie.search('Memo_iam' + id) == -1)
                        showIframeModal('/iam/notice_pop.php?id=' + id);
                }
            }
        );
        $(document).ready(function() {
            var cur_win_name = '<?= $cur_win ?>';
            if (key1_gwc_show == '4') {
                $("#wecon_search_div").hide();
                if (wide_show == 'Y' && $(window).width() > 650) {
                    $("#wrap").css('max-width', '85%');
                    $("#header").css('max-width', '85%');
                    $("#footer_menu").css('max-width', '85%');
                    $(".J_elem").css('max-width', '100%');
                    $("#wrap").css('padding', '0px');
                    //$("#show_prod_widly").show();
                } else {
                    $("#wrap").css('max-width', '777px');
                    $("#header").css('max-width', '768px');
                    $("#footer_menu").css('max-width', '768px');
                    $(".J_elem").css('max-width', '1088px');
                    $("#wrap").css('padding', '0 5px');
                    //$("#show_prod_widly").hide();
                }
                var scroll = "overflow-y: hidden;";
                var md_ht = $(".gwc_con_img").width();
                //console.log('md-ht:'+md_ht);
                if (contents_mode_gwc_1 == 'pin') {
                    //md_ht = md_ht * 1 / 2;
                    $(".media-inner").attr('style', 'max-height: 350px;' + scroll);
                    if ($(window).width() > 650) {
                        $(".media-inner").css('height', $(".media-inner").width() * 1 / 4 + 'px');
                    }
                } else {
                    //md_ht = md_ht;
                    $(".media-inner").attr('style', 'max-height: 650px;' + scroll);
                    $(".media-inner").css('height', 'auto');
                }
                var media_height = 'height:' + md_ht + 'px;';
                $(".gwc_con_img").attr('style', media_height);
            } else {
                $("#wecon_search_div").show();
                // var media_height = '';
                var scroll = "overflow-y: hidden;";
            }
            var bottom_wt = $("#bottom").width();
            var cont_wt = bottom_wt * 1 / 2 - 2;
            <? if ($_GET['key3'] == "1") { ?>
                var window_width = $(window).width();
                if (window_width < 500) {
                    AppScript.getPhoneStatus();
                }
            <? } ?>

            console.log("show_all= " + $("#show_all").val());

            if ('<?= $pre_win ?>' == 'my_info' && '<?= $cur_win ?>' == 'we_story') {
                show_wecon_guide();
            }
            if ('<?= $cur_win ?>' == "we_story") {
                $("#star").css("margin-top", $("#header").height() + "px");
            }
            if (checkMobile()) {
                $(".J_card_num").css("width", "49%");
                $(".J_card_num").css("margin-right", "1%");
                $("#star #bottom .content-item .media-wrap .media-inner").attr('style', 'max-height: 350px;' + scroll);
            } else {
                $(".J_card_num").css("width", "24%");
                $(".J_card_num").css("min-width", "160px");
                $(".J_card_num").css("margin-right", "1%");
            }
            <?php
            $cur_url = $_SERVER['REQUEST_URI'];
            if (strpos($cur_url, "?") === false || (($_GET['cur_win'] == "we_story" || $_GET['cur_win'] == "shared_receive" || $_GET['cur_win'] == "shared_send") && $_GET['type'] == "pin")) {
            ?>
                if ($(window).width() < 600) {
                    setCookie('contents_mode', 'image', 1, "");
                    $("#bottom").attr("style", "");
                    $(".content_area").attr('style', '');
                    $(".content-item").show();
                    $(".desc-wrap").show();
                    $(".info-wrap").show();
                    $(".image_mode").show();
                    $(".pin_mode").hide();
                    $(".service_title").show();
                    $("#show_contents_mode").prop("src", '/iam/img/main/icon-pin_list.png');
                } else {
                    //$(".movie_play").css("width","50px");
                    if (key1_gwc_show == '4') {
                        if (wide_show == 'Y') {
                            var bottom_wt = $("#bottom").width();
                            var cont_wt = bottom_wt * 1 / 4 - 2.5;
                        } else {
                            var bottom_wt = $("#bottom").width();
                            var cont_wt = bottom_wt * 1 / 2 - 2;
                        }
                        $(".content_area").attr('style', 'width:' + cont_wt + 'px;display:inline-block;vertical-align:top;');
                    } else {
                        $("#bottom").attr("style", "column-count:2");
                    }
                    $(".content-item").show();
                    $(".image_mode").hide();
                    $(".pin_mode").show();
                    $(".list").hide();
                    //$(".service_title").hide();
                    setCookie('contents_mode', 'pin', 1, "");

                    if ($(window).width() < 360) {
                        $(".percent").attr("style", "width:30px;font-size:12px");
                        $(".upper").attr("style", "font-size:9px;");
                        $(".downer").attr("style", "font-size:10px;");
                        $("#star #bottom .content-item .desc-wrap .desc .desc-inner .outer .order a").attr("style", "padding:1px 2px; font-size:10px;background: #99cc00;cursor: pointer;");
                        $(".buy_servicecon").attr("style", "left:-30px !important;");
                    } else if ($(window).width() < 420) {
                        $(".percent").attr("style", "width:43px;font-size:17px");
                        $(".downer").attr("style", "font-size:13px;");
                        $("#star #bottom .content-item .desc-wrap .desc .desc-inner .outer .order a").attr("style", "padding:1px 2px; font-size:10px;background: #99cc00;cursor: pointer;");
                        $(".buy_servicecon").attr("style", "left:-30px !important;");
                    }

                    //$(".info-wrap").hide();
                    $("#show_contents_mode").prop("src", '/iam/img/icon_image.png');
                    setCookie('contents_mode', 'pin', 1, "");
                }
            <?  } else if (strpos($cur_url, "?") !== false && (!isset($_GET['cur_win']) || $_GET['type'] == "image" || $_GET['cur_win'] == "my_info")) { ?>
                setCookie('contents_mode', 'image', 1, "");
                $("#bottom").attr("style", "");
                $(".content_area").attr('style', '');
                $(".content-item").show();
                //$(".content-item.list").hide();
                $(".desc-wrap").show();
                $(".info-wrap").show();
                $(".image_mode").show();
                $(".pin_mode").hide();
                $(".service_title").show();
                $("#show_contents_mode").prop("src", '/iam/img/main/icon-pin_list.png');
                $("#star #bottom .content-item .media-wrap .media-inner").attr('style', 'max-height: 650px;' + scroll);
                <? if ($_COOKIE['contents_mode'] == "pin" && (!isset($_GET['cur_win']) || $_GET['cur_win'] == "my_info")) { ?>
                    setCookie('contents_mode', 'image', 1, "");
                    //$(".movie_play").css("width","50px");
                    if (key1_gwc_show == '4') {
                        if (wide_show == 'Y') {
                            var bottom_wt = $("#bottom").width();
                            var cont_wt = bottom_wt * 1 / 4 - 2.5;
                        } else {
                            var bottom_wt = $("#bottom").width();
                            var cont_wt = bottom_wt * 1 / 2 - 2;
                        }
                        $(".content_area").attr('style', 'width:' + cont_wt + 'px;display:inline-block;vertical-align:top;');
                    } else {
                        $("#bottom").attr("style", "column-count:2");
                    }
                    $(".content-item .user-item").hide();
                    $(".content-item").show();
                    $(".image_mode").hide();
                    $(".pin_mode").show();
                    //$(".list").hide();
                    //$(".service_title").hide();
                    //$(".info-wrap").hide();
                    $("#show_contents_mode").prop("src", '/iam/img/icon_image.png');
                    setCookie('contents_mode', 'pin', 1, "");
                    $("#star #bottom .content-item .media-wrap .media-inner").attr('style', 'max-height: 350px;' + scroll);
                    if ($(window).width() < 360) {
                        $(".percent").attr("style", "width:30px;font-size:12px");
                        $(".upper").attr("style", "font-size:9px;");
                        $(".downer").attr("style", "font-size:10px;");
                        $("#star #bottom .content-item .desc-wrap .desc .desc-inner .outer .order a").attr("style", "padding:1px 2px; font-size:10px;background: #99cc00;cursor: pointer;");
                        $(".buy_servicecon").attr("style", "left:-30px !important;");
                    } else if ($(window).width() < 420) {
                        $(".percent").attr("style", "width:43px;font-size:17px");
                        $(".downer").attr("style", "font-size:13px;");
                        $("#star #bottom .content-item .desc-wrap .desc .desc-inner .outer .order a").attr("style", "padding:1px 2px; font-size:10px;background: #99cc00;cursor: pointer;");
                        $(".buy_servicecon").attr("style", "left:-30px !important;");
                    }
            <?  }
            } ?>
            <?php
            if ($_SESSION['iam_member_id']) {
                if ($_GET['key1'] == "4") { ?>
                    // var gwc_state = '<?= $member_iam['gwc_state'] ?>';
                    // if(gwc_state == "0"){
                    //     location.href="index.php";
                    // }
                    $(".buy_service_con").on('click', function() {
                        var gwc_leb = '<?= $member_iam['gwc_leb'] ?>';
                        if (gwc_leb == 4 && gwc_leb < 1) {
                            alert('본 회원은 굿슈머 이상 등급이어야 구매가 가능합니다');
                            $(".dropdown-menu").hide();
                        }
                    });
                <?php
                }
                $sql_receive = "select * from Gn_Item_Pay_Result where point_val=2 and receive_state=0 and pay_method='{$_SESSION['iam_member_id']}' and type='cardsend' order by pay_date asc limit 1";
                $res_recv = mysqli_query($self_con,$sql_receive);
                $row_recv = mysqli_fetch_array($res_recv);

                // if($row_recv['pay_method'] != NULL && strpos($_SERVER['REQUEST_URI'], "?") === false){
                if ($row_recv['pay_method'] != NULL) {
                    $sender_id = $row_recv['buyer_id'];
                    $message_recv = $row_recv['message'];
                    $message_recv = str_replace("\n", "<br>", $message_recv);
                    $message_recv = str_replace('"', "'", $message_recv);
                    $href_url = $row_recv['site'] . "&type=image&preview=Y";
                    $res_no = $row_recv['no'];
                ?>
                    $("#recv_msg").html("<?= $message_recv ?>");
                    $("#card_recv_popup").modal("show");
                <?php
                }
                $sql_con_receive = "select * from Gn_Item_Pay_Result where point_val=2 and buyer_id='{$_SESSION['iam_member_id']}' and type='contentsrecv' and message!='' and alarm_state=0 order by pay_date asc limit 1";
                $res_con_recv = mysqli_query($self_con,$sql_con_receive);
                $row_con_recv = mysqli_fetch_array($res_con_recv);
                if ($row_con_recv['buyer_id'] != NULL && strpos($_SERVER['REQUEST_URI'], "?") === false) {
                    $con_sender_id = $row_con_recv['pay_method'];
                    $con_message_recv = $row_con_recv['message'];
                    $con_message_recv = str_replace("\n", "<br>", $con_message_recv);
                    $con_message_recv = str_replace('"', "'", $con_message_recv);
                    $con_href_url = $row_con_recv['site'];
                    $con_res_no = $row_con_recv['no'];
                ?>
                    $("#recv_con_msg").html("<?= $con_message_recv ?>");
                    $("#contents_recv_popup").modal("show");
                <? }
                $sql_notice_receive = "select * from Gn_Item_Pay_Result where point_val=3 and buyer_id='{$_SESSION['iam_member_id']}' and type='noticerecv' and alarm_state=0 order by pay_date asc limit 1";
                $res_notice_recv = mysqli_query($self_con,$sql_notice_receive);
                $row_notice_recv = mysqli_fetch_array($res_notice_recv);
                if ($row_notice_recv['buyer_id'] != NULL && strpos($_SERVER['REQUEST_URI'], "?") === false) {
                    $notice_sender_id = $row_notice_recv['pay_method'];
                    $notice_message_recv = $row_notice_recv['message'];
                    $notice_message_recv = str_replace("\n", "<br>", $notice_message_recv);
                    $notice_message_recv = str_replace('"', "'", $notice_message_recv);
                    $notice_title_recv = $row_notice_recv['seller_id'];
                    $notice_title_recv = str_replace("\n", "<br>", $notice_title_recv);
                    $notice_title_recv = str_replace('"', "'", $notice_title_recv);
                    $notice_href_url = $row_notice_recv['site'];
                    $notice_res_no = $row_notice_recv['no'];
                    $msg_detail = "<p style='text-align:center;border-bottom: 1px solid grey;'>" . $notice_title_recv . "</p><br><p>" . $notice_message_recv . "</p><br><a href='" . $notice_href_url . "' target='_blank'>" . $notice_href_url . "</a>"; ?>
                    $("#recv_notice_msg").html("<?= $msg_detail ?>");
                    $("#notice_recv_popup").modal("show");
                <? } else if (strpos($_SERVER['REQUEST_URI'], "cur_win=shared_receive&modal=Y") !== false) { ?>
                    search_send_list("contentsrecv");
                    $("#contents_receive_list_modal").modal("show");
                <? } else if (strpos($_SERVER['REQUEST_URI'], "cur_win=shared_send&modal=Y") !== false) { ?>
                    $("#contents_send_list_modal").modal("show");
                    search_send_list('contentssend');
                <? } else if (strpos($_SERVER['REQUEST_URI'], "cur_win=unread_notice&modal=Y") !== false) { ?>
                    $("#notice_receive_list_modal").modal("show");
                    notice_send_recv_list('noticerecv');
                <? } else if (strpos($_SERVER['REQUEST_URI'], "cur_win=request_list") !== false) { ?>
                    get_request_list(1);
                <? } else if (strpos($_SERVER['REQUEST_URI'], "cur_win=unread_notice&box=send&modal=Y") !== false) { ?>
                    $("#notice_send_list_modal").modal("show");
                    notice_send_recv_list('noticesend');
                <? } else if ($_GET['edit_time'] == "Y") { ?>
                    edit_card('<?= $cur_card['card_short_url'] ?>');
                    $("#edit_card_business_time").focus();
                    $('body,html').animate({
                        scrollTop: 700,
                    }, 100);
                    <? } else if ($_GET['tutorial'] == "Y") {
                    if ($_GET['addafter'] == "Y") { ?>
                        $("#tutorial_addhome_popup").modal("hide");
                        $("#install-modalwindow").modal("show");
                    <?  } else { ?>
                        $("#pleaseinstall").modal("hide");
                        $("#install-modalwindow").modal("show");
                    <?  }
                } else if ($_SESSION['iam_member_subadmin_id']) {
                    $sql_point_state = "select mem_id, point_state from Gn_Iam_Service where mem_id='{$_SESSION['iam_member_subadmin_id']}'";
                    $res_point_state = mysqli_query($self_con,$sql_point_state);
                    $row_point_state = mysqli_fetch_array($res_point_state);
                    if ($row_point_state['point_state'] == 0) { ?>
                        alert("현재 포인트가 부족하여 오토회원 가입/콜백메시지 신청이 안되고 있습니다. 포인트를 충전해주세요.");
                        $.ajax({
                            type: "POST",
                            dataType: "json",
                            url: '/ajax/service_point_state.php',
                            data: {
                                point_use: true,
                                service_id: '<?= $row_point_state['mem_id'] ?>'
                            },
                            success: function(data) {

                            }
                        });
                    <? }
                }
                if ($_GET['req_provide'] == 'Y') { ?>
                    $("#contents_add_modal").modal("show");
                    $(".gallery").hide();
            <? }
            } ?>
            console.log('<?= $cur_win ?>', '<?= $_COOKIE['contents_mode'] ?>');
            if (cur_win_name == 'my_info') {
                $("#star #bottom .content-item .media-wrap .media-inner").attr('style', 'max-height: 100%;');
            }
        });

        function save_load(mem_id, cont_id, event_kind) { //event_kind : 0:노출,1:클릭
            $.ajax({
                type: "POST",
                dataType: "html",
                url: '/iam/ajax/contents.proc.php',
                data: {
                    post_type: 'history',
                    mem_id: mem_id,
                    cont_id: cont_id,
                    event_kind: event_kind
                },
                success: function(data) {
                    console.log(data);
                }
            });
        }

        function recvPhoneStatus(status) {
            if (status == "N") {
                if (getCookie('show_alarm_phonepos') == '') {
                    if (confirm("휴대폰의 위치기능이 ON으로 변경해야 방문 거리 정보를 볼수 있습니다. 위치기능을 ON으로 바꿀까요?")) {
                        AppScript.getPhonePos();
                        // recvPhonePos(lat, lng);
                    } else {
                        setCookie1('show_alarm_phonepos', '1', 3600, '');
                    }
                }
            } else {
                if (getCookie('show_alarm_phonestate') == '') {
                    AppScript.getPhonePos();
                }
            }
        }

        function recvPhonePos(lat, lng) {
            // alert(lat+":"+lng);
            setCookie1('phone_lat', lat, 10800, '');
            setCookie1('phone_lng', lng, 10800, '');
            setCookie1('show_alarm_phonepos', '1', 10800, '');
            setCookie1('show_alarm_phonestate', '1', 20, '');
            location.reload();
        }

        function recvPhonePos_show(lat, lng, idx, map_pos) {
            // $("a[id=distance_map_"+idx+"]").html('123' + "Km");
            var start_info = lat + '&' + lng;
            $.ajax({
                type: "POST",
                url: "/iam/ajax/show_naver_map.php",
                data: {
                    type: "distance_calc",
                    start_info: start_info,
                    end_info: map_pos
                },
                dataType: 'text',
                success: function(data) {
                    // $("#distance_map_"+idx).html(data + "Km");
                    $("a[id=distance_map_" + idx + "]").html(data + "Km");
                }
            });
        }

        function openAndroid() {
            var userAgent = navigator.userAgent.toLowerCase();
            if (userAgent.match(/chrome/)) {
                location.href = 'intent://onlyone#Intent;scheme=onlyoneapp;package=mms5.onepagebook.com.onlyonesms;end';
            } else {
                var iframe = document.createElement('iframe');
                iframe.style.visivility = 'hidden';
                iframe.src = 'onlyone://onlyoneapp';
                document.body.appendChild(iframe);
                document.body.removeChild(iframe);
            }
        }

        panelGroupState();
        mallGroupState();

        function addslashes(string) {
            return string.replace(/\\/g, '\\\\').
            replace(/\u0008/g, '\\b').
            replace(/\t/g, '\\t').
            replace(/\n/g, '\\n').
            replace(/\f/g, '\\f').
            replace(/\r/g, '\\r').
            replace(/'/g, '\\\'').
            replace(/"/g, '\\"');
        }

        function checkMobile() {
            var userAgent = navigator.userAgent || navigator.vendor || window.opera;
            // Windows Phone must come first because its UA also contains "Android"
            if (/windows phone/i.test(userAgent)) {
                return true;
            }
            if (/android/i.test(userAgent)) {
                if (/chrome/i.test(userAgent)) {
                    return false;
                }
                return true;
            }
            // iOS detection from: http://stackoverflow.com/a/9039885/177710
            if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
                return true;
            }
            return false;
        }

        function resize(img, maxW, maxH) {
            var maxWidth = maxW; // Max width for the image
            var maxHeight = maxH; // Max height for the image
            var ratio = 0; // Used for aspect ratio
            var width = img.width; // Current image width
            var height = img.height; // Current image height
            if (width > maxWidth) {
                ratio = maxWidth / width; // get ratio for scaling image
                $("#card_logo").css("width", maxWidth); // Set new width
                $("#card_logo").css("height", height * ratio); // Scale height based on ratio
                height = height * ratio; // Reset height to match scaled image
            }

            var width = $("#card_logo").width(); // Current image width
            var height = $("#card_logo").height(); // Current image height

            // Check if current height is larger than max
            if (height > maxHeight) {
                ratio = maxHeight / height; // get ratio for scaling image
                $("#card_logo").css("height", maxHeight); // Set new height
                $("#card_logo").css("width", width * ratio); // Scale width based on ratio
                width = width * ratio; // Reset width to match scaled image
            }
        }

        function set_language(idx) {
            //document.cookie = "language" + "=" + idx;
            //setCookie("language",idx,1);
            setCookie("lang", idx, 1, "iam");
            if ((window.location + "").indexOf("?") >= 0)
                location.href = window.location + "&lang=" + idx;
            else
                location.href = window.location + "?<?= $request_short_url . $user_mem_code ? $user_mem_code : $card_owner_code ?>&lang=" + idx;
            //location.reload();
        }

        //added by amigo 

        var busy = false;
        var limit = 10; //한개 페이지에 보여지는 샘플 갯수
        var offset = 0; //페이지에 보여지는 시작인덱스        

        function displaySample(lim, off) {
            $.ajax({
                type: "GET",
                async: false,
                url: "/iam/ajax/get_iam_sample.php",
                data: "limit=" + lim + "&offset=" + off + "&search_key=<?= $_GET['search_key'] ?>" + "&sample_type=<?= $_GET['cur_win'] ?>",
                cache: false,
                success: function(html) {
                    $(".sample_main").append(html);
                    window.busy = false;
                }
            });
        }

        function displayMall(lim, off) {
            var cont_mode = getCookie('contents_mode');
            $.ajax({
                type: "GET",
                async: false,
                url: "/iam/ajax/get_iam_mall.php",
                data: "limit=" + lim + "&offset=" + off + "&search_key=<?= $_GET['search_key'] ?>" + "&cur_win=<?= $_GET['cur_win'] ?>" + "&sort=<?= $_GET['sort'] ?>" +
                    "&mall_type=<?= $_GET['mall_type'] ?>" + "&mall_type1=<?= $_GET['mall_type1'] ?>" + "&art_type=<?= $_GET['art_type'] ?>" + "&contents_mode=" + cont_mode + "&member_iam=<?= $member_iam['site_iam'] ?>",
                cache: false,
                success: function(html) {
                    $("#div_mall").append(html);
                    if (html.length <= 10)
                        window.busy = true;
                    else
                        window.busy = false;
                }
            });
        }

        function getIamContact(card_owner, card_master, search_range, phone_count, page, paper_yn) {
            var search_str = $("#contact_search_str").val();
            if (search_str == undefined)
                search_str = "";
            $.ajax({
                type: "GET",
                url: "/iam/ajax/get_iam_contact.php",
                data: "search_str=" + search_str +
                    "&card_owner=" + card_owner +
                    "&card_master=" + card_master +
                    "&phone_count=" + phone_count +
                    "&search_range=" + search_range +
                    "&paper_yn=" + paper_yn +
                    "&page=" + page,
                success: function(html) {
                    $("#div_contact").html('');
                    $("#div_contact").append(html);
                }
            });
        }

        function getIamFriends(card_owner, card_master, phone_count, search_range, page) {
            var search_str = $("#friend_search_str").val();
            if (search_str == undefined)
                search_str = "";

            $.ajax({
                type: "GET",
                url: "/iam/ajax/get_iam_friends.php",
                data: "search_str2=" + search_str +
                    "&card_owner=" + card_owner +
                    "&card_master=" + card_master +
                    "&phone_count=" + phone_count +
                    "&search_range2=" + search_range +
                    "&search_type=" + $("#search_type").val() +
                    "&show_all=" + $("#show_all").val() +
                    "&page2=" + page,
                success: function(html) {
                    $("#div_friends").html('');
                    $("#div_friends").append(html);
                }
            });
        }

        function get_request_list(page) {
            $.ajax({
                type: "GET",
                url: "/iam/ajax/get_request_list.php",
                data: "page2=" + page,
                success: function(html) {
                    $("#request_list_table").append(html);
                }
            });
        }

        function getIamPaper(card_owner, card_master, search_range, phone_count, page) {
            var search_str = $("#paper_search_str").val();
            if (search_str == undefined)
                search_str = "";
            $.ajax({
                type: "GET",
                url: "/iam/ajax/get_paper_list.php",
                data: "search_str=" + search_str +
                    "&card_owner=" + card_owner +
                    "&card_master=" + card_master +
                    "&phone_count=" + phone_count +
                    "&search_range=" + search_range +
                    "&page=" + page,
                success: function(html) {
                    $("#papercard_list").html(html);
                }
            });
            /*$.ajax({
                type: "GET",
                url: "/iam/ajax/get_paper_list.php",
                success: function(html) {
                    $("#papercard_list").html(html);
                }
            });*/
        }

        function displayContents(lim, off) {
            $(".contents_per_page").load("ajax/get_iam_contents.php?" + "limit=" + lim + "&offset=" + off + "&search_key=<?= $_GET['search_key'] ?>" + "&cur_win=<?= $cur_win ?>" + "&card_owner=<?= $card_owner ?>" +
                "&name_card=<?= $_GET['name_card'] ?>" + "&key1=<?= $_GET['key1'] ?>" + "&key2=<?= $_GET['key2'] ?>" + "&sort=<?= $_GET['sort'] ?>" + "&request_uri=<?= $_SERVER['REQUEST_URI'] ?>",
                function(responseTxt, statusTxt, xhr) {
                    $(".content_main").append($(".contents_per_page").html());
                });
            window.busy = false;
        }

        $(window).scroll(function() {
            var isScrollAtBottom = window.innerHeight + Math.round(window.scrollY) >= document.body.offsetHeight;
            <? if ($_GET['cur_win'] == "best_sample" || $_GET['cur_win'] == "sample" || $_GET['cur_win'] == "recent_sample") { ?>
                //if ($(window).scrollTop() + $(window).height() > $(".sample_main").height() && !busy) {
                if (isScrollAtBottom && !busy) {
                    busy = true;
                    offset = limit + offset;
                    setTimeout(function() {
                        displaySample(limit, offset);
                    }, 500);
                }
            <? } else if ($_GET['cur_win'] == "iam_mall") { ?>
                //if ($(window).scrollTop() + $(window).height() > $(".sample_main").height() && !busy) {
                if (isScrollAtBottom && !busy) {
                    busy = true;
                    offset = limit + offset;
                    setTimeout(function() {
                        displayMall(limit, offset);
                    }, 500);
                }
            <? } ?>
        });

        //end block
        $(function() {
            $(document).ajaxStart(function() {
                    console.log("loading");
                    $("#ajax-loading").show();
                })
                .ajaxStop(function() {
                    $("#ajax-loading").delay(10).hide(1);
                });
            //added by amigo            
            <? if ($_GET['cur_win'] == "best_sample" || $_GET['cur_win'] == "sample" || $_GET['cur_win'] == "recent_sample") { ?>
                $('.sample_main').html('');
                offset = 0;
                displaySample(limit, offset);
            <? } else if ($_GET['cur_win'] == "iam_mall") { ?>
                $('#div_mall').html('');
                offset = 0;
                displayMall(limit, offset);
            <? } ?>
            //end block
        })

        function go_home() {
            var site1 = '<?= $member_iam['site'] ?>';
            var homeURL1 = "http://" + site1 + ".kiam.kr/ma.php";
            if (site1 == "kiam")
                homeURL1 = "http://kiam.kr/ma.php";
            else if (site1 == "")
                homeURL1 = "http://" + "<?= $HTTP_HOST ?>" + "/ma.php";
            window.open(homeURL1);
        }

        function show_prod_wide() {
            var url = '<?= $_SERVER['REQUEST_URI'] ?>';
            if (url.indexOf('&wide=Y') != -1) {
                url = url.replace('&wide=Y', '&wide=N');
            } else {
                if (url.indexOf('&wide=N') != -1) {
                    url = url.replace('&wide=N', '&wide=Y');
                } else {
                    url = url + '&wide=Y';
                }
            }
            location.href = url;
        }

        function go_mall_page(type, url) {
            alert('AI 모델 저작권은 AI 모델 창작자에게 저작권이 귀속되어 있으며, 무단 사용시 법적 책임을 지게 됩니다.');
            location.href = url;
        }
    </script>
    <script type="text/javascript" src="/jquery.lightbox_me.js"></script>
</head>

<body onclick="resizeImg()">
    <input type="hidden" id="mem_id" value="<?= $_SESSION['iam_member_id'] ?>">
    <!--input type = "hidden" id = "recent_post" value="<?= $recent_post ?>"-->
    <input type="hidden" id="exp_status" value="<?= $exp_status ?>">
    <?
    $query = "SELECT NO FROM tjd_sellerboard WHERE pop_yn='Y' ORDER BY DATE DESC LIMIT 0, 3";
    $res = mysqli_query($self_con,$query);
    $totalCnt    =  mysqli_num_rows($res);

    for ($i = 0; $i < $totalCnt; $i++) {
        $alert_ids    =  mysqli_fetch_array($res); ?>
        <input type="text" id="alertid<?= $i ?>" value="<?= $alert_ids[0] ?>" hidden>
    <? } ?>
    <iframe id="app" style="display: none"></iframe>
    <div id="zoomInVideo" style="z-index:1200;position: fixed;background-color: #ffffff;width :100%;max-width: 600px;height: 340px;top:50px;display: none;border-radius: 2%;border: 1px solid #b5b5b5;">
        <div style="position: absolute;top:-10px;right:-10px" id="btnZoomOut" onclick="clickZoomOut();">
            <img src="/iam/img/icon_close_black.svg">
        </div>
    </div>
    <div id='contents_page' style='left:0px; top:0px; width:320px; height:600px; position:absolute;  z-index:1000; display:none; background-color:white;'></div>
    <div id="wrap" class="common-wrap">
        <header id="header" style="position: fixed; z-index: 100; width:100%;max-width: 768px;">
            <!-- 헤더 시작 -->
            <div class="container J_elem">
                <div class="row" style="margin-left: auto;margin-right: auto;overflow: hidden">
                    <div class="col-12">
                        <div class="inner-wrap" style="text-align:center;padding : 0px">
                            <!--상단 좌측 로고이미지부분-->
                            <?
                            if (!$_SESSION['site_iam'])
                                $my_iam_link = "http://kiam.kr";
                            else
                                $my_iam_link = "javascript:go_my_con_page('" . $_SESSION['iam_member_id'] . "','" . $_SESSION['site_iam'] . "')";
                            ?>
                            <a href="<?= $my_iam_link ?>" style="float:left;">
                                <img src="/iam/img/common/logo-2.png" alt="온리원아이엠 로고 이미지" style="margin-top:15px;height:18px;">
                            </a>
                            <!--상단 로고이미지 부분-->
                            <? $home_link = $domainData['home_link'] == '' ? $domainData['sub_domain'] . '/?' . $first_card_url . $bunyang_site_manager_code : $domainData['home_link']; ?>
                            <div class="check-item" style="margin-top:13px;position: absolute;left:50%;top:5px;transform:translate(-50%,-50%);height:24px">
                                <a href="<?= $home_link ?>" target="_self">
                                    <img src="<?= $domainData['head_logo'] == '' ? '/iam/img/common/logo-2.png' : cross_image($domainData['head_logo']); ?>" alt="온리원아이엠 로고 이미지" style="margin-top:1px;height:35px;">
                                </a>
                            </div>
                        </div>
                        <!--다국어 아이콘부분-->
                        <!--div class="dropdown "  style="float:left; position: absolute; left: 55px; top: 12px; width: 18px;height:18px;">
                        <img class="dropdown-toggle" data-toggle="dropdown" src="img/icon_earth.png" >
                        <ul class="dropdown-menu" style="left:0px !important">
                            <li><a href="javascript:void(0)" onclick="set_language('kr')"><?= $language_row['korean'] ?></a></li>
                            <li><a href="javascript:void(0)" onclick="set_language('en')"><?= $language_row['english'] ?></a></li>
                            <li><a href="javascript:void(0)" onclick="set_language('cn')"><?= $language_row['china'] ?></a></li>
                            <li><a href="javascript:void(0)" onclick="set_language('jp')"><?= $language_row['japan'] ?></a></li>
                            <li><a href="javascript:void(0)" onclick="set_language('id')"><?= $language_row['india'] ?></a></li>
                            <li><a href="javascript:void(0)" onclick="set_language('fr')"><?= $language_row['france'] ?></a></li>
                        </ul>
                    </div-->
                        <!--상단 우측 프로필 부분-->
                        <?
                        /*if($_SESSION['iam_member_id']) {
                        if($user_mem_code == $card_owner_code){
                            if($member_iam['profile']){
                    ?>
                            <div class="dropdown " style="float:right; position: absolute; right:15px; top: 4px;width: 32px;height: 32px;border-radius: 50%;overflow: hidden;">
                                <img src="<?=cross_image($member_iam['profile'])?>" style="width:100%;height:100%;object-fit: cover;">
                            </div>
                    <?      }else{?>
                            <div class="dropdown " style="background:<?=$profile_color?>;padding:5px 0px;float:right; position: absolute; right:15px; top: 4px;width: 32px;height: 32px;border-radius: 50%;overflow: hidden;text-align:center">
                                <a class = "profile_font" style="color:white;width:100%;height:100%;object-fit: cover;"><?=mb_substr($member_iam['mem_name'],0,3,"utf-8")?></a>
                            </div>
                    <?      }
                        }else{
                    ?>        
                            <div class="dropdown " style="float:right; position: absolute; right:15px; top: 10px;">
                                <img src="/iam/img/iam_other.png" style="width:20px;margin:0px 15px 0px 0px;" data-site = '<?=$HTTP_HOST?>'>
                            </div>
                    <?  }
                    }else{?>
                        <div class="dropdown " style="float:right; position: absolute; right:15px; top: 10px;">
                            <img src="/iam/img/iam_logout.png" style="width:20px;margin:0px 15px 0px 0px;">
                        </div>
                    <?}*/ ?>
                        <div class="dropdown right" style="position:absolute;right:15px;top:10px;float:right;">
                            <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_top_menu.png" style="width:24px;height:24px">
                            <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;width: 180px;">
                                <?
                                $menu_site = explode(".", $HTTP_HOST);
                                if ($menu_site[0] == "www")
                                    $menu_host = "kiam";
                                else
                                    $menu_host = $menu_site[0];
                                if ($domainData['admin_iam_menu'] == 0) {
                                    $menu_query = "select * from Gn_Iam_Menu where site_iam='kiam' and menu_type='TR' and use_yn = 'y' order by display_order";
                                } else {
                                    $menu_query = "select * from Gn_Iam_Menu where site_iam='{$menu_host}' and menu_type='TR' and use_yn = 'y' order by display_order";
                                }
                                $menu_res = mysqli_query($self_con,$menu_query);
                                while ($menu_row = mysqli_fetch_array($menu_res)) {
                                    $func = str_replace("card_link", $request_short_url . $card_owner_code, $menu_row['move_url']);
                                    $func = str_replace("prewin", $cur_win, $func);
                                    $func = str_replace("card_name", $cur_card['card_name'], $func);
                                    if (strstr($func, "domainData['kakao']")) {
                                        $func = $domainData['kakao'];
                                        if ($func == "")
                                            $func = 'https://pf.kakao.com/_jVafC/chat';
                                    }
                                    if (!strstr($func, "javascript"))
                                        $target = "target=\"_blank\"";
                                    else
                                        $target = "";
                                    if ($menu_row['page_type'] == "payment" && !$is_pay_version)
                                        $func = "";
                                    if ($menu_row['page_type'] == "login" && !$_SESSION['iam_member_id'])
                                        $func = "/iam/login.php";
                                    if ($menu_row['page_type'] == "payment" && $is_pay_version) {
                                        if (strstr($func, "pay.php") && $_SESSION['iam_member_subadmin_id'] && $domainData['pay_link']) {
                                            $func = $domainData['pay_link'];
                                        }
                                    }
                                    $html = "";
                                    if ($func != "") {
                                        $html = "<li>";
                                        $html .= "<a href=\"" . $func . "\" $target>" .
                                            "<img src=\"" . $menu_row['img_url'] . "\" title=\"" . $menu_row['menu_desc'] . "\" style=\"height: 20px\">" .
                                            $menu_row['title'] . "</a>";
                                        $html .= "</li>";
                                    }
                                    echo $html;
                                } ?>
                                <!--li>
                                <a href="javascript:go_home()">
                                    <img src="/iam/img/menu/icon_bottom_home.png" title="셀링홈으로 가기" style="height: 20px"><?= "셀링홈으로 가기" ?>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:addMainBtn('<?= str_replace("'", "", $cur_card['card_name']) ?>','?<?= $request_short_url . $card_owner_code ?>');">
                                    <img src="/iam/img/menu/icon_home_add.png" style="height: 20px"><?= "폰 홈화면에 추가" ?>
                                </a>
                            </li>
                            <li>
                                <a href="https://tinyurl.com/hb2pp6n2" target = "_blank">
                                    <img src="/iam/img/menu/icon_help.png" style="height: 20px"><?= "이용매뉴얼" ?>
                                </a>
                            </li>
                            <li style="border-bottom:1px solid #ddd">
                                <a href="<?= $domainData['kakao'] ?>" target="_blank">
                                    <img src="/iam/img/menu/icon_ask.png" style="height: 20px"><?= "관리자에게 문의" ?>
                                </a>
                            </li>
                            <li>
                                <a href="https://play.google.com/store/apps/details?id=mms.onepagebook.com.onlyonesms" target = "_blank">
                                    <img src="/iam/img/menu/icon_install.png" style="height: 20px"><?= "IAM 앱설치" ?>
                                </a>
                            </li>
                            <? if ($is_pay_version) {
                                if ($_SESSION['iam_member_subadmin_id'] && $domainData['pay_link']) { //payment 
                            ?>
                                    <li style="border-bottom:1px solid #ddd">
                                        <a href="<?= $domainData['pay_link'] ?>" target="_self">
                                            <img src="/iam/img/menu/icon_pay.png" style="height: 20px"><?= "IAM 플랫폼 결제" ?>
                                        </a>
                                    </li>
                                <? } else { ?>
                                    <li style="border-bottom:1px solid #ddd">
                                        <a href="/iam/pay.php" target="_self">
                                            <img src="/iam/img/menu/icon_pay.png" style="height: 20px"><?= "IAM 플랫폼 결제" ?>
                                        </a>
                                    </li>
                                <?  } ?>
                                    <li style="border-bottom:1px solid #ddd">
                                        <a href="javascript:sns_sendSMS('N')">
                                            <img src="/iam/img/main/icon-kakaoimg.png"  style="height: 15px"><?= "IAM 주소문자발송" ?>
                                        </a>
                                    </li>
                                <? }
                                ?>
                            
                            <li>
                            <? if ($_SESSION['iam_member_id']) { ?>
                                <a href="javascript:iam_mystory('cur_win=group-con');">
                            <? } else { ?>
                                <a href="/iam/login.php">
                            <? } ?>
                                    <img src="/iam/img/menu/icon_group.png" title="그룹페이지로 가기" style="height: 18px"><?= "그룹페이지로 가기" ?>
                                </a>
                            </li-->
                            </ul>
                        </div>
                        <? if ($_GET['wide'] != 'Y') { ?>
                            <a href="javascript:showContentsList('<?= $_GET['key1'] ?>')" style="margin-right:10px;position: absolute;right: 50px;top: 11px;">
                                <? if (!$_COOKIE['contents_mode'] || $_COOKIE['contents_mode'] == "image") { ?>
                                    <img src="/iam/img/main/icon-pin_list.png" id="show_contents_mode" title="<?= $MENU['TOP_MENU']['ICON_PIN']; ?>" style="height: 18px">
                                    <? //}else if($_COOKIE['contents_mode'] == "pin"){
                                    ?>
                                    <!-- <img src="img/icon_list.svg" id = "show_contents_mode" title="<?= $MENU['TOP_MENU']['ICON_LIST']; ?>" style="height: 18px"> -->
                                <? } else { ?>
                                    <img src="/iam/img/icon_image.png" id="show_contents_mode" title="<?= $MENU['TOP_MENU']['ICON_IMAGE']; ?>" style="height: 18px">
                                <? } ?>
                            </a>
                        <? } ?>
                        <?/*if($_GET['key1'] == 4){?>
                    <a id="show_prod_widly" href="javascript:show_prod_wide()" style="margin-right:10px;position: absolute;right: 90px;top: 11px;">
                        <img src="/iam/img/main/icon-findgp.png" title="" style="height: 18px">
                    </a>
                    <?}*/ ?>
                    </div>
                </div>
                <nav id="middle-nav">
                    <!-- 중단 네비게이션 시작 -->
                    <div style="background:white;border-bottom:2px solid #ddd;display:flex;justify-content: space-between;">
                        <script>
                            function openShop() {
                                <? if ($is_pay_version) { ?>
                                    location.href = "?cur_win=iam_mall";
                                <? } else { ?>
                                    location.href = "/iam/index_shop.php";
                                <? } ?>
                            }

                            function showSample() {
                                location.href = "?cur_win=best_sample";
                            }

                            function mall_type_submit() {
                                location.href = "?cur_win=iam_mall&mall_type=" + $("#mall_type").val();
                            }

                            function goIamHome() {
                                var navCase = navigator.userAgent.toLocaleLowerCase();
                                if (navCase.search("android") > -1) {
                                    AppScript.goIamHome('<?= $member_iam['site_iam'] ?>');
                                } else {
                                    var site = '<?= $member_iam['site_iam'] ?>';
                                    var homeURL = "https://" + site + ".kiam.kr/m";
                                    if (site == "kiam")
                                        homeURL = "https://kiam.kr/m";
                                    else if (site == "")
                                        homeURL = "https://" + "<?= $HTTP_HOST ?>" + "/m";
                                    // window.open(homeURL);
                                    location.href = homeURL;
                                }
                            }
                        </script>
                        <?
                        if ($domainData['admin_iam_menu'] == 0) {
                            $menu_query = "select * from Gn_Iam_Menu where site_iam='kiam' and menu_type='T' and use_yn = 'Y' order by display_order";
                        } else {
                            $menu_query = "select * from Gn_Iam_Menu where site_iam='{$menu_host}' and menu_type='T' and use_yn = 'Y' order by display_order";
                        }
                        $menu_res = mysqli_query($self_con,$menu_query);
                        while ($menu_row = mysqli_fetch_array($menu_res)) {
                            $img_str = explode(".", $menu_row['img_url']);
                            $img_default = $img_str[0];
                            $img_active = $img_default . "_active";
                            $img_ext = "." . $img_str[1];
                            $func = str_replace("card_link", $request_short_url . $card_owner_code, $menu_row['move_url']);
                            $func = str_replace("prewin", $cur_win, $func);
                            if ($_SESSION['iam_member_id'] == "" && $menu_row['page_type'] == "alarm")
                                $func = "location.href='/iam/login.php'";
                            $html = "<li onclick=\"" . $func . "\" class=\"nav-item top\" title=\"" . $menu_row['title'] . "\"";
                            $menu_cur_win = $cur_win;
                            if ($cur_win == "we_story") {
                                if ($key1 == 4)
                                    $menu_cur_win = "gmarket";
                                else if ($key1 == 3)
                                    $menu_cur_win = "calliya";
                            } else if ($cur_win == "shared_receive" || $cur_win == "shared_send" || $cur_win == "unread_post" || $cur_win == "unread_notice" || $cur_win == "request_list") {
                                $menu_cur_win = "alarm";
                            }
                            if ($menu_row['page_type'] == $menu_cur_win) {
                                $html .= "\" style='border-bottom:2px solid #99cc00'\">";
                                $img = $img_active . $img_ext;
                            } else {
                                $html .= "\">";
                                $img = $img_default . $img_ext;
                            }
                            $html .= "<img src=\"" . $img . "\" class=\"iconperson\">";
                            if ($menu_cur_win == "alarm") {
                                $html .= "<label class=\"label label-sm share_count\" id = \"share_count\"></label>";
                            }
                            $html .= "</li>";
                            echo $html;
                        }
                        ?>
                    </div>
                </nav><!-- // 중단 네비게이션 끝 -->
                <? if ($cur_win == "shared_receive" || $cur_win == "shared_send" || $cur_win == "unread_post" || $cur_win == "unread_notice" || $cur_win == "request_list") { ?><!-- 수발신콘텐츠 버튼 -->
                    <div class="panel-group">
                        <div style="margin: 5px;display:flex;justify-content: space-between;">
                            <div class="mypage_menu">
                                <!-- <div>
                            <p style="font-size:14px;padding:9px 12px"></p>
                        </div> -->
                                <div style="margin-right: 5px;display:flex;float: right;">
                                    <button class="btn  btn-link" onclick="iam_mystory('cur_win=shared_receive&modal=Y')" title="<?= $MENU['IAM_MENU']['M7_TITLE']; ?>" style="display:flex;padding:6px 3px">
                                        <? if ($cur_win == 'shared_receive') { ?>
                                            <!--img src="/iam/img/menu/icon_alarm_recvcon_active.png" style="height: 22px;"-->
                                            <p style="font-size:14px;color:#99cc00">콘수신</p>
                                        <? } else { ?>
                                            <!--img src="/iam/img/menu/icon_alarm_recvcon.png" style="height: 22px;"-->
                                            <p style="font-size:14px;color:black">콘수신</p>
                                        <? } ?>
                                        <label class="label label-sm" id="share_recv_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                    </button>
                                    <button class="btn  btn-link" onclick="iam_mystory('cur_win=shared_send&modal=Y')" title="<?= $MENU['IAM_MENU']['M8_TITLE']; ?>" style="display:flex;padding:6px 3px">
                                        <? if ($cur_win == 'shared_send') { ?>
                                            <!--img src="/iam/img/menu/icon_alarm_sendcon_active.png" style="height: 22px;"-->
                                            <p style="font-size:14px;color:#99cc00">콘전송</p>
                                        <? } else { ?>
                                            <!--img src="/iam/img/menu/icon_alarm_sendcon.png" style="height: 22px;"-->
                                            <p style="font-size:14px;color:black">콘전송</p>
                                        <? } ?>
                                        <label class="label label-sm" id="share_send_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                    </button>
                                    <button class="btn  btn-link" onclick="iam_mystory('cur_win=unread_post')" title="<?= '댓글알림' ?>" style="display:flex;padding:6px 3px">
                                        <? if ($cur_win == 'unread_post') { ?>
                                            <!--img src="/iam/img/menu/icon_alarm_post_active.png" style="height: 22px;"-->
                                            <p style="font-size:14px;color:#99cc00">댓글수신</p>
                                        <? } else { ?>
                                            <!--img src="/iam/img/menu/icon_alarm_post.png" style="height: 22px;"-->
                                            <p style="font-size:14px;color:black">댓글수신</p>
                                        <? } ?>
                                        <label class="label label-sm" id="share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                    </button>
                                    <a class="btn  btn-link" href="/iam/mypage_post_lock.php" title="<?= '댓글알림' ?>" style="display:flex;padding:6px 3px">
                                        <p style="font-size:14px;color:black">댓글차단해지</p>
                                        <label class="label label-sm" id="share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                    </a>
                                    <button class="btn  btn-link" onclick="iam_mystory('cur_win=request_list')" title="<?= '신청알림' ?>" style="display:flex;padding:6px 3px">
                                        <? if ($cur_win == 'request_list') { ?>
                                            <!--img src="/iam/img/menu/icon_alarm_post_active.png" style="height: 22px;"-->
                                            <p style="font-size:14px;color:#99cc00">이벤트신청</p>
                                        <? } else { ?>
                                            <!--img src="/iam/img/menu/icon_alarm_post.png" style="height: 22px;"-->
                                            <p style="font-size:14px;color:black">이벤트신청</p>
                                        <? } ?>
                                        <label class="label label-sm" id="share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                    </button>
                                </div>
                                <div style="margin-right: 5px;display:flex;float:right;">
                                    <a class="btn  btn-link" title="" href="/iam/gwc_order_list.php" style="display:flex;padding:6px 3px">
                                        <p style="font-size:14px;color:black">주문목록</p>
                                        <label class="label label-sm" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                    </a>
                                    <? if ($_SESSION['iam_member_subadmin_id'] == $_SESSION['iam_member_id']) { ?>
                                        <a class="btn  btn-link" title="<?= '공지알림'; ?>" href="?cur_win=unread_notice&box=send&modal=Y" style="display:flex;padding:6px 3px">
                                            <? if ($_GET['box'] == 'send') { ?>
                                                <!--img src="/iam/img/menu/icon_alarm_notice_active.png" style="height: 22px;"-->
                                                <p style="font-size:14px;color:#99cc00">공지전송</p>
                                            <? } else { ?>
                                                <!--img src="/iam/img/menu/icon_alarm_notice.png" style="height: 22px;"-->
                                                <p style="font-size:14px;color:black">공지전송</p>
                                            <? } ?>
                                            <label class="label label-sm" id="notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                        </a>
                                        <a class="btn  btn-link" title="<?= '공지알림'; ?>" href="?cur_win=unread_notice&modal=Y" style="display:flex;padding:6px 3px">
                                            <? if ($_GET['box'] != 'send' && $cur_win == 'unread_notice') { ?>
                                                <!--img src="/iam/img/menu/icon_alarm_notice_active.png" style="height: 22px;"-->
                                                <p style="font-size:14px;color:#99cc00">공지수신</p>
                                            <? } else { ?>
                                                <!--img src="/iam/img/menu/icon_alarm_notice.png" style="height: 22px;"-->
                                                <p style="font-size:14px;color:black">공지수신</p>
                                            <? } ?>
                                            <label class="label label-sm" id="notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                        </a>
                                    <? } else { ?>
                                        <button class="btn  btn-link" title="<?= '공지알림'; ?>" onclick="iam_mystory('cur_win=unread_notice')" style="display:flex;padding:6px 3px">
                                            <? if ($cur_win == 'unread_notice') { ?>
                                                <!--img src="/iam/img/menu/icon_alarm_notice_active.png" style="height: 22px;"-->
                                                <p style="font-size:14px;color:#99cc00">공지</p>
                                            <? } else { ?>
                                                <!--img src="/iam/img/menu/icon_alarm_notice.png" style="height: 22px;"-->
                                                <p style="font-size:14px;color:black">공지</p>
                                            <? } ?>
                                            <label class="label label-sm" id="notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                        </button>
                                    <? } ?>
                                    <? if ($is_pay_version) { ?>
                                        <!-- <button class="btn  btn-link" title = "" onclick="open_payment_item()" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">판매</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </button> -->
                                        <a class="btn  btn-link" title="" href="/iam/mypage_refer.php" style="display:flex;padding:6px 3px">
                                            <p style="font-size:14px;color:black">추천</p>
                                            <label class="label label-sm" id="sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                        </a>
                                        <a class="btn  btn-link" title="" href="/iam/mypage_payment.php" style="display:flex;padding:6px 3px">
                                            <p style="font-size:14px;color:black">결제</p>
                                            <label class="label label-sm" id="sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                        </a>
                                        <a class="btn  btn-link" title="" href="/iam/mypage_payment_item.php" style="display:flex;padding:6px 3px">
                                            <p style="font-size:14px;color:black">판매</p>
                                            <label class="label label-sm" id="sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                        </a>
                                    <? } ?>
                                    <? if ($member_iam['service_type'] < 2) {
                                        $report_link = "/iam/mypage_report_list.php";
                                    } else {
                                        $report_link = "/iam/mypage_report.php";
                                    }
                                    ?>
                                    <a class="btn  btn-link" title="" href="<?= $report_link ?>" style="display:flex;padding:6px 3px">
                                        <p style="font-size:14px;color:black">리포트</p>
                                        <label class="label label-sm" id="sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                    </a>
                                    <!-- <a class="btn  btn-link" title = "" href="/?cur_win=unread_notice&req_provide=Y" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공급사신청</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                <? } else if ($cur_win == "best_sample" || $cur_win == "sample" || $cur_win == "recent_sample") { ?>
                    <div class="panel-group">
                        <div style="display: flex; margin: 5px;justify-content: space-between;">
                            <div>
                                <p style="font-size:14px;padding:9px 12px">IAM샘플</p>
                            </div>
                            <div style="margin-right: 20px;display:flex">
                                <button class="btn  btn-link" onclick="iam_mystory('cur_win=best_sample')" title="<?= $MENU['IAM_MENU']['M10_TITLE']; ?>" style="
                        <?= $cur_win == 'best_sample' ? 'color:#99cc00;' : 'color:black' ?>;padding:10px">
                                    <h5><?= $MENU['IAM_MENU']['M10']; ?></h5>
                                </button>
                                <button class="btn  btn-link" onclick="iam_mystory('cur_win=sample')" title="<?= $MENU['IAM_MENU']['M11_TITLE']; ?>" style="
                        <?= $cur_win == 'sample' ? 'color:#99cc00;' : 'color:black' ?>;padding:10px">
                                    <h5><?= $MENU['IAM_MENU']['M11']; ?></h5>
                                </button>
                                <button class="btn  btn-link" onclick="iam_mystory('cur_win=recent_sample')" title="<?= $MENU['IAM_MENU']['M12_TITLE']; ?>" style="
                        <?= $cur_win == 'recent_sample' ? 'color:#99cc00;' : 'color:black' ?>;padding:10px">
                                    <h5><?= $MENU['IAM_MENU']['M12']; ?></h5>
                                </button>
                            </div>
                        </div>
                    </div>
                <? } else if ($cur_win == "iam_mall") { ?>
                    <? if ($_GET['mall_type1'] == "iam") { ?>
                        <script>
                            $(document).ready(function() {
                                $(document).bind("contextmenu", function(e) {
                                    alert('AI모델 저작권은 제작자에게 귀속되어 있으므로 무단 사용시 법적 제제를 받을 수 있습니다');
                                    return false;
                                });
                            });
                        </script>
                    <? } ?>
                    <div class="panel-group">
                        <div style="display:flex;justify-content: space-between;">
                            <div style="display: flex; margin-top: 5px;">
                                <button class="btn  btn-link" onclick="openShop()" title="<?= $MENU['IAM_MENU']['M14_TITLE']; ?>" style="color:black;padding:10px">
                                    <p style="font-size:14px">위드유</p>
                                </button>
                                <!--button class="btn  btn-link"  onclick="location.href = '/iam/index_shop.php'" title = "<?= $MENU['IAM_MENU']['M15_TITLE']; ?>" style="color:black;padding:5px">
                            <h5><?= $MENU['IAM_MENU']['M15']; ?></h5>
                        </button-->
                            </div>
                            <div style="display: flex; margin: 5px;">
                                <button class="btn  btn-link" onclick="iam_mystory('cur_win=iam_mall&mall_type=best_mall')" title="" style="color:<?= ($mall_type == 'best_mall') ? '#99cc00' : 'black' ?>;padding:5px;">
                                    <h5>베스트</h5>
                                </button>
                                <button class="btn  btn-link" onclick="iam_mystory('cur_win=iam_mall&mall_type=my_mall')" title="<?= $MENU['IAM_MENU']['M11_TITLE']; ?>" style="color:<?= ($mall_type == 'my_mall') ? '#99cc00' : 'black' ?>;padding:5px;">
                                    <h5>내상품</h5>
                                </button>
                                <button class="btn  btn-link" onclick="iam_mystory('cur_win=iam_mall&mall_type=my_mall_like')" title="<?= $MENU['IAM_MENU']['M12_TITLE']; ?>" style="color:<?= $mall_type == 'my_mall_like' ? '#99cc00' : 'black' ?>;padding:5px">
                                    <h5>찜한상품</h5>
                                </button>
                                <select name="mall_type" id="mall_type" style="font-size:12px;margin-right:10px;border: 1px solid #ddd;background: #fff;height: 37px;border-radius: 5px;" onchange="mall_type_submit()">
                                    <option value="" <? if ($mall_type == "best_mall" || $mall_type == "my_mall_like") { ?>selected<? } ?>></option>
                                    <option value="main_mall" <? if ($mall_type == "main_mall") { ?>selected<? } ?>>전체몰</option>
                                    <option value="sub_mall" <? if ($mall_type == "sub_mall") { ?>selected<? } ?>>분양사몰</option>
                                    <option value="my_mall" <? if ($mall_type == "my_mall") { ?>selected<? } ?>>마이몰</option>
                                </select>
                            </div>
                        </div>
                        <div style="background-color:white;padding: 10px;" class="mall-group">
                            <ul class="nav nav-tabs mall-nav-tabs">
                                <? if ($jungo_market_view != "N") {
                                    $mall_width = "16.65%";
                                } else {
                                    $mall_width = "20%";
                                } ?>

                                <li class="nav-item <? if ($mall_type1 == 'all') echo 'active' ?>" style="border: 1px solid #ddd;width:<?= $mall_width ?>;">
                                    <a class="nav-link" style="padding:5px" href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=all' ?>">전체</a>
                                </li>
                                <li class="nav-item  <? if ($mall_type1 == 'msg_set') echo 'active' ?>" style="border: 1px solid #ddd;width:<?= $mall_width ?>;">
                                    <a class="nav-link" style="padding:5px" href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=msg_set' ?>">메시지</a>
                                </li>
                                <li class="nav-item <? if ($mall_type1 == 'card') echo 'active' ?>" style="border: 1px solid #ddd;width:<?= $mall_width ?>;">
                                    <a class="nav-link" style="padding:5px" href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=card' ?>">재능마켓</a>
                                </li>
                                <li class="nav-item <? if ($mall_type1 == 'iam') echo 'active' ?>" style="border: 1px solid #ddd;width:<?= $mall_width ?>;">
                                    <a class="nav-link" style="padding:5px" href="javascript:go_mall_page('iam', '<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=iam' ?>')">홍보대사</a>
                                </li>
                                <? if ($jungo_market_view != "N") { ?>
                                    <li class="nav-item <? if ($mall_type1 == 'service') echo 'active' ?>" style="border: 1px solid #ddd;width:<?= $mall_width ?>;">
                                        <a class="nav-link" style="padding:5px" href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=service' ?>">중고마켓</a>
                                    </li>
                                <? } ?>
                                <li class="nav-item <? if ($mall_type1 == 'gallery') echo 'active' ?>" style="border: 1px solid #ddd;width:<?= $mall_width ?>;">
                                    <a class="nav-link" style="padding:5px" href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=gallery' ?>">갤러리</a>
                                </li>
                            </ul>
                            <? if ($mall_type1 == 'gallery') { ?>
                                <div>
                                    <div style="display: flex;justify-content: space-evenly;">
                                        <div><input type="radio" class="art_type" value="0" <? if (!isset($_GET['art_type'])) echo "checked"; ?> onclick="location.href='<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=gallery' ?>'"><label style="margin: 15px 5px;">전체</label></div>
                                        <div><input type="radio" class="art_type" value="11" <? if ($_GET['art_type'] == 11) echo "checked"; ?> onclick="location.href='<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=gallery&art_type=11' ?>'"><label style="margin: 15px 5px;">회화</label></div>
                                        <div><input type="radio" class="art_type" value="12" <? if ($_GET['art_type'] == 12) echo "checked"; ?> onclick="location.href='<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=gallery&art_type=12' ?>'"><label style="margin: 15px 5px;">판화</label></div>
                                        <div><input type="radio" class="art_type" value="13" <? if ($_GET['art_type'] == 13) echo "checked"; ?> onclick="location.href='<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=gallery&art_type=13' ?>'"><label style="margin: 15px 5px;">조형</label></div>
                                        <div><input type="radio" class="art_type" value="14" <? if ($_GET['art_type'] == 14) echo "checked"; ?> onclick="location.href='<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=gallery&art_type=14' ?>'"><label style="margin: 15px 5px;">사진</label></div>
                                        <div><input type="radio" class="art_type" value="15" <? if ($_GET['art_type'] == 15) echo "checked"; ?> onclick="location.href='<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=gallery&art_type=15' ?>'"><label style="margin: 15px 5px;">AI아트</label></div>
                                        <div><input type="radio" class="art_type" value="16" <? if ($_GET['art_type'] == 16) echo "checked"; ?> onclick="location.href='<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=gallery&art_type=16' ?>'"><label style="margin: 15px 5px;">기타</label></div>
                                    </div>
                                </div>
                            <? } ?>
                            <div class="panel panel-default" style="display: flex;border: none;margin-left:auto;margin-right:auto;justify-content: space-between;box-shadow: none;">
                                <div style="display:flex">
                                    <div class="dropdown" style="margin-left: 10px;margin-top: 0px;">
                                        <img src="/iam/img/menu/icon_filter.png" class="dropdown-toggle westory_dropdown" data-toggle="dropdown" style="width: 24px;height:24px;margin-top: 10px;" aria-expanded="false">
                                        <ul class="dropdown-menu comunity" style="left: 0px !important;padding: 0px;border:none">
                                            <li>
                                                <table class="table table-borderless" style="margin-bottom:0px">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <a href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=' . $mall_type1 . '&search_key=' . $_GET['search_key'] . '&sort=0' ?>">
                                                                    업로드 날짜</a>
                                                            </th>
                                                            <th><a href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=' . $mall_type1 . '&search_key=' . $_GET['search_key'] . '&sort=0' ?>">
                                                                    정렬기준</a>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=' . $mall_type1 . '&search_key=' . $_GET['search_key'] . '&sort=1' ?>">
                                                                    1시간전</a>
                                                            </td>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=' . $mall_type1 . '&search_key=' . $_GET['search_key'] . '&sort=11' ?>">
                                                                    게시일짜</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=' . $mall_type1 . '&search_key=' . $_GET['search_key'] . '&sort=2' ?>">
                                                                    오늘</a>
                                                            </td>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=' . $mall_type1 . '&search_key=' . $_GET['search_key'] . '&sort=12' ?>">
                                                                    조회수</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=' . $mall_type1 . '&search_key=' . $_GET['search_key'] . '&sort=3' ?>">
                                                                    이번주</a>
                                                            </td>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=' . $mall_type1 . '&search_key=' . $_GET['search_key'] . '&sort=13' ?>">
                                                                    좋아요</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=' . $mall_type1 . '&search_key=' . $_GET['search_key'] . '&sort=4' ?>">
                                                                    이번달</a>
                                                            </td>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=' . $mall_type1 . '&search_key=' . $_GET['search_key'] . '&sort=14' ?>">
                                                                    가격순</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?cur_win=' . $cur_win . '&mall_type=' . $mall_type . '&mall_type1=' . $mall_type1 . '&search_key=' . $_GET['search_key'] . '&sort=5' ?>">
                                                                    올해</a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="panel-heading" style="display: flex;border-bottom:none;background:white">
                                        <input type="text" class="mall_search_input" style="font-size: 12px;width: 140px;margin-left: -5px;height: 24px;border: 1px solid #aaa;border-radius: 15px;" value="<?= $_GET['search_key'] ?>" id="mall_search_input" placeholder='키워드로 검색하세요.'>
                                        <i class="glyphicon glyphicon-remove" id="mall_input" style="margin-top: -1px;height: 24px;width: 22px;border: 1px solid #aaaaaa;margin-left: -22px;line-height: 22px;color: darkgrey;border-left: none;border-top: none;border-bottom: none;border-radius: 15px;text-align: center;display: none;" onclick="clear_wecon_search1()"></i>
                                        <img src="/iam/img/menu/icon_bottom_search.png" style="height:24px" onclick="mall_search_clicked()">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? } else if ($cur_win == "we_story") { ?>
                    <div class="panel-group" id="we_story_tab">
                        <div class="recom_like_tab" style="<? if ($_GET['key1'] != 3 && $_GET['key1'] != 4) echo 'column-count:1;background-color: #f5f5f5;padding:0px;';
                                                            if ($_GET['key1'] == 4) echo 'padding:5px 0px;' ?>">
                            <? if ($_GET['key1'] == 4) { ?>
                                <div class="container" style="border-color: #ddd;">
                                    <a class="gwc_con_type" name="key4" style="cursor:pointer;line-height: 40px;<? if ($_GET['key4'] == "3") echo 'color:#99cc00;'; ?>" value="3">굿마켓</a>
                                    <a href="javascript:gwc_tab()" style="cursor:pointer;line-height: 40px;margin-left: 5px;<? if ($_GET['key4'] != "3" && $_GET['iamstore'] != "Y" && $_GET['iamstore'] != "C") echo 'color:#99cc00;'; ?>" value="3">쇼핑</a>
                                    <a href="javascript:show_gwc_cate_prod('1268514')" id="recom_gwc_item" style="cursor:pointer;margin-left:5px;<? if ($_GET['cate_prod'] == "1268514") echo 'color:#99cc00;'; ?>" value="1">추천</a>
                                    <a href="javascript:show_gwc_cate_prod('2477701')" id="best_gwc_item" style="cursor:pointer;margin-left:5px;<? if ($_GET['cate_prod'] == "2477701") echo 'color:#99cc00;'; ?>" value="1">베스트</a>
                                </div>
                            <? } else if ($_GET['key1'] == 3) { ?>
                                <div class="container" id="callya_sort">
                                    <ul class="nav" style="flex-wrap: wrap;margin-top: 5px;">
                                        <li class="nav-item" style="width: auto;margin-top: 5px;display: inline-block;">
                                            <a class="map_gmarket" name="key3" style="padding:0px 2px;<? if ($_GET['key3'] == "3") echo 'color:#99cc00;'; ?>" value="3">소개</a>
                                        </li>
                                        <li class="nav-item" style="width: auto;margin-top: 5px;display: inline-block;">
                                            <a class="map_gmarket" name="key3" style="padding:0px 2px;<? if ($_GET['key3'] == "0") echo 'color:#99cc00;'; ?>" value="0">전체</a>
                                        </li>
                                        <li class="nav-item" style="width: auto;margin-top: 5px;display: inline-block;">
                                            <a class="map_gmarket" name="key3" style="padding:0px 2px;<? if ($_GET['key3'] == "2") echo 'color:#99cc00;'; ?>" value="2">배송</a>
                                        </li>
                                        <li class="nav-item" style="width: auto;margin-top: 5px;display: inline-block;">
                                            <a class="map_gmarket" name="key3" style="padding:0px 2px;<? if ($_GET['key3'] == "1") echo 'color:#99cc00;'; ?>" value="1">방문</a>
                                        </li>
                                        <? if ($_GET['key3'] == 1) { ?>
                                            <li class="nav-item" style="width: auto;margin-top: 5px;display: inline-block;">
                                                <a class="map_gmarket_sort" name="sort_key3" style="padding:0px;<? if ($_GET['sort_key3'] == "2") echo 'color:#99cc00;'; ?>" value="2"></a>
                                            </li>
                                            <input type="text" placeholder="지역검색" class="wecon_search_input1" style="font-size:12px;width:100px;height: 24px;border: 1px solid #aaa;" value="<?= $_GET['loc_name'] ?>" id="search_location_name">
                                            <i class="fa fa-search" style="margin-left:5px;font-size: 18px;margin-top: 5px;" onclick="wecon_location_name()"></i>
                                        <? } ?>
                                    </ul>
                                </div>
                            <? } ?>
                            <? if ($_GET['key1'] == 4 || $_GET['key1'] == 3) { ?>
                                <div class="container" style="padding:0px;margin-left: -20px;margin-right:15px;">
                                    <ul class="nav" style="display:flex;flex-wrap: wrap;margin-top: 10px;float: right;">
                                        <? if ($_GET['key1'] == 4) { ?>
                                            <li class="nav-item" style="width: auto;">
                                                <a href="javascript:show_iamstore_prod('Y')" style="padding:0px 1px;margin-right:2px;<? if ($_GET['iamstore'] == "Y") echo 'color:#99cc00;'; ?>" value="1">도매몰 |</a>
                                            </li>
                                            <li class="nav-item" style="width: auto;">
                                                <a href="javascript:show_iamstore_prod('C')" style="padding:0px 1px;<? if ($_GET['iamstore'] == "C") echo 'color:#99cc00;'; ?>" value="2">마이샵 |</a>
                                            </li>
                                            <li class="nav-item" style="width: auto;">
                                                <div class="dropdown" style="display: flex;margin-left:5px;">
                                                    <p class="dropdown-toggle westory_dropdown" data-toggle="dropdown" style="height:24px;padding:0px;margin-top:0px;" aria-expanded="false">주문목록<img src="/iam/img/menu/icon_sort_gwc.png" style="width: 10px;height: 7px;margin-left: 3px;"></p>
                                                    <ul class="dropdown-menu comunity" style="left: -30px !important;padding: 0px;border:none;min-width: 100px;width: 100px;">
                                                        <li>
                                                            <table class="table table-borderless" style="margin-bottom:0px">
                                                                <tbody>
                                                                    <tr>
                                                                        <td style="border-top:none">
                                                                            <a href="/iam/gwc_order_list.php" style="padding:0px 1px;" value="0">주문목록</a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="border-top:none">
                                                                            <a href="/iam/gwc_order_cart.php" style="padding:0px 1px;" value="0">장바구니</a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="border-top:none">
                                                                            <a href="/iam/gwc_order_change_list.php" style="padding:0px 1px;" value="0">취소/반품</a>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="border-top:none">
                                                                            <a href="/iam/req_provider_list.php" style="padding:0px 1px;" value="0">공급신청</a>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                        <? } else if ($_GET['key1'] == 3) { ?>
                                            <li class="nav-item" style="width: auto;">
                                                <a class="map_gmarket_sort" name="sort_key3" style="padding:0px 1px;<? if ($_GET['sort_key3'] == "1") echo 'color:#99cc00;'; ?>" value="1">할인 |</a>
                                            </li>
                                            <li class="nav-item" style="width: auto;">
                                                <a class="map_gmarket_sort" name="sort_key3" style="padding:0px 1px;<? if ($_GET['sort_key3'] == "2") echo 'color:#99cc00;'; ?>" value="2">저가 |</a>
                                            </li>
                                            <li class="nav-item" style="width: auto;">
                                                <a class="map_gmarket_sort" name="sort_key3" style="padding:0px 1px;<? if ($_GET['sort_key3'] == "3") echo 'color:#99cc00;'; ?>" value="3">고가 |</a>
                                            </li>
                                            <li class="nav-item" style="width: auto;">
                                                <a class="map_gmarket_sort" name="sort_key3" style="padding:0px 1px;<? if ($_GET['sort_key3'] == "0") echo 'color:#99cc00;'; ?>" value="0">최신</a>
                                            </li>
                                        <? } ?>
                                    </ul>
                                </div>
                            <? } ?>
                        </div>
                        <? if ($_GET['key1'] == 4) {
                            $height = 'height: 50px;';
                            $hide_sort_call = "flex";
                        } else if ($_GET['key1'] == 3 || $_GET['key1'] == '' || $_GET['key1'] == 0) {
                            $height = 'height: 0px;';
                            $hide_sort_call = "none";
                        } ?>
                        <div>
                            <div style="padding:10px;border: 1px solid #ddd;">
                                <div class="right" style="float:right;margin-top:5px;display:inline-flex;">
                                    <? if ($_GET['key1'] == 4) { ?>
                                        <div class="container" style="border-color: #ddd;padding: 0px;display: inline-flex;">
                                            <a href="javascript:show_category()" style="margin-top: 4px;">
                                                카테고리
                                            </a>
                                            <!-- <a href="javascript:show_iamstore_prod('C')" style="float: right;margin-right: 5px;">
                                    <? if ($_GET['iamstore'] == "C") { ?>
                                    <img src="/iam/img/main/iam_gm_mygood_check.png" style="height: 27px;">
                                    <? } else { ?>
                                    <img src="/iam/img/main/iam_gm_mygood.PNG" style="height: 27px;">
                                    <? } ?>
                                </a>
                                <a href="javascript:show_iamstore_prod('Y')" style="float: right;margin-right:5px;">
                                    <? if ($_GET['iamstore'] == "Y") { ?>
                                    <img src="/iam/img/main/iam_domae_check.png" style="height: 27px;">
                                    <? } else { ?>
                                    <img src="/iam/img/main/iam_domae_logo.png" style="height: 27px;">
                                    <? } ?>
                                </a>
                                <a href="javascript:gwc_tab()" style="float: right;margin-right:5px;">
                                    <? if ($_GET['iamstore'] != "Y" && $_GET['iamstore'] != "C") { ?>
                                    <img src="/iam/img/main/icon_iam_shopping_chk.PNG" style="height: 27px;">
                                    <? } else { ?>
                                    <img src="/iam/img/main/icon_iam_shopping.PNG" style="height: 27px;">
                                    <? } ?>
                                </a> -->
                                            <div class="dropdown" style="margin-top: -10px;display: flex;margin-left: 10px;">
                                                <?
                                                if ($_GET['sort_key3'] == "0") $txt = '최신순';
                                                if ($_GET['sort_key3'] == "1") $txt = '할인순';
                                                if ($_GET['sort_key3'] == "2") $txt = '저가순';
                                                if ($_GET['sort_key3'] == "3") $txt = '고가순';
                                                ?>
                                                <p class="dropdown-toggle westory_dropdown" data-toggle="dropdown" style="width: 60px;height:24px;margin-top: 14px;padding:0px;" aria-expanded="false"><?= $txt ?><img src="/iam/img/menu/icon_sort_gwc.png" style="width: 10px;height: 7px;margin-left: 3px;"></p>
                                                <ul class="dropdown-menu comunity" style="left: -30px !important;padding: 0px;border:none;min-width: 100px;width: 100px;">
                                                    <li>
                                                        <table class="table table-borderless" style="margin-bottom:0px">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="border-top:none">
                                                                        <a class="map_gmarket_sort" name="sort_key3" style="padding:0px 1px;<? if ($_GET['sort_key3'] == "0") echo 'color:#99cc00;'; ?>" value="0">최신순</a>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="border-top:none">
                                                                        <a class="map_gmarket_sort" name="sort_key3" style="padding:0px 1px;<? if ($_GET['sort_key3'] == "3") echo 'color:#99cc00;'; ?>" value="3">고가순</a>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="border-top:none">
                                                                        <a class="map_gmarket_sort" name="sort_key3" style="padding:0px 1px;<? if ($_GET['sort_key3'] == "2") echo 'color:#99cc00;'; ?>" value="2">저가순</a>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td style="border-top:none">
                                                                        <a class="map_gmarket_sort" name="sort_key3" style="padding:0px 1px;<? if ($_GET['sort_key3'] == "1") echo 'color:#99cc00;'; ?>" value="1">할인순</a>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    <? } ?>
                                </div>
                                <? if ($_GET['key1'] == 4) {
                                    $width = "width: 260px;";
                                } else {
                                    $width = "width: 300px;";
                                } ?>
                                <div style="display:flex;<?= $width ?>">
                                    <div class="dropdown" id="wecon_search_div" style="margin-top: -10px;display: flex">
                                        <img src="/iam/img/menu/icon_filter.png" class="dropdown-toggle westory_dropdown" data-toggle="dropdown" style="width: 24px;height:24px;margin-top: 14px;padding:0px;" aria-expanded="false">
                                        <ul class="dropdown-menu comunity" style="left: 0px !important;padding: 0px;border:none">
                                            <li>
                                                <table class="table table-borderless" style="margin-bottom:0px">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <a href="<?= '?' . $request_short_url . $card_owner_code . '&cur_win=we_story&search_key=' . $_GET['search_key'] . '&key1=' . $_GET['key1'] . '&key2=' . $_GET['key2'] . '&key3=' . $_GET['key3'] . '&sort=0&sort_key3=' . $_GET['sort_key3'] . '&loc_name=' . $_GET['loc_name'] . '&key4=' . $_GET['key4'] . '&iamstore=' . $_GET['iamstore'] . '&cate_prod=' . $_GET['cate_prod'] ?>">
                                                                    업로드 날짜</a>
                                                            </th>
                                                            <th>
                                                                <a href="<?= '?' . $request_short_url . $card_owner_code . '&cur_win=we_story&search_key=' . $_GET['search_key'] . '&key1=' . $_GET['key1'] . '&key2=' . $_GET['key2'] . '&key3=' . $_GET['key3'] . '&sort=0&sort_key3=' . $_GET['sort_key3'] . '&loc_name=' . $_GET['loc_name'] . '&key4=' . $_GET['key4'] . '&iamstore=' . $_GET['iamstore'] . '&cate_prod=' . $_GET['cate_prod'] ?>">
                                                                    정렬기준</a>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?' . $request_short_url . $card_owner_code . '&cur_win=we_story&search_key=' . $_GET['search_key'] . '&key1=' . $_GET['key1'] . '&key2=' . $_GET['key2'] . '&key3=' . $_GET['key3'] . '&sort=1&sort_key3=' . $_GET['sort_key3'] . '&loc_name=' . $_GET['loc_name'] . '&key4=' . $_GET['key4'] . '&iamstore=' . $_GET['iamstore'] . '&cate_prod=' . $_GET['cate_prod'] ?>">
                                                                    1시간전</a>
                                                            </td>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?' . $request_short_url . $card_owner_code . '&cur_win=we_story&search_key=' . $_GET['search_key'] . '&key1=' . $_GET['key1'] . '&key2=' . $_GET['key2'] . '&key3=' . $_GET['key3'] . '&sort=11&sort_key3=' . $_GET['sort_key3'] . '&loc_name=' . $_GET['loc_name'] . '&key4=' . $_GET['key4'] . '&iamstore=' . $_GET['iamstore'] . '&cate_prod=' . $_GET['cate_prod'] ?>">
                                                                    게시일짜</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?' . $request_short_url . $card_owner_code . '&cur_win=we_story&search_key=' . $_GET['search_key'] . '&key1=' . $_GET['key1'] . '&key2=' . $_GET['key2'] . '&key3=' . $_GET['key3'] . '&sort=2&sort_key3=' . $_GET['sort_key3'] . '&loc_name=' . $_GET['loc_name'] . '&key4=' . $_GET['key4'] . '&iamstore=' . $_GET['iamstore'] . '&cate_prod=' . $_GET['cate_prod'] ?>">
                                                                    오늘</a>
                                                            </td>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?' . $request_short_url . $card_owner_code . '&cur_win=we_story&search_key=' . $_GET['search_key'] . '&key1=' . $_GET['key1'] . '&key2=' . $_GET['key2'] . '&key3=' . $_GET['key3'] . '&sort=12&sort_key3=' . $_GET['sort_key3'] . '&loc_name=' . $_GET['loc_name'] . '&key4=' . $_GET['key4'] . '&iamstore=' . $_GET['iamstore'] . '&cate_prod=' . $_GET['cate_prod'] ?>">
                                                                    조회수</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?' . $request_short_url . $card_owner_code . '&cur_win=we_story&search_key=' . $_GET['search_key'] . '&key1=' . $_GET['key1'] . '&key2=' . $_GET['key2'] . '&key3=' . $_GET['key3'] . '&sort=3&sort_key3=' . $_GET['sort_key3'] . '&loc_name=' . $_GET['loc_name'] . '&key4=' . $_GET['key4'] . '&iamstore=' . $_GET['iamstore'] . '&cate_prod=' . $_GET['cate_prod'] ?>">
                                                                    이번주</a>
                                                            </td>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?' . $request_short_url . $card_owner_code . '&cur_win=we_story&search_key=' . $_GET['search_key'] . '&key1=' . $_GET['key1'] . '&key2=' . $_GET['key2'] . '&key3=' . $_GET['key3'] . '&sort=13&sort_key3=' . $_GET['sort_key3'] . '&loc_name=' . $_GET['loc_name'] . '&key4=' . $_GET['key4'] . '&iamstore=' . $_GET['iamstore'] . '&cate_prod=' . $_GET['cate_prod'] ?>">
                                                                    좋아요</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?' . $request_short_url . $card_owner_code . '&cur_win=we_story&search_key=' . $_GET['search_key'] . '&key1=' . $_GET['key1'] . '&key2=' . $_GET['key2'] . '&key3=' . $_GET['key3'] . '&sort=4&sort_key3=' . $_GET['sort_key3'] . '&loc_name=' . $_GET['loc_name'] . '&key4=' . $_GET['key4'] . '&iamstore=' . $_GET['iamstore'] . '&cate_prod=' . $_GET['cate_prod'] ?>">
                                                                    이번달</a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-top:none">
                                                                <a href="<?= '?' . $request_short_url . $card_owner_code . '&cur_win=we_story&search_key=' . $_GET['search_key'] . '&key1=' . $_GET['key1'] . '&key2=' . $_GET['key2'] . '&key3=' . $_GET['key3'] . '&sort=5&sort_key3=' . $_GET['sort_key3'] . '&loc_name=' . $_GET['loc_name'] . '&key4=' . $_GET['key4'] . '&iamstore=' . $_GET['iamstore'] . '&cate_prod=' . $_GET['cate_prod'] ?>">
                                                                    올해</a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="panel-heading" id="search_wecon_tag" style="display: flex;border-bottom:none;padding:5px 10px;margin-left: 5px;">
                                        <input type="text" class="wecon_search_input" style="font-size: 12px;width: 140px;margin-left: -5px;height: 24px;border: 1px solid #aaa;border-radius: 15px;padding-left:7px;" value="<?= $_GET['search_key'] ?>" id="wecon_search_input" placeholder='키워드로 검색하세요.'>
                                        <i class="glyphicon glyphicon-remove" id="wecon_input" style="margin-top: -1px;height: 24px;width: 22px;border: 1px solid #aaaaaa;margin-left: -22px;line-height: 22px;color: darkgrey;border-left: none;border-top: none;border-bottom: none;border-radius: 15px;text-align: center;<?= $_GET['search_key'] ? '' : 'display: none;' ?>" onclick="clear_wecon_search()"></i>
                                        <img src="/iam/img/menu/icon_bottom_search.png" style="height:24px" onclick="wecon_search_clicked()">
                                        <span style="margin-left:2px;margin-top:2px;font-size:10px;line-height: 20px;" id="we_story_count"></span>
                                    </div>
                                </div>
                                <!--div style = "display:flex;justify-content: space-around;margin-top:15px;font-size: 12px;text-align:center;font-weight:700">
                            <div style = "cursor:pointer;padding:0px 2px;text-align: -webkit-center;" onclick="contents_add_spec(1,<?= $my_first_card ?>)">
                                <img src = '/iam/img/main/icon-siglconts.png' style="max-width:45px">
                                <span>한개 등록</span>
                            </div>
                            <div style = "cursor:pointer;padding:0px 2px;text-align: -webkit-center;" onclick="contents_add_spec(2,<?= $my_first_card ?>)">
                                <img src = '/iam/img/main/icon-multfiles.png' style="max-width:45px">
                                <span>다수 등록</span>
                            </div>
                            <div style = "cursor:pointer;padding:0px 2px;text-align: -webkit-center;" onclick="contents_add_spec(3,<?= $my_first_card ?>)">
                                <img src = '/iam/img/main/icon-autowebs.png' style="max-width:45px">
                                <span>자동 등록</span>
                            </div>
                        </div-->
                            </div>
                        </div>
                    </div>
                <? } else if ($cur_win == "group-con") { ?>
                    <div class="panel-group">
                        <div style="display:flex;margin : 10px;justify-content: flex-end;">
                            <div style="width: 24px;height: 24px;border-radius: 50%;overflow: hidden;" onclick="open_make_group_modal();">
                                <img src="/iam/img/menu/icon_group_new.png">
                            </div>
                            <div style="width: 24px;height: 24px;border-radius: 50%;overflow: hidden;margin-left:10px" onclick="show_all_group('general');">
                                <img src="/iam/img/menu/icon_group_search.png">
                            </div>
                        </div>
                        <div style="display:flex;margin-left : 10px;margin-right : 10px;margin-bottom : 10px;justify-content: space-around;">
                            <div style="cursor:pointer;background:<?= $gkind == 'recommend' ? '#e5f6ff' : '#d9d9d9' ?>;border-radius: 20px;padding:5px 10px;font-weight:700" onclick="location.href='/?cur_win=group-con'">
                                <img src="/iam/img/menu/icon_group_recommend.png" style="height:20px">
                                <span style="font-size:12px;">회원님을 위한 추천</span>
                            </div>
                            <!--div style="display:flex;"-->
                            <div style="cursor:pointer;background:<?= $gkind == 'mygroup' ? '#e5f6ff' : '#d9d9d9' ?>;border-radius: 20px;padding:5px 10px;font-weight:700;" onclick="location.href='/?cur_win=group-con&gkind=mygroup'">
                                <img src="/iam/img/menu/icon_my_group.png" style="height:20px">
                                <span style="font-size:12px;">내 그룹</span>
                            </div>
                            <div style="cursor:pointer;background:<?= $gkind == 'search' ? '#e5f6ff' : '#d9d9d9' ?>;border-radius: 20px;padding:5px 10px;font-weight:700;margin-left:5px" onclick="location.href='/?cur_win=group-con&gkind=search'">
                                <img src="/iam/img/menu/icon_group_find.png" style="height:20px">
                                <span style="font-size:12px;">찾아보기</span>
                            </div>
                            <!--/div-->
                        </div>
                    </div>
                <? } ?>
            </div>
        </header><!-- // 헤더 끝 -->
        <div id="footer_menu" style="position: fixed;justify-content: space-around;width: 100%;height:50px;z-index: 100;bottom: 0px;display: flex;max-width: 768px;background:white;border-top : 1px solid #ddd">
            <? //$home = $domainData['sub_domain'].'/?'.$first_card_url.$bunyang_site_manager_code
            if ($domainData['admin_iam_menu'] == 0) {
                $menu_query = "select * from Gn_Iam_Menu where site_iam='kiam' and menu_type='B' and use_yn = 'y' order by display_order";
            } else {
                $menu_query = "select * from Gn_Iam_Menu where site_iam='{$menu_host}' and menu_type='B' and use_yn = 'y' order by display_order";
            }
            $menu_res = mysqli_query($self_con,$menu_query);
            while ($menu_row = mysqli_fetch_array($menu_res)) {
                $func = str_replace("card_link", $request_short_url . $card_owner_code, $menu_row['move_url']);
                $func = str_replace("prewin", $cur_win, $func);
                $html = "<div style=\"margin-top:12px;text-align: center;cursor:pointer\" title=\"" . $menu_row['title'] . "\" onclick=\"" . $func . "\">";
                $html .= "<img src = \"" . $menu_row['img_url'] . "\" style=\"height:24px;width:24px\">";
                if (strstr($func, "go_cart()")) {
                    $html .= "<label class=\"label label-sm share_count\" id = \"cart_cnt\" style=\"top:0px;margin-left: -5px;\">" . ($cart_cnt ? $cart_cnt : '') . "</label>";
                }
                $html .= "</div>";
                echo $html;
            } ?>
            <!--div id="btn_home" style="margin-top:12px;text-align: center;cursor:pointer" onclick = "goIamHome();" title="홈">
            <img src = "/iam/img/menu/icon_bottom_home.png" style="height:24px;width:24px">
        </div>
        <div id="btn_notice" style="margin-top:12px;text-align: center;cursor:pointer" title="소식" onclick="openNoticeModal();">
            <img src = "/iam/img/menu/icon_bottom_news.png" style="height:24px;width:24px">
        </div>
        <div id="btn_ai_chat" onclick="location.href='/iam/gpt_chat.php'" style="margin-top:12px;text-align: center;cursor:pointer" title="GPT채트">
            <img src = "/iam/img/menu/icon_ai.png" style="height:24px;width:24px">
        </div>
        <div onclick="showSample()" style="margin-top:12px;text-align: center;cursor:pointer" title="검색">
            <img src = "/iam/img/menu/icon_sample.png" style="height:24px;width:24px">
        </div>
        <div id="btn_intro" style="margin-top:12px;text-align: center;cursor:pointer" title="안내" onclick="showIntro();">
            <img src = "/iam/img/menu/icon_bottom_intro.png" style="height:24px;width:24px">
        </div>
        <div onclick="go_cart()" style="margin-top:12px;text-align: center;cursor:pointer" title="장바구니">
            <img src = "/iam/img/menu/cart_gwc.png" style="height:24px;width:24px">
            <label class="label label-sm share_count" id = "cart_cnt" style="top:0px;margin-left: -5px;"><?= $cart_cnt ? $cart_cnt : '' ?></label>
        </div-->
            <? if (!$_SESSION['iam_member_id']) { ?>
                <div id="btn_login" style="margin-top:12px;text-align: center;cursor:pointer" onclick="location.href = '<?= ($domainData['status'] == 'N') ? '' : '/iam/login.php?recommend_id=' . $card_owner ?>'">
                    <img src="/iam/img/menu/icon_bottom_login.png" style="height:24px;width:24px">
                </div>
                <? } else {
                if ($member_iam['profile']) { ?>
                    <div style="cursor:pointer;margin-top:12px;width:24px;height:24px;border-radius: 50%;overflow: hidden" onclick="$('#mypage-modalwindow').modal('show');">
                        <img src="<?= cross_image($member_iam['profile']) ?>" style="width:100%;height:100%;object-fit: cover;">
                    </div>
                <? } else { ?>
                    <div style="cursor:pointer;margin-top:12px;background:<?= $profile_color ?>;padding:5px 0px;width:24px;height:24px;border-radius: 50%;overflow:hidden;text-align:center;" onclick="$('#mypage-modalwindow').modal('show');">
                        <a class="profile_font" style="color:white;width:100%;height:100%;object-fit: cover;"><?= mb_substr($member_iam['mem_name'], 0, 3, "utf-8") ?></a>
                    </div>
            <? }
            } ?>
        </div>
        <?
        if ($cur_win == "" || $cur_win == "my_info" || $cur_win == "my_story" || $cur_win == "we_story")
            $star_top = 86;
        else if ($cur_win == "iam_mall") {
            if ($_GET['mall_type1'] == "gallery")
                $star_top = 305;
            else
                $star_top = 255;
        } else if ($cur_win == "group-con")
            $star_top = 175;
        else if ($cur_win == "shared_receive" || $cur_win == "shared_send" || $cur_win == "unread_post" || $cur_win == "request_list" || $cur_win == "unread_notice")
            $star_top = 165;
        else
            $star_top = 135;
        ?>
        <main id="star" class="common-wrap" style="margin-top: <?= $star_top . 'px' ?>">
            <script>
                if ('<?= $cur_win ?>' == "we_story") {
                    $("#star").css("margin-top", <?= $star_top ?> + $("#we_story_tab").height() + "px");
                }
            </script>
            <!-- 콘텐츠 영역 시작 -->
            <?
            // middle log
            $logs->add_log("contents start");

            if ($cur_win == "my_info") { ?>
                <section id="main-slider">
                    <!-- 슬라이더 영역 시작 -->
                    <div id="mainSlider">
                        <? if ($cur_card['video_status'] == 'I') {
                            if ($main_img1) { ?>
                                <div class="slider-item">
                                    <a data-fancybox="gallery" id="main_img1" href="<?= cross_image($main_img1) ?>">
                                        <img id="for_size_1" src="<?= cross_image($main_img1) ?>" style="opacity:0;">
                                    </a>
                                </div>
                            <? } ?>
                            <? if ($main_img2) { ?>
                                <div class="slider-item">
                                    <a id="main_img2" data-fancybox="gallery" href="<?= cross_image($main_img2) ?>">
                                        <img id="for_size_2" src="<?= cross_image($main_img2) ?>" style="opacity:0;">
                                    </a>
                                </div>
                            <? } ?>
                            <? if ($main_img3) { ?>
                                <div class="slider-item">
                                    <a id="main_img3" data-fancybox="gallery" href="<?= cross_image($main_img3) ?>">
                                        <img id="for_size_3" src="<?= cross_image($main_img3) ?>" style="opacity:0;">
                                    </a>
                                </div>
                            <? }
                        } else { ?>
                            <div style="position:relative">
                                <a style="position: fixed;top: 20px;left: 20px;background: #f3f3f3;display: flex;padding:5px;padding-right:15px;z-index:10;" class="unmute_btn">
                                    <img src="/iam/img/main/mute.png" style="height: 30px">
                                    <p style="margin-top: 5px;font-weight:bold;">탭하여 음소거 해제</p>
                                </a>
                                <video src="<?= $cur_card['video'] ?>" type="video/mp4" autoplay loop muted playsinline preload style="width:100%;height:100%;object-fit:cover" id="videoSlider">
                                </video>
                                <img src="/iam/img/movie_play.png" style="display:none;width:70px;top:auto;left:20px;bottom:20px;" class="movie_play">
                            </div>
                        <? } ?>
                    </div>
                    <!-- 본사회원,분양사관리자,분양사회원(1번카드가 아니면) 대표이미지 수정가능-->
                    <? if ($_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) {
                        if (!$cur_card['share_send_card']) { ?>
                            <a href="#" class="controls" title="<?= $MENU['IAM_MENU']['M9_TITLE']; ?>" data-card_idx="<?= $cur_card['idx'] ?>">
                                <i class="fa fa-pencil-square-o" aria-hidden="false" style="background: rgba(255, 255, 255, 0.6);padding: 3px;border-radius: 5px;"></i>
                            </a>
                            <span class="tooltiptext" id="tooltiptext_upload_img" style="display:none;">
                                <p style="font-weight:bold;margin-bottom:-15px;"><img src="/iam/img/main/icon-poplogo.png" style="width:12%;margin-right:5px;margin-bottom: 5px;">2. 대표 이미지 업로드</p><br>
                                나의 IAM 카드의 대표사진을<br>
                                업로드하세요.<br>
                                <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:38%;font-size:15px;background-color: lightgrey;margin-top: 10px;margin-left: 20px;" onclick="open_main_img('<?= $cur_card['idx'] ?>')">만들기</button>
                                <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:38%;font-size:15px;background-color: lightgrey;margin-top: 10px;" onclick="close_tooptip(2)">다음</button>
                            </span>
                    <? }
                    } ?>
                    <div class="slider-arrows"></div>
                </section><!-- // 슬라이더 영역 끝 -->
                <!-- // 중단 콘텐츠 영역  -->
                <section id="middle">
                    <?
                    //연락처 갯수 가져오기
                    if ($_SESSION['iam_member_id']) {
                        $contact_count_sql = "select count(idx) from Gn_MMS_Receive_Iam where mem_id = '{$_SESSION['iam_member_id']}' and grp = '아이엠'";
                        $contact_count_result_ = mysqli_query($self_con,$contact_count_sql);
                        $contact_count_row = mysqli_fetch_array($contact_count_result_);
                        $contact_total_count = $contact_count_row[0];
                    } else {
                        $contact_total_count = 0;
                    }
                    //종이명함 갯수 가져오기
                    if ($_SESSION['iam_member_id']) {
                        $paper_count_sql = "select count(idx) from Gn_MMS_Receive_Iam where mem_id = '{$_SESSION['iam_member_id']}' and grp = '아이엠' and paper_yn=1";
                        //"select count(*) from Gn_Member_card where mem_id='{$_SESSION['iam_member_id']}'";
                        $paper_count_result_ = mysqli_query($self_con,$paper_count_sql);
                        $paper_count_row = mysqli_fetch_array($paper_count_result_);
                        $paper_total_count = $paper_count_row[0];
                    } else {
                        $paper_total_count = 0;
                    }
                    //프렌즈 갯수 가져오기
                    $friends_count_sql = "select count(idx) from Gn_Iam_Friends where mem_id = '{$_SESSION['iam_member_id']}'";
                    $friends_count_result_ = mysqli_query($self_con,$friends_count_sql);
                    $friends_count_row = mysqli_fetch_array($friends_count_result_);

                    // $card_count_sql="select count(*) from Gn_Member";
                    $card_count_sql = "select idx from Gn_Iam_Name_Card where group_id is NULL order by idx desc limit 1";
                    $card_count_result = mysqli_query($self_con,$card_count_sql);
                    $card_count_row = mysqli_fetch_array($card_count_result);

                    $phone_number_sql = "select count(*) from Gn_MMS_Number where mem_id = '{$_SESSION['iam_member_id']}'";
                    $phone_number_result = mysqli_query($self_con,$phone_number_sql);
                    $phone_number_row = mysqli_fetch_array($phone_number_result);
                    $phone_count = $phone_number_row[0];
                    ?>
                    <!--a href="#" id="toggleContent" style="position: absolute; right: 5px;"><i class="fa fa-arrow-up" aria-hidden="true"></i></a-->
                    <!--프로필,스토리,연락처,프렌즈 바-->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item profile-tab tab_1 active in">
                            <a class="nav-link" id="profile-tab" title="<?= $MENU['IAM_TAB']['T1_TITLE']; ?>" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true"><b><?= $MENU['IAM_TAB']['T1']; ?></b><br><small id="my_profile_count"></small></a>
                        </li>
                        <li class="nav-item paper-tab tab_1" onclick="loginCheckShowModal()">
                            <a class="nav-link" id="paper-tab" title="<?= $MENU['IAM_TAB']['T5_TITLE']; ?>" data-toggle="tab" href="#paper" role="tab" onclick="javascript:getIamPaper('<?= $card_owner ?>', '<?= $card_master ?>', '<?= $search_range ?>', '<?= $phone_count ?>', 1 );" aria-controls="paper" aria-selected="false"><b><?= $MENU['IAM_TAB']['T5']; ?></b><small><br>(<?= number_format($paper_total_count) ?>)</small></a>
                        </li>
                        <li class="nav-item contact-tab tab_1" onclick="loginCheckShowModal()">
                            <a class="nav-link" id="contact-tab" title="<?= $MENU['IAM_TAB']['T3_TITLE']; ?>" data-toggle="tab" href="#contact" role="tab" onclick="javascript:getIamContact('<?= $card_owner ?>', '<?= $card_master ?>', '<?= $search_range ?>', '<?= $phone_count ?>', 1, 0);" aria-controls="contact" aria-selected="false"><b><?= $MENU['IAM_TAB']['T3']; ?></b><small><br>(<?= number_format($contact_total_count) ?>)</small></a>
                        </li>
                        <li class="nav-item friends-tab tab_1" onclick="loginCheckShowModalFriends()">
                            <a class="nav-link" id="friends-tab" title="<?= $MENU['IAM_TAB']['T4_TITLE']; ?>" data-toggle="tab" href="#friends" role="tab" onclick="javascript:getIamFriends('<?= $card_owner ?>', '<?= $card_master ?>', '<?= $phone_count ?>', '', 1 );" aria-controls="friends" aria-selected="false"><b><?= $MENU['IAM_TAB']['T4']; ?></b><small><br>(<?= number_format($card_count_row[0]) ?>)</small></a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade profile-tab-content active in" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <?
                            $sql_card_show = "select card_show from Gn_Iam_Name_Card where card_short_url='{$request_short_url}'";
                            $res_show = mysqli_query($self_con,$sql_card_show);
                            $row_show = mysqli_fetch_array($res_show);
                            if ($row_show[0] == 1) {
                                $card_hidden = "display:block;";
                                $style_drop = "min-height:30px;margin-top:10px";
                            } else {
                                $card_hidden = "display:none;";
                                if ($_GET['preview'] == "Y") {
                                    $style_drop = "min-height:5px;margin-top:5px";
                                    $card_hidden1 = "display:none;";
                                } else {
                                    $style_drop = "min-height:30px;margin-top:10px";
                                    $card_hidden1 = "display:block;";
                                }
                            } ?>
                            <div id="right-side-dropdown" style="position:relative;<?= $style_drop ?>">
                                <? //--네임카드 우측 세점 드롭박스 부분--//
                                if ($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) { ?>
                                    <div style="position:absolute;right:65px;">
                                        <a href="javascript:addMainBtn('<?= str_replace("'", "", $cur_card['card_name']) ?>','?<?= $request_short_url . $card_owner_code ?>');">
                                            <img src="/iam/img/menu/icon_home_add.png" alt="" title="<?= $MENU['IAM_CARD_BTN']['BTN6_TITLE']; ?>" style="margin-left:0px;width:24px;height:24px">
                                        </a>
                                    </div>
                                    <?
                                    $posX = 65;
                                    if (($card_owner_site == $user_site || $card_owner_site == $bunyang_site)) {
                                        $posX += 27;
                                    ?>
                                        <div style="position:absolute;right:<?= $posX ?>px;float:right;">
                                            <a href="javascript:move_card();" title="<?= $MENU['IAM_CARD_M']['CM12_TITLE']; ?>">
                                                <img src="/iam/img/menu/icon_card_move.png" title="<?= $MENU['IAM_CARD_M']['CM12']; ?>" style="width:24px;height:24px">
                                            </a>
                                        </div>
                                        <?
                                        $posX += 27;
                                        ?>
                                        <div style="position:absolute;right:<?= $posX ?>px;float:right;">
                                            <?
                                            if ($card_hidden == "display:block;") { ?>
                                                <a href="javascript:showCardState('<?= $request_short_url ?>', 0)" id="show_card" title="">
                                                    <img src="/iam/img/menu/icon_profile_show.png" title="<?= $MENU['IAM_CARD_M']['CM15']; ?>" style="width:24px;height:24pxmargin-left: 0px;">
                                                </a>
                                            <?  } else { ?>
                                                <a href="javascript:showCardState('<?= $request_short_url ?>', 1)" id="show_card" title="">
                                                    <img src="/iam/img/menu/icon_profile_show.png" title="<?= $MENU['IAM_CARD_M']['CM16']; ?>" style="width:24px;height:24pxmargin-left: 0px;">
                                                </a>
                                            <?  } ?>
                                        </div>
                                        <?
                                        if ($is_pay_version) {
                                            $posX += 27;
                                        ?>
                                            <div style="position:absolute;right:<?= $posX ?>px;float:right;cursor:pointer">
                                                <a data-toggle="modal" data-target="#card_send_modal">
                                                    <img src="/iam/img/menu/icon_card_send.png" title="<?= $MENU['IAM_CARD_M']['CM14']; ?>" style="margin-left:0px;width:24px;height:24px" alt="">
                                                </a>
                                            </div>
                                        <? } ?>
                                    <? } ?>
                                    <?
                                    $posX += 27;
                                    ?>
                                    <div style="position:absolute;right:<?= $posX ?>px;float:right;">
                                        <a href="javascript:showSNSModal('<?= $cur_win ?>')" title="<?= $MENU['IAM_TOP_MENU']['TITLE5']; ?>">
                                            <img src="/iam/img/menu/icon_share_black.png" alt="" title="" style="margin-left:0px;width:24px;height:24px">
                                        </a>
                                    </div>
                                    <?
                                    $posX += 27;
                                    if (!$cur_card['share_send_card']) {
                                    ?>
                                        <div style="position:absolute;right:<?= $posX ?>px;float:right;">
                                            <a href="javascript:edit_card('<?= $request_short_url ?>');">
                                                <img src="/iam/img/menu/icon_edit.png" alt="" title="" style="margin-left:0px;width:24px;height:24px">
                                            </a>
                                        </div>
                                    <? } ?>
                                    <div class="dropdown right" style="position:absolute;right:35px;float:right;">
                                        <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_dot.png" style="width:24px;height:24px">
                                        <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                            <li>
                                                <? if ($cur_card['phone_display'] == 'Y') { ?>
                                                    <a href="javascript:card_phone_display('<?= $cur_card['idx'] ?>', '<?= $_SESSION['iam_member_id'] ?>','N')">
                                                        <img src="/iam/img/icon_unlock.svg" title="<?= $MENU['IAM_CARD_M']['CM2_TITLE']; ?>" style="height: 20px"><?= $MENU['IAM_CARD_M']['CM2']; ?>
                                                    </a>
                                                <? } else { ?>
                                                    <a href="javascript:card_phone_display('<?= $cur_card['idx'] ?>', '<?= $_SESSION['iam_member_id'] ?>','Y')">
                                                        <img src="/iam/img/menu/icon_card_lock.png" title="<?= $MENU['IAM_CARD_M']['CM1_TITLE']; ?>" style="height: 20px"><?= $MENU['IAM_CARD_M']['CM1']; ?>
                                                    </a>
                                                <? } ?>
                                            </li>
                                            <? if ((int)$_SESSION['iam_member_leb'] != 0) { ?>
                                                <li>
                                                    <a href="/iam/mypage.php" class="edit">
                                                        <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true" title="<?= $MENU['IAM_CARD_M']['CM3_1_TITLE']; ?>"></i><?= $MENU['IAM_CARD_M']['CM3_1']; ?>
                                                    </a>
                                                </li>
                                            <? } ?>
                                            <!-- <li>
                                            <a href="javascript:showCardTitle()" id = "show_card_title_menu" title="<?= $MENU['IAM_CARD_M']['CM5_TITLE']; ?>">
                                                <img src="/iam/img/menu/icon_card_title.png"  style="height: 20px">
                                                <?
                                                if ($card_mode == "card_title") {
                                                    echo $MENU['IAM_CARD_M']['CM5'];
                                                } else {
                                                    echo $MENU['IAM_CARD_M']['CM6'];
                                                } ?>
                                            </a>
                                        </li> -->
                                            <? if (($card_owner_site == $user_site || $card_owner_site == $bunyang_site)) { ?>
                                                <li>
                                                    <a href="javascript:open_cont_order_popup('<?= $cur_win ?>', '<?= $cur_card['card_short_url'] ?>', '<?= $group_card_url ?>');" title="카드 콘텐츠 편집하기">
                                                        <img src="/iam/img/menu/icon_cont_edit.png" style="height: 20px">
                                                        카드 콘텐츠 편집
                                                    </a>
                                                </li>
                                            <? } ?>
                                            <!--li>
                                            <a href="javascript:showCardLike('<?= $post_display == 0 ? 1 : 0; ?>','<?= $request_short_url ?>')" id = "show_card_title_menu" title="<?= $MENU['IAM_CARD_M']['CM7_TITLE']; ?>">
                                                <img src="/iam/img/menu/icon_show_post.png" title="댓글박스" style="height: 20px">
                                                <? if ($post_display == 1) {
                                                    echo $MENU['IAM_CARD_M']['CM8'];
                                                } else {
                                                    echo $MENU['IAM_CARD_M']['CM7'];
                                                } ?>
                                            </a>
                                        </li-->
                                            <li>
                                                <a href="javascript:showSNSModal('<?= $cur_win ?>','Y')" id="show_card_title_menu" title="<?= $MENU['IAM_CARD_M']['CM7_TITLE']; ?>">
                                                    <img src="/iam/img/menu/icon_share_black.png" title="해당 카드 공유하기" style="height: 20px">
                                                    <?= $MENU['IAM_CARD_M']['CM17']; ?>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:show_manage_auto(0)" title="콘텐츠 오토데이트 관리">
                                                    <img src="/iam/img/menu/icon_auto_date.png" title="" style="height: 23px;margin-left: -2px;">
                                                    오토 데이트 기능
                                                </a>
                                            </li>
                                            <? if (($card_owner_site == $user_site || $card_owner_site == $bunyang_site)) {
                                                if ($is_pay_version) { ?>
                                                    <li>
                                                        <a href="javascript:open_iam_mall_popup('<?= $_SESSION['iam_member_id'] ?>',2,'<?= $cur_card['idx'] ?>');" title="<?= $MENU['IAM_CARD_M']['CM13_TITLE']; ?>">
                                                            <img src="/iam/img/menu/icon_shop.png" style="height: 20px">
                                                            <?= $MENU['IAM_CARD_M']['CM13']; ?>
                                                        </a>
                                                    </li>
                                                <? }
                                                if ($first_card_idx != $cur_card['idx']) { ?>
                                                    <li>
                                                        <a href="javascript:namecard_del('<?= $cur_card['idx'] ?>');" title="<?= $MENU['IAM_CARD_M']['CM11_TITLE']; ?>">
                                                            <img src="/iam/img/menu/icon_card_del.png" style="height: 20px">
                                                            <?= $MENU['IAM_CARD_M']['CM11']; ?>
                                                        </a>
                                                    </li>
                                            <?  }
                                            } ?>
                                        </ul>
                                    </div>
                                <? } ?>
                                <div id="card_title_down" style="text-align:right;height:20px;display:none">
                                    <img src="/iam/img/main/icon-19.png" style="height:20px;margin-right:10px">
                                </div>
                            </div>
                            <? //echo "befoer_card_show_end!!"; exit;
                            ?>
                            <? //echo "<script>alarm_cnt++; alert('중간알림'+alarm_cnt);</script>";
                            ?>
                            <div class="box-body" style="<?= $card_hidden1 ?>">
                                <div class="inner" style="padding-top:0px">
                                    <div class="utils clearfix" style="margin-bottom: 0px;">
                                        <?
                                        if (isset($_GET['preview']))
                                            $show_share_iam_card = $_GET['preview'] == "" ? "N" : $_GET['preview'];
                                        if (isset($_GET['smode'])) { ?>
                                            <script>
                                                $(document).ready(function() {
                                                    $("#card_send_modal_start").modal("show");
                                                });
                                            </script>
                                        <? }
                                        if ($show_share_iam_card == "N") { //카드공유로 방문
                                            $today = date("Y-m-d"); //검은색 카드번호
                                        ?>
                                            <div class="left">
                                                <div class="color-chips">
                                                    <?
                                                    $title_display = "inline-block";
                                                    $card_count = $cont_count = 0;
                                                    //echo $_GET['preview']."|".$_GET['cache']."|".$cur_win."|user_mem_code=".$user_mem_code."|card_owner_code=".urldecode($card_owner_code)."|session_iam_member_subadmin_id=".$_SESSION['iam_member_subadmin_id'] ."|pay_status=".$pay_status."|service_type=".$domainData['service_type']."|share_dub_admin=".$share_sub_admin."|share_pay_status=".$share_pay_status;
                                                    if (
                                                        $cur_win == "my_info" &&
                                                        (($user_mem_code == $card_owner_code && !$_SESSION['iam_member_subadmin_id'] && (!$pay_status || $domainData['service_type'] == 2)) ||
                                                            ($user_mem_code != $card_owner_code && !$share_sub_admin && (!$share_pay_status || $domainData['service_type'] == 2)))
                                                    ) { //무료회원이거나 단체회원이면 분양사관리자 1번카드를 자신의 1번카드로 한다
                                                        //$share_pay_status = false;
                                                        $title_sql = "select card_title,phone_display,next_iam_link from Gn_Iam_Name_Card where idx='$first_card_idx'";
                                                        $title_res = mysqli_query($self_con,$title_sql);
                                                        $title_row = mysqli_fetch_array($title_res);

                                                        $private_class = ($title_row['phone_display'] == 'N' ? "private" : "");
                                                        $active_class = ($request_short_url == $first_card_url ? "active" : "");

                                                        $n_cont_sql = "select count(*) from Gn_Iam_Contents where card_idx = '$first_card_idx' and req_data > '$today'";
                                                        $n_cont_res = mysqli_query($self_con,$n_cont_sql);
                                                        $n_cont_row = mysqli_fetch_array($n_cont_res);
                                                    ?>
                                                        <a href="?<?= $first_card_url . $card_owner_code ?>" onclick="show_next_iam('<?= $title_row['next_iam_link'] ?>');" class="J_card_num <?= $private_class ?> <?= $active_class ?>" id="card_title" style="display: <?= $title_display ?>;" title=<?= $title_row['card_title'] ?>><?= $title_row['card_title'] ? $title_row['card_title'] : $card_count + 1 ?><?= $title_row['next_iam_link'] == "" ? "" : "+" ?><?= $n_cont_row[0] > 0 ? "(" . $n_cont_row[0] . ")" : "" ?></a>
                                                        <?
                                                        $card_count++;
                                                        $cont_sql = "select count(*) from Gn_Iam_Contents where card_idx='$first_card_idx'";
                                                        $cont_res = mysqli_query($self_con,$cont_sql);
                                                        $cont_row = mysqli_fetch_array($cont_res);
                                                        $cont_count += $cont_row[0];
                                                    }
                                                    if ($_SESSION['iam_member_id'] && $user_mem_code == $card_owner_code)
                                                        $black_circle_sql = "select idx,card_short_url,card_title,phone_display,next_iam_link,mem_id from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' order by req_data asc";
                                                    else if ($user_mem_code != $card_owner_code)
                                                        $black_circle_sql = "select idx,card_short_url,card_title,phone_display,next_iam_link,mem_id from Gn_Iam_Name_Card where group_id is NULL and mem_id = '$share_member_id' order by req_data asc";
                                                    else
                                                        $black_circle_sql = "select idx,card_short_url,card_title,phone_display,next_iam_link,mem_id from Gn_Iam_Name_Card where group_id is NULL and mem_id = '$card_owner' order by req_data asc";
                                                    $black_circle_result = mysqli_query($self_con,$black_circle_sql);
                                                    while ($black_circle_row = mysqli_fetch_array($black_circle_result)) {
                                                        if ($card_count > 0 && $first_card_url == $black_circle_row['card_short_url'])
                                                            continue;
                                                        $private_class = ($black_circle_row['phone_display'] == 'N' ? "private" : "");
                                                        $active_class = ($request_short_url == $black_circle_row['card_short_url'] ? "active" : "");
                                                        $card_link = $black_circle_row['card_short_url'];
                                                        $card_idx = $black_circle_row['idx'];
                                                        if ($private_class != "private" || $_SESSION['iam_member_id'] == $black_circle_row['mem_id'] || (isset($_GET['smode']) && $card_idx == $_GET['slink'])) {
                                                            $cont_sql = "select count(*) from Gn_Iam_Contents where card_idx='$card_idx'";
                                                            $cont_res = mysqli_query($self_con,$cont_sql);
                                                            $cont_row = mysqli_fetch_array($cont_res);
                                                            $cont_count += $cont_row[0];
                                                        ?>
                                                            <a href="?<?= $card_link . $card_owner_code . (isset($_GET['smode']) ? '&smode=' . $_GET['smode'] . '&slink=' . $_GET['slink'] . '&smsg=' . $_GET['smsg'] : '') ?>" class=" J_card_num <?= $private_class ?> <?= $active_class ?>" id="card_title" onclick="show_next_iam('<?= $black_circle_row['next_iam_link'] ?>');" draggable="false" ondragstart="drag(event,'<?= $card_link ?>')" ondrop="drop(event,'<?= $card_link ?>')" ondragover="allowDrop(event)" ontouchstart="pickup(event,'<?= $card_link ?>')" ontouchmove="move(event)" ontouchend="pickdown(event)" style="z-index:10;display: <?= $title_display ?>;" title=<?= $black_circle_row['card_title'] ?>>
                                                                <?= $black_circle_row['card_title'] ? $black_circle_row['card_title'] : $card_count + 1 ?><?= $black_circle_row['next_iam_link'] == "" ? "" : "+" ?><?= $n_cont_row[0] > 0 ? "(" . $n_cont_row[0] . ")" : "" ?>
                                                            </a>
                                                            <script>
                                                                if ($(".color-chips").height() > 140) {
                                                                    $("a").last().addClass("card_arrow");
                                                                }
                                                            </script>
                                                        <? }
                                                        $card_count++;
                                                    }
                                                    if ($_SESSION['iam_member_id'] && $card_owner_code == $user_mem_code) {
                                                        if ($pay_status) { ?>
                                                            <a onclick="show_create_card_popup();" class="J_card_num" id="card_title" title="<?= $MENU['IAM_CARD_M']['CM9_TITLE']; ?>" style="z-index:10;background:white;color:#529c03;display: <?= $title_display ?>">
                                                                <span style="font-size:15px">새 카드 만들기</span>
                                                            </a>
                                                        <? } else { ?>
                                                            <a onclick="show_pay_popup('<?= $domainData['service_type'] ?>');" class="J_card_num" id="card_title" title="<?= $MENU['IAM_CARD_M']['CM9_TITLE']; ?>" style="z-index:10;background:white;color:#529c03;display: <?= $title_display ?>">
                                                                <span style="font-size:15px">새 카드 만들기</span>
                                                            </a>
                                                        <? } ?>
                                                    <? } ?>
                                                    <script>
                                                        $("#my_profile_count").html('(' + '<?= $card_count ?>' + '/' + '<?= $cont_count ?>' + ')');
                                                    </script>
                                                    <span class="tooltiptext-bottom" id="tooltiptext_card_edit" style="display:none;">
                                                        <p style="font-weight:bold;margin-bottom:-15px;"><img src="/iam/img/main/icon-poplogo.png" style="width:12%;margin-right:5px;margin-bottom: 5px;">1. 프로필 채우기</p><br>
                                                        나의 IAM 카드 프로필을<br>
                                                        완성하세요.<br>
                                                        <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:38%;font-size:15px;background-color: lightgrey;margin-top: 10px;margin-left: 20px;" onclick="edit_card('<?= $request_short_url ?>')">만들기</button>
                                                        <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:38%;font-size:15px;background-color: lightgrey;margin-top: 10px;" onclick="close_tooptip(1)">다음</button>
                                                    </span>
                                                </div>
                                            </div>
                                        <? } ?>
                                    </div>
                                    <div id="right-side-dropup" style="min-height:30px;margin-bottom: 25px;margin-top:-30px">
                                        <div id="card_title_up" style="text-align:right;height:20px;display:none;margin-right:-5px;">
                                            <img src="/iam/img/main/icon-20.png" style="height:20px">
                                        </div>
                                    </div>
                                    <div class="profile-card" style="<?= $card_hidden ?>"><!-- 카드 정보 노출 -->
                                        <div class="box">
                                            <!-- 입력란 -->
                                            <div class="formBox">
                                                <div>
                                                    <!-- 로고 이미지-->
                                                    <? if ($cur_card['profile_logo']) { ?>
                                                        <img src="<?= cross_image($cur_card['profile_logo']) ?>" alt="온리원그룹" class="logo" id="card_logo" onload="resize(this, '75', '50')" style="right:20px;font-size:15px" />
                                                    <? } else { ?>
                                                        <img class="logo" id="card_logo" onload="resize(this, '75', '50')" style="right:20px">
                                                    <? } ?>
                                                    <div class="top">
                                                        <? if (!$ai_map_gmarket) { ?>
                                                            <div class="J_card_name" style="max-width: 400px;"><?= $cur_card['card_name'] ?><a href="javascript:showmystory()" style="margin-left: 10px;vertical-align: top;"><img src="/images/icon-mystory.png" style="width:30px;"></a></div>
                                                            <div class="J_card_company"><?= $cur_card['card_company'] ?></div>
                                                        <? } else {
                                                            if (strpos($cur_card['card_name'], ",") !== false) {
                                                                $name_arr = explode(",", $cur_card['card_name']);
                                                                $name = $name_arr[0];
                                                                $name1 = str_replace($name . ",", "", $cur_card['card_name']);
                                                            } else {
                                                                $name = $cur_card['card_name'];
                                                            } ?>
                                                            <div class="J_card_name" style="max-width: 400px;"><?= $name ?><span style="font-size: 15px;margin-left: 7px;color: #c1a5a5;font-weight: 300;"><?= $name1 ?></span><a href="javascript:showmystory()" style="margin-left: 10px;vertical-align: top;"><img src="/images/icon-mystory.png" style="width:30px;"></a></div>
                                                            <div class="J_card_company"><?= str_replace('별점', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13 13" class="_2XLwD" aria-hidden="true" style="width: 14px;fill: #fc4c4e;"><path d="M8.26 4.68h4.26a.48.48 0 0 1 .28.87L9.35 8.02l1.33 4.01a.48.48 0 0 1-.18.54.48.48 0 0 1-.56 0l-3.44-2.5-3.44 2.5a.48.48 0 0 1-.74-.54l1.33-4L.2 5.54a.48.48 0 0 1 .28-.87h4.26l1.3-4a.48.48 0 0 1 .92 0l1.3 4z"></path></svg>', $cur_card['card_company']) ?></div>
                                                        <? } ?>
                                                        <div class="J_card_description"><?= $cur_card['card_position'] ?></div>
                                                    </div>
                                                    <div class="mid" style="margin-bottom:0px;">
                                                        <a href="tel:<?= $cur_card['card_phone'] ?>" class="J_card_number">
                                                            <?= $cur_card['card_phone'] ?>
                                                        </a>
                                                        <br>
                                                        <a class="J_card_address" href="https://map.naver.com/v5/search/<?= $cur_card['card_addr'] ?>" target="_blank">
                                                            <?= $cur_card['card_addr'] ?>
                                                        </a>
                                                    </div>
                                                    <div class="J_card_email" style="font-size:14px;">
                                                        <?= $cur_card['card_email'] ?>

                                                        <? if ($business_time != "") {
                                                            $busi_time = explode("\n", $business_time);
                                                        ?>
                                                            <div class="dropdown">
                                                                <a class="btn dropdown-toggle" type="button" data-toggle="dropdown" style="padding: 6px 0px;" aria-expanded="false">
                                                                    <span>
                                                                        영업시간 : <?= $busi_time[0] ?>
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 7" class="_12Coj" aria-hidden="true" style="width:14px;margin-left:5px;">
                                                                            <path d="M11.47.52a.74.74 0 0 0-1.04 0l-4.4 4.45v.01L1.57.52A.74.74 0 1 0 .53 1.57l5.12 5.08a.5.5 0 0 0 .7 0l5.12-5.08a.74.74 0 0 0 0-1.05z">
                                                                            </path>
                                                                        </svg>
                                                                    </span>
                                                                </a>
                                                                <ul class="dropdown-menu comunity" style="width: 300px;right: 0 !important;left: 0px !important;">
                                                                    <?
                                                                    for ($i = 1; $i < count($busi_time) - 1; $i++) {
                                                                    ?>
                                                                        <li><a><?= $busi_time[$i] ?></a></li>
                                                                    <? } ?>
                                                                </ul>
                                                            </div>
                                                        <? } ?>
                                                        <p style="font-size:14px;padding-top:25px;">
                                                            <? if ($online1_check == "Y") { ?>
                                                                <a href="<?= $story_online1 ?>" target="_blank" style="overflow-wrap: break-word;">
                                                                    <?= $story_online1_text ?> <?= $story_online1 ?>
                                                                </a>
                                                            <? } ?>
                                                        </p>
                                                        <p>
                                                            <? if ($online2_check == "Y") { ?>
                                                                <a href="<?= $story_online2 ?>" target="_blank">
                                                                    <?= $story_online2_text ?> <?= $story_online2 ?>
                                                                </a>
                                                            <? } ?>
                                                        </p>
                                                    </div>
                                                    <?
                                                    if ($ai_map_gmarket != 0 && $_SESSION['iam_member_id'] == $cur_card['mem_id']) {
                                                        $sql_chk_service_con = "select count(*) as cnt from Gn_Iam_Contents where card_idx='{$cur_card['idx']}' and contents_type=3";
                                                        $res_service_con = mysqli_query($self_con,$sql_chk_service_con);
                                                        $row_service_con = mysqli_fetch_array($res_service_con);
                                                        if ($row_service_con['cnt']) {
                                                    ?>
                                                            <button onclick="set_reduce('<?= $cur_card['idx'] ?>', '<?= $cur_card['sale_cnt'] ?>', '<?= $cur_card['add_reduce_val'] ?>', '<?= $cur_card['add_fixed_val'] ?>')" style="position: absolute;bottom: 20px;right: 20px;background-color: #99cc00;border-radius: 7px;color: white;font-size: 14px;padding: 5px;">할인설정</button>
                                                    <? }
                                                    } ?>
                                                </div>
                                                <div style="display:none;margin-top:-30px">
                                                    <a href="javascript:show_edit_card_list();" class="btn login_signup" style="width: 100%;background-color: #337ab7">다른 카드정보 가져오기
                                                    </a>
                                                    <div class="attr-value" id="edit_card_list" style="display:none;font-size: 12px;font-weight: 900;">
                                                        <?
                                                        $edit_card_sql = "select card_short_url,card_title from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' order by req_data asc";
                                                        $edit_card_res = mysqli_query($self_con,$edit_card_sql);
                                                        $i = 0;
                                                        while ($edit_card_row = mysqli_fetch_array($edit_card_res)) {
                                                            $i++;
                                                        ?>
                                                            <input type="radio" id="edit_card_url" name="edit_card_url" style="display: inline;width: auto;" value="<?= $edit_card_row['card_short_url'] ?>">
                                                            <span style="vertical-align: top;">
                                                                <? if ($edit_card_row['card_title']) {
                                                                    echo ($edit_card_row['card_title']);
                                                                } else {
                                                                    echo ($i . "번");
                                                                } ?>
                                                            </span>
                                                        <? } ?>
                                                    </div>
                                                    <div style="margin:2px 0px">
                                                        <? if ($cur_card['profile_logo']) { ?>
                                                            <img src="<?= cross_image($cur_card['profile_logo']) ?>" alt="" id="edit_card_logo" style="width:180px;height:50px" />
                                                        <? } else { ?>
                                                            <img class="logo" id="edit_card_logo" style="width:180px;height:50px">
                                                        <? } ?>
                                                        <input type="file" class="input" name="edit_card_logo_input" id="edit_card_logo_input" accept=".jpg,.jpeg,.png,.gif,.svc">
                                                    </div>
                                                    <div class="top">
                                                        <div class="J_card_name" style="max-width: 400px">
                                                            <input id="edit_card_title" class="card-input" style="font-size: 20px" value="<?= htmlspecialchars($cur_card['card_title']) ?>" placeholder="카드제목">
                                                        </div>
                                                        <div class="J_card_name" style="max-width: 400px">
                                                            <input id="edit_card_name" class="card-input" style="font-size: 20px" value="<?= htmlspecialchars($cur_card['card_name']) ?>" placeholder="성명">
                                                        </div>
                                                        <div class="J_card_company" style="margin-top:1px;">
                                                            <input id="edit_card_company" class="card-input" style="font-size: 16px;" value="<?= htmlspecialchars($cur_card['card_company']) ?>" placeholder="소속/직책">
                                                        </div>
                                                        <div class="J_card_description" style="margin-top:1px;">
                                                            <input id="edit_card_position" class="card-input" style="font-size: 14px;" value="<?= htmlspecialchars($cur_card['card_position']) ?>" placeholder="자기소개">
                                                        </div>
                                                    </div>
                                                    <div class="mid" style="margin-bottom:0px;">
                                                        <input id="edit_card_phone" class="J_card_number card-input" style="font-size: 20px" value='<?= $cur_card['card_phone'] ?>' placeholder="휴대폰번호">
                                                        <br>
                                                        <input id="edit_card_address" class="J_card_address card-input" value="<?= htmlspecialchars($cur_card['card_addr']) ?>" placeholder="직장주소">
                                                    </div>
                                                    <div class="J_card_email" style="font-size:14px;margin-top:1px;">
                                                        <input id="edit_card_email" class="card-input" value='<?= $cur_card['card_email'] ?>' placeholder="이메일주소">
                                                        <? if ($cur_card['business_time'] != "") { ?>
                                                            <div style="margin-top:25px;vertical-align:middle;display: flex;">
                                                                <textarea id="edit_card_business_time" style="width: 100%;height: 150px;"><?= $cur_card['business_time'] ?></textarea>
                                                            </div>
                                                        <? } ?>
                                                        <div style="margin-top:25px;vertical-align:middle;display: flex;">
                                                            <input id="edit_card_online1_text" class="card-input" value="<?= $online1_check == "Y" ? $story_online1_text : "" ?>" placeholder="사이트이름">
                                                            <input id="edit_card_online1" class="card-input" style="margin-left:5px;" value="<?= $online1_check == "Y" ? $story_online1 : "" ?>" placeholder="사이트주소">
                                                            <!-- <input id="edit_card_online1_check" type="checkbox" class="radio" <?= $online1_check == "Y" ? "checked" : "" ?> style="width:40px"> -->
                                                        </div>
                                                        <div style="margin-top:1px;vertical-align:middle;display: flex;">
                                                            <input id="edit_card_online2_text" class="card-input" value="<?= $online2_check == "Y" ? $story_online2_text : "" ?>" placeholder="사이트이름">
                                                            <input id="edit_card_online2" class="card-input" style="margin-left:5px;" value="<?= $online2_check == "Y" ? $story_online2 : "" ?>" placeholder="사이트주소">
                                                            <!-- <input id="edit_card_online2_check" type="checkbox" class="radio" <?= $online2_check == "Y" ? "checked" : "" ?> style="width:40px"> -->
                                                        </div>
                                                    </div>
                                                    <div style="margin-top:15px;vertical-align:middle;display: flex;">
                                                        <input type="checkbox" id="edit_card_radio_spec" class="radio" style="width:20px;height:20px;">
                                                        <h4 style="margin-top:5px;margin-left:5px">고급설정</h4>
                                                    </div>
                                                    <div id="edit_card_special" style="margin-bottom:0px;display:none">
                                                        <textarea id="edit_card_keyword" class="input card-input" name="edit_card_keyword" style="text-align:left;width:100%;" placeholder="아이엠에서 나를 검색할수 있는 단어 (30개 이내)로 입력하고, 입력시 [,]으로 구분하세요. (예시 : 강사,마케터,변호사,대안학교,노래방, 공부방 등)"><?= $cur_card['card_keyword'] ?></textarea>
                                                        <input id="edit_card_next_iam_link" class="J_card_address card-input" value='<?= $cur_card['next_iam_link'] ?>' placeholder="카드제목 클릭시 새창으로 열릴 다른 IAM주소 입력">
                                                    </div>
                                                    <div class="button-wrap">
                                                        <a href="javascript:cancel_edit_card();" class="button is-grey">취소</a>
                                                        <a href="javascript:namecard_check(<?= $cur_card['idx'] ?>);" class="button is-pink">저장</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!--카드현시까지 진행됨-->
                            <script>
                                if ($(".color-chips").height() > 140) {
                                    $("#card_title_down").show();
                                    $(".card_arrow").hide();
                                }
                                $(function() {
                                    $("#card_title_down").on("click", function() {
                                        $(".card_arrow").show();
                                        $("#card_title_up").show();
                                        $("#card_title_down").hide();

                                    });
                                    $("#card_title_up").on("click", function() {
                                        $("#card_title_down").show();
                                        $(".card_arrow").hide();
                                        $("#card_title_up").hide();
                                    });
                                });
                            </script>
                            <div class="box-footer" <? if ($_SESSION['iam_member_id']) echo "style='display:none'"; ?>>
                                <div class="inner clearfix">
                                    <div class="center" style="display:none;">
                                        <a href="javascript:iam_icon('iam_iamicon', 'https://oog.kiam.kr/pages/page_3295.php')">
                                            <span class="icon"><img src="/iam/img/main/icon-info.gif" width="200" height="25"></span>
                                        </a>
                                    </div>
                                    <? if ($_SESSION['iam_member_id'] != $card_owner && !$_SESSION['iam_member_id']) { ?>
                                        <div class="bottom">
                                            <? if ($_SESSION['iam_member_id']) { ?>
                                                <a href="/index.php" class="buttons" style="height:36px;padding-top:6px;font-size: 11px;">
                                                    <img src="/iam/img/menu/icon_join.png" style="height:24px">
                                                    <p style="font-size:14px">회원가입</p>
                                                </a>
                                                <a href="javascript:installApp();" class="buttons pink" style="height:36px;padding-top:6px;font-size: 11px;" title="[통합기능사용] 웹으로 이용이 가능하지만 앱을 설치하면 모든 기능을 이용할수 있습니다.">
                                                    <img src="/iam/img/menu/icon_sample.png" style="height:24px">
                                                    <label style="font-size:14px">샘플아이엠</label>
                                                </a>
                                                <a href="javascript:friends_join('<?= $cur_card['idx'] ?>');" class="buttons  lightgreen" style="height:36px;padding-top:6px;font-size: 11px;" title="[한번에 만들기] 포토, 프로필, 그리고 콘텐츠까지 한꺼번에 만들수 있습니다.">
                                                    <img src="/iam/img/menu/icon_home_add.png" style="height:24px">
                                                    <label style="font-size:14px">통합 IAM 만들기</label>
                                                </a>
                                            <? } else {
                                                if ($HTTP_HOST != "kiam.kr") {
                                                    $join_link = get_join_link("http://" . $HTTP_HOST, $card_owner);
                                                } else {
                                                    $join_link = get_join_link("http://www.kiam.kr", $card_owner);
                                                }
                                            ?>
                                                <a href="<?= $join_link ?>" class="buttons" style="height:36px;padding-top:6px;">
                                                    <img src="/iam/img/menu/icon_join.png" style="height:24px">
                                                    <label style="font-size:14px">회원가입</label>
                                                </a>
                                                <a href="javascript:installApp();" class="buttons" style="height:36px;padding-top:6px;" title="[통합기능사용] 웹으로 이용이 가능하지만 앱을 설치하면 모든 기능을 이용할수 있습니다.">
                                                    <img src="/iam/img/menu/icon_sample.png" style="height:24px">
                                                    <label style="font-size:14px">샘플아이엠</label>
                                                </a>
                                                <a href="javascript:addMainBtn('<?= str_replace("'", "", $cur_card['card_name']) ?>','?<?= $request_short_url . $card_owner_code ?>');" class="buttons" style="height:36px;padding-top:6px;" title="[한번에 가능] 회원가입과 프로필, 그리고 콘텐츠까지 한꺼번에 만들수 있습니다.">
                                                    <img src="/iam/img/menu/icon_home_add.png" style="height:24px">
                                                    <label style="font-size:14px">홈화면추가</label>
                                                </a>
                                            <? } ?>
                                        </div>
                                    <? } ?>
                                </div>
                            </div>
                            <div id="manage_auto_update" hidden style="text-align:center;border: solid 1px black;position:relative;margin-top: 10px;">
                                <p style="background:#99cc00;padding:10px;color:white;font-size:18px">콘텐츠 오토데이트 관리</p>
                                <div style="width:100%;padding:5px;float:right">
                                    <button style="float:right;font-size:14px;margin-right:10px;background:#797979;color:white" onclick="show_making_popup()">만들기</button>
                                </div>
                                <table style="width:100%;display:block;padding:20px;">
                                    <thead>
                                        <tr>
                                            <td class="iam_table" style="width:50px;">CNO</td>
                                            <td class="iam_table" style="width:60px;">분야</td>
                                            <td class="iam_table" style="width:85px;">수집키워드</td>
                                            <td class="iam_table" style="width:120px;">신청일시</td>
                                            <td class="iam_table" style="width:60px;">결제</td>
                                            <td class="iam_table" style="width:90px;">P잔액</td>
                                            <td class="iam_table" style="width:70px;">상태</td>
                                            <td class="iam_table" style="width:150px;">관리</td>
                                        </tr>
                                    </thead>
                                    <tbody id="auto_contetns_manage_show">

                                    </tbody>
                                </table>
                                <button style="border-radius:5px;width:20%;font-size:15px;margin-bottom:10px;" onclick="show_manage_auto(1)">닫기</button>
                                <button style="border-radius:5px;width:20%;font-size:15px;margin-bottom:10px;" onclick="search_auto_data(true)">더보기</button>
                            </div>
                            <? if ($_SESSION['iam_member_id'] == $card_owner) { ?>
                                <div style="padding-bottom:15px;">
                                    <div style="padding:10px;border: 1px solid #ddd;">
                                        <div class="right" style="float:right;margin-top:5px">
                                            <!--콘텐츠박스 만들기-->
                                            <input type="text" class="mycon_search_input" style="font-size: 12px;width: 140px;margin-left: -5px;height: 24px;border: 1px solid #aaa;border-radius: 15px;" value="<?= $_GET['search_key'] ?>" id="mycon_search_input" placeholder='키워드로 검색하세요.'>
                                            <i class="glyphicon glyphicon-remove" id="mycon_input" style="margin-top: -1px;height: 21px;width: 19px;border: 1px solid #aaaaaa;margin-left: -22px;line-height: 22px;color: darkgrey;border-left: none;border-top: none;border-bottom: none;border-radius: 15px;text-align: center;<?= $_GET['search_key'] ? '' : 'display: none;' ?>" onclick="clear_mycon_search()"></i>
                                            <img src="/iam/img/menu/icon_bottom_search.png" style="height:24px" onclick="mycon_search_clicked()">
                                            <span style="margin-left:2px;margin-top:2px;font-size:10px;line-height: 20px;" id="my_info_count"></span>
                                        </div>
                                        <div class="wish_card" style="display:flex;position:relative;" onclick="contents_add('<?= $card_owner ?>','',<?= $my_first_card ?>);">
                                            <? if ($cur_card['main_img1']) { ?>
                                                <div style="width: 38px;height: 38px;border-radius: 50%;overflow: hidden;">
                                                    <img src='<?= cross_image(str_replace("http://www.kiam.kr", $cdn_ssl, $cur_card['main_img1'])) ?>' style="width:100%;height:100%;object-fit: cover;">
                                                </div>
                                            <? } ?>
                                            <span style="margin-left:10px;border: 1px solid #ddd;border-radius: 8px;padding:5px 10px;font-weight:100">소개하고 싶은 콘텐츠를 올려보세요.</span>
                                        </div>
                                        <span class="tooltiptext-bottom-con" id="tooltiptext_contents_up" style="display:none;">
                                            <p style="font-weight:bold;margin-bottom:-15px;"><img src="/iam/img/main/icon-poplogo.png" style="width:12%;margin-right:5px;margin-bottom: 5px;">3. 콘텐츠 업로드</p><br>
                                            나를 브랜딩하는 콘텐츠를<br>
                                            한번에 업로드하세요.<br>
                                            <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:38%;font-size:15px;background-color: lightgrey;margin-top: 10px;margin-left: 20px;" onclick="contents_add('<?= $card_owner ?>','',<?= $my_first_card ?>);">만들기</button>
                                            <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:38%;font-size:15px;background-color: lightgrey;margin-top: 10px;" onclick="close_tooptip(3)">다음</button>
                                        </span>
                                    </div>
                                </div>
                            <? } ?>
                        </div>
                        <div class="tab-pane fade story-tab-content" id="story" role="tabpanel" aria-labelledby="story-tab">
                            <div class="box-body">
                                <div class="inner">
                                    <? if ($_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) { ?>
                                        <div class="utils clearfix">
                                            <div class="right">
                                                <a onclick="window.open('/iam/story.php?card_num=<?= $cur_card['idx'] ?>')" class="edit">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                    <? } ?>
                                    <dl class="story-item">
                                        <dt class="title">
                                            <span class="text" id="story_title1">
                                                <? if ($story_title1) { ?>
                                                    <?= $story_title1 ?>
                                                <? } else { ?>
                                                    내 소개
                                                <? } ?>
                                            </span>
                                        </dt>
                                        <dd class="content" style="position:relative;" id="story_info">
                                            <? if ($story_myinfo) { ?>
                                                <?= nl2br($story_myinfo) ?>
                                            <? } else { ?>
                                                "그건 할 수 없어,라는 말을 들을 때마다 나는 성공이 가까웠음을 안다."<br>
                                                온리원셀링으로 단골고객 5만명 확보<br>
                                                자동셀링경진대회 1회 대상 수상<br>
                                                아이엠 쇼핑몰 건강분야 판매 1위<br>
                                                18세부터 쇼핑몰 운영, 10억 매출 달성<br>
                                                고객의 니즈와 상품을 연결한 마케팅 전문<br>
                                                1인기업인, 자영업인, 프리랜서를 위한 셀링전략 전문<br>
                                            <? } ?>
                                        </dd>
                                    </dl>
                                    <dl class="story-item">
                                        <dt class="title">
                                            <span class="text" id="story_title2">
                                                <? if ($story_title2) { ?>
                                                    <?= $story_title2 ?>
                                                <? } else { ?>
                                                    <!-- 현재소속 -->
                                                <? } ?>
                                            </span>
                                        </dt>
                                        <dd class="content" style="position:relative;" id="story_company">
                                            <? if ($story_company) { ?>
                                                <?= nl2br($story_company) ?>
                                            <? } else { ?>
                                                <!-- 아이엠 쇼핑몰 대표<br>
                                            온리원브랜딩연구소 부소장<br>
                                            온리원셀링연구소 수석연구원<br>
                                            자동셀링경진대회 심사위원<br>
                                            온리원셀링 컨설턴트<br> -->
                                            <? } ?>
                                        </dd>
                                    </dl>
                                    <dl class="story-item">
                                        <dt class="title">
                                            <span class="text" id="story_title3">
                                                <? if ($story_title3) { ?>
                                                    <?= $story_title3 ?>
                                                <? } else { ?>
                                                    <!-- 경력소개 -->
                                                <? } ?>
                                            </span>
                                        </dt>
                                        <dd class="content" style="position:relative;" id="story_career">
                                            <? if ($story_career) { ?>
                                                <?= nl2br($story_career) ?>
                                            <? } else { ?>
                                                <!-- 2019년~ 아이엠쇼핑몰 대표<br>
                                            2010~2018년 년셀스타 의류쇼핑몰 대표<br>
                                            2017년~ 온리원셀링연구소 연구원<br> -->
                                            <? } ?>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <!-- 2020-11-09 종이명함탭 -->
                        <div class="tab-pane fade paper-tab-content" id="paper" role="tabpanel" aria-labelledby="paper-tab">
                            <div class="box-body">
                                <?
                                if ($card_num !== "") {
                                    $page_search = $cur_card['card_short_url'];
                                } else {
                                    $page_search = "";
                                }

                                $search_range = $_GET['search_range'];
                                $search_str = $_GET['search_str'];

                                if (is_null($search_str)) {
                                    $search_str = "";
                                }
                                ?>
                                <div class="search-box clearfix J1">
                                    <? if ($_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) { ?>
                                        <div class="row" style="margin-left: 8px;margin-bottom: 10px;margin-top: 7px;">
                                            <div class="left">
                                                <div class="buttons">
                                                    <a href="javascript:paper_range('1')" class="button" <? if ((int)$search_range == 1) { ?>style="background-color: #000;" <? } else { ?>style="background-color: #fff;color:black;" <? } ?>>가</a>
                                                    <a href="javascript:paper_range('3')" class="button" <? if ((int)$search_range == 3) { ?>style="background-color: #000;" <? } else { ?>style="background-color: #fff;color:black;" <? } ?>><i class="fa fa-history" aria-hidden="true"></i></a>
                                                    <input type="hidden" name="paper_range" id="paper_range" value="<?= $search_range ?>">
                                                </div>
                                                <div id="paper_chk_count" class="selects">0개 선택됨</div>
                                            </div>
                                        </div>
                                        <div class="row" style="line-height: 30px">
                                            <div class="search J_search" style="display:inline;width:60%;margin-right:8px;">
                                                <button type="button" onclick="paper_submit();" class="submit" style="top: 3px;background: #f9f9f9;font-size: 17px;">
                                                    <img src="/iam/img/menu/icon_bottom_search.png" style="margin-left:5px;width:24px">
                                                </button>
                                                <input type="text" name="paper_search_str" id="paper_search_str" class="input" value="<?= $search_str ?>" onkeyup="enterkey('paper_submit');" style="font-size:12px;height: 25px;background:#f9f9f9;width:50%;outline:none;" placeholder="검색어를 입력해주세요.">
                                            </div>
                                            <!-- 2020-11-09 전체선택 추가 -->
                                            <div style="float:right;padding-top:5px;padding-left:10px;">
                                                <input type="checkbox" name="cbG03" id="cbG03" class="css-checkbox" onclick='groupCheckClick_paper(this); '>
                                                <label for="cbG03" class="css-label cb0">전체선택</label>
                                            </div>
                                        </div>
                                    <? } ?>
                                </div>
                                <div class="inner" id="papercard_list">

                                </div>
                            </div>
                        </div>
                        <!-- ########################################################################################################### -->
                        <!-- 2020-11-09 연락처탭 -->
                        <div class="tab-pane fade contact-tab-content" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="box-body">
                                <?
                                if ($card_num !== "") {
                                    $page_search = $cur_card['card_short_url'];
                                } else {
                                    $page_search = "";
                                }

                                $search_range = $_GET['search_range'];
                                $search_str = $_GET['search_str'];

                                if (is_null($search_str)) {
                                    $search_str = "";
                                }
                                ?>
                                <div class="search-box clearfix J1">
                                    <? if ($_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) { ?>
                                        <div class="row" style="margin-left: 8px;margin-bottom: 10px;margin-top: 7px;">
                                            <div class="left">
                                                <div class="buttons">
                                                    <a href="javascript:contact_range('1')" class="button" <? if ((int)$search_range == 1) { ?>style="background-color: #000;" <? } else { ?>style="background-color: #fff;color:black;" <? } ?>>가</a>
                                                    <a href="javascript:contact_range('3')" class="button" <? if ((int)$search_range == 3) { ?>style="background-color: #000;" <? } else { ?>style="background-color: #fff;color:black;" <? } ?>><i class="fa fa-history" aria-hidden="true"></i></a>
                                                    <input type="hidden" name="contact_range" id="contact_range" value="<?= $search_range ?>">
                                                </div>
                                                <div id="contact_chk_count" class="selects">0개 선택됨</div>
                                            </div>
                                        </div>
                                        <div class="row" style="line-height: 30px">
                                            <div class="search J_search" style="display:inline;width:60%;margin-right:8px;">
                                                <button type="button" onclick="contact_submit();" class="submit" style="top: 3px;background: #f9f9f9;font-size: 17px;">
                                                    <!--img src="/iam/img/menu/icon_bottom_search.png" style="margin-left:5px;width:24px" onclick="wecon_search_clicked()"-->
                                                    <img src="/iam/img/menu/icon_bottom_search.png" style="margin-left:5px;width:24px">
                                                </button>
                                                <input type="text" name="contact_search_str" id="contact_search_str" class="input" value="<?= $search_str ?>" onkeyup="enterkey('contact_submit');" style="font-size:12px;height: 25px;background:#f9f9f9;width:50%;outline:none;" placeholder="검색어를 입력해주세요.">
                                            </div>
                                            <!-- 2020-11-09 전체선택 추가 -->
                                            <div style="float:right;padding-top:5px;padding-left:10px;">
                                                <input type="checkbox" name="cbG02" id="cbG02" class="css-checkbox" onclick='groupCheckClick_contact(this); '>
                                                <label for="cbG02" class="css-label cb0">전체선택</label>
                                            </div>
                                            <div style="float:right;padding-top:5px;">
                                                <img src="/iam/img/menu/icon_my_stroy.png" style="width:30px;height:30px;" onclick="contact_submit_paper()">
                                            </div>
                                        </div>
                                    <? } ?>
                                </div>
                                <div class="inner" id="div_contact">
                                </div>
                            </div>
                        </div>
                        <!-- ########################################################################################## -->
                        <!-- 프렌즈탭 -->
                        <div class="tab-pane fade  friends-tab-content " id="friends" role="tabpanel" aria-labelledby="friends-tab">
                            <div class="box-body">
                                <?
                                $site_info_friends = explode(".", $HTTP_HOST);
                                $site_name_friends = $site_info_friends[0];
                                if ($site_name_friends == "www") {
                                    $site_name_friends = "kiam";
                                }

                                $show_all = "N";
                                if (isset($_GET['show_all'])) {
                                    $show_all = $_GET['show_all'];
                                }

                                if ($card_num !== "") {
                                    $page_search2 = $cur_card['card_short_url'];
                                } else {
                                    $page_search2 = "";
                                }

                                if (is_null($search_str2)) {
                                    $search_str2 = "";
                                }

                                if (!$search_type) {
                                    if ($_SESSION['iam_member_id'] == $card_owner) {
                                        $search_type = 2;
                                    } else {
                                        $search_type = 1;
                                    }
                                }
                                ?>
                                <div class="search-box clearfix J2" id="friends_search">
                                    <? if ($_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) { ?>
                                        <div class="row" style='margin-left: 8px;margin-bottom: 10px;margin-top: 7px;'>
                                            <div class="left">
                                                <div class="buttons">
                                                    <a href="javascript:friends_range('1');" class="button" <? if ((int)$search_range2 == 1) { ?>style="background-color: #000;" <? } else { ?>style="background-color: #fff;color:black;" <? } ?>>가</a>
                                                    <a href="javascript:friends_range('3');" class="button" <? if ((int)$search_range2 == 3) { ?>style="background-color: #000;" <? } else { ?>style="background-color: #fff;color:black;" <? } ?>><i class="fa fa-history" aria-hidden="true"></i></a>
                                                    <input type="hidden" name="friends_range" id="friends_range" value="<?= $search_range2 ?>">
                                                </div>
                                                <div id="friends_chk_count" class="selects">0개 선택됨</div>
                                            </div>
                                            <div class="right">
                                                <select name="search_type" id="search_type" style="font-size:12px;margin-right:20px; border: 1px solid #ddd;background: #fff;border-radius: 5px;" onchange="friends_submit('<?= $show_all ?>')">
                                                    <option value="1" <? if ((int)$search_type == 1) { ?>selected <? } ?>>나의 프렌즈</option>
                                                    <option value="2" <? if ((int)$search_type == 2) { ?>selected <? } ?>>공개 프로필</option>
                                                    <option value="3" <? if ((int)$search_type == 3) { ?>selected <? } ?>>소속 회원</option>
                                                    <option value="4" <? if ((int)$search_type == 4) { ?>selected <? } ?>>조인 회원</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row" style="line-height : 30px">
                                            <div class="search J_search" style="display:inline;width:60%;margin:10px 20px;">
                                                <button type="button" onclick="friends_submit('<?= $show_all ?>');" class="submit" style="top: 3px;background: #f9f9f9;font-size: 17px;">
                                                    <!--i class="fa fa-search" aria-hidden="true"></i-->
                                                    <img src="/iam/img/menu/icon_bottom_search.png" style="margin-left:5px;width:24px">
                                                </button>
                                                <input type="text" name="friend_search_str" id="friend_search_str" class="input" value="<?= $search_str2 ?>" onkeyup="enterkey('friends_submit');" style="font-size:12px;height: 25px;background:#f9f9f9;width:50%;outline:none;" placeholder="검색어를 입력해주세요.">
                                            </div>

                                            <div style="float:right;padding-top:5px;padding-left:20px;">
                                                <?php
                                                if ($search_type == 3 || $search_type == 4) {
                                                    if ($show_all == "N") {
                                                        $style_list = "";
                                                        $style_page = "";
                                                ?>
                                                        <a href="javascript:friends_submit('Y');"><img src="/iam/img/main/icon_all_gray.png" style="width: 20px;display:none;"></a>
                                                        <img src="/iam/img/star/icon-profile.png" style="width: 20px" onclick="friends_add();">
                                                    <? } else {
                                                        $style_list = "height: 700px;overflow: auto;margin-bottom: 20px;";
                                                        $style_page = "display:none;"; ?>
                                                        <a href="javascript:friends_submit('N');"><img src="/iam/img/main/icon_all_green.png" style="width: 20px"></a>
                                                        <img src="/iam/img/star/icon-profile.png" style="width: 20px" onclick="friends_add();">
                                                <? }
                                                } ?>
                                                <? if ($search_type == 2) { ?>
                                                    <img src="/iam/img/star/icon-profile.png" style="width: 20px" onclick="friends_add();">
                                                <? } ?>
                                                <input type="checkbox" name="cbG01" id="cbG01" class="css-checkbox" onclick='groupCheckClick(this);'>
                                                <label for="cbG01" class="css-label cb0">전체선택</label>
                                            </div>
                                        </div>
                                    <? } ?>
                                </div>
                                <input type="hidden" name="show_all" id="show_all" value="<?= $show_all ?>">
                                <div class="inner" id="div_friends">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <?
            } else if ($cur_win == "group-con") {
                if ($gkind != "recommend" && $gkind != "mygroup" && $gkind != "search" && $gkind != "search_con") {
                    $group_sql = "select * from gn_group_info where idx='$gkind'";
                    $group_res = mysqli_query($self_con,$group_sql);
                    $group_row = mysqli_fetch_array($group_res);
                    $group_manager = $group_row['manager'];
                    $group_card_idx = $group_row['card_idx'];
                ?>
                    <section id="main-slider">
                        <!-- 슬라이더 영역 시작 -->
                        <div id="mainSlider">
                            <? if ($main_img1) { ?>
                                <div class="slider-item">
                                    <a data-fancybox="gallery" id="main_img1" href="<?= cross_image($main_img1) ?>">
                                        <img id="for_size_1" src="<?= cross_image($main_img1) ?>" style="opacity:0;">
                                    </a>
                                </div>
                            <? } ?>
                            <? if ($main_img2) { ?>
                                <div class="slider-item">
                                    <a id="main_img2" data-fancybox="gallery" href="<?= cross_image($main_img2) ?>">
                                        <img id="for_size_2" src="<?= cross_image($main_img2) ?>" style="opacity:0;">
                                    </a>
                                </div>
                            <? } ?>
                            <? if ($main_img3) { ?>
                                <div class="slider-item">
                                    <a id="main_img3" data-fancybox="gallery" href="<?= cross_image($main_img3) ?>">
                                        <img id="for_size_3" src="<?= cross_image($main_img3) ?>" style="opacity:0;">
                                    </a>
                                </div>
                            <? } ?>
                        </div>
                        <? if ($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $group_manager) { ?>
                            <a href="#" class="controls" title="<?= $MENU['IAM_MENU']['M9_TITLE']; ?>" data-card_idx="<?= $group_card['idx'] ?>">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </a>
                        <? } ?>
                        <div class="slider-arrows"></div>
                    </section><!-- // 슬라이더 영역 끝 -->
                <? } ?>
                <section id="middle">
                    <? if ($gkind == "recommend") {
                        $group_sql = "select * from gn_group_info";
                        $group_res = mysqli_query($self_con,$group_sql);
                        $other_group = array();
                        while ($group_row = mysqli_fetch_array($group_res)) {
                            $group_check_sql = "select count(*) from gn_group_member where mem_id='{$_SESSION['iam_member_id']}' and group_id='{$group_row['idx']}'";
                            $group_check_res = mysqli_query($self_con,$group_check_sql);
                            $group_check_row = mysqli_fetch_array($group_check_res);
                            if ($group_check_row[0] == 0)
                                array_push($other_group, $group_row['idx']);
                        }
                        $other_group = implode(",", $other_group);
                        $g_card_sql = "select card_short_url,main_img1,group_id,mem_id from Gn_Iam_Name_Card n where n.group_id in (" . $other_group . ") and sample_click='Y' order by sample_order desc";
                        $g_card_res = mysqli_query($self_con,$g_card_sql);
                    ?>
                        <div style="width:100%;overflow-x:auto;display: -webkit-box;border:1px solid #ddd">
                            <? while ($g_card_row = mysqli_fetch_array($g_card_res)) {
                                $sql_mem_g = "select mem_code from Gn_Member where mem_id='{$g_card_row['mem_id']}'";
                                $res_mem_g = mysqli_query($self_con,$sql_mem_g);
                                $row_mem_g = mysqli_fetch_array($res_mem_g);
                            ?>
                                <div class="group-card" style="width:25%;float: left;">
                                    <div class="group-card-sample" onclick="location.href='<?= '/?' . $g_card_row['card_short_url'] . $row_mem_g['mem_code'] . '&cur_win=group-con&gkind=' . $g_card_row['group_id'] ?>'">
                                        <img src="<?= cross_image($g_card_row['main_img1']) ?>" style="width:100%;">
                                        <?
                                        $group_sql = "select * from gn_group_info where idx = '{$g_card_row['group_id']}'";
                                        $group_res = mysqli_query($self_con,$group_sql);
                                        $group_row = mysqli_fetch_array($group_res);
                                        ?>
                                        <div style="position:absolute;bottom:10px;left:0px;width:100%;text-align:center">
                                            <span class="label label-info" font-size:90%;width:80%><?= $group_row['name'] ?></span>
                                        </div>
                                    </div>
                                </div>
                            <? } ?>
                            <script>
                                $(".group-card").height($(".group-card").width());
                            </script>
                        </div>
                        <?
                        $g_cont_sql = "select * from Gn_Iam_Contents where group_id in (" . $other_group . ") and sample_display='Y' and group_display = 'Y' order by sample_order desc";
                        $sql_count = str_replace(" * ", " count(idx) ", $g_cont_sql);
                        $g_cont_res = mysqli_query($self_con,$sql_count);
                        $g_cont_count_row = mysqli_fetch_array($g_cont_res);
                        $g_cont_count = $g_cont_count_row[0];
                        $g_cont_res = mysqli_query($self_con,$g_cont_sql);
                        if ($g_cont_count > 0) { ?>
                            <div class="group-item" style="margin-top:10px;border:1px solid #eee;display: flex;justify-content: space-between;">
                                <span class="label label-default" style="font-size:120%">회원님을 위한 추천 콘텐츠</span>
                                <? if ($g_cont_count > 1) { ?>
                                    <span class="label label-primary" style="font-size:120%" onclick="show_all_group_sample_contents()">모두 보기(<?= $g_cont_count - 1 ?>)개</span>
                                <? } ?>
                            </div>
                            <?
                            $g_cont_row = mysqli_fetch_array($g_cont_res);
                            $g_card_sql = "select card_short_url, mem_id,main_img1,card_name,group_id from Gn_Iam_Name_Card c where c.idx = '{$g_cont_row['card_idx']}'";
                            $g_card_res = mysqli_query($self_con,$g_card_sql);
                            $g_card_row = mysqli_fetch_array($g_card_res);

                            $sql_mem_g = "select mem_code from Gn_Member where mem_id='{$g_card_row['mem_id']}'";
                            $res_mem_g = mysqli_query($self_con,$sql_mem_g);
                            $row_mem_g = mysqli_fetch_array($res_mem_g);

                            if (!$g_cont_row['contents_img'])
                                $g_cont_images = null;
                            else
                                $g_cont_images = explode(",", $g_cont_row['contents_img']);
                            for ($i = 0; $i < count($g_cont_images); $i++) {
                                if (strstr($g_cont_images[$i], "kiam")) {
                                    $g_cont_images[$i] = str_replace("http://kiam.kr", "", $g_cont_images[$i]);
                                    $g_cont_images[$i] = str_replace("http://www.kiam.kr", "", $g_cont_images[$i]);
                                    //$g_cont_images[$i] = $cdn_ssl . $g_cont_images[$i];
                                }
                                if (!strstr($g_cont_images[$i], "http") && $g_cont_images[$i]) {
                                    $g_cont_images[$i] = $cdn_ssl . $g_cont_images[$i];
                                }
                            }
                            ?>
                            <div class="group_sample_content-item" id="group_sample_contents_list" style="margin-bottom: 20px;box-shadow: 2px 3px 3px 1px #eee;border: 1px solid #ccc;">
                                <div class="user-item" style="position: relative;display: flex;padding: 4px;border: none;border-bottom: 1px solid #dddddd;padding-top: 12px;padding-bottom: 12px;overflow:hidden;">
                                    <a href="<?= '/?' . $g_card_row['card_short_url'] . $row_mem_g['mem_code'] . '&cur_win=group-con&gkind=' . $g_card_row['group_id'] ?>" class="img-box" style="padding: 4px;">
                                        <div class="user-img" style="width: 38px;height: 38px;border-radius: 50%;overflow: hidden;">
                                            <img src="<?= $g_card_row['main_img1'] ? cross_image($g_card_row['main_img1']) : '/iam/img/common/logo-2.png' ?>" alt="" style="width: 100%;height: 100%;object-fit: cover;">
                                        </div>
                                    </a>
                                    <div class="wrap" style="width: 50%;display: flex;flex-direction: column;padding: 4px 6px;">
                                        <a href="<?= '/?' . $g_card_row['card_short_url'] . $row_mem_g['mem_code'] . '&cur_win=group-con&gkind=' . $g_card_row['group_id'] ?>" class="user-name" style="font-size: 15px;font-weight: 700;">
                                            <?= $g_card_row['card_name'] ?>
                                        </a>
                                        <? if ($g_cont_row['contents_title'] != "") { ?>
                                            <div class="title">
                                                <div class="text" style="text-align : left;font-weight: bold;font-size: 25px;margin: auto 10px;overflow: hidden;text-overflow: clip;height: 75px;"><?= $g_cont_row['contents_title'] ?></div>
                                            </div>
                                        <? } ?>
                                        <a href="<?= '/?' . $g_card_row['card_short_url'] . $row_mem_g['mem_code'] . '&cur_win=group-con&gkind=' . $g_card_row['group_id'] ?>" class="date" style="font-size: 13px;color: rgb(153, 153, 153);">
                                            <?= $g_cont_row['req_data'] ?>
                                        </a>
                                    </div>
                                    <div class="media-inner" style="position: absolute; right: 2px; top: 0px; width: 30%;height: 100%;text-align: center;background:<?= $color[0]; ?>">
                                        <? if ((int)$g_cont_row['contents_type'] == 1 || (int)$g_cont_row['contents_type'] == 3) {
                                            if (count($g_cont_images) > 0) {
                                                if ($g_cont_row['contents_url']) { ?>
                                                    <a onclick="showIframeModal('<?= $g_cont_row['contents_url'] ?>')" target="_blank">
                                                        <? if (count($g_cont_images) == 1) { ?>
                                                            <img src="<?= cross_image($g_cont_images[0]) ?>" style="height : 100%"></a>
                                                <? } else { ?>
                                                    <img src="<?= cross_image($g_cont_images[0]) ?>" style="height : 100%" onclick=""></a>
                                                <? } ?>
                                            <? } else { ?>
                                                <? if (count($g_cont_images) == 1) { ?>
                                                    <img src="<?= cross_image($g_cont_images[0]) ?>" style="height : 100%">
                                                <? } else { ?>
                                                    <img src="<?= cross_image($g_cont_images[0]) ?>" style="height : 100%" onclick="">
                                                <? } ?>
                                        <?
                                                }
                                            }
                                        } else if ((int)$g_cont_row['contents_type'] == 2) {
                                            $img_cnt = count($g_cont_images);
                                            $vid_array = explode(" ", $g_cont_row['contents_iframe']);
                                            $vid_array[2] = "height=100%";
                                            $vid_array[1] = "width=100%";
                                            $vid_data = implode(' ', $vid_array);
                                            echo $vid_data;
                                            /*if($img_cnt == 0){
                                        echo $vid_data;
                                    }else{?>
                                        <div onclick="play_list<?=$g_cont_row['idx'];?>();" id="vidwrap_list<?=$g_cont_row['idx'];?>" style="position:relative;">
                                            <?if(count($g_cont_images) == 1){?>
                                                <img src="<?=cross_image($g_cont_images[0])?>" style = "height : 110px;">
                                                <?if($g_cont_row['contents_img']){?>
                                                    <img src="/iam/img/movie_play.png" style="position: absolute; z-index: 50; left: 40%; width: 50px; top: 40%;">
                                                <?}?>
                                            <?}else{?>
                                                <img src="<?=cross_image($g_cont_images[0])?>" style = "height : 100%" onclick = "">
                                            <?}?>
                                        </div>
                                        <script type="text/javascript">
                                            function play_list<?=$g_cont_row['idx'];?>() {
                                                document.getElementById('vidwrap_list<?=$g_cont_row['idx'];?>').innerHTML = "<?=$vid_data?>";
                                            }
                                        </script>
                                    <?
                                    }*/
                                        } else if ((int)$g_cont_row['contents_type'] == 4) {
                                            $vid_data = $g_cont_row['source_iframe'];
                                        ?>
                                        <div>
                                            <iframe src="<?= $vid_data ?>" width="100%" height="100%"></iframe>
                                        </div>
                                    <? } ?>
                                    </div>
                                </div>
                            </div>
                        <?
                        }
                    } else if ($gkind == "mygroup") {
                        $invite_sql = "select info.name,info.card_idx,invite.group_id,invite.mem_id from gn_group_invite invite 
                                inner join gn_group_info info on info.idx = invite.group_id where invite_id='{$_SESSION['iam_member_id']}'";
                        $invite_res = mysqli_query($self_con,$invite_sql);
                        $invite_count = mysqli_num_rows($invite_res);
                        if ($invite_count > 0) {
                        ?>
                            <div style="width:100%;border:1px solid #ddd;padding:5px;">
                                <h3 style="margin-left:10px">초대 검토</h3>
                                <h5 style="margin-left:10px">다음 그룹에 초대되었습니다.</h5>
                                <?
                                while ($invite_row = mysqli_fetch_array($invite_res)) {
                                    $sql_card_in = "select main_img1 as group_img from Gn_Iam_Name_Card where idx='{$invite_row['card_idx']}'";
                                    $res_card_in = mysqli_query($self_con,$sql_card_in);
                                    $row_card_in = mysqli_fetch_array($res_card_in);

                                    $sql_mem_in = "select mem_name, profile as mem_img from Gn_Member where mem_id='{$invite_row['mem_id']}'";
                                    $res_mem_in = mysqli_query($self_con,$sql_mem_in);
                                    $row_mem_in = mysqli_fetch_array($res_mem_in);
                                ?>
                                    <div style="padding-top: 2px;">
                                        <div style="border:1px solid #ddd;background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display:flex;justify-content: space-between;">
                                            <div style="display: flex">
                                                <div style="border-radius: 10%;width: 50px;height: 50px;overflow: hidden;margin: 10px;position:relative">
                                                    <img src="<?= $row_card_in['group_img'] ?>" style="width: 50px;height:50px;">
                                                    <div style="position:absolute;border:1px solid white;border-radius: 50%;width: 30px;height: 30px;overflow: hidden;top:25px;left:25px">
                                                        <img src="<?= $row_mem_in['mem_img'] ?>" style="width: 50px;height:50px;">
                                                    </div>
                                                </div>
                                                <div>
                                                    <h4 style="margin-left: 10px;margin-top: 10px"><?= $invite_row['name'] ?></h4>
                                                    <h5 style="margin-left: 10px;margin-top: 10px;margin-bottom: 10px">
                                                        <?= $row_mem_in['mem_name'] . "님이 회원님을 초대했습니다." ?>
                                                    </h5>
                                                </div>
                                            </div>
                                            <div style="display:flex;margin-right:10px">
                                                <div class="dropdown " style="margin-right: 10px;margin-top: auto;margin-bottom: auto;">
                                                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" style="margin-top:0px">
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu comunity" style="border: 1px solid #ccc;padding:0px;">
                                                        <?
                                                        $card_sql = "select card_name from Gn_Iam_Name_Card where group_id = '{$invite_row['group_id']}' order by req_data";
                                                        $card_res = mysqli_query($self_con,$card_sql);
                                                        $card_num = 1;
                                                        while ($card_row = mysqli_fetch_array($card_res)) {
                                                        ?>
                                                            <li><a onclick="javascript:;;" style="padding:3px 3px 0px 3px !important;"><?= $card_row[0] == "" ? $card_num . "번카드" : $card_row[0]; ?></a></li>
                                                        <?
                                                            $card_num++;
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <div style="display:grid">
                                                    <button class="btn btn-primary" style="margin-top:5px" onclick="click_invite('<?= $invite_row['group_id'] ?>','accept')">참여</button>
                                                    <button class="btn btn-danger" style="margin-top:5px;margin-bottom:5px;" onclick="click_invite('<?= $invite_row['group_id'] ?>','del')">삭제</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <? } ?>
                            </div>
                        <? }
                        $group_sql = "select description,name,card_idx,manager from gn_group_info info where manager = '{$_SESSION['iam_member_id']}'";
                        $group_res = mysqli_query($self_con,$group_sql);
                        $group_num = mysqli_num_rows($group_res);
                        if ($group_num > 0) {
                        ?>
                            <div style="width:100%;border:1px solid #ddd;padding:5px;">
                                <h3 style="margin-left:10px">관리중인 그룹</h3>
                                <?
                                while ($group_row = mysqli_fetch_array($group_res)) {
                                    $sql_card_g = "select main_img1 as group_img,card_short_url,group_id from Gn_Iam_Name_Card where idx='{$group_row['card_idx']}'";
                                    $res_card_g = mysqli_query($self_con,$sql_card_g);
                                    $row_card_g = mysqli_fetch_array($res_card_g);

                                    $sql_mem_g = "select mem_code from Gn_Member where mem_id='{$group_row['manager']}'";
                                    $res_mem_g = mysqli_query($self_con,$sql_mem_g);
                                    $row_mem_g = mysqli_fetch_array($res_mem_g);
                                ?>
                                    <div style="padding-top: 2px;cursor:pointer" onclick="location.href = '?' + '<?= $row_card_g['card_short_url'] . $row_mem_g['mem_code'] ?>'+'&cur_win=group-con&gkind=' +'<?= $row_card_g['group_id'] ?>'">
                                        <div style="border:1px solid #ddd;background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display:flex;justify-content: space-between;">
                                            <div style="display: flex">
                                                <div style="border-radius: 10%;width: 50px;height: 50px;overflow: hidden;margin: 10px;position:relative">
                                                    <img src="<?= $row_card_g['group_img'] ?>" style="width: 50px;height:50px;">
                                                </div>
                                                <div>
                                                    <h4 style="margin-left: 10px;margin-top: 10px"><?= $group_row['name'] ?></h4>
                                                    <h5 style="margin-left: 10px;margin-top: 10px;margin-bottom: 10px">
                                                        <?= $group_row['description'] ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <? } ?>
                            </div>
                        <? }
                        $group_sql = "select g_mem.group_id,info.card_idx,g_mem.mem_id,description,name from gn_group_member g_mem
                            inner join gn_group_info info on g_mem.group_id = info.idx where g_mem.mem_id = '{$_SESSION['iam_member_id']}' and g_mem.fix_status = 'Y'";
                        $group_res = mysqli_query($self_con,$group_sql);
                        $group_num = mysqli_num_rows($group_res);
                        ?>
                        <div style="width:100%;border:1px solid #ddd;padding:5px;">
                            <div style="display:flex;justify-content: space-between;">
                                <h3 style="margin-left:10px">고정한 그룹</h3>
                                <button class="btn btn-primary" onclick="change_group_fix_status()">수정</button>
                            </div>
                            <?
                            while ($group_row = mysqli_fetch_array($group_res)) {
                                $sql_card_g = "select main_img1 as group_img,card_short_url from Gn_Iam_Name_Card where idx='{$group_row['card_idx']}'";
                                $res_card_g = mysqli_query($self_con,$sql_card_g);
                                $row_card_g = mysqli_fetch_array($res_card_g);

                                $sql_mem_g = "select mem_code from Gn_Member where mem_id='{$group_row['mem_id']}'";
                                $res_mem_g = mysqli_query($self_con,$sql_mem_g);
                                $row_mem_g = mysqli_fetch_array($res_mem_g);
                            ?>
                                <div style="padding-top: 2px;cursor:pointer">
                                    <div style="border:1px solid #ddd;background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display:flex;justify-content: space-between;">
                                        <div style="display: flex" onclick="location.href = '?' + '<?= $row_card_g['card_short_url'] . $row_mem_g['mem_code'] ?>'+'&cur_win=group-con&gkind=' +'<?= $group_row['group_id'] ?>'">
                                            <div style="border-radius: 10%;width: 50px;height: 50px;overflow: hidden;margin: 10px;position:relative">
                                                <img src="<?= $row_card_g['group_img'] ?>" style="width: 50px;height:50px;">
                                            </div>
                                            <div>
                                                <h4 style="margin-left: 10px;margin-top: 10px"><?= $group_row['name'] ?></h4>
                                                <h5 style="margin-left: 10px;margin-top: 10px;margin-bottom: 10px">
                                                    <?= $group_row['description'] ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div style="display:flex;margin-right:10px">
                                            <div class="dropdown " style="margin-right: 10px;margin-top: auto;margin-bottom: auto;">
                                                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" style="margin-top:0px">
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu comunity" style="border: 1px solid #ccc;padding:0px;">
                                                    <?
                                                    $card_sql = "select card_name from Gn_Iam_Name_Card where group_id = '{$group_row['group_id']}' order by req_data";
                                                    $card_res = mysqli_query($self_con,$card_sql);
                                                    $card_num = 1;
                                                    while ($card_row = mysqli_fetch_array($card_res)) {
                                                    ?>
                                                        <li><a onclick="javascript:;;" style="padding:3px 3px 0px 3px !important;"><?= $card_row[0] == "" ? $card_num . "번카드" : $card_row[0]; ?></a></li>
                                                    <?
                                                        $card_num++;
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <? } ?>
                        </div>
                        <?
                        if ($etc_order == "")
                            $etc_order = "reg";
                        $group_order = array("visit" => "visit_date desc", "name" => "name", "reg" => "g_mem.req_date desc");
                        $group_sql = "select g_mem.group_id,description,name,info.card_idx,g_mem.mem_id from gn_group_member g_mem
                            inner join gn_group_info info on g_mem.group_id = info.idx where g_mem.mem_id = '{$_SESSION['iam_member_id']}' and g_mem.fix_status = 'N'";
                        $group_sql .= " order by " . $group_order[$etc_order];
                        $group_res = mysqli_query($self_con,$group_sql);
                        $group_num = mysqli_num_rows($group_res);
                        ?>
                        <div style="width:100%;border:1px solid #ddd;padding:5px;">
                            <div style="display:flex;justify-content: space-between;">
                                <h3 style="margin-left:10px">기타 그룹</h3>
                                <div class="dropdown " style="margin-right: 10px;margin-top: auto;margin-bottom: auto;">
                                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" style="background:white">
                                        <img src="/iam/img/main/icon-rearrow.png" style="height:20px">
                                    </button>
                                    <ul class="dropdown-menu comunity" style="border: 1px solid #ccc;padding:0px;">
                                        <li><a href="?cur_win=group-con&gkind=mygroup&etc_order=visit" style="padding:3px 3px 0px 3px !important;">방문순</a></li>
                                        <li><a href="?cur_win=group-con&gkind=mygroup&etc_order=name" style="padding:3px 3px 0px 3px !important;">그룹 이름순</a></li>
                                        <li><a href="?cur_win=group-con&gkind=mygroup&etc_order=reg" style="padding:3px 3px 0px 3px !important;">최근 가입한 그룹</a></li>
                                    </ul>
                                </div>
                            </div>
                            <?
                            while ($group_row = mysqli_fetch_array($group_res)) {
                                $sql_card_g = "select main_img1 as group_img,card_short_url from Gn_Iam_Name_Card where idx='{$group_row['card_idx']}'";
                                $res_card_g = mysqli_query($self_con,$sql_card_g);
                                $row_card_g = mysqli_fetch_array($res_card_g);

                                $sql_mem_g = "select mem_code from Gn_Member where mem_id='{$group_row['mem_id']}'";
                                $res_mem_g = mysqli_query($self_con,$sql_mem_g);
                                $row_mem_g = mysqli_fetch_array($res_mem_g);
                            ?>
                                <div style="padding-top: 2px;cursor:pointer">
                                    <div style="border:1px solid #ddd;background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display:flex;justify-content: space-between;">
                                        <div style="display: flex" onclick="location.href = '?' + '<?= $row_card_g['card_short_url'] . $row_mem_g['mem_code'] ?>'+'&cur_win=group-con&gkind=' +'<?= $group_row['group_id'] ?>'">
                                            <div style="border-radius: 10%;width: 50px;height: 50px;overflow: hidden;margin: 10px;position:relative">
                                                <img src="<?= $row_card_g['group_img'] ?>" style="width: 50px;height:50px;">
                                            </div>
                                            <div>
                                                <h4 style="margin-left: 10px;margin-top: 10px"><?= $group_row['name'] ?></h4>
                                                <h5 style="margin-left: 10px;margin-top: 10px;margin-bottom: 10px">
                                                    <?= $group_row['description'] ?>
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="dropdown " style="margin-right: 10px;margin-top: auto;margin-bottom: auto;">
                                            <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu comunity" style="border: 1px solid #ccc;padding:0px;">
                                                <?
                                                $card_sql = "select card_name from Gn_Iam_Name_Card where group_id = '{$group_row['group_id']}' order by req_data";
                                                $card_res = mysqli_query($self_con,$card_sql);
                                                $card_num = 1;
                                                while ($card_row = mysqli_fetch_array($card_res)) {
                                                ?>
                                                    <li><a onclick="javascript:;;" style="padding:3px 3px 0px 3px !important;"><?= $card_row[0] == "" ? $card_num . "번카드" : $card_row[0]; ?></a></li>
                                                <?
                                                    $card_num++;
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <? } ?>
                        </div>
                    <? } else if ($gkind == "search") { ?>
                        <div style="width:100%;border:1px solid #ddd;padding:5px;">
                            <div style="display:flex;justify-content: space-between;">
                                <div>
                                    <h3 style="margin-left:10px;margin-top:10px">회원님을 위한 추천</h3>
                                    <h4 style="margin-left:10px;margin-top:5px"><small>회원님이 관심을 가질 만한 그룹입니다.</small></h4>
                                </div>
                                <button class="btn btn-link" onclick="show_all_group('sample')">모두 보기</button>
                            </div>
                            <? $group_sql = "select card_short_url,main_img1,group_id,card.mem_id from Gn_Iam_Name_Card card
                                        where group_id is not NULL and group_id > 0 and group_id not in ($my_group) and sample_click = 'Y' order by sample_order desc limit 0,5";
                            $sample_index = 0;
                            $group_res = mysqli_query($self_con,$group_sql);
                            while ($group_row = mysqli_fetch_array($group_res)) {
                                $sql_grp_info = "select name from gn_group_info where idx='{$group_row['group_id']}'";
                                $res_grp_info = mysqli_query($self_con,$sql_grp_info);
                                $row_grp_info = mysqli_fetch_array($res_grp_info);

                                $sql_mem_info = "select mem_code from Gn_Member where mem_id='{$group_row['mem_id']}'";
                                $res_mem_info = mysqli_query($self_con,$sql_mem_info);
                                $row_mem_info = mysqli_fetch_array($res_mem_info);

                                $mem_sql = "select count(idx) from gn_group_member where group_id='{$group_row['group_id']}'";
                                $mem_res = mysqli_query($self_con,$mem_sql);
                                $mem_row = mysqli_fetch_array($mem_res);
                                $weekMondayTime = date("Y-m-d", strtotime('last Monday'));
                                $cont_sql = "select count(idx) from Gn_Iam_Contents where group_id='{$group_row['group_id']}' and req_data >= '$weekMondayTime'";
                                $cont_res = mysqli_query($self_con,$cont_sql);
                                $cont_row = mysqli_fetch_array($cont_res);
                                $f_sql = "select mem_name, profile from Gn_Member where site_iam = '{$Gn_mem_row['site_iam']}' and mem_id in (select mem_id from gn_group_member where group_id='{$group_row['group_id']}')";
                                $f_res = mysqli_query($self_con,$f_sql);
                                $f_count = mysqli_num_rows($f_res);
                                if ($sample_index == 0) {
                            ?>
                                    <div style="padding-top: 10px;">
                                        <div style="border:1px solid #ddd;background-color: #ffffff;border-top-right-radius:10px;border-top-left-radius:10px;;padding-bottom: 2px;position:relative;padding-bottom: 60%;overflow: hidden;">
                                            <div class="content" style="background-image:url('<?= $group_row['main_img1'] ?>');background-size: cover;height:100%;background-position: center;" onclick="location.href = '?' + '<?= $group_row['card_short_url'] . $row_mem_info['mem_code'] ?>'+'&cur_win=group-con&gkind=' +'<?= $group_row['group_id'] ?>'">
                                            </div>
                                        </div>
                                        <div style="border:1px solid #ddd;border-bottom-left-radius: 10px;border-bottom-right-radius: 10px;">
                                            <h4 style="margin-left:10px;margin-top:10px"><?= $row_grp_info['name'] ?></h4>
                                            <h4 style="margin-left:10px;margin-top:5px"><small>멤버 <?= $mem_row[0] ?>명 &bull; 주간 게시물 <?= $cont_row[0] ?>개</small></h4>
                                            <? if ($f_count > 0) { ?>
                                                <div style="display:flex;margin-left:20px;margin-top:10px">
                                                    <?
                                                    $f_index = 0;
                                                    while ($f_row = mysqli_fetch_array($f_res)) {
                                                        if ($f_index == 0)
                                                            $f_name = $f_row['mem_name'];
                                                        if ($f_index++ < 12) { ?>
                                                            <div style="border:1px solid #ddd;border-radius: 50%;width: 30px;height: 30px;overflow: hidden;margin-left: -10px;">
                                                                <img src="<?= cross_image($f_row['profile']) ?>" style="width: 100%;height:100%;">
                                                            </div>
                                                    <?  }
                                                    } ?>
                                                </div>
                                                <h4 style="margin-left:10px;margin-top:5px"><small><?= $f_name . "님 외 친구 " . ($f_count - 1) . "명이 멤버입니다." ?></small></h4>
                                            <? } ?>
                                            <div style="text-align:center;margin:10px 0px">
                                                <button class="btn btn-success" style="width:95%" onclick="join_group('<?= $group_row['group_id'] ?>')">그룹 참여</button>
                                            </div>
                                        </div>
                                    </div>
                                <?  } else { ?>
                                    <div style="padding-top: 2px;">
                                        <div style="border:1px solid #ddd;background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display:flex;justify-content: space-between;">
                                            <div style="display: flex" onclick="location.href = '?' + '<?= $group_row['card_short_url'] . $group_row['mem_code'] ?>'+'&cur_win=group-con&gkind=' +'<?= $group_row['group_id'] ?>'">
                                                <div style="border-radius: 10%;width: 50px;height: 50px;overflow: hidden;margin: 10px;position:relative">
                                                    <img src="<?= $group_row['main_img1'] ?>" style="width: 50px;height:50px;">
                                                </div>
                                                <div>
                                                    <h4 style="margin-left: 10px;margin-top: 10px"><?= $group_row['name'] ?></h4>
                                                    <h5 style="margin-left: 10px;margin-top: 10px;margin-bottom: 10px">
                                                        <small>멤버 <?= $mem_row[0] ?>명 &bull; 주간 게시물 <?= $cont_row[0] ?>개</small>
                                                    </h5>
                                                    <? if ($f_count > 0) { ?>
                                                        <div style="display:flex;margin-left:20px;margin-top:10px">
                                                            <?
                                                            $f_index = 0;
                                                            while ($f_row = mysqli_fetch_array($f_res)) {
                                                                if ($f_index == 0)
                                                                    $f_name = $f_row['mem_name'];
                                                                if ($f_index++ < 12) { ?>
                                                                    <div style="border:1px solid #ddd;border-radius: 50%;width: 30px;height: 30px;overflow: hidden;margin-left: -10px;">
                                                                        <img src="<?= cross_image($f_row['profile']) ?>" style="width: 100%;height:100%;">
                                                                    </div>
                                                            <?  }
                                                            } ?>
                                                        </div>
                                                        <h5 style="margin-left:10px;margin-top:5px"><small><?= $f_name . "님 외 친구 " . ($f_count - 1) . "명이 멤버입니다." ?></small></h5>
                                                    <? } ?>
                                                </div>
                                            </div>
                                            <div style="display:flex">
                                                <div class="dropdown " style="margin-right: 10px;margin-top: auto;margin-bottom: auto;">
                                                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu comunity" style="border: 1px solid #ccc;padding:0px;">
                                                        <?
                                                        $card_sql = "select card_name from Gn_Iam_Name_Card where group_id = '{$group_row['group_id']}' order by req_data";
                                                        $card_res = mysqli_query($self_con,$card_sql);
                                                        $card_num = 1;
                                                        while ($card_row = mysqli_fetch_array($card_res)) {
                                                        ?>
                                                            <li><a onclick="javascript:;;" style="padding:3px 3px 0px 3px !important;"><?= $card_row[0] == "" ? $card_num . "번카드" : $card_row[0]; ?></a></li>
                                                        <?
                                                            $card_num++;
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <button class="btn btn-link" type="button" onclick="join_group('<?= $group_row['group_id'] ?>')">참여</button>
                                            </div>
                                        </div>
                                    </div>
                            <?  }
                                $sample_index++;
                            } ?>
                        </div>
                        <div style="width:100%;border:1px solid #ddd;padding:5px;margin-top:5px">
                            <div style="display:flex;justify-content: space-between;">
                                <div>
                                    <h3 style="margin-left:10px;margin-top:10px">친구의 그룹</h3>
                                    <h4 style="margin-left:10px;margin-top:5px"><small>친구들이 가입한 그룹입니다.</small></h4>
                                </div>
                                <button class="btn btn-link" onclick="show_all_group('friend')">모두 보기</button>
                            </div>
                            <?
                            $group_sql = "select name,ggm.group_id,count(*) as g_mem_count,ggi.card_idx 
                                    from gn_group_member ggm 
                                    inner join gn_group_info ggi on ggi.idx=ggm.group_id
                                    inner join Gn_Member m on m.mem_id=ggm.mem_id
                                    where m.site_iam='{$Gn_mem_row['site_iam']}'
                                        and ggm.group_id not in (select group_id from gn_group_member where mem_id ='{$_SESSION['iam_member_id']}') 
                                        group by group_id order by g_mem_count desc limit 0,5";
                            $gm_index = 0;
                            $group_res = mysqli_query($self_con,$group_sql);
                            while ($group_row = mysqli_fetch_array($group_res)) {
                                $sql_card_g = "select mem_id,main_img1,card_short_url from Gn_Iam_Name_Card where idx='{$group_row['card_idx']}'";
                                $res_card_g = mysqli_query($self_con,$sql_card_g);
                                $row_card_g = mysqli_fetch_array($res_card_g);

                                $sql_mem_g = "select mem_code from Gn_Member where mem_id='{$row_card_g['mem_id']}'";
                                $res_mem_g = mysqli_query($self_con,$sql_mem_g);
                                $row_mem_g = mysqli_fetch_array($res_mem_g);

                                $mem_sql = "select count(*) from gn_group_member where group_id='{$group_row['group_id']}'";
                                $mem_res = mysqli_query($self_con,$mem_sql);
                                $mem_row = mysqli_fetch_array($mem_res);
                                $weekMondayTime = date("Y-m-d", strtotime('last Monday'));
                                $cont_sql = "select count(*) from Gn_Iam_Contents where group_id='{$group_row['group_id']}' and group_display = 'Y' and req_data >= '$weekMondayTime'";
                                $cont_res = mysqli_query($self_con,$cont_sql);
                                $cont_row = mysqli_fetch_array($cont_res);
                                $f_sql = "select mem_name, profile from Gn_Member where site_iam = '{$Gn_mem_row['site_iam']}' and mem_id in (select mem_id from gn_group_member where group_id='{$group_row['group_id']}')";
                                $f_res = mysqli_query($self_con,$f_sql);
                                $f_count = mysqli_num_rows($f_res);
                                if ($gm_index == 0) {
                            ?>
                                    <div style="padding-top: 10px;">
                                        <div style="border:1px solid #ddd;background-color: #ffffff;border-top-right-radius:10px;border-top-left-radius:10px;;padding-bottom: 2px;position:relative;padding-bottom: 60%;overflow: hidden;">
                                            <div class="content" style="background-image:url('<?= $row_card_g['main_img1'] ?>');background-size: cover;height:100%;background-position: center;" onclick="location.href = '?' + '<?= $row_card_g['card_short_url'] . $row_mem_g['mem_code'] ?>'+'&cur_win=group-con&gkind=' +'<?= $group_row['group_id'] ?>'">
                                            </div>
                                        </div>
                                        <div style="border:1px solid #ddd;border-bottom-left-radius: 10px;border-bottom-right-radius: 10px;">
                                            <h4 style="margin-left:10px;margin-top:10px"><?= $group_row['name'] ?></h4>
                                            <h4 style="margin-left:10px;margin-top:5px"><small>멤버 <?= $mem_row[0] ?>명 &bull; 주간 게시물 <?= $cont_row[0] ?>개</small></h4>
                                            <? if ($f_count > 0) { ?>
                                                <div style="display:flex;margin-left:20px;margin-top:10px">
                                                    <?
                                                    $f_index = 0;
                                                    while ($f_row = mysqli_fetch_array($f_res)) {
                                                        if ($f_index == 0)
                                                            $f_name = $f_row['mem_name'];
                                                        if ($f_index++ < 12) { ?>
                                                            <div style="border:1px solid #ddd;border-radius: 50%;width: 30px;height: 30px;overflow: hidden;margin-left: -10px;">
                                                                <img src="<?= cross_image($f_row['profile']) ?>" style="width: 100%;height:100%;">
                                                            </div>
                                                    <?  }
                                                    } ?>
                                                </div>
                                                <h4 style="margin-left:10px;margin-top:5px"><small><?= $f_name . "님 외 친구 " . ($f_count - 1) . "명이 멤버입니다." ?></small></h4>
                                            <? } ?>
                                            <div style="text-align:center;margin:10px 0px">
                                                <button class="btn btn-success" style="width:95%" onclick="join_group('<?= $group_row['group_id'] ?>')">그룹 참여</button>
                                            </div>
                                        </div>
                                    </div>
                                <?  } else { ?>
                                    <div style="padding-top: 2px;">
                                        <div style="border:1px solid #ddd;background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display:flex;justify-content: space-between;">
                                            <div style="display: flex" onclick="location.href = '?' + '<?= $row_card_g['card_short_url'] . $row_mem_g['mem_code'] ?>'+'&cur_win=group-con&gkind=' +'<?= $group_row['group_id'] ?>'">
                                                <div style="border-radius: 10%;width: 50px;height: 50px;overflow: hidden;margin: 10px;position:relative">
                                                    <img src="<?= $group_row['main_img1'] ?>" style="width: 50px;height:50px;">
                                                </div>
                                                <div>
                                                    <h4 style="margin-left: 10px;margin-top: 10px"><?= $group_row['name'] ?></h4>
                                                    <h5 style="margin-left: 10px;margin-top: 10px;margin-bottom: 10px">
                                                        <small>멤버 <?= $mem_row[0] ?>명 &bull; 주간 게시물 <?= $cont_row[0] ?>개</small>
                                                    </h5>
                                                    <? if ($f_count > 0) { ?>
                                                        <div style="display:flex;margin-left:20px;margin-top:10px">
                                                            <?
                                                            $f_index = 0;
                                                            while ($f_row = mysqli_fetch_array($f_res)) {
                                                                if ($f_index == 0)
                                                                    $f_name = $f_row['mem_name'];
                                                                if ($f_index++ < 12) { ?>
                                                                    <div style="border:1px solid #ddd;border-radius: 50%;width: 30px;height: 30px;overflow: hidden;margin-left: -10px;">
                                                                        <img src="<?= cross_image($f_row['profile']) ?>" style="width: 100%;height:100%;">
                                                                    </div>
                                                            <?  }
                                                            } ?>
                                                        </div>
                                                        <h5 style="margin-left:10px;margin-top:5px"><small><?= $f_name . "님 외 친구 " . ($f_count - 1) . "명이 멤버입니다." ?></small></h5>
                                                    <? } ?>
                                                </div>
                                            </div>
                                            <div style="display:flex">
                                                <div class="dropdown " style="margin-right: 10px;margin-top: auto;margin-bottom: auto;">
                                                    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu comunity" style="border: 1px solid #ccc;padding:0px;">
                                                        <?
                                                        $card_sql = "select card_name from Gn_Iam_Name_Card where group_id = '{$group_row['group_id']}' order by req_data";
                                                        $card_res = mysqli_query($self_con,$card_sql);
                                                        $card_num = 1;
                                                        while ($card_row = mysqli_fetch_array($card_res)) {
                                                        ?>
                                                            <li><a onclick="javascript:;;" style="padding:3px 3px 0px 3px !important;"><?= $card_row[0] == "" ? $card_num . "번카드" : $card_row[0]; ?></a></li>
                                                        <?
                                                            $card_num++;
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <button class="btn btn-link" type="button" onclick="join_group('<?= $group_row['group_id'] ?>')">참여</button>
                                            </div>
                                        </div>
                                    </div>
                            <?  }
                                $gm_index++;
                            } ?>
                        </div>
                    <? } else if ($gkind != "search_con") { ?>
                        <div style="cursor:pointer;display:flex;justify-content: space-between;margin-top:5px">
                            <div>
                                <?
                                $group_public = $group_row['public_status'] == "Y" ? "공개그룹" : "비공개그룹";
                                $group_upload = $group_row['upload_status'];
                                ?>
                                <h3 style="margin-left:10px" onclick="open_group_info_modal('<?= $gkind ?>', '<?= $Gn_mem_row['site_iam'] ?>')"><?= $group_row['name'] . " >" ?></h3>
                                <?
                                $group_sql = "select count(idx) from gn_group_member where group_id='$gkind'";
                                $group_res = mysqli_query($self_con,$group_sql);
                                $group_row = mysqli_fetch_array($group_res);
                                $group_mem_count = $group_row[0];
                                $time = date("Y-m-d");
                                $group_sql = "select count(idx) from gn_group_member where group_id='$gkind' and req_date >= '$time'";
                                $group_res = mysqli_query($self_con,$group_sql);
                                $group_row = mysqli_fetch_array($group_res);
                                $group_new_mem_count = $group_row[0];
                                if ($my_group == "")
                                    $group_sql = "select count(idx) from Gn_Iam_Contents where group_id='$gkind' and group_display = 'Y' and req_data >= '$time'";
                                else
                                    $group_sql = "select count(idx) from Gn_Iam_Contents where group_id='$gkind' and req_data >= '$time'";
                                $group_res = mysqli_query($self_con,$group_sql);
                                $group_row = mysqli_fetch_array($group_res);
                                $group_new_cont_count = $group_row[0];

                                $group_card_sql = "select card_name,card_title from Gn_Iam_Name_Card where card_short_url = '$group_card_url'";
                                $group_card_res = mysqli_query($self_con,$group_card_sql);
                                $group_card_row = mysqli_fetch_array($group_card_res);

                                $group_check_sql = "select count(idx) from gn_group_member where group_id='$gkind' and mem_id='{$_SESSION['iam_member_id']}'";
                                $group_check_res = mysqli_query($self_con,$group_check_sql);
                                $group_check_row = mysqli_fetch_array($group_check_res);
                                ?>
                                <div style="display:flex;margin-top:5px;margin-left:10px">
                                    <h5><?= $group_public . " &bull; 멤버 " . $group_mem_count . "명 &bull;" ?></h5>
                                    <h5 style="cursor:pointer" onclick="<? if ($_SESSION['iam_member_id'] == $group_manager) echo 'open_all_member_1(' . $gkind . ')' ?>"><?= " 신규 " . $group_new_mem_count . "명 &bull;" ?></h5>
                                    <h5 style="cursor:pointer" onclick="<? if ($_SESSION['iam_member_id'] == $group_manager /*&& $group_upload == 'N'*/) echo 'open_group_contents(' . $gkind . ')' ?>"><?= " 신규콘텐츠 " . $group_new_cont_count . "개" ?></h5>
                                </div>
                            </div>
                            <div style="display:flex;margin-right:10px">
                                <? if ($group_check_row[0] > 0) { ?>
                                    <button type="button" class="btn btn-primary" style="height:24px;padding:0px 12px;margin-right:10px" onclick="open_invite_modal()">초대</button>
                                <? } else { ?>
                                    <button type="button" class="btn btn-primary" style="height:24px;padding:0px 12px;margin-right:10px" onclick="join_group('<?= $gkind ?>')">참여</button>
                                <? } ?>
                                <div>
                                    <a href="javascript:showGroupShareModal();">
                                        <img src="/iam/img/menu/icon_share_black.png" alt="" title="" style="margin-left:0px;width:24px;height:24px">
                                    </a>
                                </div>
                                <div>
                                    <a href="javascript:addMainBtn('<?= str_replace("'", "", $group_card_row['card_name']) ?>','?<?= $group_card_url . $card_owner_code ?>');">
                                        <img src="/iam/img/menu/icon_home_add.png" alt="" title="<?= $MENU['IAM_CARD_BTN']['BTN6_TITLE']; ?>" style="margin-left:0px;width:24px;height:24px">
                                    </a>
                                </div>
                                <? //--그룹카드 우측 세점 드롭박스 부분--//
                                if ($group_check_row[0] > 0) {
                                    if ($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $group_manager) {
                                ?>
                                        <div class="dropdown right">
                                            <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_dot.png" style="width:24px;height:24px">
                                            <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                                <li>
                                                    <? if ($group_card['phone_display'] == 'Y') { ?>
                                                        <a href="javascript:card_phone_display('<?= $group_card['idx'] ?>', '<?= $_SESSION['iam_member_id'] ?>','N')">
                                                            <img src="/iam/img/icon_unlock.svg" title="<?= $MENU['IAM_CARD_M']['CM2_TITLE']; ?>" style="width: 20px"><?= $MENU['IAM_CARD_M']['CM2']; ?>
                                                        </a>
                                                    <? } else { ?>
                                                        <a href="javascript:card_phone_display('<?= $group_card['idx'] ?>', '<?= $_SESSION['iam_member_id'] ?>','Y')">
                                                            <img src="/iam/img/icon_lock.svg" title="<?= $MENU['IAM_CARD_M']['CM1_TITLE']; ?>" style="width: 20px"><?= $MENU['IAM_CARD_M']['CM1']; ?>
                                                        </a>
                                                    <? } ?>
                                                </li>
                                                <? if ($_SESSION['iam_member_id'] == $group_manager) { ?>
                                                    <li>
                                                        <a href="javascript:edit_group_card('<?= $group_card_url ?>');">
                                                            <img src="/iam/img/menu/icon_edit.png" alt="" title="" style="width:20px;">
                                                            <?= $MENU['IAM_CARD_M']['CM3']; ?>
                                                        </a>
                                                    </li>
                                                <? } ?>
                                                <? if ((int)$_SESSION['iam_member_leb'] != 0) { ?>
                                                    <li>
                                                        <a href="/iam/mypage.php" class="edit">
                                                            <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true" title="<?= $MENU['IAM_CARD_M']['CM3_1_TITLE']; ?>"></i><?= $MENU['IAM_CARD_M']['CM3_1']; ?>
                                                        </a>
                                                    </li>
                                                <? } ?>
                                                <li>
                                                    <a href="javascript:open_cont_order_popup('<?= $cur_win ?>', '<?= $cur_card['card_short_url'] ?>', '<?= $group_card_url ?>');" title="카드 콘텐츠 편집하기">
                                                        <img src="/iam/img/main/icon-contedit.png" style="height: 20px">
                                                        카드 콘텐츠 편집
                                                    </a>
                                                </li>
                                                <li>
                                                    <a onclick="showCardLike('<?= $post_display == 0 ? 1 : 0; ?>','<?= $group_card_url ?>')" id="show_card_title_menu" title="<?= $MENU['IAM_CARD_M']['CM7_TITLE']; ?>">
                                                        <img src="/iam/img/main/icon-16.png" title="댓글박스" style="height: 20px">
                                                        <? if ($post_display == 1) {
                                                            echo $MENU['IAM_CARD_M']['CM8'];
                                                        } else {
                                                            echo $MENU['IAM_CARD_M']['CM7'];
                                                        } ?>
                                                    </a>
                                                </li>
                                                <? if ($group_card_idx != $cur_card['idx']) { ?>
                                                    <li>
                                                        <a href="javascript:namecard_del('<?= $group_card['idx'] ?>');" title="<?= $MENU['IAM_CARD_M']['CM11_TITLE']; ?>">
                                                            <img src="/iam/img/star/icon-bin.png" style="height: 20px">
                                                            <?= $MENU['IAM_CARD_M']['CM11']; ?>
                                                        </a>
                                                    </li>
                                                <? } ?>
                                                <li>
                                                    <a href="javascript:move_card();" title="<?= $MENU['IAM_CARD_M']['CM12_TITLE']; ?>">
                                                        <img src="/iam/img/main/icon-card_move.png" style="height: 20px">
                                                        <?= $MENU['IAM_CARD_M']['CM12']; ?>
                                                    </a>
                                                </li>
                                                <!--li>
                                            <a href="javascript:show_manage_auto(0)" title="콘텐츠 오토데이트 관리">
                                                <img src="/iam/img/icon-update.png" title="" style="height: 23px;margin-left: -2px;">
                                                오토 데이트 기능
                                            </a>
                                        </li-->
                                            </ul>
                                        </div>
                                    <? } else if ($_SESSION['iam_member_id']) { ?>
                                        <div class="dropdown right" style="margin-right:10px;height:30px;">
                                            <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_dot.png" style="height: 24px;">
                                            <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                                <li>
                                                    <a href="javascript:group_out('<?= $gkind ?>', '<?= $_SESSION['iam_member_id'] ?>')">
                                                        <img src="/iam/img/main/icon-exit.png" title="그룹 나가기" style="height: 20px">그룹 나가기
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                <? }
                                } ?>
                            </div>
                        </div>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade profile-tab-content in active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div id="right-side-dropdown" style="min-height:30px;position:relative;margin-top:10px">
                                    <div id="card_title_down" style="text-align:right;height:20px;display:none">
                                        <img src="/iam/img/main/icon-19.png" style="height:20px;margin-right:10px">
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="inner" style="padding-top:0px">
                                        <div class="utils clearfix" style="margin-bottom: 0px;">
                                            <div class="left">
                                                <div class="color-chips">
                                                    <?
                                                    $title_display = "inline-block";
                                                    $g_card_count = $g_cont_count = 0;
                                                    $black_circle_sql = "select idx,card_short_url,card_title,card_name,phone_display,next_iam_link,mem_id from Gn_Iam_Name_Card where group_id = '$gkind' order by req_data asc";
                                                    $black_circle_result = mysqli_query($self_con,$black_circle_sql);
                                                    while ($black_circle_row = mysqli_fetch_array($black_circle_result)) {
                                                        $private_class = ($black_circle_row['phone_display'] == 'N' ? "private" : "");
                                                        $active_class = ($group_card_url == $black_circle_row['card_short_url'] ? "active" : "");
                                                        $card_link = $black_circle_row['card_short_url'] . $user_mem_code . '&cur_win=group-con&gkind=' . $gkind;
                                                        $card_idx = $black_circle_row['idx'];
                                                        $next_iam_link = $black_circle_row['next_iam_link'] ? ($black_circle_row['next_iam_link'] . $user_mem_code . '&cur_win=group-con&gkind=' . $gkind) : "";
                                                        $cont_sql = "select count(*) from Gn_Iam_Contents where card_idx='$card_idx'";
                                                        $cont_res = mysqli_query($self_con,$cont_sql);
                                                        $cont_row = mysqli_fetch_array($cont_res);
                                                        $g_cont_count += $cont_row[0];
                                                        $n_cont_sql = "select count(*) from Gn_Iam_Contents where card_idx = '$card_idx' and req_data > '$today'";
                                                        $n_cont_res = mysqli_query($self_con,$n_cont_sql);
                                                        $n_cont_row = mysqli_fetch_array($n_cont_res);
                                                    ?>
                                                        <a href="?<?= $card_link ?>" class="J_card_num <?= $private_class ?> <?= $active_class ?>" id="card_title" onclick="show_next_iam('<?= $next_iam_link ?>');" draggable="false" ondragstart="drag(event,'<?= $card_link ?>')" ondrop="drop(event,'<?= $card_link ?>')" ondragover="allowDrop(event)" ontouchstart="pickup(event,'<?= $card_link ?>')" ontouchmove="move(event)" ontouchend="pickdown(event)" style="z-index:10;display: <?= $title_display ?>;font-size: 15px;" title=<?= $black_circle_row['card_title'] ?>>
                                                            <?= $black_circle_row['card_title'] ? $black_circle_row['card_title'] : $card_count + 1 ?><?= $next_iam_link == "" ? "" : "+" ?><?= $n_cont_row[0] > 0 ? "(" . $n_cont_row[0] . ")" : "" ?>
                                                        </a>
                                                        <script>
                                                            if ($(".color-chips").height() > 140) {
                                                                $("a").last().addClass("card_arrow");
                                                            }
                                                        </script>
                                                    <?
                                                        $g_card_count++;
                                                    }
                                                    if ($_SESSION['iam_member_id'] == $group_manager) { ?>
                                                        <a onclick="show_create_group_card_popup();" class="J_card_num" id="card_title" title="<?= $MENU['IAM_CARD_M']['CM9_TITLE']; ?>" style="font-size: 15px;font-weight: 700;text-align: center;z-index:10;background:white;color:#529c03;display: <?= $title_display ?>">
                                                            <span style="font-size:15px">새 카드 만들기</span>
                                                        </a>
                                                    <? } ?>
                                                    <script>
                                                        if ($(".color-chips").height() > 140) {
                                                            $("#card_title_down").show();
                                                            $(".card_arrow").hide();
                                                        }
                                                        $(function() {
                                                            $("#card_title_down").on("click", function() {
                                                                $(".card_arrow").show();
                                                                $("#card_title_up").show();
                                                                $("#card_title_down").hide();
                                                            });
                                                            $("#card_title_up").on("click", function() {
                                                                $("#card_title_down").show();
                                                                $(".card_arrow").hide();
                                                                $("#card_title_up").hide();
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="card_title_up" style="text-align:right;height:20px;display:none;">
                            <img src="/iam/img/main/icon-20.png" style="height:20px;margin-right:10px">
                        </div>
                        <div id="manage_auto_update" hidden style="text-align:center;border: solid 1px black;position:relative;margin-top: 10px;">
                            <p style="background:#99cc00;padding:10px;color:white;font-size:18px">콘텐츠 오토데이트 관리</p>
                            <div style="width:100%;padding:5px;float:right">
                                <button style="float:right;font-size:14px;margin-right:10px;background:#797979;color:white" onclick="show_making_popup()">만들기</button>
                            </div>
                            <table style="width:100%;display:block;padding:20px;">
                                <thead>
                                    <tr>
                                        <td class="iam_table" style="width:50px;">CNO</td>
                                        <td class="iam_table" style="width:60px;">분야</td>
                                        <td class="iam_table" style="width:85px;">수집키워드</td>
                                        <td class="iam_table" style="width:120px;">신청일시</td>
                                        <td class="iam_table" style="width:60px;">결제</td>
                                        <td class="iam_table" style="width:90px;">P잔액</td>
                                        <td class="iam_table" style="width:70px;">상태</td>
                                        <td class="iam_table" style="width:150px;">관리</td>
                                    </tr>
                                </thead>
                                <tbody id="auto_contetns_manage_show">

                                </tbody>
                            </table>
                            <button style="border-radius:5px;width:20%;font-size:15px;margin-bottom:10px;" onclick="show_manage_auto(1)">닫기</button>
                            <button style="border-radius:5px;width:20%;font-size:15px;margin-bottom:10px;" onclick="search_auto_data(true)">더보기</button>
                        </div>
                        <div style="padding-bottom:15px;margin-top:10px">
                            <div style="padding:10px;border: 1px solid #ddd;">
                                <div style="display:flex;" onclick="contents_add('<?= $_SESSION['iam_member_id'] ?>','','','<?= $gkind ?>');">
                                    <? if ($cur_card['main_img1']) { ?>
                                        <div style="width: 38px;height: 38px;border-radius: 50%;overflow: hidden;">
                                            <img src='<?= cross_image(str_replace("http://www.kiam.kr", $cdn_ssl, $cur_card['main_img1'])) ?>' style="width:100%;height:100%;object-fit: cover;">
                                        </div>
                                    <? } ?>
                                    <span style="margin-left:10px;border: 1px solid #ddd;border-radius: 8px;padding:5px 10px;font-weight:100">소개하고 싶은 콘텐츠를 올려보세요.</span>
                                </div>
                            </div>
                        </div>
                    <? } ?>
                </section>
            <? } ?>
            <!-- // 중단 콘텐츠 영역 끝 -->
            <!-- ############################################################################################################################## -->
            <!-- 하단 영역 시작 -->
            <section id="bottom">
                <!-- 콘텐츠부분 -->
                <?
                // middle log
                $logs->add_log("section start");
                // julian 콘텐츠를 순환하면서 뿌려주는 부분
                $search_key = $_GET['search_key'];
                $cont_array = array();
                //카드명이 검색키와 같은거 꺼내기
                if ($search_key) {
                    if ($_GET['key1'] == 4) {
                        $search_title = $search_seperate = '';
                        if (strpos($search_key, '|') !== false) {
                            $gwc_search_key_arr = explode('|', $search_key);
                            for ($i = 0; $i < count($gwc_search_key_arr); $i++) {
                                if ($i == count($gwc_search_key_arr) - 1) {
                                    $and_str = '';
                                } else {
                                    $and_str = ' or ';
                                }
                                $gwc_search_key = trim($gwc_search_key_arr[$i]);
                                $search_title .= "contents_title like '%$gwc_search_key%'" . $and_str;
                                $search_seperate .= "product_seperate like '%$gwc_search_key%'" . $and_str;
                            }
                            $search_sql = "((" . $search_title . ") or (" . $search_seperate . ") ";
                        } else if (strpos($search_key, ' ') !== false) {
                            $gwc_search_key_arr = explode(' ', $search_key);
                            for ($i = 0; $i < count($gwc_search_key_arr); $i++) {
                                if ($i == count($gwc_search_key_arr) - 1) {
                                    $and_str = '';
                                } else {
                                    $and_str = ' and ';
                                }
                                $gwc_search_key = trim($gwc_search_key_arr[$i]);
                                $search_title .= "contents_title like '%$gwc_search_key%'" . $and_str;
                                $search_seperate .= "product_seperate like '%$gwc_search_key%'" . $and_str;
                            }
                            $search_sql = "((" . $search_title . ") or (" . $search_seperate . ") ";
                        } else {
                            $search_sql = "((contents_title like '%" . $search_key . "%') or (product_seperate like '%" . $search_key . "%') ";
                        }
                    } else {
                        if (strpos($search_key, ' ') !== false) {
                            $search_key_arr = explode(' ', $search_key);
                            $search_key11 = trim($search_key_arr[0]);
                            $search_key22 = trim($search_key_arr[1]);
                            $search_sql = "((contents_title like '%$search_key11%' and contents_title like '%$search_key22%') or (contents_desc like '%$search_key11%' and contents_desc like '%$search_key22%') ";
                        } else {
                            $search_sql = "(contents_title like '%$search_key%' or contents_desc like '%$search_key%' ";
                        }
                    }
                    $search_sql .= ") ";
                } else {
                    $search_sql = "1=1";
                }
                if ($cur_win == "we_story") {
                    $show_counts = get_search_key('wecon_show_count');
                    $counts_arr = explode(",", $show_counts);
                    $limit_count = $counts_arr[0];
                    if ($_GET['key1'] == 3) { //콜이야
                        $limit_count = $counts_arr[1];
                    } else if ($_GET['key1'] == 4) { //굿마켓
                        $limit_count = $counts_arr[2];
                    }

                    $con_sql = "select max(idx) from " . $content_table_name;
                    $con_result = mysqli_query($self_con,$con_sql);
                    $con_row = mysqli_fetch_array($con_result);
                    $recent_idx = $con_row[0] - $limit_count;
                    if ($_GET['key1'] == 4 && $_GET['key4'] == 3)
                        $search_sql .= "";
                    else
                        $search_sql .= " and c.idx > $recent_idx";
                }

                $w_page = $_GET['w_page'];
                if (!$w_page)
                    $w_page = 1;
                if (!$cur_win || $cur_win == "my_info") {
                    $cont_id_array_ = array();
                    $total_count_ = 0;
                    //공유받은 명함은 공유한 사람의 프로필 콘텐츠가 보여야 해요// comment
                    $w_offset = $contents_count_per_page * ($w_page - 1); //페이지 내 첫 콘텐츠 offset
                    if ($w_offset < 0) {
                        $w_offset = 0;
                    }
                    if ($search_key)
                        $sql8 = "select count(idx) from " . $content_table_name . " WHERE group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' and ($search_sql) ORDER BY contents_order desc";
                    else if ($gwc_table) {
                        $sql8 = "select count(idx) from Gn_Iam_Contents_Gwc WHERE card_idx = {$cur_card['idx']} and public_display='Y' and " . $search_sql;
                    } else {
                        $sql8 = "select count(idx) from Gn_Iam_Con_Card WHERE card_idx = '{$cur_card['idx']}'";
                    }

                    $result_cnt = mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
                    $total_row = mysqli_fetch_array($result_cnt);
                    $cont_count = $total_row[0];

                    if ($cont_count > 0 && !$gwc_table) {
                        $sql8_ = "select count(idx) from Gn_Iam_Contents_Gwc WHERE card_idx = {$cur_card['idx']} and public_display='Y' and " . $search_sql;
                        $result_cnt_ = mysqli_query($self_con,$sql8_) or die(mysqli_error($self_con));
                        $total_row_ = mysqli_fetch_array($result_cnt_);
                        $total_count_ = $total_row_[0];

                        $f_sql_ = str_replace("count(idx)", "idx", $sql8_);
                        //$f_sql .= " group by c.card_idx $order_sql limit 0,300";
                        $f_sql_ .= $order_sql;
                        $f_sql_ = "select * from ($f_sql_) as tt limit 0,300";
                        $f_res_ = mysqli_query($self_con,$f_sql_);
                        while ($f_row_ = mysqli_fetch_array($f_res_)) {
                            array_push($cont_id_array_, $f_row_[0]);
                        }
                    }
                    $cont_id_array_ = array_unique($cont_id_array_);
                    $cont_count += count($cont_id_array_);

                    if ($cur_card['cont_order_type'] == 1)
                        $cont_order_type = 'contents_order desc';
                    else if ($cur_card['cont_order_type'] == 2)
                        $cont_order_type = 'req_data desc';
                    else if ($cur_card['cont_order_type'] == 3)
                        $cont_order_type = 'wm_date desc';
                    else if ($cur_card['cont_order_type'] == 4)
                        $cont_order_type = 'contents_title';
                    if ($search_key)
                        $sql8 = "select * from " . $content_table_name . " WHERE group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' and ($search_sql) ORDER BY " . $cont_order_type;
                    else if ($gwc_table) {
                        $sql8 = "select ct.* from Gn_Iam_Contents_Gwc ct WHERE ct.card_idx = {$cur_card['idx']} and public_display='Y' and " . $search_sql . " ORDER BY " . $cont_order_type;
                    } else
                        $sql8 = "select ct.* from Gn_Iam_Contents ct INNER JOIN Gn_Iam_Con_Card cc on cc.cont_idx = ct.idx WHERE cc.card_idx = {$cur_card['idx']} and " . $search_sql . " ORDER BY " . $cont_order_type;

                    if ($contents_count_per_page * ($w_page - 1) < count($cont_id_array_)) {
                        $temp_cont_arr = array_slice($cont_id_array_, $contents_count_per_page * ($w_page - 1), $contents_count_per_page);
                        foreach ($temp_cont_arr as $idx) {
                            $sort_sql = "select * from Gn_Iam_Contents_Gwc where idx = $idx";
                            $sort_res = mysqli_query($self_con,$sort_sql);
                            $sort_row = mysqli_fetch_array($sort_res);
                            array_push($cont_array, $sort_row);
                        }
                        $w_offset = 0; //페이지 내 첫 콘텐츠 offset
                        $contents_count_per_page = $contents_count_per_page * $w_page - count($cont_id_array_);
                        if ($contents_count_per_page < 0)
                            $contents_count_per_page = 0;
                    } else {
                        $w_offset = $contents_count_per_page * ($w_page - 1) - count($cont_id_array_);
                    }
                    $sql8 .= " limit $contents_count_per_page offset " . $w_offset;
                } else if ($cur_win == "my_story") {
                    if ($_SESSION['iam_member_id'] == "") {
                        $w_offset = $contents_count_per_page * ($w_page - 1); //페이지 내 첫 콘텐츠 offset
                        if ($w_offset < 0) {
                            $w_offset = 0;
                        }
                        $sql8 = "select count(idx) from " . $content_table_name . " WHERE card_idx={$cur_card['idx']} and $search_sql ";
                        $result_cnt = mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
                        $total_row = mysqli_fetch_array($result_cnt);
                        $cont_count = $total_row[0];
                        $sql8 = "select * from " . $content_table_name . " use index(card_idx) WHERE card_idx={$cur_card['idx']} and $search_sql ORDER BY contents_order desc";
                        $sql8 .= " limit $contents_count_per_page offset " . $w_offset;
                    } else {
                        $w_offset = $contents_count_per_page * ($w_page - 1); //페이지 내 첫 콘텐츠 offset
                        if ($w_offset < 0) {
                            $w_offset = 0;
                        }
                        $sql8 = "select count(idx) from " . $content_table_name . " WHERE group_id is NULL and (mem_id = '$card_owner' or contents_share_text like '%$card_owner%')  and $search_sql order by idx desc, up_data desc";
                        $result_cnt = mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
                        $total_row = mysqli_fetch_array($result_cnt);
                        $cont_count = $total_row[0];
                        $sql8 = "select * from " . $content_table_name . " WHERE group_id is NULL and (mem_id = '$card_owner' or contents_share_text like '%$card_owner%')  and $search_sql order by idx desc, up_data desc";
                        $sql8 .= " limit $contents_count_per_page offset " . $w_offset;
                    }
                } else if ($cur_win == "shared_receive" &&  $_SESSION['iam_member_id'] != "") {
                    $w_offset = $contents_count_per_page * ($w_page - 1); //페이지 내 첫 콘텐츠 offset
                    if ($w_offset < 0) {
                        $w_offset = 0;
                    }
                    $sql8 = "select count(idx) from Gn_Iam_Contents WHERE contents_share_text like '%{$_SESSION['iam_member_id']}%'  and $search_sql order by idx desc, up_data desc";
                    $result_cnt = mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
                    $total_row = mysqli_fetch_array($result_cnt);
                    $cont_count = $total_row[0];
                    $sql8 = "select * from Gn_Iam_Contents WHERE contents_share_text like '%{$_SESSION['iam_member_id']}%'  and $search_sql order by idx desc, up_data desc";
                    $sql8 .= " limit $contents_count_per_page offset " . $w_offset;
                } else if ($cur_win == "shared_send" &&  $_SESSION['iam_member_id'] != "") {
                    $w_offset = $contents_count_per_page * ($w_page - 1); //페이지 내 첫 콘텐츠 offset
                    if ($w_offset < 0) {
                        $w_offset = 0;
                    }
                    $sql8 = "select count(idx) from Gn_Iam_Contents WHERE mem_id = '{$_SESSION['iam_member_id']}' and contents_share_text != ''  and $search_sql order by idx desc, up_data desc";
                    $result_cnt = mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
                    $total_row = mysqli_fetch_array($result_cnt);
                    $cont_count = $total_row[0];
                    $sql8 = "select * from Gn_Iam_Contents WHERE mem_id = '{$_SESSION['iam_member_id']}' and contents_share_text != ''  and $search_sql order by idx desc, up_data desc";
                    $sql8 .= " limit $contents_count_per_page offset " . $w_offset;
                } else if ($cur_win == "unread_post" &&  $_SESSION['iam_member_id'] != "") {
                    $w_offset = $contents_count_per_page * ($w_page - 1);
                    if ($w_offset < 0) {
                        $w_offset = 0;
                    }
                    $sql8 = "select count(idx) from Gn_Iam_Contents c inner join  Gn_Iam_Post p on c.idx = p.content_idx WHERE c.mem_id = '{$_SESSION['iam_member_id']}' and p.status = 'N'  and $search_sql GROUP BY c.idx";
                    $result_cnt = mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
                    $total_row = mysqli_fetch_array($result_cnt);
                    $cont_count = $total_row[0];
                    $sql8 = "select c.*,p.id,p.content_idx,p.content,p.reg_date,p.status,p.lock_status from Gn_Iam_Contents c inner join  Gn_Iam_Post p on c.idx = p.content_idx WHERE c.mem_id = '{$_SESSION['iam_member_id']}' and p.status = 'N'  and $search_sql GROUP BY c.idx ORDER BY reg_date desc ";
                    $sql8 .= " limit $contents_count_per_page offset " . $w_offset;
                } else if ($cur_win == "iam_mall") {
                } else if ($cur_win == "we_story") {
                    $sql = "select block_user,block_contents from Gn_Iam_Info where mem_id = '{$_SESSION['iam_member_id']}'";
                    $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                    $row_iam_info = mysqli_fetch_array($result);

                    if ($row_iam_info['block_contents']) {
                        $block_contents_sql = " and c.idx not in ({$row_iam_info['block_contents']}) ";
                    } else {
                        $block_contents_sql = "";
                    }
                    if ($row_iam_info['block_user']) {
                        $b_arr = explode(',', $row_iam_info['block_user']);
                        $b_str = "('" . implode("','", $b_arr) . "')";
                        $block_user_sql = " and c.mem_id not in $b_str ";
                    } else {
                        $block_user_sql = "";
                    }
                    if ($_GET['sort'] == 11) { //게시일짜
                        $order_sql = " order by c.idx desc";
                    } elseif ($_GET['sort'] == 12) { //조회수
                        $order_sql = " ORDER BY c.contents_temp desc";
                    } elseif ($_GET['sort'] == 13) { //좋아요
                        $order_sql = " ORDER BY c.contents_like desc";
                    } elseif ($_GET['sort_key3'] == 1) { //할인율
                        $order_sql = " ORDER BY reduce_val desc";
                    } elseif ($_GET['sort_key3'] == 2) { //저가순
                        $order_sql = " ORDER BY contents_sell_price asc";
                    } elseif ($_GET['sort_key3'] == 3) { //고가순
                        $order_sql = " ORDER BY contents_sell_price desc";
                    } else {
                        $order_sql = " order by c.up_data desc,c.idx desc";
                    }

                    if ($_GET['sort'] == 1) { //1시간전
                        $search_sql .= " and c.req_data >= ADDTIME(now(), '-1:0:0') ";
                    } else if ($_GET['sort'] == 2) { //오늘
                        $search_sql .= " and c.req_data >= DATE(now()) ";
                    } else if ($_GET['sort'] == 3) { //이번주
                        $search_sql .= " and WEEKOFYEAR(c.req_data) = WEEKOFYEAR(now()) and YEAR(c.req_data) = YEAR(now()) ";
                    } else if ($_GET['sort'] == 4) { //이번달
                        $search_sql .= " and MONTH(c.req_data) = MONTH(now()) and YEAR(c.req_data) = YEAR(now()) ";
                    } else if ($_GET['sort'] == 5) { //올해
                        $search_sql .= " and YEAR(c.req_data) = YEAR(now()) ";
                    }

                    if ($_GET['key2'] != 0) {
                        $k = $interest_array[$_GET['key2'] - 1];
                        $search_sql .= " and (c.contents_title like '%$k%' or c.contents_desc like '%$k%')";
                    }
                    if ($_GET['key1'] == 1) { //소속콘
                        $sql8 = "select count(c.idx) from Gn_Iam_Contents c use index(idx) INNER  JOIN  Gn_Member m on c.mem_id=m.mem_id 
                            where m.site_iam =  '$bunyang_site' and contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
                    } elseif ($_GET['key1'] == 2) { //영상콘
                        $sql8 = "select count(c.idx) from Gn_Iam_Contents c use index(idx) 
                            where contents_type = 2 and contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
                    } elseif ($_GET['key1'] == 3) { //콜이야
                        if ($_GET['key3'] == 3) {
                            $sql8 = "select count(c.idx) from Gn_Iam_Contents c use index(card_idx) where card_idx='168534' and $search_sql $block_contents_sql $block_user_sql";
                        } else {
                            if (isset($_GET['key3']) && $_GET['key3'] != 0 && (isset($_GET['sort_key3']))) { //배송,방문=>적립,기본
                                if (isset($_GET['loc_name']) && $_GET['loc_name'] != '') { //방문=>지역
                                    $search_sql_loc = " and d.card_addr like '%{$_GET['loc_name']}%'";
                                    $sql8 = "select count(c.idx) from Gn_Iam_Contents c use index(idx) inner join Gn_Iam_Name_Card d on c.card_idx=d.idx where c.ai_map_gmarket=" . $_GET['key3'] . " and c.contents_type = 3 and c.contents_westory_display = 'Y' and c.public_display = 'Y' and $search_sql $search_sql_loc $block_contents_sql $block_user_sql";
                                } else {
                                    $sql8 = "select count(c.idx) from Gn_Iam_Contents c use index(idx) where c.ai_map_gmarket=" . $_GET['key3'] . " and c.contents_type = 3 and c.contents_westory_display = 'Y' and c.public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
                                }
                            } else {
                                if ($_GET['sort_key3'] == 1) { //전체=>적립
                                    $sql8 = "select count(c.idx) from Gn_Iam_Contents c use index(idx) where c.ai_map_gmarket!=3 and contents_type = 3 and contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
                                } else { //전체=>기본
                                    $sql8 = "select count(c.idx) from Gn_Iam_Contents c use index(idx) where c.ai_map_gmarket!=3 and contents_type = 3 and contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
                                }
                            }
                        }
                    } elseif ($_GET['key1'] == 4) { //굿마켓
                        $public_str = "public_display = 'Y'";
                        if ($_GET['cate_prod'] != '') {
                            if ($_GET['iamstore'] == "Y") {
                                $search_sql .= " and card_idx='{$_GET['cate_prod']}'";
                            } else {
                                $search_sql .= " and card_idx='{$_GET['cate_prod']}' and send_provide_price!=0";
                            }
                            $public_str = "(public_display = 'Y' or public_display='N')";
                        }
                        if ($_GET['iamstore'] == "Y") {
                            $search_sql .= " and mem_id in (" . $provider_arr . ") and send_provide_price!=0 and ori_store_prod_idx=0";
                        } else if ($_GET['iamstore'] == "C") {
                            $search_sql .= " and mem_id='{$_SESSION['iam_member_id']}' and ori_store_prod_idx!=0";
                        }
                        if ($_GET['key4'] == 3) {
                            $sql8 = "select count(c.idx) from " . $content_table_name . " c where card_idx='934328'";
                        } else {
                            $sql8 = "select count(c.idx) from " . $content_table_name . " c use index(idx) where contents_type = 3 and c.gwc_con_state=" . $_GET['key4'] . " and contents_westory_display = 'Y' and $public_str and $search_sql $block_contents_sql $block_user_sql";
                        }
                    } elseif ($_GET['key1'] >= 5) { //기타콘
                        $k = $rec_array[$_GET['key1'] - 5];
                        $search_sql .= " and (contents_title like '%$k%' or contents_desc like '%$k%')";
                        $sql8 = "select count(c.idx) from Gn_Iam_Contents c use index(idx) where contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
                    } else { //공개된 콘텐츠 다 현시
                        $search_sql .= " and gwc_con_state=0";
                        $sql8 = "select count(c.idx) from Gn_Iam_Contents c use index(idx) where contents_westory_display = 'Y' and public_display = 'Y' and contents_title!='방문자리뷰' and $search_sql $block_contents_sql $block_user_sql";
                    }
                    $sql8 .= " and group_display = 'Y' ";
                    // middle log
                    $logs->add_log("카운팅 하기 전 $sql8");

                    $result_cnt = mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
                    $total_row = mysqli_fetch_array($result_cnt);
                    $cont_count = $total_row[0];
                    // middle log
                    $logs->add_log("카운팅 갯수 : $cont_count");
                    //echo $sql8;
                    $cont_id_array = array();
                    if (!$search_key && $_GET['key1'] != 4) {
                        if ($cont_count > 0) {
                            $f_sql = str_replace("count(c.idx)", "c.idx", $sql8);
                            //$f_sql .= " group by c.card_idx $order_sql limit 0,300";
                            $f_sql .= " group by c.card_idx $order_sql";
                            $f_sql = "select * from ($f_sql) as tt limit 0,300";
                            $f_res = mysqli_query($self_con,$f_sql);
                            while ($f_row = mysqli_fetch_array($f_res)) {
                                array_push($cont_id_array, $f_row[0]);
                            }
                        }

                        $cont_id_array = array_unique($cont_id_array);
                        $cont_count += count($cont_id_array);
                        // middle log
                        $logs->add_log("노출적용 포함갯수=>" . $cont_count);
                    }

                    if ($_GET['key1'] == 1) { //소속콘
                        $sql8 = "select c.* from Gn_Iam_Contents c use index(idx) INNER  JOIN  Gn_Member m on c.mem_id=m.mem_id where m.site_iam =  '$bunyang_site' and contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
                    } elseif ($_GET['key1'] == 2) { //영상콘
                        $sql8 = "select c.* from Gn_Iam_Contents c use index(idx) where contents_type = 2 and contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
                    } elseif ($_GET['key1'] == 3) { //콜이야
                        if ($_GET['key3'] == 3) {
                            $sql8 = "select c.* from Gn_Iam_Contents c use index(card_idx) where card_idx='168534' and $search_sql $block_contents_sql $block_user_sql";
                        } else {
                            if (isset($_GET['key3']) && $_GET['key3'] != 0 && ($_GET['sort_key3'] == 1 || $_GET['sort_key3'] == 0)) { //배송,방문=>적립,기본
                                if (isset($_GET['loc_name']) && $_GET['loc_name'] != '') { //방문=>지역
                                    $search_sql_loc = " and d.card_addr like '%{$_GET['loc_name']}%'";
                                    $sql8 = "select c.* from Gn_Iam_Contents c use index(idx) inner join Gn_Iam_Name_Card d on c.card_idx=d.idx where c.ai_map_gmarket=" . $_GET['key3'] . " and c.contents_type = 3 and c.contents_westory_display = 'Y' and c.public_display = 'Y' and $search_sql $search_sql_loc $block_contents_sql $block_user_sql";
                                } else {
                                    $sql8 = "select c.* from Gn_Iam_Contents c use index(idx) where c.ai_map_gmarket=" . $_GET['key3'] . " and c.contents_type = 3 and c.contents_westory_display = 'Y' and c.public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
                                }
                            } else {
                                if ($_GET['sort_key3'] == 1) { //전체=>적립
                                    $sql8 = "select c.* from Gn_Iam_Contents c use index(idx) where c.ai_map_gmarket!=3 and contents_type = 3 and contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
                                } else { //전체=>기본
                                    $sql8 = "select c.* from Gn_Iam_Contents c use index(idx) where c.ai_map_gmarket!=3 and contents_type = 3 and contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
                                }
                            }
                        }
                    } elseif ($_GET['key1'] == 4) { //굿마켓
                        if ($_GET['key4'] == 3) {
                            $sql8 = "select * from " . $content_table_name . " c where card_idx='934328'";
                        } else {
                            if ($_GET['sort_key3']) {
                                $sql8 = "select * from " . $content_table_name . " c use index(idx_2) where contents_type = 3 and c.gwc_con_state=" . $_GET['key4'] . " and contents_westory_display = 'Y' and $public_str and $search_sql $block_contents_sql $block_user_sql";
                            } else {
                                $sql8 = "select c.* from " . $content_table_name . " c use index(idx_2) where contents_type = 3 and c.gwc_con_state=" . $_GET['key4'] . " and contents_westory_display = 'Y' and $public_str and $search_sql $block_contents_sql $block_user_sql";
                            }
                        }
                    } elseif ($_GET['key1'] >= 5) {
                        $k = $rec_array[$_GET['key1'] - 5];
                        $search_sql .= " and (contents_title like '%$k%' or contents_desc like '%$k%')";
                        $sql8 = "select c.* from Gn_Iam_Contents c use index(idx) where contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
                    } else { //공개된 콘텐츠 다 현시
                        $sql8 = "select c.* from Gn_Iam_Contents c use index(idx) where contents_westory_display = 'Y' and public_display = 'Y' and contents_title!='방문자리뷰' and $search_sql $block_contents_sql $block_user_sql";
                    }
                    $sql8 .= " and group_display = 'Y' ";
                    $sql8 .= $order_sql;
                    //echo $sql8;
                    if ($contents_count_per_page * ($w_page - 1) < count($cont_id_array)) {
                        $temp_cont_arr = array_slice($cont_id_array, $contents_count_per_page * ($w_page - 1), $contents_count_per_page);
                        foreach ($temp_cont_arr as $idx) {
                            $sort_sql = "select * from Gn_Iam_Contents where idx = $idx";
                            $sort_res = mysqli_query($self_con,$sort_sql);
                            $sort_row = mysqli_fetch_array($sort_res);
                            array_push($cont_array, $sort_row);
                        }
                        $w_offset = 0; //페이지 내 첫 콘텐츠 offset
                        $contents_count_per_page = $contents_count_per_page * $w_page - count($cont_id_array);
                        if ($contents_count_per_page < 0)
                            $contents_count_per_page = 0;
                    } else {
                        $w_offset = $contents_count_per_page * ($w_page - 1) - count($cont_id_array);
                    }

                    $sql8 .= " limit $contents_count_per_page offset " . $w_offset;
                    ///
                    // middle log
                    $logs->add_log("쿼리완료");
                    ////
                } else if ($cur_win == "best_sample") {
                } else if ($cur_win == "sample") {
                } else if ($cur_win == "recent_sample") {
                } else if ($cur_win == "group-con") {
                    if ($gkind != "recommend" && $gkind != "mygroup" && $gkind != "search" && $gkind != "search_con") {
                        $w_offset = $contents_count_per_page * ($w_page - 1); //페이지 내 첫 콘텐츠 offset
                        if ($w_offset < 0) {
                            $w_offset = 0;
                        }
                        //$sql8="select count(idx) from Gn_Iam_Contents WHERE card_short_url like '%$group_card_url%' and group_display = 'Y' and $search_sql";
                        $sql8 = "select count(ct.idx) from Gn_Iam_Contents ct INNER JOIN Gn_Iam_Con_Card cc on cc.cont_idx = ct.idx WHERE cc.card_idx = {$cur_card['idx']} and group_display = 'Y' and $search_sql";
                        $result_cnt = mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
                        $total_row = mysqli_fetch_array($result_cnt);
                        $cont_count = $total_row[0];
                        //$sql8="select * from Gn_Iam_Contents use index(idx) WHERE card_short_url like '%$group_card_url%' and group_display = 'Y' and ".$search_sql." ORDER BY group_fix desc,contents_order desc";
                        $sql8 = "select ct.* from Gn_Iam_Contents ct INNER JOIN Gn_Iam_Con_Card cc on cc.cont_idx = ct.idx WHERE cc.card_idx = {$cur_card['idx']}  and group_display = 'Y' and $search_sql ORDER BY group_fix desc,contents_order desc";
                        $sql8 .= " limit $contents_count_per_page offset " . $w_offset;
                    } else if ($gkind == "recommend") {
                        $w_offset = $contents_count_per_page * ($w_page - 1); //페이지 내 첫 콘텐츠 offset
                        if ($w_offset < 0) {
                            $w_offset = 0;
                        }
                        if ($other_group != "")
                            $sql8 = "select count(idx) from Gn_Iam_Contents WHERE group_id is not NULL and group_id > 0 and group_id not in ($other_group) and group_display = 'Y' and public_display = 'Y' and $search_sql ORDER BY contents_order desc";
                        else
                            $sql8 = "select count(idx) from Gn_Iam_Contents WHERE group_id is not NULL and group_id > 0 and group_display = 'Y' and public_display = 'Y' and $search_sql ORDER BY contents_order desc";
                        $result_cnt = mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
                        $total_row = mysqli_fetch_array($result_cnt);
                        $cont_count = $total_row[0];
                        if ($other_group != "")
                            $sql8 = "select * from Gn_Iam_Contents WHERE group_id is not NULL and group_id > 0 and group_id not in ($other_group) and group_display = 'Y' and public_display = 'Y' and $search_sql order by idx desc";
                        else
                            $sql8 = "select * from Gn_Iam_Contents WHERE group_id is not NULL and group_id > 0 and group_display = 'Y' and public_display = 'Y' and $search_sql order by idx desc";
                        $sql8 .= " limit $contents_count_per_page offset " . $w_offset;
                    } else if ($gkind == "search_con") {
                        $w_offset = $contents_count_per_page * ($w_page - 1); //페이지 내 첫 콘텐츠 offset
                        if ($w_offset < 0) {
                            $w_offset = 0;
                        }
                        $sql8 = "select count(idx) from Gn_Iam_Contents WHERE group_id is not NULL and group_id > 0 and group_display = 'Y' and public_display = 'Y' and $search_sql ORDER BY contents_order desc";
                        $result_cnt = mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
                        $total_row = mysqli_fetch_array($result_cnt);
                        $cont_count = $total_row[0];
                        $sql8 = "select * from Gn_Iam_Contents WHERE group_id is not NULL and group_id > 0 and group_display = 'Y' and public_display = 'Y' and $search_sql ORDER BY contents_order desc";
                        $sql8 .= " limit $contents_count_per_page offset " . $w_offset;
                    }
                } else if ($cur_win == "unread_notice" &&  $_SESSION['iam_member_id'] != "") {
                    $w_offset = $contents_count_per_page * ($w_page - 1); //페이지 내 첫 콘텐츠 offset
                    if ($w_offset < 0) {
                        $w_offset = 0;
                    }
                    $sql_recv_notice = "select * from Gn_Item_Pay_Result WHERE buyer_id = '{$_SESSION['iam_member_id']}' and type='noticerecv' and point_val=3 ORDER BY pay_date desc";
                    if (isset($_GET['box']) && $_GET['box'] == "send") {
                        $sql_recv_notice = "select * from Gn_Item_Pay_Result WHERE buyer_id = '{$_SESSION['iam_member_id']}' and type='noticesend' and point_val=3 ORDER BY pay_date desc";
                    }
                    $result_notice = mysqli_query($self_con,$sql_recv_notice) or die(mysqli_error($self_con));
                    $notice_count = mysqli_num_rows($result_notice);
                    $sql_recv_notice .= " limit $contents_count_per_page offset " . $w_offset;
                }
                $share_recv_count = $r_row['cnt'];
                $share_send_count = $s_row['cnt'];
                $share_post_count = $p_row['cnt'];
                $sell_service_con = $sell_row['cnt'];
                $notice = $notice_row['cnt'];

                $share_count = $r_row['cnt'] + $s_row['cnt'] + $p_row['cnt'] + $sell_row['cnt'] + $notice_row['cnt'];
                if ($_SESSION['iam_member_id'] == "") {
                    echo "<script>$('#share_count').hide();</script>";
                    echo "<script>$('#share_recv_count').hide();</script>";
                    echo "<script>$('#share_send_count').hide();</script>";
                    echo "<script>$('#share_post_count').hide();</script>";
                    echo "<script>$('#sell_service_contents').hide();</script>";
                    echo "<script>$('#notice').hide();</script>";
                } else {
                    if ($share_count == 0) {
                        echo "<script>$('#share_count').hide();</script>";
                        echo "<script>$('#share_recv_count').hide();</script>";
                        echo "<script>$('#share_send_count').hide();</script>";
                        echo "<script>$('#share_post_count').hide();</script>";
                        echo "<script>$('#sell_service_contents').hide();</script>";
                        echo "<script>$('#notice').hide();</script>";
                    } else {
                        echo "<script>  $('#share_count').html(" . $share_count . "); </script>";
                        echo "<script>  $('#share_count').show(); </script>";
                        if ($share_recv_count == 0) {
                            echo "<script>$('#share_recv_count').hide();</script>";
                        } else {
                            echo "<script>  $('#share_recv_count').html(" . $share_recv_count . "); </script>";
                            echo "<script>  $('#share_recv_count').show(); </script>";
                        }
                        if ($share_send_count == 0) {
                            echo "<script>$('#share_send_count').hide();</script>";
                        } else {
                            echo "<script>  $('#share_send_count').html(" . $share_send_count . "); </script>";
                            echo "<script>  $('#share_send_count').show(); </script>";
                        }
                        if ($share_post_count == 0) {
                            echo "<script>$('#share_post_count').hide();</script>";
                        } else {
                            echo "<script>  $('#share_post_count').html(" . $share_post_count . "); </script>";
                            echo "<script>  $('#share_post_count').show(); </script>";
                        }
                        if ($sell_service_con == 0) {
                            echo "<script>$('#sell_service_contents').hide();</script>";
                        } else {
                            echo "<script>  $('#sell_service_contents').html(" . $sell_service_con . "); </script>";
                            echo "<script>  $('#sell_service_contents').show(); </script>";
                        }
                        if ($notice == 0) {
                            echo "<script>$('#notice').hide();</script>";
                        } else {
                            echo "<script>  $('#notice').html(" . $notice . "); </script>";
                            echo "<script>  $('#notice').show(); </script>";
                        }
                    }
                }
                // middle log
                $logs->add_log("before sql8   $sql8");
                //echo $sql8;
                if ($sql8 && $cont_count > 0 && $contents_count_per_page > 0)
                    $result8 = mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
                if (strstr($cur_win, "sample")) { ?>
                    <div class="sample_main">
                    </div>
                <? } else if ($cur_win == "iam_mall") { ?>
                    <div id="div_mall" class="sample_main" style="padding:0px;background:white">
                    </div>
                <? } else if ($cur_win == "unread_notice" && $notice_count != 0) { ?>
                    <div class="content-item" style="display : block;">
                        <?
                        while ($notice_row = mysqli_fetch_array($result_notice)) {
                            $sender_id = $notice_row['pay_method'];
                            $sender_mem_card_info = "select * from Gn_Iam_Name_Card where mem_id='{$sender_id}' order by req_data asc limit 1";
                            $res_card_info = mysqli_query($self_con,$sender_mem_card_info);
                            $row_card_info = mysqli_fetch_array($res_card_info);

                            $sender_mem_info = "select mem_code, mem_name from Gn_Member where mem_id='{$sender_id}'";
                            $res_sender_info = mysqli_query($self_con,$sender_mem_info);
                            $row_sender_info = mysqli_fetch_array($res_sender_info);

                            $href = "/?" . $row_card_info['card_short_url'] . $row_sender_info['mem_code'];
                            $img = $row_card_info['main_img1'];
                        ?>
                            <div class="user-item">
                                <a href="<?= $href ?>" class="img-box" target="_blank">
                                    <div class="user-img">
                                        <img src="<?= $img ? cross_image($img) : '/iam/img/common/logo-2.png' ?>" alt="">
                                    </div>
                                </a>
                                <div class="wrap image_mode">
                                    <a href="<?= $href ?>" class="user-name"><?= $row_sender_info['mem_name'] ?></a>
                                    <a href="<?= $href ?>" class="date"><?= $notice_row['pay_date'] ?></a>
                                </div>
                                <div style="position:absolute; right:0px; padding:7px;">
                                    <a href="javascript:remove_recv_notice('<?= $notice_row['no'] ?>');" style="margin-left: -5px">
                                        <img src="/iam/img/main/icon-hide.png" width="35">
                                    </a>
                                </div>
                            </div>
                            <div class="desc-wrap" style="border-bottom: 1px solid #dddddd;display:block;padding: 15px;">
                                <div class="title" style="display: flex;flex-direction: column;justify-content: center;height:100%;border-radius: 10px;border: 2px solid lightgrey;">
                                    <h3 style="border-bottom:1px solid;"><?= $notice_row['seller_id'] ?></h3><br>
                                    <p><?= str_replace("\n", "<br>", $notice_row['message']) ?></p><br>
                                    <a href="<?= $notice_row['site'] ?>" target="_blank"><?= $notice_row['site'] ?></a>
                                </div>
                            </div>
                        <? } ?>
                    </div>
                <? } else if ($cur_win == "request_list") { ?>
                    <div class="content-item" style="display : block;">
                        <table style="width:100%;display:block;padding:20px;">
                            <colgroup>
                                <col width="10%">
                                <col width="10%">
                                <col width="20%">
                                <col width="15%">
                                <col width="15%">
                                <col width="15%">
                                <col width="10%">
                            </colgroup>
                            <thead style="width:100%;">
                                <tr>
                                    <td class="iam_table">아이디</td>
                                    <td class="iam_table">신청자이름</td>
                                    <td class="iam_table">이벤트제목</td>
                                    <td class="iam_table">신청폰번호</td>
                                    <td class="iam_table">발신폰번호</td>
                                    <td class="iam_table">신청일자</td>
                                    <td class="iam_table">신청취소</td>
                                </tr>
                            </thead>
                            <tbody id="request_list_table">

                            </tbody>
                        </table>
                    </div>
                    <? } else {
                    if (($cont_count == 0 && ($cur_win == "shared_send" || $cur_win == "shared_receive" || $cur_win == "unread_post")) || ($notice_count == 0 && $cur_win == "unread_notice")) {
                    ?>
                        <div class="content-item" id="contents_welcome">
                            <div class="desc-wrap">
                                <div class="title" style="display: flex;flex-direction: column;justify-content: center">
                                    <h3 style="text-align: center">환영합니다!</h3>
                                </div>
                            </div>
                            <div class="media-wrap">
                                <div class="media-inner" style="width:fit-content;margin:20px auto;min-height:30px">
                                    <img src="/iam/img/smile.png" class="contents_img">
                                </div>
                            </div>
                            <div style='border:none;'>
                                <div>
                                    <div class="desc-inner desc-text" style="height:auto;text-align:center">
                                        <img src="/iam/img/menu/icon_alarm_recvcon.png" style="height:15px">
                                        <label style="margin-left:10px;font-size:14px;">내가 받은 모든 콘텐츠를 확인해요.</label>
                                        <br>
                                        <img src="/iam/img/menu/icon_alarm_sendcon.png" style="height:15px">
                                        <label style="margin-left:10px;font-size:14px;">내가 보낸 모든 콘텐츠를 확인해요.</label>
                                        <br>
                                        <img src="/iam/img/menu/icon_alarm_post.png" style="height:15px">
                                        <label style="margin-left:10px;font-size:14px;">내게 받은 모든 댓글을 확인해요.&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        <br>
                                        <img src="/iam/img/menu/icon_alarm_notice.png" style="height:15px">
                                        <label style="margin-left:10px;font-size:14px;">내가 받은 공지사항을 확인해요.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                        <br>
                                        <img src="/iam/img/menu/icon_alarm_sold.png" style="height:15px">
                                        <label style="margin-left:10px;font-size:14px;">나의 쇼핑몰 판매정보를 확인해요.</label>
                                        <br>
                                        <label style="margin-top:20px;margin-bottom:20px;color:#99cc00;font-size:14px">새로운 알람이 오면 꼭 확인해주세요.</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?  } else if ($cont_count == 0 && $_GET['iamstore'] == 'C') { ?>
                        <div class="content-item" id="contents_welcome">
                            <div class="desc-wrap">
                                <div class="title" style="display: flex;flex-direction: column;justify-content: center;height:150px;">
                                    <h3 style="text-align: left">현재 IAMSTORE에서 가져온 상품이 없습니다.<br>회원님의 익월 소비금액이 있으면 지금 상품을 가져오시면 됩니다.<br>소비금액이 없으면 프로슈머 굿마켓의 미션달성을 위해 소비활동을 여기서 해주시기바랍니다.</h3>
                                </div>
                            </div>
                        </div>
                    <?  }
                    // middle log               
                    $logs->add_log("after sql8");
                    while ($contents_row = mysqli_fetch_array($result8)) {
                        array_push($cont_array, $contents_row);
                    }
                    foreach ($cont_array as $contents_row) {
                        if ($_SESSION['iam_member_id']) {
                            //로딩속도가 느려서 임시로 막아둔 상태
                            // echo "<script>save_load('".$_SESSION['iam_member_id']."','".$contents_row['idx']."',"."0);</script>";
                        }
                        //컨텐츠 조회수 카운팅(contents_temp1)
                        $sql_temp1 = "update {$content_table_name} set contents_temp1=contents_temp1+1 where idx='{$contents_row['idx']}'";
                        mysqli_query($self_con,$sql_temp1);

                        if (!$contents_row['contents_img'])
                            $content_images = null;
                        else
                            $content_images = explode(",", $contents_row['contents_img']);
                        for ($i = 0; $i < count($content_images); $i++) {
                            if (strstr($content_images[$i], "kiam")) {
                                $content_images[$i] = str_replace("http://kiam.kr", "", $content_images[$i]);
                                $content_images[$i] = str_replace("http://www.kiam.kr", "", $content_images[$i]);
                                //$content_images[$i] = $cdn_ssl . $content_images[$i];
                            }
                            if (!strstr($content_images[$i], "http") && $content_images[$i]) {
                                $content_images[$i] = $cdn_ssl . $content_images[$i];
                            }
                        }
                        //디폴트 아바타
                        if ($HTTP_HOST != "kiam.kr") //분양사사이트이면
                            $sub_domain = "http://" . $HTTP_HOST;
                        else
                            $sub_domain = "http://www.kiam.kr";
                        $sql = "select main_img1 from Gn_Iam_Service s where sub_domain = '$sub_domain'";
                        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                        $row = mysqli_fetch_array($result);
                        $default_avatar =  $row['main_img1'];

                        //westory 카드
                        $sql = "select * from Gn_Iam_Name_Card where idx = '{$contents_row['card_idx']}'";
                        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                        $westory_card = mysqli_fetch_array($result);
                        $sql = "select mem_code from Gn_Member where mem_id = '{$westory_card['mem_id']}'";
                        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                        $m_row = mysqli_fetch_array($result);
                        $m_code = $m_row['mem_code'];
                        //콘텐츠에 현시할 이름과 아바타
                        $contents_user_name = $cur_card['card_name'];
                        $contents_card_url = $request_short_url;
                        $contents_owner_id = $contents_row['mem_id'];
                        $contents_avatar = "";
                        if ($cur_card['main_img1']) {
                            $contents_avatar = $cur_card['main_img1'];
                        } else {
                            $contents_avatar = $default_avatar;
                        }

                        //위스토리에서 현시할 이름과 아바타
                        if ($cur_win == "we_story" || $cur_win == "shared_receive" || $cur_win == "shared_send") {
                            $contents_user_name = $westory_card['card_name'];
                            $contents_card_url = $westory_card['card_short_url'];
                            $card_locked = $westory_card['phone_display'];
                            if (!$_SESSION['iam_member_id'])
                                $card_owner_code = $m_code;
                            if ($westory_card['main_img1'])
                                $contents_avatar = $westory_card['main_img1'];
                            else
                                $contents_avatar = $default_avatar;
                        }
                        //카드가 위콘에서 공개인가 비공개인가?
                        if ($cur_win == "we_story") {
                            if ($card_locked == "N") {
                                echo "<!-- " . $contents_card_url . " 카드가 비공개    -->";
                                echo "<!-- idx = " . $contents_row['idx'] . " | time = " . $contents_row['req_data'] . "-->";
                                echo "<!-- -----------------------------    -->";
                                continue;
                            }
                            $except_keywords = trim($contents_row['except_keyword']);
                            if ($except_keywords != "") {
                                $except_count = 0;
                                $except_keywords = explode(",", $except_keywords);
                                for ($index = 0; $index < count($except_keywords); $index++) {
                                    $except_keyword = trim($except_keywords[$index]);
                                    $except_sql = "select count(*) from Gn_Iam_Contents where mem_id = '{$_SESSION['iam_member_id']}'" .
                                        " and (contents_title like '%$except_keyword%' or contents_desc like '%$except_keyword%')";
                                    $except_result = mysqli_query($self_con,$except_sql);
                                    $except_row = mysqli_fetch_array($except_result);
                                    $except_count += $except_row[0] * 1;

                                    $except_sql = "select count(*) from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}'" .
                                        " and card_keyword like '%$except_keyword%'";
                                    $except_result = mysqli_query($self_con,$except_sql);
                                    $except_row = mysqli_fetch_array($except_result);
                                    $except_count += $except_row[0] * 1;
                                }
                                if ($except_count > 0) {
                                    echo "<!-- " . $contents_card_url . " 카드가 제외    -->";
                                    echo "<!-- idx = " . $contents_row['idx'] . " | time = " . $contents_row['req_data'] . "-->";
                                    echo "<!-- -----------------------------    -->";
                                    continue;
                                }
                            }
                        }
                        $user_display = "";
                        if ($contents_row['contents_user_display'] == "N" || ($cur_win != "we_story" && $cur_win != "shared_receive" && $cur_win != "shared_send")) {
                            $user_display = "display:none";
                        }
                        $post_sql = "select SQL_CALC_FOUND_ROWS * from Gn_Iam_Post p where p.content_idx = '{$contents_row['idx']}' and p.lock_status = 'N' order by p.reg_date";
                        $post_res = mysqli_query($self_con,$post_sql);
                        $post_count =  mysqli_num_rows($post_res);
                    ?>
                        <div class="content_area">
                            <input type="hidden" id="<?= 'contents_display_' . $contents_row['idx'] ?>" value="<?= $contents_row['contents_display'] ?>">
                            <input type="hidden" id="<?= 'contents_user_display_' . $contents_row['idx'] ?>" value="<?= $contents_row['contents_user_display'] ?>">
                            <input type="hidden" id="<?= 'contents_type_display_' . $contents_row['idx'] ?>" value="<?= $contents_row['contents_type_display'] ?>">
                            <input type="hidden" id="<?= 'contents_footer_display_' . $contents_row['idx'] ?>" value="<?= $contents_row['contents_footer_display'] ?>">
                            <input type="hidden" id="<?= 'contents_media_type_' . $contents_row['idx'] ?>" value="<?= $contents_row['media_type'] ?>">
                            <input type="hidden" id="<?= 'contents_type_' . $contents_row['idx'] ?>" value="<?= $contents_row['contents_type'] ?>">
                            <input type="hidden" id="<?= 'contents_title_' . $contents_row['idx'] ?>" value="<?= $contents_row['contents_title'] ?>">
                            <input type="hidden" id="<?= 'contents_img_' . $contents_row['idx'] ?>" value="<?= $contents_row['contents_img'] ?>">
                            <input type="hidden" id="<?= 'contents_url_' . $contents_row['idx'] ?>" value="<?= $contents_row['contents_url'] ?>">
                            <input type="hidden" id="<?= 'contents_iframe_' . $contents_row['idx'] ?>" value="<?= str_replace('"', "'", $contents_row['contents_iframe']) ?>">
                            <input type="hidden" id="<?= 'contents_price_' . $contents_row['idx'] ?>" value="<?= $contents_row['contents_price'] ?>">
                            <input type="hidden" id="<?= 'contents_sell_price_' . $contents_row['idx'] ?>" value="<?= $contents_row['contents_sell_price'] ?>">
                            <input type="hidden" id="<?= 'contents_desc_' . $contents_row['idx'] ?>" value="<?= $contents_row['contents_desc'] ?>">
                            <input type="hidden" id="<?= 'except_keyword_' . $contents_row['idx'] ?>" value="<?= $except_keyword ?>">
                            <input type="hidden" id="<?= 'contents_share_text_' . $contents_row['idx'] ?>" value="<?= $contents_row['contents_share_text'] ?>">
                            <input type="hidden" id="<?= 'share_names_' . $contents_row['idx'] ?>" value="<?= $share_names ?>">
                            <input type="hidden" id="<?= 'contents_westory_display_' . $contents_row['idx'] ?>" value="<?= $contents_row['contents_westory_display'] ?>">
                            <input type="hidden" id="<?= 'card_short_url_' . $contents_row['idx'] ?>" value="<?= $contents_row['card_short_url'] ?>">
                            <input type="hidden" id="<?= 'westory_card_url_' . $contents_row['idx'] ?>" value="<?= $contents_row['westory_card_url'] ?>">
                            <input type="hidden" id="<?= 'req_data_' . $contents_row['idx'] ?>" value="<?= $contents_row['req_data'] ?>">
                            <input type="hidden" id="<?= 'card_owner_' . $contents_row['idx'] ?>" value="<?= $card_owner ?>">
                            <input type="hidden" id="<?= 'contents_user_name_' . $contents_row['idx'] ?>" value="<?= $contents_user_name ?>">
                            <input type="hidden" id="<?= 'post_display_' . $contents_row['idx'] ?>" value="<?= $westory_card['post_display'] ?>">
                            <input type="hidden" id="<?= 'contents_like_' . $contents_row['idx'] ?>" value="<?= number_format($contents_row['contents_like']) ?>">
                            <input type="hidden" id="<?= 'post_count_' . $contents_row['idx'] ?>" value="<?= $post_count ?>">
                            <input type="hidden" id="<?= 'open_type_' . $contents_row['idx'] ?>" value="<?= $contents_row['open_type']; ?>">
                            <input type="hidden" id="<?= 'gwc_con_state_' . $contents_row['idx'] ?>" value="<?= $contents_row['gwc_con_state']; ?>">
                            <input type="hidden" id="<?= 'product_code_' . $contents_row['idx'] ?>" value="<?= $contents_row['product_code']; ?>">
                            <input type="hidden" id="<?= 'product_model_name_' . $contents_row['idx'] ?>" value="<?= $contents_row['product_model_name']; ?>">
                            <input type="hidden" id="<?= 'product_seperate_' . $contents_row['idx'] ?>" value="<?= $contents_row['product_seperate']; ?>">
                            <input type="hidden" id="<?= 'prod_manufact_price_' . $contents_row['idx'] ?>" value="<?= $contents_row['prod_manufact_price']; ?>">
                            <input type="hidden" id="<?= 'send_salary_price_' . $contents_row['idx'] ?>" value="<?= $contents_row['send_salary_price']; ?>">
                            <input type="hidden" id="<?= 'send_provide_price_' . $contents_row['idx'] ?>" value="<?= $contents_row['send_provide_price']; ?>">
                            <input type="hidden" id="<?= 'landing_mode_' . $contents_row['idx'] ?>" value="<?= $contents_row['landing_mode']; ?>">
                            <? if ((int)$contents_row['contents_type'] == 3) {
                                $art_sql = "select mall_type from Gn_Iam_Mall use index(card_idx) where card_idx={$contents_row['idx']}";
                                $art_res = mysqli_query($self_con,$art_sql);
                                $art_row = mysqli_fetch_assoc($art_res);
                                if ($art_row['mall_type'] == "")
                                    $art_row['mall_type'] = 0;
                            ?>
                                <input type="hidden" id="<?= 'art_type_' . $contents_row['idx'] ?>" value="<?= $art_row['mall_type']; ?>">
                            <? } else { ?>
                                <input type="hidden" id="<?= 'art_type_' . $contents_row['idx'] ?>" value="0">
                            <? } ?>
                            <div class="content-item" id="contents_image">
                                <?
                                $pad_top = "";
                                if ($_GET['key3'] == 1) {
                                    $pad_top = "padding-bottom:20px;";
                                }
                                if ($_GET['key1'] == 4) {
                                    $pad_top = "padding:4px;";
                                }
                                ?>
                                <div class="user-item" style="<?= $user_display . $pad_top ?>">
                                    <a href="/?<?= strip_tags($contents_row['westory_card_url'] . $m_code) ?>" class="img-box" target="_blank">
                                        <div class="user-img" style="margin: 5px;width:32px;height:32px;">
                                            <img src="<?= $contents_avatar ? cross_image($contents_avatar) : '/iam/img/common/logo-2.png' ?>" alt="">
                                        </div>
                                    </a>
                                    <div class="wrap image_mode">
                                        <a href="/?<?= strip_tags($contents_row['westory_card_url'] . $m_code) ?>" class="user-name">
                                            <?= $contents_user_name ?>
                                        </a>
                                        <? if ($_GET['iamstore'] != 'N' || $_GET['cate_prod'] == '1268514') { ?>
                                            <a href="/?<?= strip_tags($contents_row['westory_card_url'] . $m_code) ?>" class="date">
                                                <?= get_date_time($contents_row['req_data']) ?>
                                            </a>
                                        <? } ?>
                                    </div>
                                    <div class="wrap pin_mode" style="display:none;">
                                        <? //if($contents_row['contents_title'] != "") { 
                                        ?>
                                        <!-- <a href="/?<?= strip_tags($contents_row['westory_card_url'] . $m_code) ?>" class="user-name pin-mode" style="text-overflow: ellipsis;white-space: nowrap;width:100%">
                                            <?= $contents_row['contents_title'] ?>
                                        </a> -->
                                        <? //}else{
                                        ?>
                                        <a href="/?<?= strip_tags($contents_row['westory_card_url'] . $m_code) ?>" class="user-name">
                                            <?= $contents_user_name ?>
                                        </a>
                                        <? //}
                                        ?>
                                        <? if ($_GET['iamstore'] != 'N') { ?>
                                            <a href="/?<?= strip_tags($contents_row['westory_card_url'] . $m_code) ?>" class="date">
                                                <?= get_date_time($contents_row['req_data']) ?>
                                            </a>
                                        <? } ?>
                                    </div>
                                    <?
                                    if ($_GET['key3'] == 1 || $chk_map_con == 1) {
                                        $card_addr = "select map_pos from Gn_Iam_Name_Card where idx='{$contents_row['card_idx']}'";
                                        $res_addr = mysqli_query($self_con,$card_addr);
                                        $row_addr = mysqli_fetch_array($res_addr);
                                        $card_map_pos = $row_addr[0];
                                    ?>
                                        <script>
                                            $(document).ready(function() {
                                                var window_width = $(window).width();
                                                if (window_width < 500) {
                                                    var phone_pos_lat = getCookie('phone_lat');
                                                    var phone_pos_lng = getCookie('phone_lng');
                                                    $("#pc_mode_<?= $contents_row['idx'] ?>").hide();
                                                    $("#mobile_mode_<?= $contents_row['idx'] ?>").show();

                                                    if (phone_pos_lat != '' && phone_pos_lng != '') {
                                                        recvPhonePos_show(phone_pos_lat, phone_pos_lng, <?= $contents_row['idx'] ?>, '<?= $card_map_pos ?>');
                                                    }
                                                }
                                            });
                                        </script>
                                        <div style="position: absolute;right: 10px;bottom: 0px;" id="pc_mode_<?= $contents_row['idx'] ?>">
                                            <a class="navermap" href="javascript:show_map_address(<?= $contents_row['card_idx'] ?>)">주소</a>&nbsp;|&nbsp;<a class="navermap" href="javascript:show_map_position(<?= $contents_row['card_idx'] ?>)">지도</a>&nbsp;|&nbsp;<a class="navermap" href="javascript:show_map_distance(<?= $contents_row['card_idx'] ?>)">거리</a>&nbsp;|&nbsp;<a class="navermap" href="javascript:show_map_busitime(<?= $contents_row['card_idx'] ?>)">영업시간</a>
                                        </div>
                                        <div style="position: absolute;right: 10px;bottom: 0px;" id="mobile_mode_<?= $contents_row['idx'] ?>" hidden>
                                            <a class="navermap" href="javascript:show_map_address(<?= $contents_row['card_idx'] ?>)">주소</a>&nbsp;|&nbsp;<a class="navermap" href="javascript:show_map_position(<?= $contents_row['card_idx'] ?>)" id="distance_map_<?= $contents_row['idx'] ?>"></a>&nbsp;|&nbsp;<a class="navermap" href="javascript:show_map_busitime(<?= $contents_row['card_idx'] ?>)">영업시간</a>
                                        </div>
                                    <? } ?>
                                    <? if ($_SESSION['iam_member_id'] != "" && ($_SESSION['iam_member_id'] != $contents_owner_id || ($_SESSION['iam_member_id'] == $contents_owner_id && $_SESSION['iam_member_id'] != 'iamstore' && $contents_row['provider_req_prod'] == "Y"))) { ?>
                                        <div class="dropdown" style="position: absolute; right: 10px; top: 8px;">
                                            <button class="btn-link dropdown-toggle westory_dropdown" type="button" data-toggle="dropdown">
                                                <img src="/iam/img/menu/icon_dot.png" style="height:24px">
                                            </button>
                                            <ul class="dropdown-menu comunity">
                                                <li><a onclick="window.open('/?<?= strip_tags($contents_row['westory_card_url']) ?>');">이 콘텐츠 게시자 보기</a></li>
                                                <li><a onclick="set_friend('<?= $westory_card['mem_id'] ?>','<?= $westory_card['card_name'] ?>','<?= $westory_card['card_short_url'] ?>','<?= $westory_card['idx'] ?>')">이 게시자와 프렌즈 하기</a></li>
                                                <li><a onclick="set_block_contents('<?= $contents_row['idx'] ?>')">이 콘텐츠 하나만 차단하기</a></li>
                                                <li><a onclick="set_block_user('<?= $westory_card['mem_id'] ?>','<?= $westory_card['card_short_url'] ?>')">이 게시자의 정보 차단하기</a></li>
                                                <? if ($contents_row['ai_map_gmarket'] != 3) { ?>
                                                    <li><a onclick="set_my_share_contents('<?= $contents_row['idx'] ?>')">이 콘텐츠 나에게 가져오기</a></li>
                                                <? } else if ($contents_row['ai_map_gmarket'] == 3 && $_GET['iamstore'] == "Y" && $gwc_mem) { ?>
                                                    <li><a onclick="set_my_share_contents('<?= $contents_row['idx'] ?>', 'gwc_prod')">내 계정으로 가져오기</a></li>
                                                <? } ?>
                                                <li><a onclick="set_report_contents('<?= $contents_row['idx'] ?>')">이 콘텐츠 신고하기</a></li>
                                                <li><a onclick="show_block_list('<?= $contents_row['idx'] ?>')">감추기 리스트 보기</a></li>
                                            </ul>
                                        </div>
                                    <? } ?>
                                    <? if ($_SESSION['iam_member_id'] != "" && $_SESSION['iam_member_id'] == $contents_owner_id && $_SESSION['iam_member_id'] != 'iamstore' && $contents_row['ai_map_gmarket'] == 3 && $contents_row['provider_req_prod'] == "N") { ?>
                                        <div class="dropdown" style="position: absolute; right: 10px; top: 8px;">
                                            <button class="btn-link dropdown-toggle westory_dropdown" type="button" data-toggle="dropdown">
                                                <img src="/iam/img/menu/icon_dot.png" style="height:24px">
                                            </button>
                                            <ul class="dropdown-menu comunity">
                                                <li><a onclick="set_my_share_contents('<?= $contents_row['idx'] ?>', 'gwc_prod_unset')">도매몰로 내보내기</a></li>
                                            </ul>
                                        </div>
                                    <? } ?>
                                    <? if ($_SESSION['iam_member_id'] == "") { ?>
                                        <div class="dropdown " style="position: absolute; right: 10px; top: 8px;">
                                            <button class="btn-link dropdown-toggle westory_dropdown" type="button" data-toggle="dropdown">
                                                <img src="/iam/img/menu/icon_dot.png" style="height:24px">
                                            </button>
                                            <ul class="dropdown-menu comunity">
                                                <li><a onclick="javascript:location.href='/iam/join.php'">나도 아이엠 만들고 싶어요</a></li>
                                                <li><a onclick="javascript:window.open('/?<?= strip_tags($contents_row['westory_card_url'] . $card_owner_code) ?>');">이 콘텐츠 게시자 보기</a></li>
                                                <li><a onclick="javascript:window.open('/?<?= $contents_row['westory_card_url'] . $card_owner_code ?>&cur_win=my_story')">더 많은 콘텐츠 보러가기</a></li>
                                                <li><a onclick="set_report_contents('<?= $contents_row['idx'] ?>')">이 콘텐츠 신고하기</a></li>
                                            </ul>
                                        </div>
                                    <?
                                    }
                                    $cursor++;
                                    ?>
                                </div>
                                <? if ($_GET['key1'] != 4) { //긋마켓상품이 아니면
                                ?>
                                    <div class="desc-wrap" style="border-bottom: 1px solid #dddddd;">
                                        <? if ($contents_row['contents_title'] != "") { ?>
                                            <div class="title service_title" style="display: flex;flex-direction: column;justify-content: center">
                                                <h3><?= $contents_row['contents_title'] ?></h3>
                                            </div>
                                        <?
                                        }
                                        if ((int)$contents_row['contents_type'] == 3) {
                                            $price_show1 = "적립금";
                                            $price_show2 = "판매가";
                                            $price_style = "";
                                            $sql_card = "select sale_cnt, sale_cnt_set, add_reduce_val from Gn_Iam_Name_Card where idx='{$contents_row['card_idx']}'";
                                            $res_card = mysqli_query($self_con,$sql_card);
                                            $row_card = mysqli_fetch_array($res_card); ?>
                                            <div class="desc is-product">
                                                <div class="desc-inner">
                                                    <div class="outer <?= $row_card['sale_cnt_set'] ?>">
                                                        <?
                                                        $buy_btn = "";
                                                        $contents_price_arr = explode("|", $contents_row['contents_price']);
                                                        $contents_row['contents_price'] = $contents_price_arr[0] * 1;
                                                        $contents_row['contents_download_price'] = $contents_price_arr[3] * 1;
                                                        if ($contents_row['contents_price'] > 0) {
                                                            if (!$row_card['sale_cnt_set']) {
                                                                $style_decor = "";
                                                                $state_end = "";
                                                                $discount = $contents_row['reduce_val']; //100 - ($contents_row['contents_sell_price'] / $contents_row['contents_price']) * 100;
                                                                $add_point = $contents_row['contents_price'] * ((int)$discount / 100);
                                                            } else {
                                                                if (!$row_card['sale_cnt']) {
                                                                    $style_decor = "text-decoration: line-through;";
                                                                    $state_end = "마감";
                                                                    $discount = $contents_row['reduce_val']; //100 - ($contents_row['contents_sell_price'] / $contents_row['contents_price']) * 100;
                                                                    $add_point = $contents_row['contents_price'] * (((int)$discount - $row_card['add_reduce_val']) / 100);
                                                                } else {
                                                                    $style_decor = "";
                                                                    $state_end = "적립";
                                                                    $discount = $contents_row['reduce_val']; //100 - ($contents_row['contents_sell_price'] / $contents_row['contents_price']) * 100;
                                                                    $add_point = $contents_row['contents_price'] * ((int)$discount / 100);
                                                                }
                                                            }
                                                            if ($cur_win == "my_info") {
                                                                $state_end = "";
                                                                if ($contents_row['gwc_con_state'] != 0) {
                                                                    $price_show1 = "최저가";
                                                                    $price_show2 = "정상가";
                                                                    $price_style = "text-decoration: line-through;";
                                                                    $add_point = $contents_row['contents_sell_price'];
                                                                    if ($_SESSION['iam_member_id'] != 'iamstore') {
                                                                        $buy_btn_con = "visibility: hidden;";
                                                                    } else {
                                                                        $buy_btn_con = "";
                                                                    }
                                                                }
                                                            }
                                                            if (($_GET['iamstore'] == "Y" && $gwc_pay_mem) || $_GET['iamstore'] == "C") {
                                                                $buy_btn_con = "visibility: hidden;";
                                                                $discount = 100 - ($contents_row['send_provide_price'] / $contents_row['contents_sell_price']) * 100;
                                                            }
                                                        ?>
                                                            <div class="price" style="width:230px;">
                                                                <span class="downer"><?= $price_show2 ?>:<span style="vertical-align: top;<?= $price_style ?>"><?= number_format($contents_row['contents_price']) ?></span>원</span>
                                                                <? if (($_GET['iamstore'] == "Y" && $gwc_pay_mem) || $_GET['iamstore'] == "C") { ?>
                                                                    <span class="downer" style="color:red;"><?= $price_show1 ?>:<?= number_format((int)$add_point) ?>원</span>
                                                                    <span class="downer" style="color:blue;">공급가:<?= number_format($contents_row['send_provide_price']) ?>원 <span style="vertical-align: top;color: red;margin-left: 10px;font-weight: bold;"><?= (int)$discount ?>%</span></span>
                                                                <? } else { ?>
                                                                    <span class="downer" style="color:red;"><?= $price_show1 ?>:<?= number_format((int)$add_point) ?>원 <span style="vertical-align: top;color: red;margin-left: 10px;font-weight: bold;"><?= (int)$discount ?>%</span></span>
                                                                <? } ?>
                                                            </div>
                                                            <span style="font-size: 15px;"><?= $state_end ?></span>
                                                        <? } else { ?>
                                                            <div class="price">
                                                                <span class="downer"><?= $price_show2 ?>:<span style="vertical-align: top;<?= $price_style ?>"><?= number_format($contents_row['contents_price']) ?></span>원 <span style="vertical-align: top;color: red;margin-left: 10px;font-weight: bold;">0%</span></span>
                                                                <span class="downer" style="color:red;"><?= $price_show1 ?>:<?= number_format($contents_row['contents_price']) ?>원</span>
                                                            </div>
                                                            <!-- <div class="percent">0%</div> -->
                                                        <? }
                                                        //}
                                                        ?>
                                                        <?
                                                        if ($cur_win == 'my_info' && $_SESSION['iam_member_id'] == 'iamstore') {
                                                            $buy_btn = "display:none;";
                                                        } ?>
                                                        <div class="order" style="<?= $buy_btn ?><?= $buy_btn_con ?>">
                                                            <? if ($_SESSION['iam_member_id']) {
                                                                $price_service = $contents_row['contents_sell_price'];
                                                                $contents_row['contents_title'] = str_replace('"', ' ', $contents_row['contents_title']);
                                                                $contents_row['contents_title'] = str_replace("'", ' ', $contents_row['contents_title']);
                                                                $name_service = $contents_row['contents_title'];
                                                                $sellerid_service = $contents_row['mem_id'];
                                                                $contents_url = $contents_row['contents_url'];
                                                                $card_price = $contents_row['contents_sell_price'] * 1 + $contents_row['send_salary_price'] * 1;
                                                                $pay_link = "/iam/pay_spgd.php?item_name=" . $contents_row['contents_title'] . '&item_price=' . $card_price . '&manager=' . $contents_row['mem_id'] . "&conidx=" . $contents_row['idx'] . "&sale_cnt=" . $row_card['sale_cnt'];
                                                            ?>
                                                                <div class="dropdown" style="float:right;width: 82px;">
                                                                    <a class="dropdown-toggle" data-toggle="dropdown" expanded="false" style="background:#99cc00;border-radius:10px;cursor:pointer;">구매</a>
                                                                    <ul class="dropdown-menu buy_servicecon" style="width: 82px;">
                                                                        <li>
                                                                            <a href="<?= $pay_link ?>" target="_blank" style="font-size: 12px;background-color:#99cc00;">카드결제</a>
                                                                        </li>
                                                                        <li>
                                                                            <a onclick="point_settle_modal(<?= $contents_row['contents_sell_price'] ?>, '<?= $contents_row['contents_title'] ?>', '<?= $contents_row['idx'] ?>', '<?= $contents_row['mem_id'] ?>', '<?= $row_card['sale_cnt'] ? $row_card['sale_cnt'] : '0' ?>', '<?= $contents_row['send_salary_price'] ? $contents_row['send_salary_price'] : '0' ?>')" style="font-size: 12px;background-color:#99cc00;">포인트결제</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            <? } else { ?>
                                                                <a href="<? echo '/iam/login.php?contents_idx=' . $contents_row['idx'] ?>" target="_self" style="background:#99cc00;border-radius:10px;">구매</a>
                                                            <? } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <? } ?>
                                        <div class="desc">
                                            <div class="desc-inner desc-text desc-inner-content">
                                                <p style="overflow-wrap: break-word;font-size: 14px;"><?= nl2br($contents_row['contents_desc']) ?></p>
                                            </div>
                                            <!--a class="arrow" style="color:#669933;font-weight:bold;cursor:pointer">
                                        [더 보기]
                                    </a-->
                                            <? if ($content_images == null) {
                                                if (!$_SESSION['iam_member_id'] || $cur_card['share_send_card'] != 0 || ($cur_win != "shared_receive" && $_SESSION['iam_member_id'] != $contents_row['mem_id'] && $_SESSION['iam_member_id'] != $group_manager)) { ?>
                                                    <a class="content-utils" style="cursor:pointer;position:absolute;bottom:5px;right:10px;z-index:10;" onclick="showSNSModal_byContents('<?= $contents_row['idx'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                        <img src="/iam/img/menu/icon_share_white.png" style="width:13px;height:16px;margin-left:3px;margin-top:-1px;">
                                                    </a>
                                                <? } else { ?>
                                                    <a onclick="show_contents_utils(<?= $contents_row['idx'] ?>);" class="content-utils" style="position: absolute;bottom:5px;right: 10px;z-index:10;" title="">
                                                        <!--i class="fa fa-pencil-square-o" aria-hidden="false"></i-->
                                                        <img src="/iam/img/menu/icon_utils_box.png" style="height:20px;">
                                                    </a>
                                                    <div class="utils-index" id="<?= 'utils_index_' . $contents_row['idx'] ?>" style="display:none;z-index:10;">
                                                        <?
                                                        if ($cur_win != "we_story" && $_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) { ?>
                                                            <a class="content-utils" onclick="contents_add('<?= $card_owner ?>','<?= $contents_row['contents_order'] ?>',<?= $my_first_card ?>);">
                                                                <img src="/iam/img/menu/icon_plus.png" style="width:20px;height:20px;">
                                                            </a>
                                                        <?  }
                                                        if ($_SESSION['iam_member_id'] && ($_SESSION['iam_member_id'] == $contents_row['mem_id'] || $_SESSION['iam_member_id'] == $group_manager)) { ?>
                                                            <a class="content-utils" onclick="contents_del('<?= $contents_row['idx'] ?>', '<?= $contents_row['ori_store_prod_idx'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                                <img src="/iam/img/menu/icon_minus.png" style="width:20px;height:20px;">
                                                            </a>
                                                            <? if (!$cur_win || $cur_win == "my_info") { ?>
                                                                <a class="content-utils" onclick="contents_range_down('<?= $contents_row['idx'] ?>', '<?= $contents_row['contents_order'] ?>','<?= $cur_card['card_short_url'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                                    <img src="/iam/img/menu/icon_arrow_down.png" style="width:20px;height:20px;">
                                                                </a>
                                                                <a class="content-utils" onclick="contents_range_up('<?= $contents_row['idx'] ?>', '<?= $contents_row['contents_order'] ?>','<?= $cur_card['card_short_url'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                                    <img src="/iam/img/menu/icon_arrow_up.png" style="width:20px;height:20px;">
                                                                </a>
                                                                <a class="content-utils" onclick="contents_range_down('<?= $contents_row['idx'] ?>', '<?= $contents_row['contents_order'] ?>','<?= $cur_card['card_short_url'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>','min');">
                                                                    <img src="/iam/img/menu/icon_arrow_end.png" style="width:20px;height:20px;">
                                                                </a>
                                                                <a class="content-utils" onclick="contents_range_up('<?= $contents_row['idx'] ?>', '<?= $contents_row['contents_order'] ?>','<?= $cur_card['card_short_url'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>','max');">
                                                                    <img src="/iam/img/menu/icon_arrow_start.png" style="width:20px;height:20px;">
                                                                </a>
                                                            <?  } ?>
                                                            <? if ($_SESSION['iam_member_id'] == $group_manager) { ?>
                                                                <a class="content-utils" onclick="contents_fix('<?= $contents_row['idx'] ?>');">
                                                                    <img src="<?= $contents_row['group_fix'] > 0 ? '/iam/img/main/icon-pin.png' : '/iam/img/main/icon-graypin.png' ?>" style="width:20px;height:20px;">
                                                                </a>
                                                            <? } ?>
                                                            <? if ($_SESSION['iam_member_id'] == $contents_row['mem_id']) {
                                                                if ($is_pay_version) { ?>
                                                                    <a class="content-utils" onclick="show_con_send('<?= $contents_row['idx'] ?>')">
                                                                        <img src="/iam/img/main/icon_con_send.png" style="width:20px;height:20px;">
                                                                    </a>
                                                        <? }
                                                            }
                                                        } ?>
                                                        <? if ($cur_win == "shared_receive") { ?>
                                                            <a class="content-utils" onclick="remove_shared_content('<?= $contents_row['idx'] ?>','<?= $_SESSION['iam_member_id'] ?>');">
                                                                <img src="/iam/img/main/icon-hide.png" style="width:20px;height:20px;">
                                                            </a>
                                                        <? } ?>
                                                        <a class="content-utils" onclick="showSNSModal_byContents('<?= $contents_row['idx'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                            <img src="/iam/img/menu/icon_share.png" style="width:20px;height:20px">
                                                        </a>
                                                        <? if ($_SESSION['iam_member_id'] && ($_SESSION['iam_member_id'] == $contents_row['mem_id'] || $_SESSION['iam_member_id'] == $group_manager)) { ?>
                                                            <? if ($_SESSION['iam_member_id'] == $contents_row['mem_id']) {
                                                                $share_ids = explode(",", $contents_row['contents_share_text']);
                                                                for ($index = 0; $index < count($share_ids); $index++) {
                                                                    $share_sql = "select mem_name from Gn_Member where mem_id = '$share_ids[$index]'";
                                                                    $share_result = mysqli_query($self_con,$share_sql);
                                                                    $share_row = mysqli_fetch_array($share_result);
                                                                    $share_names[$index] = htmlspecialchars($share_row['mem_name']);
                                                                }
                                                                $share_names = implode(",", $share_names);
                                                            ?>
                                                                <a class="content-utils" onclick="contents_edit('<?= $contents_row['idx'] ?>', '<?= $contents_row['ori_store_prod_idx'] ?>');">
                                                                    <img src="/iam/img/menu/icon_edit_white.png" style="width:20px;height:20px;">
                                                                </a>
                                                            <? } ?>
                                                        <? } ?>
                                                    </div>
                                            <? }
                                            } ?>
                                        </div>
                                    </div>
                                <? } ?>
                                <div class="media-wrap">
                                    <?
                                    $except_keyword = $contents_row['except_keyword'];
                                    $except_keyword = str_replace('"', urlencode('"'), $except_keyword);
                                    $except_keyword = str_replace("'", urlencode("\'"), $except_keyword);

                                    if ($_SESSION['iam_member_id'] == $card_owner) {
                                        if ($cur_win == "my_story") { ?>
                                            <span class="pull-left " style="position:absolute;left:10px;top:10px;"><?= $contents_row['req_data'] ?></span>
                                    <? }
                                    } ?>
                                    <div class="media-inner <?= $_GET['type'] ?>" style="overflow-y: hidden;<? if ($contents_row['contents_type'] == 1 && count($content_images) == 0) echo "min-height :30px;"; ?>">
                                        <? if ($content_images != null) {
                                            if (!$_SESSION['iam_member_id'] || $cur_card['share_send_card'] != 0 || ($cur_win != "shared_receive" && $_SESSION['iam_member_id'] != $contents_row['mem_id'] && $_SESSION['iam_member_id'] != $group_manager)) { ?>
                                                <a class="content-utils" style="cursor:pointer;position:absolute;top:5px;right:10px;z-index:10;" onclick="showSNSModal_byContents('<?= $contents_row['idx'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                    <img src="/iam/img/menu/icon_share_white.png" style="width:13px;height:16px;margin-left:0px;margin-top:-1px;">
                                                </a>
                                            <? } else { ?>
                                                <a onclick="show_contents_utils(<?= $contents_row['idx'] ?>);" class="content-utils" style="position: absolute;top:5px;right: 10px;z-index:10;" title="">
                                                    <!--i class="fa fa-pencil-square-o" aria-hidden="false"></i-->
                                                    <img src="/iam/img/menu/icon_utils_box.png" style="height:20px;">
                                                </a>
                                                <div class="utils-index" id="<?= 'utils_index_' . $contents_row['idx'] ?>" style="display:none;z-index:10;top:5px">
                                                    <?
                                                    if ($cur_win != "we_story" && $_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) { ?>
                                                        <a class="content-utils" onclick="contents_add('<?= $card_owner ?>','<?= $contents_row['contents_order'] ?>',<?= $my_first_card ?>);">
                                                            <img src="/iam/img/menu/icon_plus.png" style="width:20px;height:20px;">
                                                        </a>
                                                    <?  }
                                                    if ($_SESSION['iam_member_id'] && ($_SESSION['iam_member_id'] == $contents_row['mem_id'] || $_SESSION['iam_member_id'] == $group_manager)) { ?>
                                                        <a class="content-utils" onclick="contents_del('<?= $contents_row['idx'] ?>', '<?= $contents_row['ori_store_prod_idx'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                            <img src="/iam/img/menu/icon_minus.png" style="width:20px;height:20px;">
                                                        </a>
                                                        <? if (!$cur_win || $cur_win == "my_info") { ?>
                                                            <a class="content-utils" onclick="contents_range_down('<?= $contents_row['idx'] ?>', '<?= $contents_row['contents_order'] ?>','<?= $cur_card['card_short_url'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                                <img src="/iam/img/menu/icon_arrow_down.png" style="width:20px;height:20px;">
                                                            </a>
                                                            <a class="content-utils" onclick="contents_range_up('<?= $contents_row['idx'] ?>', '<?= $contents_row['contents_order'] ?>','<?= $cur_card['card_short_url'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                                <img src="/iam/img/menu/icon_arrow_up.png" style="width:20px;height:20px;">
                                                            </a>
                                                            <a class="content-utils" onclick="contents_range_down('<?= $contents_row['idx'] ?>', '<?= $contents_row['contents_order'] ?>','<?= $cur_card['card_short_url'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>','min');">
                                                                <img src="/iam/img/menu/icon_arrow_end.png" style="width:20px;height:20px;">
                                                            </a>
                                                            <a class="content-utils" onclick="contents_range_up('<?= $contents_row['idx'] ?>', '<?= $contents_row['contents_order'] ?>','<?= $cur_card['card_short_url'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>','max');">
                                                                <img src="/iam/img/menu/icon_arrow_start.png" style="width:20px;height:20px;">
                                                            </a>
                                                        <?  } ?>
                                                        <? if ($_SESSION['iam_member_id'] == $group_manager) { ?>
                                                            <a class="content-utils" onclick="contents_fix('<?= $contents_row['idx'] ?>');">
                                                                <img src="<?= $contents_row['group_fix'] > 0 ? '/iam/img/main/icon-pin.png' : '/iam/img/main/icon-graypin.png' ?>" style="width:20px;height:20px;">
                                                            </a>
                                                        <? } ?>
                                                        <? if ($_SESSION['iam_member_id'] == $contents_row['mem_id']) {
                                                            if ($is_pay_version) { ?>
                                                                <a class="content-utils" onclick="show_con_send('<?= $contents_row['idx'] ?>')">
                                                                    <img src="/iam/img/main/icon_con_send.png" style="width:20px;height:20px;">
                                                                </a>
                                                    <? }
                                                        }
                                                    } ?>
                                                    <? if ($cur_win == "shared_receive") { ?>
                                                        <a class="content-utils" onclick="remove_shared_content('<?= $contents_row['idx'] ?>','<?= $_SESSION['iam_member_id'] ?>');">
                                                            <img src="/iam/img/main/icon-hide.png" style="width:20px;height:20px;">
                                                        </a>
                                                    <? } ?>
                                                    <a class="content-utils" onclick="showSNSModal_byContents('<?= $contents_row['idx'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                        <img src="/iam/img/menu/icon_share.png" style="width:20px;height:20px">
                                                    </a>
                                                    <? if ($_SESSION['iam_member_id'] && ($_SESSION['iam_member_id'] == $contents_row['mem_id'] || $_SESSION['iam_member_id'] == $group_manager)) { ?>
                                                        <? if ($_SESSION['iam_member_id'] == $contents_row['mem_id']) {
                                                            $share_ids = explode(",", $contents_row['contents_share_text']);
                                                            for ($index = 0; $index < count($share_ids); $index++) {
                                                                $share_sql = "select mem_name from Gn_Member where mem_id = '$share_ids[$index]'";
                                                                $share_result = mysqli_query($self_con,$share_sql);
                                                                $share_row = mysqli_fetch_array($share_result);
                                                                $share_names[$index] = htmlspecialchars($share_row['mem_name']);
                                                            }
                                                            $share_names = implode(",", $share_names);
                                                        ?>
                                                            <a class="content-utils" onclick="contents_edit('<?= $contents_row['idx'] ?>', '<?= $contents_row['ori_store_prod_idx'] ?>');">
                                                                <img src="/iam/img/menu/icon_edit_white.png" style="width:20px;height:20px;">
                                                            </a>
                                                        <? } ?>
                                                    <? } ?>
                                                </div>
                                        <? }
                                        } ?>
                                        <?
                                        if ((int)$contents_row['contents_type'] == 1 || (int)$contents_row['contents_type'] == 3) {
                                            if (count($content_images) > 1 && $contents_row['landing_mode'] == "N" && !$contents_row['gwc_con_state']) { ?>
                                                <button onclick="show_all_content_images('<?= $contents_row['idx'] ?>')" id="content_all_image<?= $contents_row['idx'] ?>" style="position: absolute;right:0px;bottom:0px;font-size: 14px;opacity: 60%;background: black;color: white;">
                                                    <?= "+" . (count($content_images) - 1) ?>
                                                </button>
                                                <button onclick="hide_all_content_images('<?= $contents_row['idx'] ?>')" id="hide_content_all_image<?= $contents_row['idx'] ?>" style="position: absolute;left:0px;top:300px;font-size: 14px;display:none;background: transparent">
                                                    <img src="/iam/img/main/icon-img_fold.png" style="width:30px">
                                                </button>
                                            <? }
                                            $open_state = 0;
                                            if ($open_domain != '') {
                                                $filter = explode(",", trim($open_domain));
                                                for ($j = 0; $j < count($filter); $j++) {
                                                    $str = trim($filter[$j]);
                                                    if (strpos($contents_row['contents_url'], $str) !== false) {
                                                        $open_state = 1;
                                                    }
                                                }
                                            }
                                            if ($open_state) { ?>
                                                <a href='<?= $contents_row['contents_url'] ?>' data="01" target="_blank" id="pagewrap<?= $contents_row['idx'] ?>">
                                                    <? if (count($content_images) > 0) { ?>
                                                        <img src="<?= cross_image($content_images[0]) ?>" class="contents_img">
                                                        <? }
                                                    if (count($content_images) > 1) {
                                                        for ($c = 1; $c < count($content_images); $c++) { ?>
                                                            <img src="<?= cross_image($content_images[$c]) ?>" class="contents_img hidden_image<?= $contents_row['idx'] ?>" style="<?= $landing ?>">
                                                    <? }
                                                    } ?>
                                                </a>
                                                <? } else if ((int)$contents_row['open_type'] == 1) { //내부열기
                                                if ($contents_row['media_type'] == "I") {
                                                    if (count($content_images) > 0 && strstr($contents_row['contents_url'], ".mp4") === false) { ?>
                                                        <div class="gwc_con_img" data="02" onclick="showpage<?= $contents_row['idx'] ?>('<?= $contents_row['contents_url'] ?>', '<?= $contents_row['landing_mode'] ?>', '<?= $contents_row['gwc_con_state'] ?>')" id="pagewrap<?= $contents_row['idx'] ?>">
                                                            <img src="<?= cross_image($content_images[0]) ?>" class="contents_img">
                                                            <?
                                                            if (!$contents_row['gwc_con_state']) {
                                                                if ($contents_row['landing_mode'] == "Y") {
                                                                    $hide_img_state = "";
                                                                } else {
                                                                    $hide_img_state = "display:none";
                                                                }
                                                                for ($c = 1; $c < count($content_images); $c++) { ?>
                                                                    <img src="<?= cross_image($content_images[$c]) ?>" class="contents_img hidden_image<?= $contents_row['idx'] ?>" style="<?= $hide_img_state ?>">
                                                            <? }
                                                            } ?>
                                                        </div>
                                                    <? } else if ($contents_row['contents_url'] != "") { ?>
                                                        <div class="gwc_con_img">
                                                            <iframe src="<?= strpos($contents_row['contents_url'], 'http://') !== false ? $cross_page . urlencode($contents_row['contents_url']) : $contents_row['contents_url'] ?>" width="100%" height="600px" sandbox="allow-same-origin allow-scripts allow-popups allow-forms allow-top-navigation"></iframe>
                                                        </div>
                                                    <? }
                                                } else { ?>
                                                    <div class="gwc_con_img" style="position: relative">
                                                        <a style="position: absolute;top: 20px;left: 20px;background: #f3f3f3;display: flex;padding:5px;padding-right:15px;z-index:10;font-size:14px" class="unmute_btn">
                                                            <img src="/iam/img/main/mute.png" style="height: 30px">
                                                            <p style="margin-top: 5px;font-weight:bold;">탭하여 음소거 해제</p>
                                                        </a>
                                                        <video src="<?= $contents_row['contents_img'] ?>" type="video/mp4" autoplay loop muted playsinline preload style="width:100%;" autoplay></video>
                                                        <img src="/iam/img/movie_play.png" style="display:none;width:70px;" class="movie_play">
                                                    </div>
                                                <? } ?>
                                                <? } else { //외부열기
                                                if ($contents_row['contents_url'] != "") { ?>
                                                    <a href='<?= $contents_row['contents_url'] ?>' target="_blank" id="pagewrap<?= $contents_row['idx'] ?>">
                                                    <? } ?>
                                                    <? if (count($content_images) > 0) { ?>
                                                        <img src="<?= cross_image($content_images[0]) ?>" class="contents_img">
                                                        <? }
                                                    if (count($content_images) > 1 && !$contents_row['gwc_con_state']) {
                                                        for ($c = 1; $c < count($content_images); $c++) { ?>
                                                            <img src="<?= cross_image($content_images[$c]) ?>" class="contents_img hidden_image<?= $contents_row['idx'] ?>" style="<?= $landing ?>">
                                                        <? }
                                                    }
                                                    if ($contents_row['contents_url'] != "") { ?>
                                                    </a>
                                                <? } ?>
                                            <? } ?>
                                            <script type="text/javascript">
                                                function showpage<?= $contents_row['idx']; ?>(url, landing, gwc) {
                                                    <? if ($cur_win == "my_info") { ?>
                                                        if (url != "")
                                                            document.getElementById('pagewrap<?= $contents_row['idx']; ?>').innerHTML = '<iframe src="' + url + '" width="100%" height="600px" sandbox="allow-same-origin allow-scripts allow-popups allow-forms allow-top-navigation"></iframe>';
                                                    <? } else { ?>
                                                        var navCase = navigator.userAgent.toLocaleLowerCase();
                                                        if (gwc == 1) {
                                                            var contents_mode_gwc = getCookie('contents_mode');
                                                            if (landing == "Y") {
                                                                if (navCase.search("android") > -1) {
                                                                    $("#contents_page").html("<iframe id='ALLAT_MOBILE_FRAME' name='ALLAT_MOBILE_FRAME' src='https://<?= $HTTP_HOST ?>/iam/contents_gwc.php?contents_idx=<?= $contents_row['idx']; ?>&gwc=Y&mobile=Y' frameborder=0 width=100%px height=100%px scrolling=no></iframe>");
                                                                    document.getElementById("contents_page").style.width = "100%";
                                                                    document.getElementById("contents_page").style.height = "100%";
                                                                    document.getElementById("contents_page").style.left = 0 + "px";
                                                                    document.getElementById("contents_page").style.top = 0 + "px";
                                                                    document.getElementById("contents_page").style.display = "block";
                                                                    document.getElementById("wrap").style.display = "none";
                                                                    $('body,html').animate({
                                                                        scrollTop: 0,
                                                                    }, 100);
                                                                } else {
                                                                    window.open('/iam/contents_gwc.php?contents_idx=<?= $contents_row['idx']; ?>&gwc=Y', '_blank');
                                                                }
                                                            }
                                                        } else {
                                                            if (navCase.search("android") > -1) {
                                                                var link = "";
                                                                if (url == "")
                                                                    link = 'https://<?= $HTTP_HOST ?>/iam/contents.php?contents_idx=<?= $contents_row['idx']; ?>&mobile=Y';
                                                                else
                                                                    link = url;
                                                                $("#contents_page").html("<iframe id='ALLAT_MOBILE_FRAME' name='ALLAT_MOBILE_FRAME' src='" + link + "' frameborder=0 width=100%px height=100%px scrolling=no></iframe>");
                                                                document.getElementById("contents_page").style.width = "100%";
                                                                document.getElementById("contents_page").style.height = "100%";
                                                                document.getElementById("contents_page").style.left = 0 + "px";
                                                                document.getElementById("contents_page").style.top = 0 + "px";
                                                                document.getElementById("contents_page").style.display = "block";
                                                                document.getElementById("wrap").style.display = "none";
                                                                $('body,html').animate({
                                                                    scrollTop: 0,
                                                                }, 100);
                                                            } else {
                                                                var link = "";
                                                                if (url == "")
                                                                    link = '/iam/contents.php?contents_idx=<?= $contents_row['idx']; ?>';
                                                                else
                                                                    link = url;
                                                                window.open(link, '_blank');
                                                            }
                                                            save_load('<?= $_SESSION['iam_member_id'] ?>', '<?= $contents_row['idx'] ?>', 1);
                                                        }
                                                    <? } ?>
                                                }
                                            </script>
                                            <?
                                        } else if ((int)$contents_row['contents_type'] == 2) {
                                            echo $contents_row['contents_iframe'];
                                            /*$contents_movie = true;
                                        if(!$contents_row['contents_img']){
                                            $contents_movie = false;
                                            echo $contents_row['contents_iframe'];
                                        }else{
                                            if((int)$contents_row['open_type'] == 1){?>
                                                <div onclick="play<?=$contents_row['idx'];?>();" id="vidwrap<?=$contents_row['idx'];?>" style="position: relative;">
                                                    <img src="<?=cross_image($content_images[0])?>" class="contents_img">
                                                    <?if($contents_movie){?>
                                                        <img class="movie_play" src="/iam/img/movie_play.png">
                                                    <?}?>
                                                    <?if(count($content_images) > 1){?>
                                                        <?for($c = 1;$c < count($content_images);$c ++){?>
                                                            <img src="<?=cross_image($content_images[$c])?>" class="contents_img hidden_image<?=$contents_row['idx']?>" style="<?=$landing?>">
                                                        <?}?>
                                                    <?}?>
                                                </div>
                                            <?}else{?>
                                                <a href="<?=$contents_row['contents_url']?>" target="_blank" id="vidwrap<?=$contents_row['idx'];?>" style="position: relative;">
                                                    <img src="<?=cross_image($content_images[0])?>" class="contents_img">
                                                    <?if($contents_movie){?>
                                                        <img class="movie_play" src="/iam/img/movie_play.png">
                                                    <?}?>
                                                    <?if(count($content_images) > 1){?>
                                                        <?for($c = 1;$c < count($content_images);$c ++){?>
                                                            <img src="<?=cross_image($content_images[$c])?>" class="contents_img hidden_image<?=$contents_row['idx']?>" style="<?=$landing?>">
                                                        <?}?>
                                                    <?}?>
                                                </a>
                                            <?}?>
                                            <script type="text/javascript">
                                                function play<?=$contents_row['idx'];?>() {
                                                    document.getElementById('vidwrap<?=$contents_row['idx'];?>').innerHTML = "<?=$contents_row['contents_iframe']?>";
                                                    save_load('<?=$_SESSION['iam_member_id']?>','<?=$contents_row['idx']?>',1);
                                                }
                                            </script>
                                        <?}*/
                                        } else if ((int)$contents_row['contents_type'] == 4) {
                                            $vid_data = $contents_row['source_iframe'];
                                            if ((int)$contents_row['open_type'] == 1) { ?>
                                                <div onclick="play<?= $contents_row['idx']; ?>();" id="vidwrap<?= $contents_row['idx']; ?>">
                                                    <? if ($content_images[0]) { ?>
                                                        <img src="<?= cross_image($content_images[0]) ?>" class="contents_img">
                                                        <iframe src="<?= $vid_data ?>" style="width:100%;height: 600px;display:none"></iframe>
                                                    <? } else { ?>
                                                        <iframe src="<?= $vid_data ?>" style="width:100%;height: 600px;"></iframe>
                                                    <? } ?>
                                                </div>
                                            <? } else { ?>
                                                <a href="<?= $contents_row['contents_url'] ?>" id="vidwrap<?= $contents_row['idx']; ?>">
                                                    <? if ($content_images[0]) { ?>
                                                        <img src="<?= cross_image($content_images[0]) ?>" class="contents_img">
                                                        <iframe src="<?= $vid_data ?>" style="width:100%;height: 600px;display:none"></iframe>
                                                    <? } else { ?>
                                                        <iframe src="<?= $vid_data ?>" style="width:100%;height: 600px;"></iframe>
                                                    <? } ?>
                                                </a>
                                            <? } ?>
                                            <script type="text/javascript">
                                                function play<?= $contents_row['idx']; ?>() {
                                                    $('#vidwrap<?= $contents_row['idx']; ?> > iframe').css("display", "block");
                                                    $('#vidwrap<?= $contents_row['idx']; ?> > img').css("display", "none");
                                                    save_load('<?= $_SESSION['iam_member_id'] ?>', '<?= $contents_row['idx'] ?>', 1);
                                                }
                                            </script>
                                        <? } ?>
                                        <!-- <a class="content-utils" style="cursor:pointer;position:absolute;bottom:35px;right:10px;z-index:10;background-color: unset;width: 35px;height: 35px;" 
                                        onclick="scroll_down();">
                                        <img src="/iam/img/menu/icon_next_con.png"  style="width:65px;height:35px;margin-left:0px;margin-top:-1px;">
                                    </a> -->
                                    </div>
                                </div>
                                <? if ($_GET['key1'] == 4) { ?>
                                    <div class="desc-wrap" style="border-bottom: 1px solid #dddddd;">
                                        <? if ((int)$contents_row['contents_type'] == 3) {
                                            $price_show1 = "적립금";
                                            $price_show2 = "판매가";
                                            $price_style = "";
                                            $sql_card = "select sale_cnt, sale_cnt_set, add_reduce_val from Gn_Iam_Name_Card where idx='{$contents_row['card_idx']}'";
                                            $res_card = mysqli_query($self_con,$sql_card);
                                            $row_card = mysqli_fetch_array($res_card); ?>
                                            <div class="desc is-product" style="margin-top: 10px;">
                                                <div class="desc-inner">
                                                    <div class="outer <?= $row_card['sale_cnt_set'] ?>">
                                                        <?
                                                        $buy_btn = "";
                                                        $contents_price_arr = explode("|", $contents_row['contents_price']);
                                                        $contents_row['contents_price'] = $contents_price_arr[0] * 1;
                                                        $contents_row['contents_download_price'] = $contents_price_arr[3] * 1;
                                                        if ($contents_row['contents_price'] > 0) {
                                                            if (!$row_card['sale_cnt_set']) {
                                                                $style_decor = "";
                                                                $state_end = "";
                                                                $discount = $contents_row['reduce_val']; //100 - ($contents_row['contents_sell_price'] / $contents_row['contents_price']) * 100;
                                                                $add_point = $contents_row['contents_price'] * ((int)$discount / 100);
                                                            } else {
                                                                if (!$row_card['sale_cnt']) {
                                                                    $style_decor = "text-decoration: line-through;";
                                                                    $state_end = "마감";
                                                                    $discount = $contents_row['reduce_val']; //100 - ($contents_row['contents_sell_price'] / $contents_row['contents_price']) * 100;
                                                                    $add_point = $contents_row['contents_price'] * (((int)$discount - $row_card['add_reduce_val']) / 100);
                                                                } else {
                                                                    $style_decor = "";
                                                                    $state_end = "적립";
                                                                    $discount = $contents_row['reduce_val']; //100 - ($contents_row['contents_sell_price'] / $contents_row['contents_price']) * 100;
                                                                    $add_point = $contents_row['contents_price'] * ((int)$discount / 100);
                                                                }
                                                            }
                                                            if ($_GET['key1'] == 4) {
                                                                $state_end = "";
                                                                if ($contents_row['gwc_con_state'] != 0) {
                                                                    $price_show1 = "최저가";
                                                                    $price_show2 = "정상가";
                                                                    $price_style = "text-decoration: line-through;";
                                                                    $add_point = $contents_row['contents_sell_price'];
                                                                }
                                                            }
                                                            if ($cur_win == "my_info") {
                                                                $state_end = "";
                                                                if ($contents_row['gwc_con_state'] != 0) {
                                                                    $price_show1 = "최저가";
                                                                    $price_show2 = "정상가";
                                                                    $price_style = "text-decoration: line-through darkgrey;";
                                                                    $add_point = $contents_row['contents_sell_price'];
                                                                    if ($_SESSION['iam_member_id'] != 'iamstore') {
                                                                        $buy_btn_con = "visibility: hidden;";
                                                                    } else {
                                                                        $buy_btn_con = "";
                                                                    }
                                                                }
                                                            }
                                                            if (($_GET['iamstore'] == "Y" && $gwc_pay_mem) || $_GET['iamstore'] == "C") {
                                                                $buy_btn_con = "visibility: hidden;";
                                                                $discount = 100 - ($contents_row['send_provide_price'] / $contents_row['contents_sell_price']) * 100;
                                                            }
                                                        ?>
                                                            <div class="price" style="width:100%;display: inline-flex;">
                                                                <span style="vertical-align: top;color: red;font-size: 18px;font-weight: bold;margin-right: 3px;"><?= (int)$discount ?>% </span>
                                                                <span class="downer" style="color:black;font-size: 18px;margin-right: 3px;"><?= number_format((int)$add_point) ?>원 </span>
                                                                <span class="downer" style="font-size: 12px;color:darkgrey;"><span style="vertical-align: text-bottom;font-size: 12px;<?= $price_style ?>"><?= number_format($contents_row['contents_price']) ?></span>원 </span>
                                                                <? if (($_GET['iamstore'] == "Y" && $gwc_pay_mem) || $_GET['iamstore'] == "C") { ?>
                                                                    <span class="downer" style="color:black;font-size: 14px;margin-right: 5px;margin-left: 5px;">|| </span>
                                                                    <span style="vertical-align: top;color: blue;font-size: 16px;font-weight: bold;margin-right: 3px;">공급가</span>
                                                                    <span class="downer" style="color:black;font-size: 18px;margin-right: 3px;"><?= number_format($contents_row['send_provide_price']) ?>원 </span>
                                                                <? } ?>
                                                            </div>
                                                            <span style="font-size: 15px;"><?= $state_end ?></span>
                                                        <? } else { ?>
                                                            <div class="price" style="width:230px;display: inline-flex;">
                                                                <span style="vertical-align: top;color: red;font-size: 18px;font-weight: bold;margin-right: 3px;">0%</span>
                                                                <span class="downer" style="color:black;font-size: 18px;margin-right: 3px;"><?= number_format($contents_row['contents_price']) ?>원</span>
                                                                <span class="downer" style="font-size: 12px;color:darkgrey;"><span style="vertical-align: text-bottom;<?= $price_style ?>"><?= number_format($contents_row['contents_price']) ?></span>원</span>
                                                            </div>
                                                        <? } ?>
                                                        <? if ($_GET['key1'] != 4) {
                                                            if ($cur_win == 'my_info' && $_SESSION['iam_member_id'] == 'iamstore') {
                                                                $buy_btn = "display:none;";
                                                            } ?>
                                                            <div class="order" style="<?= $buy_btn ?><?= $buy_btn_con ?>">
                                                                <? if ($_SESSION['iam_member_id']) {
                                                                    $price_service = $contents_row['contents_sell_price'];
                                                                    $contents_row['contents_title'] = str_replace('"', ' ', $contents_row['contents_title']);
                                                                    $contents_row['contents_title'] = str_replace("'", ' ', $contents_row['contents_title']);
                                                                    $name_service = $contents_row['contents_title'];
                                                                    $sellerid_service = $contents_row['mem_id'];
                                                                    $contents_url = $contents_row['contents_url'];
                                                                    $card_price = $contents_row['contents_sell_price'] * 1 + $contents_row['send_salary_price'] * 1;
                                                                    $pay_link = "/iam/pay_spgd.php?item_name=" . $contents_row['contents_title'] . '&item_price=' . $card_price . '&manager=' . $contents_row['mem_id'] . "&conidx=" . $contents_row['idx'] . "&sale_cnt=" . $row_card['sale_cnt'];
                                                                ?>
                                                                    <div class="dropdown" style="float:right;width: 82px;">
                                                                        <a class="dropdown-toggle" data-toggle="dropdown" expanded="false" style="background:#99cc00;border-radius:10px;cursor:pointer;">구매</a>
                                                                        <ul class="dropdown-menu buy_servicecon" style="width: 82px;">
                                                                            <li>
                                                                                <a href="<?= $pay_link ?>" target="_blank" style="font-size: 12px;background-color:#99cc00;">카드결제</a>
                                                                            </li>
                                                                            <li>
                                                                                <a onclick="point_settle_modal(<?= $contents_row['contents_sell_price'] ?>, '<?= $contents_row['contents_title'] ?>', '<?= $contents_row['idx'] ?>', '<?= $contents_row['mem_id'] ?>', '<?= $row_card['sale_cnt'] ? $row_card['sale_cnt'] : '0' ?>', '<?= $contents_row['send_salary_price'] ? $contents_row['send_salary_price'] : '0' ?>')" style="font-size: 12px;background-color:#99cc00;">포인트결제</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                <? } else { ?>
                                                                    <a href="<? echo '/iam/login.php?contents_idx=' . $contents_row['idx'] ?>" target="_self" style="background:#99cc00;border-radius:10px;">구매</a>
                                                                <? } ?>
                                                            </div>
                                                        <? } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <? } ?>
                                        <? if ($contents_row['contents_title'] != "") { ?>
                                            <div class="title service_title" style="display: flex;flex-direction: column;justify-content: center;height:45px;">
                                                <h3 style="font-size: 14px;text-align: left;"><?= $contents_row['contents_title'] ?></h3>
                                            </div>
                                        <? } ?>
                                        <div class="desc" style="display:none;">
                                            <div class="desc-inner desc-text desc-inner-content">
                                                <p style="overflow-wrap: break-word;font-size: 14px;"><?= nl2br($contents_row['contents_desc']) ?></p>
                                            </div>
                                            <!--a class="arrow" style="color:#669933;font-weight:bold;cursor:pointer">
                                        [더 보기]
                                    </a-->
                                            <? if ($content_images == null) {
                                                if (!$_SESSION['iam_member_id'] || $cur_card['share_send_card'] != 0 || ($cur_win != "shared_receive" && $_SESSION['iam_member_id'] != $contents_row['mem_id'] && $_SESSION['iam_member_id'] != $group_manager)) { ?>
                                                    <a class="content-utils" style="cursor:pointer;position:absolute;bottom:5px;right:10px;z-index:10;" onclick="showSNSModal_byContents('<?= $contents_row['idx'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                        <img src="/iam/img/menu/icon_share_white.png" style="width:13px;height:16px;margin-left:3px;margin-top:-1px;">
                                                    </a>
                                                <? } else { ?>
                                                    <a onclick="show_contents_utils(<?= $contents_row['idx'] ?>);" class="content-utils" style="position: absolute;bottom:5px;right: 10px;z-index:10;" title="">
                                                        <!--i class="fa fa-pencil-square-o" aria-hidden="false"></i-->
                                                        <img src="/iam/img/menu/icon_utils_box.png" style="height:20px;">
                                                    </a>
                                                    <div class="utils-index" id="<?= 'utils_index_' . $contents_row['idx'] ?>" style="display:none;z-index:10;">
                                                        <?
                                                        if ($cur_win != "we_story" && $_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) { ?>
                                                            <a class="content-utils" onclick="contents_add('<?= $card_owner ?>','<?= $contents_row['contents_order'] ?>',<?= $my_first_card ?>);">
                                                                <img src="/iam/img/menu/icon_plus.png" style="width:20px;height:20px;">
                                                            </a>
                                                        <?  }
                                                        if ($_SESSION['iam_member_id'] && ($_SESSION['iam_member_id'] == $contents_row['mem_id'] || $_SESSION['iam_member_id'] == $group_manager)) { ?>
                                                            <a class="content-utils" onclick="contents_del('<?= $contents_row['idx'] ?>', '<?= $contents_row['ori_store_prod_idx'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                                <img src="/iam/img/menu/icon_minus.png" style="width:20px;height:20px;">
                                                            </a>
                                                            <? if (!$cur_win || $cur_win == "my_info") { ?>
                                                                <a class="content-utils" onclick="contents_range_down('<?= $contents_row['idx'] ?>', '<?= $contents_row['contents_order'] ?>','<?= $cur_card['card_short_url'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                                    <img src="/iam/img/menu/icon_arrow_down.png" style="width:20px;height:20px;">
                                                                </a>
                                                                <a class="content-utils" onclick="contents_range_up('<?= $contents_row['idx'] ?>', '<?= $contents_row['contents_order'] ?>','<?= $cur_card['card_short_url'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                                    <img src="/iam/img/menu/icon_arrow_up.png" style="width:20px;height:20px;">
                                                                </a>
                                                                <a class="content-utils" onclick="contents_range_down('<?= $contents_row['idx'] ?>', '<?= $contents_row['contents_order'] ?>','<?= $cur_card['card_short_url'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>','min');">
                                                                    <img src="/iam/img/menu/icon_arrow_end.png" style="width:20px;height:20px;">
                                                                </a>
                                                                <a class="content-utils" onclick="contents_range_up('<?= $contents_row['idx'] ?>', '<?= $contents_row['contents_order'] ?>','<?= $cur_card['card_short_url'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>','max');">
                                                                    <img src="/iam/img/menu/icon_arrow_start.png" style="width:20px;height:20px;">
                                                                </a>
                                                            <?  } ?>
                                                            <? if ($_SESSION['iam_member_id'] == $group_manager) { ?>
                                                                <a class="content-utils" onclick="contents_fix('<?= $contents_row['idx'] ?>');">
                                                                    <img src="<?= $contents_row['group_fix'] > 0 ? '/iam/img/main/icon-pin.png' : '/iam/img/main/icon-graypin.png' ?>" style="width:20px;height:20px;">
                                                                </a>
                                                            <? } ?>
                                                            <? if ($_SESSION['iam_member_id'] == $contents_row['mem_id']) {
                                                                if ($is_pay_version) { ?>
                                                                    <a class="content-utils" onclick="show_con_send('<?= $contents_row['idx'] ?>')">
                                                                        <img src="/iam/img/main/icon_con_send.png" style="width:20px;height:20px;">
                                                                    </a>
                                                        <? }
                                                            }
                                                        } ?>
                                                        <? if ($cur_win == "shared_receive") { ?>
                                                            <a class="content-utils" onclick="remove_shared_content('<?= $contents_row['idx'] ?>','<?= $_SESSION['iam_member_id'] ?>');">
                                                                <img src="/iam/img/main/icon-hide.png" style="width:20px;height:20px;">
                                                            </a>
                                                        <? } ?>
                                                        <a class="content-utils" onclick="showSNSModal_byContents('<?= $contents_row['idx'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                            <img src="/iam/img/menu/icon_share.png" style="width:20px;height:20px">
                                                        </a>
                                                        <? if ($_SESSION['iam_member_id'] && ($_SESSION['iam_member_id'] == $contents_row['mem_id'] || $_SESSION['iam_member_id'] == $group_manager)) { ?>
                                                            <? if ($_SESSION['iam_member_id'] == $contents_row['mem_id']) {
                                                                $share_ids = explode(",", $contents_row['contents_share_text']);
                                                                for ($index = 0; $index < count($share_ids); $index++) {
                                                                    $share_sql = "select mem_name from Gn_Member where mem_id = '$share_ids[$index]'";
                                                                    $share_result = mysqli_query($self_con,$share_sql);
                                                                    $share_row = mysqli_fetch_array($share_result);
                                                                    $share_names[$index] = htmlspecialchars($share_row['mem_name']);
                                                                }
                                                                $share_names = implode(",", $share_names);
                                                            ?>
                                                                <a class="content-utils" onclick="contents_edit('<?= $contents_row['idx'] ?>', '<?= $contents_row['ori_store_prod_idx'] ?>');">
                                                                    <img src="/iam/img/menu/icon_edit_white.png" style="width:20px;height:20px;">
                                                                </a>
                                                            <? } ?>
                                                        <? } ?>
                                                    </div>
                                            <? }
                                            } ?>
                                        </div>
                                    </div>
                                <? } ?>
                                <div class="info-wrap" <?
                                                        if (($contents_row['contents_type'] == 1 && count($content_images) == 0) || $_GET['key1'] == 4)
                                                            echo "style = 'min-height :30px;border:none;height: 50px;'";
                                                        else
                                                            echo "style = 'border:none;border-top:1px solid #dddddd;'";
                                                        ?>>
                                    <?
                                    $host_pos = strpos($contents_row['contents_url'], "//");
                                    $host_name = substr($contents_row['contents_url'], $host_pos + 2);
                                    $host_pos = strpos($host_name, "/");
                                    $host_name = substr($host_name, 0, $host_pos);
                                    ?>
                                    <? if ($contents_row['contents_url_title'] || $contents_row['contents_url']) {
                                        if ($_GET['key1'] == 4 && $contents_row['ai_map_gmarket'] == 3) {
                                            $display_gwc_con = "display:none;";
                                        } else {
                                            $display_gwc_con = "";
                                        } ?>
                                        <div class="media-tit" style="<?= $display_gwc_con ?>">
                                            <a href="<?= $contents_row['contents_url'] ?>" target="_blank"><?= $host_name ?></a>
                                            <br><?= $contents_row['contents_url_title'] ?>
                                        </div>
                                    <? } ?>
                                    <div class="second-box" style="<? if ($westory_card['post_display'] == 0) echo ('display:none !important'); ?>">
                                        <div class="in-box">
                                            <div style="display: flex;vertical-align: middle">
                                                <a class="hand" href="javascript:contents_like('<?= $contents_row['idx'] ?>','<?= $_SESSION['iam_member_id'] ?>', '<?= $contents_row['product_seperate'] ?>', '<?= $contents_row['gwc_con_state'] ?>');">
                                                    <? if (in_array($_SESSION['iam_member_id'], explode(",", $contents_row['contents_like']))) { ?>
                                                        <img src="/iam/img/menu/icon_like_active.png" width="24px" alt="" id="like_img_<?= $contents_row['idx'] ?>">
                                                    <? } else { ?>
                                                        <img src="/iam/img/menu/icon_like.png" width="24px" alt="" id="like_img_<?= $contents_row['idx'] ?>">
                                                    <? } ?>
                                                </a>
                                                <p class="second-box-like like-count like_<?= $contents_row['idx'] ?>" style="font-size:13px">
                                                    <?= number_format(count(explode(",", $contents_row['contents_like']))) ?>개
                                                </p>
                                                <? if ($_GET['key1'] != 4) { ?>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <a href="javascript:show_post('<?= $contents_row['idx'] ?>');" class="hand">
                                                        <img id="<?= 'show_post_img_' . $contents_row['idx'] ?>" src="/iam/img/menu/icon_post.png" height="24px" alt="">
                                                        <label style="font-size: 10px;background: #ff3333;border-radius: 50%!important;padding: 3px 5px!important;color: #fff;
                                                        text-align: center;line-height: 1;position: absolute;margin-left: -15px" id="<?= 'post_alarm_' . $contents_row['idx'] ?>"></label>
                                                    </a>
                                                    <p onclick="refresh_post('<?= $contents_row['idx'] ?>')" class="second-box-like like-count" id="<?= 'post_count_' . $contents_row['idx'] ?>" style="font-size:13px"><?= $post_count ?> &#x21BA;</p>
                                                <? } ?>
                                            </div>
                                        </div>
                                        <? if ($_GET['key1'] == 4 && $_GET['iamstore'] == "N") { ?>
                                            <div class="in-box" style="position: absolute;right: 10px;">
                                                <a class="gwc_order_btn1" href="javascript:show_order_option('<?= $contents_row['idx'] ?>', 'cart', '<?= $gwc_mem ?>')" style="margin-bottom: 5px;font-size: 12px;border: 1px solid #99cc00;margin-right: 10px;padding: 3px 10px;border-radius: 10px;">장바구니</a>
                                                <a class="gwc_order_btn2" href="javascript:show_order_option('<?= $contents_row['idx'] ?>', 'pay', '<?= $gwc_mem ?>')" style="margin-bottom: 5px;font-size: 12px;padding: 3px 10px;border-radius: 10px;border: 1px solid;color: white;background-color: #99cc00;">바로구매</a>
                                            </div>
                                        <? } ?>
                                    </div>
                                </div>
                                <?
                                $post_status_sql = "select count(*) from Gn_Iam_Post where content_idx = '{$contents_row['idx']}' and status = 'N' and lock_status = 'N'";
                                $post_status_res = mysqli_query($self_con,$post_status_sql);
                                $post_status_row =  mysqli_fetch_array($post_status_res);
                                $post_status_count = $post_status_row[0];
                                if ($post_status_count  > 0)
                                    echo "<script>  $('#post_alarm_" . $contents_row['idx'] . "').html(" . $post_status_count . "); </script>";
                                else
                                    echo "<script>  $('#post_alarm_" . $contents_row['idx'] . "').hide(); </script>";
                                ?>
                                <div class="post-wrap <?= 'post_wrap' . $contents_row['idx'] ?>" style="display:none" id="<?= 'post_wrap' . $contents_row['idx'] ?>">
                                    <div style="display: flex;justify-content: flex-end;">
                                        <div style="margin-left:30px;margin-right:15px;width:100%">
                                            <textarea id="post_content<?= $contents_row['idx'] ?>" name="post_content<?= $contents_row['idx'] ?>" class="post_content" maxlength="300" style="font-size:14px;width:100%;height:35px;border: 1px;" placeholder="댓글은 300자 이내로 작성해주세요"></textarea>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-link" style="font-size:14px;padding: 5px 12px;color:#99cc00" id="send_post" onclick="add_post('<?= $contents_row['idx'] ?>')">작성</button>
                                        </div>
                                    </div>
                                    <div style="margin-left:30px;">
                                        <span id="post_status" name="post_status" style="padding: 10px;font-size:10px">0/300</span>
                                    </div>
                                    <div style="border: 0px solid #dddddd;margin-left:30px;" id="<?= 'post_list_' . $contents_row['idx'] ?>" name="<?= 'post_list_' . $contents_row['idx'] ?>">
                                        <? while ($post_row = mysqli_fetch_array($post_res)) {
                                            $sql_mem_p = "select profile, mem_name,  from Gn_Member where mem_id='{$post_row['mem_id']}'";
                                            $res_mem_p = mysqli_query($self_con,$sql_mem_p);
                                            $row_mem_p = mysqli_fetch_array($res_mem_p);

                                            $post_card_sql = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$post_row['mem_id']}' order by req_data asc";
                                            $post_card_result = mysqli_query($self_con,$post_card_sql);
                                            $post_card_row = mysqli_fetch_array($post_card_result);
                                        ?>
                                            <div class="user-item" id="<?= 'post_reply' . $post_row['id'] ?>">
                                                <a href="/?<?= strip_tags($post_card_row['card_short_url']) ?>" class="img-box">
                                                    <div class="user-img" style="margin: 5px;width:32px;height:32px;">
                                                        <? if ($row_mem_p['profile']) { ?>
                                                            <img src="<?= $row_mem_p['profile'] ?>" alt="">
                                                        <? } else { ?>
                                                            <img src="/iam/img/profile_img.png" alt="">
                                                        <? } ?>
                                                    </div>
                                                </a>
                                                <div class="wrap" style="margin:10px 0px;">
                                                    <span class="date">
                                                        <?= $row_mem_p['mem_name'] . " " . $post_row['reg_date'] ?>
                                                    </span>
                                                    <span class="user-name">
                                                        <? if ($post_row['type']) { ?>
                                                            <?= $post_row['title'] ?><br>
                                                        <? } ?>
                                                        <?= str_replace("\n", "<br>", $post_row['content']) ?>
                                                    </span>
                                                </div>
                                                <?
                                                if ($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $post_row['mem_id']) {
                                                    if ($post_row['type'] == 0) { ?>
                                                        <div class="dropdown" style="top : 10px;position : absolute;right:30px;height:24px;">
                                                            <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_dot.png" style="height: 24px;">
                                                            <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                                                <li>
                                                                    <a href="javascript:void(0)" onclick="edit_post('<?= $contents_row['idx'] ?>','<?= $post_row['id'] ?>','<?= $post_row['content'] ?>')" title="댓글 수정">
                                                                        <p>수정</p>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:void(0)" onclick="delete_post('<?= $contents_row['idx'] ?>','<?= $post_row['id'] ?>')" title="댓글 삭제">
                                                                        <p>삭제</p>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    <? }
                                                } else if ($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $contents_row['mem_id']) {
                                                    if ($post_row['type'] == 0) { ?>
                                                        <div class="dropdown" style="top : 10px;position : absolute;right:30px;height:24px">
                                                            <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_dot.png" style="height: 24px;">
                                                            <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                                                <li>
                                                                    <a href="javascript:void(0)" onclick="delete_post('<?= $contents_row['idx'] ?>','<?= $post_row['id'] ?>')" title="댓글 삭제">
                                                                        <p>삭제</p>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:void(0)" onclick="lock_post('<?= $contents_row['idx'] ?>','<?= $post_row['id'] ?>')" title="댓글 차단">
                                                                        <p>차단</p>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                <? }
                                                } ?>
                                                <? if ($post_row['type'] == 0) { ?>
                                                    <div style="position: absolute;left: 60px;bottom: 0px">
                                                        <span style="color: #bdbdbd;cursor:pointer;font-size:13px" onclick="show_post_reply(<?= $post_row['id'] ?>);">
                                                            답글달기
                                                        </span>
                                                    </div>
                                                <? } ?>
                                            </div>
                                            <div id="<?= 'post_reply_' . $post_row['id'] ?>" class="post_reply_wrap" style="display: none;margin : 10px 0px">
                                                <div style="display: flex;justify-content: flex-end;">
                                                    <div style="margin-left:60px;margin-right:15px;width:100%">
                                                        <textarea id="<?= 'post_reply_' . $post_row['id'] . '_content' ?>" name="<?= 'post_reply_' . $post_row['id'] . '_content' ?>" class="post_reply_content" maxlength="300" placeholder="답글은 300자 이내로 작성해주세요" style="font-size:14px;height:35px;width: 100%;border: 1px;"></textarea>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="btn btn-link" style="font-size:14px;padding: 5px 12px;color:#99cc00" onclick="add_post_reply('<?= $contents_row['idx'] ?>','<?= $post_row['id'] ?>')">작성</button>
                                                    </div>
                                                </div>
                                                <div style="border-bottom: 0px solid #dddddd;margin-left:60px">
                                                    <span id="post_reply_status" name="post_reply_status" style="padding: 10px">0/300</span>
                                                </div>
                                            </div>
                                            <?
                                            $reply_sql = "select * from Gn_Iam_Post_Response r where r.post_idx = '{$post_row['id']}' order by r.reg_date";
                                            $reply_res = mysqli_query($self_con,$reply_sql);
                                            while ($reply_row = mysqli_fetch_array($reply_res)) {
                                                $sql_mem_pr = "select mem_name, profile from Gn_Member where mem_id='{$reply_row['mem_id']}'";
                                                $res_mem_pr = mysqli_query($self_con,$sql_mem_pr);
                                                $row_mem_pr = mysqli_fetch_array($res_mem_pr);

                                                $reply_card_sql = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$reply_row['mem_id']}' order by req_data asc";
                                                $reply_card_result = mysqli_query($self_con,$reply_card_sql);
                                                $reply_card_row = mysqli_fetch_array($reply_card_result);
                                            ?>
                                                <div class="user-item" style="padding-left: 50px">
                                                    <a href="/?<?= strip_tags($reply_card_row['card_short_url']) ?>" class="img-box">
                                                        <div class="user-img" style="margin: 5px;width:32px;height:32px;">
                                                            <? if ($row_mem_pr['profile']) { ?>
                                                                <img src="<?= $row_mem_pr['profile'] ?>" alt="">
                                                            <? } else { ?>
                                                                <img src="/iam/img/profile_img.png" alt="">
                                                            <? } ?>
                                                        </div>
                                                    </a>
                                                    <div class="wrap">
                                                        <span class="date">
                                                            <?= $row_mem_pr['mem_name'] . " " . $reply_row['reg_date'] ?>
                                                        </span>
                                                        <span class="user-name" id="<?= 'reply_list_' . $reply_row['id'] ?>">
                                                            <?= str_replace("\n", "<br>", $reply_row['contents']) ?>
                                                        </span>
                                                    </div>
                                                    <? if ($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $post_row['mem_id']) {
                                                        if ($reply_row['type'] == 0) { ?>
                                                            <div class="dropdown" style="top : 10px;position : absolute;right:30px;height:24px">
                                                                <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_dot.png" style="height: 24px;">
                                                                <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                                                    <li>
                                                                        <a href="javascript:void(0)" onclick="edit_post_reply('<?= $contents_row['idx'] ?>','<?= $post_row['id'] ?>','<?= $reply_row['id'] ?>','<?= $reply_row['contents'] ?>')" title="답글 수정">
                                                                            <p>수정</p>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:void(0)" onclick="delete_post_reply('<?= $contents_row['idx'] ?>','<?= $reply_row['id'] ?>')" title="답글 삭제">
                                                                            <p>삭제</p>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        <? }
                                                    } else if ($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $contents_row['mem_id']) {
                                                        if ($reply_row['type'] == 0) { ?>
                                                            <div class="dropdown" style="top : 10px;position : absolute;right:30px;height:24px">
                                                                <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_dot.png" style="height: 24px;">
                                                                <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                                                    <li>
                                                                        <a href="javascript:void(0)" onclick="delete_post_reply('<?= $contents_row['idx'] ?>','<?= $reply_row['id'] ?>')" title="답글 삭제">
                                                                            <p>삭제</p>
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="javascript:void(0)" onclick="lock_post_reply('<?= $contents_row['idx'] ?>','<?= $reply_row['id'] ?>')" title="답글 차단">
                                                                            <p>차단</p>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                    <? }
                                                    } ?>
                                                </div>
                                        <? }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?  } //while
                    // middle log
                    $logs->add_log("after render");
                    // echo "after render"; exit;
                }
                ?>
                <script type="text/javascript">
                    var isClicked = false;

                    function onClickMore(page) {
                        var type = getCookie('contents_mode');
                        if ('<?= $cur_win ?>' != "group-con")
                            iam_mystory('<?= $request_short_url . $card_owner_code ?>&cur_win=<?= $cur_win ?>&type=' + type + '&w_page=' + page + '&search_key=' + '<?= $_GET['search_key'] ?>' + '&key1=' + '<?= $_GET['key1'] ?>' + '&key2=' + '<?= $_GET['key2'] ?>' + '&sort=' + '<?= $_GET['sort'] ?>' + '&key3=' + '<?= $_GET['key3'] ?>' + '&sort_key3=' + '<?= $_GET['sort_key3'] ?>' + '&loc_name=' + '<?= $_GET['loc_name'] ?>' + '&key4=' + '<?= $_GET['key4'] ?>' + '&iamstore=' + '<?= $_GET['iamstore'] ?>' + '&cate_prod=' + '<?= $_GET['cate_prod'] ?>' + '&wide=' + '<?= $_GET['wide'] ?>' + '&preview=' + '<?= $_GET['preview'] ?>');
                        else
                            iam_mystory('<?= $group_card_url . $card_owner_code ?>&cur_win=<?= $cur_win ?>&type=' + type + '&w_page=' + page + '&search_key=' + '<?= $_GET['search_key'] ?>' + '&key1=' + '<?= $_GET['key1'] ?>' + '&key2=' + '<?= $_GET['key2'] ?>' + '&sort=' + '<?= $_GET['sort'] ?>' + "&gkind=" + '<?= $gkind ?>');
                    }
                    <? if ($is_show_con_count == true || $_GET['search_key'] != '') { ?>
                        $("#we_story_count").html("(" + <?= $cont_count ?> + ")건");
                        $("#my_info_count").html("(" + <?= $cont_count ?> + ")건");
                    <? } ?>

                    $(document).ready(function() {
                        var page = $(".show_next_paging").attr('data');
                        $(".show_next_paging").on({
                            mouseenter: function(e) {
                                e.preventDefault();
                                if (!isClicked) onClickMore(page);
                                isClicked = true;
                            },
                            click: function() {
                                onClickMore(page);
                            }
                        });
                    })
                </script>
            </section><!-- // 하단 영역 끝 -->
            <div class="pagination">
                <ul <? if ($_GET['cur_win'] == "best_sample" || $_GET['cur_win'] == "sample" || $_GET['cur_win'] == "recent_sample") {
                        echo 'hidden';
                    } ?>>
                    <? if (floor(($w_page - 1) / $pagination_count) >= 1) { //만약 현재 페이지가 12인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함
                    ?>
                        <li class='arrow'>
                            <a href='javascript:onClickMore(<?= $w_page - 10 ?>)'>
                                <i class='fa fa-angle-left' aria-hidden='true'></i>
                            </a>
                        </li>
                        <? }
                    $start_page = floor(($w_page - 1) / $pagination_count) * $pagination_count + 1;
                    $page_count = $pagination_count;
                    if (($start_page + $pagination_count - 1) * $contents_count_per_page > $cont_count) {
                        $page_count = floor(($cont_count + $contents_count_per_page - 1) / $contents_count_per_page) % $pagination_count;
                    }
                    //for문 반복문을 사용하여, 초기값을 블록의 시작번호를 조건으로 블록시작번호가 마지박블록보다 작거나 같을 때까지 $i를 반복시킨다
                    for ($i = 0; $i < $page_count; $i++) {
                        $cur_page = $i + $start_page;
                        if ($w_page == $cur_page) { //만약 현재페이지이면 해당하는 번호에 굵은 빨간색을 적용한다
                        ?>
                            <li class='active'>
                                <span><?= $cur_page ?></span>
                            </li>
                        <? } else { ?>
                            <li>
                                <a href="javascript:onClickMore('<?= $cur_page ?>')"><?= $cur_page ?></a>
                            </li>
                        <? }
                    }
                    if (($start_page + $page_count - 1) * $contents_count_per_page < $cont_count) { //만약 현재 페이지가 2인데 다음버튼을 누르면 12번페이지로 갈 수 있게 함
                        $next_page = $w_page + 10;
                        if ($next_page * $contents_count_per_page > $cont_count)
                            $next_page = round($cont_count / $contents_count_per_page);
                        ?>
                        <li class='arrow'>
                            <a href='javascript:onClickMore(<?= $next_page ?>)'>
                                <i class='fa fa-angle-right' aria-hidden='true'></i>
                            </a>
                        </li>
                    <? } ?>
                </ul>
                <? if ($cur_win == "my_info" || $cur_win == "my_story") { ?>
                    <span style="margin-top:10px"><?= "총 " . $cont_count . "건" ?></span>
                <? } ?>
                <br>
                <?
                if ($w_page * $contents_count_per_page < $cont_count) {
                    $next_page = $w_page + 1;
                ?>
                    <button style="width: 100%;padding: 7px;font-size: 20px;background-color: #039f57;color: white;" class="show_next_paging" data='<?= $next_page ?>'>다음페이지 열기</button>
                <? } ?>
            </div>
            <!-- 콘텐츠부분 -->
            <!-- ############################################################################################################################## -->
            <div id="ajax_div" style="display:none"></div>
        </main>
        <!-- ============================================= // 콘텐츠 영역 끝 =============================================== -->
        <footer id="footer" style="text-align: center;">
            <? //if($HTTP_HOST != "kiam.kr") {
            ?>
            <a href="<?= $domainData['footer_link']; ?>"><img src="<?= $domainData['footer_logo'] == "" ? "/iam/img/common/logo.png" : $domainData['footer_logo']; ?>" alt="아이엠 푸터로고" width="150"></a>
            <?/*} else {?>
            <a href="/m/"><img src="/iam/img/common/logo.png" alt="아이엠 푸터로고" width="120"></a>
        <?}*/ ?>
        </footer>
        <div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>
        <div id="tutorial-loading"></div>
        <a class="content-utils" id="scroll_down" onclick="scroll_down();">
            <img src="/iam/img/menu/icon_next_con.png" style="width:60px;height:58px;margin-left:0px;margin-top:-1px;">
        </a>
    </div>
    <?
    if (!$pay_status && $my_first_card) {
        echo "<script>toastr.error('본 카드는 공유카드이기 때문에 편집 또는 삭제가 안됩니다.');</script>";
    }
    // middle log
    $logs->add_log("section End");

    // echo "before_popup"; exit;
    ?>
    <form name="index_form" method="post">
        <input type="hidden" name="favorite" id="favorite" value="<?= $cur_card['favorite'] ?>">
        <input type="hidden" name="check_rnum" id="check_rnum" value="">
        <input type="hidden" name="card_url" id="card_url" value="<?= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ?>">
        <input type="hidden" name="contents_mode" id="contents_mode" value="up">
        <input type="hidden" name="contents_idx" id="contents_idx" value="">
    </form>
    <script>
        var opt_price = 0;
        var sup_price = 0;

        function go_cart() {
            location.href = "/iam/gwc_order_cart.php";
        }

        function scroll_down() {
            var cur_scroll = $(window).scrollTop();
            $('body,html').animate({
                scrollTop: cur_scroll + 600,
            }, 100);
        }

        function save_order_option() {
            var conts_cnt = $("#conts_cnt").val();
            var seller_id = $("#seller_id").val();
            var conts_price = parseInt($("#gwc_conts_price").text().replace(/,/g, ""));
            var salary = $("#gwc_conts_salary").text().replace(" +", "");
            salary = parseInt(salary.replace(/,/g, ""));
            var over_salary = $("#gwc_conts_over_salary").text().replace(/,/g, "");
            over_salary = over_salary.split('원')[0];
            over_salary = parseInt(over_salary.replace('(', ""));
            var cont_idx = $("#gwc_conts_idx").val();
            var str = '';
            var cnt = 0;
            var page_type = $("#page_type").val();

            if ($("#gwc_order_option").text().trim() != '') {
                $("#gwc_order_option dl dd select.it_option").each(function() {
                    cnt++;
                    str += $('#gwc_order_option dl dt label[for=it_option_' + cnt + ']').text() + '>>';
                    str += $('#gwc_order_option dl dd select[id=it_option_' + cnt + ']').val() + '>>';
                });
                cnt = 0;
                $("#gwc_order_option dl dd select.it_supply").each(function() {
                    cnt++;
                    str += $('#gwc_order_option dl dt label[for=it_supply_' + cnt + ']').text() + '>>';
                    str += $('#gwc_order_option dl dd select[id=it_supply_' + cnt + ']').val() + '>>';
                });
                if (str.indexOf('>>>>') != -1) {
                    alert('옵션을 정확히 선택해 주세요.');
                    return;
                }
            }

            if (cont_idx == 54065 && ($("input[id=gwc_conts_price]").val() == '' || $("input[id=gwc_conts_price]").val() < 30000)) {
                alert('최저가 3만원 이상입니다.');
                $("input[id=gwc_conts_price]").focus();
                return;
            }

            if (cont_idx == 54065) {
                conts_price = $("input[id=gwc_conts_price]").val();
            }
            let gwc_order_option_array = [];
            $('.gwc_order_option_number_title').each(function(e) {
                let data = {};
                data.name = $(this)[0].innerText.trim();
                data.number = $(this).parent().find('.option_number').val();
                data.opt_price = $(this).parent().find('.opt_price').val();
                gwc_order_option_array.push(data);
            });

            if (page_type == 'pay') {
                location.href = "/iam/gwc_order_pay.php?contents_idx=" + cont_idx + "&contents_cnt=" + conts_cnt + "&contents_price=" + conts_price + "&contents_salary=" + salary + "&over_salary=" + over_salary + "&seller_id=" + seller_id + "&order_option=" + str + "&gwc_order_option_content=" + JSON.stringify(gwc_order_option_array);
            } else {
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/manage_request_list.php",
                    dataType: "json",
                    data: {
                        cont_idx: cont_idx,
                        conts_cnt: conts_cnt,
                        conts_price: conts_price,
                        salary: salary,
                        seller_id: seller_id,
                        option: str,
                        mode: 'save_order_cart',
                        gwc_order_option_content: JSON.stringify(gwc_order_option_array)
                    },
                    success: function(data) {
                        location.href = "/iam/gwc_order_cart.php?contents_idx=" + cont_idx + "&contents_cnt=" + conts_cnt + "&contents_price=" + conts_price + "&contents_salary=" + salary + "&over_salary=" + over_salary + "&seller_id=" + seller_id + "&order_option=" + str;
                    }
                })
            }
        }

        function save_seller_mem_leb() {
            if ($('#check_rnum').val() == "") {
                alert('휴대폰 인증번호를 정확히 입력해 주세요.');
                return;
            }

            var seller_mem_id = '<?= $_SESSION['iam_member_id'] ?>';
            var seller_mem_phone = $("#seller_mem_phone").val();
            var seller_mem_addr = $("#seller_mem_addr").val();
            var seller_mem_bank_name = $("#seller_mem_bank_name").val();
            var seller_mem_bank_owner = $("#seller_mem_bank_owner").val();
            var seller_mem_bank_account = $("#seller_mem_bank_account").val();
            var seller_mem_email = $("#seller_mem_email").val();
            var mem_special_type = $("#mem_special_type").val();
            $.ajax({
                type: "POST",
                url: "/iam/ajax/manage_request_list.php",
                dataType: "json",
                data: {
                    mode: 'req_seller_leb_up',
                    seller_mem_id: seller_mem_id,
                    seller_mem_phone: seller_mem_phone,
                    seller_mem_addr: seller_mem_addr,
                    seller_mem_bank_name: seller_mem_bank_name,
                    seller_mem_bank_owner: seller_mem_bank_owner,
                    seller_mem_bank_account: seller_mem_bank_account,
                    seller_mem_email: seller_mem_email,
                    mem_special_type: mem_special_type
                },
                success: function(data) {
                    $("#post_content_selling_request").data("status", "Y");
                    $("#req_seller_mem_leb").modal('hide');
                    $("#contents_add_modal").attr('style', 'display: block;overflow: auto;');
                }
            });
        }

        function cancel_seller_modal() {
            $("#req_seller_mem_leb").modal('hide');
            $("#contents_add_modal").attr('style', 'display: block;overflow: auto;');
        }

        function clear_wecon_search() {
            $("#wecon_search_input").val('');
            wecon_search_clicked();
        }

        function clear_mycon_search() {
            $("#mycon_search_input").val('');
            mycon_search_clicked();
        }

        function clear_wecon_search1() {
            $("#mall_search_input").val('');
            mall_search_clicked();
        }

        function show_category() {
            $("#gwc_category_list_modal").modal('show');
        }

        let option_array = [];

        function show_order_option(con_idx, type, gwc_state) {
            var mem_id = '<?= $_SESSION['iam_member_id'] ?>';
            $("#gwc_ori_opt_price").val(0);
            option_array = [];
            $('.gwc_order_option_number')[0].innerHTML = '';
            if (mem_id == '') {
                location.href = "/iam/login.php";
                return;
            } else {
                $.ajax({
                    type: "POST",
                    url: '/ajax/get_mem_address.php',
                    data: {
                        mode: 'check_gwc_member',
                        mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.res == "0") {
                            location.href = "/iam/mypage.php?gwc_req=Y";
                            return;
                        }
                    }
                })
            }
            if (type == 'cart' && con_idx == 54065) {
                alert('캐시상품은 장바구니에 담을수 없습니다.');
                return;
            }

            if (gwc_state == 0) {
                // if(confirm('<?= $gwc_req_alarm ?>')){
                location.href = "/iam/mypage.php?gwc_req=Y";
                return;
                // }
                // else{
                //     location.reload();
                // }
            } else {
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/manage_request_list.php",
                    dataType: "json",
                    data: {
                        con_idx: con_idx,
                        mode: 'show_conts_price'
                    },
                    success: function(data) {
                        if (con_idx == '54065' && type == "pay") {
                            $("#common_gwc_prod").hide();
                            $("#cash_gwc_prod").show();
                            $("#gwc_conts_salary").hide();
                        } else {
                            $("#common_gwc_prod").show();
                            $("#cash_gwc_prod").hide();
                            if (data.salary_price == 0) {
                                var str = "배송료 무료";
                            } else {
                                if (data.over_send_salary != 0) {
                                    var str = number_format(data.salary_price) + '원';
                                    var str1 = '(' + number_format(data.over_send_salary) + '원 이상 구매시 무료배송)';
                                } else {
                                    var str = number_format(data.salary_price) + '원';
                                    var str1 = '';
                                }
                            }
                            $("#gwc_conts_price").text(number_format(data.sell_price));
                            $("#gwc_conts_salary").text(" +" + str);
                            $("#gwc_conts_over_salary").text(str1);
                            $("#gwc_conts_salary").show();
                        }
                        $("#seller_id").val(data.seller_id);
                        $("#gwc_ori_price").val(data.sell_price);
                        $("#gwc_con_name_modal").text(data.content_title);
                        $("#gwc_con_img_modal").attr('src', data.content_img);
                        $("#conts_cnt").val(1);
                        $("#gwc_conts_idx").val(con_idx);
                        $("#page_type").val(type);
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/manage_request_list.php",
                    dataType: "html",
                    data: {
                        con_idx: con_idx,
                        mode: 'show_order_data'
                    },
                    success: function(data) {
                        $("#gwc_order_option").html(data);
                        if (data.length > 3) {
                            $('.control_number')[0].style.display = 'none';
                        } else {
                            $('.control_number')[0].style.display = 'block';
                        }
                        $("#show_gwc_order_option").modal('show');
                        var opt_txt = '';
                        var sup_txt = '';
                        let opt_index = 1;

                        $("#gwc_order_option dl dd select.it_option").on('change', function() {
                            var option_val = $(this).val();
                            if (option_val == '') {
                                return;
                            }
                            $(this).find('option').each(function() {
                                if ($(this).attr('value') == option_val) {
                                    opt_txt = $(this).text();
                                }
                            });
                            opt_price = 0;
                            if (opt_txt.indexOf('(+') != -1 && opt_txt.indexOf('원)') != -1) {
                                opt_txt_arr = opt_txt.split('(+');
                                //opt_txt = opt_txt_arr[1].replace('원)', '');
                                opt_txt = opt_txt.match(/(?<=\+)(.*?)(?=원)/g)[0];
                                opt_price = opt_txt.replace(/,/g, '');
                            }

                            let cur_price_1 = parseInt($("#gwc_conts_price").text().replace(/,/g, ""));
                            $("#gwc_conts_price").text(number_format(cur_price_1 * 1 + opt_price * 1));
                            // $("#gwc_ori_opt_price").val(opt_price);

                            if (option_array.toString().indexOf(opt_txt_arr[0]) == -1) {
                                option_array.push(opt_txt_arr[0]);
                                let gwc_order_option_number_html =
                                    '<div class="gwc_order_option_number_row">' +
                                    '<p class="gwc_order_option_number_title">' + opt_txt_arr[0] + '</p>' +
                                    '<div>' +
                                    '<i class="fa fa-minus" onclick="changeNumber(event,`minus`, `gwc_order_option_number_' + opt_index + '`,`' + opt_price + '`)"></i>' +
                                    '<input type="text" value="1" class="option_number" id="gwc_order_option_number_' + opt_index + '" readonly>' +
                                    '<i class="fa fa-plus" onclick="changeNumber(event,`plus`, `gwc_order_option_number_' + opt_index + '`,`' + opt_price + '`)"></i>' +
                                    '</div>' +
                                    '<div class="d-flex"><input type="text" class="opt_price" value="' + opt_price + '" hidden><p>+' + opt_price + '원</p><p class="remove_option" onclick="remove_option(`gwc_order_option_number_' + opt_index + '`,`' + opt_txt_arr[0] + '`,`' + opt_price + '`)">&times;</p></div>' +
                                    '</div>';
                                opt_index++;
                                $('.gwc_order_option_number').append(gwc_order_option_number_html);
                                if (option_array.length > 1) {
                                    $("#gwc_conts_price").text(number_format(parseInt($("#gwc_conts_price").text().replace(',', '')) + parseInt($('#gwc_ori_price').val())));
                                    $("#conts_cnt").val(parseInt($("#conts_cnt").val()) + 1);
                                }
                            }
                        });

                        $("#gwc_order_option dl dd select.it_supply").on('change', function() {
                            var supply_val = $(this).val();
                            $(this).find('option').each(function() {
                                if ($(this).attr('value') == supply_val) {
                                    sup_txt = $(this).text();
                                }
                            })
                            if (sup_txt.indexOf('(+') != -1 && sup_txt.indexOf('원)') != -1) {
                                sup_txt_arr = sup_txt.split('(+');
                                sup_txt = sup_txt_arr[1].replace('원)', '');
                                sup_price = sup_txt.replace(/,/g, '');
                            }
                            var sup_ori_price = $("#gwc_ori_sup_price").val();
                            cur_price_1 = parseInt($("#gwc_conts_price").text().replace(/,/g, ""));
                            $("#gwc_conts_price").text(number_format(cur_price_1 * 1 + sup_price * 1 - sup_ori_price * 1));
                            $("#gwc_ori_sup_price").val(sup_price);
                        });
                    }
                })
            }
        }

        function change_count(val) {
            var cur_cnt = parseInt($("#conts_cnt").val());
            var cur_price = parseInt($("#gwc_conts_price").text().replace(/,/g, ""));
            var ori_price = parseInt($("#gwc_ori_price").val());

            // console.log(cur_cnt, cur_price, ori_price);
            if (val == "0") {
                if (cur_cnt * 1 - 1 == 0) {
                    alert('최소 구매수량은 1 이상 입니다.');
                    return;
                } else {
                    $("#conts_cnt").val(cur_cnt * 1 - 1);
                    $("#gwc_conts_price").text(number_format(cur_price - ori_price));
                }
            } else {
                if (cur_cnt * 1 + 1 == 50) {
                    alert('최대 구매수량은 50 이하 입니다.');
                    return;
                } else {
                    $("#conts_cnt").val(cur_cnt * 1 + 1);
                    $("#gwc_conts_price").text(number_format(cur_price + ori_price));
                }
            }
        }

        function cancel_order_modal() {
            $("#show_gwc_order_option").modal('hide');
        }

        function save_req_mem_leb() {
            var req_mem_id = '<?= $_SESSION['iam_member_id'] ?>';
            var req_mem_phone = $("#req_mem_phone").val();
            var req_mem_addr = $("#req_mem_addr").val();
            var req_mem_bank_name = $("#req_mem_bank_name").val();
            var req_mem_bank_owner = $("#req_mem_bank_owner").val();
            var req_mem_bank_account = $("#req_mem_bank_account").val();
            var req_mem_email = $("#req_mem_email").val();

            $.ajax({
                type: "POST",
                url: "/iam/ajax/manage_request_list.php",
                dataType: "json",
                data: {
                    mode: 'req_mem_leb_up',
                    req_mem_id: req_mem_id,
                    req_mem_phone: req_mem_phone,
                    req_mem_addr: req_mem_addr,
                    req_mem_bank_name: req_mem_bank_name,
                    req_mem_bank_owner: req_mem_bank_owner,
                    req_mem_bank_account: req_mem_bank_account,
                    req_mem_email: req_mem_email
                },
                success: function(data) {
                    alert('공급회원으로 변경 되었습니다.');
                    location.reload();
                }
            });
        }

        function cancel_req_modal() {
            $("#req_up_mem_leb").modal('hide');
        }

        function edit_paper(id) {
            $("#paper_seq").val(id);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/manage_paper_list.php",
                dataType: "json",
                data: {
                    seq: id,
                    get_data: 'Y'
                },
                success: function(data) {
                    if (checkMobile())
                        AppScript.setClearCache();
                    $("#paper_name").val(data.name);
                    $("#paper_job").val(data.job);
                    $("#paper_org").val(data.org_name);
                    $("#paper_addr").val(data.address);
                    $("#paper_phone1").val(data.phone1);
                    $("#paper_phone2").val(data.phone2);
                    $("#paper_mobile").val(data.mobile);
                    $("#paper_fax").val(data.fax);
                    $("#paper_email1").val(data.email1);
                    $("#paper_email2").val(data.email2);
                    $("#paper_memo").val(data.memo);
                }
            });
            $("#paper_list_edit_modal").modal("show");
        }

        function app_camera_paper() {
            AppScript.goCardCamera();
        }

        function delete_paper(id) {
            if (confirm('삭제하시겠습니까?')) {
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/manage_paper_list.php",
                    dataType: "json",
                    data: {
                        seq: id,
                        del: 'Y'
                    },
                    success: function(data) {
                        AppScript.setClearCache();
                        alert('삭제되었습니다.');
                        $("#paper_list_" + id).remove();
                        if ($(".paper_list").length == 0) {
                            var html = "<p style='text-align:center;font-size:17px;font-weight:900;'>종이명함이 없습니다.</p>";
                            $("#papercard_list").html(html);
                        }
                    }
                })
            }
        }

        function click_paper_tab() {
            $("#paper-tab").click();
        }

        function show_comment(seqid = "") {
            if (seqid == "")
                seqid = $("#paper_seq").val();
            $.ajax({
                type: "POST",
                url: "/iam/ajax/manage_paper_list.php",
                dataType: "html",
                data: {
                    seq: seqid,
                    comment: 'Y'
                },
                success: function(data) {
                    $("#comment_data").html(data);
                    $("#show_paper_comment").modal('show');
                }
            });
        }

        function cancel_paper() {
            $("#paper_list_edit_modal").modal("hide");
        }

        function save_paper() {
            var paper_seq = $("#paper_seq").val();
            var paper_name = $("#paper_name").val();
            var paper_job = $("#paper_job").val();
            var paper_org_name = $("#paper_org").val();
            var paper_address = $("#paper_addr").val();
            var paper_phone1 = $("#paper_phone1").val();
            var paper_phone2 = $("#paper_phone2").val();
            var paper_mobile = $("#paper_mobile").val();
            var paper_fax = $("#paper_fax").val();
            var paper_email1 = $("#paper_email1").val();
            var paper_email2 = $("#paper_email2").val();
            var paper_memo = $("#paper_memo").val();
            $.ajax({
                type: "POST",
                url: "/iam/ajax/manage_paper_list.php",
                dataType: "html",
                data: {
                    save_data: 'Y',
                    paper_seq: paper_seq,
                    paper_name: paper_name,
                    paper_job: paper_job,
                    paper_org_name: paper_org_name,
                    paper_address: paper_address,
                    paper_phone1: paper_phone1,
                    paper_phone2: paper_phone2,
                    paper_mobile: paper_mobile,
                    paper_fax: paper_fax,
                    paper_email1: paper_email1,
                    paper_email2: paper_email2,
                    paper_memo: paper_memo
                },
                success: function(data) {
                    alert("저장되었습니다.");
                    $("#paper_list_edit_modal").modal("hide");
                    //AppScript.savePaper();
                }
            });
        }

        function show_paper_img(img_link) {
            var img_tab = '<img src="' + img_link + '">';
            $("#paper_image_link").html(img_tab);
            $("#show_paper_image").modal('show');
        }

        function cancel_req(req_idx) {
            if (confirm('취소하시겠습니까?')) {
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/manage_request_list.php",
                    dataType: "json",
                    data: {
                        req_idx: req_idx,
                        req_state: 'N',
                        mode: 'update_state'
                    },
                    success: function(data) {
                        location.reload();
                    }
                })
            }
        }

        function allow_req(req_idx) {
            if (confirm('신청하시겠습니까?')) {
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/manage_request_list.php",
                    dataType: "json",
                    data: {
                        req_idx: req_idx,
                        req_state: 'Y',
                        mode: 'update_state'
                    },
                    success: function(data) {
                        location.reload();
                    }
                })
            }
        }

        function go_my_con_page(mem_id, site_iam) {
            if (site_iam == "kiam") {
                var url_do = "http://kiam.kr/?";
            } else {
                var url_do = "http://" + site_iam + ".kiam.kr/?";
            }
            $.ajax({
                type: "POST",
                url: "/iam/ajax/iam_left_logo.php",
                dataType: "json",
                data: {
                    mem_id: mem_id
                },
                success: function(data) {
                    var go_link = url_do + data.short_url + data.mem_code + "&type=image";
                    window.open(
                        go_link,
                        '_blank'
                    );
                }
            })
        }

        function close_tooptip(val) {
            if (val == 1) {
                $("#tooltiptext_card_edit").hide();
                $("#tooltiptext_upload_img").show();
                $('body,html').animate({
                    scrollTop: 0,
                }, 100);
            } else if (val == 2) {
                $("#tooltiptext_upload_img").hide();
                $("#tooltiptext_contents_up").show();
                $('body,html').animate({
                    scrollTop: 530,
                }, 100);
            } else {
                cancel_modal();
            }
        }

        function open_main_img(card_idx) {
            $('.main_image_popup').css('display', 'block');
            $("#tooltiptext_upload_img").hide();
            $("#tutorial-loading").hide();
            $("#slider_card_idx").val(card_idx);
        }

        function showCardState(val1, val) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/card_mode.proc.php",
                dataType: "json",
                data: {
                    id: '<?= $_SESSION['iam_member_id'] ?>',
                    state: val,
                    card_show: true,
                    short_url: val1
                },
                success: function(data) {
                    location.reload();
                }
            })
        }

        function start_app_install() {
            $("#tutorial_addhome_popup").modal("hide");
            $("#install-modalwindow").modal("show");
        }

        function show_all_content_images(content_idx) {
            $("#content_all_image" + content_idx).hide();
            $("#hide_content_all_image" + content_idx).show();
            var parent = $("#hide_content_all_image" + content_idx).parents(".media-inner");
            var height = parent.width();
            // height = height.replace("px","") * 1;
            if (height > window.outerHeight) {
                height = window.outerHeight;
            }
            parent.css("max-height", height);
            $("#hide_content_all_image" + content_idx).css("top", height * 1 / 2);
            $(".hidden_image" + content_idx).show();
            parent.css('overflow-y', 'scroll');
            save_load('<?= $_SESSION['iam_member_id'] ?>', content_idx, 1);
        }

        function hide_all_content_images(content_idx) {
            $("#content_all_image" + content_idx).show();
            $("#hide_content_all_image" + content_idx).hide();
            $(".hidden_image" + content_idx).hide();
            parent.css('overflow-y', 'hidden');
            var parent = $("#hide_content_all_image" + content_idx).parents(".media-inner");
            parent.css("max-height", 1000);
        }

        function installApp() {
            /*var navCase = navigator.userAgent.toLocaleLowerCase();
             if(navCase.search("android") > -1){
             location.href = "https://play.google.com/store/apps/details?id=mms.onepagebook.com.onlyonesms";
             }
             else{
             alert("휴대폰에서 이용해주세요.");
             }*/
            location.href = "?cur_win=best_sample";
        }

        function show_post(content_idx) {
            //var recent_post = $("#recent_post").val();
            /*if(recent_post == 0){
                $.ajax({
                    type:"POST",
                    url:"/iam/ajax/ajax.v1.php",
                    dataType:"json",
                    data:{
                        post_alert : "Y",
                        mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                    },
                    success:function(data){
                        $("#recent_post").val(1);
                    },
                    error: function(data){
                    }
                });
                //$("#post_popup").modal("show");
                //$("#post_popup").find("#content_idx").val(content_idx);
            }*/
            //else{
            //$(".post-wrap").hide();
            if ($(".post_wrap" + content_idx).css('display') == "none") {
                $(".post_wrap" + content_idx).show();
                $("#show_post_img_" + content_idx).prop("src", "/iam/img/menu/icon_post_active.png");
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/add_post.php",
                    dataType: "json",
                    data: {
                        content_idx: content_idx,
                        mode: 'read',
                        mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                    },
                    success: function(data) {
                        if (data.result == 'success') {
                            $('#post_alarm_' + content_idx).hide();
                            reloadShareCount(data.share_recv_count, data.share_send_count, data.share_post_count);
                        }
                    }
                });
            } else {
                $(".post_wrap" + content_idx).hide();
                $("#show_post_img_" + content_idx).prop("src", "/iam/img/menu/icon_post.png");
            }
            //}
        }

        function show_next_iam(link) {
            if (link != "") {
                window.open(link);
            }
        }

        function show_mc_pay_popup() {
            $("#mc_payment_popup").modal("show");
        }

        function show_pay_popup(service_type, link) {
            $("#payment_popup").modal("show");
            if (service_type == 0 || service_type == 3) {
                if (link)
                    $("#payment_popup_close").prop("href", link);
            }
        }

        function hide_pay_popup(service_type) {
            $("#payment_popup").modal("hide");
            $("#mc_payment_popup").modal("hide");
            if (service_type == 0 || service_type == 3)
                show_create_card_popup();
        }

        function hide_post_popup() {
            $("#post_popup").modal("hide");
            var content_idx = $("#post_popup").find("#content_idx").val();
            if ($(".post_wrap" + content_idx).css('display') == "none") {
                $(".post_wrap" + content_idx).show();
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/add_post.php",
                    dataType: "json",
                    data: {
                        content_idx: content_idx,
                        mode: 'read',
                        mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                    },
                    success: function(data) {
                        if (data.result == 'Y') {
                            $('#post_alarm_' + content_idx).hide();
                        }
                    }
                });
            } else
                $(".post_wrap" + content_idx).hide();

        }

        function hide_service_contents_popup() {
            $("#service_contents_popup").modal("hide");
            $("#service_contents_popup_ok").attr("href", "javascript:post_contents(4)");
        }
        //var share_kind = 0;
        // 메인 슬라이더 스크립트
        $(function() {
            show_exp_popup($("#exp_status").val());
            $('#mainSlider').slick({
                autoplay: true,
                infinite: false,
                appendArrows: $('.slider-arrows'),
                prevArrow: '<button class="arrows prev"><i class="fa fa-chevron-circle-left" aria-hidden="true" style="font-size:18px"></i></button>',
                nextArrow: '<button class="arrows next"><i class="fa fa-chevron-circle-right" aria-hidden="true" style="font-size:18px"></i></button>'
            });
            var innerImgFrame = $('.slick-track');
            var innerImgFrame2 = $('.slider-item');
            var innerImgSize = parseInt(innerImgFrame2.height());
            innerImgSize = innerImgSize == 1 ? 575 : innerImgSize;
            innerImgFrame.css({
                'height': innerImgSize,
                'line-height': innerImgSize + 'px'
            });
            var page_url = window.location.href;
            var page_id = page_url.substring(page_url.lastIndexOf("#") + 1);
            var page_id2 = '<?= $contents_anchor ?>';
            if (page_id2) {
                $('html, body').animate({
                    scrollTop: $('#' + page_id2).offset().top
                }, 500);
            } else {
                if (page_url.indexOf("#") !== -1) {
                    if (page_id) {
                        $('html, body').animate({
                            scrollTop: $('#' + page_id).offset().top
                        }, 500);
                    }
                }
            }
        });
        // end

        $("#item_count").on('change', function() {
            var settle_cash = $("#settle_point_cash1").val();
            var sale_cnt = $("#sale_cnt").val();
            if (sale_cnt == '0') {
                var item_count = $("#item_count").val();
                if (item_count == "" || item_count == 0) {
                    $("#settle_point_cash").val(settle_cash);
                } else {
                    $("#settle_point_cash").val(settle_cash * $("#item_count").val());
                }
            } else {
                var item_count = $("#item_count").val();
                if (item_count > sale_cnt) {
                    $("#item_count").val(sale_cnt);
                }
                if (item_count == "" || item_count == 0) {
                    $("#settle_point_cash").val(settle_cash);
                } else {
                    $("#settle_point_cash").val(settle_cash * $("#item_count").val());
                }
            }
        });

        $("#item_count").keyup(function() {
            var settle_cash = $("#settle_point_cash1").val();
            var sale_cnt = $("#sale_cnt").val();
            if (sale_cnt == '0') {
                var item_count = $("#item_count").val();
                if (item_count == "" || item_count == 0) {
                    $("#settle_point_cash").val(settle_cash);
                } else {
                    $("#settle_point_cash").val(settle_cash * $("#item_count").val());
                }
            } else {
                var item_count = $("#item_count").val();
                if (item_count > sale_cnt) {
                    $("#item_count").val(sale_cnt);
                }
                if (item_count == "" || item_count == 0) {
                    $("#settle_point_cash").val(settle_cash);
                } else {
                    $("#settle_point_cash").val(settle_cash * $("#item_count").val());
                }
            }
        });

        // ######re####
        $(window).ready(function() {
            var t_width = $('.slick-list').width();
            var t_height = Math.floor(t_width / 6 * 4);
            $(".slick-track").css("height", t_height);

            var win_width = $(window).width();
            var wrap_width = $("#wrap").width();
            var right = Math.floor((win_width - wrap_width) / 2) + 30;

            $("#scroll_down").attr('style', 'position: fixed;bottom: 60px;right: ' + right + 'px;z-index: 997;width: 55px;height: 55px;background-color: unset;');
        });
        $(window).resize(function() {
            var t_width = $('.slick-list').width();
            var t_height = Math.floor(t_width / 6 * 4);
            $(".slick-track").css("height", t_height);

            var win_width = $(window).width();
            var wrap_width = $("#wrap").width();
            var right = Math.floor((win_width - wrap_width) / 2) + 30;

            $("#scroll_down").attr('style', 'position: fixed;bottom: 60px;right: ' + right + 'px;z-index: 997;width: 55px;height: 55px;background-color: unset;');
        });
        // 아이엠스토에 페이지 상품 보기
        function show_iamstore_prod(val) {
            var type = getCookie('contents_mode');
            var gwc_mem_id = '<?= $_SESSION['iam_member_id'] ?>';
            var gwc_pay_mem = '<?= $gwc_pay_mem ?>';
            if (val == "C") {
                if (gwc_mem_id == '') {
                    location.href = '/iam/login.php';
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: '/ajax/get_mem_address.php',
                    data: {
                        mode: 'check_gwc_member',
                        mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.res == "0") {
                            alert(data.msg.replace(/<br>/g, "\n"));
                            $("#req_up_mem_leb").modal('show');
                            return;
                        } else {
                            if (data.cont_cnt == '0') {
                                alert("현재 IAMSTORE에서 가져온 상품이 없습니다.\n회원님의 익월 소비금액이 있으면 지금 상품을 가져오시면 됩니다.\n소비금액이 없으면 프로슈머 굿마켓의 미션달성을 위해 소비활동을 여기서 해주시기바랍니다.");
                                // return;
                            }
                            // else{
                            location.href = "?" + "<?= $request_short_url . $card_owner_code ?>&cur_win=we_story&type=" + type + "&search_key=" + "<?= $_GET['search_key'] ?>" + "&key1=" + "<?= $_GET['key1'] ?>" + "&key2=" + "<?= $_GET['key2'] ?>" + "&key3=" + "<?= $_GET['key3'] ?>" + "&sort_key3=" + "<?= $_GET['sort_key3'] ?>" + "&key4=1&iamstore=" + val + "&wide=" + "<?= $_GET['wide'] ?>";
                            // }
                        }
                    }
                })
            } else if (val == "R") {
                location.href = "?" + "<?= $request_short_url . $card_owner_code ?>&cur_win=we_story&type=" + type + "&search_key=" + "<?= $_GET['search_key'] ?>" + "&key1=" + "<?= $_GET['key1'] ?>" + "&key2=" + "<?= $_GET['key2'] ?>" + "&key3=" + "<?= $_GET['key3'] ?>" + "&sort_key3=" + "<?= $_GET['sort_key3'] ?>" + "&key4=1&iamstore=<?= $_GET['sort_key3'] ?>&req_provide=Y";
            } else {
                if (gwc_mem_id == '') {
                    location.href = '/iam/login.php';
                    return;
                }
                if (gwc_pay_mem == '1') {
                    location.href = "?" + "<?= $request_short_url . $card_owner_code ?>&cur_win=we_story&type=" + type + "&search_key=" + "<?= $_GET['search_key'] ?>" + "&key1=" + "<?= $_GET['key1'] ?>" + "&key2=" + "<?= $_GET['key2'] ?>" + "&key3=" + "<?= $_GET['key3'] ?>" + "&sort_key3=" + "<?= $_GET['sort_key3'] ?>" + "&key4=1&iamstore=" + val + "&wide=" + "<?= $_GET['wide'] ?>";
                } else {
                    alert('2만원이상 구매회원에게만 보여줍니다.');
                    return;
                }
            }
        }

        // 아이엠스토에 페이지 카테고리별 상품 보기
        function show_gwc_cate_prod(val) {
            var type = getCookie('contents_mode');
            var sort_key3 = Math.floor(Math.random() * 4);
            <? if ($_GET['iamstore'] == "C") { ?>
                var iamstore = "Y";
            <? } else { ?>
                var iamstore = "<?= $_GET['iamstore'] ?>";
            <? } ?>
            if (val == '1268514') {
                iamstore = "N";
                if ($("#recom_gwc_item").attr('style').indexOf('#99cc00') == -1) {
                    location.href = "?" + "<?= $request_short_url . $card_owner_code ?>&cur_win=we_story&type=" + type + "&search_key=" + "<?= $_GET['search_key'] ?>" + "&key1=" + "<?= $_GET['key1'] ?>" + "&key2=" + "<?= $_GET['key2'] ?>" + "&key3=" + "<?= $_GET['key3'] ?>" + "&sort_key3=" + sort_key3 + "&key4=1&iamstore=" + iamstore + "&cate_prod=" + val + "&wide=" + "<?= $_GET['wide'] ?>";
                } else {
                    location.href = "?" + "<?= $request_short_url . $card_owner_code ?>&cur_win=we_story&type=" + type + "&search_key=" + "<?= $_GET['search_key'] ?>" + "&key1=" + "<?= $_GET['key1'] ?>" + "&key2=" + "<?= $_GET['key2'] ?>" + "&key3=" + "<?= $_GET['key3'] ?>" + "&sort_key3=" + sort_key3 + "&key4=1&iamstore=" + iamstore + "&cate_prod=" + "&wide=" + "<?= $_GET['wide'] ?>";
                }
            } else {
                location.href = "?" + "<?= $request_short_url . $card_owner_code ?>&cur_win=we_story&type=" + type + "&search_key=" + "<?= $_GET['search_key'] ?>" + "&key1=" + "<?= $_GET['key1'] ?>" + "&key2=" + "<?= $_GET['key2'] ?>" + "&key3=" + "<?= $_GET['key3'] ?>" + "&sort_key3=" + sort_key3 + "&key4=1&iamstore=" + iamstore + "&cate_prod=" + val + "&wide=" + "<?= $_GET['wide'] ?>";
            }
        }

        function gwc_tab() {
            var type = getCookie('contents_mode');
            var sort_key3 = Math.floor(Math.random() * 4);
            location.href = "?" + "<?= $request_short_url . $card_owner_code ?>&cur_win=we_story&type=pin&search_key=" + "<?= $_GET['search_key'] ?>" + "&key1=4&key2=" + "<?= $_GET['key2'] ?>" + "&key3=0&sort_key3=" + sort_key3 + "&key4=1&iamstore=N" + "&wide=Y";
        }

        function callya_tab() {
            var type = getCookie('contents_mode');
            sort_key3 = 1;
            location.href = "?" + "<?= $request_short_url . $card_owner_code ?>&cur_win=we_story&type=" + type + "&search_key=" + "<?= $_GET['search_key'] ?>" + "&key1=3&key2=" + "<?= $_GET['key2'] ?>" + "&key3=0&sort_key3=" + sort_key3;
        }

        //네이버지도방문 콘텐츠 보기
        function show_map_address(card_idx) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/show_naver_map.php",
                data: {
                    type: "address",
                    card_idx: card_idx
                },
                dataType: 'text',
                success: function(data) {
                    $("#naver_map_address").html(data);
                    $("#show_navermap_address").modal("show");
                }
            });
        }

        function show_map_distance(card_idx) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/show_naver_map.php",
                data: {
                    type: "distance",
                    card_idx: card_idx
                },
                dataType: 'text',
                success: function(data) {
                    var addr = data.split('@');
                    var pos = addr[1].split('&');
                    var win = window.open("https://m.search.naver.com/search.naver?query=빠른길찾기&nso_path=placeType%5Eplace%3Bname%5E%3Baddress%5E%3Blongitude%5E%3Blatitude%5E%3Bcode%5E%7Ctype%5Eplace%3Bname%5E" + addr[0] + "%3Baddress%5E%3Blongitude%5E" + pos[1] + "%3Blatitude%5E" + pos[0] + "%3Bcode%5E%7Cobjtype%5Epath%3Bby%5Epubtrans", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=700,height=800");
                }
            });
        }

        function calc_distance() {
            var start_pos = $("#start_pos").val();
            if (start_pos == "") {
                alert("출발점을 입력해 주세요.");
                $("#start_pos").focus();
                return;
            }
            fetch("https://maps.googleapis.com/maps/api/geocode/json?address=" + start_pos + "&key=AIzaSyBOti4mM-6x9WDnZIjIeyEU21OpBXqWBgw")
                .then(response => response.json())
                .then(data => {
                    var lat1 = data.results[0].geometry;
                    var latitude = lat1.location.lat;
                    var lng = data.results[0].geometry;
                    var longitude = lng.location.lng;
                    var ret_val = latitude + "&" + longitude;
                    var end_info = $("#map_pos").val();
                    $.ajax({
                        type: "POST",
                        url: "/iam/ajax/show_naver_map.php",
                        dataType: "json",
                        data: {
                            start_info: ret_val,
                            type: 'distance_calc',
                            end_info: end_info
                        },
                        success: function(data) {
                            $("#distance_val").val(data);
                        }
                    })
                })
        }

        function show_map_position(card_idx) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/show_naver_map.php",
                data: {
                    type: "show_map",
                    card_idx: card_idx
                },
                dataType: 'text',
                success: function(data) {
                    var win = window.open("https://m.map.naver.com/search2/search.naver?query=" + data + "&sm=hty&style=v5", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=700,height=800");
                }
            });
        }

        function set_reduce(card_idx, sale_cnt, event_val, fixed_val) {
            $("#card_idx_percent").val(card_idx);
            $("#reduction_percent_event").val(event_val);
            $("#reduction_percent_cnt").val(sale_cnt);
            $("#reduction_percent_fixed").val(fixed_val);
            if (event_val == 0 && sale_cnt == 0) {
                $("#event_type_setbtn").text("저장하기");
            } else {
                $("#event_type_setbtn").text("수정하기");
            }
            if (fixed_val == 0) {
                $("#fixed_type_setbtn").text("저장하기");
            } else {
                $("#fixed_type_setbtn").text("수정하기");
            }
            $("#set_reduction_modal").modal("show");
        }

        function save_set_reduce(type) {
            var fixed = $('#reduction_percent_fixed').val();
            var event_val = $('#reduction_percent_event').val();
            var event_cnt = $('#reduction_percent_cnt').val();
            var card_idx = $("#card_idx_percent").val();

            if (type == "event") {
                if (event_val != 0 && event_cnt < 5) {
                    alert("5건 이상 입력해주세요.");
                    return;
                }
                if ((event_val != 0 && event_val != "") && (event_cnt == "" || event_cnt == 0)) {
                    alert("할인건수를 입력해 주세요.");
                    return;
                }
                if ((event_val == 0 || event_val == "") && (event_cnt != "" && event_cnt != 0)) {
                    alert("할인율을 입력해 주세요.");
                    return;
                }
            }
            if (type == "fixed") {
                var str = "고정형 설정은 모든 콘텐츠에 동일하게 적용됩니다. 또한 기존 입력된 할인율은 현재 입력값으로 변환됩니다. 적용하시겠습니까?";

                if (!confirm(str)) {
                    return;
                }
            }

            $.ajax({
                type: "POST",
                url: "/iam/ajax/show_naver_map.php",
                data: {
                    type: type,
                    fixed: fixed,
                    event_val: event_val,
                    event_cnt: event_cnt,
                    card_idx: card_idx
                },
                dataType: 'json',
                success: function(data) {
                    alert("설정 되었습니다.");
                    //location.reload();
                }
            });
        }
        /*function colse_reduce(){
            $("#set_reduction_modal").modal('hide');
            location.reload();
        }*/
        function show_map_busitime(card_idx) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/show_naver_map.php",
                data: {
                    type: "busi_time",
                    card_idx: card_idx
                },
                dataType: 'text',
                success: function(data) {
                    $("#timedata").html(data);
                    $("#show_navermap_busitime").modal("show");
                }
            });
        }

        function edit_busitime() {
            var cont_idx = $("#cur_con_idx_time").val();
            $.ajax({
                type: "POST",
                url: "/iam/ajax/show_naver_map.php",
                data: {
                    type: "edit_busi_time",
                    cont_idx: cont_idx
                },
                dataType: 'text',
                success: function(data) {
                    console.log(data);
                    location.href = '?' + data + '&edit_time=Y';
                }
            });
        }

        function price_help_show() {
            alert('<?= $contents_point_help ?>');
        }

        function price_help_show_gwc() {
            var msg = "굿마켓은 굿웰스클럽 회원들이 이용하는 멤버쉽 마켓입니다. 이 굿웰스마켓은 IAM플랫폼과 제휴를 통해 회원들이 소비와 공급을 의무적으로 수행하며 세계적인 유통불균형을 해소하고자 합니다. 홈피 : gwclub.obmms.net";
            if (confirm(msg)) {
                location.href = "http://gwclub.obmms.net";
            }
        }

        // 콘텐츠박스 설명글 접기 펼치기 스크립트
        function show_more_contents_modal() {
            if ($('#contents_desc').css("min-height") == "100px") {
                $('#contents_desc').css("min-height", "");
            } else {
                $('#contents_desc').css("min-height", "100px")
            }
        }
        //자동아이엠만들기 스크립트 시작
        function create_auto_card() {
            $('#mypage-modalwindow').modal('hide');
            $('#people_iam_modal').modal('show');
        }
        //새계정 만들기 현시prop("disabled", false)
        function newaccount() {
            var web_type = $("input[name=web_type]:checked").attr("id");
            $("#newmake").show();
            $("#mineid").hide();
            $("#cardsel").hide();
            $("#blog_mem_name").prop("disabled", false);
            $("#mem_name").prop("hidden", false);
            $("#phonenum").prop("hidden", false);
            $("#mem_company").prop("hidden", false);
            $("#mem_address").prop("hidden", false);
            if (web_type == 'mapid' || web_type == 'gmarketid' || web_type == 'navershop') {
                $("#blog_mem_name").prop("disabled", true);
                $("#mem_name").prop("hidden", true);
                $("#phonenum").prop("hidden", true);
                $("#mem_company").prop("hidden", true);
                $("#mem_address").prop("hidden", true);
            }
        }

        //내 아이디로 만들기 현시
        function myIDmake() {
            var web_type = $("input[name=web_type]:checked").attr("id");
            $("#newmake").hide();
            $("#mineid").show();
            $("#cardsel").hide();
            $("#blog_mem_name").prop("disabled", false);
            $("#mem_name").prop("hidden", false);
            $("#phonenum").prop("hidden", false);
            $("#mem_company").prop("hidden", false);
            $("#mem_address").prop("hidden", false);
            if (web_type == 'mapid' || web_type == 'gmarketid' || web_type == 'navershop') {
                $("#blog_mem_name").prop("disabled", true);
                $("#mem_name").prop("hidden", true);
                $("#phonenum").prop("hidden", true);
                $("#mem_company").prop("hidden", true);
                $("#mem_address").prop("hidden", true);
            }
        }

        //카드선택 추가하기 현시
        function selectcard() {
            $("#newmake").hide();
            $("#mineid").hide();
            $("#cardsel").show();
            $("#blog_mem_name").prop("disabled", true);
            $("#mem_name").prop("hidden", true);
            $("#phonenum").prop("hidden", true);
            $("#mem_company").prop("hidden", true);
            $("#mem_address").prop("hidden", true);
        }

        //자동아이엠 만들기, 오토데이트 기능을 위한 포인트 결제하기
        var ele;
        var auto = 0;

        function mutongjang() {
            $('#people_iam_modal').modal('hide');
            $('#mutong_settle').modal('show');
        }

        function point_chung() {
            $('#people_iam_modal').modal('hide');
            $('#card_send_modal').modal('hide');
            $('#contents_send_modal').modal('hide');
            $('#cashtoseed_settlement').modal('show');
        }

        function point_chung1() {
            $('#intro_auto_update').modal('hide');
            $('#cashtoseed_settlement').modal('show');
        }

        function cashtoseed_chung(val) {
            var cash_point = <?= $Gn_cash_point ?>;
            var seed_point = $("#money_point_cashtoseed").val();
            if (seed_point == '') {
                alert("포인트를 입력해 주세요.");
                return;
            }
            if (cash_point < seed_point) {
                alert("캐시포인트가 부족합니다. 충전해 주세요.");
                return;
            }
            if (confirm("캐시포인트에서 씨드포인트로 전환하시겠습니까?")) {
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/apply_service_con_res.php",
                    data: {
                        payMethod: "<?= $_SESSION['iam_member_id'] ?>",
                        member_type: "씨드포인트충전",
                        allat_amt: seed_point,
                        pay_percent: 90,
                        allat_order_no: <?= $mid ?>,
                        point_val: 1,
                        mode: "cashtoseed",
                        seed_point: seed_point
                    },
                    dataType: 'json',
                    success: function(data) {
                        alert("캐시포인트 " + seed_point + "P 가 씨드포인트로 전환되었습니다.");
                        location.reload();
                    }
                });
            }
        }

        function card_settle(val) {
            item_price = $("#money_point").val();
            location.href = "pay_point.php?item_price=" + item_price + "&url=" + val;
        }

        function bank_settle() {
            item_price = $("#money_point").val();
            $.ajax({
                type: "POST",
                url: "/makeData_item.php",
                data: {
                    payMethod: "BANK",
                    member_type: "포인트충전",
                    allat_amt: item_price,
                    pay_percent: 90,
                    allat_order_no: <?= $mid ?>,
                    point_val: 1
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                }
            });
            $("#mutong_settlement").modal('hide');
            $("#mutong_settle").modal('show');
        }

        function point_settle_modal(price, title, url, sell_id, sale_cnt, salary_price) {
            link = "http://<?= $HTTP_HOST ?>/iam/contents.php?contents_idx=" + url;
            $("#settle_point_cash").val(price);
            $("#settle_point_cash1").val(price);
            $("#sell_con_title_cash").val(title);
            $("#sell_con_url_cash").val(link);
            $("#sell_con_id_cash").val(sell_id);
            $("#send_salary_price1").val(salary_price);
            $("#item_count").val('1');
            if (salary_price != 0) {
                $("#send_salary_price_show").html(salary_price);
                $("#salary_price_show").show();
            } else {
                $("#salary_price_show").hide();
            }
            if (sale_cnt == '0') {
                $("#sale_cnt").val(sale_cnt);
            } else {
                $("#sale_cnt").val(sale_cnt);
                $("#con_sale_cnt").text(sale_cnt);
                $("#con_sale_cnt1").text(sale_cnt);
                $("#state_sale_cnt").show();
            }
            $("#point_settlement_cash").modal('show');
        }

        function point_mall_modal(type, price, data) {
            $("#settle_point").val(price);
            $("#point_pay_data").val(data);
            $("#point_pay_type").val(type);
            $("#point_settlement").modal('show');
        }

        function point_settle_cash() {
            var item_price = $("#settle_point_cash").val() * $("#sale_cnt").val();
            var salary_price = $("#send_salary_price1").val();
            item_price += salary_price * 1;
            var current_cash = <?= $Gn_cash_point ?>;
            if (item_price > current_cash) {
                alert("캐시포인트가 부족합니다. 포인트 충전해주세요");
                return;
            }
            var item_title = $("#sell_con_title_cash").val();
            var mall_idx = $("#sell_con_url_cash").val();
            //var seller_id = $("#sell_con_id_cash").val();
            var cash_method = $("#contents_send_type_cash").val();
            var member_type = '서비스콘텐츠/' + item_title;
            if (cash_method == 5) {
                member_type = "메시지세트구매/" + item_title;
                var formData = new FormData();
                formData.append("pay_type", "message_set");
                formData.append("pay_item", member_type);
                formData.append("allat_amt", item_price);
                formData.append("month_cnt", 120);
                formData.append("item_idx", mall_idx);
                formData.append("buyer", $("#settle_buyer").val);
                $.ajax({
                    type: "POST",
                    url: "/ajax/ajax_point_payment.php",
                    data: {
                        pay_type: "message_set",
                        pay_item: member_type,
                        allat_amt: item_price,
                        month_cnt: 120,
                        item_idx: mall_idx,
                        buyer: $("#settle_buyer").val()
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.result == 1) {
                            alert("구매되었습니다.");
                        } else {
                            alert("구매가 실패되었습니다.");
                        }
                        location.reload();
                    },
                    error: function(request, status, error) {
                        alert(request + "=>" + status + ">>>" + error);
                    }
                });
            }

        }

        function point_settle() {
            var item_price = $("#settle_point").val();
            var item_title = $("#sell_con_title").val();
            var item_url = $("#sell_con_url").val();
            var item_id = $("#sell_con_id").val();
            var item_cnt = $("#item_count").val();
            var current_point = <?= $Gn_point ?>;
            var member_type = '서비스콘텐츠/' + item_title;
            var method = item_id;
            var contents_url = item_url;
            var settle_type = "";
            if (item_price > current_point) {
                alert("씨드포인트가 부족합니다. 포인트 충전해주세요");
                return;
            }
            var point_pay_type = $("#point_pay_type").val(); //0:서비스콘,1:아이엠몰,2:카드몰,3:콘텐츠몰,4:직거래몰,5:카드전송,6:콘텐츠전송,8:그룹카드
            if (point_pay_type == 0 || point_pay_type == 4) {
                if (point_pay_type == 4)
                    member_type = "IAM몰 직거래/" + item_title;
                settle_type = "service_con";
                $.ajax({
                    type: "POST",
                    url: "/makeData_item_point.php",
                    data: {
                        payMethod: method,
                        member_type: member_type,
                        allat_amt: item_price,
                        pay_percent: 90,
                        allat_order_no: <?= $mid ?>,
                        point_val: 1,
                        service: true,
                        contents_url: contents_url,
                        item_cnt: item_cnt
                    },
                    dataType: 'json',
                    success: function(data) {
                        alert("구매되었습니다.");
                        location.reload();
                    },
                    error: function(request, status, error) {
                        alert(request + "=>" + status + ">>>" + error);
                    }
                });
            } else if (point_pay_type == 5) { //카드전송
                settle_type = "card_send";
                member_type = $("#point_pay_data").val();
                member_ids = $("#card_send_id").val();
                item_price = <?= $card_send_point ?>;
                message = $("#alarm_msg").val();
                var card_send_mode = $("#card_send_mode").val();
                $.ajax({
                    type: "POST",
                    url: "/iam/card_con_send.php",
                    data: {
                        settle_type: settle_type,
                        payMethod: member_ids,
                        member_type: member_type,
                        allat_amt: item_price,
                        pay_percent: 90,
                        allat_order_no: <?= $mid ?>,
                        point_val: 1,
                        card_url: '<?= $card_url ?>',
                        message: message,
                        card_send_mode: card_send_mode
                    },
                    dataType: 'json',
                    success: function(data) {
                        alert("전송되었습니다.");
                        location.reload();
                    }
                });
            } else if (point_pay_type == 6) { //콘텐츠전송
                settle_type = "contents_send";
                member_type = $("#point_pay_data").val();
                member_ids = $("#contents_send_id").val();
                item_price = <?= $contents_send_point ?>;
                message = $("#alarm_msg1").val();
                send_type = $("#contents_send_type").val(); //1:알림형 2:수신형
                $.ajax({
                    type: "POST",
                    url: "/iam/card_con_send.php",
                    data: {
                        settle_type: settle_type,
                        payMethod: member_ids,
                        member_type: member_type,
                        allat_amt: item_price,
                        pay_percent: 90,
                        allat_order_no: <?= $mid ?>,
                        point_val: 1,
                        send_type: send_type,
                        message: message
                    },
                    dataType: 'json',
                    success: function(data) {
                        alert("전송되었습니다.");
                        location.reload();
                    }
                });
            } else if ($("#point_pay_type").val() == 8) { //그룹카드만들기
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/group.proc.php",
                    data: {
                        method: "create_group_card",
                        price: item_price,
                        card_title: item_title,
                        group_id: item_id,
                        pay_percent: 90,
                        allat_order_no: <?= $mid ?>
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.result == "success") {
                            alert("카드가 등록되었습니다.");
                            location.reload();
                        }
                    }
                });
            } else if ($("#point_pay_type").val() == 9) { //공지사항 전송하기
                settle_type = "notice_send";
                member_ids = $("#notice_send_id").val();
                title = $("#notice_title").val();
                message = $("#notice_desc").val();
                notice_link = $("#notice_link").val();
                $.ajax({
                    type: "POST",
                    url: "/iam/card_con_send.php",
                    data: {
                        settle_type: settle_type,
                        payMethod: member_ids,
                        pay_percent: 90,
                        allat_order_no: <?= $mid ?>,
                        point_val: 3,
                        message: message,
                        title: title,
                        notice_link: notice_link
                    },
                    dataType: 'json',
                    success: function(data) {
                        alert("전송되었습니다.");
                        location.reload();
                    }
                });
            } else {
                var data = $("#point_pay_data").val();
                var mall_pay_type = data.substring(0, 1) * 1;
                console.log(data + '>>', mall_pay_type);
                return;
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/mall.proc.php",
                    data: {
                        iam_mall_method: "pay_mall",
                        iam_mall_pay_data: data,
                        iam_mall_sell_price: item_price,
                        pay_percent: 90,
                        allat_order_no: <?= $mid ?>
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.result == "ok") {
                            alert("구매되었습니다.");
                            location.href = data.link;
                        }
                    }
                });
            }
        }

        function settlement(val, frm, auto_up) {
            if (val == 'set') {
                <?php if ($_SESSION['iam_member_id'] == "") { ?>
                    window.location = '/iam/join.php';
                <? } else { ?>
                    $('#people_iam_modal').modal('hide');
                    $('#settlement_finish_modal').modal('hide');
                    $('#settlement_modal').modal('show');
                <? } ?>
            } else if (val == "auto") {
                $("#intro_auto_update").modal("hide");
                $("#auto_settlement_modal").modal("show");
            } else if (val == 'finish') {
                var memberType = "";
                var con_cnt = 0;
                var ele = 0;
                var item_type = $('input[name=make_iam]:checked').val();
                var settlment_type = $('input[name=pay_type]:checked').val();
                // var auto_type_bank = false;
                // var auto_type_card = false;
                var type_card = false;
                var type_bank = false;

                // var settlment_type_auto = $('input[name=pay_type_auto]:checked').val();
                if (auto_up == "auto") {
                    auto = 1;
                    item_type = "auto";
                    memberType = "오토데이트 포인트 충전";
                    ele = $("#auto_point").val();
                    con_cnt = 1;
                    type_bank = $("#bank_type_auto").prop('checked');
                    type_card = $("#card_type_auto").prop('checked');
                } else {
                    $("input[name=make_iam]:checked").each(function() {
                        var idVal = $(this).attr("id");
                        ele = $("label[for='" + idVal + "']").attr('value');
                    });
                    if (ele == 33000) {
                        memberType = 'IAM 1건 만들기';
                        con_cnt = 1;
                    } else if (ele == 132000) {
                        memberType = 'IAM 10건 만들기';
                        con_cnt = 10;
                    } else if (ele == 297000) {
                        memberType = 'IAM 30건 만들기';
                        con_cnt = 30;
                    } else if (ele == 660000) {
                        memberType = 'IAM 100건 만들기';
                        con_cnt = 100;
                    }
                    type_card = $("#card_type").prop('checked');
                    type_bank = $("#bank_type").prop('checked');
                }
                if (item_type == "" || item_type == undefined) {
                    toastr.error('상품을 선택 하세요.');
                    return false;
                }
                if (settlment_type == "" || settlment_type == undefined) {
                    toastr.error('결제종류를 선택 하세요.');
                    return false;
                }

                $('#month_cnt').val('12');
                $('#price').val(ele);
                $('#total_amount').text(ele);
                $('#onestep1').val("ON");
                $('#onestep2').val("ON");
                console.log(ele);

                $('#member_type').val(memberType);
                $('#add_phone').val(con_cnt);
                $('#db_cnt').val(9000);
                if (!frm.mid.value) {
                    toastr.error('결제종류를 선택해주세요.');
                    return false;
                }
                if (confirm('결제시작하시겠습니까?')) {
                    if (type_bank == true) {
                        //frm.target='pay_iframe';
                        $.ajax({
                            type: "POST",
                            url: "/pay_cash_people.php",
                            dataType: "json",
                            data: $('#pay_form').serialize(),
                            success: function(data) {
                                console.log("mutongjang!!!!!!");
                                if (data == 1) {
                                    if (auto == 1) {
                                        auto_point_chung('<?= $_SESSION['iam_member_id'] ?>', 'bank', ele);
                                    } else {
                                        iam_item('<?= $_SESSION['iam_member_id'] ?>', 'buy', 'bank');
                                    }
                                }
                            }
                        });
                        return;
                    } else if (type_card == true) {
                        location.href = "/iam/pay_spoc.php?itemname=" + memberType + "&totprice=" + ele;
                    }
                }
            } else if (val == 'make') {
                <?php if ($_SESSION['iam_member_id'] == "") { ?>
                    window.location = '/iam/login.php';
                <? } else if ($Gn_point < $point_ai) { ?>
                    toastr.error("포인트 잔액이 없습니다. 결제하신후에 사용하세요.");
                <? } else { ?>
                    $('#people_iam_modal').modal('hide');
                    $('#settlement_finish_modal').modal('hide');
                    $('#auto_making_modal').modal('show');
                <? } ?>
            } else if (val == "card_send") {
                $('#card_send_modal').modal('hide');
                $("#card_send_id").val('<?= $send_ids ?>');
                $("#card_send_id_count").val(<?= $send_ids_cnt ?> + '건');
                $('#card_send_id_count').data('num', <?= $send_ids_cnt ?>);
                $('#card_send_modal_mem').modal('show');
            } else if (val == "contents_send") {
                $('#contents_send_modal').modal('hide');
                $("#contents_send_id").val('<?= $send_ids ?>');
                $("#contents_send_id_count").val(<?= $send_ids_cnt ?> + '건');
                $('#contents_send_id_count').data('num', <?= $send_ids_cnt ?>);
                $('#contents_send_modal_mem').modal('show');
            }
        }

        function show_con_send(val) {
            $('#contents_send_modal').modal('show');
            $("#point_pay_type").val(6);
            $("#point_pay_data").val(val);
        }

        //결제/이용내역 팝업 현시
        function show_contents() {
            $('#people_iam_modal').modal('hide');
            $('#card_send_modal').modal('hide');
            $('#contents_send_modal').modal('hide');
            $('#settlement_contents_modal').modal('show');
            search_people('search');
        }

        //분야별에 따르는 아이엠 자동생성 시작
        function start_making() {
            var web_type = $('input[name=web_type]:checked').attr('id');
            if (web_type == undefined) {
                alert('수집분야를 설정 하세요.');
                return;
            }
            switch (web_type) {
                case 'peopleid':
                    start_making_web('people');
                    break;
                case 'newsid':
                    start_making_web('news');
                    break;
                case 'mapid':
                    start_making_web('map');
                    break;
                case 'gmarketid':
                    start_making_web('gmarket');
                    break;
                case 'navershop':
                    start_making_web('navershop');
                    break;
                case 'blogid':
                    start_making_web('blog');
                    break;
                case 'youtubeid':
                    start_making_web('youtube');
                    break;
                default:
                    break;
            }
        }

        //goodhow 크롤링 서버에 요청 보내기, 상태값 얻어 오기
        function start_making_web(type) {
            var slt = 0;
            var url = '';
            var mem_id_status = '';
            var sel_type = '';
            var count_interval = 0;
            var blog_link = 0;
            address = $("#people_web_address").val();
            var contents_keyword = '';
            if ($("#people_contents_start_date").val() != "") {
                start_date = $("#people_contents_start_date").val().replace(/-/g, "");
                end_date = $("#people_contents_end_date").val().replace(/-/g, "");
            }
            contents_cnt = $("#people_contents_cnt").val();
            phone_num = $("#phone_num").val();
            mem_name = $("#blog_mem_name").val();
            mem_zy = $("#blog_mem_zy").val();
            mem_address = $("#blog_mem_address").val();
            state = $("#blog_mem_name").prop("disabled");
            if ($("#update").prop("checked") == true) {
                auto_data = true;
            } else {
                auto_data = false;
            }
            sel_type = $('input[name=multi_westory_card_url]:checked').attr('value');
            if (type != 'blog' && type != 'news') {
                if (address == "" || contents_cnt == "") {
                    alert('주소/갯수/폰번호를 입력하세요.');
                    return;
                }
            } else {
                contents_keyword = $("#people_contents_key").val();
                if ((address == "") && (contents_cnt == "" || contents_keyword == "")) {
                    alert('키워드/갯수를 입력하세요.');
                    return;
                }
            }

            style_newmake = $('#newmake').attr('style').split(';')[1];
            style_mineid = $('#mineid').attr('style').split(';')[1];
            style_cardsel = $('#cardsel').attr('style').split(';')[1];
            if (style_newmake != "" && style_mineid != "" && style_cardsel != "") {
                alert("계정정보를 입력하세요.");
                return;
            }

            if (type == 'people') {
                if (address.substring(0, 37) != "https://search.naver.com/search.naver") {
                    alert("웹주소 형식이 틀립니다. 옳바른 형식으로 이용 해주세요.");
                    return;
                }
                if (contents_cnt > <?= $Gn_contents_limit; ?>) {
                    alert("유명인물 아이엠 콘텐츠 제한 갯수는 <?= $Gn_contents_limit; ?> 개 입니다.");
                    return;
                } else {
                    contents_keyword = $("#people_contents_key").val();
                    url = "https://www.goodhow.com/crawler/Crawler_people/index.php";
                    // url = "http://localhost:8082/Crawler_people/index_server.php";
                }
            } else if (type == "map") {
                if (address.substring(0, 24) != "https://map.naver.com/v5" && address.substring(0, 24) != "https://map.naver.com/p/") {
                    alert("웹주소 형식이 틀립니다. 옳바른 형식으로 이용 해주세요.");
                    return;
                }
                url = "https://www.goodhow.com/crawler/Crawler_map/index.php";
                // url = "http://localhost:8082/Crawler_map/index_server.php";
            } else if (type == "gmarket") {
                if (address.search("minishop.gmarket.co.kr") == -1) {
                    alert("웹주소 형식이 틀립니다. 옳바른 형식으로 이용 해주세요.");
                    return;
                }
                url = "https://www.goodhow.com/crawler/crawler/index.php";
                // url = "http://localhost:8082/index_server.php";
            } else if (type == "navershop") {
                if (address.substring(0, 28) != "https://smartstore.naver.com") {
                    alert("웹주소 형식이 틀립니다. 옳바른 형식으로 이용 해주세요.");
                    return;
                }
                url = "https://www.goodhow.com/crawler/crawler/index_navershop.php";
                // url = "http://localhost:8086/crawler_gmarket/index_gmarket.php";
            } else if (type == "blog") {
                if (state == false && (mem_name == '' || mem_zy == '' || mem_address == '')) {
                    alert("프로필 정보를 입력하세요");
                    return;
                }
                contents_keyword = $("#people_contents_key").val();
                if (address != "") {
                    if ($("#people_contents_start_date").val() != "") {
                        start_date = $("#people_contents_start_date").val();
                        end_date = $("#people_contents_end_date").val();
                    } else {
                        start_date = "";
                        end_date = "";
                    }
                    url = "https://www.goodhow.com/crawler/Crawler_blog/index_speclink.php";
                } else {
                    url = "https://www.goodhow.com/crawler/Crawler_blog/index.php";
                }
                // url = "http://localhost:8082/Crawler_people_blog/index_server.php";
            } else if (type == "news") {
                if (state == false && (mem_name == '' || mem_zy == '' || mem_address == '')) {
                    alert("프로필 정보를 입력하세요");
                    return;
                }
                contents_keyword = $("#people_contents_key").val();
                url = "https://www.goodhow.com/crawler/Crawler_blog/index_news.php";
                // url = "http://localhost:8082/Crawler_people_blog/index_news.php";
            } else if (type == 'youtube') {
                // alert(address.substring(0, 44));return;
                // 유튜브 URL 찾는 패턴
                if ((address.substring(0, 26) == "https://www.youtube.com/c/") ||
                    (address.substring(0, 32) == "https://www.youtube.com/channel/") ||
                    (address.substring(0, 44) == "https://www.youtube.com/results?search_query") ||
                    (address.substring(0, 29) == "https://www.youtube.com/user/") ||
                    (address.substring(0, 30) == "https://www.youtube.com/@user-") ||
                    (address.search("youtube.com") != -1 && address.search("videos") != -1)) {
                    url = "https://www.goodhow.com/crawler/Crawler_youtube/index.php";
                    // url = "http://localhost:8082/Crawler_youtube/index_server.php";
                } else {
                    alert("웹주소 형식이 틀립니다. 옳바른 형식으로 이용 해주세요.");
                    return;
                }
            }

            if (style_newmake == "") { //새계정 만들기
                slt = 1;
                var new_id = $("#newID").val();
                mem_id_status = new_id;
                pwd = $("#pwd").val();
                site = '<?= $user_site ?>';
                recommend_id = '<?= $_SESSION['iam_member_id']; ?>';
                console.log(address, contents_cnt, phone_num, new_id, pwd, site);
                style = $("#checkdup").attr('style');
                if (style.indexOf("blue") == -1) {
                    alert("아이디 중복확인을 하세요.");
                    return;
                } else {
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        data: {
                            new_account: true,
                            auto_data: auto_data,
                            address: address,
                            contents_cnt: contents_cnt,
                            phone_num: phone_num,
                            new_id: new_id,
                            pwd: pwd,
                            contents_keyword: contents_keyword,
                            start_date: start_date,
                            end_date: end_date,
                            site: site,
                            recommend_id: recommend_id,
                            mem_name: mem_name,
                            mem_zy: mem_zy,
                            mem_address: mem_address
                        },
                        url: url,
                        success: function(data) {
                            if (data == 5) {
                                alert("사진이 없어서 수집할수 없습니다");
                                location.reload();
                                // return;
                            }
                        }
                    });
                }
            } else if (style_mineid == "") { //내아이디로 만들기
                slt = 0;
                my_id = '<?= $_SESSION['iam_member_id']; ?>';
                mem_id_status = my_id;
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: {
                        my_account: true,
                        auto_data: auto_data,
                        address: address,
                        contents_cnt: contents_cnt,
                        phone_num: phone_num,
                        my_id: my_id,
                        contents_keyword: contents_keyword,
                        start_date: start_date,
                        end_date: end_date,
                        mem_name: mem_name,
                        mem_zy: mem_zy,
                        mem_address: mem_address
                    },
                    url: url,
                    success: function(data) {
                        if (data == 5) {
                            alert("사진이 없어서 수집할수 없습니다");
                            location.reload();
                            // return;
                        }
                    }
                });
                // console.log("OK mineid!!");
            } else if (style_cardsel == "") { //카드에 추가하기
                slt = 2;
                my_id = '<?= $_SESSION['iam_member_id']; ?>';
                mem_id_status = my_id;
                if (sel_type == null) {
                    alert("카드를 선택하세요.");
                    return;
                }
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    data: {
                        card_sel: true,
                        address: address,
                        contents_cnt: contents_cnt,
                        my_id: my_id,
                        contents_keyword: contents_keyword,
                        start_date: start_date,
                        end_date: end_date,
                        short_url: sel_type
                    },
                    url: url,
                    success: function(data) {
                        console.log(data);
                    }
                });
            }

            alert("지금 웹에서 관련정보를 가지고 신청한 아이엠생성하고 있습니다. 생성중에 다른 작업을 할수 있습니다. 완료되면 알람이 나타 납니다. 조금만 기다려 주세요.");
            $("#startmaking").attr('disabled', true);

            //크롤링 진행 상태값 얻어 오기
            if (type == 'people') {
                var interval_people = setInterval(function() {
                    console.log(count_interval);
                    $.ajax({
                        type: "POST",
                        url: "/admin/people_crawling_status.php",
                        dataType: "json",
                        data: {
                            get: true,
                            address: address,
                            contents_cnt: contents_cnt,
                            mem_id_status: mem_id_status
                        },
                        success: function(data) {
                            console.log(data.status);
                            if (data.status == 0) {
                                $("#startmaking").attr('disabled', false);
                                clearInterval(interval_people);
                                iam_item('<?= $_SESSION['iam_member_id'] ?>', 'use', '인물', data.keyword, mem_id_status);
                                $("#people_mem_id").html(data.mem_id);
                                $('#auto_making_modal').modal('hide');
                                if (slt == 1) {
                                    $('#finish_login').modal('show');
                                } else if (slt == 0) {
                                    $('#finish_login_own').modal('show');
                                } else if (slt == 2) {
                                    $('#finish_login_cardsel').modal('show');
                                }
                            } else if (data.status == 2) {
                                alert("콘텐츠 데이터가 없거나 이미 다 가져 왔습니다. 웹링크를 다시 확인해 주세요.");
                                clearInterval(interval_people);
                                location.reload();
                            } else if (data.status == 3 || count_interval == 4) {
                                alert("아이엠 자동 생성중 오류가 발생 하였습니다.");
                                clearInterval(interval_people);
                                location.reload();
                            }
                        }
                    });
                    count_interval++;
                }, 5000);
            } else if (type == 'news') {
                var interval_news = setInterval(function() {
                    console.log(count_interval);
                    $.ajax({
                        type: "POST",
                        url: "/admin/news_crawling_status.php",
                        dataType: "json",
                        data: {
                            get: true,
                            contents_cnt: contents_cnt,
                            mem_id_status: mem_id_status
                        },
                        success: function(data) {
                            console.log(data.status);
                            if (data.status == 0) {
                                $("#startmaking").attr('disabled', false);
                                clearInterval(interval_news);
                                iam_item('<?= $_SESSION['iam_member_id'] ?>', 'use', '뉴스', data.keyword, mem_id_status);
                                $("#news_mem_id").html(data.mem_id);
                                $('#auto_making_modal').modal('hide');
                                if (slt == 1) {
                                    $('#finish_login').modal('show');
                                } else if (slt == 0) {
                                    $('#finish_login_own').modal('show');
                                } else if (slt == 2) {
                                    $('#finish_login_cardsel').modal('show');
                                }
                            } else if (data.status == 2) {
                                alert("콘텐츠 데이터가 없거나 이미 다 가져 왔습니다. 웹링크를 다시 확인해 주세요.");
                                clearInterval(interval_news);
                                location.reload();
                            } else if (data.status == 3 || count_interval == 4) {
                                alert("아이엠 자동 생성중 오류가 발생 하였습니다.");
                                clearInterval(interval_news);
                                location.reload();
                            }
                        }
                    });
                    count_interval++;
                }, 5000);
            } else if (type == "map") {
                var interval_map = setInterval(function() {
                    $.ajax({
                        type: "POST",
                        url: "/admin/map_crawling_status.php",
                        dataType: "json",
                        data: {
                            get: true,
                            address: address,
                            contents_cnt: contents_cnt,
                            mem_id_status: mem_id_status
                        },
                        success: function(data) {
                            console.log(data.status);
                            if (data.status == 0) {
                                $("#startmaking").attr('disabled', false);
                                clearInterval(interval_map);
                                iam_item('<?= $_SESSION['iam_member_id'] ?>', 'use', '지도', 'MAP', mem_id_status);
                                $("#people_mem_id").html(data.mem_id);
                                $('#auto_making_modal').modal('hide');
                                if (slt == 1) {
                                    $('#finish_login').modal('show');
                                } else if (slt == 0) {
                                    $('#finish_login_own').modal('show');
                                } else if (slt == 2) {
                                    $('#finish_login_cardsel').modal('show');
                                }
                            } else if (data.status == 2) {
                                alert("콘텐츠 데이터가 없거나 이미 다 가져 왔습니다. 웹링크를 다시 확인해 주세요.");
                                clearInterval(interval_map);
                                location.reload();
                            } else if (data.status == 3) {
                                alert("아이엠 자동 생성중 오류가 발생 하였습니다.");
                                clearInterval(interval_map);
                                location.reload();
                            } else if (count_interval == 6) {
                                alert("아이엠 자동 생성중 오류가 발생 하였습니다...");
                                clearInterval(interval_map);
                                location.reload();
                            }
                        }
                    })
                    count_interval++;
                }, 5000);
            } else if (type == "gmarket") {
                var state_sel = slt;
                var interval_gmarket = setInterval(function() {
                    $.ajax({
                        type: "POST",
                        url: "/admin/get_db_crawling_status.php",
                        dataType: "json",
                        data: {
                            get: true,
                            contents_cnt: contents_cnt,
                            mem_id_status: mem_id_status
                        },
                        success: function(data) {
                            console.log(data.status);
                            if (data.status == 0) {
                                $("#startmaking").attr('disabled', false);
                                clearInterval(interval_gmarket);
                                iam_item('<?= $_SESSION['iam_member_id'] ?>', 'use', '지마켓', 'GMARKET', mem_id_status);
                                $("#people_mem_id").html(data.mem_id);
                                $('#auto_making_modal').modal('hide');
                                if (slt == 1) {
                                    $('#finish_login').modal('show');
                                } else if (slt == 0) {
                                    $('#finish_login_own').modal('show');
                                } else if (slt == 2) {
                                    $('#finish_login_cardsel').modal('show');
                                }
                            } else if (count_interval == 4) {
                                alert("아이엠 자동 생성중 오류가 발생 하였습니다.");
                                clearInterval(interval_gmarket);
                                location.reload();
                            }
                        }
                    });
                    count_interval++;
                }, 5000);
            } else if (type == "navershop") {
                var state_sel = slt;
                var interval_nshop = setInterval(function() {
                    $.ajax({
                        type: "POST",
                        url: "/admin/nshop_crawling_status.php",
                        dataType: "json",
                        data: {
                            get: true,
                            contents_cnt: contents_cnt,
                            mem_id_status: mem_id_status
                        },
                        success: function(data) {
                            console.log(data.status);
                            if (data.status == 0) {
                                $("#startmaking").attr('disabled', false);
                                clearInterval(interval_nshop);
                                iam_item('<?= $_SESSION['iam_member_id'] ?>', 'use', 'N샵', 'NAVERSHOP', mem_id_status);
                                $("#people_mem_id").html(data.mem_id);
                                $('#auto_making_modal').modal('hide');
                                if (slt == 1) {
                                    $('#finish_login').modal('show');
                                } else if (slt == 0) {
                                    $('#finish_login_own').modal('show');
                                } else if (slt == 2) {
                                    $('#finish_login_cardsel').modal('show');
                                }
                            } else if (count_interval == 4) {
                                alert("아이엠 자동 생성중 오류가 발생 하였습니다.");
                                clearInterval(interval_nshop);
                                location.reload();
                            }
                        }
                    });
                    count_interval++;
                }, 5000);
            } else if (type == 'blog') {
                var interval_blog = setInterval(function() {
                    $.ajax({
                        type: "POST",
                        url: "/admin/blog_crawling_status.php",
                        dataType: "json",
                        data: {
                            get: true,
                            address: address,
                            contents_cnt: contents_cnt,
                            mem_id_status: mem_id_status
                        },
                        success: function(data) {
                            console.log(data.status);
                            if (data.status == 0) {
                                $("#startmaking").attr('disabled', false);
                                clearInterval(interval_blog);
                                iam_item('<?= $_SESSION['iam_member_id'] ?>', 'use', '블로그', data.keyword, mem_id_status);
                                $("#people_mem_id").html(data.mem_id);
                                $('#auto_making_modal').modal('hide');
                                if (slt == 1) {
                                    $('#finish_login').modal('show');
                                } else if (slt == 0) {
                                    $('#finish_login_own').modal('show');
                                } else if (slt == 2) {
                                    $('#finish_login_cardsel').modal('show');
                                }
                            } else if (data.status == 2) {
                                alert("콘텐츠 데이터가 없거나 이미 다 가져 왔습니다. 웹링크를 다시 확인해 주세요.");
                                clearInterval(interval_blog);
                                location.reload();
                            } else if (data.status == 3) {
                                alert("아이엠 자동 생성중 오류가 발생 하였습니다.");
                                clearInterval(interval_blog);
                                location.reload();
                            }
                        }
                    });
                    count_interval++;
                }, 5000);
            } else if (type == 'youtube') {
                var interval_youtube = setInterval(function() {
                    $.ajax({
                        type: "POST",
                        url: "/admin/youtube_crawling_status.php",
                        dataType: "json",
                        data: {
                            get: true,
                            address: address,
                            contents_cnt: contents_cnt,
                            mem_id_status: mem_id_status
                        },
                        success: function(data) {
                            console.log(data.status);
                            if (data.status == 0) {
                                $("#startmaking").attr('disabled', false);
                                clearInterval(interval_youtube);
                                iam_item('<?= $_SESSION['iam_member_id'] ?>', 'use', '유튜브', 'YOUTUBE', mem_id_status);
                                $("#people_mem_id").html(data.mem_id);
                                $('#auto_making_modal').modal('hide');
                                if (slt == 1) {
                                    $('#finish_login').modal('show');
                                } else if (slt == 0) {
                                    $('#finish_login_own').modal('show');
                                } else if (slt == 2) {
                                    $('#finish_login_cardsel').modal('show');
                                }
                            } else if (data.status == 2) {
                                alert("콘텐츠 데이터가 없거나 이미 다 가져 왔습니다. 웹링크를 다시 확인해 주세요.");
                                clearInterval(interval_youtube);
                                location.reload();
                            } else if (data.status == 3) {
                                alert("아이엠 자동 생성중 오류가 발생 하였습니다.");
                                clearInterval(interval_youtube);
                                location.reload();
                            }
                        }
                    });
                    // count_interval++;
                }, 5000);
            }
        }

        //아이엠 자동만들기 아이템에 따르는 포인트 결제
        function iam_item(memid, action, channel, keyword, id_status) {
            if (action == 'use') {
                $.ajax({
                    type: "POST",
                    url: "iam_item_mng.php",
                    dataType: "json",
                    data: {
                        use: true,
                        memid: memid,
                        mem_type: 'AI카드',
                        channel: channel,
                        keyword: keyword,
                        id_status: id_status
                    },
                    success: function(data) {
                        console.log(data);
                        // alert("결제 되었습니다!");
                    }
                })
            }
        }

        //새계정 만들기에서 아이디 증복 검사
        function id_check1() {
            // var formData = new FormData();
            if (!$('#newID').val()) {
                $('#newID').focus();
                return;
            }
            if ($('#newID').val().length < 4) {
                alert('아이디는 4자~15자를 입력해 주세요.');
                return;
            }
            var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
            if (!pattern.test($('#newID').val())) {
                alert('영문 소문자와 숫자만 사용이 가능합니다.');
                $('#id_status').val("");
                $('#newID').val("");
                $('#newID').focus();
                return;
            }
            // formData.append('mode', 'id_check');
            // formData.append('id', $('#newID').val());
            // formData.append('card_idx', card_idx);
            $.ajax({
                type: "POST",
                url: "/ajax/ajax_checkid.php",
                dataType: "json",
                data: {
                    id: $('#newID').val()
                },
                // contentType: false,
                // processData: false,
                success: function(data) {
                    console.log(data);
                    // $("#ajax_div").html(data);
                    // alert('사용 가능한 아이디 입니다.');
                    if (data.result == "0") {
                        alert("이미 가입되어있는 아이디 입니다.");
                        $('#id_status').val("");
                        $('#newID').val("");
                        $('#newID').focus();
                    } else {
                        // alert('사용 가능한 아이디 입니다.');
                        $("#checkdup").attr('style', 'background-color: skyblue;');
                        $("#id_html").prop('hidden', false);
                    }
                    // console.log(data);
                }
            });
        }

        //결제/이용내역 팝업창에서 아이디 검색, 더보기 기능
        function search_people(val) {
            start = $("#search_start_date").val();
            end = $("#search_end_date").val();
            // type = $("#use_buy_type").val();
            // item_type='AI카드';
            // ID = $("#search_ID").val();
            see = 'false';
            if (val == 'more') see = 'see_more';

            // console.log(start, end, type, ID, see, val);
            $.ajax({
                type: "POST",
                url: "/ajax/use_contents.php",
                dataType: "html",
                data: {
                    start: start,
                    end: end,
                    ID: '<?= $_SESSION['iam_member_id']; ?>',
                    more: see
                },
                success: function(data) {
                    // console.log(data);
                    $("#contents_side").html(data);
                }
            })
        }

        function del_ai_list(val) {
            if (confirm("삭제하시겠습니까?")) {
                $.ajax({
                    type: "POST",
                    url: "/iam/card_con_send.php",
                    dataType: "json",
                    data: {
                        settle_type: "delete_ai",
                        no: val,
                        ID: '<?= $_SESSION['iam_member_id'] ?>'
                    },
                    success: function(data) {
                        alert("삭제되었습니다.");
                        search_people();
                    }
                })
            }
        }

        //내아이디로 만들기후 생성된 카드로 이동
        function go_iamlink() {
            $.ajax({
                type: "POST",
                url: "/ajax/get_iamlink.php",
                dataType: "json",
                data: {
                    get: true,
                    id: '<?= $_SESSION['iam_member_id'] ?>'
                },
                success: function(data) {
                    console.log(data.url);
                    location.href = "?" + data.url + data.mem_code;
                }
            })
        }

        function go_contentslink() {
            location.reload();
        }

        //수집분야별에 따르는 설정값 입력창 현시
        function show_keyword(val) {
            if (val == 'people') {
                $("#contents_key").attr('style', 'width:100%;display:inline-table;');
                $("#contents_time").attr('style', 'width:100%;display:inline-table;');
                $("#web_address").attr('style', 'width:100%;display:inline-table;');
                $("#mem_name").prop("hidden", true);
                $("#mem_company").prop("hidden", true);
                $("#mem_address").prop("hidden", true);
                if ($("#selcard").prop("checked")) {
                    $("#phonenum").prop("hidden", true);
                } else {
                    $("#phonenum").prop("hidden", false);
                }
                $("#my_id_select").show();
            } else if (val == 'news') {
                $("#contents_key").attr('style', 'width:100%;display:inline-table;');
                $("#contents_time").attr('style', 'width:100%;display:inline-table;');
                $("#web_address").attr('style', 'width:100%;display:none;');
                if ($("#selcard").prop("checked")) {
                    $("#mem_name").prop("hidden", true);
                    $("#mem_company").prop("hidden", true);
                    $("#mem_address").prop("hidden", true);
                    $("#phonenum").prop("hidden", true);
                } else {
                    $("#mem_name").prop("hidden", false);
                    $("#mem_company").prop("hidden", false);
                    $("#mem_address").prop("hidden", false);
                    $("#phonenum").prop("hidden", false);
                }
                $("#my_id_select").show();
            } else if (val == 'blog' || val == 'news') {
                if (val == 'blog') {
                    alert("특정 블로그에서 키워드와 매칭되는 게시물 크롤링을 원하시면 웹주소입력란에 다음의 블로그 주소를 입력하세요.\n 예시 : https://blog.naver.com/abcd123\n\n웹주소에 입력을 안하면 전체 블로그에서 키워드와 매칭되는 게시물을 크롤링합니다.");
                }
                $("#contents_key").attr('style', 'width:100%;display:inline-table;');
                $("#contents_time").attr('style', 'width:100%;display:inline-table;');
                $("#web_address").attr('style', 'width:100%;display:inline-table;');
                if ($("#selcard").prop("checked")) {
                    $("#mem_name").prop("hidden", true);
                    $("#mem_company").prop("hidden", true);
                    $("#mem_address").prop("hidden", true);
                    $("#phonenum").prop("hidden", true);
                } else {
                    $("#mem_name").prop("hidden", false);
                    $("#mem_company").prop("hidden", false);
                    $("#mem_address").prop("hidden", false);
                    $("#phonenum").prop("hidden", false);
                }
                $("#my_id_select").show();
            } else if (val == 'youtube') {
                $("#contents_key").attr('style', 'width:100%;display:none;');
                $("#contents_time").attr('style', 'width:100%;display:none;');
                $("#web_address").attr('style', 'width:100%;display:inline-table;');
                if ($("#selcard").prop("checked")) {
                    $("#mem_name").prop("hidden", true);
                    $("#mem_company").prop("hidden", true);
                    $("#mem_address").prop("hidden", true);
                    $("#phonenum").prop("hidden", true);
                } else {
                    $("#mem_name").prop("hidden", false);
                    $("#mem_company").prop("hidden", false);
                    $("#mem_address").prop("hidden", false);
                    $("#phonenum").prop("hidden", false);
                }
                $("#my_id_select").show();
            } else if (val == 'gmarket' || val == 'navershop') {
                $("#contents_key").attr('style', 'width:100%;display:none;');
                $("#contents_time").attr('style', 'width:100%;display:none;');
                $("#mem_name").prop("hidden", true);
                $("#mem_company").prop("hidden", true);
                $("#mem_address").prop("hidden", true);
                $("#phonenum").prop("hidden", true);
                $("#web_address").attr('style', 'width:100%;display:inline-table;');
                $("#my_id_select").show();
            } else if (val == 'map') {
                $("#contents_key").attr('style', 'width:100%;display:none;');
                $("#contents_time").attr('style', 'width:100%;display:none;');
                $("#mem_name").prop("hidden", true);
                $("#mem_company").prop("hidden", true);
                $("#mem_address").prop("hidden", true);
                $("#phonenum").prop("hidden", true);
                $("#web_address").attr('style', 'width:100%;display:inline-table;');
                $("#my_id_select").hide();
            } else {
                $("#contents_key").attr('style', 'width:100%;display:none;');
                $("#contents_time").attr('style', 'width:100%;display:none;');
                $("#mem_name").prop("hidden", true);
                $("#mem_company").prop("hidden", true);
                $("#mem_address").prop("hidden", true);
                if ($("#selcard").prop("checked")) {
                    $("#phonenum").prop("hidden", true);
                } else {
                    $("#phonenum").prop("hidden", false);
                }
                $("#web_address").attr('style', 'width:100%;display:inline-table;');
                $("#my_id_select").show();
            }
        }

        //포인트 쉐어하기
        var current_point = 0;
        var current_cash = 0;

        function start_sharing() {
            <?php
            if ($_SESSION['iam_member_id']) {
            ?>
                current_point = <?= $Gn_point ?>;
                current_cash = <?= $Gn_cash_point ?>;
            <? } ?>
            send_id = '<?= $_SESSION['iam_member_id']; ?>';
            receive_id = $("#share_id").val();
            var share_point = $("#share_point").val();
            var share_cash = $("#share_cash").val();
            if (share_point == '') share_point = 0;
            if (share_cash == '') share_cash = 0;
            if (send_id == '' || receive_id == '' || (share_point == 0 && share_cash == 0)) {
                alert("아이디와 포인트를 입력하세요.");
                return;
            }
            if (current_point < share_point) {
                alert("현재 보유씨드포인트는 <?= $Gn_point ?> P 입니다.");
                return;
            }
            if (current_cash < share_cash) {
                alert("현재 보유캐시포인트는 <?= $Gn_cash_point ?> P 입니다.");
                return;
            }
            $.ajax({
                type: "POST",
                url: "/iam/share_point.php",
                dataType: "json",
                data: {
                    send_id: send_id,
                    receive_id: receive_id,
                    share_point: share_point,
                    share_cash: share_cash
                },
                success: function(data) {
                    if (data == 0) {
                        alert("잘못된 회원 아이디 입니다.");
                    } else if (data == 1) {
                        if (share_point && !share_cash)
                            alert(send_id + "의 계정에서 씨드포인트 " + share_point + "P 가 " + receive_id + " 계정으로 이전되었습니다.");
                        if (!share_point && share_cash)
                            alert(send_id + "의 계정에서 캐시포인트 " + share_cash + "P 가 " + receive_id + " 계정으로 이전되었습니다.");
                        if (share_point && share_cash)
                            alert(send_id + "의 계정에서 씨드포인트 " + share_point + "P, 캐시포인트 " + share_cash + "P 가 " + receive_id + " 계정으로 이전되었습니다.");
                        location.reload();
                    }
                }
            })
        }

        //팝업에서 뒤로가기 기능
        function goback(val) {
            if (val == "settlment") {
                $('#settlement_modal').modal('hide');
                $('#people_iam_modal').modal('show');
            } else if (val == "making") {
                $('#auto_making_modal').modal('hide');
                $('#people_iam_modal').modal('show');
            } else if (val == "more") {
                $('#settlement_contents_modal').modal('hide');
                $('#people_iam_modal').modal('show');
            } else if (val == "share") {
                $('#sharepoint_modal').modal('hide');
                if ($("#place_before").val() == "webpage") {
                    $('#people_iam_modal').modal('show');
                } else if ($("#place_before").val() == "card") {
                    $('#card_send_modal').modal('show');
                } else {
                    $('#contents_send_modal').modal('show');
                }
            } else if (val == "settlment_auto") {
                $("#auto_settlement_modal").modal('hide');
                $("#auto_making_modal").modal('show');
            } else if (val == "auto_list") {
                $("#auto_list_edit_modal").modal('hide');
                //$("#auto_list_modal").modal('show');
                $.ajax({
                    type: "GET",
                    url: "/iam/ajax/modal_auto_list.php",
                    data: "start_date=" + '<?= $_REQUEST['search_start_date'] ?>' +
                        "&end_date=" + '<?= $_REQUEST['search_end_date'] ?>',
                    success: function(html) {
                        $("#auto_list_modal").html('');
                        $("#auto_list_modal").append(html);
                        $("#auto_list_modal").modal("show");
                    }
                });
            }
        }

        //오토데이트 설명 팝업 현시
        function show_update_popup() {
            $('#auto_making_modal').modal('hide');
            $('#intro_auto_update').modal('show');
        }

        function show_making() {
            $('#auto_making_modal').modal('show');
        }

        //오토데이트 팝업 현시
        function set_auto_update(val) {
            if (val == 'hide') {
                $("#update").prop("checked", false);
                $("#people_contents_cnt").attr('disabled', false);
            }
            if ($("#update").prop("checked") == true) {
                $("#auto_update_contents").attr("style", "display:block;");
                $("#startmaking").attr('disabled', true);
                // $("#people_contents_cnt").attr('disabled', true);
            } else {
                $("#auto_update_contents").attr("style", "display:none;");
                $("#startmaking").attr('disabled', false);
                $("#people_contents_cnt").attr('disabled', false);
            }
        }

        //오토데이트 시간설정부분 현시
        function show_hour() {
            if ($("#contents_auto_upload_time").prop("checked") == true) {
                $("#24_hours").attr("style", "display:block;");
            } else {
                $("#24_hours").attr("style", "display:none;");
            }
        }

        //오토데이트 설정
        function start_auto_update() {
            var slt = 0;
            style_newmake = $('#newmake').attr('style').split(';')[1];
            style_mineid = $('#mineid').attr('style').split(';')[1];
            style_cardsel = $('#cardsel').attr('style').split(';')[1];
            if (style_newmake == "") { //새계정 만들기
                slt = 1;
            } else if (style_mineid == "") { //내아이디로 만들기
                slt = 0;
            } else if (style_cardsel == "") { //내카드 선택
                slt = 2;
            }

            $auto_point = $("#usable_point").val();

            if ($auto_point < 1100) {
                alert("포인트가 부족합니다. 충전후 이용해 주세요.");
                return;
            }
            if ($("#upload_time").val() == "") {
                alert("시간을 선택해 주세요.");
                return;
            }
            // console.log(slt); return;
            if (confirm("포인트 차감 안내" + "\n" + "콘텐츠 오토데이트 기능을 사용하게 되면 선택한 IAM카드에서 발생하는 트래픽 비용으로 매월 1100원의 포인트가 차감됩니다. 포인트가 부족할 경우 오토데이트가 중지됩니다. 미리 포인트를 충분히 충전해두시기 바랍니다.")) {
                address = $("#people_web_address").val();
                contents_keyword = $("#people_contents_key").val();
                start_date = $("#people_contents_start_date").val();
                end_date = $("#people_contents_end_date").val();
                if (slt == 1) {
                    my_id = $("#newID").val();
                } else {
                    my_id = '<?= $_SESSION['iam_member_id']; ?>';
                }
                web_type = $('input[name=web_type]:checked').attr('id');
                sel_type = $('input[name=multi_westory_card_url]:checked').attr('value');
                upload_time = $("#upload_time").val();
                console.log('hello');
                $.ajax({
                    type: "POST",
                    url: "/iam/auto_update_contents.php",
                    dataType: "json",
                    data: {
                        address: address,
                        my_id: my_id,
                        contents_keyword: contents_keyword,
                        sel_position: slt,
                        short_url: sel_type,
                        web_type: web_type,
                        upload_time: upload_time,
                        start_date: start_date,
                        end_date: end_date
                    },
                    success: function(data) {
                        console.log(data);
                        if (data == 3) {
                            alert("이 카드로 오토데이트가 진행중입니다.");
                            return;
                        } else if (data == 1) {
                            alert("오토데이트가 등록되었습니다.");
                            $("#startmaking").prop("disabled", false);
                            // location.reload();
                        }
                        // alert("결제 되었습니다!");
                    }
                });
            }
        }

        //오토데이트 시간 1일에 최대 3번까지 입력 기능
        function limit_sel_hour() {
            var sel_time = new Array();
            var cnt;
            $('input[name=select_hour]:checked').each(function() {
                var idVal = $(this).attr("id");
                cnt = sel_time.push($("label[for='" + idVal + "']").attr('value'));
                if (cnt > 3) {
                    alert('최대 3개까지 선택할수 있습니다.');
                    $('input[id=' + idVal + ']').prop("checked", false);
                    return;
                }
                $("#upload_time").val(sel_time.join(","));
            });
        }

        //오토데이트 관리 부분 현시
        function show_manage_auto(val) {
            console.log('auto', val);
            if (val == 0) {
                $("#manage_auto_update").prop("hidden", false);
                search_auto_data();
            } else {
                $("#manage_auto_update").prop("hidden", true);
            }
        }

        //오토데이트 팝업 현시
        function show_making_popup() {
            $("#auto_making_modal").modal("show");
        }

        //오토데이트 포인트 충전하기
        function auto_point_chung(memid, type, price) {
            $.ajax({
                type: "POST",
                url: "gn_auto_point_chung.php",
                dataType: "json",
                data: {
                    chung: true,
                    memid: memid
                },
                success: function(data) {
                    console.log(data);
                    // alert("결제 되었습니다!");
                    $('#auto_settlement_modal').modal('hide');
                    if (type == "bank") {
                        $("#buy_point").text(price);
                        $('#settlement_mutongjang_modal').modal('show');
                    } else {
                        alert("결제되었습니다.");
                        location.reload();
                    }
                }
            });
        }

        var auto_id = 1;
        //오토데이트 설정값 변경 팝업 현시
        function edit_auto_data(id) {
            $.ajax({
                type: "POST",
                url: "/iam/get_auto_conset.php",
                dataType: "json",
                data: {
                    id: id
                },
                success: function(data) {
                    console.log(data.state_flag);
                    if (data.state_flag == 2) {
                        alert('현재 중지되어 있는 상태입니다.');
                        return;
                    }
                    $("input[id=" + data.web_type + "]").prop("checked", true);
                    if ($("#mapid").prop("checked") == true || $("#youtubeid").prop("checked") == true) {
                        $("#contents_key").attr('style', 'width:100%;display:none;');
                    }
                    $("#people_web_address").val(data.web_address);
                    $("#people_contents_key").val(data.keyword);
                    $("#people_contents_start_date").val(data.start_date);
                    $("#people_contents_end_date").val(data.end_date);
                    $("#selcard").prop("checked", true);
                    $("input[id=multi_westory_card_url_" + data.card_url + "]").prop("checked", true);
                    $("#contents_auto_upload_time").prop("checked", true);
                    $("#upload_time").val(data.get_time);
                    if (data.get_time.indexOf(",") == -1) {
                        $('input[name=select_hour]').prop("checked", false);
                        $("input[id=" + data.get_time + "hour]").prop("checked", true);
                    } else {
                        $('input[name=select_hour]').prop("checked", false);
                        var arr = data.get_time.split(",");
                        for (var i = 0; i < arr.length; i++) {
                            $("input[id=" + arr[i] + "hour]").prop("checked", true);
                        }
                    }
                    $("#update").prop("checked", true);
                    $("#24_hours").show();
                    $(".btn-submit.start").hide();
                    $(".btn-submit.edit").show();
                    $("#cardsel").show();
                    $("#auto_update_contents").show();
                    $("#startmaking").prop("disabled", true);
                    $("#auto_making_modal").modal("show");
                    auto_id = data.id;
                }
            });
        }

        //오토데이트 설정값 변경
        function edit_auto_update() {
            if (confirm("변경 하시겠습니까?")) {
                address = $("#people_web_address").val();
                my_id = '<?= $_SESSION['iam_member_id']; ?>';
                if ($('#contents_key').attr('style').split(';')[1] == "") {
                    contents_keyword = $("#people_contents_key").val();
                } else {
                    contents_keyword = "";
                }
                web_type = $('input[name=web_type]:checked').attr('id');
                sel_type = $('input[name=multi_westory_card_url]:checked').attr('value');
                upload_time = $("#upload_time").val();
                start_date = $("#people_contents_start_date").val();
                end_date = $("#people_contents_end_date").val();
                // console.log(start_date); return;
                console.log("edit_auto_data!!!" + auto_id);
                $.ajax({
                    type: "POST",
                    url: "/iam/auto_update_contents.php",
                    dataType: "json",
                    data: {
                        auto_id: auto_id,
                        address: address,
                        my_id: my_id,
                        contents_keyword: contents_keyword,
                        short_url: sel_type,
                        web_type: web_type,
                        upload_time: upload_time,
                        start_date: start_date,
                        end_date: end_date
                    },
                    success: function(data) {
                        console.log(data);
                        alert("오토데이트가 변경되었습니다.");
                        location.reload();
                    }
                });
            }
        }

        //오토데이트 중지/진행

        // function stop_auto_data(val){
        //     my_id = '<?= $_SESSION['iam_member_id']; ?>';
        //     $.ajax({
        //         type:"POST",
        //         url:"/iam/auto_update_contents.php",
        //         dataType:"json",
        //         data:{stop_auto:true, my_id:my_id, id:val},
        //         success:function (data) {
        //             console.log(data);
        //             if(data == 1){
        //                 alert("오토데이트가 중지상태로 되었습니다.");
        //             }
        //             else if(data == 2){
        //                 alert("오토데이트가 진행상태로 되었습니다.");
        //             }
        //             else if(data == 3){
        //                 alert('포인트가 부족하여 진행상태로 될수 없습니다.');
        //             }
        //             location.reload();
        //         }
        //     });
        // }

        //오토데이트 삭제
        function remove_auto_data(val) {
            my_id = '<?= $_SESSION['iam_member_id']; ?>';
            if (confirm("정말 삭제 하겠습니까?")) {
                $.ajax({
                    type: "POST",
                    url: "/iam/auto_update_contents.php",
                    dataType: "json",
                    data: {
                        remove_auto: true,
                        my_id: my_id,
                        id: val
                    },
                    success: function(data) {
                        console.log(data);
                        if (data == 1) {
                            alert("오토데이트가 삭제 되었습니다.");
                        }
                        location.reload();
                    }
                });
            }
        }

        //오토데이터 관리 더보기 기능
        function search_auto_data(val) {
            my_id = '<?= $_SESSION['iam_member_id']; ?>';
            $.ajax({
                type: "POST",
                url: "/ajax/auto_contents_more.php",
                dataType: "html",
                data: {
                    memid: my_id,
                    more: val
                },
                success: function(data) {
                    $("#auto_contetns_manage_show").html(data);
                    $('.switch').on("change", function() {
                        my_id = '<?= $_SESSION['iam_member_id']; ?>';
                        var id = $(this).find("input[type=checkbox]").val();
                        // var status = $(this).find("input[type=checkbox]").is(":checked")==true?"1":"0";
                        $.ajax({
                            type: "POST",
                            url: "/iam/auto_update_contents.php",
                            data: {
                                stop_auto: true,
                                my_id: my_id,
                                id: id
                            },
                            success: function(data) {
                                if (data == 1) {
                                    alert("오토데이트가 중지상태로 되었습니다.");
                                } else if (data == 2) {
                                    alert("오토데이트가 진행상태로 되었습니다.");
                                } else if (data == 3) {
                                    alert('포인트가 부족하여 진행상태로 될수 없습니다.');
                                }
                                location.reload();
                            }
                        })
                    });
                }
            })
        }

        function testsetinter() {
            setInterval(function() {
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/auto_update_schedule.php",
                    dataType: "json",
                    data: {
                        get: true
                    },
                    success: function(data) {}
                })
            }, 10000);
        }
        //자동아이엠만들기 스크립트 끝

        //카드, 컨텐츠 전송 스크립트 시작

        function show_more_con_detail(str) {
            $("#contents_detail").html(str);
            $("#show_detail_more").modal("show");
        }

        function card_send_settle(val) {
            var send_mode = $("input[name=card_send_mode]:checked").val();
            if (send_mode == undefined) {
                alert("전송방식을 선택하세요.");
                return;
            }
            if ($("#alarm_msg").val() == "") {
                alert("메시지를 입력하세요.");
                $("#alarm_msg").focus();
                return;
            }
            var send_type = $("input[name=card_send_type]:checked").val();
            if (send_type == "link") {
                $("#card_send_modal_mem").modal("hide");
                $("#card_send_modal_link").modal("show");
            } else {
                var cnt = $("#card_send_id_count").val();
                cnt = cnt.replace("건", "");
                if (cnt == 0) {
                    alert("전송할 아이디를 입력하세요.");
                    return;
                }
                var recv_card_ids = $("#card_send_id").val();
                $.ajax({
                    type: "POST",
                    url: "iam/card_con_send.php",
                    data: {
                        settle_type: "check_ids_card_send",
                        payMethod: recv_card_ids,
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data == 5) {
                            alert("잘못된 회원 아이디 입니다.");
                        } else {
                            $("#settle_point").val(cnt * <?= $card_send_point ?>);
                            $("#point_pay_type").val(5);
                            $("#point_pay_data").val(val);
                            $("#card_send_mode").val(send_mode);
                            $("#card_send_modal_mem").modal("hide");
                            $("#point_settlement").modal("show");
                        }
                    }
                });
            }
        }

        function contents_send_settle() {
            var cnt = $("#contents_send_id_count").val();
            cnt = cnt.replace("건", "");

            if ($("#alarm_con_send").prop("checked")) {
                if ($("#alarm_msg1").val() == "") {
                    alert("메시지를 입력하세요.");
                    return;
                }
                $("#contents_send_type").val(1);
            } else {
                $("#contents_send_type").val(2);
            }

            if (cnt == 0) {
                alert("전송할 아이디를 입력하세요.");
                return;
            }
            var recv_con_ids = $("#contents_send_id").val();
            $.ajax({
                type: "POST",
                url: "iam/card_con_send.php",
                data: {
                    settle_type: "check_ids_con_send",
                    payMethod: recv_con_ids,
                },
                dataType: 'json',
                success: function(data) {
                    if (data == 5) {
                        alert("잘못된 회원 아이디 입니다.");
                    } else {
                        $("#settle_point").val(cnt * <?= $contents_send_point ?>);
                        $("#contents_send_modal_mem").modal("hide");
                        $("#point_settlement").modal("show");
                    }
                }
            });
        }

        function del_send_list(val, val1) {
            if (confirm("삭제하시겠습니까?")) {
                $.ajax({
                    type: "POST",
                    url: "/iam/card_con_send.php",
                    dataType: "json",
                    data: {
                        settle_type: "del",
                        no: val,
                        card_con: val1
                    },
                    success: function(data) {
                        alert("삭제되었습니다.");
                        location.reload();
                    }
                })
            }
        }

        function callback_list() {
            $("#mypage-modalwindow").modal("hide");
            $('#callback_msg_modal').modal('hide');
            $.ajax({
                type: "POST",
                url: "/ajax/edit_event_callback.php",
                data: {
                    get_callback_list: "Y",
                    mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                },
                dataType: "html",
                success: function(data) {
                    $("#callback_msg_set_list").html(data);
                    $('#callback_list_modal').modal('show');
                }
            });
        }

        function edit_ev_callback(id) {
            $('#callback_list_modal').modal('hide');
            start = '';
            end = '';
            item_type = '';
            see = '';
            $.ajax({
                type: "POST",
                url: "/ajax/edit_event_callback.php",
                dataType: "json",
                data: {
                    edit_ev: true,
                    start: start,
                    end: end,
                    item_type: item_type,
                    ID: '<?= $_SESSION['iam_member_id']; ?>',
                    more: see,
                    id: id
                },
                success: function(data) {
                    $("#call_event_idx").val(data.id);
                    $("#call_event_title").val(data.event_info);
                    $("#call_event_desc").val(data.event_sms_desc);
                    $("#call_img_event").html(data.img);
                    $("#call_iam_link").val(data.iam_link);
                    $("#call_short_url").val(data.short_url);
                    $("#call_read_cnt").val(data.read_cnt);
                    $("#call_regdate1").val(data.regdate);
                    $("#event_desc_call").val(data.event_desc_call);
                    $("#event_title_call").val(data.event_title_call);
                    if (data.allow_state == 1) {
                        $("#allow").prop("checked", true);
                        $("#notallow").prop("checked", false);
                    } else {
                        $("#allow").prop("checked", false);
                        $("#notallow").prop("checked", true);
                    }
                    $('#callback_list_edit_modal').modal('show');
                }
            });
        }

        function delete_ev_callback(id) {
            if (confirm("삭제하시겠습니까?")) {
                $.ajax({
                    type: "POST",
                    url: "/ajax/edit_event_callback.php",
                    dataType: "json",
                    data: {
                        del: true,
                        id: id
                    },
                    success: function(data) {
                        if (data == 1) {
                            alert('삭제 되었습니다.');
                            window.location.reload();
                        }
                    }
                })
            } else {
                return;
            }
        }

        function save_call_edit_ev() {
            $("#dForm_call_edit").submit();
        }

        function app_set_list(val, type) {
            if (type == "callback") {
                $("#send_type_call").val(val);
                if (val == "self") {
                    $("#send_type_title_call").html("셀프폰으로 발송하기");
                } else {
                    $("#send_type_title_call").html("푸시형으로 전송하기");
                }
                $("#app_sets_member_call").show();
            } else {
                $("#send_type_daily").val(val);
                if (val == "self") {
                    $("#send_type_title_daily").html("셀프폰으로 발송하기");
                } else {
                    $("#send_type_title_daily").html("푸시형으로 전송하기");
                }
                $("#app_sets_member_daily").show();
            }
            var win = window.open("/iam/set_apps_list.php?type=" + type, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=900");
        }

        function cancel_app_list(type) {
            if (type == "callback") {
                $("#app_sets_member_call").hide();
            } else {
                $("#app_sets_member_daily").hide();
            }
        }

        function send_msg_applist(type) {
            if (type == "callback") {
                var val = $("#send_type_call").val();
                var member_ids = $("#app_set_mbs_id_call").val();
                var message = $("#event_desc_call").val();
                var title = $("#event_title_call").val();
                var notice_link = $("#call_short_url").val();
            } else {
                var val = $("#send_type_daily").val();
                var member_ids = $("#app_set_mbs_id_daily").val();
                var message = $("#daily_event_desc_intro").val();
                var title = $("#daily_event_title_intro").val();
                var notice_link = $("#daily_short_url").val();
            }

            if (val == "self") {
                $.ajax({
                    type: "POST",
                    url: "/iam/card_con_send.php",
                    data: {
                        settle_type: "self_phone_send",
                        payMethod: member_ids,
                        message: message,
                        title: title,
                        notice_link: notice_link
                    },
                    dataType: 'json',
                    success: function(data) {
                        alert("전송되었습니다.");
                        location.reload();
                    }
                });
            } else {
                $.ajax({
                    type: "POST",
                    url: "/iam/card_con_send.php",
                    data: {
                        settle_type: "notice_send",
                        payMethod: member_ids,
                        pay_percent: 90,
                        allat_order_no: <?= $mid ?>,
                        point_val: 3,
                        message: message,
                        title: title,
                        notice_link: notice_link
                    },
                    dataType: 'json',
                    success: function(data) {
                        alert("전송되었습니다.");
                        location.reload();
                    }
                });
            }
        }

        function set_callback_state(obj) {
            var id = $(obj).find("input[type=checkbox]").val();
            var status = $(obj).find("input[type=checkbox]").is(":checked") == true ? "1" : "0";
            $.ajax({
                type: "POST",
                url: "/ajax/edit_event_callback.php",
                data: {
                    update_state: true,
                    id: id,
                    status: status
                },
                success: function(data) {
                    alert('신청되었습니다.');
                }
            })
        }

        function set_callback_time(callback_idx, obj) {
            $.ajax({
                type: "POST",
                url: "/ajax/edit_event_callback.php",
                data: {
                    update_callback_time: $(obj).val(),
                    callback_idx: callback_idx
                },
                dataType: "html",
                success: function(data) {
                    alert("콜백횟수가 수정되었습니다.")
                }
            });
        }

        function make_qr() {
            $("#total_service_modal").modal("hide");
            $("#qr_code_modal").modal("show");
            var url = window.location.href;
            var qrcode = new QRCode(document.getElementById("qr_code"), {
                text: url,
                width: 256,
                height: 256,
            });
        }
        //카드 콘텐츠 전송 리스트보기
        function search_send_list(val, val1, search_key) {
            var type = val;
            var more = val1;
            if (search_key == 'search')
                var search_key = $("#search_key_recv_con").val();
            else var search_key = '';
            $.ajax({
                type: "POST",
                url: "/ajax/card_con_send_list.php",
                dataType: "html",
                data: {
                    type: type,
                    more: more,
                    ID: '<?= $_SESSION['iam_member_id']; ?>',
                    search_key: search_key
                },
                success: function(data) {
                    // console.log(data);
                    if (type == "card")
                        $("#card_send_side").html(data);
                    else if (type == "contentssend")
                        $("#contents_send_side").html(data);
                    else
                        $("#contents_recv_side").html(data);
                }
            })
        }

        //카드, 콘텐츠 전송리스트
        function send_list(val) {
            if (val == "card") {
                $('#card_send_modal').modal('hide');
                $('#card_send_list_modal').modal('show');
                search_send_list('card');
            } else if (val == "contentssend") {
                $('#contents_send_modal').modal('hide');
                $('#contents_send_list_modal').modal('show');
                search_send_list('contentssend');
            }
        }

        //카드수신 승인하기
        function receive_card(val) {
            $.ajax({
                type: "POST",
                url: "/iam/card_con_send.php",
                dataType: "json",
                data: {
                    settle_type: "recv_card",
                    no: val,
                    sender: '<?= $sender_id ?>',
                    settle_point: <?= $card_send_point ?>
                },
                success: function(data) {
                    if (data == 3) {
                        alert('전송자의 포인트가 부족합니다. 전송자에게 문의해 주세요.');
                        return;
                    } else {
                        alert('정확히 수신 되었습니다.');
                        location.reload();
                    }
                }
            })
        }

        //콘텐츠수신 알림
        function receive_contents(val1, val2) {
            $.ajax({
                type: "POST",
                url: "/iam/card_con_send.php",
                dataType: "json",
                data: {
                    settle_type: "recv_contents",
                    no: val1
                },
                success: function(data) {
                    if (val2 != "") {
                        window.open(val2, '_blank');
                    }
                    $("#contents_recv_popup").modal("hide");
                }
            })
        }

        function receive_notice(val1, val2) {
            $.ajax({
                type: "POST",
                url: "/iam/card_con_send.php",
                dataType: "json",
                data: {
                    settle_type: "recv_notice",
                    no: val1
                },
                success: function(data) {
                    if (val2 != "") {
                        window.open(val2, '_blank');
                    }
                    $("#notice_recv_popup").modal("hide");
                }
            })
        }

        //수신 콘텐츠 조회수
        function show_cnt(val) {
            $.ajax({
                type: "POST",
                url: "/iam/card_con_send.php",
                dataType: "json",
                data: {
                    settle_type: "show_cnt",
                    no: val
                },
                success: function(data) {}
            })
        }

        function delete_all(val) {
            var method = val;
            $.ajax({
                type: "POST",
                url: "/ajax/card_con_send_list.php",
                dataType: "json",
                data: {
                    del_all: true,
                    method: method,
                    ID: '<?= $_SESSION['iam_member_id']; ?>'
                },
                success: function(data) {
                    // res = JSON.parse(data);
                    console.log(data);
                    if (val == "recv")
                        del_send_list(data.toString(), 'contents');
                    else {
                        alert('삭제 되었습니다.');
                        location.reload();
                    }
                }
            })
        }

        function refuse(val) {
            $.ajax({
                type: "POST",
                url: "/iam/card_con_send.php",
                dataType: "json",
                data: {
                    settle_type: "refuse",
                    no: val
                },
                success: function(data) {
                    alert("승인거부 되었습니다.");
                    location.reload();
                }
            })
        }
        //카드, 컨텐츠 전송 스크립트 끝

        //오토회원가입리스트 보여주기
        function show_automember_list() {
            $("#mypage-modalwindow").modal("hide");
            //$("#auto_list_modal").modal("show");
            $.ajax({
                type: "GET",
                url: "/iam/ajax/modal_auto_list.php",
                data: "start_date=" + '<?= $_REQUEST['search_start_date'] ?>' +
                    "&end_date=" + '<?= $_REQUEST['search_end_date'] ?>',
                success: function(html) {
                    $("#auto_list_modal").html('');
                    $("#auto_list_modal").append(html);
                    $("#auto_list_modal").modal("show");
                }
            });
        }

        //공지사항 전송 팝업
        function send_notice() {
            $("#mypage-modalwindow").modal("hide");
            $("#notice_send_id").val('<?= $send_ids ?>');
            $("#notice_send_id_count").val(<?= $send_ids_cnt ?> + '건');
            $('#notice_send_id_count').data('num', <?= $send_ids_cnt ?>);
            $("#notice_send_modal_mem").modal("show");
        }

        function notice_send_settle() {
            $("#point_pay_type").val(9);
            point_settle();
        }

        function remove_recv_notice(val) {
            $.ajax({
                type: "POST",
                url: "/iam/card_con_send.php",
                dataType: "json",
                data: {
                    settle_type: 'remove_notice',
                    no: val,
                    ID: '<?= $_SESSION['iam_member_id']; ?>'
                },
                success: function(data) {
                    alert("삭제 되었습니다.");
                    location.reload();
                    // return;
                }
            })
        }

        function notice_delete_all(val) {
            var method = val;
            $.ajax({
                type: "POST",
                url: "/ajax/notice_send_list.php",
                dataType: "json",
                data: {
                    del_all: true,
                    method: method,
                    ID: '<?= $_SESSION['iam_member_id']; ?>'
                },
                success: function(data) {
                    alert('삭제 되었습니다.');
                    location.reload();
                }
            })
        }

        function notice_send_recv_list(val, val1, search_key) {
            var type = val;
            var more = val1;
            if (search_key == 'search')
                var search_key = $("#search_key_recv_notice").val();
            else var search_key = '';
            $.ajax({
                type: "POST",
                url: "/ajax/notice_send_list.php",
                dataType: "html",
                data: {
                    type: type,
                    more: more,
                    ID: '<?= $_SESSION['iam_member_id']; ?>',
                    search_key: search_key
                },
                success: function(data) {
                    // console.log(data);
                    if (type == "noticerecv")
                        $("#notice_recv_side").html(data);
                    else
                        $("#notice_send_side").html(data);
                }
            })
        }

        function search_auto() {
            start = $("#search_start_date1").val();
            end = $("#search_end_date1").val();

            $.ajax({
                type: "POST",
                url: "/ajax/edit_event.php",
                dataType: "html",
                data: {
                    search: true,
                    start: start,
                    end: end,
                    ID: '<?= $_SESSION['iam_member_id']; ?>'
                },
                success: function(data) {
                    $("#contents_side1").html(data);
                }
            })
        }

        function newpop(str) {
            window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
        }

        function copyHtml(url) {
            var aux1 = document.createElement("input");
            // 지정된 요소의 값을 할당 한다.
            aux1.setAttribute("value", url);
            // bdy에 추가한다.
            document.body.appendChild(aux1);
            // 지정된 내용을 강조한다.
            aux1.select();
            // 텍스트를 카피 하는 변수를 생성
            document.execCommand("copy");
            // body 로 부터 다시 반환 한다.
            document.body.removeChild(aux1);
            alert("URL이 복사되었습니다. 원하는 곳에 붙여 넣으세요.");
        }

        function edit_ev(id) {
            $('#auto_list_modal').modal('hide');

            start = '';
            end = '';
            item_type = '';
            see = '';
            $.ajax({
                type: "POST",
                url: "/ajax/edit_event.php",
                dataType: "json",
                data: {
                    edit_ev: true,
                    start: start,
                    end: end,
                    item_type: item_type,
                    ID: '<?= $_SESSION['iam_member_id']; ?>',
                    more: see,
                    id: id
                },
                success: function(data) {
                    console.log(data);
                    // for(var i in data.res) {
                    // console.log(data.m_id);
                    $("#event_idx").val(data.id);
                    // $("#mem_id").val(data.m_id);
                    $("#event_title").val(data.event_title);
                    $("#event_desc").val(data.event_desc);
                    // $("#card_short_url").val(data.card_short_url);
                    $("#btn_title").val(data.btn_title);
                    $("#btn_link").val(data.btn_link);
                    $("#short_url").val(data.short_url);
                    $("#read_cnt").val(data.read_cnt);
                    $("#autojoin_img_event").html(data.autojoin_img);
                    $("#regdate1").val(data.regdate);
                    if (data.reserv_sms_id != undefined) {
                        $("#step_title").val(data.step_title);
                        $("#step_phone").val(data.send_num);
                        $("#step_cnt").val(data.step);
                        $("#step_allow_state").val(data.step_idx);

                        if (data.step_allow_state == 1) {
                            $("#step_allow_state").prop("checked", true);
                        } else {
                            $("#step_allow_state").prop("checked", false);
                        }

                        var href = '<a class="reserv_btn" href="javascript:view_step_list(' + data.id + ', `update`)">리스트변경</a> <a class="reserv_btn" href="/mypage_reservation_create.php?sms_idx=' + data.reserv_sms_id + '" target="_blank">내용수정</a>';
                        $("#step_info").html(href);

                        // $("#step_info_tr").show();
                    } else {
                        $("#step_title").val('');
                        $("#step_phone").val('');
                        $("#step_cnt").val('');
                        $("#step_allow_state").val('');
                        $("#step_allow_state").prop("checked", false);
                        var href = '<a class="reserv_btn" href="javascript:view_step_list(' + data.id + ', `insert`)">스텝문자조회</a>';
                        $("#step_info").html(href);
                    }

                    $('input[class=we_story_radio1]').prop("checked", false);
                    card_short_url = data.card_short_url;
                    var pos = card_short_url.search(",");
                    if (pos == -1) {
                        $('input[id=multi_westory_card_url1_' + card_short_url + ']').prop("checked", true);
                    } else {
                        var arr = card_short_url.split(",");
                        for (var k = 0; k < arr.length; k++) {
                            $('input[id=multi_westory_card_url1_' + arr[k] + ']').prop("checked", true);
                        }
                    }
                    // }

                    $('#auto_list_edit_modal').modal('show');
                }
            });

        }

        function view_step_list(idx, type) {
            var win = window.open("../mypage_pop_message_list_for_edit_autolist.php?event_idx=" + idx + "&type=" + type, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
        }

        function delay(time) {
            return new Promise((resolve) => setTimeout(resolve, time));
        }

        function playPauseVideo() {
            let videos = document.querySelectorAll("video");
            videos.forEach((video) => {
                var parent = video.closest('div')
                var play_btn = parent.querySelector('.movie_play');
                // We can only control playback without insteraction if video is mute
                video.muted = true;
                // Play is a promise so we need to check we have it
                let playPromise = video.play();
                if (playPromise !== undefined) {
                    playPromise.then((_) => {
                        let observer = new IntersectionObserver(
                            (entries) => {
                                entries.forEach((entry) => {
                                    if (entry.isIntersecting) {
                                        video.play();
                                        if (play_btn)
                                            play_btn.style.display = "none";
                                    } else {
                                        video.pause();
                                        if (play_btn)
                                            play_btn.style.display = "block";
                                    }
                                });
                            }, {
                                threshold: 0.2
                            }
                        );
                        observer.observe(video);
                    });
                }
            });
        }


        $(function() {
            $('.movie_play').each(function() {
                $(this).on("click", function() {
                    $(this).parent("div").find("video").trigger("play");
                    $(this).hide();
                });
            });
            // And you would kick this off where appropriate with:
            playPauseVideo();

            //delay(1000).then(() => toggle_sound());
            $('.unmute_btn').each(function() {
                $(this).on("click", function() {
                    $(this).parent().find("video").prop("muted", false);
                    $(this).hide();
                });
            });
            $('video').each(function() {
                $(this).on("click", function() {
                    if ($(this).prop("paused")) {
                        $(this).trigger("play");
                        $(this).parent("div").find(".movie_play").hide();
                    } else {
                        $(this).trigger("pause");
                        $(this).parent("div").find(".movie_play").show();
                    }
                });
            });
            $('#video_slide_box').on("change", function() {
                var status = $(this).find("input[type=checkbox]").is(":checked") == true ? "V" : "I";
                var name_card_idx = $("#slider_card_idx").val();
                var formData = new FormData();
                formData.append('mode', 'slide');
                formData.append('name_card_idx', name_card_idx);
                formData.append('status', status);
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/mainImage_up.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (status == 'V') {
                            $("#main_slide_video").css("display", "flex");
                            $("#main_slide_image").css("display", "none");
                            $('#main_upload1_link').hide();
                            $('#main_upload2_link').hide();
                            $('#main_upload3_link').hide();
                            if ($('input[name=main_type_v]:checked').val() == "u")
                                $('#main_upload_video_link').show();
                            else
                                $('#main_upload_video_link').hide();
                        } else {
                            $("#main_slide_video").css("display", "none");
                            $("#main_slide_image").css("display", "flex");
                            if ($('input[name=main_type1]:checked').val() == "u")
                                $('#main_upload1_link').show();
                            else
                                $('#main_upload1_link').hide();
                            $('#main_upload2_link').hide();
                            $('#main_upload3_link').hide();
                            $('#main_upload_video_link').hide();
                        }
                    }
                });
            });
            $('#cont_media_slide').on("change", function() {
                var status = $(this).find("input[type=checkbox]").is(":checked") == true ? "V" : "I";
                if (status == "V") {
                    click_cont_video();
                } else {
                    click_cont_image();
                }
            });
            $('#step_apply').on("change", function() {
                var id = $(this).find("input[type=checkbox]").val();
                var status = $(this).find("input[type=checkbox]").is(":checked") == true ? "1" : "0";
                $.ajax({
                    type: "POST",
                    url: "/ajax/edit_event.php",
                    data: {
                        update_state: true,
                        id: id,
                        status: status
                    },
                    success: function(data) {
                        alert('신청되었습니다.');
                    }
                })
            });
            $('.auto_switch').on("change", function() {
                var id = $(this).find("input[type=checkbox]").val();
                $(this).closest('#contents_side1').find("input[id!=auto_stauts_" + id + "]").prop("checked", false);
                var status = $(this).find("input[type=checkbox]").is(":checked") == true ? "1" : "0";
                var auto_service_id = $("#auto_service_id").val();
                console.log(id, status, auto_service_id);
                $.ajax({
                    type: "POST",
                    url: "/ajax/edit_event.php",
                    data: {
                        update_join_state: true,
                        id: id,
                        status: status,
                        mem_id: auto_service_id
                    },
                    success: function(data) {
                        alert('신청되었습니다.');
                    }
                })
            });

            $('.auto_switch_copy').on("change", function() {
                var id = $(this).find("input[type=checkbox]").val();
                $(this).closest('#contents_side1').find("input[id!=auto_stauts_" + id + "]").prop("checked", false);
                var status = $(this).find("input[type=checkbox]").is(":checked") == true ? "1" : "0";
                var auto_service_id = $("#auto_service_id").val();
                console.log(id, status, auto_service_id);
                $.ajax({
                    type: "POST",
                    url: "/ajax/edit_event.php",
                    data: {
                        duplicate_msg: true,
                        id: id,
                        status: status,
                        mem_id: auto_service_id
                    },
                    success: function(data) {
                        alert('신청되었습니다.');
                    }
                })
            });
        });

        function save_edit_ev() {
            $("#dForm_edit").submit();
        }

        function delete_ev(id) {
            if (confirm("삭제하시겠습니까?")) {
                $.ajax({
                    type: "POST",
                    url: "/ajax/edit_event.php",
                    dataType: "json",
                    data: {
                        del: true,
                        id: id
                    },
                    success: function(data) {
                        console.log(data);
                        if (data == 1) {
                            alert('삭제 되었습니다.');
                            window.location.reload();
                        }
                    }
                })
            } else {
                return;
            }
        }

        function limit_selcard1() {
            var sel_card1 = new Array();
            var cnt1;
            $('input[class=we_story_radio1]:checked').each(function() {
                var idVal1 = $(this).attr("value");
                // console.log(idVal);
                cnt1 = sel_card1.push(idVal1);
                if (cnt1 > 4) {
                    alert('최대 4개까지 선택할수 있습니다.');
                    $('input[id=multi_westory_card_url1_' + idVal1 + ']').prop("checked", false);
                    return;
                }
                $("#card_short_url").val(sel_card1.join(","));
            });
        }
        //오토회원가입 리스트 보여주기 스크립트 끝

        //튜토리얼 스크립트
        function start_tutorial() {
            $("#tutorial_popup").modal("hide");
            $(".color-chips").attr("style", "position:relative");
            $("#tooltiptext_card_edit").show();
            $('body,html').animate({
                scrollTop: 330,
            }, 100);
            $("#tutorial-loading").show();
        }

        function start_card_tutorial(type) { //새카드 만들기 튜토리얼
            $.ajax({
                type: "POST",
                url: "/iam/ajax/card_mode.proc.php",
                dataType: "json",
                data: {
                    start_card: true,
                    mem_id: '<?= $_SESSION['iam_member_id'] ?>',
                    type: type
                },
                success: function(data) {
                    if (data.mem_code == "iam_card_cnt") {
                        alert(data.result);
                        location.reload();
                    } else if (data.mem_code != "success") {
                        location.href = "?" + data.short_url + data.mem_code + "&tutorial=Y";
                    }
                }
            })
        }

        function open_payment_item() {
            $.ajax({
                type: "POST",
                url: "/iam/card_con_send.php",
                dataType: "json",
                data: {
                    settle_type: "payment_show"
                },
                success: function(data) {
                    location.href = "/iam/mypage_payment_item.php";
                }
            })
        }


        function add_post(content_idx) { //댓글추가
            save_load('<?= $_SESSION['iam_member_id'] ?>', content_idx, 2);
            var post_content = $("#post_content" + content_idx);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/add_post.php",
                dataType: "json",
                data: {
                    content_idx: content_idx,
                    mode: 'add',
                    mem_id: '<?= $_SESSION['iam_member_id'] ?>',
                    post_content: post_content.val()
                },
                success: function(data) {
                    if (data.result == "success") {
                        reloadShareCount(data.share_recv_count, data.share_send_count, data.share_post_count);
                        $("#post_content" + content_idx).val("");
                        $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                        if (data.post_status > 0) {
                            $('#post_alarm_' + content_idx).show();
                            $('#post_alarm_' + content_idx).html(data.post_status);
                        } else {
                            $('#post_alarm_' + content_idx).hide();
                        }
                        $("#post_list_" + content_idx).empty();
                        for (var i in data.contents) {
                            add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name, data.contents[i].reg_date,
                                data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                            for (var reply_index in data.contents[i].reply_content) {
                                var reply_content = data.contents[i].reply_content[reply_index];
                                add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
                                    reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                            }
                        }
                    } else {
                        if (data.message == "login") {
                            location.href = "/iam/login.php?contents_idx=" + content_idx;
                        } else {
                            alert(data.message);
                        }
                    }
                }
            })
        }

        function delete_post(content_idx, post_idx) { //댓글 삭제
            var post_content = $("#post_content" + content_idx);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/add_post.php",
                dataType: "json",
                data: {
                    content_idx: content_idx,
                    post_idx: post_idx,
                    mode: 'del',
                    mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                },
                success: function(data) {
                    reloadShareCount(data.share_recv_count, data.share_send_count, data.share_post_count);
                    $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                    if (data.post_status > 0) {
                        $('#post_alarm_' + content_idx).show();
                        $('#post_alarm_' + content_idx).html(data.post_status);
                    } else {
                        $('#post_alarm_' + content_idx).hide();
                    }
                    $("#post_list_" + content_idx).empty();
                    for (var i in data.contents) {
                        add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
                            data.contents[i].reg_date, data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                        for (var reply_index in data.contents[i].reply_content) {
                            var reply_content = data.contents[i].reply_content[reply_index];
                            add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
                                reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                        }
                    }
                }
            });
        }

        function delete_post_reply(content_idx, reply_idx) { //답글 삭제
            var post_content = $("#post_content" + content_idx);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/add_post.php",
                dataType: "json",
                data: {
                    content_idx: content_idx,
                    post_idx: reply_idx,
                    mode: 'delete_reply',
                    mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                },
                success: function(data) {
                    reloadShareCount(data.share_recv_count, data.share_send_count, data.share_post_count);
                    $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                    if (data.post_status > 0) {
                        $('#post_alarm_' + content_idx).show();
                        $('#post_alarm_' + content_idx).html(data.post_status);
                    } else {
                        $('#post_alarm_' + content_idx).hide();
                    }
                    $("#post_list_" + content_idx).empty();
                    for (var i in data.contents) {
                        add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
                            data.contents[i].reg_date, data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                        for (var reply_index in data.contents[i].reply_content) {
                            var reply_content = data.contents[i].reply_content[reply_index];
                            add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
                                reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                        }
                    }
                }
            });
        }

        function lock_post(content_idx, post_idx) { //댓글 차단
            var post_content = $("#post_content" + content_idx);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/add_post.php",
                dataType: "json",
                data: {
                    content_idx: content_idx,
                    post_idx: post_idx,
                    mode: 'lock',
                    mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                },
                success: function(data) {
                    reloadShareCount(data.share_recv_count, data.share_send_count, data.share_post_count);
                    $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                    if (data.post_status > 0) {
                        $('#post_alarm_' + content_idx).show();
                        $('#post_alarm_' + content_idx).html(data.post_status);
                    } else {
                        $('#post_alarm_' + content_idx).hide();
                    }
                    $("#post_list_" + content_idx).empty();
                    for (var i in data.contents) {
                        add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
                            data.contents[i].reg_date, data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                        for (var reply_index in data.contents[i].reply_content) {
                            var reply_content = data.contents[i].reply_content[reply_index];
                            add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
                                reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                        }
                    }
                }
            });
        }

        function lock_post_reply(content_idx, reply_idx) { //답글 차단
            var post_content = $("#post_content" + content_idx);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/add_post.php",
                dataType: "json",
                data: {
                    content_idx: content_idx,
                    post_idx: reply_idx,
                    mode: 'lock_reply',
                    mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                },
                success: function(data) {
                    reloadShareCount(data.share_recv_count, data.share_send_count, data.share_post_count);
                    $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                    if (data.post_status > 0) {
                        $('#post_alarm_' + content_idx).show();
                        $('#post_alarm_' + content_idx).html(data.post_status);
                    } else {
                        $('#post_alarm_' + content_idx).hide();
                    }
                    $("#post_list_" + content_idx).empty();
                    for (var i in data.contents) {
                        add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
                            data.contents[i].reg_date, data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                        for (var reply_index in data.contents[i].reply_content) {
                            var reply_content = data.contents[i].reply_content[reply_index];
                            add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
                                reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                        }
                    }
                }
            });
        }

        function edit_post(content_idx, post_idx, content) {
            var post_content = $("#post_content" + content_idx);
            $("#post_content" + content_idx).val(content);
            var post_wrap = $("#post_content" + content_idx).parents(".post-wrap");
            var status_lbl = post_wrap.find("#post_status");
            status_lbl.html($("#post_content" + content_idx).val().length + '/300');
            post_wrap.find("#send_post").attr("onClick", "update_post(" + content_idx + "," + post_idx + ");");
        }

        function update_post(content_idx, post_idx) {
            var post_content = $("#post_content" + content_idx);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/add_post.php",
                dataType: "json",
                data: {
                    content_idx: content_idx,
                    post_idx: post_idx,
                    mode: 'edit',
                    mem_id: '<?= $_SESSION['iam_member_id'] ?>',
                    post_content: post_content.val()
                },
                success: function(data) {
                    reloadShareCount(data.share_recv_count, data.share_send_count, data.share_post_count);
                    $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                    if (data.post_status > 0) {
                        $('#post_alarm_' + content_idx).show();
                        $('#post_alarm_' + content_idx).html(data.post_status);
                    } else {
                        $('#post_alarm_' + content_idx).hide();
                    }
                    $("#post_list_" + content_idx).empty();
                    for (var i in data.contents) {
                        add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name, data.contents[i].reg_date,
                            data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                        for (var reply_index in data.contents[i].reply_content) {
                            var reply_content = data.contents[i].reply_content[reply_index];
                            add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
                                reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                        }
                    }
                }
            });
            post_content.val("");
            var post_wrap = post_content.parents(".post-wrap");
            var status_lbl = post_wrap.find("#post_status");
            status_lbl.html($("#post_content" + content_idx).val().length + '/300');
            post_wrap.find("#send_post").attr("onClick", "add_post(" + content_idx + ");");
        }

        function edit_post_reply(content_idx, post_idx, reply_idx, content) {
            var post_reply_content = $("#post_reply_" + post_idx + "_content");
            var post_wrap = post_reply_content.parents(".post_reply_wrap");
            var status_lbl = post_wrap.find("#post_reply_status");
            if ($("#post_reply_" + post_idx).css('display') == "none") {
                $("#post_reply_" + post_idx).show();
                post_reply_content.html(content);
                status_lbl.html(content.length + '/300');
                post_wrap.find("button").attr("onClick", "update_post_reply(" + content_idx + "," + post_idx + "," + reply_idx + ");");
            } else {
                $("#post_reply_" + post_idx).hide();
                post_reply_content.html("");
                status_lbl.html('0/300');
                post_wrap.find("button").attr("onClick", "add_post_reply(" + content_idx + "," + post_idx + "," + reply_idx + ");");
            }
        }

        function update_post_reply(content_idx, post_idx, reply_idx) {
            var post_content = $("#post_reply_" + post_idx + "_content");
            $.ajax({
                type: "POST",
                url: "/iam/ajax/add_post.php",
                dataType: "json",
                data: {
                    content_idx: content_idx,
                    post_idx: reply_idx,
                    mode: 'edit_reply',
                    mem_id: '<?= $_SESSION['iam_member_id'] ?>',
                    post_content: post_content.val()
                },
                success: function(data) {
                    reloadShareCount(data.share_recv_count, data.share_send_count, data.share_post_count);
                    $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                    if (data.post_status > 0) {
                        $('#post_alarm_' + content_idx).show();
                        $('#post_alarm_' + content_idx).html(data.post_status);
                    } else {
                        $('#post_alarm_' + content_idx).hide();
                    }
                    $("#post_list_" + content_idx).empty();
                    for (var i in data.contents) {
                        add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name, data.contents[i].reg_date,
                            data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                        for (var reply_index in data.contents[i].reply_content) {
                            var reply_content = data.contents[i].reply_content[reply_index];
                            add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
                                reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                        }
                    }
                }
            });
            post_content.val("");
            var post_wrap = post_content.parents(".post-wrap");
            var status_lbl = post_wrap.find("#post_status");
            status_lbl.html($("#post_content" + content_idx).val().length + '/300');
            post_wrap.find("#send_post").attr("onClick", "add_post(" + content_idx + ");");
        }

        function add_post_reply(content_idx, post_idx) { //답글추가
            save_load('<?= $_SESSION['iam_member_id'] ?>', content_idx, 2);
            var post_content = $("#post_reply_" + post_idx + "_content");
            $.ajax({
                type: "POST",
                url: "/iam/ajax/add_post.php",
                dataType: "json",
                data: {
                    content_idx: content_idx,
                    post_idx: post_idx,
                    mode: 'add_reply',
                    mem_id: '<?= $_SESSION['iam_member_id'] ?>',
                    post_content: post_content.val()
                },
                success: function(data) {
                    if (data.result == "success") {
                        reloadShareCount(data.share_recv_count, data.share_send_count, data.share_post_count);
                        $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                        if (data.post_status > 0) {
                            $('#post_alarm_' + content_idx).show();
                            $('#post_alarm_' + content_idx).html(data.post_status);
                        } else {
                            $('#post_alarm_' + content_idx).hide();
                        }
                        $("#post_list_" + content_idx).empty();
                        for (var i in data.contents) {
                            add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
                                data.contents[i].reg_date, data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                            for (var reply_index in data.contents[i].reply_content) {
                                var reply_content = data.contents[i].reply_content[reply_index];
                                add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
                                    reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                            }
                        }
                    } else {
                        if (data.message == "login") {
                            location.href = "/iam/login.php?contents_idx=" + content_idx;
                        } else {
                            alert(data.message);
                        }
                    }
                }
            })
        }

        function show_post_reply(post_idx) {
            if ($("#post_reply_" + post_idx).css('display') == "none")
                $("#post_reply_" + post_idx).show();
            else
                $("#post_reply_" + post_idx).hide();
        }

        function add_one_post(content_idx, post_idx, name_card, imglink, mem_name, reg_date, post_content, post_mem, login_mem, card_mem) {
            if (imglink == "")
                imglink = '/iam/img/profile_img.png';
            var cont = "<div class=\"user-item\" id=\"post_reply" + post_idx + "\">" +
                "<a href=\"/?" + name_card + "\" class=\"img-box\">" +
                "<div class=\"user-img\" style=\"margin: 5px;width:32px;height:32px;\">" +
                "<img src=\"" + imglink + "\" >" +
                "</div>" +
                "</a>" +
                "<div class=\"wrap\" style=\"margin:10px 0px;\">" +
                "<span class=\"date\">" +
                mem_name + ' ' + reg_date +
                "</span>" +
                "<span class=\"user-name\">" +
                post_content +
                "</span>" +
                "</div>";
            if (post_mem == login_mem) {
                cont += "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">" +
                    "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">" +
                    "<ul class=\"dropdown-menu namecard-dropdown \" style=\"background: white; color : black;top:10px;\">" +
                    "<li>" +
                    "<a href=\"javascript:void(0)\" onclick=\"edit_post('" + content_idx + "','" + post_idx + "','" + post_content + "')\" title=\"댓글 수정\">" +
                    "<p>수정</p>" +
                    "</a>" +
                    "</li>" +
                    "<li>" +
                    "<a href=\"javascript:void(0)\" onclick=\"delete_post('" + content_idx + "','" + post_idx + "')\" title=\"댓글 삭제\">" +
                    "<p>삭제</p>" +
                    "</a>" +
                    "</li>" +
                    "</ul>" +
                    "</div>";
            } else if (card_mem == login_mem) {
                cont += "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:0px\">" +
                    "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/main/custom.png\" style=\"height: 20px;\">" +
                    "<ul class=\"dropdown-menu namecard-dropdown \" style=\"background: white; color : black;top:10px;\">" +
                    "<li>" +
                    "<a href=\"javascript:void(0)\" onclick=\"delete_post('" + content_idx + "','" + post_idx + "')\" title=\"댓글 삭제\">" +
                    "<p>삭제</p>" +
                    "</a>" +
                    "</li>" +
                    "<li>" +
                    "<a href=\"javascript:void(0)\" onclick=\"lock_post('" + content_idx + "','" + post_idx + "')\" title=\"댓글 차단\">" +
                    "<p>차단</p>" +
                    "</a>" +
                    "</li>" +
                    "</ul>" +
                    "</div>";
            }
            cont += "<div style=\"position: absolute;left: 60px;bottom: 0px\">" +
                "<span style=\"color: #bdbdbd;cursor:pointer;font-size:13px\" onclick=\"show_post_reply(" + post_idx + ");\">" +
                "답글달기" +
                "</span>" +
                "</div>" +
                "</div>" +
                "<div id = \"post_reply_" + post_idx + "\"  class = \"post_reply_wrap\" style=\"display: none;margin : 10px 0px\" >" +
                "<div style=\"display: flex;justify-content: flex-end;\">" +
                "<div style=\"margin-left:60px;margin-right:15px;width:100%\">" +
                "<textarea id = \"post_reply_" + post_idx + "_content\" name = \"post_reply_" + post_idx + "_content\" class=\"post_reply_content\" maxlength=\"300\" placeholder=\"답글은 300자 이내로 작성해주세요\" style=\"font-size:14px;width: 100%;height:35px;border: 1px;\"></textarea>" +
                "</div>" +
                "<div style=\"\">" +
                "<button type=\"button\" class=\"btn btn-link\" style=\"font-size:14px; padding: 5px 12px;color:#99cc00\" onclick=\"add_post_reply('" + content_idx + "','" + post_idx + "')\">작성</button>" +
                "</div>" +
                "</div>" +
                "<div style=\"border-bottom: 0px solid #dddddd;margin-left:60px\">" +
                "<span id = \"post_reply_status\" name = \"post_reply_status\" style=\"padding: 10px\">0/300</span>" +
                "</div>" +
                "</div>";
            $('#post_list_' + content_idx).append(cont);
        }

        function add_one_reply(content_idx, post_id, reply_id, namecard, profile, mem_name, reg_date, reply_content, post_mem, login_mem, card_mem) {
            if (profile == "")
                profile = '/iam/img/profile_img.png';
            var cont = "<div class=\"user-item\" style=\"padding-left: 50px\">" +
                "<a href=\"/?" + namecard + "\" class=\"img-box\">" +
                "<div class=\"user-img\" style=\"margin: 5px;width:32px;height:32px;\">" +
                "<img src=\"" + profile + "\">" +
                "</div>" +
                "</a>" +
                "<div class=\"wrap\">" +
                "<span class=\"date\">" +
                mem_name + " " + reg_date +
                "</span>" +
                "<span class=\"user-name\" id=\"reply_list_" + reply_id + "\">" +
                reply_content +
                "</span>" +
                "</div>";
            if (post_mem == login_mem) {
                cont += "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">" +
                    "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">" +
                    "<ul class=\"dropdown-menu namecard-dropdown\" style=\"background: white; color : black;top:10px;\">" +
                    "<li>" +
                    "<a href=\"javascript:void(0)\" onclick=\"edit_post_reply('" + content_idx + "','" + post_id + "','" + reply_id + "','" + reply_content + "')\" title=\"답글 수정\">" +
                    "<p>수정</p>" +
                    "</a>" +
                    "</li>" +
                    "<li>" +
                    "<a href=\"javascript:void(0)\" onclick=\"delete_post_reply('" + content_idx + "','" + reply_id + "')\" title=\"답글 삭제\">" +
                    "<p>삭제</p>" +
                    "</a>" +
                    "</li>" +
                    "</ul>" +
                    "</div>";
            } else if (card_mem == login_mem) {
                cont += "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">" +
                    "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">" +
                    "<ul class=\"dropdown-menu namecard-dropdown\" style=\"background: white; color : black;top:10px;\">" +
                    "<li>" +
                    "<a href=\"javascript:void(0)\" onclick=\"delete_post_reply('" + content_idx + "','" + reply_id + "')\" title=\"답글 삭제\">" +
                    "<p>삭제</p>" +
                    "</a>" +
                    "</li>" +
                    "<li>" +
                    "<a href=\"javascript:void(0)\" onclick=\"lock_post_reply('" + content_idx + "','" + reply_id + "')\" title=\"답글 차단\">" +
                    "<p>차단</p>" +
                    "</a>" +
                    "</li>" +
                    "</ul>" +
                    "</div>";
            }
            cont += "</div>";
            $('#post_list_' + content_idx).append(cont);
        }

        function refresh_post(content_idx) { //refresh
            $.ajax({
                type: "POST",
                url: "/iam/ajax/add_post.php",
                dataType: "json",
                data: {
                    content_idx: content_idx,
                    mode: 'refresh',
                    mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                },
                success: function(data) {
                    reloadShareCount(data.share_recv_count, data.share_send_count, data.share_post_count);
                    $("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
                    if (data.post_status > 0) {
                        $('#post_alarm_' + content_idx).show();
                        $('#post_alarm_' + content_idx).html(data.post_status);
                    } else {
                        $('#post_alarm_' + content_idx).hide();
                    }
                    $("#post_list_" + content_idx).empty();
                    for (var i in data.contents) {
                        add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
                            data.contents[i].reg_date, data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                        for (var reply_index in data.contents[i].reply_content) {
                            var reply_content = data.contents[i].reply_content[reply_index];
                            add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
                                reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
                        }
                    }
                },
                error: function(data) {
                    //console.log(data);
                }
            })
        }

        function reloadShareCount(share_recv_count, share_send_count, share_post_count) {
            var share_count = share_recv_count * 1 + share_send_count * 1 + share_post_count * 1;
            if (share_count == 0) {
                $('#share_count').hide();
                $('#share_recv_count').hide();
                $('#share_send_count').hide();
                $('#share_post_count').hide();
            } else {
                $('#share_count').html(share_count);
                $('#share_count').show();
                if (share_recv_count <= 0) {
                    $('#share_recv_count').hide();
                } else {
                    $('#share_recv_count').html(share_recv_count);
                    $('#share_recv_count').show();
                }

                if (share_send_count <= 0) {
                    $('#share_send_count').hide();
                } else {
                    $('#share_send_count').html(share_send_count);
                    $('#share_send_count').show();
                }

                if (share_post_count <= 0) {
                    $('#share_post_count').hide();
                } else {
                    $('#share_post_count').html(share_post_count);
                    $('#share_post_count').show();
                }
            }
        }

        function mainImageUpload() {
            var name_card_idx = $("#slider_card_idx").val();
            var formData = new FormData();
            formData.append('mode', 'up');
            formData.append('name_card_idx', name_card_idx);
            if ($('#main_upload1')[0].files.length) {
                formData.append('uploadFile1', $('#main_upload1')[0].files[0]);
            } else {
                formData.append('uploadFile1_link', $('#main_upload1_link').val());
            }
            if ($('#main_upload2')[0].files.length) {
                formData.append('uploadFile2', $('#main_upload2')[0].files[0]);
            } else {
                formData.append('uploadFile2_link', $('#main_upload2_link').val());
            }
            if ($('#main_upload3')[0].files.length) {
                formData.append('uploadFile3', $('#main_upload3')[0].files[0]);
            } else {
                formData.append('uploadFile3_link', $('#main_upload3_link').val());
            }
            <? if ($video_upload_status == 'Y') { ?>
                if ($('#videoInput')[0].files.length) {
                    formData.append('uploadVideo', $('#videoInput')[0].files[0]);
                } else {
                    formData.append('uploadVideo_link', $('#main_upload_video_link').val());
                }
            <? } ?>
            $.ajax({
                type: "POST",
                url: "/iam/ajax/mainImage_up.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var mainImg = data.split("|")
                    $("#main_img1").attr("src", mainImg[0]);
                    $("#main_img2").attr("src", mainImg[1]);
                    $("#main_img3").attr("src", mainImg[2]);
                    toastr.success('메인이미지가 업로드 되었습니다.');
                    <? if ($_GET['tutorial'] == "Y") { ?>
                        $(".main_image_popup").hide();
                        $("#tooltiptext_contents_up").show();
                        $('body,html').animate({
                            scrollTop: 530,
                        }, 100);
                        $("#tutorial-loading").show();
                    <? } else { ?>
                        location.reload();
                    <? } ?>
                }
            });
        }
        //대표이미지 삭제
        function mainImageDel(img_name, del_status) {
            if (confirm('정말로 삭제하시겠습니까?')) {
                var formData = new FormData();
                if (del_status == 1) {
                    formData.append('mode', 'del');
                    formData.append('img_name', img_name);
                    var name_card_idx = "<?= $cur_card['idx'] ?>";
                    formData.append('name_card_idx', name_card_idx);
                    $.ajax({
                        type: "POST",
                        url: "/iam/ajax/mainImage_up.php",
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            toastr.success(data);
                            if (img_name == "main_img1") {
                                $("#main_upload1_link").val("");
                                $('#main_upload_img1').css('background-image', "");
                                $("#main_img1").css("background-image", "url('')");
                                $("#main_img1").attr("href", "#");
                                $("#for_size_1").attr("src", "");
                            } else if (img_name == "main_img2") {
                                $("#main_upload2_link").val("");
                                $('#main_upload_img2').css('background-image', "");
                                $("#main_img2").css("background-image", "url('')");
                                $("#main_img2").attr("href", "#");
                                $("#for_size_2").attr("src", "");
                            } else if (img_name == "main_img3") {
                                $("#main_upload3_link").val("");
                                $('#main_upload_img3').css('background-image', "");
                                $("#main_img3").css("background-image", "url('')");
                                $("#main_img3").attr("href", "#");
                                $("#for_size_3").attr("src", "");
                            } else if (img_name == "video") {
                                $('#main_video_preview').attr('src', '');
                                //$('#main_video_preview').load();
                                //$('#main_video_preview').play();
                                $('#main_upload_video_link').val('');
                            }
                        }
                    });
                } else {
                    toastr.success("대표이미지가 삭제 되었습니다.");
                    if (img_name == "main_img1") {
                        $("#main_upload1_link").val("");
                        $('#main_upload_img1').css('background-image', "url()");
                        $("#main_img1").css("background-image", "url('')");
                        $("#main_img1").attr("href", "#");
                        $("#for_size_1").attr("src", "");
                    } else if (img_name == "main_img2") {
                        $("#main_upload2_link").val("");
                        $('#main_upload_img2').css('background-image', "url()");
                        $("#main_img2").css("background-image", "url('')");
                        $("#main_img2").attr("href", "#");
                        $("#for_size_2").attr("src", "");
                    } else if (img_name == "main_img3") {
                        $("#main_upload3_link").val("");
                        $('#main_upload_img3').css('background-image', "url()");
                        $("#main_img3").css("background-image", "url('')");
                        $("#main_img3").attr("href", "#");
                        $("#for_size_3").attr("src", "");
                    } else if (img_name == "video") {

                    }
                }
            }
        }
        //명함 휴대폰번호 노출
        function card_phone_display(idx, id, status) {
            var formData = new FormData();
            formData.append("mode", "display");
            formData.append('card_idx', idx);
            formData.append('mem_id', id);
            formData.append("phone_display", status);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/card_favorite.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    var card_data = (JSON.parse(data));
                    if (card_data.phone_display == "Y") {
                        if (status == "N")
                            alert("< " + card_data.card_name + " > 카드는 비공개 될수 없습니다.");
                        else
                            alert("< " + card_data.card_name + " > 카드가 공개 되었습니다.");
                    } else {
                        alert("< " + card_data.card_name + " > 카드가 비공개 되었습니다.");
                    }
                    location.reload();
                }
            });
        }

        // 카카오톡
        try {
            Kakao.init("<?php echo $domainData['kakao_api_key'] != "" ? $domainData['kakao_api_key'] : "c0550dad4e9fdb8a298f6a5ef39ebae6"; ?>"); // 사용할 앱의 JavaScript 키를 설정
            //Kakao.init("2e50869591823e28ed57afa55ff56b47");      // 사용할 앱의 JavaScript 키를 설정
        } catch (e) {
            console.log("Kakao 로딩 failed : " + e);
        }
        //명함 즐겨찾기
        function card_favorite(idx, id) {
            var formData = new FormData();
            formData.append("mode", "favorite");
            formData.append('card_idx', idx);
            formData.append('mem_id', id);
            if (Number($('#favorite').val())) {
                formData.append('favorite', 0);
            } else {
                formData.append('favorite', 1);
            }
            $.ajax({
                type: "POST",
                url: "/iam/ajax/card_favorite.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    // console.log(data);
                    $('#favorite').val(data);
                }
            });
        }
        // 명함 삭제
        function namecard_del(idx) {
            var formData = new FormData();
            if (confirm("<?= $MENU['IAM_CONTENTS']['DELETE_MSG']; ?>")) {
                formData.append('mode', "del");
                formData.append('mem_id', "<?= $_SESSION['iam_member_id'] ?>");
                formData.append('card_idx', idx);
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/namecard.proc.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alert("명함이 삭제 되었습니다.");
                        if (data == 1) {
                            location.href = "index.php";
                        } else {
                            location.href = "index.php?" + data;
                        }
                    }
                });
            }
        }

        function contact_range(v1) {
            if (Number($("#contact_range").val()) == 1 && Number(v1) == 1) {
                getIamContact('<?= $card_owner ?>', '<?= $card_master ?>', 2, '<?= $phone_count ?>', 1, 0);
                $("#contact_range").val('2');
            } else if (Number($("#contact_range").val()) == 2 && Number(v1) == 1) {
                getIamContact('<?= $card_owner ?>', '<?= $card_master ?>', 1, '<?= $phone_count ?>', 1, 0);
                $("#contact_range").val('1');
            } else if (Number($("#contact_range").val()) == 3 && Number(v1) == 3) {
                getIamContact('<?= $card_owner ?>', '<?= $card_master ?>', 4, '<?= $phone_count ?>', 1, 0);
                $("#contact_range").val('4');
            } else if (Number($("#contact_range").val()) == 4 && Number(v1) == 3) {
                getIamContact('<?= $card_owner ?>', '<?= $card_master ?>', 3, '<?= $phone_count ?>', 1, 0);
                $("#contact_range").val('3');
            } else {
                getIamContact('<?= $card_owner ?>', '<?= $card_master ?>', v1, '<?= $phone_count ?>', 1, 0);
                $("#contact_range").val(v1);
            }
        }

        function paper_range(v1) {
            if (Number($("#paper_range").val()) == 1 && Number(v1) == 1) {
                getIamPaper('<?= $card_owner ?>', '<?= $card_master ?>', 2, '<?= $phone_count ?>', 1, 0);
                $("#paper_range").val('2');
            } else if (Number($("#paper_range").val()) == 2 && Number(v1) == 1) {
                getIamPaper('<?= $card_owner ?>', '<?= $card_master ?>', 1, '<?= $phone_count ?>', 1, 0);
                $("#paper_range").val('1');
            } else if (Number($("#paper_range").val()) == 3 && Number(v1) == 3) {
                getIamPaper('<?= $card_owner ?>', '<?= $card_master ?>', 4, '<?= $phone_count ?>', 1, 0);
                $("#paper_range").val('4');
            } else if (Number($("#paper_range").val()) == 4 && Number(v1) == 3) {
                getIamPaper('<?= $card_owner ?>', '<?= $card_master ?>', 3, '<?= $phone_count ?>', 1, 0);
                $("#paper_range").val('3');
            } else {
                getIamPaper('<?= $card_owner ?>', '<?= $card_master ?>', v1, '<?= $phone_count ?>', 1, 0);
                $("#paper_range").val(v1);
            }
        }

        // 연락처 검색
        function contact_submit() {
            getIamContact('<?= $card_owner ?>', '<?= $card_master ?>', '<?= $search_range ?>', '<?= $phone_count ?>', 1, 0);
        }
        // 종이명함 검색
        function paper_submit() {
            getIamPaper('<?= $card_owner ?>', '<?= $card_master ?>', '<?= $search_range ?>', '<?= $phone_count ?>', 1, 0);
        }

        function contact_submit_paper() {
            getIamContact('<?= $card_owner ?>', '<?= $card_master ?>', '<?= $search_range ?>', '<?= $phone_count ?>', 1, 1);
        }
        //연락처 선택 2020-11-09
        function contact_chk_count() {
            $("#contact_chk_count").text($("input[name=contact_chk]:checked").length + "개 선택됨");
        }
        //종이명함 선택 2024-10-13
        function paper_chk_count() {
            $("#paper_chk_count").text($("input[name=paper_chk]:checked").length + "개 선택됨");
        }
        //연락처 전화하기
        function contact_call() {
            if ($("input[name=contact_chk]:checked").length > 1) {
                alert("전화는 한명만 가능합니다.");
                return false;
            } else {
                location.href = "tel:" + $("#contact_idx" + $("input[name=contact_chk]:checked").val()).val();
            }
        }
        //연락처 문자발송
        function contact_sms() {
            iam_count('iam_sms');
            var sms = "";
            for (var i = 0; i < $("input[name=contact_chk]:checked").length; i++) {
                sms = sms + $("#contact_idx" + $("input[name=contact_chk]:checked").eq(i).val()).val() + ",";
            }
            sms = sms.substring(0, sms.length - 1);
            var navCase = navigator.userAgent.toLocaleLowerCase();
            if (navCase.search("android") > -1) {
                location.href = "sms:" + sms +
                    "<? echo (preg_match('/iPhone/', $_SERVER['HTTP_USER_AGENT'])) ? '&' : '?'; ?>body=" +
                    $(".J_card_name").text().trim() +
                    "님의 명함 <?= htmlspecialchars($cur_card['card_company']) ?> <?= htmlspecialchars($cur_card['card_position']) ?> <?= $cur_card['card_phone'] ?> <?= str_replace("http:", "https:", $domainData['sub_domain']); ?>/?<?= $cur_card['card_short_url'] ?>";
            } else {
                alert("휴대폰에서 이용해주세요.");
            }
        }
        //종이명함 문자발송
        function paper_sms() {
            iam_count('iam_sms');
            var sms = "";
            for (var i = 0; i < $("input[name=paper_chk]:checked").length; i++) {
                sms = sms + $("#paper_idx" + $("input[name=paper_chk]:checked").eq(i).val()).val() + ",";
            }
            sms = sms.substring(0, sms.length - 1);
            var navCase = navigator.userAgent.toLocaleLowerCase();
            if (navCase.search("android") > -1) {
                location.href = "sms:" + sms +
                    "<? echo (preg_match('/iPhone/', $_SERVER['HTTP_USER_AGENT'])) ? '&' : '?'; ?>body=" +
                    $(".J_card_name").text().trim() +
                    "님의 명함 <?= htmlspecialchars($cur_card['card_company']) ?> <?= htmlspecialchars($cur_card['card_position']) ?> <?= $cur_card['card_phone'] ?> <?= str_replace("http:", "https:", $domainData['sub_domain']); ?>/?<?= $cur_card['card_short_url'] ?>";
            } else {
                alert("휴대폰에서 이용해주세요.");
            }
        }
        //연락처 삭제
        function contact_del() {
            var formData = new FormData();
            formData.append('mode', "del");
            for (i = 0; i < $("input[name=contact_chk]:checked").length; i++) {
                formData.append('contact_idx[]', $("input[name=contact_chk]:checked").eq(i).val());
            }
            $.ajax({
                type: "POST",
                url: "/iam/ajax/contact.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    alert(data);
                    //location.reload();
                    for (i = 0; i < $("input[name=contact_chk]:checked").length; i++) {
                        var delItem = $("input[name=contact_chk]:checked").eq(i).parents(".list-item");
                        delItem.remove();
                    }
                }
            })
        }
        //종이명함 삭제
        function paper_del() {
            var formData = new FormData();
            formData.append('mode', "del");
            for (i = 0; i < $("input[name=paper_chk]:checked").length; i++) {
                formData.append('contact_idx[]', $("input[name=paper_chk]:checked").eq(i).val());
            }
            $.ajax({
                type: "POST",
                url: "/iam/ajax/contact.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    alert(data);
                    //location.reload();
                    for (i = 0; i < $("input[name=paper_chk]:checked").length; i++) {
                        var delItem = $("input[name=paper_chk]:checked").eq(i).parents(".list-item");
                        delItem.remove();
                    }
                }
            })
        }
        //프렌즈 정렬
        function friends_range(v1) {
            if (Number($("#friends_range").val()) == 1 && Number(v1) == 1) {
                $("#friends_range").val('2');
                getIamFriends('<?= $card_owner ?>', '<?= $card_master ?>', '<?= $phone_count ?>', 2, 1);
            } else if (Number($("#friends_range").val()) == 2 && Number(v1) == 1) {
                $("#friends_range").val('1');
                getIamFriends('<?= $card_owner ?>', '<?= $card_master ?>', '<?= $phone_count ?>', 1, 1);

            } else if (Number($("#friends_range").val()) == 3 && Number(v1) == 3) {
                $("#friends_range").val('4');
                getIamFriends('<?= $card_owner ?>', '<?= $card_master ?>', '<?= $phone_count ?>', 4, 1);

            } else if (Number($("#friends_range").val()) == 4 && Number(v1) == 3) {
                $("#friends_range").val('3');
                getIamFriends('<?= $card_owner ?>', '<?= $card_master ?>', '<?= $phone_count ?>', 3, 1);

            } else {
                $("#friends_range").val(v1);
                getIamFriends('<?= $card_owner ?>', '<?= $card_master ?>', '<?= $phone_count ?>', v1, 1);
            }
        }
        //프렌즈 검색
        function friends_submit(val) {
            $("#show_all").val(val);
            getIamFriends('<?= $card_owner ?>', '<?= $card_master ?>', '<?= $phone_count ?>', $("#friends_range").val(), 1);
        }
        //프렌즈 체크박스
        function friends_chk_count() {
            $("#friends_chk_count").text($("input[name=friends_chk]:checked").length + "개 선택됨");
        }
        //프렌즈 삭제
        function friends_del() {
            var formData = new FormData();

            if (confirm("정말 프렌즈를 삭제 하시겠습니까?")) {
                formData.append('mode', "del");
                for (i = 0; i < $("input[name=friends_chk]:checked").length; i++) {
                    var idx = $("#friends_card_idx" + $("input[name=friends_chk]:checked").eq(i).val()).val();
                    //formData.append('friends_idx[]', $("input[name=friends_chk]:checked").eq(i).val());
                    formData.append('friends_idx[]', idx);
                }
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/friends_join.proc.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alert(data);
                        location.reload();
                    }
                });
            }
        }
        //여러프렌즈 등록
        function friends_add(card_idx) {
            var formData = new FormData();
            if (confirm("정말 프렌즈로 추가 하시겠습니까?")) {
                formData.append('mode', "add_multi");
                if (card_idx) {
                    formData.append('friends_idx[]', card_idx);
                } else {
                    for (var i = 0; i < $("input[name=friends_chk]:checked").length; i++) {
                        formData.append('friends_idx[]', $("input[name=friends_chk]:checked").eq(i).val());
                    }
                }
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/friends_join.proc.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alert(data);
                        location.reload();
                    }
                });
            }
        }
        //프렌즈 등록
        function friends_join(card) {
            var formData = new FormData();
            formData.append('mode', "add_one");
            formData.append('card_idx', card);
            formData.append('card_url', $('#card_url').val());
            $.ajax({
                type: "POST",
                url: "/iam/ajax/friends_join.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    toastr.info(data, "프렌즈");
                }
            });
        }
        //콘텐츠에서 게시자 프렌즈 등록
        function set_friend(mem_id, card_name, card_short_url, card_idx) {
            var m_card_url = '<?= $_SERVER['SERVER_NAME'] ?>/iam?' + card_short_url;
            var formData = new FormData();
            formData.append('mode', "add_one");
            formData.append('card_idx', card_idx);
            formData.append('card_url', m_card_url);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/friends_join.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    toastr.info(data, "프렌즈");
                }
            });
        }
        //프렌즈 문자발송
        function friends_sms() {
            iam_count('iam_sms');
            var sms = "";
            for (var i = 0; i < $("input[name=friends_chk]:checked").length; i++) {
                sms = sms + $("#friends_idx" + $("input[name=friends_chk]:checked").eq(i).val()).val() + ",";
            }
            sms = sms.substring(0, sms.length - 1);
            location.href = "sms:" + sms +
                "<? echo (preg_match('/iPhone/', $_SERVER['HTTP_USER_AGENT'])) ? '&' : '?'; ?>body=" +
                $(".J_card_name").text().trim() +
                "님의 명함 <?= htmlspecialchars($cur_card['card_company']) ?> <?= htmlspecialchars($cur_card['card_position']) ?> <?= $cur_card['card_phone'] ?> <?= str_replace("http:", "https:", $domainData['sub_domain']); ?>/?<?= $cur_card['card_short_url'] ?>";
        }
        //콘텐츠 삭제
        function contents_del(idx, gwc_idx, seperate, gwc_con_state, group_id = 0) {
            if (gwc_idx != 0) {
                alert('굿마켓의 상품정보는 IAMSTORE 도매몰에서 관리하므로 여기서 수정/삭제할수 없습니다. 수정이 필요하면 도매몰 관리자에게 말해주시고, 삭제는 도매몰로 내보내기하면 됩니다.');
                return;
            }
            var formData = new FormData();
            if (confirm("콘텐츠를 삭제 하시겠습니까?")) {
                formData.append("post_type", "del");
                formData.append("contents_idx", idx);
                formData.append("product_seperate", seperate);
                formData.append("gwc_con_state", gwc_con_state);
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/contents.proc.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alert(data);
                        if (group_id == 0)
                            location.reload();
                        else
                            open_group_contents(group_id);
                    }
                });
            }
        }
        //콘텐츠 순서 up
        function contents_range_up(idx, temp, card_url, seperate, gwc_con_state, max = "") {
            var formData = new FormData();
            if (max == "max")
                formData.append("post_type", "range_up_max");
            else
                formData.append("post_type", "range_up");
            formData.append("contents_idx", idx);
            formData.append("contents_order", temp);
            formData.append("card_url", card_url);
            formData.append("product_seperate", seperate);
            formData.append("gwc_con_state", gwc_con_state);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/contents.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    location.reload();
                }
            });
        }

        //콘텐츠 순서 down
        function contents_range_down(idx, temp, card_url, seperate, gwc_con_state, min = "") {
            var formData = new FormData();
            if (min == "min")
                formData.append("post_type", "range_down_min");
            else
                formData.append("post_type", "range_down");
            formData.append("contents_idx", idx);
            formData.append("contents_order", temp);
            formData.append("card_url", card_url);
            formData.append("product_seperate", seperate);
            formData.append("gwc_con_state", gwc_con_state);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/contents.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    //alert(data);
                    //console.log(data);
                    location.reload();
                }
            });
        }

        // 콘텐츠 좋아요
        function contents_like(idx, like_id, seperate, gwc_con_state) {
            if (like_id == '') {
                toastr.success("로그인후에 클릭할수 있습니다.", "좋아요");
                return;
            }
            var formData = new FormData();
            formData.append("mode", "like");
            formData.append("contents_idx", idx);
            formData.append("like_id", like_id);
            formData.append("product_seperate", seperate);
            formData.append("gwc_con_state", gwc_con_state);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/contents_like.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(data) {
                    if (data.like_status == "Y") {
                        toastr.success("성공되었습니다.", "좋아요");
                        $("#like_img_" + idx).prop("src", "/iam/img/menu/icon_like_active.png");
                    } else {
                        toastr.success("삭제되었습니다.", "좋아요");
                        $("#like_img_" + idx).prop("src", "/iam/img/menu/icon_like.png");
                    }
                    $(".like_" + idx).html(data.count + "개");
                }
            });
        }

        function enterkey(v1) {
            if (window.event.keyCode == 13) {
                // 엔터키가 눌렸을 때 실행할 내용
                if (v1 == 'contact_submit') {
                    contact_submit();
                } else if (v1 == 'paper_submit') {
                    paper_submit();
                } else {
                    friends_submit('<?= $show_all ?>');
                }
            }
        }

        function iam_icon(str, url) {
            iam_count(str);
            window.open(url);
        }

        function iam_mystory(url) {
            if (url == "cur_win=unread_notice") {
                $.ajax({
                    type: "POST",
                    url: "/iam/card_con_send.php",
                    data: {
                        settle_type: 'read_all',
                        mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                    },
                    dataType: "json",
                    success: function(data) {

                    }
                });
                location.href = "?" + url + "&modal=Y";
            } else if (url.indexOf("type=image") != -1) {
                iam_count('iam_mystory');
                setCookie('contents_mode', 'image', 1, "");
                location.href = "?" + url;
            } else if (url.indexOf("type=pin") != -1) {
                iam_count('iam_mystory');
                setCookie('contents_mode', 'pin', 1, "");
                location.href = "?" + url;
            } else if (url == "cur_win=request_list") {
                location.href = "?" + url;
            } else {
                iam_count('iam_mystory');
                location.href = "?" + url;
            }
        }

        function iam_count(str) {
            var member_id = '<?= $card_owner ?>';
            var card_idx = '<?= $cur_card['idx'] ?>';
            var formData = new FormData();
            formData.append('str', str);
            formData.append('mem_id', member_id);
            formData.append('card_idx', card_idx);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/iam_count.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {

                }
            });
        }

        function showIframeModal(url) {
            window.open(url, "", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=600");
        }

        function showContentsList(val) {
            if (val == '4') {
                var media_height = 'height:' + $(".gwc_con_img").width() + 'px;';
                var scroll = "overflow-y: hidden;";
            } else {
                var media_height = '';
                var scroll = "overflow-y: hidden;";
            }
            <? if ($_GET['cur_win'] == "best_sample" ||  $_GET['cur_win'] == "unread_notice") { ?>
                return;
            <? } ?>
            var contents_mode = getCookie('contents_mode');
            var cur_win = '<?= $cur_win ?>';
            var img_ht = $(".media-inner").width();
            var img_ht1 = img_ht * 2;
            var img_ht2 = img_ht * 1 / 2;
            if (cur_win == "my_info") {
                if (contents_mode == "pin") {
                    contents_mode = "image";
                    $("#show_contents_mode").prop("src", '/iam/img/main/icon-pin_list.png');
                    $("#show_contents_mode").prop("title", '[이미지타일로 보기] 콘텐츠방식을 변경합니다.');
                    //$(".movie_play").css("width","100px");
                    $("#bottom").attr("style", "");
                    $(".content_area").attr('style', '');
                    $("#star #bottom .content-item .media-wrap .media-inner").attr('style', 'max-height: 100%;' + scroll);
                    $(".gwc_con_img").attr('style', 'height: ' + img_ht1 + 'px');
                    <? if (!$cur_win || $cur_win == "my_info") { ?>
                        $("#contents_image .user-item").hide();
                    <? } else { ?>
                        $("#contents_image .user-item").show();
                    <? } ?>
                    $(".desc-wrap").show();
                    $(".image_mode").show();
                    $(".pin_mode").hide();
                    $(".service_title").show();
                    if ($(window).width() < 420) {
                        //$(".movie_play").css("width","50px");
                        $(".percent").attr("style", "width:80px;font-size:30px");
                        $(".downer").attr("style", "font-size:16px;");
                        $("#star #bottom .content-item .desc-wrap .desc .desc-inner .outer .order a").attr("style", "padding:5px 15px; font-size:14px;background: #99cc00;cursor: pointer;");
                        $(".buy_servicecon").attr("style", "left:-30px !important;");
                        $("#star #bottom .content-item .media-wrap .media-inner").attr('style', 'max-height: 350px;' + scroll);
                        $(".gwc_con_img").attr('style', 'height: ' + img_ht + 'px');
                    }
                    $(".info-wrap").show();
                    var contents_list = $("div[id='contents_list']");
                    contents_list.each(function() {
                        $(this).hide();
                    });
                    var contents_image = $("div[id='contents_image']");
                    contents_image.each(function() {
                        $(this).show();
                    });
                } else {
                    contents_mode = "pin";
                    $("#show_contents_mode").prop("src", '/iam/img/icon_image.png');
                    $("#show_contents_mode").prop("title", '[이미지로 보기] 콘텐츠방식을 변경합니다.');
                    //$(".movie_play").css("width","50px");
                    if (key1_gwc_show == '4') {
                        if (wide_show == 'Y') {
                            var bottom_wt = $("#bottom").width();
                            var cont_wt = bottom_wt * 1 / 4 - 2.5;
                        } else {
                            var bottom_wt = $("#bottom").width();
                            var cont_wt = bottom_wt * 1 / 2 - 2;
                        }
                        $(".content_area").attr('style', 'width:' + cont_wt + 'px;display:inline-block;vertical-align:top;');
                    } else {
                        $("#bottom").attr("style", "column-count:2");
                    }
                    $("#contents_image .user-item").hide();
                    $(".image_mode").hide();
                    $(".pin_mode").show();
                    $("#star #bottom .content-item .media-wrap .media-inner").attr('style', 'max-height: 350px;' + scroll);
                    $(".gwc_con_img").attr('style', 'height: ' + img_ht + 'px');
                    if ($(window).width() < 360) {
                        $(".percent").attr("style", "width:30px;font-size:12px");
                        $(".upper").attr("style", "font-size:9px;");
                        $(".downer").attr("style", "font-size:10px;");
                        $("#star #bottom .content-item .desc-wrap .desc .desc-inner .outer .order a").attr("style", "padding:1px 2px; font-size:10px;background: #99cc00;cursor: pointer;");
                        $(".buy_servicecon").attr("style", "left:-30px !important;");
                    } else if ($(window).width() < 420) {
                        $(".percent").attr("style", "width:43px;font-size:17px");
                        $(".downer").attr("style", "font-size:13px;");
                        $("#star #bottom .content-item .desc-wrap .desc .desc-inner .outer .order a").attr("style", "padding:1px 2px; font-size:10px;background: #99cc00;cursor: pointer;");
                        $(".buy_servicecon").attr("style", "left:-30px !important;");
                    }
                    //$(".info-wrap").hide();
                    var contents_list = $("div[id='contents_list']");
                    contents_list.each(function() {
                        $(this).hide();
                    });
                    var contents_image = $("div[id='contents_image']");
                    contents_image.each(function() {
                        $(this).show();
                    });
                }
            } else if (cur_win == "iam_mall") {
                if (contents_mode == "pin") {
                    contents_mode = "image";
                    $("#show_contents_mode").prop("src", '/iam/img/main/icon-pin_list.png');
                    $("#show_contents_mode").prop("title", '[이미지타일로 보기] 콘텐츠방식을 변경합니다.');
                    $(".col-xs-6").removeClass("col-xs-6").addClass("col-xs-12");
                    $(".mall-content").css("height", "35%");
                    $(".mall-content-message").css("height", "15%");
                    $(".mall-img").css("height", "70%");
                    $(".mall-img-message").css("height", "85%");
                    $(".square").css("padding-bottom", "80%");
                } else {
                    contents_mode = "pin";
                    $("#show_contents_mode").prop("src", '/iam/img/icon_image.png');
                    $("#show_contents_mode").prop("title", '[이미지로 보기] 콘텐츠방식을 변경합니다.');
                    $(".col-xs-12").removeClass("col-xs-12").addClass("col-xs-6");
                    $(".mall-content").css("height", "48%");
                    $(".mall-content-message").css("height", "23%");
                    $(".mall-img").css("height", "50%");
                    $(".mall-img-message").css("height", "77%");
                    $(".square").css("padding-bottom", "120%");
                }
            } else {
                if (contents_mode == "pin") {
                    contents_mode = "image";
                    $("#show_contents_mode").prop("src", '/iam/img/main/icon-pin_list.png');
                    $("#show_contents_mode").prop("title", '[이미지타일로 보기] 콘텐츠방식을 변경합니다.');
                    //$(".movie_play").css("width","100px");
                    $("#bottom").attr("style", "");
                    $(".content_area").attr('style', '');
                    $("#star #bottom .content-item .media-wrap .media-inner").attr('style', 'max-height: 650px;' + scroll);
                    $(".gwc_con_img").attr('style', 'height: ' + img_ht1 + 'px');
                    <? if (!$cur_win) { ?>
                        $("#contents_image .user-item").hide();
                    <? } else { ?>
                        $("#contents_image .user-item").show();
                    <? } ?>
                    $(".desc-wrap").show();
                    $(".image_mode").show();
                    $(".pin_mode").hide();
                    $(".service_title").show();
                    $("#star #bottom .content-item .desc-wrap .desc .desc-inner .outer .order a").attr("style", "padding:5px 15px; font-size:14px;background: #99cc00;cursor: pointer;");
                    $(".buy_servicecon a").attr("style", "font-size:12px;background: #99cc00;cursor: pointer;");
                    if ($(window).width() < 420) {
                        //$(".movie_play").css("width","50px");
                        $(".percent").attr("style", "width:80px;font-size:30px");
                        $(".downer").attr("style", "font-size:16px;");
                        $(".buy_servicecon").attr("style", "left:-30px !important;");
                        $("#star #bottom .content-item .media-wrap .media-inner").attr('style', 'max-height: 350px;' + scroll);
                        $(".gwc_con_img").attr('style', 'height: ' + img_ht + 'px');
                    }

                    $(".info-wrap").show();
                    var contents_list = $("div[id='contents_list']");
                    contents_list.each(function() {
                        $(this).hide();
                    });
                    var contents_image = $("div[id='contents_image']");
                    contents_image.each(function() {
                        $(this).show();
                    });
                } else {
                    contents_mode = "pin";
                    $("#show_contents_mode").prop("src", '/iam/img/icon_image.png');
                    $("#show_contents_mode").prop("title", '[이미지로 보기] 콘텐츠방식을 변경합니다.');
                    //$(".movie_play").css("width","50px");
                    if (key1_gwc_show == '4') {
                        if (wide_show == 'Y') {
                            var bottom_wt = $("#bottom").width();
                            var cont_wt = bottom_wt * 1 / 4 - 2.5;
                        } else {
                            var bottom_wt = $("#bottom").width();
                            var cont_wt = bottom_wt * 1 / 2 - 2;
                        }
                        $(".content_area").attr('style', 'width:' + cont_wt + 'px;display:inline-block;vertical-align:top;');
                    } else {
                        $("#bottom").attr("style", "column-count:2");
                    }
                    $("#contents_image .user-item").show();
                    $(".image_mode").hide();
                    $(".pin_mode").show();
                    $("#star #bottom .content-item .media-wrap .media-inner").attr('style', 'max-height: 350px;' + scroll);
                    $(".gwc_con_img").attr('style', 'height: ' + img_ht2 + 'px');
                    if ($(window).width() < 360) {
                        $(".percent").attr("style", "width:30px;font-size:12px");
                        $(".upper").attr("style", "font-size:9px;");
                        $(".downer").attr("style", "font-size:10px;");
                        $("#star #bottom .content-item .desc-wrap .desc .desc-inner .outer .order a").attr("style", "padding:1px 2px; font-size:10px;background: #99cc00;cursor: pointer;");
                        $(".buy_servicecon").attr("style", "left:-30px !important;");
                    } else if ($(window).width() < 420) {
                        $(".percent").attr("style", "width:43px;font-size:17px");
                        $(".downer").attr("style", "font-size:13px;");
                        $("#star #bottom .content-item .desc-wrap .desc .desc-inner .outer .order a").attr("style", "padding:1px 2px; font-size:10px;background: #99cc00;cursor: pointer;");
                        $(".buy_servicecon").attr("style", "left:-30px !important;");
                    }
                    var contents_list = $("div[id='contents_list']");
                    contents_list.each(function() {
                        $(this).hide();
                    });
                    var contents_image = $("div[id='contents_image']");
                    contents_image.each(function() {
                        $(this).show();
                    });
                }
            }
            if (val != 'gwc')
                contents_mode_gwc_ori = contents_mode;
            setCookie('contents_mode', contents_mode, 1, "");
        }

        function showmystory() {
            $("#myTabContent").find(".tab-pane").each(function() {
                $(this).removeClass('active in');
            });
            $("#myTab").find(".nav-item").each(function() {
                $(this).removeClass('in active');
            });
            $("#story").addClass('active in');
            /*$('body,html').animate({
                scrollTop: 800 ,
                }, 100
            );*/
        }

        function showSNSModal(cur_win, preview = 'N') {
            if (cur_win != "my_info") {
                alert("마이콘에서만 이용가능합니다.");
                return;
            }
            $("#sns-modalwindow").modal("show");
            $("#card_share_preview").val(preview);
        }
        var j_idx;
        var gwc_state_share;
        var j_name;
        var j_title;
        var j_desc;
        var j_img;

        function showSNSModal_byContents(idx, gwc_state_share_val /*,name,title,desc,img*/ ) {
            j_idx = idx;
            gwc_state_share = gwc_state_share_val;
            j_name = $("#contents_user_name_" + j_idx).val();
            j_title = $("#contents_title_" + j_idx).val();
            j_desc = $("#contents_desc_" + j_idx).val();
            var content_img_array = [];
            content_img_array = $("#contents_img_" + j_idx).val().split(",");
            j_img = content_img_array[0];
            $("#sns-modalwindow_contents").modal("show");
        }

        function downloadURI() {
            var uri = "http://www.kiam.kr/data/onlyone-release.apk";
            if (checkMobile())
                uri = "intent://www.kiam.kr/data/onlyone-release.apk#Intent;scheme=http;package=com.android.chrome;S.browser_fallback_url=http%3A%2F%2Fwww.kiam.kr%2Fdata%2Fonlyone-release.apk;end;";
            var name = "app_link";
            var link = document.createElement("a");
            link.setAttribute('download', name);
            link.href = uri;
            document.body.appendChild(link);
            link.click();
            link.remove();
            //alert("다운로드 중입니다.잠시후 폰 파일창 또는 설치파일창에서 확인하세요.");
        }
        // ############# 스크립트부분##################
        var card = null;
        var moving = false;
        var src_idx = null;
        //gpt chat script
        var contextarray = [];

        function show_chat(api) {
            $("#gpt_chat_modal").modal('show');
        }
        $(document).ready(function() {
            $("#question").on('keyup', function() {
                text_height()
            });
            var textarea = document.getElementById("question");
            var limit = 110; //height limit
            var api_state = '<?= $member_iam['gpt_chat_api_key'] ?>';

            textarea.oninput = function() {
                textarea.style.height = "";
                textarea.style.height = Math.min(textarea.scrollHeight, limit) + "px";
            };

            $("#question").on('keydown', function(event) {
                if (api_state == '') {
                    alert("회원정보에서 본인의 API 키를 입력해주세요.");
                    location.href = "mypage.php";
                }
                if (event.keyCode == 13) {
                    if (event.shiftKey) {
                        $("#kw-target").html($("#kw-target").html() + "\n");
                        event.stopPropagation();
                    } else {
                        send_post('<?= $_SESSION['iam_member_id'] ?>');
                    }
                }
            });
        });

        function check_login(id) {
            if (id == '') {
                $("#intro_modal").modal('show');
            } else {
                return;
            }
        }

        function show_new_chat() {
            $("#answer_side").hide();
            $("#gpt_req_list_title").hide();
            $("#answer_side1").show();
            $("#answer_side2").hide();
        }

        function show(val) {
            if ($('li[id=a' + val + ']').hasClass('hided')) {
                $('li[id=a' + val + ']').removeClass('hided');
                $('i[id=down' + val + ']').css('display', 'none');
                $('i[id=up' + val + ']').css('display', 'inline-block');
            } else {
                $('li[id=a' + val + ']').addClass('hided');
                $('i[id=down' + val + ']').css('display', 'inline-block');
                $('i[id=up' + val + ']').css('display', 'none');
            }
        }

        function show_req_history() {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/manage_gpt_chat.php",
                data: {
                    mem_id: "<?= $_SESSION['iam_member_id'] ?>",
                    method: 'show_req_list'
                },
                dataType: 'html',
                success: function(data) {
                    // console.log(data);
                    $("#answer_side").hide();
                    $("#answer_side1").hide();
                    $("#gpt_req_list_title").show();
                    $("#answer_side2").html(data);
                    $("#answer_side2").show();
                }
            });
        }

        function copy_msg() {
            var value = $("#answer_side").text().trim();
            console.log(value.trim());
            // return;
            var aux1 = document.createElement("input");
            // 지정된 요소의 값을 할당 한다.
            aux1.setAttribute("value", value);
            // bdy에 추가한다.
            document.body.appendChild(aux1);
            // 지정된 내용을 강조한다.
            aux1.select();
            // 텍스트를 카피 하는 변수를 생성
            document.execCommand("copy");
            // body 로 부터 다시 반환 한다.
            document.body.removeChild(aux1);
            alert("복사되었습니다. 원하는 곳에 붙여 넣으세요.");
        }

        function del_list(id) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/manage_gpt_chat.php",
                data: {
                    method: 'del_req_list',
                    id: id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.result == "1") {
                        alert('삭제 되었습니다.');
                        show_req_history();
                    } else {
                        alert('삭제실패 되었습니다.');
                    }
                }
            });
        }

        function text_height() {
            if ($("#question").height() >= 80) {
                $(".send_ask").css('height', '100%');
            } else {
                $(".send_ask").css('height', '98%');
            }
        }

        function articlewrapper(question, answer, str) {
            $("#answer_side").html('<li class="article-title" id="q' + answer + '"><img src="/iam/img/chat_Q.png" style="width:30px;margin-right: 10px;"><span class="chat_title"></span></li>');
            let str_ = ''
            let i = 0
            let timer = setInterval(() => {
                if (str_.length < question.length) {
                    str_ += question[i++]
                    $("#q" + answer).children('span').text(str_ + '_') //인쇄할 때 커서 추가
                } else {
                    clearInterval(timer)
                    $("#q" + answer).children('span').text(str_) //인쇄할 때 커서 추가
                }
            }, 5)
            $("#answer_side").append('<li class="article-content" id="' + answer + '"><img src="/iam/img/chat_A.png" style="width:30px;"><span class="chat_answer" style="margin-left: 35px;"></span></li>');
            if (str == null || str == "") {
                str = "서버가 응답하는 데 시간이 걸리면 나중에 다시 시도할 수 있습니다.";
            }
            let str2_ = ''
            let i2 = 0
            let timer2 = setInterval(() => {
                if (str2_.length < str.length) {
                    str2_ += str[i2++]
                    $("#" + answer).children('span').text(str2_ + '_') //인쇄할 때 커서 추가
                } else {
                    clearInterval(timer2)
                    $("#" + answer).children('span').text(str2_) //인쇄할 때 커서 추가

                }

                $('#answer_side').animate({
                    scrollTop: 10000,
                }, 10);
            }, 25)
        }

        // function send_post(mem_id) {
        //     $("#answer_side1").hide();
        //     $("#answer_side2").hide();
        //     $("#answer_side").show();
        //     var prompt = $("#question").val();
        //     if (prompt == "") {
        //         alert('질문을 입력해 주세요.');
        //         return;
        //     }

        //     $.ajax({
        //         cache: true,
        //         type: "POST",
        //         url: "/iam/ajax/message.php",
        //         data: {
        //             mem_id:mem_id,
        //             message: prompt,
        //             context:$("#keep").prop("checked")?JSON.stringify(contextarray):'[]',
        //         },
        //         dataType: "json",
        //         success: function (results) {
        //             $("#question").val("");
        //             $("#question").css("height", "58px");
        //             $(".send_ask").css("height", "98%");
        //             contextarray.push([prompt, results.raw_message]);
        //             articlewrapper(prompt,randomString(16),results.raw_message);
        //         }
        //     });
        // }

        function randomString(len) {
            len = len || 32;
            var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678'; //혼란스러운 문자는 기본적으로 제거됩니다oOLl,9gq,Vv,Uu,I1
            var maxPos = $chars.length;
            var pwd = '';
            for (i = 0; i < len; i++) {
                pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
            }
            return pwd;
        }

        function send_chat() {
            var title = $("#answer_side span.chat_title").text();
            var detail = $("#answer_side span.chat_answer").text();
            if (title == "") {
                alert('질문해주세요.');
                return;
            }
            $("#contents_title").val(title);
            $("#contents_desc").val(detail);
            $("#gpt_chat_modal").modal('hide');
        } //gpt chat

        //req_delivery_set
        function self_deliver() {
            if ($("#same_gonggupsa").prop('checked')) {
                $("#deliver_id").val('<?= $_SESSION['iam_member_id'] ?>');
                $("#deliver_name").val('<?= $member_iam['mem_name'] ?>');
                $("#deliver_phone").val('<?= $member_iam['mem_phone'] ?>');
                $("#deliver_addr").val('<?= $member_iam['mem_add1'] ?>');
                $("#deliver_email").val('<?= $member_iam['mem_email'] ?>');
                $("#deliver_bank").val('<?= $member_iam['bank_name'] ?>');
                $("#deliver_owner").val('<?= $member_iam['bank_owner'] ?>');
                $("#deliver_account").val('<?= $member_iam['bank_account'] ?>');
                $("#check_deliver_id").hide();
                $("#deliver_id_code").val('<?= $member_iam['mem_code'] ?>');
                if ($("#deliver_id").val() != '' && $("#deliver_name").val() != '' && $("#deliver_phone").val() != '' && $("#deliver_addr").val() != '' && $("#deliver_email").val() != '' && $("#deliver_bank").val() != '' && $("#deliver_owner").val() != '' && $("#deliver_account").val() != '') {
                    $("#check_deliver_id_state").val('Y');
                } else {
                    $("#check_deliver_id_state").val('N');
                }
            } else {
                $("#deliver_id").val('');
                $("#deliver_name").val('');
                $("#deliver_phone").val('');
                $("#deliver_addr").val('');
                $("#deliver_email").val('');
                $("#deliver_bank").val('');
                $("#deliver_owner").val('');
                $("#deliver_account").val('');
                $("#deliver_id_code").val('');
                $("#check_deliver_id_state").val('N');
                $("#check_deliver_id").show();
            }
        }

        function check_deliver_id() {
            var req_deliver_id = $("#deliver_id").val();
            if (req_deliver_id == '') {
                alert('아이디를 입력해 주세요.');
                $("#deliver_id").focus();
                $("#check_deliver_id_state").val('N');
                return;
            } else {
                $.ajax({
                    type: "POST",
                    url: "/ajax/get_mem_address.php",
                    dataType: "json",
                    data: {
                        deliver_id: req_deliver_id,
                        mode: "check_deliver_id"
                    },
                    success: function(data) {
                        if (data.result == "0") {
                            alert('정확한 아이디를 입력하세요.');
                            $("#check_deliver_id_state").val('N');
                            return;
                        } else {
                            $("#deliver_id_code").val(data.mem_code);
                            $("#deliver_id").val(data.mem_id);
                            $("#deliver_name").val(data.mem_name);
                            $("#deliver_phone").val(data.mem_phone);
                            $("#deliver_addr").val(data.mem_add1);
                            $("#deliver_email").val(data.mem_email);
                            $("#deliver_bank").val(data.bank_name);
                            $("#deliver_owner").val(data.bank_owner);
                            $("#deliver_account").val(data.bank_account);
                            if ($("#deliver_id").val() != '' && $("#deliver_name").val() != '' && $("#deliver_phone").val() != '' && $("#deliver_addr").val() != '' && $("#deliver_email").val() != '' && $("#deliver_bank").val() != '' && $("#deliver_owner").val() != '' && $("#deliver_account").val() != '') {
                                $("#check_deliver_id_state").val('Y');
                            } else {
                                $("#check_deliver_id_state").val('N');
                            }
                        }
                    }
                });
            }
        }

        function gwc_worker() {
            if ($("#gwc_worker_state_").prop('checked')) {
                $("#worker_no_side").css('display', 'flex');
                $("#worker_img_side").css('display', 'flex');
                // $("#save_req_provider_side").show();
                $("#gwc_worker_state").val(1);
            } else {
                $("#worker_no_side").css('display', 'none');
                $("#worker_img_side").css('display', 'none');
                // $("#save_req_provider_side").hide();
                $("#gwc_worker_state").val(0);
            }
        }
        //req_delivery_set

        function save_req_provider() {
            $("#req_provider_form").submit();
        }

        function show_gwc_model_info() {
            alert('<?= get_search_key('gwc_model_info') ?>');
        }

        function check_dup_model_name() {
            var model_name = $("#product_model_name").val();
            $.ajax({
                type: "POST",
                url: "/iam/ajax/product_mng.php",
                dataType: "json",
                data: {
                    model_name: model_name,
                    mode: "check_model_name"
                },
                success: function(data) {
                    if (data.result == "0") {
                        $("#confirm_model").attr("style", "background-color:#99cc00;color: white;padding: 5px;border-radius: 7px;margin: 5px;cursor:pointer;");
                        alert("모델명 중복이 없으므로 업로드 가능합니다. 혹시 모델명 기입 미스로 중복이 있으면 삭제될 수 있습니다.");
                    } else {
                        alert("중복상품이 있으니 다시 검토해서 올려주세요.");
                    }
                }
            });
        }

        function show_alarm_no() {
            alert("지금 개발중입니다.");
            $("#gwc_con_state1").prop("checked", true);
            $("#gwc_con_state2").prop("checked", false);
            return;
        }

        function show_gwc_type() {
            $("#prod_cms").show();
            $("#prod_provide").show();
            $("#prod_manufact").show();
            $("#prod_salary").show();
            $("#min_price").html('최저가격');
            $("#contents_sell_price").attr('placeholder', '해당상품의 인터넷 최저가를 입력해주세요.');
        }

        function show_call_type() {
            $("#prod_cms").hide();
            $("#prod_provide").hide();
            $("#prod_manufact").hide();
            $("#prod_salary").hide();
            $("#min_price").html('할인가격');
            $("#contents_sell_price").attr('placeholder', '해당상품의 할인가를 입력해주세요.');
        }

        function automake_prod_code() {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/product_mng.php",
                dataType: "json",
                data: {
                    mode: "make_prod_code"
                },
                success: function(data) {
                    if (data.result == '1')
                        $("#product_code").val(data.code);
                }
            });
        }

        function automake_prod_model() {
            var prod_name = $("#contents_title").val();
            if (prod_name == '') {
                alert('상품제목을 입력해주세요.');
                return;
            }
            $.ajax({
                type: "POST",
                url: "/iam/ajax/product_mng.php",
                dataType: "json",
                data: {
                    prod_name: prod_name,
                    mode: "make_prod_model"
                },
                success: function(data) {
                    if (data.result == '1')
                        $("#product_model_name").val(data.model);
                }
            });
        }

        function show_gwc_seperate_info() {
            alert("상품명을 쿠팡에서 검색하고 유사상품을 클릭하여 상세페이지를 오픈하면 상단왼쪽에 분류표가 보이는데 이를 복사해서 입력한다.\n(쿠팡에서 소금 검색시 EX) 식품 >곤약/방탄커피 외 >조미료/오일/소스 >에리스리톨/조미료 >천일염");
        }

        function show_wecon_guide() {
            $.ajax({
                type: "POST",
                url: "/ajax/ajax_session.php",
                data: {
                    stop_westory_guide: "get"
                },
                success: function(data) {
                    if (data == 'Y')
                        $("#we_con_guide").modal("show");
                }
            });
        }

        function add_share_id(ids, names) {
            $("#contents_share_id").empty();
            var id_array = [];
            if (ids != '')
                id_array = ids.split(",");

            var name_array = [];
            if (names != '')
                name_array = names.split(",");
            for (var i = 0; i < id_array.length; i++) {
                var html = "<a onclick=\"delete_share_id(\'" + id_array[i] + "\');\" style=\"border:1px solid #b5b5b5;background:white;color:black;display: inline-block;font-size: 15px;\"" +
                    " data-id=\"" + id_array[i] + "\" id = \"shareTab" + id_array[i] + "\">" + id_array[i] + "(" + name_array[i] + ")&#x2716  " + "</a>";
                $("#contents_share_id").append(html);
            }
        }

        function delete_share_id(id) {
            $("#shareTab" + id).remove();
            var shareId_count = $('#contents_share_id_count').data('num');
            $('#contents_share_id_count').data('num', shareId_count - 1);
            $('#contents_share_id_count').val((shareId_count - 1) + "건")
        }

        function pickup(event, card_idx) {
            if ($(".J_card_num").attr("draggable") == 'true') {
                card = event.target;
                src_idx = card_idx;
                if (event.clientX) {
                    // mousemove
                    card.style.left = event.clientX - card.clientWidth / 2;
                    card.style.top = event.clientY - card.clientHeight / 2;
                } else {
                    // touchmove - assuming a single touchpoint
                    card.style.left = (event.changedTouches[0].clientX - card.clientWidth / 2) + "px";
                    card.style.top = (event.changedTouches[0].clientY - card.clientHeight / 2) + "px";
                    event.preventDefault();
                }
                card.style.position = 'fixed';
            }
        }

        function pickdown(event) {
            if (card) {
                var movingX = event.changedTouches[0].clientX - ($(window).width() - $('main').width()) / 2;
                var movingY = event.changedTouches[0].clientY - $('#header').height() + $(window).scrollTop();
                if (moving) {
                    $('[id="card_title"]').each(function() {
                        var idx = $(this).attr("href").substring(1);
                        if (idx.search("script") == -1) {
                            var top = $(this).position().top;
                            var left = $(this).position().left;
                            var width = $(this).width();
                            var height = $(this).height();
                            console.log(src_idx + "/" + idx + "|" + left + "/" + movingX + "/" + width + "|" + top + "/" + movingY + "/" + height);
                            if (src_idx != idx && movingX >= left && movingX <= left + width && movingY >= top && movingY <= top + height) {
                                $.ajax({
                                    type: "POST",
                                    url: "/iam/ajax/change_card_order.php",
                                    data: {
                                        src: src_idx,
                                        dst: idx
                                    },
                                    success: function(data) {
                                        location.reload();
                                    },
                                    error: function() {
                                        alert('변경 실패');
                                    }
                                });
                                return;
                            }
                        }
                    });
                }
                card.style.left = '';
                card.style.top = '';
                card.style.height = '';
                card.style.width = '';
                card.style.position = '';
                card = null;
                moving = false;
            }
        }

        function move(event) {
            if (card) {
                if (event.clientX) {
                    card.style.left = event.clientX - card.clientWidth / 2;
                    card.style.top = event.clientY - card.clientHeight / 2;
                } else {
                    card.style.left = (event.changedTouches[0].clientX - card.clientWidth / 2) + "px";
                    card.style.top = (event.changedTouches[0].clientY - card.clientHeight / 2) + "px";
                    event.preventDefault();
                }
                moving = true;
            }
        }

        function allowDrop(ev) {
            ev.preventDefault();
        }

        function drag(ev, card_idx) {
            ev.dataTransfer.setData("card_idx", card_idx);
        }

        function drop(ev, card_idx) {
            ev.preventDefault();
            var data = ev.dataTransfer.getData("card_idx");
            $.ajax({
                type: "POST",
                url: "/iam/ajax/change_card_order.php",
                data: {
                    src: card_idx,
                    dst: data
                },
                success: function(data) {
                    location.reload();
                },
                error: function() {
                    alert('변경 실패');
                }
            });
        }
        var tid;
        //데일리 발송 팝업
        function daily_send_pop() {
            $("#sns-modalwindow").modal("hide");
            iam_count('iam_msms');
            jQuery.fn.center = function() {
                this.css('position', 'absolute');
                this.css('width', '100%');
                this.css('top', Math.min(400, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + 'px');
                this.css('left', Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window)
                    .scrollLeft()) + 'px');
                return this;
            }
            $(".daily_popup").center();
            $('.daily_popup').css('display', 'block');
            var iam_link = "";
            if ($("#card_share_preview").val() == "N") {
                iam_link = "iam/daily_write_iam.php?msg_title=iam_daily";
            } else {
                iam_link = "iam/daily_write_iam.php?prelink=" + encodeURIComponent("<?= $domainData['sub_domain'] . '/?' . $cur_card['card_short_url'] . $card_owner_code ?>" + "&preview=" + $("#card_share_preview").val());
            }
            $("#daily_popup_content").prop("href", iam_link);
        }
        //콘텐츠 데일리 발송 팝업
        function contents_send_pop() {
            $("#sns-modalwindow_contents").modal("hide");
            iam_count('iam_msms');
            jQuery.fn.center = function() {
                this.css('position', 'absolute');
                this.css('width', '100%');
                this.css('top', ($(this).outerHeight() * .5 + $(window).scrollTop() + 400) + 'px');
                this.css('left', Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window)
                    .scrollLeft()) + 'px');
                return this;
            }
            $(".daily_popup").center();
            $('.daily_popup').css('display', 'block');
            var iam_link = "/iam/daily_write_iam.php?msg=" + "<?= $domainData['sub_domain']; ?>/iam/contents.php?contents_idx=" + j_idx;
            $("#daily_popup_content").prop("href", iam_link);
        }
        //콘텐츠 몰 데일리 발송 팝업
        function mall_send_pop() {
            $("#sns-modalwindow_mall").modal("hide");
            iam_count('iam_msms');
            jQuery.fn.center = function() {
                this.css('position', 'absolute');
                this.css('width', '100%');
                this.css('top', ($(this).outerHeight() * .5 + $(window).scrollTop() + 400) + 'px');
                this.css('left', Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window)
                    .scrollLeft()) + 'px');
                return this;
            }
            $(".daily_popup").center();
            $('.daily_popup').css('display', 'block');
            var iam_link = $("#mall_share_link").val();
            $("#daily_popup_content").prop("href", iam_link);
        }
        //그룹데일리 발송 팝업
        function group_daily_send_pop() {
            $("#group_share_modalwindow").modal("hide");
            iam_count('iam_msms');
            jQuery.fn.center = function() {
                this.css('position', 'absolute');
                this.css('width', '100%');
                this.css('top', Math.min(400, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + 'px');
                this.css('left', Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()) + 'px');
                return this;
            }
            $(".daily_popup").center();
            $('.daily_popup').css('display', 'block');
            var iam_link = "/iam/daily_write_iam.php?msg=" + $(".J_card_name").text().trim() + "님의 그룹입니다.  " + "<?= strtr($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], '&', '*') ?>" +
                "  아이엠 그룹을 새로 만들었습니다. 휴대폰에 저장부탁해요. 혹시 그룹 만들면 저에게도 보내주시구요. 감사합니다.";
            $("#daily_popup_content").prop("href", iam_link);
        }

        function daily_send_pop_close() {
            $('.daily_popup').css('display', 'none');
        }

        function sns_sendSMS(val) {
            if (val == "N") {
                $("#card_share_preview").val('N');
            }
            $("#sns-modalwindow").modal("hide");
            var iam_link = $(".J_card_name").text().trim() + "님의 명함 <?= htmlspecialchars($cur_card['card_company']) ?> <?= htmlspecialchars($cur_card['card_position']) ?>";
            var link = " <?= str_replace('http:', 'https:', $domainData['sub_domain']); ?>/?<?= $cur_card['card_short_url'] . $card_owner_code ?>" + "&preview=" + $("#card_share_preview").val();
            iam_link += encodeURIComponent(link);
            // alert(iam_link); return;
            iam_count('iam_sms');
            var sms = "";
            for (var i = 0; i < $("input[name=friends_chk]:checked").length; i++) {
                sms = sms + $("#friends_idx" + $("input[name=friends_chk]:checked").eq(i).val()).val() + ",";
            }
            sms = sms.substring(0, sms.length - 1);
            var navCase = navigator.userAgent.toLocaleLowerCase();
            if (navCase.search("android") > -1) {
                location.href = "sms:" + sms + "<? echo (preg_match('/iPhone/', $_SERVER['HTTP_USER_AGENT'])) ? '&' : '?'; ?>body=" + iam_link;
            } else {
                alert("휴대폰에서 이용해주세요.");
            }
        }

        function contents_sendSMS() {
            alert('<?= urlencode("contents.php?contentsidx=12&3jfjo") ?>');
            $("#sns-modalwindow").modal("hide");
            var navCase = navigator.userAgent.toLocaleLowerCase();
            if (navCase.search("android") > -1) {
                if (gwc_state_share == 1) {
                    location.href = "sms:?body=<?= $domainData['sub_domain']; ?>/iam/contents_gwc.php%3Fcontents_idx%3D" + j_idx + "%26gwc%3DY";
                } else {
                    location.href = "sms:?body=<?= $domainData['sub_domain']; ?>/iam/contents.php?contents_idx%3D" + j_idx;
                }
            } else {
                alert("휴대폰에서 이용해주세요.");
            }
        }

        function mall_sendSMS() {
            $("#sns-modalwindow_mall").modal("hide");
            var navCase = navigator.userAgent.toLocaleLowerCase();
            if (navCase.search("android") > -1) {
                location.href = "sms:?body=" + $("#mall_share_link").val();
            } else {
                alert("휴대폰에서 이용해주세요.");
            }
        }

        function group_sendSMS() {
            $("#group_share_modalwindow").modal("hide");
            var iam_link = $(".J_card_name").text().trim() + "님의 그룹" + window.location.href;
            iam_count('iam_sms');
            var sms = "";
            for (var i = 0; i < $("input[name=friends_chk]:checked").length; i++) {
                sms = sms + $("#friends_idx" + $("input[name=friends_chk]:checked").eq(i).val()).val() + ",";
            }
            sms = sms.substring(0, sms.length - 1);
            var navCase = navigator.userAgent.toLocaleLowerCase();
            if (navCase.search("android") > -1) {
                location.href = "sms:" + sms + "<? echo (preg_match('/iPhone/', $_SERVER['HTTP_USER_AGENT'])) ? '&' : '?'; ?>body=" + iam_link;
            } else {
                alert("휴대폰에서 이용해주세요.");
            }
        }

        function share_callback() {
            $("#sns-modalwindow").modal("hide");
            $("#sns-modalwindow_contents").modal("hide");
            $("#group_share_modalwindow").modal("hide");
            var navCase = navigator.userAgent.toLocaleLowerCase();
            if (navCase.search("android") > -1) {
                $("#app").attr("src", "onlyone://action");
            } else {
                alert("휴대폰에서 이용해주세요.");
            }
        }

        function sns_shareKakaoTalk() {
            $("#sns-modalwindow").modal("hide");
            var iam_link = '<?= str_replace("http:", "https:", $domainData['sub_domain']); ?>/?<?= $cur_card['card_short_url'] . $card_owner_code ?>' + "&preview=" + $("#card_share_preview").val();
            iam_count('iam_kakao');
            try {
                Kakao.Link.sendDefault({
                    objectType: "feed",
                    content: {
                        title: "한국형 SNS 솔루션의 탄생!", // 콘텐츠의 타이틀
                        description: $(".J_card_name").text().trim() + "/<?= htmlspecialchars($cur_card['card_company']) ?>/<?= htmlspecialchars($cur_card['card_position']) ?>/<?= $cur_card['card_phone'] ?>", // 콘텐츠 상세설명
                        imageUrl: "<?= cross_image($main_img1) ?>", // 썸네일 이미지
                        link: {
                            mobileWebUrl: iam_link, // 모바일 카카오톡에서 사용하는 웹 링크 URL
                            webUrl: iam_link // PC버전 카카오톡에서 사용하는 웹 링크 URL
                        }
                    },
                    buttons: [{
                        title: "K-플랫폼으로 소통해요!", // 버튼 제목
                        link: {
                            mobileWebUrl: iam_link, // 모바일 카카오톡에서 사용하는 웹 링크 URL
                            webUrl: iam_link // PC버전 카카오톡에서 사용하는 웹 링크 URL
                        }
                    }]
                });
            } catch (e) {
                alert("Kakao talk 과 연동할수 없습니다.");
            }
        }

        function contents_shareKakaoTalk() {
            $("#sns-modalwindow_contents").modal("hide");
            if (j_img.indexOf("http") < 0)
                j_img = "https://www.kiam.kr" + j_img;
            if (gwc_state_share == 1) {
                var php_name = "contents_gwc.php";
                var param = "&gwc=Y";
            } else {
                var php_name = "contents.php";
                var param = "";
            }
            try {
                var iam_link = '<?= str_replace("http:", "https:", $domainData['sub_domain']); ?>/iam/' + php_name + '?contents_idx=' + j_idx + param;
                Kakao.Link.sendDefault({
                    objectType: "feed",
                    content: {
                        title: j_title, // 콘텐츠의 타이틀
                        description: j_desc, // 콘텐츠 상세설명
                        imageUrl: j_img, // 썸네일 이미지
                        link: {
                            mobileWebUrl: iam_link, // 모바일 카카오톡에서 사용하는 웹 링크 URL
                            webUrl: iam_link // PC버전 카카오톡에서 사용하는 웹 링크 URL
                        }
                    },
                    buttons: [{
                        title: "K-플랫폼으로 소통해요!", // 버튼 제목
                        link: {
                            mobileWebUrl: iam_link, // 모바일 카카오톡에서 사용하는 웹 링크 URL
                            webUrl: iam_link // PC버전 카카오톡에서 사용하는 웹 링크 URL
                        }
                    }]
                });
            } catch (e) {
                alert("KaKao Talk과 연동할수 없습니다.");
            }
        }

        function mall_shareKakaoTalk() {
            $("#sns-modalwindow_mall").modal("hide");
            try {
                Kakao.Link.sendDefault({
                    objectType: "feed",
                    content: {
                        title: $("#mall_share_title").val(), // 콘텐츠의 타이틀
                        description: $("#mall_share_desc").val(), // 콘텐츠 상세설명
                        imageUrl: $("#mall_share_img").val(), // 썸네일 이미지
                        link: {
                            mobileWebUrl: $("#mall_share_link").val(), // 모바일 카카오톡에서 사용하는 웹 링크 URL
                            webUrl: $("#mall_share_link").val() // PC버전 카카오톡에서 사용하는 웹 링크 URL
                        }
                    },
                    buttons: [{
                        title: "K-플랫폼으로 소통해요!", // 버튼 제목
                        link: {
                            mobileWebUrl: $("#mall_share_link").val(), // 모바일 카카오톡에서 사용하는 웹 링크 URL
                            webUrl: $("#mall_share_link").val() // PC버전 카카오톡에서 사용하는 웹 링크 URL
                        }
                    }]
                });
            } catch (e) {
                alert("KaKao Talk과 연동할수 없습니다.");
            }
        }

        function group_shareKakaoTalk() {
            $("#group_share_modalwindow").modal("hide");
            var iam_link = window.location.href;
            iam_count('iam_kakao');
            try {
                Kakao.Link.sendDefault({
                    objectType: "feed",
                    content: {
                        title: "한국형 SNS 솔루션의 탄생!", // 콘텐츠의 타이틀
                        description: $(".J_card_name").text().trim() + "그룹페이지", // 콘텐츠 상세설명
                        imageUrl: "<?= cross_image($main_img1) ?>", // 썸네일 이미지
                        link: {
                            mobileWebUrl: iam_link, // 모바일 카카오톡에서 사용하는 웹 링크 URL
                            webUrl: iam_link // PC버전 카카오톡에서 사용하는 웹 링크 URL
                        }
                    },
                    buttons: [{
                        title: "K-플랫폼으로 소통해요!", // 버튼 제목
                        link: {
                            mobileWebUrl: iam_link, // 모바일 카카오톡에서 사용하는 웹 링크 URL
                            webUrl: iam_link // PC버전 카카오톡에서 사용하는 웹 링크 URL
                        }
                    }]
                });
            } catch (e) {
                alert("Kakao talk 과 연동할수 없습니다.");
            }
        }

        function sns_shareFaceBook() {
            $("#sns-modalwindow").modal("hide");
            var iam_link = '<?= $domainData['sub_domain']; ?>/?<?= $cur_card['card_short_url'] . $card_owner_code ?>' + "&preview=" + $("#card_share_preview").val();
            var desc = $(".J_card_name").text().trim() + "님의 명함 || <?= htmlspecialchars($cur_card['card_company']) ?> ||<?= htmlspecialchars($cur_card['card_position']) ?>";
            var text = encodeURIComponent(desc);
            var linkUrl = encodeURIComponent(iam_link);
            var title = '아이엠멀티명함IAM multicard';
            var description = desc;
            var imgUrl = '<?= cross_image($main_img1) ?>';
            if (!$('meta[property="og:title"').attr('content')) {
                $('head').append(format('<meta property="og:title" content="{0}" />', title));
            } else {
                $('meta[property="og:title"').attr('content', title);
            }
            if (!$('meta[property="og:description"').attr('content')) {
                $('head').append(format('<meta property="og:description" content="{0}" />', description));
            } else {
                $('meta[property="og:description"').attr('content', description);
            }
            if (!$('meta[property="og:image"').attr('content')) {
                $('head').append(format('<meta property="og:image" content="{0}" />', imgUrl));
            } else {
                $('meta[property="og:image"').attr('content', imgUrl);
            }
            iam_count('iam_facebook');
            window.open('http://www.facebook.com/sharer.php?u=' + linkUrl + '&t=' + text);
        }

        function contents_shareFaceBook() {
            $("#sns-modalwindow_contents").modal("hide");
            //shareFaceBook_contents(j_idx, j_name);
            var text = encodeURIComponent('아이엠의' + j_name + '님');
            if (gwc_state_share == 1) {
                var php_name = "contents_gwc.php";
                var param = "&gwc=Y";
            } else {
                var php_name = "contents.php";
                var param = "";
            }
            var linkUrl = encodeURIComponent('<?= $domainData['sub_domain']; ?>/iam/' + php_name + '?contents_idx=' + j_idx + param);
            window.open('http://www.facebook.com/sharer.php?u=' + linkUrl + '&t=' + text);
        }

        function mall_shareFaceBook() {
            $("#sns-modalwindow_mall").modal("hide");
            var iam_link = $("#mall_share_link").val();
            var desc = $("#mall_share_desc").val();
            var text = encodeURIComponent(desc);
            var linkUrl = encodeURIComponent(iam_link);
            var title = $("#mall_share_title").val();
            var imgUrl = $("#mall_share_img").val();
            if (!$('meta[property="og:title"').attr('content')) {
                $('head').append(format('<meta property="og:title" content="{0}" />', title));
            } else {
                $('meta[property="og:title"').attr('content', title);
            }
            if (!$('meta[property="og:description"').attr('content')) {
                $('head').append(format('<meta property="og:description" content="{0}" />', desc));
            } else {
                $('meta[property="og:description"').attr('content', desc);
            }
            if (!$('meta[property="og:image"').attr('content')) {
                $('head').append(format('<meta property="og:image" content="{0}" />', imgUrl));
            } else {
                $('meta[property="og:image"').attr('content', imgUrl);
            }
            iam_count('iam_facebook');
            window.open('http://www.facebook.com/sharer.php?u=' + linkUrl + '&t=' + text);
        }

        function group_shareFaceBook() {
            $("#group_share_modalwindow").modal("hide");
            var iam_link = window.location.href;
            var desc = $(".J_card_name").text().trim() + "님의 그룹페이지";
            var text = encodeURIComponent(desc);
            var linkUrl = encodeURIComponent(iam_link);
            var title = '한국형 SNS솔루션의 탄생!!!';
            var description = desc;
            var imgUrl = '<?= cross_image($main_img1) ?>';
            if (!$('meta[property="og:title"').attr('content')) {
                $('head').append(format('<meta property="og:title" content="{0}" />', title));
            } else {
                $('meta[property="og:title"').attr('content', title);
            }
            if (!$('meta[property="og:description"').attr('content')) {
                $('head').append(format('<meta property="og:description" content="{0}" />', description));
            } else {
                $('meta[property="og:description"').attr('content', description);
            }
            if (!$('meta[property="og:image"').attr('content')) {
                $('head').append(format('<meta property="og:image" content="{0}" />', imgUrl));
            } else {
                $('meta[property="og:image"').attr('content', imgUrl);
            }
            iam_count('iam_facebook');
            window.open('http://www.facebook.com/sharer.php?u=' + linkUrl + '&t=' + text);
        }

        function sns_copyContacts() {
            $("#sns-modalwindow").modal("hide");
            copy();
        }

        function copy_card_link() {
            $("#card_send_modal_link").modal("hide");
            var send_mode = $("input[name=card_send_mode]:checked").val() == 0 ? "clone" : "share";
            copy("smode=" + send_mode + "&slink=" + <?= $cur_card['idx'] ?> + "&smsg=" + btoa(unescape(encodeURIComponent($("#alarm_msg").val()))));
        }
        //텍스트 복사
        function copy(link = "") {
            iam_count('iam_share');
            var iam_link = "";
            if (link == "")
                iam_link = '<?= str_replace("http:", "https:", $domainData['sub_domain']); ?>/?<?= $cur_card['card_short_url'] . $card_owner_code ?>' + "&preview=" + $("#card_share_preview").val();
            else
                iam_link = '<?= str_replace("http:", "https:", $domainData['sub_domain']); ?>/?<?= $cur_card['card_short_url'] . $card_owner_code ?>&' + link;
            iam_link = iam_link.replace('/&/g', "%26");
            iam_link += '%26cache=' + Math.floor(Math.random() * 10);
            //iam_link = encodeURI(iam_link);
            // 글을 쓸 수 있는 란을 만든다.
            var aux = document.createElement("input");
            var msg = "";
            if (link == "") {
                //msg = $(".J_card_name").text().trim() + "님의 명함 <?= htmlspecialchars($cur_card['card_company']) ?> <?= htmlspecialchars($cur_card['card_position']) ?> <?= $cur_card['card_phone'] ?> "+iam_link
                // 지정된 요소의 값을 할당 한다.
                //aux.setAttribute("value",msg);
                aux.setAttribute("value", iam_link);
                // bdy에 추가한다.
                document.body.appendChild(aux);
                // 지정된 내용을 강조한다.
                aux.select();
                // 텍스트를 카피 하는 변수를 생성
                document.execCommand("copy");
                // body 로 부터 다시 반환 한다.
                document.body.removeChild(aux);
                alert("링크복사에 성공 하였습니다.전송하고 싶은 대상에게 카톡, 문자 등으로 보내세요.");
            } else {
                msg = iam_link;
                $.get("http://tinyurl.com/api-create.php?url=" + msg, function(shorturl) {
                    // 지정된 요소의 값을 할당 한다.
                    aux.setAttribute("value", shorturl);
                    // bdy에 추가한다.
                    document.body.appendChild(aux);
                    // 지정된 내용을 강조한다.
                    aux.select();
                    // 텍스트를 카피 하는 변수를 생성
                    document.execCommand("copy");
                    // body 로 부터 다시 반환 한다.
                    document.body.removeChild(aux);
                    alert("링크복사에 성공 하였습니다.전송하고 싶은 대상에게 카톡, 문자 등으로 보내세요.");
                });
            }
        }

        function contents_copyContacts() {
            $("#sns-modalwindow_contents").modal("hide");
            contents_copy(j_idx);
        }
        //콘텐츠 복사
        function contents_copy(idx) {
            var aux = document.createElement("input");
            if (gwc_state_share == 1) {
                var php_name = "contents_gwc.php";
                var param = "&gwc=Y";
            } else {
                var php_name = "contents.php";
                var param = "";
            }
            // 지정된 요소의 값을 할당 한다.
            aux.setAttribute("value", "<?= str_replace("http:", "https:", $domainData['sub_domain']); ?>/iam/" + php_name + "?contents_idx=" + idx + param);
            // bdy에 추가한다.
            document.body.appendChild(aux);
            $("#sns-modalwindow_contents").modal("hide");
            // 지정된 내용을 강조한다.
            aux.select();
            // 텍스트를 카피 하는 변수를 생성
            document.execCommand("copy");
            // body 로 부터 다시 반환 한다.
            document.body.removeChild(aux);
            alert("복사에 성공 하였습니다.");
        }
        //몰 복사
        function mall_copyContacts() {
            var aux = document.createElement("input");
            // 지정된 요소의 값을 할당 한다.
            aux.setAttribute("value", $("#mall_share_link").val());
            // bdy에 추가한다.
            document.body.appendChild(aux);
            $("#sns-modalwindow_mall").modal("hide");
            // 지정된 내용을 강조한다.
            aux.select();
            // 텍스트를 카피 하는 변수를 생성
            document.execCommand("copy");
            // body 로 부터 다시 반환 한다.
            document.body.removeChild(aux);
            alert("복사에 성공 하였습니다.");
        }

        function group_copyContacts() {
            var aux = document.createElement("input");
            // 지정된 요소의 값을 할당 한다.
            aux.setAttribute("value", window.location.href);
            // bdy에 추가한다.
            document.body.appendChild(aux);
            $("#group_share_modalwindow").modal("hide");
            // 지정된 내용을 강조한다.
            aux.select();
            // 텍스트를 카피 하는 변수를 생성
            document.execCommand("copy");
            // body 로 부터 다시 반환 한다.
            document.body.removeChild(aux);
            alert("복사에 성공 하였습니다.");
        }

        function loginCheckShowModal() {
            <? if (!$_SESSION['iam_member_id']) { ?>
                $("#pleaselogin").modal("show");
            <? } else if ($phone_count == 0) { ?>
                $("#pleaseinstall").modal("show");
            <? } ?>
        }

        function loginCheckShowModalFriends() {
            <? if (!$_SESSION['iam_member_id']) { ?>
                $("#pleaseloginfriends").modal("show");
            <? } else if ($phone_count == 0) { ?>
                $("#pleaseinstall").modal("show");
            <? } ?>
            show_wecon_guide();
        }

        toastr.options = {
            "timeOut": 3000
        }

        function add_iframe() {
            var str = $('#contents_url').val();
            if (str.indexOf("<iframe") == -1) {
                str = "<iframe width='400' height='400' src='" + str + "' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>";
                $('#contents_url').val(str);
            }
        }

        function show_share_user_list(val) {
            var win = window.open("/iam/_pop_public_profile_info.php?sendtype=" + val, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
        }

        function reset_westory_tr() {
            if ($("#edit_content_radio_spec").prop("checked")) {
                if ($("#contents_westory_display").prop("checked")) {
                    $("#westory_tr").show();
                } else {
                    $("#westory_tr").hide();
                }
            }
            $(".we_story_radio").prop("checked", false);
            $(".my_info_check").prop("checked", false);
            var card_short_url = '<?= $cur_win ?>' != "group-con" ? "<?= $cur_card['card_short_url'] ?>" : "<?= $group_card_url ?>";
            var card_url_arr = card_short_url.split(',');
            $card_selected = card_url_arr;
            for (var i = 0; i < card_url_arr.length; i++) {
                $(".my_info_" + card_url_arr[i]).prop("checked", true);
            }
            arrayToInput();
            /*if('<?= $cur_win ?>' != "group-con"){
                $(".my_info_<?= $cur_card['card_short_url'] ?>").attr("checked", true);
                $(".we_story_<?= $cur_card['card_short_url'] ?>").attr("checked", true);
            }else{
                $(".my_info_<?= $group_card_url ?>").attr("checked", true);
                $(".we_story_<?= $group_card_url ?>").attr("checked", true);
            }*/
        }

        function format_add_modal() {
            $("#contents_add_modal .web_cont").addClass("develop");
            $("#contents_add_modal .multi_cont").addClass("develop");
            $("#contents_add_modal .image").addClass("develop");
            $("#contents_add_modal .youtube").addClass("develop");
            $("#contents_add_modal .service").addClass("develop");
            $("#post_type").val("creat");
            $("#contents_title").val("");
            $("#contents_url").val("");
            $("#contents_price").val("");
            $("#contents_sell_price").val("");
            $("#contents_desc").val("");
            $("#except_keyword").val("");
            $("#fb_upload_check").prop("checked", false);
            $("#landing_mode").prop("checked", false);
            $("#contents_share_id").val("");
            $("#contents_share_id_count").val("");
            $("#contents_share_id_count").data("num", 0);
            $("#contents_type").val(-1);
            $("#contents_add_multi").val("");
            $("#btn_cont_image").removeClass("btn-default").addClass("btn-link");
            $("#btn_cont_youtube").removeClass("btn-default").addClass("btn-link");
            $("#btn_cont_page").removeClass("btn-default").addClass("btn-link");
            $("#btn_add_one_cont").removeClass("btn-default").addClass("btn-active");
            $("#btn_add_multi_cont").removeClass("btn-active").addClass("btn-default");
            $("#btn_add_web_cont").removeClass("btn-active").addClass("btn-default");
            $("#btn_add_shop_cont").removeClass("btn-active").addClass("btn-default");
            $("#edit_content_radio_spec").prop("checked", false);
            $("#contents_add_modal .hide_spec").addClass("develop");
            //체크박스 초기화
            //$(".we_story_radio").attr("checked", false);
            //$(".my_info_check").attr("checked", false);
            //이미지 초기화
            $("#cont_modal_image_type_file").addClass("develop");
            $("#cont_modal_image_type_link").addClass("develop");
            $("#cont_modal_video_type_file").addClass("develop");
            $("#cont_modal_video_type_link").addClass("develop");
            $("#cont_modal_image").val("");
            $("#cont_modal_image_url").val("");
            $("#cont_modal_video").val("");
            $("#cont_modal_video_url").val("");
            $("#preview_img").empty();
            //웹링크 초기화
            $('div').remove(".cont_modal_web_link");
        }

        function set_add_modal(mode) {
            format_add_modal();
            $("#post_type").val(mode);
            if (mode == "creat") {
                $("#contents_westory_display").prop("checked", true);
                $("#contents_add_modal .modal-header").show();
            } else {
                $("#contents_add_modal .modal-header").hide();
            }
            if ($("#btn_add_one_cont").hasClass("btn-active")) {
                $(".one_cont").removeClass("develop");
            } else if ($("#btn_add_multi_cont").hasClass("btn-active")) {

            } else if ($("#btn_add_web_cont").hasClass("btn-active")) {

            }
        }

        function add_web_link() {
            var index = $('#web_link_list').find('input').length * 1;
            var html = "<div class = 'cont_modal_web_link' style='display:flex;margin-top:5px' data-num=" + index + "><input type='text' name='web_url' placeholder='<?= $MENU['IAM_CONTENTS']['TITLE']; ?>'/>";
            html += "<a href = 'javascript:del_cont_modal_web_link(" + index + ")'><img src='/iam/img/main/close.png' class = 'cont_modal_web_link' style='margin-left: 10px;margin-top:5px' width='20' height='20'></a></div>";
            $("#web_link_list").append(html);
        }

        function del_cont_modal_web_link(dIndex) {
            var tmpIndex = 0;
            $('#web_link_list').find('.cont_modal_web_link').each(function() {
                var index = $(this).data('num');
                if (index != undefined) {
                    if (index == dIndex) {
                        $(this).remove();
                    } else {
                        tmpIndex++;
                        if (index != tmpIndex) {
                            var funcName = "javascript:del_cont_modal_web_link(" + tmpIndex + ");";
                            $(this).data("num", tmpIndex);
                            $(this).find('a').prop('href', funcName);
                        }
                    }
                }
            });
        }

        function get_contents() {
            var web_url = $('#web_url').val();
            if (web_url.search("youtube.com") != -1 && web_url.search("http") == -1) {
                web_url = "https://" + web_url;
            }
            $("#get_contents").attr("disabled", true);
            $('#cont_modal_btn_cancel').attr("disabled", true);
            $('#cont_modal_btn_ok').attr("disabled", true);
            $.ajax({
                type: "POST",
                url: "https://www.goodhow.com/crawler/crawler/scrape/getonecontent.php",
                dataType: "json",
                data: {
                    web_address: web_url
                },
                success: function(result) {
                    var pos = web_url.search("youtu.be");
                    var pos1 = web_url.search("youtube.com");
                    var spos = web_url.search("smartstore.naver.com");
                    var dpos = web_url.search("daangn.com");
                    var gpos = web_url.search("gmarket.co.kr");
                    if (pos != -1) {
                        $('#contents_type').val(2);
                        var vid_src = web_url.replace("youtu.be/", "www.youtube.com/embed/");
                        $('#contents_iframe').val('<iframe width="400" height="400" src="' + vid_src + '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
                    } else if (pos1 != -1) {
                        $('#contents_type').val(2);
                        var vid_src = web_url.replace("watch?v=", "embed/");
                        $('#contents_iframe').val('<iframe width="400" height="400" src="' + vid_src + '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
                    } else if (spos != -1 || dpos != -1 || gpos != -1) {
                        $('#contents_type').val(3);
                    }
                    $('#cont_modal_image_url').val(result.image);
                    $('#contents_title').val(result.title);
                    $('#contents_url').val(web_url);
                    var new_open_url = $("#new_open_url").val();
                    var url_array = new_open_url.split(",");
                    for (var i = 0; i < url_array.length; i++) {
                        if (url.toLowerCase().search(url_array[i].toLowerCase()) != -1) {
                            $("#new_open").prop("checked", true);
                            break;
                        }
                    }
                    var desc = result.description;
                    while (desc.search("&lt;") > 0)
                        desc = desc.replace("&lt;", "<");
                    while (desc.search("&gt;") > 0)
                        desc = desc.replace("&gt;", ">");

                    $('#contents_desc').val(desc);
                    $('#get_contents').attr("disabled", false);
                    $('#cont_modal_btn_cancel').attr("disabled", false);
                    $('#cont_modal_btn_ok').attr("disabled", false);
                },
                error: function() {
                    alert('해당 페이지는 크롤링이 되지 않습니다. 다른 페이지 주소를 입력해주세요.');
                    $("#get_contents").attr("disabled", false);
                    $("#cont_modal_btn_cancel").attr("disabled", false);
                    $("#cont_modal_btn_ok").attr("disabled", false);
                }
            });
        }

        function click_one_content_add() {
            format_add_modal();
            cont_modal_fileBuffer = [];
            cont_modal_linkBuffer = [];
            $("#btn_add_one_cont").removeClass("btn-default").addClass("btn-active");
            $("#btn_add_multi_cont").removeClass("btn-active").addClass("btn-default");
            $("#btn_add_web_cont").removeClass("btn-active").addClass("btn-default");
            $("#btn_add_shop_cont").removeClass("btn-active").addClass("btn-default");
            $("#contents_type").val(1);
            $("#contents_add_modal .multi_cont").addClass("develop");
            $("#contents_add_modal .web_cont").addClass("develop");
            $("#contents_add_modal .one_cont").removeClass("develop");
            reset_westory_tr();
            $("#multi-cont-image-desc").addClass("develop");
            $("#one-cont-image-desc").removeClass("develop");
            $("#gwc_con_state").hide();
            $("#gwc_con_label").hide();
            $("#gwc_type_menu").hide();
            $(".chat_btn").show();
            $("#cont_media_type_box").show();
            $("#market_radio").hide();
            $("#edit_content_radio_market").prop("checked", false);
            click_cont_image();
        }

        function click_multi_contents_add() {
            format_add_modal();
            cont_modal_fileBuffer = [];
            cont_modal_linkBuffer = [];
            $("#btn_add_multi_cont").removeClass("btn-default").addClass("btn-active");
            $("#btn_add_one_cont").removeClass("btn-active").addClass("btn-default");
            $("#btn_add_web_cont").removeClass("btn-active").addClass("btn-default");
            $("#btn_add_shop_cont").removeClass("btn-active").addClass("btn-default");
            $("#contents_type").val(1);

            $("#contents_add_modal .one_cont").addClass("develop");
            $("#contents_add_modal .web_cont").addClass("develop");
            $("#contents_add_modal .multi_cont").removeClass("develop");
            $("#contents_add_multi").val(1);
            reset_westory_tr();
            $("#multi-cont-image-desc").removeClass("develop");
            $("#one-cont-image-desc").addClass("develop");
            $("#gwc_con_state").hide();
            $("#gwc_con_label").hide();
            $(".chat_btn").hide();
            $("#cont_media_type_box").hide();
            $("#market_radio").hide();
            $("#edit_content_radio_market").prop("checked", false);
            $("#cont_modal_image_type_file").removeClass("develop");
            $("#new_open").prop("disabled", false);
        }

        function click_web_content_add() {
            format_add_modal();
            cont_modal_fileBuffer = [];
            cont_modal_linkBuffer = [];
            $("#btn_add_web_cont").removeClass("btn-default").addClass("btn-active");
            $("#btn_add_multi_cont").removeClass("btn-active").addClass("btn-default");
            $("#btn_add_one_cont").removeClass("btn-active").addClass("btn-default");
            $("#btn_add_shop_cont").removeClass("btn-active").addClass("btn-default");
            $("#contents_type").val(1);
            $("#contents_add_modal .multi_cont").addClass("develop");
            $("#contents_add_modal .one_cont").addClass("develop");
            $("#contents_add_modal .web_cont").removeClass("develop");

            $("#gwc_con_state").hide();
            $("#gwc_con_label").hide();
            reset_westory_tr();
            $(".chat_btn").hide();
            $("#cont_media_type_box").hide();
            $("#market_radio").hide();
            $("#edit_content_radio_market").prop("checked", false);
            $("#new_open").prop("disabled", false);
        }

        function click_shop_content_add() {
            format_add_modal();
            cont_modal_fileBuffer = [];
            cont_modal_linkBuffer = [];
            $("#btn_add_shop_cont").removeClass("btn-default").addClass("btn-active");
            $("#btn_add_web_cont").removeClass("btn-active").addClass("btn-default");
            $("#btn_add_multi_cont").removeClass("btn-active").addClass("btn-default");
            $("#btn_add_one_cont").removeClass("btn-active").addClass("btn-default");
            $("#contents_type").val(3);
            $("#contents_add_modal .multi_cont").addClass("develop");
            $("#contents_add_modal .web_cont").addClass("develop");
            $("#contents_add_modal .one_cont").removeClass("develop");
            reset_westory_tr();
            $("#multi-cont-image-desc").addClass("develop");
            $("#one-cont-image-desc").removeClass("develop");
            $("#contents_add_modal .service").removeClass("develop");
            if ('<?= $Gn_mem_row['special_type'] ?>' == '0') {
                $("#post_content_selling_request").data("status", "N");
                // $("#post_content_selling_request").html("판매자 미신청상태입니다. 게시하기로 신청합니다.");
            } else {
                $("#post_content_selling_request").data("status", "Y");
                // $("#post_content_selling_request").html("판매자로 신청하셨습니다.");
            }
            $("#gwc_con_state").show();
            $("#gwc_con_label").show();
            $("#gwc_type_menu").hide();
            if ('<?= $_SESSION['iam_member_id'] ?>' == 'iamstore') {
                $("#prod_cms").show();
                $("#prod_provide").show();
                $("#prod_manufact").show();
                $("#prod_salary").show();
                $("#gwc_con_state").val('1');
            } else {
                $("#prod_cms").hide();
                $("#prod_provide").hide();
                $("#prod_manufact").hide();
                $("#prod_salary").hide();
            }
            $(".chat_btn").show();
            $("#cont_media_type_box").hide();
            $("#market_radio").show();
            $("#edit_content_radio_market").prop("checked", false);

            click_cont_image();
            $('.art_type').prop("checked", false);
            $('.gallery').hide();
        }

        function click_cont_type(index) {
            $("#contents_type").val(index);
            if (index == 1) {
                $("#btn_cont_image").removeClass("btn-link").addClass("btn-default");
                $("#btn_cont_youtube").removeClass("btn-default").addClass("btn-link");
                $("#btn_cont_page").removeClass("btn-default").addClass("btn-link");
                $("#contents_add_modal .youtube").addClass("develop");
                $("#contents_add_modal .service").addClass("develop");
                $("#contents_add_modal .image").removeClass("develop");

                $("#gwc_con_state").hide();
                $("#gwc_con_label").hide();
            } else if (index == 2) {
                $("#btn_cont_image").removeClass("btn-default").addClass("btn-link");
                $("#btn_cont_youtube").removeClass("btn-link").addClass("btn-default");
                $("#btn_cont_page").removeClass("btn-default").addClass("btn-link");
                $("#contents_add_modal .image").addClass("develop");
                $("#contents_add_modal .service").addClass("develop");
                $("#contents_add_modal .youtube").removeClass("develop");

                $("#gwc_con_state").hide();
                $("#gwc_con_label").hide();
            } else if (index == 3) {
                $("#btn_cont_image").removeClass("btn-default").addClass("btn-link");
                $("#btn_cont_youtube").removeClass("btn-default").addClass("btn-link");
                $("#btn_cont_page").removeClass("btn-link").addClass("btn-default");
                $("#contents_add_modal .image").addClass("develop");
                $("#contents_add_modal .youtube").addClass("develop");
                $("#contents_add_modal .service").removeClass("develop");

                $("#gwc_con_state").show();
                $("#gwc_con_label").show();
            }
        }

        function onChangeContAddImageType(index) {
            refresh_contents_images();
            if (index == 1) {
                $("#cont_media_type").prop("checked", false);
                $("#cont_modal_image_type_file").removeClass("develop");
                $("#cont_modal_image_type_link").addClass("develop");
                $("#cont_modal_video_type_file").addClass("develop");
                $("#cont_modal_video_type_link").addClass("develop");
                $("#one-cont-image-desc").removeClass("develop");
                $("#preview_video").hide();
            } else if (index == 2) {
                $("#cont_media_type").prop("checked", false);
                $("#cont_modal_image_type_file").addClass("develop");
                $("#cont_modal_image_type_link").removeClass("develop");
                $("#cont_modal_video_type_file").addClass("develop");
                $("#cont_modal_video_type_link").addClass("develop");
                $("#one-cont-image-desc").removeClass("develop");
                $("#preview_video").hide();
            } else if (index == 3) {
                $("#cont_media_type").prop("checked", true);
                $("#cont_modal_image_type_file").addClass("develop");
                $("#cont_modal_image_type_link").addClass("develop");
                $("#cont_modal_video_type_file").removeClass("develop");
                $("#cont_modal_video_type_link").addClass("develop");
                $("#one-cont-image-desc").addClass("develop");
                $("#preview_video").hide();
            } else if (index == 4) {
                $("#cont_media_type").prop("checked", true);
                $("#cont_modal_image_type_file").addClass("develop");
                $("#cont_modal_image_type_link").addClass("develop");
                $("#cont_modal_video_type_file").addClass("develop");
                $("#cont_modal_video_type_link").removeClass("develop");
                $("#one-cont-image-desc").addClass("develop");
                $("#preview_video").hide();
            }
        }

        function click_cont_image() {
            $("#cont_modal_btn_image").show();
            $("#cont_modal_btn_video").hide();
            $("#cont_modal_btn_web").show();
            $("#cont_modal_btn_web_video").hide();
            $("#cont_modal_btn_image").removeClass("btn-cont-inactive").addClass("btn-cont-active");
            $("#cont_modal_btn_web").removeClass("btn-cont-active").addClass("btn-cont-inactive");
            $("#cont_modal_btn_video").removeClass("btn-cont-active").addClass("btn-cont-inactive");
            $("#cont_modal_btn_web_video").removeClass("btn-cont-active").addClass("btn-cont-inactive");
            $("#new_open").prop("disabled", false);
            onChangeContAddImageType(1);
        }

        function click_cont_web() {
            $("#cont_modal_btn_image").show();
            $("#cont_modal_btn_video").hide();
            $("#cont_modal_btn_web").show();
            $("#cont_modal_btn_web_video").hide();
            $("#cont_modal_btn_web").removeClass("btn-cont-inactive").addClass("btn-cont-active");
            $("#cont_modal_btn_image").removeClass("btn-cont-active").addClass("btn-cont-inactive");
            $("#cont_modal_btn_video").removeClass("btn-cont-active").addClass("btn-cont-inactive");
            $("#cont_modal_btn_web_video").removeClass("btn-cont-active").addClass("btn-cont-inactive");
            $("#new_open").prop("disabled", false);
            onChangeContAddImageType(2);
        }

        function click_cont_video() {
            $("#cont_modal_btn_image").hide();
            $("#cont_modal_btn_video").show();
            $("#cont_modal_btn_web").hide();
            $("#cont_modal_btn_web_video").show();
            $("#cont_modal_btn_web").removeClass("btn-cont-active").addClass("btn-cont-inactive");
            $("#cont_modal_btn_image").removeClass("btn-cont-active").addClass("btn-cont-inactive");
            $("#cont_modal_btn_video").removeClass("btn-cont-inactive").addClass("btn-cont-active");
            $("#cont_modal_btn_web_video").removeClass("btn-cont-active").addClass("btn-cont-inactive");
            $("#new_open").prop("disabled", true);
            onChangeContAddImageType(3);
        }

        function click_cont_web_video() {
            $("#cont_modal_btn_image").hide();
            $("#cont_modal_btn_video").show();
            $("#cont_modal_btn_web").hide();
            $("#cont_modal_btn_web_video").show();
            $("#cont_modal_btn_web_video").removeClass("btn-cont-inactive").addClass("btn-cont-active");
            $("#cont_modal_btn_web").removeClass("btn-cont-active").addClass("btn-cont-inactive");
            $("#cont_modal_btn_image").removeClass("btn-cont-active").addClass("btn-cont-inactive");
            $("#cont_modal_btn_video").removeClass("btn-cont-active").addClass("btn-cont-inactive");
            $("#new_open").prop("disabled", true);
            onChangeContAddImageType(4);
        }

        function add_contents_row() {
            if ($('#multi_btn_ok').prop("disabled") == false) {
                var index = $('#multi_contents_table').find('tr').length * 1;
                index++;
                var cont = "<tr data-num = '" + index + "'><td class='bold' width = '100px' id = 'no'>콘텐츠 " + index + "번</td><td><input type='text' style = 'width : 100%;' id='multi_contents'></td><td width = '45px'>";
                cont += "<a href='javascript:del_contents_row(" + index + ");'><img src='/iam/img/main/close.png' style = 'margin-right: 10px' width='25' height='25'></a></td></tr>";
                $('#multi_contents_table').append(cont);
            }
        }

        function del_contents_row(dIndex) {
            if ($('#multi_btn_ok').prop("disabled") == false) {
                var tmpIndex = 0;
                $('#multi_contents_table').find('tr').each(function() {
                    var index = $(this).data('num');
                    if (index != undefined) {
                        if (index == dIndex) {
                            $(this).remove();
                        } else {
                            tmpIndex++;
                            if (index != tmpIndex) {
                                var text = "콘텐츠 " + tmpIndex + "번";
                                var fName = "javascript:del_contents_row(" + tmpIndex + ");";
                                $(this).find('#no').text(text);
                                $(this).data("num", tmpIndex);
                                $(this).find('td').find('a').prop('href', fName);
                            }
                        }
                    }
                });
            }
        }
        //판매 신청하기
        function selling_request(spec_type) {
            $("#req_seller_mem_leb").modal('show');
            $("#mem_special_type").val(spec_type);
        }

        function post_one_multi_cont(cnt, landing_mode = "N", open_type = 1) {
            if (cnt > 0) {
                var web_page = $('input[name=web_url]:eq(' + (cnt - 1) + ')').val();
                if (web_page.search("tiktok.com") != -1) {
                    var url = "https://www.tiktok.com/oembed?url=" + web_page;
                    $.ajax({
                        type: "GET",
                        url: url,
                        dataType: "json",
                        success: function(result) {
                            cnt--;
                            type = 2;
                            $('#contents_iframe').val(result.html);
                            multi_post_contents(result.title, result.author_url, web_page, "", cnt, $("#card_short_url").val(), type, landing_mode, open_type);
                            post_one_multi_cont(cnt);
                        },
                        error: function() {
                            cnt--;
                            alert(web_page + ' 페이지는 크롤링이 되지 않습니다. 다른 페이지 주소를 입력해주세요.');
                            post_one_multi_cont(cnt);
                        }
                    });
                } else {
                    if (web_page.search("youtube.com") != -1 && web_page.search("http") == -1) {
                        web_page = "https://" + web_page;
                    }
                    $.ajax({
                        type: "POST",
                        url: "https://www.goodhow.com/crawler/crawler/scrape/getonecontent.php",
                        dataType: "json",
                        data: {
                            web_address: web_page
                        },
                        success: function(result) {
                            cnt--;
                            var pos = web_page.search("youtu.be");
                            var pos1 = web_page.search("youtube.com");
                            var spos = web_page.search("smartstore.naver.com");
                            var dpos = web_page.search("daangn.com");
                            var gpos = web_page.search("gmarket.co.kr");
                            var type = 1;
                            if (pos != -1) {
                                //$('#contents_type').val(2);
                                type = 2;
                                var vid_src = web_page.replace("youtu.be/", "www.youtube.com/embed/");
                                $('#contents_iframe').val("<iframe width='400' height='400' src='" + vid_src + "' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>");
                            } else if (pos1 != -1) {
                                // $('#contents_type').val(2);
                                type = 2;
                                var vid_src = web_page.replace("watch?v=", "embed/");
                                vid_src = vid_src.replace("shorts", "embed");
                                $('#contents_iframe').val("<iframe width='400' height='400' src='" + vid_src + "' frameborder='0' allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>");
                            } else if (spos != -1 || dpos != -1 || gpos != -1) {
                                // $('#contents_type').val(3);
                                type = 3;
                            }
                            multi_post_contents(result.title, result.image, web_page, result.description, cnt, $("#card_short_url").val(), type, landing_mode, open_type);
                            post_one_multi_cont(cnt);
                        },
                        error: function() {
                            cnt--;
                            alert(web_page + ' 페이지는 크롤링이 되지 않습니다. 다른 페이지 주소를 입력해주세요.');
                            post_one_multi_cont(cnt);
                        }
                    });
                }
            }
        }
        //콘텐츠 게시하기
        var req_provide = '<?= $_GET['req_provide'] ?>';
        $(document).ready(function() {
            if (req_provide == "Y") {
                setInterval(
                    function() {
                        if ($("#img_set_intro").attr('style').indexOf('color: red') != -1) {
                            $("#img_set_intro").css('color', '#33cccc');
                        } else {
                            $("#img_set_intro").css('color', 'red');
                        }
                    }, 1000);
            }
        })
        var is_artist = '<?= $is_artist ?>';

        function post_contents(service_type) {
            var mem_type = '<?= $gwc_mem ?>';
            var gwc_state = $("#gwc_con_state").val();
            if (req_provide == "Y") {
                if ($("#provider_name").val() == '') {
                    toastr.error("공급사명을 입력하세요.", "오류");
                    return;
                }
            }
            toastr.options = {
                "timeOut": 3000
            }
            if ($("#btn_add_web_cont").hasClass("btn-active")) {
                var cnt = $('input[name=web_url]').length * 1;
                var landing_mode = $("#landing_mode").prop("checked") == true ? "Y" : "N";
                var open_type = $("input[name='contents_open']:checked").val();
                post_one_multi_cont(cnt, landing_mode, open_type);
            } else {
                var formData = new FormData();
                if ($("#contents_type").val() == -1) {
                    toastr.error("콘텐츠종류를 선택하세요.", "오류");
                    $(".btn-link").fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500);
                    return;
                }
                if ($("#contents_desc").val().trim() == "" && $('#preview_img').find("div").length == 0 && $("#cont_video_preview").prop('src') == "") {
                    toastr.error("내용이나 이미지 또는 동영상이 있어야 저장됩니다.", "입력오류");
                    $("#contents_desc").focus();
                    return;
                }
                if (($("#contents_type").val() == 3 && $('#preview_img').find("div").length == 0)) {
                    toastr.error("이미지를 첨부해 주세요.", "오류");
                    $('#cont_modal_image').focus();
                    return;
                }
                if ($("#contents_type").val() == 2 && $("#contents_iframe").val().trim() == "") {
                    toastr.error("유튜브에서 ‘공유’ 아이콘을 클릭하여 소스를 복사해서 붙이세요.", "동영상소스");
                    $("#contents_iframe").focus();
                    return;
                }
                if ($("#contents_price").is(":visible") && $("#contents_price").val().trim() == "") {
                    toastr.error("정가를 입력하세요.", "오류");
                    $("#contents_price").focus();
                    return;
                }
                if ($("#contents_sell_price").is(":visible") && $("#contents_sell_price").val().trim() == "") {
                    toastr.error("최저가를 입력하세요.", "오류");
                    $("#contents_sell_price").focus();
                    return;
                }
                if ($("#btn_add_shop_cont").hasClass("btn-active") || $("#contents_type").val() == 3) {
                    if (mem_type == '1' && gwc_state != 0 || req_provide == 'Y') {
                        if ($("#confirm_model").attr('style').indexOf('background-color: black;') != -1) {
                            toastr.error("상품모델명 중복확인을 해주세요.", "오류");
                            return;
                        }
                        if ($("#product_seperate").val() == "") {
                            toastr.error("분류정보를 입력하세요.", "오류");
                            return;
                        }
                        var com_price = $("#contents_price").val();
                        var com_sel_price = $("#contents_sell_price").val();
                        var com_val_price = (com_price - com_sel_price) * 0.1;
                        formData.append("product_code", $("#product_code").val());
                        formData.append("product_model_name", $("#product_model_name").val());
                        formData.append("product_seperate", $("#product_seperate").val());
                        formData.append("send_salary_price", $("#send_salary_price").val());
                        //formData.append("prod_manufact_price", $("#prod_manufact_price").val());
                        formData.append("prod_manufact_price", 0);
                        formData.append("send_provide_price", $("#send_provide_price").val());
                    }
                    if (is_artist && req_provide != "Y") {
                        var art_type = $("input[name='art_type']:checked").val();
                        if (art_type == undefined) {
                            toastr.error("작품구분을 선택해주세요.", "오류");
                            return;
                        } else {
                            formData.append("art_type", art_type);
                            if ($("#gallery_format").is(":visible") && $("#gallery_format").val().trim() == "") {
                                toastr.error("원본규격을 입력하세요.", "오류");
                                $("#gallery_format").focus();
                                return;
                            }
                            if ($("#gallery_size").is(":visible") && $("#gallery_size").val().trim() == "") {
                                toastr.error("원본사이즈를 입력하세요.", "오류");
                                $("#gallery_size").focus();
                                return;
                            }
                            if ($("#gallery_price").is(":visible") && $("#gallery_price").val().trim() == "") {
                                toastr.error("원본가격을 입력하세요.", "오류");
                                $("#gallery_price").focus();
                                return;
                            }
                            if ($("#gallery_sell_price").is(":visible") && $("#gallery_sell_price").val().trim() == "") {
                                toastr.error("원본할인가격을 입력하세요.", "오류");
                                $("#gallery_sell_price").focus();
                                return;
                            }
                            if ($("#gallery_download_price").is(":visible") && $("#gallery_download_price").val().trim() == "") {
                                toastr.error("갤러리다운로드가격을 입력하세요.", "오류");
                                $("#gallery_download_price").focus();
                                return;
                            }
                            if (art_type == 0) {
                                formData.append("contents_price", $('#contents_price').val().replace(",", ""));
                                formData.append("contents_sell_price", $('#contents_sell_price').val().replace(",", ""));
                            } else {
                                formData.append("contents_price", $('#gallery_price').val().replace(",", "") + "|" +
                                    $("#gallery_format").val().trim() + "|" +
                                    $("#gallery_size").val().trim() + "|" +
                                    $('#gallery_download_price').val().replace(",", ""));
                                formData.append("contents_sell_price", $('#gallery_sell_price').val().replace(",", ""));
                            }
                        }
                    } else {
                        formData.append("art_type", 0);
                        formData.append("contents_price", $('#contents_price').val().replace(",", ""));
                        formData.append("contents_sell_price", $('#contents_sell_price').val().replace(",", ""));
                    }
                }
                var media_type = "I";
                if ($("#cont_modal_btn_image").hasClass("btn-cont-active")) {
                    if (cont_modal_fileBuffer.length > 0) {
                        cont_modal_fileBuffer.forEach(file => {
                            formData.append("contents_img[]", file);
                        });
                    }
                    media_type = "I";
                } else if ($("#cont_modal_btn_web").hasClass("btn-cont-active")) {
                    formData.append('contents_img_url', cont_modal_linkBuffer.toString());
                    media_type = "I";
                } else if ($("#cont_modal_btn_video").hasClass("btn-cont-active")) {
                    media_type = "V";
                    formData.append('contents_vid[]', $('#cont_modal_video')[0].files[0]);
                } else if ($("#cont_modal_btn_web_video").hasClass("btn-cont-active")) {
                    media_type = "V";
                    formData.append('contents_vid_url', $('#cont_modal_video_url').val());
                }
                formData.append("media_type", media_type);
                formData.append("contents_title", $('#contents_title').val());
                formData.append("contents_order", $('#contents_order').val());
                formData.append("contents_url", $('#contents_url').val());
                formData.append("contents_iframe", $('#contents_iframe').val());
                formData.append("source_iframe", $('#source_iframe').val());
                formData.append("contents_desc", $('#contents_desc').val());
                formData.append("except_keyword", $('#except_keyword').val());
                formData.append("contents_add_multi_free", $('#contents_add_multi').val());
                formData.append("market_reg", $('#edit_content_radio_market').prop("checked"));
                var id_list = $('#contents_share_id').find("a");
                var index = 0;
                var id_array = [];
                id_list.each(function() {
                    id_array[index++] = $(this).data("id");
                });
                formData.append("contents_share_id", id_array.toString());
                formData.append("contents_idx", $("#contents_idx").val());
                formData.append("card_short_url", $("#card_short_url").val());
                formData.append("landing_mode", $("#landing_mode").prop("checked") == true ? "Y" : "N");
                formData.append("contents_footer_display", "Y");
                formData.append("contents_user_display", "Y");
                formData.append("open_type", $("input[name='contents_open']:checked").val());
                formData.append("group_id", $("#contents_group").val());


                if (req_provide == "Y") {
                    if ($("#deliver_id").val() == '') {
                        alert('배송정보를 확인해주세요.');
                        return;
                    }
                    if ($("#check_deliver_id_state").val() == "N") {
                        alert('배송정보를 모두 채워야합니다. 배송자 아이디 회원정보를 수정해주세요.');
                    }
                    formData.append("deliver_id_code", $("#deliver_id_code").val());
                    formData.append("post_type", "creat");
                    formData.append("contents_type", 3);
                    gwc_state = 1;
                    var sel_card = $("input[name=gwc_card_url]:checked").val();
                    formData.append("westory_card_url", sel_card);
                    if (sel_card == undefined) {
                        toastr.error("카테고리를 선택해 주세요.", "오류");
                        return;
                    }
                } else {
                    formData.append("post_type", $("#post_type").val());
                    formData.append("contents_type", $('#contents_type').val());
                    req_provide = "N";
                    formData.append("westory_card_url", $("input[name='westory_card_url']:checked").val());
                }
                formData.append("gwc_con_state", gwc_state);
                formData.append("req_provide", req_provide);
                if ($("#contents_westory_display").prop("checked")) {
                    formData.append("contents_westory_display", "Y");
                } else {
                    formData.append("contents_westory_display", "N");
                }
                if (service_type != 4 && $('#contents_type').val() == 3 && $("#post_content_selling_request").data("status") == "N") {
                    $("#service_contents_popup").modal("show");
                } else {
                    $.ajax({
                        type: "POST",
                        url: "/iam/ajax/contents.proc.php",
                        data: formData,
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            if (data.idx != 0) {
                                if ($("#fb_upload_check").prop("checked")) {
                                    var text = encodeURIComponent('아이엠의' + data.name + '님');
                                    var linkUrl = encodeURIComponent('<?= $domainData['sub_domain']; ?>/iam/contents.php?contents_idx=' + data.idx);
                                    window.open('http://www.facebook.com/sharer.php?u=' + linkUrl + '&t=' + text);
                                }
                            }
                            alert(data.result);
                            if (req_provide == 'Y') {
                                location.href = "/iam/req_provider_list.php";
                            } else {
                                location.reload();
                            }
                        },
                        error: function(request, status, error) {
                            alert(request + "=>" + status + ":" + error);
                        }
                    });
                }
            }
        }
        //튜토리얼 다중콘텐츠 게시하기
        function post_contents_tuto(service_type) {
            toastr.options = {
                "timeOut": 3000
            }
            if ('<?= $HTTP_HOST ?>' != "kiam.kr") {
                var remain_count = $("#remain_count").data("num") * 1;
                var share_count = $("#contents_share_id_count").data("num") * 1;
                if (share_count > remain_count) {
                    alert("콘텐츠 전송건수를 초과하였습니다.");
                    return;
                }
            }
            if ($('#preview_img_tuto').find("div").length == 0) {
                toastr.error("이미지를 첨부해 주세요.", "오류");
                $('#contents_img_tuto').focus();
                return;
            }
            var formData = new FormData();
            if (fileBuffer_tuto.length > 0) {
                fileBuffer_tuto.forEach(file => {
                    formData.append("contents_img[]", file);
                });
            }
            formData.append('contents_img_url', "");
            formData.append("post_type", "creat");
            formData.append("contents_type", 1);
            formData.append("contents_title", "");
            formData.append("contents_add_multi_free", 1);
            formData.append("contents_share_id", "");
            formData.append("card_short_url", $("#card_short_url").val());
            formData.append("westory_card_url", $("input[name='westory_card_url']:checked").val());
            formData.append("contents_footer_display", "Y");
            formData.append("contents_user_display", "Y");
            formData.append("open_type", 1);
            formData.append("group_id", null);
            formData.append("contents_order", "");
            formData.append("contents_url", "");
            formData.append("contents_iframe", "");
            formData.append("source_iframe", "");
            formData.append("contents_price", "");
            formData.append("contents_sell_price", "");
            formData.append("contents_desc", "");
            formData.append("except_keyword", "");
            formData.append("contents_idx", "");
            formData.append("open_type", 1);
            if ($("#contents_westory_display").prop("checked")) {
                formData.append("contents_westory_display", "Y");
            } else {
                formData.append("contents_westory_display", "N");
            }
            $.ajax({
                type: "POST",
                url: "/iam/ajax/contents.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    alert(data);
                    setCookie('contents_mode', 'image', 1, "");
                    location.href = "<?= $end_link ?>";
                }
            });
        }
        //다중 콘텐츠 게시하기
        function multi_get_contents() {
            var cnt = $('input[id=multi_contents]').length * 1;
            $('#multi_btn_cancel').attr("disabled", true);
            $('#multi_btn_ok').attr("disabled", true);
            $('input[id=multi_contents]').each(function() {
                var web_page = $(this).val();
                $.ajax({
                    type: "POST",
                    url: "https://www.goodhow.com/crawler/crawler/scrape/getonecontent.php",
                    dataType: "json",
                    data: {
                        web_address: web_page
                    },
                    success: function(result) {
                        cnt--;
                        multi_post_contents(result.title, result.image, web_page, result.description, cnt);
                    },
                    error: function() {
                        cnt--;
                        console.log("error : " + cnt);
                        alert(web_page + ' 페이지는 크롤링이 되지 않습니다. 다른 페이지 주소를 입력해주세요.');
                        //$('#multi_btn_cancel').attr("disabled", false);
                        //$('#multi_btn_ok').attr("disabled", false);
                        //location.reload();
                    }
                });
            });
        }

        function multi_post_contents(title, image, link, desc, cnt, card_short_url = "", type, landing_mode = "N", open_type = 1) {
            var con_type = 1;
            if (type) {
                con_type = type;
            }
            var formData = new FormData();
            toastr.options = {
                // "progressBar": true,
                "timeOut": 3000
            }
            formData.append('contents_img_url', image);
            formData.append("post_type", "creat");
            formData.append("contents_type", con_type);
            formData.append("contents_title", title);
            formData.append("contents_url", link);
            formData.append("contents_iframe", $("#contents_iframe").val());
            formData.append("contents_price", 0);
            formData.append("contents_sell_price", 0);
            formData.append("contents_desc", desc);
            formData.append("contents_share_id", "");
            formData.append("contents_idx", 0);
            if (card_short_url == "")
                formData.append("card_short_url", $("#multi_card_short_url").val());
            else
                formData.append("card_short_url", card_short_url);
            formData.append("westory_card_url", $("#multi_card_short_url").val());
            formData.append("contents_footer_display", "Y");
            formData.append("contents_user_display", "Y");
            formData.append("contents_westory_display", "Y");
            formData.append("group_id", $("#multi_contents_group").val());
            formData.append("landing_mode", landing_mode);
            formData.append("open_type", open_type);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/contents.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (cnt == 0) {
                        $('#multi_btn_cancel').attr("disabled", false);
                        $('#multi_btn_ok').attr("disabled", false);
                        alert('콘텐츠가 등록 되었습니다.');
                        location.reload();
                    } else
                        console.log(data);
                    //alert(data);
                }
            });
        }

        function contents_img_del(contents_img_src, imgID) {
            var formData = new FormData();
            if (confirm("정말 이미지를 삭제 하시겠습니까?")) {
                //contents_img_src = contents_img_src.replace("http://cdn.kiam.kr","");
                contents_img_src = contents_img_src.replace("<?= $cdn ?>", "");
                formData.append("post_type", "img_del");
                formData.append("contents_idx", $("#contents_idx").val());
                formData.append("contents_img_src", contents_img_src);
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/contents.proc.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $("#" + imgID).remove();
                        alert(data);
                    }
                });
            }
        }
        //콘텐츠 등록 팝업
        function contents_add(memid, contents_order = 0, first_card_adding_status = 0, group_id = "NULL") {
            <? if ($_GET['tutorial'] == "Y") { ?>
                $("#tooltiptext_contents_up").hide();
                $("#tutorial-loading").hide();
                $("#tutorial_contents_add_modal").modal("show");
                return;
            <? } ?>
            if (first_card_adding_status != 0) {
                toastr.error('본 카드는 공유카드이기 때문에 콘텐츠 생성이 안됩니다.');
                return;
            }
            $("#contents_add_modal").modal("show");
            cont_modal_fileBuffer = [];
            cont_modal_linkBuffer = [];

            set_add_modal("creat");
            click_one_content_add();
            //==================================
            $("#contents_group").val(group_id);
            $("#contents_order").val(contents_order);

            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd
            }
            if (mm < 10) {
                mm = '0' + mm
            }
            var today = yyyy + '-' + mm + '-' + dd;
            $("#contents_date").html(today);
            $("#market_radio").hide();
            $("#edit_content_radio_market").prop("checked", false);
        }

        function cancel_modal() {
            <? if ($_GET['tutorial'] == "Y") { ?>
                location.href = "<?= $end_link ?>";
            <? } else { ?>
                location.reload();
            <? } ?>
        }
        //다중 콘텐츠 등록 팝업
        function multi_contents_add(first_card_adding_status = 0, group_id = "") {
            if (first_card_adding_status != 0) {
                toastr.error('본 카드는 공유카드이기 때문에 콘텐츠 생성이 안됩니다.');
                return;
            }
            $("#multi_contents_add_modal").modal({
                backdrop: 'static',
                keyboard: false
            });
            $("#multi_contents_group").val(group_id);
            while ($('#multi_contents_table').find('tr').length * 1 > 1) {
                $('tr').last().remove();
            }
        }

        function contents_add_spec(contents_type, first_card_adding_status = 0) {
            if (first_card_adding_status != 0) {
                toastr.error('본 카드는 공유카드이기 때문에 콘텐츠 생성이 안됩니다.');
                return;
            }
            set_add_modal("creat");
            cont_modal_fileBuffer = [];
            cont_modal_linkBuffer = [];
            if (contents_type == 1)
                click_one_content_add();
            else if (contents_type == 2)
                click_multi_contents_add();
            else
                click_web_content_add();
            $card_selected = [];
            $card_selected.push("<?= $cur_card['card_short_url'] ?>");
            arrayToInput();
            $("#contents_add_modal").modal("show");
            $("#contents_add_modal .modal-header").show();
            if ('<?= $gkind ?>' == "") {
                $("#contents_group").val("NULL");
            }
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1; //January is 0!
            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd
            }
            if (mm < 10) {
                mm = '0' + mm
            }
            var today = yyyy + '-' + mm + '-' + dd;
            $("#contents_date").html(today);
        }

        function show_contents_utils(cont_idx) {
            if ($("#utils_index_" + cont_idx).css("display") == "none") {
                $("#utils_index_" + cont_idx).css("display", "flex");
            } else {
                $("#utils_index_" + cont_idx).css("display", "none");
            }
        }

        function show_mall_icons(cont_idx) {
            if ($("#mall_edit_utils_" + cont_idx).css("display") == "none") {
                $("#mall_edit_utils_" + cont_idx).css("display", "block");
            } else {
                $("#mall_edit_utils_" + cont_idx).css("display", "none");
            }
        }
        //콘텐츠  수정 팝업
        function contents_edit(idx, gwc_idx) {
            if (gwc_idx != '0') {
                alert('굿마켓의 상품정보는 IAMSTORE 도매몰에서 관리하므로 여기서 수정/삭제할수 없습니다. 수정이 필요하면 도매몰 관리자에게 말해주시고, 삭제는 도매몰로 내보내기하면 됩니다.');
                return;
            }
            $("#contents_add_modal").modal("show");
            set_add_modal("edit");
            $(".like_modal").html($("#contents_like_" + idx).val());
            $(".post_refresh_modal").html($("#post_count_" + idx).val() + "&#x21BA;");
            $("#contents_type").val($("#contents_type_" + idx).val());
            if ($("#landing_mode_" + idx).val() == "Y")
                $("#landing_mode").prop("checked", true);
            var media_type = $("#contents_media_type_" + idx).val();
            if (media_type == "I") {
                $("#cont_media_type").prop("checked", false);
            } else {
                $("#cont_media_type").prop("checked", true);
            }
            click_cont_type($("#contents_type").val());
            var card_short_url = $("#card_short_url_" + idx).val();
            var card_url_arr = card_short_url.split(',');
            $card_selected = card_url_arr;
            for (var i = 0; i < card_url_arr.length; i++) {
                $(".my_info_" + card_url_arr[i]).prop("checked", true);
            }
            $("#card_short_url").val(card_short_url);
            var westory_card_url = $("#westory_card_url_" + idx).val();
            $(".we_story_" + westory_card_url).prop("checked", true);
            arrayToInput();
            if ($("#contents_footer_display_" + idx).val() == "N") {
                $("#contents_footer_display").prop("checked", false);
            } else {
                $("#contents_footer_display").prop("checked", true);
            }
            if ($("#contents_westory_display_" + idx).val() == "N") {
                $("#contents_westory_display").prop("checked", false);
            } else {
                $("#contents_westory_display").prop("checked", true);
            }

            $("#contents_title").val($("#contents_title_" + idx).val());
            $("#contents_date").html($("#req_data_" + idx).val());
            $("#contents_url").val(encodeURI($("#contents_url_" + idx).val()));
            $("#contents_iframe").val($("#contents_iframe_" + idx).val().replace(/\+/g, " "));
            $("#contents_desc").val($("#contents_desc_" + idx).val().replace(/<br \/>/g, "\n"));
            $("#except_keyword").val($("#except_keyword_" + idx).val().replace(/<br \/>/g, "\n"));
            var contents_share_text = $("#contents_share_text_" + idx).val();
            var contents_share_names = $("#share_names_" + idx).val();
            add_share_id(contents_share_text, contents_share_names);
            var id_array = [];
            if (contents_share_text != '')
                id_array = contents_share_text.split(",");
            $("#contents_share_id_count").val(id_array.length + "건");
            $("#contents_share_id_count").data("num", id_array.length);
            $("#post_type").val("edit");
            $("#contents_idx").val(idx);
            if ($("#open_type_" + idx).val() == 1)
                $("#inline_open").prop("checked", true);
            else
                $("#new_open").prop("checked", true);

            $("#gwc_type_menu").hide();
            $("#gwc_con_state").val($("#gwc_con_state_" + idx).val());
            if ($("#gwc_con_state_" + idx).val() == 0) {
                $("#prod_cms").hide();
                $("#prod_provide").hide();
                $("#prod_manufact").hide();
                $("#prod_salary").hide();
            }
            $("#product_code").val($("#product_code_" + idx).val());
            $("#product_model_name").val($("#product_model_name_" + idx).val());
            $("#product_seperate").val($("#product_seperate_" + idx).val());
            //$("#prod_manufact_price").val($("#prod_manufact_price_" + idx).val());
            $("#send_salary_price").val($("#send_salary_price_" + idx).val());
            $("#send_provide_price").val($("#send_provide_price_" + idx).val());
            $("#confirm_model").attr("style", "background-color:#99cc00;color: white;padding: 5px;border-radius: 7px;margin: 5px;cursor:pointer;");
            $("#market_radio").hide();
            <? if ($_GET['key3'] == 1) { ?>
                $("#edit_busitime_side").show();
                $("#cur_con_idx_time").val(idx);
            <? } ?>
            var art_type = $("#art_type_" + idx).val();
            $("input[name='art_type'][value='" + art_type + "']").prop("checked", true);
            var contents_price = $("#contents_price_" + idx).val();
            var contents_sell_price = $("#contents_sell_price_" + idx).val();
            if (art_type == 0) {
                $(".gallery").hide();
                $(".nogallery").hide();
                var contents_percent = parseInt(100 - (contents_sell_price * 1 / contents_price * 1) * 100);
                $("#contents_price").val(contents_price);
                $("#contents_sell_price").val(contents_sell_price);
                $("#contents_percent").val(contents_percent);
            } else {
                var gallery = [];
                var pIndex = 0;
                while (contents_price.indexOf("|", pIndex) !== -1) {
                    let comma = contents_price.indexOf("|", pIndex);
                    gallery.push(contents_price.substring(pIndex, comma));
                    pIndex = comma + 1;
                }
                gallery.push(contents_price.substring(pIndex));
                var gallery_format = gallery[1];
                var gallery_size = gallery[2];
                var gallery_price = gallery[0];
                var gallery_download_price = gallery[3];
                var gallery_sell_price = contents_sell_price;
                var gallery_discount = parseInt(100 - (gallery_sell_price * 1 / gallery_price * 1) * 100);
                $("#gallery_format").val(gallery_format);
                $("#gallery_size").val(gallery_size);
                $("#gallery_price").val(gallery_price);
                $("#gallery_sell_price").val(gallery_sell_price);
                $("#gallery_download_price").val(gallery_download_price);
                $("#gallery_discount").val(gallery_discount);
                $(".nogallery").hide();
                $(".gallery").show();
            }

            var media_src = $("#contents_img_" + idx).val();
            if (media_type == "I") {
                if (media_src) {
                    cont_modal_linkBuffer = [];
                    var content_img_array = [];
                    content_img_array = media_src.split(",");
                    var cdn = "<?= $cdn_ssl ?>";
                    for (var img_index = 0; img_index < content_img_array.length; img_index++) {
                        if (content_img_array[img_index].search("kiam") != -1) {
                            content_img_array[img_index] = content_img_array[img_index].replace("http://kiam.kr", "");
                            content_img_array[img_index] = content_img_array[img_index].replace("http://www.kiam.kr", "");
                        }
                        if (content_img_array[img_index].search("http") == -1 && content_img_array[img_index].length > 0) {
                            content_img_array[img_index] = cdn + content_img_array[img_index];
                        }
                        cont_modal_linkBuffer.push(content_img_array[img_index]);
                    }
                    click_cont_web();
                    $("#cont_modal_image_type_file").addClass("develop");
                    $("#cont_modal_image_type_link").removeClass("develop");
                    $("#cont_modal_video_type_file").addClass("develop");
                    $("#cont_modal_video_type_link").addClass("develop");
                    $("#cont_modal_image").val("");
                    $("#cont_modal_image_url").val("");
                    $("#preview_img").empty();
                    refresh_contents_images();
                }
                $("#one-cont-image-desc").removeClass("develop");
            } else {
                click_cont_web_video();
                $("#cont_modal_image_type_file").addClass("develop");
                $("#cont_modal_image_type_link").addClass("develop");
                $("#cont_modal_video_type_file").addClass("develop");
                $("#cont_modal_video_type_link").removeClass("develop");
                $("#cont_modal_video_url").val(media_src);
                $("#preview_video").show();
                $("#cont_video_preview").attr('src', media_src);
                $("#cont_video_preview").load();
                $("#cont_video_preview").play();
            }

        }

        function reorder_mainImage(index) {
            if (index != 0) {
                var divs = $("#preview_img").children();
                var firstDiv = divs.eq(0);
                var targetDiv = divs.eq(index * 1);
                var targetImgSrc = targetDiv.find("img").prop("src");
                targetDiv.find("img").prop("src", firstDiv.find("img").prop("src"));
                firstDiv.find("img").prop("src", targetImgSrc);

                var formData = new FormData();
                formData.append("post_type", "change_main_img");
                formData.append("contents_idx", $("#contents_idx").val());
                formData.append("contents_img_index", index);
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/contents.proc.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {}
                });
            }
        }

        function remove_shared_content(idx, mem_id) {
            if (confirm('수신된 콘텐츠를 삭제하시겠습니까?')) {
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/contents.proc.php",
                    data: {
                        post_type: "remove_shared_content",
                        contents_idx: idx,
                        mem_id: mem_id
                    },
                    success: function(data) {
                        location.reload();
                    }
                });
            }
        }

        function locked_card_click() {
            toastr.options = {
                "timeOut": 10000
            }
            toastr.error("선택한 아이엠카드는 비공개이므로 위스토리에 나타나지 않습니다. <br><br> 노출을 원하시면  자물쇠를 공개상태로 바꾸시기 바랍니다.", "비공개 카드 선택");
        }
        $(document).on("keypress", ".contents_search_input", function(e) {
            if (e.which == 13) {
                var parent = $(this).parents(".popup_box2");
                var cur_win = parent.prop("id");
                var inputVal = $(this).val();
                iam_mystory('<?= $request_short_url . $card_owner_code ?>&cur_win=' + cur_win + '&search_key=' + inputVal);
            }
        });
        $(document).on("keypress", ".wecon_search_input", function(e) {
            if (e.which == 13) {
                var inputVal = $("#wecon_search_input").val();
                var type = getCookie('contents_mode');
                iam_mystory('<?= $request_short_url . $card_owner_code ?>&cur_win=we_story' + '&search_key=' + inputVal + '&type=' + type + '&key1=' + '<?= $_GET['key1'] ?>' + '&key2=' + '<?= $_GET['key2'] ?>' + '&sort=' + '<?= $_GET['sort'] ?>' + '&key3=' + '<?= $_GET['key3'] ?>' + '&key4=' + '<?= $_GET['key4'] ?>' + '&sort_key3=' + '<?= $_GET['sort_key3'] ?>' + '&loc_name=' + '<?= $_GET['loc_name'] ?>' + '&iamstore=' + '<?= $_GET['iamstore'] ?>' + '&cate_prod=' + '<?= $_GET['cate_prod'] ?>' + '&wide=' + '<?= $_GET['wide'] ?>');
            }
        });
        $(document).on("keypress", ".mycon_search_input", function(e) {
            if (e.which == 13) {
                var inputVal = $("#mycon_search_input").val();
                var type = getCookie('contents_mode');
                iam_mystory('<?= $request_short_url . $card_owner_code ?>&cur_win=my_info' + '&search_key=' + inputVal + '&type=' + type + '&key1=' + '<?= $_GET['key1'] ?>' + '&key2=' + '<?= $_GET['key2'] ?>' + '&sort=' + '<?= $_GET['sort'] ?>' + '&key3=' + '<?= $_GET['key3'] ?>' + '&key4=' + '<?= $_GET['key4'] ?>' + '&sort_key3=' + '<?= $_GET['sort_key3'] ?>' + '&loc_name=' + '<?= $_GET['loc_name'] ?>' + '&iamstore=' + '<?= $_GET['iamstore'] ?>' + '&cate_prod=' + '<?= $_GET['cate_prod'] ?>' + '&wide=' + '<?= $_GET['wide'] ?>');
            }
        });

        $(document).on("keypress", ".wecon_search_input1", function(e) {
            <? if ($_GET['key3'] != 1) { ?>
                alert('방문탭에서만 사용가능합니다.');
                $("#search_location_name").val('');
                return;
            <? } ?>
            if (e.which == 13) {
                var inputVal = $("#search_location_name").val();
                var type = getCookie('contents_mode');
                iam_mystory('<?= $request_short_url . $card_owner_code ?>&cur_win=we_story' + '&search_key=' + '<?= $_GET['search_key'] ?>' + '&type=' + type + '&key1=' + '<?= $_GET['key1'] ?>' + '&key2=' + '<?= $_GET['key2'] ?>' + '&key3=' + '<?= $_GET['key3'] ?>' + '&key4=' + '<?= $_GET['key4'] ?>' + '&sort=' + '<?= $_GET['sort'] ?>' + '&sort_key3=' + '<?= $_GET['sort_key3'] ?>' + '&loc_name=' + inputVal);
            }
        });
        $(document).on("keypress", "#mall_search_input", function(e) {
            if (e.which == 13) {
                var inputVal = $("#mall_search_input").val();
                iam_mystory('cur_win=' + '<?= $cur_win ?>' + '&mall_type=' + '<?= $mall_type ?>' + '&mall_type1=' + '<?= $mall_type1 ?>' + '&search_key=' + inputVal + '&sort=' + '<?= $_GET['sort'] ?>');
            }
        });

        function search_clicked(cur_win) {
            var parent = $(".popup_box2[id=" + cur_win + "]");
            var inputVal = parent.find(".contents_search_input").val();
            iam_mystory('<?= $request_short_url . $card_owner_code ?>&cur_win=' + cur_win + '&search_key=' + inputVal);
        }

        function wecon_search_clicked() {
            var inputVal = $("#wecon_search_input").val();
            var type = getCookie('contents_mode');
            iam_mystory('<?= $request_short_url . $card_owner_code ?>&cur_win=we_story' + '&search_key=' + inputVal + '&type=' + type + '&key1=' + '<?= $_GET['key1'] ?>' + '&key2=' + '<?= $_GET['key2'] ?>' + '&sort=' + '<?= $_GET['sort'] ?>' + '&key3=' + '<?= $_GET['key3'] ?>' + '&key4=' + '<?= $_GET['key4'] ?>' + '&sort=' + '<?= $_GET['sort'] ?>' + '&sort_key3=' + '<?= $_GET['sort_key3'] ?>' + '&loc_name=' + '<?= $_GET['loc_name'] ?>' + '&iamstore=' + '<?= $_GET['iamstore'] ?>' + '&cate_prod=' + '<?= $_GET['cate_prod'] ?>' + '&wide=' + '<?= $_GET['wide'] ?>');
        }

        function mycon_search_clicked() {
            var inputVal = $("#mycon_search_input").val();
            var type = getCookie('contents_mode');
            iam_mystory('<?= $request_short_url . $card_owner_code ?>&cur_win=my_info' + '&search_key=' + inputVal + '&type=' + type + '&key1=' + '<?= $_GET['key1'] ?>' + '&key2=' + '<?= $_GET['key2'] ?>' + '&sort=' + '<?= $_GET['sort'] ?>' + '&key3=' + '<?= $_GET['key3'] ?>' + '&key4=' + '<?= $_GET['key4'] ?>' + '&sort=' + '<?= $_GET['sort'] ?>' + '&sort_key3=' + '<?= $_GET['sort_key3'] ?>' + '&loc_name=' + '<?= $_GET['loc_name'] ?>' + '&iamstore=' + '<?= $_GET['iamstore'] ?>' + '&cate_prod=' + '<?= $_GET['cate_prod'] ?>' + '&wide=' + '<?= $_GET['wide'] ?>');
        }

        function wecon_location_name() {
            <? if ($_GET['key3'] != 1) { ?>
                alert('방문탭에서만 사용가능합니다.');
                $("#search_location_name").val('');
                return;
            <? } ?>
            var inputVal = $("#search_location_name").val();
            iam_mystory('<?= $request_short_url . $card_owner_code ?>&cur_win=we_story' + '&search_key=' + '<?= $_GET['search_key'] ?>' + '&key1=' + '<?= $_GET['key1'] ?>' + '&key2=' + '<?= $_GET['key2'] ?>' + '&key3=' + '<?= $_GET['key3'] ?>' + '&sort=' + '<?= $_GET['sort'] ?>' + '&sort_key3=' + '<?= $_GET['sort_key3'] ?>' + '&loc_name=' + inputVal);
        }

        function mall_search_clicked() {
            var inputVal = $("#mall_search_input").val();
            iam_mystory('cur_win=' + '<?= $cur_win ?>' + '&mall_type=' + '<?= $mall_type ?>' + '&mall_type1=' + '<?= $mall_type1 ?>' + '&search_key=' + inputVal + '&sort=' + '<?= $_GET['sort'] ?>');
        }

        function set_my_share_contents(contents_id, gwc_state) {
            if (gwc_state == "gwc_prod_unset") {
                var formData = new FormData();
                formData.append("post_type", "save_share_contents");
                formData.append("contents_id", contents_id);
                formData.append("gwc_state", gwc_state);
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/contents.proc.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alert(data);
                        location.reload();
                    }
                });
            } else if (gwc_state == "gwc_prod") {
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/product_mng.php",
                    data: {
                        mode: "get_pay_res"
                    },
                    dataType: "json",
                    success: function(data) {
                        console.log(data);
                        $("#all_get_cnt").val(data.all_get_cnt); //전체가져오기한 건수
                        $("#pre_get_cnt").val(data.pre_get_cnt); //전달가져오기한 건수
                        $("#pre_month_cnt").val(data.pre_month_cnt); //전달가져오기 가능건수
                        $("#pre_month_money").val(data.pre_month_money); //전달구매금액
                        $("#this_get_cnt").val(data.this_get_cnt); //당월가져오기한 총건수
                        $("#this_month_cnt").val(data.this_month_cnt); //당월가져오기 가능전체건수
                        $("#this_month_money").val(data.this_month_money); //당월구매금액

                        var this_rest_cnt = data.this_month_cnt * 1 - data.this_get_cnt * 1; //당월 잔여건수
                        var pre_rest_cnt = data.pre_month_cnt * 1 - data.pre_get_cnt * 1; //전달 잔여건수

                        var pay_money = data.this_month_money * 1 + data.pre_month_money * 1; //전달+이번달 구매 총금액
                        var posible_cnt = data.this_month_cnt * 1 + data.pre_month_cnt * 1; //전달+이번달 가져오기 가능건수
                        if (posible_cnt > 20) {
                            posible_cnt = 20;
                        }
                        var get_already_cnt = data.pre_get_cnt * 1 + data.this_get_cnt * 1; //전달+이번달 가져오기 총건수

                        var rest_get_cnt = posible_cnt - get_already_cnt; //전달+이번달 가져오기 잔여건수
                        if (rest_get_cnt > 0) {
                            $("#show_modal").val('Y');
                        }

                        $("#pay_money_month").text(number_format(data.pre_month_money) + '원 / ' + number_format(data.this_month_money) + '원');
                        $("#possible_cnt_month").text(number_format(posible_cnt) + '건');
                        $("#get_cnt_month").text(number_format(get_already_cnt) + '건');
                        $("#rest_cnt_month").text(number_format(rest_get_cnt) + '건');
                        $("#gwc_pay_result_modal").modal("show");
                    }
                });
                $("#contents_gwc_state").val(gwc_state);
                $("#contents_get_ok").data("cont_id", contents_id);
            } else {
                $("#contents_gwc_state").val(gwc_state);
                $("#contents_get_modal").modal("show");
                $("#contents_get_ok").data("cont_id", contents_id);
            }
        }

        function close_gwc_intro() {
            var all_get_cnt = $("#all_get_cnt").val();
            var pre_get_cnt = $("#pre_get_cnt").val();
            var pre_month_cnt = $("#pre_month_cnt").val();
            var pre_month_money = $("#pre_month_money").val();
            var this_get_cnt = $("#this_get_cnt").val();
            var this_month_cnt = $("#this_month_cnt").val();
            var this_month_money = $("#this_month_money").val();
            var modal_state = $("#show_modal").val();

            $("#gwc_pay_result_modal").modal('hide');
            if (modal_state == "Y") {
                $("#contents_get_modal").modal("show");
            }
        }

        function onChangeCardGetCheck(cb) {
            var checkedCard = $("#contents_get_card_url").val();
            var checkedCardArr = checkedCard.split(",");
            if (checkedCardArr[0] == "")
                checkedCardArr.pop();
            var cardURL = cb.value;
            if (cb.checked) {
                var index = checkedCardArr.indexOf(cardURL);
                if (index == -1)
                    checkedCardArr.push(cardURL);
            } else {
                var index = checkedCardArr.indexOf(cardURL);
                checkedCardArr.splice(index, 1);
            }
            $("#contents_get_card_url").val(checkedCardArr.toString());
        }

        function get_shared_contents() {
            var checkedCard = $("#contents_get_card_url").val();
            var gwc_exp_mem = '<?= $is_exp_mem ?>';
            var gwc_exp_con_cnt = '<?= $gwc_exp_con_cnt ?>';
            if (checkedCard == "") {
                toastr.error("카드를 선택해주세요.");
                return;
            }
            var cont_id = $("#contents_get_ok").data("cont_id");
            var gwc_state = $("#contents_gwc_state").val();
            var formData = new FormData();
            formData.append("post_type", "save_share_contents");
            formData.append("contents_id", cont_id);
            formData.append("checked_cards", checkedCard);
            formData.append("gwc_state", gwc_state);
            formData.append("gwc_exp_mem", gwc_exp_mem);
            formData.append("gwc_exp_con_cnt", gwc_exp_con_cnt);
            $.ajax({
                type: "POST",
                url: "/iam/ajax/contents.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    alert(data);
                    $("#contents_get_modal").modal("hide");
                    location.reload();
                }
            });
        }

        function set_block_contents(contents_id) {
            if (confirm("이 콘텐츠를 감추시겠습니까?")) {
                var formData = new FormData();
                formData.append("post_type", "block_contents");
                formData.append("block_contents_id", contents_id);
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/contents.proc.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        location.reload();
                    }
                })
            }
        }

        function set_block_user(mem_id, westory_card_url) {
            if (confirm("이 게시자의 모든 정보를 감추시겠습니까?")) {
                var formData = new FormData();
                formData.append("post_type", "block_user");
                formData.append("block_mem_id", mem_id);
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/contents.proc.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alert(data);
                        location.reload();
                    }
                })
            }
        }

        function showCard(status) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/contents.proc.php",
                data: {
                    "post_type": "show_iam_card",
                    "mem_id": "mem_id",
                    "status": status
                },
                success: function(data) {
                    alert(data);
                    location.reload();
                }
            });
        }

        function showCardLike(status, card_short_url) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/namecard.proc.php",
                data: {
                    "mode": "post_display",
                    "card_short_url": card_short_url,
                    "status": status
                },
                success: function(data) {
                    alert(data);
                    location.reload();
                }
            });
        }
        var cont_modal_fileBuffer = [];
        var cont_modal_linkBuffer = [];

        $(document).on("keypress", "#cont_modal_image_url", function(e) {
            if (e.which == 13) {
                var inputVal = $("#cont_modal_image_url").val();
                if (cont_modal_linkBuffer.indexOf(inputVal) == -1) {
                    cont_modal_linkBuffer.push(inputVal);
                    refresh_contents_images();
                }
                $("#cont_modal_image_url").val("");
            }
        });

        function add_one_web_image() {
            var inputVal = $("#cont_modal_image_url").val();
            if (inputVal) {
                if (cont_modal_linkBuffer.indexOf(inputVal) == -1) {
                    cont_modal_linkBuffer.push(inputVal);
                    refresh_contents_images();
                }
                $("#cont_modal_image_url").val("");
            }
        }
        $(document).on("keypress", "#cont_modal_video_url", function(e) {
            if (e.which == 13) {
                var inputVal = $("#cont_modal_video_url").val();
                $("#preview_video").show();
                $("#cont_video_preview").attr('src', inputVal);
                $("#cont_video_preview").load();
                $("#cont_video_preview").play();
            }
        });
        $(document).on("focusout", "#cont_modal_video_url", function(e) {
            var inputVal = $("#cont_modal_video_url").val();
            $("#preview_video").show();
            $("#cont_video_preview").attr('src', inputVal);
            $("#cont_video_preview").load();
            $("#cont_video_preview").play();
        });
        var fileBuffer_tuto = [];

        function refresh_contents_images_tuto() {
            $('div').remove(".temp_content_img");
            $("#desc_con_upload").hide();
            for (var img_index = 0; img_index < fileBuffer_tuto.length; img_index++) {
                var html = "<div style = \"width:80px;height:60px;padding:1px;\" class = 'temp_content_img' id=\"" + img_index + "\">" +
                    "<img style=\"width:100%;height:100%\" src=\"" + window.URL.createObjectURL(fileBuffer_tuto[img_index]) + "\">" +
                    "<button onclick=\"contents_temp_img_del_tuto('" + img_index + "')\" style=\"position: absolute;margin-left:-23px\">" +
                    "<img src=\"/iam/img/menu/icon_main_close.png\" style=\"margin-top:-10px;\">" +
                    "</button>" +
                    "</div>";
                $("#preview_img_tuto").append(html);
            }
        }

        function contents_temp_img_del_tuto(divIndex) {
            fileBuffer_tuto.splice(divIndex * 1, 1);
            refresh_contents_images_tuto();
        }

        function arrayUnique(array) {
            var a = array.concat();
            for (var i = 0; i < a.length; ++i) {
                for (var j = i + 1; j < a.length; ++j) {
                    if (a[i].name === a[j].name)
                        a.splice(j--, 1);
                }
            }
            return a;
        }
        //콘텐츠 수정 - 이미지 삭제
        function contents_temp_img_del(divIndex) {
            cont_modal_fileBuffer.splice(divIndex * 1, 1);
            refresh_contents_images();
        }

        function contents_link_img_del(divIndex) {
            cont_modal_linkBuffer.splice(divIndex * 1, 1);
            refresh_contents_images();
        }

        function refresh_contents_images() {
            $('div').remove(".temp_content_img");
            if ($("#cont_modal_btn_image").hasClass("btn-cont-active")) {
                for (var img_index = 0; img_index < cont_modal_fileBuffer.length; img_index++) {
                    var html = "<div style = \"margin-top:10px;width:80px;height:60px;padding:1px;\" class = 'temp_content_img' id=\"" + img_index + "\">" +
                        "<img style=\"width:100%;height:100%\" src=\"" + window.URL.createObjectURL(cont_modal_fileBuffer[img_index]) + "\" onclick=\"reorder_filebuffer('" + img_index + "')\">" +
                        "<button class='btn-link' onclick=\"contents_temp_img_del('" + img_index + "')\" style=\"position: absolute;margin-left:-23px\">" +
                        "<img src='/iam/img/menu/icon_main_close.png' style='margin-top:-10px;'>" +
                        "</button>" +
                        "</div>";
                    $("#preview_img").append(html);
                }
            } else if ($("#cont_modal_btn_web").hasClass("btn-cont-active")) {
                for (var img_index = 0; img_index < cont_modal_linkBuffer.length; img_index++) {
                    if (cont_modal_linkBuffer[img_index].indexOf('http://') != -1) {
                        var cross = '<?= $cross_img ?>';
                    } else {
                        var cross = '';
                    }
                    var html = "<div style = \"margin-top:10px;width:80px;height:60px;padding:1px;\" class = 'temp_content_img' id=\"" + img_index + "\">" +
                        "<img style=\"width:100%;height:100%\" src=\"" + cross + cont_modal_linkBuffer[img_index] + "\" onclick=\"reorder_linkbuffer('" + img_index + "')\">" +
                        "<button class=\"btn-link\" onclick=\"contents_link_img_del('" + img_index + "')\" style=\"position: absolute;margin-left:-23px\">" +
                        "<img src=\"/iam/img/menu/icon_main_close.png\" style=\"margin-top:-10px;\">" +
                        "</button>" +
                        "</div>";
                    $("#preview_img").append(html);
                }
            } else if ($("#cont_modal_btn_video").hasClass("btn-cont-active")) {}
        }

        function reorder_filebuffer(index) {
            var tempBuffer = cont_modal_fileBuffer[index];
            cont_modal_fileBuffer[index] = cont_modal_fileBuffer[0];
            cont_modal_fileBuffer[0] = tempBuffer;
            refresh_contents_images();
        }

        function reorder_linkbuffer(index) {
            var tempBuffer = cont_modal_linkBuffer[index];
            cont_modal_linkBuffer[index] = cont_modal_linkBuffer[0];
            cont_modal_linkBuffer[0] = tempBuffer;
            refresh_contents_images();
        }

        function groupCheckClick(e) {
            var checkboxes = $(".friends.checkboxes");
            if (e.checked) {
                for (var i = 0; i < checkboxes.length; i++) {
                    $(checkboxes[i]).prop("checked", true);
                }
            } else {
                for (var i = 0; i < checkboxes.length; i++) {
                    $(checkboxes[i]).prop("checked", false);
                }
            }
            friends_chk_count();
        }
        //연락처 2020-11-09
        function groupCheckClick_contact(e) {
            var checkboxes = $(".contacts.checkboxes");
            if (e.checked) {
                for (var i = 0; i < checkboxes.length; i++) {
                    $(checkboxes[i]).prop("checked", true);
                }
            } else {
                for (var i = 0; i < checkboxes.length; i++) {
                    $(checkboxes[i]).prop("checked", false);
                }
            }
            contact_chk_count();
        }
        //종이명함 2024-10-09
        function groupCheckClick_paper(e) {
            var checkboxes = $(".paper.checkboxes");
            if (e.checked) {
                for (var i = 0; i < checkboxes.length; i++) {
                    $(checkboxes[i]).prop("checked", true);
                }
            } else {
                for (var i = 0; i < checkboxes.length; i++) {
                    $(checkboxes[i]).prop("checked", false);
                }
            }
            paper_chk_count();
        }
        resizeImg();

        function resizeImg() {
            $("#main_img1").css("background-image", "url('<?= cross_image(str_replace("http://www.kiam.kr", $cdn_ssl, $main_img1)) ?>')");
            $("#main_img1").css("background-repeat", "no-repeat");
            $("#main_img1").css("background-position", "center");
            width = $("#for_size_1").prop("naturalWidth");
            height = $("#for_size_1").prop("naturalHeight");
            if ((width / 6) > (height / 4)) {
                //console.log("width =" + width);
                $("#main_img1").css("background-size", "auto 100%");
            } else {
                $("#main_img1").css("background-size", "100% auto");
            }
            $("#main_img2").css("background-image", "url('<?= cross_image(str_replace("http://www.kiam.kr", $cdn_ssl, $main_img2)) ?>')");
            $("#main_img2").css("background-repeat", "no-repeat");
            $("#main_img2").css("background-position", "center");
            width = $("#for_size_2").prop("naturalWidth");
            height = $("#for_size_2").prop("naturalHeight");
            if ((width / 6) > (height / 4)) {
                $("#main_img2").css("background-size", "auto 100%");
            } else {
                $("#main_img2").css("background-size", "100% auto");
            }
            $("#main_img3").css("background-image", "url('<?= cross_image(str_replace("http://www.kiam.kr", $cdn_ssl, $main_img3)) ?>')");
            $("#main_img3").css("background-repeat", "no-repeat");
            $("#main_img3").css("background-position", "center");
            width = $("#for_size_3").prop("naturalWidth");
            height = $("#for_size_3").prop("naturalHeight");
            if ((width / 6) > (height / 4)) {
                $("#main_img3").css("background-size", "auto 100%");
            } else {
                $("#main_img3").css("background-size", "100% auto");
            }
        }
        /*function getMainImage(index){
            if($('#main_upload'+index+'_link').val() == "")
                alert("웹주소"+index+"를 입력해주세요.");
            else {
                $('#main_upload_btn'+index).attr("disabled", true);
                $.ajax({
                    type: "POST",
                    url: "https://www.goodhow.com/crawler/crawler/scrape/getImage.php",
                    dataType: "json",
                    data: {
                        web_address: $('#main_upload'+index+'_link').val()
                    },
                    success: function (result) {
                        $('#main_upload'+index+'_link').val(result.link);
                        $('#main_upload_img'+ index).attr('src', result.link);
                        $('#main_upload_btn'+index).attr("disabled", false);
                    },
                    error: function () {
                        $('#main_upload_btn'+index).attr("disabled", false);
                        alert("웹주소를 크롤링할수 없습니다.");
                    }
                });
            }
        }*/
        function addMainBtn(title, link) {
            var filter = "win16|win32|win64|mac";
            if (navigator.platform) {
                if (0 > filter.indexOf(navigator.platform.toLowerCase())) {} else {
                    //alert('모바일에서만 추가가 가능합니다.');
                    var bookmarkURL = window.location.href;
                    var bookmarkTitle = document.title;
                    var triggerDefault = false;
                    if (window.sidebar && window.sidebar.addPanel) {
                        // Firefox version < 23
                        window.sidebar.addPanel(bookmarkTitle, bookmarkURL, '');
                    } else if ((window.sidebar && (navigator.userAgent.toLowerCase().indexOf('firefox') > -1)) || (window.opera && window.print)) {
                        // Firefox version >= 23 and Opera Hotlist
                        var $this = $(this);
                        $this.attr('href', bookmarkURL);
                        $this.attr('title', bookmarkTitle);
                        $this.attr('rel', 'sidebar');
                        $this.off(e);
                        triggerDefault = true;
                    } else if (window.external && ('AddFavorite' in window.external)) {
                        // IE Favorite
                        window.external.AddFavorite(bookmarkURL, bookmarkTitle);
                    } else {
                        // WebKit - Safari/Chrome
                        alert((navigator.userAgent.toLowerCase().indexOf('mac') != -1 ? 'Cmd' : 'Ctrl') + '+D 키를 눌러 즐겨찾기에 등록하실 수 있습니다.');
                    }
                    //return triggerDefault;
                    return;
                }
            }
            if (link.search("https://") == -1) {
                var url = 'https://kiam.kr/' + link;
            } else {
                var url = link;
            }
            var encodeTitle = encodeURIComponent(title);
            var linkUrl = encodeURIComponent(url);
            var iconUrl = "https://kiam.kr/iam/img/favicon_iam.png";
            setIcon = encodeURIComponent(iconUrl);
            var sm_naver_customUrlScheme = "naversearchapp://addshortcut?url=" + linkUrl + "&icon=" + setIcon + "&title=" + encodeTitle + "&serviceCode=nstore&version=7";

            var userAgent = navigator.userAgent.toLowerCase();
            var iPhone = userAgent.indexOf("iphone");
            var iPad = userAgent.indexOf("ipad");
            var IOS = iPhone + iPad;
            if (IOS == -2) {
                alert(title + '을(를) 홈화면에 추가합니다.\n\n네이버앱이 없는 고객 네이버앱 설치페이지로 이동됩니다.!!');
                window.open(sm_naver_customUrlScheme);
            } else {
                alert("IOS는 직접 홈버튼추가를 사용하셔야합니다.");
            }
        }

        function openNoticeModal() {
            $("#sample-modalwindow").modal("hide");
            $.ajax({
                type: "POST",
                url: "/iam/ajax/today_popup_ajax.php",
                data: {
                    type: 'notice_popup'
                },
                dataType: "json",
                success: function(data) {
                    $("#notice-modalwindow").children().find(".modal-body").empty();
                    var result = data.result;
                    for (var i = 0; i < result.length; i++) {
                        var arr = result[i];
                        var html = "<div style=\"margin-top: 10px;\" onclick=\"window.open('" + arr.link + "')\">" +
                            "<div style=\"background-color: #ffffff;border-radius: 10px;display: flex\">" +
                            "<div style=\"border-radius: 50%;width: 50px;height: 50px;overflow: hidden;margin:auto 10px;\">" +
                            "<img src=\"" + arr.profile + "\" style=\"width: 50px;height:50px;\">" +
                            "</div>" +
                            "<div>" +
                            "<label style=\"font-size:14px;margin-left: 10px;margin-top: 10px\">" + arr.name + "</label><br>" +
                            "<label style=\"font-size:14px;margin-left: 10px;margin-top: 10px;margin-bottom: 10px\">" + arr.count + "</label>" +
                            "</div>" +
                            "</div>" +
                            "</div>";
                        $("#notice-modalwindow").children().find(".modal-body").append(html);
                    }
                    $("#intro-modalwindow").modal("hide");
                    $("#video-modalwindow").modal("hide");
                    $("#news-modalwindow").modal("hide");
                    $("#notice-modalwindow").modal("show");
                }
            });
        }

        function showVideo() {
            $("#intro-modalwindow").modal("hide");
            $.ajax({
                type: "GET",
                url: "/iam/ajax/modal_video_dialog.php",
                success: function(html) {
                    $("#video-modal-dialog").html('');
                    $("#video-modal-dialog").append(html);
                    $("#video-modalwindow").modal("show");
                }
            });

        }

        function showNews() {
            $("#intro-modalwindow").modal("hide");
            $("#news-modalwindow").modal("show");
        }

        function backIntro() {
            $("#video-modalwindow").modal("hide");
            $("#news-modalwindow").modal("hide");
            $("#intro-modalwindow").modal("show");
            clickZoomOut();
        }

        function changeVideo(src) {
            $("#intro_video").attr("src", src);
        }

        function openIntroKakao(link) {
            $("#intro-modalwindow").modal("hide");
            window.open(link);
        }

        function showNewsContent(index, value) {
            $("#news_kind").html('&#9660' + value);
            if (index != 0) {
                $(".news_content").css("display", "none");
                $(".news_kind_" + index).css("display", "block");
            } else {
                $(".news_content").css("display", "block");
            }
        }
        var popup_interval = setInterval(function() {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/today_popup_ajax.php",
                data: {
                    type: 'interval'
                },
                dataType: "json",
                success: function(data) {
                    $("#today_content_desc").parents(".modal-content").data("like_id", data.id);
                    $("#today_content_desc").html(data.desc);
                    $("#today_content-modalwindow").css("display", "block");
                    $("#today_content-modalwindow").animate({
                        bottom: '20px'
                    }, "slow");
                    $("#today_content-modalwindow").delay(2000);
                    $("#today_content-modalwindow").animate({
                        bottom: '-80px'
                    }, "slow");
                }
            });
        }, <?= $alarm_time ?> * 60 * 1000);

        function hideTodayContentModal() {
            clearInterval(popup_interval);
            $("#today_content-modalwindow").animate({
                bottom: '-80px'
            }, "fast");
            $("#today_content-modalwindow").css("display", "none");
        }

        function openSampleModal() {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/today_popup_ajax.php",
                data: {
                    type: 'sample_popup'
                },
                dataType: "json",
                success: function(data) {
                    $("#sample-modalwindow").children().find(".modal-body").empty();
                    var result = data.result;
                    for (var i = 0; i < result.length; i++) {
                        var arr = result[i];
                        var html = "<div style=\"margin-top: 10px;\" onclick=\"window.open('" + arr.link + "')\">" +
                            "<div style=\"background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex\">" +
                            "<div style=\"border-radius: 50%;width: 50px;height: 50px;overflow: hidden;margin: 10px;\">" +
                            "<img src=\"" + arr.profile + "\" style=\"width: 50px;height:50px;\">" +
                            "</div>" +
                            "<div>" +
                            "<p style=\"font-size:14px;font-weight:bold;margin-left: 10px;margin-top: 10px\">" + arr.name1 + "</p>" +
                            "<p style=\"font-size:14px;font-weight:bold;margin-left: 10px;margin-top: 10px;margin-bottom: 10px\">" + arr.name2 + "</p>" +
                            "</div>" +
                            "</div>" +
                            "</div>";
                        $("#sample-modalwindow").children().find(".modal-body").append(html);
                    }
                    $("#notice-modalwindow").modal("hide");
                    $("#sample-modalwindow").modal("show");
                }
            });
        }

        function backNotice() {
            $("#notice-modalwindow").modal("show");
            $("#sample-modalwindow").modal("hide");
        }

        function clickLike() {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/today_popup_ajax.php",
                data: {
                    type: 'alarm_like',
                    mem_id: $("#today_content_desc").parents(".modal-content").data("mem_id"),
                    like_id: $("#today_content_desc").parents(".modal-content").data("like_id")
                },
                dataType: "json"
            });
        }
        var zoomFilter = "win16|win32|win64|mac";
        if (navigator.platform) {
            if (zoomFilter.indexOf(navigator.platform.toLowerCase()) < 0) {
                $("#btnZoomIn").css("display", "none");
                $("#video-modal-dialog").css("margin-left", "auto");
                $("#video-modal-dialog").css("margin-right", "auto");
                $("#video-modal-dialog").css("max-width", "450px");
                $("#intro_video").css("height", "200px");
            }
        }

        function clickZoomIn() {
            $("#zoomInVideo").css("display", "block");
            $("#btnZoomIn").css("display", "none");
            $("#zoomInVideo").append($("#intro_video"));
            $("#zoomOutVideo").css("display", "none");
            $("#zoomInVideo").css("top", "50px");
            $("#zoomInVideo").css("max-width", window.innerWidth - $("#video-modal-dialog").width() - 20);
            var h = $("#zoomInVideo").width() / 16 * 9;
            $("#zoomInVideo").css("height", h);
            $("#intro_video").css("height", h);
        }

        function clickZoomOut() {
            $("#zoomInVideo").css("display", "none");
            $("#btnZoomIn").css("display", "block");
            $("#zoomOutVideo").append($("#intro_video"));
            $("#zoomOutVideo").css("display", "block");
            $("#intro_video").css("height", "150px");
        }

        function show_exp_popup(index) {
            if (index == 1) {
                $("#exp_start_popup").modal('show');
            } else if (index == 2) {
                $("#exp_mid_popup").modal("show");
            } else if (index == 3) {
                $("#exp_limit_popup").modal("show");
            }
            if (index > 0) {
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/ajax.v1.php",
                    data: {
                        type: 'show_exp_popup',
                        popup_type: index
                    },
                    dataType: "json",
                    success: function(data) {
                        if (index == 4)
                            location.reload();
                    }
                });
            }
        }

        function hide_exp_popup() {
            $("#exp_start_popup").modal("hide");
            $("#exp_mid_popup").modal("hide");
            $("#exp_limit_popup").modal("hide");
        }

        function move_card() {
            if ($(".J_card_num").attr("draggable") == 'false') {
                $(".J_card_num").attr("draggable", true);
                $(".color-chips").css("background", "#acacac");
            } else {
                $(".J_card_num").attr("draggable", false);
                $(".color-chips").css("background", "#ffffff");
            }
        }

        function edit_card(card_url) {
            $("#tooltiptext_card_edit").hide();
            $("#tutorial-loading").hide();
            $(".formBox > div:first").css("display", "none");
            $(".formBox > div:last").css("display", "block");
            if ($(".profile-card").attr('style').indexOf('block') == -1) {
                $(".profile-card").attr('style', 'display:block;');
            }
        }

        function cancel_edit_card() {
            $(".formBox > div:first").css("display", "block");
            $(".formBox > div:last").css("display", "none");
            $("#card_logo").css("border", "none");
            var img = $(".profile-card > div:first >img:eq(1)");
            img.css("display", "block");
            $('#card_logo').attr('src', '<?= $cur_card['profile_logo'] ?>');

            <?
            if ($_REQUEST['tutorial'] == "Y") {
            ?>
                $("#tooltiptext_upload_img").show();
                $('body,html').animate({
                    scrollTop: 0,
                }, 100);
                $("#tutorial-loading").show();
            <? } ?>
            if ('<?= $card_hidden ?>' == 'display:none;') {
                $(".profile-card").attr('style', 'display:none;');
            }
        }

        function namecard_check(card_idx) {
            var formData = new FormData();
            if (!$('#edit_card_name').val()) {
                toastr.error("성명을 입력하세요.");
                $('#edit_card_name').focus();
                return false;
            }
            if (!$('#edit_card_company').val()) {
                toastr.error("소속과 직책을 입력하세요.");
                $('#edit_card_company').focus();
                return false;
            }
            if (!$('#edit_card_position').val()) {
                toastr.error("자기를 한문장으로 소개해보세요");
                $('#edit_card_position').focus();
                return false;
            }
            formData.append('mode', 'edit');
            formData.append('mem_id', '<?= $_SESSION['iam_member_id'] ?>');
            formData.append('card_idx', card_idx);
            formData.append('card_title', $('#edit_card_title').val());
            formData.append('card_name', $('#edit_card_name').val());
            formData.append('card_company', $('#edit_card_company').val());
            formData.append('card_position', $('#edit_card_position').val());
            formData.append('card_phone', $('#edit_card_phone').val());
            formData.append('card_email', $('#edit_card_email').val());
            formData.append('card_addr', $('#edit_card_address').val());
            formData.append('card_map', $('#card_map').val());
            formData.append('card_keyword', $('#edit_card_keyword').val());
            formData.append('next_iam_link', $('#edit_card_next_iam_link').val());

            formData.append('story_title4', '온라인정보');
            formData.append('story_online1_text', $('#edit_card_online1_text').val());
            formData.append('story_online2_text', $('#edit_card_online2_text').val());
            var link1 = $('#edit_card_online1').val();
            var link2 = $('#edit_card_online2').val();
            if (link1.indexOf("http") == -1 && link1 != "") {
                link1 = "https://" + link1;
            }
            if (link2.indexOf("http") == -1 && link2 != "") {
                link2 = "https://" + link2;
            }
            formData.append('story_online1', link1);
            formData.append('story_online2', link2);
            formData.append('online1_check', "Y");
            formData.append('online2_check', "Y");
            if ($('#edit_card_business_time').val() == undefined) {
                formData.append('business_time', '');
            } else {
                formData.append('business_time', $('#edit_card_business_time').val());
            }
            // if ($('#edit_card_online1_check').is(":checked")) {
            //     formData.append('online1_check', "Y");
            // } else {
            //     formData.append('online1_check', "N");
            // }
            // if ($('#edit_card_online2_check').is(":checked")) {
            //     formData.append('online2_check', "Y");
            // } else {
            //     formData.append('online2_check', "N");
            // }

            if ($('#edit_card_logo_input')[0].files.length) {
                formData.append('uploadFile', $('#edit_card_logo_input')[0].files[0]);
            } else if ($('#edit_card_logo').attr('src')) {
                formData.append('logo_link', $('#edit_card_logo').attr('src'));
            }
            $.ajax({
                type: "POST",
                url: "/iam/ajax/namecard.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    alert("명함수정이 완료 되었습니다.");
                    <?
                    if ($_REQUEST['tutorial'] == "Y") {
                    ?>
                        $("#tooltiptext_upload_img").show();
                        $("#tutorial-loading").show();
                        cancel_edit_card();
                        <? } else {
                        if ($_REQUEST['edit_time'] == "Y") { ?>
                            location.href = '/';
                        <? } else { ?>
                            location.reload();
                    <? }
                    } ?>
                }
            })
        }

        function show_create_card_popup() {
            $("#create_card_popup").modal("show");
        }

        function show_create_group_card_popup() {
            $("#create_group_card_popup").modal("show");
        }

        function edit_group_card(group_card_url) {
            $("#edit_group_card_popup").modal("show");
        }

        function hide_create_card_popup() {
            $("#create_card_popup").modal("hide");
            $("#create_group_card_popup").modal("hide");
        }

        function create_card_from_popup() {
            var create_card_type = $('input[name=create_card_method]:checked').val();
            if (create_card_type == 'link') {
                if ($("#create_link_card_title").val() == "") {
                    toastr.error("카드제목을 입력하세요.", "오류");
                    $("#create_link_card_title").focus();
                    return;
                }
                if ($("#create_card_link").val() == "") {
                    toastr.error("카드링크를 입력하세요.", "오류");
                    $("#create_card_link").focus();
                    return;
                }
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/namecard.proc.php",
                    data: {
                        mode: "creat",
                        card_title: $("#create_link_card_title").val(),
                        link: $("#create_link_card_link").val()
                    },
                    success: function(data) {
                        if (data.length > 20) {
                            alert(data);
                            toastr.error(data);
                            $("#create_card_link").focus();
                        } else {
                            alert(data);
                            toastr.success("명함등록이 완료 되었습니다.");
                            location.href = "/?" + data;
                        }
                    }
                });
            } else {
                hide_create_card_popup();
                $("#create_new_card_popup").modal("show");
            }
        }

        function cancel_create_card() {
            $("#create_new_card_popup").modal("hide");
        }

        function show_card_list() {
            var display = $("#create_card_list").css("display");
            if (display == "none")
                display = "block";
            else
                display = "none";
            $("#create_card_list").css("display", display);
        }

        function show_edit_card_list() {
            var display = $("#edit_card_list").css("display");
            if (display == "none")
                display = "block";
            else
                display = "none";
            $("#edit_card_list").css("display", display);
        }

        function create_new_card() {
            var formData = new FormData();
            if (!$('#create_card_name').val()) {
                toastr.error("성명을 입력하세요.");
                $('#create_card_name').focus();
                return false;
            }

            if (!$('#create_card_company').val()) {
                toastr.error("소속과 직책을 입력하세요");
                $('#create_card_company').focus();
                return false;
            }

            if (!$('#create_card_position').val()) {
                toastr.error("한문장으로 자기를 소개해요.");
                $('#create_card_position').focus();
                return false;
            }
            formData.append('mode', "creat");
            formData.append('mem_id', '<?= $_SESSION['iam_member_id'] ?>');
            formData.append('card_title', $('#create_card_title').val());
            formData.append('card_name', $('#create_card_name').val());
            formData.append('card_company', $('#create_card_company').val());
            formData.append('card_position', $('#create_card_position').val());
            formData.append('card_phone', $('#create_card_phone').val());
            formData.append('card_email', $('#create_card_email').val());
            formData.append('card_addr', $('#create_card_address').val());
            formData.append('card_map', $('#create_card_map').val());
            formData.append('card_keyword', $('#create_card_keyword').val());
            formData.append('story_title4', '온라인정보');
            formData.append('story_online1_text', $('#create_card_online1_text').val());
            formData.append('story_online2_text', $('#create_card_online2_text').val());
            formData.append('story_online1', $('#create_card_online1').val());
            formData.append('story_online2', $('#create_card_online2').val());
            formData.append('next_iam_link', $('#create_card_next_iam_link').val());
            formData.append('main_img1_link', $('#main_img1_link').val());
            formData.append('main_img2_link', $('#main_img2_link').val());
            formData.append('main_img3_link', $('#main_img3_link').val());
            formData.append('story_title1', $('#story_title1').val());
            formData.append('story_title2', $('#story_title2').val());
            formData.append('story_title3', $('#story_title3').val());
            formData.append('story_myinfo', $('#story_myinfo').val());
            formData.append('story_company', $('#story_company').val());
            formData.append('story_career', $('#story_career').val());

            if ($('#create_card_online1_check').is(":checked")) {
                formData.append('online1_check', "Y");
            } else {
                formData.append('online1_check', "N");
            }
            if ($('#create_card_online2_check').is(":checked")) {
                formData.append('online2_check', "Y");
            } else {
                formData.append('online2_check', "N");
            }
            if ($('#create_card_logo_input')[0].files.length) {
                formData.append('uploadFile', $('#create_card_logo_input')[0].files[0]);
            } else if ($('#create_card_logo').attr('src')) {
                formData.append('logo_link', $('#create_card_logo').attr('src'));
            }
            $.ajax({
                type: "POST",
                url: "/iam/ajax/namecard.proc.php",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.length > 20) {
                        toastr.error(data);
                    } else {
                        toastr.success("명함등록이 완료 되었습니다.");
                        location.href = "index.php?" + data;
                    }
                }
            });
        }

        function open_iam_mall_popup(mem_id, mall_type, card_idx) {
            if (!mem_id)
                location.href = "/iam/login.php";
            else {
                $("#sns-modalwindow").modal("hide");
                $("#mypage-modalwindow").modal("hide");
                $("#sns-modalwindow_contents").modal("hide");
                $("#iam_mall_popup").modal("show");
                $("#iam_mall_type").val(mall_type);
                $("#iam_mall_method").val("creat");
                $("#iam_mall_link").val(card_idx);
                $("#iam_mall_title").val("");
                $("#iam_mall_sub_title").val("");
                $('input[name=iam_mall_img_type]:eq(0)').prop("checked", true);
                $('input[name=iam_mall_img_type]:eq(0)').parents(".input-wrap").css("display", "block");
                $("#iam_mall_img").val("");
                $("#iam_mall_img").css("display", "block");
                $("#iam_mall_img_link").val("");
                $("#iam_mall_img_link").css("display", "none");
                $("#iam_mall_img_link").prop("readonly", false);
                $("#iam_mall_img_preview").attr("src", "");
                $("#iam_mall_desc").val("");
                $("#iam_mall_keyword").val("");
                $("#iam_mall_price").val("");
                $("#iam_mall_price").prop("readonly", false);
                $("#iam_mall_sell_price").val("");
                $("#iam_mall_sell_price").prop("readonly", false);
                $("#iam_mall_display").prop("checked", true);
                if (mall_type == 3) {
                    $("#iam_mall_text").html("아이엠 몰 콘텐츠 등록하기");
                    $(".price").show();
                    $.ajax({
                        type: "POST",
                        url: "/iam/ajax/mall.proc.php",
                        dataType: "json",
                        data: {
                            iam_mall_method: "get_content_info",
                            iam_mall_idx: card_idx
                        },
                        success: function(data) {
                            if (data.contents_type == 3) {
                                var img = data.contents_img.split(',');
                                $("#iam_mall_img_preview").attr("src", "<?= $cross_img ?>" + img[0]);
                                $("#iam_mall_desc").val(data.contents_desc);
                                $("#iam_mall_title").val(data.contents_title);
                                $('input[name=iam_mall_img_type]:eq(0)').prop("checked", false);
                                $('input[name=iam_mall_img_type]:eq(1)').prop("checked", true);
                                $('input[name=iam_mall_img_type]:eq(0)').css("display", "none");
                                $("#iam_mall_img").val("");
                                $("#iam_mall_img").css("display", "none");
                                $("#iam_mall_img_link").val(img[0]);
                                $("#iam_mall_img_link").css("display", "block");
                                $("#iam_mall_img_link").prop("readonly", true);
                                $('input[name=iam_mall_img_type]:eq(0)').parents(".input-wrap").css("display", "none");
                                $("#iam_mall_sell_price").val(data.contents_sell_price);
                                $("#iam_mall_sell_price").prop("readonly", true);
                                if (data.contents_price == "")
                                    data.contents_price = data.contents_sell_price;
                                $("#iam_mall_price").val(data.contents_price);
                                $("#iam_mall_price").prop("readonly", true);
                                $("#iam_mall_type").val(4);
                            }
                        }
                    });
                } else if (mall_type == 2) {
                    $("#iam_mall_text").html("재능마켓에 등록하기");
                    $(".price").hide();
                    $("#iam_mall_desc").val("1. 서비스 제목\n\n2. 카테고리\n\n3. 서비스 방식\n\n4. 서비스 판매가\n\n5. 서비스 기간\n");
                } else {
                    $("#iam_mall_text").html("IAM몰에 내 아이엠 등록하기");
                    $(".price").show();
                }
            }
        }

        function close_iam_mall_popup() {
            $("#iam_mall_popup").modal("hide");
        }

        function create_iam_mall(status) {
            if ($("#iam_mall_type").val() != 2 && $("#iam_mall_sell_price").val().trim() == "") {
                toastr.error("판매가를 입력하세요.", "오류");
                $("#iam_mall_sell_price").focus();
                return;
            }
            if ('<?= $Gn_mem_row['service_type'] ?>' == '3' || status == 1) {
                var formData = new FormData();
                if ($('#iam_mall_img')[0].files.length) {
                    formData.append('iam_mall_img', $('#iam_mall_img')[0].files[0]);
                } else {
                    formData.append('iam_mall_img_link', $('#iam_mall_img_link').val());
                }
                formData.append("iam_mall_idx", $("#iam_mall_idx").val());
                formData.append("iam_mall_method", $("#iam_mall_method").val());
                formData.append("iam_mall_type", $("#iam_mall_type").val());
                formData.append("iam_mall_link", $("#iam_mall_link").val());
                formData.append("iam_mall_title", $('#iam_mall_title').val());
                formData.append("iam_mall_sub_title", $('#iam_mall_sub_title').val());
                formData.append("iam_mall_desc", $('#iam_mall_desc').val());
                formData.append("iam_mall_keyword", $('#iam_mall_keyword').val());
                formData.append("iam_mall_price", $('#iam_mall_price').val());
                formData.append("iam_mall_sell_price", $('#iam_mall_sell_price').val());
                formData.append("iam_mall_display", $('#iam_mall_display').prop("checked") == true ? 1 : 0);
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/mall.proc.php",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        alert(data);
                        location.reload();
                    }
                });
            } else {
                $("#service_contents_popup").modal("show");
                close_iam_mall_popup();
                $("#service_contents_popup_ok").attr("href", "javascript:create_iam_mall(1)");
            }
        }

        function show_mall_desc(mall_idx) {
            if ($("#mall_desc_" + mall_idx).css("display") == "none")
                $("#mall_desc_" + mall_idx).css("display", "block");
            else
                $("#mall_desc_" + mall_idx).css("display", "none");
        }

        function like_mall(idx) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/mall.proc.php",
                data: {
                    iam_mall_idx: idx,
                    mem_id: '<?= $_SESSION['iam_member_id'] ?>',
                    iam_mall_method: 'like'
                },
                success: function(data) {
                    toastr.success(data, "찜하기");
                    location.reload();
                }
            });
        }

        function unlike_mall(idx) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/mall.proc.php",
                data: {
                    iam_mall_idx: idx,
                    mem_id: '<?= $_SESSION['iam_member_id'] ?>',
                    iam_mall_method: 'unlike'
                },
                success: function(data) {
                    location.reload();
                }
            });
        }

        function delete_mall(idx) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/mall.proc.php",
                data: {
                    iam_mall_idx: idx,
                    iam_mall_method: 'delete_mall'
                },
                success: function(data) {
                    alert("성공적으로 삭제되었습니다.", "삭제 성공");
                    location.reload();
                }
            });
        }

        function visit_mall(idx, link) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/mall.proc.php",
                data: {
                    iam_mall_idx: idx,
                    iam_mall_method: 'visit'
                }
            });
            window.open(link);
        }

        function get_mall_link(idx) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/mall.proc.php",
                data: {
                    iam_mall_idx: idx,
                    iam_mall_method: "get_mall_link"
                },
                dataType: 'json',
                success: function(data) {
                    $("#mall_share_link").val(data.link);
                    $("#mall_share_img").val(data.img);
                    $("#mall_share_title").val(data.title);
                    $("#mall_share_desc").val(data.desc);
                    $("#sns-modalwindow_mall").modal("show");
                }
            });
        }

        function open_iam_mall_edit_popup(mall_idx, title, sub_title, price, sell_price, img, keyword, display) {
            $("#iam_mall_popup").modal("show");
            $("#iam_mall_idx").val(mall_idx);
            $("#iam_mall_method").val("edit");
            $("#iam_mall_title").val(title);
            $("#iam_mall_sub_title").val(sub_title);
            $('input[name=iam_mall_img_type]:eq(1)').prop("checked", true);
            $("#iam_mall_img").val("");
            $("#iam_mall_img").css("display", "none");
            $("#iam_mall_img_link").val(img);
            $("#iam_mall_img_link").css("display", "block");
            $("#iam_mall_img_preview").attr("src", "<?= $cross_img ?>" + img);
            var description = $("#mall_desc_" + mall_idx).val();
            $("#iam_mall_desc").val(description);
            $("#iam_mall_keyword").val(keyword);
            $("#iam_mall_price").val(price);
            $("#iam_mall_sell_price").val(sell_price);
            $("#iam_mall_display").prop("checked", display == 1 ? true : false);
        }

        function open_mall_description(idx, title) {
            $("#custom_notice_popup").modal("show");
            var desc = $("#mall_desc_" + idx).val().replace(/\n/g, "<br>");
            console.log(desc);
            if (desc == '') {
                desc = "1. AI 모델 이름 : <br><br>2. AI 모델 명함의 구성<br>1) AI 모델 명함은 대표이미지 3장, 다양한 콘텐츠 10장 내외로 구성됩니다.<br>2) 카드는 2장으로 구성되면, 첫번째 카드는 IAM소개 두번째는 해당 모델의 이미지 콘텐츠로 구성됩니다.<br><br>3. AI 모델 명함의 활용 절차<br>1) AI 모델 명함 선택 : 여러 AI 모델명함을 살펴보면서 마음에 드는 모델을 선택합니다.<br>2) AI 모델 명함 구매 : 해당 모델의 명함을 매월 정기 결제로 구매합니다.<br>3) 모델명함 계정 기록 : 구매할때 아이디와 비번을 입력하는데 이때 계정을 기록해둡니다. <br>4) 홍보콘텐츠의 삽입 : 해당 모델 명함의 콘텐츠 사이에 업체의 상품, 서비스 등의 콘텐츠를 삽입합니다. 이때, 카드 한개당 한개 정도의 상품/서비스 콘텐츠를 넣어야 합니다. 많으면 모델명함을 보는 고객들이 싫어할 것입니다. <br><br>4. 홍보 명함 단가<br>1) AI모델 명함  : 33,000원(부가세 포함)<br>* 카드를 추가할 경우에는 한건당 1만원씩 추가되므로 관리자에게 별도 주문해주세요. <br>2) 실제 연예인 명함 : 165,000원(부가세 포함)<br>* 계약된 연예인만 가능하며, 아직 계약이 되지 않는 연예인의 경우 샘플용이므로 구매가 되지 않습니다. <br><br>5. 부가 서비스<br>1) AI모델 명함의 수정 사항 : 이용자는 AI모델 이미지 추가시 배경이나 모델의 액션을 요청하고 제작자가 개발하게 되면 비용을 추가납부합니다.<br>2) 명함에 추가한 상품 콘텐츠 제작 : 업체에서 제작하고 싶은 이미지가 있을 경우, 해당 비용을 납부하고 제작자에게 의뢰할수 있습니다.<br>3) 해당 명함의 홍보 서비스 : 해당 명함을 플랫폼 본사가 가진 디비에 홍보를 요청할수 있습니다. <br><br>감사합니다.";
            }
            $("#custom_notice_title").html(title);
            $("#custom_notice_desc").html(desc);
        }

        function open_mall_pay_popup(mall_id, category, title, card_idx, price) {
            var text = title;
            if (category == 1) {
                text += " IAM을 구매신청하셨습니다.<br>구매할 ID/PW를 입력하셔야 합니다."
                $(".mall_iam").css("display", "block");
                $(".mall_card").css("display", "none");
                $(".mall_content").css("display", "none");
                $("#mall_pay_btn").attr("href", "javascript:onPayIamMall(" + mall_id + "," + card_idx + "," + price + ")");
                $("#mall_pay_popup").modal("show");
                $("#mall_pay_text").html(text);
                $("#mall_iam_id").val("");
                $("#mall_iam_pwd").val("");
                $("#mall_card_id").val("");
                $("#mall_content_id").val("");
                $("#mall_content_card").html('<option value="">카드번호</option>');
            } else if (category == 2) {
                text += " 카드를 구매신청하셨습니다.<br>카드를 추가할 ID를 입력하세요."
                $(".mall_iam").css("display", "none");
                $(".mall_card").css("display", "block");
                $(".mall_content").css("display", "none");
                $("#mall_pay_btn").attr("href", "javascript:onPayCardMall(" + mall_id + "," + card_idx + "," + price + ")");
                $("#mall_pay_popup").modal("show");
                $("#mall_pay_text").html(text);
                $("#mall_iam_id").val("");
                $("#mall_iam_pwd").val("");
                $("#mall_card_id").val("");
                $("#mall_content_id").val("");
                $("#mall_content_card").html('<option value="">카드번호</option>');
            } else if (category == 3 || category > 10) {
                text += " 콘텐츠를 구매신청하셨습니다.<br>콘텐츠를 추가할  ID와 카드번호를 입력하세요."
                $(".mall_iam").css("display", "none");
                $(".mall_card").css("display", "none");
                $(".mall_content").css("display", "flex");
                $("#mall_card_div").html("카드번호");
                $("#mall_pay_btn").attr("href", "javascript:onPayContentMall(" + mall_id + "," + card_idx + "," + price + ")");
                $("#mall_pay_popup").modal("show");
                $("#mall_pay_text").html(text);
                $("#mall_iam_id").val("");
                $("#mall_iam_pwd").val("");
                $("#mall_card_id").val("");
                $("#mall_content_id").val("");
                $("#mall_content_card").html('<option value="">카드번호</option>');
            } else if (category == 4) { //직거래
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/mall.proc.php",
                    dataType: "json",
                    data: {
                        iam_mall_method: "get_content_info",
                        iam_mall_idx: card_idx
                    },
                    success: function(data) {
                        $("#sell_con_title").val(data.contents_title);
                        $("#sell_con_url").val("http://<?= $HTTP_HOST ?>/iam/contents.php?contents_idx=" + card_idx);
                        $("#sell_con_id").val(data.mem_id);
                        $("#settle_point").val(price);
                        $("#point_pay_type").val(category);
                        $("#point_settlement").modal('show');
                    }
                });

            } else if (category == 5) { //메시지세트
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/mall.proc.php",
                    dataType: "json",
                    data: {
                        iam_mall_method: "get_mall_info",
                        iam_mall_idx: mall_id
                    },
                    success: function(data) {
                        point_settle_modal(price, data.title, card_idx, data.mem_id, 1, 0);
                        $("#state_sale_cnt").hide();
                        $("#item_count").prop("readonly", true);
                        $("#contents_send_type_cash").val(category);
                        $("#sell_con_url_cash").val(mall_id);
                    }
                });

            }
        }

        function onPayIamMall(mall_id, card_idx, price) {
            if ($("#mall_iam_id").val() == "") {
                toastr.error("새 ID를 입력해주세요.");
                $("#mall_iam_id").focus();
                return;
            }
            if ($("#mall_iam_pwd").val() == "") {
                toastr.error("비번을 입력해주세요.");
                $("#mall_iam_pwd").focus();
                return;
            }
            var mall_data = [1, mall_id, card_idx, $("#mall_iam_id").val(), $("#mall_iam_pwd").val()];
            $.ajax({
                type: "POST",
                url: "/iam/ajax/mall.proc.php",
                dataType: "json",
                data: {
                    iam_mall_method: "check_new_id",
                    mem_id: $("#mall_iam_id").val()
                },
                success: function(data) {
                    if (data.result == "true") {
                        $("#mall_pay_popup").modal("hide");
                        go_pay(1, price, mall_data.toString());
                    } else
                        toastr.error("아이디 중복되었으니 다른 아이디 입력해주세요.", "알림");
                }
            });
        }

        function go_pay(type, price, data1) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/mall.proc.php",
                dataType: "json",
                data: {
                    iam_mall_method: "daesa_reg_pay",
                    data1: data1
                },
                success: function(data) {
                    if (data.result == "ok") {
                        location.href = "/iam/pay.php";
                    }
                }
            });
        }

        function onPayCardMall(mall_id, card_idx, price) {
            if ($("#mall_card_id").val() == "") {
                toastr.error("카드를 추가할 ID를 입력하세요.");
                $("#mall_card_id").focus();
                return;
            }
            var mall_data = [2, mall_id, card_idx, $("#mall_card_id").val()];
            $.ajax({
                type: "POST",
                url: "/iam/ajax/mall.proc.php",
                dataType: "json",
                data: {
                    iam_mall_method: "check_id",
                    mem_id: $("#mall_card_id").val()
                },
                success: function(data) {
                    if (data.result == "true") {
                        point_mall_modal(2, price, mall_data.toString());
                        $("#mall_pay_popup").modal("hide");
                    } else
                        toastr.error("존재하지 않는 ID입니다.", "오류");
                }
            });
        }

        function onPayContentMall(mall_id, card_idx, price) {
            if ($("#mall_content_id").val() == "") {
                toastr.error("기존 ID를 입력하세요.");
                $("#mall_content_id").focus();
            } else if ($("#mall_content_card").val() == "") {
                toastr.error("콘텐츠를 추가할 카드ID를 입력하세요.");
                $("#mall_content_card").focus();
            } else {
                var mall_data = [3, mall_id, card_idx, $("#mall_content_card").val()];
                point_mall_modal(3, price, mall_data.toString());
                $("#mall_pay_popup").modal("hide");
            }
        }
        $(document).on("keypress", "#mall_content_id", function(e) {
            if (e.which == 13) {
                reset_mall_content_card($(this).val());
            }
        });

        function reset_mall_content_card(val) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/mall.proc.php",
                dataType: "json",
                data: {
                    iam_mall_method: "get_card_id",
                    mem_id: val
                },
                success: function(data) {
                    if (data.result == "true") {
                        var cards = data.card_data;
                        var html = '<option value="">카드번호</option>';
                        for (var i = 0; i < cards.length; i++) {
                            var card = cards[i];
                            html += '<option value="' + card['link'] + '">' + (i + 1) + '번카드(' + card['title'] + ')' + '</option>';
                        }
                        $("#mall_content_card").html(html);
                    } else
                        toastr.error("존재하지 않는 ID입니다.", "오류");
                }
            });
        }

        function show_block_list(cont_idx) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/ajax.v1.php",
                dataType: "json",
                data: {
                    type: "get_block_data"
                },
                success: function(data) {
                    $("#block_list_modal").modal({
                        backdrop: 'static'
                    });
                    $("#block_list_body").empty();
                    var block_users = data.block_users;
                    for (var i = 0; i < block_users.length; i++) {
                        var item = block_users[i];
                        add_block_list_body(i, "계정", item.profile, item.name, item.id, item.link, item.idx);
                    }
                    var block_contents = data.block_contents;
                    for (var i = 0; i < block_contents.length; i++) {
                        var item = block_contents[i];
                        add_block_list_body(i + block_users.length, "콘텐츠", item.profile, item.name, item.id, item.link, item.idx);
                    }
                }
            });
        }

        function add_block_list_body(index, type, img, name, user_id, link, idx) {
            var html = "<tr id='block_cell_" + index + "'>" +
                "<td>" + type + "</td>" +
                "<td>" + name + "</td>" +
                "<td>" + user_id + "</td>" +
                "<td style='text-align:center'>" +
                "<div>" +
                "<img src='" + img + "' style='width: 50px;height:50px;'>" + "<br>" +
                "<a href = '" + link + "' target = 'blank'>미리보기</a>" +
                "</div>" +
                "</td>" +
                "<td>" +
                "<button type='button' class='btn btn-default btn-primary' style='border-radius: 5px;width:90%;font-size:12px;background: #337ab7;color: white' onclick=\"remove_block_one_item(" +
                index + ",'" + type + "','" + idx + "')\">해제</button>" +
                "</td>" +
                "</tr>";
            $("#block_list_body").append(html);
        }

        function remove_block_all_item() {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/ajax.v1.php",
                dataType: "json",
                data: {
                    type: "remove_all_block_data"
                },
                success: function(data) {
                    alert("성공적으로 해제되었습니다.");
                    location.reload();
                }
            });
        }

        function remove_block_one_item(index, type, idx) {
            var block_type = 1;
            if (type == "계정")
                block_type = 1;
            else
                block_type = 2;
            $.ajax({
                type: "POST",
                url: "/iam/ajax/ajax.v1.php",
                dataType: "json",
                data: {
                    type: "remove_one_block_data",
                    block_type: block_type,
                    block_idx: idx
                },
                success: function(data) {
                    alert("성공적으로 해제되었습니다.");
                    $("#block_cell_" + index).remove();
                }
            });
        }

        function set_report_contents(cont_idx) {
            $("#content_idx_report").val(cont_idx);
            $("#contents_report_modal").modal("show");
        }

        function set_report() {
            var msg = "요청하신 신고내용이 관리자에게 전송되었습니다. 관리자가 검토후에 적절한 조치를 취하고 입력한 폰 번호로 조치결과가 전달될 것입니다.";
            var index = 0;
            var title_array = [];
            $("input[name=report_title]").each(function() {
                if ($(this).prop("checked")) {
                    var id = $(this).attr('id');
                    title_array[index++] = $('label[for=' + id + ']').text();
                }
            })
            if (!index) {
                alert('신고항목을 선택해 주세요.');
                return;
            }
            var cont_idx = $("#content_idx_report").val();
            var report_title = title_array.toString();
            var report_desc = $("#report_desc_msg").val();
            var report_phone = $("#reporter_phone_num").val();
            var mem_id = '<?= $_SESSION['iam_member_id'] == "" ? $_SERVER['REMOTE_ADDR'] : $_SESSION['iam_member_id']; ?>';
            // if(report_desc == ''){
            //     alert('신고내용을 입력해 주세요.');
            //     return;
            // }
            if (report_phone == '') {
                alert('연락처를 입력해 주세요.');
                return;
            }
            if (!confirm(msg)) {
                return;
            }
            $.ajax({
                type: "POST",
                url: "/iam/ajax/report.proc.php",
                dataType: "json",
                data: {
                    type: "report_reg",
                    cont_idx: cont_idx,
                    mem_id: mem_id,
                    report_title: report_title,
                    report_desc: report_desc,
                    report_phone: report_phone
                },
                success: function(data) {
                    alert("전송 되었습니다.");
                    location.reload();
                }
            });
        }

        function open_make_group_modal() {
            $("#create_group_popup").modal("show");
            $("#create_group_notice1").hide();
            $("#create_group_notice2").hide();
            $("#create_group_notice3").hide();
        }

        function create_iam_group() {
            var sub_admin = '<?= $_SESSION['iam_member_subadmin_id'] ?>';
            if (sub_admin != "") {
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/group.proc.php",
                    dataType: "json",
                    data: {
                        method: "check_group",
                    },
                    success: function(data) {
                        if (data.group != null) {
                            $("#create_group_notice2").show();
                            $("#create_group_notice2").attr("onclick", data.group);
                        } else {
                            $("#create_group_popup").modal("hide");
                            $("#create_edit_group_popup").modal("show");
                        }
                    }
                });
            } else {
                $("#create_group_notice1").show();
            }
        }

        function set_group_public_status(index) {
            $("input[name=ce_group_public]").eq(index).prop("checked", true);
            if (index == 0)
                $("#ce_group_public").val("공개");
            else
                $("#ce_group_public").val("비공개");
        }

        function set_group_upload_status(index) {
            $("input[name=ce_group_upload]").eq(index).prop("checked", true);
            if (index == 0)
                $("#ce_group_upload").val("자동");
            else
                $("#ce_group_upload").val("수동");
        }

        function save_group() {
            if ($("#ce_group_name").val() == "") {
                toastr.error("그룹명을 입력해주세요.", "오류");
                $("#ce_group_name").focus();
                return;
            }
            var method = "edit_group";
            if ($("#ce_group_id").val() == "")
                method = "create_group";
            $.ajax({
                type: "POST",
                url: "/iam/ajax/group.proc.php",
                dataType: "json",
                data: {
                    method: method,
                    id: $("#ce_group_id").val(),
                    name: $("#ce_group_name").val(),
                    public: $('input[name=ce_group_public]:checked').val(),
                    upload: $('input[name=ce_group_upload]:checked').val(),
                    desc: $("#ce_group_desc").val()
                },
                success: function(data) {
                    alert("성공적으로 저장되었습니다.", "성공");
                    if (method == "create_group")
                        location.href = data.result;
                    else
                        location.reload();
                }
            });
        }

        function show_group_list() {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/group.proc.php",
                dataType: "json",
                data: {
                    method: "get_group_info"
                },
                success: function(data) {
                    if (data.result.length == 0) {
                        $("#create_group_notice3").show();
                    } else {
                        $("#create_group_popup").modal("hide");
                        $("#create_group_cont_popup").modal("show");
                        $("#group_list_popup").empty();
                        for (var group_index in data.result) {
                            add_group_list(data.result[group_index]);
                        }
                    }
                }
            });
        }

        function add_group_list(group_info) {
            //var group_info = JSON.parse(group);
            var html = "<div style=\"padding-top: 2px;cursor:pointer\">" +
                "<div style=\"background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display:flex;justify-content: space-between;\">" +
                "<div style=\"display: flex\" onclick=\"location.href='/?" + group_info.link + "&cur_win=group-con&gkind=" + group_info.group + "'\">" +
                "<div style=\"border-radius: 10%;width: 50px;height: 50px;overflow: hidden;margin: 10px;\">" +
                "<img src=\"" + group_info.img + "\" style=\"width: 50px;height:50px;\">" +
                "</div>" +
                "<h4 style=\"margin-top: auto;margin-bottom: auto\">" +
                group_info.title +
                "</h4>" +
                "</div>" +
                "<div class=\"dropdown \" style=\"margin-right: 10px;margin-top: auto;margin-bottom: auto;\">" +
                "<button class=\"btn dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" style=\"margin-top:0px\">" +
                "<span class=\"caret\"></span>" +
                "</button>" +
                "<ul class=\"dropdown-menu comunity\" style=\"border: 1px solid #ccc;padding:0px;\">";
            for (i in group_info.card) {
                html += "<li><a onclick=\"location.href='/?" + group_info.card[i].card_link + "&cur_win=group-con&gkind=" + group_info.group + "'\" style=\"padding:3px 3px 0px 3px !important;\">" + group_info.card[i].card_title + "</a></li>";
            }
            html += "</ul>" +
                "</div>" +
                "</div>" +
                "</div>";
            $("#group_list_popup").append(html);
        }

        function check_group_card_info() {
            if ($("#group_card_title").val() == "") {
                toastr.error("카드제목을 입력해주세요", "오류");
                $("#group_card_title").focus();
                return;
            }
            $("#create_group_card_popup").modal("hide");
            $("#settle_point").val(<?= $group_card_point ?>);
            $("#sell_con_title").val($("#group_card_title").val());
            $("#point_pay_type").val(8);
            $("#sell_con_id").val('<?= $gkind ?>');
            $("#point_settlement").modal('show');
        }

        function edit_group_card_info() {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/group.proc.php",
                data: {
                    method: "edit_group_card",
                    url: $("#edit_group_card_idx").val(),
                    card_title: $("#edit_group_card_title").val()
                },
                dataType: 'json',
                success: function(data) {
                    if (data.result == "success") {
                        alert("카드수정이 성공적으로 진행되었습니다.");
                        location.reload();
                    }
                }
            });
        }

        function show_all_group_sample_contents() {
            $("#recommend_contents_popup").modal("show");
        }

        function open_invite_modal() {
            window.open("/iam/group_invite.php?group=<?= $gkind ?>", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=768,height=1000");
        }

        function click_invite(group_id, method) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/group.proc.php",
                dataType: "json",
                data: {
                    method: method + "_invite",
                    group_id: group_id
                },
                success: function(data) {
                    if (data.result == "success") {
                        if (method == "del")
                            alert("그룹초대가 성공적으로 삭제되었습니다.");
                        else
                            alert("그룹에 성공적으로 참여되었습니다.");
                    } else {
                        alert(data.result);
                    }
                    location.reload();
                }
            });
        }

        function change_group_fix_status() {
            window.open("/iam/group_fix.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=768,height=1000");
        }

        function join_group(group_id) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/group.proc.php",
                dataType: "json",
                data: {
                    method: "accept_invite",
                    group_id: group_id
                },
                success: function(data) {
                    if (data.result == "success") {
                        alert("그룹에 성공적으로 참여되었습니다.");
                    } else {
                        alert(data.result);
                    }
                    location.reload();
                }
            });
        }

        function show_all_group(type) {
            window.open("/iam/group_join.php?type=" + type, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=768,height=1000");
        }

        function open_group_info_modal(gkind, site_iam) {
            $.ajax({
                type: "GET",
                url: "/iam/ajax/modal_group_info.php",
                data: "gkind=" + gkind +
                    "&site_iam=" + site_iam,
                success: function(html) {
                    $("#group_info_popup").html('');
                    $("#group_info_popup").append(html);
                    $("#group_info_popup").modal("show");
                }
            });
        }

        function edit_group_info(idx, name, desc, public, upload) {
            $("#group_info_popup").modal("hide");
            $("#create_edit_group_popup").modal("show");
            $("#ce_group_name").val(name);
            if (public == "Y")
                set_group_public_status(0);
            else
                set_group_public_status(1);
            if (upload == "Y")
                set_group_upload_status(0);
            else
                set_group_upload_status(1);
            $("#ce_group_desc").val(desc);
            $("#ce_group_id").val(idx);
        }

        function open_all_member_1(group) {
            window.open("/iam/group_members_1.php?group=" + group, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=768,height=1000");
        }

        function open_all_member_2(group) {
            window.open("/iam/group_members_2.php?group=" + group, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=768,height=1000");
        }

        function open_group_contents(group) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/group.proc.php",
                dataType: "html",
                data: {
                    method: "get_contents_list",
                    group_id: group
                },
                success: function(data) {
                    $("#group_contents_list_modal").modal("show");
                    $("#group_contents_list").html(data);
                }
            });
        }

        function change_group_content_display(index) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/group.proc.php",
                dataType: "json",
                data: {
                    method: "change_content_display",
                    group_id: index
                },
                success: function(data) {

                }
            });
        }

        function showIntro() {
            var modal_id = $(".modal.fade.in").attr("id");
            var pos = '<?= $cur_win ?>';
            if (modal_id)
                pos = modal_id;
            if ($('.main_image_popup').css('display') == 'block')
                pos = 'main_image_popup';
            if (pos == "auto_making_modal") {
                pos += "_" + $('input[name=web_type]:checked').attr('id');
            }
            if (pos == 'sns-modalwindow' || pos == 'sns-modalwindow_contents')
                pos = "iam share";
            $.ajax({
                type: "POST",
                url: "/admin/ajax/gn_alert_ajax.php",
                dataType: "json",
                data: {
                    mode: "get",
                    pos: pos
                },
                success: function(data) {
                    var img = data.img;
                    $("#intro_img").prop("src", img);
                    $("#intro_title").html(data.title);
                    $("#intro_desc").html(data.desc);
                    $("#intro_link_text").html("상세페이지:<a href=\"" + data.link + "\" target='blank'>" + data.link + "</a>");

                }
            });
            $("#intro-modalwindow").modal("show");
            $("#notice-modalwindow").modal("hide");
            $("#sample-modalwindow").modal("hide");
        }

        function showGroupShareModal() {
            $("#group_share_modalwindow").modal("show");
        }

        function contents_fix(cont_idx) {
            $.ajax({
                type: "POST",
                url: "/iam/ajax/group.proc.php",
                dataType: "json",
                data: {
                    method: "group_content_fix",
                    group_id: cont_idx
                },
                success: function(data) {
                    alert(data.result);
                    location.reload();
                }
            });
        }

        function stop_westory_guide() {
            $.ajax({
                type: "POST",
                url: "/ajax/ajax_session.php",
                data: {
                    stop_westory_guide: "N"
                },
                success: function(data) {
                    $("#we_con_guide").modal("hide");
                }
            });
        }

        function install_cancel() {
            $("#install-modalwindow").modal("hide");
            $("#tutorial_popup").modal("show");
        }

        function group_out(group_id, mem_id) {
            if (confirm("그룹에서 탈퇴하시겠습니까?")) {
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/group.proc.php",
                    dataType: "json",
                    data: {
                        method: "del_members",
                        group_id: group_id,
                        members: mem_id
                    },
                    success: function(data) {
                        alert("정상적으로 탈퇴처리되었습니다.");
                        location.href = "/?cur_win=group-con";
                    }
                });
            }
        }

        function open_cont_order_popup(cur_win, short_url, group_card_url) {
            $.ajax({
                type: "GET",
                url: "/iam/ajax/modal_contents_edit.php",
                data: "cur_win=" + cur_win +
                    "&short_url=" + short_url +
                    "&group_card_url=" + group_card_url,
                success: function(html) {
                    $("#cont_order_box").html('');
                    $("#cont_order_box").append(html);
                    $("#contents_order_modal").modal("show");
                }
            });

        }

        function click_cont_order_type(index) {
            if (index == 1) {
                $("#cont_order_box_body").css("display", "block");
            } else {
                $("#cont_order_box_body").css("display", "none");
            }
        }

        function del_cont_from_order(cont_idx) {
            $('#cont_order_box').find('div').each(function() {
                if ($(this).data("num") == cont_idx) {
                    $(this).css("display", "none");
                    return;
                }
            });
        }

        function change_cont_order_type(card_url) {
            var cont_order_type = $("input[name=cont_order_type]:checked").val();
            var cont_order_array = [];
            $('#cont_order_box').find('div').each(function() {
                if ($(this).css("display") != "none")
                    cont_order_array.push($(this).data("num"));
            });
            if (confirm("정말로 변경하시겠습니까?")) {
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/namecard.proc.php",
                    dataType: "json",
                    data: {
                        mode: "change_cont_order_type",
                        cont_order_type: cont_order_type,
                        card_short_url: card_url,
                        cont_order: cont_order_array.toString()
                    },
                    success: function(data) {
                        alert(data.result);
                        location.reload();
                    }
                });
            }
        }

        function allowOrderDrop(ev) {
            ev.preventDefault();
        }

        function orderDrag(ev) {
            ev.dataTransfer.setData("text", ev.target.id);
        }

        function orderDrop(ev) {
            ev.preventDefault();
            var data = ev.dataTransfer.getData("text");
            var srcIdx = data.substr(5);
            var dstIdx = ev.target.id.substr(5);
            var srcDiv = $("#" + data).parent("div");
            var dstDiv = $("#" + ev.target.id).parent("div");
            var srcHtml = srcDiv.html();
            var dstHtml = dstDiv.html();
            srcDiv.data("num", dstIdx);
            srcDiv.html(dstHtml);
            dstDiv.data("num", srcIdx);
            dstDiv.html(srcHtml);
        }
        var orderImg;

        function orderTouchStart(event) {
            orderImg = event.target;
            if (event.clientX) {
                // mousemove
                orderImg.style.left = event.clientX - orderImg.clientWidth / 2;
                orderImg.style.top = event.clientY - orderImg.clientHeight / 2;
            } else {
                // touchmove - assuming a single touchpoint
                orderImg.style.left = (event.changedTouches[0].clientX - orderImg.clientWidth / 2) + "px";
                orderImg.style.top = (event.changedTouches[0].clientY - orderImg.clientHeight / 2) + "px";
                event.preventDefault();
            }
            orderImg.style.position = 'fixed';
        }

        function orderTouchMove(event) {
            if (orderImg) {
                if (event.clientX) {
                    orderImg.style.left = event.clientX - orderImg.clientWidth / 2;
                    orderImg.style.top = event.clientY - orderImg.clientHeight / 2;
                } else {
                    orderImg.style.left = (event.changedTouches[0].clientX - orderImg.clientWidth / 2) + "px";
                    orderImg.style.top = (event.changedTouches[0].clientY - orderImg.clientHeight / 2) + "px";
                    event.preventDefault();
                }
                moving = true;
            }
        }

        function orderTouchEnd(event) {
            if (orderImg) {
                var movingX = event.changedTouches[0].clientX;
                var movingY = event.changedTouches[0].clientY;
                if (moving) {
                    $('#cont_order_box').find('div').each(function() {
                        var top = $(this).position().top + 112;
                        var left = $(this).position().left;
                        var width = $(this).width();
                        var height = $(this).height();
                        if (movingX >= left && movingX <= left + width && movingY >= top && movingY <= top + height) {
                            var srcIdx = event.target.id.substr(5);
                            var dstIdx = $(this).data("num");
                            var srcDiv = $("#order" + srcIdx).parent("div");
                            var dstDiv = $("#order" + dstIdx).parent("div");
                            var srcHtml = srcDiv.html();
                            var dstHtml = dstDiv.html();
                            srcDiv.data("num", dstIdx);
                            srcDiv.html(dstHtml);
                            dstDiv.data("num", srcIdx);
                            dstDiv.html(srcHtml);
                            dstDiv.find("img").css("position", "");
                            return;
                        }
                    });
                }
                orderImg.style.left = '';
                orderImg.style.top = '';
                orderImg.style.height = '';
                orderImg.style.width = '';
                orderImg.style.position = '';
                orderImg = null;
                moving = false;
            }
        }

        function refreshStoryTab(t1, info, t2, company, t3, career) {
            $("#story_title1").html(t1);
            $("#story_info").html(info.replace(/\n/g, '<br>'));
            $("#story_title2").html(t2);
            $("#story_company").html(company.replace(/\n/g, '<br>'));
            $("#story_title3").html(t3);
            $("#story_career").html(career.replace(/\n/g, '<br>'));
        }

        function show_login() {
            $("#simple_login_popup").modal("show");
        }

        function simple_id_check() {
            if (!$('#simple_mem_id').val()) {
                alert("아이디를 입력해주세요");
                $('#simple_mem_id').focus();
                return;
            }
            if ($('#simple_mem_id').val().length < 4) {
                alert('아이디는 4자~15자를 입력해 주세요.');
                return;
            }
            if (!checkMobile()) {
                var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
                if (!pattern.test($('#simple_mem_id').val())) {
                    alert('영문 소문자와 숫자만 사용이 가능합니다.');
                    $('#simple_mem_id').val("");
                    $('#simple_mem_id').focus();
                    return;
                }
            }
            $.ajax({
                type: "POST",
                url: "/ajax/ajax_checkid.php",
                dataType: "json",
                data: {
                    id: $('#simple_mem_id').val()
                },
                success: function(data) {
                    $("#simple_checkup").data('check', 'Y');
                    if (data.result == "0") {
                        alert("이미 가입되어있는 아이디 입니다. 새로운 아이디를 입력해 가입신청 하시거나, 이벤트 신청을 하시려면 하단 이벤트신청하기 탭을 클릭해주세요.");
                        $("#simple_id_html").hide();
                        $("#simple_checkup").attr('style', '');
                    } else {
                        $("#simple_checkup").attr('style', 'background-color: #555;');
                        $("#simple_id_html").show();
                    }
                }
            });
        }

        function simple_login() {
            if ($("#checkAll_title").prop('checked') == false) {
                alert('모든 약관내용에 동의하셔야 신청하실수 있습니다.');
                return;
            } else {
                if ($("#simple_mem_name").val() == "") {
                    alert('이름을 입력해주세요.');
                    return;
                } else if ($("#simple_mem_phone").val() == "") {
                    alert('폰번호를 입력해주세요.');
                    return;
                } else if ($("#simple_mem_id").val() == "") {
                    alert('아이디를 입력해주세요.');
                    return;
                }
                if ($("#simple_checkup").data("check") == "N") {
                    alert("아이디 중복확인을 하세요.");
                    return;
                }
                // console.log(card_short_url); return;
                var mem_name = $("#simple_mem_name").val();
                var mem_phone = $("#simple_mem_phone").val();
                var mem_id = $("#simple_mem_id").val();
                var mem_pwd = $("#simple_mem_pwd").val();
                var recom_id = '<?= $recom_id ?>';
                var site = '<?= $site ?>';
                var site_iam = '<?= $site_iam ?>';
                var card_short_url = '<?= $card_url ?>';
                var exp_mem = '<?= $exp_mem ?>';
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '/ajax/simple_join.php',
                    data: {
                        mem_name: mem_name,
                        mem_phone: mem_phone,
                        mem_id: mem_id,
                        mem_pwd: mem_pwd,
                        send_type: '<?= $_GET['smode'] ?>',
                        send_link: '<?= $_GET['slink'] ?>'
                    },
                    success: function(data) {
                        if (data.result == "success") {
                            if (checkMobile()) {
                                AppScript.goAppLogin(mem_id, mem_pwd);
                            } else {
                                gotoLogin(data.short_url, data.mem_code);
                            }
                        }
                    },
                    error: function(request, status, error) {
                        alert(request + "=>" + status + "[" + error + "]");
                    }
                });
            }
        }

        function gotoLogin(link, mem_code) {
            $.ajax({
                type: "POST",
                url: "/admin/ajax/login_iamuser.php",
                data: {
                    one_id: $("#simple_mem_id").val(),
                    mem_pass: $("#simple_mem_pwd").val(),
                    mem_code: mem_code
                },
                success: function() {
                    location.href = "/?" + link + "&tutorial=Y";
                },
                error: function() {
                    alert('초기화 실패');
                }
            });
            return false;
        }
        $(function() {
            //새카드만들기
            $('input[name=create_card_url]').on("change", function() {
                var card_short_url = $('input[name=create_card_url]:checked').val();
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/namecard.proc.php",
                    data: {
                        mode: 'select',
                        card_short_url: card_short_url
                    },
                    dataType: "json",
                    success: function(data) {
                        $("#create_card_title").val(data.card_title);
                        $("#create_card_name").val(data.card_name);
                        $("#create_card_company").val(data.card_company);
                        $("#create_card_position").val(data.card_position);
                        $("#create_card_phone").val(data.card_phone);
                        $("#create_card_address").val(data.card_addr);
                        $("#create_card_email").val(data.card_email);
                        $("#create_card_online1_text").val(data.story_online1_text);
                        $("#create_card_online1").val(data.story_online1);
                        $("#create_card_online2_text").val(data.story_online2_text);
                        $("#create_card_online2").val(data.story_online2);
                        $("#create_card_online1_check").prop("checked", data.online1_check == "Y" ? true : false);
                        $("#create_card_online2_check").prop("checked", data.online2_check == "Y" ? true : false);
                        $("#create_card_keyword").html(data.card_keyword);
                        $("#create_card_next_iam_link").val(data.next_iam_link);
                        $('#create_card_logo').attr('src', data.profile_logo);
                        $("#main_img1_link").val(data.main_img1);
                        $("#main_img2_link").val(data.main_img2);
                        $("#main_img3_link").val(data.main_img3);
                        $("#story_title1").val(data.story_title1);
                        $("#story_title2").val(data.story_title2);
                        $("#story_title3").val(data.story_title3);
                        $("#story_myinfo").val(data.story_myinfo);
                        $("#story_company").val(data.story_company);
                        $("#story_career").val(data.story_career);
                    }
                });
            });
            $("#create_card_logo_input").change(function() {
                var input = this;
                var url = $(this).val();
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#create_card_logo').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });
            $('input[name=edit_card_url]').on("change", function() {
                var card_short_url = $('input[name=edit_card_url]:checked').val();
                $.ajax({
                    type: "POST",
                    url: "/iam/ajax/namecard.proc.php",
                    data: {
                        mode: 'select',
                        card_short_url: card_short_url
                    },
                    dataType: "json",
                    success: function(data) {
                        $("#edit_card_title").val(data.card_title);
                        $("#edit_card_name").val(data.card_name);
                        $("#edit_card_company").val(data.card_company);
                        $("#edit_card_position").val(data.card_position);
                        $("#edit_card_phone").val(data.card_phone);
                        $("#edit_card_address").val(data.card_addr);
                        $("#edit_card_email").val(data.card_email);
                        $("#edit_card_online1_text").val(data.story_online1_text);
                        $("#edit_card_online1").val(data.story_online1);
                        $("#edit_card_online2_text").val(data.story_online2_text);
                        $("#edit_card_online2").val(data.story_online2);
                        $("#edit_card_online1_check").prop("checked", data.online1_check == "Y" ? true : false);
                        $("#edit_card_online2_check").prop("checked", data.online2_check == "Y" ? true : false);
                        $("#edit_card_keyword").html(data.card_keyword);
                        $("#edit_card_next_iam_link").val(data.next_iam_link);
                        $('#edit_card_logo').attr('src', data.profile_logo);
                    }
                });
            });
            //링크전송 간단회원가입
            $('.checkAgree').on("change", function() {
                $('.checkAgree').each(function() {
                    if ($(this).prop("checked") == false) {
                        $('#checkAll').prop("checked", false);
                        $('#checkAll_title').prop("checked", false);
                    }
                });
            });

            $("#checkAll").on('click', function() {
                var check = $(this).prop('checked');
                $("#checkAll_title").prop('checked', check);
                $('.checkAgree').each(function() {
                    $(this).prop('checked', check);
                });
            });

            $("#checkAll_title").on('click', function() {
                var check = $(this).prop('checked');
                $('#checkAll').prop("checked", check);
                $('.checkAgree').each(function() {
                    $(this).prop('checked', check);
                });
            })
            $("#agreement-field").on("click", function() {
                console.log("simple");
                if ($('#agreement-wrap').css("display") == "none")
                    $('#agreement-wrap').show();
                else
                    $('#agreement-wrap').hide();
            });
            $("#simple_mem_phone").on('keyup', function() {
                var mem_phone = $(this).val();
                $("#simple_mem_pwd").val(mem_phone.replace("-", "").substr(-4));
            });
            //그룹관련
            $('input[name=ce_group_public]').change(function() {
                var status = $(this).val();
                if (status == "Y")
                    $("#ce_group_public").val("공개");
                else
                    $("#ce_group_public").val("비공개");
            });
            $('input[name=ce_group_upload]').change(function() {
                var status = $(this).val();
                if (status == "Y")
                    $("#ce_group_upload").val("자동");
                else
                    $("#ce_group_upload").val("수동");
            });
            //아이엠몰 관련 팝업
            $("#mall_content_id").blur(function() {
                reset_mall_content_card($(this).val());
            });
            $('input[name=iam_mall_img_type]').change(function() {
                $('#iam_mall_img_preview').attr('src', "");
                if ($(this).val() == 'f') {
                    $("#iam_mall_img").css("display", "block");
                    $("#iam_mall_img_link").css("display", "none");
                    $("#iam_mall_img_link").val("");
                } else if ($(this).val() == 'u') {
                    $("#iam_mall_img").css("display", "none");
                    $("#iam_mall_img").val("");
                    $("#iam_mall_img_link").css("display", "block");
                }
            });
            $("#iam_mall_img_link").keyup(function() {
                $('#iam_mall_img_preview').attr('src', "<?= $cross_image ?>" + $(this).val());
            });
            $("#iam_mall_img").change(function() {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#iam_mall_img_preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });
            //추천키워드 클릭 이벤트
            $(".form-check-input").click(function() {
                var key1 = $(this).attr('value');
                var type = getCookie('contents_mode');

                sort_key3 = '';
                location.href = "?" + "<?= $request_short_url . $card_owner_code ?>&cur_win=we_story&type=" + type + "&search_key=" + "<?= $_GET['search_key'] ?>" + "&key1=" + key1 + "&key2=" + "<?= $_GET['key2'] ?>" + "&key3=0&sort_key3=" + sort_key3;
            });
            $(".map_gmarket").click(function() {
                var key3 = $(this).attr('value');
                var type = getCookie('contents_mode');
                location.href = "?" + "<?= $request_short_url . $card_owner_code ?>&cur_win=we_story&type=" + type + "&search_key=" + "<?= $_GET['search_key'] ?>" + "&key1=3&key2=" + "<?= $_GET['key2'] ?>" + "&key3=" + key3 + "&sort_key3=1&loc_name=" + "<?= $_GET['loc_name'] ?>";
            });
            $(".gwc_con_type").click(function() {
                var key4 = $(this).attr('value');
                if (key4 == "2") {
                    alert("지금 개발중입니다.");
                    return;
                }
                var navCase = navigator.userAgent.toLocaleLowerCase();
                if (navCase.search("android") > -1) {
                    $("#contents_page").html("<iframe id='ALLAT_MOBILE_FRAME' name='ALLAT_MOBILE_FRAME' src='https://<?= $HTTP_HOST ?>/iam/contents.php?contents_idx=18736835&mobile=Y' frameborder=0 width=100%px height=100%px scrolling=no></iframe>");
                    document.getElementById("contents_page").style.width = "100%";
                    document.getElementById("contents_page").style.height = "100%";
                    document.getElementById("contents_page").style.left = 0 + "px";
                    document.getElementById("contents_page").style.top = 0 + "px";
                    document.getElementById("contents_page").style.display = "block";
                    document.getElementById("wrap").style.display = "none";
                    $('body,html').animate({
                        scrollTop: 0,
                    }, 100);
                } else {
                    window.open('/iam/contents.php?contents_idx=18736835', '_blank');
                }
            });
            $(".map_gmarket_sort").click(function() {
                var sort_key3 = $(this).attr('value');
                var key1 = '<?= $_GET['key1'] ?>';
                var key1_click = '<?= $_GET['key1'] ?>';
                if (sort_key3 == 1) {
                    key1_click = 3;
                }
                if (key1 == '4') {
                    key1_click = 4;
                }
                var type = getCookie('contents_mode');
                location.href = "?" + "<?= $request_short_url . $card_owner_code ?>&cur_win=we_story&type=" + type + "&search_key=" + "<?= $_GET['search_key'] ?>" + "&key1=" + key1_click + "&key2=" + "<?= $_GET['key2'] ?>" + "&key3=" + "<?= $_GET['key3'] ?>" + "&sort_key3=" + sort_key3 + "&loc_name=" + "<?= $_GET['loc_name'] ?>" + "&key4=" + "<?= $_GET['key4'] ?>" + "&iamstore=" + "<?= $_GET['iamstore'] ?>" + "&cate_prod=" + "<?= $_GET['cate_prod'] ?>" + "&wide=" + "<?= $_GET['wide'] ?>";
            });
            $("#main_upload1_link").keyup(function() {
                $('#main_upload_img1').css('background-image', "url(" + $(this).val() + ")");
            });
            $("#main_upload1").change(function() {
                var input = this;
                var url = $(this).val();
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#main_upload_img1').css('background-image', "url(" + e.target.result + ")");
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });
            $("#main_upload2_link").keyup(function() {
                $('#main_upload_img2').css('background-image', "url(" + $(this).val() + ")");
            });
            $("#main_upload2").change(function() {
                var input = this;
                var url = $(this).val();
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#main_upload_img2').css('background-image', "url(" + e.target.result + ")");
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });
            $("#main_upload3_link").keyup(function() {
                $('#main_upload_img3').css('background-image', "url(" + $(this).val() + ")");
            });
            $("#main_upload3").change(function() {
                var input = this;
                var url = $(this).val();
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#main_upload_img3').css('background-image', "url(" + e.target.result + ")");
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });
            $("#main_upload_video_link").keyup(function() {
                $('#main_video_preview').attr('src', $(this).val());
                $('#main_video_preview').load();
                $('#main_video_preview').play();
            });
            $("#videoInput").change(function() {
                const file = event.target.files[0];
                if (file) {
                    const video = document.createElement('video');
                    video.preload = 'metadata';
                    video.src = URL.createObjectURL(file);
                    video.onloadedmetadata = function() {
                        URL.revokeObjectURL(video.src);
                        if (video.duration > 60) {
                            alert('영상 길이가 60초를 초과합니다.');
                            //event.target.value = '';  // 파일 선택 초기화
                            $("#videoInput").replaceWith($("#videoInput").clone(true));
                            $("#videoInput").val("");
                        } else {
                            $("#main_video_preview").attr('src', video.src);
                            $("#main_video_preview").load();
                            $("#main_video_preview").play();
                        }
                    };
                }
            });
            $('input[name=main_type1]').click(function() {
                if ($(this).val() == 'f') {
                    $('#main_upload1').click();
                    $('#main_upload1_link').hide();
                    $('#main_upload1_link').val("");
                } else if ($(this).val() == 'u') {
                    $("#main_upload1").val("");
                    $('#main_upload1_link').show();
                    $('#main_upload1_link').val('<?= $main_img1 ?>');
                } else {
                    $('#main_upload1').click()
                    $("#main_upload1").val("");
                    $('#main_upload1_link').show();
                }
                $('#main_upload2_link').hide();
                $('#main_upload3_link').hide();
                $('#main_upload_video_link').hide();
            });
            $('input[name=main_type2]').click(function() {
                if ($(this).val() == 'f') {
                    $('#main_upload2').click();
                    $('#main_upload2_link').hide();
                    $('#main_upload2_link').val("");
                } else if ($(this).val() == 'u') {
                    $("#main_upload2").val("");
                    $('#main_upload2_link').show();
                    $('#main_upload2_link').val('<?= $main_img2 ?>');
                } else {
                    $('#main_upload2').click();
                    $("#main_upload2").val("");
                    $('#main_upload2_link').show();
                }
                $('#main_upload1_link').hide();
                $('#main_upload3_link').hide();
                $('#main_upload_video_link').hide();
            });
            $('input[name=main_type3]').click(function() {
                if ($(this).val() == 'f') {
                    $('#main_upload3').click();
                    $('#main_upload3_link').hide();
                    $('#main_upload3_link').val("");
                } else if ($(this).val() == 'u') {
                    $("#main_upload3").val("");
                    $('#main_upload3_link').show();
                    $('#main_upload3_link').val('<?= $main_img3 ?>');
                } else {
                    $('#main_upload3').click();
                    $("#main_upload3").val("");
                    $('#main_upload3_link').show();
                }
                $('#main_upload1_link').hide();
                $('#main_upload2_link').hide();
                $('#main_upload_video_link').hide();
            });
            $('input[name=main_type_v]').click(function() {
                if ($(this).val() == 'f') {
                    $('#videoInput').click();
                    $('#main_upload_video_link').hide();
                    $('#main_upload_video_link').val("");
                } else if ($(this).val() == 'u') {
                    $("#videoInput").val("");
                    $('#main_upload_video_link').show();
                    $('#main_upload_video_link').val('<?= $cur_card['video'] ?>');
                } else {
                    $('#videoInput').click();
                    $("#videoInput").val("");
                    $('#main_upload_video_link').show();
                }
                $('#main_upload1_link').hide();
                $('#main_upload2_link').hide();
                $('#main_upload3_link').hide();
            });
            // $("#reduction_percent_event").keyup(function(){
            //     $('#reduction_percent_fixed').val('');
            // });

            // $("#reduction_percent_fixed").keyup(function(){
            //     $('#reduction_percent_event').val('');
            //     $('#reduction_percent_cnt').val('');
            // });

            // 프로필 스토리 등 콘텐츠 영역 숨기기 펼치기 스크립트
            $('#toggleContent').on('click', function() {
                $('.tab-content').toggle();
                $(this).toggleClass('active')
            });
            // 하단 콘텐츠 접기 펼치기 스크립트
            /*$('.content-item .arrow').on('click', function() {
                var desc = $(this).parents('.content-item').find('.desc-inner-content');
                desc.toggleClass('show_content');
                $(this).toggleClass('active')
                return false;
            });*/
            //이미지 업로드 팝업
            $('.controls').on('click', function() {
                $('.main_image_popup').css('display', 'block');
                $("#tooltiptext_upload_img").hide();
                $("#tutorial-loading").hide();
                $("#slider_card_idx").val($(this).data("card_idx"));
            });
            // 팝업위치 조정
            $('.utils a').on('click', function() {
                jQuery.fn.center = function() {
                    this.css('position', 'absolute');
                    this.css('width', '100%');
                    this.css('top', Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window)
                        .scrollTop()) + 'px');
                    this.css('left', Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window)
                        .scrollLeft()) + 'px');
                    return this;
                }
                //$(".popup2").center();
            });
            $(".sharepoint").on('click', function() {
                $('#people_iam_modal').modal('hide');
                $('#card_send_modal').modal('hide');
                $('#contents_send_modal').modal('hide');
                $('#place_before').val($(this).attr('data'));
                $('#sharepoint_modal').modal('show');
            });
            $("#card_send_id").keyup(function() {
                point = $(this).val();
                var arr = point.split(",");
                cnt = arr.length;
                if (point.indexOf(",") == -1 && point == "") {
                    cnt = 0;
                }
                $("#card_send_id_count").val(cnt + "건");
                $('#card_send_id_count').data('num', cnt);
            });

            $("#contents_send_id").keyup(function() {
                point = $(this).val();
                var arr = point.split(",");
                cnt = arr.length;
                if (point.indexOf(",") == -1 && point == "") {
                    cnt = 0;
                }
                $("#contents_send_id_count").val(cnt + "건");
                $('#contents_send_id_count').data('num', cnt);
            });

            $("#notice_send_id").keyup(function() {
                point = $(this).val();
                var arr = point.split(",");
                cnt = arr.length;
                if (point.indexOf(",") == -1 && point == "") {
                    cnt = 0;
                }
                $("#notice_send_id_count").val(cnt + "건");
                $('#notice_send_id_count').data('num', cnt);
            });
            $('input[name=con_send_type]').on("click", function() {
                if ($(this).val() == "1") {
                    $("#alarm_msg1").show();
                } else {
                    $("#alarm_msg1").hide();
                }
            });
            $('.post_content').on('keyup', function() {
                var post_wrap = $(this).parents(".post-wrap");
                var status_lbl = post_wrap.find("#post_status");
                status_lbl.html($(this).val().length + '/300');
            });
            $('.post_content').on('focus', function() {
                var content_idx = $("#post_popup").find("#content_idx").val();
                if ($("#mem_id").val() == "") {
                    location.href = "/iam/login.php?contents_idx=" + content_idx;
                    return;
                }
            });
            $('.post_reply_content').on('keyup', function() {
                var post_wrap = $(this).parents(".post_reply_wrap");
                var status_lbl = post_wrap.find("#post_reply_status");
                status_lbl.html($(this).val().length + '/300');
            });
            $('.post_reply_content').on('focus', function() {
                var content_idx = $("#post_popup").find("#content_idx").val();
                if ($("#mem_id").val() == "") {
                    location.href = "/iam/login.php?contents_idx=" + content_idx;
                    return;
                }
            });
            // 팝업 닫기 스크립트
            $('.popup-overlay, #closePopup').on('click', function() {
                $('.main_image_popup').css('display', 'none');
                $('.daily_popup').css('display', 'none');
                <? if ($_GET['tutorial'] == "Y") { ?>
                    $("#tooltiptext_contents_up").show();
                    $('body,html').animate({
                        scrollTop: 530,
                    }, 100);
                    $("#tutorial-loading").show();
                <? } ?>
                return false;
            });
            $('#closeAside').on('click', function() {
                $("#mypage-modalwindow").modal("hide");
            });
            //내용 글자수 제한 더보기
            $("#bottom .content-item").each(function() {
                var updateCon = $(this).find('.desc-text').html();
                if (updateCon) {
                    if (updateCon.length > 200 && $(this).prop("id") != "contents_welcome") {
                        $(this).find('.desc-text').addClass('on');
                        $(this).find('.desc-text').html('<p>' + updateCon.substr(0, 200) + '...' + '<font class="ellipsis moreCon" style="color: #669933;cursor: pointer;">더 보기</font></p>');
                        //$(this).find('.desc-text').data("data",updateCon);
                    }
                }
                $(this).find('.desc-text').find('font').on('click', function() {
                    if ($(this).parents('.desc-text').hasClass('on')) {
                        $(this).parents('.desc-text').removeClass('on')
                        $(this).parents('.desc-text').html('<p>' + updateCon.substr(0, updateCon.length) + '</p>');
                    } else {
                        $(this).parent('.desc-text').addClass('on')
                        $(this).parent('.desc-text').html(updateCon.substr(0, 200) + '...' + '<a class="ellipsis moreCon" style="color: #669933;cursor: pointer;"><p>더 보기</p></a>');
                    }
                    $(this).remove();
                });
            });
            $(".popup_holder2").click(function() {
                if ('<?= $cur_win ?>' == "we_story")
                    $("#we_story input").focus();
                else if ('<?= $cur_win ?>' == "my_info")
                    $("#my_info input").focus();
            });
            $("#contents_url").keyup(function() {
                var url = $(this).val();
                var new_open_url = $("#new_open_url").val();
                var url_array = new_open_url.split(",");
                for (var i = 0; i < url_array.length; i++) {
                    if (url.toLowerCase().search(url_array[i].toLowerCase()) != -1) {
                        $("#new_open").prop("checked", true);
                        break;
                    }
                }
            });
            $("#contents_percent").keyup(function() {
                var con_price = $("#contents_price").val();
                var con_percent = $(this).val();
                var con_sell_price = parseInt((100 - con_percent) / 100 * con_price);
                $("#contents_sell_price").val(con_sell_price);
            });

            $("#contents_sell_price").keyup(function() {
                var con_price = $("#contents_price").val();
                var con_sell_price = $(this).val();
                var con_percent = parseInt(100 - (con_sell_price / con_price) * 100);
                $("#contents_percent").val(con_percent);
            });
            $("#gallery_discount").keyup(function() {
                var con_price = $("#gallery_price").val();
                var con_percent = $(this).val();
                var con_sell_price = parseInt((100 - con_percent) / 100 * con_price);
                $("#gallery_sell_price").val(con_sell_price);
            });
            $("#gallery_sell_price").keyup(function() {
                var con_price = $("#gallery_price").val();
                var con_sell_price = $(this).val();
                var con_percent = parseInt(100 - (con_sell_price / con_price) * 100);
                $("#gallery_discount").val(con_percent);
            });
            $("#wecon_search_input").keyup(function() {
                var inputVal = $("#wecon_search_input").val();
                if (inputVal != '') {
                    $("#wecon_input").show();
                } else {
                    $("#wecon_input").hide();
                }
            });
            $("#mycon_search_input").keyup(function() {
                var inputVal = $("#mycon_search_input").val();
                if (inputVal != '') {
                    $("#mycon_input").show();
                } else {
                    $("#mycon_input").hide();
                }
            });
            $("#cont_modal_image").on("change", function() {
                Array.prototype.push.apply(cont_modal_fileBuffer, $(this)[0].files);
                cont_modal_fileBuffer = arrayUnique(cont_modal_fileBuffer);
                refresh_contents_images();
                $("#cont_modal_image").val("");
            });
            $("#clear_preview_cont_video").on("click", function() {
                $("#cont_modal_video").val("");
                $("#cont_modal_video_url").val("");
                $("#cont_video_preview").attr('src', '');
                $("#cont_video_preview").empty();
                $("#preview_video").hide();
            });
            $("#cont_modal_video").on("change", function() {
                const file = event.target.files[0];
                if (file) {
                    const video = document.createElement('video');
                    video.preload = 'metadata';
                    video.src = URL.createObjectURL(file);
                    video.onloadedmetadata = function() {
                        URL.revokeObjectURL(video.src);
                        if (video.duration > 60) {
                            alert('영상 길이가 60초를 초과합니다.');
                            $("#cont_modal_video").replaceWith($("#cont_modal_video").clone(true));
                            $("#cont_modal_video").val("");
                            $("#preview_video").hide();
                        } else {
                            $("#preview_video").show();
                            $("#cont_video_preview").attr('src', video.src);
                            $("#cont_video_preview").load();
                            $("#cont_video_preview").play();
                        }
                    };
                }
            });
            $("#contents_img_tuto").on("change", function() {
                Array.prototype.push.apply(fileBuffer_tuto, $(this)[0].files);
                fileBuffer_tuto = arrayUnique(fileBuffer_tuto);
                refresh_contents_images_tuto();
                $("#contents_img_tuto").val("");
            });
            $(".tab_1").removeClass("active");
            $(".tab-pane").removeClass("active in");

            <?
            $tab = $_GET['tab'];
            if ($tab) { ?>
                $(".<?= $tab ?>-tab").addClass("active");
                $(".<?= $tab ?>-tab-content").addClass("in active");
            <? } else { ?>
                $(".profile-tab").addClass("active");
                $(".profile-tab-content").addClass("in active");
            <? } ?>

            $("#contents_box_alarm_more").on("click", function() {
                if ($(this).data("more") == "close") {
                    $(this).data("more", "open");
                    //$(this).html("[닫기]");
                    $("#contents_box_more").css("display", "none");
                    $("#contents_box_all").css("display", "block");
                } else {
                    $(this).data("more", "close");
                    //$(this).html("[더 보기]");
                    $("#contents_box_more").css("display", "block");
                    $("#contents_box_all").css("display", "none");
                }
            });

            $("#edit_card_logo_input").change(function() {
                var input = this;
                var url = $(this).val();
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#edit_card_logo').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });
            $("#edit_card_radio_spec").on("change", function() {
                if ($(this).prop("checked")) {
                    $("#edit_card_special").css("display", "block");
                } else {
                    $("#edit_card_special").css("display", "none");
                }
            });

            $("#create_card_radio_spec").on("change", function() {
                if ($(this).prop("checked")) {
                    $("#create_card_special").css("display", "block");
                } else {
                    $("#create_card_special").css("display", "none");
                }
            });
            $("#edit_content_radio_spec").on("change", function() {
                if ($(this).prop("checked")) {
                    $("#contents_add_modal .hide_spec").removeClass("develop");
                } else {
                    $("#contents_add_modal .hide_spec").addClass("develop");
                }
            });
            $('input[name=card_send_type]').on("click", function() {
                if ($(this).val() == "cont") {
                    $("#card_send_body").show();
                } else {
                    $("#card_send_body").hide();
                }
            });
            $('input[name=create_card_method]').change(function() {
                if ($(this).val() == "link") {
                    $("#create_card_info").css("display", "block");
                } else {
                    $("#create_card_info").css("display", "none");
                }
            });
            $('.art_type').change(function() {
                if ($(this).val() > 0) {
                    $('.gallery').show();
                    $('.nogallery').hide();
                } else {
                    $('.gallery').hide();
                    $('.nogallery').show();
                }
            });
        });
    </script>
    <? include $_SERVER['DOCUMENT_ROOT'] . "/iam/inc/index_popup.php"; ?>
</body>
<script src="/iam/js/index.js"></script>
<?php
// middle log
$logs->add_log("End");
$logs->write_to_file();
?>

</html>