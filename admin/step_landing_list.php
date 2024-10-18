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
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&case=<?php echo $_GET['case'];?>";
}

//주소록 다운
function excel_down_p_group(pno,one_member_id){
	$($(".loading_div")[0]).show();
	$($(".loading_div")[0]).css('z-index',10000);
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yy = today.getFullYear().toString().substr(2,2);
	if(dd<10) {
		dd='0'+dd
	} 
	if(mm<10) {
		mm='0'+mm
	} 

	$.ajax({
	 type:"POST",
	 dataType : 'json',
	 url:"/ajax/ajax_session_admin.php",
	 data:{
			group_create_ok:"ok",
			group_create_ok_nums:pno,
			group_create_ok_name:pno.substr(3,8)+'_'+''+ mm+''+dd,
			one_member_id:one_member_id
		  },
	 success:function(data){
	 	$($(".loading_div")[0]).hide();
	 	$('#one_member_id').val(one_member_id);
	 	parent.excel_down('/excel_down/excel_down_.php?down_type=1&one_member_id='+one_member_id,data.idx);
	 }

	});	
}
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 266;
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
            이벤트랜딩페이지
            <small>이벤트랜딩페이지를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">이벤트랜딩페이지</li>
          </ol>
        </section>
        
        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
        <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />        
        <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />        
        <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />        
        </form>                

        <!-- Main content -->
        <section class="content">
          <div class="row" id="toolbox">
            <div class="col-xs-12" style="padding-bottom:20px">
               <div style="padding:10px">
              </div>
              <?php if($_SESSION['one_member_admin_id'] != "onlyonemaket"){?>
              <?php }?>
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 250px;">
                  <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="이벤트영문명/이벤트한글명/신청경로">
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
                  <table id="example1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="60px">
                      <col width="100px">
                      <col width="100px">
                      <col width="100px">
                      <col width="200px">
                      <col width="100px">
                      <col width="220px">
                      <col width="120px">
                      <col width="120px">
                      <col width="120px">
                     </colgroup>
                    <thead>
                      <tr>
                        <td ></td>
                        <td >No</td>
                        <td>아이디</td>
                        <td>이름</td>
                        <td >제목</td>
                        <td >랜딩페이지설명</td>
                        <td >미리보기</td>
                        <td >댓글수</td>
                        <td >조회수</a></td>
                        <td >작성일</td>            
                        <td >노출/중지</td>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                    // 검색 조건을 적용한다.
                    $searchStr .= $search_key ? " AND (a.title LIKE '".$search_key."%' or a.description like '".$search_key."%'  )" : null;

                	
                	$order = $order?$order:"desc"; 		
                	
                	// $query = "
                  //       	SELECT 
                  //         count(b.mem_id)
                  //       	FROM Gn_landing a
                  //       	left join Gn_Member b
                  //       	on a.m_id = b.mem_id
                  //       	WHERE 1=1 
                  //               $searchStr";
                                
                  $query = "
                  SELECT 
                  count(a.m_id)
                  FROM Gn_landing a
                  WHERE 1=1 
                        $searchStr";             
                	              
                	$res	    = mysqli_query($self_con,$query);
                  //$totalCnt	=  mysqli_num_rows($res);	
                  $totalRow	=  mysqli_fetch_array($res);	                	
                	$totalCnt = $totalRow[0];
                  

                  $query = "
                  SELECT 
                  a.m_id, a.title, a.description, a.read_cnt, a.status_yn,
                  a.short_url, a.cnt, a.regdate, a.landing_idx
                  FROM Gn_landing a
                  WHERE 1=1 
                        $searchStr";
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                	
                    $orderQuery .= "
                    	ORDER BY a.landing_idx DESC
                    	$limitStr
                    ";            	
                	
                	$i = 1;
                	$c=0;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con,$query);
                    while($row = mysqli_fetch_array($res)) {                       	
                      $query = "
                      SELECT mem_name from Gn_Member where mem_id='$row[m_id]'";
                      $sres = mysqli_query($self_con,$query);
                      $srow = mysqli_fetch_array($sres);         				
                  ?>
                      <tr>
                        
                            <td><input type="checkbox" name=""></td>
                            <td><?=$number--?></td>
                            <td style="font-size:12px;"><?=$row[m_id]?></td>
                            <td style="font-size:12px;"><?=$srow['mem_name']?></td>
                            <td style="font-size:12px;"><?=$row['title']?></td>
                            <td style="font-size:12px;"><?=$row['description']?></td>
                            <td style="font-size:12px;">
                                <input type="button" value="미리보기" class="button" onclick="viewEvent('<?php echo $row['short_url']?>')">
                                <input type="button" value="링크복사" class="button copyLinkBtn" data-link="<?php echo $row['short_url']?>">
                            </td>
                            <td><?=number_format($row['cnt'])?></td>
                            <td><?=number_format($row['read_cnt'])?></td>
                            <td><?=$row['regdate']?></td>

                            <td>
                                <label class="switch">
                                  <input type="checkbox" name="status" id="stauts_<?php echo $row['landing_idx'];?>" value="<?php echo $row['landing_idx'];?>" <?php echo $row['status_yn']=="Y"?"checked":""?> >
                                  <span class="slider round" name="status_round" id="stauts_round_<?php echo $row['landing_idx'];?>"></span>
                                </label>                    
                            </td>
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
<script>
$(function() {
    $('.copyLinkBtn').bind("click", function() {
        var trb = $(this).data("link");
        var IE=(document.all)?true:false;
        if (IE) {
            if(confirm("이 링크를 복사하시겠습니까?")) {
                window.clipboardData.setData("Text", trb);
            } 
        } else {
                temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", trb);
        }        
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
</script>