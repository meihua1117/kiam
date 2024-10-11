<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
function goPage(pgNum) {
    location.href = '?nowPage='+pgNum+'&search_leb=<?=$search_leb?>&search_start_date=<?=$search_start_date?>&search_end_date=<?=$search_end_date?>&search_status=<?=$search_status?>&search_key=<?=$search_key?>';
}
function payment_save(fm) {
    if(confirm('상태를 변경하시겠습니까?')) {
        $(fm).submit();
        return false;
    }
}  
function addcomma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
}
$(function(){
    $('.check_no').on("click",function(){
        if($(this).prop("id") == "check_all_member"){
            if($(this).prop("checked"))
                $('.check_no').prop("checked",true);
            else
                $('.check_no').prop("checked",false);
        }else if($(this).prop("id") == "check_one_member"){
            if(!$(this).prop("checked"))
                $('#check_all_member').prop("checked",false);
        }
    });
});
function deleteMultiRow() {
    if(confirm('삭제하시겠습니까?')) {
        var check_array = $("#example1").children().find(".check_no");
        var no_array = [];
        var index = 0;
        check_array.each(function(){
            if($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });

        $.ajax({
            type:"POST",
            url:"/admin/ajax/delete_func.php",
            dataType:"json",
            data:{admin:1, delete_name:"payment_month_list", id:no_array.toString()},
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
function delete_payment(id){
    if(confirm("삭제하시겠습니까?")){
        $.ajax({
            type:"POST",
            url:"/admin/ajax/delete_func.php",
            dataType:"json",
            data:{admin:1, delete_name:"payment_month_list", id:id},
            success: function(data){
                console.log(data);
                if(data == 1){
                    alert('삭제 되었습니다.');
                    window.location.reload();
                }
            }
        })
    }
    else{
        return;
    }
}
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH /*- $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 196*/;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
</script>
<style>
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
                    정기결제해지관리<small>정기결제해지를 관리합니다.</small>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">정기결제해지관리</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row" id="toolbox">
                    <div class="col-xs-12" style="padding-bottom:20px">
                        <form method="get" name="search_form" id="search_form" class="form-inline">
                            <div class="box-tools">
                                <div class="input-group"  >
                                    <div class="form-group">
                                        <select name="search_status" class="form-control" >
                                            <option value="">상태</option>
                                            <option value="R">해지신청</option>
                                            <option value="Y">해지완료</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="date" name="search_start_date" placeholder="" id="search_start_date" value="<?=$_REQUEST[search_start_date]?>"/> ~
                                        <input type="date"  name="search_end_date" placeholder="" id="search_end_date" value="<?=$_REQUEST[search_end_date]?>"/>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="이름/아이디/금액">
                                    </div>
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                                <th>기부폰</th>
                                <th>설치일자</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="row">
                    <div class="box">
                        <div class="box-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="60px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="140px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="60px">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="check_no" id="check_all_member" value="0">번호</th>
                                        <th>추천인ID</th>
                                        <th>신청자<br>아이디</th>
                                        <th>이름</th>
                                        <th>전화번호</th>
                                        <th>상품</th>
                                        <th>금액</th>
                                        <th>결제종류</th>
                                        <th>결제일</th>
                                        <th>종료일</th>
                                        <th>해지신청<br>일시</th>
                                        <th>해지정보<br>변경</th>
                                        <th>해지완료<br>일시</th>
                                        <th>메모</th>
                                        <th><button class="btn btn-primary" onclick="deleteMultiRow();return false;"><i class="fa"></i>삭제</button></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?
                                    $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                    $startPage = $nowPage?$nowPage:1;
                                    $pageCnt = 20;
                                    // 검색 조건을 적용한다.
                                    $searchStr .= $search_key ? " AND (a.buyer_id LIKE '%".$search_key."%' or a.VACT_InputName like '%".$search_key."%' or a.TotPrice='$search_key' ) " : null;
                                    if($search_start_date && $search_end_date) {
                                        $searchStr .= " AND date >= '$search_start_date' and date <= '$search_end_date'";
                                    }
                                    // if($search_leb ) {
                                    //     $searchStr .= " AND mem_leb='$search_leb'";
                                    // }
                                    if($search_status) {
                                        $searchStr .= " AND monthly_status='$search_status'";
                                    }
                                    $searchStr .= " AND monthly_yn='Y'";
                                    $searchStr .= " AND monthly_status in ('R', 'Y')";
                                    $order = $order?$order:"desc";

                                    $query = "
                                            SELECT
                                                count(*)
                                            FROM tjd_pay_result a
                                            WHERE 1=1
                                                  $searchStr";

                                    $res	    = mysqli_query($self_con,$query);
                                    $totalRow	=  mysqli_fetch_array($res);
                                    $totalCnt = $totalRow[0];
                                    $query = "SELECT a.buyertel, a.member_type, a.TotPrice, a.buyer_id, a.VACT_InputName,
                                        a.date, a.end_date, a.cancel_Requesttime, a.no,a.payMethod, a.payment_day, a.monthly_status, a.cancel_completetime,a.print_msg
                                        FROM tjd_pay_result a
                                        WHERE 1=1
                                            $searchStr";
                                    $limitStr   = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                    $number		= $totalCnt - ($nowPage - 1) * $pageCnt;
                                    $orderQuery .= " ORDER BY a.cancel_requesttime DESC $limitStr";
                                    $i = 1;
                                    $query .= $orderQuery;
                                    $res = mysqli_query($self_con,$query);
                                    while($row = mysqli_fetch_array($res)) {
                                        $sql_mem = "select recommend_id from Gn_Member where mem_id='{$row['buyer_id']}'";
                                        $res_mem = mysqli_query($self_con,$sql_mem);
                                        $row_mem = mysqli_fetch_array($res_mem);
                                ?>
                                    <tr>
                                        <td><input type="checkbox" class="check_no" id="check_one_member" name="" value="<?=$row['no']?>"><?=$number--?></td>
                                        <td><?=$row_mem['recommend_id']?></td>
                                        <td><?=$row['buyer_id']?></td>
                                        <td><?=$row['VACT_InputName']?></td>
                                        <td><?=$row['buyertel']?></td>
                                        <td><?=$row['member_type']?></td>
                                        <td><?=$row['TotPrice']?></td>
                                        <td><?=$pay_type[$row[payMethod]]?></td>
                                        <td><?=$row['date']?></td>
                                        <td>
                                            <!--form method="post" name="ssForm<?=$i?>" id="eeForm<?=$i?>" action="ajax/payment_month_save.php">
                                                <input type="hidden" name="no" value="<?=$row['no']?>" >
                                                <input type="hidden" name="type" value="end_date" >
                                                <input type="datetime-local" value="<?=date("Y-m-d\TH:i", strtotime($row['end_date']))?>" style="width: 200px" name = "end_date">
                                                <button class="btn btn-primary pull-right" onclick="payment_save('#eeForm<?=$i?>');return false;"><i class="fa fa-download"></i> 변경</button>
                                            </form-->
                                            <?=$row['end_date']?>
                                        </td>
                                        <td><?=$row['cancel_Requesttime']?></td>
                                        <td>
                                            <form method="post" name="ssForm<?=$i?>" id="ssForm<?=$i?>" action="ajax/payment_month_save.php">
                                                <input type="hidden" name="no" value="<?=$row['no']?>">
                                                <input type="hidden" name="type" value="main">
                                                <select name="payment_day">
                                                    <option value="" >결제일</option>
                                                    <option value="5"  <?=$row['payment_day'] == "5"?"selected":""?>>5</option>
                                                    <option value="10" <?=$row['payment_day'] == "10"?"selected":""?>>10</option>
                                                    <option value="15" <?=$row['payment_day'] == "15"?"selected":""?>>15</option>
                                                    <option value="20" <?=$row['payment_day'] == "20"?"selected":""?>>20</option>
                                                    <option value="25" <?=$row['payment_day'] == "25"?"selected":""?>>25</option>
                                                    <option value="30" <?=$row['payment_day'] == "30"?"selected":""?>>30</option>
                                                </select>
                                                <select name="month_status">
                                                    <option value="R" <?=$row['monthly_status'] == "R"?"selected":""?>>해지신청</option>
                                                    <option value="Y" <?=$row['monthly_status'] == "Y"?"selected":""?>>해지완료</option>
                                                    <option value="N" <?=$row['monthly_status'] == "N"?"selected":""?>>신청취소</option>
                                                </select>
                                                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="payment_save('#ssForm<?=$i?>');return false;"><i class="fa fa-download"></i> 변경</button>
                                            </form>
                                        </td>
                                        <td><?=$row['cancel_completetime']?></td>
                                        <td>
                                            <form method="post" name="mmForm<?=$i?>" id="mmForm<?=$i?>" action="ajax/payment_month_save.php">
                                                <input type="hidden" name="no" value="<?=$row['no']?>" >
                                                <input type="hidden" name="type" value="memo" >
                                                <textarea name="memo"><?=$row['print_msg']?></textarea>
                                                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="payment_save('#mmForm<?=$i?>');return false;"><i class="fa fa-download"></i> 변경</button>
                                            </form>
                                        </td>
                                        <td><a href="javascript:delete_payment(<?=$row['no']?>)">삭제</a></td>
                                    </tr>
                                    <?
                                        $i++;
                                    }
                                    if($i == 1) {?>
                                        <tr>
                                            <td colspan="12" style="text-align:center;background:#fff">
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
                        <?
                            echo drawPagingAdminNavi($totalCnt, $nowPage);
                        ?>
                    </div>
                </div>
            </section><!-- /.content -->
        </div><!-- /content-wrapper -->
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
    </div>
    <iframe name="excel_iframe" style="display:none;"></iframe>
<!--link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script-->                
