<?php
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

extract($_POST);

// $round = $_POST['round'];
// echo $round; //exit;

if($_POST['get'] == true){
    $sql = "SELECT state_flag, mem_id FROM crawler_map_status_info ORDER BY id DESC LIMIT 1";
    $result1 = mysql_query($sql);
    while($res1 = mysql_fetch_array($result1)){
        $state = $res1['state_flag'];
        $mem_id = $res1['mem_id'];
    }

    echo '{"status":'.$state.', "mem_id":"'.$mem_id.'"}';
}
?>
