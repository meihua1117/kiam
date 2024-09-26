<?php
$wizsave_mode=1;
@include_once '/home/hosting_users/ilearning1/www/builder/lib/lib.php';
@include_once $ui_homepath.'/pages/menu_id.php';
$need_html5player=0;
$menu_id=1151;
$ui_loginpage='/pages/page_37.php';
$connect=dbconn();
@access_right_chk(1, '/pages/page_37.php', '');
$ui_og_arr=@print_board_title('지원사업', '', '', '');
$member_share_info=array('use_share'=>'0','type'=>'site','connect'=>$connect,'ui_pages'=>$ui_pages,'ui_url'=>'');
$global_script_arr=array();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?=$ui_og_arr[0]?></title>
<meta charset="UTF-8">
<?=$ui_og_arr[1]?>
<meta name="generator" content="OZ Homebuilder - ozhome.co.kr">
<?$oz_respond_cnt=array(7);?><script type="text/javascript">if(document.cookie.search(/ozDesktopMode=1/)==-1) document.write('<meta name="viewport" content="width=device-width, initial-scale=1">');</script>
<meta http-equiv="imagetoolbar" content="no">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/cgi_bank/lib/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/style.css?r=1548830785" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/jquery-ui.css" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/page_1151.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5421.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5323.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5435.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5423.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5320.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5339.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5409.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5410.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5408.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5422.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5327.css?r=0311170221" type="text/css">
<link href="//fonts.googleapis.com/earlyaccess/nanumgothic.css" rel="stylesheet" type="text/css"><link href="//fonts.googleapis.com/earlyaccess/nanumbrushscript.css" rel="stylesheet" type="text/css"><link href="//fonts.googleapis.com/earlyaccess/nanumpenscript.css" rel="stylesheet" type="text/css"><link href="//fonts.googleapis.com/css?family=Jacques+Francois+Shadow%7CElsie+Swash+Caps%7CPirata+One" rel="stylesheet" type="text/css"><link href="//cdn.rawgit.com/moonspam/NanumSquare/master/nanumsquare.css" rel="stylesheet" type="text/css"><link href="//fonts.googleapis.com/earlyaccess/nanumbrushscript.css" rel="stylesheet" type="text/css"><link href="//fonts.googleapis.com/earlyaccess/nanumgothiccoding.css" rel="stylesheet" type="text/css"><link href="//fonts.googleapis.com/earlyaccess/nanummyeongjo.css" rel="stylesheet" type="text/css"><link href="//fonts.googleapis.com/earlyaccess/nanumpenscript.css" rel="stylesheet" type="text/css"><link href="//fonts.googleapis.com/earlyaccess/hanna.css" rel="stylesheet" type="text/css"><link href="//fonts.googleapis.com/earlyaccess/jejugothic.css" rel="stylesheet" type="text/css"><link href="//fonts.googleapis.com/earlyaccess/jejuhallasan.css" rel="stylesheet" type="text/css"><link href="//fonts.googleapis.com/earlyaccess/jejumyeongjo.css" rel="stylesheet" type="text/css"><link href="//fonts.googleapis.com/earlyaccess/kopubbatang.css" rel="stylesheet" type="text/css">
<script src="//code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js" integrity="sha256-xNjb53/rY+WmG+4L6tTl9m6PpqknWZvRt0rO1SRnJzw=" crossorigin="anonymous"></script>
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
var wizsave_mode=1;
var user_webfont="'Nanum Gothic':Ly9mb250cy5nb29nbGVhcGlzLmNvbS9lYXJseWFjY2Vzcy9uYW51bWdvdGhpYy5jc3M,'Nanum Brush Script':Ly9mb250cy5nb29nbGVhcGlzLmNvbS9lYXJseWFjY2Vzcy9uYW51bWJydXNoc2NyaXB0LmNzcw,'Nanum Pen Script':Ly9mb250cy5nb29nbGVhcGlzLmNvbS9lYXJseWFjY2Vzcy9uYW51bXBlbnNjcmlwdC5jc3M,'Jacques Francois Shadow':G,'Elsie Swash Caps':G,'Pirata One':G,나눔스퀘어:Ly9jZG4ucmF3Z2l0LmNvbS9tb29uc3BhbS9OYW51bVNxdWFyZS9tYXN0ZXIvbmFudW1zcXVhcmUuY3Nz,나눔붓체:Ly9mb250cy5nb29nbGVhcGlzLmNvbS9lYXJseWFjY2Vzcy9uYW51bWJydXNoc2NyaXB0LmNzcw,나눔고딕코딩:Ly9mb250cy5nb29nbGVhcGlzLmNvbS9lYXJseWFjY2Vzcy9uYW51bWdvdGhpY2NvZGluZy5jc3M,나눔명조:Ly9mb250cy5nb29nbGVhcGlzLmNvbS9lYXJseWFjY2Vzcy9uYW51bW15ZW9uZ2pvLmNzcw,나눔펜체:Ly9mb250cy5nb29nbGVhcGlzLmNvbS9lYXJseWFjY2Vzcy9uYW51bXBlbnNjcmlwdC5jc3M,hanna:Ly9mb250cy5nb29nbGVhcGlzLmNvbS9lYXJseWFjY2Vzcy9oYW5uYS5jc3M,제주고딕:Ly9mb250cy5nb29nbGVhcGlzLmNvbS9lYXJseWFjY2Vzcy9qZWp1Z290aGljLmNzcw,제주한라산:Ly9mb250cy5nb29nbGVhcGlzLmNvbS9lYXJseWFjY2Vzcy9qZWp1aGFsbGFzYW4uY3Nz,제주명조:Ly9mb250cy5nb29nbGVhcGlzLmNvbS9lYXJseWFjY2Vzcy9qZWp1bXllb25nam8uY3Nz,kopubbatang:Ly9mb250cy5nb29nbGVhcGlzLmNvbS9lYXJseWFjY2Vzcy9rb3B1YmJhdGFuZy5jc3M,_다음:42,_포천오성과한음R:43,_나눔스퀘어B:44,_나눔스퀘어EB:45,_나눔스퀘어L:46,_오성과한음B:47,";
</script>

</head>
<body id="menu_group_143" class="menu_id_1151 menu_order_02">
<div id="page_container" class="float_clear"><p class="skiptolink"><a href="#cell_124_131" tabindex="1">본문 바로가기</a></p><div id="cell_0_133">
<div id="cell_133_118" class="float_clear row">
<div id="cell_118_150" class="hidden-xs col-sm-6">
<form name="searchbox1" action="/pages/page_1183.php?" method="GET" style="display:inline;white-space:nowrap;"><span class="searchbox1_input"><input type="text" name="keyword" value="" size="40" maxlength="40" title="검색어" class="searchbox1_input2"><input type="image" class="searchbox1_button" src="/image_bank/icon/ic_data.gif"></span></form></div>
<div id="cell_118_122" class="col-xs-12 col-sm-6">
<?php
$module_id=5323;
$cell_id='cell_118_122';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/menu/hmenu_4.php';
?>
</div>
</div>
</div>
<div id="cell_0_146">
<input id="main-menu-state5435" type="checkbox" />
<nav id="mainnav_5435" class="oz_navbar float_clear" role="navigation">
	<div class="cell_header float_clear">
		<label id="mobile_btn5435" for="main-menu-state5435"><span class="main-menu-btn-icon line1"></span><span class="main-menu-btn-icon line2"></span><span class="main-menu-btn-icon line3"></span></label>
		<div class="cell_logo"><h1 class="banner_h1_5435_1 smartOutput htmlEditor"><a href="/pages/page_1126.php?"><img class="banner_img_5435_1" src="/image_bank/dummy/fskin_177/176_logo_서경련2.png" ></a></h1></div>	</div>
	<div class="toggle_box">	<div class="cell_menus float_clear">
						<div class="cell_gnb"><ul id="hmenu_5435_2" class="smartmenu smartmenu5435_2"><li id="hmenu_5435_2_1147" depth="1"><a href="/pages/page_1148.php">서경련소개</a>
<ul>
<li id="hmenu_5435_2_1148" depth="2"><a href="/pages/page_1148.php">서경련소개</a></li>
<li id="hmenu_5435_2_1149" depth="2"><a href="/pages/page_1149.php">단체연혁</a></li>
<li id="hmenu_5435_2_1184" depth="2"><a href="/pages/page_1184.php">조직구성</a></li>
<li id="hmenu_5435_2_1150" depth="2"><a href="/pages/page_1150.php">찾아오는길</a></li>
</ul>
</li>
<li id="hmenu_5435_2_1151" depth="1"><a href="/pages/page_1151.php" class="current">지원사업</a>
<ul>
<li id="hmenu_5435_2_1185" depth="2"><a href="/pages/page_1185.php">일자리창출사업</a></li>
<li id="hmenu_5435_2_1186" depth="2"><a href="/pages/page_1186.php">셀링플래너사업</a></li>
<li id="hmenu_5435_2_1187" depth="2"><a href="/pages/page_1187.php">홍보솔루션사업</a></li>
<li id="hmenu_5435_2_1188" depth="2"><a href="/pages/page_1188.php">홍보컨설팅사업</a></li>
<li id="hmenu_5435_2_1190" depth="2"><a href="/pages/page_1190.php">지역활성화사업</a></li>
<li id="hmenu_5435_2_1189" depth="2"><a href="/pages/page_1189.php">공동쇼핑몰사업</a></li>
<li id="hmenu_5435_2_1191" depth="2"><a href="/pages/page_1191.php">서경련기부사업</a></li>
<li id="hmenu_5435_2_1192" depth="2"><a href="/pages/page_1192.php">서경련정책사업</a></li>
</ul>
</li>
<li id="hmenu_5435_2_1152" depth="1"><a href="/pages/page_1159.php">정보마당</a>
<ul>
<li id="hmenu_5435_2_1159" depth="2"><a href="/pages/page_1159.php">서경련공지</a></li>
<li id="hmenu_5435_2_1194" depth="2"><a href="/pages/page_1194.php">서경련활동</a></li>
<li id="hmenu_5435_2_1196" depth="2"><a href="/pages/page_1196.php">회원들소식</a></li>
<li id="hmenu_5435_2_1193" depth="2"><a href="/pages/page_1193.php">정책자료실</a></li>
</ul>
</li>
<li id="hmenu_5435_2_1155" depth="1"><a href="/pages/page_1156.php">회원참여</a>
<ul>
<li id="hmenu_5435_2_1156" depth="2"><a href="/pages/page_1156.php">회원가입안내</a></li>
<li id="hmenu_5435_2_1157" depth="2"><a href="/pages/page_1157.php">회원가입현황</a></li>
<li id="hmenu_5435_2_1153" depth="2"><a href="/pages/page_1153.php">회원가입상담</a></li>
<li id="hmenu_5435_2_1197" depth="2"><a href="/pages/page_1197.php">서경련품앗이</a></li>
</ul>
</li>
<li id="hmenu_5435_2_1158" depth="1"><a href="/pages/page_1154.php">커뮤니티</a>
<ul>
<li id="hmenu_5435_2_1154" depth="2"><a href="/pages/page_1154.php">FAQ</a></li>
<li id="hmenu_5435_2_1160" depth="2"><a href="/pages/page_1160.php"> QNA</a></li>
<li id="hmenu_5435_2_1198" depth="2"><a href="/pages/page_1198.php">1:1문의</a></li>
</ul>
</li>
</ul>
<script type="text/javascript">
$(function() {
	$('#hmenu_5435_2').smartmenus({
				mainMenuSubOffsetX: -1,
		mainMenuSubOffsetY: 4,
		subMenusSubOffsetX: 6,
		subMenusSubOffsetY: -6
		}).find('a[href*="#"]').click(function() {
		$('#mobile_btn5435, #mainnav_5435 .side_header .closebtn').trigger('click');
	});

	});
</script>
</div>	</div>
	</div></nav>
<script type="text/javascript">
var navbar_pc_mode=1;
	// SmartMenus mobile menu toggle button
	$(function() {
		var $mainMenuState = $('#main-menu-state5435');
		if($mainMenuState.length) {
			// animate mobile menu
			$mainMenuState.change(function(e) {
				var $menus = $('#mainnav_5435 .cell_menus');
				if (this.checked) {
					$menus.hide().slideDown(250, function() { $menus.css('display', ''); });
				} else {
					$menus.show().slideUp(250, function() { $menus.css('display', ''); });
				}
			});
			// hide mobile menu beforeunload
			$(window).bind('beforeunload unload', function() {
				if ($mainMenuState[0].checked) {
					$mainMenuState[0].click();
				}
			});
		}
	});
$(function() {
		if($('nav.oz_navbar').length>1) {
		$('#mainnav_5435').parents('div:not([id="page_container"])').each(function() {
			if(parseInt($(this).css('z-index')) < -4395) $(this).css('z-index',-4395);
		});
	}
	repos_movable5435();
});

jQuery(window).resize(repos_movable5435);
function repos_movable5435() {
	var width=$(window).width();
	var pos="";
	if(width < 800 && navbar_pc_mode==1) pos="mobile_pos";
	else if(width >= 800 && navbar_pc_mode!=1) pos="pc_pos";
	if(pos!="") {
		$('#mainnav_5435 .movable').each(function() {
			var tarr=$(this).attr(pos).split(':');
			if(tarr[1]=='before') {
				$('#mainnav_5435 .'+tarr[0]).before($(this));
			} else {
				$('#mainnav_5435 .'+tarr[0]).after($(this));
			}
		});
		navbar_pc_mode=(pos=="mobile_pos")?0:1;
	}
}
</script>
</div>
<div id="cell_0_137" class="hidden-xs">
</div>
<div id="cell_0_120" class="float_clear row">
<div id="cell_120_124" class="col-xs-12 col-sm-9">
<div id="cell_124_149">
<h2 id="locwrap_5423"><span class="loctt_5423"><img src="/image_bank/dummy/fskin_177/icon_home.gif" class="vmiddle">&nbsp;</span><a href="/pages/page_1126.php?" class="lochm_5423">Home</a><span class="locsp_5423">></span><span class="loccr_5423">지원사업</span></h2></div>
<div id="cell_124_130">
<h2 id="locwrap_5320"><span class="loccr_5320">지원사업</span></h2></div>
<div id="cell_124_131">
<article><?php
$module_id=5339;
$cell_id='cell_124_131';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/board/board_4.php';
?>
</article></div>
</div>
<div id="cell_120_123" class="col-xs-12 col-sm-3  row">
<div id="cell_123_128" class="hidden-xs">
<h2 id="vmenu_5409_head">지원사업</h2>
<ul id="vmenu_5409" class="drop_gnb_v">
<li id="vmenu_5409_1185" depth="2" num="0"><a href="/pages/page_1185.php" class="vmenu_depth_1">일자리창출사업</a></li>
<li id="vmenu_5409_1186" depth="2" num="0"><a href="/pages/page_1186.php" class="vmenu_depth_1">셀링플래너사업</a></li>
<li id="vmenu_5409_1187" depth="2" num="0"><a href="/pages/page_1187.php" class="vmenu_depth_1">홍보솔루션사업</a></li>
<li id="vmenu_5409_1188" depth="2" num="0"><a href="/pages/page_1188.php" class="vmenu_depth_1">홍보컨설팅사업</a></li>
<li id="vmenu_5409_1190" depth="2" num="0"><a href="/pages/page_1190.php" class="vmenu_depth_1">지역활성화사업</a></li>
<li id="vmenu_5409_1189" depth="2" num="0"><a href="/pages/page_1189.php" class="vmenu_depth_1">공동쇼핑몰사업</a></li>
<li id="vmenu_5409_1191" depth="2" num="0"><a href="/pages/page_1191.php" class="vmenu_depth_1">서경련기부사업</a></li>
<li id="vmenu_5409_1192" depth="2" num="0"><a href="/pages/page_1192.php" class="vmenu_depth_1">서경련정책사업</a></li>
</ul>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#vmenu_5409 ul').css('display','none');
	$('#vmenu_5409 a.active').next('ul').css('display','block');

	
	$('<li class="noLava"><div class="menu_line"></div></li>').insertBefore($('#vmenu_5409>li[num]:gt(0)'));

});
</script>
</div>
<div id="cell_123_129">
<div class="smartOutput float_clear"><p class="p2" style="text-align: center;"><img alt="" src="/image_bank/dummy/fskin_177/phone.png" /></p>

<p class="p2" style="text-align: center;"><span style="color: rgb(5, 119, 191);"><strong><span style="font-size: 20px;">고객상담센터</span></strong></span></p>

<div style="padding: 0px 3px; border-radius: 0px; border-image: none; margin-top: 7px; margin-bottom: 7px; background-color: rgb(15, 126, 197);">
<p style="text-align: center;"><span style="color: rgb(255, 255, 255);"><span style="font-size: 22px;">02-555-5555</span></span></p>
</div>

<p class="p7" style="text-align: center;"><span style="font-size: 12px;">(야간ㆍ휴일 긴급상담 가능)</span></p>
</div>
</div>
</div>
</div>
<div id="cell_0_126">
<div id="cell_126_135" class="float_clear row">
<div id="cell_135_144" class="col-xs-12 col-sm-8">
<?php
$module_id=5408;
$cell_id='cell_135_144';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/menu/hmenu_4.php';
?>
</div>
<div id="cell_135_141" class="hidden-xs col-sm-3">
<div class="smartOutput ozNowrap float_clear"><p><img alt="" src="/image_bank/dummy/fskin_177/social_icon_01.png" style="width: 42px; height: 47px;" /><img alt="" src="/image_bank/dummy/fskin_177/social_icon_02.png" /><img alt="" src="/image_bank/dummy/fskin_177/social_icon_03.png" /></p>
</div>
</div>
<div id="cell_135_140" class="col-xs-12 col-sm-9">
<div class="smartOutput float_clear"><p><span style="color: rgb(128, 128, 128);"><span style="font-size: 12px;">서울 용산구 한강로 65-28. Tel : 02-555-5555.&nbsp; Fax : 02-555-6666.</span></span></p>

<p><span style="color: rgb(128, 128, 128);"><span style="font-size: 12px;">Copyright &copy; 2014 Your Company Name.</span></span></p>
</div>
</div>
</div>
</div>
</div>
<?php @include_once '/home/hosting_users/ilearning1/www/builder/lib/common_footer.php';?>
<?php
if($need_html5player) $global_script_arr[]='/cgi_bank/lib/MediaElementPlayer/mediaelement-and-player.min.js';
$global_script_arr[]='/cgi_bank/lib/jquery.carouFredSel.js';
$global_script_arr[]='/cgi_bank/lib/jquery.touchSwipe.min.js';
$global_script_arr[]='/cgi_bank/lib/jquery.lavalamp.custom.js';
$global_script_arr[]='/cgi_bank/lib/jquery.smartmenus.min.js';
$global_script_arr[]='/cgi_bank/lib/masonry.pkgd.min.js';
foreach(array_unique($global_script_arr) as $tval) if($tval!='') echo '<script src="'.$tval.'" type="text/javascript"></script>'."\n";
?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<?if($need_html5player) {?>
<script type="text/javascript">
jQuery(document).ready(function($) {
$('head').append('<link rel="stylesheet" href="/cgi_bank/lib/MediaElementPlayer/mediaelementplayer.min.css" type="text/css">');
$('video,audio').mediaelementplayer();
});
</script>
<?}?>
</body>
</html>
<?php
@mysql_close($connect);
?>
