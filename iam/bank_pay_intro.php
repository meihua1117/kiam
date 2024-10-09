<?
	include_once "../lib/rlatjd_fun.php";
	include_once "../lib/check_access.php";
	extract($_REQUEST);
    $date_today = date("Y-m-d");
    $prev_month_ts = strtotime($date_today.' +7 day');
    $date_limit = date("Y-m-d", $prev_month_ts);
    
    $link = "https://".$HTTP_HOST.$_SERVER['REQUEST_URI'];
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
				width:100%;
			}
			.auto_iam {
				padding: 7px;
				font-size: 14px;
				background-color: #82c836;
				font-weight: 500;
				border-radius:10px;
				width:28%;
			}
			.auto_iam1 {
				padding: 7px;
				font-size: 15px;
				background-color: #82c836;
				font-weight: 500;
				width:28%;
			}
			.people_iam_show{
				font-size:18px;
				background-color:#82c836;
				font-weight:bold;
				color:white;
				border:none;
				width:100%;
				padding:10px 0px;
			}
			#checkdup {
				border: 1px solid #ddd;
				float:right;
				background-color: #82c836;
				color: white;
				width: 85px; 
				border-radius: 5px;
			}
			.mem_info{
				margin-bottom:5px;
				display:flex;
				float:left;
				height: 30px;
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
					margin-bottom:5px;
					display:flex;
					float:left;
					height: 30px;
				}
				#checkdup {
					border: 1px solid #ddd;
					float:right;
					background-color: #82c836;
					color: white;
					border-radius: 5px;
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
			}

			#ajax-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:9000;text-align:center;display:none;background-color: #fff;opacity: 0.8;}
			#ajax-loading img{position:absolute;top:50%;left:50%;width:120px;height:120px;margin:-60px 0 0 -60px;}

			#tutorial-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:150;text-align:center;display:none;background-color: grey;opacity: 0.7;}
		</style>
	</head>
	<body class="body-event" style="max-width:768px;width:100%;margin:0px auto">
		<div class="m_div">
			<div class="join">
				<div class="common-wrap"><!-- 컨텐츠 영역 시작 -->
					<div class="container" style="max-width: 970px !important;">
						<form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post">
							<input type="hidden" name="one_id"   value="" />
							<input type="hidden" name="mem_pass" value="" />
							<input type="hidden" name="mem_code" value="" />
						</form>
						<div class="row">
							<div class="col-12">
								<div>
									<div style="margin-top:20px">
										<h1 id="msg_title" style="text-align:left;margin-left:30px;font-weight: 500;"><letter style="color:red">입금계좌번호</letter> 안내드립니다.</h1>
									</div>
									<div style="">
										<h3>굿마켓 상품구매를 해주셔서 감사합니다.<br>입금 기한내에 입급해주셔야 결제가 완료 됩니다.</h3>
									</div>
									<div style="text-align:left;border-bottom: 3px solid;font-weight: 500;">
										<p style="font-size: 17px;">결제정보</p>
									</div>
									<div style="margin-top:20px;margin-left:20px;">
										<div class="mem_info" style="width:100%;border-bottom: 1px solid lightgrey;">
											<p style="margin-right:5px;font-size:15px;font-weight:700;width: 150px;padding-top: 5px;">결제금액</p>
											<p style="font-size:20px;color:blue;"><?=number_format($price)?> <letter style="font-size:12px;">원</letter></p>
										</div>
										<div class="mem_info" style="width:100%; margin-top:15px;">
											<p style="margin-right:5px;font-size:15px;font-weight:700;width: 150px;padding-top: 5px;">상품명</p>
                                            <p style="font-size: 13px;padding-top: 5px;"><?=$item_name?></p>
										</div>
										<div class="mem_info" style="width:100%;">
											<p style="margin-right:5px;font-size:15px;font-weight:700;width: 150px;padding-top: 5px;">입금기한</p>
                                            <p style="font-size: 13px;padding-top: 5px;"><?=$date_limit?></p>
										</div>
                                        <div class="mem_info" style="width:100%;">
											<p style="margin-right:5px;font-size:15px;font-weight:700;width: 150px;padding-top: 5px;">결제수단</p>
                                            <p style="font-size: 13px;padding-top: 5px;">무통장 계좌</p>
										</div>
                                        <div class="mem_info" style="width:100%;border-bottom: 1px solid lightgrey;">
											<p style="margin-right:5px;font-size:15px;font-weight:700;width: 150px;padding-top: 5px;">계좌정보</p>
                                            <p style="font-size: 13px;padding-top: 5px;">SC제일 354-20-403048  온리원셀링</p>
										</div>
                                        <div class="mem_info" style="width:100%; margin-top:15px;margin-bottom: 15px;">
											<p style="margin-right:5px;font-size:15px;font-weight:700;width: 150px;padding-top: 5px;">구매회사명</p>
                                            <p style="font-size: 13px;padding-top: 5px;">온리원셀링 굿마켓</p>
										</div>

                                        <div class="" style="width:100%; margin-top:15px;">
											<li style="list-style: disc;text-align:left;padding-top:10px;">본 정보는 전자상거래상의 결제내역 확인을 위해 제공되는 정보로 결제신청후에 휴대폰 문자로 발송됩니다.</li>
                                            <li style="list-style: disc;text-align:left;padding-top:10px;">본 정보를 임의로 위,변조하여 사용할 경우 형사처벌의 대상이 될수 있습니다.</li>
                                            <li style="list-style: disc;text-align:left;padding-top:10px;">문의 사항이 있을 경우 아래 연락처를 통해 연락바랍니다.</li>
                                            <p style="margin-top:20px;">사업자번호 : 119-86—03213 | 대표자 : 송조은 | 전화 :  031-764-1883 |이메일 : 1pagebook@naver.com</p>
										</div>
									</div>
									<div class="container" style="margin-top:35px;border:1px solid #ddd;display:flex">
										<div style="width:50%">
											<button class="people_iam_show" style="background:white;color:black" onclick="copy_link('<?=$link?>');">복사하기</button>
										</div>
										<div style="width:50%">
											<button class="people_iam_show" id="reg_automem" style="" onclick="makingiam('make')">확인</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="tutorial-loading"></div>
		<div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>
		<script>
			function copy_link(url){
                var aux1 = document.createElement("input");
                // 지정된 요소의 값을 할당 한다.
                aux1.setAttribute("value", url);
                // bdy에 추가한다.
                document.body.appendChild(aux1);
                // 지정된 내용을 강조한다.
                aux1.select();
                // 텍스트를 카피 하는 변수를 생성
                document.execCommand("copy");
                // body 로 부터 다시 반환 한다.
                document.body.removeChild(aux1);
                alert("위 내용이 복사되었으니 붙여넣기로 이용하세요.");
                location.href="gwc_order_list.php";
            }
            
			function checkMobile() {
				var userAgent = navigator.userAgent || navigator.vendor || window.opera;

				// Windows Phone must come first because its UA also contains "Android"
				if (/windows phone/i.test(userAgent)) {
					return true;

				}

				if (/android/i.test(userAgent)) {
					if (/chrome/i.test(userAgent)) {
						return false;
					}
					return true;
				}
				// iOS detection from: http://stackoverflow.com/a/9039885/177710
				if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
					return true;
				}
				return false;
			}

			function makingiam(){
                alert('해당상품이 주문되었으니 무통장 결제로 입금 하시면 결제완료 처리가 됩니다.');
				location.href="gwc_order_list.php";
			}

			function gotoLogin() {
				var link = $("#shorturl").val();
				console.log($('input[name=one_id]').val());
				$.ajax({
					type:"POST",
					url:"/admin/ajax/login_iamuser.php",
					data:$('form[name=login_form]').serialize(),
					success:function(){
						location.href = "/?"+link+"&tutorial=Y";
					},
					error: function(){
						alert('초기화 실패');
					}
				});
				return false;
			}
        </script>
	</body>
</html>