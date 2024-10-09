<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
if($no) {
    $query = "select * from gn_alert where no='$no'";
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
<div class="wrapper">
    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>셀링 기능알림 등록<small>셀링 기능별 알림정보를 등록합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">셀링 기능알림 등록</li>
            </ol>
        </section>
        <form method="post" id="dForm" name="dForm" action="/admin/ajax/gn_alert_ajax.php"  enctype="multipart/form-data">
            <input type="hidden" name="no" value="<?=$data['no']?>" />
            <input type="hidden" name="mode" value="<?=$data['no']?"updat":"creat"?>" />
            <input type="hidden" name="type" value="selling" />
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-header">
                          <h3 class="box-title">[셀링 기능 알림 상세정보]</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table id="detail1" class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="15%">
                                    <col width="80%">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>메뉴</th>
                                        <td >
                                            <input type="text" style="width:250px;" name="title" id="title" value="<?=$data['title']?>" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>위치</th>
                                        <td> <input type="text" style="width:250px;" name="pos" id="pos" value="<?=$data['pos']?>" > </td>
                                    </tr>
                                    <tr>
                                        <th>아이콘</th>
                                        <td ><input type="file" name="img">
                                            <?php if($data['img'] != "") {?>
                                                <img src="<?=$data['img']?>" style="width:120px">
                                            <?php }?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>설명글</th>
                                        <td>
                                            <textarea style="width:250px;" name="desc" id="desc"><?=$data['desc']?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>링크주소</th>
                                        <td> <input type="url" style="width:250px;" name="link" id="link" value="<?=$data['link']?>" > </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
                <div class="box-footer" style="text-align:center">
                    <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                    <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='selling_alert_list.php';return false;"><i class="fa fa-list"></i> 목록</button>
                </div>
            </section><!-- /.content -->
        </form>
    </div><!-- /content-wrapper -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
</div>
<script language="javascript">
function form_save() {
    $('#dForm').submit();
}
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 300;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
</script>