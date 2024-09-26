<?php
$wizsave_mode=1;
@include_once '/home/hosting_users/ilearning1/www/builder/lib/lib.php';
@include_once $ui_homepath.'/pages/menu_id.php';
$need_html5player=0;
$menu_id=3116;
$ui_loginpage='';
$connect=dbconn();
@access_right_chk(1, '', '');
$ui_og_arr=@print_board_title('자주하는 질문', '', '', '');
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
<link rel="stylesheet" href="/pages/auto_data/saved/page_3116.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43772.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43714.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43766.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43767.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43752.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43768.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43765.css?r=0311170221" type="text/css">
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
<body id="menu_group_162" class="menu_id_3116 menu_order_03">
<div id="page_container" class="float_clear"><div id="cell_0_1">
<div id="cell_1_7">
<span class="banner_text_43772"><a href="/pages/page_3073.php?">BizTech</a></span></div>
<div id="cell_1_13">
<?php
$module_id=43714;
$cell_id='cell_1_13';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/menu/hmenu_4.php';
?>
</div>
</div>
<div id="cell_0_15">
<div id="cell_15_16">
<ul id="hmenu_43766" class="roll_gnb" auto_sub="0" hmenu3="1">
<li num="1" style="width:33.33%;"><a href="/pages/page_3113.php" tabindex="1">회사소개</a></li>
<li num="2" style="width:33.33%;"><a href="/pages/page_3111.php" tabindex="4">제품안내</a></li>
<li num="3" class="on" style="width:33.33%;"><a href="/pages/page_3115.php" class="active" tabindex="5">고객센터</a></li>
</ul>
<div id="vmenu_43766" class="hmenu3vmenu">
<ul class="drop_gnb_v" num="1"><li id="vmenu_43766_3113" depth="2" num="0"><a href="/pages/page_3113.php" class="vmenu_depth_1" tabindex="2">CEO 인사말</a></li>
<li id="vmenu_43766_3114" depth="2" num="0"><a href="/pages/page_3114.php" class="vmenu_depth_1" tabindex="3">오시는 길</a></li>
</ul>
<ul class="drop_gnb_v" num="2"></ul>
<ul class="drop_gnb_v" num="3"><li id="vmenu_43766_3115" depth="2" num="0"><a href="/pages/page_3115.php" class="vmenu_depth_1" tabindex="6">고객의 소리</a></li>
<li id="vmenu_43766_3116" depth="2" num="0" class="on"><a href="/pages/page_3116.php" class="vmenu_depth_1 active" tabindex="7">자주하는 질문</a></li>
<li id="vmenu_43766_3117" depth="2" num="0"><a href="/pages/page_3117.php" class="vmenu_depth_1" tabindex="8">공지사항</a></li>
</ul></div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#vmenu_43766 ul ul').css('display','none');
	$('#vmenu_43766 a.active').next('ul').css('display','block');

	if(typeof(window.orientation)!== 'undefined') {
		$('#hmenu_43766 li').each(function() {
			//안드로이드에서 서브메뉴가 안보이고 바로 이동하는 현상때문에 서브메뉴가 있는경우 링크를 지웠다.
			if($('#vmenu_43766 .drop_gnb_v[num="'+$(this).attr('num')+'"]:has(li)').length>0) $(this).find('a').attr('href','#');
		});
	}
		$('#vmenu_43766 li').has('ul').find('>a').attr('href','#');
	$('#vmenu_43766 a[href="#"]').click(vmenu_toggle43766).focus(vmenu_toggle43766);

	function vmenu_toggle43766(event) {
		var $clicked_li=$(this).parent('li');
		var $tarr=$(this).parents('li');
		var $li_list=new Array();
		for(var i=0;i<$tarr.length;i++) $li_list[i]=new Array($($tarr[i]).attr('id'), $($tarr[i]).attr('depth'));
		$(this).parents('.drop_gnb_v').find('li').has('ul').each(function(index) {
			var depth=in_array($(this).attr('id'), $li_list);
			if(depth>0) {
				if(parseInt($clicked_li.attr('depth')) == depth) $(this).find('ul:first').slideDown(200);
						} else {
				$(this).find('ul:first').slideUp(200);
						}
		});
		return false;
	}
	
	$('<li class="noLava"><div class="menu_line"></div></li>').insertAfter($('#vmenu_43766 .drop_gnb_v>li[num]:not(:last-child)'));
});
</script>
</div>
<div id="cell_15_18">
<h2 id="locwrap_43767"><a href="/pages/page_3073.php?" class="lochm_43767">Home</a><span class="locsp_43767">></span><a href="/pages/page_3115.php" class="locmn_43767">고객센터</a><span class="locsp_43767">></span><span class="loccr_43767">자주하는 질문</span></h2></div>
<div id="cell_15_19">
<?php
$module_id=43752;
$cell_id='cell_15_19';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/board/board_12.php';
?>
</div>
</div>
<div id="cell_0_3">
<div id="cell_3_11">
<ul id="hmenu_43768" class="drop_gnb pulldown_menu float_clear" direct=""><li id="hmenu_43768_3129" depth="1" num="0"><a href="/pages/page_3073.php">HOME</a></li>
<li id="hmenu_43768_3130" depth="1" num="0"><a href="/pages/page_3113.php">회사소개</a></li>
<li id="hmenu_43768_3131" depth="1" num="0"><a href="/pages/page_3072.php">PC버전</a></li>
</ul>
</div>
<div id="cell_3_12">
<div class="smartOutput htmlEditor float_clear"><div style="text-align: center;"><span style="color: rgb(118, 123, 134); font-family: '맑은 고딕','malgun gothic','apple sd gothic neo',geneva,helvetica,dotum,sans-serif; font-size: 12px;">Copyrightⓒ Mycompany. All rights reserved.</span></div></div>
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
