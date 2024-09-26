<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_GET);
$query = "select * from tjd_pay_result_db where no='$no'";
$res = mysqli_query($self_con, $query);
$row = mysqli_fetch_array($res);
if($row['no'] == "") exit;
$query = "delete from tjd_pay_result_db where `no`='$no'";
mysqli_query($self_con, $query);
echo "<script>alert('삭제되었습니다.');location='/admin/crawler_payment_list.php';</script>";
exit;
?>