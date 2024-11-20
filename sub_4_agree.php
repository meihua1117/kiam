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
if ($_REQUEST['status'] == 1 || $_REQUEST['status'] == 2) {
	///금일발송가능 , 회발송가능 횟수
	$sql_sum = "select sum(user_cnt) as sumnum, sum(max_cnt) as summax ,sum(gl_cnt) as sumgl from Gn_MMS_Number where mem_id = '{$_SESSION['one_member_id']}'";
	$resul_sum = mysqli_query($self_con, $sql_sum);
	$row_sum_b = mysqli_fetch_array($resul_sum);
	//월별 총 발송가능 횟수
	$cu_user_cnt = $row_sum_b['sumnum'] ? $row_sum_b['sumnum'] : 0;
	$cu_today_cnt = $row_sum_b['summax'] ? $row_sum_b['summax'] : 0;
	$mon_text_pcount = $row_sum_b['summax'] ? ($row_sum_b['summax'] * 10) + ((199 * 20) - ($row_sum_b['sumgl'] * 20)) : 0;
	//-이번달
	$date_today = date("Y-m-d");
	$date_month = date("Y-m");
	$recv_num_ex_sum = 0;
	$sql_result = "select SUM(recv_num_cnt) from Gn_MMS where  up_date like '$date_month%' and mem_id = '{$_SESSION['one_member_id']}' ";
	$res_result = mysqli_query($self_con, $sql_result);
	$row_result = mysqli_fetch_array($res_result);
	$recv_num_ex_sum += $row_result[0] * 1;
	//-오늘발송
	$rec_cnt_today = 0;
	$sql_result2 = "select SUM(recv_num_cnt) from Gn_MMS where up_date like '$date_today%' and mem_id = '{$_SESSION['one_member_id']}' ";
	$res_result2 = mysqli_query($self_con, $sql_result2);
	$row_result2 = mysqli_fetch_array($res_result2);
	$rec_cnt_today += $row_result2[0] * 1;
	//-이번발송
	$rec_cnt_current = 0;
	$sql_result3 = "select uni_id from Gn_MMS where mem_id = '{$_SESSION['one_member_id']}' order by idx desc limit 1";
	$res_result3 = mysqli_query($self_con, $sql_result3);
	$row_result3 = mysqli_fetch_array($res_result3);
	$uni_id = substr($row_result3['uni_id'], 0, 10);

	$sql_result32 = "select idx,recv_num from Gn_MMS where mem_id = '{$_SESSION['one_member_id']}' and uni_id like '$uni_id%'";
	$res_result32 = mysqli_query($self_con, $sql_result32);
	while ($row_result32 = mysqli_fetch_array($res_result32))
		$rec_cnt_current += count(explode(",", $row_result32['recv_num']));
}
?>
<div class="big_1">
	<div class="m_div">

		<div class="left_sub_menu">
			<a href="./">홈</a> >
			<?php echo $left_str; ?>
		</div>
		<div class="right_sub_menu">
			<a href="sub_1.php">온리원문자란?</a> ㅣ<a href="sub_5.php">휴대폰등록</a> ㅣ <a href="sub_6.php">문자발송</a> ㅣ <a href="sub_4_return_.php">발신내역</a> ㅣ <a href="sub_4.php?status=5&status2=3">수신내역</a> ㅣ <a href="sub_4.php?status=6">수신여부</a>
		</div>

		<p style="clear:both;"></p>
	</div>
</div>
<div class="big_sub">
	<div class="m_div sub_4c">
		<form name="sub_4_form" action="" method="post" enctype="multipart/form-data">
			<?

			$sql_serch = " mem_id ='{$_SESSION['one_member_id']}' ";
			if ($_REQUEST['serch_colum'] && $_REQUEST['serch_text']) {
				$sql_serch .= " and {$_REQUEST['serch_colum']} like '{$_REQUEST['serch_text']}%' ";
			}
			$sql = "select count(idx) as cnt from Gn_MMS_Agree where $sql_serch ";
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
				$order_name = "idx";
			$intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);
			$sql = "select * from Gn_MMS_Agree where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
			$excel_sql = "select * from Gn_MMS_Agree where $sql_serch order by $order_name $order_status";
			$excel_sql = str_replace("'", "`", $excel_sql);
			$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

			$today_date = date("Y-m-d");
			$sql_today = "select count(idx) as today_cnt from Gn_MMS_Agree where mem_id='{$_SESSION['one_member_id']}' and reg_date like '$today_date%' ";
			$resul_today = mysqli_query($self_con, $sql_today);
			$row_today = mysqli_fetch_array($resul_today);
			?>
			<div class="sub_4_4_t1">

				<div class="sub_4_4_t2">

					<div class="sub_4_1_t3">
						<a href="sub_4.php?status=<?=$_GET['status']; ?>&status1=1">수신거부</a> &nbsp; | &nbsp;
						<a href="sub_4_agree.php?status=<?=$_GET['status']; ?>&status1=2" style="color:#000">수신동의</a></span>
					</div>
					<p style="clear:both"></p>
					<div class="sub_4_4_t3">

						<select name="serch_colum">
							<option value="">선택하세요</option>
							<?
							$select_arr = array("send_num" => "발신번호", "recv_num" => "수신번호");
							foreach ($select_arr as $key => $v) {
								$selected = $_REQUEST['serch_colum'] == $key ? "selected" : "";
							?>
								<option value="<?= $key ?>" <?= $selected ?>><?= $v ?></option>
							<?
							}
							?>
						</select>
						<input type="text" name="serch_text" value="<?= $_REQUEST['serch_text'] ?>" />
						<a href="javascript:void(0)" onclick="sub_4_form.submit();"><img src="images/sub_button_103.jpg" /></a>
					</div>

					<div class="sub_4_4_t4">
						<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="width:5%;"><label><input type="checkbox" onclick="check_all(this,'idx_box');" />번호</label></td>
								<td style="width:5%;">소유자명</td>
								<td style="width:10%;">발신번호</td>
								<td style="width:10%;">수신번호</td>
								<td style="width:10%;">문자제목</td>
								<td style="width:10%;">문자내용</td>
								<td style="width:10%;">첨부파일</td>
								<td style="width:5%;">등록경로</td>
								<td style="width:15%;">등록일시</td>
								<td style="width:10%;"></td>
							</tr>
							<?
							$i = 0;
							if ($intRowCount) {

								while ($row = mysqli_fetch_array($result)) {
									$sql_n = "select memo from Gn_MMS_Number where sendnum='{$row['send_num']}' ";
									$resul_n = mysqli_query($self_con, $sql_n);
									$row_n = mysqli_fetch_array($resul_n);
							?>
									<tr>
										<td><label><input type="checkbox" name="idx_box" value="<?= $row['idx'] ?>" /><?= $sort_no ?></label></td>
										<td><?= $row_n['memo'] ?></td>
										<td class="g_dt_name_<?= $i ?>"><?= $row['send_num'] ?></td>
										<td class="g_dt_name_<?= $i ?>" style="display:none;"><input type="text" value="<?= $row['send_num'] ?>" name="agree_send" /> </td>
										<td class="g_dt_num_<?= $i ?>"><?= $row['recv_num'] ?></td>
										<td class="g_dt_num_<?= $i ?>" style="display:none"><input type="text" value="<?= $row['recv_num'] ?>" name="agree_recv" /></td>
										<td><a href="javascript:void(0)" onclick="show_recv('show_title','<?= $i ?>','문자제목')"><?= str_substr($row['title'], 0, 20, 'utf-8') ?></a><input type="hidden" name="show_title" value="<?= $row['title'] ?>" /></td>
										<td><a href="javascript:void(0)" onclick="show_recv('show_content','<?= $i ?>','문자내용')"><?= str_substr($row['content'], 0, 30, 'utf-8') ?></a><input type="hidden" name="show_content" value="<?= $row['content'] ?>" /></td>
										<td><a href="javascript:void(0)" onclick="show_recv('show_jpg','<?= $c ?>','첨부파일')"><?= str_substr($row['jpg'], 0, 20, 'utf-8') ?></a><input type="hidden" name="show_jpg" value="<?= $row['jpg'] ?>" /></td>
										<td><?= $deny_type_arr[$row['status']] ?></td>
										<td><?= substr($row['reg_date'], 0, 16) ?></td>

										<td>
											<a href="javascript:void(0)" class="modify_btn_<?= $i ?> a_btn_2" onclick="g_dt_show_cencle('g_dt_name_','g_dt_num_','modify_btn_','<?= $i ?>')">수정</a>
											<a href="javascript:void(0)" class="modify_btn_<?= $i ?> a_btn_2" style="display:none;" onclick="agree_add(sub_4_form,'<?= $i ?>','<?= $row['idx'] ?>')">수정</a>

											<a href="javascript:void(0)" onclick="agree_del('<?= $row['idx'] ?>')" class="a_btn_2">삭제</a>
										</td>
									</tr>
								<?
									$i++;
									$sort_no--;
								}
								?>
								<tr>
									<td colspan="10">
										<? page_f($page, $page2, $intPageCount, "sub_4_form"); ?>
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
							<tr>
								<td colspan="5" style="text-align:left;line-height: 30px;">
									<!--
	                                      	<input type="file" name="excel_file" /> <input type="button" value="엑셀업로드" onclick="deny_excel_insert(sub_4_form,'deny')" />
													<br/>
		                                            <a href="javascript:void(0)" onclick="excel_down('excel_down/deny_down.php?down_type=2')" class="a_btn_2">수신거부샘플.xls</a> (97~2003통합문서)
		                                    -->

								</td>
								<td colspan="5" style="text-align:right;">
									새로 추가:
									<input type="text" name="agree_send" style="width:auto" placeholder="발신번호" itemname='발송번호' required />
									<input type="text" name="agree_recv" style="width:auto" placeholder="수신번호" itemname='수신번호' required />
									<input type="button" value="저장" onclick="agree_add(sub_4_form,'<?= $i ?>')" />
								</td>
							</tr>
						</table>
					</div>

					<div class="sub_4_4_t5">
						<div class="div_float_left">&nbsp;</div>
						<div class="div_float_right">
							<a href="javascript:void(0)" onclick="excel_down('excel_down/agree_down.php?down_type=1')"><img src="images/sub_button_107.jpg" /></a>
							<a href="javascript:void(0)" onclick="agree_del()"><img src="images/sub_button_109.jpg" /></a>
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
				alert('완료되었습니다.')
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