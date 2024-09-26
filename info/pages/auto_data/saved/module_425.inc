<?
	if($_SESSION['level']=='10') {
		$wiz_lan2=get_lang_file($ui_homepath.'/cgi_bank/notice/language'.$masterinfo['lang'].'.inc');
		$tarr=wizpath_encode($ui_pages);
		?>
		<div style="position:absolute;right:0;z-index:999;"><input type="button" class="ozbutton white small" value="<?=$wiz_lan2['setting']?>" onclick="window.open('/cgi_bank_wiz/html/html_1_config.php?cq=<?=base64_encode("425|$tarr[1]|$menu_id|84")?>','config','status=1,scrollbars=1,resizable=1,width=700,height=670');"></div>
		<?
	}
	?><div class="smartOutput htmlEditor float_clear"><hr />
<p style="text-align: center;"><span style="font-size:28px;"><span style="color:#ffffff;"><span style="letter-spacing:8px;"><span style="font-family:_오성과한음B;">미래역량 콘서트 신청하기</span></span></span></span></p>

<hr /><!-- <p style="text-align: center;"><a href="http://www.onepagebook.net/opb/popup/speech3.html?pcode=langing&amp;sp=18.03.03(Sat)Cognitive coaching Master" target="_blank"><img alt="" src="/image/신청하기.png" /></a></p>
<!-- <div class="inbox">
<form action="" id="dform1" method="post" name="dform1">
<p><input name="mode" type="hidden" value="speech" /> <input name="pcode" type="hidden" value="3key" /></p>

<p>&nbsp;</p>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="70%">
	<tbody>
		<tr>
			<td align="center" background="" height="185">
			<table border="0" cellpadding="0" cellspacing="0" height="156" width="560">
				<tbody>
					<tr>
						<td align="center">
						<table border="0" cellpadding="0" cellspacing="0" width="530">
							<tbody>
								<tr>
									<td height="21" width="65">
									<p style="text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:20px;">이름</span></span></p>
									</td>
									<td width="120"><input id="name" name="name" style="width:110px; height:30px; border:1px #cacaca solid;" type="text" value="" /></td>
									<td width="115">
									<p style="text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:20px;">신청자 메일</span></span></p>
									</td>
									<td width="230"><input id="email" name="email" style="width:205px; height:30px; border:1px #cacaca solid;" type="text" value="" /></td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
					<tr>
						<td align="center">&nbsp;</td>
					</tr>
					<tr>
						<td align="center">
						<table border="0" cellpadding="0" cellspacing="0" width="530">
							<tbody>
								<tr>
									<td height="22" width="65">
									<p style="text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:20px;">전화번호</span></span></p>
									</td>
									<td width="120"><input id="tel" name="tel" style="width:110px; height:30px; border:1px #cacaca solid;" type="tel" value="" /></td>
									<td width="115">
									<p style="text-align: center;"><span style="font-family:_나눔스퀘어B;"><span style="font-size:20px;">직업</span></span></p>
									</td>
									<td width="230"><input id="job" name="job" style="width:205px; height:30px; border:1px #cacaca solid;" type="text" value="" /></td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
					<tr>
						<td align="center">&nbsp;</td>
					</tr>
					<tr>
						<td align="center">
						<table border="0" cellpadding="0" cellspacing="0" width="530">
							<tbody>
								<tr>
									<td height="22" width="65">
									<p>신청강좌</p>
									</td>
									<td><input id="speech" name="speech" readonly="readonly" style="width:60%x; height:30px; border:1px #cacaca solid;" type="text" value="18.03.03(Sat)Cognitive coaching Master" /></td>
									<td width="40%">
									<p>&nbsp;</p>
									</td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
					<tr>
						<td align="center">&nbsp;</td>
					</tr>
					<tr>
						<td align="center" height="90"><input border="0" height="80" src="/image/신청하기.png" type="image" width="458" /></td>
					</tr>
				</tbody>
			</table>
			</td>
		</tr>
	</tbody>
</table>
</form>
</div>--></div>