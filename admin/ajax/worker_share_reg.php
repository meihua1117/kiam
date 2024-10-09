<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 레벨 수정
*/
extract($_POST);
extract($_GET);

$date_today = date("Y-m-d H:i:s");

if($mode == "worker_share_reg") {
    if(strpos($card_idx, ",") !== false){
        $idx_arr = explode(",", $card_idx);
        for($i = 0; $i < count($idx_arr); $i++){
            $sql_reg_share = "update Gn_Iam_Name_Card set worker_service_state={$worker_service_state} where idx={$idx_arr[$i]}";
            $res = mysql_query($sql_reg_share);
        }
    }
    else{
        $sql_reg_share = "update Gn_Iam_Name_Card set worker_service_state={$worker_service_state} where idx={$card_idx}";
        $res = mysql_query($sql_reg_share);
    }
    echo $res;
}
if($mode == "reg_req_id"){
    $sql_chk = "select count(a.mem_code) as cnt from Gn_Member a inner join Gn_Iam_Service b on a.mem_id=b.mem_id where a.service_type>=2 and a.mem_id='{$req_id}'";
    $res_chk = mysql_query($sql_chk);
    $row_chk = mysql_fetch_array($res_chk);

    if(!$row_chk[0]){
        echo 0;
    }
    else{
        $sql_chk_cnt = "select count(*) as cnt from Gn_Iam_Name_Card where req_worker_id='{$req_id}' and org_use_state=0 and worker_service_state=1";
        $res_chk_cnt = mysql_query($sql_chk_cnt);
        $row_chk_cnt = mysql_fetch_array($res_chk_cnt);
        $cnt = $row_chk_cnt[0];

        if($cnt >= 5){
            echo 2;
            exit;
        }

        $sql_update_req_id = "update Gn_Iam_Name_Card set req_worker_id='{$req_id}', req_worker_date='{$date_today}' where idx={$card_idx}";
        $res = mysql_query($sql_update_req_id);
        echo $res;
    }
}
if($mode == "cancel_req_id"){
    $sql_cancel = "update Gn_Iam_Name_Card set req_worker_id='', req_worker_date='' where idx={$card_idx}";
    $res = mysql_query($sql_cancel);

    echo $res;
}
if($mode == "check_info_exist"){
    $sql_mem_data = "select mem_phone, mem_add1, bank_name, bank_owner, bank_account, mem_email from Gn_Member where mem_id='{$mem_id}'";
    $res_mem_data = mysql_query($sql_mem_data);
    $row_mem_data = mysql_fetch_array($res_mem_data);

    echo '{"mem_phone":"'.$row_mem_data[mem_phone].'", "mem_add1":"'.$row_mem_data[mem_add1].'", "bank_name":"'.$row_mem_data[bank_name].'", "bank_owner":"'.$row_mem_data[bank_owner].'", "bank_account":"'.$row_mem_data[bank_account].'", "mem_email":"'.$row_mem_data[mem_email].'"}';
}
if($mode == "update_member_info"){
    $sql_req_data = "select site, site_iam from Gn_Member where mem_id='{$req_id}'";
    $res_req_data = mysql_query($sql_req_data);
    $row_req_data = mysql_fetch_array($res_req_data);

    $sql_card_update = "update Gn_Iam_Name_Card set org_use_state=1 where idx={$card_idx}";
    mysql_query($sql_card_update);

    $sql_update = "update Gn_Member set mem_phone='{$mem_phone}', mem_email='{$mem_email}', mem_add1='{$mem_add1}', bank_name='{$bank_name}', bank_owner='{$bank_owner}', bank_account='{$bank_account}', site='{$row_req_data[site]}', site_iam='{$row_req_data[site_iam]}', recommend_id='{$req_id}' where mem_id='{$mem_id}'";

    $res = mysql_query($sql_update);
    echo $res;
}
if($mode == "uncheck_use_org"){
    $sql_card_update = "update Gn_Iam_Name_Card set org_use_state=0, req_worker_date='{$date_today}' where idx={$card_idx}";
    mysql_query($sql_card_update);

    $sql_mem_update = "update Gn_Member set site='kiam', site_iam='kiam', recommend_id='obmms02' where mem_id='{$mem_id}'";
    $res = mysql_query($sql_mem_update);

    echo $res;
}
exit;

?>