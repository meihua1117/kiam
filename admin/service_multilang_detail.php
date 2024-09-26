<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
if($idx) {
    // 상세 정보
    $query = "select * from Gn_Iam_multilang where no='$idx'";
    $res = mysqli_query($self_con, $query);
    $data = mysqli_fetch_array($res);
}
?>
<style>
    .box-body th {background:#ddd;}
</style>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script> 
<!--link rel='stylesheet' id='jquery-ui-css'  href='//code.jquery.com/ui/1.11.4/themes/ui-lightness/jquery-ui.css?ver=4.5.2' type='text/css' media='all' /-->
<div class="wrapper">
    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>다국어IAM관리<small>아이엠 다국어메뉴를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">다국어IAM관리</li>
            </ol>
        </section>
        <form method="post" id="dForm" name="dForm" action="/admin/ajax/Iam_language_save.php"  enctype="multipart/form-data">
            <input type="hidden" name="idx" value="<?=$data['no']?>" />
            <input type="hidden" name="mode" value="<?=$data['no']?"updat":"creat"?>" />
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">[아이엠다국어정보]</h3>
                            <h3 class="box-title" style="margin:0px 0px 0px 500px;">[아이엠메뉴정보]</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <table id="detail1" class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="15%">
                                    <col width="35%">
                                    <col width="15%">
                                    <col width="35%">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <th>한국어</th>
                                        <td > <input type="text" style="width:250px;" name="korean" id="korean" value="<?=$data['korean']?>" >  </td>
                                        <th>프로필</th>
                                        <td >  <input type="text" style="width:250px;" name="profile_menu" id="profile_menu" value="<?=$data['profile_menu']?>" >  </td>
                                    </tr>
                                    <tr>
                                        <th>영어</th>
                                        <td> <input type="text" style="width:250px;" name="english" id="english" value="<?=$data['english']?>" > </td>
                                        <th>스토리</th>
                                        <td> <input type="text" style="width:250px;" name="story_menu" id="story_menu" value="<?=$data['story_menu']?>" > </td>
                                    </tr>
                                    <tr>
                                        <th>중국어</th>
                                        <td> <input type="text" style="width:250px;" name="china" id="china" value="<?=$data['china']?>" >  </td>
                                        <th>연락처</th>
                                        <td> <input type="text" style="width:250px;" name="contact_menu" id="contact_menu" value="<?=$data['contact_menu']?>" >   </td>
                                    </tr>
                                    <tr>
                                        <th>일본어</th>
                                        <td><input type="text" style="width:250px;" name="japan" id="japan" value="<?=$data['japan']?>" >  </td>
                                        <th>프렌즈</th>
                                        <td><input type="text" style="width:250px;" name="friends_menu" id="friends_menu" value="<?=$data['friends_menu']?>" >  </td>
                                    </tr>
                                    <tr>
                                        <th>힌디어</th>
                                        <td> <input type="text" style="width:250px;" name="india" id="india" value="<?=$data['india']?>" > </td>
                                        <th>관리자</th>
                                        <td><input type="text" style="width:250px;" name="manager" id="manager" value="<?=$data['manager']?>" >  </td>

                                    </tr>
                                    <tr>
                                        <th>프랑스어</th>
                                        <td> <input type="text" style="width:250px;" name="france" id="france" value="<?=$data['france']?>" >  </td>
                                        <th></th>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                    <div class="box-footer" style="text-align:center">
                        <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                        <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='service_Iam_list.php';return false;"><i class="fa fa-list"></i> 목록</button>
                    </div>
                </div><!-- /.row -->
            </section><!-- /.content -->
        </form>
    </div><!-- /content-wrapper -->
<script language="javascript">
    function form_save() {
        $.ajax({
            type: "POST",
            url: "ajax/check_id_exist.php",
            dataType: "json",
            data:{"mem_id" : $('#mem_id').val()},
            success: function (data) {
                if (data.count == 0) {
                alert("아이디를 정확히 입력하세요");
                }
                else{
                if($('#main_domain').val() == "") {
                alert('도메인을 입력해주세요.');
                return;
                }
                else if($('#sub_domain').val() == "") {
                alert('보조도메인을 입력해주세요.');
                return;
                }
                else
                $('#dForm').submit();
                }
            },
                error: function () {
                //alert('초기화 실패');
            }
        });
    }
</script>
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>