
<?
include_once "../../lib/rlatjd_fun.php";
include_once "../../lib/class.image.php";
extract($_POST);

$up_dir = make_folder_month(2);
if($up_dir != ''){
    $uploaddir = '../..'.$up_dir;
}
else{
    $uploaddir = '../../upload/';
    $up_dir = "/upload/";
}

$mem_id = $_SESSION[iam_member_id];

if($mode == "check_model_name"){
    $sql_chk = "select idx from Gn_Iam_Contents_Gwc where product_model_name='{$model_name}'";
    // echo $sql_chk;
    $res_chk = mysqli_query($self_con,$sql_chk);
    $row_chk = mysqli_num_rows($res_chk);
    
    echo json_encode(array('result'=>$row_chk));
}
else if($mode == "make_prod_code"){
    $code = generateRandomCode();
    echo json_encode(array('result'=>'1', 'code'=>$code));
}
else if($mode == "make_prod_model"){
    $date = date('Ymd-Hi')."s";
    $model = $prod_name."+".substr($date, 2, -1);
    echo json_encode(array('result'=>'1', 'model'=>$model));
}
else if($mode == "get_pay_res"){
    // $date_this_month = date("Y-m")."-01 00:00:00";
    // $cur_time_com_stamp = time() - (86400 * 30);
    // $date_pre_month = date("Y-m", $cur_time_com_stamp)."-01 00:00:00";

    $date_today = date("Y-m-d");
    $prev_month_ts = strtotime($date_today.' -1 month');
    $cur_time = time();
    $date_this_month = date("Y-m", $cur_time)."-01 00:00:00";
    $date_pre_month = date("Y-m", $prev_month_ts)."-01 00:00:00";

    $sql_this_month_pay = "select SUM(TotPrice) as money from tjd_pay_result where cash_prod_pay=0 and gwc_cont_pay=1 and buyer_id='{$_SESSION[iam_member_id]}' and end_status='Y' and date>'{$date_this_month}'";//당월 구매금액
    $res_this_month_pay = mysqli_query($self_con,$sql_this_month_pay);
    $row_this_month_pay = mysqli_fetch_array($res_this_month_pay);
    
    $this_month_pay = $row_this_month_pay[0]?$row_this_month_pay[0]:0;
    $cnt_all_prod_this = floor($this_month_pay * 1 / 20000);//당월 가져오기 가능 전체건수
    if($cnt_all_prod_this > 20){
        $cnt_all_prod_this = 20;
    }

    $sql_get_prod_this = "select count(*) from Gn_Iam_Contents_Gwc where mem_id='{$_SESSION[iam_member_id]}' and ori_store_prod_idx!=0 and req_data>'{$date_this_month}'";
    $res_get_prod_this = mysqli_query($self_con,$sql_get_prod_this);
    $row_get_prod_this = mysqli_fetch_array($res_get_prod_this);//당월 가져오기한 총 건수

    $sql_pre_month_pay = "select SUM(TotPrice) as money from tjd_pay_result where cash_prod_pay=0 and gwc_cont_pay=1 and buyer_id='{$_SESSION[iam_member_id]}' and end_status='Y' and date>'{$date_pre_month}' and date<'{$date_this_month}'";//전달 구매금액
    $res_pre_month_pay = mysqli_query($self_con,$sql_pre_month_pay);
    $row_pre_month_pay = mysqli_fetch_array($res_pre_month_pay);
    
    $pre_month_pay = $row_pre_month_pay[0]?$row_pre_month_pay[0]:0;
    $cnt_all_prod_pre = floor($pre_month_pay * 1 / 20000);//전달 가져오기 가능 전체건수
    if($cnt_all_prod_pre > 20){
        $cnt_all_prod_pre = 20;
    }

    $sql_get_prod_pre = "select count(*) from Gn_Iam_Contents_Gwc where mem_id='{$_SESSION[iam_member_id]}' and ori_store_prod_idx!=0 and req_data>'{$date_pre_month}' and req_data<'{$date_this_month}'";
    $res_get_prod_pre = mysqli_query($self_con,$sql_get_prod_pre);
    $row_get_prod_pre = mysqli_fetch_array($res_get_prod_pre);//전달 가져오기한 총 건수


    $sql_get_prod = "select count(*) from Gn_Iam_Contents_Gwc where mem_id='{$_SESSION[iam_member_id]}' and ori_store_prod_idx!=0";
    $res_get_prod = mysqli_query($self_con,$sql_get_prod);
    $row_get_prod = mysqli_fetch_array($res_get_prod);//현재까지 가져오기한 총 건수

    echo json_encode(array('this_month_money'=>$this_month_pay,'this_month_cnt'=>$cnt_all_prod_this,'this_get_cnt'=>$row_get_prod_this[0],'pre_month_money'=>$pre_month_pay,'pre_month_cnt'=>$cnt_all_prod_pre,'pre_get_cnt'=>$row_get_prod_pre[0],'all_get_cnt'=>$row_get_prod[0]));
}
else if($mode == "req_provider"){
    $worker_img = '';
    $tmpFilePath = $_FILES['worker_img']['tmp_name'];
    if ($tmpFilePath != "") {
        $date_file_name = date('dmYHis') . str_replace(" ", "", basename($_FILES["worker_img"]["name"]));
        $uploadfile = $uploaddir . basename($date_file_name);
        if (move_uploaded_file($tmpFilePath, $uploadfile)) {
            $worker_img = $up_dir . basename($date_file_name);
            $handle = new Image($uploadfile, 800);
            $handle->resize();
            uploadFTP($uploadfile);
        }
    }
    if($worker_img != ''){
        $img_str = ", gwc_worker_img='".$worker_img."'";
    }
    else{
        $img_str = '';
    }
    if($gongup_id){
        $mem_id = $gongup_id;
    }
    else{
        $mem_id = $_SESSION[iam_member_id];
    }
    $sql_update = "update Gn_Member set gwc_leb=2, gwc_req_leb=2, gwc_provider_name='{$provider_name}', gwc_worker_no='{$worker_no}', gwc_worker_state='{$gwc_worker_state}'".$img_str." where mem_id='{$mem_id}'";
    mysqli_query($self_con,$sql_update);

    //location.href='/?cur_win=shared_receive&req_provide=Y';
    // echo "<script>location.href='/?cur_win=unread_notice&req_provide=Y';</script>";
    echo "<script>history.go(-1);</script>";
}
else if($mode == "check_prod_order_date"){
    $sql_order_data = "select reg_date from Gn_Gwc_Order where id='{$order_id}'";
    $res_order_data = mysqli_query($self_con,$sql_order_data);
    $row_order_data = mysqli_fetch_array($res_order_data);

    $cur_time_com_stamp = time() - (86400 * 7);
    $date_pre_month = date("Y-m-d H:i:s", $cur_time_com_stamp);

    if($date_pre_month > $row_order_data[0]){
        echo json_encode(array('result'=>"0"));
    }
    else{
        echo json_encode(array('result'=>"1"));
    }
}
else if($mode == "req_edit_order"){
    $mid = date("YmdHis").rand(10,99);
    $sql_update = "update Gn_Gwc_Order set prod_state='{$edit_type}', state_detail='{$title}', prod_req_no='{$mid}', prod_req_date=now() where id='{$order_id}'";
    $res = mysqli_query($self_con,$sql_update);

    echo $res;
}
else if($mode == "get_prod_info"){
    $sql_prod = "select * from Gn_Iam_Contents_Gwc where idx='{$id}'";
    $res_prod = mysqli_query($self_con,$sql_prod);
    $row_prod = mysqli_fetch_array($res_prod);

    $mem_id = "";
    $same = "N";

    if($row_prod[delivery_id_code]){
        $sql_mem_data = "select mem_code, mem_id, mem_name, mem_phone, mem_add1, mem_email, bank_name, bank_owner, bank_account from Gn_Member where mem_code='{$row_prod[delivery_id_code]}'";
        $res_mem_data = mysqli_query($self_con,$sql_mem_data);
        $row_mem_data = mysqli_fetch_array($res_mem_data);
        $mem_id = $row_mem_data[mem_id];
    }
    if($row_prod[idx]){
        if($mem_id != ''){
            $mem_name = $row_mem_data[mem_name];
            $mem_phone = $row_mem_data[mem_phone];
            $mem_add1 = $row_mem_data[mem_add1];
            $mem_email = $row_mem_data[mem_email];
            $bank_name = $row_mem_data[bank_name];
            $bank_owner = $row_mem_data[bank_owner];
            $bank_account = $row_mem_data[bank_account];
            if($mem_id == $_SESSION[iam_member_id]){
                $same = "Y";
            }
        }
        else{
            $mem_name = '';
            $mem_phone = '';
            $mem_add1 = '';
            $mem_email = '';
            $bank_name = '';
            $bank_owner = '';
            $bank_account = '';
        }
        echo json_encode(array('result'=>1, 'card_short_url'=>$row_prod[card_short_url], 'westory_card_url'=>$row_prod[westory_card_url], 'contents_url_title'=>$row_prod[contents_url_title], 'except_keyword'=>$row_prod[except_keyword], 'contents_display'=>$row_prod[contents_display], 'contents_westory_display'=>$row_prod[contents_westory_display], 'contents_type_display'=>$row_prod[contents_type_display], 'contents_user_display'=>$row_prod[contents_user_display], 'contents_footer_display'=>$row_prod[contents_footer_display], 'contents_share_text'=>$row_prod[contents_share_text], 'card_idx'=>$row_prod[card_idx], 'init_reduce_val'=>$row_prod[init_reduce_val], 'reduce_val'=>$row_prod[reduce_val], 'landing_mode'=>$row_prod[landing_mode], 'contents_iframe'=>$row_prod[contents_iframe], 'source_iframe'=>$row_prod[source_iframe], 'contents_title'=>$row_prod[contents_title], 'contents_desc'=>$row_prod[contents_desc], 'gwc_con_state'=>$row_prod[gwc_con_state], 'contents_img'=>$row_prod[contents_img], 'contents_url'=>$row_prod[contents_url], 'open_type'=>$row_prod[open_type], 'product_code'=>$row_prod[product_code], 'product_model_name'=>$row_prod[product_model_name], 'product_seperate'=>$row_prod[product_seperate], 'contents_price'=>$row_prod[contents_price], 'contents_sell_price'=>$row_prod[contents_sell_price], 'send_provide_price'=>$row_prod[send_provide_price], 'prod_manufact_price'=>$row_prod[prod_manufact_price], 'send_salary_price'=>$row_prod[send_salary_price], 'delivery_id_code'=>$row_prod[delivery_id_code], 'delivery_mem_id'=>$mem_id, 'delivery_mem_name'=>$mem_name, 'delivery_mem_phone'=>$mem_phone, 'delivery_mem_add1'=>$mem_add1, 'delivery_mem_email'=>$mem_email, 'delivery_bank_name'=>$bank_name, 'delivery_bank_owner'=>$bank_owner, 'delivery_bank_account'=>$bank_account, 'same_id'=>$same));
    }
}
exit;
?>