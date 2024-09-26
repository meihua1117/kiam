<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
if($_REQUEST['dir'] == "desc"){
    $dir = "asc";
} 	else{
    $dir = "desc";
}
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
//계정 삭제
function del_service(idx){
    var msg = confirm('정말로 삭제하시겠습니까?');
    if(msg){
        $.ajax({
            type:"POST",
            url:"/admin/ajax/service_save.php",
            data:{mode:"del",idx:idx},
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
function goPage(pgNum) {
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&orderField=<?=$orderField;?>&dir=<?=$dir;?>";
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
function deleteMultiRow() {
    var check_array = $("#example1").children().find(".check");
    var no_array = [];
    var index = 0;
    check_array.each(function(){
        if($(this).prop("checked") && $(this).val() > 0)
            no_array[index++] = $(this).val();
    });

    if(no_array.length == 0){
        alert("삭제할 업체를 선택하세요.");
        return;
    }
    if(confirm('삭제하시겠습니까?')) {
        $.ajax({
            type:"POST",
            url:"/admin/ajax/delete_func.php",
            dataType:"json",
            data:{admin:1, delete_name:"service_list", id:no_array.toString()},
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
</script>
<style>
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #ddd!important;
}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto !important;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important;}
</style>

<div class="loading_div" ><img src="/images/ajax-loader.gif"></div>
<div class="wrapper">
<!--div class="wrapper" style="display:flex; overflow: initial;"-->
    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>분양관리<small>분양정보를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">분양관리</li>
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
                <form method="get" name="search_form" id="search_form">
                    <div class="box-tools">
                        <div class="input-group" style="width: 250px;">
                            <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/이름">
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='service_detail.php';return false;"><i class="fa fa-download"></i> 등록</button>
                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="deleteMultiRow()"><i class="fa fa-download"></i> 선택삭제</button>
            </div>
            <div class="row" style="margin-top:10px">
                <div class="box">
                    <div class="box-body" style="overflow: auto !important">
                        <table id="example1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="60px">
                                <col width="200px">
                                <col width="200px">
                                <col width="50px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="200px">
                                <col width="200px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="check" id="check_all_member" value="0">번호</th>
                                    <th>수정/삭제</th>
                                    <th>업체명</th>
                                    <th>폰수</th>
                                    <th>이용료</th>
                                    <th>분류</th>
                                    <th>도메인</th>
                                    <th>사이트명</th>
                                    <th>아이디</th>
                                    <th>담당자</th>
                                    <th>전화번호</th>
                                    <th>상태</a></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                            $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                            $startPage = $nowPage?$nowPage:1;
                            $pageCnt = 20;

                            // 검색 조건을 적용한다.
                            $searchStr .= $search_key ? " AND (a.mem_id LIKE '%".$search_key."%' or a.company_name like '%".$search_key.
                                            "%' or a.service_name like '%".$search_key."%' or a.domain like '%".$search_key.
                                            "%' or a.site_name like '%".$search_key."%' or a.manage_name like '%".$search_key."%')" : null;
                            $order = $order?$order:"desc";
                            $query = "SELECT SQL_CALC_FOUND_ROWS * FROM Gn_Service a WHERE 1=1 $searchStr";
                            $res	    = mysqli_query($self_con, $query);
                            $totalCnt	=  mysqli_num_rows($res);
                            $limitStr   = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                            $number		= ($nowPage - 1) * $pageCnt;
                            $orderField = $orderField?$orderField:"a.idx";
                            $orderQuery .= " ORDER BY a.regdate $dir $limitStr";
                            $i = 1;
                            $query .= $orderQuery;
                            $res = mysqli_query($self_con, $query);
                            while($row = mysqli_fetch_array($res)) {
                            ?>
                                <tr>
                                    <td><input type="checkbox" class="check" id="check_one_member" name="" value="<?=$row['idx']?>"><?=$i + $number?></td>
                                    <td>
                                        <a href="service_detail.php?idx=<?=$row['idx']?>">수정</a> /
                                        <a href="javascript:del_service('<?=$row['idx']?>')">삭제</a></td>
                                    </td>
                                    <td><?=$row['company_name']?></td>
                                    <td><?=$row['phone_cnt']?></td>
                                    <td><?=$row['price']?></td>
                                    <td><?=$row['service_name']?></td>
                                    <td><a href="<?=$row['domain']?>" target="_blank"><?=$row['domain']?></a></td>
                                    <td><a href="<?=$row['site_name']?>" target="_blank"><?=$row['site_name']?></a></td>
                                    <td><a href="<?=$row['mem_id']?>" target="_blank"><?=$row['mem_id']?></a></td>
                                    <td><?=$row['manage_name']?></td>
                                    <td><?=$row['manage_cell']?></td>
                                    <td><?=$row['status']=="Y"?"서비스중":"정지중"?></td>
                                </tr>
                            <?
                                $i++;
                            }
                            if($i == 1) {?>
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
                <?
                    echo drawPagingAdminNavi($totalCnt, $nowPage);
                ?>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      
    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="one_member_id" id="one_member_id" value="" />
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div><!--./wrapper-->
<!-- Footer -->