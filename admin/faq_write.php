<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$sql_no="select * from tjd_board where no='{$_REQUEST['no']}'";
$resul_no=mysqli_query($self_con,$sql_no);
$row_no=mysqli_fetch_array($resul_no);

$sql = "select * from tjd_board_category where category='4'";
$res_result = mysqli_query($self_con,$sql);
$data = mysqli_fetch_array($res_result);
$category = explode(",", $data['category_text']);
?>

<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
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
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 196;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
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
/*thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important}*/
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
            FAQ
            <small>FAQ을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">FAQ</li>
          </ol>
        </section>
        
        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
        <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />        
        <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />        
        <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />
        <input type="hidden" name="pop_yn" id="pop_yn" value="<?$row_no[pop_yn]?$row_no[pop_yn]:"N"?>" />
        <input type="hidden" name="important_yn" id="important_yn" value="<?$row_no[important_yn]?$row_no[important_yn]:"N"?>" />
        <input type="hidden" name="display_yn" id="display_yn" value="<?$row_no[display_yn]?$row_no[display_yn]:"N"?>" />        
        </form>                

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12" style="padding-bottom:20px">
              <?php if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
              <?php }?>
                         
              </div>            
          </div>
          
 
          
          <div class="row">
            
            <form name="board_write_form" id="board_write_form" action="" method="post">
                <input type="hidden" name="return_url" value="faq_list.php">
                <table id="example1" class="table table-bordered table-striped">
                <tr>
                <td>카테고리</td>
                    <td style="width:90%">
                    <select name="fl">
                        <?php foreach($category as $key => $value) {?>
                            <option value="<?php echo trim($value);?>" <?php echo $row_no['fl']==$value?"selected":""?>><?php echo trim($value);?></option>
                        <?php }?>
                    </select>
                    </td>
                </tr>                    
                <tr>
                <td>질문</td>
                <td style="width:90%"><input type="text" style="width:90%;" name="title" value="<?=$row_no['title']?>" required itemname='제목'  class="form-control input-sm"  /></td>
                </tr>
                <?
				if($_REQUEST['status']==2)
				{
					?>
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
                <?
				}
				if($_REQUEST['status']!=1)
				{
				?>
				<!--
                <tr>
                <td>연락처</td>
                <td>
                    <div>
                    <input type="text" name="mobile_1" required itemname='연락처' maxlength="4" style="width:70px;" value="<?=$phone[0]?>"  class="form-control input-sm" /> 
                    </div>
                    <div>
                    - 
                    </div>
                    <div>
                    <input type="text" name="mobile_2" required itemname='연락처' maxlength="4" style="width:70px;" value="<?=$phone[1]?>"  class="form-control input-sm"  />
                    </div>
                    <div>
                    - 
                    </div>
                    <div>
                    <input type="text" name="mobile_3" style="width:70px;" required itemname='연락처' maxlength="4" value="<?=$phone[2]?>"   class="form-control input-sm" />
                    </div>
        		</td>
                </tr>
                <tr>
                <td>이메일</td>
                <td>
                    <div>
                    <input type="text" name="email_1" required itemname='이메일' style="width:70px;" value="<?=$email[0]?>"  class="form-control input-sm"  /> 
                    </div>
                    <div>
                    @ 
                    </div>
                    <div>
                    <input type="text" name="email_2" id='email_2' itemname='이메일' required style="width:70px;" value="<?=$email[1]?>"  class="form-control input-sm"  />
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
                <td>비밀글</td>
                <td><input type="checkbox" name="status_1" <?=$row_no[status_1]=="Y"?"checked":""?> /></td>
                </tr>
                -->
                <?
				}
				?>                                                
                <tr>
                	<td colspan="2" style="background-color:#FFF">
                	    <H2>답변</H2>
						<script language="javascript" src="/naver_editor/js/HuskyEZCreator.js" charset="utf-8"></script>
                        <textarea name="ir1" id="ir1" rows="10" cols="100" style="width:100%; height:200px; min-width:645px; display:none;"><?=$row_no['content']?></textarea>
                        <script language="javascript" src="/naver_editor/js/naver_editor.js" charset="utf-8"></script>
                    </td>
                </tr>
                <tr>
                <td colspan="2" style="background-color:#FFF">
                    <iframe src="/upload.php?up_path=<?=$up_path?>&frm=board_write_form" name="upload_iframe" frameborder="0" width="100%" scrolling="no" height="0" style="margin:0; padding:0;display:none;" ></iframe>
                    <input type="hidden" name="board_write_form_img_hid" id="board_write_form_img_hid" value="<?=$row_no[adjunct_1]?>" />
                    <input type="hidden" name="board_write_form_img_hid_2" id="board_write_form_img_hid_2" value="<?=$row_no[adjunct_2]?>" />
                    <input type="hidden" itemname='이미지메모' name="board_write_form_memo_hid" id="board_write_form_memo_hid" value="<?=$row_no[adjunct_memo]?>" />
                    <input type="hidden" name="up_path" value="<?=$up_path?>" />                
                </td>
                </tr>                
                <tr>
                    <td colspan="2" style="text-align:right;">
                    	<a href="javascript:void(0)" onclick="board_save(board_write_form,'<?=$row_no['no']?>','4')"><img src="/images/client_2_3.jpg" /></a>
                    	<a href="faq_list.php"><img src="/images/client_2_4.jpg" /></a>                        
                    </td>
                </tr>
                </table>
                </form>
          </div><!-- /.row -->
          
          

        
          
          
        </section><!-- /.content -->
      </div><!-- /content-wrapper -->

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
<script type="text/javascript" src="/js/rlatjd_admin.js?m=<?php echo time();?>"></script>