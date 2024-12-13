#!/usr/bin/php
<?php

include_once "/home/kiam/lib/db_config.php";

$currentDateTime = date('Ymd');

$sql_sel = "select * from crawler_shop_update where res_crawler=0 and sync_date='{$currentDateTime}' and type=1";
$res_sel = mysqli_query($self_con, $sql_sel);
while($row_sel = mysqli_fetch_array($res_sel)){
    $cur_cnt = $row_sel['get_cnt'];
    $all_cnt = $row_sel['all_cnt'];
    $cate_name = $row_sel['cate_name'];
    $page_cnt = $row_sel['page_num'];

    $all_page = ceil($all_cnt * 1 / 40);
    
    if($all_page > $page_cnt){
        echo $cate_name.">>update_restart!!".date("Y-m-d H:i:s")."\n";
        $sql_update = "update crawler_shop_admin set res_crawler=1 where sync_date='{$currentDateTime}' and cate_name='{$cate_name}'";
        mysqli_query($self_con, $sql_update);

        $fields['sync_data_gwc'] = "Y";
        $fields['sync_date'] = $currentDateTime;
        $fields['cate_name'] = $cate_name;
        $url = "https://www.goodhow.com/crawler/crawler/index_iamshop_sync.php";
        // $fields = json_encode ($fields);

        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, true );
        // curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query($fields) );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0); 
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec ( $ch );
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close ( $ch );
    }
}
mysqli_close($self_con);
?>