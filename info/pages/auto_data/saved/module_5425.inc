<?
	if($_SESSION['level']=='10') {
		$wiz_lan2=get_lang_file($ui_homepath.'/cgi_bank/notice/language'.$masterinfo['lang'].'.inc');
		$tarr=wizpath_encode($ui_pages);
		?>
		<div style="position:absolute;right:0;z-index:999;"><input type="button" class="ozbutton white small" value="<?=$wiz_lan2['setting']?>" onclick="window.open('/cgi_bank_wiz/imgbox/img_7_config.php?cq=<?=base64_encode("5425|$tarr[1]|$menu_id|145")?>','config','status=1,scrollbars=1,resizable=1,width=700,height=670');"></div>
		<?
	}
	?><div id="visual_5425">
	<div id="bg_rolling_5425"><div class="bg_roll" id="bg_visual_5425_1"></div><div class="bg_roll" id="bg_visual_5425_2"></div><div class="bg_roll" id="bg_visual_5425_3"></div><div class="bg_roll" id="bg_visual_5425_4"></div></div>
	<div id="rolling_5425">
		<div class="fg_roll"><img id="layer_5425_11" src="/image_bank/dummy/fskin_177/190805_01.png" class="each_obj"><p id="layer_5425_12" class="each_obj">거대기업의 시대에서</p><p id="layer_5425_13" class="each_obj">서민기업의 시대로</p><p id="layer_5425_14" class="each_obj">-경제성장시대에 대기업이 중요한 역할을 수행<br />
-대기업으로 인한 부의 불균형, 문제 해결 필요<br />
-대기업 스스로 해결 못해, 이젠 서민기업이 대안<br />
-서민기업 연합, 상생, 자동화, 지역연대가 대안</p></div><div class="fg_roll"><img id="layer_5425_21" src="/image_bank/dummy/fskin_177/190805_02.png" class="each_obj"><p id="layer_5425_22" class="each_obj">오프시대에서</p><p id="layer_5425_23" class="each_obj">O2O시대로</p><p id="layer_5425_24" class="each_obj"> -오프시대의 대표는 아직도 소상공인 서민기업<br />
-세계는 O2O시스템의 플랫폼으로 초연결 전쟁중<br />
-서민기업도 융합과 초연결의 솔루션 도입 필요<br />
-서경련의 플랫폼으로 최고의 기술과 솔루션 지원</p></div><div class="fg_roll"><img id="layer_5425_31" src="/image_bank/dummy/fskin_177/190805_03.png" class="each_obj"><p id="layer_5425_32" class="each_obj">생산의 시대에서</p><p id="layer_5425_33" class="each_obj">분배의 시대로</p><p id="layer_5425_34" class="each_obj">-인류가 생산에 집중했어도 항상 1/N이 안된 과거<br />
-드디어 인류총생산이 1/N하여 안정적 수익 가능 <br />
-문제는 1%가 50%의 부를 가진 부의 불균형 상태<br />
-이제는 생산의 시대에서 부를 분배하는 미션 필요</p></div><div class="fg_roll"><p id="layer_5425_41" class="each_obj">서민기업살리기 프로젝트</p><p id="layer_5425_42" class="each_obj">4차산업시대의 소상공인 협업시스템<br />
N잡시대의 맞춤 일자리창출 시스템<br />
일자리창출과 소상공인 매출증진 연결시스템<br />
대기업 독점마켓에서 서민기업 마켓공유 시스템</p></div>				<div id="pager_5425">
			<a class="pager btn_dish" onclick="cycle_this(5425,1)"><img src="/image_bank/scroll_button/btn_roll_on.png"></a><a class="pager btn_dish" onclick="cycle_this(5425,2)"><img src="/image_bank/scroll_button/btn_roll_off.png"></a><a class="pager btn_dish" onclick="cycle_this(5425,3)"><img src="/image_bank/scroll_button/btn_roll_off.png"></a><a class="pager btn_dish" onclick="cycle_this(5425,4)"><img src="/image_bank/scroll_button/btn_roll_off.png"></a>			<div class="pager roll_control">
				<a class="btn_pause on" onclick="cycle_pause(5425);">Pause</a>
				<a class="btn_play" onclick="cycle_resume(5425);">Play</a>
			</div>
		</div>
			</div>
</div>
<script type="text/javascript">
var reset_org_5425=[400,-100,0,0,400,300,25,0,0,0,10,0,0,0,10,0,0,0,0,0,0,0,10,0,0,0,10,0,0,0,10,0,0,0,0,0,0,0,10,0,0,0,10,0,0,0,10,0,0,0,0,0,0,0,0,0];
var after_org_5425=[123,166,200,200,343,153,35,0,341,202,50,0,343,273,20,0,146,160,200,200,364,136,35,0,363,185,50,0,364,261,20,0,139,181,200,200,361,165,35,0,362,213,50,0,362,287,20,0,246,57,50,0,296,131,20,0];
var reset_cur_5425=[400,-100,0,0,400,300,25,0,0,0,10,0,0,0,10,0,0,0,0,0,0,0,10,0,0,0,10,0,0,0,10,0,0,0,0,0,0,0,10,0,0,0,10,0,0,0,10,0,0,0,0,0,0,0,0,0];
var after_cur_5425=[123,166,200,200,343,153,35,0,341,202,50,0,343,273,20,0,146,160,200,200,364,136,35,0,363,185,50,0,364,261,20,0,139,181,200,200,361,165,35,0,362,213,50,0,362,287,20,0,246,57,50,0,296,131,20,0];
jQuery(document).ready(function(){
	$('#rolling_5425').show();
		$("#rolling_5425 .fg_roll").each(function(i){$(this).data("index", i);});
visualAfter_5425();
	$("#bg_rolling_5425").cycle({fx: "scrollLeft", speed:600, timeout: 5000, slideExpr : ">.bg_roll", slideResize:0});
	$("#rolling_5425").cycle({fx: "scrollLeft", speed:800, timeout: 5000, slideExpr : ">.fg_roll", before: visualBefore_5425, after : visualAfter_5425});
	var bg_rolling_opts = $('#bg_rolling_5425').data('cycle.opts');
	var rolling_opts = $('#rolling_5425').data('cycle.opts');
	$('#rolling_5425').swipe({
		swipeLeft:function(){
			bg_rolling_opts.fx = 'scrollLeft';
			$('#bg_rolling_5425').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'scrollLeft';
			$('#rolling_5425').data('cycle.opts',rolling_opts);
			$('#bg_rolling_5425').cycle('next');
			$('#rolling_5425').cycle('next');
			bg_rolling_opts.fx = 'scrollLeft';
			$('#bg_rolling_5425').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'scrollLeft';
			$('#rolling_5425').data('cycle.opts',rolling_opts);
			cycle_pause(5425);
		},
		swipeRight:function(){
			bg_rolling_opts.fx = 'scrollRight';
			$('#bg_rolling_5425').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'scrollRight';
			$('#rolling_5425').data('cycle.opts',rolling_opts);
			$('#bg_rolling_5425').cycle('prev');
			$('#rolling_5425').cycle('prev');
			bg_rolling_opts.fx = 'scrollLeft';
			$('#bg_rolling_5425').data('cycle.opts',bg_rolling_opts);
			rolling_opts.fx = 'scrollLeft';
			$('#rolling_5425').data('cycle.opts',rolling_opts);
			cycle_pause(5425);
		}
	});
	});
jQuery(window).bind('resize', repos_5425);
function repos_5425() {
	var width=990;
	var width_cur=$('#visual_5425').width();
	if(width_cur < width) {
		var tval;
		var ratio=width_cur/width;
		var ratio2=ratio*1;
		if(ratio2>1) ratio2=1;
		var ratio_gap=ratio2-ratio;
		for(i in reset_org_5425) {
			tval=reset_org_5425[i]*ratio2;
			if((i%4)<2) tval -= reset_org_5425[i]*ratio_gap;
			reset_cur_5425[i]=Math.ceil(tval);
		}
		for(i in after_org_5425) {
			tval=after_org_5425[i]*ratio2;
			if((i%4)<2) tval -= after_org_5425[i]*ratio_gap;
			after_cur_5425[i]=Math.ceil(tval);
		}
		$('#visual_5425').css('height',Math.ceil(600*ratio)+'px');
		$('#rolling_5425').css({'left':0,'width':width_cur+'px','margin-left':0,'height':'100%'});
	} else {
		reset_cur_5425=[400,-100,0,0,400,300,25,0,0,0,10,0,0,0,10,0,0,0,0,0,0,0,10,0,0,0,10,0,0,0,10,0,0,0,0,0,0,0,10,0,0,0,10,0,0,0,10,0,0,0,0,0,0,0,0,0];
		after_cur_5425=[123,166,200,200,343,153,35,0,341,202,50,0,343,273,20,0,146,160,200,200,364,136,35,0,363,185,50,0,364,261,20,0,139,181,200,200,361,165,35,0,362,213,50,0,362,287,20,0,246,57,50,0,296,131,20,0];
		$('#visual_5425').css('height','600px');
		$('#rolling_5425').css({'left':'50%','width':'990px','margin-left':'-495px','height':'600px'});
	}
	}
function visualReset_5425(idx) {
	var tarr=reset_cur_5425;
	$("#rolling_5425 .fg_roll:eq("+idx+")").children(".each_obj").hide();
	if(idx==0){
$("#layer_5425_11").css({left:tarr[0]+'px',top:tarr[1]+'px',opacity:0,width:tarr[2]+'px',height:tarr[3]+'px'});
$("#layer_5425_12").css({left:tarr[4]+'px',top:tarr[5]+'px',opacity:0,'font-size':((tarr[6]>10)?tarr[6]:10)+'px',color:'#ffffff'});
$("#layer_5425_13").css({left:tarr[8]+'px',top:tarr[9]+'px',opacity:0,'font-size':((tarr[10]>10)?tarr[10]:10)+'px',color:'#ffffff'});
$("#layer_5425_14").css({left:tarr[12]+'px',top:tarr[13]+'px',opacity:0,'font-size':((tarr[14]>10)?tarr[14]:10)+'px',color:'#ffffff'});
$("#visual_5425").animate({"background-color":"transparent"},600);
} else if(idx==1){
$("#layer_5425_21").css({left:tarr[16]+'px',top:tarr[17]+'px',opacity:0,width:tarr[18]+'px',height:tarr[19]+'px'});
$("#layer_5425_22").css({left:tarr[20]+'px',top:tarr[21]+'px',opacity:0,'font-size':((tarr[22]>10)?tarr[22]:10)+'px',color:'#ffffff'});
$("#layer_5425_23").css({left:tarr[24]+'px',top:tarr[25]+'px',opacity:0,'font-size':((tarr[26]>10)?tarr[26]:10)+'px',color:'undefined'});
$("#layer_5425_24").css({left:tarr[28]+'px',top:tarr[29]+'px',opacity:0,'font-size':((tarr[30]>10)?tarr[30]:10)+'px',color:'undefined'});
$("#visual_5425").animate({"background-color":"transparent"},600);
} else if(idx==2){
$("#layer_5425_31").css({left:tarr[32]+'px',top:tarr[33]+'px',opacity:0,width:tarr[34]+'px',height:tarr[35]+'px'});
$("#layer_5425_32").css({left:tarr[36]+'px',top:tarr[37]+'px',opacity:0,'font-size':((tarr[38]>10)?tarr[38]:10)+'px',color:'undefined'});
$("#layer_5425_33").css({left:tarr[40]+'px',top:tarr[41]+'px',opacity:0,'font-size':((tarr[42]>10)?tarr[42]:10)+'px',color:'undefined'});
$("#layer_5425_34").css({left:tarr[44]+'px',top:tarr[45]+'px',opacity:0,'font-size':((tarr[46]>10)?tarr[46]:10)+'px',color:'undefined'});
$("#visual_5425").animate({"background-color":"transparent"},600);
} else if(idx==3){
$("#layer_5425_41").css({left:tarr[48]+'px',top:tarr[49]+'px',opacity:0,'font-size':((tarr[50]>10)?tarr[50]:10)+'px',color:'undefined'});
$("#layer_5425_42").css({left:tarr[52]+'px',top:tarr[53]+'px',opacity:0,'font-size':((tarr[54]>10)?tarr[54]:10)+'px',color:'undefined'});
$("#visual_5425").animate({"background-color":"#8cb4d1"},600);
}}
function visualBefore_5425(){
	var idx = parseInt($(this).data("index"));
	visualReset_5425(idx);
		$('#pager_5425 a').removeClass("active");
	$('#pager_5425 a:eq('+idx+')').addClass("active");
	$('#pager_5425 .btn_dish img').attr('src','/image_bank/scroll_button/btn_roll_off.png');
	if($('#pager_5425 .btn_dish').hasClass('active')) $('#pager_5425 .btn_dish.active img').attr('src','/image_bank/scroll_button/btn_roll_on.png');
	}
function visualAfter_5425(){
	var idx = parseInt($(this).data("index"));
		if(isNaN(idx)) repos_5425();
		var tarr=after_cur_5425;
	if(idx==0){
$("#layer_5425_11").delay(250).show().animate({left:tarr[0]+'px',top:tarr[1]+'px',opacity:1,width:tarr[2]+'px',height:tarr[3]+'px'},250);
$("#layer_5425_12").delay(500).show().animate({left:tarr[4]+'px',top:tarr[5]+'px',opacity:1,'font-size':((tarr[6]>10)?tarr[6]:10)+'px',color:'#ffffff'},250);
$("#layer_5425_13").delay(750).show().animate({left:tarr[8]+'px',top:tarr[9]+'px',opacity:1,'font-size':((tarr[10]>10)?tarr[10]:10)+'px',color:'#ffffff'},250);
$("#layer_5425_14").delay(1000).show().animate({left:tarr[12]+'px',top:tarr[13]+'px',opacity:1,'font-size':((tarr[14]>10)?tarr[14]:10)+'px',color:'#ffffff'},250);
} else if(idx==1){
$("#layer_5425_21").delay(250).show().animate({left:tarr[16]+'px',top:tarr[17]+'px',opacity:1,width:tarr[18]+'px',height:tarr[19]+'px'},250);
$("#layer_5425_22").delay(500).show().animate({left:tarr[20]+'px',top:tarr[21]+'px',opacity:1,'font-size':((tarr[22]>10)?tarr[22]:10)+'px',color:'#ffffff'},250);
$("#layer_5425_23").delay(750).show().animate({left:tarr[24]+'px',top:tarr[25]+'px',opacity:1,'font-size':((tarr[26]>10)?tarr[26]:10)+'px',color:'#ffffff'},250);
$("#layer_5425_24").delay(1000).show().animate({left:tarr[28]+'px',top:tarr[29]+'px',opacity:1,'font-size':((tarr[30]>10)?tarr[30]:10)+'px',color:'#ffffff'},250);
} else if(idx==2){
$("#layer_5425_31").delay(250).show().animate({left:tarr[32]+'px',top:tarr[33]+'px',opacity:1,width:tarr[34]+'px',height:tarr[35]+'px'},250);
$("#layer_5425_32").delay(500).show().animate({left:tarr[36]+'px',top:tarr[37]+'px',opacity:1,'font-size':((tarr[38]>10)?tarr[38]:10)+'px',color:'#ffffff'},250);
$("#layer_5425_33").delay(750).show().animate({left:tarr[40]+'px',top:tarr[41]+'px',opacity:1,'font-size':((tarr[42]>10)?tarr[42]:10)+'px',color:'#ffffff'},250);
$("#layer_5425_34").delay(1000).show().animate({left:tarr[44]+'px',top:tarr[45]+'px',opacity:1,'font-size':((tarr[46]>10)?tarr[46]:10)+'px',color:'#ffffff'},250);
} else if(idx==3){
$("#layer_5425_41").delay(250).show().animate({left:tarr[48]+'px',top:tarr[49]+'px',opacity:1,'font-size':((tarr[50]>10)?tarr[50]:10)+'px',color:'#ffffff'},250);
$("#layer_5425_42").delay(500).show().animate({left:tarr[52]+'px',top:tarr[53]+'px',opacity:1,'font-size':((tarr[54]>10)?tarr[54]:10)+'px',color:'#ffffff'},250);
}}
</script>
