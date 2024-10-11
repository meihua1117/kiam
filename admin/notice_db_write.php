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
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
var mem_id=""; 

//주소록 다운
function excel_down_(){
	$("#excel_down_form").submit();
	return false;
}


function goPage(pgNum) {
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>";
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
            디버 공지사항
            <small>디버 공지사항을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">디버 공지사항</li>
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
                <input type="hidden" name="return_url" value="notice_db_list.php">
                <table id="example1" class="table table-bordered table-striped">
                <tr>
                <td>제목</td>
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
						<script language="javascript" src="/naver_editor/js/HuskyEZCreator.js" charset="utf-8"></script>
                        <textarea name="ir1" id="ir1" rows="10" cols="100" style="width:100%; height:200px; min-width:645px; display:none;"><?=$row_no['content']?></textarea>
                        <script language="javascript" src="/naver_editor/js/naver_editor.js" charset="utf-8"></script>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="background-color:#FFF">
                        <iframe src="/upload.php?up_path=<?=$up_path?>&frm=board_write_form" name="upload_iframe" frameborder="0" width="100%" scrolling="no" height="100" style="margin:0; padding:0;"></iframe>
                        <input type="hidden" name="board_write_form_img_hid" id="board_write_form_img_hid" value="<?=$row_no[adjunct_1]?>" />
                        <input type="hidden" name="board_write_form_img_hid_2" id="board_write_form_img_hid_2" value="<?=$row_no[adjunct_2]?>" />
                        <input type="hidden" itemname='이미지메모' name="board_write_form_memo_hid" id="board_write_form_memo_hid" value="<?=$row_no[adjunct_memo]?>" />
                        <input type="hidden" name="up_path" value="<?=$up_path?>" />                
                    </td>
                </tr>                
                <tr>
                    <td></td>
                    <td style="width:90%">
                        <input type="checkbox" name="popup_yn" id="popup_yn"  value="Y" > 팝업 
                        <input type="checkbox" name="important_yn" id="important_yn"  value="Y" > 중요 
                    </td>
                </tr>                
                <tr>
                    <td>노출 기간</td>
                    <td style="width:90%">
                        <input type="radio" name="display_yn" id="display_yn"  value="Y" > 항상 
                        <input type="radio" name="display_yn" id="display_yn"  value="T" > 항상 
                        <input type="text" name="start_date" id="start_date" value="" style="with:60px">
                        <input type="text" name="end_date"  id="end_date" value=""  style="with:60px">
                    </td>
                </tr>                                                         
                <tr>
                    <td colspan="2" style="text-align:right;">
                    	<a href="javascript:void(0)" onclick="board_save(board_write_form,'<?=$row_no['no']?>','6')"><img src="/images/client_2_3.jpg" /></a>
                    	<a href="notice_db_list.php"><img src="/images/client_2_4.jpg" /></a>                        
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
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                
<script language="javascript">
jQuery(function($){
 $.datepicker.regional['ko'] = {
  closeText: '닫기',
  prevText: '이전달',
  nextText: '다음달',
  currentText: 'X',
  monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)',
  '7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
  monthNamesShort: ['1월','2월','3월','4월','5월','6월',
  '7월','8월','9월','10월','11월','12월'],
  dayNames: ['일','월','화','수','목','금','토'],
  dayNamesShort: ['일','월','화','수','목','금','토'],
  dayNamesMin: ['일','월','화','수','목','금','토'],
  weekHeader: 'Wk',
  dateFormat: 'yy-mm-dd',
  firstDay: 0,
  isRTL: false,
  showMonthAfterYear: true,
  yearSuffix: ''};
 $.datepicker.setDefaults($.datepicker.regional['ko']);

    $('#start_date').datepicker({
        showOn: 'button',
  buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
  buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });
    $('#end_date').datepicker({
        showOn: 'button',
  buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
  buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });	
});
</script>      
      <!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      