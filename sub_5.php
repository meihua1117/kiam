<?
$path="./";
include_once "_head.php";
include_once "lib/rlatjd_fun.php";
if(!$_SESSION[one_member_id]){
?>
<script language="javascript">
location.replace('/ma.php');
</script>
<?
exit;
}else{
	$date_today=date("Y-m-d");
	$date_month=date("Y-m");
	//회원가입일 (선거용 3일 무료 사용 관련)
	$sql = "select first_regist,mem_type,phone_cnt,fujia_date1,fujia_date2 from Gn_Member where mem_id = '$_SESSION[one_member_id]'";
	$res_result = mysql_query($sql);
	$rsJoinDate = mysql_fetch_array($res_result);
	mysql_free_result($res_result);
	$trialLimit = date("Y-m-d 23:59:59",strtotime($rsJoinDate[0]."+0 days")); //회원가입일+3일
	/*
	$sql = "select add_phone from tjd_pay_result where buyer_id = '$_SESSION[one_member_id]' and end_date > '$date_today'  and end_status in ('Y','A') order by no desc limit 1";
	$res_result = mysql_query($sql);
	//결제 휴대폰 수
	$buyPhoneCnt = mysql_fetch_row($res_result);
	*/
	$sql = "select sum(add_phone) add_phone from tjd_pay_result where buyer_id = '$_SESSION[one_member_id]' and end_date > '$date_today'  and end_status in ('Y','A') and gwc_cont_pay=0 order by no desc limit 1";
	$res_result = mysql_query($sql);
	//결제 휴대폰 수
	$buyPhoneCnt = mysql_fetch_row($res_result);	
	mysql_free_result($res_result);
	if($buyPhoneCnt == 0){	//발송가능건
		$buyMMSCount = 0;
	}else{
		//$buyMMSCount = ($buyPhoneCnt[0] -1) * 9000;
		$buyMMSCount = $buyPhoneCnt[0] * 9000;
	}
	//무료제공건(선거용은 3일제한)
	// Cooper 무료건 변경 2016-04-18
	if (($trialLimit < $date_today) && $buyMMSCount == 0) {
		$freeMMSCount = 0;//100;
		$freeChk = "Y";
	}else{
	    if($buyMMSCount == 0) {
    		$freeMMSCount = 0;//100;
    		$freeChk = "Y";
	    } else {
    		$freeMMSCount = 0;
    		$freeChk = "N";
    	}
	}
	// 16-01-20 예약발송 건 전송 제한 30분
	// 1) reservation ~ 30분까지만 가져간다. 
	// 2) 시간 지난 것은 실패 처리 : Gn_MMS_ReservationFail로 이동,result = 3
	$sql_where = "where now() > adddate(reservation,INTERVAL 30 Minute) and result = 1 and mem_id = '$_SESSION[one_member_id]'";
	$sql = "insert into Gn_MMS_ReservationFail select * from Gn_MMS $sql_where";
	mysql_query($sql);
	//$sql = "delete from Gn_MMS $sql_where";
	//mysql_query($sql);
	$sql = "update Gn_MMS_ReservationFail set result = 3 $sql_where";
	mysql_query($sql);

	//로그인한 가입자 폰 번호
	$sql_num="SELECT mem_phone  FROM Gn_Member WHERE mem_id ='$_SESSION[one_member_id]'";
	$result_mem_phone=mysql_query($sql_num);
	$row_mem_phone = mysql_fetch_row($result_mem_phone);
	mysql_free_result($result_mem_phone);	
	$mem_phone = substr(str_replace(array("-"," ",","),"",$row_mem_phone[0]),0,11); 
	//수신처수는 당월 차감 / 발송 수는 당일 차감
	//오늘 예약 건 확인
	$reserv_cnt_today=0;
	$sql_result2 = "select SUM(recv_num_cnt) from Gn_MMS where reservation like '$date_today%' and up_date is null and mem_id = '$_SESSION[one_member_id]' ";
	$res_result2 = mysql_query($sql_result2);
	$row_result2 = mysql_fetch_array($res_result2);
	$reserv_cnt_today += $row_result2[0] * 1;
	mysql_free_result($res_result2);	
	//-이번달 예약건 수
	$reserv_cnt_thismonth=0;
	$sql_result = "select SUM(recv_num_cnt) from Gn_MMS where reservation like '$date_month%' and up_date is null and mem_id = '$_SESSION[one_member_id]' ";
	$res_result = mysql_query($sql_result);
	$row_result = mysql_fetch_array($res_result);
	$reserv_cnt_thismonth += $row_result[0] * 1;
	mysql_free_result($res_result);
	//-이번달 발송된 수
	$recv_num_ex_sum=0;
	$sql_result = "select SUM(recv_num_cnt) from Gn_MMS where reg_date like '$date_month%' and mem_id = '$_SESSION[one_member_id]' ";
	$res_result = mysql_query($sql_result);
	$row_result = mysql_fetch_array($res_result);
	$recv_num_ex_sum += $row_result[0] * 1;
	mysql_free_result($res_result);
	$recv_num_ex_sum +=$reserv_cnt_thismonth; //이번 달 예약된 수 추가
	//-오늘발송 건 수
	$rec_cnt_today=0;
	$sql_result2 = "select SUM(recv_num_cnt) from Gn_MMS where reg_date like '$date_today%' and reservation is null and mem_id = '$_SESSION[one_member_id]' ";
	$res_result2 = mysql_query($sql_result2);
	$row_result2 = mysql_fetch_array($res_result2);
	$rec_cnt_today += $row_result2[0] * 1;
	mysql_free_result($res_result2);
	$rec_cnt_today += $reserv_cnt_today; //오늘 예약된 발송 건 추가 Cooper 04-26 제외
	//-이번발송 $uni_id
	$rec_cnt_current=0;
	$sql_result3 = "select uni_id from Gn_MMS where mem_id = '$_SESSION[one_member_id]' order by idx desc limit 1";
	$res_result3 = mysql_query($sql_result3);
	$row_result3 = mysql_fetch_array($res_result3);
	$uni_id=substr($row_result3[uni_id],0,10);
	mysql_free_result($res_result3);
	//마지막 발송 건수
	$sql_result32 = "select SUM(recv_num_cnt) from Gn_MMS where mem_id = '$_SESSION[one_member_id]' and uni_id like '$uni_id%'";
	$res_result32 = mysql_query($sql_result32);
	$row_result32 = mysql_fetch_array($res_result32);
	$rec_cnt_current += $row_result32[0] * 1;
	mysql_free_result($res_result32);	
	//-마지막발송일
	$sql_result4 = "select reg_date from Gn_MMS where mem_id = '$_SESSION[one_member_id]' order by reg_date desc limit 1";
	$res_result4 = mysql_query($sql_result4);
	$row_result4 = mysql_fetch_row($res_result4);

	if($row_result4 == 0){
		$last_reg_date = "-";
	}else{
		$last_reg_date=date("Y.m.d", strtotime($row_result4[0]));
	}	
	mysql_free_result($res_result4);

	//이달 제공건
	$thiMonTotCnt = $freeMMSCount + $buyMMSCount;

	//이달잔여건
	$thiMonleftCnt = $thiMonTotCnt - $recv_num_ex_sum;
	if($freeChk == "N")
	    $thiMonTotCnt = 0;
	else if($freeChk == "Y") {
	    $thiMonTotCnt = $thiMonleftCnt;
	    $thiMonleftCnt = 0;
	}
}
?>
<link href='/css/sub_4_re.css' rel='stylesheet' type='text/css'/>
<script type="text/javascript" src="jquery.lightbox_me.js"></script>
<script type="text/javascript" src="/js/mms_send.js"></script>
<script type="text/javascript" src="/plugin/tablednd/js/jquery.tablednd.0.7.min.js"></script>
<script>
$(function(){
	$(".popbutton1").click(function(){
		$('.ad_layer1').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	})

	$(".popbutton5").click(function(){
		$('.ad_layer5').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	})
	$(".popbutton7").click(function(){
		$('.ad_layer7').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	})	

	$(".popbutton6").click(function(){
		$('.ad_layer6').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	})

	$(".popbutton2").click(function(){
		$('.ad_layer2').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	})
	$(".popbutton3").click(function(){
		$('.ad_layer3').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	})
	$(".popbutton4").click(function(){
		var phoneno = $(this).siblings().eq(0).find("input").val();
		$('.ad_layer4').lightbox_me({
			centered: true,
			onLoad: function() {
				$.ajax({
					type:"POST",
					url:"ajax/get_numinfo_election_coo.php",
					data:{pno:phoneno},
					dataType: 'json',
					success:function(data){
						$("#detail_name").val(data.detail_name);
						$("#pno").val(data.phoneno);
						$("#phoneno").html(data.phoneno);
						$("#installDate").html(data.installDate);	
						$("#donationCnt").html(data.donationCnt);
						$("#sshLimit").html(data.sshLimit);
						$("#phoneNumber").html(data.phoneNumber);
						$("#personalCnt").html(data.personalCnt);
						$("#cnt1State").html(data.cnt1State);
						$("#detail_company").val(data.detail_company);
						if(data.excelDownload == "완료")
						    $("#excelDownload").html("없음");
						else 
						    $("#excelDownload").html(data.excelDownload);
						if(data.excelDownload2 == "완료")
						    $("#excelDownload2").html("없음");
						else 
						    $("#excelDownload2").html(data.excelDownload2);
						$("#todaySendCnt").html(data.todaySendCnt);
						$("#cnt2State").html(data.cnt2State);
						$("#diviceModel").html(data.diviceModel);
						$("#detail_rate").val(data.detail_rate);
						$("#thismonthCnt").html(data.thismonthCnt);
						$("#lastSendDate").html(data.lastSendDate);	
						$("#syncDbdate").html(data.syncDbdate);	
						
						$("#daily_limit_cnt").val(data.daily_limit_cnt);	
						$("#daily_min_cnt").val(data.daily_min_cnt);	
						$("#monthly_receive_cnt").val(data.monthly_receive_cnt);	
						$("#daily_limit_cnt_user").val(data.daily_limit_cnt_user);	
						$("#daily_min_cnt_user").val(data.daily_min_cnt_user);	
						$("#monthly_receive_cnt_user").val(data.monthly_receive_cnt_user);	
					},
					error: function(){
					  alert('로딩 실패');
					}
				});
			}
		});
	});
    $('#table-dnd').tableDnD({
	    onDrop: function(table, row) {
	        var kk = -1;
            $('#table-dnd tr').each(function() {
                $(this).find("input[name=sort_no]").val(kk);
                kk++;
            });
	    },        
    }); // no options currently
    $("#table-dnd tr").not(".nodrop").hover(function() {
          $(this.cells[0]).addClass('showDragHandle');
    }, function() {
          $(this.cells[0]).removeClass('showDragHandle');
    });	
	
});

//폰정보 수정
function modify_phone_info(){
	var phno = $("#pno").val();
	if(!phno){
		alert('폰 정보가 없습니다.');
		return false;
	}else{
	    if($("#daily_limit_cnt").val() < $("#daily_limit_cnt_user").val()) {
	        alert('10회 최대발송 건수 보다 큽니다.');
	        return;
	    }
	    if($("#daily_min_cnt").val() < $("#daily_min_cnt_user").val()) {
	        alert('20회 최대발송 건수 보다 큽니다.');
	        return;
	    }
	    if($("#monthly_receive_cnt").val() < $("#monthly_receive_cnt_user").val()) {
	        alert('월수신처 건수 보다 큽니다.');
	        return;
	    }	    
	    
		$.ajax({
			type:"POST",
			url:"ajax/modify_phoneinfo_coo.php",
			data:{
				pno:phno,
				daily_limit_cnt:$("#daily_limit_cnt").val(),
				daily_min_cnt:$("#daily_min_cnt").val(),
				monthly_receive_cnt:$("#monthly_receive_cnt").val(),
				daily_limit_cnt_user:$("#daily_limit_cnt_user").val(),
				daily_min_cnt_user:$("#daily_min_cnt_user").val(),
				monthly_receive_cnt_user:$("#monthly_receive_cnt_user").val(),
				name:$("#detail_name").val(),
				company:$("#detail_company").val(),
			},
			success:function(data){
				location.reload();
			},
			error: function(){
			  alert('수정 실패');
			}
		});		
	}
}

//폰정보 삭제
function del_phone_info(){
	var msg = confirm('정말로 삭제하시겠습니까?');
	if(msg){
		var phno = $("#pno").val();
		console.log(phno);
		if(!phno){
			alert('폰 정보가 없습니다.');
			return false;
		}else{
			$.ajax({
				type:"POST",
				url:"ajax/del_phoneinfo.php",
				data:{pno:phno},
				success:function(){
					alert('삭제되었습니다.');
					location.reload();
				},
				error: function(){
				  alert('삭제 실패');
				}
			});		
		}	
	}else{
		return false;
	}
}
//주소록 다운
function excel_down_personal(pno){
	$("#box_text").val(pno);
	$("#excel_down_form").submit();
}
</script>
<div class="big_div">
	<div class="big_1">
    	<div class="m_div">
        	<div class="left_sub_menu">
                <a href="./">홈</a> > 
                <a href="sub_5.php">휴대폰등록</a>
            </div>
            <div class="right_sub_menu">
                <!--
                <a href="sub_1.php">온리원문자</a> &nbsp;|&nbsp; 
                <a href="sub_2.php">온리원디버</a> &nbsp;|&nbsp;
                -->
                <a href="sub_1.php">폰문자소개</a> ㅣ<a href="sub_5.php">휴대폰등록</a> ㅣ <a href="sub_6.php">문자발송</a> ㅣ <a href="sub_4_return_.php">발신내역</a> ㅣ <a href="sub_4.php?status=5&status2=3">수신내역</a> ㅣ <a href="sub_4.php?status=6">수신여부</a>
            </div>
            <p style="clear:both;"></p>
    	</div>
    </div>
	<div class="big_sub">
    	<form name="sub_4_form" action="" method="post" enctype="multipart/form-data">   
			<input type="hidden" name="order_name" value="<?=$order_name?>"  />
			<input type="hidden" name="order_status" value="<?=$order_status?>"/>
			<input type="hidden" name="page" value="<?=$page?>" />
			<input type="hidden" name="page2" value="<?=$page2?>" />        
    		<div class="m_div">
				<div class="m_div sub_4c">
					<div class="con_title">휴대폰 등록 관리</div>
					<div class="con_in">
						<div class="in_info">
							<table class="info_box_table" cellpadding="0" cellspacing="0">
								<tbody>
									<tr>
										<th style="width:125px">유료이용기간</th>
										<td style="width:381px"><?=$rsJoinDate[fujia_date1]?> ~ <?=$rsJoinDate[fujia_date2]?></td>
										<th style="width:125px">이용가능폰수</th>
										<td style="width:384px"><?=number_format($buyPhoneCnt[0])?></td>
									</tr>
								</tbody>
							</table>				    
							<table class="info_box_table" cellpadding="0" cellspacing="0">
								<tbody>
									<tr>
										<th>무료제공건</th>
										<td><?=number_format($freeMMSCount)?></td>
										<th>무료잔여건</th>
										<td><?=number_format($thiMonTotCnt)?></td>
										<th>이달발송건</th>
										<td><?=number_format($recv_num_ex_sum)?></td>
										<th>마지막발송일</th>
										<td><?=$last_reg_date?></td>
									</tr>
									<tr class="last_tr">
										<th>발송가능건</th>
										<td><?=number_format($buyMMSCount)?></td>
										<th>이달잔여건</th>
										<td><?=number_format($thiMonleftCnt)?></td>
										<th>오늘 발송건</th>
										<td><?=number_format($rec_cnt_today)?></td>
										<th>마지막발송건</th>
										<td><?=number_format($rec_cnt_current)?></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="button_box">
							<div class="left_box">
								<!--<span class="button_type"><a href="javascript:void(0)" onclick="select_app_check()">앱 상태 체크</a><span class="popbutton5 pop_view pop_right">?</span></span>
								<span class="button_type"><a href="javascript:void(0)" onclick="no_msg_del()">미 전송 문자 삭제</a><span class="popbutton6 pop_view pop_right">?</span></span>
								-->
							</div>
							<div class="in_bbox">
								<select name="lms_select">
									<option value="">선택</option>
										<?
										$select_lms_arr=array("sendnum"=>"휴대폰번호","memo"=>"소유자명");
										foreach($select_lms_arr as $key=>$v)
										{
										$selected=$_REQUEST[lms_select]==$key?"selected":"";
										?>
										<option value="<?=$key?>" <?=$selected?>><?=$v?></option>                                        
										<?
										}
										?>
								</select>
								<input type="text" name="lms_text" value="<?=$_REQUEST[lms_text]?>" />
								<span class="search_button"><a href="javascript:void(0)" onclick="sub_4_form.submit();">검색</a></span>
							</div>
							<div class="right_box">
								<!--
								<div class="red_info">
									* 첫번째 칸은 가입자의 번호입니다.
								</div>
								<div class="red_info">
									* 각 발송폰의 이름을 클릭하여 이름,통신사,기부비율을 입력하세요.
								</div>
								-->
							<!--
								<input type="text" name="deny_num" class="input2" placeholder="수신거부 개별 등록. 번호를 입력해주세요">
								<span class="search_button2"><a href="javascript:void(0)" onclick="deny_g_add()">저장</a></span>
								
								-->
								<!--<span class="popbutton7 button_type">
								상세정보입력안내
								</span>-->
								<select name="page_num">
									<option value="">목록개수</option>
									<option value="20">20</option>
									<option value="50">50</option>
									<option value="100">100</option>
									<option value="500">500</option>
									<option value="1000">1000</option>
								</select>
							</div>
						</div>
                    	<div class="red_info">
							* 문자솔루션 활용 가능한 폰은 2014년 5월 이후부터 출시된 안드로이드폰이며 아이폰은 사용할 수 없습니다.
						</div>
    					<div class="table_box">
							<table class="info_table" cellpadding="0" cellspacing="0" id="table-dnd" >
								<thead>
									<tr class="start_title_tr nodrop">
										<th class="table_title" rowspan="2" onclick="check_all(this,'seq[]')"><input type="checkbox" class="allCheck">선택</th>
										<!--<th class="table_title" rowspan="2">앱상태</th>-->
										<th class="table_title" rowspan="2" style="border-bottom: 1px solid #24303e;">발송순서</th>
										<th class="table_title" colspan="3">휴대폰 등록정보 <span class="popbutton1 pop_view">?</span></th>
										<th class="table_title" colspan="4">발송 관리용 이달의 통계 </th>
										<th class="table_title" colspan="3">통신 정책용 이달의 통계 <span class="popbutton3 pop_view">?</span></th>
									</tr>
									<tr class="finish_title_tr nodrop">
										<td class="table_sub_title">이름</td>
										<td class="table_sub_title">휴대폰 번호</td>
										<td class="table_sub_title">통신사 </td>
										<td class="table_sub_title">최대발송<br>(20회/10회)</td>
										<!--<td class="table_sub_title">개인문자 </td>-->
										<td class="table_sub_title">오늘발송 </td>
										<td class="table_sub_title">이달발송 </td>
										<td class="table_sub_title">수신처 제한
										</td>
										<td class="table_sub_title">최소건수 </td>
										<td class="table_sub_title">최대건수 </td>
									</tr>
									<? 
									//폰별 설정 가져오기
									$sql_serch=" (not (cnt1 = 10 and cnt2 = 20)) and mem_id ='$_SESSION[one_member_id]' ";
									if($_REQUEST[lms_text] && $_REQUEST[lms_select])
										$sql_serch.=" and {$_REQUEST[lms_select]} like '$_REQUEST[lms_text]%' ";
									$sql="select count(idx) as cnt from Gn_MMS_Number where $sql_serch ";
									$result = mysql_query($sql) or die(mysql_error());
									$row=mysql_fetch_array($result);
									mysql_free_result($result);
									$intRowCount=$row[cnt];
									if (!$_POST[lno]) 
										$intPageSize =20;
									else 
										$intPageSize = $_POST[lno];				
									if($_POST[page]){
										$page=(int)$_POST[page];
										$sort_no=$intRowCount-($intPageSize*$page-$intPageSize); 
									}else{
										$page=1;
										$sort_no=$intRowCount;
									}
									if($_POST[page2])
										$page2=(int)$_POST[page2];
									else
										$page2=1;
									$int=($page-1)*$intPageSize;
									if($_REQUEST[order_status])
										$order_status=$_REQUEST[order_status];
									else
										$order_status="desc"; 
									if($_REQUEST[order_name])
										$order_name=$_REQUEST[order_name];
									else
										$order_name="user_cnt";
									$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
								?>
								</thead>
								<tbody>
								<?
									$sql="(select idx,sendnum,memo,memo2,cnt1,cnt2,donation_rate,daily_limit_cnt,sort_no,daily_limit_cnt_user,daily_min_cnt_user,monthly_receive_cnt_user from Gn_MMS_Number where $sql_serch  order by sort_no asc limit $int,$intPageSize)";
									$result=mysql_query($sql) or die(mysql_error());
									if($intRowCount){		
										$i=0;
										$k=0;							
										while($row=mysql_fetch_array($result)){							
											$idx = $row[idx];
											$memo = $row[memo];
											$memo2 = $row[memo2];
											$cnt1 = $row[cnt1];
											$cnt2 = $row[cnt2];
											$sendnum = $row[sendnum];
											$total_cnt = $row[daily_limit_cnt]; //기본 일별 총발송 가능량
											$donation_rate = $row[donation_rate]; //기부 비율
											$donation_cnt = ceil($total_cnt * $donation_rate / 100); //기부 받은 수
											$person_cnt = $total_cnt - $donation_cnt; //개인 발송 문자 수
											$send_donation_cnt = 0; //$row[gl_cnt] //기부 받은 수 중 발송 수
											$send_person_cnt = 0; //개인 할당 분 발송 수
											$monthly_limit_ssh = $memo2 ? $agency_arr[$memo2] : 800; //월별 수신처 제한 수 
											$sql_num="update Gn_MMS_Number set max_cnt = $donation_cnt,gl_cnt = $person_cnt where mem_id='$_SESSION[one_member_id]' and sendnum='$sendnum' ";
											mysql_query($sql_num);
											//금일 발송 건수
											$sql_result2_g = "select SUM(recv_num_cnt) from Gn_MMS where send_num='$sendnum' and ((reg_date like '$date_today%' and reservation is null) or up_date like '$date_today%')";
											$res_result2_g = mysql_query($sql_result2_g);			
											$row_result2_g = mysql_fetch_array($res_result2_g);
											$send_donation_cnt += $row_result2_g[0] * 1;
											mysql_free_result($res_result2_g);
											if(($donation_cnt - $send_donation_cnt) >= 0){
												$sql_cnt_u = "update Gn_MMS_Number set user_cnt=$donation_cnt - $send_donation_cnt-2 where idx='$idx' ";
												mysql_query($sql_cnt_u);										
											}
											//이번달 총 발송 건 수
											$month_cnt_1=0;
											$sql_result_g = "select SUM(recv_num_cnt) from Gn_MMS where send_num='$sendnum' and (reg_date like '$date_month%'  or reservation like '$date_month%') ";
											$res_result_g = mysql_query($sql_result_g);
											$row_result_g = mysql_fetch_array($res_result_g);
											$month_cnt_1+= $row_result_g[0] * 1;
											mysql_free_result($res_result_g);
											//이번 달 총 수신처 수
											$ssh_cnt=0;
											$ssh_numT =array();
											$sql_ssh="select recv_num from Gn_MMS where send_num='$sendnum' and (reg_date like '$date_month%' or reservation like '$date_month%')  group by(recv_num)";
											$result_ssh=mysql_query($sql_ssh);
											while($row_ssh=mysql_fetch_array($result_ssh)){
												$ssh_arr=explode(",",$row_ssh[recv_num]);
												$ssh_numT=array_merge($ssh_numT,(array)$ssh_arr);
											}
											$ssh_arr=array_unique($ssh_numT);
											$ssh_cnt=count($ssh_arr);	
											mysql_free_result($result_ssh);  
											$sql_s="select status from Gn_MMS_status where send_num	='$sendnum' and recv_num	='$sendnum' order by regdate desc limit 1 ";
											$resul_s=mysql_query($sql_s);
											$row_s=mysql_fetch_array($resul_s);
											$row_s[status] = -1;
											if($row_s[status] == "-1") {
												$color = "btn_option_red";
												$msg = "OFF";
											} else if($row_s[status] == "0") {
												$color = "btn_option_blue";
												$msg = "ON";
											} else {
												$color = "btn_option_red";
												$msg = "체크전";
											}
											mysql_free_result($resul_s);						 
											?>
											<tr  id="dnd<?=$k++?>" class="list_tr">
												<td><input type="checkbox" name="seq[]" value="<?=$sendnum?>" /><?=$sort_no?></td>
												<td style="display:none"><span id='btn_<?=$row[sendnum]?>' class="<?php echo $color;?>"><?=$msg?></span></td>
												<td class="vio_td"><input type="text" name="sort_no" value="<?=$row['sort_no']&&$row['sort_no']!=100?$row['sort_no']:$k?>" style="width:30px;" ></td>
												<td class="name_input popbutton4"><input type="text" name="memo" value="<?=$memo?>" ></td>
												<td class="sendnum"><?=$sendnum?></td>
												<td class="com_select popbutton4"><span><?=$memo2?></span></td>
												<td class="sky_td popbutton4"><span><?=$row[daily_min_cnt_user]."/".$row[daily_limit_cnt_user]?></span></td>
												<!--<td class="sky_td popbutton4"><span><?=$send_person_cnt."/".$person_cnt?></span></td>-->
												<td class="gre_td"><span><?=($send_donation_cnt + $send_person_cnt)."/".$total_cnt?></span></td>
												<td class="gre_td"><span><?=$month_cnt_1?></span></td>
												<td class="pur_td"><span><?=$ssh_cnt."/".$row['monthly_receive_cnt_user']?></span></td>
												<td class="vio_td2"><span><?=$cnt2?></span></td>
												<td class="vio_td"><span><?=$cnt1?></span></td>
											</tr>
											<?
											$i++;
											$sort_no--;
										}
									}else{
										?>
										<tr>
											<td colspan="12">등록된 내용이 없습니다.</td>
										</tr>
										<?								
									}
										//연결 해제
										mysql_free_result($result);
										mysql_close($self_con);?>
								</tbody>
							</table>
							<div class="button_box">
								<div class="left_box">
									<span class="button_type"><a href="javascript:void(0)" onclick="save_sort()">발송순서 변경저장</a></span>
								</div>	
							</div>
							<div class="page_num" style="padding:0px">
								<!--span class="page_on"><a href="#">1</a></span>
								<span class="page_off"><a href="#">2</a></span>
								<span class="page_off"><a href="#">3</a></span>
								<span class="page_now">1/1</span//-->
								<?
								page_f($page,$page2,$intPageCount,"sub_4_form");
								?>
							</div>
						</div>
						<div class="ad_layer1">
							<div class="layer_in">
								<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
								<div class="pop_title">
									휴대폰 등록 정보
								</div>
								<div class="info_box">
									<table class="info_box_table" cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<th>이름</th>
												<td>홍길동</td>
												<th>앱 설치일</th>
												<td>15.10.25</td>
											</tr>
											<tr>
												<th>폰번호</th>
												<td>010-1111-1111</td>
												<th>앱 버전</th>
												<td>V70</td>
											</tr>
											<tr>
												<th>통신사</th>
												<td>KT</td>
												<th>마지막 사용일</th>
												<td>15.12.23</td>
											</tr>
											<tr>
												<th>기종</th>
												<td>삼성0000</td>
												<th>동기화 DB</th>
												<td>1148</td>
											</tr>
											<tr class="last_tr">
												<th>메모</th>
												<td></td>
												<th>엑셀다운</th>
												<td>파일표시</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="info_text">
									<p>
										이름 : 초기값(폰번호)을 소유자명으로 수정<br>
										폰번호 : 휴대폰에 문자앱 설치시 자동입력<br>
										통신사 : 앱설치 후 안내문자 발송시 자동입력<br>
										폰기종 : 앱설치 후 안내문자 발송시 자동입력<br>
										앱설치일 : 앱설치후 안내문자 발송시 자동입력<br>
										앱버전 : 설치한 앱의 버전표시<br>
										마지막사용일 : 마지막으로 문자앱을 사용한 날짜<br>
										동기화DB : 본인 휴대폰의 동기화된 휴대폰 번호숫자<br>
													<span>* 동기화시 자동으로 파일업로드</span><br>
										엑셀다운 : 동기화된 번호를 엑셀로 다운받기<br>
										메모사항 : 기억해야 할 중요한 사항 기록하기<br>
									</p>
								</div>
							</div>
						</div>
						<div class="ad_layer2">
							<div class="layer_in">
								<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
								<div class="pop_title">
									발송 관리용 이달의 통계
								<!--/div>
								<div class="info_box">
									<table class="info_box_table" cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<th>위탁문자</th>
												<th>개인문자</td>
												<th>오늘 발송</th>
												<th>이달 발송</td>
											</tr>
											<tr class="last_tr">
												<td>16/150</th>
												<td>100/350</td>
												<td>116/500</th>
												<td>3600</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="info_text">
									<p>
										<span>오늘 사용한 위탁문자량 / 기부자가 제공한 1일 위탁문자량: </span><br>
										기부자가 입력한 수치와 발송조건에 따라 자동으로 변경<br>
										예) 개인문자 중 30% 기부시 – 최대 150건 , 최소 60건 <br>
										<span>오늘 사용한 개인문자량 / 기부자가 사용할 1일 개인문자량: </span><br>
										1일 최대 문자사용량 - 위탁문자량<br>
										<span>오늘 발송한 총 문자량 (위탁문자+개인문자)/ 최대량 또는 최소량:</span><br>
										200~500건 10회 발송시 500-> 200으로 변경<br>
										이달 발송한 총 문자량 (위탁문자+개인문자) 
									</p>
								</div>
							</div>
						</div>-->
						<div class="ad_layer3">
							<div class="layer_in">
								<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
								<div class="pop_title">
									통신 정책용 이달의 통계
								</div>
								<div class="info_box">
									<table class="info_box_table" cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<th>수신처 제한</th>
												<th>200건 미만</td>
												<th>200~500 건</th>
											</tr>
											<tr class="last_tr">
												<td>2000/3000</th>
												<td>4</td>
												<td>6</th>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="info_text">
									<p>
										각 통신사에서 제한하는 이달의 문자수신처<br>
										- KT,LG : 월 3,000건  / SK : 월 3,000건<br>
										<br>
										이번 달에 1일 200건 미만을 발송한 횟수 (최대 30~31회)<br>
										- 200~500건을 10회 사용시 최대횟수 : 한달날짜수 – 10 (20~21회)<br>
										<br>
										이번 달에 1일 200건 이상 500건 미만으로 발송한 횟수 (최대10회)<br>
									</p>
								</div>
							</div>
						</div>

						<div class="ad_layer5">
							<div class="layer_in">
								<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
								<div class="pop_title">
									앱상태체크
								</div>
								<div class="info_text">
									<p>
										선택한 폰번호로 앱체크문자를 발송하여 사용가능 상태를 확인합니다. 5분 내에 수신 되면 on, 수신이 안되면 off<br />
									</p>
									<p>기본적으로 휴대폰이 WiFi 상태에서도 발송이 잘 되나 3G나 LTE 상태로 바꿔주시면 더욱 좋습니다.</p><br />
									<p>휴대폰의 스마트매니저 기능으로 인해 온리원문자 앱이 절전상태가 되어 발송이 되지 않을 수 있습니다. 설치 시 절전 해지를 꼭 해주세요.</p>
								</div>

							</div>
						</div>
						<div class="ad_layer7" style="height:480px">
							<div class="layer_in">
								<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
								<div class="pop_title">
									문자발송설정안내
								</div>
								<div class="info_text">	
									<p>
									1. 아이엠주소 : 아이엠 주소는 휴대폰주소록에서 중복을 제거하고 아이엠 명함의 연락처로 사용하는 주소록입니다.
									</p>
									<p>* 동기화 정보와 아이엠주소가 정상으로 나타나지 않을 경우 앱에서 계정변경을 눌러 로그아웃한 후 다시 로그인하시기 바랍니다. </p>
									<br />
									<p>
									2. 최대발송건수 : 각 통신사에서 제공하는 일별 최대발송건수로서 보통 500건까지월 10회 제공하고 있습니다. 
									</p>
									<br />
									<p>
									3. 최소발송건수 : 각 통신사에서 제공하는 일별 최소발송건수로서 보통 150-200건까지월 20회 제공하고 있습니다. 
									</p>
									<br />
									<p>
									4. 발송희망건수 : 최대발송, 최소발송, 수신처제한에 대한 이용자의 희망건수를 입력하되 왼쪽 항목의 수치보다 낮은 수를 입력합니다. 주의할 것은 개인이 폰에서 발송하는 건수는 통계에 포함되지 않으므로 하루에 사용하는 건수를 대략 계산하여 희망발송건수를 입력하세요. 
									</p>
									<br />
									<p>
									5. 발송건수 초과 책임 : 셀링솔루션에서 지원하는 여러가지 기능으로 인해 발송건수가 초과되는 것은 이용자의 책임입니다. 기본문자, 예약문자, 원스텝문자, 데일리문자 등에서 발송되는 통계가 희망건수를 초과하지 않도록 발송폰을 준비하거나 발송량을 확인하여 발송해주세요	
									</p>
								</div>
							</div>
						</div>
						<div class="ad_layer6">
							<div class="layer_in">
								<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
								<div class="pop_title">
									미전송문자삭제
								</div>
								<div class="info_text">
									<p>
										문자앱에서 가져가지 않고 서버에 대기중인 문자를 삭제합니다.
									</p>
								</div>

							</div>
						</div>
						<div class="ad_layer4">
							<div class="layer_in">
								<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
								<div>
								<div class="pop_title">
									휴대폰 상세정보                 
									<div class="right_box button_box" style="padding:0px 0px 10px 0px "> 
									<span class="popbutton7 button_type" style="font-size:12px"> 휴대폰정보 및 설정하기 </span>	
									</div>              
								</div>    					
								<div class="info_box">
									<table class="info_box_table" cellpadding="0" cellspacing="0">
										<tbody>
											<tr>
												<th>이름</th>
												<td><input type="text" id="detail_name" name="detail_name" value="" style="width:70px;"><input type="hidden" id="pno" name="pno" value=""></td>
												<th>앱 설치일</th>
												<td><span id="installDate">-</span></td>
												<th>10회 최대발송</th>
												<td>
													<select name="daily_limit_cnt" id="daily_limit_cnt">
														<option value="500">500</option>
													</select>
													</td>
												<th>발송희망건수<!--수신처제한--></th>
												<td>
													<input type="text" name="daily_limit_cnt_user" id="daily_limit_cnt_user">
												</td>
											</tr>
											<tr>
												<th>폰번호</th>
												<td><span id="phoneno">-</span></td>
												<th>동기화정보</th>
												<td><span id="excelDownload"></span></td>
												<th>20회 최대발송</th>
												<td>
													<select name="daily_min_cnt" id="daily_min_cnt">
														<option value="150">150</option>
														<option value="200">200</option>							        
													</select>							    
												</td>
												<th>발송희망건수<!--200건 미만--></th>
												<td><input type="text" name="daily_min_cnt_user" id="daily_min_cnt_user"></td>
											</tr>
											<tr>
												<th>통신사</th>
												<td><select id="detail_company" name="detail_company" ><option value="SK">SK</option><option value="KT">KT</option><option value="LG">LG</option><option value="HL">HL</option></select></td>
												<th>아이엠주소</th>
												<td><span id="excelDownload2"></span></td>
												<th>월수신처건수</th>
												<td> 
													<select name="monthly_receive_cnt" id="monthly_receive_cnt">
														<option value="2000">2000</option>
														<option value="3000">3000</option>							        
														<!--<option value="9000">9000</option>-->
													</select>							    							    
													</td>
												<th>발송희망건수<!--월수신처건수--></th>
												<td><input type="text" name="monthly_receive_cnt_user" id="monthly_receive_cnt_user"></td>
											</tr>
											<tr>
												<th>기종</th>
												<td><span id="diviceModel">-</span></td>
												<th>동기화일자</th>                         
												<td><span id="syncDbdate">-</span></td>     
												<th>마지막 사용일</th>
												<td><span id="lastSendDate">-</span></td>														
												<th>이달발송건</th>
												<td><span id="thismonthCnt">-</span></td>							
												<!--
												<th>마지막 사용일</th>
												<td><span id="lastSendDate">-</span></td>
												-->
											</tr>
										</tbody>
									</table>
								</div>
								<div class="ok_box">
									<a href="javascript:void(0)" onclick="modify_phone_info()"><img src="/images/su_button_18.jpg"></a> <a href="javascript:void(0)" onclick="del_phone_info()"><img src="/images/su_button_20.jpg" style="margin-left:10px;"></a>
								</div>
							</div>
						</div>
					</div>
				</div>
     		</div> 
     	</div>                                                        
	</div> 
	<form id="excel_down_form" name="excel_down_form" action="excel_down/box_down.php" target="excel_iframe" method="post">
		<input type="hidden" id="box_text" name="box_text" /> 
		<input type="hidden" id="grp_id" name="grp_id" value="" />       
	</form>
	<iframe name="excel_iframe" width="0" height="0"></iframe>	
<?
include_once "_foot.php";
?>
