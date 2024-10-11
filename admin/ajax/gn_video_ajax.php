<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 알림 정보 수정
*/
extract($_POST);
$uploaddir = '../../upload/';
$no = $_POST["no"];
if($_POST['mode'] == "creat") {
    $sql="insert into gn_video (`type`, title, link,use_status,display) values ('$type', '$title','$link','$use_status','$display')";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
}
else if($_POST['mode'] == "updat") {
    $sql="update gn_video set title='$title', use_status = '$use_status', display = '$display',link = '$link' where no = '$no'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
} else if($_POST['mode'] == "del") {
    $query="delete  from gn_video WHERE no='$no'";
    mysqli_query($self_con,$query);	
}
if($type == 'iam')
    echo "<script>alert('저장되었습니다.');location='/admin/iam_video_list.php';</script>";
else
    echo "<script>alert('저장되었습니다.');location='/admin/selling_video_list.php';</script>";
exit;
?>