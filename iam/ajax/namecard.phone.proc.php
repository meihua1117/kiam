<?php
ob_start();
header('Content-Type: text/html; charset=utf-8');
$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: 0'); // rfc2616 - Section 14.21
header('Last-Modified: ' . $gmnow);
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0
include_once "../../lib/rlatjd_fun.php";
extract($_REQUEST);
if($mode == "send_sms") {
        $rphone = $_POST['rphone'];
        // = "010-8825-4394";
        $sql="select * from Gn_Member where mem_phone='$rphone'";
        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        $data = $row=mysqli_fetch_array($result);
        // if($data['mem_phone'] != "") {
        //     echo '{"result":"fail","msg":"이미 가입된 핸드폰이 있습니다. 관리자에 문의하세요."}';
        //     exit;
        // }
        $rand_num = sprintf('%06d',rand(000000,999999));

        $sql="select count(*) cnt from Gn_Member_Check_Sms where mem_phone='$rphone' and date_format(regdate, '%Y-%m-%d' )=curdate() ";
        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        $data = $row=mysqli_fetch_array($result);
        if($data['cnt'] >=5) {
            echo '{"result":"fail","msg":"1일 5번까지 인증이 가능합니다.."}';
            exit;
        }

        $sql="select * from Gn_Member_Check_Sms where mem_phone='$rphone' order by idx desc";
        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        $data = $row=mysqli_fetch_array($result);

        $sql="update Gn_Member_Check_Sms set status='N' where mem_phone='$rphone'";
        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

        $sql="insert into Gn_Member_Check_Sms set mem_phone='$rphone', secret_key='$rand_num', regdate= NOW()";
        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

        $data = "";

        $conf_sql = "select web_phone from gn_conf";
        $conf_result = mysqli_query($self_con,$conf_sql);
        $conf_row = mysqli_fetch_array($conf_result);
        $sphone1 = substr($conf_row[0], 0, 3);
        $sphone2 = substr($conf_row[0], 3, 4);
        $sphone3 = substr($conf_row[0], 7, 4);

        $subject = "전화번호 인증".$rphone;
        $msg = "[$rand_num] 온라인 문자 인증정보를 입력해주세요. ";
       /******************** 인증정보 ********************/
        //$sms_url = "https://sslsms.cafe24.com/sms_sender.php"; // HTTPS 전송요청 URL
        $sms_url = "http://sslsms.cafe24.com/sms_sender.php"; // 전송요청 URL
        $sms['user_id'] = base64_encode("onlysms"); //SMS 아이디.
        $sms['secure'] = base64_encode("5248a8661ef2a3b97a39a710a1bf5b44") ;//인증키
        $sms['msg'] = base64_encode(stripslashes($msg));
        if( $_POST['smsType'] == "L"){
              $sms['subject'] =  base64_encode($subject);
        }
        $sms['rphone'] = base64_encode($rphone);
        $sms['sphone1'] = base64_encode($sphone1);
        $sms['sphone2'] = base64_encode($sphone2);
        $sms['sphone3'] = base64_encode($sphone3);
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
        $path = $host_info[3]."/".$host_info[4];

        srand((double)microtime()*1000000);
        $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
        //print_r($sms);

        // 헤더 생성
        $header = "POST /".$path ." HTTP/1.0\r\n";
        $header .= "Host: ".$host."\r\n";
        $header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

        // 본문 생성
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
            //echo "<script>alert('".$alert ."')</script>";
        }
        else if($nointeractive!="1") {
            //echo "<script>alert('".$alert ."')</script>";
        }
        //echo "<script>location.href='".$returnurl."';</script>";
        if($Result == -101)
            echo '{"result":"fail","msg":"발송정보가 있어 발송이 지연되고 있습니다.'.$Result.'"}';
        else
            echo '{"result":"success","msg":"'.$rphone.'에 SMS가 발송되었습니다.'.$Result.'"}';
        exit;
} else if($mode == "check_sms") {
        $sql="select * from Gn_Member_Check_Sms where mem_phone='$rphone' order by idx desc";
        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        $data = $row=mysqli_fetch_array($result);

        if($data['secret_key'] == $rnum) {
            echo '{"result":"success","msg":"인증되었습니다."}';
            exit;
        } else {
            echo '{"result":"fail","msg":"정보를 확인해주세요."}';
            exit;
        }

}
?>
