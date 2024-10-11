<?php
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

extract($_POST);

$round = $_POST['round'];
// echo $round; //exit;

if($_POST['get'] == true){

    $sql_count = "SELECT COUNT(round_num) as cnt from crawler_gm_seller_info_ad where round_num={$round}";
    // echo $sql_count; exit;
    $result = mysqli_query($self_con,$sql_count);
    if(mysqli_num_rows($result) > 0){
        $res = mysqli_fetch_array($result);
        $market = $res['cnt'];
    }
    else{
        $market = 0;
    }

    $sql = "SELECT state_flag, round_num FROM crawler_gm_status_info_ad ORDER BY id DESC LIMIT 1";
    $result1 = mysqli_query($self_con,$sql);
    while($res1 = mysqli_fetch_array($result1)){
        $state = $res1['state_flag'];
        $round_num = $res1['round_num'];
    }

    echo '{"status":'.$state.', "count":'.$market.', "round_num":'.$round_num.'}';
}
?>
