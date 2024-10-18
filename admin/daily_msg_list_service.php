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
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 270;
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
.iam_table{
    border: 1px solid black;
    border-collapse: collapse;
    padding:3px;
    text-align: center;
    cursor:pointer;
}
.zoom {
    transition: transform .2s; /* Animation */
}

.zoom:hover {
    transform: scale(4); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    border:1px solid #0087e0;
    box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
}
@media (min-width: 1200px){
    .container {
        width: 800px;
    }
}
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important}
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
            분양사 데일리 메시지 관리
            <small>분양사 데일리 메시지를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">분양사 데일리 메시지관리</li>
          </ol>
        </section>            

        <!-- Main content -->
        <section class="content">
          <div class="row" id="toolbox">
            <div class="col-xs-12" style="padding-bottom:20px">
               <div style="padding:10px">
              </div>
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='daily_msg_list_mem.php';return false;"><i class="fa fa-download"></i> 회원 데일리 메시지 관리</button>
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 350px;">
                  <input type="text" name="search_recom" id="search_recom" class="form-control input-sm pull-right" style="width:32%;" placeholder="소속">
                  <input type="text" name="search_name" id="search_name" class="form-control input-sm pull-right" style="width:32%;" placeholder="이름">
                  <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" style="width:32%;" placeholder="타이틀/콘텐츠">
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
                      <col width="150px">
                      <col width="150px">
                      <col width="100px">
                      <col width="100px">
                      <col width="100px">
                      <col width="120px">
                     </colgroup>
                    <thead>
                      <tr>
                        <td ><input type="checkbox" class="check" id="check_all_member" value="0">&nbsp;No</td>
                        <td >도메인</td>
                        <td >아이디</td>
                        <td >이름</td>
                        <td >타이틀</td>
                        <td >콘텐츠</td>
                        <td >이미지</td>
                        <td >미리보기</td>
                        <td >조회수/신청수</td>
                        <td >등록일</td>
                        <td ><button class="btn btn-primary" onclick="deleteMultiRow();return false;"><i class="fa"></i>삭제</button></td>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                    // 검색 조건을 적용한다.
                    $searchStr .= $search_key ? " AND (a.event_title LIKE '".$search_key."%' or a.event_desc like '".$search_key."%'  )" : null;
                    $searchStr .= $search_name ? " AND (b.mem_name LIKE '".$search_name."%')" : null;
                    $searchStr .= $search_recom ? " AND (b.site LIKE '".$search_recom."%')" : null;

                	
                	$order = $order?$order:"desc";
                	
                	$query = "
                        	SELECT 
                          count(a.m_id)
                        	FROM Gn_event a inner join Gn_Member b on a.m_id=b.mem_id
                        	WHERE a.event_name_kor='데일리문자세트자동생성' 
                	              $searchStr";
                	              
                	$res	    = mysqli_query($self_con,$query);
                  //$totalCnt	=  mysqli_num_rows($res);	
                  $totalRow	=  mysqli_fetch_array($res);
                	$totalCnt = $totalRow[0];
                  

                  $query = "
                  SELECT 
                    a.*, b.mem_name, b.site
                  FROM Gn_event a inner join Gn_Member b on a.m_id=b.mem_id
                  WHERE a.event_name_kor='데일리문자세트자동생성' 
                        $searchStr";
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                	
                    $orderQuery .= "
                    	ORDER BY a.event_idx DESC
                    	$limitStr
                    ";           	
                	
                	$i = 1;
                	$c=0;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con,$query);
                  while($row = mysqli_fetch_array($res)) {
                    $sql_req_mem = "select count(*) as cnt from Gn_daily where event_idx={$row['event_idx']}";
                    $res_req_mem = mysqli_query($self_con,$sql_req_mem);
                    $row_req_mem = mysqli_fetch_array($res_req_mem);
                  ?>
                      <tr>
                            <td><input type="checkbox" class="check" id="check_one_member" name="" value="<?=$row['event_idx']?>">&nbsp;&nbsp;<?=$number--?></td>
                            <td style="font-size:12px;"><?=$row['site']?></td>
                            <td style="font-size:12px;"><?=$row[m_id]?></td>
                            <td style="font-size:12px;"><?=$row['mem_name']?></td>
                            <td style="font-size:12px;"><?=$row['event_title']?></td>
                            <td style="font-size:12px;"><a href="javascript:show_more('<?=str_replace("\n", "<br>", $row['event_desc'])?>')"><?=cut_str($row['event_desc'], 50)?></a></td>
                            <td style="font-size:12px;"><?if($row['object'] != ""){?><img class="zoom" src="http://www.kiam.kr/<?=$row['object']?>" style="width:90%;"><?}?></td>
                            <td style="font-size:12px;">
                                <input type="button" value="미리보기" class="button" onclick="viewEvent('<?php echo $row['short_url']?>')">
                                <input type="button" value="링크복사" class="button copyLinkBtn" data-link="<?php echo $row['short_url']?>">
                            </td>
                            <td style="font-size:12px;"><a href="javascript:show_req_mem('<?=$row['event_idx']?>')"><?=$row['read_cnt']?>/<?=$row_req_mem['cnt']?></a></td>
                            <td style="font-size:12px;"><?=$row['regdate']?></td>
                            <td><a href="edit_daily_msg_service.php?event_idx=<?=$row['event_idx']?>">수정</a>/<a href="javascript:delete_event(<?=$row['event_idx']?>)">삭제</a></td>
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
        <!-- Footer -->
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>  
      </div><!-- /content-wrapper -->

      <!-- ./데일리 신청회원리스트 모달 -->
    <div id="daily_reqmem_list_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background-color:rgb(130,199,54); color:white;padding:5px;">
                    <h2 style="text-align: center;">디비 데일리 신청자</h2>
                    <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin: -33px 10px;cursor:pointer;">X</a>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;text-align: center;">
                        <table style="width:100%">
                            <thead>
                                <th class="iam_table">번호</th>
                                <th class="iam_table">이름</th>
                                <th class="iam_table">아이디</th>
                                <th class="iam_table">상세보기/삭제</th>
                            </thead>
                            <tbody id="daily_reqmem_table">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="daily_reqmem_detail_list_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background-color:rgb(130,199,54); color:white;">
                    <!-- <h2 style="text-align: center;">디비 데일리 신청자</h2> -->
                    <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -10px;cursor:pointer;">X</a>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;text-align: center;">
                        <table style="width:100%">
                            <thead>
                                <th class="iam_table">발송폰번호</th>
                                <th class="iam_table">주소건수</th>
                                <th class="iam_table">발송일수</th>
                                <th class="iam_table">일발송량</th>
                                <th class="iam_table">발송시작일</th>
                                <th class="iam_table">발송마감일</th>
                                <th class="iam_table">등록일</th>
                            </thead>
                            <tbody id="daily_reqmem_detail_table">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />        
        <input type="hidden" name="one_member_id" id="one_member_id" value="" />        
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />                
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>	

<script language="javascript">
function viewEvent(str){
    window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
}
</script>
          
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
    $('.switch').on("change", function() {
        var no = $(this).find("input[type=checkbox]").val();
        var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
		$.ajax({
			 type:"POST",
			 url:"/mypage.proc.php",
			 data:{
				 mode:"land_updat_status",
				 landing_idx:no,
				 status:status,
				 },
			 success:function(data){
			    //alert('신청되었습니다.');location.reload();
			 }
			})    
			        
        //console.log($(this).find("input[type=checkbox]").is(":checked"));
        //console.log($(this).find("input[type=checkbox]").val());
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
function newpop(str){
    window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}
function copyHtml(url){
    //oViewLink = $( "ViewLink" ).innerHTML;
    ////alert ( oViewLink.value );
    //window.clipboardData.setData("Text", oViewLink);
    //alert ( "주소가 복사되었습니다. \'Ctrl+V\'를 눌러 붙여넣기 해주세요." );
    var IE=(document.all)?true:false;
    if (IE) {
        if(confirm("이 소스코드를 복사하시겠습니까?")) {
            window.clipboardData.setData("Text", url);
        } 
    } else {
            temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", url);
    }

}
function deleteMultiRow() {
    if(confirm('삭제하시겠습니까?')) {
        var check_array = $("#phone_table").children().find(".check");
        var no_array = [];
        var index = 0;
        check_array.each(function(){
            if($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });
        location.href = "delete_daily_msg.php?gd_id="+no_array.toString();
        $.ajax({
            type:"POST",
            url:"/ajax/edit_event_daily.php",
            dataType:"json",
            data:{
                del:true,
                id:no_array.toString()
            },
            success:function(data){
                alert("삭제 되었습니다");
                location.reload();
            }
        })
    }
}
function show_req_mem(idx){
    $("#daily_reqmem_list_modal").modal("show");
    $.ajax({
        type:"POST",
        url:"/ajax/edit_event_daily.php",
        dataType:"html",
        data:{
            show_req_mem_admin:true,
            event_idx:idx
        },
        success:function(data){
            // console.log(data);
            $("#daily_reqmem_table").html(data);
        }
    })
    // $("#daily_list_modal").modal("hide");
}
function show_detail_daily(gd_id, event_idx, mem_id){
  $("#daily_reqmem_detail_list_modal").modal("show");
    $.ajax({
        type:"POST",
        url:"/ajax/edit_event_daily.php",
        dataType:"html",
        data:{
            show_req_mem_detail:true,
            event_idx:event_idx,
            gd_id:gd_id,
            mem_id:mem_id
        },
        success:function(data){
            // console.log(data);
            $("#daily_reqmem_detail_table").html(data);
        }
    })
}
function del_req_data(event_idx, mem_id){
  if(confirm('삭제하시겠습니까?')) {
    $("#daily_reqmem_list_modal").modal("show");
    $.ajax({
        type:"POST",
        url:"/ajax/edit_event_daily.php",
        dataType:"json",
        data:{
            del_req_mem:true,
            event_idx:event_idx,
            mem_id:mem_id
        },
        success:function(data){
            // console.log(data);
            alert('삭제되었습니다.');
            location.reload();
        }
    })
  }
}
function delete_event(idx){
  if(confirm('삭제하시겠습니까?')) {
    $.ajax({
        type:"POST",
        url:"/ajax/edit_event_daily.php",
        dataType:"json",
        data:{
            del:true,
            id:idx
        },
        success:function(data){
            alert("삭제 되었습니다");
            location.reload();
        }
    })
  }
}
function show_more(str){
    $("#contents_detail").html(str);
    $("#show_detail_more").modal("show");
}
</script>