<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_GET);


//			$sql_m="update Gn_Member set fujia_date1=now() , fujia_date2=date_add(now(),INTERVAL {$_POST[month_cnt]} month)  where mem_id='$member_1[mem_id]' ";
//			mysql_query($sql_m)or die(mysql_error());
//			//print_r($_POST);
//			$sql_m="update Gn_Member set   phone_cnt=phone_cnt+'$_POST[add_phone]' where mem_id='$member_1[mem_id]' ";
//			//echo $sql_m;
//			mysql_query($sql_m)or die(mysql_error());		

$query = "select * from tjd_pay_result_db where no='$no'";
$res = mysql_query($query);
$row = mysql_fetch_array($res);
if($row[no] == "") exit;


$query = "delete from tjd_pay_result_db where `no`='$no'";
//echo $query."<BR>";
mysql_query($query);

echo "<script>alert('삭제되었습니다.');location='/admin/crawler_payment_list.php';</script>";
exit;
?>