
<?
include_once "../../lib/rlatjd_fun.php";

if($_POST['mode'] == 'update_state'){
    $req_idx = $_POST['req_idx'];
    $state = $_POST['req_state'];
    
    $sql_update = "update Gn_event_request set req_yn='{$state}' where request_idx='{$req_idx}'";
    mysqli_query($self_con,$sql_update);
}
else if($_POST['mode'] == 'req_mem_leb_up'){
    $sql_update = "update Gn_Member set mem_phone='{$_POST['req_mem_phone']}', mem_add1='{$_POST['req_mem_addr']}', bank_name='{$_POST['req_mem_bank_name']}', bank_owner='{$_POST['req_mem_bank_owner']}', bank_account='{$_POST['req_mem_bank_account']}', mem_email='{$_POST['req_mem_email']}', gwc_leb=2, gwc_req_leb=2, gwc_state=1, gwc_req_date=now() where mem_id='{$_POST['req_mem_id']}'";

    mysqli_query($self_con,$sql_update);
}
else if($_POST['mode'] == 'show_order_data'){
    $sql_order = "select prod_order_option from Gn_Iam_Contents_Gwc where idx={$_POST['con_idx']}";
    $res_order = mysqli_query($self_con,$sql_order);
    $row_order = mysqli_fetch_array($res_order);

    $order_option = htmlspecialchars_decode(str_replace("disabled", "", $row_order[0]));
    echo $order_option;
    exit;
}
else if($_POST['mode'] == 'show_conts_price'){
    $sql_price = "select mem_id, contents_sell_price, send_salary_price, over_send_salary, contents_title, contents_img from Gn_Iam_Contents_Gwc where idx={$_POST['con_idx']}";
    $res_price = mysqli_query($self_con,$sql_price);
    $row_price = mysqli_fetch_array($res_price);

    $img_arr = explode(',', $row_price['contents_img']);
    $img = trim($img_arr[0]);

    echo '{"sell_price":"'.$row_price['contents_sell_price'].'", "salary_price":"'.$row_price['send_salary_price'].'", "over_send_salary":"'.$row_price['over_send_salary'].'", "seller_id":"'.$row_price['mem_id'].'", "content_title":"'.$row_price['contents_title'].'", "content_img":"'.$img.'"}';
    exit;
}
else if($_POST['mode'] == 'req_seller_leb_up'){
    $spec_type_arr = explode(",",$member_iam['special_type']);
    array_push($spec_type_arr,$_POST['mem_special_type']);
    $spec_type_arr = array_unique($spec_type_arr);
    $spec_type = implode(",",$spec_type_arr);
    $member_iam['special_type'] = $spec_type;
    $sql_update = "update Gn_Member set mem_phone='{$_POST['seller_mem_phone']}', mem_add1='{$_POST['seller_mem_addr']}', bank_name='{$_POST['seller_mem_bank_name']}', bank_owner='{$_POST['seller_mem_bank_owner']}', bank_account='{$_POST['seller_mem_bank_account']}', mem_email='{$_POST['seller_mem_email']}', special_type='{$spec_type}' where mem_id='{$_POST['seller_mem_id']}'";
    mysqli_query($self_con,$sql_update);
}
else if($_POST['mode'] == 'save_address'){
    $main_addr_state = $_POST['main_addr_val'];
    if($main_addr_state == "1"){
        $sql_update = "update Gn_Order_Address set main_addr=0 where mem_id='{$_SESSION['iam_member_id']}'";
        mysqli_query($self_con,$sql_update);
    }
    
    $sql_chk = "select id from Gn_Order_Address where mem_id='{$_SESSION['iam_member_id']}' and name='{$_POST['name']}' and phone_num='{$_POST['phone']}' and zip='{$_POST['zip']}' and address1='{$_POST['address1']}'";
    $res_chk = mysqli_query($self_con,$sql_chk);
    $chk = mysqli_num_rows($res_chk);

    if($chk){
        $row_chk = mysqli_fetch_array($res_chk);
        $sql_update = "update Gn_Order_Address set reg_date=now(), main_addr='{$main_addr_state}' where id='{$row_chk['id']}'";
        mysqli_query($self_con,$sql_update);
        echo '{"status":"0"}';
    }
    else{
        $sql_insert = "insert into Gn_Order_Address set mem_id='{$_SESSION['iam_member_id']}', name='{$_POST['name']}', phone_num='{$_POST['phone']}', phone_num1='{$_POST['phone1']}', zip='{$_POST['zip']}', address1='{$_POST['address1']}', address2='{$_POST['address2']}', reg_date=now(), main_addr='{$main_addr_state}'";
        mysqli_query($self_con,$sql_insert);
        echo '{"status":"1"}';
    }
    exit;
}
else if($_POST['mode'] == 'delete_address'){
    if(strpos($_POST['id'], ',') !== false){
        $id_arr = explode(",", $_POST['id']);
        for($i = 0; $i < count($id_arr); $i++){
            $sql_del = "delete from Gn_Order_Address where id='{$id_arr[$i]}'";
            mysqli_query($self_con,$sql_del);
        }
    }
    else{
        $sql_del = "delete from Gn_Order_Address where id='{$_POST['id']}'";
        mysqli_query($self_con,$sql_del);
    }
}
else if($_POST['mode'] == 'set_main_address'){
    $sql_update1 = "update Gn_Order_Address set main_addr=0 where mem_id='{$_SESSION['iam_member_id']}'";
    mysqli_query($self_con,$sql_update1);

    $sql_update2 = "update Gn_Order_Address set main_addr=1 where mem_id='{$_SESSION['iam_member_id']}' and id='{$_POST['id']}'";
    mysqli_query($self_con,$sql_update2);
}
else if($_POST['mode'] == 'save_order_cart'){
    $sql_chk = "select id from Gn_Gwc_Order where contents_idx='{$_POST['cont_idx']}' and mem_id='{$_SESSION['iam_member_id']}' and page_type=1";
    $res_chk = mysqli_query($self_con,$sql_chk);
    $row_chk = mysqli_fetch_array($res_chk);

    if(!$row_chk['id']){
        $sql_order = "insert into Gn_Gwc_Order
                        set mem_id='{$_SESSION['iam_member_id']}',
                            contents_idx='{$_POST['cont_idx']}',
                            contents_price = '{$_POST['conts_price']}',
                            salary_price='{$_POST['salary']}',
                            order_option='{$_POST['option']}',
                            gwc_order_option_content='{$_POST['gwc_order_option_content']}',
                            reg_date=NOW(),
                            contents_cnt = '{$_POST['conts_cnt']}',
                            page_type=1";
        $res_result = mysqli_query($self_con,$sql_order);
    }
}
else if($_POST['mode'] == 'seldel'){
    if(!count($_POST['ct_chk']))
        alert("삭제하실 상품을 하나이상 선택해 주십시오.");
    $fldcnt = count($_POST['gs_id']);
    for($i=0; $i<$fldcnt; $i++) {
        $ct_chk = $_POST['ct_chk'][$i];
        if($ct_chk) {
            $gs_id = $_POST['gs_id'][$i];
            $sql = " delete from Gn_Gwc_Order where id='$gs_id'";
            sql_query($sql);
        }
    }
    goto_url("/iam/gwc_order_cart.php");
}
else if($_POST['mode'] == "alldel") // 모두 삭제이면
{
    $sql = " delete from Gn_Gwc_Order where page_type='1' and mem_id='{$_SESSION['iam_member_id']}' ";
    sql_query($sql);
    goto_url("/iam/gwc_order_cart.php");
}
else if($_POST['mode'] == "buy")
{
    if(!count($_POST['ct_chk']))
        alert("주문하실 상품을 하나이상 선택해 주십시오.");
	$comma = "";
	$ss_cart_id = "";
    $fldcnt = count($_POST['gs_id']);
    for($i=0; $i<$fldcnt; $i++) {
        $ct_chk = $_POST['ct_chk'][$i];
        if($ct_chk) {
			$gs_id = $_POST['gs_id'][$i];
			$sql = "select * from Gn_Gwc_Order where id='$gs_id' and page_type='1'";
			$res = sql_query($sql);
			while($row=sql_fetch_array($res)) {
				$ss_cart_id .= $comma . $row['contents_idx'];
				$comma = ">>";
			}
        }
    }
    $link = "/iam/gwc_order_pay.php?cart_ids=".$_POST['cart_ids'];
    if($_POST["shop"] != '')
        $link .= "&shop=".$_POST['shop'];
    goto_url($link);
}
else if($_POST['mode'] == "optionmod")
{
    extract($_POST);
    $price = ($it_price * 1) * ($cont_count * 1) + $gwc_ori_opt_price * 1 + $gwc_ori_sup_price * 1;

    $sql_update = "update Gn_Gwc_Order set contents_cnt='{$cont_count}', contents_price='{$price}', order_option='{$order_option}' where id='{$gs_id}'";
    mysqli_query($self_con,$sql_update);
    
    goto_url("/iam/gwc_order_cart.php");
}
echo 1;
?>