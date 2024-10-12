
<?
include_once "../../lib/rlatjd_fun.php";
$limit = (intval($_GET['limit']) != 0 ) ? $_GET['limit'] : 4;
$offset = (intval($_GET['offset']) != 0 ) ? $_GET['offset'] : 0;
$contents_mode = $_GET['contents_mode'];
$contents_count_per_page = $limit;
$w_offset = $offset;
if($w_offset < 0 ){
    $w_offset = 0;
}

//added by amigo middle log 로그부분이므로 삭제하지 말것!!!!
$logs = new Logs("../../iamlog.txt", false);

$member_iam = $_GET['member_iam'];

if($cur_win != "iam_mall")
{
    echo '';
    exit;
}
if($_GET['search_key']){
    $search_title = $search_seperate = '';
    if(strpos($search_key, '|') !== false){
        $gwc_search_key_arr = explode('|', $search_key);
        for($i = 0; $i < count($gwc_search_key_arr); $i++){
            if($i == count($gwc_search_key_arr) - 1){
                $and_str = '';
            }
            else{
                $and_str = ' or ';
            }
            $gwc_search_key = trim($gwc_search_key_arr[$i]);
            $search_title .= "contents_title like '%$gwc_search_key%'".$and_str;
            $search_seperate .= "product_seperate like '%$gwc_search_key%'".$and_str;
        }
        $search_sql = "((".$search_title.") or (".$search_seperate.") ";
    }else if(strpos($search_key, ' ') !== false){
        $gwc_search_key_arr = explode(' ', $search_key);
        for($i = 0; $i < count($gwc_search_key_arr); $i++){
            if($i == count($gwc_search_key_arr) - 1){
                $and_str = '';
            }
            else{
                $and_str = ' and ';
            }
            $gwc_search_key = trim($gwc_search_key_arr[$i]);
            $search_title .= "contents_title like '%$gwc_search_key%'".$and_str;
            $search_seperate .= "product_seperate like '%$gwc_search_key%'".$and_str;
        }
        $search_sql = "((".$search_title.") or (".$search_seperate.") ";
    }else{
        $search_sql = "((contents_title like '%".$search_key."%') or (product_seperate like '%".$search_key."%') ";
    }
    $search_sql.= ") ";
}else{
    $search_sql = "1=1";
}
$public_str = "public_display = 'Y'";
if($_GET['cate_prod'] != ''){
    if($_GET['iamstore'] == "Y"){
        $search_sql .= " and card_idx='{$_GET['cate_prod']}'";
    }else{
        $search_sql .= " and card_idx='{$_GET['cate_prod']}' and send_provide_price!=0";
    }
    $public_str = "(public_display = 'Y' or public_display='N')";
}
if($_GET['iamstore'] == "Y"){
    $search_sql .= " and mem_id in (".stripslashes($_GET['provider_arr']).") and send_provide_price!=0 and ori_store_prod_idx=0";
}
else if($_GET['iamstore'] == "C"){
    $search_sql .= " and mem_id='{$_SESSION['iam_member_id']}' and ori_store_prod_idx!=0";
}
if($_GET['key4'] == 3){
    $sql8="select * from ".$content_table_name." c where card_idx='934328'";
}else{
    $sql8="select * from Gn_Iam_Contents_Gwc c use index(idx_2) where contents_type = 3 and c.gwc_con_state=1 and contents_westory_display = 'Y' and $public_str and $search_sql ".$_GET['block_contents_sql'].$_GET['block_user_sql'];
}
$sql8 .= " limit $w_offset, $contents_count_per_page ";
$cont_array = array();
if(!$global_is_local){
    $redisCache = new RedisCache();
    $cont_list = $redisCache -> get_query_to_array($sql8);
    for($i=0 ; $i < count($cont_list); $i++){
        array_push($cont_array,$cont_list[$i]);
    }
}else{
    $result8 = mysqli_query($self_con, $sql8) or die(mysqli_error($self_con));
    while( $contents_row = mysqli_fetch_array($result8)){
        array_push($cont_array,$contents_row);
    }
}
//디폴트 아바타
if ($HTTP_HOST != "kiam.kr") //분양사사이트이면
    $sub_domain = "http://" . $HTTP_HOST;
else
    $sub_domain = "http://www.kiam.kr";
$sql = "select main_img1 from Gn_Iam_Service s where sub_domain = '$sub_domain'";
$result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$row=mysqli_fetch_array($result);
$default_avatar =  $row['main_img1'];

$body = '';
foreach($cont_array as $contents_row){
    //westory 카드
    $sql = "select * from Gn_Iam_Name_Card where idx = '{$contents_row['card_idx']}'";
    $result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $cur_card=mysqli_fetch_array($result);
    $sql = "select mem_code from Gn_Member where mem_id = '{$cur_card['mem_id']}'";
    $result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $m_row =mysqli_fetch_array($result);
    
    $m_code = $m_row['mem_code'];
    $contents_user_name = $cur_card['card_name'];
    $contents_card_url = $request_short_url;
    $contents_owner_id = $contents_row['mem_id'];
    $contents_avatar = "";
    if($cur_card['main_img1']){
        $contents_avatar = $cur_card['main_img1'];
    }else{
        $contents_avatar = $default_avatar;
    }
    $contents_avatar =  $contents_avatar?cross_image($contents_avatar):'/iam/img/common/logo-2.png';
    $body .= "<div class=\"content_area\">".
        "<input type=\"hidden\" id = \"contents_display_".$contents_row['idx']."\" value=\"".$contents_row['contents_display']." \">".
        "<input type=\"hidden\" id = \"contents_user_display_".$contents_row['idx']."\" value=\"".$contents_row['contents_user_display']." \">".
        "<input type=\"hidden\" id = \"contents_type_display_".$contents_row['idx']."\" value=\"".$contents_row['contents_type_display'] ." \">".
        "<input type=\"hidden\" id = \"contents_footer_display_".$contents_row['idx']."\" value=\"".$contents_row['contents_footer_display'] ." \">".
        "<input type=\"hidden\" id = \"contents_media_type_".$contents_row['idx']."\" value=\"".$contents_row['media_type'].".\">".
        "<input type=\"hidden\" id = \"contents_type_".$contents_row['idx']."\" value=\"".$contents_row['contents_type'] ." \">".
        "<input type=\"hidden\" id = \"contents_title_".$contents_row['idx']."\" value=\"".$contents_row['contents_title'] ." \">".
        "<input type=\"hidden\" id = \"contents_img_".$contents_row['idx']."\" value=\"".$contents_row['contents_img']." \">".
        "<input type=\"hidden\" id = \"contents_url_".$contents_row['idx']."\" value=\"".$contents_row['contents_url'] ." \">".
        "<input type=\"hidden\" id = \"contents_iframe_".$contents_row['idx']."\" value=\"".str_replace('\"', "'",$contents_row['contents_iframe']) ." \">".
        "<input type=\"hidden\" id = \"contents_price_".$contents_row['idx']."\" value=\"".$contents_row['contents_price'] ." \">".
        "<input type=\"hidden\" id = \"contents_sell_price_".$contents_row['idx']."\" value=\"".$contents_row['contents_sell_price'] ." \">".
        "<input type=\"hidden\" id = \"contents_desc_".$contents_row['idx']."\" value=\"".$contents_row['contents_desc']." \">".
        "<input type=\"hidden\" id = \"except_keyword_".$contents_row['idx']."\" value=\"".$except_keyword." \">".
        "<input type=\"hidden\" id = \"contents_share_text_".$contents_row['idx']."\" value=\"".$contents_row['contents_share_text'] ." \">".
        "<input type=\"hidden\" id = \"share_names_".$contents_row['idx']."\" value=\"".$share_names." \">".
        "<input type=\"hidden\" id = \"contents_westory_display_".$contents_row['idx']."\" value=\"".$contents_row['contents_westory_display'] ." \">".
        "<input type=\"hidden\" id = \"card_short_url_".$contents_row['idx']."\" value=\"".$contents_row['card_short_url'] ." \">".
        "<input type=\"hidden\" id = \"westory_card_url_".$contents_row['idx']."\" value=\"".$contents_row['westory_card_url'] ." \">".
        "<input type=\"hidden\" id = \"req_data_".$contents_row['idx']."\" value=\"".$contents_row['req_data'] ." \">".
        "<input type=\"hidden\" id = \"card_owner_".$contents_row['idx']."\" value=\"".$_GET['card_owner'] ." \">".
        "<input type=\"hidden\" id = \"contents_user_name_".$contents_row['idx']."\" value=\"".$contents_user_name ." \">".
        "<input type=\"hidden\" id = \"post_display_".$contents_row['idx']."\" value=\"".$cur_card['post_display']." \">".
        "<input type=\"hidden\" id = \"contents_like_".$contents_row['idx']."\" value=\"".number_format($contents_row['contents_like'])." \">".
        "<input type=\"hidden\" id = \"post_count_".$contents_row['idx']."\" value=\"".$post_count." \">".
        "<input type=\"hidden\" id = \"open_type_".$contents_row['idx']."\" value=\"".$contents_row['open_type']." \">".
        "<input type=\"hidden\" id = \"gwc_con_state_".$contents_row['idx']."\" value=\"".$contents_row['gwc_con_state']." \">".
        "<input type=\"hidden\" id = \"product_code_".$contents_row['idx']."\" value=\"".$contents_row['product_code']." \">".
        "<input type=\"hidden\" id = \"product_model_name_".$contents_row['idx']."\" value=\"".$contents_row['product_model_name']." \">".
        "<input type=\"hidden\" id = \"product_seperate_".$contents_row['idx']."\" value=\"".$contents_row['product_seperate']." \">".
        "<input type=\"hidden\" id = \"prod_manufact_price_".$contents_row['idx']."\" value=\"".$contents_row['prod_manufact_price']." \">".
        "<input type=\"hidden\" id = \"send_salary_price_".$contents_row['idx']."\" value=\"".$contents_row['send_salary_price']." \">".
        "<input type=\"hidden\" id = \"send_provide_price_".$contents_row['idx']."\" value=\"".$contents_row['send_provide_price']." \">".
        "<input type=\"hidden\" id = \"landing_mode_".$contents_row['idx']."\" value=\"".$contents_row['landing_mode']." \">".
        "<div class=\"content-item\" id=\"contents_image\" >";
    $body .= "<div class=\"user-item\" style=\"padding:4px;\">".
                "<a href=\"/?".strip_tags($contents_row['westory_card_url'].$m_code)."\" class=\"img-box\"  target = \"_blank\">".
                    "<div class=\"user-img\" style=\"margin: 5px;width:32px;height:32px;\">".
                        "<img src=\"".$contents_avatar."\" >".
                    "</div>".
                "</a>".
                "<div class=\"wrap pin_mode\" style=\"display:none;\">".
                    "<a href=\"/?".strip_tags($contents_row['westory_card_url'].$m_code)."\" class=\"user-name\" style=\"\">".
                        $contents_user_name.
                    "</a>";
                    if($_GET['iamstore'] != 'N'){
    $body .=        "<a href=\"/?".strip_tags($contents_row['westory_card_url'].$m_code)."\" class=\"date\" style=\"\">".
                        get_date_time($contents_row['req_data']).
                    "</a>";
                    }
    $body .=    "</div>";
                if( $_SESSION['iam_member_id'] != "" && ($_SESSION['iam_member_id'] != $contents_owner_id || ($_SESSION['iam_member_id'] == $contents_owner_id && $_SESSION['iam_member_id'] != 'iamstore' && $contents_row['provider_req_prod'] == "Y")) ){
    $body .=    "<div class=\"dropdown\" style=\"position: absolute; right: 10px; top: 8px;\">".
                    "<button class=\"btn-link dropdown-toggle westory_dropdown\" type=\"button\" data-toggle=\"dropdown\" style=\"\">".
                        "<img src=\"/iam/img/menu/icon_dot.png\" style=\"height:24px\">".
                    "</button>".
                    "<ul class=\"dropdown-menu comunity\">".
                        "<li><a onclick=\"window.open('/?".strip_tags($contents_row['westory_card_url'])."');\">이 콘텐츠 게시자 보기</a></li>".
                        "<li><a onclick=\"set_friend('".$cur_card['mem_id']."','".$cur_card['card_name']."','".$cur_card['card_short_url']."','".$cur_card['idx']."')\">이 게시자와 프렌즈 하기</a></li>".
                        "<li><a onclick=\"set_block_contents('".$contents_row['idx']."')\">이 콘텐츠 하나만 차단하기</a></li>".
                        "<li><a onclick=\"set_block_user('".$cur_card['mem_id']."','".$cur_card['card_short_url']."')\">이 게시자의 정보 차단하기</a></li>";
                        if($contents_row['ai_map_gmarket'] != 3){
    $body .=                "<li><a onclick=\"set_my_share_contents('".$contents_row['idx']."')\">이 콘텐츠 나에게 가져오기</a></li>";
                        }else if($contents_row['ai_map_gmarket'] == 3 && $_GET['iamstore'] == "Y" && $gwc_mem){
    $body .=                "<li><a onclick=\"set_my_share_contents('".$contents_row['idx']."', 'gwc_prod')\">내 계정으로 가져오기</a></li>";
                        }
    $body .=            "<li><a onclick=\"set_report_contents('".$contents_row['idx']."')\">이 콘텐츠 신고하기</a></li>".
                        "<li><a onclick=\"show_block_list('".$contents_row['idx']."')\">감추기 리스트 보기</a></li>".
                    "</ul>".
                "</div>";
                }
                if( $_SESSION['iam_member_id'] != "" && $_SESSION['iam_member_id'] == $contents_owner_id && $_SESSION['iam_member_id'] != 'iamstore' && $contents_row['ai_map_gmarket'] == 3 && $contents_row['provider_req_prod'] == "N"){
    $body .=    "<div class=\"dropdown\" style=\"position: absolute; right: 10px; top: 8px;\">".
                    "<button class=\"btn-link dropdown-toggle westory_dropdown\" type=\"button\" data-toggle=\"dropdown\" style=\"\">".
                        "<img src=\"/iam/img/menu/icon_dot.png\" style=\"height:24px\">".
                    "</button>".
                    "<ul class=\"dropdown-menu comunity\">".
                        "<li><a onclick=\"set_my_share_contents('".$contents_row['idx']."', 'gwc_prod_unset')\">매인몰로 내보내기</a></li>".
                    "</ul>".
                "</div>";
                }
                if($_SESSION['iam_member_id'] =="" ){
    $body .=    "<div class=\"dropdown \" style=\"position: absolute; right: 10px; top: 8px;\">".
                    "<button class=\"btn-link dropdown-toggle westory_dropdown\" type=\"button\" data-toggle=\"dropdown\" style=\"\">".
                        "<img src=\"/iam/img/menu/icon_dot.png\" style=\"height:24px\">".
                    "</button>".
                    "<ul class=\"dropdown-menu comunity\">".
                        "<li><a onclick=\"javascript:location.href='/iam/join.php'\">나도 아이엠 만들고 싶어요</a></li>".
                        "<li><a onclick=\"javascript:window.open('/?".strip_tags($contents_row['westory_card_url'].$m_code)."');\">이 콘텐츠 게시자 보기</a></li>".
                        "<li><a onclick=\"javascript:window.open('/?".$contents_row['westory_card_url'].$m_code."&cur_win=my_story')\" >더 많은 콘텐츠 보러가기</a></li>".
                        "<li><a onclick=\"set_report_contents('".$contents_row['idx']."')\">이 콘텐츠 신고하기</a></li>".
                    "</ul>".
                "</div>";
                }
    $body .= "</div>";
        if(count($content_images) > 0 || $contents_row['contents_url'] != ""){
    $body .="<div class=\"media-wrap\" >";
            $except_keyword = $contents_row['except_keyword'];
            $except_keyword = str_replace('"', urlencode('"'), $except_keyword);
            $except_keyword = str_replace("'", urlencode("\'"), $except_keyword);
            $height_prop = "";
            if($contents_row['contents_type'] == 1 && count($content_images) == 0) 
            $height_prop = "min-height :30px;";
    $body .=    "<div class=\"media-inner ".$_GET['type']."\" style=\"overflow-y: hidden;".$height_prop."\">";
                if($content_images != null){
                    if($cur_card['share_send_card'] != 0 || $_SESSION['iam_member_id'] != $contents_row['mem_id']){
    $body .=        "<a class=\"content-utils\" style=\"cursor:pointer;position:absolute;top:14px;right:10px;z-index:10;\"". 
                            "onclick=\"showSNSModal_byContents('".$contents_row['idx']."', '".$contents_row['gwc_con_state']."');\">".
                        "<img src=\"/iam/img/menu/icon_utils_share.png\"  >".
                    "</a>";
                    } else{
    $body .=        "<a onclick=\"show_contents_utils(".$contents_row['idx'].");\" class=\"content-utils\"".
                            "style=\"position: absolute;top:14px;right: 10px;z-index:15;\" title=\"\">".
                        "<img src = \"/iam/img/menu/icon_utils_box.png\" >".
                    "</a>".
                    "<div class = \"utils-index\" id=\"utils_index_".$contents_row['idx']."\" style=\"display:none;flex-direction: column;z-index:10;top:14px\">".
                        "<div>";
                            if ($_SESSION['iam_member_id'] == $_GET['card_owner'] && $_SESSION['iam_member_id'] == $_GET['card_master']) { 
    $body .=                "<a class=\"content-utils\" onclick=\"contents_add('".$_GET['card_owner']."','".$contents_row['contents_order']."',".$_GET['my_first_card'].");\" >".
                                "<img src=\"/iam/img/menu/icon_utils_add.png\">".
                            "</a>";
                            }
                            if ($_SESSION['iam_member_id'] == $contents_row['mem_id'] && $_GET['is_pay_version']){
    $body .=                "<a class=\"content-utils\" onclick=\"show_con_send('".$contents_row['idx']."')\">".
                                "<img src=\"/iam/img/menu/icon_utils_send.png\"  >".
                            "</a>";
                            }
    $body .=                "<a class=\"content-utils\" onclick=\"showSNSModal_byContents('".$contents_row['idx']."', '".$contents_row['gwc_con_state']."');\" >".
                                "<img src=\"/iam/img/menu/icon_utils_share.png\"  >".
                            "</a>";
    $body .=                "<a class=\"content-utils\" onclick=\"contents_edit('".$contents_row['idx']."', '".$contents_row['ori_store_prod_idx']."');\">".
                                "<img src=\"/iam/img/menu/icon_utils_edit.png\" >".
                            "</a>";
    $body .=            "</div>".
                        "<div style=\"margin-top:7px;\">";
                        if ($_SESSION['iam_member_id'] == $contents_row['mem_id']) {
    $body .=                "<a class=\"content-utils\" onclick=\"contents_del('".$contents_row['idx']."','".$contents_row['ori_store_prod_idx']."','".$contents_row['product_seperate']."','".$contents_row['gwc_con_state']."');\">".
                                "<img src=\"/iam/img/menu/icon_utils_del.png\"  >".
                            "</a>".
                            "<a class=\"content-utils\" onclick=\"contents_range_up('".$contents_row['idx']."','".$contents_row['contents_order']."','".$cur_card['card_short_url']."','".$contents_row['product_seperate']."','".$contents_row['gwc_con_state']."');\">".
                                "<img src=\"/iam/img/menu/icon_utils_up.png\"  >".
                            "</a>".
                            "<a class=\"content-utils\" onclick=\"contents_range_down('".$contents_row['idx']."','".$contents_row['contents_order']."','".$cur_card['card_short_url']."','".$contents_row['product_seperate']."','".$contents_row['gwc_con_state']."');\">".
                                "<img src=\"/iam/img/menu/icon_utils_down.png\"  >".
                            "</a>".
                            "<a class=\"content-utils\" onclick=\"contents_range_up('".$contents_row['idx']."','".$contents_row['contents_order']."','".$cur_card['card_short_url']."','".$contents_row['product_seperate']."','".$contents_row['gwc_con_state']."','max');\">".
                                "<img src=\"/iam/img/menu/icon_utils_start.png\"  >".
                            "</a>".
                            "<a class=\"content-utils\" onclick=\"contents_range_down('".$contents_row['idx']."','".$contents_row['contents_order']."','".$cur_card['card_short_url']."','".$contents_row['product_seperate']."','".$contents_row['gwc_con_state']."','min');\">".
                                "<img src=\"/iam/img/menu/icon_utils_end.png\"  >".
                            "</a>";
                        }
    $body .=            "</div>".
                    "</div>";
                    }
                }
                if((int)$contents_row['contents_type'] == 1 || (int)$contents_row['contents_type'] == 3 ) {
                    if(count($content_images) > 1 && $contents_row['landing_mode'] == "N" && !$contents_row['gwc_con_state']){
    $body .=            "<button onclick=\"show_all_content_images('".$contents_row['idx']."')\"  id = \"content_all_image".$contents_row['idx']."\"".
                                "style=\"position: absolute;right:16px;bottom:13px;opacity: 90%;background: black;color: white;width:50px;height:27px;border-radius:20px;font-size:15px\">".
                            "+".(count($content_images)-1).
                        "</button>".
                        "<button onclick=\"hide_all_content_images('".$contents_row['idx']."')\"  id = \"hide_content_all_image".$contents_row['idx']."\"".
                                "style=\"position: absolute;left:0px;top:300px;font-size: 14px;display:none;background: transparent\">".
                            "<img src=\"/iam/img/main/icon-img_fold.png\" style=\"width:30px\">".
                        "</button>";
                    }
                    $open_state = 0;
                    if($_GET['open_domain'] != ''){
                        $filter = explode(",", trim($_GET['open_domain']));
                        for ($j=0; $j<count($filter); $j++) {
                            $str = trim($filter[$j]);
                            if(strpos($contents_row['contents_url'], $str) !== false){
                                $open_state = 1;
                            }
                        }
                    }
                    if($open_state){
    $body .=            "<a href='".$contents_row['contents_url']."' data=\"01\" target=\"_blank\" id=\"pagewrap".$contents_row['idx']."\">";
                            if(count($content_images) > 0){
    $body .=                "<img src=\"".cross_image($content_images[0])."\" class=\"contents_img\">";
                            }
                            if(count($content_images) > 1){
                                for($c = 1;$c < count($content_images);$c ++){
    $body .=                    "<img src=\"".cross_image($content_images[$c])."\" class=\"contents_img hidden_image".$contents_row['idx']."\" style=\"display:none\">";
                                }
                            }
    $body .=            "</a>";
                    }else if((int)$contents_row['open_type'] == 1){//내부열기
                        if($contents_row['media_type'] == "I"){
                            if(count($content_images) > 0 && strstr($contents_row['contents_url'],".mp4") === false){
    $body .=                    "<div class=\"gwc_con_img\" data=\"02\" onclick=\"showpage".$contents_row['idx']."('".$contents_row['contents_url']."','".$contents_row['landing_mode']."','".$contents_row['gwc_con_state']."')\" id=\"pagewrap".$contents_row['idx']."\">".
                                    "<img src=\"".cross_image($content_images[0])."\" class=\"contents_img\">";
                                    if(!$contents_row['gwc_con_state']){
                                        if($contents_row['landing_mode'] == "Y")
                                            $hide_img_state = "";
                                        else
                                            $hide_img_state = "display:none";
                                        for($c = 1;$c < count($content_images);$c ++){
    $body .=                                "<img src=\"".cross_image($content_images[$c])."\" class=\"contents_img hidden_image".$contents_row['idx']."\" style=\"".$hide_img_state."\">";
                                        }
                                    }
    $body .=                    "</div>";
                            }else if($contents_row['contents_url'] != ""){
                                if(strpos($contents_row['contents_url'], 'http://') !== false)
                                    $cont_url = $cross_page.urlencode($contents_row['contents_url']);
                                else
                                    $cont_url = $contents_row['contents_url'];
    $body .=                    "<div class=\"gwc_con_img\">".
                                    "<iframe src=\"".$cont_url."\" width=\"100%\" height=\"600px\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-forms allow-top-navigation\"></iframe>".
                                "</div>";
                            }
                        }else{
    $body .=                "<div class=\"gwc_con_img\" style=\"position: relative\">".
                                "<a style=\"position: absolute;top: 20px;left: 20px;background: #f3f3f3;display: flex;padding:5px;padding-right:15px;z-index:10;font-size:14px;text-decoration-line: none;\" class=\"unmute_btn\">".
                                    "<img src=\"/iam/img/main/mute.png\" style=\"height: 30px\"><p style=\"margin-top: 5px;font-weight:bold;\">탭하여 음소거 해제</p>".
                                "</a>".
                                "<video src=\"".$contents_row['contents_img']."\" type=\"video/mp4\" autoplay loop muted playsinline preload style=\"width:100%;\" autoplay></video>".
                                "<img src=\"/iam/img/movie_play.png\" style=\"display:none;width:70px;\" class=\"movie_play\">".
                            "</div>";
                        }
                    } else{//외부열기
                        if($contents_row['contents_url'] != ""){
    $body .=                "<a href='".$contents_row['contents_url']."' target=\"_blank\" id=\"pagewrap".$contents_row['idx']."\">";
                        }
                        if(count($content_images) > 0){
    $body .=                    "<img src=\"".cross_image($content_images[0])."\" class=\"contents_img\">";
                        }
                        if(count($content_images) > 1 && !$contents_row['gwc_con_state']){
                            for($c = 1;$c < count($content_images);$c ++){
    $body .=                    "<img src=\"".cross_image($content_images[$c])."\" class=\"contents_img hidden_image".$contents_row['idx']."\" style=\"".$landing."\">";
                            }
                        }
                        if($contents_row['contents_url'] != ""){
    $body .=                "</a>";
                        }
                    }
    $body .=        "<script type=\"text/javascript\">".
                        "function showpage".$contents_row['idx']."(url, landing, gwc) {";
    $body .=                "if(url != '')".
                                "document.getElementById('pagewrap".$contents_row['idx']."').innerHTML = '<iframe src=\"'+url+'\" width=\"100%\" height=\"600px\" sandbox=\"allow-same-origin allow-scripts allow-popups allow-forms allow-top-navigation\"></iframe>';".
                        "}".
                    "</script>";
                } else if((int)$contents_row['contents_type'] == 2) {
                    $contents_movie = true;
                    if(!$contents_row['contents_img']){
                        $contents_movie = false;
    $body .=            $contents_row['contents_iframe'];
                    }else{
                        if((int)$contents_row['open_type'] == 1){
    $body .=                "<div onclick=\"play".$contents_row['idx']."();\" id=\"vidwrap".$contents_row['idx']."\" style=\"position: relative;\">".
                                "<img src=\"".cross_image($content_images[0])."\" class=\"contents_img\">";
                                if($contents_movie){
    $body .=                        "<img class=\"movie_play\" src=\"/iam/img/movie_play.png\" >";
                                }
                                if(count($content_images) > 1){
                                    for($c = 1;$c < count($content_images);$c ++){
    $body .=                        "<img src=\"".cross_image($content_images[$c])."\" class=\"contents_img hidden_image".$contents_row['idx']."\" style=\"".$landing."\">";
                                    }
                                }
    $body .=                "</div>";
                        }else{
    $body .=                "<a href=\"".$contents_row['contents_url']."\" target=\"_blank\" id=\"vidwrap".$contents_row['idx']."\" style=\"position: relative;\">".
                                "<img src=\"".cross_image($content_images[0])."\" class=\"contents_img\">";
                                if($contents_movie){
    $body .=                    "<img class=\"movie_play\" src=\"/iam/img/movie_play.png\" >";
                                }
                                if(count($content_images) > 1){
                                    for($c = 1;$c < count($content_images);$c ++){
    $body .=                            "<img src=\"".cross_image($content_images[$c])."\" class=\"contents_img hidden_image".$contents_row['idx']."\" style=\"".$landing."\">";
                                    }
                                }
    $body .=                "</a>";
                        }
    $body .=            "<script type=\"text/javascript\">".
                            "function play".$contents_row['idx']."() {".
                                "document.getElementById('vidwrap".$contents_row['idx']."').innerHTML = \"".$contents_row['contents_iframe']."\";".
                                "save_load('".$_SESSION['iam_member_id']."','".$contents_row['idx']."',1);".
                            "}".
                        "</script>";
                    }
                } else if((int)$contents_row['contents_type'] == 4) {
                    $vid_data = $contents_row['source_iframe'];
                    if((int)$contents_row['open_type'] == 1){
    $body .=            "<div onclick=\"play".$contents_row['idx']."();\" id=\"vidwrap".$contents_row['idx']."\">";
                            if($content_images[0]){
    $body .=                "<img src=\"".cross_image($content_images[0])."\" class=\"contents_img\">".
                            "<iframe src=\"".$vid_data."\" style=\"width:100%;height: 600px;display:none\"></iframe>";
                            }else{
    $body .=                    "<iframe src=\"".$vid_data."\" style=\"width:100%;height: 600px;\"></iframe>";
                            }
    $body .=            "</div>";
                    }else{
    $body .=            "<a href=\"".$contents_row['contents_url']."\" id=\"vidwrap".$contents_row['idx']."\">";
                            if($content_images[0]){
    $body .=                "<img src=\"".cross_image($content_images[0])."\" class=\"contents_img\">".
                            "<iframe src=\"".$vid_data."\" style=\"width:100%;height: 600px;display:none\"></iframe>";
                            }else{
    $body .=                    "<iframe src=\"".$vid_data."\" style=\"width:100%;height: 600px;\"></iframe>";
                            }
    $body .=            "</a>";
                    }
    $body .=        "<script type=\"text/javascript\">".
                        "function play".$contents_row['idx']."() {".
                            "$('#vidwrap".$contents_row['idx']." > iframe').css(\"display\",\"block\");".
                            "$('#vidwrap".$contents_row['idx']." > img').css(\"display\",\"none\");".
                            "save_load('".$_SESSION['iam_member_id']."','".$contents_row['idx']."',1);".
                        "}".
                    "</script>";
                }
    $body .=    "</div>".
            "</div>";
        }
    $body .="<div class=\"desc-wrap\" style=\"border-bottom: 1px solid #dddddd;\">".
                "<div class=\"desc\" style=\"\">";
                    if($content_images == null){
                        if($cur_card['share_send_card'] != 0 || $_SESSION['iam_member_id'] != $contents_row['mem_id']){
    $body .=        "<a class=\"content-utils\" style=\"cursor:pointer;position:absolute;top:14px;right:10px;z-index:10;\"". 
                        "onclick=\"showSNSModal_byContents('".$contents_row['idx']."', '".$contents_row['gwc_con_state']."');\">".
                        "<img src=\"/iam/img/menu/icon_utils_share.png\"  >".
                    "</a>";
                        } else{
    $body .=        "<a onclick=\"show_contents_utils(".$contents_row['idx'].");\" class=\"content-utils\"".
                        "style=\"position: absolute;top:14px;right: 10px;z-index:15;\" title=\"\">".
                        "<img src = \"/iam/img/menu/icon_utils_box.png\" >".
                    "</a>".
                    "<div class = \"utils-index\" id=\"utils_index_".$contents_row['idx']."\" style=\"display:none;flex-direction: column;z-index:10;top:14px\">".
                        "<div>";
                            if ($_SESSION['iam_member_id'] == $_GET['card_owner'] && $_SESSION['iam_member_id'] == $_GET['card_master']) { 
    $body .=                "<a class=\"content-utils\" onclick=\"contents_add('".$_GET['card_owner']."','".$contents_row['contents_order']."',".$_GET['my_first_card'].");\" >".
                                "<img src=\"/iam/img/menu/icon_utils_add.png\">".
                            "</a>";
                            }
                            if ($_SESSION['iam_member_id'] == $contents_row['mem_id'] && $_GET['is_pay_version']){
    $body .=                "<a class=\"content-utils\" onclick=\"show_con_send('".$contents_row['idx']."')\">".
                                "<img src=\"/iam/img/menu/icon_utils_send.png\"  >".
                            "</a>";
                            }
    $body .=                "<a class=\"content-utils\" onclick=\"showSNSModal_byContents('".$contents_row['idx']."', '".$contents_row['gwc_con_state']."');\" >".
                                "<img src=\"/iam/img/menu/icon_utils_share.png\"  >".
                            "</a>";
    $body .=                "<a class=\"content-utils\" onclick=\"contents_edit('".$contents_row['idx']."', '".$contents_row['ori_store_prod_idx']."');\">".
                                "<img src=\"/iam/img/menu/icon_utils_edit.png\" >".
                            "</a>";
    $body .=            "</div>".
                        "<div style=\"margin-top:7px;\">";
                            if ($_SESSION['iam_member_id'] == $contents_row['mem_id']) {
    $body .=                "<a class=\"content-utils\" onclick=\"contents_del('".$contents_row['idx']."','".$contents_row['ori_store_prod_idx']."','".$contents_row['product_seperate']."','".$contents_row['gwc_con_state']."');\">".
                                "<img src=\"/iam/img/menu/icon_utils_del.png\"  >".
                            "</a>".
                            "<a class=\"content-utils\" onclick=\"contents_range_up('".$contents_row['idx']."','".$contents_row['contents_order']."','".$cur_card['card_short_url']."','".$contents_row['product_seperate']."','".$contents_row['gwc_con_state']."');\">".
                                "<img src=\"/iam/img/menu/icon_utils_up.png\"  >".
                            "</a>".
                            "<a class=\"content-utils\" onclick=\"contents_range_down('".$contents_row['idx']."','".$contents_row['contents_order']."','".$cur_card['card_short_url']."','".$contents_row['product_seperate']."','".$contents_row['gwc_con_state']."');\">".
                                "<img src=\"/iam/img/menu/icon_utils_down.png\"  >".
                            "</a>".
                            "<a class=\"content-utils\" onclick=\"contents_range_up('".$contents_row['idx']."','".$contents_row['contents_order']."','".$cur_card['card_short_url']."','".$contents_row['product_seperate']."','".$contents_row['gwc_con_state']."','max');\">".
                                "<img src=\"/iam/img/menu/icon_utils_start.png\"  >".
                            "</a>".
                            "<a class=\"content-utils\" onclick=\"contents_range_down('".$contents_row['idx']."','".$contents_row['contents_order']."','".$cur_card['card_short_url']."','".$contents_row['product_seperate']."','".$contents_row['gwc_con_state']."','min');\">".
                                "<img src=\"/iam/img/menu/icon_utils_end.png\"  >".
                            "</a>";
                            }
    $body .=            "</div>".
                    "</div>".
                "</div>";
                        }
                    }
            if((int)$contents_row['contents_type'] == 3) {
                $price_show1 = "적립금";
                $price_show2 = "판매가";
                $price_style = "";
                $sql_card = "select sale_cnt, sale_cnt_set, add_reduce_val from Gn_Iam_Name_Card where idx='{$contents_row['card_idx']}'";
                $res_card = mysqli_query($self_con, $sql_card);
                $row_card = mysqli_fetch_array($res_card);
    $body .=    "<div class=\"desc is-product\" style=\"margin-top: 10px;\">".
                    "<div class=\"desc-inner\">".
                        "<div class=\"outer ".$row_card['sale_cnt_set']."\">";
                            $buy_btn = "";
                            if($contents_row['contents_price'] > 0){
                                if(!$row_card['sale_cnt_set']){
                                    $style_decor = "";
                                    $state_end = "";
                                    $discount = 100 - ($contents_row['contents_sell_price'] / $contents_row['contents_price']) * 100;
                                    $add_point = $contents_row['contents_price'] * ((int)$discount / 100);
                                }else{
                                    if(!$row_card['sale_cnt']){
                                        $style_decor = "text-decoration: line-through;";
                                        $state_end = "마감";
                                        $discount = 100 - ($contents_row['contents_sell_price'] / $contents_row['contents_price']) * 100;
                                        $add_point = $contents_row['contents_price'] * (((int)$discount - $row_card['add_reduce_val']) / 100);
                                    }else{
                                        $style_decor = "";
                                        $state_end = "적립";
                                        $discount = 100 - ($contents_row['contents_sell_price'] / $contents_row['contents_price']) * 100;
                                        $add_point = $contents_row['contents_price'] * ((int)$discount / 100);
                                    }
                                }
                                $state_end = "";
                                if($contents_row['gwc_con_state'] != 0){
                                    $price_show1 = "최저가";
                                    $price_show2 = "정상가";
                                    $price_style = "text-decoration: line-through;";
                                    $add_point = $contents_row['contents_sell_price'];
                                }
                                if(($_GET['iamstore'] == "Y" && $gwc_pay_mem) || $_GET['iamstore'] == "C"){
                                    $buy_btn_con = "visibility: hidden;";
                                    $discount = 100 - ($contents_row['send_provide_price'] / $contents_row['contents_sell_price']) * 100;
                                }
    $body .=                    "<div class=\"price\" style=\"width:100%;display: inline-flex;\">".    
                                    "<span style=\"vertical-align: top;color: red;font-size: 18px;font-weight: bold;margin-right: 3px;\">".(int)$discount."% </span>".
                                    "<span class=\"downer\" style=\"color:black;font-size: 18px;margin-right: 3px;\">".number_format((int)$add_point)."원 </span>".
                                    "<span class=\"downer\" style=\"font-size: 12px;color:darkgrey;\"><span style=\"vertical-align: text-bottom;font-size: 12px;".$price_style."\">".number_format($contents_row['contents_price'])."</span>원 </span>";
                                    if(($_GET['iamstore'] == "Y" && $gwc_pay_mem) || $_GET['iamstore'] == "C"){
    $body .=                        "<span class=\"downer\" style=\"color:black;font-size: 14px;margin-right: 5px;margin-left: 5px;\">|| </span>".
                                    "<span style=\"vertical-align: top;color: blue;font-size: 16px;font-weight: bold;margin-right: 3px;\">공급가</span>".
                                    "<span class=\"downer\" style=\"color:black;font-size: 18px;margin-right: 3px;\">".number_format($contents_row['send_provide_price'])."원 </span>";
                                    }
    $body .=                    "</div>".
                                "<span style=\"font-size: 15px;\">".$state_end."</span>";
                            }else{
    $body .=                    "<div class=\"price\" style=\"width:230px;display: inline-flex;\">".
                                    "<span style=\"vertical-align: top;color: red;font-size: 18px;font-weight: bold;margin-right: 3px;\">0%</span>".
                                    "<span class=\"downer\" style=\"color:black;font-size: 18px;margin-right: 3px;\">".number_format($contents_row['contents_price'])."원</span>".
                                    "<span class=\"downer\" style=\"font-size: 12px;color:darkgrey;\"><span style=\"vertical-align: text-bottom;".$price_style."\">".number_format($contents_row['contents_price'])."</span>원</span>".
                                "</div>";
                            }
    $body .=            "</div>".
                    "</div>".
                "</div>";
            }
            if($contents_row['contents_title'] != "") {
    $body .=    "<div class=\"title service_title\" style=\"display: flex;flex-direction: column;justify-content: center;height:45px;\">".
                    "<h3 style=\"font-size: 14px;text-align: left;\">".$contents_row['contents_title']."</h3>".
                "</div>";
            }
    $body .="</div>";
    $body .="<div class=\"info-wrap\" style=\"min-height :30px;border:none;height: 50px;\">";
                $host_pos = strpos($contents_row['contents_url'],"//");
                $host_name = substr($contents_row['contents_url'],$host_pos + 2);
                $host_pos = strpos($host_name,"/");
                $host_name = substr($host_name,0,$host_pos);       
    $body .=    "<div class=\"second-box\" style=\"border:none;padding-right:2px;padding-left:2px;";
                if($cur_card['post_display'] == 0){ 
    $body .=        "display:none !important;";
                }
    $body .=    "\">";
    /*$body .=      "<div class=\"in-box\">".
                        "<div style=\"display: flex;vertical-align: middle\">".
                            "<a class = \"hand\" href=\"javascript:contents_like('".$contents_row['idx']."','".$_SESSION['iam_member_id']."','". $contents_row['product_seperate'] ."', '". $contents_row['gwc_con_state'] ."');\">";
                                if(in_array($_SESSION['iam_member_id'],explode(",",$contents_row['contents_like']))){ 
    $body .=                    "<img src=\"/iam/img/menu/icon_like_active.png\" width=\"24px\" alt=\"\" id=\"like_img_".$contents_row['idx']."\">";
                                }else{
    $body .=                    "<img src=\"/iam/img/menu/icon_like.png\" width=\"24px\" alt=\"\" id=\"like_img_".$contents_row['idx']."\">";
                                }
    $body .=                "</a>".
                            "<p class = \"second-box-like like-count like_".$contents_row['idx']."\" style=\"font-size:13px;margin-top:3px;\">".
                                number_format(count(explode(",",$contents_row['contents_like'])))."개".
                            "</p>".
                        "</div>";
                        if($_GET['iamstore'] == "N"){
    $body .=            "<div class=\"in-box\" style=\"position: absolute;right: 10px;\">".
                            "<a class=\"gwc_order_btn1\" href=\"javascript:show_order_option('".$contents_row['idx']."','cart','".$gwc_mem."')\" style=\"margin-bottom: 5px;font-size: 12px;border: 1px solid #99cc00;margin-right: 10px;padding: 3px 10px;border-radius: 10px;\">장바구니</a>".
                            "<a class=\"gwc_order_btn2\" href=\"javascript:show_order_option('".$contents_row['idx']."','pay','".$gwc_mem."')\" style=\"margin-bottom: 5px;font-size: 12px;padding: 3px 10px;border-radius: 10px;border: 1px solid;color: white;background-color: #99cc00;\">바로구매</a>".
                        "</div>";
                        }
    $body .=        "</div>";*/
    $body .=    "</div>".
            "</div>";
        
            $post_status_sql = "select count(*) from Gn_Iam_Post where content_idx = '{$contents_row['idx']}' and status = 'N' and lock_status = 'N'";
            $post_status_res = mysqli_query($self_con, $post_status_sql);
            $post_status_row =  mysqli_fetch_array($post_status_res);
            $post_status_count = $post_status_row[0];
            if ($post_status_count  > 0)
                $script =  "<script>  $('#post_alarm_".$contents_row['idx']."').html(".$post_status_count."); </script>";
            else
                $script =  "<script>  $('#post_alarm_".$contents_row['idx']."').hide(); </script>";
    $body .=   $script; 
    $body .="<div class=\"post-wrap post_wrap".$contents_row['idx']."\" style=\"display:none\" id = \"post_wrap".$contents_row['idx']."\">".
                "<div style=\"display: flex;justify-content: flex-end;\">".
                    "<div style=\"margin-left:30px;margin-right:15px;width:100%\">".
                        "<textarea id = \"post_content".$contents_row['idx']."\" name = \"post_content".$contents_row['idx']."\" class=\"post_content\" maxlength=\"300\" style=\"font-size:14px;width:100%;height:35px;border: 1px;\" placeholder=\"댓글은 300자 이내로 작성해주세요\" ></textarea>".
                    "</div>".
                    "<div>".
                    "<button type=\"button\" class=\"btn btn-link\" style=\"font-size:14px;padding: 5px 12px;color:#99cc00\" id=\"send_post\" onclick=\"add_post('".$contents_row['idx']."')\">작성</button>".
                "</div>".
            "</div>".
            "<div style=\"margin-left:30px;\">".
                "<span id = \"post_status\" name = \"post_status\" style=\"padding: 10px;font-size:10px\">0/300</span>".
            "</div>".
            "<div style=\"border: 0px solid #dddddd;margin-left:30px;\" id = \"post_list_".$contents_row['idx']."\" name = \"post_list_".$contents_row['idx']."\">";
                while($post_row = mysqli_fetch_array($post_res)){
                    $sql_mem_p = "select profile, mem_name,  from Gn_Member where mem_id='{$post_row['mem_id']}'";
                    $res_mem_p = mysqli_query($self_con, $sql_mem_p);
                    $row_mem_p = mysqli_fetch_array($res_mem_p);

                    $post_card_sql = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$post_row['mem_id']}' order by req_data asc";
                    $post_card_result = mysqli_query($self_con, $post_card_sql);
                    $post_card_row = mysqli_fetch_array($post_card_result);
                    
    $body .=        "<div class=\"user-item\" id=\"post_reply".$post_row['id']."\">".
                        "<a href=\"/?".strip_tags($post_card_row['card_short_url'])."\" class=\"img-box\">".
                            "<div class=\"user-img\" style=\"margin: 5px;width:32px;height:32px;\">";
                                if($row_mem_p['profile']){
    $body .=                        "<img src=\"".$row_mem_p['profile'] ."\" alt=\"\">";
                                }else{
    $body .=                        "<img src=\"/iam/img/profile_img.png\" alt=\"\">";
                                }
    $body .=                "</div>".
                        "</a>".
                        "<div class=\"wrap\" style=\"margin:10px 0px;\">".
                            "<span class=\"date\">".
                                $row_mem_p['mem_name']. $post_row['reg_date'].
                            "</span>".
                            "<span class=\"user-name\">";
                                if($post_row['type']){
    $body .=                        $post_row['title']."<br>";
                                }
    $body .=                    str_replace("\n", "<br>", $post_row['content']).
                            "</span>".
                        "</div>";
                        if($_SESSION['iam_member_id'] == $post_row['mem_id']){
                            if($post_row['type'] == 0){
    $body .=                "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px;\">".
                                "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">".
                                "<ul class=\"dropdown-menu namecard-dropdown \" style=\"background: white; color : black;top:10px;\">".
                                    "<li>".
                                        "<a onclick=\"edit_post('".$contents_row['idx']."','".$post_row['id']."','".$post_row['content']."')\" title=\"댓글 수정\">".
                                            "<p>수정</p>".
                                        "</a>".
                                    "</li>".
                                    "<li>".
                                        "<a onclick=\"delete_post('".$contents_row['idx']."','".$post_row['id']."')\" title=\"댓글 삭제\">".
                                            "<p>삭제</p>".
                                        "</a>".
                                    "</li>".
                                "</ul>".
                            "</div>";
                            }
                        }else if($_SESSION['iam_member_id'] == $contents_row['mem_id']){
                            if($post_row['type'] == 0){
    $body .=                "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">".
                                "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">".
                                "<ul class=\"dropdown-menu namecard-dropdown \" style=\"background: white; color : black;top:10px;\">".
                                    "<li>".
                                        "<a onclick=\"delete_post('".$contents_row['idx']."','".$post_row['id']."')\" title=\"댓글 삭제\">".
                                            "<p>삭제</p>".
                                        "</a>".
                                    "</li>".
                                    "<li>".
                                        "<a onclick=\"lock_post('".$contents_row['idx']."','".$post_row['id']."')\" title=\"댓글 차단\">".
                                            "<p>차단</p>".
                                        "</a>".
                                    "</li>".
                                "</ul>".
                            "</div>";
                            }
                        }
                        if($post_row['type'] == 0){
    $body .=            "<div style=\"position: absolute;left: 60px;bottom: 0px\">".
                            "<span style=\"color: #bdbdbd;cursor:pointer;font-size:13px\" onclick=\"show_post_reply(".$post_row['id'].");\">".
                                "답글달기".
                            "</span>".
                        "</div>";
                        }
    $body .=        "</div>".
                    "<div id = \"post_reply_".$post_row['id']."\"  class = \"post_reply_wrap\" style=\"display: none;margin : 10px 0px\" >".
                        "<div style=\"display: flex;justify-content: flex-end;\">".
                            "<div style=\"margin-left:60px;margin-right:15px;width:100%\">".
                                "<textarea id=\"post_reply_".$post_row['id']."_content\" name=\"post_reply_".$post_row['id']."_content\" class  = \"post_reply_content\" maxlength=\"300\" placeholder=\"답글은 300자 이내로 작성해주세요\" style=\"font-size:14px;height:35px;width: 100%;border: 1px;\"></textarea>".
                            "</div>".
                            "<div>".
                                "<button type=\"button\" class=\"btn btn-link\" style=\"font-size:14px;padding: 5px 12px;color:#99cc00\" onclick=\"add_post_reply('".$contents_row['idx']."','".$post_row['id']."')\">작성</button>".
                            "</div>".
                        "</div>".
                        "<div style=\"border-bottom: 0px solid #dddddd;margin-left:60px\">".
                            "<span id = \"post_reply_status\" name = \"post_reply_status\" style=\"padding: 10px\">0/300</span>".
                        "</div>".
                    "</div>";
                    
                    $reply_sql = "select * from Gn_Iam_Post_Response r where r.post_idx = {$post_row['id']} order by r.reg_date";
                    $reply_res = mysqli_query($self_con, $reply_sql);
                    while($reply_row = mysqli_fetch_array($reply_res)){
                        $sql_mem_pr = "select mem_name, profile from Gn_Member where mem_id='{$reply_row['mem_id']}'";
                        $res_mem_pr = mysqli_query($self_con, $sql_mem_pr);
                        $row_mem_pr = mysqli_fetch_array($res_mem_pr);

                        $reply_card_sql = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$reply_row['mem_id']}' order by req_data asc";
                        $reply_card_result = mysqli_query($self_con, $reply_card_sql);
                        $reply_card_row = mysqli_fetch_array($reply_card_result);
    $body .=            "<div class=\"user-item\" style=\"padding-left: 50px\">".
                            "<a href=\"/?".strip_tags($reply_card_row['card_short_url'])."\" class=\"img-box\">".
                                "<div class=\"user-img\" style=\"margin: 5px;width:32px;height:32px;\">";
                                    if($row_mem_pr['profile']){ 
    $body .=                            "<img src=\"".$row_mem_pr['profile'] ."\" alt=\"\">";
                                    }else{
    $body .=                            "<img src=\"/iam/img/profile_img.png\" alt=\"\">";
                                    }
    $body .=                    "</div>".
                            "</a>".
                            "<div class=\"wrap\">".
                                    "<span class=\"date\">".
                                        $row_mem_pr['mem_name'] .$reply_row['reg_date'].
                                    "</span>".
                                    "<span class=\"user-name\" id = \"reply_list_".$reply_row['id']."\">".
                                        str_replace("\n", "<br>", $reply_row['contents']).
                                    "</span>".
                            "</div>";
                            if($_SESSION['iam_member_id'] == $post_row['mem_id']){
                                if($reply_row['type'] == 0){
    $body .=                    "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">".
                                    "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">".
                                    "<ul class=\"dropdown-menu namecard-dropdown \" style=\"background: white; color : black;top:10px;\">".
                                        "<li>".
                                            "<a onclick=\"edit_post_reply('".$contents_row['idx']."','".$post_row['id']."','".$reply_row['id']."','".$reply_row['contents']."')\" title=\"답글 수정\">".
                                                "<p>수정</p>".
                                            "</a>".
                                        "</li>".
                                        "<li>".
                                            "<a onclick=\"delete_post_reply('".$contents_row['idx']."','".$reply_row['id']."')\" title=\"답글 삭제\">".
                                                "<p>삭제</p>".
                                            "</a>".
                                        "</li>".
                                    "</ul>".
                                "</div>";
                                }
                            }else if($_SESSION['iam_member_id'] == $contents_row['mem_id']){
                                if($reply_row['type'] == 0){
    $body .=                    "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">".
                                    "<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">".
                                    "<ul class=\"dropdown-menu namecard-dropdown \" style=\"background: white; color : black;top:10px;\">".
                                        "<li>".
                                            "<a onclick=\"delete_post_reply('".$contents_row['idx']."','".$reply_row['id']."')\" title=\"답글 삭제\">".
                                                "<p>삭제</p>".
                                            "</a>".
                                        "</li>".
                                        "<li>".
                                            "<a href=\"javascript:void(0)\" onclick=\"lock_post_reply('".$contents_row['idx']."','".$reply_row['id']."')\" title=\"답글 차단\">".
                                                "<p>차단</p>".
                                            "</a>".
                                        "</li>".
                                    "</ul>".
                                "</div>";
                                }
                            }
    $body .=                "</div>";
                    }
                }
    $body .=    "</div>".
            "</div>".
        "</div>".
    "</div>";
        }
$logs->add_log("end gmarket");
$logs->write_to_file();
echo $body;
?>
