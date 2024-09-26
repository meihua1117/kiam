<?php 
header("Content-Type: text/html; charset=UTF-8");

$secret_key = "!tagby$";

$plaintext = "010-8825-4394";
include_once('Crypt/AES.php');

$cipher = new Crypt_AES(CRYPT_MODE_CBC);
$cipher->setKey($secret_key);
//
//$size = 10 * 1024;
//$plaintext = str_repeat('a', $size);
//
echo base64_encode($cipher->encrypt($plaintext))."<BR>";
echo $cipher->decrypt($cipher->encrypt($plaintext));           

// 문자열을 암호화한다.

function aes_encrypt($plaintext, $password)
{
    // 보안을 최대화하기 위해 비밀번호를 해싱한다.
    
    //$password = hash('sha256', $password, true);
    
    // 용량 절감과 보안 향상을 위해 평문을 압축한다.
    
    //$plaintext = gzcompress($plaintext);
    
    // 초기화 벡터를 생성한다.
    
    //$iv_source = defined('MCRYPT_DEV_URANDOM') ? MCRYPT_DEV_URANDOM : MCRYPT_RAND;
    //$iv = mcrypt_create_iv(32, $iv_source);
    //echo $iv;
    $iv = str_repeat(chr(0), 32);
    
    // 암호화한다.
    
    $ciphertext = mcrypt_encrypt('rijndael-256', $password, $plaintext, 'cbc', $iv);
    
    
    // 위변조 방지를 위한 HMAC 코드를 생성한다. (encrypt-then-MAC)
    
    $hmac = hash_hmac('sha256', $ciphertext, $password, true);
    
    // 암호문, 초기화 벡터, HMAC 코드를 합하여 반환한다.
    
//    return base64_encode($ciphertext . $iv . $hmac);
    return base64_encode($ciphertext);
}

// 위의 함수로 암호화한 문자열을 복호화한다.
// 복호화 과정에서 오류가 발생하거나 위변조가 의심되는 경우 false를 반환한다.

function aes_decrypt($ciphertext, $password)
{
    // 초기화 벡터와 HMAC 코드를 암호문에서 분리하고 각각의 길이를 체크한다.
    
    $ciphertext = @base64_decode($ciphertext, true);
  //  if ($ciphertext === false) return false;
  //  $len = strlen($ciphertext);
  //  if ($len < 64) return false;
  //  $iv = substr($ciphertext, $len - 64, 32);
  //  $hmac = substr($ciphertext, $len - 32, 32);
  //  $ciphertext = substr($ciphertext, 0, $len - 64);
    $iv = str_repeat(chr(0), 32);
    
    // 암호화 함수와 같이 비밀번호를 해싱한다.
    
    //$password = hash('sha256', $password, true);
    
    // HMAC 코드를 사용하여 위변조 여부를 체크한다.
    
    //$hmac_check = hash_hmac('sha256', $ciphertext, $password, true);
    //if ($hmac !== $hmac_check) return false;
    
    // 복호화한다.
    
    $plaintext = @mcrypt_decrypt('rijndael-256', $password, $ciphertext, 'cbc', $iv);
    if ($plaintext === false) return false;
    
    // 압축을 해제하여 평문을 얻는다.
    
    //$plaintext = @gzuncompress($plaintext);
    //if ($plaintext === false) return false;
    
    // 이상이 없는 경우 평문을 반환한다.
    
    return $plaintext;
} 

$strAESKey128 = $secret_key;  // 16자리
$strAESKeyIV = str_repeat(chr(0), 16); #Same as in JAVA  16자리

function encrypt ($value)
{               
    global $strAESKey128, $strAESKeyIV;
    $padSize = 16 - (strlen ($value) % 16) ;
    $output = mcrypt_encrypt (MCRYPT_RIJNDAEL_256, $strAESKey128, $value, MCRYPT_MODE_CBC, str_repeat(chr(0),32)) ;               
    return base64_encode( ($output)) ;       
}

function decrypt ($value)       
{                    
    global $strAESKey128, $strAESKeyIV;
    $value = base64_decode($value);               
    $output = mcrypt_decrypt (MCRYPT_RIJNDAEL_256, $strAESKey128, $value, MCRYPT_MODE_CBC, str_repeat(chr(0),16)) ;               
    //echo $output;
   /*
    $valueLen = strlen ($output) ;
    if ( $valueLen % 16 > 0 )
        $output = "";

    $padSize = ord ($output{$valueLen - 1}) ;
    if ( ($padSize < 1) or ($padSize > 16) )
        $output = "";                // Check padding.               

    for ($i = 0; $i < $padSize; $i++)
    {
        if ( ord ($output{$valueLen - $i - 1}) != $padSize )
            $output = "";
    }
    $output = substr ($output, 0, $valueLen - $padSize) ;
*/
    return $output;       
}

$str = "안녕하세요. 하보니입니다.";

/*

$encrypted = aes_encrypt($str, $secret_key);
echo "암호화 문자열 => " .$encrypted. "<br />\n";
//
$decrypted = aes_decrypt($encrypted, $secret_key);
echo "복호화 문자열 => " .$decrypted. "\n";


$encrypted = encrypt($str);
echo "암호화 문자열 => " .$encrypted. "<br />\n";

$decrypted = decrypt($encrypted);
echo "복호화 문자열 => " .$decrypted. "\n";
*/


/*
# 회원가입
(개발)https://sob.tagby.kr/api/saas/reg_account.php
(운영) https://www.tagby.kr/api/saas/reg_account.php
1. 암호화 key : !tagby$
2. 암호화가 적용되는 parameter
    - 회원 가입 API : account_email, account_phone
3. 암호화 후 base64 인코딩 부탁 드려요.
[김영수 팀장  태그바이 개발자] [오전 10:59] 파일: TAGby 간편 로그인 연동 규격.doc
*/

$saas_id = "onlyone";
$account_idx = "11";
$account_email = "turbolight@naver.com";
$account_phone = "010-1111-2222";

$data = array(
    'saas_id' => $saas_id,
    'account_idx' => $account_idx,
    //'account_email' => aes_encrypt($account_email, $secret_key),
    //'account_phone' => aes_encrypt($account_phone, $secret_key)
    'account_email' => encrypt($account_email),
    'account_phone' => encrypt($account_phone)    
);
echo "<pre>";
print_r($data);
 
$url = "http://sob.tagby.kr/api/saas/reg_account.php";

echo encrypt($data[account_email], $secret_key)."<BR>";
echo encrypt($data[account_phone], $secret_key);
 $ch = curl_init(); 
 curl_setopt($ch, CURLOPT_URL, $url); 

 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
 //curl_setopt($ch, CURLOPT_SSLVERSION, 5);
 curl_setopt($ch, CURLOPT_HEADER, 1);
 //curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'ecdhe_rsa_aes_128_gcm_sha_256');
 //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
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

 print_r(json_decode($data)); 
exit;
  
/*
#토큰 발급
(개발)http://sob.tagby.kr/api/saas/get_login_token.php
(운영) https://www.tagby.kr/api/saas/get_login_token.php
*/
$saas_id = "onlyone";
$account_idx = "1";
$account_email = "turbolight@daum.net";
$account_phone = "010-8888-9999";
$data = array(
    'saas_id' => $saas_id,
    'account_idx' => $account_idx
);
$url = "https://sob.tagby.kr/api/saas/get_login_token.php";
 $ch = curl_init(); 
 curl_setopt($ch, CURLOPT_URL, $url); 

 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
 curl_setopt($ch, CURLOPT_SSLVERSION, 5);
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
$login_token = $data['login_token'];

/*
#로그인
(개발)http://sob.tagby.kr/api/saas/login.php
(운영) https://www.tagby.kr/api/saas/login.php
*/
$saas_id = "onlyone";
$account_idx = "1";
$account_email = "turbolight@daum.net";
$account_phone = "010-8888-9999";
$data = array(
    'saas_id' => $saas_id,
    'login_token' => $login_token
);
$url = "https://sob.tagby.kr/api/saas/login.php";
 $ch = curl_init(); 
 curl_setopt($ch, CURLOPT_URL, $url); 

 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
 curl_setopt($ch, CURLOPT_SSLVERSION, 5);
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
