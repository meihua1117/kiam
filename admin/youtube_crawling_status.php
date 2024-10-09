<?php
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

extract($_POST);

// $round = $_POST['round'];
// echo $round; //exit;

if($_POST['get'] == true){
    $contents_cnt = $_POST['contents_cnt'];
    $mem_id = $_POST['mem_id_status'];
    $address = $_POST['address'];

    // $sql = "SELECT state_flag, mem_id FROM crawler_youtube_status_info where mem_id='{$mem_id}' and web_link='{$address}' and contents_cnt={$contents_cnt} ORDER BY id DESC LIMIT 1";
    $sql = "SELECT state_flag, mem_id FROM crawler_status_info where mem_id='{$mem_id}' and type='YOUTUBE' and web_link='{$address}' and contents_cnt={$contents_cnt} ORDER BY id DESC LIMIT 1";
    $result1 = mysql_query($sql);
    if(mysql_num_rows($result1) == 0){
        echo '{"status":3, "mem_id":"'.$mem_id.'"}';
    }
    else{
        while($res1 = mysql_fetch_array($result1)){
            $state = $res1['state_flag'];
            $mem_id = $res1['mem_id'];
            // $round_num = $res1['round_num'];
        }
        echo '{"status":'.$state.', "mem_id":"'.$mem_id.'"}';
    }
}
?>
