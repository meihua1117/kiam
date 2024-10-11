<?
/* ------------------------------------------------------------------------------

title: 셀링솔루션 마이페이지 결제정보
sub_title: 나의 결제 정보
this file is for update.
update : 2020.05.25
by rturbo
-------------------------------------------------------------------------------*/

$path="./";
include_once "_head.php";

if(!$_SESSION['one_member_id'])
{
    $chk = false;

//business_type = B
//service_type = 1
?>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                
<script language="javascript">
jQuery(function($){
 $.datepicker.regional['ko'] = {
  closeText: '닫기',
  prevText: '이전달',
  nextText: '다음달',
  currentText: 'X',
  monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)',
  '7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
  monthNamesShort: ['1월','2월','3월','4월','5월','6월',
  '7월','8월','9월','10월','11월','12월'],
  dayNames: ['일','월','화','수','목','금','토'],
  dayNamesShort: ['일','월','화','수','목','금','토'],
  dayNamesMin: ['일','월','화','수','목','금','토'],
  weekHeader: 'Wk',
  dateFormat: 'yy-mm-dd',
  firstDay: 0,
  isRTL: false,
  showMonthAfterYear: true,
  yearSuffix: ''};
 $.datepicker.setDefaults($.datepicker.regional['ko']);

    $('#rday1').datepicker({
        showOn: 'button',
  buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
  buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });
    $('#rday2').datepicker({
        showOn: 'button',
  buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
  buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });	
    
    $('#krday1, #sday1').datepicker({
        showOn: 'button',
  buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
  buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });
    $('#krday2, #sday2').datepicker({
        showOn: 'button',
  buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
  buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });	    
});
</script>
<script language="javascript">
location.replace('/ma.php');
</script>
<?
exit;
}


	$sql="select * from Gn_Member  where mem_id='".$_SESSION['one_member_id']."'";
	$sresul_num=mysqli_query($self_con,$sql);
	$member = $data=mysqli_fetch_array($sresul_num);	
	

?>
<script>
function copyHtml(){
    //oViewLink = $( "ViewLink" ).innerHTML;
    ////alert ( oViewLink.value );
    //window.clipboardData.setData("Text", oViewLink);
    //alert ( "주소가 복사되었습니다. \'Ctrl+V\'를 눌러 붙여넣기 해주세요." );
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
			onLoad: function() {
			}
		});
	})

});
</script>
<style>
.pop_right {
    position: relative;
    right: 2px;
    display: inline;
    margin-bottom: 6px;
    width: 5px;
}    
</style>

 
<div class="big_sub">
<?php include_once "mypage_base_navi.php";?>

   <div class="m_div">
    
     
        <div class="join">   
        <!--//마이페이지 결제정보--> 
                <?
				$sql_serch=" buyer_id ='{$_SESSION['one_member_id']}' ";
				if($_REQUEST[search_date])
				{					
					if($_REQUEST[rday1])
					{
					$start_time=strtotime($_REQUEST[rday1]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) >=$start_time ";
					}
					if($_REQUEST[rday2])
					{
					$end_time=strtotime($_REQUEST[rday2]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) <= $end_time ";
					}
				}
				$sql="select count(no) as cnt from tjd_pay_result where $sql_serch ";

				$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
				$row=mysqli_fetch_array($result);
				$intRowCount=$row['cnt'];

// for debug code

					$msg = "		
			  <tr>
                <td colspan='11'>";
					$msg = $msg ."intRowCount is ==". $intRowCount . "<br>";
					$msg = $msg . "	           
                </td>
              </tr> ";
					echo $msg;
					exit();


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
				  $order_name="end_status";
				$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
				$sql="select * from tjd_pay_result where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
				$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
		?>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                
<script language="javascript">
jQuery(function($){
 $.datepicker.regional['ko'] = {
  closeText: '닫기',
  prevText: '이전달',
  nextText: '다음달',
  currentText: 'X',
  monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)',
  '7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
  monthNamesShort: ['1월','2월','3월','4월','5월','6월',
  '7월','8월','9월','10월','11월','12월'],
  dayNames: ['일','월','화','수','목','금','토'],
  dayNamesShort: ['일','월','화','수','목','금','토'],
  dayNamesMin: ['일','월','화','수','목','금','토'],
  weekHeader: 'Wk',
  dateFormat: 'yy-mm-dd',
  firstDay: 0,
  isRTL: false,
  showMonthAfterYear: true,
  yearSuffix: ''};
 $.datepicker.setDefaults($.datepicker.regional['ko']);

    $('#rday1').datepicker({
        showOn: 'button',
  buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
  buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });
    $('#rday2').datepicker({
        showOn: 'button',
  buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
  buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });	
    
    $('#krday1, #sday1').datepicker({
        showOn: 'button',
  buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
  buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });
    $('#krday2, #sday2').datepicker({
        showOn: 'button',
  buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
  buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });	    
});
</script>
	<div class="ad_layer_info">
		<div class="layer_in">
			<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
			<div class="pop_title">
				정기결제해지
			</div>
			<div class="info_text">
				<p>
					정기결제일 1주일 전 해지 시 당월 적용되며, 1주일 이내 해지 시 익월 적용됩니다
				</p>
			</div>

		</div>
	</div>
    
         <form name="pay_form" action="" method="post" class="my_pay">
            <input type="hidden" name="order_name" value="<?=$order_name?>"  />
            <input type="hidden" name="order_status" value="<?=$order_status?>"/>
            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1">
        <li style="float:left;">구매상품정보 및 정기결제해지관리</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1">
            	<a href="mypage.php" class="a_btn_2">전체보기</a>
            	<?
				$search_date=array("date"=>"결제일","end_date"=>"만료(해지)일");
				foreach($search_date as $key=>$v)
				{
					$checked=$_REQUEST[search_date]==$key?"checked":"";
				?>
                	<label><input name="search_date" type="radio" value="<?=$key?>" <?=$checked?> /><?=$v?></label>
                <?	
				}
				?>
                <input type="text" name="rday1" placeholder="" id="rday1" value="<?=$_REQUEST[rday1]?>"/> ~
                <input type="text" name="rday2" placeholder="" id="rday2" value="<?=$_REQUEST[rday2]?>"/>
                <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>                                            
            </div>
            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:6%;">번호</td>
                <td style="width:6%;">회원구분</td>
                <td style="width:15%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'date',pay_form.order_status.value)">결제일<? if($_REQUEST['order_name']=="date"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                <td style="width:15%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'end_date',pay_form.order_status.value)">만료(해지)일<? if($_REQUEST['order_name']=="end_date"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                <td style="width:6%"><a href="javascript:void(0)" onclick="order_sort(pay_form,'month_cnt',pay_form.order_status.value)">개월수<? if($_REQUEST['order_name']=="month_cnt"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                <td style="width:8%"><a href="javascript:void(0)" onclick="order_sort(pay_form,'fujia_status',pay_form.order_status.value)">결제상품<? if($_REQUEST['order_name']=="fujia_status"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                <td style="width:10%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'payMethod',pay_form.order_status.value)">결제방식<? if($_REQUEST['order_name']=="payMethod"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>            
                
                <td style="width:9%;">결제한<br />폰 수</td>
                <td style="width:9%;">등록된<br />건 수</td>
                <td style="width:10%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'TotPrice',pay_form.order_status.value)">결제금액<? if($_REQUEST['order_name']=="TotPrice"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                <td style="width:12%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'end_status',pay_form.order_status.value)">상태<? if($_REQUEST['order_name']=="end_status"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
              </tr>
<?
// for debug code

					$msg = "		
			  <tr>
                <td colspan='11'>";
					$msg = $msg ."intRowCount is ==". $intRowCount . "<br>";
					$msg = $msg . "	           
                </td>
              </tr> ";
					echo $msg;
					exit();


              if($intRowCount) {

                while($row=mysqli_fetch_array($result)) {
					$num_arr=array();
					$sql_num="select sendnum from Gn_MMS_Number where mem_id='$row[buyer_id]' and end_date='$row[end_date]' ";
					$resul_num=mysqli_query($self_con,$sql_num);
					
					while($row_num=mysqli_fetch_array($resul_num)) array_push($num_arr,$row_num[sendnum]);
					$sql="select mem_leb from Gn_Member  where mem_id='$row[buyer_id]'";
					$sresul_num=mysqli_query($self_con,$sql);
					$srow=mysqli_fetch_array($sresul_num);
												
					if($srow['mem_leb'] == "22") $mem_leb = "일반회원";
					else  if($srow['mem_leb'] == "50") $mem_leb = "사업회원";
					else $mem_leb = "";

?>
              <tr>
                <td><?=$sort_no?></td>
                <td>
                    <?=$mem_leb?>
                </td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                <td><?=$row[month_cnt]?>개월</td>
                <td>문자</td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"무통장"?></td>
                <td><?=$row[add_phone]?></td>
                <td><?=$row[phone_cnt]?></td>
                <td><?=number_format($row[TotPrice])?>원</td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
               	<?php if($row['monthly_yn'] == "Y") {?>
               	<div style="border:1px solid #000;padding:3px;background:#D8D8D8; font-size:10px" ><A href="javascript:void(monthly_remove('<?php echo $row['no'];?>'))">정기결제해지</a> <span class="popbutton pop_view pop_right">?</span></div>                
                <?php }?>
            
                </td>
              </tr>
<?
					$sort_no--;

                } // end while

				$sql_serch=" buyer_id ='{$_SESSION['one_member_id']}' ";

				if($_REQUEST[search_date]) {					
					if($_REQUEST[rday1]) {
						$start_time=strtotime($_REQUEST[rday1]);
						$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) >=$start_time ";
					}
		
					if($_REQUEST[rday2]) {
						$end_time=strtotime($_REQUEST[rday2]);
						$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) <= $end_time ";
					}
				} // end if

				$sql="select count(no) as cnt from tjd_pay_result_db where $sql_serch ";
				$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
				$row=mysqli_fetch_array($result);
				$intRowCount=$row['cnt'];

				if (!$_POST['lno']) $intPageSize =20;
				else $intPageSize = $_POST['lno'];				

				if($_POST['page']) {
				  $page=(int)$_POST['page'];
				  $sort_no=$intRowCount-($intPageSize*$page-$intPageSize); 
				} else {
				  $page=1;
				  $sort_no=$intRowCount;
				} // end if else

				if($_POST['page2']) $page2=(int)$_POST['page2'];
				else $page2=1;
				
				$int=($page-1)*$intPageSize;

				if($_REQUEST['order_status']) $order_status=$_REQUEST['order_status'];
				else $order_status="desc"; 

				if($_REQUEST['order_name']) $order_name=$_REQUEST['order_name'];
				else $order_name="end_status";

				$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
				$sql="select * from tjd_pay_result_db where $sql_serch order by $order_name $order_status limit $int,$intPageSize";

				$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				

                while($row=mysqli_fetch_array($result)) {
										
					$num_arr=array();
					$sql_num="select sendnum from Gn_MMS_Number where mem_id='$row[buyer_id]' and end_date='$row[end_date]' ";
					$resul_num=mysqli_query($self_con,$sql_num);
					
					while($row_num=mysqli_fetch_array($resul_num)) array_push($num_arr,$row_num[sendnum]);
						
					$sql="select mem_leb from Gn_Member  where mem_id='$row[buyer_id]'";
					$sresul_num=mysqli_query($self_con,$sql);
					$srow=mysqli_fetch_array($sresul_num);
												
					if($srow['mem_leb'] == "22") $mem_leb = "일반회원";
					else  if($srow['mem_leb'] == "50") $mem_leb = "사업회원";
					else $mem_leb = "";

 ?>
              <tr>
                <td><?=$sort_no?></td>
                <td>
                    <?=$mem_leb?>
                </td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                <td><?=$row[month_cnt]?>개월</td>
                <td>디버</td>                
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=$row[add_phone]?></td>
                <td><?=$row[phone_cnt]?></td>
                <td><?=number_format($row[TotPrice])?>원</td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
               	<?php if($row['monthly_yn'] == "Y") {?>
               	<div style="border:1px solid #000;padding:3px; background:#D8D8D8; font-size:10px;" ><A href="javascript:void(monthly_remove('<?php echo $row['no'];?>'))">정기결제해지</a> <span class="popbutton pop_view pop_right">?</span></div>
                <?php }?>
                </td>
              </tr>
<?
                    $sort_no--;
                } // end while
?>		               
              <tr>
                <td colspan="11">
                        <?
                        page_f($page,$page2,$intPageCount,"pay_form");
                        ?>
                </td>
              </tr>    
<?
			  } else {
					$msg = "		
			  <tr>
                <td colspan='11'>";
					//$msg = $msg . "	검색된 내용이 없습니다.";
					//$msg = $msg ."sql is ==". $sql . "<br>";
					$msg = $msg . "	           
                </td>
              </tr> ";
					echo $msg;
					//exit();

			  } // end if($intRowCount) else - line 339

              ?>
            </table>
            </div>
        </div>
        </form>
        <!--마이페이지 결제정보//-->
           
        <?
        
				$sql="select a.* from tjd_pay_result a 
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 
				              where a.buyer_id='{$_SESSION['one_member_id']}'  and end_status='Y' order by end_date desc ";
				$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
				$row=mysqli_fetch_array($result);
				$chk = false;
				if(strstr($row['member_type'],"Business")==true && $row[0]) {
				    $chk = 1;    
				}
				if(strstr($row['member_type'],"Premium")==true && $row[0]) {
				    $chk = 2;    
				}	        
        
				    $sql_serch=" buyer_id ='{$_SESSION['one_member_id']}' ";
				if($_REQUEST[search_date])
				{					
					if($_REQUEST[rday1])
					{
					$start_time=strtotime($_REQUEST[rday1]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) >=$start_time ";
					}
					if($_REQUEST[rday2])
					{
					$end_time=strtotime($_REQUEST[rday2]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) <= $end_time ";
					}
				}
				$sql="select count(no) as cnt from tjd_pay_result a
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 				
				where $sql_serch  and end_status='Y' ";
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
				$sql="select * from tjd_pay_result a 
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 
				              where $sql_serch  and end_status='Y' order by $order_name $order_status limit $int,$intPageSize";
                
				$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
				
				$row=mysqli_fetch_array($result);
		?>

	<div class="ad_layer_info">
	</div>
	
        <form name="pay_form" action="" method="post" class="my_pay">
            <input type="hidden" name="order_name" value="<?=$order_name?>"  />
            <input type="hidden" name="order_status" value="<?=$order_status?>"/>
            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1">
        <li style="float:left;">

            
        나의 결제정보</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1" style="float:left">
            	<a href="mypage.php" class="a_btn_2">전체보기</a>
            	<?
				$search_date=array("date"=>"결제일","end_date"=>"만료(해지)일");
				foreach($search_date as $key=>$v)
				{
					$checked=$_REQUEST[search_date]==$key?"checked":"";
				?>
                	<label><input name="search_date" type="radio" value="<?=$key?>" <?=$checked?> /><?=$v?></label>
                <?	
				}
				?>
                <input type="text" name="rday1" placeholder="" id="rday1" value="<?=$_REQUEST[rday1]?>"/> ~
                <input type="text" name="rday2" placeholder="" id="rday2" value="<?=$_REQUEST[rday2]?>"/>
                <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
                
                
            </div>
<?php
        if($member['service_type'] <= 0 || $member['service_type'] == "") {
            
            
            
?>
            <?php if($chk > 0) {?>
            <div style="float:right">
                <a href="mypage_agreement.php?mode=1" style="padding:10px;border:1px solid #000;">리셀러신청하기</a>
            </div>
            <?php }?>
            <?php if($chk > 0) {?>
            <div style="float:right">
                <a href="mypage_agreement.php?mode=3" style="padding:10px;border:1px solid #000;">분양권 신청하기</a>
            </div>
            <?php }?>    
                   
<?php
        }
?>            

            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:6%;">번호</td>
                <td style="width:6%;">구매자이름</td>
                <td style="width:6%;">구매자직급</td>
                <td style="width:6%;">구매자소속</td>
                <td style="width:6%;">판매자이름</td>
                <td style="width:6%;">판매자직급</td>
                <td style="width:6%;">결제일</td>
                <td style="width:6%;">만료일</td>
                <td style="width:6%;">잔여일수</td>
                <td style="width:6%;">결제방식</td>
                <td style="width:6%;">결제금액</td>
                <td style="width:6%;">상태</td>
              </tr>
              <?
              if($intRowCount)
              {
                  $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
                  while($row=mysqli_fetch_array($result))
                  {
                      //echo $row[mem_id]."=".$row[recommend_id];
                      $num_arr=array();
                      $sql = "select * from Gn_Member where recommend_id='$row[recommend_id]' ";
                      $res_result = mysqli_query($self_con,$sql);
                      $sInfo = mysqli_fetch_array($res_result);
                      
                      $sql = "select * from Gn_Member where recommend_id='$sInfo[mem_id]'";
                      $res_result = mysqli_query($self_con,$sql);
                      $ssInfo = mysqli_fetch_array($res_result);                      
                    	                      
                      //1 : 이용자, 2 : 직원, 3 : 일반대리점, 4:지사대리점, 5:총판 대리점
                      if($row['service_type'] == 0) {
                          $service_type = "이용자";
                      } else if($row['service_type'] == 1) {
                          $service_type = "리셀러";
                      } else if($row['service_type'] == 2) {
                          $service_type = "";
                      } else if($row['service_type'] == 3) {
                          $service_type = "분양자";
                      } else if($row['service_type'] == 4) {
                          $service_type = "";
                      } else if($row['service_type'] == 5) {
                          $service_type = "";
                      } else if($row['service_type'] == 6) {
                          $service_type = "본사";                          
                      } else {
                          $service_type="이용자";
                      }

                      if($row['service_want_type'] == 0) {
                          $service_want_level = "이용자";
                      } else if($row['service_want_type'] == 1) {
                           $service_want_level = "리셀러";                          
                      } else if($row['service_want_type'] == 2) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 3) {
                           $service_want_level = "분양자";
                      } else if($row['service_want_type'] == 4) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 5) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 6) {
                          $service_type = "본사";                          
                      } else {
                           $service_want_level = "이용자";
                      }                            

                      $remain_date=floor((strtotime($row[end_date])-time())/86400);
                    //이용자결제정보에서 구매자는 본인
                    //판매자는 추천인 아이디
                    //=====================
                    //직원 판매정보
                    //구매자 = 나를 추천한사람
                    //판매자는 = 직원자신 
                      
              ?>
              <?php if($row[service_type] == 1) {?>
              <tr>
                <td>
                    <?=$member['mem_name']?>
                </td>
                <td style="font-size:12px;"><?=$my_service_type?></td>
                <td style="font-size:12px;"><?=$member['mem_name']?></td>
                
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$service_type?></td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                
                <td><?=$remain_date;?></td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=number_format($row[TotPrice])?></td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
                </td>
              </tr>
              <?php }else{?>
              <tr>
                <td><?=$sort_no?></td>
                <td style="font-size:12px;"><?=$member['mem_name']?></td>
                <td style="font-size:12px;"><?=$my_service_type?></td>
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$service_type?></td>              
                
                
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                
                <td><?=$remain_date;?></td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=number_format($row[TotPrice])?></td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
                </td>
              </tr>              
              <?php }?>
                
              <?
                    $sort_no--;
                  }
                  ?>
              <tr>
                <td colspan="12">
                        <?
                        page_f($page,$page2,$intPageCount,"pay_form");
                        ?>
                </td>
              </tr>    
            <?
              }
              else
              {
                ?>
              <tr>
                <td colspan="12">
                    검색된 내용이 없습니다.
                </td>
              </tr>            
                <?  
              }
              ?>
            </table>
            </div>
        </div>
        </form>
        
        
        <div class="join">       
        <?
        
        
                if($member[service_type] > 1)
				    $sql_serch=" buyer_id ='{$_SESSION['one_member_id']}' ";
				else
				    $sql_serch=" recommend_id ='{$_SESSION['one_member_id']}' "; 
				    
				$sql="select * from tjd_pay_result a 
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 
				              where a.buyer_id='{$_SESSION['one_member_id']}'  and end_status='Y' ";
				$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
				$row=mysqli_fetch_array($result);
				
				$chk = false;
				if(strstr($row['member_type'],"Business")==true && $row[0]) {
				    $chk = 1;    
				}
				if(strstr($row['member_type'],"Premium")==true && $row[0]) {
				    $chk = 2;    
				}	
								    
				if($_REQUEST[search_date])
				{					
					if($_REQUEST[rday1])
					{
					$start_time=strtotime($_REQUEST[rday1]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) >=$start_time ";
					}
					if($_REQUEST[rday2])
					{
					$end_time=strtotime($_REQUEST[rday2]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) <= $end_time ";
					}
				}
				$sql="select count(no) as cnt from tjd_pay_result a
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 				
				where $sql_serch  and end_status='Y' ";
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
				$sql="select * from tjd_pay_result a 
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 
				              where $sql_serch  and end_status='Y' order by $order_name $order_status limit $int,$intPageSize";
                
				$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
				
				$row=mysqli_fetch_array($result);
				
				$chk = false;
 			
		?>

	<div class="ad_layer_info">
	</div>
	
        <form name="pay_form" action="" method="post" class="my_pay">
            <input type="hidden" name="order_name" value="<?=$order_name?>"  />
            <input type="hidden" name="order_status" value="<?=$order_status?>"/>
            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1">
        <li style="float:left;">
<?php
        if($member['service_type'] <= 0) {
           $my_service_type= $service_type = "이용자";

?>
        
            <?php echo $service_type;?>
        판매정보</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1" style="float:left">
            	<a href="mypage.php" class="a_btn_2">전체보기</a>
            	<?
				$search_date=array("date"=>"결제일","end_date"=>"만료(해지)일");
				foreach($search_date as $key=>$v)
				{
					$checked=$_REQUEST[search_date]==$key?"checked":"";
				?>
                	<label><input name="search_date" type="radio" value="<?=$key?>" <?=$checked?> /><?=$v?></label>
                <?	
				}
				?>
                <input type="text" name="rday1" placeholder="" id="rday1" value="<?=$_REQUEST[rday1]?>"/> ~
                <input type="text" name="rday2" placeholder="" id="rday2" value="<?=$_REQUEST[rday2]?>"/>
                <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
                
                
            </div>
<?php
        if($member['service_type'] <= 0 || $member['service_type'] == "") {
?>
            <?php if($chk == 1) {?>
            <div style="float:right">
                <a href="mypage_agreement.php?mode=1" style="padding:10px;border:1px solid #000;">리셀러 신청하기</a>
            </div>
            <?php }?>
            <?php if($chk == 2) {?>
            <div style="float:right">
                <a href="mypage_agreement.php?mode=3" style="padding:10px;border:1px solid #000;">분양권 신청하기</a>
            </div>
            <?php }?>            
<?php
        }
?>            

            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:6%;">번호</td>
                <td style="width:6%;">구매자이름</td>
                <td style="width:6%;">구매자직급</td>
                <td style="width:6%;">구매자소속</td>
                <td style="width:6%;">판매자이름</td>
                <td style="width:6%;">판매자직급</td>
                <td style="width:6%;">결제일</td>
                <td style="width:6%;">만료일</td>
                <td style="width:6%;">잔여일수</td>
                <td style="width:6%;">결제방식</td>
                <td style="width:6%;">결제금액</td>
                <td style="width:6%;">상태</td>
              </tr>
              <?
              if($intRowCount)
              {
                  $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
                  while($row=mysqli_fetch_array($result))
                  {
                      //echo $row[mem_id]."=".$row[recommend_id];
                      $num_arr=array();
                      $sql = "select * from Gn_Member where recommend_id='$row[recommend_id]' ";
                      $res_result = mysqli_query($self_con,$sql);
                      $sInfo = mysqli_fetch_array($res_result);
                      
                      $sql = "select * from Gn_Member where recommend_id='$sInfo[mem_id]'";
                      $res_result = mysqli_query($self_con,$sql);
                      $ssInfo = mysqli_fetch_array($res_result);                      
                    	                      
                      //1 : 이용자, 2 : 직원, 3 : 일반대리점, 4:지사대리점, 5:총판 대리점
                      if($row['service_type'] == 0) {
                          $service_type = "이용자";
                      } else if($row['service_type'] == 1) {
                          $service_type = "리셀러";
                      } else if($row['service_type'] == 2) {
                          $service_type = "";
                      } else if($row['service_type'] == 3) {
                          $service_type = "분양자";
                      } else if($row['service_type'] == 4) {
                          $service_type = "";
                      } else if($row['service_type'] == 5) {
                          $service_type = "";
                      } else if($row['service_type'] == 6) {
                          $service_type = "본사";                          
                      } else {
                          $service_type="이용자";
                      }

                      if($row['service_want_type'] == 0) {
                          $service_want_level = "이용자";
                      } else if($row['service_want_type'] == 1) {
                           $service_want_level = "리셀러";                          
                      } else if($row['service_want_type'] == 2) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 3) {
                           $service_want_level = "분양자";
                      } else if($row['service_want_type'] == 4) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 5) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 6) {
                          $service_type = "본사";                          
                      } else {
                           $service_want_level = "이용자";
                      }                            

                      $remain_date=floor((strtotime($row[end_date])-time())/86400);
                    //이용자결제정보에서 구매자는 본인
                    //판매자는 추천인 아이디
                    //=====================
                    //직원 판매정보
                    //구매자 = 나를 추천한사람
                    //판매자는 = 직원자신 
                      
              ?>
              <?php if($row[service_type] == 1) {?>
              <tr>
                <td><?=$sort_no?></td>
                <td>
                    <?=$member['mem_name']?>
                </td>
                <td style="font-size:12px;"><?=$my_service_type?></td>
                <td style="font-size:12px;"><?=$member['mem_name']?></td>
                
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$service_type?></td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                
                <td><?=$remain_date;?></td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=number_format($row[TotPrice])?></td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
                </td>
              </tr>
              <?php }else{?>
              <tr>
                <td><?=$sort_no?></td>
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$service_type?></td>
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                
                <td style="font-size:12px;"><?=$member['mem_name']?></td>
                <td style="font-size:12px;"><?=$my_service_type?></td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                
                <td><?=$remain_date;?></td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=number_format($row[TotPrice])?></td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
                </td>
              </tr>              
              <?php }?>
                
              <?
                    $sort_no--;
                  }
                  ?>
              <tr>
                <td colspan="12">
                        <?
                        page_f($page,$page2,$intPageCount,"pay_form");
                        ?>
                </td>
              </tr>    
            <?
              }
              else
              {
                ?>
              <tr>
                <td colspan="12">
                    검색된 내용이 없습니다.
                </td>
              </tr>            
                <?  
              }
              ?>
            </table>
            </div>
        </div>
        </form>
			<?php }?>        
			
      <? /* 리셀러==================== */?>
      
        <form name="pay_form" action="" method="post" class="my_pay">
            <input type="hidden" name="order_name" value="<?=$order_name?>"  />
            <input type="hidden" name="order_status" value="<?=$order_status?>"/>
            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1">
        <li style="float:left;">
<?php

                //$sql_serch =" recommend_id  ='{$_SESSION['one_member_id']}' and a.member_type in ('스텝문자','사업문자-정기','사업문자1년','기본형','사업형-일반결제','일반결제-연간타입')"; 
                $sql_serch =" recommend_id  ='{$_SESSION['one_member_id']}' "; 
				//$sql_serch=" recommend_id ='{$_SESSION['one_member_id']}' and a.member_type='기본문자'"; 
				if($_REQUEST[search_date])
				{					
					if($_REQUEST[rday1])
					{
					$start_time=strtotime($_REQUEST[rday1]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) >=$start_time ";
					}
					if($_REQUEST[rday2])
					{
					$end_time=strtotime($_REQUEST[rday2]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) <= $end_time ";
					}
				}
				$sql="select count(no) as cnt from tjd_pay_result a
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 				
				where $sql_serch   and end_status='Y' ";
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
				$sql="select * from tjd_pay_result a 
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 
				              where $sql_serch and end_status='Y' order by $order_name $order_status limit $int,$intPageSize";
				
				$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
				$row=mysqli_fetch_array($result);
				//echo $sql;
				//print_r($row);
				$chk = false;
				if($row['member_type']=="사업형-일반결제") {
				}
				
                if($member['service_type'] == 1) {
                   $my_service_type= $service_type = "리셀러";

?>
        
            <?php echo $service_type;?>
        판매정보</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1" style="float:left">
            	<a href="mypage.php" class="a_btn_2">전체보기</a>
            	<?
				$search_date=array("date"=>"결제일","end_date"=>"만료(해지)일");
				foreach($search_date as $key=>$v)
				{
					$checked=$_REQUEST[search_date]==$key?"checked":"";
				?>
                	<label><input name="search_date" type="radio" value="<?=$key?>" <?=$checked?> /><?=$v?></label>
                <?	
				}
				?>
                <input type="text" name="rday1" placeholder="" id="rday1" value="<?=$_REQUEST[rday1]?>"/> ~
                <input type="text" name="rday2" placeholder="" id="rday2" value="<?=$_REQUEST[rday2]?>"/>
                <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
                
                
            </div>
<?php
        if($member['service_type'] == 2) {
?>
            <?php if($intRowCount>=2){?>
 
            <?php }?>
<?php
        }
?>            

            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:6%;">번호</td>
                <td style="width:6%;">구매자이름</td>
                <td style="width:6%;">구매자직급</td>
                <td style="width:6%;">구매자소속</td>
                <td style="width:6%;">판매자이름</td>
                <td style="width:6%;">판매자직급</td>
                <td style="width:6%;">결제일</td>
                <td style="width:6%;">만료일</td>
                <td style="width:6%;">잔여일수</td>
                <td style="width:6%;">결제방식</td>
                <td style="width:6%;">결제금액</td>
                <td style="width:6%;">상태</td>
              </tr>
              <?
              if($intRowCount)
              {
                  $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
                  while($row=mysqli_fetch_array($result))
                  {
                      //echo $row[mem_id]."=".$row[recommend_id];
                      $num_arr=array();
                      $sql = "select * from Gn_Member where mem_id='$row[mem_id]' ";
                      $res_result = mysqli_query($self_con,$sql);
                      $sInfo = mysqli_fetch_array($res_result);
                      
                      $sql = "select * from Gn_Member where recommend_id='$sInfo[mem_id]'";
                      $res_result = mysqli_query($self_con,$sql);
                      $ssInfo = mysqli_fetch_array($res_result);                      
                    	                      
                      //1 : 이용자, 2 : 직원, 3 : 일반대리점, 4:지사대리점, 5:총판 대리점
                      /*
                      가입회원 : 회원가입만 하고 결제를 하지 않는 회원
                      0 이용자 : 셀링상품을 구입한 모든 회원 – 수당이 가지 않음.
                      1 리셀러 : 기본형을 구입한 후에 리셀러로 신청한 회원. (만원이상일 경우)
                      2 직원 : 사업형 이상을 결제한 후에 직원신청을 한 회원 (오만원이상)
                      3 대리점 : 직원으로 자신을 포함하여 5인에게 판매한 회원
                      4 지사 : 105명에게 판매한 회원
                      5 총판 : 1100명에게 판매한 회원
                      
                      */
                      if($row['service_type'] == 0) {
                          $service_type = "이용자";
                      } else if($row['service_type'] == 1) {
                          $service_type = "리셀러";
                      } else if($row['service_type'] == 2) {
                          $service_type = "";
                      } else if($row['service_type'] == 3) {
                          $service_type = "분양자";
                      } else if($row['service_type'] == 4) {
                          $service_type = "";
                      } else if($row['service_type'] == 5) {
                          $service_type = "";
                      } else if($row['service_type'] == 6) {
                          $service_type = "본사";                          
                      } else {
                          $service_type="이용자";
                      }

                      if($row['service_want_type'] == 0) {
                          $service_want_level = "이용자";
                      } else if($row['service_want_type'] == 1) {
                           $service_want_level = "리셀러";                          
                      } else if($row['service_want_type'] == 2) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 3) {
                           $service_want_level = "분양자";
                      } else if($row['service_want_type'] == 4) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 5) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 6) {
                          $service_type = "본사";                          
                      } else {
                           $service_want_level = "이용자";
                      }                       

                      $remain_date=floor((strtotime($row[end_date])-time())/86400);
                    //이용자결제정보에서 구매자는 본인
                    //판매자는 추천인 아이디
                    //=====================
                    //직원 판매정보
                    //구매자 = 나를 추천한사람
                    //판매자는 = 직원자신 
                     
              ?>
              <?php if($row[service_type] == 1) {?>
              <tr>
                <td><?=$sort_no?></td>
                <td>
                    <?=$member['mem_name']?>
                </td>
                <td style="font-size:12px;"><?=$my_service_type?></td>
                <td style="font-size:12px;"><?=$member['mem_name']?></td>
                
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$service_type?></td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                
                <td><?=$remain_date;?></td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=number_format($row[TotPrice])?></td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
                </td>
              </tr>
              <?php }else{?>
              <tr>
                <td><?=$sort_no?></td>
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$service_type?></td>
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                
                <td style="font-size:12px;"><?=$member['mem_name']?></td>
                <td style="font-size:12px;"><?=$my_service_type?></td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                
                <td><?=$remain_date;?></td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=number_format($row[TotPrice])?></td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
                </td>
              </tr>              
              <?php }?>
                
              <?
                    $sort_no--;
                  }
                  ?>
              <tr>
                <td colspan="12">
                        <?
                        page_f($page,$page2,$intPageCount,"pay_form");
                        ?>
                </td>
              </tr>    
            <?
              }
              else
              {
                ?>
              <tr>
                <td colspan="12">
                    검색된 내용이 없습니다.
                </td>
              </tr>            
                <?  
              }
              ?>
            </table>
            </div>
        </div>
        </form>      
			<?php }?>        
        			
        
      <? /* 직원==================== */?>
<?php
if($member['service_type'] == 2) {
?>      
        <form name="pay_form" action="" method="post" class="my_pay">
            <input type="hidden" name="order_name" value="<?=$order_name?>"  />
            <input type="hidden" name="order_status" value="<?=$order_status?>"/>
            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1">
        <li style="float:left;">
<?php

				$sql_serch=" recommend_id ='{$_SESSION['one_member_id']}' "; 
				if($_REQUEST[search_date])
				{					
					if($_REQUEST[rday1])
					{
					$start_time=strtotime($_REQUEST[rday1]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) >=$start_time ";
					}
					if($_REQUEST[rday2])
					{
					$end_time=strtotime($_REQUEST[rday2]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) <= $end_time ";
					}
				}
				$sql="select count(no) as cnt from tjd_pay_result a
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 				
				where $sql_serch  and end_status='Y' ";
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
				$sql="select * from tjd_pay_result a 
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 
				              where $sql_serch   and end_status='Y'  order by $order_name $order_status limit $int,$intPageSize";
				$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
				$row=mysqli_fetch_array($result);
				$chk = false;
				if($row['member_type']=="사업형-일반결제") {
				    $chk = true;    
				}
				
        
           $my_service_type= $service_type = "직원";

?>
        
            <?php echo $service_type;?>
        판매정보</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1" style="float:left">
            	<a href="mypage.php" class="a_btn_2">전체보기</a>
            	<?
				$search_date=array("date"=>"결제일","end_date"=>"만료(해지)일");
				foreach($search_date as $key=>$v)
				{
					$checked=$_REQUEST[search_date]==$key?"checked":"";
				?>
                	<label><input name="search_date" type="radio" value="<?=$key?>" <?=$checked?> /><?=$v?></label>
                <?	
				}
				?>
                <input type="text" name="rday1" placeholder="" id="rday1" value="<?=$_REQUEST[rday1]?>"/> ~
                <input type="text" name="rday2" placeholder="" id="rday2" value="<?=$_REQUEST[rday2]?>"/>
                <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
                
                
            </div>
<?php
        if($member['service_type'] == 2) {
?>
            <?php if($intRowCount>=4){?>
            <div style="float:right">
                <a href="mypage_agreement.php" style="padding:10px;border:1px solid #000;">대리점 신청하기</a>
            </div>
            <?php }?>
<?php
        }
?>            

            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:6%;">번호</td>
                <td style="width:6%;">구매자이름</td>
                <td style="width:6%;">구매자직급</td>
                <td style="width:6%;">구매자소속</td>
                <td style="width:6%;">판매자이름</td>
                <td style="width:6%;">판매자직급</td>
                <td style="width:6%;">결제일</td>
                <td style="width:6%;">만료일</td>
                <td style="width:6%;">잔여일수</td>
                <td style="width:6%;">결제방식</td>
                <td style="width:6%;">결제금액</td>
                <td style="width:6%;">상태</td>
              </tr>
              <?
              if($intRowCount)
              {
                  $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
                  while($row=mysqli_fetch_array($result))
                  {
                      //echo $row[mem_id]."=".$row[recommend_id];
                      $num_arr=array();
                      $sql = "select * from Gn_Member where mem_id='$row[mem_id]' ";
                      $res_result = mysqli_query($self_con,$sql);
                      $sInfo = mysqli_fetch_array($res_result);
                      
                      $sql = "select * from Gn_Member where recommend_id='$sInfo[mem_id]'";
                      $res_result = mysqli_query($self_con,$sql);
                      $ssInfo = mysqli_fetch_array($res_result);                      
                    	                      
                      //1 : 이용자, 2 : 직원, 3 : 일반대리점, 4:지사대리점, 5:총판 대리점
                      /*
                      가입회원 : 회원가입만 하고 결제를 하지 않는 회원
                      0 이용자 : 셀링상품을 구입한 모든 회원 – 수당이 가지 않음.
                      1 리셀러 : 기본형을 구입한 후에 리셀러로 신청한 회원. (만원이상일 경우)
                      2 직원 : 사업형 이상을 결제한 후에 직원신청을 한 회원 (오만원이상)
                      3 대리점 : 직원으로 자신을 포함하여 5인에게 판매한 회원
                      4 지사 : 105명에게 판매한 회원
                      5 총판 : 1100명에게 판매한 회원
                      
                      */
                      if($row['service_type'] == 0) {
                          $service_type = "이용자";
                      } else if($row['service_type'] == 1) {
                          $service_type = "리셀러";
                      } else if($row['service_type'] == 2) {
                          $service_type = "";
                      } else if($row['service_type'] == 3) {
                          $service_type = "분양자";
                      } else if($row['service_type'] == 4) {
                          $service_type = "";
                      } else if($row['service_type'] == 5) {
                          $service_type = "";
                      } else if($row['service_type'] == 6) {
                          $service_type = "본사";                          
                      } else {
                          $service_type="이용자";
                      }

                      if($row['service_want_type'] == 0) {
                          $service_want_level = "이용자";
                      } else if($row['service_want_type'] == 1) {
                           $service_want_level = "리셀러";                          
                      } else if($row['service_want_type'] == 2) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 3) {
                           $service_want_level = "분양자";
                      } else if($row['service_want_type'] == 4) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 5) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 6) {
                          $service_type = "본사";                          
                      } else {
                           $service_want_level = "이용자";
                      }                        

                      $remain_date=floor((strtotime($row[end_date])-time())/86400);
                    //이용자결제정보에서 구매자는 본인
                    //판매자는 추천인 아이디
                    //=====================
                    //직원 판매정보
                    //구매자 = 나를 추천한사람
                    //판매자는 = 직원자신 
                      
              ?>
              <?php if($row[service_type] == 1) {?>
              <tr>
                <td><?=$sort_no?></td>
                <td>
                    <?=$sInfo['mem_name']?>
                </td>
                <td style="font-size:12px;"><?=$my_service_type?></td>
                <td style="font-size:12px;"><?=$member['mem_name']?></td>
                
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$service_type?></td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                
                <td><?=$remain_date;?></td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=number_format($row[TotPrice])?></td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
                </td>
              </tr>
              <?php }else{?>
              <tr>
                <td><?=$sort_no?></td>
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$service_type?></td>
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                
                <td style="font-size:12px;"><?=$member['mem_name']?></td>
                <td style="font-size:12px;"><?=$my_service_type?></td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                
                <td><?=$remain_date;?></td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=number_format($row[TotPrice])?></td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
                </td>
              </tr>              
              <?php }?>
                
              <?
                    $sort_no--;
                  }
                  ?>
              <tr>
                <td colspan="12">
                        <?
                        page_f($page,$page2,$intPageCount,"pay_form");
                        ?>
                </td>
              </tr>    
            <?
              }
              else
              {
                ?>
              <tr>
                <td colspan="12">
                    검색된 내용이 없습니다.
                </td>
              </tr>            
                <?  
              }
              ?>
            </table>
            </div>
        </div>
        </form>      
			<?php }?>        
        
      <? /* 일반 대리점 ==================== */?>
<?php
if($member['service_type'] > 2) {
?>            
        <form name="pay_form" action="" method="post" class="my_pay">
            <input type="hidden" name="order_name" value="<?=$order_name?>"  />
            <input type="hidden" name="order_status" value="<?=$order_status?>"/>
            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1">
        <li style="float:left;">
<?php
				$sql_serch =" recommend_id  ='{$_SESSION['one_member_id']}' "; 
				if($_REQUEST[search_date])
				{					
					if($_REQUEST[rday1])
					{
					$start_time=strtotime($_REQUEST[rday1]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) >=$start_time ";
					}
					if($_REQUEST[rday2])
					{
					$end_time=strtotime($_REQUEST[rday2]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) <= $end_time ";
					}
				}
				$sql="select count(no) as cnt from tjd_pay_result a
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 				
				where $sql_serch   and end_status='Y' ";
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
				$sql="select * from tjd_pay_result a 
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 
				              where $sql_serch  and end_status='Y'  order by $order_name $order_status limit $int,$intPageSize";
				$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
				$row=mysqli_fetch_array($result);
				$chk = false;
				if($row['member_type']=="사업형-일반결제") {
				    $chk = true;    
				}
				 
            $my_service_type=$service_type = "분양자";
        
?>
        
            <?php echo $service_type;?>
        판매정보</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1" style="float:left">
            	<a href="mypage.php" class="a_btn_2">전체보기</a>
            	<?
				$search_date=array("date"=>"결제일","end_date"=>"만료(해지)일");
				foreach($search_date as $key=>$v)
				{
					$checked=$_REQUEST[search_date]==$key?"checked":"";
				?>
                	<label><input name="search_date" type="radio" value="<?=$key?>" <?=$checked?> /><?=$v?></label>
                <?	
				}
				?>
                <input type="text" name="rday1" placeholder="" id="rday1" value="<?=$_REQUEST[rday1]?>"/> ~
                <input type="text" name="rday2" placeholder="" id="rday2" value="<?=$_REQUEST[rday2]?>"/>
                <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
                
                
            </div>
<?php
          if($member['service_type'] == 3) {
?>
            <?php if($intRowCount>=105){?>
            <div style="float:right">
                <a href="mypage_agreement.php" style="padding:10px;border:1px solid #000;">지사 대리점 신청하기</a>
            </div>
            <?php }?>
<?php
        }  
?>            

            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:6%;">번호</td>
                <td style="width:6%;">구매자이름</td>
                <td style="width:6%;">구매자직급</td>
                <td style="width:6%;">구매자소속</td>
                <td style="width:6%;">판매자이름</td>
                <td style="width:6%;">판매자직급</td>
                <td style="width:6%;">결제일</td>
                <td style="width:6%;">만료일</td>
                <td style="width:6%;">잔여일수</td>
                <td style="width:6%;">결제방식</td>
                <td style="width:6%;">결제금액</td>
                <td style="width:6%;">상태</td>
              </tr>
              <?
              if($intRowCount)
              {
                  $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
                  while($row=mysqli_fetch_array($result))
                  {
                      //echo $row[mem_id]."=".$row[recommend_id];
                      $num_arr=array();
                      $sql = "select * from Gn_Member where mem_id='$row[mem_id]' ";
                      $res_result = mysqli_query($self_con,$sql);
                      $sInfo = mysqli_fetch_array($res_result);
                      
                      $sql = "select * from Gn_Member where recommend_id='$sInfo[mem_id]'";
                      $res_result = mysqli_query($self_con,$sql);
                      $ssInfo = mysqli_fetch_array($res_result);                      
                    	                      
                      //1 : 이용자, 2 : 직원, 3 : 일반대리점, 4:지사대리점, 5:총판 대리점
                      if($row['service_type'] == 0) {
                          $service_type = "이용자";
                      } else if($row['service_type'] == 1) {
                          $service_type = "리셀러";
                      } else if($row['service_type'] == 2) {
                          $service_type = "";
                      } else if($row['service_type'] == 3) {
                          $service_type = "분양자";
                      } else if($row['service_type'] == 4) {
                          $service_type = "";
                      } else if($row['service_type'] == 5) {
                          $service_type = "";
                      } else if($row['service_type'] == 6) {
                          $service_type = "본사";                          
                      } else {
                          $service_type="이용자";
                      }

                      if($row['service_want_type'] == 0) {
                          $service_want_level = "이용자";
                      } else if($row['service_want_type'] == 1) {
                           $service_want_level = "리셀러";                          
                      } else if($row['service_want_type'] == 2) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 3) {
                           $service_want_level = "분양자";
                      } else if($row['service_want_type'] == 4) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 5) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 6) {
                          $service_type = "본사";                          
                      } else {
                           $service_want_level = "이용자";
                      }   
                      $remain_date=floor((strtotime($row[end_date])-time())/86400);
                    //이용자결제정보에서 구매자는 본인
                    //판매자는 추천인 아이디
                    //=====================
                    //직원 판매정보
                    //구매자 = 나를 추천한사람
                    //판매자는 = 직원자신 
                      
              ?>
              <?php if($row[service_type] == 1) {?>
              <tr>
                <td><?=$sort_no?></td>
                <td>
                    <?=$member['mem_name']?>
                </td>
                <td style="font-size:12px;"><?=$my_service_type?></td>
                <td style="font-size:12px;"><?=$member['mem_name']?></td>
                
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$service_type?></td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                
                <td><?=$remain_date;?></td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=number_format($row[TotPrice])?></td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
                </td>
              </tr>
              <?php }else{?>
              <tr>
                <td><?=$sort_no?></td>
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$service_type?></td>
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                
                <td style="font-size:12px;"><?=$member['mem_name']?></td>
                <td style="font-size:12px;"><?=$my_service_type?></td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                
                <td><?=$remain_date;?></td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=number_format($row[TotPrice])?></td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
                </td>
              </tr>              
              <?php }?>
                
              <?
                    $sort_no--;
                  }
                  ?>
              <tr>
                <td colspan="12">
                        <?
                        page_f($page,$page2,$intPageCount,"pay_form");
                        ?>
                </td>
              </tr>    
            <?
              }
              else
              {
                ?>
              <tr>
                <td colspan="12">
                    검색된 내용이 없습니다.
                </td>
              </tr>            
                <?  
              }
              ?>
            </table>
            </div>
        </div>
        </form>         
      	<?php } ?>
        
      <? /* 지사 대리점==================== */?>
<?php
if($member['service_type']  >3 ) {
?>
        <form name="pay_form" action="" method="post" class="my_pay">
            <input type="hidden" name="order_name" value="<?=$order_name?>"  />
            <input type="hidden" name="order_status" value="<?=$order_status?>"/>
            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1">
        <li style="float:left;">
<?php
				$sql_serch=" recommend_id ='{$_SESSION['one_member_id']}' and recommend_type='60'"; 
				if($_REQUEST[search_date])
				{					
					if($_REQUEST[rday1])
					{
					$start_time=strtotime($_REQUEST[rday1]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) >=$start_time ";
					}
					if($_REQUEST[rday2])
					{
					$end_time=strtotime($_REQUEST[rday2]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) <= $end_time ";
					}
				}
				$sql="select count(no) as cnt from tjd_pay_result a
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 				
				where $sql_serch  and end_status='Y' ";
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
				$sql="select * from tjd_pay_result a 
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 
				              where $sql_serch  and end_status='Y'  order by $order_name $order_status limit $int,$intPageSize";
				$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
				$row=mysqli_fetch_array($result);
				$chk = false;
				if($row['member_type']=="사업형-일반결제") {
				    $chk = true;    
				}
				
        
            $my_service_type=$service_type = "지사대리점";
        
?>
        
            <?php echo $service_type;?>
        판매정보</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1" style="float:left">
            	<a href="mypage.php" class="a_btn_2">전체보기</a>
            	<?
				$search_date=array("date"=>"결제일","end_date"=>"만료(해지)일");
				foreach($search_date as $key=>$v)
				{
					$checked=$_REQUEST[search_date]==$key?"checked":"";
				?>
                	<label><input name="search_date" type="radio" value="<?=$key?>" <?=$checked?> /><?=$v?></label>
                <?	
				}
				?>
                <input type="text" name="rday1" placeholder="" id="rday1" value="<?=$_REQUEST[rday1]?>"/> ~
                <input type="text" name="rday2" placeholder="" id="rday2" value="<?=$_REQUEST[rday2]?>"/>
                <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
                
                
            </div>
<?php
        if($member['service_type'] == 4) {
?>
            <?php if($intRowCount>=1100){?>
            <div style="float:right">
                <a href="mypage_agreement.php" style="padding:10px;border:1px solid #000;">총판 대리점 신청하기</a>
            </div>
            <?php }?>

<?php 
        }
?>            

            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:6%;">번호</td>
                <td style="width:6%;">구매자이름</td>
                <td style="width:6%;">구매자직급</td>
                <td style="width:6%;">구매자소속</td>
                <td style="width:6%;">판매자이름</td>
                <td style="width:6%;">판매자직급</td>
                <td style="width:6%;">결제일</td>
                <td style="width:6%;">만료일</td>
                <td style="width:6%;">잔여일수</td>
                <td style="width:6%;">결제방식</td>
                <td style="width:6%;">결제금액</td>
                <td style="width:6%;">상태</td>
              </tr>
              <?
              if($intRowCount)
              {
                  $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
                  while($row=mysqli_fetch_array($result))
                  {
                      //echo $row[mem_id]."=".$row[recommend_id];
                      $num_arr=array();
                      $sql = "select * from Gn_Member where mem_id='$row[mem_id]' ";
                      $res_result = mysqli_query($self_con,$sql);
                      $sInfo = mysqli_fetch_array($res_result);
                      
                      $sql = "select * from Gn_Member where recommend_id='$sInfo[mem_id]'";
                      $res_result = mysqli_query($self_con,$sql);
                      $ssInfo = mysqli_fetch_array($res_result);                      
                    	                      
                      //1 : 이용자, 2 : 직원, 3 : 일반대리점, 4:지사대리점, 5:총판 대리점
                      if($row['service_type'] == 0) {
                          $service_type = "이용자";
                      } else if($row['service_type'] == 1) {
                          $service_type = "리셀러";
                      } else if($row['service_type'] == 2) {
                          $service_type = "";
                      } else if($row['service_type'] == 3) {
                          $service_type = "분양자";
                      } else if($row['service_type'] == 4) {
                          $service_type = "";
                      } else if($row['service_type'] == 5) {
                          $service_type = "";
                      } else if($row['service_type'] == 6) {
                          $service_type = "본사";                          
                      } else {
                          $service_type="이용자";
                      }

                      if($row['service_want_type'] == 0) {
                          $service_want_level = "이용자";
                      } else if($row['service_want_type'] == 1) {
                           $service_want_level = "리셀러";                          
                      } else if($row['service_want_type'] == 2) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 3) {
                           $service_want_level = "분양자";
                      } else if($row['service_want_type'] == 4) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 5) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 6) {
                          $service_type = "본사";                          
                      } else {
                           $service_want_level = "이용자";
                      }                               

                      $remain_date=floor((strtotime($row[end_date])-time())/86400);
                    //이용자결제정보에서 구매자는 본인
                    //판매자는 추천인 아이디
                    //=====================
                    //직원 판매정보
                    //구매자 = 나를 추천한사람
                    //판매자는 = 직원자신 
                      
              ?>
              <?php if($row[service_type] == 1) {?>
              <tr>
                <td><?=$sort_no?></td>
                <td>
                    <?=$member['mem_name']?>
                </td>
                <td style="font-size:12px;"><?=$my_service_type?></td>
                <td style="font-size:12px;"><?=$member['mem_name']?></td>
                
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$service_type?></td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                
                <td><?=$remain_date;?></td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=number_format($row[TotPrice])?></td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
                </td>
              </tr>
              <?php }else{?>
              <tr>
                <td><?=$sort_no?></td>
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$service_type?></td>
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                
                <td style="font-size:12px;"><?=$member['mem_name']?></td>
                <td style="font-size:12px;"><?=$my_service_type?></td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                
                <td><?=$remain_date;?></td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=number_format($row[TotPrice])?></td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
                </td>
              </tr>              
              <?php }?>
                
              <?
                    $sort_no--;
                  }
                  ?>
              <tr>
                <td colspan="12">
                        <?
                        page_f($page,$page2,$intPageCount,"pay_form");
                        ?>
                </td>
              </tr>    
            <?
              }
              else
              {
                ?>
              <tr>
                <td colspan="12">
                    검색된 내용이 없습니다.
                </td>
              </tr>            
                <?  
              }
              ?>
            </table>
            </div>
        </div>
        </form>        
        <?php }?>
        
        
      <? /* 총판 대리점==================== */?>
<?php
if($member['service_type']  >  4) {
?>
      
        <form name="pay_form" action="" method="post" class="my_pay">
            <input type="hidden" name="order_name" value="<?=$order_name?>"  />
            <input type="hidden" name="order_status" value="<?=$order_status?>"/>
            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1">
        <li style="float:left;">
<?php
				$sql_serch=" recommend_id ='{$_SESSION['one_member_id']}' and recommend_type='70'"; 
				if($_REQUEST[search_date])
				{					
					if($_REQUEST[rday1])
					{
					$start_time=strtotime($_REQUEST[rday1]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) >=$start_time ";
					}
					if($_REQUEST[rday2])
					{
					$end_time=strtotime($_REQUEST[rday2]);
					$sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) <= $end_time ";
					}
				}
				$sql="select count(no) as cnt from tjd_pay_result a
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 				
				where $sql_serch   and end_status='Y' ";
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
				$sql="select * from tjd_pay_result a 
				          left join Gn_Member b
				                 on b.mem_id=a.buyer_id 
				              where $sql_serch   and end_status='Y'  order by $order_name $order_status limit $int,$intPageSize";
				$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
				$row=mysqli_fetch_array($result);
				$chk = false;
				if($row['member_type']=="사업형-일반결제") {
				    $chk = true;    
				}
				
        
            $my_service_type=$service_type = "총판대리점";

?>
        
            <?php echo $service_type;?>
        판매정보</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1" style="float:left">
            	<a href="mypage.php" class="a_btn_2">전체보기</a>
            	<?
				$search_date=array("date"=>"결제일","end_date"=>"만료(해지)일");
				foreach($search_date as $key=>$v)
				{
					$checked=$_REQUEST[search_date]==$key?"checked":"";
				?>
                	<label><input name="search_date" type="radio" value="<?=$key?>" <?=$checked?> /><?=$v?></label>
                <?	
				}
				?>
                <input type="text" name="rday1" placeholder="" id="rday1" value="<?=$_REQUEST[rday1]?>"/> ~
                <input type="text" name="rday2" placeholder="" id="rday2" value="<?=$_REQUEST[rday2]?>"/>
                <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
                
                
            </div>
        

            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:6%;">번호</td>
                <td style="width:6%;">구매자이름</td>
                <td style="width:6%;">구매자직급</td>
                <td style="width:6%;">구매자소속</td>
                <td style="width:6%;">판매자이름</td>
                <td style="width:6%;">판매자직급</td>
                <td style="width:6%;">결제일</td>
                <td style="width:6%;">만료일</td>
                <td style="width:6%;">잔여일수</td>
                <td style="width:6%;">결제방식</td>
                <td style="width:6%;">결제금액</td>
                <td style="width:6%;">상태</td>
              </tr>
              <?
              if($intRowCount)
              {
                  $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
                  while($row=mysqli_fetch_array($result))
                  {
                      //echo $row[mem_id]."=".$row[recommend_id];
                      $num_arr=array();
                      $sql = "select * from Gn_Member where recommend_id='$row[recommend_id]' ";
                      $res_result = mysqli_query($self_con,$sql);
                      $sInfo = mysqli_fetch_array($res_result);
                      
                      $sql = "select * from Gn_Member where recommend_id='$sInfo[mem_id]'";
                      $res_result = mysqli_query($self_con,$sql);
                      $ssInfo = mysqli_fetch_array($res_result);                      
                    	                      
                      //1 : 이용자, 2 : 직원, 3 : 일반대리점, 4:지사대리점, 5:총판 대리점
                      if($row['service_type'] == 0) {
                          $service_type = "이용자";
                      } else if($row['service_type'] == 1) {
                          $service_type = "리셀러";
                      } else if($row['service_type'] == 2) {
                          $service_type = "";
                      } else if($row['service_type'] == 3) {
                          $service_type = "분양자";
                      } else if($row['service_type'] == 4) {
                          $service_type = "";
                      } else if($row['service_type'] == 5) {
                          $service_type = "";
                      } else if($row['service_type'] == 6) {
                          $service_type = "본사";                          
                      } else {
                          $service_type="이용자";
                      }

                      if($row['service_want_type'] == 0) {
                          $service_want_level = "이용자";
                      } else if($row['service_want_type'] == 1) {
                           $service_want_level = "리셀러";                          
                      } else if($row['service_want_type'] == 2) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 3) {
                           $service_want_level = "분양자";
                      } else if($row['service_want_type'] == 4) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 5) {
                           $service_want_level = "";
                      } else if($row['service_want_type'] == 6) {
                          $service_type = "본사";                          
                      } else {
                           $service_want_level = "이용자";
                      }                              

                      $remain_date=floor((strtotime($row[end_date])-time())/86400);
                    //이용자결제정보에서 구매자는 본인
                    //판매자는 추천인 아이디
                    //=====================
                    //직원 판매정보
                    //구매자 = 나를 추천한사람
                    //판매자는 = 직원자신 
                      
              ?>
              <?php if($row[service_type] == 1) {?>
              <tr>
                <td><?=$sort_no?></td>
                <td>
                    <?=$member['mem_name']?>
                </td>
                <td style="font-size:12px;"><?=$my_service_type?></td>
                <td style="font-size:12px;"><?=$member['mem_name']?></td>
                
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$service_type?></td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                
                <td><?=$remain_date;?></td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=number_format($row[TotPrice])?></td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
                </td>
              </tr>
              <?php }else{?>
              <tr>
                <td><?=$sort_no?></td>
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                <td style="font-size:12px;"><?=$service_type?></td>
                <td style="font-size:12px;"><?=$sInfo['mem_name']?></td>
                
                <td style="font-size:12px;"><?=$member['mem_name']?></td>
                <td style="font-size:12px;"><?=$my_service_type?></td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                
                <td><?=$remain_date;?></td>
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=number_format($row[TotPrice])?></td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
                </td>
              </tr>              
              <?php }?>
                
              <?
                    $sort_no--;
                  }
                  ?>
              <tr>
                <td colspan="12">
                        <?
                        page_f($page,$page2,$intPageCount,"pay_form");
                        ?>
                </td>
              </tr>    
            <?
              }
              else
              {
                ?>
              <tr>
                <td colspan="12">
                    검색된 내용이 없습니다.
                </td>
              </tr>            
                <?  
              }
              ?>
            </table>
            </div>
        </div>
        </form>                         
        <?php }?>
<?php 
if($member['service_type'] >= 3) {
    $query = "";
    $sql_serch = "";
	if($_REQUEST[krday1] || $_REQUEST[krday2])
	{					
		if($_REQUEST[krday1])
		{
		$start_time=strtotime($_REQUEST[krday1]);
		$sql_serch.=" and unix_timestamp(a.date) >=$start_time ";
		}
		if($_REQUEST[sday2])
		{
		$end_time=strtotime($_REQUEST[krday2]);
		$sql_serch.=" and unix_timestamp(a.date) <= $end_time ";
		}
	}    
	$query = "
        	SELECT 
        	    SQL_CALC_FOUND_ROWS 
        	   *
        	FROM Gn_Member 
        	WHERE recommend_id='".$_SESSION['one_member_id']."' and branch_yn='N'
	              $sql_serch";
	$res	    = mysqli_query($self_con,$query);
	$totalCnt	=  mysqli_num_rows($res);	
                	
	$intRowCount=$totalCnt;    
	
	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
	
    $orderQuery .= "
    	ORDER BY a.no DESC
    	$limitStr
    ";   	
    
?>
  <!--      <form name="payment_form" action="" method="post" class="my_pay">
            <input type="hidden" name="order_name" value="<?=$order_name?>"  />
            <input type="hidden" name="order_status" value="<?=$order_status?>"/>
            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1">
        <li style="float:left;">추천인리스트</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1">
                <input type="text" name="krday1" placeholder="" id="krday1" value="<?=$_REQUEST[krday1]?>"/> ~
                <input type="text" name="krday2" placeholder="" id="krday2" value="<?=$_REQUEST[krday2]?>"/>
                <a href="javascript:void(0)" onclick="payment_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>                                            
                <input type="button" value="사업자 승인" style="padding:7px" onclick="save()">
            </div>
            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:5%;">번호</td>
                <td style="width:10%;">회원구분</td>
                <td style="width:10%;">이름</td>
                <td style="width:10%;">이메일</td>
                <td style="width:15%;">전화번호</td>
                <td style="width:10%;">거주지</td>
                <td style="width:10%;">직업</td>
                <td style="width:15%;">생년월일</td>
                <td style="width:10%;">가입일시</td>
                <td style="width:10%;">사업자<input type="checkbox" name="allchk" id="allchk"></td>
              </tr>
                <?
                if($intRowCount)
                {
                  $num = 1;
                    while($row=mysqli_fetch_array($res))
                    {
                      
                       $query = "
                           	SELECT 
                           	    SQL_CALC_FOUND_ROWS 
                           	    *
                           	FROM Gn_Member 
                           	WHERE 1=1 and mem_id='$row[mem_id]'";
                       $sres	    = mysqli_query($self_con,$query);                    
                       $srow = mysqli_fetch_array($sres);
                       if($row['service_type'] == 1) {
                           $mem_level = "리셀러";
                       } else if($row['service_type'] == 2) {
                            $mem_level = "";
                       } else if($row['service_type'] == 3) {
                            $mem_level = "분양자";
                       } else if($row['service_type'] == 4) {
                            $mem_level = "";
                       } else if($row['service_type'] == 5) {
                            $mem_level = "";
                       } else {
                            $mem_level = "이용자";
                       }
                ?>
              <tr>
                <td><?=$num++?></td>
                <td>
                    <?=$mem_level?>
                </td>
                <td>
                    <?=$row['mem_name']?>
                </td>                
                <td>
                    <?=$row['mem_email']?>
                </td>                
                <td>
                    <?=$row['mem_phone']?>
                </td>
                <td>
                    <?=$row['mem_addr1']?>
                </td>
                <td>
                    <?=$row['zy']?>
                </td>                  
                <td>
                    <?=$row['mem_birth']?>
                </td>              
                <td>
                    <?=$row['first_regist']?>
                </td>                                              
               <td>
                    <input type="hidden" name="org_mem_code" value="<?=$row['mem_code']?>">
                    <input type="checkbox" class="checkbox" name="mem_code" value="<?=$row['mem_code']?>" <?=$row['business_yn']=="Y"?"checked":""?>>
                </td>                                                              
              </tr>
              <?
                  $sort_no--;
              }
              ?>
              <tr>
                <td colspan="10">
                        <?
                        page_f($page,$page2,$intPageCount,"payment_form");
                        ?>
                </td>
              </tr>    
            <?
              }
              else
              {
                ?>
              <tr>
                <td colspan="10">
                    검색된 내용이 없습니다.
                </td>
              </tr>            
                <?  
              }
              ?>
            </table>
            </div>
        </div>
        </form> 
<?php }?>
     
    </div>
</div> -->
<Script>
$(function() {
    $('#allchk').bind("change",function(){
         if($(this).is(":checked") == true) {
            $('.checkbox').prop("checked", true);
         } else {
            $('.checkbox').prop("checked", false);
         }
    });
});

function save() {
    var mem_code = "";
    var org_mem_code = "";
    
    $('input[name=org_mem_code').each(function() {
       if(org_mem_code)
           org_mem_code += ","+$(this).val();
       else
           org_mem_code = $(this).val();
    });
    
    $('.checkbox:checked').each(function() {
       if(mem_code)
           mem_code += ","+$(this).val();
       else
           mem_code = $(this).val();
    });  

	$.ajax({
		 type:"POST",
		 url:"ajax/ajax_save.php",
		 data:{
			 mode : "save_business",
			 org_mem_code: org_mem_code,
			 mem_code: mem_code,
			 },
		 success:function(data){
		 	if(data.result == "fail") {
		 	    alert(data.msg);
		 	} else {
		 	    alert('변경되었습니다.');
		 	    location ='mypage_payment.php';
		 	    return;
		 	}
		}
	});    
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
		 	if(data.result == "fail") {
		 	    alert(data.msg);
		 	} else {
		 	    alert('신청되었습니다.');
		 	    location ='mypage_payment.php';
		 	    return;
		 	}
		}
	});
		
}
function showInfo() {
    if($('#outLayer').css("display") == "none") {
        $('#outLayer').show();
    } else {
        $('#outLayer').hide();
    }
}

</Script>
<?
include_once "_foot.php";
?>

<script>
//회원가입체크
function join_check(frm,modify)
{
	if(!wrestSubmit(frm))
		return  false;
	var id_str="";
	var app_pwd="";
	var web_pwd="";
	var phone_str="";
	if(document.getElementsByName('pwd')[0])
	app_pwd=document.getElementsByName('pwd')[0].value;
	if(document.getElementsByName('pwd')[1])
	web_pwd=document.getElementsByName('pwd')[1].value;	
	if(frm.id)	
	id_str=frm.id.value;	
	var msg=modify?"수정하시겠습니까?":"등록하시겠습니까?";	
	var email_str=frm.email_1.value+"@"+frm.email_2.value+frm.email_3.value;
	if(!modify)
	phone_str=frm.mobile_1.value+"-"+frm.mobile_2.value+"-"+frm.mobile_3.value;
	var birth_str=frm.birth_1.value+"-"+frm.birth_2.value+"-"+frm.birth_3.value;
	var is_message_str=frm.is_message.checked?"Y":"N";
	
	var bank_name = frm.bank_name.value;
	var bank_account = frm.bank_account.value;
	var bank_owner = frm.bank_owner.value;
	
	if(confirm(msg))
	{
		$.ajax({
			 type:"POST",
			 url:"ajax/ajax.php",
			 data:{
				 join_id:id_str,
				 join_nick:frm.nick.value,
				 join_pwd:app_pwd,
				 join_web_pwd:web_pwd,				 
				 join_name:frm.name.value,
				 join_email:email_str,
				 join_phone:phone_str,
				 join_add1:frm.add1.value,
				 join_zy:frm.zy.value,
				 join_birth:birth_str,
				 join_is_message:is_message_str,
				 join_modify:modify,
				 bank_name:bank_name,
				 bank_account:bank_account,
				 bank_owner:bank_owner
				 },
			 success:function(data){$("#ajax_div").html(data)}
			})
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
			})        
        
    }
}
</script>      