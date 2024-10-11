<?
$path="./";
include_once $_REQUEST['status']?"_head_open.php":"_head.php";
if(!$fujia_pay)
{
//echo "권한이 없습니다.";	
//exit;
}
?>
<div class="big_sub">
	<div class="big_1">
    	<div class="m_div">
        	<div class="left_sub_menu">
                <a href="./">홈</a> > 
                <a href="join.php">원북검색</a>
            </div>
            <div class="right_sub_menu">
            <?
			if(!$_REQUEST['status'])
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
   <div class="m_div onebook">
   		<?		
				$sql_serch=" 1 ";
				if($_REQUEST[book_text])
				{
					$book_text=trim($_REQUEST[book_text]);
					$sql_serch.=" and ( ca_id like '10%' or ca_id like '20%')";
					$sql_serch.=" and ( it_name like '%$book_text%' or 
									   	it_name_ori like '%$book_text%' or 
									   	it_origin like '%$book_text%' or 
									   	it_publisher like '%$book_text%' or 
									   	preview1 like '%$book_text%' or 
									   	propb1_subject like '%$book_text%' or 
									   	propb1 like '%$book_text%' or 
									   	it_code1 like '%$book_text%' or 
									   	it_code2 like '%$book_text%' )";
					$sql_serch.=" and it_use='1' and  it_state2='1' ";
					$sql="select count(it_id) as cnt from Gn_Shop_Item where $sql_serch ";
					$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
					$row=mysqli_fetch_array($result);
					$intRowCount=$row['cnt'];
					if($_POST['page'])
					  $page=(int)$_POST['page'];
					else
					  $page=1;
					if($_POST['page2'])
					  $page2=(int)$_POST['page2'];
					else
					  $page2=1;
					if (!$_POST['lno']) 
						$intPageSize =5;
					else 
					   $intPageSize = $_POST['lno'];
					$int=($page-1)*$intPageSize;
					if($_REQUEST['order_status'])
					  $order_status=$_REQUEST['order_status'];
					else
					  $order_status="desc"; 
					if($_REQUEST['order_name'])
					  $order_name=$_REQUEST['order_name'];
					else
					  $order_name="it_time";
					$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
					$sql="select * from Gn_Shop_Item where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
					$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
				}
		?>
        <form name="onebook_form" method="post">
        <input type="hidden" name="order_name" value="<?=$order_name?>"  />
        <input type="hidden" name="order_status" value="<?=$order_status?>"/>
        <input type="hidden" name="page" value="<?=$page?>" />
        <input type="hidden" name="page2" value="<?=$page2?>" />     
        	<div class="r1">
                <div><img src="images/sub_04_02_img_03.jpg" /></div>
                <div style="padding:20px;">
                    <div class="a1">원북 문자</div>
                    <!--<div style="margin-bottom:2px;"><input type="text" placeholder="제목" /></div>-->
                    <div><textarea placeholder="내용" name="one_content" onkeydown="textCounter(document.getElementsByName('one_content')[0],'wenzi_cnt',1500,0)" onfocus="textCounter(document.getElementsByName('one_content')[0],'wenzi_cnt',1500,0)"></textarea></div>
                    <div style="margin-bottom:14px;text-align:left;"><span class="wenzi_cnt">0</span> Byte</div>
                    <div><a href="javascript:void(0)" onclick="move_parent2()"><img src="images/sub_04_02_img_21.jpg" /></a></div>
                </div>
            </div>
            <div class="r2">
            	<div><img src="images/sub_04_02_img_05.jpg" /></div>
                <div style="padding:20px;">
                    <div class="a1">원북검색 &nbsp; 
                        <input type="text" name="book_text" value="<?=$_REQUEST[book_text]?>" />
                        <input type="image" src="images/sub_button_703.jpg" />
                        <a href="http://www.onepagebook.net" target="_blank"><img src="images/sub_04_02_img_12.jpg" /></a>
                    </div>
                  <div class="a2">
                       <?
					   if($intRowCount)
					   {
						   $i=0;
						 while($row=mysqli_fetch_array($result))
						 {
							if(strpos($row['propb2_title'],"[|*|]")!==false)
								$propb2_title=explode("[|*|]",$row['propb2_title']);
							else
								$propb2_title[0]=$row['propb2_title'];
							if(strpos($row['propb2_subject'],"[|*|]")!==false)
								$propb2_subject=explode("[|*|]",$row['propb2_subject']);
							else
								$propb2_subject[0]=$row['propb2_subject'];
							if(strpos($row['propb2'],"[|*|]")!==false)
								$propb2=explode("[|*|]",$row['propb2']);
							else
								$propb2[0]=$row['propb2'];							 
							$row[opb3]=opb_text($row[propb3_title],$row[propb3_subject],$row[propb3])."<br>".$row[propb3_remember];
							//주제와 요점
							$row[opb1]=opb_text($row[propb1_title],$row[propb1_subject],$row[propb1]);
							//1장
							$row[opb2]=opb_text($propb2_title[0],$propb2_subject[0],$propb2[0]);
							//저자이해
							$row[prset1] = opb_text($row[prset1_title],$row[prset1_subject],$row[prset1]);
							//저자주장
							$row[prset2] = opb_text($row[prset2_title],$row[prset2_subject],$row[prset2]);
							//저자의도와 목적
							$row[prset3] = opb_text($row[prset3_title],$row[prset3_subject],$row[prset3]);							 
							 ?>
                                <input type="hidden" name="it_name" value="<?=$row[it_name]?>">
                                <input type="hidden" name="it_origin" value="<?=$row[it_origin]?>">
                                <input type="hidden" name="it_id" value="<?=$row[it_id]?>">
                                <input type="hidden" name="opb1" value="<?=$row[opb1]?>">
                                <input type="hidden" name="opb2" value="<?=$row[opb2]?>">
                                <input type="hidden" name="opb3" value="<?=$row[opb3]?>">
                                
                                <input type="hidden" name="prset1" value="<?=$row[prset1]?>">
                                <input type="hidden" name="prset2" value="<?=$row[prset2]?>">
                                <input type="hidden" name="prset3" value="<?=$row[prset3]?>">                             
                             <?
							if($row[it_publishday])
							$pubday=" | ".str_replace(" ","",$row[it_publishday]);							 
							if($row[it_maker])
								$view2=$row[it_origin]." 저/".$row[it_maker]." 역 | ".$row[it_publisher].$pubday;
							else
								$view2=$row[it_origin]." 저 | ".$row[it_publisher].$pubday;
							if(substr($row[ca_id],0,2)=="10")
								$icon_src="/opbkorea/img/btn/icon_f.gif";
							else if(substr($row[ca_id],0,2)=="30")
							{
								$icon_src="/opbkorea/img/btn/icon_m.gif";
								if ($row[it_name_ori2])
								$view2=$row[it_name_ori]." : ".$row[it_name_ori2];
								else
								$view2=$row[it_name_ori];
							}
							else
								$icon_src="/opbkorea/img/btn/icon_o.gif";
							?>
                            	<div class="a2_1">
                                	<div class="a2_1_1"><img src="http://onepagebook.net/shop/data/item/<?=$row[it_id]?>_s" width="31" height="45"  /></div>
                                    <div class="a2_1_1">
                                    	<li class="a2_1_1_1"><?=$row[it_name]?> <input type="button" value="선택" onclick="book_select('<?=$i?>','<?=$member_1['mem_name']?>')" /></li>
                                        <li class="a2_1_1_2">
											<?=$row[it_name_ori]?"<br/>원제 : ".$row[it_name_ori]."<br>":""?>
                                            <?=$view2?>                                       
                                        </li>
                                    </div>
                                    <p style="clear:both;"></p>
                                </div>
                            <?
							$i++;
						 }
						page_f($page,$page2,$intPageCount,"onebook_form");
					   }
					   else
					   {
						  ?>
                          <div style="text-align:center;">
                         	검색된 내용이 없습니다. 
                          </div>  
                          <? 
					   }
					   ?>
                  </div>
                  <div class="a1">항목선택</div>
                  <div>
                    <?
					$b=0;
                    for($i=1; $i<9; $i++)
                    {
						$margin_right=$i%4?"7px":"";
                        ?>
                        <div class="a3" style="margin-right:<?=$margin_right?>">
                            <div class="a3_1"></div>
                            <div class="a3_2"><label><input type="radio" name="ab" onclick="show_content('<?=$b?>')" /><?=$onebook_type_arr[$b]?></label></div>
                        </div>		                    
                        <?
						$b++;
                    }
                    ?>
                    <p style="clear:both;"></p>
                  </div>
               </div>   
            </div>
            <p style="clear:both;"></p> 
            <div class="a4">※ 원북추천글과 기본문자 추가시 글자수를 미리 조정하시면 편리합니다.</div>
        </form>
    </div>
</div> 
<?
include_once "_foot.php";
?>
<script language="javascript">
$(window).load(function(){
var screen_width=(window.screen.width)/2;
var screen_height=(window.screen.height)/2;
//window.resizeBy(screen_width+200,screen_height+200);
window.resizeTo(screen_width+200,screen_height+300);
window.moveTo((screen_width-((screen_width+200)/2)),100);
});
</script>