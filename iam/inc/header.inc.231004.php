<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$code = whois_ascc($whois_api_key, $_SERVER['REMOTE_ADDR']);
$language_index = $_COOKIE[language];
if($language_index == "") {
	$language_index = 1;
	@setcookie("language", $language_index, time()+3600);
}
$language_sql = "select * from Gn_Iam_multilang where no = '$language_index'";
$language_res = mysqli_query($self_con,$language_sql);
$language_row = mysqli_fetch_array($language_res);
$lang = $_COOKIE['lang']?$_COOKIE['lang']:"kr";
$sql = "select * from Gn_Iam_lang ";
$result = mysqli_query($self_con,$sql);
while($row = mysqli_fetch_array($result)) {
	$MENU[$row[menu]][$row[pos]] = $row[$lang];
}

$mall_reg_ids = explode(",", get_search_key('mall_reg_menu_ids'));
if(in_array($_SESSION['iam_member_id'], $mall_reg_ids)){
    $mall_reg_state = 1;
}
else{
    $mall_reg_state = 0;
}

$Gn_contents_limit = get_search_key('people_except_count');

$Gn_contents_link_limit = get_search_key('contents_link_limit');

$point_ai = get_search_key('ai_card_making');

$group_card_point = get_search_key('group_card_point');

$new_open_url = get_search_key('cont_modal_new_open');

$cart_cnt = $Gn_point = 0;

$mid = date("YmdHis").rand(10,99);
$sql_point = "select mem_point, mem_cash from Gn_Member where mem_id='{$_SESSION['iam_member_id']}'";
$result_point = mysqli_query($self_con,$sql_point);
$row_point = mysqli_fetch_array($result_point);
$Gn_point = $row_point['mem_point'];
$Gn_cash = $row_point['mem_cash'];
$Gn_auto_point = 0;
$contents_count_per_page = 20;
$pagination_count = 10;
$send_ids = "";
$send_ids_cnt = 0;
$alarm_time = get_search_key('content_alarm_time');
if($alarm_time == "")
    $alarm_time = 10;
$contents_box_alarm = get_search_key('bring_web_address');

$card_url = "http://" . $HTTP_HOST . $_SERVER['REQUEST_URI'];
$card_title = $_SERVER['REQUEST_URI'];
$val1 = explode("?", $card_title);
$card_title = trim(substr(trim($val1[1]), 0, 10));

$card_send_point = get_search_key('card_send_point');

$contents_send_point = get_search_key('contents_send_point');

$contents_point_help = get_search_key('contents_point_help');

$wecon_zy_count = get_search_key('wecon_zy_count');

$open_domain = get_search_key('open_blank_cont_domain');

$gwc_req_alarm = get_search_key('gwc_req_alarm');
?>
<?
if ($HTTP_HOST != "kiam.kr") //분양사사이트이면
    $query = "select * from Gn_Iam_Service where sub_domain like 'http://" . $HTTP_HOST . "'";
else
    $query = "select * from Gn_Iam_Service where sub_domain like 'http://www.kiam.kr'";
$res = mysqli_query($self_con,$query);
$domainData = mysqli_fetch_array($res);
$first_card_idx = $domainData['profile_idx'];//분양사의 1번 카드아이디
$sql = "select * from Gn_Iam_Name_Card where idx = '$first_card_idx'";
$result = mysqli_query($self_con,$sql);
$main_card_row = mysqli_fetch_array($result);
$first_card_url = $main_card_row[card_short_url];//분양사이트 1번 네임카드 url

$sql = "select site_iam,mem_code from Gn_Member where mem_id = '{$main_card_row['mem_id']}'";
$result = mysqli_query($self_con,$sql);
$row = mysqli_fetch_array($result);
$bunyang_site = $row['site_iam'];
$bunyang_site_manager_code = $row['mem_code'];
if ($_SESSION['iam_member_id']) {
    $Gn_mem_row = $member_iam;
    $user_site = $Gn_mem_row['site_iam'];//아이엠분양사명
    $Gn_point = $Gn_mem_row['mem_point'];
	$Gn_cash = $Gn_mem_row['mem_cash'];

    $query = "select * from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' order by req_data limit 0,1";
    $result = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($result);
	$request_short_url = $row['card_short_url'];

	$sql_cart_cnt = "select count(*) from Gn_Gwc_Order where mem_id='{$_SESSION['iam_member_id']}' and page_type=1";
    $res_cart_cnt = mysqli_query($self_con,$sql_cart_cnt);
    $row_cart_cnt = mysqli_fetch_array($res_cart_cnt);
    $cart_cnt = $row_cart_cnt[0];
}else{
    $request_short_url = $main_card_row[card_short_url];
}
$date = date("Y-m-d H:i:s");
if(strpos($_SERVER['REQUEST_URI'], 'mypage_payment_item.php') === false && strpos($_SERVER['REQUEST_URI'], 'mypage_payment.php') === false && strpos($_SERVER['REQUEST_URI'], 'mypage_refer.php') === false && strpos($_SERVER['REQUEST_URI'], 'mypage_post_lock.php') === false){
	$mypage = 0;
}
else{
	$mypage = 1;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<!--meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"-->
	<link rel="shortcut icon" href="img/common/icon-os.ico">
	<!--오픈그래프 (웹페이지 미리보기 -페이스북, 카카오톡)-->
	<meta property="og:title" content="아이엠으로 나를 브랜딩하기">
	<!--이미지-->
	<title>아이엠으로 홍보와 소통이 가능해요</title>
    <link rel="shortcut icon" href="img/common/iconiam.ico">
    <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="css/notokr.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/new_style.css">
    <link rel="stylesheet" href="css/grid.min.css">
    <link rel="stylesheet" href="css/slick.min.css">
    <link rel="stylesheet" href="css/style_j.css">
    <link rel="stylesheet" href="../iam/inc/css/header.inc.css">
    <link rel='stylesheet' href='/plugin/toastr/css/toastr.css' />
    <!-- ########## TODO COMMENT FOR TEST  패치할떄 해제해야함 ###########  -->
	<script src="//developers.kakao.com/sdk/js/kakao.min.js" charset="utf-8"></script>
	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="js/slick.min.js"></script>
	<script src="js/main.js"></script>
	<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
	<script src="/js/rlatjd_fun.js"></script>
	<script src="/js/rlatjd.js"></script>
	<script src='/plugin/toastr/js/toastr.min.js'></script>
    <script type="text/javascript" src="/jquery.lightbox_me.js"></script>
	<script>
		<?if(strpos($_SERVER['REQUEST_URI'], 'gpt_chat.php') === false){?>
		$(function(){
            $(document).ajaxStart(function() {
                $("#ajax-loading").show();
            })
            .ajaxStop(function() {
                //$("#ajax-loading").delay(10).hide(1);
            });
		});
		<?}?>
		function addslashes(string) {
			return string.replace(/\\/g, '\\\\').
				replace(/\u0008/g, '\\b').
				replace(/\t/g, '\\t').
				replace(/\n/g, '\\n').
				replace(/\f/g, '\\f').
				replace(/\r/g, '\\r').
				replace(/'/g, '\\\'').
				replace(/"/g, '\\"');
		}
		function set_language(idx){
			setCookie("lang",idx,1,"iam");
			if((window.location+"").indexOf("?") >=0 )
				location = window.location+"&lang="+idx;
			else
				location = window.location+"?<?=$request_short_url?>&lang="+idx;
		}
		// function iam_mystory(url) {
		// 	//iam_count('iam_mystory');
		// 	location.href = "/?" + url;
		// }
		function iam_mystory(url) {
			if(url == "cur_win=unread_notice"){
				$.ajax({
					type: "POST",
					url: "/iam/card_con_send.php",
					data: {settle_type:'read_all', mem_id:'<?=$_SESSION['iam_member_id']?>'},
					dataType: "json",
					success: function(data) {

					}
				});
				location.href = "/?" + url + "&modal=Y";
			}else if(url.indexOf("type=image") != -1){
				// iam_count('iam_mystory');
				setCookie('contents_mode','image', 1,"");
				location.href = "/?" + url;
			}else if(url.indexOf("type=pin") != -1){
				// iam_count('iam_mystory');
				setCookie('contents_mode','pin', 1,"");
				location.href = "/?" + url;
			}
			else if(url == "cur_win=request_list"){
				location.href = "/?" + url;
			}else{
				// iam_count('iam_mystory');
				location.href = "/?" + url;
			}
		}
		function search_clicked(cur_win) {
			var parent = $(".popup_box2[id="+cur_win+"]");
			var inputVal = parent.find(".contents_search_input").val();
			iam_mystory('<?=$request_short_url?>&cur_win='+cur_win+'&search_key=' + inputVal );
		}
		$(document).on("keypress", ".contents_search_input", function(e) {
			if (e.which == 13) {
				var parent = $(this).parents(".popup_box2");
				var cur_win = parent.prop("id");
				var inputVal = $(this).val();
				iam_mystory('<?=$request_short_url?>&cur_win='+cur_win+'&search_key=' + inputVal);
			}
		});
		function addMainBtn(title, link) {
			var filter = "win16|win32|win64|mac";
			if(navigator.platform){
				if(0 > filter.indexOf(navigator.platform.toLowerCase())){
				}else{
					//alert('모바일에서만 추가가 가능합니다.');
					var bookmarkURL = window.location.href;
					var bookmarkTitle = document.title;
					var triggerDefault = false;
					if (window.sidebar && window.sidebar.addPanel) {
						// Firefox version < 23
						window.sidebar.addPanel(bookmarkTitle, bookmarkURL, '');
					} else if ((window.sidebar && (navigator.userAgent.toLowerCase().indexOf('firefox') > -1)) || (window.opera && window.print)) {
						// Firefox version >= 23 and Opera Hotlist
						var $this = $(this);
						$this.attr('href', bookmarkURL);
						$this.attr('title', bookmarkTitle);
						$this.attr('rel', 'sidebar');
						$this.off(e);
						triggerDefault = true;
					} else if (window.external && ('AddFavorite' in window.external)) {
						// IE Favorite
						window.external.AddFavorite(bookmarkURL, bookmarkTitle);
					} else {
						// WebKit - Safari/Chrome
						alert((navigator.userAgent.toLowerCase().indexOf('mac') != -1 ? 'Cmd' : 'Ctrl') + '+D 키를 눌러 즐겨찾기에 등록하실 수 있습니다.');
					}
					//return triggerDefault;
					return;
				}
			}
			var url = link;
			var encodeTitle = encodeURIComponent(title);
			var linkUrl = encodeURIComponent(url);
			var iconUrl = "http://kiam.kr/iam/img/favicon_iam.png";
			setIcon = encodeURIComponent(iconUrl);
			var sm_naver_customUrlScheme = "naversearchapp://addshortcut?url=" + linkUrl + "&icon=" + setIcon + "&title=" + encodeTitle + "&serviceCode=nstore&version=7";

			var userAgent = navigator.userAgent.toLowerCase();
			var iPhone = userAgent.indexOf("iphone");
			var iPad = userAgent.indexOf("ipad");
			var IOS = iPhone + iPad;
			if (IOS == -2) {
				alert(title + '을(를) 홈화면에 추가합니다.\n\n네이버앱이 없는 고객 네이버앱 설치페이지로 이동됩니다.!!');
				window.open(sm_naver_customUrlScheme);
			} else {
				alert("IOS는 직접 홈버튼추가를 사용하셔야합니다.");
			}
		}
		function go_cart(){
			location.href = "/iam/gwc_order_cart.php";
		}
		panelGroupState();
    </script>
</head>
<body>
	<div id="wrap" class="common-wrap">
		<header id="header" style="position: fixed; z-index: 100; width:100%;max-width: 768px;">
			<!-- 헤더 시작 -->
			<div class="container J_elem" style="border-bottom:1px solid #ddd">
				<div class="row" style="margin-left: auto;margin-right: auto;overflow: hidden">
					<div class="col-12">
                        <div class="inner-wrap" style = "padding : 0px">
							<!--상단 좌측 로고이미지부분-->
							<a href="<?=$domainData[sub_domain].'/?'.$first_card_url.$bunyang_site_manager_code?>" target = "blank" style="float:left;">
								<img src="/iam/img/common/logo-2.png" alt="온리원아이엠 로고 이미지" style="margin-top:15px;height:18px;">
							</a> 
							<!--상단 로고이미지 부분-->
							<? $home_link=$domainData['home_link'] == ''?$domainData[sub_domain].'/?'.$first_card_url.$bunyang_site_manager_code:$domainData['home_link'];?>
							<div class="check-item" style="margin-top:13px;position: absolute;left:50%;top:5px;transform:translate(-50%,-50%);height:35px">
                            <a href="<?=$home_link?>" target = "_self">
                                <?if($HTTP_HOST != "kiam.kr") {?>
                                    <img src="<?=$domainData[head_logo] == ''?'/iam/img/common/logo-2.png':cross_image($domainData[head_logo]);?>" alt="온리원아이엠 로고 이미지" style="margin-top:5px;height:35px;">
                                <?}else{?>
                                    <img src="/iam/img/common/logo-3.png" alt="온리원아이엠 로고 이미지" style="height:35px;">
                                <?php }?>
                            </a>
                        </div>
                        </div>
                        <!--상단 우측 프로필 부분-->
						<div class="dropdown right" style="position:absolute;right:15px;top:10px;float:right;">
							<img class="dropdown-toggle" data-toggle="dropdown" src="/iam/img/menu/icon_top_menu.png" style="width:24px;height:24px">
							<ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;top:10px;">
								<li style="text-align: left;">
									<a class='top_menu_font' href="/ma.php" target = "_blank">
										<img src="/iam/img/menu/icon_bottom_home.png" title="셀링홈으로 가기" style="height: 20px"><?="셀링홈으로 가기"?>
									</a>
								</li>
								<li style="text-align: left;">
									<a class='top_menu_font' href="javascript:addMainBtn('<?=str_replace("'","",$cur_card['card_name'])?>','?<?=$request_short_url.$card_owner_code?>');">
										<img src="/iam/img/menu/icon_home_add.png" title="<?=$MENU['IAM_CARD_M']['CM1_TITLE'];?>" style="height: 20px"><?="폰 홈화면에 추가"?>
									</a>
								</li>
								<li style="text-align: left;">
									<a class='top_menu_font' href="https://tinyurl.com/hb2pp6n2" target = "_blank">
										<img src="/iam/img/menu/icon_help.png" title="<?=$MENU['IAM_CARD_M']['CM1_TITLE'];?>" style="height: 20px"><?="이용매뉴얼"?>
									</a>
								</li>
								<li style="border-bottom:1px solid #ddd;text-align: left;">
									<a class='top_menu_font' href="<?=$domainData[kakao]?>" target="_blank">
										<img src="/iam/img/menu/icon_ask.png" title="<?=$MENU['IAM_CARD_M']['CM1_TITLE'];?>" style="height: 20px"><?="관리자에게 문의"?>
									</a>
								</li>
								<!-- <li style="text-align: left;">
									<a onclick="downloadURI()" target = "_blank">
										<img src="/iam/img/menu/icon_install.png" title="<?=$MENU['IAM_CARD_M']['CM1_TITLE'];?>" style="height: 20px"><?="IAM 앱 설치(APK다운)"?>
									</a>
								</li> -->
								<li style="text-align: left;">
									<a class='top_menu_font' href="https://play.google.com/store/apps/details?id=mms.onepagebook.com.onlyonesms" target = "_blank">
										<img src="/iam/img/menu/icon_install.png" title="<?=$MENU['IAM_CARD_M']['CM1_TITLE'];?>" style="height: 20px"><?="IAM앱설치하기"?>
									</a>
								</li>
								<?if($is_pay_version){
									if($_SESSION['iam_member_subadmin_id'] && $domainData[pay_link]){//payment ?>
										<li style="border-bottom:1px solid #ddd;text-align: left;">
											<a class='top_menu_font' href="<?= $domainData[pay_link] ?>" target="_self">
												<img src="/iam/img/menu/icon_pay.png" title="<?=$MENU['IAM_CARD_M']['CM1_TITLE'];?>" style="height: 20px"><?="IAM 플랫폼 결제"?>
											</a>
										</li>
									<? }else{?>
										<li style="border-bottom:1px solid #ddd;text-align: left;">
											<a class='top_menu_font' href="/iam/pay.php" target="_self">
												<img src="/iam/img/menu/icon_pay.png" title="<?=$MENU['IAM_CARD_M']['CM1_TITLE'];?>" style="height: 20px"><?="IAM 플랫폼 결제"?>
											</a>
										</li>
									<?  }
									}
								?>
								
								<!-- <li style="text-align: left;">
								<?if($_SESSION['iam_member_id']){?>
									<a class="top_menu_font" href="javascript:openShop()">
								<?}else{?>
									<a class="top_menu_font" href="/iam/login.php">
								<?}?>
										<img src="/iam/img/menu/icon_mall.png" title="IAM몰로 가기" style="height: 20px"><?="IAM몰로 가기"?>
									</a>
								</li> -->
								<li style="text-align: left;">
								<?if($_SESSION['iam_member_id']){?>
									<a class="top_menu_font" href="javascript:iam_mystory('cur_win=group-con');">
								<?}else{?>
									<a class="top_menu_font" href="/iam/login.php">
								<?}?>
										<img src="/iam/img/menu/icon_group.png" title="그룹페이지로 가기" style="height: 18px"><?="그룹페이지로 가기"?>
									</a>
								</li>
								<!--li>
									<a href="javascript:addMainBtn('<?=str_replace("'","",$cur_card['card_name'])?>','?<?=$request_short_url.$card_owner_code?>');">
										<img src="/iam/img/menu/icon_language.png" title="<?=$MENU['IAM_CARD_M']['CM1_TITLE'];?>" style="height: 20px"><?="언어 : 한국어"?>
									</a>
								</li>
								<li>
									<a href="javascript:addMainBtn('<?=str_replace("'","",$cur_card['card_name'])?>','?<?=$request_short_url.$card_owner_code?>');">
										<img src="/iam/img/menu/icon_position.png" title="<?=$MENU['IAM_CARD_M']['CM1_TITLE'];?>" style="height: 20px"><?="위치 : 한국"?>
									</a>
								</li>
								<li>
									<a href="javascript:addMainBtn('<?=str_replace("'","",$cur_card['card_name'])?>','?<?=$request_short_url.$card_owner_code?>');">
										<img src="/iam/img/menu/icon_setting.png" title="<?=$MENU['IAM_CARD_M']['CM1_TITLE'];?>" style="height: 20px"><?="설정"?>
									</a>
								</li>
								<li>
									<a href="javascript:addMainBtn('<?=str_replace("'","",$cur_card['card_name'])?>','?<?=$request_short_url.$card_owner_code?>');">
										<img src="/iam/img/menu/icon_feedback.png" title="<?=$MENU['IAM_CARD_M']['CM1_TITLE'];?>" style="height: 20px"><?="의견 보내기"?>
									</a>
								</li-->
							</ul>
						</div>
					</div>
				</div>
				<nav id="middle-nav">
					<!-- 중단 네비게이션 시작 -->
					<ul style="background:white">
						<li onclick="iam_mystory('<?=$request_short_url?>&type=image')" class="nav-item top" title="[마이콘텐츠] 나를 소개하는 콘텐츠만 보입니다.">
							<img src="/iam/img/menu/icon_myinfo.png" class="iconperson">
						</li>
						<li onclick="iam_mystory('<?=$request_short_url?>&cur_win=we_story&type=pin&w_page=1#bottom')" title="[위콘텐츠] 모든 아이엠 이용자의 콘텐츠를 한꺼번에 봅니다." class="nav-item top">
							<img src="/iam/img/menu/icon_westory.png" class="iconperson">
						</li>
						<?if($_SESSION['iam_member_id']){?>
						<li onclick="gwc_tab()" class="nav-item top" <?if($_GET[key1] == "4") echo "style='border-bottom:2px solid #99cc00'"?>>
						<?}else{?>
						<li onclick="location.href='/iam/login.php'" class="nav-item top" <?if($_GET[key1] == "4") echo "style='border-bottom:2px solid #99cc00'"?>>
						<?}?>
							<?if($_GET[key1] == "4") {?>
								<img src="/iam/img/menu/icon_mall_active.png" class="iconperson">
							<?}else{?>
								<img src="/iam/img/menu/icon_mall.png" class="iconperson">
							<?}?>
						</li>
						<li onclick="callya_tab()" class="nav-item top" <?if($_GET[key1] == "3") echo "style='border-bottom:2px solid #99cc00'"?>>
							<?if($_GET[key1] == "3") {?>
								<img src="/iam/img/menu/icon_calliya_active.png" class="iconperson">
							<?}else{?>
								<img src="/iam/img/menu/icon_calliya.png" class="iconperson">
							<?}?>
						</li>
						<li onclick="iam_mystory('<?=$request_short_url?>&cur_win=unread_notice#bottom')" title="[수발신콘텐츠] 내가 수신받거나 전송한 콘텐츠입니다." class="nav-item top" <?if($mypage) echo "style='border-bottom:2px solid #99cc00'"?>>
						<?if($mypage){?>
							<img src="/iam/img/menu/icon_alarm_active.png" class="iconperson">
						<?}else{?>
							<img src="/iam/img/menu/icon_alarm.png" class="iconperson">
						<?}?>
							<label class="label label-sm share_count" style="display: none"></label>
						</li>
						<li onclick="openShop()" class="nav-item top" title="" <?if(strstr($cur_win,"iam_mall")) echo "style='border-bottom:2px solid #99cc00'"?>>
						<?if(strstr($cur_win,"iam_mall")) {?>
							<img src="/iam/img/menu/icon_withyou_active.png" class="iconperson">
						<?}else{?>
							<img src="/iam/img/menu/icon_withyou.png" class="iconperson">
						<?}?>  
						</li>
					</ul>
				</nav><!-- // 중단 네비게이션 끝 -->
				<?if(strpos($_SERVER['REQUEST_URI'], 'gwc_order_list.php') !== false || strpos($_SERVER['REQUEST_URI'], 'gwc_order_change_list.php') !== false){?>
				<div class="panel-group" style="border: 1px solid lightgrey;">
					<div style="margin: 5px;display:flex;justify-content: space-between;">
						<div class="mypage_menu" style="">
							<div style="margin-right: 5px;display:flex;float: right;">
								<button class="btn  btn-link" onclick="iam_mystory('cur_win=shared_receive&modal=Y')" title = "<?=$MENU['IAM_MENU']['M7_TITLE'];?>" style="display:flex;padding:6px 3px">
									<p style="font-size:14px;color:black">콘수신</p>
									<label class="label label-sm" id = "share_recv_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
								</button>
								<button class="btn  btn-link" onclick="iam_mystory('cur_win=shared_send&modal=Y')" title = "<?=$MENU['IAM_MENU']['M8_TITLE'];?>" style="display:flex;padding:6px 3px">
									<p style="font-size:14px;color:black">콘전송</p>
									<label class="label label-sm" id = "share_send_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
								</button>
								<button class="btn  btn-link" onclick="iam_mystory('cur_win=unread_post')" title = "<?='댓글알림'?>" style="display:flex;padding:6px 3px">
									<p style="font-size:14px;color:black">댓글수신</p>
									<label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
								</button>
								<a class="btn  btn-link" href="/iam/mypage_post_lock.php" title = "<?='댓글알림'?>" style="display:flex;padding:6px 3px">
									<p style="font-size:14px;color:black">댓글차단해지</p>
									<label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
								</a>
								<button class="btn  btn-link" onclick="iam_mystory('cur_win=request_list')" title = "<?='신청알림'?>" style="display:flex;padding:6px 3px">
									<p style="font-size:14px;color:black">이벤트신청</p>
									<label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
								</button>
							</div>
							<div style="margin-right: 5px;display:flex;float:right;">
								<a class="btn  btn-link" title = "" href="/iam/gwc_order_list.php" style="display:flex;padding:6px 3px">
									<p style="font-size:14px;color:#99cc00">주문목록</p>
									<label class="label label-sm" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
								</a>
								<?if($_SESSION['iam_member_subadmin_id'] == $_SESSION['iam_member_id']){?>
								<a class="btn  btn-link" title = "<?='공지알림';?>" href="/?cur_win=unread_notice&box=send&modal=Y" style="display:flex;padding:6px 3px">
									<p style="font-size:14px;color:black">공지전송</p>
									<label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
								</a>
								<a class="btn  btn-link" title = "<?='공지알림';?>" href="/?cur_win=unread_notice&modal=Y" style="display:flex;padding:6px 3px">
									<p style="font-size:14px;color:black">공지수신</p>
									<label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
								</a>
								<?}else{?>
								<button class="btn  btn-link" title = "<?='공지알림';?>" onclick="iam_mystory('cur_win=unread_notice')" style="display:flex;padding:6px 3px">
									<p style="font-size:14px;color:black">공지</p>
									<label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
								</button>
								<?}?>
								<?if($is_pay_version){?>
								<a class="btn  btn-link" title = "" href="/iam/mypage_refer.php" style="display:flex;padding:6px 3px">
									<p style="font-size:14px;color:black">추천</p>
									<label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
								</a>
								<a class="btn  btn-link" title = "" href="/iam/mypage_payment.php" style="display:flex;padding:6px 3px">
									<p style="font-size:14px;color:black">결제</p>
									<label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
								</a>
								<a class="btn  btn-link" title = "" href="/iam/mypage_payment_item.php" style="display:flex;padding:6px 3px">
									<p style="font-size:14px;color:black">판매</p>
									<label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
								</a>
								<?}?>
                                <?if($member_iam[service_type] < 2){
                                    $report_link = "/iam/mypage_report_list.php";
                                }else{
                                    $report_link = "/iam/mypage_report.php";
                                }
                                ?>
								<a class="btn  btn-link" title = "" href="<?=$report_link?>" style="display:flex;padding:6px 3px">
									<p style="font-size:14px;color:black">리포트</p>
									<label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
								</a>
								<a class="btn  btn-link" title = "" href="/?cur_win=unread_notice&req_provide=Y" style="display:flex;padding:6px 3px">
									<p style="font-size:14px;color:black">공급사신청</p>
									<label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
								</a>
							</div>
						</div>
					</div>
				</div>
				<?}?>
			</div>
		</header><!-- // 헤더 끝 -->
		<!-- // 헤더 끝 -->
        <div id="mypage-modalwindow" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog modal-sm" role="document" style="margin-top: 0px;width:70%;max-width : 500px;min-height:100%;margin-right:0px !important;margin-left : auto !important;">
				<div class="modal-content" style = "margin-right:0px;">
					<div class="modal-header" style="border:none;background: #f5f5f5;">
						<div style="margin-top:20px;margin-left:15px;">
							<img src="/iam/img/common/logo-2.png" style="height:35px;padding-top: 0px !important">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true" ><img src = "/iam/img/main/close.png" style="width:24px;margin-top: -20px;" class="close" data-dismiss="modal" aria-hidden="true"></button>
						</div>
						
						<?if($_SESSION['iam_member_id']) {?>
						<div style="margin-top:30px;margin-left:15px;display:flex;position:relative">
						<?  if($user_mem_code == $card_owner_code){
								if($member_iam[profile]){
									if(strstr($member_iam[profile], "kiam")) {
										$member_iam[profile] = str_replace("http://kiam.kr", "", $member_iam[profile]);
										$member_iam[profile] = str_replace("http://www.kiam.kr", "", $member_iam[profile]);
									} 
									if(!strstr($member_iam[profile], "http") && $member_iam[profile]) {
										$image_link = $cdn_ssl.$member_iam[profile];
									}else{
										$image_link = $member_iam[profile];
									}
									$image_link = cross_image($image_link);?>
									<div style="margin:0;width:70px;height:70px;border-radius: 50%;overflow: hidden">
										<img src="<?=$image_link?>" style="width:100%;height:100%;object-fit: cover;">
									</div>
							<?  }else{?>
									<div style="margin:0;background:<?=$profile_color?>;padding:5px 0px;width:70px;height:70px;border-radius: 50%;overflow:hidden;text-align:center;">
										<a class="profile_font" style="color:white;width:100%;height:100%;object-fit: cover;"><?=mb_substr($member_iam['mem_name'],0,3,"utf-8")?></a>
									</div>
							<?  }
							}else{
							?>        
								<div style="width:70px;height:70px;border-radius: 50%;overflow: hidden">
									<img src="/iam/img/iam_other.png" style="width:100%;height:100%;object-fit: cover;">
								</div>
						<?  }?>
							<img src="/iam/img/menu/icon_profile_edit.png" style="position:absolute;left: 45px;top: 45px;cursor:pointer" onclick="location.href='/iam/mypage.php'">
							<div style="margin-left:20px;font-family: 'notokr', sans-serif;">
								<h4><?=$member_iam['mem_name']?></h4>
								<div style="display:flex;margin-top:10px">
									<h5 style="padding:5px 0px;margin:0px"><?=$member_iam['mem_id']?></h5>
									<? 
										$mem_leb = "일반";
										if($member_iam['mem_leb'] == "22")
											$mem_leb = "일반";
										else if($member_iam['mem_leb'] == "50")
											$mem_leb = "사업";
										else if($member_iam['mem_leb'] == "21")
											$mem_leb ="강사";
										else if($member_iam['mem_leb'] == "60")
											$mem_leb ="홍보";
									?>
									<p style="font-size:10px;margin-left:20px;background:#99cc00;border-radius:20px;color:white;padding:5px 10px"><?=$mem_leb?></p>
								</div>
							<!--/div>
							<div class="center_text" style="width:40%;"-->
								<div class="sns_item" style="background: #f5f5f5;border:2px solid #d7d7d7;color:black;padding:4px 2px;border-radius:15px;margin-top:10px;text-align:center;width:100%">
									<a href="/iam/ajax/logout.php" style="font-size:14px;font-weight:500"><?=$MENU['TOP_MENU']['LOGOUT'];?></a>
								</div>
							</div>
						</div>
						<?}else{?>
						<div style="margin-top:30px;margin-left:0px;">
							<div style="text-align: center">
								<h4 style="color: black;text-align:left">아이엠을 이용하시려면 <strong>로그인</strong>이 필요해요.</h4>
							</div>
							<div style="display:flex;justify-content: space-between;;margin-top:30px">
								<div class="center_text" style="width:40%;">
									<div  class="sns_item" style="background: #f5f5f5;border: 2px solid #d7d7d7;color:black;padding: 2px; border-radius: 15px;margin-top:0px;width:100%">
										<a href="/iam/login.php" style="font-size:14px;font-weight:500"><?=$MENU['TOP_MENU']['LOGIN'];?></a>
									</div>
								</div>
								<div class="center_text" style="width:40%;">
									<div  class="sns_item" style="background: #f5f5f5;border: 2px solid #d7d7d7;color:black;padding: 2px; border-radius: 15px;margin-top:0px;width:100%">
									<?
										if($HTTP_HOST != "kiam.kr"){
											$join_link = get_join_link("http://".$HTTP_HOST);
										}
										else{
											$join_link = get_join_link("http://www.kiam.kr");
										}
										?>
										<a href="<?=$join_link?>" style="font-size:14px;font-weight:500"><?=$MENU['TOP_MENU']['JOIN'];?></a>
									</div>
								</div>
							</div>
						</div>
						<?}?>
					</div>
					<div class="modal-body">
						<div style="padding-top: 0px;display:flex;">
							<img src="/iam/img/main/icon_iam_member.png" style="margin: 8px 2px 0px 2px;height:20px;">
							<div style="width:100%">
								<a class="menu_font" href="/iam/mypage.php" ><h3 class = "mypage_list"><?='회원정보';?></h3></a>
							</div>
						</div>
						<div style="padding-top: 0px;display:flex;border-bottom:1px solid #ddd">
							<img src="/iam/img/menu/icon_setting.png" style="margin-top:8px;height:24px;">
							<div style="width:100%">
								<a class="menu_font" href="/?cur_win=shared_receive" ><h3 class = "mypage_list"><?=$MENU['TOP_MENU']['MYPAGE'];?></h3></a>
							</div>
						</div>
						<?if($_SESSION['iam_member_subadmin_id']) {?>
						<div style="padding-top: 0px;display:flex;">
							<img src="/iam/img/menu/icon_service_manager.png" style="margin-top:8px;height:24px;">
							<div style="width:100%">
								<a class="menu_font" href="/iama/service_Iam_admin.php" data='<?=$_SESSION['iam_member_subadmin_id']?>' title="<?=$MENU['TOP_MENU']['ADMIN_TITLE'];?>">
									<h3 class = "mypage_list"><?=$MENU['TOP_MENU']['ADMIN'];?></h3>
								</a>
							</div>
						</div>
						<div style="padding-top: 0px;display:flex;">
							<img src="/iam/img/menu/icon_automem_list.png" style="margin-top:8px;height:24px;">
							<div style="width:100%">
								<a class="menu_font" href="javascript:show_automember_list()" title="오토회원가입리스트">
									<h3 class = "mypage_list"><?="오토회원가입리스트"?></h3>
								</a>
							</div>
						</div>
						<div style="padding-top: 0px;display:flex;border-bottom:1px solid #ddd">
							<img src="/iam/img/menu/icon_notice_send.png" style="margin-top:8px;height:24px;">
							<div style="width:100%">
								<a class="menu_font" href="javascript:send_notice()" title="공지사항 전송하기">
									<h3 class = "mypage_list"><?="공지사항 전송하기"?></h3>
								</a>
							</div>
						</div>
						<?}?>
						<div style="padding-top: 0px;display:flex;">
							<img src="/iam/img/menu/icon_manage_payment.png" style="margin-top:8px;height:24px;">
							<div style="width:100%">
								<a class="menu_font" href="/iam/mypage_payment.php" ><h3 class = "mypage_list"><?="결제정보"?></h3></a>
							</div>
						</div>
						<div style="padding-top: 0px;display:flex;">
							<img src="/iam/img/menu/icon_manage_post.png" style="margin-top:8px;height:24px;">
							<div style="width:100%">
								<a class="menu_font" href="/iam/mypage_post_lock.php" ><h3 class = "mypage_list"><?="댓글관리"?></h3></a>
							</div>
						</div>
						<div style="padding-top: 0px;display:flex;border-bottom:1px solid #ddd">
							<img src="/iam/img/menu/icon_manage_selling.png" style="margin-top:8px;height:24px;">
							<div style="width:100%">
								<a class="menu_font" href="/iam/mypage_payment_item.php" ><h3 class = "mypage_list"><?="판매내역"?></h3></a>
							</div>
						</div>
						<?if($mall_reg_state){?>
						<div style="padding-top: 0px;display:flex;" onclick="open_iam_mall_popup('<?=$_SESSION['iam_member_id']?>',1,'<?=$card_owner_code?>');">
							<img src="/iam/img/menu/icon_shop.png" style="margin-top:8px;height:24px;">
							<div style="width:100%">
								<a style="font-size:14px" title="<?=$MENU['IAM_MENU']['M5_TITLE'];?>"><h3 class = "mypage_list">IAM몰에 내 아이엠 등록</h3></a>
							</div>
						</div>
						<?}?>
						<div style="padding-top: 0px;display:flex;" onclick="iam_mystory('cur_win=my_story')">
							<img src="/iam/img/menu/icon_folder.png" style="margin-top:8px;height:24px;">
							<div style="width:100%">
								<a class="menu_font" style="font-size:14px" title="<?=$MENU['IAM_MENU']['M5_TITLE'];?>"><h3 class = "mypage_list"><?=$MENU['IAM_MENU']['M5'];?></h3></a>
							</div>
						</div>
						<div style="padding-top: 0px;display:flex;" data-toggle="modal" data-target="#people_iam_modal" onclick="$('#mypage-modalwindow').modal('hide')">
							<img src="/iam/img/menu/icon_ai.png" style="margin-top:8px;height:24px;">
							<div style="width:100%">
								<a class="menu_font" style="font-size:14px" title="<?=$MENU['IAM_MENU']['M5_TITLE'];?>"><h3 class = "mypage_list"><?="AI로 자동 카드 만들기"?></h3></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--인물검색으로 아이엠 만들기 팝업창 -->
		<div id="people_iam_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
			<div class="modal-dialog" role="document" style="margin: 100px auto;">
				<div class="modal-content">
					<div>
						<button type="button" class="close" data-dismiss="modal">
							<img src="/iam/img/menu/icon_close_white.png" style="" class="close" data-dismiss="modal">
						</button>
					</div>
					<div class = "modal-title" style="">
						<label style="padding:15px 0px">AI로 자동 IAM 만들기</label>
					</div>
					<div class="modal-header" style="padding:0px">
						<div class="container" style="margin-top:30px;">
							<p style="margin:0px 10px;font-size:15px;font-weight:700;padding:10px 0px;text-align:center;border:1px solid #dddddd">웹페이지 주소만 입력하면 IAM카드가 <br>자동으로 생성되는 서비스입니다.</p>
						</div>
						<div class="container" style="margin-top: 20px;text-align: center;">
							<a href="https://www.youtube.com/playlist?list=PLP7cr8I5HQ8iLO-oGYvCOFygjKYnjYO28" target = "_blank" style="font-size:15px; color:#99cc00;">AI로 자동 아이엠 만들기 영상보러가기</a>
						</div>
						<div class="container" style="margin-top:10px;">
							<p style="margin:0px 10px;font-size:15px;font-weight:700;">포인트충전</p>
						</div>
						<div class="container" style="margin-top: 5px;">
							<div style="margin-top:10px;display: flex;justify-content: space-around;font-size:18px;font-weight:700;">
								<?if($is_pay_version){?>
									<button class="people_iam settlement_btn" style="padding:20px 0px" href="#" onclick="point_chung()">포인트로<br>충전하기</button>
								<?}?>
								<p style="padding:20px 0px" id="point_show_share">보유포인트<br><a style="color:red"><?=number_format($Gn_point);?>P</a></p>
								<p class="people_iam settlement_btn sharepoint" data="webpage" style="padding:20px 0px;text-align:center; cursor:pointer;">포인트<br>쉐어하기</p>
							</div>
							<div style="margin:30px 0px;display: flex;justify-content: space-around;">
								<button type="button" class="btn btn-default btn-submit" style="border-radius: 20px;width:40%;font-size:14px;font-weight:700;" onclick="show_contents()">이용내역보기</button>
								<button type="button" class="btn btn-default btn-submit" style="border-radius: 20px;width:40%;font-size:14px;font-weight:700;" onclick="location.href='mypage_payment.php'">포인트내역보기</button>
							</div>
						</div>
						<div class="container" style="margin: 20px 0px;">
							<p style="font-size:15px;border: 1px solid #dddddd;margin:0px 10px;padding:0px 10px">
							<br>
							[확인하세요]<br>
							1. 방법확인 : AI로 자동 IAM 만들기 영상으로 방법을 확인하세요.<br>
							2. 검색확인 : 네이버에서 인물, 지도, 쇼핑, 블로그, 유튜브 등에서 검색어를 입력하고 웹페이지 콘텐츠정보와 콘텐츠수를 확인하세요.<br>
							3. 수집건수 : 한 개의 웹주소당 유튜브 30건까지, 나머지는 200건까지 등록됩니다.<br>
							4. 차감포인트 : 자동 IAM 1회 사용시 <?=number_format($point_ai)?> 포인트가 차감됩니다.<br>
							<br>
							</p>
						</div>
						
					</div>
					<div class="modal-footer" style="padding:0px;display:flex">
						<button type="button" class="btn btn-default btn-left" style="width:50%;border-radius:0px;padding:15px 0px;" data-dismiss="modal">취소하기</button>
						<button type="button" class="btn btn-active btn-right" style="width:50%;border-radius:0px;margin-left:0px;padding:15px 0px;" id="cont_modal_btn_ok" onclick="settlement('make')">자동 IAM 만들기</button>
					</div>
				</div>
			</div>
		</div>
		<div id="auto_settlement_modal" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" style="margin: 100px auto;">
				<!-- Modal content-->
				<div class="modal-content">
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
						<label style="font-size:18px;margin-top:10px">오토데이트 포인트 이용 안내</label>
					</div>
					<div class="modal-header">
						<div class="container" style="margin-top: 20px;">
							<div class="container" style="margin-top: 20px;">
								<p style="font-size:16px;color:black;">
									콘텐츠 오토데이트 기능을 사용하게 되면 선택한 IAM카드에서 발생하는 트래픽 비용으로 매월 1100원의 포인트가 차감됩니다.<br>
									포인트가 부족할 경우 오토데이트가 중지됩니다. 미리 포인트를 충분히 충전해두시기 바랍니다.
								</p>
							</div>
							<div class="container" style="margin-top: 20px;">
								<table style="width:100%;">
									<tbody>
									<tr>
										<td class="iam_table" style="width: 40%;">충전포인트</td>
										<td class="iam_table"><input type="number" name="auto_point" id="auto_point" placeholder="충전할 포인트를 입력하세요." value="0"></td>
									</tr>
									</tbody>
								</table>
							</div>
							<div class="container" style="margin-top: 20px;text-align:center;column-count: 2;">
								<div>
									<input type="radio" name="pay_type" id="card_type_auto" style="vertical-align:middle;">
									<label for="card_type_auto" value="card" style="font-size: 17px;">카드결제</label>
								</div>
								<div>
									<input type="radio" name="pay_type" id="bank_type_auto" style="vertical-align:middle;">
									<label for="bank_type_auto" value="bank" style="font-size: 17px;">무통장결제</label>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer" style="text-align: center;">
						<button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:35%;font-size:15px;" onclick="goback('settlment_auto')">뒤로가기</button>
						<button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:35%;font-size:15px;" onclick="settlement('finish', document.pay_form, 'auto')">결제하기</button>
					</div>
				</div>
			</div>
		</div>
		<div id="settlement_finish_modal" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" style="margin: 100px auto;">
				<!-- Modal content-->
				<div class="modal-content">
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
						<label style="font-size:18px;margin-top:10px">결제완료</label>
					</div>
					<div class="modal-header">
						<div class="container" style="margin-top: 20px;">
							<p style="font-size:20px;text-align:center;"><?=$_SESSION['iam_member_id'];?>님은 <span id="finish_name"></span> 상품을 구매하여 해당 포인트가 충전되었으므로 아래에서 IAM자동만들기 버튼을 클릭하시면 됩니다. 만들기 전에 먼저 영상을 꼭 보시기 바랍니다.</p>
						</div>
						<div class="container" style="margin-top: 20px;text-align: center;">
							<a href="#" style="font-size:22px;">인물검색 IAM 만들기 영상(1분)</a>
						</div>
						<div class="container" style="margin-top: 20px;">
							<div style="margin-left:14px;margin-bottom:20px;">
								<button class="people_iam" href="#" style="width:40%;" onclick="settlement('set')">결제하기</button>
								<input type="text" disabled value="0P" id="finish_point" style="background-color:#aaaaaa;width:57%;text-align:center;font-size: 22px;">
							</div>
							<button class="people_iam" href="#" style="margin-left:15px;width:95.3%;" onclick="settlement('make')">자동 IAM만들기</button>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default btn-submit" data-dismiss="modal" style="border-radius: 5px;width:42%;font-size:15px;" onclick="show_contents()">결제/이용내역보기</button>
					</div>
				</div>
			</div>
		</div>
		<div id="mutong_settle" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
			<div class="modal-dialog" style="margin: 100px auto;">
				<!-- Modal content-->
				<div class="modal-content">
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
						<label style="font-size:18px;margin-top:10px">무통장 결제 안내</label>
					</div>
					<div class="modal-body">
						<div class="container" style="margin-top: 20px;box-shadow: none;">
							<p style="font-size:16px;color:#6e6c6c">
								[입금 계좌 안내]<br>
								스텐다드차타드은행 617-20-109431<br>
								온리원연구소(구,SC제일은행)
							</p>
						</div>
					</div>
					<div class="modal-footer" style="display: flex;justify-content: center">
						<a class="btn btn-default btn-submit" style="border-radius: 5px;width:45%;font-size:15px;background-color: #ff0066;color:white;" href="https://pf.kakao.com/_jVafC/chat" target="_blank">입금 후 카톡창에 남기기</a>
					</div>
				</div>
			</div>
		</div>
		<div id="settlement_contents_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
			<div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
				<!-- Modal content-->
				<div class="modal-content">
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
						<label style="font-size:18px;margin-top:10px">IAM 만들기 결제/이용내역</label>
					</div>
					<div class="modal-body">
						<div class="container" style="display:inline-block;">
							<input type="date" placeholder="시작일" id="search_start_date" value="<?=$_REQUEST[search_start_date]?>" style="border: 1px solid black;width:130px;"><span style="margin-left: 3px;">~</span>
							<input type="date" placeholder="종료일" id="search_end_date" value="<?=$_REQUEST[search_end_date]?>" style="border: 1px solid black;width:130px;">
							<!-- <select title="" id="use_buy_type" data-plugin-selectTwo onchange="" style="width:90px;margin: 20px 0 0 5px;">
								<option value="">전체</option>
								<option value="use">이용</option>
								<option value="buy">입금</option>
							</select> -->
							<!-- <input type="text" placeholder="아이디" name="search_ID" id="search_ID" style="margin-top: 20px;border: 1px solid black;width:120px;"> -->
							<button onclick="search_people('search')"><i class="fa fa-search"></i></button>
							<button type="button" class="btn btn-primary" id="btn_del_ai" onclick="del_ai_list('all')" style="float:right;">전체삭제</button>
						</div>
						<div class="container" style="margin-top: 20px;text-align: center;">
							<!-- <div style="border: 1px solid black;">인물주소입력<input type="text" placeholder="네이버 인물검색 웹주소입력"></div>
								<div style=""></div>
								<div style=""></div> -->
							<table style="width:100%">
								<thead>
								<th class="iam_table" style="width:20%;">일시</th>
								<th class="iam_table" style="width:9%;">활동</th>
								<th class="iam_table" style="width:16%;">분야</th>
								<th class="iam_table" style="width:12%;">해당 아이디</th>
								<th class="iam_table" style="width:25%;">링크주소</th>
								<th class="iam_table" style="width:10%;">잔여포인트</th>
								<th class="iam_table" style="width:10%;">삭제</th>
								</thead>
								<tbody id="contents_side">

								</tbody>
							</table>
						</div>
						<div class="container" style="text-align:center;">
							<button style="border-radius: 5px;width:20%;font-size:15px;margin-top: 10px;" onclick="goback('more')">뒤로가기</button>
							<button style="border-radius: 5px;width:20%;font-size:15px;margin-top: 10px;" onclick="search_people('more')">더보기</button>
						</div>
						<div class="container" style="margin-top: 20px;border: 1px solid black;">
							<p style="font-size:16px;color:#6e6c6c">
								[주의사항]<br>
								1. 잔여액을 출금요청시 구매할때 할인 받은 금액을 이용건수에서 차감하고 지불합니다.<br>
								2. 아이엠을 자동으로 생성한 이후에는 해당 생성분에 대해서는 환불이 되지 않으므로 생성전에 웹정보를 상세히 확인하기바랍니다.<br>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="finish_login" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" style="margin: 100px auto;">
				<!-- Modal content-->
				<div class="modal-content">
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
						<label style="font-size:18px;margin-top:10px">IAM 생성완료</label>
					</div>
					<div class="modal-header">
						<div class="container" style="margin-top: 20px;">
							<p style="font-size:20px;text-align:center;"><span id="people_mem_id" style="vertical-align: top;font-weight: 900;"></span>님의 자동아이엠이 생성되었습니다.<br>아이엠을 편집, 이용하려면 로그인을 클릭하고 앞에서 입력한 아이디와 비번을 입력 하세요.<br>감사합니다.</p>
						</div>
					</div>
					<div class="modal-footer" style="display:flex;text-align:center;">
						<button class="people_iam" style="margin-left:70px;width:30.3%;" onclick="location='/index.php'">취소</button>
						<button class="people_iam" onclick="location='login.php'" style="margin-left:70px;width:30.3%;">로그인</button>
					</div>
				</div>
			</div>
		</div>
		<div id="intro_auto_update" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" style="margin: 100px auto;">
				<!-- Modal content-->
				<div class="modal-content">
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
						<label style="font-size:18px;margin-top:10px">콘텐츠 오토데이트 기능 소개</label>
					</div>
					<div class="modal-header">
						<div class="container" style="margin-top: 20px;">
							<p style="font-size:20px;text-align:center;">이 기능은 위 페이지에 새로운 콘텐츠가 업로드되면 매일 지정한 시간에 IAM 카드에 자동으로 업데이트 되는 기능입니다. 이 기능은 매일 업데이트되는 콘텐츠로 인해 트래픽 비용이 발생하기때문에 이용자의 포인트에서 유료결제로 운영됩니다.</p>
						</div>
					</div>
					<div class="modal-footer" style="display:flex;text-align:center;">
						<button class="people_iam" style="margin-left:70px;width:30.3%;" data-dismiss="modal" onclick="show_making()">확인</button>
						<button class="people_iam" onclick="point_chung1()" style="margin-left:70px;width:30.3%;">충전하기</button>
					</div>
				</div>
			</div>
		</div>
		<div id="finish_login_own" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" style="margin: 100px auto;">
				<!-- Modal content-->
				<div class="modal-content">
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
						<label style="font-size:18px;margin-top:10px">IAM 생성완료</label>
					</div>
					<div class="modal-header">
						<div class="container" style="margin-top: 20px;">
							<p style="font-size:20px;text-align:center;">본인의 아이디로 자동아이엠이 생성되었습니다.<br>현재 로그인 상태이므로 생성된 아이엠을 수정하거나 이용해보세요.</p>
						</div>
					</div>
					<div class="modal-footer" style="text-align:center;">
						<button class="people_iam" onclick="go_iamlink()" style="width:41%;background-color: azure;">생성된 아이엠으로 가기</button>
					</div>
				</div>
			</div>
		</div>
		<div id="finish_login_cardsel" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" style="margin: 100px auto;">
				<!-- Modal content-->
				<div class="modal-content">
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
						<label style="font-size:18px;margin-top:10px">IAM 생성완료</label>
					</div>
					<div class="modal-header">
						<div class="container" style="margin-top: 20px;">
							<p style="font-size:20px;text-align:center;">카드에 콘텐츠가 추가되었습니다.<br>현재 로그인 상태이므로 생성된 콘텐츠를 수정하거나 이용해보세요.</p>
						</div>
					</div>
					<div class="modal-footer" style="text-align:center;">
						<button class="people_iam" onclick="go_contentslink()" style="width:41%;background-color: azure;">확인</button>
					</div>
				</div>
			</div>
		</div>
		<div id="sharepoint_modal" class="modal fade" tabindex="-1" role="dialog">
			<div class="modal-dialog" style="margin: 100px auto;">
				<!-- Modal content-->
				<div class="modal-content">
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
						<label style="font-size:18px;margin-top:10px">포인트 쉐어하기</label>
					</div>
					<div class="modal-header">
						<div class="container" style="margin-top: 20px;text-align: center;">
							<table style="width:100%;">
								<tbody>
								<tr>
									<td class="iam_table" style="width: 22.8%;border-bottom-color: white;">쉐어ID</td>
									<td class="iam_table" style="border-bottom-color: white;"><input type="text" id="share_id" placeholder="아이디를 입력하세요." style="width: 100%;"></td>
								</tr>
								</tbody>
							</table>
							<table style="width:100%">
								<tbody>
								<tr>
									<td class="iam_table" style="width: 22.8%;border-bottom-color: white;">쉐어 캐시포인트</td>
									<td class="iam_table" style="border-bottom-color: white;"><input type="number" id="share_cash" style="width: 100%;"></td>
								</tr>
								</tbody>
							</table>
							<table style="width:100%">
								<tbody>
								<tr>
									<td class="iam_table" style="width: 22.8%;">쉐어 씨드포인트</td>
									<td class="iam_table"><input type="number" id="share_point" style="width: 100%;"></td>
								</tr>
								</tbody>
							</table>
						</div>
						<div class="container" style="margin-top: 20px;border: 1px solid black;">
							<p style="font-size:16px;color:#6e6c6c">
								1. 자신의 계정에 있는 포인트를 다른 ID로 쉐어하는 기능입니다.<br>
								2. 쉐어하려는 ID와 포인트를 입력하세요.<br>
							</p>
						</div>
					</div>
					<div class="modal-footer" style="display: flex;justify-content: space-around;">
						<input type="hidden" id="place_before">
						<button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:45%;font-size:15px;" onclick="goback('share')">취소</button>
						<button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:45%;font-size:15px;" onclick="start_sharing()" id="start_share">쉐어하기</button>
					</div>
				</div>
			</div>
		</div>
		<div id="mutong_settlement" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
			<div class="modal-dialog" style="margin: 100px auto;">
				<!-- Modal content-->
				<div class="modal-content">
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
						<label style="font-size:18px;margin-top:10px">캐시포인트 충전</label>
					</div>
					<div class="modal-body">
						<div class="container"  style="font-size:16px">
                            <p align="center">회원님의 보유포인트 안내</p>
							<p>
                                캐시포인트: <?=number_format($Gn_cash)?> P<br>
                                ※캐시P는 현금과 같아서 모든 결제에 이용가능합니다.<br><br>
                                씨드포인트: <?=number_format($Gn_point)?> P<br>
                                ※씨드P는 IAM의 내부 기능이용에만 사용합니다.<br><br>
                                결제금액:<input type="number" id="money_point" style="border: 1px solid;margin-left: 10px;">
                            </p>
						</div>
					</div>
					<div class="modal-footer" style="display: flex;justify-content: space-around;">
						<a class="btn btn-default btn-submit" style="border-radius: 5px;width:45%;font-size:15px;background-color: #ff0066;color:white;" onclick="card_settle('<?=$_GET[cur_win]?>')" target="_blank">카드 결제</a>
						<a class="btn btn-default btn-submit" style="border-radius: 5px;width:45%;font-size:15px;background-color: #ff0066;color:white;" onclick="bank_settle()" target="_blank">무통장 결제</a>
					</div>
				</div>
			</div>
		</div>
        <!--포인트 전환 팝업-->
        <div id="point_change" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1100;top: 20px;height: 100%">
            <div class="modal-dialog modal-sm" role="document" style="width:90%;max-width : 350px;">
                <div class="modal-content">
                    <div>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
                            <img src = "/iam/img/menu/icon_close_white.png" style="" class="close" data-dismiss="modal" aria-hidden="true">
                        </button>
                    </div>
                    <div class="modal-title" style="padding: 10px 0;">
                        <label style="">포인트 전환</label>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body-div">
                            <p class="modal-body-title">회원님의 캐시포인트</p>
                            <p id="myCash"><?=number_format($Gn_cash)?> P</p>
                        </div>
                        <div class="modal-body-div">
                            <p class="modal-body-title">씨드포인트로 전환</p>
                            <input type="number" min="0" id="seedPoint" onkeyup="onChangePoint('<?=$Gn_cash?>')" onchange="onChangePoint('<?=$Gn_cash?>')">
                        </div>
                        <div class="modal-body-div">
                            <p class="modal-body-title">현금으로 전환</p>
                            <input type="number" min="0" id="money" onkeyup="onChangePoint('<?=$Gn_cash?>')" onchange="onChangePoint('<?=$Gn_cash?>')">
                        </div>
                        <p style="text-align: center">※10,000원 이상부터 현금전환 가능합니다.</p>
                        <div style="display: flex;justify-content: center">
                            <button class="btn" style="background-color: #ff0066;color:white;margin-top: 20px" onclick="onRequestCashChange()">전환 요청하기</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<div id="cashtoseed_settlement" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
			<div class="modal-dialog" style="margin: 100px auto;">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header" style="border:none;background-color: rgb(130,199,54)">
						<div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">씨드포인트 충전
						</div>
						<a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -33px;cursor:pointer;">X</a>
					</div>
					<div class="modal-body">
						<div class="container" style="margin-top: 20px;box-shadow: none;">
							<p style="font-size:16px;color:#6e6c6c">
								회원님의 보유 캐시포인트는 <?=number_format($Gn_cash)?> P 입니다.<br>
								회원님의 보유 씨드포인트는 <?=number_format($Gn_point)?> P 입니다.<br>
								캐시포인트 부족시 충전하시기 바랍니다.
							</p>
						</div>
						<div class="container" style="margin-top: 20px;box-shadow: none;">
							<p style="font-size:16px;color:#6e6c6c">
								결제금액:<input type="number" id="money_point_cashtoseed" style="border: 1px solid;">
							</p>
						</div>
					</div>
					<div class="modal-footer"  style="display: flex;justify-content: space-around;">
						<a class="btn btn-default btn-submit" style="border-radius: 5px;width:45%;font-size:15px;background-color: #ff0066;color:white;" onclick="cashtoseed_chung('<?=$_GET[cur_win]?>')" target="_blank">씨드포인트 충전</a>
						<a class="btn btn-default btn-submit" style="border-radius: 5px;width:45%;font-size:15px;background-color: #ff0066;color:white;" data-toggle="modal" data-target="#mutong_settlement" data-dismiss="modal" target="_blank">캐시포인트 충전</a>
					</div>
				</div>
			</div>
		</div>
		<div id="point_settlement" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
			<div class="modal-dialog" style="margin: 100px auto;">
				<!-- Modal content-->
				<div class="modal-content">
				<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
						<label style="font-size:18px;margin-top:10px">포인트 충전/결제</label>
					</div>
					<div class="modal-body">
						<div class="container" style="margin-top: 20px;box-shadow: none;">
							<p style="font-size:16px;color:#6e6c6c">
								회원님의 보유 씨드포인트는 <?=number_format($Gn_point)?> P 입니다.<br>
								포인트 부족시 충전하시기 바랍니다.
							</p>
							<input type="hidden" id="point_pay_type" value=""><!--0:서비스구매 1:아이엠몰 구매 2:카드몰구매3:콘텐츠몰구매-->
							<input type="hidden" id="point_pay_data" value=""><!--구매할 상품 정보 array str-->
						</div>
						<div class="container" style="margin-top: 20px;box-shadow: none;">
							<p style="font-size:16px;color:#6e6c6c">
								포인트결제 <input type="number" id="settle_point" style="border: 1px solid;" value="<?=$price_service?>" readonly>P
								<input type="hidden" id="sell_con_title" value="<?=$name_service?>">
								<input type="hidden" id="sell_con_url" value="<?=$contents_url?>">
								<input type="hidden" id="sell_con_id" value="<?=$sellerid_service?>">
							</p>
						</div>
					</div>
					<div class="modal-footer"  style="display: flex;justify-content: space-around;">
						<a class="btn btn-default btn-submit" style="border-radius: 5px;width:45%;font-size:15px;background-color: #ff0066;color:white;" data-toggle="modal" data-target="#cashtoseed_settlement" data-dismiss="modal" target="_blank">충전하기</a>
						<a class="btn btn-default btn-submit" style="border-radius: 5px;width:45%;font-size:15px;background-color: #ff0066;color:white;" onclick="point_settle()" target="_blank">지금결제하기</a>
					</div>
				</div>
			</div>
		</div>
        <div id="auto_list_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        </div>
		<!-- ./오토회원 설정리스트 수정 모달 -->
		<div id="auto_list_edit_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
			<div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
				<!-- Modal content-->
				<div class="modal-content" id="edit_modal_content">
				<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
						<label style="font-size:18px;margin-top:10px">회원가입 메시지 리스트</label>
					</div>
					<div class="modal-body">
						<div class="container" style="margin-top: 20px;text-align: center;">
							<table style="width:100%">
								<form method="post" id="dForm_edit" name="dForm_edit" action="/ajax/edit_event.php"  enctype="multipart/form-data">
								<tbody id="edit_event">
									<tr>
										<input type="hidden" id="event_idx" value="'.$event_id.'">
										<th class="iam_table" style="width:20%;">아이디</th>
										<td class="iam_table"><?=$_SESSION['iam_member_id'];?></td>
									</tr>
									<tr>
										<th class="iam_table" style="width:20%;">이벤트타이틀</th>
										<td class="iam_table">
											<textarea style="width:100%;height: 50px;" name="event_title" id="event_title" value="" ></textarea>
										</td>
									</tr>
									<tr>
										<th class="iam_table" style="width:20%;">이벤트메시지</th>
										<td class="iam_table"><textarea style="width:100%;height: 100px;" name="event_desc" id="event_desc" value="" ></textarea></td>
									</tr>
									<tr>
										<th class="iam_table" style="width:20%;">카드링크</th>
										<td class="iam_table">
											<input type="text" id="card_short_url" hidden>
											<div id="cardsel1" onclick="limit_selcard1()" style="margin-top:15px;">
												<?
												$sql5="select card_short_url,phone_display, card_title from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' order by req_data asc";
												$result5=mysqli_query($self_con,$sql5);
												$i = 0;
												while($row5=mysqli_fetch_array($result5)) {
													if($i == 0){
														$hidden = "hidden";
													}
													else{
														$hidden = "";
													}
													?>
													<input type="checkbox" id="multi_westory_card_url1_<?= $i+1 ?>" name="multi_westory_card_url1"
														class="we_story_radio1"
														value="<?= $i+1 ?>" <? if($row5['phone_display']=="N"
													){echo "onclick='locked_card_click();'" ;} ?> <?=$hidden?>
														>
													<span <? if($row5['phone_display']=="N" ){echo "class='locked' title='비공개카드'" ;} ?> <?=$hidden?>>
														<?=$i+1?>번(<?=$row5['card_title']?>)
													</span>
													<?
													$i++;
												}
												?>
											</div>
										</td>
									</tr>
									<tr>
										<th class="iam_table" style="width:20%;">버튼타이틀</th>
										<td class="iam_table"><input type="text" style="width:100%;" name="btn_title" id="btn_title" value="" ></td>
									</tr>
									<tr>
										<th class="iam_table" style="width:20%;">버튼링크</th>
										<td class="iam_table"><input type="text" style="width:100%;" name="btn_link" id="btn_link" value="" ></td>
									</tr>
									<tr>
										<th class="iam_table" style="width:20%;">단축주소</th>
										<td class="iam_table"><input type="text" style="width:100%;" name="short_url" id="short_url" value="" ></td>
									</tr>
									<tr>
										<th class="iam_table" style="width:20%;">조회수</th>
										<td class="iam_table"><input type="text" style="width:100%;" name="read_cnt" id="read_cnt" value="" ></td>
									</tr>
									<tr>
										<th class="iam_table" style="width:20%;">이미지</th>
										<td class="iam_table"><input type="file" name="autojoin_img" style="width:93px;"><span id="autojoin_img_event"></span></td>
									</tr>
									<tr id="step_info_tr" hidden>
										<th class="iam_table" style="width:20%;">스텝문자정보</th>
										<td class="iam_table">
											<input type="text" style="width:45%;" name="step_title" id="step_title" value="" disabled>
											<input type="text" style="width:100px;" name="step_phone" id="step_phone" value="" disabled>
											<input type="text" style="width:50px;" name="step_cnt" id="step_cnt" value="" disabled><br><br>
											적용상황
											<label class="step_switch">
												<input type="checkbox" name="step_allow_state" id="step_allow_state" value="">
												<span class="slider round" name="step_status_round" id="step_status_round"></span>
											</label>
											수정하기
											<p id="step_info" style="display:inline-block;"></p>
										</td>
									</tr>
									<tr>
										<th class="iam_table" style="width:20%;">등록일시</th>
										<td class="iam_table"><input type="text" style="width:100%;" name="regdate1" id="regdate1" value="" ></td>
									</tr>
								</tbody>
								</form>
							</table>
							<button style="" class="button_edit btn_back" onclick="goback('auto_list')">뒤로가기</button>
							<button style="" class="button_edit btn_save" onclick="save_edit_ev()">저장</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- // 공지사항 전송 팝업 -->
		<div id="notice_send_modal_mem" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
			<div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:768px;">
				<div class="modal-content">
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
							<img src="/iam/img/menu/icon_close_white.png" style="" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class = "modal-title" style="">
						<label style="padding:15px 0px">공지사항 전송하기</label>
					</div>
					<div class="modal-header" style="background:#f5f5f5;border:none">
						<textarea id="notice_title" style="width: 100%;border: 1px solid #b5b5b5;height: 30px;margin-bottom: 15px;" placeholder="공지 제목을 입력하세요."></textarea>
						<textarea id="notice_desc" style="width: 100%;border: 1px solid #b5b5b5;height: 100px;margin-bottom: 15px;" placeholder="공지 내용을 입력하세요."></textarea>
						<textarea id="notice_link" style="width: 100%;border: 1px solid #b5b5b5;height: 30px;margin-bottom: 15px;" placeholder="연결하려는 웹주소를 입력하세요."></textarea>
					</div>
					<div class="modal-body">
						<table class="table table-bordered">
							<tbody>
								<tr class="hide_spec">
									<td class="bold" id="remain_count" data-num="" style="width:70px;">전송하기<br>
									<textarea name="notice_send_id_count" id="notice_send_id_count" style="width:100%; height:50px;color: red;font-size: 12px" readonly="" data-num="0" placeholder="0건"></textarea></td>
									<td colspan="2">
										<div style="display:flex">
											<textarea name="notice_send_id" id="notice_send_id" style="border: solid 1px #b5b5b5;width:85%; height:100px;" data-num="0" placeholder="전송할 친구 아이디를 입력하세요.<컴마로 구분>"></textarea>
											<button type="button" class="btn btn-info" onclick="show_share_user_list('notice_send')" style="font-size:12px; padding:2px 20px;float:right;"> 전송할 친구<br>선택하기 </button>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="modal-footer" style="">
						<button type="button" class="btn-default btn-left" style="padding:15px 0px"  data-dismiss="modal" onclick="location.reload();">취소하기</button>
						<button type="button" class="btn-active btn-right" style="padding:15px 0px"  onclick="notice_send_settle()">전송하기</button>
					</div>
				</div>
			</div>
		</div>
		<div style="position: fixed;justify-content: space-around;width: 100%;height:50px;z-index: 100;bottom: 0px;display: flex;max-width: 768px;background:white;border-top : 1px solid #ddd">
			<?$home = $domainData[sub_domain].'/?'.$first_card_url.$bunyang_site_manager_code?>
			<div id="btn_home" style="margin-top:12px;text-align: center;cursor:pointer" onclick = "goIamHome();" title="홈">
				<img src = "/iam/img/menu/icon_bottom_home.png" style="height:24px;width:24px">
				<!--label style="font-size:12px">홈</label-->
			</div>
			<div id="btn_notice" style="margin-top:12px;text-align: center;cursor:pointer" title="소식">
				<img src = "/iam/img/menu/icon_bottom_news.png" style="height:24px;width:24px">
				<!--label style="font-size:12px">소식</label-->
			</div>
			<div id="btn_ai_chat" onclick="location.href='/iam/gpt_chat.php'" style="margin-top:12px;text-align: center;cursor:pointer" title="GPT채트">
				<img src = "/iam/img/menu/icon_ai.png" style="height:24px;width:24px">
			</div>
			<div onclick="showSample()" class="popup_holder2" style="margin-top:12px;text-align: center;cursor:pointer" title="검색">
				<img src = "/iam/img/menu/icon_sample.png" style="height:24px;width:24px">
				<!--label style="font-size:12px">검색</label-->
			</div>
			<div id="btn_intro" style="margin-top:12px;text-align: center;cursor:pointer" title="안내">
				<img src = "/iam/img/menu/icon_bottom_intro.png" style="height:24px;width:24px">
				<!--label style="font-size:12px">안내</label-->
			</div>
			<div onclick="go_cart()" style="margin-top:12px;text-align: center;cursor:pointer" title="장바구니">
				<img src = "/iam/img/menu/cart_gwc.png" style="height:24px;width:24px">
				<label class="label label-sm share_count" id = "cart_cnt" style="top:0px;"><?=$cart_cnt?$cart_cnt:''?></label>
			</div>
			<?if(!$_SESSION['iam_member_id']){?>
			<div id="btn_login" style="margin-top:12px;text-align: center;cursor:pointer" onclick="location.href = '/iam/login.php'">
				<img src = "/iam/img/menu/icon_bottom_login.png" style="height:24px;width:24px">
				<!--label style="font-size:12px">로그인</label-->
			</div>
			<?}else{
				if($user_mem_code == $card_owner_code){
					if($member_iam[profile]){
						if(strstr($member_iam[profile], "kiam")) {
							$member_iam[profile] = str_replace("http://kiam.kr", "", $member_iam[profile]);
							$member_iam[profile] = str_replace("http://www.kiam.kr", "", $member_iam[profile]);
						} 
						if(!strstr($member_iam[profile], "http") && $member_iam[profile]) {
							$image_link = $cdn_ssl.$member_iam[profile];
						}else{
							$image_link = $member_iam[profile];
						}
						$image_link = cross_image($image_link);
						?>
						<div style="cursor:pointer;margin-top:12px;width:24px;height:24px;border-radius: 50%;overflow: hidden" onclick="$('#mypage-modalwindow').modal('show');">
							<img src="<?=$image_link?>" style="width:100%;height:100%;object-fit: cover;">
						</div>
				<?  }else{?>
						<div style="cursor:pointer;margin-top:12px;background:<?=$profile_color?>;padding:5px 0px;width:24px;height:24px;border-radius: 50%;overflow:hidden;text-align:center;" onclick="$('#mypage-modalwindow').modal('show');">
							<a class="profile_font" style="color:white;width:100%;height:100%;object-fit: cover;"><?=mb_substr($member_iam['mem_name'],0,3,"utf-8")?></a>
						</div>
				<?  }
				}else{?>        
					<div style="cursor:pointer;margin-top:12px;width:24px;height:24px;border-radius: 50%;overflow: hidden" onclick="$('#mypage-modalwindow').modal('show');">
						<img src="/iam/img/iam_other.png" style="width:100%;height:100%;object-fit: cover;">
					</div>
			<?  }
			}?>
		</div>
		<!--sample 팝업-->
		<div id="sample-modalwindow" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1100;top: 20px;height: 100%;overflow-x: hidden;overflow-y: auto;">
			<div class="modal-dialog" role="document" style="margin-bottom: 30px;width:90%;max-width : 500px;margin-left:auto;margin-right:auto;">
				<div class="modal-content" style = "margin-right:0px;">
					<div style = "position:absolute;right : 10px;bottom:10px;z-index:100;">
						<button type="button" style = "background:transparent" onclick = "openNoticeModal()">
							<img src = "/iam/img/main/icon-notice.png" style="width:30px">
						</button>
					</div>
					<div style = "position:absolute;left : 10px;top:14px;z-index:100;">
						<button type="button" style = "background:transparent;color:white" onclick = "backNotice()">
							<
						</button>
					</div>
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="">
						<div>
							<img src = '/iam/img/menu/icon_sample_white.png' style="width:24px;vertical-align:bottom">
							<label style="">베스트 샘플IAM</label>
						</div>
					</div>
					<div class="modal-body" style="background-color: #e5e5e5;overflow-y:auto;padding-top:0px">
						<div style="padding-top: 2px;" >
							<div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex">
								<div style="">
									<img src="/iam/img/main/masterimage.png" style="width: 24px;margin: 10px;">
								</div>
								<div>
									<h4 style="margin-left: 10px;margin-top: 10px">desc</h4>
									<h4 style="margin-left: 10px;margin-top: 10px;margin-bottom: 10px">상세페이지:<a href="#"></a></h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--notice 팝업-->
		<div id="notice-modalwindow" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1100;top: 20px;height: 100%;overflow-x: hidden;overflow-y: auto;">
			<div class="modal-dialog" role="document" style="margin-bottom: 30px;width:90%;max-width : 500px;margin-left:auto;margin-right:auto;">
				<div class="modal-content" style = "margin-right:0px;">
					<div style = "position:absolute;right : 10px;bottom:10px;z-index:100;">
						<button type="button" style = "background:transparent" onclick = "openSampleModal()">
							<img src = "/iam/img/menu/icon_news_sample.png" style="width:30px">
						</button>
					</div>
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="">
						<div>
							<img src = '/iam/img/menu/icon_news_white.png' style="width:24px;vertical-align:bottom">
							<label style="">회원님들의 콘텐츠 업로드</label>
						</div>
					</div>
					<div class="modal-body popup-bottom" style="padding:5px 15px 15px 15px;background-color: #e5e5e5;overflow-y:auto">
						<div style="padding-top: 2px;" >
							<div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex">
								<div style="">
									<img src="/iam/img/main/masterimage.png" style="width: 50px;margin: 10px;">
								</div>
								<div>
									<p style="margin-left:10px;margin-top:10px;font-size:14px">desc</p>
									<p style="margin-left:10px;margin-top:10px;margin-bottom:10px;font-size:14px">상세페이지:<a href="#"></a></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--동영상 팝업-->
		<div id="video-modalwindow" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1100;">
			<div class="modal-dialog modal-sm" id="video-modal-dialog" role="document" style="width:100%;max-width : 300px;margin-right:0px;max-height: 80%;top : 50px">
			</div>
		</div>
		<!--아이엠 뉴스 팝업-->
		<div id="news-modalwindow" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1100;">
			<div class="modal-dialog modal-sm" role="document" style="width:90%;max-width : 500px;margin-left:auto;margin-right:auto;max-height: 80%;top : 50px">
				<div class="modal-content" style = "margin-right:0px;">
					<div class="modal-title" style="display: flex;justify-content: space-between;">
						<button type="button" style="background-color: transparent" onclick="backIntro();">
							<h3 style="color: #ffffff"><</h3>
						</button>
							<span style="text-align: center">
								<label style="color: #ffffff;font-size:18px">아이엠 소식</label>
								<label style="color: #ffffff;font-size:16px">업데이트 및 교육정보 등을 안내합니다</label>
							</span>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-top:0px" >
							<img src = "/iam/img/menu/icon_close_white.png" style="" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-body popup-bottom" style="background-color: #e5e5e5;max-height:400px;overflow-y:auto">
						<div>
							<div class="container" style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;">
								<button type="button"  data-toggle="collapse" aria-expanded="true" data-target="#news_go" style="background-color: #ffffff;margin: 8px 0px">
									<p style="font-size:14px;font-weight:bold">&#9660;바로가기</p>
								</button>
								<div id="news_go" class="collapse in" aria-expanded="true">
									<a href="/iam">
										<p style="font-size:14px;background-color: #ffffff;border-radius: 10px;margin-left: 10px;">아이엠</p>
									</a>
									<a href="?cur_win=best_sample">
										<p style="font-size:14px;background-color: #ffffff;border-radius: 10px;margin-left: 10px;margin-top: 5px">샘플보기</p>
									</a>
									<a href="#">
										<p style="font-size:14px;background-color: #ffffff;border-radius: 10px;margin-left: 10px;margin-top: 5px">유튜브 채널</p>
									</a>
									<a href="<?=$domainData[kakao]?>">
										<p style="font-size:14px;background-color: #ffffff;border-radius: 10px;margin-left: 10px;margin-top: 5px;margin-bottom: 5px">카카오톡 오픈채팅</p>
									</a>
								</div>
							</div>
							<div class="dropdown" style="background-color: #ffffff;border-radius: 10px;margin-top: 2px;padding-bottom: 2px;">
								<button type="button" class="dropdown-toggle" data-toggle="dropdown" style="background-color: #ffffff;margin: 8px 15px">
									<p style="font-size:14px;font-weight:bold" id="news_kind">&#9660;전체</p>
								</button>
								<div id="news_kind" class="dropdown-menu" style="background-color: #ffffff;float:left;position: absolute;left: 0px !important;width: 18px;">
									<?foreach($iam_notice_arr as $key=>$v){?>
										<p style="font-size:14px;" class="dropdown-item"  onclick="showNewsContent('<?=$key?>','<?=$v?>')"><?=$v?></p>
									<?}?>
								</div>
							</div>
							<?
							$news_sql = "select * from tjd_sellerboard where category=10 and important_yn='Y' order by date desc";
							$news_res = mysqli_query($self_con,$news_sql);
							while($news_row = mysqli_fetch_array($news_res)){?>
								<div style="padding-top: 1px;background-color: #ffffff;border-radius: 10px;margin-top: 2px" class = "news_content <?='news_kind_'.$news_row[fl]?>">
									<div style="display: flex">
										<p style="font-size:14px;margin-top:2px;margin-left: 10px;margin-right: 10px"><?=$iam_notice_arr[$news_row[fl]]?></p>
										<p style="font-size:14px"><?=$news_row[date]?></p>
									</div>
									<div style="text-align: center">
										<p style="font-size:18px"><?=$news_row[title]?></p><br>
									</div>
									<div style="text-align: left;margin-left: 10px">
										<?=htmlspecialchars_decode($news_row['content'])?>
									</div>
								</div>
							<?  }
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--intro 팝업-->
		<div id="intro-modalwindow" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1100;top: 20px;height: 100%">
			<div class="modal-dialog modal-sm" role="document" style="margin-bottom: 30px;width:90%;max-width : 500px;margin-left:auto;margin-right:auto;">
				<div class="modal-content" style = "margin-right:0px;">
					<div>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
							<img src = "/iam/img/menu/icon_close_white.png" style="" class="close" data-dismiss="modal" aria-hidden="true">
						</button>
					</div>
					<div class="modal-title" style="padding: 10px;">
						<div>
							<img src = '/iam/img/menu/icon_intro_white.png' style="width:24px;vertical-align:bottom">
							<label style="">안내</label>
						</div>
					</div>
					<div class="modal-body popup-bottom" style="background-color: #e5e5e5;">
						<div>
							<div style="padding-top: 2px;" >
								<div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px">
									<div style="display: flex">
										<img src="/iam/img/menu/icon_intro_mycon.png" style="width: 24px;height:24px;margin-left: 30px;margin-top: 30px;" id="intro_img">
										<label style="font-size:14px;margin-left: 10px; margin-top: 30px" id="intro_title">test</label>
									</div>
									<label style="font-size:14px;font-weight:300;margin-left: 10px;margin-top: 10px" id="intro_desc">desc</label>
									<label style="font-size:14px;font-weight:300;margin-left: 10px;margin-top: 10px;margin-bottom: 10px" id="intro_link_text">상세페이지:<a href="#"></a></label>
								</div>
							</div>
							<div style="padding-top: 5px;">
								<div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex" onclick="showVideo()">
									<img src = "/iam/img/menu/icon_intro_video.png" style="width: 24px;height:24px;margin-left: 10px;margin-top: 7px">
									<label style="font-size:14px;background-color: #ffffff;border-radius: 10px;margin:8px 10px 8px 0px">아이엠 주요 동영상</label>
								</div>
							</div>
							<div style="padding-top: 5px;">
								<div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex" onclick="showNews()">
									<img src = "/iam/img/menu/icon_intro_news.png" style="width: 24px;height:24px;margin-left: 10px;margin-top: 7px">
									<label style="font-size:14px;background-color: #ffffff;border-radius: 10px;margin:8px 10px 8px 0px">아이엠 소식</label>
								</div>
							</div>
							<div style="padding-top: 5px;">
								<div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex" onclick="openIntroKakao('<?=$domainData[kakao]?>')">
									<img src = "/iam/img/menu/icon_intro_kakao.png" style="width: 24px;height:24px;margin-left: 10px;margin-top: 7px">
									<label style="font-size:14px;background-color: #ffffff;border-radius: 10px;margin:8px 10px 8px 0px">카톡창으로 문의하기</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="zoomInVideo" style="z-index:1200;position: fixed;background-color: #ffffff;width :100%;max-width: 600px;height: 340px;top:50px;display: none;border-radius: 2%;border: 1px solid #b5b5b5;">
			<div style="position: absolute;top:-10px;right:-10px" id = "btnZoomOut" onclick = "clickZoomOut();">
				<img src = "/iam/img/menu/icon_close_white.png" style="width:24px">
			</div>
		</div>
		<div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>
        <script src="../iam/inc/js/header.inc.js"></script>
	<script>
        function showNewsContent(index,value){
            $("#news_kind").html('&#9660' + value);
            if(index != 0) {
                $(".news_content").css("display","none");
                $(".news_kind_" + index).css("display", "block");
            }else{
                $(".news_content").css("display","block");
            }
        }
        function openIntroKakao(link){
            $("#intro-modalwindow").modal("hide");
            window.open(link);
        }
        var zoomFilter = "win16|win32|win64|mac";
        if(navigator.platform){
            if(zoomFilter.indexOf(navigator.platform.toLowerCase()) < 0) {
                $("#btnZoomIn").css("display","none");
                $("#video-modal-dialog").css("margin-left","auto");
                $("#video-modal-dialog").css("margin-right","auto");
                $("#video-modal-dialog").css("max-width","450px");
                $("#intro_video").css("height","200px");
            }
        }
        function clickZoomIn(){
            $("#zoomInVideo").css("display","block");
            $("#btnZoomIn").css("display","none");
            $("#zoomInVideo").append($("#intro_video"));
            $("#zoomOutVideo").css("display","none");
            $("#zoomInVideo").css("top","50px");
            $("#zoomInVideo").css("max-width",window.innerWidth - $("#video-modal-dialog").width() - 20);
            var h = $("#zoomInVideo").width() / 16 * 9;
            $("#zoomInVideo").css("height",h);
            $("#intro_video").css("height",h);
        }
        function clickZoomOut(){
            $("#zoomInVideo").css("display","none");
            $("#btnZoomIn").css("display","block");
            $("#zoomOutVideo").append($("#intro_video"));
            $("#zoomOutVideo").css("display","block");
            $("#intro_video").css("height","150px");
        }
        function changeVideo(src){
            $("#intro_video").attr("src",src);
        }
        function backIntro(){
            $("#video-modalwindow").modal("hide");
            $("#news-modalwindow").modal("hide");
            $("#intro-modalwindow").modal("show");
            clickZoomOut();
        }
        function showVideo(){
            $("#intro-modalwindow").modal("hide");
            $.ajax({
                type: "GET",
                url: "/iam/ajax/modal_video_dialog.php",
                success: function(html) {
                    $("#video-modal-dialog").html('');
                    $("#video-modal-dialog").append(html);
                    $("#video-modalwindow").modal("show");
                }
            });
        }
        function showNews(){
            $("#intro-modalwindow").modal("hide");
            $("#news-modalwindow").modal("show");
        }

        function backNotice(){
            $("#notice-modalwindow").modal("show");
            $("#sample-modalwindow").modal("hide");
        }

        function openSampleModal(){
            $.ajax({
                type: "POST",
                url: "/iam/ajax/today_popup_ajax.php",
                data:{type : 'sample_popup'},
                dataType: "json",
                success: function(data) {
                    $("#sample-modalwindow").children().find(".modal-body").empty();
                    var result = data.result;
                    console.log(result.length);
                    for(var i = 0; i < result.length; i++){
                        var arr = result[i];
                        var html = "<div style=\"padding-top: 2px;\" onclick=\"window.open('"+arr.link+"')\">"+
                            "<div style=\"background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex\">"+
                            "<div style=\"border-radius: 50%;width: 50px;height: 50px;overflow: hidden;margin: 10px;\">"+
                            "<img src=\"" + arr.profile + "\" style=\"width: 50px;height:50px;\">"+
                            "</div>"+
                            "<div>"+
                            "<p style=\"font-size:14px;margin-left: 10px;margin-top: 10px\">" + arr.name1 + "</p>"+
                            "<p style=\"font-size:14px;margin-left: 10px;margin-top: 10px;margin-bottom: 10px\">" + arr.name2 + "</p>"+
                            "</div>"+
                            "</div>"+
                            "</div>";
                        $("#sample-modalwindow").children().find(".modal-body").append(html);
                    }
                    $("#notice-modalwindow").modal("hide");
                    $("#sample-modalwindow").modal("show");
                    $("#ajax-loading").delay(10).hide(1);
                }
            });
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

		$("#btn_intro").on("click",function(){
            var modal_id = $(".modal.fade.in").attr("id");
            var pos = '<?=$cur_win?>';
            if(modal_id)
                pos = modal_id;
            if($('.main_image_popup').css('display') == 'block')
                pos = 'main_image_popup';
            if(pos == "auto_making_modal"){    
                pos += "_" + $('input[name=web_type]:checked').attr('id');
            }
            if(pos == 'sns-modalwindow' || pos == 'sns-modalwindow_contents')
                pos = "iam share";
            $.ajax({
                type: "POST",
                url: "/admin/ajax/gn_alert_ajax.php",
                dataType: "json",
                data: {mode:"get",pos:pos},
                success: function(data) {
					$("#ajax-loading").delay(10).hide(1);
                    var img = data.img;
                    $("#intro_img").prop("src",img);
                    $("#intro_title").html(data.title);
                    $("#intro_desc").html(data.desc);
                    $("#intro_link_text").html("상세페이지:<a href=\""+data.link+"\" target='blank'>"+data.link+"</a>");
                }
            });
            $("#intro-modalwindow").modal("show");
            $("#notice-modalwindow").modal("hide");
            $("#sample-modalwindow").modal("hide");
        });



		function goIamHome(){
			if(checkMobile())
				AppScript.goIamHome('<?=$member_iam[site_iam]?>');
			else{
				var site = '<?=$member_iam[site_iam]?>';
				var homeURL = "http://"+site+".kiam.kr/m";
				if(site == "kiam")
					homeURL = "http://kiam.kr/m";
				else if(site == "")
					homeURL = "http://"+"<?=$HTTP_HOST?>"+"/m";
				window.open(homeURL);
			}
		}
		//자동아이엠만들기 스크립트 시작---------->>>>>>>>>>

		//새계정 만들기 현시prop("disabled", false)
		function newaccount(){
			$("#newmake").show();
			$("#mineid").hide();
			$("#cardsel").hide();
			$("#blog_mem_name").prop("disabled", false);
			$("#phone_num").prop("disabled", false);
			$("#blog_mem_zy").prop("disabled", false);
			$("#blog_mem_address").prop("disabled", false);
		}

		//내 아이디로 만들기 현시
		function myIDmake(){
			$("#newmake").hide();
			$("#mineid").show();
			$("#cardsel").hide();
			$("#blog_mem_name").prop("disabled", false);
			$("#phone_num").prop("disabled", false);
			$("#blog_mem_zy").prop("disabled", false);
			$("#blog_mem_address").prop("disabled", false);
		}

		//카드선택 추가하기 현시
		function selectcard(){
			$("#newmake").hide();
			$("#mineid").hide();
			$("#cardsel").show();
			$("#blog_mem_name").prop("disabled", true);
			$("#phone_num").prop("disabled", true);
			$("#blog_mem_zy").prop("disabled", true);
			$("#blog_mem_address").prop("disabled", true);
		}

		//자동아이엠 만들기, 오토데이트 기능을 위한 포인트 결제하기
		var ele;
		var auto = 0;

		function mutongjang(){
			$('#people_iam_modal').modal('hide');
			$('#mutong_settle').modal('show');
		}

		function point_chung(){
			$('#people_iam_modal').modal('hide');
			$('#card_send_modal').modal('hide');
			$('#contents_send_modal').modal('hide');
			$('#cashtoseed_settlement').modal('show');
		}

		function cashtoseed_chung(val){
			var cash_point = "<?=$Gn_cash?>";
			var seed_point = $("#money_point_cashtoseed").val();
			if(seed_point == ''){
				alert("포인트를 입력해 주세요.");
				return;
			}
			if(cash_point < seed_point){
				alert("캐시포인트가 부족합니다. 충전해 주세요.");
				return;
			}
			if(confirm("캐시포인트에서 씨드포인트로 전환하시겠습니까?")){
				$.ajax({
					type:"POST",
					url:"ajax/apply_service_con_res.php",
					data:{payMethod:"<?=$_SESSION['iam_member_id']?>", member_type:"씨드포인트충전", allat_amt:seed_point, pay_percent:90, allat_order_no:<?=$mid?>, point_val:1, mode:"cashtoseed", seed_point:seed_point},
					dataType: 'json',
					success:function(data){
						alert("캐시포인트 "+seed_point+"P 가 씨드포인트로 전환되었습니다.");
						location.reload();
					}
				});
			}
			// $("#mutong_settlement").modal('hide');
			// $("#mutong_settle").modal('show');
		}

		function point_chung1(){
			$('#intro_auto_update').modal('hide');
			$('#mutong_settlement').modal('show');
		}

		function card_settle(val){
			// console.log(val); return;
			let item_price = $("#money_point").val();
			location.href="/iam/pay_point.php?item_price=" + item_price + "&url=" + val;
		}

		function bank_settle(){
			let item_price = $("#money_point").val();
			if (!item_price) {
			    alert('금액을 입력해주세요');return;
            }
			$.ajax({
				type:"POST",
				url:"/makeData_item.php",
				data:{payMethod:"BANK", member_type:"포인트충전", allat_amt:item_price, pay_percent:90, allat_order_no:<?=$mid?>, point_val:1},
				dataType: 'json',
				success:function(data){
				    alert('신청되었습니다.');
					$("#ajax-loading").delay(10).hide(1);
				}
			});
			$("#mutong_settlement").modal('hide');
			$("#mutong_settle").modal('show');
		}

        let changedCash;

        function onChangePoint(cash) {
            changedCash = Number(cash) - Number($('#seedPoint').val()) - Number($('#money').val());
            if (changedCash < 0) {
                alert('현재 보유 캐시포인트 초과할수 없습니다.');
                return;
            }
            $("#myCash")[0].innerText = changedCash + ' p';
        }

        function onRequestCashChange() {
            let money = Number($('#money').val());
            if (money == 0) {

            } else {
                if (money < 10000) {
                    alert('현금전환은 10,000원 이상부터 가능합니다.');
                    return;
                }
            }
            let myCash = $('#myCash')[0].innerText;
            myCash = Number(myCash.replace('p','').trim());
            let seedPoint = Number($('#seedPoint').val());
            if(confirm("전환하시겠습니까?")){
                $.ajax({
                    type:"POST",
                    url:"ajax/apply_service_con_res.php",
                    data:{
                        payMethod : "<?=$_SESSION['iam_member_id']?>",
                        member_type : "씨드포인트충전",
                        allat_amt : seedPoint,
                        pay_percent : 90,
                        allat_order_no : <?=$mid?>,
                        point_val : 1,
                        mode : "cashtoseed",
                        seed_point : seedPoint
                    },
                    dataType: 'json',
                    success:function(data){
                        alert("캐시포인트 "+seedPoint+"P 가 씨드포인트로 전환되었습니다.");
                        if (money !== 0) {
                            $.ajax({
                                type:"POST",
                                url:"/makeData_item.php",
                                data:{
                                    payMethod : "BANK",
                                    member_type: "현금전환",
                                    allat_amt : money,
                                    pay_percent : 90,
                                    allat_order_no : <?=$mid?>,
                                    point_val : 1,
                                },
                                dataType: 'json',
                                success:function(data){
                                    alert("캐시포인트 " + money + "P 가 현금신청 되었습니다.");
                                    location.reload();
                                }
                            });
                        } else {
                            location.reload();
                        }
                    }
                });
            }

        }

		function point_settle_modal(price, title, url, sell_id){
			link = "http://<?=$HTTP_HOST?>/iam/contents.php?contents_idx=" + url;
			$("#settle_point").val(price);
			$("#sell_con_title").val(title);
			$("#sell_con_url").val(link);
			$("#sell_con_id").val(sell_id);
			$("#point_settlement").modal('show');
		}
		function point_mall_modal(type, price, data){
			$("#settle_point").val(price);
			$("#point_pay_data").val(data);
			$("#point_pay_type").val(type);
			$("#point_settlement").modal('show');
		}

		function point_settle(){
			var item_price = $("#settle_point").val();
			var item_title = $("#sell_con_title").val();
			var item_url = $("#sell_con_url").val();
			var item_id = $("#sell_con_id").val();
			var current_point = <?=$Gn_point?>;
			var member_type = '서비스콘텐츠/' + item_title;
			var method = item_id;
			var contents_url = item_url;
			if(item_price > current_point){
				alert("포인트가 부족합니다. 포인트 충전해주세요");
				return;
			}
			var point_pay_type = $("#point_pay_type").val();
			if(point_pay_type == 0 || point_pay_type == 4) {//서비스콘 구매
				if(point_pay_type == 4)
					member_type = "IAM몰 직거래/" + item_title;
				var settle_type = "service_con";
				$.ajax({
					type: "POST",
					url: "/makeData_item_point.php",
					data: {
						payMethod: method,
						member_type: member_type,
						allat_amt: item_price,
						pay_percent: 90,
						allat_order_no:<?=$mid?>,
						point_val: 1,
						service: true,
						contents_url: contents_url
					},
					dataType: 'json',
					success: function (data) {
						$("#ajax-loading").delay(10).hide(1);
						alert("구매되었습니다.");
						location.reload();
					}
				});
			}else if(point_pay_type == 5){//카드전송
				settle_type = "card_send";
				member_type = $("#point_pay_data").val();
				member_ids = $("#card_send_id").val();
				item_price = <?=$card_send_point?>;
				message = $("#alarm_msg").val();
				$.ajax({
					type: "POST",
					url: "/iam/card_con_send.php",
					data: {
						settle_type:settle_type,
						payMethod: member_ids,
						member_type: member_type,
						allat_amt: item_price,
						pay_percent: 90,
						allat_order_no:<?=$mid?>,
						point_val: 1,
						card_url:'<?=$card_url?>',
						message:message
					},
					dataType: 'json',
					success: function (data) {
						$("#ajax-loading").delay(10).hide(1);
						alert("전송되었습니다.");
						location.reload();
					}
				});
			}else if(point_pay_type == 6){//콘텐츠전송
				settle_type = "contents_send";
				member_type = $("#point_pay_data").val();
				member_ids = $("#contents_send_id").val();
				item_price = <?=$contents_send_point?>;
				$.ajax({
					type: "POST",
					url: "/iam/card_con_send.php",
					data: {
						settle_type:settle_type,
						payMethod: member_ids,
						member_type: member_type,
						allat_amt: item_price,
						pay_percent: 90,
						allat_order_no:<?=$mid?>,
						point_val: 1
						// message:message
					},
					dataType: 'json',
					success: function (data) {
						$("#ajax-loading").delay(10).hide(1);
						alert("전송되었습니다.");
						// location.reload();
					}
				});
			}else{
				var data = $("#point_pay_data").val();
				var mall_pay_type = data.substring(0,1) * 1;
				$.ajax({
					type: "POST",
					url: "/iam/ajax/mall.proc.php",
					data: {
						iam_mall_method: "pay_mall",
						iam_mall_pay_data: data,
						iam_mall_sell_price: item_price,
						pay_percent: 90,
						allat_order_no:<?=$mid?>
					},
					dataType: 'json',
					success: function (data) {
						$("#ajax-loading").delay(10).hide(1);
						if(data.result == "ok") {
							alert("구매되었습니다.");
							location.href = data.link;
						}
					}
				});
			}
		}

		function settlement(val, frm, auto_up){
			if(val == 'set'){
				<?php if($_SESSION['iam_member_id'] == ""){ ?>
				window.location = '/join.php';
				<?}
				else{?>
				$('#people_iam_modal').modal('hide');
				$('#settlement_finish_modal').modal('hide');
				$('#settlement_modal').modal('show');
				<?}?>
			}
			else if(val == "auto"){
				$("#intro_auto_update").modal("hide");
				$("#auto_settlement_modal").modal("show");
			}
			else if(val == 'finish'){
				var memberType = "";
				var con_cnt = 0;
				var ele = 0;
				var item_type = $('input[name=make_iam]:checked').val();
				var settlment_type = $('input[name=pay_type]:checked').val();
				var type_card = false;
				var type_bank = false;

				if(auto_up == "auto"){
					auto = 1;
					item_type = "auto";
					memberType = "오토데이트 포인트 충전";
					ele = $("#auto_point").val();
					con_cnt = 1;
					type_bank = $("#bank_type_auto").prop('checked');
					type_card = $("#card_type_auto").prop('checked');
				}
				else{
					$("input[name=make_iam]:checked").each(function() {
						var idVal = $(this).attr("id");
						ele = $("label[for='"+idVal+"']").attr('value');
					});
					if(ele == 33000) {
						memberType = 'IAM 1건 만들기';
						con_cnt = 1;
					} else if(ele == 132000) {
						memberType = 'IAM 10건 만들기';
						con_cnt = 10;
					} else if(ele == 297000) {
						memberType = 'IAM 30건 만들기';
						con_cnt = 30;
					} else if(ele == 660000) {
						memberType = 'IAM 100건 만들기';
						con_cnt = 100;
					}
					type_card = $("#card_type").prop('checked');
					type_bank = $("#bank_type").prop('checked');
				}
				if(item_type == "" || item_type == undefined) {
					toastr.error('상품을 선택 하세요.');
					return false;
				}
				if(settlment_type == "" || settlment_type == undefined) {
					toastr.error('결제종류를 선택 하세요.');
					return false;
				}

				$('#month_cnt').val('12');
				$('#price').val(ele);
				$('#total_amount').text(ele);
				$('#onestep1').val("ON");
				$('#onestep2').val("ON");
				$('#member_type').val(memberType);
				$('#add_phone').val(con_cnt);
				$('#db_cnt').val(9000);
				if(!frm.mid.value)
				{
					toastr.error('결제종류를 선택해주세요.');
					return false;
				}
				if(confirm('결제시작하시겠습니까?'))
				{
					if(type_bank == true) {
						//frm.target='pay_iframe';
						$.ajax({
							type:"POST",
							url:"/pay_cash_people.php",
							dataType:"json",
							data:$('#pay_form').serialize(),
							success:function(data){
								console.log("mutongjang!!!!!!");
								$("#ajax-loading").delay(10).hide(1);
								if(data == 1){
									if(auto == 1){
										auto_point_chung('<?=$_SESSION['iam_member_id']?>', 'bank', ele);
									}
									else{
										iam_item('<?=$_SESSION['iam_member_id']?>', 'buy', 'bank');
									}
								}
							}
						});
						return;
					} else if(type_card == true) {
						location.href = "/iam/pay_spoc.php?itemname="+memberType+"&totprice="+ele;
					}
				}
			}
			else if(val == 'make'){
				<?php if($_SESSION['iam_member_id'] == ""){ ?>
				window.location = '/iam/login.php';
				<?}
				else if($Gn_point < $point_ai){?>
				toastr.error("포인트 잔액이 없습니다. 결제하신후에 사용하세요.");
				<?}
				else{?>
				$('#people_iam_modal').modal('hide');
				$('#settlement_finish_modal').modal('hide');
				$('#auto_making_modal').modal('show');
				<?}?>
			}
			else if(val == "card_send"){
				$('#card_send_modal').modal('hide');
				$("#card_send_id").val('<?=$send_ids?>');
				$("#card_send_id_count").val(<?=$send_ids_cnt?> + '건');
				$('#card_send_id_count').data('num', <?=$send_ids_cnt?>);
				$('#card_send_modal_mem').modal('show');
			}
			else if(val == "contents_send"){
				$('#contents_send_modal').modal('hide');
				$("#contents_send_id").val('<?=$send_ids?>');
				$("#contents_send_id_count").val(<?=$send_ids_cnt?> + '건');
				$('#contents_send_id_count').data('num', <?=$send_ids_cnt?>);
				$('#contents_send_modal_mem').modal('show');
			}
		}

		function show_con_send(val){
			$('#contents_send_modal').modal('show');
			$("#point_pay_type").val(6);
			$("#point_pay_data").val(val);
		}

		//결제/이용내역 팝업 현시
		function show_contents(){
			$('#people_iam_modal').modal('hide');
			$('#card_send_modal').modal('hide');
			$('#contents_send_modal').modal('hide');
			$('#settlement_contents_modal').modal('show');
			search_people('search');
		}

		//분야별에 따르는 아이엠 자동생성 시작
		function start_making(){
			var web_type = $('input[name=web_type]:checked').attr('id');
			console.log(web_type);
			if(web_type == undefined){
				alert('수집분야를 설정 하세요.');
				return;
			}
			switch(web_type){
				case 'peopleid':
					start_making_web('people');
					break;
				case 'newsid':
					start_making_web('news');
					break;
				case 'mapid':
					start_making_web('map');
					break;
				case 'gmarketid':
					start_making_web('gmarket');
					break;
				case 'blogid':
					start_making_web('blog');
					break;
				case 'youtubeid':
					start_making_web('youtube');
					break;
				default:
					console.log("select type!");
					break;
			}
		}

		//goodhow 크롤링 서버에 요청 보내기, 상태값 얻어 오기
		function start_making_web(type) {
			var slt = 0;
			var url = '';
			var mem_id_status = '';
			var sel_type = '';
			var count_interval = 0;
			address = $("#people_web_address").val();
			var contents_keyword = '';
			if($("#people_contents_start_date").val() != ""){
				start_date = $("#people_contents_start_date").val().replace(/-/g, "");
				end_date = $("#people_contents_end_date").val().replace(/-/g, "");
			}
			contents_cnt = $("#people_contents_cnt").val();
			phone_num = $("#phone_num").val();
			mem_name = $("#blog_mem_name").val();
			mem_zy = $("#blog_mem_zy").val();
			mem_address = $("#blog_mem_address").val();
			state = $("#blog_mem_name").prop("disabled");
			if($("#update").prop("checked") == true){
				auto_data = true;
			}
			else{
				auto_data = false;
			}
			// console.log(start_date, end_date);return;

			sel_type = $('input[name=multi_westory_card_url]:checked').attr('value');
			// console.log(contents_cnt); return;
			if(type != 'blog' && type != 'news'){
				if(address == "" || contents_cnt == ""){
					alert('주소/갯수/폰번호를 입력하세요.');
					return;
				}
			}
			else{
				contents_keyword = $("#people_contents_key").val();
				if(contents_cnt == "" || contents_keyword == ""){
					alert('키워드/갯수를 입력하세요.');
					return;
				}
			}

			style_newmake = $('#newmake').attr('style').split(';')[1];
			style_mineid = $('#mineid').attr('style').split(';')[1];
			style_cardsel = $('#cardsel').attr('style').split(';')[1];
			if(style_newmake != "" && style_mineid != "" && style_cardsel != ""){
				alert("계정정보를 입력하세요.");
				return;
			}

			if(type == 'people'){
				if(address.substring(0, 37) != "https://search.naver.com/search.naver"){
					alert("웹주소 형식이 틀립니다. 옳바른 형식으로 이용 해주세요.");
					return;
				}
				if(contents_cnt > <?=$Gn_contents_limit;?>){
					alert("유명인물 아이엠 콘텐츠 제한 갯수는 <?=$Gn_contents_limit;?> 개 입니다.");
					return;
				}
				else{
					contents_keyword = $("#people_contents_key").val();
					url = "https://www.goodhow.com/crawler/Crawler_people";
					// url = "http://localhost:8082/Crawler_people/index_server.php";
				}
			}
			else if(type == "map"){
				if(address.substring(0, 24) != "https://map.naver.com/v5"){
					alert("웹주소 형식이 틀립니다. 옳바른 형식으로 이용 해주세요.");
					return;
				}
				url = "https://www.goodhow.com/crawler/Crawler_map";
				// url = "http://localhost:8082/Crawler_map/index_server.php";
			}
			else if(type == "gmarket"){
				if(address.substring(0, 29) != "http://minishop.gmarket.co.kr"){
					alert("웹주소 형식이 틀립니다. 옳바른 형식으로 이용 해주세요.");
					return;
				}
				url = "https://www.goodhow.com/crawler/crawler";
				// url = "http://localhost:8082/index_server.php";
			}
			else if(type == "blog"){
				if(state == false && (mem_name == '' || mem_zy == '' || mem_address == '')){
					alert("프로필 정보를 입력하세요");
					return;
				}
				contents_keyword = $("#people_contents_key").val();
				url = "https://www.goodhow.com/crawler/Crawler_blog";
				// url = "http://localhost:8082/Crawler_people_blog/index_server.php";
			}
			else if(type == "news"){
				if(state == false && (mem_name == '' || mem_zy == '' || mem_address == '')){
					alert("프로필 정보를 입력하세요");
					return;
				}
				contents_keyword = $("#people_contents_key").val();
				url = "https://www.goodhow.com/crawler/Crawler_blog/index_news.php";
				// url = "http://localhost:8082/Crawler_people_blog/index_news.php";
			}
			else if(type == 'youtube'){
				// alert(address.substring(0, 44));return;
				if((address.substring(0, 26) == "https://www.youtube.com/c/") || (address.substring(0, 32) == "https://www.youtube.com/channel/") || (address.substring(0, 44) == "https://www.youtube.com/results?search_query")){
					url = "https://www.goodhow.com/crawler/Crawler_youtube/index.php";
					// url = "http://localhost:8082/Crawler_youtube/index_server.php";
				}
				else{
					alert("웹주소 형식이 틀립니다. 옳바른 형식으로 이용 해주세요.");
					return;
				}
			}

			if(style_newmake == ""){//새계정 만들기
				slt = 1;
				var new_id = $("#newID").val();
				mem_id_status = new_id;
				pwd = $("#pwd").val();
				site = '<?=$user_site?>';
				recommend_id = '<?=$_SESSION['iam_member_id'];?>';
				console.log(address, contents_cnt, phone_num, new_id, pwd, site);
				style = $("#checkdup").attr('style');
				if(style.indexOf("blue") == -1){
					alert("아이디 중복확인을 하세요.");
					return;
				}
				else{
					$.ajax({
						type:"POST",
						dataType:"json",
						data:{new_account:true, auto_data:auto_data, address:address, contents_cnt:contents_cnt, phone_num:phone_num, new_id:new_id, pwd:pwd, contents_keyword:contents_keyword, start_date:start_date, end_date:end_date, site:site, recommend_id:recommend_id, mem_name:mem_name, mem_zy:mem_zy, mem_address:mem_address},
						url:url,
						success: function(data){
							$("#ajax-loading").delay(10).hide(1);
							console.log(data);
						}
					});
				}
				// console.log(new_id);
			}
			else if(style_mineid == ""){//내아이디로 만들기
				slt = 0;
				my_id = '<?=$_SESSION['iam_member_id'];?>';
				mem_id_status = my_id;
				$.ajax({
					type:"POST",
					dataType:"json",
					data:{my_account:true, auto_data:auto_data, address:address, contents_cnt:contents_cnt, phone_num:phone_num, my_id:my_id, contents_keyword:contents_keyword, start_date:start_date, end_date:end_date, mem_name:mem_name, mem_zy:mem_zy, mem_address:mem_address},
					url:url,
					success: function(data){
						$("#ajax-loading").delay(10).hide(1);
						console.log(data);
					}
				});
				// console.log("OK mineid!!");
			}
			else if(style_cardsel == ""){//카드에 추가하기
				slt = 2;
				my_id = '<?=$_SESSION['iam_member_id'];?>';
				mem_id_status = my_id;
				if(sel_type == null){
					alert("카드를 선택하세요."); return;
				}
				$.ajax({
					type:"POST",
					dataType:"json",
					data:{card_sel:true, address:address, contents_cnt:contents_cnt, my_id:my_id, contents_keyword:contents_keyword, start_date:start_date, end_date:end_date, short_url:sel_type},
					url:url,
					success: function(data){
						$("#ajax-loading").delay(10).hide(1);
						console.log(data);
					}
				});
			}

			alert("지금 웹에서 관련정보를 가지고 신청한 아이엠생성하고 있습니다. 생성중에 다른 작업을 할수 있습니다. 완료되면 알람이 나타 납니다. 조금만 기다려 주세요.");
			$("#startmaking").attr('disabled', true);

			//크롤링 진행 상태값 얻어 오기
			if(type == 'people'){
				var interval_people = setInterval(function() {
					console.log(count_interval);
					$.ajax({
						type:"POST",
						url:"/admin/people_crawling_status.php",
						dataType:"json",
						data:{get:true, address:address, contents_cnt:contents_cnt, mem_id_status:mem_id_status},
						success:function (data){
							$("#ajax-loading").delay(10).hide(1);
							console.log(data.status);
							if(data.status == 0){
								$("#startmaking").attr('disabled', false);
								clearInterval(interval_people);
								iam_item('<?=$_SESSION['iam_member_id']?>', 'use', '인물', data.keyword, mem_id_status);
								$("#people_mem_id").html(data.mem_id);
								$('#auto_making_modal').modal('hide');
								if(slt == 1){
									$('#finish_login').modal('show');
								}
								else if(slt == 0){
									$('#finish_login_own').modal('show');
								}
								else if(slt == 2){
									$('#finish_login_cardsel').modal('show');
								}
							}
							else if(data.status == 2){
								alert("콘텐츠 데이터가 없거나 이미 다 가져 왔습니다. 웹링크를 다시 확인해 주세요.");
								clearInterval(interval_people);
								location.reload();
							}
							else if(data.status == 3 || count_interval == 4){
								alert("아이엠 자동 생성중 오류가 발생 하였습니다.");
								clearInterval(interval_people);
								location.reload();
							}
						}
					});
					count_interval++;
				}, 5000);
			}
			else if(type == 'news'){
				var interval_news = setInterval(function() {
					console.log(count_interval);
					$.ajax({
						type:"POST",
						url:"/admin/news_crawling_status.php",
						dataType:"json",
						data:{get:true, contents_cnt:contents_cnt, mem_id_status:mem_id_status},
						success:function (data){
							$("#ajax-loading").delay(10).hide(1);
							console.log(data.status);
							if(data.status == 0){
								$("#startmaking").attr('disabled', false);
								clearInterval(interval_news);
								iam_item('<?=$_SESSION['iam_member_id']?>', 'use', '뉴스', data.keyword, mem_id_status);
								$("#news_mem_id").html(data.mem_id);
								$('#auto_making_modal').modal('hide');
								if(slt == 1){
									$('#finish_login').modal('show');
								}
								else if(slt == 0){
									$('#finish_login_own').modal('show');
								}
								else if(slt == 2){
									$('#finish_login_cardsel').modal('show');
								}
							}
							else if(data.status == 2){
								alert("콘텐츠 데이터가 없거나 이미 다 가져 왔습니다. 웹링크를 다시 확인해 주세요.");
								clearInterval(interval_news);
								location.reload();
							}
							else if(data.status == 3 || count_interval == 4){
								alert("아이엠 자동 생성중 오류가 발생 하였습니다.");
								clearInterval(interval_news);
								location.reload();
							}
						}
					});
					count_interval++;
				}, 5000);
			}
			else if(type == "map"){
				var interval_map = setInterval(function(){
					$.ajax({
						type:"POST",
						url:"/admin/map_crawling_status.php",
						dataType:"json",
						data:{get:true, address:address, contents_cnt:contents_cnt, mem_id_status:mem_id_status},
						success:function(data){
							$("#ajax-loading").delay(10).hide(1);
							console.log(data.status);
							if(data.status == 0){
								$("#startmaking").attr('disabled', false);
								clearInterval(interval_map);
								iam_item('<?=$_SESSION['iam_member_id']?>', 'use', '지도', 'MAP', mem_id_status);
								$("#people_mem_id").html(data.mem_id);
								$('#auto_making_modal').modal('hide');
								if(slt == 1){
									$('#finish_login').modal('show');
								}
								else if(slt == 0){
									$('#finish_login_own').modal('show');
								}
								else if(slt == 2){
									$('#finish_login_cardsel').modal('show');
								}
							}
							else if(data.status == 2){
								alert("콘텐츠 데이터가 없거나 이미 다 가져 왔습니다. 웹링크를 다시 확인해 주세요.");
								clearInterval(interval_map);
								location.reload();
							}
							else if(data.status == 3 || count_interval == 4){
								alert("아이엠 자동 생성중 오류가 발생 하였습니다.");
								clearInterval(interval_map);
								location.reload();
							}
						}
					})
					count_interval++;
				}, 5000);
			}
			else if(type == "gmarket"){
				var state_sel = slt;
				var interval_gmarket = setInterval(function() {
					$.ajax({
						type:"POST",
						url:"/admin/get_db_crawling_status.php",
						dataType:"json",
						data:{get:true, contents_cnt:contents_cnt, mem_id_status:mem_id_status},
						success:function (data){
							$("#ajax-loading").delay(10).hide(1);
							console.log(data.status);
							if(data.status == 0){
								$("#startmaking").attr('disabled', false);
								clearInterval(interval_gmarket);
								iam_item('<?=$_SESSION['iam_member_id']?>', 'use', '지마켓', 'GMARKET', mem_id_status);
								$("#people_mem_id").html(data.mem_id);
								$('#auto_making_modal').modal('hide');
								if(slt == 1){
									$('#finish_login').modal('show');
								}
								else if(slt == 0){
									$('#finish_login_own').modal('show');
								}
								else if(slt == 2){
									$('#finish_login_cardsel').modal('show');
								}
							}
							else if(count_interval == 4){
								alert("아이엠 자동 생성중 오류가 발생 하였습니다.");
								clearInterval(interval_gmarket);
								location.reload();
							}
						}
					});
					count_interval++;
				}, 5000);
			}
			else if(type == 'blog'){
				var interval_blog = setInterval(function() {
					$.ajax({
						type:"POST",
						url:"/admin/blog_crawling_status.php",
						dataType:"json",
						data:{get:true, contents_cnt:contents_cnt, mem_id_status:mem_id_status},
						success:function (data){
							$("#ajax-loading").delay(10).hide(1);
							console.log(data.status);
							if(data.status == 0){
								$("#startmaking").attr('disabled', false);
								clearInterval(interval_blog);
								iam_item('<?=$_SESSION['iam_member_id']?>', 'use', '블로그', data.keyword, mem_id_status);
								$("#people_mem_id").html(data.mem_id);
								$('#auto_making_modal').modal('hide');
								if(slt == 1){
									$('#finish_login').modal('show');
								}
								else if(slt == 0){
									$('#finish_login_own').modal('show');
								}
								else if(slt == 2){
									$('#finish_login_cardsel').modal('show');
								}
							}
							else if(data.status == 2){
								alert("콘텐츠 데이터가 없거나 이미 다 가져 왔습니다. 웹링크를 다시 확인해 주세요.");
								clearInterval(interval_blog);
								location.reload();
							}
							else if(data.status == 3){
								alert("아이엠 자동 생성중 오류가 발생 하였습니다.");
								clearInterval(interval_blog);
								location.reload();
							}
						}
					});
					// count_interval++;
				}, 5000);
			}
			else if(type == 'youtube'){
				var interval_youtube = setInterval(function(){
					$.ajax({
						type:"POST",
						url:"/admin/youtube_crawling_status.php",
						dataType:"json",
						data:{get:true, address:address, contents_cnt:contents_cnt, mem_id_status:mem_id_status},
						success:function(data){
							$("#ajax-loading").delay(10).hide(1);
							console.log(data.status);
							if(data.status == 0){
								$("#startmaking").attr('disabled', false);
								clearInterval(interval_youtube);
								iam_item('<?=$_SESSION['iam_member_id']?>', 'use', '유튜브', 'YOUTUBE', mem_id_status);
								$("#people_mem_id").html(data.mem_id);
								$('#auto_making_modal').modal('hide');
								if(slt == 1){
									$('#finish_login').modal('show');
								}
								else if(slt == 0){
									$('#finish_login_own').modal('show');
								}
								else if(slt == 2){
									$('#finish_login_cardsel').modal('show');
								}
							}
							else if(data.status == 2){
								alert("콘텐츠 데이터가 없거나 이미 다 가져 왔습니다. 웹링크를 다시 확인해 주세요.");
								clearInterval(interval_youtube);
								location.reload();
							}
							else if(data.status == 3){
								alert("아이엠 자동 생성중 오류가 발생 하였습니다.");
								clearInterval(interval_youtube);
								location.reload();
							}
						}
					});
					// count_interval++;
				}, 5000);
			}
		}

		//아이엠 자동만들기 아이템에 따르는 포인트 결제
		function iam_item(memid, action, channel, keyword, id_status){
			if(action == 'use'){
				$.ajax({
					type:"POST",
					url:"/iam/iam_item_mng.php",
					dataType:"json",
					data:{use:true, memid:memid, mem_type:'AI카드', channel:channel, keyword:keyword, id_status:id_status},
					success:function (data) {
						$("#ajax-loading").delay(10).hide(1);
						console.log(data);
						// alert("결제 되었습니다!");
					}
				})
			}
		}
		//새계정 만들기에서 아이디 증복 검사
		function id_check1() {
			if (!$('#newID').val()) {
				$('#newID').focus();
				return;
			}
			if ($('#newID').val().length < 4) {
				alert('아이디는 4자~15자를 입력해 주세요.');
				return;
			}
			var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
			if (!pattern.test($('#newID').val())) {
				alert('영문 소문자와 숫자만 사용이 가능합니다.');
				$('#id_status').val("");
				$('#newID').val("");
				$('#newID').focus();
				return;
			}
			$.ajax({
				type: "POST",
				url: "/ajax/ajax_checkid.php",
				dataType:"json",
				data: {id:$('#newID').val()},
				// contentType: false,
				// processData: false,
				success: function(data) {
					$("#ajax-loading").delay(10).hide(1);
					if (data.result == "0") {
						alert("이미 가입되어있는 아이디 입니다.");
						$('#id_status').val("");
						$('#newID').val("");
						$('#newID').focus();
					} else {
						// alert('사용 가능한 아이디 입니다.');
						$("#checkdup").attr('style', 'background-color: skyblue;');
						$("#id_text").prop('hidden', false);
					}
				}
			});
		}

		//결제/이용내역 팝업창에서 아이디 검색, 더보기 기능
		function search_people(val){
			start = $("#search_start_date").val();
			end = $("#search_end_date").val();
			// type = $("#use_buy_type").val();
			// item_type='AI카드';
			// ID = $("#search_ID").val();
			see = 'false';
			if(val == 'more') see = 'see_more';

			// console.log(start, end, type, ID, see, val);
			$.ajax({
				type:"POST",
				url:"/ajax/use_contents.php",
				dataType:"html",
				data:{start:start, end:end, ID:'<?=$_SESSION['iam_member_id'];?>', more:see},
				success: function(data){
					$("#ajax-loading").delay(10).hide(1);
					// console.log(data);
					$("#contents_side").html(data);
				}
			})
		}

		function del_ai_list(val){
			if(confirm("삭제하시겠습니까?")){
				$.ajax({
					type:"POST",
					url:"/iam/card_con_send.php",
					dataType:"json",
					data:{settle_type:"delete_ai", no:val, ID:'<?=$_SESSION['iam_member_id']?>'},
					success:function(data){
						$("#ajax-loading").delay(10).hide(1);
						alert("삭제되었습니다.");
						search_people();
					}
				})
			}
		}

		//내아이디로 만들기후 생성된 카드로 이동
		function go_iamlink(){
			$.ajax({
				type:"POST",
				url:"/ajax/get_iamlink.php",
				dataType:"json",
				data:{get:true, id:'<?=$_SESSION['iam_member_id']?>'},
				success: function(data){
					$("#ajax-loading").delay(10).hide(1);
					console.log(data.url);
					location.href = "?" + data.url + data.mem_code;
				}
			})
		}

		function go_contentslink(){
			location.reload();
		}

		//수집분야별에 따르는 설정값 입력창 현시
		function show_keyword(val){
			if(val == 'people'){
				$("#contents_key").attr('style', 'width:100%;display:inline-table;');
				$("#contents_time").attr('style', 'width:100%;display:inline-table;');
				$("#web_address").attr('style', 'width:100%;display:inline-table;');
				$("#mem_name").prop("hidden", true);
				$("#mem_company").prop("hidden", true);
				$("#mem_address").prop("hidden", true);
				if($("#selcard").prop("checked")){
					$("#phonenum").prop("hidden", true);
				}
				else{
					$("#phonenum").prop("hidden", false);
				}
				$("#my_id_select").show();
			}
			else if(val == 'news'){
				$("#contents_key").attr('style', 'width:100%;display:inline-table;');
				$("#contents_time").attr('style', 'width:100%;display:inline-table;');
				$("#web_address").attr('style', 'width:100%;display:none;');
				if($("#selcard").prop("checked")){
					$("#mem_name").prop("hidden", true);
					$("#mem_company").prop("hidden", true);
					$("#mem_address").prop("hidden", true);
					$("#phonenum").prop("hidden", true);
				}
				else{
					$("#mem_name").prop("hidden", false);
					$("#mem_company").prop("hidden", false);
					$("#mem_address").prop("hidden", false);
					$("#phonenum").prop("hidden", false);
				}
				$("#my_id_select").show();
			}
			else if(val == 'blog' || val == 'news'){
				if(val == 'blog'){
					alert("특정 블로그에서 키워드와 매칭되는 게시물 크롤링을 원하시면 웹주소입력란에 다음의 블로그 주소를 입력하세요.\n 예시 : https://blog.naver.com/abcd123\n\n웹주소에 입력을 안하면 전체 블로그에서 키워드와 매칭되는 게시물을 크롤링합니다.");
				}
				$("#contents_key").attr('style', 'width:100%;display:inline-table;');
				$("#contents_time").attr('style', 'width:100%;display:inline-table;');
				$("#web_address").attr('style', 'width:100%;display:inline-table;');
				if($("#selcard").prop("checked")){
					$("#mem_name").prop("hidden", true);
					$("#mem_company").prop("hidden", true);
					$("#mem_address").prop("hidden", true);
					$("#phonenum").prop("hidden", true);
				}
				else{
					$("#mem_name").prop("hidden", false);
					$("#mem_company").prop("hidden", false);
					$("#mem_address").prop("hidden", false);
					$("#phonenum").prop("hidden", false);
				}
				$("#my_id_select").show();
			}
			else if(val == 'youtube'){
				$("#contents_key").attr('style', 'width:100%;display:none;');
				$("#contents_time").attr('style', 'width:100%;display:none;');
				$("#web_address").attr('style', 'width:100%;display:inline-table;');
				if($("#selcard").prop("checked")){
					$("#mem_name").prop("hidden", true);
					$("#mem_company").prop("hidden", true);
					$("#mem_address").prop("hidden", true);
					$("#phonenum").prop("hidden", true);
				}
				else{
					$("#mem_name").prop("hidden", false);
					$("#mem_company").prop("hidden", false);
					$("#mem_address").prop("hidden", false);
					$("#phonenum").prop("hidden", false);
				}
				$("#my_id_select").show();
			}
			else if(val == 'gmarket' || val == 'navershop'){
				$("#contents_key").attr('style', 'width:100%;display:none;');
				$("#contents_time").attr('style', 'width:100%;display:none;');
				$("#mem_name").prop("hidden", true);
				$("#mem_company").prop("hidden", true);
				$("#mem_address").prop("hidden", true);
				$("#phonenum").prop("hidden", true);
				$("#web_address").attr('style', 'width:100%;display:inline-table;');
				$("#my_id_select").show();
			}
			else if(val == 'map'){
				$("#contents_key").attr('style', 'width:100%;display:none;');
				$("#contents_time").attr('style', 'width:100%;display:none;');
				$("#mem_name").prop("hidden", true);
				$("#mem_company").prop("hidden", true);
				$("#mem_address").prop("hidden", true);
				$("#phonenum").prop("hidden", true);
				$("#web_address").attr('style', 'width:100%;display:inline-table;');
				$("#my_id_select").hide();
			}
			else{
				$("#contents_key").attr('style', 'width:100%;display:none;');
				$("#contents_time").attr('style', 'width:100%;display:none;');
				$("#mem_name").prop("hidden", true);
				$("#mem_company").prop("hidden", true);
				$("#mem_address").prop("hidden", true);
				if($("#selcard").prop("checked")){
					$("#phonenum").prop("hidden", true);
				}
				else{
					$("#phonenum").prop("hidden", false);
				}
				$("#web_address").attr('style', 'width:100%;display:inline-table;');
				$("#my_id_select").show();
			}
		}

		$(".sharepoint").on('click', function(){
			$('#people_iam_modal').modal('hide');
			$('#card_send_modal').modal('hide');
			$('#contents_send_modal').modal('hide');
			$('#place_before').val($(this).attr('data'));
			$('#sharepoint_modal').modal('show');
		});

		//포인트 쉐어하기
		var current_point = 0;
		function start_sharing(){
			<?php
				if ($_SESSION['iam_member_id']) {
			?>
			current_point = <?=$Gn_point?>;
			current_cash = <?=$Gn_cash?>;
			<?}?>
			send_id = '<?=$_SESSION['iam_member_id'];?>';
			receive_id = $("#share_id").val();
			var share_point = $("#share_point").val();
			var share_cash = $("#share_cash").val();
			if(share_point == '') share_point = 0;
			if(share_cash == '') share_cash = 0;
			if(send_id == '' || receive_id == '' || (share_point == 0 && share_cash == 0)){
				alert("아이디와 포인트를 입력하세요.");
				return;
			}
			if(current_point < share_point){
				alert("현재 보유씨드포인트는 <?=$Gn_point?> P 입니다.");
				return;
			}
			if(current_cash < share_cash){
				alert("현재 보유캐시포인트는 <?=$Gn_cash?> P 입니다.");
				return;
			}
			console.log(share_point, share_cash);
			// return;
			$.ajax({
				type:"POST",
				url:"/iam/share_point.php",
				dataType:"json",
				data:{send_id:send_id, receive_id:receive_id, share_point:share_point, share_cash:share_cash},
				success:function(data){
					$("#ajax-loading").delay(10).hide(1);
					console.log(data);
					if(data == 0){
						alert("잘못된 회원 아이디 입니다.");
					}
					else if(data == 1){
						if(share_point && !share_cash)
							alert(send_id+"의 계정에서 씨드포인트 "+share_point+"P 가 "+receive_id+" 계정으로 이전되었습니다.");
						if(!share_point && share_cash)
							alert(send_id+"의 계정에서 캐시포인트 "+share_cash+"P 가 "+receive_id+" 계정으로 이전되었습니다.");
						if(share_point && share_cash)
							alert(send_id+"의 계정에서 씨드포인트 "+share_point+"P, 캐시포인트 "+share_cash+"P 가 "+receive_id+" 계정으로 이전되었습니다.");
						location.reload();
					}
				}
			})
		}

		//팝업에서 뒤로가기 기능
		function goback(val){
			if(val == "settlment"){
				$('#settlement_modal').modal('hide');
				$('#people_iam_modal').modal('show');
			}
			else if(val == "making"){
				$('#auto_making_modal').modal('hide');
				$('#people_iam_modal').modal('show');
			}
			else if(val == "more"){
				$('#settlement_contents_modal').modal('hide');
				$('#people_iam_modal').modal('show');
			}
			else if(val == "share"){
				$('#sharepoint_modal').modal('hide');
				if($("#place_before").val() == "webpage"){
					$('#people_iam_modal').modal('show');
				}
				else if($("#place_before").val() == "card"){
					$('#card_send_modal').modal('show');
				}
				else{
					$('#contents_send_modal').modal('show');
				}
			}
			else if(val == "settlment_auto"){
				$("#auto_settlement_modal").modal('hide');
				$("#auto_making_modal").modal('show');
			}
		}

		//오토데이트 설명 팝업 현시
		function show_update_popup(){
			$('#auto_making_modal').modal('hide');
			$('#intro_auto_update').modal('show');
		}

		function show_making(){
			$('#auto_making_modal').modal('show');
		}

		//오토데이트 팝업 현시
		function set_auto_update(val){
			if(val == 'hide'){
				$("#update").prop("checked", false);
				$("#people_contents_cnt").attr('disabled', false);
			}
			if($("#update").prop("checked") == true){
				$("#auto_update_contents").attr("style", "display:block;");
				$("#startmaking").attr('disabled', true);
				// $("#people_contents_cnt").attr('disabled', true);
			}
			else{
				$("#auto_update_contents").attr("style", "display:none;");
				$("#startmaking").attr('disabled', false);
				$("#people_contents_cnt").attr('disabled', false);
			}
		}

		//오토데이트 시간설정부분 현시
		function show_hour(){
			if($("#contents_auto_upload_time").prop("checked") == true){
				$("#24_hours").attr("style", "display:block;");
			}
			else{
				$("#24_hours").attr("style", "display:none;");
			}
		}

		//오토데이트 설정
		function start_auto_update(){
			var slt = 0;
			style_newmake = $('#newmake').attr('style').split(';')[1];
			style_mineid = $('#mineid').attr('style').split(';')[1];
			style_cardsel = $('#cardsel').attr('style').split(';')[1];
			if(style_newmake == ""){//새계정 만들기
				slt = 1;
			}
			else if(style_mineid == ""){//내아이디로 만들기
				slt = 0;
			}
			else if(style_cardsel == ""){//내카드 선택
				slt = 2;
			}

			$auto_point = $("#usable_point").val();

			if($auto_point < 1100){
				alert("포인트가 부족합니다. 충전후 이용해 주세요.");
				return;
			}
			if($("#upload_time").val() == ""){
				alert("시간을 선택해 주세요.");
				return;
			}
			// console.log(slt); return;
			if(confirm("포인트 차감 안내"+"\n"+"콘텐츠 오토데이트 기능을 사용하게 되면 선택한 IAM카드에서 발생하는 트래픽 비용으로 매월 1100원의 포인트가 차감됩니다. 포인트가 부족할 경우 오토데이트가 중지됩니다. 미리 포인트를 충분히 충전해두시기 바랍니다.")){
				address = $("#people_web_address").val();
				contents_keyword = $("#people_contents_key").val();
				if(slt == 1){
					my_id = $("#newID").val();
				}
				else{
					my_id = '<?=$_SESSION['iam_member_id'];?>';
				}
				web_type = $('input[name=web_type]:checked').attr('id');
				sel_type = $('input[name=multi_westory_card_url]:checked').attr('value');
				upload_time = $("#upload_time").val();
				console.log('hello');
				$.ajax({
					type:"POST",
					url:"/iam/auto_update_contents.php",
					dataType:"json",
					data:{address:address, my_id:my_id, contents_keyword:contents_keyword, sel_position:slt, short_url:sel_type, web_type:web_type, upload_time:upload_time},
					success:function (data) {
						console.log(data);
						$("#ajax-loading").delay(10).hide(1);
						if(data == 3){
							alert("이 카드로 오토데이트가 진행중입니다.");
							return;
						}
						else if(data == 1){
							alert("오토데이트가 등록되었습니다.");
							$("#startmaking").prop("disabled", false);
							// location.reload();
						}
						// alert("결제 되었습니다!");
					}
				});
			}
		}

		//오토데이트 시간 1일에 최대 3번까지 입력 기능
		function limit_sel_hour(){
			var sel_time = new Array();
			var cnt;
			$('input[name=select_hour]:checked').each(function() {
				var idVal = $(this).attr("id");
				cnt = sel_time.push($("label[for='"+idVal+"']").attr('value'));
				if(cnt > 3){
					alert('최대 3개까지 선택할수 있습니다.');
					$('input[id='+idVal+']').prop("checked", false);
					return;
				}
				$("#upload_time").val(sel_time.join(","));
			});
		}

		//오토데이트 관리 부분 현시
		/*function show_manage_auto(val){
			console.log('auto', val);
			if(val == 0){
				$("#manage_auto_update").prop("hidden", false);
				search_auto_data();
			}
			else{
				$("#manage_auto_update").prop("hidden", true);
			}
		}*/

		//오토데이트 팝업 현시
		function show_making_popup(){
			$("#auto_making_modal").modal("show");
		}

		//오토데이트 포인트 충전하기
		function auto_point_chung(memid, type, price){
			$.ajax({
				type:"POST",
				url:"/iam/gn_auto_point_chung.php",
				dataType:"json",
				data:{chung:true, memid:memid},
				success:function (data) {
					$("#ajax-loading").delay(10).hide(1);
					console.log(data);
					// alert("결제 되었습니다!");
					$('#auto_settlement_modal').modal('hide');
					if(type == "bank"){
						$("#buy_point").text(price);
						$('#settlement_mutongjang_modal').modal('show');
					}
					else{
						alert("결제되었습니다.");
						location.reload();
					}
				}
			});
		}

		var auto_id = 1;
		//오토데이트 설정값 변경 팝업 현시
		function edit_auto_data(id){
			$.ajax({
				type:"POST",
				url:"/iam/get_auto_conset.php",
				dataType:"json",
				data:{id:id},
				success:function (data){
					$("#ajax-loading").delay(10).hide(1);
					console.log(data.state);
					if(data.state == 2){
						alert('현재 중지되어 있는 상태입니다.');
						return;
					}
					$("input[id="+data.web_type+"]").prop("checked", true);
					if($("#mapid").prop("checked") == true || $("#youtubeid").prop("checked") == true){
						$("#contents_key").attr('style', 'width:100%;display:none;');
					}
					$("#people_web_address").val(data.web_address);
					$("#people_contents_key").val(data.keyword);
					$("#selcard").prop("checked", true);
					$("input[id=multi_westory_card_url_"+data.card_url+"]").prop("checked", true);
					$("#contents_auto_upload_time").prop("checked", true);
					$("#upload_time").val(data.get_time);
					if(data.get_time.indexOf(",") == -1){
						$('input[name=select_hour]').prop("checked", false);
						$("input[id="+data.get_time+"hour]").prop("checked", true);
					}
					else{
						$('input[name=select_hour]').prop("checked", false);
						var arr = data.get_time.split(",");
						for(var i = 0; i < arr.length; i++){
							$("input[id="+arr[i]+"hour]").prop("checked", true);
						}
					}
					$("#update").prop("checked", true);
					$("#24_hours").show();
					$(".btn-submit.start").hide();
					$(".btn-submit.edit").show();
					$("#cardsel").show();
					$("#auto_update_contents").show();
					$("#startmaking").prop("disabled", true);
					$("#auto_making_modal").modal("show");
					auto_id = data.id;
				}
			});
		}

		//오토데이트 설정값 변경
		function edit_auto_update(){
			if(confirm("변경 하시겠습니까?")){
				address = $("#people_web_address").val();
				my_id = '<?=$_SESSION['iam_member_id'];?>';
				if($('#contents_key').attr('style').split(';')[1] == ""){
					contents_keyword = $("#people_contents_key").val();
				}
				else{
					contents_keyword = "";
				}
				web_type = $('input[name=web_type]:checked').attr('id');
				sel_type = $('input[name=multi_westory_card_url]:checked').attr('value');
				upload_time = $("#upload_time").val();
				console.log("edit_auto_data!!!"+auto_id);
				$.ajax({
					type:"POST",
					url:"/iam/auto_update_contents.php",
					dataType:"json",
					data:{auto_id:auto_id, address:address, my_id:my_id, contents_keyword:contents_keyword, short_url:sel_type, web_type:web_type, upload_time:upload_time},
					success:function (data) {
						$("#ajax-loading").delay(10).hide(1);
						console.log(data);
						alert("오토데이트가 변경되었습니다.");
						location.reload();
					}
				});
			}
		}

		//오토데이트 중지/진행
		function stop_auto_data(val){
			my_id = '<?=$_SESSION['iam_member_id'];?>';
			$.ajax({
				type:"POST",
				url:"/iam/auto_update_contents.php",
				dataType:"json",
				data:{stop_auto:true, my_id:my_id, id:val},
				success:function (data) {
					$("#ajax-loading").delay(10).hide(1);
					console.log(data);
					if(data == 1){
						alert("오토데이트가 중지상태로 되었습니다.");
					}
					else if(data == 2){
						alert("오토데이트가 진행상태로 되었습니다.");
					}
					else if(data == 3){
						alert('포인트가 부족하여 진행상태로 될수 없습니다.');
					}
					location.reload();
				}
			});
		}

		//오토데이트 삭제
		function remove_auto_data(val){
			my_id = '<?=$_SESSION['iam_member_id'];?>';
			if(confirm("정말 삭제 하겠습니까?")){
				$.ajax({
					type:"POST",
					url:"/iam/auto_update_contents.php",
					dataType:"json",
					data:{remove_auto:true, my_id:my_id, id:val},
					success:function (data) {
						$("#ajax-loading").delay(10).hide(1);
						console.log(data);
						if(data == 1){
							alert("오토데이트가 삭제 되었습니다.");
						}
						location.reload();
					}
				});
			}
		}

		//오토데이터 관리 더보기 기능
		function search_auto_data(val){
			my_id = '<?=$_SESSION['iam_member_id'];?>';
			$.ajax({
				type:"POST",
				url:"/ajax/auto_contents_more.php",
				dataType:"html",
				data:{memid:my_id, more:val},
				success:function (data) {
					$("#ajax-loading").delay(10).hide(1);
					$("#auto_contetns_manage_show").html(data);
				}
			})
		}
		//자동아이엠만들기 스크립트 끝
        $(".popup_holder2").click(function(){
            $(".popup_box1").hide();
            $(".popup_box2").toggle();
            if('<?=$cur_win?>' == "we_story")
                $("#we_story input").focus();
            else if('<?=$cur_win?>' == "my_info")
                $("#my_info input").focus();
        });
        function show_automember_list(){
			$("#mypage-modalwindow").modal("hide");
			//$("#auto_list_modal").modal("show");
			$.ajax({
				type: "GET",
				url: "/iam/ajax/modal_auto_list.php",
				data: "start_date=" + '<?=$_REQUEST['search_start_date']?>' + 
				"&end_date=" + '<?=$_REQUEST['search_end_date']?>' ,
				success: function(html) {
					$("#auto_list_modal").html('');
					$("#auto_list_modal").append(html);
					$("#auto_list_modal").modal("show");
				}
			});
        }
        function search_auto(){
            var start = $("#search_start_date1").val();
            var end = $("#search_end_date1").val();
            $.ajax({
                type:"POST",
                url:"/ajax/edit_event.php",
                dataType:"html",
                data:{
                    search:true,
                    start:start,
                    end:end,
                    ID:'<?=$_SESSION['iam_member_id'];?>'
                },
                success: function(data){
					$("#ajax-loading").delay(10).hide(1);
                    $("#contents_side1").html(data);
                }
            })
        }
        function newpop(str){
            window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
        }
        function edit_ev(id){
            $('#auto_list_modal').modal('hide');
            var start = '';
            var end = '';
            var item_type = '';
            var see = '';
            $.ajax({
                type:"POST",
                url:"/ajax/edit_event.php",
                dataType:"json",
                data:{edit_ev:true, start:start, end:end, item_type:item_type, ID:'<?=$_SESSION['iam_member_id'];?>', more:see, id:id},
                success: function(data){
					$("#ajax-loading").delay(10).hide(1);
                    $("#event_idx").val(data.id);
                    $("#event_title").val(data.event_title);
                    $("#event_desc").val(data.event_desc);
                    $("#btn_title").val(data.btn_title);
                    $("#btn_link").val(data.btn_link);
                    $("#short_url").val(data.short_url);
					$("#event_req_link").val(data.event_link);
                    $("#read_cnt").val(data.read_cnt);
                    $("#autojoin_img_event").html(data.autojoin_img);
                    $("#regdate1").val(data.regdate);

					if(data.reserv_sms_id != undefined){
						$("#step_title").val(data.step_title);
						$("#step_phone").val(data.send_num);
						$("#step_cnt").val(data.step);
						$("#step_allow_state").val(data.step_idx);

						if(data.step_allow_state == 1){
							$("#step_allow_state").prop("checked", true);
						}
						else{
							$("#step_allow_state").prop("checked", false);
						}

						var href = '<a class="reserv_btn" href="/mypage_reservation_create.php?sms_idx='+data.reserv_sms_id+'" target="_blank">GO</a>';
						$("#step_info").html(href);
						$("#step_info_tr").show();
					}
					else{
						$("#step_info_tr").hide();
					}

                    $('input[class=we_story_radio1]').prop("checked", false);
                    card_short_url = data.card_short_url;
                    var pos = card_short_url.search(",");
                    if(pos == -1){
                        $('input[id=multi_westory_card_url1_'+card_short_url+']').prop("checked", true);
                    }else{
                        var arr = card_short_url.split(",");
                        for(var k = 0; k < arr.length; k++){
                            $('input[id=multi_westory_card_url1_'+arr[k]+']').prop("checked", true);
                        }
                    }
                    $('#auto_list_edit_modal').modal('show');
                }
            });
        }
        function delete_ev(id){
            if(confirm("삭제하시겠습니까?")){
                $.ajax({
                    type:"POST",
                    url:"/ajax/edit_event.php",
                    dataType:"json",
                    data:{delete:true, id:id},
                    success: function(data){
						$("#ajax-loading").delay(10).hide(1);
                        console.log(data);
                        if(data == 1){
                            alert('삭제 되었습니다.');
                            window.location.reload();
                        }
                    }
                })
            }
            else{
                return;
            }
        }
        function save_edit_ev(){
            $("#dForm_edit").submit();
        }
        function limit_selcard1(){
            var sel_card1 = new Array();
            var cnt1;
            $('input[class=we_story_radio1]:checked').each(function() {
                var idVal1 = $(this).attr("value");
                // console.log(idVal);
                cnt1 = sel_card1.push(idVal1);
                if(cnt1 > 4){
                    alert('최대 4개까지 선택할수 있습니다.');
                    $('input[id=multi_westory_card_url1_'+idVal1+']').prop("checked", false);
                    return;
                }
                $("#card_short_url").val(sel_card1.join(","));
            });
        }
        //팝업에서 뒤로가기 기능
        function goback(val){
            if(val == "settlment"){
                $('#settlement_modal').modal('hide');
                $('#people_iam_modal').modal('show');
            }else if(val == "making"){
                $('#auto_making_modal').modal('hide');
                $('#people_iam_modal').modal('show');
            }else if(val == "more"){
                $('#settlement_contents_modal').modal('hide');
                $('#people_iam_modal').modal('show');
            }else if(val == "share"){
                $('#sharepoint_modal').modal('hide');
                if($("#place_before").val() == "webpage"){
                    $('#people_iam_modal').modal('show');
                }else if($("#place_before").val() == "card"){
                    $('#card_send_modal').modal('show');
                }else{
                    $('#contents_send_modal').modal('show');
                }
            }else if(val == "settlment_auto"){
                $("#auto_settlement_modal").modal('hide');
                $("#auto_making_modal").modal('show');
            }else if(val == "auto_list"){
                $("#auto_list_edit_modal").modal('hide');
                $("#auto_list_modal").modal('show');
            }
        }
        //공지사항 전송 팝업
        function send_notice(){
            $("#mypage-modalwindow").modal("hide");
            $("#notice_send_id").val('<?=$send_ids?>');
            $("#notice_send_id_count").val(<?=$send_ids_cnt?> + '건');
            $('#notice_send_id_count').data('num', <?=$send_ids_cnt?>);
            $("#notice_send_modal_mem").modal("show");
        }
        function show_share_user_list(val){
            var win = window.open("/iam/_pop_public_profile_info.php?sendtype="+val, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
        }
        function notice_send_settle(){
            $("#point_pay_type").val(9);
            point_settle();
        }
        function downloadURI()
        {
            var uri = "<?=$cdn_ssl?>"+"/data/onlyone-release.apk";
            var name = "app_link";
            var link = document.createElement("a");
            link.setAttribute('download', name);
            link.href = uri;
            document.body.appendChild(link);
            link.click();
            link.remove();
            alert("다운로드 중입니다.잠시후 폰 파일창 또는 설치파일창에서 확인하세요.");
        }
		function openShop(){
			<?if($is_pay_version){?>
			location.href = "/?cur_win=iam_mall";
			<?}else{?>
			location.href = "/iam/index_shop.php";
			<?}?>
		}
		function gwc_tab(val){
			var mem_id_gwc = '<?=$_SESSION['iam_member_id']?>';
			var type = getCookie('contents_mode');
			sort_key3 = 1;
			if(val == "provider"){
				var str = "&req_provide=Y";
			}
			else{
				var str = "";
			}
			location.href = "/?"+"<?=$request_short_url.$card_owner_code?>&cur_win=we_story&type="+type+"&search_key=" +"<?=$_GET[search_key]?>" + "&key1=4&key2=" + "<?=$_GET[key2]?>"+"&key3=0&sort_key3="+sort_key3+"&key4=1&iamstore=N"+str+"&wide=Y";
		}
		function callya_tab(){
			var type = getCookie('contents_mode');
			sort_key3 = 1;
			location.href = "/?"+"<?=$request_short_url.$card_owner_code?>&cur_win=we_story&type="+type+"&search_key=" +"<?=$_GET[search_key]?>" + "&key1=3&key2=" + "<?=$_GET[key2]?>"+"&key3=0&sort_key3="+sort_key3;
		}
		$("#btn_notice").on("click",function(){
            openNoticeModal();
        });

		function openNoticeModal(){
            $("#sample-modalwindow").modal("hide");
            $.ajax({
                type: "POST",
                url: "/iam/ajax/today_popup_ajax.php",
                data:{type : 'notice_popup'},
                dataType: "json",
                success: function(data) {
					$("#ajax-loading").delay(10).hide(1);
                    $("#notice-modalwindow").children().find(".modal-body").empty();
                    var result = data.result;
                    for(var i = 0; i < result.length; i++){
                        var arr = result[i];
                        var html = "<div style=\"padding-top: 10px;\" onclick=\"window.open('"+arr.link+"')\">"+
                            "<div style=\"background-color: #ffffff;border-radius: 10px;display: flex\">"+
                            "<div style=\"border-radius: 50%;width: 50px;height: 50px;overflow: hidden;margin:auto 10px;\">"+
                            "<img src=\"" + arr.profile + "\" style=\"width: 50px;height:50px;\">"+
                            "</div>"+
                            "<div>"+
                            "<label style=\"font-size:14px;margin-left: 10px;margin-top: 10px\">" + arr.name + "</label><br>"+
                            "<label style=\"font-size:14px;margin-left: 10px;margin-top: 10px;margin-bottom: 10px\">"+arr.count + "</label>"+
                            "</div>"+
                            "</div>"+
                            "</div>";
                        $("#notice-modalwindow").children().find(".modal-body").append(html);
                    }
                    $("#intro-modalwindow").modal("hide");
                    $("#video-modalwindow").modal("hide");
                    $("#news-modalwindow").modal("hide");
                    $("#notice-modalwindow").modal("show");
                }
            });
        }
	</script>