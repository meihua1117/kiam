<?php
$wizsave_mode=1;
@include_once '/home/hosting_users/ilearning1/www/builder/lib/lib.php';
@include_once $ui_homepath.'/pages/menu_id.php';
$need_html5player=0;
$menu_id=1107;
$ui_loginpage='/pages/page_37.php';
$connect=dbconn();
@access_right_chk(1, '/pages/page_37.php', '');
$ui_og_arr=@print_board_title('CEO 인사말', '', 'http://3key.co.kr/image_bank/dummy/fskin_12/logo.png', '');
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
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="imagetoolbar" content="no">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/cgi_bank/lib/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/style.css?r=1548830785" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/jquery-ui.css" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/page_1107.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5179.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5234.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5178.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5177.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5229.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5184.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5235.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5183.css?r=0311170221" type="text/css">
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
<body id="menu_group_136" class="menu_id_1107 menu_order_01">
<div id="page_container" class="float_clear"><p class="skiptolink"><a href="#cell_6_8" tabindex="1">본문 바로가기</a></p><div id="cell_0_13">
<div id="cell_13_17">
<div id="cell_17_18">
<?php
$module_id=5179;
$cell_id='cell_17_18';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/menu/hmenu_4.php';
?>
</div>
<div id="cell_17_20">
<div class="smartOutput float_clear"><p><img alt="phone" src="/image_bank/dummy/fskin_12/phone.png" style="width: 22px; height: 20px;" /> <span style="color: rgb(255, 117, 0);">010-555-5555</span></p>
</div>
</div>
</div>
</div>
<div id="cell_0_14" class="float_clear">
<div id="cell_14_15">
<h1 class="banner_h1_5178"><a href="/pages/page_1085.php?"><img class="banner_img_5178" src="/image_bank/dummy/fskin_12/logo.png" ></a></h1></div>
<div id="cell_14_16">
<ul id="hmenu_5177" class="drop_gnb pulldown_menu float_clear" direct=""><li id="hmenu_5177_1106" depth="1" num="0" class="on"><a href="/pages/page_1107.php" class="active">회사소개</a>
<ul>
<li id="hmenu_5177_1107" depth="2" class="on"><a href="/pages/page_1107.php" class="active">CEO 인사말</a></li>
<li id="hmenu_5177_1108" depth="2"><a href="/pages/page_1108.php">회사연혁</a></li>
<li id="hmenu_5177_1109" depth="2"><a href="/pages/page_1109.php">오시는 길</a></li>
</ul>
</li>
<li id="hmenu_5177_1110" depth="1" num="0"><a href="/pages/page_1110.php">제품안내</a>
<ul>
<li id="hmenu_5177_1123" depth="2"><a href="/pages/page_1123.php">미술품재테크</a></li>
<li id="hmenu_5177_1124" depth="2"><a href="/pages/page_1124.php">셀링플래너설명회</a></li>
<li id="hmenu_5177_1125" depth="2"><a href="/pages/page_1125.php">셀링플래너설명회2</a></li>
<li id="hmenu_5177_1199" depth="2"><a href="/pages/page_1199.php">교회성장솔루션</a></li>
<li id="hmenu_5177_1200" depth="2"><a href="/pages/page_1200.php">정책자금설명회</a></li>
<li id="hmenu_5177_1201" depth="2"><a href="/pages/page_1201.php">샐링플랜정책자금설명회</a></li>
<li id="hmenu_5177_3070" depth="2"><a href="/pages/page_3070.php">셀링솔루션설명회</a></li>
<li id="hmenu_5177_3071" depth="2"><a href="/pages/page_3071.php">셀링플래너교육</a></li>
</ul>
</li>
<li id="hmenu_5177_1111" depth="1" num="0"><a href="/pages/page_1112.php">고객센터</a>
<ul>
<li id="hmenu_5177_1112" depth="2"><a href="/pages/page_1112.php">고객의 소리</a></li>
<li id="hmenu_5177_1113" depth="2"><a href="/pages/page_1113.php">자주하는 질문</a></li>
</ul>
</li>
<li id="hmenu_5177_1114" depth="1" num="0"><a href="/pages/page_1115.php">채용정보</a>
<ul>
<li id="hmenu_5177_1115" depth="2"><a href="/pages/page_1115.php">채용안내</a></li>
<li id="hmenu_5177_1116" depth="2"><a href="/pages/page_1116.php">인사/복지제도</a></li>
</ul>
</li>
<li id="hmenu_5177_1117" depth="1" num="0"><a href="/pages/page_1118.php">커뮤니티</a>
<ul>
<li id="hmenu_5177_1118" depth="2"><a href="/pages/page_1118.php">공지사항</a></li>
<li id="hmenu_5177_1119" depth="2"><a href="/pages/page_1119.php">질문과답변</a></li>
</ul>
</li>
</ul>
</div>
</div>
<div id="cell_0_2">
<div id="cell_2_5">
</div>
<div id="cell_2_6">
<div id="cell_6_7">
<div id="cell_7_19">
<h2 id="locwrap_5229"><span class="loccr_5229">CEO 인사말</span></h2></div>
</div>
<div id="cell_6_8">
<article><div class="smartOutput htmlEditor float_clear"><p><span style="font-size: 17px;"><span style="color: rgb(255, 255, 255);">Meet our Team</span></span></p>

<p>&nbsp;</p>

<table border="0" cellpadding="0" cellspacing="10" style="width: 100%;">
	<tbody>
		<tr>
			<td style="text-align: center; vertical-align: top;"><img alt="" class="oz_zoom" src="/image_bank/dummy/fskin_12/member1.jpg" style="width: 100%;" zimg="/image_bank/dummy/fskin_12/member1.jpg" /></td>
			<td style="text-align: center; vertical-align: top;"><img alt="" class="oz_zoom" src="/image_bank/dummy/fskin_12/member2.jpg" style="width: 100%;" zimg="/image_bank/dummy/fskin_12/member2.jpg" /></td>
			<td style="text-align: center; vertical-align: top;"><img alt="" class="oz_zoom" src="/image_bank/dummy/fskin_12/member3.jpg" style="width: 100%;" zimg="/image_bank/dummy/fskin_12/member3.jpg" /></td>
			<td style="text-align: center; vertical-align: top;"><img alt="" class="oz_zoom" src="/image_bank/dummy/fskin_12/member4.jpg" style="width: 100%;" zimg="/image_bank/dummy/fskin_12/member4.jpg" /></td>
		</tr>
		<tr>
			<td style="text-align: center; padding-top: 10px; vertical-align: top;">
			<p class="team-inner-header"><strong><span style="color: rgb(255, 255, 255);">TRACY</span></strong></p>

			<p class="team-inner-subtext"><span style="color: rgb(255, 255, 255);">Designer</span></p>
			</td>
			<td style="text-align: center; padding-top: 10px; vertical-align: top;">
			<p class="team-inner-header"><strong><span style="color: rgb(255, 255, 255);">MARY</span></strong></p>

			<p class="team-inner-subtext"><span style="color: rgb(255, 255, 255);">Developer</span></p>
			</td>
			<td style="text-align: center; padding-top: 10px; vertical-align: top;">
			<p class="team-inner-header"><strong><span style="color: rgb(255, 255, 255);">JULIA</span></strong></p>

			<p class="team-inner-subtext"><span style="color: rgb(255, 255, 255);">Director</span></p>
			</td>
			<td style="text-align: center; padding-top: 10px; vertical-align: top;">
			<p class="team-inner-header"><strong><span style="color: rgb(255, 255, 255);">LINDA</span></strong></p>

			<p class="team-inner-subtext"><span style="color: rgb(255, 255, 255);">Manager</span></p>
			</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>
</div>
</article></div>
</div>
<div id="cell_2_11">
<div id="cell_11_12">
<div class="smartOutput htmlEditor"><p align="center">
<a href="http://www.templatemo.com/page/1" target="_blank"><span style="color:#428bca; font-size:11px;">HTML5 Template</span></a>
<span style="color:#777; font-size:10px;">by</span>
<a href="http://www.templatemo.com/preview/templatemo_395_urbanic" target="_blank"><span style="color:#e67e22; font-size:11px;">Urbanic</span> </a>
<span style="color:#bbb; font-size:11px;"> / Modified by </span>
<a href="http://hosting.ozhome.co.kr" target="_blank"><span style="color:#428bca; font-size:11px;">OZWeb</span> </a>
</p></div>
</div>
<div id="cell_11_21">
<div class="smartOutput float_clear"><p style="text-align: center;"><img alt="" himg="/image_bank/dummy/fskin_12/social-icon-fb-over.png" src="/image_bank/dummy/fskin_12/social-icon-fb.png" style="width: 42px; height: 42px;" />&nbsp; <img alt="" himg="/image_bank/dummy/fskin_12/social-icon-rss-over.png" src="/image_bank/dummy/fskin_12/social-icon-rss.png" style="width: 42px; height: 42px;" />&nbsp; <img alt="" himg="/image_bank/dummy/fskin_12/social-icon-twitter-over.png" src="/image_bank/dummy/fskin_12/social-icon-twitter.png" style="width: 42px; height: 42px;" />&nbsp; <img alt="" himg="/image_bank/dummy/fskin_12/social-icon-linkedin-over.png" src="/image_bank/dummy/fskin_12/social-icon-linkedin.png" style="width: 42px; height: 42px;" />&nbsp; <img alt="" himg="/image_bank/dummy/fskin_12/social-icon-dribbble-over.png" src="/image_bank/dummy/fskin_12/social-icon-dribbble.png" style="width: 42px; height: 42px;" /></p>
</div>
</div>
<div id="cell_11_22">
<div class="smartOutput float_clear"><p style="text-align: center;">Copyright &copy; 2084 <a href="http://www.templatemo.com/templates/templatemo_395_urbanic/#"><u><font color="#0066cc">Your Company Name</font></u></a></p>
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
