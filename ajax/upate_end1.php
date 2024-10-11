<?
set_time_limit(0);
ini_set('memory_limit','-1');
include_once "../lib/rlatjd_fun.php";
$sql_card_data = "select idx, mem_id from Gn_Iam_Name_Card where admin_shopping!=0 GROUP BY mem_id";
$res_card_data = mysqli_query($self_con,$sql_card_data);
while($row_card_data = mysqli_fetch_array($res_card_data)){
    $sql_del = "delete from Gn_Member where mem_id='{$row_card_data[mem_id]}'";
    mysqli_query($self_con,$sql_del);
    $sql_del_contents = "delete from Gn_Iam_Contents where card_idx='{$row_card_data[idx]}'";
    mysqli_query($self_con,$sql_del_contents);
}
$sql_del = "delete from Gn_Iam_Name_Card where admin_shopping!=0";
mysqli_query($self_con,$sql_del);
?>