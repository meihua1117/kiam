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
?>
<div class="big_sub">
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
   <div class="m_div">
        <div><img src="images/sub_mypage_03.jpg" /></div>
        <div class="join">       
        <form name="join_form" method="post">
        <div class="a1">
        <li style="float:left;">회원정보 수정</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>         
        <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td>아이디</td>
        <td><?=$member_1[mem_id]?></td>
        </tr>
        <tr>
        <td>닉네임</td>
        <td>
        <li><input type="text" name="nick" itemname='닉네임' value="<?=$member_1[mem_nick]?>" required /></li>
        <li><input type="button" value="중복확인" onClick="nick_check(join_form,'join_form')" /></li>
        <li id='nick_html'></li>
        <input type="hidden" name="nick_status" itemname='닉네임중복확인' required  />                
        </td>
        </tr>
        <tr>
        <td>성명</td>
        <td><input type="text" name="name" itemname='성명' value="<?=$member_1[mem_name]?>" required /></td>
        </tr>
        <tr>
        <td>이메일</td>
        <td>
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
        <td>핸드폰</td>
        <td><?=$member_1[mem_phone]?></td>
        </tr>        
        <tr>
        <td>주소</td>
        <td><input type="text" name="add1" required itemname='주소' style="width:90%;" value="<?=$member_1[mem_add1]?>" /></td>
        </tr>
        <tr>
        <td>직업</td>
        <td><input type="text" name="zy" required itemname='직업' value="<?=$member_1[zy]?>" /></td>
        </tr>
        <tr>
        <td>생년월일</td>
        <td>
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
        </td>
        </tr>
        <tr>
        <td>소식받기</td>
        <td><label><input type="checkbox" name="is_message" <?=$member_1[is_message]=="Y"?"checked":""?> />원페이지북,와칭리딩 소식을 받겠습니다.</label></td>
        </tr>                                                
        <tr>
        <td colspan="2" style="text-align:center;padding:30px;">
        <a href="javascript:void(0)" onclick="join_check(join_form,'<?=$member_1[mem_code]?>')"><img src="images/sub_mypage_07.jpg" /></a>
        </td>
        </tr>
        </table>
        </form>        
        <?
				$sql_serch=" buyer_id ='$_SESSION[one_member_id]' ";
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
				$intRowCount=$row[cnt];
				if (!$_POST[lno]) 
					$intPageSize =20;
				else 
				   $intPageSize = $_POST[lno];				
				if($_POST[page])
				{
				  $page=(int)$_POST[page];
				  $sort_no=$intRowCount-($intPageSize*$page-$intPageSize); 
				}
				else
				{
				  $page=1;
				  $sort_no=$intRowCount;
				}
				if($_POST[page2])
				  $page2=(int)$_POST[page2];
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
});
</script>
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
                <td style="width:15%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'date',pay_form.order_status.value)">결제일<? if($_REQUEST[order_name]=="date"){echo $_REQUEST[order_status]=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                <td style="width:15%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'end_date',pay_form.order_status.value)">만료(해지)일<? if($_REQUEST[order_name]=="end_date"){echo $_REQUEST[order_status]=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                <td style="width:6%"><a href="javascript:void(0)" onclick="order_sort(pay_form,'month_cnt',pay_form.order_status.value)">개월수<? if($_REQUEST[order_name]=="month_cnt"){echo $_REQUEST[order_status]=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                <td style="width:8%"><a href="javascript:void(0)" onclick="order_sort(pay_form,'fujia_status',pay_form.order_status.value)">부가서비스<? if($_REQUEST[order_name]=="fujia_status"){echo $_REQUEST[order_status]=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                <td style="width:10%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'payMethod',pay_form.order_status.value)">결제방식<? if($_REQUEST[order_name]=="payMethod"){echo $_REQUEST[order_status]=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>            
                
                <td style="width:9%;">결제한<br />폰 수</td>
                <td style="width:9%;">등록된<br />건 수</td>
                <td style="width:10%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'TotPrice',pay_form.order_status.value)">결제금액<? if($_REQUEST[order_name]=="TotPrice"){echo $_REQUEST[order_status]=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                <td style="width:12%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'end_status',pay_form.order_status.value)">상태<? if($_REQUEST[order_name]=="end_status"){echo $_REQUEST[order_status]=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
              </tr>
              <?
              if($intRowCount)
              {
                  while($row=mysqli_fetch_array($result))
                  {
					  	$num_arr=array();
						$sql_num="select sendnum from Gn_MMS_Number where mem_id='$row[buyer_id]' and end_date='$row[end_date]' ";
						$resul_num=mysqli_query($self_con,$sql_num);
						while($row_num=mysqli_fetch_array($resul_num))
						array_push($num_arr,$row_num[sendnum]);
						//$num_str=implode(",",$num_arr);
                  ?>
              <tr>
                <td><?=$sort_no?></td>
                <td style="font-size:12px;"><?=$row[date]?></td>
                <td style="font-size:12px;"><?=$row[end_date]?></td>
                <td><?=$row[month_cnt]?>개월</td>
                <!--<td><?=$row[fujia_status]?></td>-->
                <td>Y</td>                
                <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                <td><?=$row[add_phone]?></td>
                <td><?=$row[phone_cnt]?></td>
                <!--<td><?=count($num_arr)?></td>-->
                <td><?=number_format($row[TotPrice])?>원</td>
                <td>
				<?=$pay_result_status[$row[end_status]]?>
				<!--
               	<?=$row[end_status]=="Y" && !count($num_arr) && strtotime("+1 week",strtotime($row[date])) > time() ?"<a href=\"javascript:void(0)\" onclick=\"pay_cancel('{$row[no]}','{$row[payMethod]}','{$row[mid]}','{$row[tid]}','{$row[end_date]}','{$row[fujia_status]}')\" class=\"a_btn_2\">해지</a>":""?>
               	-->
               	<? //=$row[end_status]=="N"?"<a href='javascript:void(0)' onclick=\"pay_ex_go('{$row[no]}','{$row[end_date]}','{$is_chrome}')\" class='a_btn_2'>연장</a>":""?>
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
        <form name="app_pwd_form" action="" method="post">
        <div class="a1">
        <li style="float:left;">어플 비밀번호변경</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td>기존 어플비밀번호</td>
        <td>
        <li><input type="password" name="old_pwd" itemname='기존 어플비밀번호' required /></li>
        </td>
        </tr>        
        <tr>
        <td>새 어플비밀번호</td>
        <td>
        <li><input type="password" name="pwd" itemname='새 어플비밀번호' required onkeyup="pwd_check('0')" onblur="pwd_check('0')" /></li>
        <li class='pwd_html'></li>
        </td>
        </tr>
        <tr>
        <td>새 어플비밀번호확인</td>
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
        </form>
        <form name="web_pwd_form" action="" method="post">
        <div class="a1">
        <li style="float:left;">PC 비밀번호변경</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td>기존 PC비밀번호</td>
        <td>
        <li><input type="password" name="old_pwd" itemname='기존 웹비밀번호' required /></li>
        </td>
        </tr>        
        <tr>
        <td>새 PC비밀번호</td>
        <td>
        <li><input type="password" name="pwd" itemname='새 웹비밀번호' required onkeyup="pwd_check('1')" onblur="pwd_check('1')" /></li>
        <li class='pwd_html'></li>
        </td>
        </tr>
        <tr>
        <td>새 PC비밀번호확인</td>
        <td>
        <li><input type="password" name="pwd_cfm" itemname='새 웹비밀번호확인' required onblur="pwd_cfm_check('1')"/></li>
        <li class='pwd_cfm_html'></li>
        <input type="hidden" name="pwd_status" required itemname='새 웹비밀번호확인' />
        </td>
        </tr>
        <td colspan="2" style="text-align:center;padding:30px;">
        <a href="javascript:void(0)" onclick="pwd_change(web_pwd_form,'1')"><img src="images/btn_web_change password.gif" /></a>                    
        </td>
        </tr>            
        </table>        
        </form>                
        <form name="leave_form" action="" method="post">
        <div class="a1">
        <li style="float:left;">회원탈퇴</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div style="margin-bottom:5px">회원탈퇴를 신청하시면 즉시 해제되며 다시 로그인 할수 없습니다.회원탈퇴를 신청하시려면 아래 작성후 회원탈퇴를 클릭해주세요.</div>
        <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td>PC비밀번호</td>
        <td><input type="password" name="leave_pwd" required itemname='웹비밀번호' /></td>
        </tr>
        <tr>
        <td>탈퇴사유</td>
        <td><input type="text" name="leave_liyou" required itemname='탈퇴사유' /></td>
        </tr>
        <tr>
        <td colspan="2" style="text-align:center;padding:30px;">
        <a href="javascript:void(0)" onclick="member_leave(leave_form)"><img src="images/sub_mypage_17.jpg" /></a>                    
        </td>
        </tr>            
        </table>        
        </form>
        </div>
    </div>
</div> 
<?
include_once "_foot.php";
?>

