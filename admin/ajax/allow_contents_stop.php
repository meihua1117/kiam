<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
date_default_timezone_set('Asia/Seoul');
$cur_time = date("Y-m-d H:i:s");
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

if(isset($_POST['share'])){
    $query = "select * from share_contents_mng where id='$id'";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    if($row[id] == "") exit;

    if($row[share_state] == 1) {
        $sql="update share_contents_mng set  share_state=0 where id='$id' ";
        mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
    } else {
        $sql="update share_contents_mng set   share_state=1 where id='$id' ";
        mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
    }
}
else if($_POST['get_con']){
    $query = "select * from reg_biz_contents where id={$id}";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    if($row[id] == "") exit;

    if($row['work_status'] == 1){
        $sql="update reg_biz_contents set work_status=2, up_date='{$cur_time}' where id={$id}";
        mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
    }
    else{
        $sql="update reg_biz_contents set work_status=1, up_date='{$cur_time}' where id={$id}";
        mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
    }
}
else{
    $query = "select * from get_crawler_bizinfo where id='$id'";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    if($row[id] == "") exit;
    
    if($row[allow_state] == 1) {
        $sql="update get_crawler_bizinfo set  allow_state=0 where id='$id' ";
        mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
    } else {
        $sql="update get_crawler_bizinfo set   allow_state=1 where id='$id' ";
        mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
    }
}
?>