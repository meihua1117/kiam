<?php
$wizsave_mode=1;
@include_once '/home/hosting_users/ilearning1/www/builder/lib/lib.php';
@include_once $ui_homepath.'/pages/menu_id.php';
$need_html5player=0;
$menu_id=170;
$ui_loginpage='/pages/page_37.php';
$connect=dbconn();
@access_right_chk(1, '/pages/page_37.php', '');
$ui_og_arr=@print_board_title('생각설계로 책쓰기과정', '누구나 가능한 책쓰기 기술, 특허출원 언어인지기술, 알고리즘 사고, 책쓰기 기획부터 저술까지', '', 'I');
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
<meta name="keywords" content="#책쓰기, #책쓰기강의, #책쓰기강좌, #책쓰기혁신, #책쓰기모임, #작가, #작가되는법, #작가수업,#책쓰기코칭, #언어인지기술, #송조은, #출판기획,#책쓰는법,#언어코딩학습,#논문쓰기,#독서,#책읽기,#생각설계,#책쓰기학교">
<link rel="stylesheet" href="/cgi_bank/lib/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/style.css?r=1548830785" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/jquery-ui.css" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/page_170.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_392.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_393.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_426.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_399.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_400.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_415.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_404.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_411.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_405.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_408.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_410.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_409.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_403.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_407.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_412.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_402.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_396.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_416.css?r=0311170221" type="text/css">
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
<meta property="og:image" content="http://3key.co.kr/image/180313_02.png">
</head>
<body id="menu_group_1" class="menu_id_170 menu_order_03">
<div id="page_container" class="float_clear"><div id="cell_0_1">
<div id="cell_1_4">
<div id="cell_4_5" class="float_clear">
<div id="cell_5_7">
<div id="cell_7_9">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="대학원 정식 교과과정으로 채택  |  국제인지코치협회 인증 |  대한민국 출판문화예술 독서진흥 대상수상" src="/image/180306_02.png" /><!--<p style="line-height:250%;"><span style="font-family:나눔스퀘어;"><span style="font-size:20px;"><span style="color:#ffff00;"><strong>&nbsp; </strong></span><span style="color:#ffff00;"><strong>대학원 정식 교과정으로 채택</strong></span><span style="color:#ffffff;"><strong> | </strong></span><span style="color:#ffffff;"><strong>대한민국 출판문화예술</strong></span><span style="color:#ffff00;"><strong> 독서진흥 대상수상 </strong></span><span style="color:#ffffff;"><strong>|</strong></span><span style="color:#ffff00;"><strong> 국제인</strong><span style="color:#ffff00;"><strong><img alt="" src="/image/tropi02.png" style="float: left; width: 43px; height: 40px;" /></strong></span><strong>지코치협회 인증</strong></span></span></span></p>--></p>
</div>
</div>
</div>
</div>
</div>
<div id="cell_1_10">
<div id="cell_10_11">
<div id="cell_11_12">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="" src="/image/180208_11.png" /><!--<table align="center" border="0" cellpadding="5" cellspacing="1" style="background-color:#ffffff;border-spacing:1px;width:50%;">
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
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="" src="/image/180212_01.png" /></p>
</div>
</div>
</div>
</div>
<div id="cell_1_17">
<div id="cell_17_22">
<div id="cell_22_30">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="작가들도 모르는 비밀 책쓰기의 기초 이해와 생각설계기술을 활용하여  정교하고 창의적인 책쓰기 방법을 배우는과정입니다.  " src="/image/180309_08.png" /><!-- <p style="line-height: 200%; text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:17px;">&lt;책쓰기&gt; &lsquo;노하우&rsquo;를 넘어 &lsquo;책쓰기 기술&rsquo;이 있다!</span></span></p>

<p style="line-height: 200%; text-align: center;"><span style="font-family:_나눔스퀘어EB;"><span style="font-size:15px;"><span style="color:#d35400;"><strong>독서교육 25년! 5000회 이상 신화!</strong></span></span><br />
&ldquo;90% 나만 알고 싶은 강의&rdquo;<br />
&ldquo;80% 인문학 공식에 충격&rdquo;</span></p>--></p>
</div>
</div>
</div>
</div>
<div id="cell_1_18">
<div id="cell_18_23">
<div id="cell_23_31">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="자료수집부터 기획, 저술, 그리고 출간까지 모든 과정에는 정보를 융합설계하는 알고리즘 사고가 필요합니다. 세계 최초 특허 출원된 &lt;언어인지기술&gt;을 활용한 알고리즘사고로 정보를 융합설계하는 기술을 배우는 과정입니다." src="/image/180309_03.png" /><!-- <table align="center" border="0" cellpadding="5" cellspacing="10" style="background-color:#009933;border-radius:5px;border-spacing:10px;width:40%;">
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
<div id="cell_74_75">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="" src="/image/180212_07.png" /></p>
</div>
</div>
</div>
</div>
<div id="cell_1_28">
<div id="cell_28_38">
<div id="cell_38_39">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="" src="/image/180209_13.png" /><!-- <table align="center" border="0" cellpadding="10" cellspacing="1" style="background-color:#009933;border-radius:5px;border-spacing:1px;width:30%;">
	<tbody>
		<tr>
			<td style="background-color: #009933; padding: 10px; text-align: center;"><strong><span style="font-family:_포천오성과한음R;"><span style="color:#ffffff;"><span style="font-size:18px;">과정 커리큘럼</span></span></span></strong></td>
		</tr>
	</tbody>
</table>

<p>&nbsp;</p>

<p style="text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="color:#666633;"><span style="font-size:14px;">내 책도 쓰고 저술코치도 된다! 인생의 2막을 시작하세요!</span></span></span></p>
--></p>

<table align="center" border="0" cellpadding="15" cellspacing="3" style="background-color:#99cc99;border-spacing:3px;width:75%;">
	<thead>
		<tr>
			<th scope="col" style="background-color: rgb(155, 187, 89); padding: 15px;"><span style="font-size:22px;"><span style="color:#ffffff;">주차</span></span></th>
			<th scope="col" style="background-color: rgb(155, 187, 89); padding: 15px;"><span style="font-size:22px;"><span style="color:#ffffff;">과정내용</span></span></th>
			<th scope="col" style="background-color: rgb(155, 187, 89); padding: 15px;"><span style="font-size:22px;"><span style="color:#ffffff;">세부내용</span></span></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">1주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">저술의 가치</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">저술의 문제와 가치 이해 코칭</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p style="text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">2주차</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">저술컨셉 설정 코칭</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">저술개요 설정 및 목차 설정 방법 코칭</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p style="text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">3주차</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">저술자료 수집 코칭</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">문제인지기술, 정보수집기술&nbsp; 코칭</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p style="text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">4주차</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">생각설계기술 코칭</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">목차융합설계도 작성 코칭</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">5주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">생각설계기술 코칭</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">정보요약과 핵심파악으로 설계도 작성코칭</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p style="text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">6주차</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">생각설계기술 코칭</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">정보관계 &nbsp;설계도 작성법 코칭</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">7주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">생각설계기술 코칭</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">정보설계로 정보융합 설계도 작성법 코칭</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">8주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">생각설계기술 코칭</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">정보융합 설계도 완성 방법 코칭</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">9주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">설계오류기술 코칭</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">생각설계 오류 찾아 해결 방법 코칭</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">10주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">원고작성기술 코칭</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">정보융합 설계도로 글쓰기 방법 코칭</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">11주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">문장단락쓰기 코칭</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">문장과 단락쓰기의 기술적 접근 코칭</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">12주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">소제목글쓰기 코칭</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">꼭지글의 구성형태 포맷 글쓰기 코칭</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">13주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">원고퇴고코칭</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">문장의 흐름과 수사 퇴고 방법 코칭</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">14주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">출간제안코칭</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">제안포맷활용 전략 코칭</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 15px; text-align: center;"><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">15주차</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">학기 종강파티</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-size:22px;"><span style="font-family:_나눔스퀘어B;">출간결과 발표</span></span></p>
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
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="" src="/image/180209_11.png" /></p>
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
<div id="cell_27_47">
<div id="cell_47_51">
<div id="cell_51_62">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><img alt="저술설계도가 그대로 글쓰기로 연결되는 정보융합설계기술" src="/image/180309_05.png" /></p>
</div>
</div>
</div>
</div>
<div id="cell_27_48">
<div id="cell_48_52">
<div id="cell_52_63">
<div class="smartOutput htmlEditor float_clear"><p><span style="font-family:_포천오성과한음R;"></span></p>

<p style="text-align: center;"><img alt="한번 습득하면 일상생활에서 전문영역까지 어디서든 활용할 수 있는 언어인지기술 사고력 창의력 개발 한번에 해결가능" src="/image/180309_06.png" /></p>
</div>
</div>
</div>
<div id="cell_48_55">
<div id="cell_55_66" class="float_clear">
<div id="cell_66_69">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><span style="color:#ffffff;"><span style="font-family:_오성과한음B;"><strong><span style="font-size:28px;">강사</span><span style="font-size:36px;"> 송 조 은</span></strong></span></span></p>

<p style="text-align: center;">&nbsp;</p>

<p style="text-align: center;">&nbsp;</p>

<p style="text-align: center;"><img alt="" src="/image/송조은이미지(광진구청1).jpg" style="height: 334px; width: 250px; border-radius: 5px;" /></p>

<p style="text-align: center;"><span style="color:#66ff33;"><span style="font-family:_나눔스퀘어EB;"><span style="font-size:36px;"><b>| 온리원역량 전문가</b>&nbsp;<span style="color:#66ff33;"><span style="font-family:_나눔스퀘어EB;"><span style="font-size:36px;"><b>| </b></span></span></span></span></span></span></p>

<p style="line-height:250%;">&nbsp;</p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>현) 대한신학대학원 인지코칭학 교수(언어인지기술 개발)</b></span></span></p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>현) (사)국민독서인지문화원 이사장&nbsp;</b></span></span></p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>현) 국제인지코칭학회 이사장</b></span></span></p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>현) 국제인지코치협회 이사장&nbsp;</b></span></span></p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>현) <span style="font-family:kopubbatang;">온리원독서클럽 대표</span></b></span></span></p>

<p style="line-height:250%;"><span style="font-family:kopubbatang;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>현) 온리원연구소 소장</b>&nbsp;</span></span></span></p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>전) 꿈의학교 설립 및 교장 역임&nbsp;</b></span></span></p>

<p style="line-height:250%;"><span style="color:#ffffff;"><span style="font-size:22px;"><b>King David University Religious education 명예박사</b>&nbsp;</span></span></p>

<p style="line-height:250%;">&nbsp;</p>

<p><span style="color:#ffffff;"></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:24px;">핵심역량개발전문가, 인재양성전문가, 언어인지기술 개발 원페이지요약기술 개발, 원페이지북 개발, 온리원리딩 개발 인지인성기술, 자주경영기술, 정보활용기술 외 다수 개발&nbsp;</span></span></span></p>

<p>&nbsp;</p>

<p><span style="color:#ffffff;"></span></p>

<p><span style="color:#ffffff;"><span style="font-size:20px;"><span style="font-family:_나눔스퀘어L;"></span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;">□&nbsp;대한민국 출판문화예술 대상 독서진흥 대상수상(2014)&nbsp;</span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;">□</span></span></span>&nbsp;대한민국공정사회발전대상 수상(2014)&nbsp;</span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;">□&nbsp;</span></span></span>교육부 표창장 수여(2017)&nbsp;</span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;">□</span></span></span>&nbsp;인천강화교육지원청 자유학기제 진로탐색 및 인문사회 부분- 온리원 나의 자서전, 나의 행복메이커 교재 및 수업과정 개발(2015, NEIS탑재)&nbsp;</span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;">□&nbsp;</span></span></span>독서법, 학습법 강의 3000회 이상 진행&nbsp;</span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;">□&nbsp;</span></span></span>강화교육청, 제주교육청 외 다수 교육청 교장/교사대상 연수</span></span></span></p>

<p><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;"><span style="font-family:_다음;"><span style="color:#ffffff;"><span style="font-size:20px;">□&nbsp;</span></span></span>(저서) 꿈의학습혁명 | 독서쇼크 | 온리원노트기술 | 온리원정보기억법 온리원파워 | 한국어인지기술(대학원교재)</span></span></span></p>

<p style="text-align: center;">&nbsp;</p>

<div class="bootgrid">&nbsp;</div>
</div>
</div>
</div>
</div>
<div id="cell_48_65">
<div id="cell_65_67">
<div id="cell_67_71">
<div class="smartOutput htmlEditor float_clear"><p style="text-align: center;"><strong><span style="font-family:_오성과한음B;"><span style="font-size:36px;">과정 참여안내</span></span></strong></p>

<p style="text-align: center;">&nbsp;</p>

<table align="center" border="0" cellpadding="15" cellspacing="3" style="background-color:#99cc99;border-spacing:3px;width:70%;">
	<tbody>
		<tr>
			<td style="background-color: rgb(204, 255, 204); padding: 15px; text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">개강일</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">2018년 3월 3일 (매주 토요일)</span></span></p>

			<p><span style="line-height:0;"><span style="font-size:18px;"><span style="font-family:_나눔스퀘어B;">* 전문가/영상 과정 수시모집</span>&nbsp;</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: #ccffcc; padding: 15px;">
			<p style="text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">과정선택</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">전문가과정(비학위), 석사과정(학위) 선택</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: #ccffcc; padding: 15px;">
			<p style="text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">수업장소</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">* 온라인/현장 동시 진행</span></span></p>

			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">1) 현장참여 : 석수역 1호선 대학원 강의실</span></span></p>

			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">2) 온라인 참여 : 실시간 참여, 영상참여 모두 가능</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: #ccffcc; padding: 15px;">
			<p style="text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">수업비용</span></span></p>
			</td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">개인코칭, 출판코칭, 마케팅 코칭은 별도 진행</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(204, 255, 204); padding: 15px; text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">상담문의</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 15px;">
			<p><span style="font-family:_나눔스퀘어B;"><span style="font-size:22px;">070-4477-6631</span></span></p>
			</td>
		</tr>
		<tr>
			<td style="background-color: #ccffcc; padding: 15px;">
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
include_once '/home/hosting_users/ilearning1/www/pages/auto_data/saved/module_396.inc';
?>
</div>
</div>
<div id="cell_72_82">
<div id="cell_82_83">
<?php
$module_id=416;
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
