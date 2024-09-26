<?php
$wizsave_mode=1;
@include_once '/home/hosting_users/ilearning1/www/builder/lib/lib.php';
@include_once $ui_homepath.'/pages/menu_id.php';
$need_html5player=0;
$menu_id=1123;
$ui_loginpage='/pages/page_37.php';
$connect=dbconn();
@access_right_chk(1, '/pages/page_37.php', '');
$ui_og_arr=@print_board_title('병원장님을 위한 미술품 재테크', '병원장님을 위한 미술품 재테크', '', 'I');
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
<meta http-equiv="imagetoolbar" content="no">
<meta name="format-detection" content="telephone=no">
<meta name="keywords" content="#병원장님, #미술품, #재테크">
<link rel="stylesheet" href="/cgi_bank/lib/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/style.css?r=1548830785" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/jquery-ui.css" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/page_1123.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5237.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5238.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5239.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5240.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5241.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5243.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5244.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5245.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5246.css?r=0311170221" type="text/css">
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
<meta property="og:image" content="http://3key.co.kr/image_bank/landing/190703_11.png">
</head>
<body id="menu_group_136" class="menu_id_1123 menu_order_02">
<div id="page_container" class="float_clear"><div id="cell_0_1">
<div id="cell_1_13">
<div id="cell_13_14">
<div id="cell_14_15">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190703_01.png" style="width: 80%;" /></p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_1_17">
<div id="cell_17_22">
<div id="cell_22_30">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img alt="작가들도 모르는 비밀 책쓰기의 기초 이해와 생각설계기술을 활용하여  정교하고 창의적인 책쓰기 방법을 배우는과정입니다.  " src="/image_bank/landing/190703_02.png" style="width: 80%;" /></p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_1_18">
<div id="cell_18_23">
<div id="cell_23_31">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="자료수집부터 기획, 저술, 그리고 출간까지 모든 과정에는 정보를 융합설계하는 알고리즘 사고가 필요합니다. 세계 최초 특허 출원된 &lt;언어인지기술&gt;을 활용한 알고리즘사고로 정보를 융합설계하는 기술을 배우는 과정입니다." src="/image_bank/landing/190703_03.png" /><!-- <table align="center" border="0" cellpadding="5" cellspacing="10" style="background-color:#009933;border-radius:5px;border-spacing:10px;width:40%;">
	<tbody>
		<tr>
			<td style="background-color: #009933; padding: 5px;">
			<p style="text-align: center;"><span style="font-size:22px;"><strong><span style="color:#ffffff;"><span style="font-family:_포천오성과한음R;">정보융합설계기술로 책쓰기 과정 소개</span></span></strong></span></p>
			</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p style="text-align: center;"><strong><span style="font-family:나눔스퀘어;"><span style="font-size:13px;">책쓰기의 기초 이해와 정보융합설계기술을 활용하여<br />
정교하고 창의적인 책쓰기 방법을 배우는 과정입니다.</span></span></strong></p>

<p style="text-align: center;">&nbsp;</p>--></p>
</div>
</div>
</div>
</div>
<div id="cell_1_28">
<div id="cell_28_38">
<div id="cell_38_39">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img alt="" src="/image_bank/landing/190703_04.png" style="width: 80%;" /></p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_1_27">
<div id="cell_27_40">
<div id="cell_40_41">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img alt="" src="/image_bank/landing/190703_05.png" style="width: 80%;" /></p>

<p class="figwrap" style="text-align: center;">&nbsp;</p>

<p class="figwrap" style="text-align: center;"><img alt="" src="/image_bank/landing/190703_06.png" style="width: 60%;" /></p>

<p>&nbsp;</p>
</div>
</div>
</div>
<div id="cell_27_46">
<div id="cell_46_50">
<div id="cell_50_54">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;">&nbsp;</p>

<p class="figwrap" style="text-align: center;"><img alt="" src="/image_bank/landing/190703_07.png" style="width: 80%;" /></p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_27_47">
<div id="cell_47_51">
<div id="cell_51_62">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><span style="font-family:_나눔스퀘어EB;"><span style="font-size:48px;"><span style="color:#ffffff;">8월 특별혜택<em>!</em></span></span></span></p>

<p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190805_04.png" style="width: 80%;" /></p>

<p class="figwrap" style="text-align: center;">&nbsp;</p>

<p class="figwrap" style="text-align: center;"><img alt="" src="/image_bank/landing/190703_10.png" style="width: 80%;" /></p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_27_48">
<div id="cell_48_64">
<div id="cell_64_68">
<div id="cell_68_84">
<div class="smartOutput htmlEditor float_clear"><div style="background-attachment:initial;background-clip:initial;background-origin:initial;background-size:initial;border-image:initial;border-radius:0px;margin:5px;padding:10px;">
<p style="text-align: center;"><span style="font-size:28px;"><strong><span style="font-family:_나눔스퀘어EB;"><span style="letter-spacing:3px;"><a class="ozbutton black big square" href="http://www.onepagebook.net/opb/popup/speech13.html?pcode=sj&amp;sp=seojeongac">미술품 절세 상담&nbsp;신청하기</a></span></span></strong></span><span style="line-height:0;"></span></p>
</div>
</div>
</div>
</div>
</div>
<div id="cell_48_77">
<div id="cell_77_78">
<div class="smartOutput htmlEditor float_clear"><div style="background-attachment:initial;background-clip:initial;background-color:#ffffff;background-image:url(&quot;/image/180209_17.png&quot;);background-origin:initial;background-position:center top;background-size:initial;border-image:initial;border-radius:15px;margin:5px;padding:10px;">
<p style="text-align: center;"><span style="font-size:16px;"><strong><span style="font-family:_나눔스퀘어EB;"><span style="letter-spacing:3px;"><a class="ozbutton orange big rounded" href="http://www.onepagebook.net/opb/popup/speech13.html?pcode=sj&amp;sp=seojeongac">상담 신청</a></span></span></strong></span><span style="line-height:0;"></span></p>
</div>
</div>
</div>
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
