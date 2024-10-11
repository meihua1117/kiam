<?
$path="./";
include_once "_head.php";
if(!$_SESSION['one_member_id']){
?>
	<script language="javascript">
	location.replace('/ma.php');
	</script>
<?
	exit;
}
$sql="select * from Gn_Member  where mem_id='".$_SESSION['one_member_id']."'";
$sresul_num=mysqli_query($self_con,$sql);
$data=mysqli_fetch_array($sresul_num);
?>
<script type="text/javascript" src="/js/mms_send.js"></script>
<script>
function copyHtml(){
	var trb = $.trim($('#sHtml').html());
	var IE=(document.all)?true:false;
	if (IE) {
		if(confirm("이 소스코드를 복사하시겠습니까?")) {
		window.clipboardData.setData("Text", trb);
		}
	} else {
		temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", trb);
	}
}
$(function(){
	$(".popbutton").click(function(){
		$('.ad_layer_info').lightbox_me({
			centered: true,
			onLoad: function() {}
		});
	});
});
</script>
<style>
	/*.pop_right {
		position: relative;
		right: 2px;
		display: inline;
		margin-bottom: 6px;
		width: 5px;
	}*/
</style>
<div class="big_div">
	<div class="big_sub">
		<?php include "mypage_step_navi.php";?>
		<div class="m_div">
			<?php include "mypage_left_menu.php";?>
			<div class="m_body">
				<form name="pay_form" action="" method="post" class="my_pay">
					<input type="hidden" name="page" value="<?=$page?>" />
					<input type="hidden" name="page2" value="<?=$page2?>" />
					<div class="a1" style="margin-top:50px; margin-bottom:15px">
						<li style="float:left;">
							<div class="popup_holder popup_text">예약메시지 발송예정내역
								<div class="popupbox" style="display:none;height: 56px;width: 220px;left: 215px;top: -37px;">예약문자가 이벤트 신청고객에게 회차별 발송 예정인 정보를 보여줍니다.<br><br>
									<a class = "detail_view" href="https://tinyurl.com/2p94y2kw" target="_blank">[자세히 보기]</a>
								</div>
							</div>
						</li>
						<li style="float:right;">
						</li>
						<p style="clear:both"></p>
					</div>
					<div class="p1">
						<select name="search_key" class="select">
							<option value="send_num" <?if($_REQUEST[search_key] == "send_num") echo "selected";?>>발송번호</option>
							<option value="recv_num" <?if($_REQUEST[search_key] == "recv_num") echo "selected";?>>수신번호</option>
							<option value="sms.event_name_eng"   <?if($_REQUEST[search_key] == "sms.event_name_eng") echo "selected";?>>수신키워드</option>
						</select>
						<input type="text" name="search_text" placeholder="" id="search_text" value="<?=$_REQUEST[search_text]?>" style="height:30px;"/>
						<a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
						<div style="float:right;">
							<div class="popup_holder" style="display:inline-block"> <!--Parent-->
								<input type="button" value="발송결과내역" class="button" onclick="location.href='mypage_send_list.php'">
							</div>							
						</div>
					</div>
					<div >
                            <a href="?" style="color:">전체내역</a> &nbsp;|&nbsp; 
							<a href="?channel=2" style="color:<?=$_REQUEST['channel']==2?"#f00":""?>">오토회원가입신청</a>&nbsp;|&nbsp;
							<a href="?channel=3" style="color:<?=$_REQUEST['channel']==3?"#f00":""?>">고객신청</a>&nbsp;|&nbsp;
							<a href="?channel=4" style="color:<?=$_REQUEST['channel']==4?"#f00":""?>">신청스텝예약</a>&nbsp;|&nbsp;
							<a href="?channel=8" style="color:<?=$_REQUEST['channel']==8?"#f00":""?>">새디비예약</a>
					</div>
					<div>
						<div class="p1"></div>
							<div>
								<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td style="width:7%;"><label><input type="checkbox" onclick="check_all(this,'fs_idx');" value="0"/>번호</label></td>
										<td style="width:7%;">발송자명</td>
										<td style="width:10%;">발송번호</td>
										<td style="width:10%;">수신번호</td>
										<td style="width:10%;">수신자명</td>
										<td style="width:5%;">세트</td>
										<td style="width:5%;">회차</td>
										<td style="width:7%;">문자제목</td>
										<td style="width:7%;">문자내용</td>
										<td style="width:5%;"><?=$_REQUEST['status2']=='2'?"예약일시":"첨부파일"?></td>
										<td style="width:6%;">신청키워드</td>
										<td style="width:10%;">발송예정일시</td>
										<td style="width:7%;">
											<a href="javascript:fs_multi_del()">삭제</a>
										</td>
									</tr>
									<?
									$sql_serch= " 1=1 ";
									$sql_serch.=" and mms.mem_id ='{$_SESSION['one_member_id']}' and sms_detail_idx is not null";
									$sql_serch .=" and result = 1 and reservation > now()";
									$sql_table = " Gn_MMS ";
									if( $_REQUEST[search_text])
									{
										$sql_serch.=" and (".$_REQUEST[search_key]." like '%$_REQUEST[search_text]%') ";
										//$sql_serch.=" and (send_num like '$_REQUEST[search_text]%' or recv_num like '$_REQUEST[search_text]%'   or content like '%$_REQUEST[search_text]%' ) ";

									}
									if($_REQUEST['channel'])
										$sql_serch .= " and type='$_REQUEST[channel]' ";
									// 상태 검색 추가
									if($_REQUEST['result'] == 1)
										$sql_serch .= " and result = 0 and up_date is not null ";
									elseif($_REQUEST['result'] == 2)
										$sql_serch .= " and result = 1 and up_date is null ";
									elseif($_REQUEST['result'] == 3)
										$sql_serch .= " and result = 3";
									$sql="select count(*) as cnt from Gn_MMS mms left join Gn_event_sms_info sms 
									on sms.sms_idx=mms.sms_idx where $sql_serch ";
									$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
									$row=mysqli_fetch_array($result);
									mysqli_free_result($result);
									$intRowCount=$row['cnt'];
									if (!$_POST['lno'])
										$intPageSize =20;
									else
										$intPageSize = $_POST['lno'];
									if($_POST['page']){
										$page=(int)$_POST['page'];
										$sort_no=$intRowCount-($intPageSize*$page-$intPageSize);
									}else{
										$page=1;
										$sort_no=$intRowCount;
									}
									if($_POST['page2'])
										$page2=(int)$_POST['page2'];
									else
										$page2=1;
									$int=($page-1)*$intPageSize;
									if($_REQUEST['order_status'])
										$order_status=$_REQUEST['order_status'];
									else
										$order_status="desc";
									if($_REQUEST['order_name'])
										$order_name=$_REQUEST['order_name'];
									else
										$order_name="reservation";
									$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);
									$sql="select mms.*,sms.event_name_eng sp from Gn_MMS mms left join  Gn_event_sms_info sms 
									   on sms.sms_idx=mms.sms_idx where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
									$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
									if($intRowCount){
										$c=0;
										while($row=mysqli_fetch_array($result))
										{
											$sql_s="select * from Gn_MMS_status where idx='$row[idx]' ";
											$resul_s=mysqli_query($self_con,$sql_s);
											$row_s=mysqli_fetch_array($resul_s);
											mysqli_free_result($resul_s);

											$sql_n="select mem_name from Gn_Member where mem_id='$row[mem_id]' ";
											$resul_n=mysqli_query($self_con,$sql_n);
											$row_n=mysqli_fetch_array($resul_n);
											mysqli_free_result($resul_n);

											$recv_cnt=explode(",",$row['recv_num']);
											$sql_as="select count(idx) as cnt from Gn_MMS_status where idx='$row[idx]' ";
											$resul_as=mysqli_query($self_con,$sql_as);
											$row_as=mysqli_fetch_array($resul_as);
											$status_total_cnt = $row_as[0];

											$sql_cs="select count(idx) as cnt from Gn_MMS_status where idx='$row[idx]' and status='0'";
											$resul_cs=mysqli_query($self_con,$sql_cs);
											$row_cs=mysqli_fetch_array($resul_cs);
											$success_cnt = $row_cs[0];

											$sql_sn="select * from Gn_MMS where idx='$row[idx]' ";
											$resul_sn=mysqli_query($self_con,$sql_sn);
											$row_sn=mysqli_fetch_array($resul_sn);
											$recv_cnt=explode(",",$row_sn['recv_num']);
											$total_cnt = count($recv_cnt);

											// $sql_sn="select * from Gn_event_request where request_idx='$row[request_idx]' ";
											// $sresul=mysqli_query($self_con,$sql_sn);
											// $srow=mysqli_fetch_array($sresul);

											$sql_sn="select count(*) as cnt from Gn_event_sms_step_info where sms_idx='$row[sms_idx]' ";
											$sresul=mysqli_query($self_con,$sql_sn);
											$crow=mysqli_fetch_array($sresul);
											$total_cnt = $crow['cnt'];

											$sql_sn="select step from Gn_event_sms_step_info where sms_detail_idx='$row[sms_detail_idx]' ";
											$sresul=mysqli_query($self_con,$sql_sn);
											$crow=mysqli_fetch_array($sresul);

											$sql_n="select mem_name from Gn_Member where REPLACE(mem_phone, '-', '')='{$row['recv_num']}' ";
											$resul_rn=mysqli_query($self_con,$sql_n);
											$row_rn=mysqli_fetch_array($resul_rn);
											mysqli_free_result($resul_rn);
									?>
											<tr>
												<td><label><input type="checkbox" name="fs_idx" value="<?=$row[idx]?>"><?=$sort_no?></label></td>
												<td><?=$row_n['mem_name']?></td>
												<td><?=$row['send_num']?></td>
												<td style="font-size:12px;">
													<?$recv_cnt=explode(",",$row['recv_num']);?>
													<a href="javascript:void(0)" onclick="show_recv('show_recv_num','<?=$c?>','수신번호')"><?=str_substr($row['recv_num'],0,14,'utf-8')?></a>
													<span style="color:#F00;">(<?=count($recv_cnt)?>)</span>
													<input type="hidden" name="show_recv_num" value="<?=$row['recv_num']?>"/>
												</td>
												<td>
													<?
														if($row_rn['mem_name'] != '')
															echo $row_rn['mem_name'];
														else{
															$group_idx = $row['request_idx'] * -1;
															$sql_group="select * from Gn_MMS_GROUP where idx='$group_idx' ";
															$gresult=mysqli_query($self_con,$sql_group);
															$grow=mysqli_fetch_array($gresult);
															echo $grow['grp'];
														}
													?>
												</td>
												<td><?=$total_cnt;?></td>
												<td><?=$crow['step'];?></td>
												<td>
													<a href="javascript:void(0)" onclick="show_recv('show_title','<?=$c?>','문자제목')"><?=str_substr($row[title],0,14,'utf-8')?></a>
													<input type="hidden" name="show_title" value="<?=$row[title]?>"/>
												</td>
												<td style="font-size:12px;">
													<a href="javascript:void(0)" onclick="show_recv('show_content','<?=$c?>','문자내용')"><?=str_substr($row['content'],0,30,'utf-8')?></a>
													<input type="hidden" name="show_content" value="<?=$row['content']?>"/>
												</td>
												<?if($_REQUEST['status2']=='2'){?>
													<td style="width:5%;">
														<?if($row['up_date']!=''&&$row[result]==0){?>완료<?}elseif($row['up_date']==''&&$row[result]==1){?>대기<?}elseif($row[result]==3){?>실패<?}?>
													</td>
												<?}?>
												<td>
													<?
													if ($_REQUEST['status2']==2){
														echo substr($row[reservation],0,16);
													}else{?>
														<a href="javascript:void(0)" onclick="show_recv('show_jpg','<?=$c?>','첨부파일')"><?=str_substr($row[jpg],0,20,'utf-8')?></a>
														<input type="hidden" name="show_jpg" value="<?=$row[jpg]?>"/>
														<a href="javascript:void(0)" onclick="show_recv('show_jpg1','<?=$c?>','첨부파일')"><?=str_substr($row[jpg1],0,20,'utf-8')?></a>
														<input type="hidden" name="show_jpg1" value="<?=$row[jpg1]?>"/>
														<a href="javascript:void(0)" onclick="show_recv('show_jpg2','<?=$c?>','첨부파일')"><?=str_substr($row[jpg2],0,20,'utf-8')?></a>
														<input type="hidden" name="show_jpg2" value="<?=$row[jpg2]?>"/>
													<?}?>
												</td>
												<td><?=$row['sp'];?></td>
												<td style="font-size:12px;"><?=substr($row[reservation],0,16)?></td>
												<td>
													<a href="javascript:fs_del_num('<?=$row[idx]?>')">삭제</a>
												</td>
											</tr>
									<?
											$c++;
											$sort_no--;
										}
									?>
										<tr>
											<td colspan="13">
												<?page_f($page,$page2,$intPageCount,"pay_form");?>
											</td>
										</tr>
									<?
									}else{
									?>
										<tr>
											<td colspan="13">
												검색된 내용이 없습니다.
											</td>
										</tr>
									<?}?>
								</table>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	function fs_multi_del(){
		if(confirm('삭제하시겠습니까?'))
		{
		 	var check_array = $(".list_table").children().find(":checkbox");
            var no_array = [];
            var index = 0;
            check_array.each(function(){
                if($(this).prop("checked") && $(this).val() > 0)
                    no_array[index++] = $(this).val();
            });
            console.log(no_array);
			$.ajax({
				 type:"POST",
				 url:"/ajax/ajax_session.php",
				 data:{
						fs_del_num_s:no_array.toString()
					  },
				 success:function(data){$("#ajax_div").html(data)}
			})
		}
	}
	function change_message(form) {
		if(form.intro_message.value == "") {
			alert('정보를 입력해주세요.');
			form.intro_message.focus();
			return false;
		}

		$.ajax({
			type:"POST",
			url:"ajax/ajax.php",
			data:{
				mode : "intro_message",
				intro_message: form.intro_message.value
			},
			success:function(data){
				$("#ajax_div").html(data);
				alert('저장되었습니다.');
			}
		});
		return false;
	}
	function showInfo() {
		if($('#outLayer').css("display") == "none") {
			$('#outLayer').show();
		} else {
			$('#outLayer').hide();
		}
	}
	function monthly_remove(no) {
		if(confirm('정기결제 해지신청하시겠습니까?')) {
			$.ajax({
				type:"POST",
				url:"ajax/ajax_add.php",
				data:{
					mode:"monthly",
					no:no
				},
				success:function(data){alert('신청되었습니다.');location.reload();}
			});
		}
	}
</script>      
<script language="javascript" src="./js/rlatjd_fun.js?m=<?php echo time();?>"></script>
<script language="javascript" src="./js/rlatjd.js?m=<?php echo time();?>"></script>
<!--div id='open_recv_div' class="open_1">
<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
<li class="open_recv_title open_2_1"></li>
<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>
</div>
<div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">

</div>
</div-->
<?include_once "_foot.php";?>