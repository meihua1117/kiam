<?
$path="./";
include_once "_head.php";
extract($_REQUEST);
if(!$_SESSION[one_member_id])
{


?>
<script language="javascript">
location.replace('/');
</script>
<?
exit;
}
$sql="select * from Gn_lecture  where lecture_id='".$lecture_id."'";
$sresul_num=mysql_query($sql);
$row=mysql_fetch_array($sresul_num);	
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
.w200 {width:200px}
.list_table1 tr:first-child td{border-top:1px solid #CCC;}
.list_table1 tr:first-child th{border-top:1px solid #CCC;}
.list_table1 td{height:40px;border-bottom:1px solid #CCC;}
.list_table1 th{height:40px;border-bottom:1px solid #CCC;}
.list_table1 input[type=text]{width:600px;height:30px;}

</style>

<div class="big_sub">
    
<?php include "mypage_base_navi.php";?>

   <div class="m_div">
       <?php include "mypage_left_menu.php";?>
       <div class="m_body">

        <form name="sform" id="sform" action="mypage.proc.php" method="post" enctype="multipart/form-data">

        <input type="hidden" name="mode" value="<?php echo $lecture_id?"lecture_update":"lecture_save";?>" />
        <input type="hidden" name="lecture_id" value="<?php echo $row['lecture_id'];?>" />
        <input type="hidden" name="event_idx" id="event_idx" value="<?php echo $row['event_idx'];?>" />
        <div class="a1" style="margin-top:15px; margin-bottom:15px">
        <li style="float:left;">코칭정보 입력하기</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1">
                <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th class="w200">코티ID</th>

                    <td><input type="text" style="width:200px;" name="user_id" value="<?=$data['user_id']?>" > </td>




                </tr>    
                <tr>
                    <th class="w200">코칭시작일시</th>
                    <td>
                        <input type="text" name="start_date" id="start_date" placeholder="코칭시작날자와 시작시간을 입력하세요"  value="<?=$row[start_date]?>" /> 
                    </td>
                </tr>      
                <tr>
                    <th class="w200">코칭시간</th>
                    <td>
                        <input type="text" name="start_date" id="start_date" placeholder="코칭을 수행한 시간을 분단위로 기록하세요"  value="<?=$row[start_date]?>" /> 
                    </td>
                </tr>                    
              
                <tr>
                    <th class="w200">코칭제목</th>
                    <td><input type="text" name="lecture_info" placeholder="" id="lecture_info" value="<?=$row[lecture_info]?>"/> </td>
                </tr>                    
                <tr>
                    <th class="w200">코칭내용</th>
                    <td><input type="text" name="instructor" placeholder="" id="instructor" value="<?=$row[instructor]?>"/> </td>
                </tr>                    
                <tr>
                    <th class="w200">파일첨부</th>
                    <td><input type="text" name="instructor" placeholder="" id="instructor" value="<?=$row[instructor]?>"/> </td>
                </tr>                    
                <tr>
                    <th class="w200">코치평가</th>
                    <td><input type="text" name="instructor" placeholder="1=매우못함 2=못함 3=보통 4=잘함 5=아주잘함" id="instructor" value="<?=$row[instructor]?>"/> </td>
                </tr>    
                <tr>
                    <th class="w200">과제안내</th>
                    <td><input type="text" name="instructor" placeholder="다음 코칭때까지 수행할 과제를 기록하세요" id="instructor" value="<?=$row[instructor]?>"/> </td>
                </tr>                    
                <tr>
                    <th class="w200">코치의견</th>
                    <td><input type="text" name="instructor" placeholder="오늘 수업에 대한 코치의견을 기록하세요" id="instructor" value="<?=$row[instructor]?>"/> </td>
                </tr>                    
                <tr>
                    <th class="w200">등록일시</th>
                    <td>
                        <input type="text" name="start_date" id="start_date" placeholder=""  value="<?=$row[start_date]?>" /> 
                    </td>
                </tr>      
                               
                </table>
            </div>
            <div class="p1" style="text-align:center;margin-top:20px;">
            <input type="button" value="취소" class="button"  id="cancleBtn">
            <input type="button" value="저장" class="button" id="saveBtn">
            </div>
        </div>
        </form>
    </div>     
    
</div> 
<link rel='stylesheet' id='jquery-ui-css'  href='//code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css?ver=4.5.2' type='text/css' media='all' />
<script
  src="http://code.jquery.com/ui/1.12.0-rc.2/jquery-ui.min.js"
  integrity="sha256-55Jz3pBCF8z9jBO1qQ7cIf0L+neuPTD1u7Ytzrp2dqo="
  crossorigin="anonymous"></script>
<script>
function newpop(){
    var win = window.open("mypage_pop_link_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}    
$(function() {
    $('#searchBtn').on("click", function() {
        newpop();
    });
})    
$(function() {
    $('#cancleBtn').on("click", function() {
        location = "mypage_landing_list.php";
    });
    
    $('#saveBtn').on("click", function() {
 
	
        $('#sform').submit();
    });    
})
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
});
//시작일. 끝일보다는 적어야 되게끔
$( "#start_date" ).datepicker({
 dateFormat: "yy-mm-dd",
 defaultDate: "+1w",
 onClose: function( selectedDate ) {
  $("#start_date").datepicker( "option", "minDate", selectedDate );
 }
});
 
//끝일. 시작일보다는 길어야 되게끔
$( "#end_date" ).datepicker({
 dateFormat: "yy-mm-dd",
 defaultDate: "+1w",
 onClose: function( selectedDate ) {
  $("#end_date").datepicker( "option", "maxDate", selectedDate );
 }
});
</script>      