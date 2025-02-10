<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$sql_no="select * from tjd_board where no='{$_REQUEST['no']}'";
$resul_no=mysqli_query($self_con,$sql_no);
$row_no=mysqli_fetch_array($resul_no);
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script language="javascript" src="/js/rlatjd.js"></script>
<script language="javascript" src="/js/rlatjd_fun.js"></script>
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
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto !important;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important;}
</style>
<div class="loading_div" ><img src="/images/ajax-loader.gif"></div>
<div class="wrapper"  style="height:100%;overflow:auto">
    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header"  style="min-height : 80% !important">
                <h1>관리자 매뉴얼<small>관리자 매뉴얼을 관리합니다.</small></h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">관리자 매뉴얼</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row"  id="toolbox">
                    <div class="col-xs-12" style="padding-bottom:20px">
                        <? if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                        <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='manual_write.php'"><i class="fa fa-download"></i> 작성하기</button>
                        <?}?>
                    </div>
                </div>
                <div class="row">
                    <form name="board_write_form" id="board_write_form" action="" method="post">
                        <input type="hidden" name="return_url" value="admin_manual.php">
                        <?if(!$_REQUEST['status'] || $_REQUEST['status'] == 1){?>
                        <div style="position:relative;left:20px;">
                            <label><input type="radio" value="아이엠" name="fl" onclick="oncategorychange(1)" id="c_miam" <?=$row_no['fl']=="아이엠"?'checked':''?>>아이엠</label> &nbsp;
                            <label><input type="radio" value="폰문자" name="fl" onclick="oncategorychange(2)" id="c_ptext" <?=$row_no['fl']=='폰문자'?'checked':''?>>폰문자</label> &nbsp;
                            <label><input type="radio" value="디비수집" name="fl" onclick="oncategorychange(3)" id="c_getdb" <?=$row_no['fl']=='디비수집'?'checked':''?>>디비수집</label> &nbsp;
                            <label><input type="radio" value="콜백문자" name="fl" onclick="oncategorychange(4)" id="c_cbtext" <?=$row_no['fl']=='콜백문자'?'checked':''?>>콜백문자</label> &nbsp;
                            <label><input type="radio" value="퍼널문자" name="fl" onclick="oncategorychange(5)" id="c_steptext" <?=$row_no['fl']=='퍼널문자'?'checked':''?>>퍼널문자</label> &nbsp;
                            <label><input type="radio" value="웹문자" name="fl" onclick="oncategorychange(6)" id="c_webtext" <?=$row_no['fl']=='웹문자'?'checked':''?>>웹문자</label> &nbsp;
                            <label><input type="radio" value="국제문자" name="fl" onclick="oncategorychange(7)" id="c_intertext" <?=$row_no['fl']=='국제문자'?'checked':''?>>국제문자</label> &nbsp;
                            <label><input type="radio" value="결제" name="fl" onclick="oncategorychange(8)" id="c_settle" <?=$row_no['fl']=='결제'?'checked':''?>>결제</label> &nbsp;
                            <label><input type="radio" value="사업" name="fl" onclick="oncategorychange(9)" id="c_working" <?=$row_no['fl']=='사업'?'checked':''?>>사업</label> &nbsp;
                            <label><input type="radio" value="마케팅" name="fl" onclick="oncategorychange(10)" id="c_marketing" <?=$row_no['fl']=='마케팅'?'checked':''?>>마케팅</label> &nbsp;
                            <label><input type="radio" value="디비테이블" name="fl" onclick="oncategorychange(11)" id="c_dbtable" <?=$row_no['fl']=='디비테이블'?'checked':''?>>디비테이블</label> &nbsp;
                            <label><input type="radio" value="카페24" name="fl" onclick="oncategorychange(12)" id="c_cafe24" <?=$row_no['fl']=='카페24'?'checked':''?>>카페24</label> &nbsp;
                            <label><input type="radio" value="서버" name="fl" onclick="oncategorychange(13)" id="c_server" <?=$row_no['fl']=='서버'?'checked':''?>>서버</label> &nbsp;
                            <label><input type="radio" value="기타" name="fl" onclick="oncategorychange(14)" id="c_other" <?=$row_no['fl']=='기타'?'checked':''?>>기타</label> &nbsp;
                        </div>
                        <?}?>
                        <table id="example1" class="table table-bordered table-striped">
                            <tr>
                                <td>제목</td>
                                <td style="width:90%"><input type="text" style="width:90%;" name="title" value="<?=$row_no['title']?>" required itemname='제목'  class="form-control input-sm"  /></td>
                            </tr>
                            <?if($_REQUEST['status']==2){?>
                            <tr>
                                <td>분류</td>
                                <td>
                                <?foreach($fl_arr as $key=>$v){
                                    $checked=$row_no['fl']==$key?"checked":"";
                                ?>
                                <label><input  type="radio" value="<?=$key?>" name="fl" <?=$checked?> /><?=$v?></label> &nbsp;
                                <?}?>
                                </td>
                            </tr>
                            <?}?>
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
                                    <input type="hidden" name="board_write_form_img_hid" id="board_write_form_img_hid" value="<?=$row_no['adjunct_1']?>" />
                                    <input type="hidden" name="board_write_form_img_hid_2" id="board_write_form_img_hid_2" value="<?=$row_no['adjunct_2']?>" />
                                    <input type="hidden" itemname='이미지메모' name="board_write_form_memo_hid" id="board_write_form_memo_hid" value="<?=$row_no['adjunct_memo']?>" />
                                    <input type="hidden" name="up_path" value="<?=$up_path?>" />
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td style="width:90%">
                                <input type="checkbox" name="important_yn" id="important_yn"  value="Y" <?=$row_no['important_yn']=='Y'?'checked':''?>> 중요
                                    <input type="checkbox" name="popup_yn" id="popup_yn"  value="Y" <?=$row_no['pop_yn'] == 'Y'?'checked':''?>> 팝업
                                </td>
                            </tr>
                            <tr>
                                <td>노출 기간</td>
                                <td style="width:90%">
                                    <input type="radio" name="display_al" id="display_al"  value="Y" <?=$row_no['display_yn']=='Y'?'checked':''?>> 항상
                                    <input type="radio" name="display_al" id="display_tr"  value="T" <?=$row_no['display_yn']=='T'?'checked':''?>> 기간
                                    <input type="date" name="start_date" id="start_date" value="" style="with:60px">
                                    <input type="date" name="end_date"  id="end_date" value=""  style="with:60px">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:right;">
                                    <a href="javascript:void(0)" onclick="board_save(board_write_form,'<?=$row_no['no']?>','5')"><img src="/images/client_2_3.jpg" /></a>
                                    <a href="admin_manual.php"><img src="/images/client_2_4.jpg" /></a>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div><!-- /.row -->
            </section><!-- /.content -->
        </div><!-- /content-wrapper -->
        <!-- Footer -->
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>  
        <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
            <input type="hidden" name="grp_id" value="" />
            <input type="hidden" name="box_text" value="" />
            <input type="hidden" name="one_member_id" id="one_member_id" value="" />
            <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
        </form>
        <iframe name="excel_iframe" style="display:none;"></iframe>
    </div>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                
<script language="javascript">
function oncategorychange(i){
    var a = ['c_miam','c_ptext','c_getdb','c_cbtext','c_steptext','c_webtext','c_intertext','c_settle','c_working','c_marketing','c_dbtable','c_cafe24','c_server','c_other'];
    for(var index = 0; index < 14; index++){
        if(index != i-1)
            document.getElementById(a[index]).checked = false;
    }
}
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 51 - 45 - 164;
    if(height < 375)
        height = 375;
    console.log("height="+ height);
    $(".board_write_form").css("height",height);
});
</script>      
    