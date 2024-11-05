<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/

$cur_time = date("Y-m-d H:i:s");
$cur_time1 = date("YmdHis");
extract($_POST);

if($mode == "save")
{
    $idx = $_POST["idx"]; 
    if($_FILES['img']['name']) {
        $info = explode(".",$_FILES['img']['name']);
        $ext = $info[count($info)-1];
        $filename = time().".".$ext;
        $img_path = "http://www.kiam.kr".gcUpload($_FILES['img']['name'], $_FILES['img']['tmp_name'], $_FILES['img']['size'], "ad", $filename);
    }
    if($idx == "") {
        $query="insert into gn_mms_callback set 
                                    `title`          ='$title', 
                                    `content`      ='$content', 
                                    `img`        ='$img_path',
                                    `iam_link`   = '$iam_link',
                                    regdate=NOW()
                                     ";
        mysqli_query($self_con,$query);	
    } else {
        if($img_path)
            $addQuery = " `img`        ='$img_path', ";
        $query="update gn_mms_callback set `title`          ='$title', 
                                    `content`      ='$content', 
                                    `iam_link`   = '$iam_link',
                                    $addQuery
                                    regdate=NOW()
                                    WHERE idx=$idx";
        mysqli_query($self_con,$query);		
    }
    echo "<script>alert('저장되었습니다.');location='/admin/mms_callback_list.php';</script>";
}
else if($mode == "reg_msg")
{
    $call_img_path = '';
    if($_FILES['call_img']['name']) {
        $info = explode(".",$_FILES['call_img']['name']);
        $ext = $info[count($info)-1];
        $filename = time().".".$ext;
        $call_img_path = "http://www.kiam.kr".gcUpload($_FILES['call_img']['name'], $_FILES['call_img']['tmp_name'], $_FILES['call_img']['size'], "ad", $filename);
    }
    $query="insert into gn_mms_callback set 
                                `title`          ='$call_title', 
                                `content`      ='$call_content', 
                                `img`        ='$call_img_path',
                                `iam_link`   = '$iam_link',
                                `type`       = 3,
                                regdate=NOW(),
                                service_state=1";
    mysqli_query($self_con,$query);	
    $callback_no = mysqli_insert_id($self_con);

    $sql_mem = "select * from Gn_Member where mem_id='{$_SESSION['iam_member_id']}'";
    $res_mem = mysqli_query($self_con,$sql_mem);
    $row_mem = mysqli_fetch_array($res_mem);

    $event_name_eng = "callback msg reg".$cur_time1;
    $pcode = "callbackmsg".$cur_time1;

    $sql_event = "insert into Gn_event set event_name_kor='콜백메시지관리자설정동의', event_name_eng='$event_name_eng', event_title='{$msgtitle_call}', event_desc='{$msgdesc_call}', event_info='{$call_title}', event_sms_desc='{$call_content}', pcode='{$pcode}', event_type='{$iam_link}', mobile='{$row_mem['mem_phone']}', regdate='{$cur_time}', m_id='{$_SESSION['iam_member_id']}', read_cnt=0, object='{$call_img_path}', callback_no={$callback_no}";
    mysqli_query($self_con,$sql_event) or die(mysqli_error($self_con));
    $event_idx = mysqli_insert_id($self_con);

    $transUrl = "http://".$HTTP_HOST."/event/callbackmsg.php?pcode=".$pcode."&eventidx=".$event_idx;
    $transUrl = get_short_url($transUrl);
    $insert_short_url = "update Gn_event set short_url='{$transUrl}' where event_idx={$event_idx}";
    mysqli_query($self_con,$insert_short_url) or die(mysqli_error($self_con));

    echo '{"shorturl":"'.$transUrl.'"}';
}
else if($mode == "servicesave"){
    $idx = $_POST["idx"]; 
    if($_FILES['img']['name']) {
        $info = explode(".",$_FILES['img']['name']);
        $ext = $info[count($info)-1];
        $filename = time().".".$ext;
        $img_path = "http://www.kiam.kr".gcUpload($_FILES['img']['name'], $_FILES['img']['tmp_name'], $_FILES['img']['size'], "ad", $filename);
    }
    if($idx == "") {
        $query="insert into gn_mms_callback set `title`          ='$title', 
                                    `content`      ='$content', 
                                    `img`        ='$img_path',
                                    `iam_link`   = '$iam_link',
                                                regdate=NOW()";
        mysqli_query($self_con,$query);	
    } else {
        if($img_path)
            $addQuery = " `img`        ='$img_path', ";
        $query="update gn_mms_callback set  `title`          ='$title', 
                                    `content`      ='$content', 
                                    `iam_link`   = '$iam_link',
                                    $addQuery
                                    regdate=NOW()
                                    WHERE idx='$idx'";
        mysqli_query($self_con,$query);		
    }
    if(isset($_POST['isservice'])){
        if($img_path)
            $addQuery = " `object`        ='$img_path', ";
        $query = "update Gn_event set event_info='$title', event_sms_desc='$content', event_type='$iam_link', $addQuery regdate=now() where callback_no='$idx'";
        mysqli_query($self_con,$query);
        echo "<script>alert('저장되었습니다.');location='/admin/mms_callback_list_service.php';</script>";
    }
    else{
	echo "<script>alert('저장되었습니다.');location='/admin/mms_callback_list_member.php';</script>";
    }
}
else if($mode == "type")
{
    if($type != 0)
    {
        $query="update gn_mms_callback set type = 0 where type = '$type'";
        mysqli_query($self_con,$query);
        $query="update gn_mms_callback set `type`  ='$type', 
                        regdate=NOW()
                                            WHERE idx='$idx'";
        mysqli_query($self_con,$query);
    }
    else{
        $query="update gn_mms_callback set `type`          = 0, 
                        regdate=NOW()
                                        WHERE idx='$idx'";
        mysqli_query($self_con,$query);
    }
}
else if($mode == "duplicate_msg"){
    if($status){
        $sql_format = "update gn_mms_callback set duplicate_event_idx=0 where duplicate_event_idx!=0";
        mysqli_query($self_con,$sql_format);
        $sql_update = "update gn_mms_callback set duplicate_event_idx={$id} where idx='{$mms_id}'";
        mysqli_query($self_con,$sql_update);
        
        $sql_service_ids = "select mem_id, main_domain from Gn_Iam_Service where mem_id!='iam1' order by idx asc";
        $res_service_ids = mysqli_query($self_con,$sql_service_ids);
        while($row_servicee_ids = mysqli_fetch_array($res_service_ids)){
            $sql_mem_data = "select mem_phone from Gn_Member where mem_id='{$row_servicee_ids['mem_id']}'";
            $res_mem_data = mysqli_query($self_con,$sql_mem_data);
            $row_mem_data = mysqli_fetch_array($res_mem_data);
            if($row_mem_data['mem_phone'] != ""){
                $rand_num = rand(100, 999);
                $cur_time1 = date("YmdHis");
                $event_name_eng = "callback msg reg".$cur_time1.$rand_num;
                $pcode = "callbackmsg".$cur_time1.$rand_num;

                $sql_dup_mms = "INSERT INTO gn_mms_callback(title, content, img, iam_link, type, regdate, service_state, allow_state, mem_sel_cnt, mb_id, duplicate_event_idx) 
                (SELECT title, content, img, iam_link, type, now(), service_state, allow_state, 0, NULL, 0 FROM gn_mms_callback WHERE idx='{$mms_id}')";
                mysqli_query($self_con,$sql_dup_mms) or die(mysqli_error($self_con));
                $mms_idx = mysqli_insert_id($self_con);
            
                $sql_dup_event = "INSERT INTO Gn_event(event_name_kor, event_name_eng, event_title, event_desc, event_info, event_sms_desc, pcode, event_type, mobile, regdate, ip_addr, m_id, short_url, read_cnt, cnt, object, callback_no, event_req_link, daily_req_link) 
                (SELECT event_name_kor, '{$event_name_eng}', event_title, event_desc, event_info, event_sms_desc, '{$pcode}', event_type, '{$row_mem_data['mem_phone']}', now(), ip_addr, '{$row_servicee_ids['mem_id']}', '', 0, cnt, object, {$mms_idx}, event_req_link, daily_req_link FROM Gn_event WHERE event_idx='{$id}')";
                mysqli_query($self_con,$sql_dup_event) or die(mysqli_error($self_con));
                $event_idx = mysqli_insert_id($self_con);
            
                $transUrl = $row_servicee_ids['main_domain']."/event/callbackmsg.php?pcode=".$pcode."&eventidx=".$event_idx;
                $transUrl = get_short_url($transUrl);
                $insert_short_url = "update Gn_event set short_url='{$transUrl}' where event_idx={$event_idx}";
                mysqli_query($self_con,$insert_short_url) or die(mysqli_error($self_con));
            }
        }
    }
    else{
        $sql_format = "update gn_mms_callback set duplicate_event_idx=0 where duplicate_event_idx!=0";
        mysqli_query($self_con,$sql_format);
    }
    echo 1;
}
exit;

?>