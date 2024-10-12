<?
@header("Content-type: text/html; charset=utf-8");
include_once "../../lib/rlatjd_fun.php";
$alarm_sql = "select * from Gn_Search_Key where key_id = 'content_alarm_time'";
$alarm_res = mysqli_query($self_con,$alarm_sql);
$alarm_row = mysqli_fetch_array($alarm_res);
$alarm_time = $alarm_row['key_content'];
$alarm_sql = "select * from Gn_Search_Key where key_id = 'content_notice_count'";
$alarm_res = mysqli_query($self_con,$alarm_sql);
$alarm_row = mysqli_fetch_array($alarm_res);
$notice_count = $alarm_row['key_content'];
if($_POST['type'] == 'interval') {
    $time = date("Y-m-d H:i:s", strtotime("-".$alarm_time." minutes"));
    $sql = "select count(*) from Gn_Iam_Contents where req_data >= '$time'";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    $count = $row[0];
    if($count > 0){
        $sql = "select mem_id from Gn_Iam_Contents where req_data >= '$time'";
        $res = mysqli_query($self_con,$sql);
        $mem_array = array();
        while($row = mysqli_fetch_array($res)){
            array_push($mem_array,$row['mem_id']);
        }
        $mem_array = array_unique($mem_array);
        $desc = count($mem_array) . "명 회원분들의 " . $count . "개 콘텐츠가 게시되었습니다.";
        echo json_encode(array("desc" => $desc));
    }
}else if($_POST['type'] == 'notice_popup') {
    $result = array();
    $arr_Mems = array();
    $index = 0;
    $sIdx = 0;
    $limit = 1000;
    while($index < $notice_count)
    {
        $sql = "select mem_id, westory_card_url, req_data from Gn_Iam_Contents order by idx desc limit $sIdx,$limit";
        $res = mysqli_query($self_con,$sql);
        $f_count = mysqli_num_rows($res);
        while($row = mysqli_fetch_array($res)){
            $mem_id = $row['mem_id'];
            if($mem_id == "" || in_array($mem_id, $arr_Mems))
                continue;
            $arr_Mems[] = $mem_id;
            $mem_sql = "select mem_code,mem_name,site_iam,profile from Gn_Member where mem_id='$mem_id'";
            $mem_res = mysqli_query($self_con,$mem_sql);
            $mem_row = mysqli_fetch_array($mem_res);
            $profile = $mem_row['profile'];
    
            $card_sql = "select main_img1,card_short_url,card_name,card_company from Gn_Iam_Name_Card where card_short_url ='".$row[westory_card_url]."' order by req_data limit 0,1";
            $card_res = mysqli_query($self_con,$card_sql);
            $card_row = mysqli_fetch_array($card_res);
            if($profile == "" || is_null($profile))
                $profile = $card_row['main_img1'];
            if($profile || is_null($profile))
                $profile = "iam/img/profile_img.png";
            $link = "http://".($mem_row['site_iam'] == "kiam"?"kiam.kr?":$mem_row['site_iam'].".kiam.kr?").$card_row['card_short_url'].$mem_row['mem_code'];
    
            $time = date("Y-m-d H:i:s", strtotime($row['req_data']."-".$alarm_time." minutes"));
            $count_sql = "select count(*) from Gn_Iam_Contents where mem_id = '$mem_id' and req_data >= '$time'";
            $count_res = mysqli_query($self_con,$count_sql);
            $count_row = mysqli_fetch_array($count_res);
            $count = $count_row[0];
            if($count == 0)
                $count = 1;
            $card_name = mb_substr($card_row['card_name'], 0, 4, "utf-8");
            $card_company = explode("/", $card_row['card_company']);
            if ($card_company[1] != "")
                $company = "(" . mb_substr($card_company[0], 0, 6, "utf-8") . "/" . mb_substr($card_company[1], 0, 6, "utf-8") . ")";
            else
                $company = "(" . mb_substr($card_company[1], 0, 12, "utf-8") . ")";
            $card_name .= $company;
            $name = $mem_row['site_iam'] . "의 " . $card_name . "님의";
            if (mb_strlen($name, "utf-8") > 15) {
                $desc1 = mb_substr($name, 0, 15, "utf-8");
                $desc2 = mb_substr($name, 15, 3, "utf-8").(mb_strlen($name, "utf-8") > 18 ? ")":"")." 콘텐츠가 " . $count . "개 올라왔습니다.";
            }else{
                $desc1 = $name;
                $desc2 = "콘텐츠가 " . $count . "개 올라왔습니다.";
            }
            $array = array("name" => $desc1, "count" => $desc2, "profile"=>$profile, "link"=>$link);
            array_push($result,$array);
            $index++;
            if($index == $notice_count)
                break;
        }
        if($f_count < $limit)
            break;
        $sIdx += $limit;     
    }
    echo json_encode(array("result" => $result));
}else if($_POST['type'] == 'sample_popup') {
    $sql = "select mem_id,main_img1,card_name,card_company,card_short_url from Gn_Iam_Name_Card where group_id is NULL and sample_click = 'Y' order by sample_order desc,req_data";
    $res = mysqli_query($self_con,$sql);
    $result = array();
    while($row = mysqli_fetch_array($res)) {
        $mem_id = $row['mem_id'];
        $profile = $row['main_img1'];
        if ($mem_id == "")
            continue;
        $mem_sql = "select mem_code,mem_name,site_iam,profile from Gn_Member where mem_id='$mem_id'";
        $mem_res = mysqli_query($self_con,$mem_sql);
        $mem_row = mysqli_fetch_array($mem_res);
        if ($profile == "" || is_null($profile))
            $profile = $mem_row['profile'];
        if($profile || is_null($profile))
            $profile = "img/profile_img.png";
        $card_name = mb_substr($row['card_name'], 0, 4, "utf-8");
        $card_company = explode("/", $row['card_company']);
        if ($card_company[1] != "")
            $company = "(" . mb_substr($card_company[0], 0, 6, "utf-8") . "/" . mb_substr($card_company[1], 0, 6, "utf-8") . ")";
        else
            $company = "(" . mb_substr($card_company[0], 0, 12, "utf-8") . ")";
        $card_name .= $company;
        $link = "http://" . ($mem_row['site_iam'] == "kiam" ? "kiam.kr?" : $mem_row['site_iam'] . ".kiam.kr?") . $row['card_short_url'] . $mem_row['mem_code'];
        $name = $mem_row['site_iam'] . "의 " . $card_name . "님의";
        if (mb_strlen($name, "utf-8") > 15) {
            $name1 = mb_substr($name, 0, 15, "utf-8");
            $name2 = mb_substr($name, 15, 9, "utf-8").(mb_strlen($name, "utf-8") > 24 ? ")":"")." 카드보러 가기";
        }else{
            $name1 = $name;
            $name2 = "카드보러 가기";
        }
        $array = array("name1" => $name1,"name2"=>$name2, "profile"=>$profile, "link"=>$link);
        array_push($result,$array);
    }
    echo json_encode(array("result" => $result));
}else if($_POST['type'] == 'alarm_like'){
    $mem_id = $_POST['mem_id'];
    $like_id = $_POST[like_id];
    $sql = "select mem_like from Gn_Member where mem_id = '$mem_id'";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    $mem_like = $row['mem_like'];
    $mem_array = explode(",",$mem_like);
    if($mem_array[0] == "")
        array_shift($mem_array);
    array_push($mem_array,$like_id);
    $mem_array = array_unique($mem_array);
    $mem_like = implode(",",$mem_array);
    $sql = "update Gn_Member set mem_like = '$mem_like' where mem_id = '$mem_id'";
    mysqli_query($self_con,$sql);
}
?>
