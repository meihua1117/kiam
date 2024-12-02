<?
include_once "lib/rlatjd_fun.php";
if($_SESSION['one_member_id'] == "")
	exit;
$year = date('Y');
$month = date('m');
$day = date('d');
$hour = date('H');
$minute = date('i');

$sql_def_reduce = "select key_content from Gn_Search_Key where key_id='buyer_point_reduce_percent'";
$res_def_reduce = mysqli_query($self_con,$sql_def_reduce);
$row_def_reduce = mysqli_fetch_array($res_def_reduce);
$def_reduce = $row_def_reduce['key_content'];
$def_reduce1 = 100 - $def_reduce * 1;

$sql="select * from Gn_Member where mem_id='{$_SESSION['one_member_id']}' and site != '' ";
$resul=mysqli_query($self_con,$sql);
$data=mysqli_fetch_array($resul);
$payMethod = 'CARD';
if($_POST['payMethod'] != "") {
    $payMethod = $_POST['payMethod'];
}

if ($_POST['payMethod'] == "order_change") {
    $order_ids = array();
    $method_type = 0;
    if(isset($_POST['payMethod1']) && $_POST['payMethod1'] == "order_complete"){
        $sql_order_ids = "select id from Gn_Gwc_Order where pay_order_no='{$_POST['allat_order_no']}'";
        $res_order_ids = mysqli_query($self_con,$sql_order_ids);
        while($row_order_ids = mysqli_fetch_array($res_order_ids)){
            array_push($order_ids, $row_order_ids['id']);
        }
    }
    else if(isset($_POST['payMethod1']) && $_POST['payMethod1'] == "order_complete_admin"){
        $sql_order_id = "select id from Gn_Gwc_Order where tjd_idx='{$_POST['tjd_no']}'";
        $res_order_id = mysqli_query($self_con,$sql_order_id);
        $row_order_id = mysqli_fetch_array($res_order_id);
        array_push($order_ids, $row_order_id['id']);
    }
    else{
        $method_type = 1;//주문취소문안
        array_push($order_ids, $_POST['order_id']);
    }

    for($i = 0; $i < count($order_ids); $i++){
        $sql_order_data = "select * from Gn_Gwc_Order where id='{$order_ids[$i]}'";
        $res_order_data = mysqli_query($self_con,$sql_order_data);
        $row_order_data = mysqli_fetch_array($res_order_data);
    
        $sql_mem_name = "select mem_name, mem_phone from Gn_Member where mem_id='{$row_order_data['mem_id']}'";
        $res_mem_name = mysqli_query($self_con,$sql_mem_name);
        $row_mem_name = mysqli_fetch_array($res_mem_name);
    
        $sql_deliver_code = "select contents_title, delivery_id_code from Gn_Iam_Contents_Gwc where idx='{$row_order_data['contents_idx']}'";
        $res_deliver_code = mysqli_query($self_con,$sql_deliver_code);
        $row_deliver_code = mysqli_fetch_array($res_deliver_code);
    
        if($row_deliver_code['delivery_id_code']){
            $sql_deliver = "select mem_id, mem_phone from Gn_Member where mem_code='{$row_deliver_code['delivery_id_code']}'";
            $res_deliver = mysqli_query($self_con,$sql_deliver);
            $row_deliver = mysqli_fetch_array($res_deliver);
        }
    
        $sql_seller_data = "select mem_phone from Gn_Member where mem_id='{$row_order_data['seller_id']}'";
        $res_seller_data = mysqli_query($self_con,$sql_seller_data);
        $row_seller_data = mysqli_fetch_array($res_seller_data);
    
        if($method_type){
            $subject = "취소/교환/반품 접수안내 문자";
            $type = $_POST['type'];
            $content = $year."년 ".$month."월 ".$day."일 ".$hour.":".$minute." ".$row_order_data['mem_id']."/".$row_mem_name['mem_name']."님의 (".$type.") 접수안내. ";
            $content .= "주문변경사유 : " . $row_order_data['state_detail'];
        }
        else{
            $subject = "굿마켓 상품구매안내 문자";
            $content = $year."년 ".$month."월 ".$day."일 ".$hour.":".$minute." ".$row_order_data['mem_id']."/".$row_mem_name['mem_name']."님이 [".$row_deliver_code['contents_title']."] 상품을 구매하셨습니다. ";

            send_mms($row_order_data['mem_id'], $row_mem_name['mem_phone'], $subject, $content);//구매자문자발송
        }
    
        if($row_deliver_code['delivery_id_code']){//공급사문자발송
            send_mms($row_deliver['mem_id'], $row_deliver['mem_phone'], $subject, $content);
        }
        send_mms($row_order_data['seller_id'], $row_seller_data['mem_phone'], $subject, $content);//배송자문자발송
    }
    echo 1;
    exit;
}
if(isset($_POST['point_val'])){
    $point = 1;

    $cont_idx = explode('contents_idx=', $_POST['contents_url']);
    $contents_idx = trim($cont_idx[1]);
    $sql_card_idx = "select card_idx, contents_price, contents_sell_price from Gn_Iam_Contents where idx='{$contents_idx}'";
    $res_card_idx = mysqli_query($self_con,$sql_card_idx);
    $row_card_idx = mysqli_fetch_array($res_card_idx);

    $sql_card_data = "select sale_cnt, add_fixed_val, card_name, card_company from Gn_Iam_Name_Card where idx='{$row_card_idx['card_idx']}'";
    $res_card_data = mysqli_query($self_con,$sql_card_data);
    $row_card_data = mysqli_fetch_array($res_card_data);

    $pay_percent = ($row_card_idx['contents_sell_price'] / $row_card_idx['contents_price']) * 100;
    if(!$row_card_data['sale_cnt']){
        $cont_percent = 100 - (int)$pay_percent + $row_card_data['add_fixed_val'];
    }
    else{
        $cont_percent = 100 - (int)$pay_percent;
    }
    $point_percent = $cont_percent . "/" . $def_reduce;

    if(isset($_POST['service'])){
        $current_point_buy = $data['mem_cash'] * 1 - ceil($_POST['allat_amt'] * ($pay_percent * 1 / 100) * ($def_reduce * 1 / 100));
        $_POST['seller_id'] = $payMethod;

        // $get_seller_point = "select current_point from Gn_Item_Pay_Result where buyer_id='{$payMethod}' and point_val!=0 and pay_status='Y' order by pay_date desc limit 1";
        $get_seller_data = "select mem_cash, mem_point, mem_phone, mem_name from Gn_Member where mem_id='{$payMethod}'";
        $result_seller_data = mysqli_query($self_con,$get_seller_data);
        $row_seller_data = mysqli_fetch_array($result_seller_data);
        $seller_cash = ceil($_POST['allat_amt'] * ($cont_percent * 1 /100));
        $current_cash_seller = $row_seller_data['mem_cash'] * 1 + $seller_cash * 1;
    }
    if(!isset($_POST['mypage'])){
        $sql_buyer = "insert into Gn_Item_Pay_Result set buyer_id='{$_SESSION['one_member_id']}',
                                                        buyer_tel='{$data['mem_phone']}',
                                                        site='{$_POST['contents_url']}',
                                                        pay_method='$payMethod',
                                                        item_name = '{$_POST['member_type']}',
                                                        item_price={$_POST['allat_amt']},
                                                        seller_id='{$_POST['seller_id']}',
                                                        pay_date=NOW(),
                                                        pay_status='Y',
                                                        pay_percent='$pay_percent',
                                                        order_number = '{$_POST['allat_order_no']}',
                                                        VACT_InputName='{$data['mem_name']}',
                                                        point_val=$point,
                                                        type='use',
                                                        current_point='{$data['mem_point']}',
                                                        current_cash='{$data['mem_cash']}',
                                                        point_percent='$point_percent',
                                                        contents_cnt='{$_POST['item_cnt']}',
                                                        billdate=now()";
        $res_result = mysqli_query($self_con,$sql_buyer);
        $db_idx_buy = mysqli_insert_id($self_con);
        // $sql_update = "update Gn_Member set mem_point={$current_point_buy} where mem_id='{$_SESSION['one_member_id']}'";
        // $res_result = mysqli_query($self_con,$sql_update);

        $sql_card_data = "select sale_cnt, add_fixed_val, card_name, card_company from Gn_Iam_Name_Card where idx='{$row_card_idx['card_idx']}'";
        $res_card_data = mysqli_query($self_con,$sql_card_data);
        $row_card_data = mysqli_fetch_array($res_card_data);

        if($row_card_data['sale_cnt']){
            $sale_after_cnt = $row_card_data['sale_cnt'] * 1 - $_POST['item_cnt'] * 1;
            if($sale_after_cnt){
                $sql_reduce_update = "update Gn_Iam_Name_Card set sale_cnt='{$sale_after_cnt}' where idx='{$row_card_idx['card_idx']}'";
                mysqli_query($self_con,$sql_reduce_update);
            }
            else{
                    $sql_reduce_update = "update Gn_Iam_Name_Card set sale_cnt='{$sale_after_cnt}' where idx='{$row_card_idx['card_idx']}'";
                mysqli_query($self_con,$sql_reduce_update);
            }
        }

        $sql_seller = "insert into Gn_Item_Pay_Result set buyer_id='$payMethod',
                                                        buyer_tel='{$data['mem_phone']}',
                                                        site='{$_POST['contents_url']}',
                                                        pay_method='{$_SESSION['one_member_id']}',
                                                        item_name = '{$_POST['member_type']}',
                                                        item_price={$_POST['allat_amt']},
                                                        seller_id='{$_SESSION['one_member_id']}',
                                                        pay_date=NOW(),
                                                        pay_status='Y',
                                                        pay_percent='$pay_percent',
                                                        order_number = '{$_POST['allat_order_no']}',
                                                        VACT_InputName='{$data['mem_name']}',
                                                        point_val=$point,
                                                        type='servicebuy',
                                                        current_point='{$row_seller_data['mem_point']}',
                                                        current_cash='{$row_seller_data['mem_cash']}',
                                                        point_percent='$point_percent',
                                                        contents_cnt='{$_POST['item_cnt']}',
                                                        billdate=now()";
            $res_result = mysqli_query($self_con,$sql_seller);
        $db_idx_sell = mysqli_insert_id($self_con);

        $con_name = explode("/", $_POST['member_type']);
        if(strpos($row_card_data['card_company'], '별점') !== false || strpos($row_card_data['card_company'], '방문자리뷰') !== false || strpos($row_card_data['card_company'], '블로그리뷰') !== false){
            $org_name1 = explode(",", $row_card_data['card_name']);
            $org_name = $org_name1[0];
        }
        else{
            $org_name = $row_card_data['card_company'];
        }
    }
    
    // $sql_update = "update Gn_Member set mem_point={$current_point_seller} where mem_id='{$payMethod}'";
    // $res_result = mysqli_query($self_con,$sql_update);

    if(isset($_POST['mypage'])){
        if(isset($_POST['mypage_buy'])){
            $db_idx_buy = $_POST['db_idx_buy'];
            $db_idx_sell = $db_idx_buy * 1 + 1;
    
            $sql_db_data_buyer = "select * from Gn_Item_Pay_Result where no='$db_idx_buy'";
            $res_db_data_buyer = mysqli_query($self_con,$sql_db_data_buyer);
            $row_db_data_buyer = mysqli_fetch_array($res_db_data_buyer);
    
            $mem_data_buyer = "select mem_phone from Gn_Member where mem_id='{$row_db_data_buyer['buyer_id']}'";
            $res_mem_data_buyer = mysqli_query($self_con,$mem_data_buyer);
            $row_mem_data_buyer = mysqli_fetch_array($res_mem_data_buyer);
    
            $sql_db_data_seller = "select * from Gn_Item_Pay_Result where no='$db_idx_sell'";
            $res_db_data_seller = mysqli_query($self_con,$sql_db_data_seller);
            $row_db_data_seller = mysqli_fetch_array($res_db_data_seller);
    
            $mem_data_seller = "select mem_phone from Gn_Member where mem_id='{$row_db_data_seller['seller_id']}'";
            $res_mem_data_seller = mysqli_query($self_con,$mem_data_seller);
            $row_mem_data_seller = mysqli_fetch_array($res_mem_data_seller);
    
            $cont_idx = explode('contents_idx=', $row_db_data_buyer['site']);
            $contents_idx = trim($cont_idx[1]);
            $sql_card_idx = "select card_idx, contents_price, contents_sell_price from Gn_Iam_Contents where idx='{$contents_idx}'";
            $res_card_idx = mysqli_query($self_con,$sql_card_idx);
            $row_card_idx = mysqli_fetch_array($res_card_idx);
    
            $sql_card_data = "select sale_cnt, add_fixed_val, card_name, card_company from Gn_Iam_Name_Card where idx='{$row_card_idx['card_idx']}'";
            $res_card_data = mysqli_query($self_con,$sql_card_data);
            $row_card_data = mysqli_fetch_array($res_card_data);

            $pay_percent = ($row_card_idx['contents_sell_price'] / $row_card_idx['contents_price']) * 100;
            if(!$row_card_data['sale_cnt']){
                $cont_percent = 100 - (int)$pay_percent + $row_card_data['add_fixed_val'];
            }
            else{
                $cont_percent = 100 - (int)$pay_percent;
            }
            $point_percent = $cont_percent . "/" . $def_reduce;
    
            $con_name = explode("/", $row_db_data_buyer['item_name']);
            if(strpos($row_card_data['card_company'], '별점') !== false || strpos($row_card_data['card_company'], '방문자리뷰') !== false || strpos($row_card_data['card_company'], '블로그리뷰') !== false){
                $org_name1 = explode(",", $row_card_data['card_name']);
                $org_name = $org_name1[0];
            }
            else{
                $org_name = $row_card_data['card_company'];
            }
    
            $add_point = $row_db_data_buyer['item_price'] * 1 * ($pay_percent * 1 / 100);
            $final_point = $row_db_data_buyer['item_price'] * 1 + (int)$add_point;
    
            $subject = "판매확인 문자";
            $content = $year."년 ".$month."월 ".$day."일 ".$hour.":".$minute."에 ".$row_db_data_buyer['buyer_id']."님이 ".$org_name."업체 ".$con_name[1]."상품 ".$row_db_data_buyer['item_price']."원 구매확인하셨습니다.";
            send_mms($row_db_data_buyer['buyer_id'], $row_mem_data_seller['mem_phone'], $subject, $content);

            $mid = date("YmdHis").rand(10,99);        
            $sql_notice_recv_seller = "insert into Gn_Item_Pay_Result set buyer_id='{$row_db_data_seller['buyer_id']}',
                                                                        buyer_tel='{$row_mem_data_seller['mem_phone']}',
                                                                        site='',
                                                                        pay_method='obmms02',
                                                                        item_name = '공지사항전송',
                                                                        pay_date=NOW(),
                                                                        pay_status='Y',
                                                                        pay_percent='90',
                                                                        order_number = '$mid',
                                                                        VACT_InputName='{$row_mem_data_seller['mem_name']}',
                                                                        seller_id='{$subject}',
                                                                        point_val=3,
                                                                        type='noticerecv',
                                                                        current_point={$row_mem_data_seller['mem_point']},
                                                                        current_cash={$row_mem_data_seller['mem_cash']},
                                                                        receive_state=1,
                                                                        message='$content',
                                                                        alarm_state=0,
                                                                        billdate=now()";
            mysqli_query($self_con,$sql_notice_recv_seller);
    
            $subject1 = "구매확인 문자";
            $content1 = $year."년 ".$month."월 ".$day."일 ".$hour.":".$minute."에  ".$org_name."업체 ".$con_name[1]." 상품 ".$row_db_data_buyer['item_price']."원 구매확인하셨습니다. ".$final_point."포인트가 지급되었습니다.";
            send_mms($row_db_data_buyer['buyer_id'], $row_mem_data_buyer['mem_phone'], $subject1, $content1);
            echo 1;
            exit;
        }
        else if(isset($_POST['mypage_sell'])){
            $db_idx_sell = $_POST['db_idx_sell'];
            $db_idx_buy = $db_idx_sell * 1 - 1;
    
            $sql_db_data_seller = "select * from Gn_Item_Pay_Result where no='$db_idx_sell'";
            $res_db_data_seller = mysqli_query($self_con,$sql_db_data_seller);
            $row_db_data_seller = mysqli_fetch_array($res_db_data_seller);
    
            $mem_data_seller = "select mem_phone from Gn_Member where mem_id='{$row_db_data_seller['buyer_id']}'";
            $res_mem_data_seller = mysqli_query($self_con,$mem_data_seller);
            $row_mem_data_seller = mysqli_fetch_array($res_mem_data_seller);
    
            $sql_db_data_buyer = "select * from Gn_Item_Pay_Result where no='$db_idx_buy'";
            $res_db_data_buyer = mysqli_query($self_con,$sql_db_data_buyer);
            $row_db_data_buyer = mysqli_fetch_array($res_db_data_buyer);
    
            $mem_data_buyer = "select mem_phone from Gn_Member where mem_id='{$row_db_data_buyer['buyer_id']}'";
            $res_mem_data_buyer = mysqli_query($self_con,$mem_data_buyer);
            $row_mem_data_buyer = mysqli_fetch_array($res_mem_data_buyer);
    
            $cont_idx = explode('contents_idx=', $row_db_data_seller['site']);
            $contents_idx = trim($cont_idx[1]);
            $sql_card_idx = "select card_idx, contents_price, contents_sell_price from Gn_Iam_Contents where idx='{$contents_idx}'";
            $res_card_idx = mysqli_query($self_con,$sql_card_idx);
            $row_card_idx = mysqli_fetch_array($res_card_idx);
    
            $sql_card_data = "select sale_cnt, add_fixed_val, card_name, card_company from Gn_Iam_Name_Card where idx='{$row_card_idx['card_idx']}'";
            $res_card_data = mysqli_query($self_con,$sql_card_data);
            $row_card_data = mysqli_fetch_array($res_card_data);

            $pay_percent = ($row_card_idx['contents_sell_price'] / $row_card_idx['contents_price']) * 100;
            if(!$row_card_data['sale_cnt']){
                $cont_percent = 100 - (int)$pay_percent + $row_card_data['add_fixed_val'];
            }
            else{
                $cont_percent = 100 - (int)$pay_percent;
            }
            $point_percent = $cont_percent . "/" . $def_reduce;
    
            $con_name = explode("/", $row_db_data_seller['item_name']);
            if(strpos($row_card_data['card_company'], '별점') !== false || strpos($row_card_data['card_company'], '방문자리뷰') !== false || strpos($row_card_data['card_company'], '블로그리뷰') !== false){
                $org_name1 = explode(",", $row_card_data['card_name']);
                $org_name = $org_name1[0];
            }
            else{
                $org_name = $row_card_data['card_company'];
            }
    
            $add_point = $row_db_data_seller['item_price'] * 1 * ($pay_percent * 1 / 100);
            $final_point = $row_db_data_seller['item_price'] * 1 + (int)$add_point;
    
            $subject = "판매확인 문자";
            $content = $year."년 ".$month."월 ".$day."일 ".$hour.":".$minute."에 ".$org_name."업체 ".$con_name[1]."상품 ".$row_db_data_seller['item_price']."원 판매확인하셨습니다. ".$final_point."포인트가 지급되었습니다.";
            send_mms($row_db_data_seller['buyer_id'], $row_mem_data_seller['mem_phone'], $subject, $content);
    
            $subject1 = "구매확인 문자";
            $content1 = "판매자분이 ".$year."년 ".$month."월 ".$day."일 ".$hour.":".$minute."에  ".$org_name."업체 ".$con_name[1]." 상품 ".$row_db_data_seller['item_price']."원 판매확인하셨습니다.";
            send_mms($row_db_data_buyer['buyer_id'], $row_mem_data_buyer['mem_phone'], $subject1, $content1);

            $mid = date("YmdHis").rand(10,99);        
            $sql_notice_recv_buyer = "insert into Gn_Item_Pay_Result set buyer_id='{$row_db_data_buyer['buyer_id']}',
                                                                        buyer_tel='{$row_mem_data_buyer['mem_phone']}',
                                                                        site='',
                                                                        pay_method='obmms02',
                                                                        item_name = '공지사항전송',
                                                                        pay_date=NOW(),
                                                                        pay_status='Y',
                                                                        pay_percent='90',
                                                                        order_number = '$mid',
                                                                        VACT_InputName='{$row_mem_data_buyer['mem_name']}',
                                                                        seller_id='{$subject1}',
                                                                        point_val=3,
                                                                        type='noticerecv',
                                                                        current_point={$row_mem_data_buyer['mem_point']},
                                                                        current_cash={$row_mem_data_buyer['mem_cash']},
                                                                        receive_state=1,
                                                                        message='$content1',
                                                                        alarm_state=0,
                                                                        billdate=now()";
            mysqli_query($self_con,$sql_notice_recv_buyer);
            echo 1;
            exit;
        }
    }
    else{
        $buy_link_apply = "http://".$HTTP_HOST."/iam/ajax/apply_service_con_res.php?mode=buy&residx_sell=".$db_idx_sell."&residx_buy=".$db_idx_buy;
        $buy_link_apply = get_short_url($buy_link_apply);
	$subject = "구매확인 문자";
        $content = "회원님은 ".$year."년 ".$month."월 ".$day."일 ".$hour.":".$minute."에 ".$_SESSION['one_member_id']."아이디로 ".$org_name."업체에서 ".$con_name[1]." 상품을 ".$row_card_idx['contents_price']."원에 구매하셨습니다. 회원님이 ".$org_name."업체에서 해당 상품을 구매확인하면 아래 구매확인링크를 클릭해주시면 해당 상품 포인트중 ".$def_reduce."%를 회원님의 캐시포인트로 지급합니다. 캐시포인트 ".$def_reduce1."%는 해당 업무에 지원되는 운영비로서 추후 조정될 수 있습니다.  * 구매확인링크 : ".$buy_link_apply." *해당 링크는 마이페이지>결제확인에서 확인할수 있습니다. 해당페이지로 가려면 여기를 클릭하세요.";

        $content1 = "회원님은 ".$year."년 ".$month."월 ".$day."일 ".$hour.":".$minute."에 ".$_SESSION['one_member_id']."아이디로 ".$org_name."업체에서 ".$con_name[1]." 상품을 ".$row_card_idx['contents_price']."원에 구매하셨습니다. 회원님이 ".$org_name."업체에서 해당 상품을 구매확인하면 아래 구매확인링크를 클릭해주시면 해당 상품 포인트중 ".$def_reduce."%를 회원님의 캐시포인트로 지급합니다. 캐시포인트 ".$def_reduce1."%는 해당 업무에 지원되는 운영비로서 추후 조정될 수 있습니다.  *해당 링크는 마이페이지>결제확인에서 확인할수 있습니다. 해당페이지로 가려면 여기를 클릭하세요.";
    
        send_mms($_SESSION['one_member_id'], $data['mem_phone'], $subject, $content);
    
        $mid = date("YmdHis").rand(10,99);
        $sql_admin_data = "select * from Gn_Member where mem_id='obmms02'";
        $res_admin_data = mysqli_query($self_con,$sql_admin_data);
        $row_admin_data = mysqli_fetch_array($res_admin_data);
    
        $sql_notice_recv_buyer = "insert into Gn_Item_Pay_Result set buyer_id='{$_SESSION['one_member_id']}',
                                                                    buyer_tel='{$data['mem_phone']}',
                                                                    site='$buy_link_apply',
                                                                    pay_method='obmms02',
                                                                    item_name = '공지사항전송',
                                                                    pay_date=NOW(),
                                                                    pay_status='Y',
                                                                    pay_percent='90',
                                                                    order_number = '$mid',
                                                                    VACT_InputName='{$data['mem_name']}',
                                                                    seller_id='{$subject}',
                                                                    point_val=3,
                                                                    type='noticerecv',
                                                                    current_point={$data['mem_point']},
                                                                    current_cash={$data['mem_cash']},
                                                                    receive_state=1,
                                                                    message='$content1',
                                                                    alarm_state=0,
                                                                    billdate=now()";
        mysqli_query($self_con,$sql_notice_recv_buyer);

        $sell_link_apply = "http://".$HTTP_HOST."/iam/ajax/apply_service_con_res.php?mode=sell&residx_sell=".$db_idx_sell."&residx_buy=".$db_idx_buy;
        $sell_link_apply = get_short_url($sell_link_apply);
        $subject = "판매확인 문자";
        $content = "IAM플랫폼입니다. ".$_SESSION['one_member_id']."회원님이 ".$payMethod."판매자님의 상품을 ".$row_card_idx['contents_price']."원에(할인 ".$cont_percent."%)에 구매하였습니다. 회원님의 문자로 구매 안내와 ".$org_name." 업체 정보를 전송하였습니다. ".$_SESSION['one_member_id']."회원님이 업체에서 상품을 구매하면 아래 판매확인링크를 클릭해주세요.*  판매확인링크 : ".$sell_link_apply." 그리고 아래 회원님의 정보를 안내하오니 필요한 경우 구매회원님과 상품구매까지 잘 안내 바랍니다.  구매일시 :".date('Y-m-d H:i:s').",구매회원아이디 :".$_SESSION['one_member_id'].",구매회원휴대폰 :".$data['mem_phone'].",구매상품 :".$con_name[1].",구매가격 :".$row_card_idx['contents_price'].",할인비율 : ".$cont_percent;
        send_mms($payMethod, $row_seller_data['mem_phone'], $subject, $content);

        $content1 = "IAM플랫폼입니다. ".$_SESSION['one_member_id']."회원님이 ".$payMethod."판매자님의 상품을 ".$row_card_idx['contents_price']."원에(할인 ".$cont_percent."%)에 구매하였습니다. 회원님의 문자로 구매 안내와 ".$org_name." 업체 정보를 전송하였습니다. ".$_SESSION['one_member_id']."회원님이 업체에서 상품을 구매하면 아래 판매확인링크를 클릭해주세요. 그리고 아래 회원님의 정보를 안내하오니 필요한 경우 구매회원님과 상품구매까지 잘 안내 바랍니다.  구매일시 :".date('Y-m-d H:i:s').",구매회원아이디 :".$_SESSION['one_member_id'].",구매회원휴대폰 :".$data['mem_phone'].",구매상품 :".$con_name[1].",구매가격 :".$row_card_idx['contents_price'].",할인비율 : ".$cont_percent;
        send_mms($payMethod, $row_seller_data['mem_phone'], $subject, $content);
    
        $mid = date("YmdHis").rand(10,99);
        $sql_notice_recv_seller = "insert into Gn_Item_Pay_Result set buyer_id='$payMethod',
                                                                    buyer_tel='{$row_seller_data['mem_phone']}',
                                                                    site='$sell_link_apply',
                                                                    pay_method='obmms02',
                                                                    item_name = '공지사항전송',
                                                                    pay_date=NOW(),
                                                                    pay_status='Y',
                                                                    pay_percent='90',
                                                                    order_number = '$mid',
                                                                    VACT_InputName='{$row_seller_data['mem_name']}',
                                                                    seller_id='{$subject}',
                                                                    point_val=3,
                                                                    type='noticerecv',
                                                                    current_point={$row_seller_data['mem_point']},
                                                                    current_cash={$row_seller_data['mem_cash']},
                                                                    receive_state=1,
                                                                    message='$content1',
                                                                    alarm_state=0,
                                                                    billdate=now()";
        mysqli_query($self_con,$sql_notice_recv_seller);

        $subject = "본사 서비스콘 판매 문자";
        $content = $year."년 ".$month."월 ".$day."일 ".$hour.":".$minute."에 ".$_SESSION['one_member_id']."회원님이 ".$payMethod."판매자님의 [".$con_name[1]."] 상품을 구매하였습니다";
        $send_num = "01083904260";
        sendmms(5, "obmms02", $send_num, $send_num, "", $subject, $content, "", "", "", "Y");

        $mid = date("YmdHis").rand(10,99);
        $sql_notice_recv_admin = "insert into Gn_Item_Pay_Result set buyer_id='obmms02',
                                                                    buyer_tel='{$row_admin_data['mem_phone']}',
                                                                    site='',
                                                                    pay_method='obmms02',
                                                                    item_name = '공지사항전송',
                                                                    pay_date=NOW(),
                                                                    pay_status='Y',
                                                                    pay_percent='90',
                                                                    order_number = '$mid',
                                                                    VACT_InputName='{$row_admin_data['mem_name']}',
                                                                    seller_id='{$subject}',
                                                                    point_val=3,
                                                                    type='noticerecv',
                                                                    current_point={$row_admin_data['mem_point']},
                                                                    current_cash={$row_admin_data['mem_cash']},
                                                                    receive_state=1,
                                                                    message='$content',
                                                                    alarm_state=0,
                                                                    billdate=now()";
        $res_result = mysqli_query($self_con,$sql_notice_recv_admin);
        echo 1;
        exit;
    }
}else{
    $point = 0;
    $item_price = $_POST['allat_amt'] * 1;

    $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$_SESSION['one_member_id']}',
                                                buyer_tel='{$data['mem_phone']}',
                                                pay_method='$payMethod',
                                                item_name = '{$_POST['member_type']}',
                                                item_price=$item_price,
                                                seller_id='{$_POST['seller_id']}',
                                                pay_date=NOW(),
                                                pay_percent='{$_POST['pay_percent']}',
                                                order_number = '{$_POST['allat_order_no']}',
                                                VACT_InputName='{$data['mem_name']}',
                                                point_val=$point,
                                                billdate=now()";
    $res_result = mysqli_query($self_con,$sql);
}

function send_mms($mem_id, $phone_num, $subject, $content){
    global $self_con;
    $sql_app_mem = "select * from Gn_MMS_Number where (sendnum='$phone_num' and sendnum is not null and sendnum != '')";
    // echo $sql_app_mem."pwd"; exit;
    $res_app_mem = mysqli_query($self_con,$sql_app_mem);
    if(mysqli_num_rows($res_app_mem)){
        $number_row = mysqli_fetch_array($res_app_mem);
        sendmms(5, $number_row['mem_id'], $phone_num, $phone_num, "", $subject, $content, "", "", "", "Y");
    }
    else{
        //$msg = message_send($phone_num, $subject, $content1,"회원님의 비밀번호가 변경되었습니다.");
        //회사폰을 발송폰으로 설정해서 발송한다.010-8390-4260
        $send_num = "01083904260";
        sendmms(5, $mem_id, $send_num, $phone_num, "", $subject, $content, "", "", "", "Y");
    }
}
?>