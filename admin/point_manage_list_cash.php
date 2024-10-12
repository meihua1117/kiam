<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>

<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<style>
    .loading_div {
        display:none;
        position: fixed;
        left: 50%;
        top: 50%;
        z-index: 1000;
    }
    .switch_repo_status {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 28px;
    }
    .switch_repo_status input {
        margin-left: 10px;
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
        height: 20px;
        width: 20px;
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
                포인트관리 현금전환관리
                <small>포인트 현금전환정보를 관리합니다.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">현금전환관리</li>
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
                <table id="example1" class="table table-bordered table-striped">
                    <tbody>
                    <form method="get" name="search_people" id="search_people">
                        <div class="box-tools">
                            <div class="input-group" style="display:inline-flex;">
                                <input type="date" placeholder="시작일" name="search_start_date" value="<?=$_REQUEST['search_start_date']?>" style="margin-right:5px;border: 1px solid black;width:125px;">
                                <input type="date" placeholder="종료일" name="search_end_date" value="<?=$_REQUEST['search_end_date']?>" style="margin-right:5px;border: 1px solid black;width:125px;">
                                <select title="" name="use_buy_type" data-plugin-selectTwo onchange="" style="width:90px;margin-right:5px;">
                                    <option value="">전체</option>
                                    <option value="sell" <?if($_REQUEST['use_buy_type'] == "sell") echo "selected"?>>판매포인트</option>
                                    <option value="chung" <?if($_REQUEST['use_buy_type'] == "chung") echo "selected"?>>충전포인트</option>
                                </select>
                                <input type="text" name="search_key" id="search_key" value="<?=$_REQUEST['search_key']?>" style="width:150px" class="form-control input-sm pull-right" placeholder="아이디">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    </tbody>
                </table>
                <div style="display: flex;justify-content: end;margin-bottom: 5px">
                    <a href="point_manage_list.php" type="button" class="btn btn-primary" style="margin-right: 5px">회원포인트관리</a>
                    <button class="btn btn-primary" onclick="deleteMultiRow()">선택삭제</button>
                </div>
            </div>
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered table-striped" id="gm_iam">
                            <colgroup>
                                <col width="50px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="150px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="check" id="check_all_member" value="0">번호</th>
                                    <th>소속</th>
                                    <th>추천인</th>
                                    <th>이름</th>
                                    <th>아이디</th>
                                    <th>현금전환</th>
                                    <th>캐시포인트</th>
                                    <th>요청일</th>
                                    <th>계좌정보</th>
                                    <th>전환</th>
                                    <th>지급일</th>
                                </tr>
                            </thead>
                            <tbody id="iam_body">
                            <?php

                            $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                            $startPage = $nowPage?$nowPage:1;
                            $pageCnt = 20;

                            $searchStr .= $search_key ? " AND (buyer_id LIKE '%".$search_key."%' OR VACT_InputName LIKE '%".$search_key."%')" : null;

                            if($search_start_date && $search_end_date)
                                $searchStr .= " AND date >= '$search_start_date' and date <= '$search_end_date'";
                            $sql = "select * from tjd_pay_result where member_type = '현금전환'".$searchStr;
                            $res = mysqli_query($self_con,$sql);
                            $totalCnt = mysqli_num_rows($res);

                            $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                            $number = $totalCnt - ($nowPage - 1) * $pageCnt;
                            $orderQuery .= " ORDER BY date DESC $limitStr ";
                            // $no = 0;

                            $query = $sql.$searchStr.$orderQuery;
                            $result = mysqli_query($self_con,$query);
                            while($row = mysqli_fetch_array($result)){
                                $mem_sql = "select site_iam, recommend_id, mem_point, mem_cash,bank_name,bank_account from Gn_Member where mem_id= '".$row['buyer_id']."'";
                                $mem_sql_res = mysqli_query($self_con,$mem_sql);
                                $member = mysqli_fetch_array($mem_sql_res);
                                ?>
                                <tr>
                                    <td align="center">
                                        <input type="checkbox" class="check" id="check_one_member" name="" value="<?=$row['no']?>">
                                        <br><?=$number--?>
                                    </td>
                                    <td><?=$member['site_iam']?></td>
                                    <td><?=$member['recommend_id']?></td>
                                    <td id="buyer_id_<?=$row['no']?>"><?=$row['buyer_id']?></td>
                                    <td><?=$row['VACT_InputName']?></td>
                                    <td id="TotPrice_<?=$row['no']?>"><?=$row['TotPrice']?></td>
                                    <td><?=$member['mem_cash']?></td>
                                    <td><?=$row['date']?></td>
                                    <td><?=$member['bank_name'].$member['bank_account']?></td>
                                    <td>
                                        <label class="switch_repo_status">
                                            <input type="checkbox" name="status" id="switch_input_<?php echo $row['no'];?>" value="<?php echo $row['no'];?>" <?php echo $row['end_status']== "Y" ? "checked":""?>>
                                            <span class="slider round" id="slider_<?php echo $row['no'];?>"></span>
                                        </label>
                                    </td>
                                    <td><?=$row['applDate']?></td>
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
                    <?=drawPagingAdminNavi($totalCnt, $nowPage);?>
                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>

    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="one_member_id" id="one_member_id" value="" />
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
    <script type="text/javascript" src="./js/point_manage_list_cash.js"></script>
    <script>
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

        function deleteMultiRow() {
            var check_array = $("#gm_iam").children().find(".check");
            var no_array = [];
            var index = 0;
            check_array.each(function(){
                if($(this).prop("checked") && $(this).val() > 0)
                    no_array[index++] = $(this).val();
            });

            if(no_array.length == 0){
                alert("삭제할 리코드를 선택하세요.");
                return;
            }
            if(confirm('삭제하시겠습니까?')) {
                $.ajax({
                    type:"POST",
                    url:"/admin/ajax/delete_func.php",
                    dataType:"json",
                    data:{
                        admin:1,
                        delete_name: "point_manage_list_cash_del",
                        id:no_array.toString()
                    },
                    success: function(data){
                        if(data == 1){
                            alert('삭제 되었습니다.');
                            window.location.reload();
                        }
                    }
                })
            }
        }

        $('.switch_repo_status').on("change", function() {
            var id = $(this).find("input[type=checkbox]").val();
            var status = $(this).find("input[type=checkbox]").is(":checked") == true ? "Y" : "N";
            $.ajax({
                type:"POST",
                url:"/admin/ajax/point_change_money.php",
                data:{
                    method:'change_status',
                    index:id,
                    status:status,
                    mem_id : $('#buyer_id_'+id)[0].innerText,
                    TotPrice : $('#TotPrice_'+id)[0].innerText
                },
                success:function(data){
                    if (data == 1) {
                        alert('성공되었습니다.')
                    }
                    location.reload();
                    //location.reload();
                }
            })
        });
    </script>