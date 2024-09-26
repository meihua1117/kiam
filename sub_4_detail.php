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

function arr_del($list_arr, $del_value) // 배열, 삭제할 값
{
	$b = array_search($del_value, $list_arr);
	if ($b !== FALSE) unset($list_arr[$b]);
	return $list_arr;
}

?>
<link href="/css/sub_4_re.css" rel="stylesheet" type="text/css">

<div class="big_sub">
	<div class="m_div sub_4c">
		<form name="sub_4_form" action="" method="post" enctype="multipart/form-data">
			<?
			$sql_serch = " 1=1 ";
			$sql_serch .= " and idx ='$_REQUEST[idx]' ";
			if ($_REQUEST['serch_fs_text'] != "") {
				$sql_serch .= " and recv_num like '%$_REQUEST[serch_fs_text]%' ";
			}
			if ($_REQUEST['search_fs_status'] != "") {
				$sql_serch .= " and status='$_REQUEST[search_fs_status]' ";
			}

			$sql_table = " Gn_MMS_status ";

			$excel_sql = "select * from $sql_table where $sql_serch ";
			$result = mysqli_query($self_con, $excel_sql) or die(mysqli_error($self_con));
			while ($row = mysqli_fetch_array($result)) {
				if ($row['status'] == "-1") {
					$fail[] = $row['recv_num'];
				}
			}

			$sql = "select count(*) as cnt from $sql_table where $sql_serch ";
			$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
			$row = mysqli_fetch_array($result);
			mysqli_free_result($result);
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
				$order_name = "regdate";
			$intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);



			$sql = "select * from $sql_table where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
			$excel_sql = "select * from $sql_table where $sql_serch order by $order_name $order_status";
			$excel_sql = str_replace("'", "`", $excel_sql);
			$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

			$sql_s = "select count(idx) as cnt from Gn_MMS_status where idx='$_GET[idx]' ";
			$resul_s = mysqli_query($self_con, $sql_s);
			$row_s = mysqli_fetch_array($resul_s);
			$status_total_cnt = $row_s[0];



			$sql_s = "select count(idx) as cnt from Gn_MMS_status where idx='$_GET[idx]' and status='0'";
			$resul_s = mysqli_query($self_con, $sql_s);
			$row_s = mysqli_fetch_array($resul_s);
			$success_cnt = $row_s[0];

			$sql_n = "select * from Gn_MMS where idx='$_GET[idx]' ";
			$resul_n = mysqli_query($self_con, $sql_n);
			$row_n = mysqli_fetch_array($resul_n);

			mysqli_free_result($resul_n);

			$recv_cnt = explode(",", $row_n['recv_num']);
			$total_cnt = count($recv_cnt);

			$sql_s = "select * from Gn_MMS_status where idx='$_GET[idx]' ";
			$resul_s = mysqli_query($self_con, $sql_s);
			while ($srow_s = mysqli_fetch_array($resul_s)) {
				$recv_cnt = arr_del($recv_cnt, $srow_s['recv_num']);
			}
			$recv_cnt = arr_del($recv_cnt, "");
			//print_R($fail);
			//print_R($recv_cnt);
			$fail = array_merge((array)$fail, (array)$recv_cnt);
			//    			    print_r($fail);


			?>
			<div class="sub_4_4_t1">
				<div class="sub_4_1_t3">
					<a href="sub_4.php?status=4" style="color:<?= $_REQUEST['status2'] == '' ? "#000" : "" ?>">발신내역 상세보기</a> <!--&nbsp;|&nbsp; <a href="sub_4.php?status=4&status2=2" style="color:<?= $_REQUEST['status2'] == 2 ? "#000" : "" ?>">예약내역 확인</a>-->
				</div>
				<div class="sub_4_4_t2">
					<div class="in_info">
						<table class="info_box_table" cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<th style="width:125px">총발송건수</th>
									<td style="width:378px"><?php echo $total_cnt; ?></td>
									<th style="width:125px">성공건수</th>
									<td style="width:384px"><?php echo $success_cnt; ?></td>
									<th style="width:125px">실패건수</th>
									<td style="width:384px"><?php echo $total_cnt - $success_cnt; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="sub_4_4_t3">

						수신번호 :
						<input type="text" name="serch_fs_text" value="<?= $_REQUEST[serch_fs_text] ?>" />
						<select name="search_fs_status">
							<option value="">전체</option>
							<option value="0" <?php echo $_REQUEST['search_fs_status'] == "0" ? "selected" : "" ?>>성공</option>
							<option value="-1" <?php echo $_REQUEST['search_fs_status'] == "-1" ? "selected" : "" ?>>실패</option>
						</select>
						<a href="javascript:void(0)" onclick="sub_4_form.submit();"><img src="images/sub_button_103.jpg" /></a>
					</div>
					<div class="sub_4_4_t4">
						<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td style="width:7%;"><label><input type="checkbox" onclick="check_all(this,'fs_idx');" />번호</label></td>
								<td style="width:10%;">발신번호</td>
								<td style="width:15%;">수신번호</td>
								<td style="width:12%;">전송일시</td>
								<td style="width:12%;">발송여부</td>
							</tr>
							<?
							if ($intRowCount) {
								$c = 0;
								while ($row = mysqli_fetch_array($result)) {
							?>
									<tr>
										<td><label><?= $sort_no ?></label></td>
										<td><?= $row['send_num'] ?></td>
										<td><?= $row['recv_num'] ?></td>
										<td><?= $row['regdate'] ?></td>
										<td><?= $row['status'] == "0" ? "성공" : "실패" ?></td>

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
						</div>
						<p style="clear:both;"></p>
					</div>
					<div class="box-footer" style="text-align:center">
						<button class="btn btn-primary" style="margin-right: 5px;" onclick="void(submitForm());return false;"><i class="fa fa-list"></i> 실패건 재전송</button>
						<button class="btn btn-primary" style="margin-right: 5px;" onclick="location='sub_4.php?status=4';return false;"><i class="fa fa-list"></i> 발송내역 목록</button>
					</div>
					<div class="box-footer" style="text-align:right">
						<a href="javascript:void(0)" onclick="excel_down('excel_down/result_down.php?idx=<?php echo $_REQUEST['idx'] ?>')"><img src="images/sub_button_107.jpg"></a>
					</div>
				</div>
			</div>


			<?
			?>
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
	function submitForm() {
		$('#resendForm').submit();
	}
</script>
</form>
<form method="post" action="sub_6.php" id="resendForm" name="resendForm">
	<textarea name="num" id="num" style="width:0px;height:0px"><?php echo implode(",", $fail); ?></textarea>
</form>
<?
mysqli_close($self_con);
include_once "_foot.php";
?>