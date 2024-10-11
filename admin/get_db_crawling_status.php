<?php
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

extract($_POST);

// $round = $_POST['round'];
// echo $round; //exit;

if($_POST['get'] == true){
    $web_address = $_POST['address'];
    $contents_cnt = $_POST['contents_cnt'];
    $mem_id = $_POST['mem_id_status'];

    // $sql = "SELECT state_flag, mem_id FROM crawler_gm_status_info where mem_id='{$mem_id}' and market_count={$contents_cnt} ORDER BY id DESC LIMIT 1";
    $sql = "SELECT state_flag, mem_id FROM crawler_status_info where mem_id='{$mem_id}' and type='GMARKET' and contents_cnt={$contents_cnt} ORDER BY id DESC LIMIT 1";
    $result1 = mysqli_query($self_con,$sql);
    while($res1 = mysqli_fetch_array($result1)){
        $state = $res1['state_flag'];
        $mem_id = $res1['mem_id'];
    }

    echo '{"status":'.$state.', "mem_id":"'.$mem_id.'"}';
}
?>
