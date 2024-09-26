<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
date_default_timezone_set('Asia/Seoul');
$cur_time = date("Y-m-d H:i:s");
$cur_time1 = date("YmdHis");
$pcode = "dailymsg".$cur_time1;
$event_name_eng = "데일리문자 신청".$cur_time1;
extract($_POST);
// $msg_title = $_POST['msgtitle_daily'];
// $msg_desc = $_POST['msgdesc_daily'];
$mem_id =$_SESSION['iam_member_id'];
// $daily_cnt = $_POST['daily_cnt'];
if($_FILES['upimage']['name']) {
    $img_name=date("dmYHis").str_replace(" ","",basename($_FILES["upimage"]["name"]));	
    $img_name=str_replace("'","",$img_name);	
    $img_name=str_replace('"',"",$img_name);	
    $img_name=basename($img_name);	
    if(move_uploaded_file($_FILES["upimage"]['tmp_name'], "../adjunct/mms/thum/".$img_name)){
        $size = getimagesize("../adjunct/mms/thum/".$img_name);
        if($size[0] > 640) {
            $ysize = $size[1] * (640 / $size[0]);
            thumbnail("../adjunct/mms/thum/".$img_name,"../adjunct/mms/thum1/".$img_name,"",640,$ysize,"");
            $show_img = "adjunct/mms/thum1/".$img_name;
        } else if($size[1] > 480) {
            $xsize = $size[0] * (480/ $size[1]);
            thumbnail("../adjunct/mms/thum/".$img_name,"../adjunct/mms/thum1/".$img_name,"",$xsize,480,"");
            $show_img = "adjunct/mms/thum1/".$img_name;
        } else {
            $show_img = "adjunct/mms/thum/".$img_name;
        }
    }
}
$up_img = "";
if($show_img){
    $up_img = $show_img;
}
// if($upimage_str1 != ""){
//     $up_img .= ",".$upimage_str1;
// }
// if($upimage_str2 != ""){
//     $up_img .= ",".$upimage_str2;
// }

$sql_mem = "select * from Gn_Member where mem_id='{$mem_id}'";
$res_mem = mysqli_query($self_con, $sql_mem);
$row_mem = mysqli_fetch_array($res_mem);

$sql_event = "insert into Gn_event set event_name_kor='데일리문자세트자동생성', event_name_eng='$event_name_eng', event_title='{$msgtitle_daily_intro}', event_desc='{$msgdesc_daily_intro}', event_info='{$msgtitle_daily}', event_sms_desc='{$htime}', pcode='{$pcode}', object='{$up_img}', mobile='{$row_mem['mem_phone']}', regdate='{$cur_time}', m_id='{$mem_id}', read_cnt=0, event_type='{$mtime}', callback_no='{$daily_cnt}', event_req_link='{$msgdesc_daily}', daily_req_link='{$iam_link_daily}'";
// echo $sql_event; exit;
mysqli_query($self_con, $sql_event) or die(mysqli_error($self_con));
$event_idx = mysqli_insert_id($self_con);

$transUrl = "http://".$HTTP_HOST."/event/dailymsg.php?pcode=".$pcode."&eventidx=".$event_idx;
$transUrl = get_short_url($transUrl);
// $rand_num = rand(100, 999);
// $transUrl = "https://tinyurl.com/hexdg".$rand_num;
$insert_short_url = "update Gn_event set short_url='{$transUrl}' where event_idx={$event_idx}";
mysqli_query($self_con, $insert_short_url) or die(mysqli_error($self_con));

echo '{"shorturl":"'.$transUrl.'"}';
?>