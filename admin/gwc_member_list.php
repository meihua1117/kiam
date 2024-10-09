<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");
if($case == 0 )
$case = -1;
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
    //계정 탈퇴
    function logout_member_info(mem_code){
        var msg = confirm('정말로 탈퇴하시겠습니까?');
        if(msg){
            $.ajax({
                type:"POST",
                url:"/admin/ajax/user_leave.php",
                data:{
                    type : "logout",
                    mem_code:mem_code
                    },
                success:function(){
                    alert('탈퇴되었습니다.');
                    location.reload();
                },
                error: function(){
                    alert('탈퇴 실패');
                }
            });
        }else{
            return false;
        }
    }
    //계정 삭제
    function del_member_info(mem_code){
        var msg = confirm('정말로 삭제하시겠습니까?');
        if(msg){
            $.ajax({
                type:"POST",
                url:"/admin/ajax/user_leave.php",
                data:{
                    type : "del",
                    mem_code:mem_code
                    },
                success:function(){
                    alert('삭제되었습니다.');
                    location.reload();
                },
                error: function(){
                    alert('삭제 실패');
                }
            });
        }else{
            return false;
        }
    }
    //주소록 다운
    function excel_down_(){
        $("#excel_down_form").submit();
        return false;
    }
    function goPage(pgNum) {
        location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&identifier=<?=$_GET['identifier'];?>&rec_id=<?=$_GET['rec_id'];?>&n_key=<?=$_GET['n_key'];?>&p_key=<?=$_GET['p_key'];?>&s_key=<?=$_GET['s_key'];?>&i_key=<?=$_GET['i_key'];?>&g_key=<?=$_GET['g_key'];?>&iam_type=<?=$_GET['iam_type'];?>&orderField=<?=$_GET['orderField'];?>&dir=<?=$_GET['dir'];?>&case=<?=$_GET['case'];?>";
            
    }
    //주소록 다운
    function excel_down_p_group(pno,one_member_id){
        $($(".loading_div")[0]).show();
        $($(".loading_div")[0]).css('z-index',10000);
        location='/excel_down/excel_down__.php?pno='+pno+'&one_member_id='+one_member_id;
        $($(".loading_div")[0]).hide();
    }
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
                location.reload();
            },
            error: function(){
                alert('초기화 실패');
            }
        });
    }
    function changegwcLevel(mem_code) {
        var gwc_leb = $('#gwc_leb'+mem_code+" option:selected").val();
        $.ajax({
            type:"POST",
            url:"/admin/ajax/user_gwclevel_change.php",
            dataType:"json",
            data:{mode:"change_leb",mem_code:mem_code,gwc_leb:gwc_leb},
            success:function(data){
                alert('등급승인상태가 변경되었습니다.');
                location.reload();
            },
            error: function(){
                alert('초기화 실패');
            }
        });
    }
    function setMultiRow(val){
        var check_array = $("#example1").children().find(".check");
        var no_array = [];
        var index = 0;
        check_array.each(function(){
            if($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });

        if(no_array.length == 0){
            alert("회원을 선택하세요.");
            return;
        }
        if(val == '1'){
            var msg = "일괄승인하시겠습니까?";
        }
        else{
            var msg = "일괄해제하시겠습니까?";
        }
        
        if(confirm(msg)) {
            $.ajax({
                type:"POST",
                url:"/admin/ajax/user_gwclevel_change.php",
                dataType:"json",
                data:{mode:"multi_change_leb", id:no_array.toString(), state:val},
                success: function(data){
                    alert('변경되었습니다.');
                    location.reload();
                }
            })
        }
    }
    function changeServiceType(mem_code) {
        var service_type = $('#service_type'+mem_code+" option:selected").val();
        $.ajax({
            type:"POST",
            url:"/admin/ajax/user_service_type_change.php",
            dataType:"json",
            data: {
                mode: "updat",
                mem_code: mem_code,
                service_type: 'service_type',
                service_value: service_type
            },
            success:function(data){
                alert('변경이 완료되었습니다.');
                location.reload();
            },
            error: function(){
                alert('초기화 실패');
            }
        });
    }

    function changeIamType(mem_code) {
        var iam_type = $('#iam_pay_type' + mem_code + " option:selected").val();
        $.ajax({
            type:"POST",
            url:"/admin/ajax/user_service_type_change.php",
            dataType:"json",
            data: {
                mode: "updat",
                mem_code: mem_code,
                service_type: 'iam_type',
                service_value: iam_type
            },
            success:function(res){
                alert('변경이 완료되었습니다.');
                location.reload();
            },
            error: function(){
                alert('초기화 실패');
            }
        });
    }

    function changeSpecialType(mem_code) {
        var special_type = $('#special_type' + mem_code + " option:selected").val();
        $.ajax({
            type:"POST",
            url:"/admin/ajax/user_service_type_change.php",
            dataType:"json",
            data: {
                mode: "updat",
                mem_code: mem_code,
                service_type: 'special_type',
                service_value: special_type
            },
            success:function(res){
                alert('변경이 완료되었습니다.');
                location.reload();
            },
            error: function(){
                alert('초기화 실패');
            }
        });
    }

    $(function(){
        $("#sec_pwd").keypress(function (evt) {
            if (evt.keyCode === 13) {
                onConfirm();
            } 
        });

        $('.switch').on("change", function() {
            var mem_code = $(this).find("input[type=checkbox]").val();
            var status = $(this).find("input[type=checkbox]").is(":checked")==true?"1":"0";
            $.ajax({
                type:"POST",
                url:"/admin/ajax/user_gwclevel_change.php",
                data:{
                    mode:'change_state', mem_code:mem_code, status:status
                },
                success:function(data){
                    //alert('신청되었습니다.');location.reload();
                    location.reload();
                }
            })
        });
    });
    function loginGo(mem_id, mem_pw, mem_code) {
        $('#sec_pwd').val('');        
        $('#admin_pwd').val('');
        $("#modal-password").modal("show");
        $('#user_id').val(mem_id);
        $('#user_pwd').val(mem_pw);
        $('#user_code').val(mem_code);
        $("#sec_pwd").focus();
    }

    function onConfirm() {
        $("#modal-password").modal("hide");
        $('#admin_pwd').val($('#sec_pwd').val());
        $('#one_id').val($('#user_id').val());
        $('#mem_pass').val($('#user_pwd').val());
        $('#mem_code').val($('#user_code').val());
        $('#login_form').submit();
    }

    $(function(){
        $('.check').on("click",function(){
            if($(this).prop("id") == "check_all_member"){
                if($(this).prop("checked"))
                    $('.check').prop("checked",true);
                else
                    $('.check').prop("checked",false);
            }else if($(this).prop("id") == "check_one_member"){
                if(!$(this).prop("checked"))
                    $('#check_all_member').prop("checked",false);
            }
        });
    });
    function deleteMultiRow() {
        var check_array = $("#example1").children().find(".check");
        var no_array = [];
        var index = 0;
        check_array.each(function(){
            if($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });

        if(no_array.length == 0){
            alert("삭제할 회원을 선택하세요.");
            return;
        }
        if(confirm('삭제하시겠습니까?')) {
            $.ajax({
            type:"POST",
            url:"/admin/ajax/delete_func.php",
            dataType:"json",
            data:{admin:1, delete_name:"member_list", id:no_array.toString()},
            success: function(data){
                console.log(data);
                if(data == 1){
                    alert('삭제 되었습니다.');
                    window.location.reload();
                }
            }
        })
        }
    }
    function request_btn(val){
        var leb = $(".req_leb_txt_"+val+"").text();
        console.log(leb);
        if(leb.search('일반') != -1){
            if(!confirm("일반회원으로 전환되면 이 회원은 탈퇴 됩니다. 계속 하시겠습니까?")){
                return;
            }
        }
        $.ajax({
            type:"POST",
            url:"/admin/ajax/user_gwclevel_change.php",
            data:{
                mode:'accep_req', mem_code:val
            },
            success:function(data){
                alert('신청되었습니다.');
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
        var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 196;
        if(height < 375)
            height = 375;
        $(".box-body").css("height",height);
    });
</script>
<style>
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    input, select, textarea {
        vertical-align: middle;
        border: 1px solid #CCC;
    }
    user agent stylesheet
    input[type="checkbox" i] {
        background-color: initial;
        cursor: default;
        -webkit-appearance: checkbox;
        box-sizing: border-box;
        margin: 3px 3px 3px 4px;
        padding: initial;
        border: initial;
    }
    input:checked + .slider {
        background-color: #2196F3;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }
    input:checked + .slider {
        background-color: #2196F3;
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
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
    .wrapper{height:100%;overflow:auto;}
    .content-wrapper{min-height : 80% !important;}
    .box-body{overflow:auto;padding:0px !important}
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
            <h1>
                굿마켓회원관리<small>굿마켓회원을 관리합니다.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">굿마켓회원관리</li>
            </ol>
        </section>

        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
            <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />
            <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />
            <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />
            <input type="hidden" name="admin_pwd"   id="admin_pwd" />
        </form>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:10px">
                    <div style="padding:10px">
                        <a href="?case=-1" style="border:1px solid
                            <?if($case == -1){?>
                                black; background-color: lightblue;
                            <?}else{?>
                                #337ab7;
                            <?}?>
                            padding:5px">전체</a>
                        <a href="?case=1" style="border:1px solid
                            <?if($case == 1){?>
                                black; background-color: lightblue;
                            <?}else{?>
                                #337ab7;
                            <?}?>
                            padding:5px">회원/앱</a>
                        <a href="?case=2" style="border:1px solid
                            <?if($case == 2){?>
                                black; background-color: lightblue;
                            <?}else{?>
                                #337ab7;
                            <?}?>
                            padding:5px">회원/미앱</a>
                        <a href="?case=3" style="border:1px solid
                            <?if($case == 3){?>
                                black; background-color: lightblue;
                            <?}else{?>
                                #337ab7;
                            <?}?>
                            padding:5px">추가폰</a>
                        <a href="?case=4" style="border:1px solid
                        <?if($case == 4){?>
                            black; background-color: lightblue;
                        <?}else{?>
                            #337ab7;
                            <?}?>
                            padding:5px">탈퇴회원</a>
                        <a href="?case=5" style="border:1px solid
                        <?if($case == 5){?>
                            black; background-color: lightblue;
                        <?}else{?>
                            #337ab7;
                            <?}?>
                            padding:5px">오토회원</a>
                    </div>
                    <? if($_SESSION['one_member_admin_id'] != "onlyonemaket"){
                        if ($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == $HTTP_HOST) {
                        } else { ?>
                                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_member_down_.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>
                            <? }
                    }?>
                    <div>
                    <form method="get" name="search_form" id="search_form">
                        <input type="hidden" name="case" value="<? echo $_GET['case'];?>">
                        <div class="box-tools">
                            <div class="input-group" style="display:inline-flex;width: 100%">
                                <input type="text" name="identifier" id="identifier" value="<?=$identifier?>" style="width:7%" class="form-control input-sm pull-right" placeholder="아이디">
                                <input type="text" name="rec_id" id="rec_id" value="<?=$rec_id?>" style="width:7%" class="form-control input-sm pull-right" placeholder="추천인">
                                <input type="text" name="n_key" id="n_key" value="<?=$n_key?>" style="width:7%" class="form-control input-sm pull-right" placeholder="이름">
                                <input type="text" name="p_key" id="p_key" value="<?=$p_key?>" style="width:7%" class="form-control input-sm pull-right" placeholder="휴대폰">
                                <input type="text" name="s_key" id="s_key" value="<?=$s_key?>" style="width:7%" class="form-control input-sm pull-right" placeholder="셀링소속">
                                <input type="text" name="i_key" id="i_key" value="<?=$i_key?>" style="width:7%" class="form-control input-sm pull-right" placeholder="아이엠소속">
                                <input type="text" name="g_key" id="g_key" value="<?=$g_key?>" style="width:7%" class="form-control input-sm pull-right" placeholder="그룹소속">
                                <select name="iam_type" id="iam_type<?=$iam_type?>">
                                    <option value="a" <? echo $iam_type == "a" || $iam_type == ''?"selected":""?>>전체</option>
                                    <option value="0" <? echo $iam_type == '0' && $iam_type != ''?"selected":""?>>FREE</option>
                                    <option value="1" <? echo $iam_type == 1?"selected":""?>>BASIC</option>
                                    <option value="2" <? echo $iam_type == 2?"selected":""?>>가맹점</option>
                                    <option value="3" <? echo $iam_type == 3?"selected":""?>>슈퍼바이저</option>
                                    <option value="4" <? echo $iam_type == 4?"selected":""?>>홍보대사</option>
                                </select>
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                    <div>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="deleteMultiRow()"> 선택삭제</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="setMultiRow('0')"> 일괄해제</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="setMultiRow('1')"> 일괄승인</button>
                    </div>
                </div>
            </div>

            <div class="ad_layer1" style="display:none" style="overflow-y:auto !important;height:150px !important;">
                <table id="phone_table" class="table table-bordered table-striped" style="background:#fff !important">
                    <colgroup>
                        <col width="60px">
                        <col width="100px">
                        <col width="180px">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>번호</th>
                            <th>추가폰</th>
                            <th>설치일자</th>
                        </tr>
                    </thead>
                    <tbody id="phone_list">
                    </tbody>
                </table>
            </div>
            <?
            if($_REQUEST['dir'] == "desc"){
            $dir = "asc";
            } 	else{
            $dir = "desc";
            }
            ?>
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="60px">
                                <col width="140px">
                                <col width="50px">
                                <col width="50px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="70px">
                                <col width="70px">
                                <col width="50px">
                                <col width="120px">
                                <col width="120px">
                                <col width="120px">
                                <col width="40px">
                                <col width="40px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th style="text-align: center"><input type="checkbox" class="check" id="check_all_member" value="0"><br>번호</th>
                                    <th style="text-align: center">회원정보</th>
                                    <th style="text-align: center">셀링/IAM 소속</th>
                                    <th style="text-align: center">아이디</th>
                                    <th style="text-align: center">이름</th>
                                    <th style="text-align: center">전화번호</th>
                                    <th style="text-align: center">구분</th>
                                    <th style="text-align: center"><a href="?orderField=tcnt&dir=<?=$dir?>" class="sort-by">추가폰</a></th>
                                    <th style="text-align: center">약정</th>
                                    <th style="text-align: center">총결제</th>
                                    <th style="text-align: center">추천인</th>
                                    <th style="text-align: center">신청일<br>승인일</th>
                                    <th style="text-align: center">오토회원</th>
                                    <th style="text-align: center">탈퇴회원</th>
                                    <? if($_SESSION['one_member_admin_id'] != "" && $_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                                    <th style="text-align: center">동기</th>
                                    <? }?>
                                    <th style="text-align: center">등급</th>
                                    <th style="text-align: center">승인</th>
                                    <th style="text-align: center">레벨등급</th>
                                    <th style="text-align: center">지원등급</th>
                                    <th style="text-align: center">스페셜등급</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $identifier ? " AND a.mem_id LIKE '%".$identifier."%'" : null;
                                $searchStr .= $rec_id ? " AND a.recommend_id like '%".$rec_id."%'" : null;
                                $searchStr .= $n_key ? " AND a.mem_name like '%".$n_key."%'" : null;
                                $searchStr .= $p_key ? " AND a.mem_phone like '%".$p_key."%'" : null;
                                $searchStr .= $s_key ? " AND a.site like '%" .$s_key."%'" : null;
                                $searchStr .= $i_key ? " AND a.site_iam like  '%" .$i_key."%'" : null;
                                $searchStr .= $g_key ? " AND a.mem_id in (select mem_id from gn_group_member g_mem left join gn_group_info g_info on g_info.idx = g_mem.group_id where g_info.name like '%".$g_key."%')" : null;
                                if($iam_type != '')
                                $searchStr .= $iam_type != 'a' ? " AND a.iam_type = ".$iam_type : null;
                                if ($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == $HTTP_HOST) {
                                    $do = explode(".", $HTTP_HOST);
                                    $searchStr .= " and a.site = '$do[0]'";
                                }
                                if($case == "1") {
                                    $searchStr .= " and (REPLACE(a.mem_phone,'-','')=REPLACE(b.sendnum, '-','') and b.sendnum is not null and b.sendnum != '')";
                                } else if($case == "2") {
                                    $searchStr .= " and (b.sendnum is null or b.sendnum = '')";
                                }else if ($case == "3") {
                                    $searchStr .= " and (REPLACE(a.mem_phone,'-','')!=REPLACE(b.sendnum,'-','') and b.sendnum is not null and b.sendnum != '')";
                                }else if ($case == "4") {
                                    $searchStr .= " and (a.is_leave = 'Y')";
                                }else if ($case == "5") {
                                    $searchStr .= " and (a.mem_type = 'A')";
                                }
                                $order = $order?$order:"desc";
                                $query = "SELECT SQL_CALC_FOUND_ROWS
                                            a.mem_leb, a.mem_code, a.mem_id, a.mem_name, a.mem_nick, a.mem_phone, a.mem_id, a.web_pwd,a.site,a.site_iam, a.recommend_id,a.mem_type, a.special_type, a.gwc_leb, a.gwc_req_leb, a.gwc_state,
                                            a.login_date, a.visited, a.level, a.fujia_date1, a.fujia_date2, a.is_leave, a.first_regist,a.iam_type,a.gwc_req_date,a.gwc_accept_date,
                                            (select count(*) from Gn_MMS_Number where 1=1 and ( not (cnt1 = 10 and cnt2 = 20)) and mem_id =a.mem_id) tcnt,
                                            b.memo, b.sendnum, b.memo2, a.service_type, a.mem_iam_type,a.is_leave
                                          FROM Gn_Member a LEFT JOIN Gn_MMS_Number b on (b.mem_id =a.mem_id and REPLACE(a.mem_phone, '-', '')=b.sendnum)
                                          WHERE (gwc_leb!=0 or gwc_req_leb!=0) $searchStr";
                                $res	    = mysql_query($query);
                                $totalCnt	=  mysql_num_rows($res);
                                $limitStr   = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number		= $totalCnt - ($nowPage - 1) * $pageCnt;
                                // rcs s
                                if(!$orderField){
                                    $orderField = "a.gwc_req_date";
                                }
                                $orderQuery .= " ORDER BY $orderField $dir ";
                                //rcs e
                                $i = 1;
                                $query .= $orderQuery;
                                $excel_sql = "";
                                $excel_sql = $query;
                                $query .= $limitStr;
                                $res = mysql_query($query);
                                while($row = mysql_fetch_array($res)) {
                                    // =====================  유료결제건 시작 =====================
                                    $sql = "select phone_cnt,onestep2 from tjd_pay_result where buyer_id = '".$row['mem_id']."' and end_date > '$date_today' and end_status='Y' order by end_date desc limit 1";
                                    $res_result = mysql_query($sql);
                                    $pay_result = mysql_fetch_row($res_result);
                                    mysql_free_result($res_result);
                                    if($pay_result == ""){
                                        $buyMMSCount = "OFF";
                                    }else{
                                        //$buyMMSCount = $pay_result['phone_cnt'];
                                        $buyMMSCount = $pay_result[1];
                                    }
                                    // ===================== 유료결제건 끝 =====================
                                    // =====================  총결제금액 시작 =====================
                                    $sql = "select sum(TotPrice) totPrice from tjd_pay_result where buyer_id = '".$row['mem_id']."' and end_status='Y'";
                                    $res_result = mysql_query($sql);
                                    $totPriceRow = mysql_fetch_row($res_result);
                                    mysql_free_result($res_result);
                                    $totPrice = $totPriceRow[0];
                                    // ===================== 총결제금액 끝 =====================
                                    // 부가서비스 이용 여부 확인
                                    // tjd_pay_result.fujia_status
                                    //if($row['fujia_date2'] >= date("Y-m-d H:i:s")) {
                                    //    $add_opt = "사용";
                                    //} else {
                                    //    $add_opt = "미사용";
                                    //}
                                ?>
                                    <tr>
                                        <td style="text-align:center"><input type="checkbox" class="check" id="check_one_member" name="" value="<?=$row['mem_code']?>"><br><?=$number--?></td>
                                        <td style="text-align:center">
                                            <? if ($_SESSION['one_member_admin_id'] != "") { ?>
                                                <a href="member_detail.php?mem_code=<?=$row['mem_code']?>&sendnum=<?=$row['sendnum']?>">수정</a><br>
                                                <a href="javascript:logout_member_info('<?=$row['mem_code']?>')">탈퇴</a><br>
                                                <a href="javascript:del_member_info('<?=$row['mem_code']?>')">삭제</a>
                                            <?} else {?>
                                                <a href="smember_detail.php?mem_code=<?=$row['mem_code']?>&sendnum=<?=$row['sendnum']?>">수정</a>
                                            <?}?>    
                                        </td>
                                        <td><?=$row['site']."/".$row['site_iam']?></td>
                                        <td>
                                            <? if($_SESSION['one_member_admin_id'] == "" || $_SESSION['one_member_admin_id'] == "onlyonemaket"){?>
                                                <?=$row['mem_id']?>
                                            <? }else{?>
                                                <a href="javascript:loginGo('<?=$row['mem_id']?>', '<?=$row['web_pwd']?>', '<?=$row['mem_code']?>');"><?=$row['mem_id']?></a>
                                            <? }?>
                                        </td>
                                        <td><?=$row['mem_name']?></td>
                                        <td>
                                            <?=str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""?str_replace("-", "",$row['mem_phone']):$row['sendnum']?>
                                        </td>
                                        <td>
                                            <?=str_replace("-", "",$row['mem_phone'])==$row['sendnum'] || $row['sendnum'] == ""?"회원폰":"추가폰"?>
                                        </td>
                                        <td onclick="page_view('<?=$row['mem_id']?>');"><?=number_format($row['tcnt'])?> <? echo $row['memo'];?></td>
                                        <!--td><?=number_format($buyMMSCount)?></td-->
                                        <td><?=$buyMMSCount?></td>
                                        <td><?=number_format($totPrice)?></td>
                                        <td><?=$row['recommend_id']?></td>
                                        <td><?=$row['gwc_req_date']?>/<?=$row['gwc_accept_date']?></td>
                                        <td><?=$row['mem_type']=='A'?"오토":"셀프"?></td>
                                        <td><?=$row['is_leave']=='Y'?"탈퇴회원":"가입회원"?></td>
                                        <? if($_SESSION['one_member_admin_id'] != "" && $_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                                        <td>
                                            <a href="javascript:excel_down_p_group('<?=str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""?str_replace("-", "",$row['mem_phone']):$row['sendnum']?>','<?=$row['mem_id']?>')"><img src="/images/ico_xls.gif" border="0"></a>
                                        </td>
                                        <? }?>
                                        <!-- <td>
                                            <? if(str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""){?>
                                                <select name="mem_leb" id="mem_leb<?=$row['mem_code']?>">
                                                    <option value="22" <? echo $row['mem_leb'] == "22"?"selected":""?>>일반</option>
                                                    <option value="50" <? echo $row['mem_leb'] == "50"?"selected":""?>>코치</option>
                                                    <option value="21" <? echo $row['mem_leb'] == "21"?"selected":""?>>강사</option>
                                                    <option value="60" <? echo $row['mem_leb'] == "60"?"selected":""?>>홍보</option>
                                                </select>
                                            <? if($_SESSION['one_member_admin_id'] != "" && $_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                                                <input type="button" name="변경" value=" 변경 " onclick="changeLevel('<?=$row['mem_code']?>')">
                                            <? }
                                            }?>
                                        </td> -->
                                        <td>
                                            <!-- <button style="border: 1px solid #2196f3;padding: 2px;<?=$row['gwc_req_leb'] == $row['gwc_leb']?'':'background-color:lightblue;'?>" <?=$row['gwc_req_leb'] == $row['gwc_leb']?'disabled':''?> onclick="request_btn('<?=$row['mem_code']?>')">신청</button> -->
                                            <?php
                                            // $style1 = '<span class="req_leb_txt_'.$row['mem_code'].'" style="border: 1px solid #2196f3;padding: 2px;margin-left: 3px;">';
                                            // if($row['gwc_req_leb'] == "0") echo $style1.'일반';
                                            // $style = '<span class="req_leb_txt_'.$row['mem_code'].'" style="border: 1px solid #2196f3;padding: 2px;margin-left: 3px;">';
                                            // if($row['gwc_req_leb'] == "1") echo $style.'가입';
                                            // if($row['gwc_req_leb'] == "2") echo $style.'소비';
                                            // if($row['gwc_req_leb'] == "3") echo $style.'공급';
                                            // if($row['gwc_req_leb'] == "4") echo $style.'판매';
                                            // if($row['gwc_req_leb'] == "5") echo $style.'팀';
                                            // if($row['gwc_req_leb'] == "6") echo $style.'그룹';
                                            // if($row['gwc_req_leb'] == "7") echo $style.'지역';
                                            // if($row['gwc_req_leb'] == "8") echo $style.'국가';
                                            // if($row['gwc_req_leb'] == "9") echo $style.'국제';
                                            ?>
                                            </span>
                                            <? if(str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""){?>
                                                <select name="gwc_leb" id="gwc_leb<?=$row['mem_code']?>">
                                                    <option value="4" <? echo $row['gwc_leb'] == "4"?"selected":""?>>일반</option>
                                                    <option value="1" <? echo $row['gwc_leb'] == "1"?"selected":""?>>굿슈머</option>
                                                    <option value="2" <? echo $row['gwc_leb'] == "2"?"selected":""?>>공급사</option>
                                                    <option value="3" <? echo $row['gwc_leb'] == "3"?"selected":""?>>센터</option>
                                                    <!-- <option value="4" <? echo $row['gwc_leb'] == "4"?"selected":""?>>판매회원</option>
                                                    <option value="5" <? echo $row['gwc_leb'] == "5"?"selected":""?>>팀리더</option>
                                                    <option value="6" <? echo $row['gwc_leb'] == "6"?"selected":""?>>그룹리더</option>
                                                    <option value="7" <? echo $row['gwc_leb'] == "7"?"selected":""?>>지역리더</option>
                                                    <option value="8" <? echo $row['gwc_leb'] == "8"?"selected":""?>>국가리더</option>
                                                    <option value="9" <? echo $row['gwc_leb'] == "9"?"selected":""?>>국제리더</option> -->
                                                </select>
                                            <? if($_SESSION['one_member_admin_id'] != "" && $_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                                                <input type="button" name="변경" value=" 변경 " onclick="changegwcLevel('<?=$row['mem_code']?>')">
                                            <? }
                                            }?>
                                        </td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" name="status" id="stauts_<?php echo $row['mem_code'];?>" value="<?php echo $row['mem_code'];?>" <?php echo $row['gwc_state']=="1"?"checked":""?>>
                                                <span class="slider round" name="status_round" id="stauts_round_<?php echo $row['mem_code'];?>"></span>
                                            </label>
                                        </td>
                                        <td>
                                        <? if(str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""){?>
                                            <select name="service_type" id="service_type<?=$row['mem_code']?>">
                                                <option value="0" <? echo $row['service_type'] == "0"?"selected":""?>>FREE</option>
                                                <option value="1" <? echo $row['service_type'] == "1"?"selected":""?>>이용자</option>
                                                <option value="2" <? echo $row['service_type'] == "2"?"selected":""?>>리셀러</option>
                                                <option value="3" <? echo $row['service_type'] == "3"?"selected":""?>>분양자</option>
                                            </select>
                                            <? if($_SESSION['one_member_admin_id'] != "" && $_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                                                <input type="button" name="변경" value=" 변경 " onclick="changeServiceType('<?=$row['mem_code']?>')">
                                            <? }
                                        }?>
                                        </td>
                                        <td>
                                            <? if (str_replace("-", "", $row['mem_phone']) == $row['sendnum'] || $row['sendnum'] == "") { ?>
                                            <select name="iam_pay_type" id="iam_pay_type<?=$row['mem_code']?>">
                                                    <option value="0" <? echo $row['iam_type'] == 0 ? "selected" : "" ?>>FREE</option>
                                                    <option value="1" <? echo $row['iam_type'] == 1 ? "selected" : "" ?>>BASIC</option>
                                                    <option value="2" <? echo $row['iam_type'] == 2 ? "selected" : "" ?>>가맹점</option>
                                                    <option value="3" <? echo $row['iam_type'] == 3 ? "selected" : "" ?>>슈퍼바이저</option>
                                                    <option value="4" <? echo $row['iam_type'] == 4 ? "selected" : "" ?>>홍보대사</option>
                                            </select>
                                            <? if($_SESSION['one_member_admin_id'] != "" && $_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                                                <input type="button" name="변경" value=" 변경 " onclick="changeIamType('<?=$row['mem_code']?>')">
                                            <? }
                                        }?> 
                                        </td>
                                        <td style="width:100px;">
                                            <? if (str_replace("-", "", $row['mem_phone']) == $row['sendnum'] || $row['sendnum'] == "") {
                                            $special_type = "";
                                                $special_arr = explode(",", $row['special_type']);
                                            for($si = 0; $si < count($special_arr); $si++){
                                                if($special_arr[$si] == 1) $special_type .= "판매자";
                                                if($special_arr[$si] == 2) $special_type .= "전문가";
                                                if($special_arr[$si] == 3) $special_type .= "구인자";
                                                if($special_arr[$si] == 4) $special_type .= "구직자";
                                                if($special_arr[$si] == 5) $special_type .= "리포터";
                                                if($si != count($special_arr) - 1) $special_type .= ",";
                                            }
                                            echo $special_type;
                                            ?>
                                            <!-- <select name="special_type<?=$row['special_type']?>" id="special_type<?=$row['mem_code']?>">
                                                <option value="0" <? echo $row['special_type'] == 0 ? "selected" : "" ?>>판매자</option>
                                                <option value="1" <? echo $row['special_type'] == 1 ? "selected" : "" ?>>전문가</option>
                                                <option value="2" <? echo $row['special_type'] == 2 ? "selected" : "" ?>>구인자</option>
                                                <option value="3" <? echo $row['special_type'] == 3 ? "selected" : "" ?>>구직자</option>
                                            </select> -->
                                            <? if($_SESSION['one_member_admin_id'] != "" && $_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
                                                <!-- <input type="button" name="변경" value=" 변경 " onclick="changeSpecialType('<?=$row['mem_code']?>')"> -->
                                            <? }
                                        }?> 
                                        </td>
                                    </tr>
                                    <?
                                    $i++;
                                }
                                if($i == 1) {
                                ?>
                                    <tr>
                                        <td colspan="11" style="text-align:center;background:#fff">
                                            등록된 내용이 없습니다.
                                        </td>
                                    </tr>
                                <?}?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.row -->
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                </div>
                <div class="col-sm-7">
                    <?echo drawPagingAdminNavi($totalCnt, $nowPage,$pageCnt);?>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->

    <!-- 비번입력창 -->
    <div class="modal inmodal" id="modal-password" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="width:300px">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">2차비번 입력</h4>
                </div>
                <div class="modal-body">
                    <div>
                    <input type="password" class="form-control" style="width:250px" name="sec_pwd" id="sec_pwd" >
                    <input type="hidden" name="user_id"   id="user_id"    />
                    <input type="hidden" name="user_pwd" id="user_pwd" />
                    <input type="hidden" name="user_code" id="user_code"  />            
                    </div>

                </div>
                <div class="modal-footer" style="text-align: center;">
                    <a style="color:white;" class="btn btn-primary" onclick="onConfirm()" data-dismiss="modal">확인</a>
                    
                </div>
            </div>
        </div>
    </div>

    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="one_member_id" id="one_member_id" value="" />
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>  
</div><!--wrapper-->
    