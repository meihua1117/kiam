<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$sql="select * from Gn_lecture  where lecture_id='".$lecture_id."'";
$sresul_num=mysql_query($sql);
$row=mysql_fetch_array($sresul_num);	
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 260;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
</script>   
<style>
.loading_div {
    display:none;
    position: fixed;
    left: 50%;
    top: 50%;
    display: none;
    z-index: 1000;
}
#open_recv_div li{list-style: none;}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important}
</style>
    <div class="loading_div" ><img src="/images/ajax-loader.gif"></div>
    <div class="wrapper">
      <!-- Top 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>      
      
      <!-- Left 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>      
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            강연/교육 진행 관리
            <small>강연/교육을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">강연/교육관리 페이지</li>
          </ol>
        </section>
        
        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
        <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />        
        <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />        
        <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />        
        </form>                

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="box">
            <form name="sform" id="sform" action="ajax/lecture.proc.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="mode" value="<?php echo $lecture_id?"lecture_update":"lecture_save";?>" />
            <input type="hidden" name="lecture_id" value="<?php echo $row['lecture_id'];?>" />
            <input type="hidden" name="event_idx" id="event_idx" value="<?php echo $event_idx;?>" />
            <div>
                <div class="box-body">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered table-striped">
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
                            <input type="text" name="end_date" id="end_date" placeholder=""  value="<?=$row[end_date]?>" style="width:100px" autocomplete="off"/> 
                        </td>
                    </tr>                    
                    <tr>
                        <th class="w200">요일</th>
                        <td>
                            <input type="checkbox" name="lecture_day[]" value="월" <?php echo strstr($row['lecture_dat'],"월")?"checked":""?>> 월
                            <input type="checkbox" name="lecture_day[]" value="화" <?php echo strstr($row['lecture_dat'],"화")?"checked":""?>> 화
                            <input type="checkbox" name="lecture_day[]" value="수" <?php echo strstr($row['lecture_dat'],"수")?"checked":""?>> 수
                            <input type="checkbox" name="lecture_day[]" value="목" <?php echo strstr($row['lecture_dat'],"목")?"checked":""?>> 목
                            <input type="checkbox" name="lecture_day[]" value="금" <?php echo strstr($row['lecture_dat'],"금")?"checked":""?>> 금
                            <input type="checkbox" name="lecture_day[]" value="토" <?php echo strstr($row['lecture_dat'],"토")?"checked":""?>> 토
                            <input type="checkbox" name="lecture_day[]" value="일" <?php echo strstr($row['lecture_dat'],"일")?"checked":""?>> 일
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
                        <th class="w200">강의내용</th>
                        <td><input type="text" name="lecture_info" placeholder="" id="lecture_info" value="<?=$row[lecture_info]?>"/> </td>
                    </tr>      
                    
                    <tr>
                       <th class="w200">랜딩URL</th>
                       <td><input type="text" name="lecture_url" placeholder="" id="lecture_url"   value="<?=$row[lecture_url]?>"/> </td>
                    </tr>     

                    <tr>
                        <th class="w200">강사</th>
                        <td><input type="text" name="instructor" placeholder="" id="instructor" value="<?=$row[instructor]?>"/> </td>
                    </tr>                    
                    <tr>
                        <th class="w200">지역</th>
                        <td><input type="text" name="area" placeholder="" id="area" value="<?=$row[area]?>"/> </td>
                    </tr>                    
                    <tr>
                        <th class="w200">대상</th>
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
                        <th class="w200">신청</th>
                        <td>
                            <input type="text" name="event_code" placeholder="" id="event_code" value="<?=$row[event_code]?>"  style="width:100px"/> 이벤트영문명
                             <input type="button" value="이벤트 조회" class="button btn btn-primary " id="searchBtn">
                        </td>
                    </tr>                                  
                    </table>
                </div>
                <div class="p1" style="text-align:center;margin-top:20px;padding-bottom:10px">
                    <input type="button" value="취소" class="button btn btn-primary"  id="cancleBtn">
                    <input type="button" value="저장" class="button btn btn-primary" id="saveBtn">
                </div>
            </div>
            </form>
            
          </div>

        
        </div>
          
        </section><!-- /.content -->
      </div><!-- /content-wrapper -->

    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />        
        <input type="hidden" name="one_member_id" id="one_member_id" value="" />        
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />                
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>	

<script language="javascript">
$(function() {
    $('#cancleBtn').on("click", function() {
        location = "lecture_list.php";
    });
    
    $('#saveBtn').on("click", function() {
 
	
        $('#sform').submit();
    });    
})
    
function viewEvent(str){
    window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}    
function changeLevel(mem_code) {
    var mem_leb = $('#mem_leb'+mem_code+" option:selected").val();
    var data = {"mode":"change","mem_code":"'+mem_code+'","mem_leb":"'+mem_leb+'"};
    $.ajax({
		type:"POST",
		url:"/admin/ajax/user_level_change.php",
		dataType:"json",
		data:{mode:"change",mem_code:mem_code,mem_leb:mem_leb},
		success:function(data){
		    //console.log(data);
		    //location = "/";
			//location.reload();
			alert('변경이 완료되었습니다.');
		},
		error: function(){
		  alert('초기화 실패');
		}
	});	
    
//    alert(mem_code);
}

function loginGo(mem_id, mem_pw, mem_code) {
    $('#one_id').val(mem_id);
    $('#mem_pass').val(mem_pw);
    $('#mem_code').val(mem_code);
    $('#login_form').submit();
}
</script>
      <!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      
<link rel='stylesheet' id='jquery-ui-css'  href='//code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css?ver=4.5.2' type='text/css' media='all' />
<script
  src="http://code.jquery.com/ui/1.12.0-rc.2/jquery-ui.min.js"
  integrity="sha256-55Jz3pBCF8z9jBO1qQ7cIf0L+neuPTD1u7Ytzrp2dqo="
  crossorigin="anonymous"></script>
<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	<li class="open_recv_title open_2_1"></li>
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
    </div>
</div>
<script>
function copyHtml(url){
    //oViewLink = $( "ViewLink" ).innerHTML;
    ////alert ( oViewLink.value );
    //window.clipboardData.setData("Text", oViewLink);
    //alert ( "주소가 복사되었습니다. \'Ctrl+V\'를 눌러 붙여넣기 해주세요." );
    var IE=(document.all)?true:false;
    if (IE) {
        if(confirm("이 소스코드를 복사하시겠습니까?")) {
            window.clipboardData.setData("Text", url);
        } 
    } else {
            temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", url);
    }

}

function newpop(){
    var win = window.open("/mypage_pop_link_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}    
$(function() {
    $('#searchBtn').on("click", function() {
        newpop();
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