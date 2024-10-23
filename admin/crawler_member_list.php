<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
//계정 삭제
function del_member_info(cmid){
	var msg = confirm('정말로 삭제하시겠습니까?');
	if(msg){
        $.ajax({
            type:"POST",
            url:"/admin/ajax/delete_func.php",
            data:{admin:1, delete_name:"crawler_member_list", id:cmid},
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
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&type=<?php echo $type;?>";
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
            data:{admin:1, delete_name:"crawler_member_list", id:no_array.toString()},
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
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #ddd!important;
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
            <h1>디버회원관리<small>디버회원관리을 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">디버회원관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="deleteMultiRow()"><i class="fa fa-download"></i> 선택삭제</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='crawler_member_detail.php'"><i class="fa fa-download"></i> 등록하기</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='?type=2'"> 정규회원</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='?type=1'"> 체험회원</button>
                    <form method="get" name="search_form" id="search_form">
                        <div class="box-tools">
                            <div class="input-group" style="width: 250px;">
                                <input type="hidden" name="type" value="<?php echo $type;?>">
                                <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/이름">
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
                                <col width="70px">
                                <col width="60px">
                                <col width="60px">
                                <col width="70px">
                                <col width="60px">
                                <col width="60px">
                                <col width="70px">
                                <col width="70px">
                                <col width="70px">
                                <col width="70px">
                                <col width="70px">
                                <col width="80px">
                                <col width="80px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="check" id="check_all_member" value="0">번호</th>
                                    <th>회원<br>정보</th>
                                    <th>회원<br>아이디</th>
                                    <th>이름</th>
                                    <th>폰승인<br>건수</th>
                                    <th>폰잔량<br>건수/추가</th>
                                    <th>폰수집<br>총건수</th>
                                    <th>메일<br>승인건수</th>
                                    <th>메일잔량<br>건수/추가</th>
                                    <th>메일<br>총수집건수</th>
                                    <th>쇼핑승인<br>건수</th>
                                    <th>쇼핑잔량<br>건수/추가</th>
                                    <th>쇼핑수집<br>총건수</th>
                                    <th>사용<br>여부</th>
                                    <th>종료<br>일시</th>
                                    <th>초기화</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;

                                // 검색 조건을 적용한다.
                                $searchStr .= $search_key ? " AND (user_id LIKE '%".$search_key."%' or user_name like '%".$search_key."%' )" : null;
                                $order = $order?$order:"desc";
                                if($type == 1)
                                    $searchStr .= " and use_cnt = '300' ";
                                else if($type == 2)
                                    $searchStr .= " and use_cnt > '300' ";
                                $query = "SELECT SQL_CALC_FOUND_ROWS * FROM crawler_member_real WHERE 1=1 $searchStr";
                                $res	    =   mysqli_query($self_con,$query);
                                $totalCnt	=   mysqli_num_rows($res);
                                $limitStr   = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number		= $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY cmid DESC $limitStr";
                                $i = 1;
                                $query .= $orderQuery;
                                $res = mysqli_query($self_con,$query);
                                while($row = mysqli_fetch_array($res)) {
                            ?>
                                <tr>
                                    <td><input type="checkbox" class="check" id="check_one_member" name="" value="<?=$row['cmid']?>"><?=$number--?></td>
                                    <td>
                                        <a href="crawler_member_detail.php?cmid=<?=$row['cmid']?>">수정</a> /
                                        <a href="javascript:del_member_info('<?=$row['cmid']?>')">삭제</a></td>
                                    <td><?=$row['user_id']?></td>
                                    <td><?=$row['user_name']?></td>
                                    <td><?=$row['use_cnt']?></td>
                                    <td><?=$row['use_cnt']-$row['monthly_cnt']."/".$row['extra_db_cnt']?></td>
                                    <td><?=$row['total_cnt']+$row['monthly_cnt']?></td>
                                    <td><?=$row['search_email_cnt']?></td>
                                    <td><?=$row['search_email_cnt']-$row['search_email_use_cnt']."/".$row['extra_email_cnt']?></td>
                                    <td><?=$row['search_email_total_cnt']+$row['search_email_use_cnt']?></td>
                                    <td><?=$row['shopping_cnt']?></td>
                                    <td><?=$row['shopping_cnt']-$row['shopping_use_cnt']."/".$row['extra_shopping_cnt']?></td>
                                    <td><?=$row['shopping_total_cnt']+$row['shopping_use_cnt']?></td>
                                    <td><?=$row['status']=="Y"?"사용":"미사용"?></td>
                                    <td><?=$row['term']?></td>
                                    <th><a href="javascript:;;" onclick="resetRow('<?=$row['cmid']?>')">초기화</a></th>
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
            </div><!-- /.col -->
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                </div>
                <div class="col-sm-7">
                	<?echo drawPagingAdminNavi($totalCnt, $nowPage);?>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
    