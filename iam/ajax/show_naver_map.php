<?include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";?>
<?
    $date_today = date("Y-m-d H:i:s");
    extract($_POST);
    if($type == "address"){
        $sql_addr = "select card_addr, card_name from Gn_Iam_Name_Card where idx='{$card_idx}'";
        $res_addr = mysqli_query($self_con,$sql_addr);
        $row_addr = mysqli_fetch_array($res_addr);

        echo $row_addr[0]."&&".$row_addr[1];
    }
    else if($type == "show_map"){
        $sql_addr = "select card_addr from Gn_Iam_Name_Card where idx='{$card_idx}'";
        $res_addr = mysqli_query($self_con,$sql_addr);
        $row_addr = mysqli_fetch_array($res_addr);

        echo $row_addr[0];
    }
    else if($type == "distance"){
        $sql_addr = "select card_addr, map_pos from Gn_Iam_Name_Card where idx='{$card_idx}'";
        $res_addr = mysqli_query($self_con,$sql_addr);
        $row_addr = mysqli_fetch_array($res_addr);

        echo $row_addr[0] . "@" . $row_addr[1];
    }
    else if($type == "distance_calc"){
        $lat1_res = explode("&", $start_info);
        $lat1 = $lat1_res[0] * 1;
        $lon1_res = explode("&", $start_info);
        $lon1 = $lon1_res[1] * 1;

        $lat2_res = explode("&", $end_info);
        $lat2 = $lat2_res[0] * 1;
        $lon2_res = explode("&", $end_info);
        $lon2 = $lon2_res[1] * 1;

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        echo round($miles * 1.609344);
    }
    else if($type == "event"){
        $sql_contents_data = "select idx, contents_price, contents_sell_price, init_reduce_val from Gn_Iam_Contents where card_idx='{$card_idx}'";
        $res_contents_data = mysqli_query($self_con,$sql_contents_data);
        
        while($row_contents_data = mysqli_fetch_array($res_contents_data)){
            $sql_card_redu_val = "select add_fixed_val from Gn_Iam_Name_Card where idx='{$card_idx}'";
            $res_card_redu_val = mysqli_query($self_con,$sql_card_redu_val);
            $row_card_redu_val = mysqli_fetch_array($res_card_redu_val);

            $sell_price = (1 - ($row_card_redu_val['add_fixed_val'] * 1 + $event_val * 1 + $row_contents_data['init_reduce_val'] * 1) / 100) * $row_contents_data['contents_price'];

            $reduce_val = 100 - ($sell_price / $row_contents_data['contents_price']) * 100;
            $sql_update = "update Gn_Iam_Contents set contents_sell_price='{$sell_price}', reduce_val = '{$reduce_val}' where idx='{$row_contents_data['idx']}'";
            mysqli_query($self_con,$sql_update);
        }

        if(!$event_cnt){
            $date_today = "";
        }
        $update = "update Gn_Iam_Name_Card set sale_cnt='{$event_cnt}', sale_cnt_set='{$event_cnt}', sale_cnt_reg_date='{$date_today}', add_reduce_val='{$event_val}' where idx='{$card_idx}'";
        mysqli_query($self_con,$update);
        echo 1;
    }
    else if($type == "fixed"){
        $sql_card_data = "select idx, add_reduce_val from Gn_Iam_Name_Card where idx='{$card_idx}'";
        $res_card_data = mysqli_query($self_con,$sql_card_data);
        $row_card_data = mysqli_fetch_array($res_card_data);

        $sql_contents_data = "select idx, contents_price, contents_sell_price, init_reduce_val from Gn_Iam_Contents where card_idx='{$card_idx}'";
        $res_contents_data = mysqli_query($self_con,$sql_contents_data);
        while($row_contents_data = mysqli_fetch_array($res_contents_data)){
            if($row_card_data['add_reduce_val']){
                $sell_price = (1 - ($fixed * 1 + $row_card_data['add_reduce_val'] * 1 + $row_contents_data['init_reduce_val'] * 1) / 100) * $row_contents_data['contents_price'];
            }
            else{
                $sell_price = (1 - ($fixed * 1 + $row_contents_data['init_reduce_val'] * 1) / 100) * $row_contents_data['contents_price'];
            }
            
            $reduce_val = 100 - ($sell_price / $row_contents_data['contents_price']) * 100;
            $sql_update = "update Gn_Iam_Contents set contents_sell_price='{$sell_price}', reduce_val = '{$reduce_val}' where idx='{$row_contents_data['idx']}'";
            mysqli_query($self_con,$sql_update);
        }
        $update = "update Gn_Iam_Name_Card set add_fixed_val='{$fixed}' where idx='{$card_idx}'";
        mysqli_query($self_con,$update);
        echo 1;
    }
    else if($type == "busi_time"){
        $sql_time = "select business_time from Gn_Iam_Name_Card where idx='{$card_idx}'";
        $res_time = mysqli_query($self_con,$sql_time);
        $row_time = mysqli_fetch_array($res_time);

        $res_str = str_replace("\n", "<br>", $row_time[0]);
        echo $res_str;
    }
    else if($type == "edit_busi_time"){
        $sql_cont_data = "select mem_id, card_short_url from Gn_Iam_Contents where idx='{$cont_idx}'";
        $res_cont_data = mysqli_query($self_con,$sql_cont_data);
        $row_cont_data = mysqli_fetch_array($res_cont_data);
        $short_url = $row_cont_data['card_short_url'];

        $sql_mem_code = "select mem_code from Gn_Member where mem_id='{$row_cont_data['mem_id']}'";
        $res_mem_code = mysqli_query($self_con,$sql_mem_code);
        $row_mem_code = mysqli_fetch_array($res_mem_code);
        $mem_code = $row_mem_code[0];

        echo $short_url . $mem_code;
    }
?>