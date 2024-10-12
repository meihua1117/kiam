<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
$(function(){
    $('.switch').on("change", function() {
        var no = $(this).find("input[type=checkbox]").val();
        var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
		$.ajax({
			 type:"POST",
			 url:"/admin/ajax/lecture.proc.php",
			 data:{
				 mode:"lecture_update_status",
				 lecture_id:no,
				 status:status
             },
			 success:function(data){
			    //alert('신청되었습니다.');location.reload();
			 }
        })
    });    
});
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
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
/*user agent stylesheet*/
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
            실시간 리뷰리스트
            <small>리뷰를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">리뷰 페이지</li>
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
                <div class="input-group" style="width: 250px;float:left;">
                    <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="강의내역/강사/지역">
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
                      <col width="4%">
                      <col width="4%">
                      <col width="11%">
                      <col width="8%">
                      <col width="8%">
                      <col width="20%">
                      <col width="8%">
                      <col width="8%">
                      <col width="8%">
                     </colgroup>
                    <thead>
                      <tr>
                        <th style="width:4%;">No</th>
                        <th style="width:4%;">분야</th>
                        <th style="width:11%;">강의내용</th>
                        <th style="width:8%;">강사명</th>
                        <th style="width:8%">지역</th>
                        <th style="width:20%">리뷰내용</th>
                        <th style="width:8%;">작성일시</th>            
                        <th style="width:8%;">작성자</th>
                        <th style="width:8%;">수정/삭제</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                  // 검색 조건을 적용한다.
                  $searchStr .= $search_key ? " AND (a.lecture_info LIKE '".$search_key."%' or a.instructor like '".$search_key."%' or a.area like '".$search_key."%' )" : null;
                	$order = $order?$order:"desc"; 		
                	$query = "SELECT count(a.mem_id) FROM  Gn_review a inner join Gn_lecture b on  a.lecture_id = b.lecture_id WHERE 1=1 $searchStr";
                	              
                	$res	    = mysqli_query($self_con,$query);
                  $totalRow	=  mysqli_fetch_array($res);	                	
                	$totalCnt = $totalRow[0];
                  $query = "SELECT SQL_CALC_FOUND_ROWS a.*, b.* FROM  Gn_review a inner join Gn_lecture b on  a.lecture_id = b.lecture_id  WHERE 1=1 $searchStr";
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                  $orderQuery .= " ORDER BY a.review_id DESC $limitStr ";            	
                	 
                	$i = 1;
                	$c=0;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con,$query);
                  while($row = mysqli_fetch_array($res)) {                       	
						        $sql_num="select * from Gn_event where m_id='{$row['mem_id']}' and event_idx='{$row['event_idx']}' ";
						        $resul_num=mysqli_query($self_con,$sql_num);
						        $crow=mysqli_fetch_array($resul_num); 	 
                  ?>
                      <tr>
                        <td><?=$number--?></td>
                        <td style="font-size:12px;"><?=$row[category]?></td>
                        <td style="font-size:12px;"><?=$row[lecture_info]?></td>
                        <td style="font-size:12px;"><?=$row[instructor]?></td>
                        <td style="font-size:12px;"><?=$row[area]?></td>
                        <td style="font-size:12px;"><?=$row['content']?></td>
                        <td style="font-size:12px;"><?=$row['regdate']?></td>
                        <td style="font-size:12px;"><?=$row['profile'];?></td>
                        <td style="font-size:12px;">
                            <a href="review_write.php?review_id=<?php echo $row['review_id'];?>">수정</a>
                            / 
                            <a href="javascript:;;" onclick="deleteRow('<?php echo $row['review_id']?>');">삭제</a>
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
    <iframe name="excel_iframe" style="display:none;"></iframe>	
<script language="javascript">
function viewEvent(str){
    window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
    
}    
function changeLevel(mem_code) {
    var mem_leb = $('#mem_leb'+mem_code+" option:selected").val();
    var data = {"mode":"change","mem_code":"'+mem_code+'","mem_leb":"'+mem_leb+'"};
    $.ajax({
		type:"POST",
		url:"/admin/ajax/user_level_change.php",
		dataType:"json",
		data:{mode:"change",mem_code:mem_code,mem_leb:mem_leb},
		success:function(data){
		    //console.log(data);
		    //location = "/";
			//location.reload();
			alert('변경이 완료되었습니다.');
		},
		error: function(){
		  alert('초기화 실패');
		}
	});	
    
//    alert(mem_code);
}

function loginGo(mem_id, mem_pw, mem_code) {
    $('#one_id').val(mem_id);
    $('#mem_pass').val(mem_pw);
    $('#mem_code').val(mem_code);
    $('#login_form').submit();
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


function deleteRow(review_id) {
    if(confirm('삭제하시겠습니까?')) {
        location = "ajax/review.proc.php?mode=del&review_id="+review_id;
    }
}
</script>