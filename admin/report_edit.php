<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
$repo = 0;
if(isset($_GET["repo"]))
    $repo  = $_GET["repo"];
if($repo != 0){
    $sql = "select * from gn_report_form where id = $repo";
    $res = mysqli_query($self_con, $sql);
    $row_form = mysqli_fetch_array($res);
    if($row_form['request_yn'] == 'Y'){
        $erq_sql = "select * from Gn_event_request where request_idx = {$row_form['pcode']}";
        $erq_res = mysqli_query($self_con, $erq_sql);
        $erq_row = mysqli_fetch_array($erq_res);
    }
}
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script src="/iam/js/jquery-ui-1.10.3.custom.js"></script>
<script src="/iam/js/jquery-signature.js"></script>
<script src="/iam/js/jquery.report_form.js"></script>
<style>
.loading_div {
    display:none;
    position: fixed;
    left: 50%;
    top: 50%;
    display: none;
    z-index: 1000;
}
#open_recv_div li{list-style: none;}
.key{
    background-color: #bfbfbf !important;
    color: white !important;
}
.m-signature-pad{
    height: 130px;
    padding: 12px 0 0;
    margin: 7px 0 12px;
    position: relative;
}
.marb3 {
    margin-bottom: 3px !important;
}
.kbw-signature {
    width: 100%;
    height: 100px;
    background-color: #f1f1f1;
    display: inline-block;
    border: 1px solid #eee;
    -ms-touch-action: none;
}
input:checked + .slider {
    background-color: #2196F3;
}
.switch_repo_status {
    position: relative;
    display: inline-block;
    width: 55px;
    height: 28px;
    text-align: center;
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
    height: 20px;
    width: 20px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;

}
.form-wrap {
    padding: 15px 10px;
    background-color: #fff;
    border: 1px solid #eee;
}
.attr-row {
    display: table;
    width: 100%;
    margin-bottom: 10px;
}
.attr-name {
    width: 80px;
    font-size: 14px;
    font-weight: 500;
    display: table-cell;
    vertical-align: middle;
}
.attr-value {
    width: -webkit-calc(100% - 72px);
    width: calc(100% - 72px);
    padding-left: 5px;
    display: table-cell;
    vertical-align: middle;
}
.form-wrap .attr-row .attr-value .input {
    width: 100%;
    height: 28px;
    padding: 5px 10px;
    background-color: #fff;
    border: 1px solid #ccc;
    font-size: 12px;
    line-height: 16px;
}
.input-field {
    margin-top: 10px;
    margin-bottom: 30px;
    display: block;
    border: 1px solid #eee;
    padding: 15px 15px 0 15px;
}
.input-field .title {
    position: relative;
    padding: 0px 20px;
    border-radius: 15px;
    font-size: 16px;
    font-weight: 500;
    line-height: 20px;
}
.button-wrap {
    margin-top: 30px;
    font-size: 0;
    text-align: center;
}
.button {
    display: inline-block;
    width: 120px;
    margin: 0 5px;
    padding: 5px 0;
    color: #fff;
    font-size: 16px;
    font-weight: 500;
    line-height: 20px;
    text-align: center;
}
.is-pink {
    background-color: #ff0066;
}
.is-grey {
    background-color: #bbb;
}
.button_delete
{
    padding: 3px;
    border: 1px solid #bfbfbf;
    cursor: pointer
}
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
            <h1>리포트포맷 수정<small>리포트포맷을 수정합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">리포트포맷 수정</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="input-field">
            <label class="title">리포트 타이틀정보 입력</label>
            <div class="form-wrap">
                <div class="attr-row">
                    <div class="attr-name">타이틀</div>
                    <div class="attr-value">
                        <div class="input-wrap">
                            <input type="text" name="repo_title" id="repo_title" class="input" placeholder="리포트 타이틀 입력" value="<?=$row_form['title']?>">
                            <input type="hidden" value="<?=$repo?>" name="repo_index">
                        </div>
                    </div>
                </div>
                <div class="attr-row">
                    <div class="attr-name">설명글</div>
                    <div class="attr-value">
                        <div class="input-wrap">
                            <textarea name="repo_desc" id="repo_desc" class="input" style="height: 60px;" wrap="hard" placeholder="설명글 입력"><?=$row_form['descript']?></textarea>
                        </div>
                    </div>
                </div>
                <div class="attr-row">
                    <div class="attr-name" style="width: 100px">신청창 자동 삽입</div>
                    <div class="attr-value">
                        <div class="input-wrap">
                            <input type="radio" name="request_yn" id="request_y" value="Y" <?php echo $row_form['request_yn']=="Y"?"checked":""?>>사용
                            <input type="radio" name="request_yn" id="request_n" value="N" <?php echo $row_form['request_yn']=="N"||$row_form['request_yn']==""?"checked":""?>>사용 안함
                            <input type="text" class="input" name="event_name_eng" placeholder="" id="event_name_eng" value="<?=$erq_row['event_code']?>" readonly style="width:100px"/>
                            <input type="hidden" name="request_idx" id="request_idx" value="<?=$row_form['pcode'];?>" />
                            <input type="hidden" name="org_event_code" value="<?=$erq_row['sp'];?>" />
                            <input type="hidden" name="event_idx" id="event_idx" value="<?=$erq_row['event_idx'];?>" />
                            <input type="hidden" name="step_num" id="step_num" value="<?=$erq_row['sms_step_num']?>" />
                            <input type="hidden" name="sms_idx" id="sms_idx" value="" />
                            <input type="hidden" name="event_code" id="event_code" value="<?=$erq_row['event_code']?>" />
                            <input type="hidden" name="pcode" id="pcode" value="<?=$erq_row['pcode']?>" />
                            <input type="hidden" name="event_name_eng_event" id="event_name_eng_event" value="" />
                            <input type="button" value="신청창키워드조회" class="button"  style="padding: 5px 10px;font-size: 12px" id="searchBtn">
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
        <section class="input-field repo_format">
            <label class="title">리포트 포맷 선택</label>
            <?if($repo != 0){
                $sql = "select * from gn_report_form1 where form_id = $repo order by item_order";
                $res = mysqli_query($self_con, $sql);
                while($row = mysqli_fetch_array($res)){
                    ?>
                    <div class="form-wrap" data-index="<?=$row['item_order']?>">
                        <input type="radio" name="item_type_<?=$row['item_order']?>"  value="0" onclick="chk_item_type(this,0);" <?=$row['item_type']==0?"checked":"";?>>
                        <label >제시형</label>
                        <input type="radio" name="item_type_<?=$row['item_order']?>"  value="1"  onclick="chk_item_type(this,1);" <?=$row['item_type']==1?"checked":"";?>>
                        <label >설문형</label>
                        <input type="radio" name="item_type_<?=$row['item_order']?>"  value="3"  onclick="chk_item_type(this,3);" <?=$row['item_type']==3?"checked":"";?>>
                        <label >입력형</label>
                        <input type="radio" name="item_type_<?=$row['item_order']?>"  value="2"  onclick="chk_item_type(this,2);" <?=$row['item_type']==2?"checked":"";?>>
                        <label >설명형</label>
                        <div class="attr-row" style="margin-top: 5px">
                            <div class="attr-name">항목명</div>
                            <div class="attr-value">
                                <div class="input-wrap">
                                    <input type="text" name="repo_item" class="input" placeholder="항목 입력" value="<?=$row['item_title']?>">
                                </div>
                            </div>
                        </div>

                        <div class="attr-row repo_req" style="margin-top: 5px;">
                            <div class="attr-name">질문</div>
                            <div class="attr-value">
                                <div class="input-wrap">
                                    <input type="text" name="repo_req" id="repo_req" class="input" placeholder="질문내용 입력" value="<?=$row['item_req']?>">
                                </div>
                            </div>
                        </div>
                        <div class="jesi-content">
                            <?
                            $repo_sql = "select * from gn_report_form2 where form_id=$repo and item_id='{$row['id']}' order by id";
                            $repo_res = mysqli_query($self_con, $repo_sql);
                            while($repo_row = mysqli_fetch_array($repo_res)){
                                if($row['item_type'] == 0){?>
                                    <div class="attr-row" style="margin-top: 5px">
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="text" name="repo_item_key" class="input key" placeholder="제시어 입력(예시:이름)" value="<?=$repo_row['tag_name']?>">
                                            </div>
                                        </div>
                                        <div class="attr-value">
                                            <div class="input-wrap" style="display: flex">
                                                <input type="text" name="repo_item_value" class="input" placeholder="" value="">
                                                <input type="button" value="삭제" onclick="jesi_del(this)" class="button_delete">
                                            </div>
                                        </div>
                                    </div>
                                <?}else if($row['item_type'] == 1){?>
                                    <div class="attr-row" style="margin-top: 5px">
                                        <div class="attr-value">
                                            <div class="input-wrap" style="display: flex">
                                                <input type="text" name="repo_item_key" class="input" placeholder="옵션" value="<?=$repo_row['tag_name']?>">
                                                <input type="button" value="삭제" onclick="jesi_del(this)" class="button_delete">
                                            </div>
                                        </div>
                                    </div>
                                <?}else if($row['item_type'] == 3){?>
                                    <div class="attr-row" style="margin-top: 5px">
                                        <div class="attr-value" style="display: block;width: 100%">
                                            <div class="input-wrap">
                                                <input type="text" name="repo_item_key" class="input key" placeholder="세부질문내용 입력" value="<?=$repo_row['tag_name']?>">
                                            </div>
                                        </div>
                                        <div class="attr-value" style="display: block;width: 100%;margin-top: 5px">
                                            <div class="input-wrap" style="display: flex">
                                                <input type="text" name="repo_item_value" class="input" placeholder="" value="">
                                                <input type="button" value="삭제" onclick="jesi_del(this)" class="button_delete">
                                            </div>
                                        </div>
                                    </div>
                                <?}else{?>
                                    <div class="attr-row" style="margin-top: 5px">
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <textarea class="input" placeholder="내용 입력"  style='height: 200px'><?=$repo_row['tag_name']?></textarea>
                                                <div style='display: flex'>
                                                    <input type="text" name="repo_item_value" class="input" placeholder="링크주소 입력 http://" value="<?=$repo_row['tag_link']?>">
                                                    <input type="file" name="repo_item_file" class="input" style="padding:2px;width:33%" accept=".jpg,.jpeg,.png,.gif,.svc" onchange="uploadImage(this)">
                                                    <input type="button" value="삭제" onclick="jesi_del(this)" class="button_delete">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?}
                            }?>
                        </div>
                        <div style="text-align: center;margin-top: 5px">
                            <input type="button" onclick = "jesi_add(this);" value="추가하기">
                        </div>
                        <div style="text-align: right;margin-top: 5px">
                            <a class="content-utils" onclick="item_up(this)">
                                <img src="/iam/img/main/icon-up.png"  style="width:20px;height:20px;cursor: pointer">
                            </a>
                            <a class="content-utils" onclick="item_down(this)">
                                <img src="/iam/img/main/icon-down.png"  style="width:20px;height:20px;cursor: pointer">
                            </a>
                            <a class="content-utils" onclick="clone_item(this)">
                                <img src="/iam/img/main/icon-clone.png"  style="width:20px;height:20px;cursor: pointer">
                            </a>
                            <a class="content-utils" onclick="del_item(this)">
                                <img src="/iam/img/main/icon-trash.png"  style="width:20px;height:20px;cursor: pointer">
                            </a>
                        </div>
                    </div>
                <?    }
            }else{?>
                <div class="form-wrap" data-index="0">
                    <input type="radio" name="item_type_0"  value="0" checked  onclick="chk_item_type(this,0);">
                    <label >제시형</label>
                    <input type="radio" name="item_type_0"  value="1"  onclick="chk_item_type(this,1);">
                    <label >설문형</label>
                    <input type="radio" name="item_type_0"  value="3"  onclick="chk_item_type(this,3);">
                    <label >입력형</label>
                    <input type="radio" name="item_type_0"  value="2"  onclick="chk_item_type(this,2);">
                    <label >설명형</label>
                    <div class="attr-row" style="margin-top: 5px">
                        <div class="attr-name">항목명</div>
                        <div class="attr-value">
                            <div class="input-wrap">
                                <input type="text" name="repo_item" class="input" placeholder="항목 입력" value="">
                            </div>
                        </div>
                    </div>
                    <div class="attr-row repo_req" style="margin-top: 5px;display: none">
                        <div class="attr-name">질문</div>
                        <div class="attr-value">
                            <div class="input-wrap">
                                <input type="text" name="repo_req" id="repo_req" class="input" placeholder="질문내용 입력" value="">
                            </div>
                        </div>
                    </div>
                    <div class="jesi-content">
                        <div class="attr-row" style="margin-top: 5px">
                            <div class="attr-value">
                                <div class="input-wrap">
                                    <input type="text" name="repo_item_key" class="input key" placeholder="제시어 입력(예시:이름)" value="">
                                </div>
                            </div>
                            <div class="attr-value">
                                <div class="input-wrap" style="display: flex">
                                    <input type="text" name="repo_item_value" class="input" placeholder="" value="">
                                    <input type="button" value="삭제" class="button_delete" onclick="jesi_del(this)">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: center;margin-top: 5px">
                        <input type="button" onclick = "jesi_add(this);" value="추가하기">
                    </div>
                    <div style="text-align: right;margin-top: 5px">
                        <a class="content-utils" onclick="item_up(this)">
                            <img src="/iam/img/main/icon-up.png"  style="width:20px;height:20px;cursor: pointer">
                        </a>
                        <a class="content-utils" onclick="item_down(this)">
                            <img src="/iam/img/main/icon-down.png"  style="width:20px;height:20px;cursor: pointer">
                        </a>
                        <a class="content-utils" onclick="clone_item(this)">
                            <img src="/iam/img/main/icon-clone.png"  style="width:20px;height:20px;cursor: pointer">
                        </a>
                        <a class="content-utils" onclick="del_item(this)">
                            <img src="/iam/img/main/icon-trash.png"  style="width:20px;height:20px;cursor: pointer">
                        </a>
                    </div>
                </div>
            <?}?>
        </section>
        <section class="input-field">
            <div>
                <label>리포트서명</label>
                <label class="switch_repo_status" style="margin:0 25px;">
                    <input type="checkbox" name="status" id="stauts_logo" value="<?php echo $row_form['id'];?>" <?php echo $row_form['sign_visible']==1?"checked":""?>>
                    <span class="slider round" name="status_round" id="stauts_round"></span>
                </label>
            </div>
            <div id="signature-pad" class="m-signature-pad">
                <p class="marb3"><strong class="blink">하단에 서명(마우스로 싸인)을 해주세요.</strong><button type="button" id="clear" class="btn_ssmall bx-white">서명 초기화</button></p>
                <div id="sign"></div>
                <textarea name="signatureJSON" id="signatureJSON" style="display: none" readonly=""></textarea>
            </div>
            <div class="button-wrap">
                <a href="javascript:save_format(<?=$repo?>)" class="button is-pink">저장하기</a>
                <a href="javascript:location.href='report_list.php';" class="button is-grey">목록보기</a>
            </div>
        </section>
    </div><!-- /content-wrapper -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
</div>
<div id="show_detail_more" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="border:none;background-color: rgb(130,199,54)">
                <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -12px;cursor:pointer;">X</a>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;box-shadow: none;width: 100%;">
                    <p style="font-size:16px;color:#6e6c6c" id="contents_detail">
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer -->
<div id='open_recv_div' class="open_1">
    <div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
        <li class="open_recv_title open_2_1"></li>
        <li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">

    </div>
</div>
<script>
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
function viewEvent(str){
  window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
}
$(function() {
    if($('.switch_repo_status').find("input[type=checkbox]").is(":checked")==true)
        $("#signature-pad").show();
    else
        $("#signature-pad").hide();
    $(document).ajaxStop(function() {
        $("#ajax-loading").delay(10).hide(1);
    });
    $('.switch_repo_status').on("change", function() {
        var status = $(this).find("input[type=checkbox]").is(":checked")==true?1:0;
        if(status == 1)
            $("#signature-pad").show();
        else
            $("#signature-pad").hide();
    });
});
function save_format(index) {
    if ($('#repo_title').val() == "") {
        alert('리포트 타이틀을 입력해주세요.');
        $('#repo_title').focus();
        return;
    }
    if ($('#repo_desc').val() == "") {
        alert('리포트 설명글을 입력해주세요.');
        $('#repo_desc').focus();
        return;
    }
    var jsonObj = {};
    jsonObj["title"] = $('#repo_title').val();
    jsonObj["desc"] = $('#repo_desc').val();
    jsonObj["sign"] = $('.switch_repo_status').find("input[type=checkbox]").is(":checked")==true?1:0;
    jsonObj["item"] = [];
        jsonObj["req_yn"] = $('input[name=request_yn]:checked').val();
        jsonObj["event_code"] = $('#event_code').val();
        jsonObj["pcode"] = $('#pcode').val();
        jsonObj["sp"] = $('#event_name_eng_event').val();
        jsonObj["event_idx"] = $('#event_idx').val();

    $(".repo_format").find(".form-wrap").each(function(){
        var index = $(this).data("index");
        var item_type = $(this).find("input[name=item_type_" + index+"]:checked").val();
        var item_title = $(this).find("input[name=repo_item]").val();
        var item_req = $(this).find("input[name=repo_req]").val();
        var item = {};
        item ["order"] = index;
        item ["type"] = item_type;
        item ["title"] = item_title;
        item["req"] = item_req;
        item["key"] = [];
        if(item_type != 2){
            $(this).find(".attr-row").find("input[name=repo_item_key]").each(function(){
                item["key"].push($(this).val());
            });
        }else{
            $(this).find(".jesi-content").find(".attr-row").each(function(){
                var repo = {};
                repo["desc"] = $(this).find("textarea").val();
                repo["link"] = $(this).find("input[name=repo_item_value]").val();
                item["key"].push(repo);
            });
        }
        jsonObj["item"].push(item);
    });
    var method = (index != 0?"edit_format":"create_format");
    var msg=index != 0?"수정하시겠습니까?":"등록하시겠습니까?";
    if(confirm(msg)){
        $.ajax({
            type:"POST",
            url:"/ajax/ajax.report.php",
            dataType:"json",
            data:{
                index:index,
                method:method,
                data:JSON.stringify(jsonObj)
            },
            success:function(data){
                alert("성공적으로 등록되었습니다.");
                location.href="report_list.php";
            }
        });
    }
}
function jesi_add(obj){
    var parent = $(obj).parents(".form-wrap");
    var order = parent.data("index");
    var jesi_type = parent.find("input[name^=item_type_]:checked").val();
    var jesi_row = parent.find(".jesi-content");
    var content = "";
    if(jesi_type == 0){
        content = "<div class=\"attr-row\" style=\"margin-top: 5px\">"+
            "<div class=\"attr-value\">"+
            "<div class=\"input-wrap\">"+
            "<input type=\"text\" name=\"repo_item_key\" class=\"input key\" placeholder=\"제시어 입력(예시:이름)\" value=\"\">"+
            "</div>"+
            "</div>"+
            "<div class=\"attr-value\">"+
            "<div class=\"input-wrap\" style=\"display: flex\">"+
            "<input type=\"text\" name=\"repo_item_value\" class=\"input\" placeholder=\"\" value=\"\">"+
            "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">"+
            "</div>"+
            "</div>"+
            "</div>";
    }else if(jesi_type == 1){
        content = "<div class=\"attr-row\" style=\"margin-top: 5px\">"+
            "<div class=\"attr-value\">"+
            "<div class=\"input-wrap\" style=\"display: flex\">"+
            "<input type=\"text\" name=\"repo_item_key\" class=\"input\" placeholder=\"옵션\" value=\"\">"+
            "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">"+
            "</div>"+
            "</div>"+
            "</div>";
    }else if(jesi_type == 3){
        content = "<div class=\"attr-row\" style=\"margin-top: 5px\">"+
            "<div class=\"attr-value\" style=\"display: block;width: 100%\">"+
            "<div class=\"input-wrap\">"+
            "<input type=\"text\" name=\"repo_item_key\" class=\"input key\" placeholder=\"세부질문내용 입력\" value=\"\">"+
            "</div>"+
            "</div>"+
            "<div class=\"attr-value\" style=\"display: block;width: 100%;margin-top: 5px\">"+
            "<div class=\"input-wrap\" style=\"display: flex\">"+
            "<input type=\"text\" name=\"repo_item_value\" class=\"input\" placeholder=\"\" value=\"\">"+
            "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">"+
            "</div>"+
            "</div>"+
            "</div>";
    }else{
        content = "<div class=\"attr-row\" style=\"margin-top: 5px\">"+
            "<div class=\"attr-value\">"+
            "<div class=\"input-wrap\">"+
            "<textarea class=\"input\" placeholder=\"내용 입력\"  style='height: 200px' value=\"\"></textarea>"+
            "<div style='display: flex'>"+
            "<input type=\"text\" name=\"repo_item_value\" class=\"input\" placeholder=\"링크주소 입력 http://\" value=\"\">"+
            "<input type=\"file\" name=\"repo_item_file\" class=\"input\" style=\"padding:2px;width:33%\" accept=\".jpg,.jpeg,.png,.gif,.svc\" onchange=\"uploadImage(this)\">"+
            "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">"+
            "</div>"+
            "</div>"+
            "</div>"+
            "</div>";
    }
    jesi_row.append(content);
}
function chk_item_type(obj,jesi_type){
    var parent = $(obj).parents(".form-wrap");
    var jesi_row = parent.find(".jesi-content");
    jesi_row.empty();
    var content = "";
    if(jesi_type == 0){
        parent.find(".repo_req").hide();
        content = "<div class=\"attr-row\" style=\"margin-top: 5px\">"+
            "<div class=\"attr-value\">"+
            "<div class=\"input-wrap\">"+
            "<input type=\"text\" name=\"repo_item_key\" class=\"input key\" placeholder=\"제시어 입력(예시:이름)\" value=\"\">"+
            "</div>"+
            "</div>"+
            "<div class=\"attr-value\">"+
            "<div class=\"input-wrap\" style=\"display: flex\">"+
            "<input type=\"text\" name=\"repo_item_value\" class=\"input\" placeholder=\"\" value=\"\">"+
            "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">"+
            "</div>"+
            "</div>"+
            "</div>";
    }else if(jesi_type == 1){
        parent.find(".repo_req").show();
        content = "<div class=\"attr-row\" style=\"margin-top: 5px\">"+
            "<div class=\"attr-value\">"+
            "<div class=\"input-wrap\" style=\"display: flex\">"+
            "<input type=\"text\" name=\"repo_item_key\" class=\"input\" placeholder=\"옵션\" value=\"\">"+
            "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">"+
            "</div>"+
            "</div>"+
            "</div>";
    }else if(jesi_type == 3){
        parent.find(".repo_req").hide();
        content = "<div class=\"attr-row\" style=\"margin-top: 5px\">"+
            "<div class=\"attr-value\" style=\"display: block;width: 100%\">"+
            "<div class=\"input-wrap\">"+
            "<input type=\"text\" name=\"repo_item_key\" class=\"input key\" placeholder=\"세부질문내용 입력\" value=\"\">"+
            "</div>"+
            "</div>"+
            "<div class=\"attr-value\" style=\"display: block;width: 100%;margin-top: 5px\">"+
            "<div class=\"input-wrap\" style=\"display: flex\">"+
            "<input type=\"text\" name=\"repo_item_value\" class=\"input\" placeholder=\"\" value=\"\">"+
            "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">"+
            "</div>"+
            "</div>"+
            "</div>";
    }else{
        parent.find(".repo_req").show();
        content = "<div class=\"attr-row\" style=\"margin-top: 5px\">"+
            "<div class=\"attr-value\">"+
            "<div class=\"input-wrap\">"+
            "<textarea class=\"input\" placeholder=\"내용 입력\"  style='height: 200px' value=\"\"></textarea>"+
            "<div style='display: flex'>"+
            "<input type=\"text\" name=\"repo_item_value\" class=\"input\" placeholder=\"링크주소 입력 http://\" value=\"\">"+
            "<input type=\"file\" name=\"repo_item_file\" class=\"input\" style=\"padding:2px;width:33%\" accept=\".jpg,.jpeg,.png,.gif,.svc\" onchange=\"uploadImage(this)\">"+
            "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">"+
            "</div>"+
            "</div>"+
            "</div>"+
            "</div>";
    }
    jesi_row.append(content);
}
function jesi_del(obj){
    $(obj).parents(".attr-row").remove();
}
function uploadImage(obj){
    var parent = $(obj).parents(".attr-row");
    var item_val = parent.find("input[name=repo_item_value]");
    var item_file = parent.find("input[name=repo_item_file]")[0];
    var formData = new FormData();
    formData.append('method', 'uploadImage');
    formData.append('uploadFile', item_file.files[0]);
    $.ajax({
            type: "POST",
            url:"/ajax/ajax.report.php",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                item_val.val(data);
            }
        });
}
function clone_item(obj){
    var parent = $(obj).parents(".form-wrap");
    var order = parent.data("index");
    var section = $(obj).parents("section");
    $.fn.reverse = [].reverse;
    section.find('.form-wrap').reverse().each(function () {
        var index = $(this).data('index');
        var newIndex = index + 1;
        if (index > order) {
            $(this).data("index",newIndex);
            $(this).find("input[name^=item_type_]").each(function () {
                $(this).attr("name","item_type_" + newIndex);
            });
        }
    });
    var child = parent.clone();
    var childOrder = order + 1;
    child.data("index",childOrder);
    child.find("input[name^=item_type_]").each(function () {
        $(this).attr("name","item_type_" + childOrder);
    });
    child.insertAfter(parent);
}
function del_item(obj){
    var parent = $(obj).parents(".form-wrap");
    var order = parent.data("index");
    var section = $(obj).parents("section");
    var count = 0;
    if(order == 0){
        section.find('.form-wrap').each(function () {
            var index = $(this).data('index');
            if (index > order) {
                count++;
            }
        });
    }
    if(count == 0 && order == 0){
        alert("삭제할수 없습니다.");
        return;
    }
    section.find('.form-wrap').each(function () {
        var index = $(this).data('index');
        var newIndex = index - 1;
        if (index > order) {
            $(this).data("index",newIndex);
            $(this).find("input[name^=item_type_]").each(function () {
                $(this).attr("name","item_type_" + newIndex);
            });
        }
    });
    parent.remove();
}
function item_up(obj){
    var parent = $(obj).parents(".form-wrap");
    var order = parent.data("index");
    if(order == 0)
    {
        alert("이동할수 없습니다.");
        return;
    }
    parent.find("input[name^=item_type_]").each(function () {
        $(this).attr("name","item_type_" + "_");
    });
    var dst = null;
    var section = $(obj).parents("section");
    section.find('.form-wrap').each(function () {
        var index = $(this).data('index');
        if (index == order - 1) {
            dst  = $(this);
            dst.data("index",order);
            $(this).find("input[name^=item_type_]").each(function () {
                $(this).attr("name","item_type_" + order);
            });
        }
    });
    var child = parent.clone();
    var childOrder = order - 1;
    child.data("index",childOrder);
    child.find("input[name^=item_type_]").each(function () {
        $(this).attr("name","item_type_" + childOrder);
    });
    child.insertBefore(dst);
    parent.remove();
}
function item_down(obj){
    var parent = $(obj).parents(".form-wrap");
    var order = parent.data("index");
    var section = $(obj).parents("section");
    var count = 0;
    section.find('.form-wrap').each(function () {
        var index = $(this).data('index');
        if (index > order) {
            count++;
        }
    });
    if(count == 0){
        alert("이동할수 없습니다.");
        return;
    }
    parent.find("input[name^=item_type_]").each(function () {
        $(this).attr("name","item_type_" + "_");
    });
    var dst = null;
    section.find('.form-wrap').each(function () {
        var index = $(this).data('index');
        if (index == order + 1) {
            dst  = $(this);
            dst.data("index",order);
            $(this).find("input[name^=item_type_]").each(function () {
                $(this).attr("name","item_type_" + order);
            });
        }
    });
    var child = parent.clone();
    var childOrder = order + 1;
    child.data("index",childOrder);
    child.find("input[name^=item_type_]").each(function () {
        $(this).attr("name","item_type_" + childOrder);
    });
    child.insertAfter(dst);
    parent.remove();
}
function delete_report(id){
    if(confirm("삭제하시겠습니까?")){
        $.ajax({
            type:"POST",
            url:"/ajax/ajax.report.php",
            dataType:"json",
            data:{
                method:"del",
                index:id
            },
            success: function(data){
                if(data.result == "success"){
                    alert('성공적으로 삭제 되었습니다.');
                    window.location.reload();
                }
            }
        });
    }
}
function deleteMultiRow() {
    if(confirm('삭제하시겠습니까?')) {
        var check_array = $("#report_table").children().find(".check");
        var no_array = [];
        var index = 0;
        check_array.each(function(){
            if($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });
        if(index == 0){
            alert("삭제할 리포트를 선택해주세요.");
            return;
        }
        $.ajax({
            type:"POST",
            url:"/ajax/ajax.report.php",
            dataType:"json",
            data:{
                method:"del",
                index:no_array.toString()
            },
            success: function(data){
                if(data.result == "success"){
                    alert('성공적으로 삭제 되었습니다.');
                    window.location.reload();
                }
            }
        })
    }
}
function cloneMultiRow() {
    if(confirm('복제하시겠습니까?')) {
        var check_array = $("#report_table").children().find(".check");
        var no_array = [];
        var index = 0;
        check_array.each(function(){
            if($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });
        if(index == 0){
            alert("복제할 리포트를 선택해주세요.");
            return;
        }
        $.ajax({
            type:"POST",
            url:"/ajax/ajax.report.php",
            dataType:"json",
            data:{
                method:"clone",
                index:no_array.toString()
            },
            success: function(data){
                if(data.result == "success"){
                    alert('성공적으로 복제 되었습니다.');
                    window.location.reload();
                }
            }
        })
    }
}
function show_more(str){
    $("#contents_detail").html(str);
    $("#show_detail_more").modal("show");
}
</script>