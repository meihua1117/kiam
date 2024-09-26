<?
$path="./";
include_once "_head.php";
if(!$_SESSION['one_member_id'])
{

?>
<script language="javascript">
location.replace('/ma.php');
</script>
<?
exit;
}
	$sql="select * from Gn_Member  where mem_id='".$_SESSION['one_member_id']."'";
	$sresul_num=mysqli_query($self_con, $sql);
	$data=mysqli_fetch_array($sresul_num);	
	
	if($data['intro_message'] =="") {
		$data['intro_message'] = "안녕하세요\n
\n
귀하의 휴대폰으로\n
기부문자발송을 시작합니다.\n
\n
협조해주셔서 감사합니다^^
";
	}
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
    <!--
	<div class="big_1">
    	<div class="m_div">
        	<div class="left_sub_menu">
                <a href="./">홈</a> > 
                <a href="mypage.php">마이페이지</a>
            </div>
            <div class="right_sub_menu">&nbsp;</div>
            <p style="clear:both;"></p>
    	</div>
   </div>
   -->
   <?php include_once "mypage_base_navi_coach.php";?>
   <div class="m_div">
 <!--       <div><img src="images/sub_mypage_03.jpg" /></div> -->
        <div class="join">       
        <form name="join_form" id="join_form" method="post"  enctype="multipart/form-data">
        <input type="hidden" name="join_modify" value="<?php echo $member_1['mem_code'];?>" />    
        <div class="a1">
        <li style="float:left;">회원정보 수정</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>         
        <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <colgroup>
            <col width="100" />
            <col width="300" />
            <col width="100" />
            <col width="300" />
        </colgroup>
        <tr>
        <td>아이디</td>
        <td><?=$member_1['mem_id']?></td>
        </tr>
		<tr>
		<td>회원구분</td>
        <td>
            <?php if($member_1['mem_leb'] == "50") {?>
                사업회원
            <?php } else {?>
                일반회원
     <!--           <input type="button" name="" value="마케터 회원신청" onclick="location='mypage_business.php'"> -->
            <?php } ?>
        </td>
        </tr>

        <tr>
        <td>휴대폰번호</td>
        <td  colspan="3"><?=$member_1['mem_phone']?></td>
        </tr>  
        <tr>
        <td>성명/성별</td>
        <td ><input type="text" name="name" itemname='성명' value="<?=$member_1['mem_name']?>" required /></td>
		</tr>  
        <tr>
		<td>프로필사진</td>
        <td ><input type="file" name="profile" id="profile" itemname='사진' /><?php if($member_1['profile'] != "") { echo "<img src='$member_1[profile]'>"; }?></td>      
        </tr>
        <td>생년월일</td>
        <td colspan="3">
        <li><select name="birth_1" type='select-one' required itemname='생년'>
        <option value="">년</option>
        <?
        for($i=date("Y"); $i>1899; $i--)
        {
        $selected=$m_birth_arr[0]==$i?"selected":"";
        ?>
        <option value="<?=$i?>" <?=$selected?>><?=$i?></option>
        <?
        }
        ?>
        </select></li>
        <li>
        <select name="birth_2" required type='select-one' itemname='월'>
        <option value="">월</option>
        <?
        for($i=1; $i<13; $i++)
        {
        $k=$i<10?"0".$i:$i;
        $selected=$m_birth_arr[1]==$k?"selected":"";
        ?>
        <option value="<?=$k?>" <?=$selected?>><?=$k?></option>
        <?
        }
        ?>                    
        </select></li>
        <li>
        <select name="birth_3" required type='select-one' itemname='일'>
        <option value="">일</option>
        <?
        for($i=1; $i<32; $i++)
        {
        $k=$i<10?"0".$i:$i;
        $selected=$m_birth_arr[2]==$k?"selected":"";
        ?>
        <option value="<?=$k?>" <?=$selected?>><?=$k?></option>
        <?
        }
        ?>                    
        </select></li>                
        
남<input type="radio" name="mem_sex" value="m" <?php echo $member_1['mem_sex']=="m"?"checked":""?>>
            여<input type="radio" name="mem_sex" value="f"  <?php echo $member_1['mem_sex']=="f"?"checked":""?>>        
        </td>
        </tr>
         <tr>
        <td>소속/직책</td>
        <td  colspan="3"><input type="text" name="zy" required itemname='소속' style="width:20%;" value="<?=$member_1['zy']?>" /></td>
        </tr>
        <tr>
        <td>자택주소</td>
        <td  colspan="3"><input type="text" name="add1" required itemname='주소' style="width:90%;" value="<?=$member_1['mem_add1']?>" /></td>
        </tr>  
        <tr>
        <td>이메일</td>
        <td  colspan="3">
        <input type="text" name="email_1" required itemname='이메일' style="width:70px;" value="<?=$m_email_arr[0]?>" /> @ <input type="text" name="email_2" id='email_2' itemname='이메일' required style="width:70px;" value="<?=$m_email_arr[1]?>" />
        <select name="email_3" itemname='이메일' onchange="inmail(this.value,'email_2')" style="background-color:#c8edfc;">
        <?
        foreach($email_arr as $key=>$v)
        {
        ?>
        <option value="<?=$key?>"><?=$v?></option>                            
        <?
        }
        ?>
        </select>                
        </td>
        </tr>
        <tr>
            <!--
        <td>닉네임</td>
        <td>
        <li><input type="text" name="nick" itemname='닉네임' value="<?=$member_1['mem_name']?>" required /></li>
        <li><input type="button" value="중복확인" onClick="nick_check(join_form,'join_form')" /></li>
        <li id='nick_html'></li>
        <input type="hidden" name="nick_status" itemname='닉네임중복확인' required  />                
        </td>
        -->
        <td>추천링크</td>
        <td colspan="3">
            <input type="hidden" name="nick" itemname='닉네임' value="<?=$member_1['mem_name']?>" />
            <input type="hidden" name="nick_status" itemname='닉네임중복확인' value="ok"  />                
               <?php if($member_1['mem_leb'] == "50") {?>
               <?php }?>
               <span id="sHtml" style="display:none">https://www.kiam.kr/?mem_code=<?php echo $member_1['mem_code']?></span>
               <!--<a href="https://www.kiam.kr/?mem_code=<?php echo $member_1['mem_code']?>">온리원문자사이트로 가기</a>-->
               <input type="button" name="" value="복사하기" onclick="copyHtml()">
               
        </td>
        </tr>            
        <!--<tr>
        <td>직책</td>
        <td  colspan="3"><input type="text" name="mem_sch" required itemname='직책' style="width:20%;" value="<?=$member_1['mem_sch']?>" /></td>
        </tr>   
        <tr>
        <td>직무</td>
        <td  colspan="3"><input type="text" name="keywords" required itemname='직무' style="width:90%;" value="<?=$member_1['keywords']?>" /></td>
        </tr>-->
              
       
        
        <!--
        <tr>
        <td>직업</td>
        <td colspan="3"><input type="text" name="zy" required itemname='직업' value="<?=$member_1['zy']?>" /></td>
        </tr>
        -->
        <tr>
        
        <?php if($member_1['mem_leb'] == "50") {?>
        <tr>
        <td>통장내역</td>
            <td  colspan="3">
                <table style="width:100%">
                    <tr>
                        <td>은행명</td><td><input type="text" name="bank_name"  itemname='주소' style="width:40%;" value="<?=$member_1['bank_name']?>" /></td>
                    </tr>
                    <tr>
                        <td>계좌번호</td><td><input type="text" name="bank_account"  itemname='주소' style="width:40%;" value="<?=$member_1['bank_account']?>" /></td>
                    </tr>
                    <tr>
                    <td>이름</td><td><input type="text" name="bank_owner"  itemname='주소' style="width:40%;" value="<?=$member_1['bank_owner']?>" /></td>
                    </tr>
                </table>
            </td>
        </tr>
        <?}else{?>
            <input type="hidden" name="bank_name"  itemname='주소' style="width:40%;" value="<?=$member_1['bank_name']?>" />
            <input type="hidden" name="bank_account"  itemname='주소' style="width:40%;" value="<?=$member_1['bank_account']?>" />
            <input type="hidden" name="bank_owner"  itemname='주소' style="width:40%;" value="<?=$member_1['bank_owner']?>" />
        <?}?>

		        <tr>
       <td>앱다운받기</td>
										<td colspan="3">
											<p style="width:80%; display: inline-block;">온리원셀링앱을 설치하면 콜백문자, 대량문자, 포털디비수집, 365일 자동메시지발송, 자동예약메시지기능, 아이엠기능 등을 사용할수 있습니다.</p>
									        <input type="button" name="" value="셀링앱다운받기" a href="https://bit.ly/2wg4v3g" target="_blank" style="float: right;" / >
								        							   
								    
        </td>
        </tr>
        <tr>
        <td>소식받기</td>
        <td colspan="3"><label><input type="checkbox" name="is_message" <?=$member_1['is_message']=="Y"?"checked":""?> />※ 아이엠, 셀링솔루션, 셀링대회, 제휴업체, 셀링교육, 마케팅지원과 온리원그룹 활동 및 사업소식을 전달합니다.</label></td>
        </tr>                                                
        <tr>
        <td colspan="4" style="text-align:center;padding:30px;">
        <a href="javascript:void(0)" onclick="join_check(join_form,'<?=$member_1['mem_code']?>')"><img src="images/sub_mypage_07.jpg" /></a>
        </td>
        </tr>

        </table>
        </form> 
    

       <!-- <//?
				$sql_serch=" buyer_id ='{$_SESSION['one_member_id']}' ";
				if($_REQUEST['search_date'])
				{					
					if($_REQUEST['rday1'])
					{
					$start_time=strtotime($_REQUEST['rday1']);
					$sql_serch.=" and unix_timestamp({$_REQUEST['search_date']}) >=$start_time ";
					}
					if($_REQUEST['rday2'])
					{
					$end_time=strtotime($_REQUEST['rday2']);
					$sql_serch.=" and unix_timestamp({$_REQUEST['search_date']}) <= $end_time ";
					}
				}
				$sql="select count(no) as cnt from tjd_pay_result where $sql_serch ";
				$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
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
				  $order_name="end_status";
				$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
				$sql="select * from tjd_pay_result where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
				$result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));				
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
        <li style="float:left;">결제정보</li>
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
					$checked=$_REQUEST['search_date']==$key?"checked":"";
				?>
                	<label><input name="search_date" type="radio" value="<?=$key?>" <?=$checked?> /><?=$v?></label>
                <?	
				}
				?>
                <input type="text" name="rday1" placeholder="" id="rday1" value="<?=$_REQUEST['rday1']?>"/> ~
                <input type="text" name="rday2" placeholder="" id="rday2" value="<?=$_REQUEST['rday2']?>"/>
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
              if($intRowCount)
              {
                  while($row=mysqli_fetch_array($result))
                  {
					  	$num_arr=array();
						$sql_num="select sendnum from Gn_MMS_Number where mem_id='{$row['buyer_id']}' and end_date='{$row['end_date']}' ";
						$resul_num=mysqli_query($self_con, $sql_num);
						while($row_num=mysqli_fetch_array($resul_num))
						array_push($num_arr,$row_num['sendnum']);
						//$num_str=implode(",",$num_arr);
						
						$sql="select mem_leb from Gn_Member  where mem_id='{$row['buyer_id']}'";
						$sresul_num=mysqli_query($self_con, $sql);
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
                <td style="font-size:12px;"><?=$row['date']?></td>
                <td style="font-size:12px;"><?=$row['end_date']?></td>
                <td><?=$row['month_cnt']?>개월</td>
                <!--<td><?=$row['fujia_status']?></td>-->
                <td>문자</td>
                <td><?=$pay_type[$row['payMethod']]?$pay_type[$row['payMethod']]:"무통장"?></td>
                <td><?=$row['add_phone']?></td>
                <td><?=$row['phone_cnt']?></td>
                <!--<td><?=count($num_arr)?></td>-->
                <td><?=number_format($row['TotPrice'])?>원</td>
                <td>
				<?=$pay_result_status[$row['end_status']]?>
               	<?php if($row['monthly_yn'] == "Y") {?>
               	<div style="border:1px solid #000;padding;3px;background:#B2CCFF" ><A href="javascript:void(monthly_remove('<?php echo $row['no'];?>'))">정기결제해지</a> <span class="popbutton pop_view pop_right">?</span></div>
                <?php }?>
             
				<!--
               	<?=$row['end_status']=="Y" && !count($num_arr) && strtotime("+1 week",strtotime($row['date'])) > time() ?"<a href=\"javascript:void(0)\" onclick=\"pay_cancel('{$row['no']}','{$row['payMethod']}','{$row['mid']}','{$row['tid']}','{$row['end_date']}','{$row['fujia_status']}')\" class=\"a_btn_2\">해지</a>":""?>
               	-->
               	<? //=$row['end_status']=="N"?"<a href='javascript:void(0)' onclick=\"pay_ex_go('{$row['no']}','{$row['end_date']}','{$is_chrome}')\" class='a_btn_2'>연장</a>":""?>

                </td>
              </tr>
              <?
                    $sort_no--;
                  }
                  ?>
        <?
				$sql_serch=" buyer_id ='{$_SESSION['one_member_id']}' ";
				if($_REQUEST['search_date'])
				{					
					if($_REQUEST['rday1'])
					{
					$start_time=strtotime($_REQUEST['rday1']);
					$sql_serch.=" and unix_timestamp({$_REQUEST['search_date']}) >=$start_time ";
					}
					if($_REQUEST['rday2'])
					{
					$end_time=strtotime($_REQUEST['rday2']);
					$sql_serch.=" and unix_timestamp({$_REQUEST['search_date']}) <= $end_time ";
					}
				}
				$sql="select count(no) as cnt from tjd_pay_result_db where $sql_serch ";
				$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
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
				  $order_name="end_status";
				$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
				$sql="select * from tjd_pay_result_db where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
				$result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));				
		?>   
              <?
                  while($row=mysqli_fetch_array($result))
                  {
					  	$num_arr=array();
						$sql_num="select sendnum from Gn_MMS_Number where mem_id='{$row['buyer_id']}' and end_date='{$row['end_date']}' ";
						$resul_num=mysqli_query($self_con, $sql_num);
						while($row_num=mysqli_fetch_array($resul_num))
						array_push($num_arr,$row_num['sendnum']);
						//$num_str=implode(",",$num_arr);
						
						$sql="select mem_leb from Gn_Member  where mem_id='{$row['buyer_id']}'";
						$sresul_num=mysqli_query($self_con, $sql);
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
                <td style="font-size:12px;"><?=$row['date']?></td>
                <td style="font-size:12px;"><?=$row['end_date']?></td>
                <td><?=$row['month_cnt']?>개월</td>
                <!--<td><?=$row['fujia_status']?></td>-->
                <td>디버</td>                
                <td><?=$pay_type[$row['payMethod']]?$pay_type[$row['payMethod']]:"카드"?></td>
                <td><?=$row['add_phone']?></td>
                <td><?=$row['phone_cnt']?></td>
                <!--<td><?=count($num_arr)?></td>-->
                <td><?=number_format($row['TotPrice'])?>원</td>
                <td>
				<?=$pay_result_status[$row['end_status']]?>
               	<?php if($row['monthly_yn'] == "Y") {?>
               	<div style="border:1px solid #000;padding;3px;background:#B2CCFF" ><A href="javascript:void(monthly_remove('<?php echo $row['no'];?>'))">정기결제해지</a> <span class="popbutton pop_view pop_right">?</span></div>
                <?php }?>
             
				<!--
               	<?=$row['end_status']=="Y" && !count($num_arr) && strtotime("+1 week",strtotime($row['date'])) > time() ?"<a href=\"javascript:void(0)\" onclick=\"pay_cancel('{$row['no']}','{$row['payMethod']}','{$row['mid']}','{$row['tid']}','{$row['end_date']}','{$row['fujia_status']}')\" class=\"a_btn_2\">해지</a>":""?>
               	-->
               	<? //=$row['end_status']=="N"?"<a href='javascript:void(0)' onclick=\"pay_ex_go('{$row['no']}','{$row['end_date']}','{$is_chrome}')\" class='a_btn_2'>연장</a>":""?>

                </td>
              </tr>
              <?
                    $sort_no--;
                  }
                  ?>		               
              <tr>
                <td colspan="10">
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
<!--<?php if($_SESSION['one_mem_lev'] == "50"){       
    $query = "";
    $sql_serch = "";
				if($_REQUEST['krday1'] || $_REQUEST['krday2'])
				{					
					if($_REQUEST['krday1'])
					{
					$start_time=strtotime($_REQUEST['krday1']);
					$sql_serch.=" and unix_timestamp(a.date) >=$start_time ";
					}
					if($_REQUEST['sday2'])
					{
					$end_time=strtotime($_REQUEST['krday2']);
					$sql_serch.=" and unix_timestamp(a.date) <= $end_time ";
					}
				}    
	$query = "
        	SELECT 
        	    SQL_CALC_FOUND_ROWS 
        	   *
        	FROM Gn_Member 
        	WHERE recommend_id='".$_SESSION['one_member_id']."'
	              $sql_serch";
	$res	    = mysqli_query($self_con, $query);
	$totalCnt	=  mysqli_num_rows($res);	
                	
	$intRowCount=$totalCnt;    
	
	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
	
    $orderQuery .= "
    	ORDER BY a.no DESC
    	$limitStr
    ";   	
?>
        <form name="payment_form" action="" method="post" class="my_pay">
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
                <input type="text" name="krday1" placeholder="" id="krday1" value="<?=$_REQUEST['krday1']?>"/> ~
                <input type="text" name="krday2" placeholder="" id="krday2" value="<?=$_REQUEST['krday2']?>"/>
                <a href="javascript:void(0)" onclick="payment_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>                                            
            </div>
            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:10%;">번호</td>
                <td style="width:10%;">이름</td>
                <td style="width:10%;">휴대폰번호</td>
                <td style="width:10%;">가입일</td>
                <td style="width:10%;">해지일</td>
                <td style="width:20%;">결제상태</td>
                <td style="width:10%;">회원구분</td>
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
                            	FROM tjd_pay_result a
                            	LEFT JOIN Gn_Member b
                            	       on b.mem_id =a.buyer_id
                            	WHERE 1=1 
                    	              and a.buyer_id='{$row['mem_id']}'";
                    	              
                    	$sres	    = mysqli_query($self_con, $query);                    
                        $srow = mysqli_fetch_array($sres);
                        if($row['business_type'] == "U") {
                            if($srow[0] != "")
                                $member_type = "일반회원";
                            else 
                                $member_type = "무료회원";
                        } else if($row['business_type'] == "B") {
                            $member_type = "사업자회원";
                        }
                    
                  ?>
              <tr>
                <td><?=$num++?></td>
                <td>
                    <?=$row['mem_name']?>
                </td>
                <td>
                    <?=$row['mem_phone']?>
                </td>                
                <td>
                    <?=substr($row['first_regist'],0,10)?>
                </td>                
                <td>
                    <?=$end_date?>
                </td>
                <td>
                    <?=$total_amount?>
                </td>
                <td>
                    <?=$member_type?>
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
<?php if($_SESSION['one_mem_lev'] == "50"){       
    $query = "";
    $sql_serch = "";
				if($_REQUEST['sday1'] || $_REQUEST['sday2'])
				{					
					if($_REQUEST['sday1'])
					{
					$start_time=strtotime($_REQUEST['sday1']);
					$sql_serch.=" and unix_timestamp(a.date) >=$start_time ";
					}
					if($_REQUEST['sday2'])
					{
					$end_time=strtotime($_REQUEST['sday2']);
					$sql_serch.=" and unix_timestamp(a.date) <= $end_time ";
					}
				}    
	$query = "
        	SELECT 
        	    SQL_CALC_FOUND_ROWS 
        	    a.no, 
        	    b.mem_id,
        	    b.mem_name,
        	    a.end_status,
        	    b.mem_phone,
        	    a.TotPrice,
        	    a.add_phone,
        	    a.month_cnt,
        	    a.date,
        	    a.end_date,
        	    c.price as total_price,
        	    c.payment_date,
        	    a.balance_date,
        	    a.balance_yn
        	FROM Gn_Member b
        	INNER JOIN tjd_pay_result a
        	       on b.mem_id =a.buyer_id
            INNER JOIN (   select 
                              bb.recommend_id, 
                              DATE_FORMAT(date, '%Y%m') payment_date,
                              sum(TotPrice) price 
                         from tjd_pay_result aa 
                    left join Gn_Member bb
                           on bb.mem_id = aa.buyer_id 
                        where bb.recommend_id='".$_SESSION['one_member_id']."' and end_status='Y' 
                        
                        group by bb.recommend_id, payment_date
                    ) c
                   on b.mem_id = c.recommend_id 
        	WHERE 1=1 
        	  AND b.mem_leb='50'
        	  and b.mem_id='".$_SESSION['one_member_id']."'
	              $sql_serch";
	$res	    = mysqli_query($self_con, $query);
	$totalCnt	=  mysqli_num_rows($res);	
                	
	$intRowCount=$totalCnt;    
	
	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
	
    $orderQuery .= "
    	ORDER BY a.no DESC
    	$limitStr
    ";   	
?>
        <form name="payment_form" action="" method="post" class="my_pay">
            <input type="hidden" name="order_name" value="<?=$order_name?>"  />
            <input type="hidden" name="order_status" value="<?=$order_status?>"/>
            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        
            
        <div class="a1">
        <li style="float:left;">정산내역보기</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1">
                <input type="text" name="sday1" placeholder="" id="sday1" value="<?=$_REQUEST['sday1']?>"/> ~
                <input type="text" name="sday2" placeholder="" id="sday2" value="<?=$_REQUEST['sday2']?>"/>
                <a href="javascript:void(0)" onclick="payment_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>                                            
            </div>
            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:10%;">정산월</td>
                <td style="width:10%;">지급일</td>
                <td style="width:10%;">정산액(당월)</td>
                <td style="width:10%;">지급액(당월)</td>
                <td style="width:10%;">미지급(당월)</td>
                <td style="width:20%;">세부내역</td>
                <td style="width:10%;">총지급액</td>
              </tr>
              <?
              if($intRowCount)
              {
                  while($row=mysqli_fetch_array($res))
                  {
                  ?>
              <tr>
                <td><?=substr($row['date'],0,7)?></td>
                <td>
                    <?=substr($row['balance_date'],0,10)?>
                </td>
                <td style="font-size:12px;"><?=number_format($row['total_price']/1.1*0.5)?> 원</td>
                <td style="font-size:12px;">
                    <?php if($row['balance_yn'] == "Y") {?>
                    <?=number_format($row['total_price']/1.1*0.5)?>
                    <?php }else{?>
                    0 
                    <?php }?>
                    원
                </td>
                <td>
                    <?php if($row['balance_yn'] == "N") {?>
                    <?=number_format($row['total_price']/1.1*0.5)?>
                    <?php }else{?>
                    0 
                    <?php }?>
                    원                    
                </td>
                <td></td>                
                <td>
                    <?php if($row['balance_yn'] == "Y") {?>
                    <?=number_format($row['total_price']/1.1*0.5)?>
                    <?php }else{?>
                    0 
                    <?php }?>
                    원                        
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
        </form>-->
<?php }?>                
        <!--<form name="app_pwd_form" action="" method="post">
        <div class="a1">
        <li style="float:left;">앱 비밀번호변경</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td>기존 앱비밀번호</td>
        <td>
        <li><input type="password" name="old_pwd" itemname='기존 어플비밀번호' required /></li>
        </td>
        </tr>        
        <tr>
        <td>새 앱비밀번호</td>
        <td>
        <li><input type="password" name="pwd" itemname='새 어플비밀번호' required onkeyup="pwd_check('0')" onblur="pwd_check('0')" /></li>
        <li class='pwd_html'></li>
        </td>
        </tr>
        <tr>
        <td>새 앱비밀번호확인</td>
        <td>
        <li><input type="password" name="pwd_cfm" itemname='새 어플비밀번호확인' required onblur="pwd_cfm_check('0')"/></li>
        <li class='pwd_cfm_html'></li>
        <input type="hidden" name="pwd_status" required itemname='새 어플비밀번호확인' />
        </td>
        </tr>
        <td colspan="2" style="text-align:center;padding:30px;">
        <a href="javascript:void(0)" onclick="pwd_change(app_pwd_form,'0')"><img src="images/btn_application Password_ change.gif" /></a>                    
        </td>
        </tr>            
        </table>        
        </form>-->
        <form name="web_pwd_form" action="" method="post">
        <div class="a1">
        <li style="float:left;">비밀번호수정</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td>기존비밀번호</td>
        <td>
        <li><input type="password" name="old_pwd" itemname='기존비밀번호' required /></li>
        </td>
        </tr>        
        <tr>
        <td>새비밀번호</td>
        <td>
        <li><input type="password" name="pwd" itemname='새비밀번호' required onkeyup="pwd_check('0')" onblur="pwd_check('0')" /></li>
        <li class='pwd_html'></li>
        </td>
        </tr>
        <tr>
        <td>새비번확인</td>
        <td>
        <li><input type="password" name="pwd_cfm" itemname='새비번확인' required onblur="pwd_cfm_check('0')"/></li>
        <li class='pwd_cfm_html'></li>
        <input type="hidden" name="pwd_status" required itemname='새비번확인' />
        </td>
        </tr>
        <td colspan="2" style="text-align:center;padding:30px;">
        <a href="javascript:void(0)" onclick="pwd_change(web_pwd_form,'0')"><img src="images/btn_web_change password.gif" /></a>                    
        </td>
        </tr>            
        </table>        
        </form>       

       <!-- <form name="form_message" method="post">
        <div class="a1">
        <li style="float:left;">발송시작안내문자</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td>발송시작안내문자</td>
        <td>
        
            <textarea style="width:400px;height:240px;" name="intro_message" id="intro_message"><?php echo $data['intro_message'];?></textarea>
        
        </td>
        </tr>        
            <tr>
            <td colspan="2" style="text-align:center;padding:30px;">
            	<input type="button" value="안내 메세지 변경" style="height:42px;width:280px" onclick="change_message(form_message)">
            	
            </a>                    
            </td>
            </tr>           
        </table>        
        </form>  -->  

                 
        <form name="leave_form" action="" method="post">
        <div class="a1">
        <li style="float:left;"><a href="javascript:void(0)" onclick="showInfo()">회원탈퇴</a></li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div id="outLayer" style="display:none">
            <div style="margin-bottom:5px">회원탈퇴를 신청하시면 즉시 해제되며 다시 로그인 할수 없습니다.회원탈퇴를 신청하시려면 아래 작성후 회원탈퇴를 클릭해주세요.</div>
            <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
            <td>비밀번호</td>
            <td><input type="password" name="leave_pwd" required itemname='비밀번호' /></td>
            </tr>
            <tr>
            <td>탈퇴사유</td>
            <td><input type="text" name="leave_liyou" required itemname='탈퇴사유' style="width:90%;" /></td>
            </tr>
            <tr>
            <td colspan="2" style="text-align:center;padding:30px;">
            <a href="javascript:void(0)" onclick="member_leave(leave_form)"><img src="images/sub_mypage_17.jpg" /></a>                    
            </td>
            </tr>            
            </table>        
            </form>
            </div>
        <!--</div>
    </div>
</div>
</div>--> 
<Script>
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
    var form = $('#join_form')[0];
    var formData = new FormData(form);
    formData.append("profile", $("#profile")[0].files[0])
    console.log(formData);
	
	if(confirm(msg))
	{
		$.ajax({
			 type:"POST",
			 url:"ajax/ajax.member.php",
             processData: false,
             contentType: false,
			 data:formData,
			 /*
			 {
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
				 bank_owner:bank_owner,
				 "fileObj2": $("#profile")[0].files[0]
				 },*/
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