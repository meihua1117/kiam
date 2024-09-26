<?php
$wizsave_mode=1;
@include_once '/home/hosting_users/ilearning1/www/builder/lib/lib.php';
@include_once $ui_homepath.'/pages/menu_id.php';
$need_html5player=0;
$menu_id=449;
$ui_loginpage='/pages/page_37.php';
$connect=dbconn();
@access_right_chk(1, '/pages/page_37.php', '');
$ui_og_arr=@print_board_title('교과목안내', '', '', '');
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
<link rel="stylesheet" href="/pages/auto_data/saved/page_449.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1197.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_906.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1196.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1199.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1198.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1006.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_903.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1242.css?r=0311170221" type="text/css">
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
<body id="menu_group_51" class="menu_id_449 menu_order_04">
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
<li id="hmenu_1199_2_324" depth="1"><a href="/pages/page_325.php" class="current">대학원</a>
<ul>
<li id="hmenu_1199_2_325" depth="2"><a href="/pages/page_325.php">대학원개요</a></li>
<li id="hmenu_1199_2_326" depth="2"><a href="/pages/page_326.php">과정로드맵</a></li>
<li id="hmenu_1199_2_449" depth="2"><a href="/pages/page_449.php" class="current">교과목안내</a></li>
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
<h2 id="locwrap_1006"><span class="loctt_1006"><img src="/image_bank/icon/dot/head_symbol_9.gif" class="vmiddle">&nbsp;</span><a href="/pages/page_295.php?" class="lochm_1006">Home</a><span class="locsp_1006">></span><a href="/pages/page_325.php" class="locmn_1006">대학원</a><span class="locsp_1006">></span><span class="loccr_1006">교과목안내</span></h2></div>
<h2 id="locwrap_903"><span class="loccr_903">교과목안내</span></h2></div>
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

<p><font color="#2980b9" face="_오성과한음B"><b>과정구성</b></font>(총42학점)</p>

<p>&nbsp;</p>

<table align="center" border="0" cellpadding="5" cellspacing="1" style="background-color:#c9ced5;border-spacing:1px;width:80%;">
	<thead>
		<tr>
			<th scope="col" style="background-color: rgb(238, 242, 249); padding: 5px;">이수구분</th>
			<th scope="col" style="background-color: rgb(238, 242, 249); padding: 5px;">과목명</th>
			<th scope="col" style="background-color: rgb(238, 242, 249); padding: 5px;">과목설명</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="1" rowspan="3" style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>COMMON FOUNDATION COURSES</p>

			<p>&nbsp;</p>

			<p><strong>기본필수</strong></p>

			<p>(9학점/</p>

			<p>3과목)</p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Foundation of Education</p>

			<p><strong>교육의 이해</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>교육의 본질, 현대교육의 방향, 교육의 수단 등에 대해 종합적인 시각을 가질 수 있기 위해서 필요한 정보를 습득한다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Methods of Research</p>

			<p><strong>연구의 방법</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>배우는 방법에서 창의적 서술까지 하나의 단서로 하위 설계까지 만들어낼 수 있는 연구방법을 배워야 한다. 특히 논문작성시 연구방법론은 핵심기술에 속하므로 인지기술을 통해 훈련한다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Computer course for Teachers</p>

			<p><strong>컴퓨터활용법</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>인지력을 개발할 때 필수적으로 컴퓨터를 활용한 스마트러닝과 정보의 맵핑을 활용해야 한다. 이를 위해서는 코치의 컴퓨터 활용력을 개발하고 나아가 수업현장에서 컴퓨터를 활용할수 있도록 지도해야 한다.</p>
			</td>
		</tr>
		<tr>
			<td colspan="1" rowspan="4" style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>MAJOR REGUIRED COURSES</p>

			<p>&nbsp;</p>

			<p><strong>전공필수</strong></p>

			<p>(12학점/</p>

			<p>4과목)</p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Understanding the cognitive Coaching</p>

			<p><strong>인지코칭학이해</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>인지기술에 의해 언어력과 상황판단력을 동시에 개발하므로 인지력을 개발하는 코칭능력을 습득한다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Accelerated cognitive skill</p>

			<p><strong>가속인지기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>순간인지, 다중인지, 자동인지, 패턴인지와 같은 두뇌의 잠재인지를 활용하여 지문을 분당 3천자의 속도로 읽을 수 있다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Core cognitive skill</p>

			<p><strong>핵심인지기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>글, 말, 상황과 같은 정보에서 각 정보간 관계를 토대로 가장 중요한 정보를 찾아내고 이를 언어로 표현하는 과정을 말한다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Cognitive design skill</p>

			<p><strong>인지설계기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>모든 언어는 하나의 개념인 단어부터 시작하여 한 문장으로 진행되는 과정에서 상호간에 엮어지는 원리를 찾아내고 이를 토대로 창의적 설계도 진행할 수 있다.</p>
			</td>
		</tr>
		<tr>
			<td colspan="1" rowspan="3" style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>MAJOR ELECTIVES</p>

			<p>&nbsp;</p>

			<p><strong>전공선택</strong></p>

			<p>(9학점/</p>

			<p>3과목)</p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Cognitive</p>

			<p>speaking skill</p>

			<p><strong>인지발표기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>청산유수형 발표를 위해 우수한 발표자를 완벽하게 모방하도록 대상 분석기술을 활용하고 이를 자신의 분석자료와 비교하여 자신의 스피치력을 개발할 수 있다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Cognitive</p>

			<p>writing skill</p>

			<p><strong>인지저술기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>창작적 저술에 도달할수 있도록 하나의 단서를 가지고 논리적 전개와 창의적 상상을 통합한 인지적 저술을 지원한다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Learning Coaching skill</p>

			<p><strong>학습코칭기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>학습자들이 스스로 자신의 목표를 달성할 수 있도록 동기화, 계획화, 실행화, 평가화의 과정을 직접 모델화할 있도록 지원한다.</p>
			</td>
		</tr>
		<tr>
			<td colspan="1" rowspan="2" style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>MAJOR COGNATES</p>

			<p>&nbsp;</p>

			<p><strong>기본선택</strong></p>

			<p>(6학점/</p>

			<p>2과목)</p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Coaching</p>

			<p>design skill</p>

			<p><strong>코칭설계기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>교수학습에 대한 다양한 이론에 근거하여 인지적 학습코칭모델을 연구 분석한다. 이를 이론과 현장검증을 통해 여러변수에 알맞은 코칭모델을 직접 개발한다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Coaching</p>

			<p>evaluation skill</p>

			<p><strong>코칭평가기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>코칭과정의 평가 프로그램에 관련된 평가의 제 문제를 다룬다. 형성평가와 총괄평가를 포함한 다양한 평가의 유형과 주요 평가모형을 검토함으로써 각종 코칭과정의 평가에 관한 이론적&middot;실제적 문제를 탐구한다.</p>
			</td>
		</tr>
		<tr>
			<td colspan="1" rowspan="2" style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>THESIS WRITING</p>

			<p>&nbsp;</p>

			<p><strong>논문작성</strong></p>

			<p>(6학점/</p>

			<p>2과목)</p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Proposal Defense</p>

			<p><strong>논문제안</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>논문작성을 위한 10여쪽 제안서 작성과 해당 제안서에 대해서 교수의 질문에 대해서 방어할 수 있는 수준의 역량을 길러야 한다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Final</p>

			<p>Defense</p>

			<p><strong>논문제출</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>제안서 방어후 교수가 요구하는 수정사항을 정정한 다음 교수의 논문 제출 지침에 따라 작성하여 제출하고 논문에 대한 방어로 마감한다.</p>
			</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p><font color="#2980b9" face="_오성과한음B"><b>학기구성</b></font>(총42학점)</p>

<p>&nbsp;</p>

<table align="center" border="0" cellpadding="5" cellspacing="1" style="background-color:#c9ced5;border-spacing:1px;width:80%;">
	<thead>
		<tr>
			<th scope="col" style="background-color: rgb(238, 242, 249); padding: 5px;">이수학기</th>
			<th scope="col" style="background-color: rgb(238, 242, 249); padding: 5px;">과목명</th>
			<th scope="col" style="background-color: rgb(238, 242, 249); padding: 5px;">과목설명</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="1" rowspan="3" style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p><strong>1학기</strong></p>

			<p>(9학점)</p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Understanding the cognitive Coaching</p>

			<p><strong>인지코칭학이해</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>인지기술에 의해 언어력과 상황판단력을 동시에 개발하므로 인지력을 개발하는 코칭능력을 습득한다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Core cognitive skill</p>

			<p><strong>핵심인지기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>글, 말, 상황과 같은 정보에서 각 정보간 관계를 토대로 가장 중요한 정보를 찾아내고 이를 언어로 표현하는 과정을 말한다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Computer course for Teachers</p>

			<p><strong>컴퓨터활용법</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>인지력을 개발할 때 필수적으로 컴퓨터를 활용한 스마트러닝과 정보의 맵핑을 활용해야 한다. 이를 위해서는 코치의 컴퓨터 활용력을 개발하고 나아가 수업현장에서 컴퓨터를 활용할수 있도록 지도해야 한다.</p>
			</td>
		</tr>
		<tr>
			<td colspan="1" rowspan="3" style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p><strong>2학기</strong></p>

			<p>(9학점)</p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Learning Coaching skill</p>

			<p><strong>학습코칭기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>학습자들이 스스로 자신의 목표를 달성할 수 있도록 동기화, 계획화, 실행화, 평가화의 과정을 직접 모델화할 있도록 지원한다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Accelerated cognitive skill</p>

			<p><strong>가속인지기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>순간인지, 다중인지, 자동인지, 패턴인지와 같은 두뇌의 잠재인지를 활용하여 지문을 분당 3천자의 속도로 읽을 수 있다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Foundation of Education</p>

			<p><strong>교육의 이해</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>교육의 본질, 현대교육의 방향, 교육의 수단 등에 대해 종합적인 시각을 가질 수 있기 위해서 필요한 정보를 습득한다.</p>
			</td>
		</tr>
		<tr>
			<td colspan="1" rowspan="3" style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p><strong>3학기</strong></p>

			<p>(9학점)</p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Cognitive design skill</p>

			<p><strong>인지설계기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>모든 언어는 하나의 개념인 단어부터 시작하여 한 문장으로 진행되는 과정에서 상호간에 엮어지는 원리를 찾아내고 이를 토대로 창의적 설계도 진행할 수 있다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Cognitive</p>

			<p>speaking skill</p>

			<p><strong>인지발표기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>청산유수형 발표를 위해 우수한 발표자를 완벽하게 모방하도록 대상 분석기술을 활용하고 이를 자신의 분석자료와 비교하여 자신의 스피치력을 개발할 수 있다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Coaching</p>

			<p>design skill</p>

			<p><strong>코칭설계기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>교수학습에 대한 다양한 이론에 근거하여 인지적 학습코칭모델을 연구 분석한다. 이를 이론과 현장검증을 통해 여러변수에 알맞은 코칭모델을 직접 개발한다.</p>
			</td>
		</tr>
		<tr>
			<td colspan="1" rowspan="3" style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p><strong>4학기</strong></p>

			<p>(9학점)</p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Cognitive</p>

			<p>writing skill</p>

			<p><strong>인지저술기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>창작적 저술에 도달할수 있도록 하나의 단서를 가지고 논리적 전개와 창의적 상상을 통합한 인지적 저술을 지원한다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Coaching</p>

			<p>evaluation skill</p>

			<p><strong>코칭평가기술</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>코칭과정의 평가 프로그램에 관련된 평가의 제 문제를 다룬다. 형성평가와 총괄평가를 포함한 다양한 평가의 유형과 주요 평가모형을 검토함으로써 각종 코칭과정의 평가에 관한 이론적&middot;실제적 문제를 탐구한다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Methods of Research</p>

			<p><strong>연구의 방법</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>배우는 방법에서 창의적 서술까지 하나의 단서로 하위 설계까지 만들어낼 수 있는 연구방법을 배워야 한다. 특히 논문작성시 연구방법론은 핵심기술에 속하므로 인지기술을 통해 훈련한다.</p>
			</td>
		</tr>
		<tr>
			<td colspan="1" rowspan="2" style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>THESIS WRITING</p>

			<p>&nbsp;</p>

			<p><strong>논문작성</strong></p>

			<p>(6학점/</p>

			<p>2과목)</p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Proposal Defense</p>

			<p><strong>논문제안</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>논문작성을 위한 10여 쪽 제안서 작성과 해당 제안서에 대해서 교수의 질문에 대해서 방어할 수 있는 수준의 역량을 길러야 한다.</p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>Final</p>

			<p>Defense</p>

			<p><strong>논문제출</strong></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p>제안서 방어후 교수가 요구하는 수정사항을 정정한 다음 교수의 논문 제출 지침에 따라 작성하여 제출하고 논문에 대한 방어로 마감한다.</p>
			</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>
</div>
</div>
</div>
<div id="cell_158_159" class="col-xs-12 col-sm-3  row">
<div id="cell_159_160" class="hidden-xs">
<h2 id="vmenu_992_head">대학원</h2>
<ul id="vmenu_992" class="drop_gnb_v">
<li id="vmenu_992_325" depth="2" num="0"><a href="/pages/page_325.php" class="vmenu_depth_1">대학원개요</a></li>
<li id="vmenu_992_326" depth="2" num="0"><a href="/pages/page_326.php" class="vmenu_depth_1">과정로드맵</a></li>
<li id="vmenu_992_449" depth="2" num="0" class="on"><a href="/pages/page_449.php" class="vmenu_depth_1 active">교과목안내</a></li>
<li id="vmenu_992_450" depth="2" num="0"><a href="/pages/page_450.php" class="vmenu_depth_1">교수진안내</a></li>
<li id="vmenu_992_463" depth="2" num="0"><a href="/pages/page_463.php" class="vmenu_depth_1">입학신청</a></li>
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
