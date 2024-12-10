<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
date_default_timezone_set('Asia/Seoul');
$cur_time = date("Y-m-d H:i:s");
$cur_time1 = date("YmdHis");
$pcode = "aimem".$cur_time1;
$event_name_eng = "회원IAM 신청".$cur_time1;
extract($_POST);
$msg_title = $_POST['msg_title'];
$msg_desc = $_POST['msg_desc'];
$mem_id = $_POST['mem_id'];
$sel_card = $_POST['sel_card'];
$myiam_link = $_POST['myiam_link'];//도장아이엠링크
$button_txt = $_POST['button_txt'];
$event_link = $_POST['event_link'];//이벤트 신청링크

// echo $myiam_link.$HTTP_HOST; exit;
/*if(strpos($sel_card, ',') !== false){
    $res = explode(",", $sel_card);
    $card_short_url = trim($res[0]);
}
else{
    $card_short_url = $sel_card;
}*/
$autojoin_img_path = '';
if($_FILES['autojoin_img']['name']) {
    // echo $_FILES['autojoin_img']['name']; exit;
    $info = explode(".",$_FILES['autojoin_img']['name']);
    $ext = $info[count($info)-1];
    $filename = time().".".$ext;
    //메인에 옮길때 수정패치 할것 test->www
    $autojoin_img_path = "http://www.kiam.kr".gcUpload($_FILES['autojoin_img']['name'], $_FILES['autojoin_img']['tmp_name'], $_FILES['autojoin_img']['size'], "ad", $filename);
}
$sql_mem = "select mem_phone from Gn_Member where mem_id='{$mem_id}'";
$res_mem = mysqli_query($self_con,$sql_mem);
$row_mem = mysqli_fetch_array($res_mem);
$msg_desc = str_replace("'", " ", $msg_desc);

$sql_event = "insert into Gn_event set event_name_kor='단체회원자동가입및아이엠카드생성', 
                                        event_name_eng='{$event_name_eng}', 
                                        event_title='{$msg_title}', 
                                        event_desc='{$msg_desc}', 
                                        event_info='{$sel_card}', 
                                        event_sms_desc='{$myiam_link}', 
                                        pcode='{$pcode}', 
                                        event_type='{$button_txt}', 
                                        mobile='{$row_mem['mem_phone']}', 
                                        regdate='{$cur_time}', 
                                        m_id='{$mem_id}', 
                                        read_cnt=0, 
                                        object='{$autojoin_img_path}',
                                        event_req_link='{$event_link}'";
mysqli_query($self_con,$sql_event) or die(mysqli_error($self_con));
$event_idx = mysqli_insert_id($self_con);

//added by amigo
$reserv_id = $_POST['reserv_id'];
$mobile = $_POST['send_phonenum'];

if($reserv_event_name != "") {

    $sql = "select * from Gn_event_sms_info where sms_idx='{$reserv_id}'";
    $lresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $lrow = mysqli_fetch_array($lresult);
    $reserv_sms_idx = $lrow['sms_idx'];
    
    $sql = "insert into gn_automem_sms_reserv set auto_event_id='{$event_idx}',
                                 reserv_sms_id='{$reserv_sms_idx}',
                                 send_num='{$mobile}'";
    $result=mysqli_query($self_con,$sql);
}
//end block

$transUrl = "http://".$HTTP_HOST."/event/automember.php?pcode=".$pcode."&eventidx=".$event_idx;
$transUrl = get_short_url($transUrl);
$insert_short_url = "update Gn_event set short_url='{$transUrl}' where event_idx={$event_idx}";
mysqli_query($self_con,$insert_short_url) or die(mysqli_error($self_con));

//echo '{"shorturl":"'.$transUrl.'"}';
echo json_encode(array("shorturl"=>$surl));
?>