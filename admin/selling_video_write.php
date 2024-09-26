<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
if($no) {
    $query = "select * from gn_video where no='$no'";
    $res = mysqli_query($self_con, $query);
    $data = mysqli_fetch_array($res);
}
?>
<style>
    .box-body th {background:#ddd;}
    input[type="checkbox" i] {
        background-color: initial;
        cursor: default;
        -webkit-appearance: checkbox;
        box-sizing: border-box;
        margin: 3px 3px 3px 4px;
        padding: initial;
        border: initial;
    }
    input:checked + .slider {
        background-color: #2196F3;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }
    input:checked + .slider {
        background-color: #2196F3;
    }
    .slider.round {
        border-radius: 34px;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }
    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }
    .slider.round:before {
        border-radius: 50%;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto !important;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important;}
</style>

<div class="wrapper"  style="height:100%;overflow:auto">
    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>셀링 동영상 등록<small>셀링 동영상  정보를 등록합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">셀링 동영상 등록</li>
            </ol>
        </section>
        <form method="post" id="dForm" name="dForm" action="/admin/ajax/gn_video_ajax.php"  enctype="multipart/form-data">
            <input type="hidden" name="no" value="<?=$data['no']?>" />
            <input type="hidden" name="mode" value="<?=$data['no']?"updat":"creat"?>" />
            <input type="hidden" name="type" value="selling" />
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-header">
                          <h3 class="box-title">[셀링 동영상 상세정보]</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table id="detail1" class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="15%">
                                    <col width="80%">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>동영상제목</th>
                                        <td >
                                            <input type="text" style="width:250px;" name="title" id="title" value="<?=$data['title']?>" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>링크주소</th>
                                        <td> <input type="url" style="width:250px;" name="link" id="link" value="<?=$data['link']?>" > </td>
                                    </tr>
                                    <tr>
                                        <th>사용</th>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" class="chkagree" <?=$data['use_status']==1?"checked":""?> >
                                                <span class="slider round"></span>
                                            </label>
                                            <input type="hidden" name="use_status" id="use_status" value="<?=$data['use_status']?>" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>노출</th>
                                        <td> <input type="number" style="width:250px;" name="display" id="display" value="<?=$data['display']?>" > </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
                <div class="box-footer" style="text-align:center">
                    <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                    <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='selling_video_list.php';return false;"><i class="fa fa-list"></i> 목록</button>
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
$(function(){
    $('.chkagree').change(function() {
        if($(this)[0].checked){
            $("#use_status").val(1);
        }else{
            $("#use_status").val(0);
        }
    });
});
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