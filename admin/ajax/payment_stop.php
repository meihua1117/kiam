<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
$query = "select * from tjd_pay_result where no='$no'";
$res = mysqli_query($self_con,$query);
$row = mysqli_fetch_array($res);

if($row[no] == "") exit;
if($row[stop_yn] == "Y") {
    $sql="update tjd_pay_result set  stop_yn='N' where no='$no' ";
    mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
    
    $sql="insert into tjd_pay_result_stop_log set   no='$no', user_id='$row[buyer_id]',  content='복구', regdate=NOW() ";
    mysqli_query($self_con,$sql)or die(mysqli_error($self_con));		        
    
    $sql="update crawler_member_real set  status='Y', search_email_yn='Y' where user_id='$row[buyer_id]' ";
    mysqli_query($self_con,$sql)or die(mysqli_error($self_con));

    if($row['payMethod'] == "MONTH"){
        $date = date("Y-m");
        $query = "update tjd_pay_result_month set pay_yn='Y' where order_number='$row[orderNumber]' and regdate like '$date%'";
        mysqli_query($self_con,$query);
    }
} else {
    $sql="update tjd_pay_result set stop_yn='Y' where no='$no' ";
    mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
    
    $sql="insert into tjd_pay_result_stop_log set   no='$no', user_id='$row[buyer_id]',  content='정지', regdate=NOW() ";
    mysqli_query($self_con,$sql)or die(mysqli_error($self_con));		            
    
    $sql="update crawler_member_real set status='N', search_email_yn='N', search_email_date='{$row['end_date']}', term='{$row['end_date']}' where user_id='$row[buyer_id]' ";
    mysqli_query($self_con,$sql)or die(mysqli_error($self_con));

    if($row['payMethod'] == "MONTH"){
        $date = date("Y-m");
        $query = "update tjd_pay_result_month set pay_yn='N' where order_number='$row[orderNumber]' and regdate like '$date%'";
        mysqli_query($self_con,$query);
    }

    $date_today=date("Y-m-d");

    $end_date = $row['end_date'];
    $end_status = $row['end_status'];
    $member_type = $row['member_type'];
    $buyer_id = $row['buyer_id'];
    if($end_date > $date_today && ($end_status =='Y' || $end_status =='A')){
        //$sql = "update Gn_Service set status = 'N' where mem_id = '$buyer_id'";            
        //mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
        //$sql = "update Gn_Iam_Service set status = 'N' where mem_id = '$buyer_id'";            
        //mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
    }
}
?>