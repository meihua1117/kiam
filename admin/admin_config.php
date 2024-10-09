<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
$sql="select * from gn_conf";
$result = mysql_query($sql) or die(mysql_error());
$row=mysql_fetch_array($result);
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    function saveConfig() {
        var phone_id = $('#phone_id').val();
        var phone_num = $('#phone_num').val();
        var web_phone = $('#web_phone').val();
        if(!phone_id){
            alert("발송폰 아이디를 입력해 주세요.");
            $('#phone_id').focus();
            return;
        }
        if(!phone_num){
            alert("발송폰번호를 입력해 주세요.");
            $('#phone_num').focus();
            return;
        }
        if(!web_phone){
            alert("웹문자폰번호를 입력해 주세요.");
            $('#web_phone').focus();
            return;
        }
        $.ajax({
            type:"POST",
            url:"/admin/ajax/save_config.php",
            data:{
                phone_id:phone_id,
                phone_num:phone_num,
                web_phone:web_phone,
            },
            success:function(data){
                alert('저장되었습니다.');
                location.reload();
            }
        })
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
<style>
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
            <h1>환경 설정<small>환경 설정을  진행합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">환경 설정 관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12" style="padding-bottom:20px"></div>
            </div>
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered">
                            <colgroup>
                                <col width="15%">
                                <col width="60%">
                            </colgroup>
                            <tbody class="text-center">
                                <tr>
                                    <td>발송폰 소유자 아이디</td>
                                    <td class="text-left">
                                        <input type="text" name="phone_id" id="phone_id" value="<?=$row[phone_id]?>" style="width: 200px">
                                    </td>
                                </tr>
                                <tr>
                                    <td>발송폰 번호</td>
                                    <td class="text-left">
                                        <input type="text" name="phone_num" id="phone_num" value="<?=$row[phone_num]?>" style="width: 200px">
                                    </td>
                                </tr>     
                                <tr>
                                    <td>웹문자폰 번호</td>
                                    <td class="text-left">
                                        <input type="text" name="web_phone" id="web_phone" value="<?=$row[web_phone]?>" style="width: 200px">
                                    </td>
                                </tr>                            
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            <div style="text-align:center;">
                <button class="btn btn-primary" style="margin-right: 5px;width: 100px" onclick="saveConfig()"><i class="fa fa-download"></i> 저 장</button>
            </div>
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <iframe name="excel_iframe" style="display:none;"></iframe>
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      
</div>
<!-- Footer -->