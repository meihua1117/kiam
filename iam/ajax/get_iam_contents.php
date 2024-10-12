<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

$limit = (intval($_GET['limit']) != 0 ) ? $_GET['limit'] : 4;
$offset = (intval($_GET['offset']) != 0 ) ? $_GET['offset'] : 0;

$contents_count_per_page = $limit;
$w_offset = $offset;
if($w_offset < 0 ){
    $w_offset = 0;
}

$request_url = $_GET['request_uri'];
if($_GET['name_card'])
    $request_short_url = $_GET['name_card'];
if($_GET['key1'] == "")//추천키워드
    $_GET['key1'] = 0;
if($_GET['key2'] == "")//인기검색어
    $_GET['key2'] = 0;
if($_GET['sort'] == "")//정렬기준
    $_GET['sort'] = 0;
if($cur_win == ""){
    if($card_owner == $_SESSION['iam_member_id'])
        $cur_win = "we_story";//curwin이 주소에 없으면 my_info로 설정
    else
        $cur_win = "my_info";//curwin이 주소에 없으면 my_info로 설정
}
if($cur_win == "iam_mall"){
    $mall_type = $_GET['mall_type'];
    if(!$mall_type)
        $mall_type = "main_mall";
    $mall_type1 = $_GET['mall_type1'];
    if(!$mall_type1)
        $mall_type1 = "all";
}
//추천키워드
if($cur_win == "we_story"){
    $rec_sql = "select * from Gn_Search_Key where key_id = 'wecon_recom_keyword'";
    $rec_result = mysqli_query($self_con,$rec_sql);
    $rec_row = mysqli_fetch_array($rec_result);
    $rec_array = explode(",",$rec_row['key_content']);
    $rec_count = count($rec_array) > 6 ? 6:count($rec_array);
    //인기검색어
    $rec_sql = "select * from  Gn_Search_Key where key_id = 'wecon_search_keyword'";
    $rec_result = mysqli_query($self_con,$rec_sql);
    $rec_row = mysqli_fetch_array($rec_result);
    $interest_array = explode(",",$rec_row['key_content']);
    $interest_count = count($interest_array) > 5 ? 5:count($interest_array);
    $post_display = 1;//댓글박스 보이기 1:보이기 0:감추기
}
$recent_post = 0;
if($cur_win == "my_info"){
    if(strstr($request_url, '?')) {//카드링크가 있으면
        $request_short_url = explode('?',$request_url);
        if(strstr($request_short_url[1], '&')) {//링크에 다른 파라미터가 있을 경우
            $request_short_url_arr = explode('&',$request_short_url[1]);
            $request_short_url = $request_short_url_arr[0];
        } else {
            $request_short_url = $request_short_url[1];//링크에 카드링크만 있을 경우
        }
        $request_short_url = substr($request_short_url,0,10);
    }
    $card_mem_sql = "select * from Gn_Member where mem_code = '$card_owner_code'";
    $card_mem_result = mysqli_query($self_con,$card_mem_sql);
    $card_mem_row = mysqli_fetch_array($card_mem_result);
    $card_owner_site = $card_mem_row['site_iam'];//카드를 방문한 회원의 아이엠분양사명
    $card_owner = $card_mem_row['mem_id'];
}else if($cur_win == "group-con"){
    if(strstr($request_url, '?')) {//카드링크가 있으면
        $group_card_url = explode('?',$request_url);
        if(strstr($group_card_url[1], '&')) {//링크에 다른 파라미터가 있을 경우
            $group_card_url_arr = explode('&',$group_card_url[1]);
            $group_card_url = $group_card_url_arr[0];
        } else {
            $group_card_url = $group_card_url[1];//링크에 카드링크만 있을 경우
        }
        $group_card_url = substr($group_card_url,0,10);
    }
}
if ($HTTP_HOST != "kiam.kr") //분양사사이트이면
    $query = "select * from Gn_Iam_Service where sub_domain like 'http://" . $HTTP_HOST . "'";
else
    $query = "select * from Gn_Iam_Service where sub_domain like 'http://www.kiam.kr'";
$res = mysqli_query($self_con,$query);
$domainData = mysqli_fetch_array($res);
$first_card_idx = $domainData['profile_idx'];//분양사의 1번 카드아이디
$sql = "select * from Gn_Iam_Name_Card where idx = '$first_card_idx'";
$result = mysqli_query($self_con,$sql);
$main_card_row = mysqli_fetch_array($result);
$first_card_url = $main_card_row[card_short_url];//분양사이트 1번 네임카드 url

$sql = "select site_iam,mem_code from Gn_Member where mem_id = '{$main_card_row['mem_id']}'";
$result = mysqli_query($self_con,$sql);
$row = mysqli_fetch_array($result);
$bunyang_site = $row['site_iam'];
$bunyang_site_manager_code = $row['mem_code'];


if ($_SESSION['iam_member_id']) {
    $price_service = 0;
    $name_service = "";
    $sellerid_service = "";
    $Gn_mem_row = $member_iam;
    $Gn_point = $Gn_mem_row['mem_point'];
    $user_site = $Gn_mem_row['site_iam'];//로긴한 회원의 아이엠분양사명
    $user_mem_code = $Gn_mem_row['mem_code'];
    $send_ids = $Gn_mem_row['send_ids'];//카드, 콘텐츠 전송한 아아디
    if($send_ids != ""){
        $cnt = explode(",", $send_ids);
        $send_ids_cnt = count($cnt);
    }
    $date = date("Y-m-d H:i:s");
    $pay_query = "select count(*) from tjd_pay_result where (iam_pay_type != '0' and iam_pay_type != '') and buyer_id='{$_SESSION['iam_member_id']}' and end_status='Y' and `end_date` > '$date'";
    $pay_result = mysqli_query($self_con,$pay_query);
    $pay_row = mysqli_fetch_array($pay_result);
    $pay_status = $pay_row[0] + $Gn_mem_row[iam_type];// 유료회원이면 true,무료회원이면 false
    $show_sql = "select show_iam_card,show_iam_like from Gn_Member where mem_id = '{$_SESSION['iam_member_id']}'";
    $show_result = mysqli_query($self_con,$show_sql);
    $show_row = mysqli_fetch_array($show_result);
    $show_my_iam_card = $show_row['show_iam_card'];


    //24시간내에 등록한 댓글이 있는지 확인
    $post_time = date("Y-m-d H:i:s", strtotime("-1 week"));
    $post_sql = "select count(*) from Gn_Member where mem_id = '{$_SESSION['iam_member_id']}' and last_regist > '$post_time'";
    $post_result = mysqli_query($self_con,$post_sql);
    $post_row = mysqli_fetch_array($post_result);
    $recent_post = $post_row[0];
    $exp_status = 0;
    if($domainData[service_type] == 3 && !is_null($Gn_mem_row[exp_limit_date])){
        if($Gn_mem_row[exp_start_status]){
            $exp_status = 1;
        }else if($Gn_mem_row[exp_mid_status]){
            $limit_time = strtotime($Gn_mem_row[exp_limit_date]);
            $cur_time = strtotime(date("Y-m-d H:i:s", strtotime("+1 week")));
            if($limit_time <= $cur_time)
                $exp_status = 2;
        }else if($Gn_mem_row[exp_limit_status]){
            $limit_time = strtotime($Gn_mem_row[exp_limit_date]);
            $cur_time = strtotime(date("Y-m-d H:i:s"));
            if($limit_time <= $cur_time)
                $exp_status = 3;
        }else{
            $limit_time = strtotime($Gn_mem_row[exp_limit_date]);
            $cur_time = strtotime(date("Y-m-d H:i:s"));
            if($limit_time <= $cur_time){
                $exp_status = 4;
            }
        }
    }
}
else
    $pay_status = false;
if($cur_win == "my_info"){
    $card_sql="select * from Gn_Iam_Name_Card where card_short_url = '$request_short_url'";
    $card_result=mysqli_query($self_con,$card_sql);
    $G_card=mysqli_fetch_array($card_result);
    $mem_sql = "select * from Gn_Member where mem_id = '{$G_card['mem_id']}'";
    $mem_res = mysqli_query($self_con,$mem_sql);
    $mem_row = mysqli_fetch_array($mem_res);
    $mem_site = $mem_row[site_iam];
    $mem_site .= $mem_site == "kiam"?".kr":".kiam.kr";
    if($mem_site != $HTTP_HOST) {
        echo "<script>location.href='http://".$mem_site."/?".$request_short_url.$card_owner_code."';</script>";
    }
    if($card_owner_code != $user_mem_code){//로긴자가 다른 회원의 아이엠을 보기
        $share_sql = "select mem_id,iam_type from Gn_Member where mem_code = '$card_owner_code'";
        $share_res = mysqli_query($self_con,$share_sql);
        $share_row = mysqli_fetch_array($share_res);
        $share_member_id = $share_row['mem_id'];//로긴자가 방문하는 다른 회원
        $date = date("Y-m-d H:i:s");
        $pay_query = "select count(*) from tjd_pay_result where (iam_pay_type != '0' and iam_pay_type != '') and buyer_id='$share_member_id' and end_status='Y' and `end_date` > '$date'";
        $pay_result = mysqli_query($self_con,$pay_query);
        $pay_row = mysqli_fetch_array($pay_result);
        $share_pay_status = $pay_row[0] + $share_row[iam_type];//본사 유료회원이면 true,무료회원이면 false
        $share_admin_sql = "select count(*) from Gn_Iam_Service where mem_id = '$share_member_id'";
        $share_admin_res = mysqli_query($self_con,$share_admin_sql);
        $share_admin_row = mysqli_fetch_array($share_admin_res);
        $share_sub_admin = $share_admin_row[0];
        $show_sql = "select show_iam_card from Gn_Member where mem_id = '$share_member_id'";
        $show_result = mysqli_query($self_con,$show_sql);
        $show_row = mysqli_fetch_array($show_result);
        $show_share_iam_card = $show_row['show_iam_card'];
    }else{
        $share_pay_status = false;
        $show_share_iam_card = "N";
    }
    $_SESSION['recommender_code'] = $card_owner_code;
    @setcookie("recommender_code", $card_owner_code, time()+3600);
    $_COOKIE[recommender_code] = $card_owner_code;

    if($G_card[phone_display]=='N' && $card_owner != $_SESSION['iam_member_id'] ) {
        echo "<script>toastr.error('비공개카드입니다.');history.go(-1);</script>";
        exit;
    }
    $myiam_count_sql="update Gn_Iam_Name_Card set iam_click = iam_click + 1 where idx = '{$G_card['idx']}'";
    mysqli_query($self_con,$myiam_count_sql) or die(mysqli_error($self_con));

    if($domainData['sub_domain'] == "")
        $domainData['sub_domain'] = "http://kiam.kr/";
    if(!$card_owner) {
        $card_owner = trim($_SESSION['iam_member_id']);
    }
    $story_title1 = $G_card[story_title1];//내 소개
    $story_title2 = $G_card[story_title2];//현재 소속
    $story_title3 = $G_card[story_title3];//경력소개
    //$story_title4 = $G_card[story_title4];
    $story_myinfo = $G_card[story_myinfo];//나의 스토리 내소개
    $story_company = $G_card[story_company];//나의 스토리 소속
    $story_career = $G_card[story_career];//나의 스토리 경력
    $online1_check = $G_card[online1_check];//홈피1 체크
    $story_online1_text = $G_card[story_online1_text];//홈피1텍스트
    $story_online1 = $G_card[story_online1];//홈피1링크

    $online2_check = $G_card[online2_check];//홈피2 체크
    $story_online2_text = $G_card[story_online2_text];//홈피2텍스트
    $story_online2 = $G_card[story_online2];//홈피2링크
    $post_display = $G_card[post_display];//댓글박스 보이기 1:보이기 0:감추기
    
    //메인이미지
    //디폴트 이미지 꺼내기
    if($G_card['main_img1']) {
        $main_img1 = str_replace("http://www.kiam.kr",$cdn, $G_card['main_img1']);
    } else {
        $main_img1 = str_replace("http://www.kiam.kr",$cdn, $main_card_row['main_img1']);
    }

    if($G_card['main_img2']) {
        $main_img2 = str_replace("http://www.kiam.kr",$cdn, $G_card['main_img2']);
    } else {
        $main_img2 = str_replace("http://www.kiam.kr",$cdn, $main_card_row['main_img2']);
    }
    if($G_card['main_img3']) {
        $main_img3 = str_replace("http://www.kiam.kr",$cdn, $G_card['main_img3']);
    } else {
        $main_img3 = str_replace("http://www.kiam.kr",$cdn, $main_card_row['main_img3']);
    }
    //로고
    if(is_null($G_card[profile_logo])) {
        $G_card[profile_logo] = "/iam/img/common/logo-2.png";
    }
}else if($cur_win == "group-con"){
    if($gkind == "" && $search_key == "")
        $gkind = "recommend";
    else if($gkind == "" && $search_key != "")
        $gkind = "search_con";
    if($gkind != "recommend" && $gkind != "mygroup" && $gkind != "search" && $gkind != "search_con"){
        $g_card_sql = "select * from Gn_Iam_Name_Card where card_short_url = '$group_card_url'";
        $g_card_res = mysqli_query($self_con,$g_card_sql);
        $group_card = mysqli_fetch_array($g_card_res);
        $post_display = $group_card[post_display];//댓글박스 보이기 1:보이기 0:감추기
        $main_img1 = str_replace("http://www.kiam.kr",$cdn, $group_card['main_img1']);
        $main_img2 = str_replace("http://www.kiam.kr",$cdn, $group_card['main_img2']);
        $main_img3 = str_replace("http://www.kiam.kr",$cdn, $group_card['main_img3']);
        $visit_sql = "update gn_group_member set visit_date = now() where mem_id = '{$_SESSION['iam_member_id']}' and group_id=$gkind";
        mysqli_query($self_con,$visit_sql);
    }
    if($_SESSION['iam_member_id']){
        $sql = "select * from gn_group_member where mem_id = '{$_SESSION['iam_member_id']}'";
        $res = mysqli_query($self_con,$sql);
        $my_group = array();
        while($row = mysqli_fetch_array($res)){
            array_push($my_group,$row[group_id]);
        }
        $my_group = implode(",",$my_group);
    }
}else
    $post_display = 1;
$my_first_card = ($cur_win == "my_info" && $request_short_url == $first_card_url && $_SESSION['iam_member_id'] && !$_SESSION['iam_member_subadmin_id']) * 1;

$cursor = 0;

// 콘텐츠부분 시작

// julian 콘텐츠를 순환하면서 뿌려주는 부분
$search_key = $_GET['search_key'];
//카드명이 검색키와 같은거 꺼내기
if($search_key){
    $search_sql = "(contents_title like '%$search_key%' or contents_desc like '%$search_key%' ";
    $sql = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and card_name like '%$search_key%' ";
    $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    while( $row_card = mysqli_fetch_array($result)){
        $search_sql.=" or westory_card_url like '%$row_card[card_short_url]%'";
    }
    $search_sql.= ") ";
}else{
    $search_sql = "1=1";
}

if(!$cur_win || $cur_win == "my_info"){
    //공유받은 명함은 공유한 사람의 프로필 콘텐츠가 보여야 해요// comment

    $sql8="select * from Gn_Iam_Contents WHERE card_short_url = '$G_card[card_short_url]' and ".$search_sql." ORDER BY contents_order desc";
    if($search_key)
        $sql8="select * from Gn_Iam_Contents WHERE group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' and ($search_sql) ORDER BY contents_order desc";
    $result8=mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
    $cont_count = mysqli_num_rows($result8);
    $sql8 .= " limit $contents_count_per_page offset ".$w_offset;
}else if($cur_win == "my_story"  && $_SESSION['iam_member_id']== ""){

    $sql8="select * from Gn_Iam_Contents WHERE card_short_url = '$G_card[card_short_url]' and $search_sql ORDER BY contents_order desc";
    $result8=mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
    $cont_count = mysqli_num_rows($result8);
    $sql8 .= " limit $contents_count_per_page offset ".$w_offset;
}else if($cur_win == "my_story" && ( $_SESSION['iam_member_id']!= "")){

    $sql8="select * from Gn_Iam_Contents WHERE group_id is NULL and (mem_id = '$card_owner' or contents_share_text like '%$card_owner%')  and $search_sql ORDER BY req_data desc, up_data desc";
    $result8=mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
    $cont_count = mysqli_num_rows($result8);
    $sql8 .= " limit $contents_count_per_page offset ".$w_offset;
}else if($cur_win == "shared_receive" &&  $_SESSION['iam_member_id']!= ""){

    $sql8="select * from Gn_Iam_Contents WHERE contents_share_text like '%{$_SESSION['iam_member_id']}%'  and $search_sql ORDER BY req_data desc, up_data desc";
    $result8=mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
    $cont_count = mysqli_num_rows($result8);
    $sql8 .= " limit $contents_count_per_page offset ".$w_offset;
}else if($cur_win == "shared_send" &&  $_SESSION['iam_member_id']!= ""){

    $sql8="select * from Gn_Iam_Contents WHERE mem_id = '{$_SESSION['iam_member_id']}' and contents_share_text != ''  and $search_sql ORDER BY req_data desc, up_data desc";
    $result8=mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
    $cont_count = mysqli_num_rows($result8);
    $sql8 .= " limit $contents_count_per_page offset ".$w_offset;
}else if($cur_win == "unread_post" &&  $_SESSION['iam_member_id']!= ""){

    $sql8="select * from Gn_Iam_Contents c inner join  Gn_Iam_Post p on c.idx = p.content_idx WHERE c.mem_id = '{$_SESSION['iam_member_id']}' and p.status = 'N'  and $search_sql GROUP BY c.idx ORDER BY req_data desc ";
    $result8=mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
    $cont_count = mysqli_num_rows($result8);
    $sql8 .= " limit $contents_count_per_page offset ".$w_offset;
}else if($cur_win == "iam_mall"){

    $sql8 = "select mall.*,mem.mem_name,mem.site_iam from Gn_Iam_Mall mall inner join Gn_Member mem on mem.mem_id=mall.mem_id where ";
    if($mall_type == "main_mall"){
        $sql8 .= " display_status = 1 ";
    }else if($mall_type == "sub_mall"){
        $site_info = explode(".",$HTTP_HOST);
        $site_name = $site_info[0];
        $sql8 .= " display_status = 1 and mem.site_iam = '$site_name'";
    }else if($mall_type == "my_mall"){
        $sql8 .= "mall.mem_id = '{$_SESSION['iam_member_id']}'";
    }else if($mall_type == "best_mall"){
        $sql8 .= "mall.sample_display = 'Y'";
    }else if($mall_type == "my_mall_like"){
        $sql8 .= "(mall.mall_like ='{$_SESSION['iam_member_id']}' or mall.mall_like like '{$_SESSION['iam_member_id']},%' or mall.mall_like like '%,{$_SESSION['iam_member_id']}' or mall.mall_like like '%,{$_SESSION['iam_member_id']},%')";
    }

    if($mall_type1 == "iam")
        $sql8 .= " and mall.mall_type = 1";
    elseif($mall_type1 == "card")
        $sql8 .= " and mall.mall_type = 2";
    elseif($mall_type1 == "contents")
        $sql8 .= " and mall.mall_type = 3";
    elseif($mall_type1 == "service")
        $sql8 .= " and mall.mall_type = 4";

    if($_GET[sort] == 1){
        $sql8 .= " and reg_date >= ADDTIME(now(), '-1:0:0') ";
    }else if($_GET[sort] == 2){
        $sql8 .= " and reg_date >= DATE(now()) ";
    }else if($_GET[sort] == 3){
        $sql8 .= " and WEEKOFYEAR(reg_date) = WEEKOFYEAR(now()) and YEAR(reg_date) = YEAR(now()) ";
    }else if($_GET[sort] == 4){
        $sql8 .= " and MONTH(reg_date) = MONTH(now()) and YEAR(reg_date) = YEAR(now()) ";
    }else if($_GET[sort] == 5){
        $sql8 .= " and YEAR(reg_date) = YEAR(now()) ";
    }
    if($search_key){
        $sql8 .= " and (keyword like '%$search_key%' or title like '%$search_key%' or sub_title like '%$search_key%' or description like '%$search_key%' or mall.mem_id like '%$search_key%' or mem.mem_name like '%$search_key%' or mem.site_iam like '%$search_key%') ";
    } 

    if($_GET[sort] == 11){//게시일짜
        $sql8 .= " ORDER BY reg_date desc";
    }elseif($_GET[sort] == 12){//조회수
        $sql8 .= " ORDER BY visit_count desc";
    }elseif($_GET[sort] == 13){//좋아요
        $sql8 .= " ORDER BY mall_like_count desc";
    }elseif($_GET[sort] == 14){//가격순
        $sql8 .= " ORDER BY sell_price desc";
    }else{
        if($mall_type == "best_mall")
            $sql8 .= " ORDER BY sample_order desc";    
        else
            $sql8 .= " ORDER BY reg_date desc";
    }
}else if($cur_win == "we_story"){
    $sql = "select * from Gn_Iam_Info where mem_id = '{$_SESSION['iam_member_id']}'";
    $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $row_iam_info=mysqli_fetch_array($result);

    if($row_iam_info[block_contents]){
        $block_contents_sql = " and c.idx not in ($row_iam_info[block_contents]) ";
    }else{
        $block_contents_sql = "";
    }
    if($row_iam_info[block_user]){
        $b_arr = explode(',', $row_iam_info[block_user]);
        $b_str = "('".implode("','",$b_arr)."')";
        $block_user_sql = " and c.mem_id not in $b_str ";
    }else{
        $block_user_sql = "";
    }

    if($_GET[sort] == 1){
        $search_sql .= " and req_data >= ADDTIME(now(), '-1:0:0') ";
    }else if($_GET[sort] == 2){
        $search_sql .= " and req_data >= DATE(now()) ";
    }else if($_GET[sort] == 3){
        $search_sql .= " and WEEKOFYEAR(req_data) = WEEKOFYEAR(now()) and YEAR(req_data) = YEAR(now()) ";
    }else if($_GET[sort] == 4){
        $search_sql .= " and MONTH(req_data) = MONTH(now()) and YEAR(req_data) = YEAR(now()) ";
    }else if($_GET[sort] == 5){
        $search_sql .= " and YEAR(req_data) = YEAR(now()) ";
    }

    if($_GET[key2] != 0){
        $k = $interest_array[$_GET[key2]-1];
        $search_sql .= " and (contents_title like '%$k%' or contents_desc like '%$k%')";
    }
    if($_GET[key1] == 1){
        $sql8="select c.* from Gn_Iam_Contents c INNER  JOIN  Gn_Member m on c.mem_id=m.mem_id where m.site_iam =  '$bunyang_site' and $search_sql $block_contents_sql $block_user_sql";
    }elseif($_GET[key1] == 2){
        $sql8="select * from Gn_Iam_Contents c where contents_type = 2 and $search_sql $block_contents_sql $block_user_sql";
    }elseif($_GET[key1] == 3){
        $sql8="select * from Gn_Iam_Contents c where contents_type = 3 and $search_sql $block_contents_sql $block_user_sql";
    }elseif($_GET[key1] == 4){
        $sql8="select * from Gn_Iam_Contents c where group_id is not NULL and $search_sql $block_contents_sql $block_user_sql";
    }elseif($_GET[key1] >= 5){
        $k = $rec_array[$_GET[key1]-5];
        $search_sql .= " and (contents_title like '%$k%' or contents_desc like '%$k%')";
        $sql8="select * from Gn_Iam_Contents c where $search_sql $block_contents_sql $block_user_sql";
    }else{
        //공개된 콘텐츠 다 현시
        $sql8="select * from Gn_Iam_Contents c where $search_sql $block_contents_sql $block_user_sql";
    }
    if($_GET[sort] == 11){//게시일짜
        $sql8 .= " ORDER BY req_data desc";
    }elseif($_GET[sort] == 12){//조회수
        $sql8 .= " ORDER BY contents_temp desc";
    }elseif($_GET[sort] == 13){//좋아요
        $sql8 .= " ORDER BY contents_like desc";
    }else{
        $sql8 .= " ORDER BY req_data desc, up_data desc,contents_like desc,contents_temp desc";
    }
    $result8=mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
    $total_cont_count = mysqli_num_rows($result8);

    if($_GET[key1] == 1){                
        $sql8="select c.* from Gn_Iam_Contents c INNER  JOIN  Gn_Member m on c.mem_id=m.mem_id where m.site_iam =  '$bunyang_site' and contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
    }elseif($_GET[key1] == 2){
        $sql8="select * from Gn_Iam_Contents c where contents_type = 2 and contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
    }elseif($_GET[key1] == 3){
        $sql8="select * from Gn_Iam_Contents c where contents_type = 3 and contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
    }elseif($_GET[key1] == 4){
        $sql8="select * from Gn_Iam_Contents c where group_id is not NULL and contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
    }elseif($_GET[key1] >= 5){
        $k = $rec_array[$_GET[key1]-5];
        $search_sql .= " and (contents_title like '%$k%' or contents_desc like '%$k%')";
        $sql8="select * from Gn_Iam_Contents c where contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
    }else{
        //공개된 콘텐츠 다 현시
        $sql8="select * from Gn_Iam_Contents c where contents_westory_display = 'Y' and public_display = 'Y' and $search_sql $block_contents_sql $block_user_sql";
    }
    if($_GET[sort] == 11){//게시일짜
        $sql8 .= " ORDER BY req_data desc";
    }elseif($_GET[sort] == 12){//조회수
        $sql8 .= " ORDER BY contents_temp desc";
    }elseif($_GET[sort] == 13){//좋아요
        $sql8 .= " ORDER BY contents_like desc";
    }else{
        $sql8 .= " ORDER BY req_data desc, up_data desc,contents_like desc,contents_temp desc";
    }
    $result8=mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
    $cont_count = mysqli_num_rows($result8);
    $sql8 .= " limit $contents_count_per_page offset ".$w_offset;
}else if($cur_win == "best_sample"){  
}else if($cur_win == "sample"){
}else if($cur_win == "recent_sample"){    
}else if($cur_win == "group-con"){
    if($gkind != "recommend" && $gkind != "mygroup" && $gkind != "search" && $gkind != "search_con"){
        $g_sql = "select count(*) from gn_group_member where mem_id='{$_SESSION['iam_member_id']}' and group_id = '$gkind'";
        $g_res = mysqli_query($self_con,$g_sql);
        $g_row = mysqli_fetch_array($g_res);
        if($g_row[0] == 0)
            $sql8="select * from Gn_Iam_Contents WHERE card_short_url = '$group_card_url' and group_display = 'Y' and ".$search_sql." ORDER BY group_fix desc,contents_order desc";
        else
            $sql8="select * from Gn_Iam_Contents WHERE card_short_url ='$group_card_url' and ".$search_sql." ORDER BY group_fix desc,contents_order desc";
        $result8=mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
        $cont_count = mysqli_num_rows($result8);
        $sql8 .= " limit $contents_count_per_page offset ".$w_offset;
    }else if($gkind == "recommend"){
        if($other_group != "")
            $sql8="select * from Gn_Iam_Contents WHERE group_id is not NULL and group_id not in (".$other_group.") and group_display = 'Y' and public_display = 'Y' and ".$search_sql." ORDER BY contents_order desc";
        else
            $sql8="select * from Gn_Iam_Contents WHERE group_id is not NULL and group_display = 'Y' and public_display = 'Y' and ".$search_sql." ORDER BY contents_order desc";
        $result8 = mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
        $cont_count = mysqli_num_rows($result8);
        $sql8 .= " limit $contents_count_per_page offset ".$w_offset;
    }else if($gkind == "search_con"){
        $sql8="select * from Gn_Iam_Contents WHERE group_id is not NULL and group_display = 'Y' and public_display = 'Y' and ".$search_sql." ORDER BY contents_order desc";
        $result8 = mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
        $cont_count = mysqli_num_rows($result8);
        $sql8 .= " limit $contents_count_per_page offset ".$w_offset;
    }
}else if($cur_win == "unread_notice" &&  $_SESSION['iam_member_id']!= ""){
    $sql_recv_notice="select * from Gn_Item_Pay_Result WHERE buyer_id = '{$_SESSION['iam_member_id']}' and type='noticerecv' and point_val=3 ORDER BY pay_date desc";
    if(isset($_GET['box']) && $_GET['box'] == "send"){
        $sql_recv_notice="select * from Gn_Item_Pay_Result WHERE buyer_id = '{$_SESSION['iam_member_id']}' and type='noticesend' and point_val=3 ORDER BY pay_date desc";
    }
    $result_notice=mysqli_query($self_con,$sql_recv_notice) or die(mysqli_error($self_con));
    $notice_count = mysqli_num_rows($result_notice);
    $sql_recv_notice .= " limit $contents_count_per_page offset ".$w_offset;
}

$color = array ("#FA8258",
            "#8000FF",
            "#4B8A08",
            "#B404AE");
$receive_sql = "select count(idx) as cnt from Gn_Iam_Contents WHERE (contents_share_text like '%{$_SESSION['iam_member_id']}%') and DATE(up_data) = DATE(now()) and $search_sql ";
$receive_result = mysqli_query($self_con,$receive_sql) or die(mysqli_error($self_con));
$r_row = mysqli_fetch_array($receive_result);

$send_sql = "select count(idx) as cnt from Gn_Iam_Contents WHERE mem_id = '{$_SESSION['iam_member_id']}' and contents_share_text != '' and DATE(up_data) = DATE(now()) and $search_sql ";
$send_result = mysqli_query($self_con,$send_sql) or die(mysqli_error($self_con));
$s_row = mysqli_fetch_array($send_result);

$post_sql = "select count(id) as cnt from Gn_Iam_Post p inner join Gn_Iam_Contents c on p.content_idx = c.idx WHERE c.mem_id = '{$_SESSION['iam_member_id']}' and p.status = 'N' and $search_sql ";
$post_result = mysqli_query($self_con,$post_sql) or die(mysqli_error($self_con));
$p_row = mysqli_fetch_array($post_result);

$sql_sell_service_con = "select count(no) as cnt from Gn_Item_Pay_Result where buyer_id='{$_SESSION['iam_member_id']}' and point_val=1 and site is not null and type='servicebuy' and alarm_state=0";
$sell_result = mysqli_query($self_con,$sql_sell_service_con) or die(mysqli_error($self_con));
$sell_row = mysqli_fetch_array($sell_result);

$sql_notice_unread = "select count(*) as cnt from Gn_Item_Pay_Result where buyer_id='{$_SESSION['iam_member_id']}' and point_val=3 and alarm_state=0 and type='noticerecv'";
$notice_result = mysqli_query($self_con,$sql_notice_unread) or die(mysqli_error($self_con));
$notice_row = mysqli_fetch_array($notice_result);

$share_recv_count = $r_row['cnt'];
$share_send_count = $s_row['cnt'];
$share_post_count = $p_row['cnt'];
$sell_service_con = $sell_row['cnt'];
$notice = $notice_row['cnt'];

$share_count = $r_row['cnt'] + $s_row['cnt'] + $p_row['cnt'] + $sell_row['cnt'] + $notice_row['cnt'];;

// echo $sql8;
// exit;
if($sql8)
    $result8=mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));
    
if(strstr($cur_win,"sample")){}
else if($cur_win == "iam_mall"){}
else{

        while( $contents_row = mysqli_fetch_array($result8)){
            if(!$contents_row['contents_img'])
                $content_images = null;
            else
                $content_images = explode(",",$contents_row['contents_img']);
            for($i = 0; $i < count($content_images); $i++) {
                if (strstr($content_images[$i], "kiam") && !strstr($content_images[$i], $cdn)) {
                    $content_images[$i] = str_replace("http://kiam.kr", "", $content_images[$i]);
                    $content_images[$i] = str_replace("http://www.kiam.kr", "", $content_images[$i]);
                    $content_images[$i] = $cdn . $content_images[$i];
                } else if (!strstr($content_images[$i], "http") && $content_images[$i]) {
                    $content_images[$i] = str_replace("http://kiam.kr", "", $content_images[$i]);
                    $content_images[$i] = str_replace("http://www.kiam.kr", "", $content_images[$i]);
                    $content_images[$i] = $cdn . $content_images[$i];
                }
            }
            //디폴트 아바타
            if ($HTTP_HOST != "kiam.kr") //분양사사이트이면
                $sub_domain = "http://" . $HTTP_HOST;
            else
                $sub_domain = "http://www.kiam.kr";
            $sql = "select n.main_img1 from Gn_Iam_Service s inner join Gn_Iam_Name_Card n on s.profile_idx = n.idx where sub_domain like '%$sub_domain%'";
            $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $row=mysqli_fetch_array($result);
            $default_avatar =  $row['main_img1'];

            //westory 카드
            $sql = "select * from Gn_Iam_Name_Card where idx = '{$contents_row['card_idx']}'";
            $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $westory_card=mysqli_fetch_array($result);
            $sql = "select mem_code from Gn_Member where mem_id = '{$westory_card['mem_id']}'";
            $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $m_row =mysqli_fetch_array($result);
            $m_code = $m_row['mem_code'];
            //콘텐츠에 현시할 이름과 아바타
            $contents_user_name = $G_card['card_name'];
            $contents_card_url = $request_short_url;
            $contents_owner_id = $contents_row['mem_id'];
            $contents_avatar = "";
            if($G_card['main_img1']){
                $contents_avatar = $G_card['main_img1'];
            }else{
                $contents_avatar = $default_avatar;
            }

            //위스토리에서 현시할 이름과 아바타
            if($cur_win == "we_story" || $cur_win == "shared_receive" || $cur_win == "shared_send"){
                $contents_user_name = $westory_card['card_name'];
                $contents_card_url = $westory_card['card_short_url'];
                $card_locked = $westory_card['phone_display'];
                //$contents_owner_id = $contents_row['mem_id'];
                if($westory_card['main_img1'])
                    $contents_avatar = $westory_card['main_img1'];
                else
                    $contents_avatar = $default_avatar;
            }
            //카드가 공개인가 비공개인가?
            if($cur_win == "we_story" ){
                if($card_locked == "N") {
                    echo "<!-- " . $contents_card_url . " 카드가 비공개    -->";
                    echo "<!-- idx = " . $contents_row['idx'] . " | time = " . $contents_row['req_data'] . "-->";
                    echo "<!-- -----------------------------    -->";
                    continue;
                }
                $except_keywords = trim($contents_row[except_keyword]);
                if($except_keywords != "") {
                    $except_count = 0;
                    $except_keywords = explode(",",$except_keywords);
                    for ($index = 0; $index < count($except_keywords); $index++) {
                        $except_keyword = trim($except_keywords[$index]);
                        $except_sql = "select count(*) from Gn_Iam_Contents where mem_id = '{$_SESSION['iam_member_id']}'".
                            " and (contents_title like '%$except_keyword%' or contents_desc like '%$except_keyword%')";
                        $except_result = mysqli_query($self_con,$except_sql);
                        $except_row = mysqli_fetch_array($except_result);
                        $except_count += $except_row[0] * 1;

                        $except_sql = "select count(*) from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}'".
                            " and card_keyword like '%$except_keyword%'";
                        $except_result = mysqli_query($self_con,$except_sql);
                        $except_row = mysqli_fetch_array($except_result);
                        $except_count += $except_row[0] * 1;
                    }
                    if($except_count > 0){
                        echo "<!-- " . $contents_card_url . " 카드가 제외    -->";
                        echo "<!-- idx = " . $contents_row['idx'] . " | time = " . $contents_row['req_data'] . "-->";
                        echo "<!-- -----------------------------    -->";
                        continue;
                    }
                }
            }
            $user_display = "";
            if($contents_row['contents_user_display'] == "N" || ($cur_win != "we_story" && $cur_win != "shared_receive" && $cur_win != "shared_send")){
                $user_display = "display:none";
            }
            if($contents_row['contents_footer_display'] == "N"){
                //$footer_display = "display:none";
            }

            if($_COOKIE[contents_mode] == "pin"){
                $content_list_display = "none";
                $content_image_display = "block";
            }
            else if($_COOKIE[contents_mode] == "image"){
                $content_list_display = "none";
                $content_image_display = "block";
            }
            else{
                $content_list_display = "block";
                $content_image_display = "none";
            }

            $post_sql = "select SQL_CALC_FOUND_ROWS * from Gn_Iam_Post p inner join Gn_Member m on p.mem_id = m.mem_id where p.content_idx = '{$contents_row['idx']}' and p.lock_status = 'N' order by p.reg_date";
            $post_res = mysqli_query($self_con,$post_sql);
            $post_count	=  mysqli_num_rows($post_res);
        ?>
        <div>
            <input type="hidden" id = "<?='contents_display_'.$contents_row['idx']?>" value="<?= $contents_row['contents_display'] ?>">
            <input type="hidden" id = "<?='contents_user_display_'.$contents_row['idx']?>" value="<?= $contents_row['contents_user_display'] ?>">
            <input type="hidden" id = "<?='contents_type_display_'.$contents_row['idx']?>" value="<?= $contents_row['contents_type_display'] ?>">
            <input type="hidden" id = "<?='contents_footer_display_'.$contents_row['idx']?>" value="<?= $contents_row['contents_footer_display'] ?>">
            <input type="hidden" id = "<?='contents_type_'.$contents_row['idx']?>" value="<?= $contents_row['contents_type'] ?>">
            <input type="hidden" id = "<?='contents_title_'.$contents_row['idx']?>" value="<?= $contents_row['contents_title'] ?>">
            <input type="hidden" id = "<?='contents_img_'.$contents_row['idx']?>" value="<?=$contents_row['contents_img']?>">
            <input type="hidden" id = "<?='contents_url_'.$contents_row['idx']?>" value="<?= $contents_row['contents_url'] ?>">
            <input type="hidden" id = "<?='contents_iframe_'.$contents_row['idx']?>" value="<?= str_replace('"', "'",$contents_row['contents_iframe']) ?>">
            <input type="hidden" id = "<?='contents_price_'.$contents_row['idx']?>" value="<?= $contents_row['contents_price'] ?>">
            <input type="hidden" id = "<?='contents_sell_price_'.$contents_row['idx']?>" value="<?= $contents_row['contents_sell_price'] ?>">
            <input type="hidden" id = "<?='contents_desc_'.$contents_row['idx']?>" value="<?=$contents_row['contents_desc']?>">
            <input type="hidden" id = "<?='except_keyword_'.$contents_row['idx']?>" value="<?= $except_keyword?>">
            <input type="hidden" id = "<?='contents_share_text_'.$contents_row['idx']?>" value="<?= $contents_row['contents_share_text'] ?>">
            <input type="hidden" id = "<?='share_names_'.$contents_row['idx']?>" value="<?= $share_names?>">
            <input type="hidden" id = "<?='contents_westory_display_'.$contents_row['idx']?>" value="<?= $contents_row['contents_westory_display'] ?>">
            <input type="hidden" id = "<?='card_short_url_'.$contents_row['idx']?>" value="<?= $contents_row['card_short_url'] ?>">
            <input type="hidden" id = "<?='westory_card_url_'.$contents_row['idx']?>" value="<?= $contents_row['westory_card_url'] ?>">
            <input type="hidden" id = "<?='req_data_'.$contents_row['idx']?>" value="<?= $contents_row['req_data'] ?>">
            <input type="hidden" id = "<?='card_owner_'.$contents_row['idx']?>" value="<?= $card_owner ?>">
            <input type="hidden" id = "<?='contents_user_name_'.$contents_row['idx']?>" value="<?= $contents_user_name ?>">
            <input type="hidden" id = "<?='source_iframe_'.$contents_row['idx']?>" value="<?= $contents_row['source_iframe'];?>">
            <input type="hidden" id = "<?='post_display_'.$contents_row['idx']?>" value="<?= $westory_card['post_display']?>">
            <input type="hidden" id = "<?='contents_like_'.$contents_row['idx']?>" value="<?= number_format($contents_row['contents_like'])?>">
            <input type="hidden" id = "<?='post_count_'.$contents_row['idx']?>" value="<?= $post_count?>">
            <input type="hidden" id = "<?='open_type_'.$contents_row['idx']?>" value="<?= $contents_row['open_type'];?>">
            <div class="content-item list" id="contents_list" style ="display : <?=$content_list_display?>;">
                <div class="user-item">
                    <a href="/?<?=strip_tags($contents_card_url.$m_code)?>" class="img-box" target = "_blank">
                        <div class="user-img">
                            <img src="<?=$contents_avatar ?>" alt="">
                        </div>
                    </a>
                    <div class="wrap" style="width: 50%">
                        <a href="/?<?=strip_tags($contents_card_url.$m_code)?>" class="user-name">
                            <?=$contents_user_name ?>
                        </a>
                        <?if($contents_row['contents_title'] != "") { ?>
                            <div class="title" >
                                <div class="text" style="text-align : left;"><?= $contents_row['contents_title'] ?></div>
                            </div>
                        <?}?>
                        <a href="/?<?=strip_tags($contents_card_url.$m_code)?>" class="date">
                            <?=$contents_row['req_data']?>
                        </a>
                    </div>
                    <div class="media-inner" style="position: absolute; right: 2px; top: 0px; width: 30%;height: 100%;text-align: center;background:<?php echo $color[$cursor%4];?>">
                        <?
                        if((int)$contents_row['contents_type'] == 1 || (int)$contents_row['contents_type'] == 3 ) {
                            if(count($content_images) > 0) {
                                if($contents_row['contents_url']) {?>
                                        <a  onclick="showIframeModal('<?=$contents_row['contents_url']?>')"  target="_blank">
                                        <?if(count($content_images) == 1){?>
                                    <img src="<?=$content_images[0]?>" style = "height : 100%"></a>
                                <?}else{?>
                                            <img src="<?=$content_images[0]?>" style = "height : 100%" onclick = ""></a>
                                        <?}?>
                                <?} else {?>
                                    <?if(count($content_images) == 1){?>
                                        <img src="<?=$content_images[0]?>" style = "height : 100%">
                                    <?}else{?>
                                        <img src="<?=$content_images[0]?>" style = "height : 100%" onclick = "">
                                    <?}?>
                                <?
                                }
                            }
                        } else if((int)$contents_row['contents_type'] == 2) {
                            $img_cnt = count($content_images);
                            // $img_cnt = 0;
                            $vid_array = explode(" ",$contents_row[contents_iframe]);
                            $vid_array[2] = "height=100%";
                            $vid_array[1] = "width=100%";
                            $vid_data = implode(" ",$vid_array);
                            $kk++;
                            if($img_cnt == 0){
                                echo $vid_data;
                            }else{?>
                                <div onclick="play_list<?=$kk;?>();" id="vidwrap_list<?=$kk;?>" style="position:relative;">
                                    <?if(count($content_images) == 1){?>
                                        <img src="<?=$content_images[0]?>" style = "height : 110px;">
                                        <?if($contents_row['contents_img']){?>
                                            <img src="/iam/img/movie_play.png" style="position: absolute; z-index: 50; left: 40%; width: 50px; top: 40%;">
                                        <?}?>
                                    <?}else{?>
                                        <img src="<?=$content_images[0]?>" style = "height : 100%" onclick = "">
                                    <?}?>
                                </div>
                                <script type="text/javascript">
                                    function play_list<?=$kk;?>() {
                                        $('#vidwrap_list<?=$kk;?>').html('<?=$vid_data?>');
                                    }
                                </script>
                            <?
                            }
                        } else if((int)$contents_row['contents_type'] == 4) {
                            $vid_data = $contents_row[source_iframe];
                            $kk++;
                            ?>
                            <div >
                                <iframe src="<?=$vid_data?>" width="100%" height="100%"></iframe>
                            </div>
                        <?
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="content-item" id="contents_image" style ="display : <?=$content_image_display?>;">
                <div class="user-item" style="<?=$user_display ?>">
                    <a href="/?<?=strip_tags($contents_card_url.$m_code)?>" class="img-box"  target = "_blank">
                        <div class="user-img">
                            <img src="<?=$contents_avatar ?>" alt="">
                        </div>
                    </a>
                    <div class="wrap image_mode">
                        <a href="/?<?=strip_tags($contents_card_url.$m_code)?>" class="user-name">
                            <?=$contents_user_name ?>
                        </a>
                        <a href="/?<?=strip_tags($contents_card_url.$m_code)?>" class="date">
                            <?=$contents_row['req_data']?>
                        </a>
                    </div>
                    <div class="wrap pin_mode" style="display:none;">
                        <?if($contents_row['contents_title'] != "") { ?>
                        <a href="/?<?=strip_tags($contents_card_url.$m_code)?>" class="user-name pin-mode">
                            <?=$contents_row['contents_title'] ?>
                        </a>
                        <?}else{?>
                        <a href="/?<?=strip_tags($contents_card_url.$m_code)?>" class="user-name">
                            <?=$contents_user_name ?>
                        </a>
                        <a href="/?<?=strip_tags($contents_card_url.$m_code)?>" class="date">
                            <?=$contents_row['req_data']?>
                        </a>
                        <?}?>
                    </div>
                    <?if( $_SESSION['iam_member_id'] != "" && $_SESSION['iam_member_id'] != $contents_owner_id  ){?>
                        <div class="dropdown" style="position: absolute; right: 10px; top: 8px;">
                            <button class="btn dropdown-toggle westory_dropdown" type="button" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu comunity">
                                <li><a onclick="location.href='/?<?=strip_tags($contents_card_url)?>'">이 콘텐츠 게시자 보기</a></li>
                                <li><a onclick="set_friend('<?=$westory_card['mem_id'] ?>','<?=$westory_card[card_name] ?>','<?=$westory_card[card_short_url] ?>','<?=$westory_card['idx'] ?>')">이 게시자와 프렌즈 하기</a></li>
                                <li><a onclick="set_block_contents('<?=$contents_row['idx']?>')">이 콘텐츠 하나만 감추기</a></li>
                                <li><a onclick="set_block_user('<?=$westory_card['mem_id']?>','<?=$westory_card[card_short_url] ?>')">이 게시자의 정보 감추기</a></li>
                                <li><a onclick="set_my_share_contents('<?=$contents_row['idx']?>')">이 콘텐츠 나에게 가져오기</a></li>
                                <li><a onclick="show_block_list('<?=$contents_row['idx']?>')">감추기 리스트 보기</a></li>
                            </ul>
                        </div>
                    <?}?>
                    <?if( $_SESSION['iam_member_id'] =="" ){?>
                        <div class="dropdown " style="position: absolute; right: 10px; top: 8px;">
                            <button class="btn dropdown-toggle westory_dropdown" type="button" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu comunity">
                                <li><a onclick="javascript:location.href='join.php'">나도 아이엠 만들고 싶어요</a></li>
                                <li><a onclick="javascript:location.href='/?<?=strip_tags($contents_card_url.$card_owner_code)?>'">이 콘텐츠 게시자 보기</a></li>
                                <li><a onclick="iam_mystory('<?=$request_short_url.$card_owner_code?>&cur_win=my_story')" >더 많은 콘텐츠 보러가기</a></li>
                            </ul>
                        </div>
                    <?
                    }
                    $cursor++;
                    ?>
                </div>

                <div class="desc-wrap" style="border-bottom: 1px solid #dddddd;">
                    <?if($contents_row['contents_title'] != "") { ?>
                        <div class="title service_title" style="display: flex;flex-direction: column;justify-content: center">
                            <h3><?= $contents_row['contents_title'] ?></h3>
                        </div>
                    <?
                    }
                    if((int)$contents_row['contents_type'] == 3) {?>
                        <div class="desc is-product">
                            <div class="desc-inner">
                                <div class="outer">
                                    <?if($contents_row['contents_price'] > 0){
                                        $discount = 100 - ($contents_row['contents_sell_price'] / $contents_row['contents_price']) * 100;?>
                                        <div class="percent"><?=(int)$discount?>%</div>
                                        <div class="price">
                                            <span class="upper"><?=number_format($contents_row['contents_price'])?>원</span>
                                            <span class="downer"><?=number_format($contents_row['contents_sell_price'])?>원</span>
                                        </div>
                                    <?}else{?>
                                        <div class="percent">0%</div>
                                        <div class="price">
                                            <span class="upper"><?=number_format($contents_row['contents_sell_price'])?>원</span>
                                            <span class="downer"><?=number_format($contents_row['contents_sell_price'])?>원</span>
                                        </div>
                                    <?}?>
                                    <div class="order" >
                                        <?if($_SESSION['iam_member_id']){
                                            $price_service = $contents_row['contents_sell_price'];
                                            $name_service = $contents_row['contents_title'];
                                            $sellerid_service = $contents_row['mem_id'];
                                            $contents_url = $contents_row['contents_url'];
                                            $pay_link = "pay_spgd.php?item_name=". $contents_row['contents_title'].'&item_price='.$contents_row['contents_sell_price'].'&manager='.$contents_row['mem_id'];
                                        ?>
                                        <div class="dropdown" style="float:right;">
                                            <a class = "dropdown-toggle" data-toggle="dropdown" expanded = "false" style="background:blue;cursor:pointer;">구매하기</a>
                                            <ul class="dropdown-menu buy_servicecon">
                                                <li>
                                                    <a href="<?=$pay_link?>" target="_blank" style="font-size: 15px;background-color:blue;">카드결제</a>
                                                </li>
                                                <li>
                                                    <a onclick="point_settle_modal(<?=$contents_row['contents_sell_price']?>, '<?=$contents_row['contents_title']?>', '<?=$contents_row['idx']?>', '<?=$contents_row['mem_id']?>')" style="font-size: 15px;background-color:blue;">포인트결제</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <?}else{?>
                                            <a href="<?echo '/iam/login.php?contents_idx='. $contents_row['idx']?>" target="_self" style="background:blue;">구매하기</a>
                                        <?}?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?}
                    if($contents_row['contents_desc']) {?>
                        <div class="desc" >
                            <div class="desc-inner desc-text desc-inner-content" style="font-size:15px;<?if(count($content_images) == 0) echo 'height:125px;'?>">
                                <?=nl2br($contents_row['contents_desc'])?>
                            </div>
                            <a class="arrow" style="color:#669933;font-weight:bold;cursor:pointer">
                                [더 보기]
                            </a>
                        </div>
                    <?}?>
                </div>
                <div class="media-wrap" >
                    <?
                    $except_keyword = $contents_row['except_keyword'];
                    $except_keyword = str_replace('"', urlencode('"'), $except_keyword);
                    $except_keyword = str_replace("'", urlencode("\'"), $except_keyword);
                    if($_SESSION['iam_member_id'] == $card_owner) {
                        if( $cur_win == "my_story") { ?>
                            <span class="pull-left " style="position:absolute;left:10px;top:10px;"><?=$contents_row['req_data']?></span>
                        <?}
                    }?>
                    <div class="media-inner" <?if($contents_row['contents_type'] == 1 && count($content_images) == 0) echo "style = 'min-height :30px'";?>>
                        <?php
                        if(($cur_win == "we_story" || strpos($request_url, "?") === false) && $_SESSION['iam_member_id'] != $contents_row['mem_id']){
                        ?>
                        <a href="javascript:showSNSModal_byContents('<?=$contents_row['idx']?>',
                                            '<?=addslashes(htmlspecialchars($westory_card[card_name],ENT_COMPAT,'UTF-8'))?>',
                                            '<?=addslashes(htmlspecialchars($contents_row[contents_title],ENT_COMPAT,'UTF-8'))?>',
                                            '<?=addslashes(htmlspecialchars($contents_row[contents_desc],ENT_COMPAT,'UTF-8'))?>',
                                            '<?=$contents_row[contents_img]?>');" style="position: absolute;font-size: 20px;top:5px;right: 10px;border-radius: 5px;line-height: 20px;z-index:10;background:white">
                            <img src="img/icon_share-android_black.png"  width="20px">
                        </a>
                        <?} else{?>
                        <a href="javascript:show_contents_utils(<?=$contents_row['idx']?>);" 
                            style="position: absolute;font-size: 20px;top:5px;right: 10px;border-radius: 5px;line-height: 20px;z-index:10;background:white" title="">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <div class = "utils-index" id="<?='utils_index_'.$contents_row['idx']?>" style="display:none;right:30px;z-index:10;background:white;border-radius: 5px;">
                            <?
                            if ($cur_win != "we_story" && $_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) { ?>
                                <a href="javascript:contents_add('<?= $card_owner ?>','<?= $contents_row[contents_order]?>',<?=$my_first_card?>);" style="margin-left: 10px;">
                                    <i class="fa fa-plus" aria-hidden="true"></i></a>
                            <?  }
                            if ($_SESSION['iam_member_id'] && ($_SESSION['iam_member_id'] == $contents_row['mem_id'] || $_SESSION['iam_member_id'] == $group_manager)) {
                                if (!$cur_win || $cur_win == "my_info") { ?>
                                    <a href="javascript:contents_range_down('<?= $contents_row['idx'] ?>', '<?= $contents_row['contents_order'] ?>','<?= $G_card[card_short_url] ?>');">
                                        <i class="fa fa-arrow-down" aria-hidden="true"></i></a>
                                    <a href="javascript:contents_range_up('<?= $contents_row['idx'] ?>', '<?= $contents_row['contents_order'] ?>','<?= $G_card[card_short_url] ?>');">
                                        <i class="fa fa-arrow-up" aria-hidden="true"></i></a>
                                <?  }?>
                                <a href="javascript:contents_del('<?= $contents_row['idx'] ?>');"><i class="fa fa-minus" aria-hidden="true"></i></a>
                                <?if($_SESSION['iam_member_id'] == $group_manager){?>
                                    <a href="javascript:contents_fix('<?= $contents_row['idx'] ?>');"><img src="<?=$contents_row[group_fix] > 0?'img/main/icon-pin.png':'img/main/icon-graypin.png'?>" width="24"></a>
                                <?}?>
                                <?if($_SESSION['iam_member_id'] == $contents_row['mem_id']){
                                    $share_ids = explode(",",$contents_row['contents_share_text']);
                                    for ($index = 0; $index < count($share_ids); $index++) {
                                        $share_sql = "select mem_name from Gn_Member where mem_id = '$share_ids[$index]'";
                                        $share_result = mysqli_query($self_con,$share_sql);
                                        $share_row = mysqli_fetch_array($share_result);
                                        $share_names[$index] = htmlspecialchars($share_row['mem_name']);
                                    }
                                    $share_names = implode(",",$share_names);
                                    ?>
                                    <a href="javascript:contents_edit('<?= $contents_row['idx'] ?>');">
                                        <img src="img/icon_edit.svg" width="24">
                                    </a>
                                    <?if($is_test_version){?>
                                        <a href="javascript:show_con_send('<?=$contents_row['idx']?>')">
                                            <img src="img/main/icon_con_send.png"  width="20px">
                                        </a>
                                <?  }
                                }?>
                            <?}?>
                            <?  if($cur_win == "shared_receive"){?>
                                <a href="javascript:remove_shared_content('<?=$contents_row['idx']?>','<?=$_SESSION['iam_member_id']?>');" style="margin-left: -5px">
                                    <img src="img/main/icon-hide.png"  width="24">
                                </a>
                            <?  }?>
                            <a href="javascript:showSNSModal_byContents('<?=$contents_row['idx']?>',
                                                '<?=addslashes(htmlspecialchars($westory_card[card_name],ENT_COMPAT,'UTF-8'))?>',
                                                '<?=addslashes(htmlspecialchars($contents_row[contents_title],ENT_COMPAT,'UTF-8'))?>',
                                                '<?=addslashes(htmlspecialchars($contents_row[contents_desc],ENT_COMPAT,'UTF-8'))?>',
                                                '<?=$contents_row[contents_img]?>');" style="margin-left: -5px">
                                <img src="img/icon_share-android_black.png"  width="20px">
                            </a>
                        </div>
                        <?}?>
                        <?if((int)$contents_row['contents_type'] == 1 || (int)$contents_row['contents_type'] == 3 ) {
                            if($contents_row['contents_url']) {
                                $kk++;
                            if(count($content_images) > 1){
                        ?>
                                <button onclick="show_all_content_images('<?=$contents_row['idx']?>')"  id = "content_all_image<?=$contents_row['idx']?>"
                                        style="position: absolute;right:0px;bottom:0px;font-size: 24px"><?="+".(count($content_images)-1)?></button>
                                <button onclick="hide_all_content_images('<?=$contents_row['idx']?>')"  id = "hide_content_all_image<?=$contents_row['idx']?>"
                                        style="position: absolute;left:0px;top:300px;font-size: 24px;display:none;background: transparent">
                                    <img src="img/main/icon-img_fold.png" style="width:30px">
                                </button>
                        <?  }
                        if($cur_win == 'we_story' && $_COOKIE[contents_mode] == "pin"){?>
                        <a href='/iam/contents.php?contents_idx=<?=$contents_row['idx']?>' target="_blank">
                        <img src="<?=$content_images[0]?>" class="contents_img">
                            <?if(count($content_images) > 1){?>
                                <?for($c = 1;$c < count($content_images);$c ++){?>
                                    <img src="<?=$content_images[$c]?>" class="contents_img hidden_image<?=$contents_row['idx']?>" style="display:none">
                                <?}?>
                            <?}?>
                        </a>
                    <?}else{
                        if((int)$contents_row['open_type'] == 1){?>
                            <div  onclick="showpage<?=$kk?>('<?=$contents_row['contents_url']?>')" id="pagewrap<?=$kk?>">
                            <img src="<?=$content_images[0]?>" class="contents_img">
                                <?if(count($content_images) > 1){?>
                                    <?for($c = 1;$c < count($content_images);$c ++){?>
                                        <img src="<?=$content_images[$c]?>" class="contents_img hidden_image<?=$contents_row['idx']?>" style="display:none">
                                    <?}?>
                                <?}?>
                            </div>
                        <?} else{?>
                            <a href='<?=$contents_row['contents_url']?>' target="_blank" id="pagewrap<?=$kk?>">
                            <img src="<?=$content_images[0]?>" class="contents_img">
                                <?if(count($content_images) > 1){?>
                                    <?for($c = 1;$c < count($content_images);$c ++){?>
                                        <img src="<?=$content_images[$c]?>" class="contents_img hidden_image<?=$contents_row['idx']?>" style="display:none">
                                    <?}?>
                                <?}?>
                            </a>
                        <?}
                        }?>
                            <script type="text/javascript">
                                function showpage<?=$kk;?>(url) {
                                    document.getElementById('pagewrap<?=$kk;?>').innerHTML = '<iframe src="'+url+'" width="100%" height="600px" sandbox="allow-same-origin allow-scripts allow-popups allow-forms allow-top-navigation"></iframe>';
                                }
                            </script>
                        <?} else {
                                if($cur_win == 'we_story' && $_COOKIE[contents_mode] == "pin"){?>
                                    <a href='/iam/contents.php?contents_idx=<?=$contents_row['idx']?>' target="_blank">
                                    <img src="<?=$content_images[0]?>" class="contents_img">
                                        <?if(count($content_images) > 1){?>
                                            <?for($c = 1;$c < count($content_images);$c ++){?>
                                                <img src="<?=$content_images[$c]?>" class="contents_img hidden_image<?=$contents_row['idx']?>" style="display:none">
                                            <?}?>
                                        <?}?>
                                    </a>    
                        <?} else {?>
                            <img src="<?=$content_images[0]?>" class="contents_img">
                        <?}?>
                                <?if(count($content_images) > 1){?>
                                    <button onclick="show_all_content_images('<?=$contents_row['idx']?>')"  id = "content_all_image<?=$contents_row['idx']?>"
                                            style="position: absolute;right:0px;bottom:0px;font-size: 24px"><?="+".(count($content_images)-1)?></button>
                                    <button onclick="hide_all_content_images('<?=$contents_row['idx']?>')"  id = "hide_content_all_image<?=$contents_row['idx']?>"
                                            style="position: absolute;left:0px;top:300px;font-size: 24px;display:none;background: transparent">
                                        <img src="img/main/icon-img_fold.png" style="width:30px">
                                    </button>
                                    <?for($c = 1;$c < count($content_images);$c ++){?>
                                        <img src="<?=$content_images[$c]?>" class="contents_img hidden_image<?=$contents_row['idx']?>" style="display:none">
                                    <?}
                                }
                            }
                        } else if((int)$contents_row['contents_type'] == 2) {
                        $contents_movie = true;
                        if(!$contents_row['contents_img']){
                            $contents_movie = false;
                            echo $contents_row['contents_iframe'];
                        }else{
                        $kk++;
                        ?>
                        <?if(count($content_images) > 1){?>
                            <button onclick="show_all_content_images('<?=$contents_row['idx']?>')"  id = "content_all_image<?=$contents_row['idx']?>"
                                    style="position: absolute;right:0px;bottom:0px;font-size: 24px"><?="+".(count($content_images)-1)?></button>
                            <button onclick="hide_all_content_images('<?=$contents_row['idx']?>')"  id = "hide_content_all_image<?=$contents_row['idx']?>"
                                    style="position: absolute;left:0px;top:300px;font-size: 24px;display:none;background: transparent">
                                <img src="img/main/icon-img_fold.png" style="width:30px">
                            </button>
                        <?}
                        if($cur_win == 'we_story' && $_COOKIE[contents_mode] == "pin"){?>
                            <a href='/iam/contents.php?contents_idx=<?=$contents_row['idx']?>' target="_blank">
                            <img src="<?=$content_images[0]?>" class="contents_img">
                            <img class="movie_play" src="/iam/img/movie_play.png">
                                <?if(count($content_images) > 1){?>
                                    <?for($c = 1;$c < count($content_images);$c ++){?>
                                        <img src="<?=$content_images[$c]?>" class="contents_img hidden_image<?=$contents_row['idx']?>" style="display:none">
                                    <?}?>
                                <?}?>
                            </a>
                        <?}else{
                        
                        if((int)$contents_row['open_type'] == 1){?>
                            <div onclick="play<?=$kk;?>();" id="vidwrap<?=$kk;?>" style="position: relative;">
                                <img src="<?=$content_images[0]?>" class="contents_img">
                                <?if($contents_movie){?>
                                    <img class="movie_play" src="/iam/img/movie_play.png">
                                <?}?>
                                <?if(count($content_images) > 1){?>
                                    <?for($c = 1;$c < count($content_images);$c ++){?>
                                        <img src="<?=$content_images[$c]?>" class="contents_img hidden_image<?=$contents_row['idx']?>" style="display:none">
                                    <?}?>
                                <?}?>
                            </div>
                        <?}else{?>
                            <a href="<?=$contents_row[contents_url]?>" target="_blank" id="vidwrap<?=$kk;?>" style="position: relative;">
                                <img src="<?=$content_images[0]?>" class="contents_img">
                                <?if($contents_movie){?>
                                    <img class="movie_play" src="/iam/img/movie_play.png">
                                <?}?>
                                <?if(count($content_images) > 1){?>
                                    <?for($c = 1;$c < count($content_images);$c ++){?>
                                        <img src="<?=$content_images[$c]?>" class="contents_img hidden_image<?=$contents_row['idx']?>" style="display:none">
                                    <?}?>
                                <?}?>
                            </a>
                        <?}
                        }?>
                            <script type="text/javascript">
                                function play<?=$kk;?>() {
                                    document.getElementById('vidwrap<?=$kk;?>').innerHTML = '<?=$contents_row[contents_iframe]?>';
                                }
                            </script>
                        <?
                        }
                    } else if((int)$contents_row['contents_type'] == 4) {
                        if($cur_win == 'we_story' && $_COOKIE[contents_mode] == "pin"){?>
                            <a href='/iam/contents.php?contents_idx=<?=$contents_row['idx']?>' target="_blank">
                            <img src="<?=$content_images[0]?>" class="contents_img">
                                <?if(count($content_images) > 1){?>
                                    <?for($c = 1;$c < count($content_images);$c ++){?>
                                        <img src="<?=$content_images[$c]?>" class="contents_img hidden_image<?=$contents_row['idx']?>" style="display:none">
                                    <?}?>
                                <?}?>
                            </a>
                        <?}else{
                        if((int)$contents_row['open_type'] == 1){?>
                        <div onclick="play<?=$kk;?>();" id="vidwrap<?=$kk;?>">
                            <?if($content_images[0]){?>
                            <img src="<?=$content_images[0]?>" class="contents_img">
                            <iframe src="<?=$vid_data?>" style="width:100%;height: 600px;display:none"></iframe>
                            <?}else{?>
                                <iframe src="<?=$vid_data?>" style="width:100%;height: 600px;"></iframe>
                            <?}?>
                        </div>
                        <?}else{?>
                        <a href="<?=$contents_row[contents_url]?>" id="vidwrap<?=$kk;?>">
                            <?if($content_images[0]){?>
                            <img src="<?=$content_images[0]?>" class="contents_img">
                            <iframe src="<?=$vid_data?>" style="width:100%;height: 600px;display:none"></iframe>
                            <?}else{?>
                                <iframe src="<?=$vid_data?>" style="width:100%;height: 600px;"></iframe>
                            <?}?>
                        </a>
                        <?}
                        }?>
                        <script type="text/javascript">
                            function play<?=$kk;?>() {
                                $('#vidwrap<?=$kk;?> > iframe').css("display","block");
                                $('#vidwrap<?=$kk;?> > img').css("display","none");
                            }
                        </script>
                    <?}?>
                    </div>
                </div>
                <div class="info-wrap" 
                    <?
                    if($contents_row['contents_type'] == 1 && count($content_images) == 0) 
                        echo "style = 'min-height :30px;border:none;'";
                    else
                        echo "style = 'border:none;border-top:1px solid #dddddd;'";
                    ?>>
                    <?if($contents_row['contents_type'] == 2){
                        if(strstr($contents_row['contents_url'],"youtube.com"))
                            $video_type = "youtube.com";
                        else if(strstr($contents_row['contents_url'],"blog.naver.com"))
                            $video_type = "blog.naver.com"; 
                        else if(strstr($contents_row['contents_url'],"cafe.naver.com"))
                            $video_type = "cafe.naver.com"; 
                        else if(strstr($contents_row['contents_url'],"jungto.org"))
                            $video_type = "jungto.org"; 
                        else                              
                            $video_type = $contents_row['contents_url'];?>
                        <div class="media-tit" style="padding-bottom:0px;font-size: 16px; font-weight: bold;">
                            <a href="<?=$contents_row['contents_url']?>" target="_blank"><?=$video_type?></a>
                            <br><?=$contents_row['contents_url_title']?>
                        </div>
                    <?}?>
                    <div class="second-box" style="<?if($westory_card[post_display] == 0) echo('display:none !important');?>">
                        <div class="in-box">
                            <div style="display: flex;vertical-align: middle">
                                <a  class = "hand" href="javascript:contents_like('<?=$contents_row['idx']?>');">
                                    <img src="img/icon_thumbs-up.svg" width="30px" alt="">
                                </a>
                                <?if($contents_row['contents_like']) {?>
                                    <p class = "second-box-like like-count like_<?=$contents_row['idx']?>">
                                        <?=number_format($contents_row['contents_like'])?>
                                    </p>
                                <?} else {?>
                                    <p class = "second-box-like like-count like_<?=$contents_row['idx']?>">0</p>
                                <? } ?>
                                &nbsp;&nbsp;&nbsp;
                                <a href="javascript:show_post('<?=$contents_row['idx']?>');" class="hand">
                                    <img src="img/main/icon-16.png" height="30px" alt="">
                                    <label style="font-size: 14px;background: #ff3333;border-radius: 50%!important;padding: 3px 7px!important;color: #fff;
                                            text-align: center;line-height: 1;position: absolute;margin-left: -15px" id = "<?='post_alarm_'.$contents_row['idx']?>"></label>
                                </a>
                                <p onclick = "refresh_post('<?=$contents_row['idx']?>')" class = "second-box-like like-count" id = "<?='post_count_'.$contents_row['idx']?>"><?=$post_count?>  &#x21BA;</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?
                $post_status_sql = "select count(*) from Gn_Iam_Post where content_idx = '{$contents_row['idx']}' and status = 'N' and lock_status = 'N'";
                $post_status_res = mysqli_query($self_con,$post_status_sql);
                $post_status_row =  mysqli_fetch_array($post_status_res);
                $post_status_count = $post_status_row[0];
                if ($post_status_count  > 0)
                    echo "<script>  $('#post_alarm_".$contents_row['idx']."').html(".$post_status_count."); </script>";
                else
                    echo "<script>  $('#post_alarm_".$contents_row['idx']."').hide(); </script>";
                ?>
                <div class="post-wrap <?='post_wrap'.$contents_row['idx']?>" style="display:none" id = "<?='post_wrap'.$contents_row['idx']?>">
                    <div style="border: 1px solid #dddddd">
                        <textarea id = "post_content<?=$contents_row['idx']?>" name = "post_content<?=$contents_row['idx']?>" class  = "post_content" maxlength="300" placeholder="댓글은 300자 이내로 작성해주세요" style="width: 100%;border: 1px"></textarea>
                    </div>
                    <div style="display: flex">
                        <span id = "post_status" name = "post_status" style="padding: 10px">0/300</span>
                        <button type="button" class="btn btn-primary" style="position: absolute; right: 1px; padding: 9px 12px" id="send_post" onclick="add_post('<?=$contents_row['idx']?>')">등록</button>
                    </div>
                    <div style="border: 1px solid #dddddd" id = "<?='post_list_'.$contents_row['idx']?>" name = "<?='post_list_'.$contents_row['idx']?>">
                        <?while($post_row = mysqli_fetch_array($post_res)){
                            $post_card_sql = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$post_row['mem_id']}' order by req_data asc";
                            $post_card_result = mysqli_query($self_con,$post_card_sql);
                            $post_card_row = mysqli_fetch_array($post_card_result);
                            ?>
                            <div class="user-item" id="<?='post_reply'.$post_row['id']?>">
                                <a href="/?<?=strip_tags($post_card_row['card_short_url'])?>" class="img-box">
                                    <div class="user-img" style="margin: 5px">
                                        <?if($post_row[profile]){?>
                                            <img src="<?=$post_row[profile] ?>" alt="">
                                        <?}else{?>
                                            <img src="img/profile_img.png" alt="">
                                        <?}?>
                                    </div>
                                </a>
                                <div class="wrap">
                                    <span class="date">
                                        <?=$post_row['mem_name'] ?>&nbsp;&nbsp;&nbsp;<?=$post_row['reg_date']?>
                                    </span>
                                    <span class="user-name">
                                        <?=$post_row['content']?>
                                    </span>
                                </div>
                                <?if($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $post_row['mem_id']){?>
                                    <div class="dropdown" style="top : 10px;position : absolute;right:0px">
                                        <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/main/custom.png" style="height: 20px;">
                                        <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                            <li>
                                                <a href="javascript:void(0)" onclick="edit_post('<?=$contents_row['idx']?>','<?=$post_row['id']?>','<?=$post_row['content']?>')" title="댓글 수정">
                                                    <p>수정</p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" onclick="delete_post('<?=$contents_row['idx']?>','<?=$post_row['id']?>')" title="댓글 삭제">
                                                    <p>삭제</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                <?}else if($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $contents_row['mem_id']){?>
                                    <div class="dropdown" style="top : 10px;position : absolute;right:0px">
                                        <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/main/custom.png" style="height: 20px;">
                                        <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                            <li>
                                                <a href="javascript:void(0)" onclick="delete_post('<?=$contents_row['idx']?>','<?=$post_row['id']?>')" title="댓글 삭제">
                                                    <p>삭제</p>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0)" onclick="lock_post('<?=$contents_row['idx']?>','<?=$post_row['id']?>')" title="댓글 차단">
                                                    <p>차단</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                <?}?>
                                <div style="position: absolute;left: 10px;bottom: 0px">
                                    <span style="color: #a3bfe2;cursor:pointer" onclick="show_post_reply(<?=$post_row['id']?>);">
                                        &#x2198;답글달기
                                    </span>
                                </div>
                            </div>
                            <div id = "<?='post_reply_'.$post_row['id']?>"  class = "post_reply_wrap" style="display: none;" >
                                <div style="border-bottom: 1px solid #dddddd">
                                    <textarea id = "<?='post_reply_'.$post_row['id'].'_content'?>" name = "<?='post_reply_'.$post_row['id'].'_content'?>" class  = "post_reply_content" maxlength="300" placeholder="답글은 300자 이내로 작성해주세요" style="width: 100%;border: 1px"></textarea>
                                </div>
                                <div style="display: flex;border-bottom: 1px solid #dddddd">
                                    <span id = "post_reply_status" name = "post_reply_status" style="padding: 10px">0/300</span>
                                    <button type="button" class="btn btn-primary" style="position: absolute; right: 1px; padding: 9px 12px" onclick="add_post_reply('<?=$contents_row['idx']?>','<?=$post_row['id']?>')">등록</button>
                                </div>
                            </div>
                            <?
                            $reply_sql = "select * from Gn_Iam_Post_Response r inner join Gn_Member m on r.mem_id = m.mem_id where r.post_idx = '{$post_row['id']}' order by r.reg_date";
                            $reply_res = mysqli_query($self_con,$reply_sql);
                            while($reply_row = mysqli_fetch_array($reply_res)){
                                $reply_card_sql = "select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$reply_row['mem_id']}' order by req_data asc";
                                $reply_card_result = mysqli_query($self_con,$reply_card_sql);
                                $reply_card_row = mysqli_fetch_array($reply_card_result);
                                ?>
                                <div class="user-item" style="padding-left: 30px">
                                    <a href="/?<?=strip_tags($reply_card_row['card_short_url'])?>" class="img-box">
                                        <div class="user-img" style="margin: 5px">
                                            <?if($reply_row[profile]){ ?>
                                                <img src="<?=$reply_row[profile] ?>" alt="">
                                            <?}else{?>
                                                <img src="img/profile_img.png" alt="">
                                            <?}?>
                                        </div>
                                    </a>
                                    <div class="wrap">
                                            <span class="date">
                                                <?=$reply_row['mem_name'] ?>&nbsp;&nbsp;&nbsp;<?=$reply_row['reg_date']?>
                                            </span>
                                            <span class="user-name" id = "<?='reply_list_'.$reply_row['id']?>">
                                                <?=$reply_row['contents']?>
                                            </span>
                                    </div>
                                    <?if($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $post_row['mem_id']){?>
                                        <div class="dropdown" style="top : 10px;position : absolute;right:0px">
                                            <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/main/custom.png" style="height: 20px;">
                                            <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                                <li>
                                                    <a href="javascript:void(0)" onclick="edit_post_reply('<?=$contents_row['idx']?>','<?=$post_row['id']?>','<?=$reply_row['id']?>','<?=$reply_row['contents']?>')" title="답글 수정">
                                                        <p>수정</p>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" onclick="delete_post_reply('<?=$contents_row['idx']?>','<?=$reply_row['id']?>')" title="답글 삭제">
                                                        <p>삭제</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    <?}else if($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $contents_row['mem_id']){?>
                                        <div class="dropdown" style="top : 10px;position : absolute;right:0px">
                                            <img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/main/custom.png" style="height: 20px;">
                                            <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
                                                <li>
                                                    <a href="javascript:void(0)" onclick="delete_post_reply('<?=$contents_row['idx']?>','<?=$reply_row['id']?>')" title="답글 삭제">
                                                        <p>삭제</p>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" onclick="lock_post_reply('<?=$contents_row['idx']?>','<?=$reply_row['id']?>')" title="답글 차단">
                                                        <p>차단</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    <?}?>
                                </div>
                            <?}
                        }?>
                    </div>
                </div>
            </div>
        </div>
    <?  }//while
}

?>
