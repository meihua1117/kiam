
<?
include_once "../../lib/rlatjd_fun.php";
$limit = (intval($_GET['limit']) != 0 ) ? $_GET['limit'] : 4;
$offset = (intval($_GET['offset']) != 0 ) ? $_GET['offset'] : 0;
$gwc_table = $_GET['gm'];
$status = $_GET['mode'];
$search_key = $_GET['search_key'];
if($search_key){
    if(strpos($search_key, ' ') !== false){
        $search_key_arr = explode(' ', $search_key);
        $search_key1 = trim($search_key_arr[0]);
        $search_key2 = trim($search_key_arr[1]);
        $search_sql = "((contents_title like '%$search_key1%' and contents_title like '%$search_key2%') or (contents_desc like '%$search_key1%' and contents_desc like '%$search_key2%')) ";
    }
    else{
        $search_sql = "(contents_title like '%$search_key%' or contents_desc like '%$search_key%')";
    }
}else{
    $search_sql = '1=1';
}
$contents_count_per_page = $limit;
$w_offset = $offset;
if($w_offset < 0 ){
    $w_offset = 0;
}

$logs = new Logs("../../my_info.txt", false);
$logs->add_log("loading start");

$cont_array = array();
$total_count_ = 0;
if($status == "send")
    $sql8="select * from Gn_Item_Pay_Result WHERE buyer_id = '{$_SESSION['iam_member_id']}' and type='noticesend' and point_val=3 ORDER BY pay_date desc";
else
    $sql8="select * from Gn_Item_Pay_Result WHERE buyer_id = '{$_SESSION['iam_member_id']}' and type='noticerecv' and point_val=3 ORDER BY pay_date desc";
$result_cnt=mysqli_query($self_con, $sql8) or die(mysqli_error($self_con));
$cont_count = mysqli_num_rows($result_cnt);
$sql8 .= " limit $contents_count_per_page offset ".$w_offset; 

$body = "<script>$('#total_count').html('총 ".$cont_count."건');$('#total_count').data('count',".$cont_count.");</script>";
if($cont_count == 0){
$body .="<div class=\"content-item\" id=\"contents_welcome\" style =\"\">".
            "<div class=\"media-wrap\" >".
                "<div class=\"media-inner-no-cont\" style=\"width:fit-content;margin:20px auto;min-height:30px\">".
                    "<img src=\"/iam/img/menu/no_alarm.png\" class=\"contents_img\">".
                "</div>".
            "</div>".
            "<div class=\"desc-wrap\" style=\"\">".
                "<div class=\"title\" style=\"display: flex;flex-direction: column;justify-content: center\">".
                    "<h3 style=\"text-align: center\">해당 알림이 없습니다.</h3>".
                "</div>".
            "</div>".
        "</div>";                
}else{
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

    foreach($cont_array as $notice_row){
        $sender_id = $notice_row['pay_method'];
        $sender_mem_card_info = "select * from Gn_Iam_Name_Card where mem_id='{$sender_id}' order by req_data asc limit 1";
        $res_card_info = mysqli_query($self_con, $sender_mem_card_info);
        $row_card_info = mysqli_fetch_array($res_card_info);

        $sender_mem_info = "select mem_code, mem_name from Gn_Member where mem_id='{$sender_id}'";
        $res_sender_info = mysqli_query($self_con, $sender_mem_info);
        $row_sender_info = mysqli_fetch_array($res_sender_info);

        $href = "/?" . $row_card_info['card_short_url'] . $row_sender_info['mem_code'];
        $img = $row_card_info['main_img1'];
        $img = $img?cross_image($img):'/iam/img/common/logo-2.png';
        if($status != "send" && $notice_row['alarm_state'] == 0)
            $circle = "<div style=\"width:17px;height:17px;position: absolute;top:40px;left:0px;background: #99cc00;border-radius: 50% !important;\"></div>";
        else
            $circle="";
$body .="<div class=\"content_area\" style=\"margin-left:15px;margin-right:15px;\">".
            "<div class=\"content-item\" style=\"display:block;position:relative;box-shadow:none\" >".
                "<div class=\"user-item\" style=\"\">".
                    "<a href=\"".$href."\" class=\"img-box\" target=\"_blank\">".
                        "<div class=\"user-img\" style=\"width:52px;height:52px\">".
                            "<img src=\"".$img."\" alt=\"\">".
                        "</div>".
                    "</a>".
                    "<div class=\"wrap image_mode\">".
                        "<a href=\"".$href."\" class=\"user-name\" style=\"text-decoration-line:none\">".$row_sender_info['mem_name']."</a>".
                        "<a href=\"".$href."\" class=\"date\" style=\"text-decoration-line:none\">".$notice_row['pay_date']."</a>".
                    "</div>".
                "</div>".
                $circle.
                "<div class=\"desc-wrap\" style=\"position:relative;display:block;\">".
                    "<div class=\"title\" style=\"display: flex;flex-direction: column;justify-content: center;height:100%;border-radius: 10px;border: 2px solid lightgrey;padding:0px;\">".
                        "<div style=\"padding:15px;border-bottom:2px solid #ddd;\">".
                            "<h3>".$notice_row['seller_id']."</h3>".
                        "</div>".
                        "<div style=\"padding:15px;\">".
                            "<p>".str_replace("\n", "<br>", $notice_row['message'])."</p><br>".
                            "<a href=\"".$notice_row['site']."\" target=\"_blank\">".$notice_row['site']."</a>".
                        "</div>".
                    "</div>".
                    "<div class=\"dropdown\" style=\"position: absolute; right: 0px; top: 8px;\">".
                        "<button class=\"btn-link dropdown-toggle westory_dropdown\" type=\"button\" data-toggle=\"dropdown\" style=\"margin:0px\">".
                            "<img src=\"/iam/img/menu/icon_dot.png\" style=\"height:24px\">".
                        "</button>".
                        "<ul class=\"dropdown-menu comunity\">";
                            if($status != "send")
$body .=                    "<li><a onclick=\"receive_contents(".$notice_row['no'].", 'refresh');\">읽음</a></li>";
$body .=                    "<li><a onclick=\"remove_recv_notice('".$notice_row['no']."')\">삭제</a></li>".
                        "</ul>".
                    "</div>".
                "</div>".
            "</div>".
        "</div>";
    }
}
$logs->add_log("end unread_notice");
$logs->write_to_file();
echo $body;
?>
