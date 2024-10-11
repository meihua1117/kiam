<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
# 설정파일을 php자체 에서도 바꿀 수 있음(이 파일 위해서만), 1은 on을 의미, display_errors는 디버그
$uploaddir = '../../upload/';

$mode = $_POST['mode'];
$contents_idx = $_POST['contents_idx'];
$like_id = $_POST['like_id'];
$product_seperate = $_POST['product_seperate'];
$gwc_con_state = isset($_POST['gwc_con_state'])?$_POST['gwc_con_state']:'0';

if($product_seperate != "" && $gwc_con_state){
    $contents_table_name = "Gn_Iam_Contents_Gwc";
}
else{
    $contents_table_name = "Gn_Iam_Contents";
}
# name값이 userfile인 input에 들어있는 name이 파일이름이 되게 해라(uploaddir에 추가)
# basename은 파일 이름이 myapp\black.png 일때 black만 뽑아내서 파일이름이 되게 함

// echo $card_name;
# 배열 같은게 각 줄마다 나와서 보기좋게 함
$sql = "select contents_like from ".$contents_table_name." where idx='$contents_idx'";
$res = mysqli_query($self_con,$sql);
$row = mysqli_fetch_array($res);
if($row[0] == ""){
    $contents_like = $like_id;
    $like_count = 1;
    $like_status = "Y";
}else{
    $ids = explode(",",$row[0]);
    if (in_array($like_id, $ids)){
        $ids = array_diff($ids,array($like_id));
        $like_status = "N";
    }else{
        array_push($ids,$like_id);
        $like_status = "Y";
    }
    $like_count = count($ids);
    if($like_count == 0)
        $contents_like = "";
    else
        $contents_like = implode(",",$ids);
}

$sql="update ".$contents_table_name." set contents_like = '$contents_like' where idx = '$contents_idx'";
mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
echo json_encode(array("count"=>$like_count,"like_status"=>$like_status));
exit;
?>
