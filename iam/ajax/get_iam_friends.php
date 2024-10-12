
<?
include_once "../../lib/rlatjd_fun.php";

$card_owner = $_GET['card_owner'];
$card_master = $_GET['card_master'];
$phone_count = $_GET['phone_count'];
$search_range2 = $_GET['search_range2'];
$search_str2 = $_GET['search_str2'];
$search_type = $_GET['search_type'];
$show_all = $_GET['show_all'];
                         
$site_info_friends = explode(".",$HTTP_HOST);
$site_name_friends = $site_info_friends[0];
if($site_name_friends == "www"){
    $site_name_friends = "kiam";
}
if(is_null($search_str2)) {
    $search_str2 = "";
}
if(!$search_type) {
    if($_SESSION['iam_member_id'] == $card_owner) {
        $search_type = 2;
    } else {
        $search_type = 1;
    }
}
if($search_type == 3 || $search_type == 4){
    if($show_all == "N"){
        $style_list = "";
        $style_page = "";
    }
    else{
        $style_list = "height: 700px;overflow: auto;margin-bottom: 20px;";
        $style_page = "display:none;";
    }
}

$body = '<div class="contact-list" style="'. $style_list. '">';
$body .= '<ul>';

if($search_str2 !== "") {
    if((int)$search_type == 2) {
        $friends_sql_msg = "and (card_title like '%$search_str2%' or card_name like '%$search_str2%' or card_company like '%$search_str2%' or card_position like '%$search_str2%' or card_keyword like '%$search_str2%')";
    }
    else if((int)$search_type == 3 || (int)$search_type == 4){
        $friends_sql_msg = "and (a.card_title like '%$search_str2%' or a.card_name like '%$search_str2%' or a.card_company like '%$search_str2%' or a.card_position like '%$search_str2%' or a.card_keyword like '%$search_str2%')";
    } else {
        $friends_sql_msg = "and (friends_name like '%$search_str2%' or friends_phone like '%$search_str2%')";
    }
} else {
    $friends_sql_msg = "";
}

if((int)$search_type == 2) {
    $sql5="select count(idx) from Gn_Iam_Name_Card where group_id is NULL and phone_display = 'Y' $friends_sql_msg";
}
else if((int)$search_type == 3){
    $sql5="select count(idx) from Gn_Iam_Name_Card a inner join Gn_Member b on a.mem_id=b.mem_id where a.group_id is NULL and a.phone_display = 'Y' and b.site='$site_name_friends' $friends_sql_msg";
}
else if((int)$search_type == 4){
    $sql5="select count(idx) from Gn_Iam_Name_Card a inner join Gn_Member b on a.mem_id=b.mem_id where b.recommend_id='{$_SESSION['iam_member_id']}' and a.group_id is NULL and a.phone_display = 'Y' $friends_sql_msg";
} else {
    $sql5="select count(idx) from Gn_Iam_Friends where mem_id = '$card_owner' $friends_sql_msg";
}
if(!$global_is_local){
    $redisCache = new RedisCache();
    $comment_row5 = $redisCache->get_query_to_data($sql5);
}else{
    $result5=mysqli_query($self_con,$sql5);
    $comment_row5=mysqli_fetch_array($result5);
}

$row_num5 = $comment_row5[0];
$list2 = 10; //한 페이지에 보여줄 개수
$block_ct2 = 10; //블록당 보여줄 페이지 개수

if($_GET['page2']) {
    $page2 = $_GET['page2'];
} else {
    $page2 = 1;
}

$block_num2 = ceil($page2/$block_ct2); // 현재 페이지 블록 구하기
$block_start2 = (($block_num2 - 1) * $block_ct2) + 1; // 블록의 시작번호
$block_end2 = $block_start2 + $block_ct2 - 1; //블록 마지막 번호
$total_page2 = ceil($row_num5 / $list2); // 페이징한 페이지 수 구하기
if($block_end2 > $total_page2) $block_end2 = $total_page2; //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
$total_block2 = ceil($total_page2/$block_ct2); //블럭 총 개수
$start_num2 = ($page2-1) * $list2; //시작번호 (page-1)에서 $list를 곱한다.

if((int)$search_range2 == 1) {
    $friends_sql_msg = $friends_sql_msg. " order by friends_name";
} else if((int)$search_range2 == 2) {
    $friends_sql_msg = $friends_sql_msg. " order by friends_name desc";
} else if((int)$search_range2 == 3) {
    $friends_sql_msg = $friends_sql_msg. " order by idx desc";
} else if((int)$search_range2 == 4) {
    $friends_sql_msg = $friends_sql_msg. " order by idx";
} else {
    $friends_sql_msg = $friends_sql_msg. " order by idx desc";
}

if($show_all == "N"){
    $limit_str = "limit " . $start_num2 . ", " . $list2;
}
else{
    $limit_str = "";
}

if((int)$search_type == 2) {
    $sql6="select idx as friends_card_idx, card_short_url as friends_url, card_name as friends_name,
                card_company as friends_company, card_phone as friends_phone from Gn_Iam_Name_Card where group_id is NULL and phone_display = 'Y' $friends_sql_msg " . $limit_str;
} else if((int)$search_type == 3) {
    $sql6="select a.idx as friends_card_idx, a.card_short_url as friends_url, a.card_name as friends_name,
                a.card_company as friends_company, a.card_phone as friends_phone from Gn_Iam_Name_Card a inner join Gn_Member b on a.mem_id=b.mem_id where group_id is NULL and phone_display = 'Y' and b.site_iam='$site_name_friends' $friends_sql_msg " . $limit_str;// limit $start_num2, $list2  height: 800px;overflow: auto;
} else if((int)$search_type == 4){
    $sql6="select a.idx as friends_card_idx, a.card_short_url as friends_url, a.card_name as friends_name,
                a.card_company as friends_company, a.card_phone as friends_phone from Gn_Iam_Name_Card a inner join Gn_Member b on a.mem_id=b.mem_id where b.recommend_id='{$_SESSION['iam_member_id']}' and group_id is NULL and phone_display = 'Y' $friends_sql_msg " . $limit_str;// limit $start_num2, $list2  height: 800px;overflow: auto;
} else {
    $sql6="select * from Gn_Iam_Friends where mem_id = '$card_owner' $friends_sql_msg " . $limit_str;
}
//file_put_contents("iamlog.txt", $sql6 . "\n", FILE_APPEND);
$cont_array = array();
if(!$global_is_local){
    //$redisCache = new RedisCache();
    $cont_list = $redisCache -> get_query_to_array($sql6);
    for($i=0 ; $i < count($cont_list); $i++){
        array_push($cont_array,$cont_list[$i]);
    }
}else{
    $result6 = mysqli_query($self_con,$sql6) or die(mysqli_error($self_con));
    while( $contents_row = mysqli_fetch_array($result6)){
        array_push($cont_array,$contents_row);
    }
}
foreach($cont_array as $row6){
    $diplay_sql="select main_img1 ,card_name as friends_name, card_company as friends_company, card_phone as friends_phone, phone_display, mem_id from Gn_Iam_Name_Card use index(idx) where idx = '$row6[friends_card_idx]'";
    $diplay_result=mysqli_query($self_con,$diplay_sql);
    $diplay_row=mysqli_fetch_array($diplay_result);

    $friend_sql="select profile from Gn_Member where mem_id = '{$diplay_row['mem_id']}'";
    $friend_result=mysqli_query($self_con,$friend_sql);
    $friend_row=mysqli_fetch_array($friend_result);
    $friends_main_img = $friend_row['profile'];
    if(!$friends_main_img) {
        $friends_main_img = $diplay_row['main_img1'];
        if(!$friends_main_img) {
            $friends_main_img = "/iam/img/profile_img.png";
        }
    }
        
    $body .= '    <li class="list-item">';
    $body .= '        <div class="item-wrap">';
    $body .= '            <div class="thumb">';
    $body .= '                <div class="thumb-inner">';
                        if($_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) {
                            if((int)$search_type != 1) {
                                $body .= '        <a href="/?' . $row6[friends_url] .'" target="blank">';
                                $body .= '            <img src="'. cross_image($friends_main_img) .'" id="friends_logo" class="friends_logo" style="max-height:40px;height:100%;object-fit:cover;"></a>';
                            }else{
                                $body .= '        <a href="http://'. $row6[friends_url] .'" target="blank">';
                                $body .= '            <img src="'. cross_image($friends_main_img) .'" id="friends_logo" class="friends_logo" style="max-height:40px;height:100%;object-fit:cover;"></a>';
                            }
                        }else{
                            if($diplay_row[phone_display] == "Y") {
                                if((int)$search_type != 1) {
                                    $body .= '            <a href="javascript:loginCheckShowModalFriends();"><img src="'. cross_image($friends_main_img) .'" id="friends_logo" class="friends_logo" style="max-height:40px;height:100%;object-fit:cover;"></a>';
                                }else{
                                    $body .= '<a href="javascript:loginCheckShowModalFriends();"><img  src="'.cross_image($friends_main_img).'" id="friends_logo" class="friends_logo" style="max-height:40px;height:100%;object-fit:cover;"></a>';
                                }
                            }else{
                                $body .= '        <a href="javascript:toastr.error(\'비공개 아이엠 입니다.\');">';
                                $body .= '            <img src="'. cross_image($friends_main_img).'" id="friends_logo" class="friends_logo" style="max-height:40px;height:100%;object-fit:cover;">';
                                $body .= '        </a>';
                            }
                        }
    $body .= '                </div>';
    $body .= '            </div>';
    $body .= '            <div class="info">';
    $body .= '                <div class="upper">';
    $body .= '                        <span class="name">';
                        if($diplay_row[phone_display] == "Y") {
                            if($_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) {
                                $body .= $diplay_row[friends_name];
                            }else{
                                $body .= iconv_substr($diplay_row['friends_name'], 0, 1, "utf-8")."**";
                            }
                        }else{
                            if($_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) {
                                $body .= $diplay_row[friends_name];
                            }else{
                                $body .= iconv_substr($diplay_row['friends_name'], 0, 1, "utf-8")."**";
                            }
                        }
    $body .= '                        </span>';
    $body .= '                    <span class="company">'. $diplay_row[friends_company]. '</span>';
    $body .= '                </div>';
    $body .= '                <div class="downer">';
                        if($diplay_row[phone_display] == "Y") {
                            if($_SESSION['iam_member_id'] == $card_owner && $phone_count > 0 && $_SESSION['iam_member_id'] == $card_master) {
                                $body .= '                            <a href="tel:'. $diplay_row[friends_phone] .'">'. $diplay_row[friends_phone].'</a>';
                            }else{
                                $body .= iconv_substr($diplay_row[friends_phone], 0, 6, "utf-8")."**-****";
                            }
                        }else{
                            if($_SESSION['iam_member_id'] == $card_owner && $phone_count > 0 && $_SESSION['iam_member_id'] == $card_master) {
                                $body .= '                            <a href="tel:'. $diplay_row[friends_phone] . '">'. $diplay_row[friends_phone].'</a>';
                            }else{
                                $body .= iconv_substr($diplay_row[friends_phone], 0, 6, "utf-8")."**-****";
                            }
                        }
    $body .= '                 </div>';
    $body .= '             </div>';
                if($_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) {
                    if((int)$search_type != 1) {
                        $myFriends_sql="select count(idx) from Gn_Iam_Friends where mem_id = '$card_owner' and friends_card_idx = '$row6[friends_card_idx]'";
                        $myFriends_result=mysqli_query($self_con,$myFriends_sql);
                        $myFriends_row=mysqli_fetch_array($myFriends_result);
                        $myFriends_row_num = $myFriends_row[0];
                        if($myFriends_row_num) {
                            $body .= '                         <div class="chat" style="text-align: right;width:20px">';
                            $body .= '                             <img src="/iam/img/star/icon-frenz2.PNG">';
                            $body .= '                         </div>';
                        }
    $body .= '                     <div class="check">';
    $body .= '                         <div style="display: flex;margin-right: 20px;">';
                                if($search_type != 1) {
                                    $body .= '                                 <img src ="/iam/img/star/icon-profile.png" style="width: 20px;margin-right: 2px" onclick = "friends_add(\''. $row6[friends_card_idx].'\');">';
                                }
    $body .= '                             <input type="checkbox" name="friends_chk" id="inputItem'. $row6[friends_card_idx] .'" class="friends checkboxes input css-checkbox" onclick=\'friends_chk_count() \' value="'. $row6[friends_card_idx].'">';
    $body .= '                             <label for="inputItem'. $row6[friends_card_idx].'" class="css-label cb0"></label>';
    $body .= '                             <input type="hidden" name="friends_idx'. $row6['friends_card_idx'] .'" id="friends_idx'. $row6['friends_card_idx'].'"';
    $body .= '                                 value="'. $diplay_row['friends_phone'].'">';
    $body .= '                         </div>';
    $body .= '                     </div>';
                    }else{
    $body .= '                     <div class="check">';
    $body .= '                         <div style="display: flex;margin-right: 20px;">';
                                if($search_type != 1) {
                                    $body .= '                                 <img src ="/iam/img/star/icon-profile.png" style="width: 20px" onclick = "friends_add(\''. $row6[friends_card_idx].'\');">';
                                }
    $body .= '                             <input type="checkbox" name="friends_chk" id="inputItem'. $row6[friends_card_idx] .'" class="friends checkboxes input css-checkbox" onclick=\'friends_chk_count() \' value="'. $row6[friends_card_idx].'">';
    $body .= '                             <label for="inputItem'. $row6[friends_card_idx] .'" class="css-label cb0"></label>';
    $body .= '                             <input type="hidden" name="friends_idx'. $row6['friends_card_idx'] .'"';
    $body .= '                                 id="friends_idx'. $row6['friends_card_idx'] .'"';
    $body .= '                                 value="'. $diplay_row['friends_phone'] .'">';
    $body .= '                             <input type="hidden" name="friends_card_idx'. $row6['friends_card_idx'] .'"';
    $body .= '                                 id="friends_card_idx'. $row6['friends_card_idx'] .'"';
    $body .= '                                 value="'. $row6['idx'] .'">';
    $body .= '                         </div>';
    $body .= '                     </div>';
                    }
                }
    $body .= '         </div>';
    $body .= '     </li>';
}
$body .= ' </ul>';
$body .= ' </div>';

$body .= ' <div class="pagination" style="'. $style_page .'">';
$body .= ' <ul style="min-height:25px">';
    if($page2 <= 1) { //만약 page가 1보다 크거나 같다면 빈값

    } else {
        $pre2 = $page2-1; //pre변수에 page-1을 해준다 만약 현재 페이지가 3인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함
        $body .= '<li class=\'arrow\'><a href="javascript:getIamFriends(\''.$card_owner.'\', \''. $card_master. '\', \''. $phone_count .'\', \''. $search_range2. '\', '. $pre2 .' )"><i class=\'fa fa-angle-left\' aria-hidden=\'true\'></i></a></li>';
        
    }
    for($i=$block_start2; $i<=$block_end2; $i++){
        //for문 반복문을 사용하여, 초기값을 블록의 시작번호를 조건으로 블록시작번호가 마지박블록보다 작거나 같을 때까지 $i를 반복시킨다
        if($page2 == $i) { //만약 page가 $i와 같다면
            $body .= '<li class=\'active\'><span>' . $i. '</span></li>'; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다            
        } else {
            $body .= '<li><a href="javascript:getIamFriends(\''.$card_owner.'\', \''. $card_master. '\', \''. $phone_count .'\', \''. $search_range2. '\', '. $i .' )">'. $i. '</a></li>'; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
        }
    }

    if($block_num2 >= $total_block2) { //만약 현재 블록이 블록 총개수보다 크거나 같다면 빈 값
    } else {
        $next2 = $page2 + 1; //next변수에 page + 1을 해준다.
        // echo "<li><a href='?page=$next'>다음</a></li>"; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
        $body .= '<li class=\'arrow\'><a href="javascript:getIamFriends(\''.$card_owner.'\', \''. $card_master. '\', \''. $phone_count .'\', \''. $search_range2. '\', '. $next2 .' )"><i class=\'fa fa-angle-right\' aria-hidden=\'true\'></i></a></li>'; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
    }
    
    if($_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) {
        if((int)$search_type !== 2) {
            if((int)$search_type != 1 || $total_block2 != 0){
                $body .= '<li style="float:right">';
                $body .= '    <a style="background-image: url(/iam/img/main/icon-kakaoimg.png);border:none !important;border-radius: 0%;height: 27px; width: 27px; background-size: 27px 27px;" href="javascript:sns_sendSMS();"></a>';
                $body .= '</li>';
                $body .= '<li style="float:right;">';
                $body .= '    <a style="background-image: url(/iam/img/star/icon-bin.png);border:none !important;border-radius: 0%;height: 27px; width: 27px; background-size: 27px 27px;" href="javascript:friends_del();"></a>';
                $body .= '</li>';
          }
        }else{
            $body .= '<li style="float:right;">';
            $body .= '    <a style="background-image: url(/iam/img/star/icon-profile.png); -webkit-background-size: 18px 18px; background-size: 18px 18px;" href="javascript:friends_add();"></a>';
            $body .= '</li>';
        }
    }
$body .= '</ul>';
$body .= '</div>';

echo $body;
?>