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
    location.href = '?nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&case=<?php echo $_GET['case'];?>";
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
.zoom {
    transition: transform .2s; /* Animation */
}
.zoom:hover {
    transform: scale(4); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    border:1px solid #0087e0;
    box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
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
          <h1>
            오토회원 메시지 관리
            <small>분양사 오토회원 메시지를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">오토회원 메시지관리</li>
          </ol>
        </section>            

        <!-- Main content -->
        <section class="content">
          <div class="row" id="toolbox">
            <div class="col-xs-12" style="padding-bottom:20px">
               <div style="padding:10px">
              </div>
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 150px;">
                  <!-- <input type="text" name="search_recom" id="search_recom" class="form-control input-sm pull-right" style="width:32%;" placeholder="소속">
                  <input type="text" name="search_name" id="search_name" class="form-control input-sm pull-right" style="width:32%;" placeholder="이름"> -->
                  <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" style="width:100%;" placeholder="타이틀/콘텐츠">
                  <div class="input-group-btn">
                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </form>
              </div>            
              </div>            
          </div>
          
          <div class="row">
              <div class="box">
                <div class="box-body">
                  <table id="phone_table" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="70px">
                      <col width="70px">
                      <col width="70px">
                      <col width="70px">
                      <col width="150px">
                      <col width="200px">
                      <col width="100px">
                      <col width="150px">
                      <col width="100px">
                      <col width="120px">
                      <col width="120px">
                     </colgroup>
                    <thead>
                      <tr>
                        <th><input type="checkbox" class="check" id="check_all_member" value="0">&nbsp;No</th>
                        <th>도메인</th>
                        <th>아이디</th>
                        <th>이름</th>
                        <th>타이틀</th>
                        <th>콘텐츠</th>
                        <th>이미지</th>
                        <th>미리보기</th>
                        <th>등록일</th>
                        <th>조회/가입</th>
                        <th><button class="btn btn-primary" onclick="deleteMultiRow();return false;"><i class="fa"></i>삭제</button></th>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                  $searchStr .= $search_key ? " AND (a.event_title LIKE '".$search_key."%' or a.event_desc like '".$search_key."%'  )" : null;
                	$order = $order?$order:"desc";
                	$query = "SELECT count(a.m_id) FROM Gn_event a WHERE a.event_name_kor='단체회원자동가입및아이엠카드생성' $searchStr";
                	$res	    = mysqli_query($self_con,$query);
                  $totalRow	=  mysqli_fetch_array($res);
                	$totalCnt = $totalRow[0];

                  $query = "SELECT a.* FROM Gn_event a WHERE a.event_name_kor='단체회원자동가입및아이엠카드생성' $searchStr";
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                  $orderQuery .= " ORDER BY a.event_idx DESC $limitStr";
                	$i = 1;
                	$c=0;
                	$query .= $orderQuery;
                	$res = mysqli_query($self_con,$query);
                    while($row = mysqli_fetch_array($res)) {
                      $sql_mem = "select site, mem_name from Gn_Member where mem_id='{$row[m_id]}'";
                      $res_mem = mysqli_query($self_con,$sql_mem);
                      $row_mem = mysqli_fetch_array($res_mem);
                      $pop_url = '/event/automember.php?pcode='.$row['pcode'].'&eventidx='.$row['event_idx'];
                      $id_sql = "select count(event_id) as cnt from Gn_Member where event_id={$row['event_idx']} and mem_type='A'";
                      $res_id = mysqli_query($self_con,$id_sql);
                      $row_id = mysqli_fetch_array($res_id);
                      if($row_id['cnt'] != null){
                          $cnt_join = $row_id['cnt'];
                      }
                      else{
                          $cnt_join = 0;
                      }
                  ?>
                      <tr>
                            <td><input type="checkbox" class="check" id="check_one_member" name="" value="<?=$row['event_idx']?>">&nbsp;&nbsp;<?=$number--?></td>
                            <td style="font-size:12px;"><?=$row_mem['site']?></td>
                            <td style="font-size:12px;"><?=$row[m_id]?></td>
                            <td style="font-size:12px;"><?=$row_mem['mem_name']?></td>
                            <td style="font-size:12px;"><?=$row['event_title']?></td>
                            <td style="font-size:12px;"><a href="javascript:show_more('<?=str_replace("\n", "<br>", $row['event_desc'])?>')"><?=cut_str($row['event_desc'], 50)?></a></td>
                            <td style="font-size:12px;"><?if($row['object'] != ""){?><img class="zoom" src="<?=$row['object']?>" style="width:90%;"><?}?></td>
                            <td class="iam_table">
                              <input type="button" value="미리보기" class="button" onclick="viewEvent('<?=$pop_url?>')">
                              <input type="button" value="링크복사" class="button copyLinkBtn" data-link="<?=$row['short_url']?>">
                            </td>
                            <td><?=$row['regdate']?></td>
                            <td><?=$row['read_cnt']?>/<?=$cnt_join?></td>
                            <td><a href="edit_autojoin_msg.php?event_idx=<?=$row['event_idx']?>">수정</a>/<a href="javascript:delete_autojoin(<?=$row['event_idx']?>)">삭제</a></td>
                      </tr>
                    <?
                    $c++;
                    $i++;
                    }
                    if($i == 1) {
                    ?>
                        <tr>
                            <td colspan="10" style="text-align:center;background:#fff">
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
            </div>
          </div><!-- /.row -->
        </section><!-- /.content -->
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      

      </div><!-- /content-wrapper -->
      <!-- Footer -->
<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	<li class="open_recv_title open_2_1"></li>
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
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
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 235;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
$(function() {
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
});
function deleteMultiRow() {
  if(confirm('삭제하시겠습니까?')) {
      var check_array = $("#phone_table").children().find(".check");
      var no_array = [];
      var index = 0;
      check_array.each(function(){
          if($(this).prop("checked") && $(this).val() > 0)
              no_array[index++] = $(this).val();
      });

      $.ajax({
        type:"POST",
        url:"/ajax/edit_event.php",
        dataType:"json",
        data:{del:true, id:no_array.toString()},
        success: function(data){
            console.log(data);
            if(data == 1){
                alert('삭제 되었습니다.');
                window.location.reload();
            }
        }
    })
  }
}
function delete_autojoin(id){
  if(confirm("삭제하시겠습니까?")){
    $.ajax({
        type:"POST",
        url:"/ajax/edit_event.php",
        dataType:"json",
        data:{del:true, id:id},
        success: function(data){
            console.log(data);
            if(data == 1){
                alert('삭제 되었습니다.');
                window.location.reload();
            }
        }
    });
  }else{
      return;
  }
}
function show_more(str){
    $("#contents_detail").html(str);
    $("#show_detail_more").modal("show");
}
</script>