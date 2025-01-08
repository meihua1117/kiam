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
$sql = "select * from Gn_Member  where mem_id='" . $_SESSION['one_member_id'] . "' and site != ''";
$sresul_num = mysqli_query($self_con, $sql);
$data = mysqli_fetch_array($sresul_num);
extract($_POST);
?>
<script>
	function copyHtml() {
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
		$(".popbutton1").click(function() {
			$('.ad_layer1').lightbox_me({
				centered: true,
				onLoad: function() {}
			});
		})
		$('.check').on("click", function() {
			if ($(this).prop("id") == "check_all_member") {
				if ($(this).prop("checked"))
					$('.check').prop("checked", true);
				else
					$('.check').prop("checked", false);
			} else if ($(this).prop("id") == "check_one_member") {
				if (!$(this).prop("checked"))
					$('#check_all_member').prop("checked", false);
			}
		});
	});

	function deleteMultiRow() {
		var check_array = $("#example1").children().find(".check");
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
					delete_name: "mypage_link_list",
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

	function copyHtml(shortlink) {
		// 글을 쓸 수 있는 란을 만든다.
		var aux = document.createElement("input");
		// 지정된 요소의 값을 할당 한다.
		aux.setAttribute("value", shortlink);
		// bdy에 추가한다.
		document.body.appendChild(aux);
		// 지정된 내용을 강조한다.
		aux.select();
		// 텍스트를 카피 하는 변수를 생성
		document.execCommand("copy");
		// body 로 부터 다시 반환 한다.
		document.body.removeChild(aux);
		alert("URL이 복사되었습니다. 원하는 곳에 붙여 넣으세요.");
	}

	function newpop(str) {
		window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
	}

	function deleteRow(event_id) {
		if (confirm('삭제하시겠습니까?')) {
			$.ajax({
				type: "POST",
				url: "mypage.proc.php",
				data: {
					mode: "event_del",
					event_idx: event_id
				},
				success: function(data) {
					$("#ajax_div").html(data);
					//alert('삭제되었습니다.');
					location.reload();
				}
			});
			return false;
		}
	}
</script>
<style>
	.pop_right {
		position: relative;
		right: 2px;
		display: inline;
		margin-bottom: 6px;
		width: 5px;
	}

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
							<div class="popup_holder popup_text"> 고객신청그룹 리스트
								<div class="popupbox" style="display:none; height: 75px;width: 240px;color: black;left: 160px;top: -37px;">자신의 이벤트 상품이나 서비스를 통해 고객정보를 받아올 수 있도록 만든 신청창을 리스트로 보는 기능입니다.<br><br>
									<a class="detail_view" style="color: blue;" href="https://tinyurl.com/37ts8xrk" target="_blank">[자세히 보기]</a>
								</div>
							</div>
						</li>
						<li style="float:right;"></li>
						<p style="clear:both"></p>
					</div>
					<div>
						<div class="p1">
							<select name="search_key" class="select">
								<option value="" <? if ($search_key == "") echo "selected" ?>>전체</option>
								<option value="event_title" <? if ($search_key == "event_title") echo "selected" ?>>신청그룹제목</option>
								<option value="event_name_kor" <? if ($search_key == "event_name_kor") echo "selected" ?>>신청대상</option>
								<option value="mobile" <? if ($search_key == "mobile") echo "selected" ?>>발송폰번호</option>
							</select>
							<input type="text" name="search_text" placeholder="" id="search_text" value="<?= $_REQUEST['search_text'] ?>" />
							<a href="javascript:pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
							<div style="float:right;">
								<div class="popup_holder"> <!--Parent-->
									<input type="button" value=" 엑셀다운 " class="button" onclick="excel_down('/excel_down/excel_mypage_link_list.php');return false;" style="cursor: pointer">
									<input type="button" value="신청그룹전송" class="button" onclick="send_reqlink()" style="cursor: pointer">
									<input type="hidden" name="send_reqlink_idx" id="send_reqlink_idx" value="">
									<input type="button" value="등록하기" class="button" onclick="location='mypage_link_write.php'" style="cursor: pointer">
									<!-- <input type="button" value="전체삭제" class="button" onclick="delete_all()" style="cursor: pointer"> -->
									<input type="button" value="선택삭제" class="button" onclick="deleteMultiRow()" style="cursor: pointer">
									<!--div class="popupbox" style="display:none; height: 63px;width: 180px; bottom: 37px;">자신의 이벤트 상품이나 서비스를 통해 고객이 신청한 정보를 자동으로 보여주는 기능입니다. <br><br>
										<a class = "detail_view" style="color: blue;" href="https://url.kr/ldQyeR" target="_blank">[자세히 보기]</a>
									</div-->
								</div>
							</div>
						</div>
						<div>
							<table class="list_table" id='example1' width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td style="width:2%;"><input type="checkbox" class="check" id="check_all_member" value="0"></td>
									<td style="width:6%;">No</td>
									<td style="width:15%;">신청그룹제목</td>
									<td style="width:10%;">신쳥대상</td>
									<td style="width:10%;">미리보기<br>링크주소</td>
									<td style="width:8%">발송폰번호</td>
									<td style="width:10%">스탭문자<br>회차</td>
									<td style="width:6%">중단문자<br>ON/OFF</td>
									<td style="width:10%;">조회수/<br>신청수</td>
									<td style="width:9%;">등록일</td>
									<td style="width:9%;">수정/삭제</td>
								</tr>
								<?
								$sql_serch = " m_id ='{$_SESSION['one_member_id']}' and event_name_kor!='단체회원자동가입및아이엠카드생성' AND event_name_kor!='콜백메시지관리자설정동의' AND event_name_kor!='데일리문자세트자동생성' ";
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
								if ($_REQUEST['search_key'] && $_REQUEST['search_text']) {
									$sql_serch .= " AND {$_REQUEST['search_key']} LIKE '%" . $search_text . "%'";
								}
								$sql = "select count(event_idx) as cnt from Gn_event where $sql_serch ";
								$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
								$row = mysqli_fetch_array($result);
								$intRowCount = $row['cnt'];
								if ($intRowCount) {
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
										$order_name = "event_idx";
									$intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);
									$sql = "select * from Gn_event where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
									$excel_sql = "select * from Gn_event where $sql_serch order by $order_name $order_status";
									$excel_sql = str_replace("'", "`", $excel_sql);
									$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
									while ($row = mysqli_fetch_array($result)) { 
										$sql_event = "select count(*) as cnt from Gn_event_request where m_id ='$_SESSION[one_member_id]' and event_idx='{$row['event_idx']}' ";
										$res_event = mysqli_query($self_con, $sql_event);
										$row_event = mysqli_fetch_assoc($res_event);
										$req_count = $row_event['cnt'];
									?>
										<tr>
											<td><input type="checkbox" class="check" id="check_one_member" name="event_idx" value="<?= $row['event_idx']; ?>"></td>
											<td><?= $sort_no ?></td>
											<td style="font-size:12px;"><?= $row['event_title'] ?></td>
											<td style="font-size:12px;"><?= $row['event_name_kor'] ?></td>
											<td>
												<?php
												if ($row['event_name_kor'] == "단체회원자동가입및아이엠카드생성") {
													$pop_url = '/event/automember.php?pcode=' . $row['pcode'] . '&eventidx=' . $row['event_idx'];
												} else if ($row['event_name_kor'] == "콜백메시지관리자설정동의") {
													$pop_url = '/event/callbackmsg.php?pcode=' . $row['pcode'] . '&eventidx=' . $row['event_idx'];
												} else if ($row['event_name_kor'] == "데일리문자세트자동생성") {
													$pop_url = '/event/dailymsg.php?pcode=' . $row['pcode'] . '&eventidx=' . $row['event_idx'];
												} else {
													$pop_url = '/event/event.html?pcode=' . $row['pcode'] . '&sp=' . $row['event_name_eng'];
												}
												?>
												<a onclick="newpop('<?= $pop_url ?>')">미리보기</a><br>
												<a onclick="copyHtml('<?= $row['short_url'] ?>')">&nbsp;링크복사</a>
											</td>
											<td><?= $row['mobile'] ?></td>
											<td>
												<?
												if ($row['sms_idx1'] != 0) {
													$sql = "select reservation_title from Gn_event_sms_info where sms_idx='{$row['sms_idx1']}'";
													$res = mysqli_query($self_con, $sql);
													$sms_row = mysqli_fetch_array($res);
													$sql = "select count(*) from Gn_event_sms_step_info where sms_idx='{$row['sms_idx1']}'";
													$res = mysqli_query($self_con, $sql);
													$step_row = mysqli_fetch_array($res);
													echo "<a onclick=\"javascript:window.open('/mypage_reservation_create.php?sms_idx={$row['sms_idx1']}','','toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=600');\">$sms_row[0]<br><strong>($step_row[0])</strong></a>";
												}
												?>
											</td>
											<td><?
												if ($row['stop_event_idx'] != 0) {
													$sql = "select event_title from Gn_event where event_idx='{$row['stop_event_idx']}'";
													$res = mysqli_query($self_con, $sql);
													$sms_row = mysqli_fetch_array($res);
													echo "$sms_row[0]";
												} else {
													echo "<strong>OFF</strong>";
												}
												?>
											</td>
											<td><?= $row['read_cnt']."/" ?><a style="cursor:pointer" onclick="window.open('mypage_pop_member_list.php?eventid='+'<?=$row['event_idx']?>','','top=300,left=300,width=800,height=500,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no, status=no')" ><?=$req_count?></a></td>
											<td><?= $row['regdate'] ?></td>
											<td>
												<a href='mypage_link_write.php?event_idx=<?php echo $row['event_idx']; ?>'>수정</a>/
												<a href="javascript:;;" onclick="deleteRow('<?php echo $row['event_idx']; ?>')">삭제</a>
											</td>
										</tr>
									<?
										$sort_no--;
									}
									?>
									<tr>
										<td colspan="10">
											<? page_f($page, $page2, $intPageCount, "pay_form"); ?>
										</td>
									</tr>
								<?	} else { ?>
									<tr>
										<td colspan="10">
											검색된 내용이 없습니다.
										</td>
									</tr>
								<?	} ?>
							</table>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<span class="tooltiptext-bottom" id="tooltiptext_card_edit" style="display:none;">
		<p class="title_app">고객신청창 전송하기</p>
		<table class="table table-bordered" style="width: 97%;">
			<tbody>
				<tr class="hide_spec">
					<td class="bold" id="remain_count" data-num="" style="width:70px;padding:5px;">전송하기<br>
						<textarea name="req_send_id_count" id="req_send_id_count" style="width:90%; height:50px;color: red;font-size: 12px" readonly="" data-num="0" placeholder="0건"></textarea>
					</td>
					<td colspan="2" style="padding:5px;">
						<div>
							<textarea name="req_send_id" id="req_send_id" style="border: solid 1px #b5b5b5;width:97%; height:100px;" data-num="0" placeholder="전송할 친구 아이디를 입력하세요.<컴마로 구분>"></textarea>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="button_app">
			<a href="javascript:cancel_set()" class="btn login_signup" style="width: 40%;background-color: #d7d3d3;border-radius: 3px;padding: 5px;">취소하기</a>
			<a href="javascript:send_req_link()" class="btn login_signup" style="width: 40%;background-color: #2b78cf;border-radius: 3px;color: white;padding: 5px;">전송하기</a>
		</div>
	</span>
	<div id="tutorial-loading"></div>
	<form id="excel_down_form" name="excel_down_form" target="excel_iframe" method="post">
		<input type="hidden" name="grp_id" value="" />
		<input type="hidden" name="box_text" value="" />
		<input type="hidden" name="excel_sql" value="<?= $excel_sql ?>" />
	</form>
	<iframe name="excel_iframe" style="display:none;"></iframe>
</div>
<script>
	function send_reqlink() {
		var idx_arr = new Array();
		$('input[name=event_idx]').each(function() {
			if ($(this).is(":checked") == true) {
				idx_arr.push($(this).val());
			}
		});
		$("#send_reqlink_idx").val(idx_arr.join(","));

		if (idx_arr.length == 0) {
			alert("전송할 신청창을 선택하세요.");
			return;
		}

		$("#tooltiptext_card_edit").show();
		$("#tutorial-loading").show();
	}

	function cancel_set() {
		$("#tooltiptext_card_edit").hide();
		$("#tutorial-loading").hide();
	}

	function send_req_link() {
		var send_ids = $("#req_send_id").val();
		var req_idx = $("#send_reqlink_idx").val();

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
				req_idx: req_idx,
				type: "reqlink"
			},
			success: function(data) {
				console.log(data);
				alert("전송되었습니다.");
				location.reload();
			}
		});
	}
	$("#req_send_id").keyup(function() {
		point = $(this).val();
		var arr = point.split(",");
		cnt = arr.length;
		if (point.indexOf(",") == -1 && point == "") {
			cnt = 0;
		}
		$("#req_send_id_count").val(cnt + "건");
		$('#req_send_id_count').data('num', cnt);
	});

	function delete_all() {
		var msg = confirm('모든 페이지 데이타를 모두 삭제합니다.  삭제하시겠어요?');
		if (msg) {
			$.ajax({
				type: "POST",
				url: "/admin/ajax/delete_func.php",
				data: {
					admin: 0,
					delete_name: "mypage_link_list",
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
</script>
<? include_once "_foot.php"; ?>