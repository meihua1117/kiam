<?
include_once "../lib/rlatjd_fun.php";
if($_POST['pay_type'] == "message_set"){
    if($_POST['buyer'] != $_SESSION['one_member_id'] && $_POST['buyer'] != $_SESSION['iam_member_id']){
        echo json_encode(array("result"=>0)); 
        return;
    }
    $allat_order_no = date("YmdHis").rand(10,99);

    $item_idx = $_POST['item_idx'];
    $sql = "select mem_name,mem_phone,mem_email,mem_cash,mem_point from Gn_Member where mem_id='$_POST[buyer]'";
    $res = mysqli_query($self_con,$sql);
    $mem_data = mysqli_fetch_array($res);

    $sql = "select * from Gn_Iam_Mall where idx=$item_idx";
    $res = mysqli_query($self_con,$sql);
    $mall_data = mysqli_fetch_array($res);
    $sql = "insert into tjd_pay_result set 
                idx='$allat_order_no',
                orderNumber='$allat_order_no',
                VACT_InputName='{$mem_data['mem_name']}',
                TotPrice='$_POST[allat_amt]',
                month_cnt='$_POST[month_cnt]',
                end_date=date_add(now(),INTERVAL {$_POST[month_cnt]} month),
                end_status='Y',
                buyertel='$mem_data[mem_phone]',
                buyeremail='$mem_data[mem_email]',
                payMethod='POINT',
                buyer_id='$_POST[buyer]',
                date=NOW(),
                member_type='$_POST[pay_item]',
                max_cnt='0',
                phone_cnt='0',
                add_phone='0',
                db_cnt='0',
                email_cnt='0',
                shop_cnt='0',
                onestep1='OFF',
                onestep2='OFF',
                member_cnt='0',
                gwc_cont_pay=0,
                yutong_name=0,
                seller_id='$mall_data[mem_id]',
                cash_prod_pay=0";
    mysqli_query($self_con,$sql);
    $pay_idx_tjd = mysqli_insert_id($self_con);
    
    $sql = "update Gn_Member set mem_cash=mem_cash-{$_POST[allat_amt]} where mem_id='{$_POST[buyer]}'";
    mysqli_query($self_con,$sql);
    $current_cash = $mem_data['mem_cash'] * 1 - $_POST['allat_amt'] * 1;

    $sql = "insert into Gn_Item_Pay_Result
            set buyer_id='$_POST[buyer]',
                buyer_tel='$mem_data[mem_phone]',
                site='$item_idx',
                pay_method='POINT',
                item_name = '$_POST[pay_item]',
                item_price=$use_point,
                seller_id='$row_cont_info[mem_id]',
                pay_date=NOW(),
                pay_status='Y',
                pay_percent='',
                order_number = '$allat_order_no',
                VACT_InputName='{$mem_data['mem_name']}',
                point_val=1,
                type='use',
                current_point='$mem_data[mem_point]',
                current_cash=$current_cash,
                contents_cnt=1,
                gwc_cont_pay=0,
                tjd_idx=$pay_idx_tjd";
    mysqli_query($self_con,$sql);
    $pay_idx = mysqli_insert_id($self_con);

    $sql="select * from Gn_event_sms_info where sms_idx='$mall_data[card_idx]'";
    $res = mysqli_query($self_con,$sql);
    $sms_data = mysqli_fetch_array($res);

    $sql = "insert into Gn_event_sms_info set event_idx = '$sms_data[event_idx]',
                                            event_name_eng = '$sms_data[event_name_eng]',
                                            mobile = '$sms_data[mobile]',
                                            reservation_title = '$sms_data[reservation_title]',
                                            reservation_desc= '$sms_data[reservation_desc]',
                                            m_id = '$_POST[buyer]',
                                            sendable = 0,
                                            regdate=NOW()";
    mysqli_query($self_con,$sql);
    $sms_idx = mysqli_insert_id($self_con);
    
    $sql="select * from Gn_event_sms_step_info where sms_idx='$mall_data[card_idx]'";
    $res = mysqli_query($self_con,$sql);
    while($step_row = mysqli_fetch_array($res)){
        $sql = "insert into Gn_event_sms_step_info set sms_idx = $sms_idx,
                                                    step = '$step_row[step]',
                                                    send_day = '$step_row[send_day]',
                                                    send_time = '$step_row[send_time]',
                                                    title= '$step_row[title]',
                                                    content = '{$step_row['content']}',
                                                    image = '$step_row[image]',
                                                    image1 = '$step_row[image1]',
                                                    image2 = '$step_row[image2]',
                                                    send_deny = '$step_row[send_deny]',
                                                    regdate=NOW()";
        mysqli_query($self_con,$sql);
    }
    echo json_encode(array("result"=>1));
}else if($_POST['pay_type'] == "modify_sendable"){
    $sms_idx = $_POST['idx'];
    $sendable = $_POST['sendable'];
    $sql = "update Gn_event_sms_info set sendable=$sendable where sms_idx=$sms_idx";
    mysqli_query($self_con,$sql);
    echo json_encode(array("result"=>1));
}
?>