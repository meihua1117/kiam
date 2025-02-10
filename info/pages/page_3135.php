<?php
$wizsave_mode=1;
@include_once '/home/hosting_users/ilearning1/www/builder/lib/lib.php';
@include_once $ui_homepath.'/pages/menu_id.php';
$need_html5player=0;
$menu_id=3135;
$ui_loginpage='/pages/page_37.php';
$connect=dbconn();
@access_right_chk(1, '/pages/page_37.php', '');
$ui_og_arr=@print_board_title('온리원문자 매뉴얼', '', '', '');
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
<?$oz_respond_cnt=array(5);?><script type="text/javascript">if(document.cookie.search(/ozDesktopMode=1/)==-1) document.write('<meta name="viewport" content="width=device-width, initial-scale=1">');</script>
<meta http-equiv="imagetoolbar" content="no">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="/cgi_bank/lib/bootstrap.min.css" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/style.css?r=1548830785" type="text/css">
<link rel="stylesheet" href="/cgi_bank/lib/jquery-ui.css" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/page_3135.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43878.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43889.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43880.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43879.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43828.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43829.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43827.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43885.css?r=0311170221" type="text/css">
<link rel="stylesheet" href="/pages/auto_data/saved/module_43746.css?r=0311170221" type="text/css">
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
<body id="menu_group_155" class="menu_id_3135 menu_order_02">
<div id="page_container" class="float_clear"><p class="skiptolink"><a href="#cell_124_131" tabindex="1">본문 바로가기</a></p><div id="cell_0_133">
<div id="cell_133_118">
<div id="navbar_canvas43878" onclick="close_side_navbar43878()"></div><input id="main-menu-state43878" type="checkbox" />
<nav id="mainnav_43878" class="oz_navbar float_clear" role="navigation">
	<div class="cell_header float_clear">
		<label id="mobile_btn43878" onclick="open_side_navbar43878()"><span class="main-menu-btn-icon line1"></span><span class="main-menu-btn-icon line2"></span><span class="main-menu-btn-icon line3"></span></label>
		<div class="cell_logo"><h1 class="banner_h1_43878_1 smartOutput htmlEditor"><a href="/pages/page_3094.php?"><img class="banner_img_43878_1" src="/image_bank/dummy/fskin_178/logo_200221.png"  alt="비즈테크"></a></h1></div>	</div>
	<div class="toggle_box">	<div class="cell_menus float_clear">
					<div class="side_header float_clear">
						<img src="/image_bank/dummy/fskin_178/logo.png" class="mlogo">
						<a href="#" class="closebtn" onclick="close_side_navbar43878();return false;">&times;</a>
			</div>
				<div class="cell_tmenu"><?php
$module_id=43878;
$is_navbar_module=1;
$idx=3;
$tconfig=array (
  'group_id' => '7',
  'depth' => '1',
  'right_chk' => '1',
  'hide_level' => '2',
  'show_mbox' => '1',
  'exit_page' => '/pages/page_1.php',
  'info_page' => '/pages/page_4.php?',
  'direct' => '',
  'text_mode' => 't',
  'align' => 'left',
  'theme' => '',
  'font_c' => '#666666',
  'font_f' => '\'Nanum Gothic\',NanumGothic, 나눔고딕, \'Malgun Gothic\', \'맑은 고딕\'',
  'font_s' => '13',
  'font_fs' => 'font-weight:normal;font-style:normal;',
  'font_shadow' => '',
  'font_sd1x' => '0',
  'font_sd1y' => '1',
  'font_sd1b' => '0',
  'font_sd1c' => '#dddddd',
  'fonton_c' => '#000000',
  'fonton_f' => '\'Nanum Gothic\',NanumGothic, 나눔고딕, \'Malgun Gothic\', \'맑은 고딕\'',
  'fonton_s' => '13',
  'fonton_fs' => 'font-weight:normal;font-style:normal;',
  'fonton_shadow' => '',
  'fonton_sd1x' => '0',
  'fonton_sd1y' => '1',
  'fonton_sd1b' => '0',
  'fonton_sd1c' => '#dddddd',
  'pad_t' => '0',
  'pad_b' => '0',
  'pad_l' => '0',
  'pad_r' => '0',
  'padding_t' => '3',
  'padding_b' => '3',
  'padding_l' => '3',
  'sl1' => '#bbbbbb',
  'sl2' => '',
  'sbg' => '',
  'slm' => '4',
  'sltm' => '11',
  'slh' => '10',
  'bg' => '',
  'bg_op' => '',
  'border_ts' => 'none',
  'border_tw' => '1',
  'border_tc' => '',
  'border_bs' => 'none',
  'border_bw' => '1',
  'border_bc' => '',
  'border_ls' => 'none',
  'border_lw' => '1',
  'border_lc' => '',
  'border_rs' => 'none',
  'border_rw' => '1',
  'border_rc' => '',
  'round_lt' => '0',
  'round_rt' => '0',
  'round_lb' => '0',
  'round_rb' => '0',
  'grad_type' => '0',
  'grad_col' => '',
  'offbg' => '',
  'offbg_op' => '',
  'offbgi' => '',
  'hbg' => '',
  'hbg_op' => '',
  'hbgi' => '',
  'hmargin_t' => '3',
  'hmargin_b' => '2',
  'offborder_ts' => 'none',
  'offborder_tw' => '',
  'offborder_tc' => '',
  'offborder_bs' => 'none',
  'offborder_bw' => '',
  'offborder_bc' => '',
  'offborder_ls' => 'none',
  'offborder_lw' => '',
  'offborder_lc' => '',
  'offborder_rs' => 'none',
  'offborder_rw' => '',
  'offborder_rc' => '',
  'hround_lt' => '3',
  'hround_rt' => '3',
  'hround_lb' => '3',
  'hround_rb' => '3',
  'offgrad_col' => '',
  'offgrad_col2' => '',
  'hborder_ts' => 'none',
  'hborder_tw' => '1',
  'hborder_tc' => '',
  'hborder_bs' => 'none',
  'hborder_bw' => '1',
  'hborder_bc' => '',
  'hborder_ls' => 'none',
  'hborder_lw' => '1',
  'hborder_lc' => '',
  'hborder_rs' => 'none',
  'hborder_rw' => '1',
  'hborder_rc' => '',
  'hgrad_col' => '',
  'hgrad_col2' => '',
  'use_lava' => '',
  'pop_login' => '1',
  'pi_use' => '1',
  'pi_size' => '30',
  'pi_r' => '30',
  'pi_before' => '/image_bank/thumbnail/thumb_guest.gif',
  'pi_after' => '/image_bank/thumbnail/thumb_guest.gif',
  'pi_bs' => 'solid',
  'pi_bw' => '1',
  'pi_bc' => '#bbbbbb',
  'pi_align' => 'right',
  'depth_2nd' => '1',
  'font2_c' => '#555555',
  'font2_f' => '\'Nanum Gothic\',NanumGothic, 나눔고딕, \'Malgun Gothic\', \'맑은 고딕\'',
  'font2_s' => '12',
  'font2_fs' => 'font-weight:normal;font-style:normal;',
  'font2_shadow' => '',
  'font2_sd1x' => '0',
  'font2_sd1y' => '1',
  'font2_sd1b' => '0',
  'font2_sd1c' => '#dddddd',
  'bg2off' => '',
  'bg2off_op' => '',
  'bg2offi' => '',
  'offborder2_ts' => 'none',
  'offborder2_tw' => '',
  'offborder2_tc' => '',
  'offborder2_bs' => 'none',
  'offborder2_bw' => '',
  'offborder2_bc' => '',
  'offborder2_ls' => 'none',
  'offborder2_lw' => '',
  'offborder2_lc' => '',
  'offborder2_rs' => 'none',
  'offborder2_rw' => '',
  'offborder2_rc' => '',
  'hround2_lt' => '',
  'hround2_rt' => '',
  'hround2_lb' => '',
  'hround2_rb' => '',
  'bg2' => '#ffffff',
  'opacity2' => '',
  'bg2i' => '',
  'font2on_c' => '#555555',
  'font2on_f' => '\'Nanum Gothic\',NanumGothic, 나눔고딕, \'Malgun Gothic\', \'맑은 고딕\'',
  'font2on_s' => '12',
  'font2on_fs' => 'font-weight:normal;font-style:normal;',
  'font2on_shadow' => '',
  'font2on_sd1x' => '0',
  'font2on_sd1y' => '1',
  'font2on_sd1b' => '0',
  'font2on_sd1c' => '#dddddd',
  'bg2on' => '#d4ebb3',
  'bg2on_op' => '',
  'bg2oni' => '',
  'onborder2_ts' => 'none',
  'onborder2_tw' => '',
  'onborder2_tc' => '',
  'onborder2_bs' => 'none',
  'onborder2_bw' => '',
  'onborder2_bc' => '',
  'onborder2_ls' => 'none',
  'onborder2_lw' => '',
  'onborder2_lc' => '',
  'onborder2_rs' => 'none',
  'onborder2_rw' => '',
  'onborder2_rc' => '',
  'width2' => '150',
  'align2' => 'left',
  'align2b' => 'left',
  'pad2b_t' => '0',
  'pad2b_b' => '0',
  'pad2b_l' => '0',
  'pad2b_r' => '0',
  'mg2b_l' => '',
  'mg2b_r' => '',
  'pad2_t' => '5',
  'pad2_b' => '5',
  'pad2_l' => '5',
  'pad2_r' => '5',
  'arrow' => '/image_bank/icon/dot/menu_arrow_black.gif',
  'line_s' => 'none',
  'line_w' => '1',
  'line_c' => '#dddddd',
  'border2_ts' => 'solid',
  'border2_tw' => '1',
  'border2_tc' => '#87bc43',
  'border2_bs' => 'solid',
  'border2_bw' => '1',
  'border2_bc' => '#87bc43',
  'border2_ls' => 'solid',
  'border2_lw' => '1',
  'border2_lc' => '#87bc43',
  'border2_rs' => 'solid',
  'border2_rw' => '1',
  'border2_rc' => '#87bc43',
  'round2_lt' => '0',
  'round2_rt' => '0',
  'round2_lb' => '0',
  'round2_rb' => '0',
  'shadow_len' => '0',
  'shadow_col' => '',
  'use_img' => '',
);
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/menu/hmenu_4.php';
$is_navbar_module=0;
?>
</div>		<div class="cell_gnb"><ul id="hmenu_43878_2" class="smartmenu smartmenu43878_2"><li id="hmenu_43878_2_3093" depth="1"><a href="/pages/page_3094.php">온리원셀링홈</a>
<ul>
<li id="hmenu_43878_2_3095" depth="2"><a href="/pages/page_3095.php">회사연혁</a></li>
<li id="hmenu_43878_2_3127" depth="2"><a href="/pages/page_3127.php">조직도</a></li>
<li id="hmenu_43878_2_3096" depth="2"><a href="/pages/page_3096.php">오시는 길</a></li>
<li id="hmenu_43878_2_3099" depth="2"><a href="/pages/page_3099.php">고객의 소리</a></li>
<li id="hmenu_43878_2_3097" depth="2"><a href="/pages/page_3097.php">제품소개</a></li>
<li id="hmenu_43878_2_3128" depth="2"><a href="/pages/page_3128.php">자료실</a></li>
<li id="hmenu_43878_2_3102" depth="2"><a href="/pages/page_3102.php">채용안내</a></li>
<li id="hmenu_43878_2_3103" depth="2"><a href="/pages/page_3103.php">인사/복지제도</a></li>
<li id="hmenu_43878_2_3105" depth="2"><a href="/pages/page_3105.php">공지사항</a></li>
</ul>
</li>
<li id="hmenu_43878_2_3098" depth="1"><a href="/pages/page_3094.php" class="current">이용안내</a>
<ul>
<li id="hmenu_43878_2_3094" depth="2"><a href="/pages/page_3094.php">처음오셨나요?</a></li>
<li id="hmenu_43878_2_3133" depth="2"><a href="/pages/page_3133.php">아이엠 매뉴얼</a></li>
<li id="hmenu_43878_2_3134" depth="2"><a href="/pages/page_3134.php">온리원콜백 매뉴얼</a></li>
<li id="hmenu_43878_2_3135" depth="2"><a href="/pages/page_3135.php" class="current">온리원문자 매뉴얼</a></li>
<li id="hmenu_43878_2_3136" depth="2"><a href="/pages/page_3136.php">온리원디버 매뉴얼</a></li>
<li id="hmenu_43878_2_3137" depth="2"><a href="/pages/page_3137.php">메시지카피 매뉴얼</a></li>
<li id="hmenu_43878_2_3138" depth="2"><a href="/pages/page_3138.php">원퍼널문자 매뉴얼</a></li>
<li id="hmenu_43878_2_3100" depth="2"><a href="/pages/page_3100.php">자주하는 질문</a></li>
</ul>
</li>
<li id="hmenu_43878_2_3106" depth="1"><a href="/pages/page_3106.php">1:1상담</a></li>
<li id="hmenu_43878_2_3139" depth="1"><a href="http://pf.kakao.com/_jVafC/chat" target="_blank">카카오상담</a></li>
</ul>
<script type="text/javascript">
$(function() {
	$('#hmenu_43878_2').smartmenus({
				mainMenuSubOffsetX: -1,
		mainMenuSubOffsetY: 4,
		subMenusSubOffsetX: 6,
		subMenusSubOffsetY: -6
		}).find('a[href*="#"]').click(function() {
		$('#mobile_btn43878, #mainnav_43878 .side_header .closebtn').trigger('click');
	});

	});
</script>
</div>	</div>
	</div></nav>
<script type="text/javascript">
var navbar_pc_mode=1;
	function open_side_navbar43878() {
		$('#navbar_canvas43878').css({width:'100%',opacity:0.5});
		$('#mainnav_43878 .toggle_box').css('left',0);
			}
	function close_side_navbar43878() {
		$('#mainnav_43878 .toggle_box').css('left','-305px');
		$('#navbar_canvas43878').css({width:'0',opacity:0});
			}
$(function() {
		if($('nav.oz_navbar').length>1) {
		$('#mainnav_43878').parents('div:not([id="page_container"])').each(function() {
			if(parseInt($(this).css('z-index')) < -42838) $(this).css('z-index',-42838);
		});
	}
	repos_movable43878();
});

jQuery(window).resize(repos_movable43878);
function repos_movable43878() {
	var width=$(window).width();
	var pos="";
	if(width < 960 && navbar_pc_mode==1) pos="mobile_pos";
	else if(width >= 960 && navbar_pc_mode!=1) pos="pc_pos";
	if(pos!="") {
		$('#mainnav_43878 .movable').each(function() {
			var tarr=$(this).attr(pos).split(':');
			if(tarr[1]=='before') {
				$('#mainnav_43878 .'+tarr[0]).before($(this));
			} else {
				$('#mainnav_43878 .'+tarr[0]).after($(this));
			}
		});
		navbar_pc_mode=(pos=="mobile_pos")?0:1;
	}
}
</script>
</div>
</div>
<div id="cell_0_120" class="float_clear row">
<div id="cell_120_124" class="col-xs-12 col-sm-9  row">
<div id="cell_124_131">
<article><?php
$module_id=43889;
$cell_id='cell_124_131';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/board/board_4.php';
?>
</article></div>
<div id="cell_124_130" class="row">
<div id="cell_130_154">
<h2 id="locwrap_43880"><span class="loccr_43880">온리원문자 매뉴얼</span></h2></div>
<div id="cell_130_152" class="hidden-xs">
<h2 id="locwrap_43879"><a href="/pages/page_3073.php?" class="lochm_43879">Home</a><span class="locsp_43879">></span><a href="/pages/page_3094.php" class="locmn_43879">이용안내</a><span class="locsp_43879">></span><span class="loccr_43879">온리원문자 매뉴얼</span></h2></div>
</div>
<div id="cell_124_153" class="hidden-xs hidden-sm">
</div>
</div>
<div id="cell_120_123" class="col-xs-12 col-sm-3  row">
<div id="cell_123_128" class="hidden-xs">
<h2 id="vmenu_43828_head">이용안내</h2>
<ul id="vmenu_43828" class="drop_gnb_v">
<li id="vmenu_43828_3094" depth="2" num="0"><a href="/pages/page_3094.php" class="vmenu_depth_1">처음오셨나요?</a></li>
<li id="vmenu_43828_3133" depth="2" num="0"><a href="/pages/page_3133.php" class="vmenu_depth_1">아이엠 매뉴얼</a></li>
<li id="vmenu_43828_3134" depth="2" num="0"><a href="/pages/page_3134.php" class="vmenu_depth_1">온리원콜백 매뉴얼</a></li>
<li id="vmenu_43828_3135" depth="2" num="0" class="on"><a href="/pages/page_3135.php" class="vmenu_depth_1 active">온리원문자 매뉴얼</a></li>
<li id="vmenu_43828_3136" depth="2" num="0"><a href="/pages/page_3136.php" class="vmenu_depth_1">온리원디버 매뉴얼</a></li>
<li id="vmenu_43828_3137" depth="2" num="0"><a href="/pages/page_3137.php" class="vmenu_depth_1">메시지카피 매뉴얼</a></li>
<li id="vmenu_43828_3138" depth="2" num="0"><a href="/pages/page_3138.php" class="vmenu_depth_1">원퍼널문자 매뉴얼</a></li>
<li id="vmenu_43828_3100" depth="2" num="0"><a href="/pages/page_3100.php" class="vmenu_depth_1">자주하는 질문</a></li>
</ul>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$('#vmenu_43828 ul').css('display','none');
	$('#vmenu_43828 a.active').next('ul').css('display','block');

	
	$('<li class="noLava"><div class="menu_line"></div></li>').insertBefore($('#vmenu_43828>li[num]:gt(0)'));

});
</script>
</div>
<div id="cell_123_129">
<div class="smartOutput float_clear"><p style="text-align: center;"><span style="font-size:16px;"><strong><span style="color:#e74c3c;">CUSTOMER</span> <span style="color:#f39c12;">CENTER</span></strong></span></p>

<table align="center" border="0" cellpadding="5" cellspacing="1" style="border-bottom:1px dotted #dddddd;border-spacing:1px;margin-bottom:5px;">
	<tbody>
		<tr>
			<td style="padding: 5px;"><img alt="" src="/image_bank/dummy/fskin_178/phone.png" /></td>
			<td style="padding: 5px;">
			<p style="line-height:130%;"><strong><span style="font-size:22px;">1588-1234</span></strong></p>

			<p style="line-height:130%;"><span style="font-size:12px;">&nbsp;<a href="mailto:help@abc.co.kr">help@abc.co.kr</a></span></p>
			</td>
		</tr>
	</tbody>
</table>

<p style="line-height: 100%; text-align: center;"><strong><span style="font-size:12px;">평일 : 09:00 ~ 18:00</span></strong></p>

<p style="line-height: 100%; text-align: center;"><strong><span style="font-size:12px;">주말/공휴일 : 10:00 ~ 18:00</span></strong></p>
</div>
</div>
</div>
</div>
<div id="cell_0_126">
<div id="cell_126_135" class="float_clear row">
<div id="cell_135_141" class="col-xs-12 col-sm-12 col-md-4">
<?php
$module_id=43827;
$cell_id='cell_135_141';
@include_once '/home/hosting_users/ilearning1/www/cgi_bank/menu/hmenu_4.php';
?>
</div>
<div id="cell_135_155" class="hidden-xs col-sm-2">
<div class="smartOutput htmlEditor float_clear"><p><img alt="" src="/image_bank/dummy/fskin_178/logo_bottom.png" /></p>
</div>
</div>
<div id="cell_135_140" class="col-xs-12 col-sm-12 col-md-6">
<div class="smartOutput float_clear"><p><span style="color: rgb(105, 105, 105);"><span style="font-size: 12px;">서울 용산구 한강로 65-28. Tel : 02-555-5555.&nbsp; Fax : 02-555-6666.</span></span></p>

<p><span style="color: rgb(105, 105, 105);"><span style="font-size: 12px;">Copyright &copy; 2014 Your Company Name.</span></span></p>
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
$global_script_arr[]='/cgi_bank/lib/masonry.pkgd.min.js';
foreach(array_unique($global_script_arr) as $tval) if($tval!='') echo '<script src="'.$tval.'" type="text/javascript"></script>'."\n";
?>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
oz_cell_scroll('cell_124_153','','2',',0,,','1,0,,',0);
});
</script>
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
