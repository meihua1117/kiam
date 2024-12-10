<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
extract($_POST);
$query = "select * from tjd_pay_result where no='{$no}'";
$res = mysqli_query($self_con,$query);
$row = mysqli_fetch_array($res);
if($row['no'] == "") exit;
if($type == "main"){
    if($row['end_status'] == "Y" && $end_status != "A") {
        if($row['end_date'] == "1970-01-01 09:00:00") {
            $row['end_date'] = date('Y-m-d H:i:s', time()+(86400*365*3));
        }
        $query = "select * from tjd_pay_result where buyer_id='{$row['buyer_id']}' and end_status='Y' and `no` < '{$no}'";
        $res = mysqli_query($self_con,$query);
        $sdata = mysqli_fetch_array($res);
        if($sdata['no'] != "") {
            $sql_m="update Gn_Member set fujia_date1='{$sdata['date']}' , fujia_date2='{$sdata['end_date']}'  where mem_id='{$row['buyer_id']}' ";
        } else {
            $sql_m="update Gn_Member set fujia_date2=fujia_date1  where mem_id='{$row['buyer_id']}' ";
        }
        mysqli_query($self_con,$sql_m)or die(mysqli_error($self_con));

        $sql_m="update Gn_Member set  phone_cnt=phone_cnt-'{$row['add_phone']}' where mem_id='{$row['buyer_id']}' ";
        mysqli_query($self_con,$sql_m)or die(mysqli_error($self_con));

        $query = "update tjd_pay_result set TotPrice='{$price}', end_status='E',end_date='{$end_date}' where `no`='{$no}'";
        mysqli_query($self_con,$query);

        if($row['payMethod'] == "MONTH"){
            $date = date("Y-m");
            $query = "update tjd_pay_result_month set pay_yn='N' where order_number='{$row['orderNumber']}' and regdate like '$date%'";
            mysqli_query($self_con,$query);
        }

        $last_time = date("Y-m-d H:i:s",strtotime( "+{$row['month_cnt']} month" ));
        $sql="select * from crawler_member_real where user_id='{$row['buyer_id']}' ";
        $sresult=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
        $srow = mysqli_fetch_array($sresult);
        
        $cell=$srow['mem_phone'];
        $email=$srow['mem_email'];
        $address=$srow['mem_add1'];
        $status="Y";
        $use_cnt = $row['db_cnt'];
        $search_email_date = substr($last_time,0,10);
        $search_email_cnt = $row['email_cnt'];
        $term = substr($last_time,0,10);
        if($search_email_cnt > 0) $search_email_yn = "Y";
        else $search_email_yn = "N";
        $query = "update crawler_member_real set
                                            cell='{$cell}',
                                            email='{$email}',
                                            address='{$address}',
                                            term='{$term}',
                                            status='{$status}',
                                            use_cnt='{$use_cnt}',
                                            monthly_cnt = 0,
                                            total_cnt = 0,
                                            regdate=NOW(),
                                            search_email_yn='{$search_email_yn}',
                                            search_email_date='{$search_email_date}',
                                            search_email_cnt='{$search_email_cnt}',
                                            shopping_yn='N',
                                            shopping_end_date='{$search_email_date}',
                                            status='N'
                                            where user_id='{$row['buyer_id']}'
                                            ";
         mysqli_query($self_con,$query);

        if($row['member_type'] == 'year-professional'){
            $sql_seed_point = "update Gn_Member set mem_point=mem_point+1000000 where mem_id='{$row['buyer_id']}'";
            mysqli_query($self_con,$sql_seed_point);
    
            $sql_data = "select mem_phone, mem_point, mem_cash, mem_name from Gn_Member where mem_id='{$row['buyer_id']}'";
            $res_data = mysqli_query($self_con,$sql_data);
            $row_data = mysqli_fetch_array($res_data);
    
            $sql_res = "insert into Gn_Item_Pay_Result set buyer_id='{$row['buyer_id']}', buyer_tel='{$row_data['mem_phone']}', item_name='씨드포인트충전', item_price=1000000, pay_percent=90, current_point={$row_data['mem_point']}, current_cash={$row_data['mem_cash']}, pay_status='Y', VACT_InputName='{$row_data['mem_name']}', type='buy', seller_id='{$row['buyer_id']}', pay_method='결제씨드충전', pay_date=now(), point_val=1,billdate=now()";
    
            mysqli_query($self_con,$sql_res);
        }
    } else if($row['end_status'] == "N" && $end_status != "A") {
        $date = date("Y-m-d H:i:s");
        if($row['end_date'] == "1970-01-01 09:00:00") {
            $row['end_date'] = date('Y-m-d H:i:s', time()+(86400*365*3));
        }
        $query = "update tjd_pay_result set TotPrice = $price, end_status='Y' where `no`='{$no}'";
        mysqli_query($self_con,$query);

        if($row['payMethod'] == "MONTH"){
            $date = date("Y-m");
            $query = "update tjd_pay_result_month set pay_yn='Y' where order_number='{$row['orderNumber']}' and regdate like '$date%'";
            mysqli_query($self_con,$query);
        }
        if($row['member_type'] == "dber"){
            $mem_sql = "select * from Gn_Member where mem_id = '{$row['buyer_id']}'";
            $mem_res = mysqli_query($self_con,$mem_sql);
            $member = mysqli_fetch_array($mem_res);
            $user_id = $member['mem_id'];
            $user_name = $member['mem_name'];
            $password = $member['mem_pass'];
            $cell = $member['mem_phone'];
            $email = $member['mem_email'];
            $address = $member['mem_add1'];
            $use_cnt = $row['db_cnt'];
            $last_time = date("Y-m-d H:i:s", strtotime("+{$row['month_cnt']} month"));
            $search_email_date = substr($last_time, 0, 10);
            $search_email_cnt = $row['email_cnt'];
            $term = substr($last_time, 0, 10);

            $sql = "select count(cmid) from crawler_member_real where user_id='{$member_iam['mem_id']}' ";
            $sresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $crow = mysqli_fetch_array($sresult);
            if ($crow[0] == 0) {
                $query = "insert into crawler_member_real set user_id='{$user_id}',
                                            user_name='{$user_name}',
                                            password='{$password}',
                                            cell='{$cell}',
                                            email='{$email}',
                                            address='{$address}',
                                            term='{$term}',
                                            use_cnt='{$use_cnt}',
                                            regdate=NOW(),
                                            search_email_date='{$search_email_date}',
                                            search_email_cnt='{$search_email_cnt}',
                                            shopping_end_date='{$search_email_date}',
                                            extra_db_cnt = '{$row['db_cnt']}',
                                            extra_email_cnt = '{$row['email_cnt']}',
                                            extra_shopping_cnt = '$row[shop_cnt]'";
                mysqli_query($self_con,$query);
            } else {
                $query = "update crawler_member_real set
                                            extra_db_cnt = extra_db_cnt + '{$row['db_cnt']}',
                                            extra_email_cnt = extra_email_cnt + '{$row['email_cnt']}',
                                            extra_shopping_cnt = extra_shopping_cnt + '$row[shop_cnt]'
                                            where user_id='{$user_id}'";
                mysqli_query($self_con,$query);
            }
        }else if($row['member_type'] == "포인트충전"){
            $sql="select * from Gn_Member where mem_id='{$row['buyer_id']}' and site != ''";
            $resul=mysqli_query($self_con,$sql);
            $data=mysqli_fetch_array($resul);

            $current_point = $data['mem_point'] * 1 + $row['TotPrice'] * 1;
            $sql_update = "update Gn_Member set mem_point={$current_point} where mem_id='{$row['buyer_id']}'";
            mysqli_query($self_con,$sql_update);
            
            $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$row['buyer_id']}',
                                                    buyer_tel='{$data['mem_phone']}',
                                                    pay_method='BANK',
                                                    item_name = '{$row['member_type']}',
                                                    item_price={$row['TotPrice']},
                                                    seller_id='',
                                                    pay_status='Y',
                                                    pay_date=NOW(),
                                                    pay_percent='90',
                                                    order_number = '{$row['orderNumber']}',
                                                    VACT_InputName='{$data['mem_name']}',
                                                    point_val=1,
                                                    type='buy',
                                                    current_cash=$current_point,
                                                    current_point='{$data['mem_point']}',
                                                    billdate=now()";
                            // echo $sql; exit;
            $res_result = mysqli_query($self_con,$sql);
        }else if(strstr($row['member_type'],"professional") || strstr($row['member_type'],"enterprise")){
            $iam_card_cnt = $row['iam_card_cnt'];
            $iam_share_cnt = $row['iam_share_cnt']?$row['iam_share_cnt']:0;
            $add_phone = $row['add_phone'];
            $sql_m="update Gn_Member set fujia_date1=now() ,
                                        fujia_date2=date_add(now(),INTERVAL 120 month),
                                        phone_cnt = phone_cnt + $add_phone,
                                        iam_card_cnt = iam_card_cnt + $iam_card_cnt,
                                        iam_share_cnt = iam_share_cnt + $iam_share_cnt,
                                        exp_start_status = 0,
                                        exp_mid_status = 0,
                                        exp_limit_status = 0,
                                        exp_limit_date = NULL"; 
            if($row['member_type'] == "year-professional")
                $sql_m .=",mem_point = mem_point + 1000000,service_type=2";  
            else if($row['member_type'] == "professional")
                $sql_m .=",service_type=1";
            else if($row['member_type'] == "enterprise")
                $sql_m .=",service_type=1";
            $sql_m .=" where mem_id='{$row['buyer_id']}' ";
            mysqli_query($self_con,$sql_m) or die(mysqli_error($self_con));
            
            $last_time = date("Y-m-d H:i:s",strtotime( "+{$row['month_cnt']} month" ));
            $sql="select * from crawler_member_real where user_id='{$row['buyer_id']}' ";
            $sresult=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
            $srow = mysqli_fetch_array($sresult);
            $cell=$srow['mem_phone'];
            $email=$srow['mem_email'];
            $address=$srow['mem_add1'];
            $status="Y";
            $use_cnt = $row['db_cnt'];
            $search_email_date = substr($last_time,0,10);
            $search_email_cnt = $row['email_cnt'];
            $term = substr($last_time,0,10);
            if($search_email_cnt > 0)
                $search_email_yn = "Y";
            else
                $search_email_yn = "N";
            $query = "update crawler_member_real set
                                            cell='{$cell}',
                                            email='{$email}',
                                            address='{$address}',
                                            term='{$term}',
                                            status='{$status}',
                                            use_cnt='{$use_cnt}',
                                            monthly_cnt = 0,
                                            total_cnt = 0,
                                            regdate=NOW(),
                                            search_email_yn='{$search_email_yn}',
                                            search_email_date='{$search_email_date}',
                                            search_email_cnt='{$search_email_cnt}',
                                            shopping_yn='N',
                                            shopping_end_date='{$search_email_date}',
                                            status='Y'
                                            where user_id='{$row['buyer_id']}'
                                            ";
            mysqli_query($self_con,$query);
            $query = "update Gn_Iam_Service set status='Y' where mem_id='{$row['buyer_id']}'";
            mysqli_query($self_con,$query);
        }
        else if(strstr($row['member_type'],"가맹점")){
            $last_time = date("Y-m-d H:i:s",strtotime( "+{$row['month_cnt']} month" ));
            $sql="select * from crawler_member_real where user_id='{$row['buyer_id']}' ";
            $sresult=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
            $srow = mysqli_fetch_array($sresult);
            $cell=$srow['mem_phone'];
            $email=$srow['mem_email'];
            $address=$srow['mem_add1'];
            $status="Y";
            $use_cnt = $row['db_cnt'];
            $search_email_date = substr($last_time,0,10);
            $search_email_cnt = $row['email_cnt'];
            $term = substr($last_time,0,10);
            if($search_email_cnt > 0)
                $search_email_yn = "Y";
            else
                $search_email_yn = "N";
            $query = "update crawler_member_real set
                                            cell='{$cell}',
                                            email='{$email}',
                                            address='{$address}',
                                            term='{$term}',
                                            status='{$status}',
                                            use_cnt='{$use_cnt}',
                                            monthly_cnt = 0,
                                            total_cnt = 0,
                                            regdate=NOW(),
                                            search_email_yn='{$search_email_yn}',
                                            search_email_date='{$search_email_date}',
                                            search_email_cnt='{$search_email_cnt}',
                                            shopping_yn='N',
                                            shopping_end_date='{$search_email_date}',
                                            status='Y'
                                            where user_id='{$row['buyer_id']}'
                                            ";
            mysqli_query($self_con,$query);
        }
    } else if($end_status == "A") {
        $date = date("Y-m-d H:i:s");
        if($row['end_date'] == "1970-01-01 09:00:00") {
            $row['end_date'] = date('Y-m-d H:i:s', time()+(86400*365*3));
        }
        $sql_m="update Gn_Member set fujia_date1=now() , fujia_date2='{$row['end_date']}' where mem_id='{$row['buyer_id']}' ";
        mysqli_query($self_con,$sql_m) or die(mysqli_error($self_con));
        if($row['member_type'] != "dber") {//셀링결제인 경우
            $sql_m = "update Gn_Member set   phone_cnt=phone_cnt+'{$row['add_phone']}' where mem_id='{$row['buyer_id']}' ";
            //echo $sql_m."<BR>";
            mysqli_query($self_con,$sql_m) or die(mysqli_error($self_con));

            $query = "update tjd_pay_result set TotPrice='{$price}', end_status='A', end_date='{$row['end_date']}' where `no`='{$no}'";
            mysqli_query($self_con,$query);

            $last_time = date("Y-m-d H:i:s", strtotime("+{$row['month_cnt']} month"));
            $sql = "select * from crawler_member_real where user_id='{$row['buyer_id']}' ";
            $sresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $srow = mysqli_fetch_array($sresult);
            $user_id = $srow['mem_id'];
            $user_name = $srow['mem_name'];
            $password = $srow['mem_pass'];
            $cell = $srow['mem_phone'];
            $email = $srow['mem_email'];
            $address = $srow['mem_add1'];
            $status = "Y";
            $use_cnt = $row['db_cnt'];
            $search_email_date = substr($last_time, 0, 10);
            $search_email_cnt = $row['email_cnt'];
            $term = substr($last_time, 0, 10);
            if ($search_email_cnt > 0) $search_email_yn = "Y";
            else $search_email_yn = "N";
            $query = "update crawler_member_real set
                                            cell='{$cell}',
                                            email='{$email}',
                                            address='{$address}',
                                            term='{$term}',
                                            status='{$status}',
                                            use_cnt='{$use_cnt}',
                                            monthly_cnt = 0,
                                            total_cnt = 0,
                                            regdate=NOW(),
                                            search_email_yn='{$search_email_yn}',
                                            search_email_date='{$search_email_date}',
                                            search_email_cnt='{$search_email_cnt}',
                                            shopping_yn='N',
                                            shopping_end_date='{$search_email_date}',
                                            status='Y'
                                            where user_id='{$row['buyer_id']}'";
            mysqli_query($self_con,$query);
        }
    }
    echo "<script>alert('저장되었습니다.');location='/admin/payment_list.php';</script>";
    exit;
}else if($type == "end_date"){
    $old_month = $row['month_cnt'];
    $month_cnt = $month - $old_month;
    $end_date = date("Y-m-d H:i:s",strtotime( "{$row['end_date']} +{$month_cnt} month" ));
    $query = "update tjd_pay_result set end_date='{$end_date}',month_cnt = '{$month}' where `no`='{$no}'";
    mysqli_query($self_con,$query);
    if ($row['member_type'] != "dber") {
        $dber_sql = "update crawler_member_real set term='{$end_date}',search_email_date='{$end_date}',shopping_end_date='{$end_date}' where user_id='{$row['buyer_id']}'";
        mysqli_query($self_con,$dber_sql);
    }
    echo json_encode(array('end_date'=>$end_date));
}else if($type == "onestep2_update"){
    if($yak == "OFF"){
        $sql_update = "update tjd_pay_result set onestep2='{$yak}', monthly_status='R', cancel_Requesttime=NOW() where no='{$no}'";
    }
    else{
        $sql_update = "update tjd_pay_result set onestep2='{$yak}' where no='{$no}'";
    }
    mysqli_query($self_con,$sql_update);
    echo 1;
}
?>