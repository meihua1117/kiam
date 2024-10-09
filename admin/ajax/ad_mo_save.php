<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$cam_id = $_POST["cam_id"]; 
if($cam_id == "") {
    if($_FILES['img']['name']) {
        $info = explode(".",$_FILES['img']['name']);
        $ext = $info[count($info)-1];
        $filename = mktime().".".$ext;
        $img_path = "http://www.kiam.kr".gcUpload($_FILES['img']['name'], $_FILES['img']['tmp_name'], $_FILES['img']['size'], "ad", $filename);
    }
    $query="insert into Gn_Ad_Manager set `client`          ='$client', 
                                  `start_date`      ='$start_date', 
                                  `send_start_date` ='$send_start_date', 
                                  `send_end_date`   ='$send_end_date', 
                                  `title`           ='$title', 
                                  `move_url`         ='$move_url', 
                                  `img_url`        ='$img_path', 
                                  `use_yn`      ='$use_yn', 
                                  `ad_position`      ='$ad_position', 
                                  view_time = '$view_time',
                                  `display_order`      ='$display_order', 
                                  `start_time`      ='$start_time', 
                                  `end_time`      ='$end_time'
                                 ";
    mysql_query($query);	
} else {
    if($_FILES['img']['name']) {
        $info = explode(".",$_FILES['img']['name']);
        $ext = $info[count($info)-1];
        $filename = mktime().".".$ext;
        $img_path = "http://www.kiam.kr".gcUpload($_FILES['img']['name'], $_FILES['img']['tmp_name'], $_FILES['img']['size'], "ad", $filename);
    }
    if($img_path)
        $addQuery = " `img_url`        ='$img_path', ";
    $query="update      Gn_Ad_Manager set `client`          ='$client', 
                                  `start_date`      ='$start_date', 
                                  `send_start_date` ='$send_start_date', 
                                  `send_end_date`   ='$send_end_date', 
                                  `title`           ='$title', 
                                  `move_url`         ='$move_url', 
                                  `use_yn`      ='$use_yn', 
                                  `ad_position`      ='$ad_position', 
                                  `display_order`      ='$display_order', 
                                  view_time = '$view_time',
                                  `start_time`      ='$start_time', 
                                  $addQuery
                                  `end_time`      ='$end_time'
                         WHERE cam_id='$cam_id'
                                 ";
    mysql_query($query);	
}
echo "<script>alert('저장되었습니다.');location='/admin/ad_mo_list.php';</script>";
exit;
?>