<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
extract($_POST);
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
//language 삭제
function del_language(idx){
	var msg = confirm('정말로 삭제하시겠습니까?');
	if(msg){
			$.ajax({
				type:"POST",
				url:"/admin/ajax/Iam_lang_save.php",
				data:{mode:"del",no:idx},
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
function saveSortOrder() {
			$.ajax({
				type:"POST",
				url:"/admin/ajax/Iam_lang_save.php",
				data:$('#action_form').serialize(),
				success:function(){
					alert('수정되었습니다.');
					//location.reload();
				},
				error: function(){
				  alert('삭제 실패');
				}
			});		    
}
function goPage(pgNum) {
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>";
}
function loginGo(sub_domain,mem_id, mem_pw, mem_code) {
    $('#one_id').val(mem_id);
    $('#mem_pass').val(mem_pw);
    $('#mem_code').val(mem_code);
    $('#sub_domain').val(sub_domain);
    $('#login_form').submit();
}
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 246;
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
#open_recv_div li{list-style: none;}
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #ddd!important;
}
#open_recv_div li{list-style: none;}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important}
</style>
<div class="loading_div" >
    <img src="/images/ajax-loader.gif">
</div>

<div class="wrapper">
  <!-- Top 메뉴 -->
  <?include_once "include/admin_header_menu.inc.php";?>
  <!-- Left 메뉴 -->
  <?include_once "include/admin_left_menu.inc.php";?>
      <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>다국어IAM관리<small>아이엠 다국어정보를 관리합니다.</small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">아이엠 다국어관리</li>
      </ol>
    </section>
        
    <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
    <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />
    <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />
    <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />
    <input type="hidden" name="sub_domain" id="sub_domain" value="<?=$data['sub_domain']?>" />
    </form>

    <!-- Main content -->
    <section class="content">
      <div class="row" id="toolbox">
        <div class="col-xs-12" style="padding-bottom:20px">
          <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="saveSortOrder();return false;"><i class="fa fa-download"></i> 노출순서 저장</button>
          <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='service_lang_detail.php';return false;"><i class="fa fa-download"></i> 등록</button>
        </div>
      </div>
     <form name="action_form" id="action_form"  method="post" >
            <div class="row">
                <div class="col-xs-12" style="padding-bottom:20px">
                        <input type="hidden" name="case" value="<? echo $_GET['case'];?>">
                        <div class="box-tools">
                            <div class="input-group" style="display:inline-flex;width: 250px;">
                                <input type="text" name="search_key" id="search_key" style="width:115px" class="form-control input-sm pull-right" placeholder="위치/한국어">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                </div>
            </div>        
        <input type="hidden" name="mode"   id="mode"   value="sort_order" />
      <div class="row">
        <div class="box">
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                 <colgroup>
                      <col width="30px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                      <col width="60px">
                 </colgroup>
                <thead>
                  <tr>
                    <th>번호</th>
                    <th>수정/삭제</th>
                    <th>메뉴</th>
                    <th>위치</th>
                    <th>한국어</th>
                    <th>영어</th>
                    <th>중국어</th>
                    <th>일본어</th>
                    <th>힌디어</th>
                    <th>프랑스어</th>
                    <th>노출순서</th>
                  </tr>
                </thead>
                <tbody>
                  <?
                    $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                    $startPage = $nowPage?$nowPage:1;
                    $pageCnt = 20;
                    // 검색 조건을 적용한다.
                    
                    $searchStr .= $search_key ? " AND (menu like '%".$search_key."%' or pos like '%".$search_key."%' or kr like '%".$search_key."%') " : null;
                    $query = "
                            SELECT
                                SQL_CALC_FOUND_ROWS
                                *
                            FROM Gn_Iam_lang where 1=1 $searchStr";
                    $res	    = mysqli_query($self_con, $query);
                    $totalCnt	=  mysqli_num_rows($res);
                    $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                    $number			= $totalCnt - ($nowPage - 1) * $pageCnt;
                    $orderQuery .= " ORDER BY menu asc, sort_order ".$limitStr;
                    $i = 1;
                    $query .= "$orderQuery";
                    $res = mysqli_query($self_con, $query);
                    while($row = mysqli_fetch_array($res)) {
                  ?>
                  <tr>
                    <td><?=$number--?></td>
                    <td>
                        <a href="service_lang_detail.php?no=<?=$row['no']?>">수정</a> /
                        <a href="javascript:del_language('<?=$row['no']?>')">삭제</a>
                    </td>
                    <td><?=$row['menu']?></td>
                    <td><?=$row['pos']?></td>
                    <td><?=$row['kr']?></td>
                    <td><?=$row['en']?></td>
                    <td><?=$row['cn']?></td>
                    <td><?=$row['jp']?></td>
                    <td><?=$row['id']?></td>
                    <td><?=$row['fr']?></td>
                    <td>
                        <input type="hidden" name="no[]" value="<?php echo $row['no'];?>">
                        <input type="text" name="sort_order[]" value="<?=$row['sort_order']?>"></td>
                  </tr>
                <?
                $i++;
                }
                if($i == 1) {
                ?>
                    <tr>
                        <td colspan="11" style="text-align:center;background:#fff">
                            등록된 내용이 없습니다.
                        </td>
                    </tr>
                <?
                }
                ?>
                </tbody>
              </table>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div><!-- /.col -->
    </form>
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
  </div><!-- /.row -->
  <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
</div><!-- /content-wrapper -->
<iframe name="excel_iframe" style="display:none;"></iframe>