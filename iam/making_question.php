<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$sql="select * from Gn_Member where mem_id='{$_SESSION['one_member_id']}' and site != '' ";
$resul=mysqli_query($self_con,$sql);
$data=mysqli_fetch_array($resul);

if(isset($_POST['settle_type'])){
    $get_point = "select mem_point from Gn_member where mem_id='{$_SESSION['one_member_id']}'";
    $result_point = mysqli_query($self_con,$get_point);
    $row_point = mysqli_fetch_array($result_point);

    $point = $_POST['point_val'];
    $type = $_POST['settle_type'];
    $allat_amt = $_POST['allat_amt'];

    $current_point = $row_point['mem_point'] * 1 - $allat_amt;

    if($type == "make_question"){
        $member_type = "설문작성";
        $sql_question_make = "insert into Gn_Item_Pay_Result
                    set buyer_id='{$_SESSION['one_member_id']}',
                        buyer_tel='$data[mem_phone]',
                        item_name = '$member_type',
                        item_price=$_POST[allat_amt],
                        pay_date=NOW(),
                        pay_status='Y',
                        pay_percent='$_POST[pay_percent]',
                        order_number = '$_POST[allat_order_no]',
                        VACT_InputName='{$data['mem_name']}',
                        point_val=$point,
                        type='question',
                        current_point=$current_point";
        $res_result = mysqli_query($self_con,$sql_question_make);
    }
    else if($type == "contents_send"){
        $sql_ids = "update Gn_Member set send_ids='{$payMethod}' where mem_id='{$_SESSION['one_member_id']}'";
        mysqli_query($self_con,$sql_ids) or die(mysqli_error($self_con));

        $sql_service = "select * from Gn_Iam_Service where mem_id='{$_SESSION['one_member_id']}'";//분양사아이디이면.
        $res_service = mysqli_query($self_con,$sql_service);
        if(mysqli_num_rows($res_service)){
            $row = mysqli_fetch_array($res_service);
            if($row['contents_point_end'] < $date_today){
                $min_point = $_POST[allat_amt] * 1;
            }
            else{
                if($row['contents_send_point'] == ''){
                    $min_point = $_POST[allat_amt] * 1;
                }
                else if($row['contents_send_point'] == 0){
                    $min_point = 0;
                }
                else{
                    $min_point = $row['contents_send_point'] * 1;
                }
            }
        }
        else{
            $min_point = $_POST[allat_amt] * 1;
        }

        $sql_con_title = "select contents_title from Gn_Iam_Contents where idx={$member_type}";
        $res_con_title = mysqli_query($self_con,$sql_con_title);
        $row_con_title = mysqli_fetch_array($res_con_title);
        $con_url = "http://".$HTTP_HOST."/iam/contents.php?contents_idx=".$member_type;
        $item_name = "IAM 콘텐츠/" . $row_con_title['contents_title'];
        
        for($i = 0; $i < count($arr_id); $i++){
            // $get_point = "select current_point from Gn_Item_Pay_Result where buyer_id='{$_SESSION['one_member_id']}' and point_val!=0 and pay_status='Y' order by pay_date desc limit 1";
            $get_point = "select mem_point from Gn_member where mem_id='{$_SESSION['one_member_id']}'";
            $result_point = mysqli_query($self_con,$get_point);
            $row_point = mysqli_fetch_array($result_point);
            $current_point_buy = $row_point['mem_point'];
            $current_point = $current_point_buy * 1 - $min_point * 1;

            $sql_contents_send = "insert into Gn_Item_Pay_Result
                        set buyer_id='{$_SESSION['one_member_id']}',
                            buyer_tel='$data[mem_phone]',
                            site='$con_url',
                            pay_method='$arr_id[$i]',
                            item_name = '$item_name',
                            item_price=$min_point,
                            seller_id='$_POST[seller_id]',
                            pay_date=NOW(),
                            pay_status='Y',
                            pay_percent='$_POST[pay_percent]',
                            order_number = '$_POST[allat_order_no]',
                            VACT_InputName='{$data['mem_name']}',
                            point_val=$point,
                            type='contentssend',
                            current_point=$current_point,
                            receive_state=1,
                            message='$message'";
                            // echo $sql_contents_send; exit;
            $res_result = mysqli_query($self_con,$sql_contents_send);

            $sql_update = "update Gn_Member set mem_point={$current_point} where mem_id='{$_SESSION['one_member_id']}'";
            mysqli_query($self_con,$sql_update);
        }

        $sql_ori = "select * from Gn_Iam_Contents where idx={$member_type}";
        $res_ori = mysqli_query($self_con,$sql_ori);
        $row_ori = mysqli_fetch_array($res_ori);
        $mem_ori = $row_ori['contents_share_text'];

        $mem_ids = $mem_ori . "," . $payMethod;
        if($mem_ids != "") {
            $share_array = explode($mem_ids);
            $share_count = count($share_array);
        }
        $sql_share_con = "update Gn_Iam_Contents set contents_share_text='{$mem_ids}',contents_share_count = $share_count, up_data=now() where idx={$member_type}";
        mysqli_query($self_con,$sql_share_con);
    }
    else if($type == "del"){
        $no = $_POST['no'];
        $card_con = $_POST['card_con'];
        if($card_con == "contents"){
            if(strpos($no, ",") !== false){
                $nos = explode(",", $no);
                for($i = 0; $i < count($nos); $i++){
                    $sql_mem = "select * from Gn_Item_Pay_Result where no={$nos[$i]}";
                    $res_mem = mysqli_query($self_con,$sql_mem);
                    $row_mem = mysqli_fetch_array($res_mem);

                    $con_id = $row_mem['site'];
                    $val_id = explode("=", $con_id);
                    $con_id = trim($val_id[1]);
                    
                    $sql_contents = "select * from Gn_Iam_Contents where idx={$con_id}";
                    $res_contents = mysqli_query($self_con,$sql_contents);
                    $row_contents = mysqli_fetch_array($res_contents);
                    $contents_share_txt = $row_contents['contents_share_text'];
                    $contents_share_txt = str_replace($row_mem['pay_method'], "", $contents_share_txt);
                    $share_count = 0;
                    if($contents_share_txt != "") {
                        $share_array = explode($contents_share_txt);
                        $share_count = count($share_array);
                    }
                    $sql_update_con = "update Gn_Iam_Contents set contents_share_text='{$contents_share_txt}',contents_share_count=$share_count where idx={$con_id}";
                    $res_result = mysqli_query($self_con,$sql_update_con);
                }
            }
            else{
                $sql_mem = "select * from Gn_Item_Pay_Result where no={$no}";
                $res_mem = mysqli_query($self_con,$sql_mem);
                $row_mem = mysqli_fetch_array($res_mem);
    
                $con_id = $row_mem['site'];
                $val_id = explode("=", $con_id);
                $con_id = trim($val_id[1]);
                
                $sql_contents = "select * from Gn_Iam_Contents where idx={$con_id}";
                $res_contents = mysqli_query($self_con,$sql_contents);
                $row_contents = mysqli_fetch_array($res_contents);
                $contents_share_txt = $row_contents['contents_share_text'];
                $contents_share_txt = str_replace($row_mem['pay_method'], "", $contents_share_txt);
                $share_count = 0;
                if($contents_share_txt != "") {
                    $share_array = explode($contents_share_txt);
                    $share_count = count($share_array);
                }
                $sql_update_con = "update Gn_Iam_Contents set contents_share_text='{$contents_share_txt}',contents_share_count=$share_count where idx={$con_id}";
                mysqli_query($self_con,$sql_update_con);

                $sql_del = "delete from Gn_Item_Pay_Result where no={$no}";
                $res_result = mysqli_query($self_con,$sql_del);
            }
        }
    }
    else if($type == "recv_card"){
        $no = $_POST['no'];
        $sender = $_POST['sender'];

        $sql_service = "select * from Gn_Iam_Service where mem_id='{$sender}'";//분양사아이디이면.
        $res_service = mysqli_query($self_con,$sql_service);
        if(mysqli_num_rows($res_service)){
            $row = mysqli_fetch_array($res_service);
            if($row['card_point_end'] < $date_today){
                $settle_point = $_POST['settle_point'] * 1;
            }
            else{
                if($row['card_send_point'] == ''){
                    $settle_point = $_POST['settle_point'] * 1;
                }
                else if($row['card_send_point'] == 0){
                    $settle_point = 0;
                }
                else{
                    $settle_point = $row['card_send_point'] * 1;
                }
            }
        }
        else{
            $settle_point = $_POST['settle_point'] * 1;
        }       

        // $sql_point = "select current_point from Gn_Item_Pay_Result where point_val!=0 and buyer_id='{$sender}' order by pay_date desc limit 1";
        $sql_point = "select mem_point from Gn_Member where mem_id='{$sender}'";
        $res_point = mysqli_query($self_con,$sql_point);
        $row_point = mysqli_fetch_array($res_point);
        $cur_point = $row_point['mem_point'];

        if($cur_point < $settle_point){
            echo 3;
            exit;
        }

        $sql_card = "select * from Gn_Item_Pay_Result where no={$no}";
        $res_card = mysqli_query($self_con,$sql_card);
        $row_card = mysqli_fetch_array($res_card);

        $card_url = $row_card['site'];
        $val1 = explode("?", $card_url);
        $card_url = trim(substr(trim($val1[1]), 0, 10));

        $short_url = generateRandomString();

        $sql_name = "INSERT INTO Gn_Iam_Name_Card(mem_id, card_short_url, card_title, card_name, card_company, card_position, card_phone, phone_display, card_email, card_addr, card_map, card_keyword, profile_logo, favorite, story_title1, story_myinfo, story_title2, story_company, story_title3, story_career, story_title4, story_online1_text, story_online1, online1_check, story_online2_text, story_online2, online2_check, iam_click, iam_story, iam_friends, iam_iamicon, iam_msms, iam_sms, iam_facebook, iam_kakao, iam_share, iam_mystory, req_data, up_data, sample_click, sample_order, main_img1, main_img2, main_img3, next_iam_link) (SELECT '{$_SESSION['iam_member_id']}', '{$short_url}', card_title, card_name, card_company, card_position, card_phone, phone_display, card_email, card_addr, card_map, card_keyword, profile_logo, favorite, story_title1, story_myinfo, story_title2, story_company, story_title3, story_career, story_title4, story_online1_text, story_online1, online1_check, story_online2_text, story_online2, online2_check, iam_click, iam_story, iam_friends, iam_iamicon, iam_msms, iam_sms, iam_facebook, iam_kakao, iam_share, iam_mystory, now(), now(), sample_click, sample_order, main_img1, main_img2, main_img3, next_iam_link FROM Gn_Iam_Name_Card WHERE card_short_url='{$card_url}')";
		// echo $sql_name; exit;
		mysqli_query($self_con,$sql_name) or die(mysqli_error($self_con));
		$card_idx = mysqli_insert_id($self_con);
		
		$sql_con = "INSERT INTO Gn_Iam_Contents(mem_id, contents_type, contents_img, contents_title, contents_url, contents_url_title, contents_iframe, source_iframe, contents_price, contents_sell_price, contents_desc, contents_display, contents_user_display, contents_type_display, contents_footer_display, contents_temp, contents_like, contents_share_text, contents_share_count, req_data, up_data, card_short_url, contents_westory_display, westory_card_url, public_display, card_idx, except_keyword) (SELECT '{$_SESSION['iam_member_id']}', contents_type, contents_img, contents_title, contents_url, contents_url_title, contents_iframe, source_iframe, contents_price, contents_sell_price, contents_desc, contents_display, contents_user_display, contents_type_display, contents_footer_display, contents_temp, contents_like, contents_share_text, contents_share_count, now(), now(), '{$short_url}', contents_westory_display, '{$short_url}', public_display, {$card_idx}, except_keyword FROM Gn_Iam_Contents WHERE card_short_url='{$card_url}')";
        mysqli_query($self_con,$sql_con) or die(mysqli_error($self_con));
        $contents_idx = mysqli_insert_id($self_con);
        $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$contents_idx,card_idx=$card_idx,main_card=$card_idx";
        mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        
        $card_link = "http://" . $HTTP_HOST . "/?" . $short_url . $data['mem_code'];

        $cur_point = $cur_point * 1 - $settle_point * 1;
        $sql_update1 = "update Gn_Member set mem_point={$cur_point} where mem_id='{$sender}'";
        mysqli_query($self_con,$sql_update1);

        $sql_update = "update Gn_Item_Pay_Result set seller_id='{$card_link}', receive_state=1, item_price={$settle_point}, current_point={$cur_point}, pay_date=now() where no={$no}";
        $res_result = mysqli_query($self_con,$sql_update);
    }
    else if($type == "show_cnt"){
        $no = $_POST['no'];
        $update_cnt = "update Gn_Item_Pay_Result set con_show_cnt=con_show_cnt+1 where no={$no}";
        $res_result = mysqli_query($self_con,$update_cnt);
    }
    else if($type == "payment_show"){
        $sql_sell = "update Gn_Item_Pay_Result set alarm_state=1 where buyer_id='{$_SESSION['iam_member_id']}' and point_val=1 and site is not null and type='servicebuy' and alarm_state=0";
        $res_result = mysqli_query($self_con,$sql_sell);
    }
    else if($type == "refuse"){
        $no = $_POST['no'];
        $update_use = "update Gn_Item_Pay_Result set receive_state=2 where no={$no}";
        $res_result = mysqli_query($self_con,$update_use);
    }
    else if($type == "delete_ai"){
        $no = $_POST['no'];
        $ID = $_POST['ID'];
        if($no != "all"){
            $sql_del = "delete from Gn_Item_Pay_Result where no={$no}";
            $res_result = mysqli_query($self_con,$sql_del);
        }
        else{
            $sql_del = "delete from Gn_Item_Pay_Result where item_name='AI카드' and buyer_id='{$ID}'";
            $res_result = mysqli_query($self_con,$sql_del);
        }
    }
    echo $res_result;
}
?>