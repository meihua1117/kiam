<?
	if($_SESSION['level']=='10') {
		$wiz_lan2=get_lang_file($ui_homepath.'/cgi_bank/notice/language'.$masterinfo['lang'].'.inc');
		$tarr=wizpath_encode($ui_pages);
		?>
		<div style="position:absolute;right:0;z-index:999;"><input type="button" class="ozbutton white small" value="<?=$wiz_lan2['setting']?>" onclick="window.open('/cgi_bank_wiz/imgbox/img_7_config.php?cq=<?=base64_encode("357|$tarr[1]|$menu_id|154")?>','config','status=1,scrollbars=1,resizable=1,width=700,height=670');"></div>
		<?
	}
	?><div id="visual_357">
	<div id="bg_rolling_357"><div class="bg_roll" id="bg_visual_357_1"></div><div class="bg_roll" id="bg_visual_357_2"></div><div class="bg_roll" id="bg_visual_357_3"></div></div>
	<div id="rolling_357">
		<div class="fg_roll"><p id="layer_357_11" class="each_obj">온리원교육</p><p id="layer_357_12" class="each_obj"><알아내는> 방법을 배우는 <역량중심> 교육</p></div><div class="fg_roll"><p id="layer_357_21" class="each_obj">온리원교육</p><p id="layer_357_22" class="each_obj"><알아내는> 방법을 배우는 <역량중심> 교육</p></div><div class="fg_roll"><p id="layer_357_31" class="each_obj">온리원교육</p><p id="layer_357_32" class="each_obj"><알아내는> 방법을 배우는 <역량중심> 교육</p></div>				<div id="pager_357">
			<a class="pager btn_dish" onclick="cycle_this(357,1)"><img src="/image_bank/scroll_button/btn_roll_on.png"></a><a class="pager btn_dish" onclick="cycle_this(357,2)"><img src="/image_bank/scroll_button/btn_roll_off.png"></a><a class="pager btn_dish" onclick="cycle_this(357,3)"><img src="/image_bank/scroll_button/btn_roll_off.png"></a>			<div class="pager roll_control">
				<a class="btn_pause on" onclick="cycle_pause(357);">Pause</a>
				<a class="btn_play" onclick="cycle_resume(357);">Play</a>
			</div>
		</div>
			</div>
</div>
<script type="text/javascript">
var reset_org_357=[450,-100,10,0,385,500,16,0,999,-100,10,0,999,500,13,0,450,-100,10,0,385,500,16,0];
var after_org_357=[331,46,50,0,358,121,16,0,485,90,50,0,485,61,13,0,331,46,50,0,358,121,16,0];
var reset_cur_357=[450,-100,10,0,385,500,16,0,999,-100,10,0,999,500,13,0,450,-100,10,0,385,500,16,0];
var after_cur_357=[331,46,50,0,358,121,16,0,485,90,50,0,485,61,13,0,331,46,50,0,358,121,16,0];
jQuery(document).ready(function(){
	$('#rolling_357').show();
		$("#rolling_357 .fg_roll").each(function(i){$(this).data("index", i);});
visualAfter_357();
	$("#bg_rolling_357").cycle({fx: "scrollLeft", speed:800, timeout: 5000, slideExpr : ">.bg_roll", slideResize:0});
	$("#rolling_357").cycle({fx: "scrollLeft", speed:800, timeout: 5000, slideExpr : ">.fg_roll", before: visualBefore_357, after : visualAfter_357});
	var bg_rolling_opts = $('#bg_rolling_357').data('cycle.opts');
	var rolling_opts = $('#rolling_357').data('cycle.opts');
	$('#rolling_357').swipe({
		swipeLeft:function(){
			bg_rolling_opts.fx = 'scrollLeft';
			$('#bg_rolling_357').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'scrollLeft';
			$('#rolling_357').data('cycle.opts',rolling_opts);
			$('#bg_rolling_357').cycle('next');
			$('#rolling_357').cycle('next');
			bg_rolling_opts.fx = 'scrollLeft';
			$('#bg_rolling_357').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'scrollLeft';
			$('#rolling_357').data('cycle.opts',rolling_opts);
			cycle_pause(357);
		},
		swipeRight:function(){
			bg_rolling_opts.fx = 'scrollRight';
			$('#bg_rolling_357').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'scrollRight';
			$('#rolling_357').data('cycle.opts',rolling_opts);
			$('#bg_rolling_357').cycle('prev');
			$('#rolling_357').cycle('prev');
			bg_rolling_opts.fx = 'scrollLeft';
			$('#bg_rolling_357').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'scrollLeft';
			$('#rolling_357').data('cycle.opts',rolling_opts);
			cycle_pause(357);
		}
	});
	});
jQuery(window).bind('resize', repos_357);
function repos_357() {
	var width=1100;
	var width_cur=$('#visual_357').width();
	if(width_cur < width) {
		var tval;
		var ratio=width_cur/width;
		var ratio2=ratio*1.2;
		if(ratio2>1) ratio2=1;
		var ratio_gap=ratio2-ratio;
		for(i in reset_org_357) {
			tval=reset_org_357[i]*ratio2;
			if((i%4)<2) tval -= reset_org_357[i]*ratio_gap;
			reset_cur_357[i]=Math.ceil(tval);
		}
		for(i in after_org_357) {
			tval=after_org_357[i]*ratio2;
			if((i%4)<2) tval -= after_org_357[i]*ratio_gap;
			after_cur_357[i]=Math.ceil(tval);
		}
		$('#visual_357').css('height',Math.ceil(200*ratio)+'px');
		$('#rolling_357').css({'left':0,'width':width_cur+'px','margin-left':0,'height':'100%'});
	} else {
		reset_cur_357=[450,-100,10,0,385,500,16,0,999,-100,10,0,999,500,13,0,450,-100,10,0,385,500,16,0];
		after_cur_357=[331,46,50,0,358,121,16,0,485,90,50,0,485,61,13,0,331,46,50,0,358,121,16,0];
		$('#visual_357').css('height','200px');
		$('#rolling_357').css({'left':'50%','width':'1100px','margin-left':'-550px','height':'200px'});
	}
	}
function visualReset_357(idx) {
	var tarr=reset_cur_357;
	$("#rolling_357 .fg_roll:eq("+idx+")").children(".each_obj").hide();
	if(idx==0){
$("#layer_357_11").css({left:tarr[0]+'px',top:tarr[1]+'px',opacity:0,'font-size':((tarr[2]>10)?tarr[2]:10)+'px',color:'#000000'});
$("#layer_357_12").css({left:tarr[4]+'px',top:tarr[5]+'px',opacity:0,'font-size':((tarr[6]>10)?tarr[6]:10)+'px',color:'#000000'});
} else if(idx==1){
$("#layer_357_21").css({left:tarr[8]+'px',top:tarr[9]+'px','font-size':((tarr[10]>10)?tarr[10]:10)+'px',color:'#3094b5'});
$("#layer_357_22").css({left:tarr[12]+'px',top:tarr[13]+'px','font-size':((tarr[14]>10)?tarr[14]:10)+'px',color:'#333333'});
} else if(idx==2){
$("#layer_357_31").css({left:tarr[16]+'px',top:tarr[17]+'px',opacity:0,'font-size':((tarr[18]>10)?tarr[18]:10)+'px',color:'#000000'});
$("#layer_357_32").css({left:tarr[20]+'px',top:tarr[21]+'px',opacity:0,'font-size':((tarr[22]>10)?tarr[22]:10)+'px',color:'#000000'});
}}
function visualBefore_357(){
	var idx = parseInt($(this).data("index"));
	visualReset_357(idx);
		$('#pager_357 a').removeClass("active");
	$('#pager_357 a:eq('+idx+')').addClass("active");
	$('#pager_357 .btn_dish img').attr('src','/image_bank/scroll_button/btn_roll_off.png');
	if($('#pager_357 .btn_dish').hasClass('active')) $('#pager_357 .btn_dish.active img').attr('src','/image_bank/scroll_button/btn_roll_on.png');
	}
function visualAfter_357(){
	var idx = parseInt($(this).data("index"));
		if(isNaN(idx)) repos_357();
		var tarr=after_cur_357;
	if(idx==0){
$("#layer_357_11").delay(250).show().animate({left:tarr[0]+'px',top:tarr[1]+'px',opacity:0.9,'font-size':((tarr[2]>10)?tarr[2]:10)+'px',color:'#ffffff'},900);
$("#layer_357_12").delay(1200).show().animate({left:tarr[4]+'px',top:tarr[5]+'px',opacity:0.9,'font-size':((tarr[6]>10)?tarr[6]:10)+'px',color:'#ffffff'},900);
} else if(idx==1){
$("#layer_357_21").delay(250).show().animate({left:tarr[8]+'px',top:tarr[9]+'px',opacity:1,'font-size':((tarr[10]>10)?tarr[10]:10)+'px'},900);
$("#layer_357_22").delay(1200).show().animate({left:tarr[12]+'px',top:tarr[13]+'px',opacity:1,'font-size':((tarr[14]>10)?tarr[14]:10)+'px'},900);
} else if(idx==2){
$("#layer_357_31").delay(250).show().animate({left:tarr[16]+'px',top:tarr[17]+'px',opacity:0.9,'font-size':((tarr[18]>10)?tarr[18]:10)+'px',color:'#092613'},900);
$("#layer_357_32").delay(1200).show().animate({left:tarr[20]+'px',top:tarr[21]+'px',opacity:0.9,'font-size':((tarr[22]>10)?tarr[22]:10)+'px',color:'#69b5ab'},900);
}}
</script>
