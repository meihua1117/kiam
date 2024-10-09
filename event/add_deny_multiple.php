<?
	include_once "../lib/rlatjd_fun.php";

	if(!isset($_GET['userid'])){
		exit;
	}
	else{
		$mem_id=$_GET['userid'];
	}
	
	$sql_mem_data = "select mem_phone, mem_id from Gn_Member where mem_id='{$mem_id}'";
	$res_mem_data = mysql_query($sql_mem_data);
	$row_mem_data = mysql_fetch_array($res_mem_data);
	$mem_phone = $row_mem_data['mem_phone'];
	$mem_id = $row_mem_data['mem_id'];

	if(!$mem_id){
		exit;
	}

	$phone = str_replace("-", "", $mem_phone);
	$sql_grp = "select idx from Gn_MMS_Group where grp like '%{$phone}%' order by reg_date desc limit 1";
	$res_grp = sql_query($sql_grp);
	$row_grp = sql_fetch_array($res_grp);
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
				width:200px;
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
					font-size:15px;
					background-color:#7be278;
					font-weight:bold;
					color:white;
					/* margin-left:35%; */
					width:80px;
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

			#ajax-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:9000;text-align:center;display:none;background-color: #fff;opacity: 0.8;}
			#ajax-loading img{position:absolute;top:50%;left:50%;width:120px;height:120px;margin:-60px 0 0 -60px;}
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
											<input type="hidden" name="btn_type" id="btn_type" value="deny">
											<h2 id="msg_title" style="text-align:center; background-color:rgb(130,199,54);color: white;padding: 10px;">자동콜백 제외 대상 추가하기</h2>
											<div>
												<button class="people_iam_show" style="font-size: 15px;margin: 0px;margin-bottom: 15px;background-color: #4ab4ff;border-radius:0px;width: 48%;" onclick="get_addr_list('undeny')">제외리스트<br>보기/해제</button>
												<button class="people_iam_show" style="font-size: 15px;margin: 0px;margin-bottom: 15px;background-color: #0975c1;border-radius:0px;width: 48%;float: right;" onclick="get_addr_list('deny')">내 주소록에서<br>제외대상 추가</button>
											</div>
											<h4 class="" id = "msg_desc" style="margin-top: 35px; text-align:left;margin: 20px 0 10px 10%;width: 80%;padding: 5px;font-size: 15px;overflow-wrap: break-word;">콜백제외번호
											</h4>
											<textarea name="deny_num_multi" id="deny_num_multi" placeholder="전화번호(쉼표, 엔터키로 구분)" style="width:200px;font-size: 14px;height: 200px;width:98%;"></textarea>
											<div style="border:1px solid;margin-top:15px;">
												<p style="font-size:16px;color:#6e6c6c;padding:10px;">
													1. 콜백제외 대상등록 : 주소록가져오기 또는 수동입력으로 제외대상을 등록하세요.<br><br>
													2. 콜백제외 해제설정 : 제외리스트/해제 클릭 후 번호를 선택해서 해제하세요.
												</p>
											</div>
											<div class="container" style="margin-top: 20px;text-align: center;width: 100%;">
												<button class="people_iam_show" style="width:40%" onclick="cancel()">취소</button>
												<!-- <button class="people_iam_show" style="" onclick="view_list()">목록보기</button> -->
												<button class="people_iam_show" style="width:40%" onclick="makingiam('make')">등록</button>
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
		<div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>
		<script>
			function cancel(){
				location.reload();
			}
			function view_list(){
				location.href="/sub_4.php?status=6";
			}
			function makingiam(){
				var recv_nums = $("#deny_num_multi").val();
				if(recv_nums == ""){
					alert("수신 번호를 입력해 주세요.");
					$("#deny_num_multi").focus();
					return;
				}
				var send_num = '<?=$mem_phone?>';
				var mem_id = '<?=$mem_id?>';

				var type = $("#btn_type").val();
				$.ajax({
					type: "POST",
					url: "/ajax/add_deny_multi_event.php",
					dataType:"json",
					data: {
						deny_add_send: send_num,
						deny_add_recv: recv_nums,
						mem_id:mem_id,
						reg_chanel:9,
						type:type
					},
					success: function (data) {
						if(data == 1){
							alert("콜백발신제한 등록이 완료되었습니다.\n셀링사이트에서 확인가능합니다.");
							location.reload();
						}
						else if(data == 2){
							alert('발신번호는 등록된 번호가 아닙니다.');
						}
						else if(data == 3){
							alert('수신번호를 확인해 주세요.');
							location.reload();
						}
						else if(data == 4){
							alert('정확한 번호가 아닙니다.[발신번호error]');
						}
						else if(data == 5){
							alert('제외리스트에서 해제 되었습니다.');
							location.reload();
						}
					}
				});
			}
			function get_addr_list(val){
				if(val == "deny"){
					$("#btn_type").val("deny");
				}
				else{
					$("#btn_type").val("undeny");
				}

				window.open('/group_detail_for_adddeny.php?phone=<?=$phone?>&mem_id=<?=$mem_id?>&type='+val, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=500,height=600");
			}
        </script>
	</body>
</html>
