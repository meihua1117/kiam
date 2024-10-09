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
$sql="select * from coaching_info  where info_no='".$info_no."'";
$sresul_num=mysql_query($sql);
$row=mysql_fetch_array($sresul_num);	

echo $row[coty_name]

?>
<script>
function copyHtml(){
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
        <li style="float:left;">코칭신청 정보</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1">
                <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th>코티이름</th>
                    <td><?=$row[coty_name]?></td>
                    <th>코티ID</th>
                    <td><?=$row[coty_id]?></td>
                </tr>       
                <tr>
                    <th>전화번호</th>
                    <td>010-1234-8651 [가상]</td>
                    <th>이메일</th>
                    <td>aaa@aaa.gmail [가상]</td>
                </tr>        
                <tr>
                    <th>계약기간</th>
                    <td>30일[가상]</td>
                    <th>계약시간</th>
                    <td>6시간[가상]</td>
                </tr>                    
 
                <tr>
                    <th>코칭비용</th>
                    <td>5만원[가상]</td>
                    <th></th>
                    <td></td>
                </tr>                      
                <tr>
                    <th>코칭시작</th>
                    <td><?=$row[coaching_date]?></td>
                    <th>코칭종료</th>
                    <td><?=$row[coaching_date]?>[계산필요]</td>
                </tr>                    
 
                <tr>
                    <th>진행상태</th>
                    <td>코칭완료[가상]</td>
                    <th></th>
                    <td></td>
                </tr>                      
                                
                </table>
            </div>
        </div>
        </form>



                <form name="sform" id="sform" action="mypage.proc.php" method="post" enctype="multipart/form-data">

        <input type="hidden" name="mode" value="<?php echo $lecture_id?"lecture_update":"lecture_save";?>" />
        <input type="hidden" name="lecture_id" value="<?php echo $row['lecture_id'];?>" />
        <input type="hidden" name="event_idx" id="event_idx" value="<?php echo $row['event_idx'];?>" />
        <div class="a1" style="margin-top:15px; margin-bottom:15px">
        <li style="float:left;">진행과정 조회</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1">
                <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th class="w200">코치이름</th>
                    <td>김경한</td>
                </tr>    
                <tr>
                    <th class="w200">코칭일시</th>
                    <td>20.05.03  15:00  ~  20.05.03  17:00 </td>
                </tr>                    
                <tr>
                    <th class="w200">코칭제목</th>
                    <td><?=$row[coaching_title]?></td>
                </tr>                    
                <tr>
                    <th class="w200">코칭내용</th>
                    <td><?=$row[coaching_content]?></td>
                </tr>                    
                <tr>
                    <th class="w200">파일첨부</th>
                    <td><?=$row[coaching_file]?></td>
                </tr>                    
                <tr>
                    <th class="w200">코치평가</th>
                    <td><?=$row[coach_value]?></td>
                </tr>    
                <tr>
                    <th class="w200">코티평가</th>
                    <td>88점/250점</td>
                </tr>    
                <tr>
                    <th class="w200">본사평가</th>
                    <td>88점/250점</td>
                </tr>    

                <tr>
                    <th class="w200">과제안내</th>
                    <td><?=$row[home_work]?></td>
                </tr>                    
                <tr>
                    <th class="w200">코치의견</th>
                    <td><?=$row[coach_comment]?></td>
                </tr>                 
                <tr>
                    <th class="w200">코티의견</th>
                    <td>상세하게 설명해주셔서 이해가 잘되었어요.  감사합니다</td>
                </tr>                    
                <tr>
                    <th class="w200">본부의견</th>
                    <td>꾸준히 교육자료를 업데이트 하시네요</td>
                </tr>                    
                                
                </table>
            </div>
            <div class="p1" style="text-align:center;margin-top:20px;">
            <input type="button" value="닫기" class="button"  id="cancleBtn">
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
        location = "mypage_coaching_list.php";
    });
    
    // $('#saveBtn').on("click", function() {
    //     $('#sform').submit();
    // });    
})
</script>      