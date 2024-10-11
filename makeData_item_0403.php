<?
include_once "lib/rlatjd_fun.php";
$sql="select * from Gn_Member where mem_id='$_SESSION[one_member_id]' and site != '' ";
$resul=mysqli_query($self_con,$sql);
$data=mysqli_fetch_array($resul);
$payMethod = 'CARD';
if($_POST[payMethod] != "") {
    $payMethod = $_POST[payMethod];
}
$_SESSION['allat_amt'] = $_POST['allat_amt'];
$_COOKIE['allat_amt'] = $_POST['allat_amt'];
$_SESSION['allat_amt_'] = $_POST['allat_amt'];
$_COOKIE['allat_amt_'] = $_POST['allat_amt'];

if(isset($_POST['point_val'])){
    $point = 1;
    $get_point = "select mem_cash from Gn_Member where mem_id='{$_SESSION[one_member_id]}'";
    $result_point = mysqli_query($self_con,$get_point);
    $row_point = mysqli_fetch_array($result_point);
    
    $current_point = $row_point['mem_cash'] * 1;

    $_POST[month_cnt] = 90;
    $sql_tjd = "insert into tjd_pay_result set idx='$_POST[allat_order_no]',
                    orderNumber='$_POST[allat_order_no]',
                    VACT_InputName='$data[mem_name]',
                    TotPrice='$_POST[allat_amt]',
                    month_cnt='$_POST[month_cnt]',
                    end_date=date_add(now(),INTERVAL {$_POST[month_cnt]} month),
                    end_status='N',
                    buyertel='$data[mem_phone]',
                    buyeremail='$data[mem_email]',
                    payMethod='BANK',
                    buyer_id='$_SESSION[one_member_id]',
                    date=NOW(),
                    member_type='$_POST[member_type]',
                    max_cnt='0',
                    phone_cnt='0',
                    add_phone='0',
                    db_cnt='9000',
                    email_cnt='0',
                    onestep1='ON',
                    onestep2='ON'";
    $res_result = mysqli_query($self_con,$sql_tjd);
}
else{
    $point = 0;
    $use_point = 0;
    $gwc = 0;
    $item_price = $_POST[allat_amt] * 1;
    if(isset($_POST[allat_amt_point])){
        $use_point = $_POST[allat_amt_point];
    }
    if(isset($_POST[gwc_order])){
        $gwc = 1;
    }
    $pay_idx = 0;

    if(strpos($_POST[cont_idx], ',') === false){
        $sql_cont_info = "select * from Gn_Iam_Contents_Gwc where idx='{$_POST[cont_idx]}'";
        $res_cont_info = mysqli_query($self_con,$sql_cont_info);
        $row_cont_info = mysqli_fetch_array($res_cont_info);
        if($row_cont_info[mem_id] == 'iamstore'){
            $yutong = 1;
            $provider_name = '';
        }
        else{
            $yutong = 2;
            $sql_mem = "select gwc_provider_name from Gn_Member where mem_id='{$row_cont_info[mem_id]}' and (gwc_leb=2 or gwc_leb=3)";
            $res_mem = mysqli_query($self_con,$sql_mem);
            $row_mem = mysqli_fetch_array($res_mem);
            $provider_name = $row_mem[gwc_provider_name];
            if(!$provider_name){
        	$yutong = 1;
	    }
        }
        $_POST[month_cnt] = 120;
        if(!$gwc || ($gwc && $item_price)){
            if($use_point){
                $tot_price = $item_price * 1 + $use_point * 1;
                $payMethod1 = "CARDPOINT";
            }
            else{
                $tot_price = $item_price;
                $payMethod1 = "CARD";
            }
            if(!isset($_POST['spgd'])){
                $sql = "insert into tjd_pay_result set 
                        idx='$_POST[allat_order_no]',
                        orderNumber='$_POST[allat_order_no]',
                        VACT_InputName='$member_iam[mem_name]',
                        TotPrice='$tot_price',
                        month_cnt='$_POST[month_cnt]',
                        end_date=date_add(now(),INTERVAL {$_POST[month_cnt]} month),
                        end_status='N',
                        buyertel='$member_iam[mem_phone]',
                        buyeremail='$member_iam[mem_email]',
                        payMethod='$payMethod1',
                        buyer_id='$member_iam[mem_id]',
                        date=NOW(),
                        member_type='$_POST[member_type]',
                        max_cnt='0',
                        phone_cnt='0',
                        add_phone='0',
                        db_cnt='0',
                        email_cnt='0',
                        shop_cnt='0',
                        onestep1='OFF',
                        onestep2='OFF',
                        member_cnt='0',
                        gwc_cont_pay=$gwc,
                        yutong_name=$yutong,
                        provider_name='$provider_name',
                        seller_id='$row_cont_info[mem_id]'";
            $res_result = mysqli_query($self_con,$sql);
            $pay_idx_tjd = mysqli_insert_id($self_con);
            }
            else{
                $pay_idx_tjd = 0;
                $_POST[contents_cnt] = 1;
            }

            $sql = "insert into Gn_Item_Pay_Result
                        set buyer_id='$_SESSION[one_member_id]',
                            buyer_tel='$data[mem_phone]',
                            pay_method='$payMethod',
                            item_name = '$_POST[member_type]',
                            item_price=$item_price,
                            seller_id='$row_cont_info[mem_id]',
                            pay_date=NOW(),
                            pay_percent='$_POST[pay_percent]',
                            order_number = '$_POST[allat_order_no]',
                            VACT_InputName='$data[mem_name]',
                            contents_cnt='$_POST[contents_cnt]',
                            point_val=$point,
                            gwc_cont_pay=$gwc,
                            tjd_idx=$pay_idx_tjd";
            $res_result = mysqli_query($self_con,$sql);
            $pay_idx = mysqli_insert_id($self_con);
        }
        
        if($gwc){ 
            $gwc_pay_status = "N";   
            if($use_point){
                // $pay_idx_tjd = 0;
                if(!$item_price){
                    $sql = "insert into tjd_pay_result set 
                                idx='$_POST[allat_order_no]',
                                orderNumber='$_POST[allat_order_no]',
                                VACT_InputName='$member_iam[mem_name]',
                                TotPrice='$use_point',
                                month_cnt='$_POST[month_cnt]',
                                end_date=date_add(now(),INTERVAL {$_POST[month_cnt]} month),
                                end_status='Y',
                                buyertel='$member_iam[mem_phone]',
                                buyeremail='$member_iam[mem_email]',
                                payMethod='POINT',
                                buyer_id='$member_iam[mem_id]',
                                date=NOW(),
                                member_type='$_POST[member_type]',
                                max_cnt='0',
                                phone_cnt='0',
                                add_phone='0',
                                db_cnt='0',
                                email_cnt='0',
                                shop_cnt='0',
                                onestep1='OFF',
                                onestep2='OFF',
                                member_cnt='0',
                                gwc_cont_pay=$gwc,
                                yutong_name=$yutong,
                                provider_name='$provider_name',
                                seller_id='$row_cont_info[mem_id]'";
                    $res_result = mysqli_query($self_con,$sql);
                    $pay_idx_tjd = mysqli_insert_id($self_con);
                    $gwc_pay_status = "Y";
                }
                $sql_mem = "update Gn_Member set mem_cash=mem_cash-{$use_point} where mem_id='{$_SESSION[one_member_id]}'";
                mysqli_query($self_con,$sql_mem);
                $data[mem_cash] = $data[mem_cash] * 1 - $use_point * 1;
        
                $sql_buyer = "insert into Gn_Item_Pay_Result
                        set buyer_id='$_SESSION[one_member_id]',
                            buyer_tel='$data[mem_phone]',
                            site='$_POST[contents_url]',
                            pay_method='POINT',
                            item_name = '$_POST[member_type]',
                            item_price=$use_point,
                            seller_id='$row_cont_info[mem_id]',
                            pay_date=NOW(),
                            pay_status='Y',
                            pay_percent='',
                            order_number = '$_POST[allat_order_no]',
                            VACT_InputName='$data[mem_name]',
                            point_val=1,
                            type='use',
                            current_point='$data[mem_point]',
                            current_cash='$data[mem_cash]',
                            contents_cnt='$_POST[contents_cnt]',
                            gwc_cont_pay=$gwc,
                            tjd_idx=$pay_idx_tjd";
                $res_result = mysqli_query($self_con,$sql_buyer);
                if(!$pay_idx){
                    $pay_idx = mysqli_insert_id($self_con);
                }

                $sql_del = "delete from Gn_Gwc_Order where pay_order_no='$_POST[allat_order_no]' and mem_id='$member_iam[mem_id]' and page_type=1";
                mysqli_query($self_con,$sql_del) or die(mysqli_error($self_con));
            }
            $sql_order = "insert into Gn_Gwc_Order
                            set mem_id='$_SESSION[one_member_id]',
                                contents_idx='$_POST[cont_idx]',
                                tjd_idx=$pay_idx_tjd,
                                pay_idx='$pay_idx',
                                pay_order_no='$_POST[allat_order_no]',
                                contents_price = '$_POST[org_price]',
                                salary_price=$_POST[baesong_price2],
                                order_option='$_POST[order_option]',
                                reg_date=NOW(),
                                pay_status='$gwc_pay_status',
                                order_mem_name='$_POST[name]',
                                order_mem_phone = '$_POST[cellphone]',
                                order_mem_phone1='$_POST[telephone]',
                                order_mem_zip='$_POST[zip]',
                                order_mem_add='$_POST[allat_recp_addr]',
                                order_mem_email='$_POST[email]',
                                receive_mem_name = '$_POST[b_name]',
                                receive_mem_phone='$_POST[b_cellphone]',
                                receive_mem_phone1='$_POST[b_telephone]',
                                receive_mem_zip='$_POST[b_zip]',
                                receive_mem_add='$_POST[b_addr1]',
                                contents_cnt = '$_POST[contents_cnt]',
                                use_point=$use_point,
                                seller_id='$row_cont_info[mem_id]'";
            $res_result = mysqli_query($self_con,$sql_order);
        }
    }
    else{
        $idx_arr = explode(',', $_POST[cont_idx]);
        $org_price_arr = explode(',', $_POST[org_price1]);
        $baesong_price_arr = explode(',', $_POST[baesong_price]);
        $con_title_arr = explode('||', $_POST[item_name]);
        $order_opt_arr = explode(',', $_POST[order_option]);
        $con_cnt_arr = explode(',', $_POST[contents_cnt]);
        $cont_url_arr = explode(',', $_POST[contents_url]);

        for($i = 0; $i < count($idx_arr); $i++){
            $sql_cont_info = "select * from Gn_Iam_Contents_Gwc where idx='{$idx_arr[$i]}'";
            $res_cont_info = mysqli_query($self_con,$sql_cont_info);
            $row_cont_info = mysqli_fetch_array($res_cont_info);
            if($row_cont_info[mem_id] == 'iamstore'){
                $yutong = 1;
                $provider_name = '';
            }
            else{
                $yutong = 2;
                $sql_mem = "select gwc_provider_name from Gn_Member where mem_id='{$row_cont_info[mem_id]}'";
                $res_mem = mysqli_query($self_con,$sql_mem);
                $row_mem = mysqli_fetch_array($res_mem);
                $provider_name = $row_mem[gwc_provider_name];
            }

            $comp_price = $org_price_arr[$i] * 1 + $baesong_price_arr[$i] * 1;
            if($use_point){
                if($use_point > $comp_price){
                    $mi_price = $comp_price * 1;

                    $sql = "insert into tjd_pay_result set 
                                idx='$_POST[allat_order_no]',
                                orderNumber='$_POST[allat_order_no]',
                                VACT_InputName='$member_iam[mem_name]',
                                TotPrice='$mi_price',
                                month_cnt='$_POST[month_cnt]',
                                end_date=date_add(now(),INTERVAL {$_POST[month_cnt]} month),
                                end_status='Y',
                                buyertel='$member_iam[mem_phone]',
                                buyeremail='$member_iam[mem_email]',
                                payMethod='POINT',
                                buyer_id='$member_iam[mem_id]',
                                date=NOW(),
                                member_type='$con_title_arr[$i]',
                                max_cnt='$_POST[max_cnt]',
                                phone_cnt='$_POST[phone_cnt]',
                                add_phone='$_POST[add_phone]',
                                db_cnt='$_POST[db_cnt]',
                                email_cnt='$_POST[email_cnt]',
                                shop_cnt='$_POST[shop_cnt]',
                                onestep1='$_POST[onestep1]',
                                onestep2='$_POST[onestep2]',
                                member_cnt='$_POST[member_cnt]',
                                gwc_cont_pay=$gwc,
                                yutong_name=$yutong,
                                provider_name='$provider_name',
                                seller_id='$row_cont_info[mem_id]'";
                    $res_result = mysqli_query($self_con,$sql);
                    $pay_idx_tjd = mysqli_insert_id($self_con);

                    $sql_mem = "update Gn_Member set mem_cash=mem_cash-{$mi_price} where mem_id='{$_SESSION[one_member_id]}'";
                    mysqli_query($self_con,$sql_mem);
                    $data[mem_cash] = $data[mem_cash] * 1 - $mi_price * 1;
                    //포인트결제내역
                    $sql_buyer = "insert into Gn_Item_Pay_Result
                                set buyer_id='$_SESSION[one_member_id]',
                                    buyer_tel='$data[mem_phone]',
                                    site='$cont_url_arr[$i]',
                                    pay_method='POINT',
                                    item_name = '$con_title_arr[$i]',
                                    item_price=$mi_price,
                                    seller_id='$row_cont_info[mem_id]',
                                    pay_date=NOW(),
                                    pay_status='Y',
                                    pay_percent='',
                                    order_number = '$_POST[allat_order_no]',
                                    VACT_InputName='$data[mem_name]',
                                    point_val=1,
                                    type='use',
                                    current_point='$data[mem_point]',
                                    current_cash='$data[mem_cash]',
                                    contents_cnt='$con_cnt_arr[$i]',
                                    gwc_cont_pay=$gwc,
                                    tjd_idx=$pay_idx_tjd";
                    $res_result = mysqli_query($self_con,$sql_buyer);
                    $pay_idx = mysqli_insert_id($self_con);
                    //주문결과내역
                    $sql_order = "insert into Gn_Gwc_Order
                                    set mem_id='$_SESSION[one_member_id]',
                                        contents_idx='$idx_arr[$i]',
                                        tjd_idx='$pay_idx_tjd',
                                        pay_idx='$pay_idx',
                                        pay_order_no='$_POST[allat_order_no]',
                                        contents_price = '$org_price_arr[$i]',
                                        salary_price=$baesong_price_arr[$i],
                                        order_option='$order_opt_arr[$i]',
                                        reg_date=NOW(),
                                        pay_status='Y',
                                        order_mem_name='$_POST[name]',
                                        order_mem_phone = '$_POST[cellphone]',
                                        order_mem_phone1='$_POST[telephone]',
                                        order_mem_zip='$_POST[zip]',
                                        order_mem_add='$_POST[allat_recp_addr]',
                                        order_mem_email='$_POST[email]',
                                        receive_mem_name = '$_POST[b_name]',
                                        receive_mem_phone='$_POST[b_cellphone]',
                                        receive_mem_phone1='$_POST[b_telephone]',
                                        receive_mem_zip='$_POST[b_zip]',
                                        receive_mem_add='$_POST[b_addr1]',
                                        contents_cnt = '$con_cnt_arr[$i]',
                                        use_point=$mi_price,
                                        seller_id='$row_cont_info[mem_id]'";
                    $res_result = mysqli_query($self_con,$sql_order);
                    $use_point = $use_point * 1 - $mi_price * 1;
                }
                else{
                    $real_price = $comp_price * 1 - $use_point * 1;
                    $sql = "insert into tjd_pay_result set 
                                idx='$_POST[allat_order_no]',
                                orderNumber='$_POST[allat_order_no]',
                                VACT_InputName='$member_iam[mem_name]',
                                TotPrice='$comp_price',
                                month_cnt='$_POST[month_cnt]',
                                end_date=date_add(now(),INTERVAL {$_POST[month_cnt]} month),
                                end_status='Y',
                                buyertel='$member_iam[mem_phone]',
                                buyeremail='$member_iam[mem_email]',
                                payMethod='CARDPOINT',
                                buyer_id='$member_iam[mem_id]',
                                date=NOW(),
                                member_type='$con_title_arr[$i]',
                                max_cnt='$_POST[max_cnt]',
                                phone_cnt='$_POST[phone_cnt]',
                                add_phone='$_POST[add_phone]',
                                db_cnt='$_POST[db_cnt]',
                                email_cnt='$_POST[email_cnt]',
                                shop_cnt='$_POST[shop_cnt]',
                                onestep1='$_POST[onestep1]',
                                onestep2='$_POST[onestep2]',
                                member_cnt='$_POST[member_cnt]',
                                gwc_cont_pay=$gwc,
                                yutong_name=$yutong,
                                provider_name='$provider_name',
                                seller_id='$row_cont_info[mem_id]'";
                    $res_result = mysqli_query($self_con,$sql);
                    $pay_idx_tjd = mysqli_insert_id($self_con);
                    
                    $sql = "insert into Gn_Item_Pay_Result
                                set buyer_id='$_SESSION[one_member_id]',
                                    buyer_tel='$data[mem_phone]',
                                    pay_method='$payMethod',
                                    item_name = '$con_title_arr[$i]',
                                    item_price=$real_price,
                                    seller_id='$row_cont_info[mem_id]',
                                    pay_date=NOW(),
                                    pay_status='Y',
                                    pay_percent='$_POST[pay_percent]',
                                    order_number = '$_POST[allat_order_no]',
                                    VACT_InputName='$data[mem_name]',
                                    contents_cnt='$con_cnt_arr[$i]',
                                    point_val=$point,
                                    gwc_cont_pay=$gwc,
                                    tjd_idx=$pay_idx_tjd";
                    $res_result = mysqli_query($self_con,$sql);
                    $pay_idx = mysqli_insert_id($self_con);

                    $sql_mem = "update Gn_Member set mem_cash=mem_cash-{$use_point} where mem_id='{$_SESSION[one_member_id]}'";
                    mysqli_query($self_con,$sql_mem);
                    $data[mem_cash] = $data[mem_cash] * 1 - $use_point * 1;
                    //포인트결제내역
                    $sql_buyer = "insert into Gn_Item_Pay_Result
                                set buyer_id='$_SESSION[one_member_id]',
                                    buyer_tel='$data[mem_phone]',
                                    site='$cont_url_arr[$i]',
                                    pay_method='POINT',
                                    item_name = '$con_title_arr[$i]',
                                    item_price=$use_point,
                                    seller_id='$row_cont_info[mem_id]',
                                    pay_date=NOW(),
                                    pay_status='Y',
                                    pay_percent='',
                                    order_number = '$_POST[allat_order_no]',
                                    VACT_InputName='$data[mem_name]',
                                    point_val=1,
                                    type='use',
                                    current_point='$data[mem_point]',
                                    current_cash='$data[mem_cash]',
                                    contents_cnt='$con_cnt_arr[$i]',
                                    gwc_cont_pay=$gwc,
                                    tjd_idx=$pay_idx_tjd";
                    $res_result = mysqli_query($self_con,$sql_buyer);
                    //주문결과내역
                    $sql_order = "insert into Gn_Gwc_Order
                                    set mem_id='$_SESSION[one_member_id]',
                                        contents_idx='$idx_arr[$i]',
                                        tjd_idx='$pay_idx_tjd',
                                        pay_idx='$pay_idx',
                                        pay_order_no='$_POST[allat_order_no]',
                                        contents_price = '$org_price_arr[$i]',
                                        salary_price=$baesong_price_arr[$i],
                                        order_option='$order_opt_arr[$i]',
                                        reg_date=NOW(),
                                        pay_status='Y',
                                        order_mem_name='$_POST[name]',
                                        order_mem_phone = '$_POST[cellphone]',
                                        order_mem_phone1='$_POST[telephone]',
                                        order_mem_zip='$_POST[zip]',
                                        order_mem_add='$_POST[allat_recp_addr]',
                                        order_mem_email='$_POST[email]',
                                        receive_mem_name = '$_POST[b_name]',
                                        receive_mem_phone='$_POST[b_cellphone]',
                                        receive_mem_phone1='$_POST[b_telephone]',
                                        receive_mem_zip='$_POST[b_zip]',
                                        receive_mem_add='$_POST[b_addr1]',
                                        contents_cnt = '$con_cnt_arr[$i]',
                                        use_point=$use_point,
                                        seller_id='$row_cont_info[mem_id]'";
                    $res_result = mysqli_query($self_con,$sql_order);
                    $use_point = 0;
                }
            }
            else{
                $sql = "insert into tjd_pay_result set 
                            idx='$_POST[allat_order_no]',
                            orderNumber='$_POST[allat_order_no]',
                            VACT_InputName='$member_iam[mem_name]',
                            TotPrice='$comp_price',
                            month_cnt='$_POST[month_cnt]',
                            end_date=date_add(now(),INTERVAL {$_POST[month_cnt]} month),
                            end_status='N',
                            buyertel='$member_iam[mem_phone]',
                            buyeremail='$member_iam[mem_email]',
                            payMethod='$payMethod',
                            buyer_id='$member_iam[mem_id]',
                            date=NOW(),
                            member_type='$con_title_arr[$i]',
                            max_cnt='$_POST[max_cnt]',
                            phone_cnt='$_POST[phone_cnt]',
                            add_phone='$_POST[add_phone]',
                            db_cnt='$_POST[db_cnt]',
                            email_cnt='$_POST[email_cnt]',
                            shop_cnt='$_POST[shop_cnt]',
                            onestep1='$_POST[onestep1]',
                            onestep2='$_POST[onestep2]',
                            member_cnt='$_POST[member_cnt]',
                            gwc_cont_pay=$gwc,
                            yutong_name=$yutong,
                            provider_name='$provider_name',
                            seller_id='$row_cont_info[mem_id]'";
                $res_result = mysqli_query($self_con,$sql);
                $pay_idx_tjd = mysqli_insert_id($self_con);
                    
                $sql = "insert into Gn_Item_Pay_Result
                            set buyer_id='$_SESSION[one_member_id]',
                                buyer_tel='$data[mem_phone]',
                                pay_method='$payMethod',
                                item_name = '$con_title_arr[$i]',
                                item_price=$comp_price,
                                seller_id='$row_cont_info[mem_id]',
                                pay_date=NOW(),
                                pay_percent='$_POST[pay_percent]',
                                order_number = '$_POST[allat_order_no]',
                                VACT_InputName='$data[mem_name]',
                                contents_cnt='$con_cnt_arr[$i]',
                                point_val=$point,
                                gwc_cont_pay=$gwc,
                                tjd_idx=$pay_idx_tjd";
                $res_result = mysqli_query($self_con,$sql);
                $pay_idx = mysqli_insert_id($self_con);

                //주문결과내역
                $sql_order = "insert into Gn_Gwc_Order
                                set mem_id='$_SESSION[one_member_id]',
                                    contents_idx='$idx_arr[$i]',
                                    tjd_idx=$pay_idx_tjd,
                                    pay_idx='$pay_idx',
                                    pay_order_no='$_POST[allat_order_no]',
                                    contents_price = '$org_price_arr[$i]',
                                    salary_price=$baesong_price_arr[$i],
                                    order_option='$order_opt_arr[$i]',
                                    reg_date=NOW(),
                                    order_mem_name='$_POST[name]',
                                    order_mem_phone = '$_POST[cellphone]',
                                    order_mem_phone1='$_POST[telephone]',
                                    order_mem_zip='$_POST[zip]',
                                    order_mem_add='$_POST[allat_recp_addr]',
                                    order_mem_email='$_POST[email]',
                                    receive_mem_name = '$_POST[b_name]',
                                    receive_mem_phone='$_POST[b_cellphone]',
                                    receive_mem_phone1='$_POST[b_telephone]',
                                    receive_mem_zip='$_POST[b_zip]',
                                    receive_mem_add='$_POST[b_addr1]',
                                    contents_cnt = '$con_cnt_arr[$i]',
                                    use_point=$use_point,
                                    seller_id='$row_cont_info[mem_id]'";
                $res_result = mysqli_query($self_con,$sql_order);
            }
        }
        if($use_point){
            $sql_del = "delete from Gn_Gwc_Order where pay_order_no='$_POST[allat_order_no]' and mem_id='$member_iam[mem_id]' and page_type=1";
            mysqli_query($self_con,$sql_del) or die(mysqli_error($self_con));
        }
    }
    

    if($cont_idx){
        $sql_card_idx = "select card_idx from Gn_Iam_Contents where idx='{$cont_idx}'";
        $res_card_idx = mysqli_query($self_con,$sql_card_idx);
        $row_card_idx = mysqli_fetch_array($res_card_idx);
    
        $sql_card_data = "select sale_cnt, add_fixed_val from Gn_Iam_Name_Card where idx='{$row_card_idx[card_idx]}'";
        $res_card_data = mysqli_query($self_con,$sql_card_data);
        $row_card_data = mysqli_fetch_array($res_card_data);

        if($row_card_data['sale_cnt']){
            $sale_after_cnt = $row_card_data['sale_cnt'] * 1 - 1;
            if($sale_after_cnt){
                $sql_reduce_update = "update Gn_Iam_Name_Card set sale_cnt='{$sale_after_cnt}' where idx='{$row_card_idx[card_idx]}'";
                mysqli_query($self_con,$sql_reduce_update);
            }
            else{
                $sql_reduce_update = "update Gn_Iam_Name_Card set sale_cnt='{$sale_after_cnt}' where idx='{$row_card_idx[card_idx]}'";
                mysqli_query($self_con,$sql_reduce_update);
            }
        }
    }
}
?>