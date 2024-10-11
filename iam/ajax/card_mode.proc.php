<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
if(isset($_POST['card_show'])){
    $mem_id = $_POST['id'];
    $state = $_POST['state'];
    $card_short_url = $_POST['short_url'];
    $update = "update Gn_Iam_Name_Card set card_show={$state} where mem_id='{$mem_id}' and card_short_url='{$card_short_url}'";
    $result = mysqli_query($self_con,$update) or die(mysqli_error($self_con));
    echo $result;
    exit;
}
else if(isset($_POST['start_card'])){
    $mem_id = $_POST['mem_id'];
    $query = "select iam_card_cnt from Gn_Member where mem_id='$mem_id'";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);

    $query = "select count(*) as cnt from Gn_Iam_Name_Card where group_id is NULL and mem_id='".$mem_id."'";
    $res = mysqli_query($self_con,$query);
    $data = mysqli_fetch_array($res);
    if($row['iam_card_cnt'] <= $data[0]) {
        $result = "고객님의 아이엠은 {$row['iam_card_cnt']}개의 카드를 사용할수 있습니다. 추가하시려면 관리자에게 문의하세요.";
        echo json_encode(array("mem_code"=>"iam_card_cnt", "result"=>$result));
    }else if($_POST['type'] == "first"){
        $sql_mem_info = "select * from Gn_Member where mem_id='{$mem_id}'";
        $res_mem = mysqli_query($self_con,$sql_mem_info);
        $row_mem = mysqli_fetch_array($res_mem);
    
        $date = date("Y-m-d");
        $mem_code = $row_mem['mem_code'];
        $mem_name = $row_mem['mem_name'];
        $mem_phone = $row_mem['mem_phone'];
        $short_url_db1 = generateRandomString();
        $card_title = $mem_name . " 소개";
        $img_url = "/iam/img/common/logo-2.png";
    
        $sql2 = "insert into Gn_Iam_Name_Card (mem_id, card_title, card_short_url, card_name, card_company, card_position, card_phone, card_email, card_addr, card_map, card_keyword, profile_logo, favorite, story_title4, story_online1_text,".
                                    "story_online1, online1_check, story_online2_text, story_online2, online2_check,req_data,main_img1,main_img2,main_img3,story_title1,story_title2,story_title3,story_myinfo,story_company,story_career)".
                                "values ('$mem_id', '$card_title', '$short_url_db1', '$mem_name', '', '', '$mem_phone', '@', '', '', '', '$img_url', 0, '온라인정보','','', '', '', '', '', '{$date}','','','','','','','','','')";
        $result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
        echo json_encode(array("mem_code"=>$mem_code, "short_url"=>$short_url_db1));
    }else{
        echo json_encode(array("mem_code"=>"success"));
    }
    exit;
}
$card_mode = $_POST['card_mode'];
$mem_id = $_POST['mem_id'];
$sql="update Gn_Member set card_mode = '$card_mode' where mem_id = '$mem_id'";
$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
exit;
?>
