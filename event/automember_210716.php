<?
	include_once "../lib/rlatjd_fun.php";
	extract($_REQUEST);
	if($_GET['event_idx']) $event_idx = $_GET['event_idx'];
	if($_GET['pcode']) $pcode = $_GET['pcode'];

	$sql_recom = "select m_id, event_info from Gn_event where pcode='{$pcode}'";
	$res = mysql_query($sql_recom);
	$row = mysql_fetch_array($res);
	$recom_id = $row['m_id'];
	$card_short_url = $row['event_info'];

	$sql_site = "select site, site_iam from Gn_Member where mem_id='{$recom_id}'";
	$res_site = mysql_query($sql_site);
	$row_site = mysql_fetch_array($res_site);
	$site = $row_site['site'];
	$site_iam = $row_site['site_iam'];

	$sql="update Gn_event set read_cnt = read_cnt+1 where pcode='$pcode'";
	mysql_query($sql) or die(mysql_error());
	
	$sql="select * from Gn_event where pcode='$pcode'";
	$result = mysql_query($sql) or die(mysql_error());
	$event_data = $row=mysql_fetch_array($result);
	$m_id = $row['m_id'];
	$event_idx = $row['event_idx'];		
?>
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
		<script language="javascript">
		
		</script>
		<style>
			.m_div {
				width:70%;
			}
			.auto_iam {
				padding: 5px;
				font-size: 22px;
				background-color: #82aae0;
				font-weight: 500;
			}
			.people_iam_show{
				font-size:22px;
				background-color:#e287c4;
				font-weight:500;
				color:white;
			}
			#checkdup {
				border: 1px solid black;
				float:right;
				margin-right:45%;
			}
			.mem_info{
				margin-right:35%;
				margin-bottom:5px;
				display:flex;
				float:right;
			}

			@media only screen and (max-width: 420px) {
				.m_div {
					width:100%;
				}
				.mem_info{
					margin-right:10%;
					margin-bottom:5px;
					display:flex;
					float:right;
				}
				#checkdup {
					border: 1px solid black;
					float:right;
					margin-right:33%;
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
											<h2 id="msg_title" style="text-align:center;"><?=$event_data['event_title']?></h2>
											<p style="font-size:15px;text-align:center;"><?=$event_data['event_desc']?></p>
											<div class="container" style="margin-top: 20px;text-align: center;width: 100%;">
												<a class="auto_iam settlement_btn" href="http://obmms.net/iam/index_sample.php">추천IAM보기</a>
												<a class="auto_iam settlement_btn" href="<?=$event_data['event_sms_desc']?>" style="margin-left:25px;">도장IAM보기</a>
											</div>
											<div class="container" style="margin-top: 20px;width: 100%;height:105px;">
												<div class="mem_info">
													<p style="margin-right:5px;font-size:18px;">이름  </p>
													<input type="text" style="font-size:18px;" id="mem_name">
												</div>
												<div class="mem_info">
													<p style="margin-right:5px;font-size:18px;">휴대폰</p>
													<input type="text" style="font-size:18px;" id="mem_phone">
												</div>
												<div class="mem_info">
													<p style="margin-right:5px;font-size:18px;">아이디</p>
													<input type="text" style="font-size:18px;" id="mem_id">
												</div>
												
											</div>
											<input type="button" id="checkdup" value="아이디중복확인" style="" onclick="id_check1()">
											<h3 class="title" id = "agreement-field" style="margin-top: 35px;">
												2. 약관 동의하기<span style="color:red">[필수]</span>
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
											<button class="people_iam_show" style="margin-left:42%;width:115px;border: none;margin-top:20px;" onclick="makingiam('make')">신청하기</button>
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
					console.log('making');
					mem_name = $("#mem_name").val();
					mem_phone = $("#mem_phone").val();
					mem_id = $("#mem_id").val();
					$.ajax({
						type:'POST',
						dataType:'json',
						url:'/ajax/auto_join.php',
						data:{mem_name:mem_name, mem_phone:mem_phone, mem_id:mem_id, site:site, site_iam:site_iam, recom_id:recom_id, card_short_url:card_short_url},
						success:function(data){
							console.log(data);
							if(data == 1){
								iam_item(recom_id, 'use');
								alert('회원가입이 완료되었습니다.');
								location.reload();
							}
						}
					});
				}
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
						}
					}
				});
			}

			function iam_item(memid, action, type){
				if(action == 'use'){
					console.log("use123123");
					$.ajax({
						type:"POST",
						url:"/iam/iam_item_mng.php",
						dataType:"json",
						data:{use:true, memid:memid, mem_type:'오토회원'},
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
