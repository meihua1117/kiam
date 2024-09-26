<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
if($no) {
    $query = "select * from gn_check_phone where no='$no'";
    $res = mysqli_query($self_con, $query);
    $data = mysqli_fetch_array($res);
}else{
    $data['api_key'] = generateRandomString(32);
    $data['main_price'] = 30000;
    $data['sub_price'] = 5;
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
            <h1>폰문자 인증 등록<small>폰문자 인증에 이용할 정보를 등록합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">폰문자 인증 등록</li>
            </ol>
        </section>
        <form method="post" id="dForm" name="dForm" action="/admin/ajax/phone_check.php"  enctype="multipart/form-data">
            <input type="hidden" name="no" value="<?=$data['no']?>" />
            <input type="hidden" name="mode" value="<?=$data['no']?"updat":"creat"?>" />
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-header">
                          <h3 class="box-title">[폰문자 인증 등록]</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table id="detail1" class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="15%">
                                    <col width="80%">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>아이디</th>
                                        <td >
                                            <input type="text" style="width:250px;" name="mem_id" id="mem_id" value="<?=$data['mem_id']?>" >
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>회사명</th>
                                        <td> <input type="text" style="width:250px;" name="company" id="company" value="<?=$data['company']?>" > </td>
                                    </tr>
                                    <tr>
                                        <th>담당자</th>
                                        <td> <input type="text" style="width:250px;" name="manager" id="manager" value="<?=$data['manager']?>" > </td>
                                    </tr>
                                    <tr>
                                        <th>적용도메인</th>
                                        <td> <input type="text" style="width:250px;" name="domain" id="domain" value="<?=$data['domain']?>" > </td>
                                    </tr>
                                    <tr>
                                        <th>기본료 설정</th>
                                        <td> <input type="text" style="width:250px;" name="main_price" id="main_price" value="<?=$data['main_price']?>" >원 </td>
                                    </tr>
                                    <tr>
                                        <th>추가료 설정</th>
                                        <td> <input type="text" style="width:250px;" name="sub_price" id="sub_price" value="<?=$data['sub_price']?>" >원 </td>
                                    </tr>
                                    <tr>
                                        <th>API KEY</th>
                                        <td> <input type="text" style="width:250px;" name="api_key" id="api_key" value="<?=$data['api_key']?>" readonly> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div><!-- /.col -->
                <div class="box-footer" style="text-align:center">
                    <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                    <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='phone_check_list.php';return false;"><i class="fa fa-list"></i> 목록</button>
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
