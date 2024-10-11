<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
date_default_timezone_set('Asia/Seoul');
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_today1=date("Y-m-d H:i:s");
$date_month=date("Y-m");
$time = time()-(86400*365);
$comp_date = date("Y-m-d", $time);

$sql_share = "select * from share_contents_mng where share_obj='장수군청산림과'";
$res_share = mysqli_query($self_con,$sql_share);
$row_share = mysqli_fetch_array($res_share);

$sql_key_search_work = get_search_key($row_share['work_key']);
$sql_key_search_public = get_search_key($row_share['public_key']);
$sql_key_search_edu = get_search_key($row_share['edu_key']);
$sql_key_search_other = get_search_key($row_share['other_key']);

$sql_key_search = " and ((web_type='지원사업'".$sql_key_search_work.") or (web_type='행사교육'".$sql_key_search_edu.") or (web_type='입찰공고'".$sql_key_search_public.") or (web_type='기타정보'".$sql_key_search_other."))";

if(isset($_GET['id'])){
    $update = true;
}
$id = $_GET['id'];
$sql = "select * from share_contents_mng where id={$id}";
$res = mysqli_query($self_con,$sql);
while($row = mysqli_fetch_array($res)){
    $share_obj = $row['share_obj'];
    $share_domain = $row['share_domain'];
    $server_position = $row['server_position'];
    $work_key = $row['work_key'];
    $public_key = $row['public_key'];
    $edu_key = $row['edu_key'];
    $other_key = $row['other_key'];
    $reg_date = $row['reg_date'];
    $end_date = $row['end_date'];
    $share_state = $row['share_state'];
    $manager = $row['manager'];
}

$sql_count1 = "select * from get_crawler_bizinfo where allow_state=1 and allow_state_biz=1 and reg_date>'{$comp_date}'".$sql_key_search;
//echo $sql_count;
$row_cnt1 = mysqli_query($self_con,$sql_count1);
$contents_cnt1 = mysqli_num_rows($row_cnt1);
// $contents_cnt = $res_cnt['cnt'];

$sql_count2 = "select * from get_crawler_bizinfo where allow_state=1 and allow_state_biz=0 and reg_date>'{$comp_date}'".$sql_key_search;
//echo $sql_count;
$row_cnt2 = mysqli_query($self_con,$sql_count2);
$contents_cnt2 = mysqli_num_rows($row_cnt2);

$contents_cnt = $contents_cnt1 * 1 + $contents_cnt2 * 1;
/*function get_search_key($key){
    $sql_key_search = "";
    if($key != ""){
        if(strpos($key, ",") !== false){
            $key_arr = explode(",", $key);
            for($i = 0; $i < count($key_arr); $i++){
                if($i == count($key_arr) - 1){
                    $sql_key_search .= " work_name like '%".$key_arr[$i]."%'";
                }
                else{
                    $sql_key_search .= " work_name like '%".$key_arr[$i]."%' or";
                }   
            }
        }
        else{
            $key_arr[0] = $key;
            $sql_key_search .= " work_name like '%".$key_arr[0]."%'";
        }
        $sql_key_search = " and (".$sql_key_search.")";
    }
    return $sql_key_search;
}*/
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
            공급사 등록
                <small>콘텐츠 공급사 등록합니다.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">공급사등록 관리</li>
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
            <h2>공급사 등록페이지</h2>
                <table id="example2" class="table table-bordered table-striped" style="width:80%;">
                    <thead>
                        <tr>
                            <th style="width:7%;">NO</th>
                            <th style="width:15%;">항목</th>
                            <th style="width:75%;text-align:center;">항목 설명</th>
                        </tr>
                    </thead>
                    <tbody id="reg_table">
                        <tr>
                            <td>1</td>
                            <td>공급대상</td>
                            <td><input type="text" name="share_obj" id="share_obj" placeholder="공급할 업체를 입력합니다." value='<?=$share_obj?>' style="width: 100%;"></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>공급도메인</td>
                            <td><input type="text" name="share_domain" id="share_domain" placeholder="공급할 업체도메인을 입력합니다." value='<?=$share_domain?>' style="width: 100%;"></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>서버위치</td>
                            <td><input type="text" name="server_position" id="server_position" placeholder="서버위치를 입력합니다." value="<?=$server_position?>" style="width: 100%;"></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>지원사업</td>
                            <td><input type="text" name="work_key" id="work_key" placeholder="지원사업 조회키워드를 입력합니다." style="width: 100%;" value='<?=$work_key?>'></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>입찰정보</td>
                            <td><input type="text" name="public_key" id="public_key" placeholder="입찰정보 조회키워드를 입력합니다." style="width: 100%;" value='<?=$public_key?>'></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>행사/교육</td>
                            <td><input type="text" name="edu_key" id="edu_key" placeholder="행사/교육 조회키워드를 입력합니다." style="width: 100%;" value='<?=$edu_key?>'></td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>관련정보</td>
                            <td><input type="text" name="other_key" id="other_key" placeholder="관련정보 조회키워드를 입력합니다." style="width: 100%;" value='<?=$other_key?>'></td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>게시일시</td>
                            <td><input type="date" name="reg_date" id="reg_date" value="<?=$reg_date;?>" style="width:100%;"></td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>종료일시</td>
                            <td><input type="date" name="end_date" id="end_date" value="<?=$end_date?>" style="width:100%;"></td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>콘텐츠갯수</td>
                            <td><input type="text" name="contents_cnt" id="contents_cnt" placeholder="" style="width: 100%;" value='<?=$contents_cnt?>' disabled></td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>담당자</td>
                            <td><input type="text" name="manager" id="manager" placeholder="담당자이름을 입력합니다." style="width: 100%;" value='<?=$manager?>'></td>
                        </tr>      
                        <tr>
                            <td>12</td>
                            <td>진행상태</td>
                            <td>
                            <?php
                            if($share_state == 1){
                                $wors1 = "checked";
                            }
                            else if($share_state == 2){
                                $wors2 = "checked";
                            }
                            ?>
                                <input type="radio" name="status_work" id="goon" style="vertical-align: top;" <?=$wors1?>>
                                <label for="goon" value="1" style="font-size:15px;">승인</label>
                                <input type="radio" name="status_work" id="stop" style="vertical-align: top;" <?=$wors2?>>
                                <label for="stop" value="2" style="font-size:15px;">대기</label>
                            </td>
                        </tr>         
                    </tbody>
                </table>
                <button class="btn btn-primary pull-center" style="margin-right: 5px;" id="btn_get" onclick="goback()">
                    취소
                </button>
                <?php
                if($update){
                ?>
                <button class="btn btn-primary pull-center" style="margin-right: 5px;" id="btn_get" onclick="register_getcon(<?=$id?>)">
                    저장
                </button>
                <?php }
                else{
                ?>
                <button class="btn btn-primary pull-center" style="margin-right: 5px;" id="btn_get" onclick="register_getcon()">
                    저장
                </button>
                <?php }?>
            </div>
        </div><!-- /.row -->
    </section><!-- /.content -->
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>    
</div><!-- /.content-wrapper -->

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
                alert('변경이 완료되었습니다.');
            },
            error: function(){
                alert('초기화 실패');
            }
        });
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
        var status_work = 0;//진행상태
        share_obj = $("#share_obj").val();//공급대상
        share_domain = $("#share_domain").val();//공급도메인
        server_position = $("#server_position").val();//서버위치
        work_key = $("#work_key").val();//지원사업 조회키워드
        public_key = $("#public_key").val();//입찰정보 조회키워드
        edu_key = $("#edu_key").val();//행사/교육 조회키워드
        other_key = $("#other_key").val();//기타사업 조회키워드
        end_date = $("#end_date").val();//마감시간
        contents_cnt = $("#contents_cnt").val();//콘텐츠갯수
        reg_date = $("#reg_date").val();//게시일시
        manager = $("#manager").val();//담당자

        $('input[name=status_work]:checked').each(function() {
            idVal = $(this).attr("id");
            status_work = $("label[for='"+idVal+"']").attr('value');
        });

        $.ajax({
            type:"POST",
            url:"ajax/register_share_db.php",
            dataType:"json",
            data:{update:update, share_obj:share_obj, share_domain:share_domain, server_position:server_position, work_key:work_key, public_key:public_key, edu_key:edu_key, other_key:other_key, end_date:end_date, reg_date:reg_date, contents_cnt:contents_cnt, manager:manager, status_work:status_work},
            success:function(data){
                if(data == 1){
                    alert("정확히 등록되었습니다.");
                    location.href = "share_contents_manage.php";
                }
                else if(data == 2){
                    alert("수정 되었습니다.");
                    location.href = "share_contents_manage.php";
                }
            }
        });
    }

    function goback(){
        location.href="share_contents_manage.php";
    }
</script>
  