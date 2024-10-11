<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");
if($cam_id) {
    // 가입 회원 상세 정보
    $query = "select *
                from Gn_Ad_Manager where cam_id='$cam_id'";
    $res = mysqli_query($self_con,$query);
    $data = mysqli_fetch_array($res);
}

?>
<style>
    .box-body th {background:#ddd;}
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important}
</style>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script> 

<link rel='stylesheet' id='jquery-ui-css'  href='//code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css?ver=4.5.2' type='text/css' media='all' />
<script
  src="http://code.jquery.com/ui/1.12.0-rc.2/jquery-ui.min.js"
  integrity="sha256-55Jz3pBCF8z9jBO1qQ7cIf0L+neuPTD1u7Ytzrp2dqo="
  crossorigin="anonymous"></script>

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
            셀링솔루션 광고관리
            <small>셀링솔루션 광고를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">셀링솔루션 광고관리</li>
          </ol>
        </section>
        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post">
        <input type="hidden" name="one_id"   value="<?=$data['mem_id']?>" />        
        <input type="hidden" name="mem_pass" value="<?=$data['web_pwd']?>" />        
        <input type="hidden" name="mem_code" value="<?=$data['mem_code']?>" />        
        </form>

        <form method="post" id="dForm" name="dForm" action="/admin/ajax/ad_mo_save.php"  enctype="multipart/form-data">
        <input type="hidden" name="cam_id" value="<?=$data['cam_id']?>" />
        <input type="hidden" name="sendnum" value="<?=$_GET['sendnum']?>" />
        <input type="hidden" name="ad_position" value="H" />
        <!-- Main content -->
        <section class="content">
          <div class="row" id="toolbox">
            <div class="col-xs-12" style="padding-bottom:20px">
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="gotoLogin()"><i class="fa fa-user"></i> 회원페이지 로그인</button>
              셀링솔루션 광고정보 수정
              </div>            
          </div>
          <div class="row">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">광고상세정보</h3>
                </div><!-- /.box-header -->                
                <div class="box-body">
                  <table id="detail1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="20%">
                      <col width="20%">
                      <col width="60%">
                     </colgroup>
                    <tbody>
                      <tr>
                        <th rowspan="3">수주관리</th>
                        <th>광고주</th>
                        <td>
                            <input type="text" style="width:250px;" name="client" id="client" value="<?=$data['client']?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>의뢰일시</th>
                        <td>
                            <input type="text" style="width:100px;" name="start_date" id="start_date" value="<?=$data['start_date']?>" class="date"> 
                        </td>
                      </tr>
                      <tr>
                        <th>기간</th>
                        <td>
                            <input type="text" style="width:100px;" name="send_start_date" id="send_start_date" value="<?=$data['send_start_date']?>" > 
                             ~ 
                            <input type="text" style="width:100px;" name="send_end_date" id="send_end_date" value="<?=$data['send_end_date']?>" > 
                            
                        </td>
                      </tr>         
                                  
                      <tr>
                        <th rowspan="5">내용관리</th>
                        <th>광고제목</th>
                        <td>
                            <input type="text" style="width:400px;" name="title" id="title" value="<?=$data['title']?>" > 
                        </td>
                      </tr>                      
                      <tr>
                        <th>이동경로</th>
                        <td>
                            <input type="text" style="width:400px;" name="move_url" id="move_url" value="<?=$data['move_url']?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>광고이미지</th>
                        <td>
                            <input type="file" style="width:100px;" name="img" > 
                            <?php if($data['img_url']){?>
                                <?php echo $data['img_url']?>
                            <?php }?>
                        </td>
                      </tr>                      
                      <tr>
                        <th>노출순서</th>
                        <td>
                            <input type="text" style="width:100px;" name="display_order" id="display_order" value="<?=$data['display_order']?>" > 
                        </td>
                      </tr>         
                      <tr>
                        <th rowspan="3">상황관리</th>
                        <th>진행상태</th>
                        <td>
                            <select name="use_yn">
                                <option value="N" <?echo $data['use_yn']=="N"?"selected":""?>>미사용</option>
                                <option value="Y" <?echo $data['use_yn']=="Y"?"selected":""?>>사용</option>
                            </select>
                        </td>
                      </tr>                        
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="box-footer" style="text-align:center">
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='ad_mo_list.php';return false;"><i class="fa fa-list"></i> 목록</button>
            </div>            
          </div><!-- /.row -->          
        </section><!-- /.content -->
        </form>
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>   
      </div><!-- /content-wrapper -->
<script language="javascript">
function form_save() {
    if($('#title').val() == "") {
        alert('광고제목을 입력해주세요.');
        return;
    }
    $('#dForm').submit();
}    

$( ".date" ).datepicker({
 dateFormat: "yy-mm-dd",
 defaultDate: "+1w"
});

//시작일. 끝일보다는 적어야 되게끔
$( "#send_start_date" ).datepicker({
 dateFormat: "yy-mm-dd",
 defaultDate: "+1w",
 onClose: function( selectedDate ) {
  $("#send_start_date").datepicker( "option", "minDate", selectedDate );
 }
});
 
//끝일. 시작일보다는 길어야 되게끔
$( "#send_end_date" ).datepicker({
 dateFormat: "yy-mm-dd",
 defaultDate: "+1w",
 onClose: function( selectedDate ) {
  $("#send_end_date").datepicker( "option", "maxDate", selectedDate );
 }
});
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 347;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
</script>      
   