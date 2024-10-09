<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include_once/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
$(function(){
});

function goPage(pgNum) {
   location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?=$search_key?>&orderField=<?=$orderField?>&dir=<?=$dir?>";
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


.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #ddd!important;
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
.agree{
     /*background: #d5ffd5!important;   */
    }
.disagree{
     background: #ffd5d5!important;   
    }
    th a.sort-by { 
	padding-right: 18px;
	position: relative;
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

.zoom-2x {
  transition: transform .2s; /* Animation */
}

.zoom-2x:hover {
  transform: scale(2); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
  border:1px solid #0087e0;
  box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
}
</style>
    <div class="wrapper">

      <!-- Top 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include_once/admin_header_menu.inc.php";?>      
      
      <!-- Left 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include_once/admin_left_menu.inc.php";?>      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            로그인 이력
            <small>로그인 이력을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">로그인 이력관리</li>
          </ol>
        </section>

        <form method="get" name="black_form" id="black_form " class="form-inline">
        <div class="form-group" style="padding: 20px 30px"> 
        <span style="font-size: 20px">차단 아이피 목록: </span>   
        <select style="width: 160px" id="black_list" name="black_list" class="form-control" >
        <?
             	
               $query = "SELECT ip FROM gn_block_ip";
                             
               $res	    = mysql_query($query);
               while($row = mysql_fetch_array($res)) { 
        ?>                                        
              <option value="<?php echo $row[0]?>"><?php echo $row[0]?></option>
        <?}?>                                
        </select>

        <button class="btn btn-primary " style="margin-left: 5px" onclick="delete_ip();">차단해지</button>
        </div>
        </form>

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12" style="padding-bottom:20px">
            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="delete_all()"><i class="fa fa-download"></i> 전체삭제</button>
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 250px;">
                  <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/아이피">
                  <div class="input-group-btn">
                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </form>
              </div>            
              </div>            
          </div>

          <?$dir = $_REQUEST['dir'] == "desc" ? "asc" : "desc";?>
          <form method="post" action="/admin/ajax/ad_sort_save.php" id="ssForm" name="ssForm">
          <div class="row">
            
              <div class="box">
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="3%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <col width="10%">
                      <col width="3%">
                     </colgroup>
                    <thead>
                      <tr>
                        <th>번호</th>
                        <th>아이디</th>
                        <th>아이피</th>
                        <th><a href="?orderField=success&dir=<?=$dir?>" class="sort-by">로그인 성공/실패</a></th>
                        <th><a href="?orderField=count&dir=<?=$dir?>" class="sort-by">로그인 시도 횟수</a></th>
                        <th>로그인 페이지</th>
                        <th>도메인</th>
                        <th>등록날자</th>
                        <th>차단</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 15;
                	
                    // 검색 조건을 적용한다.
                    $searchStr .= $search_key ? " AND (userid LIKE '%".$search_key."%' or ip like '%".$search_key."%' )" : null;
                	
                	$order = $order?$order:"desc"; 		
                	
                	$query = "
                        	SELECT 
                        	    SQL_CALC_FOUND_ROWS 
                        	    *
                        	FROM gn_hist_login 
                        	WHERE 1=1 
                	              $searchStr";
                	              
                	$res	    = mysql_query($query);
                	$totalCnt	=  mysql_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                  $number			= $totalCnt - ($nowPage - 1) * $pageCnt;  
                  
                  if(!$orderField)
                    $orderField = "regdate";
                  $orderQuery .= " ORDER BY $orderField $dir $limitStr";
                 	
                	$i = 1;
                	$c=0;
                	$query .= "$orderQuery";
                	$res = mysql_query($query);
                    while($row = mysql_fetch_array($res)) {                       	
                  ?>
                      <tr <?if($row['success'] == 'N' && $row['count'] >= 5){ ?> style="background-color: #00a65a;"<?}?>>
                        <td><?=$number--?></td>
                        <td><?php echo $row['userid']?></td>
                        <td><?php echo $row['ip']?></td>
                        <td><?php if($row['success'] == 'Y') {?>성공<?}else {?><b>실패</b><?}?></td>
                        <td><?php echo $row['count']?></td>
                        <td><?php echo $row['position']?></td>
                        <td><?php echo $row['domain']?></td>
                        <td><?php echo $row['regdate']?></td>
                        <td> <a href="javascript:block_ip('<?=$row['ip']?>')">차단</a> </td>
    
                        <!--<td> <a href="javascript:del_member_info('<?=$row['idx']?>')">삭제</a> </td>-->
                      </tr>
                    <?
                    $c++;
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
                    	echo drawPagingAdminNavi($totalCnt, $nowPage, $pageCnt);
                    ?>
                </div>
            </div>
          </div><!-- /.row -->         
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->



<script language="javascript">

function delete_all(){

    var msg = confirm('전체 삭제하시겠습니까?');

    if(msg){

        $.ajax({
          type:"POST",
          url:"/admin/ajax/block_ip_ajax.php",
          data:{
          mode:"del"
          },
          success:function(data){
            alert(data);
            location.href = "/admin/member_login_history.php";
          },
          error: function(){
            alert('오류 발생');
          }


        });		

    } 
}

function delete_ip(){

    var ip = $("#black_list").val();
    if(ip == null)
      return;
    var msg = confirm('차단된 아이피 ' + ip + '를 해지시겠습니까?');

    if(msg){

        $.ajax({
          type:"POST",
          url:"/admin/ajax/block_ip_ajax.php",
          data:{ip:ip, 
          mode:"minus"
          },
          success:function(data){
            alert(data);
            location.href = "/admin/member_login_history.php";
          },
          error: function(){
            alert('오류 발생');
          }


        });		

    }
}

//계정 삭제
function block_ip(ip){

    var msg = confirm('아이피 ' + ip + '를 정말로 차단하시겠습니까?');

    if(msg){

        $.ajax({
          type:"POST",
          url:"/admin/ajax/block_ip_ajax.php",
          data:{ip:ip, 
          mode:"plus"
          },
          success:function(data){
            alert(data);
            location.href = "/admin/member_login_history.php";
          },
          error: function(){
            alert('오류 발생');
          }


        });		

    }
}
</script>
      <!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include_once/admin_footer.inc.php";?>      
<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
    </div>
</div>
