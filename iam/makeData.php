<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$_SESSION['allat_amt'] = $_POST['allat_amt'];
$_COOKIE['allat_amt'] = $_POST['allat_amt'];
$_SESSION['allat_amt_'] = $_POST['allat_amt'];
$_COOKIE['allat_amt_'] = $_POST['allat_amt'];
$_POST['max_cnt'] = $_POST['phone_cnt'];
$_POST['add_phone'] = $_POST['phone_cnt'] * 1 / 9000;
if($_POST['allat_order_no'] != "") {
    $sql = "delete from tjd_pay_result where  idx='{$_POST['allat_order_no']}' and  buyer_id='{$_SESSION['iam_member_id']}'";
    mysqli_query($self_con,$sql);
}
if($_POST['payMethod']!= "")
    $payMethod = $_POST['payMethod'];
else
    $payMethod = "CARD";

$sql = "insert into tjd_pay_result set 
            idx='{$_POST['allat_order_no']}',
            orderNumber='{$_POST['allat_order_no']}',
            VACT_InputName='{$member_iam['mem_name']}',
            TotPrice='{$_POST['allat_amt']}',
            month_cnt='{$_POST['month_cnt']}',
            end_date=date_add(now(),INTERVAL {$_POST['month_cnt']} month),
            end_status='N',
            buyertel='{$member_iam['mem_phone']}',
            buyeremail='{$member_iam['mem_email']}',
            payMethod='$payMethod',
            buyer_id='{$member_iam['mem_id']}',
            date=NOW(),
            member_type='{$_POST['member_type']}',
            max_cnt='{$_POST['max_cnt']}',
            phone_cnt='{$_POST['phone_cnt']}',
            add_phone='{$_POST['add_phone']}',
            db_cnt='{$_POST['db_cnt']}',
            email_cnt='{$_POST['email_cnt']}',
            shop_cnt='$_POST[shop_cnt]',
            onestep1='{$_POST['onestep1']}',
            onestep2='{$_POST['onestep2']}',
            iam_card_cnt='{$_POST['iam_card_cnt']}',
            iam_share_cnt='{$_POST['iam_share_cnt']}',
            member_cnt='{$_POST['member_cnt']}'";
$res_result = mysqli_query($self_con,$sql);
// set_service_mem_cnt($member_iam['mem_id'], $_POST['member_cnt']);
?>