<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/

$cur_time = date("Y-m-d H:i:s");
$cur_time1 = date("YmdHis");
extract($_POST);

if($delete){
    $sql_card = "select idx from Gn_Iam_Name_Card where admin_shopping='{$set_idx}'";
    $res_card = mysqli_query($self_con,$sql_card);
    $row_card = mysqli_fetch_array($res_card);

    $del_conts = "delete from Gn_Iam_Contents where card_idx={$row_card[idx]}";
    mysqli_query($self_con,$del_conts);

    $sql_contents = "delete from Gn_Iam_Con_Card where main_card={$row_card[idx]}";
    mysqli_query($self_con,$sql_contents);

    $sql_contents = "delete from Gn_Iam_Con_Card where card_idx={$row_card[idx]}";
    mysqli_query($self_con,$sql_contents);

    $del_card = "delete from Gn_Iam_Name_Card where admin_shopping='{$set_idx}'";
    $res = mysqli_query($self_con,$del_card);

    $del_set = "delete from crawler_shop_admin where id='{$set_idx}'";
    $res = mysqli_query($self_con,$del_set);

    echo $res;
}
else{
    if(strpos($reg_search_addr,',') !== false)
    {
        $search_addrs = explode(',', $reg_search_addr);
        $filterd = array_filter($search_addrs, 'strlen');
        $search_addrs = array_values($filterd);

        $set_idxs =  array();
        for($i = 0; $i < count($search_addrs); $i++)
        {
            $search_addr =  $search_addrs[$i];
            
            $sql_reg = "insert into crawler_shop_admin set web_type='{$web_type}', reg_title='{$reg_title}', reg_search_busi_type='$reg_search_busi_type', reg_search_busi='{$reg_search_busi}', reg_search_addr='{$search_addr}', reg_search_keyword='{$reg_search_keyword}', reg_cnt='{$reg_cnt}', reg_con_cnt='{$reg_con_cnt}', reg_auto_data={$auto}, reg_auto_day='{$reg_auto_day}', reg_auto_time='{$reg_auto_time}', reg_memo='{$reg_memo}', reg_date='{$reg_date}', iamstore_link='{$iamstore_link}'";
            $res = mysqli_query($self_con,$sql_reg);
            
            $set_idx = mysqli_insert_id($self_con);
            array_push($set_idxs, $set_idx);
        }

        echo '{"status":'.$res.',"set_id":"'.$set_idxs[0].'", "set_ids":"'.implode(',',$set_idxs).'", "reg_search_addr":"'.implode(',',$search_addrs).'"}';
    }
    else {
        $sql_reg = "insert into crawler_shop_admin set web_type='{$web_type}', reg_title='{$reg_title}', reg_search_busi_type='$reg_search_busi_type', reg_search_busi='{$reg_search_busi}', reg_search_addr='{$reg_search_addr}', reg_search_keyword='{$reg_search_keyword}', reg_cnt='{$reg_cnt}', reg_con_cnt='{$reg_con_cnt}', reg_auto_data={$auto}, reg_auto_day='{$reg_auto_day}', reg_auto_time='{$reg_auto_time}', reg_memo='{$reg_memo}', reg_date='{$reg_date}', iamstore_link='{$iamstore_link}'";
        $res = mysqli_query($self_con,$sql_reg);
        
        $set_idx = mysqli_insert_id($self_con);
        
        echo '{"status":'.$res.',"set_id":"'.$set_idx.'", "set_ids":"'.$set_idx.'", "reg_search_addr":"'.$reg_search_addr.'"}';
    }
}
?>