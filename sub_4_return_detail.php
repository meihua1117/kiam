<?
$path = "./";
$send_num = $_GET['send_num'];
include_once "_head.php";
if (!$_SESSION['one_member_id']) {
?>
	<script language="javascript">
		location.replace('/ma.php');
	</script>
<?
	exit;
}
switch ($_REQUEST['status']) {
	case 1:
		$left_str = "휴대폰등록관리";
		break;
	case 2:
		$left_str = "문자발송하기";
		break;
	case 3:
		$left_str = "문자 저장관리";
		break;
	case 4:
		$left_str = "발신내역 확인";
		break;
	case 5:
		$left_str = "수신내역 확인";
		break;
	case 6:
		$left_str = "수신거부 관리";
		break;
	default:
		$left_str = "휴대폰등록관리";
		break;
}


$sql = "select * from Gn_MMS where idx='{$_GET['idx']}'";
$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$krow = mysqli_fetch_array($result);
$date = $krow['up_date'];

$recv_num = explode(",", $krow['recv_num']);
$recv_num_in = "'" . implode("','", $recv_num) . "'";


$sql_serch .= " and  send_num='$send_num' and recv_num in ($recv_num_in) and recv_num like '01%'  and regdate >= '$date' and sms not like '[%'";
if ($_REQUEST['status2'])
	$sql_serch .= " and msg_flag='{$_REQUEST['status2']}' ";
if ($_REQUEST['serch_colum'] && $_REQUEST['serch_text']) {
	$sql_serch .= " and {$_REQUEST['serch_colum']} like '%{$_REQUEST['serch_text']}%' ";
}
$sql = "select count(seq) as cnt from call_app_log where api_name='receive_sms' and LENGTH(recv_num) >= 10 $sql_serch ";
$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$row = mysqli_fetch_array($result);
$intRowCount = $row['cnt'];
if (!$_REQUEST['lno'])
	$intPageSize = 20;
else
	$intPageSize = $_REQUEST['lno'];
if ($_REQUEST['page']) {
	$page = (int)$_REQUEST['page'];
	$sort_no = $intRowCount - ($intPageSize * $page - $intPageSize);
} else {
	$page = 1;
	$sort_no = $intRowCount;
}
if ($_REQUEST['page2'])
	$page2 = (int)$_REQUEST['page2'];
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
	$order_name = "seq";
$intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);
$sql = "select *   from call_app_log where api_name='receive_sms' and LENGTH(recv_num) >= 10 $sql_serch order by $order_name $order_status limit $int,$intPageSize";
$excel_sql = "select *   from call_app_log where api_name='receive_sms' and LENGTH(recv_num) >= 10 $sql_serch order by $order_name $order_status";
$excel_sql = str_replace("'", "`", $excel_sql);
$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

?>
<div class="ad_layer5">
	<div class="layer_in">
		<span class="layer_close close"><img src="/images/close_button_05.jpg"></span>
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


<div class="ad_layer6">
	<div class="layer_in">
		<span class="layer_close close"><img src="/images/close_button_05.jpg"></span>
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


<div class="sub_4_4_t1">
	<div class="sub_4_4_t2">
		<!--div class="sub_4_1_t3"><a href="sub_4.php?status=4" style="color:<?= $_REQUEST['status2'] == '' ? "#000" : "" ?>">발신내역 확인</a> 
	                	    <&nbsp;|&nbsp; <a href="sub_4.php?status=4&status2=2" style="color:<?= $_REQUEST['status2'] == 2 ? "#000" : "" ?>">예약내역 확인</a></div>-->

		<div class="sub_4_1_t3">
			<a href="sub_4.php?status=4" style="color:">전체내역</a> &nbsp;|&nbsp;
			<a href="sub_4.php?status=4&status2=1" style="color:<?= $_REQUEST['status2'] == 1 ? "#000" : "" ?>">앱체크내역</a> &nbsp;|&nbsp;
			<!--<a href="sub_4.php?status=4&status2=2" style="color:<?= $_REQUEST['status2'] == 2 ? "#000" : "" ?>">발신내역</a> &nbsp;|&nbsp; -->
			<a href="sub_4_return_.php" style="color:#000">발신/회신문자</a>
		</div>

		<div class="sub_4_4_t2">
			<div class="sub_4_4_t3">
				<select name="serch_fs_select">
					<option value="">선택하세요</option>
					<?
					$select_fs_arr = array("send_num" => "발신번호", "recv_num" => "수신번호", "title" => "문자제목", "content" => "문자내용");
					foreach ($select_fs_arr as $key => $v) {
						$selected = $_REQUEST['serch_fs_select'] == $key ? "selected" : "";
					?>
						<option value="<?= $key ?>" <?= $selected ?>><?= $v ?></option>
					<?
					}
					?>
				</select>
				<select name="result">
					<option value="">전체</option>
					<option value="1" <?= $_REQUEST['result'] == 1 ? "selected" : "" ?>>성공</option>
					<option value="2" <?= $_REQUEST['result'] == 2 ? "selected" : "" ?>>대기</option>
					<option value="3" <?= $_REQUEST['result'] == 3 ? "selected" : "" ?>>실패</option>

				</select>
				<input type="text" name="serch_fs_text" value="<?= $_REQUEST['serch_fs_text'] ?>" />
				<a href="javascript:void(0)" onclick="sub_4_form.submit();"><img src="images/sub_button_103.jpg" /></a>
			</div>
			<div class="button_box">
				<div class="left_box">
					<span class="button_type"><a href="javascript:void(0)" onclick="no_msg_del()">미 전송 문자 삭제</a><span class="popbutton6 pop_view pop_right">?</span></span>
				</div>
			</div>
			<div class="sub_4_4_t4">
				<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td style="width:2%;"></td>
						<td style="width:2%;">번호</td>
						<td style="width:8%;">발신번호</td>
						<td style="width:5%">소유자명</td>
						<td style="width:10%">발신일시</td>
						<td style="width:10%">발신내용</td>
						<td style="width:8%">회신번호</td>
						<td style="width:5%">회신자명</td>
						<td style="width:10%">회신일시</td>
						<td style="width:32%">회신내용</td>
					</tr>
					<?
					if ($intRowCount) {

						$c = 0;
						while ($row = mysqli_fetch_array($result)) {
							$sql_n = "select memo from Gn_MMS_Number where sendnum='{$row['send_num']}' ";
							$resul_n = mysqli_query($self_con, $sql_n);
							$row_n = mysqli_fetch_array($resul_n);
							//$recv_num = split(",",$row['recv_num']);
							$recv_num = $row['recv_num'];

							//회신자명
							$sql_n = "select name from Gn_MMS_Receive where mem_id='{$_SESSION['one_member_id']}' and recv_num='{$row['recv_num']}' ";
							$resul_s = mysqli_query($self_con, $sql_n);
							$row_s = mysqli_fetch_array($resul_s);

					?>

							<tr>

								<td><label><input type="checkbox" name="idx_box" value="<?= $row['idx'] ?>" /></label></td>
								<td><?= $sort_no ?></td>
								<td><?= $row['send_num'] ?></td>
								<td><?= $row_n['memo'] ?></td>
								<td style="font-size:12px;"><?= substr($krow['up_date'], 0, 16) ?></td>
								<td><a href="javascript:void(0)" onclick="show_recv('show_content','<?= $c ?>','문자내용')"><?= str_substr($krow['content'], 0, 30, 'utf-8') ?></a><input type="hidden" name="show_content" value="<?= $krow['content'] ?>" /></td>
								<td><?= $recv_num ?></td>
								<td><?= $row_s['name'] ?></td>
								<td style="font-size:12px;"><?= substr($row['regdate'], 0, 16) ?></td>
								<td><a href="javascript:void(0)" onclick="show_recv('show_sms','<?= $c ?>','문자내용')"><?= str_substr($row['sms'], 0, 30, 'utf-8') ?></a><input type="hidden" name="show_sms" value="<?= $row['sms'] ?>" /></td>
							</tr>
						<?
							$c++;
							$sort_no--;
						}
						?>
						<tr>
							<td colspan="10">
								<?
								page_f($page, $page2, $intPageCount, "sub_4_form");
								?>
							</td>
						</tr>
					<?
					} else {
					?>
						<tr>
							<td colspan="10">
								등록된 내용이 없습니다.
							</td>
						</tr>
					<?
					}
					?>
				</table>
			</div>
			<div class="sub_4_4_t5">
				<div class="div_float_left">&nbsp;</div>
				<div class="div_float_right">
					<? if ($_REQUEST['status2'] == '') { ?><a href="javascript:void(0)" onclick="excel_down('excel_down/fs_down.php?status=1')"><img src="images/sub_button_107.jpg" /></a><? } ?>
					<a href="javascript:void(0)" onclick="fs_del()"><img src="images/sub_button_109.jpg" /></a>
				</div>
				<p style="clear:both;"></p>
			</div>
		</div>
	</div>

	<input type="hidden" name="order_name" value="<?= $order_name ?>" />
	<input type="hidden" name="order_status" value="<?= $order_status ?>" />
	<input type="hidden" name="page" value="<?= $page ?>" />
	<input type="hidden" name="page2" value="<?= $page2 ?>" />
	</form>
	<form name="excel_down_form" action="" target="excel_iframe" method="post">
		<input type="hidden" name="grp_id" value="" />
		<input type="hidden" name="box_text" value="" />
		<input type="hidden" name="excel_sql" value="<?= $excel_sql ?>" />
	</form>
	<iframe name="excel_iframe" style="display:none;"></iframe>
	<form name="sub_4_form" id="sub_4_form">
		<input type="hidden" name="order_status" value="<?= $order_status ?>" />
		<input type="hidden" name="page" value="<?= $page ?>" />
		<input type="hidden" name="idx" value="<?= $_GET['idx'] ?>" />
		<input type="hidden" name="send_num" value="<?= $send_num ?>" />
		<input type="hidden" name="page2" value="<?= $page2 ?>" />
	</form>
</div>
</div>
<script>
	function deleteAddress() {
		var checked = false;
		var seq = "";
		$('input[name=idx_box]').each(function() {
			if ($(this).is(":checked") == true) {
				checked = true;
				if (seq != "") seq += ",";
				seq += $(this).val();
			}

		});
		if (checked == false) {
			alert('삭제할 번호를 선택해주세요');
			return;
		}

		var values = {
			"seq": seq
		};
		$.ajax({
			type: 'post',
			dataType: 'json',
			url: '/ajax/truncate_num.php',
			data: values,
			success: function(dataObj) {
				alert('완료되었습니다.');
			},
			error: function(request, status, error) {
				console.log('code: ' + request.status + "\n" + 'message: ' + request.responseText + "\n" + 'error: ' + error);
			}
		});
	}
</script>
<?
include_once "_foot.php";
?>