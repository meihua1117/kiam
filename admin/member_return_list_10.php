<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today = date("Y-m-d");
function get_style($case, $active_case)
{
	if ($case == $active_case) {
		return "border: 1px solid #337ab7;background:#337ab7;color:white;padding:5px;width:100px;";
	} else {
		return "border: 1px solid #337ab7;color:#337ab7;padding:5px;width:100px;";
	}
}
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
	function goPage(pgNum) {
		location.href = '?nowPage=' + pgNum + "&search_id=<?= $_GET['search_id']; ?>&search_name=<?= $_GET['search_name']; ?>&search_phone=<?= $_GET['search_phone']; ?>&search_site=<?= $_GET['search_site']; ?>&search_site_iam=<?= $_GET['search_site_iam']; ?>&search_content=<?= $_GET['search_content']; ?>&case=<?= $_GET['case']; ?>";
	}
	$(function() {
		var contHeaderH = $(".main-header").height();
		var navH = $(".navbar").height();
		if (navH != contHeaderH)
			contHeaderH += navH - 50;
		$(".content-wrapper").css("margin-top", contHeaderH);
		var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - $("#list_paginate").height() - 196;
		if (height < 375)
			height = 375;
		$(".box-body").css("height", height);
	});
</script>
<style>
	.loading_div {
		display: none;
		position: fixed;
		left: 50%;
		top: 50%;
		display: none;
		z-index: 1000;
	}

	#open_recv_div li {
		list-style: none;
	}

	thead tr th {
		position: sticky;
		top: 0;
		background: #ebeaea;
		z-index: 10;
	}

	.wrapper {
		height: 100%;
		overflow: auto;
	}

	.content-wrapper {
		min-height: 80% !important;
	}

	.box-body {
		overflow: auto;
		padding: 0px !important
	}
</style>
<div class="loading_div"><img src="/images/ajax-loader.gif"></div>
<div class="wrapper">
	<!-- Top 메뉴 -->
	<? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_header_menu.inc.php"; ?>
	<!-- Left 메뉴 -->
	<? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_left_menu.inc.php"; ?>
	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>수발신관리<small>수발신관리를 관리합니다.</small></h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				<li class="active">회원관리</li>
			</ol>
		</section>
		<!-- Main content -->
		<section class="content">
			<div class="row" id="toolbox">
				<div class="col-xs-12" style="padding-bottom:20px">
					<div class="box-tools" style="padding:10px">
						<div class="input-group" style="display:inline-flex;width: 100%">
							<div class="form-group">
								<a href="?upd=&case=3" style="<?= get_style($case, 3) ?>">10개이상건수</a>
							</div>
							<div class="form-group">
								<a href="member_return_list.php?case=&search_id=<?= $_GET['search_id']; ?>&search_name=<?= $_GET['search_name']; ?>&search_phone=<?= $_GET['search_phone']; ?>&search_site=<?= $_GET['search_site']; ?>&search_site_iam=<?= $_GET['search_site_iam']; ?>" style="<?= get_style($case, 0) ?>">전체 내역</a>
							</div>
							<div class="form-group">
								<a href="member_return_list.php?case=1&search_id=<?= $_GET['search_id']; ?>&search_name=<?= $_GET['search_name']; ?>&search_phone=<?= $_GET['search_phone']; ?>&search_site=<?= $_GET['search_site']; ?>&search_site_iam=<?= $_GET['search_site_iam']; ?>" style="<?= get_style($case, 1) ?>">앱체크 내역</a>
							</div>
							<div class="form-group">
								<a href="member_return_list.php?case=2&search_id=<?= $_GET['search_id']; ?>&search_name=<?= $_GET['search_name']; ?>&search_phone=<?= $_GET['search_phone']; ?>&search_site=<?= $_GET['search_site']; ?>&search_site_iam=<?= $_GET['search_site_iam']; ?>" style="<?= get_style($case, 2) ?>">발신문자</a>
							</div>
							<div class="form-group">
								<a href="member_return_list.php?case=4&search_id=<?= $_GET['search_id']; ?>&search_name=<?= $_GET['search_name']; ?>&search_phone=<?= $_GET['search_phone']; ?>&search_site=<?= $_GET['search_site']; ?>&search_site_iam=<?= $_GET['search_site_iam']; ?>" style="<?= get_style($case, 4) ?>">스텝문자</a>
							</div>
							<div class="form-group">
								<a href="member_return_list.php?case=5&search_id=<?= $_GET['search_id']; ?>&search_name=<?= $_GET['search_name']; ?>&search_phone=<?= $_GET['search_phone']; ?>&search_site=<?= $_GET['search_site']; ?>&search_site_iam=<?= $_GET['search_site_iam']; ?>" style="<?= get_style($case, 5) ?>">데일리문자</a>
							</div>
							<div class="form-group">
								<a href="member_return_list.php?case=6&search_id=<?= $_GET['search_id']; ?>&search_name=<?= $_GET['search_name']; ?>&search_phone=<?= $_GET['search_phone']; ?>&search_site=<?= $_GET['search_site']; ?>&search_site_iam=<?= $_GET['search_site_iam']; ?>" style="<?= get_style($case, 6) ?>">콜백문자</a>
							</div>
						</div>
					</div>
					<? if ($_SESSION['one_member_admin_id'] != "onlyonemaket") { ?>
						<button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_member_return.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>
					<? } ?>
					<form method="get" name="search_form" id="search_form">
						<div class="box-tools">
							<div class="input-group" style="display:inline-flex;width: 100%">
								<div class="form-group">
									<input type="text" name="search_id" id="search_id" class="form-control input-sm pull-right" style="width:100px" placeholder="아이디" value="<?= $search_id ?>">
								</div>
								<div class="form-group">
									<input type="text" name="search_name" id="search_name" class="form-control input-sm pull-right" style="width:100px" placeholder="이름" value="<?= $search_name ?>">
								</div>
								<div class="form-group">
									<input type="text" name="search_phone" id="search_phone" class="form-control input-sm pull-right" style="width:100px" placeholder="휴대폰" value="<?= $search_phone ?>">
								</div>
								<div class="form-group">
									<input type="text" name="search_site" id="search_site" class="form-control input-sm pull-right" style="width:100px" placeholder="셀링소속" value="<?= $search_site ?>">
								</div>
								<div class="form-group">
									<input type="text" name="search_site_iam" id="search_site_iam" class="form-control input-sm pull-right" style="width:100px" placeholder="아이엠소속" value="<?= $search_site_iam ?>">
								</div>
								<div class="form-group">
									<input type="text" name="search_content" id="search_content" class="form-control input-sm pull-right" style="width:100px" placeholder="발신내용" value="<?= $search_content ?>">
								</div>
								<div class="input-group-btn">
									<button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
								</div>
								<input type="hidden" name="upd" value="<?= $upd ?>">
								<input type="hidden" name="case" value="<?= $case ?>">
							</div>
						</div>
					</form>
				</div>
			</div>
			<div class="row">
				<div class="box">
					<div class="box-body">
						<table id="example1" class="table table-bordered table-striped">
							<colgroup>
								<col width="60px">
								<col width="100px">
								<col width="100px">
								<col width="100px">
								<col width="100px">
								<col width="100px">
								<col width="100px">
								<col width="100px">
								<col width="220px">
								<col width="120px">
								<col width="120px">
								<col width="120px">
							</colgroup>
							<thead>
								<tr>
									<th>번호</th>
									<th>아이디</th>
									<th>이름</th>
									<th>소속</th>
									<th>IAM소속</th>
									<th>발신번호</th>
									<th>소유자명</th>
									<th>발신내용</th>
									<th>발신시간</th>
									<th>수신번호</th>
									<th>발송건수</th>
									<th>회신수</th>
								</tr>
							</thead>
							<tbody>
								<?
								$nowPage = $_REQUEST['nowPage'] ? $_REQUEST['nowPage'] : 1;
								$startPage = $nowPage ? $nowPage : 1;
								$pageCnt = 20;
								// 검색 조건을 적용한다.
								$mem_sql = "SELECT GROUP_CONCAT(mem_id) AS mem_ids FROM Gn_Member WHERE 1 = 1 ";
								$mem_sort = false;
								if ($search_name) {
									$mem_sql .= " AND mem_name LIKE '%{$search_name}%'";
									$mem_sort = true;
								}
								if ($search_site) {
									$mem_sql .= " AND site = '{$search_site}'";
									$mem_sort = true;
								}
								if ($search_site_iam) {
									$mem_sql .= " AND site_iam = '{$search_site_iam}'";
									$mem_sort = true;
								}
								if ($mem_sort) {
									echo 1;
									$mem_res = mysqli_query($self_con, $mem_sql);
									$mem_row = mysqli_fetch_assoc($mem_res);
									$mem_ids = explode(",", $mem_row["mem_ids"]);
									echo 2;
									if (empty($mem_ids))
										$searchStr = "1 <> 1 ";
									else {
										echo 3;
										$ids_array = array();
										if ($search_id) {
											foreach ($mem_ids as $mem_id) {
												if (strpos($mem_id, $search_id) !== false)
													array_push($ids_array, $mem_id);
											}
										}
										echo 4;
										if ($search_id && empty($ids_array))
											$searchStr = "1 <> 1 ";
										else {
											$id_str = implode("','", $id_array);
											$searchStr .= " AND mem_id in ('{$id_str}')";
											$searchStr .= $search_phone ? " AND a.send_num like '" . $search_phone . "%' " : null;
											$searchStr .= $search_content ? " AND a.content like '%" . $search_content . "%' " : null;
										}
									}
									echo 5;
								} else {
									$searchStr = "1 = 1";
									$searchStr .= $search_id ? " AND a.mem_id LIKE '%" . $search_id . "%' " : null;
									$searchStr .= $search_phone ? " AND a.send_num like '" . $search_phone . "%' " : null;
									$searchStr .= $search_content ? " AND a.content like '%" . $search_content . "%' " : null;
								}
								if ($_GET['upd'] == 'yes') {
									$searchStr .= " and recv_num_cnt is NULL ";
								}
								if ($case == 3) {
									$searchStr .= " and recv_num_cnt > 10 ";
								}
								$order = $order ? $order : "desc";
								$query = "SELECT a.idx FROM Gn_MMS a WHERE $searchStr";
								$res   = mysqli_query($self_con, $query);
								$totalCnt = mysqli_num_rows($res);
								if ($_GET['upd'] == 'yes') {
									while ($row_idx = mysqli_fetch_array($res)) {
										$index_no = $row_idx["idx"];
										$q = "select round(( length(recv_num) - length(replace(recv_num,',',''))) / length(',')) as cnt_rest
											from ( select recv_num from Gn_MMS where idx = '$index_no' ) A";
										$r = mysqli_query($self_con, $q);
										$row = mysqli_fetch_row($r);
										$recv_num_cnt = $row[0] + 1;
										// SET 절을 통해 전달한 데이터로 Gn_MMS_more10 테이블의 레코드를 수정하는 SQL 구문
										$sql = "UPDATE Gn_MMS SET recv_num_cnt = '{$recv_num_cnt}' WHERE (idx = '{$index_no}') and (recv_num_cnt is NULL)";
										mysqli_query($self_con, $sql);
										//mysqli_affected_rows();
										//mysqli_query($self_con,"COMMIT");
										//mysqli_free_result($r);
									}
									echo "<meta http-equiv='refresh' content='1; url=/admin/member_return_list_10.php?upd=&case=3'></meta>";
									exit();
								}
								$query = "SELECT a.idx, a.send_num, a.recv_num, a.up_date, a.mem_id, a.reservation, a.reg_date, a.content 
											FROM Gn_MMS a WHERE $searchStr";
								$limitStr = " LIMIT " . (($startPage - 1) * $pageCnt) . ", " . $pageCnt;
								$number	= $totalCnt - ($nowPage - 1) * $pageCnt;
								$orderQuery .= " ORDER BY a.reg_date DESC $limitStr";
								$i = 1;
								$c = 0;
								$excel_sql = $query;
								$query .= $orderQuery;
								echo $query;
								$res = mysqli_query($self_con, $query);
								while ($row = mysqli_fetch_array($res)) {
									$mem_sql = "SELECT mem_name,site,site_iam FROM Gn_Member WHERE mem_id = '{$row['mem_id']}'";
									$mem_res = mysqli_query($self_con, $mem_sql);
									$mem_row = mysqli_fetch_assoc($mem_res);

									$row['mem_name'] = $mem_row['mem_name'];
									$row['site'] = $mem_row['site'];
									$row['site_iam'] = $mem_row['site_iam'];
									$sql_s = "select regdate from Gn_MMS_status where idx='{$row['idx']}' ";
									$resul_s = mysqli_query($self_con, $sql_s);
									$row_s = mysqli_fetch_array($resul_s);
									mysqli_free_result($resul_s);

									$sql_n = "select memo from Gn_MMS_Number where sendnum='{$row['send_num']}' ";
									$resul_n = mysqli_query($self_con, $sql_n);
									$row_n = mysqli_fetch_array($resul_n);
									mysqli_free_result($resul_n);

									$recv_num = $recv_cnt = explode(",", $row['recv_num']);
									$recv_num_in = "'" . implode("','", $recv_num) . "'";
									$date = $row['up_date'];

									$sql = "select count(seq) as cnt from call_app_log where api_name='receive_sms' and LENGTH(recv_num) >= 10 and  send_num='{$row['send_num']}' and recv_num in ($recv_num_in) and recv_num like '01%'  and regdate >= '$date' and sms not like '[%'";
									$kresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
									$krow = mysqli_fetch_array($kresult);
									$intRowCount = $krow['cnt'];

									$sql_as = "select count(idx) as cnt from Gn_MMS_status where idx='{$row['idx']}' ";
									$resul_as = mysqli_query($self_con, $sql_as);
									$row_as = mysqli_fetch_array($resul_as);
									$status_total_cnt = $row_as[0];

									$sql_cs = "select count(idx) as cnt from Gn_MMS_status where idx='{$row['idx']}' and status='0'";
									$resul_cs = mysqli_query($self_con, $sql_cs);
									$row_cs = mysqli_fetch_array($resul_cs);
									$success_cnt = $row_cs[0];

									// $sql_sn="select * from Gn_MMS where idx='{$row['idx']}' ";
									// $resul_sn=mysqli_query($self_con,$sql_sn);
									// $row_sn=mysqli_fetch_array($resul_sn);											
									// $recv_cnt=explode(",",$row_sn['recv_num']);										
									$recv_cnt = explode(",", $row['recv_num']);
									$total_cnt = count($recv_cnt);
									$reg_date_1hour = strtotime("{$row['reg_date']} +1hours");
								?>
									<tr>
										<td><?= $number-- ?></td>
										<td><?= $row['mem_id'] ?></td>
										<td><?= $row['mem_name'] ?></td>
										<td><?= $row['site'] ?></td>
										<td><?= $row['site_iam'] ?></td>
										<td><?= $row['send_num'] ?></td>
										<td><?= $row_n['memo'] ?></td>
										<td style="font-size:12px;"><a onclick="show_recv('show_content','<?= $c ?>','문자내용')"><?= str_substr($row['content'], 0, 30, 'utf-8') ?></a><input type="hidden" name="show_content" value="<?= $row['content'] ?>" /></td>
										<td style="font-size:12px;"><?= substr($row['reg_date'], 0, 16) ?></td>
										<td style="font-size:12px;">
											<a onclick="show_recv('show_recv_num','<?= $c ?>','수신번호')"><?= str_substr($row['recv_num'], 0, 14, 'utf-8') ?>
												<?= $row['reservation'] ? "<br>" . $row['reservation'] : "" ?>
											</a>
											<span style="color:#F00;">(<?= $total_cnt ?>)</span>
											<input type="hidden" name="show_recv_num" value="<?= $row['recv_num'] ?>" />
										</td>
										<td>
											<? if ($row['reservation']) { ?>
												예약
											<? } ?>
											<? if ($success_cnt == 0) {
												if (time() > $reg_date_1hour && $row['up_date'] == "") {
													if ($row['reservation'] > date("Y-m-d H:i:s")) {
													} else { ?>
														실패
													<? }
												} else {
													if (time() > $reg_date_1hour && $row_s['up_date'] == "") { ?>
														발송실패
													<? } else { ?>
														발송중
												<? }
												}
											} else { ?>
												<?= $success_cnt ?>/<?= $total_cnt - $success_cnt; ?>
											<? } ?>
										</td>
										<td style="font-size:12px;">
											<a href="member_return_detail.php?idx=<?php echo $row['idx'] ?>&send_num=<?= $row['send_num'] ?>"><?= $intRowCount; ?></a>
										</td>
									</tr>
								<?
									$c++;
									$i++;
								} //end while
								if ($i == 1) { ?>
									<tr>
										<td colspan="12" style="text-align:center;background:#fff">
											등록된 내용이 없습니다.
										</td>
									</tr>
								<? } ?>
							</tbody>
						</table>
					</div><!-- /.box-body -->
				</div><!-- /.box -->
			</div><!-- /.row -->
			<div class="row">
				<div class="col-sm-5">
					<div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?= $totalCnt ?> 건</div>
				</div>
				<div class="col-sm-7">
					<?
					echo drawPagingAdminNavi($totalCnt, $nowPage);
					?>
				</div>
			</div>
		</section><!-- /.content -->
	</div><!-- /.col -->
	<!-- Footer -->
	<? include_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/admin_footer.inc.php"; ?>
</div><!-- /content-wrapper -->

<form id="excel_down_form" name="excel_down_form" target="excel_iframe" method="post">
	<input type="hidden" name="grp_id" value="" />
	<input type="hidden" name="box_text" value="" />
	<input type="hidden" name="one_member_id" id="one_member_id" value="" />
	<input type="hidden" name="excel_sql" value="<?= $excel_sql ?>" />
</form>
<iframe name="excel_iframe" style="display:none;"></iframe>
<script language="javascript">
	function changeLevel(mem_code) {
		var mem_leb = $('#mem_leb' + mem_code + " option:selected").val();
		var data = {
			"mode": "change",
			"mem_code": "'+mem_code+'",
			"mem_leb": "'+mem_leb+'"
		};
		$.ajax({
			type: "POST",
			url: "/admin/ajax/user_level_change.php",
			dataType: "json",
			data: {
				mode: "change",
				mem_code: mem_code,
				mem_leb: mem_leb
			},
			success: function(data) {
				//console.log(data);
				//location = "/";
				//location.reload();
				alert('변경이 완료되었습니다.');
			},
			error: function() {
				alert('초기화 실패');
			}
		});
	}
</script>

<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
		<li class="open_recv_title open_2_1"></li>
		<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>
	</div>
	<div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">

	</div>
</div>
<script>
	function show_recv(name, c, t, status) {
		console.log(name);
		console.log(c);
		if (!document.getElementsByName(name)[c].value)
			return;
		open_div(open_recv_div, 100, 1, status);
		if (name == "show_jpg")
			$($(".open_recv")[0]).html("<img src='" + document.getElementsByName(name)[c].value + "' />");
		else if (name == "show_jpg1")
			$($(".open_recv")[0]).html("<img src='" + document.getElementsByName(name)[c].value + "' />");
		else if (name == "show_jpg2")
			$($(".open_recv")[0]).html("<img src='" + document.getElementsByName(name)[c].value + "' />");
		else
			$($(".open_recv")[0]).html(document.getElementsByName(name)[c].value.replace(/\n/g, "<br/>"));
		$($(".open_recv_title")[0]).html(t);
	}
</script>