<?
$path="./";
include_once "_head.php";
extract($_REQUEST);
if(!$_SESSION[one_member_id])
{
?>
<script language="javascript">
location.replace('/ma.php');
</script>
<?
exit;
}

$sql="select * from gn_coaching_info a inner join Gn_Member b on b.mem_code = a.coty_mem_code inner join gn_coaching_apply c on c.coty_id = a.coty_id where a.coaching_id='".$coaching_id."'";

$sresul_num=mysqli_query($self_con,$sql);
$coaching_info_data=mysqli_fetch_array($sresul_num);	

$this_coach_id =$coaching_info_data[coach_id];

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
    
<?php include_once "mypage_base_navi.php";?>

   <div class="m_div">
       <?php include_once "mypage_left_menu.php";?>
       <div class="m_body">

        <form name="sform" id="sform" >

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
                    <td><?=$coaching_info_data[mem_name]?></td>
                    <th>코티ID</th>
                    <td><?=$coaching_info_data[mem_id]?></td>
                </tr>       
                <tr>
                    <th>전화번호</th>
                    <td><?=$coaching_info_data[mem_phone]?></td>
                    <th>이메일</th>
                    <td><?=$coaching_info_data[mem_email]?></td>
                </tr>        
                <tr>
                    <th>계약기간</th>
                    <td><?=$coaching_info_data[cont_term]?>일</td>
                    <th>계약시간</th>
                    <td><?=$coaching_info_data[cont_time]?>시간</td>
                </tr>                    
 
                <tr>
                    <th>코칭비용</th>
                    <td><?=$coaching_info_data[coaching_price]/10000?>만원</td>
                    <th>희망코칭</th>
                    <td><?=$coaching_info_data[want_coaching]?></td>
                </tr>                      
                <tr>
                    <th>코칭시작</th>
                    <td><?=$coaching_info_data[start_date]?></td>
                    <th>코칭종료</th>
                    <td><?=$coaching_info_data[end_date]?></td>
                </tr>                    
 
                <tr>
                    <th>진행상태</th>
                    <td>
                        <? 
                        $currentTime = date("Y-m-d H:i:s");

                         if($currentTime < $coaching_info_data[start_date]){
                            echo "<label class='label label-sm label-warning'>대기</label>";

                         }else if($currentTime > $coaching_info_data[start_date] && $currentTime < $coaching_info_data[end_date]){
                            echo "<label class='label label-sm label-primary'>진행중</label>";
                         }
                         else if($currentTime > $coaching_info_data[end_date]){
                            echo "<label class='label label-sm label-danger'>종료</label>";
                         }

                        // echo $coaching_info_data[coaching_status]==1?"진행중":"완료";


                        ?>
                    </td>
                    <th>코칭회차</th>
                    <td><?=$coaching_info_data[coaching_turn]?>회</td>
                </tr>                      
                                
                </table>
            </div>
        </div>
        </form>



                <form >

        <div class="a1" style="margin-top:15px; margin-bottom:15px">
        <li style="float:left;">진행과정 조회</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>

            <? 


            $sql="select * from gn_coach_apply a inner join Gn_Member b on b.mem_code = a.mem_code where a.coach_id = ".$this_coach_id;

            $sresul_num=mysqli_query($self_con,$sql);
            $coach_data=mysqli_fetch_array($sresul_num);    



            ?>


        <div>
            <div class="p1">
                <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th class="w200">코치이름</th>
                    <td><?=$coach_data['mem_name'] ?></td>
                </tr>    
                <tr>
                    <th class="w200">코칭일시</th>
                    <td>
                        <?=$coaching_info_data[coaching_date]?>
                     </td>
                </tr>                    
                <tr>
                    <th class="w200">코칭제목</th>
                    <td><?=$coaching_info_data[coaching_title]?></td>
                </tr>                    
                <tr>
                    <th class="w200">코칭내용</th>
                    <td><?=$coaching_info_data[coaching_content]?></td>
                </tr>                    
                <tr>
                    <th class="w200">파일첨부</th>
                    <td>
                        <a href="<?=$coaching_info_data[coaching_file]?>" style="color:blue;">
                            <? if($coaching_info_data[coaching_file]){
                                        echo "파일";
                                    } ?>
                        </a>
                        
                            
                        </td>
                </tr>                    
                <tr>
                    <th class="w200">코치평가</th>
                    <td>
                    <?
                        $coach_value = $coaching_info_data[coach_value];
                        echo (($coach_value / 5) * 100 )."점( ".$coach_value." / 5 )";
                    ?>
                    </td>
                </tr>    
                <tr>
                    <th class="w200">코티평가</th>
                    <td>
                      
                    <?
                        $coty_value = $coaching_info_data[coty_value];
                        echo (($coty_value / 5) * 100 )."점( ".$coty_value." / 5 )";
                    ?>  
                    </td>
                </tr>    
                <tr>
                    <th class="w200">본사평가</th>
                    <td>
                      
                    <?
                        $site_value = $coaching_info_data[site_value];
                        echo (($site_value / 5) * 100 )."점( ".$site_value." / 5 )";
                    ?>  
                    </td>
                </tr>    

                <tr>
                    <th class="w200">과제안내</th>
                    <td><?=$coaching_info_data[home_work]?></td>
                </tr>                    
                <tr>
                    <th class="w200">코치의견</th>
                    <td><?=$coaching_info_data[coach_comment]?></td>
                </tr>                 
                <tr>
                    <th class="w200">코티의견</th>
                    <td><?=$coaching_info_data[coty_comment]?></td>
                </tr>                    
                <tr>
                    <th class="w200">본부의견</th>
                    <td><?=$coaching_info_data[site_comment]?></td>
                </tr>                    
                                
                </table>
            </div>
            <div class="p1" style="text-align:center;margin-top:20px;margin-bottom: 20px;">
            <input type="button" value="닫기" class="button btn-danger btn"  id="cancleBtn">
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






<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<script src="admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="/admin/bootstrap/js/bootstrap.min.js"></script>