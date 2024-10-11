<?
$_REQUEST['status'] = 4;
$path="./";
include_once "_head.php";
switch($_REQUEST['status'])
{
	case 1:
		$left_str="공지사항";
	break;
	case 2:
		$left_str="Q&#38;A";
	break;
	case 3:
		$left_str="사용후기";
	break;
}
$sql_serch=" category='{$_REQUEST['status']}' ";
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
$sql="select * from tjd_board where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
?>
<div class="big_main">
	<div class="big_1">
    	<div class="m_div">
    	    <!--
        	<div class="left_sub_menu">
                <a href="./">홈</a> > 
                <a href="cliente_list.php?status=1">고객센터</a> > 
                <a href="cliente_list.php?status=<?=$_REQUEST['status']?>"><?=$left_str?></a>
            </div>
            <div class="right_sub_menu">
                <a href="cliente_list.php?status=1">공지사항</a> &nbsp;|&nbsp; 
                <a href="cliente_list.php?status=2">Q&#38;A</a> &nbsp;|&nbsp;
                <a href="cliente_list.php?status=3">사용후기</a>
            </div>
            -->
            <p style="clear:both;"></p>
    	</div>
    </div>
    <div class="m_div" style="padding-bottom:50px;">
    	<div><img src="images/client_<?=$_REQUEST['status']?>_1.png" /></div>
        <div class="client">
        <form name="board_list_form" action="" method="post">
        	<?
			if($_REQUEST['one_no'])
			{
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
                if($row_no[adjunct_2])
                {
                    $file_1_arr=explode("\n",$row_no[adjunct_1]);							
                    $file_2_arr=explode("\n",$row_no[adjunct_2]);
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
                                <img id='view_main_img' src="adjunct/board/thum1/<?=$row_no[up_path]?>/<?=$img_arr[0]?>" />
                                </div>
                                <div style="margin:5px 0 0 5px;">
                                    <?
                                    for($i=0; $i<count($img_arr); $i++)
                                    {
                                        ?>
    <a href="javascript:void(0)" onmouseover="$('#view_main_img').attr('src','adjunct/board/thum1/<?=$row_no[up_path]?>/<?=$img_arr[$i]?>')"><img src="adjunct/board/thum2/<?=$row_no[up_path]?>/<?=$img_arr[$i]?>" width="50" height="50" /></a>
                                        <?
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
                }
                ?>
                <tr>
                	<td colspan="2" style="text-align:right;">
                    <a href="faq_list.php?status=<?=$_REQUEST['status']?>"><img src="images/btn_list.gif" /></a>
                    <?
					if($member_1['mem_id']==$row_no['id'])
					{
					?>
                    <a href="javascript:void(0)" onclick="board_del('<?=$row_no['no']?>','<?=$_REQUEST['status']?>')"><img src="images/client_1_5.jpg" /></a>
                    <?
					}
					?>
                    </td>
                </tr>
            </table>
            <?
			}
			?>
        	<div class="a2">
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
                <a href="javascript:void(0)" onclick="sub_4_form.submit();"><img src="images/sub_button_703.jpg" /></a>            
          </div>
            <div>
            	<table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0" >
                  <tr>
                    <td style="width:5%;"><label><input type="checkbox" onclick="check_all(this,'no_box')"  />번호</label></td>
                    <? if($_REQUEST['status']==2){?><td style="width:10%;">분류</td><? }?>
                    <td style="width:<?=$_REQUEST['status']==2?"60%":"70%"?>;">제목</td>
                    <!--<td style="width:15%;">등록일자</td>
                    <td style="width:10%;">조회수</td>-->
                  </tr>
                  <?
				  if($intRowCount)
				  {
					  while($row=mysqli_fetch_array($result))
					  {
						?>
						<tr>
                            <td><label><input type="checkbox" value="<?=$row['no']?>" name="no_box"  /><?=$sort_no?></label></td>
                            <? if($_REQUEST['status']==2){?><td><?=$fl_arr[$row['fl']]?></td><? }?>
                            <td style="text-align:left;padding-left:20px;"><a href="javascript:viewContent('a<?=$row['no']?>')"><?=$row['title']?></a></td>
                            <!--<td><?=substr($row[date],0,10)?></td>
                            <td><?=$row[view_cnt]?></td>-->
						</tr>
						<tr id="a<?=$row['no']?>" style="display:none;" >
                            <td colspan="4"style="text-align:left;padding-left:80px;padding-top:10px; padding-bottom:10px; background-color:#CEECF5;"><?=html_entity_decode($row['content'])?></td>
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
          </form>
        </div>
    </div>
</div> 
<script>
function viewContent(id) {
    if($('#'+id).css("display") == "none") {
        $('#'+id).show();
    } else{
        $('#'+id).hide();
    }
}
</script>
<?
include_once "_foot.php";
?>
