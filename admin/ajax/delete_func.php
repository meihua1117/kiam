<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
if(!$admin){
    if($delete_name == "mypage_link_list"){
        $id_arr = explode(",", $id);
        for($i = 0; $i < count($id_arr); $i++){
            $sql_del = "delete from Gn_event where event_idx='{$id_arr[$i]}' and event_name_kor!='단체회원자동가입및아이엠카드생성' AND event_name_kor!='콜백메시지관리자설정동의' AND event_name_kor!='데일리문자세트자동생성'";
            mysqli_query($self_con,$sql_del);
        }
        echo 1;
        exit;
    }
    else if($delete_name == "mypage_reservation_list"){
        $id_arr = explode(",", $id);
        for($i = 0; $i < count($id_arr); $i++){
            $sql_del = "delete from Gn_event_sms_info where sms_idx='{$id_arr[$i]}' ";
            mysqli_query($self_con,$sql_del);
        }
        echo 1;
        exit;
    }
    else if($delete_name == "mypage_landing_list"){
        $id_arr = explode(",", $id);
        for($i = 0; $i < count($id_arr); $i++){
            $sql_del = "delete from Gn_landing where landing_idx='{$id_arr[$i]}' ";
            mysqli_query($self_con,$sql_del);
        }
        echo 1;
        exit;
    }
    else if($delete_name == "mypage_request_list"){
        //$sql_del = "delete from Gn_event_request where m_id ='{$mem_id}'";
        $id_arr = explode(",", $id);
        for($i = 0; $i < count($id_arr); $i++){
            $sql_del = "delete from Gn_event_request where request_idx='{$id_arr[$i]}' ";
            mysqli_query($self_con,$sql_del);
        }
        echo 1;
        exit;
    }
    else if($delete_name == "daily_list"){
        $id_arr = explode(",", $id);
        for($i = 0; $i < count($id_arr); $i++){
            $sql_del = "delete from Gn_daily where gd_id='{$id_arr[$i]}' ";
            mysqli_query($self_con,$sql_del);

            $query = "delete from Gn_daily_date where gd_id='{$id_arr[$i]}';";
            mysqli_query($self_con,$query);
                
            $query = "delete from Gn_MMS where gd_id='{$id_arr[$i]}' ";
            mysqli_query($self_con,$query);

            $query = "delete from gn_mail where gd_id='{$id_arr[$i]}' ";
            mysqli_query($self_con,$query);  
        }
        echo 1;
        exit;
    }
    else if($delete_name == "mms_del"){
        if($id){
            $id_arr = array();
            if(strpos($id, ",") !== false){
                $id_arr = explode(",", $id);
            }
            else{
                $id_arr[0] = $id;
            }

            for($i = 0; $i < count($id_arr); $i++){
                $sql_del = "delete from Gn_MMS where idx='{$id_arr[$i]}'";
                mysqli_query($self_con,$sql_del);
            }
        }
        else if($mem_id){
            if($type == 2){
                $search_str = " and (type=2 || type=3 || type=4)";
            }
            if($type == 4){
                $search_str = " and type=6";
            }
            if(!$type){
                $search_str = " and title != 'app_check_process' and type=1";
            }
            $sql_del = "delete from Gn_MMS where mem_id='{$mem_id}'".$search_str;
            mysqli_query($self_con,$sql_del);
        }
        echo 1;
        exit;
    }
    mysqli_query($self_con,$sql_del);
    echo 1;
    exit;
}
else{
    $id_arr = array();
    if(strpos($id, ",") !== false){
        $id_arr = explode(",", $id);
    }
    else{
        $id_arr[0] = $id;
    }
    
    for($i = 0; $i < count($id_arr); $i++){
        if($delete_name == "payment_item"){
            $sql_del = "delete from Gn_Item_Pay_Result where no={$id_arr[$i]}";
            mysqli_query($self_con,$sql_del);
        }
        else if($delete_name == "payment_per_list" || $delete_name == "payment_month_list"){
            $sql_del = "delete from tjd_pay_result where no={$id_arr[$i]}";
            mysqli_query($self_con,$sql_del);
        }
        else if($delete_name == "crawler_member_list"){
            $sql_del = "delete from crawler_member_real where cmid={$id_arr[$i]}";
            mysqli_query($self_con,$sql_del);
        }
        else if($delete_name == "member_list"){
            $sql_mem_id="select mem_id from Gn_Member where mem_code='$id_arr[$i]'";
            $res_mem_id = mysqli_query($self_con,$sql_mem_id);
            $row_mem_id = mysqli_fetch_array($res_mem_id);
            $sql_del_mem="delete from Gn_Member where mem_code='$id_arr[$i]'";
            mysqli_query($self_con,$sql_del_mem);
            $sql_del_mall="delete from Gn_Iam_Mall where card_idx = '$id_arr[$i]' and mall_type = 1";
            mysqli_query($self_con,$sql_del_mall) or die(mysqli_error($self_con));
        
            $sql_name_card="select * from Gn_Iam_Name_Card where mem_id='{$row_mem_id['mem_id']}'";
            $res_name_card = mysqli_query($self_con,$sql_name_card);
            while($row_name_card = mysqli_fetch_array($res_name_card)){
                $card_idx = $row_name_card[idx];
                $sql_del_mall1="delete from Gn_Iam_Mall where card_idx = '$card_idx' and mall_type = 2";
                mysqli_query($self_con,$sql_del_mall1) or die(mysqli_error($self_con));
            }
            $sql_del_card="delete from Gn_Iam_Name_Card where mem_id='{$row_mem_id['mem_id']}'";
            mysqli_query($self_con,$sql_del_card);
        
            $mall_sql = "select * from Gn_Iam_Contents where mem_id='{$row['mem_id']}'";
            $mall_res = mysqli_query($self_con,$mall_sql);
            while($mall_row = mysqli_fetch_array($mall_res)){
                $m_idx = $mall_row[idx];
                $m_sql="delete from Gn_Iam_Mall where card_idx = '$m_idx' and (mall_type = 3 or mall_type = 4)";
                mysqli_query($self_con,$m_sql) or die(mysqli_error($self_con));
            }
            $sql_contents="select * from Gn_Iam_Contents where mem_id='{$row_mem_id['mem_id']}'";
            $res_contents = mysqli_query($self_con,$sql_contents);
            while($row_contents = mysqli_fetch_array($res_contents)){
                $content_idx = $row_contents[idx];
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
            // echo 1;
            // exit;
        }
        else if($delete_name == "service_list"){
            $sql_del = "delete from Gn_Service where idx={$id_arr[$i]}";
            mysqli_query($self_con,$sql_del);
        }
        else if($delete_name == "iam_service_list"){
            $sql_del = "delete from Gn_Iam_Service where idx={$id_arr[$i]}";
            mysqli_query($self_con,$sql_del);
        }
        else if($delete_name == "member_point_manage_del"){
            $sql_del = "delete from Gn_Item_Pay_Result where no={$id_arr[$i]}";
            mysqli_query($self_con,$sql_del);
        }
        else if($delete_name == "point_manage_list_cash_del"){
            $sql_del = "delete from tjd_pay_result where no={$id_arr[$i]}";
            mysqli_query($self_con,$sql_del);
        }
        // echo $sql_del; exit;
    }
    echo 1;
    exit;
}
?>