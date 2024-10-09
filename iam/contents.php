<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
$contents_idx = $_GET['contents_idx'];
$recent_post = 0;
if (isset($_GET['gwc'])) {
	$gwc = 1;
} else {
	$gwc = 0;
}
if ($member_iam['gwc_leb'] >= 1 && $member_iam['gwc_state'] == "1") {
	$gwc_mem = 1;
} else {
	$gwc_mem = 0;
}
$HTTP_HOST = str_replace("www.", "", $_SERVER['HTTP_HOST']);
if ($HTTP_HOST != "kiam.kr") { //분양사사이트이면
	$query = "select * from Gn_Iam_Service where sub_domain like '%http://" . $HTTP_HOST . "'";
	$res = mysql_query($query);
	$domainData = mysql_fetch_array($res);
} else {
	$query = "select * from Gn_Iam_Service where sub_domain like 'http://www.kiam.kr'";
	$res = mysql_query($query);
	$domainData = mysql_fetch_array($res);
	$domainData['sub_domain'] = "http://kiam.kr/";
}
if ($_SESSION['iam_member_id']) {
	$post_time = date("Y-m-d H:i:s", strtotime("-1 week"));
	$post_sql = "select count(*) from Gn_Member where mem_id = '{$_SESSION['iam_member_id']}' and last_regist > '$post_time'";
	$post_result = mysql_query($post_sql);
	$post_row = mysql_fetch_array($post_result);
	$recent_post = $post_row[0];
}
$mem_sql = "select mem_code from Gn_Member where mem_id = '{$domainData['mem_id']}'";
$mem_result = mysql_query($mem_sql);
$mem_row = mysql_fetch_array($mem_result);

$meta_sql = "update Gn_Iam_Contents set contents_temp=contents_temp+1 where idx = '$contents_idx'";
mysql_query($meta_sql);

$meta_sql = "select * from Gn_Iam_Contents where idx = '$contents_idx'";
$meta_result = mysql_query($meta_sql);
$meta_row = mysql_fetch_array($meta_result);

$sql = "select main_img1 from Gn_Iam_Info where mem_id = 'obmms02'";
$result = mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_array($result);
$default_avatar =  $row['main_img1'];

$sql = "select * from Gn_Iam_Name_Card where idx = '{$meta_row['card_idx']}'";
$result = mysql_query($sql) or die(mysql_error());
$name_card = mysql_fetch_array($result);

$card_owner = $name_card['mem_id'];
$_SESSION['recommender_code'] = $card_owner;
@setcookie("recommender_code", $card_owner, time() + 3600);
$_COOKIE['recommender_code'] = $card_owner;

//콘텐츠에 현시할 이름과 아바타
$contents_user_name = $name_card['card_name'];
$contents_avatar = "";
if ($name_card['main_img1']) {
	$contents_avatar = $name_card['main_img1'];
} else {
	$contents_avatar = $default_avatar;
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
?>
<style>
	.post_content,
	.post_reply_content {
		border-radius: 15px;
		background: #dddddd;
		border: none;
		padding-left: 10px;
		padding-top: 8px;
	}

	.content-utils {
		background: #797979;
		border-radius: 50%;
		width: 22px;
		height: 22px;
		overflow: hidden;
		font-size: 15px;
		cursor: pointer;
		position: absolute;
		top: 70px;
		right: 20px;
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
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
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
	<!-- ########## TODO COMMENT FOR TEST  패치할떄 해제해야함 ###########  -->
	<script src="//developers.kakao.com/sdk/js/kakao.min.js" charset="utf-8"></script>
	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="js/slick.min.js"></script>
	<script src="js/main.js"></script>
	<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
	<link rel='stylesheet' href='../plugin/toastr/css/toastr.css' />
	<script src='../plugin/toastr/js/toastr.min.js'></script>
	<script>
		$(document).ready(function() {
			var win_height = $(window).height();
			// var user_height = $('.user-item').height();
			// var desc_height = $('.desc-wrap').height();
			var info_height = $('.info-wrap').height();

			var media_higth = win_height - (info_height);
			$('.media-inner').attr('style', 'max-height:' + media_higth + 'px;');
		});
	</script>
</head>

<body>
	<input type="hidden" id="mem_id" value="<?= $_SESSION['iam_member_id'] ?>">
	<input type="hidden" id="recent_post" value="<?= $recent_post ?>">
	<div id="wrap" class="common-wrap">
		<main id="star" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
			<section id="bottom">
				<input type="hidden" id="contents_user_name" value="<?= $contents_user_name ?>">
				<input type="hidden" id="contents_title" value="<?= $meta_row['contents_title'] ?>">
				<input type="hidden" id="contents_desc" value="<?= $meta_row['contents_desc'] ?>">
				<input type="hidden" id="contents_img" value="<?= $meta_row['contents_img'] ?>">
				<div class="content-item" id="contents<?= $meta_row['idx'] ?>">
					<div class="desc-wrap">
						<? if ($meta_row['contents_title'] != "") { ?>
							<div class="title">
								<h3><?= $meta_row['contents_title'] ?></h3>
							</div>
						<?
						}
						if ((int)$meta_row['contents_type'] == 3) {
							$price_show1 = "적립금";
							$price_show2 = "판매가";
							$price_style = "";
							$sql_card = "select sale_cnt, sale_cnt_set, add_reduce_val from Gn_Iam_Name_Card where idx='{$meta_row['card_idx']}'";
							$res_card = mysql_query($sql_card);
							$row_card = mysql_fetch_array($res_card); ?>
							<div class="desc is-product">
								<div class="desc-inner">
									<div class="outer <?= $row_card['sale_cnt_set'] ?>">
										<? if ($gwc && !$gwc_mem) {
											$buy_btn = "display:none;"; ?>
											<span style="font-size: 15px;">공급/소비 회원에게만 보입니다.</span>
											<? } else {
											$buy_btn = "";
											if ($meta_row['contents_price'] > 0) {
												if (!$row_card['sale_cnt_set']) {
													$style_decor = "";
													$state_end = "";
													$discount = 100 - ($meta_row['contents_sell_price'] / $meta_row['contents_price']) * 100;
													$add_point = $meta_row['contents_price'] * ((int)$discount / 100);
												} else {
													if (!$row_card['sale_cnt']) {
														$style_decor = "text-decoration: line-through;";
														$state_end = "마감";
														$discount = 100 - ($meta_row['contents_sell_price'] / $meta_row['contents_price']) * 100;
														$add_point = $meta_row['contents_price'] * (((int)$discount - $row_card['add_reduce_val']) / 100);
													} else {
														$style_decor = "";
														$state_end = "적립";
														$discount = 100 - ($meta_row['contents_sell_price'] / $meta_row['contents_price']) * 100;
														$add_point = $meta_row['contents_price'] * ((int)$discount / 100);
													}
												}
												if ($gwc) {
													$state_end = "";
													if ($meta_row['gwc_con_state'] != 0) {
														$price_show1 = "최저가";
														$price_show2 = "정상가";
														$price_style = "text-decoration: line-through;";
														$add_point = $meta_row['contents_sell_price'];
													}
												}
											?>
												<div class="price" style="width:230px;">
													<span class="downer"><?= $price_show2 ?>:<span style="vertical-align: top;<?= $price_style ?>"><?= number_format($meta_row['contents_price']) ?></span>원</span>
													<span class="downer" style="color:red;"><?= $price_show1 ?>:<?= number_format((int)$add_point) ?>원 <span style="vertical-align: top;color: red;margin-left: 10px;font-weight: bold;"><?= (int)$discount ?>%</span></span>
												</div>
												<span style="font-size: 15px;"><?= $state_end ?></span>
											<? } else { ?>
												<div class="price">
													<span class="downer"><?= $price_show2 ?>:<span style="vertical-align: top;<?= $price_style ?>"><?= number_format($meta_row['contents_price']) ?></span>원 <span style="vertical-align: top;color: red;margin-left: 10px;font-weight: bold;">0%</span></span>
													<span class="downer" style="color:red;"><?= $price_show1 ?>:<?= number_format($meta_row['contents_price']) ?>원</span>
												</div>
												<!-- <div class="percent">0%</div> -->
										<? }
										} ?>
										<? if (!$gwc) { ?>
											<div class="order" style="<?= $buy_btn ?><?= $buy_btn_con ?>">
												<? if ($_SESSION['iam_member_id']) {
													$price_service = $meta_row['contents_sell_price'];
													$meta_row['contents_title'] = str_replace('"', ' ', $meta_row['contents_title']);
													$meta_row['contents_title'] = str_replace("'", ' ', $meta_row['contents_title']);
													$name_service = $meta_row['contents_title'];
													$sellerid_service = $meta_row['mem_id'];
													$contents_url = $meta_row['contents_url'];
													$card_price = $meta_row['contents_sell_price'] * 1 + $meta_row['send_salary_price'] * 1;
													$pay_link = "/iam/pay_spgd.php?item_name=" . $meta_row['contents_title'] . '&item_price=' . $card_price . '&manager=' . $meta_row['mem_id'] . "&conidx=" . $meta_row['idx'] . "&sale_cnt=" . $row_card['sale_cnt'];
												?>
													<div class="dropdown" style="float:right;width: 82px;">
														<a class="dropdown-toggle" data-toggle="dropdown" expanded="false" style="background:#99cc00;border-radius:10px;cursor:pointer;">구매</a>
														<ul class="dropdown-menu buy_servicecon" style="width: 82px;">
															<li>
																<a href="<?= $pay_link ?>" target="_blank" style="font-size: 12px;background-color:#99cc00;">카드결제</a>
															</li>
															<li>
																<a onclick="point_settle_modal(<?= $meta_row['contents_sell_price'] ?>, '<?= $meta_row['contents_title'] ?>', '<?= $meta_row['idx'] ?>', '<?= $meta_row['mem_id'] ?>', '<?= $row_card['sale_cnt'] ? $row_card['sale_cnt'] : '0' ?>', '<?= $meta_row['send_salary_price'] ? $meta_row['send_salary_price'] : '0' ?>')" style="font-size: 12px;background-color:#99cc00;">포인트결제</a>
															</li>
														</ul>
													</div>
												<? } else { ?>
													<a href="<? echo '/iam/login.php?contents_idx=' . $meta_row['idx'] ?>" target="_self" style="background:#99cc00;border-radius:10px;">구매</a>
												<? } ?>
											</div>
										<? } ?>
									</div>
								</div>
							</div>
						<? } ?>
						<? if ($meta_row['contents_desc']) { ?>
							<div class="desc">
								<? if ($content_images == null) { ?>
									<a href="javascript:showSNSModal('<?= $meta_row['idx'] ?>');" class="content-utils">
										<img src="/iam/img/menu/icon_share_white.png" style="width:13px;height:16px;margin-left:2px;margin-top:3px;">
									</a>
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
					<div class="media-wrap">
						<div class="media-inner" <? if ($meta_row['contents_type'] == 1 && count($content_images) == 0) echo "style = 'min-height :30px'"; ?>>
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
									<a href="/?<?= strip_tags($meta_row['westory_card_url']) ?>" class="user-name">
										<?= $meta_row['req_data'] ?>
									</a>
								</div>
								<div class="dropdown" style="position: absolute; right: 50px; top: 8px;">
									<button class="btn-link dropdown-toggle westory_dropdown" type="button" data-toggle="dropdown">
										<img src="/iam/img/menu/icon_dot.png" style="height:24px">
									</button>
									<ul class="dropdown-menu comunity">
										<li><a onclick="javascript:location.href='join.php'">나도 아이엠 만들고 싶어요</a></li>
										<li><a onclick="javascript:location.href='/?<?= strip_tags($meta_row['westory_card_url']) ?>'">이 콘텐츠 게시자 보기</a></li>
										<li><a onclick="iam_mystory('<?= $meta_row['westory_card_url'] ?>&cur_win=we_story#bottom')">더 많은 콘텐츠 보러가기</a></li>
									</ul>
								</div>
							</div>
							<div class="dropdown" style="position: absolute;right: 14px;top: 8px;z-index: 10;">
								<button class="btn-link dropdown-toggle westory_dropdown" type="button" onclick="close_frame()">
									<img src="/iam/img/menu/icon_x.PNG" style="height:24px">
								</button>
							</div>

							<? if ($content_images != null) { ?>
								<a href="javascript:showSNSModal('<?= $meta_row['idx'] ?>');" class="content-utils">
									<img src="/iam/img/menu/icon_share_white.png" style="width:13px;height:16px;margin-left:-1px;margin-top:3px;">
								</a>
								<? }
							if ((int)$meta_row['contents_type'] == 1 || (int)$meta_row['contents_type'] == 3) {
								$open_domain = get_search_key('open_blank_cont_domain');
								$open_state = 0;
								if ($open_domain != '') {
									$filter = explode(",", trim($open_domain));
									for ($j = 0; $j < count($filter); $j++) {
										$str = trim($filter[$j]);
										if (strpos($meta_row['contents_url'], $str) !== false) {
											$open_state = 1;
										}
									}
								}
								if ($open_state) {
								?>
									<a href='<?= $meta_row['contents_url'] ?>' data="01" target="_blank" id="pagewrap<?= $meta_row['idx'] ?>">
										<? if (count($content_images) > 0) { ?>
											<img src="<?= cross_image($content_images[0]) ?>" class="contents_img">
											<? }
										if (count($content_images) > 1) {
											for ($c = 1; $c < count($content_images); $c++) { ?>
												<img src="<?= cross_image($content_images[$c]) ?>" class="contents_img hidden_image<?= $meta_row['idx'] ?>" style="<?= $landing ?>">
										<? }
										} ?>
									</a>
									<? } else if ((int)$meta_row['open_type'] == 1) {
									if ($meta_row['media_type'] == "I") {
										if (count($content_images) > 0 && strstr($meta_row['contents_url'], ".mp4") === false) { ?>
											<div onclick="showpage('<?= $meta_row['contents_url'] ?>')" id="pagewrap">
												<img src="<?= cross_image($content_images[0]) ?>" class="contents_img">
												<? for ($c = 1; $c < count($content_images); $c++) { ?>
													<img src="<?= cross_image($content_images[$c]) ?>" class="contents_img hidden_image<?= $meta_row['idx'] ?>" style="<?= $landing ?>">
												<? } ?>
											</div>
										<? } else if ($meta_row['contents_url'] != "") { ?>
											<div style="background:white">
												<iframe src="<?= $cross_page . urlencode($meta_row['contents_url']) ?>" width="100%" height="600px" sandbox="allow-same-origin allow-scripts allow-popups allow-forms allow-top-navigation"></iframe>
											</div>
										<? }
									} else { ?>
										<div class="gwc_con_img" style="position: relative">
											<a style="position: absolute;top: 20px;left: 20px;background: #f3f3f3;display: flex;padding:5px;padding-right:15px;z-index:10;font-size:14px" class="unmute_btn">
												<img src="/iam/img/main/mute.png" style="height: 30px">
												<p style="margin-top: 5px;font-weight:bold;">탭하여 음소거 해제</p>
											</a>
											<video src="<?= $meta_row['contents_img'] ?>" type="video/mp4" autoplay loop muted playsinline preload style="width:100%;" autoplay></video>
											<img src="/iam/img/movie_play.png" style="display:none;width:70px;" class="movie_play">
										</div>
										<? }
								} else {
									if (count($content_images) > 0) {
										if ($meta_row['contents_url'] != "") { ?>
											<a href='<?= $meta_row['contents_url'] ?>' target="_blank" id="pagewrap">
											<? } ?>
											<img src="<?= cross_image($content_images[0]) ?>" class="contents_img">
											<? for ($c = 1; $c < count($content_images); $c++) { ?>
												<img src="<?= cross_image($content_images[$c]) ?>" class="contents_img hidden_image<?= $meta_row['idx'] ?>" style="<?= $landing ?>">
											<? } ?>
											<? if ($meta_row['contents_url'] != "") { ?>
											</a>
										<? } ?>
									<? } else if ($meta_row['contents_url'] != "") { ?>
										<div style="background:white">
											<iframe src="<?= $cross_page . urlencode($meta_row['contents_url']) ?>" width="100%" height="600px" sandbox="allow-same-origin allow-scripts allow-popups allow-forms allow-top-navigation"></iframe>
										</div>
									<? }
								}
							} else if ((int)$meta_row['contents_type'] == 2) {
								$vid_array = explode(" ", $meta_row['contents_iframe']);
								$vid_array[2] = "height=100%";
								$vid_array[1] = "width=100%";
								$vid_data = implode(" ", $vid_array);
								if (!$meta_row['contents_img']) {
									echo $meta_row['contents_iframe'];
								} else { ?>
									<div onclick="play();" id="vidwrap">
										<img src="<?= cross_image($content_images[0]) ?>" class="contents_img">
										<img src="/iam/img/movie_play.png" style="position: absolute; z-index: 50; left: 45%; width: 100px; top: 45%;">
										<? if (count($content_images) > 1) { ?>
											<? for ($c = 1; $c < count($content_images); $c++) { ?>
												<img src="<?= cross_image($content_images[$c]) ?>" class="contents_img hidden_image<?= $meta_row['idx'] ?>" style="display:none">
											<? } ?>
										<? } ?>
									</div>
									<script type="text/javascript">
										function play() {
											document.getElementById('vidwrap').innerHTML = "<?= $meta_row['contents_iframe'] ?>";
										}
									</script>
								<?
								}
							} else if ((int)$meta_row['contents_type'] == 4) {
								$vid_data = $meta_row['source_iframe'];
								?>
								<div>
									<iframe src="<?= $vid_data ?>" style="width:100%;height: 600px"></iframe>
								</div>
								<script type="text/javascript">
									function play() {
										document.getElementById('vidwrap').innerHTML = '<iframe src="<?= $meta_row['source_iframe'] ?>" width="100%" height="100%"></iframe>';
									}
								</script>
							<? } ?>
						</div>
					</div>
					<div class="info-wrap" style="border:none">
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
							<div class="media-tit" style="padding-bottom:0px;font-size: 16px; font-weight: bold;">
								<a href="<?= $meta_row['contents_url'] ?>" target="_blank"><?= $video_type ?></a>
								<br><?= $meta_row['contents_url_title'] ?>
							</div>
						<? } ?>

						<div class="second-box">
							<div class="in-box">
								<a href="javascript:contents_like('<?= $meta_row['idx'] ?>','<?= $_SESSION['iam_member_id'] ?>');" class="hand">
									<? if (in_array($_SESSION['iam_member_id'], explode(",", $meta_row['contents_like']))) { ?>
										<img src="img/menu/icon_like_active.png" width="24px" alt="" id="like_img_<?= $meta_row['idx'] ?>">
									<? } else { ?>
										<img src="img/menu/icon_like.png" width="24px" alt="" id="like_img_<?= $meta_row['idx'] ?>">
									<? } ?>
								</a>
								<p class="like-count like_<?= $meta_row['idx'] ?>" style="font-size:13px">
									<?= number_format(count(explode(",", $meta_row['contents_like']))) ?>개
								</p>
								<a href="javascript:show_post('<?= $meta_row['idx'] ?>');" class="hand">
									<img src="img/menu/icon_post.png" id="<?= 'show_post_img_' . $meta_row['idx'] ?>" width="24px" alt="">
									<label style="font-size: 10px;background: #ff3333;border-radius: 50%!important;padding: 3px 5px!important;color: #fff;
                                        text-align: center;line-height: 1;position: absolute;margin-left: -15px" id="<?= 'post_alarm_' . $meta_row['idx'] ?>"></label>
								</a>
								<?
								$post_sql = "select SQL_CALC_FOUND_ROWS * from Gn_Iam_Post p inner join Gn_Member m on p.mem_id = m.mem_id where p.content_idx = '{$meta_row['idx']}' and p.lock_status = 'N' order by p.reg_date";
								$post_res = mysql_query($post_sql);
								$post_count	=  mysql_num_rows($post_res);

								$post_status_sql = "select count(*) from Gn_Iam_Post where content_idx = '{$meta_row['idx']}' and status = 'N' and lock_status = 'N'";
								$post_status_res = mysql_query($post_status_sql);
								$post_status_row =  mysql_fetch_array($post_status_res);
								$post_status_count = $post_status_row[0];
								if ($post_status_count  > 0)
									echo "<script>  $('#post_alarm_" . $meta_row['idx'] . "').html(" . $post_status_count . "); </script>";
								else
									echo "<script>  $('#post_alarm_" . $meta_row['idx'] . "').hide(); </script>";
								?>
								<p onclick="refresh_post('<?= $meta_row['idx'] ?>')" style="font-size:13px;margin: 5px;font-weight: 600;cursor:pointer" id="<?= 'post_count_' . $meta_row['idx'] ?>"><?= $post_count ?>개의 댓글 &#x21BA;</p>
							</div>
							<? if ($gwc) { ?>
								<div class="in-box" style="position: absolute;right: 10px;">
									<a class="gwc_order_btn1" href="javascript:show_order_option('<?= $meta_row['idx'] ?>', 'cart')" style="margin-top: 5px;font-size: 12px;border: 1px solid #99cc00;margin-right: 10px;padding: 3px 10px;border-radius: 10px;">장바구니</a>
									<a class="gwc_order_btn2" href="javascript:show_order_option('<?= $meta_row['idx'] ?>', 'pay')" style="margin-top: 5px;font-size: 12px;padding: 3px 10px;border-radius: 10px;border: 1px solid;color: white;background-color: #99cc00;">바로구매</a>
								</div>
							<? } ?>
						</div>
					</div>
					<div class="post-wrap <?= 'post_wrap' . $meta_row['idx'] ?>" style="display:none" id="<?= 'post_wrap' . $meta_row['idx'] ?>">
						<div style="display: flex;justify-content: flex-end;">
							<div style="margin-left:30px;margin-right:35px;width:100%">
								<textarea id="post_content<?= $meta_row['idx'] ?>" name="post_content<?= $meta_row['idx'] ?>" class="post_content" maxlength="300" style="font-size:14px;height:35px;width:100%;border: 1px" placeholder="댓글은 300자 이내로 작성해주세요"></textarea>
							</div>
							<div style="width:35px">
								<button type="button" class="btn btn-link" style="position: absolute; right: 1px; padding: 9px 12px;color:#99cc00" id="send_post" onclick="add_post('<?= $meta_row['idx'] ?>')">작성</button>
							</div>
						</div>
						<div style="margin-left:30px;">
							<span id="post_status" name="post_status" style="padding: 10px;font-size:10px">0/300</span>
						</div>
						<div style="border: 0px solid #dddddd;margin-left:30px;" id="<?= 'post_list_' . $meta_row['idx'] ?>" name="<?= 'post_list_' . $meta_row['idx'] ?>">
							<? while ($post_row = mysql_fetch_array($post_res)) { ?>
								<div class="user-item" id="<?= 'post_reply' . $post_row['id'] ?>">
									<a href="/?<?= strip_tags($meta_row['westory_card_url']) ?>" class="img-box">
										<div class="user-img" style="margin: 5px;width:32px;height:32px;">
											<? if ($post_row['profile']) { ?>
												<img src="<?= $post_row['profile'] ?>" alt="">
											<? } else { ?>
												<img src="<?= $contents_avatar ?>" alt="">
											<? } ?>
										</div>
									</a>
									<div class="wrap" style="margin:10px 0px;">
										<span class="date">
											<?= $post_row['mem_name'] . " " . $post_row['reg_date'] ?>
										</span>
										<span class="user-name">
											<?= $post_row['content'] ?>
										</span>
									</div>
									<? if ($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $post_row['mem_id']) { ?>
										<div class="dropdown" style="top : 10px;position : absolute;right:30px;height:24px">
											<img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_dot.png" style="height: 24px;">
											<ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
												<li>
													<a href="javascript:void(0)" onclick="edit_post('<?= $meta_row['idx'] ?>','<?= $post_row['id'] ?>','<?= $post_row['content'] ?>')" title="댓글 수정">
														<p>수정</p>
													</a>
												</li>
												<li>
													<a href="javascript:void(0)" onclick="delete_post('<?= $meta_row['idx'] ?>','<?= $post_row['id'] ?>')" title="댓글 삭제">
														<p>삭제</p>
													</a>
												</li>
											</ul>
										</div>
									<? } else if ($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $meta_row['mem_id']) { ?>
										<div class="dropdown" style="top : 10px;position : absolute;right:30px;height:24px">
											<img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_dot.png" style="height: 24px;">
											<ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
												<li>
													<a href="javascript:void(0)" onclick="delete_post('<?= $meta_row['idx'] ?>','<?= $post_row['id'] ?>')" title="댓글 삭제">
														<p>삭제</p>
													</a>
												</li>
												<li>
													<a href="javascript:void(0)" onclick="lock_post('<?= $meta_row['idx'] ?>','<?= $post_row['id'] ?>')" title="댓글 차단">
														<p>차단</p>
													</a>
												</li>
											</ul>
										</div>
									<? } ?>
									<div style="position: absolute;left: 60px;bottom: 0px">
										<span style="color: #bdbdbd;cursor:pointer;font-size:13px" onclick="show_post_reply(<?= $post_row['id'] ?>);">
											답글달기
										</span>
									</div>
								</div>
								<div id="<?= 'post_reply_' . $post_row['id'] ?>" class="post_reply_wrap" style="display: none;margin : 10px 0px">
									<div style="display: flex;justify-content: flex-end;">
										<div style="margin-left:60px;margin-right:35px;width:100%">
											<textarea id="<?= 'post_reply_' . $post_row['id'] . '_content' ?>" name="<?= 'post_reply_' . $post_row['id'] . '_content' ?>" class="post_reply_content" maxlength="300" placeholder="답글은 300자 이내로 작성해주세요" style="font-size:14px;height:35px;width: 100%;border: 1px;"></textarea>
										</div>
										<div style="width:35px">
											<button type="button" class="btn btn-link" style="font-size:14px;position: absolute; right: 1px; padding: 5px 12px;color:#99cc00" onclick="add_post_reply('<?= $meta_row['idx'] ?>','<?= $post_row['id'] ?>')">작성</button>
										</div>
									</div>
									<div style="border-bottom: 0px solid #dddddd;margin-left:60px">
										<span id="post_reply_status" name="post_reply_status" style="padding: 10px">0/300</span>
									</div>
								</div>
								<?
								$reply_sql = "select * from Gn_Iam_Post_Response r inner join Gn_Member m on r.mem_id = m.mem_id where r.post_idx = '{$post_row['id']}' order by r.reg_date";
								$reply_res = mysql_query($reply_sql);
								while ($reply_row = mysql_fetch_array($reply_res)) { ?>
									<div class="user-item" style="padding-left: 50px">
										<a href="/?<?= strip_tags($meta_row['westory_card_url']) ?>" class="img-box">
											<div class="user-img" style="margin: 5px;width:32px;height:32px;">
												<? if ($reply_row['profile']) { ?>
													<img src="<?= $reply_row['profile'] ?>" alt="">
												<? } else { ?>
													<img src="<?= $contents_avatar ?>" alt="">
												<? } ?>
											</div>
										</a>
										<div class="wrap">
											<span class="user-name">
												<?= $reply_row['mem_name'] ?>
											</span>
											<span class="user-name">
												<?= $reply_row['contents'] ?>
											</span>
											<span class="date">
												<?= $reply_row['reg_date'] ?>
											</span>
										</div>
									</div>
							<? }
							} ?>
						</div>
					</div>
				</div>
			</section>
		</main><!-- // 컨텐츠 영역 끝 -->

		<!-- <footer id="footer" style="text-align: center;">
			<?php
			if ($HTTP_HOST != "kiam.kr") {
			?>
				<a href="<?php echo $domainData['footer_link']; ?>"><img src="<?= $domainData['footer_logo'] == "" ? "img/common/logo.png" : cross_image($domainData['footer_logo']); ?>" alt="아이엠 푸터로고" width="100"></a>
			<?php } else { ?>
				<a href="/m/"><img src="img/common/logo.png" alt="아이엠 푸터로고" width="100"></a>
			<?php } ?>
		</footer> -->
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
				<a href="https://www.onestore.co.kr/userpoc/apps/view?pid=0000738690">둘째 내 폰의 문자를 보내려면 앱을
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
	var mobile = '<?= $_GET['mobile'] ?>';

	function show_order_option(cont_idx, type) {
		if (mobile == "Y") {
			window.parent.show_order_option(cont_idx, type);
			close_frame();
		} else {
			window.opener.show_order_option(cont_idx, type);
			window.close();
		}
	}

	function close_frame() {
		if (mobile == "Y") {
			window.parent.document.getElementById('contents_page').style.display = "none";
			window.parent.document.getElementById("wrap").style.display = "flow-root";
		} else {
			window.close();
		}
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

	function showpage(url) {
		if (url != "")
			$("#pagewrap").html('<iframe src="' + url + '" width="100%" height="600px" sandbox="allow-same-origin allow-scripts allow-popups allow-forms allow-top-navigation"></iframe>');
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
			"<?= $domainData['sub_domain'] . "/iam/contents.php?contents_idx =" . $contents_idx ?>";
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
			location.href = "sms:?body=<?= $domainData['sub_domain'] ?>/iam/contents.php?contents_idx=" + j_idx;
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
						mobileWebUrl: '<?= $domainData['sub_domain'] ?>/iam/contents.php?contents_idx=' + idx, // 모바일 카카오톡에서 사용하는 웹 링크 URL
						webUrl: '<?= $domainData['sub_domain'] ?>/iam/contents.php?contents_idx=' + idx // PC버전 카카오톡에서 사용하는 웹 링크 URL
					}
				},
				buttons: [{
					title: "우리 프렌즈해요!", // 버튼 제목
					link: {
						mobileWebUrl: '<?= $domainData['sub_domain'] ?>/iam/contents.php?contents_idx=' + idx, // 모바일 카카오톡에서 사용하는 웹 링크 URL
						webUrl: '<?= $domainData['sub_domain'] ?>/iam/contents.php?contents_idx=' + idx // PC버전 카카오톡에서 사용하는 웹 링크 URL
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
		var linkUrl = encodeURIComponent('<?= $domainData['sub_domain'] ?>/iam/contents.php?contents_idx=' + idx);
		window.open('http://www.facebook.com/sharer.php?u=' + linkUrl + '&t=' + text);
	}
	//콘텐츠 복사
	function contents_copy(idx) {
		var aux = document.createElement("input");
		// 지정된 요소의 값을 할당 한다.
		aux.setAttribute("value", "<?= $domainData['sub_domain'] ?>/iam/contents.php?contents_idx=" + idx);
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
	function contents_like(idx, like_id) {
		var formData = new FormData();
		formData.append("mode", "like");
		formData.append("contents_idx", idx);
		formData.append("like_id", like_id);
		$.ajax({
			type: "POST",
			url: "/iam/ajax/contents_like.proc.php",
			data: formData,
			contentType: false,
			processData: false,
			dataType: "json",
			success: function(data) {
				if (data.like_status == "Y") {
					toastr.success("성공되었습니다.", "좋아요");
					$("#like_img_" + idx).prop("src", "/iam/img/menu/icon_like_active.png");
				} else {
					toastr.success("삭제되었습니다.", "좋아요");
					$("#like_img_" + idx).prop("src", "/iam/img/menu/icon_like.png");
				}
				$(".like_" + idx).html(data.count + "개");
			}
		});
	}
	// 팝업 닫기 스크립트
	$('.popup-overlay, #closePopup').on('click', function() {
		$('.daily_popup').css('display', 'none');
		return false;
	});

	// 하단 콘텐츠 접기 펼치기 스크립트
	$('.content-item .arrow').on('click', function() {
		var desc = $(this).parents('.content-item').find('.desc-inner-content');
		desc.toggleClass('show_content');
		$(this).toggleClass('active')
		return false;
	});
	//동영상 플레이하기
	$('.movie_play').each(function() {
		$(this).on("click", function() {
			$(this).parent("div").find("video").trigger("play");
			$(this).hide();
		});
	});
	//탭하여 음소거 해지
	$('.unmute_btn').each(function() {
		$(this).on("click", function() {
			$(this).parent().find("video").prop("muted", false);
			$(this).hide();
		});
	});

	function show_post(content_idx) {
		if ($(".post_wrap" + content_idx).css('display') == "none") {
			$(".post_wrap" + content_idx).show();
			$("#show_post_img_" + content_idx).prop("src", "/iam/img/menu/icon_post_active.png");
			$.ajax({
				type: "POST",
				url: "ajax/add_post.php",
				dataType: "json",
				data: {
					content_idx: content_idx,
					mode: 'read',
					mem_id: '<?= $_SESSION['iam_member_id'] ?>'
				},
				success: function(data) {
					if (data.result == 'Y') {
						$('#post_alarm_' + content_idx).hide();
					}
				}
			});
		} else {
			$(".post_wrap" + content_idx).hide();
			$("#show_post_img_" + content_idx).prop("src", "/iam/img/menu/icon_post.png");
		}
	}

	function add_post(content_idx) { //댓글추가
		var post_content = $("#post_content" + content_idx);
		$.ajax({
			type: "POST",
			url: "ajax/add_post.php",
			dataType: "json",
			data: {
				content_idx: content_idx,
				mode: 'add',
				mem_id: '<?= $_SESSION['iam_member_id'] ?>',
				post_content: post_content.val()
			},
			success: function(data) {
				if (data.result == "success") {
					reloadShareCount(data.share_recv_count, data.share_send_count, data.share_post_count);
					$("#post_content" + content_idx).val("");
					$("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
					if (data.post_status > 0) {
						$('#post_alarm_' + content_idx).show();
						$('#post_alarm_' + content_idx).html(data.post_status);
					} else {
						$('#post_alarm_' + content_idx).hide();
					}
					$("#post_list_" + content_idx).empty();
					for (var i in data.contents) {
						add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name, data.contents[i].reg_date,
							data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
						for (var reply_index in data.contents[i].reply_content) {
							var reply_content = data.contents[i].reply_content[reply_index];
							//add_one_reply(content_idx ,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date, reply_content.post_content);
							add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
								reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
						}
					}
				} else {
					if (data.message == "login") {
						location.href = "login.php?contents_idx=" + content_idx;
					} else {
						alert(data.message);
					}
				}
			}
		})
	}

	function delete_post(content_idx, post_idx) { //댓글 삭제
		var post_content = $("#post_content" + content_idx);
		$.ajax({
			type: "POST",
			url: "ajax/add_post.php",
			dataType: "json",
			data: {
				content_idx: content_idx,
				post_idx: post_idx,
				mode: 'del',
				mem_id: '<?= $_SESSION['iam_member_id'] ?>'
			},
			success: function(data) {
				$("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
				if (data.post_status > 0) {
					$('#post_alarm_' + content_idx).show();
					$('#post_alarm_' + content_idx).html(data.post_status);
				} else {
					$('#post_alarm_' + content_idx).hide();
				}
				$("#post_list_" + content_idx).empty();
				for (var i in data.contents) {
					add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
						data.contents[i].reg_date, data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
					for (var reply_index in data.contents[i].reply_content) {
						var reply_content = data.contents[i].reply_content[reply_index];
						//add_one_reply(content_idx ,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date, reply_content.post_content);
						add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
							reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
					}
				}
			}
		});
	}

	function edit_post(content_idx, post_idx, content) {
		var post_content = $("#post_content" + content_idx);
		$("#post_content" + content_idx).val(content);
		var post_wrap = $("#post_content" + content_idx).parents(".post-wrap");
		var status_lbl = post_wrap.find("#post_status");
		status_lbl.html($("#post_content" + content_idx).val().length + '/300');
		post_wrap.find("#send_post").attr("onClick", "update_post(" + content_idx + "," + post_idx + ");");
	}

	function update_post(content_idx, post_idx) {
		var post_content = $("#post_content" + content_idx);
		$.ajax({
			type: "POST",
			url: "ajax/add_post.php",
			dataType: "json",
			data: {
				content_idx: content_idx,
				post_idx: post_idx,
				mode: 'edit',
				mem_id: '<?= $_SESSION['iam_member_id'] ?>',
				post_content: post_content.val()
			},
			success: function(data) {
				$("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
				if (data.post_status > 0) {
					$('#post_alarm_' + content_idx).show();
					$('#post_alarm_' + content_idx).html(data.post_status);
				} else {
					$('#post_alarm_' + content_idx).hide();
				}
				$("#post_list_" + content_idx).empty();
				for (var i in data.contents) {
					add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name, data.contents[i].reg_date,
						data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
					for (var reply_index in data.contents[i].reply_content) {
						var reply_content = data.contents[i].reply_content[reply_index];
						//add_one_reply(content_idx ,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date, reply_content.post_content);
						add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
							reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
					}
				}
			}
		});
		post_content.val("");
		var post_wrap = post_content.parents(".post-wrap");
		var status_lbl = post_wrap.find("#post_status");
		status_lbl.html($("#post_content" + content_idx).val().length + '/300');
		post_wrap.find("#send_post").attr("onClick", "add_post(" + content_idx + ");");
	}

	function add_post_reply(content_idx, post_idx) { //답글추가
		var post_content = $("#post_reply_" + post_idx + "_content");
		$.ajax({
			type: "POST",
			url: "ajax/add_post.php",
			dataType: "json",
			data: {
				content_idx: content_idx,
				post_idx: post_idx,
				mode: 'add_reply',
				mem_id: '<?= $_SESSION['iam_member_id'] ?>',
				post_content: post_content.val()
			},
			success: function(data) {
				if (data.result == "success") {
					$("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
					if (data.post_status > 0) {
						$('#post_alarm_' + content_idx).show();
						$('#post_alarm_' + content_idx).html(data.post_status);
					} else {
						$('#post_alarm_' + content_idx).hide();
					}
					$("#post_list_" + content_idx).empty();
					for (var i in data.contents) {
						add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
							data.contents[i].reg_date, data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
						for (var reply_index in data.contents[i].reply_content) {
							var reply_content = data.contents[i].reply_content[reply_index];
							//add_one_reply(content_idx ,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date, reply_content.post_content);
							add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
								reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
						}
					}
				} else {
					if (data.message == "login") {
						location.href = "login.php?contents_idx=" + content_idx;
					} else {
						alert(data.message);
					}
				}
			}
		})
	}

	function show_post_reply(post_idx) {
		if ($("#post_reply_" + post_idx).css('display') == "none")
			$("#post_reply_" + post_idx).show();
		else
			$("#post_reply_" + post_idx).hide();
	}

	function add_one_post(content_idx, post_idx, name_card, imglink, mem_name, reg_date, post_content, post_mem, login_mem, card_mem) {
		if (imglink == "")
			imglink = '<?= $contents_avatar ?>';
		var cont = "<div class=\"user-item\" id=\"post_reply" + post_idx + "\">" +
			"<a href=\"/?" + name_card + "\" class=\"img-box\">" +
			"<div class=\"user-img\" style=\"margin: 5px;width:32px;height:32px;\">" +
			"<img src=\"" + imglink + "\" >" +
			"</div>" +
			"</a>" +
			"<div class=\"wrap\" style=\"margin:10px 0px;\">" +
			"<span class=\"date\">" +
			mem_name + ' ' + reg_date +
			"</span>" +
			"<span class=\"user-name\">" +
			post_content +
			"</span>" +
			"</div>";
		if (post_mem == login_mem) {
			cont += "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">" +
				"<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">" +
				"<ul class=\"dropdown-menu namecard-dropdown \" style=\"background: white; color : black;top:10px;\">" +
				"<li>" +
				"<a href=\"javascript:void(0)\" onclick=\"edit_post('" + content_idx + "','" + post_idx + "','" + post_content + "')\" title=\"댓글 수정\">" +
				"<p>수정</p>" +
				"</a>" +
				"</li>" +
				"<li>" +
				"<a href=\"javascript:void(0)\" onclick=\"delete_post('" + content_idx + "','" + post_idx + "')\" title=\"댓글 삭제\">" +
				"<p>삭제</p>" +
				"</a>" +
				"</li>" +
				"</ul>" +
				"</div>";
		} else if (card_mem == login_mem) {
			cont += "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:0px\">" +
				"<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/main/custom.png\" style=\"height: 20px;\">" +
				"<ul class=\"dropdown-menu namecard-dropdown \" style=\"background: white; color : black;top:10px;\">" +
				"<li>" +
				"<a href=\"javascript:void(0)\" onclick=\"delete_post('" + content_idx + "','" + post_idx + "')\" title=\"댓글 삭제\">" +
				"<p>삭제</p>" +
				"</a>" +
				"</li>" +
				"<li>" +
				"<a href=\"javascript:void(0)\" onclick=\"lock_post('" + content_idx + "','" + post_idx + "')\" title=\"댓글 차단\">" +
				"<p>차단</p>" +
				"</a>" +
				"</li>" +
				"</ul>" +
				"</div>";
		}
		cont += "<div style=\"position: absolute;left: 60px;bottom: 0px\">" +
			"<span style=\"color: #bdbdbd;cursor:pointer;font-size:13px\" onclick=\"show_post_reply(" + post_idx + ");\">" +
			"답글달기" +
			"</span>" +
			"</div>" +
			"</div>" +
			"<div id = \"post_reply_" + post_idx + "\"  class = \"post_reply_wrap\" style=\"display: none;margin : 10px 0px\" >" +
			"<div style=\"display: flex;justify-content: flex-end;\">" +
			"<div style=\"margin-left:60px;margin-right:35px;width:100%\">" +
			"<textarea id = \"post_reply_" + post_idx + "_content\" name = \"post_reply_" + post_idx + "_content\" class=\"post_reply_content\" maxlength=\"300\" placeholder=\"답글은 300자 이내로 작성해주세요\" style=\"font-size:14px;height:35px;width: 100%;border: 1px;\"></textarea>" +
			"</div>" +
			"<div style=\"width:35px\">" +
			"<button type=\"button\" class=\"btn btn-link\" style=\"font-size:14px;position: absolute; right: 1px; padding: 5px 12px;color:#99cc00\" onclick=\"add_post_reply('" + content_idx + "','" + post_idx + "')\">작성</button>" +
			"</div>" +
			"</div>" +
			"<div style=\"border-bottom: 0px solid #dddddd;margin-left:60px\">" +
			"<span id = \"post_reply_status\" name = \"post_reply_status\" style=\"padding: 10px\">0/300</span>" +
			"</div>" +
			"</div>";
		$('#post_list_' + content_idx).append(cont);
	}

	function add_one_reply(content_idx, post_id, reply_id, namecard, profile, mem_name, reg_date, reply_content, post_mem, login_mem, card_mem) {
		if (profile == "")
			profile = '<?= $contents_avatar ?>';
		var cont = "<div class=\"user-item\" style=\"padding-left: 30px\">" +
			"<a href=\"/?" + namecard + "\" class=\"img-box\">" +
			"<div class=\"user-img\" style=\"margin: 5px;width:32px;height:32px;\">" +
			"<img src=\"" + profile + "\">" +
			"</div>" +
			"</a>" +
			"<div class=\"wrap\">" +
			"<span class=\"date\">" +
			mem_name + "&nbsp;&nbsp;&nbsp;" + reg_date +
			"</span>" +
			"<span class=\"user-name\" id=\"reply_list_" + reply_id + "\">" +
			reply_content +
			"</span>" +
			"</div>";
		if (post_mem == login_mem) {
			cont += "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">" +
				"<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">" +
				"<ul class=\"dropdown-menu namecard-dropdown\" style=\"background: white; color : black;top:10px;\">" +
				"<li>" +
				"<a href=\"javascript:void(0)\" onclick=\"edit_post_reply('" + content_idx + "','" + post_id + "','" + reply_id + "','" + reply_content + "')\" title=\"답글 수정\">" +
				"<p>수정</p>" +
				"</a>" +
				"</li>" +
				"<li>" +
				"<a href=\"javascript:void(0)\" onclick=\"delete_post_reply('" + content_idx + "','" + reply_id + "')\" title=\"답글 삭제\">" +
				"<p>삭제</p>" +
				"</a>" +
				"</li>" +
				"</ul>" +
				"</div>";
		} else if (card_mem == login_mem) {
			cont += "<div class=\"dropdown\" style=\"top : 10px;position : absolute;right:30px;height:24px\">" +
				"<img class=\"dropdown-toggle\" data-toggle=\"dropdown\" src=\"/iam/img/menu/icon_dot.png\" style=\"height: 24px;\">" +
				"<ul class=\"dropdown-menu namecard-dropdown\" style=\"background: white; color : black;top:10px;\">" +
				"<li>" +
				"<a href=\"javascript:void(0)\" onclick=\"delete_post_reply('" + content_idx + "','" + reply_id + "')\" title=\"답글 삭제\">" +
				"<p>삭제</p>" +
				"</a>" +
				"</li>" +
				"<li>" +
				"<a href=\"javascript:void(0)\" onclick=\"lock_post_reply('" + content_idx + "','" + reply_id + "')\" title=\"답글 차단\">" +
				"<p>차단</p>" +
				"</a>" +
				"</li>" +
				"</ul>" +
				"</div>";
		}
		cont += "</div>";
		$('#post_list_' + content_idx).append(cont);
	}

	function refresh_post(content_idx) { //refresh
		$.ajax({
			type: "POST",
			url: "ajax/add_post.php",
			dataType: "json",
			data: {
				content_idx: content_idx,
				mode: 'refresh',
				mem_id: '<?= $_SESSION['iam_member_id'] ?>'
			},
			success: function(data) {
				$("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
				if (data.post_status > 0) {
					$('#post_alarm_' + content_idx).show();
					$('#post_alarm_' + content_idx).html(data.post_status);
				} else {
					$('#post_alarm_' + content_idx).hide();
				}
				$("#post_list_" + content_idx).empty();
				for (var i in data.contents) {
					add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
						data.contents[i].reg_date, data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
					for (var reply_index in data.contents[i].reply_content) {
						var reply_content = data.contents[i].reply_content[reply_index];
						//add_one_reply(content_idx ,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date, reply_content.post_content);
						add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
							reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
					}
				}
			},
			error: function(data) {
				//console.log(data);
			}
		})
	}

	function lock_post(content_idx, post_idx) { //댓글 차단
		var post_content = $("#post_content" + content_idx);
		$.ajax({
			type: "POST",
			url: "ajax/add_post.php",
			dataType: "json",
			data: {
				content_idx: content_idx,
				post_idx: post_idx,
				mode: 'lock',
				mem_id: '<?= $_SESSION['iam_member_id'] ?>'
			},
			success: function(data) {
				$("#post_count_" + content_idx).html(data.post_count + "개의 댓글  &#x21BA;");
				if (data.post_status > 0) {
					$('#post_alarm_' + content_idx).show();
					$('#post_alarm_' + content_idx).html(data.post_status);
				} else {
					$('#post_alarm_' + content_idx).hide();
				}
				$("#post_list_" + content_idx).empty();
				for (var i in data.contents) {
					add_one_post(content_idx, data.contents[i].post_idx, data.contents[i].namecard, data.contents[i].profile, data.contents[i].mem_name,
						data.contents[i].reg_date, data.contents[i].post_content, data.contents[i].post_mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
					for (var reply_index in data.contents[i].reply_content) {
						var reply_content = data.contents[i].reply_content[reply_index];
						//add_one_reply(content_idx ,reply_content.namecard,reply_content.profile,reply_content.mem_name,reply_content.reg_date, reply_content.post_content);
						add_one_reply(content_idx, data.contents[i].post_idx, reply_content.idx, reply_content.namecard, reply_content.profile, reply_content.mem_name, reply_content.reg_date,
							reply_content.post_content, reply_content.mem_id, data.contents[i].mem_id, data.contents[i].card_mem_id);
					}
				}
			}
		});
	}

	function hide_post_popup() {
		$("#post_popup").modal("hide");
		var content_idx = $("#post_popup").find("#content_idx").val();
		if ($(".post_wrap" + content_idx).css('display') == "none") {
			$(".post_wrap" + content_idx).show();
			$.ajax({
				type: "POST",
				url: "ajax/add_post.php",
				dataType: "json",
				data: {
					content_idx: content_idx,
					mode: 'read',
					mem_id: '<?= $_SESSION['iam_member_id'] ?>'
				},
				success: function(data) {
					if (data.result == 'Y') {
						$('#post_alarm_' + content_idx).hide();
					}
				}
			});
		} else
			$(".post_wrap" + content_idx).hide();

	}
	$('.post_content').on('keyup', function() {
		var post_wrap = $(this).parents(".post-wrap");
		var status_lbl = post_wrap.find("#post_status");
		status_lbl.html($(this).val().length + '/300');
	});
	$('.post_content').on('focus', function() {
		var content_idx = $("#post_popup").find("#content_idx").val();
		if ($("#mem_id").val() == "") {
			location.href = "login.php?contents_idx=" + content_idx;
			return;
		}
	});
	$('.post_reply_content').on('keyup', function() {
		var post_wrap = $(this).parents(".post_reply_wrap");
		var status_lbl = post_wrap.find("#post_reply_status");
		status_lbl.html($(this).val().length + '/300');
	});
	$('.post_reply_content').on('focus', function() {
		var content_idx = $("#post_popup").find("#content_idx").val();
		if ($("#mem_id").val() == "") {
			location.href = "login.php?contents_idx=" + content_idx;
			return;
		}
	});
	/*function showIframeModal(url){
		window.open(url, "","toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=600");
	}*/
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

	function reloadShareCount(share_recv_count, share_send_count, share_post_count) {
		var share_count = share_recv_count * 1 + share_send_count * 1 + share_post_count * 1;
		if (share_count == 0) {
			$('#share_count').hide();
			$('#share_recv_count').hide();
			$('#share_send_count').hide();
			$('#share_post_count').hide();
		} else {
			$('#share_count').html(share_count);
			$('#share_count').show();
			if (share_recv_count <= 0) {
				$('#share_recv_count').hide();
			} else {
				$('#share_recv_count').html(share_recv_count);
				$('#share_recv_count').show();
			}

			if (share_send_count <= 0) {
				$('#share_send_count').hide();
			} else {
				$('#share_send_count').html(share_send_count);
				$('#share_send_count').show();
			}

			if (share_post_count <= 0) {
				$('#share_post_count').hide();
			} else {
				$('#share_post_count').html(share_post_count);
				$('#share_post_count').show();
			}
		}
	}
	//end	
</script>

</html>