<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");
	$sql_no="select * from tjd_board where no='{$_REQUEST['no']}'";
	$resul_no=mysqli_query($self_con,$sql_no);
	$row_no=mysqli_fetch_array($resul_no);
	
	$sql_no="select * from Gn_Member where mem_id='{$row_no['id']}'";
	$resul_no=mysqli_query($self_con,$sql_no);
	$data=mysqli_fetch_array($resul_no);
		
	$phone = explode("-", $row_no['phone']);
	$email = explode("@", $row_no['email']);
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
var mem_id="";
function page_view(mem_id) {
    $('.ad_layer1').lightbox_me({
    	centered: true,
    	onLoad: function() {
    		$.ajax({
    			type:"POST",
    			url:"/admin/ajax/member_list_page1.php",
    			data:{mem_id:mem_id},
    			dataType: 'html',
    			success:function(data){
    				$('#phone_list').html(data);
    			},
    			error: function(){
    			  alert('로딩 실패');
    			}
    		});			    
    	}
    });
    $('.ad_layer1').css({"overflow-y":"auto", "height":"300px"});
}
$(function(){
});

//폰정보 수정
function modify_phone_info(){

	var phno = $("#pno").val();

	if(!phno){
		alert('폰 정보가 없습니다.');
		return false;
	}else{
		$.ajax({
			type:"POST",
			url:"ajax/modify_phoneinfo.php",
			data:{
				pno:phno,
				name:$("#detail_name").val(),
				company:$("#detail_company").val(),
				rate:$("#detail_rate").val()
			},
			success:function(data){
				location.reload();
			},
			error: function(){
			  alert('수정 실패');
			}
		});		
	}
}

//계정 삭제
function del_member_info(mem_code){

	var msg = confirm('정말로 삭제하시겠습니까?');

	if(msg){

			$.ajax({
				type:"POST",
				url:"/admin/ajax/user_leave.php",
				data:{mem_code:mem_code},
				success:function(){
					alert('삭제되었습니다.');
					location.reload();
				},
				error: function(){
				  alert('삭제 실패');
				}
			});		

	}else{
		return false;
	}
}

//주소록 다운
function excel_down_(){
	$("#excel_down_form").submit();
	return false;
}


function goPage(pgNum) {
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>";
}

//주소록 다운
function excel_down_p_group(pno,one_member_id){
	$($(".loading_div")[0]).show();
	$($(".loading_div")[0]).css('z-index',10000);
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yy = today.getFullYear().toString().substr(2,2);
	if(dd<10) {
		dd='0'+dd
	} 
	if(mm<10) {
		mm='0'+mm
	} 

	$.ajax({
	 type:"POST",
	 dataType : 'json',
	 url:"/ajax/ajax_session_admin.php",
	 data:{
			group_create_ok:"ok",
			group_create_ok_nums:pno,
			group_create_ok_name:pno.substr(3,8)+'_'+''+ mm+''+dd,
			one_member_id:one_member_id
		  },
	 success:function(data){
	 	$($(".loading_div")[0]).hide();
	 	$('#one_member_id').val(one_member_id);
	 	parent.excel_down('/excel_down/excel_down_.php?down_type=1&one_member_id='+one_member_id,data.idx);
	 }

	});	
}
</script>   
<style>
.loading_div {
    display:none;
    position: fixed;
    left: 50%;
    top: 50%;
    display: none;
    z-index: 1000;
}

td div {
    float:left;
}
</style>
    <div class="loading_div" ><img src="/images/ajax-loader.gif"></div>
    <div class="wrapper">

      <!-- Top 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>      
      
      <!-- Left 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            1:1 상담
            <small>1:1 상담을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">1:1 상담</li>
          </ol>
        </section>
        
        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
        <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />        
        <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />        
        <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />        
        </form>                

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12" style="padding-bottom:20px">
              <?php if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='notice_write.php'"><i class="fa fa-download"></i> 작성하기</button>
              <?php }?>
                         
              </div>            
          </div>
          
 
          
          <div class="row">
            
            <form name="board_write_form" id="board_write_form" action="" method="post">
                <input type="hidden" name="return_url" value="faq_list.php">
                <table id="example1" class="table table-bordered table-striped">
                <tr>
                <td>질문</td>
                <td style="width:90%"><input type="text" style="width:90%;" name="title" value="<?=$row_no['title']?>" required itemname='제목'  class="form-control input-sm"  /></td>
                </tr>
                <tr>
                <td>분류</td>
                <td>
                	<?
					foreach($fl_arr as $key=>$v)
					{
						$checked=$row_no['fl']==$key?"checked":"";
						?>
                        <label><input  type="radio" value="<?=$key?>" name="fl" <?=$checked?> /><?=$v?></label> &nbsp;
                        <?	
					}
					?>
                </td>
                </tr>                

                <tr>
                <td>연락처</td>
                <td>
                    <div>
                    <input type="text" name="mobile_1"  itemname='연락처' maxlength="4" style="width:70px;" value="<?=$phone[0]?>"  class="form-control input-sm" /> 
                    </div>
                    <div>
                    - 
                    </div>
                    <div>
                    <input type="text" name="mobile_2"  itemname='연락처' maxlength="4" style="width:70px;" value="<?=$phone[1]?>"  class="form-control input-sm"  />
                    </div>
                    <div>
                    - 
                    </div>
                    <div>
                    <input type="text" name="mobile_3" style="width:70px;"  itemname='연락처' maxlength="4" value="<?=$phone[2]?>"   class="form-control input-sm" />
                    </div>
        		</td>
                </tr>
                <tr>
                <td>이메일</td>
                <td>
                    <div>
                    <input type="text" name="email_1"  itemname='이메일' style="width:70px;" value="<?=$email[0]?>"  class="form-control input-sm"  /> 
                    </div>
                    <div>
                    @ 
                    </div>
                    <div>
                    <input type="text" name="email_2" id='email_2' itemname='이메일'  style="width:70px;" value="<?=$email[1]?>"  class="form-control input-sm"  />
                    </div>
                    <div>
                    <select name="email_3" itemname='이메일' onchange="inmail(this.value,'email_2')" style="background-color:#c8edfc;">
                    <?
                    foreach($email_arr as $key=>$v)
                    {
                    ?>
                    <option value="<?=$key?>"><?=$v?></option>                            
                    <?
                    }
                    ?>
                    </select>         
                    </div>
        		</td>
                </tr>
                <tr>
                <td>등록자정보</td>
                <td>
                    <div>
                        <?php echo $row_no['id'];?>
                        (<?php echo $data['mem_name'];?>)
                    </div>
        		</td>
                </tr>                
                <tr>
                <td>비밀글</td>
                <td><input type="checkbox" name="status_1" <?=$row_no['status_1']=="Y"?"checked":""?> /></td>
                </tr>
                <tr>
                	<td colspan="2" style="background-color:#FFF">
                	    <H2>질문</H2>
                	    <?=str_replace("<img src=\"","<img src=\"/", html_entity_decode($row_no['content']))?>
                	    <H2>답변</H2>
						<script language="javascript" src="/naver_editor/js/HuskyEZCreator.js" charset="utf-8"></script>
                        <textarea name="ir1" id="ir1" rows="10" cols="100" style="width:100%; height:200px; min-width:645px; display:none;"><?=$row_no['reply']?></textarea>
                        <script language="javascript" src="/naver_editor/js/naver_editor.js" charset="utf-8"></script>
                    </td>
                </tr>
                <tr>
                <td colspan="2" style="background-color:#FFF">
                    <iframe src="/upload.php?up_path=<?=$up_path?>&frm=board_write_form" name="upload_iframe" frameborder="0" width="100%" scrolling="no" height="0" style="margin:0; padding:0;" ></iframe>
                    <input type="hidden" name="board_write_form_img_hid" id="board_write_form_img_hid" value="<?=$row_no['adjunct_1']?>" />
                    <input type="hidden" name="board_write_form_img_hid_2" id="board_write_form_img_hid_2" value="<?=$row_no['adjunct_2']?>" />
                    <input type="hidden" itemname='이미지메모' name="board_write_form_memo_hid" id="board_write_form_memo_hid" value="<?=$row_no['adjunct_memo']?>" />
                    <input type="hidden" name="up_path" value="<?=$up_path?>" />                
                </td>
                </tr>                
                <tr>
                    <td colspan="2" style="text-align:right;">
                    	<a href="javascript:void(0)" onclick="board_save_reply(board_write_form,'<?=$row_no['no']?>','2')"><img src="/images/client_2_3.jpg" /></a>
                    	<a href="qna_list.php"><img src="/images/client_2_4.jpg" /></a>                        
                    </td>
                </tr>
                </table>
                </form>
          </div><!-- /.row -->
          
          

        
          
          
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />        
        <input type="hidden" name="one_member_id" id="one_member_id" value="" />        
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />                
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>	

<script language="javascript">
function changeLevel(mem_code) {
    var mem_leb = $('#mem_leb'+mem_code+" option:selected").val();
    var data = {"mode":"change","mem_code":"'+mem_code+'","mem_leb":"'+mem_leb+'"};
    $.ajax({
		type:"POST",
		url:"/admin/ajax/user_level_change.php",
		dataType:"json",
		data:{mode:"change",mem_code:mem_code,mem_leb:mem_leb},
		success:function(data){
		    //console.log(data);
		    //location = "/";
			//location.reload();
			alert('변경이 완료되었습니다.');
		},
		error: function(){
		  alert('초기화 실패');
		}
	});	
    
//    alert(mem_code);
}

function loginGo(mem_id, mem_pw, mem_code) {
    $('#one_id').val(mem_id);
    $('#mem_pass').val(mem_pw);
    $('#mem_code').val(mem_code);
    $('#login_form').submit();
}
</script>
      <!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      