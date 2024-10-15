<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 탈퇴
*/
extract($_POST);
if($type == "logout"){
    $sql="update Gn_Member set is_leave='Y', leave_txt='관리자 직권 탈퇴' where mem_code='$mem_code'";
    mysqli_query($self_con,$sql);	
}else if($type == "del"){
    $sql_mem_id="select mem_id from Gn_Member where mem_code='$mem_code'";
    $res_mem_id = mysqli_query($self_con,$sql_mem_id);
    $row_mem_id = mysqli_fetch_array($res_mem_id);
    $sql_del_mem="delete from Gn_Member where mem_code='$mem_code'";
    mysqli_query($self_con,$sql_del_mem);
    $sql_del_mall="delete from Gn_Iam_Mall where card_idx = '$mem_code' and mall_type = 1";
    mysqli_query($self_con,$sql_del_mall) or die(mysqli_error($self_con));

    $sql_name_card="select * from Gn_Iam_Name_Card where mem_id='{$row_mem_id['mem_id']}'";
    $res_name_card = mysqli_query($self_con,$sql_name_card);
    while($row_name_card = mysqli_fetch_array($res_name_card)){
        $card_idx = $row_name_card['idx'];
        $sql_del_mall1="delete from Gn_Iam_Mall where card_idx = '$card_idx' and mall_type = 2";
        mysqli_query($self_con,$sql_del_mall1) or die(mysqli_error($self_con));
    }
    $sql_del_card="delete from Gn_Iam_Name_Card where mem_id='{$row_mem_id['mem_id']}'";
    mysqli_query($self_con,$sql_del_card);

    $mall_sql = "select * from Gn_Iam_Contents where mem_id='{$row['mem_id']}'";
    $mall_res = mysqli_query($self_con,$mall_sql);
    while($mall_row = mysqli_fetch_array($mall_res)){
        $m_idx = $mall_row['idx'];
        $m_sql="delete from Gn_Iam_Mall where card_idx = '$m_idx' and (mall_type = 3 or mall_type = 4)";
        mysqli_query($self_con,$m_sql) or die(mysqli_error($self_con));
    }
    $sql_contents="select * from Gn_Iam_Contents where mem_id='{$row_mem_id['mem_id']}'";
    $res_contents = mysqli_query($self_con,$sql_contents);
    while($row_contents = mysqli_fetch_array($res_contents)){
        $content_idx = $row_contents['idx'];
        $sql_del_mall2="delete from Gn_Iam_Mall where card_idx = '$content_idx' and (mall_type = 3 or mall_type = 4)";
        mysqli_query($self_con,$sql_del_mall2) or die(mysqli_error($self_con));
    }
    $sql="select idx from Gn_Iam_Contents where mem_id='{$row_mem_id['mem_id']}'";
    $res = mysqli_query($self_con,$sql);
    while($row = mysqli_fetch_array($res)){
        $sql = "delete from Gn_Iam_Con_Card where cont_idx = {$row['idx']}";
        mysqli_query($self_con,$sql);
    }
    $sql_del_contents="delete from Gn_Iam_Contents where mem_id='{$row_mem_id['mem_id']}'";
    mysqli_query($self_con,$sql_del_contents);
    $sql_del_grp="delete from Gn_MMS_Group where mem_id='{$row_mem_id['mem_id']}'";
    mysqli_query($self_con,$sql_del_grp);
    $sql_del_recv="delete from Gn_MMS_Receive where mem_id='{$row_mem_id['mem_id']}'";
    mysqli_query($self_con,$sql_del_recv);
    $sql_recv_iam="delete from Gn_MMS_Receive_Iam where mem_id='{$row_mem_id['mem_id']}'";
    mysqli_query($self_con,$sql_recv_iam);
    $sql_recv_iam="delete from gn_iam_contacts where mem_id='{$row_mem_id['mem_id']}' or contact_id='{$row_mem_id['mem_id']}'";
    mysqli_query($self_con,$sql_recv_iam);

}
echo json_encode(array("result"=>$result));
?>