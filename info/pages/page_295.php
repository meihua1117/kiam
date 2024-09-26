<?php
$wizsave_mode=1;
@include_once '/home/hosting_users/ilearning1/www/builder/lib/lib.php';
@include_once $ui_homepath.'/pages/menu_id.php';
$need_html5player=0;
$menu_id=295;
$ui_loginpage='/pages/page_37.php';
$connect=dbconn();
@access_right_chk(1, '/pages/page_37.php', '');
$ui_og_arr=@print_board_title('온리원스쿨 인지코칭학과', '', '', '');
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
<?$oz_respond_cnt=array(4);?><script type="text/javascript">if(document.cookie.search(/ozDesktopMode=1/)==-1) document.write('<meta name="viewport" content="width=device-width, initial-scale=1">');</script>
<meta http-equiv="imagetoolbar" content="no">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/cgi_bank/lib/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/style.css?r=1548830785" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/jquery-ui.css" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/page_295.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1197.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_906.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1196.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1199.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1008.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1200.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1212.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1213.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1237.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1005.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_991.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_910.css?r=0311170221" type="text/css">
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
<body id="menu_group_52" class="menu_id_295 menu_order_01">
<div id="page_container" class="float_clear"><div id="cell_0_151" class="hidden-xs">
<div id="cell_151_152">
<div id="cell_152_168">
<div class="smartOutput float_clear"><p style="text-align: center;"><span style="font-size:28px;"><span style="color:#FFFFFF;">070-4477-6631</span></span></p>
</div>
</div>
</div>
</div>
<div id="cell_0_133">
<div id="cell_133_118" class="float_clear row">
<div id="cell_118_122" class="col-xs-12 col-sm-6">
<?php
$module_id=906;
$cell_id='cell_118_122';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/menu/hmenu_4.php';
?>
</div>
<div id="cell_118_167" class="hidden-xs col-sm-6">
<?php
$module_id=1196;
$cell_id='cell_118_167';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/notice/notice_2.php';
?>
</div>
</div>
</div>
<div id="cell_0_153">
<div id="cell_153_146">
<div id="navbar_canvas1199" onclick="close_side_navbar1199()"></div><input id="main-menu-state1199" type="checkbox" />
<nav id="mainnav_1199" class="oz_navbar float_clear" role="navigation">
	<div class="cell_header float_clear">
		<label id="mobile_btn1199" onclick="open_side_navbar1199()"><span class="main-menu-btn-icon line1"></span><span class="main-menu-btn-icon line2"></span><span class="main-menu-btn-icon line3"></span></label>
		<div class="cell_logo"><h1 class="banner_h1_1199_1"><a href="/pages/page_295.php?"><img class="banner_img_1199_1" src="/image_bank/dummy/fskin_174/logo_ddc6.png" ></a></h1></div>	</div>
	<div class="toggle_box">	<div class="cell_menus float_clear">
					<div class="side_header float_clear">
						<img src="/image_bank/dummy/fskin_174/logo.gif" class="mlogo">
						<a href="#" class="closebtn" onclick="close_side_navbar1199();return false;">&times;</a>
			</div>
						<div class="cell_gnb"><ul id="hmenu_1199_2" class="smartmenu1199_2 pulldown_all float_clear" is_full="1" is_layer="1"><li id="hmenu_1199_2_316" depth="1"><a href="/pages/page_441.php">학교소개</a>
<ul>
<li id="hmenu_1199_2_441" depth="2"><a href="/pages/page_441.php">학교소개</a></li>
<li id="hmenu_1199_2_318" depth="2"><a href="/pages/page_318.php">운영학교</a></li>
</ul>
</li>
<li id="hmenu_1199_2_320" depth="1"><a href="/pages/page_443.php">전공소개</a>
<ul>
<li id="hmenu_1199_2_443" depth="2"><a href="/pages/page_443.php">학과소개</a></li>
<li id="hmenu_1199_2_444" depth="2"><a href="/pages/page_444.php">학과활동</a></li>
<li id="hmenu_1199_2_445" depth="2"><a href="/pages/page_445.php">선배인터뷰</a></li>
<li id="hmenu_1199_2_446" depth="2"><a href="/pages/page_446.php">자격증안내</a></li>
<li id="hmenu_1199_2_447" depth="2"><a href="/pages/page_447.php">강의체험</a></li>
<li id="hmenu_1199_2_466" depth="2"><a href="/pages/page_466.php">주요연혁</a></li>
</ul>
</li>
<li id="hmenu_1199_2_321" depth="1"><a href="/pages/page_454.php">대학교</a>
<ul>
<li id="hmenu_1199_2_454" depth="2"><a href="/pages/page_454.php">학부개요</a></li>
<li id="hmenu_1199_2_323" depth="2"><a href="/pages/page_323.php">과정로드맵</a></li>
<li id="hmenu_1199_2_448" depth="2"><a href="/pages/page_448.php">교과목안내</a></li>
<li id="hmenu_1199_2_461" depth="2"><a href="/pages/page_461.php">교수진안내</a></li>
<li id="hmenu_1199_2_462" depth="2"><a href="/pages/page_462.php">입학신청</a></li>
</ul>
</li>
<li id="hmenu_1199_2_324" depth="1"><a href="/pages/page_325.php">대학원</a>
<ul>
<li id="hmenu_1199_2_325" depth="2"><a href="/pages/page_325.php">대학원개요</a></li>
<li id="hmenu_1199_2_326" depth="2"><a href="/pages/page_326.php">과정로드맵</a></li>
<li id="hmenu_1199_2_449" depth="2"><a href="/pages/page_449.php">교과목안내</a></li>
<li id="hmenu_1199_2_450" depth="2"><a href="/pages/page_450.php">교수진안내</a></li>
<li id="hmenu_1199_2_463" depth="2"><a href="/pages/page_463.php">입학신청</a></li>
</ul>
</li>
<li id="hmenu_1199_2_327" depth="1"><a href="/pages/page_328.php">커뮤니티</a>
<ul>
<li id="hmenu_1199_2_328" depth="2"><a href="/pages/page_328.php">국제인지코칭학회</a></li>
<li id="hmenu_1199_2_329" depth="2"><a href="/pages/page_329.php">국제인지코치협회</a></li>
<li id="hmenu_1199_2_451" depth="2"><a href="/pages/page_451.php">인지코칭학 원우회</a></li>
</ul>
</li>
<li id="hmenu_1199_2_459" depth="1"><a href="/pages/page_452.php">소통공간</a>
<ul>
<li id="hmenu_1199_2_452" depth="2"><a href="/pages/page_452.php">공지사항</a></li>
<li id="hmenu_1199_2_464" depth="2"><a href="/pages/page_464.php">FAQ</a></li>
<li id="hmenu_1199_2_465" depth="2"><a href="/pages/page_465.php">QNA</a></li>
</ul>
</li>
</ul>
<script type="text/javascript">
$(function() {
	$('#hmenu_1199_2').smartmenus({
	noMouseOver:true,			mainMenuSubOffsetX: -1,
		mainMenuSubOffsetY: 4,
		subMenusSubOffsetX: 6,
		subMenusSubOffsetY: -6
		}).find('a[href*="#"]').click(function() {
		$('#mobile_btn1199, #mainnav_1199 .side_header .closebtn').trigger('click');
	});

	});
</script>
</div>	</div>
	</div></nav>
<script type="text/javascript">
var navbar_pc_mode=1;
	function open_side_navbar1199() {
		$('#navbar_canvas1199').css({width:'100%',opacity:0.5});
		$('#mainnav_1199 .toggle_box').css('left',0);
			}
	function close_side_navbar1199() {
		$('#mainnav_1199 .toggle_box').css('left','-305px');
		$('#navbar_canvas1199').css({width:'0',opacity:0});
			}
$(function() {
		if($('nav.oz_navbar').length>1) {
		$('#mainnav_1199').parents('div:not([id="page_container"])').each(function() {
			if(parseInt($(this).css('z-index')) < -159) $(this).css('z-index',-159);
		});
	}
	repos_movable1199();
});

jQuery(window).resize(repos_movable1199);
function repos_movable1199() {
	var width=$(window).width();
	var pos="";
	if(width < 800 && navbar_pc_mode==1) pos="mobile_pos";
	else if(width >= 800 && navbar_pc_mode!=1) pos="pc_pos";
	if(pos!="") {
		$('#mainnav_1199 .movable').each(function() {
			var tarr=$(this).attr(pos).split(':');
			if(tarr[1]=='before') {
				$('#mainnav_1199 .'+tarr[0]).before($(this));
			} else {
				$('#mainnav_1199 .'+tarr[0]).after($(this));
			}
		});
		navbar_pc_mode=(pos=="mobile_pos")?0:1;
	}
}
</script>
</div>
</div>
<div id="cell_0_137">
<?
include_once '/home/hosting_users/ilearning1/www/pages/auto_data/saved/module_1008.inc';
?>
</div>
<div id="cell_0_172">
<div id="cell_172_158">
<div id="cell_158_155">
<div id="cell_155_156">
<?
include_once '/home/hosting_users/ilearning1/www/pages/auto_data/saved/module_1200.inc';
?>
</div>
</div>
</div>
<div id="cell_172_196">
<div id="cell_196_212">
<table id="table_212_229" class="wiz_column" cellpadding="0" cellspacing="0"><tr class="tcell">
<td id="td_212_229"><div class="td_wrapper">
<div id="cell_229_232">
<?php
$module_id=1212;
$cell_id='cell_229_232';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/notice/notice_2.php';
?>
</div>
</div></td>
<td id="td_212_230"><div class="td_wrapper">
<div id="cell_230_233">
<?php
$module_id=1213;
$cell_id='cell_230_233';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/notice/notice_2.php';
?>
</div>
</div></td>
<td id="td_212_231"><div class="td_wrapper">
<div id="cell_231_234">
<?php
$module_id=1237;
$cell_id='cell_231_234';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/notice/notice_2.php';
?>
</div>
</div></td>
</tr></table>
</div>
</div>
</div>
<div id="cell_0_126">
<div id="cell_126_135" class="float_clear row">
<div id="cell_135_169" class="col-xs-12 col-sm-6">
<div class="smartOutput ozNowrap float_clear"><p><img alt="" src="/image_bank/dummy/fskin_174/social_icon_01.png" style="width: 42px; height: 47px;" /><img alt="" src="/image_bank/dummy/fskin_174/social_icon_02.png" /><img alt="" src="/image_bank/dummy/fskin_174/social_icon_03.png" /></p>
</div>
</div>
<div id="cell_135_144" class="col-xs-12 col-sm-6">
<?php
$module_id=991;
$cell_id='cell_135_144';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/menu/hmenu_4.php';
?>
</div>
<div id="cell_135_140">
<div class="smartOutput float_clear"><p><span style="color:#aaaaaa;"><span style="font-size: 12px;">서울특별시 구로시 개봉로4 4층. Tel : 070-4477-6630.&nbsp; Fax : 02-3280-1884.</span></span></p>

<p><span style="color:#aaaaaa;"><span style="font-size: 12px;">Copyright &copy; 2017&nbsp;OnlyOne education.</span></span></p>
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
$global_script_arr[]='/cgi_bank/lib/jquery.cycle.min.js';
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
