<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
$contents_idx = $_GET['contents_idx'];
$HTTP_HOST = str_replace("www.", "", $_SERVER['HTTP_HOST']);
if ($HTTP_HOST != "kiam.kr") { //분양사사이트이면
	$query = "select * from Gn_Iam_Service where sub_domain like '%http://" . $HTTP_HOST . "'";
	$res = mysqli_query($self_con,$query);
	$domainData = mysqli_fetch_array($res);
} else {
	$query = "select * from Gn_Iam_Service where sub_domain like 'http://www.kiam.kr'";
	$res = mysqli_query($self_con,$query);
	$domainData = mysqli_fetch_array($res);
	$domainData['sub_domain'] = "http://kiam.kr/";
}

$meta_sql = "update Gn_Iam_Contents set contents_temp=contents_temp+1 where idx = '$contents_idx'";
mysqli_query($self_con,$meta_sql);

$meta_sql = "select * from Gn_Iam_Contents where idx = '$contents_idx'";
$meta_result = mysqli_query($self_con,$meta_sql);
$meta_row = mysqli_fetch_assoc($meta_result);

$sql = "select idx,mem_id,main_img1,card_name from Gn_Iam_Name_Card where idx = {$meta_row['card_idx']}";
$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$name_card = mysqli_fetch_array($result);

$card_owner = $name_card['mem_id'];
$_SESSION['recommender_code'] = $card_owner;
@setcookie("recommender_code", $card_owner, time() + 3600);
$_COOKIE['recommender_code'] = $card_owner;

//콘텐츠에 현시할 이름과 아바타

$contents_avatar = $name_card['main_img1'];
$mem_sql = "select profile,mem_name from Gn_Member where mem_id='{$card_owner}'";
$mem_res = mysqli_query($self_con,$mem_sql);
$mem_row = mysqli_fetch_assoc($mem_res);
$contents_user_name = $mem_row['mem_name'];
if ($contents_avatar == "") {
	$contents_avatar = $mem_row['profile'];
}
if (strstr($contents_avatar, "kiam")) {
	$contents_avatar = str_replace("http://kiam.kr", "", $contents_avatar);
	$contents_avatar = str_replace("http://www.kiam.kr", "", $contents_avatar);
	//$contents_avatar = $cdn_ssl . $contents_avatar;
}
if (!strstr($contents_avatar, "http") && $contents_avatar) {
	$contents_avatar = $cdn_ssl . $contents_avatar;
}
$contents_avatar = cross_image($contents_avatar);
if (!$meta_row['contents_img'])
	$content_images = null;
else
	$content_images = explode(",", $meta_row['contents_img']);
for ($i = 0; $i < count($content_images); $i++) {
	if (strstr($content_images[$i], "kiam") && !strstr($content_images[$i], $cdn)) {
		$content_images[$i] = str_replace("http://kiam.kr", "", $content_images[$i]);
		$content_images[$i] = str_replace("http://www.kiam.kr", "", $content_images[$i]);
		//$content_images[$i] = $cdn_ssl . $content_images[$i];
	}
	if (!strstr($content_images[$i], "http") && $content_images[$i]) {
		$content_images[$i] = $cdn_ssl . $content_images[$i];
	}
}
$meta_title = $meta_row['contents_title'];
$cur_card_short_url = $meta_row['westory_card_url'];
$meta_desc = $meta_row['contents_desc'];
$gallery_price = $meta_row['contents_price'];
$gallery_sell_price = $meta_row['contents_sell_price'];
$gallery_info = explode("|", $gallery_price);
$gallery_price = $gallery_info[0];
$gallery_format = $gallery_info[1];
$gallery_size = $gallery_info[2];
$gallery_download_price = $gallery_info[3];
$discount = $meta_row['reduce_val'] * 1;
$add_point = $gallery_price * ((int)$discount / 100);
?>
<style>
	.content-utils {
		background: #797979;
		border-radius: 50%;
		width: 22px;
		height: 22px;
		overflow: hidden;
		font-size: 15px;
		cursor: pointer;
		position: absolute;
		top: 190px;
		right: 10px;
		z-index: 10;
	}
</style>
<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<!--오픈그래프 (웹페이지 미리보기 -페이스북, 카카오톡)-->
	<meta property="og:title" content="<?= $meta_title ?>"> <!--제목-->
	<meta property="og:description" content="<?= $meta_desc ?>"> <!--내용-->
	<meta property="og:image" content="<?= $content_images[0] ?>"> <!--이미지-->
	<!--오픈그래프 끝-->
	<title>아이엠 하나이면 홍보와 소통이 가능하다</title>
	<link rel="shortcut icon" href="img/common/iconiam.ico">
	<link rel="stylesheet" href="css/notokr.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/new_style.css">
	<link rel="stylesheet" href="css/grid.min.css">
	<link rel="stylesheet" href="css/slick.min.css">
	<link rel="stylesheet" href="css/style_j.css">
	<link rel="stylesheet" href="/iam/css/iam.css">
	<? if (!$global_is_local) { ?>
		<script src="//developers.kakao.com/sdk/js/kakao.min.js" charset="utf-8"></script>
	<? } ?>
	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="js/slick.min.js"></script>
	<script src="js/main.js"></script>
	<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
	<link rel='stylesheet' href='../plugin/toastr/css/toastr.css' />
	<script src='../plugin/toastr/js/toastr.min.js'></script>
	<script src="/js/rlatjd_fun.js"></script>
</head>

<body>
	<input type="hidden" id="mem_id" value="<?= $_SESSION['iam_member_id'] ?>">
	<div id="wrap" class="common-wrap">
		<main id="star" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
			<section id="bottom" style="border:none">
				<input type="hidden" id="contents_user_name" value="<?= $contents_user_name ?>">
				<input type="hidden" id="contents_title" value="<?= $meta_row['contents_title'] ?>">
				<input type="hidden" id="contents_desc" value="<?= $meta_row['contents_desc'] ?>">
				<input type="hidden" id="contents_img" value="<?= $meta_row['contents_img'] ?>">
				<input type="hidden" id="origin_price" value="<?= $gallery_price ?>">
				<input type="hidden" id="download_price" value="<?= $gallery_download_price ?>">
				<input type="hidden" id="discount" value="<?= $discount ?>">
				<input type="hidden" id="cont_idx" value="<?= $contents_idx ?>">
				<input type="hidden" id="seller_id" value="<?= $card_owner ?>">
				<input type="hidden" id="gallery_format" value="<?= $gallery_format ?>">
				<input type="hidden" id="gallery_size" value="<?= $gallery_size ?>">
				<div class="content-item" style="margin-top:0px;box-shadow:none" id="contents<?= $meta_row['idx'] ?>">
					<div class="media-wrap" style="border: none;">
						<div class="media-inner">
							<div class="desc-wrap">
								<? if ($meta_row['contents_title'] != "") { ?>
									<div class="title">
										<div class="text"><?= $meta_row['contents_title'] ?></div>
									</div>
								<? } ?>
								<div class="desc is-product">
									<div class="desc-inner">
										<div style="text-align:left;">
											<?

											?>
											<div class="price">
												<span style="color:black;font-size: 18px;font-weight: bold;margin-right: 3px;">판매가 : 원본 <?= number_format($gallery_price) ?>원 </span>
												<span style="color:black;font-size: 18px;font-weight: bold;margin-right: 3px;">/ 다운로드 <?= number_format($gallery_download_price) ?>원</span>
											</div>
											<div class="price" style="margin-top:5px;display: inline-flex;">
												<span style="color: red;font-size: 18px;font-weight: bold;margin-right: 3px;">적립금 : <?= number_format($add_point) ?>원 </span>
												<span style="color: red;font-size: 18px;font-weight: bold;margin-right: 3px;">(<?= (int)$discount ?>%)</span>
											</div>
										</div>
									</div>
								</div>
								<? if ($meta_row['contents_desc']) { ?>
									<div class="desc">
										<? if ($content_images == null) { ?>
											<a href="javascript:showSNSModal('<?= $meta_row['idx'] ?>');" class="content-utils">
												<img src="/iam/img/menu/icon_share_white.png" style="width:13px;height:16px;margin-left:2px;margin-top:3px;">
											</a>
											<a href="javascript:close_win();" class="content-utils" style="top:15px;right: 15px;background-color:white;font-weight:bold;"><img src="/iam/img/menu/icon_x.PNG" style="width:22px;height:22px;"></a>
										<? } ?>
										<div class="desc-inner desc-text desc-inner-content" style="font-size:15px;">
											<?= nl2br($meta_row['contents_desc']) ?>
										</div>
										<!--a href="#" class="arrow" style="color:#669933;font-weight:bold;cursor:pointer">
											[더 보기]
										</a-->
									</div>
								<? } ?>
							</div>
							<div class="user-item">
								<a href="/?<?= strip_tags($meta_row['westory_card_url']) ?>" class="img-box">
									<div class="user-img">
										<img src="<?= $contents_avatar ?>" alt="">
									</div>
								</a>
								<div class="wrap">
									<a href="/?<?= strip_tags($meta_row['westory_card_url']) ?>" class="user-name">
										<?= $contents_user_name ?>
									</a>
								</div>
							</div>
							<? if ($content_images != null) { ?>
								<a href="javascript:showSNSModal('<?= $meta_row['idx'] ?>');" class="content-utils">
									<img src="/iam/img/menu/icon_share_white.png" style="width:13px;height:16px;margin-left:-1px;margin-top:3px;">
								</a>
								<a href="javascript:close_win();" class="content-utils" style="top:15px;right: 15px;background-color:white;font-weight:bold;"><img src="/iam/img/menu/icon_x.PNG" style="width:22px;height:22px;"></a>
							<? } ?>
							<img src="<?= cross_image($content_images[0]) ?>" class="contents_img">
							<? if (count($content_images) > 1) { ?>
								<button onclick="show_all_content_images('<?= $meta_row['idx'] ?>')" id="content_all_image<?= $meta_row['idx'] ?>" <?= $gwc ? 'hidden' : '' ?> style="position: absolute;right:0px;bottom:0px;font-size: 14px;opacity: 60%;background: black;color: white;"><?= "+" . (count($content_images) - 1) ?></button>
								<button onclick="hide_all_content_images('<?= $meta_row['idx'] ?>')" id="hide_content_all_image<?= $meta_row['idx'] ?>" style="position: absolute;left:0px;top:300px;font-size: 14px;display:none;background: transparent">
									<img src="img/main/icon-img_fold.png" style="width:30px">
								</button>
								<? for ($c = 1; $c < count($content_images); $c++) { ?>
									<img src="<?= cross_image($content_images[$c]) ?>" class="contents_img hidden_image<?= $meta_row['idx'] ?>">
								<? } ?>
							<? } ?>
						</div>
					</div>
					<div class="info-wrap" style="margin-top: 50px;border:none">
						<? if ($meta_row['contents_url'] && strpos($meta_row['contents_url'], "<iframe") === false) {
							if (strstr($meta_row['contents_url'], "youtube.com"))
								$video_type = "youtube.com";
							else if (strstr($meta_row['contents_url'], "blog.naver.com"))
								$video_type = "blog.naver.com";
							else if (strstr($meta_row['contents_url'], "cafe.naver.com"))
								$video_type = "cafe.naver.com";
							else if (strstr($meta_row['contents_url'], "jungto.org"))
								$video_type = "jungto.org";
							else
								$video_type = $meta_row['contents_url']; ?>
						<? } ?>

						<div style="padding:0px;position: fixed;width: 100%;height: 50px;z-index: 100;bottom: 0px;max-width: 768px;background: white;border-top: 1px solid #ddd;">
							<div class="in-box">
								<a class="gwc_order_btn1" href="javascript:show_order_option('<?= $meta_row['idx'] ?>', 'cart')" style="text-align:center;font-size: 18px;padding: 10px 10px;border-bottom-left-radius: 10px;border: 1px solid #ddd;width:50%;">장바구니</a>
								<a class="gwc_order_btn2" href="javascript:show_order_option('<?= $meta_row['idx'] ?>', 'pay')" style="text-align:center;font-size: 18px;padding: 10px 10px;border-bottom-right-radius: 10px;border: 1px solid #ff3b30;width:50%;color: white;background-color: #ff3b30;">구매하기</a>
							</div>
						</div>
					</div>
				</div>
			</section>
		</main><!-- // 컨텐츠 영역 끝 -->
	</div>
	<div id="show_gwc_order_option" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
		<div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:570px">
			<div class="modal-content">
				<div>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
					</button>
				</div>
				<div class="modal-title">
					<label><?= $meta_row['contents_title'] ?></label>
					<input type="hidden" id="buy_type">
				</div>
				<div class="modal-body popup-bottom">
					<div class="container" id="gwc_price_option" style="text-align: left;margin-bottom: 25px;display:inline-flex;">
						<img src="<?= cross_image($content_images[0]) ?>" id="gwc_con_img_modal" style="width: 30%;height:30%">
						<div style="margin-left: 20px;width:100%">
							<div style="margin-top: 10px;">
								<div style="display: flex;justify-content: space-between;">
									<div>
										<input type="radio" name="buy_type" value="download" checked>
										<label for="download" style="margin: 15px 10px;">다운로드 구매</label>
									</div>
									<label style="margin: 15px 10px;" id="gallery_download_price"><?= number_format($gallery_download_price) ?>원</label>
								</div>
								<div style="display: flex;justify-content: space-between;">
									<div>
										<input type="radio" name="buy_type" value="origin">
										<label for="origin" style="margin: 15px 10px;">원본 구매</label>
									</div>
									<div>
										<label style="margin: 15px 0px;color:red"><?= $discount ?> %</label>
										<label style="margin: 15px 0px 15px 10px;" id="gallery_sell_price"><?= number_format($gallery_sell_price) ?>원</label>
										<label style="margin: 15px 0px 15px 5px;text-decoration: line-through;color:grey;font-size:10px" id="gallery_ori_price"><?= number_format($gallery_price) ?>원 </label>
									</div>
								</div>
								<div style="text-align: center;">
									<label style="margin: 15px 10px;">규격 : <?= $gallery_format ?></label>
									<label style="margin: 15px 10px;">사이즈 : <?= $gallery_size ?></label>
								</div>
							</div>
							<div class="control_number">
								<label style="margin: 15px 10px;">수량</label>
								<button type="button" class="btn_small grey" onclick="change_count(0)" style="font-weight: bold;font-size: 15px;border-radius: 12px;padding: 0px 8px;margin-left: 20px;">-</button>
								<input type="text" name="conts_cnt" id="conts_cnt" value="1" title="수량설정" style="width:30px;padding:5px 7px;height:35px">
								<button type="button" class="btn_small grey" onclick="change_count(1)" style="font-weight: bold;font-size: 15px;border-radius: 12px;">+</button>
							</div>
							<div style="color:grey">
								<label>※원본 작품에는 보증서와 작품 싸인이 들어갑니다.<br>액자와 배송비 별도.</label>
								<label>※규격 변동울 원하시는 경우 별도 문의해주세요.</label>
							</div>
						</div>
					</div>
					<div class="container" id="gwc_order_option" style="text-align: left;">

					</div>
					<div class="gwc_order_option_number"></div>
				</div>
				<div class="modal-footer" style="text-align: center;display:flex;padding:0px;margin-top: 10px;">
					<button type="button" class="btn-default btn-left" onclick="cancel_order_modal()">취소</button>
					<button type="button" class="btn-active btn-right" onclick="save_order_option()">확인</button>
				</div>
			</div>
		</div>
	</div>
	<div id="sns-modalwindow" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-sm" role="document" style="margin-top: 200px;max-width:300px;margin-left:auto;margin-right:auto;">
			<div class="modal-content">
				<div class="modal-header" style="border:none;">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="img/icon_close_black.svg"></button>
				</div>
				<div class="modal-body">
					<div class="center_text">
						<div class="sns_item" onclick="daily_send_pop()">
							<div class="sns_icon_div"><img class="sns_icon" src="img/sns_icon1.png"></div>
							<div class="sns_item_text">데일리<br>문자발송</div>
						</div>
						<div class="sns_item" onclick="contents_sendSMS()">
							<div class="sns_icon_div"><img class="sns_icon" src="img/sns_icon2.png"></div>
							<div class="sns_item_text">문자<br>보내기</div>
						</div>
						<div class="sns_item" onclick="contents_shareKakaoTalk()">
							<div class="sns_icon_div"><img class="sns_icon" src="img/sns_icon3.png"></div>
							<div class="sns_item_text">카톡<br>공유</div>
						</div>
					</div>
					<div class="center_text">
						<div class="sns_item" onclick="sns_shareEmail()">
							<div class="sns_icon_div">
								<img class="sns_icon" src="img/sns_icon8.png">
							</div>
							<div class="sns_item_text">콜백<br>공유</div>
						</div>
						<div class="sns_item" onclick="contents_shareFaceBook()">
							<div class="sns_icon_div"><img class="sns_icon" src="img/sns_icon5.png"></div>
							<div class="sns_item_text">페북<br>공유</div>
						</div>
						<div class="sns_item" onclick="contents_copyContacts()">
							<div class="sns_icon_div"><img class="sns_icon" src="img/sns_icon4.png"></div>
							<div class="sns_item_text">주소<br>복사</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--데일리 발송 팝업-->
	<div id="popup" class="daily_popup">
		<!-- 팝업 시작 -->
		<div class="popup-wrap" id="dailysend">
			<div class="text-wrap">
				<h3>내 아이엠을 내 폰 지인 모두에게 보내기!</h3><br><br>
				새명함이 나오면 지인들께 보내고<br>
				싶은데 방법이 마땅치 않지요?<br><br>

				①데일리발송기능과 ②내 폰안의 무료 문자를<br>
				이용하여 내 폰주소록의 모든 지인에게<br>
				매일매일 자동으로 발송해보세요!<br>
				<br><br>
				<h3>내 아이엠을 보내는 절차!</h3><br><br>

				<a href="join.php">첫째 회원가입 먼저 해야지요(클릭)</a><br>
				<a href="https://play.google.com/store/apps/details?id=mms.onepagebook.com.onlyonesms&pli=1">둘째 내 폰의 문자를 보내려면 앱을
					설치해야지요!(클릭)</a><br>
				셋째 데일리발송을 시작해요!<br>
				※ 아이엠을 보내는 기능은 무료이지만 일반 메시지를 보내는 것은 유료입니다.</h3>
			</div>
			<div class="button-wrap">
				<? if ($_SESSION['iam_member_id']) {
					$iam_link = $domainData['sub_domain'] . "/?" . $cur_card_short_url;
				?>
					<a href="#" id="closePopup" class="buttons is-cancel">다음에보내기</a>
					<a id="daily_popup_content" href="#" target="_blank" class="buttons is-save">시작하기</a>
				<? } else { ?>
					<a href="#" id="closePopup" class="buttons is-cancel">다음에보내기</a>
					<a href="login.php" target="_blank" class="buttons is-save">시작하기</a>
				<? } ?>
			</div>
		</div>

		<div class="popup-overlay"></div>
	</div><!-- // 팝업 끝 -->
</body>
<script>
	var ft_ht = $(".info-wrap").height();
	var win_ht = $(window).height();
	var mobile = '<?= $_GET['mobile'] ?>';
	$(document).ready(function() {
		var media_ht = win_ht * 1 - ft_ht * 1 - 10;
		$('.media-inner').attr('style', 'max-height:' + media_ht + 'px');
	})

	function save_order_option() {
		var conts_cnt = $("#conts_cnt").val();
		var seller_id = $("#seller_id").val();
		var sell_type = $("input[name=buy_type]:checked").val();
		var conts_price = 0;
		var str = 'gallery>>';
		if (sell_type == "download") {
			conts_price = $("#download_price").val() * conts_cnt;
			str += "download";
		} else if (sell_type == "origin") {
			conts_price = $("#origin_price").val() * conts_cnt * (100 - $("#discount").val()) / 100;
			str += "origin>>" + $("#gallery_format").val() + ">>" + $("#gallery_size").val() + ">>" + $("#origin_price").val() + ">>" + $("#discount").val() + "%";
		}
		var salary = 0;
		var over_salary = 0;
		var cont_idx = $("#cont_idx").val();

		var cnt = 0;

		if ($("#buy_type").val() == 'pay') {
			location.href = "/iam/gwc_order_pay.php?contents_idx=" + cont_idx + "&contents_cnt=" + conts_cnt + "&contents_price=" + conts_price + "&contents_salary=" + salary + "&over_salary=" + over_salary + "&seller_id=" + seller_id + "&order_option=" + str + "&shop=gallery";
		} else {
			$.ajax({
				type: "POST",
				url: "/iam/ajax/manage_request_list.php",
				dataType: "json",
				data: {
					cont_idx: cont_idx,
					conts_cnt: conts_cnt,
					conts_price: conts_price,
					salary: salary,
					seller_id: seller_id,
					option: str,
					mode: 'save_order_cart',
					gwc_order_option_content: ""
				},
				success: function(data) {
					location.href = "/iam/gwc_order_cart.php?shop=gallery";
				}
			})
		}
	}

	function change_count(val) {
		var cur_cnt = parseInt($("#conts_cnt").val());
		var sell_type = $("input[name=buy_type]:checked").val();
		if (val == "0") {
			if (cur_cnt * 1 - 1 == 0) {
				alert('최소 구매수량은 1 이상 입니다.');
				return;
			} else {
				cur_cnt--;
			}
		} else {
			if (cur_cnt * 1 + 1 == 50) {
				alert('최대 구매수량은 50 이하 입니다.');
				return;
			} else {
				cur_cnt++;
			}
		}
		$("#conts_cnt").val(cur_cnt);
		if (sell_type == "download") {
			$("#gallery_download_price").html(number_format($("#download_price").val() * cur_cnt) + "원");
			$("#gallery_sell_price").html(number_format($("#origin_price").val() * (100 - $("#discount").val()) / 100) + "원");
			$("#gallery_ori_price").html(number_format($("#origin_price").val()) + "원");
		} else if (sell_type == "origin") {
			$("#gallery_download_price").html(number_format($("#download_price").val()) + "원");
			$("#gallery_sell_price").html(number_format($("#origin_price").val() * cur_cnt * (100 - $("#discount").val()) / 100) + "원");
			$("#gallery_ori_price").html(number_format($("#origin_price").val() * cur_cnt) + "원");
		}
	}

	function close_win() {
		if (mobile == "Y") {
			window.parent.document.getElementById('contents_page').style.display = "none";
			window.parent.document.getElementById("wrap").style.display = "flow-root";
		} else {
			window.close();
		}
	}

	function show_order_option(con_idx, type) {
		var mem_id = '<?= $_SESSION['iam_member_id'] ?>';
		option_array = [];
		if (mem_id == '') {
			location.href = "/iam/login.php";
			return;
		}
		$("#buy_type").val(type);
		$("#show_gwc_order_option").modal('show');
		return;
	}
	toastr.options = {
		// "progressBar": true,
		"timeOut": 3000
	}
	try {
		Kakao.init("<?php echo $domainData['kakao_api_key'] != "" ? $domainData['kakao_api_key'] : "c0550dad4e9fdb8a298f6a5ef39ebae6"; ?>"); // 사용할 앱의 JavaScript 키를 설정
		//Kakao.init("2e50869591823e28ed57afa55ff56b47");      // 사용할 앱의 JavaScript 키를 설정
	} catch (e) {
		console.log("Kakao 로딩 failed : " + e);
	}

	//데일리 발송 팝업
	function daily_send_pop() {
		$("#sns-modalwindow").modal("hide");
		iam_count('iam_msms');
		jQuery.fn.center = function() {
			this.css('position', 'absolute');
			this.css('width', '100%');
			this.css('top', Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window)
				.scrollTop()) + 'px');
			this.css('left', Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window)
				.scrollLeft()) + 'px');
			return this;
		}
		$(".daily_popup").center();
		$('.daily_popup').css('display', 'block');
		var iam_link = "daily_write_iam.php?msg_title=콘텐츠 제목을 가져와서 디폴트로 입력해주세요." + "&msg=아래 내용을 디폴트로 넣어주세요.멋진 콘텐츠를 소개합니다.  " +
			"<?= $domainData['sub_domain'] . "/iam/contents_gwc.php?gwc=Y&contents_idx =" . $contents_idx ?>";
		$("#daily_popup_content").attr("href", iam_link);
	}

	function daily_send_pop_close() {
		$('.daily_popup').css('display', 'none');
	}
	var j_idx;
	var j_name;
	var j_title;
	var j_desc;
	var j_img;

	function showSNSModal(idx) {
		$("#sns-modalwindow").modal("show");
		j_idx = idx;
		j_name = $("#contents_user_name").val();
		j_title = $("#contents_title").val();
		j_desc = $("#contents_desc").val();
		var content_img_array = [];
		content_img_array = $("#contents_img").val().split(",");
		j_img = content_img_array[0];
	}

	function iam_mystory(url) {
		iam_count('iam_mystory');
		location.href = "/?" + url;
	}

	function iam_count(str) {
		var member_id = '<?= $name_card['mem_id'] ?>';
		var card_idx = '<?= $name_card['idx'] ?>';
		var formData = new FormData();
		formData.append('str', str);
		formData.append('mem_id', member_id);
		formData.append('card_idx', card_idx);
		$.ajax({
			type: "POST",
			url: "ajax/iam_count.proc.php",
			data: formData,
			contentType: false,
			processData: false,
			success: function(data) {

			}
		})
	}

	function contents_sendSMS() {
		$("#sns-modalwindow").modal("hide");
		var navCase = navigator.userAgent.toLocaleLowerCase();
		if (navCase.search("android") > -1) {
			location.href = "sms:?body=<?php echo $domainData['sub_domain']; ?>/iam/contents_gwc.php?gwc=Y&contents_idx=" + j_idx;
		} else {
			alert("휴대폰에서 이용해주세요.");
		}
	}

	function sns_shareEmail() {
		$("#sns-modalwindow").modal("hide");
		var navCase = navigator.userAgent.toLocaleLowerCase();
		console.log(navCase);
		if (navCase.search("android") > -1) {
			$("#app").attr("src", "onlyone://action");
		} else {
			alert("휴대폰에서 이용해주세요.");
		}
	}

	function contents_shareKakaoTalk() {
		$("#sns-modalwindow").modal("hide");
		shareKakaotalk_contents(j_idx, j_name, j_title, j_desc, j_img);
	}

	function contents_shareFaceBook() {
		$("#sns-modalwindow").modal("hide");
		shareFaceBook_contents(j_idx, j_name);
	}

	function contents_copyContacts() {
		$("#sns-modalwindow").modal("hide");
		contents_copy(j_idx);
	}

	function shareKakaotalk_contents(idx, name, title, desc, img) {
		try {
			Kakao.Link.sendDefault({
				objectType: "feed",
				content: {
					title: title, // 콘텐츠의 타이틀
					description: desc, // 콘텐츠 상세설명
					imageUrl: img, // 썸네일 이미지
					link: {
						mobileWebUrl: '<?php echo $domainData['sub_domain']; ?>/iam/contents_gwc.php?gwc=Y&contents_idx=' + idx, // 모바일 카카오톡에서 사용하는 웹 링크 URL
						webUrl: '<?php echo $domainData['sub_domain']; ?>/iam/contents_gwc.php?gwc=Y&contents_idx=' + idx // PC버전 카카오톡에서 사용하는 웹 링크 URL
					}
				},
				buttons: [{
					title: "우리 프렌즈해요!", // 버튼 제목
					link: {
						mobileWebUrl: '<?php echo $domainData['sub_domain']; ?>/iam/contents_gwc.php?gwc=Y&contents_idx=' + idx, // 모바일 카카오톡에서 사용하는 웹 링크 URL
						webUrl: '<?php echo $domainData['sub_domain']; ?>/iam/contents_gwc.php?gwc=Y&contents_idx=' + idx // PC버전 카카오톡에서 사용하는 웹 링크 URL
					}
				}]
			});
		} catch (e) {
			alert("KaKao Talk과 연동할수 없습니다.");
		}
	}

	function shareFaceBook_contents(idx, name) {
		// alert(idx + " " + name);
		// return;
		var text = encodeURIComponent('아이엠의' + name + '님');
		var linkUrl = encodeURIComponent('<?php echo $domainData['sub_domain']; ?>/iam/contents_gwc.php?gwc=Y&contents_idx=' + idx);
		window.open('http://www.facebook.com/sharer.php?u=' + linkUrl + '&t=' + text);
	}
	//콘텐츠 복사
	function contents_copy(idx) {
		var aux = document.createElement("input");
		// 지정된 요소의 값을 할당 한다.
		aux.setAttribute("value", "<?php echo $domainData['sub_domain']; ?>/iam/contents_gwc.php?gwc=Y&contents_idx=" + idx);
		// bdy에 추가한다.
		document.body.appendChild(aux);
		$("#sns-modalwindow").modal("hide");
		// 지정된 내용을 강조한다.
		aux.select();
		// 텍스트를 카피 하는 변수를 생성
		document.execCommand("copy");
		// body 로 부터 다시 반환 한다.
		document.body.removeChild(aux);
		alert("복사에 성공 하였습니다.");
	}

	// 팝업 닫기 스크립트
	$('.popup-overlay, #closePopup').on('click', function() {
		$('.daily_popup').css('display', 'none');
		return false;
	});
	$('input[name=buy_type]').on('change', function() {
		var cur_cnt = parseInt($("#conts_cnt").val());
		if ($(this).val() == "download") {
			$("#gallery_download_price").html(number_format($("#download_price").val() * cur_cnt) + "원");
			$("#gallery_sell_price").html(number_format($("#origin_price").val() * (100 - $("#discount").val()) / 100) + "원");
			$("#gallery_ori_price").html(number_format($("#origin_price").val()) + "원");
		} else if ($(this).val() == "origin") {
			$("#gallery_download_price").html(number_format($("#download_price").val()) + "원");
			$("#gallery_sell_price").html(number_format($("#origin_price").val() * cur_cnt * (100 - $("#discount").val()) / 100) + "원");
			$("#gallery_ori_price").html(number_format($("#origin_price").val() * cur_cnt) + "원");
		}
	});

	// 하단 콘텐츠 접기 펼치기 스크립트
	$('.content-item .arrow').on('click', function() {
		var desc = $(this).parents('.content-item').find('.desc-inner-content');
		desc.toggleClass('show_content');
		$(this).toggleClass('active')
		return false;
	});

	function show_all_content_images(content_idx) {
		$("#content_all_image" + content_idx).hide();
		$("#hide_content_all_image" + content_idx).show();
		var parent = $("#hide_content_all_image" + content_idx).parents(".media-inner");
		var height = parent.css("max-height");
		height = height.replace("px", "") * 1;
		if (height > window.outerHeight) {
			height = window.outerHeight;
		}
		parent.css("max-height", height);
		$("#hide_content_all_image" + content_idx).css("top", height / 2);
		$(".hidden_image" + content_idx).show();
	}

	function hide_all_content_images(content_idx) {
		$("#content_all_image" + content_idx).show();
		$("#hide_content_all_image" + content_idx).hide();
		$(".hidden_image" + content_idx).hide();
		var parent = $("#hide_content_all_image" + content_idx).parents(".media-inner");
		parent.css("max-height", 1000);
	}

	function cancel_order_modal() {
		$("#show_gwc_order_option").modal('hide');
	}
</script>

</html>