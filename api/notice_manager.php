<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
/*
광고 관리
*/
$ad_position = trim($_REQUEST["ad_position"]);


$sql = "select * from tjd_board where category='1' and diber='Y' order by no desc";
$result = mysqli_query($self_con,$sql);
$info["result"] = "1";
$k = 0;
while($row=mysqli_fetch_array($result)) {
    $info["data"][$k]['no'] = $row['no'];
    $info["data"][$k]['title'] = $row['title'];
    //$info["data"][$k]['img_url'] = $row['img_url'];
    $info["data"][$k]['move_url'] = "/cliente_list.php?status=1&one_no={$row['no']}";
    $k++;
}
echo json_encode($info);
?>