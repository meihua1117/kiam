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
    location.href = '?nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&search_type=<?php echo $_GET['search_type'];?>&sdate=<?php echo $_GET['sdate'];?>&edate=<?php echo $_GET['edate'];?>";
}

</script>   
<style>
.loading_div {
    display:none;
    position: fixed;
    left: 50%;
    top: 50%;
    z-index: 1000;
}
#open_recv_div li{list-style: none;}
th,td{
    text-align: center;
}
.switch_repo_status {
    position: relative;
    display: inline-block;
    width: 55px;
    height: 28px;
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
.wrapper{height:100%;overflow:auto !important;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important;}
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
            <h1>공개디비광고관리</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">공개디비광고관리</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <div style="padding:10px">
                        <? if($_SESSION['one_member_admin_id'] != "onlyonemaket"){
                                if($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == $HTTP_HOST) {} else {?>
                                    <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_member_down_.php');return false;"><i class="fa fa-download"></i> 엑셀다운받기</button>
                                <? }
                        }?>
                        <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="deleteMultiRow();"><i class="fa"></i> 선택삭제</button>
                        <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="onSetCost();"><i class="fa"></i> 요금설정</button>
                    </div>
                    <form method="get" name="search_form" id="search_form" class="form-inline">
                        <div class="box-tools">
                            <div class="input-group">
                                <div class="form-group">
                                    <input type="date" style="height: 30px" name="sdate" placeholder="" id="search_start_date" value="<?=$_REQUEST['sdate']?>" multiple/> ~
                                    <input type="date" style="height: 30px" name="edate" placeholder="" id="search_end_date" value="<?=$_REQUEST['edate']?>"/>
                                </div>
                                <div class="form-group">
                                    <input type="text" name="s_key" id="s_key" value="<?=$s_key?>" class="form-control input-sm pull-right" placeholder="셀링소속">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="i_key" id="i_key" value="<?=$i_key?>" class="form-control input-sm pull-right" placeholder="아이엠소속">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="identifier" id="identifier" value="<?=$identifier?>" class="form-control input-sm pull-right" placeholder="아이디">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="n_key" id="n_key" value="<?=$n_key?>" class="form-control input-sm pull-right" placeholder="이름">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="mesasge_content" id="mesasge_content" class="form-control input-sm pull-right"  placeholder="발신내용" value="<?=$mesasge_content?>">
                                </div>
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
                        <table id="report_table" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="30px">
                                <col width="50px">
                                <col width="80px">
                                <col width="70px">
                                <col width="70px">
                                <col width="90px">
                                <col width="90px">
                                <col width="90px">
                                <col width="90px">
                                <col width="90px">
                                <col width="90px">
                                <col width="90px">
                                <col width="90px">
                                <col width="90px">
                                <col width="90px">
                                <col width="90px">
                                <col width="90px">
                                <col width="90px">
                                <col width="90px">
                                <col width="90px">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="check" id="check_all" value="0"></th>
                                    <th>번호</th>
                                    <th>셀링<br>/IAM소속</th>
                                    <th>이름</th>
                                    <th>아이디</th>
                                    <th>문자형태</th>
                                    <th>전송건수</th>
                                    <th>1차성공</th>
                                    <th>2차성공</th>
                                    <th>실패건수</th>
                                    <th>총사용액</th>
                                    <th>메시지</th>
                                    <th>광고제목</th>
                                    <th>세부설명</th>
                                    <th>지역선택</th>
                                    <th>직업업종</th>
                                    <th>연령선택</th>
                                    <th>성별선택</th>
                                    <th>학력선택</th>
                                    <th>결혼여부</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;

                                // 검색 조건을 적용한다.
                                $searchStr = '1=1';
                                if($sdate)
                                    $searchStr .= " AND r.reg_date >= '$sdate'";
                                if($edate)
                                    $searchStr .= " AND r.reg_date <= '$edate'";
                                if($search_key){
                                    if($search_type == 'a')
                                        $searchStr .= " AND (r.user_id LIKE '%$search_key%' or r.title like '%$search_key%'  or r.descript like '%$search_key%' or m.mem_name like '%$search_key%')" ;
                                    else
                                        $searchStr .= " AND $search_type LIKE '%$search_key%'" ;
                                }

                                $query = "SELECT count(r.id) FROM gn_report_form r inner join Gn_Member m on m.mem_id = r.user_id WHERE $searchStr";
                                $res    = mysqli_query($self_con, $query);
                                $totalRow	=  mysqli_fetch_array($res);
                                $totalCnt = $totalRow[0];
                                //$query = "SELECT r.*,m.mem_name,m.site_iam FROM gn_report_form r inner join Gn_Member m on m.mem_id = r.user_id WHERE $searchStr ";
                                $query = "SELECT r.* FROM gn_report_form r use index(user_id) WHERE $searchStr ";
                                $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number	= $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= "ORDER BY r.id desc $limitStr";
                                $i = 1;
                                $query .= "$orderQuery";
                                $res = mysqli_query($self_con, $query);
                                while($row = mysqli_fetch_array($res)) {
                                    $mem_sql = "SELECT mem_name,site_iam FROM Gn_Member use index(mem_id) where mem_id='$row[user_id]'";
                                    $mem_res = mysqli_query($self_con, $mem_sql);
                                    $mem_row = mysqli_fetch_array($mem_res);
                            ?>
                                <tr>
                                    <td><input type="checkbox" class="check" id="check_one" name="" value="<?=$row['id']?>"></td>
                                    <td style="font-size:12px;"><?=($startPage-1)*$pageCnt + $i?></td>
                                    <td style="font-size:12px;"><?=$mem_row['mem_name']?></td>
                                    <td style="font-size:12px;"><?=$row['user_id']?></td>
                                    <td style="font-size:12px;"><?=$mem_row['site_iam']?></td>
                                    <td style="font-size:12px;">
                                        <a href="javascript:show_more('<?='title'.$i?>')"><?=cut_str($row['title'], 10)?></a>
                                        <input type="hidden" id=<?='title'.$i?> value="<?=htmlspecialchars($row['title'])?>">
                                    </td>
                                    <td style="font-size:12px;">
                                        <a href="javascript:show_more('<?='desc'.$i?>')"><?=cut_str($row['descript'], 10)?></a>
                                        <input type="hidden" id=<?='desc'.$i?> value="<?=htmlspecialchars($row['descript'])?>">
                                    </td>
                                    <?
                                    $sql1 = "select count(idx) from gn_report_table where repo_id='{$row['id']}'";
                                    $res1 = mysqli_query($self_con, $sql1);
                                    $row1 = mysqli_fetch_array($res1);
                                    $count = $row1[0];
                                    if($count == null)
                                        $count = 0;
                                    ?>
                                    <td style="font-size:12px;"><?=$row['visit']?>/<?=$count?></td>
                                    <td class="iam_table">
                                        <?
                                            $link_pre = "/iam/report_preview.php?repo={$row['id']}";
                                            $link = $row['short_url'];
                                        ?>
                                        <input type="button" value="미리보기" class="button" onclick="viewEvent('<?=$link_pre?>')">
                                        <input type="button" value="링크복사" class="button copyLinkBtn" data-link="<?=$link?>">
                                    </td>
                                    <td><?=$row['reg_date']?></td>
                                    <td>
                                        <textarea id="<?='detail'.$row['id']?>" style="width:100%;"><?=$row['detail']?></textarea>
                                        <br>
                                        <button type="button" class="btn-default" style="margin-top:5px;padding:5px 10px;border:1px solid #ccc;cursor:pointer" onclick="save_detail(<?=$row['id']?>);">저장</button>
                                    </td>
                                    <td><a href="report_reply.php?repo=<?=$row['id']?>" target="_blank">답변</a></td>
                                    <td>
                                        <?if($count != 0){?>
                                            <a onclick="deleteRow(<?=$row['id']?>)">삭제</a>
                                        <?}else{?>
                                            <a href="report_edit.php?repo=<?=$row['id']?>">수정</a>/<a href="javascript:delete_report(<?=$row['id']?>)">삭제</a>
                                        <?}?>
                                    </td>
                                    <td>
                                        <label class="switch_repo_status" style="margin:0 25px;">
                                            <input type="checkbox" name="status" id="stauts_logo_<?php echo $row['id'];?>" value="<?php echo $row['id'];?>" <?php echo $row['status']==1?"checked":""?>>
                                            <span class="slider round" name="status_round" id="stauts_round_<?php echo $row['idx'];?>"></span>
                                        </label>
                                    </td>
                              </tr>
                            <?
                                $i++;
                            }
                            if($i == 1) {
                            ?>
                                <tr>
                                    <td colspan="14" style="text-align:center;background:#fff">
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
</div>
<div id="show_detail_more" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="border:none;background-color: rgb(130,199,54)">
                <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -12px;cursor:pointer;">X</a>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;box-shadow: none;width: 100%;">
                    <p style="font-size:16px;color:#6e6c6c" id="contents_detail">
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer -->
<div id='open_recv_div' class="open_1">
    <div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
        <li class="open_recv_title open_2_1"></li>
        <li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">

    </div>
</div>
<script>
function viewEvent(str){
  window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
}
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 232;
    if(height < 375)
        height = 375;
    console.log("height="+ height);
    $(".box-body").css("height",height);
});
$(function() {
    $('.switch_repo_status').on("change", function() {
        var id = $(this).find("input[type=checkbox]").val();
        var status = $(this).find("input[type=checkbox]").is(":checked")==true?1:0;
        $.ajax({
            type:"POST",
            url:"/ajax/ajax.report.php",
            data:{
                method:'change_status',
                index:id,
                status:status
            },
            success:function(data){
                //location.reload();
            }
        })
    });
    $('.copyLinkBtn').bind("click", function() {
        var url = $(this).data("link");
        var aux1 = document.createElement("input");
        // 지정된 요소의 값을 할당 한다.
        aux1.setAttribute("value", url);
        // bdy에 추가한다.
        document.body.appendChild(aux1);
        // 지정된 내용을 강조한다.
        aux1.select();
        // 텍스트를 카피 하는 변수를 생성
        document.execCommand("copy");
        // body 로 부터 다시 반환 한다.
        document.body.removeChild(aux1);
        alert("URL이 복사되었습니다. 원하는 곳에 붙여 넣으세요.");       
    });
    $('.check').on("click",function(){
        if($(this).prop("id") == "check_all"){
            if($(this).prop("checked"))
                $('.check').prop("checked",true);
            else
                $('.check').prop("checked",false);
        }else if($(this).prop("id") == "check_one"){
            if(!$(this).prop("checked"))
                $('#check_all').prop("checked",false);
        }
    });
});
function deleteMultiRow() {
    if(confirm('삭제하시겠습니까?')) {
        var check_array = $("#report_table").children().find(".check");
        var no_array = [];
        var index = 0;
        check_array.each(function(){
            if($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });
        if(index == 0){
            alert("삭제할 리포트를 선택해주세요.");
            return;
        }
        $.ajax({
            type:"POST",
            url:"/ajax/ajax.report.php",
            dataType:"json",
            data:{
                method:"del",
                index:no_array.toString()
            },
            success: function(data){
                if(data.result == "success"){
                    alert('성공적으로 삭제 되었습니다.');
                    window.location.reload();
                }
            }
        })
    }
}
function deleteRow(repo) {
    if(confirm('삭제하시겠습니까?')) {
        $.ajax({
            type:"POST",
            url:"/ajax/ajax.report.php",
            dataType:"json",
            data:{
                method:"del",
                index:repo
            },
            success: function(data){
                if(data.result == "success"){
                    alert('성공적으로 삭제 되었습니다.');
                    window.location.reload();
                }
            }
        })
    }
}
function delete_report(id){
    if(confirm("삭제하시겠습니까?")){
        $.ajax({
            type:"POST",
            url:"/ajax/ajax.report.php",
            dataType:"json",
            data:{
                method:"del",
                index:id
            },
            success: function(data){
                if(data.result == "success"){
                    alert('성공적으로 삭제 되었습니다.');
                    window.location.reload();
                }
            }
        });
    }
}
function cloneMultiRow() {
    if(confirm('복제하시겠습니까?')) {
        var check_array = $("#report_table").children().find(".check");
        var no_array = [];
        var index = 0;
        check_array.each(function(){
            if($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });
        if(index == 0){
            alert("복제할 리포트를 선택해주세요.");
            return;
        }
        $.ajax({
            type:"POST",
            url:"/ajax/ajax.report.php",
            dataType:"json",
            data:{
                method:"clone",
                index:no_array.toString()
            },
            success: function(data){
                if(data.result == "success"){
                    alert('성공적으로 복제 되었습니다.');
                    window.location.reload();
                }
            }
        })
    }
}
function show_more(str){
    $("#contents_detail").html($("#"+str).val());
    $("#show_detail_more").modal("show");
}
function save_detail(idx){
    $.ajax({
        type:"POST",
        url:"/ajax/ajax.report.php",
        dataType:"json",
        data:{
            method:"save_report_detail",
            id:idx,
            cont:$("#detail" + idx).val()
        },
        success:function (data) {
        }
    });
}

function onSetCost() {
    
}

</script>