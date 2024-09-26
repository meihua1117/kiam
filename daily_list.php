<?
$path="./";
include_once "_head.php";
if(!$_SESSION['one_member_id'])
{

?>
<script language="javascript">
location.replace('/ma.php');
</script>
<?
exit;
}
	$sql="select * from Gn_Member  where mem_id='".$_SESSION['one_member_id']."' and site != ''";
	$sresul_num=mysqli_query($self_con, $sql);
	$data=mysqli_fetch_array($sresul_num);	
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

function go_sendlist(){
	location.href="sub_4_return_.php?chanel=4";
}
</script>
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
        <div class="a1" style="margin-top:50px; margin-bottom:15px">
        <li style="float:left;">		
			<div class="popup_holder popup_text">데일리발송 세트리스트
				<div class="popupbox" style="display:none;height: 75px;width: 220px;left: 200px;top: -37px;">디비를 매일 발송가능한 숫자로 나누어 매일 발송할 수 있도록 자동화한 문자발송 솔루션입니다.<br><br>
				  <a class = "detail_view" style="color: blue;" href="https://tinyurl.com/5ey7er6r" target="_blank">[자세히 보기]</a>

				</div>
			</div>		
		</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1">
                <select name="search_key" class="select">
					<option value="all" <?=$_REQUEST['search_key'] == "all"?"selected":""?>>전체</option>
					<option value="step" <?=$_REQUEST['search_key'] == "step"?"selected":""?>>데일리스텝</option>
                </select>
                <input type="text" name="search_text" placeholder="" id="search_text" value="<?=$_REQUEST['search_text']?>"/> 
                <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>                                            
                <div style="float:right;display: flex;">
					<div class="popup_holder"> <!--Parent-->
						<input type="button" value="데일리 발신내역" class="button" onclick="go_sendlist()" style="cursor: pointer">
					</div> 
					<div class="popup_holder"> <!--Parent-->
						<input type="button" value="메시지세트 등록하기" class="button" onclick="location='daily_write.php'">
						<div class="popupbox" style="display:none; height: 50px;width: 160px;bottom: 37px;">클릭하면 데일리 메시지 세트를 만들 수 있습니다.<br><!--Child-->
						  <a class = "detail_view" href="https://tinyurl.com/4ktekrcd" target="_blank">[자세히 보기]</a>
						</div>
					</div>                                        
					<div class="popup_holder"> <!--Parent-->
						<input type="button" value="선택삭제" class="button" onclick="deleteMultiRow()" style="cursor: pointer">
					</div>                                    
                </div>                
            </div>

            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:2%;"><input type="checkbox" name="allChk" id="allChk"></td>
                <td style="width:6%;">No</td>
                <td style="width:10%;">메시지제목</td>
                <td style="width:8%;">발송폰번호</td>
                <td style="width:10%;">주소록이름</td>
                <td style="width:6%">주소건수</td>
                <td style="width:8%">발송일수</td>
                <td style="width:8%">일발송량</td>
                <td style="width:8%">발송시작일</td>
                <td style="width:8%">발송마감일</td>
                <td style="width:9%;">등록일</td>
                <!--<td style="width:9%;">상태</td>-->
                <td style="width:9%;">관리</td>
                
              </tr>
              <?

				$sql_serch=" mem_id ='{$_SESSION['one_member_id']}' ";
				if($_REQUEST['search_text'])
				{					
					
					    $search_text=$_REQUEST['search_text'];
					    $sql_serch.=" and title like '%{$_REQUEST['search_text']}%' ";
				}

				if($_REQUEST['search_key'] == "step")
				{
				    $sql_serch.=" and step_sms_idx!=0 ";
				}
				
				$sql="select count(gd_id) as cnt from Gn_daily where $sql_serch ";
				$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
				$row=mysqli_fetch_array($result);
				$intRowCount=$row['cnt'];
				
              if($intRowCount)
              {				
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
				  $order_name="gd_id";
				  
				$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
				$sql="select * from Gn_daily where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
				$result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
		?>   
              <?
                  while($row=mysqli_fetch_array($result))
                  {
					  	 
							$sql="select * from Gn_MMS_Group where idx='$row[group_idx]'";
							$sresult=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));					  	 
							$krow = mysqli_fetch_array($sresult);
							
							$sql="select count(*) cnt from Gn_daily_date where gd_id='$row[gd_id]'";
							$sresult=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));					  	 
							$srow = mysqli_fetch_array($sresult);							
					  	 
                  ?>
              <tr>
                <td><input type="checkbox" class="check" name="gd_id" value="<?php echo $row['gd_id'];?>"></td>
                <td><?=$sort_no?></td>

                <td style="font-size:12px;"><?=$row['title']?></td>
                <td style="font-size:12px;"><?=$row['send_num']?></td>
                <td style="font-size:12px;"><?=$krow['grp']?></td>
                <td style="font-size:12px;"><?=$row['total_count']?></td>
                <td style="font-size:12px;"><?=$srow['cnt']?></td>
                <td style="font-size:12px;"><?=$row['daily_cnt']?></td>
                <td style="font-size:12px;"><?=$row['start_date']?></td>
                <td style="font-size:12px;"><?=$row['end_date']?></td>
                    
                <td><?=$row['reg_date']?></td>
                <!--<td><?=$row['status']?></td>-->
                <td>
                    <a href='daily_write.php?gd_id=<?php echo $row['gd_id'];?>'>수정</a>/<a href="javascript:;;" onclick="deleteRow('<?php echo $row['gd_id'];?>')">삭제</a>
                </td>                
              </tr>
              <?
                    $sort_no--;
                  }
                  ?>		               
              <tr>
                <td colspan="13">
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
                <td colspan="13">
                    검색된 내용이 없습니다.
                </td>
              </tr>            
                <?  
              }
              ?>
            </table>
            <!--
            <input type="button" value="예약 문자 보내기" class="button">
            -->
            </div>
        </div>
        </form>
    </div>     
   </div> 
</div> 
</div>

<Script>
function newpop(str){
    window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}
$('#allChk').on("change",function(){
	$('.check').prop("checked", $(this).is(":checked"));
});
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

function deleteRow(gd_id) {
    if(confirm('삭제하시겠습니까?')) {

    	$.ajax({
    		 type:"POST",
    		 url:"mypage.proc.php",
    		 data:{
    			 mode : "daily_del",
    			 gd_id: gd_id
    			 },
    		 success:function(data){
    		 	//$("#ajax_div").html(data);
    		 	alert('삭제되었습니다.');
    		 	location.reload();
    		 	}
    		});
    		return false;
    }
}

function deleteMultiRow() {
    var check_array = $(".list_table").children().find(".check");
    var no_array = [];
    var index = 0;
    check_array.each(function(){
        if($(this).prop("checked") && $(this).val() > 0)
            no_array[index++] = $(this).val();
    });

    if(no_array.length == 0){
        alert("삭제할 신청창을 선택하세요.");
        return;
    }
    if(confirm('삭제하시겠습니까?')) {
        $.ajax({
            type:"POST",
            url:"/admin/ajax/delete_func.php",
            dataType:"json",
            data:{admin:0, delete_name:"daily_list", id:no_array.toString()},
            success: function(data){
                console.log(data);
                if(data == 1){
                    alert('삭제 되었습니다.');
                    window.location.reload();
                }
            }
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
			 success:function(data){
				 alert('신청되었습니다.');
				 location.reload();
			 }
		});
    }
}
function removeAll() {
    var no ="";
    if(confirm('모든 페이지 데이타를 모두 삭제합니다.  삭제하시겠어요?')) {
		$.ajax({
			type:"POST",
			url:"/admin/ajax/delete_func.php",
			data:{admin:0, delete_name:"daily_list", mem_id:'<?=$_SESSION['one_member_id']?>'},
			success:function(){
				alert('삭제되었습니다.');
				location.reload();
			},
			error: function(){
				alert('삭제 실패');
			}
		});
    }
}
        </script>   
<?
include_once "_foot.php";
?>   