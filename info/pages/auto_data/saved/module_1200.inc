<?
	if($_SESSION['level']=='10') {
		$wiz_lan2=get_lang_file($ui_homepath.'/cgi_bank/notice/language'.$masterinfo['lang'].'.inc');
		$tarr=wizpath_encode($ui_pages);
		?>
		<div style="position:absolute;right:0;z-index:999;"><input type="button" class="ozbutton white small" value="<?=$wiz_lan2['setting']?>" onclick="window.open('/cgi_bank_wiz/imgbox/img_1_config.php?cq=<?=base64_encode("1200|$tarr[1]")?>','config','status=1,scrollbars=1,resizable=1,width=700,height=670');"></div>
		<?
	}
	?><div id="notice_1200"><ul class="notice_1200"><li><div class="each_item"><div class="keep_ratio"><a href="/pages/page_317.php?"><img src="/image_bank/dummy/fskin_174/kmlink_off_01.png" himg="/image_bank/dummy/fskin_174/kmlink_on_01.png" border="0" class="notice_thumb" alt=""></a></div></div></li><li><div class="each_item"><div class="keep_ratio"><a href="/pages/page_325.php?"><img src="/image_bank/dummy/fskin_174/kmlink_off_02.gif" himg="/image_bank/dummy/fskin_174/kmlink_on_02.gif" border="0" class="notice_thumb" alt=""></a></div></div></li><li><div class="each_item"><div class="keep_ratio"><a href="/pages/page_320.php?"><img src="/image_bank/dummy/fskin_174/kmlink_off_03.png" himg="/image_bank/dummy/fskin_174/kmlink_on_03.gif" border="0" class="notice_thumb" alt=""></a></div></div></li><li><div class="each_item"><div class="keep_ratio"><a href="/pages/page_323.php?"><img src="/image_bank/dummy/fskin_174/mlink_off_04.gif" himg="/image_bank/dummy/fskin_174/mlink_on_04.gif" border="0" class="notice_thumb" alt=""></a></div></div></li></ul></div>
<script type="text/javascript">
var $cell_id_1200;
jQuery(document).ready(function($) {
$cell_id_1200=$('#notice_1200').parent();
});
jQuery(window).load(function() {

});
jQuery(document).ready(function($){setInterval(function(){if($cell_id_1200.width()<800){$(".notice_1200 li").css("width","50%");}else if($cell_id_1200.width()<1200){$(".notice_1200 li").css("width","25%");}else{$(".notice_1200 li").css("width","25%");}},100);});</script>
