<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$card_num = $_GET['card_num'];
$_SERVER['REQUEST_URI'] = urldecode($_SERVER['REQUEST_URI']);
$request_url =$_SERVER['REQUEST_URI'];
$cur_win = $_GET['cur_win'];
if(!$cur_win && !$_SESSION['iam_member_id'])
    $cur_win = "my_info";
if($_SESSION['iam_member_id']) {
    //아이엠이 없으면 창조한다.
    $sql="select count(idx) from Gn_Iam_Info where mem_id = '{$_SESSION['iam_member_id']}'";
    $sql_count = mysqli_query($self_con,$sql);
    $comment_row = mysqli_fetch_array($sql_count);

    if((int)$comment_row[0] == 0 && $HTTP_HOST == "kiam.kr") {
        $sql2="insert into Gn_Iam_Info (mem_id, reg_data) values ('{$_SESSION['iam_member_id']}', now())";
        $result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
    }

    $check_sql="select count(idx) from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}'";
    $check_result=mysqli_query($self_con,$check_sql);
    $check_comment_row=mysqli_fetch_array($check_result);
    if (!$check_comment_row[0]) {//네임카드가 하나도 없으면 자동으로 한개 생성한다
        $Gn_mem_row = $member_iam;
        $site_name = $Gn_mem_row['site'];
        $card_name = $Gn_mem_row['mem_name'];
        $card_title = $Gn_mem_row['mem_name']." 소개";
        $card_phone = $Gn_mem_row['mem_phone'];
        $card_email = $Gn_mem_row['mem_email'];
        $card_addr1 = $Gn_mem_row['mem_add1'];
        $card_addr2 = $Gn_mem_row['mem_add2'];
        if (!strpos($card_phone, "-")) {
            if (strlen($card_phone) > 10) {
                $card_phone1 = substr($card_phone, 0, 3);
                $card_phone2 = substr($card_phone, 3, 4);
                $card_phone3 = substr($card_phone, 7, 4);
                $card_phone = $card_phone1 . "-" . $card_phone2 . "-" . $card_phone3;
            } else {
                $card_phone1 = substr($card_phone, 0, 3);
                $card_phone2 = substr($card_phone, 3, 3);
                $card_phone3 = substr($card_phone, 6, 4);
                $card_phone = $card_phone1 . "-" . $card_phone2 . "-" . $card_phone3;
            }
        }
        $card_addr = $card_addr1 . " " . $card_addr2;
        $short_url = generateRandomString();
        $img_url = "/iam/img/common/logo-2.png";
        $name_card_sql = "insert into Gn_Iam_Name_Card (mem_id, card_short_url, card_title,card_name, card_phone, card_email, card_addr, profile_logo, req_data,up_data) 
                            values ('{$_SESSION['iam_member_id']}', '$short_url', '$card_title','$card_name', '$card_phone', '$card_email', '$card_addr', '$img_url', now(), now())";
        mysqli_query($self_con,$name_card_sql) or die(mysqli_error($self_con));
    }
}
if(strstr($request_url, '?') == false) {
    if(!$_SESSION['iam_member_id']) {//비로긴이면 분양사의 1번카드를 노출시킨다.
        if ($HTTP_HOST != "kiam.kr") //분양사사이트이면
            $query = "select * from Gn_Iam_Service where sub_domain like '%://" . $HTTP_HOST . "%'";
        else
            $query = "select profile_idx from Gn_Iam_Service where sub_domain like '%www.kiam.kr%'";
        $result = mysqli_query($self_con,$query);
        $row = mysqli_fetch_array($result);
        $card_idx = $row['profile_idx'];
        $query = "select mem_id, card_short_url, main_img1 from Gn_Iam_Name_Card where idx = '$card_idx'";
        $result = mysqli_query($self_con,$query);
        $row = mysqli_fetch_array($result);
        $card_url = $row['card_short_url'];
        $main_img1_ins = $row['main_img1'];
        $query = "select mem_code from Gn_Member where mem_id = '$row[mem_id]'";
        $result = mysqli_query($self_con,$query);
        $row = mysqli_fetch_array($result);
        $card_url .= $row['mem_code'];
        echo "<script>location.href='/?".$card_url."';</script>";
    }else{
        $query = "select mem_id, card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' order by req_data limit 0,1";
        $result = mysqli_query($self_con,$query);
        $row = mysqli_fetch_array($result);
        $card_url = $row['card_short_url'];
        $query = "select mem_code from Gn_Member where mem_id = '$row[mem_id]'";
        $result = mysqli_query($self_con,$query);
        $row = mysqli_fetch_array($result);
        if($cur_win == "my_info"){
            $card_url .= $row['mem_code'];
            echo "<script>location.href='/?".$card_url."';</script>";
        }else{
            $card_owner = $_SESSION['iam_member_id'];
            $request_short_url = $card_url;
            $card_owner_code = $row['mem_code'];
        }
    }
}else{
    if($cur_win == "share_iam"){
        $request_url_arr = explode('?', $request_url);
        $request_short_url = substr($request_url_arr[1], 0, 10);
        echo "<script>location.href='/?".$request_short_url."';</script>";
    }
    if(!$cur_win || $cur_win == "my_info"){
        $request_url_arr = explode('?', $request_url);
        $param_str = substr($request_url_arr[1], 10);
        $param_array = explode("&", $param_str);
        $card_owner_code = $param_array[0];
        $request_short_url = substr($request_url_arr[1],0,10);

        $short_sql="select idx, mem_id from Gn_Iam_Name_Card where card_short_url = '$request_short_url'";
        $short_result=mysqli_query($self_con,$short_sql);
        $short_row=mysqli_fetch_array($short_result);
        if (!$short_row) {
            echo "<script>alert('잘못된 카드링크입니다." . $request_short_url . "');history.back();</script>";
        }
        if($card_owner_code) {
            $mem_sql = "select mem_id from Gn_Member where mem_code = '$card_owner_code'";
            $mem_res = mysqli_query($self_con,$mem_sql);
            $mem_row = mysqli_fetch_array($mem_res);
            if (!$mem_row) {
                echo "<script>alert('잘못된 카드링크입니다." . $request_short_url . "');history.back();</script>";
            }
            $card_owner = $mem_row[mem_id];//방문하는 아이엠 소유자
        }else{
            $mem_sql = "select mem_code from Gn_Member where mem_id = '$short_row[mem_id]'";
            $mem_res = mysqli_query($self_con,$mem_sql);
            $mem_row = mysqli_fetch_array($mem_res);
            $card_owner_code = $mem_row['mem_code'];
            $card_owner = $short_row[mem_id];//방문하는 아이엠 소유자
        }
        $card_master = $short_row[mem_id];//현재 선택된 카드소유자
        $cur_win = "my_info";
    }else{
        if($_SESSION['iam_member_id']){
            $query = "select mem_id, card_short_url from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' order by req_data limit 0,1";
            $result = mysqli_query($self_con,$query);
            $row = mysqli_fetch_array($result);
            $card_url = $row['card_short_url'];
            $query = "select mem_code from Gn_Member where mem_id = '$row[mem_id]'";
            $result = mysqli_query($self_con,$query);
            $row = mysqli_fetch_array($result);
            $card_owner = $_SESSION['iam_member_id'];
            $request_short_url = $card_url;
            $card_owner_code = $row['mem_code'];
        }else{
            if ($HTTP_HOST != "kiam.kr") //遺꾩뼇?ъ궗?댄듃?대㈃
                $query = "select profile_idx from Gn_Iam_Service where sub_domain like '%://" . $HTTP_HOST . "%'";
            else
                $query = "select profile_idx from Gn_Iam_Service where sub_domain like '%www.kiam.kr%'";
            $result = mysqli_query($self_con,$query);
            $row = mysqli_fetch_array($result);
            $card_idx = $row['profile_idx'];
            $query = "select * from Gn_Iam_Name_Card where idx = '$card_idx'";
            $result = mysqli_query($self_con,$query);
            $row = mysqli_fetch_array($result);
            $request_short_url = $row['card_short_url'];
            $query = "select mem_code from Gn_Member where mem_id = '$row[mem_id]'";
            $result = mysqli_query($self_con,$query);
            $row = mysqli_fetch_array($result);
            $card_owner_code = $row['mem_code'];
            //exit;
        }
    }
}
@setcookie("recommender_code", $card_owner_code, time()+3600 ,"/");
$_COOKIE['recommender_code'] = $card_owner_code;
?>
