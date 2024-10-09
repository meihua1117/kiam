<?php
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

extract($_POST);

if($_POST['get'] == true){

    $set_idx = $_POST['set_idx'];

    $sql = "SELECT crawler_status, res_crawler, reg_con_cnt, reg_title, iamstore_page_cnt FROM crawler_shop_admin WHERE id='{$set_idx}'";
    $result1 = mysql_query($sql);
    if(mysql_num_rows($result1) == 0){
        echo '{"status":3, "mem_id":"'.$mem_id.'"}';
    }
    else{
        while($res1 = mysql_fetch_array($result1)){
            $state = $res1['crawler_status'];
            $cur_cnt = $res1['res_crawler'];
            $all_cnt = $res1['reg_con_cnt'];
            $cate_name = $res1['reg_title'];
            $page_cnt = $res1['iamstore_page_cnt'];

            $all_page = ceil($all_cnt * 1 / 40);
            $restart = 0;
            
            if(!$state && $all_page > $page_cnt){
                $sql_update = "update crawler_shop_admin set crawler_status=1 where id='{$set_idx}'";
                mysql_query($sql_update);
                $restart = 1;
            }
        }
        echo '{"status":'.$state.', "cur_cnt":"'.$cur_cnt.'", "all_cnt":"'.$all_cnt.'", "restart":"'.$restart.'"}';
    }
}
?>
