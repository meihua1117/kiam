<?php

$client_id = "4YcN5sW997pxrnx0M48x";
$client_secret = "5I8bLgV1o6";

$url = "https://openapi.naver.com/v1/datalab/shopping/categories";
$category_arr = array();
$category1 = array("name"=>"패션의류","param"=>array("50000000"));
$category2 = array("name"=>"화장품/미용","param"=>array("50000002"));
array_push($category_arr,$category1);
array_push($category_arr,$category2);
$body = json_encode(array("startDate"=>"2017-08-01","endDate"=>"2017-09-30","timeUnit"=>"month","category"=>$category_arr,"device"=>"pc","ages"=>array("20","30"),"gender"=>"f"));
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers = array();
$headers[] = "X-Naver-Client-Id: ".$client_id;
$headers[] = "X-Naver-Client-Secret: ".$client_secret;
$headers[] = "Content-Type: application/json";
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

$response = curl_exec ($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
echo "status_code:".$status_code."
";
curl_close ($ch);
if($status_code == 200) {
    echo $response;
} else {
    echo "Error 내용:".$response;
}
?>