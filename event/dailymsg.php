<?
	include_once "../lib/rlatjd_fun.php";
	extract($_REQUEST);
	if($_GET['eventidx']) $event_idx = $_GET['eventidx'];
	if($_GET['pcode']) $pcode = $_GET['pcode'];

	$sql_recom = "select * from Gn_event where pcode='{$pcode}'";
	$res = mysqli_query($self_con, $sql_recom);
	$event_data = $row = mysqli_fetch_array($res);
	$recom_id = $row['m_id'];
	$up_img = $row['object'];
	$daily_cnt = $row['callback_no'];
	$mtime = $row['event_type'];
	$htime = $row['event_sms_desc'];

	$event_title = $page_title = $row['event_title'];
	$desc = str_replace("\n", "<br>", $row['event_desc']);

	$msg_desc = str_replace("\n", "<br>", $row['event_req_link']);


	$sql="update Gn_event set read_cnt = read_cnt+1 where pcode='$pcode'";
	mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

	$cur_point = 0;
	$sql_cur_point = "select mem_point from Gn_Member where mem_id='{$recom_id}'";
	$res_point = mysqli_query($self_con, $sql_cur_point);
	$row_point = mysqli_fetch_array($res_point);
	$cur_point = $row_point['mem_point'];

	if ($HTTP_HOST != "kiam.kr") //분양사사이트이면
		$query = "select * from Gn_Iam_Service where sub_domain like 'http://" . $HTTP_HOST . "'";
	else
		$query = "select * from Gn_Iam_Service where sub_domain like 'http://www.kiam.kr'";
	$res = mysqli_query($self_con, $query);
	$domainData = mysqli_fetch_array($res);
	$first_card_idx = $domainData['profile_idx'];//분양사의 1번 카드아이디
	$sql = "select * from Gn_Iam_Name_Card where idx = '$first_card_idx'";
	$result = mysqli_query($self_con, $sql);
	$main_card_row = mysqli_fetch_array($result);

	$main_img1 = $main_card_row['main_img1'];

	$img_name_arr = array();
	$img_cnt = 0;
	if($up_img != ""){
		if(strpos($up_img, ",") !== false){
			$img_name_arr = explode(",", $up_img);
			$img_cnt = count($img_name_arr);
			$main_img1 = "http://www.kiam.kr/".trim($img_name_arr[0]);
		}
		else{
			$img_cnt = 1;
			$img_name_arr[0] = $up_img;
			$main_img1 = "http://www.kiam.kr/".$up_img;
		}
	}

	$sql_point = "select key_content from Gn_Search_Key where key_id='dailymsg_set_point'";
	$res_point = mysqli_query($self_con, $sql_point);
	$row_point = mysqli_fetch_array($res_point);
	$daily_set_point = $row_point['key_content'];
?>
<!DOCTYPE html>
<html lang="ko">
	<head>
	    <meta charset="UTF-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1" />

		<meta property="og:title" content="<?=$page_title?>">
		<meta property="og:image" content="<?=$main_img1?>">
        <link rel="stylesheet" href="/css/main.css" type="text/css">

		<link rel="stylesheet" href="css/slick.css" type="text/css">
		<link rel="stylesheet" href="../css/responsive.css" type="text/css">
		<link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">

		<script src="/iam/js/jquery-3.1.1.min.js"></script>
		<script language="javascript" type="text/javascript" src="common.js"></script>
		<script language="javascript" type="text/javascript" src="jquery.rotate.js"></script>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-3E40Q09QGE"></script>
		<script language="javascript">
			$(function(){
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
				width:70%;
			}
			.auto_iam {
				padding: 7px;
				font-size: 15px;
				background-color: black;
				font-weight: 500;
				border-radius:10px;
				width:28%;
			}
			.people_iam_show{
				font-size:22px;
				background-color:#7be278;
				font-weight:bold;
				color:white;
				/* margin-left:30%; */
				width:290px;
				border: none;
				margin-top:20px;
				padding:9px;
				border-radius:10px;
			}
			#checkdup {
				border: 1px solid black;
				float:right;
				background-color: black;
				color: white;
				/* width: 75px; */
				border-radius: 5px;
			}
			.mem_info{
				margin-right:35%;
				margin-bottom:5px;
				display:flex;
				float:left;
				height: 30px;
			}
			#id_html{
				font-weight:normal;
				font-size:13px;
				margin-top: 15px;
				margin-bottom: -15px;
			}
			.meminfo{
				margin-top: 20px;
				margin-left: 33%;
				width: 75%;
				height:105px;
			}
			#mem_id{
				font-size:15px;
				width: 32%;
			}

			@media only screen and (max-width: 500px) {
				.m_div {
					width:100%;
				}
				.mem_info{
					margin-right:10%;
					margin-bottom:5px;
					display:flex;
					float:left;
					height: 30px;
				}
				#checkdup {
					border: 1px solid black;
					float:right;
					background-color: black;
					color: white;
					width: 75px;
					border-radius: 5px;
				}
				.people_iam_show{
					font-size:22px;
					background-color:#7be278;
					font-weight:bold;
					color:white;
					/* margin-left:35%; */
					width:115px;
					border: none;
					margin-top:20px;
					padding:9px;
					border-radius:10px;
				}
				#mem_id{
					font-size:15px;
					width: 40%;
				}
				.meminfo{
					margin-top: 20px;
					margin-left: 15%;
					width: 75%;
					height:105px;
				}
				#id_html{
					font-weight:normal;
					font-size:13px;
					margin-top: 15px;
					margin-bottom: -15px;
				}
			}

			@media only screen and (max-width: 420px) {
				#mem_id{
					font-size:15px;
					width: 37%;
				}
				.meminfo{
					margin-top: 20px;
					margin-left: 15%;
					width: 75%;
					height:105px;
				}
				.auto_iam {
					width:35%;
				}
				#id_html{
					font-weight:normal;
					font-size:13px;
					margin-top: 15px;
					margin-bottom: -15px;
				}
			}

			@media only screen and (max-width: 350px) {
				.meminfo{
					margin-top: 20px;
					margin-left: 15%;
					width: 100%;
					height:105px;
				}
				.auto_iam {
					width:40%;
				}
				#id_html{
					font-weight:normal;
					font-size:13px;
					margin-top: 15px;
					margin-bottom: -15px;
				}
			}

			.tooltiptext-bottom {
				width: 420px;
				font-size:15px;
				background-color: white;
				color: black;
				text-align: left;
				position: absolute;
				z-index: 200;
				top: 25%;
				left: 35%;
			}
			.title_app{
				text-align: center;
				background-color: rgb(130,199,54);
				padding: 10px;
				font-size: 20px;
				color: white;
				font-weight: 900;
			}
			.desc_app{
				padding: 15px;
			}
			.button_app{
				text-align: center;
				padding: 10px;
			}

			@media only screen and (max-width: 450px) {
				.tooltiptext-bottom{
					width: 80%;
					left:8%;
				}
			}

			#ajax-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:9000;text-align:center;display:none;background-color: #fff;opacity: 0.8;}
			#ajax-loading img{position:absolute;top:50%;left:50%;width:120px;height:120px;margin:-60px 0 0 -60px;}

			#tutorial-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:150;text-align:center;display:none;background-color: grey;opacity: 0.7;}
		</style>
	</head>
	<body class="body-event">
		<div class="m_div">
			<div class="join">
				<div class="common-wrap"><!-- 컨텐츠 영역 시작 -->
					<div class="container" style="max-width: 970px !important;">
						<div class="row">
							<div class="col-12">
								<div class="inner-wrap">
									<section class="input-field">												
										<section class="agreement-field">
											<h2 id="msg_title" style="text-align:center; border-bottom:1px solid;"><?=$event_data['event_title']?></h2>
											<h4 class="" id = "msg_desc" style="margin-top: 35px; text-align:left;margin: 30px 0 0 10%;width: 80%;padding: 5px;font-size: 15px;overflow-wrap: break-word;"><?=$desc?>
											</h4>
											<div style="border:1px solid;">
											<?if($img_cnt){?>
												<div class="container" style="margin: 10px 0 0 9%;text-align: center;width: 83%;padding: 5px;">
												<?
												for($i = 0; $i < $img_cnt; $i++){
												?>
													<img src="<?="http://www.kiam.kr/".trim($img_name_arr[$i])?>" style="width:100%;">
												<?}?>
												</div>
											<?}?>
												<div class="container" style="margin-top: 20px;text-align: center;width: 100%;">
													<h4 id="call_title" style="text-align:left;margin: 20px 0 0 10%;width: 80%;padding: 5px;font-size: 15px;overflow-wrap: break-word;"><?=$event_data['event_info']?></h4>
													<h4 class="" id = "call_detail" style="text-align:left;margin: 20px 0 0 10%;width: 80%;padding: 5px;font-size: 15px;overflow-wrap: break-word;">
														<?=$msg_desc?>
													</h4><br>
													<a href="<?=$event_data['daily_req_link']?>" target="_blank" style="color: blue;font-size: 15px;"><?=$event_data['daily_req_link']?></a>
												</div>
											</div>
											<div class="container" style="margin: 10px 0 0 9%;text-align: center;width: 83%;padding: 5px;">
											<?
											if($_SESSION['iam_member_id'] != ""){
											?>
												<input type="text" name="member_id" id="member_id" value="<?=$_SESSION['iam_member_id']?>" placeholder="회원님의 아이디를 입력하세요." style="width:250px;font-size: 15px;padding:5px;">
											<?} else if($_SESSION['one_member_id'] != ""){?>
												<input type="text" name="member_id" id="member_id" value="<?=$_SESSION['one_member_id']?>" placeholder="회원님의 아이디를 입력하세요." style="width:250px;font-size: 15px;padding:5px;">
											<?} else{?>
												<input type="text" name="member_id" id="member_id" placeholder="회원님의 아이디를 입력하세요." style="width:250px;font-size: 15px;padding:5px;">
											<?}?>
											</div>
											<div class="container" style="margin-top: 20px;text-align: center;width: 100%;">
												<button class="people_iam_show" style="font-size: 15px;background-color: #6868ed;width: 200px;" onclick="deny_add_multi()">발송 제외 폰 등록하기</button>
											</div>
											<div class="container" style="margin-top: 20px;text-align: center;width: 100%;">
												<button class="people_iam_show"  onclick="makingiam('make')">신청하기</button>
											</div>
										</section>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<span class="tooltiptext-bottom" id="tooltiptext_add_deny_multi" style="display:none;">
			<p class="title_app">제외 대상 추가<span onclick="cancel_set()" style="float:right;cursor:pointer;">X</span></p>
			<table class="table table-bordered" style="width: 97%;">
				<tbody>
					<input type="hidden" name="btn_type" id="btn_type" value="add_deny">
					<input type="hidden" name="req_mem_id" id="req_mem_id">
					<input type="hidden" name="req_mem_phone" id="req_mem_phone">
					<div style="text-align:center;">
						<button class="people_iam_show" style="font-size: 15px;margin: 0px;margin-bottom: 15px;background-color: #4ab4ff;border-radius:0px;width: 150px;margin-top: 15px;color: white;border: none;padding: 5px;" onclick="get_addr_list('undeny')">제외리스트<br>보기/해제</button>
						<button class="people_iam_show" style="font-size: 15px;margin: 0px;margin-bottom: 15px;background-color: rgb(9 117 193);border-radius:0px;width: 150px;margin-top: 15px;color: white;border: none;padding: 5px;" onclick="get_addr_list('deny')">내 주소록에서<br>제외대상 추가</button>
					</div>
					<h3 style="margin-left:20px;">
						제외번호 입력
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
					1. 제외 대상등록 : 주소록가져오기 또는 수동입력으로 제외대상을 등록하세요.<br><br>
					2. 제외 해제설정 : 제외리스트/해제 클릭 후 번호를 선택해서 해제하세요.
				</p>
			</div>
			<div class="button_app">
				<a href="javascript:clear_nums()" class="btn login_signup" style="width: 40%;background-color: #82c736;border-radius: 3px;padding: 5px;color: white;">취소</a>
				<a href="javascript:add_deny_multi()" class="btn login_signup" style="width: 40%;background-color: #82c736;border-radius: 3px;color: white;padding: 5px;">등록</a>
			</div>
		</span>
		<div id="tutorial-loading"></div>
		<div id="ajax_div"></div>
		<div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>
		<script>
			function makingiam(){
				<?php
				if($cur_point < $daily_set_point){
				?>
				alert("관리자님의 포인트가 부족합니다. 충전후 이용해 주세요.");
				$.ajax({
					type:"POST",
					dataType:"json",
					url:'/ajax/service_point_state.php',
					data:{point_stop:true, service_id:'<?=$event_data['m_id']?>'},
					success:function(data){

					}
				});
				return;
				<?}?>

				var event_idx = <?=$event_idx?>;
				
				if($("#member_id").val() == ""){
					alert('회원님의 아이디를 입력하세요.');
					return;
				}
				else{
					var member_id = $("#member_id").val();
					$.ajax({
						type:'POST',
						dataType:'json',
						url:'/ajax/daily_msg_reg.php',
						data:{member_id:member_id, event_idx:event_idx},
						success:function(data){
							console.log(data);
							if(data == 3){
								alert('아이디를 정확히 입력하세요.');
								return;
							}
							else if(data == 2){
								alert('현재 기능 정지 중입니다. 관리자에게 문의해주세요.');
								return;
							}
							else{
								alert("신청 되었습니다.");
							}
						}
					});
				}
			}

			function deny_add_multi(){
				var mem_id = $("#member_id").val();
				if(mem_id == ''){
					alert("아이디를 입력해 주세요.");
					return;
				}
				$("#req_mem_id").val(mem_id);
				$("#tooltiptext_add_deny_multi").show();
				$("#tutorial-loading").show();
			}

			function cancel_set(){
				$("#tooltiptext_add_deny_multi").hide();
				$("#tutorial-loading").hide();
			}

			function clear_nums(){
				$("#deny_num_multi").val('');
			}

			function add_deny_multi(){
				var recv_nums = $("#deny_num_multi").val();
				var send_num = $("#req_mem_phone").val();
				var mem_id = $("#req_mem_id").val();

				var type = $("#btn_type").val();
				$($(".loading_div")[0]).show();
				$.ajax({
					type: "POST",
					url: "/ajax/add_deny_multi.php",
					data: {
						deny_add_send: send_num,
						deny_add_recv: recv_nums,
						mem_id:mem_id,
						reg_chanel:9,
						type:type
					},
					success: function (data) {
						$($(".loading_div")[0]).hide();
						$("#ajax_div").html(data);
					}
				});
			}

			function get_addr_list(val){
				var mem_id = $("#req_mem_id").val();
				if(val == "deny"){
					$("#btn_type").val("add_deny");
				}
				else{
					$("#btn_type").val("unadd_deny");
				}
				$.ajax({
					type: "POST",
					url: "/ajax/get_mem_address.php",
					data: {
						mem_id:mem_id,
						mode:"get_phone_num"
					},
					success: function (data) {
						$("#req_mem_phone").val(data);
						window.open('/group_detail_for_adddeny.php?phone='+data+'&mem_id='+mem_id+'&type='+val, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=500,height=600");
					}
				});
			}
        </script>
	</body>
</html>
