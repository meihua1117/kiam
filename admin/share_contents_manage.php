<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");

$time = time() - (86400 * 365);
$comp_date = date("Y-m-d", $time);

$sql_share = "select * from share_contents_mng where share_obj='장수군청산림과'";
$res_share = mysql_query($sql_share);
$row_share = mysql_fetch_array($res_share);

if($row_share['end_date'] < $date_today){
    $sql_update = "update share_contents_mng set share_state=2 where share_obj='장수군청산림과'";
    mysql_query($sql_update);
}
else{
    $sql_update = "update share_contents_mng set share_state=1 where share_obj='장수군청산림과'";
    mysql_query($sql_update);
}

$sql_key_search_work = get_search_key($row_share['work_key']);
$sql_key_search_public = get_search_key($row_share['public_key']);
$sql_key_search_edu = get_search_key($row_share['edu_key']);
$sql_key_search_other = get_search_key($row_share['other_key']);

$sql_key_search = " and ((web_type='지원사업'".$sql_key_search_work.") or (web_type='행사교육'".$sql_key_search_edu.") or (web_type='입찰공고'".$sql_key_search_public.") or (web_type='기타정보'".$sql_key_search_other."))";

$sql_count1 = "select * from get_crawler_bizinfo where allow_state=1 and allow_state_biz=1 and reg_date>'{$comp_date}'".$sql_key_search;
$row_cnt1 = mysql_query($sql_count1);
$contents_cnt1 = mysql_num_rows($row_cnt1);

$sql_count2 = "select * from get_crawler_bizinfo where allow_state=1 and allow_state_biz=0 and reg_date>'{$comp_date}'".$sql_key_search;
$row_cnt2 = mysql_query($sql_count2);
$contents_cnt2 = mysql_num_rows($row_cnt2);

$contents_cnt = $contents_cnt1 * 1 + $contents_cnt2 * 1;
//
//function get_search_key($key){
//    $sql_key_search = "";
//    if($key != ""){
//        if(strpos($key, ",") !== false){
//            $key_arr = explode(",", $key);
//            for($i = 0; $i < count($key_arr); $i++){
//                if($i == count($key_arr) - 1){
//                    $sql_key_search .= " work_name like '%".$key_arr[$i]."%'";
//                }
//                else{
//                    $sql_key_search .= " work_name like '%".$key_arr[$i]."%' or";
//                }
//            }
//        }
//        else{
//            $key_arr[0] = $key;
//            $sql_key_search .= " work_name like '%".$key_arr[0]."%'";
//        }
//        $sql_key_search = " and (".$sql_key_search.")";
//    }
//    return $sql_key_search;
//}
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    $(function(){
        $('.switch').on("change", function() {
            var id = $(this).find("input[type=checkbox]").val();
            var status = $(this).find("input[type=checkbox]").is(":checked")==true?"N":"Y";
            $.ajax({
                type:"POST",
                url:"/admin/ajax/allow_contents_stop.php",
                data:{
                    share:true,
                    id:id
                },
                success:function(data){
                    alert('신청되었습니다.');location.reload();
                }
            })
        });
    });
    function goPage(pgNum) {
        location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&type=<?php echo $type;?>&orderField=<?=$orderField;?>&dir=<?=$dir;?>";
    }

    //주소록 다운
    function excel_down_p_group(pno,one_member_id){
        $($(".loading_div")[0]).show();
        $($(".loading_div")[0]).css('z-index',10000);
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yy = today.getFullYear().toString().substr(2,2);
        if(dd<10) {
            dd='0'+dd
        }
        if(mm<10) {
            mm='0'+mm
        }

        $.ajax({
            type:"POST",
            dataType : 'json',
            url:"/ajax/ajax_session_admin.php",
            data:{
                group_create_ok:"ok",
                group_create_ok_nums:pno,
                group_create_ok_name:pno.substr(3,8)+'_'+''+ mm+''+dd,
                one_member_id:one_member_id
            },
            success:function(data){
                $($(".loading_div")[0]).hide();
                $('#one_member_id').val(one_member_id);
                parent.excel_down('/excel_down/excel_down_.php?down_type=1&one_member_id='+one_member_id,data.idx);
            }

        });
    }
    function show_share() {
        location.href = "register_share.php";
    }
    $(function() {
        var contHeaderH = $(".main-header").height();
        var navH = $(".navbar").height();
        if(navH != contHeaderH)
            contHeaderH += navH - 50;
        $(".content-wrapper").css("margin-top",contHeaderH);
        var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 250;
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
    input, select, textarea {
        vertical-align: middle;
        border: 1px solid #CCC;
    }
    /*user agent stylesheet*/
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
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
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
                콘텐츠공급관리
                <small>생성된 콘텐츠를 공급합니다.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">콘텐츠공급관리</li>
            </ol>
        </section>

        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
            <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />
            <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />
            <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />
        </form>

        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="show_share()">
                        공급사 등록
                    </button>

                    <form method="get" name="search_form" id="search_form">
                        <div class="box-tools">
                            <div class="input-group" style="width: 250px;">
                                <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="정보출처/정보구분/사업명/게시기관" value="<?php echo $search_key;?>">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="60px">
                                <col width="100px">
                                <col width="100px">
                                <col width="80px">
                                <col width="150px">
                                <col width="150px">
                                <col width="150px">
                                <col width="150px">
                                <col width="60px">
                                <col width="70px">
                                <col width="70px">
                                <col width="70px">
                                <col width="70px">
                                <col width="70px">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>NO</th>
                                <th>공급대상</th>
                                <th>공급도메인</th>
                                <th>서버위치</th>
                                <th>지원사업</th>
                                <th>입찰정보</th>
                                <th>행사/교육</th>
                                <th>관련정보</th>
                                <th>게시일자</th>
                                <th>종료일자</th>
                                <th>콘텐츠수</th>
                                <th>담당자</th>
                                <th>승인확인</th>
                                <th>수정/삭제</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                            $startPage = $nowPage?$nowPage:1;
                            $pageCnt = 20;

                            $searchStr .= $search_key ? " AND (info_source LIKE '%".$search_key."%' or web_type like '%".$search_key."%' or org_name like '%".$search_key."%' or work_name like '%".$search_key."%' )" : null;

                            $sql = "select * from share_contents_mng where 1=1";
                            $query = $sql.$searchStr;
                            $res = mysql_query($query);
                            $totalCnt = mysql_num_rows($res);

                            $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                            $number = $totalCnt - ($nowPage - 1) * $pageCnt;
                            $orderQuery .= " ORDER BY id DESC $limitStr ";

                            $query = $sql.$searchStr.$orderQuery;
                            $result = mysql_query($query);
                            $i = 1;
                            while($row = mysql_fetch_array($result)){
                            ?>
                                <tr>
                                    <th><?=$number--?></th>
                                    <th><?=$row['share_obj']?></th>
                                    <th><a href="<?=$row['share_domain']?>" target="_blank"><?=$row['share_domain']?></a></th>
                                    <th><?=$row['server_position']?></th>
                                    <th><?=$row['work_key']?></th>
                                    <th><?=$row['public_key']?></th>
                                    <th><?=$row['edu_key']?></th>
                                    <th><?=$row['other_key']?></th>
                                    <th><?=$row['reg_date']?></th>
                                    <th><?=$row['end_date']?></th>
                                    <th><?=$contents_cnt?></th>
                                    <th><?=$row['manager']?></th>
                                    <th>
                                    <?php
                                    if($row['share_state'] == 1){
                                        $checked = "checked";
                                    }
                                    else{
                                        $checked = "";
                                    }
                                    ?>
                                        <label class="switch">
                                            <input type="checkbox" name="status" id="stauts_<?php echo $row['id'];?>" value="<?php echo $row['id'];?>" <?=$checked?>>
                                            <span class="slider round" name="status_round" id="stauts_round_<?php echo $row['id'];?>"></span>
                                        </label>
                                    </th>
                                    <td>
                                        <a href="register_share.php?id=<?=$row['id']?>">수정</a> /
                                        <a href="javascript:del_making_db('<?=$row['id']?>')">삭제</a>
                                    </td>
                                </tr>
                            <?
                            $i++;
                            }
                            if($i == 1) {?>
                                <tr>
                                    <td colspan="10" style="text-align:center;background:#fff">
                                        등록된 내용이 없습니다.
                                    </td>
                                </tr>
                            <?php }?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->

            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                </div>
                <div class="col-sm-7">
                    <?
                    echo drawPagingAdminNavi($totalCnt, $nowPage);
                    ?>
                </div>
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
    setInterval(() => {
        if($('#btn_get').prop("disabled") ==  true) {
            $.ajax({
                type: "POST",
                url: "get_iam_crawling_status.php",
                dataType: "json",
                success: function (data) {
                    console.log(data.status);
                    if (data.status == 0) {
                       location.reload();
                    }
                },
                error: function () {
                    //alert('초기화 실패');
                }
            });
        }
    }, 1000);
</script>
     