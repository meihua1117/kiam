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
.wrapper{height:100%;overflow:auto;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important}
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
                <h1>공지사항<small>공지사항을 관리합니다.</small></h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">공지사항</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row" id="toolbox">
                    <div class="col-xs-12" style="padding-bottom:20px">
                        <? if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                        <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='notice_write.php'"><i class="fa fa-download"></i> 작성하기</button>
                        <?}?>
                    </div>
                </div>
                <div class="box-body">
                    <form name="board_write_form" id="board_write_form" action="" method="post">
                        <input type="hidden" name="return_url" value="notice_list.php">
                        <?if(!$_REQUEST['status'] || $_REQUEST['status'] == 1){?>
                        <div style="position:relative;left:20px;">
                            <label><input type="radio" value="전체" name="fl" onclick="oncategorychange(1)" id="c_all" <?=$row_no['fl']=="전체"?'checked':''?>>전체</label> &nbsp;
                            <label><input type="radio" value="문자" name="fl" onclick="oncategorychange(2)" id="c_text" <?=$row_no['fl']=='문자'?'checked':''?>>문자</label> &nbsp;
                            <label><input type="radio" value="디버" name="fl" onclick="oncategorychange(3)" id="c_diver" <?=$row_no['fl']=='디버'?'checked':''?>>디버</label> &nbsp;
                            <label><input type="radio" value="윈스텝" name="fl" onclick="oncategorychange(4)" id="c_winstep" <?=$row_no['fl']=='윈스텝'?'checked':''?>>윈스텝</label> &nbsp;
                            <label><input type="radio" value="아이엠" name="fl" onclick="oncategorychange(5)" id="c_iam" <?=$row_no['fl']=='아이엠'?'checked':''?>>아이엠</label> &nbsp;
                            <label><input type="radio" value="쇼핑" name="fl" onclick="oncategorychange(6)" id="c_shopping" <?=$row_no['fl']=='쇼핑'?'checked':''?>>쇼핑</label> &nbsp;
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
                                    <a href="javascript:void(0)" onclick="board_save(board_write_form,'<?=$row_no['no']?>','1')"><img src="/images/client_2_3.jpg" /></a>
                                    <a href="notice_list.php"><img src="/images/client_2_4.jpg" /></a>
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
<!--link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script-->                
<script language="javascript">
function oncategorychange(i){
    var a = ['c_all','c_text','c_diver','c_winstep','c_iam','c_shopping'];
    for(var index = 0; index < 5; index++){
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
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 227;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
</script>      
     