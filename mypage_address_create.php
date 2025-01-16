<?
$path = "./";
include_once "_head.php";
extract($_REQUEST);
if (!$_SESSION['one_member_id']) {

?>
	<script language="javascript">
		location.replace('/ma.php');
	</script>
<?
	exit;
}
$sql = "select * from Gn_Member  where mem_id='" . $_SESSION['one_member_id'] . "'";
$sresul_num = mysqli_query($self_con, $sql);
$data = mysqli_fetch_array($sresul_num);

if ($data['intro_message'] == "") {
	$data['intro_message'] = "안녕하세요\n
\n
귀하의 휴대폰으로\n
기부문자발송을 시작합니다.\n
\n
협조해주셔서 감사합니다^^
";
}

$mem_phone = str_replace("-", "", $data['mem_phone']);



$sql = "select * from Gn_event_request  where request_idx='" . $request_idx . "'";
$sresul_num = mysqli_query($self_con, $sql);
$data = $row = mysqli_fetch_array($sresul_num);

$sql = "select * from Gn_event where event_idx='{$row['event_idx']}' order by event_idx desc";
$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$event_data = $row = mysqli_fetch_array($result);
?>
<script>
	function copyHtml() {
		//oViewLink = $( "ViewLink" ).innerHTML;
		////alert ( oViewLink.value );
		//window.clipboardData.setData("Text", oViewLink);
		//alert ( "주소가 복사되었습니다. \'Ctrl+V\'를 눌러 붙여넣기 해주세요." );
		var trb = $.trim($('#sHtml').html());
		var IE = (document.all) ? true : false;
		if (IE) {
			if (confirm("이 소스코드를 복사하시겠습니까?")) {
				window.clipboardData.setData("Text", trb);
			}
		} else {
			temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", trb);
		}

	}
	$(function() {
		$(".popbutton").click(function() {
			$('.ad_layer_info').lightbox_me({
				centered: true,
				onLoad: function() {}
			});
		});

		$('#cancleBtn').on("click", function() {
			location = "mypage_request_list.php";
		});


	});
</script>
<style>
	.pop_right {
		position: relative;
		right: 2px;
		display: inline;
		margin-bottom: 6px;
		width: 5px;
	}

	.w200 {
		width: 200px;
	}

	.list_table1 tr:first-child td {
		border-top: 1px solid #CCC;
	}

	.list_table1 tr:first-child th {
		border-top: 1px solid #CCC;
	}

	.list_table1 td {
		height: 40px;
		border-bottom: 1px solid #CCC;
	}

	.list_table1 th {
		height: 40px;
		border-bottom: 1px solid #CCC;
	}

	.list_table1 input[type=text] {
		width: 600px;
		height: 30px;
	}

	.info_box_table input[type=text] {
		width: 600px;
		height: 30px;
	}

	.info_box_table th {
		height: 40px;
		border-bottom: 1px solid #CCC;
		width: 200px !important;
	}
</style>

<div class="big_sub">

	<?php include_once "mypage_step_navi.php"; ?>

	<div class="m_div">
		<?php include_once "mypage_left_menu.php"; ?>
		<div class="m_body">


			<input type="hidden" name="page" value="<?= $page ?>" />
			<input type="hidden" name="page2" value="<?= $page2 ?>" />
			<div class="a1" style="margin-top:15px; margin-bottom:15px">
				<li style="float:left;">디비예약문자 등록</li>
				<li style="float:right;"></li>
				<p style="clear:both"></p>
			</div>
			<form name="sform" id="sform" action="mypage.proc.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="mode" value="<?php echo $_REQUEST['address_idx'] != "" ? "address_event_update" : "address_event_add" ?>" />
				<input type="hidden" name="request_idx" value="<?php echo $request_idx; ?>" />
				<input type="hidden" name="org_event_code" value="<?php echo $row['sp']; ?>" />
				<input type="hidden" name="event_idx" id="event_idx" value="" />
				<input type="hidden" name="address_idx" id="address_idx" value="" />
				<input type="hidden" name="address_cnt" id="address_cnt" value="" />

				<div class="p1">
					<table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<th class="w200">주소록명</th>
							<td><input type="text" name="address_name" placeholder="" id="address_name" value="<?= $data['name'] ?>" style="width:200px" /> <input type="button" value="주소록 조회" class="button " id="searchAddressBtn"></td>
						</tr>
						<tr>
							<th class="w200">이벤트</th>
							<td>
								<input type="text" name="sp" placeholder="" id="event_name_eng" value="<?= $data['sp'] ?>" style="width:200px" /> <input type="button" value="이벤트 조회" class="button " id="searchBtn">
							</td>
						</tr>
					</table>
				</div>
				<div style="text-align:center;margin-top:10px">
					<input type="button" value="저장" class="button " id="saveBtn">
					<input type="button" value="취소" class="button" id="cancleBtn">
				</div>
			</form>

		</div>

	</div>
	<Script>
		function newpop() {
			var win = window.open("mypage_pop_link_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
		}

		function newpopAddress() {
			var win = window.open("mypage_pop_address_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
		}
		$(function() {

			$('#searchAddressBtn').on("click", function() {
				newpopAddress();
			});
			$('#searchBtn').on("click", function() {
				newpop();
			});
		})

		function change_message(form) {
			if (form.intro_message.value == "") {
				alert('정보를 입력해주세요.');
				form.intro_message.focus();
				return false;
			}

			$.ajax({
				type: "POST",
				url: "ajax/ajax.php",
				data: {
					mode: "intro_message",
					intro_message: form.intro_message.value
				},
				success: function(data) {
					$("#ajax_div").html(data);
					alert('저장되었습니다.');
				}
			});
			return false;
		}

		function showInfo() {
			if ($('#outLayer').css("display") == "none") {
				$('#outLayer').show();
			} else {
				$('#outLayer').hide();
			}
		}
	</Script>


	<script>
		//회원가입체크
		function join_check(frm, modify) {
			if (!wrestSubmit(frm))
				return false;
			var id_str = "";
			var app_pwd = "";
			var web_pwd = "";
			var phone_str = "";
			if (document.getElementsByName('pwd')[0])
				app_pwd = document.getElementsByName('pwd')[0].value;
			if (document.getElementsByName('pwd')[1])
				web_pwd = document.getElementsByName('pwd')[1].value;
			if (frm.id)
				id_str = frm.id.value;
			var msg = modify ? "수정하시겠습니까?" : "등록하시겠습니까?";
			var email_str = frm.email_1.value + "@" + frm.email_2.value + frm.email_3.value;
			if (!modify)
				phone_str = frm.mobile_1.value + "-" + frm.mobile_2.value + "-" + frm.mobile_3.value;
			var birth_str = frm.birth_1.value + "-" + frm.birth_2.value + "-" + frm.birth_3.value;
			var is_message_str = frm.is_message.checked ? "Y" : "N";

			var bank_name = frm.bank_name.value;
			var bank_account = frm.bank_account.value;
			var bank_owner = frm.bank_owner.value;

			if (confirm(msg)) {
				$.ajax({
					type: "POST",
					url: "ajax/ajax.php",
					data: {
						join_id: id_str,
						join_nick: frm.nick.value,
						join_pwd: app_pwd,
						join_web_pwd: web_pwd,
						join_name: frm.name.value,
						join_email: email_str,
						join_phone: phone_str,
						join_add1: frm.add1.value,
						join_zy: frm.zy.value,
						join_birth: birth_str,
						join_is_message: is_message_str,
						join_modify: modify,
						bank_name: bank_name,
						bank_account: bank_account,
						bank_owner: bank_owner
					},
					success: function(data) {
						$("#ajax_div").html(data)
					}
				})
			}
		}

		function monthly_remove(no) {
			if (confirm('정기결제 해지신청하시겠습니까?')) {
				$.ajax({
					type: "POST",
					url: "ajax/ajax_add.php",
					data: {
						mode: "monthly",
						no: no
					},
					success: function(data) {
						alert('신청되었습니다.');
						location.reload();
					}
				})

			}
		}
		$(function() {
			$('#saveBtn').on("click", function() {
				if ($('#mobile').val() == "") {
					alert('발송폰번호을 입력해주세요.');
					return;
				}
				if ($('#event_name_eng').val() == "") {
					alert('이벤트명을 입력해주세요.');
					return;
				}
				if ($('#reservation_title').val() == "") {
					alert('예약문자 제목을 입력해주세요.');
					return;
				}
				$('#sform').submit();
			});
			$(".popbutton4").click(function() {
				var phoneno = $(this).siblings().eq(0).find("input").val();
				$('.ad_layer4').lightbox_me({
					centered: true,
					onLoad: function() {
						/*
				$.ajax({
					type:"POST",
					url:"ajax/get_numinfo_election.php",
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
						$("#todaySendCnt").html(data.todaySendCnt);
						$("#cnt2State").html(data.cnt2State);
						$("#diviceModel").html(data.diviceModel);
						$("#detail_rate").val(data.detail_rate);
						$("#thismonthCnt").html(data.thismonthCnt);
						$("#lastSendDate").html(data.lastSendDate);	
						$("#syncDbdate").html(data.syncDbdate);	
					},
					error: function(){
					  alert('로딩 실패');
					}
				});
				*/
					}
				});
			});
			$('#popSaveBtn').on("click", function() {
				if ($('#step').val() == "") {
					alert('순서를 입력하세요.');
					return;
				}

				if ($('#send_day').val() == "") {
					alert('발송일시를 입력하세요.');
					return;
				}

				if ($('#send_time_hour').val() == "") {
					alert('발송일시를 입력하세요.');
					return;
				}

				if ($('#send_time_min').val() == "") {
					alert('발송일시를 입력하세요.');
					return;
				}

				if ($('#title').val() == "") {
					alert('제목을 입력하세요.');
					return;
				}

				if ($('#content').val() == "") {
					alert('제목을 입력하세요.');
					return;
				}
				$('#addForm').submit();
			});

			$('#popCloseBtn').on("click", function() {
				$('.lb_overlay, .ad_layer4').hide();
			});
		});
	</script>
	<link href='/css/sub_4_re.css' rel='stylesheet' type='text/css' />
	<script type="text/javascript" src="jquery.lightbox_me.js"></script>
	<script type="text/javascript" src="/js/mms_send.js"></script>
	<script type="text/javascript" src="/plugin/tablednd/js/jquery.tablednd.0.7.min.js"></script>
	<style>
		.ad_layer4 {
			width: 903px;
			height: 498px;
			background-color: #fff;
			border: 2px solid #24303e;
			position: relative;
			box-sizing: border-box;
			padding: 30px 30px 50px 30px;
			display: none;
		}
	</style>

	<div class="ad_layer4">
		<div class="layer_in">
			<span class="layer_close close"><img src="/images/close_button_05.jpg"></span>
			<div class="pop_title">
				예약문자
			</div>
			<div class="info_box">
				<form method="post" name="addForm" id="addForm" action="mypage.proc.php" enctype="multipart/form-data">
					<input type="hidden" name="sms_idx" value="<?php echo $sms_idx; ?>">
					<input type="hidden" name="mode" id="mode" value="step_add">
					<input type="hidden" name="sms_detail_idx" id="sms_detail_idx">
					<table class="info_box_table" cellpadding="0" cellspacing="0">
						<tbody>
							<tr>
								<th class="w200">순서</th>
								<td>
									<input type="text" id="step" name="step" value="" style="width:70px;">
								</td>
							</tr>
							<tr>
								<th class="w200">발송일시</th>
								<td>
									<input type="text" id="day" name="send_day" value="" style="width:70px;" maxlength="3">일후(0이면 신청직후발송)
									<input type="text" id="send_time_hour" name="send_time_hour" value="" style="width:70px;" maxlength="2"> 시
									<!--input type="text" id="send_time_min" name="send_time_min" value="" style="width:70px;" maxlength="2"> 분 (10분단위로 설정가능)-->
									<select name="send_time_min" id="send_time_min" style="width:70px;">
										<?
										for ($i = 0; $i < 60; $i += 10) {
											$iv = $i == 0 ? "00" : $i;
										?>
											<option value="<?= $iv ?>" data="<?= $iv ?>"><?= $iv ?></option>
										<?
										}
										?>
									</select>
									<div id="display_day"></div>
								</td>
							</tr>
							<tr>
								<th>문자제목</th>
								<td><input type="text" id="title" name="title" value=""></td>
							</tr>
							<tr>
								<th>문자내용</th>
								<td>
									<textarea name="content" itemname="내용" id="content" required="" placeholder="reservation_desc" style="background-color: rgb(200, 237, 252);width:300px;height:200px;"></textarea>
								</td>
							</tr>
							<tr>
								<th>첨부파일</th>
								<td><input type="file" id="file" name="image" value=""></td>
							</tr>

						</tbody>
					</table>
				</form>
			</div>
			<div class="ok_box">
				<input type="button" value="취소" class="button " id="popCloseBtn">
				<input type="button" value="저장" class="button" id="popSaveBtn">
			</div>

		</div>


	</div>