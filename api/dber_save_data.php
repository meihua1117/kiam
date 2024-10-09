<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
/*
크롤링 데이터 저장
입력파라미터

출력파라미터
 
*/

$token = $_POST["token"];

$sql = "SELECT user_id FROM crawler_member_real WHERE token = '$token'";
$result = mysql_query($sql);
$row=mysql_fetch_array($result);
if($row['user_id'] == "") {
    echo json_encode(array('result' => 1));
    exit;
}
$user_id = $row['user_id'];
$keyword = $_POST["keyword"];
$title = $_POST["title"];
$type = $_POST["type"];
$cell = $_POST["cell"];
$email = $_POST["email"];
$ceo = $_POST["ceo"];
$company_name = $_POST["company_name"];
$company_type = $_POST["company_type"];
$address = $_POST["address"];
$currentLink = $_POST["currentLink"];
$tag = $_POST["tag"];
$info = $_POST["info"];
$incword = $_POST["incword"];
$exword = $_POST["exword"];

$sql = "INSERT INTO crawler_data_2022 (user_id, keyword, page_title, data_type, cell, email, ceo, company_name, company_type, address, url, tag, info, incword, exword, regdate) 
VALUES ('$user_id','$keyword','$title','$type','$cell','$email','$ceo','$company_name','$company_type','$address','$currentLink','$tag','$info','$incword','$exword',NOW())";
mysql_query($sql);

    


?>