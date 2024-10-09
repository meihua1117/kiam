<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);


//			$sql_m="update Gn_Member set fujia_date1=now() , fujia_date2=date_add(now(),INTERVAL {$_POST[month_cnt]} month)  where mem_id='$member_1[mem_id]' ";
//			mysql_query($sql_m)or die(mysql_error());
//			//print_r($_POST);
//			$sql_m="update Gn_Member set   phone_cnt=phone_cnt+'$_POST[add_phone]' where mem_id='$member_1[mem_id]' ";
//			//echo $sql_m;
//			mysql_query($sql_m)or die(mysql_error());		
$payment_day = $payment_day?$payment_day:0;
$query = "select * from tjd_pay_result_db where no='$no'";
$res = mysql_query($query);
$row = mysql_fetch_array($res);
if($row[no] == "") exit;

if($row[end_status] == "Y" && $end_status != "A") {
    
    if($row[end_date] == "1970-01-01 09:00:00") {
        $row[end_date] = date('Y-m-d H:i:s', time()+(86400*365*3));
    }    
    $query = "select *
                from tjd_pay_result_db where buyer_id='$row[buyer_id]' and end_status='Y' and `no` < '$no'";
    $res = mysql_query($query);
    $sdata = mysql_fetch_array($res);
    if($sdata[no] != "") {
        $sql_m="update Gn_Member set fujia_date1='$sdata[date]' , fujia_date2='$sdata[end_date]'  where mem_id='$row[buyer_id]' ";
        mysql_query($sql_m)or die(mysql_error());        
    } else {
        $sql_m="update Gn_Member set fujia_date1='0000-00-00 00:00:00' , fujia_date2='0000-00-00 00:00:00'  where mem_id='$row[buyer_id]' ";
        mysql_query($sql_m)or die(mysql_error());
    }
    //print_r($_POST);
    $sql_m="update Gn_Member set   phone_cnt=phone_cnt-'$row[add_phone]' where mem_id='$row[buyer_id]' ";
    //echo $sql_m."<BR>";
    //echo $sql_m;
    mysql_query($sql_m)or die(mysql_error());		
    
    $query = "update tjd_pay_result_db set TotPrice='$price', end_status='N',payment_day='$payment_day' where `no`='$no'";
    //echo $query."<BR>";
    mysql_query($query);    
} else if($row[end_status] == "N" && $end_status != "A") {
    $date = date("Y-m-d H:i:s");
    // 이미 진행중인 결제가 있는지 확인
    //$query = "select * from tjd_pay_result_db where buyer_id='$row[buyer_id]' and end_status='Y' and `end_date` > '$date' and `no` != '$no'";
    //$res = mysql_query($query);
    //$sdata = mysql_fetch_array($res);
    //if($sdata['no'] != "") {
    //    echo "<Script>alert('이미 진행중인 결제가 있습니다.');history.go(-1);</script>";
    //    exit;
    //}
        
    if($row[end_date] == "1970-01-01 09:00:00") {
        $row[end_date] = date('Y-m-d H:i:s', time()+(86400*365*3));
    }
    $sql_m="update Gn_Member set fujia_date1=now() , fujia_date2='$row[end_date]' where mem_id='$row[buyer_id]' ";
    //echo $sql_m."<BR>";
    mysql_query($sql_m)or die(mysql_error());
    
    $sql_m="update Gn_Member set   phone_cnt=phone_cnt+'$row[add_phone]' where mem_id='$row[buyer_id]' ";
    //echo $sql_m."<BR>";
    mysql_query($sql_m)or die(mysql_error());		
    
    $query = "update tjd_pay_result_db set TotPrice='$price', end_status='Y', end_date='$row[end_date]',payment_day='$payment_day' where `no`='$no'";
    //echo $query."<BR>";
    mysql_query($query);
} else if($end_status == "A") {
    $date = date("Y-m-d H:i:s");
    // 이미 진행중인 결제가 있는지 확인
    //$query = "select * from tjd_pay_result_db where buyer_id='$row[buyer_id]' and end_status='Y' and `end_date` > '$date'";
    //$res = mysql_query($query);
    //$sdata = mysql_fetch_array($res);
    if($row[end_date] == "1970-01-01 09:00:00") {
        $row[end_date] = date('Y-m-d H:i:s', time()+(86400*365*3));
    }
    $sql_m="update Gn_Member set fujia_date1=now() , fujia_date2='$row[end_date]' where mem_id='$row[buyer_id]' ";
    //echo $sql_m."<BR>";
    mysql_query($sql_m)or die(mysql_error());
    
    $sql_m="update Gn_Member set   phone_cnt=phone_cnt+'$row[add_phone]' where mem_id='$row[buyer_id]' ";
    //echo $sql_m."<BR>";
    mysql_query($sql_m)or die(mysql_error());		
    
    $query = "update tjd_pay_result_db set TotPrice='$price', end_status='A', end_date='$row[end_date]',payment_day='$payment_day' where `no`='$no'";
    mysql_query($query);
}
echo "<script>alert('저장되었습니다.');location='/admin/payment_list.php';</script>";
exit;
?>