<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
date_default_timezone_set('Asia/Seoul');
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_today1=date("Y-m-d H:i:s");

if(isset($_GET['reg_id'])){
    $update = true;
}
$id = $_GET['reg_id'];
$sql = "select * from reg_biz_contents where id={$id}";
$res = mysqli_query($self_con,$sql);
while($row = mysqli_fetch_array($res)){
    $info_source = $row['info_source'];
    $info_type = $row['info_type'];
    $web_address = $row['web_address'];
    $search_key = $row['search_key'];
    $status = $row['status'];
    $keyword = $row['keyword'];
    $get_time = $row['get_time'];
    $memo = $row['memo'];
    $work_status = $row['work_status'];
}
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    $(function() {
        var contHeaderH = $(".main-header").height();
        var navH = $(".navbar").height();
        if(navH != contHeaderH)
            contHeaderH += navH - 50;
        $(".content-wrapper").css("margin-top",contHeaderH);
        var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 173;
        if(height < 375)
            height = 375;
        $(".box-body").css("height",height);
    });
</script>
<style>
    .loading_div {
        display:none;
        position: fixed;
        left: 50%;
        top: 50%;
        display: none;
        z-index: 1000;
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
            <h1>
            콘텐츠수집 컬럼 매칭 등록
                <small>기업마당 페이지 콘텐츠 수집 등록합니다.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">콘텐츠수집 관리</li>
            </ol>
        </section>

        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
            <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />
            <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />
            <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />
        </form>

        <!-- Main content -->
        <section class="content">
            <div class="box-body" id="register_contents" style="margin-left:7%;">
            <h2>콘텐츠수집 매칭/기준 등록</h2>
                <table id="example2" class="table table-bordered table-striped" style="width:80%;">
                    <thead>
                        <tr>
                            <th style="width:7%;">NO</th>
                            <th style="width:15%;">항목</th>
                            <th style="width:75%;text-align:center;">수집키워드 및 설명</th>
                        </tr>
                    </thead>
                    <tbody id="reg_table">
                        <tr>
                            <td>1</td>
                            <td>정보사이트</td>
                            <td><input type="text" name="info_source" id="info_source" placeholder="페이지에서 출처를 입력합니다." value='<?=$info_source?>' style="width: 100%;"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>정보구분</td>
                            <td>
                            <?php
                            if($info_type == "지원사업"){
                                $sel1 = "checked";
                            }
                            else if($info_type == "입찰공고"){
                                $sel2 = "checked";
                            }
                            else if($info_type == "행사교육"){
                                $sel3 = "checked";
                            }
                            else if($info_type == "기타정보"){
                                $sel4 = "checked";
                            }
                            ?>
                                <input type="radio" name="work_type" id="support" style="vertical-align: top;" <?=$sel1?>>
                                <label for="support" value="1" style="font-size:15px;">지원사업</label>
                                <input type="radio" name="work_type" id="public" style="vertical-align: top;" <?=$sel2?>>
                                <label for="public" value="2" style="font-size:15px;">입찰공고</label>
                                <input type="radio" name="work_type" id="eventedu" style="vertical-align: top;" <?=$sel3?>>
                                <label for="eventedu" value="3" style="font-size:15px;">행사교육</label>
                                <input type="radio" name="work_type" id="other" style="vertical-align: top;" <?=$sel4?>>
                                <label for="other" value="4" style="font-size:15px;">기타정보</label>
                                <!-- <input type="radio" name="work_type" id="notice" style="vertical-align: top;">
                                <label for="notice" value="5" style="font-size:15px;">공지사항</label> -->
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>웹주소</td>
                            <td><input type="text" name="web_address" id="web_address" placeholder="웹페이지 주소를 입력합니다." value="<?=$web_address?>" style="width: 100%;"></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>검색어</td>
                            <td><input type="text" name="search_key" id="search_key" placeholder="관리자가 입력한 내용으로 검색합니다." style="width: 100%;" value='<?=$search_key?>'></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>진행/종료</td>
                            <td>
                            <?php
                            $sta3 = "checked";
                            if($status == 1){
                                $sta3 = "";
                                $sta1 = "checked";
                            }
                            else if($status == 2){
                                $sta3 = "";
                                $sta2 = "checked";
                            }
                            else if($status == 3){
                                $sta3 = "checked";
                            }
                            ?>
                                <input type="radio" name="status" id="online" style="vertical-align: top;" <?=$sta1?>>
                                <label for="online" value="1" style="font-size:15px;">진행사업</label>
                                <input type="radio" name="status" id="end" style="vertical-align: top;" <?=$sta2?>>
                                <label for="end" value="2" style="font-size:15px;">종료사업</label>
                                <input type="radio" name="status" id="all" style="vertical-align: top;" <?=$sta3?>>
                                <label for="all" value="3" style="font-size:15px;">전체사업</label>
                            </td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>조회키워드</td>
                            <td><input type="text" name="keyword" id="keyword" placeholder="페이지에서 조회할때 필요한 단어를 입력합니다." style="width: 100%;" value='<?=$keyword?>'></td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td onclick="show_hour()">
                                <input type="checkbox" name="auto_upload_time" id="contents_auto_upload_time">
                                <label for="contents_auto_upload_time" style="margin-top: -7px;">수집시간</label>
                            </td>
                            <td><input type="text" name="upload_time" id="upload_time" placeholder="하루 1-24시간중 3회를 선택합니다." style="width: 100%;" value='<?=$get_time?>'></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td id="24_hours" style="display:none;" onclick="limit_sel_hour()">
                            <?php
                            for($i = 1; $i < 25; $i++){
                            ?>
                            <input type="checkbox" name="select_hour" id="<?=$i?>hour">
                            <label for="<?=$i?>hour" value="<?=$i?>" style="margin-top: -7px;"><?=$i?></label>
                            <?}?>
                            </td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>메모내용</td>
                            <td><input type="text" name="memo_detail" id="memo_detail" placeholder="관리자나 개발자가 필요한 메모를 합니다." style="width:100%;" value='<?=$memo?>'></td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>게시일시</td>
                            <td><input type="text" name="reg_date" id="reg_date" value="<?=$date_today1;?>" style="width:100%;"></td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>진행상태</td>
                            <td>
                            <?php
                            if($work_status == 1){
                                $wors1 = "checked";
                            }
                            else if($work_status == 2){
                                $wors2 = "checked";
                            }
                            ?>
                                <input type="radio" name="status_work" id="goon" style="vertical-align: top;" <?=$wors1?>>
                                <label for="goon" value="1" style="font-size:15px;">진행</label>
                                <input type="radio" name="status_work" id="stop" style="vertical-align: top;" <?=$wors2?>>
                                <label for="stop" value="2" style="font-size:15px;">대기</label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php
                if($update){
                ?>
                <button class="btn btn-primary pull-center" style="margin-right: 5px;" id="btn_get" onclick="register_getcon(<?=$id?>)">
                    저장
                </button>
                <a href="javascript:history.back()" class="btn btn-primary pull-center" style="margin-right: 5px;">취소</a>
                <?php }
                else{
                ?>
                <button class="btn btn-primary pull-center" style="margin-right: 5px;" id="btn_get" onclick="register_getcon()">
                    저장
                </button>
                <a href="javascript:history.back()" class="btn btn-primary pull-center" style="margin-right: 5px;">취소</a>
                <?php }?>
            </div>
        </div><!-- /.row -->
    </section><!-- /.content -->
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>   
</div><!-- /content-wrapper -->

<form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
    <input type="hidden" name="grp_id" value="" />
    <input type="hidden" name="box_text" value="" />
    <input type="hidden" name="one_member_id" id="one_member_id" value="" />
    <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
</form>
<iframe name="excel_iframe" style="display:none;"></iframe>

<script language="javascript">
    function changeLevel(mem_code) {
        var mem_leb = $('#mem_leb'+mem_code+" option:selected").val();
        var data = {"mode":"change","mem_code":"'+mem_code+'","mem_leb":"'+mem_leb+'"};
        $.ajax({
            type:"POST",
            url:"/admin/ajax/user_level_change.php",
            dataType:"json",
            data:{mode:"change",mem_code:mem_code,mem_leb:mem_leb},
            success:function(data){
                //console.log(data);
                //location = "/";
                //location.reload();
                alert('변경이 완료되었습니다.');
            },
            error: function(){
                alert('초기화 실패');
            }
        });

//    alert(mem_code);
    }

    function loginGo(mem_id, mem_pw, mem_code) {
        $('#one_id').val(mem_id);
        $('#mem_pass').val(mem_pw);
        $('#mem_code').val(mem_code);
        $('#login_form').submit();
    }

    function resetRow(cmid) {
        if(confirm('초기화 하시겠습니까?')) {

            $.ajax({
                type:"POST",
                url:"/admin/ajax/crawler_user_change.php",
                dataType:"json",
                data:{mode:"reset",cmid:cmid},
                success:function(data){
                    alert('초기화 되었습니다.되었습니다.');
                },
                error: function(){
                    alert('초기화 실패');
                }
            });
        }
    }

    function show_hour(){
        if($("#contents_auto_upload_time").prop("checked") == true){
            $("#24_hours").attr("style", "display:block;text-align: center;");
        }
        else{
            $("#24_hours").attr("style", "display:none;");
        }
    }

    function limit_sel_hour(){
        var sel_time = new Array();
        var cnt;
        $('input[name=select_hour]:checked').each(function() {
            var idVal = $(this).attr("id");
            cnt = sel_time.push($("label[for='"+idVal+"']").attr('value'));
            if(cnt > 3){
                alert('최대 3개까지 선택할수 있습니다.');
                $('input[id='+idVal+']').prop("checked", false);
                return;
            }
            $("#upload_time").val(sel_time.join(","));
        });
    }
    
    function register_getcon(update){
        var info_type = 0;//정보구분
        var status = 3;//진행/종료
        var status_work = 0;//진행상태
        info_source = $("#info_source").val();//정보출처
        web_address = $("#web_address").val();//웹주소
        search_key = $("#search_key").val();//검색어
        keyword = $("#keyword").val();//조회키워드
        get_time = $("#upload_time").val();//수집시간
        memo = $("#memo_detail").val();//메모내용
        reg_date = $("#reg_date").val();//게시일시

        $('input[name=work_type]:checked').each(function() {
            idVal = $(this).attr("id");
            info_type = $("label[for='"+idVal+"']").attr('value');
        });
        $('input[name=status]:checked').each(function() {
            idVal = $(this).attr("id");
            status = $("label[for='"+idVal+"']").attr('value');
        });
        $('input[name=status_work]:checked').each(function() {
            idVal = $(this).attr("id");
            status_work = $("label[for='"+idVal+"']").attr('value');
        });

        if((info_source == "") || (web_address == "") || (get_time == "") || (info_type == "")){
            alert("정보출처, 정보구분, 웹주소, 수집시간을 정확히 입력해 주세요.");
            return;
        }

        $.ajax({
            type:"POST",
            url:"ajax/register_con_db.php",
            dataType:"json",
            data:{update:update, info_source:info_source, info_type:info_type, web_address:web_address, search_key:search_key, status:status, keyword:keyword, get_time:get_time, memo:memo, reg_date:reg_date, status_work:status_work},
            success:function(data){
                if(data == 1){
                    alert("정확히 등록되었습니다.");
                    location.href = "get_contents_manage.php";
                }
                else if(data == 2){
                    alert("수정 되었습니다.");
                    location.href = "get_contents_manage.php";
                }
            }
        });
    }
</script>
   