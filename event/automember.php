<?
include_once "../lib/rlatjd_fun.php";
include_once "../lib/check_access.php";
extract($_REQUEST);
if ($_GET['eventidx']) $event_idx = $_GET['eventidx'];
if ($_GET['pcode']) $pcode = $_GET['pcode'];

$sql = "update Gn_event set read_cnt = read_cnt+1 where (pcode='$pcode' or event_idx='$event_idx')";
mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

$sql_recom = "select * from Gn_event where (pcode='$pcode' or event_idx='$event_idx')";
$res = mysqli_query($self_con, $sql_recom);
$event_data = mysqli_fetch_array($res);

if ($_GET['recommend_id'])
	$recom_id = $_GET['recommend_id'];
else
	$recom_id = $event_data['m_id'];
$button_txt = $event_data['event_type'];
$card_idx = $event_data['event_info'];

$sql_auto_point = "select * from Gn_Search_Key where key_id='auto_member_join'";
$res_auto = mysqli_query($self_con, $sql_auto_point);
$row_auto = mysqli_fetch_array($res_auto);
$point_auto = $row_auto['key_content'];

$card_url = "";
if (strpos($card_idx, ",") !== false) {
	$card_idx = explode(",", $card_idx);
	for ($i = 0; $i < count($card_idx); $i++) {
		$idx = $card_idx[$i] * 1 - 1;
		$sql_card_url = "select * from Gn_Iam_Name_Card where mem_id='{$recom_id}' order by req_data asc limit " . $idx . ", 1;";
		$res_url = mysqli_query($self_con, $sql_card_url);
		$row_url = mysqli_fetch_array($res_url);
		if ($i == count($card_idx) - 1)
			$card_url .= $row_url['card_short_url'];
		else
			$card_url .= $row_url['card_short_url'] . ",";
	}
} else if($card_idx != ""){
	$idx = $card_idx * 1 - 1;
	$sql_card_url = "select * from Gn_Iam_Name_Card where mem_id='{$recom_id}' order by req_data asc limit " . $idx . ", 1;";
	$res_url = mysqli_query($self_con, $sql_card_url);
	$row_url = mysqli_fetch_array($res_url);
	$card_url .= $row_url['card_short_url'];
}

$sql_site = "select site, site_iam from Gn_Member where mem_id='{$recom_id}'";
$res_site = mysqli_query($self_con, $sql_site);
$row_site = mysqli_fetch_array($res_site);
$site = $row_site['site'];
$site_iam = $row_site['site_iam'];

$sql_exp = "select service_type from Gn_Iam_Service where sub_domain = 'http://" . $HTTP_HOST . "'";
$res_exp = mysqli_query($self_con, $sql_exp);
$row_exp = mysqli_fetch_array($res_exp);
$exp_mem = $row_exp['service_type'];
// echo $exp_mem;
if ($exp_mem == 3) {
	$exp_mem = 'true';
} else {
	$exp_mem = 'false';
}

$m_id = $event_data['m_id'];
if ($event_idx == "")
	$event_idx = $event_data['event_idx'];
$page_title = $event_data['event_title'];
$main_img1 = $event_data['object'];

$cur_point = 0;
// $sql_cur_point = "select current_point from Gn_Item_Pay_Result where buyer_id='{$m_id}' order by pay_date desc limit 1";
$sql_cur_point = "select mem_point from Gn_Member where mem_id='{$m_id}'";
$res_point = mysqli_query($self_con, $sql_cur_point);
$row_point = mysqli_fetch_array($res_point);
$cur_point = $row_point['mem_point'];

if ($HTTP_HOST != "kiam.kr") //분양사사이트이면
	$query = "select * from Gn_Iam_Service where sub_domain like 'http://" . $HTTP_HOST . "'";
else
	$query = "select * from Gn_Iam_Service where sub_domain like 'http://www.kiam.kr'";
$res = mysqli_query($self_con, $query);
$domainData = mysqli_fetch_array($res);
$first_card_idx = $domainData['profile_idx']; //분양사의 1번 카드아이디
$sql = "select * from Gn_Iam_Name_Card where idx = '$first_card_idx'";
$result = mysqli_query($self_con, $sql);
$main_card_row = mysqli_fetch_array($result);

if ($main_img1 == "")
	$main_img1 = $main_card_row['main_img1'];
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

	<script src="/iam/js/jquery-3.1.1.min.js"></script>
	<script language="javascript" type="text/javascript" src="common.js"></script>
	<script language="javascript" type="text/javascript" src="jquery.rotate.js"></script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src='https://www.googletagmanager.com/gtag/js?id=G-3E40Q09QGE'></script>
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
			font-size: 14px;
			background-color: #99cc00;
			font-weight: 500;
			border-radius: 10px;
			width: 28%;
		}

		.auto_iam1 {
			padding: 7px;
			font-size: 15px;
			background-color: #99cc00;
			font-weight: 500;
			width: 28%;
		}

		.people_iam_show {
			font-size: 18px;
			background-color: #99cc00;
			font-weight: bold;
			color: white;
			border: none;
			width: 100%;
			padding: 10px 0px;
		}

		#checkdup {
			border: 1px solid #ddd;
			float: right;
			background-color: #99cc00;
			color: white;
			width: 85px;
			border-radius: 5px;
		}

		.mem_info {
			margin-bottom: 5px;
			display: flex;
			float: left;
			height: 30px;
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
				margin-bottom: 5px;
				display: flex;
				float: left;
				height: 30px;
			}

			#checkdup {
				border: 1px solid #ddd;
				float: right;
				background-color: #99cc00;
				color: white;
				border-radius: 5px;
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

		.tooltiptext-bottom {
			width: 57%;
			font-size: 15px;
			background-color: white;
			color: black;
			text-align: left;
			position: absolute;
			z-index: 200;
			top: 100px;
			left: 50%;
			transform: translate(-50%, 0px);
		}

		.title_app {
			font-weight: bold;
			margin-bottom: -15px;
			background-color: rgb(130, 199, 54);
			color: white;
			text-align: center;
			padding: 10px;
			font-size: 17px;
		}

		.desc_app {
			padding: 20px;
		}

		.button_app {
			padding: 6px;
			text-align: center;
		}

		.login_signup {
			color: white !important;
			width: 35%;
			background-color: #bbbbbb;
			display: inline-block;
			padding: 6px 12px;
			margin-bottom: 0;
			font-size: 14px;
			font-weight: 400;
			line-height: 1.42857143;
			text-align: center;
			white-space: nowrap;
			vertical-align: middle;
			-ms-touch-action: manipulation;
			touch-action: manipulation;
			cursor: pointer;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
			background-image: none;
			border: 1px solid transparent;
			border-radius: 4px;
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
</head>

<body class="body-event" style="max-width:768px;width:100%;margin:0px auto">
	<div class="m_div">
		<div class="join">
			<div class="common-wrap"><!-- 컨텐츠 영역 시작 -->
				<div class="container" style="max-width: 970px !important;">
					<form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post">
						<input type="hidden" name="one_id" value="" />
						<input type="hidden" name="mem_pass" value="" />
						<input type="hidden" name="mem_code" value="" />
					</form>
					<div class="row">
						<div class="col-12">
							<div>
								<div style="border-top:1px solid #ddd;margin-top:20px">
									<h2 id="msg_title" style="text-align:center;"><?= str_replace("\n", "<br>", $event_data['event_title']) ?></h2>
								</div>
								<div style="border-top:1px solid #ddd;padding-left:20px;padding-right:20px">
									<h3><?= str_replace("\n", "<br>", $event_data['event_desc']); ?></h3>
								</div>
								<? if ($event_data['object'] != "") { ?>
									<div style="border:1px solid #ddd;text-align:center">
										<img src="<?= $event_data['object'] ?>" style="width:100%;">
									</div>
								<? } ?>
								<div style="margin-top: 20px;text-align: center;display:flex;justify-content: space-evenly;">
									<input type="button" class="auto_iam" onclick="window.open('/?cur_win=best_sample')" style="color:white;" value="Best IAM 보기">
									<?
									if ($button_txt != "") {
									?>
										<input type="button" class="auto_iam" onclick="window.open('<?= $event_data['event_sms_desc'] ?>')" style="margin-left:25px;color:white;" value="<?= $button_txt ?>" target="_blank">
									<? } ?>
								</div>
								<div style="margin-top:20px;margin-left:20px;">
									<div class="mem_info" style="width:100%">
										<p style="margin-right:5px;font-size:15px;font-weight:700;width: 54px;padding-top: 5px;">이름</p>
										<input type="text" style="font-size:14px;width: 70%;" id="mem_name" placeholder="이름을 입력하세요">
									</div>
									<div class="mem_info" style="width:100%">
										<p style="margin-right:5px;font-size:15px;font-weight:700;width: 54px;padding-top: 5px;">아이디</p>
										<input type="text" id="mem_id" style="width:calc(70% - 85px);font-size:14px;" placeholder="소문자로 입력하세요">
										<input type="button" id="checkdup" value="ID 중복확인" data-check="N" onclick="id_check1()">
									</div>
									<div id="id_html" class="mem_info" style="width:100%;font-size:13px;display:none;padding-top:10px;margin-left:54px">
										<img src="/images/check.gif" style="height:18px"> 사용 가능하신 아이디입니다.
									</div>
									<div class="mem_info" style="width:100%">
										<p style="margin-right:5px;font-size:15px;font-weight:700;width: 54px;padding-top: 5px;">휴대폰</p>
										<input type="text" style="font-size:14px;width: 70%;" id="mem_phone" placeholder="-없이 번호를 입력하세요">
									</div>
									<div class="mem_info" style="width:100%">
										<p style="margin-right:5px;font-size:15px;font-weight:700;width: 54px;padding-top: 5px;">비번</p>
										<input type="text" style="font-size:14px;width: 70%;" id="mem_pwd" placeholder="" readonly>
									</div>
									<div class="mem_info" id="go_myiam" style="margin-top:5px;text-align: center;width: 100%;justify-content: center;display:none">
										<a class="auto_iam1" id="myiam_link" href="" target="_blank" style="color:white;" value="내 아이엠 가기">내 아이엠 가기</a>
									</div>
								</div>


								<!--div style="margin-top: 30px;border:1px solid #ddd">
										<div style="text-align:center">
											<h3 style="color:red"><img src="/iam/img/menu/icon_danger_sign.png"  style="height:18px">잠깐! 꼭 읽어주세요.</h3>
										</div>
										<div style="display:flex;padding:5px">
											<img src="/iam/img/menu/icon_check_box.png" style="height: 12px;"/>
											<p style="margin:0px;font-size:12px">신청과 동시에 가입과 내 명함이 자동으로 만들어집니다.</p>
										</div>
										<div style="display:flex;padding:5px">
											<img src="/iam/img/menu/icon_check_box.png" style="height: 12px;"/>
											<p style="margin:0px;font-size:12px">내 명함은 <새 카드 만들기>를 눌러서 추가합니다.</p>
										</div>
										<div style="display:flex;padding:5px">
											<img src="/iam/img/menu/icon_check_box.png" style="height: 12px;"/>
											<p style="margin:0px;font-size:12px">IAM 첫 로그인시 PM(비번)은 <span style="color:red">휴대폰 뒷번호4자리</span>입니다.<br>회원정보관리에서 비번 변경하세요.</p>
										</div>
									</div-->
								<div style="margin-top: 10px;">
									<p class="notice">서비스 이용을 위한 이용약관, 개인정보 처리방침 등 필수, 선택 등의 항목에 동의해주세요.</p>
								</div>
								<div style="display:flex;justify-content: space-between;border:1px solid #ddd;margin-top:10px">
									<div>
										<h3 class="title" id="agreement-field" style="margin:10px">약관 동의하기*</h3>
									</div>
									<div style="padding: 10px;">
										<input type="checkbox" class="check" id="checkAll_title">
										<label for="checkAll_title">모두 동의</label>
									</div>
								</div>
								<div class="agreement-wrap" style="display: none;border:1px solid #ddd;padding:5px" id="agreement-wrap">
									<div class="agreement-item is-checkall" style="margin-top:15px">
										<div class="check-wrap">
											<input type="checkbox" class="check" id="checkAll" required itemname='약관동의'>
											<label for="checkAll"> <strong>모두동의</strong> </label>
										</div>
									</div>
									<div class="agreement-item" style="margin-top:15px;display:flex;justify-content: space-between;">
										<div class="check-wrap" style="margin:5px 0px">
											<input type="checkbox" class="check checkAgree" id="checkPersonal">
											<label for="checkPersonal">개인정보수집동의</label>
										</div>
										<div style="border:1px solid #ddd;border-radius:10px;margin-right:15px;padding:5px">
											<a href="/m/privacy.php" target="_blank">전문보기</a>
										</div>
									</div>
									<div class="agreement-item" style="margin-top:10px;display:flex;justify-content: space-between;">
										<div class="check-wrap" style="margin:5px 0px">
											<input type="checkbox" class="check checkAgree" id="checkTerms">
											<label for="checkTerms">회원이용약관</label>
										</div>
										<div style="border:1px solid #ddd;border-radius:10px;margin-right:15px;padding:5px">
											<a href="/m/terms.php" target="_blank">전문보기</a>
										</div>
									</div>
									<div class="agreement-item" style="margin-top:15px;">
										<div class="check-wrap">
											<input type="checkbox" class="check checkAgree" id="checkReceive">
											<label for="checkReceive">메시지수신동의</label>
										</div>
										<div class="desc">
											<h4 style="margin:0px">① 메시지 종류 : 아이엠 프로필 및 솔루션의 기능개선 메시지정보, 앱체크정보, 회원관리정보, 공익정보, 유익정보, 회원프로필정보를 발송합니다.</h4>
											<h4 style="margin:0px">② 메시지 발송 방법 : 고객님이 설치한 문자앱을 통해 고객님 폰의 문자를 활용하여 고객님의 계정에서 볼수 있게 합니다.</h4>
										</div>
									</div>
									<div class="agreement-item" style="margin-top:15px;">
										<div class="check-wrap">
											<input type="checkbox" class="check checkAgree" id="checkThirdparty">
											<label for="checkThirdparty">개인정보 제3자 제공 동의</label>
										</div>
										<div class="desc">
											<h4 style="margin:0px">① 제공받는 자 : 본 서비스를 개발하는 온리원계열사, 본 서비스 제공을 지원하는 협업사, 상품을 제공하는 쇼핑몰 관계사, 기타 본서비스 제공에 필요한 기관</h4>
											<h4 style="margin:0px">② 개인정보 이용 목적 : 서비스 제공을 위한 고객정보의 활용, 서비스 정보의 제공, e프로필서비스의 공유, 회원간의 품앗이 정보공유 등</h4>
											<h4 style="margin:0px">③ 개인정보의 항목 : 개인정보 제공에 동의한 내용</h4>
											<h4 style="margin:0px">④ 보유 및 이용 기간 :본 서비스를 이용하는 기간</h4>
											<h4 style="margin:0px">⑤ 제공 동의에 거부시 본 서비스가 제공되지 않습니다.</h4>
										</div>
									</div>
								</div>
								<div class="container" style="margin-top:10px;border:1px solid #ddd;display:flex">
									<div style="width:50%">
										<button class="people_iam_show" style="background:white;color:black" onclick="window.close();">취소하기</button>
									</div>
									<div style="width:50%">
										<button class="people_iam_show" id="reg_automem" onclick="makingiam('<?= $join_type ?>')">신청하기</button>
										<button class="people_iam_show" id="event_reqlink" hidden onclick="go_eventlink('<?= $event_data['event_req_link'] ?>')">이벤트 신청하기</button>
										<input type="text" id="shorturl" hidden>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<span class="tooltiptext-bottom" id="app_install_modal" style="display:none">
		<p class="title_app">앱 설치하기 안내</p><br>
		<p class="desc_app">※ 앱을 설치하면 콜백문자, 대량문자, 포털디비수집, 365일 자동메시지발동, 자동예약메시지기능, IAM기능 등 통합적인 모든 기능을 사용할 수 있습니다.<br><br>
			※ 현재 앱설치는 안드로이드폰만 가능, IAM은 아이폰에서 [홈화면추가]로 이용 가능합니다.<br><br>
			※ IAM 앱설치가 완료되면 앱을 통해 문자를 수신하고, 이후에도 온리원 플랫폼에서 보내는 문자는 IAM앱을 통해서 수신됩니다.</p>
		<div class="button_app">
			<a href="javascript:void(0)" onclick="downloadApp()" class="btn login_signup" style="width: 40%;background-color: #ff0066">앱 설치하기</a>
			<a href="/ma.php" class="btn login_signup" style="width: 40%;background-color: #bbbbbb">나중에 하기</a>
		</div>
	</span>
	<div id="tutorial-loading"></div>
	<div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>
	<script>
		$('#agreement-field').on("click", function() {
			if ($('#agreement-wrap').css("display") == "none")
				$('#agreement-wrap').show();
			else
				$('#agreement-wrap').hide();
		});
		$('.checkAgree').on("change", function() {
			$('.checkAgree').each(function() {
				if ($(this).prop("checked") == false) {
					$('#checkAll').prop("checked", false);
					$('#checkAll_title').prop("checked", false);
				}
			});
		});

		$("#checkAll").on('click', function() {
			var check = $(this).prop('checked');
			$("#checkAll_title").prop('checked', check);
			$('.checkAgree').each(function() {
				$(this).prop('checked', check);
			});
		});

		$("#checkAll_title").on('click', function() {
			var check = $(this).prop('checked');
			$('#checkAll').prop("checked", check);
			$('.checkAgree').each(function() {
				$(this).prop('checked', check);
			});
		})
		$("#mem_phone").on('keyup', function() {
			var mem_phone = $(this).val();
			$("#mem_pwd").val(mem_phone.replace("-", "").substr(-4));
		});

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

		function checkMobile1() {
			var userAgent = navigator.userAgent || navigator.vendor || window.opera;
			// Windows Phone must come first because its UA also contains "Android"
			if (/windows phone/i.test(userAgent)) {
				return true;
			}
			if (/android/i.test(userAgent)) {
				return true;
			}
			// iOS detection from: http://stackoverflow.com/a/9039885/177710
			if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
				return true;
			}
			return false;
		}

		function downloadApp() {
			var uri = "https://play.google.com/store/apps/details?id=mms.onepagebook.com.onlyonesms";
			location.href = uri;
			/*var uri = "http://www.kiam.kr/data/onlyone-release.apk";
				if(checkMobile())
					uri = "intent://www.kiam.kr/data/onlyone-release.apk#Intent;scheme=http;package=com.android.chrome;S.browser_fallback_url=http%3A%2F%2Fwww.kiam.kr%2Fdata%2Fonlyone-release.apk;end;";
				var name = "app_link";
				var link = document.createElement("a");
				link.setAttribute('download', name);
				link.href = uri;
				document.body.appendChild(link);
				link.click();
				link.remove();
*/
		}

		function go_eventlink(val) {
			if (val == "") {
				alert("이미 가입되어있는 아이디 입니다. 새로운 아이디를 입력해주세요.");
			} else {
				location.href = val;
			}
		}

		function makingiam(join_type) {
			var recom_id = '<?= $recom_id ?>';
			var site = '<?= $site ?>';
			var site_iam = '<?= $site_iam ?>';
			var card_short_url = '<?= $card_url ?>';
			var exp_mem = '<?= $exp_mem ?>';
			if ($("#checkAll_title").prop('checked') == false) {
				alert('모든 약관내용에 동의하셔야 신청하실수 있습니다.');
				return;
			} else {
				if ($("#mem_name").val() == "") {
					alert('이름을 입력해주세요.');
					return;
				} else if ($("#mem_phone").val() == "") {
					alert('폰번호를 입력해주세요.');
					return;
				} else if ($("#mem_id").val() == "") {
					alert('아이디를 입력해주세요.');
					return;
				}
				style = $("#checkdup").attr('style');
				if ($("#checkdup").data("check") == "N") {
					alert("아이디 중복확인을 하세요.");
					return;
				}
				var mem_name = $("#mem_name").val();
				var mem_phone = $("#mem_phone").val();
				var mem_id = $("#mem_id").val();
				var mem_pwd = $("#mem_pwd").val();
				var event_id = '<?= $event_idx ?>';
				$.ajax({
					type: 'POST',
					dataType: 'json',
					url: '/ajax/auto_join.php',
					data: {
						mem_name: mem_name,
						mem_phone: mem_phone,
						mem_id: mem_id,
						mem_pwd: mem_pwd,
						site: site,
						site_iam: site_iam,
						recom_id: recom_id,
						card_short_url: card_short_url,
						event_id: <?= $event_idx ?>,
						exp_mem: exp_mem,
						join_type: join_type
					},
					success: function(data) {
						if (data.status == 1) {
							if (join_type == "iam")
								iam_item(recom_id, 'use', mem_name, mem_id);
							$('input[name=one_id]').val(data.mem_id);
							$('input[name=mem_pass]').val(data.mem_pass);
							$('input[name=mem_code]').val(data.mem_code);
							$("#shorturl").val(data.short_url + data.mem_code);
							if (event_id == '5169' && checkMobile() && join_type == "iam") {
								AppScript.goAppLogin(mem_id, mem_pwd);
							} else {
								gotoLogin(join_type);
							}
						}
					},
					error: function(request, status, error) {
						alert(request + "=>" + status + "[" + error + "]");
					}
				});
			}
		}

		function gotoLogin(join_type) {
			var link = $("#shorturl").val();
			console.log($('input[name=one_id]').val());
			$.ajax({
				type: "POST",
				url: "/admin/ajax/login_iamuser.php",
				data: $('form[name=login_form]').serialize(),
				success: function() {
					if (join_type == "iam") {
						location.href = "/?" + link + "&tutorial=Y";
					} else {
						alert('회원가입 되었습니다.');
						var userAgent = navigator.userAgent.toLowerCase();
            			if (userAgent.match(/onlyone/)) {
							signin($('input[name=one_id]').val(),$('input[name=mem_pass]').val());
						}else{
							$("#app_install_modal").show();
						}
					}
				},
				error: function(request,status,error) {
					console.log('code: ' + request.status + "\n" + 'message: ' + request.responseText + "\n" + 'error: ' + error);
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
			//if(!checkMobile1()){
			var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
			if (!pattern.test($('#mem_id').val())) {
				alert('영문 소문자와 숫자만 사용이 가능합니다.');
				$('#id_status').val("");
				$('#mem_id').val("");
				$('#mem_id').focus();
				return;
			}
			//}
			$.ajax({
				type: "POST",
				url: "/ajax/ajax_checkid.php",
				dataType: "json",
				data: {
					id: $('#mem_id').val()
				},
				success: function(data) {
					console.log(data);
					$("#checkdup").data('check', 'Y');
					if (data.result == "0") {
						alert("이미 가입되어있는 아이디 입니다. 새로운 아이디를 입력해 가입신청 하시거나, 이벤트 신청을 하시려면 하단 이벤트신청하기 탭을 클릭해주세요.");
						$("#reg_automem").hide();
						$("#event_reqlink").show();
						$("#id_html").hide();
						$("#checkdup").attr('style', '');
						$("#myiam_link").attr("href", data.iam_link);
						$("#go_myiam").show();
					} else {
						$("#checkdup").attr('style', 'background-color: #555;');
						$("#id_html").show();
						$("#reg_automem").show();
						$("#event_reqlink").hide();
					}
				}
			});
		}

		function iam_item(memid, action, reg_name, reg_id) {
			if (action == 'use') {
				$.ajax({
					type: "POST",
					url: "/iam/iam_item_mng.php",
					dataType: "json",
					data: {
						use: true,
						memid: memid,
						mem_type: '오토회원',
						reg_name: reg_name,
						reg_id: reg_id
					},
					success: function(data) {
						console.log(data);
						// alert("결제 되었습니다!");
					}
				})
			}
		}
	</script>
</body>

</html>