<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 아이엠 삭제
*/
extract($_POST);
$id = $_POST['id'];
if(isset($_POST['reg_contents'])){
    $sql="delete from reg_biz_contents where id='$id'";
    $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
}
else if(isset($_POST['allow_contents'])){
    $sql="delete from get_crawler_bizinfo where id='$id'";
    $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
}
else if(isset($_POST['share_contents'])){
    $sql="delete from share_contents_mng where id='$id'";
    $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
}
// 정보 확인
echo "{\"result\":\"$result\"}";
?>