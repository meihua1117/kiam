<?
include_once "lib/rlatjd_fun.php";
if($_SESSION['one_member_id'] == "")
	exit;
$sql="select * from Gn_Member where mem_id='$_SESSION[one_member_id]' and site != '' ";
$resul=mysqli_query($self_con,$sql);
$data=mysqli_fetch_array($resul);

$_SESSION['allat_amt'] = $_POST['allat_amt'];
$_COOKIE['allat_amt'] = $_POST['allat_amt'];
$_SESSION['allat_amt_'] = $_POST['allat_amt'];
$_COOKIE['allat_amt_'] = $_POST['allat_amt'];
$_POST[max_cnt] = $_POST[phone_cnt];
 
if($_POST[allat_order_no] != "") {
    $sql = "delete from tjd_pay_result where  idx='$_POST[allat_order_no]' and  buyer_id='$_SESSION[one_member_id]'";
    mysqli_query($self_con,$sql);
}
if($_POST[payMethod] != "") {
    $payMethod = $_POST[payMethod];
}else
    $payMethod = 'CARD';
$sql = "insert into tjd_pay_result
            set idx='$_POST[allat_order_no]',
                orderNumber='$_POST[allat_order_no]',
                VACT_InputName='$data[mem_name]',
                TotPrice='$_POST[allat_amt]',
                month_cnt='$_POST[month_cnt]',
                end_date=date_add(now(),INTERVAL {$_POST[month_cnt]} month),
                end_status='N',
                buyertel='$data[mem_phone]',
                buyeremail='$data[mem_email]',
                payMethod='CARD',
                buyer_id='$_SESSION[one_member_id]',
                date=NOW(),
                member_type='$_POST[member_type]',
                max_cnt='$_POST[max_cnt]',
                phone_cnt='$_POST[phone_cnt]',
                add_phone='$_POST[add_phone]',
                db_cnt='$_POST[db_cnt]',
                email_cnt='$_POST[email_cnt]',
                onestep1='$_POST[onestep1]',
                onestep2='$_POST[onestep2]'";
$res_result = mysqli_query($self_con,$sql);
?>