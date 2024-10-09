<?
$path = "./";
include_once "_head.php";
if (!$_SESSION['one_member_id']) {

?>
	<script language="javascript">
		location.replace('/ma.php');
	</script>
<?
	exit;
}
$sql = "select * from Gn_Member  where mem_id='" . $_SESSION['one_member_id'] . "'";
$sresul_num = mysql_query($sql);
$data = mysql_fetch_array($sresul_num);

if ($data['intro_message'] == "") {
	$data['intro_message'] = "안녕하세요\n
\n
귀하의 휴대폰으로\n
기부문자발송을 시작합니다.\n
\n
협조해주셔서 감사합니다^^
";
}

$sql_mem_info = "select step_send_ids from Gn_Member where mem_id='{$_SESSION['one_member_id']}'";
$res_mem_info = mysql_query($sql_mem_info);
$row_mem_info = mysql_fetch_array($res_mem_info);
$send_ids = $row_mem_info['step_send_ids'];
if ($send_ids != "") {
	$cnt = explode(",", $send_ids);
	$send_ids_cnt = count($cnt);
}
?>
<script>
	$(function() {
		$(".popbutton").click(function() {
			$('.ad_layer_info').lightbox_me({
				centered: true,
				onLoad: function() {}
			});
		});
	});
</script>
<style>
	.tooltiptext-bottom {
		width: 420px;
		font-size: 15px;
		background-color: white;
		color: black;
		text-align: left;
		position: absolute;
		z-index: 200;
		top: 25%;
		left: 35%;
	}

	.title_app {
		text-align: center;
		background-color: rgb(130, 199, 54);
		padding: 10px;
		font-size: 20px;
		color: white;
	}

	.desc_app {
		padding: 15px;
	}

	.button_app {
		text-align: center;
		padding: 10px;
	}

	.table-bordered>tbody>tr>td {
		border: 1px solid #ddd;
	}

	@media only screen and (max-width: 450px) {
		.tooltiptext-bottom {
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
</style>
<div class="big_div">
	<div class="big_sub">

		<?php include "mypage_step_navi.php"; ?>

		<div class="m_div">
			<?php include "mypage_left_menu.php"; ?>
			<div class="m_body">

				<form name="pay_form" action="" method="post" class="my_pay">

					<input type="hidden" name="page" value="<?= $page ?>" />
					<input type="hidden" name="page2" value="<?= $page2 ?>" />
					<div class="a1" style="margin-top:50px; margin-bottom:15px">
						<li style="float:left;">
							<div class="popup_holder popup_text">스탭예약관리 리스트
								<div class="popupbox" style="height: 75px;width: 245px;left: 200px;top: -37px;">스텝 예약문자 메시지 세트를 등록하여 활용할수 있는 기능과 예약세트메시지를 리스트로 보는 기능입니다.<br><br>
									<a class="detail_view" style="color: blue;" href="https://tinyurl.com/2p8vsjm2" target="_blank">[자세히 보기]</a>
								</div>
							</div>
						</li>
						<li style="float:right;"></li>
						<p style="clear:both"></p>
					</div>
					<div>
						<div class="p1">
							<select name="search_key" class="select">
								<option value="">전체</option>
							</select>
							<input type="text" name="search_text" placeholder="" id="search_text" value="<?= $_REQUEST['search_text'] ?>" />
							<a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
							<div style="text-align:right;margin-top:0px;float:right;display: flex;">
								<div class="popup_holder"> <!--Parent-->
									<input type="button" value="메시지세트판매" class="button" onclick="sell_step()">
									<input type="button" value="메시지세트 전송" class="button" onclick="send_step()">
									<input type="button" value="메시지복제하기" class="button" onclick="get_steplist()">
									<input type="hidden" name="send_sms_idx" id="send_sms_idx" value="">
								</div>
								<div class="popup_holder"> <!--Parent-->
									<input type="button" value="메시지세트등록" class="button" onclick="location='mypage_reservation_create.php'">
									<div class="popupbox" style="height: 52px;width: 196px;bottom: 37px;">예약문자메시지를 주기적으로 보내기 위한 예약문자의 세트를 만드는 기능입니다.<br><!--Child-->
										<a class="detail_view" href="https://tinyurl.com/uwcybb6p" target="_blank">[자세히 보기]</a>
									</div>
								</div>
								<div class="popup_holder"> <!--Parent-->
									<input type="button" value="선택삭제" class="button" onclick="deleteMultiRow()">
								</div>
							</div>
						</div>
						<div>
							<table class="list_table" style="width:100%;border:none" cellspacing="0" cellpadding="0">
								<tr>
									<td style="width:2%;"><input type="checkbox" name="allChk" id="allChk" value="<?php echo $row['event_idx']; ?>"></td>
									<!-- <td style="width:2%;"></td> -->
									<td style="width:6%;">No</td>
									<td style="width:15%;">메시지세트제목</td>
									<td style="width:15%;">메시지세트설명</td>
									<td style="width:8%">단계</td>
									<td style="width:10%;">발신횟수/건수</td>
									<td style="width:9%;">등록일</td>
									<td style="width:9%;">관리</td>

								</tr>
								<?

								$sql_serch = " m_id ='$_SESSION[one_member_id]' ";
								if ($_REQUEST['search_date']) {
									if ($_REQUEST['rday1']) {
										$start_time = strtotime($_REQUEST['rday1']);
										$sql_serch .= " and unix_timestamp({$_REQUEST['search_date']}) >=$start_time ";
									}
									if ($_REQUEST['rday2']) {
										$end_time = strtotime($_REQUEST['rday2']);
										$sql_serch .= " and unix_timestamp({$_REQUEST['search_date']}) <= $end_time ";
									}
								}

								if ($_REQUEST['search_text']) {
									$search_text = $_REQUEST['search_text'];
									$sql_serch .= " and (reservation_title like '%$search_text%' or reservation_desc like '%$search_text%')";
								}

								$sql = "select count(sms_idx) as cnt from Gn_event_sms_info where $sql_serch ";
								$result = mysql_query($sql) or die(mysql_error());
								$row = mysql_fetch_array($result);
								$intRowCount = $row['cnt'];
								if (!$_POST['lno'])
									$intPageSize = 20;
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
									$order_name = "sms_idx";
								$intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);
								if ($intRowCount) {
									$sql = "select * from Gn_event_sms_info where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
									$result = mysql_query($sql) or die(mysql_error());

								?>
									<?
									while ($row = mysql_fetch_array($result)) {
										$sql = "select count(*) as cnt from Gn_event_sms_step_info where sms_idx='{$row['sms_idx']}'";
										$sresult = mysql_query($sql) or die(mysql_error());
										$srow = mysql_fetch_array($sresult);
									?>
										<tr>
											<!-- <td></td> -->
											<td>
												<? if ($row['sendable'] == 1) { ?>
													<input type="checkbox" class="check" name="sms_idx" value="<?php echo $row['sms_idx']; ?>" data-event-idx="<?= $row['event_idx'] ?>" data-name="<?= $row['m_id'] ?>" data-mobile="<?= $row['mobile'] ?>" data-event_name_eng="<?= $row['event_name_eng'] ?>" data-title="<?= $row['reservation_title'] ?>" data-desc="<?= $row['reservation_desc'] ?>">
											</td>
										<? } ?>
										<td><?= $sort_no ?></td>
										<td style="font-size:12px;"><?= $row['reservation_title'] ?></td>
										<td><?= $row['reservation_desc'] ?></td>
										<td><?= number_format($srow['cnt']) ?></td>
										<td><?= number_format($cnt) ?>/<?= number_format($cnt) ?></td>
										<td><?= $row['regdate'] ?></td>
										<td>
											<a href='mypage_reservation_create.php?sms_idx=<?php echo $row['sms_idx']; ?>'>수정</a>/<a href="javascript:;;" onclick="deleteRow('<?php echo $row['sms_idx']; ?>')">삭제</a>
										</td>
										</tr>
									<?
										$sort_no--;
									}
									?>
									<tr>
										<td colspan="10">
											<?
											page_f($page, $page2, $intPageCount, "pay_form");
											?>
										</td>
									</tr>
								<?
								} else {
								?>
									<tr>
										<td colspan="10">
											검색된 내용이 없습니다.
										</td>
									</tr>
								<?
								}
								?>
							</table>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<span class="tooltiptext-bottom" id="tooltiptext_card_edit" style="display:none;">
	<p class="title_app">스텝문자세트 전송하기</p>
	<table class="table table-bordered" style="width: 97%;">
		<tbody>
			<tr class="hide_spec">
				<td class="bold" id="remain_count" data-num="" style="width:70px;padding:5px;">전송하기<br>
					<textarea name="step_send_id_count" id="step_send_id_count" style="width:90%; height:50px;color: red;font-size: 12px" readonly="" data-num="0" placeholder="0건"></textarea>
				</td>
				<td colspan="2" style="padding:5px;">
					<div>
						<textarea name="step_send_id" id="step_send_id" style="border: solid 1px #b5b5b5;width:97%; height:100px;" data-num="0" placeholder="전송할 친구 아이디를 입력하세요.<컴마로 구분>"></textarea>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="button_app">
		<a href="javascript:cancel_set()" class="btn login_signup" style="width: 40%;background-color: #d7d3d3;border-radius: 3px;padding: 5px;">취소하기</a>
		<a href="javascript:send_step_sms()" class="btn login_signup" style="width: 40%;background-color: #2b78cf;border-radius: 3px;color: white;padding: 5px;">전송하기</a>
	</div>
</span>

<span class="tooltiptext-bottom" id="step_mall_set" style="display:none;width:600px;">
	<p class="title_app">메시지세트 등록하기</p>
	<table class="table table-bordered" style="width: 97%;padding: 17px;border: 1px solid #ddd;">
		<input type="hidden" id="iam_mall_type" value="5">
		<!--input type="hidden" id="iam_mall_link" value="<?= $row_card[0] ?>"-->
		<input type="hidden" id="iam_mall_method" value="creat">
		<input type="hidden" id="iam_mall_idx" value="">
		<tbody>
			<colgroup>
				<col width="20%">
				<col width="80%">
			</colgroup>
			<tr class="bold">
				<td>상품제목</td>
				<td>
					<input type="text" id="iam_mall_title" style="width: 98%;">
				</td>
			</tr>
			<tr class="bold">
				<td>상품부제목</td>
				<td>
					<input type="text" id="iam_mall_sub_title" style="width: 98%;">
				</td>
			</tr>
			<tr class="bold">
				<td>상품썸네일</td>
				<td>
					<div class="input-wrap">
						<input type="radio" name="iam_mall_img_type" value="f" checked>파일가져오기
						<input type="radio" name="iam_mall_img_type" value="u" id="main_type1">이미지주소
					</div>
					<div class="input-wrap" style="margin-top:10px">
						<input type="file" id="iam_mall_img" style="width: 98%;height: 24px;" accept=".jpg,.jpeg,.png,.gif">
						<input type="text" id="iam_mall_img_link" style="height: 24px;width:100%;display:none" placeholder="예시 https://www.abcdef.jpg (png/gif)">
					</div>
					<img id="iam_mall_img_preview" style="width:80%">
				</td>
			</tr>
			<tr class="bold">
				<td>상세설명</td>
				<td>
					<textarea name="iam_mall_desc" placeholder="<?= $MENU['IAM_CONTENTS']['CONTS14']; ?>"
						id="iam_mall_desc" style="border:none;min-height:175px;overflow:auto;width:98%;resize: vertical;">1. 메시지 세트 제목
								2. 메시지 세트 예약건수
								3. 메시지 세트 적용 대상
								4. 메시지 세트 적용 목표
								5. 메시지 세트 적용 효과
								6. 메시지 세트 홍보 전략</textarea>
				</td>
			</tr>
			<tr class="bold">
				<td>검색키워드</td>
				<td>
					<input type="text" id="iam_mall_keyword" style="height: 24px;width:98%;">
				</td>
			</tr>
			<tr class="bold">
				<td>상품정가</td>
				<td>
					<input type="text" id="iam_mall_price" style="width: 98%;height: 24px;" placeholder="<?= $MENU['IAM_CONTENTS']['CONTS12']; ?>">
				</td>
			</tr>
			<tr class="bold">
				<td>판매가격</td>
				<td>
					<input type="text" id="iam_mall_sell_price" style="width: 98%;height: 24px;" placeholder="<?= $MENU['IAM_CONTENTS']['CONTS13']; ?>">
				</td>
			</tr>
			<tr class="bold">
				<td>노출상태</td>
				<td>
					<input type="checkbox" id="iam_mall_display" checked>&nbsp;&nbsp;&nbsp;노출</input>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="button_app">
		<a href="javascript:cancel_set()" class="btn login_signup" style="width: 40%;background-color: #d7d3d3;border-radius: 3px;padding: 5px 40px;">취소</a>
		<a href="javascript:create_iam_mall(0)" class="btn login_signup" style="width: 40%;background-color: #2b78cf;border-radius: 3px;color: white;padding: 5px 40px;">등록</a>
	</div>
</span>

<span class="tooltiptext-bottom" id="service_contents_popup" style="display:none;">
	<p class="title_app">판매자 신청하기</p>
	<div class="modal-body">
		<div>
			<div class="login_text" style="padding: 15px;">
				서비스콘텐츠에서는 본사의 결제시스템으로 판매를 하므로 결제, 판매, 홍보에 대한 수수료 납부가 필요합니다.<br>
				그래서 아래에 판매자신청 버튼을 클릭하셔야 서비스콘텐츠를 사용할수 있습니다.
			</div>
		</div>
	</div>
	<div style="width:100%;text-align: center;padding: 10px 0px;">
		<a href="javascript:hide_service_contents_popup()" class="btn login_signup" style="width: 90%;background-color: #ff0066;color: white;padding: 3px 15px;" id="service_contents_popup_ok">확인</a>
	</div>
</span>

<div id="tutorial-loading"></div>
<script>
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
	$('#allChk').on("change", function() {
		$('input[name=sms_idx]').prop("checked", $(this).is(":checked"));
	});
</script>


<script>
	function deleteRow(sms_idx) {
		if (confirm('삭제하시겠습니까?')) {
			$.ajax({
				type: "POST",
				url: "mypage.proc.php",
				data: {
					mode: "reservation_del",
					sms_idx: sms_idx
				},
				success: function(data) {
					$("#ajax_div").html(data);
					alert('삭제되었습니다.');
					location.reload();
				}
			});
			return false;
		}
	}

	function deleteMultiRow() {
		var check_array = $(".list_table").children().find(".check");
		var no_array = [];
		var index = 0;
		check_array.each(function() {
			if ($(this).prop("checked") && $(this).val() > 0)
				no_array[index++] = $(this).val();
		});

		if (no_array.length == 0) {
			alert("삭제할 신청창을 선택하세요.");
			return;
		}
		if (confirm('삭제하시겠습니까?')) {
			$.ajax({
				type: "POST",
				url: "/admin/ajax/delete_func.php",
				dataType: "json",
				data: {
					admin: 0,
					delete_name: "mypage_reservation_list",
					id: no_array.toString()
				},
				success: function(data) {
					console.log(data);
					if (data == 1) {
						alert('삭제 되었습니다.');
						window.location.reload();
					}
				}
			})
		}
	}
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

	function send_step() {
		var idx_arr = new Array();
		$('input[name=sms_idx]').each(function() {
			if ($(this).is(":checked") == true) {
				idx_arr.push($(this).val());
			}
		});
		$("#send_sms_idx").val(idx_arr.join(","));

		if (idx_arr.length == 0) {
			alert("전송할 메시지를 선택하세요.");
			return;
		}

		<? if ($send_ids != "") { ?>
			$("#step_send_id").val('<?= $send_ids ?>');
			$("#step_send_id_count").val(<?= $send_ids_cnt ?> + "건");
		<? } ?>
		$("#tooltiptext_card_edit").show();
		$("#tutorial-loading").show();
	}

	function cancel_set() {
		$("#tooltiptext_card_edit").hide();
		$("#step_mall_set").hide();
		$("#tutorial-loading").hide();
	}

	$("#step_send_id").keyup(function() {
		point = $(this).val();
		var arr = point.split(",");
		cnt = arr.length;
		if (point.indexOf(",") == -1 && point == "") {
			cnt = 0;
		}
		$("#step_send_id_count").val(cnt + "건");
		$('#step_send_id_count').data('num', cnt);
	});

	function send_step_sms() {
		var send_ids = $("#step_send_id").val();
		var sms_idx = $("#send_sms_idx").val();

		if (send_ids == "") {
			alert("아이디를 입력하세요.");
			return;
		}

		$.ajax({
			type: "POST",
			url: "/ajax/step_sms_send.php",
			dataType: "json",
			data: {
				send_ids: send_ids,
				sms_idx: sms_idx,
				type: "step"
			},
			success: function(data) {
				console.log(data);
				alert("전송되었습니다.");
				location.reload();
			}
		});
	}

	function get_steplist() {
		var win = window.open("../mypage_pop_message_list_for_copylist.php?mode=creat", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
	}

	function open_create_page(ori_sms_idx) {
		location.href = "/mypage_reservation_create.php?get_idx=" + ori_sms_idx;
	}

	function delete_all() {
		var msg = confirm('모든 페이지 데이타를 모두 삭제합니다.  삭제하시겠어요?');
		if (msg) {
			$.ajax({
				type: "POST",
				url: "/admin/ajax/delete_func.php",
				data: {
					admin: 0,
					delete_name: "mypage_reservation_list",
					mem_id: '<?= $_SESSION['one_member_id'] ?>'
				},
				success: function() {
					alert('삭제되었습니다.');
					location.reload();
				},
				error: function() {
					alert('삭제 실패');
				}
			})
		}
	}

	function sell_step() {
		var idx_arr = new Array();
		$('input[name=sms_idx]').each(function() {
			if ($(this).is(":checked") == true) {
				idx_arr.push($(this).val());
			}
		});
		$("#send_sms_idx").val(idx_arr.join(","));

		if (idx_arr.length == 0) {
			alert("판매할 메시지를 선택하세요.");
			return;
		}
		$("#step_mall_set").show();
		$("#tutorial-loading").show();
	}

	function create_iam_mall(status) {
		var idx_arr = new Array();
		$('input[name=sms_idx]').each(function() {
			if ($(this).is(":checked") == true) {
				idx_arr.push($(this).val());
			}
		});
		$("#send_sms_idx").val(idx_arr.join(","));

		if (idx_arr.length == 0) {
			alert("판매할 메시지를 선택하세요.");
			return;
		}

		if ($("#iam_mall_sell_price").val().trim() == "") {
			alert("판매가를 입력하세요.");
			$("#iam_mall_sell_price").focus();
			return;
		}
		if ('<?= $data['service_type'] ?>' == '3' || status == 1) {
			var formData = new FormData();
			formData.append("step_set", 'Y');
			formData.append("step_set_ids", $("#send_sms_idx").val());
			formData.append("iam_mall_idx", $("#iam_mall_idx").val());
			if ($('#iam_mall_img')[0].files.length) {
				formData.append('iam_mall_img', $('#iam_mall_img')[0].files[0]);
			} else {
				formData.append('iam_mall_img_link', $('#iam_mall_img_link').val());
			}
			formData.append("iam_mall_idx", $("#iam_mall_idx").val());
			formData.append("iam_mall_method", $("#iam_mall_method").val());
			formData.append("iam_mall_type", $("#iam_mall_type").val());
			formData.append("iam_mall_link", $("#iam_mall_link").val());
			formData.append("iam_mall_title", $('#iam_mall_title').val());
			formData.append("iam_mall_sub_title", $('#iam_mall_sub_title').val());
			formData.append("iam_mall_desc", $('#iam_mall_desc').val());
			formData.append("iam_mall_keyword", $('#iam_mall_keyword').val());
			formData.append("iam_mall_price", $('#iam_mall_price').val());
			formData.append("iam_mall_sell_price", $('#iam_mall_sell_price').val());
			formData.append("iam_mall_display", $('#iam_mall_display').prop("checked") == true ? 1 : 0);
			$.ajax({
				type: "POST",
				url: "/iam/ajax/mall.proc.php",
				data: formData,
				contentType: false,
				processData: false,
				success: function(data) {
					alert(data);
					location.reload();
				}
			});
		} else {
			$("#service_contents_popup").show();
			cancel_set();
			$("#tutorial-loading").show();
			$("#service_contents_popup_ok").attr("href", "javascript:create_iam_mall(1)");
		}
	}
	$('input[name=iam_mall_img_type]').change(function() {
		$('#iam_mall_img_preview').attr('src', "");
		if ($(this).val() == 'f') {
			$("#iam_mall_img").css("display", "block");
			$("#iam_mall_img_link").css("display", "none");
			$("#iam_mall_img_link").val("");
		} else if ($(this).val() == 'u') {
			$("#iam_mall_img").css("display", "none");
			$("#iam_mall_img").val("");
			$("#iam_mall_img_link").css("display", "block");
		}
	});
</script>
<?
include_once "_foot.php";
?>