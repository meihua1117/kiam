<?
	if($_SESSION['level']=='10') {
		$wiz_lan2=get_lang_file($ui_homepath.'/cgi_bank/notice/language'.$masterinfo['lang'].'.inc');
		$tarr=wizpath_encode($ui_pages);
		?>
		<div style="position:absolute;right:0;z-index:999;"><input type="button" class="ozbutton white small" value="<?=$wiz_lan2['setting']?>" onclick="window.open('/cgi_bank_wiz/imgbox/img_7_config.php?cq=<?=base64_encode("1008|$tarr[1]|$menu_id|137")?>','config','status=1,scrollbars=1,resizable=1,width=700,height=670');"></div>
		<?
	}
	?><div id="visual_1008">
	<div id="bg_rolling_1008"><div class="bg_roll" id="bg_visual_1008_1"></div><div class="bg_roll" id="bg_visual_1008_2"></div><div class="bg_roll" id="bg_visual_1008_3"></div><div class="bg_roll" id="bg_visual_1008_4"></div><div class="bg_roll" id="bg_visual_1008_5"></div></div>
	<div id="rolling_1008">
		<div class="fg_roll"><p id="layer_1008_11" class="each_obj">인지코칭학</p><p id="layer_1008_12" class="each_obj"><알아내는> 방법을 배우는 <역량중심> 교육</p></div><div class="fg_roll"><p id="layer_1008_21" class="each_obj">인지코칭학</p><p id="layer_1008_22" class="each_obj"><알아내는> 방법을 배우는 <역량중심> 교육</p></div><div class="fg_roll"><p id="layer_1008_31" class="each_obj">인지코칭학</p><p id="layer_1008_32" class="each_obj"><알아내는> 방법을 배우는 <역량중심> 교육</p></div><div class="fg_roll"><p id="layer_1008_41" class="each_obj">인지코칭학</p><p id="layer_1008_42" class="each_obj"><알아내는> 방법을 배우는 <역량중심> 교육</p></div><div class="fg_roll"><p id="layer_1008_51" class="each_obj">인지코칭학</p><p id="layer_1008_52" class="each_obj"><알아내는> 방법을 배우는 <역량중심> 교육</p></div>				<div id="pager_1008">
			<a class="pager btn_dish" onclick="cycle_this(1008,1)"><img src="/image_bank/scroll_button/btn_roll_on.png"></a><a class="pager btn_dish" onclick="cycle_this(1008,2)"><img src="/image_bank/scroll_button/btn_roll_off.png"></a><a class="pager btn_dish" onclick="cycle_this(1008,3)"><img src="/image_bank/scroll_button/btn_roll_off.png"></a><a class="pager btn_dish" onclick="cycle_this(1008,4)"><img src="/image_bank/scroll_button/btn_roll_off.png"></a><a class="pager btn_dish" onclick="cycle_this(1008,5)"><img src="/image_bank/scroll_button/btn_roll_off.png"></a>			<div class="pager roll_control">
				<a class="btn_pause on" onclick="cycle_pause(1008);">Pause</a>
				<a class="btn_play" onclick="cycle_resume(1008);">Play</a>
			</div>
		</div>
			</div>
</div>
<script type="text/javascript">
var reset_org_1008=[400,-100,10,0,320,500,18,0,400,-100,10,0,320,500,18,0,400,-100,10,0,320,500,18,0,400,-100,10,0,320,500,18,0,400,-100,10,0,320,500,18,0];
var after_org_1008=[427,94,50,0,389,160,18,0,258,91,50,0,175,157,18,0,428,99,50,0,383,166,18,0,423,95,50,0,376,160,18,0,425,95,50,0,375,157,18,0];
var reset_cur_1008=[400,-100,10,0,320,500,18,0,400,-100,10,0,320,500,18,0,400,-100,10,0,320,500,18,0,400,-100,10,0,320,500,18,0,400,-100,10,0,320,500,18,0];
var after_cur_1008=[427,94,50,0,389,160,18,0,258,91,50,0,175,157,18,0,428,99,50,0,383,166,18,0,423,95,50,0,376,160,18,0,425,95,50,0,375,157,18,0];
jQuery(document).ready(function(){
	$('#rolling_1008').show();
		$("#rolling_1008 .fg_roll").each(function(i){$(this).data("index", i);});
visualAfter_1008();
	$("#bg_rolling_1008").cycle({fx: "fade", speed:800, timeout: 7000, slideExpr : ">.bg_roll", slideResize:0});
	$("#rolling_1008").cycle({fx: "scrollLeft", speed:800, timeout: 7000, slideExpr : ">.fg_roll", before: visualBefore_1008, after : visualAfter_1008});
	var bg_rolling_opts = $('#bg_rolling_1008').data('cycle.opts');
	var rolling_opts = $('#rolling_1008').data('cycle.opts');
	$('#rolling_1008').swipe({
		swipeLeft:function(){
			bg_rolling_opts.fx = 'scrollLeft';
			$('#bg_rolling_1008').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'scrollLeft';
			$('#rolling_1008').data('cycle.opts',rolling_opts);
			$('#bg_rolling_1008').cycle('next');
			$('#rolling_1008').cycle('next');
			bg_rolling_opts.fx = 'fade';
			$('#bg_rolling_1008').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'scrollLeft';
			$('#rolling_1008').data('cycle.opts',rolling_opts);
			cycle_pause(1008);
		},
		swipeRight:function(){
			bg_rolling_opts.fx = 'scrollRight';
			$('#bg_rolling_1008').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'scrollRight';
			$('#rolling_1008').data('cycle.opts',rolling_opts);
			$('#bg_rolling_1008').cycle('prev');
			$('#rolling_1008').cycle('prev');
			bg_rolling_opts.fx = 'fade';
			$('#bg_rolling_1008').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'scrollLeft';
			$('#rolling_1008').data('cycle.opts',rolling_opts);
			cycle_pause(1008);
		}
	});
	});
jQuery(window).bind('resize', repos_1008);
function repos_1008() {
	var width=1100;
	var width_cur=$('#visual_1008').width();
	if(width_cur < width) {
		var tval;
		var ratio=width_cur/width;
		var ratio2=ratio*1.2;
		if(ratio2>1) ratio2=1;
		var ratio_gap=ratio2-ratio;
		for(i in reset_org_1008) {
			tval=reset_org_1008[i]*ratio2;
			if((i%4)<2) tval -= reset_org_1008[i]*ratio_gap;
			reset_cur_1008[i]=Math.ceil(tval);
		}
		for(i in after_org_1008) {
			tval=after_org_1008[i]*ratio2;
			if((i%4)<2) tval -= after_org_1008[i]*ratio_gap;
			after_cur_1008[i]=Math.ceil(tval);
		}
		$('#visual_1008').css('height',Math.ceil(300*ratio)+'px');
		$('#rolling_1008').css({'left':0,'width':width_cur+'px','margin-left':0,'height':'100%'});
	} else {
		reset_cur_1008=[400,-100,10,0,320,500,18,0,400,-100,10,0,320,500,18,0,400,-100,10,0,320,500,18,0,400,-100,10,0,320,500,18,0,400,-100,10,0,320,500,18,0];
		after_cur_1008=[427,94,50,0,389,160,18,0,258,91,50,0,175,157,18,0,428,99,50,0,383,166,18,0,423,95,50,0,376,160,18,0,425,95,50,0,375,157,18,0];
		$('#visual_1008').css('height','300px');
		$('#rolling_1008').css({'left':'50%','width':'1100px','margin-left':'-550px','height':'300px'});
	}
	}
function visualReset_1008(idx) {
	var tarr=reset_cur_1008;
	$("#rolling_1008 .fg_roll:eq("+idx+")").children(".each_obj").hide();
	if(idx==0){
$("#layer_1008_11").css({left:tarr[0]+'px',top:tarr[1]+'px','font-size':((tarr[2]>10)?tarr[2]:10)+'px',color:'#ffffff'});
$("#layer_1008_12").css({left:tarr[4]+'px',top:tarr[5]+'px','font-size':((tarr[6]>10)?tarr[6]:10)+'px',color:'#ffffff'});
} else if(idx==1){
$("#layer_1008_21").css({left:tarr[8]+'px',top:tarr[9]+'px','font-size':((tarr[10]>10)?tarr[10]:10)+'px',color:'#000000'});
$("#layer_1008_22").css({left:tarr[12]+'px',top:tarr[13]+'px','font-size':((tarr[14]>10)?tarr[14]:10)+'px',color:'#000000'});
} else if(idx==2){
$("#layer_1008_31").css({left:tarr[16]+'px',top:tarr[17]+'px','font-size':((tarr[18]>10)?tarr[18]:10)+'px',color:'#ffffff'});
$("#layer_1008_32").css({left:tarr[20]+'px',top:tarr[21]+'px','font-size':((tarr[22]>10)?tarr[22]:10)+'px',color:'#ffffff'});
} else if(idx==3){
$("#layer_1008_41").css({left:tarr[24]+'px',top:tarr[25]+'px','font-size':((tarr[26]>10)?tarr[26]:10)+'px',color:'#ffffff'});
$("#layer_1008_42").css({left:tarr[28]+'px',top:tarr[29]+'px','font-size':((tarr[30]>10)?tarr[30]:10)+'px',color:'#ffffff'});
} else if(idx==4){
$("#layer_1008_51").css({left:tarr[32]+'px',top:tarr[33]+'px','font-size':((tarr[34]>10)?tarr[34]:10)+'px',color:'#ffffff'});
$("#layer_1008_52").css({left:tarr[36]+'px',top:tarr[37]+'px','font-size':((tarr[38]>10)?tarr[38]:10)+'px',color:'#ffffff'});
}}
function visualBefore_1008(){
	var idx = parseInt($(this).data("index"));
	visualReset_1008(idx);
		$('#pager_1008 a').removeClass("active");
	$('#pager_1008 a:eq('+idx+')').addClass("active");
	$('#pager_1008 .btn_dish img').attr('src','/image_bank/scroll_button/btn_roll_off.png');
	if($('#pager_1008 .btn_dish').hasClass('active')) $('#pager_1008 .btn_dish.active img').attr('src','/image_bank/scroll_button/btn_roll_on.png');
	}
function visualAfter_1008(){
	var idx = parseInt($(this).data("index"));
		if(isNaN(idx)) repos_1008();
		var tarr=after_cur_1008;
	if(idx==0){
$("#layer_1008_11").delay(250).show().animate({left:tarr[0]+'px',top:tarr[1]+'px',opacity:1,'font-size':((tarr[2]>10)?tarr[2]:10)+'px'},900);
$("#layer_1008_12").delay(1500).show().animate({left:tarr[4]+'px',top:tarr[5]+'px',opacity:1,'font-size':((tarr[6]>10)?tarr[6]:10)+'px'},400);
} else if(idx==1){
$("#layer_1008_21").delay(250).show().animate({left:tarr[8]+'px',top:tarr[9]+'px',opacity:1,'font-size':((tarr[10]>10)?tarr[10]:10)+'px'},900);
$("#layer_1008_22").delay(1500).show().animate({left:tarr[12]+'px',top:tarr[13]+'px',opacity:1,'font-size':((tarr[14]>10)?tarr[14]:10)+'px'},400);
} else if(idx==2){
$("#layer_1008_31").delay(250).show().animate({left:tarr[16]+'px',top:tarr[17]+'px',opacity:1,'font-size':((tarr[18]>10)?tarr[18]:10)+'px'},900);
$("#layer_1008_32").delay(1500).show().animate({left:tarr[20]+'px',top:tarr[21]+'px',opacity:1,'font-size':((tarr[22]>10)?tarr[22]:10)+'px'},400);
} else if(idx==3){
$("#layer_1008_41").delay(250).show().animate({left:tarr[24]+'px',top:tarr[25]+'px',opacity:1,'font-size':((tarr[26]>10)?tarr[26]:10)+'px'},900);
$("#layer_1008_42").delay(1500).show().animate({left:tarr[28]+'px',top:tarr[29]+'px',opacity:1,'font-size':((tarr[30]>10)?tarr[30]:10)+'px'},400);
} else if(idx==4){
$("#layer_1008_51").delay(250).show().animate({left:tarr[32]+'px',top:tarr[33]+'px',opacity:1,'font-size':((tarr[34]>10)?tarr[34]:10)+'px'},900);
$("#layer_1008_52").delay(1500).show().animate({left:tarr[36]+'px',top:tarr[37]+'px',opacity:1,'font-size':((tarr[38]>10)?tarr[38]:10)+'px'},400);
}}
</script>
