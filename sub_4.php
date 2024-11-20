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

	$sql_result32 = "select SUM(recv_num_cnt) from Gn_MMS where mem_id = '{$_SESSION['one_member_id']}' and uni_id like '$uni_id%'";
	$res_result32 = mysqli_query($self_con, $sql_result32);
	$row_result32 = mysqli_fetch_array($res_result32);
	$rec_cnt_current += $row_result32[0] * 1;
}

$sql_mem_data = "select mem_phone from Gn_Member where mem_id='{$_SESSION['one_member_id']}'";
$res_mem_data = mysqli_query($self_con, $sql_mem_data);
$row_mem_data = mysqli_fetch_array($res_mem_data);
$mem_phone = $row_mem_data['mem_phone'];

$phone = str_replace("-", "", $mem_phone);
?>
<script type="text/javascript" src="jquery.lightbox_me.js"></script>
<script type="text/javascript" src="/js/mms_send.js"></script>
<script type="text/javascript" src="/plugin/tablednd/js/jquery.tablednd.0.7.min.js"></script>
<script>
	$(function() {
		$(".popbutton1").click(function() {
			$('.ad_layer1').lightbox_me({
				centered: true,
				onLoad: function() {}
			});
		})

		$(".popbutton5").click(function() {
			$('.ad_layer5').lightbox_me({
				centered: true,
				onLoad: function() {}
			});
		})

		$(".popbutton6").click(function() {
			$('.ad_layer6').lightbox_me({
				centered: true,
				onLoad: function() {}
			});
		})
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
	<div class="big_1">
		<div class="m_div">
			<div class="left_sub_menu">
				<a href="./">홈</a> >
				<?= $left_str; ?>
			</div>
			<div class="right_sub_menu">
				<a href="sub_1.php">폰문자소개</a> ㅣ<a href="sub_5.php">휴대폰등록</a> ㅣ <a href="sub_6.php">문자발송</a> ㅣ <a href="sub_4_return_.php">발신내역</a> ㅣ <a href="sub_4.php?status=5&status2=3">수신내역</a> ㅣ <a href="sub_4.php?status=6">수신여부</a>
			</div>
			<p style="clear:both;"></p>
		</div>
	</div>

	<div class="big_sub">
		<div class="m_div sub_4c">
			<form name="sub_4_form" action="" method="post" enctype="multipart/form-data">
				<?
				switch ($_REQUEST['status']) {
					case 1:
						$sql_serch = " mem_id ='{$_SESSION['one_member_id']}' ";
						if ($_REQUEST['lms_text'] && $_REQUEST['lms_select'])
							$sql_serch .= " and {$_REQUEST['lms_select']} like '{$_REQUEST['lms_text']}%' ";
						$sql = "select count(idx) as cnt from Gn_MMS_Number where $sql_serch ";
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
							$order_name = "user_cnt";
						$intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);
						$sql = "select * from Gn_MMS_Number where $sql_serch order by $order_name $order_status , idx desc limit $int,$intPageSize";
						$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
				?>
						<div class="sub_4_1_t3">
							<a href="sub_4.php?status=1&status2=1" style="color:<?= $_REQUEST['status2'] == 1 ? "#000" : "" ?>">휴대폰 등록 정보</a> &nbsp;|&nbsp;
							<a href="sub_4.php?status=1&status2=2" style="color:<?= $_REQUEST['status2'] == 2 ? "#000" : "" ?>">휴대폰 상세 정보</a>
						</div>
						<div style="background-color:#FFF;padding:20px;">
							<div class="sub_4_1_t2">
								<img src="images/sub_04_01_img_07.jpg" />
								<img src="images/sub_04_01_img_10.jpg" /> 마지막발송<span class="sub_4_1_t2_1">(<?= $rec_cnt_current ?>)</span> &nbsp;&nbsp; 오늘발송<span class="sub_4_1_t2_1">(<?= $rec_cnt_today ?>)</span> &nbsp;&nbsp; 이달발송<span class="sub_4_1_t2_1">(<?= $recv_num_ex_sum ?>)</span> &nbsp;&nbsp;
								<img src="images/sub_04_01_img_12.jpg" /> 이번발송<span class="sub_4_1_t2_2">(<?= $cu_user_cnt ?>)</span> &nbsp;&nbsp; 하루발송<span class="sub_4_1_t2_2">(<?= $cu_today_cnt ?>)</span> &nbsp;&nbsp; 한달발송<span class="sub_4_1_t2_2">(<?= $mon_text_pcount ?>)</span>
							</div>
							<div class="sub_4_1_t7">
								<div style="float:left">
									수신거부 개별등록:<input type="text" name="deny_num" style="width:auto" placeholder="수신번호" itemname='수신번호' />
									<input type="button" value="저장" onclick="deny_g_add()" />
								</div>
								<div style="float:right;">
									<select name="lms_select">
										<option value="">선택</option>
										<?
										$select_lms_arr = array("sendnum" => "휴대폰번호", "memo" => "소유자명");
										foreach ($select_lms_arr as $key => $v) {
											$selected = $_REQUEST['lms_select'] == $key ? "selected" : "";
										?>
											<option value="<?= $key ?>" <?= $selected ?>><?= $v ?></option>
										<? } ?>
									</select>
									<input type="text" name="lms_text" value="<?= $_REQUEST['lms_text'] ?>" />
									<a href="javascript:void(0)" onclick="sub_4_form.submit();"><img src="images/sub_button_703.jpg" /></a>
								</div>
								<p style="clear:both;"></p>
							</div>
							<? if ($_REQUEST['status2'] == 1) { ?>
								<div class="sub_4_1_t5">
									<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td class="d_font" style="width:6%;text-align:left"><label><input type="checkbox" onclick="check_all(this,'seq[]')" />번호</label></td>
											<td class="d_font" style="width:6%;">앱상태</td>
											<td class="x_font" style="width:13%;background-color:#CCC"><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'sendnum',sub_4_form.order_status.value)">휴대폰번호<? if ($_REQUEST['order_name'] == "sendnum") {
																																																						echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																																					} else {
																																																						echo '▼';
																																																					} ?></a>
												<div class="xt_font" style="margin-left:110px;">폰별 등록정보</div>
											</td>
											<td class="x_font" style="width:10%;background-color:#CCC"><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'memo',sub_4_form.order_status.value)">소유자명<? if ($_REQUEST['order_name'] == "memo") {
																																																					echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																																				} else {
																																																					echo '▼';
																																																				} ?></a></td>
											<td class="x_font" style="width:7%;background-color:#CCC"><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'memo2',sub_4_form.order_status.value)">통신사<? if ($_REQUEST['order_name'] == "memo2") {
																																																					echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																																				} else {
																																																					echo '▼';
																																																				} ?></a></td>
											<td class="x_font" style="width:7%;background-color:#CFF;">최대발송건<div class="xt_font" style="margin-left:60px;">폰별 문자발송 설정</div>
											</td>
											<td class="x_font" style="width:7%;background-color:#CFF;"><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'gl_cnt',sub_4_form.order_status.value)">개인문자<? if ($_REQUEST['order_name'] == "gl_cnt") {
																																																					echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																																				} else {
																																																					echo '▼';
																																																				} ?></a></td>
											<td class="x_font" style="width:6%;background-color:#CFF;"><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'cnt1',sub_4_form.order_status.value)">횟수1<? if ($_REQUEST['order_name'] == "cnt1") {
																																																					echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																																				} else {
																																																					echo '▼';
																																																				} ?></a></td>
											<td class="x_font" style="width:6%;background-color:#CFF;"><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'cnt2',sub_4_form.order_status.value)">횟수2<? if ($_REQUEST['order_name'] == "cnt2") {
																																																					echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																																				} else {
																																																					echo '▼';
																																																				} ?></a></td>
											<td class="d_font" style="width:6%;background-color:#FF9;"><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'user_cnt',sub_4_form.order_status.value)">이번<br />발송<? if ($_REQUEST['order_name'] == "user_cnt") {
																																																							echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																																						} else {
																																																							echo '▼';
																																																						} ?></a></td>
											<td class="x_font" style="width:7%;">오늘발송<div class="xt_font" style="margin-left:30px;">폰별 문자발송 결과</div>
											</td>
											<td class="x_font" style="width:7%;">이달발송</td>
											<td class="x_font" style="width:7%;">수신처</td>
										</tr>
										<? if ($intRowCount) {
											$i = 0;
											while ($row = mysqli_fetch_array($result)) {
												$sql_result2_g = "select SUM(recv_num_cnt) from Gn_MMS where up_date like '$date_today%' and send_num='{$row['sendnum']}' ";
												$res_result2_g = mysqli_query($self_con, $sql_result2_g);
												$today_cnt_1 = 0;
												$row_result2_g = mysqli_fetch_array($res_result2_g);
												$today_cnt_1 += $row_result2_g[0] * 1;

												if ($today_cnt_1 > $row['max_cnt']) {
													$sql_cnt_u = " update Gn_MMS_Number set user_cnt=0 where idx='{$row['idx']}' ";
													mysqli_query($self_con, $sql_cnt_u);
												}

												$month_cnt_1 = 0;
												$sql_result_g = "select SUM(recv_num_cnt) from Gn_MMS where up_date like '$date_month%' and send_num='{$row['sendnum']}' ";
												$res_result_g = mysqli_query($self_con, $sql_result_g);
												$row_result_g = mysqli_fetch_array($res_result_g);
												$month_cnt_1 += $row_result_g[0] * 1;

												$ssh_cnt = 0;
												$sql_ssh = "select recv_num from Gn_MMS where send_num='{$row['sendnum']}' and up_date like '$date_month%' group by(recv_num)";
												$resul_ssh = mysqli_query($self_con, $sql_ssh);
												while ($row_ssh = mysqli_fetch_array($resul_ssh)) {
													$ssh_arr = array_unique(explode(",", $row_ssh['recv_num']));
													sort($ssh_arr);
													$ssh_cnt += count($ssh_arr);
												}
										?>
												<tr>
													<td style="text-align:left;">
														<label><input type="checkbox" name="seq[]" value="<?= $row['sendnum'] ?>" /><?= $sort_no ?></label>
													</td>
													<td><span id='btn_<?= $row['sendnum'] ?>' class="btn_option_red">체크전</span></td>
													<td><?= $row['sendnum'] ?></td>
													<td><input type="text" name="memo" value="<?= $row['memo'] ?>" /></td>
													<td>
														<select name="memo2" onchange="ssc_show('<?= $i ?>',this.value)">
															<option value="">선택하세요</option>
															<? foreach ($agency_arr as $key => $v) {
																$selected = $row['memo2'] == $key ? "selected" : "";
															?>
																<option value="<?= $key ?>" <?= $selected ?>><?= $key ?></option>
															<? } ?>
														</select>
													</td>
													<td><span class="max_cnt_c"><?= $row['max_cnt'] ?></span><input type="hidden" name="max_cnt" value="<?= $row['max_cnt'] ?>" /></td>
													<td><input type="text" name="gl_cnt" value="<?= $row['gl_cnt'] ?>" onkeyup="jiajian_oo('<?= $i ?>')" /></td>
													<td><?= $row['cnt1'] ?>/10 <input type="hidden" name="cnt1" value="<?= $row['cnt1'] ?>" /></td>
													<td><?= $row['cnt2'] ?>/20 <input type="hidden" name="cnt2" value="<?= $row['cnt2'] ?>" /></td>
													<td><input type="text" name="user_cnt" <?= $row['cnt1'] == 10 && $row['cnt2'] == 20 ? "disabled" : "" ?> value="<?= $row['user_cnt'] ?>" onkeyup="jiajian_oo_1('<?= $i ?>','<?= $today_cnt_1 ?>')" /></td>
													<td><?= $today_cnt_1 ?></td>
													<td><?= $month_cnt_1 ?></td>
													<td><?= $ssh_cnt ?>/<span class="agency_c"><?= $agency_arr[$row['memo2']] ?></span></td>
												</tr>
											<?
												$i++;
												$sort_no--;
											}
											?>
											<tr>
												<td colspan="13">
													<?
													page_f($page, $page2, $intPageCount, "sub_4_form");
													?>
												</td>
											</tr>
										<? } else { ?>
											<tr>
												<td colspan="13">등록된 내용이 없습니다.</td>
											</tr>
										<? } ?>
									</table>
								</div>
								<div style="text-align:center;margin-top:20px;">
									<a href="javascript:void(0)" onclick="group_create()" style="float:left; margin-right:10px;"><img src="images/btn_address_book _groups_create.gif" /></a>
									<a href="javascript:void(0)" onclick="no_msg_del()" style="float:left;"><img src="images/sub_04_01_img_35.jpg" /></a>
									<a href="javascript:void(0)" onclick="set_save()"><img src="images/sub_04_01_img_37.jpg" /></a>
									<a href="javascript:void(0)" onclick="select_app_check()" style="float:right;"><img src="images/sub_04_01_img_40.jpg" /></a>
									<p style="clear:both;"></p>
								</div>
							<? } else { ?>
								<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td style="text-align:left"><label><input type="checkbox" onclick="check_all(this,'seq[]')" />번호</label></td>
										<td><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'memo',sub_4_form.order_status.value)">소유자명<? if ($_REQUEST['order_name'] == "memo") {
																																						echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																					} else {
																																						echo '▼';
																																					} ?></a></td>
										<td><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'sendnum',sub_4_form.order_status.value)">휴대폰 번호<? if ($_REQUEST['order_name'] == "sendnum") {
																																							echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																						} else {
																																							echo '▼';
																																						} ?></a></td>
										<td><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'reg_date',sub_4_form.order_status.value)">등록일시<? if ($_REQUEST['order_name'] == "reg_date") {
																																							echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																						} else {
																																							echo '▼';
																																						} ?></a></td>
										<td><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'memo2',sub_4_form.order_status.value)">통신사<? if ($_REQUEST['order_name'] == "memo2") {
																																						echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																					} else {
																																						echo '▼';
																																					} ?></a></td>
										<td>DB유무</td>
										<td>기종</td>
										<td>메모사항</td>
										<td><a href="javascript:void(0)" onclick="order_sort(sub_4_form,'end_date',sub_4_form.order_status.value)">사용완료시간<? if ($_REQUEST['order_name'] == "end_date") {
																																								echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																							} else {
																																								echo '▼';
																																							} ?></a>(<a href="javascript:void(0)" onclick="order_sort(sub_4_form,'end_status',sub_4_form.order_status.value)">상태<? if ($_REQUEST['order_name'] == "end_status") {
																																																																					echo $_REQUEST['order_status'] == "desc" ? '▼' : '▲';
																																																																				} else {
																																																																					echo '▼';
																																																																				} ?></a>)</td>
									</tr>
									<? if ($intRowCount) {
										while ($row = mysqli_fetch_array($result)) {
											$sql_db = "select count(seq) as cnt from sm_data where dest='{$row['sendnum']}' ";
											$resul_db = mysqli_query($self_con, $sql_db);
											$row_db = mysqli_fetch_array($resul_db);
									?>
											<tr>
												<td style="width:5%;text-align:left;"><label><input type="checkbox" name="seq[]" value="<?= $row['sendnum'] ?>" /><?= $sort_no ?></label></td>
												<td style="width:10%;"><?= $row['memo'] ?></td>
												<td style="width:10%;"><?= $row['sendnum'] ?></td>
												<td style="width:10%;"><?= substr($row['reg_date'], 0, 10) ?></td>
												<td style="width:10%;"><?= $row['memo2'] ?></td>
												<td style="width:5%;">(<?= $row_db['cnt'] ?>)</td>
												<td style="width:10%;"><input type="text" name="device" value="<?= $row['device'] ?>" /></td>
												<td style="width:23%;"><input type="text" name="memo3" value="<?= $row['memo3'] ?>" /></td>
												<td style="width:17%;font-size:12px;"><?= $row['end_status'] != "M" ? substr($row['end_date'], 0, 10) : "" ?> (<?= $pay_phone_status[$row['end_status']] ?>)</td>
											</tr>
										<? $sort_no--;
										} ?>
										<tr>
											<td colspan="9">
												<?
												page_f($page, $page2, $intPageCount, "sub_4_form");
												?>
											</td>
										</tr>
									<? } else { ?>
										<tr>
											<td colspan="9">등록된 내용이 없습니다.</td>
										</tr>
									<? } ?>
								</table>
								<div style="text-align:center;margin-top:20px;">
									<a href="javascript:void(0)" onclick="num_del()" style="float:left;"><img src="images/sub_04_01_img_33.jpg" /></a>
									<a href="javascript:void(0)" onclick="set_save_xx()"><img src="images/sub_04_01-2_img_32.jpg" /></a>
									<a href="javascript:void(0)" onclick="excel_down('excel_down/box_down.php','','seq[]')" style="float:right;"><img src="images/sub_button_107.jpg" /></a>
									<p style="clear:both;"></p>
								</div>
							<? } ?>
						</div>
					<?
						break;
					case 2: ?>
						<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
						<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>
						<script language="javascript">
							$(function() {
								if (document.getElementsByName('group_num')[0].value || document.getElementsByName('num')[0].value) {
									<? if ($_REQUEST['deny_wushi']) { ?>
										numchk('4');
									<? } ?>
								}
							});
						</script>
						<div class="sub_4_1_t2">
							<img src="images/sub_04_01_img_07.jpg" />
							<img src="images/sub_04_01_img_10.jpg" /> 마지막발송<span class="sub_4_1_t2_1">(<?= $rec_cnt_current ?>)</span> &nbsp;&nbsp; 오늘발송<span class="sub_4_1_t2_1">(<?= $rec_cnt_today ?>)</span> &nbsp;&nbsp; 이달발송<span class="sub_4_1_t2_1">(<?= $recv_num_ex_sum ?>)</span> &nbsp;&nbsp;
							<img src="images/sub_04_01_img_12.jpg" /> 이번발송<span class="sub_4_1_t2_2">(<?= $cu_user_cnt ?>)</span> &nbsp;&nbsp; 하루발송<span class="sub_4_1_t2_2">(<?= $cu_today_cnt ?>)</span> &nbsp;&nbsp; 한달발송<span class="sub_4_1_t2_2">(<?= $mon_text_pcount ?>)</span>
						</div>
						<div>
							<div class="sub_4_2_t1">
								<div><img src="images/sub_button_03.jpg" /></div>
								<div class="div_240">
									<div class="a1">
										<div class="b1">기본서비스</div>
										<div class="b2 select_group_div">그룹번호</div>
										<div class="div_2px"><textarea name="group_num" itemname='그룹번호' placeholder="그룹번호" onblur="numchk('4')"><?= $_REQUEST['group_num'] ?></textarea></div>
										<div class="b2">개별번호</div>
										<div class="div_2px"><textarea name="num" itemname='전화번호' placeholder="전화번호" onblur="numchk('4')"><?= $_REQUEST['num'] ?></textarea></div>
										<div class="b4">
											<div class="div_2px"><label><input type="radio" name="type" value="1" />묶음발송</label> <label><input type="radio" name="type" value="0" />개별발송</label></div>
											<div class="div_2px">
												<div style="float:left;">
													<input type="text" name="delay" placeholder="발송간격" value="<?= $_REQUEST['delay'] ? $_REQUEST['delay'] : 5 ?>" onblur="send_delay(sub_4_form,this,1)" onkeyup="send_delay(sub_4_form,this,1)" />~<input type="text" name="delay2" placeholder="발송간격" value="<?= $_REQUEST['delay'] ? $_REQUEST['delay'] : 15 ?>" onblur="send_delay(sub_4_form,this,2)" />초
												</div>
												<div style="float:right;"><input type="text" name="close" placeholder="발송제한" value="<?= $_REQUEST['close'] ?>" onkeyup="if(isNaN(this.value)){this.value='';}" onblur="if(isNaN(this.value)){this.value='';}" />시(21시)</div>
												<p style="clear:both;"></p>
											</div>
											<div class="c1">
												<div>발송제외(<a href="javascript:void(0)" onclick="show_recv('deny_num','4','발송제외')" class="num_check_c">0</a>)</div>
												<div>실제발송(<span class="num_check_c">0</span>)</div>
												<div style="margin-right:0;">총건수(<span class="num_check_c">0</span>)</div>
												<p style="clear:both;"></p>
											</div>
											<div class="c1">
												<div>처리후중복제거(<a href="javascript:void(0)" onclick="show_recv('deny_num','6','처리후중복제거된번호')" class="num_check_c">0</a>)</div>
												<div>중복제거(<a href="javascript:void(0)" onclick="show_recv('deny_num','7','중복제거된번호')" class="num_check_c">0</a>)</div>
											</div>
											<div class="c1">
												<div><label><input type="checkbox" name="deny_wushi[]" <?= $_REQUEST['deny_wushi'][0] ? "checked" : "" ?> onclick="numchk('0');type_check()" />수신불가</label>(<a href="javascript:void(0)" onclick="show_recv('deny_num','0','수신불가')" class="num_check_c">0</a>)</div>
												<div><label><input type="checkbox" name="deny_wushi[]" <?= $_REQUEST['deny_wushi'][1] ? "checked" : "" ?> onclick="numchk('1');type_check()" />없는번호</label>(<a href="javascript:void(0)" onclick="show_recv('deny_num','1','없는번호')" class="num_check_c">0</a>)</div>
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
											</div>
										</div>
									</div>
									<div class="a2">
										<div class="b1">부가서비스 <?= $fujia_pay ? "" : "<a href='pay.php'>(결제후 사용가능합니다.)</a>" ?></div>
										<div class="b2">
											<div style="float:left;">
												<label><input type="checkbox" <?= $fujia_pay ? "" : "disabled" ?> name="deny_wushi[]" <?= $_REQUEST['deny_wushi'][3] ? "checked" : "" ?> onclick="numchk('3');type_check()" />수신거부
													(<a href="javascript:void(0)" onclick="show_recv('deny_num','3','수신거부')" class="num_check_c">0</a>)</label>
											</div>
											<div style="float:right">
												<label><input type="checkbox" <?= $fujia_pay ? "" : "disabled" ?> name="deny_msg" onclick="deny_msg_click(this,0)" />수신거부 문자</label>
												<div class="deny_msg_span">OFF</div>
												<p style="clear:both"></p>
											</div>
											<p style="clear:both"></p>
										</div>
										<div class="b2">
											<div style="float:left;">
												<label><input type="checkbox" name="ssh_check" onclick="deny_msg_click(this,1);type_check()" />수신처 우선발송</label>
												<div class="deny_msg_span">OFF</div>
												<p style="clear:both"></p>
											</div>
											<p style="clear:both;"></p>
										</div>
										<div class="b2">
											<div style="float:left;">
												<label><input type="checkbox" name="ssh_check" onclick="deny_msg_click(this,2);numchk('5');type_check()" />수신처 제외발송</label>(<a href="javascript:void(0)" onclick="show_recv('deny_num','5','수신처번호')" class="num_check_c">0</a>)
												<div class="deny_msg_span">OFF</div>
												<p style="clear:both"></p>
											</div>
											<p style="clear:both;"></p>
										</div>
										<div class="b2">
											<div class="div_2px">이미지미리보기</div>
											<div id="preview_wrapper" class="img_view" style="display:inline-block;width:100%;">
												<div id="preview_fake" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale);">
													<img id="preview" onload="onPreviewLoad(this)" />
												</div>
											</div>
											<img id="preview_size_fake" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image);visibility:hidden; height:0;" />
											<div class="div_2px">
												<input type="file" name="upimage" onChange="onUploadImgChange(this);sub_4_form.action='up_image.php?i=0';sub_4_form.target='excel_iframe';sub_4_form.submit();" />
											</div>
											<div><input type="hidden" name="upimage_str" /></div>
										</div>
										<div>
											<? if ($fujia_pay) { ?>
												<a href="javascript:void(0)" onclick="window.open('msg_serch.php?status=1&status2=2','msg_serch','top=0,left=0,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no, status=no')">
													<img src="images/btn_Importing-photos.gif" /></a>
												<a href="javascript:void(0)" onclick="window.open('onebook_serch.php?status=1','onebook_serch','top=0,left=0,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no, status=no')">
													<img src="images/btn_search_-wonbuk02.gif" /></a>
											<? } else { ?>
												<a href="javascript:void(0)" onclick="if(confirm('결제후 사용가능합니다.\n\n 결제페이지로 이동하시겠습니까?')){location.replace('pay.php')}">
													<img src="images/btn_Importing-photos.gif" /></a>
												<a href="javascript:void(0)" onclick="if(confirm('결제후 사용가능합니다.\n\n 결제페이지로 이동하시겠습니까?')){location.replace('pay.php')}">
													<img src="images/btn_search_-wonbuk02.gif" /></a>
											<? } ?>
										</div>
									</div>
									<div class="a3">
										<div class="b1">예약서비스</div>
										<div>
											<input type="text" name="rday" onfocus="type_check();" placeholder="예약발송(일)" id="rday" value="<?= $_REQUEST['rday'] ?>" style="width:100px;" />
											<select name="htime" style="width:50px;">
												<? for ($i = 9; $i < 22; $i++) {
													$iv = $i < 10 ? "0" . $i : $i;
													$selected = $_REQUEST['htime'] == $iv ? "selected" : "";
												?>
													<option value="<?= $iv ?>" <?= $selected ?>><?= $iv ?></option>
												<? } ?>
											</select>
											<select name="mtime" style="width:50px;">
												<option value="">분</option>
												<? for ($i = 0; $i < 31; $i += 30) {
													$iv = $i == 0 ? "00" : $i;
													$selected = $_REQUEST['mtime'] == $iv ? "selected" : "";
												?>
													<option value="<?= $iv ?>" <?= $selected ?>><?= $iv ?></option>
												<? } ?>
											</select>
										</div>
									</div>
									<div class="a4">
										<div class="div_2px"><input type="text" name="title" itemname='제목' required placeholder="제목" style="width:100%;" value="<?= $_REQUEST['title'] ?>" /></div>
										<div class="div_2px">
											<textarea name="txt" itemname='내용' id='txt' required placeholder="내용" onkeydown="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);" onkeyup="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();" onfocus="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();"><?= $_REQUEST['txt'] ?></textarea>
											<input type="hidden" name="onebook_status" value="N" />
											<input type="hidden" name="onebook_url" value="" />
										</div>
										<div class="div_2px" style="height:28px;line-height:28px;">
											<div style="float:left;">
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
											<div style="float:left;margin-left:10px;">
												<a href="javascript:void(0)" onclick="ml_view('txt','0','미리보기')">미리보기</a>
											</div>
											<div style="float:right">
												<label><input type="checkbox" name="save_mms" <?= $_REQUEST['save_mms'] ? "checked" : "" ?> />문자발송후 저장하기</label>
											</div>
											<p style="clear:both;"></p>
										</div>
										<div class="b2">
											<div style="float:left">
												<a href="javascript:void(0)" onclick="window.open('msg_serch.php?status=1&status2=1','msg_serch','top=0,left=0,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no, status=no')">
													<img src="images/sub_04_02-2_btn_81.jpg" /></a>
											</div>
											<div style="float:right;">
												<label><input type="checkbox" name="fs_msg" onclick="deny_msg_click(this,3)" />발신전용안내</label>
												<input type="hidden" name="fs_txt" value="발신전용안내" />
												<div class="deny_msg_span">OFF</div>
												<p style="clear:both;"></p>
											</div>
											<p style="clear:both;"></p>
										</div>
									</div>
									<div><a href="javascript:void(0)" onclick="send_msg(sub_4_form)"><img src="images/sub_button_68.jpg" /></a></div>
								</div>
							</div>
							<div class="sub_4_2_t2">
								<div><img src="images/sub_button_05.jpg" /></div>
								<div class="div_340">
									<div class="d_font">엑셀 업로드</div>
									<div>
										<div style="position:absolute;margin-left:240px;margin-top:-20px;">97~2003통합문서</div>
										<input type="file" name='excel_file' />
										<a href="javascript:void(0)" onclick="excel_down('excel_down/excel_down.php?down_type=2')" class="a_btn_2">업로드샘플.xls</a>
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
											<? if ($intRowCount) {
												$g = 0;
												while ($row = mysqli_fetch_array($result)) {
											?>
													<tr>
														<td><label><input type="checkbox" value="<?= $row['idx'] ?>" name="chk" id="chk" onclick="group_choice('<?= $g ?>')" /><?= $sort_no ?></label></td>
														<td class="group_title_<?= $g ?>">
															<a href="javascript:void(0)" onclick="show_detail('group_detail.php?grp_id=<?= $row['idx'] ?>','<?= $g ?>')"><?= str_substr($row['grp'], 0, 20, "utf-8") ?></a>
															<a href="javascript:void(0)" onclick="g_dt_show_cencle('group_title_','','','<?= $g ?>')" class="a_btn" style="background-color:#4f81bd;color:#FFF;float:right;">Re</a>
														</td>
														<td class="group_title_<?= $g ?>" style="display:none;">
															<input type="text" name="group_title" value="<?= $row['grp'] ?>" style="width:65%;" />
															<a href="javascript:void(0)" onclick="group_title_modify('<?= $row['idx'] ?>','<?= $g ?>')" class="a_btn">저장</a>
															<a href="javascript:void(0)" onclick="g_dt_cencle('group_title_','','','<?= $g ?>')">x</a>
														</td>
														<td><?= substr($row['reg_date'], 2, 9) ?></td>
														<td><?= $row['count'] ?></td>
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
											<? } else { ?>
												<tr>
													<td colspan="5">
														내용이 없습니다.
													</td>
												</tr>
											<? } ?>
										</table>
									</div>
									<div style="text-align:right">
										<a href="javascript:void(0)" onclick="all_group_del()"><img src="images/sub_button_39.jpg" /></a>
									</div>
								</div>
							</div>
							<div class="sub_4_2_t3">
								<div><img src="images/sub_button_007.jpg" /></div>
								<div class="div_280">
									<div style="margin:20px 0 20px 0">
										<?
										$sql = "select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' and user_cnt>0  order by user_cnt desc , idx desc";
										$resul = mysqli_query($self_con, $sql);
										$intRowCount = mysqli_num_rows($resul);
										?>
										<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td style="width:45%;text-align:left;"><label><input type="checkbox" onclick="check_all(this,'go_num');send_sj_fun()" />휴대폰 번호</label></td>
												<td style="width:25%;">이름</td>
												<td style="width:15%;">이번<br />발송</td>
												<td style="width:15%;">상태</td>
											</tr>
											<? if ($intRowCount) {
												$today_send_total = 0;
												while ($row = mysqli_fetch_array($resul)) {
													$is_send = true;
													$today_reg = date("Y-m-d");
													$sql_result2_g = "select SUM(recv_num_cnt) from Gn_MMS where reg_date like '$today_reg%' and send_num='{$row['sendnum']}' ";
													$res_result2_g = mysqli_query($self_con, $sql_result2_g) or die(mysqli_error($self_con));
													$today_cnt_1 = 0;
													$row_result2_g = mysqli_fetch_array($res_result2_g);
													$today_cnt_1 += $row_result2_g[0] * 1;

													if ($today_cnt_1 > $row['max_cnt']) {
														$is_send = false;
														$send_status = "<span style='color:red'>불가</span>";
													} else {
														$is_send = true;
														$today_send_total += $row['user_cnt'];
														$send_status = "가능";
													}
											?>
													<tr>
														<td style="text-align:left;">
															<label><input type="checkbox" name="go_num" value="<?= $row['sendnum'] ?>" <?= !$is_send ? "disabled" : "" ?> onclick="send_sj_fun()" /><?= $row['sendnum'] ?></label>
															<input type="hidden" name="go_user_cnt" value="<?= $row['user_cnt'] ?>" />
															<input type="hidden" name="go_max_cnt" value="<?= $row['max_cnt'] ?>" />
															<input type="hidden" name="go_memo2" value="<?= $row['memo2'] ?>" />
															<input type="hidden" name="go_cnt1" value="<?= $row['cnt1'] ?>" />
															<input type="hidden" name="go_cnt2" value="<?= $row['cnt2'] ?>" />
														</td>
														<td><?= $row['memo'] ?></td>
														<td><?= $row['user_cnt'] ?></td>
														<td><?= $send_status ?></td>
													</tr>
												<? } ?>
												<tr>
													<td colspan="4" style="text-align:right;"><span class="send_sj_c">0</span> / <?= number_format($today_send_total) ?></td>
												</tr>
											<? } else { ?>
												<tr>
													<td colspan="4">
														발송가능한 휴대폰이 없습니다.
													</td>
												</tr>
											<? } ?>
										</table>
									</div>
								</div>
							</div>
							<p style="clear:both;"></p>
						</div>
					<?
						break;
					case 3:
					?>
						<div class="sub_4_3_t1">
							<div class="sub_4_1_t3">
								<a href="sub_4.php?status=3&status2=1" style="color:<?= $_REQUEST['status2'] == 1 ? "#000" : "" ?>">LMS 문자저장</a> &nbsp;|&nbsp;
								<a href="sub_4.php?status=3&status2=2" style="color:<?= $_REQUEST['status2'] == 2 ? "#000" : "" ?>">포토 문자 저장</a> &nbsp;|&nbsp;
								<a href="sub_4.php?status=3&status2=3" style="color:<?= $_REQUEST['status2'] == 3 ? "#000" : "" ?>">원북 문자 저장</a>
							</div>
							<?
							switch ($_REQUEST['status2']) {
								case 1:
									$sql_serch = " mem_id ='{$_SESSION['one_member_id']}' and msg_type='A' ";
									if ($_REQUEST['lms_text']) {
										if ($_REQUEST['lms_select'])
											$sql_serch .= " and {$_REQUEST['lms_select']} like '{$_REQUEST['lms_text']}%' ";
										else
											$sql_serch .= " and (message like '{$_REQUEST['lms_text']}%'  or title like '{$_REQUEST['lms_text']}%') ";
									}
									$sql = "select count(idx) as cnt from Gn_MMS_Message where $sql_serch ";
									$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
									$row = mysqli_fetch_array($result);
									$intRowCount = $row['cnt'];
									if ($_POST['page'])
										$page = (int)$_POST['page'];
									else
										$page = 1;
									if ($_POST['page2'])
										$page2 = (int)$_POST['page2'];
									else
										$page2 = 1;
									if (!$_POST['lno'])
										$intPageSize = 8;
									else
										$intPageSize = $_POST['lno'];
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
									$sql = "select * from Gn_MMS_Message where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
									$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
							?>
									<div class="sub_4_3_t2">
										<div class="sub_4_4_t3">
											<select name="lms_select">
												<option value="">전체</option>
												<?
												$select_lms_arr = array("title" => "제목", "message" => "내용");
												foreach ($select_lms_arr as $key => $v) {
													$selected = $_REQUEST['lms_select'] == $key ? "selected" : "";
												?>
													<option value="<?= $key ?>" <?= $selected ?>><?= $v ?></option>
												<? } ?>
											</select>
											<input type="text" name="lms_text" value="<?= $_REQUEST['lms_text'] ?>" />
											<input type="image" src="images/sub_button_703.jpg" />
										</div>
										<div class="d_div_font">LMS문자저장하기</div>
										<div class="sub_4_3_t2_left">
											<div><input type="text" name="title" placeholder="제목" value="" /></div>
											<div>
												<textarea name="lms_content" placeholder="내용" onkeydown="textCounter(document.getElementsByName('lms_content')[0],'wenzi_cnt',1800,0)" onfocus="textCounter(document.getElementsByName('lms_content')[0],'wenzi_cnt',1800,0)" onkeyup="textCounter(document.getElementsByName('lms_content')[0],'wenzi_cnt',1800,0)"></textarea>
											</div>
											<div style="margin-bottom:14px;"><span class="wenzi_cnt">0</span> Byte</div>
											<div><a href="javascript:void(0)" onclick="lms_save(sub_4_form,'0','add','<?= $intRowCount + 1 ?>','')"><img src="images/sub_button_723.jpg" /></a></div>
										</div>
										<div class="sub_4_3_t2_right">
											<?
											if ($intRowCount) {
												$i = 1;
												while ($row = mysqli_fetch_array($result)) {
											?>
													<div class="sub_4_3_t2_right_1">
														<div class="sub_4_3_t2_right_1_1">
															<div style="margin-bottom:2px;">
																<input type="text" name="title" placeholder="제목" value="<?= $row['title'] ?>" />
															</div>
															<div>
																<textarea name="lms_content" placeholder="내용" onkeydown="textCounter(document.getElementsByName('lms_content')[<?= $i ?>],'wenzi_cnt',2000,'<?= $i ?>')" onkeyup="textCounter(document.getElementsByName('lms_content')[<?= $i ?>],'wenzi_cnt',2000,'<?= $i ?>')"><?= $row['message'] ?></textarea>
															</div>
														</div>
														<div style="margin-bottom:14px;"><span class="wenzi_cnt"></span> Byte</div>
														<div class="sub_4_3_t2_right_1_3">
															<a href="javascript:void(0)" onclick="lms_save(sub_4_form,'<?= $i ?>','modify','','<?= $row['idx'] ?>')"><img src="images/sub_button_711.jpg" /></a>
															<a href="javascript:void(0)" onclick="lms_del('<?= $row['idx'] ?>')"><img src="images/sub_button_713.jpg" /></a>

														</div>
													</div>
													<script language="javascript">
														$(function() {
															textCounter(document.getElementsByName('lms_content')[<?= $i ?>], 'wenzi_cnt', 2000, '<?= $i ?>')
														});
													</script>
												<?
													$i++;
												}
												?>
												<p style="clear:both;"></p>
												<div>
													<?
													page_f($page, $page2, $intPageCount, "sub_4_form");
													?>
												</div>
											<?
											} else {
											?>
												<div style="text-align:center">등록된 내용이 없습니다.</div>
											<?
											}
											?>
										</div>
										<p style="clear:both;"></p>
									</div>
								<?
									break;
								case 2:
									$sql_serch = " mem_id ='{$_SESSION['one_member_id']}' and msg_type='B' ";
									if ($_REQUEST['lms_text']) {
										if ($_REQUEST['lms_select'])
											$sql_serch .= " and {$_REQUEST['lms_select']} like '{$_REQUEST['lms_text']}%' ";
										else
											$sql_serch .= " and (message like '{$_REQUEST['lms_text']}%'  or title like '{$_REQUEST['lms_text']}%') ";
									}
									$sql = "select count(idx) as cnt from Gn_MMS_Message where $sql_serch ";
									$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
									$row = mysqli_fetch_array($result);
									$intRowCount = $row['cnt'];
									if ($_POST['page'])
										$page = (int)$_POST['page'];
									else
										$page = 1;
									if ($_POST['page2'])
										$page2 = (int)$_POST['page2'];
									else
										$page2 = 1;
									if (!$_POST['lno'])
										$intPageSize = 8;
									else
										$intPageSize = $_POST['lno'];
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
									$sql = "select * from Gn_MMS_Message where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
									$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
								?>
									<div class="sub_4_3_t2">
										<div class="sub_4_4_t3">
											<select name="photo_select">
												<option value="">전체</option>
												<?
												$select_photo_arr = array("title" => "제목", "message" => "내용");
												foreach ($select_photo_arr as $key => $v) {
													$selected = $_REQUEST['photo_select'] == $key ? "selected" : "";
												?>
													<option value="<?= $key ?>" <?= $selected ?>><?= $v ?></option>
												<?
												}
												?>
											</select>
											<input type="text" name="photo_text" value="<?= $_REQUEST['photo_text'] ?>" />
											<input type="image" src="images/sub_button_703.jpg" />
										</div>
										<div class="d_div_font">포토문자저장하기</div>
										<div class="sub_4_3_t2_left">
											<div><input type="text" name="title" placeholder="제목" value="" /></div>
											<div style="margin-top:20px;">이미지미리보기</div>
											<div class="img_view"></div>
											<div><input type="file" name="upimage[]" onChange="sub_4_form.action='up_image2.php?i=0';sub_4_form.target='excel_iframe';sub_4_form.submit();" /></div>
											<div><input type="hidden" name="upimage_str" /></div>
											<div style="margin-top:100px;"><a href="javascript:void(0)" onclick="lms_save(sub_4_form,'0','add','<?= $intRowCount + 1 ?>','')"><img src="images/sub_button_723.jpg" /></a></div>
										</div>
										<div class="sub_4_3_t2_right">
											<?
											if ($intRowCount) {
												$i = 1;
												while ($row = mysqli_fetch_array($result)) {
											?>
													<div class="sub_4_3_t2_right_1">
														<div class="sub_4_3_t2_right_1_1">
															<div style="margin-bottom:2px;">
																<input type="text" name="title" placeholder="제목" value="<?= $row['title'] ?>" />
															</div>
															<div class="img_view_1"><img src="<?= $row['img'] ?>" /></div>
															<div><input type="file" name="upimage[]" onChange="sub_4_form.action='up_image2.php?i=<?= $i ?>';sub_4_form.target='excel_iframe';sub_4_form.submit();" /></div>
															<div><input type="hidden" name="upimage_str" value="<?= $row['img'] ?>" /></div>
														</div>
														<div class="sub_4_3_t2_right_1_3">
															<a href="javascript:void(0)" onclick="lms_save(sub_4_form,'<?= $i ?>','modify','','<?= $row['idx'] ?>')"><img src="images/sub_button_711.jpg" /></a>
															<a href="javascript:void(0)" onclick="lms_del('<?= $row['idx'] ?>')"><img src="images/sub_button_713.jpg" /></a>

														</div>
													</div>
												<?
													$i++;
												}
												?>
												<p style="clear:both;"></p>
												<div>
													<?
													page_f($page, $page2, $intPageCount, "sub_4_form");
													?>
												</div>
											<?
											} else {
											?>
												<div style="text-align:center;">등록된 내용이 없습니다.</div>
											<?
											}
											?>
										</div>
										<p style="clear:both;"></p>
									</div>
								<?
									break;
								case 3:
									$sql_serch = " mem_id ='{$_SESSION['one_member_id']}' and msg_type='C' ";
									if ($_REQUEST['lms_text']) {
										if ($_REQUEST['lms_select'])
											$sql_serch .= " and {$_REQUEST['lms_select']} like '{$_REQUEST['lms_text']}%' ";
										else
											$sql_serch .= " and (message like '{$_REQUEST['lms_text']}%'  or title like '{$_REQUEST['lms_text']}%') ";
									}
									$sql = "select count(idx) as cnt from Gn_MMS_Message where $sql_serch ";
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
									$sql = "select * from Gn_MMS_Message where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
									$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
								?>
									<div class="sub_4_3_t2">
										<div class="sub_4_4_t3">
											<select name="lms_select">
												<option value="">전체</option>
												<?
												$select_lms_arr = array("title" => "제목", "message" => "내용");
												foreach ($select_lms_arr as $key => $v) {
													$selected = $_REQUEST['lms_select'] == $key ? "selected" : "";
												?>
													<option value="<?= $key ?>" <?= $selected ?>><?= $v ?></option>
												<?
												}
												?>
											</select>
											<input type="text" name="lms_text" value="<?= $_REQUEST['lms_text'] ?>" />
											<input type="image" src="images/sub_button_703.jpg" />
										</div>
										<div>
											<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td style="width:7%;"><label><input type="checkbox" onclick="check_all(this,'one_idx');" />선택</label></td>
													<td style="width:7%;">번호</td>
													<td style="width:26%;">제목</td>
													<td style="width:50%;">내용</td>
													<td style="width:10%;">시간</td>
												</tr>
												<?
												if ($intRowCount) {
													$c = 0;
													while ($row = mysqli_fetch_array($result)) {
												?>
														<tr>
															<td><input type="checkbox" value="<?= $row['idx'] ?>" name="one_idx" /></td>
															<td><?= $sort_no ?></td>
															<td><a href="javascript:void(0)" onclick="show_recv('show_title','<?= $c ?>','원북저장-제목')"><?= $row['title'] ?></a><input type="hidden" name="show_title" value="<?= $row['title'] ?>" /></td>
															<td style="text-align:left;"><a href="javascript:void(0)" onclick="show_recv('show_message','<?= $c ?>','원북저장-내용')"><?= str_substr($row['message'], 0, 50, "utf-8") ?></a><input type="hidden" name="show_message" value="<?= $row['message'] ?>" /></td>
															<td><?= substr($row['reg_date'], 0, 16) ?></td>
														</tr>
													<?
													}
													?>
													<tr>
														<td colspan="5">
															<? page_f($page, $page2, $intPageCount, "sub_4_form"); ?>
														</td>
													</tr>
												<?
													$c++;
													$sort_no--;
												} else {
												?>
													<tr>
														<td colspan="5" style="text-align:center;padding:10px;">
															검색된 내용이 없습니다.
														</td>
													</tr>
												<?
												}
												?>
											</table>
											<div style="text-align:right;margin-top:10px;"><a href="javascript:void(0)" onclick="one_del()"><img src="images/sub_button_109.jpg" /></a></div>
										</div>
									</div>
							<?
									break;
							}
							?>
						</div>
					<?
						break;
					case 4:
						$sql_serch = " mem_id ='{$_SESSION['one_member_id']}' ";
						if ($_REQUEST['status2'] == 1) { //예약내역
							$sql_serch .= " and title = 'app_check_process'";
							$sql_table = " Gn_MMS ";
						} else if ($_REQUEST['status2'] == 2) { //예약내역
							$sql_serch .= " and title != 'app_check_process' and title != '폰문자인증'";
							$sql_table = " Gn_MMS ";
						} else if ($_REQUEST['status2'] == 10) {
							$sql_serch .= " and type = 10";
							$sql_table = " Gn_MMS ";
						} else {
							$sql_table = " Gn_MMS ";
						}

						if ($_REQUEST['serch_fs_select'] && $_REQUEST['serch_fs_text'])
							$sql_serch .= " and {$_REQUEST['serch_fs_select']} like '{$_REQUEST['serch_fs_text']}%' ";
						// 상태 검색 추가
						if ($_REQUEST['result'] == 1) {
							$sql_serch .= " and result = 0 and up_date is not null ";
						} elseif ($_REQUEST['result'] == 2) {
							$sql_serch .= " and result = 1 and up_date is null ";
						} elseif ($_REQUEST['result'] == 3) {
							$sql_serch .= " and result = 3";
						} else {
							$sql_serch .= " and result >= 0";
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
							$order_name = "reg_date";
						$intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);



						$sql = "select * from $sql_table where $sql_serch order by $order_name $order_status limit $int,$intPageSize";

						$excel_sql = "select * from $sql_table where $sql_serch order by $order_name $order_status";
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
									<a href="sub_4.php?status=4" style="color:<?= $_REQUEST['status2'] == "" ? "#000" : "" ?>">전체내역</a> &nbsp;|&nbsp;
									<a href="sub_4.php?status=4&status2=1" style="color:<?= $_REQUEST['status2'] == 1 ? "#000" : "" ?>">앱체크내역</a> &nbsp;|&nbsp;
									<a href="sub_4_return_.php" style="color:<?= $_REQUEST['status2'] == 2 ? "#000" : "" ?>">발신/회신문자</a>&nbsp;|&nbsp;
									<a href="sub_4_return_.php?chanel=2" style="color:<?= $_REQUEST['chanel'] == 2 ? "#000" : "" ?>">스텝문자</a>&nbsp;|&nbsp;
									<a href="sub_4_return_.php?chanel=4" style="color:<?= $_REQUEST['chanel'] == 4 ? "#000" : "" ?>">데일리문자</a>&nbsp;|&nbsp;
									<a href="sub_4_return_.php?chanel=9" style="color:<?= $_REQUEST['chanel'] == 9 ? "#000" : "" ?>">콜백문자</a>&nbsp;|&nbsp;
									<a href="sub_4.php?status=4&status2=10" style="color:<?= $_REQUEST['status2'] == 10 ? "#000" : "" ?>">폰문자인증내역</a>
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
												<td style="width:7%;"><label><input type="checkbox" onclick="check_all(this,'fs_idx');" />번호</label></td>
												<td style="width:7%;">소유자명</td>
												<td style="width:10%;">발신번호</td>
												<td style="width:15%;">수신번호<?= $_REQUEST['status2'] == '' ? "<br />(수신예약시간)" : "" ?></td>
												<td style="width:8%;">문자제목</td>
												<td style="width:12%;">문자내용</td>
												<? if ($_REQUEST['status2'] == '2') { ?>
													<td style="width:5%;">상태</td>
												<? } ?>
												<td style="width:5%;"><?= $_REQUEST['status2'] == '2' ? "예약일시" : "첨부파일" ?></td>
												<td style="width:8%;">PC전송시간</td>
												<td style="width:8%;">발송완료시간</td>
												<td style="width:8%;">성공/실패</td>
											</tr>
											<?
											if ($intRowCount) {
												$c = 0;
												while ($row = mysqli_fetch_array($result)) {
													$sql_s = "select * from Gn_MMS_status where idx='{$row['idx']}' ";
													$resul_s = mysqli_query($self_con, $sql_s);
													$row_s = mysqli_fetch_array($resul_s);
													mysqli_free_result($resul_s);

													$sql_n = "select memo from Gn_MMS_Number where sendnum='{$row['send_num']}' ";
													$resul_n = mysqli_query($self_con, $sql_n);
													$row_n = mysqli_fetch_array($resul_n);
													mysqli_free_result($resul_n);

													$recv_cnt = explode(",", $row['recv_num']);


													$sql_as = "select count(idx) as cnt from Gn_MMS_status where idx='{$row['idx']}' ";
													$resul_as = mysqli_query($self_con, $sql_as);
													$row_as = mysqli_fetch_array($resul_as);
													$status_total_cnt = $row_as[0];

													$sql_cs = "select count(idx) as cnt from Gn_MMS_status where idx='{$row['idx']}' and status='0'";
													$resul_cs = mysqli_query($self_con, $sql_cs);
													$row_cs = mysqli_fetch_array($resul_cs);
													$success_cnt = $row_cs[0];

													$sql_sn = "select * from Gn_MMS where idx='{$row['idx']}' ";
													$resul_sn = mysqli_query($self_con, $sql_sn);
													$row_sn = mysqli_fetch_array($resul_sn);
													$recv_cnt = explode(",", $row_sn['recv_num']);

													$total_cnt = count($recv_cnt);

													if ($success_cnt > $total_cnt) $success_cnt = $total_cnt;
											?>

													<tr>
														<td><label><input type="checkbox" name="fs_idx" value="<?= $row['idx'] ?>" /><?= $sort_no ?></label></td>
														<td><?= $row_n['memo'] ?></td>
														<td><?= $row['send_num'] ?></td>
														<td style="font-size:12px;"><a href="javascript:void(0)" onclick="show_recv('show_recv_num','<?= $c ?>','수신번호')"><?= str_substr($row['recv_num'], 0, 14, 'utf-8') ?>
																<?= $row['reservation'] ? "<br>" . $row['reservation'] : "" ?></a> <span style="color:#F00;">(<?= count($recv_cnt) ?>)</span><input type="hidden" name="show_recv_num" value="<?= $row['recv_num'] ?>" /></td>
														<td><a href="javascript:void(0)" onclick="show_recv('show_title','<?= $c ?>','문자제목')"><?= str_substr($row['title'], 0, 14, 'utf-8') ?></a><input type="hidden" name="show_title" value="<?= $row['title'] ?>" /></td>
														<td style="font-size:12px;"><a href="javascript:void(0)" onclick="show_recv('show_content','<?= $c ?>','문자내용')"><?= str_substr($row['content'], 0, 30, 'utf-8') ?></a><input type="hidden" name="show_content" value="<?= $row['content'] ?>" /></td>
														<? if ($_REQUEST['status2'] == '2') { ?>
															<td style="width:5%;"><? if ($row['up_date'] != '' && $row['result'] == 0) { ?>완료<? } elseif ($row['up_date'] == '' && $row['result'] == 1) { ?>대기<? } elseif ($row['result'] == 3) { ?>실패<? } ?></td>
														<? } ?>
														<td>
															<? if ($_REQUEST['status2'] == 2) {
																echo substr($row['reservation'], 0, 16);
															} else { ?>
																<a href="javascript:void(0)" onclick="show_recv('show_jpg','<?= $c ?>','첨부파일')"><?= str_substr($row['jpg'], 0, 20, 'utf-8') ?></a><input type="hidden" name="show_jpg" value="<?= $row['jpg'] ?>" />
																<a href="javascript:void(0)" onclick="show_recv('show_jpg1','<?= $c ?>','첨부파일')"><?= str_substr($row['jpg1'], 0, 20, 'utf-8') ?></a><input type="hidden" name="show_jpg1" value="<?= $row['jpg1'] ?>" />
																<a href="javascript:void(0)" onclick="show_recv('show_jpg2','<?= $c ?>','첨부파일')"><?= str_substr($row['jpg2'], 0, 20, 'utf-8') ?></a><input type="hidden" name="show_jpg2" value="<?= $row['jpg2'] ?>" />


															<? } ?>

														</td>

														<td style="font-size:12px;"><?= substr($row['reg_date'], 0, 16) ?></td>
														<!--<td style="font-size:12px;"><?= substr($row['up_date'], 0, 16) ?></td>-->
														<td style="font-size:12px;">
															<? if ($row_s['status'] == "-1") { ?>
																기본앱아님
															<? } else { ?>
																<?= substr($row_s['regdate'], 0, 16) ?>
																<?php
																$reg_date = strtotime($row['reg_date']);
																$reg_date_1hour = strtotime("{$row['reg_date']} +1hours");
																if (time() > $reg_date_1hour && $row_s['regdate'] == "") {
																?>
																	<? if ($row['reservation']) { ?>
																		<a href="javascript:fs_del_num('<?= $row['idx'] ?>')">취소가능</a>
																	<? } else { ?>
																		<a href="javascript:fs_del_num('<?= $row['idx'] ?>')">미수신</a>
																	<? } ?>
																<?
																}
																?>

															<? } ?>

														</td>
														<td style="font-size:12px;">
															<? if ($success_cnt == 0) { ?>
																<? if (time() > $reg_date_1hour && $row['up_date'] == "") { ?>
																	<?php if ($row['reservation'] > date("Y-m-d H:i:s")) { ?>
																	<? } else { ?>
																		실패
																	<? } ?>
																<? } else { ?>
																	<? if (time() > $reg_date_1hour && $row_s['up_date'] == "") { ?>
																		발송실패
																	<? } else { ?>
																		<? if ($row['reservation'] < date("Y-m-d H:i:s")) { ?>
																			발송중
																		<? } else { ?>
																			예약
																		<? } ?>
																	<? } ?>
																<? } ?>
															<? } else { ?>
																<a href="sub_4_detail.php?idx=<?php echo $row['idx']; ?>"><?= $success_cnt ?>/<?php echo $total_cnt - $success_cnt; ?> <?php if ($row['reservation']) { ?>예약<?php } ?>
																<? } ?>
														</td>
													</tr>
												<?
													$c++;
													$sort_no--;
												}
												?>
												<tr>
													<td colspan="12">
														<?
														page_f($page, $page2, $intPageCount, "sub_4_form");
														?>
													</td>
												</tr>
											<?
											} else {
											?>
												<tr>
													<td colspan="12">
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
						<?
						break;
					case 5:
						$sql_serch = " 1 ";
						$sql_serch .= " and mem_id='{$_SESSION['one_member_id']}' ";
						if ($_REQUEST['status2'])
							$sql_serch .= " and msg_flag='{$_REQUEST['status2']}' ";
						if ($_REQUEST['serch_colum'] && $_REQUEST['serch_text']) {
							$sql_serch .= " and {$_REQUEST['serch_colum']} like '%{$_REQUEST['serch_text']}%' ";
						}
						$sql = "select count(seq) as cnt from sm_log where $sql_serch ";
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
							$order_name = "seq";
						$intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);
						$sql = "select * from sm_log where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
						$excel_sql = "select * from sm_log where $sql_serch order by $order_name $order_status";
						$excel_sql = str_replace("'", "`", $excel_sql);
						$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
						?>
							<div class="sub_4_4_t1">
								<div class="sub_4_4_t2">
									<div class="sub_4_1_t3">
										<a href="sub_4.php?status=5&status2=3" style="color:<?= $_REQUEST['status2'] == 3 ? "#000" : "" ?>">수신불가</a> &nbsp;|&nbsp;
										<a href="sub_4.php?status=5&status2=2" style="color:<?= $_REQUEST['status2'] == 2 ? "#000" : "" ?>">없는번호</a> &nbsp;|&nbsp;
										<a href="sub_4.php?status=5&status2=1" style="color:<?= $_REQUEST['status2'] == 1 ? "#000" : "" ?>">번호변경</a> &nbsp;|&nbsp;



									</div>
									<div class="sub_4_4_t3">
										<select name="serch_colum">
											<?
											$select_arr = array("ori_num" => "수신번호", "dest" => "발신번호", "msg_text" => "문자내용");
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
										<?
										if ($_REQUEST['status2'] == 1) {
										?>
											<!--<a href="javascript:void(0)" onclick="fugai_num('','cho')" class="a_btn_2">선택 덮기</a>-->
											<a href="javascript:void(0)" onclick="fugai_num('','all')" class="a_btn_2">일괄 덮기</a>
										<?
										}
										?>
										<?
										if ($_REQUEST['status2'] == 2) {
										?>
											<a href="javascript:void(0)" onclick="deleteAddress()" class="a_btn_2">주소록에서 삭제</a>
										<?
										}
										?>
									</div>
									<div class="sub_4_4_t4">
										<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td style="width:6%;"><label><input type="checkbox" onclick="check_all(this,'idx_box');" />선택</label></td>
												<td style="width:10%;">발신번호</td>
												<td style="width:8%;">소유자명</td>
												<td style="width:12%">수신일시</td>
												<td style="width:<?= $_REQUEST['status2'] == 1 ? "29%" : "46%" ?>;">문자내용</td>
												<td style="width:10%">수신번호</td>
												<? if ($_REQUEST['status2'] == 1) { ?><td style="width:10%">변경된번호</td><? } ?>
												<td style="width:7%;">그룹명</td>
												<? if ($_REQUEST['status2'] == 1) { ?><td style="width:8%"></td><? } ?>
											</tr>
											<?
											if ($intRowCount) {
												while ($row = mysqli_fetch_array($result)) {
													$sql_n = "select memo from Gn_MMS_Number where sendnum='{$row['dest']}' ";
													$resul_n = mysqli_query($self_con, $sql_n);
													$row_n = mysqli_fetch_array($resul_n);
											?>
													<tr>
														<td><label><input type="checkbox" name="idx_box" value="<?= $row['seq'] ?>" /><?= $sort_no ?></label></td>
														<td><?= $row['dest'] ?></td>
														<td><?= $row_n['memo'] ?></td>
														<td style="font-size:12px;"><?= substr($row['reservation_time'], 0, 16) ?></td>
														<td><?= $row['msg_text'] ?></td>
														<td><?= $row['ori_num'] ?></td>
														<? if ($_REQUEST['status2'] == 1) { ?><td><?= $row['chg_num'] ?></td><? } ?>
														<td><?= $row['grp_name'] ?></td>
														<? if ($_REQUEST['status2'] == 1) { ?>
															<td><? if ($row['chg_num']) { ?><a href="javascript:void(0)" onclick="fugai_num('<?= $row['seq'] ?>','cho')" class="a_btn_2">덮어쓰기</a><? } ?></td>
														<? } ?>
													</tr>
												<?
													$sort_no--;
												}
												?>
												<tr>
													<td colspan="9">
														<? page_f($page, $page2, $intPageCount, "sub_4_form"); ?>
													</td>
												</tr>
											<?
											} else {
											?>
												<tr>
													<td colspan="9">
														등록된 내용이 없습니다.
													</td>
												</tr>
											<?
											}
											?>
											<tr>
												<td colspan="9" style="text-align:left;">
													<div style="float:right">
														새로 추가:
														<input type="text" name="log_dest" style="width:auto" placeholder="보낸번호" itemname='보낸번호' required />
														<input type="text" name="log_ori" style="width:auto" placeholder="수신번호" itemname='수신번호' required />
														<input type="button" value="저장" onclick="log_add(sub_4_form,'0','<?= $_REQUEST['status2'] ?>')" />
													</div>
												</td>
											</tr>
										</table>
									</div>
									<div class="sub_4_4_t5">
										<div class="div_float_left">&nbsp;</div>
										<div class="div_float_right">
											<a href="javascript:void(0)" onclick="excel_down('excel_down/log_down.php', '', '', '1')"><img src="images/sub_button_107.jpg" /></a>
											<a href="javascript:void(0)" onclick="log_del()"><img src="images/sub_button_109.jpg" /></a>
										</div>
										<p style="clear:both;"></p>
									</div>
								</div>
							</div>
						<?
						break;
					case 6:
						$sql_serch = " mem_id ='{$_SESSION['one_member_id']}' ";
						if ($_REQUEST['serch_colum'] && $_REQUEST['serch_text']) {
							$sql_serch .= " and {$_REQUEST['serch_colum']} like '{$_REQUEST['serch_text']}%' ";
						}
						if ($_REQUEST['serch_chanel']) {
							$sql_serch .= " and chanel_type = {$_REQUEST['serch_chanel']} ";
						}
						$sql = "select count(idx) as cnt from Gn_MMS_Deny where $sql_serch ";
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
						$sql = "select * from Gn_MMS_Deny where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
						$excel_sql = "select * from Gn_MMS_Deny where $sql_serch order by $order_name $order_status";
						$excel_sql = str_replace("'", "`", $excel_sql);
						$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

						$today_date = date("Y-m-d");
						$sql_today = "select count(idx) as today_cnt from Gn_MMS_Deny where mem_id='{$_SESSION['one_member_id']}' and reg_date like '$today_date%' ";
						$resul_today = mysqli_query($self_con, $sql_today);
						$row_today = mysqli_fetch_array($resul_today);
						?>
							<div class="sub_4_4_t1">
								<div class="sub_4_4_t2">
									<div class="sub_4_1_t3">
										<a href="sub_4.php?status=<?= $_GET['status']; ?>&status1=1" style="color:#000" >수신거부</a> &nbsp; | &nbsp;
										<a href="sub_4_agree.php?status=<?= $_GET['status']; ?>&status1=2" style="color:#000">수신동의</a></span>
									</div>
									<div class="sub_4_4_t3">
										<p style="clear:both"></p>
									</div>

									<div class="sub_4_1_t2">
										<div style="float:left;"><img src="images/sub_04_06_03.jpg" /></div>
										<div style="float:left;width:780px;text-align:center">
											전체<span class="sub_4_1_t2_2">(<?= $intRowCount ?>)</span> &nbsp; | &nbsp;
											오늘등록<span class="sub_4_1_t2_2">(<?= $row_today['today_cnt'] ?>)</span>
										</div>
										<p style="clear:both"></p>
									</div>
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
										<select name="serch_chanel">
											<option value="">채널</option>
											<?
											$select_arr = array("1" => "폰문자", "2" => "스텝문자", "9" => "콜백문자", "4" => "데일리문자");
											foreach ($select_arr as $key => $v) {
												$selected = $_REQUEST['serch_chanel'] == $key ? "selected" : "";
											?>
												<option value="<?= $key ?>" <?= $selected ?>><?= $v ?></option>
											<?
											}
											?>
										</select>
										<input type="text" name="serch_text" value="<?= $_REQUEST['serch_text'] ?>" />
										<a href="javascript:void(0)" onclick="sub_4_form.submit();"><img src="images/sub_button_103.jpg" /></a>
										<a href="javascript:void(0)" onclick="deny_add_multi()" style="color:white;background-color:rgb(130,199,54);padding:8px;float:right;">메시지 발송 제외번호등록</a>
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
												<td style="width:5%;">채널</td>
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
														<td class="g_dt_name_<?= $i ?>" style="display:none;"><input type="text" value="<?= $row['send_num'] ?>" name="deny_send" /> </td>
														<td class="g_dt_num_<?= $i ?>"><?= $row['recv_num'] ?></td>
														<td class="g_dt_num_<?= $i ?>" style="display:none"><input type="text" value="<?= $row['recv_num'] ?>" name="deny_recv" /></td>
														<td><a href="javascript:void(0)" onclick="show_recv('show_title','<?= $i ?>','문자제목')"><?= str_substr($row['title'], 0, 20, 'utf-8') ?></a><input type="hidden" name="show_title" value="<?= $row['title'] ?>" /></td>
														<td><a href="javascript:void(0)" onclick="show_recv('show_content','<?= $i ?>','문자내용')"><?= str_substr($row['content'], 0, 30, 'utf-8') ?></a><input type="hidden" name="show_content" value="<?= $row['content'] ?>" /></td>
														<td><a href="javascript:void(0)" onclick="show_recv('show_jpg','<?= $c ?>','첨부파일')"><?= str_substr($row['jpg'], 0, 20, 'utf-8') ?></a><input type="hidden" name="show_jpg" value="<?= $row['jpg'] ?>" /></td>

														<td>
															<?php
															switch ($row['chanel_type']) {
																case 1:
																	echo "폰문자";
																	break;
																case 2:
																	echo "스텝문자";
																	break;
																case 9:
																	echo "콜백문자";
																	break;
																case 4:
																	echo "데일리문자";
																	break;
															}
															?>
														</td>
														<td><?= $deny_type_arr[$row['status']] ?></td>
														<td><?= substr($row['reg_date'], 0, 16) ?></td>
														<td>
															<a href="javascript:void(0)" class="modify_btn_<?= $i ?> a_btn_2" onclick="g_dt_show_cencle('g_dt_name_','g_dt_num_','modify_btn_','<?= $i ?>')">수정</a>
															<a href="javascript:void(0)" class="modify_btn_<?= $i ?> a_btn_2" style="display:none;" onclick="deny_add(sub_4_form,'<?= $i ?>','<?= $row['idx'] ?>', <?= $row['chanel_type'] ?>)">수정</a>
															<a href="javascript:void(0)" onclick="deny_del('<?= $row['idx'] ?>')" class="a_btn_2">삭제</a>
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
													<input type="file" name="excel_file" /> <input type="button" value="엑셀업로드" onclick="deny_excel_insert(sub_4_form,'deny')" />
													<br />
													<a href="javascript:void(0)" onclick="excel_down('excel_down/deny_down.php?down_type=2')" class="a_btn_2">수신거부샘플.xls</a> (97~2003통합문서)

												</td>
												<td colspan="6" style="text-align:right;">
													<select name="reg_chanel" id="reg_chanel">
														<?
														$select_arr = array("1" => "폰문자", "2" => "스텝문자", "9" => "콜백문자", "4" => "데일리문자");
														foreach ($select_arr as $key => $v) {
															$selected = $_REQUEST['serch_colum'] == $key ? "selected" : "";
														?>
															<option value="<?= $key ?>" <?= $selected ?>><?= $v ?></option>
														<?
														}
														?>
													</select>
													새로 추가:
													<input type="text" name="deny_send" style="width:auto" placeholder="발신번호" itemname='발송번호' required />
													<input type="text" name="deny_recv" style="width:auto" placeholder="수신번호" itemname='수신번호' required />
													<input type="button" value="저장" onclick="deny_add(sub_4_form,'<?= $i ?>', 0)" />
												</td>
											</tr>
										</table>
									</div>
									<div class="sub_4_4_t5">
										<div class="div_float_left">&nbsp;</div>
										<div class="div_float_right">
											<a href="javascript:void(0)" onclick="excel_down('excel_down/deny_down.php?down_type=1')"><img src="images/sub_button_107.jpg" /></a>
											<a href="javascript:void(0)" onclick="deny_del()"><img src="images/sub_button_109.jpg" /></a>
										</div>
										<p style="clear:both;"></p>
									</div>
								</div>
							</div>
						</div>
				<?
						break;
				}
				?>
				<input type="hidden" name="order_name" value="<?= $order_name ?>" />
				<input type="hidden" name="order_status" value="<?= $order_status ?>" />
				<input type="hidden" name="page" value="<?= $page ?>" />
				<input type="hidden" name="page2" value="<?= $page2 ?>" />
			</form>
			<form name="excel_down_form" action="" target="excel_iframe" method="post">
				<input type="hidden" name="grp_id" value="" />
				<input type="hidden" name="box_text" value="" />
				<input type="hidden" name="ids" value="" />
				<input type="hidden" name="excel_sql" value="<?= $excel_sql ?>" />
			</form>
			<iframe name="excel_iframe" style="display:none;"></iframe>
		</div>
	</div>
</div>
<span class="tooltiptext-bottom" id="tooltiptext_add_deny_multi" style="display:none;">
	<p class="title_app">콜백 제외 대상 추가<span onclick="cancel_set()" style="float:right;cursor:pointer;">X</span></p>
	<table class="table table-bordered" style="width: 97%;">
		<tbody>
			<input type="hidden" name="btn_type" id="btn_type" value="add_deny">
			<div style="text-align:center;">
				<button class="people_iam_show" style="font-size: 15px;margin: 0px;margin-bottom: 15px;background-color: #4ab4ff;border-radius:0px;width: 150px;margin-top: 15px;color: white;border: none;padding: 5px;" onclick="get_addr_list('undeny')">제외리스트<br>보기/해제</button>
				<button class="people_iam_show" style="font-size: 15px;margin: 0px;margin-bottom: 15px;background-color: rgb(9 117 193);border-radius:0px;width: 150px;margin-top: 15px;color: white;border: none;padding: 5px;" onclick="get_addr_list('deny')">내 주소록에서<br>제외대상 추가</button>
			</div>
			<h3 style="margin-left:20px;">
				콜백제외번호 입력
			</h3>
			<tr class="hide_spec">
				<td colspan="2" style="padding:0 20px;">
					<div>
						<textarea name="deny_num_multi" id="deny_num_multi" style="border: solid 1px #b5b5b5;width:100%; height:150px;" data-num="0" placeholder="전화번호(쉼표, 엔터키로 구분)"></textarea>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<div style="padding:10px;">
		<p style="font-size:16px;color:#6e6c6c;border:1px solid;padding:10px;">
			1. 콜백제외 대상등록 : 주소록가져오기 또는 수동입력으로 제외대상을 등록하세요.<br><br>
			2. 콜백제외 해제설정 : 제외리스트/해제 클릭 후 번호를 선택해서 해제하세요.
		</p>
	</div>
	<div class="button_app">
		<a href="javascript:clear_nums()" class="btn login_signup" style="width: 40%;background-color: #82c736;border-radius: 3px;padding: 5px;color: white;">취소</a>
		<a href="javascript:add_deny_multi()" class="btn login_signup" style="width: 40%;background-color: #82c736;border-radius: 3px;color: white;padding: 5px;">등록</a>
	</div>
</span>
<div id="tutorial-loading"></div>
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

	function deny_add_multi() {
		$("#tooltiptext_add_deny_multi").show();
		$("#tutorial-loading").show();
	}

	function cancel_set() {
		$("#tooltiptext_add_deny_multi").hide();
		$("#tutorial-loading").hide();
	}

	function add_deny_multi() {
		var recv_nums = $("#deny_num_multi").val();
		var send_num = '<?= $mem_phone ?>';
		var mem_id = '<?= $_SESSION['one_member_id'] ?>';

		var type = $("#btn_type").val();
		$($(".loading_div")[0]).show();
		$.ajax({
			type: "POST",
			url: "/ajax/add_deny_multi.php",
			data: {
				deny_add_send: send_num,
				deny_add_recv: recv_nums,
				mem_id: mem_id,
				reg_chanel: 9,
				type: type
			},
			success: function(data) {
				$($(".loading_div")[0]).hide();
				$("#ajax_div").html(data);
			}
		});
	}

	function clear_nums() {
		$("#deny_num_multi").val('');
	}

	function get_addr_list(val) {
		if (val == "deny") {
			$("#btn_type").val("add_deny");
		} else {
			$("#btn_type").val("unadd_deny");
		}

		window.open('/group_detail_for_adddeny.php?phone=<?= $phone ?>&mem_id=<?= $_SESSION['one_member_id'] ?>&type=' + val, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=500,height=600");
	}
</script>
<?
include_once "_foot.php";
?>