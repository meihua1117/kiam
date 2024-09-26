<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
date_default_timezone_set('Asia/Seoul');
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_today1=date("Y-m-d H:i:s");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    function goPage(pgNum) {
        location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&type=<?php echo $type;?>&orderField=<?=$orderField;?>&dir=<?=$dir;?>";
    }
    function show_getcon() {
        // $("#register_contents").prop('hidden', false);
        // $("#key_type").focus();
        location.href = "register_contents.php";
    }

    $(function(){
        $('.switch').on("change", function() {
            var id = $(this).find("input[type=checkbox]").val();
            var status = $(this).find("input[type=checkbox]").is(":checked")==true?"N":"Y";
            $.ajax({
                type:"POST",
                url:"/admin/ajax/allow_contents_stop.php",
                data:{
                    get_con:true,
                    id:id
                },
                success:function(data){
                    alert('신청되었습니다.');
                    location.reload();
                }
            })
        });
    });
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
    .loading_div {
        display:none;
        position: fixed;
        left: 50%;
        top: 50%;
        display: none;
        z-index: 1000;
    }
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
                콘텐츠수집 관리
                <small>기업마당 페이지 콘텐츠 수집 관리합니다.</small>
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
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="show_getcon()">
                        콘텐츠수집등록
                    </button>

                    <form method="get" name="search_form" id="search_form">
                        <div class="box-tools">
                            <div class="input-group" style="width: 250px;">
                                <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="정보출처/정보구분/검색어" value="<?php echo $search_key;?>">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?$dir = $_REQUEST['dir'] == "desc" ? "asc" : "desc";?>
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="30px">
                                <col width="80px">
                                <col width="80px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="70px">
                                <col width="100px">
                                <col width="80px">
                                <col width="80px">
                                <col width="80px">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>NO</th>
                                <th><a href="?orderField=info_source&dir=<?=$dir?>" class="sort-by">정보사이트</a></th>
                                <th><a href="?orderField=info_type&dir=<?=$dir?>" class="sort-by">정보구분</a></th>
                                <th><a href="?orderField=web_address&dir=<?=$dir?>" class="sort-by">웹주소</a></th>
                                <th><a href="?orderField=search_key&dir=<?=$dir?>" class="sort-by">검색어</a></th>
                                <th><a href="?orderField=status&dir=<?=$dir?>" class="sort-by">진행/종료</a></th>
                                <th><a href="?orderField=keyword&dir=<?=$dir?>" class="sort-by">조회키워드</a></th>
                                <th><a href="?orderField=get_time&dir=<?=$dir?>" class="sort-by">수집횟수</a></th>
                                <th><a href="?orderField=memo&dir=<?=$dir?>" class="sort-by">메모</a></th>
                                <th><a href="?orderField=up_date&dir=<?=$dir?>" class="sort-by">등록일시</a></th>
                                <th>승인확인</th>
                                <th>수정/삭제</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                            $startPage = $nowPage?$nowPage:1;
                            $pageCnt = 20;

                            // 검색 조건을 적용한다.                            
                            $searchStr .= $search_key ? " AND (info_source LIKE '%".$search_key."%' or info_type like '%".$search_key."%' or search_key like '%".$search_key."%' )" : null;

                            $sql = "select * from reg_biz_contents where 1=1";
                            $query = $sql.$searchStr;
                            $res = mysqli_query($self_con, $query);
                            $totalCnt = mysqli_num_rows($res);

                            $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                            $number = $totalCnt - ($nowPage - 1) * $pageCnt;

                            if(!$orderField)
                                    $orderField = "up_date";
                            $orderQuery .= " ORDER BY $orderField $dir $limitStr";
                            $query = $sql.$searchStr.$orderQuery;
                            $result = mysqli_query($self_con, $query);

                            $i = 1;

                            while($row = mysqli_fetch_array($result)){
                                if($row['status'] == 1){
                                    $status = "진행사업";
                                }
                                else if($row['status'] == 2){
                                    $status = "종료된사업";
                                }
                                else if($row['status'] == 3){
                                    $status = "전체사업";
                                }
                            ?>
                                <tr>
                                    <th><?=$i?></th>
                                    <th><?=$row['info_source']?></th>
                                    <th><?=$row['info_type']?></th>
                                    <th><a href="<?=$row['web_address']?>" target="_blank"><?=$row['web_address']?></a></th>
                                    <th><?=$row['search_key']?></th>
                                    <th><?=$status?></th>
                                    <th><?=$row['keyword']?></th>
                                    <th><?=$row['get_time']?></th>
                                    <th><?=$row['memo']?></th>
                                    <th><?=$row['up_date']?></th>
                                    <th>
                                    <?php
                                    if($row['work_status'] == 1){
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
                                        <a href="register_contents.php?reg_id=<?=$row['id']?>">수정</a> /
                                        <a href="javascript:del_making_db(<?=$row['id']?>)">삭제</a>
                                    </td>
                                </tr>
                                <?
                                $i++;
                            }                                
                            if($i == 1) {
                                ?>
                                <tr>
                                    <td colspan="10" style="text-align:center;background:#fff">
                                        등록된 내용이 없습니다.
                                    </td>
                                </tr>
                                <?
                            }
                            ?>
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
        </section><!-- /.content -->
    </div><!-- /.row -->
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
    
    function register_getcon(){
        alert("정확히 등록되었습니다.");
        location.reload();
    }
</script>
     