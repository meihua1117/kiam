<?
@header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Seoul');
$date_today1=date("Y-m-d H:i:s");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$info_source = $_POST['info_source'];
if($_POST['info_type'] == 1){
    $info_type = "지원사업";
}
else if($_POST['info_type'] == 2){
    $info_type = "입찰공고";
}
else if($_POST['info_type'] == 3){
    $info_type = "행사교육";
}
else if($_POST['info_type'] == 4){
    $info_type = "기타정보";
}
// $info_type = $_POST['info_type'];
$web_address = $_POST['web_address'];
$search_key = $_POST['search_key'];
$status = $_POST['status'];
$keyword = $_POST['keyword'];
$get_time = $_POST['get_time'];
$memo = $_POST['memo'];
$reg_date = $_POST['reg_date'];
$status_work = $_POST['status_work'];

if(isset($_POST["updat"])){
    $sql = "update reg_biz_contents set info_source='{$info_source}', info_type='{$info_type}', web_address='{$web_address}', search_key='{$search_key}', status={$status}, keyword='{$keyword}', get_time='{$get_time}', memo='{$memo}', up_date='{$date_today1}', work_status={$status_work} where id={$_POST["updat"]}";
    // echo $sql; exit;
    mysqli_query($self_con, $sql);
    echo 2;
}
else{
    $sql = "insert into reg_biz_contents set info_source='{$info_source}', info_type='{$info_type}', web_address='{$web_address}', search_key='{$search_key}', status={$status}, keyword='{$keyword}', get_time='{$get_time}', memo='{$memo}', reg_date='{$reg_date}', up_date='{$reg_date}', work_status={$status_work}";
    // echo $sql; exit;
    mysqli_query($self_con, $sql);
    echo 1;
}
?>