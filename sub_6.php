<?
header("Progma:no-cache");
$path = "./";
include_once "_head.php";
?>
<!-- <script language="javascript">
	alert('공지사항\n지금 문자발송 기능을 업그레이드 하는 중입니다.\n수정되는대로 공지하겠습니다.감사합니다');
	history.back();
</script>
-->
<? if (!$_SESSION['one_member_id']) { ?>
	<script language="javascript">
		location.replace('/ma.php');
	</script>
<?
	exit;
} else {
	echo "test18<br>";
	$date_today = date("Y-m-d");
	$date_month = date("Y-m");
	$trialLimit = date("Y-m-d 23:59:59", strtotime($member_1['first_regist'] . "-1 days")); //회원가입일+3일
	$sql = "select phone_cnt from tjd_pay_result where buyer_id = '{$_SESSION['one_member_id']}' and end_date > '$date_today' and end_status in ('Y','A') and gwc_cont_pay=0 order by end_date desc";
	$res_result = mysqli_query($self_con, $sql);
	echo "test24<br>";
	//결제 휴대폰 수
	$buyPhoneCnt = mysqli_fetch_row($res_result);
	mysqli_free_result($res_result);
	if ($buyPhoneCnt[0] == 0) {	//유료결제건
		$buyMMSCount = 0;
	} else {
		$buyMMSCount = $buyPhoneCnt[0];
	}
	//무료제공건(선거용은 3일제한)
	if ($buyMMSCount == 0) {
		$freeMMSCount = 0; //100;
		$freeChk = "Y";
		$fujia_pay = "Y";
	} else {
		$freeMMSCount = 0; //100;
		$freeChk = "";
		$fujia_pay = "Y";
	}
	echo "test43<br>";
	// 16-01-20 예약발송 건 전송 제한 30분
	// 1) reservation ~ 30분까지만 가져간다. 
	// 2) 시간 지난 것은 실패 처리 : Gn_MMS_ReservationFail로 이동,result = 3
	$sql_where = "where now() > adddate(reservation,INTERVAL 30 Minute) and result = 1 and mem_id = '{$_SESSION['one_member_id']}'";
	$sql = "select * from Gn_MMS $sql_where";
	$res = mysqli_query($self_con, $sql);
	echo "test50<br>";
	if (mysqli_num_rows($res) > 0) {
		while ($fail_row = mysqli_fetch_assoc($res)) {
			$sql = "insert into Gn_MMS_ReservationFail set mem_id = {$fail_row['mem_id']},
															send_num= {$fail_row['send_num']},
															recv_num= {$fail_row['recv_num']},
															uni_id= {$fail_row['uni_id']},
															content= {$fail_row['content']},
															title= {$fail_row['title']},
															type= {$fail_row['type']},
															delay= {$fail_row['delay']},
															delay2= {$fail_row['delay2']},
															close= {$fail_row['close']},
															jpg= {$fail_row['jpg']},
															result= {$fail_row['result']},
															reg_date= {$fail_row['reg_date']},
															up_date= {$fail_row['up_date']},
															url= {$fail_row['url']},
															reservation= {$fail_row['reservation']}";
			echo $sql;
			mysqli_query($self_con, $sql);
		}
	}
	$sql = "update Gn_MMS_ReservationFail set result = 3 $sql_where";
	echo $sql;
	mysqli_query($self_con, $sql);
	echo "test57<br>";
	//수신처수는 당월 차감 / 발송 수는 당일 차감
	//오늘 예약 건 확인
	$reserv_cnt_today = 0;
	$sql_result2 = "select SUM(recv_num_cnt) as cnt from Gn_MMS where reservation like '$date_today%' and up_date is null and mem_id = '{$_SESSION['one_member_id']}' ";
	$res_result2 = mysqli_query($self_con, $sql_result2);
	$row_result2 = mysqli_fetch_array($res_result2);
	$reserv_cnt_today += $row_result2[0] * 1;
	mysqli_free_result($res_result2);
	echo "test66<br>";
	//-이번달 예약건 수
	$reserv_cnt_thismonth = 0;
	$sql_result = "select SUM(recv_num_cnt) as cnt from Gn_MMS where reservation like '$date_month%' and up_date is null and mem_id = '{$_SESSION['one_member_id']}' ";
	$res_result = mysqli_query($self_con, $sql_result);
	$row_result = mysqli_fetch_array($res_result);
	$reserv_cnt_today += $row_result[0] * 1;
	mysqli_free_result($res_result);
	echo "test74<br>";
	//-이번달 발송된 수
	$recv_num_ex_sum = 0;
	$sql_result = "select SUM(recv_num_cnt) as cnt from Gn_MMS where reg_date like '$date_month%' and mem_id = '{$_SESSION['one_member_id']}' ";
	$res_result = mysqli_query($self_con, $sql_result);
	$row_result = mysqli_fetch_array($res_result);
	$recv_num_ex_sum += $row_result[0] * 1;
	mysqli_free_result($res_result);
	$recv_num_ex_sum += $reserv_cnt_thismonth; //이번 달 예약된 수 추가
	echo "test82<br>";
	//-오늘발송 건 수
	$rec_cnt_today = 0;
	$sql_result2 = "select SUM(recv_num_cnt) as cnt from Gn_MMS where reg_date like '$date_today%' and mem_id = '{$_SESSION['one_member_id']}' ";
	$res_result2 = mysqli_query($self_con, $sql_result2);
	$row_result2 = mysqli_fetch_array($res_result2);
	$rec_cnt_today += $row_result2[0] * 1;
	mysqli_free_result($res_result2);
	echo "test91<br>";
	//-이번발송 $uni_id
	$rec_cnt_current = 0;
	$sql_result3 = "select uni_id from Gn_MMS use index(gn_mms_mem_id) where mem_id = '{$_SESSION['one_member_id']}' order by idx desc";
	$res_result3 = mysqli_query($self_con, $sql_result3);
	$row_result3 = mysqli_fetch_array($res_result3);
	$uni_id = substr($row_result3['uni_id'], 0, 10);
	mysqli_free_result($res_result3);
	echo "test99<br>";
	//마지막 발송 건수
	$sql_result32 = "select SUM(recv_num_cnt) as cnt from Gn_MMS where mem_id = '{$_SESSION['one_member_id']}' and uni_id like '$uni_id%'";
	$res_result32 = mysqli_query($self_con, $sql_result32);
	$row_result32 = mysqli_fetch_array($res_result32);
	$rec_cnt_current += $row_result32[0] * 1;
	mysqli_free_result($res_result32);
	echo "test106<br>";
	//-마지막발송일
	$sql_result4 = "select reg_date from Gn_MMS use index(gn_mms_mem_id) where mem_id = '{$_SESSION['one_member_id']}' order by reg_date desc";
	$res_result4 = mysqli_query($self_con, $sql_result4);
	$row_result4 = mysqli_fetch_row($res_result4);
	if ($row_result4 == 0) {
		$last_reg_date = "-";
	} else {
		$last_reg_date = date("Y.m.d", strtotime($row_result4[0]));
	}
	mysqli_free_result($res_result4);
	echo "test117<br>";
	//이달 제공건
	$thiMonTotCnt = $freeMMSCount + $buyMMSCount;
	//이달잔여건
	$thiMonleftCnt = $thiMonTotCnt - $recv_num_ex_sum;
}
$hour = date("H");
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
echo "test142<br>";
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
echo "test161<br>";
?>
<link href='/css/sub_4_re.css' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="jquery.lightbox_me.js"></script>
<!--패치-->
<!--<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>
-->
<script src="/iam/js/layer.min.js" type="application/javascript"></script>
<script src="/iam/js/chat.js"></script>
<script>
	function showImage(cursor) {
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

	function onSave(frm) {
		if (login_check(frm)) {
			var addr = $("#txt_mail_address").val();
			if (!addr.match(/^[\w.%+\-]+@[\w.\-]+\.[A-Za-z]{2,3}$/)) {
				alert("유효한 메일형식이 아닙니다.");
				$("#txt_mail_address").focus();
				return;
			}
			if ($("#chk_save").prop("checked")) {
				$.ajax({
					type: "POST",
					url: "ajax/mail_msg_func.php",
					data: {
						mode: "add",
						mail_addr: $("#txt_mail_address").val(),
						mail_title: $("#txt_mail_title").val(),
						mail_content: $("#txt_mail_content").val(),
						attach_file: $("#link_attachment").val()
					},
					success: function(data) {
						//alert('저장되었습니다.');
					}
				});
			}
			$("#is_send_mail").val("Y");
			hide_mail_box();
		}
	}

	function onSaveMailAddress() {
		if ($("#txt_mail_address").val() == "") {
			alert('메일주소를 입력해 주세요');
			$("#txt_mail_address").focus();
			return;
		}
		var addr = $("#txt_mail_address").val();
		if (!addr.match(/^[\w.%+\-]+@[\w.\-]+\.[A-Za-z]{2,3}$/)) {
			alert("유효한 메일형식이 아닙니다.");
			$("#txt_mail_address").focus();
			return;
		}

		if (confirm("메일을 등록하시겠습니까?")) {
			$.ajax({
				type: "POST",
				url: "ajax/mail_address_func.php",
				data: {
					mode: "add",
					mail_addr: $("#txt_mail_address").val()
				},
				success: function(data) {
					alert("메일이 등록되었습니다.");
				}
			});
		}
	}

	function onLoadMailAddresses() {
		window.open("mail_address_list.php", "", "scrollbars=yes,resizable=yes,top=200,left=200,width=500,height=600");
	}
	$(function() {
		if ($("#group_num").val() || $("#num").val()) {
			<? if ($_REQUEST['deny_wushi']) { ?>
				numchk('4');
			<? } ?>
		}

		$('#down_excel').bind("click", function() {
			if ($('.num_check_c:eq(1)').html() * 1 > 10000) {
				alert('다운로드는 총건수 만건 이하만 지원합니다.');
			} else {
				$('#grp_id').val($("#group_num").val());
				$('#down_type').val('1');
				excel_down('excel_down/excel_join_down.php');
			}
		});
		$('input[name=type]').change(function() {
			if ($(this).val() == 0) {
				$("#delay").show();
			} else {
				$("#delay").hide();
			}
		});
	})

	function show_mail_box() {
		var coord = $("#btn_mail").position();
		$("#modal_mail").css({
			"top": coord.top - 400,
			"left": coord.left + 30
		});
		$("#modal_mail").show();
		$("#tutorial-loading").show();
	}

	function hide_mail_box() {
		$("#modal_mail").hide();
		$("#tutorial-loading").hide();
	}

	function hide_cnt_box() {
		$("#modal_cnt_contact").hide();
		$("#tutorial-loading").hide();
	}

	function hide_intro_box() {
		$("#modal_intro_function").hide();
	}

	function show_intro_func() {
		$("#modal_intro_function").show();
	}

	function group_choice_betw(g) {
		var name_arr = document.getElementsByName('chk');
		if (name_arr[g].checked) {
			$("#grp_no").val(g);
			$("#modal_cnt_contact").show();
			$("#tutorial-loading").show();
			$('body,html').animate({
				scrollTop: 50,
			}, 100);
		} else {
			group_choice(g, "N", 0, 0);
		}
	}

	function group_choice_1(val) {
		var g = $("#grp_no").val();
		var start = $("#count_start").val();
		var end = $("#count_end").val();
		if (val == "Y" && (start == '' || end == '')) {
			alert("시작 종료건수를 입력해주세요.");
			return;
		}
		group_choice(g, val, start, end);
		$("#modal_cnt_contact").hide();
		$("#tutorial-loading").hide();
		numchk('4');
	}
	//gpt chat script
	function show_chat(api) {
		$("#gpt_chat_modal").show();
		$("#tutorial-loading").show();
		$('body,html').animate({
			scrollTop: 250,
		}, 100);
	}

	var contextarray = [];

	$(document).ready(function() {
		var textarea = document.getElementById("question");
		var limit = 100; //height limit
		var api_state = '<?= $member_1['gpt_chat_api_key'] ?>';

		textarea.oninput = function() {
			textarea.style.height = "";
			textarea.style.height = Math.min(textarea.scrollHeight - 17, limit) + "px";
		};

		$("#question").on('keydown', function(event) {
			if (api_state == '') {
				alert("회원정보에서 본인의 API 키를 입력해주세요.");
				location.href = "mypage.php";
			}
			if (event.keyCode == 13) {
				if (event.shiftKey) {
					$("#kw-target").html($("#kw-target").html() + "\n");
					event.stopPropagation();
				} else {
					send_post('<?= $_SESSION['iam_member_id'] ?>');
				}
			}
		});
	});

	function check_login(id) {
		if (id == '') {
			$("#intro_modal").modal('show');
		} else {
			return;
		}
	}

	function show_new_chat() {
		$("#answer_side").hide();
		$("#gpt_req_list_title").hide();
		$("#answer_side1").show();
		$("#answer_side2").hide();
	}

	function show(val) {
		if ($('li[id=a' + val + ']').hasClass('hided')) {
			$('li[id=a' + val + ']').removeClass('hided');
			$('i[id=down' + val + ']').css('display', 'none');
			$('i[id=up' + val + ']').css('display', 'inline-block');
		} else {
			$('li[id=a' + val + ']').addClass('hided');
			$('i[id=down' + val + ']').css('display', 'inline-block');
			$('i[id=up' + val + ']').css('display', 'none');
		}
	}

	function show_req_history() {
		$.ajax({
			type: "POST",
			url: "/iam/ajax/manage_gpt_chat.php",
			data: {
				mem_id: "<?= $_SESSION['iam_member_id'] ?>",
				method: 'show_req_list'
			},
			dataType: 'html',
			success: function(data) {
				// console.log(data);
				$("#answer_side").hide();
				$("#answer_side1").hide();
				$("#gpt_req_list_title").show();
				$("#answer_side2").html(data);
				$("#answer_side2").show();
			}
		});
	}

	function copy_msg() {
		var value = $("#answer_side").text().trim();
		console.log(value.trim());
		// return;
		var aux1 = document.createElement("input");
		// 지정된 요소의 값을 할당 한다.
		aux1.setAttribute("value", value);
		// bdy에 추가한다.
		document.body.appendChild(aux1);
		// 지정된 내용을 강조한다.
		aux1.select();
		// 텍스트를 카피 하는 변수를 생성
		document.execCommand("copy");
		// body 로 부터 다시 반환 한다.
		document.body.removeChild(aux1);
		alert("복사되었습니다. 원하는 곳에 붙여 넣으세요.");
	}

	function del_list(id) {
		$.ajax({
			type: "POST",
			url: "/iam/ajax/manage_gpt_chat.php",
			data: {
				method: 'del_req_list',
				id: id
			},
			dataType: 'json',
			success: function(data) {
				if (data.result == "1") {
					alert('삭제 되었습니다.');
					show_req_history();
				} else {
					alert('삭제실패 되었습니다.');
				}
			}
		});
	}

	function articlewrapper(question, answer, str) {
		$("#answer_side").html('<li class="article-title" id="q' + answer + '"><img src="/iam/img/chat_Q.png" style="width:30px;margin-right: 10px;"><span class="chat_title"></span></li>');
		let str_ = ''
		let i = 0
		let timer = setInterval(() => {
			if (str_.length < question.length) {
				str_ += question[i++]
				$("#q" + answer).children('span').text(str_ + '_') //인쇄할 때 커서 추가
			} else {
				clearInterval(timer)
				$("#q" + answer).children('span').text(str_) //인쇄할 때 커서 추가
			}
		}, 5)
		$("#answer_side").append('<li class="article-content" id="' + answer + '"><img src="/iam/img/chat_A.png" style="width:30px;"><span class="chat_answer" style="margin-left: 35px;"></span></li>');
		if (str == null || str == "") {
			str = "서버가 응답하는 데 시간이 걸리면 나중에 다시 시도할 수 있습니다.";
		}
		let str2_ = ''
		let i2 = 0
		let timer2 = setInterval(() => {
			if (str2_.length < str.length) {
				str2_ += str[i2++]
				$("#" + answer).children('span').text(str2_ + '_') //인쇄할 때 커서 추가
			} else {
				clearInterval(timer2)
				$("#" + answer).children('span').text(str2_) //인쇄할 때 커서 추가

			}

			$('#answer_side').animate({
				scrollTop: 10000,
			}, 10);
		}, 25)
	}

	function randomString(len) {
		len = len || 32;
		var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678'; /****혼란스러운 문자는 기본적으로 제거됩니다oOLl,9gq,Vv,Uu,I1****/
		var maxPos = $chars.length;
		var pwd = '';
		for (i = 0; i < len; i++) {
			pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
		}
		return pwd;
	}

	function send_chat() {
		var title = $("#answer_side span.chat_title").text();
		var detail = $("#answer_side span.chat_answer").text();
		if (title == "") {
			alert('질문해주세요.');
			return;
		}
		$("input[name=title]").val(title);
		$("#txt").val(detail);
		$("#gpt_chat_modal").hide();
		$("#tutorial-loading").hide();
		$('body,html').animate({
			scrollTop: 2500,
		}, 100);
	}

	function hide_gpt_box() {
		$("#gpt_chat_modal").hide();
		$("#tutorial-loading").hide();
	}
</script>

<style>
	#ajax-loading {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 9000;
		text-align: center;
		display: none;
		background-color: #fff;
		opacity: 0.8;
	}

	#ajax-loading img {
		position: absolute;
		top: 50%;
		left: 50%;
		width: 120px;
		height: 120px;
		margin: -60px 0 0 -60px;
	}

	.tooltiptext-bottom {
		width: 420px;
		font-size: 15px;
		background-color: white;
		color: black;
		text-align: left;
		position: absolute;
		z-index: 200;
		top: 1370px;
		left: 750px;
	}

	.tooltiptext-bottom1 {
		width: 420px;
		font-size: 15px;
		background-color: white;
		color: black;
		text-align: left;
		position: absolute;
		z-index: 200;
		top: 400px;
		left: 35%;
	}

	.tooltiptext-bottom2 {
		width: 720px;
		font-size: 15px;
		background-color: white;
		color: black;
		text-align: left;
		position: absolute;
		z-index: 200;
		top: 400px;
		left: 30%;
	}

	.title_app {
		text-align: center;
		background-color: rgb(247, 131, 116);
		padding: 10px;
		font-size: 20px;
		color: white;
		font-weight: 900;
	}

	.desc_app {
		padding: 15px;
	}

	.button_app {
		text-align: center;
		padding: 10px;
	}

	@media only screen and (max-width: 450px) {
		.tooltiptext-bottom {
			width: 80%;
			left: 8%;
		}

		.tooltiptext-bottom1 {
			width: 80%;
			left: 8%;
		}
	}

	#tutorial-loading {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		z-index: 150;
		text-align: center;
		display: none;
		background-color: grey;
		opacity: 0.7;
	}

	.chat_btn {
		color: white;
		border-radius: 7px;
		background-color: red;
		font-size: 12px;
		float: right;
		border-color: red;
		padding: 4px 0px;
		margin-right: 3px;
	}

	#answer_side,
	#answer_side1,
	#answer_side2 {
		width: 90%;
		height: 400px;
		background-color: white;
		margin-right: auto;
		margin-left: auto;
		border-radius: 10px;
		margin-top: 12px;
		padding: 35px 30px 10px 30px;
		overflow: auto;
		text-align: left;
		position: relative;
	}

	.search_keyword {
		position: relative;
		width: 99%;
		margin: 0 auto;
		margin-top: 10px;
	}

	.search_keyword textarea {
		width: 78%;
		height: 50px;
		padding: 17px 60px 0 25px;
		border-width: 0;
		border-radius: 15px;
		font-size: 15px;
		border: 2px solid transparent;
		background-color: #fff;
		outline-width: 0;
		box-shadow: 0 5px 10px -5px rgb(0 0 0 / 30%);
		-webkit-transition: border-color 1000ms ease-out;
		transition: border-color 1000ms ease-out;
	}

	.send_ask {
		position: absolute;
		top: 0;
		right: 0;
		width: 58px;
		height: 100%;
		left: 90%;
		background-color: white;
		border-radius: 20px;
		border: none;
	}

	#gpt_req_list_title {
		float: left;
		padding: 7px;
		margin-left: 40px;
		background-color: #f18484;
		border-radius: 10px;
	}

	.history {
		position: absolute;
		top: 5px;
		left: 80px;
	}

	.gpt_act {
		position: relative;
		height: 35px;
	}

	.newpane,
	.newpane:hover {
		background-color: black;
		color: white !important;
		padding: 4px;
		border-radius: 10px;
		position: absolute;
		top: 5px;
		right: 80px;
	}

	@media only screen and (max-width: 720px) {
		.send_ask {
			position: absolute;
			top: 0;
			right: 0;
			width: 50px;
			height: 100%;
			left: 90%;
			background-color: white;
			border-radius: 20px;
			border: none;
		}
	}

	@media only screen and (max-width: 600px) {
		.send_ask {
			position: absolute;
			top: 0;
			right: 0;
			width: 50px;
			height: 100%;
			left: 88%;
			background-color: white;
			border-radius: 20px;
			border: none;
		}

		.chat_btn {
			color: white;
			border-radius: 7px;
			background-color: red;
			font-size: 15px;
			padding: 5px 20px;
		}
	}

	@media only screen and (max-width: 450px) {
		.send_ask {
			position: absolute;
			top: 0;
			right: 0;
			width: 50px;
			height: 98%;
			left: 85%;
			background-color: white;
			border-radius: 20px;
		}

		.history {
			position: absolute;
			top: 5px;
			left: 35px;
		}

		.newpane {
			background-color: black;
			color: white !important;
			padding: 4px;
			border-radius: 10px;
			position: absolute;
			top: 5px;
			right: 40px;
		}

		.chat_btn {
			color: white;
			border-radius: 7px;
			background-color: red;
			font-size: 15px;
			padding: 5px 20px;
		}
	}

	.chat_answer {
		word-break: break-all;
		word-wrap: break-word;
		white-space: pre-wrap;
	}

	.article-title {
		border-bottom: 1px solid lightgrey;
		margin-bottom: 15px;
		font-size: 15px;
		text-align: left;
	}

	.article-content {
		display: grid;
		margin-bottom: 15px;
		font-size: 15px;
		text-align: left;
	}

	.hided {
		display: none;
	}

	.copy_msg {
		position: absolute;
		right: 10px;
		top: 10px;
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
				</div>
				<br>
				<form name="sub_4_form" id="sub_4_form" action="" method="post" enctype="multipart/form-data">
					<div>
						<div class="sub_4_2_t1">
							<div><img src="images/sub_button_03.jpg" /></div>
							<div class="div_240">
								<div class="a1">
									<div class="b1">발송번호선택</div>
									<div class="b2 select_group_div" style="display: none;">그룹번호</div>
									<div class="div_2px" style="display: none;">
										<textarea name="group_num" id="group_num" itemname='그룹번호' placeholder="그룹번호" onblur="numchk('4')"><?= $_REQUEST['group_num'] ?></textarea>
									</div>
									<div class="b2">개별번호
										<button style="float:right;font-size:12px;padding-left:10px;padding-right:10px;background:#155e80;color:white;" onclick="numchk('4')">확인</button>
									</div>
									<div class="div_2px">
										<textarea name="num" id="num" itemname='전화번호' placeholder="번호구분은 콤마(,)로 해주세요.입력이 끝나면 확인을 클릭해주세요." onblur="numchk('4')"><?= $_REQUEST['num'] ?></textarea>
									</div>

									<div class="b4">
										<div class="c1" style="display: flex;border-bottom:1px solid #ddd">
											<div style="margin-left:0;">총건수(<span class="num_check_c">0</span>)</div>
											<div>실제발송(<span class="num_check_c">0</span>)</div>
											<div>발송제외(<a href="javascript:void(0)" onclick="show_recv('deny_num','4','발송제외')" class="num_check_c">0</a>)</div>
											<label style="float:right;font-size:16px;font-weight: bold;transform: scaleX(2);" id="address_arrow" onclick="click_address_arrow();">&#x02C5;</label>
										</div>

										<div id="send_address_pad" style="display: none;">
											<div class="div_2px">
												<div style="float:left; display:none;" id="delay">
													<input type="text" name="delay" placeholder="발송간격" value="<?= $_REQUEST['delay'] ? $_REQUEST['delay'] : 5 ?>" onblur="send_delay(sub_4_form,this,1)" onkeyup="send_delay(sub_4_form,this,1)" />~
													<input type="text" name="delay2" placeholder="발송간격" value="<?= $_REQUEST['delay'] ? $_REQUEST['delay'] : 15 ?>" onblur="send_delay(sub_4_form,this,2)" />초
												</div>
												<div style="float:left;clear:both; display:none">
													<input type="text" id="close" name="close" placeholder="발송제한" value="<?= $_REQUEST['close'] ? $_REQUEST['close'] : 24 ?>" onblur="limitNight();" />시(20시)
													<input type="checkbox" value="Y" id="time_limit" checked> 제한해제
												</div>
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
											<div class="div_2px" style="">
												<label><input type="radio" name="type" value="1" checked />묶음발송</label>
												<label><input type="radio" name="type" value="0" />개별발송</label>

											</div>
										</div>
									</div>
								</div>
								<div class="a2">
									<div class="b1" style="display: flex;justify-content: space-between;border-bottom:1px solid #ddd">
										<div class="popup_holder" style="cursor:pointer;margin:0px;" ;>부가서비스
											<?= $fujia_pay ? "" : "<a href='pay.php'>(결제후 사용가능합니다.)</a>" ?>
											<div class="popupbox" style="display:none; height: 85px;width: 214px; left: 87px; top: -10px; line-height:1.5;">
												국내 유일의 수신처 기능 등 문자 발송시 다양한 서비스를 무료로 사용가능합니다. <br>
												<a style="color: blue;" href="https://tinyurl.com/5n84rewk" target="_blank">[자세히 보기]</a>
											</div>
										</div>
										<label style="float:right;font-size:16px;font-weight: bold;transform: scaleX(2);" id="additional_arrow" onclick="click_additional_arrow();">&#x02C5;</label>
									</div>
									<div id="additional_pad" style="display: none;">
										<div class="b2">
											<div class="popup_holder">
												<div style="float:left;font-size:12px;">
													<label><input type="checkbox" <?= $fujia_pay ? "" : "disabled" ?> name="deny_wushi[]" checked onclick="numchk('3');type_check()" />수신거부제외
														(<a href="javascript:void(0)" onclick="show_recv('deny_num','3','수신거부옵션으로 발송제외 되는번호')" class="num_check_c">0</a>)</label>
												</div>
												<div class="popupbox" style="left: 50px; top: 20px; height: 18px; width: 200px; background: white; display: none;">
													수신거부한 디비를 제외하고 발송하는 기능입니다.<br>
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
										<input type="file" name="upimage" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=&target=upimage_str';sub_4_form.target='excel_iframe';sub_4_form.submit();" accept=".jpg,.jpeg,.png,.gif" />
										<div><input type="hidden" name="upimage_str" /></div>
										<input type="file" name="upimage1" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=1&target=upimage_str1';sub_4_form.target='excel_iframe';sub_4_form.submit();" accept=".jpg,.jpeg,.png,.gif" />
										<div><input type="hidden" name="upimage_str1" /></div>
										<input type="file" name="upimage2" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=2&target=upimage_str2';sub_4_form.target='excel_iframe';sub_4_form.submit();" accept=".jpg,.jpeg,.png,.gif" />
									</div>
									<div><input type="hidden" name="upimage_str2" /></div>
								</div>
								<div>
									<? if ($fujia_pay == "Y") { ?>
										<a href="javascript:void(0)" style="display:none" onclick="window.open('msg_serch.php?status=1&status2=2','msg_serch','top=0,left=0,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no, status=no')">
											<img src="images/btn_send_help1.gif" />
										</a>
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

								<div class="a3">
									<div class="b1" style="display: flex;justify-content: space-between;border-bottom:1px solid #ddd">
										<div style="margin:0px">예약서비스</div>
										<label style="float:right;font-size:16px;font-weight: bold;transform: scaleX(2);" id="reserve_arrow" onclick="click_reserve_arrow();">&#x02C5;</label>
									</div>
									<div id="reserve_pad" style="display: none;">
										예약발송일:
										<input type="date" <?= $fujia_pay ? "" : "disabled" ?> name="rday" onfocus="type_check();" onblur="check_date('<?= date('Ymd') ?>')" placeholder="예약발송(일)" id="rday" value="<?= $_REQUEST['rday'] ?>" style="width:130px;" />
										<br>
										<select name="htime" style="width:50px;" <?= $fujia_pay ? "" : "disabled" ?>>
											<?
											for ($i = 0; $i < 22; $i++) {
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
											for ($i = 0; $i < 60; $i += 10) {
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
									<div class="b1">
										문자입력<!--<span class="popbutton7 pop_view pop_right">?</span>-->
										<div class="pull-right">
											<span onclick="input_replace_char();" style="font-size:12px;cursor:pointer">치환하기</span>
											<span class="popbutton1" style="font-size:12px;cursor:pointer">(?)</span>
										</div>
										<button onclick="show_chat('<?= $member_1['gpt_chat_api_key'] ?>')" class="chat_btn">AI와 대화하기</button>
									</div>

									<div class="b2">
										<div class="div_2px"> </div>
									</div>
									<div class="div_2px"><input type="text" class="replace_tab" name="title" id="title" itemname='제목' required placeholder="제목" style="width:100%;" value="<?= $_REQUEST['title'] ?>" /></div>
									<div class="div_2px">
										<textarea class="replace_tab" name="txt" style="height:150px" itemname='내용' id='txt' required placeholder="내용" onkeydown="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);" onkeyup="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();" onfocus="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();"><?= $_REQUEST['txt'] ?></textarea>
										<input type="hidden" name="onebook_status" value="N" />
										<input type="hidden" name="onebook_url" value="" />
									</div>
									<div class="div_2px" style="height:28px;line-height:28px;">
										<div style="float:left;">
											<a href="javascript:ml_view('txt','0','미리보기')">미리보기</a>
										</div>
										<div style="float:right;">
											<span class="wenzi_cnt">0</span> byte
										</div>
									</div>
									<!--<div class="div_2px" style="display:flex">-->
									<div class="div_2px" style="display:none">
										<div class="type_icon"><img src="images/sub_04_02-2_btn_50.jpg" title="문자입력기능 작동중" /></div>
										<div class="type_icon"><img src="images/sub_04_02-2_btn_52.jpg" title="포토입력기능 작동중" /></div>
										<div class="type_icon"><img src="images/sub_04_02-2_btn_54.jpg" title="원북입력기능 작동중" /></div>
										<div class="type_icon"><img src="images/sub_04_02-2_btn_56.jpg" title="수신제외기능 작동중" /></div>
										<div class="type_icon"><img src="images/sub_04_02-2_btn_58.jpg" title="발송제한기능 작동중" /></div>
										<div class="type_icon"><img src="images/sub_04_02-2_btn_60.jpg" title="예약발송기능 작동중" /></div>
										<p style="clear:both;"></p>
									</div>
									<div class="div_2px">
										<div>
											<div style="float:left;height:20px">
												<a href="javascript:saveMessage()">
													메시지저장</a>
											</div>
											<div style="float:right;margin-left:30px;">
												<label><input type="checkbox" name="fs_msg" id="fs_msg" onclick="deny_msg_click(this,4)" />발신전용 표시</label>
												<input type="hidden" name="fs_txt" value="이 번호는 문자발신전용 번호이오니 관련문의는 위의 안내전화를 이용해주시기바랍니다." />
												<!--<div class="deny_msg_span">OFF</div>-->
											</div>
										</div>
										<div>
											<div style="float:left;height:20px">
												<a href="javascript:window.open('msg_serch.php?status=1&status2=1','msg_serch','top=0,left=0,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no, status=no')">
													보관함보기</a>
											</div>
											<div style="float:right;margin-left:30px;">
												<label><input type="checkbox" name="agreement_yn" id="agreement_yn" value="Y" <?= $_REQUEST['agreement_yn'] ? "checked" : "" ?> />수신동의 표시</label>
												<!--<div class="deny_msg_span " id="agreement_yn_span">OFF</div>-->
											</div>
										</div>
										<div>
											<div style="float:left;height:20px">
												<a href="javascript:show_mail_box()" id="btn_mail">
													이메일입력</a>
											</div>
											<div style="float:right;margin-left:30px;">
												<label><input type="checkbox" name="save_mms" value="Y" <?= $_REQUEST['save_mms'] ? "checked" : "" ?> />발송후에 저장</label>
											</div>
										</div>
									</div>
								</div>
								<div>
									<a href="javascript:send_msg_new(sub_4_form);">
										<img src="images/sub_button_68.jpg" />
									</a>
								</div>
							</div>
						</div>
						<div class="sub_4_2_t2">
							<div><img src="images/sub_button_05.jpg" /></div>
							<div class="div_340">
								<div class="d_font popup_holder" style="width:150px;float:left;margin-top:0px; cursor:pointer;">
									엑셀 업로드
									<div class="popupbox" style="display:none; left:30px; top:30px; height: 84px;width: 214px; line-height:1.5;">
										보유한 DB파일을 업로드하는 기능입니다.
										꼭 샘플을 다운받아서 엑셀작업 하세요.<br>
										<a style="color: blue;" href="https://tinyurl.com/yk6mv66k" target="_blank">[자세히 보기]</a>
									</div>
									<a href="javascript:void(0)" onclick="excel_down('excel_down/excel_down.php?down_type=2')" class="a_btn_2">샘플</a>
								</div>
								<div style="float:right;" class="popup_holder">
									<a href="javascript:excel_down_c_group('<?= $mem_phone ?>')" style="border:1px solid #000;padding:5px">폰주소 업로드</a>
									<div class="popupbox" style="display:none; left:30px; top:30px; height: 85px;width: 218px; line-height:1.5;">여기를 클릭하면 자신의 폰 연락처 디비를 가져와서 자동으로 주소록을 만들어줍니다.<br>
										<a style="color: blue;" href="https://tinyurl.com/5fw7xju6" target="_blank">[자세히 보기]</a>
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
											<td style="width:15%;">
												<label><input type="checkbox" onclick="check_all(this,'chk');group_choice()" />선택</label>
											</td>
											<td style="width:40%;">
												<a href="javascript:order_sort(sub_4_form,'grp',sub_4_form.order_status.value)">그룹명<? if ($_REQUEST['order_name'] == "grp") {
																																		echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																	} else {
																																		echo '▼';
																																	} ?></a>
											</td>
											<td style="width:20%;">
												<a href="javascript:order_sort(sub_4_form,'reg_date',sub_4_form.order_status.value)">날짜<? if ($_REQUEST['order_name'] == "reg_date") {
																																			echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																		} else {
																																			echo '▼';
																																		} ?></a>
											</td>
											<td style="width:15%;">
												<a href="javascript:order_sort(sub_4_form,'count',sub_4_form.order_status.value)">인원</a><? if ($_REQUEST['order_name'] == "count") {
																																			echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																		} else {
																																			echo '▼';
																																		} ?>
											</td>
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
													<td>
														<label>
															<input type="checkbox" value="<?= $row['idx'] ?>" data="<?= $srow['cnt'] ?>" name="chk" id="chk" onchange="group_choice_betw('<?= $g ?>')" />
															<?= $sort_no ?>
														</label>
													</td>
													<td class="group_title_<?= $g ?>">
														<a href="javascript:show_detail('group_detail.php?grp_id=<?= $row['idx'] ?>','<?= $g ?>')"><?= str_substr($row['grp'], 0, 20, "utf-8") ?></a>
														<a href="javascript:g_dt_show_cencle('group_title_','','','<?= $g ?>', '')" class="a_btn" style="background-color:#4f81bd;color:#FFF;float:right;">Re</a>
													</td>
													<td class="group_title_<?= $g ?>" style="display:none;">
														<input type="text" name="group_title" value="<?= $row['grp'] ?>" style="width:65%;" />
														<a href="javascript:group_title_modify('<?= $row['idx'] ?>','<?= $g ?>')" class="a_btn">저장</a>
														<a href="javascript:g_dt_cencle('group_title_','','','<?= $g ?>')">x</a>
													</td>
													<td><?= substr($row['reg_date'], 2, 9) ?></td>
													<td><?= $srow['cnt'] ?></td>
													<td>
														<a href="javascript:excel_down('excel_down/excel_down.php?down_type=1','<?= $row['idx'] ?>')"><img src="images/ico_xls.gif"></a>
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
									<a href="javascript:all_group_del()"><img src="images/sub_button_39.jpg" /></a>
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
									$sql = "select count(idx) from Gn_MMS where mem_id='{$_SESSION['one_member_id']}' and DATE(reg_date)='{$date}' and  content like '%app_check_process%' and result=0 group by send_num,reg_date order by reg_date desc";
									$resul = mysqli_query($self_con, $sql);
									$row = mysqli_fetch_array($resul);
									$ablable = $row[0];

									$sql = "select send_num from Gn_MMS where mem_id='{$_SESSION['one_member_id']}' and DATE(reg_date)='{$date}' and  content like '%app_check_process%' and result=0 group by send_num,reg_date order by reg_date desc";
									$resul = mysqli_query($self_con, $sql);
									while ($row = mysqli_fetch_array($resul)) {
										$ableNum[$row['send_num']] = $row['send_num'];
									}
									$sql = "select count(idx) from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' order by sort_no asc, user_cnt desc , idx desc";
									$resul = mysqli_query($self_con, $sql);
									$row = mysqli_fetch_array($resul);
									$intRowCount = $row[0];
									$sql = "select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' order by sort_no asc, user_cnt desc , idx desc";
									$resul = mysqli_query($self_con, $sql);
									?>
									<div class="button_box" style="">
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
										<div class="right_box">
											<span class="popup_holder button_type">
												<a href="javascript:void(0)" onclick="select_app_check_push('check_num')">앱 상태 체크</a>
												<div class="popupbox" style="display:none;left:0px; top:40px; height:114px; 	width:220px;margin-top:0px; line-height:1.5;">•발송 전에 현재 발송 가능한 폰을 확인합니다.<br>
													•체크횟수 : 하루 처음 발송 전 1회 체크하면 됩니다. 클릭하세요!<br>
													<a class="detail_view" style="color: blue;" href="https://tinyurl.com/fjdzys6p" target="_blank">[자세히 보기]</a>
												</div>
											</span>
										</div>
									</div>
									<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="position:relative;top:10px;">
										<thead>
											<tr>
												<td style="width:35%;text-align:left;"><label><input type="checkbox" onclick="check_all(this,'go_num');send_sj_fun()" />발송폰선택</label></td>
												<td style="width:25%;">이름</td>
												<td style="width:15%;">이번<br>발송</td>
												<td style="width:15%;text-align:left;"><label><input type="checkbox" onclick="check_all(this,'check_num');" />폰선택</label></td>
												<td style="width:10%;">상태</td>
											</tr>
										</thead>
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
													$total_cnt = $row['daily_limit_cnt']; //기본 일별 총발송 가능량
													$donation_rate = $row['donation_rate']; //기부 비율
													$donation_cnt = ceil($total_cnt * $donation_rate / 100); //기부 받은 수

													if ($mem_phone == $row['sendnum'] && ($member_1['mem_type'] == "V" || $member_1['mem_type'] == "")) {
														$limitCnt = 1000000; // 무제한
														$row['user_cnt'] = "무제한";
													}
													$row['user_cnt'] = $row['user_cnt'] - $today_cnt_1;
													if ($row['user_cnt'] < 0) $row['user_cnt'] = 0;
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
													// =========== Cooper add 폰별 월 발송량 체크  Start ===========
													// $query = "select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' and sendnum='".$row['sendnum']."'";
													// $result = mysqli_query($self_con,$query);

													$memo2 = $row['memo2'];
													$monthly_limit_ssh = $memo2 ? $agency_arr[$memo2] : 800; //월별 수신처 제한 수
													//이번 달 총 수신처 수
													$ssh_cnt = 0;
													$sql_ssh = "select recv_num from Gn_MMS where send_num='{$row['sendnum']}' and (reg_date like '$date_month%' or reservation like '$date_month%')  group by recv_num";
													$result_ssh = mysqli_query($self_con, $sql_ssh);
													if (mysqli_num_rows($result_ssh)) {
														$ssh_numT = array();
														while ($row_ssh = mysqli_fetch_array($result_ssh)) {
															$ssh_arr = explode(",", $row_ssh['recv_num']);
															$ssh_numT = array_merge($ssh_numT, (array)$ssh_arr);
														}
														$ssh_arr = array_unique($ssh_numT);
														$ssh_cnt = count($ssh_arr);
														mysqli_free_result($result_ssh);
														unset($ssh_numT);
													}
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
													$sql_s = "select status,regdate from Gn_MMS_status where send_num='{$row['sendnum']}' order by regdate desc";
													$resul_s = mysqli_query($self_con, $sql_s);
													$row_s = mysqli_fetch_array($resul_s);
													mysqli_free_result($resul_s);
													if ($row_s['status'] == "-1") {
														$rdate = date("Y-m-d", strtotime($row_s['regdate']));
														if ($rdate == $today_reg) {
															$is_send = false;
															$send_status = "<span style='color:red'>불가</span>";
														}
													}
													if ($ableNum[$row['sendnum']] == "") {
														$is_send = false;
													}
													if ($is_send == true)
														$use_phone_cnt++;
											?>
													<tr style="<?php if ($row_s['status'] == "-1") {
																	echo "background:#efefef";
																} ?>">
														<td style="text-align:left;">
															<label>
																<input type="checkbox" name="go_num" value="<?= $row['sendnum'] ?>" <?= !$is_send ? "disabled" : "" ?> <?= $fujia_pay == "" && $row['sendnum'] != $mem_phone ? "disabled" : "" ?> onclick="send_sj_fun()" data-user_cnt="<?= $row['user_cnt'] ?>" data-send-cnt="<?= $ssh_cnt ?>" data-max-cnt="<?= $monthly_limit_ssh ?>" data-name="<?= $row['memo'] ?>" /><?= $row['sendnum'] ?>
															</label>
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
													<td colspan="5" style="text-align:right;">
														<span class="send_sj_c">0</span> / <?= number_format($today_send_total) ?>
													</td>
												</tr>
											<? } else { ?>
												<tr>
													<td colspan="5">
														발송가능한 휴대폰이 없습니다.
													</td>
												</tr>
											<? } ?>
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
	<span class="tooltiptext-bottom" id="modal_mail" style="display:none;">
		<p class="title_app">메일 정보 입력 <span onclick="hide_mail_box()" style="float:right;cursor:pointer;">X</span></p>
		<form name="mail_form" action="ajax/mail_send.php" method="post" enctype="multipart/form-data">
			<div><input type="hidden" id="link_attachment" name="link_attachment" value="<?php echo $row['jpg1']; ?>" /></div>
			<input type="hidden" id="is_send_mail" name="is_send_mail" value="N" />
			<table class="table table-bordered" style="width: 97%;">
				<tbody>
					<tr class="hide_spec">
						<td colspan="3" style="padding:20 20px;">
							<div>
								<input type="text" name="txt_mail_address" id="txt_mail_address" style="border: solid 1px #b5b5b5;width:79%;height:34px" itemname='발신 이메일' required placeholder="발신 이메일 입력"></input>
								<a href="javascript:onLoadMailAddresses()"><img src="./images/icon-open.png" style="width:32px;height: 32px;"></a>
								<a href="javascript:onSaveMailAddress()"><img src="./images/icon-save.png" style="width:32px;height: 32px;"></a>
							</div>
						</td>
					</tr>

					<tr class="hide_spec">
						<td colspan="3" style="padding:20 20px;">
							<div>
								<input type="text" name="txt_mail_title" id="txt_mail_title" style="border: solid 1px #b5b5b5;width:100%;height:34px" itemname='메일 제목' required placeholder="메일 제목"></input>
							</div>
						</td>
					</tr>

					<tr class="hide_spec">
						<td colspan="3" style="padding:20 20px;">
							<div>
								<textarea name="txt_mail_content" id="txt_mail_content" style="border: solid 1px #b5b5b5;width:100%; height:150px;" itemname='메시지' data-num="0" required placeholder="메시지 입력"></textarea>
							</div>
						</td>
					</tr>
					<tr class="hide_spec">
						<td colspan="3" style="padding:20 20px;">
							<div>
								<span>★메일 주소가 등록된 디비에만 이메일이 발송됩니다.</span>
							</div>
						</td>
					</tr>
					<tr>
						<td style="width:20%">[파일등록]</td>
						<td style="width:50%">
							<input type="file" name="file_attach" style="width:200px" onChange="mail_form.action='upload_file.php?target=link_attachment';mail_form.target='excel_iframe';mail_form.submit();" />
						</td>
						<td style="width: 30%;">
							<div style="float: left;"><label><input type="checkbox" style="padding:5px" id="chk_save" name="chk_save" />메시지 저장</label></div>
						</td>
					</tr>
				</tbody>
			</table>
			<div style="margin: 10px;width:100%">
				<span style="text-align:center;width:45%;display:inline-block"><a href="javascript:void(0)" style="color:red;padding-right:20px" onclick="window.open('mail_msg_list.php','mail_msg_list','top=100,left=120,width=1145,height=632,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no, status=no')">
						메시지 불러오기</a></span>
				<span style="text-align:center;width:45%;display:inline-block"><a href="javascript:void(0)" style="width:50%;color:red;padding-right:20px" onclick="onSave(document.mail_form)">
						발송창으로 가기 </a></span>
			</div>
		</form>
	</span>
	<span class="tooltiptext-bottom1" id="modal_cnt_contact" style="display:none;">
		<p class="title_app">발송건수 선택<a style="border: 1px solid white;padding:0px 10px;cursor: pointer;border-radius: 15px;margin: 7px; height: 20px;color: white;" href="javascript:show_intro_func()">i</a><span onclick="hide_cnt_box()" style="float:right;cursor:pointer;">X</span></p>
		<input type="hidden" id="grp_no" name="grp_no" value="" />
		<table style="width:100%;">
			<tbody>
				<tr class="hide_spec">
					<td colspan="3" style="padding:20 20px;">
						<div style="padding: 7px;width: 100%;">
							<input type="number" name="count_start" id="count_start" style="border: solid 1px #b5b5b5;width:45%;height:34px" min="1" required placeholder="시작건"></input>
							-
							<input type="number" name="count_end" id="count_end" style="border: solid 1px #b5b5b5;width:45%;height:34px" min="1" required placeholder="종료건"></input>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<div style="margin: 10px;width:100%">
			<span style="text-align:center;width:45%;display:inline-block"><a href="javascript:void(0)" style="color:red;padding-right:20px" onclick="group_choice_1('N')">
					전체</a></span>
			<span style="text-align:center;width:45%;display:inline-block"><a href="javascript:void(0)" style="width:50%;color:red;padding-right:20px" onclick="group_choice_1('Y')">
					확인 </a></span>
		</div>
	</span>
	<span class="tooltiptext-bottom1" id="modal_intro_function" style="display:none;">
		<p class="title_app">발송건수 분할발송 기능 안내<span onclick="hide_intro_box()" style="float:right;cursor:pointer;">X</span></p>
		<input type="hidden" id="grp_no" name="grp_no" value="" />
		<p style="padding:20px;">
			1. 핵심기능 : 한개의 연락처 파일이 발송건수보다 많아 분할이 필요한 경우 시작건과 종료건을 입력하여 발송하는 기능.<br>
			2. 이용조건 : 발송폰의 가능 건수보다 연락처 건수가 많을 경우.<br>
			3. 이용방법<br>
			1) 연락처 클릭시 노출되는 팝업에 시작건-종료건 입력.<br>
			2) 분할발송 기능 미사용시 전체 버튼 클릭.<br>
			3) 여러개 연락처를 클릭할 경우 전체 선택후 미사용 연락처만 해지.<br>
			4. 이용절차와 사례<br>
			1) 이용조건 확인 : 연락처 건수가 5천건이고, 오늘 발송가능건수가 1천건일 경우, 남은 건을 5일간 보내야 할 경우에 사용한다.<br>
			2) 발송폰에서 발송가능건수가 1천건인지 확인한다.<br>
			3) 연락처 파일을 선택하고 1-1000을 입력한다. <br>
			4) 이전처럼 메시지를 발송한다.
		</p>
	</span>
	<span class="tooltiptext-bottom2" id="gpt_chat_modal" style="display:none;">
		<p class="title_app">콘텐츠 창작AI 알지(ALJI) <span onclick="hide_gpt_box()" style="float:right;cursor:pointer;">X</span></p>
		<div class="container" style="text-align: center;padding: 10px;background-color: lightgrey;">
			<p><img src="/iam/img/arji_intro_title.png" style="width: 22px;margin-right: 3px;">"알지(ALJI)" 인공지능에게 무엇이든 물어보세요.<br>구체적으로 질문할수록 "알지 AI" 답변이 정교해집니다.</p>
			<p id="gpt_req_list_title" hidden>질문답변목록</p>
			<ul id="answer_side" hidden>
				<a class="copy_msg" href="javascript:copy_msg()"><img src="/iam/img/gpt_res_copy.png" style="height:20px;"></a>
			</ul>
			<ul id="answer_side1">
				<?
				$gpt_qu = get_search_key('gpt_question_example');
				$gpt_an = get_search_key('gpt_answer_example');
				$gpt_qu_arr = explode("||", $gpt_qu);
				$gpt_an_arr = explode("||", $gpt_an);
				for ($i = 0; $i < count($gpt_qu_arr); $i++) {
				?>
					<li class="article-title" id="q<?= $i ?>" onclick="show('<?= $i ?>')"><img src="/iam/img/chat_Q.png" style="width:30px;margin-right: 10px;"><span class="chat_title"><?= htmlspecialchars_decode($gpt_qu_arr[$i]) ?></span><i id="down<?= $i ?>" class="fa fa-angle-down" style="font-size: 20px;font-weight: bold;margin-left: 10px;"></i><i id="up<?= $i ?>" class="fa fa-angle-up" style="font-size: 20px;font-weight: bold;margin-left: 10px;display:none;"></i></li>
					<li class="article-content hided" id="a<?= $i ?>"><img src="/iam/img/chat_A.png" style="width:30px;"><span class="chat_answer" style="margin-left: 35px;"><?= htmlspecialchars_decode($gpt_an_arr[$i]) ?></span></li>
				<? } ?>
			</ul>
			<ul id="answer_side2" hidden>
			</ul>
			<div class="gpt_act">
				<a class="history" href="javascript:show_req_history();"><img src="/iam/img/gpt_req_list.png" style="height: 25px;"></a>
				<a class="newpane" href="javascript:show_new_chat();"><span style="font-size: 5px;">NEW</a>
			</div>
			<div class="search_keyword">
				<input type="hidden" name="key" id="key" value="<?= $member_1['gpt_chat_api_key'] ?>">
				<textarea class="search_input" autocomplete="off" name="question" id="question" value="" title="질문을 입력하세요" placeholder="알지AI에게 질문해보세요" onclick="check_login('<?= $_SESSION['iam_member_id'] ?>')"></textarea>
				<button type="button" onclick="send_post('<?= $_SESSION['iam_member_id'] ?>')" class="send_ask"><img src="/iam/img/send_ask.png" alt="전송"></button>
			</div>
		</div>
		<div style="background-color: lightgrey;text-align: center;padding: 7px;">
			<button type="button" style="width:50%;background:#82C836;color:white;padding:10px 0px;border:none;" onclick="send_chat()">보내기</button>
		</div>
	</span>
	<iframe name="excel_iframe" style="display:none"></iframe>
	<div id="tutorial-loading"></div>
	<div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>
	<div class="ad_layer1">
		<div class="layer_in">
			<span class="layer_close close"><img src="/images/close_button_05.jpg"></span>
			<div class="pop_title">
				문자 메시지 치환 기능 안내
			</div>
			<div class="info_text">
				<b>
					<p style="font-size: 14px">
						1. 치환기능<br />
						주소록에 있는 이름,이메일이 수신문자에 입력됩니다.<br /><br />
						2. 사용방법<br />
						1) 주소록이름을 치환: 문자메시지내에 {|name|} 를 입력하면 <br />
						수신문자에 이름이 치환되어 보여집니다. 주소록에 이름이 <br />
						없으면 폰뒷번호가 입력됩니다. <br />
						2) 이메일주소를 치환: 문자메시지내에 {|email|} 을 발송문안<br />
						에 입력하면 수신문자에 이메일주소가 치환되어 보여집니다.<br />
						주소록에 이메일이 없으면 해당 문자는 발송되지 않습니다.<br /><br />
						3. 사용예제<br />
					<table class="table table-bordered" style="font-size:12px;width: 100%;">
						<tbody>
							<tr>
								<td style="width:43%">
									발송문자
									<div style="padding:5px;margin-top:10px; border: 2px solid #7698d4;">
										안녕하세요?<br />
										{|name|} 님!<br />
										고객님의 {|email|} 이메일로<br />
										자료를 보냈습니다.<br />
										검토하고 연락주세요<br />
										감사합니다.<br />
									</div>
								</td>
								<td style="width:14%;vertical-align:middle;text-align:center;">
									<img src="/images/sub_arrow.png" style="width:50px;height:40px">
								</td>
								<td style="width:43%">
									수신문자
									<div style="padding:5px;margin-top:10px; border: 2px solid #7698d4;">
										안녕하세요?<br />
										홍길동 님!<br />
										고객님의 abc@naver.com<br />
										이메일로 자료를 보냈습니<br />
										다. 검토하고 연락주세요<br />
										감사합니다.<br />
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					</p>
				</b>
			</div>

			<div class="ad_layer7">
				<div class="layer_in">
					<span class="layer_close close"><img src="/images/close_button_05.jpg"></span>
					<div class="pop_title">
						문자입력
					</div>
					<div class="info_text">
						<p> 문자 내용 중에 <,>, $, ", " 기호를 사용하시면 발송내용이 특수문자열로 바뀌니 다른 기호를 사용해 주세요.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--form name="excel_down_form" action="" target="excel_iframe" method="post">
	<input type="hidden" name="grp_id" id="grp_id" value="" />
	<input type="hidden" name="box_text" value="" />
	<input type="hidden" name="excel_sql" value="<?= $excel_sql ?>" />
</form-->
<iframe name="excel_iframe" style="display:none"></iframe>
<? include_once "_foot.php"; ?>
<script language="javascript" src="js/mms_send.2020.js?<?= date("His") ?>"></script>
<script language="javascript">
	$('#use_cnt').html('<?= $use_phone_cnt ?>');
	$(function() {
		$('#agreement_yn').on("change", function() {
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

		if ($('input[name=upimage_str]').val() != "") {
			contents += "<br><img src='" + $('input[name=upimage_str]').val() + "' style='width:150px;height:150x;'>";
		}
		if ($('input[name=upimage_str1]').val() != "") {
			contents += "<br><img src='" + $('input[name=upimage_str1]').val() + "' style='width:150px;height:150x;'>";

		}
		if ($('input[name=upimage_str2]').val() != "") {
			contents += "<br><img src='" + $('input[name=upimage_str2]').val() + "' style='width:150px;height:150x;'>";
		}
		//console.log($('[name=upimage_str]').val());
		$($(".open_recv")[0]).html(content.replace(/\n/g, "<br/>") + contents);
		$($(".open_recv_title")[0]).html(t);
	}

	//수신거부
	function numchk(status) {
		var box_status = "";
		if (status != "4" && status != "5") {
			if (!$("#group_num").val() && !$("#num").val()) {
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
		if ($("#group_num").val() || $("#num").val()) {
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
					num_check_grp_id: $("#group_num").val(),
					num_check_num2: $("#num").val(),
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

	function click_address_arrow() {
		if ($("#send_address_pad").css('display') == "none") {
			$("#send_address_pad").show();
			$("#address_arrow").html('&#x02C4;');
		} else {
			$("#send_address_pad").hide();
			$("#address_arrow").html('&#x02C5;');
		}
	}

	function click_additional_arrow() {
		if ($("#additional_pad").css('display') == "none") {
			$("#additional_pad").show();
			$("#additional_arrow").html('&#x02C4;');
		} else {
			$("#additional_pad").hide();
			$("#additional_arrow").html('&#x02C5;');
		}
	}

	function click_reserve_arrow() {
		if ($("#reserve_pad").css('display') == "none") {
			$("#reserve_pad").show();
			$("#reserve_arrow").html('&#x02C4;');
		} else {
			$("#reserve_pad").hide();
			$("#reserve_arrow").html('&#x02C5;');
		}
	}

	function input_replace_char() {
		$("#txt").val($("#txt").val() + "{|name|}");
		$("#title").val($("#title").val() + "{|name|}");
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