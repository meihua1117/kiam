<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    function goPage(pgNum) {
        location.href = '?<?=$nowPage?>&nowPage='+pgNum;
    }
    function save_key(index) {
        var key_type = $('#key_type'+index).val();
        var key_content = $('#key_content'+index).val();
        var key_id = $('#key_id'+index).val();
        if(!key_type){
            alert("검색어를 입력해주세요.");
            $('#key_type'+index).focus();
            return;
        }
        // if(!key_content){
        //     alert("검색어 콘텐츠를 입력해주세요.");
        //     $('#key_content'+index).focus();
        //     return;
        // }
        if(!key_id){
            alert("검색어 아이디를 입력해주세요.");
            $('#key_id'+index).focus();
            return;
        }
        $.ajax({
            type:"POST",
            url:"/ajax/ajax_key.php",
            data:{
                no:index,
                mode:"edit",
                key_type:key_type,
                key_content:key_content,
                key_id:key_id
            },
            success:function(data){
                alert('저장되었습니다.');
                location.reload();
            }
        })
    }
    function add_key(){
        $.ajax({
            type:"POST",
            url:"/ajax/ajax_key.php",
            data:{
                mode:"creat"
            },
            success:function(data){
                alert('추가되었습니다.');
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
        var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 232;
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
            <h1>플랫폼 관리자 입력 정보<small>플랫폼의 기능별 입력 정보를  관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">관리자입력정보</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px"></div>
            </div>
            <div class="row">
                <div class="box">
                    <div class="box-header">
                        <div class="col-xs-12" style="padding-bottom:0px">
                            <h3 class="box-title">플랫폼 관리자 입력정보 관리</h3>
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="add_key()"><i class="fa fa-download"></i> 검색어 추가</button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="detail1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="15%">
                                <col width="60%">
                                <col width="20%">
                                <col width="5%">
                            </colgroup>
                            <?
                            $sql = "select SQL_CALC_FOUND_ROWS * from Gn_Search_Key order by no";
                            $result = mysqli_query($self_con, $sql);
                            while($key_row=mysqli_fetch_array($result)){?>
                                <tr>
                                    <th>
                                        <input type="text" name="key_type" id="<?='key_type'.$key_row['no']?>" value="<?=$key_row['key_type']?>" style="width: 100%">
                                    </th>
                                    <td>
                                    <?if($key_row['key_id'] == 'daily_msg_contents' || $key_row['key_id'] == 'gwc_req_alarm' || $key_row['key_id'] == 'gpt_answer_example' || $key_row['key_id'] == 'gpt_question_example'){?>
                                        <textarea name="key_content" id="<?='key_content'.$key_row['no']?>" style="width:100%;height:100px;"><?=htmlspecialchars_decode($key_row['key_content'])?></textarea>
                                    <?}
                                    else{?>
                                        <input type="text" name="key_content" id="<?='key_content'.$key_row['no']?>" value="<?=$key_row['key_content']?>" style="width: 100%">
                                    <?}?>
                                    </td>
                                    <td>
                                        <input type="text" name="key_content" id="<?='key_id'.$key_row['no']?>" value="<?=$key_row['key_id']?>" style="width: 100%">
                                    </td>
                                    <th>
                                        <input type="button" onclick="save_key('<?=$key_row['no']?>')" name="key_save" id="<?='key_save'.$key_row['no']?>" value="저장" style="width: 100%">
                                    </th>
                                </tr>
                            <?}?>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            <!--div class="box-footer" style="text-align:center;">
                <button class="btn btn-primary" style="margin-right: 0px;" ><i class="fa fa-save"></i> 저장</button>
            </div-->
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div>
<!-- Footer -->