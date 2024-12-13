#!/usr/bin/php
<?php
include_once "/home/kiam/lib/db_config.php";
$pay_sql = "select key_content from Gn_Search_Key where key_id= 'group_card_point'";
$pay_res = mysqli_query($self_con, $pay_sql);
$pay_row = mysqli_fetch_array($pay_res);
$card_price = $pay_row[0];
$year = date("Y");
$month = date("m");
$last_day = 30;
if($month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12){
    $last_day = 31;
}else if($month == 2){
    $last_day = 28;
    if($year % 4 == 0){
        if($year % 100 != 0)
            $last_day = 29;
        else if($year % 400 == 0)
            $last_day = 29;
    }
}
$cur_day = date("d");
if($cur_day != $last_day){
    $query = "SELECT * FROM Gn_Iam_Name_Card WHERE group_id is not NULL  AND phone_display ='Y' AND req_data like '%-" . date("d") . " %' ORDER BY idx asc";
}else{
    if($last_day == 31) {
        $query = "SELECT * FROM Gn_Iam_Name_Card WHERE group_id is not NULL  AND phone_display ='Y' AND req_data like '%-" . date("d") . " %' ORDER BY idx asc";
    }else if($last_day == 30){
        $query = "SELECT * FROM Gn_Iam_Name_Card WHERE group_id is not NULL  AND phone_display ='Y' AND (req_data like '%-31 %' or req_data like '%-30 %') ORDER BY idx asc";
    }else if($last_day == 29) {
        $query = "SELECT * FROM Gn_Iam_Name_Card WHERE group_id is not NULL  AND phone_display ='Y' AND (req_data like '%-31 %' or req_data like '%-30 %' or req_data like '%-29 %') ORDER BY idx asc";
    }else if($last_day == 28) {
        $query = "SELECT * FROM Gn_Iam_Name_Card WHERE group_id is not NULL  AND phone_display ='Y' AND (req_data like '%-31 %' or req_data like '%-30 %' or req_data like '%-29 %' or req_data like '%-28 %') ORDER BY idx asc";
    }
}
$res = mysqli_query($self_con, $query) or die(mysqli_error($self_con));
while($row=mysqli_fetch_array($res)) {
    $mem_sql = "select mem_point,mem_phone,mem_name,mem_id,mem_code,mem_cash from Gn_Member where mem_id= '$row[mem_id]'";
    $mem_res = mysqli_query($self_con, $mem_sql);
    $mem_row = mysqli_fetch_array($mem_res);
    $pay_method = $mem_row['mem_id']."/".$mem_row['mem_name'];
    $group_link = "/iam/?".$row['card_short_url'].$mem_row['mem_code']."cur_win=group-con&gkind=".$row['group_id'];
    $item_name = "그룹카드/".$row['card_short_url'];
    if($mem_row[0] >= $card_price){
        $mem_point = $mem_row[0] - $card_price;
        $sql_buyer = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_row['mem_id']}',
                                                        buyer_tel='{$mem_row['mem_phone']}',
                                                        site='{$group_link}',
                                                        pay_method='{$pay_method}',
                                                        item_name = '{$item_name}',
                                                        item_price={$card_price},
                                                        pay_date=NOW(),
                                                        pay_status='Y',
                                                        pay_percent=90,
                                                        VACT_InputName='{$mem_row['mem_name']}',
                                                        point_val=1,
                                                        type='group_card',
                                                        current_point={$mem_point},
                                                        current_cash={$mem_row['mem_cash']}";
        $res_result = mysqli_query($self_con, $sql_buyer);
        $sql_update = "update Gn_Member set mem_point={$mem_point} where mem_id='{$row['mem_id']}'";
        $res_result = mysqli_query($self_con, $sql_update);
    }else{
        $sql_update = "update Gn_Iam_Name_Card set phone_display='N' where idx='{$row['idx']}'";
        $res_result = mysqli_query($self_con, $sql_update);
        $sql_update = "update Gn_Iam_Contents set public_display='N' where card_idx='{$row['idx']}'";
        $res_result = mysqli_query($self_con, $sql_update);
    }
}
exit;
?>
