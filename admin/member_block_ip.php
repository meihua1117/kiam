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
thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
.wrapper{height:100%;overflow:auto !important;}
.content-wrapper{min-height : 80% !important;}
.box-body{overflow:auto;padding:0px !important;}
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
          <h1>
            차단아이피 리스트
            <small>차단아이피를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">차단아이피 관리</li>
          </ol>
        </section>

        <form method="get" name="black_form" id="black_form " class="form-inline">
        <div class="form-group" style="padding: 20px 30px"> 
        <span style="font-size: 20px">차단할 아이피: </span>   
        <input type="text" name="black_ip" id="black_ip" class="form-control input-sm " placeholder="아이피">
        <button class="btn btn-primary " style="margin-left: 1px;" onclick="block_ip();">차단</button>
        </div>
        </form>

        <!-- Main content -->
        <section class="content">
          <div class="row" id="toolbox">
            <div class="col-xs-12" style="padding-bottom:20px">
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 250px;">
                  <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이피">
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
                      <col width="7%">
                      <col width="30%">
                      <col width="30%">
                      <col width="26%">
                      <col width="7%">
                     </colgroup>
                    <thead>
                      <tr>
                        <th>번호</th>
                        <th><a href="?orderField=ip&dir=<?=$dir?>" class="sort-by">차단아이피</a></th>
                        <th><a href="?orderField=type&dir=<?=$dir?>" class="sort-by">차단이유</a></th>
                        <th><a href="?orderField=regdate&dir=<?=$dir?>" class="sort-by">등록날자</a></th>
                        <th>해지</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 15;
                	
                    // 검색 조건을 적용한다.
                    $searchStr .= $search_key ? " AND (ip like '%".$search_key."%' )" : null;
                	
                	$order = $order?$order:"desc"; 		
                	
                	$query = "SELECT SQL_CALC_FOUND_ROWS * FROM gn_block_ip WHERE 1=1 $searchStr";
                	$res	    = mysqli_query($self_con,$query);
                	$totalCnt	=  mysqli_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                  $number			= $totalCnt - ($nowPage - 1) * $pageCnt;  
                  
                  if(!$orderField)
                    $orderField = "regdate";
                  $orderQuery .= " ORDER BY $orderField $dir $limitStr";
                 	
                	$i = 1;
                	$c=0;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con,$query);
                    while($row = mysqli_fetch_array($res)) {                       	
                  ?>
                      <tr >
                        <td><?=$number--?></td>
                        <td><?php echo $row['ip']?></td>
                        <td><?php if($row['type'] == '0') echo '관리자추가' ;
                        else if($row['type'] == '1') echo '로그인시도';
                        else if($row['type'] == '2') echo '공격시도';
                        else if($row['type'] == '3') echo '크롤링시도';
                        else echo '기타';
                        ?></td>
                        <td><?php echo $row['regdate']?></td>
                        <td> <a href="javascript:delete_ip('<?=$row['ip']?>')">해지</a> </td>
                        
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
        <!-- Footer -->
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?> 
      </div><!-- /content-wrapper -->
<script language="javascript">
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 316;
    if(height < 375)
        height = 375;
    $(".box-body").css("height",height);
});
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
            location.href = "/admin/member_block_ip.php";
          },
          error: function(){
            alert('오류 발생');
          }
        });		
    } 
}
function delete_ip(ip){
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
            location.href = "/admin/member_block_ip.php";
          },
          error: function(){
            alert('오류 발생');
          }
        });		
    }
}
//계정 삭제
function block_ip(){
    var ip = $("#black_ip").val();
      if(ip == null)
        return;
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
            location.href = "/admin/member_block_ip.php";
          },
          error: function(){
            alert('오류 발생');
          }
        });
    }
}
</script>
           
<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
    </div>
</div>
