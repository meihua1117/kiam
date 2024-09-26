<?php
$wizsave_mode=1;
@include_once '/home/hosting_users/ilearning1/www/builder/lib/lib.php';
@include_once $ui_homepath.'/pages/menu_id.php';
$need_html5player=0;
$menu_id=143;
$ui_loginpage='';
$connect=dbconn();
@access_right_chk(1, '', '');
$ui_og_arr=@print_board_title('회원가입', '', '', '');
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
<link rel="stylesheet" href="/pages/auto_data/saved/page_143.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_271.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_353.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_265.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_266.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_206.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_346.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_267.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_264.css?r=0311170221" type="text/css">
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
<link rel="stylesheet" href="/image_bank/dummy/fskin_170/style.css" type="text/css">
</head>
<body id="menu_group_26" class="menu_id_143 menu_order_02">
<div id="page_container" class="float_clear"><div id="cell_0_1">
<div id="cell_1_7">
<span class="banner_text_271"><a href="/pages/page_2.php?">BizTech</a></span></div>
<div id="cell_1_21">
<style type="text/css">#cell_15_16 {display:none;}</style><img id="toggle1_347" src="/image_bank/dummy/fskin_170/hamburger_on.gif" alt=""><script type="text/javascript">
jQuery(document).ready(function($) {
var toggle_347='none';
	if(toggle_347=='none') {
		$('#cell_15_16').hide();
		$('#toggle1_347').attr('src','/image_bank/dummy/fskin_170/hamburger_off.gif');
	}
	$('#toggle1_347').css('cursor','pointer').click(function(e) {
		$('#cell_15_16').toggle('slide',{direction:'up'},100,function() {
			$(this).clearQueue();
			if($(this).css('display')=='none') {
				$('#toggle1_347').attr('src','/image_bank/dummy/fskin_170/hamburger_off.gif');
			} else {
				$('#toggle1_347').attr('src','/image_bank/dummy/fskin_170/hamburger_on.gif');
			}
			document.cookie="toggle_347="+$(this).css('display')+"; path=/;";
		});
	});
});
</script>
</div>
</div>
<div id="cell_0_15">
<div id="cell_15_14">
<ul id="hmenu_353" class="drop_gnb pulldown_menu float_clear" direct=""><li id="hmenu_353_112" depth="1" num="0" style="width:25%;"><a href="/pages/page_132.php">회사소개</a></li>
<li id="hmenu_353_113" depth="1" num="0" style="width:25%;"><a href="/pages/page_113.php">제품안내</a></li>
<li id="hmenu_353_114" depth="1" num="0" style="width:25%;"><a href="/pages/page_135.php">채용정보</a></li>
<li id="hmenu_353_117" depth="1" num="0" style="width:25%;"><a href="/pages/page_137.php">고객센터</a></li>
</ul>
</div>
<div id="cell_15_16">
<ul id="vmenu_265" class="drop_gnb_v">
<li id="vmenu_265_112" depth="1" num="0"><a href="/pages/page_132.php" class="vmenu_depth_1">회사소개</a>
<ul>
<li id="vmenu_265_132" depth="2"><a href="/pages/page_132.php" class="vmenu_depth_2">CEO 인사말</a></li>
<li id="vmenu_265_133" depth="2"><a href="/pages/page_133.php" class="vmenu_depth_2">회사연혁</a></li>
<li id="vmenu_265_134" depth="2"><a href="/pages/page_134.php" class="vmenu_depth_2">오시는길</a></li>
</ul>
</li>
<li id="vmenu_265_113" depth="1" num="0"><a href="/pages/page_113.php" class="vmenu_depth_1">제품안내</a></li>
<li id="vmenu_265_114" depth="1" num="0"><a href="/pages/page_135.php" class="vmenu_depth_1">채용정보</a>
<ul>
<li id="vmenu_265_135" depth="2"><a href="/pages/page_135.php" class="vmenu_depth_2">채용안내</a></li>
<li id="vmenu_265_136" depth="2"><a href="/pages/page_136.php" class="vmenu_depth_2">인사복지제도</a></li>
</ul>
</li>
<li id="vmenu_265_117" depth="1" num="0"><a href="/pages/page_137.php" class="vmenu_depth_1">고객센터</a>
<ul>
<li id="vmenu_265_137" depth="2"><a href="/pages/page_137.php" class="vmenu_depth_2">공지사항</a></li>
<li id="vmenu_265_138" depth="2"><a href="/pages/page_138.php" class="vmenu_depth_2">자주하는질문</a></li>
<li id="vmenu_265_139" depth="2"><a href="/pages/page_139.php" class="vmenu_depth_2">질문과답변</a></li>
<li id="vmenu_265_140" depth="2"><a href="/pages/page_140.php" class="vmenu_depth_2">고객의소리</a></li>
</ul>
</li>
</ul>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#vmenu_265 ul').css('display','none');
	$('#vmenu_265 a.active').next('ul').css('display','block');

		$('#vmenu_265 li').has('ul').find('>a').attr('href','#');
	$('#vmenu_265 a[href="#"]').click(vmenu_toggle265).focus(vmenu_toggle265);

	function vmenu_toggle265(event) {
		var $clicked_li=$(this).parent('li');
		var $tarr=$(this).parents('li');
		var $li_list=new Array();
		for(var i=0;i<$tarr.length;i++) $li_list[i]=new Array($($tarr[i]).attr('id'), $($tarr[i]).attr('depth'));
		$('#vmenu_265 li').has('ul').each(function(index) {
			var depth=in_array($(this).attr('id'), $li_list);
			if(depth>0) {
				if(parseInt($clicked_li.attr('depth')) == depth) $(this).find('ul:first').slideDown(200);
						} else {
				$(this).find('ul:first').slideUp(200);
						}
		});
		return false;
	}
	
	$('<li class="noLava"><div class="menu_line"></div></li>').insertBefore($('#vmenu_265>li[num]:gt(0)'));

});
</script>
</div>
<div id="cell_15_18">
<h2 id="locwrap_266"><a href="/pages/page_75.php?" class="lochm_266">Home</a><span class="locsp_266">></span><span class="loccr_266">회원가입</span></h2></div>
<div id="cell_15_19">
<?php
$module_id=206;
$cell_id='cell_15_19';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/member/regist_1.php';
?>
</div>
</div>
<div id="cell_0_3">
<div id="cell_3_20">
<?php
$module_id=346;
$cell_id='cell_3_20';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/menu/hmenu_4.php';
?>
</div>
<div id="cell_3_11">
<ul id="hmenu_267" class="drop_gnb pulldown_menu float_clear" direct=""><li id="hmenu_267_120" depth="1" num="0"><a href="/pages/page_2.php">HOME</a></li>
<li id="hmenu_267_122" depth="1" num="0"><a href="/pages/page_132.php">회사소개</a></li>
<li id="hmenu_267_121" depth="1" num="0"><a href="/pages/page_1.php">PC버전</a></li>
</ul>
</div>
<div id="cell_3_12">
<div class="smartOutput htmlEditor float_clear"><div style="text-align: center;"><span style="color:#D3D3D3;"><span style="font-family: '맑은 고딕', 'malgun gothic', 'apple sd gothic neo', geneva, helvetica, dotum, sans-serif; font-size: 12px;">Copyrightⓒ Mycompany. All rights reserved.</span></span></div>
</div>
</div>
</div>
</div>
<?php @include_once '/home/hosting_users/ilearning1/www/builder/lib/common_footer.php';?>
<?php
if($need_html5player) $global_script_arr[]='/cgi_bank/lib/MediaElementPlayer/mediaelement-and-player.min.js';
$global_script_arr[]='/cgi_bank/lib/jquery.carouFredSel.js';
$global_script_arr[]='/cgi_bank/lib/jquery.touchSwipe.min.js';
$global_script_arr[]='/cgi_bank/lib/jquery.ozfontresize.js';
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
