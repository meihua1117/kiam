<?
$path="./";
include_once "_head.php";
if(!$_SESSION['one_member_id'])
{
?>
<script language="javascript">
location.replace('/ma.php');
var idx = "";
</script>
<?
exit;
}
$sql="select * from Gn_Member  where mem_id='".$_SESSION['one_member_id']."' and site != ''";
$sresul_num=mysqli_query($self_con,$sql);
$data=mysqli_fetch_array($sresul_num);	

// if($data[0]) {
// 	$sql="select * from Gn_MMS_Group where idx='$row[group_idx]'";
// 	$sresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));					  	 
// 	$krow = mysqli_fetch_array($sresult);    
// }
$mem_phone = str_replace("-","",$data['mem_phone']);	
?>
<!-- <script>
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
</script> -->
<style>
.pop_right {
    position: relative;
    right: 2px;
    display: inline;
    margin-bottom: 6px;
    width: 5px;
}    
</style>
<div class="big_div">
<div class="big_sub">
    
<?php include "mypage_step_navi.php";?>

   <div class="m_div">
       <?php include "mypage_left_menu.php";?>
       <div class="m_body">

        <form name="pay_form" action="" method="post" class="my_pay">

        <input type="hidden" name="page" value="<?=$page?>" />
        <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1" style="margin-top:15px; margin-bottom:15px">
        <li style="float:left;">새디비예약리스트</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1">
                <select name="search_key" class="select">
                    <option value="">전체</option>
                </select>
                <input type="text" name="search_text" placeholder="" id="search_text" value="<?=$_REQUEST[search_text]?>"/> 
                <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>                                            
                
                <div style="float:right;">
                    <input type="button" value="새디비예약등록하기" class="button " id="eventAddBtn"></div>
            </div>
            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:2%;"><input type="checkbox" name="allChk" id="allChk" value="<?php echo $row['event_idx'];?>"></td>
                <td style="width:5%;">No</td>
                <td style="width:8%;">발송폰번호</td>
                <td style="width:8%;">주소록명</td>
                <td style="width:5%;">시작건수</td>
                <td style="width:5%;">종료건수</td>
                <td style="width:6%;">예약건수</td>
                <td style="width:9%;">제목</td>
                <td style="width:14%">메시지내용</td>
                <td style="width:7%;">발송시작일</td>
                <td style="width:7%">발송마감일</td>
                <td  style="width:7%;">등록일</td>
                <td style="width:7%;">관리</td>
              </tr>
              <?

				$sql_serch=" mem_id ='{$_SESSION['one_member_id']}' ";
				
				if($_REQUEST[search_text])
				{
					$search_text1 = $_REQUEST[search_text];

					$sql="select * from Gn_MMS_group where $sql_serch and grp = '$search_text1' ";
					$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
					$address_idx = mysqli_fetch_array($result);
					
					if($address_idx['idx'] != ""){
						$sql_serch.=" and address_idx = '{$address_idx['idx']}'";
						// echo "<script>alert('$address_idx['idx']');</script>";   
					}else{
						$sql_serch.=" and reservation_title like '%$search_text1%'";
					}
				}	
				$sql="select count(*) as cnt from Gn_event_oldrequest where $sql_serch ";
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
				  $order_name="reg_date";
				$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
                if($intRowCount)
                {
                  $sql="select * from Gn_event_oldrequest where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
                  $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
                  while($row=mysqli_fetch_array($result))
                  {

					$sql="select * from Gn_event_sms_info where sms_idx='$row[sms_idx]'";
                    $sresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				                    
                    $rrow = mysqli_fetch_array($sresult);

                    $sql="select count(*) as cnt from Gn_event_sms_step_info where sms_idx='$rrow[sms_idx]'";
                    $sresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				                    
                    $srow = mysqli_fetch_array($sresult);

                    // $sql = "select * from Gn_event_address where event_idx = '$row[reservation_title]'";
                    // $aresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                    // $arow=mysqli_fetch_array($aresult);
                    $sql = "select * from Gn_MMS_Group where idx = '$row[address_idx]'";
                    $addrresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                    $addrrow=mysqli_fetch_array($addrresult);

                    $sql="select count(idx) as cnt from Gn_MMS_Receive where grp_id = '{$addrrow['idx']}'";
                    $dresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                    $drow=mysqli_fetch_array($dresult);
                    $date = $row['regdate'];
                  ?>
                  <tr>
                    <td><input type="checkbox" name="event_idx" value="<?php echo $row['idx'];?>" data-reservation_title="<?=$row['reservation_title']?>" data-idx="<?=$row['idx']?>" data-name="<?=$row['name']?>" data-mobile="<?=$row[mobile]?>"  data-email="<?=$row['email']?>" data-job="<?=$row[job]?>"  data-event_code="<?=$row[event_code]?>"  data-counsult_date="<?=$row[counsult_date]?>" data-sp="<?=$row[sp]?>" data-request_idx="<?php echo $row['request_idx'];?>"></td>
                    <td><?=$sort_no?></td>
                    <td ><?=$row['send_num']?></td>
                    <td ><?=$addrrow['grp']?></td>
                    <td ><?=$row['addr_start_index']?></td>
                    <td ><?=$row['addr_end_index']?></td>
                    <td ><?=$drow['cnt']?></td>
                    <td ><?=$rrow['reservation_title']?></td>
                    <td ><?=$rrow['reservation_desc']?></td>
                    <td ><?=$row['start_date']?></td>
                    <td ><?=$row['end_date']?></td>
                    <td ><?=$row['reg_date']?></td>
                    <td >
						<!-- <a href="javascript:;;" onclick="updateRow('<?php echo $row['idx'];?>')">수정</a> / -->
                        <a href="mypage_oldrequest_edit.php?idx=<?php echo $row['idx'];?>">수정</a> /
                        <a href="javascript:;;" onclick="deleteRow('<?php echo $row['idx'];?>','<?php echo $row['reservation_title'];?>')">삭제</a>
                    </td>
 
                  </tr>
              <?
                    $sort_no--;
                  }
                  ?>		               
              <tr>
                <td colspan="15">
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
                <td colspan="14">
                    검색된 내용이 없습니다.
                </td>
              </tr>            
                <?  
              }
              ?>
            </table>
            
            <div style="text-align:right;margin:20px 0;">
                <input type="button" value="삭제" class="button" id="deleteMsg">
            </div>
            
            </div>
        </div>
        </form>
    </div>     
    
</div> 
<!-- <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>  -->
<Script>
function newpop(){
    var win = window.open("mypage_pop_link_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}    
$(function() {
    $('#searchBtn').on("click", function() {
        newMessageEvent();
    });
    $('#searchEventBtn').on("click", function() {
        newMessageEvent();
    });
	$('#updateEventBtn').on("click", function() {
        newMessageEvent();
    });
    $('#searchAddressBtn').on("click", function() { // test 주소록 조회
        newAddress();
    });    // test
	$('#updateAddressBtn').on("click", function() { // test 주소록 조회
        newAddress();
    });    // test
    
})
function newMessageEvent(){ // test 메시지조회
    var win = window.open("mypage_pop_message_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
}
function newAddress(){ // test 주소록조회
    var win = window.open("mypage_pop_address_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
} 
function newpop(){
    //var win = window.open("pop_reservation_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
    var win = window.open("mypage_pop_link_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
    
}    
$(function(){
    $('#popBtn').on("click",function(){
        newpop()
    });
    $('#allChk').on("change",function(){
        $('input[name=event_idx]').prop("checked", $(this).is(":checked"));
    })
    
    $('#eventAddBtn').on("click", function() {
        var html = "";
        $('input[name=event_idx]').each(function() {
			if($(this).is(":checked") == true){

			}
        });
        $('#event_receive_info').html(html);

		var phoneno = $(this).siblings().eq(0).find("input").val();
		$('.ad_layer5').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
    });    
	
	$('#popEventCloseBtn').on("click", function() {
	    $('.lb_overlay, .ad_layer5').hide();
        location.reload();
	});
	$('#popUpdateCloseBtn').on("click", function() {
	    $('.lb_overlay, .ad_layer4').hide();
	});  
	
	$('#popEventSaveBtn').on("click", function() {	    
	    if($('#send_num').val() == "")
        {
            alert("선택된 발송번호가 없습니다.");
            return;
        } 
        if($('#address_name').val() == "")
        {
            alert("주소록을 선택해 주세요.");
            $('#address_name').focus();
            return;
        } 
        if($('#event_name_eng').val() == "")
        {
            alert("스텝예약문자를 선택해 주세요.");
            $('#event_name_eng').focus();
            return;
        } 
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
        $('#popEventSaveBtn').prop('disabled', true); 
        $($(".loading_div")[0]).show();
	    $($(".loading_div")[0]).css('z-index',10000);
	    $.ajax({
    		 type:"POST",
    		 url:"mypage.proc.php",
    		 data:{
    			 mode : "old_customer_reservation",
    			 m_id: $('#m_id').val(),
                 mem_code: $('#mem_code').val(),
                 send_num: $('#send_num').val(),
                 group_idx: $('#address_idx').val(),
                 address_name: $('#address_name').val(),
                 step_idx: $('#step_idx').val(),
                 reservation_title: $('#reservation_title').val(),
                 start_index : $('#start_index').val(),
                 end_index : $('#end_index').val()
    		 },
    		 success:function(data){
                $($(".loading_div")[0]).hide();
                alert(data);
                $('#popEventSaveBtn').prop('disabled', false); 
    		 }
    	});
	});	
	$('#popEventUpdateBtn').on("click", function() {	    
	    $('#addFormEvent4').submit();
	});
    $('#deleteMsg').on("click", function() {
        var idx = "";
        var reservation_title = "";
        $('input[name=event_idx]').each(function() {
            if($(this).is(":checked") == true) {
                if(idx != ""){
                    idx += ",";
                    reservation_title += ",";
                }
                idx += $(this).data("idx");
                reservation_title += $(this).data("reservation_title");
            }
        });
        deleteRow(idx, reservation_title);
    });
})
function updateRow(request_id){
	var html = "";
	idx = request_id;
    $('input[name=event_idx]').each(function() { 
    });
    $('#event_receive_info').html(html);
    $('.ad_layer5').lightbox_me({
        centered: true,
        onLoad: function() {
        }
    });
}

function deleteRow(request_id, org_event_code) {
    if(confirm('삭제하시겠습니까?')) {
    	$.ajax({
    		 type:"POST",
    		 url:"mypage.proc.php",
    		 data:{
    			 mode : "oldrequest_del",
    			 idx: request_id,
    			 org_event_code : org_event_code
    			 },
    		 success:function(data){
    		 	$("#ajax_div").html(data);
    		 	alert('삭제되었습니다.');
    		 	location.reload();
    		 	}
    		});
    		return false;
    }
}
function showInfo() {
    if($('#outLayer').css("display") == "none") {
        $('#outLayer').show();
    } else {
        $('#outLayer').hide();
    }
}
</Script>
<link href='/css/sub_4_re.css' rel='stylesheet' type='text/css'/>
<script type="text/javascript" src="jquery.lightbox_me.js"></script>
<script type="text/javascript" src="/js/mms_send.js"></script>
<script type="text/javascript" src="/plugin/tablednd/js/jquery.tablednd.0.7.min.js"></script>
<style>
.ad_layer4 {
    width: 903px;
    height: 498px;
    background-color: #fff;
    border: 2px solid #24303e;
    position: relative;
    box-sizing: border-box;
    padding: 30px 30px 50px 30px;

    display: none;
}    
.ad_layer5 {
    width: 903px;
    height: 498px;
    background-color: #fff;
    border: 2px solid #24303e;
    position: relative;
    box-sizing: border-box;
    padding: 30px 30px 50px 30px;

    display: none;
}    
.ui-widget-content {
    border: none !important;
background: #ffffff/*{bgColorContent}*/ url(images/ui-bg_flat_75_ffffff_40x100.png)/*{bgImgUrlContent}*/ 50%/*{bgContentXPos}*/ 50%/*{bgContentYPos}*/ repeat-x/*{bgContentRepeat}*/;
    color: #222222/*{fcContent}*/;
}
</style>
	<div class="ad_layer5">
		<div class="layer_in">
			<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
		    <form method="post" name="addFormEvent" id="addFormEvent"  action="mypage.proc.php" enctype="multipart/form-data">
			<input type="hidden" name="mode" value="old_customer_reservation">
		    <input type="hidden" name="m_id" id="m_id" value="<?php echo $_SESSION['one_member_id'];?>"> 			
            <div class="pop_title">
				새디비 예약하기
			</div>
			<div class="info_box">

				<table class="info_box_table" cellpadding="0" cellspacing="0">
					<tbody>
                        <tr>
                            <th class="w200">[발송폰선택]</th>
                            <td style="height:35px;text-align:left;" >
                                <input type="hidden" name="mem_code" placeholder="" id="mem_code" value="<?=$data['mem_code']?>" readonly style="width:200px"/>
                                <select id="send_num" name="send_num">
                                    <!--option value="<?=str_replace("-", "", $data['mem_phone'])?>"><?php echo str_replace("-","",$data['mem_phone']);?></option-->
							        <?php
                                        $query = "select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' order by sort_no asc, user_cnt desc , idx desc";
                                        $resul=mysqli_query($self_con,$query);
                                        while($korow=mysqli_fetch_array($resul)) {
                                    ?>
                                    <option value="<?=$korow[sendnum]?>" <?php echo $row['send_num']==$korow['sendnum']?"selected":""?>><?php echo $korow['sendnum'];?></option>
                                    <?php }?>
                                </select>
                            </td>
                        </tr> 
                        <tr>
                            <th class="w200">[주소록선택]</th>
                            <td style="height:35px;text-align:left;" >
                                <input type="hidden" name="group_idx" placeholder="" id="address_idx" value="" readonly style="width:200px"/> 
                                <input type="text" id="address_name" name="address_name" placeholder="" id="address_name" value="" readonly style="width:200px; height: 27px;"/> 
                                <input type="button" value="주소록 조회" class="button " id="searchAddressBtn">
                                [선택건수]<span id="address_cnt"><?php echo $row['total_count'];?></span>
                            </td>
                        </tr> 
                        <tr>
						    <th class="w200">[예약메시지]</td>
							<td style="height:35px;text-align:left;" >
							    <input type="text" id="reservation_title" name="reservation_title" value="" readonly style="width:250px; height: 27px;">
							    <input type="hidden" id="step_idx" name="step_idx" value="" style="width:95px;">
							    <input type="button" value="스텝예약관리 조회" class="button " id="searchEventBtn">
							</td>
                        </tr>  
                        <tr>
						    <th class="w200">[발송건수]</td>
							<td style="height:35px;text-align:left;" >
							    <input type="text" id="start_index" name="start_index" value="" placeholder="시작건수" style="width:50px; height: 27px;">&nbsp; - &nbsp;
							    <input type="text" id="end_index" name="end_index" value="" placeholder="종료건수" style="width:50px;height: 27px;">
							</td>
                        </tr> 
					</tbody>
				</table>
			</div>
			<div class="ok_box">
                <input type="button" value="저장" class="button" id="popEventSaveBtn">	
                <input type="button" value="취소" class="button "  id="popEventCloseBtn">			
			</div>
		</form>

		</div>

</div>
	</div>	
    </div>
<?
include_once "_foot.php";
?>