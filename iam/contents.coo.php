<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
$contents_idx = $_GET['contents_idx'];
if ($HTTP_HOST != "kiam.kr") { //분양사사이트이면
	$query = "select * from Gn_Iam_Service where sub_domain like '%http://" . $HTTP_HOST . "'";
	$res = mysqli_query($self_con, $query);
	$domainData = mysqli_fetch_array($res);
} else {
	$query = "select * from Gn_Iam_Service where sub_domain like 'http://www.kiam.kr'";
	$res = mysqli_query($self_con, $query);
	$domainData = mysqli_fetch_array($res);
	$domainData['sub_domain'] = "http://kiam.kr/";
}
$mem_sql = "select mem_code from Gn_Member where mem_id = '$domainData[mem_id]'";
$mem_result = mysqli_query($self_con, $mem_sql);
$mem_row = mysqli_fetch_array($mem_result);
@setcookie("recommender_code", $mem_row['mem_code'], time() + 3600, "/");
$_COOKIE['recommender_code'] = $mem_row['mem_code'];

$meta_sql = "select * from Gn_Iam_Contents where idx = '$contents_idx'";
$meta_result = mysqli_query($self_con, $meta_sql);
$meta_row = mysqli_fetch_array($meta_result);

$sql = "select main_img1 from Gn_Iam_Info where mem_id = 'obmms02'";
$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$row = mysqli_fetch_array($result);
$default_avatar =  $row['main_img1'];

$sql = "select * from Gn_Iam_Name_Card where idx = '$meta_row[card_idx]'";
$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$name_card = mysqli_fetch_array($result);
//콘텐츠에 현시할 이름과 아바타
$contents_user_name = $name_card['card_name'];
$contents_avatar = "";
if ($name_card['main_img1']) {
	$contents_avatar = $name_card['main_img1'];
} else {
	$contents_avatar = $default_avatar;
}
$meta_img = $meta_row['contents_img'];
$meta_title = $meta_row['contents_title'];
$cur_card_short_url = $meta_row['westory_card_url'];
$meta_desc = $meta_row['contents_desc'];
?>
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
	<meta property="og:image" content="<?= $meta_img ?>"> <!--이미지-->
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
	<!-- ########## TODO COMMENT FOR TEST  패치할떄 해제해야함 ###########  -->
	<!--script src="//developers.kakao.com/sdk/js/kakao.min.js" charset="utf-8"></script-->
	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="js/slick.min.js"></script>
	<script src="js/main.js"></script>
	<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
	<link rel='stylesheet' href='../plugin/toastr/css/toastr.css' />
	<script src='../plugin/toastr/js/toastr.min.js'></script>
</head>

<body>
	<div id="wrap" class="common-wrap">
		<main id="star" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
			<section id="bottom">
				<div class="content-item" id="contents<?= $meta_row['idx'] ?>">
					<div class="user-item">
						<a href="/iam/?<?= strip_tags($meta_row['westory_card_url']) ?>" class="img-box">
							<div class="user-img">
								<img src="<?= $contents_avatar ?>" alt="">
							</div>
						</a>
						<div class="wrap">
							<a href="/iam/?<?= strip_tags($meta_row['westory_card_url']) ?>" class="user-name">
								<?= $contents_user_name ?>
							</a>
							<a href="/iam/?<?= strip_tags($meta_row['westory_card_url']) ?>" class="date">
								<?= $meta_row['req_data'] ?>
							</a>
						</div>
						<div class="dropdown" style="position: absolute; right: 10px; top: 8px;">
							<button class="btn dropdown-toggle westory_dropdown" type="button" data-toggle="dropdown">
								<span class="caret"></span></button>
							<ul class="dropdown-menu comunity">
								<li><a onclick="javascript:location.href='join.php'">나도 아이엠 만들고 싶어요</a></li>
								<li><a onclick="javascript:location.href='/iam/?<?= strip_tags($meta_row['westory_card_url']) ?>'">이 콘텐츠 게시자 보기</a></li>
								<li><a onclick="iam_mystory('<?= $meta_row['westory_card_url'] ?>&cur_win=we_story#bottom')">더 많은 콘텐츠 보러가기</a></li>
							</ul>
						</div>
					</div>

					<div class="desc-wrap">
						<? if ($meta_row['contents_title'] != "") { ?>
							<div class="title">
								<div class="text"><?= $meta_row['contents_title'] ?></div>
							</div>
						<?
						}
						if ((int)$meta_row['contents_type'] == 3) {
							$discount = 100 - ($meta_row['contents_sell_price'] / $meta_row['contents_price']) * 100;
						?>
							<div class="desc is-product">
								<div class="desc-inner">
									<div class="outer">
										<div class="percent"><?= (int)$discount ?>%</div>
										<div class="price">
											<span class="upper"><?= number_format($meta_row['contents_price']) ?>원</span>
											<span class="downer"><?= number_format($meta_row['contents_sell_price']) ?>원</span>
										</div>
										<div class="order">
											<a href="<?= $meta_row['contents_url'] ?>" target="_blank">주문</a>
										</div>
									</div>
								</div>
							</div>
						<? } ?>
					</div>
					<div class="media-wrap">
						<?
						if ($_SESSION['iam_member_id'] == $card_owner) {
							$footer_contents_title = $meta_row['contents_title'];
							$footer_contents_title = str_replace('"', urlencode('"'), $footer_contents_title);
							$footer_contents_title = str_replace("'", urlencode("\'"), $footer_contents_title);
							$footer_contents_desc = $meta_row['contents_desc'];
							$footer_contents_desc = str_replace('"', urlencode('"'), $footer_contents_desc);
							$footer_contents_desc = str_replace("'", urlencode("\'"), $footer_contents_desc);
							$footer_contents_iframe = urlencode($meta_row['contents_iframe']);
							// echo $footer_contents_title;
							if ($cur_win == "my_story") { ?>
								<span class="pull-left " style="position:absolute;left:10px;top:10px;"><?= $meta_row['req_data'] ?></span>
						<? }
						} ?>

						<div class="media-inner">
							<?
							if ((int)$meta_row['contents_type'] == 1 || (int)$meta_row['contents_type'] == 3) {
								if ($meta_row['contents_img']) {
									if ($meta_row['contents_url']) {
										if (strpos($contents_row['contents_url'], "<iframe") == 0) { ?>
											<div onclick="play_contents<?= $meta_row['idx'] ?>();" id="<?= 'vid_cont' . $meta_row['idx'] ?>">
												<img src="<?= $meta_row['contents_img'] ?>" class="contents_img">
											</div>
											<script type="text/javascript">
												function play_contents<?= $meta_row['idx'] ?>() {
													document.getElementById("<?= 'vid_cont' . $meta_row['idx'] ?>").innerHTML = '<?= $meta_row['contents_url'] ?>';
												}
											</script>
										<? } else { ?>
											<a onclick="showIframeModal('<?= $meta_row['contents_url'] ?>')" target="_blank">
												<img src="<?= $meta_row['contents_img'] ?>" class="contents_img"></a>
										<? }
									} else { ?>
										<img src="<?= $meta_row['contents_img'] ?>" class="contents_img">
									<?
									}
								}
							} else if ((int)$meta_row['contents_type'] == 2) {
								if (!$meta_row['contents_img']) {
									echo $meta_row['contents_iframe'];
								} else {
									?>
									<div onclick="play();" id="vidwrap">
										<img src="<?= $meta_row['contents_img'] ?>" class="contents_img">
									</div>
									<script type="text/javascript">
										function play() {
											document.getElementById('vidwrap').innerHTML = '<?= $meta_row['contents_iframe'] ?>';
										}
									</script>
								<?
								}
							} else if ((int)$meta_row['contents_type'] == 4) {
								?>
								<div>
									<iframe src="<?= $vid_data ?>" width="100%" height="100%"></iframe>
								</div>
								<!--
                                <div onclick="play<?= $kk; ?>();" id="vidwrap<?= $kk; ?>">
                                    <img src="<?= $contents_row['contents_img'] ?>" class="contents_img">
                                </div>
                                -->
								<script type="text/javascript">
									function play<?= $kk; ?>() {
										document.getElementById('vidwrap<?= $kk; ?>').innerHTML = '<iframe src="<?= $contents_row['source_iframe'] ?>" width="100%" height="100%"></iframe>';
									}
								</script>
							<?
							}
							?>
						</div>
					</div>
					<div class="info-wrap" style="<?= $footer_display ?>">
						<? if ($meta_row['contents_url'] && strpos($meta_row['contents_url'], "<iframe") === false) { ?>
							<div class="media-tit" style="padding-bottom:0px;font-size: 16px; font-weight: bold;">
								<a href="<?= $meta_row['contents_url'] ?>" target="_blank"><?= $meta_row['contents_url'] ?></a>
								<br><?= $meta_row['contents_url_title'] ?>
							</div>
						<? } ?>
						<?
						if ($meta_row['contents_desc']) {
						?>
							<div class="desc">
								<div class="desc-inner desc-text desc-inner-content">
									<?= nl2br($meta_row['contents_desc']) ?>
								</div>
								<a href="#" class="arrow" style="color:#888;font-weight:bold;">
									[+]
								</a>
							</div>
						<? } ?>
						<div class="second-box">
							<div class="in-box">
								<a href="javascript:contents_like('<?= $meta_row['idx'] ?>');" class="hand">
									<img src="img/icon_thumbs-up.svg" width="30" alt="">
								</a>
								<? if ($meta_row['contents_like']) { ?>
									<p class="like-count like_<?= $meta_row['idx'] ?>">
										<?= number_format($meta_row['contents_like']) ?>개
									</p>
								<? } else { ?>
									<p class="like-count like_<?= $meta_row['idx'] ?>">0개</p>
								<? } ?>
								<div class="sns-icon">
									<a href="javascript:showSNSModal('<?= $meta_row['idx'] ?>','<?= $name_card['card_name'] ?>','<?= $meta_row['contents_title'] ?>', '<?= htmlspecialchars($meta_row['contents_desc'], ENT_COMPAT, 'UTF-8') ?>', '<?= $meta_row['contents_img'] ?>');">
										<img src="img/icon_share-android_black.png" width="25" height="25">
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</main><!-- // 컨텐츠 영역 끝 -->

		<footer id="footer" style="text-align: center;">
			<?php
			if ($HTTP_HOST != "kiam.kr") {
			?>
				<a href="<?php echo $domainData['footer_link']; ?>"><img src="<?= $domainData['footer_logo'] == "" ? "img/common/logo.png" : $domainData['footer_logo']; ?>" alt="아이엠 푸터로고" width="100"></a>
			<?php } else { ?>
				<a href="/m/"><img src="img/common/logo.png" alt="아이엠 푸터로고" width="100"></a>
			<?php } ?>
		</footer>
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
						<div class="sns_item" onclick="sns_shareEmail()">
							<div class="sns_icon_div"><img class="sns_icon" src="img/sns_icon8.png"></div>
							<div class="sns_item_text">이메일<br>공유</div>
						</div>
						<div class="sns_item" onclick="contents_shareKakaoTalk()">
							<div class="sns_icon_div"><img class="sns_icon" src="img/sns_icon3.png"></div>
							<div class="sns_item_text">카톡<br>공유</div>
						</div>
					</div>
					<div class="center_text">
						<div class="sns_item" onclick="sns_shareInsta()">
							<div class="sns_icon_div"><img class="sns_icon" src="img/sns_icon7.png"></div>
							<div class="sns_item_text">인스타<br>공유</div>
						</div>
						<div class="sns_item" onclick="sns_shareBand()">
							<div class="sns_icon_div"><img class="sns_icon" src="img/sns_icon6.png"></div>
							<div class="sns_item_text">밴드<br>공유</div>
						</div>
						<div class="sns_item" onclick="contents_shareFaceBook()">
							<div class="sns_icon_div"><img class="sns_icon" src="img/sns_icon5.png"></div>
							<div class="sns_item_text">페북<br>공유</div>
						</div>
						<div class="sns_item" onclick="contents_copyContacts()">
							<div class="sns_icon_div"><img class="sns_icon" src="img/sns_icon4.png"></div>
							<div class="sns_item_text">아이엠<br>주소복사</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--데일리 발송 팝업-->
	<div id="popup" class="popup5">
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
				<a href="https://www.onestore.co.kr/userpoc/apps/view?pid=0000738690">둘째 내 폰의 문자를 보내려면 앱을
					설치해야지요!(클릭)</a><br>
				셋째 데일리발송을 시작해요!<br>
				※ 아이엠을 보내는 기능은 무료이지만 일반 메시지를 보내는 것은 유료입니다.</h3>
			</div>
			<div class="button-wrap">
				<? if ($_SESSION['iam_member_id']) {
					$iam_link = $domainData['sub_domain'] . "/iam/?" . $cur_card_short_url;
				?>
					<a href="#" id="closePopup" class="buttons is-cancel">다음에보내기</a>
					<a id="popup5_content" href="#" target="_blank" class="buttons is-save">시작하기</a>
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
		$(".popup5").center();
		$('.popup5').css('display', 'block');
		var iam_link = "daily_write_iam.php?msg_title=콘텐츠 제목을 가져와서 디폴트로 입력해주세요." + "&msg=아래 내용을 디폴트로 넣어주세요.멋진 콘텐츠를 소개합니다.  " +
			"<?= $domainData['sub_domain'] . "/iam/contents.php?contents_idx =" . $contents_idx ?>";
		$("#popup5_content").attr("href", iam_link);
	}

	function daily_send_pop_close() {
		$('.popup5').css('display', 'none');
	}
	var j_idx;
	var j_name;
	var j_title;
	var j_desc;
	var j_img;

	function showSNSModal(idx, name, title, desc, img) {
		$("#sns-modalwindow").modal("show");
		j_idx = idx;
		j_name = name;
		j_title = title;
		j_desc = desc;
		j_img = img;
	}

	function iam_mystory(url) {
		iam_count('iam_mystory');
		location.href = "/iam/?" + url;
	}

	function iam_count(str) {
		let member_id = '<?= $name_card['mem_id'] ?>';
		let card_idx = '<?= $name_card['idx'] ?>';
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
		location.href = "sms:?body=<?php echo $domainData['sub_domain']; ?>/iam/contents.php?contents_idx=" + j_idx;
	}

	function sns_shareInsta() {
		$("#sns-modalwindow").modal("hide");
	}

	function sns_shareEmail() {
		$("#sns-modalwindow").modal("hide");
	}

	function sns_shareBand() {
		$("#sns-modalwindow").modal("hide");
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
						mobileWebUrl: '<?php echo $domainData['sub_domain']; ?>/iam/contents.php?contents_idx=' + idx, // 모바일 카카오톡에서 사용하는 웹 링크 URL
						webUrl: '<?php echo $domainData['sub_domain']; ?>/iam/contents.php?contents_idx=' + idx // PC버전 카카오톡에서 사용하는 웹 링크 URL
					}
				},
				buttons: [{
					title: "우리 프렌즈해요!", // 버튼 제목
					link: {
						mobileWebUrl: '<?php echo $domainData['sub_domain']; ?>/iam/contents.php?contents_idx=' + idx, // 모바일 카카오톡에서 사용하는 웹 링크 URL
						webUrl: '<?php echo $domainData['sub_domain']; ?>/iam/contents.php?contents_idx=' + idx // PC버전 카카오톡에서 사용하는 웹 링크 URL
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
		var linkUrl = encodeURIComponent('<?php echo $domainData['sub_domain']; ?>/iam/contents.php?contents_idx=' + idx);
		window.open('http://www.facebook.com/sharer.php?u=' + linkUrl + '&t=' + text);
	}
	//콘텐츠 복사
	function contents_copy(idx) {
		var aux = document.createElement("input");
		// 지정된 요소의 값을 할당 한다.
		aux.setAttribute("value", "<?php echo $domainData['sub_domain']; ?>/iam/contents.php?contents_idx=" + idx);
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
	// 콘텐츠 좋아요
	function contents_like(idx) {
		var formData = new FormData();
		// console.log(idx + ", " + temp);

		formData.append("mode", "like");
		formData.append("contents_idx", idx);

		$.ajax({
			type: "POST",
			url: "ajax/contents_like.proc.php",
			data: formData,
			contentType: false,
			processData: false,
			success: function(data) {
				console.log(data);
				//alert("성공되었습니다.");
				toastr.success("성공되었습니다.", "좋아요");
				$(".like_" + idx).html(data + "개");
				//location.reload();
				//console.log(data);
			}
		})
	}
	// 팝업 닫기 스크립트
	$('.popup-overlay, #closePopup').on('click', function() {
		$('.popup5').css('display', 'none');
		return false;
	});
</script>

</html>