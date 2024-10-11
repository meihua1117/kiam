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
if($is_pay_version){
    $display = "display:block;";
}
else{
    $display = "display:none;";
}
$mall_type = $_GET['mall_type'];
if(!$mall_type)
    $mall_type = "main_mall";
$mall_type1 = $_GET['mall_type1'];
if(!$mall_type1)
    $mall_type1 = "all";

$sql8 = "select mall.* from Gn_Iam_Mall mall where ";
if($search_key || $mall_type == "sub_mall")
    $sql8 = "select mall.* from Gn_Iam_Mall mall inner join Gn_Member mem on mem.mem_id=mall.mem_id where ";
if($mall_type == "main_mall"){
    $sql8 .= " mall.display_status = 1 ";
}else if($mall_type == "sub_mall"){
    $site_info = explode(".",$HTTP_HOST);
    $site_name = $site_info[0];
    $sql8 .= " mall.display_status = 1 and mem.site_iam = '$site_name'";
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
elseif($mall_type1 == "service")
    $sql8 .= " and mall.mall_type = 4";
elseif($mall_type1 == "msg_set")
    $sql8 .= " and mall.mall_type = 5";
elseif($mall_type1 == "gallery"){
    if($_GET['art_type'] != "")
        $sql8 .= " and mall.mall_type = {$_GET['art_type']}";
    else
        $sql8 .= " and mall.mall_type > 10";
}

if($_GET['sort'] == 1){
    $sql8 .= " and mall.reg_date >= ADDTIME(now(), '-1:0:0') ";
}else if($_GET['sort'] == 2){
    $sql8 .= " and mall.reg_date >= DATE(now()) ";
}else if($_GET['sort'] == 3){
    $sql8 .= " and WEEKOFYEAR(mall.reg_date) = WEEKOFYEAR(now()) and YEAR(mall.reg_date) = YEAR(now()) ";
}else if($_GET['sort'] == 4){
    $sql8 .= " and MONTH(mall.reg_date) = MONTH(now()) and YEAR(mall.reg_date) = YEAR(now()) ";
}else if($_GET['sort'] == 5){
    $sql8 .= " and YEAR(mall.reg_date) = YEAR(now()) ";
}
if($search_key){
    $sql8 .= " and (mall.keyword like '%$search_key%' or mall.title like '%$search_key%' or mall.sub_title like '%$search_key%' or mall.description like '%$search_key%' or mall.mem_id like '%$search_key%' or mem.mem_name like '%$search_key%' or mem.site_iam like '%$search_key%') ";
} 

$sql8 = "SELECT t.* FROM ($sql8) as t";

if($_GET['sort'] == 11){//게시일짜
    $sql8 .= " ORDER BY t.reg_date desc";
}elseif($_GET['sort'] == 12){//조회수
    $sql8 .= " ORDER BY t.visit_count desc";
}elseif($_GET['sort'] == 13){//좋아요
    $sql8 .= " ORDER BY t.mall_like_count desc";
}elseif($_GET['sort'] == 14){//가격순
    $sql8 .= " ORDER BY t.sell_price desc";
}else{
    if($mall_type == "best_mall")
        $sql8 .= " ORDER BY t.sample_order desc";    
    else
        $sql8 .= " ORDER BY t.reg_date desc";
}        

$sql8 .= " limit $w_offset, $contents_count_per_page ";
//file_put_contents("../../iamlog.txt", $sql8 . "\n", FILE_APPEND);

if($sql8)
    $result8=mysqli_query($self_con,$sql8) or die(mysqli_error($self_con));

$logs->add_log("mall start $slq8");
    
$body = '';
while( $contents_row = mysqli_fetch_array($result8)){
    $card_sql = "select card_short_url from Gn_Iam_Name_Card where mem_id = '{$contents_row['mem_id']}' order by req_data";
    $card_res = mysqli_query($self_con,$card_sql);
    $card_row = mysqli_fetch_array($card_res);

    $mem_sql = "select site_iam,mem_name from Gn_Member where mem_id = '{$contents_row['mem_id']}'";
    $mem_res = mysqli_query($self_con,$mem_sql);
    $mem_row = mysqli_fetch_array($mem_res);
    
    if($mem_row['site_iam'] == "kiam")
        $card_url = "http://kiam.kr?";
    else
        $card_url = "http://".$mem_row['site_iam'].".kiam.kr?";
    $card_url .= $card_row['card_short_url'];
    $card_url .= "&site=".$member_iam;
    if($contents_row['mall_type'] == 1){
        $sql = "select card_short_url,profile from Gn_Iam_Name_Card n inner join Gn_Member m on m.mem_id=n.mem_id where n.group_id is NULL and m.mem_code = {$contents_row['card_idx']} order by n.req_data limit 0,1";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        //if($mem_row['site_iam'] == "kiam")
        //    $preview_link = "http://www.kiam.kr/?";
        //else
        //    $preview_link = "http://".$mem_row['site_iam'].".kiam.kr/?";
        $preview_link = "/?";
        $preview_link .= $row[0].$contents_row['card_idx'] . "&type=image";
        $avatar = $row['profile']?$row['profile']:"/iam/img/common/logo-2.png";
    }else if($contents_row['mall_type'] == 2){
        $sql = "select card_short_url,mem_id from Gn_Iam_Name_Card where idx = {$contents_row['card_idx']}";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        $mall_url = $row[0];
        $sql = "select mem_code,site_iam,profile from Gn_Member where mem_id = '$row[1]'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        /*if($row['site_iam'] == "kiam")
            $preview_link = "http://www.kiam.kr/?";
        else
            $preview_link = "http://".$row['site_iam'].".kiam.kr/?";*/
        $preview_link = "/?";
        $preview_link .= $mall_url.$row['mem_code']."&type=image&preview=Y";
        $avatar = $row['profile']?$row['profile']:"/iam/img/common/logo-2.png";
    }else if($contents_row['mall_type'] == 3 || $contents_row['mall_type'] == 4 || $contents_row['mall_type'] > 10){
        $sql = "select mem_id, card_idx from Gn_Iam_Contents where idx = {$contents_row['card_idx']}";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        $card_idx = $row['card_idx'];
        $sql = "select site_iam,profile from Gn_Member where mem_id = '$row[0]'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        /*if($row['site_iam'] == "kiam")
            $preview_link = "http://www.kiam.kr/iam/contents.php?contents_idx=";
        else
            $preview_link = "http://".$row['site_iam'].".kiam.kr/iam/contents.php?contents_idx=";*/
        $preview_link = "/iam/contents.php?contents_idx=";
        $preview_link .= $contents_row['card_idx'];
        if($contents_row['mall_type'] > 10)
            $preview_link = str_replace("contents.php","contents_gallery.php",$preview_link);
        
        $sql_chk_card = "select ai_map_gmarket,main_img1 from Gn_Iam_Name_Card use index(idx) where idx='{$card_idx}'";
        $res_chk_card = mysqli_query($self_con,$sql_chk_card);
        $row_chk_card = mysqli_fetch_assoc($res_chk_card);

        if($row_chk_card['main_img1'] != "")
            $avatar = $row_chk_card['main_img1'];
        else{
            $avatar = $row['profile']?$row['profile']:"/iam/img/common/logo-2.png";
        }

        if($row_chk_card['ai_map_gmarket']){
            $map_show = '<div class="card-data" style="font-size:14px;">';
            $map_show .= '<a class="navermap" href="javascript:show_map_address('.$card_idx.')">주소</a>&nbsp;|&nbsp;<a class="navermap" href="javascript:show_map_position('.$card_idx.')">지도</a>&nbsp;|&nbsp;<a class="navermap" href="javascript:show_map_distance('.$card_idx.')">거리</a>&nbsp;|&nbsp;<a class="navermap" href="javascript:show_map_busitime('.$card_idx.')">영업시간</a>';
            $map_show .= '</div>';
        }
    }else if($contents_row['mall_type'] == 5){
        $sql = "select profile from Gn_Member where mem_id = '{$contents_row['mem_id']}'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        $avatar = $row['profile']?$row['profile']:"/iam/img/common/logo-2.png";
    }
    if($contents_mode == "pin"){
        $body .= '<div class="col-xs-6 sample_card" style = "">';
        $body .= '<div class="square" style= "">';
    }
    else{
        $body .= '<div class="col-xs-12 sample_card" style = "">';
        $body .= '<div class="square" style= "padding-bottom:80%">';
    }
    if($contents_mode == "pin"){
        $body .= '<div class="content mall-img';
        if($contents_row['mall_type'] == 2)
            $body .= ' mall-img-message';
        $body .= '" style="background-image:url(\''. cross_image($contents_row['img']).'\'); background-size: cover;background-position: top;';
        if($contents_row['mall_type'] == 2)
            $body .= 'height:77%';
        if($contents_row['mall_type'] == 5)
            $body .= '" onclick="open_mall_description(\''.$contents_row['idx'].'\',\'상세설명\');">';    
        else
            $body .= '" onclick="visit_mall(\''.$contents_row['idx'].'\',\''.$preview_link.'\');">';    
    }else{
        $body .= '<div class="content mall-img';
        if($contents_row['mall_type'] == 2)
            $body .= ' mall-img-message';
        $body .= '" style="background-image:url(\''. cross_image($contents_row['img']).'\'); background-size: cover;background-position: top;';
        if($contents_row['mall_type'] == 2)
            $body .= 'height:85%';
        else
            $body .= 'height:70%';

        if($contents_row['mall_type'] == 5)
            $body .= '" onclick="open_mall_description(\''.$contents_row['idx'].'\',\'상세설명\');">';     
        else
            $body .= '" onclick="visit_mall(\''.$contents_row['idx'].'\',\''.$preview_link.'\');">';      
    }
    $body .= '</div>';

    $logs->add_log("mall middle");
   
    if($contents_row['mem_id'] == $_SESSION['iam_member_id']){
        $body .= '<div style="display:flex;justify-content: flex-end;flex-direction: row-reverse;float: right;margin-top:5px;margin-right:5px">';
        $body .= '<a class="content-utils" style="cursor:pointer;z-index:10;"';
        $body .= 'href="javascript:show_mall_icons(\''.$contents_row['idx'].'\')">';
        $body .= '<img src = "/iam/img/menu/icon_utils_box.png" style="height:20px">';
        $body .= '</a>';
        $body .= '<div id="mall_edit_utils_'.$contents_row['idx'] .'" style="display:none;z-index:10;margin-right:5px">';
        $body .= '<a class="content-utils" href="javascript:open_iam_mall_edit_popup(\''.$contents_row['idx'].'\',';
        $body .= '\''. nl2br(str_replace('"', urlencode('"'), str_replace("'", urlencode("'"), $contents_row['title']))).'\',';
        $body .= '\''. nl2br(str_replace('"', urlencode('"'), str_replace("'", urlencode("'"), $contents_row['sub_title']))).'\',';
        $body .= '\''. $contents_row['price'] .'\',';
        $body .= '\''. $contents_row['sell_price']. '\',';
        $body .= '\''. $contents_row['img'].'\',';
        $body .= '\''. nl2br(str_replace('"', urlencode('"'), str_replace("'", urlencode("'"), $contents_row['keyword']))).'\',';
        $body .= '\''. $contents_row['display_status'].'\');">';
        $body .= '<img src="/iam/img/menu/icon_edit_white.png" style="width:20px;height:20px;">';
        $body .= '</a>';
        $body .= '<a class="content-utils" href="javascript:delete_mall(\''. $contents_row['idx'] .'\');"><img src="/iam/img/menu/icon_minus.png" style="width:20px;height:20px;"></a>';
        $body .= '<a class="content-utils" href="javascript:get_mall_link(\''.$contents_row['idx'].'\');" style="margin-left: 1px;cursor:pointer;width:24px;height:24px;border-radius:50%;overflow:hidden">';                                    
        $body .= '<img src="/iam/img/menu/icon_share.png"  style="width:20px">';
        $body .= '</a>';
        $body .= '</div>';
        if(strstr($contents_row['mall_like'],$_SESSION['iam_member_id'])){
            $body .= '<a class="content-utils" style="cursor:pointer;z-index:10;" ';
            $body .= 'onclick="unlike_mall(\''.$contents_row['idx'].'\')">';
            $body .= '<img src="/iam/img/menu/icon_like_active.png"  style="margin-left: 2px;width: 18px;">';
            $body .= '</a>';
        }else{
            $body .= '<a class="content-utils" style="cursor:pointer;z-index:10;" ';
            $body .= 'onclick="like_mall(\''.$contents_row['idx'].'\')">';
            $body .= '<img src="/iam/img/menu/icon_like_white.png"  style="margin-left: 2px;width: 18px;">';
            $body .= '</a>';
        }
        $body .= '</div>';
    }
    else{
        $body .= '<div style="display:flex;justify-content: flex-end;flex-direction: row-reverse;float: right;margin-top:5px;margin-right:5px">';
        $body .= '<a class="content-utils" href="javascript:get_mall_link(\''.$contents_row['idx'].'\');" style="cursor:pointer;z-index:10;">';
        $body .= '<img src="/iam/img/menu/icon_share_white.png"  style="width:13px;height:16px;margin-left:3px;margin-top:-1px;">';
        $body .= '</a>';
        if(strstr($contents_row['mall_like'],$_SESSION['iam_member_id'])){
            $body .= '<a class="content-utils" style="cursor:pointer;z-index:10;" ';
            $body .= 'onclick="unlike_mall(\''.$contents_row['idx'].'\')">';
            $body .= '<img src="/iam/img/menu/icon_like_active.png"  style="margin-left: 2px;width: 18px;">';
            $body .= '</a>';
        }else{
            $body .= '<a class="content-utils" style="cursor:pointer;z-index:10;" ';
            $body .= 'onclick="like_mall(\''.$contents_row['idx'].'\')">';
            $body .= '<img src="/iam/img/menu/icon_like_white.png"  style="margin-left: 2px;width: 18px;">';
            $body .= '</a>';
        }
        $body .= '</div>';
    }
    if($contents_mode == "pin")
    {
        if($contents_row['mall_type'] != 2){
            $body .= '<div class="content2 mall-content">';
        }else{
            $body .= '<div class="content2 mall-content mall-content-message" style="height:23%">';
        }
    }
    else{
        if($contents_row['mall_type'] != 2){
            $body .= '<div class="content2 mall-content" style="height:35%">';
        }else{
            $body .= '<div class="content2 mall-content mall-content-message" style="height:15%">';
        }
    }
    $body .= '<div class="card-data" style="display:flex;margin-top: 5px;margin-bottom:10px;position: relative;">';
    $body .= '<div style="width:24px;height:24px;border-radius:50%;overflow:hidden">';
    $body .= '<img src="'.$avatar.'"  style="width:100%;height:100%" onclick="window.open(\''.$card_url.'\')">';
    $body .= '</div>';
    $body .= '<p class="card-text" style="margin-left:10px;font-size:13px">'. $mem_row['mem_name'].'</p>';
    $body .= '<p class="card-text" style="position:absolute;right:3px;" onclick="open_mall_description(\''.$contents_row['idx'].'\',\'상세설명\');"><strong style="">상세보기</strong></p>';
    $body .= '</div>';
    if($contents_row['mall_type'] == 5)
        $body .= '<div class="card-data" style="cursor:pointer" onclick="open_mall_description(\''.$contents_row['idx'].'\',\'상세설명\');">';     
    else
        $body .= '<div class="card-data" style="cursor:pointer" onclick="visit_mall(\''.$contents_row['idx'].'\',\''.$preview_link.'\');">';
    $body .= '<p class="card-text mall-desc">'.$contents_row['title'].'</p>';
    $body .= '</div>';
    $body .= '<div class="card-data" style = "display:flex;justify-content: space-between;">';
    $body .= '<p class="card-text mall-desc">'.$contents_row['sub_title'].'</p>';
    // $body .= '<p class="card-text" style="float:right"><strong style="">상세보기</strong></p>';
    $body .= '<input type="hidden" id="mall_desc_'.$contents_row['idx'].'" value="'.$contents_row['description'].'">';
    $body .= '</div>';
    if($contents_row['mall_type'] != 2){
        if($contents_row['mall_type'] < 10){
            $body .= '<div class="card-price" style="margin-top: 5px;">';
            $body .= '<p class="card-price" style="float:right;text-decoration: line-through;color:grey;">'. number_format($contents_row['price']) ." 원 " .'</p>';        
            $body .= '</div>';
            $body .= '<div class="card-data" style = "display:flex;justify-content: space-between;">';
            $body .= '<p class="card-text" style="color:red">'. number_format($contents_row['sell_price']*100/$contents_row['price']) ."%" .'</p>';        
            $body .= '<p class="card-text" style="margin-left:5px;font-weight:bold">'.number_format($contents_row['sell_price'])." 원".'</p>';
            $body .= '</div>';
        }else{
            $gallery = explode("|",$contents_row['price']);
            $gallery_price = $gallery[0];
            $download_price = $gallery[3];
            $discount = $contents_row['sell_price'] * 100 / $gallery_price;
            $sell_price = $download_price * $discount / 100;
            $body .= '<div class="card-price" style="margin-top: 5px;">';
            $body .= '<p class="card-price" style="float:right;text-decoration: line-through;color:grey;">'. number_format($download_price) ." 원 " .'</p>';        
            $body .= '</div>';
            $body .= '<div class="card-data" style = "display:flex;justify-content: space-between;">';
            $body .= '<p class="card-text" style="color:red">'. number_format($discount) ."%" .'</p>';        
            $body .= '<p class="card-text" style="margin-left:5px;font-weight:bold">'.number_format($sell_price)." 원".'</p>';
            $body .= '</div>';
        }
    }
    $body .= $map_show;
    if($contents_row['mall_type'] != 2 && $contents_row['mall_type'] < 10){
        $body .= '<div class="mall-btn-div" style="">';
        $body .= '<button type="button" class="mall_btn" style="border: 1px solid #ddd" onclick="open_mall_pay_popup(\''.$contents_row['idx']. '\',\''. $contents_row['mall_type'].'\',\''.$contents_row['title'].'\',\''.$contents_row['card_idx'].'\',\''.$contents_row['sell_price'].'\')">구매하기</button>';
        $body .= '</div>';
    }
    $body .= '</div>';
    $body .= '</div>';
    $body .= '</div>';
}
$logs->add_log("mall end");
$logs->write_to_file();

echo $body;
?>

