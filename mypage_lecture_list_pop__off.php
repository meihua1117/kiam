<?
$path="./";
include_once "_head_open.php";
/*
//if(!$_SESSION[one_member_id])
//{
//
//?>
//<script language="javascript">
//location.replace('/ma.php');
//</script>
//<?
//exit;
//}

	*/
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
        if(confirm("이 링크를 복사하시겠습니까?")) {
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
</style>

<div class="big_sub">
    

   <div class="m_div">
       <?php include_once "mypage_left_menu.php";?>
       <div class="m_body">

        <form name="pay_form" action="" method="post" class="my_pay">

            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1" style="margin-top:15px; margin-bottom:15px">
        <li style="float:left;">강연/교육과정 입력결과보기</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
								<script>
								$(function() {
								$('input[name=category]').bind("click",function() {
										$.ajax({
											type:"POST",
											url:"/event/event.lecture.ajax.html",
											data:{
												mode:"review_save",
												category:$('input[name=category]:checked').val(),
												end_date:$('input[name=end_date]:checked').val(),
												search_text:$('#search_text').val(),
												landing_idx:'<?php echo $_REQUEST['landing_idx'];?>'
											},
											success:function(data){
											    $('#lecture_list').html(data);
                								$('.paging').on("click",function() {
                								    ajaxView($(this).data("page"));
                								});
											}
										});
								});
								$('input[name=end_date]').bind("click",function() {
										$.ajax({
											type:"POST",
											url:"/event/event.lecture.ajax.html",
											data:{
												mode:"review_save",
												category:$('input[name=category]:checked').val(),
												end_date:$('input[name=end_date]:checked').val(),
												search_text:$('#search_text').val(),
												landing_idx:'<?php echo $_REQUEST['landing_idx'];?>'
											},
											success:function(data){
											    $('#lecture_list').html(data);
                								$('.paging').on("click",function() {
                								    ajaxView($(this).data("page"));
                								});
											}
										});
								});
								$('#searchC').bind("click",function() {
										$.ajax({
											type:"POST",
											url:"/event/event.lecture.ajax.html",
											data:{
												mode:"review_save",
												category:$('input[name=category]:checked').val(),
												end_date:$('input[name=end_date]:checked').val(),
												search_text:$('#search_text').val(),
												landing_idx:'<?php echo $_REQUEST['landing_idx'];?>'
											},
											success:function(data){
											    $('#lecture_list').html(data);
                								$('.paging').on("click",function() {
                								    ajaxView($(this).data("page"));
                								});											    
											}
										});
								});

								})
								function ajaxView(page) {
									$.ajax({
										type:"POST",
										url:"/event/event.lecture.ajax.html",
										data:{
											mode:"review_save",
											category:$('input[name=category]:checked').val(),
											end_date:$('input[name=end_date]:checked').val(),
											search_text:$('#search_text').val(),
											landing_idx:'<?php echo $_REQUEST['landing_idx'];?>',
											page:page,
										},
										success:function(data){
										    $('#lecture_list').html(data);
            								$('.paging').on("click",function() {
            								    ajaxView($(this).data("page"));
            								});
										}
									});								    
								}
								</script>        
        <div>
            <div class="p1">
                
                <input type="radio" name="category" value="">전체
                <input type="radio" name="category" value="강연" checked>강연
                <input type="radio" name="category" value="교육">교육
                <input type="radio" name="category" value="영상">영상
                <input type="text" name="search_text" placeholder="" id="search_text" value="<?=$_REQUEST[search_text]?>"/> 
                <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>                                            
                <div style="float:right">
                    <input type="radio" name="end_date" value="">전체
                    <input type="radio" name="end_date" value="Y">진행완료
                    <input type="radio" name="end_date" value="N" checked>진행중
                </div>
            </div>
            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="width:2%;"></td>
                <td style="width:6%;">No</td>
                <td style="width:6%;">분야</td>
                <td style="width:11%;">일정/기간</td>
                <td style="width:8%;">요일</td>
                <td style="width:8%">시간</td>
                <td style="width:8%">강의내용(자세히보기)</a></td>
                <td style="width:8%;">강사</td>            
                <td style="width:8%;">지역</td>            
                <td style="width:8%;">정원</td>            
                <td style="width:8%;">비용</td>            
                <td style="width:8%;">사용</td>
              </tr>
              <tbody id="lecture_list">
              <?

				//$sql_serch=" mem_id ='$_SESSION[one_member_id]' and status='Y' ";
					if(isset($_REQUEST['end_date'])) {
					    
					} else {
					    $_REQUEST[end_date]= "N";
					}
															
				$sql_serch=" status='Y' ";
					if($_REQUEST[category])
					{
					    $sql_serch.=" and category ='$category'";
					}
					$now = date("Y-m-d");
					if($_REQUEST[end_date] == "Y")
					{
					    $sql_serch.=" and end_date < '$now'";
					}					
                    if($_REQUEST[end_date] == "N")
					{
					    $sql_serch.=" and end_date >= '$now'";
					}										
				
					if($_REQUEST[search_text])
					{
					    $search_text = $_REQUEST[search_text];
					    $sql_serch.=" and (lecture_info like '%$search_text%' or area like '%$search_text%'or instructor like '%$search_text%')";
					}				
				
				$sql="select count(lecture_id) as cnt from Gn_lecture where $sql_serch ";
				$result = mysql_query($sql) or die(mysql_error());
				$row=mysql_fetch_array($result);
				$intRowCount=$row[cnt];
              if($intRowCount)
              {
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
				  $order_status="desc"; 
				if($_REQUEST[order_name])
				  $order_name=$_REQUEST[order_name];
				else
				  $order_name="lecture_id";
				$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
				//$sql="select * from Gn_lecture where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
				$sql="select * from Gn_lecture where $sql_serch order by start_date desc limit $int,$intPageSize";
				$result=mysql_query($sql) or die(mysql_error());				
		?>   
              <?
                  while($row=mysql_fetch_array($result))
                  {
					  	//$num_arr=array();
						$sql_num="select * from Gn_event where m_id='$row[mem_id]' and event_idx='$row[event_idx]' ";
						$resul_num=mysql_query($sql_num);
						$crow=mysql_fetch_array($resul_num);
 
                  ?>
              <tr>
                <td></td>
                <td><?=$sort_no?></td>
                <td style="font-size:12px;"><?=$row[category]?></td>
                <td style="font-size:12px;"><?=$row[start_date]?>~<?=$row[end_date]?></td>
                <td style="font-size:12px;"><?=$row[lecture_day]?></td>
                <td style="font-size:12px;"><?=$row[lecture_start_time]?>~<?=$row[lecture_end_time]?></td>
                <td style="font-size:12px;"><?=$row[lecture_info]?></td>
                <td style="font-size:12px;"><?=$row[instructor]?></td>
                <td style="font-size:12px;"><?=$row[area]?></td>
                <td style="font-size:12px;"><?=$row[max_num]?>명</td>
                <td style="font-size:12px;"><?=$row[fee]==0?"무료":$row[fee]."원"?></td>
                <td style="font-size:12px;">
                    <input type="button" value="사용하기" class="button" onclick="useIt('<?php echo $row['lecture_id']?>','<?php echo $row['lecture_info']?>','<?php echo $row['instructor']?>','<?php echo $row['start_date']?>~<?php echo $row['end_date']?>','<?php echo $row['area']?>')">
                </td>
              </tr>
              <?
                    $sort_no--;
                  }
                  ?>	
                </tbody>	               
              <tr>
                <td colspan="14">
                        <?
                        page_ajax($page,$page2,$intPageCount,"pay_form");
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
            <!--
            <input type="button" value="랜딩 페이지 삭제" class="button">
            -->
            </div>
        </div>
        </form>
    </div>     
    
</div> 
<Script>
function useIt(lecture_id, lecture_info, instructor, date, area) {
    opener.$('#lecture_id').val(lecture_id);
    var info = lecture_info+"/"+instructor+"/"+date+"/"+area;
    opener.$('#lecture_info').val(info);
    //opener.$('#lecture_info').val(lecture_info);
    //opener.$('#instructor').val(instructor);
    //opener.$('#date').val(date);
    //opener.$('#area').val(area);

    window.close();
}    
    
$(function() {
    $('.copyLinkBtn').bind("click", function() {
        var trb = $(this).data("link");
        var IE=(document.all)?true:false;
        if (IE) {
            if(confirm("이 링크를 복사하시겠습니까?")) {
                window.clipboardData.setData("Text", trb);
            } 
        } else {
                temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", trb);
        }        
    });
    $('.switch').on("change", function() {
        var no = $(this).find("input[type=checkbox]").val();
        var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
		$.ajax({
			 type:"POST",
			 url:"mypage.proc.php",
			 data:{
				 mode:"land_updat_status",
				 landing_idx:no,
				 status:status,
				 },
			 success:function(data){
			    //alert('신청되었습니다.');location.reload();
			 }
			})    
			        
        //console.log($(this).find("input[type=checkbox]").is(":checked"));
        //console.log($(this).find("input[type=checkbox]").val());
    });
})
function viewEvent(str){
    window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
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

function removeRow(no) {
    if(confirm('삭제하시겠습니까?')) {
		$.ajax({
			 type:"POST",
			 url:"mypage.proc.php",
			 data:{
				 mode:"land_del",
				 landing_idx:no
				 },
			 success:function(data){
			    alert('삭제되었습니다.');
			    location.reload();
			    }
			})        
        
    }
}

function removeAll() {
    var no ="";
    if(confirm('삭제하시겠습니까?')) {
		$.ajax({
			 type:"POST",
			 url:"mypage.proc.php",
			 data:{
				 mode:"land_del",
				 landing_idx:no
				 },
			 success:function(data){
			    alert('삭제되었습니다.');
			    location.reload();
			    }
			})        
        
    }
}


</script>      