<?include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";?>
<?include_once $_SERVER['DOCUMENT_ROOT']."/lib/class.image.php";?>
<?php
# 설정파일을 php자체 에서도 바꿀 수 있음(이 파일 위해서만), 1은 on을 의미, display_errors는 디버그
$up_dir = make_folder_month(2);
if($up_dir != ''){
    $uploaddir = '../..'.$up_dir;
}
else{
    $uploaddir = '../../upload/';
    $up_dir = "/upload/";
}
$method_type = $_POST['iam_mall_method'];
$mall_idx = $_POST['iam_mall_idx'];
$iam_mall_type = $_POST['iam_mall_type'];
$date_file_name = date('dmYHis').str_replace(" ", "", basename($_FILES["iam_mall_img"]["name"]));
$uploadfile = $uploaddir.basename($date_file_name);
$iam_mall_img_link = $_POST['iam_mall_img_link'];
$iam_mall_title = $_POST['iam_mall_title'];
$iam_mall_sub_title = $_POST['iam_mall_sub_title'];
$iam_mall_price = $_POST['iam_mall_price'];
$iam_mall_sell_price = $_POST['iam_mall_sell_price'];
if(!$iam_mall_price || $iam_mall_price == '')
    $iam_mall_price = $iam_mall_sell_price;
$iam_mall_desc = $_POST['iam_mall_desc'];
$iam_mall_keyword = $_POST['iam_mall_keyword'];
$iam_mall_display = $_POST['iam_mall_display'];
$iam_mall_link = $_POST['iam_mall_link'];
$step_set = $_POST['step_set'];
$step_set_ids = $_POST['step_set_ids'];
if($_POST['mem_id'])
    $mem_id = $_POST['mem_id'];
else
    $mem_id = $_SESSION['iam_member_id'];
if(!$mem_id){
    echo '로그아웃 되었습니다. 다시 로그인을 해주세요.';
    exit;
}
if(!$method_type) {
    echo '잘못된 접근입니다. 다시 시도 해주세요.';
    exit;
}
// Create
if($method_type == "creat") {
    if($step_set == "Y"){
        $iam_mall_link = $step_set_ids;
    }
    if ($iam_mall_img_link) {
        $img = $iam_mall_img_link;
    }else if(count($_FILES['iam_mall_img']['name']) > 0){
        if (move_uploaded_file($_FILES['iam_mall_img']['tmp_name'], $uploadfile)) {
            $img = $up_dir . basename($date_file_name);
            $handle = new Image($uploadfile, 800);
            $handle->resize();
            uploadFTP($uploadfile);
        }
    }else {
        $sql = "select * from Gn_Iam_Name_Card where group_id is NULL and mem_id = '$mem_id' order by req_data limit 0,1";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        $img = $row['main_img1'];
    }
    $sql = "select count(*) from Gn_Iam_Mall where mem_id='$mem_id' and card_idx = '$iam_mall_link'";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    if($row[0] >= 1 && $step_set != "Y"){
        echo '이미 등록하신 상품입니다.';
        exit;
    }
    $sql = "update Gn_Member set special_type = 1 where mem_id = '$mem_id'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $sql = "insert into Gn_Iam_Mall (mem_id,mall_type,title,sub_title,img,description,keyword,price,sell_price,display_status,reg_date,card_idx) ".
            "values ('$mem_id','$iam_mall_type','$iam_mall_title','$iam_mall_sub_title','$img','$iam_mall_desc','$iam_mall_keyword','$iam_mall_price','$iam_mall_sell_price',$iam_mall_display,now(),'$iam_mall_link')";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo '아이엠 몰이 등록 되었습니다.';
    exit;
}
if($method_type == "edit") {
    if ($iam_mall_img_link) {
        $img = $iam_mall_img_link;
    }else if(count($_FILES['iam_mall_img']['name']) > 0){
        if (move_uploaded_file($_FILES['iam_mall_img']['tmp_name'], $uploadfile)) {
            $img = $up_dir . basename($date_file_name);
            $handle = new Image($uploadfile, 800);
            $handle->resize();
            uploadFTP($uploadfile);
        }
    }else {
        $sql = "select * from Gn_Iam_Name_Card where group_id is NULL and mem_id = '$mem_id' order by req_data limit 0,1";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        $img = $row['main_img1'];
    }
    $sql = "update Gn_Iam_Mall set title = '$iam_mall_title',sub_title = '$iam_mall_sub_title',img = '$img',".
            "description = '$iam_mall_desc',keyword = '$iam_mall_keyword',price = '$iam_mall_price',".
            "sell_price = '$iam_mall_sell_price',display_status = $iam_mall_display where idx = '$mall_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo '성공적으로 수정되었습니다.';
    exit;
}
if($method_type == "update_display_status"){
    $sql = "update Gn_Iam_Mall set display_status = '$iam_mall_display' where idx = '$mall_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo '성공적으로 수정되었습니다.';
    exit;
}
if($method_type == "update_sample_display"){
    $sql = "update Gn_Iam_Mall set sample_display = '$iam_mall_display' where idx = '$mall_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo '성공적으로 수정되었습니다.';
    exit;
}
if($method_type == "change_sample_order"){
    $sql = "update Gn_Iam_Mall set sample_order = '$iam_mall_display' where idx = '$mall_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo '성공적으로 수정되었습니다.';
    exit;
}

if($method_type == "like"){
    $sql = "select count(*) from Gn_Iam_Mall where idx = $mall_idx and (mall_like like '$mem_id,%' or mall_like like '%,$mem_id,%' or mall_like like '%,$mem_id' or mall_like = '$mem_id')";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    if($row[0] == 0){
        $sql="update Gn_Iam_Mall set mall_like_count = mall_like_count + 1 where idx = '$mall_idx'";
        mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

        $sql = "select mall_like from Gn_Iam_Mall where idx = '$mall_idx'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        $mall_like = $row[0];
        if($mall_like == ""){
            $sql="update Gn_Iam_Mall set mall_like = '$mem_id' where idx = '$mall_idx'";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        }else{
            $mall_like_ids = explode(",",$mall_like);
            array_push($mall_like_ids,$mem_id);
            $mall_like = implode(",",$mall_like_ids);
            $sql="update Gn_Iam_Mall set mall_like = '$mall_like' where idx = '$mall_idx'";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        }
        echo '성공되었습니다.';
    }else{
        echo '이미 찜하기 되었습니다.';
    }
    exit;
}
if($method_type == "unlike"){
    $sql = "select count(*) from Gn_Iam_Mall where idx = $mall_idx and (mall_like like '$mem_id,%' or mall_like like '%,$mem_id,%' or mall_like like '%,$mem_id' or mall_like = '$mem_id')";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    if($row[0] > 0){
        $sql="update Gn_Iam_Mall set mall_like_count = mall_like_count - 1 where idx = '$mall_idx'";
        mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        $sql = "select mall_like from Gn_Iam_Mall where idx = '$mall_idx'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        $mall_like = $row[0];
        $mall_like_ids = explode(",",$mall_like);
        $del_ids = array($mem_id);
        $mall_like_ids = array_diff($mall_like_ids,$del_ids);
        $mall_like = implode(",",$mall_like_ids);
        $sql="update Gn_Iam_Mall set mall_like = '$mall_like' where idx = '$mall_idx'";
        mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        echo '성공되었습니다.';
    }else{
        echo '몰아이디를 다시 확인해주세요.';
    }
    exit;
}
if($method_type == "visit"){
    $sql="update Gn_Iam_Mall set visit_count = visit_count + 1 where idx = '$mall_idx'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo '성공적으로 수정되었습니다.';
    exit;
}
if($method_type == "check_new_id"){
    $sql="select count(*) from Gn_Member where mem_id = '$mem_id'";
    $res = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $row = mysqli_fetch_array($res);
    if($row[0] == 0)
        $result = "true";
    else 
        $result = "false";
    echo json_encode(array("result"=>$result));
    exit;
}
if($method_type == "check_id"){
    $sql="select count(*) from Gn_Member where mem_id = '$mem_id'";
    $res = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $row = mysqli_fetch_array($res);
    if($row[0] == 0)
        $result = "false";
    else 
        $result = "true";
    echo json_encode(array("result"=>$result));
    exit;
}
if($method_type == "get_card_id"){
    $sql="select count(*) from Gn_Member where mem_id = '$mem_id'";
    $res = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $row = mysqli_fetch_array($res);
    if($row[0] == 0)
        $result = "false";
    else 
        $result = "true";
    $sql = "select card_short_url,card_title from Gn_Iam_Name_Card where group_id is NULL and mem_id = '$mem_id' order by req_data";
    $res = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $data = array();
    $index = 0;
    while($row = mysqli_fetch_array($res)){
        $card['link'] = $row['card_short_url'];
        $card['title'] = $row[card_title];
        $data[$index++] = $card;
    }
    echo json_encode(array("result"=>$result,"card_data"=>$data));
    exit;
}
if($method_type == "get_mall_link"){
    $mall_sql = "select * from Gn_Iam_Mall where idx = $mall_idx";
    $mall_res = mysqli_query($self_con,$mall_sql);
    $mall_row = mysqli_fetch_array($mall_res);
    if($mall_row['mall_type'] == 1){
        $sql = "select card_short_url,m.site_iam from Gn_Iam_Name_Card n inner join Gn_Member m on m.mem_id=n.mem_id where n.group_id is NULL and m.mem_code = '{$mall_row['card_idx']}' order by n.req_data limit 0,1";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        if($row['site_iam'] == "kiam") {
            $preview_link = "http://www.kiam.kr?";
            if(!strstr($mall_row['img'],"http"))
                $mall_row['img'] = "http://www.kiam.kr/".$mall_row['img'];
        }else {
            $preview_link = "http://" . $row['site_iam'] . ".kiam.kr?";
            if(!strstr($mall_row['img'],"http"))
                $mall_row['img'] = "http://" . $row['site_iam'] . ".kiam.kr/".$mall_row['img'];
        }
        $preview_link .= $row['card_short_url'].$mall_row['card_idx'];
    } else if($mall_row['mall_type'] == 2){
        $sql = "select card_short_url,m.site_iam,m.mem_code from Gn_Iam_Name_Card n inner join Gn_Member m on m.mem_id=n.mem_id where n.idx = '{$mall_row['card_idx']}'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        if($row['site_iam'] == "kiam") {
            $preview_link = "http://www.kiam.kr?";
            if(!strstr($mall_row['img'],"http"))
                $mall_row['img'] = "http://www.kiam.kr/".$mall_row['img'];
        }else {
            $preview_link = "http://" . $row['site_iam'] . ".kiam.kr?";
            if(!strstr($mall_row['img'],"http"))
                $mall_row['img'] = "http://" . $row['site_iam'] . ".kiam.kr/".$mall_row['img'];
        }
        $preview_link .= $row['card_short_url'].$row['mem_code']."&preview=Y";
    }else if($mall_row['mall_type'] == 3 || $mall_row['mall_type'] == 4 || $mall_row['mall_type'] > 10){
        $sql = "select m.site_iam from Gn_Iam_Contents c inner join Gn_Member m on m.mem_id=c.mem_id where c.idx = '{$mall_row['card_idx']}'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        if($row['site_iam'] == "kiam") {
            $preview_link = "http://www.kiam.kr/iam/contents.php?contents_idx=";
            if(!strstr($mall_row['img'],"http"))
                $mall_row['img'] = "http://www.kiam.kr/".$mall_row['img'];
        }else {
            $preview_link = "http://" . $row['site_iam'] . ".kiam.kr/iam/contents.php?contents_idx=";
            if(!strstr($mall_row['img'],"http"))
                $mall_row['img'] = "http://" . $row['site_iam'] . ".kiam.kr/".$mall_row['img'];
        }
        $preview_link .= $mall_row['card_idx'];
    }
    echo json_encode(array("link"=>$preview_link,"img"=>$mall_row['img'],"title"=>$mall_row['title'],"desc"=>$mall_row['description']));
    exit;
}
if($method_type == "pay_mall"){
    $mem_sql="select * from Gn_Member where mem_id='{$_SESSION['iam_member_id']}'";
    $mem_res=mysqli_query($self_con,$mem_sql);
    $mem_row=mysqli_fetch_array($mem_res);
    $site_iam = $mem_row['site_iam'];
    $site = $mem_row['site'];
    if($site_iam == "kiam")
        $service = "www.kiam.kr";
    else
        $service = $site_iam.".kiam.kr";
    $buyer_name = $mem_row['mem_name'];
    $buyer_phone = $mem_row['mem_phone'];
    $point = 1;
    // $point_sql = "select current_point from Gn_Item_Pay_Result where buyer_id='{$_SESSION['iam_member_id']}' and point_val!=0 and pay_status='Y' order by pay_date desc limit 1";
    $point_sql = "select mem_point, mem_cash from Gn_Member where mem_id='{$_SESSION['iam_member_id']}'";
    $point_res = mysqli_query($self_con,$point_sql);
    $point_row = mysqli_fetch_array($point_res);
    $cur_point = $point_row['mem_point'] * 1 - $iam_mall_sell_price * 1;
    $data = $_POST['iam_mall_pay_data'];
    $data = explode(",",$data);
    $iam_mall_pay_type = $data[0];
    $iam_mall_idx = $data[1];
    $iam_mall_link = $data[2];
    $mall_sql = "select title from Gn_Iam_Mall where idx = $iam_mall_idx";
    $mall_res = mysqli_query($self_con,$mall_sql);
    $mall_row = mysqli_fetch_array($mall_res);
    $item_name = $mall_row[0];
    if($iam_mall_pay_type == 1){//아이엠 몰 구매
        $service_sql = "update Gn_Iam_Service set mem_cnt = mem_cnt + 1 where sub_domain like '%".$service."%'";
        mysqli_query($self_con,$service_sql);
        $user_id = $data[3];
        $user_pwd = $data[4];
        $mem_sql = "select * from Gn_Member where mem_code = '$iam_mall_link'";
        $mem_res = mysqli_query($self_con,$mem_sql);
        $mem_row = mysqli_fetch_assoc($mem_res);
        $seller_id = $mem_row['mem_id'];
        $sql=" insert into Gn_Member set ";
        $index=0;
        foreach($mem_row as $key=>$v){
            if($key == "mem_id") {
                $v = "'".htmlspecialchars($user_id)."'";
            }else if($key == "mem_pass") {
                $v = "'".md5($user_pwd)."'";
            }else if($key == "web_pwd") {
                $v = "password('$user_pwd')";
            }else if($key == "site") {
                $v = "'".$site."'";
            }else if($key == "site_iam") {
                $v = "'".$site_iam."'";
            }else{
                $v = "'".str_replace("'","",$v)."'";
            }
            if($key != "mem_code" && $v != "")
                $sql.= $key."=".$v.",";
            $index++;
        }
        $sql = substr($sql,0,strlen($sql)-1);
        mysqli_query($self_con,$sql);
        $mem_code = mysqli_insert_id($self_con);
        $card_link = "";
        $card_sql = "select * from Gn_Iam_Name_Card where group_id is NULL and mem_id='$seller_id' order by req_data";
        $card_res = mysqli_query($self_con,$card_sql);
        $card_index = 0;
        while($card_row = mysqli_fetch_assoc($card_res)){
            $card_index ++;
            $card_short_url = $card_row['card_short_url'];
            $sql = "insert into Gn_Iam_Name_Card set ";
            foreach($card_row as $key=>$v){
                if($key == "mem_id") {
                    $v = "'".htmlspecialchars($user_id)."'";
                }else if($key == "card_short_url"){
                    $new_card_short_url = generateRandomString();
                    $v = "'".$new_card_short_url."'";
                }else if($key =="req_data"){
                    $v = "'".date("Y-m-d H:i:s",strtotime("+$card_index seconds"))."'";
                }else if($key =="group_id"){
                    $v = "NULL";
                }else
                    $v = "'".str_replace("'","",$v)."'";
                if($key != "idx" && $v != "")
                    $sql.= $key."=".$v.",";
            }
            $sql = substr($sql,0,strlen($sql)-1);
            mysqli_query($self_con,$sql);
            $card_idx = mysqli_insert_id($self_con);
            if($card_link == "")
                $card_link = $new_card_short_url;
            $cont_sql = "select * from Gn_Iam_Contents where mem_id='$seller_id' and card_idx = '$card_row[idx]' order by idx";
            $cont_res = mysqli_query($self_con,$cont_sql);
            $cont_index = $card_index;
            while($cont_row = mysqli_fetch_assoc($cont_res)) {
                $cont_index ++;
                $sql = "insert into Gn_Iam_Contents set ";
                foreach ($cont_row as $key => $v) {
                    if ($key == "mem_id") {
                        $v = "'".htmlspecialchars($user_id)."'";
                    }else if ($key == "card_short_url" || $key == "westory_card_url") {
                        $v = "'".$new_card_short_url."'";
                    }else if ($key == "card_idx") {
                        $v = $card_idx;
                    }else if($key =="req_data"){
                        $v = "'".date("Y-m-d H:i:s",strtotime("+$cont_index seconds"))."'";
                    }else if($key =="contents_share_text"){
                        $v = "''";
                    }else if($key =="up_data"){
                        $v = "'".date("Y-m-d H:i:s")."'";
                    }else if($key =="group_id"){
                        $v = "NULL";
                    }else
                        $v = "'".str_replace("'","",$v)."'";
                    if ($key != "idx" && $v != "")
                        $sql .= $key . "=" . $v . ",";
                }
                $sql = substr($sql,0,strlen($sql)-1);
                mysqli_query($self_con,$sql);
                $contents_idx = mysqli_insert_id($self_con);
                $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$contents_idx,card_idx=$card_idx,main_card=$card_idx";
                mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
                $sql2 = "update Gn_Iam_Name_Card set up_data = now() where idx={$card_idx}";
                mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
            }
        }
        $_SESSION['iam_member_id'] = htmlspecialchars($user_id);
        $_SESSION['one_member_id'] = htmlspecialchars($user_id);
        $url = "http://".$HTTP_HOST."/?".$card_link.$mem_code;
        $item_name = "IAM몰 아이엠/".$item_name;
    }else if($iam_mall_pay_type == 2){//아이엠 카드 구매
        $mem_code = $mem_row['mem_code'];
        $card_sql="update Gn_Member set iam_card_cnt = iam_card_cnt + 1 where mem_code='$mem_code'";
        mysqli_query($self_con,$card_sql);
        $user_id = $data[3];
        $card_sql = "select * from Gn_Iam_Name_Card where idx ='$iam_mall_link'";
        $card_res = mysqli_query($self_con,$card_sql);
        $card_row = mysqli_fetch_assoc($card_res);
        $seller_id = $card_row[mem_id];
        $card_short_url = $card_row['card_short_url'];
        $sql = "insert into Gn_Iam_Name_Card set ";
        foreach($card_row as $key=>$v){
            if($key == "mem_id") {
                $v = "'".htmlspecialchars($user_id)."'";
            }else if($key == "card_short_url"){
                $new_card_short_url = generateRandomString();
                $v = "'".$new_card_short_url."'";
            }else if($key =="req_data"){
                $v = "now()";
            }else if($key =="group_id"){
                $v = "NULL";
            }else
                $v = "'".str_replace("'","",$v)."'";
            if($key != "idx" && $v != "")
                $sql.= $key."=".$v.",";
        }
        $sql = substr($sql,0,strlen($sql)-1);
        mysqli_query($self_con,$sql);
        $card_idx = mysqli_insert_id($self_con);
        $cont_sql = "select * from Gn_Iam_Contents where mem_id='$seller_id' and card_idx = '$card_row[idx]' order by idx";
        $cont_res = mysqli_query($self_con,$cont_sql);
        $cont_index = 0;
        while($cont_row = mysqli_fetch_assoc($cont_res)) {
            $cont_index ++;
            $sql = "insert into Gn_Iam_Contents set ";
            foreach ($cont_row as $key => $v) {
                if ($key == "mem_id") {
                    $v = "'".htmlspecialchars($user_id)."'";
                }else if ($key == "card_short_url" || $key == "westory_card_url") {
                    $v = "'".$new_card_short_url."'";
                }else if ($key == "card_idx") {
                    $v = $card_idx;
                }else if($key =="req_data"){
                    $v = "'".date("Y-m-d H:i:s",strtotime("+$cont_index seconds"))."'";
                }else if($key =="contents_share_text"){
                    $v = "''";
                }else if($key =="up_data"){
                    $v = "'".date("Y-m-d H:i:s")."'";
                }else if($key =="group_id"){
                    $v = "NULL";
                }else
                    $v = "'".str_replace("'","",$v)."'";
                if ($key != "idx" && $v != "")
                    $sql .= $key . "=" . $v . ",";
            }
            $sql = substr($sql,0,strlen($sql)-1);
            mysqli_query($self_con,$sql);
            $contents_idx = mysqli_insert_id($self_con);
            $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$contents_idx,card_idx=$card_idx,main_card=$card_idx";
            mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
            $sql2 = "update Gn_Iam_Name_Card set up_data = now() where idx={$card_idx}";
            mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        }
        $url = "http://".$HTTP_HOST."/?".$new_card_short_url.$mem_code;
        $item_name = "IAM몰 카드/".$item_name;
    }else if($iam_mall_pay_type == 3){//아이엠 콘텐츠 구매
        $dst_card_short_url = $data[3];
        $card_sql = "select idx,mem_id from Gn_Iam_Name_Card where card_short_url = '$dst_card_short_url'";
        $card_res = mysqli_query($self_con,$card_sql);
        $card_row = mysqli_fetch_array($card_res);
        $card_idx = $card_row['idx'];
        $user_id = $card_row['mem_id'];
        $cont_sql = "select max(contents_order) from Gn_Iam_Contents where card_idx = '$card_idx'";
        $cont_res = mysqli_query($self_con,$cont_sql);
        $cont_row = mysqli_fetch_array($cont_res);
        $cont_order = $cont_row[0] + 1;
        $sql = "select mem_code from Gn_Member where mem_id = '$user_id'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        $mem_code = $row[0];

        $cont_sql = "select * from Gn_Iam_Contents where idx = '$iam_mall_link'";
        $cont_res = mysqli_query($self_con,$cont_sql);
        $cont_row = mysqli_fetch_assoc($cont_res);
        $seller_id = $cont_row['mem_id'];
        $sql = "insert into Gn_Iam_Contents set ";
        foreach ($cont_row as $key => $v) {
            if ($key == "mem_id") {
                $v = "'".htmlspecialchars($user_id)."'";
            }else if ($key == "card_short_url" || $key == "westory_card_url") {
                $v = "'".$dst_card_short_url."'";
            }else if ($key == "card_idx") {
                $v = $card_idx;
            }else if($key =="req_data"){
                $v = "now()";
            }else if($key =="contents_share_text"){
                $v = "''";
            }else if($key =="up_data"){
                $v = "'".date("Y-m-d H:i:s")."'";
            }else if($key =="group_id"){
                $v = "NULL";
            }else if($key =="contents_order"){
                $v = $cont_order;
            }else
                $v = "'".str_replace("'","",$v)."'";
            if ($key != "idx" && $v != "")
                $sql .= $key . "=" . $v . ",";
        }
        $sql = substr($sql,0,strlen($sql)-1);
        mysqli_query($self_con,$sql);
        $contents_idx = mysqli_insert_id($self_con);
        $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$contents_idx,card_idx=$card_idx,main_card=$card_idx";
        mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        $cont_idx = mysqli_insert_id($self_con);
        $sql2 = "update Gn_Iam_Name_Card set up_data = now() where idx={$card_idx}";
        mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        // $url = "http://".$HTTP_HOST."/iam?".$dst_card_short_url.$mem_code;
        $url = "http://".$HTTP_HOST."/iam/contents.php?contents_idx=".$cont_idx;
        $item_name = "IAM몰 콘텐츠/".$item_name;
    }
    $sql_buyer = "insert into Gn_Item_Pay_Result set buyer_id='{$_SESSION['iam_member_id']}',
                                                    buyer_tel='$buyer_phone',
                                                    site='$url',
                                                    pay_method='$seller_id',
                                                    item_name = '$item_name',
                                                    item_price=$_POST[iam_mall_sell_price],
                                                    seller_id='{$_POST['seller_id']}',
                                                    pay_date=NOW(),
                                                    pay_status='Y',
                                                    pay_percent='$_POST[pay_percent]',
                                                    order_number = '{$_POST['allat_order_no']}',
                                                    VACT_InputName='$buyer_name',
                                                    point_val=$point,
                                                    type='use',
                                                    current_point=$cur_point,
                                                    current_cash={$point_row['mem_cash']}";
    mysqli_query($self_con,$sql_buyer);

    $sql_update = "update Gn_Member set mem_point={$cur_point} where mem_id='{$_SESSION['iam_member_id']}'";
    mysqli_query($self_con,$sql_update);

    // $seller_sql = "select current_point from Gn_Item_Pay_Result where buyer_id='{$seller_id}' and point_val!=0 and pay_status='Y' order by pay_date desc limit 1";
    $seller_sql = "select mem_point, mem_cash from Gn_Member where mem_id='{$seller_id}'";
    $seller_res = mysqli_query($self_con,$seller_sql);
    $seller_row = mysqli_fetch_array($seller_res);
    $seller_cur_point = $seller_row['mem_point'] * 1 + $iam_mall_sell_price * 1;

    $sql_seller = "insert into Gn_Item_Pay_Result set buyer_id='$seller_id',
                                                    buyer_tel='$buyer_phone',
                                                    site='$url',
                                                    pay_method='{$_SESSION['one_member_id']}',
                                                    item_name = '$item_name',
                                                    item_price=$_POST[iam_mall_sell_price],
                                                    seller_id='{$_SESSION['one_member_id']}',
                                                    pay_date=NOW(),
                                                    pay_status='Y',
                                                    pay_percent='$_POST[pay_percent]',
                                                    order_number = '{$_POST['allat_order_no']}',
                                                    VACT_InputName='$buyer_name',
                                                    point_val=$point,
                                                    type='servicebuy',
                                                    current_point=$seller_cur_point,
                                                    current_cash={$seller_row['mem_cash']}";
    mysqli_query($self_con,$sql_seller);

    $sql_update1 = "update Gn_Member set mem_point={$seller_cur_point} where mem_id='{$seller_id}'";
    mysqli_query($self_con,$sql_update1);
    echo json_encode(array("result"=>"ok","link"=>$url));
    exit;
}
if($method_type == "daesa_reg_pay"){
    $mem_sql="select * from Gn_Member where mem_id='{$_SESSION['iam_member_id']}'";
    $mem_res=mysqli_query($self_con,$mem_sql);
    $mem_row=mysqli_fetch_array($mem_res);
    $site_iam = $mem_row['site_iam'];
    $site = $mem_row['site'];
    if($site_iam == "kiam")
        $service = "www.kiam.kr";
    else
        $service = $site_iam.".kiam.kr";
    $buyer_name = $mem_row['mem_name'];
    $buyer_phone = $mem_row['mem_phone'];
    
    $data = $_POST['data1'];
    $data = explode(",",$data);
    $iam_mall_pay_type = $data[0];
    $iam_mall_idx = $data[1];
    $iam_mall_link = $data[2];
    $mall_sql = "select title from Gn_Iam_Mall where idx = $iam_mall_idx";
    $mall_res = mysqli_query($self_con,$mall_sql);
    $mall_row = mysqli_fetch_array($mall_res);
    $item_name = $mall_row[0];
    if($iam_mall_pay_type == 1){//아이엠 몰 구매
        $service_sql = "update Gn_Iam_Service set mem_cnt = mem_cnt + 1 where sub_domain like '%".$service."%'";
        mysqli_query($self_con,$service_sql);
        $user_id = $data[3];
        $user_pwd = $data[4];
        $mem_sql = "select * from Gn_Member where mem_code = '$iam_mall_link'";
        $mem_res = mysqli_query($self_con,$mem_sql);
        $mem_row = mysqli_fetch_assoc($mem_res);
        $seller_id = $mem_row['mem_id'];
        $sql=" insert into Gn_Member set ";
        $index=0;
        foreach($mem_row as $key=>$v){
            if($key == "mem_id") {
                $v = "'".htmlspecialchars($user_id)."'";
            }else if($key == "mem_pass") {
                $v = "'".md5($user_pwd)."'";
            }else if($key == "web_pwd") {
                $v = "password('$user_pwd')";
            }else if($key == "site") {
                $v = "'".$site."'";
            }else if($key == "site_iam") {
                $v = "'".$site_iam."'";
            }else{
                $v = "'".str_replace("'","",$v)."'";
            }
            if($key != "mem_code" && $v != "")
                $sql.= $key."=".$v.",";
            $index++;
        }
        $sql = substr($sql,0,strlen($sql)-1);
        mysqli_query($self_con,$sql);
        $mem_code = mysqli_insert_id($self_con);
        $card_link = "";
        $card_sql = "select * from Gn_Iam_Name_Card where group_id is NULL and mem_id='$seller_id' order by req_data";
        $card_res = mysqli_query($self_con,$card_sql);
        $card_index = 0;
        while($card_row = mysqli_fetch_assoc($card_res)){
            $card_index ++;
            $card_short_url = $card_row['card_short_url'];
            $sql = "insert into Gn_Iam_Name_Card set ";
            foreach($card_row as $key=>$v){
                if($key == "mem_id") {
                    $v = "'".htmlspecialchars($user_id)."'";
                }else if($key == "card_short_url"){
                    $new_card_short_url = generateRandomString();
                    $v = "'".$new_card_short_url."'";
                }else if($key =="req_data"){
                    $v = "'".date("Y-m-d H:i:s",strtotime("+$card_index seconds"))."'";
                }else if($key =="group_id"){
                    $v = "NULL";
                }else
                    $v = "'".str_replace("'","",$v)."'";
                if($key != "idx" && $v != "")
                    $sql.= $key."=".$v.",";
            }
            $sql = substr($sql,0,strlen($sql)-1);
            mysqli_query($self_con,$sql);
            $card_idx = mysqli_insert_id($self_con);
            if($card_link == "")
                $card_link = $new_card_short_url;
            $cont_sql = "select * from Gn_Iam_Contents where mem_id='$seller_id' and card_idx = '{$card_row['idx']}' order by idx";
            $cont_res = mysqli_query($self_con,$cont_sql);
            $cont_index = $card_index;
            while($cont_row = mysqli_fetch_assoc($cont_res)) {
                $cont_index ++;
                $sql = "insert into Gn_Iam_Contents set ";
                foreach ($cont_row as $key => $v) {
                    if ($key == "mem_id") {
                        $v = "'".htmlspecialchars($user_id)."'";
                    }else if ($key == "card_short_url" || $key == "westory_card_url") {
                        $v = "'".$new_card_short_url."'";
                    }else if ($key == "card_idx") {
                        $v = $card_idx;
                    }else if($key =="req_data"){
                        $v = "'".date("Y-m-d H:i:s",strtotime("+$cont_index seconds"))."'";
                    }else if($key =="contents_share_text"){
                        $v = "''";
                    }else if($key =="up_data"){
                        $v = "'".date("Y-m-d H:i:s")."'";
                    }else if($key =="group_id"){
                        $v = "NULL";
                    }else
                        $v = "'".str_replace("'","",$v)."'";
                    if ($key != "idx" && $v != "")
                        $sql .= $key . "=" . $v . ",";
                }
                $sql = substr($sql,0,strlen($sql)-1);
                mysqli_query($self_con,$sql);
                $contents_idx = mysqli_insert_id($self_con);
                $sql2 = "insert into Gn_Iam_Con_Card set cont_idx=$contents_idx,card_idx=$card_idx,main_card=$card_idx";
                mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
                $sql2 = "update Gn_Iam_Name_Card set up_data = now() where idx={$card_idx}";
                mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
            }
        }
        $_SESSION['iam_member_id'] = htmlspecialchars($user_id);
        $_SESSION['one_member_id'] = htmlspecialchars($user_id);
        $url = "http://".$HTTP_HOST."/?".$card_link.$mem_code;
        $item_name = "IAM몰 아이엠/".$item_name;
    }
    echo json_encode(array("result"=>"ok"));
}
if($method_type == "delete_mall"){
    $mall_idx = explode(",",$mall_idx);
    $mall_count = count($mall_idx);
    for($x = 0; $x < $mall_count; $x++) {
        if($mall_idx[$x] != "")
        {
            $sql_del = "delete from Gn_Iam_Mall where idx='$mall_idx[$x]'";
            mysqli_query($self_con,$sql_del);
        }
    }
    echo json_encode(array("result"=>"성공적으로 삭제되었습니다."));
    exit;
}
if($method_type == "get_content_info"){
    $sql = "select * from Gn_Iam_Contents where idx=".$mall_idx;
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_assoc($res);
    echo json_encode($row);
    exit;
}
if($method_type == "get_mall_info"){
    $sql = "select * from Gn_Iam_Mall where idx=".$mall_idx;
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_assoc($res);
    echo json_encode($row);
    exit;
}
?>
