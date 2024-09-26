<?
	if($_SESSION['level']=='10') {
		$wiz_lan2=get_lang_file($ui_homepath.'/cgi_bank/notice/language'.$masterinfo['lang'].'.inc');
		$tarr=wizpath_encode($ui_pages);
		?>
		<div style="position:absolute;right:0;z-index:999;"><input type="button" class="ozbutton white small" value="<?=$wiz_lan2['setting']?>" onclick="window.open('/cgi_bank_wiz/imgbox/img_7_config.php?cq=<?=base64_encode("43875|$tarr[1]|$menu_id|154")?>','config','status=1,scrollbars=1,resizable=1,width=700,height=670');"></div>
		<?
	}
	?><div id="visual_43875">
	<div id="bg_rolling_43875"><div class="bg_roll" id="bg_visual_43875_1"></div><div class="bg_roll" id="bg_visual_43875_2"></div><div class="bg_roll" id="bg_visual_43875_3"></div><div class="bg_roll" id="bg_visual_43875_4"></div></div>
	<div id="rolling_43875">
		<div class="fg_roll"><p id="layer_43875_11" class="each_obj">Business Innovation!</p></div><div class="fg_roll"><p id="layer_43875_21" class="each_obj">Business Innovation!</p></div><div class="fg_roll"><p id="layer_43875_31" class="each_obj">성공적인 사업을 위한 든든한 파트너,<br />
오즈웹이 함께하겠습니다.</p></div><div class="fg_roll"><p id="layer_43875_41" class="each_obj">Business Innovation!</p></div>				<div id="pager_43875">
			<a class="pager btn_dish" onclick="cycle_this(43875,1)"><img src="/image_bank/scroll_button/btn_roll_on.png"></a><a class="pager btn_dish" onclick="cycle_this(43875,2)"><img src="/image_bank/scroll_button/btn_roll_off.png"></a><a class="pager btn_dish" onclick="cycle_this(43875,3)"><img src="/image_bank/scroll_button/btn_roll_off.png"></a><a class="pager btn_dish" onclick="cycle_this(43875,4)"><img src="/image_bank/scroll_button/btn_roll_off.png"></a>			<div class="pager roll_control">
				<a class="btn_pause on" onclick="cycle_pause(43875);">Pause</a>
				<a class="btn_play" onclick="cycle_resume(43875);">Play</a>
			</div>
		</div>
			</div>
</div>
<script type="text/javascript">
var reset_org_43875=[-500,90,50,0,200,50,1,0,0,300,99,0,350,300,1,0];
var after_org_43875=[44,64,50,0,123,113,50,0,262,35,30,0,131,145,50,0];
var reset_cur_43875=[-500,90,50,0,200,50,1,0,0,300,99,0,350,300,1,0];
var after_cur_43875=[44,64,50,0,123,113,50,0,262,35,30,0,131,145,50,0];
jQuery(document).ready(function(){
	$('#rolling_43875').show();
		$("#rolling_43875 .fg_roll").each(function(i){$(this).data("index", i);});
visualAfter_43875();
	$("#bg_rolling_43875").cycle({fx: "fade", speed:800, timeout: 8000, slideExpr : ">.bg_roll", slideResize:0});
	$("#rolling_43875").cycle({fx: "fade", speed:800, timeout: 8000, slideExpr : ">.fg_roll", before: visualBefore_43875, after : visualAfter_43875});
	var bg_rolling_opts = $('#bg_rolling_43875').data('cycle.opts');
	var rolling_opts = $('#rolling_43875').data('cycle.opts');
	$('#rolling_43875').swipe({
		swipeLeft:function(){
			bg_rolling_opts.fx = 'scrollLeft';
			$('#bg_rolling_43875').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'scrollLeft';
			$('#rolling_43875').data('cycle.opts',rolling_opts);
			$('#bg_rolling_43875').cycle('next');
			$('#rolling_43875').cycle('next');
			bg_rolling_opts.fx = 'fade';
			$('#bg_rolling_43875').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'fade';
			$('#rolling_43875').data('cycle.opts',rolling_opts);
			cycle_pause(43875);
		},
		swipeRight:function(){
			bg_rolling_opts.fx = 'scrollRight';
			$('#bg_rolling_43875').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'scrollRight';
			$('#rolling_43875').data('cycle.opts',rolling_opts);
			$('#bg_rolling_43875').cycle('prev');
			$('#rolling_43875').cycle('prev');
			bg_rolling_opts.fx = 'fade';
			$('#bg_rolling_43875').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'fade';
			$('#rolling_43875').data('cycle.opts',rolling_opts);
			cycle_pause(43875);
		}
	});
	});
jQuery(window).bind('resize', repos_43875);
function repos_43875() {
	var width=750;
	var width_cur=$('#visual_43875').width();
	if(width_cur < width) {
		var tval;
		var ratio=width_cur/width;
		var ratio2=ratio*1.2;
		if(ratio2>1) ratio2=1;
		var ratio_gap=ratio2-ratio;
		for(i in reset_org_43875) {
			tval=reset_org_43875[i]*ratio2;
			if((i%4)<2) tval -= reset_org_43875[i]*ratio_gap;
			reset_cur_43875[i]=Math.ceil(tval);
		}
		for(i in after_org_43875) {
			tval=after_org_43875[i]*ratio2;
			if((i%4)<2) tval -= after_org_43875[i]*ratio_gap;
			after_cur_43875[i]=Math.ceil(tval);
		}
		$('#visual_43875').css('height',Math.ceil(400*ratio)+'px');
		$('#rolling_43875').css({'left':0,'width':width_cur+'px','margin-left':0,'height':'100%'});
	} else {
		reset_cur_43875=[-500,90,50,0,200,50,1,0,0,300,99,0,350,300,1,0];
		after_cur_43875=[44,64,50,0,123,113,50,0,262,35,30,0,131,145,50,0];
		$('#visual_43875').css('height','400px');
		$('#rolling_43875').css({'left':'50%','width':'750px','margin-left':'-375px','height':'400px'});
	}
	}
function visualReset_43875(idx) {
	var tarr=reset_cur_43875;
	$("#rolling_43875 .fg_roll:eq("+idx+")").children(".each_obj").hide();
	if(idx==0){
$("#layer_43875_11").css({left:tarr[0]+'px',top:tarr[1]+'px',opacity:0,'font-size':((tarr[2]>10)?tarr[2]:10)+'px',color:'#ffffff'});
} else if(idx==1){
$("#layer_43875_21").css({left:tarr[4]+'px',top:tarr[5]+'px',opacity:0,'font-size':((tarr[6]>10)?tarr[6]:10)+'px',color:'#ffffff'});
} else if(idx==2){
$("#layer_43875_31").css({left:tarr[8]+'px',top:tarr[9]+'px',opacity:0,'font-size':((tarr[10]>10)?tarr[10]:10)+'px',color:'#ffffff'});
} else if(idx==3){
$("#layer_43875_41").css({left:tarr[12]+'px',top:tarr[13]+'px',opacity:0,'font-size':((tarr[14]>10)?tarr[14]:10)+'px',color:'#ff0000'});
}}
function visualBefore_43875(){
	var idx = parseInt($(this).data("index"));
	visualReset_43875(idx);
		$('#pager_43875 a').removeClass("active");
	$('#pager_43875 a:eq('+idx+')').addClass("active");
	$('#pager_43875 .btn_dish img').attr('src','/image_bank/scroll_button/btn_roll_off.png');
	if($('#pager_43875 .btn_dish').hasClass('active')) $('#pager_43875 .btn_dish.active img').attr('src','/image_bank/scroll_button/btn_roll_on.png');
	}
function visualAfter_43875(){
	var idx = parseInt($(this).data("index"));
		if(isNaN(idx)) repos_43875();
		var tarr=after_cur_43875;
	if(idx==0){
$("#layer_43875_11").delay(1000).show().animate({left:tarr[0]+'px',top:tarr[1]+'px',opacity:1,'font-size':((tarr[2]>10)?tarr[2]:10)+'px'},400);
} else if(idx==1){
$("#layer_43875_21").delay(1000).show().animate({left:tarr[4]+'px',top:tarr[5]+'px',opacity:1,'font-size':((tarr[6]>10)?tarr[6]:10)+'px'},400);
} else if(idx==2){
$("#layer_43875_31").delay(1000).show().animate({left:tarr[8]+'px',top:tarr[9]+'px',opacity:1,'font-size':((tarr[10]>10)?tarr[10]:10)+'px'},400);
} else if(idx==3){
$("#layer_43875_41").delay(1000).show().animate({left:tarr[12]+'px',top:tarr[13]+'px',opacity:1,'font-size':((tarr[14]>10)?tarr[14]:10)+'px',color:'#2e4f4f'},400);
}}
</script>
