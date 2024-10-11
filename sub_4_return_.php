<?
$path="./";
include_once "_head.php";
if(!$_SESSION[one_member_id])
{
?>
<script language="javascript">
location.replace('/ma.php');
</script>
<?
exit;
}
extract($_REQUEST);

    $sql_serch= " 1=1 ";
	$sql_serch .= "AND mem_id='$_SESSION[one_member_id]'";
	if ($search_category != '') {
        if($startdate)
            $sql_serch .= " AND ".$search_category." >= '$startdate 00:00:00'";
        if($enddate)
            $sql_serch .= " AND ".$search_category." <= '$enddate 23:59:59'";
    }

	/*
	if($_REQUEST[status2]==2){ //예약내역
		$sql_serch .= "and ifnull(reservation,'') <> '' ";
		$sql_table = " ((SELECT `idx`, `mem_id`, `send_num`, `recv_num`, `uni_id`, `content`, `title`, `type`, `delay`, `delay2`, `close`, `jpg`, `jpg1`, `jpg2`, `result`, `reg_date`, `up_date`, `url`, `reservation` FROM Gn_MMS_ReservationFail ) ";
		$sql_table .= "UNION (SELECT `idx`, `mem_id`, `send_num`, `recv_num`, `uni_id`, `content`, `title`, `type`, `delay`, `delay2`, `close`, `jpg`, `jpg1`, `jpg2`, `result`, `reg_date`, `up_date`, `url`, `reservation` FROM Gn_MMS WHERE IFNULL(reservation, '') <> '')) as R ";
	}else{
		$sql_serch .=" and result >= 0 ";
		$sql_table = " Gn_MMS ";
	}
	*/
	$sql_table = " Gn_MMS ";
	if($_REQUEST[status2]==1){ //예약내역
	    $sql_serch .= " and title = 'app_check_process'";
	    $sql_table = " Gn_MMS ";
	} else if($_REQUEST[status2]==2){ //예약내역
	    $sql_serch .= " and title != 'app_check_process'";
	    $sql_table = " Gn_MMS ";
	}else if($_REQUEST[chanel]==2){
		$sql_serch .=" and result >= 0 and (type=2 || type=3 || type=4)";
		$sql_table = " Gn_MMS ";
	}else if($_REQUEST[chanel]==4){
		$sql_serch .=" and result >= 0 and type=6 ";
		$sql_table = " Gn_MMS ";
	}else if($_REQUEST[chanel]==9){
		$sql_serch .=" and result >= 0 and type=9 ";
		$sql_table = " Gn_MMS ";
	}else{
		$sql_serch .=" and result >= 0 and title != 'app_check_process' and (type=1 or type=0)";
		$sql_table = " Gn_MMS ";
	}
	
	if($_REQUEST[daily_type] == "normal"){
		$sql_serch .= " and sms_idx is null ";
	}
	else if($_REQUEST[daily_type] == "step"){
		$sql_serch .= " and sms_idx is not null ";
	}
	else{
		$sql_serch .= "";
	}
	if($_REQUEST[serch_fs_select] && $_REQUEST[serch_fs_text])
	{
		$sql_serch.=" and $_REQUEST[serch_fs_select] like '$_REQUEST[serch_fs_text]%' ";	
	}				
	// $sql_serch .= " and content != '".$_SESSION[one_member_id].", app_check_process'";
	// 상태 검색 추가
	// if($row[up_date]!=''&&$row[result]==0){echo"완료";}elseif($row[up_date]==''&&$row[result]==1){echo "대기";}elseif($row[result]==3){echo "실패";}
	if($_REQUEST['result'] == 1) {
	    $sql_serch .= " and result = 0 and up_date is not null ";
	} elseif($_REQUEST['result'] == 2) {
	    $sql_serch .= " and result = 1 and up_date is null ";
	} elseif($_REQUEST['result'] == 3) {
	    $sql_serch .= " and result = 3";
	} elseif($_REQUEST['result'] == 4) {
	    $sql_serch .= " and reservation != '' ";
	}					
	$sql="select count(*) as cnt from $sql_table where $sql_serch ";
	$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
	$row=mysqli_fetch_array($result);
	mysqli_free_result($result);
	$intRowCount=$row[cnt];
	if (!$_POST[lno]) 
	$intPageSize =20;
	else 
	$intPageSize = $_POST[lno];					
	if($_REQUEST[page])
	{
	  $page=(int)$_REQUEST[page];
	  $sort_no=$intRowCount-($intPageSize*$page-$intPageSize); 
	}
	else
	{
	  $page=1;
	  $sort_no=$intRowCount;
	}
	if($_REQUEST[page2])
	$page2=(int)$_REQUEST[page2];
	else
	$page2=1;
	$int=($page-1)*$intPageSize;
	if($_REQUEST[order_status])
	$order_status=$_REQUEST[order_status];
	else
	$order_status="desc"; 
	if($_REQUEST[order_name])
	$order_name=$_REQUEST[order_name];
	else
	$order_name="reg_date";
	$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
	$sql="select idx,send_num,recv_num,up_date,reg_date,reservation,title,content,result,jpg,jpg1,jpg2,count_start,count_end,grp_idx,type from $sql_table where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
	$excel_sql="select idx,send_num,recv_num,up_date,reg_date,reservation,title,content,result,jpg,jpg1,jpg2 from $sql_table where $sql_serch order by $order_name $order_status ";
	$excel_sql=str_replace("'","`",$excel_sql);				
	$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));		
	?>
					
	<style>
		.tooltiptext-bottom {
			width: 350px;
			font-size:15px;
			background-color: white;
			color: black;
			text-align: left;
			position: absolute;
			z-index: 200;
			top: 400px;
			left: 35%;
		}
		.title_app{
			text-align: center;
			background-color: rgb(247,131,116);
			padding: 10px;
			font-size: 20px;
			color: white;
			font-weight: 900;
		}
		.desc_app{
			padding: 15px;
		}
		.button_app{
			text-align: center;
			padding: 10px;
		}

		.detail_txt{
			text-align:left;
		}

		@media only screen and (max-width: 450px) {
			.tooltiptext-bottom{
				width: 80%;
				left:8%;
			}
		}
		#tutorial-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:150;text-align:center;display:none;background-color: grey;opacity: 0.7;}
	</style>
	<div class="ad_layer5">
		<div class="layer_in">
			<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
			<div class="pop_title">
				앱상태체크
			</div>
			<div class="info_text">
				<p>
					선택한 폰번호로 앱체크문자를 발송하여 사용가능 상태를 확인합니다. 5분 내에 수신 되면 on, 수신이 안되면 off<br />
				</p>
                <p>기본적으로 휴대폰이 WiFi 상태에서도 발송이 잘 되나 3G나 LTE 상태로 바꿔주시면 더욱 좋습니다.</p><br />
                <p>휴대폰의 스마트매니저 기능으로 인해 온리원문자 앱이 절전상태가 되어 발송이 되지 않을 수 있습니다. 설치 시 절전 해지를 꼭 해주세요.</p>
			</div>

		</div>
	</div>


	<div class="ad_layer6">
		<div class="layer_in">
			<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
			<div class="pop_title">
				미전송문자삭제
			</div>
			<div class="info_text">
				<p>
					문자앱에서 가져가지 않고 서버에 대기중인 문자를 삭제합니다.
				</p>
			</div>

		</div>
	</div>
<div class="big_div">
<div class="big_1">
    	<div class="m_div">
    	    
        	<div class="left_sub_menu">
                <a href="./">홈</a> > 
                <a href="sub_4_return_.php">발신내역</a>
            </div>
            <div class="right_sub_menu">
                <!--
                <a href="sub_1.php">온리원문자</a> &nbsp;|&nbsp; 
                <a href="sub_2.php">온리원디버</a> &nbsp;|&nbsp;
                -->
                <a href="sub_1.php">폰문자소개</a> ㅣ<a href="sub_5.php">휴대폰등록</a> ㅣ <a href="sub_6.php">문자발송</a> ㅣ <a href="sub_4_return_.php">발신내역</a> ㅣ <a href="sub_4.php?status=5&status2=3">수신내역</a> ㅣ <a href="sub_4.php?status=6">수신여부</a>
            </div>
            
            <p style="clear:both;"></p>
    	</div>
    </div>
					<div class="m_div sub_4c">
					<div class="sub_4_4_t1">
                    <div class="sub_4_4_t2">
	                	<!--div class="sub_4_1_t3"><a href="sub_4.php?status=4" style="color:<?=$_REQUEST[status2]==''?"#000":""?>">발신내역 확인</a> 
	                	    <&nbsp;|&nbsp; <a href="sub_4.php?status=4&status2=2" style="color:<?=$_REQUEST[status2]==2?"#000":""?>">예약내역 확인</a></div>-->
	                	    
                    	<div class="sub_4_1_t3">
                            <a href="sub_4.php?status=4" style="color:">전체내역</a> &nbsp;|&nbsp; 
                            <a href="sub_4.php?status=4&status2=1" style="color:<?=$_REQUEST[status2]==1?"#000":""?>">앱체크내역</a> &nbsp;|&nbsp; 
                            <a href="sub_4_return_.php" style="color:<?=!$_REQUEST[chanel]?"#000":""?>">발신/회신문자</a>&nbsp;|&nbsp;
							<a href="sub_4_return_.php?chanel=2" style="color:<?=$_REQUEST[chanel]==2?"#000":""?>">스텝문자</a>&nbsp;|&nbsp;
							<a href="sub_4_return_.php?chanel=4" style="color:<?=$_REQUEST[chanel]==4?"#000":""?>">데일리문자</a>&nbsp;|&nbsp;
							<a href="sub_4_return_.php?chanel=9" style="color:<?=$_REQUEST[chanel]==9?"#000":""?>">콜백문자</a>&nbsp;|&nbsp; 
							<a href="sub_4.php?status=4&status2=10" style="color:<?=$_REQUEST[status2]==10?"#000":""?>">폰문자인증내역</a>
                        </div>

	                    <div class="sub_4_4_t2">
	                        <form name="sub_4_form" id="sub_4_form">
                                <div class="sub_4_4_t3">
                                    <select name="serch_fs_select">
                                        <option value="">선택하세요</option>
                                        <?
                                            $select_fs_arr=array("send_num"=>"발신번호","recv_num"=>"수신번호","title"=>"문자제목","content"=>"문자내용");
                                            foreach($select_fs_arr as $key=>$v)
                                            {
                                                $selected=$_REQUEST[serch_fs_select]==$key?"selected":"";
                                                ?>
                                                <option value="<?=$key?>" <?=$selected?> ><?=$v?></option>
                                                <?
                                            }
                                        ?>
                                    </select>
                                    <select name="result">
                                        <option value="">전체</option>
                                        <option value="1" <?=$_REQUEST['result']==1?"selected":""?>>성공</option>
                                        <option value="2" <?=$_REQUEST['result']==2?"selected":""?>>대기</option>
                                        <option value="3" <?=$_REQUEST['result']==3?"selected":""?>>실패</option>
                                        <option value="4" <?=$_REQUEST['result']==4?"selected":""?>>예약</option>
                                    </select>
                                    <input type="text" name="serch_fs_text" value="<?=$_REQUEST[serch_fs_text]?>" />
                                    <?
                                        if ($_REQUEST['chanel'] != 9) {
                                            ?>
                                            <select name="search_category">
                                                <option value="">선택하세요</option>
                                                <option value="reservation" <?= $_REQUEST['search_category'] == 'reservation' ? "selected" : "" ?>>
                                                    발송예정시간
                                                </option>
                                                <option value="up_date" <?= $_REQUEST['search_category'] == 'up_date' ? "selected" : "" ?>>
                                                    발송완료시간
                                                </option>
                                            </select>
                                            <input type="date" name="startdate" value="<?= $_REQUEST[startdate] ?>"
                                                   style="padding: 6px">
                                            <input type="date" name="enddate" value="<?= $_REQUEST[enddate] ?>"
                                                   style="padding: 6px">
                                            <?
                                        }
                                    ?>
                                    <a href="javascript:void(0)" onclick="sub_4_form.submit();"><img src="images/sub_button_103.jpg" /></a>
                                </div>
                                <input type="hidden" name="order_status" value="<?=$order_status?>"/>
                                <input type="hidden" name="page" value="<?=$page?>" />
								<input type="hidden" name="chanel" value="<?=$_REQUEST[chanel]?>" />
								<input type="hidden" name="daily_type" value="<?=$_REQUEST[daily_type]?>" />
                                <input type="hidden" name="page2" value="<?=$page2?>" />        
                            </form>	                        
							<?
							if($_REQUEST[chanel] == 2 || $_REQUEST[chanel] == 4 || (!$_REQUEST[chanel] && !$_REQUEST[status])){
							?>
            				<div class="button_box">
								<?if($_REQUEST[chanel] == 4){?>
								<div class="left_box">
            						<span class="button_type"><a href="javascript:void(0)" onclick="show_daily('all')">전체보기</a></span>
									<span class="button_type"><a href="javascript:void(0)" onclick="show_daily('normal')">데일리발송</a></span>
									<span class="button_type"><a href="javascript:void(0)" onclick="show_daily('step')">데일리스텝</a></span>
            					</div>
								<?}?>
								</form>
            					<div class="right_box">
            						<span class="button_type"><a href="javascript:void(0)" onclick="all_msg_del('<?=$_REQUEST[chanel]?>')">전체삭제</a></span>
									<span class="button_type"><a href="javascript:void(0)" onclick="selected_msg_del()">선택삭제</a></span>
            					</div>
            					</div>
							<?}?>
	                        <div class="sub_4_4_t4">
	                            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
	                                <tr>
	                                    <td style="width:7%;"><label><input type="checkbox" onclick="check_all(this,'fs_idx');" />번호</label></td>
	                                    <td style="width:8%;">소유자명</td>
	                                    <td style="width:8%;">발신번호</td>
	                                    <td style="width:10%;">수신번호</td>
	                                    <td style="width:8%;">문자제목</td>
	                                    <td style="width:16%;">문자내용</td>
	                                    <?if($_REQUEST[status2]=='2'){?>
	                                    <td style="width:3%;">상태</td>
	                                    <?}?>
	                                    <td style="width:5%;"><?=$_REQUEST[status2]=='2'?"예약일시":"첨부파일"?></td>
	                                    <td style="width:8%;">PC전송시간</td>
	                                    <!--<td style="width:8%;">앱수신시간</td>-->
										<?=$_REQUEST[status2]==''?'<td style="width:8%;">발송예정시간</td>':""?>
                                        <td style="width:8%;">발송완료시간</td>
	                                    <td style="width:8%;">성공/실패</td>
	                                    <td style="width:5%;">회신수</td>
	                                </tr>
	                                <?
									if($intRowCount)
									{
										$c=0;
										while($row=mysqli_fetch_array($result))
										{
											$sql_s="select status,regdate from Gn_MMS_status where idx='$row[idx]' ";
											$resul_s=mysqli_query($self_con,$sql_s);
											$row_s=mysqli_fetch_array($resul_s);
											mysqli_free_result($resul_s);
																					    
											$sql_n="select memo from Gn_MMS_Number where mem_id='$_SESSION[one_member_id]' and sendnum='$row[send_num]' ";
											$resul_n=mysqli_query($self_con,$sql_n);
											$row_n=mysqli_fetch_array($resul_n);
											mysqli_free_result($resul_n);
											
											$recv_num = explode(",",$row[recv_num]);
											$recv_num_in = "'".implode("','", $recv_num)."'";
											$date = $row['up_date'];

											$sql="select count(seq) as cnt from call_app_log where api_name='receive_sms' and LENGTH(recv_num) >= 10 and  send_num='$row[send_num]' and recv_num in ($recv_num_in) and recv_num like '01%'  and regdate >= '$date' and sms not like '[%'";
											$kresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
											$krow=mysqli_fetch_array($kresult);
											$intRowCount=$krow[cnt];											
											if($date == "") $intRowCount = "";
											
                            				$sql_as="select count(idx) as cnt from Gn_MMS_status where idx='$row[idx]' ";
                            				$resul_as=mysqli_query($self_con,$sql_as);
                            				$row_as=mysqli_fetch_array($resul_as);
                            				$status_total_cnt = $row_as[0];											
                            				
                            				$sql_cs="select count(idx) as cnt from Gn_MMS_status where idx='$row[idx]' and status='0'";
                            				$resul_cs=mysqli_query($self_con,$sql_cs);
                            				$row_cs=mysqli_fetch_array($resul_cs);
                            				$success_cnt = $row_cs[0];

                            				$sql_sn="select recv_num from Gn_MMS where idx='$row[idx]' ";
                            				$resul_sn=mysqli_query($self_con,$sql_sn);
											$row_sn=mysqli_fetch_array($resul_sn);											
                            				$recv_cnt=explode(",",$row_sn[0]);                          				
                            				$total_cnt = count($recv_cnt);

									        $reg_date = strtotime($row[reg_date]);
											$reg_date_1hour = strtotime("$row[reg_date] +1hours"); 								
									        if($success_cnt > $total_cnt) $success_cnt = $total_cnt;
										?>
										<tr>
											<td><label><input type="checkbox" name="fs_idx" value="<?=$row[idx]?>" /><?=$sort_no?></label></td>
											<td><?=$row_n[memo]?></td>											
	                                        <td>
												<?=$row[send_num]?>
												<?if(!$_REQUEST[chanel]){
												if(!$row[type]){?>
												<span style="color:#3f50cd;">(개별발송)</span>
												<?}
												else{?>
												<span style="color:#3f50cd;">(묶음발송)</span>
												<?}}?>
											</td>
											<td style="font-size:12px;">
												<a href="javascript:void(0)" onclick="show_recv('show_recv_num','<?=$c?>','수신번호')"><?=str_substr($row[recv_num],0,14,'utf-8')?></a>
												<span style="color:#F00;">(<?=$total_cnt?>)</span>
												<?if($row[grp_idx] && !$_REQUEST[chanel]){?>
												<span style="color:#3f50cd;" onclick="show_grp_detail('<?=$row[grp_idx]?>', '<?=$row[count_start]?>', '<?=$row[count_end]?>')">[보기]</span>
												<?}?>
												<input type="hidden" name="show_recv_num" value="<?=$row[recv_num]?>"/>
											</td>
											<td>
												<a href="javascript:void(0)" onclick="show_recv('show_title','<?=$c?>','문자제목')"><?=str_substr($row[title],0,14,'utf-8')?></a>
												<input type="hidden" name="show_title" value="<?=$row[title]?>"/>
											</td>
											<td style="font-size:12px;">
												<a href="javascript:void(0)" onclick="show_recv('show_content','<?=$c?>','문자내용')"><?=str_substr($row[content],0,30,'utf-8')?></a>
												<input type="hidden" name="show_content" value="<?=$row[content]?>"/>
											</td>
											<?if($_REQUEST[status2]=='2'){?>
		                                    <td style="width:5%;"><?if($row[up_date]!=''&&$row[result]==0){?>완료<?}elseif($row[up_date]==''&&$row[result]==1){?>대기<?}elseif($row[result]==3){?>실패<?}?></td>
		                                    <?}?>
											<td>
											    <?if ($_REQUEST[status2]==2){ 
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
                                            <td style="font-size:12px;"><?=substr($row[reg_date],0,16)?></td>
											<!--<td style="font-size:12px;"><?=substr($row[up_date],0,16)?></td>-->
											<td>
												<?=$row[reservation]?$row[reservation]:""?>
											</td>
											<td style="font-size:12px;">
											    <?if($row_s['status']=="-1") {?>
											    기본앱아님
											    <?}else{?>
											        <?=substr($row_s[regdate],0,16)?>
											        <?php 
											        if(time() > $reg_date_1hour && $row_s[regdate] == "" ) {
											            if($row[reservation] != "" && $row[reservation] > date("Y-m-d H:i:s")) {?>
    											            <a href="javascript:fs_del_num('<?=$row[idx]?>')">취소가능</a>
											            <?}else{?>
											                <?if($row[reservation]) {?>
											                예약
											                <?}else{?>
											                <a href="javascript:fs_del_num('<?=$row[idx]?>')">미수신</a>
											                <?}
											            }
											        }
											    }?>
											</td>
											<td style="font-size:12px;">
												<?
												if($success_cnt == 0 ){
                        					        if(time() > $reg_date_1hour && $row[up_date] == "") {
														if($row[reservation] > date("Y-m-d H:i:s")){}
														else{?>
                            					            <?="실패=".$row[up_date];?>
                            					        <?}
                        					        }else{
                            					        if(time() > $reg_date_1hour && $row[up_date] != "") {?>
                            					            발송실패
                            					        <?}else{
                            					            if($row[up_date] == "" && $row[reservation] < date("Y-m-d H:i:s")) {?>
                            					                <a href="sub_4_detail.php?idx=<?php echo $row['idx'];?>">발송중</a>
                            					            <?}
                            					        }
                        					        }
                        					    }else{?>												    
											    	<a href="sub_4_detail.php?idx=<?php echo $row['idx'];?>"><?=$success_cnt?>/<?php echo $total_cnt-$success_cnt;?> 
											    <?}?>
											    <?php if($row[reservation]) {?>예약<?php }?>
											</td>
											<td style="font-size:12px;">
												<a href="sub_4_return_detail.php?idx=<?php echo $row['idx']?>&send_num=<?=$row[send_num]?>"><?=$intRowCount;?></a> 
											</td>
										</tr>
										<?
										$c++;
										$sort_no--;
										}
										?>
										<tr>
	                                    	<td colspan="13">
	                                        <?
	                                        page_f($page,$page2,$intPageCount,"sub_4_form");
											?>
	                                        </td>
	                                    </tr>                                        
	                                    <?
									}else{
									?>
										<tr>
	                                    	<td colspan="13">
	                                        등록된 내용이 없습니다.
	                                        </td>
	                                    </tr>                                     
	                                <?	
									}
									?>
	                            </table>
	                        </div>
	                        <div class="sub_4_4_t5">
	                        	<div class="div_float_left">&nbsp;</div>
	                            <div class="div_float_right">
	                                <?if ($_REQUEST[status2]==''){?><a href="javascript:void(0)" onclick="excel_down('excel_down/fs_down.php?status=1')"><img src="images/sub_button_107.jpg" /></a><?}?>
	                                <!--<a href="javascript:void(0)" onclick="fs_del()"><img src="images/sub_button_109.jpg" /></a>-->
	                            </div>
	                            <p style="clear:both;"></p>                                
	                        </div>
	                    </div>
	                </div> 
                    </div>                   
	

        <form name="excel_down_form" action="" target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />        
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />        
        </form>
        <iframe name="excel_iframe" style="display:none;"></iframe>	
    </div>
</div> 
<span class="tooltiptext-bottom" id="modal_grp_detail" style="display:none;">
	<p class="title_app">발송상세보기<span onclick="hide_mail_box()" style="float:right;cursor:pointer;">X</span></p>
	<ul>
		<li class="detail_txt">그룹명 : <span id="grp_name"></span></li>
		<li class="detail_txt">총건수 : <span id="count_all"></span></li>
		<li class="detail_txt" hidden>발송건 : <span id="count_sent"></span></li>
	</ul>
</span>
<div id="tutorial-loading"></div>
<script>
function deleteAddress() {
    var checked = false;
    var seq = "";
    $('input[name=idx_box]').each(function() {
        if($(this).is(":checked") == true)  {
            checked = true;
            if(seq != "") seq += ",";
            seq += $(this).val();
        }
            
    });
    if(checked == false ) {
        alert('삭제할 번호를 선택해주세요');
        return;
    }

    var values = {"seq":seq}; 
    $.ajax({ type: 'post', 
             dataType: 'json', 
             url:'/ajax/truncate_num.php', 
             data: values, 
             success: function (dataObj) {
                
                
                alert('완료되었습니다.');
 
        },
        error: function (request, status, error) {
            console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
        }
    });    
    
}

function selected_msg_del(){
	var idx_arr = new Array();
	$('input[name=fs_idx]').each(function() {
		if($(this).is(":checked") == true) {
			idx_arr.push($(this).val());
		}
	});

	if(idx_arr.length == 0){
		alert("삭제할 메시지를 선택하세요.");
		return;
	}

	// console.log(idx_arr.toString());
	if(confirm("삭제 하시겠습니까?")){
		$.ajax({
			type:"POST",
			url:"/admin/ajax/delete_func.php",
			dataType:"json",
			data:{admin:0, delete_name:"mms_del", id:idx_arr.toString()},
			success: function(data){
				console.log(data);
				if(data == 1){
					alert('삭제 되었습니다.');
					window.location.reload();
				}
			}
		})
	}
}

function all_msg_del(type){
	// console.log(type);
	if(confirm("모든 페이지 데이타를 모두 삭제합니다.  삭제하시겠어요?")){
		$.ajax({
			type:"POST",
			url:"/admin/ajax/delete_func.php",
			dataType:"json",
			data:{admin:0, delete_name:"mms_del", mem_id:'<?=$_SESSION[one_member_id]?>', type:type},
			success: function(data){
				console.log(data);
				if(data == 1){
					alert('삭제 되었습니다.');
					window.location.reload();
				}
			}
		})
	}
}

function show_daily(type){
	location.href="sub_4_return_.php?serch_fs_select="+"<?=$_REQUEST[serch_fs_select]?>"+"&result="+"<?=$_REQUEST[result]?>"+"&serch_fs_text="+"<?=$_REQUEST[serch_fs_text]?>"+"&order_status="+"<?=$order_status?>"+"&page="+"<?=$page?>"+"&chanel="+"<?=$_REQUEST[chanel]?>"+"&daily_type="+type+"&page2="+"<?=$page2?>";
}

function show_grp_detail(grp_id, start, end){
	$.ajax({
		type:"POST",
		url:"/admin/ajax/mms_group_detail.php",
		dataType:"json",
		data:{grp_id:grp_id},
		success: function(data){
			$("#grp_name").text(data.grp);
			$("#count_all").text(data.count);
			if(start != 0 && end != 0){
				$(".detail_txt").eq(2).show();
				$("#count_sent").text(start + ' - ' + end);
			}
			else{
				$(".detail_txt").eq(2).hide();
			}
			$("#modal_grp_detail").show();
			$("#tutorial-loading").show();
			$('body,html').animate({
				scrollTop: 100 ,
				}, 100
			);
		}
	})
}

function hide_mail_box(){
	$("#modal_grp_detail").hide();
	$("#tutorial-loading").hide();
}
</script>
<?

include_once "_foot.php";
?>
