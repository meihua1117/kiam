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
	$sql="select * from Gn_Member  where mem_id='".$_SESSION[one_member_id]."'";
	$sresul_num=mysql_query($sql);
	$data=mysql_fetch_array($sresul_num);	
	
	if($data['intro_message'] =="") {
		$data['intro_message'] = "안녕하세요\n
\n
귀하의 휴대폰으로\n
기부문자발송을 시작합니다.\n
\n
협조해주셔서 감사합니다^^
";
	}
	
$mem_phone = str_replace("-","",$data['mem_phone']);	

$sql="select * from Gn_event_sms_info  where sms_idx='".$sms_idx."'";
$sresul_num=mysql_query($sql);
$row=mysql_fetch_array($sresul_num);	
?>
<script>
    function newpop(){
    var win = window.open("mypage_pop_link_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}    
$(function() {
    $('#searchBtn').on("click", function() {
        newpop();
    });
})
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

$('#_popupbox').mouseleave(function() { $(this).hide(); });
        
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
					
	<div class="ad_layer5">
		<div class="layer_in">
			<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
			<div class="pop_title">
				앱상태체크
			</div>
			<div class="info_text">
				<p>
					선택한 폰번호로 앱체크문자를 발송하여 사용가능 상태를 확인합니다. 5분 내에 수신 되면 on, 수신이 안되면 off<br />
				</p>
                <p>기본적으로 휴대폰이 WiFi 상태에서도 발송이 잘 되나 3G나 LTE 상태로 바꿔주시면 더욱 좋습니다.</p><br />
                <p>휴대폰의 스마트매니저 기능으로 인해 온리원문자 앱이 절전상태가 되어 발송이 되지 않을 수 있습니다. 설치 시 절전 해지를 꼭 해주세요.</p>
			</div>

		</div>
	</div>


	<div class="ad_layer6">
		<div class="layer_in">
			<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
			<div class="pop_title">
				미전송문자삭제
			</div>
			<div class="info_text">
				<p>
					문자앱에서 가져가지 않고 서버에 대기중인 문자를 삭제합니다.
				</p>
			</div>

		</div>
	</div>
<div class="big_sub">
    
<?php include_once "mypage_step_navi.php";?>

   <div class="m_div">
       <?php include_once "mypage_left_menu.php";?>
       <div class="m_body">


            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1" style="margin-top:50px; margin-bottom:15px">

		<li style="float:left;">		
			<div class="popup_holder popup_text" style=cursor:pointer;>신청고객 수동추가
				<div id="_popupbox" class="popupbox" style="height: 75px;width: 260px;left: 160px;top: -37px;display:none ; ">자신의 이벤트 상품이나 서비스를 소개하거나 상세페이지로 만든 랜딩페이지를 리스트로 보는 기능입니다.<br><br>
				  <a class = "detail_view" style="color: blue;" href="https://url.kr/ldQyeR" target="_blank">[자세히 보기]</a>

				</div>
			</div>		
		</li>

        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <form name="sform" id="sform" action="mypage.proc.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" value="<?php echo $sms_idx?"sms_update":"sms_save";?>" />
        <input type="hidden" name="sms_idx" value="<?php echo $sms_idx;?>" />
        <input type="hidden" name="event_idx" id="event_idx" value="<?php echo $row[event_idx];?>" />
            <div class="p1">
                <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th class="w200">발송폰번호</th>
                    <td><input type="text" name="mobile" placeholder="" id="mobile" value="<?=$mem_phone?>" style="width:200px" readonly/> </td>
                </tr>                    
                <tr>
                    <th class="w200">신청창키워드</th>
                    <td><input type="text" name="event_name_eng" placeholder="" id="event_name_eng" value="<?=$row[event_name_eng]?>" style="width:200px"/>  <input type="button" value="신청창키워드조회" class="button " id="searchBtn"></td>
                </tr>                                    
                <tr>
                    <th class="w200">예약메시지 세트제목</th>
                    <td><input type="text" name="reservation_title" placeholder="" id="reservation_title" value="<?=$row[reservation_title]?>"/> </td>
                </tr>
                <tr>
                    <th class="w200">예약메시지 세트설명</th>
                    <td>
                        <input type="text" name="reservation_desc" placeholder="" id="reservation_desc" value="<?=$row[reservation_desc]?>"/> </td>
                </tr>
                </table>
            </div>
            <div style="text-align:center;margin-top:10px">
                <input type="button" value="저장" class="button " id="saveBtn">
                <input type="button" value="취소" class="button" id="cancleBtn">
            </div>
        </form>
        <?php 
        if($sms_idx != "")  {
        ?>
        <form name="pay_form" action="" method="post" class="my_pay">

        <input type="hidden" name="page" value="<?=$page?>" />
        <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1">
        <li style="float:left;">예약메시지 리스트</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1">
                                 
            </div>
            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:2%;"></td>
                <td style="width:6%;">회차</td>
                <td style="width:6%;">발송일시</td>
                <td style="width:20%;">메시지제목</td>
                <td style="width:40%;">메시지내용</td>
                <td style="width:15%;">이미지</td>
                <td style="width:15%;">수정/삭제</td>
              </tr>
              <?

				$sql_serch=" sms_idx ='$sms_idx' ";
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
				$sql="select count(step) as cnt from Gn_event_sms_step_info where $sql_serch ";
				$result = mysql_query($sql) or die(mysql_error());
				$row=mysql_fetch_array($result);
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
				  $order_status="asc"; 
				if($_REQUEST[order_name])
				  $order_name=$_REQUEST[order_name];
				else
				  $order_name="step";
				$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
              if($intRowCount)
              {
				$sql="select * from Gn_event_sms_step_info where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
				$result=mysql_query($sql) or die(mysql_error());				
				$c=0;
		?>   
              <?
                  while($row=mysql_fetch_array($result))
                  {
					  	 
                  ?>
              <tr>
                
                <td><?=$sort_no?></td>
                <td style="font-size:12px;"><?=$row[step]?></td>
                <td style="font-size:12px;"><?=$row[send_day]?>일후</td>
                <td><?=$row[title]?></td>
                <td><a href="javascript:void(0)" onclick="show_recv('show_content','<?=$c?>','문자내용')"><?=str_substr($row[content],0,190,'utf-8')?></a><input type="hidden" name="show_content" value="<?=$row[content]?>"/>
                    </td>
                <td>
                    <?php if($row[image]) {?>
                    <img src="/upload/<?=$row[image]?>" style="max-height:50px">
                    <?php }?>
                    <?php if($row[image1]) {?>
                    <img src="/upload/<?=$row[image1]?>" style="max-height:50px">
                    <?php }?>
                    <?php if($row[image2]) {?>
                    <img src="/upload/<?=$row[image2]?>" style="max-height:50px">
                    <?php }?>                    
                </td>
                <td>
                    <a href="javascript:;;" onclick="editRow('<?php echo $row['sms_detail_idx'];?>','<?php echo $row['sms_idx'];?>')">수정</a>/
                    <a href="javascript:;;" onclick="deleteRow('<?php echo $row['sms_detail_idx'];?>','<?php echo $row['sms_idx'];?>')">삭제</a>
                </td>                                
                
              </tr>
              <?
                $c++;
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
            <div style="margin-top:10px;">
                <input type="button" value="발송회차 추가하기" class="button popbutton4">
            </div>

            </div>
        </div>
        </form>
        <?php }?>
    </div>     
    
</div> 

<Script>
function editRow(sms_detail_idx, sms_idx) {
	$.ajax({
		 type:"POST",
		 url:"mypage.proc.php",
		 dataType:"json",
		 data:{
			 mode : "sms_detail_info",
    			 sms_detail_idx: sms_detail_idx,
    			 sms_idx: sms_idx
			 },
		 success:function(data){
		 	    console.log(data);
		 	    console.log(data.data.sms_detail_idx);
        		$('.ad_layer4').lightbox_me({
        			centered: true,
        			onLoad: function() {
                        $('#mode').val('step_update');
                        $('#sms_detail_idx').val(data.data.sms_detail_idx);
                        console.log(data.data);
                        var send_time  = (data.data.send_time).split(":");
                        $('#step').val(data.data.step);

                        $('#send_day').val(data.data.send_day);
                        if(data.data.send_day != "0") $('#timeArea').show();
                        $('#send_time_hour').val(send_time[0]);
                        $('#send_time_min').val(send_time[1]);

                        $('#title').val(data.data.title);
                        $('#content').val(data.data.content);
                        $('#image1').html('');
                        $('#image2').html('');
                        $('#image3').html('');

                        if(data.data.image1)
                            $('#image2').html('<img src="/upload/'+data.data.image1+'" style="width:80px">');
                            
                        if(data.data.image2)
                            $('#image3').html('<img src="/upload/'+data.data.image2+'" style="width:80px">');
                            
                        if(data.data.image)    
                            $('#image1').html('<img src="/upload/'+data.data.image+'" style="width:80px">');
        			    
        			}
        		});		 	
		 	}
		});
		return false;
}    
function deleteRow(sms_detail_idx, sms_idx) {
    if(confirm('삭제하시겠습니까?')) {

    	$.ajax({
    		 type:"POST",
    		 url:"mypage.proc.php",
    		 data:{
    			 mode : "sms_detail_del",
    			 sms_detail_idx: sms_detail_idx,
    			 sms_idx: sms_idx
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
function newpop(str){
    window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}
var tid;

	$(".popup_holder").hover(function(){
		$(".popupbox").hide();
		clearTimeout(tid);
		$(this).children(".popupbox").show();
		//$(this).children(".popupbox").css("height","50px");
		//$(this).children(".popupbox").css("width","200px");
	},function (e) {
		tid = setTimeout(function() {
			//$(e.currentTarget).children(".popupbox").hide();
		}, 3000);
   });
   $(".popup_text").hover(function(){
		$(this).css("color","#0f7bef");
		$(this).children(".popupbox").css("color","black");
	},function (e) {
		$(".popup_text").css("color","black");
   });
</script>
<style>
.popup_holder
{
	position:relative;
}
.popupbox
{	
	z-index: 1;
	text-align: left;
	font-size: 12px;
	font-weight: normal;
	background: white;
	border-radius: 3px;
	padding: 10px;
	border: none;
	position: absolute;
	box-shadow:  0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);
}
</style>


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
	
	if(confirm(msg))
	{
		$.ajax({
			 type:"POST",
			 url:"ajax/ajax.php",
			 data:{
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
				 bank_owner:bank_owner
				 },
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
$(function() {



    $('#saveBtn').on("click",function() {
        if($('#mobile').val() == "") {
            alert('발송폰번호을 입력해주세요.');
            return;
        }        
        if($('#event_name_eng').val() == "") {
            alert('신청키워드을 입력해주세요.');
            return;
        }        
        if($('#reservation_title').val() == "") {
            alert('예약메시지 제목을 입력해주세요.');
            return;
        }
        $('#sform').submit();
    });
	$(".popbutton4").click(function(){
		$('.ad_layer4').lightbox_me({
			centered: true,
			onLoad: function() {
                $('#mode').val('step_add');
                $('#sms_detail_idx').val('');


                $('#step').val('');

                $('#day').val('');
                $('#send_time_hour').val('');
                $('#send_time_min').val('');

                $('#title').val('');
                $('#content').val('');

                $('#image1').html('');
                $('#image2').html('');
                $('#image3').html('');
			    
			}
		});
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
	$('#popSaveBtn').on("click", function() {
	    if($('#step').val() == "") {
	        alert('순서를 입력하세요.');
	        return;
	    }
	    
	    if($('#send_day').val() == "") {
	        alert('발송일시를 입력하세요.');
	        return;
	    }	    
	    
	    //if($('#send_time_hour').val() == "") {
	    //    alert('발송일시를 입력하세요.');
	    //    return;
	    //}	    
	    //
	    //if($('#send_time_min').val() == "") {
	    //    alert('발송일시를 입력하세요.');
	    //    return;
	    //}	    	    	    
	    
	    if($('#title').val() == "") {
	        alert('제목을 입력하세요.');
	        return;
	    }	    	    
	    
	    if($('#content').val() == "") {
	        alert('내용을 입력하세요.');
	        return;
	    }	    	    	    
	    $('#addForm').submit();
	});
	
	$('#popCloseBtn').on("click", function() {
	    $('.lb_overlay, .ad_layer4').hide();
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

	<div class="ad_layer4">
		<div class="layer_in">
			<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
			<div class="pop_title">
				예약메시지
			</div>
			<div class="info_box">
			    <form method="post" name="addForm" id="addForm"  action="mypage.proc.php" enctype="multipart/form-data">
			    <input type="hidden" name="sms_idx" value="<?php echo $sms_idx;?>">
			    <input type="hidden" name="mode" id="mode" value="step_add">
			    <input type="hidden" name="sms_detail_idx" id="sms_detail_idx"> 
				<table class="info_box_table" cellpadding="0" cellspacing="0">
					<tbody>
						<tr>
							<th class="w200">순서</th>
							<td>
							    <input type="text" id="step" name="step" value="" style="width:70px;">
							</td>
						</tr>					    
						<tr>
							<th class="w200">발송일시</th>
							<td>
							    <input type="text" id="send_day" name="send_day" value="" style="width:70px;" maxlength="3">일후(0이면 신청 후 즉시 발송)
							    <span id="timeArea" style="display:none">
							    <input type="text" id="send_time_hour" name="send_time_hour" value="" style="width:70px;" maxlength="2"> 시
							    <input type="text" id="send_time_min" name="send_time_min" value="" style="width:70px;" maxlength="2"> 분 (10분단위로 설정가능)
							    </span>
							    <div id="display_day"></div>
							</td>
						</tr>
						<tr>
							<th>메시지제목</th>
							<td><input type="text" id="title" name="title" value="" ></td>
						</tr>			
						<tr>
							<th>메시지내용</th>
							<td>
                                <textarea name="content" itemname="내용" id="content" required="" placeholder="메시지내용을 입력하세요" style="background-color: rgb(200, 237, 252);width:300px;height:200px;"></textarea>                    							    
							    </td>
						</tr>			
						<tr>
							<th>이미지1</th>
							<td><input type="file" id="file" name="image" value=""  accept="image/*" ><span id="image1"></span></td>
						</tr>												
						<tr>
							<th>이미지2</th>
							<td><input type="file" id="file" name="image1" value=""  accept="image/*" ><span id="image2"></span></td>
						</tr>												
						<tr>
							<th>이미지3</th>
							<td><input type="file" id="file" name="image2" value=""  accept="image/*" ><span id="image3"></span></td>
						</tr>																								
						 
					</tbody>
				</table>
                </form>
			</div>
			<div class="ok_box">
                <input type="button" value="취소" class="button "  id="popCloseBtn">
                <input type="button" value="저장" class="button" id="popSaveBtn">				
			</div>

		</div>


	</div>
<div id="open_recv_div" class="open_recv_div" name="open_recv_div">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	<li class="open_recv_title open_2_1"></li>
    	<li class="open_2_2"><a href="javascript:void(0)" onclick="close_div(open_recv_div)"><img src="images/div_pop_01.jpg"></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
    </div>
</div>