<?
ini_set("display_errors", 0);
include_once "../lib/rlatjd_fun.php";
//로그아웃
if($_POST[logout_go])
  {
	if($_SESSION[one_member_id])
	  {
        $_SESSION['one_member_id'] = "";
	    $_SESSION['one_member_admin_id'] = "";		
	    $_SESSION[one_member_subadmin_domain] = "";
	    $_SESSION[one_member_subadmin_id] = "";
	    $_SESSION[one_mem_lev] = "";
		?>
		<script language="javascript">
		//alert('로그아웃되었습니다.')
		location.replace('<?=$_SERVER['HTTP_REFERER']?>')
		</script>
        <?
        
		exit;
	  }	  
  }
if(!$_SESSION[one_member_id])
{
    
		?>
		<script language="javascript">
		//alert('로그아웃되었습니다.')
		location.replace("<?=$_SERVER['HTTP_REFERER']?>")
		</script>
        <?
        
		exit;
}
//번호체크삭제
if($_POST[num_del_num_a])
{
	$num_a=$_POST[num_del_num_a];
	$no_num=array();
	foreach($num_a as $key=>$v)
	{
		$sql="delete from Gn_MMS_Number where mem_id = '$_SESSION[one_member_id]' and sendnum='$v' and end_status='N' ";
		//mysqli_query($self_con,$sql);
		$sql_d="delete from Gn_MMS where sendnum='$v' ";
		//mysqli_query($self_con,$sql_d);
		$sql_da="delete from sm_data where dest='$v' ";
		//mysqli_query($self_con,$sql_da);
	}
	?>
	<script language="javascript">
	alert('47 임시비활성: 처리되었습니다.');
	location.reload();
	</script>
	<?	
}
//전송안된문자 삭제 //2016-03-08 삭제시 발송제한 카운트 복구 추가
if($_POST[no_msg_del_ok])
{
	$sql0 = "select idx from Gn_MMS where result='1' and mem_id='$_SESSION[one_member_id]' order by idx desc";
	$result0=mysqli_query($self_con,$sql0);
	while($row0=mysqli_fetch_array($result0)){
		
		$num = $row0[idx];

		//발송완료여부 확인(up_date)
		$sql="select uni_id,mem_id,send_num,up_date,reg_date from Gn_MMS where idx = $num;";
		$result=mysqli_query($self_con,$sql);
		$row1=mysqli_fetch_array($result);

		$uni_id = $row1[uni_id];
		$mem_id = $row1[mem_id];
		$sendnum = $row1[send_num];
		$up_date = $row1[up_date];
		$reg_date = $row1[reg_date];		

		//이번 달 것인지 확인
		if (substr($reg_date,0,7) == date("Y-m") && !$up_date){ //이번 달

			$sqlRinfo="select cnt1,cnt2,cntYN,cntAdj,reg_date from Gn_MMS_Send_Cnt_Log where mem_id='$_SESSION[one_member_id]' and uni_id='$uni_id';";
			$resultRinfo=mysqli_query($self_con,$sqlRinfo);
			$rowRinfo=mysqli_fetch_array($resultRinfo);

			$Rinfo_cnt1 = $rowRinfo[cnt1]*-1;
			$Rinfo_cnt2 = $rowRinfo[cnt2]*-1;
			$Rinfo_cntYN = $rowRinfo[cntYN];
			$Rinfo_cntAdj = $rowRinfo[cntAdj];

			//최후의 것인지 확인
			//$sql="select uni_id from Gn_MMS where mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' and idx > $num limit 1;";
			$sql="select uni_id,mem_id from Gn_MMS where mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE() limit 1;";
			$result1=mysqli_query($self_con,$sql);
			$row1=mysqli_fetch_array($result1);
			
			if($row1[uni_id]){ //최후 건 아님: cnt1,cnt2 복구

				$sql_num="update Gn_MMS_Number set cnt1=cnt1+($Rinfo_cnt1), cnt2=cnt2+($Rinfo_cnt2) where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum' ";
				mysqli_query($self_con,$sql_num);

				//이후 오늘 발송 건 유무 확인
				//$sql="SELECT uni_id FROM Gn_MMS WHERE mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE( ) and idx > $num limit 1;"; 
				//$sql="SELECT uni_id FROM Gn_MMS WHERE mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE( )  limit 1;"; 
				$sql="SELECT uni_id FROM Gn_MMS WHERE mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE( ) and idx != '$num' limit 1;"; 
				$result=mysqli_query($self_con,$sql);
				$row=mysqli_fetch_array($result);
				if($row[uni_id]){//이후 오늘 발송 건 존재

					//$sql="select SUM(recv_num REGEXP '[,]') AS matches from Gn_MMS where mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE() and idx > $num;";
					$sql="select recv_num from Gn_MMS where mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE() and idx != '$num' ;";
					$result2=mysqli_query($self_con,$sql);

					$check_flag = 0;
					
					while($row2=mysqli_fetch_array($result2))
					{
					    $matches = count(explode(",", $row2['recv_num']));
						if($matches >= 198){
							$check_flag += 1;
						}
					}

					if ($check_flag == 0){
						$sql_num="update tjd_mms_cnt_check set status='N' where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum' and date=curdate(); ";
						mysqli_query($self_con,$sql_num);					
					}

				}else{//이후 오늘 발송 건 없음
					$sql_num="delete from tjd_mms_cnt_check where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum' and date=curdate();"; 
					mysqli_query($self_con,$sql_num);
				}
				
        		$sql="delete from Gn_MMS where idx = $num;";
        		$result=mysqli_query($self_con,$sql);				

			}else{//최후 건 : cnt1,cnt2,cntYN,cntAdj 모두 복구
                if($row1['mem_id'] == "") {
    				$sql_num="update Gn_MMS_Number set cnt1=cnt1+($Rinfo_cnt1), cnt2=cnt2+($Rinfo_cnt2) where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum' ";
    				mysqli_query($self_con,$sql_num);
    
    				$sql_num="delete from tjd_mms_cnt_check where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum' and date=curdate();";
    				mysqli_query($self_con,$sql_num);
    
    				$sql="select cnt1, cnt2 from Gn_MMS_Number where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum';";
    				$result=mysqli_query($self_con,$sql);
    				$row=mysqli_fetch_array($result);
    				$chk_cnt1 = $row[cnt1];
    
    				if ($chk_cnt1 < 10){//발송 가능 수 복구
    					$sql_num="update Gn_MMS_Number set daily_limit_cnt=500, max_cnt = ceil(500 * 100 / donation_rate),gl_cnt = 500 - ceil(500 * 100 / donation_rate)  where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum' ";
    					mysqli_query($self_con,$sql_num);			
    				}
    			}
				
			}
            
		}

	}
	
	$sql = "delete from Gn_MMS where  mem_id='$_SESSION[one_member_id]' and result='1' and reservation is null";
	$result = mysqli_query($self_con,$sql);
	if($result)
	{
			?>
			<script language="javascript">
			alert('삭제되었습니다.');
			location.reload();
			</script>
			<?		
	}
}
//그룹생성 고
if($_POST[group_create_go])
{
	$in_sendnum=str_replace("\'","'",$_POST[group_create_nums]);
	$sql= "select msg_text, msg_url from sm_data where dest in ($in_sendnum) ";
	$resul=mysqli_query($self_con,$sql);
	if(mysqli_num_rows($resul))
	{
		?>
			<script language="javascript">
			open_div(open_group_create,200,1);
		  if($("#open_group_create").css("display")!="none")
		  {
		  $("#sendnum_span").html("<?=$in_sendnum?>");
		  $("#sendnum_hid").val("<?=$in_sendnum?>");
		  }
			</script>
        <?
	}
	else
	{
	?>
    	<script language="javascript">
		alert('그룹생성 가능번호 없습니다.')
		</script>
    <?
		exit;
	}
}
//그룹생성 원모어
if($_POST[group_create_ok_])
{
	$in_nums=str_replace("\'","'",$_POST[group_create_ok_nums]);
	$group_name=htmlspecialchars($_POST[group_create_ok_name]);
	$sql_s="select idx from Gn_MMS_Group where grp='$group_name' and mem_id = '$_SESSION[one_member_id]' ";
	$resul_s=mysqli_query($self_con,$sql_s);
	$row_s=mysqli_fetch_array($resul_s);
	if($row_s[idx] && substr($group_name,-4) != date("md"))
	{
		?>
    	<script language="javascript">
		alert('해당 그룹명은 이미 존재합니다.\n\n다른 그룹명으로 사용해주세요.')
		</script>
    	<?
		exit;		
	}elseif($row_s[idx] && substr($group_name,-4) == date("md")){

		$sql = "delete from Gn_MMS_Group where grp='$group_name' and mem_id = '$_SESSION[one_member_id]'";
		mysqli_query($self_con,$sql);

		$sql = "delete from Gn_MMS_Receive where grp='$group_name' and mem_id = '$_SESSION[one_member_id]'";
		mysqli_query($self_con,$sql);
	}
	$sql = "insert Gn_MMS_Group set mem_id = '$_SESSION[one_member_id]', grp = '$group_name' , reg_date = now()";
	mysqli_query($self_con,$sql);
	
	$sql_s="select idx from Gn_MMS_Group where grp='$group_name' and mem_id = '$_SESSION[one_member_id]'";
	$resul_s=mysqli_query($self_con,$sql_s);
	$row_s=mysqli_fetch_array($resul_s);
	
	$sql = "delete from Gn_MMS_Receive where mem_id='$_SESSION[one_member_id]' and grp_id='$row_s[idx]'";
	mysqli_query($self_con,$sql);	
	
	$sql_d = "select msg_text,msg_url,grp from sm_data where dest in ($in_nums) ";
	$resul_d = mysqli_query($self_con,$sql_d) or die(mysqli_error($self_con));
	$i=0;
	while($row_d=mysqli_fetch_array($resul_d))
	{
		$recv_num=str_replace(array("-"," ",","),"",$row_d[msg_url]);
		$is_zero=substr($recv_num,0,1);
		$recv_num=$is_zero?"0".$recv_num:$recv_num;
		$recv_num = preg_replace("/[^0-9]/i", "", $recv_num); 

		if(!check_cellno($recv_num))
		continue;
		
		$sql_c="select idx from Gn_MMS_Receive where mem_id='$_SESSION[one_member_id]' and grp_id='$row_s[idx]' and recv_num='$recv_num' ";
		$resul_c=mysqli_query($self_con,$sql_c);
		$row_c=mysqli_fetch_array($resul_c);
		if($row_c[idx])
		continue;
						
		$sql_i = "insert into Gn_MMS_Receive set grp_id='$row_s[idx]', mem_id = '$_SESSION[one_member_id]', grp = '$group_name',grp_2='$row_d[grp]', recv_num = '$recv_num', name = '$row_d[msg_text]',reg_date=now() ";
		mysqli_query($self_con,$sql_i)or die(mysqli_error($self_con));
		$i++;
	}
	
	$sql_u="update Gn_MMS_Group set count='$i' where idx='$row_s[idx]' ";
	mysqli_query($self_con,$sql_u);

	
	?>
    	<script language="javascript">
		alert('그룹 생성완료되었습니다.')
		location.reload();
		</script>
    <?	
	
}

//그룹생성 오케이
if($_POST[group_create_ok])
{
	$in_nums=str_replace("\'","'",$_POST[group_create_ok_nums]);
	$group_name=htmlspecialchars($_POST[group_create_ok_name]);
	$sql_s="select idx from Gn_MMS_Group where grp='$group_name' and mem_id = '$_SESSION[one_member_id]' ";
	$resul_s=mysqli_query($self_con,$sql_s);
	$row_s=mysqli_fetch_array($resul_s);
	if($row_s[idx] && substr($group_name,-4) != date("md"))
	{
		?>
    	<script language="javascript">
		alert('해당 그룹명은 이미 존재합니다.\n\n다른 그룹명으로 사용해주세요.')
		</script>
    	<?
		exit;		
	}elseif($row_s[idx] && substr($group_name,-4) == date("md")){

		$sql = "delete from Gn_MMS_Group where grp='$group_name' and mem_id = '$_SESSION[one_member_id]'";
		mysqli_query($self_con,$sql);

		$sql = "delete from Gn_MMS_Receive where grp='$group_name' and mem_id = '$_SESSION[one_member_id]'";
		mysqli_query($self_con,$sql);
	}
	$sql = "insert Gn_MMS_Group set mem_id = '$_SESSION[one_member_id]', grp = '$group_name' , reg_date = now()";
	mysqli_query($self_con,$sql);
	
	
	$sql_s="select idx from Gn_MMS_Group where grp='$group_name' and mem_id = '$_SESSION[one_member_id]'";
	$resul_s=mysqli_query($self_con,$sql_s);
	$row_s=mysqli_fetch_array($resul_s);
	
	$sql = "delete from Gn_MMS_Receive where mem_id='$_SESSION[one_member_id]' and grp_id='$row_s[idx]'";
	mysqli_query($self_con,$sql);
	
	$sql_d = "select msg_text,msg_url,grp from sm_data where dest in ($in_nums) ";
	$resul_d = mysqli_query($self_con,$sql_d) or die(mysqli_error($self_con));
	$i=0;
	while($row_d=mysqli_fetch_array($resul_d))
	{
		$recv_num=str_replace(array("-"," ",","),"",$row_d[msg_url]);
		$is_zero=substr($recv_num,0,1);
		$recv_num=$is_zero?"0".$recv_num:$recv_num;
		$recv_num = preg_replace("/[^0-9]/i", "", $recv_num); 

		if(!check_cellno($recv_num))
		continue;
		
		$sql_c="select idx from Gn_MMS_Receive where mem_id='$_SESSION[one_member_id]' and grp_id='$row_s[idx]' and recv_num='$recv_num' ";
		$resul_c=mysqli_query($self_con,$sql_c);
		$row_c=mysqli_fetch_array($resul_c);
		if($row_c[idx])
		continue;
						
		$sql_i = "insert into Gn_MMS_Receive set grp_id='$row_s[idx]', mem_id = '$_SESSION[one_member_id]', grp = '$group_name',grp_2='$row_d[grp]', recv_num = '$recv_num', name = '$row_d[msg_text]',reg_date=now() ";
		mysqli_query($self_con,$sql_i)or die(mysqli_error($self_con));
		$i++;
	}
	
	
	$sql_u="update Gn_MMS_Group set count='$i' where idx='$row_s[idx]' ";
	mysqli_query($self_con,$sql_u);

	if ($group_name == substr($in_nums,-8) ."_". date("md")) { //번호 DB동기화 출력

		echo '{"idx":'.$row_s[idx].'}'; 

	}else{
	?>
    	<script language="javascript">
		alert('그룹 생성완료되었습니다.')
		location.reload();
		</script>
    <?	
	}
}
//그룹명수정
if($_POST[group_modify_title] && $_POST[group_modify_idx])
{
	$group_name=htmlspecialchars($_POST[group_modify_title]);
	$sql_s="select idx from Gn_MMS_Group where grp='$group_name' ";
	$resul_s=mysqli_query($self_con,$sql_s);
	$row_s=mysqli_fetch_array($resul_s);
	if($row_s[idx])
	{
		?>
    	<script language="javascript">
		alert('해당 그룹명은 이미 존재합니다.\n\n다른 그룹명으로 사용해주세요.')
		</script>
    	<?
		exit;		
	}
	$sql="update Gn_MMS_Group set grp='$group_name' where idx='$_POST[group_modify_idx]' ";
	if(mysqli_query($self_con,$sql))
	{
		$sql_u1="update Gn_MMS_Receive set grp='$group_name' where grp_id='$_POST[group_modify_idx]' ";
		mysqli_query($self_con,$sql_u1) or die(mysqli_error($self_con));
		$sql_s1=" select recv_num,grp from Gn_MMS_Receive where grp_id='$_POST[group_modify_idx]' ";
		$resul_s1=mysqli_query($self_con,$sql_s1)or die(mysqli_error($self_con));
		while($row_s1=mysqli_fetch_array($resul_s1))
		{
			$sql_u2="update sm_log set grp_name='$row_s1[grp]' where ori_num='$row_s1[recv_num]' ";
			mysqli_query($self_con,$sql_u2)or die(mysqli_error($self_con));
		}
		?>
    	<script language="javascript">
		alert('처리되었습니다.');
		location.reload();
		</script>
    	<?
	}
}

//번호체크
if($_POST[num_check_go])
{
	$check_status=$_POST[num_check_status];
	$num_arr=array();
	$num_arr2=array();	
	$check_cnt0=array();
	$check_cnt1=array();
	$check_cnt2=array();
	$check_cnt3=array();
	$check_cnt4=array();
	$check_cnt5=array();	
	if($_POST[num_check_num2])
	$num_arr=array_merge($num_arr,explode(",",$_POST[num_check_num2]));
	
	if($_POST[num_check_grp_id])
	{
		$group_idx_arr=explode(",",$_POST[num_check_grp_id]);	
		foreach($group_idx_arr as $key=>$v)
		{
			$sql_rece="select recv_num from Gn_MMS_Receive where grp_id='$v'  order by idx asc";
			$resul_rece=mysqli_query($self_con,$sql_rece);
			while($row_rece=mysqli_fetch_array($resul_rece))
			array_push($num_arr,$row_rece[recv_num]);
		}
	}	
	$num_arr2=array_unique($num_arr);
	//echo count($num_arr)."\n";
	//echo count($num_arr2)."\n";
	$total_send_num = count($num_arr);
	//echo count($num_arr2);
	
	$check_cnt5=array_diff_assoc($num_arr,$num_arr2);//중복번호
	$check_cnt5=array_unique($check_cnt5);
	$ssh_total_num=array();
    if($_POST[send_rday]) //예약발송 확인
        $reservation = $_POST[send_rday]." ".$_POST[send_htime].":".$_POST[send_mtime].":00";	

    	
	if($_POST[num_check_send_num])
	{
	    
		$no_num=array(); //없는 번호 // === 2016-05-11 추가 ===
		$start_num=array();  // === 2016-05-11 추가 ===
		$deny_num=array(); //수신거부 번호 // === 2016-05-11 추가 ===
		$etc_arr=array(); //기타번호 // === 2016-05-11 추가 ===
		$wrong_arr=array(); // === 2016-05-11 추가 ===
		$lose_arr=array(); // === 2016-05-11 추가 ===
		$cnt1_log_arr=array(); //cnt1_변동 저장 // 2016-03-07 추가 // === 2016-05-11 추가 ===
		$cnt2_log_arr=array(); //cnt2_변동 저장 // === 2016-05-11 추가 ===
		$cntYN_log_arr=array(); //200이상으로 횟수 조절 저장 // === 2016-05-11 추가 ===
		$cntAdj_log_arr=array(); //발송 가능 수 조절 저장 // === 2016-05-11 추가 ===
		$date_today=date("Y-m-d");// === 2016-05-11 추가 ===
		$date_month=date("Y-m");// === 2016-05-11 추가 ===
		if($reservation) {
			$date_today=substr($reservation,0,10);
			$date_month=substr($reservation,0,7);				    
		}		
	    
        //로그인한 가입자 폰 번호 Cooper Add 2016-04-19
        $sql_num="SELECT mem_phone  FROM Gn_Member WHERE mem_id ='$_SESSION[one_member_id]'"; 
        $result_mem_phone=mysqli_query($self_con,$sql_num);
        $row_mem_phone = mysqli_fetch_row($result_mem_phone);
        mysqli_free_result($result_mem_phone);	
        $mem_phone = substr(str_replace(array("-"," ",","),"",$row_mem_phone[0]),0,11);

		$num_check_send_num_s=str_replace("`","'",$_POST[num_check_send_num]);
		
		$sendnum_seperate=str_replace("`","",$_POST[num_check_send_num]);   // === 2016-05-11 추가 ===
		$sendnum = explode("," ,$sendnum_seperate); // === 2016-05-11 추가 ===
		
    	$recv_over = ""; // cooper 2016-04-19 수신처 오버 번호 체크
		
		
		//$sql_ssh="select send_num, idx, recv_num from Gn_MMS where mem_id = '$_SESSION[one_member_id]' and send_num in({$num_check_send_num_s}) and result = '0' and reg_date like '$date_month%'";
		$sql_ssh="select send_num, idx, recv_num from Gn_MMS where mem_id = '$_SESSION[one_member_id]' and send_num in({$num_check_send_num_s})  and result = '0' and reg_date like '$date_month%'";
		//echo $sql_ssh;
		$resul_ssh=mysqli_query($self_con,$sql_ssh);
		if(mysqli_num_rows($resul_ssh))
		{
		    while($row_ssh=mysqli_fetch_array($resul_ssh)) {
		        
				$ssh_arr=array();
				$ssh_num=array();		
				
				$row_ssh[recv_num] = str_replace(",$row_ssh[send_num]", "", $row_ssh[recv_num]);   // 자기자신값 삭제 2016-04-28
				$row_ssh[recv_num] = str_replace("$row_ssh[send_num]", "", $row_ssh[recv_num]);   // 자기자신값 삭제 2016-04-28
				
				$ssh_arr=explode(",",$row_ssh[recv_num]);
				$ssh_arr=array_unique($ssh_arr);
				$ssh_num=array_merge($ssh_num,(array)$ssh_arr);		
				$ssh_total_num=array_merge($ssh_total_num,$ssh_num); //총 수신처 누적						
						        
		        if($send_iphone[$row_ssh[send_num]])
		            $send_iphone[$row_ssh[send_num]] = $send_iphone[$row_ssh[send_num]].",".$row_ssh[recv_num];
		        else 
		            $send_iphone[$row_ssh[send_num]] = $row_ssh[recv_num];
		            
		        
    			// Cooper Add 수신처 갯수 확인 2016-04-19
                  $query = "select * from Gn_MMS_Number where mem_id='$_SESSION[one_member_id]' and sendnum='".$row_ssh['send_num']."'";
                  $result = mysqli_query($self_con,$query);
                  $info = mysqli_fetch_array($result);
                  if($info[memo2] != "") {
                      $memo2 = $telecom = $info['memo2'];
                  }
                  
                  $monthly_limit_ssh = $memo2 ? $agency_arr[$memo2] : 800; //월별 수신처 제한 수 
                  //if($info['cnt1'] >= 10) $monthly_limit_ssh = 199;
                  
                /*      
                  if($member_info['mem_type'] == "V" && $mem_phone == $row_ssh['send_num']) {
                      
                  } else {
                      echo "<!--$monthly_limit_ssh === ".count($ssh_arr)."-->\n";
                      if($monthly_limit_ssh <= count($ssh_arr)) {
                          if($recv_over) 
                              $recv_over .= ",".$row_ssh['send_num'];
                          else
                              $recv_over = $row_ssh['send_num'];
                      }
                  }				            
		        */
		        
		        //echo "<BR>".count(array_unique(explode(",", $send_iphone[$row_ssh[send_num]])));
		        if($member_info['mem_type'] == "V" && $mem_phone == $row_ssh['send_num']) {
		        } else {
		          //echo $row_ssh['send_num']."===".$monthly_limit_ssh." <= ".count($ssh_arr)."\n";
		            
                  if($monthly_limit_ssh <= count($ssh_arr)) {
                      if($recv_over) 
                          $recv_over .= ",".$row_ssh['send_num'];
                      else
                          $recv_over = $row_ssh['send_num'];
                  } else if($monthly_limit_ssh <= count(array_unique(explode(",", $send_iphone[$row_ssh[send_num]])))) {
                      if($recv_over) 
                          $recv_over .= ",".$row_ssh['send_num'];
                      else
                          $recv_over = $row_ssh['send_num'];                    
                  }
                }
		    }
		}
    }
    
	sort($num_arr2);
	//$num_arr = $num_arr2;
	$num_arr_1=array();
	
	for($i=0; $i<count($num_arr2); $i++)
	{
		$is_zero=substr($num_arr2[$i],0,1);
		$recv_arr[$i]=$is_zero?"0".$num_arr2[$i]:$num_arr2[$i];
	    $recv_arr[$i] = preg_replace("/[^0-9]/i", "", $recv_arr[$i]); 

		if(!check_cellno($recv_arr[$i]))
		{//기타 번호(폰번호아님) 모으기: $_POST[send_deny_wushi_2]
			array_push($check_cnt2,$num_arr2[$i]);//기타			
			if($_POST[send_deny_wushi_2])
			continue;
		}			
		$num_arr2[$i]=preg_replace("/[^0-9]/i","",$num_arr2[$i]);					

		$sql_deny = "select idx from Gn_MMS_Deny where recv_num = '$num_arr2[$i]' and mem_id = '$_SESSION[one_member_id]'";//수신거부
		$resul_deny=mysqli_query($self_con,$sql_deny)or die(mysqli_error($self_con));
		$row_deny=mysqli_fetch_array($resul_deny);
		if($row_deny[idx])
		{//수신 거부 번호 모으기 : $_POST[send_deny_wushi_3]
			array_push($check_cnt3,$num_arr2[$i]);//수신거부
			if($_POST[send_deny_wushi_3])
			continue;	
		}
	    //$sql_etc="select seq,msg_flag from sm_log where ori_num='$num_arr2[$i]' order by seq desc limit 0,1";
		$sql_etc="select seq,dest,msg_flag from sm_log where ori_num='$num_arr2[$i]' and mem_id='$_SESSION[one_member_id]' order by seq desc limit 0,1 ";
		
		$resul_etc=mysqli_query($self_con,$sql_etc);
		$row_etc=mysqli_fetch_array($resul_etc);
		if($row_etc[seq])
		{
			if($row_etc[msg_flag]==1)
			{//기타 번호 모으기 : $_POST[send_deny_wushi_2]
				array_push($check_cnt2,$num_arr2[$i]);//기타
				if($_POST[send_deny_wushi_2])
				continue;
			}
			else if($row_etc[msg_flag]==2)
			{//없는 번호 모으기 : $_POST[send_deny_wushi_1]
				array_push($check_cnt1,$num_arr2[$i]);//없는번호
				if($_POST[send_deny_wushi_1])
				continue;	
			}
			else if($row_etc[msg_flag]==3)
			{//수신불가 번호 모으기 : $_POST[send_deny_wushi_0]
				array_push($check_cnt0,$num_arr2[$i]);//수신불가
				if($_POST[send_deny_wushi_0])						
				continue;							
			}

		}						
		array_push($num_arr_1,$num_arr2[$i]); // 제외 빼고 나머지 번호들
	}	
	$num_arr2=$num_arr_1; //제외 빼고 나머지 번호들 넣기
	unset($num_arr_1);
	
	foreach($num_arr2 as $key=>$v)
	{
			$is_zero=substr($v,0,1);
			$v=$is_zero?"0".$v:$v;			
			$v=preg_replace("/[^0-9]/i","",$v);
			//if(!check_cellno($v))
			//    array_push($check_cnt2,$v);//기타			
			$sql_deny="select idx,recv_num from Gn_MMS_Deny where recv_num='$v' and mem_id='$_SESSION[one_member_id]' ";
			$resul_deny=mysqli_query($self_con,$sql_deny);
			$row_deny=mysqli_fetch_array($resul_deny);
			//if($row_deny[idx])
			//    array_push($check_cnt3,$row_deny[recv_num]);//수신거부
				
			$sql_etc="select seq,dest,msg_flag from sm_log where ori_num='$v' and mem_id='$_SESSION[one_member_id]' order by seq desc limit 0,1 ";
			$resul_etc=mysqli_query($self_con,$sql_etc);
			$row_etc=mysqli_fetch_array($resul_etc);
			if($row_etc[seq])
			{
				//if($row_etc[msg_flag]==1)
				//array_push($check_cnt2,$v);//기타
				//if($row_etc[msg_flag]==2)
				//array_push($check_cnt1,$v);//없는번호
				//if($row_etc[msg_flag]==3)
				//array_push($check_cnt0,$v);//수신불가			
			}
			//print_r($ssh_total_num);
			if(in_array_fun($v,$ssh_total_num))
			    @array_push($check_cnt4,$v);			
	}
	
	$diff_info = array_diff($num_arr,$ssh_num); 
	
	// 추가 2016-05-11
	$num_arr=array_merge($num_arr,explode(",",$_POST[send_num]));
	$num_arr=array_unique($num_arr);
    $num_arr_1=array();
    $total_num_cnt = count($num_arr);
    $send_cnt=$_POST[send_go_user_cnt];
    $memo2_arr=$_POST[send_go_memo2];
    
	for($i=0; $i<count($num_arr); $i++)
	{
		$is_zero=substr($num_arr[$i],0,1);
		$recv_arr[$i]=$is_zero?"0".$num_arr[$i]:$num_arr[$i];
	    $recv_arr[$i] = preg_replace("/[^0-9]/i", "", $recv_arr[$i]); 

		if(!check_cellno($recv_arr[$i]))

		{//기타 번호(폰번호아님) 모으기: $_POST[send_deny_wushi_2]
			@array_push($etc_arr,$num_arr[$i]);
			if($_POST[send_deny_wushi_2])
			continue;
		}			
		$num_arr[$i]=preg_replace("/[^0-9]/i","",$num_arr[$i]);					

		$sql_deny = "select idx from Gn_MMS_Deny where recv_num = '$num_arr[$i]' and mem_id = '$_SESSION[one_member_id]'";//수신거부
		$resul_deny=mysqli_query($self_con,$sql_deny)or die(mysqli_error($self_con));
		$row_deny=mysqli_fetch_array($resul_deny);
		if($row_deny[idx])
		{//수신 거부 번호 모으기 : $_POST[send_deny_wushi_3]
			@array_push($deny_num,$num_arr[$i]);
			if($_POST[send_deny_wushi_3])
			continue;	
		}
		$sql_etc="select seq,msg_flag from sm_log where  mem_id = '$_SESSION[one_member_id]' and ori_num='$num_arr[$i]' order by seq desc limit 0,1";
		$resul_etc=mysqli_query($self_con,$sql_etc);
		$row_etc=mysqli_fetch_array($resul_etc);
		if($row_etc[seq])
		{
			if($row_etc[msg_flag]==1)
			{//기타 번호 모으기 : $_POST[send_deny_wushi_2]
				@array_push($etc_arr,$num_arr[$i]);
				if($_POST[send_deny_wushi_2])
				continue;
			}
			else if($row_etc[msg_flag]==2)
			{//없는 번호 모으기 : $_POST[send_deny_wushi_1]
				@array_push($wrong_arr,$num_arr[$i]);
				if($_POST[send_deny_wushi_1])
				continue;	
			}
			else if($row_etc[msg_flag]==3)
			{//수신불가 번호 모으기 : $_POST[send_deny_wushi_0]
				@array_push($lose_arr,$num_arr[$i]);
				if($_POST[send_deny_wushi_0])						
				continue;							
			}

		}						
		array_push($num_arr_1,$num_arr[$i]); // 제외 빼고 나머지 번호들
	}	
    $num_arr=$num_arr_1; //제외 빼고 나머지 번호들 넣기
    
					
	unset($num_arr_1);
	//발송가능 폰번호별 이번 달 발송했던 수신번호 확인
	$send_msg = array(); // 2016-05-08 상태 메세지
	$num_arr_2=array();
	$num_arr_3=array();
	$ssh_num_true=array(); //새로 발송 가능 수신처
	$ssh_total_num=array();
    
 
    //$re_today_cnt = 0;
	for($j=0;$j<count($sendnum);$j++) //발송가능 폰번호별
	{
	    
            $superChk = false;
            $query = "select * from Gn_Member where mem_id='$_SESSION[one_member_id]'";
            $result = mysqli_query($self_con,$query);
            $member_info = mysqli_fetch_array($result);
            
            $query = "select * from Gn_MMS_Number where mem_id='$_SESSION[one_member_id]' and sendnum='".str_replace("-", "", $member_info['mem_phone'])."'";
            $result = mysqli_query($self_con,$query);
            $info = mysqli_fetch_array($result);
            if($info[memo2] != "") {
                $telecom = $info['memo2'];
            }							
            
            
            
			$memo2 = $info[memo2];
			$limitCnt = $memo2 ? $agency_arr[$memo2] : 800; //월별 수신처 제한 수                            
			$daily_cnt = $limitCnt * 0.01 * $info['donation_rate']; // 기부비율에 맞춰 수정
			
            $memo2_arr[] = $memo2;
            if($sendnum[$j] == str_replace("-", "", $member_info['mem_phone'])) {
                if($member_info['mem_type'] == "V" || $member_info['mem_type'] == "") {
                    
                    if($telecom == "SK") {
                        // SKT
                        $superChk = true;
                        
                        $limitCnt = 3000;
                        $daily_cnt = $limitCnt * 0.01 * $info['donation_rate']; // 기부비율에 맞춰 수정
                    } else if($telecom == "KT") {
                        // KT
                        $superChk = true;
                        $limitCnt = 2000;
                        $daily_cnt = $limitCnt * 0.01 * $info['donation_rate']; // 기부비율에 맞춰 수정
                    } else if($telecom == "LG") {
                        // LG
                        $superChk = true;
                        $limitCnt = 1000000; // 무제한
                        $daily_cnt = $limitCnt * 0.01 * $info['donation_rate']; // 기부비율에 맞춰 수정
                    }
                }
            }
            
    			    
			$ssh_num=array(); //$ssh_num <= 중복없는 수신번호들
			
			//$sql_ssh="select recv_num from Gn_MMS where mem_id = '$_SESSION[one_member_id]' and send_num='$sendnum[$j]' and result = '0' and reg_date like '$date_month%'";
			$sql_ssh="select recv_num from Gn_MMS where mem_id = '$_SESSION[one_member_id]' and send_num='$sendnum[$j]' and result = '0' and reg_date like '$date_month%'";
			$resul_ssh=mysqli_query($self_con,$sql_ssh);
			if(mysqli_num_rows($resul_ssh))
			{
				while($row_ssh=mysqli_fetch_array($resul_ssh))
				{
					$ssh_arr=array();
					$ssh_arr=explode(",",$row_ssh[recv_num]);
					$ssh_num=array_merge($ssh_num,(array)$ssh_arr);	 
				}
				//echo count($ssh_num)."\n";
				
				unset($ssh_arr);
				$ssh_numarray_unique=($ssh_num); //$ssh_num <= 중복없는 수신번호들	
				$send_num_list[$sendnum[$j]] = array_intersect((array)$ssh_num, (array)$num_arr_2);
				//$num_arr_2 = array
			}
			

			$used_ssh_cnt = count(array_unique($ssh_num)); //사용된 수신처 수 //2016-03-10 추가

			//새로 발송 가능 수신처 , $agency_arr는 rlatjd_fun.php에서 정의 
			$ssh_num_true[$j]=$agency_arr[$memo2_arr[$j]]-$used_ssh_cnt; //2016-03-10 수정
			
    		// Cooper Add 총 발송건수 체크 (선거 본인용) 수신처 무제한 변경 2016-04-04 
    		if($sendnum[$j] == str_replace("-", "", $member_info['mem_phone']) && $member_info[mem_type] == "V") {
    		        $ssh_num_true[$j] = 1000000;
    		}								
    		
    		

			$ssh_total_num=array_merge($ssh_total_num,$ssh_num); //총 수신처 누적						

			
			
			// 폰별 수신처별 번호 배정 추가 2016-05-02
	}
	

	 
	//echo count(array_unique($num_arr));

    $s=0; //전체 발송 번호 건수 중 수신거부 링크 추가한 인덱스로 사용
    $re_today_cnt=0; //금일 전송 성공
    $ssh_total_cnt=0; //전송실패건수(수신처제한)
    
    //변수 선언 2016-03-10 추가
    $today_limit = 500; //기부 받은 일일 최대 발송량(500 : 대량문자 100% 기부) 4여우
    $grade_limit = 200; //1구간 최대 발송량(200) 2여유				
    
    $remain_count = $today_will_send_count;											
	/*
	 2) $ssh_total_num : 총 수신처 누적에 $num_arr 요소가 
        있으면 $num_arr_2 추가
        없으면 $num_arr_3 추가
	*/
	
	if($_POST[send_ssh_check] || $_POST[send_ssh_check2] || $_POST[send_ssh_check3])
	{
		foreach($num_arr as $key=>$v)
		{
			if(in_array_fun($v,$ssh_total_num))
			array_push($num_arr_2,$v); //기존수신처
			else
			array_push($num_arr_3,$v); //새 수신처				
		}
		unset($ssh_total_num);

		if($_POST[send_ssh_check]){ //수신처 우선
			$num_arr=array_merge($num_arr_2,$num_arr_3);
			//$num_arr=array_merge($num_arr_3,$num_arr_2);  // Cooper 2016-04-26 순서 변경
			//$ssh_num_true[$j] = count($num_arr_2);
			$recv_exp_cnt = count($num_arr_2);
		}
		if($_POST[send_ssh_check2]){ //수신처 제외
			$num_arr=$num_arr_3;
		}
		if($_POST[send_ssh_check3]){ //수신처 전용
			$num_arr=$num_arr_2;
			$ssh_num_true[$j] = count($num_arr_2);
			$recv_exp_cnt = count($num_arr_2);
			
		}
		
		if($_POST[send_ssh_check] || $_POST[send_ssh_check2] || $_POST[send_ssh_check3]){ //수신처 우선
		    
		    
		    $loop_check_num = 0; // 폰별 신규 배정된 번호 합
		    $loop_allocate_num = 0; // 폰별 배정된 번호 합
    		for($j=0;$j<count($sendnum);$j++) //발송가능 폰번호별
    		{
    			$req = $reg.$j;
    			if ($max_cnt_arr[$j] > $today_limit)  $max_cnt_arr[$j] = $today_limit; //최대 발송 수 494 건 넘는 것 제한
    			
    			$recv_arr=array();
    			$deny_url_arr=array();
    			//$left_ssh_count = $ssh_num_true[$j]-2; // 발송 가능 수신처 수(2여유 둠)
    			//$this_time_send = $send_cnt[$j]-2; //이번 발송 가능 수 -2
    			$left_ssh_count = $ssh_num_true[$j]; // 발송 가능 수신처 수(2여유 둠)
    			$this_time_send = $send_cnt[$j]; //이번 발송 가능 수 -2					
    			$allocation_cnt = count($num_arr);
    			
    			$cnt1_log_arr[$j] = 0; //초기화 // 2016-03-07 추가
    			$cnt2_log_arr[$j] = 0; 
    			$cntYN_log_arr[$j] = 0;
    			$cntAdj_log_arr[$j] = "";
    			$remain_array = array();
	    						    
	    						    
    		    // 폰별 번호 배정
    		    if($_POST[send_ssh_check] ||  $_POST[send_ssh_check3]) {
    				$ssh_num=array(); //$ssh_num <= 중복없는 수신번호들
    				//$sql_ssh="select recv_num from Gn_MMS where mem_id = '$_SESSION[one_member_id]' and send_num='$sendnum[$j]' and result = '0' and reg_date like '$date_month%' group by(recv_num)";
    				$sql_ssh="select recv_num from Gn_MMS where mem_id = '$_SESSION[one_member_id]' and send_num='$sendnum[$j]' and result = '0' and reg_date like '$date_month%' group by(recv_num)";
    				//echo $sql_ssh;
    				$resul_ssh=mysqli_query($self_con,$sql_ssh);
    				if(mysqli_num_rows($resul_ssh))
    				{
    					while($row_ssh=mysqli_fetch_array($resul_ssh))
    					{
    						$ssh_arr=array();
    						$ssh_arr=explode(",",$row_ssh[recv_num]);
    						$ssh_num=array_merge($ssh_num,(array)$ssh_arr);	 			
    					}
    					unset($ssh_arr);
    					$ssh_num=array_unique($ssh_num); //$ssh_num <= 중복없는 수신번호들	
    					//print_r($num_arr_2);
    					$send_num_list[$sendnum[$j]] = array_intersect($ssh_num, $num_arr_2); //해당 값의 중복값 (우선발송 OR 전용 발송 값 배정
    					$ck = 0;
    					//echo $this_time_send;
    					foreach($send_num_list[$sendnum[$j]] as $key=>$val) {
    					    if($ck > $this_time_send-1)
    					        array_push($remain_array,$val); //오버되는 수신처
    					    $ck++;
    					}
    					//print_r($send_num_list);
    					//print_r($remain_array);
    					//print_R($send_num_list[$sendnum[$j]]);
    					$send_num_list[$sendnum[$j]] = array_diff($send_num_list[$sendnum[$j]], $remain_array);
    					// 중복 배열 삭제
    					$num_arr_2 = array_diff($num_arr_2, $send_num_list[$sendnum[$j]]); // 사용한 발송이력 전화번호 배정후 삭제
    					
    					// 오버 배열 재적용
    					//$num_arr_2 = array_merge($num_arr_2, $remain_array);

    					sort($send_num_list[$sendnum[$j]]);
    					
    					if(count($send_num_list[$sendnum[$j]]) >0) {
    					    $success_cell_arr[$sendnum[$j]] = $sendnum[$j];
    					}
    					
    					//echo $sendnum[$j]."====".count($send_num_list[$sendnum[$j]])."\n";
    				}
    			}
    
    			$used_ssh_cnt = count($ssh_num); //사용된 수신처 수 //2016-03-10 추가
    
    			//새로 발송 가능 수신처 , $agency_arr는 rlatjd_fun.php에서 정의 
       			$ssh_num_true[$j]=$agency_arr[$memo2_arr[$j]]-$used_ssh_cnt; //2016-03-10 수정						    
       			//echo "발송수신처".$ssh_num_true[$j]."\n";
       			
    			// Cooper Add 총 발송건수 체크 (선거 본인용) 수신처 무제한 변경 2016-04-04 
    			if($sendnum[$j] == str_replace("-", "", $member_info['mem_phone']) && $member_info[mem_type] == "V") {
    			        $ssh_num_true[$j] = 1000000;
    			}										
    			
    			// HERE 코드 확인
    			// 전화번호별 1일 발송양, 월간 발송양 , 월간 수신처 확인
    			// 오늘발송량, 이달발송량, 이달수신처량(사용량/전체한도), 이달 200건 초과횟수 10회 미만일경우만 발송(200건이상 발송의 경우 10회 미만일경우 성공 10회 이상일경우 실패)
    			// ( 오늘 오전에 100건 보내고 오후에 100건 보내면 200미만 +1 카운트에서 200초과 +1로 이동해야 하니까 / 취소하거나 미발송건으로 복원시에도)        					
    			// STEP #1 == 1일 발송양 확인 // 폰별
    			
    			// $total_num_cnt 총발송양
    			//echo $send_cnt[$j]." < ".count($num_arr)." < ".$allocation_cnt."\n";
    			if($send_cnt[$j] < count($num_arr)) {
    			    $allocation_cnt = $send_cnt[$j]; // 일발송양보다 작으면 일발송양으로 수정
    			    //echo "T1";
    			} else {
    			    //$allocation_cnt = $send_cnt[$j];
    			    //echo "T2";
    			}
    			
                $query = "select * from Gn_MMS_Number where mem_id='$_SESSION[one_member_id]' and sendnum='".$sendnum[$j]."'";
                $result = mysqli_query($self_con,$query);
                $info = mysqli_fetch_array($result);    		
                // 이번달 발송건이 10건 이상시	
                if($info['cnt1'] >= 10) $allocation_cnt = 199;    			
    			
    			
    			
    			// STEP #2 == 월간 발송양 확인 // 아이디별
    			//echo "월발송양:".$thiMonleftCnt."\n";
    			//if($thiMonleftCnt < $total_num_cnt) {
    			//    $allocation_cnt = $thiMonleftCnt;
    			//    //echo "T3";
    			//}
    			 
    			// STEP #3 == 월간 수신처 확인 // 폰별 $limitCnt
    			//echo "수신처:".$used_ssh_cnt."==".$limitCnt."====".$ssh_num_true[$j]."===".$ssh_num_true[$j]."===".$allocation_cnt."\n";
    			// 2016.05.18 수정
    			if($ssh_num_true[$j] <= $allocation_cnt) {
    			    // 수신처 초과시 신규 배정 X
    			    $allocation_cnt = $allocation_cnt;
    			} else if($ssh_num_true[$j] <= 0) {    
    				$allocation_cnt = 0;
    			    //echo "T4";
    			} else {
    			    // 수신처 초과가 아닌경우 
    			    //if($allocation_cnt > $limitCnt - $used_ssh_cnt) {
    			    //    $allocation_cnt = $limitCnt - $used_ssh_cnt;
    			        //echo "T5";
    			    //}
    			}
    			//echo "수신처:".$used_ssh_cnt."==".$limitCnt."====".$ssh_num_true[$j]."===".$ssh_num_true[$j]."===".$allocation_cnt."\n";
    			
    			//echo "배정수:$allocation_cnt==$total_num_cnt";
    			
    			// STEP #4 == 이달 200건 초과횟수 10회 미만일경우만 발송(200건이상 발송의 경우 10회 미만일경우 성공 10회 이상일경우 실패)
    			if($cnt1_arr[$j]>=10) { //10회 이상일우
    			    $send_msg[$j] = $sendnum[$j]."폰의 이달 200건 초과횟수 10회 이상 예외처리 199건까지 발송가능";
    		    }
    		    
    		    
    		     // STEP #4-1 폰별 신규 폰번호 발송 가능양에 따라 재분배
    			if($_POST[send_ssh_check]) {
    			    //echo "check";
    			    // 차이 만큼 신규 배정
    			    
                    if($loop_check_num <= $allocation_cnt - count($send_num_list[$sendnum[$j]])) {
                        
                        $send_num_list_cnt = count($send_num_list[$sendnum[$j]]);
                        //echo "$loop_check_num < $allocation_cnt - ".count($send_num_list[$sendnum[$j]])."============".($allocation_cnt - $send_num_list_cnt)."==========".count($num_arr_3)."\n";
                        // 총 발송 건수와 배정된 건수가 적을 경우만 루프
                        if(  $loop_allocate_num < count($num_arr)) {
                            for($kkk = 0; $kkk < $allocation_cnt - $send_num_list_cnt;$kkk++) {
                            //for($kkk = 0; $kkk < $allocation_cnt;$kkk++) {
                                if($num_arr_3[$kkk]) { // 값이 있을경우 배정
                                    //echo ($send_num_list_cnt."+".$kkk)."===".$num_arr_3[$kkk]."\n";
                                    $send_num_list[$sendnum[$j]][$send_num_list_cnt+$kkk] = $num_arr_3[$kkk];
                                    $success_cell_arr[$sendnum[$j]] = $sendnum[$j];
                                }
                            }
                            //echo "CNT:".count($num_arr_3)."\n";
                            $num_arr_3 = array_diff($num_arr_3, $send_num_list[$sendnum[$j]]);
                            sort($num_arr_3);
                            $loop_allocate_num = count($send_num_list[$sendnum[$j]]);
                            $loop_check_num = $loop_check_num + $send_num_list_cnt;
                        }
                        //echo $sendnum[$j]."====".count($send_num_list[$sendnum[$j]])."\n";
                    }
    		    }        			
    		    	
    			if($_POST[send_ssh_check2]) {
    			    // 차이 만큼 신규 배정
    			    
                    if($loop_check_num < $allocation_cnt - count($send_num_list[$sendnum[$j]])) {
                        $send_num_list_cnt = count($send_num_list[$sendnum[$j]]);
                        // 총 발송 건수와 배정된 건수가 적을 경우만 루프
                        
                        if(  count($num_arr) > 0) {
                            
                            for($kkk = 0; $kkk < $allocation_cnt - $send_num_list_cnt;$kkk++) {
                                
                                if($num_arr[$kkk]) { // 값이 있을경우 배정
                                    $send_num_list[$sendnum[$j]][$send_num_list_cnt+$kkk] = $num_arr[$kkk];
                                    $success_cell_arr[$sendnum[$j]] = $sendnum[$j];
                                }
                            }
                            
                            $num_arr = array_diff($num_arr, $send_num_list[$sendnum[$j]]);
                            sort($num_arr);
                            $loop_allocate_num = count($send_num_list[$sendnum[$j]]);
                            $loop_check_num = $loop_check_num + $send_num_list_cnt;
                            
                            
                        }
                    }
    		    }        				    						    
                $success_arr=array_merge($success_arr,(array)$send_num_list[$sendnum[$j]]);
    			
    			// STEP #5 == 금일 발송양에 따른 통계 계산        						
    			//echo $sendnum[$j]."===".count($send_num_list[$sendnum[$j]])."===".$this_time_send."\n";
    			
    			if($_POST[send_ssh_check]) {
    				$sql_check_s="select no,status from tjd_mms_cnt_check where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum[$j]' and date=curdate() ";
    				$resul_check_s=mysqli_query($self_con,$sql_check_s);
    				$row_check_s=mysqli_fetch_array($resul_check_s);
    				if($row_check_s[no]) { //tjd_mms_cnt_check에 자료 있으면 : 오늘 보낸 적 있음
    				    if($row_check_s[status]=="N") { //200미만 건 발송 이력 있음
    					    // Cooper Add  2016-05-08
    					    if($this_time_send >= 200) {
//    							$cntYN_log_arr[$j] = $this_time_send; //2016-05-08 추가
    							$cntYN_log_arr[$j] = count($send_num_list[$sendnum[$j]]); //2016-05-08 추가
    						}
    					} else if($row_check_s[status]=="Y") { //200미만 건 발송 이력 있음
    					    
    					}
    				} else {
    					//$cntYN_log_arr[$j] = $this_time_send; //2016-05-08 추가
    					$cntYN_log_arr[$j] = count($send_num_list[$sendnum[$j]]); //2016-05-08 추가
    					
    			    }    							
    			} else {
    				$sql_check_s="select no,status from tjd_mms_cnt_check where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum[$j]' and date=curdate() ";
    				$resul_check_s=mysqli_query($self_con,$sql_check_s);
    				$row_check_s=mysqli_fetch_array($resul_check_s);
    				if($row_check_s[no]) { //tjd_mms_cnt_check에 자료 있으면 : 오늘 보낸 적 있음
    				    if($row_check_s[status]=="N") { //200미만 건 발송 이력 있음
    					    // Cooper Add  2016-05-08
    					    if($this_time_send >= 200) {
    							$cntYN_log_arr[$j] = count($send_num_list[$sendnum[$j]]); //2016-05-08 추가
    						}
    					} else if($row_check_s[status]=="Y") { //200미만 건 발송 이력 있음
    					}
    				} else {
    					$cntYN_log_arr[$j] = count($send_num_list[$sendnum[$j]]); //2016-05-08 추가
    			    }    							            					    
    			}
    			//echo count($send_num_list[$sendnum[$j]])."====".$this_time_send."\n";
    		}	
    		//echo $sql_num;
    		//print_r($send_num_list);
    	}
	} else {
		    $loop_check_num = 0; // 폰별 신규 배정된 번호 합
		    $loop_allocate_num = 0; // 폰별 배정된 번호 합
    		for($j=0;$j<count($sendnum);$j++) //발송가능 폰번호별
    		{
    			$req = $reg.$j;
    			if ($max_cnt_arr[$j] > $today_limit)  $max_cnt_arr[$j] = $today_limit; //최대 발송 수 494 건 넘는 것 제한
    			

    			
    			$recv_arr=array();
    			$deny_url_arr=array();
    			//$left_ssh_count = $ssh_num_true[$j]-2; // 발송 가능 수신처 수(2여유 둠)
    			//$this_time_send = $send_cnt[$j]-2; //이번 발송 가능 수 -2
    			$left_ssh_count = $ssh_num_true[$j]; // 발송 가능 수신처 수(2여유 둠)
    			$this_time_send = $send_cnt[$j]; //이번 발송 가능 수 -2					
    			$allocation_cnt = count($num_arr);
    			
    			$cnt1_log_arr[$j] = 0; //초기화 // 2016-03-07 추가
    			$cnt2_log_arr[$j] = 0; 
    			$cntYN_log_arr[$j] = 0;
    			$cntAdj_log_arr[$j] = "";
    			$remain_array = array();
	    						    
    			$used_ssh_cnt = count($ssh_num); //사용된 수신처 수 //2016-03-10 추가
    
    			//새로 발송 가능 수신처 , $agency_arr는 rlatjd_fun.php에서 정의 
       			$ssh_num_true[$j]=$agency_arr[$memo2_arr[$j]]-$used_ssh_cnt; //2016-03-10 수정						    
       			//echo $agency_arr[$memo2_arr[$j]]." - ".$used_ssh_cnt."\n";
       			//echo "발송수신처".$ssh_num_true[$j]."\n";
       			
    			// Cooper Add 총 발송건수 체크 (선거 본인용) 수신처 무제한 변경 2016-04-04 
    			if($sendnum[$j] == str_replace("-", "", $member_info['mem_phone']) && $member_info[mem_type] == "V") {
    			        $ssh_num_true[$j] = 1000000;
    			}										
    			
    			// HERE 코드 확인
    			// 전화번호별 1일 발송양, 월간 발송양 , 월간 수신처 확인
    			// 오늘발송량, 이달발송량, 이달수신처량(사용량/전체한도), 이달 200건 초과횟수 10회 미만일경우만 발송(200건이상 발송의 경우 10회 미만일경우 성공 10회 이상일경우 실패)
    			// ( 오늘 오전에 100건 보내고 오후에 100건 보내면 200미만 +1 카운트에서 200초과 +1로 이동해야 하니까 / 취소하거나 미발송건으로 복원시에도)        					
    			// STEP #1 == 1일 발송양 확인 // 폰별
    			
    			// $total_num_cnt 총발송양
    			//echo $send_cnt[$j]." < ".count($num_arr)." < ".$allocation_cnt."\n";
    			if($send_cnt[$j] < count($num_arr)) {
    			    $allocation_cnt = $send_cnt[$j]; // 일발송양보다 작으면 일발송양으로 수정
    			    //echo "T1";
    			} else {
    			    //$allocation_cnt = $send_cnt[$j];
    			    //echo "T2";
    			}
    			
                $query = "select * from Gn_MMS_Number where mem_id='$_SESSION[one_member_id]' and sendnum='".$sendnum[$j]."'";
                $result = mysqli_query($self_con,$query);
                $info = mysqli_fetch_array($result);    		
                // 이번달 발송건이 10건 이상시	
                if($info['cnt1'] >= 10) $allocation_cnt = 199;
    			
    			
    			
    			// STEP #2 == 월간 발송양 확인 // 아이디별
    			//echo "월발송양:".$thiMonleftCnt."\n";
    			//if($thiMonleftCnt < $total_num_cnt) {
    			//    $allocation_cnt = $thiMonleftCnt;
    			//    //echo "T3";
    			//}
    			 
				// STEP #3 == 월간 수신처 확인 // 폰별 $limitCnt
				//echo "수신처:".$used_ssh_cnt."==".$limitCnt."====".$ssh_num_true[$j]."===".$ssh_num_true[$j]."\n";
    			if($ssh_num_true[$j] <= $allocation_cnt) {
    			    // 수신처 초과시 신규 배정 X
    			    $allocation_cnt = $allocation_cnt;
    			} else if($ssh_num_true[$j] <= 0) {    
    				$allocation_cnt = 0;
	    			    //echo "T4";
				} else {
				    // 수신처 초과가 아닌경우 
				    //if($allocation_cnt > $limitCnt - $used_ssh_cnt) {
				    //    $allocation_cnt = $limitCnt - $used_ssh_cnt;
				        //echo "T5";
				    //}
				}
				
				// 추가
				if($left_ssh_count  <= $allocation_cnt) {
				    $allocation_cnt = $left_ssh_count;
				}
    			//echo "수신처:".$used_ssh_cnt."==".$limitCnt."====".$ssh_num_true[$j]."===".$ssh_num_true[$j]."===".$allocation_cnt."\n";
    			
    			//echo "배정수:$allocation_cnt==$total_num_cnt";
    			
    			// STEP #4 == 이달 200건 초과횟수 10회 미만일경우만 발송(200건이상 발송의 경우 10회 미만일경우 성공 10회 이상일경우 실패)
    			if($cnt1_arr[$j]>=10) { //10회 이상일우
    			    $send_msg[$j] = $sendnum[$j]."폰의 이달 200건 초과횟수 10회 이상 예외처리 199건까지 발송가능";
    		    }
    		    
 
                    //echo $loop_check_num." < ".$allocation_cnt." - ".count($send_num_list[$sendnum[$j]])."\n";
                    if($loop_check_num < $allocation_cnt - count($send_num_list[$sendnum[$j]])) {
                        $send_num_list_cnt = count($send_num_list[$sendnum[$j]]);
                        // 총 발송 건수와 배정된 건수가 적을 경우만 루프
                        
                        if(  count($num_arr) > 0) {
                            echo "$allocation_cnt - $send_num_list_cnt\n";
                            for($kkk = 0; $kkk < $allocation_cnt - $send_num_list_cnt;$kkk++) {
                                
                                if($num_arr[$kkk]) { // 값이 있을경우 배정
                                    $send_num_list[$sendnum[$j]][$send_num_list_cnt+$kkk] = $num_arr[$kkk];
                                    $success_cell_arr[$sendnum[$j]] = $sendnum[$j];
                                }
                            }
                            
                            $num_arr = array_diff($num_arr, $send_num_list[$sendnum[$j]]);
                            sort($num_arr);
                            $loop_allocate_num = count($send_num_list[$sendnum[$j]]);
                            $loop_check_num = $loop_check_num + $send_num_list_cnt;
                            
                            
                        }
                    }
                    
                    //echo $sendnum[$j]."===".count($send_num_list[$sendnum[$j]])."\n";
                    
                $success_arr=@array_merge($success_arr,(array)$send_num_list[$sendnum[$j]]);
    			
    			// STEP #5 == 금일 발송양에 따른 통계 계산        						
    			//echo $sendnum[$j]."===".count($send_num_list[$sendnum[$j]])."===".$this_time_send."\n";
    			

				$sql_check_s="select no,status from tjd_mms_cnt_check where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum[$j]' and date=curdate() ";
				$resul_check_s=mysqli_query($self_con,$sql_check_s);
				$row_check_s=mysqli_fetch_array($resul_check_s);
				if($row_check_s[no]) { //tjd_mms_cnt_check에 자료 있으면 : 오늘 보낸 적 있음
				    if($row_check_s[status]=="N") { //200미만 건 발송 이력 있음
					    // Cooper Add  2016-05-08
							$cntYN_log_arr[$j] = count($send_num_list[$sendnum[$j]]); //2016-05-08 추가
					} else if($row_check_s[status]=="Y") { //200미만 건 발송 이력 있음
					}
				} else {
					$cntYN_log_arr[$j] = count($send_num_list[$sendnum[$j]]); //2016-05-08 추가
			    }    							            					    
    			//echo count($send_num_list[$sendnum[$j]])."====".$this_time_send."\n";
    		}	
    		//echo $sql_num;
    		//print_r($send_num_list);
	}

	//
	
	
	for($j=0;$j<count($sendnum);$j++) //발송가능 폰번호별 발송 가능 수신처 확인
	{					
 
        
        $max_cnt = count($send_num_list[$sendnum[$j]]); // 재선언 2016-05-08
        $re_today_cnt +=  $max_cnt;
 
	}    
	$ssh_total_cnt = $total_num_cnt - $re_today_cnt; // 재선언 발송실패 2016-05-08
	
	// 2016-05-11 끝
	
    unset($num_arr_2);
	unset($num_arr_3);	
	unset($etc_arr); //2016-03-07 위치이동
	unset($wrong_arr);
	unset($lose_arr);
	unset($deny_num);
	unset($revnum);
	unset($success_arr);		
	
	//echo "\n".print_r($num_arr)."\n";
	//echo print_r($ssh_num)."\n";
	//Cooper Add 전용발송시 발송된적 없는 번호 배열
	
	//$intersect = array_intersect($num_arr, $ssh_num);  // 교집합 2016-04-29
	
	//$diff_info = array_merge(array_diff($num_diff_info,$ssh_num));  // 값을 비교하여 중복값 삭제
	//$diff_info = array_diff($num_diff_info,$ssh_num); 
	unset($ssh_num);
	
    // Cooper Add 2016-04-27
	if($check_cnt4) {
	    $diff_info = array_merge(array_diff($diff_info,$check_cnt4));  // 값을 비교하여 중복값 삭제
	}	
	if($check_cnt3) {
	    $diff_info = array_merge(array_diff($diff_info,$check_cnt3));  // 값을 비교하여 중복값 삭제
	    
	}
	if($check_cnt2) {
	    $diff_info = array_merge(array_diff($diff_info,$check_cnt2));  // 값을 비교하여 중복값 삭제
	}
	if($check_cnt1) {
	    $diff_info = array_merge(array_diff($diff_info,$check_cnt1));  // 값을 비교하여 중복값 삭제
	}
	if($check_cnt0) {
	    $diff_info = array_merge(array_diff($diff_info,$check_cnt0));  // 값을 비교하여 중복값 삭제
	}				
	
	$check_cnt_diff4 = array_diff($check_cnt5,$check_cnt4);
	
	$kk = 1;
	foreach($diff_info as $key=>$value) {
	    if($kk == 1)
	        $check_cnt6_str = $value;
	    else
    	    $check_cnt6_str .= ",".$value;
    	$kk++;
	}	
		
	$check_cnt5_str=implode(",",$check_cnt5);	
	$check_cnt4_str=implode(",",$check_cnt4);	
	$check_cnt3_str=implode(",",$check_cnt3);
	$check_cnt2_str=implode(",",$check_cnt2);
	$check_cnt1_str=implode(",",$check_cnt1);
	$check_cnt0_str=implode(",",$check_cnt0);
	/*
	echo "<!--6:".count($diff_info)."-->";
	echo "<!--6:".count($check_cnt_diff4)."-->";
	echo "<!--5:$check_cnt5_str-->";
	echo "<!--4:$check_cnt4_str-->";
	echo "<!--3:$check_cnt3_str-->";
	echo "<!--2:$check_cnt2_str-->";
	echo "<!--1:$check_cnt1_str-->";
	*/
	
	?>
	<script language="javascript">
	var total_num=parseInt("<?=$total_send_num?>");
	var check_cnt6=parseInt("<?=count($check_cnt6)?>");	
	var check_cnt5=parseInt("<?=count($check_cnt5)?>");	
	var check_cnt4=parseInt(document.getElementsByName('ssh_check')[1].checked?"<?=count($check_cnt4)?>":"0");	
	var check_cnt3=parseInt(document.getElementsByName('deny_wushi[]')[3].checked?"<?=count($check_cnt3)?>":"0");
	var check_cnt2=parseInt(document.getElementsByName('deny_wushi[]')[2].checked?"<?=count($check_cnt2)?>":"0");
	var check_cnt1=parseInt(document.getElementsByName('deny_wushi[]')[1].checked?"<?=count($check_cnt1)?>":"0");
	var check_cnt0=parseInt(document.getElementsByName('deny_wushi[]')[0].checked?"<?=count($check_cnt0)?>":"0");

    var check_cnt6_str="<?=$check_cnt6_str?>";	
	var check_cnt5_str="<?=$check_cnt5_str?>";
	var check_cnt4_str=document.getElementsByName('ssh_check')[1].checked?"<?=$check_cnt4_str?>":"";
	var check_cnt3_str=document.getElementsByName('deny_wushi[]')[3].checked?"<?=$check_cnt3_str?>":"";
	var check_cnt2_str=document.getElementsByName('deny_wushi[]')[2].checked?"<?=$check_cnt2_str?>":"";
	var check_cnt1_str=document.getElementsByName('deny_wushi[]')[1].checked?"<?=$check_cnt1_str?>":"";
	var check_cnt0_str=document.getElementsByName('deny_wushi[]')[0].checked?"<?=$check_cnt0_str?>":"";
	
	var jy_cnt_str="";	
	var jy_cnt_arr=[];
	var jy_cnt2_arr=[];	
	var bd=jy_cnt_str!=""?",":"";
	if(check_cnt5_str)
	jy_cnt_str+=bd+check_cnt5_str;	
	if(check_cnt4_str) //2016-03-08 수정
	jy_cnt_str+=bd+check_cnt4_str;	
	if(check_cnt3_str)
	jy_cnt_str+=bd+check_cnt3_str;
	if(check_cnt2_str)
	jy_cnt_str+=bd+check_cnt2_str;
	if(check_cnt1_str)
	jy_cnt_str+=bd+check_cnt1_str;
	if(check_cnt0_str)
	jy_cnt_str+=bd+check_cnt0_str;
	
	if(jy_cnt_str)	
	{
	jy_cnt2_arr=jy_cnt_str.split(",");	
	jy_cnt_arr=uniqueArray2(jy_cnt2_arr,true);	
	}
	var cf_cnt=jy_cnt_arr.length;
	var jy_cnt=jy_cnt2_arr.length-cf_cnt;	
	var cf_cnt=cf_cnt?"-"+cf_cnt:cf_cnt;
	var shiji_cnt=total_num-jy_cnt;	
	
	
	//if($('input[name=ssh_check]:eq(0)').is(":checked") == true || $('input[name=ssh_check]:eq(1)').is(":checked") == true || $('input[name=ssh_check]:eq(2)').is(":checked") == true) {
	    shiji_cnt = (<?=$re_today_cnt?> *1);
	    
	    jy_cnt = total_num - shiji_cnt;
        if($('input[name=ssh_check]:eq(2)').is(":checked") == true)	    
	        $($(".num_check_c")[10]).html((<?=count($diff_info)?> *1));//수신처전용발송 누락
	    else
	        $($(".num_check_c")[10]).html(0);//수신처전용발송 누락
	//} else {
	//    $($(".num_check_c")[10]).html(0);//수신처전용발송 누락
	//}
	
	$($(".num_check_c")[0]).html(jy_cnt);//발송제외	
	$($(".num_check_c")[1]).html(shiji_cnt);//실제발송가능건
	$($(".num_check_c")[2]).html(total_num);//총발송건수
	$($(".num_check_c")[3]).html(cf_cnt);//처리후중복제거
	$($(".num_check_c")[4]).html(check_cnt5);//중복제거	
	$($(".num_check_c")[5]).html(check_cnt0);//수신불가
	$($(".num_check_c")[6]).html(check_cnt1);//없는번호	
	$($(".num_check_c")[7]).html(check_cnt2);//기타	
	$($(".num_check_c")[8]).html(check_cnt3);//수신거부
	$($(".num_check_c")[9]).html(check_cnt4);//수신처 제외	
	
	
	
	document.getElementsByName('deny_num')[0].value=check_cnt0_str;
	document.getElementsByName('deny_num')[1].value=check_cnt1_str;
	document.getElementsByName('deny_num')[2].value=check_cnt2_str;
	document.getElementsByName('deny_num')[3].value=check_cnt3_str;	
	document.getElementsByName('deny_num')[4].value=jy_cnt_arr;
	document.getElementsByName('deny_num')[5].value=check_cnt4_str;	
	document.getElementsByName('deny_num')[6].value=jy_cnt_arr;
	document.getElementsByName('deny_num')[7].value=check_cnt5_str;	
	document.getElementsByName('deny_num')[8].value=check_cnt6_str;	
	$('#recv_over').val('<?=$recv_over?>');
	</script>
	<?
}
//디테일 수정 삭제 추가
if($_POST[g_dt_status])
{
	$recv_num=str_replace(array("-"," ",","),"",$_POST[g_dt_num]);
	$is_zero=substr($recv_num,0,1);
	$recv_num=$is_zero?"0".$recv_num:$recv_num;
	$recv_num = preg_replace("/[^0-9]/i", "", $recv_num); 

	if(!check_cellno($recv_num))
	{
		?>
			<script language="javascript">
			alert('정확한 번호가 아닙니다.[수신번호error]');
			</script>
		<?
		exit;	
	}
	
	if($_POST[g_dt_status]!="del")
	{
	    if($_POST[g_dt_status] != "modify") {
    		$sql_c="select idx from Gn_MMS_Receive where mem_id='$_SESSION[one_member_id]' and grp_id='$_POST[g_dt_g_id]' and recv_num='$recv_num' ";
    		$resul_c=mysqli_query($self_con,$sql_c);
    		$row_c=mysqli_fetch_array($resul_c);
    		if($row_c[idx])
    		{
    			?>
    				<script language="javascript">
    				alert('현재 수신번호는 이미 등록되어있습니다.');
    				</script>
    			<?
    			exit;	
    		}
    	}
	}
	switch($_POST[g_dt_status])
	{
		case "modify":
			$sql = "update Gn_MMS_Receive set grp_2='$_POST[g_dt_grp_2]', recv_num = '$recv_num', name = '$_POST[g_dt_name]' where idx = '$_POST[g_dt_idx]'";		
		break;
		case "del":
			$sql = "delete from Gn_MMS_Receive where idx = '$_POST[g_dt_idx]'";
			$sql_g="update Gn_MMS_Group set count=count-1 where idx='$_POST[g_dt_g_id]' ";
			mysqli_query($self_con,$sql_g);			
		break;
		case "add":
			$sql = "insert into Gn_MMS_Receive set mem_id = '$_SESSION[one_member_id]',grp_2='$_POST[g_dt_grp_2]', recv_num = '$recv_num', name = '$_POST[g_dt_name]', grp_id = '$_POST[g_dt_g_id]', reg_date = now()";
			$sql_g="update Gn_MMS_Group set count=count+1 where idx='$_POST[g_dt_g_id]' ";
			mysqli_query($self_con,$sql_g);
		break;
	}
	if(mysqli_query($self_con,$sql))
	{
		?>
		<script language="javascript">
		alert('처리되었습니다.')
		location.reload();
		</script>
		<?		
	}
}
//그룹삭제
if($_POST[all_group_chk])
{
	$chk=$_POST[all_group_chk];
	for($i=0;$i<count($chk);$i++)
	{
		$sql1 = "select * from Gn_MMS_Group where mem_id = '$_SESSION[one_member_id]' and idx = '$chk[$i]'";
		$res1 = mysqli_query($self_con,$sql1);
		while($row1 = mysqli_fetch_array($res1))
		{
			$sql2 = "delete from Gn_MMS_Receive where mem_id = '$_SESSION[one_member_id]' and grp_id = '$row1[idx]'";
			mysqli_query($self_con,$sql2);
		}
		$sql0 = "delete from Gn_MMS_Group where mem_id = '$_SESSION[one_member_id]' and idx = '$chk[$i]'";
		$res0 = mysqli_query($self_con,$sql0);
	}
		?>
		<script language="javascript">
		alert('처리되었습니다.')
		location.reload();
		</script>
		<?	
}
//문자 저장
if($_POST[lms_save_title])
{
	if($_POST[lms_save_status]=="add")
	{
	$sql="insert into Gn_MMS_Message set ";
	$message_info[seq_num]=$_POST[lms_save_seq_num];
	$message_info[mem_id]=$_SESSION[one_member_id];	
	}
	else if($_POST[lms_save_status]=="modify")
	{
	$sql="update Gn_MMS_Message set ";		
	}
	$message_info[title]=htmlspecialchars($_POST[lms_save_title]);	
	$message_info[message]=htmlspecialchars($_POST[lms_save_content]);
	if($_POST[lms_save_img])
	$message_info[msg_type]="B";
	else
	$message_info[msg_type]="A";	
	$message_info[img]=$_POST[lms_save_img];
	$i=0;
	foreach($message_info as $key=>$v)
	{
		$bd=$i==count($message_info)-1?"":",";
		$sql.=" $key='$v' $bd ";
		$i++;
	}
	if($_POST[lms_save_status]=="add")
	{
		$sql.=" , reg_date=now() ";
	}
	else if($_POST[lms_save_status]=="modify")
	{
		$sql.=" where idx='$_POST[lms_save_idx]' ";		
	}
	if(mysqli_query($self_con,$sql))
	{
	?>
    <script language="javascript">
	alert('처리되었습니다.')
	location.reload();
	</script>
    <?	
	}
}
//문자삭제
if($_POST[lms_del_idx])
{
	$sql="delete from Gn_MMS_Message where idx='$_POST[lms_del_idx]' ";
	if(mysqli_query($self_con,$sql))
	{
	?>
    <script language="javascript">
	alert('삭제되었습니다.')
	location.reload();
	</script>
    <?		
	}
}
//등록관리설정저장
if($_POST[set_num])
{
	$num_arr=$_POST[set_num];
	$memo_arr=$_POST[set_memo];
	$memo2_arr=$_POST[set_memo2];
	$max_cnt_arr=$_POST[set_max_cnt];
	$gl_cnt_arr=$_POST[set_gl_cnt];	
	$user_cnt_arr=$_POST[set_user_cnt];
	foreach($num_arr as $key=>$v)
	{
		$mms_number_info[memo]=$memo_arr[$key];
		$mms_number_info[memo2]=$memo2_arr[$key];
		$mms_number_info[max_cnt]=$max_cnt_arr[$key];
		$mms_number_info[gl_cnt]=$gl_cnt_arr[$key];		
		$mms_number_info[user_cnt]=$user_cnt_arr[$key];
		$sql="update Gn_MMS_Number set ";
		$i=0;
		foreach($mms_number_info as $key2=>$v2)
		{
			$bd=$i==count($mms_number_info)-1?"":",";
			$sql.=" $key2='$v2' $bd ";
			$i++;
		}
		$sql.=" where sendnum='$v' and mem_id='$_SESSION[one_member_id]' ";
		mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
	}
	?>
    <script language="javascript">
	alert('저장되었습니다.');
	location.reload();
	</script>
    <?
}
//수신거부등록수정
if($_POST[deny_add_send] && $_POST[deny_add_recv])
{
	$recv_num=str_replace(array("-"," ",","),"",$_POST[deny_add_recv]);
	$is_zero=substr($recv_num,0,1);
	$recv_num=$is_zero?"0".$recv_num:$recv_num;
	$recv_num = preg_replace("/[^0-9]/i", "", $recv_num); 
	if(!check_cellno($recv_num))
	{
		?>
			<script language="javascript">
			alert('정확한 번호가 아닙니다.[수신번호error]');
			</script>
		<?
		exit;	
	}
	$send_num=str_replace(array("-"," ",","),"",$_POST[deny_add_send]);
	$is_zero=substr($send_num,0,1);
	$send_num=$is_zero?"0".$send_num:$send_num;	
	$send_num = preg_replace("/[^0-9]/i", "", $send_num); 
	if(!check_cellno($send_num))
	{
		?>
			<script language="javascript">
			alert('정확한 번호가 아닙니다.[발신번호error]');
			</script>
		<?
		exit;	
	}	
	
	$sql_num="select sendnum from Gn_MMS_Number where mem_id ='$_SESSION[one_member_id]' and sendnum='$send_num' ";
	$resul_num=mysqli_query($self_con,$sql_num);
	$row_num=mysqli_fetch_array($resul_num);
	if(!$row_num[sendnum])
	{
			?>
				<script language="javascript">
				alert('발신번호는 등록된 번호가 아닙니다.');
				</script>
			<?
			exit;		
	}	
	
	$sql_s="select idx from Gn_MMS_Deny where mem_id='$_SESSION[one_member_id]' and recv_num='$recv_num' and send_num='$send_num' ";
	$resul_s=mysqli_query($self_con,$sql_s);
	$row_s=mysqli_fetch_array($resul_s);
	if($row_s[idx])
	{
		?>
			<script language="javascript">
			alert('현재 수신번호는 이미 등록되어있습니다.');
			</script>
		<?
		exit;
	}	
	$deny_info[send_num]=$send_num;
	$deny_info[recv_num]=$recv_num;
	if($_POST[deny_add_idx])
	{
	$sql="update Gn_MMS_Deny set ";
	}
	else
	{	
		$sql="insert into Gn_MMS_Deny set ";
		$deny_info[title]="수동입력";
		$deny_info[content]="수동입력";	
		$deny_info[status]="B";		
		$deny_info[mem_id]=$_SESSION[one_member_id];	
	}
	$i=0;
	foreach($deny_info as $key=>$v)
	{
		$bd=$i==count($deny_info)-1?"":",";
		$sql.=" $key='$v' $bd ";
		$i++;
	}
	if($_POST[deny_add_idx])
		$sql.=" where idx='$_POST[deny_add_idx]' ";
	else
	$sql.=" , reg_date=now() ";
	if(mysqli_query($self_con,$sql) or die(mysqli_error($self_con)))
	{
	?>
    	<script language="javascript">
		alert('처리되었습니다.');
		location.reload();
		</script>
    <?	
	}
}

//수신동의등록수정
if($_POST[agree_add_send] && $_POST[agree_add_recv])
{
	$recv_num=str_replace(array("-"," ",","),"",$_POST[agree_add_recv]);
	$is_zero=substr($recv_num,0,1);
	$recv_num=$is_zero?"0".$recv_num:$recv_num;
	$recv_num = preg_replace("/[^0-9]/i", "", $recv_num); 
	if(!check_cellno($recv_num))
	{
		?>
			<script language="javascript">
			alert('정확한 번호가 아닙니다.[수신번호error]');
			</script>
		<?
		exit;	
	}
	$send_num=str_replace(array("-"," ",","),"",$_POST[agree_add_send]);
	$is_zero=substr($send_num,0,1);
	$send_num=$is_zero?"0".$send_num:$send_num;	
	$send_num = preg_replace("/[^0-9]/i", "", $send_num); 
	if(!check_cellno($send_num))
	{
		?>
			<script language="javascript">
			alert('정확한 번호가 아닙니다.[발신번호error]');
			</script>
		<?
		exit;	
	}	
	
	$sql_num="select sendnum from Gn_MMS_Number where mem_id ='$_SESSION[one_member_id]' and sendnum='$send_num' ";
	$resul_num=mysqli_query($self_con,$sql_num);
	$row_num=mysqli_fetch_array($resul_num);
	if(!$row_num[sendnum])
	{
			?>
				<script language="javascript">
				alert('발신번호는 등록된 번호가 아닙니다.');
				</script>
			<?
			exit;		
	}	
	
	$sql_s="select idx from Gn_MMS_Agree where mem_id='$_SESSION[one_member_id]' and recv_num='$recv_num' and send_num='$send_num' ";
	$resul_s=mysqli_query($self_con,$sql_s);
	$row_s=mysqli_fetch_array($resul_s);
	if($row_s[idx])
	{
		?>
			<script language="javascript">
			alert('현재 수신번호는 이미 등록되어있습니다.');
			</script>
		<?
		exit;
	}	
	$deny_info[send_num]=$send_num;
	$deny_info[recv_num]=$recv_num;
	if($_POST[deny_add_idx])
	{
	$sql="update Gn_MMS_Agree set ";
	}
	else
	{	
		$sql="insert into Gn_MMS_Agree set ";
		$deny_info[title]="수동입력";
		$deny_info[content]="수동입력";	
		$deny_info[status]="B";		
		$deny_info[mem_id]=$_SESSION[one_member_id];	
	}
	$i=0;
	foreach($deny_info as $key=>$v)
	{
		$bd=$i==count($deny_info)-1?"":",";
		$sql.=" $key='$v' $bd ";
		$i++;
	}
	if($_POST[deny_add_idx])
		$sql.=" where idx='$_POST[deny_add_idx]' ";
	else
	$sql.=" , reg_date=now() ";
	if(mysqli_query($self_con,$sql) or die(mysqli_error($self_con)))
	{
	?>
    	<script language="javascript">
		alert('처리되었습니다.');
		location.reload();
		</script>
    <?	
	}
}
//수신거부개별등록
if($_POST[deny_g_add_recv_num] && $_POST[deny_g_add_send_num])
{
		$num_arr=$_POST[deny_g_add_send_num];
		foreach($num_arr as $key=>$v)
		{
			$recv_num=str_replace(array("-"," ",","),"",$_POST[deny_g_add_recv_num]);
			$is_zero=substr($recv_num,0,1);
			$recv_num=$is_zero?"0".$recv_num:$recv_num;
			
			$v=preg_replace("/[^0-9]/i","",$v);
			$is_zero=substr($v,0,1);
			$v=$is_zero?"0".$v:$v;
			if(!check_cellno($v))
			{
				?>
					<script language="javascript">
					alert('정확한 번호가 아닙니다.[수신번호error]');
					</script>
				<?
				exit;	
			}			
			
			$sql="select idx from Gn_MMS_Deny where mem_id='$_SESSION[one_member_id]' and recv_num='$recv_num' and send_num='$v' ";
			$resul=mysqli_query($self_con,$sql);
			$row=mysqli_fetch_array($resul);
			if($row[idx])
			continue;			
			$sql_i="insert into Gn_MMS_Deny set ";
			$deny_info[send_num]=$v;	
			$deny_info[recv_num]=$recv_num;			
			$deny_info[title]="수동입력";
			$deny_info[content]="수동입력";	
			$deny_info[status]="B";		
			$deny_info[mem_id]=$_SESSION[one_member_id];
			foreach($deny_info as $key2=>$v2)
			$sql_i.=" $key2='$v2' , ";
			$sql_i.=" reg_date=now() ";
			mysqli_query($self_con,$sql_i);
		}
		?>
			<script language="javascript">
			alert('등록되었습니다.');
			location.reload();
			</script>
        <?
}
//수신거부 삭제
if($_POST[deny_del_ids])
{
	$ids=$_POST[deny_del_ids];
	$sql="delete from Gn_MMS_Deny where idx in($ids) ";
	if(mysqli_query($self_con,$sql))
	{
	?>
    	<script language="javascript">
		alert('처리되었습니다.');
		location.reload();
		</script>
    <?		
	}
}
//동의  삭제
if($_POST[agree_del_ids])
{
	$ids=$_POST[agree_del_ids];
	$sql="delete from Gn_MMS_Agree where idx in($ids) ";
	if(mysqli_query($self_con,$sql))
	{
	?>
    	<script language="javascript">
		alert('처리되었습니다.');
		location.reload();
		</script>
    <?		
	}
}
//앱체크
if($_POST[select_app_check_num])
{
	$num_arr=$_POST[select_app_check_num];
	$uni_id=time();
	$i=$_POST[select_app_check_i];
	$url = 'https://fcm.googleapis.com/fcm/send';
    $headers = array (
        'Authorization: key=' . GOOGLE_SERVER_KEY,
        'Content-Type: application/json'
    );
    for($k = 0 ; $k < count($num_arr); $k++) {
        $sendnum[] = $num_arr[$k];
    }
    
	$d=$i*10+15;
	$reg_date="DATE_ADD(now(), INTERVAL -{$d} second)";
	    
	if($i>1)
	{
	    

        $query = "select * from Gn_MMS_Number where mem_id='$_SESSION[one_member_id]' and sendnum in ('".implode("','",$sendnum)."')";
        $result = mysqli_query($self_con,$query);
        while($info = mysqli_fetch_array($result)) {
            $pkey[$info['sendnum']] = $info['pkey']; 	
            
            $id = $info['pkey'];
    		$sql="select idx from Gn_MMS where mem_id='$_SESSION[one_member_id]'  and send_num='$info[sendnum]' and result=0 and content like '%app_check_process%'  order by idx desc limit 0,1";
    		$resul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    		$row=mysqli_fetch_array($resul);
    		
                if(is_array($id)) {
                    $fields['registration_ids'] = $id;
                } else {
                    $fields['to'] = $id;
                }
                $sidx = $row['idx'];

                $title='{"MMS Push"}';
                $message='{"Send":"Start","idx":"'.$sidx.'","send_type":"1"}';
                $fields = array ( 
                    'data' => array (
                                "body" => $message,
                            	"title" => "app_check_process")
                );

                $fields['priority'] = "high";
                $fields['to'] = $id;
		$fields['token'] = $id;
                //$fields = json_encode ($fields);
                $fields = http_build_query($fields);
                $ch = curl_init ();
                curl_setopt ( $ch, CURLOPT_URL, "https://nm.kiam.kr/fcm/send_fcm.php" );
                curl_setopt ( $ch, CURLOPT_POST, true );
                //curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
                curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
                curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
                curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0); 
                curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);

                $kresult = curl_exec ( $ch );
                /*
                print_r($fields);
                print_r($url);
                print_r($kresult);
                */
                if ($kresult === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
                } 
                curl_close ( $ch );		               
        }		    
        
     
	}
		    	
	if($i==1)
	{
		foreach($num_arr as $key=>$v)
		{
			$title = "app_check_process";
			$content = $_SESSION[one_member_id].", app_check_process";
			sendmms(7, $_SESSION[one_member_id], $v, $v, "", $title, $content, "", "", "", "N");		
		}
		

	}
	sleep(10);
	$d=$i*10+15;
	$reg_date="DATE_ADD(now(), INTERVAL -{$d} second)";
	foreach($num_arr as $key=>$v)
	{
		if($check_status_arr[$key]=="on")
		continue;
		$sql="select idx,send_num,recv_num from Gn_MMS where mem_id='$_SESSION[one_member_id]' and reg_date>$reg_date and send_num='$v' and result=0 and content like '%app_check_process%' limit 0,1";
		$resul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
		$row=mysqli_fetch_array($resul);
/*
			$sql_s="select * from Gn_MMS_status where send_num	='$sendnum' and recv_num	='$sendnum' order by regdate desc limit 1 ";
			$resul_s=mysqli_query($self_con,$sql_s);
			$row_s=mysqli_fetch_array($resul_s);		
			*/
		$sql="select status from Gn_MMS_status where send_num='$row[send_num]' and  recv_num='$row[recv_num]' order by regdate desc limit 1 ";
		$sresul=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
		$srow=mysqli_fetch_array($sresul);		
		
		if($row[idx] && $srow[0]==0)
		{
			$check_status_arr[$key]="on";
			$check_status=true;
		}
		else
		{
			$check_status_arr[$key]="off";
			$check_status=false;	
		}
		$check_num_arr[$key]=$v;
	}
	$i++;
	$check_status_str=implode(",",$check_status_arr);	
	$check_num_str=implode(",",$check_num_arr);
	?> 
	<script language="javascript">
		var check_status_str="<?=$check_status_str?>";
		var check_status_arr=check_status_str.split(",");
		var check_num_str="<?=$check_num_str?>";
		var check_num_arr=check_num_str.split(",");	
		function app_check(num_arr,i)
		{
		$.ajax({
			 type:"POST",
			 url:"ajax/ajax_session_debug.php",
			 data:{
					select_app_check_num:num_arr,
					select_app_check_i:i
				  },
			 success:function(data){$("#ajax_div").html(data)}
			})			
		}
	<?
	if($check_status || $i==30)
	{
	?>
		for(var i=0; i<check_status_arr.length; i++)
		{
			if(check_status_arr[i]=="on")
			{
				$("#btn_"+check_num_arr[i]).addClass("btn_option_blue");
				$("#btn_"+check_num_arr[i]).html("ON");			
			}
			else
			{
				$("#btn_"+check_num_arr[i]).addClass("btn_option_red");
				$("#btn_"+check_num_arr[i]).html("OFF");			
			}
			
		}
		alert('앱상태 체크 완료되었습니다.');
	<?
	}
	else
	{
	?>
	app_check(check_num_arr,'<?=$i?>');
	<?
	}
	?>
    </script>
    <?
}
//발신수신 삭제 (2016-03-08 카운트 복구 추가)
if($_POST[fs_del_num_s])
{
	$num_s=$_POST[fs_del_num_s];

	if(strpos($num_s, ",") !== false){
		$nums_arr = explode(",",$num_s);
		arsort($nums_arr); //마지막 것부터 처리
	}else{
		$nums_arr = array($num_s);
	}	

	for($i=0;$i<count($nums_arr);$i++){
		
		$num = $nums_arr[$i];

		//발송완료여부 확인(up_date)
		$sql="select uni_id,mem_id,send_num,up_date,reg_date from Gn_MMS where idx = $num;";
		$result=mysqli_query($self_con,$sql);
		$row1=mysqli_fetch_array($result);

		$uni_id = $row1[uni_id];
		$mem_id = $row1[mem_id];
		$sendnum = $row1[send_num];
		$up_date = $row1[up_date];
		$reg_date = $row1[reg_date];		

		//이번 달 것인지 확인
		if (substr($reg_date,0,7) == date("Y-m") && !$up_date){ //이번 달

			$sqlRinfo="select cnt1,cnt2,cntYN,cntAdj,reg_date from Gn_MMS_Send_Cnt_Log where mem_id='$_SESSION[one_member_id]' and uni_id='$uni_id';";
			$resultRinfo=mysqli_query($self_con,$sqlRinfo);
			$rowRinfo=mysqli_fetch_array($resultRinfo);

			$Rinfo_cnt1 = $rowRinfo[cnt1]*-1;
			$Rinfo_cnt2 = $rowRinfo[cnt2]*-1;
			$Rinfo_cntYN = $rowRinfo[cntYN];
			$Rinfo_cntAdj = $rowRinfo[cntAdj];

			//최후의 것인지 확인
			//$sql="select uni_id from Gn_MMS where mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' and idx > $num limit 1;";
			$sql="select uni_id from Gn_MMS where mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE() limit 1;";
			$result1=mysqli_query($self_con,$sql);
			$row1=mysqli_fetch_array($result1);
			if($row1[uni_id]){ //최후 건 아님: cnt1,cnt2 복구

				$sql_num="update Gn_MMS_Number set cnt1=cnt1+($Rinfo_cnt1), cnt2=cnt2+($Rinfo_cnt2) where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum' ";
				mysqli_query($self_con,$sql_num);

				//이후 오늘 발송 건 유무 확인
				//$sql="SELECT uni_id FROM Gn_MMS WHERE mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE( ) and idx > $num limit 1;"; 
				$sql="SELECT uni_id FROM Gn_MMS WHERE mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE( ) and idx != '$num' limit 1;"; 
				$result=mysqli_query($self_con,$sql);
				$row=mysqli_fetch_array($result);
				
				if($row[uni_id]){//이후 오늘 발송 건 존재

					//$sql="select SUM(recv_num REGEXP '[,]') AS matches from Gn_MMS where mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE() and idx > $num;";
					$sql="select recv_num from Gn_MMS where mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE() and idx != '$num' ;";
					$result2=mysqli_query($self_con,$sql);

					$check_flag = 0;
					
					while($row2=mysqli_fetch_array($result2))
					{
					    $matches = count(explode(",", $row2['recv_num']));
						if($matches >= 198){
							$check_flag += 1;
						}
					}
					

					if ($check_flag == 0){
						$sql_num="update tjd_mms_cnt_check set status='N' where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum' and date=curdate(); ";
						mysqli_query($self_con,$sql_num);					
					}

				}else{//이후 오늘 발송 건 없음
					$sql_num="delete from tjd_mms_cnt_check where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum' and date=curdate();"; 
					mysqli_query($self_con,$sql_num);
				}

			}else{//최후 건 : cnt1,cnt2,cntYN,cntAdj 모두 복구

				$sql_num="update Gn_MMS_Number set cnt1=cnt1+($Rinfo_cnt1), cnt2=cnt2+($Rinfo_cnt2) where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum' ";
				echo $sql_num."\n";
				mysqli_query($self_con,$sql_num);

				$sql_num="delete from tjd_mms_cnt_check where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum' and date=curdate();";
				mysqli_query($self_con,$sql_num);

				$sql="select cnt1, cnt2 from Gn_MMS_Number where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum';";
				$result=mysqli_query($self_con,$sql);
				$row=mysqli_fetch_array($result);
				$chk_cnt1 = $row[cnt1];

				if ($chk_cnt1 < 10){//발송 가능 수 복구
					$sql_num="update Gn_MMS_Number set daily_limit_cnt=500, max_cnt = ceil(500 * 100 / donation_rate),gl_cnt = 500 - ceil(500 * 100 / donation_rate)  where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum' ";
					mysqli_query($self_con,$sql_num);			
				}
				
			}

		}

	}
	
	$sql="delete from Gn_MMS where idx in ($num_s);";
	if(mysqli_query($self_con,$sql)or die(mysqli_error($self_con)))
	$sql="delete from Gn_MMS_ReservationFail where idx in ($num_s);";
	if(mysqli_query($self_con,$sql)or die(mysqli_error($self_con)))
	{
	?>
    	<script language="javascript">
		alert('처리되었습니다.');
		location.reload();
		</script>
    <?	
	}
}
//회원탈퇴
if($_POST[member_leave_pwd])
{
	$mem_pass=$_POST[member_leave_pwd];
	$sql="select mem_code from Gn_Member where mem_id='$_SESSION[one_member_id]'  and web_pwd=password('$mem_pass') ";
	$resul=mysqli_query($self_con,$sql);
	$row=mysqli_fetch_array($resul);
	if($row[mem_code])
	{
		$sql_u="update Gn_Member set is_leave='Y' , leave_txt='$_POST[member_leave_liyou]' where mem_id='$_SESSION[one_member_id]' ";
		if(mysqli_query($self_con,$sql_u))
		{
			//session_unregister("one_member_id");
            $_SESSION['one_member_id'] = "";              
	        $_SESSION['one_member_admin_id'] = "";		?>
        <script language="javascript">
			alert('정상적으로 탈퇴처리되었습니다.')
			location.replace("./");
		</script>
        <?	
		}
	}
	else
	{
		?>
        <script language="javascript">
			alert('비밀번호가 정확하지 않습니다.')
			leave_form.leave_pwd.focus();
		</script>
        <?			
	}
}
//원북저장 삭제
if($_POST[one_del_num_s])
{
	$num_s=$_POST[one_del_num_s];
	$sql="delete from Gn_MMS_Message where idx in ($num_s) ";
	if(mysqli_query($self_con,$sql)or die(mysqli_error($self_con)))
	{
	?>
    	<script language="javascript">
		alert('처리되었습니다.')
		location.reload();
		</script>
    <?	
	}
}
//비밀번호 변경
if($_POST[pwd_change_old_pwd] && $_POST[pwd_change_new_pwd])
{
		$msg="비밀번호";
		$add_sql_u=" web_pwd=password('$_POST[pwd_change_new_pwd]') ";
		$add_sql=" web_pwd=password('$_POST[pwd_change_old_pwd]') ";
		$msg="비밀번호";
		$add_sql_u.=" ,mem_pass='".md5($_POST[pwd_change_new_pwd])."'";
		//$add_sql=" mem_pass='".md5($_POST[pwd_change_old_pwd])."'";		
	$sql="select mem_code from Gn_Member where mem_id='$_SESSION[one_member_id]' and $add_sql ";
	$resul=mysqli_query($self_con,$sql);
	$row=mysqli_fetch_array($resul);
	if($row[mem_code])
	{
		$sql_u="update Gn_Member set $add_sql_u where mem_id='$_SESSION[one_member_id]' ";
		if(mysqli_query($self_con,$sql_u))
		{
		?>
        	<script language="javascript">
			alert('변경되었습니다.');
			location.reload();
			</script>
        <?	
		}
	}
	else
	{
	?>
    	<script language="javascript">
			alert('기존 <?=$msg?> 가 틀렸습니다.');
			document.getElementsByName('old_pwd')[<?=$_POST[pwd_change_status]?>].focus();
		</script>
    <?	
	}
}
//휴대폰 상세정보등록
if($_POST[set_save_num])
{
	$num_arr=$_POST[set_save_num];
	$device_arr=$_POST[set_save_device];
	$memo3_arr=$_POST[set_save_memo3];
	foreach($num_arr as $key=>$v)
	{
		$mms_number_info[device]=$device_arr[$key];		
		$mms_number_info[memo3]=$memo3_arr[$key];
		$sql="update Gn_MMS_Number set ";
		$i=0;
		foreach($mms_number_info as $key2=>$v2)
		{
			$bd=$i==count($mms_number_info)-1?"":",";
			$sql.=" $key2='$v2' $bd ";
			$i++;
		}
		$sql.=" where sendnum='$v' and mem_id='$_SESSION[one_member_id]' ";
		mysqli_query($self_con,$sql)or die(mysqli_error($self_con));
	}
	?>
    <script language="javascript">
	alert('저장되었습니다.');
	location.reload();
	</script>
    <?	
}
//로그기록 삭제
if($_POST[log_del_ids])
{
	 $ids=$_POST[log_del_ids];
	 $sql="delete from sm_log where seq in($ids) ";
	 if(mysqli_query($self_con,$sql))
	 {
	 ?>
     	<script language="javascript">

	// 	alert('잠시 비활성화 처리함.');
	 	alert('처리되었습니다.');
	 	location.reload();
	 	</script>
     <?		
	 }

}
//로그기록등록수정
if($_POST[log_add_dest] && $_POST[log_add_ori])
{
	$dest=preg_replace("/[^0-9]/i", "", $_POST[log_add_dest]);
	$is_zero=substr($dest,0,1);
	$dest=$is_zero?"0".$dest:$dest;
	
	$ori_num=preg_replace("/[^0-9]/i", "", $_POST[log_add_ori]);
	$is_zero=substr($ori_num,0,1);
	$ori_num=$is_zero?"0".$ori_num:$ori_num;

	if(!check_cellno($dest))
		{
			?>
				<script language="javascript">
				alert('정확한 번호가 아닙니다.[발신번호error]');
				</script>
			<?
			exit;	
		}
		if(!check_cellno($ori_num))
		{
			?>
				<script language="javascript">
				alert('정확한 번호가 아닙니다.[수신번호error]');
				</script>
			<?
			exit;	
		}			
	
		$sql_num="select sendnum from Gn_MMS_Number where mem_id ='$_SESSION[one_member_id]' and sendnum='$dest' ";
		$resul_num=mysqli_query($self_con,$sql_num);
		$row_num=mysqli_fetch_array($resul_num);
		if(!$row_num[sendnum])
		{
			?>
				<script language="javascript">
				alert('발신번호는 등록된 번호가 아닙니다.');
				</script>
			<?
			exit;			
		}
		
		$sql_s="select seq from sm_log where dest='$dest' and ori_num='$ori_num' and msg_flag='$_POST[log_add_msg_flag]' ";
		$resul_s=mysqli_query($self_con,$sql_s);
		$row_s=mysqli_fetch_array($resul_s);
		if($row_s[seq])
		{
			?>
				<script language="javascript">
				alert('현재 수신번호는 이미 등록되어있습니다.');
				</script>
			<?
			exit;
		}		
	$log_info[dest]=$dest;	
	$log_info[ori_num]=$ori_num;
	$log_info[msg_flag]=$_POST[log_add_msg_flag];
	$log_info[msg_text]="수동입력";	
	$log_info[mem_id]=$_SESSION[one_member_id];
	if($_POST[log_add_idx])
	$sql="update sm_log set ";
	else
	$sql="insert into sm_log set ";	
	$i=0;
	foreach($log_info as $key=>$v)
	{
		$bd=$i==count($log_info)-1?"":",";
		$sql.=" $key='$v' $bd ";
		$i++;
	}
	if($_POST[log_add_idx])
		$sql.=" where seq='$_POST[log_add_idx]' ";
	else
	$sql.=" , reservation_time=now() ";
	if(mysqli_query($self_con,$sql) or die(mysqli_error($self_con)))
	{
	?>
    	<script language="javascript">
		alert('처리되었습니다.');
		location.reload();
		</script>
    <?	
	}
}
//고객센터 글등록
if($_POST[board_save_title] && ($_POST[board_save_content] || $_POST[board_save_reply]))
{
	$board_info[title]=htmlspecialchars($_POST[board_save_title]);
	if($_POST[board_save_content])
	    $board_info[content]=htmlspecialchars($_POST[board_save_content]);
	if($_POST[board_save_reply])
	    $board_info[reply]=htmlspecialchars($_POST[board_save_reply]);
	$board_info[phone]=$_POST[board_save_phone];
	$board_info[fl]=$_POST[board_save_fl];
	$board_info[email]=$_POST[board_save_email];
	$board_info[adjunct_1]=$_POST[board_save_adjunct_1];
	$board_info[adjunct_2]=$_POST[board_save_adjunct_2];
	$board_info[adjunct_memo]=$_POST[board_save_adjunct_memo];
	$board_info[status_1]=$_POST[board_save_status_1];
	$board_info[up_path]=$_POST[board_save_up_path];
	$board_info[pop_yn]=$_POST[board_save_pop_yn];
	$board_info[important_yn]=$_POST[board_save_important_yn];
	$board_info[display_yn]=$_POST[board_save_display_yn];
	$board_info[start_date]=$_POST[board_save_start_date];
	$board_info[end_date]=$_POST[board_save_end_date];
	
	if($_POST[board_save_no])
	$sql="update tjd_sellerboard set ";
	else
	{
		$board_info[id]=$_SESSION[one_member_id];
		$board_info[category]=$_POST[board_save_category];	
		$sql="insert into tjd_sellerboard set";	
	}
	$i=0;
	foreach($board_info as $key=>$v)
	{
		$bd=$i==count($board_info)-1?"":",";
		$sql.=" $key='$v' $bd ";
		$i++;
	}
	if($_POST[board_save_no])
	$sql.=" where no='$_POST[board_save_no]' ";
	else
	$sql.=" , date=now() ";
	if(mysqli_query($self_con,$sql) or die(mysqli_error($self_con)))
	{
	?>
    	<script language="javascript">
		alert('처리되었습니다.');
		<?if($_POST['return_url'] !="") {?>
			location.href='<?=$_POST['return_url'];?>';
		<?}?>
    	<?if(strstr($_SERVER['HTTP_REFERER'], "faq")) {?>
			location.href='faq_list.php';
		<? } else if(strstr($_SERVER['HTTP_REFERER'], "notice_db")) {?>
			location.href='notice_db_list.php';
		<? } else if(strstr($_SERVER['HTTP_REFERER'], "notice_")) {?>
			<?php if($_REQUEST['return_url'] !="") {?>
				location.href='<?=$_REQUEST['return_url'];?>';
	    	<?}else {?>
				location.href='notice_list.php';
	    	<? }?>
		<? } else if(strstr($_SERVER['HTTP_REFERER'], "qna")) {?>
			location.href='qna_list.php';
	    <? } else {?>
			location.href='cliente_list.php?status=<?=$_POST[board_save_category]?>';
	    <? }?>
		</script>
    <?		
	}
}
//게시판 삭제
if($_POST[board_del_ids])
{
	$nos=$_POST[board_del_ids];
	$sql="delete from tjd_sellerboard where no = '$nos' ";
	mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
	?>
    	<script language="javascript">
		alert('처리되었습니다.' + '<?=$sql?>');
		//location.href='cliente_list.php?status=<?=$_POST[board_del_status]?>'
		location.reload();
		</script>
    <?		
}
/*
//번호변경 덮어쓰기
if($_POST[fugai_num_status])
{
	$sql_serch=" where chg_num<>'' ";
	if($_POST[fugai_num_status]=="cho")
	{
		$sql_serch.=" and seq in ($_POST[fugai_num_ids])  ";
	}
	else if($_POST[fugai_num_status]=="all")
	{
		$sql_num="select sendnum from Gn_MMS_Number where mem_id ='$_SESSION[one_member_id]'";
		$resul_num=mysqli_query($self_con,$sql_num);
		$send_num_arr=array();
		while($row_num=mysqli_fetch_array($resul_num))
		{
				array_push($send_num_arr,"'".$row_num[sendnum]."'");
		}
		$send_num_str=implode(",",$send_num_arr);
		$sql_serch.=" and dest in($send_num_str) ";		
	}	
				
	$sql="update sm_log set ori_num=chg_num ,chg_num='' $sql_serch ";
	if(mysqli_query($self_con,$sql))
	{
	?>
	<script language="javascript">
    alert('처리되었습니다.');
    location.reload();
    </script>
    <?
	}
}
*/
// 번호 삭제
// Cooper 2018.08.
if($_POST[delete_num_ids]) 
{
 
		$sql_serch.=" and seq in ($_POST[delete_num_ids])  ";
		
    	$query = "select ori_num, chg_num from sm_log where 1=1 $sql_serch";
    	$resul_num=mysqli_query($self_con,$query);
    	while($row_num=mysqli_fetch_array($resul_num)) {
    	    $ori_num = $row_num[ori_num];
    		$sql = "select * from Gn_MMS_Receive where recv_num='$ori_num' and mem_id = '{$_SESSION[one_member_id]}'";
    		$result1 = mysqli_query($self_con,$sql);   
    		$row1=mysqli_fetch_array($result1);

            //삭제    		
    		$sql = "delete from Gn_MMS_Receive where recv_num='$ori_num' and mem_id = '{$_SESSION[one_member_id]}'";
    		$results = mysqli_query($self_con,$sql);            
    		//
    		//// 갯수 업데이트
    		if($row1[0] != "") {
    		    $sql = "update Gn_MMS_Group set count=count-1 where grp='$row1[grp]' and  mem_id = '{$_SESSION[one_member_id]}'";
    		    mysqli_query($self_con,$sql);            
    		}
    		
    		$sql = "delete from sm_log where $sql_serch";
    		mysqli_query($self_con,$sql);            
    		
    	
    	}
    	
    	
    	// Cooper add 번호 변경 로그
    	exit;
    	?>
        	<script language="javascript">
            alert('처리되었습니다.');
            location.reload();
            </script>
        <?	
        
	exit;
}
//번호변경 덮어쓰기
//변경 Cooper 2016.02.
if($_POST[fugai_num_status])
{
	$sql_serch=" where chg_num<>'' ";
	if($_POST[fugai_num_status]=="cho")
	{
		$sql_serch.=" and seq in ($_POST[fugai_num_ids])  ";
		
    	$query = "select ori_num, chg_num from sm_log $sql_serch";
    	$resul_num=mysqli_query($self_con,$query);
    	$row_num=mysqli_fetch_array($resul_num);
    	
    	
    	// Cooper add 번호 변경 로그
    	if(cell_change_log ($row_num[chg_num], $row_num[ori_num]) === true) {
    	?>
        	<script language="javascript">
            alert('처리되었습니다.');
            location.reload();
            </script>
        <?	    
    	} else {
    	?>
        	<script language="javascript">
            //alert('이미 처리된 정보가 있습니다.'); COO
            alert('처리 되었습니다.');
            location.reload();
            </script>
        <?	    
    	}		
	}
	else if($_POST[fugai_num_status]=="all")
	{
		$sql_num="select sendnum from Gn_MMS_Number where mem_id ='$_SESSION[one_member_id]'";
		$resul_num=mysqli_query($self_con,$sql_num);
		$send_num_arr=array();
		while($row_num=mysqli_fetch_array($resul_num))
		{
				array_push($send_num_arr,"'".$row_num[sendnum]."'");
		}
		$send_num_str=implode(",",$send_num_arr);
		$sql_serch.=" and dest in($send_num_str) ";		
		
		
		$cnt = 0;
    	$query = "select ori_num, chg_num from sm_log where mem_id ='$_SESSION[one_member_id]' and chg_status='N' order by reservation_time asc";
    	$resul_num=mysqli_query($self_con,$query);
    	while($row_num=mysqli_fetch_array($result_num)) {
        	// Cooper add 번호 변경 로그
        	if(cell_change_log ($row_num[chg_num], $row_num[ori_num]) === true) {
        	    // 저장
        	    $cnt ++;
        	}				
        }
        
    	// Cooper add 번호 변경 로그
    	if($cnt > 0) {
    	?>
        	<script language="javascript">
            alert('처리되었습니다.');
            location.reload();
            </script>
        <?	    
    	} else {
    	?>
        	<script language="javascript">
            //alert('처리된 새로운 정보가 없습니다.'); COO?
            alert('처리되었습니다.');
            location.reload();
            </script>
        <?	    
    	}	        
	}	
	exit;
				
	//$sql="update sm_log set ori_num=chg_num ,chg_num='' $sql_serch ";
	//if(mysqli_query($self_con,$sql))
	//{
	//}
}
//결제하기
if($_POST[pay_go_mid] && $_POST[pay_go_goodname])
{
	require_once('../inipay/libs/INIStdPayUtil.php');
	$SignatureUtil = new INIStdPayUtil();
	$mid=$_POST[pay_go_mid];
	$signKey=$sign_arr[$mid];
	$timestamp = $SignatureUtil->getTimestamp();
	$price =$_POST[pay_go_total_price];
	$orderNumber=$member_1[mem_code]."_".date("ymdhis");
	$_SESSION[total_price]=$price;
	$_SESSION[phone_cnt]=$_POST[pay_go_add_phone];
	$_SESSION[month_cnt]=$_POST[pay_go_month_cnt];
	$_SESSION[fujia_status]=$_POST[pay_go_fujia_status];
	$_SESSION[orderNumber]=$orderNumber;	
	$cardNoInterestQuota = "11-2:3:,34-5:12,14-6:12:24,12-12:36,06-9:12,01-3:4";  // 카드 무이자 여부 설정(가맹점에서 직접 설정)
	$cardQuotaBase = "2:3:4:5:6:11:12:24:36";  // 가맹점에서 사용할 할부 개월수 설정
	$mKey = $SignatureUtil->makeHash($signKey, "sha256");
	$params = array(
	"oid" => $orderNumber,
	"price" => $price,
	"timestamp" => $timestamp
	);
	$sign = $SignatureUtil->makeSignature($params, "sha256");
	$siteDomain = "http://kiam.kr";
	?>
    <form id="SendPayForm_id" name="" action="" method="post">
        <input type="hidden" name="version" value="1.0"  />
        <input type="hidden" name="mid" value="<?=$mid?>"  />
        <input type="hidden" name="goodsname" value="<?=$_POST[pay_go_goodname]?>"  />
        <input type="hidden" name="oid" value="<?=$orderNumber?>"  />
        <input type="hidden" name="price" value="<?=$price?>"  />
        <input type="hidden" name="currency" value="WON"  />
        <input type="hidden" name="buyername" value="<?=$member_1[mem_name]?>"  />
        <input type="hidden" name="buyertel" value="<?=$member_1[mem_phone]?>"  />
        <input type="hidden" name="buyeremail" value="<?=$member_1[mem_email]?>"  />
        <input type="hidden" name="timestamp" value="<?=$timestamp ?>"  />
        <input type="hidden" name="signature" value="<?=$sign?>"  />
        <input type="hidden" name="returnUrl" value="<?=$siteDomain?>/pay_return.php"  /><!--pay_return.php inipay/INIStdPaySample/INIStdPayReturn.php-->
        <input type="hidden"  name="mKey" value="<?=$mKey?>"  />
        <input type="hidden" name="gopaymethod" value="Card"  /><!--카드- Card--><!--실시간계좌이체 - DirectBank--><!--VBank -무통장입금-->
        <input type="hidden" name="offerPeriod" value=""  />
        <input type="hidden" name="acceptmethod" value="HPP(1):no_receipt:va_receipt:vbanknoreg(0):vbank(20150611):below1000"  />
        <input type="hidden" name="languageView" value=""  />
        <input type="hidden" name="charset" value=""  />
        <input type="hidden" name="payViewType" value=""  />
        <input type="hidden" name="closeUrl" value="<?=$siteDomain ?>/pay_close.php" />
        <input type="hidden" name="popupUrl" value="<?=$siteDomain ?>/pay_popup.php" />
        <input type="hidden" name="nointerest" value="<?=$cardNoInterestQuota ?>" />
        <input type="hidden" name="quotabase" value="<?=$cardQuotaBase ?>"  />
        <input type="hidden" name="vbankRegNo" value="" >
        <input  type="hidden" name="merchantData" value="" >    
    </form>  
    <script language="javascript">
    INIStdPay.pay('SendPayForm_id');
    </script>
    <?
}
//결제해제
if($_POST[pay_cancel_no] && $_POST[pay_cancel_paymethod])
{
	switch($_POST[pay_cancel_paymethod])
	{
		case "Card":
			require($_SERVER['DOCUMENT_ROOT']."/inipay/libs/INILib.php");
			$inipay = new INIpay50;
			$inipay->SetField("inipayhome", $_SERVER['DOCUMENT_ROOT']."/inipay"); // 이니페이 홈디렉터리(상점수정 필요)
			$inipay->SetField("type", "cancel");                            // 고정 (절대 수정 불가)
			$inipay->SetField("debug", "true");                             // 로그모드("true"로 설정하면 상세로그가 생성됨.)
			$inipay->SetField("mid", $_POST[pay_cancel_mid]);               // 상점아이디

			$inipay->SetField("admin", "1111");                            
			$inipay->SetField("tid", $_POST[pay_cancel_tid]);                             // 취소할 거래의 거래아이디
			$inipay->SetField("cancelmsg", "고객직접지원요청");                           // 취소사유
			$inipay->startAction();
			if($inipay->getResult('ResultCode')=="00")
			{
				if($_POST[pay_cancel_fujia]=='Y')
				{
					$sql_m="update Gn_Member set fujia_date1='' , fujia_date2='' where mem_id='$member_1[mem_id]' ";
					mysqli_query($self_con,$sql_m)or die(mysqli_error($self_con));	
				}				
				$pay_info[end_status]="C";				
				$pay_info[cancel_ResultCode]=$inipay->GetResult('ResultCode');//취소코드
				$pay_info[cancel_ResultMsg]=iconv("euc-kr","utf-8",$inipay->GetResult('ResultMsg'));//취소메시지
				$pay_info[cancel_CancelDate]=$inipay->GetResult('CancelDate');//취소일
				$pay_info[cancel_CancelTime]=$inipay->GetResult('CancelTime');//취소시각
				$pay_info[cancel_CSHR_CancelNum]=$inipay->GetResult('CSHR_CancelNum');//현금영수증 취소 승인번호(현금영수증 발급 취소시에만 리턴됨)
				$pay_info[cancel_status]="Y";
				$sql="update tjd_pay_result set ";
				foreach($pay_info as $key=>$v)
				{
					$sql.=" $key = '$v' , ";
				}
				$sql.=" end_date=now() where no='$_POST[pay_cancel_no]' ";
				if(mysqli_query($self_con,$sql) or die(mysqli_error($self_con)))
				{
					$sql_num_up="update Gn_MMS_Number set end_status='N' ,end_date=now() where end_date='$_POST[pay_cancel_end_date]' and mem_id='$member_1[mem_id]'  ";
					mysqli_query($self_con,$sql_num_up);					
					?>
					<script language="javascript">
					alert('<?=iconv("euc-kr","utf-8",$inipay->GetResult('ResultMsg'))?>');
					location.reload();
					</script>
                    <?
				}

			}
			else
			{
					?>
					<script language="javascript">
					alert('<?=iconv("euc-kr","utf-8",$inipay->GetResult('ResultMsg'))?>');
					location.reload();
					</script>
					<?			
			}
		break;
		case "Auto_Card":
			require($_SERVER['DOCUMENT_ROOT']."/INIbill41/sample/INIpay41Lib.php");
			$inipay = new INIpay41;
			$inipay->m_inipayHome = $_SERVER['DOCUMENT_ROOT']."/INIbill41"; // 이니페이 홈디렉터리
			$inipay->m_type = "cancel"; // 고정
			$inipay->m_subPgIp = "203.238.3.10"; // 고정

			$inipay->m_keyPw = "1111"; // 키패스워드(상점아이디에 따라 변경)
			$inipay->m_debug = "true"; // 로그모드("true"로 설정하면 상세로그가 생성됨.)
			$inipay->m_mid = $_POST[pay_cancel_mid]; // 상점아이디
			$inipay->m_tid = $_POST[pay_cancel_tid]; // 취소할 거래의 거래아이디
			$inipay->m_cancelMsg = "고객직접지원요청"; // 취소사유
			$inipay->startAction();
			if($inipay->m_resultCode=="00")
			{
				if($_POST[pay_cancel_fujia]=='Y')
				{
					$sql_m="update Gn_Member set fujia_date1='' , fujia_date2='' where mem_id='$member_1[mem_id]' ";
					mysqli_query($self_con,$sql_m)or die(mysqli_error($self_con));	
				}				
				$pay_info[end_status]="C";				
				$pay_info[cancel_ResultCode]=$inipay->m_resultCode;//취소코드
				$pay_info[cancel_ResultMsg]=iconv("euc-kr","utf-8",$inipay->m_resultMsg);//취소메시지
				$pay_info[cancel_CancelDate]=$inipay->m_pgCancelDate;//취소일
				$pay_info[cancel_CancelTime]=$inipay->m_pgCancelTime;//취소시각
				$pay_info[cancel_CSHR_CancelNum]=$inipay->m_rcash_cancel_noappl;//현금영수증 취소 승인번호(현금영수증 발급 취소시에만 리턴됨)
				$pay_info[cancel_status]="Y";				
				$sql="update tjd_pay_result set ";
				foreach($pay_info as $key=>$v)
				{
					$sql.=" $key = '$v' , ";
				}
				$sql.=" end_date=now() where no='$_POST[pay_cancel_no]' ";
				if(mysqli_query($self_con,$sql) or die(mysqli_error($self_con)))
				{
					$sql_num_up="update Gn_MMS_Number set end_status='N' ,end_date=now() where end_date='$_POST[pay_cancel_end_date]' and mem_id='$member_1[mem_id]'  ";
					mysqli_query($self_con,$sql_num_up);					
					?>
					<script language="javascript">
					alert('<?=iconv("euc-kr","utf-8",$inipay->m_resultMsg)?>');
					location.reload();
					</script>
                    <?
				}

			}
			else
			{
					?>
					<script language="javascript">
					alert('<?=iconv("euc-kr","utf-8",$inipay->m_resultMsg)?>');
					location.reload();
					</script>
					<?			
			}			
		break;		
	}
}
?>