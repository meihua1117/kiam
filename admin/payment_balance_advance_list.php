<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$search_year = $search_year?$search_year:date("Y");
$search_month = $search_month?sprintf("%02d",$search_month):sprintf("%02d",date("m"));
/*
결제 금액에서 부가세 빼고
-사업자에게 일반 사용자 결제금액 1만원의 50%, 정산지속 기간 5년간
-사업자A가 추천한 사업자 B의 가입비용 50만원의 50%, 정산기간 1회
==> 지급일
기본: 지급액 입력시 자동나오게
수정: 날짜 수정도 가능하게

*/
$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
$startPage = $nowPage?$nowPage:1;
$pageCnt = 30;

// 검색 조건을 적용한다.
$searchStr .= $service_type ? " AND b.service_type =".$service_type : null;
$searchStr .= $search_key ? " AND (b.mem_id LIKE '%".$search_key."%' or b.mem_phone like '%".$search_key."%' or b.mem_name like '%".$search_key."%' or c.price like '%".$search_key."%' ) " : null;
$searchStr .=" and balance_date='$search_year$search_month' ";
$order = $order?$order:"desc"; 		

?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    //주소록 다운
    function excel_down_(){
        $("#excel_down_form").submit();
        return false;
    }
    function goPage(pgNum) {
        location.href = '?nowPage='+pgNum+'&search_year=<?=$search_year?>&search_month=<?=$search_month?>&search_key=<?=$search_key?>';
    }
    function payment_save(fm) {
        if(confirm('상태를 변경하시겠습니까?')) {
            $(fm).submit();
            return false;
        }
    }
    function payment_multi_save() {
        if(confirm('상태를 변경하시겠습니까?')) {
            var check_array = $("#example1").children().find(".check");
            var mem_array = [];
            var status_array = [];
            var index = 0;
            check_array.each(function(){
                if($(this).prop("checked") && $(this).val() > 0) {
                    var form = $(this).parents("tr").find("form");
                    mem_array[index] = form.find("input[name=mem_id]").val();
                    //if(status_array.length == 0)
                    ///    status_array[index++] = form.find("select").val();
                    //else
                        status_array[index++] = "Y";
                }
            });
            if(mem_array.length > 0){
                $.ajax({
                    type:"POST",
                    url:"ajax/payment_balance_advance_save.php",
                    data:{
                        mem_id:mem_array.toString(),
                        balance_date:<?=$search_year.$search_month?>,
                        search_year:<?=$search_year?>,
                        search_month:<?=$search_month?>,
                        end_status : status_array.toString()
                    },
                    success:function(data){
                        alert('저장되었습니다.');
                        location.reload();
                    }
                });
            }
            return false;
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
        $('.select').on("change", function() {
            var parent = $(this).parents("tr");
            parent.children().find(".check").prop("checked",true);
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
            <h1>사업자 정산관리<small>사업자 정산을 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">사업자정산관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='crawler_member_detail.php'"><i class="fa fa-download"></i> 전체보기</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='make_balance.php?search_year=<?php echo $search_year;?>&search_month=<?php echo $search_month;?>'"> 정산생성</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='?type=2'"> 지급완료</button>
                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='?type=1'"> 지급대기</button>
                    <form method="get" name="search_form" id="search_form" class="form-inline">
                        <div class="box-tools">
                            <div class="input-group"  style="display:inline-flex;width: 250px;">
                                <select name="service_type" class="form-inline" >
                                    <option value="">전체</option>
                                    <option value="0" <? echo $service_type == "0"?"selected":""?>>FREE</option>
                                    <option value="1" <? echo $service_type == "1"?"selected":""?>>이용자</option>
                                    <option value="2" <? echo $service_type == "2"?"selected":""?>>리셀러</option>
                                    <option value="3" <? echo $service_type == "3"?"selected":""?>>분양자</option>
                                </select>
                                <select name="search_year" class="form-inline">
                                    <?for($i=$search_year-4;$i<=$search_year;$i++){?>
                                        <option value="<?=$i?>"  <?php echo $i==$search_year?"selected":""?>><?=$i?></option>
                                    <?}?>
                                </select>
                                <select name="search_month" class="form-inline">
                                    <?for($i=1;$i<13;$i++){?>
                                        <option value="<?=$i?>" <?php echo sprintf("%02d",$i)==$search_month?"selected":""?>><?=$i?></option>
                                    <?}?>
                                </select>
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
            <div>
                수당합계 : <span id="total_share"></span>
                지급합계 : <span id="total_balance"></span>
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
                                <col width="60px">
                                <col width="100px">
                                <col width="140px">
                                <col width="210px">
                                <col width="200px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="check" id="check_all_member" value="0">&nbsp;번호</th>
                                    <th>상세보기</th>
                                    <th>사업자<br>아이디</th>
                                    <th>사업자<br>이름</th>
                                    <th>사업자<br>직급</th>
                                    <th>전화번호</th>
                                    <th>정산(월)</th>
                                    <th>정산액</th>
                                    <th>지급액</th>
                                    <th>지급여부
                                        <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="payment_multi_save();return false;">
                                            <i class="fa"></i> 지급완료
                                        </button>
                                    </th>
                                    <th>지급일</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $query = "SELECT count(b.mem_id) FROM tjd_pay_result_balance c INNER JOIN Gn_Member b on b.mem_id = c.seller_id
                                            WHERE 1=1 and b.service_type > 1 $searchStr group by b.mem_id";
                                $res	    = mysqli_query($self_con,$query);
                                $totalCnt	=  mysqli_num_rows($res);
                                // echo $totalCnt."|".$nowPage;
                                $query = "SELECT SQL_CALC_FOUND_ROWS b.mem_id,b.mem_name,b.mem_phone,c.balance_date,b.service_type,c.balance_yn 
                                            FROM tjd_pay_result_balance c INNER JOIN Gn_Member b on b.mem_id = c.seller_id
                                            WHERE 1=1 and b.service_type > 1 $searchStr group by b.mem_id";
                                $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number			= $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY b.mem_id DESC ";
                                $i = 1;
                                $query .= $orderQuery;
                                $res = mysqli_query($self_con,$query);
                                while($row = mysqli_fetch_array($res)) {
                                    if($row['service_type'] == 2) {
                                        $mem_level = "리셀러";
                                    } else if($row['service_type'] == 3) {
                                        $mem_level = "분양자";
                                    } else {
                                        $mem_level = "이용자";
                                    }
                                    $branch_share_fee = 0;
                                    $pquery = " select
                                                  sum(price/1.1*0.01*share_per) price,
                                                  share_per,
                                                  balance_yn,
                                                  balance_confirm_date
                                                from tjd_pay_result_balance 
                                                where seller_id='$row[mem_id]' and balance_date='$search_year$search_month' ";
                                    $pres = mysqli_query($self_con,$pquery);
                                    $prow = mysqli_fetch_array($pres);

                                    //$row['balance_yn']  ="N";
                                    $row['balance_confirm_date'] = $prow['balance_confirm_date'];
                                    $srow = "";
                                    // 분양자일경우 리셀러의 20프로
                                    if($mem_level == "분양자") {
                                        $squery = "select sum(price/1.1*0.01*branch_share_per) price,
                                                         branch_share_per ,
                                                         branch_balance_yn,
                                                         branch_balance_confirm_date
                                                    from tjd_pay_result_balance 
                                                    where branch_id='$row[mem_id]' and balance_date='$search_year$search_month' ";
                                        $sres = mysqli_query($self_con,$squery);
                                        $srow = mysqli_fetch_array($sres);
                                        $branch_share_fee = $srow[price];
                                    }
                                    if($prow[balance_yn] =="Y") {
                                        $row['balance_yn'] ="Y";
                                        $row['balance_confirm_date'] = $prow['balance_confirm_date'];
                                    }
                                    if($srow[branch_balance_yn] =="Y") {
                                        $row['balance_yn'] ="Y";
                                        $row['balance_confirm_date'] = $srow['branch_balance_confirm_date'];
                                    }
                                    $total_balance_fee = 0;
                                    if($row['balance_yn'] == "Y")
                                        $total_balance_fee = number_format(($prow[price])+$branch_share_fee);
                                    $total_sum_share_fee += $prow[price];
                                    if($row['balance_yn'] == "Y")
                                        $total_sum_balance_fee += $prow[price];
                                }
                                
                                $query .= $limitStr;
                                $res = mysqli_query($self_con,$query);
                                while($row = mysqli_fetch_array($res)) {
                                    if($row['service_type'] == 2) {
                                        $mem_level = "리셀러";
                                    } else if($row['service_type'] == 3) {
                                        $mem_level = "분양자";
                                    } else if($row['service_type'] == 1) {
                                        $mem_level = "이용자";
                                    } else {
                                        $mem_level = "FREE";
                                    }
                                    $branch_share_fee = 0;
                                    $pquery = " select
                                                  sum(price/1.1*0.01*share_per) price,
                                                  share_per,
                                                  balance_yn,
                                                  balance_confirm_date
                                                from tjd_pay_result_balance 
                                                where seller_id='$row[mem_id]' and balance_date='$search_year$search_month' ";
                                    $pres = mysqli_query($self_con,$pquery);
                                    $prow = mysqli_fetch_array($pres);

                                    //$row['balance_yn']  ="N";
                                    $row['balance_confirm_date'] = $prow['balance_confirm_date'];
                                    $srow = "";
                                    // 분양자일경우 리셀러의 20프로
                                    if($mem_level == "분양자") {
                                        $squery = "select sum(price/1.1*0.01*branch_share_per) price,
                                                         branch_share_per ,
                                                         branch_balance_yn,
                                                         branch_balance_confirm_date
                                                    from tjd_pay_result_balance 
                                                    where branch_id='$row[mem_id]' and balance_date='$search_year$search_month' ";
                                        $sres = mysqli_query($self_con,$squery);
                                        $srow = mysqli_fetch_array($sres);
                                        $branch_share_fee = $srow[price];
                                    }
                                    if($prow[balance_yn] =="Y") {
                                        $row['balance_yn'] ="Y";
                                        $row['balance_confirm_date'] = $prow['balance_confirm_date'];
                                    }
                                    if($srow[branch_balance_yn] =="Y") {
                                        $row['balance_yn'] ="Y";
                                        $row['balance_confirm_date'] = $srow['branch_balance_confirm_date'];
                                    }
                                    $balance_fee = 0;
                                    $share_fee = number_format(($prow[price])+$branch_share_fee);
                                    if($row['balance_yn'] == "Y")
                                        $balance_fee = number_format(($prow[price])+$branch_share_fee);
                                    $sum_share_fee += $prow[price];
                                    if($row['balance_yn'] == "Y")
                                        $sum_balance_fee += $prow[price];
                                    ?>
                                    <tr>
                                        <td><input type="checkbox" class="check" id="check_one_member" value="<?=$number?>">&nbsp;<?=$number--?></td>
                                        <td><a href="payment_balance_list_advance_detail.php?mem_id=<?=$row['mem_id']?>&search_year=<?php echo $search_year;?>&search_month=<?php echo $search_month;?>"><?="상세보기"?></a></td>
                                        <td><?=$row['mem_id']?></td>
                                        <td><?=$row['mem_name']?></td>
                                        <td><?=$mem_level;?></td>
                                        <td>
                                            <?=str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""?str_replace("-", "",$row['mem_phone']):$row['sendnum']?>
                                        </td>
                                        <td><?=$row['balance_date']?></td>
                                        <td><?=$share_fee?> 원</td>
                                        <td><?=$balance_fee?> </td>
                                        <td>
                                            <form method="post" name="ssForm<?=$i?>" id="ssForm<?=$i?>" action="ajax/payment_balance_advance_save.php">
                                                <input type="hidden" name="mem_id" value="<?php echo $row['mem_id']?>" >
                                                <input type="hidden" name="balance_date" value="<?php echo $search_year."".$search_month?>" >
                                                <input type="hidden" name="search_year" value="<?php echo $search_year?>" >
                                                <input type="hidden" name="search_month" value="<?php echo $search_month?>" >
                                                <select name="end_status" class = "select">
                                                    <option value="N" <?php echo $row['balance_yn'] == "N"?"selected":""?>>지급대기</option>
                                                    <option value="Y" <?php echo $row['balance_yn'] == "Y"?"selected":""?>>지급완료</option>
                                                </select>
                                                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="payment_save('#ssForm<?=$i?>');return false;"><i class="fa"></i> 변경</button>
                                            </form>
                                        </td>
                                        <td><?=$row['balance_confirm_date']?></td>
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
            <div style="float:right;margin-right:20px;">
                / 미지급 합계 : <?php echo number_format($sum_share_fee - $sum_balance_fee);?>
            </div>
            <div style="float:right;margin-right:20px;">
                / 지급 합계 : <?php echo number_format($sum_balance_fee);?>
            </div>
            <div style="float:right;margin-right:20px;">
                수당 합계 : <?php echo number_format($sum_share_fee);?>
            </div>
            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                </div>
                <div class="col-sm-7">
                    <?echo drawPagingAdminNavi($totalCnt, $nowPage,$pageCnt);?>
                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div>
<script>
$("#total_share").html('<?php echo number_format($total_sum_share_fee);?>');
$("#total_balance").html('<?php echo number_format($total_sum_balance_fee);?>');
</script>

