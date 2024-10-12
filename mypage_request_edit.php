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
	$sql="select * from Gn_Member  where mem_id='".$_SESSION['one_member_id']."'";
	$sresul_num=mysqli_query($self_con,$sql);
	$data=mysqli_fetch_array($sresul_num);	
	
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



$sql="select * from Gn_event_request  where request_idx='".$request_idx."'";
$sresul_num=mysqli_query($self_con,$sql);
$data = $row=mysqli_fetch_array($sresul_num);	

$sql="select * from Gn_event where event_idx='$row['event_idx']' order by event_idx desc";
$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$event_data = $row=mysqli_fetch_array($result);
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
	});
	
	$('#cancleBtn').on("click", function() {
	   location = "mypage_request_list.php"; 
	});
	

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


<div class="big_sub">
    
<?php include "mypage_step_navi.php";?>

   <div class="m_div">
       <?php include "mypage_left_menu.php";?>
       <div class="m_body">


            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1" style="margin-top:15px; margin-bottom:15px">
        <!--li style="float:left;">신청고객 수동추가</li-->

 		<li style="float:left;">		
			<div class="popup_holder popup_text">신청자 추가
				<div id="_popupbox" class="popupbox" style="height: 75px;width: 260px;left: 160px;top: -37px;display:none ;">자신의 이벤트 상품이나 서비스를 소개하거나 상세페이지로 만든 랜딩페이지를 리스트로 보는 기능입니다.<br><br>
				  <a class = "detail_view" style="color: blue;" href="https://url.kr/ldQyeR" target="_blank">[자세히 보기]</a>

				</div>
			</div>		
		</li>


        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <form name="sform" id="sform" action="mypage.proc.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" value="<?php echo $_REQUEST['request_idx']!=""?"request_update":"request_add"?>" />
        <input type="hidden" name="request_idx" value="<?php echo $request_idx;?>" />
        <input type="hidden" name="org_event_code" value="<?php echo $row['sp'];?>" />
        <input type="hidden" name="event_idx" id="event_idx" value="<?php echo $event_data['event_idx'];?>" />
		<input type="hidden" name="step_num" id="step_num" value="<?=$data[sms_step_num]?>" />
		<input type="hidden" name="sms_idx" id="sms_idx" value="" />
		<input type="hidden" name="event_code" id="event_code" value="" />
		<input type="hidden" name="event_name_eng_event" id="event_name_eng_event" value="" />
        
            <div class="p1">
                <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th class="w200">신청자이름</th>
                    <td><input type="text" name="name" placeholder="" id="name" value="<?=$data['name']?>" style="width:200px"/> </td>
                </tr>                    
                <tr>
                    <th class="w200">핸드폰</th>
                    <td><input type="text" name="mobile" placeholder="" id="mobile" value="<?=$data['mobile']?>" style="width:200px"/> </td>
                </tr>                                    
                <tr>
                    <th class="w200">성별</th>
                    <td>
						<input type="radio" name="sex" value="m" <?php echo $data['sex']=="m"?"checked":""?>>    남
						<input type="radio" name="sex" value="f" <?php echo $data['sex']=="f"?"checked":""?>>    여                        
                    </td>
                </tr>                
                <tr>
                    <th class="w200">이메일</th>
                    <td><input type="text" name="email" placeholder="" id="email" value="<?=$data['email']?>"/> </td>
                </tr>                

                <tr>
                    <th class="w200">직업</th>
                    <td>
                        <input type="text" name="job" placeholder="" id="job" value="<?=$data[job]?>"/> </td>
                </tr>
                <tr>
                    <th class="w200">주소</th>
                    <td><input type="text" name="addr" placeholder="" id="addr" value="<?=$data[addr]?>"/> </td>
                </tr>                
                <tr>
                    <th class="w200">생년월일</th>
                    <td><input type="text" name="birthday" placeholder="" id="birthday" value="<?=$data[birthday]?>"/> </td>
                </tr>                                
                <tr>
                    <th class="w200">기타</th>
                    <td><input type="text" name="consult_date" placeholder="" id="consult_date" value="<?=$data[consult_date]?>"/> </td>
                </tr>                                
                <tr>
                    <th class="w200">신청행사</th>
                    <td>
                        <input type="text" name="sp" readonly placeholder="" id="event_name_eng" value="<?=$event_data['event_name_eng']?>"/> <input type="button" value="신청그룹 조회" class="button " id="searchBtn"></td>
                </tr>                                           
                <tr>
                    <th class="w200">등록일</th>
                    <td><?php echo $data['regdate'];?>
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
function newpop(){
    var win = window.open("mypage_pop_link_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}    
function newpop_step(){
    var win = window.open("../mypage_pop_message_list_for_addstep_edit.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
}    
$(function() {
    $('#searchBtn').on("click", function() {
        newpop();
    });
	$('#searchstep').on("click", function() {
        newpop_step();
    });
    ///추가
    $('#_popupbox').mouseout(function() { $(this).hide(); });
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

})

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

</Script>


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
        if($('#event_name_eng').val() == "" && $('#step_sms_title').val() == "") {
            alert('이벤트명/스텝문자명을 입력해주세요.');
            return;
        }        
        if($('#reservation_title').val() == "") {
            alert('예약문자 제목을 입력해주세요.');
            return;
        }
        $('#sform').submit();
    });
	$(".popbutton4").click(function(){
		var phoneno = $(this).siblings().eq(0).find("input").val();
		$('.ad_layer4').lightbox_me({
			centered: true,
			onLoad: function() {
			    /*
				$.ajax({
					type:"POST",
					url:"ajax/get_numinfo_election.php",
					data:{pno:phoneno},
					dataType: 'json',
					success:function(data){
						$("#detail_name").val(data.detail_name);
						$("#pno").val(data.phoneno);
						$("#phoneno").html(data.phoneno);
						$("#installDate").html(data.installDate);	
						$("#donationCnt").html(data.donationCnt);
						$("#sshLimit").html(data.sshLimit);
						$("#phoneNumber").html(data.phoneNumber);
						$("#personalCnt").html(data.personalCnt);
						$("#cnt1State").html(data.cnt1State);
						$("#detail_company").val(data.detail_company);
						
						
						if(data.excelDownload == "완료")
						    $("#excelDownload").html("없음");
						else 
						    $("#excelDownload").html(data.excelDownload);
						$("#todaySendCnt").html(data.todaySendCnt);
						$("#cnt2State").html(data.cnt2State);
						$("#diviceModel").html(data.diviceModel);
						$("#detail_rate").val(data.detail_rate);
						$("#thismonthCnt").html(data.thismonthCnt);
						$("#lastSendDate").html(data.lastSendDate);	
						$("#syncDbdate").html(data.syncDbdate);	
					},
					error: function(){
					  alert('로딩 실패');
					}
				});
				*/
			}
		});
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
	    
	    if($('#send_time_hour').val() == "") {
	        alert('발송일시를 입력하세요.');
	        return;
	    }	    
	    
	    if($('#send_time_min').val() == "") {
	        alert('발송일시를 입력하세요.');
	        return;
	    }	    	    	    
	    
	    if($('#title').val() == "") {
	        alert('제목을 입력하세요.');
	        return;
	    }	    	    
	    
	    if($('#content').val() == "") {
	        alert('제목을 입력하세요.');
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
    height: 498px;
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
				예약문자
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
							    <input type="text" id="day" name="send_day" value="" style="width:70px;" maxlength="3">일후(0이면 신청직후발송)
							    <input type="text" id="send_time_hour" name="send_time_hour" value="" style="width:70px;" maxlength="2"> 시
							    <input type="text" id="send_time_min" name="send_time_min" value="" style="width:70px;" maxlength="2"> 분 (10분단위로 설정가능)
							    <div id="display_day"></div>
							</td>
						</tr>
						<tr>
							<th>문자제목</th>
							<td><input type="text" id="title" name="title" value="" ></td>
						</tr>			
						<tr>
							<th>문자내용</th>
							<td>
                                <textarea name="content" itemname="내용" id="content" required="" placeholder="reservation_desc" style="background-color: rgb(200, 237, 252);width:300px;height:200px;"></textarea>                    							    
							    </td>
						</tr>			
						<tr>
							<th>첨부파일</th>
							<td><input type="file" id="file" name="image" value="" ></td>
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