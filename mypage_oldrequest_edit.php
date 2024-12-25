<!-- test new oldrequest page3 -->
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
	

$sql="select * from Gn_event_oldrequest  where idx='{$idx}'";
$sresul_num=mysqli_query($self_con,$sql);
$row=mysqli_fetch_array($sresul_num);

$sql="select * from Gn_event_sms_info where sms_idx='{$row['sms_idx']}'";
$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$event_row=mysqli_fetch_array($result);

$sql="select * from Gn_MMS_Group where idx='{$row['address_idx']}'";
$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$group_row=mysqli_fetch_array($result);

$sql="select count(idx) as cnt from Gn_MMS_Receive where grp_id = '{$row['address_idx']}'";
$dresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$drow=mysqli_fetch_array($dresult);
$cnt = $drow[0];

?>
<script>
    function newpop(){
    var win = window.open("mypage_pop_link_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}    
$(function() {
    $('#searchEventBtn').on("click", function() {
        newMessageEvent();
    });
    $('#searchAddressBtn').on("click", function() { // test 주소록 조회
        newAddress();
    });    // test
})
function newMessageEvent(){ // test 메시지조회
    var win = window.open("mypage_pop_message_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
}
function newAddress(){ // test 주소록조회
    var win = window.open("mypage_pop_address_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
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

.w200 {width:200px;}
.list_table1 tr:first-child td{border-top:1px solid #CCC;}
.list_table1 tr:first-child th{border-top:1px solid #CCC;}
.list_table1 td{height:40px;border-bottom:1px solid #CCC;}
.list_table1 th{height:40px;border-bottom:1px solid #CCC;}
.list_table1 input[type=text]{width:600px;height:30px;}
.info_box_table input[type=text]{width:600px;height:30px;}
.info_box_table th{height:40px;border-bottom:1px solid #CCC;width:200px !important;}
</style>
<div class="big_sub">
    
<?php include "mypage_step_navi.php";?>

   <div class="m_div">
       <?php include "mypage_left_menu.php";?>
       <div class="m_body">


        <input type="hidden" name="page" value="<?=$page?>" />
        <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1">
        <li style="float:left;">기존고객디비 예약수정하기</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <form name="sform" id="sform" action="mypage.proc.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" value="reservation_update"> 
        <input type="hidden" name="idx" value="<?php echo $idx;?>" />
            <div class="p1">
                <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th class="w200">[발송폰번호]</th>
                    <td><input type="text" name="mobile" placeholder="" id="mobile" value="<?=$row['send_num']?>" style="width:200px" readonly/> </td>
                </tr>                    
                <tr>
                    <th class="w200">[주소록선택]</th>
                    <td>
                    <input type="hidden" name="group_idx" placeholder="" id="address_idx" value="<?=$row['address_idx']?>" readonly style="width:200px"/> 
                    <input type="text" name="address_name" placeholder="" id="address_name" value="<?=$group_row['grp']?>" readonly style="width:200px; height: 27px;"/> 
                    <input type="button" value="주소록 조회" class="button " id="searchAddressBtn">
                    [선택건수]<span id="address_cnt"><?=$cnt;?></span></td>
                </tr>                                    
                <tr>
                    <th class="w200">[예약메시지]</th>
                    <td>
                        <input type="hidden" id="step_idx" name="step_idx" value="<?=$event_row['sms_idx']?>" style="width:250px;">
                        <input type="text" id="reservation_title" name="reservation_title" readonly value="<?=$event_row['reservation_title']?>" style="width:250px; height: 27px;">
                        <input type="button" value="스텝예약관리 조회" class="button " id="searchEventBtn">
                    </td>
                </tr>
                <tr>
                    <th class="w200">[발송건수]</td>
                    <td style="height:35px;text-align:left;" >
                        <input type="text" id="start_index" name="start_index" value="<?=$row['addr_start_index']?>" placeholder="시작건수" style="width:50px; height: 27px;">&nbsp; - &nbsp;
                        <input type="text" id="end_index" name="end_index" value="<?=$row['addr_end_index']?>" placeholder="종료건수" style="width:50px;height: 27px;">
                    </td>
                </tr> 
                </table>
            </div>
            <div style="text-align:center;margin-top:10px">
                <input type="button" value="저장" class="button " id="saveBtn">
                <input type="button" value="취소" class="button" id="cancleBtn">
            </div>
        </form>
    </div>     
    
</div> 
<Script>
   
function deleteRow(sms_detail_idx, sms_idx) {
}
function change_message(form) {
}
function showInfo() {
}
</Script>


<script>
//회원가입체크
function monthly_remove(no) {
}
$(function() {
    $('#saveBtn').on("click",function() {
        if($('#start_index').val() == "")
        {
            alert("시작건수를 입력해 주세요.");
            $('#start_index').focus();
            return;
        } 
        if($('#end_index').val() == "")
        {
            alert("종료건수를 선택해 주세요.");
            $('#end_index').focus();
            return;
        } 

        alert("요청하신 예약은 컴퓨터 성능에 따라 5분 내외의 시간이 필요합니다. \n예약이 완료 되면 안내 메시지가 나오는데 그동안 컴퓨터를 오프하지 마십시요. \n대신 예약 세팅하는 동안 다른 작업을 하셔도 됩니다.");
        $('#saveBtn').prop('disabled', true); 
        $('#sform').submit();
    });
    $('#cancleBtn').on("click", function() {
	   location = "mypage_oldrequest_list.php"; 
	});
	$('#send_day').on("change", function(){
	    if($(this).val() == "0") {
	        $('#timeArea').hide();
	    } else {
	        $('#timeArea').show();
	    }
	});
	$('#send_day').on("keyup", function(){
	    if($(this).val() == "0") {
	        $('#timeArea').hide();
	    } else {
	        $('#timeArea').show();
	    }
	});	

});


</script>      
<link href='/css/sub_4_re.css' rel='stylesheet' type='text/css'/>
<script type="text/javascript" src="jquery.lightbox_me.js"></script>
<script type="text/javascript" src="/js/mms_send.js"></script>
<script type="text/javascript" src="/plugin/tablednd/js/jquery.tablednd.0.7.min.js"></script>
<style>
.ad_layer4 {
    width: 903px;
    height: 548px;
    background-color: #fff;
    border: 2px solid #24303e;
    position: relative;
    box-sizing: border-box;
    padding: 30px 30px 50px 30px;
    display: none;
}    
</style>

<div id="open_recv_div" class="open_recv_div" name="open_recv_div">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	<li class="open_recv_title open_2_1"></li>
    	<li class="open_2_2"><a href="javascript:void(0)" onclick="close_div(open_recv_div)"><img src="images/div_pop_01.jpg"></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
    </div>
</div>