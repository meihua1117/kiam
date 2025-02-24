<?php

function current_millis() {
    list($usec, $sec) = explode(" ", microtime());
    return round(((float)$usec + (float)$sec) * 1000);
}

$mid = 'M2587703';
$pay_type = 'CREDIT_CARD';
$pay_method = 'CREDIT_UNAUTH_API'; // 비인증: CREDIT_UNAUTH_API, 구인증: CREDIT_OLDAUTH_API
$order_no = '상점의고유주문번호'; // 중복되면 안됩니다. 영문숫자로 구성 공백 및 특수문자 사용금지
$amount = '1000';
$millis = current_millis();
$card_num = '1111222233334444'; // 승인되지 않는 번호입니다. 실제 승인테스트할 카드번호로 변경해주세요.
$apikey = "fa5159287186f86d2e76ccd007830776";
$iv = "cac9dfa1320c22055cac9f8b5ac3aa0b";

// 카드번호 암호화
$card_no = bin2hex(openssl_encrypt($card_num, 'AES-128-CBC', hex2bin($apikey), OPENSSL_RAW_DATA,hex2bin($iv)));

// 검증값 생성
$hash_value = hash("sha256", $mid.$pay_type.$pay_method.$order_no.$amount.$millis.$apikey);

$card_expiry_ym='2107'; // 2021년 07월
$user_name='테스터';
$prodPrice='1000';
$prodName='상품명';
$card_sell_mm='00'; // 일시불 00, 2개월 02, 12개월 12
$data = array(
    'mid' => $mid,
    'pay_type' => $pay_type,
    'pay_method' => $pay_method,
    'card_no' => $card_no,
    'card_expiry_ym' => $card_expiry_ym,
    'order_no'=> $order_no,
    'user_name'=>$user_name,
    'amount'=>$prodPrice,
    'product_name'=>$prodName,
    'card_sell_mm'=>$card_sell_mm,
    'millis'=>$millis,
    'hash_value'=>$hash_value
);

$payload = json_encode($data);

// Prepare new cURL resource
$ch = curl_init('https://payapi.welcomepayments.co.kr/api/payment/approval');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

// Set HTTP Header for POST request
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($payload))
);

// Submit the POST request
$result = curl_exec($ch);

// Close cURL session handle
curl_close($ch);

$data_array = json_decode($result, true);
print_r($data_array);exit;

?>
