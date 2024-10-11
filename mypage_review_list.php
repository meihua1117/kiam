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
$sql="select * from Gn_Member  where mem_id='".$_SESSION['one_member_id']."'";
$sresul_num=mysqli_query($self_con,$sql);
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
	
	$('#writeBtn').on("click",function() {
	   if($('#writeForm').css("display") == "none") {
	        $('#writeForm').css("display", "block");
	   } else {
	        $('#writeForm').css("display", "none");
	   }
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
.list_table th {background:#efefef;border-top:1px solid #000;border-bottom:1px solid #efefef;}
</style>

<div class="big_sub">
<?php include_once "mypage_step_navi.php";?>
   <div class="m_div">
       <?php include_once "mypage_left_menu.php";?>
       <div class="m_body">

        <form name="pay_form" action="" method="post" class="my_pay">

        <input type="hidden" name="page" value="<?=$page?>" />
        <input type="hidden" name="page2" value="<?=$page2?>" />        
        <div class="a1" style="margin-top:15px; margin-bottom:15px">
        <li style="float:left;">실시간 리뷰 보기</li>
        <li style="float:right;"></li>
        <p style="clear:both"></p>
        </div>
        <div>
            <div class="p1">
                
                <input type="radio" name="category" value="" checked>전체
                <input type="radio" name="category" value="강연">강연
                <input type="radio" name="category" value="교육">교육
                <input type="radio" name="category" value="영상">영상
                <input type="text" name="search_text" placeholder="" id="search_text" value="<?=$_REQUEST[search_text]?>"/> 
                <div style="float:right">
                    <input type="button" value="후기입력" class="button" id="writeBtn">
                </div>
            </div>
            <div id="writeForm" style="display:none">
                <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                <colgroup>
                    <col width="200px" />
                    <col width="*" />
                </colgroup>                
                <tr>
                    <th>평가</th>
                    <td style="text-align: left;height: 40px;border-bottom: 1px solid #CCC;border-top: 1px solid #CCC;background:#fff">
                    <input type="radio" name="score" value="5" checked>★★★★★
                    <input type="radio" name="score" value="4">★★★★
                    <input type="radio" name="score"  value="3">★★★
                    <input type="radio" name="score" value="2">★★
                    <input type="radio" name="score" value="1">★
                    </td>
                </tr>
                <tr>
                    <th>강의선택</th>
                    <td style="text-align: left;padding-left:10px;">
                        <input type="hidden" name="lecture_id" id="lecture_id" >
                        <input type="text" name="lecture_info" id="lecture_info" readonly>
                        <input type="button" value="강의선택" class="button" id="searchBtn">
                    </td>
                </tr>           
                <tr>
                    <th>리뷰내용</th>
                    <td style="text-align: left;padding-left:10px;">
                        <textarea name="content" id="content" style="width:600px;height:250px"></textarea>
                    </td>
                </tr>           
                <tr>
                    <th>자기소개</th>
                    <td style="text-align: left;padding-left:10px;"><input type="text" name="profile" id="profile"></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:right;">
                        <input type="button" value="취소" class="button" id="cancleBtn" >    
                        <input type="button" value="글올리기" class="button" id="saveBtn" >    
                    </td>
                </tr>            
                </table>
            </div>         
            </form>  
            <div>
            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
              <?

				$sql_serch=" a.mem_id ='{$_SESSION['one_member_id']}' ";
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
				
				$sql="select count(review_id) as cnt from Gn_review a where $sql_serch ";
				$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
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
				  $order_name="review_id";
				$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);     
				$sql="select * from Gn_review a 
				inner join Gn_lecture b
				       on  a.lecture_id = b.lecture_id
				where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
				$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));				
		?>   
              <?
                  while($row=mysqli_fetch_array($result))
                  {
                  ?>
                  <tr>
                    <td colspan="3" style="font-size:12px;text-align:left;"><?=$row[lecture_info]?>/<?=$row[instructor]?>/<?=$row[start_date]?>~<?=$row[end_date]?>/<?=$row[area]?></td>
                  </tr>
                  <tr>
                    <td colspan="3" style="font-size:12px;text-align:left;"><?=$row['content']?></td>
                  </tr>
                  <tr>
                    <td style="font-size:12px;text-align:left;"><?=$row['mem_name']?></td>
                    <td style="font-size:12px;text-align:left;"><?=$row['regdate']?></td>
                    <td style="font-size:12px;text-align:right;">
                        <input type="button" value="리뷰더보기" class="button" id="saveBtn" >    
                    </td>
                  </tr>
              <?
                    $sort_no--;
                  }
                  ?>		               
              <tr>
                <td colspan="14">
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
            <!--
            <input type="button" value="랜딩 페이지 삭제" class="button">
            -->
            </div>
        </div>
        </form>
    </div>     
</div> 
<Script>
function newpop(){
    var win = window.open("mypage_lecture_list_pop.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}    
$(function() {
    $('#searchBtn').on("click", function() {
        newpop();
    });
})    
   
$(function() {
    $('#saveBtn').on("click",function() {
        if($('#lecture_id').val() == "") {
            alert('강의를 선택해 주세요.');
            $('#lecture_id').focus();
            return;
        }
        
        if($('#content').val() == "") {
            alert('리뷰내용을 선택해 주세요.');
            $('#content').focus();
            return;
        }       
         
		$.ajax({
			 type:"POST",
			 url:"mypage.proc.php",
			 data:{
				 mode:"review_save",
				 score:$('input[name=score]:checked').val(),
				 lecture_id:$('#lecture_id').val(),
				 content:$('#content').val(),
				 profile:$('#profile').val()
			 },
			 success:function(data){
			    
			 }
		});
    });
    /*
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
    */
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