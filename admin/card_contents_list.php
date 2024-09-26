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
    location.href = '?nowPage='+pgNum+"&search_name=<?=$search_name?>&search_id=<?=$search_id?>&search_group=<?=$search_group?>&search_title=<?=$search_title?>&orderField=<?=$orderField?>&dir=<?=$dir?>";
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
      <h1>아이엠 CONTENTS 관리<small>아이엠의 컨텐츠를 관리합니다.</small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">아이엠컨텐츠관리</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row" id="toolbox">
        <div class="col-xs-12" style="padding-bottom:20px">
          <form method="get" name="search_form" id="search_form">
            <div class="box-tools">
              <div class="input-group" style="display:flex;width:350px">
                <!-- <input type="text" name="search_name" id="search_name" class="form-control input-sm pull-right" placeholder="이름" value="<?=$search_name?>"> -->
                <input type="text" name="search_id" id="search_id" class="form-control input-sm pull-right" placeholder="아이디" value="<?=$search_id?>">
                <input type="text" name="search_group" id="search_group" class="form-control input-sm pull-right" placeholder="그룹명" value="<?=$search_group?>">
                <input type="text" name="search_title" id="search_title" class="form-control input-sm pull-right" placeholder="콘텐츠제목" value="<?=$search_title?>">
                <div class="input-group-btn">
                  <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <?$dir = $_REQUEST['dir'] == "asc" ? "desc": "asc";?>
      <div class="row">
        <div class="box">
          <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
                <colgroup>
                  <col width="2%">
                  <col width="2%">
                  <col width="2%">
                  <col width="5%">
                  <col width="5%">
                  <col width="3%">
                  <col width="2%">
                  <col width="7%">
                  <!-- <col width="5%"> -->
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
                    <th>소속/아이디</th>
                    <th>이름</th>
                    <th><a href="?orderField=group_name&dir=<?=$dir?>" class="sort-by">그룹명</a></th>
                    <th>종류</th>
                    <th>이미지</th>
                    <th>컨텐츠<br>제목</th>
                    <!-- <th>컨텐츠<br>링크</th> -->
                    <!-- <th>MY스토리<br>URL</th> -->
                    <th>판매<br>가격</th>
                    <th><a href="?orderField=contents_temp&dir=<?=$dir?>" class="sort-by">조회<br>수</a></th>
                    <th><a href="?orderField=contents_temp1&dir=<?=$dir?>" class="sort-by">노출<br>수</a></th>
                    <th><a href="?orderField=contents_like&dir=<?=$dir?>" class="sort-by">좋아요<br>수</a></th>
                    <th>공유<br>ID</th>
                    <th><a href="?orderField=contents_share_count&dir=<?=$dir?>" class="sort-by">공유<br>건수</a></th>
                    <th>수정<br>일자</th>	
                    <th><a href="?orderField=sample_display&dir=<?=$dir?>" class="sort-by">샘플<br>선택</th>
                    <th><a href="?orderField=sample_order&dir=<?=$dir?>" class="sort-by">샘플<br>순위</th>
                    <th>수정<br>삭제</th>							
                  </tr>
                </thead>
                <tbody>
                <?
                  $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                  $startPage = $nowPage?$nowPage:1;
                  $pageCnt = 20;
                  $count_query = "select count(idx) from Gn_Iam_Contents";
                  $count_result = mysqli_query($self_con, $count_query);
                  $count_row = mysqli_fetch_array($count_result);
                    $totalCnt	=  $count_row[0];
                // 검색 조건을 적용한다.
                  // $searchStr .= $search_name ? " AND (card_name like '%".$search_name."%' )" : null;
                  $searchStr .= $search_id ? " AND (contents.mem_id LIKE '%".$search_id."%' )" : null;
                  if($search_group){
                    $join_str = "left join gn_group_info gi on gi.idx=contents.group_id";
                    $join_sel_str = ",gi.name as group_name";
                    $searchStr .= " AND (gi.name LIKE '%".$search_group."%' )";
                  }
                  else{
                    $join_str = "";
                    $join_sel_str = "";
                  }
                  $searchStr .= $search_group ? " AND (gi.name LIKE '%".$search_group."%' )" : null;
                  $searchStr .= $search_title ? " AND (contents.contents_title like '%".$search_title."%' )" : null;
                  $order = $order?$order:"desc";

                  $query = "SELECT  contents.idx, contents.mem_id,contents.contents_type,contents.contents_img,contents.group_id,".
                      "contents.contents_title,contents.contents_url,contents.card_short_url,contents.westory_card_url,contents.contents_sell_price,".
                      "contents.contents_temp,contents.contents_temp1,contents.contents_like,contents.contents_share_text,contents.contents_share_count,contents.up_data,contents.sample_display,contents.sample_order,contents.card_idx$join_sel_str ".
                      "FROM Gn_Iam_Contents contents $join_str WHERE 1=1 $searchStr";
                  $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                  $number = $totalCnt - ($nowPage - 1) * $pageCnt;                      
                  if(!$orderField){
                    $orderField = "up_data";
                  }
                  $orderQuery .= " ORDER BY $orderField $dir $limitStr";            	
                  $i = 1;
                  $c=0;
                  $query .= $orderQuery;
                  $res = mysqli_query($self_con, $query);
                  while($row = mysqli_fetch_array($res)) {
                    $mem_sql = "select site, site_iam from Gn_Member where mem_id='{$row['mem_id']}'";
                    $mem_res = mysqli_query($self_con, $mem_sql);
                    $mem_row = mysqli_fetch_array($mem_res);
                    if(strpos($row['contents_img'], ",") !== false){
                      $img_link_arr = explode(",", $row['contents_img']);
                      $img_link = trim($img_link_arr[0]);
                    }
                    else{
                      $img_link = $row['contents_img'];
                    }
                    $card_sql = "select card_name from Gn_Iam_Name_Card where idx='$row[card_idx]'";
                    $res_card = mysqli_query($self_con, $card_sql);
                    $row_card = mysqli_fetch_array($res_card);

                    if(!$search_group){
                      $sql_group = "select name as group_name from gn_group_info where idx='{$row['group_id']}'";
                      $res_group = mysqli_query($self_con, $sql_group);
                      $row_group = mysqli_fetch_array($res_group);
                      $g_name = $row_group['group_name'];
                    }
                    else{
                      $g_name = $row['group_name'];
                    }
                  ?>
                      <tr>
                        <td><?=$number--?></td>
                        <td><?=$row['idx']?></td>
                        <td>
                            <div style="overflow-x:hidden;width:100px;">
                              <?=$mem_row[0]?>/<br><?=$mem_row[1]?>/<br><?=$row['mem_id']?>
                            </div>
                        </td>
                        <td><a href="/iam/contents.php?contents_idx=<?=$row['idx']?>" target="_blank"><?=$row_card['card_name']?></a></td>
                        <td><?=$g_name?></td>
                        <td><?=$row['contents_type']?></td>
                        <td>
                          <a href="<?=$row['contents_url']?>" target="_blank">
                            <img class="zoom" src="<?=cross_image($img_link)?>" style="width:50px;"> 
                            </a>
                        </td>
                        <td><?=$row['contents_title']?></td>
                        <!-- <td>
                            <div style="overflow-x:hidden;width:100px;">
                            <a href="<?=$row['contents_url']?>" target="_blank"><?=$row['contents_url']?></a> 
                            </div>
                        </td> -->
                        <!-- <td><a href="/iam/contents.php?contents_idx=<?=$row['idx']?>" target="_blank"> <?=$row['card_short_url']?></a></td> -->
                        <td><?=$row['contents_sell_price']?></td>
                        <td><?=$row['contents_temp']?></td>
                        <td><?=$row['contents_temp1']?></td>
                        <td><?=$row['contents_like']?></td>
                        <td><?=$row['contents_share_text']?></td>
                        <td><?=$row['contents_share_count']?></td>
                        <td><?=$row['up_data']?></td>
                        <td style="font-size:12px;">
                            <label class="switch">
                                <input type="checkbox" class="chkclick" name="cardclick" id="card_click_<?=$row['idx'];?>" <?php echo $row['sample_display']=="Y"?"checked":"";?> >
                                <span class="slider round" name="status_round" id="card_click_<?=$row['idx'];?>"></span>
                            </label>
                        </td>
                        <td><input type = "number" class = "number" value='<?=$row['sample_order']?>' style="width: 50px;text-align: right" data-no="<?=$row['idx']?>"></td>
                        <td><a href="javascript:delContent('<?=$row['idx']?>')">삭제</a></td>
                      </tr>
                  <?
                      $c++;
                      $i++;
                    }
                  if($i == 1) {
                  ?>
                    <tr>
                        <td colspan="18" style="text-align:center;background:#fff">
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
function delContent(idx){
    var msg = confirm('정말로 삭제하시겠습니까?');
    if(msg){

      $.ajax({
          type:"POST",
          url:"/admin/ajax/_db_controller.php",
          data:{
            mode:"delete_contents",
            idx:idx
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
                mode:"update-contents-sample-display",
                sample_display:sample_click,
                cont_idx:card_idx
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
                mode:"update_contents_sample_order",
                sample_order:order,
                cont_idx:idx
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
          