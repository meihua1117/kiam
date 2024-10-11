<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
/*
광고 관리
*/
$ad_position = trim($_REQUEST["ad_position"]);

if($ad_position == "window" || $ad_position == "dber") {
    $time = date("Y-m-d");
    $addQuery = "and send_start_date <= '$time' and send_end_date > '$time'";
    //$addQuery = "and start_time <= '$time' and end_time > '$time'";
}
$sql = "select * from crawler_ad_manager where ad_position='$ad_position' and use_yn='Y' $addQuery order by cam_id desc";
$result = mysqli_query($self_con,$sql);
//$info["display_time"] = "09,12,18,20";
$info["result"] = "1";
$k = 0;
while($row=mysqli_fetch_array($result)) {
    $info["data"][$k]['cam_id'] = $row['cam_id'];
    $info["data"][$k]['ad_position'] = $row['ad_position'];
    $info["data"][$k]['title'] = $row['title'];
    $info["data"][$k]['img_url'] = $row['img_url'];
    $info["data"][$k]['move_url'] = $row['move_url'];
    $info["data"][$k]['use_yn'] = $row['use_yn'];
    $info["data"][$k]['display_time'] = $row['display_time'];
    $info["data"][$k]['view_time'] = $row['view_time'];
    $info["data"][$k]['start_time'] = $row['start_time'];
    $info["data"][$k]['end_time'] = $row['end_time'];
    $k++;
}
echo json_encode($info);
?>