<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
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

    //주소록 다운
    function excel_down_(){
        $("#excel_down_form").submit();
        return false;
    }


    function goPage(pgNum) {
        location.href = '?nowPage='+pgNum+'&search_start_date=<?=$search_start_date?>&search_end_date=<?=$search_end_date?>&search_key=<?=$search_key?>&use_buy_type=<?=$use_buy_type?>';
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

    var ele = "";
    function changemakecount(val){        
        // console.log(make_count, idx); return;
        if(val == "chung"){
            var idx = $("#mem_id_cnt").val().trim();
            var share_point = $("#share_point").val();
            var change_type = "chung";
        }
        else if(val == "cha"){
            var idx = $("#mem_id_minus").val().trim();
            var share_point = $("#minus_point").val();
            var change_type = "cha";
        }
        
        if((idx == '') && (share_point == 0)){
            alert("아이디와 씨드포인트를 입력하세요.");
            return;
        }

        if(val == "chungcash"){
            var idx = $("#mem_id_cnt_cash").val().trim();
            var share_cash = $("#share_cash").val();
            var change_type = "chungcash";
        }
        else if(val == "chacash"){
            var idx = $("#mem_id_minus_cash").val().trim();
            var share_cash = $("#minus_cash").val();
            var change_type = "chacash";
        }
        
        if((idx == '') && (share_cash == 0)){
            alert("아이디와 캐시포인트를 입력하세요.");
            return;
        }
        else{
            $.ajax({
                type:"POST",
                url:"/admin/ajax/chung_make_count.php",
                dataType:"json",
                data:{changetype:change_type, mem_id:idx, share_point:share_point, share_cash:share_cash},
                success:function(data){
                    if(data == 1){
                        alert('변경이 완료되었습니다. 포인트 관리에서 확인할수 있습니다');
                    }
                    else if(data == 2){
                        alert('결제내용이 없습니다.');
                    }
                    else if(data == 3){
                        alert('보유포인트가 부족합니다.');
                    }
                    else if(data == 4){
                        alert('잘못된 회원 아이디입니다.');
                    }
                    location.reload();
                },
                error:function(){
                    alert('변경 실패');
                }
            });
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
    .loading_div {
        display:none;
        position: fixed;
        left: 50%;
        top: 50%;
        display: none;
        z-index: 1000;
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
                회원 포인트관리
                <small>회원의 포인트정보를 관리합니다.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">포인트관리</li>
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
                                <select name="search_title" id="" style="margin-right: 5px">
                                    <option value="">선택</option>
                                    <option value="mem_name"<? if($_REQUEST[search_title] == 'mem_name') { echo ' selected';}?>>회원명</option>
                                    <option value="mem_id"<? if($_REQUEST[search_title] == 'mem_id') { echo ' selected';}?>>아이디</option>
                                    <option value="item_name"<? if($_REQUEST[search_title] == 'item_name') { echo ' selected';}?>>상품명</option>
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
                <table id="example11" class="table table-bordered table-striped">
                    <tbody>
                        <div class="box-tools" style="margin-top: 10px;">
                            <!-- <div style="margin-top: 5px;">
                                <input type="radio" name="use_mem_type" id="auto" style="vertical-align: top;">
                                <label for="auto" value="automem" style="font-size:15px;">오토회원</label>
                                <input type="radio" name="use_mem_type" id="self" style="vertical-align: top;">
                                <label for="self" value="selfmem" style="font-size:15px;">셀프회원</label>
                            </div> -->
                            <div class="input-group" style="display:inline-flex;">
                                
                                <input type="text" name="mem_id_cnt" id="mem_id_cnt" style="width:150px" class="form-control input-sm pull-right" placeholder="아이디">
                                <input type="number" name="share_point" id="share_point" style="width:150px" class="form-control input-sm pull-right" placeholder="씨드포인트">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" onclick="changemakecount('chung')">충전</button>
                                </div>
                            </div>
                            <div class="input-group" style="display:inline-flex;">
                                
                                <input type="text" name="mem_id_minus" id="mem_id_minus" style="width:150px" class="form-control input-sm pull-right" placeholder="아이디">
                                <input type="number" name="minus_point" id="minus_point" style="width:150px" class="form-control input-sm pull-right" placeholder="씨드포인트">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" onclick="changemakecount('cha')">차감</button>
                                </div>
                            </div>
                        </div>
                        <div class="box-tools" style="margin-top: 10px;">
                            <!-- <div style="margin-top: 5px;">
                                <input type="radio" name="use_mem_type" id="auto" style="vertical-align: top;">
                                <label for="auto" value="automem" style="font-size:15px;">오토회원</label>
                                <input type="radio" name="use_mem_type" id="self" style="vertical-align: top;">
                                <label for="self" value="selfmem" style="font-size:15px;">셀프회원</label>
                            </div> -->
                            <div class="input-group" style="display:inline-flex;">
                                
                                <input type="text" name="mem_id_cnt_cash" id="mem_id_cnt_cash" style="width:150px" class="form-control input-sm pull-right" placeholder="아이디">
                                <input type="number" name="share_cash" id="share_cash" style="width:150px" class="form-control input-sm pull-right" placeholder="캐시포인트">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" onclick="changemakecount('chungcash')">충전</button>
                                </div>
                            </div>
                            <div class="input-group" style="display:inline-flex;">
                                
                                <input type="text" name="mem_id_minus_cash" id="mem_id_minus_cash" style="width:150px" class="form-control input-sm pull-right" placeholder="아이디">
                                <input type="number" name="minus_cash" id="minus_cash" style="width:150px" class="form-control input-sm pull-right" placeholder="캐시포인트">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" onclick="changemakecount('chacash')">차감</button>
                                </div>
                            </div>
                        </div>
                    </tbody>
                </table>
                <div style="display: flex;justify-content: end;margin-bottom: 5px">
                    <a href="point_manage_list_cash.php" type="button" class="btn btn-primary" style="margin-right: 5px">포인트 현금전환관리</a>
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
                                    <th>아이디</th>
                                    <th>이름</th>
                                    <th>유형</th>
                                    <th>상품명</th>
                                    <th>상세정보</th>
                                    <th>링크주소</th>
                                    <th>결제일</th>
                                    <th>전체포인트</th>
                                    <th>캐시포인트</th>
                                    <th>씨드포인트</th>
                                </tr>
                            </thead>
                            <tbody id="iam_body">
                            <?php

                            $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                            $startPage = $nowPage?$nowPage:1;
                            $pageCnt = 20;
                            if ($search_title && $search_key) {
                                if ($search_title == 'mem_name') {
                                    $searchStr .= " AND VACT_InputName LIKE '%".$search_key."%'";
                                } else if ($search_title == 'mem_id') {
                                    $searchStr .= " AND buyer_id LIKE '%".$search_key."%'";
                                } else {
                                    $searchStr .= " AND item_name LIKE '%".$search_key."%'";
                                }
                            }
                            if($search_start_date && $search_end_date)
                                $searchStr .= " AND pay_date >= '$search_start_date' and pay_date <= '$search_end_date'";

                            if($use_buy_type == "sell")
                                $searchStr .= " AND site is not null and type='servicebuy'";
                            else if($use_buy_type == "chung")
                                $searchStr .= " AND site is null";
                            $sql = "select * from Gn_Item_Pay_Result where (point_val=1 or (point_val=2 and receive_state=1))".$searchStr;
                            $res = mysqli_query($self_con, $sql);
                            $totalCnt = mysqli_num_rows($res);

                            $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                            $number = $totalCnt - ($nowPage - 1) * $pageCnt;
                            $orderQuery .= " ORDER BY pay_date DESC $limitStr ";
                            // $no = 0;
                            
                            $query = $sql.$searchStr.$orderQuery;
                            $result = mysqli_query($self_con, $query);
                            while($row = mysqli_fetch_array($result)){
                                $mem_name_sql = "select mem_name from Gn_Member where mem_id= '".$row['buyer_id']."'";
                                $mem_name_sql_res = mysqli_query($self_con, $mem_name_sql);
                                $mem_name = mysqli_fetch_array($mem_name_sql_res);
                                $no++;
                                if(($row['type'] == "buy") || ($row['type'] == "service")){
                                    $type = "충전";
                                }
                                else if($row['type'] == "use" || $row[type] == "group_card"){
                                    $type = "결제";
                                }
                                else if($row['type'] == "minus"){
                                    $type = "차감";
                                }
                                else if($row['type'] == "servicebuy"){
                                    $type = "판매";
                                }
                                else if($row['type'] == "cardsend" || $row['type'] == "contentssend"){
                                    $type = "전송";
                                }
                                else if($row['type'] == "contentsrecv"){
                                    $type = "수신";
                                    $row['item_price'] = 0;
                                }
                                else{
                                    $type = "쉐어";
                                }
                            ?>
                            <tr>
                                <td align="center"><input type="checkbox" class="check" id="check_one_member" name="" value="<?=$row['no']?>"><br><?=$number--?></td>
                                <td><?=$row['buyer_id']?></td>
                                <td><?=$mem_name['mem_name']?></td>
                                <td><?=$type?></td>
                                <td><?=$row['item_name']?></td>
                                <td><?=$row['pay_method']?></td>
                                <td><a href="<?=$row['site']?>" target="_blank"><?=cut_str($row['site'], 50)?></a></td>
                                <td><?=$row['pay_date']?></td>
                                <td><?=$row['current_point'] + $row['current_cash']?></td>
                                <td><?=$row['current_cash']?></td>
                                <td><?=$row['current_point']?></td>
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
    </div><!-- /content-wrapper -->

<form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
    <input type="hidden" name="grp_id" value="" />
    <input type="hidden" name="box_text" value="" />
    <input type="hidden" name="one_member_id" id="one_member_id" value="" />
    <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
</form>
<iframe name="excel_iframe" style="display:none;"></iframe>

<script language="javascript">
    var round = 0;
    $( document ).ready( function() {});
    function changeLevel(mem_code) {
        var mem_leb = $('#mem_leb'+mem_code+" option:selected").val();
        var data = {"mode":"change","mem_code":"'+mem_code+'","mem_leb":"'+mem_leb+'"};
        $.ajax({
            type:"POST",
            url:"/admin/ajax/user_level_change.php",
            dataType:"json",
            data:{
                mode:"change",
                mem_code:mem_code,
                mem_leb:mem_leb
            },
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
    }

    function loginGo(mem_id, mem_pw, mem_code) {
        $('#one_id').val(mem_id);
        $('#mem_pass').val(mem_pw);
        $('#mem_code').val(mem_code);
        $('#login_form').submit();
    }

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
                    delete_name: "member_point_manage_del",
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
</script>
<!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      