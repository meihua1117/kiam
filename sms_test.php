<?php
$rphone = '01023720920';
$rand_num = sprintf('%06d',rand(000000,999999));
$subject = "전화번호 인증". $rphone;
$msg = "[$rand_num] Hello world. ";
/******************** 인증정보 ********************/
//$sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
$sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
$sms['user_id'] = base64_encode("onlysms"); //SMS 아이디.
$sms['secure'] = base64_encode("5248a8661ef2a3b97a39a710a1bf5b44") ;//인증키
$sms['msg'] = base64_encode(stripslashes($msg));
// if( $_POST['smsType'] == "L"){
    $sms['subject'] =  base64_encode($subject);
// }
$sms['rphone'] = base64_encode($rphone);
$sms['sphone1'] = base64_encode("010");
$sms['sphone2'] = base64_encode("6733");
$sms['sphone3'] = base64_encode("1882");
$sms['rdate'] = base64_encode("");
$sms['rtime'] = base64_encode("");
$sms['mode'] = base64_encode("1"); // base64 사용시 반드시 모드값을 1로 주셔야 합니다.
$sms['returnurl'] = base64_encode("");
$sms['testflag'] = base64_encode("");
$sms['destination'] = "";
$returnurl = "";
$sms['repeatFlag'] = base64_encode("");
$sms['repeatNum'] = base64_encode("1");
$sms['repeatTime'] = base64_encode("15");
$sms['smsType'] = base64_encode("S"); // LMS일경우 L
//$nointeractive = $_POST['nointeractive']; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략
$nointeractive = ""; //사용할 경우 : 1, 성공시 대화상자(alert)를 생략
//print_r($sms);

$host_info = explode("/", $sms_url);
$host = $host_info[2];
$path = $host_info[3];

srand((double)microtime()*1000000);
$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
//print_r($sms);

// 헤더 생성
$header = "POST /".$path ." HTTP/1.0\r\n";
$header .= "Host: ".$host."\r\n";
$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

// 본문 생성
$data = "";
foreach($sms AS $index => $value){
    $data .="--$boundary\r\n";
    $data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
    $data .= "\r\n".$value."\r\n";
    $data .="--$boundary\r\n";
}
$header .= "Content-length: " . strlen($data) . "\r\n\r\n";

$fp = fsockopen($host, 80);

if ($fp) {
    fputs($fp, $header.$data);
    $rsp = '';
    while(!feof($fp)) {
        $rsp .= fgets($fp,8192);
    }
    fclose($fp);
    $msg = explode("\r\n\r\n",trim($rsp));
    $rMsg = explode(",", $msg[1]);
    $Result= $rMsg[0]; //발송결과
    $Count= $rMsg[1]; //잔여건수

    //발송결과 알림
    if($Result=="success") {
        $alert = "성공";
        $alert .= " 잔여건수는 ".$Count."건 입니다.";
    }
    else if($Result=="reserved") {
        $alert = "성공적으로 예약되었습니다.";
        $alert .= " 잔여건수는 ".$Count."건 입니다.";
    }
    else if($Result=="3205") {
        $alert = "잘못된 번호형식입니다.";
    }

    else if($Result=="0044") {
        $alert = "스팸문자는발송되지 않습니다.";
    }

    else {
        $alert = "[Error]".$Result;
    }
}
else {
    $alert = "Connection Failed";
}
//echo $_POST['msg'];
//print_r($alert);

if($nointeractive=="1" && ($Result!="success" && $Result!="Test Success!" && $Result!="reserved") ) {
    echo "<script>alert('".$alert ."')</script>";
}
else if($nointeractive!="1") {
    echo "<script>alert('".$alert ."')</script>";
}
