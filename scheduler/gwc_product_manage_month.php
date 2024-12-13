#!/usr/bin/php
<?php
include_once "/home/kiam/lib/db_config.php";

$headers = array('Accept: application/json, text/plain, */*', 'Cache_Control: no-cache', 'content-type:mutipart/form-data;');
date_default_timezone_set('Asia/Seoul');
echo "data::".date("Y-m-d H:i:s")."\n";
$s = 0;
$sql_mem_auto = "select mem_id from Gn_Gwc_Order where pay_status='Y' and page_type=0 group by mem_id";
$res_mem_auto = mysqli_query($self_con,$sql_mem_auto);
while($row_mem_auto = mysqli_fetch_array($res_mem_auto)){
    // $date_this_month = date("Y-m")."-01 00:00:00";
    $date_today = date("Y-m-d");
    $prev_month_ts = strtotime($date_today.' -1 month');
    $cur_time = time();
    $date_this_month = date("Y-m", $cur_time)."-01 00:00:00";
    $date_pre_month = date("Y-m", $prev_month_ts)."-01 00:00:00";

    //$sql_this_month_pay = "select SUM(TotPrice) as money from tjd_pay_result where cash_prod_pay=0 and gwc_cont_pay=1 and buyer_id='{$row_mem_auto[mem_id]}' and end_status='Y' and date>'{$date_this_month}'";//당월 구매금액
    $sql_this_month_pay = "select SUM(TotPrice) as money from tjd_pay_result_gwc where cash_prod_pay=0 and buyer_id='{$row_mem_auto['mem_id']}' and end_status='Y' and date>'{$date_this_month}'";//당월 구매금액
    $res_this_month_pay = mysqli_query($self_con,$sql_this_month_pay);
    $row_this_month_pay = mysqli_fetch_array($res_this_month_pay);
    
    $this_month_pay = $row_this_month_pay[0]?$row_this_month_pay[0]:0;
    $cnt_all_prod_this = floor($this_month_pay * 1 / 20000);//당월 가져오기 가능 전체건수

    $sql_get_prod_this = "select count(*) from Gn_Iam_Contents_Gwc where mem_id='{$row_mem_auto['mem_id']}' and ori_store_prod_idx!=0 and req_data>'{$date_this_month}'";
    $res_get_prod_this = mysqli_query($self_con,$sql_get_prod_this);
    $row_get_prod_this = mysqli_fetch_array($res_get_prod_this);//당월 가져오기한 총 건수

    //$sql_pre_month_pay = "select SUM(TotPrice) as money from tjd_pay_result where cash_prod_pay=0 and gwc_cont_pay=1 and buyer_id='{$row_mem_auto[mem_id]}' and end_status='Y' and date>'{$date_pre_month}' and date<'{$date_this_month}'";//전달 구매금액
    $sql_pre_month_pay = "select SUM(TotPrice) as money from tjd_pay_result_gwc where cash_prod_pay=0 and buyer_id='{$row_mem_auto['mem_id']}' and end_status='Y' and date>'{$date_pre_month}' and date<'{$date_this_month}'";//전달 구매금액
    $res_pre_month_pay = mysqli_query($self_con,$sql_pre_month_pay);
    $row_pre_month_pay = mysqli_fetch_array($res_pre_month_pay);
    
    $pre_month_pay = $row_pre_month_pay[0]?$row_pre_month_pay[0]:0;
    $cnt_all_prod_pre = floor($pre_month_pay * 1 / 20000);//전달 가져오기 가능 전체건수

    $sql_get_prod_pre = "select count(*) from Gn_Iam_Contents_Gwc where mem_id='{$row_mem_auto['mem_id']}' and ori_store_prod_idx <> 0 and req_data>'{$date_pre_month}' and req_data<'{$date_this_month}'";
    $res_get_prod_pre = mysqli_query($self_con,$sql_get_prod_pre);
    $row_get_prod_pre = mysqli_fetch_array($res_get_prod_pre);//전달 가져오기한 총 건수

    $sql_get_prod = "select count(*) from Gn_Iam_Contents_Gwc where mem_id='{$row_mem_auto['mem_id']}' and ori_store_prod_idx <> 0";
    $res_get_prod = mysqli_query($self_con,$sql_get_prod);
    $row_get_prod = mysqli_fetch_array($res_get_prod);//현재까지 가져오기한 총 건수

    $all_possible_cnt = $cnt_all_prod_pre * 1 + $cnt_all_prod_this * 1;//전월+당월 가져오기가능건수
    $all_already_cnt = $row_get_prod_pre[0] * 1 + $row_get_prod_this[0] * 1;//전월+당월 가져온건수
    $rest_cnt = $all_possible_cnt - $all_already_cnt;

    if($rest_cnt < 0){
        $cnt_unset = abs($rest_cnt);
        $sql_gwc = "select idx, ori_store_prod_idx from Gn_Iam_Contents_Gwc where mem_id='{$row_mem_auto['mem_id']}' and ori_store_prod_idx!=0 order by idx asc limit {$cnt_unset}";
        $res_gwc = mysqli_query($self_con,$sql_gwc);
        while($row_gwc = mysqli_fetch_array($res_gwc)){
            $sql_update = "update Gn_Iam_Contents_Gwc set public_display='Y' where idx = '{$row_gwc['ori_store_prod_idx']}'";
            mysqli_query($self_con,$sql_update) or die(mysqli_error($self_con));

            $sql_del = "delete from Gn_Iam_Contents_Gwc where idx={$row_gwc['idx']}";
            mysqli_query($self_con,$sql_del) or die(mysqli_error($self_con));

            echo "mem_id>>".$row_mem_auto['mem_id'].">>cont_idx_unset>>".$row_gwc['ori_store_prod_idx']."\n";
        }
    }
}
?>