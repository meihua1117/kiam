<?
set_time_limit(0);
ini_set('memory_limit','-1');
include_once "../lib/rlatjd_fun.php";
$sqlcard = "SELECT mem_id, idx FROM Gn_Iam_Name_Card WHERE req_data>'2023-01-14 00:00:00'";
$rescard = mysql_query($sqlcard);
while($rowcard = mysql_fetch_array($rescard)){
    $sql_chk = "select mem_code from Gn_Member where mem_id='{$rowcard['mem_id']}'";
    $res_chk = mysql_query($sql_chk);
    $cnt = mysql_num_rows($res_chk);
    if(!$cnt){
        $sql_del = "delete from Gn_Iam_Name_Card where mem_id='{$rowcard['mem_id']}'";
        mysql_query($sql_del);
        $sql_del_con = "delete from Gn_Iam_Contents where card_idx='{$rowcard['idx']}'";
        mysql_query($sql_del_con);
    }
}
?>