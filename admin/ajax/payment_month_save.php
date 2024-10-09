<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
$query = "select * from tjd_pay_result where no='$no'";
$res = mysql_query($query);
$row = mysql_fetch_array($res);
if($row[no] == "") exit;
if($type == "main") {
    if ($payment_day == "") {
        echo "<script>alert('결제일이 없습니다.');history.go(-1);</script>";
        exit;
    }
    //결제예정일 이전이면 당월 적용해 주시고, 이후면 당월은 이용하고 익월 해지되게 해 주세요.
    //echo date("Y")."-".date("m", strtotime("+1 months"))."-".date("d", strtotime($row['end_date']));
    if ($payment_day > date("d", strtotime($row['end_date']))) {
        $end_date = date("Y-m", strtotime("+1 months")) . "-" . date("d", strtotime($row['end_date'])) . " " . substr($row['end_date'], 11, 10);
    } else if ($payment_day <= date("d", strtotime($row['end_date']))) {
        $end_date = date("Y") . "-" . date("m") . "-" . date("d", strtotime($row['end_date'])) . " " . substr($row['end_date'], 11, 10);
    }
    if ($month_status == "Y") {
        $sql_m = "update tjd_pay_result set monthly_status='$month_status',payment_day='$payment_day', end_date='$end_date', cancel_completetime =now()  where `no` = '$no' ";
        // if (strstr($row[member_type] ,"professional") || strstr($row[member_type] ,"standard") || strstr($row[member_type] ,"enterprise")) {
            $dber_sql = "update crawler_member_real set term='$end_date',search_email_date='$end_date',shopping_end_date='$end_date', search_email_yn='N', status='N' where user_id='$row[buyer_id]'";
            mysql_query($dber_sql);
        // }
        /*else{
            $sql_member = "update Gn_Member m inner join tjd_pay_result p on p.buyer_id=m.mem_id set m.iam_type=0  where p.no = '$no' ";
            mysql_query($sql_member);
        }*/
    } else {
        $sql_m = "update tjd_pay_result set monthly_status='$month_status',payment_day='$payment_day'  where `no` = '$no' ";
    }
    mysql_query($sql_m) or die(mysql_error());
    echo "<script>alert('저장되었습니다.');location='/admin/payment_month_list.php';</script>";
    exit;
}else if($type == "memo"){
    $sql_m = "update tjd_pay_result set print_msg='$memo' where `no` = '$no' ";
    mysql_query($sql_m) or die(mysql_error());
    echo "<script>alert('저장되었습니다.');location='/admin/payment_month_list.php';</script>";
    exit;
}else if($type == "end_date"){
    $sql_m = "update tjd_pay_result set end_date='$end_date'  where `no` = '$no' ";
    mysql_query($sql_m) or die(mysql_error());
    echo "<script>alert('저장되었습니다.');location='/admin/payment_month_list.php';</script>";
    exit;
}
?>