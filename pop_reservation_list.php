<?
$path = "./";
include_once "_head_open.php";
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
		})

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
</style>

<div class="big_sub" style="background:#fff;">



	<div class="m_div">
		<div class="m_body">

			<form name="pay_form" action="" method="post" class="my_pay">

				<input type="hidden" name="page" value="<?= $page ?>" />
				<input type="hidden" name="page2" value="<?= $page2 ?>" />
				<div class="a1">
					<li style="float:left;">예약문자 리스트</li>
					<li style="float:right;"></li>
					<p style="clear:both"></p>
				</div>
				<div style="background:#fff;">
					<div class="p1">
						<select name="search_key" class="select">
							<option value="">전체</option>
						</select>
						<input type="text" name="search_text" placeholder="" id="search_text" value="<?= $_REQUEST['search_text'] ?>" />
						<a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
						<div style="text-align:right;margin-top:20px;">
						</div>
					</div>
					<div>
						<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="width:2%;"></td>
								<td style="width:6%;">No</td>
								<td style="width:15%;">예약문자제목</td>
								<td style="width:15%;">예약문자설명</td>
								<td style="width:8%">단계</td>
								<td style="width:10%;">발신횟수/건수</td>
								<td style="width:9%;">등록일</td>
								<td style="width:9%;">사용</td>

							</tr>
							<?

							$sql_serch = " m_id ='{$_SESSION['one_member_id']}' ";
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
							$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
							$row = mysqli_fetch_array($result);
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
								$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
							?>
								<?
								while ($row = mysqli_fetch_array($result)) {

									$sql = "select count(*) as cnt from Gn_event_sms_step_info where sms_idx='{$row['sms_idx']}'";
									$sresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
									$srow = mysqli_fetch_array($sresult);

									$sql = "select * from Gn_event where event_name_eng='{$row['event_name_eng']}'";
									$eresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
									$erow = mysqli_fetch_array($eresult);

								?>
									<tr>
										<td></td>
										<td><?= $sort_no ?></td>
										<td style="font-size:12px;"><?= $row['reservation_title'] ?></td>
										<td><?= $row['reservation_desc'] ?></td>
										<td><?= number_format($srow['cnt']) ?></td>
										<td><?= number_format($cnt) ?>/<?= number_format($cnt) ?></td>
										<td><?= $row['regdate'] ?></td>
										<td>
											<a href="javascript:;;" onclick="useIt('<?= $row['sms_idx']; ?>','<?= $erow['event_idx'] ?>','<?= $row['event_name_eng'] ?>','<?= $row['event_name_kor'] ?>','<?= $row['reservation_title'] ?>','<?= $srow['cnt'] ?>','<?= $erow['pcode'] ?>')">사용하기</a>
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
	<Script>
		function useIt(sms_idx, event_idx, event_name_eng, event_name_kor, reservation_title, cnt, pcode) {
			opener.$('#sms_idx').val(sms_idx);
			opener.$('#event_idx').val(event_idx);
			opener.$('#event_code').val(event_name_eng);
			opener.$('#event_name_kor').val(reservation_title);
			opener.$('#step').val(cnt);
			try {
				opener.$('#event_idx_event').val(event_idx);
			} catch (e) {

			}
			try {
				opener.$('#event_name_eng_event').val(event_name_eng);
			} catch (e) {

			}

			try {
				opener.$('#event_pcode_event').val(pcode);
			} catch (e) {

			}
			window.close();
		}

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
	</script>