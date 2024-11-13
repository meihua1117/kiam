<?
// header("Progma:no-cache");
$path = "./";
include_once "_head.php";
?>
<!-- <script language="javascript">
	alert('공지사항\n지금 문자발송기능을 업그레이드중입니다.수정되는대로 알려드리겠습니다.감사합니다.');
	history.back();
</script>
-->
<?
if (!$_SESSION['one_member_id']) {
?>
	<script language="javascript">
		location.replace('/ma.php');
	</script>
<?
	exit;
} else {
	$date_today = date("Y-m-d");
	$date_month = date("Y-m");
	//회원가입일 (선거용 3일 무료 사용 관련)
	/*$sql = "select first_regist from Gn_Member where mem_id = '{$_SESSION['one_member_id']}'";
	$res_result = mysqli_query($self_con,$sql);
	$rsJoinDate = mysqli_fetch_row($res_result);
	mysqli_free_result($res_result);
	$trialLimit = date("Y-m-d 23:59:59",strtotime($rsJoinDate[0]."-1 days")); //회원가입일-1일*/
	$trialLimit = date("Y-m-d 23:59:59", strtotime($member_1['first_regist'] . "-1 days")); //회원가입일-1일
	$sql = "select phone_cnt from tjd_pay_result where buyer_id = '{$_SESSION['one_member_id']}' and end_date > '$date_today' and end_status in ('Y','A') and gwc_cont_pay=0 order by end_date desc limit 1";
	$res_result = mysqli_query($self_con, $sql);
	//결제 휴대폰 수
	$buyPhoneCnt = mysqli_fetch_row($res_result);
	mysqli_free_result($res_result);
	if ($buyPhoneCnt[0] == 0) {	//유료결제건
		$buyMMSCount = 0;
	} else {
		//$buyMMSCount = ($buyPhoneCnt[0] -1) * 9000;
		$buyMMSCount = $buyPhoneCnt[0];
	}
	//무료제공건(선거용은 3일제한)
	// Cooper 무료건 변경 2016-04-18
	if ($buyMMSCount == 0) {
		$freeMMSCount = 0; //100;
		$freeChk = "Y";
		$fujia_pay = "Y";
	} else {
		$freeMMSCount = 0; //100;
		$freeChk = "";
		$fujia_pay = "Y";
	}
	// 16-01-20 예약발송 건 전송 제한 30분
	// 1) reservation ~ 30분까지만 가져간다. 
	// 2) 시간 지난 것은 실패 처리 : Gn_MMS_ReservationFail로 이동,result = 3
	$sql_where = "where now() > adddate(reservation,INTERVAL 30 Minute) and result = 1 and mem_id = '{$_SESSION['one_member_id']}'";
	$sql = "select * from Gn_MMS $sql_where";
	$res = mysqli_query($self_con,$sql);
	if(mysqli_num_rows($res) > 0){
		$sql = "insert into Gn_MMS_ReservationFail select * from Gn_MMS $sql_where";
		mysqli_query($self_con,$sql);
	}
	//$sql = "delete from Gn_MMS $sql_where";
	//mysqli_query($self_con,$sql);
	$sql = "update Gn_MMS_ReservationFail set result = 3 $sql_where";
	mysqli_query($self_con, $sql);

	//수신처수는 당월 차감 / 발송 수는 당일 차감
	//오늘 예약 건 확인
	$reserv_cnt_today = 0;
	$sql_result2 = "select SUM(recv_num_cnt) from Gn_MMS where reservation like '$date_today%' and up_date is null and mem_id = '{$_SESSION['one_member_id']}' ";
	$res_result2 = mysqli_query($self_con, $sql_result2);
	$row_result2 = mysqli_fetch_array($res_result2);
	$reserv_cnt_today += $row_result2[0] * 1;
	mysqli_free_result($res_result2);
	//-이번달 예약건 수
	$reserv_cnt_thismonth = 0;
	$sql_result = "select SUM(recv_num_cnt) from Gn_MMS where reservation like '$date_month%' and up_date is null and mem_id = '{$_SESSION['one_member_id']}' ";
	$res_result = mysqli_query($self_con, $sql_result);
	$row_result = mysqli_fetch_array($res_result);
	$reserv_cnt_thismonth += $row_result[0] * 1;
	mysqli_free_result($res_result);

	//-이번달 발송된 수
	$recv_num_ex_sum = 0;
	$sql_result = "select SUM(recv_num_cnt) from Gn_MMS where reg_date like '$date_month%' and mem_id = '{$_SESSION['one_member_id']}' ";
	$res_result = mysqli_query($self_con, $sql_result);
	$row_result = mysqli_fetch_array($res_result);
	$recv_num_ex_sum += $row_result[0] * 1;
	mysqli_free_result($res_result);
	$recv_num_ex_sum += $reserv_cnt_thismonth; //이번 달 예약된 수 추가
	//-오늘발송 건 수
	$rec_cnt_today = 0;
	$sql_result2 = "select SUM(recv_num_cnt) from Gn_MMS where reg_date like '$date_today%' and mem_id = '{$_SESSION['one_member_id']}' ";
	$res_result2 = mysqli_query($self_con, $sql_result2);
	$row_result2 = mysqli_fetch_array($res_result2);
	$rec_cnt_today += $row_result2[0] * 1;
	mysqli_free_result($res_result2);
	//$rec_cnt_today += $reserv_cnt_today; //오늘 예약된 발송 건 추가 // Cooper 제거
	//-이번발송 $uni_id
	$rec_cnt_current = 0;
	$sql_result3 = "select uni_id from Gn_MMS where mem_id = '{$_SESSION['one_member_id']}' order by idx desc limit 1";
	$res_result3 = mysqli_query($self_con, $sql_result3);
	$row_result3 = mysqli_fetch_array($res_result3);
	$uni_id = substr($row_result3['uni_id'], 0, 10);
	mysqli_free_result($res_result3);
	//마지막 발송 건수
	$sql_result32 = "select SUM(recv_num_cnt) from Gn_MMS where mem_id = '{$_SESSION['one_member_id']}' and uni_id like '$uni_id%'";
	$res_result32 = mysqli_query($self_con, $sql_result32);
	$row_result32 = mysqli_fetch_array($res_result32);
	$rec_cnt_current += $row_result32[0] * 1;
	mysqli_free_result($res_result32);
	//-마지막발송일
	$sql_result4 = "select reg_date from Gn_MMS where mem_id = '{$_SESSION['one_member_id']}' order by reg_date desc limit 1";
	$res_result4 = mysqli_query($self_con, $sql_result4);
	$row_result4 = mysqli_fetch_row($res_result4);

	if ($row_result4 == 0) {
		$last_reg_date = "-";
	} else {
		$last_reg_date = date("Y.m.d", strtotime($row_result4[0]));
	}
	mysqli_free_result($res_result4);
	//이달 제공건
	$thiMonTotCnt = $freeMMSCount + $buyMMSCount;

	//이달잔여건
	$thiMonleftCnt = $thiMonTotCnt - $recv_num_ex_sum;
}
// 2016-03-11 Cooper Add
$hour = date("H");
/*$sql="select * from Gn_Member where mem_id = '{$_SESSION['one_member_id']}' ";
$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$memberInfo=mysqli_fetch_array($result);
*/
$mem_phone = str_replace("-", "", $member_1['mem_phone']);

$sql = "(select idx,sendnum,memo,memo2,cnt1,cnt2,donation_rate,daily_limit_cnt from Gn_MMS_Number where  sendnum = '$mem_phone') ";
$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$srow = mysqli_fetch_array($result);
$total_cnt = 0;
if ($member_1['mem_type'] == "V" || $member_1['mem_type'] == "") {
	if ($srow['memo2'] == "LG") {
		$total_cnt = 1000000 * $srow['donation_rate'] * 0.01;
	} else if ($srow['memo2'] == "KT") {
		$total_cnt = 2000 * $srow['donation_rate'] * 0.01;
	} else if ($srow['memo2'] == "SK") {
		$total_cnt = 3000 * $srow['donation_rate'] * 0.01;
	}
}




///금일발송가능 , 회발송가능 횟수
$sql_sum = "select sum(user_cnt) as sumnum, sum(max_cnt) as summax ,sum(gl_cnt) as sumgl from Gn_MMS_Number where mem_id = '{$_SESSION['one_member_id']}'";
$resul_sum = mysqli_query($self_con, $sql_sum);
$row_sum_b = mysqli_fetch_array($resul_sum);
//월별 총 발송가능 횟수




$cu_user_cnt =  "";
$cu_today_cnt =  "";
$mon_text_pcount =  "";


if (!empty($row_sum_b)) {
	$cu_user_cnt = $row_sum_b['sumnum'] ? $row_sum_b['sumnum'] : $freeMMSCount;
	$cu_today_cnt = $row_sum_b['summax'] ? $row_sum_b['summax'] : 0;
	$mon_text_pcount = $row_sum_b['summax'] ? ($row_sum_b['summax'] * 10) + ((199 * 20) - ($row_sum_b['sumgl'] * 20)) : 0;
	if ($freeChk) {
		$cu_user_cnt = $freeMMSCount - $recv_num_ex_sum;
		$cu_today_cnt = $recv_num_ex_sum;
		$mon_text_pcount = $recv_num_ex_sum;
	}
} else {
	$cu_user_cnt = $row_sum_b['sumnum'] ? $row_sum_b['sumnum'] : $freeMMSCount;
	$cu_today_cnt = $row_sum_b['summax'] ? $row_sum_b['summax'] : 0;
	$mon_text_pcount = $cu_user_cnt - $cu_today_cnt;
}

$sql_get_data = "select * from Gn_MMS where reg_date>now()-60 and mem_id='{$_SESSION['one_member_id']}' order by idx desc limit 1";
$res_data = mysqli_query($self_con, $sql_get_data);
$row_data = mysqli_fetch_array($res_data);
?>
<link href='/css/sub_4_re.css' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="jquery.lightbox_me.js"></script>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>

<script>
	function showImage(cursor) {
		console.log(cursor);
		console.log($("[name='upimage_str" + cursor + "']").val());
		$('#preview').attr("src", "" + $("[name='upimage_str" + cursor + "']").val());
	}
	$(function() {
		$(".popbutton1").click(function() {
			$('.ad_layer1').lightbox_me({
				centered: true,
				onLoad: function() {}
			});
		})
	});

	$(".popbutton7").click(function() {
		$('.ad_layer7').lightbox_me({
			centered: true,
			onLoad: function() {}
		});
	})
	<?
	// 2016-03-11 Cooper Add
	?>
	var hour = "<?= $hour ?>";

	jQuery(function($) {
		$.datepicker.regional['ko'] = {
			closeText: '닫기',
			prevText: '이전달',
			nextText: '다음달',
			currentText: 'X',
			monthNames: ['1월(JAN)', '2월(FEB)', '3월(MAR)', '4월(APR)', '5월(MAY)', '6월(JUN)',
				'7월(JUL)', '8월(AUG)', '9월(SEP)', '10월(OCT)', '11월(NOV)', '12월(DEC)'
			],
			monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월',
				'7월', '8월', '9월', '10월', '11월', '12월'
			],
			dayNames: ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
			dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
			weekHeader: 'Wk',
			dateFormat: 'yy-mm-dd',
			firstDay: 0,
			isRTL: false,
			showMonthAfterYear: true,
			yearSuffix: ''
		};
		$.datepicker.setDefaults($.datepicker.regional['ko']);

		$('#rday').datepicker({
			showOn: 'button',
			buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
			buttonImageOnly: true,
			buttonText: "달력",
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			yearRange: 'c-99:c+99',
			minDate: 0,
			maxDate: ''
		});
	});
	$(function() {
		if (document.getElementsByName('group_num')[0].value || document.getElementsByName('num')[0].value) {
			<?
			if ($_REQUEST['deny_wushi']) {
			?>
				numchk('4');
			<?
			}
			?>
		}

		$('#down_excel').bind("click", function() {
			if ($('.num_check_c:eq(1)').html() * 1 > 10000) {
				alert('다운로드는 총건수 만건 이하만 지원합니다.');
			} else {
				$('#grp_id').val($('textarea[name=group_num]').val());
				$('#down_type').val('1');
				excel_down('excel_down/excel_join_down.php');
			}
		});
	})
</script>
<style>
	.panel-collapse.collapsed {
		display: none;
	}

	.panel-collapse {
		display: block;
	}
</style>
<div class="big_div">
	<div class="big_1">
		<div class="m_div">

			<div class="left_sub_menu">
				<a href="./">홈</a> >
				<a href="sub_6.php">문자발송</a>
			</div>
			<div class="right_sub_menu" style="float:left;margin-left:200px;">
				<!--
                <a href="sub_1.php">온리원문자</a> &nbsp;|&nbsp; 
                <a href="sub_2.php">온리원디버</a> &nbsp;|&nbsp;
                -->
				<a href="sub_1.php">온리원문자란?</a> ㅣ<a href="sub_5.php">휴대폰등록</a> ㅣ <a href="sub_6.php">문자발송</a> ㅣ <a href="sub_4_return_.php">발신내역</a> ㅣ <a href="sub_4.php?status=5&status2=3">수신내역</a> ㅣ <a href="sub_4.php?status=6">수신여부</a>
			</div>

			<p style="clear:both;"></p>
		</div>
	</div>
	<div class="big_sub">
		<div class="m_div">
			<div class="m_div sub_4c">
				<div class="sub_4_1_t2">
					<img src="images/sub_04_01_img_07.jpg" />
					<img src="images/sub_04_01_img_10.jpg" /> 마지막발송<span class="sub_4_1_t2_1">(<?= $rec_cnt_current ?>)</span> &nbsp;&nbsp; 오늘발송<span class="sub_4_1_t2_1">(<?= $rec_cnt_today ?>)</span> &nbsp;&nbsp; 이달발송<span class="sub_4_1_t2_1">(<?= $recv_num_ex_sum ?>)</span> &nbsp;&nbsp;
					<!--<img src="images/sub_04_01_img_12.jpg" /> 이번발송<span class="sub_4_1_t2_2">(<?= $cu_user_cnt ?>)</span> &nbsp;&nbsp; 하루발송<span class="sub_4_1_t2_2">(<?= $cu_today_cnt ?>)</span>  &nbsp;&nbsp; 한달발송<span class="sub_4_1_t2_2">(<?= $mon_text_pcount ?>)</span>-->
				</div>
				<?/*
		   		 <div class="con_title">
						문자발송하기
				</div>     				
				<div class="con_in">
			        <div class="in_info">
						<table class="info_box_table" cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<th>무료제공건</th>
									<td><?=number_format($freeMMSCount)?></td>
									<th>발송제공건</th>
									<td><?=number_format($thiMonTotCnt)?></td>
									<th>이달발송건</th>
									<td><?=number_format($recv_num_ex_sum)?></td>
									<th>마지막발송일</th>
									<td><?=$last_reg_date?></td>
								</tr>
								<tr class="last_tr">
									<th>유료결제건</th>
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
				</div>	
				*/ ?><br />
				<form name="sub_4_form" id="sub_4_form" action="" method="post" enctype="multipart/form-data">


					<div>
						<div class="sub_4_2_t1">
							<div><img src="images/sub_button_03.jpg" /></div>
							<div class="div_240">
								<div class="a1">
									<div class="b1" hidden>주소관리</div>
									<div class="b2 select_group_div" hidden>그룹번호</div>
									<div class="div_2px" hidden><textarea name="group_num" itemname='그룹번호' placeholder="그룹번호" onblur="numchk('4')"><?= $_REQUEST['group_num'] ?></textarea></div>
									<div class="b2" hidden>개별번호</div>
									<div class="div_2px"><textarea name="num" id="num" itemname='전화번호' placeholder="전화번호(쉼표로 구분)" onblur="numchk('4')"><?= $_REQUEST['num'] ?></textarea></div>
									<div class="b4">
										<div class="div_2px" style="display:none;">
											<label><input type="radio" name="type" value="1" checked />묶음발송</label>
											<label><input type="radio" name="type" value="0" />개별발송</label>
										</div>
										<div class="div_2px">
											<div style="float:left; display:none;">
												<!-- 16-01 20 발송간격 : 5 ~ 15 //-->
												<input type="text" name="delay" placeholder="발송간격" value="<?= $_REQUEST['delay'] ? $_REQUEST['delay'] : 5 ?>" onblur="send_delay(sub_4_form,this,1)" onkeyup="send_delay(sub_4_form,this,1)" />~<input type="text" name="delay2" placeholder="발송간격" value="<?= $_REQUEST['delay'] ? $_REQUEST['delay'] : 15 ?>" onblur="send_delay(sub_4_form,this,2)" />초
											</div>
											<div style="float:left;clear:both; display:none">
												<input type="text" id="close" name="close" placeholder="발송제한" value="<?= $_REQUEST['close'] ? $_REQUEST['close'] : 24 ?>" onblur="limitNight();" />시(20시)
												<input type="checkbox" value="Y" id="time_limit" checked> 제한해제
											</div>
											<p style="clear:both;"></p>
										</div>
										<div class="c1">
											<div>발송제외(<a href="javascript:void(0)" onclick="show_recv('deny_num','4','발송제외')" class="num_check_c">0</a>)</div>
											<div>실제발송(<span class="num_check_c">0</span>)</div>
											<div style="margin-right:0;">총건수(<span class="num_check_c">0</span>)</div>
											<p style="clear:both;"></p>
										</div>
										<div class="c1">
											<div style="display:none">처리후중복제거(<a href="javascript:void(0)" onclick="show_recv('deny_num','6','처리후중복제거된번호')" class="num_check_c">0</a>)</div>
											<div>중복제거(<a href="javascript:void(0)" onclick="show_recv('deny_num','7','중복제거된번호')" class="num_check_c">0</a>)</div>
										</div>
										<div class="c1">
											<div><label><input type="checkbox" name="deny_wushi[]" checked <?= $_REQUEST['deny_wushi'][0] ? "checked" : "" ?> onclick="numchk('0');type_check()" />수신불가</label>(<a href="javascript:void(0)" onclick="show_recv('deny_num','0','수신불가')" class="num_check_c">0</a>)</div>
											<div><label><input type="checkbox" name="deny_wushi[]" checked <?= $_REQUEST['deny_wushi'][1] ? "checked" : "" ?> onclick="numchk('1');type_check()" />없는번호</label>(<a href="javascript:void(0)" onclick="show_recv('deny_num','1','없는번호')" class="num_check_c">0</a>)</div>
											<div><label><input type="checkbox" name="deny_wushi[]" <?= $_REQUEST['deny_wushi'][2] ? "checked" : "" ?> onclick="numchk('2');type_check()" />기타</label>(<a href="javascript:void(0)" onclick="show_recv('deny_num','2','기타')" class="num_check_c">0</a>)</div>
											<p style="clear:both;"></p>
											<input type="hidden" name="deny_num" />
											<input type="hidden" name="deny_num" />
											<input type="hidden" name="deny_num" />
											<input type="hidden" name="deny_num" />
											<input type="hidden" name="deny_num" />
											<input type="hidden" name="deny_num" />
											<input type="hidden" name="deny_num" />
											<input type="hidden" name="deny_num" />
											<input type="hidden" name="deny_num" />
											<input type="hidden" name="recv_over" id="recv_over" />
										</div>
									</div>
								</div>
								<div class="a2">
									<div class="b1 popup_holder btn_addservice" style="cursor:pointer;border: 1px solid black;border-radius:1px;">부가서비스
										<?= $fujia_pay ? "" : "<a href='pay.php'>(결제후 사용가능합니다.)</a>" ?>

										<div class="popupbox" style="display:none; height: 55px;width: 214px; left: 87px; top: -10px;">
											국내 유일의 수신처 기능 등 문자 발송시 다양한 서비스를 무료로 사용가능합니다. <br><br>
											<a style="color: blue;" href="https://url.kr/sRzAer" target="_blank">[자세히 보기]</a>
										</div>
									</div>

									<div id="add_service" class="panel-collapse collapsed">
										<div class="b2">
											<div class="popup_holder">
												<div style="float:left;font-size:12px;">
													<label><input type="checkbox" <?= $fujia_pay ? "" : "disabled" ?> name="deny_wushi[]" <?= $_REQUEST['deny_wushi'][3] ? "checked" : "" ?> onclick="numchk('3');type_check()" />수신거부제외
														(<a href="javascript:void(0)" onclick="show_recv('deny_num','3','수신거부옵션으로 발송제외 되는번호')" class="num_check_c">0</a>)</label>

												</div>
												<div class="popupbox" style="left: 50px; top: 20px; height: 18px; width: 214px; background: white; display: none;">
													수신거부 제외하는 기능입니다.<br>
												</div>
											</div>
											<div style="float:right;font-size:12px;">
												<label><input type="checkbox" <?= $fujia_pay ? "" : "disabled" ?> name="deny_msg" onclick="deny_msg_click(this,0)" />수신거부 문자</label>
												<div class="deny_msg_span">OFF</div>
												<p style="clear:both"></p>
											</div>
											<p style="clear:both"></p>
										</div>

										<div class="b2 popup_holder">
											<div style="float:left;">
												<label><input type="checkbox" <?= $fujia_pay ? "" : "disabled" ?> name="ssh_check" onclick="deny_msg_click(this,1);type_check();numchk('4')" />이달의 수신처 우선발송</label>
												<div class="popupbox" style="display:none; height: 55px;width: 214px; left: 87px; top: -10px;">
													국내 유일의 수신처 기능 등 문자 발송시 다양한 서비스를 무료로 사용가능합니다. </div>
												<div class="deny_msg_span ">OFF</div>
												<p style="clear:both"></p>
											</div>
											<p style="clear:both;"></p>
										</div>
										<div class="b2">
											<div style="float:left;">
												<label><input type="checkbox" <?= $fujia_pay ? "" : "disabled" ?> name="ssh_check" onclick="deny_msg_click(this,2);numchk('5');type_check()" />이달의 수신처 제외발송</label>(<a href="javascript:void(0)" onclick="show_recv('deny_num','5','수신처옵션에서  발송제외되는 번호')" class="num_check_c">0</a>)
												<div class="deny_msg_span">OFF</div>
												<p style="clear:both"></p>
											</div>
											<p style="clear:both;"></p>
										</div>
										<div class="b2">
											<div style="float:left;">
												<label><input type="checkbox" <?= $fujia_pay ? "" : "disabled" ?> name="ssh_check" onclick="deny_msg_click(this,3);numchk('6');type_check()" />이달의 수신처 전용발송</label>(<a href="javascript:void(0)" onclick="show_recv('deny_num','8','수신처옵션에서  발송제외되는 번호')" class="num_check_c">0</a>)
												<div class="deny_msg_span">OFF</div>
												<p style="clear:both"></p>
											</div>
											<p style="clear:both;"></p>
										</div>
									</div>
								</div>
								<div class="a3">
									<div class="b1">예약서비스</div>
									<div>
										<input type="text" <?= $fujia_pay ? "" : "disabled" ?> name="rday" onfocus="type_check();" onblur="check_date('<?= date('Ymd') ?>')" placeholder="예약발송(일)" id="rday" value="<?= $_REQUEST['rday'] ?>" style="width:100px;" />
										<select name="htime" style="width:50px;" <?= $fujia_pay ? "" : "disabled" ?>>
											<?
											for ($i = 9; $i < 22; $i++) {
												$iv = $i < 10 ? "0" . $i : $i;
												$selected = $_REQUEST['htime'] == $iv ? "selected" : "";
											?>
												<option value="<?= $iv ?>" <?= $selected ?>><?= $iv ?></option>
											<?
											}
											?>
										</select>
										<select name="mtime" style="width:50px;" <?= $fujia_pay ? "" : "disabled" ?>>
											<?
											for ($i = 0; $i < 31; $i += 30) {
												$iv = $i == 0 ? "00" : $i;
												$selected = $_REQUEST['mtime'] == $iv ? "selected" : "";
											?>
												<option value="<?= $iv ?>" <?= $selected ?>><?= $iv ?></option>
											<?
											}
											?>
										</select>
									</div>
								</div>
								<div class="a4">
									<div class="b1">문자입력<span class="popbutton7 pop_view pop_right">?</span></div>
									<div class="b2">
										<div class="div_2px"> </div>
									</div>
									<div class="div_2px"><input type="text" name="title" itemname='제목' required placeholder="제목" style="width:100%;" value="<?= $row_data['title'] ?>" /></div>
									<div class="div_2px">
										<textarea name="txt" itemname='내용' id='txt' required placeholder="내용" onkeydown="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);" onkeyup="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();" onfocus="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();"><?= $row_data['content'] ?></textarea>
										<input type="hidden" name="onebook_status" value="N" />
										<input type="hidden" name="onebook_url" value="" />
									</div>
									<div class="div_2px" style="height:28px;line-height:28px;">
										<div style="float:left;">
											<a href="javascript:saveMessage()">문자저장하기</a>
										</div>
										<div style="float:right;">
											<span class="wenzi_cnt">0</span> byte
										</div>
										<div class="c1" style="float:right;">
											<div class="type_icon"><img src="images/sub_04_02-2_btn_50.jpg" title="문자입력기능 작동중" /></div>
											<div class="type_icon"><img src="images/sub_04_02-2_btn_52.jpg" title="포토입력기능 작동중" /></div>
											<div class="type_icon"><img src="images/sub_04_02-2_btn_54.jpg" title="원북입력기능 작동중" /></div>
											<div class="type_icon"><img src="images/sub_04_02-2_btn_56.jpg" title="수신제외기능 작동중" /></div>
											<div class="type_icon"><img src="images/sub_04_02-2_btn_58.jpg" title="발송제한기능 작동중" /></div>
											<div class="type_icon"><img src="images/sub_04_02-2_btn_60.jpg" title="예약발송기능 작동중" /></div>
											<p style="clear:both;"></p>
										</div>
										<p style="clear:both;"></p>
									</div>
									<div class="div_2px">
										<div style="float:left;">
											<a href="javascript:void(0)" onclick="window.open('msg_serch.php?status=1&status2=1','msg_serch','top=0,left=0,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no, status=no')">
												문자불러오기</a>
										</div>
										<div style="float:left;margin-left:30px;">
											<label><input type="checkbox" name="fs_msg" onclick="deny_msg_click(this,4)" />발신전용안내</label>
											<input type="hidden" name="fs_txt" value="이 번호는 문자발신전용 번호이오니 관련문의는 위의 안내전화를 이용해주시기바랍니다." />
											<div class="deny_msg_span">OFF</div>
											<p style="clear:both;"></p>
										</div>
										<div class="b2">
											<div style="float:left">
												<a href="javascript:void(0)" onclick="ml_view('txt','0','미리보기')">문자미리보기</a>
											</div>
											<div style="float:left;margin-left:30px;">
												<label><input type="checkbox" name="save_mms" value="Y" <?= $_REQUEST['save_mms'] ? "checked" : "" ?> />문자발송후 저장하기</label>
											</div>
											<p style="clear:both;"></p>
										</div>
										<p style="clear:both;"></p>
									</div>

								</div>
								<div class="a2">
									<div class="b2" style="float:left">이미지 미리보기</div>
									<div style="float:right">
										<button id="show1" onclick="showImage('');return false;">1</button>
										<button id="show2" onclick="showImage('1');return false;">2</button>
										<button id="show3" onclick="showImage('2');return false;">3</button>
									</div>
									<div id="preview_wrapper" class="img_view" style="display:inline-block;width:100%;">
										<div id="preview_fake" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale);">
											<img id="preview" onload="onPreviewLoad(this)" />
										</div>
									</div>
									<img id="preview_size_fake" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image);visibility:hidden; height:0;" />

									<div class="b2">형식 : jpg, gif, png / 크기 : 640X480이하</div>
									<div class="div_2px">
										<input type="file" id="img_file1" name="upimage" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=&target=upimage_str';sub_4_form.target='excel_iframe';sub_4_form.submit();" value="<?= $row_data['jpg'] ?>" />
										<div><input type="hidden" name="upimage_str" /></div>
										<input type="file" id="img_file2" name="upimage1" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=1&target=upimage_str1';sub_4_form.target='excel_iframe';sub_4_form.submit();" value="<?= $row_data['jpg1'] ?>" />
										<div><input type="hidden" name="upimage_str1" /></div>
										<input type="file" id="img_file3" name="upimage2" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=2&target=upimage_str2';sub_4_form.target='excel_iframe';sub_4_form.submit();" value="<?= $row_data['jpg2'] ?>" />
									</div>
									<div><input type="hidden" name="upimage_str2" /></div>
								</div>
								<div>
									<?
									if ($fujia_pay == "Y") {
									?>
										<a href="javascript:void(0)" onclick="window.open('msg_serch.php?status=1&status2=2','msg_serch','top=0,left=0,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no, status=no')">
											<img src="images/btn_send_help1.gif" /></a>
										<!--<a href="javascript:void(0)" onclick="window.open('onebook_serch.php?status=1','onebook_serch','top=0,left=0,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no, status=no')">
                                        <img src="images/btn_send_help2.gif" /></a> 원북문자-->
										<a href="javascript:void(0)" class="popbutton1">
											<img src="images/btn_send_help3.gif" /></a>
									<?
									} else {
									?>
										<a href="javascript:void(0)" onclick="if(confirm('결제후 사용가능합니다.\n\n 결제페이지로 이동하시겠습니까?')){location.replace('pay.php')}">
											<img src="images/btn_send_help1.gif" /></a>
										<a href="javascript:void(0)" onclick="if(confirm('결제후 사용가능합니다.\n\n 결제페이지로 이동하시겠습니까?')){location.replace('pay.php')}">
											<img src="images/btn_send_help2.gif" /></a>
										<a href="javascript:void(0)" onclick="if(confirm('결제후 사용가능합니다.\n\n 결제페이지로 이동하시겠습니까?')){location.replace('pay.php')}">
											<img src="images/btn_send_help3.gif" /></a>
									<?
									}
									?>
								</div>
								<br />


								<div><a id="send_msg_btn" href="javascript:void(0)" onclick="send_msg_new(sub_4_form)"><img src="images/sub_button_68.jpg" /></a></div>
							</div>
						</div>




						<div class="sub_4_2_t2">
							<div><img src="images/sub_button_05.jpg" /></div>
							<div class="div_340">
								<div class="d_font popup_holder" style="width:150px;float:left;margin-top:0px; cursor:pointer;">
									엑셀 업로드
									<div class="popupbox" style="display:none; left:30px; top:30px; height: 56px;width: 214px; background:white">
										보유한 DB파일을 업로드하는 기능입니다.<br>
										꼭 샘플을 다운받아서 엑셀작업 하세요.<br><br>
										<a style="color: blue;" href="https://url.kr/WkSwJA" target="_blank">[자세히 보기]</a>
									</div>
									<a href="javascript:void(0)" onclick="excel_down('excel_down/excel_down.php?down_type=2')" class="a_btn_2">샘플</a>


								</div>
								<div style="float:right;" class="popup_holder">
									<a href="javascript:;;" onclick="excel_down_c_group('<?= $mem_phone ?>')" style="border:1px solid #000;padding:5px">폰주소 업로드</a>
									<div class="popupbox" style="display:none; left:30px; top:30px; height: 56px;width: 218px; background:white">여기를 클릭하면 자신의 폰 연락처 디비를 가져와서 자동으로 주소록을 만들어줍니다.<br><br>
										<a style="color: blue;" href="https://url.kr/JApsjO" target="_blank">[자세히 보기]</a>
									</div>
								</div>
								<div style="clear:both;padding-top:10px;height:35px;">
									<input type="file" name='excel_file' style="width:230px" />
								</div>
								<div>
									<div class="div_float"><input type="hidden" name="old_group" /></div>
									<div class="div_float"><input type="text" placeholder="그룹명" name="new_group" /></div>
									<p style="clear:both;"></p>
								</div>
								<div style="margin-bottom:20px;">
									<div class="div_float"><a href="javascript:void(0)" onclick="excel_insert(sub_4_form,'old');"><img src="images/sub_button_54.jpg" /></a></div>
									<div class="div_float"><a href="javascript:void(0)" onclick="excel_insert(sub_4_form,'new');"><img src="images/sub_button_56.jpg" /></a></div>
									<p style="clear:both;"></p>
								</div>
								<div>
									<input type="text" style="width:250px;" placeholder="그룹명" name="group_name" value="<?= $_REQUEST['group_name'] ?>" />
									<input type="button" value="검색" style="height:32px;" onclick="sub_4_form.submit()" />
								</div>
								<div style="margin-bottom:5px;margin-top:5px;">
									<?
									$sql_serch = " mem_id ='{$_SESSION['one_member_id']}' ";
									if ($_REQUEST['group_name'])
										$sql_serch .= " and grp like '%{$_REQUEST['group_name']}%' ";

									$sql_serch .= " and grp != '아이엠' ";
									$sql = "select count(idx) as cnt from Gn_MMS_Group where $sql_serch ";
									$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
									$row = mysqli_fetch_array($result);
									$intRowCount = $row['cnt'];
									if (!$_POST['lno'])
										$intPageSize = 15;
									else
										$intPageSize = $_POST['lno'];
									if ($_POST['page']) {
										$page = (int)$_POST['page'];
										$sort_no = $intRowCount - ($intPageSize * $page - $intPageSize);
									} else {
										$page = 1;
										$sort_no = $intRowCount;
									}
									if ($_POST['page2'])
										$page2 = (int)$_POST['page2'];
									else
										$page2 = 1;
									$int = ($page - 1) * $intPageSize;
									if ($_REQUEST['order_status'])
										$order_status = $_REQUEST['order_status'];
									else
										$order_status = "desc";
									if ($_REQUEST['order_name'])
										$order_name = $_REQUEST['order_name'];
									else
										$order_name = "idx";
									$intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);
									$sql = "select * from Gn_MMS_Group where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
									$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
									?>
									<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td style="width:15%;"><label><input type="checkbox" onclick="check_all(this,'chk');group_choice()" />선택</label></td>
											<td style="width:40%;"><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'grp',sub_4_form.order_status.value)">그룹명<? if ($_REQUEST['order_name'] == "grp") {
																																											echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																										} else {
																																											echo '▼';
																																										} ?></a></td>
											<td style="width:20%;"><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'reg_date',sub_4_form.order_status.value)">날짜<? if ($_REQUEST['order_name'] == "reg_date") {
																																												echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																											} else {
																																												echo '▼';
																																											} ?></a></td>
											<td style="width:15%;"><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'count',sub_4_form.order_status.value)">인원</a><? if ($_REQUEST['order_name'] == "count") {
																																													echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																												} else {
																																													echo '▼';
																																												} ?></td>
											<td style="width:10%;">엑셀</td>
										</tr>
										<?
										if ($intRowCount) {
											$g = 0;
											while ($row = mysqli_fetch_array($result)) {
												$sql = "select count(idx) as cnt from Gn_MMS_Receive where grp_id = '{$row['idx']}' ";
												$sresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
												$srow = mysqli_fetch_array($sresult);
										?>
												<tr>
													<td><label><input type="checkbox" value="<?= $row['idx'] ?>" name="chk" id="chk" onclick="group_choice('<?= $g ?>')" /><?= $sort_no ?></label></td>
													<td class="group_title_<?= $g ?>">
														<a href="javascript:void(0)" onclick="show_detail('group_detail_elc.php?grp_id=<?= $row['idx'] ?>','<?= $g ?>')"><?= str_substr($row['grp'], 0, 20, "utf-8") ?></a>
														<a href="javascript:void(0)" onclick="g_dt_show_cencle('group_title_','','','<?= $g ?>')" class="a_btn" style="background-color:#4f81bd;color:#FFF;float:right;">Re</a>
													</td>
													<td class="group_title_<?= $g ?>" style="display:none;">
														<input type="text" name="group_title" value="<?= $row['grp'] ?>" style="width:65%;" />
														<a href="javascript:void(0)" onclick="group_title_modify('<?= $row['idx'] ?>','<?= $g ?>')" class="a_btn">저장</a>
														<a href="javascript:void(0)" onclick="g_dt_cencle('group_title_','','','<?= $g ?>')">x</a>
													</td>
													<td><?= substr($row['reg_date'], 2, 9) ?></td>
													<td><?= $srow['cnt'] ?></td>
													<td>
														<a href="javascript:void(0)" onclick="excel_down('excel_down/excel_down.php?down_type=1','<?= $row['idx'] ?>')"><img src="images/ico_xls.gif"></a>
														<a href="#" class="btn_option_white" onclick="frm_update(document.getElementById('group<?= $i ?>').value,document.getElementById('group_name<?= $i ?>').value)" id="upt<?= $i ?>" style="display:none;">수정</a>&nbsp;<a href="#" class="btn_option_red" onclick="frm_del(document.getElementById('group_name<?= $i ?>').value)" id="del<?= $i ?>" style="display:none;">삭제</a>
													</td>
												</tr>
											<?
												$g++;
												$sort_no--;
											}
											?>
											<tr>
												<td colspan="5">
													<?
													page_f($page, $page2, $intPageCount, "sub_4_form");
													?>
												</td>
											</tr>
										<?
										} else {
										?>
											<tr>
												<td colspan="5">
													내용이 없습니다.
												</td>
											</tr>
										<?
										}
										?>
									</table>
								</div>
								<div style="text-align:right">
									<a href="javascript:void(0)" id="down_excel"><img src="images/sub_button_107.jpg"></a>
									<a href="javascript:void(0)" onclick="all_group_del()"><img src="images/sub_button_39.jpg" /></a>
								</div>
							</div>
						</div>
						<div class="sub_4_2_t3">
							<div><img src="images/sub_button_007.jpg" /></div>
							<div class="div_280" style="margin-top:0px">
								<!--
                                <div style="margin:20px 0 20px 0;text-align:right;">
                                <a href="/sub_5.php">휴대폰 등록관리</a>
                                </div>
                                -->
								<div style="margin:0px 0 20px 0;height:1200px;overflow-y:auto;">
									<?
									$date = date("Y-m-d");
									$sql = "select send_num from Gn_MMS where mem_id='{$_SESSION['one_member_id']}' and DATE(reg_date)='{$date}' and  content like '%app_check_process%' and result=0 group by send_num,reg_date order by reg_date desc";
									$resul = mysqli_query($self_con, $sql);
									$ablable = mysqli_num_rows($resul);
									while ($row = mysqli_fetch_array($resul)) {
										$ableNum[$row['send_num']] = $row['send_num'];
									}



									$sql = "select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' order by sort_no asc, user_cnt desc , idx desc";
									$resul = mysqli_query($self_con, $sql);
									$intRowCount = mysqli_num_rows($resul);

									?>
									<div class="button_box">
										<div class="left_box">
											<span class="popup_holder button_type">
												<a href="javascript:void(0)" onclick="select_app_check_push('check_num')">앱 상태 체크</a>
												<div class="popupbox" style="display:none;left:0px; top:40px; height:114px; 	width:220px;margin-top:0px; line-height:1.5;">•발송 전에 현재 발송 가능한 폰을 확인합니다.<br>
													•체크횟수 : 하루 처음 발송 전 1회 체크하면 됩니다. 클릭하세요!<br>
													<a class="detail_view" style="color: blue;" href="https://tinyurl.com/3pra9rde" target="_blank">[자세히 보기]</a>
												</div>
											</span>
										</div>
										<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="background:#fff">
											<tr>
												<th style="background:#AED9FD;padding:5px;">발송가능폰</th>
												<th style="background:#AED9FD;padding:5px;margin-left:1px;">전체폰수</th>
											</tr>
											<tr>
												<td id="use_cnt"><?= number_format($ablable); ?></td>
												<td><?= number_format($intRowCount); ?></td>
											</tr>
										</table>
									</div>
									<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="position:relative;top:10px;">
										<tr>
											<td style="width:45%;text-align:left;"><label><input type="checkbox" onclick="check_all(this,'go_num');send_sj_fun()" />휴대폰 번호</label></td>
											<td style="width:25%;">이름</td>
											<td style="width:15%;">이번<br />발송</td>
											<td style="width:15%;text-align:left;"><label><input type="checkbox" onclick="check_all(this,'check_num');" />앱체크</label></td>
											<td style="width:15%;">상태</td>
										</tr>
										<tbody id="phone_list">
											<?
											if ($intRowCount) {
												$today_send_total = 0;
												$use_phone_cnt = 0;
												$today_reg = date("Y-m-d");
												while ($row = mysqli_fetch_array($resul)) {
													$row['user_cnt'] = $row['daily_limit_cnt_user'];

													$is_send = true;



													$sql_result2_g = "select SUM(recv_num_cnt) from Gn_MMS where send_num='{$row['sendnum']}' and ((reg_date like '$today_reg%' and reservation is null) or reservation like '$today_reg%')";
													$res_result2_g = mysqli_query($self_con, $sql_result2_g) or die(mysqli_error($self_con));
													$today_cnt_1 = 0;
													$row_result2_g = mysqli_fetch_array($res_result2_g);
													$today_cnt_1 += $row_result2_g[0] * 1;
													//echo $today_cnt_1;
													//$total_cnt = $row['daily_limit_cnt']; //기본 일별 총발송 가능량
													//$donation_rate = $row['donation_rate']; //기부 비율
													$total_cnt = $row['daily_limit_cnt']; //기본 일별 총발송 가능량
													$donation_rate = $row['donation_rate']; //기부 비율
													$donation_cnt = ceil($total_cnt * $donation_rate / 100); //기부 받은 수

													if ($mem_phone == $row['sendnum'] && ($member_1['mem_type'] == "V" || $member_1['mem_type'] == "")) {
														//    if($row['memo2'] == "SK") {
														//        // SKT
														//        $limitCnt = 3000;
														//    } else if($row['memo2'] == "KT") {
														//        // KT
														//        $limitCnt = 2000;
														//    } else if($row['memo2'] == "LG") {
														//        // LG
														$limitCnt = 1000000; // 무제한
														//    }
														//    $daily_cnt = $limitCnt * 0.01 * $row['donation_rate'];
														//    // 1일 발송 가능량
														//    $row['user_cnt'] = $daily_cnt - $today_cnt_1;
														$row['user_cnt'] = "무제한";
													}
													//    $row['user_cnt'] = $limitCnt-$today_cnt_1;
													//    $row['max_cnt']  = $limitCnt;
													//} else {
													//$row['user_cnt'] = $row['user_cnt'] - $today_cnt_1;


													$row['user_cnt'] = $row['user_cnt'] - $today_cnt_1;

													//echo $donation_cnt."=".$row['user_cnt']."=".$today_cnt_1."<BR>";

													//print_r($row);
													//echo $today_cnt_1;

													if ($row['user_cnt'] < 0) $row['user_cnt'] = 0;
													//}
													if ($row['daily_limit_cnt_user'] - $today_cnt_1 > $row['user_cnt']) {
														if ($row['cnt1'] >= 10 &&  $today_cnt_1 < $row['daily_min_cnt_user']) {
															//$row['user_cnt'] = 199;
															if ($row['user_cnt'] > $row['daily_min_cnt_user'] - $today_cnt_1)
																$row['user_cnt'] =     $row['daily_min_cnt_user'] - $today_cnt_1;
														} else {
															$row['user_cnt'] =     $row['daily_limit_cnt_user'] - $today_cnt_1;
														}
													}

													if ($row['user_cnt'] < 0) $row['user_cnt'] = 0;

													//if($row['cnt1'] >= 10 &&  $today_cnt_1 < 200) {
													//    if($row['user_cnt'] >= 199)
													//        $row['user_cnt'] = 199;
													//    //echo $ssh_cnt;
													//}
													//echo $row['user_cnt']."==";
													//echo "$row['max_cnt'] - $today_cnt_1<BR>";





													// =========== Cooper add 폰별 월 발송량 체크  Start ===========

													$query = "select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' and sendnum='" . $row['sendnum'] . "'";
													$result = mysqli_query($self_con, $query);

													$memo2 = $row['memo2'];
													$monthly_limit_ssh = $memo2 ? $agency_arr[$memo2] : 800; //월별 수신처 제한 수
													//$daily_cnt = $monthly_limit_ssh * 0.01 * $info['donation_rate']; // 기부비율에 맞춰 수정




													//이번 달 총 수신처 수
													$ssh_cnt = 0;


													$sql_ssh = "select recv_num from Gn_MMS where send_num='" . $row['sendnum'] . "' and (reg_date like '$date_month%' or reservation like '$date_month%')  group by(recv_num)";

													$result_ssh = mysqli_query($self_con, $sql_ssh);
													while ($row_ssh = mysqli_fetch_array($result_ssh)) {
														$ssh_arr = explode(",", $row_ssh['recv_num']);
														$ssh_numT = array_merge($ssh_numT, (array)$ssh_arr);
													}
													$ssh_arr = array_unique($ssh_numT);
													$ssh_cnt = count($ssh_arr);

													mysqli_free_result($result_ssh);
													// 200~500건 10번 이상 발송 경우 건수 조정
													//echo "$row['cnt1'] $row['user_cnt'] == ";


													//echo $row['user_cnt'];

													// 200~500건 10번 이상 발송 경우 건수 조정 금일 발송 기록이 있는경우 발송 불가
													//echo "$row['max_cnt'] - $ssh_cnt < $row['user_cnt'] == $monthly_limit_ssh<BR>";
													//if($monthly_limit_ssh - $ssh_cnt < $row['user_cnt']) {
													//    $row['user_cnt'] = $monthly_limit_ssh - $ssh_cnt;
													//}

													if ($mem_phone == $row['sendnum'] && ($member_1['mem_type'] == "V" || $member_1['mem_type'] == "")) {
														if ($row['memo2'] == "SK") {
															//        // SKT
															$limitCnt = 2000;
														} else if ($row['memo2'] == "KT") {
															//        // KT
															$limitCnt = 2000;
														} else if ($row['memo2'] == "LG") {
															//        // LG
															$limitCnt = 3000; // 무제한
														}
														//    $daily_cnt = $limitCnt * 0.01 * $row['donation_rate'];
														//    // 1일 발송 가능량
														$row['user_cnt'] = $limitCnt - $today_cnt_1;
														//$row['user_cnt'] = "무제한";
													}

													// 당일 발송회수가 1이면 한개를 더 차감으로 커멘트함
													// if($today_cnt_1 == 1)
													// 	$row['user_cnt'] = $row['user_cnt'] - $today_cnt_1;

													if ($member_1['mem_type'] == "V" && $mem_phone == $row['sendnum']) {
														$is_send = true;
														//$today_send_total+=$row['user_cnt'];
														$today_send_total += $row['user_cnt'];
														$send_status = "가능";
													} else if ($today_cnt_1 > $row['daily_limit_cnt_user']) {
														$row['user_cnt'] = 0;
														$is_send = false;
														$send_status = "<span style='color:red'>불가</span>";
													} else {
														$is_send = true;
														$today_send_total += $row['user_cnt'];
														$send_status = "가능";
													}

													// 200~500건 10번 이상 발송 경우 건수 조정 금일 발송 기록이 있는경우 발송 불가
													//echo "$row['user_cnt'] - $today_cnt_1<BR>";


													if ($row['cnt1'] > 10 && $today_cnt_1 > $row['daily_min_cnt_user'] - 1) {
														$row['user_cnt'] = 0;
														$is_send = false;
														$send_status = "<span style='color:red'>불가</span>";
													}

													$sql_s = "select * from Gn_MMS_status where send_num='{$row['sendnum']}' and  regdate like '$today_reg%' order by regdate desc limit 1";
													$resul_s = mysqli_query($self_con, $sql_s);
													$row_s = mysqli_fetch_array($resul_s);
													mysqli_free_result($resul_s);
													if ($row_s['status'] == "-1") {
														$is_send = false;
														$send_status = "<span style='color:red'>불가</span>";
													}

													//$remain_cnt = 0;
													//$remain_cnt = $monthly_limit_ssh - $ssh_cnt;


													//if($row['user_cnt'] > $remain_cnt) $row['user_cnt'] = $remain_cnt;
													//if($row['user_cnt'] < 0) $row['user_cnt'] = 0;

													//echo $ssh_cnt."/".$monthly_limit_ssh;
													// =========== Cooper add 폰별 월 발송량 체크  End ===========

													if ($ableNum[$row['sendnum']] == "") $is_send = false;

													if ($is_send == true) $use_phone_cnt++;
											?>
													<tr style="<?php if ($row_s['status'] == "-1") {
																	echo "background:#efefef";
																} ?>">
														<td style="text-align:left;">
															<label><input type="checkbox" name="go_num" class="<?= $row['sendnum'] ?>" value="<?= $row['sendnum'] ?>" <?= !$is_send ? "disabled" : "" ?> <?= $fujia_pay == "" && $row['sendnum'] != $mem_phone ? "disabled" : "" ?> onclick="send_sj_fun()" data-user_cnt="<?= $row['user_cnt'] ?>" data-send-cnt="<?= $ssh_cnt ?>" data-max-cnt="<?= $monthly_limit_ssh ?>" data-name="<?= $row['memo'] ?>" /><?= $row['sendnum'] ?></label>
															<input type="hidden" name="go_user_cnt" value="<?= $row['user_cnt'] ?>" />
															<input type="hidden" name="go_max_cnt" value="<?= $row['daily_limit_cnt_user'] ?>" />
															<input type="hidden" name="go_memo2" value="<?= $row['memo2'] ?>" />
															<input type="hidden" name="go_cnt1" value="<?= $row['cnt1'] ?>" />
															<input type="hidden" name="go_cnt2" value="<?= $row['cnt2'] ?>" />
															<input type="hidden" name="go_remain_cnt" value="<?= $remain_cnt ?>" />
														</td>
														<td><?= $row['memo'] ?></td>
														<td>
															<? if ($row['daily_limit_cnt_user'] >= 10000) { ?>
																<?= $today_cnt_1; ?> <?= $ssh_cnt; ?>
															<? } else { ?>

																<?= $row['user_cnt'] ?>
																<!--
				                                                 <?= $today_cnt_1; ?> <?= $ssh_cnt; ?>
				                                                 -->
															<? } ?>
														</td>
														<td style="text-align:center;">
															<input type="checkbox" name="check_num" value="<?= $row['sendnum'] ?>" />
														</td>
														<td><?= $send_status ?></td>
													</tr>
												<?
												}
												?>
												<tr>
													<td colspan="4" style="text-align:right;"><span class="send_sj_c">0</span> / <?= number_format($today_send_total) ?></td>
												</tr>
											<?
											} else {
											?>
												<tr>
													<td colspan="4">
														발송가능한 휴대폰이 없습니다.
													</td>
												</tr>
											<?
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<p style="clear:both;"></p>
					</div>
					<input type="hidden" name="order_name" value="<?= $order_name ?>" />
					<input type="hidden" name="order_status" value="<?= $order_status ?>" />
					<input type="hidden" name="page" value="<?= $page ?>" />
					<input type="hidden" name="page2" value="<?= $page2 ?>" />
				</form>

			</div>


		</div>
	</div>

	<div class="ad_layer1">
		<div class="layer_in">
			<span class="layer_close close"><img src="/images/close_button_05.jpg"></span>
			<div class="pop_title">
				치환문자 기능 사용안내
			</div>
			<div class="info_box">
				<!--
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
                -->
			</div>

			<div class="info_text">
				<p>
					치환문자 기능 사용안내<br /><br />

					치환문자 : 주소록에 있는 이름대로 수신문자에 발송됩니다<br />
					사용방법 : {|name|} 를 발송문안에 입력하면 <br />
					수신문자에 이름이 치환되어 보여집니다<br /><br />

					주소록에 이름이 있는 경우 <br />
					{|name|}님 안녕하세요^^ => 홍길동님 안녕하세요^^<br /><br />

					주소록에 이름이 없는 경우<br />
					{|name|}님 안녕하세요^^ => 9876님 안녕하세요^^<br />
					<img src="/images/replace_explain.jpg" width="378" />

				</p>
			</div>

			<div class="ad_layer7">
				<div class="layer_in">
					<span class="layer_close close"><img src="/images/close_button_05.jpg"></span>
					<div class="pop_title">
						문자입력
					</div>
					<div class="info_text">
						<p> 문자 내용 중에 <,>, $, ", " 기호를 사용하시면 발송내용이 특수문자열로 바뀌니 다른 기호를 사용해 주세요.
						</p>

					</div>

				</div>
			</div>

		</div>
	</div>
</div>

<form name="excel_down_form" action="" target="excel_iframe" method="post">
	<input type="hidden" name="grp_id" id="grp_id" value="" />
	<input type="hidden" name="box_text" value="" />
	<input type="hidden" name="excel_sql" value="<?= $excel_sql ?>" />
</form>
<iframe name="excel_iframe" style="display:none"></iframe>

<?
include_once "_foot.php";
?>
<script language="javascript" src="js/mms_send.2020_elc.js?<?= date("His") ?>"></script>
<script language="javascript">
	$(document).ready(function() {

		<?php
		if ($row_data['send_num']) {
		?>
			$("input[class='<?= $row_data['send_num'] ?>']").prop("checked", true);
		<? } ?>
	})
	$(".btn_addservice").on('click', function() {
		$("#add_service").hasClass('collapsed') ? $("#add_service").removeClass('collapsed') : $("#add_service").addClass('collapsed');
	});
	$('#use_cnt').html('<?= $use_phone_cnt ?>');
	$(function() {
		$('#agreement_yn').live("change", function() {
			if ($(this).is(":checked") == true) {
				$('#agreement_yn_span').html('ON').css('color', 'rgb(0, 0, 255)');

			} else {
				$('#agreement_yn_span').html('OFF').css('color', '');
			}
		});
	});

	function saveMessage() {
		if (confirm('작성중인 문자를 저장하시겠습니까?')) {
			frm = document.sub_4_form;
			var save_mms_s = "";
			save_mms_s = "ok";
			var txt_s = "";
			if (document.getElementsByName('fs_msg')[0].checked)
				txt_s = frm.txt.value + document.getElementsByName('onebook_url')[0].value + "\n\n" + document.getElementsByName('fs_txt')[0].value;
			else
				txt_s = frm.txt.value + document.getElementsByName('onebook_url')[0].value;

			$.ajax({
				type: "POST",
				url: "ajax/ajax_session.php",
				data: {
					send_save_mms: save_mms_s,
					send_onebook_status: frm.onebook_status.value,
					send_img: frm.upimage_str.value,
					send_img1: frm.upimage_str1.value,
					send_img2: frm.upimage_str2.value,
					send_title: frm.title.value,
					send_txt: txt_s
				},
				success: function(data) {
					var arrData = data.split('|');
					var msg = "";
					if ($.trim(data == "true"))
						alert('저장되었습니다.');
					else {
						alert('저장중 문제가 발생하였습니다.');
					}
				}
			})
		}
	}
	var election_cnt = "";
	var election_yn = false;
	var election_myphone = "<?= $mem_phone ?>";
	var freeChk = "<?= $freeChk ?>";

	function ml_view(name, c, t) {
		open_div(open_recv_div, 100, 1);
		var content = "";
		var contents = "";
		if (document.getElementsByName('fs_msg')[0].checked)
			content = document.getElementsByName(name)[c].value + document.getElementsByName('onebook_url')[c].value + "\n\n" + document.getElementsByName('fs_txt')[c].value;
		else
			content = document.getElementsByName(name)[c].value + document.getElementsByName('onebook_url')[c].value;

		if ($('[name=upimage_str]').val() != "") {
			contents += "<br><img src='" + $('[name=upimage_str]').val() + "' style='width:150px;height:150x;'>";
		}
		if ($('[name=upimage_str1]').val() != "") {
			contents += "<br><img src='" + $('[name=upimage_str1]').val() + "' style='width:150px;height:150x;'>";

		}
		if ($('[name=upimage_str2]').val() != "") {
			contents += "<br><img src='" + $('[name=upimage_str2]').val() + "' style='width:150px;height:150x;'>";

		}

		$($(".open_recv")[0]).html(content.replace(/\n/g, "<br/>") + contents);
		$($(".open_recv_title")[0]).html(t);
	}

	//수신거부
	function numchk(status) {
		var box_status = "";
		if (status != "4" && status != "5") {
			if (!document.getElementsByName('group_num')[0].value && !document.getElementsByName('num')[0].value) {
				alert('선택된 번호가 없습니다.');
				document.getElementsByName('deny_wushi[]')['status'].checked = false;
				return false;
			}
		}
		var go_num_arr = document.getElementsByName('go_num');
		var send_num_a = [];
		var send_num_s = "";
		for (var i = 0; i < go_num_arr.length; i++) {
			if (go_num_arr[i].checked)
				send_num_a.push("`" + go_num_arr[i].value + "`");
		}
		send_num_s = send_num_a.join(",");
		if (status == "5") {
			if (send_num_s == "") {
				alert('발송가능 휴대폰을 선택해주세요.');
				go_num_arr[0].focus();
				document.getElementsByName('ssh_check')[1].checked = false;
				$($('.deny_msg_span')[2]).html('OFF');
				$($('.deny_msg_span')[2]).css('color', '#F00');
				$($(".num_check_c")[8]).html('0');
				return false;
			}
		}
		if (document.getElementsByName('group_num')[0].value || document.getElementsByName('num')[0].value) {

			//2016-05-08 추가
			var deny_wushi_0_s = "";
			var deny_wushi_1_s = "";
			var deny_wushi_2_s = "";
			var deny_wushi_3_s = "";
			var deny_msg_s = "";
			var ssh_check_s = "";
			var ssh_check2_s = "";
			var ssh_check3_s = "";
			var go_num_name_arr = document.getElementsByName('go_num');
			var go_num_arr = [];
			var go_user_cnt_name_arr = document.getElementsByName('go_user_cnt');
			var go_user_cnt_arr = [];
			var go_max_cnt_name_arr = document.getElementsByName('go_max_cnt');
			var go_max_cnt_arr = [];
			var go_memo2_name_arr = document.getElementsByName('go_memo2');
			var go_memo2_arr = [];
			var go_cnt1_name_arr = document.getElementsByName('go_cnt1');
			var go_cnt1_arr = [];
			var go_cnt2_name_arr = document.getElementsByName('go_cnt2');
			var go_cnt2_arr = [];
			var go_remain_name_arr = document.getElementsByName('go_remain_cnt');
			var go_remain_arr = [];
			for (var i = 0; i < go_num_name_arr.length; i++) {
				if (go_num_name_arr[i].checked) {
					go_num_arr.push(go_num_name_arr[i].value);
					go_user_cnt_arr.push(go_user_cnt_name_arr[i].value);
					go_max_cnt_arr.push(go_max_cnt_name_arr[i].value);
					go_memo2_arr.push(go_memo2_name_arr[i].value);
					go_cnt1_arr.push(go_cnt1_name_arr[i].value);
					go_cnt2_arr.push(go_cnt2_name_arr[i].value);
					go_remain_arr.push(go_remain_name_arr[i].value);
				}
			}

			if (document.getElementsByName('deny_wushi[]')[0].checked)
				deny_wushi_0_s = "ok";
			if (document.getElementsByName('deny_wushi[]')[1].checked)
				deny_wushi_1_s = "ok";
			if (document.getElementsByName('deny_wushi[]')[2].checked)
				deny_wushi_2_s = "ok";
			if (document.getElementsByName('deny_wushi[]')[3].checked)
				deny_wushi_3_s = "ok";
			if (document.getElementsByName('deny_msg')[0].checked)
				deny_msg_s = "ok";
			if (document.getElementsByName('ssh_check')[0].checked)
				ssh_check_s = "ok";
			if (document.getElementsByName('ssh_check')[1].checked)
				ssh_check2_s = "ok";
			if (document.getElementsByName('ssh_check')[2].checked)
				ssh_check3_s = "ok";

			$($(".loading_div")[0]).show();
			$.ajax({
				type: "POST",
				url: "/ajax/ajax_session.php",
				data: {
					send_rday: sub_4_form.rday.value,
					send_htime: sub_4_form.htime.value,
					send_mtime: sub_4_form.mtime.value,

					num_check_grp_id: document.getElementsByName('group_num')[0].value,
					num_check_num2: document.getElementsByName('num')[0].value,
					num_check_send_num: send_num_s,
					num_check_status: status,
					num_check_go: "ok",
					send_deny_wushi_0: deny_wushi_0_s,
					send_deny_wushi_1: deny_wushi_1_s,
					send_deny_wushi_2: deny_wushi_2_s,
					send_deny_wushi_3: deny_wushi_3_s,
					send_ssh_check: ssh_check_s,
					send_ssh_check2: ssh_check2_s,
					send_ssh_check3: ssh_check3_s,
					send_go_user_cnt: go_user_cnt_arr,
					send_go_memo2: go_memo2_arr

				},
				success: function(data) {
					$($(".loading_div")[0]).hide();
					$("#ajax_div").html(data)
				}
			})
		}
	}
	<? if ($total_cnt >= 0) { ?>
		election_cnt = <?= $total_cnt ?>;
		election_yn = true;
	<? } ?>
</script>
<?
if ($_SESSION['one_member_admin_id'] != "") {
?>
	<Script>
		chk_time = true;
	</Script>

<?
}
?>