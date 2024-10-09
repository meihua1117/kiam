<?php
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

extract($_POST);

// $round = $_POST['round'];
// echo $round; //exit;

if(isset($_POST['url'])){
    $sql_idx = "select idx from Gn_Iam_Name_Card where card_short_url='{$_POST['url']}'";
    $res_idx = mysql_query($sql_idx);
    $row_idx = mysql_fetch_array($res_idx);
    $idx = $row_idx['idx'];

    //$sql_update = "update Gn_Iam_Name_Card set next_iam_link = '' where next_iam_link like '%".$_POST['url']."%'";
    //mysql_query($sql_update);
    
    $sql_card = "delete from Gn_Iam_Name_Card where card_short_url='{$_POST['url']}'";
    mysql_query($sql_card);

    $mall_sql = "select * from Gn_Iam_Contents where card_idx={$idx}";
    $mall_res = mysql_query($mall_sql);
    while($mall_row = mysql_fetch_array($mall_res)){
        $m_idx = $mall_row[idx];
        $m_sql="delete from Gn_Iam_Mall where card_idx = '$m_idx' and (mall_type = 3 or mall_type = 4)";
        mysql_query($m_sql) or die(mysql_error());
    }

    $sql = "delete from Gn_Iam_Contents where card_idx=$idx";
    mysql_query($sql) or die(mysql_error());

    $sql_contents = "delete from Gn_Iam_Con_Card where main_card={$idx}";
    mysql_query($sql_contents);

    $sql_contents = "delete from Gn_Iam_Con_Card where card_idx={$idx}";
    mysql_query($sql_contents);

    $sql="delete from Gn_Iam_Mall where card_idx = '$idx' and mall_type = 2";
    mysql_query($sql) or die(mysql_error());

    $sql_info_id = "select id from crawler_iam_info where iam_link='{$_POST['url']}' and web_type='{$type}'";
    $res_info = mysql_query($sql_info_id);
    $row_id = mysql_fetch_array($res_info);
    $id = $row_id['id'];

    $sql_crawler_iam_info = "delete from crawler_iam_info where iam_link='{$_POST['url']}' and web_type='{$type}'";
    mysql_query($sql_crawler_iam_info);

    $sql_crawler_contents_info = "delete from crawler_people_contents_info where info_id={$id}";
    mysql_query($sql_crawler_contents_info);

    echo 1;
}
?>
