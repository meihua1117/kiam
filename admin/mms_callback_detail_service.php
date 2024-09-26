<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");
if($idx) {
    // 가입 회원 상세 정보
    $query = "select * from gn_mms_callback where idx=$idx";
    $res = mysqli_query($self_con, $query);
    $data = mysqli_fetch_array($res);
}

?>
<style>
    .box-body th {background:#ddd;}
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto !important;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important;}
</style>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script> 

<link rel='stylesheet' id='jquery-ui-css'  href='//code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css?ver=4.5.2' type='text/css' media='all' />
<script src="http://code.jquery.com/ui/1.12.0-rc.2/jquery-ui.min.js" integrity="sha256-55Jz3pBCF8z9jBO1qQ7cIf0L+neuPTD1u7Ytzrp2dqo=" crossorigin="anonymous"></script>
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
            분양사 콜백 메시지관리
            <small>분양사 콜백 메시지를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">분양사 콜백 메시지관리</li>
          </ol>
        </section>

        <form method="post" id="dForm" name="dForm" action="/admin/ajax/mms_callback_save.php"  enctype="multipart/form-data">
        <input type="hidden" name="idx" value="<?=$data['idx']?>" />
        <input type="hidden" name="mode" value="servicesave" />
        <input type="hidden" name="isservice" value="isservice" />
        <!-- Main content -->
        <section class="content">
          <div class="row" id="toolbox">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">콜백 메시지 상세정보</h3>
                </div><!-- /.box-header -->                
                <div class="box-body">
                  <table id="detail1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="30%">
                      <col width="60%">
                     </colgroup>
                    <tbody>
                      <tr>
                        <th>메시지 타이틀</th>
                        <td>
                            <input type="text" style="width:400px;" name="title" id="title" value="<?=$data['title']?>" > 
                        </td>
                      </tr>
                      <tr>                      
                        <th>메시지 콘텐츠</th>
                        <td>
                            <textarea name="content" id="content"  style="width:550px;height:100px" ><?php echo $data['content'];?></textarea>
                        </td>
                      </tr>
                      <tr>
                        <th>이미지</th>
                        <td>
                            <input type="file" style="width:100px;" name="img" > 
                            <?php if($data['img']){?>
                                <?php echo $data['img']?>
                            <?php }?>
                        </td>
                      </tr>      
                      <tr>
                        <th>링크주소</th>
                        <td>
                            <input type="text" style="width:500px;" name="iam_link" id="iam_link" value="<?=$data['iam_link']?>" > 
                            <br>&nbsp;이 주소가 설정되지 않으면 이용자의 IAM주소를 가져옵니다.
                        </td>
                      </tr>                  
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="box-footer" style="text-align:center">
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='mms_callback_list_service.php';return false;"><i class="fa fa-list"></i> 목록</button>
            </div>            
          </div><!-- /.row -->          
        </section><!-- /.content -->
        </form>
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      
      </div><!-- /wrapper -->
<script language="javascript">
  $(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 235;
    if(height < 375)
        height = 375;
    console.log("height="+ height);
    $(".box-body").css("height",height);
  });
  function form_save() {
    if($('#title').val() == "") {
        alert('메시지 타이틀을 입력해주세요.');
        return;
    }
    if($('#content').val() == "") {
        alert('메시지 콘텐츠를 입력해주세요.');
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

</script>      