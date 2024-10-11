<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
$repo = $_GET['repo'];
$sql = "select * from gn_report_form where id=$repo";
$res = mysqli_query($self_con,$sql);
$row = mysqli_fetch_array($res);

$query = "SELECT count(idx) FROM gn_report_table where repo_id=$repo";
$res    = mysqli_query($self_con,$query);
$totalRow	=  mysqli_fetch_array($res);
$totalCnt = $totalRow[0];
$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
$startPage = $nowPage?$nowPage:1;
$pageCnt = 100;
$limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
$number	= $totalCnt - ($nowPage - 1) * $pageCnt;
$orderQuery .= "ORDER BY idx desc $limitStr";
$sql = "select * from gn_report_table where repo_id=$repo ";
if($_REQUEST[sdate]){
    $sql.=" and reg_date >= '{$_REQUEST[sdate]}' ";
}
if($_REQUEST[edate]){
    $sql.=" and reg_date <= '{$_REQUEST[edate]}' ";
}
$excel_sql=str_replace("'","`",$sql);
$sql .= $orderQuery;
$repo_res = mysqli_query($self_con,$sql);

?>
<script src="/iam/js/jquery-3.1.1.min.js"></script>
<script src="/iam/js/jquery-ui-1.10.3.custom.js"></script>
<script src="/iam/js/jquery-signature.js"></script>
<script src="/iam/js/jquery.report_form.js"></script>
<script>
function goPage(pgNum) {
    location.href = '?nowPage='+pgNum+"&repo=<?php echo $_GET['repo'];?>";
}

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
th,td{
    white-space: nowrap;
}
thead tr th{position: sticky; top: 0; background: #ebeaea;}
thead tr:nth-child(2) th { position: sticky; top: 57px; }
.box-body{padding:0px !important;}
.box{border:none !important}
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
            <h1><?=$row['title']?><small><?=$row['descript']?></small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">리포트 답변보기</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <div style="padding:10px">
                        <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="excel_down('/excel_down/excel_report_list.php');return false;"><i class="fa"></i> 엑셀저장</button>
                        <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="deleteMultiRow();"><i class="fa"></i> 선택삭제</button>
                    </div>
                    <form method="post" name="search_form" id="search_form" class="form-inline">
                        <div class="box-tools">
                            <div class="input-group">
                                <div class="form-group">
                                    <input type="date" style="height: 30px" name="sdate" placeholder="" id="search_start_date" value="<?=$_REQUEST[sdate]?>" multiple/> ~
                                    <input type="date" style="height: 30px" name="edate" placeholder="" id="search_end_date" value="<?=$_REQUEST[edate]?>"/>
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
                    <div class="box-body"  style="overflow: auto !important">
                        <table id="report_table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th rowspan=2 style="border: 1px solid #ddd;width: 5px"><input type="checkbox" class="check" id="check_all" value="0"></th>
                                <th rowspan=2 style="border: 1px solid #ddd;width: 20px">번호</th>
                                <th rowspan=2 style="border: 1px solid #ddd;width: 20px">아이디</th>
                                <th rowspan=2 style="border: 1px solid #ddd;width: 20px">이름</th>
                                <th rowspan=2 style="border: 1px solid #ddd;width: 20px">휴대폰</th>
                                <th rowspan=2 style="border: 1px solid #ddd;width: 20px">응답일시</th>
                                <?
                                $form_arr = array();
                                $item_arr = array();
                                $sql1 = "select * from gn_report_form1 where form_id=$repo and item_type <> 2 order by item_order";
                                $res1 = mysqli_query($self_con,$sql1);
                                while($row1 = mysqli_fetch_array($res1)){
                                    array_push($form_arr,$row1);
                                    $sql2 = "select count(id) from gn_report_form2 where form_id=$repo and item_id = {$row1['id']}";
                                    $res2 = mysqli_query($self_con,$sql2);
                                    $row2 = mysqli_fetch_array($res2);
                                    ?>
                                    <th colspan="<?=$row2[0]?>" style="border: 1px solid #ddd">
                                        <a href="javascript:show_more('<?=str_replace("\n", "<br>", $row1[item_title])?>')"><?=cut_str($row1['item_title'], 10)?></a><br>
                                        <a href="javascript:show_more('<?=str_replace("\n", "<br>", $row1[item_req])?>')"><?=cut_str($row1['item_req'], 10)?>
                                    </th>
                                <?}?>
                                <th rowspan=2 style="border: 1px solid #ddd;width: 20px">서명</th>
                            </tr>
                            <tr>
                                <?
                                foreach($form_arr as $form){
                                    $sql2 = "select * from gn_report_form2 where form_id=$repo and item_id = {$form['id']}  order by id";
                                    $res2 = mysqli_query($self_con,$sql2);
                                    while($row2 = mysqli_fetch_array($res2)){
                                        $row2['item_type'] = $form[item_type];
                                        array_push($item_arr,$row2);
                                        ?>
                                        <th style="border: 1px solid #ddd">
                                            <a href="javascript:show_more('<?=str_replace("\n", "<br>", $row2[tag_name])?>')"><?=cut_str($row2['tag_name'], 10)?>
                                        </th>
                                    <?  }
                                }
                                ?>
                            </tr>
                            </thead>
                            <tbody>
                            <?
                            $i = 1;
                            while($repo_row = mysqli_fetch_array($repo_res)){?>
                                <tr>
                                    <td style="border: 1px solid #ddd"><input type="checkbox" class="check" id="check_one" name="" value="<?=$repo_row['idx']?>"></td>
                                    <td style="border: 1px solid #ddd"><?=($startPage-1)*$pageCnt + $i?></td>
                                    <td style="border: 1px solid #ddd"><?=$repo_row['userid']?></td>
                                    <td style="border: 1px solid #ddd"><?=$repo_row['name']?></td>
                                    <td style="border: 1px solid #ddd"><?=$repo_row['phone']?></td>
                                    <td style="border: 1px solid #ddd"><?=$repo_row['reg_date']?></td>
                                    <?
                                    $conts = json_decode($repo_row['cont'],true);
                                    foreach($item_arr as $item){
                                        foreach($conts as $cont){
                                            foreach($cont as $key => $value)
                                            {
                                                if($item['tag_id'] == $key)
                                                    $repo_value = str_replace("'","`",$value);
                                            }
                                        }?>
                                        <td style="border: 1px solid #ddd">
                                            <?if($item[item_type] == 0 || $item[item_type] == 3){
                                                if(mb_strlen($repo_value,"UTF-8") < 10){
                                                    echo $repo_value;
                                                }else{?>
                                                <a href="javascript:show_more('<?=str_replace("\n", "<br>", $repo_value)?>')"><?=cut_str($repo_value, 10)?>
                                            <?  }
                                            }else{?>
                                                <input type="checkbox" <?if($repo_value) echo "checked"?> disabled>
                                            <?}?>
                                        </td>
                                    <?}?>
                                    <td style="border: 1px solid #ddd;padding: 0px">
                                        <div id="<?='sign'.$repo_row['idx']?>" style="width:700px;height: 90px;zoom:0.4" onclick="sign_zoom(this)" data-index="<?=$repo_row['idx']?>"></div>
                                        <textarea name="<?='signatureJSON'.$repo_row['idx']?>" id="<?='signatureJSON'.$repo_row['idx']?>" style="display: none" readonly=""><?=$repo_row['sign']?></textarea>
                                        <script>
                                            $(function() {
                                                $("#<?='sign'.$repo_row['idx']?>").signature({syncField: "#<?='signatureJSON'.$repo_row['idx']?>"});
                                                $('#<?='sign'.$repo_row['idx']?>').signature('enable').signature('draw', $('#<?='signatureJSON'.$repo_row['idx']?>').val()).signature('disable');
                                            });
                                        </script>
                                    </td>
                                </tr>
                            <?
                                $i++;
                            }
                            if($i == 1) {?>
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
                        echo drawPagingAdminNavi($totalCnt, $nowPage,$pageCnt);
                    ?>
                </div>
            </div>
        </section><!-- /.content -->
        </div><!-- /content-wrapper -->

        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
        <div id='open_recv_div' class="open_1">
            <div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
                <li class="open_recv_title open_2_1"></li>
                <li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>
            </div>
            <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">

            </div>
        </div>
        <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
            <input type="hidden" name="excel_sql" value="<?=base64_encode($excel_sql)?>" />
            <input type="hidden" name="index" value="<?=$repo?>" />
        </form>
        <iframe name="excel_iframe" style="display:none;"></iframe>
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
<div id="show_zoom" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: 730px">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="container" style="box-shadow: none;width: 100%;padding: 0px">
                    <div id="zoom_sign" style="width:700px;height: 90px;"></div>
                    <textarea name="signatureJSON" id="signatureJSON" style="display: none" readonly=""></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer -->

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
    $("#zoom_sign").signature({syncField: "#signatureJSON"});
    $('#zoom_sign').signature({disabled: true});
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
                location.reload();
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
function sign_zoom(obj){
    var index = $(obj).data("index");
    $("#signatureJSON").val($('#signatureJSON' + index).val());
    var w = $(window).width();
    var zoom = w / 730;
    if(zoom > 1)
        zoom = 1;
    $(".modal-dialog").css("zoom",zoom);
    $("#show_zoom").modal("show");
    $('#zoom_sign').signature('enable').signature('draw', $('#signatureJSON').val()).signature('disable');
}
function deleteMultiRow() {
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
    if(confirm('삭제하시겠습니까?')) {
        $.ajax({
            type:"POST",
            url:"/ajax/ajax.report.php",
            dataType:"json",
            data:{
                method:"delete_report",
                idx:no_array.toString(),
                index:<?=$repo?>
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
function show_more(str){
    $("#contents_detail").html(str);
    $("#show_detail_more").modal("show");
}
</script>