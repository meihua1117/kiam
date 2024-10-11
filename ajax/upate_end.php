<?
set_time_limit(0);
ini_set('memory_limit','-1');
include_once "../lib/rlatjd_fun.php";

$sql_card = "select id, contents_idx from Gn_Gwc_Order order by id desc";
$res_card = mysqli_query($self_con,$sql_card);
while($row_card = mysqli_fetch_array($res_card)){
    $sql_provide = "select send_provide_price from Gn_Iam_Contents_Gwc where idx='{$row_card[contents_idx]}'";
    $res_provide = mysqli_query($self_con,$sql_provide);
    $row_provide = mysqli_fetch_array($res_provide);

    $sql_update = "update Gn_Gwc_Order set contents_provide_price='{$row_provide[0]}' where id='{$row_card[id]}'";
    mysqli_query($self_con,$sql_update);
}
?>