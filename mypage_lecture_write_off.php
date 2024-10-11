<?
$path="./";
include_once "_head.php";
extract($_REQUEST);
if(!$_SESSION['one_member_id'])
{


?>
<script language="javascript">
location.replace('/ma.php');
</script>
<?
exit;
}
$sql="select * from Gn_lecture  where lecture_id='".$lecture_id."'";
$sresul_num=mysqli_query($self_con,$sql);
$row=mysqli_fetch_array($sresul_num);	
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
    
<?php include_once "mypage_base_navi.php";?>

   <div class="m_div">
       <?php include_once "mypage_left_menu.php";?>
       <div class="m_body">

        <form name="sform" id="sform" action="mypage.proc.php" method="post" enctype="multipart/form-data">

        <input type="hidden" name="mode" value="<?php echo $lecture_id?"lecture_update":"lecture_save";?>" />
        <input type="hidden" name="lecture_id" value="<?php echo $row['lecture_id'];?>" />
        <input type="hidden" name="event_idx" id="event_idx" value="<?php echo $row['event_idx'];?>" />
        <div class="a1" style="margin-top:15px; margin-bottom:15px">
        <li style="float:left;">강연/교육 만들기</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1">
                <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th class="w200">분야</th>
                    <td>
                        <select name="category" id="category" style="height:35px;width:80px">
                            <option value="강연" <?php echo $row['category']=="강연"?"selected":""?>>강연</option>
                            <option value="교육" <?php echo $row['category']=="교육"?"selected":""?>>교육</option>
                            <option value="영상" <?php echo $row['category']=="영상"?"selected":""?>>영상</option>
                        </select>
                    </td>
                </tr>    
                <tr>
                    <th class="w200">일정</th>
                    <td>
                        <input type="text" name="start_date" id="start_date" placeholder=""  value="<?=$row[start_date]?>" style="width:100px" autocomplete="off"/> ~  
                        <input type="text" name="end_date" id="end_date" placeholder=""  value="<?=$row['end_date']?>" style="width:100px" autocomplete="off"/> 
                    </td>
                </tr>                    
                <tr>
                    <th class="w200">요일</th>
                    <td>
                        <input type="checkbox" name="lecture_day[]" value="월" <?php echo strstr($row['lecture_day'],"월")?"checked":""?>> 월
                        <input type="checkbox" name="lecture_day[]" value="화" <?php echo strstr($row['lecture_day'],"화")?"checked":""?>> 화
                        <input type="checkbox" name="lecture_day[]" value="수" <?php echo strstr($row['lecture_day'],"수")?"checked":""?>> 수
                        <input type="checkbox" name="lecture_day[]" value="목" <?php echo strstr($row['lecture_day'],"목")?"checked":""?>> 목
                        <input type="checkbox" name="lecture_day[]" value="금" <?php echo strstr($row['lecture_day'],"금")?"checked":""?>> 금
                        <input type="checkbox" name="lecture_day[]" value="토" <?php echo strstr($row['lecture_day'],"토")?"checked":""?>> 토
                        <input type="checkbox" name="lecture_day[]" value="일" <?php echo strstr($row['lecture_day'],"일")?"checked":""?>> 일
                    </td>
                </tr>                    
                <tr>
                    <th class="w200">시간</th>
                    <td>
                        <input type="text" name="lecture_start_time" placeholder="" id="lecture_start_time" value="<?=$row[lecture_start_time]?>" style="width:100px"/> ~ 
                        <input type="text" name="lecture_end_time" placeholder="" id="lecture_end_time" value="<?=$row[lecture_end_time]?>" style="width:100px"/>
                        </td>
                    
                </tr>                    
                <tr>
                    <th class="w200">제목</th>
                    <td><input type="text" name="lecture_info" placeholder="" id="lecture_info" value="<?=$row[lecture_info]?>"/> </td>
                </tr>                    
                <tr>
                    <th class="w200">랜딩URL</th>
                    <td><input type="text" name="lecture_url" placeholder="" id="lecture_url" value="<?=$row[lecture_url]?>"/> </td>
                </tr>                                    
                <tr>
                    <th class="w200">강사</th>
                    <td><input type="text" name="instructor" placeholder="" id="instructor" value="<?=$row[instructor]?>"/> </td>
                </tr>                    
                <tr>
                    <th class="w200">지역/장소</th>
                    <td><input type="text" name="area" placeholder="" id="area" value="<?=$row[area]?>"/> </td>
                </tr>                    
                <tr>
                    <th class="w200">참여대상</th>
                    <td><input type="text" name="target" placeholder="" id="target" value="<?=$row[target]?>"/> </td>
                </tr>                                    
                <tr>
                    <th class="w200">정원</th>
                    <td><input type="text" name="max_num" placeholder="" id="max_num" value="<?=$row[max_num]?>" style="width:100px"/> 명 </td>
                </tr>                    
                <tr>
                    <th class="w200">비용</th>
                    <td><input type="text" name="fee" placeholder="" id="fee" value="<?=$row[fee]?>" style="width:100px"/> 원 </td>
                </tr>                    
                <tr>
                    <th class="w200">신청설정</th>
                    <td>
                        <input type="text" name="event_code" placeholder="" id="event_code" value="<?=$row[event_code]?>" readonly style="width:100px"/> 이벤트명 (영문)
                         <input type="button" value="이벤트 선택" class="button " id="searchBtn">
                    </td>
                </tr>                                  
                <?php if($lecture_id != "") {?>
                <tr>
                    <th class="w200">리뷰 사진 1</th>
                    <td>
                        <input type="file" name="review_img1" placeholder="" id="review_img1" style="width:300px"/> 
                        <?php if($row['review_img1']) { echo "<img src='/upload/lecture/".$row['review_img1']."' style='height:80px'>";}?>
                        <input type="text" name="review_title1" placeholder="제목" id="review_title1" style="width:300px" value="<?php echo $row['review_title1']?>"/> 
                    </td>
                </tr>                                    
                <tr>
                    <th class="w200">리뷰 사진 2</th>
                    <td>
                        <input type="file" name="review_img2" placeholder="" id="review_img2" style="width:300px"/> 
                        <?php if($row['review_img2']) { echo "<img src='/upload/lecture/".$row['review_img2']."' style='height:80px'>";}?>
                        <input type="text" name="review_title2" placeholder="제목" id="review_title2" style="width:300px"  value="<?php echo $row['review_title2']?>"/> 
                    </td>
                </tr>                 
                <tr>
                    <th class="w200">리뷰 사진 3</th>
                    <td>
                        <input type="file" name="review_img3" placeholder="" id="review_img3" style="width:300px"/> 
                        <?php if($row['review_img3']) { echo "<img src='/upload/lecture/".$row['review_img3']."' style='height:80px'>";}?>
                        <input type="text" name="review_title3" placeholder="제목" id="review_title3" style="width:300px"  value="<?php echo $row['review_title3']?>"/> 
                    </td>
                </tr>                 
                <tr>
                    <th class="w200">리뷰 사진 4</th>
                    <td>
                        <input type="file" name="review_img4" placeholder="" id="review_img4" style="width:300px"/> 
                        <?php if($row['review_img4']) { echo "<img src='/upload/lecture/".$row['review_img4']."' style='height:80px'>";}?>
                        <input type="text" name="review_title4" placeholder="제목" id="review_title4" style="width:300px"  value="<?php echo $row['review_title4']?>"/> 
                    </td>
                </tr>   
                <tr>
                    <th class="w200">리뷰 사진 5</th>
                    <td>
                        <input type="file" name="review_img5" placeholder="" id="review_img5" style="width:300px"/> 
                        <?php if($row['review_img5']) { echo "<img src='/upload/lecture/".$row['review_img5']."' style='height:80px'>";}?>
                        <input type="text" name="review_title5" placeholder="제목" id="review_title5" style="width:300px"  value="<?php echo $row['review_title5']?>"/> 
                    </td>
                </tr>                                     
                <?php }?>
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