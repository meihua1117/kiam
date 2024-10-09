<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$gaid = $_POST["gaid"]; 
if($gaid == "") {
    if($_FILES['img']['name']) {
        $info = explode(".",$_FILES['img']['name']);
        $ext = $info[count($info)-1];
        $filename = time().".".$ext;
        $img_path = gcUpload($_FILES['img']['name'], $_FILES['img']['tmp_name'], $_FILES['img']['size'], "ad", $filename);
    }
    $query="insert into Gn_Ad set `client`          ='$client', 
                                  `start_date`      ='$start_date', 
                                  `send_start_date` ='$send_start_date', 
                                  `send_end_date`   ='$send_end_date', 
                                  `send_count`      ='$send_count', 
                                  `title`           ='$title', 
                                  `content`         ='$content', 
                                  `img_path`        ='$img_path', 
                                  `sort_order`      ='$sort_order', 
                                  `status`          ='$status', 
                                  `regdate`         =NOW() 
                                 ";
    mysql_query($query);	
} else {
    if($_FILES['img']['name']) {
        $info = explode(".",$_FILES['img']['name']);
        $ext = $info[count($info)-1];
        $filename = time().".".$ext;
        $img_path = gcUpload($_FILES['img']['name'], $_FILES['img']['tmp_name'], $_FILES['img']['size'], "ad", $filename);
    }
    if($img_path)
        $addQuery = " `img_path`        ='$img_path', ";
    $query="update      Gn_Ad set `client`          ='$client', 
                                  `start_date`      ='$start_date', 
                                  `send_start_date` ='$send_start_date', 
                                  `send_end_date`   ='$send_end_date', 
                                  `send_count`      ='$send_count', 
                                  `title`           ='$title', 
                                  `content`         ='$content', 
                                  $addQuery
                                  `status`          ='$status', 
                                  `moddate`         =NOW()
                         WHERE gaid='$gaid'
                                 ";
    mysql_query($query);	
}
echo "<script>alert('저장되었습니다.');location='/admin/ad_list.php';</script>";
exit;
?>