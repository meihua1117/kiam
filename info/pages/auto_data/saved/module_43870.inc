<?
	if($_SESSION['level']=='10') {
		$wiz_lan2=get_lang_file($ui_homepath.'/cgi_bank/notice/language'.$masterinfo['lang'].'.inc');
		$tarr=wizpath_encode($ui_pages);
		?>
		<div style="position:absolute;right:0;z-index:999;"><input type="button" class="ozbutton white small" value="<?=$wiz_lan2['setting']?>" onclick="window.open('/cgi_bank_wiz/imgbox/img_7_config.php?cq=<?=base64_encode("43870|$tarr[1]|$menu_id|17")?>','config','status=1,scrollbars=1,resizable=1,width=700,height=670');"></div>
		<?
	}
	?><div id="visual_43870">
	<div id="bg_rolling_43870"><div class="bg_roll" id="bg_visual_43870_1"></div></div>
	<div id="rolling_43870">
		<div class="fg_roll"><img id="layer_43870_11" src="/image_bank/dummy/fskin_178/obj_2.png" class="each_obj"><img id="layer_43870_12" src="/image_bank/dummy/fskin_178/obj_1.png" class="each_obj"><p id="layer_43870_13" class="each_obj">Business Innovation!</p><p id="layer_43870_14" class="each_obj">성공적인 사업을 위한 든든한 파트너, 오즈웹이 함께하겠습니다.</p></div>			</div>
</div>
<script type="text/javascript">
var reset_org_43870=[200,250,0,0,320,250,88,100,118,-100,10,0,119,187,13,0];
var after_org_43870=[-31,21,500,273,413,44,0,0,118,136,40,0,119,187,13,0];
var reset_cur_43870=[200,250,0,0,320,250,88,100,118,-100,10,0,119,187,13,0];
var after_cur_43870=[-31,21,500,273,413,44,0,0,118,136,40,0,119,187,13,0];
jQuery(document).ready(function(){
	$('#rolling_43870').show();
			repos_43870();
		var idx=0;
	var tarr=reset_cur_43870;
	if(idx==0){
$("#layer_43870_11").css({left:tarr[0]+'px',top:tarr[1]+'px',width:tarr[2]+'px',height:tarr[3]+'px'});
$("#layer_43870_12").css({left:tarr[4]+'px',top:tarr[5]+'px',opacity:0,width:tarr[6]+'px',height:tarr[7]+'px'});
$("#layer_43870_13").css({left:tarr[8]+'px',top:tarr[9]+'px','font-size':((tarr[10]>10)?tarr[10]:10)+'px'});
$("#layer_43870_14").css({left:tarr[12]+'px',top:tarr[13]+'px',opacity:0,'font-size':((tarr[14]>10)?tarr[14]:10)+'px'});
}	tarr=after_cur_43870;
	if(idx==0){
$("#layer_43870_11").delay(500).show().animate({left:tarr[0]+'px',top:tarr[1]+'px',opacity:1,width:tarr[2]+'px',height:tarr[3]+'px'},400);
$("#layer_43870_12").delay(2500).show().animate({left:tarr[4]+'px',top:tarr[5]+'px',opacity:1},400);
$("#layer_43870_13").delay(1000).show().animate({left:tarr[8]+'px',top:tarr[9]+'px',opacity:1,'font-size':((tarr[10]>10)?tarr[10]:10)+'px',color:'#00569c'},600);
$("#layer_43870_14").delay(2000).show().animate({left:tarr[12]+'px',top:tarr[13]+'px',opacity:1,'font-size':((tarr[14]>10)?tarr[14]:10)+'px'},400);
}	});
jQuery(window).bind('resize', repos_43870);
function repos_43870() {
	var width=600;
	var width_cur=$('#visual_43870').width();
	if(width_cur < width) {
		var tval;
		var ratio=width_cur/width;
		var ratio2=ratio*1.2;
		if(ratio2>1) ratio2=1;
		var ratio_gap=ratio2-ratio;
		for(i in reset_org_43870) {
			tval=reset_org_43870[i]*ratio2;
			if((i%4)<2) tval -= reset_org_43870[i]*ratio_gap;
			reset_cur_43870[i]=Math.ceil(tval);
		}
		for(i in after_org_43870) {
			tval=after_org_43870[i]*ratio2;
			if((i%4)<2) tval -= after_org_43870[i]*ratio_gap;
			after_cur_43870[i]=Math.ceil(tval);
		}
		$('#visual_43870').css('height',Math.ceil(300*ratio)+'px');
		$('#rolling_43870').css({'left':0,'width':width_cur+'px','margin-left':0,'height':'100%'});
	} else {
		reset_cur_43870=[200,250,0,0,320,250,88,100,118,-100,10,0,119,187,13,0];
		after_cur_43870=[-31,21,500,273,413,44,0,0,118,136,40,0,119,187,13,0];
		$('#visual_43870').css('height','300px');
		$('#rolling_43870').css({'left':'50%','width':'600px','margin-left':'-300px','height':'300px'});
	}
		var idx=0;
	var tarr=reset_cur_43870;
	if(idx==0){
$("#layer_43870_11").css({left:tarr[0]+'px',top:tarr[1]+'px',width:tarr[2]+'px',height:tarr[3]+'px'});
$("#layer_43870_12").css({left:tarr[4]+'px',top:tarr[5]+'px',opacity:0,width:tarr[6]+'px',height:tarr[7]+'px'});
$("#layer_43870_13").css({left:tarr[8]+'px',top:tarr[9]+'px','font-size':((tarr[10]>10)?tarr[10]:10)+'px'});
$("#layer_43870_14").css({left:tarr[12]+'px',top:tarr[13]+'px',opacity:0,'font-size':((tarr[14]>10)?tarr[14]:10)+'px'});
}	$('#layer_43870_11,#layer_43870_12,#layer_43870_13,#layer_43870_14').stop(true);
	tarr=after_cur_43870;
	if(idx==0){
$("#layer_43870_11").delay(500).show().animate({left:tarr[0]+'px',top:tarr[1]+'px',opacity:1,width:tarr[2]+'px',height:tarr[3]+'px'},400);
$("#layer_43870_12").delay(2500).show().animate({left:tarr[4]+'px',top:tarr[5]+'px',opacity:1},400);
$("#layer_43870_13").delay(1000).show().animate({left:tarr[8]+'px',top:tarr[9]+'px',opacity:1,'font-size':((tarr[10]>10)?tarr[10]:10)+'px',color:'#00569c'},600);
$("#layer_43870_14").delay(2000).show().animate({left:tarr[12]+'px',top:tarr[13]+'px',opacity:1,'font-size':((tarr[14]>10)?tarr[14]:10)+'px'},400);
}	}
</script>
