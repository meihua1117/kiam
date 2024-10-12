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
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
function goPage(pgNum) {
    location.href = '?nowPage='+pgNum+"&search_id=<?=$search_id?>&search_group=<?=$search_group?>&search_card=<?=$search_card?>&orderField=<?=$orderField?>&dir=<?=$dir?>";
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
            <h1>그룹 페이지 관리<small>그룹페이지를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">그룹 페이지 관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:10px">
                <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location.href='card_click_list.php'"><i class="fa"></i>아이엠 카드 관리</button>
                    <form method="get" name="search_form" id="search_form">
                        <div class="box-tools">
                            <div class="input-group" style="width: 250px;display:flex">
                                <input type="text" name="search_id" id="search_id" class="form-control input-sm pull-right" placeholder="아이디" value="<?=$search_id?>">
                                <input type="text" name="search_group" id="search_group" class="form-control input-sm pull-right" placeholder="그룹명" value="<?=$search_group?>">
                                <input type="text" name="search_card" id="search_card" class="form-control input-sm pull-right" placeholder="카드명" value="<?=$search_card?>">
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
                                <col width="2%">
                                <col width="2%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
                                <col width="5%">
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
                                    <th>IDX</th>
                                    <th><a href="?orderField=mem_id&dir=<?=$dir?>" class="sort-by">아이디</a></th>
                                    <th>그룹명</th>
                                    <th>카드명</th>
                                    <th>카드<br>링크</th>
                                    <th>IMAGE</th>
                                    <th>휴대폰</th>
                                    <th><a href="?orderField=phone_display&dir=<?=$dir?>" class="sort-by">노출<br>여부</a></th>
                                    <th>멤버수</th>
                                    <th><a href="?orderField=req_data&dir=<?=$dir?>" class="sort-by">등록<br>일자</a></th>
                                    <th>컨텐<br>츠수</th>
                                    <th ><a href="?orderField=iam_click&dir=<?=$dir?>" class="sort-by">조회<br>건수</a></th>
                                    <th><a href="?orderField=iam_share&dir=<?=$dir?>" class="sort-by">공유<br>건수</a></th>
                                    <th><a href="?orderField=sample_click&dir=<?=$dir?>" class="sort-by">샘플<br>선택</th>
                                    <th><a href="?orderField=sample_order&dir=<?=$dir?>" class="sort-by">샘플<br>순위</th>
                                    <th>수정<br>삭제</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                //디폴트 아바타
                                $sql = "select main_img1 from Gn_Iam_Info where mem_id = 'obmms02'";
                                $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                                $row=mysqli_fetch_array($result);
                                $default_img =  $row['main_img1'];


                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $search_id ? " AND (ca_1.mem_id LIKE '%".$search_id."%' )" : null;
                                $searchStr .= $search_group ? " AND (name LIKE '%".$search_group."%' )" : null;
                                $searchStr .= $search_card ? " AND (ca_1.card_name like '%".$search_card."%' )" : null;

                                $count_query = "select count(idx) from Gn_Iam_Name_Card ca_1 WHERE group_id is not NULL and group_id > 0 $searchStr";
                                $count_result = mysqli_query($self_con,$count_query);
                                $count_row = mysqli_fetch_array($count_result);
                                $totalCnt	=  $count_row[0];

                                $query = "SELECT ca_1.*,gi.name as group_name FROM Gn_Iam_Name_Card ca_1 left join gn_group_info gi on gi.idx=ca_1.group_id";
                                $query .= " WHERE group_id is not NULL and group_id > 0 $searchStr";
                                $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number	= $totalCnt - ($nowPage - 1) * $pageCnt;
                                if(!$orderField)
                                    $orderField = "req_data";
                                $orderQuery .= " ORDER BY $orderField $dir $limitStr";
                                $i = 1;
                                $c=0;
                                $query .= $orderQuery;
                                $res = mysqli_query($self_con,$query);
                                while($row = mysqli_fetch_array($res)) {
                                    $mem_sql = "select mem_code,site_iam from Gn_Member where mem_id='{$row['mem_id']}'";
                                    $mem_res = mysqli_query($self_con,$mem_sql);
                                    $mem_row = mysqli_fetch_array($mem_res);

                                    $fquery = "select count(*) from gn_group_member where group_id = ".$row['group_id'];
                                    $fresult = mysqli_query($self_con,$fquery);
                                    $frow = mysqli_fetch_array($fresult);

                                    $cquery = "select count(*) from Gn_Iam_Contents where westory_card_url = "."'{$row['card_short_url']}'";
                                    $cresult = mysqli_query($self_con,$cquery);
                                    $crow = mysqli_fetch_array($cresult);?>
                                    <tr>
                                        <td><?=$number--?></td>
                                        <td><?=$row['idx']?></td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=$row['mem_id']?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=$row['group_name']?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <?=$row['card_name']?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="overflow-x:hidden;width:100px;">
                                                <a href="<?=($mem_row['site_iam'] == 'kiam'?'http://kiam.kr/?': 'http://'.$mem_row['site_iam'].'.kiam.kr/?').strip_tags($row['card_short_url'].$mem_row['mem_code']).'&preview=Y'?>" target="_blank"><?=$row['card_short_url']?></a>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <?
                                                if($row['main_img1']){
                                                    $thumb_img =  $row['main_img1'];
                                                }else{
                                                    $thumb_img =  $default_img;
                                                }
                                                ?>
                                                <a href="<?=$thumb_img?>" target="_blank">
                                                    <img class="zoom" src="<?=$thumb_img?>" style="width:50px;">
                                                </a>
                                            </div>
                                        </td>
                                        <td><?=$row['card_phone']?></td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" class="chkagree" name="status" id="card_idx_<?php echo $row['idx'];?>"<?php echo $row['phone_display']!="N"?"checked":""?> >
                                                <span class="slider round" name="status_round" id="card_idx_<?php echo $row['idx'];?>"></span>
                                            </label>
                                        </td>
                                        <td><?=$frow[0]?></td>
                                        <td><?=$row['req_data']?></td>
                                        <td><?=$crow[0]?></td>
                                        <td><?=$row['iam_click']?></td>
                                        <td><?=$row[iam_share]?></td>
                                        <td style="font-size:12px;">
                                            <label class="switch">
                                                <input type="checkbox" class="chkclick" name="cardclick" id="card_click_<?=$row['idx'];?>" <?php echo $row['sample_click']=="Y"?"checked":"";?> >
                                                <span class="slider round" name="status_round" id="card_click_<?=$row['idx'];?>"></span>
                                            </label>
                                        </td>
                                        <td><input type = "number" class = "number" value='<?=$row['sample_order']?>' style="width: 50px;text-align: right" data-no="<?=$row['idx']?>"></td>
                                        <td><a href="javascript:delNameCard('<?=$row['idx']?>')">삭제</a></td>
                                    </tr>
                                    <?
                                    $c++;
                                    $i++;
                                }
                                if($i == 1) {?>
                                    <tr>
                                        <td colspan="17" style="text-align:center;background:#fff">등록된 내용이 없습니다.</td>
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
        var id = $(this)[0].id;
        var sample_click = $(this)[0].checked;
        if(sample_click){
            sample_click = "Y";
        }else{
            sample_click = "N";
        }
        var card_idx = id.split("_")[2];
        $.ajax({
            type:"POST",
            url:"/admin/ajax/_db_controller.php",
            data:{
                mode:"update_name_card - sample_click",
                sample_click:sample_click,
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
    