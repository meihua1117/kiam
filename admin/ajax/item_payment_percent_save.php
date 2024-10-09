<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
extract($_POST);
$query = "Update Gn_Item_Pay_Result set pay_percent='$pay_percent' where no='$no'";
mysql_query($query);
if($point_percent != ""){
    $query = "Update Gn_Item_Pay_Result set point_percent='$point_percent' where `no`='$no'";
    mysql_query($query);
}
echo "<script>alert('저장되었습니다.');location='/admin/payment_item_list.php';</script>";
exit;
?>