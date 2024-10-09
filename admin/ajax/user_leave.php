<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 탈퇴
*/
extract($_POST);
if($type == "logout"){
    $sql="update Gn_Member set is_leave='Y', leave_txt='관리자 직권 탈퇴' where mem_code='$mem_code'";
    mysql_query($sql);	
}else if($type == "del"){
    $sql_mem_id="select mem_id from Gn_Member where mem_code='$mem_code'";
    $res_mem_id = mysql_query($sql_mem_id);
    $row_mem_id = mysql_fetch_array($res_mem_id);
    $sql_del_mem="delete from Gn_Member where mem_code='$mem_code'";
    mysql_query($sql_del_mem);
    $sql_del_mall="delete from Gn_Iam_Mall where card_idx = '$mem_code' and mall_type = 1";
    mysql_query($sql_del_mall) or die(mysql_error());

    $sql_name_card="select * from Gn_Iam_Name_Card where mem_id='$row_mem_id[mem_id]'";
    $res_name_card = mysql_query($sql_name_card);
    while($row_name_card = mysql_fetch_array($res_name_card)){
        $card_idx = $row_name_card[idx];
        $sql_del_mall1="delete from Gn_Iam_Mall where card_idx = '$card_idx' and mall_type = 2";
        mysql_query($sql_del_mall1) or die(mysql_error());
    }
    $sql_del_card="delete from Gn_Iam_Name_Card where mem_id='$row_mem_id[mem_id]'";
    mysql_query($sql_del_card);

    $mall_sql = "select * from Gn_Iam_Contents where mem_id='$row[mem_id]'";
    $mall_res = mysql_query($mall_sql);
    while($mall_row = mysql_fetch_array($mall_res)){
        $m_idx = $mall_row[idx];
        $m_sql="delete from Gn_Iam_Mall where card_idx = '$m_idx' and (mall_type = 3 or mall_type = 4)";
        mysql_query($m_sql) or die(mysql_error());
    }
    $sql_contents="select * from Gn_Iam_Contents where mem_id='$row_mem_id[mem_id]'";
    $res_contents = mysql_query($sql_contents);
    while($row_contents = mysql_fetch_array($res_contents)){
        $content_idx = $row_contents[idx];
        $sql_del_mall2="delete from Gn_Iam_Mall where card_idx = '$content_idx' and (mall_type = 3 or mall_type = 4)";
        mysql_query($sql_del_mall2) or die(mysql_error());
    }
    $sql="select idx from Gn_Iam_Contents where mem_id='$row_mem_id[mem_id]'";
    $res = mysql_query($sql);
    while($row = mysql_fetch_array($res)){
        $sql = "delete from Gn_Iam_Con_Card where cont_idx = $row[idx]";
        mysql_query($sql);
    }
    $sql_del_contents="delete from Gn_Iam_Contents where mem_id='$row_mem_id[mem_id]'";
    mysql_query($sql_del_contents);
    $sql_del_grp="delete from Gn_MMS_Group where mem_id='$row_mem_id[mem_id]'";
    mysql_query($sql_del_grp);
    $sql_del_recv="delete from Gn_MMS_Receive where mem_id='$row_mem_id[mem_id]'";
    mysql_query($sql_del_recv);
    $sql_recv_iam="delete from Gn_MMS_Receive_Iam where mem_id='$row_mem_id[mem_id]'";
    mysql_query($sql_recv_iam);
    $sql_recv_iam="delete from gn_iam_contacts where mem_id='$row_mem_id[mem_id]' or contact_id='$row_mem_id[mem_id]'";
    mysql_query($sql_recv_iam);

}
echo "{\"result\":\"$result\"}";
?>