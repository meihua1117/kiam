<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
# 회원가입
(개발)https://sob.tagby.kr/api/saas/reg_account.php
(운영) https://www.tagby.kr/api/saas/reg_account.php
*/
$saas_id = "onlyone";
$account_idx = "1";
$account_email = "turbolight@daum.net";
$account_phone = "010-8888-9999";
$data = array(
    'saas_id' => $saas_id,
    'account_idx' => $account_idx,
    'account_email' => $account_email,
    'account_phone' => $account_phone
);
 
$url = "https://sob.tagby.kr/api/saas/reg_account.php";
$url = "https://sob.tagby.kr/api/saas/reg_account.php";

 
 $ch = curl_init(); 
 curl_setopt($ch, CURLOPT_URL, $url); 

 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
 curl_setopt($ch, CURLOPT_SSLVERSION, 6);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'ecdhe_rsa_aes_128_gcm_sha_256');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_VERBOSE,true);
 curl_setopt($ch, CURLOPT_HEADER, 0); 
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
 curl_setopt($ch, CURLOPT_POST, 1); 
 curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 

 $data = curl_exec($ch); 
 if (curl_error($ch))  
 { 
    exit('CURL Error('.curl_errno( $ch ).') '.

                                        curl_error($ch)); 
 } 
 curl_close($ch); 

 print_r($data); 

/*
#토큰 발급
(개발)http://sob.tagby.kr/api/saas/get_login_token.php
(운영) https://www.tagby.kr/api/saas/get_login_token.php
*/



/*
#로그인
(개발)http://sob.tagby.kr/api/saas/login.php
(운영) https://www.tagby.kr/api/saas/login.php
*/
