<!DOCTYPE html>
<html lang="ko">
	<head>
	    <meta charset="UTF-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link rel="stylesheet" href="/css/main.css" type="text/css">

		<link rel="stylesheet" href="css/slick.css" type="text/css">
		<link rel="stylesheet" href="../css/responsive.css" type="text/css">
		<link rel="stylesheet" href="../css/font-awesome.min.css" type="text/css">

		<script src="/iam/js/jquery-3.1.1.min.js"></script>
		<script language="javascript" type="text/javascript" src="common.js"></script>
		<script language="javascript" type="text/javascript" src="jquery.rotate.js"></script>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-3E40Q09QGE"></script>
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
											<div style="border:1px solid;">
											<h4 class="" id = "call_detail" style="text-align:left;margin: 20px 0 0 10%;width: 80%;padding: 5px;font-size: 15px;overflow-wrap: break-word;">
											불편을 드려 죄송합니다.<br>
											최근 기승을 부리는 디도스 공격때문에 로그인 정보를 확인하고 오픈을 하고 있습니다.<br> 한번 확인받은 아이피의 경우에는 계속 이용가능합니다.<br> 혹시 휴대폰에서 아이피가 변경될 경우에도 보안을 위해 계정정보를 확인할수 있습니다.<br> 아래에 계정정보 입력해주시면 차단이 해제됩니다.<br> 감사합니다. 
											</h4>
											</div>
											<div class="container" style="margin-top: 20px;text-align: center;width: 100%;">
												<input type="text" name="member_id" id="member_id" placeholder="회원님의 아이디를 입력하세요." style="width:200px;font-size: 14px;padding: 7px;margin-bottom: 10px;">
												<!-- <input type="text" name="member_pwd" id="member_pwd" placeholder="회원님의 비번을 입력하세요." style="width:200px;font-size: 14px;padding: 7px;margin-bottom: 10px;"> -->
												<input type="text" name="member_phone" id="member_phone" placeholder="폰번호 (-)없이  입력하세요." style="width:200px;font-size: 14px;padding: 7px;margin-bottom: 10px;">
											</div>
											<div class="container" style="margin-top: 20px;text-align: center;width: 100%;">
												<button class="people_iam_show" onclick="makingiam()">확인</button>
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
		<script>
			function makingiam(){
				if($("#member_id").val() == "" || $("#member_phone").val() == "" || $("#member_pwd").val() == ""){
					alert('회원정보를 입력하세요.');
					return;
				}
				else{
					var member_id = $("#member_id").val();
					var member_phone = $("#member_phone").val();
					// var member_pwd = $("#member_pwd").val();
					$.ajax({
						type:'POST',
						dataType:'json',
						url:'/ajax/block_ip_allow.php',
						data:{member_id:member_id, member_phone:member_phone},
						success:function(data){
							if(data.result == "0"){
								alert('회원님이 입력한 계정 정보가 맞지 않습니다. 다시 확인하고 입력해주세요.');
								return;
							}
							else{
								alert("해지 되었습니다.");
								location.href="<?=$_GET[cur_url]?>";
							}
						}
					});
				}
			}
        </script>
	</body>
</html>
