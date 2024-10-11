<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
extract($_POST);

if($type == "main"){
    $query = "select * from tjd_pay_result where no='$no'";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    if($row[no] == "") exit;
    if($row[end_status] == "Y" && $end_status != "A") {
        if($row[end_date] == "1970-01-01 09:00:00") {
            $row[end_date] = date('Y-m-d H:i:s', time()+(86400*365*3));
        }
        $query = "select * from tjd_pay_result where buyer_id='$row[buyer_id]' and end_status='Y' and `no` < '$no'";
        $res = mysqli_query($self_con,$query);
        $sdata = mysqli_fetch_array($res);
        if($sdata[no] != "") {
            $sql_m="update Gn_Member set fujia_date1='$sdata[date]' , fujia_date2='$sdata[end_date]'  where mem_id='$row[buyer_id]' ";
        } else {
            $sql_m="update Gn_Member set fujia_date1='0000-00-00 00:00:00' , fujia_date2='0000-00-00 00:00:00'  where mem_id='$row[buyer_id]' ";
        }
        mysqli_query($self_con,$sql_m)or die(mysqli_error($self_con));

        $sql_m="update Gn_Member set  phone_cnt=phone_cnt-'$row[add_phone]' where mem_id='$row[buyer_id]' ";
        mysqli_query($self_con,$sql_m)or die(mysqli_error($self_con));

        $query = "update tjd_pay_result set TotPrice='$price', end_status='E',end_date='$end_date' where `no`='$no'";
        mysqli_query($self_con,$query);

        if($row['payMethod'] == "MONTH"){
            $date = date("Y-m");
            $query = "update tjd_pay_result_month set pay_yn='N' where order_number='$row[orderNumber]' and regdate like '$date%'";
            mysqli_query($self_con,$query);
        }

        $last_time = date("Y-m-d H:i:s",strtotime( "+{$row[month_cnt]} month" ));
        $sql="select * from crawler_member_real where user_id='$row[buyer_id]' ";
        $sresult=mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
        $srow = mysqli_fetch_array($sresult);
        
        $cell=$srow['mem_phone'];
        $email=$srow['mem_email'];
        $address=$srow['mem_add1'];
        $status="Y";
        $use_cnt = $row[db_cnt];
        $search_email_date = substr($last_time,0,10);
        $search_email_cnt = $row[email_cnt];
        $term = substr($last_time,0,10);
        if($search_email_cnt > 0) $search_email_yn = "Y";
        else $search_email_yn = "N";
        $query = "update crawler_member_real set
                                            cell='$cell',
                                            email='$email',
                                            address='$address',
                                            term='$term',
                                            status='$status',
                                            use_cnt='$use_cnt',
                                            monthly_cnt = 0,
                                            total_cnt = 0,
                                            regdate=NOW(),
                                            search_email_yn='$search_email_yn',
                                            search_email_date='$search_email_date',
                                            search_email_cnt='$search_email_cnt',
                                            shopping_yn='N',
                                            shopping_end_date='$search_email_date',
                                            status='N'
                                            where user_id='$row[buyer_id]'
                                            ";
         mysqli_query($self_con,$query);

        if($row[member_type] == 'year-professional'){
            $sql_seed_point = "update Gn_Member set mem_point=mem_point+1000000 where mem_id='{$row[buyer_id]}'";
            mysqli_query($self_con,$sql_seed_point);
    
            $sql_data = "select mem_phone, mem_point, mem_cash, mem_name from Gn_Member where mem_id='{$row[buyer_id]}'";
            $res_data = mysqli_query($self_con,$sql_data);
            $row_data = mysqli_fetch_array($res_data);
    
            $sql_res = "insert into Gn_Item_Pay_Result set buyer_id='{$row[buyer_id]}', buyer_tel='{$row_data['mem_phone']}', item_name='씨드포인트충전', item_price=1000000, pay_percent=90, current_point={$row_data['mem_point']}, current_cash={$row_data[mem_cash]}, pay_status='Y', VACT_InputName='{$row_data[mem_name]}', type='buy', seller_id='{$row[buyer_id]}', pay_method='결제씨드충전', pay_date=now(), point_val=1";
    
            mysqli_query($self_con,$sql_res);
        }
    } else if($row[end_status] == "N" && $end_status != "A") {
        $date = date("Y-m-d H:i:s");
        if($row[end_date] == "1970-01-01 09:00:00") {
            $row[end_date] = date('Y-m-d H:i:s', time()+(86400*365*3));
        }
        $query = "update tjd_pay_result set TotPrice = $price, end_status='Y' where `no`='$no'";
        mysqli_query($self_con,$query);

        if($row['payMethod'] == "MONTH"){
            $date = date("Y-m");
            $query = "update tjd_pay_result_month set pay_yn='Y' where order_number='$row[orderNumber]' and regdate like '$date%'";
            mysqli_query($self_con,$query);
        }
        if($row[member_type] == "dber"){
            $mem_sql = "select * from Gn_Member where mem_id = '$row[buyer_id]'";
            $mem_res = mysqli_query($self_con,$mem_sql);
            $member = mysqli_fetch_array($mem_res);
            $user_id = $member['mem_id'];
            $user_name = $member['mem_name'];
            $password = $member['mem_pass'];
            $cell = $member['mem_phone'];
            $email = $member['mem_email'];
            $address = $member['mem_add1'];
            $use_cnt = $row[db_cnt];
            $last_time = date("Y-m-d H:i:s", strtotime("+$row[month_cnt] month"));
            $search_email_date = substr($last_time, 0, 10);
            $search_email_cnt = $row[email_cnt];
            $term = substr($last_time, 0, 10);

            $sql = "select count(cmid) from crawler_member_real where user_id='$member_iam[mem_id]' ";
            $sresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $crow = mysqli_fetch_array($sresult);
            if ($crow[0] == 0) {
                $query = "insert into crawler_member_real set user_id='$user_id',
                                            user_name='$user_name',
                                            password='$password',
                                            cell='$cell',
                                            email='$email',
                                            address='$address',
                                            term='$term',
                                            use_cnt='$use_cnt',
                                            regdate=NOW(),
                                            search_email_date='$search_email_date',
                                            search_email_cnt='$search_email_cnt',
                                            shopping_end_date='$search_email_date',
                                            extra_db_cnt = '$row[db_cnt]',
                                            extra_email_cnt = '$row[email_cnt]',
                                            extra_shopping_cnt = '$row[shop_cnt]'";
                mysqli_query($self_con,$query);
            } else {
                $query = "update crawler_member_real set
                                            extra_db_cnt = extra_db_cnt + '$row[db_cnt]',
                                            extra_email_cnt = extra_email_cnt + '$row[email_cnt]',
                                            extra_shopping_cnt = extra_shopping_cnt + '$row[shop_cnt]'
                                            where user_id='$user_id'";
                mysqli_query($self_con,$query);
            }
        }else if($row['member_type'] == "포인트충전"){
            $sql="select * from Gn_Member where mem_id='$row[buyer_id]' and site != ''";
            $resul=mysqli_query($self_con,$sql);
            $data=mysqli_fetch_array($resul);

            $current_point = $data['mem_point'] * 1 + $row['TotPrice'] * 1;
            $sql_update = "update Gn_Member set mem_point={$current_point} where mem_id='{$row[buyer_id]}'";
            mysqli_query($self_con,$sql_update);
            
            $sql = "insert into Gn_Item_Pay_Result
                        set buyer_id='$row[buyer_id]',
                            buyer_tel='$data[mem_phone]',
                            pay_method='BANK',
                            item_name = '$row[member_type]',
                            item_price=$row[TotPrice],
                            seller_id='',
                            pay_status='Y',
                            pay_date=NOW(),
                            pay_percent='90',
                            order_number = '$row[orderNumber]',
                            VACT_InputName='$data[mem_name]',
                            point_val=1,
                            type='buy',
                            current_cash=$current_point,
                            current_point='$data[mem_point]'";
                            // echo $sql; exit;
            $res_result = mysqli_query($self_con,$sql);
        }else if(strstr($row['member_type'],"professional") || strstr($row['member_type'],"enterprise")){
            $iam_card_cnt = $row[iam_card_cnt];
            $iam_share_cnt = $row[iam_share_cnt]?$row[iam_share_cnt]:0;
            $add_phone = $row[add_phone];
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
                $sql_m .=",service_type=3";
            $sql_m .=" where mem_id='$row[buyer_id]' ";
            mysqli_query($self_con,$sql_m) or die(mysqli_error($self_con));
            
            $last_time = date("Y-m-d H:i:s",strtotime( "+{$row[month_cnt]} month" ));
            $sql="select * from crawler_member_real where user_id='$row[buyer_id]' ";
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
                                            cell='$cell',
                                            email='$email',
                                            address='$address',
                                            term='$term',
                                            status='$status',
                                            use_cnt='$use_cnt',
                                            monthly_cnt = 0,
                                            total_cnt = 0,
                                            regdate=NOW(),
                                            search_email_yn='$search_email_yn',
                                            search_email_date='$search_email_date',
                                            search_email_cnt='$search_email_cnt',
                                            shopping_yn='N',
                                            shopping_end_date='$search_email_date',
                                            status='Y'
                                            where user_id='$row[buyer_id]'
                                            ";
            mysqli_query($self_con,$query);
            $query = "update Gn_Iam_Service set status='Y' where mem_id='$row[buyer_id]'";
            mysqli_query($self_con,$query);
        }
        else if(strstr($row['member_type'],"가맹점")){
            $last_time = date("Y-m-d H:i:s",strtotime( "+{$row[month_cnt]} month" ));
            $sql="select * from crawler_member_real where user_id='$row[buyer_id]' ";
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
                                            cell='$cell',
                                            email='$email',
                                            address='$address',
                                            term='$term',
                                            status='$status',
                                            use_cnt='$use_cnt',
                                            monthly_cnt = 0,
                                            total_cnt = 0,
                                            regdate=NOW(),
                                            search_email_yn='$search_email_yn',
                                            search_email_date='$search_email_date',
                                            search_email_cnt='$search_email_cnt',
                                            shopping_yn='N',
                                            shopping_end_date='$search_email_date',
                                            status='Y'
                                            where user_id='$row[buyer_id]'
                                            ";
            mysqli_query($self_con,$query);
        }
    } else if($end_status == "A") {
        $date = date("Y-m-d H:i:s");
        if($row[end_date] == "1970-01-01 09:00:00") {
            $row[end_date] = date('Y-m-d H:i:s', time()+(86400*365*3));
        }
        $sql_m="update Gn_Member set fujia_date1=now() , fujia_date2='$row[end_date]' where mem_id='$row[buyer_id]' ";
        mysqli_query($self_con,$sql_m) or die(mysqli_error($self_con));
        if($row[member_type] != "dber") {//셀링결제인 경우
            $sql_m = "update Gn_Member set   phone_cnt=phone_cnt+'$row[add_phone]' where mem_id='$row[buyer_id]' ";
            //echo $sql_m."<BR>";
            mysqli_query($self_con,$sql_m) or die(mysqli_error($self_con));

            $query = "update tjd_pay_result set TotPrice='$price', end_status='A', end_date='$row[end_date]' where `no`='$no'";
            mysqli_query($self_con,$query);

            $last_time = date("Y-m-d H:i:s", strtotime("+{$row[month_cnt]} month"));
            $sql = "select * from crawler_member_real where user_id='$row[buyer_id]' ";
            $sresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $srow = mysqli_fetch_array($sresult);
            $user_id = $srow['mem_id'];
            $user_name = $srow['mem_name'];
            $password = $srow['mem_pass'];
            $cell = $srow['mem_phone'];
            $email = $srow['mem_email'];
            $address = $srow['mem_add1'];
            $status = "Y";
            $use_cnt = $row[db_cnt];
            $search_email_date = substr($last_time, 0, 10);
            $search_email_cnt = $row[email_cnt];
            $term = substr($last_time, 0, 10);
            if ($search_email_cnt > 0) $search_email_yn = "Y";
            else $search_email_yn = "N";
            $query = "update crawler_member_real set
                                            cell='$cell',
                                            email='$email',
                                            address='$address',
                                            term='$term',
                                            status='$status',
                                            use_cnt='$use_cnt',
                                            monthly_cnt = 0,
                                            total_cnt = 0,
                                            regdate=NOW(),
                                            search_email_yn='$search_email_yn',
                                            search_email_date='$search_email_date',
                                            search_email_cnt='$search_email_cnt',
                                            shopping_yn='N',
                                            shopping_end_date='$search_email_date',
                                            status='Y'
                                            where user_id='$row[buyer_id]'";
            mysqli_query($self_con,$query);
        }
    }

    $sql_update_item = "update Gn_Item_Pay_Result set pay_status='{$end_status}' where tjd_idx='{$no}'";
    mysqli_query($self_con,$sql_update_item);
    $sql_update_gwc = "update Gn_Gwc_Order set pay_status='{$end_status}' where tjd_idx='{$no}'";
    mysqli_query($self_con,$sql_update_gwc);

    // 결제취소시 가져간 상품 내보내기
    $date_today = date("Y-m-d");
    $prev_month_ts = strtotime($date_today.' -1 month');
    $cur_time = time();
    $date_this_month = date("Y-m", $cur_time)."-01 00:00:00";
    $date_pre_month = date("Y-m", $prev_month_ts)."-01 00:00:00";

    $sql_get_cnt = "select count(*) from Gn_Iam_Contents_Gwc where mem_id='$row[buyer_id]' and ori_store_prod_idx!=0";
    $res_get_cnt = mysqli_query($self_con,$sql_get_cnt);
    $row_get_cnt = mysqli_fetch_array($res_get_cnt);

    $sql_pay = "select sum(TotPrice) as all_money from tjd_pay_result where cash_prod_pay=0 and gwc_cont_pay=1 and buyer_id='$row[buyer_id]' and date>'$date_pre_month' and end_status='Y'";
    $res_pay = mysqli_query($self_con,$sql_pay);
    $row_pay = mysqli_fetch_array($res_pay);

    $possible_cnt = floor($row_pay[0] * 1 / 20000);
    if($possible_cnt < $row_get_cnt[0]){
        $unset_cnt = $row_get_cnt[0] * 1 - $possible_cnt;

        $sql_gwc = "select idx, ori_store_prod_idx from Gn_Iam_Contents_Gwc where mem_id='{$row[buyer_id]}' and ori_store_prod_idx!=0 order by idx asc limit {$unset_cnt}";
        $res_gwc = mysqli_query($self_con,$sql_gwc);
        while($row_gwc = mysqli_fetch_array($res_gwc)){
            $sql_update = "update Gn_Iam_Contents_Gwc set public_display='Y' where idx = '$row_gwc[ori_store_prod_idx]'";
            mysqli_query($self_con,$sql_update) or die(mysqli_error($self_con));

            $sql_del = "delete from Gn_Iam_Contents_Gwc where idx=$row_gwc[idx]";
            mysqli_query($self_con,$sql_del) or die(mysqli_error($self_con));
        }
    }

    if($row[gwc_cont_pay] == 1 && $end_status == "Y"){
        echo "<script>alert('저장되었습니다.');location='/admin/gwc_payment_list.php?send_mms=Y&tjd_no=".$no."';</script>";
    }
    else{
        echo "<script>alert('저장되었습니다.');location='/admin/gwc_payment_list.php';</script>";
    }
    exit;
}
else if($type == "prod_state_change"){
    $sql_update = "update Gn_Gwc_Order set change_prod_req_date=now(), prod_req_state='{$prod_req_state}' where id='{$no}'";
    mysqli_query($self_con,$sql_update);

    echo "<script>alert('저장되었습니다.');location='/admin/gwc_prod_order_change_list.php';</script>";
    exit;
}
else if($type == "reciptimg"){
    $up_dir = make_folder_month(2);
    if($up_dir != ''){
        $uploaddir = '../..'.$up_dir;
    }
    else{
        $uploaddir = '../../upload/';
        $up_dir = "/upload/";
    }
    $tmpFilePath = $_FILES['receipt_file']['tmp_name'];
    if ($tmpFilePath != "") {
        $date_file_name = date('dmYHis') . str_replace(" ", "", basename($_FILES["receipt_file"]["name"]));
        $uploadfile = $uploaddir . basename($date_file_name);
        if (move_uploaded_file($tmpFilePath, $uploadfile)) {
            $receipt_file = $up_dir . basename($date_file_name);
            $handle = new Image($uploadfile, 800);
            $handle->resize();
            uploadFTP($uploadfile);
        }
    }
    if($receipt_file){
        $sql_update = "update Gn_Gwc_Order set prod_req_img='{$receipt_file}' where id='{$no}'";
        mysqli_query($self_con,$sql_update);

        echo "<script>alert('저장되었습니다.');location='/admin/gwc_prod_order_change_list.php';</script>";
        exit;
    }
    else{
        echo "<script>alert('업로드 실패 되었습니다.');location='/admin/gwc_prod_order_change_list.php';</script>";
        exit;
    }
}
else if($type == "prod_storage_change"){
    $sql_update = "update Gn_Gwc_Order set prod_storage='{$storage_state}' where id='{$no}'";
    mysqli_query($self_con,$sql_update);

    echo "<script>alert('저장 되었습니다.');location='/admin/gwc_prod_order_change_list.php';</script>";
    exit;
}
else if($type == "release"){
    $sql_update = "update Gn_Gwc_Order set prod_release_state='{$prod_release_state}' where id='{$no}'";
    mysqli_query($self_con,$sql_update);

    echo "<script>alert('저장 되었습니다.');location='/admin/gwc_payment_list.php';</script>";
    exit;
}
?>