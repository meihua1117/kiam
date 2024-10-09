<?
$path="./"; 
include_once $_REQUEST[status]?"_head_open.php":"_head.php";
if($_REQUEST[status2]==2)
{
	if(!$fujia_pay)
	{
	//echo "권한이 없습니다.";	
	//exit;
	}
}
?>
<script language="javascript">
function deleteRow(idx) {
    if(confirm('삭제하시겠습니까?')) {
        location = "msg_search.proc.php?mode=del&idx="+idx+"&status2=<?php echo $_GET[status2];?>";
    }
}
</script>

<div class="big_sub">
	<div class="big_1">
    	<div class="m_div">
        	<div class="left_sub_menu">
                <a href="./">홈</a> > 
                <a href="msg_serch.php">문자검색</a>
            </div>
            <div class="right_sub_menu">
            <?
			if(!$_REQUEST[status])
			{
				?>            
                <a href="sub_4.php?status=1">휴대폰등록관리</a> &nbsp;|&nbsp; 
                <a href="sub_4.php?status=2">문자발송하기</a> &nbsp;|&nbsp;
                <a href="sub_4.php?status=3&status2=1">문자 저장관리</a> &nbsp;|&nbsp; 
                <a href="sub_4.php?status=4">발신내역 확인</a> &nbsp;|&nbsp; 
                <a href="sub_4.php?status=5">수신내역 확인</a> &nbsp;|&nbsp; 
                <a href="sub_4.php?status=6">수신거부 관리</a>
                <?
			}
			?>
            </div>
            <p style="clear:both;"></p>
    	</div>
   </div>
   <div class="m_div msg_serch">
        <form name="msg_serch_form" method="post">
					<div class="sub_4_3_t1">
                    	<div class="sub_4_1_t3">
						<a href="msg_serch.php?status=1&status2=1" <?if($_REQUEST[status2]==1) echo "style='color:#9dff9d'";?>>LMS 문자</a>
						<span style="margin: 20px"></span>
						<a href="msg_serch.php?status=1&status2=2" <?if($_REQUEST[status2]==2) echo "style='color:#9dff9d'";?>>포토 문자</a>
                        </div>
                        <?
						switch($_REQUEST[status2])
						{
							case 1:
								$sql_serch=" mem_id ='$_SESSION[one_member_id]' and msg_type='A' ";
								if($_REQUEST[lms_text])
								{
									if($_REQUEST[lms_select])
									$sql_serch.=" and {$_REQUEST[lms_select]} like '$_REQUEST[lms_text]%' ";
									else
									$sql_serch.=" and (message like '$_REQUEST[lms_text]%'  or title like '$_REQUEST[lms_text]%') ";
								}
								$sql="select count(idx) as cnt from Gn_MMS_Message where $sql_serch ";
								$result = mysql_query($sql) or die(mysql_error());
								$row=mysql_fetch_array($result);
								$intRowCount=$row[cnt];
								if($_POST[page])
								$page=(int)$_POST[page];
								else
								$page=1;
								if($_POST[page2])
								$page2=(int)$_POST[page2];
								else
								$page2=1;
								if (!$_POST[lno]) 
								$intPageSize =8;
								else 
								$intPageSize = $_POST[lno];
								$int=($page-1)*$intPageSize;
								if($_REQUEST[order_status])
								$order_status=$_REQUEST[order_status];
								else
								$order_status="desc"; 
								if($_REQUEST[order_name])
								$order_name=$_REQUEST[order_name];
								else
								$order_name="idx";
								$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
								$sql="select * from Gn_MMS_Message where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
								$result=mysql_query($sql) or die(mysql_error());							
							?>
                        <div class="sub_4_3_t2">
                        	<div class="sub_4_4_t3">
                                <select name="lms_select">
                                	<option value="">전체</option>
                                <?
									$select_lms_arr=array("title"=>"제목","message"=>"내용");
									foreach($select_lms_arr as $key=>$v)
									{
										$selected=$_REQUEST[lms_select]==$key?"selected":"";
										?>
										<option value="<?=$key?>" <?=$selected?>><?=$v?></option>                                        
                                        <?
									}
								?>
                                </select>
                                <input type="text" name="lms_text" value="<?=$_REQUEST[lms_text]?>" />
                                <input type="image" src="images/sub_button_703.jpg" />
                            </div>
                            <div class="d_div_font">LMS</div>
                        	<div class="sub_4_3_t2_left">
                            	<div><input type="text" name="title" placeholder="제목" value="" /></div>
                                <div>
                                	<textarea name="lms_content" placeholder="내용" onkeydown="textCounter(document.getElementsByName('lms_content')[0],'wenzi_cnt',2000,0)" onkeyup="textCounter(document.getElementsByName('lms_content')[0],'wenzi_cnt',2000,0)"></textarea>
                                </div>
                                <div style="margin-bottom:14px;"><span class="wenzi_cnt">0</span> Byte</div>
                                <div><a href="javascript:void(0)" onclick="move_parent('txt')"><img src="images/sub_04_02_img_21.jpg" /></a></div>
                            </div>
                            <div class="sub_4_3_t2_right">
                            	<?								
								if($intRowCount)
								{
									?>
                                    <div>
										<?
                                        $i=0;
                                        while($row=mysql_fetch_array($result))
                                        {
                                            ?>
                                        <div class="sub_4_3_t2_right_1">
                                            <div class="sub_4_3_t2_right_1_1">
                                                <div class="msg_title" style="margin-bottom:2px;"><?=str_replace("{|REP|}", "{|name|}", $row[title])?></div>
                                                <div class="msg_content"><?=html_entity_decode( str_replace("{|REP|}", "{|name|}", $row[message]))?></div>
                                            </div>
                                            <div class="sub_4_3_t2_right_1_3"><label><input type="radio" name="ab" onclick="show_msg('<?=$i?>')" />사용시 선택</label>
                                                <div class="sub_4_3_t2_right_1_3" style="float:right"><a href="javascript:deleteRow('<?=$row['idx']?>');">삭제</a></div>
                                            </div>
                                            
                                        </div>
                                        <?
                                        $i++;
                                        }
                                        ?>
                                        <p style="clear:both;"></p>
                                    </div>
                                    <div>
										<?
                                        page_f($page,$page2,$intPageCount,"msg_serch_form");
                                        ?>                                   
                                    </div>
                                    <?
								}
								else
								{
								?>
                                <div style="text-align:center">등록된 내용이 없습니다.</div>	
                                <?	
								}
								?>
                            </div>
                            <p style="clear:both;"></p>
                        </div>
							<?
							break;
							case 2:
								$sql_serch=" mem_id ='$_SESSION[one_member_id]' and msg_type='B' ";
									if($_REQUEST[lms_text])
									{
										if($_REQUEST[lms_select])
										$sql_serch.=" and {$_REQUEST[lms_select]} like '$_REQUEST[lms_text]%' ";
										else
										$sql_serch.=" and (message like '$_REQUEST[lms_text]%'  or title like '$_REQUEST[lms_text]%') ";
									}									
									$sql="select count(idx) as cnt from Gn_MMS_Message where $sql_serch ";
									$result = mysql_query($sql) or die(mysql_error());
									$row=mysql_fetch_array($result);
									$intRowCount=$row[cnt];
									if($_POST[page])
									$page=(int)$_POST[page];
									else
									$page=1;
									if($_POST[page2])
									$page2=(int)$_POST[page2];
									else
									$page2=1;
									if (!$_POST[lno]) 
									$intPageSize =8;
									else 
									$intPageSize = $_POST[lno];
									$int=($page-1)*$intPageSize;
									if($_REQUEST[order_status])
									$order_status=$_REQUEST[order_status];
									else
									$order_status="desc"; 
									if($_REQUEST[order_name])
									$order_name=$_REQUEST[order_name];
									else
									$order_name="idx";
									$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
									$sql="select * from Gn_MMS_Message where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
									$result=mysql_query($sql) or die(mysql_error());							
							?>
                        <div class="sub_4_3_t2">
                        	<div class="sub_4_4_t3">
                                <select name="photo_select">
                                	<option value="">전체</option>
                                <?
									$select_photo_arr=array("title"=>"제목","message"=>"내용");
									foreach($select_photo_arr as $key=>$v)
									{
										$selected=$_REQUEST[photo_select]==$key?"selected":"";
										?>
										<option value="<?=$key?>" <?=$selected?>><?=$v?></option>                                        
                                        <?
									}
								?>
                                </select>
                                <input type="text" name="photo_text" value="<?=$_REQUEST[photo_text]?>" />
                                <input type="image" src="images/sub_button_703.jpg" />
                            </div>
                            <div class="d_div_font">포토문자</div>
                        	<div class="sub_4_3_t2_left">
                            	<div><input type="text" name="title" placeholder="제목" value="" /></div>
                                <div style="margin-top:20px;">이미지미리보기</div>
                                <div class="img_view"></div>
                                <div class="img_view"></div>
                                <div class="img_view"></div>
                                <div>
                                    <input type="hidden" name="photo_content" />
                                    <input type="hidden" name="photo_content1" />
                                    <input type="hidden" name="photo_content2" />
                                    <textarea name="message" style="display:none"></textarea>
                                </div>
                                <div style="margin-top:100px;"><a href="javascript:void(0)" onclick="move_parent('photo')"><img src="images/sub_04_02_img_21.jpg" /></a></div>
                            </div>
                            <div class="sub_4_3_t2_right">
                            	<?								
								if($intRowCount)
								{
								?>
                                    <div>
										<?
                                        $i=1;
                                        while($row=mysql_fetch_array($result))
                                        {
                                            ?>
                                        <div class="sub_4_3_t2_right_1">
                                            <div class="sub_4_3_t2_right_1_1" style="height:250px">
                                                <div class="msg_title"><?=$row[title]?></div>
                                                <div class="img_view_1"><img src="<?=$row[img]?>" /></div>
                                                <?php if($row['img1'] != "") {?>
                                                <div class="img_view_1"><img src="<?=$row[img1]?>" /></div>
                                                <?php }?>
                                                <?php if($row['img2'] != "") {?>
                                                <div class="img_view_1"><img src="<?=$row[img2]?>" /></div>
                                                <?php } ?>
                                                <div>
                                                    <input type="hidden" name="photo_content" value="<?=$row[img]?>" />
                                                    <input type="hidden" name="photo_content1" value="<?=$row[img1]?>" />
                                                    <input type="hidden" name="photo_content2" value="<?=$row[img2]?>" />
                                                    <textarea name="message" style="display:none"><?=$row[message]?></textarea>
                                                </div>                                            
                                            </div>
                                            <div class="sub_4_3_t2_right_1_3"><label><input type="radio" name="ab" onclick="show_photo('<?=$i?>')" />사용시 선택
                                                <div class="sub_4_3_t2_right_1_3" style="float:right"><a href="javascript:deleteRow('<?=$row['idx']?>');">삭제</a></div>
                                                </label>
                                                
                                                
                                            </div>
                                        </div>
                                        <?
                                        $i++;
                                        }
                                        ?>
                                        <p style="clear:both;"></p>
                                    </div>
                                    <div>
										<?
                                        page_f($page,$page2,$intPageCount,"msg_serch_form");
                                        ?>                                   
                                    </div>
                                    <?
								}
								else
								{
								?>
                                <div style="text-align:center;">등록된 내용이 없습니다.</div>
                                <?	
								}
								?>
                            </div>
                            <p style="clear:both;"></p>
                        </div>
                        	<?
							break;
						}
						?>
                   </div>
            <input type="hidden" name="order_name" value="<?=$order_name?>"  />
            <input type="hidden" name="order_status" value="<?=$order_status?>"/>
            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />                        
        </form>
    </div>
</div> 

<script language="javascript">
    $('.ad_layer_login').hide();
$(window).load(function(){
    $('.ad_layer_login').hide();
var screen_width=(window.screen.width)/2;
var screen_height=(window.screen.height)/2;
//window.resizeBy(screen_width+200,screen_height+200);
window.resizeTo(screen_width+200,screen_height+300);
window.moveTo((screen_width-((screen_width+200)/2)),100);
});

function show_photo(i)
{
	document.getElementsByName('title')[0].value=$($(".msg_title")[i-1]).html();	
	document.getElementsByName('photo_content')[0].value=document.getElementsByName('photo_content')[i].value;
	$($(".img_view")[0]).html("<img src='"+document.getElementsByName('photo_content')[i].value+"'/>");
	
	if(document.getElementsByName('photo_content1')[i].value) {
    	document.getElementsByName('photo_content1')[0].value=document.getElementsByName('photo_content1')[i].value;
    	$($(".img_view")[1]).html("<img src='"+document.getElementsByName('photo_content1')[i].value+"'/>");
    } else {
    	document.getElementsByName('photo_content1')[0].value="";
    	$($(".img_view")[1]).html("");
    }
	
	if(document.getElementsByName('photo_content2')[i].value) {
    	document.getElementsByName('photo_content2')[0].value=document.getElementsByName('photo_content2')[i].value;
    	$($(".img_view")[2]).html("<img src='"+document.getElementsByName('photo_content2')[i].value+"'/>");		
    } else {
    	document.getElementsByName('photo_content2')[0].value="";
    	$($(".img_view")[2]).html("");
    }
    //console.log(document.getElementsByName('message')[i].value);
    document.getElementsByName('message')[0].value=document.getElementsByName('message')[i].value;
    //console.log(document.getElementsByName('message')[0].value);
}


function move_parent(status)
{
	if(status=="photo")
	{
		if(document.getElementsByName('photo_content')[0].value)
		{
			window.opener.document.getElementsByName("upimage_str")[0].value=document.getElementsByName('photo_content')[0].value;
			window.opener.document.getElementsByName("upimage_str1")[0].value=document.getElementsByName('photo_content1')[0].value;
			window.opener.document.getElementsByName("upimage_str2")[0].value=document.getElementsByName('photo_content2')[0].value;
			window.opener.document.getElementsByName('title')[0].value=document.getElementsByName('title')[0].value;
			window.opener.document.getElementsByName('txt')[0].value=document.getElementsByName('message')[0].value;
			$($(".img_view",window.opener.document)[0]).html("<img src='"+document.getElementsByName('photo_content')[0].value+"'  id='preview'/>")
			//$($(".img_view1",window.opener.document)[0]).html("<img src='"+document.getElementsByName('photo_content1')[0].value+"' />")
			//$($(".img_view2",window.opener.document)[0]).html("<img src='"+document.getElementsByName('photo_content2')[0].value+"' />")
			window.close();
		}		
	}
	else
	{
		if(document.getElementsByName('lms_content')[0].value)
		{
			window.opener.document.getElementsByName("txt")[0].value=document.getElementsByName('lms_content')[0].value;
			window.opener.document.getElementsByName('title')[0].value=document.getElementsByName('title')[0].value;
			window.close();
		}
		else
		document.getElementsByName('lms_content')[0].focus();
	}
	window.opener.type_check();
}

//문자저장 이동
function show_msg(i)
{
	document.getElementsByName('title')[0].value=$($(".msg_title")[i]).html();	
	document.getElementsByName('lms_content')[0].value=$($(".msg_content")[i]).html().replace("&gt;",">").replace("&lt;","<");
	document.getElementsByName('lms_content')[0].focus();
}
function page_p(e1,e2,e3)
{
   e3.page.value=e1
   if(e2%parseInt(e2)==0)
      {
	  e3.page2.value=e2   
	  }
	else
	  {
	 e3.page2.value=parseInt(e2)+1 
	  }  
  e3.submit();
}
</script>


<?
mysql_close();
//include_once "_foot.php";
?>