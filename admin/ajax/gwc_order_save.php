<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);

// 오늘날짜
$date_today=date("Y-m-d");

if($type == "delivery_save"){
    if($delivery_no){
        $date = date("Y-m-d H:i:s");
    }
    else{
        $date = '';
    }
    $sql_update = "update Gn_Gwc_Order set delivery='{$delivery}', delivery_no='{$delivery_no}', delivery_state='{$delivery_state}', delivery_set_date='{$date}' where id='{$id}'";
    $res = mysqli_query($self_con,$sql_update);
    echo $res;
}
else if($type == "get_delivery_link"){
    $sql = "select delivery_link from delivery_list where id='{$delivery_id}'";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);

    echo '{"link":"'.$row[0].'"}';
}
else if($type == "delete_list"){

    $no_arr = explode(",", $no);
    for($i = 0; $i < count($no_arr); $i++){
        $sql_id = "select orderNumber, buyer_id from tjd_pay_result where no='{$no_arr[$i]}'";
        $res_id = mysqli_query($self_con,$sql_id);
        $row_id = mysqli_fetch_array($res_id);
        if($row_id)
        {
            $sql_order = "select mem_id, use_point, order_mem_name, order_mem_phone, seller_id, contents_cnt from Gn_Gwc_Order where tjd_idx='{$no_arr[$i]}'";
            $res_order = mysqli_query($self_con,$sql_order);
            while($row_order = mysqli_fetch_array($res_order)){
                if($row_order[use_point]){
                    $sql_mem_cash = "update Gn_Member set mem_cash=mem_cash+{$row_order[use_point]} where mem_id='$row_order[mem_id]'";
                    mysqli_query($self_con,$sql_mem_cash);
                    $sql_mem = "select mem_point, mem_cash from Gn_Member where mem_id='{$row_order[mem_id]}'";
                    $res_mem = mysqli_query($self_con,$sql_mem);
                    $row_mem = mysqli_fetch_array($res_mem);
                    $sql_buyer = "insert into Gn_Item_Pay_Result
                            set buyer_id='$row_order[mem_id]',
                                buyer_tel='$row_order[order_mem_phone]',
                                site='',
                                pay_method='POINT',
                                item_name = '캐시포인트충전(결제취소)',
                                item_price=$row_order[use_point],
                                seller_id='$row_order[seller_id]',
                                pay_date=NOW(),
                                pay_status='Y',
                                pay_percent='',
                                order_number = '',
                                VACT_InputName='$row_order[order_mem_name]',
                                point_val=1,
                                type='buy',
                                current_point='$row_mem[mem_point]',
                                current_cash='$row_mem[mem_cash]',
                                contents_cnt='$row_order[contents_cnt]',
                                gwc_cont_pay=1";
                    $res_result = mysqli_query($self_con,$sql_buyer);
                }
            }

            $sql_del = "delete from Gn_Gwc_Order where tjd_idx='{$no_arr[$i]}'";
            mysqli_query($self_con,$sql_del);
    
            $sql_del = "delete from Gn_Item_Pay_Result where tjd_idx='{$no_arr[$i]}'";
            mysqli_query($self_con,$sql_del);
    
            $sql_del = "delete from tjd_pay_result where no='{$no_arr[$i]}'";
            mysqli_query($self_con,$sql_del);

            // 결제취소시 가져간 상품 내보내기
            $date_today = date("Y-m-d");
            $prev_month_ts = strtotime($date_today.' -1 month');
            $cur_time = time();
            $date_this_month = date("Y-m", $cur_time)."-01 00:00:00";
            $date_pre_month = date("Y-m", $prev_month_ts)."-01 00:00:00";

            $sql_get_cnt = "select count(*) from Gn_Iam_Contents_Gwc where mem_id='$row_id[buyer_id]' and ori_store_prod_idx!=0";
            $res_get_cnt = mysqli_query($self_con,$sql_get_cnt);
            $row_get_cnt = mysqli_fetch_array($res_get_cnt);

            $sql_pay = "select sum(TotPrice) as all_money from tjd_pay_result where cash_prod_pay=0 and gwc_cont_pay=1 and buyer_id='$row_id[buyer_id]' and date>'$date_pre_month' and end_status='Y'";
            $res_pay = mysqli_query($self_con,$sql_pay);
            $row_pay = mysqli_fetch_array($res_pay);

            $possible_cnt = floor($row_pay[0] * 1 / 20000);
            if($possible_cnt < $row_get_cnt[0]){
                $unset_cnt = $row_get_cnt[0] * 1 - $possible_cnt;

                $sql_gwc = "select idx, ori_store_prod_idx from Gn_Iam_Contents_Gwc where mem_id='{$row_id[buyer_id]}' and ori_store_prod_idx!=0 order by idx asc limit {$unset_cnt}";
                $res_gwc = mysqli_query($self_con,$sql_gwc);
                while($row_gwc = mysqli_fetch_array($res_gwc)){
                    $sql_update = "update Gn_Iam_Contents_Gwc set public_display='Y' where idx = '$row_gwc[ori_store_prod_idx]'";
                    mysqli_query($self_con,$sql_update) or die(mysqli_error($self_con));

                    $sql_del = "delete from Gn_Iam_Contents_Gwc where idx=$row_gwc[idx]";
                    mysqli_query($self_con,$sql_del) or die(mysqli_error($self_con));
                }
            }
        }
    }
    echo 1;
}
else if($type == "gwc_sync_state"){
    $sql = "update Gn_Search_Key set key_content='{$sample_click}' where key_id='gwc_prod_sync_status'";
    mysqli_query($self_con,$sql);

    echo 1;
}
else if($type == "delete_change_list"){
    $id_arr = explode(",", $no);
    for($i = 0; $i < count($id_arr); $i++){
        $sql_update = "update Gn_Gwc_Order set prod_state=0, state_detail=NULL, prod_req_date=NULL, change_prod_req_date=NULL where id='$id_arr[$i]'";
        mysqli_query($self_con,$sql_update);
    }
    echo 1;
}
?>