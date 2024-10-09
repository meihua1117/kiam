<?
$path="./";
include_once "_head.php";
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
$mem_phone = str_replace("-","",$data['mem_phone']);

$sql="select * from Gn_event  where event_idx='".$_GET[event_idx]."'";

$sresul_num=mysql_query($sql);
$row=mysql_fetch_array($sresul_num);	
?>
<script>

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
.ad_layer1 {
    width: 484px;
    height: 200px;
    background-color: #fff;
    border: 2px solid #24303e;
    position: relative;
    box-sizing: border-box;
    padding: 30px 30px 50px 30px;
    display: none;
}

.ad_layer2 {
    width: 484px;
    height: 200px;
    background-color: #fff;
    border: 2px solid #24303e;
    position: relative;
    box-sizing: border-box;
    padding: 30px 30px 50px 30px;
    display: none;
}

</style>

<div class="big_sub">
    
<?php include_once "mypage_step_navi.php";?>

   <div class="m_div">
       <?php include_once "mypage_left_menu.php";?>
       <div class="m_body">

        <form name="sform" id="sform" action="mypage.proc.php" method="post" enctype="multipart/form-data">

        <input type="hidden" name="mode" value="<?php echo $_GET[event_idx]?"event_update":"event_save";?>" />
        <input type="hidden" name="event_idx" value="<?php echo $_GET[event_idx];?>" />
        <input type="hidden" name="dup" id="dup" value="<?php echo $_GET[event_idx]?"ok":"";?>" />
        <div class="a1" style="margin-top:50px; margin-bottom:15px">
        <li style="float:left;">		
			<div class="popup_holder popup_text" style=cursor:pointer;>고객 신청창 만들기
				<div id="_popupbox" class="popupbox" style="height: 75px;width: 260px;left: 160px;top: -37px; display:none;" >자신의 이벤트 상품이나 서비스를 소개하거나 상세페이지로 만든 랜딩페이지를 리스트로 보는 기능입니다.<br><br>
				  <a class = "detail_view" style="color: blue;" href="https://url.kr/ldQyeR" target="_blank">[자세히 보기]</a>

				</div>
			</div>		
		</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1">
                <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th class="w200">링크생성</th>
                    <td>
                        <?php if($_GET['event_idx']){
                        ?>    
                        <input type="text" name="url" placeholder="" id="url" value="http://kiam.kr/event/event.html?pcode=<?php echo $row['pcode'];?>&sp=<?php echo $row['event_name_eng'];?>&event_idx=<?php echo $row['event_idx'];?>" style="border:0px" readonly/> <BR>
                        <input type="button" value="이벤트창 미리보기" class="button"  id="showBtn" onclick="newpop('/event/event.html?pcode=<?php echo $row['pcode'];?>&sp=<?php echo $row['event_name_eng'];?>')">
                        <input type="button" value="단축 URL복사" class="button"  id="copyBtn" onclick="copyHtml('<?php echo $row['short_url']?>')">            
                        <?php }?>
                    </td>
                </tr>    
                <tr>
                    <th class="w200">신청창정보</th>
                    <td>
                        <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <th class="w200">신청창제목</th>
                            <td>
                            <input type="text" name="event_title" placeholder="" id="event_title" value="<?=$row[event_title]?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th class="w200">신청항목선택</th>
                            <td>
                                <input type="checkbox" name="event_info[]" placeholder="" id="event_info0" value="sms" <?php if(strstr($row['event_info'],"sms")) echo "checked";?>/> 문자인증
                                <input type="checkbox" name="event_info[]" placeholder="" id="event_info1" value="email" <?php if(strstr($row['event_info'],"email")) echo "checked";?>/> 이메일
                                <input type="checkbox" name="event_info[]" placeholder="" id="event_info2" value="sex" <?php if(strstr($row['event_info'],"sex")) echo "checked";?>/> 성별
                                <input type="checkbox" name="event_info[]" placeholder="소속과 직업을 '/'로 구분하여 입력하세요" id="event_info3" value="job" <?php if(strstr($row['event_info'],"job")) echo "checked";?>/>  소속/직업
                                <input type="checkbox" name="event_info[]" placeholder="" id="event_info4" value="address" <?php if(strstr($row['event_info'],"address")) echo "checked";?>/> 주소
                                <input type="checkbox" name="event_info[]" placeholder="통계정보이니 년도만(예시:1995) 입력하세요" id="event_info5" value="birth" <?php if(strstr($row['event_info'],"birth")) echo "checked";?>/> 출생년도
                                <input type="checkbox" name="event_info[]" placeholder="참여하고 싶은 강연제목 입력" id="event_info6" value="consult_date" <?php if(strstr($row['event_info'],"consult_date")) echo "checked";?>/> 신청강좌명
                                <input type="checkbox" name="event_info[]" placeholder="" id="event_info7" value="join" <?php if(strstr($row['event_info'],"join")) echo "checked";?>/> 회원가입
                                
                                <BR>
                                (※ 이름과 휴대전화는 자동선택됩니다)
                            </td>
                        </tr>                        
                        <tr>
                            <th class="w200">신청정보안내</th>
                            <td>
                            <textarea name="event_desc" placeholder="" id="event_desc" style="width:100%;height:200px"/><?=$row[event_desc]?></textarea>
                            </td>
                        </tr>                        
                        </table>
                        
                    </td>
                </tr>                
                <!--
                <tr>
                    <th class="w200">신청타입</th>
                    <td>
                        <select name="event_type" id="event_type" style="height:30px">
                            <option value="1" <? echo $row['event_type'] =="1"?"Selected":""?>>신청타입</option>
                            <option value="2" <? echo $row['event_type'] =="2"?"Selected":""?>>퀴즈타입</option>
                            <option value="3" <? echo $row['event_type'] =="3"?"Selected":""?>>설문타입</option>
                        </select>
                    </td>
                </tr>
                -->
                <tr>
                    <th class="w200">신청내용(한글)</th>
                    <td><input type="text" name="event_name_kor" placeholder="" id="event_name_kor" value="<?=$row[event_name_kor]?>"/> </td>
                </tr>                               
                <tr>
                    <th class="w200">신청창키워드(영문)</th>
                    <td><input type="text" name="event_name_eng" placeholder="" id="event_name_eng" value="<?=$row[event_name_eng]?>" <?php $_GET[event_idx]?"readonly":""?>/> 
                        <span id="msg"></span><br>이 키워드는 원스텝 문자의 다양한 기능에 사용되므로 중복사용이 안됩니다.<span class="popbutton2 pop_view">?</span>
                        </td>
                </tr>                          
                <tr>
                    <th class="w200">신청채널입력</th>
                    <td><input type="text" name="pcode" placeholder="" id="pcode" value="<?=$row[pcode]?>"/> <span class="popbutton1 pop_view">?</span></td>
                </tr>                  
                   

                <tr>
                    <th class="w200">발송폰번호</th>
                    <td><input type="text" name="mobile" placeholder="" id="mobile" value="<?=$mem_phone?>" readonly/> </td>
                </tr>                                                                                          
                </table>
   <!--             <div style="border:1px solid #eee;">
                    알림문자 예시<br>
                    OOO님!!!<br>
                    신청해주셔서 감사합니다.<br>
                   </div>-->
                </div>
            </div>
            <div class="p1" style="text-align:center;margin-top:20px;">
            <input type="button" value="취소" class="button"  id="cancleBtn">
            <input type="button" value="저장" class="button" id="saveBtn">
            </div>
        </div>
        </form>
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
$(function() {
 

$('#_popupbox').mouseout(function() { $(this).hide(); });


	$(".popbutton1").click(function(){
		$('.ad_layer1').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	})


	$(".popbutton2").click(function(){
		$('.ad_layer2').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	})
    
    $('#event_name_eng').bind("keyup", function() {
        //console.log(($('#event_name_eng').val()).length);
        if(($('#event_name_eng').val()).length < 3) {
            
        } else {
            //console.log("====");
        	$.ajax({
        		 type:"POST",
        		 url:"mypage.proc.php",
        		 dataType: 'json',
        		 data:{
        			 mode : "event_check",
        			 event_name_eng: $(this).val()
        			 },
        		 success:function(data){
        		    if(data.result == "success") {
        		        $('#dup').val("ok");
        		        $('#event_name_eng').css("border","2px solid blue");
        		        $('#pcode').val($('#event_name_eng').val());
        		        $('#msg').html('사용이 가능한 이벤트명입니다.')
        		        $('#msg').css("color","blue");
        		    } else {
        		        $('#dup').val("");
        		        $('#event_name_eng').css("border","2px solid red");
        		        $('#msg').html('사용중인 이벤트명입니다.')
        		        $('#msg').css("color","red");
        		    }
        		    //console.log(data);
        		 	//$("#ajax_div").html(data);
        		 	//alert('저장되었습니다.');
        		 	}
        		});
        		return false;            
        }
    });
    $('#cancleBtn').on("click", function() {
        location = "mypage_link_list.php";
    });
    
    $('#saveBtn').on("click", function() {
 
        if($('#event_name_kor').val() == "") {
            alert('이벤트 (한글)을 입력해주세요');
            return;            
        }
        if(($('#event_name_eng').val()).length < 3) {
            alert('이벤트 (영문)은 최소3자 이상입니다.');
            return;            
        }
        
        if($('#dup').val() != "ok") {
            alert('이벤트 (영문) 중복확인해주세요.');
            return;                        
        }
        
        if($('#pcode').val() == "") {
            alert('신청경로를 입력해주세요');
            return;
        }
        
        if($('#mobile').val() == "") {
            alert('연락처를 입력해주세요');
            return;
        }        
        
        $('#sform').submit();      
    });
    
    $('#event_info7').click(function(){
        if(this.checked){
            $('#event_info0').attr("checked", "checked");
        }
    });
    $('#event_info7').click(function(){
        if(!this.checked){
            $('#event_info0').removeAttr("checked");
        }
    });
})
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

	<div class="ad_layer1">
		<div class="layer_in">
			<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
			<div class="pop_title">
			</div>

			<div class="info_text">
				<p>

<span><br>신청경로를 채널별로 다르게 작성하시면 유입채널을 구분할 수 있습니다.<br> 예시 : event_m(문자) event_b(블로그) event_c(카페) event_k(카톡) </span>
				</p>
			</div>

		</div>
	</div>



	<div class="ad_layer2">
		<div class="layer_in">
			<span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>	
			<div class="pop_title">
			</div>
 

			<div class="info_text">
				<p>
					<span>이벤트명 (영문)은 중복될 수 없습니다. 예약문자 설정할 때 이벤트명 (영문)이 중복되면 원하는 문자가 발송되지 않습니다.</span>
				</p>
			</div>

		</div>
	</div>
