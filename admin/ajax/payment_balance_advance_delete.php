<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 정산관리
*/
extract($_GET);
    $sql="delete from tjd_pay_result_balance where bid='$bid' ";
    mysqli_query($self_con,$sql);

echo "<script>alert('삭제되었습니다.');location='/admin/payment_balance_list_advance_detail.php?search_year=$_GET[search_year]&search_month=$_GET[search_month]&mem_id=$_GET[mem_id]';</script>";
exit; 
?>