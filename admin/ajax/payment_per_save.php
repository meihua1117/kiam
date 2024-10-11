<?@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);
$payment_day = $payment_day?$payment_day:0;
$query = "select * from tjd_pay_result where no='$no'";
$res = mysqli_query($self_con,$query);
$row = mysqli_fetch_array($res);
if($row[no] == "") exit;
$query = "update tjd_pay_result set share_per='$share_per', branch_share_per='$branch_share_per'  where `no`='$no'";
mysqli_query($self_con,$query);    
echo "<script>alert('저장되었습니다.');location='/admin/payment_per_list.php';</script>";
exit;
?>