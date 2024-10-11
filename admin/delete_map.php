<?php
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

extract($_POST);
if(isset($_POST['url'])){
    $sql_idx = "select idx from Gn_Iam_Name_Card where card_short_url='{$_POST['url']}'";
    $res_idx = mysqli_query($self_con,$sql_idx);
    $row_idx = mysqli_fetch_array($res_idx);
    $idx = $row_idx['idx'];
    
    //$sql_update = "update Gn_Iam_Name_Card set next_iam_link = '' where next_iam_link like '%".$_POST['url']."%'";
    //mysqli_query($self_con,$sql_update);

    $sql_card = "delete from Gn_Iam_Name_Card where card_short_url='{$_POST['url']}'";
    mysqli_query($self_con,$sql_card);

    $sql="delete from Gn_Iam_Mall where card_idx = '$idx' and mall_type = 2";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

    $mall_sql = "select * from Gn_Iam_Contents where card_idx={$idx}";
    $mall_res = mysqli_query($self_con,$mall_sql);
    while($mall_row = mysqli_fetch_array($mall_res)){
        $m_idx = $mall_row[idx];
        $m_sql="delete from Gn_Iam_Mall where card_idx = '$m_idx' and (mall_type = 3 or mall_type = 4)";
        mysqli_query($self_con,$m_sql) or die(mysqli_error($self_con));
    }

    $sql = "delete from Gn_Iam_Contents where card_idx=$idx";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

    $sql_contents = "delete from Gn_Iam_Con_Card where main_card={$idx}";
    mysqli_query($self_con,$sql_contents);

    $sql_contents = "delete from Gn_Iam_Con_Card where card_idx={$idx}";
    mysqli_query($self_con,$sql_contents);

    $sql_info_id = "select id from cralwer_map_info where iam_link='{$_POST['url']}'";
    $res_info = mysqli_query($self_con,$sql_info_id);
    $row_id = mysqli_fetch_array($res_info);
    $id = $row_id['id'];

    $sql_crawler_map_info = "delete from crawler_map_info where iam_link='{$_POST['url']}'";
    mysqli_query($self_con,$sql_crawler_map_info);

    $sql_crawler_contents_info = "delete from crawler_map_contents_info where info_id={$id}";
    mysqli_query($self_con,$sql_crawler_contents_info);

    echo 1;
}
?>
