<style>
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #ddd!important;
}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
.zoom {
  transition: transform .2s; /* Animation */
}
.zoom:hover {
  transform: scale(4); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
  border:1px solid #0087e0;
  box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
}
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important}
.iam_table{border: 1px solid black;border-collapse: collapse;padding:3px;text-align: center;}
</style>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
function goPage(pgNum) {
    location.href = '?nowPage='+pgNum+"&search_key=<?=$search_key?>&cnt_per_page=<?=$cnt_per_page;?>&orderField=<?=$orderField?>&dir=<?=$dir?>";
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

<div class="wrapper">
    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>사업자공급관리 전체리스트<small>사업자공급리스트를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">사업자공급리스트관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:10px">
                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location.href='worker_service_admin.php'"><i class="fa fa-download"></i>전체리스트보기</button>
                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location.href='worker_service_admin_req.php'"><i class="fa fa-download"></i>신청리스트보기</button>
                    <form method="get" name="search_form" id="search_form">
                        <div class="box-tools">
                            <div class="input-group" style="width: 315px;">
                                <div class="form-group" style="margin:0px;">
                                    <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-left" placeholder="아이디/이름/소속직책" value="<?=$search_key?>" style="width:175px;">
                                </div>
                                <div class="form-group" style="margin:0px;">
                                    <select name="cnt_per_page" id="cnt_per_page" class="form-control input-sm pull-right" style="width:100px;">
                                        <option value="20" <?=$cnt_per_page == "20"?"selected":""?>>20개씩</option>
                                        <option value="50" <?=$cnt_per_page == "50"?"selected":""?>>50개씩</option>
                                        <option value="100" <?=$cnt_per_page == "100"?"selected":""?>>100개씩</option>
                                    </select>
                                </div>
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default" style="height: 30px;"><i class="fa fa-search"></i></button>
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
                                <col width="2%">
                                <col width="5%">
                                <col width="3%">
                                <!-- <col width="10%"> -->
                                <col width="10%">
                                <col width="10%">
                                <col width="5%">
                                <col width="10%">
                                <col width="10%">
                                <col width="5%">
                                <col width="10%">
                                <col width="5%">
                                <col width="5%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th><a href="?orderField=mem_id&dir=<?=$dir?>" class="sort-by">IAM ID</a></th>
                                    <th>채널</th>
                                    <th>카드명</th>
                                    <!-- <th>카드<br>링크</th> -->
                                    <th>IMAGE</th>
                                    <th>소속<br>직책</th>
                                    <th>폰번호</th>
                                    <th>카드수</th>
                                    <th>결제<br>여부</th>
                                    <th><a href="?orderField=req_data&dir=<?=$dir?>" class="sort-by">등록<br>일자</a></th>
                                    <th>컨텐<br>츠수</th>
                                    <th ><a href="?orderField=iam_click&dir=<?=$dir?>" class="sort-by">조회<br>건수</a></th>
                                    <th>신청<br>관리</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                //디폴트 아바타
                                $sql = "select main_img1 from Gn_Iam_Info where mem_id = 'obmms02'";
                                $result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
                                $row=mysqli_fetch_array($result);
                                $default_img =  $row['main_img1'];


                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                if($cnt_per_page){
                                    $pageCnt = $cnt_per_page;
                                }
                                // 검색 조건을 적용한다.
                                $searchStr .= $search_key ? " AND (ca_1.mem_id LIKE '%".$search_key."%' or ca_1.card_name like '%".$search_key."%' or ca_1.card_company like '%".$search_key."%' )" : null;

                                $count_query = "select count(idx) from Gn_Iam_Name_Card ca_1 WHERE worker_service_state=1 AND req_worker_id ='' AND group_id is NULL AND admin_shopping!=0 $searchStr";
                                $count_result = mysqli_query($self_con, $count_query);
                                $count_row = mysqli_fetch_array($count_result);
                                $totalCnt	=  $count_row[0];

                                $query = "SELECT * FROM Gn_Iam_Name_Card ca_1";
                                $query .= " WHERE worker_service_state=1 AND req_worker_id ='' AND group_id is NULL AND admin_shopping!=0 $searchStr";
                                $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number	= $totalCnt - ($nowPage - 1) * $pageCnt;
                                if(!$orderField)
                                    $orderField = "req_data";
                                $orderQuery .= " ORDER BY $orderField $dir $limitStr";
                                $i = 1;
                                $c=0;
                                $query .= $orderQuery;
                                $res = mysqli_query($self_con, $query);
                                while($row = mysqli_fetch_array($res)) {
                                    $mem_sql = "select mem_code from Gn_Member where mem_id='{$row['mem_id']}'";
                                    $mem_res = mysqli_query($self_con, $mem_sql);
                                    $mem_row = mysqli_fetch_array($mem_res);

                                    $fquery = "select count(*) from Gn_Iam_Friends where friends_card_idx = ".$row['idx'];
                                    $fresult = mysqli_query($self_con, $fquery);
                                    $frow = mysqli_fetch_array($fresult);
                                    //$friend_count	=  $frow[0];

                                    $sql_pay = "select sum(TotPrice) totPrice, date from tjd_pay_result where buyer_id = '".$row['mem_id']."' and end_status='Y'";
                                    $res_result = mysqli_query($self_con, $sql_pay);
                                    $totPriceRow = mysqli_fetch_row($res_result);
                                    $totPrice = $totPriceRow[0];

                                    $cquery = "select count(*) from Gn_Iam_Contents where westory_card_url = '{$row['card_short_url']}'";
                                    $cresult = mysqli_query($self_con, $cquery);
                                    $crow = mysqli_fetch_array($cresult);
                                    
                                    if($row['ai_map_gmarket'] == 1){
                                        $chanel = "지도";
                                        $card_arr = array();
                                        $index_card = 0;
                                        $sql_card = "select card_title from Gn_Iam_Name_Card where mem_id='{$row['mem_id']}' order by idx asc";
                                        $res_card = mysqli_query($self_con, $sql_card);
                                        while($row_card = mysqli_fetch_array($res_card)){
                                            if($row_card['card_title'] == "업체보기"){
                                                $card_arr[$index_card] = 1;
                                            }
                                            if($row_card['card_title'] == "메뉴보기"){
                                                $card_arr[$index_card] = 2;
                                            }
                                            if($row_card['card_title'] == "리뷰보기"){
                                                $card_arr[$index_card] = 3;
                                            }
                                            $index_card++;
                                        }
                                        $card_cnt = implode(',', $card_arr);
                                    }
                                    else if($row['ai_map_gmarket'] == 2 && $row['card_title'] == "상품소개해요"){
                                        $chanel = "G쇼핑";
                                        $card_cnt = 2;
                                    }
                                    else if($row['ai_map_gmarket'] == 2 && $row['card_title'] != "상품소개해요"){
                                        $chanel = "N쇼핑";
                                        $card_cnt = 2;
                                    }
                                    ?>
                                    <tr>
                                        <td><?=$number--?></td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=$row['mem_id']?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=$chanel?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=$row['card_name']?>
                                            </div>
                                        </td>
                                        <!-- <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <a href="http://kiam.kr/?<?=strip_tags($row['card_short_url'].$mem_row['mem_code'])?>" target="_blank"><?=$row['card_short_url']?></a>
                                            </div>
                                        </td> -->
                                        <td>
                                            <div >
                                                <?
                                                if($row['main_img1']){
                                                    $thumb_img =  $row['main_img1'];
                                                }else{
                                                    $thumb_img =  $default_img;
                                                }
                                                ?>
                                                <a href="http://kiam.kr/?<?=strip_tags($row['card_short_url'].$mem_row['mem_code'])?>" target="_blank">
                                                    <img class="zoom" src="<?=$thumb_img?>" style="width:50px;">
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=$row['card_company']?>
                                            </div>
                                        </td>
                                        <td><?=$row['card_phone']?></td>
                                        <td>
                                            <div style="overflow-x:hidden;">
                                                <?=$card_cnt?>
                                            </div>
                                        </td>
                                        <td><?=$totPrice?$totPrice:"0"?></td>
                                        <td><?=$row['req_data']?></td>
                                        <td><?=$crow[0]?></td>
                                        <td><?=$row['iam_click']?></td>
                                        <td><a href="javascript:show_alarm_1('<?=$row['idx']?>')" style="cursor:pointer;">신청</a></td>
                                    </tr>
                                    <?
                                    $c++;
                                    $i++;
                                }
                                if($i == 1) {?>
                                    <tr>
                                        <td colspan="18" style="text-align:center;background:#fff">등록된 내용이 없습니다.</td>
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
                    echo drawPagingAdminNavi($totalCnt, $nowPage, $pageCnt);
                    ?>
                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>  
    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="one_member_id" id="one_member_id" value="" />
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
</div>
<div id="insert_mem_id" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width:300px;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;box-shadow: none;">
                    <input type="hidden" name="card_idx" id="card_idx">
                    <input type="text" name="req_member_id" id="req_member_id" placeholder="아이디를 입력하세요." style="width:200px;font-size:15px;">
                </div>
            </div>
            <div class="modal-footer" style="text-align:right;">
                <a data-dismiss="modal" class="btn btn-default btn-submit" style="border-radius: 5px;width:30%;font-size:15px;background-color: #ff0066;color:white;" target="_blank">취소</a>
                <a class="btn btn-default btn-submit" style="border-radius: 5px;width:30%;font-size:15px;background-color: #ff0066;color:white;" href="javascript:save_req_id()">저장</a>
            </div>
        </div>
    </div>
</div>
<script language="javascript">
function delNameCard(card_idx){
    var msg = confirm('정말로 삭제하시겠습니까?');
    if(msg){
        $.ajax({
            type:"POST",
            url:"/admin/ajax/_db_controller.php",
            data:{
                mode:"delete_name_card",
                card_idx:card_idx
            },
            success:function(data){
                alert(data);
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

$(function(){
    $('.chkagree').change(function() {
        var id = $(this)[0].id;
        var phone_display = $(this)[0].checked;
        if($(this)[0].checked){
            phone_display = "Y";
        }else{
            phone_display = "N";
        }
        var card_idx = id.split("_")[2];
        $.ajax({
            type:"POST",
            url:"/admin/ajax/_db_controller.php",
            data:{
                mode:"update_name_card - phone_display",
                phone_display:phone_display,
                card_idx:card_idx
            },
            success:function(data){
                location.reload();
            },
            error: function(){
                alert('삭제 실패');
            }
        });
    });
    $('.chkclick').change(function() {
        var msg = "신청하기 전 필독하세요!!\n\n1. 사용하기 클릭 : [사용하기]는 본사에서 제공한 업체  IAM을 해당 업체 대표가 사용하겠다는 의견을 밝힌 경우 사용하기 버튼을 클릭합니다. 클릭과 동시에 추천인과 소속은 소개자 정보로 변경됩니다. \n2. 본사의 확인 조치 : 본사는 사용하기 버튼이 클릭된 경우 해당 업체 대표에게 아래의 사항을 확인합니다. \n 1) 리셀러가 제공한 IAM을 확인했는가 여부\n 2) IAM에서 해당 업체의 상품 판매 여부 \n 3) 상품 판매시 할인 이벤트 진행과 고객에게 포인트 지급 여부 \n 4) IAM 이용료 결제시 상품이용 권리의 이해 여부 \n3. 미사용 전환 : 본사의 확인과정에서 업체 대표가 위 2항에 대해 잘못된 정보 제공에 의한 사용 혹은 의사결정에 오해가 있을 경우 소개 사업자와 협의를 통해 미사용으로 전환합니다. 미사용 전환시 추천과 소속도 이전 상태로 복원됩니다.\n4. 사용하기 재 설정 : 미사용전환이 된 경우 소개 사업자는 해당 업체 대표와 최종 미팅을 통해 사용하기 재설정을 할 수 있습니다.";

        if($(this).prop("checked"))
        {
        if(confirm(msg)){
            $("#insert_user_data").modal("show");
        }
        }
        else{
            return;
        }
    });
    $('.number').change(function() {
        var idx = $(this).data('no');
        var order = $(this).val();
        $.ajax({
            type:"POST",
            url:"/admin/ajax/_db_controller.php",
            data:{
                mode:"update_name_card_sample_order",
                sample_order:order,
                card_idx:idx
            },
            success:function(data){
                //location.reload();
            },
            error: function(){
                alert('변경 실패');
            }
        });
    });
});

function save_req_id(){
    var req_id=$("#req_member_id").val();
    if(req_id == ''){
        alert("신청회원 아이디를 입력하세요.");
        return;
    }

    var card_idx = $("#card_idx").val();
    $.ajax({
        type:"POST",
        url:"/admin/ajax/worker_share_reg.php",
        data:{
            mode:"reg_req_id",
            req_id:req_id,
            card_idx:card_idx
        },
        success:function(data){
            console.log(data);
            if(data == "1"){
                alert("신청되었습니다.");
                location.reload();
            }
            else if(data == "0"){
                alert("리셀러 사업자 회원아이디가 아닙니다. 다시 확인해 주세요.");
                return;
            }
            else if(data == "2"){
                alert("최대 5개까지만 입력이 가능하므로 이미 신청된 5개 중에 한개 이상을 취소하거나 업체사용확인이 된 후에 추가 신청해야 합니다.");
                return;
            }
        },
        error: function(){
            alert('삭제 실패');
        }
    });
}

function show_alarm_1(idx){
    var msg = "신청하기 전 필독하세요!!\n\n1. 신청하기 클릭 : 자신이 원하는 업체 IAM을 선택하고 [신청]을 클릭한 후 아이디를 입력하여 저장하면 자신의 리스트로 이동합니다. \n2. 신청건수 제한 : 소개 사업자는 신청리스트에 답을수 있는 IAM이 총5건으로 제한됩니다. 단, 사용하기로 확정된 업체 IAM은 제한건수와 무관합니다.\n3. 신청후 소개기간 : 소개 사업자는 신청후 15일 이내에 업체 대표가 사용하겠다는 의견을 받아 사용하기를 클릭해야 합니다. 15일이 지나면 자동으로 해당 업체의 IAM이 신청리스트에서 사라져 전체리스트로 이동합니다.\n4. 재신청하기 : 신청후 15일이 지나 신청리스트에서 사라지면 다른 소개사업자가 신청하기 전에 다시 신청해야 합니다. 만약 타 소개 사업자가 신청하게 되면 자신의 소속과 추천으로 동기화가 되지 않으므로 주의해야 합니다.";

    if(confirm(msg)){
        $("#card_idx").val(idx);
        $("#insert_mem_id").modal("show");
    }
}
</script>
    