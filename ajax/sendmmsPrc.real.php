<?php
include_once "../lib/rlatjd_fun.php";
//define("GOOGLE_SERVER_KEY", "AAAA2MWeAvc:APA91bGFL-_Ja4LP2B7H9UrciLYK_VlUFYKsREiEaMo4iH1ZyWILxUkczb5Vsvn0Oo71ra2izOUvN9RqKZEWeM1ldZ6_Ck0JgP8eM5VKxaVFcUk_P3EUgWtIoBkXOmaDgPrOI61O-1ZxV0t-ReZCwnq--fOYYhs80Q");
define("GOOGLE_SERVER_KEY", "AAAAmvl-uQA:APA91bHP4S4L8-nMvfOJ9vcjYlTmiRjEfOcLbAm6ITDFo9Ky-ziKAowlZi0rWhO3c7jsZ50unqWabQCBAmtr9bOxUIbwyAMgRsxO1jeLKlJ9l_Gir_wc1sZ66VBtHVBSjeAZcRfffVwo7M2fBvrrt1d5vz5clf7PVQ");

//$debug_mode = true;
$debug_mode = false;



if($_SESSION['one_member_id']){
    
    $url = 'https://fcm.googleapis.com/fcm/send';

    $headers = array (
        'Authorization: key=' . GOOGLE_SERVER_KEY,
        'Content-Type: application/json'
    );

	//문자발송
	if($_POST[send_title] && $_POST[send_txt])
	{
	    if($_POST[send_rday]) //예약발송 확인
	        $reservation = $_POST[send_rday]." ".$_POST[send_htime].":".$_POST[send_mtime].":00";
	        $now_date = date("Y-m-d H:i:s");
	        if($_POST[send_rday])
	        {
	            if($now_date  > $reservation) // 현재 > 예약 시각
	            {
	                ?>
				<script language="javascript">
				var ans = confirm('지금보다 예약하기 이전시간입니다. 즉시발송하겠습니까?');
				if (ans == false)
				{
				<? 
				exit;
				?> 
				}
				</script>
				<?
				}
			}
			
            
			
			// [기본서비스] 시작
			
				$reg = time();
				$num_arr=array(); 
				$success_arr=array(); 
				$group_recv_info = array();
				$success_cell_arr = array(); //발송배정 핸드폰
				if($_POST[send_chk]) //체크된 연락처 그룹 : $_POST[send_chk] : tag name = group_num
				{
					$send_chk_arr=explode(",",$_POST[send_chk]);
					//번호들 담기 : $num_arr 
					foreach($send_chk_arr as $key=>$v)
					{
						$sql_g2 = "select recv_num,grp from Gn_MMS_Receive where grp_id = '$v' order by idx asc";
						$resul_g2 = mysqli_query($self_con,$sql_g2);
						while($row_g2=mysqli_fetch_array($resul_g2)) {
    						array_push($num_arr,$row_g2['recv_num']);
    						
    						/* Cooper Add 그룹 구분 추가 */
    						if (array_key_exists($row_g2['recv_num'], $group_recv_info) === false) {
    						    $group_recv_info[$row_g2['recv_num']] = $row_g2['grp'];
    						}						
					    }
					}
				}
				if($_POST['send_num']) //합침/유일/정렬 tag name = num
				$num_arr=array_merge($num_arr,explode(",",$_POST['send_num']));
				$num_arr=array_unique($num_arr);
				
				sort($num_arr);
				$no_num=array(); //없는 번호
				$start_num=array(); 
				$deny_num=array(); //수신거부 번호
				$etc_arr=array(); //기타번호
				$wrong_arr=array();
				$lose_arr=array();
				$send_num_list = array(); // 번호별 수신된 번호 저장 // 2016-04-26

				$cnt1_log_arr=array(); //cnt1_변동 저장 // 2016-03-07 추가
				$cnt2_log_arr=array(); //cnt2_변동 저장
				$cntYN_log_arr=array(); //200이상으로 횟수 조절 저장
				$cntAdj_log_arr=array(); //발송 가능 수 조절 저장
				
				$sendnum=$_POST[send_go_num];			
				$send_cnt=$_POST['send_go_user_cnt'];
				$max_cnt_arr=$_POST[send_go_max_cnt];
				$memo2_arr=$_POST['send_go_memo2'];
				$cnt1_arr=$_POST[send_go_cnt1]; 
				$cnt2_arr=$_POST[send_go_cnt2];
				
				$total_send_thistime = 0; //이번 발송할 총합
				$total_num_cnt = count($num_arr);

				if(!count($sendnum))
				{
				?>
				<script language="javascript">
					alert('발송가능한 휴대폰이 없습니다.')		
				</script>
				<?
				exit;
				}

				$date_today=date("Y-m-d");
				$date_month=date("Y-m");
				if($reservation) {
    				$date_today=substr($reservation,0,10);
    				$date_month=substr($reservation,0,7);				    
				}

				$sql = "select phone_cnt from tjd_pay_result where buyer_id = '{$_SESSION['one_member_id']}' and end_date > '$date_today' and end_status in ('Y','A') order by end_date desc limit 1";
				$res_result = mysqli_query($self_con,$sql);
				//결제 휴대폰 수
				$buyPhoneCnt = mysqli_fetch_row($res_result);

				mysqli_free_result($res_result);
				if($buyPhoneCnt[0] == 0){	//유료결제건

					$buyMMSCount = 0;

				}else{

					//$buyMMSCount = ($buyPhoneCnt[0] -1) * 9000;
					$buyMMSCount = $buyPhoneCnt[0];

				}

				//무료제공건
				$freeMMSCount = 100;
				
				$ad_msg = "";
				
				if($_POST['free_use'] == "Y") {
                    $sql_ad = "select 
                                          `gaid`, `client`, `start_date`, `send_start_date`, `send_end_date`, `send_count`, `title`, `content`, `img_path`, `sort_order` 
                                     from Gn_Ad 
                                    where status='C' 
                                 order by sort_order asc, regdate desc";
				    $res_ad = mysqli_query($self_con,$sql_ad);
				    $row_ad = mysqli_fetch_array($res_ad);
				    $ad_msg = $row_ad['content'];
				    if($row_ad[img_path] == "") {
				        $ad_msg .= $domain_url.$row_ad['img_path'];
				    }else {
				        //$ad_msg .= $domain_url;
				        $img = "http://www.kiam.kr/".$row_ad['img_path'];
				    }
				}				

				//-이번달 예약건 수
				$reserv_cnt_thismonth=0;
				$sql_result = "select recv_num from Gn_MMS where reservation like '$date_month%' and up_date is null and mem_id = '{$_SESSION['one_member_id']}' ";
				$res_result = mysqli_query($self_con,$sql_result);
				while($row_result = mysqli_fetch_array($res_result))
				$reserv_cnt_thismonth += count(explode(",",$row_result['recv_num']));

				mysqli_free_result($res_result);
					
				//-이번달 발송된 수
				$recv_num_ex_sum=0;
				$sql_result = "select recv_num from Gn_MMS where reg_date like '$date_month%' and mem_id = '{$_SESSION['one_member_id']}' ";
				$res_result = mysqli_query($self_con,$sql_result);
				while($row_result = mysqli_fetch_array($res_result))
				$recv_num_ex_sum += count(explode(",",$row_result['recv_num']));

				mysqli_free_result($res_result);

				$recv_num_ex_sum +=$reserv_cnt_thismonth; //이번 달 예약된 수 추가

				//이달잔여건
				$thiMonleftCnt = $freeMMSCount + $buyMMSCount - $recv_num_ex_sum;
				//echo count($num_arr)." == $thiMonleftCnt = $freeMMSCount + $buyMMSCount - $recv_num_ex_sum";

				if(count($num_arr) > $thiMonleftCnt)
				{
				?>
				<script language="javascript">
					alert('이번 달 발송 가능 건수를 초과하였습니다.');
				</script>
				<?
				exit;
				}
				$user_cnt = array(); // 사용자 잔여건수

					$num_arr_1=array();
					for($i=0; $i<count($num_arr); $i++)
					{
						$is_zero=substr($num_arr[$i],0,1);
						$recv_arr[$i]=$is_zero?"0".$num_arr[$i]:$num_arr[$i];
					    $recv_arr[$i] = preg_replace("/[^0-9]/i", "", $recv_arr[$i]); 

						if(!check_cellno($recv_arr[$i]))

						{//기타 번호(폰번호아님) 모으기: $_POST['send_deny_wushi_2']
							array_push($etc_arr,$num_arr[$i]);
							if($_POST['send_deny_wushi_2'])
							continue;
						}			
						$num_arr[$i]=preg_replace("/[^0-9]/i","",$num_arr[$i]);					
	
						$sql_deny = "select idx from Gn_MMS_Deny where recv_num = '$num_arr[$i]' and mem_id = '{$_SESSION['one_member_id']}'";//수신거부
						$resul_deny=mysqli_query($self_con,$sql_deny)or die(mysqli_error($self_con));
						$row_deny=mysqli_fetch_array($resul_deny);
						if($row_deny['idx'])
						{//수신 거부 번호 모으기 : $_POST['send_deny_wushi_3']
							array_push($deny_num,$num_arr[$i]);
							if($_POST['send_deny_wushi_3'])
							continue;	
						}
						$sql_etc="select seq,msg_flag from sm_log where mem_id = '{$_SESSION['one_member_id']}' and ori_num='$num_arr[$i]' order by seq desc limit 0,1";
						$resul_etc=mysqli_query($self_con,$sql_etc);
						$row_etc=mysqli_fetch_array($resul_etc);
						if($row_etc['seq'])
						{
							if($row_etc['msg_flag']==1)
							{//기타 번호 모으기 : $_POST['send_deny_wushi_2']
								array_push($etc_arr,$num_arr[$i]);
								if($_POST['send_deny_wushi_2'])
								continue;
							}
							//else if($row_etc['msg_flag']==2)
							//{//없는 번호 모으기 : $_POST['send_deny_wushi_1']
							//	array_push($wrong_arr,$num_arr[$i]);
							//	if($_POST['send_deny_wushi_1'])
							//	continue;	
							//}
							else if($row_etc['msg_flag']==3)
							{//수신불가 번호 모으기 : $_POST['send_deny_wushi_0']
								array_push($lose_arr,$num_arr[$i]);
								if($_POST['send_deny_wushi_0'])						
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
					$today_reg=date("Y-m-d");

			
                    //$re_today_cnt = 0;
					for($j=0;$j<count($sendnum);$j++) //발송가능 폰번호별
					{
					    
                			// Cooper Add Super User인지 체크  
                			/*
                			가입한 소유자(후보자)폰만 통신사별 예외발송 기능
                 
                            월 수신처 :  무제한
                            1 일 건별 :
                                   LGT 1일 무제한  
                                   KT   1일 2000건 
                                   SKT 1일 3000건   
                            */
                            $superChk = false;
                            $query = "select * from Gn_Member where mem_id='{$_SESSION['one_member_id']}'";
                            $result = mysqli_query($self_con,$query);
                            $member_info = mysqli_fetch_array($result);
                            
                            $query = "select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' and sendnum='".str_replace("-", "", $sendnum[$j])."'";
                            $result = mysqli_query($self_con,$query);
                            $info = mysqli_fetch_array($result);
                            if($info['memo2'] != "") {
                                $telecom = $info['memo2'];
                            }			
                            
                            $daily_limit_cnt_user = $info['daily_limit_cnt_user'];
                            $daily_min_cnt_user= $info['daily_min_cnt_user'];
                            $monthly_receive_cnt_user= $info['monthly_receive_cnt_user'];
                            
                            
                            
							$memo2 = $info['memo2'];
							//$limitCnt = $memo2 ? $agency_arr[$memo2] : 800; //월별 수신처 제한 수
							$limitCnt = $monthly_receive_cnt_user;//월별 수신처 제한 수                            
							//$daily_cnt = $limitCnt * 0.01 * $info['donation_rate']; // 기부비율에 맞춰 수정
							$daily_cnt = $daily_limit_cnt_user;
                            
                            if($sendnum[$j] == str_replace("-", "", $member_info['mem_phone'])) {
                                if($member_info['mem_type'] == "V" || $member_info['mem_type'] == "") {
                                    if($telecom == "SK") {
                                        // SKT
                                        $superChk = true;
                                        
                                        //$limitCnt = 2000;
                                        $limitCnt = 60000;
                                        $daily_cnt = $limitCnt * 0.01 * $info['donation_rate']; // 기부비율에 맞춰 수정
                                    } else if($telecom == "KT") {
                                        // KT
                                        $superChk = true;
                                        //$limitCnt = 2000;
                                        $limitCnt = 60000;
                                        $daily_cnt = $limitCnt * 0.01 * $info['donation_rate']; // 기부비율에 맞춰 수정
                                    } else if($telecom == "LG") {
                                        // LG
                                        $superChk = true;
                                        //$limitCnt = 3000; // 무제한
                                        $limitCnt = 90000;
                                        $daily_cnt = $limitCnt * 0.01 * $info['donation_rate']; // 기부비율에 맞춰 수정
                                    }
                                }
                            }
        						    
							$ssh_num=array(); //$ssh_num <= 중복없는 수신번호들
//							$sql_ssh="select recv_num from Gn_MMS where mem_id = '{$_SESSION['one_member_id']}' and send_num='$sendnum[$j]' and result = '0' and reg_date like '$date_month%'";
							$sql_ssh="select recv_num from Gn_MMS where mem_id = '{$_SESSION['one_member_id']}' and send_num='$sendnum[$j]' and result = '0' and reg_date like '$date_month%'";
							$resul_ssh=mysqli_query($self_con,$sql_ssh);
							if(mysqli_num_rows($resul_ssh))
							{
								while($row_ssh=mysqli_fetch_array($resul_ssh))
								{
									$ssh_arr=array();
									$ssh_arr=explode(",",$row_ssh['recv_num']);
									$ssh_num=array_merge($ssh_num,(array)$ssh_arr);	 
								}
								//echo count($ssh_num)."\n";
								
								unset($ssh_arr);
								$ssh_numarray_unique=($ssh_num); //$ssh_num <= 중복없는 수신번호들	
								$send_num_list[$sendnum[$j]] = array_intersect((array)$ssh_num, (array)$num_arr_2);
								//$num_arr_2 = array
							}

							$used_ssh_cnt = count(array_unique($ssh_num)); //사용된 수신처 수 //2016-03-10 추가
							
							// 총 수신처 갯수 확인
							//if($limitCnt - $used_ssh_cnt >0)
							//    $ssh_num_true[$j] = $limitCnt - $used_ssh_cnt;
							//else 
							//    $ssh_num_true[$j] = 0;
							//echo "\n=>".$used_ssh_cnt ."\n";

							//새로 발송 가능 수신처 , $agency_arr는 rlatjd_fun.php에서 정의 
							$ssh_num_true[$j]=$monthly_receive_cnt_user-$used_ssh_cnt; //2016-03-10 수정
							
							//echo "$ssh_num_true[$j]\n";
							
							
        					// Cooper Add 총 발송건수 체크 (선거 본인용) 수신처 무제한 변경 2016-04-04 
        					if($sendnum[$j] == str_replace("-", "", $member_info['mem_phone']) && $member_info['mem_type'] == "V") {
        					    if($telecom == "LG") 
        					        $ssh_num_true[$j] = 90000;
        					    else
        					        $ssh_num_true[$j] = 60000;
        					}								

							$ssh_total_num=array_merge($ssh_total_num,$ssh_num); //총 수신처 누적						
							
							$user_cnt[$sendnum[$j]] = ($info['max_cnt'] - $info['user_cnt']) * 1 >2?($info['max_cnt'] - $info['user_cnt']) * 1:0; // 금일 발송 건수
							

							unset($ssh_num);
							
							
							
							// 폰별 수신처별 번호 배정 추가 2016-05-02
					}
					//echo count(array_unique($num_arr));

    				$s=0; //전체 발송 번호 건수 중 수신거부 링크 추가한 인덱스로 사용
    				$re_today_cnt=0; //금일 전송 성공
    				$ssh_total_cnt=0; //전송실패건수(수신처제한)
    
    				//변수 선언 2016-03-10 추가
    				//$today_limit = 500 - 4; //기부 받은 일일 최대 발송량(500 : 대량문자 100% 기부) 4여우
    				//$grade_limit = 200 - 2; //1구간 최대 발송량(200) 2여유
    				$today_limit = 500; //기부 받은 일일 최대 발송량(500 : 대량문자 100% 기부) 4여우
    				$grade_limit = 199; //1구간 최대 발송량(200) 2여유				
    				
    				$remain_count = $today_will_send_count;											
					/*
					 2) $ssh_total_num : 총 수신처 누적에 $num_arr 요소가 
                        있으면 $num_arr_2 추가
                        없으면 $num_arr_3 추가
					*/
					
					if($_POST['send_ssh_check'] || $_POST['send_ssh_check2'] || $_POST['send_ssh_check3'])
					{
						foreach($num_arr as $key=>$v)
						{
							if(in_array_fun($v,$ssh_total_num))
							array_push($num_arr_2,$v); //기존수신처
							else
							array_push($num_arr_3,$v); //새 수신처				
						}
						//echo "기존수신처:".count($num_arr_2)."\n";
						//echo "새수신처:".count($num_arr_3)."\n";
						
						unset($ssh_total_num);

						if($_POST['send_ssh_check']){ //수신처 우선
							$num_arr=array_merge($num_arr_2,$num_arr_3);
							//$num_arr=array_merge($num_arr_3,$num_arr_2);  // Cooper 2016-04-26 순서 변경
							//$ssh_num_true[$j] = count($num_arr_2);
							$recv_exp_cnt = count($num_arr_2);
						}
						if($_POST['send_ssh_check2']){ //수신처 제외
							$num_arr=$num_arr_3;
						}
						if($_POST['send_ssh_check3']){ //수신처 전용
							$num_arr=$num_arr_2;
							$ssh_num_true[$j] = count($num_arr_2);
							$recv_exp_cnt = count($num_arr_2);
							
						}
						
						if($_POST['send_ssh_check'] || $_POST['send_ssh_check2'] || $_POST['send_ssh_check3']){ //수신처 우선
						    $loop_check_num = 0; // 폰별 신규 배정된 번호 합
						    $loop_allocate_num = 0; // 폰별 배정된 번호 합
						    
    						for($j=0;$j<count($sendnum);$j++) //발송가능 폰번호별
    						{
    						    
            					$req = $reg.$j;
            					if ($max_cnt_arr[$j] > $today_limit)  $max_cnt_arr[$j] = $today_limit; //최대 발송 수 494 건 넘는 것 제한
            					$recv_arr=array();
            					$deny_url_arr=array();
            					$add_msg_arr=array();
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
    						    if($_POST['send_ssh_check'] ||  $_POST['send_ssh_check3']) {
        							$ssh_num=array(); //$ssh_num <= 중복없는 수신번호들
        							$sql_ssh="select recv_num from Gn_MMS where mem_id = '{$_SESSION['one_member_id']}' and send_num='$sendnum[$j]' and result = '0' and reg_date like '$date_month%' group by(recv_num)";
        							$resul_ssh=mysqli_query($self_con,$sql_ssh);
        							if(mysqli_num_rows($resul_ssh))
        							{
        								while($row_ssh=mysqli_fetch_array($resul_ssh))
        								{
        									$ssh_arr=array();
        									$ssh_arr=explode(",",$row_ssh['recv_num']);
        									$ssh_num=array_merge($ssh_num,(array)$ssh_arr);	 			
        								}
        								unset($ssh_arr);
        								$ssh_num=array_unique($ssh_num); //$ssh_num <= 중복없는 수신번호들	
        								//print_r($num_arr_2);
        								$send_num_list[$sendnum[$j]] = array_intersect($ssh_num, $num_arr_2); //해당 값의 중복값 (우선발송 OR 전용 발송 값 배정
        								
        								$ck = 0;
        								foreach($send_num_list[$sendnum[$j]] as $key=>$val) {
        								    if($ck > $this_time_send-1)
        								        array_push($remain_array,$val); //오버되는 수신처
        								    $ck++;
        								}
        								//print_r($remain_array);
        								$send_num_list[$sendnum[$j]] = array_diff($send_num_list[$sendnum[$j]], $remain_array);
        								// 중복 배열 삭제
        								$num_arr_2 = array_diff($num_arr_2, $send_num_list[$sendnum[$j]]); // 사용한 발송이력 전화번호 배정후 삭제
        								
        								// 오버 배열 재적용
        								//$num_arr_2 = array_merge($num_arr_2, $remain_array);
        								
        								
        								sort($send_num_list[$sendnum[$j]]);
        								
        								if(count($send_num_list[$sendnum[$j]]) >0) {
        								    $success_cell_arr[$sendnum[$j]] = $sendnum[$j];
        								}
        							}
        							//echo "\n".$sendnum[$j]."====".count($send_num_list[$sendnum[$j]])."===기존수신처".count($num_arr_2)."===새수신처".count($num_arr_3)."\n";
        						}
    							
    
    							$used_ssh_cnt = count($ssh_num); //사용된 수신처 수 //2016-03-10 추가
    							
    
    							//새로 발송 가능 수신처 , $agency_arr는 rlatjd_fun.php에서 정의 
    				   			//$ssh_num_true[$j]=$monthly_receive_cnt_user-$used_ssh_cnt; //2016-03-10 수정						     // 금일 삭제
    				   			
    				   			
                				// Cooper Add 총 발송건수 체크 (선거 본인용) 수신처 무제한 변경 2016-04-04 
                				if($sendnum[$j] == str_replace("-", "", $member_info['mem_phone']) && $member_info['mem_type'] == "V") {
            					    if($telecom == "LG") 
            					        $ssh_num_true[$j] = 90000;
            					    else
            					        $ssh_num_true[$j] = 60000;
                				}										
    							
        						// HERE 코드 확인
        						// 전화번호별 1일 발송양, 월간 발송양 , 월간 수신처 확인
        						// 오늘발송량, 이달발송량, 이달수신처량(사용량/전체한도), 이달 200건 초과횟수 10회 미만일경우만 발송(200건이상 발송의 경우 10회 미만일경우 성공 10회 이상일경우 실패)
        						// ( 오늘 오전에 100건 보내고 오후에 100건 보내면 200미만 +1 카운트에서 200초과 +1로 이동해야 하니까 / 취소하거나 미발송건으로 복원시에도)        					
        						// STEP #1 == 1일 발송양 확인 // 폰별
        						
        						// $total_num_cnt 총발송양
        						//echo "일발송양:".$send_cnt[$j]."====".$this_time_send."\n";
        						//echo "\n".$allocation_cnt."===".count($num_arr)."\n";
        						
        						if($send_cnt[$j] < count($num_arr)) {
        						    $allocation_cnt = $send_cnt[$j]; // 일발송양보다 작으면 일발송양으로 수정
        						    //echo "T1";
        						} else {
        						    //$allocation_cnt = count($num_arr);
        						    //echo "T2";
        						}
        						
        						
        						// STEP #2 == 월간 발송양 확인 // 아이디별
        						//echo "월발송양:".$thiMonleftCnt."\n";
        						if($thiMonleftCnt < $total_num_cnt) {
        						    $allocation_cnt = $thiMonleftCnt;
        						    //echo "T3";
        						}
        						
        						 
        						// STEP #3 == 월간 수신처 확인 // 폰별 $limitCnt
        						//echo "수신처:".$used_ssh_cnt."==".$limitCnt."====".$allocation_cnt."===".$ssh_num_true[$j]."\n";
        						// 추가 2016-05-20
        						if($allocation_cnt > $ssh_num_true[$j]) {
        						    $allocation_cnt = $ssh_num_true[$j];
        						}
        						
        						// 월간 수신처 초과 확인 2019-05-31
        						if($limitCnt  - $used_ssh_cnt < $allocation_cnt) { 
        						    $allocation_cnt = $limitCnt  - $used_ssh_cnt;
        						}
        						
        						// 월간 수신처가 마이너스 체크 2019-05-31
        						if($limitCnt  - $used_ssh_cnt <= 0) { 
        						    $allocation_cnt = 0;
        						}        						
        						
                                $query = "select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' and sendnum='".$sendnum[$j]."'";
                                $result = mysqli_query($self_con,$query);
                                $info = mysqli_fetch_array($result);    		
                                $daily_limit_cnt_user = $info['daily_limit_cnt_user']; // 10건 
                                $daily_min_cnt_user= $info['daily_min_cnt_user']; // 20건
                                $monthly_receive_cnt_user= $info['monthly_receive_cnt_user']; // 수신처 제한
                                
                                // 이번달 발송건이 10건 이상시	
                                //if($info['cnt1'] >= 10) $allocation_cnt = 199;        						        						
                                if($info['cnt1'] >= 10) $allocation_cnt = $daily_min_cnt_user-1;        						        						
        						
        						if(!$_POST['send_ssh_check']) {
        						    if($ssh_num_true[$j] <= $used_ssh_cnt) {
        						        // 수신처 초과시 신규 배정 X
        						        $allocation_cnt = 0;
        						        //echo "T4";
        						    } else {
            						    // 수신처 초과가 아닌경우 
        						        //if($allocation_cnt > $limitCnt - $used_ssh_cnt) {
        						        //    $allocation_cnt = $limitCnt - $used_ssh_cnt;
            						        //echo "T5";
        						        //}
        						    }
        						}
        						
        						 //echo "배정수:$allocation_cnt==".count($send_num_list[$sendnum[$j]])."\n";
        						
        						// STEP #4 == 이달 200건 초과횟수 10회 미만일경우만 발송(200건이상 발송의 경우 10회 미만일경우 성공 10회 이상일경우 실패)
        						//echo "횟수:".$cnt1_arr[$j]."\n";
        						if($cnt1_arr[$j]>=10) { //10회 이상일우
        						    $send_msg[$j] = $sendnum[$j]."폰의 이달 200건 초과횟수 10회 이상 예외처리 199건까지 발송가능";
        					    }
        					    
        					    
        					     // STEP #4-1 폰별 신규 폰번호 발송 가능양에 따라 재분배
    							if($_POST['send_ssh_check']) {
    							    
    							    //if($allocation_cnt < ) // 발송건수가 오늘 발송 건수보다 작을 경우 수량을 더함
    			                    if(count($sendnum) == 1) {
    							        $allocation_cnt = $allocation_cnt + count($send_num_list[$sendnum[$j]]);  //2016-05-27 추가
    							        if($allocation_cnt > $send_cnt[$j]) $allocation_cnt = $send_cnt[$j]; //2016-05-27 추가 
    							    }
    							        
    							    //echo "C:".$loop_check_num."========".$allocation_cnt." - ".count($send_num_list[$sendnum[$j]])."\n";
    							    // 차이 만큼 신규 배정
                                    if($loop_check_num < $allocation_cnt - count($send_num_list[$sendnum[$j]])) {
                                        $send_num_list_cnt = count($send_num_list[$sendnum[$j]]);
                                        //echo "$allocation_cnt - $send_num_list_cnt";
                                        //echo $loop_allocate_num;
                                        //echo "$allocation_cnt  == $send_num_list_cnt == $loop_allocate_num < ".count($num_arr)."\n";
                                        //echo "$loop_check_num == $allocation_cnt  == $send_num_list_cnt == $loop_allocate_num < ".count($num_arr)."\n";
                                        // 총 발송 건수와 배정된 건수가 적을 경우만 루프
                                        if(  $loop_allocate_num < count($num_arr)) {
                                            for($kkk = 0; $kkk < $allocation_cnt - $send_num_list_cnt;$kkk++) { // 1차 배정 분 제외 할지 여부
                                            //for($kkk = 0; $kkk < $allocation_cnt;$kkk++) {
                                                if($num_arr_3[$kkk]) { // 값이 있을경우 배정
                                                    //echo ($send_num_list_cnt."+".$kkk)."===".$num_arr_3[$kkk]."\n";
                                                    $send_num_list[$sendnum[$j]][$send_num_list_cnt+$kkk] = $num_arr_3[$kkk];
                                                    $success_cell_arr[$sendnum[$j]] = $sendnum[$j];
                                                }
                                            }
                                            
                                            
                                            $num_arr_3 = array_diff($num_arr_3, $send_num_list[$sendnum[$j]]);
                                            sort($num_arr_3);
                                            $loop_allocate_num = count($send_num_list[$sendnum[$j]]);
                                            //$loop_check_num = $loop_check_num + $send_num_list_cnt;
                                        }
                                        //echo "\n".$sendnum[$j]."====".count($send_num_list[$sendnum[$j]])."====".count($num_arr_3)."\n";
                                    }
    						    }        				
    							if($_POST['send_ssh_check2']) {
    							    // 차이 만큼 신규 배정
    							    
                                    if($loop_check_num < $allocation_cnt - count($send_num_list[$sendnum[$j]])) {
                                        $send_num_list_cnt = count($send_num_list[$sendnum[$j]]);
                                        //echo "$loop_check_num == $allocation_cnt  == $send_num_list_cnt == $loop_allocate_num < ".count($num_arr)."====".$ssh_num_true[$j]."\n";
                                        //echo "$allocation_cnt - $send_num_list_cnt";
                                        //echo $loop_allocate_num;
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
                                //echo count($send_num_list[$sendnum[$j]])."\n";
                                //print_r($send_num_list);
                                $success_arr=array_merge($success_arr,(array)$send_num_list[$sendnum[$j]]);
        						
        						// STEP #5 == 금일 발송양에 따른 통계 계산        						
        						//echo $sendnum[$j]."===".count($send_num_list[$sendnum[$j]])."===".$this_time_send."\n";
        						
        					    //echo $sendnum[$j]."====".$user_cnt[$sendnum[$j]]." + ".count($send_num_list[$sendnum[$j]])."\n";
        						
        						if($_POST['send_ssh_check']) {
            						$sql_check_s="select no,status from tjd_mms_cnt_check where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' and date=curdate() ";
            						$resul_check_s=mysqli_query($self_con,$sql_check_s);
            						$row_check_s=mysqli_fetch_array($resul_check_s);
            						
            						if($row_check_s['no']) { //tjd_mms_cnt_check에 자료 있으면 : 오늘 보낸 적 있음
            						    if($row_check_s['status']=="N") { //200미만 건 발송 이력 있음
        								    // Cooper Add  2016-05-08
        								    if($user_cnt[$sendnum[$j]] + count($send_num_list[$sendnum[$j]]) >= $daily_min_cnt_user  && count($send_num_list[$sendnum[$j]]) > 0) {
        										$sql_num="update Gn_MMS_Number set cnt1=cnt1+1, cnt2=cnt2-1 where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' ";
        										if($debug_mode == false) {
        										    mysqli_query($self_con,$sql_num);
        									    }	
        										
        										$sql_check_u="update tjd_mms_cnt_check set status='Y' where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' and date=curdate() ";
        										if($debug_mode == false) {
        										    mysqli_query($self_con,$sql_check_u);
        									    }
        									    
        										$cnt1_log_arr[$j] += 1; 
        										$cnt2_log_arr[$j] -= 1;
        										
//        										$cntYN_log_arr[$j] = $this_time_send; //2016-05-08 추가
        										//echo $cntYN_log_arr[$j];
        										//$this_time_send
        									}
                						} else if($row_check_s['status']=="Y") { //200미만 건 발송 이력 있음
                						}
                						$cntYN_log_arr[$j] = count($send_num_list[$sendnum[$j]]); //2016-05-08 추가
                					} else {
            					        if($user_cnt[$sendnum[$j]] + count($send_num_list[$sendnum[$j]]) >= $daily_min_cnt_user && count($send_num_list[$sendnum[$j]]) > 0) {
        									$sql_num="update Gn_MMS_Number set cnt1=cnt1+1 where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' ";
        									if($debug_mode == false) {
        									    mysqli_query($self_con,$sql_num);
        								    }	
        								    
        									//$sql_check_u="update tjd_mms_cnt_check set status='N' where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' and date=curdate() ";
        									//if($debug_mode == false) {
        									//    mysqli_query($self_con,$sql_check_u);
        								    //}
        									$sql_check_i="insert into tjd_mms_cnt_check set mem_id='{$_SESSION['one_member_id']}' , sendnum='$sendnum[$j]' , status='Y', date=curdate() ";
        									if($debug_mode == false) {
        									    mysqli_query($self_con,$sql_check_i);
        								    }
        								    $cnt1_log_arr[$j] += 1; 
        								} else if($user_cnt[$sendnum[$j]] + count($send_num_list[$sendnum[$j]]) < $daily_min_cnt_user  && count($send_num_list[$sendnum[$j]]) > 0) {    
        									$sql_num="update Gn_MMS_Number set cnt2=cnt2+1 where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' ";
        									if($debug_mode == false) {
        									    mysqli_query($self_con,$sql_num);
        								    }	
        								    
        									//$sql_check_u="update tjd_mms_cnt_check set status='Y' where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' and date=curdate() ";
        									//if($debug_mode == false) {
        									//    mysqli_query($self_con,$sql_check_u);
        								    //}   
        									$sql_check_i="insert into tjd_mms_cnt_check set mem_id='{$_SESSION['one_member_id']}' , sendnum='$sendnum[$j]' , status='N', date=curdate() ";
        									if($debug_mode == false) {
        									    mysqli_query($self_con,$sql_check_i);
        								    }        								            								     									    
        								    $cnt2_log_arr[$j] += 1;
        							    }
//        								$cntYN_log_arr[$j] = $this_time_send; //2016-05-08 추가
        								$cntYN_log_arr[$j] = count($send_num_list[$sendnum[$j]]); //2016-05-08 추가
        								
            					    }    							
            					} else {
            					    
            						$sql_check_s="select no,status from tjd_mms_cnt_check where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' and date=curdate() ";
            						$resul_check_s=mysqli_query($self_con,$sql_check_s);
            						$row_check_s=mysqli_fetch_array($resul_check_s);
            						if($row_check_s['no']) { //tjd_mms_cnt_check에 자료 있으면 : 오늘 보낸 적 있음
            						    if($row_check_s['status']=="N") { //200미만 건 발송 이력 있음
        								    // Cooper Add  2016-05-08
        								    if($user_cnt[$sendnum[$j]] + count($send_num_list[$sendnum[$j]]) >= $daily_min_cnt_user  && count($send_num_list[$sendnum[$j]]) > 0) {
        										$sql_num="update Gn_MMS_Number set cnt1=cnt1+1, cnt2=cnt2-1 where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' ";
        										if($debug_mode == false) {
        										    mysqli_query($self_con,$sql_num);
        									    }	
        										
        										$sql_check_u="update tjd_mms_cnt_check set status='Y' where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' and date=curdate() ";
        										//echo $sql_check_u."\n";
        										if($debug_mode == false) {
        										    mysqli_query($self_con,$sql_check_u);
        									    }
        									    
        										$cnt1_log_arr[$j] += 1; 
        										$cnt2_log_arr[$j] -= 1;
        										//$ssh_num_true[$j]
        										$cntYN_log_arr[$j] = count($send_num_list[$sendnum[$j]]); //2016-05-08 추가
        										//$this_time_send
        									}
                						} else if($row_check_s['status']=="Y") { //200미만 건 발송 이력 있음
                						}
                					} else {
            					        if($user_cnt[$sendnum[$j]] + count($send_num_list[$sendnum[$j]]) >= $daily_min_cnt_user  && count($send_num_list[$sendnum[$j]]) > 0) {
        									$sql_num="update Gn_MMS_Number set cnt1=cnt1+1 where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' ";
        									if($debug_mode == false) {
        									    mysqli_query($self_con,$sql_num);
        								    }	
        								    
        									//$sql_check_u="update tjd_mms_cnt_check set status='N' where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' and date=curdate() ";
        									//if($debug_mode == false) {
        									//    mysqli_query($self_con,$sql_check_u);
        								    //}
        								    
        									$sql_check_i="insert into tjd_mms_cnt_check set mem_id='{$_SESSION['one_member_id']}' , sendnum='$sendnum[$j]' , status='Y', date=curdate() ";
        									if($debug_mode == false) {
        									    mysqli_query($self_con,$sql_check_i);
        								    }        								    
        								    $cnt1_log_arr[$j] += 1; 
        								} else if($user_cnt[$sendnum[$j]] + count($send_num_list[$sendnum[$j]]) < $daily_min_cnt_user  && count($send_num_list[$sendnum[$j]]) > 0) {    
        									$sql_num="update Gn_MMS_Number set cnt2=cnt2+1 where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' ";
        									if($debug_mode == false) {
        									    mysqli_query($self_con,$sql_num);
        								    }	
        								    
        									$sql_check_i="insert into tjd_mms_cnt_check set mem_id='{$_SESSION['one_member_id']}' , sendnum='$sendnum[$j]' , status='N', date=curdate() ";
        									if($debug_mode == false) {
        									    mysqli_query($self_con,$sql_check_i);
        								    }
        								            								    
        									//$sql_check_u="update tjd_mms_cnt_check set status='Y' where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' and date=curdate() ";
        									//if($debug_mode == false) {
        									//    mysqli_query($self_con,$sql_check_u);
        								    //}    									    
        								    $cnt2_log_arr[$j] += 1;
        								    
        							    }
        							    
        								$cntYN_log_arr[$j] = count($send_num_list[$sendnum[$j]]); //2016-05-08 추가
            					    }    							            					    
            					}
    						}	
    						//echo $sql_num;
    						unset($ssh_num);
    						//print_r($send_num_list);					
    						
                            
                            //echo count($success_arr);
                            //print_r($success_arr);    						
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
            					$add_msg_arr = array();
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
    				   			$ssh_num_true[$j]=$monthly_receive_cnt_user-$used_ssh_cnt; //2016-03-10 수정						    
    				   			//echo $ssh_num_true[$j]."\n";
    				   			
                				// Cooper Add 총 발송건수 체크 (선거 본인용) 수신처 무제한 변경 2016-04-04 
                				if($sendnum[$j] == str_replace("-", "", $member_info['mem_phone']) && $member_info['mem_type'] == "V") {
            					    if($telecom == "LG") 
            					        $ssh_num_true[$j] = 90000;
            					    else
            					        $ssh_num_true[$j] = 60000;
                				}										
    							
        						// HERE 코드 확인
        						// 전화번호별 1일 발송양, 월간 발송양 , 월간 수신처 확인
        						// 오늘발송량, 이달발송량, 이달수신처량(사용량/전체한도), 이달 200건 초과횟수 10회 미만일경우만 발송(200건이상 발송의 경우 10회 미만일경우 성공 10회 이상일경우 실패)
        						// ( 오늘 오전에 100건 보내고 오후에 100건 보내면 200미만 +1 카운트에서 200초과 +1로 이동해야 하니까 / 취소하거나 미발송건으로 복원시에도)        					
        						// STEP #1 == 1일 발송양 확인 // 폰별
        						
        						// $total_num_cnt 총발송양
        						//echo "일발송양:".$send_cnt[$j]."====".$this_time_send."\n";
        						//echo "\n".$allocation_cnt."===".count($num_arr)."\n";
        						if($send_cnt[$j] < count($num_arr)) {
        						    $allocation_cnt = $send_cnt[$j]; // 일발송양보다 작으면 일발송양으로 수정
        						    //echo "T1";
        						} else {
        						    //$allocation_cnt = count($num_arr);
        						    //echo "T2";
        						}
        						
        						// STEP #2 == 월간 발송양 확인 // 아이디별
        						//echo "월발송양:".$thiMonleftCnt."\n";
        						if($thiMonleftCnt < $total_num_cnt) {
        						    $allocation_cnt = $thiMonleftCnt;
        						    //echo "T3";
        						}
        						
                                $query = "select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' and sendnum='".$sendnum[$j]."'";
                                $result = mysqli_query($self_con,$query);
                                $info = mysqli_fetch_array($result);   
                                
                                $daily_limit_cnt_user = $info['daily_limit_cnt_user']; // 10건 
                                $daily_min_cnt_user= $info['daily_min_cnt_user']; // 20건
                                $monthly_receive_cnt_user= $info['monthly_receive_cnt_user']; // 수신처 제한
                                                                
                                $pkey[$info['sendnum']] = $info['pkey']; 		
                                // 이번달 발송건이 10건 이상시	
                                //if($info['cnt1'] >= 10) $allocation_cnt = 199;        						        						
                                if($info['cnt1'] >= 10) $allocation_cnt = $daily_min_cnt_user-1;
        						 
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
        						
    							$sql_ssh="select recv_num from Gn_MMS where mem_id = '{$_SESSION['one_member_id']}' and send_num='$sendnum[$j]' and result = '0' and reg_date like '$date_month%'";
    							//echo $sql_ssh;
    							$resul_ssh=mysqli_query($self_con,$sql_ssh);
    							$ssh_num = array();
    							if(mysqli_num_rows($resul_ssh))
    							{
    								while($row_ssh=mysqli_fetch_array($resul_ssh))
    								{
    									$ssh_arr=array();
    									$ssh_arr=explode(",",$row_ssh['recv_num']);
    									//echo count($ssh_arr)."\n";
    									$ssh_num=array_merge($ssh_num,$ssh_arr);	 
    								}
    								//echo "갯수".count($ssh_num)."\n";
    								
    								unset($ssh_arr);

    								//$num_arr_2 = array
    							}

    							$used_ssh_cnt = count(array_unique($ssh_num)); //사용된 수신처 수 //2016-03-10 추가        						
        						
        						// 월간 수신처 초과 확인 2019-05-31
        						if($limitCnt  - $used_ssh_cnt < $allocation_cnt) { 
        						    $allocation_cnt = $limitCnt  - $used_ssh_cnt;
        						}
        						
        						// 월간 수신처가 마이너스 체크 2019-05-31
        						if($limitCnt  - $used_ssh_cnt <= 0) { 
        						    $allocation_cnt = 0;
        						}        						        						
        						
        						// 추가
        						if($left_ssh_count  <= $allocation_cnt) {
        						    $allocation_cnt = $left_ssh_count;
        						}
        						
        						//echo "배정수:$allocation_cnt==$total_num_cnt==$left_ssh_count\n";
        						
        						// STEP #4 == 이달 200건 초과횟수 10회 미만일경우만 발송(200건이상 발송의 경우 10회 미만일경우 성공 10회 이상일경우 실패)
        						//echo "횟수:".$cnt1_arr[$j]."\n";
        						if($cnt1_arr[$j]>=10) { //10회 이상일우
        						    $send_msg[$j] = $sendnum[$j]."폰의 이달 200건 초과횟수 10회 이상 예외처리 199건까지 발송가능";
        					    }
        					    
        					    
        					    
        					     // STEP #4-1 폰별 신규 폰번호 발송 가능양에 따라 재분배
     
    							    // 차이 만큼 신규 배정
    							    
                                    if($loop_check_num < $allocation_cnt - count($send_num_list[$sendnum[$j]])) {
                                        $send_num_list_cnt = count($send_num_list[$sendnum[$j]]);
                                        //echo "\n$loop_check_num == $allocation_cnt  == $send_num_list_cnt == $loop_allocate_num < ".count($num_arr)."\n";
                                        //echo "$allocation_cnt - $send_num_list_cnt";
                                        //echo $loop_allocate_num;
                                        // 총 발송 건수와 배정된 건수가 적을 경우만 루프
                                        
                                        if(  count($num_arr) > 0) {
                                            //echo "밸쎔:".$allocation_cnt." - ".$send_num_list_cnt."\n";
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
                                    
                                
                                $success_arr=array_merge($success_arr,(array)$send_num_list[$sendnum[$j]]);
        						
        						// STEP #5 == 금일 발송양에 따른 통계 계산        						
        						//echo $sendnum[$j]."===".count($send_num_list[$sendnum[$j]])."===".$this_time_send."\n";
        						
 
            						$sql_check_s="select no,status from tjd_mms_cnt_check where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' and date=curdate() ";
            						$resul_check_s=mysqli_query($self_con,$sql_check_s);
            						$row_check_s=mysqli_fetch_array($resul_check_s);
            						if($row_check_s['no']) { //tjd_mms_cnt_check에 자료 있으면 : 오늘 보낸 적 있음
            						    
            						    if($row_check_s['status']=="N") { //200미만 건 발송 이력 있음
        								    // Cooper Add  2016-05-08
        								    if($user_cnt[$sendnum[$j]] + count($send_num_list[$sendnum[$j]]) >= $daily_min_cnt_user  && count($send_num_list[$sendnum[$j]]) > 0) {
        										$sql_num="update Gn_MMS_Number set cnt1=cnt1+1, cnt2=cnt2-1 where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' ";
        										if($debug_mode == false) {
        										    mysqli_query($self_con,$sql_num);
        									    }	
        										
        										$sql_check_u="update tjd_mms_cnt_check set status='Y' where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' and date=curdate() ";
        										if($debug_mode == false) {
        										    mysqli_query($self_con,$sql_check_u);
        									    }
        									    
        										$cnt1_log_arr[$j] += 1; 
        										$cnt2_log_arr[$j] -= 1;
        										
        										//$this_time_send
        									}
                						} else if($row_check_s['status']=="Y") { //200미만 건 발송 이력 있음
                						}
                						$cntYN_log_arr[$j] = count($send_num_list[$sendnum[$j]]); //2016-05-08 추가
                					} else {
                					    //echo $row_check_s['status']."==";
            					        if($user_cnt[$sendnum[$j]] + count($send_num_list[$sendnum[$j]]) >= $daily_min_cnt_user && count($send_num_list[$sendnum[$j]]) > 0) {
        									$sql_num="update Gn_MMS_Number set cnt1=cnt1+1 where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' ";
        									if($debug_mode == false) {
        									    mysqli_query($self_con,$sql_num);
        								    }	
        								    
        									//$sql_check_u="update tjd_mms_cnt_check set status='N' where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' and date=curdate() ";
        									$sql_check_i="insert into tjd_mms_cnt_check set mem_id='{$_SESSION['one_member_id']}' , sendnum='$sendnum[$j]' , status='Y', date=curdate() ";
        									if($debug_mode == false) {
        									    mysqli_query($self_con,$sql_check_i);
        								    }
        								    $cnt1_log_arr[$j] += 1; 
        								} else if($user_cnt[$sendnum[$j]] + count($send_num_list[$sendnum[$j]]) < $daily_min_cnt_user && count($send_num_list[$sendnum[$j]]) > 0) {    
        									$sql_num="update Gn_MMS_Number set cnt2=cnt2+1 where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' ";
        									if($debug_mode == false) {
        									    mysqli_query($self_con,$sql_num);
        								    }	
        								    
        									$sql_check_i="insert into tjd_mms_cnt_check set mem_id='{$_SESSION['one_member_id']}' , sendnum='$sendnum[$j]' , status='N', date=curdate() ";
        									if($debug_mode == false) {
        									    mysqli_query($self_con,$sql_check_i);
        								    }
        								            								    
        									//$sql_check_u="update tjd_mms_cnt_check set status='Y' where mem_id='{$_SESSION['one_member_id']}' and sendnum='$sendnum[$j]' and date=curdate() ";
        									//if($debug_mode == false) {
        									//    mysqli_query($self_con,$sql_check_u);
        								    //}    									    
        								    $cnt2_log_arr[$j] += 1;
        							    }
        								$cntYN_log_arr[$j] = count($send_num_list[$sendnum[$j]]); //2016-05-08 추가
            					    }    					
    						}	
    						//echo $sql_num;
    						unset($ssh_num);
    						//print_r($send_num_list);				
    				}
					

					//
					
					unset($num_arr_2);
					unset($num_arr_3);

					$today_will_send_count = count($num_arr); //금일 발송해야 되는 총 건 수
					//echo $today_will_send_count;
					

                $deny_url_arr=array();
                $agree_url_arr = array();
                $re_today_cnt = 0;
				for($j=0;$j<count($sendnum);$j++) //발송가능 폰번호별 발송 가능 수신처 확인
				{					
 
                    $recv_arr=array();
                    //echo "\n".$max_cnt."===".count($success_arr)."\n";
                    $max_cnt = count($send_num_list[$sendnum[$j]]); // 재선언 2016-05-08
                    //echo $sendnum[$j]."==".$max_cnt."\n";
                    //$recv_arr = implode(",", $send_num_list[$sendnum[$j]]); // Cooper Add
                    //echo $sendnum[$j]."_S\n";
                    if($max_cnt > 0) {
                        //echo $sendnum[$j]."_E\n";
    					//for($i=$s; $i<$max_cnt && $i<count($success_arr); $i++,$s++)
    					/*
    					for($i=0; $i<$max_cnt ; $i++)
    					{
    					    //echo $sendnum[$j]."====".$success_arr[$i]."\n";
    						array_push($recv_arr,$send_num_list[$sendnum[$j]][$i]);
    						if($_POST[send_deny_msg]) //수신거부 링크 삽입
    						{
    						    $longUrl = "kiam.kr/u.php?u=".$req."&n=".$send_num_list[$sendnum[$j]][$i];
    						    array_push($deny_url_arr,"문자수신을 원치 않으시는 분은 아래 주소를 클릭하시기 바랍니다. ".$longUrl."\n".$ad_msg);
    						}
    						else
    						array_push($deny_url_arr,"".$ad_msg);
    						
    						if($_POST[send_agree_msg]) //수신동의 링크 삽입
    						{
    							$longUrl = "kiam.kr/g.php?u=".$req."&n=".$send_num_list[$sendnum[$j]][$i];
    						    array_push($deny_url_arr,"문자수신을 원하시면 아래 링크를 클릭해 주세요. ".$longUrl."\n".$ad_msg);
    						}    						    						
    					}
    					*/
    					
    					
    					for($i=0; $i<$max_cnt ; $i++)
    					{
    					    $opt_message = "";
    					    //echo $sendnum[$j]."====".$success_arr[$i]."\n";
    					    // 추가
                            if(strlen($send_num_list[$sendnum[$j]][$i]) >= 10) {
        						array_push($recv_arr,$send_num_list[$sendnum[$j]][$i]);
        						if($_POST[send_deny_msg]) //수신거부 링크 삽입
        						{
        						    $longUrl = "kiam.kr/u.php?u=".$req."&n=".$send_num_list[$sendnum[$j]][$i];
        						    //array_push($deny_url_arr,"문자수신을 원치 않으시는 분은 아래 주소를 클릭하시기 바랍니다. ".$longUrl."\n".$ad_msg);
        						    $opt_message = "문자수신을 원치 않으시는 분은 아래 주소를 클릭하시기 바랍니다. ".$longUrl."\n".$ad_msg;
        						}
        						if($_POST[send_agree_msg] != "N") //수신동의 링크 삽입
        						{
        							$longUrl = "kiam.kr/g.php?u=".$req."&n=".$send_num_list[$sendnum[$j]][$i];
        						    $opt_message .= "문자수신을 원하시면 아래 링크를 클릭해 주세요. ".$longUrl."\n";
        						}    						    						
        						
        						array_push($deny_url_arr,$opt_message);
        					}
    						/*
    						array_push($deny_url_arr,"문자수신을 원치 않으시는 분은 아래 주소를 클릭하시기 바랍니다. ".$longUrl."\n".$ad_msg);
    						
    						if($_POST[send_agree_msg]) //수신동의 링크 삽입
    						{
    							$longUrl = "kiam.kr/g.php?u=".$req."&n=".$send_num_list[$sendnum[$j]][$i];
    						    array_push($deny_url_arr,"문자수신을 원하시면 아래 링크를 클릭해 주세요. ".$longUrl."\n".$ad_msg);
    						}    				
    						*/		    						
    					}    					
    					
    					//echo $sendnum[$j];
    					//print_R($recv_arr);
    
    					//$re_today_cnt+=count($recv_arr); 
    					if(count($recv_arr))
    					{   //앱에서만 보내기로 합 2016-03-07 
    /*						$mms_start_info['mem_id']=$_SESSION['one_member_id'];
    						$mms_start_info['send_num']=$sendnum[$j];
    						$mms_start_info['recv_num']=$sendnum[$j];
    						$mms_start_info['uni_id']=$reg."999";
    						$mms_start_info['content']="온리원문자 문자발송시작";		
    						$mms_start_info['title']="온리원문자";
    						$sql_start="insert into Gn_MMS set ";
    						foreach($mms_start_info as $key=>$v)
    						$sql_start.=" $key='$v' ,";
    						$sql_start.=" reg_date = now() ";
    						mysqli_query($self_con,$sql_start) or die(mysqli_error($self_con));*/
    					
    						// Cooper Add 치환 대상자 이름 뽑기
    						if(strstr($_POST[send_txt], "{|name|}")) {
    						    $recv_str_sql ="'".implode("','",$recv_arr)."'";
    						    //$query = "select distinct recv_num as recv_num, name as name from  Gn_MMS_Receive where Gn_MMS_Receive where mem_id ='{$_SESSION['one_member_id']}' and recv_num in ($recv_str_sql)  and name !='' group by recv_num order by reg_date desc";
    						    //$query = "set @@sort_buffer_size = 200000000;set @@group_concat_max_len = 200000000;";
    						    //mysqli_query($self_con,$query) or die(mysqli_error($self_con));
    						    
    						    $query = "
    						                select  GROUP_CONCAT(res.recv_num SEPARATOR ',') as recv_num, GROUP_CONCAT(res.name SEPARATOR ',') as name FROM (
                                                select a.recv_num, a.name from (
                                                    select  distinct recv_num as recv_num,name,reg_date from  Gn_MMS_Receive 
                                                    where mem_id ='{$_SESSION['one_member_id']}' and recv_num in ($recv_str_sql)
                                                    and name != ''
                                                    order by recv_num asc, reg_date desc
                                                ) a group by a.recv_num						                
                                            ) res";
    						    $replace_result = mysqli_query($self_con,$query) or die(mysqli_error($self_con));
    						    $replace_row = mysqli_fetch_array($replace_result);
    						    
    						    
    						    // Cooper add 03-10 
    						    // 차이있는 전화번호를 수정해준다(주소록에 없는 번호)
    						    
    						    $recv_arr_temp = explode(",", $replace_row['recv_num']);
    						    
    						    $diff_result = array_diff($recv_arr, $recv_arr_temp);
    						    
    						    $diff_str = implode(",", $diff_result);
    						    
    						    $recv_str=$replace_row['recv_num'];
    						    $recv_name_str=$replace_row['name'];
    						    
    						    if($recv_str) $recv_str .= ",".$diff_str;
    						    else  $recv_str .= $diff_str;
    						    
                                foreach($diff_result as $key => $val) {
        						    if($recv_name_str) $recv_name_str .= ",".substr($val,-4);
        						    else   $recv_name_str .= substr($val,-4);
                                }
    						    
    						    $mms_info[replacement1] = $recv_name_str."";
    						    
    						    mysqli_free_result($replace_result);
    						} else {
    						    
    						    $recv_str=implode(",",$recv_arr);
    						    
    						    $recv_name_str="";
    	                    }					    
    	                    
    	                    if(substr($recv_str,-1) == ",") $recv_str = substr($recv_str,0,strlen($recv_str)-1);
    	                    

    						//if($_POST[send_deny_msg]) //수신거부 링크 삽입
    						//{
    						//    $longUrl = "kiam.kr/u.php?u=".$req."&n=".$sendnum[$j];
    						//    $denv_url_str = "문자수신을 원치 않으시는 분은 아래 주소를 클릭하시기 바랍니다. ".$longUrl."\n".$ad_msg;
    						//}    	                    
    	                    
    						$denv_url_str=implode(",",$deny_url_arr);     

    						$mms_info['mem_id']=$_SESSION['one_member_id'];
    						$mms_info['send_num']=$sendnum[$j];
    						$mms_info['recv_num']=$recv_str;
    						$mms_info['uni_id']=$req;
    						$mms_info['content']=addslashes(htmlspecialchars($_POST[send_txt]));
    						$mms_info['jpg']=$img;	
    						$mms_info['type']=$_POST[send_type];	
    						$mms_info['title']=htmlspecialchars($_POST[send_title]);	
    						$mms_info[delay]=$_POST[send_delay];
    						$mms_info[delay2]=$_POST[send_delay2];					
    						$mms_info[close]=$_POST[send_close];
    						$mms_info[url]=$denv_url_str;
    						$mms_info['jpg']=$_POST[send_img].$img;
    						$mms_info['jpg1']=$_POST[send_img1].$img;
    						$mms_info['jpg2']=$_POST[send_img2].$img;
    						$mms_info['agreement_yn'] = $_POST['send_agreement_yn'];
    						if($reservation) //예약문자
    						$mms_info['reservation']=$reservation;
    						$sql="insert into Gn_MMS set ";
    						foreach($mms_info as $key=>$v)
    						{
    						$sql.=" $key='$v' ,";
    						}
    						$sql.=" reg_date = now() ";
    						
    						
    						
    						if($debug_mode == false) {
        						mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        						$sidx = mysqli_insert_id($self_con);
        						if($_POST[send_rday] == "") {
            						if($pkey[$mms_info['send_num']] != "") {
            						    $id = $pkey[$mms_info['send_num']];
                                        $title='{"MMS Push"}';
                                        $message='{"Send":"Start","idx":"'.$sidx.'","send_type":"'.$_POST[send_type].'"}';
                                        $fields = array ( 
                                            'data' => array (
                                                        "body" => $message,
                                                    	"title" => $title)
                                        );


                                        if(is_array($id)) {
                                            $fields['registration_ids'] = $id;
                                        } else {
                                            $fields['to'] = $id;
                                        }


                                        $fields['priority'] = "high";

                                        $fields = json_encode ($fields);
                                        $ch = curl_init ();
                                        curl_setopt ( $ch, CURLOPT_URL, $url );
                                        curl_setopt ( $ch, CURLOPT_POST, true );
                                        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
                                        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
                                        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );
                                        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
                                        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);                                        

                                        $result = curl_exec ( $ch );
                                        if ($result === FALSE) {
                                        die('FCM Send Error: ' . curl_error($ch));
                                        } 
                                        curl_close ( $ch );
                                        $json = json_decode($result);
                                        $msg = "";
                                        $msg = $json->results[0]->error;
                                        
                                        $query = "insert into Gn_MMS_PUSH set send_num='".$mms_info['send_num']."',
                                                                              idx='".$sidx."',
                                                                              token='".$pkey[$mms_info['send_num']]."',
                                                                              error='$msg'
                                                                              ";
            					        if($debug_mode == false) {
            					            
            					            mysqli_query($self_con,$query) or die(mysqli_error($self_con));        
            					        }
                                    }
                                }
    					    }
    						array_push($start_num,$sendnum[$j]);
    						$deny_url_arr = array();
    						
    					    $revnum = explode(",", $recv_str);
    					    
    					    //echo count($revnum)."\n";

    					    //echo "===========".count($revnum)."============";
    					    for($kk = 0; $kk < count($revnum);$kk++) { // 2016-03-07 uni_id 추가
    					        $log_query = "insert into Gn_MMS_Send_Log (uni_id,mem_id, send_num, recv_num, grp_name, reg_date) values ('".$req."','".$_SESSION['one_member_id']."','".$sendnum[$j]."','".$revnum[$kk]."','".$group_recv_info[$revnum[$kk]]."',NOW());";
    					        
    					        if($debug_mode == false) {
    					            
    					            mysqli_query($self_con,$log_query) or die(mysqli_error($self_con));        
    					        }
    					    }	
    					    
    					    if($_POST['free_use'] == "Y") {
    					        $log_query = "insert into Gn_Ad_Static (mem_id, send_num, send_count, reg_date) values ('".$_SESSION['one_member_id']."','".$sendnum[$j]."','".count($revnum)."',NOW());";
    					        if($debug_mode == false) {
    					            
    					            mysqli_query($self_con,$log_query) or die(mysqli_error($self_con));        
    					        }    					        
    					    }
    					    //건수 변경 기록  2016-03-07 추가
    				        $log_query = "insert into Gn_MMS_Send_Cnt_Log (uni_id,mem_id, send_num, recv_num_cnt, cnt1, cnt2, cntYN,cntAdj, reg_date) values ('".$req."','".$_SESSION['one_member_id']."','".$sendnum[$j]."','".count($revnum)."','".$cnt1_log_arr[$j]."','".$cnt2_log_arr[$j]."','".$cntYN_log_arr[$j]."','".$cntAdj_log_arr[$j]."',NOW())";
    				        // 발송 성공 건수 체크
    				        //echo $log_query."\n";
    				        $re_today_cnt += $cntYN_log_arr[$j];
    				        if($debug_mode == false) {
    				            mysqli_query($self_con,$log_query) or die(mysqli_error($self_con));   
    				        }
    					}
    				}	
				}


				unset($cnt1_log_arr[$j]); //2016-03-07 추가
				unset($cnt2_log_arr[$j]); 
				unset($cntYN_log_arr[$j]);
				unset($cntAdj_log_arr[$j]);
				unset($num_arr);
				
				


			//if($_POST[send_save_mms] && $re_today_cnt)
			if($_POST[send_save_mms])
			{ //메시지 저장
				if($_POST[send_onebook_status]=="Y")
				$message_info['msg_type']="C";
				else
				{
					if($_POST[send_img]) //메시지 타입
					$message_info['msg_type']="B";
					else
					$message_info['msg_type']="A";
				}
				$sql="insert into Gn_MMS_Message set "; //발송
				$message_info['mem_id']=$_SESSION['one_member_id'];
				$message_info['title']=htmlspecialchars(str_replace("{|name|}", "{|REP|}",$_POST[send_title]));
				$message_info['message']=htmlspecialchars(str_replace("{|name|}", "{|REP|}",$_POST[send_txt]));
				$message_info['img']=$_POST[send_img];			
				$message_info[img1]=$_POST[send_img1];			
				$message_info[img2]=$_POST[send_img2];			
				
				foreach($message_info as $key=>$v)
				{
					$sql.=" $key='$v' , ";
				}
				$sql.=" reg_date=now() ";
				if($debug_mode == false) {
    				mysqli_query($self_con,$sql);
			    }
			}
			$start_num=array_unique($start_num);   //발송참여 발신폰 번호들
			$no_num=array_unique($no_num);         //발송 안된 번호들
			$deny_num=array_unique($deny_num);   //수신거부 번호
			$etc_arr=array_unique($etc_arr);     // 기타 번호
			$wrong_arr=array_unique($wrong_arr); //없는 번호
			$lose_arr=array_unique($lose_arr);   //수신불가 번호


            // 재선언			
			//$re_today_cnt = count($success_arr); // 재선언 발송성공 2016-05-08
			//echo $re_today_cnt;
			//if($_POST['send_ssh_check'] || $_POST['send_ssh_check2'] || $_POST['send_ssh_check3']){
			    $ssh_total_cnt = $_POST[send_cnt] - $re_today_cnt; // 재선언 발송실패 2016-05-0
			//} else {
			    //$ssh_total_cnt = $total_num_cnt - $re_today_cnt; // 재선언 발송실패 2016-05-0
			//}
			

			//echo count($start_num).'|'.count($no_num).'|'.$re_today_cnt.'|'.$ssh_total_cnt;	
			//echo count($start_num)."/".count($no_num)."/".$re_today_cnt."/".$ssh_total_cnt."=====\n";
			//echo count($start_num).'|'.count($no_num).'|'.$re_today_cnt.'|'.$ssh_total_cnt.'|'.count($deny_num)."|".count($etc_arr)."|".count($wrong_arr)."|".count($lose_arr);	
			// Cooper Add
			//if($total_num_cnt == $re_today_cnt) {
			//    $re_today_cnt = $total_num_cnt - $ssh_total_cnt - count($deny_num) - count($etc_arr) -count($wrong_arr) - count($lose_arr);
			//    
			//}
			
		    if($ssh_total_cnt < 0) $ssh_total_cnt = 0;
			
			echo count($success_cell_arr).'|'.count($no_num).'|'.$re_today_cnt.'|'.$ssh_total_cnt.'|'.count($deny_num)."|".count($etc_arr)."|".count($wrong_arr)."|".count($lose_arr);	

			unset($etc_arr); //2016-03-07 위치이동
			unset($wrong_arr);
			unset($lose_arr);
			unset($deny_num);
			unset($revnum);
			unset($success_arr);
			
			exit;
					
	}
}
mysqli_close($self_con);
?>