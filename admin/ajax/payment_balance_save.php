<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);
if($_POST['end_status'] != "" && $_POST['no'] != "") {
    if($_POST['end_status'] == "N") {
        $sql="update tjd_pay_result set   balance_yn='".$_POST['end_status']."',balance_date=NULL where date like '$balance_date%' and share_id='$mem_id' ";
        mysqli_query($self_con,$sql);
        $sql="update tjd_pay_result set   branch_balance_yn='".$_POST['end_status']."',branch_balance_date=null where date like '$balance_date%' and branch_share_id='$mem_id'  ";
        mysqli_query($self_con,$sql);        
    } else {
        $sql="update tjd_pay_result set   balance_yn='".$_POST['end_status']."',balance_date=NOW() where date like '$balance_date%' and share_id='$mem_id' ";
        mysqli_query($self_con,$sql);
        $sql="update tjd_pay_result set   branch_balance_yn='".$_POST['end_status']."',branch_balance_date=NOW() where date like '$balance_date%' and branch_share_id='$mem_id'  ";
        mysqli_query($self_con,$sql);                
    }
    $query = "Select * from tjd_pay_result where no='$no'";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    $mem_id= $row['buyer_id'];
    $TotPrice = $row['TotPrice'];
    $query = "Select * from tjd_pay_result_delaer where mem_id='$mem_id' and result_no='$no'"; 
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    if($row[0] != "") {
        $query = "insert into tjd_pay_result_delaer set mem_id='$mem_id', regtime=NOW(), balance_date='$balance_date', result_no='$no'";
        echo $query;
        mysqli_query($self_con,$query);
    }
}
echo "<script>alert('저장되었습니다.');location='/admin/payment_balance_list.php';</script>";
exit; 
?>