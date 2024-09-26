<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
$date = date("Y-m-d H:i:s");
$mem_id = $_POST["mem_id"];
// 정보 확인
$sql = "select * from tjd_pay_result where buyer_id='$mem_id' and end_status='Y' and (member_type like '%professional%' or member_type like '%enterprise%') and `end_date` > '$date'";
$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$row = mysqli_fetch_array($result);
echo json_encode(array("mem_cnt"=>$row['member_cnt'],"my_card_cnt"=>$row['iam_card_cnt'],"iam_card_cnt"=>1,"iam_share_cnt"=>$row['iam_share_cnt']));
?>