<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$sql_end_update = "update get_crawler_bizinfo set work_status=2 where end_date<now()";
mysqli_query($self_con,$sql_end_update);
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
                    id:id
                },
                success:function(data){
                    alert('신청되었습니다.');location.reload();
                }
            })
        });
    });

    function goPage(pgNum) {
        location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&type=<?php echo $type;?>&orderField=<?=$orderField;?>&dir=<?=$dir;?>&show_end=<?=$_GET['show_end']?>";
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
                콘텐츠노출목록
                <small>생성된 콘텐츠를 관리합니다.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">콘텐츠노출목록</li>
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
                    <form method="get" name="search_form" id="search_form">
                        <div class="box-tools">
                            <div class="input-group" style="width: 600px; display:flex;">
                                <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="정보출처/정보구분/사업명/게시기관" value="<?php echo $search_key;?>">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                </div>
                                <?
                                $checked1 = "";
                                $checked2 = "";
                                if(isset($_GET['show_end'])){
                                    if($_GET['show_end'] == "false")$checked1 = "checked";
                                    if($_GET['show_end'] == "true")$checked2 = "checked";
                                }
                                ?>
                                <div style="width:70%;margin-left:30px;">
                                    <input type="radio" name="exist_data" id="exist_end" style="vertical-align: top;" <?=$checked1?>>
                                    <label for="exist_end"><a href="?search_key=<?php echo $_GET['search_key'];?>&orderField=info_source&dir=<?=$dir?>&show_end=false">진행중 정보만 보기</a></label>
                                </div>
                                <div style="width:70%;margin-left:30px;">
                                    <input type="radio" name="exist_data" id="show_end" style="vertical-align: top;" <?=$checked2?>>
                                    <label for="show_end"><a href="?search_key=<?php echo $_GET['search_key'];?>&orderField=info_source&dir=<?=$dir?>&show_end=true">마감된 정보만 보기</a></label>
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
                                <col width="60px">
                                <col width="100px">
                                <col width="100px">
                                <col width="200px">
                                <col width="150px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="60px">
                                <col width="70px">
                                <col width="50px">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>NO</th>
                                <th><a href="?search_key=<?php echo $_GET['search_key'];?>&orderField=info_source&dir=<?=$dir?>&show_end=<?=$_GET['show_end']?>" class="sort-by">정보사이트</a></th>
                                <th><a href="?search_key=<?php echo $_GET['search_key'];?>&orderField=web_type&dir=<?=$dir?>&show_end=<?=$_GET['show_end']?>" class="sort-by">정보구분</a></th>
                                <th><a href="?search_key=<?php echo $_GET['search_key'];?>&orderField=work_name&dir=<?=$dir?>&show_end=<?=$_GET['show_end']?>" class="sort-by">사업명칭</a></th>
                                <th><a href="?search_key=<?php echo $_GET['search_key'];?>&orderField=reg_date&dir=<?=$dir?>&show_end=<?=$_GET['show_end']?>" class="sort-by">등록일시</a></th>
                                <th><a href="?search_key=<?php echo $_GET['search_key'];?>&orderField=end_date&dir=<?=$dir?>&show_end=<?=$_GET['show_end']?>" class="sort-by">마감일시</a></th>
                                <th><a href="?search_key=<?php echo $_GET['search_key'];?>&orderField=org_name&dir=<?=$dir?>&show_end=<?=$_GET['show_end']?>" class="sort-by">출처</a></th>
                                <th><a href="?search_key=<?php echo $_GET['search_key'];?>&orderField=search_key&dir=<?=$dir?>&show_end=<?=$_GET['show_end']?>" class="sort-by">검색어</a></th>
                                <th><a href="?search_key=<?php echo $_GET['search_key'];?>&orderField=get_date&dir=<?=$dir?>&show_end=<?=$_GET['show_end']?>" class="sort-by">추출시간</a></th>
                                <th>승인확인</th>
                                <th>수정/삭제</th>
                                <th>설정</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                            $startPage = $nowPage?$nowPage:1;
                            $pageCnt = 20;

                            if(isset($_GET['show_end'])){
                                if($_GET['show_end'] == "false")$searchStr .= " AND a.work_status=1";
                                if($_GET['show_end'] == "true")$searchStr .= " AND a.work_status=2";
                            }

                            $searchStr .= $search_key ? " AND (a.info_source LIKE '%".$search_key."%' or a.web_type like '%".$search_key."%' or a.org_name like '%".$search_key."%' or a.work_name like '%".$search_key."%' )" : null;

                            $sql = "select a.*, b.search_key from get_crawler_bizinfo a inner join reg_biz_contents b on a.reg_id=b.id where 1=1";
                            $query = $sql.$searchStr;
                            $res = mysqli_query($self_con,$query);
                            $totalCnt = mysqli_num_rows($res);

                            $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                            $number = $totalCnt - ($nowPage - 1) * $pageCnt;

                            if(!$orderField)
                                    $orderField = "a.get_date";
                            $orderQuery .= " ORDER BY $orderField $dir $limitStr";
                            $query = $sql.$searchStr.$orderQuery;
                            $result = mysqli_query($self_con,$query);

                            $i = 1;
                            while($row = mysqli_fetch_array($result)){
                                if($row['work_status'] == 1){
                                    $work_status = "진행중인사업";
                                }
                                else if($row['work_status'] == 2){
                                    $work_status = "종료된사업";
                                }
                            ?>
                                <tr>
                                    <th><?=$number--?></th>
                                    <th><?=$row['info_source']?></th>
                                    <th><?=$row['web_type']?></th>
                                    <th><a href="<?=$row['detail_link']?>" target="_blank"><?=$row['work_name']?></a></th>
                                    <th><?=$row['reg_date']?></th>
                                    <th><?=$row['end_date']?></th>
                                    <th><?=$row['org_name']?></th>
                                    <th><?=$row['search_key']?></th>
                                    <th><?=$row['get_date']?></th>
                                    <th>
                                    <?php
                                    if($row['allow_state'] == 1){
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
                                        <a href="register_allow_contents.php?id=<?=$row['id']?>">수정</a> /
                                        <a href="javascript:del_making_db('<?=$row['id']?>')">삭제</a>
                                    </td>
                                    <td>
                                        <a href="javascript:newopen(<?=$row['id']?>)">보기</a>
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

    function newopen(id){
        $.ajax({
            type:"POST",
            url:"/admin/ajax/allow_contents_update.php",
            dataType:"json",
            data:{check:true, id:id},
            success:function(data){
                console.log(data);
                if(data == 0){
                    alert("삭제되었습니다.");
                    return;
                }
                str = "/event/set_biz_contents.php?id="+data;
                window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=710");
            }
        });
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
   