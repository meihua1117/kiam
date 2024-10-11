<?
include_once "_head.php";
$data = $member_1;
if($_POST[member_type] != "")
    $_POST[iam_pay_type] = $_POST[member_type];
$sql = "insert into tjd_pay_result
    set idx='$_POST[allat_order_no]',
        orderNumber='$_POST[allat_order_no]',
        VACT_InputName='{$data['mem_name']}',
        TotPrice='$_POST[allat_amt]',
        end_date=date_add(now(),INTERVAL {$_POST[month_cnt]} month),
        end_status='N',
        buyertel='$data[mem_phone]',
        buyeremail='$data[mem_email]',
        payMethod='MONTH',
        buyer_id='{$_SESSION['one_member_id']}',
        date=NOW(),
        iam_pay_type='$_POST[iam_pay_type]',
        iam_card_cnt='$_POST[iam_card_cnt]',
        iam_share_cnt='$_POST[iam_share_cnt]',
        month_cnt='$_POST[month_cnt]',
        member_cnt='$_POST[member_cnt]',
        monthly_yn = 'Y'
        ";
mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
