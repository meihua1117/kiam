<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);

// 오늘날짜
$date_today=date("Y-m-d");

if($seq) {
    // 가입 회원 상세 정보
    $query = "select *
                from Gn_app_version where seq='$seq'";
    $res = mysql_query($query);
    $data = mysql_fetch_array($res);
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
            어플 버전 관리
            <small>어플버전 자동업로드 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">어플버전 상세정보 등록</li>
          </ol>
        </section>

        <form method="post" id="dForm" name="dForm" action="/admin/ajax/app_manage_save.php"  enctype="multipart/form-data">
        <input type="hidden" name="seq" value="<?=$data['seq']?>" />
        <input type="hidden" name="mode" value="save" />
        <!-- Main content -->
        <section class="content">
          <div class="row" id="toolbox">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">어플 버전 관리</h3>
                </div><!-- /.box-header -->                
                <div class="box-body">
                  <table id="detail1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="30%">
                      <col width="60%">
                     </colgroup>
                    <tbody>
                      <tr>
                        <th>어플 코드</th>
                        <td>
                            <input type="text" style="width:400px;" name="app_code" id="app_code" value="<?=$data['app_code']?>" > 
                        </td>
                      </tr>
                      <tr>                      
                        <th>어플 버전</th>
                        <td>
                            <textarea name="app_version" id="app_version"  style="width:550px;height:100px" ><?php echo $data['app_version'];?></textarea>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="box-footer" style="text-align:center">
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='app_manage_list.php';return false;"><i class="fa fa-list"></i> 목록</button>
            </div>            
          </div><!-- /.row -->          
        </section><!-- /.content -->
        </form>
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      

      </div><!-- /content-wrapper -->

      <!-- Footer -->
      
<script language="javascript">
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 232;
    if(height < 375)
        height = 375;
    console.log("height="+ height);
    $(".box-body").css("height",height);
});
function form_save() {
    if($('#title').val() == "") {
        alert('어플 코드를 입력해주세요.');
        return;
    }
    if($('#content').val() == "") {
        alert('어플 버전을 입력해주세요.');
        return;
    }
    $('#dForm').submit();
}    
</script>      