
<?
include_once "../../lib/rlatjd_fun.php";
header('Access-Control-Allow-Origin: *');
$limit = (intval($_GET['limit']) != 0) ? $_GET['limit'] : 4;
$offset = (intval($_GET['offset']) != 0) ? $_GET['offset'] : 0;
$content_table_name = $_GET['content_table_name'];
$card_idx = $_GET['card_idx'];
$gwc_table = $_GET['gm'];
$search_key = $_GET['search_key'];
$recent_idx = $_GET['recent_idx'];

$sql = "select block_user,block_contents from Gn_Iam_Info where mem_id = '{$_SESSION['iam_member_id']}'";
$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$row_iam_info = mysqli_fetch_array($result);

if ($row_iam_info['block_contents']) {
    $block_contents_sql = " and ct.idx not in ({$row_iam_info['block_contents']}) ";
} else {
    $block_contents_sql = "";
}
if ($row_iam_info['block_user']) {
    $b_arr = explode(',', $row_iam_info['block_user']);
    $b_str = "('" . implode("','", $b_arr) . "')";
    $block_user_sql = " and ct.mem_id not in $b_str ";
} else {
    $block_user_sql = "";
}
$search_sql = " group_display = 'Y' and contents_westory_display = 'Y' and public_display = 'Y' and contents_title!='방문자리뷰' and gwc_con_state=0";
$search_sql .= " and ct.idx > $recent_idx";
$search_sql .= $block_contents_sql;
$search_sql .= $block_user_sql;
if ($search_key) {
    if (strpos($search_key, ' ') !== false) {
        $search_key_arr = explode(' ', $search_key);
        $search_key1 = trim($search_key_arr[0]);
        $search_key2 = trim($search_key_arr[1]);
        $search_sql .= " and ((contents_title like '%$search_key1%' and contents_title like '%$search_key2%') or (contents_desc like '%$search_key1%' and contents_desc like '%$search_key2%')) ";
    } else {
        $search_sql .= " and (contents_title like '%$search_key%' or contents_desc like '%$search_key%')";
    }
}

if ($_GET['sort'] == 1) { //1시간전
    $search_sql .= " and ct.req_data >= ADDTIME(now(), '-1:0:0') ";
} else if ($_GET['sort'] == 2) { //오늘
    $search_sql .= " and ct.req_data >= DATE(now()) ";
} else if ($_GET['sort'] == 3) { //이번주
    $search_sql .= " and WEEKOFYEAR(ct.req_data) = WEEKOFYEAR(now()) and YEAR(ct.req_data) = YEAR(now()) ";
} else if ($_GET['sort'] == 4) { //이번달
    $search_sql .= " and MONTH(ct.req_data) = MONTH(now()) and YEAR(ct.req_data) = YEAR(now()) ";
} else if ($_GET['sort'] == 5) { //올해
    $search_sql .= " and YEAR(ct.req_data) = YEAR(now()) ";
}

if ($_GET['sort'] == 11) { //게시일짜
    $order_sql = " order by ct.idx desc";
} elseif ($_GET['sort'] == 12) { //조회수
    $order_sql = " ORDER BY ct.contents_temp desc";
} elseif ($_GET['sort'] == 13) { //좋아요
    $order_sql = " ORDER BY ct.contents_like desc";
} else {
    $order_sql = " ORDER BY ct.idx desc,up_data desc";
}

$contents_count_per_page = $limit;
$w_offset = $offset;
if ($w_offset < 0) {
    $w_offset = 0;
}

$logs = new Logs("../../wecon_info.txt", false);
$logs->add_log("loading start");

$cont_id_array = array();
$cont_array = array();
$total_count_ = 0;

$sql8 = "select * from Gn_Iam_Contents ct use index(idx) where $search_sql $order_sql";
$sql8 .= " limit $contents_count_per_page , " . $w_offset;
if (!$global_is_local) {
    $redisCache = new RedisCache();
    $cont_list = $redisCache->get_query_to_array($sql8);
    for ($i = 0; $i < count($cont_list); $i++) {
        array_push($cont_array, $cont_list[$i]);
    }
} else {
    $result8 = mysqli_query($self_con, $sql8) or die(mysqli_error($self_con));;
    while ($contents_row = mysqli_fetch_array($result8)) {
        array_push($cont_array, $contents_row);
    }
}
$sql = "select * from Gn_Iam_Name_Card where idx = $card_idx";
$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$cur_card = mysqli_fetch_array($result);

//콘텐츠에 현시할 이름과 아바타
$contents_user_name = $cur_card['card_name'];
$body = '';
foreach ($cont_array as $contents_row) {
    if ($_SESSION['iam_member_id'] == $contents_row['mem_id']) {
        $share_ids = explode(",", $contents_row['contents_share_text']);
        for ($index = 0; $index < count($share_ids); $index++) {
            $share_sql = "select mem_name from Gn_Member where mem_id = '$share_ids[$index]'";
            $share_result = mysqli_query($self_con, $share_sql);
            $share_row = mysqli_fetch_array($share_result);
            $share_names[$index] = htmlspecialchars($share_row['mem_name']);
        }
        $share_names = implode(",", $share_names);
    }

    //컨텐츠 조회수 카운팅(contents_temp1)
    $sql_temp1 = "update $content_table_name set contents_temp1=contents_temp1+1 where idx={$contents_row['idx']}";
    mysqli_query($self_con, $sql_temp1);

    if (!$contents_row['contents_img'])
        $content_images = null;
    else
        $content_images = explode(",", $contents_row['contents_img']);
    for ($i = 0; $i < count($content_images); $i++) {
        if (strstr($content_images[$i], "kiam")) {
            $content_images[$i] = str_replace("http://kiam.kr", "", $content_images[$i]);
            $content_images[$i] = str_replace("http://www.kiam.kr", "", $content_images[$i]);
        }
        if (!strstr($content_images[$i], "http") && $content_images[$i]) {
            $content_images[$i] = $cdn_ssl . $content_images[$i];
        }
    }

    //westory 카드
    $sql = "select * from Gn_Iam_Name_Card where idx = '{$contents_row['card_idx']}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $westory_card = mysqli_fetch_array($result);
    if ($westory_card['phone_display'] == "N")
        continue;
    $except_keywords = trim($contents_row['except_keyword']);
    if ($except_keywords != "") {
        $except_count = 0;
        $except_keywords = explode(",", $except_keywords);
        for ($index = 0; $index < count($except_keywords); $index++) {
            $except_keyword = trim($except_keywords[$index]);
            $except_sql = "select count(*) from Gn_Iam_Contents where mem_id = '{$_SESSION['iam_member_id']}'" .
                " and (contents_title like '%$except_keyword%' or contents_desc like '%$except_keyword%')";
            $except_result = mysqli_query($self_con, $except_sql);
            $except_row = mysqli_fetch_array($except_result);
            $except_count += $except_row[0] * 1;

            $except_sql = "select count(*) from Gn_Iam_Name_Card where group_id = 0 and mem_id = '{$_SESSION['iam_member_id']}'" .
                " and card_keyword like '%$except_keyword%'";
            $except_result = mysqli_query($self_con, $except_sql);
            $except_row = mysqli_fetch_array($except_result);
            $except_count += $except_row[0] * 1;
        }
        if ($except_count > 0) {
            $body .= "<!-- " . $contents_card_url . " 카드가 제외    -->";
            $body .= "<!-- idx = " . $contents_row['idx'] . " | time = " . $contents_row['req_data'] . "-->";
            $body .= "<!-- -----------------------------    -->";
            continue;
        }
    }
    $sql = "select mem_code from Gn_Member where mem_id = '{$westory_card['mem_id']}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $m_row = mysqli_fetch_array($result);
    $m_code = $m_row['mem_code'];

    $post_sql = "select SQL_CALC_FOUND_ROWS * from Gn_Iam_Post p where p.content_idx = '{$contents_row['idx']}' and p.lock_status = 'N' order by p.reg_date";
    $post_res = mysqli_query($self_con, $post_sql);
    $post_count =  mysqli_num_rows($post_res);

    $body .= "<div class=\"content_area\">" .
        "<input type=\"hidden\" id = \"contents_display_" . $contents_row['idx'] . "\" value=\"" . $contents_row['contents_display'] . " \">" .
        "<input type=\"hidden\" id = \"contents_user_display_" . $contents_row['idx'] . "\" value=\"" . $contents_row['contents_user_display'] . " \">" .
        "<input type=\"hidden\" id = \"contents_type_display_" . $contents_row['idx'] . "\" value=\"" . $contents_row['contents_type_display'] . " \">" .
        "<input type=\"hidden\" id = \"contents_footer_display_" . $contents_row['idx'] . "\" value=\"" . $contents_row['contents_footer_display'] . " \">" .
        "<input type=\"hidden\" id = \"contents_media_type_" . $contents_row['idx'] . "\" value=\"" . $contents_row['media_type'] . ".\">" .
        "<input type=\"hidden\" id = \"contents_type_" . $contents_row['idx'] . "\" value=\"" . $contents_row['contents_type'] . " \">" .
        "<input type=\"hidden\" id = \"contents_title_" . $contents_row['idx'] . "\" value=\"" . $contents_row['contents_title'] . " \">" .
        "<input type=\"hidden\" id = \"contents_img_" . $contents_row['idx'] . "\" value=\"" . $contents_row['contents_img'] . " \">" .
        "<input type=\"hidden\" id = \"contents_url_" . $contents_row['idx'] . "\" value=\"" . $contents_row['contents_url'] . " \">" .
        "<input type=\"hidden\" id = \"contents_iframe_" . $contents_row['idx'] . "\" value=\"" . str_replace('\"', "'", $contents_row['contents_iframe']) . " \">" .
        "<input type=\"hidden\" id = \"contents_price_" . $contents_row['idx'] . "\" value=\"" . $contents_row['contents_price'] . " \">" .
        "<input type=\"hidden\" id = \"contents_sell_price_" . $contents_row['idx'] . "\" value=\"" . $contents_row['contents_sell_price'] . " \">" .
        "<input type=\"hidden\" id = \"contents_desc_" . $contents_row['idx'] . "\" value=\"" . $contents_row['contents_desc'] . " \">" .
        "<input type=\"hidden\" id = \"except_keyword_" . $contents_row['idx'] . "\" value=\"" . $except_keyword . " \">" .
        "<input type=\"hidden\" id = \"contents_share_text_" . $contents_row['idx'] . "\" value=\"" . $contents_row['contents_share_text'] . " \">" .
        "<input type=\"hidden\" id = \"share_names_" . $contents_row['idx'] . "\" value=\"" . $share_names . " \">" .
        "<input type=\"hidden\" id = \"contents_westory_display_" . $contents_row['idx'] . "\" value=\"" . $contents_row['contents_westory_display'] . " \">" .
        "<input type=\"hidden\" id = \"card_short_url_" . $contents_row['idx'] . "\" value=\"" . $contents_row['card_short_url'] . " \">" .
        "<input type=\"hidden\" id = \"westory_card_url_" . $contents_row['idx'] . "\" value=\"" . $contents_row['westory_card_url'] . " \">" .
        "<input type=\"hidden\" id = \"req_data_" . $contents_row['idx'] . "\" value=\"" . $contents_row['req_data'] . " \">" .
        "<input type=\"hidden\" id = \"card_owner_" . $contents_row['idx'] . "\" value=\"" . $_GET['card_owner'] . " \">" .
        "<input type=\"hidden\" id = \"contents_user_name_" . $contents_row['idx'] . "\" value=\"" . $contents_user_name . " \">" .
        "<input type=\"hidden\" id = \"post_display_" . $contents_row['idx'] . "\" value=\"" . $westory_card['post_display'] . " \">" .
        "<input type=\"hidden\" id = \"contents_like_" . $contents_row['idx'] . "\" value=\"" . number_format($contents_row['contents_like']) . " \">" .
        "<input type=\"hidden\" id = \"post_count_" . $contents_row['idx'] . "\" value=\"" . $post_count . " \">" .
        "<input type=\"hidden\" id = \"open_type_" . $contents_row['idx'] . "\" value=\"" . $contents_row['open_type'] . " \">" .
        "<input type=\"hidden\" id = \"gwc_con_state_" . $contents_row['idx'] . "\" value=\"" . $contents_row['gwc_con_state'] . " \">" .
        "<input type=\"hidden\" id = \"product_code_" . $contents_row['idx'] . "\" value=\"" . $contents_row['product_code'] . " \">" .
        "<input type=\"hidden\" id = \"product_model_name_" . $contents_row['idx'] . "\" value=\"" . $contents_row['product_model_name'] . " \">" .
        "<input type=\"hidden\" id = \"product_seperate_" . $contents_row['idx'] . "\" value=\"" . $contents_row['product_seperate'] . " \">" .
        "<input type=\"hidden\" id = \"prod_manufact_price_" . $contents_row['idx'] . "\" value=\"" . $contents_row['prod_manufact_price'] . " \">" .
        "<input type=\"hidden\" id = \"send_salary_price_" . $contents_row['idx'] . "\" value=\"" . $contents_row['send_salary_price'] . " \">" .
        "<input type=\"hidden\" id = \"send_provide_price_" . $contents_row['idx'] . "\" value=\"" . $contents_row['send_provide_price'] . " \">" .
        "<input type=\"hidden\" id = \"landing_mode_" . $contents_row['idx'] . "\" value=\"" . $contents_row['landing_mode'] . " \">";
    if ($contents_row['contents_type'] == 3) {
        $art_sql = "select mall_type from Gn_Iam_Mall use index(card_idx) where card_idx={$contents_row['idx']}";
        $art_res = mysqli_query($self_con, $art_sql);
        $art_row = mysqli_fetch_assoc($art_res);
        if ($art_row['mall_type'] == "")
            $art_row['mall_type'] = 0;
    } else {
        $art_row['mall_type'] = 0;
    }
    $body .= "<input type=\"hidden\" id=\"art_type_" . $contents_row['idx'] . "\" value=\"" . $art_row['mall_type'] . "\">" .
        "<div class=\"content-item\" id=\"contents_image\" >";
    $contents_avatar = $contents_avatar ? cross_image($contents_avatar) : '/iam/img/common/logo-2.png';
    $href = strip_tags($contents_row['westory_card_url'] . $m_code);
    $body .= "<div class=\"user-item\" style=\"\">" .
        "<a href=\"/?" . $href . "\" class=\"img-box\"  target = \"_blank\">" .
        "<div class=\"user-img\" style=\"margin: 5px;width:32px;height:32px;\">" .
        "<img src=\"" . $contents_avatar . "\" alt=\"\">" .
        "</div>" .
        "</a>" .
        "<div class=\"wrap image_mode\">" .
        "<a href=\"/?" . $href . "\" class=\"user-name\" style=\"\">" .
        $contents_user_name .
        "</a>";
    if ($_GET['iamstore'] != 'N' || $_GET['cate_prod'] == '1268514') {
        $body .=        "<a href=\"/?" . $href . "\" class=\"date\" style=\"\">" .
            get_date_time($contents_row['req_data']) .
            "</a>";
    }
    $body .=    "</div>";
    $body .=    "<div class=\"wrap pin_mode\" style=\"display:none;\">" .
        "<a href=\"/?" . $href . "\" class=\"user-name\" style=\"\">" .
        $contents_user_name .
        "</a>";
    if ($_GET['iamstore'] != 'N') {
        $body .=        "<a href=\"/?" . $href . "\" class=\"date\" style=\"\">" .
            get_date_time($contents_row['req_data']) .
            "</a>";
    }
    $body .=    "</div>";
    //if( $_SESSION['iam_member_id'] != "" && ($_SESSION['iam_member_id'] != $contents_row['mem_id'] || ($_SESSION['iam_member_id'] == $contents_row['mem_id'] && $_SESSION['iam_member_id'] != 'iamstore' && $contents_row['provider_req_prod'] == "Y")) ){
    $body .=    "<div class=\"dropdown\" style=\"position: absolute; right: 10px; top: 8px;\">" .
        "<button class=\"btn-link dropdown-toggle westory_dropdown\" type=\"button\" data-toggle=\"dropdown\" style=\"\">" .
        "<img src=\"/iam/img/menu/icon_dot.png\" style=\"height:24px\">" .
        "</button>" .
        "<ul class=\"dropdown-menu comunity\">" .
        "<li><a onclick=\"window.open('/?" . strip_tags($contents_row['westory_card_url']) . "');\">이 콘텐츠 게시자 보기</a></li>" .
        "<li><a onclick=\"set_friend('" . $westory_card['mem_id'] . "','" . $westory_card['card_name'] . "','" . $westory_card['card_short_url'] . "','" . $westory_card['idx'] . "')\">이 게시자와 프렌즈 하기</a></li>" .
        "<li><a onclick=\"set_block_contents('" . $contents_row['idx'] . "')\">이 콘텐츠 하나만 차단하기</a></li>" .
        "<li><a onclick=\"set_block_user('" . $westory_card['mem_id'] . "','" . $westory_card['card_short_url'] . "')\">이 게시자의 정보 차단하기</a></li>";
    if ($contents_row['ai_map_gmarket'] != 3) {
        $body .=                "<li><a onclick=\"set_my_share_contents('" . $contents_row['idx'] . "')\">이 콘텐츠 나에게 가져오기</a></li>";
    } else if ($contents_row['ai_map_gmarket'] == 3 && $_GET['iamstore'] == "Y" && $_GET['gwc_mem']) {
        $body .=                "<li><a onclick=\"set_my_share_contents('" . $contents_row['idx'] . "','gwc_prod')\">내 계정으로 가져오기</a></li>";
    }
    $body .=                "<li><a onclick=\"set_report_contents('" . $contents_row['idx'] . "')\">이 콘텐츠 신고하기</a></li>" .
        "<li><a onclick=\"show_block_list('" . $contents_row['idx'] . "')\">감추기 리스트 보기</a></li>" .
        "</ul>" .
        "</div>";
    //}
    if ($_SESSION['iam_member_id'] != "" && $_SESSION['iam_member_id'] == $contents_row['mem_id'] && $_SESSION['iam_member_id'] != 'iamstore' && $contents_row['ai_map_gmarket'] == 3 && $contents_row['provider_req_prod'] == "N") {
        $body .=        "<div class=\"dropdown\" style=\"position: absolute; right: 10px; top: 8px;\">" .
            "<button class=\"btn-link dropdown-toggle westory_dropdown\" type=\"button\" data-toggle=\"dropdown\" style=\"\">" .
            "<img src=\"/iam/img/menu/icon_dot.png\" style=\"height:24px\">" .
            "</button>" .
            "<ul class=\"dropdown-menu comunity\">" .
            "<li><a onclick=\"set_my_share_contents('" . $contents_row['idx'] . "', 'gwc_prod_unset')\">메인몰로 내보내기</a></li>" .
            "</ul>" .
            "</div>";
    }
    if ($_SESSION['iam_member_id'] == "") {
        $body .=        "<div class=\"dropdown \" style=\"position: absolute; right: 10px; top: 8px;\">" .
            "<button class=\"btn-link dropdown-toggle westory_dropdown\" type=\"button\" data-toggle=\"dropdown\" style=\"\">" .
            "<img src=\"/iam/img/menu/icon_dot.png\" style=\"height:24px\">" .
            "</button>" .
            "<ul class=\"dropdown-menu comunity\">" .
            "<li><a onclick=\"javascript:location.href='/iam/join.php'\">나도 아이엠 만들고 싶어요</a></li>" .
            "<li><a onclick=\"javascript:window.open('/?" . strip_tags($contents_row['westory_card_url'] . $card_owner_code) . "');\">이 콘텐츠 게시자 보기</a></li>" .
            "<li><a onclick=\"javascript:window.open('/?" . $contents_row['westory_card_url'] . $card_owner_code . "&cur_win=my_story')\" >더 많은 콘텐츠 보러가기</a></li>" .
            "<li><a onclick=\"set_report_contents('" . $contents_row['idx'] . "')\">이 콘텐츠 신고하기</a></li>" .
            "</ul>" .
            "</div>";
    }
    $body .=    "</div>";
    $body .= "<div class=\"desc-wrap\" >";
    if ($contents_row['contents_title'] != "") {
        $body .=    "<div class=\"title service_title\">" .
            $contents_row['contents_title'] .
            "</div>";
    }
    if ((int)$contents_row['contents_type'] == 3) {
        $price_show1 = "적립금";
        $price_show2 = "판매가";
        $price_style = "";
        $sql_card = "select sale_cnt, sale_cnt_set, add_reduce_val from Gn_Iam_Name_Card where idx={$contents_row['card_idx']}";
        $res_card = mysqli_query($self_con, $sql_card);
        $row_card = mysqli_fetch_array($res_card);
        $body .=    "<div class=\"desc is-product\">" .
            "<div class=\"desc-inner\">" .
            "<div class=\"outer " . $row_card['sale_cnt_set'] . "\">";
        $buy_btn = "";
        if ($contents_row['contents_price'] > 0) {
            if (!$row_card['sale_cnt_set']) {
                $style_decor = "";
                $state_end = "";
                $discount = 100 - ($contents_row['contents_sell_price'] / $contents_row['contents_price']) * 100;
                $add_point = $contents_row['contents_price'] * ((int)$discount / 100);
            } else {
                if (!$row_card['sale_cnt']) {
                    $style_decor = "text-decoration: line-through;";
                    $state_end = "마감";
                    $discount = 100 - ($contents_row['contents_sell_price'] / $contents_row['contents_price']) * 100;
                    $add_point = $contents_row['contents_price'] * (((int)$discount - $row_card['add_reduce_val']) / 100);
                } else {
                    $style_decor = "";
                    $state_end = "적립";
                    $discount = 100 - ($contents_row['contents_sell_price'] / $contents_row['contents_price']) * 100;
                    $add_point = $contents_row['contents_price'] * ((int)$discount / 100);
                }
            }
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
            if (($_GET['iamstore'] == "Y" && $_GET['gwc_pay_mem']) || $_GET['iamstore'] == "C") {
                $buy_btn_con = "visibility: hidden;";
                $discount = 100 - ($contents_row['send_provide_price'] / $contents_row['contents_sell_price']) * 100;
            }
            $body .=                "<div class=\"price\" style=\"width:230px;\">" .
                "<span class=\"downer\">" . $price_show2 . ":<span style=\"vertical-align: top;" . $price_style . "\">" . number_format($contents_row['contents_price']) . "</span>원</span>";
            if (($_GET['iamstore'] == "Y" && $_GET['gwc_pay_mem']) || $_GET['iamstore'] == "C") {
                $body .=                    "<span class=\"downer\" style=\"color:red;\">" . $price_show1 . ":" . number_format((int)$add_point) . "원</span>" .
                    "<span class=\"downer\" style=\"color:blue;\">공급가:" . number_format($contents_row['send_provide_price']) . "원 <span style=\"vertical-align: top;color: red;margin-left: 10px;font-weight: bold;\">" . (int)$discount . "%</span></span>";
            } else {
                $body .=                    "<span class=\"downer\" style=\"color:red;\">" . $price_show1 . ":" . number_format((int)$add_point) . "원 <span style=\"vertical-align: top;color: red;margin-left: 10px;font-weight: bold;\">" . (int)$discount . "%</span></span>";
            }
            $body .=                "</div>" .
                "<span style=\"font-size: 15px;\">" . $state_end . "</span>";
        } else {
            $body .=                "<div class=\"price\">" .
                "<span class=\"downer\">" . $price_show2 . ":<span style=\"vertical-align: top;" . $price_style . "\">" . number_format($contents_row['contents_price']) . "</span>원 <span style=\"vertical-align: top;color: red;margin-left: 10px;font-weight: bold;\">0%</span></span>" .
                "<span class=\"downer\" style=\"color:red;\">" . $price_show1 . ":" . number_format($contents_row['contents_price']) . "원</span>" .
                "</div>";
        }
        if ($_SESSION['iam_member_id'] == 'iamstore') {
            $buy_btn = "display:none;";
        }
        $body .=                "<div class=\"order\" style=\"" . $buy_btn . $buy_btn_con . "\">";
        if ($_SESSION['iam_member_id']) {
            $price_service = $contents_row['contents_sell_price'];
            $contents_row['contents_title'] = str_replace('"', ' ', $contents_row['contents_title']);
            $contents_row['contents_title'] = str_replace("'", ' ', $contents_row['contents_title']);
            $name_service = $contents_row['contents_title'];
            $sellerid_service = $contents_row['mem_id'];
            $contents_url = $contents_row['contents_url'];
            $card_price = $contents_row['contents_sell_price'] * 1 + $contents_row['send_salary_price'] * 1;
            $pay_link = "/iam/pay_spgd.php?item_name=" . $contents_row['contents_title'] . '&item_price=' . $card_price . '&manager=' . $contents_row['mem_id'] . "&conidx=" . $contents_row['idx'] . "&sale_cnt=" . $row_card['sale_cnt'];
            $body .=                    "<div class=\"dropdown\" style=\"float:right;width: 82px;\">" .
                "<a class = \"dropdown-toggle\" data-toggle=\"dropdown\" expanded = \"false\" style=\"background:#99cc00;border-radius:10px;cursor:pointer;\">구매</a>" .
                "<ul class=\"dropdown-menu buy_servicecon\" style=\"width: 82px;\">" .
                "<li>" .
                "<a href=\"" . $pay_link . "\" target=\"_blank\" style=\"font-size: 12px;background-color:#99cc00;\">카드결제</a>" .
                "</li>" .
                "<li>";
            $salary_price = $contents_row['send_salary_price'] ? $contents_row['send_salary_price'] : 0;
            $sale_cnt = $row_card['sale_cnt'] ? $row_card['sale_cnt'] : 0;
            $body .=                                "<a onclick=\"point_settle_modal(" . $contents_row['contents_sell_price'] . ", '" . $contents_row['contents_title'] . "', '" . $contents_row['idx'] . "', '" . $contents_row['mem_id'] . "', '" . $sale_cnt . "', '" . $salary_price . "')\" style=\"font-size: 12px;background-color:#99cc00;\">포인트결제</a>" .
                "</li>" .
                "</ul>" .
                "</div>";
        } else {
            $body .=                    "<a href=\"/iam/login.php?contents_idx=" . $contents_row['idx'] . "\" target=\"_self\" style=\"background:#99cc00;border-radius:10px;\">구매</a>";
        }
        $body .=                "</div>" .
            "</div>" .
            "</div>" .
            "</div>";
    }
    $body .= "</div>";
    if (count($content_images) > 0 || $contents_row['contents_url'] != "") {
        $body .= "<div class=\"media-wrap\" >";
        $except_keyword = $contents_row['except_keyword'];
        $except_keyword = str_replace('"', urlencode('"'), $except_keyword);
        $except_keyword = str_replace("'", urlencode("\'"), $except_keyword);
        $height_prop = "";
        if ($contents_row['contents_type'] == 1 && count($content_images) == 0)
            $height_prop = "min-height :30px;";
        $body .=    "<div class=\"media-inner " . $_GET['type'] . "\" style=\"overflow-y: hidden;" . $height_prop . "\">";
        if ($content_images != null) {
            if ($cur_card['share_send_card'] != 0 || $_SESSION['iam_member_id'] != $contents_row['mem_id']) {
                $body .=        "<a class=\"content-utils\" style=\"cursor:pointer;position:absolute;top:14px;right:10px;z-index:10;\"" .
                    "onclick=\"showSNSModal_byContents('" . $contents_row['idx'] . "', '" . $contents_row['gwc_con_state'] . "');\">" .
                    "<img src=\"/iam/img/menu/icon_utils_share.png\"  >" .
                    "</a>";
            } else {
                $body .=        "<a onclick=\"show_contents_utils(" . $contents_row['idx'] . ");\" class=\"content-utils\"" .
                    "style=\"position: absolute;top:14px;right: 10px;z-index:15;\" title=\"\">" .
                    "<img src = \"/iam/img/menu/icon_utils_box.png\" >" .
                    "</a>" .
                    "<div class = \"utils-index\" id=\"utils_index_" . $contents_row['idx'] . "\" style=\"display:none;flex-direction: column;z-index:10;top:14px\">" .
                    "<div>";
                if ($_SESSION['iam_member_id'] == $_GET['card_owner'] && $_SESSION['iam_member_id'] == $_GET['card_master']) {
                    $body .=                "<a class=\"content-utils\" onclick=\"contents_add('" . $_GET['card_owner'] . "','" . $contents_row['contents_order'] . "'," . $_GET['my_first_card'] . ");\" >" .
                        "<img src=\"/iam/img/menu/icon_utils_add.png\">" .
                        "</a>";
                }
                if ($_SESSION['iam_member_id'] == $contents_row['mem_id'] && $_GET['is_pay_version']) {
                    $body .=                "<a class=\"content-utils\" onclick=\"show_con_send('" . $contents_row['idx'] . "')\">" .
                        "<img src=\"/iam/img/menu/icon_utils_send.png\"  >" .
                        "</a>";
                }
                $body .=                "<a class=\"content-utils\" onclick=\"showSNSModal_byContents('" . $contents_row['idx'] . "', '" . $contents_row['gwc_con_state'] . "');\" >" .
                    "<img src=\"/iam/img/menu/icon_utils_share.png\"  >" .
                    "</a>";
                $body .=                "<a class=\"content-utils\" onclick=\"contents_edit('" . $contents_row['idx'] . "', '" . $contents_row['ori_store_prod_idx'] . "');\">" .
                    "<img src=\"/iam/img/menu/icon_utils_edit.png\" >" .
                    "</a>";
                $body .=            "</div>" .
                    "<div style=\"margin-top:7px;\">";
                if ($_SESSION['iam_member_id'] == $contents_row['mem_id']) {
                    $body .=                "<a class=\"content-utils\" onclick=\"contents_del('" . $contents_row['idx'] . "','" . $contents_row['ori_store_prod_idx'] . "','" . $contents_row['product_seperate'] . "','" . $contents_row['gwc_con_state'] . "');\">" .
                        "<img src=\"/iam/img/menu/icon_utils_del.png\"  >" .
                        "</a>" .
                        "<a class=\"content-utils\" onclick=\"contents_range_up('" . $contents_row['idx'] . "','" . $contents_row['contents_order'] . "','" . $cur_card['card_short_url'] . "','" . $contents_row['product_seperate'] . "','" . $contents_row['gwc_con_state'] . "');\">" .
                        "<img src=\"/iam/img/menu/icon_utils_up.png\"  >" .
                        "</a>" .
                        "<a class=\"content-utils\" onclick=\"contents_range_down('" . $contents_row['idx'] . "','" . $contents_row['contents_order'] . "','" . $cur_card['card_short_url'] . "','" . $contents_row['product_seperate'] . "','" . $contents_row['gwc_con_state'] . "');\">" .
                        "<img src=\"/iam/img/menu/icon_utils_down.png\"  >" .
                        "</a>" .
                        "<a class=\"content-utils\" onclick=\"contents_range_up('" . $contents_row['idx'] . "','" . $contents_row['contents_order'] . "','" . $cur_card['card_short_url'] . "','" . $contents_row['product_seperate'] . "','" . $contents_row['gwc_con_state'] . "','max');\">" .
                        "<img src=\"/iam/img/menu/icon_utils_start.png\"  >" .
                        "</a>" .
                        "<a class=\"content-utils\" onclick=\"contents_range_down('" . $contents_row['idx'] . "','" . $contents_row['contents_order'] . "','" . $cur_card['card_short_url'] . "','" . $contents_row['product_seperate'] . "','" . $contents_row['gwc_con_state'] . "','min');\">" .
                        "<img src=\"/iam/img/menu/icon_utils_end.png\"  >" .
                        "</a>";
                }
                $body .=            "</div>" .
                    "</div>";
            }
        }
        if ((int)$contents_row['contents_type'] == 1 || (int)$contents_row['contents_type'] == 3) {
            if (count($content_images) > 1 && $contents_row['landing_mode'] == "N" && !$contents_row['gwc_con_state']) {
                $body .=            "<button onclick=\"show_all_content_images('" . $contents_row['idx'] . "')\"  id = \"content_all_image" . $contents_row['idx'] . "\"" .
                    "style=\"position: absolute;right:16px;bottom:13px;opacity: 90%;background: black;color: white;width:50px;height:27px;border-radius:20px;font-size:15px\">" .
                    "+" . (count($content_images) - 1) .
                    "</button>" .
                    "<button onclick=\"hide_all_content_images('" . $contents_row['idx'] . "')\"  id = \"hide_content_all_image" . $contents_row['idx'] . "\"" .
                    "style=\"position: absolute;left:0px;top:300px;font-size: 14px;display:none;background: transparent\">" .
                    "<img src=\"/iam/img/main/icon-img_fold.png\" style=\"width:30px\">" .
                    "</button>";
            }
            $open_state = 0;
            if ($_GET['open_domain'] != '') {
                $filter = explode(",", trim($_GET['open_domain']));
                for ($j = 0; $j < count($filter); $j++) {
                    $str = trim($filter[$j]);
                    if (strpos($contents_row['contents_url'], $str) !== false) {
                        $open_state = 1;
                    }
                }
            }
            if ($open_state) {
                $body .=            "<a href='" . $contents_row['contents_url'] . "' data=\"01\" target=\"_blank\" id=\"pagewrap" . $contents_row['idx'] . "\">";
                if (count($content_images) > 0) {
                    $body .=                "<img src=\"" . cross_image($content_images[0]) . "\" class=\"contents_img\">";
                }
                if (count($content_images) > 1) {
                    for ($c = 1; $c < count($content_images); $c++) {
                        $body .=                    "<img src=\"" . cross_image($content_images[$c]) . "\" class=\"contents_img hidden_image" . $contents_row['idx'] . "\" style=\"display:none\">";
                    }
                }
                $body .=            "</a>";
            } else if ((int)$contents_row['open_type'] == 1) { //내부열기
                if ($contents_row['media_type'] == "I") {
                    if (count($content_images) > 0 && strstr($contents_row['contents_url'], ".mp4") === false) {
                        $body .=                    "<div class=\"gwc_con_img\" data=\"02\" onclick=\"showpage" . $contents_row['idx'] . "('" . $contents_row['contents_url'] . "','" . $contents_row['landing_mode'] . "','" . $contents_row['gwc_con_state'] . "')\" id=\"pagewrap" . $contents_row['idx'] . "\">" .
                            "<img src=\"" . cross_image($content_images[0]) . "\" class=\"contents_img\">";
                        if (!$contents_row['gwc_con_state']) {
                            if ($contents_row['landing_mode'] == "Y")
                                $hide_img_state = "";
                            else
                                $hide_img_state = "display:none";
                            for ($c = 1; $c < count($content_images); $c++) {
                                $body .=                                "<img src=\"" . cross_image($content_images[$c]) . "\" class=\"contents_img hidden_image" . $contents_row['idx'] . "\" style=\"" . $hide_img_state . "\">";
                            }
                        }
                        $body .=                    "</div>";
                    } else if ($contents_row['contents_url'] != "") {
                        if (strpos($contents_row['contents_url'], 'http://') !== false)
                            $cont_url = $cross_page . urlencode($contents_row['contents_url']);
                        else
                            $cont_url = $contents_row['contents_url'];
                        $body .=                    "<div class=\"gwc_con_img\">" .
                            "<iframe src=\"" . $cont_url . "\" width=\"100%\" height=\"600px\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-forms allow-top-navigation\"></iframe>" .
                            "</div>";
                    }
                } else {
                    $body .=                "<div class=\"gwc_con_img\" style=\"position: relative\">" .
                        "<a style=\"position: absolute;top: 20px;left: 20px;background: #f3f3f3;display: flex;padding:5px;padding-right:15px;z-index:10;font-size:14px;text-decoration-line: none;\" class=\"unmute_btn\">" .
                        "<img src=\"/iam/img/main/mute.png\" style=\"height: 30px\"><p style=\"margin-top: 5px;font-weight:bold;\">탭하여 음소거 해제</p>" .
                        "</a>" .
                        "<video src=\"" . $contents_row['contents_img'] . "\" type=\"video/mp4\" autoplay loop muted playsinline preload style=\"width:100%;\" autoplay></video>" .
                        "<img src=\"/iam/img/movie_play.png\" style=\"display:none;width:70px;\" class=\"movie_play\">" .
                        "</div>";
                }
            } else { //외부열기
                if ($contents_row['contents_url'] != "") {
                    $body .=                "<a href='" . $contents_row['contents_url'] . "' target=\"_blank\" id=\"pagewrap" . $contents_row['idx'] . "\">";
                }
                if (count($content_images) > 0) {
                    $body .=                    "<img src=\"" . cross_image($content_images[0]) . "\" class=\"contents_img\">";
                }
                if (count($content_images) > 1 && !$contents_row['gwc_con_state']) {
                    for ($c = 1; $c < count($content_images); $c++) {
                        $body .=                    "<img src=\"" . cross_image($content_images[$c]) . "\" class=\"contents_img hidden_image" . $contents_row['idx'] . "\" style=\"" . $landing . "\">";
                    }
                }
                if ($contents_row['contents_url'] != "") {
                    $body .=                "</a>";
                }
            }
            $body .=        "<script type=\"text/javascript\">" .
                "function showpage" . $contents_row['idx'] . "(url, landing, gwc) {";
            $body .=                "if(url != '')" .
                "document.getElementById('pagewrap" . $contents_row['idx'] . "').innerHTML = '<iframe src=\"'+url+'\" width=\"100%\" height=\"600px\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-forms allow-top-navigation\"></iframe>';" .
                "}" .
                "</script>";
        } else if ((int)$contents_row['contents_type'] == 2) {
            $contents_movie = true;
            if (!$contents_row['contents_img']) {
                $contents_movie = false;
                $body .=            $contents_row['contents_iframe'];
            } else {
                if ((int)$contents_row['open_type'] == 1) {
                    $body .=                "<div onclick=\"play" . $contents_row['idx'] . "();\" id=\"vidwrap" . $contents_row['idx'] . "\" style=\"position: relative;\">" .
                        "<img src=\"" . cross_image($content_images[0]) . "\" class=\"contents_img\">";
                    if ($contents_movie) {
                        $body .=                        "<img class=\"movie_play\" src=\"/iam/img/movie_play.png\" >";
                    }
                    if (count($content_images) > 1) {
                        for ($c = 1; $c < count($content_images); $c++) {
                            $body .=                        "<img src=\"" . cross_image($content_images[$c]) . "\" class=\"contents_img hidden_image" . $contents_row['idx'] . "\" style=\"" . $landing . "\">";
                        }
                    }
                    $body .=                "</div>";
                } else {
                    $body .=                "<a href=\"" . $contents_row['contents_url'] . "\" target=\"_blank\" id=\"vidwrap" . $contents_row['idx'] . "\" style=\"position: relative;\">" .
                        "<img src=\"" . cross_image($content_images[0]) . "\" class=\"contents_img\">";
                    if ($contents_movie) {
                        $body .=                    "<img class=\"movie_play\" src=\"/iam/img/movie_play.png\" >";
                    }
                    if (count($content_images) > 1) {
                        for ($c = 1; $c < count($content_images); $c++) {
                            $body .=                            "<img src=\"" . cross_image($content_images[$c]) . "\" class=\"contents_img hidden_image" . $contents_row['idx'] . "\" style=\"" . $landing . "\">";
                        }
                    }
                    $body .=                "</a>";
                }
                $body .=            "<script type=\"text/javascript\">" .
                    "function play" . $contents_row['idx'] . "() {" .
                    "document.getElementById('vidwrap" . $contents_row['idx'] . "').innerHTML = \"" . $contents_row['contents_iframe'] . "\";" .
                    "save_load('" . $_SESSION['iam_member_id'] . "','" . $contents_row['idx'] . "',1);" .
                    "}" .
                    "</script>";
            }
        } else if ((int)$contents_row['contents_type'] == 4) {
            $vid_data = $contents_row['source_iframe'];
            if ((int)$contents_row['open_type'] == 1) {
                $body .=            "<div onclick=\"play" . $contents_row['idx'] . "();\" id=\"vidwrap" . $contents_row['idx'] . "\">";
                if ($content_images[0]) {
                    $body .=                "<img src=\"" . cross_image($content_images[0]) . "\" class=\"contents_img\">" .
                        "<iframe src=\"" . $vid_data . "\" style=\"width:100%;height: 600px;display:none\"></iframe>";
                } else {
                    $body .=                    "<iframe src=\"" . $vid_data . "\" style=\"width:100%;height: 600px;\"></iframe>";
                }
                $body .=            "</div>";
            } else {
                $body .=            "<a href=\"" . $contents_row['contents_url'] . "\" id=\"vidwrap" . $contents_row['idx'] . "\">";
                if ($content_images[0]) {
                    $body .=                "<img src=\"" . cross_image($content_images[0]) . "\" class=\"contents_img\">" .
                        "<iframe src=\"" . $vid_data . "\" style=\"width:100%;height: 600px;display:none\"></iframe>";
                } else {
                    $body .=                    "<iframe src=\"" . $vid_data . "\" style=\"width:100%;height: 600px;\"></iframe>";
                }
                $body .=            "</a>";
            }
            $body .=        "<script type=\"text/javascript\">" .
                "function play" . $contents_row['idx'] . "() {" .
                "$('#vidwrap" . $contents_row['idx'] . " > iframe').css(\"display\",\"block\");" .
                "$('#vidwrap" . $contents_row['idx'] . " > img').css(\"display\",\"none\");" .
                "save_load('" . $_SESSION['iam_member_id'] . "','" . $contents_row['idx'] . "',1);" .
                "}" .
                "</script>";
        }
        $body .=    "</div>" .
            "</div>";
    }
    if ($contents_row['contents_type'] == 1 && count($content_images) == 0)
        $style = "style = 'min-height :30px;border:none;height: 50px;'";
    else
        $style =  "style = 'border:none;'";
    $body .= "<div class=\"info-wrap\" " . $style . ">";
    $host_pos = strpos($contents_row['contents_url'], "//");
    $host_name = substr($contents_row['contents_url'], $host_pos + 2);
    $host_pos = strpos($host_name, "/");
    $host_name = substr($host_name, 0, $host_pos);
    if ($contents_row['contents_url_title'] || $contents_row['contents_url']) {
        $display_gwc_con = "display:flex;justify-content: space-between;";
        $body .=    "<div class=\"media-tit\" style=\"height:30px;background-color: #f5f5f5;padding:7px 16px;" . $display_gwc_con . "\" onclick=\"window.open('" . $contents_row['contents_url'] . "');\">" .
            "<a style='text-decoration:none;cursor:pointer;font-size:12px'>더 알아보기</a>" .
            "</div>";
    }
    if ($westory_card['post_display'] == 0)
        $post_style = "display:none !important;";
    $body .=    "<div class=\"second-box\" style=\"border:none;" . $post_style . "\">";
    /*$body .=      "<div class=\"in-box\">".
                        "<div style=\"display: flex;vertical-align: middle\">".
                            "<a class = \"hand\" href=\"javascript:contents_like('".$contents_row['idx']."','".$_SESSION['iam_member_id']."','". $contents_row['product_seperate'] ."', '". $contents_row['gwc_con_state'] ."');\">";
                                if(in_array($_SESSION['iam_member_id'],explode(",",$contents_row['contents_like']))){ 
    $body .=                    "<img src=\"/iam/img/menu/icon_like_active.png\" width=\"24px\" alt=\"\" id=\"like_img_".$contents_row['idx']."\">";
                                }else{
    $body .=                    "<img src=\"/iam/img/menu/icon_like.png\" width=\"24px\" alt=\"\" id=\"like_img_".$contents_row['idx']."\">";
                                }
    $body .=                "</a>".
                            "<p class = \"second-box-like like-count like_".$contents_row['idx']."\" style=\"font-size:13px\">".
                                number_format(count(explode(",",$contents_row['contents_like'])))."개".
                            "</p>".
                            "&nbsp;&nbsp;&nbsp;".
                            "<a href=\"javascript:show_post('".$contents_row['idx']."');\" class=\"hand\">".
                                "<img id=\"show_post_img_".$contents_row['idx']."\" src=\"/iam/img/menu/icon_post.png\" height=\"24px\" alt=\"\">".
                                "<label style=\"font-size: 10px;background: #ff3333;border-radius: 50%!important;padding: 3px 5px!important;color: #fff;".
                                        "text-align: center;line-height: 1;position: absolute;margin-left: -15px\" id = \"post_alarm_".$contents_row['idx']."\"></label>".
                            "</a>".
                            "<p onclick = \"refresh_post('".$contents_row['idx']."')\" class=\"second-box-like like-count\" id=\"post_count_".$contents_row['idx']."\" style=\"font-size:13px;cursor:pointer;\">".$post_count."  &#x21BA;</p>".
                        "</div>".
                    "</div>";*/
    $body .=    "</div>" .
        "</div>";

    $body .= "<div class=\"desc-wrap\" style=\"padding:5px 6px;\">" .
        "<div class=\"desc\" >" .
        "<div class=\"desc-inner desc-text desc-inner-content\">" ;
        if(mb_strlen($contents_row['contents_desc'],"UTF-8") > 50){
            $body .= nl2br(mb_substr($contents_row['contents_desc'],0,50,"UTF-8")).
                    "<font class=\"ellipsis moreCon\" style=\"color: #669933;cursor: pointer;\" data-idx=\"".$contents_row['idx']."\" onclick=\"showMoreDetail($(this));\">더 보기</font>";
        }else{
            $body .= nl2br($contents_row['contents_desc']);
        }
    $body .=  "</div>";
    if ($content_images == null) {
        if ($cur_card['share_send_card'] != 0 || $_SESSION['iam_member_id'] != $contents_row['mem_id']) {
            $body .=            "<a class=\"content-utils\" style=\"cursor:pointer;position:absolute;top:5px;right:10px;z-index:10;\"" .
                "onclick=\"showSNSModal_byContents('" . $contents_row['idx'] . "','" . $contents_row['gwc_con_state'] . "');\">" .
                "<img src=\"/iam/img/menu/icon_utils_share.png\"  >" .
                "</a>";
        } else {
            $body .=            "<a onclick=\"show_contents_utils(" . $contents_row['idx'] . ");\" class=\"content-utils\"" .
                "style=\"position: absolute;top:5px;right: 10px;z-index:15;\" title=\"\">" .
                "<img src = \"/iam/img/menu/icon_utils_box.png\" >" .
                "</a>" .
                "<div class = \"utils-index\" id=\"utils_index_" . $contents_row['idx'] . "\" style=\"display:none;flex-direction: column;z-index:10;top:5px\">" .
                "<div>";
            if ($_SESSION['iam_member_id'] == $_GET['card_owner'] && $_SESSION['iam_member_id'] == $_GET['card_master']) {
                $body .=                    "<a class=\"content-utils\" onclick=\"contents_add('" . $_GET['card_owner'] . "','" . $contents_row['contents_order'] . "'," . $_GET['my_first_card'] . ");\" >" .
                    "<img src=\"/iam/img/menu/icon_utils_add.png\"  >" .
                    "</a>";
            }
            if ($_SESSION['iam_member_id'] == $contents_row['mem_id'] && $_GET['is_pay_version']) {
                $body .=                    "<a class=\"content-utils\" onclick=\"show_con_send('" . $contents_row['idx'] . "')\">" .
                    "<img src=\"/iam/img/menu/icon_utils_send.png\"  >" .
                    "</a>";
            }
            $body .=                    "<a class=\"content-utils\" onclick=\"showSNSModal_byContents('" . $contents_row['idx'] . "','" . $contents_row['gwc_con_state'] . "');\" >" .
                "<img src=\"/iam/img/menu/icon_utils_share.png\"  >" .
                "</a>";
            if ($_SESSION['iam_member_id'] == $contents_row['mem_id']) {
                $body .=                    "<a class=\"content-utils\" onclick=\"contents_edit('" . $contents_row['idx'] . "','" . $contents_row['ori_store_prod_idx'] . "');\">" .
                    "<img src=\"/iam/img/menu/icon_utils_edit.png\" >" .
                    "</a>";
            }
            $body .=                "</div>" .
                "<div style=\"margin-top:7px;\">";
            if ($_SESSION['iam_member_id'] == $contents_row['mem_id']) {
                $body .=                    "<a class=\"content-utils\" onclick=\"contents_del('" . $contents_row['idx'] . "','" . $contents_row['ori_store_prod_idx'] . "','" . $contents_row['product_seperate'] . "', '" . $contents_row['gwc_con_state'] . "');\">" .
                    "<img src=\"/iam/img/menu/icon_utils_del.png\"  >" .
                    "</a>" .
                    "<a class=\"content-utils\" onclick=\"contents_range_up('" . $contents_row['idx'] . "', '" . $contents_row['contents_order'] . "','" . $cur_card['card_short_url'] . "', '" . $contents_row['product_seperate'] . "', '" . $contents_row['gwc_con_state'] . "');\">" .
                    "<img src=\"/iam/img/menu/icon_utils_up.png\"  >" .
                    "</a>" .
                    "<a class=\"content-utils\" onclick=\"contents_range_down('" . $contents_row['idx'] . "', '" . $contents_row['contents_order'] . "','" . $cur_card['card_short_url'] . "', '" . $contents_row['product_seperate'] . "', '" . $contents_row['gwc_con_state'] . "');\">" .
                    "<img src=\"/iam/img/menu/icon_utils_down.png\"  >" .
                    "</a>" .
                    "<a class=\"content-utils\" onclick=\"contents_range_up('" . $contents_row['idx'] . "', '" . $contents_row['contents_order'] . "','" . $cur_card['card_short_url'] . "', '" . $contents_row['product_seperate'] . "', '" . $contents_row['gwc_con_state'] . "','max');\">" .
                    "<img src=\"/iam/img/menu/icon_utils_start.png\"  >" .
                    "</a>" .
                    "<a class=\"content-utils\" onclick=\"contents_range_down('" . $contents_row['idx'] . "', '" . $contents_row['contents_order'] . "','" . $cur_card['card_short_url'] . "', '" . $contents_row['product_seperate'] . "', '" . $contents_row['gwc_con_state'] . "','min');\">" .
                    "<img src=\"/iam/img/menu/icon_utils_end.png\"  >" .
                    "</a>";
            }
            $body .=                "</div>" .
                "</div>";
        }
    }
    $body .=   "</div>" .
        "</div>";
    
    $post_status_sql = "select count(*) from Gn_Iam_Post where content_idx = '{$contents_row['idx']}' and status = 'N' and lock_status = 'N'";
    $post_status_res = mysqli_query($self_con, $post_status_sql);
    $post_status_row =  mysqli_fetch_array($post_status_res);
    $post_status_count = $post_status_row[0];
    if ($post_status_count  > 0)
        $script =  "<script>  $('#post_alarm_" . $contents_row['idx'] . "').html(" . $post_status_count . "); </script>";
    else
        $script =  "<script>  $('#post_alarm_" . $contents_row['idx'] . "').hide(); </script>";
    $body .=   $script;
    $body .= "<div class=\"post-wrap post_wrap" . $contents_row['idx'] . "\" style=\"display:none\" id = \"post_wrap" . $contents_row['idx'] . "\">" .
        "<div style=\"display: flex;justify-content: flex-end;\">" .
        "<div style=\"margin-left:30px;margin-right:15px;width:100%\">" .
        "<textarea id = \"post_content" . $contents_row['idx'] . "\" name = \"post_content" . $contents_row['idx'] . "\" class=\"post_content\" maxlength=\"300\" style=\"font-size:14px;width:100%;height:35px;border: 1px;\" placeholder=\"댓글은 300자 이내로 작성해주세요\" ></textarea>" .
        "</div>" .
        "<div>" .
        "<button type=\"button\" class=\"btn btn-link\" style=\"font-size:14px;padding: 5px 12px;color:#99cc00\" id=\"send_post\" onclick=\"add_post('" . $contents_row['idx'] . "')\">작성</button>" .
        "</div>" .
        "</div>" .
        "<div style=\"margin-left:30px;\">" .
        "<span id = \"post_status\" name = \"post_status\" style=\"padding: 10px;font-size:10px\">0/300</span>" .
        "</div>" .
        "<div style=\"border: 0px solid #dddddd;margin-left:30px;\" id = \"post_list_" . $contents_row['idx'] . "\" name = \"post_list_" . $contents_row['idx'] . "\">";
    while ($post_row = mysqli_fetch_array($post_res)) {
        $sql_mem_p = "select profile, mem_name,  from Gn_Member where mem_id='{$post_row['mem_id']}'";
        $res_mem_p = mysqli_query($self_con, $sql_mem_p);
        $row_mem_p = mysqli_fetch_array($res_mem_p);

        $post_card_sql = "select card_short_url from Gn_Iam_Name_Card where group_id = 0 and mem_id = '{$post_row['mem_id']}' order by req_data asc";
        $post_card_result = mysqli_query($self_con, $post_card_sql);
        $post_card_row = mysqli_fetch_array($post_card_result);

        $body .=        "<div class=\"user-item\" id=\"post_reply" . $post_row['id'] . "\">" .
            "<a href=\"/?" . strip_tags($post_card_row['card_short_url']) . "\" class=\"img-box\">" .
            "<div class=\"user-img\" style=\"margin: 5px;width:32px;height:32px;\">";
        if ($row_mem_p['profile']) {
            $body .=                        "<img src=\"" . $row_mem_p['profile'] . "\" alt=\"\">";
        } else {
            $body .=                        "<img src=\"/iam/img/profile_img.png\" alt=\"\">";
        }
        $body .=                "</div>" .
            "</a>" .
            "<div class=\"wrap\" style=\"margin:10px 0px;\">" .
            "<span class=\"date\">" .
            $row_mem_p['mem_name'] . $post_row['reg_date'] .
            "</span>" .
            "<span class=\"user-name\">";
        if ($post_row['type']) {
            $body .=                        $post_row['title'] . "<br>";
        }
        $body .=                    str_replace("\n", "<br>", $post_row['content']) .
            "</span>" .
            "</div>";
        if ($_SESSION['iam_member_id'] == $post_row['mem_id']) {
            if ($post_row['type'] == 0) {
                $body .=                "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px;\">" .
                    "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">" .
                    "<ul class=\"dropdown-menu namecard-dropdown \" style=\"background: white; color : black;top:10px;\">" .
                    "<li>" .
                    "<a onclick=\"edit_post('" . $contents_row['idx'] . "','" . $post_row['id'] . "','" . $post_row['content'] . "')\" title=\"댓글 수정\">" .
                    "<p>수정</p>" .
                    "</a>" .
                    "</li>" .
                    "<li>" .
                    "<a onclick=\"delete_post('" . $contents_row['idx'] . "','" . $post_row['id'] . "')\" title=\"댓글 삭제\">" .
                    "<p>삭제</p>" .
                    "</a>" .
                    "</li>" .
                    "</ul>" .
                    "</div>";
            }
        } else if ($_SESSION['iam_member_id'] == $contents_row['mem_id']) {
            if ($post_row['type'] == 0) {
                $body .=                "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">" .
                    "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">" .
                    "<ul class=\"dropdown-menu namecard-dropdown \" style=\"background: white; color : black;top:10px;\">" .
                    "<li>" .
                    "<a onclick=\"delete_post('" . $contents_row['idx'] . "','" . $post_row['id'] . "')\" title=\"댓글 삭제\">" .
                    "<p>삭제</p>" .
                    "</a>" .
                    "</li>" .
                    "<li>" .
                    "<a onclick=\"lock_post('" . $contents_row['idx'] . "','" . $post_row['id'] . "')\" title=\"댓글 차단\">" .
                    "<p>차단</p>" .
                    "</a>" .
                    "</li>" .
                    "</ul>" .
                    "</div>";
            }
        }
        if ($post_row['type'] == 0) {
            $body .=            "<div style=\"position: absolute;left: 60px;bottom: 0px\">" .
                "<span style=\"color: #bdbdbd;cursor:pointer;font-size:13px\" onclick=\"show_post_reply(" . $post_row['id'] . ");\">" .
                "답글달기" .
                "</span>" .
                "</div>";
        }
        $body .=        "</div>" .
            "<div id = \"post_reply_" . $post_row['id'] . "\"  class = \"post_reply_wrap\" style=\"display: none;margin : 10px 0px\" >" .
            "<div style=\"display: flex;justify-content: flex-end;\">" .
            "<div style=\"margin-left:60px;margin-right:15px;width:100%\">" .
            "<textarea id=\"post_reply_" . $post_row['id'] . "_content\" name=\"post_reply_" . $post_row['id'] . "_content\" class  = \"post_reply_content\" maxlength=\"300\" placeholder=\"답글은 300자 이내로 작성해주세요\" style=\"font-size:14px;height:35px;width: 100%;border: 1px;\"></textarea>" .
            "</div>" .
            "<div>" .
            "<button type=\"button\" class=\"btn btn-link\" style=\"font-size:14px;padding: 5px 12px;color:#99cc00\" onclick=\"add_post_reply('" . $contents_row['idx'] . "','" . $post_row['id'] . "')\">작성</button>" .
            "</div>" .
            "</div>" .
            "<div style=\"border-bottom: 0px solid #dddddd;margin-left:60px\">" .
            "<span id = \"post_reply_status\" name = \"post_reply_status\" style=\"padding: 10px\">0/300</span>" .
            "</div>" .
            "</div>";

        $reply_sql = "select * from Gn_Iam_Post_Response r where r.post_idx = '{$post_row['id']}' order by r.reg_date";
        $reply_res = mysqli_query($self_con, $reply_sql);
        while ($reply_row = mysqli_fetch_array($reply_res)) {
            $sql_mem_pr = "select mem_name, profile from Gn_Member where mem_id='{$reply_row['mem_id']}'";
            $res_mem_pr = mysqli_query($self_con, $sql_mem_pr);
            $row_mem_pr = mysqli_fetch_array($res_mem_pr);

            $reply_card_sql = "select card_short_url from Gn_Iam_Name_Card where group_id = 0 and mem_id = '{$reply_row['mem_id']}' order by req_data asc";
            $reply_card_result = mysqli_query($self_con, $reply_card_sql);
            $reply_card_row = mysqli_fetch_array($reply_card_result);
            $body .=            "<div class=\"user-item\" style=\"padding-left: 50px\">" .
                "<a href=\"/?" . strip_tags($reply_card_row['card_short_url']) . "\" class=\"img-box\">" .
                "<div class=\"user-img\" style=\"margin: 5px;width:32px;height:32px;\">";
            if ($row_mem_pr['profile']) {
                $body .=                            "<img src=\"" . $row_mem_pr['profile'] . "\" alt=\"\">";
            } else {
                $body .=                            "<img src=\"/iam/img/profile_img.png\" alt=\"\">";
            }
            $body .=                    "</div>" .
                "</a>" .
                "<div class=\"wrap\">" .
                "<span class=\"date\">" .
                $row_mem_pr['mem_name'] . $reply_row['reg_date'] .
                "</span>" .
                "<span class=\"user-name\" id = \"reply_list_" . $reply_row['id'] . "\">" .
                str_replace("\n", "<br>", $reply_row['contents']) .
                "</span>" .
                "</div>";
            if ($_SESSION['iam_member_id'] == $post_row['mem_id']) {
                if ($reply_row['type'] == 0) {
                    $body .=                    "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">" .
                        "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">" .
                        "<ul class=\"dropdown-menu namecard-dropdown \" style=\"background: white; color : black;top:10px;\">" .
                        "<li>" .
                        "<a onclick=\"edit_post_reply('" . $contents_row['idx'] . "','" . $post_row['id'] . "','" . $reply_row['id'] . "','" . $reply_row['contents'] . "')\" title=\"답글 수정\">" .
                        "<p>수정</p>" .
                        "</a>" .
                        "</li>" .
                        "<li>" .
                        "<a onclick=\"delete_post_reply('" . $contents_row['idx'] . "','" . $reply_row['id'] . "')\" title=\"답글 삭제\">" .
                        "<p>삭제</p>" .
                        "</a>" .
                        "</li>" .
                        "</ul>" .
                        "</div>";
                }
            } else if ($_SESSION['iam_member_id'] == $contents_row['mem_id']) {
                if ($reply_row['type'] == 0) {
                    $body .=                    "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">" .
                        "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">" .
                        "<ul class=\"dropdown-menu namecard-dropdown \" style=\"background: white; color : black;top:10px;\">" .
                        "<li>" .
                        "<a onclick=\"delete_post_reply('" . $contents_row['idx'] . "','" . $reply_row['id'] . "')\" title=\"답글 삭제\">" .
                        "<p>삭제</p>" .
                        "</a>" .
                        "</li>" .
                        "<li>" .
                        "<a href=\"javascript:void(0)\" onclick=\"lock_post_reply('" . $contents_row['idx'] . "','" . $reply_row['id'] . "')\" title=\"답글 차단\">" .
                        "<p>차단</p>" .
                        "</a>" .
                        "</li>" .
                        "</ul>" .
                        "</div>";
                }
            }
            $body .=                "</div>";
        }
    }
    $body .=    "</div>" .
        "</div>" .
        "</div>" .
        "</div>";
}
$logs->add_log("end sample");
$logs->write_to_file();
echo $body;
?>
