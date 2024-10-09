<style>
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #ddd!important;
}
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
/* user agent stylesheet */
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
a.sort-by:before,
a.sort-by:after {
	border: 4px solid transparent;
	content: "";
	display: block;
	height: 0;
	right: 5px;
	top: 50%;
	position: absolute;
	width: 0;
}
a.sort-by:before {
	border-bottom-color: #666;
	margin-top: -9px;
}
a.sort-by:after {
	border-top-color: #666;
	margin-top: 1px;
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
</style>
<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
function goPage(pgNum) {
    location.href = '?nowPage='+pgNum+"&search_key=<?=$search_key?>&cnt_per_page=<?=$cnt_per_page;?>&start_req_data=<?=$start_req_data;?>&end_req_data=<?=$end_req_data;?>&search_position=<?=$search_position;?>&search_chanel=<?=$search_chanel;?>&orderField=<?=$orderField?>&dir=<?=$dir?>";
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
            <h1>자동생성리스트<small>자동생성리스트를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">자동생성리스트관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:10px">
                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location.href='worker_service_admin.php'"><i class="fa fa-download"></i>사업자공급관리</button>
                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location.href='ai_map_gmarket_making.php'"><i class="fa fa-download"></i>IAM자동생성</button>
                    <form method="get" name="search_form" id="search_form" class="form-inline">
                        <div class="box-tools">
                            <div class="input-group" style="width: 770px;">
                                <div class="form-group" style="margin:0px;">
                                    <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-left" placeholder="아이디/이름" value="<?=$search_key?>" style="width:175px;">
                                </div>
                                <div class="form-group" style="margin:0px;">
                                    <input type="text" name="search_position" id="search_position" class="form-control input-sm pull-left" placeholder="지역" value="<?=$search_position?>" style="width:100px;">
                                </div>
                                <div class="form-group" style="margin:0px;">
                                    <select name="search_chanel" id="search_chanel" class="form-control input-sm" style="width:100px;font-size: 12px;padding: 6.5px;">
                                        <option value="0" <?=$search_chanel == "0"?"selected":""?>>전체</option>
                                        <option value="1" <?=$search_chanel == "1"?"selected":""?>>지도</option>
                                        <option value="2" <?=$search_chanel == "2"?"selected":""?>>G쇼핑</option>
                                        <option value="3" <?=$search_chanel == "3"?"selected":""?>>N쇼핑</option>
                                    </select>
                                </div>
                                <div class="form-group" style="margin:0px;">
                                    <input type="date" name="start_req_data" id="start_req_data" value="<?=$start_req_data?>" style="width:120px;"> ~
                                    <input type="date" name="end_req_data" id="end_req_data" value="<?=$end_req_data?>" style="width:120px;">
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
                                <col width="2%">
                                <col width="5%">
                                <col width="12%">
                                <col width="5%">
                                <col width="12%">
                                <col width="7%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="10%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th><a href="?search_key=<?=$search_key?>&cnt_per_page=<?=$cnt_per_page;?>&start_req_data=<?=$start_req_data;?>&end_req_data=<?=$end_req_data;?>&search_position=<?=$search_position;?>&search_chanel=<?=$search_chanel;?>&orderField=mem_id&dir=<?=$dir?>" class="sort-by">IAM ID</a></th>
                                    <th>채널</th>
                                    <th>카드명</th>
                                    <th>IMAGE</th>
                                    <th>지역주소</th>
                                    <th>폰번호</th>
                                    <th><a href="?search_key=<?=$search_key?>&cnt_per_page=<?=$cnt_per_page;?>&start_req_data=<?=$start_req_data;?>&end_req_data=<?=$end_req_data;?>&search_position=<?=$search_position;?>&search_chanel=<?=$search_chanel;?>&orderField=phone_display&dir=<?=$dir?>" class="sort-by">노출<br>여부</a></th>
                                    <th>결제<br>여부</th>
                                    <th><a href="?search_key=<?=$search_key?>&cnt_per_page=<?=$cnt_per_page;?>&start_req_data=<?=$start_req_data;?>&end_req_data=<?=$end_req_data;?>&search_position=<?=$search_position;?>&search_chanel=<?=$search_chanel;?>&orderField=cnt&dir=<?=$dir?>" class="sort-by">카드수</a></th>
                                    <th><a href="?search_key=<?=$search_key?>&cnt_per_page=<?=$cnt_per_page;?>&start_req_data=<?=$start_req_data;?>&end_req_data=<?=$end_req_data;?>&search_position=<?=$search_position;?>&search_chanel=<?=$search_chanel;?>&orderField=req_data&dir=<?=$dir?>" class="sort-by">등록일자</a></th>
                                    <th>컨텐<br>츠수</th>
                                    <th ><a href="?search_key=<?=$search_key?>&cnt_per_page=<?=$cnt_per_page;?>&start_req_data=<?=$start_req_data;?>&end_req_data=<?=$end_req_data;?>&search_position=<?=$search_position;?>&search_chanel=<?=$search_chanel;?>&orderField=iam_click&dir=<?=$dir?>" class="sort-by">조회<br>건수</a></th>
                                    <!-- <th><a href="?orderField=iam_share&dir=<?=$dir?>" class="sort-by">공유<br>건수</a></th> -->
                                    <th>공급<br>노출<input type="checkbox" class="check" id="check_all_member" value="0" style="margin: 0px 5px;"></th>
                                    <th>신청<br>관리</th>
                                    <th>업체<br>사용</th>
                                    <th>삭제</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                //디폴트 아바타
                                $sql = "select main_img1 from Gn_Iam_Info where mem_id = 'obmms02'";
                                $result=mysql_query($sql) or die(mysql_error());
                                $row=mysql_fetch_array($result);
                                $default_img =  $row['main_img1'];


                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                if($cnt_per_page){
                                    $pageCnt = $cnt_per_page;
                                }
                                // 검색 조건을 적용한다.
                                $searchStr .= $search_key ? " AND (ca_1.mem_id LIKE '%".$search_key."%' or ca_1.card_name like '%".$search_key."%' )" : null;

                                $searchStr .= $search_position ? " AND ca_1.card_addr LIKE '%".$search_position."%'" : null;

                                switch($search_chanel){
                                    case 0:
                                        $searchStr .= "";
                                    break;
                                    case 1:
                                        $searchStr .= " AND ca_1.ai_map_gmarket=1 ";
                                    break;
                                    case 2:
                                        $searchStr .= " AND ca_1.ai_map_gmarket=2 AND ca_1.card_title='상품소개해요' ";
                                    break;
                                    case 3:
                                        $searchStr .= " AND ca_1.ai_map_gmarket=2 AND ca_1.card_title!='상품소개해요' ";
                                    break;
                                }

                                if($start_req_data && $end_req_data){
                                    $searchStr .= " AND ca_1.req_data>'".$start_req_data." 00:00:00' AND ca_1.req_data<'".$end_req_data." 23:59:59'";
                                }

                                $count_query = "select count(DISTINCT(mem_id)) from Gn_Iam_Name_Card ca_1 WHERE group_id is NULL AND admin_shopping!=0 $searchStr";
                                $count_result = mysql_query($count_query);
                                $count_row = mysql_fetch_array($count_result);
                                $totalCnt	=  $count_row[0];

                                $query = "SELECT *, count(ca_1.mem_id) as cnt FROM Gn_Iam_Name_Card ca_1";
                                $query .= " WHERE group_id is NULL AND admin_shopping!=0 $searchStr";
                                $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number	= $totalCnt - ($nowPage - 1) * $pageCnt;
                                if(!$orderField)
                                    $orderField = "req_data";
                                $orderQuery .= " GROUP BY mem_id ORDER BY $orderField $dir $limitStr";
                                $i = 1;
                                $c=0;
                                $query .= $orderQuery;
                                $res = mysql_query($query);
                                while($row = mysql_fetch_array($res)) {
                                    $mem_sql = "select mem_code from Gn_Member where mem_id='$row[mem_id]'";
                                    $mem_res = mysql_query($mem_sql);
                                    $mem_row = mysql_fetch_array($mem_res);

                                    $fquery = "select count(*) from Gn_Iam_Friends where friends_card_idx = ".$row['idx'];
                                    $fresult = mysql_query($fquery);
                                    $frow = mysql_fetch_array($fresult);
                                    //$friend_count	=  $frow[0];

                                    $sql_pay = "select sum(TotPrice) totPrice, date from tjd_pay_result where buyer_id = '".$row[mem_id]."' and end_status='Y'";
                                    $res_result = mysql_query($sql_pay);
                                    $totPriceRow = mysql_fetch_row($res_result);
                                    $totPrice = $totPriceRow[0];

                                    $cquery = "select count(*) from Gn_Iam_Contents where westory_card_url = "."'$row[card_short_url]'";
                                    $cresult = mysql_query($cquery);
                                    $crow = mysql_fetch_array($cresult);
                                    
                                    if($row['ai_map_gmarket'] == 1){
                                        $chanel = "지도";
                                        $card_arr = array();
                                        $index_card = 0;
                                        $sql_card = "select card_title from Gn_Iam_Name_Card where mem_id='{$row[mem_id]}' order by idx asc";
                                        $res_card = mysql_query($sql_card);
                                        while($row_card = mysql_fetch_array($res_card)){
                                            if($row_card[card_title] == "업체보기"){
                                                $card_arr[$index_card] = 1;
                                            }
                                            if($row_card[card_title] == "메뉴보기"){
                                                $card_arr[$index_card] = 2;
                                            }
                                            if($row_card[card_title] == "리뷰보기"){
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
                                            <!-- <div style="overflow-x:hidden;width:100px;"> -->
                                                <?=$row[mem_id]?>
                                            <!-- </div> -->
                                        </td>
                                        <td>
                                            <!-- <div style="overflow-x:hidden;width:100px;"> -->
                                                <?=$chanel?>
                                            <!-- </div> -->
                                        </td>
                                        <td>
                                            <!-- <div style="overflow-x:hidden;width:100px;"> -->
                                                <?=$row[card_name]?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="">
                                                <?
                                                if($row[main_img1]){
                                                    $thumb_img =  $row[main_img1];
                                                }else{
                                                    $thumb_img =  $default_img;
                                                }
                                                ?>
                                                <a href="https://kiam.kr?<?=strip_tags($row['card_short_url'].$mem_row[mem_code])?>" target="_blank">
                                                    <img class="zoom" src="<?=$thumb_img?>" style="width:50px;">
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <!-- <div style="overflow-x:hidden;width:100px;"> -->
                                                <?=$row[card_addr]?>
                                            <!-- </div> -->
                                        </td>
                                        <td><?=$row[card_phone]?></td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" class="chkagree" name="status" id="card_idx_<?php echo $row['idx'];?>"<?php echo $row['phone_display']!="N"?"checked":""?> >
                                                <span class="slider round" name="status_round" id="card_idx_<?php echo $row['idx'];?>"></span>
                                            </label>
                                        </td>
                                        <td><?=$totPrice?$totPrice:"0"?></td>
                                        <td><?=$card_cnt?></td>
                                        <td><?=$row[req_data]?></td>
                                        <td><?=$crow[0]?></td>
                                        <td><?=$row[iam_click]?></td>
                                        <td style="font-size:12px;">
                                            <label class="switch">
                                                <input type="checkbox" class="chkshare" name="chkshare" id="card_share_<?=$row['idx'];?>_<?=$row[req_worker_id]?>" <?php echo $row['worker_service_state']=="1"?"checked":"";?> >
                                                <span class="slider round" name="status_round" id="card_share_<?=$row['idx'];?>_<?=$row[req_worker_id]?>"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <?=$row[req_worker_id] != ''?$row[req_worker_id]:"신청";?>
                                        </td>
                                        <td style="font-size:12px;">
                                            <label class="switch">
                                                <input type="checkbox" class="chkclick" name="cardclick" id="card_click_<?=$row['idx'];?>" <?php echo $row['org_use_state']=="1"?"checked":"";?> >
                                                <span class="slider round" name="status_round" id="card_click_<?=$row['idx'];?>"></span>
                                            </label>
                                        </td>
                                        <!-- <td><input type = "number" class = "number" value='<?=$row[sample_order]?>' style="width: 50px;text-align: right" data-no="<?=$row['idx']?>"></td> -->
                                        <td><a href="javascript:delNameCard('<?=$row['idx']?>')">삭제</a></td>
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
<script language="javascript">
function delNameCard(card_idx){
    if($("input[id=card_click_"+card_idx+"]").prop("checked")){
        alert("이미 판매자가 사용중이기때문에 판매자, 본사와 협의한 후에 삭제할수 있습니다.");
        return;
    }
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

    $('.check').on("click",function(){
        var worker_service_state = "0";
        if($(this).prop("checked")){
            $('.chkshare').prop("checked",true);
            worker_service_state = "1";
        }
        else{
            $('.chkshare').prop("checked",false);
            worker_service_state = "0";
        }

        var no_array = [];
        var check_array = $("#example1").children().find(".chkshare");
        var index = 0;
        check_array.each(function(){
            var arr1 = $(this).attr('id').split("_");
            no_array[index++] = arr1[2].trim();
        });

        console.log(no_array.toString());
        $.ajax({
            type:"POST",
            url:"/admin/ajax/worker_share_reg.php",
            data:{
                mode:"worker_share_reg",
                worker_service_state:worker_service_state,
                card_idx:no_array.toString()
                },
            success:function(data){
                // location.reload();
            },
            error: function(){
                alert('삭제 실패');
            }
        });
    });
    
    $('.chkshare').change(function() {
        if(!$('#check_all_member').prop("checked")){
            $('#check_all_member').prop("checked",false);
        }
        
        var worker_service_state = $(this)[0].checked;
        /*if(worker_service_state){
            if(!confirm("사업자공급리스트에 노출 시키겠습니까?")){
                return;
            }
        }
        else{
            if(!confirm("사업자공급리스트에 노출 해지 시키겠습니까?")){
                return;
            }
        }*/

        var id = $(this)[0].id;
        var card_idx = id.split("_")[2];
        var req_id = id.split("_")[3];
        if(worker_service_state){
            worker_service_state = "1";
        }else{
            if(req_id != ''){
                alert("이미 신청되어 있습니다.");
                return;
            }
            worker_service_state = "0";
        }
        $.ajax({
            type:"POST",
            url:"/admin/ajax/worker_share_reg.php",
            data:{
                mode:"worker_share_reg",
                worker_service_state:worker_service_state,
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
</script>
     