<?
header("Content-Type: application/json; charset=utf-8");
//header("Content-Type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
// include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";

$cur_time = date("Y-m-d H:i:s");
$cur_time1 = date("YmdHis");
$index = 0;
$result = array();
extract($_POST);

$userid= isset($_POST['userid'])?$_POST['userid']:"";
$token = $_POST['mem_token'];
$phone_num = $_POST['phone_num'];

    $service_callback= isset($_POST['service_callback'])?$_POST['service_callback']:"";//본사,분양사,개인콜백 판정
    $callback_idx = isset($_POST['callback_idx'])?$_POST['callback_idx']:0;//콜백아이디 번호
    $select_service = isset($_POST['select_service'])?$_POST['select_service']:"N";
    $callback_type = isset($_POST['callback_type'])?$_POST['callback_type']:0;//콜백메시지 타입
    
    $select_check_box = isset($_POST['select_check_box'])?$_POST['select_check_box']:"N";
    $check_box_type = isset($_POST['check_box_type'])?$_POST['check_box_type']:0;//체크박스 타입 (1:전화수신 2:문자수신)
    // $checked_all = isset($_POST['checked_all'])?$_POST['checked_all']:"N";//체크박스 모두 선택&해지
    $check_box_val = isset($_POST['check_box_val'])?$_POST['check_box_val']:0;//체크박스 상태
    
    $update_msg = isset($_POST['update_msg'])?$_POST['update_msg']:"N";//개인 콜백메시지 업뎃하기
    $delete_msg = isset($_POST['delete_msg'])?$_POST['delete_msg']:"N";//개인 콜백메시지 삭제하기
    
    $sql_mem_info = "select * from Gn_Member where mem_id='{$userid}'";
    $res_mem_info = mysql_query($sql_mem_info);
    $row_mem_info = mysql_fetch_array($res_mem_info);
    
    if($select_service == "Y"){
        $sql_one_type = "select idx, type from gn_mms_callback where service_state=2 and mb_id='{$userid}' and type={$callback_type}";
        $res_one_type = mysql_query($sql_one_type);
        $row_one_type = mysql_fetch_array($res_one_type);
    
    
        if($callback_type == 1){
            if($row_mem_info['mun_callback'] == $callback_idx){
                $callback_type = 3;
                $sql_update = "update Gn_Member set phone_callback={$callback_idx}, phone_callback_state={$callback_type}, mun_callback_state={$callback_type} where mem_id='{$userid}'";
            }
            else{
                $sql_update = "update Gn_Member set phone_callback={$callback_idx}, phone_callback_state={$callback_type} where mem_id='{$userid}'";
            }
            mysql_query($sql_update);
        }
        if($callback_type == 2){
            if($row_mem_info['phone_callback'] == $callback_idx){
                $callback_type = 3;
                $sql_update = "update Gn_Member set mun_callback={$callback_idx}, phone_callback_state={$callback_type}, mun_callback_state={$callback_type} where mem_id='{$userid}'";
            }
            else{
                $sql_update = "update Gn_Member set mun_callback={$callback_idx}, mun_callback_state={$callback_type} where mem_id='{$userid}'";
            }
            mysql_query($sql_update);
        }
    
        if($service_callback == 2){//개인콜백 선택인 경우
            $sql_mms_update = "update gn_mms_callback set type={$callback_type}, regdate=now() where idx={$callback_idx}";
            mysql_query($sql_mms_update);
            if($row_one_type['type'] != ""){
                if($row_one_type['type'] == $_POST['callback_type']){
                    $sql_update_one = "update gn_mms_callback set type=0 where idx={$row_one_type['idx']}";
                    mysql_query($sql_update_one);
                }
            }
        }
        else if($service_callback == 1){//분양사콜백 선택인 경우
            $sql_update = "update Gn_Member set mem_callback={$callback_idx} where mem_id='{$userid}'";
            mysql_query($sql_update);
            $sql_mms_update = "update gn_mms_callback set regdate=now() where idx={$callback_idx}";
            mysql_query($sql_mms_update);
            if($row_one_type['type'] != ""){
                if($row_one_type['type'] == $_POST['callback_type']){
                    $sql_update_one = "update gn_mms_callback set type=0 where idx={$row_one_type['idx']}";
                    mysql_query($sql_update_one);
                }
            }
        }
        else{//본사콜백인경우
            $sql_mms_update = "update gn_mms_callback set regdate=now() where idx={$callback_idx}";
            mysql_query($sql_mms_update);
    
            if($row_one_type['type'] != ""){
                if($row_one_type['type'] == $_POST['callback_type']){
                    $sql_update_one = "update gn_mms_callback set type=0 where idx={$row_one_type['idx']}";
                    mysql_query($sql_update_one);
                }
            }
        }
        echo '{"result":"success","token_res":"1"}';
        exit;
    }
    if($select_check_box == "Y"){
        if($check_box_type == 1){
            $sql_update = "update Gn_Member set mem_callback_phone_state={$check_box_val} where mem_id='{$userid}'";
            mysql_query($sql_update);
        }
        else{
            $sql_update = "update Gn_Member set mem_callback_mun_state={$check_box_val} where mem_id='{$userid}'";
            mysql_query($sql_update);
        }
        echo '{"result":"success","token_res":"1"}';
        exit;
    }
    
    if($delete_msg == "Y"){
        $sql_del = "delete from gn_mms_callback where idx={$callback_idx}";
        mysql_query($sql_del);
        $sql_mms_update = "update gn_mms_callback set regdate=NOW() order by idx asc limit 1";
        mysql_query($sql_mms_update);
    
        $sql_sel_first_main = "select * from gn_mms_callback where service_state=0 order by idx asc limit 1";
        $res_main = mysql_query($sql_sel_first_main);
        $row_main = mysql_fetch_array($res_main);
        $main_idx = $row_main['idx'];
    
        if($row_mem_info['phone_callback'] == $callback_idx && $row_mem_info['mun_callback'] == $callback_idx){
            $sql_update = "update Gn_Member set phone_callback={$main_idx}, mun_callback={$main_idx} where mem_id='{$userid}'";
        }
        else if($row_mem_info['phone_callback'] == $callback_idx){
            if($row_mem_info['mun_callback'] == $main_idx){
                $sql_update = "update Gn_Member set phone_callback={$main_idx}, phone_callback_state=3, mun_callback_state=3 where mem_id='{$userid}'";
            }
            else{
                $sql_update = "update Gn_Member set phone_callback={$main_idx} where mem_id='{$userid}'";
            }
        }
        else if($row_mem_info['mun_callback'] == $callback_idx){
            if($row_mem_info['phone_callback'] == $main_idx){
                $sql_update = "update Gn_Member set mun_callback={$main_idx}, phone_callback_state=3, mun_callback_state=3 where mem_id='{$userid}'";
            }
            else{
                $sql_update = "update Gn_Member set mun_callback={$main_idx} where mem_id='{$userid}'";
            }
        }
        mysql_query($sql_update);
        echo '{"result":"success","token_res":"1"}';
        exit;
    }
    
    $msg_title= isset($_POST['msg_title'])?$_POST['msg_title']:"";
    $msg_content= isset($_POST['msg_content'])?$_POST['msg_content']:"";
    $msg_link= isset($_POST['msg_link'])?$_POST['msg_link']:"";
    
    $msg_img_path = "";
    if($_FILES['msg_img']['name']) {
        $idx=time();
        $file = explode(".", $_FILES['msg_img']['name']);
        $filename = $_FILES['msg_img']['name'];
        $filePath = $_FILES['msg_img']['tmp_name'];
        $exif = exif_read_data($_FILES['msg_img']['tmp_name']);
        if (!empty($exif['Orientation'])) {
            $imageResource = imagecreatefromjpeg($filePath);
            switch ($exif['Orientation']) {
                case 3:
                $image = imagerotate($imageResource, 180, 0);
                break;
                case 6:
                $image = imagerotate($imageResource, -90, 0);
                break;
                case 8:
                $image = imagerotate($imageResource, 90, 0);
                break;
                default:
                $image = $imageResource;
            } 
        }
        imagejpeg($image, $filename, 90);
        $file_path='../ad/'.$idx.'.'.$file[1]; //이미지화일명은 인덱스번호로 지정
        if(move_uploaded_file($filePath, $file_path)) {
            $msg_img_path = "http://www.kiam.kr/ad/".$idx.'.'.$file[1];
        }
        // $info = explode(".", $_FILES['msg_img']['tmp_name']);
        // $ext = $info[count($info)-1];
        // $filename = mktime().".".$ext;
        // $msg_img_path = "http://www.kiam.kr".gcUpload($_FILES['msg_img']['name'], $_FILES['msg_img']['tmp_name'], $_FILES['msg_img']['size'], "ad", $filename);
    }
    
    if($update_msg == "Y"){
        $add_query = "";
        if($msg_title)
            $add_query .= " `title`   ='$msg_title', ";
        if($msg_content)
            $add_query .= " `content` ='$msg_content', ";
        if($msg_img_path)
            $add_query .= " `img`     ='$msg_img_path', ";
        if($msg_link)
            $add_query .= " `iam_link` ='$msg_link', ";
        $query="update gn_mms_callback set 
                $add_query
                `regdate`    =NOW() 
                where mb_id='$userid' and idx=$callback_idx
                    ";
        mysql_query($query);
    }
    else{
        $query="insert into gn_mms_callback set 
        `title`          ='$msg_title', 
        `content`      ='$msg_content', 
        `img`        ='$msg_img_path',
        `iam_link`   = '$msg_link',
        `regdate`    =NOW(),
        `service_state` = 2,
        `mb_id`      ='$userid'
            ";
        mysql_query($query);
    }
    
    $query2 = "select SQL_CALC_FOUND_ROWS * from gn_mms_callback where service_state=2 and mb_id='$userid' order by idx";
    $res2   = mysql_query($query2);
    $items = array();
    while($rows_mem = mysql_fetch_assoc($res2))
    {
        $item['idx'] = $rows_mem['idx'];
        $item['title'] = $rows_mem['title'];
        $item['content'] = $rows_mem['content'];
        $item['image'] = $rows_mem['img'];
        $item['type'] = $rows_mem['type'];
        $item['service_state'] = $rows_mem['service_state'];
        array_push($items, $item);
        //$items[$index++] = $item;
    }
    $result['items'] = $items;
    $result['token_res'] = 1;
    
    //echo json_encode($result,JSON_UNESCAPED_UNICODE);
    echo json_encode(array('token_res'=>1,'items'=>$items));
?>