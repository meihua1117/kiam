<?
$path="./";
include_once "_head.php";
switch($_REQUEST['status']){
	case 1:
		$left_str="공지사항";
	break;
	case 2:
		$left_str="1:1상담";
	break;
	case 3:
		$left_str="사용후기";
	break;
    case 5:
		$left_str="관리자 매뉴얼";
	break;
}
if($_SESSION['one_member_admin_id'] != ""){
    $btn_go = '<a href="/admin/admin_manual.php">관리자 매뉴얼</a>';
}
$sql_serch="1=1 ";
if(strlen($_REQUEST['status']) == 1)
    $sql_serch.="and category='{$_REQUEST['status']}' ";
if($_REQUEST[cat] && $_REQUEST[cat] != 0 && $_REQUEST['status'] != 5){
    switch($_REQUEST[cat])
    {
        case 1:
            $sql_serch.="and (fl='문자')";
        break;
        case 2:
            $sql_serch.="and (fl='디버')";
        break;
        case 3:
            $sql_serch.="and (fl='윈스텝')";
        break;
        case 4:
            $sql_serch.="and (fl='아이엠')";
        break;
        case 5:
            $sql_serch.="and (fl='쇼핑')";
        break;
    }   
}
else if($_REQUEST[cat] && $_REQUEST[cat] != 0 && $_REQUEST['status'] == 5){
    switch($_REQUEST[cat])
    {
        case 1:
            $sql_serch.="and (fl='아이엠')";
        break;
        case 2:
            $sql_serch.="and (fl='폰문자')";
        break;
        case 3:
            $sql_serch.="and (fl='디비수집')";
        break;
        case 4:
            $sql_serch.="and (fl='콜백문자')";
        break;
        case 5:
            $sql_serch.="and (fl='스텝문자')";
        break;
        case 6:
            $sql_serch.="and (fl='웹문자')";
        break;
        case 7:
            $sql_serch.="and (fl='국제문자')";
        break;
        case 8:
            $sql_serch.="and (fl='결제')";
        break;
        case 9:
            $sql_serch.="and (fl='사업')";
        break;
        case 10:
            $sql_serch.="and (fl='마케팅')";
        break;
        case 11:
            $sql_serch.="and (fl='디비테이블')";
        break;
        case 12:
            $sql_serch.="and (fl='카페24')";
        break;
        case 13:
            $sql_serch.="and (fl='서버')";
        break;
        case 14:
            $sql_serch.="and (fl='기타')";
        break;
    } 
}
if($_REQUEST[lms_text] && $_REQUEST[lms_select])
{
	if($_REQUEST[lms_select]=="title_content")
	    $sql_serch.=" and (title like '%$_REQUEST[lms_text]%' or content like '%$_REQUEST[lms_text]%') ";
	else
	    $sql_serch.=" and {$_REQUEST[lms_select]} like '$_REQUEST[lms_text]%' ";
}
$sql="select count(no) as cnt from tjd_board where $sql_serch ";
$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$row=mysqli_fetch_array($result);
$intRowCount=$row['cnt'];
if (!$_POST['lno']) 
	$intPageSize =20;
else 
   $intPageSize = $_POST['lno'];
if($_POST['page'])
{
  $page=(int)$_POST['page'];
  $sort_no=$intRowCount-($intPageSize*$page-$intPageSize); 
}
else
{
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
  $order_name="no";
$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
$sql="select * from tjd_board where $sql_serch and important_yn = 'Y' order by $order_name $order_status limit $int,$intPageSize";
$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$sql="select * from tjd_board where $sql_serch and important_yn != 'Y' order by $order_name $order_status limit $int,$intPageSize";
$result2=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
?>
<div class="big_main">
	<div class="big_1 top_menu">
    	<div class="m_div">
    	    
        	<div class="left_sub_menu">
                <a href="./">홈</a> > 
                <a href="cliente_list.php?status=1">고객센터</a> > 
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>"><?=$left_str?></a>
            </div>
            <div class="right_sub_menu">
                <a href="cliente_list.php?status=1">공지사항</a> &nbsp;|&nbsp; 
                <a href="cliente_list.php?status=2">1:1상담</a> &nbsp;|&nbsp;
                <?=$btn_go?>
            </div>
            
            <p style="clear:both;"></p>
    	</div>
    </div>
    <div class="m_div" style="padding-bottom:50px;">
    	<div><img src="images/client_<?=$_REQUEST['status']?>_1.jpg" /></div>
        <div class="client">
        <form name="board_list_form" action="" method="post">
            <input type="hidden" name="page" value="<?php echo $page;?>">
            <input type="hidden" name="page2" value="<?php echo $page;?>">
        	<?
			if($_REQUEST['one_no'] && strlen($_REQUEST['one_no']) < 4)
			{
			$sql_no = "select * from tjd_board where no='{$_REQUEST['one_no']}'";
			$res_no = mysqli_query($self_con,$sql_no);
			$row_no = mysqli_fetch_array($res_no);
                //if(!$_SESSION['one_member_id']){
                //    echo "<script>alert('로그인후 이용해 주세요.');history.go(-1);</script>";
                //    exit;
                //}
			    if($row_no['id'] != $_SESSION['one_member_id'] && $row_no['status_1'] == "Y" ) {
			        echo "<script>alert('비밀글입니다. 로그인후 이용해 주세요.');history.go(-1);</script>";
			        exit;
			    }
			?>
                <table class="view_table_1" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="width:90%;"><?=htmlspecialchars_decode($row_no['title'])?></td>
                        <td style="text-align:right;"><?=substr($row_no[date],0,10)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?=htmlspecialchars_decode($row_no['content'])?></td>
                    </tr>
                    <?if($row_no['reply']){?>
                        <tr>
                            <td colspan="2"><h2>답변입니다</h2><BR><?=htmlspecialchars_decode($row_no[reply])?></td>
                        </tr>
                    <?}?>
                    <?
                    if($row_no['adjunct_2'])
                    {
                        $file_1_arr=explode("\n",$row_no['adjunct_1']);
                        $file_2_arr=explode("\n",$row_no['adjunct_2']);
                        $img_arr=array();
                        $order_1_arr=array();
                        $order_2_arr=array();
                        foreach($file_2_arr as $key=>$v)
                        {
                            $extr=explode(".",$v);
                            if(in_array(strtolower($extr[count($extr)-1]),$fileTypes))
                            array_push($img_arr,$v);
                            else
                            {
                            array_push($order_1_arr,$file_1_arr[$key]);
                            array_push($order_2_arr,$v);
                            }
                        }
                        if(count($img_arr))
                        {
                        ?>
                        <tr>
                            <td colspan="2">
                                <div style="margin:5px 0 0 5px;">
                                <?php if(strstr($img_arr[0],'pdf')) {?>
                                <?php } else {?>
                                    <img id='view_main_img' src="adjunct/board/thum1/<?=$row_no[up_path]?>/<?=$img_arr[0]?>" />
                                <?php }?>
                                </div>
                                <div style="margin:5px 0 0 5px;">
                                    <?
                                    for($i=0; $i<count($img_arr); $i++)
                                    {
                                        if(strstr($img_arr[$i],'pdf')) {
                                        ?>
                                        <a href="adjunct/board/thum/<?=$row_no[up_path]?>/<?=$img_arr[$i]?>" target="_blank"/><?php echo $file_1_arr[$i];?></a>
                                        <?php
                                        } else {
                                        ?>
                                        <a href="javascript:void(0)" onmouseover="$('#view_main_img').attr('src','adjunct/board/thum1/<?=$row_no[up_path]?>/<?=$img_arr[$i]?>')"><img src="adjunct/board/thum2/<?=$row_no[up_path]?>/<?=$img_arr[$i]?>" width="50" height="50" /></a>
                                        <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <?
                        }
                        if(count($order_1_arr))
                        {
                        ?>
                        <tr>
                            <td colspan="2">
                                <div>
                                    <?
                                    for($i=0; $i<count($order_1_arr); $i++)
                                    {
                                        ?>
                                        <a href="javascript:void(0)"><?=$order_1_arr[$i]?></a>
                                        <?
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>                        
                        <?
                        }
                    }?>
                        <tr>
                            <td colspan="2" style="text-align:right;">
                            <a href="cliente_list.php?status=<?=$_REQUEST['status']?>"><img src="images/btn_list.gif" /></a>
                            <?
                            if($member_1['mem_id']==$row_no['id'])
                            {
                            ?>
                            <a href="cliente_write.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>"><img src="images/btn_crystal.gif" /></a>
                            <a href="javascript:void(0)" onclick="board_del('<?=$row_no['no']?>','<?=$_REQUEST['status']?>')"><img src="images/client_1_5.jpg" /></a>
                            <?
                            }
                            ?>
                            </td>
                        </tr>
                </table>
            <?}?>
        	<div class="a2">
                <?if($_REQUEST['status'] != 5){?>
                <div style="float:left;">
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=0" class="bd_page bd_page_over">전체</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=1" class="bd_page bd_page_over">문자</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=2" class="bd_page bd_page_over">디버</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=3" class="bd_page bd_page_over">윈스텝</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=4" class="bd_page bd_page_over">아이엠</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=5" class="bd_page bd_page_over">쇼핑</a>                 
                </div>
                <?}
                else{?>
                <div style="float:left;">
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=0" class="bd_page bd_page_over">전체</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=1" class="bd_page bd_page_over">아이엠</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=2" class="bd_page bd_page_over">폰문자</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=3" class="bd_page bd_page_over">디비수집</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=4" class="bd_page bd_page_over">콜백문자</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=5" class="bd_page bd_page_over">스텝문자</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=6" class="bd_page bd_page_over">웹문자</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=7" class="bd_page bd_page_over">국제문자</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=8" class="bd_page bd_page_over">결제</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=9" class="bd_page bd_page_over">사업</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=10" class="bd_page bd_page_over">마케팅</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=11" class="bd_page bd_page_over">디비테이블</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=12" class="bd_page bd_page_over">카페24</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=13" class="bd_page bd_page_over">서버</a>
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row_no['no']?>&cat=14" class="bd_page bd_page_over">기타</a>
                </div>
                <br><br>
                <?}?>
                <select name="lms_select">
                    <?
                    $select_lms_arr=array("title_content"=>"제목+내용");
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
                <a href="javascript:void(0)" onclick="board_list_form.submit();"><img src="images/sub_button_703.jpg" /></a>            
            </div>
            <div>
            	<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td style="width:5%;"><label><input type="checkbox" onclick="check_all(this,'no_box')"  />번호</label></td>                    
                    <? if($_REQUEST['status']==2){?>
                    <td style="width:10%;">분류</td>
                    <td style="width:10%;">답변여부</td>
                    <? } else {?>
                    <td style="width:10%;">서비스</td>
                    <? } ?>
                    <td style="width:<?=$_REQUEST['status']==2?"60%":"70%"?>;">제목</td>
                    <td style="width:10%;">등록일자</td>
                    <? if($_REQUEST['status']!=2 && $_REQUEST['status']!=4){?>
                    <td style="width:10%;">조회수</td>
                    <?}?>
                  </tr>
                  <?
				  if($intRowCount)
				  {
					  while($row=mysqli_fetch_array($result))
					  {
						?>
						<tr style="<?=$row[important_yn] == 'Y'?'background-color:gold':''?>">
                            <td><label><input type="checkbox" value="<?=$row['no']?>" name="no_box"  /><?=$sort_no?></label></td>
                            <? if($_REQUEST['status']==2){?>
                            <td><?=$fl_arr[$row['fl']]?></td>
                            <td><?=$row[reply]?"답변완료":"문의접수"?></td>
                            <? } else { ?>                            
                            <td><?=$row['fl']?></td>
                            <? } ?>
                            <td>
                                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row['no']?>"><?=$row['title']?></a> <?php echo $row['status_1']=="Y"?"[비밀글]":""?>
                            </td>
                            <td><?=substr($row[date],0,10)?></td>
                            <? if($_REQUEST['status']!=2 && $_REQUEST['status']!=4){?>
                            <td><?=$row[view_cnt]?></td>
                            <?}?>
						</tr>
						<?
						$sort_no--;
                      }
                      while($row=mysqli_fetch_array($result2))
					  {?>
						<tr style="<?=$row[important_yn] == 'Y'?'background-color:gold':''?>">
                            <td><label><input type="checkbox" value="<?=$row['no']?>" name="no_box"  /><?=$sort_no?></label></td>
                            <? if($_REQUEST['status']==2){?>
                            <td><?=$fl_arr[$row['fl']]?></td>
                            <td><?=$row[reply]?"답변완료":"문의접수"?></td>
                            <? } else { ?>                            
                            <td><?=$row['fl']?></td>
                            <? } ?>
                            <td>
                                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>&one_no=<?=$row['no']?>"><?=$row['title']?></a> <?php echo $row['status_1']=="Y"?"[비밀글]":""?>
                            </td>
                            <td><?=substr($row[date],0,10)?></td>
                            <? if($_REQUEST['status']!=2 && $_REQUEST['status']!=4){?>
                            <td><?=$row[view_cnt]?></td>
                            <?}?>
						</tr>
						<?
						$sort_no--;
					  }
					  ?>
                      
                        <tr>
                            <td colspan="5">
                                <?
                                page_f($page,$page2,$intPageCount,"board_list_form");
                                ?>
                            </td>
                        </tr>
                      <?
				  }
				  else
				  {
					?>
                    <tr>
                    	<td colspan="5">검색된 내용이 없습니다.</td>
                    </tr>
                    <?  
				  }
				  ?>
                </table>
            </div>
            <div class="a3">
            	<?
				if($_REQUEST['status']==1)
				{
					if($member_1[level]==9)
					{
					?>
                <a href="cliente_write.php?status=<?=$_REQUEST['status']?>"><img src="images/client_1_3.jpg" /></a>
                	<?
					}
				}
				else
				{
					if($_SESSION['one_member_id'])
					{
						?>
                    <a href="cliente_write.php?status=<?=$_REQUEST['status']?>"><img src="images/client_1_3.jpg" /></a>    
                        <?
					}
					else
					{
					?>
					<a href="javascript:void(0)" onclick="alert('로그인하세요')"><img src="images/client_1_3.jpg" /></a>
					<?
					}
				}
				if($member_1[level]==9)
				{
				?>
                <a href="javascript:void(0)" onclick="board_del('','<?=$_REQUEST['status']?>')"><img src="images/client_1_5.jpg" /></a>                
                <?	
				}
				?>
            </div>
            </form>
        </div>
    </div>
</div> 
<?
include_once "_foot.php";
?>
