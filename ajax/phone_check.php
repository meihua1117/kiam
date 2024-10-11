<?
@header("Content-type: text/html; charset=utf-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 86400');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization,x-requested-with');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
if($_POST['mode'] == "munja_send"){
    $api_key = $_POST['api_key'];
    $recv_phone = str_replace("-", "", $_POST['phone_number']);
    $query = "select mem_id,check_type from gn_check_phone where api_key = '$api_key' and status='Y'";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    if($row['mem_id'] == ""){
        echo json_encode(array("result"=>"등록되지 않은 api입니다."));
    }else{
        $mem_id = $row['mem_id'];
        $date_today = date("Y-m-d");
        $rand_num = sprintf('%06d',rand(000000,999999));
        if($row['check_type'] == 'phone'){
            $send_num = "";
            $pkey = "";
            $sql="select * from Gn_MMS_Number where (not(cnt1 = 10 and cnt2 = 20)) and mem_id='$mem_id' order by sort_no asc, user_cnt desc , idx desc";
            $res=mysqli_query($self_con,$sql);
            while($row = mysqli_fetch_array($res)){
                $today_send_sql = "select SUM(recv_num_cnt) from Gn_MMS where send_num='$row[sendnum]' and ((reg_date like '$date_today%' and reservation is null) or up_date like '$date_today%')";
                $today_send_res = mysqli_query($self_con,$today_send_sql);			
                $today_send_row = mysqli_fetch_array($today_send_res);
                $today_send_count = 0;
                $today_send_count += $today_send_row[0] * 1;
                
                if($row[daily_limit_cnt_user] > $today_send_count){
                    $send_num = $row['sendnum'];
                    $pkey = $row['pkey'];
                    break;
                }
            }
            if($send_num == "" || $pkey == ""){
                echo json_encode(array("result"=>"전송할 폰이 없습니다."));
            }else{
                $query = "update Gn_MMS set content = '폰문자인증 완료' where mem_id='$mem_id' and type='10' and recv_num = '$recv_phone'";
                $res = mysqli_query($self_con,$query);
                $content = "IAM플랫폼에서 발송한 인증문자입니다.인증번호 ".$rand_num."를 입력하세요.";
		$send_num = '01067226400';
                $result = sendmms(10,$mem_id,$send_num,$recv_phone,"","폰문자인증",$content,"","","","Y");
                if($result == "fail")
                    echo json_encode(array("result"=>"인증문자가 발송실패되었습니다."));
                else
                    echo json_encode(array("result"=>"인증문자가 발송되었습니다.","code"=>1));
            }
        }else{
            $sql="select count(*) cnt from Gn_Member_Check_Sms where mem_phone='$recv_phone' and date_format(regdate, '%Y-%m-%d' )=curdate() ";
            $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $data = $row=mysqli_fetch_array($result);
            if($data['cnt'] >=5) {
                echo json_encode(array("result"=>"1일 5번까지 인증이 가능합니다.","code"=>0));
                exit;
            }

            $sql="select * from Gn_Member_Check_Sms where REPLACE(mem_phone,'-','')=REPLACE('$recv_phone','-','') order by idx desc";
            $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $data = $row=mysqli_fetch_array($result);

            $sql="update Gn_Member_Check_Sms set status='N' where mem_phone='$recv_phone'";
            $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

            $sql="insert into Gn_Member_Check_Sms set mem_phone='$recv_phone', secret_key='$rand_num', regdate= NOW()";
            $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

            $data = "";

            $conf_sql = "select web_phone from gn_conf";
            $conf_result = mysqli_query($self_con,$conf_sql);
            $conf_row = mysqli_fetch_array($conf_result);
            $sphone1 = substr($conf_row[0], 0, 3);
            $sphone2 = substr($conf_row[0], 3, 4);
            $sphone3 = substr($conf_row[0], 7, 4);

            $subject = "전화번호 인증".$recv_phone;
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
            $sms['rphone'] = base64_encode($recv_phone);
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

            $host_info = explode("/", $sms_url);
            $host = $host_info[2];
            $path = $host_info[3]."/".$host_info[4];

            srand((double)microtime()*1000000);
            $boundary = "---------------------".substr(md5(rand(0,32000)),0,10);
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
                }else if($Result=="reserved") {
                    $alert = "성공적으로 예약되었습니다.";
                    $alert .= " 잔여건수는 ".$Count."건 입니다.";
                }else if($Result=="3205") {
                    $alert = "잘못된 번호형식입니다.";
                }else if($Result=="0044") {
                    $alert = "스팸문자는 발송되지 않습니다.";
                }else if($Result=="-101") {
                    $alert = "발송정보가 있어 발송이 지연되고 있습니다.";
                }else {
                    $alert = "[Error]".$Result;
                }
            }
            else {
                $alert = "Connection Failed";
            }
            if($nointeractive=="1" && ($Result!="success" && $Result!="Test Success!" && $Result!="reserved") ) {
                //echo "<script>alert('".$alert ."')</script>";
            }else if($nointeractive!="1") {
                //echo "<script>alert('".$alert ."')</script>";
            }
            //echo "<script>location.href='".$returnurl."';</script>";
            if($Result != "success")
                echo json_encode(array("result"=>$alert,"code"=>$Result));
            else
                echo json_encode(array("result"=>$recv_phone.'에 SMS가 발송되었습니다.',"code"=>1));
            exit;
        }
    }
    exit;
} else if($_POST['mode'] == "munja_check"){
    $check_str = $_POST['check'];
    $api_key = $_POST['api_key'];
    $recv_phone = str_replace("-", "", $_POST['phone_number']);
    $query = "select mem_id,check_type from gn_check_phone where api_key = '$api_key' and status='Y'";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    if($row['mem_id'] == ""){
        echo json_encode(array("result"=>"등록되지 않은 api입니다."));
    }else{
        if($row['check_type'] == "phone"){
            $query = "select count(idx) from Gn_MMS where mem_id='{$row['mem_id']}' and content like '%$check_str%' and type='10' and recv_num = '$recv_phone' order by idx desc";
            $res = mysqli_query($self_con,$query);
            $row = mysqli_fetch_array($res);
            if($row[0] > 0){
                echo json_encode(array("result"=>"정확히 인증되었습니다.","code"=>1));
            }else{
                echo json_encode(array("result"=>"인증문자가 정확하지 않습니다.정확하게 입력해주세요.","code"=>0));
            }
        }else{
            $sql="select * from Gn_Member_Check_Sms where mem_phone='$recv_phone' order by idx desc";
            $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $data = $row=mysqli_fetch_array($result);

            if($data['secret_key'] == $check_str) {
                echo json_encode(array("code"=>1,"result"=>"인증되었습니다."));
                exit;
            } else {
                echo json_encode(array("code"=>0,"result"=>"인증문자가 정확하지 않습니다.정확하게 입력해주세요."));
                exit;
            }
        }
    }
    exit;
}
?>