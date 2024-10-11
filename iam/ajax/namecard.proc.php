<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/class.image.php";
# 설정파일을 php자체 에서도 바꿀 수 있음(이 파일 위해서만), 1은 on을 의미, display_errors는 디버그
$up_dir = make_folder_month(2);
if($up_dir != ''){
    $uploaddir = '../..'.$up_dir;
}
else{
    $uploaddir = '../../upload/';
    $up_dir = "/upload/";
}
$mode = $_POST['mode'];
$link = $_POST['link'];
$card_idx = $_POST['card_idx'];
$card_title = htmlspecialchars($_POST['card_title']);
$card_name = htmlspecialchars($_POST['card_name']);
$card_company = htmlspecialchars($_POST['card_company']);
$card_position = htmlspecialchars($_POST['card_position']);
$card_phone = $_POST['card_phone'];
$card_email = $_POST['card_email'];
$card_addr = $_POST['card_addr'];
$card_map = $_POST['card_map'];
$card_keyword = $_POST['card_keyword'];
$next_iam_link = $_POST['next_iam_link'];
$story_title4 = htmlspecialchars($_POST['story_title4']);
$story_online1_text = htmlspecialchars($_POST['story_online1_text']);
$story_online2_text = htmlspecialchars($_POST['story_online2_text']);

$story_online1 = htmlspecialchars($_POST['story_online1']);
$story_online2 = htmlspecialchars($_POST['story_online2']);
$business_time = $_POST['business_time'];

$online1_check = $_POST['online1_check'];
$online2_check = $_POST['online2_check'];
$story_title1 = htmlspecialchars($_POST['story_title1']);
$story_title2 = htmlspecialchars($_POST['story_title2']);
$story_title3 = htmlspecialchars($_POST['story_title3']);
$story_myinfo = htmlspecialchars($_POST['story_myinfo']);
$story_company = htmlspecialchars($_POST['story_company']);
$story_career = htmlspecialchars($_POST['story_career']);
if($_POST['mem_id'])
    $mem_id = $_POST['mem_id'];
else
    $mem_id = $_SESSION['iam_member_id'];
$date_file_name = date('dmYHis').str_replace(" ", "", basename($_FILES["uploadFile"]["name"]));
$date_file_name1 = date('dmYHis').str_replace(" ", "", basename($_FILES["uploadFile1"]["name"]));
$date_file_name2 = date('dmYHis').str_replace(" ", "", basename($_FILES["uploadFile2"]["name"]));
$date_file_name3 = date('dmYHis').str_replace(" ", "", basename($_FILES["uploadFile3"]["name"]));

$uploadfile = $uploaddir.basename($date_file_name);
$uploadfile1 = $uploaddir.basename($date_file_name1);
$uploadfile2 = $uploaddir.basename($date_file_name2);
$uploadfile3 = $uploaddir.basename($date_file_name3);
# name값이 userfile인 input에 들어있는 name이 파일이름이 되게 해라(uploaddir에 추가)
# basename은 파일 이름이 myapp\black.png 일때 black만 뽑아내서 파일이름이 되게 함
// echo $card_name;
# 배열 같은게 각 줄마다 나와서 보기좋게 함
if(!$mem_id){
    echo '로그아웃 되었습니다. 다시 로그인을 해주세요.';
    exit;
}
if($mode == "creat") {
    $query = "select iam_card_cnt from Gn_Member where mem_id='$mem_id'";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);

    $query = "select count(*) as cnt from Gn_Iam_Name_Card where group_id is NULL and mem_id='".$mem_id."'";
    $res = mysqli_query($self_con,$query);
    $data = mysqli_fetch_array($res);
    if($row['iam_card_cnt'] <= $data[0]) {
        $result = "고객님의 아이엠은 {$row['iam_card_cnt']}개의 카드를 사용할수 있습니다. 추가하시려면 관리자에게 문의하세요.";
        echo $result;
        exit;
    }
    if($link){
        $link_arr = explode("?",$link);
        $card_short_url = substr($link_arr[1],0,10);
        $sql = "select * from Gn_Iam_Name_Card where card_short_url = '$card_short_url'";
        $res = mysqli_query($self_con,$sql);
        $row = mysqli_fetch_array($res);
        if(!$row['idx']){
            echo "카드링크가 존재하지 않습니다.다시 확인해주세요";
        }else{
            $short_url = generateRandomString();
            $sql = "insert into Gn_Iam_Name_Card (mem_id, card_title, card_short_url, card_name, card_company, card_position, card_phone, card_email, card_addr, card_map, card_keyword, profile_logo, favorite, story_title4, story_online1_text," .
                "story_online1, online1_check, story_online2_text, story_online2, online2_check,iam_click,req_data,main_img1,main_img2,main_img3,story_title1,story_title2,story_title3,story_myinfo,story_company,story_career,next_iam_link)" .
                "values (\"$mem_id\", \"$card_title\", \"$short_url\", \"$row[card_name]\", \"$row[card_company]\", \"$row[card_position]\", \"$row[card_phone]\", \"$row[card_email]\", \"$row[card_addr]\", \"$row[card_map]\", \"$row[card_keyword]\",".
                " \"$row[profile_logo]\", 0, \"$row[story_title4]\",\"$row[story_online1_text]\",\"$row[story_online1]\", \"$row[online1_check]\", \"$row[story_online2_text]\", \"$row[story_online2]\", \"$row[online2_check]\", 0, now(),".
                "\"$row[main_img1]\",\"$row[main_img2]\",\"$row[main_img3]\",\"$row[story_title1]\",\"$row[story_title2]\",\"$row[story_title3]\",\"$row[story_myinfo]\",\"$row[story_company]\",\"$row[story_career]\",\"$link\")";
            mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

            $mem_sql = "select mem_code from Gn_Member where mem_id = '$mem_id'";
            $mem_res = mysqli_query($self_con,$mem_sql);
            $mem_row = mysqli_fetch_array($mem_res);
            echo $short_url . $mem_row['mem_code'];
        }
    }else {
        if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadfile)) {
            $img_url = $up_dir . basename($date_file_name);
            $handle = new Image($uploadfile, 800);
            $handle->resize();
            uploadFTP($uploadfile);
            # temp파일에 있는 걸, 내가 지정한 uploadfile에 이동시켜라
        }else {
            if ($_POST['logo_link'] == "")
                $img_url = "/iam/img/common/logo-2.png";
            else
                $img_url = $_POST['logo_link'];
        }
        if (move_uploaded_file($_FILES['uploadFile1']['tmp_name'], $uploadfile1)) {
            $img_url1 = $up_dir . basename($date_file_name1);
            $handle = new Image($uploadfile1, 800);
            $handle->resize();
            uploadFTP($uploadfile1);
        }else {
            $img_url1 = $_POST['main_img1_link'];
        }
        if (move_uploaded_file($_FILES['uploadFile2']['tmp_name'], $uploadfile2)) {
            $img_url2 = $up_dir . basename($date_file_name2);
            $handle = new Image($uploadfile2, 800);
            $handle->resize();
            uploadFTP($uploadfile2);
        }else {
            $img_url2 = $_POST['main_img2_link'];
        }
        if (move_uploaded_file($_FILES['uploadFile3']['tmp_name'], $uploadfile3)) {
            $img_url3 = $up_dir . basename($date_file_name3);
            $handle = new Image($uploadfile3, 800);
            $handle->resize();
            uploadFTP($uploadfile3);
        }else {
            $img_url3 = $_POST['main_img3_link'];
        }
        $short_url = generateRandomString();

        if ($card_phone == "--") {
            $sql = "select mem_phone from Gn_Member where mem_id = '$mem_id'";
            $result = mysqli_query($self_con,$sql);
            $row = mysqli_fetch_array($result);
            $card_phone = $row['mem_phone'];
        }
        $iam_sql = "select count(*) from Gn_Iam_Info where mem_id = '$mem_id'";
        $iam_result = mysqli_query($self_con,$iam_sql);
        $iam_row = mysqli_fetch_array($iam_result);
        if ($iam_row[0] == 0) {
            $query_info = "insert into Gn_Iam_Info (mem_id,main_img1,main_img2,main_img3, reg_data) values ('$memid','$profile_image[0]','$profile_image[1]','$profile_image[2]', now())";
            mysqli_query($self_con,$query_info);
        }
        $sql2 = "insert into Gn_Iam_Name_Card (mem_id, card_title, card_short_url, card_name, card_company, card_position, card_phone, card_email, card_addr, card_map, card_keyword, profile_logo, favorite, story_title4, story_online1_text," .
            "story_online1, online1_check, story_online2_text, story_online2, online2_check,req_data,main_img1,main_img2,main_img3,story_title1,story_title2,story_title3,story_myinfo,story_company,story_career)" .
            "values (\"$mem_id\", \"$card_title\", \"$short_url\", \"$card_name\", \"$card_company\", \"$card_position\", \"$card_phone\", \"$card_email\", \"$card_addr\", \"$card_map\", \"$card_keyword\", \"$img_url\", 0, \"$story_title4\"," .
            "\"$story_online1_text\",\"$story_online1\", \"$online1_check\", \"$story_online2_text\", \"$story_online2\", \"$online2_check\", now(),\"$img_url1\",\"$img_url2\",\"$img_url3\"," .
            "\"$story_title1\",\"$story_title2\",\"$story_title3\",\"$story_myinfo\",\"$story_company\",\"$story_career\")";
        $result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));

        $mem_sql = "select mem_code from Gn_Member where mem_id = '$mem_id'";
        $mem_res = mysqli_query($self_con,$mem_sql);
        $mem_row = mysqli_fetch_array($mem_res);
        echo $short_url . $mem_row['mem_code'];
    }
    exit;
}
//==========================================================================================================
//명함 수정
else if($mode == "edit") {
    if(move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadfile)){
        $img_url = $up_dir.basename($date_file_name);
        $handle = new Image($uploadfile, 800);
        $handle->resize();
        uploadFTP($uploadfile);
        # temp파일에 있는 걸, 내가 지정한 uploadfile에 이동시켜라
    } else {
        if ($_POST['logo_link'] == "")
            $img_url = "/iam/img/common/logo-2.png";
        else
            $img_url = $_POST['logo_link'];
    }
    $sql_chk_share = "select idx from Gn_Iam_Name_Card where share_send_card='{$card_idx}'";
    $res_chk_share = mysqli_query($self_con,$sql_chk_share);
    $cnt_chk = mysqli_num_rows($res_chk_share);
    if($cnt_chk){
        while($row_chk = mysqli_fetch_array($res_chk_share)){
            $sql2="update Gn_Iam_Name_Card set card_title = \"$card_title\", card_name = \"$card_name\", card_company = \"$card_company\", card_position = \"$card_position\",".
            " card_phone = '$card_phone', card_email = '$card_email', card_addr = \"$card_addr\", card_map = '$card_map', card_keyword = '$card_keyword',next_iam_link = '$next_iam_link',".
            " profile_logo = '$img_url', story_title4 = '$story_title4', story_online1_text = '$story_online1_text', story_online1 = '$story_online1', online1_check = '$online1_check',".
            " story_online2_text = '$story_online2_text', story_online2 = '$story_online2', online2_check = '$online2_check', business_time = '$business_time', up_data = now() where idx = '{$row_chk['idx']}'";
            $result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        }
    }
    $sql2="update Gn_Iam_Name_Card set card_title = \"$card_title\", card_name = \"$card_name\", card_company = \"$card_company\", card_position = \"$card_position\",".
        " card_phone = '$card_phone', card_email = '$card_email', card_addr = \"$card_addr\", card_map = '$card_map', card_keyword = '$card_keyword',next_iam_link = '$next_iam_link',".
        " profile_logo = '$img_url', story_title4 = '$story_title4', story_online1_text = '$story_online1_text', story_online1 = '$story_online1', online1_check = '$online1_check',".
        " story_online2_text = '$story_online2_text', story_online2 = '$story_online2', online2_check = '$online2_check', business_time = '$business_time', up_data = now() where idx = '$card_idx' and mem_id = '$mem_id'";
    $result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
    $sql = "select busi_time_edit, card_short_url from Gn_Iam_Name_Card where idx = '$card_idx'";
    $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $row = mysqli_fetch_array($result);

    $busi_time_edit = $row['busi_time_edit'];
    $sql3 = "update Gn_Iam_Name_Card set business_time = '$business_time' where mem_id='$mem_id' and busi_time_edit='$busi_time_edit' and idx != '$card_idx'";
    $result3 = mysqli_query($self_con,$sql3) or die(mysqli_error($self_con));

    $mem_sql="select mem_code from Gn_Member where mem_id = '$mem_id'";
    $mem_res = mysqli_query($self_con,$mem_sql);
    $mem_row = mysqli_fetch_array($mem_res);
    echo $row["card_short_url"].$mem_row['mem_code'];
    exit;
} 
//==========================================================================================================
//명함 삭제
else if($mode == "del") {
    $sql = "select org_use_state,card_short_url,group_id from Gn_Iam_Name_Card use index(idx) where idx={$card_idx} and mem_id='{$mem_id}'";
    $res = mysqli_query($self_con,$sql);
    $row_url = mysqli_fetch_array($res);
    if($row_url['org_use_state']){
        echo 3;
        exit;
    }
    $short_url = $row_url['card_short_url'];
    $group_id = $row_url['group_id'];

    $sql_auto_del = "delete from auto_update_contents where card_idx={$card_idx}";
    mysqli_query($self_con,$sql_auto_del);

    $sql="delete from Gn_Iam_Name_Card where idx = $card_idx and mem_id = '$mem_id'";
    $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

    $sql="delete from Gn_Iam_Mall where card_idx = '$card_idx' and mall_type = 2";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

    $sql2="delete from Gn_Iam_Friends where friends_card_idx = $card_idx ";
    $result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));

    $mall_sql = "select * from Gn_Iam_Contents where card_idx={$card_idx}";
    $mall_res = mysqli_query($self_con,$mall_sql);
    while($mall_row = mysqli_fetch_array($mall_res)){
        $m_idx = $mall_row['idx'];
        $m_sql="delete from Gn_Iam_Mall where card_idx = '$m_idx' and (mall_type = 3 or mall_type = 4)";
        mysqli_query($self_con,$m_sql) or die(mysqli_error($self_con));
    }

    $sql_del_contents = "delete from Gn_Iam_Contents where card_idx=$card_idx and mem_id = '$mem_id'";
    $result3 = mysqli_query($self_con,$sql_del_contents) or die(mysqli_error($self_con));
    $sql_del_contents = "delete from Gn_Iam_Con_Card where card_idx=$card_idx";
    $result3 = mysqli_query($self_con,$sql_del_contents) or die(mysqli_error($self_con));

    if($group_id == "")
        $sql="select card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '$mem_id' order by req_data desc";
    else
        $sql="select card_short_url from Gn_Iam_Name_Card where group_id = '$group_id' and mem_id = '$mem_id' order by req_data desc";
    $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $row = mysqli_fetch_array($result);

    if($row["card_short_url"] == NULL){
        echo 1;
        exit;
    }

    $mem_sql="select mem_code from Gn_Member where mem_id = '$mem_id'";
    $mem_res = mysqli_query($self_con,$mem_sql);
    $mem_row = mysqli_fetch_array($mem_res);
    if($group_id == "")
        echo $row["card_short_url"].$mem_row['mem_code'];
    else
        echo $row["card_short_url"].$mem_row['mem_code']."&cur_win=group-con&gkind=".$group_id;
    exit;
} 
//==========================================================================================================
//이미지를 삭제 할때
else if($mode == "img_del") {
    $img_name = $_POST['img_name'];
    $profile_logo = explode('/upload/', $img_name);
    unlink($uploaddir.$profile_logo[1]);

    $sql2="update Gn_Iam_Name_Card set profile_logo = '', up_data = now() where idx = '$card_idx' and mem_id = '$mem_id'";
    $result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
}
else if($mode == "select"){
    $card_short_url = $_POST['card_short_url'];
    $sql="select * from Gn_Iam_Name_Card where card_short_url = '$card_short_url'";
    $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $data = mysqli_fetch_array($result);
    echo json_encode($data);
}
else if($mode == "post_display"){
    $card_short_url = $_POST['card_short_url'];
    $status = $_POST['status'];
    $sql="update Gn_Iam_Name_Card set post_display = $status where card_short_url = '$card_short_url'";
    mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    echo "저장되었습니다.";
}
else if($mode == "change_cont_order_type"){
    $card_short_url = $_POST['card_short_url'];
    $cont_order_type = $_POST['cont_order_type'];
    $cont_order_array = array_reverse(explode(",",$_POST['cont_order']));
    $sql="update Gn_Iam_Name_Card set cont_order_type = $cont_order_type,up_data=now() where card_short_url = '$card_short_url'";
    mysqli_query($self_con,$sql);
    if($cont_order_type == 1) {
        for ($i = 1; $i <= count($cont_order_array); $i++) {
            $cont_idx = $cont_order_array[$i - 1];
            $sql = "update Gn_Iam_Contents set contents_order = '$i' where idx = '$cont_idx'";
            mysqli_query($self_con,$sql);
        }
        $sql = "select idx from Gn_Iam_Contents use index(westory_card_url) where idx not in (".$_POST[cont_order].") and mem_id = '{$_SESSION['iam_member_id']}' and westory_card_url ='$card_short_url'";
        $res = mysqli_query($self_con,$sql);
        while($row = mysqli_fetch_array($res)) {
            $sql = "delete from Gn_Iam_Contents where idx = '{$row['idx']}'";
            mysqli_query($self_con,$sql);

            $sql = "delete from Gn_Iam_Con_Card use index(cont_idx) where cont_idx = '{$row['idx']}'";
            mysqli_query($self_con,$sql);

            $sql = "delete from Gn_Iam_Mall where card_idx = '$contents_idx' and (mall_type = 3 or mall_type = 4)";
            mysqli_query($self_con,$sql);
        }
    }
    echo json_encode(array("result"=>"성공적으로 변경되었습니다."));
}
?>