<?php
$wizsave_mode=1;
@include_once '/home/hosting_users/ilearning1/www/builder/lib/lib.php';
@include_once $ui_homepath.'/pages/menu_id.php';
$need_html5player=0;
$menu_id=345;
$ui_loginpage='/pages/page_37.php';
$connect=dbconn();
@access_right_chk(1, '/pages/page_37.php', '');
$ui_og_arr=@print_board_title('개인정보취급방침', '', '', '');
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
<link rel="stylesheet" href="/pages/auto_data/saved/page_345.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1197.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_906.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1196.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1199.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1198.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_1006.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_903.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_996.css?r=0311170221" type="text/css">
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
<body id="menu_group_61" class="menu_id_345 menu_order_02">
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
<h2 id="locwrap_1006"><span class="loctt_1006"><img src="/image_bank/icon/dot/head_symbol_9.gif" class="vmiddle">&nbsp;</span><a href="/pages/page_295.php?" class="lochm_1006">Home</a><span class="locsp_1006">></span><span class="loccr_1006">개인정보취급방침</span></h2></div>
<h2 id="locwrap_903"><span class="loccr_903">개인정보취급방침</span></h2></div>
<div id="cell_120_157">
<div class="smartOutput float_clear"><p style="line-height: 160%;">&#39;오즈파이낸셜&#39;은 (이하 &#39;회사&#39;는)<br />
고객님의 개인정보를 중요시하며, &quot;정보통신망 이용촉진 및 정보보호&quot;에 관한 법률을 준수하고 있습니다.</p>

<p style="line-height: 160%;">회사는 개인정보취급방침을 통하여 고객님께서 제공하시는 개인정보가 어떠한 용도와 방식으로 이용되고 있으며, 개인정보보호를 위해 어떠한 조치가 취해지고 있는지 알려드립니다.</p>

<p style="line-height: 160%;"><br />
회사는 개인정보취급방침을 개정하는 경우 웹사이트 공지사항(또는 개별공지)을 통하여 공지할 것입니다.</p>

<p style="line-height: 160%;">&omicron; 본 방침은 : 2008 년 05 월 02 일 부터 시행됩니다.</p>

<p style="line-height: 160%;">■ 수집하는 개인정보 항목</p>

<p style="line-height: 160%;">회사는 회원가입, 상담, 서비스 신청 등등을 위해 아래와 같은 개인정보를 수집하고 있습니다.</p>

<p style="line-height: 160%;"><br />
&omicron; 수집항목 : 이름 , 로그인ID , 비밀번호 , 휴대전화번호 , 이메일 , 회사명 , 서비스 이용기록 , 쿠키 , 접속 IP 정보<br />
&omicron; 개인정보 수집방법 : 홈페이지(<a href="/"><u><font color="#0066cc">www.ozhome.co.kr</font></u></a>)</p>

<p style="line-height: 160%;">■ 개인정보의 수집 및 이용목적</p>

<p style="line-height: 160%;">회사는 수집한 개인정보를 다음의 목적을 위해 활용합니다..</p>

<p style="line-height: 160%;">&omicron; 서비스 제공에 관한 계약 이행 및 서비스 제공에 따른 요금정산 콘텐츠 제공 , 구매 및 요금 결제<br />
&omicron; 회원 관리 : 회원제 서비스 이용에 따른 본인확인</p>

<p style="line-height: 160%;">■ 개인정보의 보유 및 이용기간</p>

<p style="line-height: 160%;">회사는 개인정보 수집 및 이용목적이 달성된 후에는 예외 없이 해당 정보를 지체 없이 파기합니다.</p>

<p style="line-height: 160%;">■ 개인정보의 파기절차 및 방법</p>

<p style="line-height: 160%;">회사는 원칙적으로 개인정보 수집 및 이용목적이 달성된 후에는 해당 정보를 지체없이 파기합니다. 파기절차 및 방법은 다음과 같습니다.</p>

<p style="line-height: 160%;"><br />
&omicron; 파기절차</p>

<p style="line-height: 160%;">회원님이 회원가입 등을 위해 입력하신 정보는 회원탈퇴시 곧바로 데이타베이스 완전 삭제됩니다.</p>

<p style="line-height: 160%;"><br />
&omicron; 파기방법</p>

<p style="line-height: 160%;">- 전자적 파일형태로 저장된 개인정보는 기록을 재생할 수 없는 기술적 방법을 사용하여 삭제합니다.</p>

<p style="line-height: 160%;"><br />
■ 개인정보 제공</p>

<p style="line-height: 160%;">회사는 이용자의 개인정보를 원칙적으로 외부에 제공하지 않습니다. 다만, 아래의 경우에는 예외로 합니다.</p>

<p style="line-height: 160%;">- 이용자들이 사전에 동의한 경우</p>

<p style="line-height: 160%;">- 법령의 규정에 의거하거나, 수사 목적으로 법령에 정해진 절차와 방법에 따라 수사기관의 요구가 있는 경우</p>

<p style="line-height: 160%;">■ 수집한 개인정보의 위탁</p>

<p style="line-height: 160%;">회사는 고객님의 동의없이 고객님의 정보를 외부 업체에 위탁하지 않습니다. 향후 그러한 필요가 생길 경우, 위탁 대상자와 위탁 업무 내용에 대해 고객님에게 통지하고 필요한 경우 사전 동의를 받도록 하겠습니다.</p>

<p style="line-height: 160%;">■ 이용자 및 법정대리인의 권리와 그 행사방법</p>

<p style="line-height: 160%;">이용자 및 법정 대리인은 언제든지 등록되어 있는 자신 혹은 당해 만 14세 미만 아동의 개인정보를 조회하거나 수정할 수 있으며 가입해지를 요청할 수도 있습니 다.<br />
이용자 혹은 만 14세 미만 아동의 개인정보 조회?수정을 위해서는 &lsquo;개인정보변 경&rsquo;(또는 &lsquo;회원정보수정&rsquo; 등)을 가입해지(동의철회)를 위해서는 &ldquo;회원탈퇴&rdquo;를 클릭 하여 본인 확인 절차를 거치신 후 직접 열람, 정정 또는 탈퇴가 가능합니다.<br />
혹은 개인정보관리책임자에게 서면, 전화 또는 이메일로 연락하시면 지체없이 조 치하겠습니다.<br />
귀하가 개인정보의 오류에 대한 정정을 요청하신 경우에는 정정을 완료하기 전까 지 당해 개인정보를 이용 또는 제공하지 않습니다. 또한 잘못된 개인정보를 제3자 에게 이미 제공한 경우에는 정정 처리결과를 제3자에게 지체없이 통지하여 정정이 이루어지도록 하겠습니다.<br />
oo는 이용자 혹은 법정 대리인의 요청에 의해 해지 또는 삭제된 개인정보는 &ldquo;oo 가 수집하는 개인정보의 보유 및 이용기간&rdquo;에 명시된 바에 따라 처리하고 그 외의 용도로 열람 또는 이용할 수 없도록 처리하고 있습니다.</p>

<p style="line-height: 160%;">■ 개인정보 자동수집 장치의 설치, 운영 및 그 거부에 관한 사항</p>

<p style="line-height: 160%;">쿠키 등 인터넷 서비스 이용 시 자동 생성되는 개인정보를 수집하는 장치를 운영하지 않습니다.</p>

<p style="line-height: 160%;"><br />
■ 개인정보에 관한 민원서비스</p>

<p style="line-height: 160%;">회사는 고객의 개인정보를 보호하고 개인정보와 관련한 불만을 처리하기 위하여 아래와 같이 관련 부서 및 개인정보관리책임자를 지정하고 있습니다..</p>

<p style="line-height: 160%;">&nbsp;</p>

<p style="line-height: 160%;">개인정보관리책임자 성명 : 홍길동<br />
전화번호 : 02-555-5555<br />
이메일 : <a href="mailto:homepage@ozhome.co.kr"><u><font color="#0066cc">homepage@ozhome.co.kr</font></u></a></p>

<p style="line-height: 160%;">&nbsp;</p>

<p style="line-height: 160%;">귀하께서는 회사의 서비스를 이용하시며 발생하는 모든 개인정보보호 관련 민원을 개인정보관리책임자 혹은 담당부서로 신고하실 수 있습니다. 회사는 이용자들의 신고사항에 대해 신속하게 충분한 답변을 드릴 것입니다.</p>

<p style="line-height: 160%;">&nbsp;</p>

<p style="line-height: 160%;">기타 개인정보침해에 대한 신고나 상담이 필요하신 경우에는 아래 기관에 문의하시기 바랍니다.</p>

<p style="line-height: 160%;">1.개인분쟁조정위원회 (<a href="http://www.1336.or.kr/1336"><u><font color="#0066cc">www.1336.or.kr/1336</font></u></a>)</p>

<p style="line-height: 160%;">2.정보보호마크인증위원회 (<a href="http://www.eprivacy.or.kr/02-580-0533~4"><u><font color="#0066cc">www.eprivacy.or.kr/02-580-0533~4</font></u></a>)</p>

<p style="line-height: 160%;">3.대검찰청 인터넷범죄수사센터 (<a href="http://icic.sppo.go.kr/02-3480-3600"><u><font color="#0066cc">http://icic.sppo.go.kr/02-3480-3600</font></u></a>)</p>

<p style="line-height: 160%;">4.경찰청 사이버테러대응센터 (<a href="http://www.ctrc.go.kr/02-392-0330"><u><font color="#0066cc">www.ctrc.go.kr/02-392-0330</font></u></a>)</p>
</div>
</div>
</div>
<div id="cell_158_159" class="col-xs-12 col-sm-3">
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
