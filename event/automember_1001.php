<?
	include_once "../lib/rlatjd_fun.php";
	extract($_REQUEST);
	if($_GET['event_idx']) $event_idx = $_GET['event_idx'];
	if($_GET['pcode']) $pcode = $_GET['pcode'];

	$sql_recom = "select m_id, event_info, event_type from Gn_event where pcode='{$pcode}'";
	$res = mysql_query($sql_recom);
	$row = mysql_fetch_array($res);
	$recom_id = $row['m_id'];
	$button_txt = $row['event_type'];
	$card_short_url = $row['event_info'];

	$sql_site = "select site, site_iam from Gn_Member where mem_id='{$recom_id}'";
	$res_site = mysql_query($sql_site);
	$row_site = mysql_fetch_array($res_site);
	$site = $row_site['site'];
	$site_iam = $row_site['site_iam'];
	
	$sql_exp = "select service_type from Gn_Iam_Service where sub_domain='http://".$HTTP_HOST."'";
	$res_exp = mysql_query($sql_exp);
	$row_exp = mysql_fetch_array($res_exp);
	$exp_mem = $row_exp['service_type'];
	// echo $exp_mem;
	if($exp_mem == 3){
		$exp_mem = 'true';
	}
	else{
		$exp_mem = 'false';
	}

	$sql="update Gn_event set read_cnt = read_cnt+1 where pcode='$pcode'";
	mysql_query($sql) or die(mysql_error());
	
	$sql="select * from Gn_event where pcode='$pcode'";
	$result = mysql_query($sql) or die(mysql_error());
	$event_data = $row=mysql_fetch_array($result);
	$m_id = $row['m_id'];
	$event_idx = $row['event_idx'];		

	$desc = str_replace("\n", "<br>", $event_data['event_desc']);
	
	if($HTTP_HOST != "obmms.net")
	    $query = "select * from Gn_Iam_Service where sub_domain like 'http://".$HTTP_HOST."'";
	else
	    $query = "select * from Gn_Iam_Service where sub_domain like 'http://www.obmms.net'";
	$res = mysql_query($query);
	$domainData = mysql_fetch_array($res);
	$first_card_idx = $domainData['profile_idx'];
	$sql = "select * from Gn_Iam_Name_Card where idx= '$first_card_idx'";
	$result = mysql_query($sql);
	$main_card_row = mysql_fetch_array($result);
	$main_img1 = $main_card_row['main_img1'];
	//echo $main_img1;
?>
<!DOCTYPE html>
<html lang="ko">
	<head>
	    <meta charset="UTF-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1" />
	    <meta property="og:title" content="오토회원가입메시지">
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
				margin-left:35%;
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
					margin-left:35%;
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
											<form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post">
												<input type="hidden" name="one_id"   value="" />
												<input type="hidden" name="mem_pass" value="" />
												<input type="hidden" name="mem_code" value="" />
											</form>
											<h2 id="msg_title" style="text-align:center; border-bottom:1px solid;"><?=$event_data['event_title']?></h2>
											<!-- <textarea style="font-size:15px;text-align:left;font-weight:bold;width:100%; height:152px;" id="msgdesc" readonly><?=$event_data['event_desc']?></textarea> -->
											<p style="font-size:15px;"><?=$desc?></p>
											<div class="container" style="margin-top: 20px;text-align: center;width: 100%;">
												<input type="button" class="auto_iam" onclick="javascript:location.href='/iam/index_sample.php'" target="_blank" style="color:white;" value="추천IAM보기">
												<?
												if($button_txt != ""){
												?>
												<input type="button" class="auto_iam" onclick="javascript:location.href='<?=$event_data['event_sms_desc']?>'" style="margin-left:25px;color:white;" value="<?=$button_txt?>" target="_blank">
												<?}?>
											</div>
											<div class="container meminfo">
												<div class="mem_info">
													<p style="margin-right:5px;font-size:18px;width: 54px;">이름</p>
													<input type="text" style="font-size:15px;width: 70%;" id="mem_name" placeholder="이름을 입력하세요">
												</div>
												<div class="mem_info">
													<p style="margin-right:5px;font-size:18px;width: 54px;">휴대폰</p>
													<input type="text" style="font-size:15px;width: 70%;" id="mem_phone" placeholder="-없이 번호를 입력하세요">
												</div>
												<div class="mem_info">
													<p style="margin-right:5px;font-size:18px;width: 54px;">아이디</p>
													<input type="text" id="mem_id" placeholder="소문자로 입력하세요">
													<input type="button" id="checkdup" value="ID중복확인" onclick="id_check1()">
												</div>
												
											</div>
											<li id="id_html" style="" hidden><img src="/images/check.gif"> 사용 가능하신 아이디입니다.</li>
											<h4 class="" id = "" style="margin-top: 35px; text-align:left;margin: 30px 0 0 10%;width: 80%;border:1px solid;padding: 5px;">
											신청 후 IAM회원으로 가입되며, 나의 전자명함이 자동으로 만들어집니다. <새카드 만들기>로 나의 명함을 추가할 수 있습니다. 비밀번호는 휴대폰번호 뒤 4자리입니다. 마이페이지에서 수정 가능합니다.</h4>
											<h3 class="title" id = "agreement-field" style="">
												약관 동의하기<span style="color:red">[필수]</span>
												<input type="checkbox" class="check" id="checkAll_title" >
												<label for="checkAll_title">모두 동의</label>
											</h3>
											<p class="notice">서비스 이용을 위한 이용약관, 개인정보 처리방침 등 필수, 선택 등의 항목에 동의해주세요.</p>
											<div class="agreement-wrap" style="display: none" id = "agreement-wrap">
												<div class="agreement-item is-checkall">
													<div class="check-wrap">
														<input type="checkbox" class="check" id="checkAll" required itemname='약관동의' >
														<label for="checkAll"> <strong>모두동의에 클릭하면 전체를 클릭합니다</strong> </label>
													</div>
												</div>
												<div class="agreement-item">
													<div class="check-wrap">
														<input type="checkbox" class="check checkAgree" id="checkPersonal" >
														<label for="checkPersonal">개인정보수집동의</label>
													</div>
													<a href="/m/privacy.php" target="_blank">전문보기</a>
												</div>
												<div class="agreement-item">
													<div class="check-wrap">
														<input type="checkbox" class="check checkAgree" id="checkTerms" >
														<label for="checkTerms">회원이용약관</label>
													</div>
													<a href="/m/terms.php" target="_blank">전문보기</a>
												</div>
												<div class="agreement-item">
													<div class="check-wrap">
														<input type="checkbox" class="check checkAgree" id="checkReceive" >
														<label for="checkReceive">메시지수신동의</label>
													</div>
													<div class="desc" style="width:90%;">
														<ul>
															<li>① 메시지 종류 : 아이엠 프로필 및 솔루션의 기능개선 메시지정보, 앱체크정보, 회원관리정보, 공익정보, 유익정보,  회원프로필정보를 발송합니다.</li>
															<li>② 메시지 발송 방법 : 고객님이 설치한 문자앱을 통해 고객님 폰의 문자를 활용하여 고객님의 계정에서 볼수 있게 합니다.</li>
														</ul>
													</div>
												</div>
												<div class="agreement-item">
													<div class="check-wrap">
														<input type="checkbox" class="check checkAgree" id="checkThirdparty" >
														<label for="checkThirdparty">개인정보 제3자 제공 동의</label>
													</div>
													<div class="desc" style="width:90%;">
														<ul>
															<li>① 제공받는 자 : 본 서비스를 개발하는 온리원계열사, 본 서비스 제공을 지원하는 협업사, 상품을 제공하는 쇼핑몰  관계사, 기타 본서비스 제공에 필요한 기관</li>
															<li>② 개인정보 이용 목적 : 서비스 제공을 위한 고객정보의 활용, 서비스 정보의 제공, e프로필서비스의 공유, 회원간의 품앗이 정보공유 등</li>
															<li>③ 개인정보의 항목 : 개인정보 제공에 동의한 내용</li>
															<li>④ 보유 및 이용 기간 :본 서비스를 이용하는 기간</li>
															<li>⑤ 제공 동의에 거부시 본 서비스가 제공되지 않습니다.</li>
														</ul>
													</div>
												</div>
											</div>
											<button class="people_iam_show" style="" onclick="makingiam('make')">신청하기</button>
											<button class="people_iam_show" id="goiam" onclick="gotoLogin()" style="" hidden>로그인</button>
											<input type="text" id="shorturl" hidden>
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
			$('#agreement-field').on("click",function(){
				console.log('ok');
				if($('#agreement-wrap').css("display") == "none")
					$('#agreement-wrap').show();
				else
					$('#agreement-wrap').hide();
			});
			$('.checkAgree').on("change",function(){
				$('.checkAgree').each(function(){
					if($(this).prop("checked") == false) {
						$('#checkAll').prop("checked", false);
						$('#checkAll_title').prop("checked", false);
					}
				});
			});

			$("#checkAll").on('click',function(){
				$("#checkAll_title").prop('checked', true);
				$('.checkAgree').each(function(){
					$(this).prop('checked', true);
				});
			});

			$("#checkAll_title").on('click', function(){
				$('#checkAll').prop("checked", true);
				$('.checkAgree').each(function(){
					$(this).prop('checked', true);
				});
			})

			function makingiam(){
				var recom_id = '<?=$recom_id?>';
				var site = '<?=$site?>';
				var site_iam = '<?=$site_iam?>';
				var card_short_url = '<?=$card_short_url?>';
				var exp_mem = '<?=$exp_mem?>';
				if($("#checkAll_title").prop('checked') == false){
					alert('모든 약관내용에 동의하셔야 신청하실수 있습니다.');
					return;
				}
				else{
					if($("#mem_name").val() == ""){
						alert('이름을 입력해주세요.');
						return;
					}
					else if($("#mem_phone").val() == ""){
						alert('폰번호를 입력해주세요.');
						return;
					}
					else if($("#mem_id").val() == ""){
						alert('아이디를 입력해주세요.');
						return;
					}
					style = $("#checkdup").attr('style');
					if(style.indexOf("blue") == -1){
						alert("아이디 중복확인을 하세요.");
						return;
					}
					// console.log(exp_mem); return;
					var mem_name = $("#mem_name").val();
					var mem_phone = $("#mem_phone").val();
					var mem_id = $("#mem_id").val();
					$.ajax({
						type:'POST',
						dataType:'json',
						url:'/ajax/auto_join.php',
						data:{mem_name:mem_name, mem_phone:mem_phone, mem_id:mem_id, site:site, site_iam:site_iam, recom_id:recom_id, card_short_url:card_short_url, event_id:<?=$event_idx?>, exp_mem:exp_mem},
						success:function(data){
							console.log(data);
							if(data.status == 1){
								iam_item(recom_id, 'use', mem_name, mem_id);
								alert('회원가입이 완료되었습니다. 비밀번호는 핸드폰 뒤 4자리 입니다. 마이페이지에서 수정 가능합니다.');
								$('input[name=one_id]').val(data.mem_id);
								$('input[name=mem_pass]').val(data.mem_pass);
								$('input[name=mem_code]').val(data.mem_code);
								$("#shorturl").val(data.short_url+data.mem_code);
								// $("#goiam").prop('hidden', false);
								gotoLogin();
							}
						}
					});
				}
			}

			function gotoLogin() {
				var link = $("#shorturl").val();
				console.log($('input[name=one_id]').val());
				$.ajax({
					type:"POST",
					url:"/admin/ajax/login_iamuser.php",
					data:$('form[name=login_form]').serialize(),
					success:function(){
						location.href = "/iam/index.php?"+link;
					},
					error: function(){
						alert('초기화 실패');
					}
				});
				return false;
			}

			function id_check1() {
				if (!$('#mem_id').val()) {
					$('#mem_id').focus();
					return;
				}
				if ($('#mem_id').val().length < 4) {
					alert('아이디는 4자~15자를 입력해 주세요.');
					return;
				}
				var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
				if (!pattern.test($('#mem_id').val())) {
					alert('영문과 숫자만 사용이 가능합니다.');
					$('#id_status').val("");
					$('#mem_id').val("");
					$('#mem_id').focus();
					return;
				}
				$.ajax({
					type: "POST",
					url: "/ajax/ajax_checkid.php",
					dataType:"json",
					data: {id:$('#mem_id').val()},
					success: function(data) {
						console.log(data);
						if (data == "0") {
							alert("이미 가입되어있는 아이디 입니다.");
							$('#id_status').val("");
							$('#mem_id').val("");
							$('#mem_id').focus();
						} else {
							$("#checkdup").attr('style', 'background-color: skyblue;');
							$("#id_html").prop('hidden', false);
						}
					}
				});
			}

			function iam_item(memid, action, reg_name, reg_id){
				if(action == 'use'){
					console.log("use123123");
					$.ajax({
						type:"POST",
						url:"/iam/iam_item_mng.php",
						dataType:"json",
						data:{use:true, memid:memid, mem_type:'오토회원', reg_name:reg_name, reg_id:reg_id},
						success:function (data) {
							console.log(data);
							// alert("결제 되었습니다!");
						}
					})
				}
			}
        </script>
	</body>
</html>
