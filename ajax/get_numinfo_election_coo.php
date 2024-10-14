<?
header("Content-Type: text/html; charset=UTF-8");
include_once "../lib/rlatjd_fun.php";

if($_SESSION['one_member_id']){

	$pno = substr(str_replace(array("-"," ",","),"",$_REQUEST["pno"]),0,11);

	if (strlen($pno) < 10){
		exit;
	}

	$sql_num="SELECT mem_phone, mem_type  FROM Gn_Member WHERE mem_id ='{$_SESSION['one_member_id']}'";

	$result_mem_phone=mysqli_query($self_con,$sql_num);

	$row_mem_phone = mysqli_fetch_row($result_mem_phone);
	$memberInfo = $row_mem_phone;

	mysqli_free_result($result_mem_phone);	

	$mem_phone = substr(str_replace(array("-"," ",","),"",$row_mem_phone[0]),0,11); //로그인한 가입자 폰 번호



	$sql_num="select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' and sendnum='$pno' ";
	

	$result=mysqli_query($self_con,$sql_num) or die(mysqli_error($self_con));

	$row = mysqli_fetch_array($result);

	mysqli_free_result($result);

	if($result){

		$date_today=date("Y-m-d");
		$date_month=date("Y-m");

		$detail_name = trim($row['memo']);
		$detail_company = trim($row['memo2']);
		$detail_device = trim($row['device']);
		$detail_reg_date = date("Y. m. d.",strtotime($row['reg_date']));
	    $total_cnt = $row['daily_limit_cnt']; //기본 일별 총발송 가능량
	    $donation_rate = $row['donation_rate']; 
	    $donation_cnt = ceil($total_cnt * $donation_rate / 100); //기부 받은 수
	    $person_cnt = $total_cnt - $donation_cnt; //개인 발송 문자 수
	    $send_donation_cnt = 0; //$row['gl_cnt'] //기부 받은 수 중 발송 수
	    $send_person_cnt = 0; //개인 할당 분 발송 수
	    $monthly_limit_ssh = $detail_company ? $agency_arr[$detail_company] : 800; //월별 수신처 제한 수 	
	    
    	//금일 발송 건수
		$sql_result2_g = "select SUM(recv_num_cnt) from Gn_MMS where reg_date like '$date_today%' and mem_id = '{$_SESSION['one_member_id']}' and send_num='$pno' ";
		$res_result2_g = mysqli_query($self_con,$sql_result2_g);			
		$row_result2_g = mysqli_fetch_array($res_result2_g);
		$send_donation_cnt+=$row_result2_g[0] * 1;
		mysqli_free_result($res_result2_g);	

		//이번달 총 발송 건 수
		$month_cnt_1=0;
		$sql_result_g = "select SUM(recv_num_cnt) from Gn_MMS where reg_date like '$date_month%' and mem_id = '{$_SESSION['one_member_id']}' and send_num='$pno' ";
		$res_result_g = mysqli_query($self_con,$sql_result_g);
		$row_result_g = mysqli_fetch_array($res_result_g);
		$month_cnt_1+= $row_result_g[0] * 1;
		mysqli_free_result($res_result_g);

		//이번 달 총 수신처 수
		$ssh_cnt=0;
		$ssh_numT =array();
		$sql_ssh="select recv_num from Gn_MMS where mem_id = '{$_SESSION['one_member_id']}' and send_num='$pno' and reg_date like '$date_month%' group by(recv_num)";
		$result_ssh=mysqli_query($self_con,$sql_ssh);
		while($row_ssh=mysqli_fetch_array($result_ssh))
		{
			$ssh_arr=explode(",",$row_ssh['recv_num']);
			$ssh_numT=array_merge($ssh_numT,(array)$ssh_arr);
			
		}
		$ssh_arr=array_unique($ssh_numT);
		$ssh_cnt=count($ssh_arr);	
		mysqli_free_result($result_ssh);  

		// 개인 주소록 DB동기화 수
		$sql_db="select count(*) from sm_data where dest='$pno' ";
		$result_phonebook=mysqli_query($self_con,$sql_db);
		$row_phonebook = mysqli_fetch_row($result_phonebook);

		mysqli_free_result($result_phonebook);
		
		// $sql_db="select count(*) from sm_data where dest='$pno'  ";
		// $result_phonebook=mysqli_query($self_con,$sql_db);
		// $row_phonebook = mysqli_fetch_row($result_phonebook);

		// mysqli_free_result($result_phonebook);		
		
		$sql_db="select count, idx  from Gn_MMS_Group where mem_id='{$_SESSION['one_member_id']}' and grp='아이엠' ";
		$result_phonebook=mysqli_query($self_con,$sql_db);
		$row_phonebook1 = mysqli_fetch_row($result_phonebook);

		mysqli_free_result($result_phonebook);				

		// 개인 주소록 최종 DB동기화 일		
		$sql_db="SELECT reservation_time FROM sm_data WHERE dest='$pno' ORDER BY reservation_time DESC LIMIT 1";
		$result_phonebook=mysqli_query($self_con,$sql_db);
		$row_syncdate = mysqli_fetch_row($result_phonebook);

		mysqli_free_result($result_phonebook);		

		// 마지막 사용일
		$sql_result4 = "select reg_date from Gn_MMS where mem_id = '{$_SESSION['one_member_id']}' and send_num='$pno' order by reg_date desc limit 1";
		$res_result4 = mysqli_query($self_con,$sql_result4);
		$row_result4 = mysqli_fetch_row($res_result4);

		mysqli_free_result($res_result4);

		$lastSendDate = date("Y. m. d.",strtotime($row_result4[0]));

		

		$phoneNumber = $pno;

		$installDate = $detail_reg_date;

		if ($mem_phone == $pno){ //가입자폰

			$excelDownload = $row_phonebook[0] ? number_format($row_phonebook[0])." 건 <a href=javascript:void(0); onClick = excel_down_p_group('$pno')><img src=/images/ico_xls.gif border=0 /></a>" : "-";
			$excelDownload2 = $row_phonebook1[0] ? number_format($row_phonebook1[0])." 건 <a href=javascript:void(0); onClick = excel_down_p_group('".$row_phonebook1[1]."')><img src=/images/ico_xls.gif border=0 /></a>" : "-";

		}else{ //기부폰

			$excelDownload = $row_phonebook[0] ? "완료" : "예정";
			$excelDownload2 = $row_phonebook1[0] ? "완료" : "예정";
		}
		
		//if($memberInfo[1]=="V" || $memberInfo[1]=="") {
    	//    if($mem_phone == $row['sendnum']) {
        //		if($detail_company == "LG") {
        //		    $donation_cnt = "무제한";
        //		    $person_cnt = "무제한";
        //		    $total_cnt = "무제한";
        //		    //$monthly_limit_ssh = "무제한";
        //		} else if($detail_company == "KT") {
        //		    $total_cnt = 2000;			    
        //		    $donation_rate = $row['donation_rate']; //기부 비율
        //		    $donation_cnt = ceil($total_cnt * $donation_rate / 100); //기부 받은 수
        //		    $person_cnt = $total_cnt - $donation_cnt; //개인 발송 문자 수
        //		    $monthly_limit_ssh = "무제한";
        //		} else if($detail_company == "SK") {
        //		    $total_cnt = 3000;
        //		    $donation_rate = $row['donation_rate']; //기부 비율
        //		    $donation_cnt = ceil($total_cnt * $donation_rate / 100); //기부 받은 수
        //		    $person_cnt = $total_cnt - $donation_cnt; //개인 발송 문자 수
        //		    $monthly_limit_ssh = "무제한";
        //		} else {
        //		    $total_cnt = $row['daily_limit_cnt']; //기본 일별 총발송 가능량
        //		    $donation_cnt = ceil($total_cnt * $donation_rate / 100); //기부 받은 수
        //		    $person_cnt = $total_cnt - $donation_cnt; //개인 발송 문자 수
        //		    $donation_rate = $row['donation_rate']; //기부 비율
        //		}		    		    	    
        //	}		
		//} else {
		    $total_cnt = $row['daily_limit_cnt']; //기본 일별 총발송 가능량
		    $donation_cnt = ceil($total_cnt * $donation_rate / 100); //기부 받은 수
		    $person_cnt = $total_cnt - $donation_cnt; //개인 발송 문자 수
		    $donation_rate = $row['donation_rate']; //기부 비율
		//}		    		    	    
		
		$sshLimit = $ssh_cnt . "/" .$monthly_limit_ssh;

		$personalCnt = $send_person_cnt ."/". $person_cnt;

		$cnt1State = $row['cnt1'];

		$cnt2State = $row['cnt2'];

		$diviceModel = $detail_device ? $detail_device : "-";

		$todaySendCnt = ($send_donation_cnt + $send_person_cnt). "/" .$total_cnt;

		$donationCnt = $send_donation_cnt. "/" .$donation_cnt;

		$thismonthCnt = $month_cnt_1;

		$syncDbdate = date("Y. m. d.",strtotime($row_syncdate[0]));
		$daily_limit_cnt = $row['daily_limit_cnt'];
		$daily_min_cnt = $row['daily_min_cnt'];
		$monthly_receive_cnt = $row['monthly_receive_cnt'];
		$daily_limit_cnt_user = $row['daily_limit_cnt_user'];
		$daily_min_cnt_user = $row['daily_min_cnt_user'];
		$monthly_receive_cnt_user = $row['monthly_receive_cnt_user'];

	}else{

		$detail_name = "";
		$detail_company = "";
		$detail_rate = "";
		$sshLimit = "";
		$phoneNumber = "";
		$installDate = "";
		$personalCnt = "";
		$cnt1State = "";
		$excelDownload = "";
		$cnt2State = "";
		$diviceModel = "";
		$todaySendCnt = "";
		$donationCnt = "";
		$thismonthCnt = "";
		$lastSendDate = "";
		$syncDbdate = "";
		
		$daily_limit_cnt = "";
		$daily_min_cnt = "";
		$monthly_receive_cnt = "";
		$daily_limit_cnt_user = "";
		$daily_min_cnt_user = "";
		$monthly_receive_cnt_user = "";		
		$excelDownload2 = "";

	}

}
mysqli_close($self_con);

 echo "{";
 echo "\"detail_name\" : \"$detail_name\"";
 echo ",\"phoneno\" : \"$pno\"";
 echo ",\"installDate\" : \"$installDate\"";
 echo ",\"donationCnt\" : \"$donationCnt\"";
 echo ",\"sshLimit\" : \"$sshLimit\"";
 echo ",\"phoneNumber\" : \"$pno\"";
 echo ",\"syncDb\" : \"$syncDb\"";
 echo ",\"personalCnt\" : \"$personalCnt\"";
 echo ",\"cnt1State\" : \"$cnt1State\"";
 echo ",\"detail_company\" : \"$detail_company\"";
 echo ",\"excelDownload\" : \"$excelDownload\"";
 echo ",\"excelDownload2\" : \"$excelDownload2\"";
 echo ",\"todaySendCnt\" : \"$todaySendCnt\"";
 echo ",\"cnt2State\" : \"$cnt2State\"";
 echo ",\"diviceModel\" : \"$diviceModel\"";
 echo ",\"detail_rate\" : \"$donation_rate\"";
 echo ",\"thismonthCnt\" : \"$thismonthCnt\"";
 echo ",\"lastSendDate\" : \"$lastSendDate\"";
 echo ",\"syncDbdate\" : \"$syncDbdate\"";
 
 echo ",\"daily_limit_cnt\" : \"$daily_limit_cnt\"";
 echo ",\"daily_min_cnt\" : \"$daily_min_cnt\"";
 echo ",\"monthly_receive_cnt\" : \"$monthly_receive_cnt\"";
 echo ",\"daily_limit_cnt_user\" : \"$daily_limit_cnt_user\"";
 echo ",\"daily_min_cnt_user\" : \"$daily_min_cnt_user\"";
 echo ",\"monthly_receive_cnt_user\" : \"$monthly_receive_cnt_user\"";
 echo "}";
?>