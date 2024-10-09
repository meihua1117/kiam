<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
//계정 삭제
function del_member_info(mem_code){
	var msg = confirm('정말로 삭제하시겠습니까?');
	if(msg){
        $.ajax({
            type:"POST",
            url:"/admin/ajax/user_leave.php",
            data:{mem_code:mem_code},
            success:function(){
                alert('삭제되었습니다.');
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

//주소록 다운
function excel_down_(){
	$("#excel_down_form").submit();
	return false;
}
function goPage(pgNum) {
   location.href = '?nowPage='+pgNum + '&search_type=' + '<?=$search_type?>'+'&search_key=' + '<?=$search_key?>'+'&rec_key=' + '<?=$rec_key?>';
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
                <h1>사업자배당관리<small>사업자 배당을 관리합니다.</small></h1>
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">사업자배당관리</li>
                </ol>
            </section>
            <!-- Main content -->
            <section class="content">
                <div class="row" id="toolbox">
                    <div class="col-xs-12" style="padding-bottom:20px">
                        <form method="get" name="search_form" id="search_form">
                            <div class="box-tools">
                                <div class="input-group" style="display:inline-flex;width: 250px;">
                                    <select name="search_type" class="form-inline" >
                                        <option value="">전체</option>
                                        <option value="2" <? echo $search_type == "2"?"selected":""?>>리셀러</option>
                                        <option value="3" <? echo $search_type == "3"?"selected":""?>>분양자</option>
                                    </select>
                                    <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/이름" value="<?=$search_key?>">
                                    <input type="text" name="rec_key" id="rec_key" class="form-control input-sm pull-right" placeholder="추천인" value="<?=$rec_key?>">
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
                            <table id="example1" class="table table-bordered table-striped">
                                <colgroup>
                                    <col width="60px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                    <col width="100px">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>번호</th>
                                        <th>회원<br>정보</th>
                                        <th>사업자<br>아이디</th>
                                        <th>사업자<br>이름</th>
                                        <th>사업자<br>직급</th>
                                        <th>추천인<br>아이디</th>
                                        <th>추천인<br>이름</th>
                                        <th>배당율/추가배당율</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $search_type ? " AND a.service_type =".$search_type : " AND a.service_type > 1";
                                $searchStr .= $search_key ? " AND (a.mem_id LIKE '%".$search_key."%' or a.mem_phone like '%".$search_key."%' or a.mem_name like '%".$search_key."%')" : null;
                                $searchStr .= $rec_key ? " AND (a.recommend_id LIKE '%".$rec_key."%' or b.mem_name like '%".$rec_key."%')" : null;
                                $order = $order?$order:"desc";
                                $query = "SELECT SQL_CALC_FOUND_ROWS a.*,b.mem_name as rec_name FROM Gn_Member a INNER JOIN  Gn_Member b on a.recommend_id = b.mem_id WHERE 1=1 $searchStr";
                                $res	    = mysql_query($query);
                                $totalCnt	=  mysql_num_rows($res);
                                $limitStr   = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number		= $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY a.mem_code DESC $limitStr";
                                $i = 1;
                                $query .= $orderQuery;
                                $res = mysql_query($query);
                                while($row = mysql_fetch_array($res)) {
                                    if($row['service_type'] == 2) {
                                        $mem_level = $service_type = "리셀러";
                                        $row['share_per'] = $row['share_per']?$row['share_per']:30;
                                    } else if($row['service_type'] == 3) {
                                        $mem_level = $service_type = "분양자";
                                        $row['share_per'] = $row['share_per']?$row['share_per']:50;
                                    } else {
                                        $mem_level = $service_type="이용자";
                                    }

                                ?>
                                    <tr>
                                        <td><?=$number--?></td>
                                        <td>
                                            <a href="member_manager_detail.php?mem_code=<?=$row['mem_code']?>&sendnum=<?=$row['sendnum']?>">수정</a> /
                                            <a href="javascript:del_member_info('<?=$row['mem_code']?>&sendnum=<?=$row['sendnum']?>')">탈퇴</a></td>
                                        <td><?=$row['mem_id']?></td>
                                        <td><?=$row['mem_name']?></td>
                                        <td><?=$mem_level;?></td>
                                        <td><?=$row['recommend_id']?></td>
                                        <td><?=$row['rec_name'];?></td>
                                        <td>
                                            <?if(str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""){?>
                                            <input type="text" name="share_per" id="share_per<?=$row['mem_code']?>" value="<?php echo $row['share_per']?>" style="width:40px;">
                                            <input type="text" name="balance_per" id="balance_per<?=$row['mem_code']?>" value="<?php echo $row['balance_per']?>" style="width:40px;">
                                            <select name="service_type" id="service_type<?=$row['mem_code']?>">
                                                <option value="0" <? echo $row['service_type'] == "0"?"selected":""?>>FREE</option>
                                                <option value="1" <? echo $row['service_type'] == "1"?"selected":""?>>이용자</option>
                                                <option value="2" <? echo $row['service_type'] == "2"?"selected":""?>>리셀러</option>
                                                <option value="3" <? echo $row['service_type'] == "3"?"selected":""?>>분양자</option>
                                            </select>
                                            <input type="button" name="변경" value=" 변경 " onclick="changeLevel('<?=$row['mem_id']?>','<?=$row['mem_code']?>')">
                                            <?  }?>
                                        </td>
                                    </tr>
                                    <?$i++;
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
        <!-- Footer -->
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
        <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
            <input type="hidden" name="grp_id" value="" />
            <input type="hidden" name="box_text" value="" />
            <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
        </form>
        <iframe name="excel_iframe" style="display:none;"></iframe>
    </div>

<script language="javascript">
function changeLevel(mem_id, seq) {
    var service_type= $('#service_type'+seq+" option:selected").val();
    var share_per = $('#share_per'+seq).val();
    var balance_per = $('#balance_per'+seq).val();
    var data = {mode:"change",mem_id:mem_id,seq:seq,service_type:service_type,share_per:share_per,balance_per:balance_per};
    $.ajax({
		type:"POST",
		url:"/admin/ajax/user_request_change.php",
		dataType:"json",
		data:data,
		success:function(data){
			alert('변경이 완료되었습니다.');
			location.reload();
		},
		error: function(){
		  alert('초기화 실패');
		}
	});	
}
</script>
