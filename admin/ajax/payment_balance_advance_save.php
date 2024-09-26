<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 정산관리
*/
extract($_POST);
$mem_id_array = explode(",",$mem_id);
$status_array = explode(",",$end_status);
for($i = 0;$i <count($mem_id_array);$i++) {
    $mem_id = $mem_id_array[$i];
    $end_status = $status_array[$i];
    if ($end_status != "") {
        if ($end_status == "N") {
            $sql = "update tjd_pay_result_balance set   balance_yn='" . $end_status . "',balance_confirm_date=NULL where balance_date ='$balance_date' and seller_id='$mem_id' ";
            mysqli_query($self_con, $sql);
            $sql = "update tjd_pay_result_balance set   branch_balance_yn='" . $end_status . "',branch_balance_confirm_date=null where balance_date ='$balance_date' and branch_id='$mem_id'  ";
            mysqli_query($self_con, $sql);
        } else {
            $sql = "update tjd_pay_result_balance set   balance_yn='" . $end_status . "',balance_confirm_date=NOW() where balance_date ='$balance_date' and seller_id='$mem_id' ";
            mysqli_query($self_con, $sql);
            $sql = "update tjd_pay_result_balance set   branch_balance_yn='" . $end_status . "',branch_balance_confirm_date=NOW() where balance_date ='$balance_date' and branch_id='$mem_id'  ";
            mysqli_query($self_con, $sql);
        }
    }
}
if(count($mem_id_array) == 1) {
    echo "<script>alert('저장되었습니다.');location.href='/admin/payment_balance_advance_list.php?search_year=".$_POST['search_year']."&search_month=".$_POST['search_month']."';</script>";
}else{
    echo json_encode(array("result"=>"success"));
}
exit;
?>