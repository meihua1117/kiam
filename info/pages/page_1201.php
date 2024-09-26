<?php
$wizsave_mode=1;
@include_once '/home/hosting_users/ilearning1/www/builder/lib/lib.php';
@include_once $ui_homepath.'/pages/menu_id.php';
$need_html5player=0;
$menu_id=1201;
$ui_loginpage='/pages/page_37.php';
$connect=dbconn();
@access_right_chk(1, '/pages/page_37.php', '');
$ui_og_arr=@print_board_title('셀링플랜으로  정부지원 정책자금 받기  설명회', '샐링플랜으로  정부지원 정책자금 받기  설명회', '', '');
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
<meta name="keywords" content="#비즈니스, #정책자금, #정부지원">
<link rel="stylesheet" href="/cgi_bank/lib/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/style.css?r=1548830785" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/jquery-ui.css" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/page_1201.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5457.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5462.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5460.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5456.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5458.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5459.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5463.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5464.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5465.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5257.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5466.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5253.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5468.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5467.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5469.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5472.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_5258.css?r=0311170221" type="text/css">
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
<body id="menu_group_136" class="menu_id_1201 menu_order_02">
<div id="page_container" class="float_clear"><div id="cell_0_1">
<div id="cell_1_13">
<div id="cell_13_14">
<div id="cell_14_15">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190812_14.png" style="width: 80%;" /></p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_1_102">
<div id="cell_102_103">
<div id="cell_103_104">
<div class="smartOutput htmlEditor float_clear"><p><img src="/image_bank/landing/190812_03.png" /></p>
</div>
</div>
</div>
</div>
<div id="cell_1_17">
<div id="cell_17_22" class="row">
<div id="cell_22_97">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190812_04.png" style="width: 80%;" /></p>

<p>&nbsp;</p>
</div>
</div>
<div id="cell_22_30" class="col-xs-12 col-sm-7">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center; line-height: 1.1;">&nbsp;</p>

<p style="text-align: center;">&nbsp;</p>

<p><span style="font-family: _나눔스퀘어L;"></span></p>

<div style="padding: 7px 10px; border: 1px solid rgb(204, 204, 204); border-image: none; background-color: rgb(0, 0, 102);">
<p><span style="font-size: 18px;"><span style="color: rgb(255, 255, 255);"><span style="font-family: _나눔스퀘어EB;"><span style="background-color: rgb(0, 0, 102);">1%가 인류자산 50% 가진 부의 불균형 시대 등장</span></span></span></span></p>
</div>

<p><span style="font-family: _나눔스퀘어L;"></span></p>

<div class="dragbox" style="padding: 7px 10px; border: 1px solid rgb(204, 204, 204); border-image: none; margin-top: 10px; margin-bottom: 10px;">
<p><strong><span style="font-size: 15px;"><span style="font-family: _나눔스퀘어L;">1%의 사람들이 인류자산의 50%를 가지므로 인해 부의 불균형의 시대가 되었습니다. 국내의 경우 10%가 66%의 자산을 가지고 있고, 더 놀라운 것은 50%의 국민이 가진 자산의 총합이 2%밖에 안됩니다.</span></span></strong></p>
</div>

<p><span style="font-family: _나눔스퀘어L;"></span></p>

<p>&nbsp;</p>

<div style="padding: 7px 10px; border: 1px solid rgb(204, 204, 204); border-image: none; background-color: rgb(0, 0, 102);">
<p><span style="font-size: 18px;"><span style="color: rgb(255, 255, 255);"><span style="font-family: _나눔스퀘어EB;"><span style="background-color: rgb(0, 0, 102);">대기업의 수익독점구조와 플랫폼산업의 출현 때문</span></span></span></span></p>
</div>

<p>&nbsp;</p>

<p><span style="font-family: _나눔스퀘어L;"></span></p>

<div class="dragbox" style="padding: 7px 10px; border: 1px solid rgb(204, 204, 204); border-image: none; margin-top: 10px; margin-bottom: 10px; background-color: rgb(255, 255, 255);">
<p><strong><span style="font-size: 15px;"><span style="font-family: _나눔스퀘어L;">이 현상은 대기업의 수익 독점구조와 4차산업시대의 초연결의 상징인 플랫폼산업의 출현 때문입니다. 세계 10대 기업 중 8개가 플랫폼 사업인데 이는 일부러 만든 것이 아니고 기술의 발전에 의해 자연스럽게 생성되었을 뿐입니다.</span></span></strong></p>
</div>

<p><span style="font-family: _나눔스퀘어L;"></span></p>

<p>&nbsp;</p>

<div style="padding: 7px 10px; border: 1px solid rgb(204, 204, 204); border-image: none; background-color: rgb(0, 0, 102);">
<p><span style="font-size: 18px;"><span style="color: rgb(255, 255, 255);"><span style="font-family: _나눔스퀘어EB;"><span style="background-color: rgb(0, 0, 102);">부의 불균형 해소와 서민기업살리기 가능 기술 발견</span></span></span></span></p>
</div>

<p>&nbsp;</p>

<p><span style="font-family: _나눔스퀘어L;"></span></p>

<div class="dragbox" style="padding: 7px 10px; border: 1px solid rgb(204, 204, 204); border-image: none; margin-top: 10px; margin-bottom: 10px; background-color: rgb(255, 255, 255);">
<p><strong><span style="font-size: 15px;"><span style="font-family: _나눔스퀘어L;">문제는 이렇게 형성된 부의 불균형 문제를 어떻게 해결할 것인가에 의견을 모아야 한다는 것입니다. 다행히 올해 7월에 출범한 서경련(서민경제살리기국민연합)은 온리원연구소에서 개발한 강력한 셀링솔루션을 보고, 세계의 부의 불균형 문제와 서민경제를 살리는 방법을 찾았습니다.</span></span></strong></p>
</div>
</div>
</div>
</div>
</div>
<div id="cell_1_18">
<div id="cell_18_23">
<div id="cell_23_31">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190827_08.png" style="width: 90%;" /></p>

<p class="figwrap" style="text-align: center;">&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_1_94">
<div id="cell_94_95">
<div id="cell_95_96">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190812_06.png" /></p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_1_105">
<div id="cell_105_106">
<div id="cell_106_107">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190812_07.png" style="width: 80%;" /></p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190812_08.png" style="width: 80%;" /></p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_1_108">
<div id="cell_108_40">
<div id="cell_40_41">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190827_14.png" style="width: 90%;" /></p>

<p class="figwrap" style="text-align: center;">&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_1_46">
<div id="cell_46_50">
<div id="cell_50_54">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190812_10.png" style="width: 90%;" /></p>

<p class="figwrap" style="text-align: center;">&nbsp;</p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_1_28">
<div id="cell_28_38">
<div id="cell_38_39">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190721_02.png" style="width: 90%;" /></p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_1_47">
<div id="cell_47_51">
<div id="cell_51_62">
<div class="smartOutput htmlEditor float_clear"><h2 class="oz_txt_h2" style="text-align: center;"><span style="letter-spacing: 4px;"><span style="font-family: _나눔스퀘어EB;"><strong>강연 내용</strong></span></span></h2>

<p>&nbsp;</p>

<table align="center" border="0" cellpadding="10" cellspacing="3" style="width: 80%; border-spacing: 3px;">
	<tbody>
		<tr>
			<td style="padding: 10px; text-align: center; vertical-align: middle; background-color: rgb(0, 102, 204);">
			<h3 class="oz_txt_h3"><span style="color: rgb(255, 255, 255);"><span style="font-family: _나눔스퀘어B;">강의 </span></span></h3>

			<h3 class="oz_txt_h3"><span style="color: rgb(255, 255, 255);"><span style="font-family: _나눔스퀘어B;">주제</span></span></h3>
			</td>
			<td colspan="2" style="padding: 10px; background-color: rgb(255, 255, 255);">
			<h4 class="oz_txt_h4">&nbsp;</h4>

			<p><span style="left: -5000px; width: 1px; height: 1px; overflow: hidden; position: absolute;"></span></p>

			<h4 class="oz_txt_h4"><span style="left: -5000px; width: 1px; height: 1px; overflow: hidden; position: absolute;"><span style="font-family: _나눔스퀘어B;">4차산업형&nbsp;셀링플랫폼</span></span><span style="left: -5000px; width: 1px; height: 1px; overflow: hidden; position: absolute;"></span></h4>

			<h2 class="oz_txt_h2"><span style="left: -5000px; width: 1px; height: 1px; overflow: hidden; position: absolute;"><span style="font-family: _나눔스퀘어B;">4차산업형&nbsp;셀링플랫폼</span></span><span style="font-family:_나눔스퀘어EB;">온리원셀링 솔루션 설명회</span></h2>

			<h2 class="oz_txt_h2"><span style="font-family:_나눔스퀘어EB;">(4차산업형&nbsp;셀링플랫폼)</span></h2>
			</td>
		</tr>
		<tr>
			<td style="padding: 10px; text-align: center; vertical-align: middle; background-color: rgb(0, 102, 204);">
			<h3 class="oz_txt_h3"><span style="color: rgb(255, 255, 255);"><span style="font-family: _나눔스퀘어B;">강의 </span></span></h3>

			<h3 class="oz_txt_h3"><span style="color: rgb(255, 255, 255);"><span style="font-family: _나눔스퀘어B;">내용</span></span></h3>
			</td>
			<td colspan="2" style="padding: 10px; background-color: rgb(255, 255, 255);">
			<h2 class="oz_txt_h2" style="line-height: 1.8;"><span style="font-family:_나눔스퀘어B;">4차산업시대의 마케팅 변화 이해</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.8;"><span style="font-family:_나눔스퀘어B;">1인셀링시대가&nbsp;시작되었다</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.8;"><span style="font-family:_나눔스퀘어B;">&nbsp;온리원셀링역량이란?</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.8;"><span style="font-family:_나눔스퀘어B;">온리원셀링솔루션&nbsp;이해와&nbsp;로직</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.8;"><span style="font-family:_나눔스퀘어B;">온리원셀링&nbsp;솔루션&nbsp;훈련</span></h2>
			</td>
		</tr>
		<tr>
			<td style="padding: 10px; text-align: center; vertical-align: middle; background-color: rgb(0, 102, 204);">
			<h3 class="oz_txt_h3"><span style="color: rgb(255, 255, 255);"><span style="font-family: _나눔스퀘어B;">강의 </span></span></h3>

			<h3 class="oz_txt_h3"><span style="color: rgb(255, 255, 255);"><span style="font-family: _나눔스퀘어B;">강사</span></span></h3>
			</td>
			<td style="padding: 10px; background-color: rgb(255, 255, 255);">
			<h4 class="oz_txt_h4">&nbsp;</h4>

			<h2 class="oz_txt_h2"><span style="font-family: _나눔스퀘어B;">송조은 대표</span></h2>

			<p>&nbsp;</p>

			<h3 class="oz_txt_h3" style="line-height: 1.4;"><span style="font-family: 나눔스퀘어;">온리원그룹 회장</span></h3>

			<h3 class="oz_txt_h3" style="line-height: 1.4;"><span style="font-family: 나눔스퀘어;">1인셀링협회 회장</span></h3>

			<h3 class="oz_txt_h3" style="line-height: 1.4;"><span style="font-family: 나눔스퀘어;">국제인지코치협회 회장</span></h3>

			<h3 class="oz_txt_h3" style="line-height: 1.4;"><span style="font-family: 나눔스퀘어;">KDU대학원 역량코칭학 교수</span></h3>

			<h3 class="oz_txt_h3" style="line-height: 1.4;"><span style="font-family: 나눔스퀘어;">온리원연구소 소장</span></h3>
			</td>
			<td style="padding: 10px; background-color: rgb(255, 255, 255);">
			<h3 class="oz_txt_h3" style="line-height: 1.4;">&nbsp;</h3>

			<h3 class="oz_txt_h3" style="line-height: 1.4;"><span style="font-family: 나눔명조;"></span></h3>
			</td>
		</tr>
	</tbody>
</table>

<p class="figwrap" style="text-align: center;">&nbsp;</p>

<p class="figwrap" style="text-align: center;">&nbsp;</p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_1_48">
<div id="cell_48_86">
<div id="cell_86_87">
<div id="cell_87_89">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190721_06.png" style="width: 90%;" /></p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_48_85">
<div id="cell_85_88">
<div id="cell_88_110">
<div class="smartOutput htmlEditor float_clear"><h1 class="oz_txt_h1" style="text-align: center;"><img src="/image_bank/landing/190812_11.png" style="width: 10%;" />&nbsp;<span style="font-family:_나눔스퀘어EB;">이런 분들 참여를 추천합니다<em>!</em></span></h1>
</div>
</div>
<div id="cell_88_90">
<div class="smartOutput htmlEditor float_clear"><h2 class="oz_txt_h2">&nbsp;</h2>

<h2 class="oz_txt_h2">&nbsp;</h2>

<table border="0" cellpadding="5" cellspacing="1" style="border-spacing:1px;width:100%;">
	<tbody>
		<tr>
			<td style="padding: 5px;">
			<h2 class="oz_txt_h2">&nbsp;</h2>

			<h2 class="oz_txt_h2">&nbsp;</h2>
			</td>
			<td style="padding: 5px;">
			<h2 class="oz_txt_h2" style="line-height: 1.5;"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎&nbsp;</span>오랫동안 영업 및 판매로 힘들어 하고 있는 분</span></h2>

			<h2 class="oz_txt_h2"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎ 셀링플랫폼으로 새로운&nbsp;셀링을&nbsp;하고&nbsp;싶은&nbsp;마케터</span></span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.5;"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎&nbsp;</span>다양한 상품을 셀링을 하고 싶은 분</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.5;"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎&nbsp;</span>직장 및 개인사업을 하면서 투잡을 하고 싶은 분</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.5;"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎&nbsp;</span>모바일마케팅 기술을 배워 투잡하고 싶은 분</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.5;"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎&nbsp;</span>모바일마케팅 기술을 배워 무점포창업 하고 싶은 분</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.5;"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎&nbsp;</span>DB수집 및 문자발송 알바를 하고 싶은 분</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.5;"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎&nbsp;</span>디지털 노마드 생활을 꿈꾸는 분</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.5;"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎&nbsp;</span>소상공인의 GOOD상품 소개하고 싶은 분</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.5;"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎&nbsp;</span>자신의 상품을 홍보플랜유통하고 싶은 분</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.5;"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎&nbsp;</span>대량문자솔루션을 사용해보고 싶은 분</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.5;"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎&nbsp;</span>타겟종합DB솔루션을 사용해보고 싶은 분</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.5;"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎&nbsp;</span>메시지카피라이팅기술을 직접 해보고 싶은 분</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.5;"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎&nbsp;</span>멀티플래너의 팀별 프로젝트에 참여해보고 싶은 분</span></h2>

			<h2 class="oz_txt_h2" style="line-height: 1.5;"><span style="font-family:_나눔스퀘어B;"><span style="font-family:_나눔스퀘어B;">∎&nbsp;</span>온라인마케팅솔루션을 체험하고 싶은 분</span></h2>
			</td>
		</tr>
	</tbody>
</table>

<h2 class="oz_txt_h2">&nbsp;</h2>
</div>
</div>
<div id="cell_88_109">
<div class="smartOutput htmlEditor float_clear"><h4 class="oz_txt_h4" style="text-align: center;"><span style="color: rgb(41, 128, 185);"><span style="font-family: _나눔스퀘어B;">※ 신청자에게 자세한 일정과 길 안내 정보를&nbsp;보내드립니다.</span>&nbsp;</span></h4>
</div>
</div>
</div>
</div>
<div id="cell_48_111">
<div id="cell_111_112">
<div id="cell_112_113">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190812_15.png" style="width: 80%;" /></p>

<p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190812_16.png" style="width: 83%;" /></p>

<p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190812_17.png" style="width: 83%;" /></p>

<p class="figwrap" style="text-align: center;">&nbsp;</p>

<p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190812_18.png" style="width: 83%;" /></p>

<p class="figwrap" style="text-align: center;">&nbsp;</p>

<p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190812_19.png" style="width: 83%;" /></p>

<p class="figwrap" style="text-align: center;">&nbsp;</p>

<p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190812_20.png" style="width: 83%;" /></p>

<p class="figwrap" style="text-align: center;">&nbsp;</p>

<p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/190812_21.png" style="width: 83%;" /></p>

<p>&nbsp;</p>
</div>
</div>
</div>
</div>
<div id="cell_48_91">
<div id="cell_91_92">
<div id="cell_92_93">
<div class="smartOutput htmlEditor float_clear"><p class="figwrap" style="text-align: center;"><img src="/image_bank/landing/효원힐링센터.PNG" style="width: 70%;" /></p>
</div>
</div>
</div>
</div>
<div id="cell_48_64">
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
