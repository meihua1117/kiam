<?php
set_time_limit(0);
ini_set('memory_limit','-1');
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
/*
크롤링 데이터 저장
입력파라미터

출력파라미터
 
*/

$token = $_POST["token"];

$sql = "SELECT user_id FROM crawler_member_real WHERE token = '$token'";
$result = mysqli_query($self_con, $sql);
$row=mysqli_fetch_array($result);
if($row['user_id'] == "") {
    echo json_encode(array('result' => 1));
    exit;
}

$user_id = $row['user_id'];
$count = $_POST["count"];
//$json = str_replace("\\", "", $_POST["items"]);
$json = base64_decode($_POST["items"]);
$items = json_decode( $json);

$sql = "INSERT INTO crawler_data_2022 (user_id, keyword, page_title, data_type, cell, email, ceo, company_name, company_type, address, url, tag, info, incword, exword, regdate) 
    VALUES ";
for($i = 0; $i < count($items) ; $i++)
{
    if($i != 0)
        $sql .= ",";
    $keyword = $items[$i]->keyword;
    $title = $items[$i]->title;
    $type = $items[$i]->type;
    $cell = $items[$i]->cell;
    $email = $items[$i]->email;
    $ceo = $items[$i]->ceo;
    $company_name = $items[$i]->company_name;
    $company_type = $items[$i]->company_type;
    $address = $items[$i]->address;
    $currentLink = $items[$i]->currentLink;
    $tag = $items[$i]->tag;
    $info = $items[$i]->info;
    $incword = $items[$i]->incword;
    $exword = $items[$i]->exword;
    
    $sql .= "('$user_id','$keyword','$title','$type','$cell','$email','$ceo','$company_name','$company_type','$address','$currentLink','$tag','$info','$incword','$exword',NOW())";
   
}

//echo json_encode(array('result' => 0, 'sql' => $sql));
mysqli_query($self_con, $sql);





    


?>