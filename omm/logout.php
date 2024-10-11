<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";

$id = $_POST['id'];
$mem_token = $_POST['mem_token'];
$phone_num = $_POST['phone_num'];

if(!$mem_token || !check_token($phone_num, $mem_token)){
    $ret = array("result"=>"0");
    echo json_encode($ret);
}
else{
    $sql = "update Gn_Member set mem_token = '' where mem_id = '$id'";
    $result = mysqli_query($self_con,$sql);

    $sql = "update gn_mms_token set token = '' where phone_num = '$phone_num'";
    $result = mysqli_query($self_con,$sql);
    
    $ret = array("result"=>"1");
    echo json_encode($ret);
}
?>
<script>
    AppScript.setClearCache();
</script>
