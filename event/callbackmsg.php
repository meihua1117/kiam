<?
include_once "../lib/rlatjd_fun.php";
extract($_REQUEST);
$event_idx = $_GET['eventidx'];
$pcode = $_GET['pcode'];

$sql_recom = "select * from Gn_event where pcode='{$pcode}'";
$res = mysql_query($sql_recom);
$row = mysql_fetch_array($res);
$recom_id = $row['m_id'];
$iam_link = $row['event_type'];
$event_desc = $row['event_info'];
$img = $row['object'];
$event_sms = str_replace("\n", "<br>", $row['event_sms_desc']);
$callback_no = $row['callback_no'];

$sql_exp = "select service_type from Gn_Iam_Service where sub_domain = 'http://" . $HTTP_HOST . "'";
$res_exp = mysql_query($sql_exp);
$row_exp = mysql_fetch_array($res_exp);
$exp_mem = $row_exp['service_type'];
if ($exp_mem == 3) {
	$exp_mem = 'true';
} else {
	$exp_mem = 'false';
}

$sql = "update Gn_event set read_cnt = read_cnt+1 where pcode='$pcode'";
mysql_query($sql) or die(mysql_error());

$sql = "select * from Gn_event where pcode='$pcode'";
$result = mysql_query($sql) or die(mysql_error());
$event_data = $row = mysql_fetch_array($result);
$m_id = $row['m_id'];
$event_idx = $row['event_idx'];
$page_title = $row['event_title'];

$desc = str_replace("\n", "<br>", $event_data['event_desc']);

$cur_point = 0;
$sql_cur_point = "select mem_point from Gn_Member where mem_id='{$m_id}'";
$res_point = mysql_query($sql_cur_point);
$row_point = mysql_fetch_array($res_point);
$cur_point = $row_point['mem_point'];

if ($HTTP_HOST != "kiam.kr") //분양사사이트이면
	$query = "select mem_id,profile_idx from Gn_Iam_Service where sub_domain like 'http://" . $HTTP_HOST . "'";
else
	$query = "select mem_id,profile_idx from Gn_Iam_Service where sub_domain like 'http://www.kiam.kr'";
$res = mysql_query($query);
$domainData = mysql_fetch_assoc($res);
$manager = $domainData['mem_id'];
//분양사 콜백횟수 가져오기
$callback_sql = "select callback_times from Gn_Member where mem_id='{$manager}'";
$callback_res = mysql_query($callback_sql);
$callback_row = mysql_fetch_assoc($callback_res);
$callback_times = substr($callback_row['callback_times'],1);
//분양사 콜백횟수 가져오기 끝
if ($event_data['object'] != "") {
	$main_img1 = $event_data['object'];
}else{
	$first_card_idx = $domainData['profile_idx']; //분양사의 1번 카드아이디
	$sql = "select * from Gn_Iam_Name_Card where idx = '$first_card_idx'";
	$result = mysql_query($sql);
	$main_card_row = mysql_fetch_array($result);
	$main_img1 = $main_card_row['main_img1'];
}

$sql_point = "select key_content from Gn_Search_Key where key_id='callback_set_point'";
$res_point = mysql_query($sql_point);
$row_point = mysql_fetch_array($res_point);
$callback_set_point = $row_point['key_content'];
?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<meta property="og:title" content="<?= $page_title ?>">
	<meta property="og:image" content="<?= $main_img1 ?>">
	<link rel="stylesheet" href="/css/main.css" type="text/css">

	<link rel="stylesheet" href="css/slick.css" type="text/css">
	<link rel="stylesheet" href="../css/responsive.css" type="text/css">
	<link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">
	<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/iam/css/iam.css">

	<script src="/iam/js/jquery-3.1.1.min.js"></script>
	<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-3E40Q09QGE"></script>
	<script language="javascript">
		$(function() {
			$(document).ajaxStart(function() {
					console.log("loading");
					$("#ajax-loading").show();
				})
				.ajaxStop(function() {
					$("#ajax-loading").delay(10).hide(1);
				});
		})
	</script>
	<style>
		.m_div {
			width: 70%;
		}

		.auto_iam {
			padding: 7px;
			font-size: 15px;
			background-color: black;
			font-weight: 500;
			border-radius: 10px;
			width: 28%;
		}

		.people_iam_show {
			font-size: 22px;
			background-color: #7be278;
			font-weight: bold;
			color: white;
			width: 290px;
			border: none;
			margin-top: 20px;
			padding: 9px;
			border-radius: 10px;
		}

		#checkdup {
			border: 1px solid black;
			float: right;
			background-color: black;
			color: white;
			border-radius: 5px;
		}

		.mem_info {
			margin-right: 35%;
			margin-bottom: 5px;
			display: flex;
			float: left;
			height: 30px;
		}

		#id_html {
			font-weight: normal;
			font-size: 13px;
			margin-top: 15px;
			margin-bottom: -15px;
		}

		.meminfo {
			margin-top: 20px;
			margin-left: 33%;
			width: 75%;
			height: 105px;
		}

		#mem_id {
			font-size: 15px;
			width: 32%;
		}

		@media only screen and (max-width: 500px) {
			.m_div {
				width: 100%;
			}

			.mem_info {
				margin-right: 10%;
				margin-bottom: 5px;
				display: flex;
				float: left;
				height: 30px;
			}

			#checkdup {
				border: 1px solid black;
				float: right;
				background-color: black;
				color: white;
				width: 75px;
				border-radius: 5px;
			}

			.people_iam_show {
				font-size: 22px;
				background-color: #7be278;
				font-weight: bold;
				color: white;
				width: 115px;
				border: none;
				margin-top: 20px;
				padding: 9px;
				border-radius: 10px;
			}

			#mem_id {
				font-size: 15px;
				width: 40%;
			}

			.meminfo {
				margin-top: 20px;
				margin-left: 15%;
				width: 75%;
				height: 105px;
			}

			#id_html {
				font-weight: normal;
				font-size: 13px;
				margin-top: 15px;
				margin-bottom: -15px;
			}
		}

		@media only screen and (max-width: 420px) {
			#mem_id {
				font-size: 15px;
				width: 37%;
			}

			.meminfo {
				margin-top: 20px;
				margin-left: 15%;
				width: 75%;
				height: 105px;
			}

			.auto_iam {
				width: 35%;
			}

			#id_html {
				font-weight: normal;
				font-size: 13px;
				margin-top: 15px;
				margin-bottom: -15px;
			}
		}

		@media only screen and (max-width: 350px) {
			.meminfo {
				margin-top: 20px;
				margin-left: 15%;
				width: 100%;
				height: 105px;
			}

			.auto_iam {
				width: 40%;
			}

			#id_html {
				font-weight: normal;
				font-size: 13px;
				margin-top: 15px;
				margin-bottom: -15px;
			}
		}

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
	</style>
</head>

<body class="body-event">
	<div class="m_div">
		<div class="">
			<div class="common-wrap"><!-- 컨텐츠 영역 시작 -->
				<div class="container" style="width:100%">
					<div class="row">
						<div class="col-12">
							<div class="inner-wrap">
								<section class="input-field">
									<section class="agreement-field">
										<h2 id="msg_title" style="text-align:center;"><?= str_replace("\n", "<br>", $event_data['event_title']) ?></h2>
										<div style="border-bottom: 2px solid #ddd;"></div>
										<h4 class="" id="msg_desc" style="margin-top:5px;padding: 5px;overflow-wrap: break-word;"><?= $desc ?></h4>
										<div style="margin-top:15px;text-align:center;background:#7f7f7f;color:white;font-weight:bold;padding:10px">IAM [아이디]를 입력 후 신청해주시면<br>아래 내용을 콜백으로 내 휴대폰에서 발송할 수 있어요!</div>
										<div style="border:1px solid;">
											<div class="container" style="padding:10px;width:100%;text-align: center;">
												<img src="<?= $img ?>" style="width:100%;">
											</div>
											<div style="width:100%;display:flex">
												<h4 style="background:#7f7f7f;color:white;font-weight:bold;padding:10px">콜백제목</h4>
												<h3 id="call_title" style="width:calc(100% - 100px);text-align:center;margin: 10px 0px;padding: 5px;overflow-wrap: break-word;border-bottom:1px solid black"><?= $event_desc ?></h3>
											</div>
											<h4 class="" id="call_detail" style="text-align:left;margin: 10px;overflow-wrap: break-word;">
												<?= $event_sms ?><br><br>
												<a href="<?= $iam_link ?>" target="_blank" style="color: blue;font-size:16px;"><?= $iam_link?></a>
											</h4>
										</div>
										<div class="container" style="margin-top: 20px;text-align: center;width: 100%;">
											<?
											if ($_SESSION['iam_member_id'] != "") {
											?>
												<input type="text" name="member_id" id="member_id" value="<?= $_SESSION['iam_member_id'] ?>" placeholder="회원님의 아이디를 입력하세요." style="width:200px;padding:5px;font-size: 14px;">
											<? } else if ($_SESSION['one_member_id'] != "") { ?>
												<input type="text" name="member_id" id="member_id" value="<?= $_SESSION['one_member_id'] ?>" placeholder="회원님의 아이디를 입력하세요." style="width:200px;padding:5px;font-size: 14px;">
											<? } else { ?>
												<input type="text" name="member_id" id="member_id" placeholder="회원님의 아이디를 입력하세요." style="width:200px;padding:5px;font-size: 14px;">
											<? } ?>
											<input type="hidden" id="callback_times" value="<?=$callback_times?>">
										</div>
										<div class="container" style="text-align: center;width: 100%;">
											<button class="people_iam_show" onclick="makingiam('make')">신청하기</button>
										</div>
									</section>
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	<div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>
	<script>
		function makingiam() {
			<? if ($cur_point < $callback_set_point) { ?>
				alert("관리자님의 포인트가 부족합니다. 충전후 이용해 주세요.");
				$.ajax({
					type: "POST",
					dataType: "json",
					url: '/ajax/service_point_state.php',
					data: {
						point_stop: true,
						service_id: '<?= $event_data['m_id'] ?>'
					},
					success: function(data) {

					}
				});
				return;
			<? } else { ?>
				var event_idx = <?= $event_idx ?>;
				var callback_no = <?= $callback_no ?>;
				var callback_times = $("#callback_times").val();
				if ($("#member_id").val() == "") {
					alert('회원님의 아이디를 입력하세요.');
					return;
				} else {
					var member_id = $("#member_id").val();
					$.ajax({
						type: 'POST',
						dataType: 'json',
						url: '/ajax/callback_msg_reg.php',
						data: {
							member_id: member_id,
							event_idx: event_idx,
							callback_no: callback_no,
							callback_times:callback_times
						},
						success: function(data) {
							if (data.result == 3) {
								alert('아이디를 정확히 입력하세요.');
								return;
							} else if (data.result == 2) {
								alert('현재 기능 정지 중입니다. 관리자에게 문의해주세요.');
								return;
							} else {
								$("#result_modal").modal("show");
							}
						},
						error: function(request, status, error) {
							console.log(request + "=>" + status + ">>>" + error);
						}
					});
				}
			<? } ?>
		}
	</script>
</body>
<!-- 신청결과 모달 -->
<div id="result_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
	<div class="modal-dialog" style="margin: 100px auto;">
		<!-- Modal content-->
		<div class="modal-content">
			<div>
				<button type="button" class="close" data-dismiss="modal" style="opacity:2 !important">
					<img src="/iam/img/menu/icon_close_white.png" style="width:24px;opacity:2 !important" class="close" data-dismiss="modal">
				</button>
			</div>
			<div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
				<label>콜백등록완료</label>
			</div>
			<div class="modal-body">
				<div class="container" style="text-align: center;width:100%">
					<p style="font-weight: bold;font-size:18px">감사합니다!</p>
					<p style="font-weight: 700">회원님의 휴대폰에 <br>콜백메시지가 저장되어 <br>통화나 문자를 한 후<br>콜백이 자동으로 전송됩니다!</p>
				</div>
			</div>
			<div class="modal-footer" style="padding:0px;display:flex">
				<button type="button" class="btn btn-active btn-center" onclick="$('#result_modal').modal('hide');">확인</button>
			</div>
		</div>
	</div>
</div>

</html>