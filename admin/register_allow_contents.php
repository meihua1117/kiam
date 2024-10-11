<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
date_default_timezone_set('Asia/Seoul');
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_today1=date("Y-m-d H:i:s");
$date_month=date("Y-m");

if(isset($_GET['id'])){
    $update = true;
}
$id = $_GET['id'];
$sql = "select * from get_crawler_bizinfo where id={$id}";
$res = mysqli_query($self_con,$sql);
while($row = mysqli_fetch_array($res)){
    $info_source = $row['info_source'];
    $web_type = $row['web_type'];
    $work_name = $row['work_name'];
    $detail_link = $row['detail_link'];
    $reg_date = $row['reg_date'];
    $end_date = $row['end_date'];
    $org_name = $row['org_name'];
    $show_cnt = $row['show_cnt'];
    $allow_state = $row['allow_state'];
}
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    var mem_id="";
    function page_view(mem_id) {
        $('.ad_layer1').lightbox_me({
            centered: true,
            onLoad: function() {
                $.ajax({
                    type:"POST",
                    url:"/admin/ajax/member_list_page1.php",
                    data:{mem_id:mem_id},
                    dataType: 'html',
                    success:function(data){
                        $('#phone_list').html(data);
                    },
                    error: function(){
                        alert('로딩 실패');
                    }
                });
            }
        });
        $('.ad_layer1').css({"overflow-y":"auto", "height":"300px"});
    }
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
            콘텐츠수집목록 관리
                <small>수집된 콘텐츠목록 관리합니다.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">콘텐츠목록 관리</li>
            </ol>
        </section>

        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
            <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />
            <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />
            <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />
        </form>

        <!-- Main content -->
        <section class="content">
            <div class="row" id="register_contents" style="margin-left:7%;">
            <h2>검색결과 상세 페이지 보기</h2>
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
                            if($web_type == "지원사업"){
                                $sel1 = "checked";
                            }
                            else if($web_type == "입찰공고"){
                                $sel2 = "checked";
                            }
                            else if($web_type == "행사교육"){
                                $sel3 = "checked";
                            }
                            else if($web_type == "기타정보"){
                                $sel4 = "checked";
                            }
                            ?>
                                <input type="radio" name="web_type" id="support" style="vertical-align: top;" <?=$sel1?>>
                                <label for="support" value="1" style="font-size:15px;">지원사업</label>
                                <input type="radio" name="web_type" id="public" style="vertical-align: top;" <?=$sel2?>>
                                <label for="public" value="2" style="font-size:15px;">입찰공고</label>
                                <input type="radio" name="web_type" id="eventedu" style="vertical-align: top;" <?=$sel3?>>
                                <label for="eventedu" value="3" style="font-size:15px;">행사교육</label>
                                <input type="radio" name="web_type" id="other" style="vertical-align: top;" <?=$sel4?>>
                                <label for="other" value="4" style="font-size:15px;">기타정보</label>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>사업명칭</td>
                            <td><input type="text" name="work_name" id="work_name" placeholder="사업명칭을 입력합니다." value="<?=$work_name?>" style="width: 100%;"></td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>웹주소</td>
                            <td><input type="text" name="detail_link" id="detail_link" placeholder="웹주소를 입력합니다." value="<?=$detail_link?>" style="width: 100%;"></td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>등록일시</td>
                            <td><input type="text" name="reg_date" id="reg_date" placeholder="등록일시를 입력합니다." style="width: 100%;" value='<?=$reg_date?>'></td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>마감일시</td>
                            <td><input type="text" name="end_date" id="end_date" placeholder="마감일시를 입력합니다." style="width: 100%;" value='<?=$end_date?>'></td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>출처</td>
                            <td><input type="text" name="org_name" id="org_name" placeholder="게시기관을 입력합니다." style="width: 100%;" value='<?=$org_name?>'></td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>조회수</td>
                            <td><input type="text" name="show_cnt" id="show_cnt" value="<?=$show_cnt;?>" style="width:100%;"></td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>승인상태</td>
                            <td>
                            <?php
                            if($allow_state == 1){
                                $wors1 = "checked";
                            }
                            else if($allow_state == 0){
                                $wors2 = "checked";
                            }
                            ?>
                                <input type="radio" name="status_work" id="goon" style="vertical-align: top;" <?=$wors1?>>
                                <label for="goon" value="1" style="font-size:15px;">승인</label>
                                <input type="radio" name="status_work" id="stop" style="vertical-align: top;" <?=$wors2?>>
                                <label for="stop" value="0" style="font-size:15px;">대기</label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-primary pull-center" style="margin-right: 5px;" id="btn_get" onclick="goback()">
                    취소
                </button>
                <button class="btn btn-primary pull-center" style="margin-right: 5px;" id="btn_get" onclick="register_getcon(<?=$id?>)">
                    저장
                </button>
            </div>
    </div><!-- /.row -->




    </section><!-- /.content -->
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
        var status_allow = 0;//승인상태
        var info_type = 0;//정보구분
        info_source = $("#info_source").val();//정보출처
        work_name = $("#work_name").val();//사업명칭
        detail_link = $("#detail_link").val();//웹주소
        reg_date = $("#reg_date").val();//등록일시
        end_date = $("#end_date").val();//마감일시
        org_name = $("#org_name").val();//게시기관
        show_cnt = $("#show_cnt").val();//조회수

        $('input[name=web_type]:checked').each(function() {
            idVal = $(this).attr("id");
            info_type = $("label[for='"+idVal+"']").attr('value');
        });

        $('input[name=status_work]:checked').each(function() {
            idVal = $(this).attr("id");
            status_allow = $("label[for='"+idVal+"']").attr('value');
        });

        $.ajax({
            type:"POST",
            url:"ajax/allow_contents_update.php",
            dataType:"json",
            data:{update:update, info_source:info_source, info_type:info_type, work_name:work_name, detail_link:detail_link, reg_date:reg_date, end_date:end_date, org_name:org_name, show_cnt:show_cnt, status_allow:status_allow},
            success:function(data){
                if(data == 2){
                    alert("수정 되었습니다.");
                    location.href = "allow_contents_manage.php";
                }
            }
        });
    }

    function goback(){
        location.href="allow_contents_manage.php";
    }
</script>
<!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      