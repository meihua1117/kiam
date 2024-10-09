<?php
header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
/*
아이디중복관리
*/
$id = trim($_REQUEST["id"]);
//아이디 유무 판단
if($id){
    $sql="select * from Gn_Member where mem_id='$id'";
    $resul=mysql_query($sql);
    $row=mysql_fetch_array($resul);
    if($row['mem_id'])
    {
        echo json_encode(array('result'=>true));
    }
    else{
        echo json_encode(array('result'=>false));
    }
}
?>