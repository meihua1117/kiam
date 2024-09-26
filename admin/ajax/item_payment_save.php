<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
extract($_POST);
//$payment_day = $payment_day?$payment_day:0;
$query = "select * from Gn_Item_Pay_Result where no='$no'";
$res = mysqli_query($self_con, $query);
$row = mysqli_fetch_array($res);
if($row['no'] == "") exit;
if($row['pay_status'] == "Y" && $pay_status != "A") {
    $query = "update Gn_Item_Pay_Result set item_price='$price', pay_status='E'  where `no`='$no'";
    mysqli_query($self_con, $query);
} else if($row['pay_status'] == "N" && $pay_status != "A" && $row['point_val'] != 1) {
    $query = "update Gn_Item_Pay_Result set item_price='$price', pay_status='Y'  where `no`='$no'";
    mysqli_query($self_con, $query);
} else if($pay_status == "A") {
    $query = "update Gn_Item_Pay_Result set item_price='$price', pay_status='A'  where `no`='$no'";
    mysqli_query($self_con, $query);
} else if($row['point_val'] == 1 && $row['pay_status'] == "N"){
    $sql_val = "select buyer_id, item_price from Gn_Item_Pay_Result where `no`='$no'";
    $res_val = mysqli_query($self_con, $sql_val);
    $row_val = mysqli_fetch_array($res_val);
    $query = "update Gn_Item_Pay_Result set pay_status='Y', current_point=current_point+{$row_val['item_price']}, pay_date=now() where buyer_id='{$row_val['buyer_id']}' and point_val=1 and pay_status='N' order by pay_date desc limit 1";
    mysqli_query($self_con, $query);

    $sql_update = "update Gn_Member set mem_point=mem_point+{$row_val['item_price']} where mem_id='{$row['buyer_id']}'";
    mysqli_query($self_con, $sql_update);
}
echo "<script>alert('저장되었습니다.');location='/admin/payment_item_list.php';</script>";
exit;
?>