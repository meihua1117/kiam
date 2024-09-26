<?php
$wizsave_mode=1;
@include_once '/home/hosting_users/ilearning1/www/builder/lib/lib.php';
@include_once $ui_homepath.'/pages/menu_id.php';
$need_html5player=0;
$menu_id=467;
$ui_loginpage='/pages/page_37.php';
$connect=dbconn();
@access_right_chk(1, '/pages/page_37.php', '');
$ui_og_arr=@print_board_title('생각설계 문제해결과정', '핵심역량,문제해결능력, 특허출원 언어인지기술, 알고리즘 사고, 인간역량', '', 'I');
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
<?$oz_respond_cnt=array(3);?><script type="text/javascript">if(document.cookie.search(/ozDesktopMode=1/)==-1) document.write('<meta name="viewport" content="width=device-width, initial-scale=1">');</script>
<meta http-equiv="imagetoolbar" content="no">
<meta name="format-detection" content="telephone=no">
<meta name="keywords" content="#언어인지기술, #송조은,#언어코딩학습,#문제해결,#생각설계,#역량개발,#문제해결능력,#인지코칭학">
<link rel="stylesheet" href="/cgi_bank/lib/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/style.css?r=1548830785" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/jquery-ui.css" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/page_467.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1248.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1249.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1250.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1251.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1252.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1253.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1265.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1262.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1263.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1264.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1254.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1270.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1266.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1267.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1256.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1260.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_405.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_408.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_410.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_409.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1261.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1258.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1257.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1259.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1268.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_418.css?r=0311170221" type="text/css">
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
<meta property="og:image" content="http://3key.co.kr/image/180906_08.png">
</head>
<body id="menu_group_1" class="menu_id_467 menu_order_03">
<div id="page_container" class="float_clear"><div id="cell_0_1">
<div id="cell_1_4">
<div id="cell_4_5" class="float_clear">
<div id="cell_5_7">
<div id="cell_7_9">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="대학원 정식 교과과정으로 채택  |  국제인지코치협회 인증 |  대한민국 출판문화예술 독서진흥 대상수상" src="/image/180911_01.png" /><!--<p style="line-height:250%;"><span style="font-family:나눔스퀘어;"><span style="font-size:20px;"><span style="color:#ffff00;"><strong>&nbsp; </strong></span><span style="color:#ffff00;"><strong>대학원 정식 교과정으로 채택</strong></span><span style="color:#ffffff;"><strong> | </strong></span><span style="color:#ffffff;"><strong>대한민국 출판문화예술</strong></span><span style="color:#ffff00;"><strong> 독서진흥 대상수상 </strong></span><span style="color:#ffffff;"><strong>|</strong></span><span style="color:#ffff00;"><strong> 국제인</strong><span style="color:#ffff00;"><strong><img alt="" src="/image/tropi02.png" style="float: left; width: 43px; height: 40px;" /></strong></span><strong>지코치협회 인증</strong></span></span></span></p>--></p>
</div>
</div>
</div>
</div>
</div>
<div id="cell_1_10">
<div id="cell_10_11">
<div id="cell_11_12">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="" src="/image/180906_02.png" /><!--<table align="center" border="0" cellpadding="5" cellspacing="1" style="background-color:#ffffff;border-spacing:1px;width:50%;">
	<tbody>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;width:25%;">
			<p style="text-align: right;"><span style="font-size:16px;"><span style="font-family:_나눔스퀘어B;">특별혜택</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;border-color:#009900;border-radius:2px;border-spacing:1px;border-style:solid;border-width:2px;width:30%;">
			<p style="text-align: center;"><span style="font-size:16px;"><span style="font-family:_나눔스퀘어B;">특강 참여 시</span></span></p>

			<p style="text-align: center;"><span style="font-size:16px;"><span style="font-family:_나눔스퀘어B;"></span></span><span style="font-size:16px;"><span style="font-family:_나눔스퀘어B;">선착순 20명</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;width:25%;">
			<p><span style="font-size:16px;"><span style="font-family:_나눔스퀘어B;">20% 할인</span></span></p>
			</td>
		</tr>
	</tbody>
</table>--></p>
</div>
</div>
</div>
</div>
<div id="cell_1_13">
<div id="cell_13_14">
<div id="cell_14_15">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="" src="/image/180906_08.png" /></p>
</div>
</div>
</div>
</div>
<div id="cell_1_17">
<div id="cell_17_22">
<div id="cell_22_30">
<div class="smartOutput htmlEditor float_clear"><p><span style="font-size:13px;"></span></p>

<table align="center" border="0" cellpadding="5" cellspacing="1" style="border-spacing:1px;">
	<tbody>
		<tr>
			<td style="text-align: center; padding: 5px;"><img alt="" src="/image/180906_09.png" /></td>
			<td style="text-align: center; padding: 5px;">&nbsp;</td>
			<td style="text-align: center; padding: 5px;"><img alt="" src="/image/180906_10.png" /></td>
			<td style="text-align: center; padding: 5px;">&nbsp;</td>
			<td style="text-align: center; padding: 5px;"><img alt="" src="/image/180906_11.png" /></td>
		</tr>
		<tr>
			<td style="text-align: center; padding: 5px;">
			<p><span style="font-size:20px;"><span style="font-family:_나눔스퀘어EB;"></span></span></p>
			<span style="font-size:20px;"><span style="color: inherit; white-space: inherit; background-color: transparent; font-family: _나눔스퀘어EB;"><strong><img alt="" src="/image/180906_12.png" style="height: 15px; width: 15px; margin-bottom: 5px;" />&nbsp;보고제안역량&nbsp;</strong></span><span style="color: inherit; white-space: inherit; background-color: transparent; font-family: _나눔스퀘어EB;"><strong>향상 솔루션</strong></span></span></td>
			<td colspan="1" rowspan="2" style="text-align: center; padding-left: 0px; width: 1px; background-image: url(&quot;/image/180907_line.png&quot;); background-position: center top; background-repeat: repeat-y;"><span style="font-size:20px;"></span></td>
			<td style="text-align: center; padding: 5px;">
			<p><span style="font-size:20px;"><span style="font-family:_나눔스퀘어EB;"></span></span></p>
			<span style="font-size:20px;"><span style="color: inherit; white-space: inherit; background-color: transparent; font-family: _나눔스퀘어EB;"><strong><img alt="" src="/image/180906_13.png" style="height: 15px; width: 15px; margin-bottom: 5px;" />&nbsp;창의기획역량&nbsp;</strong></span><span style="color: inherit; white-space: inherit; background-color: transparent; font-family: _나눔스퀘어EB;"><strong>향상 솔루션</strong></span></span></td>
			<td colspan="1" rowspan="2" style="text-align: center; padding-left: 0px; width: 1px; background-image: url(&quot;/image/180907_line.png&quot;); background-position: center center; background-repeat: repeat-y;"><span style="font-size:20px;"></span></td>
			<td style="text-align: center; padding: 5px;">
			<p><span style="font-size:20px;"><span style="font-family:_나눔스퀘어EB;"></span></span></p>
			<span style="font-size:20px;"><span style="color: inherit; white-space: inherit; background-color: transparent; font-family: _나눔스퀘어EB;"><strong><img alt="" src="/image/180906_14.png" style="height: 15px; width: 15px; margin-bottom: 5px;" />&nbsp;소통추진역량&nbsp;</strong></span><span style="color: inherit; white-space: inherit; background-color: transparent; font-family: _나눔스퀘어EB;"><strong>향상 솔루션</strong></span></span></td>
		</tr>
		<tr>
			<td style="text-align: center; padding: 5px;">
			<p style="text-align: left;">보고서 작성, 제안서 작성 방법의 원천은&nbsp;생각설계로 정보와 생각을 설계도로 만들어&nbsp;최적의 보고 및 제안을 하는 것입니다. 본 과정에서는 보고제안역량의 원리-요소-기술을 배울 수 있습니다.</p>

			<p><span style="font-size:11px;"></span></p>
			</td>
			<td style="text-align: center; padding: 5px;">
			<p style="text-align: left;"><span style="font-size:13px;">기획의 출발은 의문탐구, 기획의 과정은 정보와 생각의 설계도를 만들어 대안의 솔루션을 만들어내는 일입니다. 본 과정에서는 창의기획역량의 원리-요소-기술을 배울 수 있습니다.</span></p>
			</td>
			<td style="text-align: center; padding: 5px;">
			<p style="text-align: left;"><span style="font-size:13px;">자신이 만든 보고서, 제안서, 기획서 등을 실행으로 옮겨 최종의 결과로 만들기 위해서는 조직에서 소통하는 역량과 일을 추진하는 역량이 중요합니다. 본 과정에서는 소통추진역량의 원리-요소-기술을 배울 수 있습니다. &nbsp;</span></p>
			</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p style="text-align: center;"><!-- <p style="line-height: 200%; text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:17px;">&lt;책쓰기&gt; &lsquo;노하우&rsquo;를 넘어 &lsquo;책쓰기 기술&rsquo;이 있다!</span></span></p>

<p style="line-height: 200%; text-align: center;"><span style="font-family:_나눔스퀘어EB;"><span style="font-size:15px;"><span style="color:#d35400;"><strong>독서교육 25년! 5000회 이상 신화!</strong></span></span><br />
&ldquo;90% 나만 알고 싶은 강의&rdquo;<br />
&ldquo;80% 인문학 공식에 충격&rdquo;</span></p>--></p>
</div>
</div>
</div>
</div>
<div id="cell_1_85">
<div id="cell_85_86">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><span style="font-size:24px;"><span style="color:#cc0000;"><span style="font-family:_오성과한음B;">&lsquo;의문-질문-탐구-답변&rsquo;</span><span style="font-family:_오성과한음B;">의</span></span><span style="font-family:_오성과한음B;"><span style="color:#cc0000;"> </span><span style="color:#16a085;">간단 프로세스</span></span><span style="font-family:_오성과한음B;">로</span></span></p>

<p style="text-align: center;"><span style="font-size:24px;"><span style="font-family:_오성과한음B;">내 상황에 맞는 창의적 목표달성 역량을 개발하라!</span></span></p>

<p style="text-align: center;">&nbsp;</p>

<p style="text-align: center;"><span style="font-size:18px;">세계 최초 특허 출원된 언어인지기술을 활용하여</span></p>

<p style="text-align: center;"><span style="font-size:18px;">어떤 상황이든 알고리즘 사고로 정보를 설계하고</span></p>

<p style="text-align: center;"><span style="font-size:18px;">대안을 찾아 목표를 달성하는 원리와 기술울 배운다</span></p>

<p style="text-align: center;">&nbsp;</p>

<p style="text-align: center;"><span style="font-size:24px;"><span style="font-family:_오성과한음B;">복잡한 포맷기법이 아닌 단순한<span style="color:#16a085;">&lt;원리와 기술&gt;</span>로</span></span></p>

<p style="text-align: center;"><span style="font-size:24px;"><span style="font-family:_오성과한음B;">AI시대에 맞는 문제해결역량을 체화할 수 있다.</span></span></p>

<p style="text-align: center;"><!-- <p style="line-height: 200%; text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:17px;">&lt;책쓰기&gt; &lsquo;노하우&rsquo;를 넘어 &lsquo;책쓰기 기술&rsquo;이 있다!</span></span></p>

<p style="line-height: 200%; text-align: center;"><span style="font-family:_나눔스퀘어EB;"><span style="font-size:15px;"><span style="color:#d35400;"><strong>독서교육 25년! 5000회 이상 신화!</strong></span></span><br />
&ldquo;90% 나만 알고 싶은 강의&rdquo;<br />
&ldquo;80% 인문학 공식에 충격&rdquo;</span></p>--></p>
</div>
</div>
</div>
<div id="cell_1_18">
<div id="cell_18_23">
<div id="cell_23_31">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="" src="/image/180906_17_02.png" /></p>

<p style="text-align: center;">&nbsp;</p>

<p style="text-align: center;"><span style="font-size:16px;">AI시대를 대비한 언어인지기술로 </span></p>

<p style="text-align: center;"><span style="font-size:16px;">상황의 핵심파악 -&gt;문제파악 -&gt;대안설정 -&gt; 목표를 달성하는 기술</span><span style="font-size:16px;"></span><span style="font-size:16px;"></span></p>

<p style="text-align: center;">&nbsp;</p>

<p style="text-align: center;"><img alt="" src="/image/180906_18.png" /></p>

<p style="text-align: center;">&nbsp;</p>

<p style="text-align: center;"><span style="font-size:18px;">인간역량체계도 기반으로 언어와 상황의 인지를 일치하여</span></p>

<p style="text-align: center;"><span style="font-size:18px;"><strong><u>학습지능=</u><u>업무지능이 되게 하는 </u><u>&lsquo;</u><u>생각설계 문제해결</u><u>&rsquo; 과정</u></strong></span></p>

<p style="text-align: center;">&nbsp;</p>

<p style="text-align: center;"><!-- <table align="center" border="0" cellpadding="5" cellspacing="10" style="background-color:#009933;border-radius:5px;border-spacing:10px;width:40%;">
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
<div id="cell_1_87">
<div id="cell_87_88" class="float_clear">
<div id="cell_88_92">
</div>
<div id="cell_88_93">
<div class="smartOutput htmlEditor float_clear"><p><span style="font-size:28px;"><span style="font-family:_오성과한음B;">AI시대, 왜 <span style="color:#cc0099;">인간역량</span>이 부각되는가?</span></span></p>

<p>&nbsp;</p>

<p><span style="font-size:20px;">인간지능을 모방한 인공지능의 등장으로 많은 일자리가 대체되고 있습니다. 정보화 사회에서 하던 방식의 문제해결 방식으로는 이제 살아남기 어렵습니다.</span></p>

<p><span style="font-size:20px;"></span></p>

<p><span style="font-size:20px;">그동안은 학습지능 개발을 위한 교육이 중심이었다면, 이제는</span></p>

<p><span style="font-size:20px;"><span style="color:#cc0099;">학습지능과 업무지능을 일치시키는 인간역량 중심의 교육</span>이 필요합니다.</span></p>

<p><span style="font-size:20px;">무엇보다 의문탐구와 질문답변을 통해 정보를 처리하고</span></p>

<p><span style="font-size:20px;">스스로 문제와 대안을 알아내는 인간역량 자체를 개발해야 합니다.</span></p>

<p><span style="font-size:20px;"></span></p>

<p><span style="font-size:20px;">본 과정은 온리원연구소 송조은교수가 28년간 연구한</span></p>

<p><span style="font-size:20px;"><span style="color:#cc0099;">&lt;인간역량체계도&gt;</span>를 기반으로 하여 <span style="color:#cc0099;">&lt;의문-질문-탐구-답변&gt;</span>의</span></p>

<p><span style="font-size:20px;">간단한 원리와 기술로 창의적인 문제해결과 목표달성하는 방법을 배울 수 있습니다.</span></p>
</div>
</div>
<div id="cell_88_94" class="row">
<div id="cell_94_96" class="col-xs-12 col-sm-4">
<div id="cell_96_100">
<div id="cell_100_104">
<div class="smartOutput float_clear"><p style="text-align: center;"><span style="color:#f39c12;"><span style="font-size:22px;"><strong><span style="background-color:#777777;">다보스포럼</span></strong></span></span></p>

<p style="text-align: center;"><span style="color:#ffffff;"><span style="font-size:22px;"><span style="background-color:#777777;">&ldquo;2020년까지 500만개</span></span></span></p>

<p style="text-align: center;"><span style="color:#ffffff;"><span style="font-size:22px;"><span style="background-color:#777777;">일자리 사라질 것으로 예측&rdquo;</span></span></span></p>
</div>
</div>
</div>
</div>
<div id="cell_94_97" class="col-xs-12 col-sm-4">
<div id="cell_97_101">
<div id="cell_101_106">
<div class="smartOutput float_clear"><p style="text-align: center;"><span style="color:#f39c12;"><span style="font-size:22px;"><strong><span style="background-color:#777777;">옥스퍼드대학교</span></strong></span></span></p>

<p style="text-align: center;"><span style="color:#ffffff;"><span style="font-size:22px;"><span style="background-color:#777777;">&ldquo;2033년 현재 일자리</span></span></span></p>

<p style="text-align: center;"><span style="color:#ffffff;"><span style="font-size:22px;"><span style="background-color:#777777;">&nbsp;47% 사라질 전망&rdquo;</span></span></span></p>
</div>
</div>
</div>
</div>
<div id="cell_94_99" class="col-xs-12 col-sm-4">
<div id="cell_99_103">
<div id="cell_103_108">
<div class="smartOutput float_clear"><p style="text-align: center;"><span style="color:#f39c12;"><span style="font-size:22px;"><strong><span style="background-color:#777777;">앨런 머스크</span></strong></span></span></p>

<p style="text-align: center;"><span style="color:#ffffff;"><span style="font-size:22px;"><span style="background-color:#777777;">&ldquo;미래 사회에선</span></span></span><span style="color:#ffffff;"><span style="font-size:22px;"><span style="background-color:#777777;">&nbsp;20%만 </span></span></span></p>

<p style="text-align: center;"><span style="color:#ffffff;"><span style="font-size:22px;"><span style="background-color:#777777;">의미 있는 직업을 갖게 될 것&rdquo;</span></span></span></p>
</div>
</div>
</div>
</div>
</div>
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;">&nbsp;</p>

<p style="text-align: center;"><span style="font-family: _오성과한음B; font-size: 20px; color: inherit; white-space: inherit;">문제해결의 KEY는 </span><span style="font-family: _오성과한음B; font-size: 20px; white-space: inherit; color: rgb(204, 0, 153);">&ldquo;알아내는 역량&rdquo;</span></p>

<p style="text-align: center;"><span style="font-family: _오성과한음B; font-size: 20px; white-space: inherit; color: rgb(204, 0, 153);"></span><span style="font-size:20px;"><span style="font-family:_오성과한음B;">미래핵심경쟁력은 <span style="color:#cc0099;">&ldquo;문제해결 능력&rdquo;</span></span></span></p>

<p style="text-align: center;"><span style="font-size:20px;"><span style="font-family:_오성과한음B;">알아내는 방법은 <span style="color:#cc0099;">&ldquo;생각설계기술&rdquo;</span></span></span></p>

<p style="text-align: center;">&nbsp;</p>

<p style="text-align: center;">&nbsp;</p>

<p style="text-align: center;"><!--<table align="center" border="0" cellpadding="5" cellspacing="1" style="background-color:#ffffff;border-spacing:1px;width:50%;">
	<tbody>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;width:25%;">
			<p style="text-align: right;"><span style="font-size:16px;"><span style="font-family:_나눔스퀘어B;">특별혜택</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;border-color:#009900;border-radius:2px;border-spacing:1px;border-style:solid;border-width:2px;width:30%;">
			<p style="text-align: center;"><span style="font-size:16px;"><span style="font-family:_나눔스퀘어B;">특강 참여 시</span></span></p>

			<p style="text-align: center;"><span style="font-size:16px;"><span style="font-family:_나눔스퀘어B;"></span></span><span style="font-size:16px;"><span style="font-family:_나눔스퀘어B;">선착순 20명</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;width:25%;">
			<p><span style="font-size:16px;"><span style="font-family:_나눔스퀘어B;">20% 할인</span></span></p>
			</td>
		</tr>
	</tbody>
</table>--></p>
</div>
</div>
</div>
<div id="cell_1_115">
<div id="cell_115_116">
<div id="cell_116_117">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><span style="color:#ffffff;"><span style="font-size:28px;"><span style="font-family:_오성과한음B;">언어인지기술 적용 사례</span></span><span style="font-size:18px;"></span></span></p>

<p style="text-align: center;"><span style="color:#ffffff;"><span style="font-size:18px;"></span></span></p>

<p style="text-align: center;"><span style="color:#ffffff;"></span></p>

<p>&nbsp;</p>

<p><span style="color:#ffffff;"><span style="font-size:18px;">-언어인지기술 교재 출간 및 특허출원!<br />
-교육청 요청으로 인정교과서 5종 출판<br />
-언어인지기술 전국 세미나 진행<br />
-외국인 대상 한국어 교육과정 대학원에서 진행<br />
-성경독해세미나를 전국 교회 대상에 진행<br />
-미국 주정부 인가대학 독서인지학사과정 개설</span></span></p>

<p>&nbsp;</p>

<p style="text-align: center;"><!-- <table align="center" border="0" cellpadding="5" cellspacing="10" style="background-color:#009933;border-radius:5px;border-spacing:10px;width:40%;">
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
<div id="cell_1_73">
<div id="cell_73_74">
<div id="cell_74_75" class="float_clear">
<div id="cell_75_112">
<div id="cell_112_114">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><span style="letter-spacing:0;"><span style="color:#ffffff;"><span style="font-size:22px;"><span style="font-family:_오성과한음B;"><strong><span style="background-color:#ff0066;">이런 분들에게 추천합니다.</span></strong></span></span></span></span></p>
</div>
</div>
</div>
<div id="cell_75_113">
<div class="smartOutput htmlEditor float_clear"><p style="line-height:1.8;"><strong><span style="font-size:18px;"><span style="font-family:kopubbatang;"><span style="font-family:kopubbatang;"><span style="font-size:18px;">&bull;&nbsp;</span></span>AI시대에 필요한 알아내는 학습이 필요한 학생</span></span></strong></p>

<p style="line-height:1.8;"><strong><span style="font-family:kopubbatang;"><span style="font-size:18px;">&bull; 나만이 할 수 있는 컨텐츠 개발이 필요한 일반인</span></span></strong></p>

<p style="line-height:1.8;"><strong><span style="font-family:kopubbatang;"><span style="font-size:18px;">&bull; 시험이냐 취업이냐 창업이냐 고민하고 있는 대학생</span></span></strong></p>

<p style="line-height:1.8;"><strong><span style="font-family:kopubbatang;"><span style="font-size:18px;">&bull; 매일 일어나는 업무 문제상황에 괴로운 직장인</span></span></strong></p>

<p style="line-height:1.8;"><strong><span style="font-family:kopubbatang;"><span style="font-size:18px;">&bull; 각 부서의 기여도 향상에 고민하는 관리자</span></span></strong></p>

<p style="line-height:1.8;"><strong><span style="font-family:kopubbatang;"><span style="font-size:18px;">&bull; 회사의 모든 문제를 해결해야 하는 경영자</span></span></strong></p>

<p style="line-height:1.8;"><strong><span style="font-family:kopubbatang;"><span style="font-size:18px;">&bull; 나의 정보와 생각을 설계하여 창의성을 발휘하고 싶은 분</span></span></strong></p>

<p style="line-height:1.8;"><strong><span style="font-family:kopubbatang;"><span style="font-size:18px;">&bull; 기획, 보고, 제안 역량을 향상시키고 싶은 분</span></span></strong></p>

<p style="line-height:1.8;"><strong><span style="font-family:kopubbatang;"><span style="font-size:18px;">&bull; 회사에서 발표와 대화를 위한 설계를 알고 싶은 분</span></span></strong></p>

<p style="line-height:1.8;"><strong><span style="font-family:kopubbatang;"><span style="font-size:18px;">&bull; 사업실행의 프로세스를 알고 싶은 분</span></span></strong></p>

<p style="line-height:1.8;"><strong><span style="font-family:kopubbatang;"><span style="font-size:18px;">&bull; 상황이 언어로 바뀌는 과정의 원리와 기술이 궁금한 분</span></span></strong></p>
</div>
</div>
</div>
</div>
</div>
<div id="cell_1_28">
<div id="cell_28_38">
<div id="cell_38_39">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><span style="font-size:36px;"><span style="letter-spacing:4px;"><span style="font-family:_오성과한음B;">커리큘럼</span></span></span><!-- <table align="center" border="0" cellpadding="10" cellspacing="1" style="background-color:#009933;border-radius:5px;border-spacing:1px;width:30%;">
	<tbody>
		<tr>
			<td style="background-color: #009933; padding: 10px; text-align: center;"><strong><span style="font-family:_포천오성과한음R;"><span style="color:#ffffff;"><span style="font-size:18px;">과정 커리큘럼</span></span></span></strong></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p style="text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="color:#666633;"><span style="font-size:14px;">내 책도 쓰고 저술코치도 된다! 인생의 2막을 시작하세요!</span></span></span></p>
--></p>

<p style="text-align: center;">&nbsp;</p>

<table align="center" border="0" cellpadding="15" cellspacing="3" style="background-color:#33ccff;border-spacing:3px;width:75%;">
	<thead>
		<tr>
			<th scope="col" style="background-color: rgb(51, 102, 255); padding: 15px;"><span style="font-size:20px;"><span style="color:#ffffff;">주차</span></span></th>
			<th scope="col" style="background-color: rgb(51, 102, 255); padding: 15px;"><span style="font-size:20px;"><span style="color:#ffffff;">과정내용</span></span></th>
			<th scope="col" style="background-color: rgb(51, 102, 255); padding: 15px;"><span style="font-size:20px;"><span style="color:#ffffff;">세부내용</span></span></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">1주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">온리원의 역량을 가져라</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 온리원역량의미 &middot; 온리원역량진단</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 인류의 인지혁명 이해</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p style="text-align: center;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">2주차</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">인지역량의 증진이 희망이다</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 언어인지이해 : 언어습득인지원리, 언어활용 인지규칙</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 상황인지이해 : 상황인지의미, 상황인지가치, 상황인지원리, 상황인지사례, 상황인지단계</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p style="text-align: center;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">3주차</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">언제나 의문을 갖고 사는 기술</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 의문의 이해 : 의문의 가치, 의문의 의미, 의문의 원리, 의문의 태도</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 의문의 기술 : 사실의문, 인과의문, 계층의문, 포함의문, 비교의문 등</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p style="text-align: center;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">4주차</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">질문으로 의문을 해결하는 기술</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 질문의 이해 : 질문의 가치, 의미, 원리, 종류, 절차</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 질문의 기술 : 의문의 확정, 질문의 확정, 질문의 탐구, 사실의 확인, 사실의 검증, 의문의 설정</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">5주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">해답을 찾는 정보수집의 기술</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 정보수집의 이해 : 수집의 가치, 수집의 의미, 수집의 원리, 수집의 종류, 수집의 절차</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 정보수집의 기술 : 폴더형 수집, 설계형 수집</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p style="text-align: center;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">6주차</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">정보의 설계도를 만드는 기술</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 상향형 설계 : 상향형 설계의 이해, 상향형 설계의 기술</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 하향형 설계 : 하형 설계의 이해, 하형 설계의 기술</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">7주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">설계도를 패턴으로 만드는 기술</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 설계도 패턴의 이해 : 패턴의 가치, 패턴의 의미, 패턴의 원리, 패턴의 활용</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 설계도 패턴의 기술 : 어휘일치형, 순서배열형, 의문정보형, 의문완성형, 핵심정보형, 핵심완성형</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">8주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">설계도에서 문제를 찾는 기술</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 문제인지의 이해 : 문제인지의 가치, 의미, 원리, 도구</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 문제인지의 기술 : 부재문제, 부족문제, 사실오류, 관계오류 등</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">9주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">문제의 대안을 수집하는 기술</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 대안수집의 이해 : 대안수집의 가치, 의미, 원리, 도구</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 대안수집의 기술 : 원인분석, 원인요소분석, 우선순위배열</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">10주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">최적의 대안을 발견하는 기술</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 대안확정의 이해 : 대안확정의 가치, 의미, 원리, 도구</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 대안확정의 기술 : 결과설정, 자원확정, 제약요소확정, 순위배열</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">11주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">대안을 검증하는 알고리즘 기술</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 대안검증의 이해 : 대안검증의 가치, 의미, 원리, 도구</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 알고리즘의 이해 : 알고리즘의 가치, 의미, 원리, 도구</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">12주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">대안을 확증하는 검증기술</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 대안확증의 기술 : 전문가형 확증, 정형패턴 확증, 검증실험 확증</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 알고리즘의 연습 : 알고리즘 문제 풀이</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">13주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">대안을 계획으로 바꾸는 기술</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 계획수립의 이해 : 계획수립의 가치, 의미, 원리, 도구</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 계획수립의 기술 : 개요작성, 요소확정, 자원확정, 시간배열, 관리계획</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">14주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">계획이 성공으로 바뀌는 기술</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 사업수행의 이해 : 사업수행의 가치, 의미, 원리, 도구</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 사업수행의 기술 : 리더십, 조직, 상품개발, 상품유통, 광고홍보</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center; width: 20%;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">15주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">종강식, 상황인지의 희망</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 상황인지의 희망</span></span></p>

			<p><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">&middot; 4학기 수업 종강식</span></span></p>
			</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_1_59">
<div id="cell_59_60">
<div id="cell_60_61">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="" src="/image/180908_04.png" /></p>
</div>
</div>
</div>
</div>
<div id="cell_1_27">
<div id="cell_27_40">
<div id="cell_40_41">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><span style="font-family:_오성과한음B;"><span style="font-size:40px;"><strong>교육 전문가들이 인정한 교육</strong></span></span></p>

<p>&nbsp;</p>

<table align="center" border="0" cellpadding="10" cellspacing="10" style="background-color:#c9ced5;border-spacing:10px;width:80%;">
	<tbody>
		<tr>
			<td style="padding: 10px; background-color: rgb(255, 255, 255);">
			<p style="text-align: center;"><span style="font-size:26px;"><span style="font-family: _나눔스퀘어B;"><strong><span style="color: rgb(192, 57, 43);">&ldquo;교육계의 혁명!&rdquo;</span></strong></span></span></p>

			<p style="text-align: right;"><span style="font-size:18px;"><span style="font-family: _나눔스퀘어B;">(김0희, 전 ㅈ교육청 교육장)</span></span></p>

			<p style="text-align: right;">&nbsp;</p>

			<p style="text-align: right;"><span style="font-size:26px;"></span></p>

			<p style="text-align: center;"><span style="font-size:26px;"><span style="font-family: _나눔스퀘어B;"></span></span></p>

			<p style="text-align: center;"><span style="font-size:20px;"><span style="font-family: _나눔스퀘어B;">지금까지 고민했던 독서교육의 한계를 해결할 수 있는 탁월한 학습기술. 이것은 교육계의 혁명입니다!</span></span></p>
			</td>
			<td style="padding: 10px; background-color: rgb(255, 255, 255);">
			<p style="text-align: center;"><span style="font-size:26px;"><span style="font-family: _나눔스퀘어B;"><span style="color: rgb(192, 57, 43);">&ldquo;획기적인 기술&rdquo;</span></span></span></p>

			<p style="text-align: right;"><span style="font-size:18px;"><span style="font-family: _나눔스퀘어B;">(문0식, ㅇ교육회사 대표)</span></span></p>

			<p style="text-align: center;"><span style="font-size:22px;"><span style="font-family: _나눔스퀘어B;"></span></span><span style="font-size:26px;"><span style="font-family: _나눔스퀘어B;"></span></span><br />
			<span style="font-size:20px;"><span style="font-family: _나눔스퀘어B;">기존의 방법을 백지화하고&nbsp;다시 시작하게 되는 획기적인 기술입니다.</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="padding: 10px; background-color: rgb(255, 255, 255);">
			<p style="text-align: center;"><span style="font-size:26px;"><span style="font-family: _나눔스퀘어B;"><span style="color: rgb(192, 57, 43);">&ldquo;정보융합 최적화 프로그램!&rdquo;</span></span></span></p>

			<p style="text-align: right;"><span style="font-size:18px;"><span style="font-family: _나눔스퀘어B;">(유0열, ㄱ대학원 교수)</span></span></p>

			<p style="text-align: center;"><span style="font-size:26px;"><span style="font-family: _나눔스퀘어B;"></span></span><br />
			<span style="font-size:20px;"><span style="font-family: _나눔스퀘어B;">사막에서 오아이스를 만난 느낌&hellip;</span></span></p>

			<p style="text-align: center;"><span style="font-size:20px;"><span style="font-family: _나눔스퀘어B;"><span style="letter-spacing:1.5px;">생각설계기술로 책쓰기 과정은 미래인재양성을 위한 최적화 프로그램입니다.</span></span></span></p>
			</td>
			<td style="padding: 10px; background-color: rgb(255, 255, 255);">
			<p style="text-align: center;"><span style="font-size:26px;"><span style="font-family: _나눔스퀘어B;"><span style="color: rgb(192, 57, 43);">&ldquo;교수 학습법 중 최고&rdquo;</span></span></span></p>

			<p style="text-align: right;"><span style="font-size:18px;"><span style="font-family: _나눔스퀘어B;">(김0곤, ㅅ대학교 교수)</span></span></p>

			<p style="text-align: right;">&nbsp;</p>

			<p style="text-align: right;"><span style="font-size:26px;"></span></p>

			<p><span style="font-size:26px;"><span style="font-family: _나눔스퀘어B;"></span></span></p>

			<p style="text-align: center;"><span style="font-size:20px;"><span style="font-family: _나눔스퀘어B;">사람을 변화시키기 위한 교수학습법은 여러 가지가 있지만, 나는 이 과정이 그 중 가장 높은 위치에 있다고 확신한다.</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="padding: 10px; background-color: rgb(255, 255, 255);">
			<p style="text-align: center;"><span style="font-size:26px;"><span style="font-family: _나눔스퀘어B;"><span style="color: rgb(192, 57, 43);">&ldquo;교육의 새로운 패러다임&rdquo;</span></span></span></p>

			<p style="text-align: right;"><span style="font-size:18px;"><span style="font-family: _나눔스퀘어B;">(이0미, ㅎ대학원 어학원 교수)</span></span></p>

			<p style="text-align: right;">&nbsp;</p>

			<p style="text-align: right;"><span style="font-size:26px;"></span></p>

			<p><span style="font-size:26px;"><span style="font-family: _나눔스퀘어B;"></span></span></p>

			<p style="text-align: center;"><span style="font-size:20px;"><span style="letter-spacing:1.5px;"><span style="font-family: _나눔스퀘어B;">생각설계과제를 할 때 신천지가 펼쳐지는 것 같았어요. 이것은 교육의 새로운 패러다임입니다.</span></span></span></p>
			</td>
			<td style="padding: 10px; background-color: rgb(255, 255, 255);">
			<p style="text-align: center;"><span style="font-size:26px;"><span style="font-family: _나눔스퀘어B;"><span style="color: rgb(192, 57, 43);">&ldquo;귀한 학습시스템&rdquo;</span></span></span></p>

			<p style="text-align: right;"><span style="font-size:18px;"><span style="font-family: _나눔스퀘어B;">(김0민, 초등학교 교사)</span></span></p>

			<p style="text-align: right;">&nbsp;</p>

			<p style="text-align: right;"><span style="font-size:26px;"></span></p>

			<p style="text-align: right;"><span style="font-size:26px;"><span style="font-family: _나눔스퀘어B;"></span></span></p>

			<p style="text-align: center;"><span style="font-size:20px;"><span style="font-family: _나눔스퀘어B;">단순한 학습기술 방법론을 넘어 한 사람과 인류의 문제를 해결할 수 있는 &lsquo;귀한 시스템&rsquo;입니다.</span></span></p>
			</td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>
</div>
</div>
</div>
<div id="cell_27_45">
<div id="cell_45_49">
<div id="cell_49_53">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="사고를 깨우는 학습기술 유대인을 뛰어넘는 인재가 되는 학습방법" src="/image/180309_04.png" /><br />
<!-- <p style="text-align: center;"><span style="font-family:_나눔스퀘어EB;"><span style="font-size:12px;"></span><span style="font-size:18px;">정보융합설계기술 코칭결과</span></span></p>

<p>&nbsp;</p>

<table align="center" border="0" cellpadding="5" cellspacing="20" style="border-spacing:20px;width:70%;">
	<tbody>
		<tr>
			<td style="background-color: #D6D6D6; padding: 15px; width:45%">
			<p style="text-align: center; "><span style="font-size:16px;"><span style="font-family:_나눔스퀘어B;">교사연수에서 인정</span></span></p>

			<p style="text-align: center;"><span style="font-size:14px;"><span style="color:#d35400;"><span style="font-family:_나눔스퀘어B;">&ldquo;유대교육을 넘어설 수 있다!&rdquo;</span></span></span></p>
			</td>
			<td style="background-color: #D6D6D6; padding: 15px;">
			<p style="text-align: center;"><span style="font-size:14px;"><span style="font-family:_나눔스퀘어B;">책쓰기 코칭 결과</span></span></p>

			<p style="text-align: center;"><span style="font-size:14px;"><span style="color:#d35400;"><span style="font-family:_나눔스퀘어B;">&ldquo;청소년이 썼다면 누구나 쓸 수 있다!&rdquo;</span></span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px; width:45%">
			<p><span style="font-size:14px;"><span style="color:#d35400;"><strong>90%</strong></span> 교사<strong>&nbsp;<span style="font-family:나눔명조;">&ldquo;정보설계기술이 &lt;영재.유대교육&gt;에 비해 <u>사고력이 더 개발</u>된다.&rdquo;</span></strong>고 평가</span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 5px; width:40%"><img alt="" src="/image/학습쇼크.png" style="float: left; width: 40%;" />&nbsp;&nbsp;<img alt="" src="/image/저술.png" style="width: 50%; float: right;" /></td>
		</tr>
		<tr>
			<td style="background-color: #D6D6D6; padding: 5px; ">
			<p style="text-align: center;"><span style="font-size:14px;">정보설계기술이 영재학습, 유대인학습에 비해</span></p>

			<p style="text-align: center;"><span style="font-size:14px;">사고력 개발에 비해 더 나은 결과를 산출할 수</span></p>

			<p style="text-align: center;"><span style="font-size:14px;">있다고 생각하는가?</span></p>
			</td>
			<td colspan="1" rowspan="2" style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p><span style="font-size:14px;"><span style="font-family:_나눔스퀘어B;"><strong>[청소년 논문코칭]</strong></span></span></p>

			<p><span style="font-size:14px;"><span style="font-family:_나눔스퀘어L;">학습방법에 대한 연구(30명)</span></span></p>

			<p><span style="font-size:14px;"><span style="font-family:_나눔스퀘어L;">한국 근대사 연구(20명)</span></span></p>

			<p><span style="font-size:14px;"><span style="font-family:_나눔스퀘어L;">서양철학의 변천사 연구(20명)</span></span></p>

			<p><span style="font-size:14px;"><span style="font-family:_나눔스퀘어L;">동양철학의</span></span></p>

			<p><span style="font-size:14px;"><span style="font-family:_나눔스퀘어L;">나의 자서전 (30명)</span></span></p>

			<p><span style="font-size:14px;"><span style="font-family:_나눔스퀘어L;">미래학</span></span></p>

			<p><span style="font-size:14px;"><span style="font-family:_나눔스퀘어L;">미래의 네트워크와 리더쉽 연구 (10명) 외 다수</span></span></p>

			<p><span style="font-size:14px;"></span></p>

			<p><span style="font-size:14px;"><span style="font-family:_나눔스퀘어L;"></span></span></p>

			<p><span style="font-size:14px;"><span style="font-family:_나눔스퀘어B;"><strong>[청소년 저술코칭]</strong></span></span></p>

			<p><span style="font-size:14px;"><span style="font-family:_나눔스퀘어L;">학습쇼크(출간)</span></span></p>

			<p><span style="font-size:14px;"><span style="font-family:_나눔스퀘어L;">청소년이 쓴 청소년을 위한 리더십 (출간예정)</span></span></p>

			<p><span style="font-size:14px;"><span style="font-family:_나눔스퀘어L;">청소년이 쓴 볼매대화법(출간예정)</span></span></p>

			<p><span style="font-size:14px;"><span style="font-family:_나눔스퀘어L;">청소년이 쓴 스스로 한 자만이 산다(출간예정) 외 다수</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 5px;">
			<p><img alt="" src="/image/그래프.png" /></p>

			<p style="text-align: center;">&nbsp;</p>

			<p style="text-align: center;"><span style="font-size:11px;">(교사연수 설문집계, 온리원연구소)</span></p>
			</td>
		</tr>
	</tbody>
</table>

<div class="dragbox" style="background-color:#ccff99;border-image:initial;margin-bottom:10px;margin-top:10px;padding:7px 10px;">
<p style="text-align: center;"><span style="font-family:_나눔스퀘어L;"></span><span style="font-size:16px;"><span style="color:#2980b9;"><span style="font-family:_나눔스퀘어EB;"><u>정보의 핵심을 찾고</u><u>, </u><u>지식을 </u><u>설계함</u><u>으로써</u><u> </u><u>&nbsp;</u><u>체계화 </u><u>시키고</u><u> 도식화</u></span></span><span style="font-family:_나눔스퀘어L;">할 수 있다는 점이 인상 깊었다.</span></span></p>

<p style="text-align: center;"><span style="font-family:_나눔스퀘어L;">(ㅌ직무연수 참여 교사)</span></p>
</div>
--></p>
</div>
</div>
</div>
</div>
<div id="cell_27_56">
<div id="cell_56_57">
<div id="cell_57_58">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><strong><span style="font-size:28px;"><span style="font-family:_나눔스퀘어B;"><span style="color:#3498db;"><u>정보의 핵심을 찾고, 지식을 설계함으로써 &nbsp;체계화 시키고 도식화</u></span></span></span></strong><span style="font-size:24px;"><span style="font-family:_나눔스퀘어B;">할 수 있다는 점이 인상 깊었다.</span></span><span style="font-size:26px;"><span style="font-family:_나눔스퀘어B;"> </span></span></p>

<p style="text-align: right;"><span style="font-size:26px;"><span style="font-family:_나눔스퀘어B;"></span></span><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">(ㅌ직무연수 참여 교사)</span></span><span style="font-size:28px;"></span></p>
</div>
</div>
</div>
</div>
<div id="cell_27_46">
<div id="cell_46_50">
<div id="cell_50_54">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="20여 년의 독서인지연구 10여 년의 언어인지기술 대중화 노력" src="/image/180209_07.png" /></p>
</div>
</div>
</div>
</div>
<div id="cell_27_48">
<div id="cell_48_52">
<div id="cell_52_63">
<div class="smartOutput htmlEditor float_clear"><p><span style="font-family:_포천오성과한음R;"></span></p>

<p style="text-align: center;"><img alt="한번 습득하면 일상생활에서 전문영역까지 어디서든 활용할 수 있는 언어인지기술 사고력 창의력 개발 한번에 해결가능" src="/image/180908_03.png" /></p>
</div>
</div>
</div>
<div id="cell_48_55">
<div id="cell_55_66" class="float_clear">
<div id="cell_66_69">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><span style="color:#ffffff;"><span style="font-family:_오성과한음B;"><strong><span style="font-size: 28px;">교수</span><span style="font-size:36px;">&nbsp;송 조 은</span></strong></span></span></p>

<p style="text-align: center;">&nbsp;</p>

<p style="text-align: center;">&nbsp;</p>

<p style="text-align: center;"><img alt="" src="/image/송조은이미지(광진구청1).jpg" style="height: 334px; width: 250px; border-radius: 5px;" /></p>

<p style="text-align: center;"><span style="color:#99ffff;"><span style="font-family:_나눔스퀘어EB;"><span style="font-size:36px;"><b>| 온리원역량 전문가</b>&nbsp;<span style="font-family:_나눔스퀘어EB;"><span style="font-size:36px;"><b>| </b></span></span></span></span></span></p>

<p style="line-height:250%;">&nbsp;</p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>현) </b><strong>대한신학대학원 인지코칭학 교수</strong><b>(언어인지기술 개발)</b></span></span></p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>현) </b><strong>킹데이빗대학원 인지코칭학 교수</strong></span></span></p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>현) (사)국민독서인지문화원 이사장&nbsp;</b></span></span></p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>현) 국제인지코칭학회 이사장</b></span></span></p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>현) 국제인지코치협회 이사장&nbsp;</b></span></span></p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>현) <span style="font-family:kopubbatang;">온리원리딩클럽 대표</span></b></span></span></p>

<p style="line-height:250%;"><span style="font-family:kopubbatang;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>현) 온리원연구소 소장</b>&nbsp;</span></span></span></p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>전) 꿈의학교 설립 및 교장 역임&nbsp;</b></span></span></p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><strong>대한신학대학원 철학 명예박사</strong></span></span></p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>King David University Religious education 명예박사</b>&nbsp;</span></span></p>

<p style="line-height:250%;">&nbsp;</p>

<p><span style="color:#ffffff;"></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:24px;"></span></span></span></p>

<p><span style="font-family:_나눔스퀘어L;"><span style="font-size:20px;"><span style="color:#ffffff;"><strong>핵심역량개발전문가, 인재양성 전문가</strong></span></span></span></p>

<p><span style="font-family:_나눔스퀘어L;"><span style="font-size:20px;"><span style="color:#ffffff;"><strong>언어인지기술, 인간역량체계도, 원페이지요약기술, 인지인성기술, 자주경영기술,정보활용기술, 건강관리기술 외 다수 학습법 개발</strong></span></span></span></p>

<p><span style="font-family:_나눔스퀘어L;"><span style="font-size:20px;"><span style="color:#ffffff;"><strong>원페이지북 시스템, 온리원리딩 시스템, 온리원파워 시스템, 수평기 하나로 시스템 외 다수 시스템 개발</strong></span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:24px;">&nbsp;</span></span></span></p>

<p><span style="color:#ffffff;"></span></p>

<p><span style="color:#ffffff;"><span style="font-size:20px;"><span style="font-family:_나눔스퀘어L;"></span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;">□&nbsp;대한민국 출판문화예술 대상 독서진흥 대상수상(2014)&nbsp;</span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;">□</span></span></span>&nbsp;대한민국공정사회발전대상 수상(2014)&nbsp;</span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;">□&nbsp;</span></span></span>교육부 표창장 수여(2017)&nbsp;</span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;">□</span></span></span>&nbsp;인천강화교육지원청 자유학기제 진로탐색 및 인문사회 부분- 온리원 나의 자서전, 나의 행복메이커 교재 및 수업과정 개발(2015, NEIS탑재),&nbsp;공감소통,건강관리 자유학기제 교재 개발&nbsp;</span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;">□&nbsp;</span></span></span>독서법, 학습법 강의 5,000회 이상 진행&nbsp;</span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;">□&nbsp;</span></span></span>강화교육청, 제주교육청 외 다수 교육청 교장/교사대상 연수</span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;">□&nbsp;</span></span></span>(저서) </span></span></span><span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;">언어인지기술(대학원교재) | 독서쇼크 | 온리원노트기술 | 온리원정보기억법 | 온리원파워&nbsp;<span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;">|&nbsp;</span></span></span></span></span></span><span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;">온리원 소통공감기술 | 온리원 건강관리기술 | 꿈의학습혁명 외 다수</span></span></span></p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_48_65">
<div id="cell_65_67">
<div id="cell_67_71">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><strong><span style="font-family:_오성과한음B;"><span style="font-size:36px;">과정 참여안내</span></span></strong></p>

<p style="text-align: center;">&nbsp;</p>

<table align="center" border="0" cellpadding="15" cellspacing="3" style="background-color:#66ccff;border-spacing:3px;width:70%;">
	<tbody>
		<tr>
			<td style="background-color: rgb(153, 255, 255); padding: 15px; text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">개강일</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<ul>
				<li><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">대학원 개강 2018년 9월 8일 (매주 토요일)</span></span></li>
				<li><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">전문가/영상 개강 수시모집</span></span></li>
			</ul>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(153, 255, 255); padding: 15px;">
			<p style="text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">과정선택</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">전문가과정(비학위), 석사과정(학위) 선택</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(153, 255, 255); padding: 15px;">
			<p style="text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">수업장소</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">* 온라인/현장 동시 진행</span></span></p>

			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">1) 현장참여 : 석수역 1호선 대학원 강의실</span></span></p>

			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">2) 온라인 참여 : 실시간 화상참여, 영상참여 모두 가능</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(153, 255, 255); padding: 15px;">
			<p style="text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">참여방법</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">현장수업/실시간 화상/영상 수업 중 선택 가능</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(153, 255, 255); padding: 15px; text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">상담문의</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">070-4477-6631</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(153, 255, 255); padding: 15px;">
			<p style="text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">진행기관</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;"></span></span></p>

			<ul>
				<li><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">주최 : (사)국민독서인지문화원&nbsp;부설 온리원교육연구소</span></span></li>
				<li><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">주관 : 국제인지코치협회,&nbsp;</span></span><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">대한신학대학원 인지코칭학</span></span></li>
			</ul>

			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;"></span></span></p>
			</td>
		</tr>
	</tbody>
</table>

<p style="text-align: center;">&nbsp;</p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_48_64">
<div id="cell_64_68">
<div id="cell_68_72">
<div id="cell_72_81">
<div id="cell_81_84">
<?
include_once '/home/hosting_users/ilearning1/www/pages/auto_data/saved/module_1259.inc';
?>
</div>
</div>
<div id="cell_72_82">
<div id="cell_82_83">
<?php
$module_id=1268;
$cell_id='cell_82_83';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/form/form_1.php';
?>
</div>
</div>
</div>
</div>
</div>
<div id="cell_48_77">
<div id="cell_77_78">
<div class="smartOutput htmlEditor float_clear"><div style="background-attachment:initial;background-clip:initial;background-color:#ffcc00;background-image:url(&quot;/image/180209_17.png&quot;);background-origin:initial;background-position:center top;background-size:initial;border-image:initial;border-radius:15px;margin:5px;padding:10px;">
<p style="text-align: center;"><span style="font-size:16px;"><strong><span style="font-family:_나눔스퀘어EB;"><span style="letter-spacing:3px;"><a class="ozbutton orange big rounded" href="#cell_82_83">신청하기</a></span></span></strong></span><span style="line-height:0;"></span></p>
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
