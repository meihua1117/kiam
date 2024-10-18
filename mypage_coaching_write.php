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
$sql="select * from gn_coaching_info  where coaching_id='".$coaching_id."'";
$sresul_num=mysqli_query($self_con,$sql);
$coaching_info_data=mysqli_fetch_array($sresul_num);	


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
.list_table1 input[type=text]{width:600px;height:30px; padding-left: 10px;}

</style>

<div class="big_sub">
    
<?php include_once "mypage_base_navi.php";?>

   <div class="m_div">
       <?php include_once "mypage_left_menu.php";?>
       <div class="m_body">
        <? 
          $sql="select * from gn_coach_apply a inner join Gn_Member b on b.mem_code = a.mem_code where b.mem_id = '".$_SESSION['one_member_id']."'";
          $coach_data =mysqli_fetch_array(mysqli_query($self_con,$sql));    
        ?>
        <form name="sform" id="sform" action="mypage.proc.php" method="post" enctype="multipart/form-data">

        <input type="hidden" name="mode" value="create_coaching_info" />
        <input type="hidden" name="coach_id" value="<?=$coach_data['coach_id']?>" />
        <input type="hidden" name="coach_mem_code" value="<?=$coach_data['mem_code']?>" />
        <input type="hidden" name="update_coaching_id" value="<?=$coaching_id?>" />


        <div class="a1" style="margin-top:15px; margin-bottom:15px">
        <li style="float:left;">코칭정보 입력하기</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>

            <div class="p1">
                <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th class="w200">코티</th>
                    <td>
                      <!-- <input type="text" name="coty_id" id="coty_id">  -->
                       <select name="coty_id" id="coty_id" onchange="cotyIDChanged()"  style="height:35px;width:75%; padding-left:5px;">
                          <? 
                          $sql="select * from gn_coaching_apply a inner join Gn_Member b on b.mem_code = a.mem_code where a.coach_id= ".$coach_data['coach_id']." order by reg_date desc";
                          $coaching_res = mysqli_query($self_con,$sql);
                          while($coaching_data = mysqli_fetch_array($coaching_res)) { 






                              $sql="select sum(coaching_time) as sum from gn_coaching_info where coty_id=".$coaching_data['coty_id']." order by coaching_turn desc";

                                $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                                $row=mysqli_fetch_array($result);
                                $last_time_sum=$row['sum'];

                                if( ($coaching_data['cont_time'] * 60) != $last_time_sum){

                               

                            ?>


                            <option value="<?=$coaching_data["coty_id"] ?>_<?=$coaching_data["mem_name"] ?>_<?=$coaching_data["mem_code"] ?>_<?=$coaching_data["mem_id"] ?>"  
                              <? if($coaching_data["coty_id"]==$coaching_info_data['coty_id']){echo selected;} ?>
                              ><?=$coaching_data["mem_id"] ?>&nbsp;&nbsp;/&nbsp;&nbsp;<?=$coaching_data["mem_name"] ?>&nbsp;&nbsp;/&nbsp;&nbsp;<?=$coaching_data["mem_phone"] ?> </option>
                          <?}}?>
                        </select>
                        
                    </td>
                </tr>    
                <tr>
                    <th class="w200">코티이름</th>
                    <td><input type="text" name="coty_name" id="coty_name" disabled> </td>
                </tr> 
                <script type="text/javascript">
                  setTimeout(function(){ 
                    console.log($("#coty_id").val()); 
                    if($("#coty_id").val()){

                     $("#coty_name").val($("#coty_id").val().split("_")[3] +"  /  "+ $("#coty_id").val().split("_")[1]);

                    }
                  }, 500);
                  
                  function cotyIDChanged(){
                    $("#coty_name").val($("#coty_id").val().split("_")[3] +"  /  "+ $("#coty_id").val().split("_")[1]);
                  }   
                </script>
                <tr>
<!--                     <script type="text/javascript" src="//code.jquery.com/jquery-2.1.1.min.js"></script>
                    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
                    <link rel="stylesheet" type="text/css" media="screen" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

<link href="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
<script src="//cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>

 -->
 <style type="text/css">
 
   #start_date
   {
    width: 80px;
    padding-left:10px; 
   }
   #start_hour,
   #start_min
   {
    height: 32px;
    width: 50px;
   }
 </style>

 <? 
 if($coaching_info_data["coaching_date"]){
    $coaching_date = $coaching_info_data["coaching_date"];

  $start_date = date('Y-m-d',strtotime($coaching_date)); 
  $time = date('H:i:s', strtotime($coaching_date));
  $start_hour = date('H', strtotime($coaching_date));
  $start_min = date('i', strtotime($coaching_date));
 }
 else{
    $coaching_date = date('m/d/Y h:i:s a', time());
  $start_date = date('Y-m-d',strtotime($coaching_date)); 
  $start_hour = 9;
  $start_min = 0;
 }
  //echo $time;
?>
                    <th class="w200">코칭시작</th>
                    <td>
                        <input class="form-control" type="text" name="start_date" id="start_date" autocomplete="off" value="<?=$start_date ?>"/> 일 &nbsp;&nbsp;

                        <select name="start_hour" id="start_hour" value = "13">

                         <?
                          for($i=1; $i<25; $i++)
                          {
                            $selected=$start_hour==$i?"selected":"";
                          ?>
                            <option value="<?=$i?>" <?=$selected?>><?=$i?></option>
                          <?
                          }
                          ?>
                        </select> 시
                        
                        <select name="start_min" id="start_min">
                          <?
                          for($i=0; $i<60; $i=$i+10)
                          {
                            $selected=$start_min==$i?"selected":"";
                          ?>
                            <option value="<?=$i?>" <?=$selected?>><?=$i?></option>
                          <?
                          }
                          ?>
                         
                        </select> 분
                    </td>
                </tr>      
                <tr>
                    <th class="w200">코칭시간</th>
                    <td>
                        <input type="text" name="coaching_time" id="coaching_time"   value="<?=$coaching_info_data['coaching_time']?>" style="width:80px;" /> 분
                    </td>
                </tr>                    
              
                <tr>
                    <th class="w200">코칭제목</th>
                    <td><input type="text" name="coaching_title" placeholder="" id="coaching_title" value="<?=$coaching_info_data['coaching_title']?>"/> </td>
                </tr>                    
                <tr>
                    <th class="w200">코칭내용</th>
                    <td><input type="text" name="coaching_content" placeholder="" id="coaching_content" value="<?=$coaching_info_data['coaching_content']?>"/> </td>
                </tr>                    
                <tr>
                    <th class="w200">파일첨부</th>
                    <td>

                      <!-- <input type="file" name="fileToUpload"/>   -->
                      <input type="file" name="coaching_file" placeholder="" id="coaching_file" value="<?=$coaching_info_data['coaching_file']?>"/> 

                    </td>
                </tr>                    
                <tr>
                    <th class="w200">코치평가</th>
                    <td><input type="text" name="coach_value" placeholder="1=매우못함 2=못함 3=보통 4=잘함 5=아주잘함" id="coach_value" value="<?=$coaching_info_data['coach_value']?>"/> </td>
                </tr>    
                <tr>
                    <th class="w200">과제안내</th>
                    <td><input type="text" name="home_work" placeholder="다음 코칭때까지 수행할 과제를 기록하세요" id="home_work" value="<?=$coaching_info_data['home_work']?>"/> </td>
                </tr>                    
                <tr>
                    <th class="w200">코치의견</th>
                    <td><input type="text" name="coach_comment" placeholder="오늘 수업에 대한 코치의견을 기록하세요" id="coach_comment" value="<?=$coaching_info_data['coach_comment']?>"/> </td>
                               
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


<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>  

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
        location = "mypage_coaching_list.php";
    });
    
    $('#saveBtn').on("click", function() {

        if($("#coty_id").val()== null){alert("코티가 할당되지 않았습니다.");location = "mypage_coaching_list.php"; return;};
        if($("#start_date").val().trim() == ""){alert("값을 정확히 입력하세요.");$("#start_date").focus(); return;};
        if($("#coaching_time").val().trim() == ""){alert("값을 정확히 입력하세요.");$("#coaching_time").focus(); return;};
        if($("#coaching_title").val().trim() == ""){alert("코칭제목을 정확히 입력하세요.");$("#coaching_title").focus(); return;};
        if($("#coaching_content").val().trim() == ""){alert("코칭내용을 정확히 입력하세요.");$("#coaching_content").focus(); return;};
        if($("#coach_value").val().trim() == ""){alert("코치평가 정확히 입력하세요.");$("#coach_value").focus(); return;};
        if($("#home_work").val().trim() == ""){alert("과제안내를 정확히 입력하세요.");$("#home_work").focus(); return;};
        if($("#coach_comment").val().trim() == ""){alert("코치의견을 정확히 입력하세요.");$("#coach_comment").focus(); return;};


        if($("#coach_value").val() > 5){alert("코치평가 정확히 입력하세요. 1 - 5까지의 숫자여야 합니다.");$("#coach_value").focus(); return;};






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
 // onClose: function( selectedDate ) {
 //  $("#start_date").datepicker( "option", "minDate", selectedDate );
 // }
});





// $("#start_date").datepicker( "option", "minDate", "<?



// $sql_1 = "Select * FROM `gn_coaching_info` WHERE coaching_turn = (  SELECT MAX( coaching_turn ) AS max_c_turn FROM  `gn_coaching_info`  WHERE `coach_id` = '{$coaching_info_data['coach_id']}' AND `coty_id` = '{$coaching_info_data['coty_id']}' )  AND `coach_id` = '{$coaching_info_data['coach_id']}' AND `coty_id` = '{$coaching_info_data['coty_id']}';";

// $res_1=mysqli_query($self_con,$sql_1);
// $coaching=mysqli_fetch_array($res_1);

// $max_coaching_date = $coaching['coaching_date'];

//   $start_date = date('Y-m-d',strtotime($max_coaching_date)); 
// echo $start_date;

//  ?>" );


//$("#start_time").timepicker();
 
//끝일. 시작일보다는 길어야 되게끔
// $( "#end_date" ).datepicker({
//  dateFormat: "yy-mm-dd",
//  defaultDate: "+1w",
//  onClose: function( selectedDate ) {
//   $("#end_date").datepicker( "option", "maxDate", selectedDate );
//  }
// });
</script>      


<style type="text/css">
  .ui-datepicker
  {
    display: none;
  }
</style>