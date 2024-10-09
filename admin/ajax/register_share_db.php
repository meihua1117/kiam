<?
@header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Seoul');
$date_today1=date("Y-m-d H:i:s");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$end_date = NULL;

$share_obj = $_POST['share_obj'];
$share_domain = $_POST['share_domain'];
$server_position = $_POST['server_position'];
$work_key = $_POST['work_key'];
$public_key = $_POST['public_key'];
$edu_key = $_POST['edu_key'];
$other_key = $_POST['other_key'];
$reg_date = $_POST['reg_date'];
$end_date = $_POST['end_date'];
$contents_cnt = $_POST['contents_cnt'];
$manager = $_POST['manager'];

if(isset($_POST['updat'])){
    $sql = "update share_contents_mng set share_obj='{$share_obj}', share_domain='{$share_domain}', server_position='{$server_position}', work_key='{$work_key}', public_key='{$public_key}', edu_key='{$edu_key}', other_key='{$other_key}', contents_cnt={$contents_cnt}, reg_date='{$reg_date}', end_date='{$end_date}', manager='{$manager}' where id={$_POST['updat']}";
    // echo $sql; exit;
    mysql_query($sql);
    echo 2;
}
else{
    $sql = "insert into share_contents_mng set share_obj='{$share_obj}', share_domain='{$share_domain}', server_position='{$server_position}', work_key='{$work_key}', public_key='{$public_key}', edu_key='{$edu_key}', other_key='{$other_key}', contents_cnt={$contents_cnt}, reg_date='{$reg_date}', manager='{$manager}', share_state=1";
    // echo $sql; exit;
    mysql_query($sql);
    echo 1;
}
?>