<?
set_time_limit(0);
ini_set('memory_limit','-1');
include_once "../lib/rlatjd_fun.php";

$sql_card = "select id, contents_idx, tjd_idx from Gn_Gwc_Order where page_type=0 order by id desc";
$res_card = mysqli_query($self_con, $sql_card);
while($row_card = mysqli_fetch_array($res_card)){
    $sql_insert = "select member_type from tjd_pay_result where no='{$row_card['tjd_idx']}'";
    $res_insert = mysqli_query($self_con, $sql_insert);
    while($row_insert = mysqli_fetch_array($res_insert)){
        $sql_idx = "select idx from Gn_Iam_Contents_Gwc where contents_title = '{$row_insert['member_type']}' and public_display='Y'";
        $res_idx = mysqli_query($self_con, $sql_idx);
        $row_idx = mysqli_fetch_array($res_idx);
        if($row_idx['idx']){
            $sql_update = "update Gn_Gwc_Order set contents_idx='{$row_idx['idx']}' where id='{$row_card['id']}'";
            mysqli_query($self_con, $sql_update);
        }
    }
}
?>