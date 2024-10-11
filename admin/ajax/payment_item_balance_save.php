<?@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);
echo $_POST['end_status'];
if($_POST['end_status'] != ""  ) {
    if($_POST['end_status'] == "N") {
        $sql="update Gn_Item_Pay_Result_Balance set   balance_yn='".$_POST['end_status']."',balance_confirm_date=NULL where bid='$bid'";
        mysqli_query($self_con,$sql);
    } else {
        $sql="update Gn_Item_Pay_Result_Balance set   balance_yn='".$_POST['end_status']."',balance_confirm_date=NOW() where bid='$bid'";
        mysqli_query($self_con,$sql);
    }
}
echo "<script>alert('저장되었습니다.');location='/admin/payment_item_balance_list.php?search_year=$_POST[search_year]&search_month=$_POST[search_month]';</script>";
exit; 
?>