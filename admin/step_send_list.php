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
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&case=<?php echo $_GET['case'];?>";
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
            예약문자 발신내역
            <small>예약문자 발신내역를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">예약문자 발신내역</li>
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
          
          <div class="ad_layer1" style="display:none" style="overflow-y:auto !important;height:150px !important;">
              <table id="phone_table" class="table table-bordered table-striped" style="background:#fff !important">
                 <colgroup>
                  <col width="60px">
                  <col width="100px">
                  <col width="180px">
                </colgroup>
                <thead>
                  <tr>
                    <th>번호</th>
                    <th>기부폰</th>
                    <th>설치일자</th>
                  </tr>
                </thead>
                <tbody id="phone_list">
                    
                </tbody>
                </table>
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
                      <col width="120px">
                      <col width="120px">
                      <col width="120px">
                      <col width="120px">
                      <col width="120px">
                      <col width="120px">
                     </colgroup>
                    <thead>
                      <tr>
                        <td ></td>
                        <td >No</td>
                        <td >아이디</td>
                        <td >이름</td>                        
                        <td >소유자명</td>
                        <td >발신정보</td>
                        <td >수신번호</td>
                        <td >수신자이름</td>
                        <td >세트</td>
                        <td >회차</td>
                        <td >문자제목</td>
                        <td >문자내용</td>
                        <td ><?=$_REQUEST['status2']=='2'?"예약일시":"첨부파일"?></td>
                        <td >이벤트명</td>
                        <td >발송예정일시</td>                                        
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                    // 검색 조건을 적용한다.
                    $searchStr .= $search_key ? " AND (a.send_num LIKE '".$search_key."%' or a.recv_num like '".$search_key."%'  or a.content like '".$search_key."%'  )" : null;

                	
                	$order = $order?$order:"desc"; 		
                	
                	$query = "
                        	SELECT 
                          count(a.mem_id)
                        	FROM Gn_MMS a
                        	WHERE 1=1  and result = 0  and sms_detail_idx is not null
                	              $searchStr";
                	              
                	$res	    = mysqli_query($self_con,$query);
                  //$totalCnt	=  mysqli_num_rows($res);	
                  $totalRow	=  mysqli_fetch_array($res);	                	
                	$totalCnt = $totalRow[0];
                  
                  $query = "
                  SELECT 
                      a.*
                  FROM Gn_MMS a
                  WHERE 1=1  and result = 0  and sms_detail_idx is not null
                        $searchStr";
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                	
                    $orderQuery .= "
                    	ORDER BY a.reg_date DESC
                    	$limitStr
                    ";            	
                	$i = 1;
                	$c=0;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con,$query);
                    while($row = mysqli_fetch_array($res)) { 
                      
                      $query = "
                      SELECT mem_name from Gn_Member where mem_id='{$row['mem_id']}'";
                      $sres = mysqli_query($self_con,$query);
                      $urow = mysqli_fetch_array($sres);    
    														    
                      $sql_n="select memo from Gn_MMS_Number where sendnum='{$row['send_num']}' ";
                      $resul_n=mysqli_query($self_con,$sql_n);
                      $row_n=mysqli_fetch_array($resul_n);
                      mysqli_free_result($resul_n);
                      
                      $recv_cnt=explode(",",$row['recv_num']);
                      
                      
                      $sql_as="select count(idx) as cnt from Gn_MMS_status where idx='{$row['idx']}' ";
                      $resul_as=mysqli_query($self_con,$sql_as);
                      $row_as=mysqli_fetch_array($resul_as);
                      $status_total_cnt = $row_as[0];											
                      
                      $sql_cs="select count(idx) as cnt from Gn_MMS_status where idx='{$row['idx']}' and status='0'";
                      $resul_cs=mysqli_query($self_con,$sql_cs);
                      $row_cs=mysqli_fetch_array($resul_cs);
                      $success_cnt = $row_cs[0];

                    
                      $total_cnt = count($recv_cnt);											
                      
                      $sql_sn="select name, sp from Gn_event_request where request_idx='$row[request_idx]' ";
                      $sresul=mysqli_query($self_con,$sql_sn);
                      $srow=mysqli_fetch_array($sresul);											
                      
                      $sql_sn="select count(*) as cnt from Gn_event_sms_step_info where sms_idx='$row[sms_idx]' ";
                      $sresul=mysqli_query($self_con,$sql_sn);
                      $crow=mysqli_fetch_array($sresul);
                      $total_cnt = $crow['cnt'];
                      
                      $sql_sn="select step from Gn_event_sms_step_info where sms_detail_idx='$row[sms_detail_idx]' ";
                      $sresul=mysqli_query($self_con,$sql_sn);
                      $crow=mysqli_fetch_array($sresul);        				
                  ?>
                      <tr>
                        
											<td><label><input type="checkbox" name="fs_idx" value="<?=$row['idx']?>" /></label></td>
											<td><?=$number--?></td>
                                            <td style="font-size:12px;"><?=$row['mem_id']?></td>
                                            <td style="font-size:12px;"><?=$urow['mem_name']?></td>											
											<td><?=$row_n['memo']?></td>											
	                                        <td><?=$row['send_num']?></td>
											<td style="font-size:12px;"><a href="javascript:void(0)" onclick="show_recv('show_recv_num','<?=$c?>','수신번호')"><?=str_substr($row['recv_num'],0,14,'utf-8')?>
											</td>
											<td><?php echo $srow['name'];?></td>
											<td><?php echo $total_cnt;?></td>
											<td><?php echo $crow[step];?></td>
											<td><a href="javascript:void(0)" onclick="show_recv('show_title','<?=$c?>','문자제목')"><?=str_substr($row['title'],0,14,'utf-8')?></a><input type="hidden" name="show_title" value="<?=$row['title']?>"/></td>
											<td style="font-size:12px;"><a href="javascript:void(0)" onclick="show_recv('show_content','<?=$c?>','문자내용')"><?=str_substr($row['content'],0,30,'utf-8')?></a><input type="hidden" name="show_content" value="<?=$row['content']?>"/></td>
											<?if($_REQUEST['status2']=='2'){?>
		                                    <td style="width:5%;"><?if($row['up_date']!=''&&$row[result]==0){?>완료<?}elseif($row['up_date']==''&&$row[result]==1){?>대기<?}elseif($row[result]==3){?>실패<?}?></td>
		                                    <?}?>
											<td>
											    <?if ($_REQUEST['status2']==2){ echo substr($row['reservation'],0,16); }else{?>
											    <a href="javascript:void(0)" onclick="show_recv('show_jpg','<?=$c?>','첨부파일')"><?=str_substr($row['jpg'],0,20,'utf-8')?></a><input type="hidden" name="show_jpg" value="<?=$row['jpg']?>"/>
											    <a href="javascript:void(0)" onclick="show_recv('show_jpg1','<?=$c?>','첨부파일')"><?=str_substr($row['jpg1'],0,20,'utf-8')?></a><input type="hidden" name="show_jpg1" value="<?=$row['jpg1']?>"/>
											    <a href="javascript:void(0)" onclick="show_recv('show_jpg2','<?=$c?>','첨부파일')"><?=str_substr($row['jpg2'],0,20,'utf-8')?></a><input type="hidden" name="show_jpg2" value="<?=$row['jpg2']?>"/>
											    
											    
											    <?}?>
											    
											</td>
                                            <td><?php echo $srow['sp'];?></td>
											<td style="font-size:12px;"><?=substr($row['reservation'],0,16)?></td>
                      </tr>
                    <?
                    $c++;
                    $i++;
                    }
                    if($i == 1) {
                    ?>
                        <tr>
                            <td colspan="13" style="text-align:center;background:#fff">
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