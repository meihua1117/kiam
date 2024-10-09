<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
$date = date("Y-m-d H:i:s");
// echo $position; exit; 
if($position == "logo"){
    $ad_position = "L";
    $display_order = 1;
    $move_url = $logo_move_url;
    $desc = "";
    $file_name = $_FILES['app_logo']['name'];
    $temp_name = $_FILES['app_logo']['tmp_name'];
    $file_size = $_FILES['app_logo']['size'];
}
else if($position == "rolling"){
    $ad_position = "R";
    $display_order = $rolling_display_order;
    $move_url = $rolling_move_url;
    $desc = "";
    $file_name = $_FILES['app_rolling']['name'];
    $temp_name = $_FILES['app_rolling']['tmp_name'];
    $file_size = $_FILES['app_rolling']['size'];
}
else if($position == "menu"){
    $ad_position = "M";
    $display_order = $menu_display_order;
    $move_url = $menu_move_url;
    $title = $menu_title;
    $desc = "";
    $file_name = $_FILES['app_menu']['name'];
    $temp_name = $_FILES['app_menu']['tmp_name'];
    $file_size = $_FILES['app_menu']['size'];
}
else if($position == "market"){
    $ad_position = "I";
    $display_order = $market_display_order;
    $move_url = $market_move_url;
    $title = $market_title;
    $desc = "";
    $file_name = $_FILES['app_market']['name'];
    $temp_name = $_FILES['app_market']['tmp_name'];
    $file_size = $_FILES['app_market']['size'];
}
else if($position == "card"){
    $ad_position = "C";
    $display_order = $card_display_order;
    $move_url = $card_move_url;
    $title = $card_title;
    $desc = "";
    $file_name = $_FILES['app_card']['name'];
    $temp_name = $_FILES['app_card']['tmp_name'];
    $file_size = $_FILES['app_card']['size'];
}
else if($position == "banner"){
    $ad_position = "B";
    $display_order = $banner_display_order;
    $move_url = $banner_move_url;
    $desc = "";
    $file_name = $_FILES['app_banner']['name'];
    $temp_name = $_FILES['app_banner']['tmp_name'];
    $file_size = $_FILES['app_banner']['size'];
}
else if($position == "change"){
    $ad_position = "E";
    $display_order = 1;
    $move_url = $change_move_url;
    $desc = "";
    $file_name = $_FILES['app_change']['name'];
    $temp_name = $_FILES['app_change']['tmp_name'];
    $file_size = $_FILES['app_change']['size'];
}

if($idx == "") {    
    if($file_name) {
        $info = explode(".",$file_name);
        $ext = $info[count($info)-1];
        $filename = mktime().".".$ext;
        $img_path = "http://www.kiam.kr".gcUpload($file_name, $temp_name, $file_size, "ad", $filename);
    }
    $query="insert into Gn_App_Home_Manager set `title`  ='$title', 
                                  `move_url`         ='$move_url', 
                                  `img_url`        ='$img_path', 
                                  `ad_position`      ='$ad_position', 
                                  `site_iam` = '$site_iam',
                                  `display_order`      ='$display_order',
                                  `reg_date` = '$date'
                                 ";
    mysql_query($query);	
} else {
    if($file_name) {
        $info = explode(".",$file_name);
        $ext = $info[count($info)-1];
        $filename = mktime().".".$ext;
        $img_path = "http://www.kiam.kr".gcUpload($file_name, $temp_name, $file_size, "ad", $filename);
    }
    if($img_path)
        $addQuery = " `img_url`        ='$img_path', ";

    if($mode == "save"){
        $query="insert into Gn_App_Home_Manager set `title` ='$title', 
                                                    `move_url` ='$move_url', 
                                                    `img_url` ='$img_path', 
                                                    `ad_position` ='$ad_position', 
                                                    `site_iam` = '$site_iam',
                                                    `display_order` ='$display_order',
                                                    `reg_date` = '$date'
                                                ";
    }
    else{
        $query="update  Gn_App_Home_Manager set `title`  ='$title', 
                                                `move_url` ='$move_url', 
                                                `ad_position` ='$ad_position', 
                                                `display_order` ='$display_order', 
                                                $addQuery
                                                `site_iam` ='$site_iam',
                                                `up_date` = '$date'
                                        WHERE idx='$idx'
                                            ";
    }
    mysql_query($query);	
}
//echo "<script>alert('".$query."')</script>";
echo "<script>location='/admin/app_home_menu.php?site={$site_iam}';</script>";
exit;
?>