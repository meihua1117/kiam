<?
	if($_SESSION['level']=='10') {
		$wiz_lan2=get_lang_file($ui_homepath.'/cgi_bank/notice/language'.$masterinfo['lang'].'.inc');
		$tarr=wizpath_encode($ui_pages);
		?>
		<div style="position:absolute;right:0;z-index:999;"><input type="button" class="ozbutton white small" value="<?=$wiz_lan2['setting']?>" onclick="window.open('/cgi_bank_wiz/html/html_1_config.php?cq=<?=base64_encode("5471|$tarr[1]|$menu_id|68")?>','config','status=1,scrollbars=1,resizable=1,width=700,height=670');"></div>
		<?
	}
	?><div class="smartOutput htmlEditor float_clear"><table align="center" border="0" cellpadding="10" cellspacing="1" style="background-color:#c9ced5;border-spacing:1px;width:60%;">
	<thead>
		<tr>
			<th scope="col" style="background-color: rgb(238, 242, 249); padding: 10px;"><span style="font-size:17px;">설명회 일시</span></th>
			<th scope="col" style="background-color: rgb(238, 242, 249); padding: 10px;"><span style="font-size:17px;">신청하기<a id="셀링_신청하기" name="셀링_신청하기"></a></span></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 10px; text-align: center;"><span style="font-size:20px;">2019년 8월 17일(토) <span style="font-size:20px;">오전 10:30~12:30</span></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 10px; text-align: center;"><span style="font-size:17px;"><a class="ozbutton blue big " href="http://www.onepagebook.net/opb/popup/speech13.html?pcode=ld&amp;sp=sp2019.08.17" target="_blank">신청하기</a></span></td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 10px; text-align: center;"><s><span style="color:#7f8c8d;"><span style="font-size:20px;">2019년 8월 14일(수) <span style="font-size:20px;">오전 10:30~12:30</span></span></span></s></td>
			<td style="background-color: rgb(255, 255, 255); padding: 10px; text-align: center;">
			<h4 class="oz_txt_h4"><span style="color:#999999;"><strong>완료</strong></span></h4>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 10px; text-align: center;"><span style="color:#999999;"><s><span style="font-size:20px;">2019년 8월 10일(토) 오후 4:00~6:00</span></s></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 10px; text-align: center;">
			<h4 class="oz_txt_h4"><span style="color:#999999;"><strong>완료</strong></span></h4>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 10px; text-align: center;"><span style="color:#999999;"><s><span style="font-size:20px;">2019년 8월 7일(수) 오전 10:30~12:30</span></s></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 10px; text-align: center;">
			<h4 class="oz_txt_h4"><span style="color:#999999;"><strong>완료</strong></span></h4>
			</td>
		</tr>
		<tr>
			<td style="background-color: rgb(255, 255, 255); padding: 10px; text-align: center;"><span style="color:#999999;"><s><span style="font-size:20px;">2019년 8월 3일(토) 오후 4:00~6:00</span></s></span></td>
			<td style="background-color: rgb(255, 255, 255); padding: 10px; text-align: center;">
			<h4 class="oz_txt_h4"><span style="color:#999999;"><strong>완료</strong></span></h4>
			</td>
		</tr>
	</tbody>
</table>

<p style="text-align: center;">&nbsp;</p>

<p>&nbsp;</p>
</div>