<?php
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

extract($_POST);

if($_POST['get'] == true){

    $web_address = $_POST['address'];
    $contents_cnt = $_POST['contents_cnt'];
    $mem_id = $_POST['mem_id_status'];

    // $sql = "SELECT state_flag, mem_id FROM crawler_people_status_info WHERE mem_id='{$mem_id}' and web_link='{$web_address}' and contents_cnt={$contents_cnt} ORDER BY id DESC LIMIT 1";
    $sql = "SELECT state_flag, mem_id, keyword FROM crawler_status_info WHERE mem_id='{$mem_id}' and type='MAN' and web_link='{$web_address}' and contents_cnt={$contents_cnt} ORDER BY id DESC LIMIT 1";
    // echo $sql; exit;
    $result1 = mysqli_query($self_con,$sql);
    if(mysqli_num_rows($result1) == 0){
        echo '{"status":3, "mem_id":"'.$mem_id.'"}';
    }
    else{
        while($res1 = mysqli_fetch_array($result1)){
            $state = $res1['state_flag'];
            $mem_id = $res1['mem_id'];
            $keyword = $res1['keyword'];
            // $round_num = $res1['round_num'];
        }
        echo '{"status":'.$state.', "mem_id":"'.$mem_id.'", "keyword":"'.$keyword.'"}';
    }
}
?>
