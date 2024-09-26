<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
date_default_timezone_set('Asia/Seoul');
extract($_POST);
// 오늘날짜
$date_today=date("Y-m-d");
$cur_time1 = date("Y-m-d H:i:s");

if(isset($_POST['my_id'])){
    $mem_id = $_POST['my_id'];
    $address = $_POST['address'];
    $contents_keyword = $_POST['contents_keyword'];
    $short_url = $_POST['short_url'];
    $web_type = $_POST['web_type'];
    $upload_time = $_POST['upload_time'];
    $set_position = $_POST['sel_position'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if($set_position != 1){
        $sql_mem_name = "select mem_name from Gn_Member where mem_id='{$mem_id}'";
        $res_name = mysqli_query($self_con, $sql_mem_name);
        $row_name = mysqli_fetch_array($res_name);
        $mem_name = $row_name['mem_name'];
    }
    else{
        $mem_name = "";
    }

    if($set_position == 2){
        $sql_card_idx = "select idx from Gn_Iam_Name_Card where card_short_url='{$short_url}'";
        $res_idx = mysqli_query($self_con, $sql_card_idx);
        $row_idx = mysqli_fetch_array($res_idx);
        $card_idx = $row_idx['idx'];

        $sql_dup = "select card_idx from auto_update_contents where card_idx={$card_idx}";
        $res_dup = mysqli_query($self_con, $sql_dup);
        $row_dup = mysqli_fetch_array($res_dup);
    }
    else{
        $card_idx = 0;
    }

    
    if(isset($_POST['auto_id'])){//오토데이트 편집
        $id = $_POST['auto_id'];
        $sql_contents = "update auto_update_contents set card_idx={$card_idx}, web_type='{$web_type}', web_address='{$address}', keyword='{$contents_keyword}', get_time='{$upload_time}', start_date='{$start_date}', end_date='{$end_date}', update_date='{$cur_time1}' where id={$id}";
        mysqli_query($self_con, $sql_contents);

        $sql_history = "update contents_update_history set card_idx={$card_idx}, web_type='{$web_type}', reg_date='{$cur_time1}' where update_id={$id} order by id desc limit 1";
        mysqli_query($self_con, $sql_history);

        echo 1;
    }
    else if(isset($_POST['id']) && isset($_POST['stop_auto'])){//오토데이트 중지/진행
        $sql_check_state = "select state_flag from auto_update_contents where id={$_POST['id']}";
        $res_state = mysqli_query($self_con, $sql_check_state);
        $row_state = mysqli_fetch_array($res_state);
        $state = $row_state['state_flag'];

        $sql_ori_history = "select * from contents_update_history where update_id={$_POST['id']} order by id desc limit 1";
        $res_ori_history = mysqli_query($self_con, $sql_ori_history);
        $row_ori_history = mysqli_fetch_array($res_ori_history);

        if($state == 1){
            $sql_contents = "update auto_update_contents set state_flag=2, update_date='{$cur_time1}' where id={$_POST['id']}";
            mysqli_query($self_con, $sql_contents);

            $sql_history = "insert into contents_update_history set mem_id='{$row_ori_history['mem_id']}', update_id={$_POST['id']}, card_idx={$row_ori_history['card_idx']}, web_type='{$row_ori_history['web_type']}', settle_cnt={$row_ori_history['settle_cnt']}, state=3, point={$row_ori_history['point']}, used_point=1100, rest_point={$row_ori_history['rest_point']}, reg_date='{$cur_time1}'";
            mysqli_query($self_con, $sql_history);
    
            echo 1;
        }
        else{
            $sql_point = "select mem_point from Gn_Member where mem_id='{$mem_id}'";
            $res_point = mysqli_query($self_con, $sql_point);
            $row_point = mysqli_fetch_array($res_point);
            $cur_point = $row_point['mem_point'];

            if($cur_point < 1100){
                echo 3;
            }
            else{
                $sql_contents = "update auto_update_contents set state_flag=1, update_date='{$cur_time1}' where id={$_POST['id']}";
                mysqli_query($self_con, $sql_contents);
    
                $sql_history = "insert into contents_update_history set mem_id='{$row_ori_history['mem_id']}', update_id={$_POST['id']}, card_idx={$row_ori_history['card_idx']}, web_type='{$row_ori_history['web_type']}', settle_cnt={$row_ori_history['settle_cnt']}, state=1, point={$row_ori_history['point']}, used_point=1100, rest_point={$row_ori_history['rest_point']}, reg_date='{$cur_time1}'";
                mysqli_query($self_con, $sql_history);
        
                echo 2;
            }
        }
        
    }
    else if(isset($_POST['id']) && isset($_POST['remove_auto'])){//오토데이트 삭제
        $sql = "delete from auto_update_contents where id={$_POST['id']}";
        mysqli_query($self_con, $sql);

        echo 1;
    }
    else{//오토데이트 설정
        if(($set_position == 2) && ($row_dup['card_idx'] != NULL)){
            echo 3;
        }
        else if(isset($_POST['sel_position'])){

            $sql_service = "select * from Gn_Iam_Service where mem_id='{$mem_id}'";//분양사아이디이면.
            $res_service = mysqli_query($self_con, $sql_service);
            if(mysqli_num_rows($res_service)){
                $row = mysqli_fetch_array($res_service);
                if($row['autodata_point_end'] < $date_today){
                    $min_point = 1100;
                }
                else{
                    if($row['autodata_point'] == ''){
                        $min_point = 1100;
                    }
                    else if($row['autodata_point'] == 0){
                        $min_point = 0;
                    }
                    else{
                        $min_point = $row['autodata_point'] * 1;
                    }
                }
            }
            else{
                $min_point = 1100;
            }
            
            $sql_release = "update Gn_Member set mem_point=mem_point-{$min_point} where mem_id='{$mem_id}'";
            mysqli_query($self_con, $sql_release);

            $sql_rest_point = "select * from Gn_Member where mem_id='{$mem_id}'";
            $res_rest_point = mysqli_query($self_con, $sql_rest_point);
            $row_rest_point = mysqli_fetch_array($res_rest_point);
            $rest_point = $row_rest_point['mem_point'];
            $rest_cash = $row_rest_point['mem_cash'];
            $mem_phone = $row_rest_point['mem_phone'];
            $method = $mem_id . "/" . $mem_name;

            $sql_contents = "insert into auto_update_contents set mem_id='{$mem_id}', mem_name='{$mem_name}', card_idx={$card_idx}, web_type='{$web_type}', web_address='{$address}', keyword='{$contents_keyword}', set_position={$set_position}, settle_cnt=1, get_time='{$upload_time}', start_date='{$start_date}', end_date='{$end_date}', state_flag=1, reg_date='{$cur_time1}'";
            // echo $sql_contents;
            mysqli_query($self_con, $sql_contents);
            $auto_id = mysqli_insert_id($self_con);
        
            $sql_history = "insert into contents_update_history set mem_id='{$mem_id}', update_id={$auto_id}, card_idx={$card_idx}, web_type='{$web_type}', settle_cnt=1, point={$min_point}, used_point={$min_point}, rest_point={$rest_point}, state=1, reg_date='{$cur_time1}'";
            mysqli_query($self_con, $sql_history);
            
            $sql_item_pay_res = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_id}', buyer_tel='{$mem_phone}', item_name='오토데이트', item_price={$min_point}, pay_percent=90, current_point={$rest_point}, current_cash='{$rest_cash}', pay_status='Y', VACT_InputName='{$mem_name}', type='buy', seller_id='', pay_method='{$method}', pay_date=now(), point_val=1";
            mysqli_query($self_con, $sql_item_pay_res);

            echo 1;
        }
    }
}
?>