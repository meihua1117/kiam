<?php
$wizsave_mode=1;
@include_once '/home/hosting_users/ilearning1/www/builder/lib/lib.php';
@include_once $ui_homepath.'/pages/menu_id.php';
$need_html5player=0;
$menu_id=58;
$ui_loginpage='/pages/page_37.php';
$connect=dbconn();
@access_right_chk(1, '/pages/page_37.php', '');
$ui_og_arr=@print_board_title('검색', '', 'http://3key.co.kr/image_bank/dummy/fskin_174/logo.gif', '');
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
<?$oz_respond_cnt=array(1);?><script type="text/javascript">if(document.cookie.search(/ozDesktopMode=1/)==-1) document.write('<meta name="viewport" content="width=device-width, initial-scale=1">');</script>
<meta http-equiv="imagetoolbar" content="no">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/cgi_bank/lib/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/style.css?r=1548830785" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/jquery-ui.css" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/page_58.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_163.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_65.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_64.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_63.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_165.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_62.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_162.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_152.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_150.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_164.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_69.css?r=0311170221" type="text/css">
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
<body id="menu_group_12" class="menu_id_58 menu_order_01">
<div id="page_container" class="float_clear"><p class="skiptolink"><a href="#cell_124_131" tabindex="1">본문 바로가기</a></p><div id="cell_0_133">
<div id="cell_133_118" class="float_clear row">
<div id="cell_118_150">
<form name="searchbox1" action="/pages/page_58.php?" method="GET" style="display:inline;white-space:nowrap;"><input type="text" name="keyword" value="" size="40" maxlength="40" title="검색어" class="searchbox1_input"><button type="submit" class="searchbox1_button ozbutton black small"><span class="add-icon-white icon-search"></span>검색</button></form></div>
<div id="cell_118_122" class="col-xs-12 col-sm-6">
<?php
$module_id=65;
$cell_id='cell_118_122';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/menu/hmenu_4.php';
?>
</div>
</div>
</div>
<div id="cell_0_146">
<div id="cell_146_147">
<h1 class="banner_h1_64"><a href="/pages/page_1.php"><img class="banner_img_64" src="/image_bank/dummy/fskin_174/logo.gif" ></a></h1></div>
<div id="cell_146_148">
<ul id="hmenu_63" class="pulldown_all float_clear" is_full="1" is_layer="1"><li id="hmenu_63_22" depth="1" num="0"><a href="/pages/page_147.php">교육소개</a>
<ul>
<li id="hmenu_63_147" depth="2"><a href="/pages/page_147.php">교육소개</a></li>
<li id="hmenu_63_148" depth="2"><a href="/pages/page_148.php">과정소개</a></li>
<li id="hmenu_63_23" depth="2"><a href="/pages/page_23.php">사업소개</a></li>
<li id="hmenu_63_24" depth="2"><a href="/pages/page_24.php">주요연혁</a></li>
<li id="hmenu_63_25" depth="2"><a href="/pages/page_25.php">오시는 길</a></li>
</ul>
</li>
<li id="hmenu_63_26" depth="1" num="0"><a href="/pages/page_149.php">과정소개</a>
<ul>
<li id="hmenu_63_149" depth="2"><a href="/pages/page_149.php">강연과정</a></li>
<li id="hmenu_63_150" depth="2"><a href="/pages/page_150.php">학생과정</a></li>
<li id="hmenu_63_151" depth="2"><a href="/pages/page_151.php">교사연수</a></li>
<li id="hmenu_63_152" depth="2"><a href="/pages/page_152.php">전문가과정</a></li>
<li id="hmenu_63_153" depth="2"><a href="/pages/page_153.php">기업연수</a></li>
</ul>
</li>
<li id="hmenu_63_27" depth="1" num="0"><a href="/pages/page_29.php">모집안내</a>
<ul>
<li id="hmenu_63_29" depth="2"><a href="/pages/page_29.php">모집진행</a></li>
<li id="hmenu_63_154" depth="2"><a href="/pages/page_29.php">모집완료</a></li>
<li id="hmenu_63_169" depth="2"><a href="/pages/page_29.php">랜딩페이지</a></li>
<li id="hmenu_63_165" depth="2"><a href="/pages/page_165.php">신청리스트</a></li>
</ul>
</li>
<li id="hmenu_63_30" depth="1" num="0"><a href="/pages/page_31.php">온리소식</a>
<ul>
<li id="hmenu_63_31" depth="2"><a href="/pages/page_31.php">온리원소식</a></li>
<li id="hmenu_63_32" depth="2"><a href="/pages/page_32.php">온리원배달</a></li>
<li id="hmenu_63_155" depth="2"><a href="/pages/page_155.php">보도자료</a></li>
<li id="hmenu_63_156" depth="2"><a href="/pages/page_156.php">무료영상</a></li>
</ul>
</li>
<li id="hmenu_63_33" depth="1" num="0"><a href="/pages/page_34.php">온리원성과</a>
<ul>
<li id="hmenu_63_34" depth="2"><a href="/pages/page_34.php">후기성과</a></li>
<li id="hmenu_63_35" depth="2"><a href="/pages/page_35.php">교육성과</a></li>
<li id="hmenu_63_157" depth="2"><a href="/pages/page_157.php">대회성과</a></li>
<li id="hmenu_63_158" depth="2"><a href="/pages/page_158.php">연구성과</a></li>
</ul>
</li>
</ul>
<script type="text/javascript">
jQuery(document).ready(function($) {
		$('<li class="noLava"><div class="menu_line"></div></li>').insertBefore($('#hmenu_63>li[num]:gt(0)'));
		});
</script>
</div>
</div>
<div id="cell_0_137">
</div>
<div id="cell_0_120" class="float_clear">
<div id="cell_120_124">
<div id="cell_124_149">
<h2 id="locwrap_165"><span class="loctt_165"><img src="/image_bank/icon/dot/head_symbol_9.gif" class="vmiddle">&nbsp;</span><a href="/pages/page_1.php?" class="lochm_165">Home</a><span class="locsp_165">></span><span class="loccr_165">검색</span></h2></div>
<div id="cell_124_130">
<h2 id="locwrap_62"><span class="loccr_62">검색</span></h2></div>
<div id="cell_124_131">
<article><?php
$module_id=162;
$cell_id='cell_124_131';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/search/search_1.php';
?>
</article></div>
</div>
<div id="cell_120_123">
<div id="cell_123_129">
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
<div id="cell_126_135" class="float_clear">
<div id="cell_135_144">
<?php
$module_id=150;
$cell_id='cell_135_144';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/menu/hmenu_4.php';
?>
</div>
<div id="cell_135_141">
<div class="smartOutput ozNowrap float_clear"><p><img alt="" src="/image_bank/dummy/fskin_174/social_icon_01.png" style="width: 42px; height: 47px;" /><img alt="" src="/image_bank/dummy/fskin_174/social_icon_02.png" /><img alt="" src="/image_bank/dummy/fskin_174/social_icon_03.png" /></p>
</div>
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
