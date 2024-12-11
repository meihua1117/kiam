<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$repo_id = $_GET['repo'];
$sql = "select * from gn_report_form where id=$repo_id";
$res = mysqli_query($self_con,$sql);
$row = mysqli_fetch_array($res);

$query = "SELECT count(idx) FROM gn_report_table where repo_id = $repo_id ";
$res    = mysqli_query($self_con,$query);
$totalRow	=  mysqli_fetch_array($res);
$totalCnt = $totalRow[0];
$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
$startPage = $nowPage?$nowPage:1;
$pageCnt = 100;
$limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
$number	= $totalCnt - ($nowPage - 1) * $pageCnt;
$orderQuery .= "ORDER BY idx desc $limitStr";

$sql = "select * from gn_report_table where repo_id = $repo_id ";
if($_REQUEST['sday']){
    $sql.=" and reg_date >= '{$_REQUEST['sday']}' ";
}
if($_REQUEST['eday']){
    $sql.=" and reg_date <= '{$_REQUEST['eday']}' ";
}
$excel_sql=str_replace("'","`",$sql);
//$excel_sql=str_replace(" ","#",$excel_sql);
$sql .= $orderQuery;
$repo_res = mysqli_query($self_con,$sql);
?>
<style>
.report_item{
    width: 100%;
    margin-top: 10px;
    padding-left: 20px;
    padding-right: 20px;
    border: none !important;
    overflow:auto !important;
}
.report_item:last-child {
    margin-bottom: 20px;
}
th,td{
    white-space: nowrap;
}
input{
    vertical-align: middle !important;
    border: 1px solid #CCC !important;
}
thead tr th{position: sticky; top: 0; background: #ebeaea;}
thead tr:nth-child(2) th { position: sticky; top: 57px; }
</style>
<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<!--오픈그래프 (웹페이지 미리보기 -페이스북, 카카오톡)-->
	<meta property="og:title" content="<?=$row['title']?>">  <!--제목-->
	<meta property="og:description" content="<?=$row['descript']?>">  <!--내용-->
	<!--오픈그래프 끝-->
	<title>아이엠 하나이면 홍보와 소통이 가능하다</title>
	<link rel="shortcut icon" href="img/common/iconiam.ico">
	<link rel="stylesheet" href="css/notokr.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.js"></script>
    <script src="js/jquery-signature.js"></script>
    <script src="js/jquery.report_form.js"></script>
    <script language="javascript" src="/js/rlatjd.js"></script>
</head>
<body >
	<div id="wrap1" class="common-wrap" style="overflow:hidden">
		<main id="star" class="common-wrap" style="border: none !important"><!-- 컨텐츠 영역 시작 -->
			<section id="bottom">
				<div class="content-item" style="border: none !important;box-shadow: none !important;">
					<div style="margin-top: 20px;margin-left: 20px">
                        <h2 class="title"><?=$row['title']?></h2>
					</div>
                    <div style="margin-top: 10px;margin-left: 20px;margin-right: 20px;border-bottom: 1px solid #ddd;">
                        <h4 class="desc"><?=$row['descript']?></h4>
                    </div>
                    <form name="pay_form1" action="" method="post" class="my_pay" enctype="multipart/form-data" style="margin-top: 10px">
                        <div style="margin-top: 10px;margin-left: 20px;margin-right: 20px;">
                            <input type="date" name="sday" value="<?=$_REQUEST['sday']?>"/> ~
                            <input type="date" name="eday" value="<?=$_REQUEST['eday']?>"/>
                            <a onclick="pay_form1.submit();"><img src="/images/sub_mypage_11.jpg" /></a>
                            <a style="background-color: white;color: black;padding: 3px;border: 1px solid #000000;padding: 6px 5px;cursor: pointer" onclick="deleteMultiRow();">선택삭제</a>
                            <a onclick="excel_down('/excel_down/excel_report_list.php');return false;" style="background-color: white;color: black;padding: 3px;border: 1px solid #000000;padding: 6px 5px;cursor: pointer">엑셀저장</a>
                        </div>
                    </form>
                    <div class="table-responsive report_item">
                        <table class="table" style="border: 1px solid #ddd" id="report_table">
                            <thead>
                                <tr>
                                    <th  rowspan=2 style="width:5%;border: 1px solid #ddd;">
                                        <input type="checkbox" class="check" id="check_all" value="0">
                                    </th>
                                    <th rowspan=2 style="border: 1px solid #ddd;width: 20px">번호</th>
                                    <th rowspan=2 style="border: 1px solid #ddd;width: 20px">아이디</th>
                                    <th rowspan=2 style="border: 1px solid #ddd;width: 20px">이름</th>
                                    <th rowspan=2 style="border: 1px solid #ddd;width: 20px">휴대폰</th>
                                    <th rowspan=2 style="border: 1px solid #ddd;width: 20px">응답일시</th>
                                <?
                                $form_arr = array();
                                $item_arr = array();
                                $sql1 = "select * from gn_report_form1 where form_id=$repo_id and item_type <> 2 order by item_order";
                                $res1 = mysqli_query($self_con,$sql1);
                                while($row1 = mysqli_fetch_array($res1)){
                                    array_push($form_arr,$row1);
                                    $sql2 = "select count(id) from gn_report_form2 where form_id=$repo_id and item_id = {$row1['id']}";
                                    $res2 = mysqli_query($self_con,$sql2);
                                    $row2 = mysqli_fetch_array($res2);
                                    ?>
                                    <th colspan="<?=$row2[0]?>" style="border: 1px solid #ddd">
                                        <a href="javascript:show_more('<?=str_replace("\n", "<br>", $row1['item_title'])?>')"><?=cut_str($row1['item_title'], 10)?></a><br>
                                        <a href="javascript:show_more('<?=str_replace("\n", "<br>", $row1['item_req'])?>')"><?=cut_str($row1['item_req'], 10)?>
                                    </th>
                                <?}?>
                                    <th rowspan=2 style="border: 1px solid #ddd;width: 20px">서명</th>
                                </tr>
                                <tr>
                                    <?
                                    foreach($form_arr as $form){
                                        $sql2 = "select * from gn_report_form2 where form_id=$repo_id and item_id = {$form['id']} order by id";
                                        $res2 = mysqli_query($self_con,$sql2);
                                        while($row2 = mysqli_fetch_array($res2)){
                                            $row2['item_type'] = $form['item_type'];
                                            array_push($item_arr,$row2);
                                    ?>
                                            <th style="border: 1px solid #ddd">
                                                <a href="javascript:show_more('<?=str_replace("\n", "<br>", $row2['tag_name'])?>')"><?=cut_str($row2['tag_name'], 10)?>
                                            </th>
                                    <?  }
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $i = 1;
                                while($repo_row = mysqli_fetch_array($repo_res)){
                                    $conts = json_decode($repo_row['cont'],true);
                                ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="check" id="check_one" value="<?=$repo_row['idx']?>">
                                    </td>
                                    <td style="border: 1px solid #ddd"><?=($startPage-1)*$pageCnt + $i?></td>
                                    <td style="border: 1px solid #ddd"><?=$repo_row['userid']?></td>
                                    <td style="border: 1px solid #ddd"><?=$repo_row['name']?></td>
                                    <td style="border: 1px solid #ddd"><?=$repo_row['phone']?></td>
                                    <td style="border: 1px solid #ddd"><?=$repo_row['reg_date']?></td>
                                    <?foreach($item_arr as $item){
                                        foreach($conts as $cont){
                                            foreach($cont as $key => $value)
                                            {
                                                if($item['tag_id'] == $key)
                                                    $repo_value = $value;
                                            }
                                        }
                                    ?>
                                    <td style="border: 1px solid #ddd">
                                        <?if($item['item_type'] == 0 || $item['item_type'] == 3){
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
                    </div>
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
                    <div id="ajax_div" style="display:none"></div>
			</section>
		</main>
	</div>
</body>
<div id="show_zoom" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" id="modal_zoom" style="margin: 100px auto;width: 730px">
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
<form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
    <input type="hidden" name="excel_sql" value="<?=base64_encode($excel_sql)?>" />
    <input type="hidden" name="index" value="<?=$repo_id?>" />
</form>
<iframe name="excel_iframe" style="display:none;"></iframe>
<script>
    function show_more(str){
        $("#contents_detail").html(str);
        $("#show_detail_more").modal("show");
    }
    function goPage(pgNum) {
        location.href = '?nowPage='+pgNum+"&repo=<?php echo $_GET['repo'];?>";
    }
    function sign_zoom(obj){
        var index = $(obj).data("index");
        $("#signatureJSON").val($('#signatureJSON' + index).val());
        var w = $(window).width();
        var zoom = w / 730;
        if(zoom > 1)
            zoom = 1;
        $("#modal_zoom").css("zoom",zoom);
        $("#show_zoom").modal("show");
        $('#zoom_sign').signature('enable').signature('draw', $('#signatureJSON').val()).signature('disable');
    }
    $(function(){
        var height = window.outerHeight;
        //$(".report_item").css("height",height);
        $("#zoom_sign").signature({syncField: "#signatureJSON"});
        $('#zoom_sign').signature({disabled: true});
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
                    index:<?=$repo_id?>
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
</script>
</html>
