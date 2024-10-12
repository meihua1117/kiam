<? //include $_SERVER['DOCUMENT_ROOT']."/iam/snoopy/Snoopy.class.php";
?>
<? include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php"; ?>
<? include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/class.image.php"; ?>
<?
$up_dir = make_folder_month(2);
if ($up_dir != '') {
    $uploaddir = '../..' . $up_dir;
} else {
    $uploaddir = '../../upload/';
    $up_dir = "upload/";
}
$post_type = $_POST['post_type'];
$contents_idx = $_POST['contents_idx'];
$contents_type = $_POST['contents_type'];
$media_type = "I";
if (isset($_POST['media_type']))
    $media_type = $_POST['media_type'];
if ($media_type == "I")
    $contents_img_url = htmlspecialchars($_POST['contents_img_url']);
else
    $contents_img_url = htmlspecialchars($_POST['contents_vid_url']);
$contents_title = htmlspecialchars($_POST['contents_title']);
$contents_temp = $_POST['contents_temp'];
if ($contents_temp == "")
    $contents_temp = 0;
$contents_order = $_POST['contents_order'];
$contents_url = htmlspecialchars($_POST['contents_url']);
$contents_iframe = $_POST['contents_iframe'];
$contents_price = $_POST['contents_price'];
if ($contents_price == "")
    $contents_price = 0;
$contents_price_arr = explode("|",$contents_price);
$contents_sell_price = $_POST['contents_sell_price'];
if ($contents_sell_price == "")
    $contents_sell_price = 0;

$reduce_val = 100 - ($contents_sell_price / ($contents_price_arr[0]*1)) * 100;
$contents_desc = htmlspecialchars($_POST['contents_desc']);
$except_keyword = $_POST['except_keyword'];
$contents_display = $_POST['contents_display'];
$contents_westory_display = $_POST['contents_westory_display'];
$contents_user_display = $_POST['contents_user_display'];
$contents_type_display = $_POST['contents_type_display'];
$contents_footer_display = $_POST['contents_footer_display'];
$contents_share_id = trim($_POST['contents_share_id']);
$card_short_url = $_POST['card_short_url'];
$card_array = explode(",", $card_short_url);
$westory_card_url = $_POST['westory_card_url'];
$source_iframe = $_POST['source_iframe'];
$contents_add_multi_free = $_POST['contents_add_multi_free'];
$open_type = $_POST['open_type'];
if($media_type == "V")
    $open_type = 1;
$landing_mode = $_POST['landing_mode'];
$group_id = $_POST['group_id'];
$gwc_con_state = isset($_POST['gwc_con_state']) ? $_POST['gwc_con_state'] : '0';
$product_code = $_POST['product_code'];
$product_model_name = $_POST['product_model_name'];
$product_seperate = $_POST['product_seperate'];
$send_provide_price = $_POST['send_provide_price'];
$prod_manufact_price = $_POST['prod_manufact_price'];
$send_salary_price = $_POST['send_salary_price'];
$req_provide = $_POST['req_provide'];
$deliver_id_code = $_POST['deliver_id_code'];
if ($req_provide == "Y") {
    $card_short_url = $westory_card_url;
    $public_display = "N";
    $landing_mode = "Y";
} else {
    $public_display = "Y";
}
if ($_POST['admin'] || $_POST['provider'] == "Y") {
    $westory_card_url = $card_short_url = $_POST['gwc_card_url'];
}
if ($group_id == "")
    $group_id = "NULL";
//if($_POST['mem_id'])
//    $mem_id = $_POST['mem_id'];
//else
$mem_id = $_SESSION['iam_member_id'];
$card_sql = "select idx,card_name, add_reduce_val, add_fixed_val from Gn_Iam_Name_Card where card_short_url = '$westory_card_url'";
$card_res = mysqli_query($self_con,$card_sql);
$card_row = mysqli_fetch_array($card_res);
if (!$mem_id) {
    echo '로그아웃 되었습니다. 다시 로그인을 해주세요.';
    exit;
}

if (!$post_type) {
    echo '잘못된 접근입니다. 다시 시도 해주세요.';
    exit;
}
if ($product_seperate != "" && $gwc_con_state) {
    $contents_table_name = "Gn_Iam_Contents_Gwc";
} else {
    $contents_table_name = "Gn_Iam_Contents";
}
// ==================================================================================
// Create
if ($post_type == "creat") {
    //$mem_id = $_POST['mem_id'];
    $msg = "컨텐츠가 등록 되었습니다.";
    $query = "select iam_share_cnt from Gn_Member where mem_id='$mem_id'";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    $info = explode(",", $contents_share_id);
    if ($contents_share_id != "" && $row[0] < count($info)) {
        echo json_encode(array('idx' => 0, 'result' => "콘텐츠 전송건수를 초과하였습니다."));
        exit;
    }
    $group_display = "Y";
    if ($group_id != "NULL") {
        $group_sql = "select upload_status from gn_group_info where idx = $group_id";
        $group_res = mysqli_query($self_con,$group_sql);
        $group_row = mysqli_fetch_array($group_res);
        $group_display = $group_row[0];
    }
    $sql_share_new = "select idx, mem_id, card_short_url from Gn_Iam_Name_Card where share_send_card='{$card_row['idx']}'";
    $res_share_new = mysqli_query($self_con,$sql_share_new);
    $cnt_share_new = mysqli_num_rows($res_share_new);
    if ($contents_add_multi_free == 1) {
        if ($contents_img_url == "") {
            $total = count($_FILES['contents_img']['name']);
        } else {
            $contents_img_url_arr = explode(",", $contents_img_url);
            $total = count($contents_img_url_arr);
        }
        if ($contents_order == 0) {
            if ($group_id == "NULL")
                $sql = "select max(contents_order) from " . $contents_table_name . " where mem_id = '$mem_id' and card_idx = {$card_row['idx']}";
            else
                $sql = "select max(contents_order) from " . $contents_table_name . " where group_id = '$group_id' and card_idx = {$card_row['idx']}";
            $result = mysqli_query($self_con,$sql);
            $comment_row = mysqli_fetch_array($result);
            $contents_order = (int)$comment_row[0] + 1;
        } else {
            if ($group_id == "NULL")
                $sql = "update " . $contents_table_name . " set contents_order = contents_order + '$total'  where mem_id = '$mem_id' and card_idx = {$card_row['idx']} and contents_order >= $contents_order";
            else
                $sql = "update " . $contents_table_name . " set contents_order = contents_order + '$total'  where group_id = '$group_id' and card_idx = {$card_row['idx']} and contents_order >= $contents_order";
            mysqli_query($self_con,$sql);
        }
        for ($i = 0; $i < $total; $i++) {
            if ($contents_img_url == "") {
                $tmpFilePath = $_FILES['contents_img']['tmp_name'][$i];
                if ($tmpFilePath != "") {
                    $date_file_name = date('dmYHis') . $i . "." . pathinfo($_FILES["contents_img"]["name"][$i], PATHINFO_EXTENSION);
                    $uploadfile = $uploaddir . basename($date_file_name);
                    $contents_img = $up_dir . basename($date_file_name);
                    if (move_uploaded_file($tmpFilePath, $uploadfile)) {
                        $handle = new Image($uploadfile, 800);
                        $handle->resize();
                        uploadFTP($uploadfile);
                    }
                }
            } else {
                $contents_img = $contents_img_url_arr[$i];
            }
            $contents_share_text = $contents_share_id;
            if ($contents_share_text == "")
                $contents_share_count = 0;
            else {
                $contents_share_ids = explode(",", $contents_share_id);
                $contents_share_count = count($contents_share_ids);
            }
            $cont_order = $contents_order + $total - 1 - $i;
            $sql2 = "insert into " . $contents_table_name . " (
                                      mem_id, 
                                      contents_type, 
                                      contents_img, 
                                      contents_title, 
                                      contents_url, 
                                      contents_url_title, 
                                      contents_iframe, 
                                      contents_price, 
                                      contents_sell_price, 
                                      contents_desc,
                                      except_keyword,
                                      contents_display,
                                      contents_westory_display, 
                                      contents_user_display, 
                                      contents_type_display, 
                                      contents_footer_display, 
                                      contents_temp, 
                                      contents_share_text, 
                                      contents_share_count, 
                                      req_data, 
                                      up_data,
                                      card_short_url,
                                      westory_card_url,
                                      public_display,
                                      card_idx,
                                      source_iframe,
                                      contents_order,
                                      gwc_con_state,
                                      landing_mode,
                                      reduce_val,
                                      open_type,";
            if ($group_id != "NULL") {
                $sql2 .= "group_id,";
            }
            if ($product_seperate != "") {
                $sql2 .= "product_code,
                          product_model_name,
                          product_seperate,
                          send_provide_price,
                          prod_manufact_price,
                          send_salary_price,
                          ai_map_gmarket,
                          provider_req_prod,
                          delivery_id_code,";
            }
            $sql2 .= "group_display) values 
                                      (\"$mem_id\",
                                      \"$contents_type\", 
                                      \"$contents_img\", 
                                      \"$contents_title\", 
                                      \"$contents_url\", 
                                      \"$contents_url_title\", 
                                      \"$contents_iframe\", 
                                      \"$contents_price\", 
                                      \"$contents_sell_price\", 
                                      \"$contents_desc\",
                                      \"$except_keyword\",
                                      \"$contents_display\",
                                      \"$contents_westory_display\", 
                                      \"$contents_user_display\", 
                                      \"$contents_type_display\", 
                                      \"$contents_footer_display\", 
                                      \"$contents_temp\", 
                                      \"$contents_share_text\",
                                      \"$contents_share_count\",
                                      now(),
                                      now(),
                                      \"$card_short_url\",
                                      \"$westory_card_url\",
                                      \"$public_display\",
                                      \"{$card_row['idx']}\",
                                      \"$source_iframe\",
                                      $cont_order,
                                      '$gwc_con_state',
                                      \"$landing_mode\",
                                      '$reduce_val',";
            if ($group_id != "NULL") {
                $sql2 .= "'$group_id',";
            }
            if ($product_seperate != "") {
                $sql2 .= "'$product_code',
                          '$product_model_name',
                          '$product_seperate',
                          '$send_provide_price',
                          '$prod_manufact_price',
                          '$send_salary_price',
                          '3',
                          '$req_provide',
                          '$deliver_id_code',";
            }
            $sql2 .= " '$group_display'
                                      )";
            $result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
            $content_idx = mysqli_insert_id($self_con);
            foreach ($card_array as $card_link) {
                $sql1 = "select idx from Gn_Iam_Name_Card where card_short_url = '$card_link'";
                $res1 = mysqli_query($self_con,$sql1);
                $row1 = mysqli_fetch_array($res1);
                $card = $row1['idx'];
                $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$content_idx,card_idx=$card,main_card={$card_row['idx']}";
                mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
            }
        }

        if ($cnt_share_new) {
            while ($row_share_new = mysqli_fetch_array($res_share_new)) {
                if ($_POST['contents_order'] == 0) {
                    if ($group_id == "NULL")
                        $sql = "select max(contents_order) from " . $contents_table_name . " where mem_id = '$mem_id' and card_idx = {$row_share_new['idx']}";
                    else
                        $sql = "select max(contents_order) from " . $contents_table_name . " where group_id = '$group_id' and card_idx = {$row_share_new['idx']}";
                    $result = mysqli_query($self_con,$sql);
                    $comment_row = mysqli_fetch_array($result);
                    $contents_order = (int)$comment_row[0] + 1;
                } else {
                    if ($group_id == "NULL")
                        $sql = "update " . $contents_table_name . " set contents_order = contents_order + '$total'  where card_idx = {$row_share_new['idx']} and contents_order >= $_POST[contents_order]";
                    else
                        $sql = "update " . $contents_table_name . " set contents_order = contents_order + '$total'  where group_id = '$group_id' and card_idx = {$row_share_new['idx']} and contents_order >= $_POST[contents_order]";
                    mysqli_query($self_con,$sql);
                }

                for ($i = 0; $i < $total; $i++) {
                    if ($contents_img_url == "") {
                        $tmpFilePath = $_FILES['contents_img']['tmp_name'][$i];
                        if ($tmpFilePath != "") {
                            //$date_file_name = date('dmYHis') . str_replace(" ", "", basename($_FILES["contents_img"]["name"][$i]));
                            $date_file_name = date('dmYHis') . $i . "." . pathinfo($_FILES["contents_img"]["name"][$i], PATHINFO_EXTENSION);
                            $uploadfile = $uploaddir . basename($date_file_name);
                            if (move_uploaded_file($tmpFilePath, $uploadfile)) {
                                $contents_img = $up_dir . basename($date_file_name);
                                $handle = new Image($uploadfile, 800);
                                $handle->resize();
                                uploadFTP($uploadfile);
                            }
                        }
                    } else {
                        $contents_img = $contents_img_url_arr[$i];
                    }
                    $contents_share_text = $contents_share_id;
                    if ($contents_share_text == "")
                        $contents_share_count = 0;
                    else {
                        $contents_share_ids = explode(",", $contents_share_id);
                        $contents_share_count = count($contents_share_ids);
                    }
                    $cont_order = $contents_order + $total - 1 - $i;
                    $sql2 = "insert into " . $contents_table_name . " (
                                              mem_id, 
                                              contents_type, 
                                              contents_img, 
                                              contents_title, 
                                              contents_url, 
                                              contents_url_title, 
                                              contents_iframe, 
                                              contents_price, 
                                              contents_sell_price, 
                                              contents_desc,
                                              except_keyword,
                                              contents_display,
                                              contents_westory_display, 
                                              contents_user_display, 
                                              contents_type_display, 
                                              contents_footer_display, 
                                              contents_temp, 
                                              contents_share_text, 
                                              contents_share_count, 
                                              req_data, 
                                              up_data,
                                              card_short_url,
                                              westory_card_url,
                                              public_display,
                                              card_idx,
                                              source_iframe,
                                              contents_order,
                                              gwc_con_state,
                                              landing_mode,
                                              share_send_cont,
                                              reduce_val,";
                    if ($group_id != "NULL") {
                        $sql2 .= "group_id,";
                    }
                    if ($product_seperate != "") {
                        $sql2 .= "product_code,
                                  product_model_name,
                                  product_seperate,
                                  send_provide_price,
                                  prod_manufact_price,
                                  send_salary_price,
                                  ai_map_gmarket,
                                  provider_req_prod,
                                  delivery_id_code,";
                    }
                    $sql2 .= "group_display) values 
                                              (\"{$row_share_new['mem_id']}\",
                                              \"$contents_type\", 
                                              \"$contents_img\", 
                                              \"$contents_title\", 
                                              \"$contents_url\", 
                                              \"$contents_url_title\", 
                                              \"$contents_iframe\", 
                                              \"$contents_price\", 
                                              \"$contents_sell_price\", 
                                              \"$contents_desc\",
                                              \"$except_keyword\",
                                              \"$contents_display\",
                                              \"$contents_westory_display\", 
                                              \"$contents_user_display\", 
                                              \"$contents_type_display\", 
                                              \"$contents_footer_display\", 
                                              \"$contents_temp\", 
                                              \"$contents_share_text\",
                                              \"$contents_share_count\",
                                              now(),
                                              now(),
                                              \"{$row_share_new['card_short_url']}\",
                                              \"{$row_share_new['card_short_url']}\",
                                              \"$public_display\",
                                              \"{$row_share_new['idx']}\",
                                              \"$source_iframe\",
                                              $cont_order,
                                              '$gwc_con_state',
                                              \"$landing_mode\",
                                              \"$contents_idx\",
                                              '$reduce_val',";
                    if ($group_id != "NULL") {
                        $sql2 .= "'$group_id',";
                    }
                    if ($product_seperate != "") {
                        $sql2 .= "'$product_code',
                                  '$product_model_name',
                                  '$product_seperate',
                                  '$send_provide_price',
                                  '$prod_manufact_price',
                                  '$send_salary_price',
                                  '3',
                                  '$req_provide',
                                  '$deliver_id_code',";
                    }
                    $sql2 .= " '$group_display'
                                              )";
                    $result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
                    $content_idx = mysqli_insert_id($self_con);
                    $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$content_idx,card_idx={$row_share_new['idx']},main_card={$row_share_new['idx']}";
                    mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
                }
            }
        }
        echo json_encode(array('idx' => $contents_idx, 'name' => $card_row['card_name'], 'result' => $msg));
        exit;
    } else {
        if ($contents_img_url) {
            $contents_img = $contents_img_url;
        } else {
            if ($media_type == "I")
                $total = count($_FILES['contents_img']['name']);
            else
                $total = 1;
            if ($total > 10)
                $total = 10;
            for ($i = 0; $i < $total; $i++) {
                //Get the temp file path
                if ($media_type == "I")
                    $tmpFilePath = $_FILES['contents_img']['tmp_name'][$i];
                else
                    $tmpFilePath = $_FILES['contents_vid']['tmp_name'][$i];
                //Make sure we have a filepath
                if ($tmpFilePath != "") {
                    //Setup our new file path
                    if ($media_type == "I")
                        //$date_file_name = date('dmYHis') . str_replace(" ", "", basename($_FILES["contents_img"]["name"][$i]));
                        $date_file_name = date('dmYHis') . $i . "." . pathinfo($_FILES["contents_img"]["name"][$i], PATHINFO_EXTENSION);
                    else
                        //$date_file_name = date('dmYHis') . str_replace(" ", "", basename($_FILES["contents_vid"]["name"][$i]));
                        $date_file_name = date('dmYHis') . $i . "." . pathinfo($_FILES["contents_vid"]["name"][$i], PATHINFO_EXTENSION);
                    $uploadfile = $uploaddir . basename($date_file_name);
                    //Upload the file into the temp dir
                    if (move_uploaded_file($tmpFilePath, $uploadfile)) {
                        $contents_img[$i] = $up_dir . basename($date_file_name);
                        if ($media_type == "I") {
                            $handle = new Image($uploadfile, 800);
                            $handle->resize();
                        }
                        if ($_POST['art_type'] > 0) {
                            $dstFile = addWatermark($uploadfile, "iamgallery", "../../iam/img/common/mark.png");
                            uploadFTP("../../".$dstFile);
                            $contents_img[$i] = "/".$dstFile;
                        }
                        uploadFTP($uploadfile);
                    }
                }
            }
            $contents_img = implode(",", $contents_img);
        }
        if ($contents_order == 0) {
            if ($group_id == "NULL")
                $sql = "select max(contents_order) from " . $contents_table_name . " where mem_id = '$mem_id' and card_idx = {$card_row['idx']}";
            else
                $sql = "select max(contents_order) from " . $contents_table_name . " where group_id = '$group_id' and card_idx = {$card_row['idx']}";
            $result = mysqli_query($self_con,$sql);
            $comment_row = mysqli_fetch_array($result);
            $contents_order = (int)$comment_row[0] + 1;
        } else {
            if ($group_id == "NULL")
                $sql = "update " . $contents_table_name . " set contents_order = contents_order + 1  where mem_id = '$mem_id' and card_idx = {$card_row['idx']} and contents_order >= $contents_order";
            else
                $sql = "update " . $contents_table_name . " set contents_order = contents_order + 1  where group_id = '$group_id' and card_idx = {$card_row['idx']} and contents_order >= $contents_order";
            mysqli_query($self_con,$sql);
        }
        $contents_share_text = $contents_share_id;
        if ($contents_share_text == "")
            $contents_share_count = 0;
        else {
            $contents_share_ids = explode(",", $contents_share_id);
            $contents_share_count = count($contents_share_ids);
        }
        if ($contents_type == 3) {
            $sql2 = "select special_type from Gn_Member where mem_id = '$mem_id'";
            $spec_res = mysqli_query($self_con,$sql2);
            $spec_row = mysqli_fetch_assoc($spec_res);
            if (!strstr($spec_row['special_type'], "1")) {
                $special_arr = explode(",", $spec_row['special_type']);
                array_push($special_arr, 1);
                $special_arr = array_unique($special_arr);
                $special_type = implode(",", $special_arr);
                $sql2 = "update Gn_Member set special_type = '{$special_type}' where mem_id = '$mem_id'";
                mysqli_query($self_con,$sql2);
            }
        }
        if ($_POST['market_reg'] == 'true' && $contents_table_name == "Gn_Iam_Contents" && $contents_type == 3) {
            $sql = "insert into Gn_Iam_Mall (mem_id,mall_type,title,sub_title,img,description,keyword,price,sell_price,display_status,reg_date,card_idx) " .
                "values ('$mem_id',4,'$contents_title','','$contents_img','$contents_desc','','$contents_price','$contents_sell_price',1,now(),0)";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $content_idx = mysqli_insert_id($self_con);
            echo json_encode(array('idx' => $content_idx, 'result' => '성공적으로 등록되었습니다.'));
            exit;
        } else {
            $sql2 = "insert into " . $contents_table_name . " (mem_id,contents_type,contents_img,contents_title,contents_url,contents_url_title, 
                                                contents_iframe, contents_price,contents_sell_price,contents_desc,except_keyword,
                                                contents_display,contents_westory_display,contents_user_display,contents_type_display, 
                                                contents_footer_display, contents_temp,contents_share_text,contents_share_count,req_data,up_data,
                                                card_short_url,westory_card_url,public_display,card_idx,source_iframe,contents_order,landing_mode,
                                                gwc_con_state,reduce_val,media_type,";
            if ($group_id != "NULL") {
                $sql2 .= "group_id,";
            }
            if ($product_seperate != "") {
                $sql2 .= "product_code,
                        product_model_name,
                        product_seperate,
                        send_provide_price,
                        prod_manufact_price,
                        send_salary_price,
                        ai_map_gmarket,
                        provider_req_prod,
                        delivery_id_code,";
            }
            $sql2 .= "group_display) values (\"$mem_id\",\"$contents_type\",\"$contents_img\",\"$contents_title\",\"$contents_url\",\"$contents_url_title\", 
                                                \"$contents_iframe\", \"$contents_price\",\"$contents_sell_price\",\"$contents_desc\",\"$except_keyword\",
                                                \"$contents_display\",\"$contents_westory_display\",\"$contents_user_display\",\"$contents_type_display\", 
                                                \"$contents_footer_display\", \"$contents_temp\",\"$contents_share_text\",$contents_share_count,now(),now(),
                                                \"$card_short_url\",\"$westory_card_url\",\"$public_display\",\"{$card_row['idx']}\",\"$source_iframe\",$contents_order,\"$landing_mode\",
                                                '$gwc_con_state','$reduce_val','$media_type',";
            if ($group_id != "NULL") {
                $sql2 .= "'$group_id',";
            }
            if ($product_seperate != "") {
                $sql2 .= "'$product_code',
                        '$product_model_name',
                        '$product_seperate',
                        '$send_provide_price',
                        '$prod_manufact_price',
                        '$send_salary_price',
                        '3',
                        '$req_provide',
                        '$deliver_id_code',";
            }
            $sql2 .= "'$group_display')";
            mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));

            $content_idx = mysqli_insert_id($self_con);
            foreach ($card_array as $card_link) {
                $sql1 = "select idx from Gn_Iam_Name_Card where card_short_url = '$card_link'";
                $res1 = mysqli_query($self_con,$sql1);
                $row1 = mysqli_fetch_array($res1);
                $card = $row1['idx'];
                $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$content_idx,card_idx=$card,main_card={$card_row['idx']}";
                mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
            }
            if ($_POST['art_type'] > 0 && $contents_table_name == "Gn_Iam_Contents" && $contents_type == 3) {
                $sql = "insert into Gn_Iam_Mall (mem_id,mall_type,title,sub_title,img,description,keyword,price,sell_price,display_status,reg_date,card_idx) " .
                    "values ('$mem_id',{$_POST['art_type']},'$contents_title','','$contents_img','$contents_desc','','$contents_price','$contents_sell_price',1,now(),{$content_idx})";
                mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            }
            if ($cnt_share_new) {
                while ($row_share_new = mysqli_fetch_array($res_share_new)) {
                    if ($contents_order == 0) {
                        if ($group_id == "NULL")
                            $sql = "select max(contents_order) from " . $contents_table_name . " where card_idx = {$row_share_new['idx']}";
                        else
                            $sql = "select max(contents_order) from " . $contents_table_name . " where group_id = '$group_id' and card_idx = {$row_share_new['idx']}";
                        $result = mysqli_query($self_con,$sql);
                        $comment_row = mysqli_fetch_array($result);
                        $contents_order = (int)$comment_row[0] + 1;
                    } else {
                        if ($group_id == "NULL")
                            $sql = "update " . $contents_table_name . " set contents_order = contents_order + 1  where card_idx = {$row_share_new['idx']} and contents_order >= $contents_order";
                        else
                            $sql = "update " . $contents_table_name . " set contents_order = contents_order + 1  where group_id = '$group_id' and card_idx = {$row_share_new['idx']} and contents_order >= $contents_order";
                        mysqli_query($self_con,$sql);
                    }
                    $contents_share_text = $contents_share_id;
                    if ($contents_share_text == "")
                        $contents_share_count = 0;
                    else {
                        $contents_share_ids = explode(",", $contents_share_id);
                        $contents_share_count = count($contents_share_ids);
                    }
                    $sql2 = "insert into " . $contents_table_name . " (mem_id,contents_type,contents_img,contents_title,contents_url,contents_url_title,
                                                        contents_iframe, contents_price,contents_sell_price,contents_desc,except_keyword,
                                                        contents_display,contents_westory_display,contents_user_display,contents_type_display, 
                                                        contents_footer_display, contents_temp,contents_share_text,contents_share_count,req_data,up_data,
                                                        card_short_url,westory_card_url,public_display,card_idx,source_iframe,contents_order,landing_mode,
                                                        gwc_con_state,share_send_cont,reduce_val,media_type,";
                    if ($group_id != "NULL") {
                        $sql2 .= "group_id,";
                    }
                    if ($product_seperate != "") {
                        $sql2 .= "product_code,
                                product_model_name,
                                product_seperate,
                                send_provide_price,
                                prod_manufact_price,
                                send_salary_price,
                                ai_map_gmarket,
                                provider_req_prod,
                                delivery_id_code,";
                    }
                    $sql2 .= "group_display) values (\"{$row_share_new['mem_id']}\",\"$contents_type\",\"$contents_img\",\"$contents_title\",\"$contents_url\",\"$contents_url_title\", 
                                                        \"$contents_iframe\", \"$contents_price\",\"$contents_sell_price\",\"$contents_desc\",\"$except_keyword\",
                                                        \"$contents_display\",\"$contents_westory_display\",\"$contents_user_display\",\"$contents_type_display\", 
                                                        \"$contents_footer_display\", \"$contents_temp\",\"$contents_share_text\",$contents_share_count,now(),now(),
                                                        \"{$row_share_new['card_short_url']}\",\"{$row_share_new['card_short_url']}\",\"$public_display\",\"{$row_share_new['idx']}\",\"$source_iframe\",$contents_order,\"$landing_mode\",
                                                        '$gwc_con_state','$contents_idx','$reduce_val','$media_type',";
                    if ($group_id != "NULL") {
                        $sql2 .= "'$group_id',";
                    }
                    if ($product_seperate != "") {
                        $sql2 .= "'$product_code',
                                '$product_model_name',
                                '$product_seperate',
                                '$send_provide_price',
                                '$prod_manufact_price',
                                '$send_salary_price',
                                '3',
                                '$req_provide',
                                '$deliver_id_code',";
                    }
                    $sql2 .= "'$group_display')";
                    mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
                    $content_idx = mysqli_insert_id($self_con);
                    $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$content_idx,card_idx={$row_share_new['idx']},main_card={$row_share_new['idx']}";
                    mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
                }
            }
            echo json_encode(array('idx' => $contents_idx, 'name' => $card_row['card_name'], 'result' => $msg));
            exit;
        }
    }
}
// ==================================================================================
// Update
else if ($post_type == "edit") {
    $query = "select iam_share_cnt from Gn_Member where mem_id='$mem_id'";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    $info = explode(",", $contents_share_id);
    if ($contents_share_id != "" && $row[0] < count($info)) {
        echo json_encode(array('idx' => 0, 'result' => "콘텐츠 전송건수를 초과하였습니다."));
        exit;
    }
    if ($contents_iframe) {
        $contents_iframe_msg = ", contents_iframe = '$contents_iframe'";
    } else {
        $contents_iframe_msg = "";
    }

    if ($source_iframe) {
        $contents_iframe_msg = ", source_iframe = '$source_iframe'";
    }
    
    $init_reduce_val = 100 - ($contents_sell_price / $contents_price) * 100;
    $init_reduce_val = (int)$init_reduce_val;

    if ($contents_table_name == "Gn_Iam_Contents_Gwc") {
        $str_up = "prod_manufact_price = '$prod_manufact_price', delivery_id_code='$deliver_id_code', ";
    } else {
        $str_up = "";
    }

    if ($contents_idx == 0)
        $cnt_chk = 0;
    else {
        $sql_chk = "select idx from " . $contents_table_name . " where share_send_cont='$contents_idx'";
        $res_chk = mysqli_query($self_con,$sql_chk);
        $cnt_chk = mysqli_num_rows($res_chk);
    }
    if ($contents_img_url) {
        $old_sql = "select card_idx,card_short_url from " . $contents_table_name . " where idx = '$contents_idx'";
        $old_res = mysqli_query($self_con,$old_sql);
        $old_row = mysqli_fetch_array($old_res);
        $mall_img = $contents_img_url;
        $sql2 = "update " . $contents_table_name . " set contents_type = '$contents_type',
                                    media_type = '$media_type',
                                    card_short_url = '$card_short_url',
                                    westory_card_url = '$westory_card_url',
                                    contents_img = \"$contents_img_url\",
                                    contents_title = \"$contents_title\",
                                    contents_url = \"$contents_url\",
                                    contents_url_title = \"$contents_url_title\",
                                    contents_price = \"$contents_price\",
                                    contents_sell_price = \"$contents_sell_price\",
                                    contents_desc = \"$contents_desc\",
                                    except_keyword = \"$except_keyword\",
                                    contents_display = \"$contents_display\",
                                    contents_westory_display = \"$contents_westory_display\",
                                    contents_user_display = \"$contents_user_display\",
                                    contents_type_display = \"$contents_type_display\",
                                    contents_footer_display = \"$contents_footer_display\",
                                    contents_share_text = \"$contents_share_id\",
                                    card_idx = \"{$card_row['idx']}\",
                                    open_type = '$open_type',
                                    gwc_con_state = '$gwc_con_state',
                                    product_code = '$product_code',
                                    product_model_name = '$product_model_name',
                                    product_seperate = '$product_seperate',
                                    send_provide_price = '$send_provide_price',
                                    send_salary_price = '$send_salary_price',
                                    $str_up
                                    init_reduce_val = '$init_reduce_val',
                                    up_data = now(),
                                    reduce_val = '$reduce_val',
                                    landing_mode = \"$landing_mode\" $contents_iframe_msg where idx = '$contents_idx' and mem_id = '$mem_id' ";
        mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        $sql = "update Gn_Iam_Con_Card set main_card = {$card_row['idx']} where cont_idx = $content_idx and main_card = {$old_row['card_idx']}";
        mysqli_query($self_con,$sql);
        $old_card_array = explode(",", $old_row['card_short_url']);
        foreach (array_diff($old_card_array, $card_array) as $card_link) {
            $sql1 = "select idx from Gn_Iam_Name_Card where card_short_url = '$card_link'";
            $res1 = mysqli_query($self_con,$sql1);
            $row1 = mysqli_fetch_array($res1);
            $card = $row1['idx'];
            $sql2 = "delete from Gn_Iam_Con_Card where cont_idx='$contents_idx' and card_idx='{$card}'";
            mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        }
        foreach (array_diff($card_array, $old_card_array) as $card_link) {
            $sql1 = "select idx from Gn_Iam_Name_Card where card_short_url = '$card_link'";
            $res1 = mysqli_query($self_con,$sql1);
            $row1 = mysqli_fetch_array($res1);
            $card = $row1['idx'];
            $sql2 = "insert into Gn_Iam_Con_Card set cont_idx='$contents_idx',card_idx='$card',main_card={$card_row['idx']}";
            mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        }

        if ($cnt_chk) {
            while ($row_chk = mysqli_fetch_array($res_chk)) {
                $sql_share_con = "select card_short_url, westory_card_url, card_idx from " . $contents_table_name . " where idx='{$row_chk['idx']}'";
                $res_share_con = mysqli_query($self_con,$sql_share_con);
                $row_share_con = mysqli_fetch_array($res_share_con);
                $sql2 = "update " . $contents_table_name . " set contents_type = '$contents_type',
                                    media_type = '$media_type',
                                    card_short_url = '{$row_share_con['card_short_url']}',
                                    westory_card_url = '$row_share_con[westory_card_url]',
                                    contents_img = \"$contents_img_url\",
                                    contents_title = \"$contents_title\",
                                    contents_url = \"$contents_url\",
                                    contents_url_title = \"$contents_url_title\",
                                    contents_price = \"$contents_price\",
                                    contents_sell_price = \"$contents_sell_price\",
                                    contents_desc = \"$contents_desc\",
                                    except_keyword = \"$except_keyword\",
                                    contents_display = \"$contents_display\",
                                    contents_westory_display = \"$contents_westory_display\",
                                    contents_user_display = \"$contents_user_display\",
                                    contents_type_display = \"$contents_type_display\",
                                    contents_footer_display = \"$contents_footer_display\",
                                    contents_share_text = \"$contents_share_id\",
                                    card_idx = \"{$row_share_con['card_idx']}\",
                                    open_type = '$open_type',
                                    gwc_con_state = '$gwc_con_state',
                                    product_code = '$product_code',
                                    product_model_name = '$product_model_name',
                                    product_seperate = '$product_seperate',
                                    send_provide_price = '$send_provide_price',
                                    send_salary_price = '$send_salary_price',
                                    $str_up
                                    init_reduce_val = '$init_reduce_val',
                                    up_data = now(),
                                    reduce_val = '$reduce_val',
                                    landing_mode = \"$landing_mode\" $contents_iframe_msg where idx = {$row_chk['idx']}";
                mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
            }
        }
    } else if (count($_FILES['contents_img']['name']) > 0) {
        $total = count($_FILES['contents_img']['name']);
        if ($total > 10)
            $total = 10;
        for ($i = 0; $i < $total; $i++) {
            //Get the temp file path
            $tmpFilePath = $_FILES['contents_img']['tmp_name'][$i];
            //Make sure we have a filepath
            if ($tmpFilePath != "") {
                //Setup our new file path
                //$date_file_name = date('dmYHis').str_replace(" ", "", basename($_FILES["contents_img"]["name"][$i]));
                $date_file_name = date('dmYHis') . $i . "." . pathinfo($_FILES["contents_img"]["name"][$i], PATHINFO_EXTENSION);
                $uploadfile = $uploaddir . basename($date_file_name);
                //Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $uploadfile)) {
                    $contents_img[$i] = $up_dir . basename($date_file_name);
                    $handle = new Image($uploadfile, 800);
                    $handle->resize();
                    uploadFTP($uploadfile);
                }
            }
        }
        $mall_img = $contents_img[0];
        $contents_img = implode(",", $contents_img);
        $old_sql = "select card_idx,card_short_url from " . $contents_table_name . " where idx = '$contents_idx'";
        $old_res = mysqli_query($self_con,$old_sql);
        $old_row = mysqli_fetch_array($old_res);
        $sql2 = "update " . $contents_table_name . " set contents_type = \"$contents_type\",
                                        media_type = '$media_type',
                                        card_short_url = \"$card_short_url\",
                                        westory_card_url = \"$westory_card_url\",
                                        contents_img = \"$contents_img\",
                                        contents_title = \"$contents_title\",
                                        contents_url = \"$contents_url\",
                                        contents_url_title = \"$contents_url_title\",
                                        contents_price = \"$contents_price\",
                                        contents_sell_price = \"$contents_sell_price\",
                                        contents_desc = \"$contents_desc\",
                                        except_keyword = \"$except_keyword\",
                                        contents_display = \"$contents_display\",
                                        contents_westory_display = \"$contents_westory_display\",
                                        contents_user_display = \"$contents_user_display\",
                                        contents_type_display = \"$contents_type_display\",
                                        contents_footer_display = \"$contents_footer_display\",
                                        contents_share_text = \"$contents_share_id\",
                                        card_idx = \"{$card_row['idx']}\",
                                        open_type = \"$open_type\",
                                        gwc_con_state = '$gwc_con_state',
                                        product_code = '$product_code',
                                        product_model_name = '$product_model_name',
                                        product_seperate = '$product_seperate',
                                        send_provide_price = '$send_provide_price',
                                        send_salary_price = '$send_salary_price',
                                        $str_up
                                        init_reduce_val = \"$init_reduce_val\",
                                        up_data = now(),
                                        reduce_val = '$reduce_val',
                                        landing_mode = \"$landing_mode\" $contents_iframe_msg where idx = '$contents_idx' and mem_id = '$mem_id' ";
        mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        $sql = "update Gn_Iam_Con_Card set main_card = {$card_row['idx']} where cont_idx = $content_idx and main_card = {$old_row['card_idx']}";
        mysqli_query($self_con,$sql);
        $old_card_array = explode(",", $old_row['card_short_url']);
        foreach (array_diff($old_card_array, $card_array) as $card_link) {
            $sql1 = "select idx from Gn_Iam_Name_Card where card_short_url = '$card_link'";
            $res1 = mysqli_query($self_con,$sql1);
            $row1 = mysqli_fetch_array($res1);
            $card = $row1['idx'];
            $sql2 = "delete from Gn_Iam_Con_Card where cont_idx='$contents_idx' and card_idx='{$card}'";
            mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        }
        foreach (array_diff($card_array, $old_card_array) as $card_link) {
            $sql1 = "select idx from Gn_Iam_Name_Card where card_short_url = '$card_link'";
            $res1 = mysqli_query($self_con,$sql1);
            $row1 = mysqli_fetch_array($res1);
            $card = $row1['idx'];
            $sql2 = "insert into Gn_Iam_Con_Card set cont_idx='$contents_idx',card_idx='$card',main_card={$card_row['idx']}";
            mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        }
        if ($cnt_chk) {
            while ($row_chk = mysqli_fetch_array($res_chk)) {
                $sql_share_con = "select card_short_url, westory_card_url, card_idx from " . $contents_table_name . " where idx='{$row_chk['idx']}'";
                $res_share_con = mysqli_query($self_con,$sql_share_con);
                $row_share_con = mysqli_fetch_array($res_share_con);
                $sql2 = "update " . $contents_table_name . " set contents_type = \"$contents_type\",
                                        media_type = '$media_type',
                                        card_short_url = \"{$row_share_con['card_short_url']}\",
                                        westory_card_url = \"$row_share_con[westory_card_url]\",
                                        contents_img = \"$contents_img\",
                                        contents_title = \"$contents_title\",
                                        contents_url = \"$contents_url\",
                                        contents_url_title = \"$contents_url_title\",
                                        contents_price = \"$contents_price\",
                                        contents_sell_price = \"$contents_sell_price\",
                                        contents_desc = \"$contents_desc\",
                                        except_keyword = \"$except_keyword\",
                                        contents_display = \"$contents_display\",
                                        contents_westory_display = \"$contents_westory_display\",
                                        contents_user_display = \"$contents_user_display\",
                                        contents_type_display = \"$contents_type_display\",
                                        contents_footer_display = \"$contents_footer_display\",
                                        contents_share_text = \"$contents_share_id\",
                                        card_idx = \"{$row_share_con['card_idx']}\",
                                        open_type = \"$open_type\",
                                        gwc_con_state = '$gwc_con_state',
                                        product_code = '$product_code',
                                        product_model_name = '$product_model_name',
                                        product_seperate = '$product_seperate',
                                        send_provide_price = '$send_provide_price',
                                        send_salary_price = '$send_salary_price',
                                        $str_up
                                        init_reduce_val = \"$init_reduce_val\",
                                        up_data = now(),
                                        reduce_val = '$reduce_val',
                                        landing_mode = \"$landing_mode\" $contents_iframe_msg where idx = {$row_chk['idx']} ";
                mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
            }
        }
    } else if (count($_FILES['contents_vid']['name']) > 0) {
        $total = count($_FILES['contents_vid']['name']);
        if ($total > 10)
            $total = 10;
        for ($i = 0; $i < $total; $i++) {
            //Get the temp file path
            $tmpFilePath = $_FILES['contents_vid']['tmp_name'][$i];
            //Make sure we have a filepath
            if ($tmpFilePath != "") {
                //Setup our new file path
                //$date_file_name = date('dmYHis').str_replace(" ", "", basename($_FILES["contents_img"]["name"][$i]));
                $date_file_name = date('dmYHis') . $i . "." . pathinfo($_FILES["contents_vid"]["name"][$i], PATHINFO_EXTENSION);
                $uploadfile = $uploaddir . basename($date_file_name);
                //Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $uploadfile)) {
                    $contents_img[$i] = $up_dir . basename($date_file_name);
                    uploadFTP($uploadfile);
                }
            }
        }
        $mall_img = $contents_img[0];
        $contents_img = implode(",", $contents_img);
        $old_sql = "select card_idx,card_short_url from " . $contents_table_name . " where idx = '$contents_idx'";
        $old_res = mysqli_query($self_con,$old_sql);
        $old_row = mysqli_fetch_array($old_res);
        $sql2 = "update " . $contents_table_name . " set contents_type = \"$contents_type\",
                                        media_type = '$media_type',
                                        card_short_url = \"$card_short_url\",
                                        westory_card_url = \"$westory_card_url\",
                                        contents_img = \"$contents_img\",
                                        contents_title = \"$contents_title\",
                                        contents_url = \"$contents_url\",
                                        contents_url_title = \"$contents_url_title\",
                                        contents_price = \"$contents_price\",
                                        contents_sell_price = \"$contents_sell_price\",
                                        contents_desc = \"$contents_desc\",
                                        except_keyword = \"$except_keyword\",
                                        contents_display = \"$contents_display\",
                                        contents_westory_display = \"$contents_westory_display\",
                                        contents_user_display = \"$contents_user_display\",
                                        contents_type_display = \"$contents_type_display\",
                                        contents_footer_display = \"$contents_footer_display\",
                                        contents_share_text = \"$contents_share_id\",
                                        card_idx = \"{$card_row['idx']}\",
                                        open_type = \"$open_type\",
                                        gwc_con_state = '$gwc_con_state',
                                        product_code = '$product_code',
                                        product_model_name = '$product_model_name',
                                        product_seperate = '$product_seperate',
                                        send_provide_price = '$send_provide_price',
                                        send_salary_price = '$send_salary_price',
                                        $str_up
                                        init_reduce_val = \"$init_reduce_val\",
                                        up_data = now(),
                                        reduce_val = '$reduce_val',
                                        landing_mode = \"$landing_mode\" $contents_iframe_msg where idx = '$contents_idx' and mem_id = '$mem_id' ";
        mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        $sql = "update Gn_Iam_Con_Card set main_card = {$card_row['idx']} where cont_idx = $content_idx and main_card = {$old_row['card_idx']}";
        mysqli_query($self_con,$sql);
        $old_card_array = explode(",", $old_row['card_short_url']);
        foreach (array_diff($old_card_array, $card_array) as $card_link) {
            $sql1 = "select idx from Gn_Iam_Name_Card where card_short_url = '$card_link'";
            $res1 = mysqli_query($self_con,$sql1);
            $row1 = mysqli_fetch_array($res1);
            $card = $row1['idx'];
            $sql2 = "delete from Gn_Iam_Con_Card where cont_idx='$contents_idx' and card_idx='{$card}'";
            mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        }
        foreach (array_diff($card_array, $old_card_array) as $card_link) {
            $sql1 = "select idx from Gn_Iam_Name_Card where card_short_url = '$card_link'";
            $res1 = mysqli_query($self_con,$sql1);
            $row1 = mysqli_fetch_array($res1);
            $card = $row1['idx'];
            $sql2 = "insert into Gn_Iam_Con_Card set cont_idx='$contents_idx',card_idx='$card',main_card={$card_row['idx']}";
            mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        }
        if ($cnt_chk) {
            while ($row_chk = mysqli_fetch_array($res_chk)) {
                $sql_share_con = "select card_short_url, westory_card_url, card_idx from " . $contents_table_name . " where idx='{$row_chk['idx']}'";
                $res_share_con = mysqli_query($self_con,$sql_share_con);
                $row_share_con = mysqli_fetch_array($res_share_con);
                $sql2 = "update " . $contents_table_name . " set contents_type = \"$contents_type\",
                                        media_type = '$media_type',
                                        card_short_url = \"{$row_share_con['card_short_url']}\",
                                        westory_card_url = \"$row_share_con[westory_card_url]\",
                                        contents_img = \"$contents_img\",
                                        contents_title = \"$contents_title\",
                                        contents_url = \"$contents_url\",
                                        contents_url_title = \"$contents_url_title\",
                                        contents_price = \"$contents_price\",
                                        contents_sell_price = \"$contents_sell_price\",
                                        contents_desc = \"$contents_desc\",
                                        except_keyword = \"$except_keyword\",
                                        contents_display = \"$contents_display\",
                                        contents_westory_display = \"$contents_westory_display\",
                                        contents_user_display = \"$contents_user_display\",
                                        contents_type_display = \"$contents_type_display\",
                                        contents_footer_display = \"$contents_footer_display\",
                                        contents_share_text = \"$contents_share_id\",
                                        card_idx = \"{$row_share_con['card_idx']}\",
                                        open_type = \"$open_type\",
                                        gwc_con_state = '$gwc_con_state',
                                        product_code = '$product_code',
                                        product_model_name = '$product_model_name',
                                        product_seperate = '$product_seperate',
                                        send_provide_price = '$send_provide_price',
                                        send_salary_price = '$send_salary_price',
                                        $str_up
                                        init_reduce_val = \"$init_reduce_val\",
                                        up_data = now(),
                                        reduce_val = '$reduce_val',
                                        landing_mode = \"$landing_mode\" $contents_iframe_msg where idx = {$row_chk['idx']} ";
                mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
            }
        }
    } else {
        $old_sql = "select card_idx,card_short_url from " . $contents_table_name . " where idx = '$contents_idx'";
        $old_res = mysqli_query($self_con,$old_sql);
        $old_row = mysqli_fetch_array($old_res);
        $sql2 = "update " . $contents_table_name . " set contents_type = \"$contents_type\",
                                    media_type = '$media_type',
                                    card_short_url = \"$card_short_url\",
                                    contents_img = \"\",
                                    westory_card_url = \"$westory_card_url\",
                                    contents_title = \"$contents_title\",
                                    contents_url = \"$contents_url\",
                                    contents_url_title = \"$contents_url_title\",
                                    contents_price = \"$contents_price\",
                                    contents_sell_price = \"$contents_sell_price\",
                                    contents_desc = \"$contents_desc\",
                                    except_keyword = \"$except_keyword\",
                                    contents_display = \"$contents_display\",
                                    contents_westory_display = \"$contents_westory_display\",
                                    contents_user_display = \"$contents_user_display\",
                                    contents_type_display = \"$contents_type_display\",
                                    contents_footer_display = \"$contents_footer_display\",
                                    contents_share_text = \"$contents_share_id\",
                                    card_idx = \"{$card_row['idx']}\",
                                    open_type = '$open_type',
                                    gwc_con_state = '$gwc_con_state',
                                    product_code = '$product_code',
                                    product_model_name = '$product_model_name',
                                    product_seperate = '$product_seperate',
                                    send_provide_price = '$send_provide_price',
                                    send_salary_price = '$send_salary_price',
                                    $str_up
                                    init_reduce_val = '$init_reduce_val',
                                    up_data = now(),
                                    reduce_val = '$reduce_val',
                                    landing_mode = \"$landing_mode\" $contents_iframe_msg where idx = '$contents_idx' and mem_id = '$mem_id' ";
        mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        $sql = "update Gn_Iam_Con_Card set main_card = {$card_row['idx']} where cont_idx = $content_idx and main_card = {$old_row['card_idx']}";
        mysqli_query($self_con,$sql);
        $old_card_array = explode(",", $old_row['card_short_url']);
        foreach (array_diff($old_card_array, $card_array) as $card_link) {
            $sql1 = "select idx from Gn_Iam_Name_Card where card_short_url = '$card_link'";
            $res1 = mysqli_query($self_con,$sql1);
            $row1 = mysqli_fetch_array($res1);
            $card = $row1['idx'];
            $sql2 = "delete from Gn_Iam_Con_Card where cont_idx='$contents_idx' and card_idx='{$card}'";
            mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        }
        foreach (array_diff($card_array, $old_card_array) as $card_link) {
            $sql1 = "select idx from Gn_Iam_Name_Card where card_short_url = '$card_link'";
            $res1 = mysqli_query($self_con,$sql1);
            $row1 = mysqli_fetch_array($res1);
            $card = $row1['idx'];
            $sql2 = "insert into Gn_Iam_Con_Card set cont_idx='$contents_idx', card_idx='$card', main_card={$card_row['idx']}";
            mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        }
        if ($cnt_chk) {
            while ($row_chk = mysqli_fetch_array($res_chk)) {
                $sql_share_con = "select card_short_url, westory_card_url, card_idx from " . $contents_table_name . " where idx='{$row_chk['idx']}'";
                $res_share_con = mysqli_query($self_con,$sql_share_con);
                $row_share_con = mysqli_fetch_array($res_share_con);
                $sql2 = "update " . $contents_table_name . " set contents_type = \"$contents_type\",
                                    media_type = '$media_type',
                                    card_short_url = \"{$row_share_con['card_short_url']}\",
                                    contents_img = \"\",
                                    westory_card_url = \"$row_share_con[westory_card_url]\",
                                    contents_title = \"$contents_title\",
                                    contents_url = \"$contents_url\",
                                    contents_url_title = \"$contents_url_title\",
                                    contents_price = \"$contents_price\",
                                    contents_sell_price = \"$contents_sell_price\",
                                    contents_desc = \"$contents_desc\",
                                    except_keyword = \"$except_keyword\",
                                    contents_display = \"$contents_display\",
                                    contents_westory_display = \"$contents_westory_display\",
                                    contents_user_display = \"$contents_user_display\",
                                    contents_type_display = \"$contents_type_display\",
                                    contents_footer_display = \"$contents_footer_display\",
                                    contents_share_text = \"$contents_share_id\",
                                    card_idx = \"{$row_share_con['card_idx']}\",
                                    open_type = '$open_type',
                                    gwc_con_state = '$gwc_con_state',
                                    product_code = '$product_code',
                                    product_model_name = '$product_model_name',
                                    product_seperate = '$product_seperate',
                                    send_provide_price = '$send_provide_price',
                                    send_salary_price = '$send_salary_price',
                                    $str_up
                                    init_reduce_val = '$init_reduce_val',
                                    up_data = now(),
                                    reduce_val = '$reduce_val',
                                    landing_mode = \"$landing_mode\" $contents_iframe_msg where idx = {$row_chk['idx']}";
                mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
            }
        }
    }
    if (/*$_POST['art_type'] > 0 && */$contents_table_name == "Gn_Iam_Contents" && $contents_type == 3) {
        $mall_sql = "select idx,mall_type from Gn_Iam_Mall where card_idx= {$contents_idx} and mall_type > 10";
        $mall_res = mysqli_query($self_con,$mall_sql) or die(mysqli_error($self_con));
        $mall_row = mysqli_fetch_assoc($mall_res);
        if ($mall_row['idx'] != "") {
            if (strpos($mall_img, "/") == 0)
                $mall_img = substr($mall_img, 1);
            $mall_img = "../../" . $mall_img;
            if ($_POST['art_type'] == 0) {
                $sql = "delete from Gn_Iam_Mall where idx = {$mall_row['idx']}";
            } else {
                $dstFile = addWatermark($mall_img, "iamgallery", "../../iam/img/common/mark.png");
                uploadFTP("../../" . $dstFile);
                $mall_img = "/".$dstFile;
                $sql = "update " . $contents_table_name . " set contents_img = '{$mall_img}' where idx = {$contents_idx}";
                mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                $sql = "update Gn_Iam_Mall set mall_type={$_POST['art_type']},
                                            title='$contents_title',
                                            img='{$mall_img}',
                                            description='$contents_desc',
                                            price='$contents_price',
                                            sell_price='$contents_sell_price'
                                            where idx= {$mall_row['idx']}";
            }
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        } else if ($_POST['art_type'] > 0) {
            $dstFile = addWatermark($mall_img, "iamgallery", "../../iam/img/common/mark.png");
            uploadFTP("../../" . $dstFile);
            $mall_img = "/".$dstFile;
            $sql = "update " . $contents_table_name . " set contents_img = '{$mall_img}' where idx = {$contents_idx}";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $sql = "insert into Gn_Iam_Mall (mem_id,mall_type,title,img,description,price,sell_price,display_status,reg_date,card_idx) " .
                "values ('$mem_id',{$_POST['art_type']},'$contents_title','{$mall_img}','$contents_desc','$contents_price','$contents_sell_price',1,now(),{$contents_idx})";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        }
    }
    if ($_POST['admin']) {
        if ($_POST['provider'] == "Y") {
            if ($_POST['provider_iam'] == "Y") {
                echo '<script>alert("컨텐츠가 수정 되었습니다."); location.href="/iam/req_provider_list.php"</script>';
            } else {
                if ($_POST['member'] == "Y") {
                    echo '<script>alert("컨텐츠가 수정 되었습니다."); location.href="/admin/gwc_member_prod_list.php"</script>';
                } else {
                    echo '<script>alert("컨텐츠가 수정 되었습니다."); location.href="/admin/card_gwc_contents_list_provider.php"</script>';
                }
            }
        } else {
            echo '<script>alert("컨텐츠가 수정 되었습니다."); location.href="/admin/card_gwc_contents_list.php"</script>';
        }
        exit;
    }
    echo json_encode(array('idx' => 0, 'result' => '컨텐츠가 수정 되었습니다.'));
    exit;
}
// ==================================================================================
// Remove Shared link
else if ($post_type == "remove_shared_content") {
    $sql2 = "select contents_share_text from Gn_Iam_Contents where idx = '$contents_idx'";
    $result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
    $row = mysqli_fetch_array($result2);
    $share_ids = explode(",", $row['contents_share_text']);
    $del_ids = array($mem_id);
    $share_ids = array_diff($share_ids, $del_ids);
    $result = implode(",", $share_ids);
    $share_count = 0;
    if ($result != "")
        $share_count = count($share_ids);
    $sql2 = "update Gn_Iam_Contents set contents_share_text ='$result',contents_share_count = $share_count where idx = '$contents_idx'";
    mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
    echo '수신된 컨텐츠가 삭제 되었습니다.';
    exit;
}

// ==================================================================================
// Delete
else if ($post_type == "del") {
    $sql = "select contents_order,card_idx ,group_id from " . $contents_table_name . " where idx = '$contents_idx'";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    $contents_order = $row['contents_order'];
    $card_idx = $row['card_idx'];
    if ($row['group_id'] == "")
        $sql = "update " . $contents_table_name . " set contents_order = contents_order - 1  where mem_id = '$mem_id' and card_idx = '$card_idx' and contents_order > $contents_order";
    else
        $sql = "update " . $contents_table_name . " set contents_order = contents_order - 1  where group_id = '{$row['group_id']}' and card_idx = '$card_idx' and contents_order > $contents_order";
    mysqli_query($self_con,$sql);
    $sql = "delete from " . $contents_table_name . " where idx = '$contents_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $sql = "delete from Gn_Iam_Con_Card where cont_idx = '$contents_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $sql = "delete from Gn_Iam_Mall where card_idx = '$contents_idx' and (mall_type = 3 or mall_type = 4 or mall_type > 10)";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

    $sql_share = "select idx from " . $contents_table_name . " where share_send_cont='{$contents_idx}'";
    $res_share = mysqli_query($self_con,$sql_share);
    $cnt_share = mysqli_num_rows($res_share);
    if ($cnt_share) {
        while ($row_share = mysqli_fetch_array($res_share)) {
            $sql = "select contents_order,card_idx ,group_id from " . $contents_table_name . " where idx = {$row_share['idx']}";
            $res = mysqli_query($self_con,$sql);
            $row = mysqli_fetch_array($res);
            $contents_order = $row['contents_order'];
            $card_idx = $row['card_idx'];
            if ($row['group_id'] == "")
                $sql = "update " . $contents_table_name . " set contents_order = contents_order - 1  where card_idx = '$card_idx' and contents_order > $contents_order";
            else
                $sql = "update " . $contents_table_name . " set contents_order = contents_order - 1  where group_id = '{$row['group_id']}' and card_idx = '$card_idx' and contents_order > $contents_order";
            mysqli_query($self_con,$sql);

            $sql = "delete from " . $contents_table_name . " where idx = {$row_share['idx']}";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $sql = "delete from Gn_Iam_Con_Card where cont_idx = {$row_share['idx']}";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $sql = "delete from Gn_Iam_Mall where card_idx = {$row_share['idx']} and (mall_type = 3 or mall_type = 4)";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        }
    }
    echo '컨텐츠가 삭제 되었습니다.';
    exit;
}
// ==================================================================================
// 위로
else if ($post_type == "range_up") {
    $contents_order = $_POST['contents_order'];
    $card_url = $_POST['card_url'];
    $sql = "select max(contents_order) from " . $contents_table_name . " where mem_id = '$mem_id'  and card_short_url like '%$card_url%'";
    $result = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($result);
    $max_order = $row[0];
    if ($contents_order < $max_order) {
        $change_order = $contents_order + 1;
        $sql = "select idx from " . $contents_table_name . " where mem_id = '$mem_id'  and card_short_url like '%$card_url%' and contents_order = '$change_order'";
        $result = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($result);
        if ($row) {
            $contents_change_idx = $row['idx'];
            $sql = "update " . $contents_table_name . " set contents_order = '$contents_order' where idx = '$contents_change_idx' and mem_id = '$mem_id'";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

            $sql = "update " . $contents_table_name . " set contents_order = '$change_order' where idx = '$contents_idx' and mem_id = '$mem_id'";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        }
    }
    $sql_share = "select idx, card_idx from " . $contents_table_name . " where share_send_cont='{$contents_idx}'";
    $res_share = mysqli_query($self_con,$sql_share);
    $cnt_share = mysqli_num_rows($res_share);
    if ($cnt_share) {
        while ($row_share = mysqli_fetch_array($res_share)) {
            $sql = "select max(contents_order) from " . $contents_table_name . " where card_idx = '{$row_share['card_idx']}'";
            $result = mysqli_query($self_con,$sql);
            $row = mysqli_fetch_array($result);
            $max_order = $row[0];
            if ($contents_order < $max_order) {
                $change_order = $contents_order + 1;
                $sql = "select idx from " . $contents_table_name . " where card_idx = '{$row_share['card_idx']}' and contents_order = '$change_order'";
                $result = mysqli_query($self_con,$sql);
                $row = mysqli_fetch_array($result);
                if ($row) {
                    $contents_change_idx = $row['idx'];
                    $sql = "update " . $contents_table_name . " set contents_order = '$contents_order' where idx = '$contents_change_idx'";
                    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

                    $sql = "update " . $contents_table_name . " set contents_order = '$change_order' where idx = {$row_share['idx']}";
                    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                }
            }
        }
    }
    exit;
}
// ==================================================================================
// 맨 위로
else if ($post_type == "range_up_max") {
    $contents_order = $_POST['contents_order'];
    $card_url = $_POST['card_url'];
    $sql = "select max(contents_order) from " . $contents_table_name . " where mem_id = '$mem_id'  and card_short_url like '%$card_url%'";
    $result = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($result);
    $max_order = $row[0];

    $sql = "select idx from " . $contents_table_name . " where mem_id = '$mem_id'  and card_short_url like '%$card_url%' and contents_order = '$max_order'";
    $result = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($result);
    if ($row) {
        $sql = "update " . $contents_table_name . " set contents_order = contents_order - 1 where mem_id = '$mem_id'  and card_short_url like '%$card_url%' and contents_order > '$contents_order'";
        mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

        $sql = "update " . $contents_table_name . " set contents_order = '$max_order' where idx = '$contents_idx' and mem_id = '$mem_id'";
        mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    }
    $sql_share = "select idx, card_idx from " . $contents_table_name . " where share_send_cont='{$contents_idx}'";
    $res_share = mysqli_query($self_con,$sql_share);
    $cnt_share = mysqli_num_rows($res_share);
    if ($cnt_share) {
        while ($row_share = mysqli_fetch_array($res_share)) {
            $sql = "select max(contents_order) from " . $contents_table_name . " where card_idx = '{$row_share['card_idx']}'";
            $result = mysqli_query($self_con,$sql);
            $row = mysqli_fetch_array($result);
            $max_order = $row[0];

            $sql = "select idx from " . $contents_table_name . " where card_idx = '{$row_share['card_idx']}' and contents_order = '$max_order'";
            $result = mysqli_query($self_con,$sql);
            $row = mysqli_fetch_array($result);
            if ($row) {
                $sql = "update " . $contents_table_name . " set contents_order = contents_order - 1 where card_idx = '{$row_share['card_idx']}' and contents_order > '$contents_order'";
                mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

                $sql = "update " . $contents_table_name . " set contents_order = '$max_order' where idx = {$row_share['idx']}";
                mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            }
        }
    }
    exit;
}
// ==================================================================================
// 아래로
else if ($post_type == "range_down") {
    $contents_order = $_POST['contents_order'];
    $card_url = $_POST['card_url'];
    $sql = "select min(contents_order) from " . $contents_table_name . " where mem_id = '$mem_id'  and card_short_url like '%$card_url%'";
    $result = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($result);
    $min_order = $row[0];
    if ($contents_order > $min_order) {
        $change_order = $contents_order - 1;
        $sql = "select idx from " . $contents_table_name . " where mem_id = '$mem_id'  and card_short_url like '%$card_url%' and contents_order = '$change_order'";
        $result = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($result);
        if ($row) {
            $contents_change_idx = $row['idx'];
            $sql = "update " . $contents_table_name . " set contents_order = '$contents_order' where idx = '$contents_change_idx' and mem_id = '$mem_id'";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

            $sql = "update " . $contents_table_name . " set contents_order = '$change_order' where idx = '$contents_idx' and mem_id = '$mem_id'";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        }
    }
    $sql_share = "select idx, card_idx from " . $contents_table_name . " where share_send_cont='{$contents_idx}'";
    $res_share = mysqli_query($self_con,$sql_share);
    $cnt_share = mysqli_num_rows($res_share);
    if ($cnt_share) {
        while ($row_share = mysqli_fetch_array($res_share)) {
            $sql = "select min(contents_order) from " . $contents_table_name . " where card_idx = '{$row_share['card_idx']}'";
            $result = mysqli_query($self_con,$sql);
            $row = mysqli_fetch_array($result);
            $min_order = $row[0];
            if ($contents_order > $min_order) {
                $change_order = $contents_order - 1;
                $sql = "select idx from " . $contents_table_name . " where card_idx = '{$row_share['card_idx']}' and contents_order = '$change_order'";
                $result = mysqli_query($self_con,$sql);
                $row = mysqli_fetch_array($result);
                if ($row) {
                    $contents_change_idx = $row['idx'];
                    $sql = "update " . $contents_table_name . " set contents_order = '$contents_order' where idx = '$contents_change_idx'";
                    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

                    $sql = "update " . $contents_table_name . " set contents_order = '$change_order' where idx = {$row_share['idx']}";
                    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                }
            }
        }
    }
    exit;
}
// ==================================================================================
// 맨 아래로
else if ($post_type == "range_down_min") {
    $contents_order = $_POST['contents_order'];
    $card_url = $_POST['card_url'];
    $sql = "select min(contents_order) from " . $contents_table_name . " where mem_id = '$mem_id'  and card_short_url like '%$card_url%'";
    $result = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($result);
    $min_order = $row[0];

    $sql = "select idx from " . $contents_table_name . " where mem_id = '$mem_id'  and card_short_url like '%$card_url%' and contents_order = '$min_order'";
    $result = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($result);
    if ($row) {
        $sql = "update " . $contents_table_name . " set contents_order = contents_order + 1 where mem_id = '$mem_id'  and card_short_url like '%$card_url%' and contents_order < '$contents_order'";
        mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

        $sql = "update " . $contents_table_name . " set contents_order = '$min_order' where idx = '$contents_idx' and mem_id = '$mem_id'";
        mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    }
    $sql_share = "select idx, card_idx from " . $contents_table_name . " where share_send_cont='{$contents_idx}'";
    $res_share = mysqli_query($self_con,$sql_share);
    $cnt_share = mysqli_num_rows($res_share);
    if ($cnt_share) {
        while ($row_share = mysqli_fetch_array($res_share)) {
            $sql = "select min(contents_order) from " . $contents_table_name . " where card_idx = '{$row_share['card_idx']}'";
            $result = mysqli_query($self_con,$sql);
            $row = mysqli_fetch_array($result);
            $min_order = $row[0];

            $sql = "select idx from " . $contents_table_name . " where card_idx = '{$row_share['card_idx']}' and contents_order = '$min_order'";
            $result = mysqli_query($self_con,$sql);
            $row = mysqli_fetch_array($result);
            if ($row) {
                $sql = "update " . $contents_table_name . " set contents_order = contents_order + 1 where card_idx = '{$row_share['card_idx']}' and contents_order < '$contents_order'";
                mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

                $sql = "update " . $contents_table_name . " set contents_order = '$min_order' where idx = {$row_share['idx']}";
                mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            }
        }
    }
    exit;
}
// ==================================================================================
// 좋아요 클릭
else if ($post_type == "like") {
    $sql = "update " . $contents_table_name . " set contents_like = contents_like + 1 where idx = '$contents_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
}
// ==================================================================================
// 이미지 삭제
else if ($post_type == "img_del") {
    $contents_img_src = $_POST['contents_img_src'];
    $sql = "select contents_img from Gn_Iam_Contents where idx = '$contents_idx' and mem_id = '$mem_id'";
    $result = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($result);
    $contents_img = $row["contents_img"];
    $img_array = explode(",", $contents_img);
    $del_array = array($contents_img_src);
    $img_array = array_diff($img_array, $del_array);
    $contents_img = implode(",", $img_array);
    $sql = "update Gn_Iam_Contents set contents_img = '$contents_img' where idx = '$contents_idx' and mem_id = '$mem_id'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo "이미지가 삭제 되었습니다.";
    exit;
}
// ==================================================================================
// 이미지 순위 변경
else if ($post_type == "change_main_img") {
    $contents_img_index = $_POST['contents_img_index'];
    $sql = "select contents_img from Gn_Iam_Contents where idx = '$contents_idx' and mem_id = '$mem_id'";
    $result = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($result);
    $contents_img = $row["contents_img"];
    $contents_img = explode(",", $contents_img);
    $target_img = $contents_img[$contents_img_index];
    $contents_img[$contents_img_index] = $contents_img[0];
    $contents_img[0] = $target_img;
    $contents_img = implode(",", $contents_img);
    $sql = "update Gn_Iam_Contents set contents_img = '$contents_img' where idx = '$contents_idx' and mem_id = '$mem_id'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo "이미지가 변경 되었습니다.";
    exit;
}
// ==================================================================================
// 프렌즈하기
else if ($post_type == "set_friend") {
    echo "성공되었습니다.";
    exit;
}
// ==================================================================================
// 특정컨텐츠 감추기
else if ($post_type == "block_contents") {
    $sql = "select block_contents from Gn_Iam_Info where mem_id = '$mem_id'";
    $result = mysqli_query($self_con,$sql);
    $iam_info = mysqli_fetch_array($result);
    $prev = $iam_info['block_contents'];
    if ($prev)
        $new = $prev . "," . $_POST['block_contents_id'];
    else
        $new = $_POST['block_contents_id'];
    $sql = "update Gn_Iam_Info set block_contents = '$new' where mem_id = '$mem_id'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo $new . " 성공되었습니다.";
    exit;
}
// ==================================================================================
// 특정유저의 컨텐츠 감추기
else if ($post_type == "block_user") {
    $sql = "select block_user from Gn_Iam_Info where mem_id = '$mem_id'";
    $result = mysqli_query($self_con,$sql);
    $iam_info = mysqli_fetch_array($result);
    $prev = $iam_info['block_user'];
    if ($prev)
        $new = $prev . "," . $_POST['block_mem_id'];
    else
        $new = $_POST['block_mem_id'];
    $sql = "update Gn_Iam_Info set block_user = '$new' where mem_id = '$mem_id'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo $new . " 성공되었습니다.";
    exit;
} else if ($post_type == "save_share_contents") {
    $msg = "저장되었습니다.";
    if ($_POST['gwc_state'] == "gwc_prod") {
        $checked_cards = $_POST['checked_cards'];
        $checked_cards = explode(",", $checked_cards);

        $sql_chk_limit = "select count(*) from Gn_Iam_Contents_Gwc where mem_id='{$_SESSION['iam_member_id']}' and gwc_con_state=1 and ori_store_prod_idx!=0";
        $res_chk_limit = mysqli_query($self_con,$sql_chk_limit);
        $row_chk_limit = mysqli_fetch_array($res_chk_limit);

        if ($_POST['gwc_exp_mem']) {
            if ($_POST['gwc_exp_con_cnt'] * 1 - 1 < $row_chk_limit[0]) {
                $msg = "현재 회원님의 굿마켓 상품 등록건수를 초과 했습니다. 관리자에게 문의해주세요.";
                echo $msg;
                exit;
            }
        } else {
            if ($row_chk_limit[0] == 20) {
                $msg = "최대 등록건수는 20건입니다.";
                echo $msg;
                exit;
            }
        }
        for ($i = 0; $i < count($checked_cards); $i++) {
            $card_sql = "select idx from Gn_Iam_Name_Card where card_short_url = '$checked_cards[$i]'";
            $card_res = mysqli_query($self_con,$card_sql);
            $card_row = mysqli_fetch_array($card_res);
            $cont_sql = "select max(contents_order) as order, card_idx from Gn_Iam_Contents_Gwc where westory_card_url = '$checked_cards[$i]'";
            $cont_res = mysqli_query($self_con,$cont_sql);
            $cont_row = mysqli_fetch_array($cont_res);
            $contents_order = $cont_row[0] + 1;
            $code = generateRandomCode();
            $sql_chk = "select count(*) as cnt from Gn_Iam_Contents_Gwc where ori_store_prod_idx='{$_POST['contents_id']}' and mem_id='{$_SESSION['iam_member_id']}'";
            $res_chk = mysqli_query($self_con,$sql_chk);
            $row_chk = mysqli_fetch_array($res_chk);
            if ($row_chk[0]) {
                $msg = "이미 가져온 상품입니다.";
                echo $msg;
                exit;
            }
            $sql_con = "INSERT INTO Gn_Iam_Contents_Gwc(mem_id, contents_type, contents_img, contents_title, contents_url, contents_url_title, contents_iframe, source_iframe, contents_price, contents_sell_price, contents_desc, contents_display, contents_user_display, contents_type_display, contents_footer_display, contents_temp, contents_like, contents_share_text, contents_share_count, req_data, up_data, card_short_url, contents_westory_display, westory_card_url, public_display, card_idx, except_keyword, contents_order, reduce_val, ai_map_gmarket, product_code, product_model_name, product_seperate,send_salary_price, send_provide_price, prod_manufact_price, ori_store_prod_idx, landing_mode, gwc_con_state, prod_order_option) 
                                            (SELECT '{$_SESSION['iam_member_id']}', contents_type, contents_img, contents_title, contents_url, contents_url_title, contents_iframe, source_iframe, contents_price, contents_sell_price, contents_desc, contents_display, contents_user_display, contents_type_display, contents_footer_display, contents_temp, contents_like, '', contents_share_count, now(), now(), '{$checked_cards[$i]}', contents_westory_display, '{$checked_cards[$i]}', public_display, {$card_row['idx']}, except_keyword, '{$contents_order}', reduce_val, ai_map_gmarket, '{$code}', product_model_name, product_seperate,send_salary_price, send_provide_price, prod_manufact_price, '{$_POST['contents_id']}', landing_mode, gwc_con_state, prod_order_option FROM Gn_Iam_Contents_Gwc WHERE idx='{$_POST['contents_id']}')";
            mysqli_query($self_con,$sql_con) or die(mysqli_error($self_con));
            $cont_idx = mysqli_insert_id($self_con);

            foreach (explode(",", $checked_cards[$i]) as $card_link) {
                $sql1 = "select idx from Gn_Iam_Name_Card where card_short_url = '$card_link'";
                $res1 = mysqli_query($self_con,$sql1);
                $row1 = mysqli_fetch_array($res1);
                $card = $row1['idx'];
                $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$cont_idx,card_idx=$card,main_card={$card_row['idx']}";
                mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
            }
        }

        $sql_store_con = "update Gn_Iam_Contents_Gwc set public_display='N' where idx = '$_POST[contents_id]'";
        mysqli_query($self_con,$sql_store_con) or die(mysqli_error($self_con));
    } else if ($_POST['gwc_state'] == 'gwc_prod_unset') {
        $sql_ori_idx = "select ori_store_prod_idx from Gn_Iam_Contents_Gwc where idx='{$_POST['contents_id']}'";
        $res_ori_idx = mysqli_query($self_con,$sql_ori_idx);
        $row_ori_idx = mysqli_fetch_array($res_ori_idx);

        $sql_update = "update Gn_Iam_Contents_Gwc set public_display='Y' where idx = '$row_ori_idx[ori_store_prod_idx]'";
        mysqli_query($self_con,$sql_update) or die(mysqli_error($self_con));

        $sql_del = "delete from Gn_Iam_Contents_Gwc where idx=$_POST[contents_id]";
        mysqli_query($self_con,$sql_del) or die(mysqli_error($self_con));
    } else {
        $sql = "update Gn_Iam_Contents_Gwc set contents_share_text = '" . $_SESSION['iam_member_id'] . "',contents_share_count = 1 where idx = '$_POST[contents_id]'";
        mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        $checked_cards = $_POST['checked_cards'];
        $checked_cards = explode(",", $checked_cards);
        $sql = "select * from Gn_Iam_Contents_Gwc where idx = '$_POST[contents_id]'";
        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        $row = mysqli_fetch_array($result);
        for ($i = 0; $i < count($checked_cards); $i++) {
            $card_sql = "select idx from Gn_Iam_Name_Card where card_short_url = '$checked_cards[$i]'";
            $card_res = mysqli_query($self_con,$card_sql);
            $card_row = mysqli_fetch_array($card_res);
            $cont_sql = "select max(contents_order) from Gn_Iam_Contents_Gwc where westory_card_url = '$checked_cards[$i]'";
            $cont_res = mysqli_query($self_con,$cont_sql);
            $cont_row = mysqli_fetch_array($cont_res);
            $contents_order = $cont_row[0] + 1;
            $sql = "insert into Gn_Iam_Contents_Gwc (mem_id,contents_type,contents_img,contents_title,contents_url, 
                                                  contents_url_title,contents_iframe,contents_price,contents_sell_price, 
                                                  contents_desc,except_keyword,contents_display,contents_westory_display, 
                                                  contents_user_display,contents_type_display,contents_footer_display, 
                                                  contents_temp,contents_share_text,req_data,up_data,
                                                  card_short_url,westory_card_url,card_idx,source_iframe,contents_order, reduce_val)
                                          values('{$_SESSION['iam_member_id']}','{$row['contents_type']}','{$row['contents_img']}','{$row['contents_title']}','{$row['contents_url']}', 
                                                  '{$row['contents_url_title']}','{$row['contents_iframe']}','{$row['contents_price']}','{$row['contents_sell_price']}', 
                                                  '{$row['contents_desc']}','{$row['except_keyword']}','{$row['contents_display']}','{$row['contents_westory_display']}', 
                                                  '{$row['contents_user_display']}','{$row['contents_type_display']}','{$row['contents_footer_display']}', 
                                                  0, '{$row['contents_share_id']}',now(),now(),
                                                  '$checked_cards[$i]','$checked_cards[$i]',{$card_row['idx']},'{$row['source_iframe']}',$contents_order, '$reduce_val'
                      )";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $cont_idx = mysqli_insert_id($self_con);

            foreach (explode(",", $checked_cards[$i]) as $card_link) {
                $sql1 = "select idx from Gn_Iam_Name_Card where card_short_url = '$card_link'";
                $res1 = mysqli_query($self_con,$sql1);
                $row1 = mysqli_fetch_array($res1);
                $card = $row1['idx'];
                $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$cont_idx,card_idx=$card,main_card={$card_row['idx']}";
                mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
            }
        }
    }
    echo $msg;
} else if ($post_type == "show_iam_card") {
    $show_iam_card = $_POST['status'];
    $sql = "update Gn_Member set show_iam_card='$show_iam_card' where mem_id = '" . $_SESSION['iam_member_id'] . "' ";
    $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo " 저장되었습니다.";
} else if ($post_type == "history") {
    $mem_id = $_POST['mem_id'];
    if (!$mem_id)
        exit;
    $cont_id = $_POST['cont_id'];
    $sql = "select count(idx) from Gn_Contents_History where mem_id='$mem_id' and cont_id='$cont_id'";
    $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $row = mysqli_fetch_array($result);
    if ($_POST['event_kind'] == 0) {
        $field = "load_count";
        $time_field = "load_time";
    } else if ($_POST['event_kind'] == 1) {
        $field = "click_count";
        $time_field = "click_time";
    } else if ($_POST['event_kind'] == 2) {
        $field = "post_count";
        $time_field = "post_time";
    }
    if ($row[0] == 0) {
        $sql = "insert into Gn_Contents_History set " . $field . "= 1, mem_id = '$mem_id', cont_id='$cont_id'," . $time_field . "= NOW()";
    } else {
        $sql = "update Gn_Contents_History set " . $field . "=" . $field . "+1," . $time_field . "= NOW() where mem_id = '$mem_id' and cont_id='$cont_id'";
    }
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo " 저장되었습니다.";
}
?>
