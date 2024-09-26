<?php
$wizsave_mode=1;
@include_once '/home/hosting_users/ilearning1/www/builder/lib/lib.php';
@include_once $ui_homepath.'/pages/menu_id.php';
$need_html5player=0;
$menu_id=443;
$ui_loginpage='/pages/page_37.php';
$connect=dbconn();
@access_right_chk(1, '/pages/page_37.php', '');
$ui_og_arr=@print_board_title('학과소개', '', '', '');
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
<?$oz_respond_cnt=array(6);?><script type="text/javascript">if(document.cookie.search(/ozDesktopMode=1/)==-1) document.write('<meta name="viewport" content="width=device-width, initial-scale=1">');</script>
<meta http-equiv="imagetoolbar" content="no">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/cgi_bank/lib/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/style.css?r=1548830785" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/jquery-ui.css" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/page_443.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1197.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_906.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1196.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1199.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1198.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1006.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_903.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1232.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_992.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_993.css?r=0311170221" type="text/css">
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
<body id="menu_group_51" class="menu_id_443 menu_order_02">
<div id="page_container" class="float_clear"><div id="cell_0_151" class="hidden-xs">
<div id="cell_151_152">
<div id="cell_152_163">
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
<div id="cell_118_162" class="hidden-xs col-sm-6">
<?php
$module_id=1196;
$cell_id='cell_118_162';
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
<li id="hmenu_1199_2_320" depth="1"><a href="/pages/page_443.php" class="current">전공소개</a>
<ul>
<li id="hmenu_1199_2_443" depth="2"><a href="/pages/page_443.php" class="current">학과소개</a></li>
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
<div id="cell_137_154">
<?
include_once '/home/hosting_users/ilearning1/www/pages/auto_data/saved/module_1198.inc';
?>
</div>
</div>
<div id="cell_0_158" class="float_clear row">
<div id="cell_158_120" class="col-xs-12 col-sm-9">
<div id="cell_120_155" class="row">
<div id="cell_155_156" class="hidden-xs">
<h2 id="locwrap_1006"><span class="loctt_1006"><img src="/image_bank/icon/dot/head_symbol_9.gif" class="vmiddle">&nbsp;</span><a href="/pages/page_295.php?" class="lochm_1006">Home</a><span class="locsp_1006">></span><a href="/pages/page_443.php" class="locmn_1006">전공소개</a><span class="locsp_1006">></span><span class="loccr_1006">학과소개</span></h2></div>
<h2 id="locwrap_903"><span class="loccr_903">학과소개</span></h2></div>
<div id="cell_120_157">
<div class="smartOutput htmlEditor float_clear"><h3 style="color: rgb(78, 95, 112); font-size: 24px; font-weight: bold; line-height: 1.6;">인지코칭학과&nbsp;Department of Cognitive Coaching</h3>

<p>&nbsp;</p>

<p>인지코칭학과는 인간핵심역량의 중심인 인지역량을 개발하기 위해 언어설계와 생각설계로 무엇이든 알아내는 언어인지기술과 상황인지기술을 배워 인지역량과 코칭역량을 개발하는 인지코칭 전문가를 양성하는 학과입니다.</p>

<p>&nbsp;</p>

<table border="0" cellpadding="5" cellspacing="0" style="background-color:#ffffff;border-spacing:0px;margin-bottom:5px;margin-top:5px;width:100%;">
	<thead>
		<tr>
			<th scope="col" style="background-color: rgb(238, 242, 249); padding: 5px;">
			<p>상담문의&nbsp; &nbsp; 070-4477-6631</p>
			</th>
			<th scope="col" style="background-color: rgb(238, 242, 249); padding: 5px;">
			<p>EMAIL&nbsp; &nbsp; onlyonemaker@naver.com</p>
			</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<p>&nbsp;</p>

<p><span style="font-family:_오성과한음B;"><span style="color:#2980b9;"><strong>전공소개</strong></span></span></p>

<p>인지코칭학은 인간의 핵심역량인 인지기능을 강화하는 것을 목적으로 한 교육학입니다. 교육학이 인간전체역량을 개발시키기 위해 필요한 철학, 교육과정, 교육내용, 교육방법, 교사양성 교육제도, 교육시설 등을 연구하고 운영하는 방향을 제시한다면 인지코칭학은 인간의 역량 중에서 인지역량을 개발시키기 위해 필요한 철학과 인지역량개발기술과 도구, 코칭기법에 집중하여 연구하여 이를 교육현장에 접목시키는 학문입니다.</p>

<p>&nbsp;</p>

<p>인공지능시대가 다가오면서 가장 큰 변화를 일으키는 영역이 바로 교육계입니다. 시대의 변화는 역량의 변화를 필요로 하는데 그동안&nbsp; 사용해온 역량과 매우 다른 역량이 요구되고 있기 때문이다. 인류가 문장언어의 탄생을 분기점으로 원시와 문명으로 구분되었다면, 인공지능을 분기점으로 정보교육과 창의교육으로 나뉜다고 할 수 있다. 물론 창의교육은 모든 교육에서 중심 주제였지만, 인공지능시대가 필요로 하는 창의교육은 그 형태가 좀 다릅니다.</p>

<p>인공지능시대 이전의 창의교육은 정보교육을 수행한 결과로 얻게 되는 자연스러운 창의교육이라고 한다면, 인공지능시대의 창의교육이란 창의교육을 하기 위해 필요한 정보를 수집하고, 수집된 정보를 이용하여 창의적 결과를 산출하는 것을 말합니다.</p>

<p>어떻게 보면 학교교육과정을 마치고 업무현장에서 일하는 사람들이 수행하는 것과 같은 형태라 볼 수 있습니다. 또한 모든 천재들이 업적을 만드는 과정에서 사용했던 방법이기도 합니다. 즉 문제를 인지하고, 문제해결을 위해 정보를 수집, 분석하는 과정을 거쳐 문제해결의 알고리즘을 세우는 과정을 거쳐 원하는 결과를 달성하는 방법을 말하는 것입니다. 결국 인지코칭학이란 창의교육을 수행하므로 인간의 본질이 되는 인지역량을 개발하게 만드는 학문이라 할 수 있습니다.</p>

<p>&nbsp;</p>

<p>AI시대를 맞이하여 그동안 정보중심형 학습으로 인재를 양성하던 프로세스가 인간의 핵심역량중심형 학습방향으로 전세계가 변화되고 있습니다. 따라서 인지코칭학은 정보를 이해하고 사고하고 표현하는 모든 활동에 있어 언어인지기술로 활용하여 상황과 언어를 일치시키는 의문과 답변의 탐구과정을 기술적인 학습으로 진행할 수 있도록 새로운 교육 패러다임을 적용하고자 합니다. 또한 학습역량이 사회의 업무역량으로 바로 될 수 있는 보다 효과적인 코칭기법으로 국가와 세계를 이끌어갈 미래인재의 리더를 양성하기 위해 설립되었습니다.</p>

<p>&nbsp;</p>

<table align="center" border="0" cellpadding="5" cellspacing="2" style="background-color:#ffffff;border-spacing:2px;width:60%;">
	<tbody>
		<tr>
			<td bgcolor="#0099ff" style="background-color: rgb(0, 153, 255); padding: 5px;">
			<p style="text-align: center;"><span style="color:#ffffff;"><strong>시대적방향</strong></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p><span style="color:#2980b9;"><strong><span style="font-family:바탕,batang;">인공지능시대가 요구하는 인지역량 개발</span></strong></span></p>
			</td>
		</tr>
		<tr>
			<td bgcolor="#0099ff" style="background-color: rgb(0, 153, 255); padding: 5px;">
			<p style="text-align: center;"><span style="color:#ffffff;"><strong>교육적방향</strong></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p><span style="color:#2980b9;"><strong><span style="font-family:바탕,batang;">아는 것에서 알아내는 방법으로 인지역량 개발</span></strong></span></p>
			</td>
		</tr>
		<tr>
			<td bgcolor="#0099ff" style="background-color: rgb(0, 153, 255); padding: 5px;">
			<p style="text-align: center;"><span style="color:#ffffff;"><strong>종합적방향</strong></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p><span style="color:#2980b9;"><strong><span style="font-family:바탕,batang;">인지역량에 기반한 온리원 종합역량 개발</span></strong></span></p>
			</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<table align="center" border="0" cellpadding="5" cellspacing="1" style="background-color:#ffffff;border-spacing:1px;width:60%;">
	<tbody>
		<tr>
			<td colspan="2" style="background-color: rgb(255, 255, 255); padding: 5px;"><img alt="" src="/image_bank/dummy/fskin_174/180829_01.png" /></td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p style="text-align: center;">(a)인간전체역량</p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p style="text-align: center;">(b)인지역량(핵심역량)</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p style="text-align: right;">출처:인간역량의 전체상/</p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>온리원연구소, 송조은교수 2017</p>
			</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p><span style="font-family:_오성과한음B;"><span style="color:#2980b9;">학과특징</span></span></p>

<p>오늘날 우리는 4차산업혁명 시대에 살고 있습니다. 4차산업시대는 산업을 기준으로 정의한 것으로 농업시대, 산업시대, 정보시대 이후를 명칭한 말입니다. 4차산업시대의 핵심은 인공지능(AI)이다. AI의 출현으로 전세계가 정보중심의 학습에서 인간의 핵심역량 중심의 교육으로 전환하고 있습니다. 인지코칭학과는 인간의 전체역량을 주체역량, 인지역량, 인성역량, 정보역량으로 정의하고, 그 중에서 핵심역량인 인지역량을 실용적으로 개발하기 위한 언어인지기술을 바탕으로 인지코칭 전문가를 양성하고 있습니다.</p>

<p>&nbsp;</p>

<p>인지코칭학과는 언어인지기술을 개발자인 주교수와&nbsp; 우수한 교수진을 확보하여 학생 중심의 이론적이고 현실적인 교과과정을 제공합니다. 온라인학습과 오프라인 학습을 통합하여 특강, 연수, 학생 자치 활동에 대한 지원으로 학생들에게 다양한 인맥구축 및 자기계발의 기회를 제공하고 있습니다.</p>

<p>또한, 대학교 및 대학원을 졸업한 후에 관련 분야의 여러 진로를 탐색할 수 있도록 도와주고 있습니다.</p>

<p>&nbsp;</p>

<p><font color="#2980b9" face="_오성과한음B">전공로드맵</font></p>

<table align="center" border="0" cellpadding="5" cellspacing="2" style="background-color:#c9ced5;border-spacing:2px;width:100%;">
	<tbody>
		<tr>
			<td colspan="1" rowspan="2" style="background-color: rgb(0, 153, 255); padding: 5px;">
			<p style="text-align: center;"><span style="color:#ffffff;"><strong>배경학문</strong></span></p>
			</td>
			<td colspan="4" rowspan="1" style="background-color: rgb(0, 153, 255); padding: 5px;">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="1" style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>인지과학, 인지심리학, 언어학, 인지언어학, 인지교육, 인지교육학, 정보분류학, 논리학, 코칭학, 학습코칭학</p>
			</td>
		</tr>
		<tr>
			<td colspan="1" rowspan="2" style="background-color: rgb(0, 153, 255); padding: 5px;">
			<p style="text-align: center;"><span style="color:#ffffff;"><strong>대학원</strong></span></p>

			<p style="text-align: center;"><span style="color:#ffffff;"><strong>전공과정</strong></span></p>
			</td>
			<td style="background-color: rgb(0, 153, 255); padding: 5px;">
			<p style="text-align: center;"><span style="color:#ffffff;"><strong>정보설계기술</strong></span></p>

			<p style="text-align: center;"><span style="color:#ffffff;"><strong>(문일지십기술)</strong></span></p>
			</td>
			<td style="background-color: rgb(0, 153, 255); padding: 5px;">
			<p style="text-align: center;"><span style="color:#ffffff;"><strong>창의설계기술</strong></span></p>

			<p style="text-align: center;"><span style="color:#ffffff;"><strong>(관주위보기술)</strong></span></p>
			</td>
			<td style="background-color: rgb(0, 153, 255); padding: 5px;">
			<p style="text-align: center;"><span style="color:#ffffff;"><strong>저술설계기술</strong></span></p>

			<p style="text-align: center;"><span style="color:#ffffff;"><strong>(일필휘지기술)</strong></span></p>
			</td>
			<td style="background-color: rgb(0, 153, 255); padding: 5px;">
			<p style="text-align: center;"><span style="color:#ffffff;"><strong>상황인지기술</strong></span></p>

			<p style="text-align: center;"><span style="color:#ffffff;"><strong>(알고리즘기술)</strong></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(251, 255, 211); padding: 5px;">
			<p style="text-align: center;">인간역량이해론</p>

			<p style="text-align: center;">도서설계기술론</p>

			<p style="text-align: center;">언어인지기술론</p>

			<p style="text-align: center;">정보설계코칭론</p>
			</td>
			<td style="background-color: rgb(251, 255, 211); padding: 5px;">
			<p style="text-align: center;">생각설계기술론</p>

			<p style="text-align: center;">패턴설계기술론</p>

			<p style="text-align: center;">언어인지기술론</p>

			<p style="text-align: center;">창의설계코칭론</p>
			</td>
			<td style="background-color: rgb(251, 255, 211); padding: 5px;">
			<p style="text-align: center;">의문탐구기술론</p>

			<p style="text-align: center;">문제해결기술론</p>

			<p style="text-align: center;">언어인지기술론</p>

			<p style="text-align: center;">의문소통코칭론</p>

			<p style="text-align: center;">논문저술코칭론</p>
			</td>
			<td style="background-color: rgb(251, 255, 211); padding: 5px;">
			<p style="text-align: center;">의문탐구기술</p>

			<p style="text-align: center;">문제해결기술</p>

			<p style="text-align: center;">언어인지기술</p>

			<p style="text-align: center;">의문탐구코칭론</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(0, 153, 255); padding: 5px;">
			<p style="text-align: center;"><span style="color:#ffffff;"><strong>졸업과정</strong></span></p>
			</td>
			<td colspan="4" rowspan="1" style="background-color: rgb(0, 153, 255); padding: 5px;">
			<p><span style="color:#ffffff;"><strong>논문세미나 또는 프로젝트 세미나</strong></span></p>
			</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p><font color="#2980b9" face="_오성과한음B">전공의 특징</font></p>

<p><em># AI시대의 교육대안</em></p>

<p>AI시대는 정보를 기억하는 기존의 공부보다 AI를 활용한 정보탐색을 통해 재생산적 사고를 필요로 합니다. 본 과정은 인류가 남긴 정보와 AI가 생산한 정보를 저술로 바꾸어 창조적 역량을 개발할 수 있습니다.</p>

<p>&nbsp;</p>

<p><em><span style="color:#2c3e50;"><strong>#세계 최초의 학과</strong></span></em></p>

<p>현재 인지관련학과는 인지과학, 인지심리학, 언어학, 인지언어학, 인지교육 등이 있습니다. 본 과정은 인지과학의 원리에 근거하여 인지언어학과 인지교육학, 정보분류학, 논리학을 도구로 그리고 인간역량에 대한 20여년의 현장연구결과를 통해 개발된 과정입니다. 하지만 인지관계학보다 현장연구가 핵심을 이루고 있습니다. 본 과정은 인간역량의 핵심인 인지기능 중 특히 사고영역을 개발하되 언어의 인지순서라는 개념을 이용하고 있습니다. 결국 상호인지순서를 언어에 도입함으로써 언어와 사고를 동시에 개발할 수 있는 과정이라 할 수 있습니다.</p>

<p>&nbsp;</p>

<p><em><span style="color:#2c3e50;"><strong>#세계 최초의 과정</strong></span></em></p>

<p>언어인지기술의 특허를 활용하여 기본적으로 창의융합형 정보설계가 진행되고, 설계에 의한 글쓰기를 통해 10배의 빠르고 쉬운 글쓰기가 되면서 동시에 알고리즘 사고를 기반으로 저술과 업무역량 증진까지 가능하도록 언어,사고,정보를 통합해 인지역량을 개발하는 최초의 과정입니다.</p>

<p>&nbsp;</p>

<p><em><strong><span style="color:#2c3e50;">#자격과 진로 방향</span></strong></em></p>

<p>인지코칭학과를 이수하면 조건에 따라 인지자격급수 자격증과 인지코치, 요약코치, 독서코치, 저술코치의 자격을 취득할 수 있는 있습니다. 또한 방과후학교 강사, 학생코치, 대학강사, 평생교육원 강사, 요약전문가 등으로 활동할 수 있습니다.</p>
</div>
</div>
</div>
<div id="cell_158_159" class="col-xs-12 col-sm-3  row">
<div id="cell_159_160" class="hidden-xs">
<h2 id="vmenu_992_head">전공소개</h2>
<ul id="vmenu_992" class="drop_gnb_v">
<li id="vmenu_992_443" depth="2" num="0" class="on"><a href="/pages/page_443.php" class="vmenu_depth_1 active">학과소개</a></li>
<li id="vmenu_992_444" depth="2" num="0"><a href="/pages/page_444.php" class="vmenu_depth_1">학과활동</a></li>
<li id="vmenu_992_445" depth="2" num="0"><a href="/pages/page_445.php" class="vmenu_depth_1">선배인터뷰</a></li>
<li id="vmenu_992_446" depth="2" num="0"><a href="/pages/page_446.php" class="vmenu_depth_1">자격증안내</a></li>
<li id="vmenu_992_447" depth="2" num="0"><a href="/pages/page_447.php" class="vmenu_depth_1">강의체험</a></li>
<li id="vmenu_992_466" depth="2" num="0"><a href="/pages/page_466.php" class="vmenu_depth_1">주요연혁</a></li>
</ul>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#vmenu_992 ul').css('display','none');
	$('#vmenu_992 a.active').next('ul').css('display','block');

	
	$('<li class="noLava"><div class="menu_line"></div></li>').insertBefore($('#vmenu_992>li[num]:gt(0)'));

});
</script>
</div>
<div id="cell_159_161">
<div class="smartOutput float_clear"><p class="p2" style="text-align: center;"><img alt="" src="/image_bank/dummy/fskin_174/phone.png" /></p>

<p class="p2" style="text-align: center;"><span style="color:#696969;"><strong><span style="font-size: 20px;">고객상담센터</span></strong></span></p>

<div style="background-color:rgb(221, 221, 221);border-image-outset:initial;border-image-repeat:initial;border-image-slice:initial;border-image-source:none;border-image-width:initial;border-radius:0px;margin-bottom:7px;margin-top:7px;padding:0px 3px;">
<p style="text-align: center;"><span style="font-size: 22px;">070-4477-6631</span></p>
</div>

<p class="p7" style="text-align: center;"><span style="font-size: 12px;">(월~금 10:00-18:00)</span></p>
</div>
</div>
</div>
</div>
<div id="cell_0_126">
<div id="cell_126_135" class="float_clear row">
<div id="cell_135_164" class="col-xs-12 col-sm-6">
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
