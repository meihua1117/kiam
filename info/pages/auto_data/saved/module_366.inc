<?
	if($_SESSION['level']=='10') {
		$wiz_lan2=get_lang_file($ui_homepath.'/cgi_bank/notice/language'.$masterinfo['lang'].'.inc');
		$tarr=wizpath_encode($ui_pages);
		?>
		<div style="position:absolute;right:0;z-index:999;"><input type="button" class="ozbutton white small" value="<?=$wiz_lan2['setting']?>" onclick="window.open('/cgi_bank_wiz/html/html_1_config.php?cq=<?=base64_encode("366|$tarr[1]|$menu_id|218")?>','config','status=1,scrollbars=1,resizable=1,width=700,height=670');"></div>
		<?
	}
	?><div class="smartOutput htmlEditor float_clear"><h2 style="color: rgb(78, 95, 112); font-size: 24px; font-weight: bold; line-height: 1.6; text-align: center;"><strong><span style="color:#003366;"></span></strong><span style="color:#003366;"></span></h2>

<h2 style="color: rgb(78, 95, 112); font-size: 24px; font-weight: bold; line-height: 1.6; text-align: center;"><span style="color:#003366;"><span style="background-color:#ffffff;">알아내는 방법</span><span style="font-size:10px;"><span style="background-color:#ffffff;">을 </span></span><span style="font-size:13px;"><span style="background-color:#ffffff;">배우는</span></span><span style="background-color:#ffffff;"> 핵심역량교육</span></span></h2>

<p style="text-align: center;"><span style="background-color:#ffffff;"></span><span style="background-color:#ffffff;">AI시대!&nbsp; 새로운 교육의 패러다임!</span></p>

<h2 style="color: rgb(78, 95, 112); font-size: 24px; font-weight: bold; line-height: 1.6; text-align: center;"><span style="color:#003366;"></span></h2>
</div>