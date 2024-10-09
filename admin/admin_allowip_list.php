<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
var mem_id="";
$(function() {
    var contHeaderH = $(".main-header").height();
    var navH = $(".navbar").height();
    if(navH != contHeaderH)
        contHeaderH += navH - 50;
    $(".content-wrapper").css("margin-top",contHeaderH);
    var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() - 51 - 45 - 164;
    if(height < 375)
        height = 375;
    $(".board_write_form").css("height",height);
});
function page_view(mem_id) {
  $('.ad_layer1').lightbox_me({
    centered: true,
    onLoad: function() {
      $.ajax({
        type:"POST",
        url:"/admin/ajax/member_list_page1.php",
        data:{mem_id:mem_id},
        dataType: 'html',
        success:function(data){
          $('#phone_list').html(data);
        },
        error: function(){
          alert('로딩 실패');
        }
      });			    
    }
  });
  $('.ad_layer1').css({"overflow-y":"auto", "height":"300px"});
}
//폰정보 수정
function modify_phone_info(){
  var phno = $("#pno").val();
  if(!phno){
		alert('폰 정보가 없습니다.');
		return false;
	}else{
		$.ajax({
			type:"POST",
			url:"ajax/modify_phoneinfo.php",
			data:{
				pno:phno,
				name:$("#detail_name").val(),
				company:$("#detail_company").val(),
				rate:$("#detail_rate").val()
			},
			success:function(data){
				location.reload();
			},
			error: function(){
			  alert('수정 실패');
			}
		});		
	}
}

//주소록 다운
function excel_down_(){
	$("#excel_down_form").submit();
	return false;
}
function goPage(pgNum) {
  location.href = '?<?=$nowPage?>&nowPage='+pgNum;
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
            허용아이피 관리
            <small>허용아이피를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">허용아이피 관리</li>
          </ol>
        </section>
        <section>
        <?
          $query = "SELECT secure_connect FROM gn_conf";
          $res	    = mysql_query($query);
          $row = mysql_fetch_array($res);
        ?>
        <div style="padding: 40px 60px"> 
        <span style="font-size: 20px">보안접속 사용: </span>
        <input style="margin-left: 40px" type="radio"  id="use_agree" name="use_sec" class="use_sec" value="Y" <?=$row['secure_connect']=='Y'?'checked':''?>> 사용 &nbsp;&nbsp;
        <input type="radio" id="use_deny" name="use_sec" class="use_sec" value="N" <?=$row['secure_connect']=='N'?'checked':''?>> 사용안함

        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row" id="toolbox">
            <div class="col-xs-12" style="padding-bottom:20px">
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='admin_allowip_detail.php';return false;"><i class="fa fa-download"></i> 등록</button>
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
                      <col width="3%">
                      <col width="3%">
                     </colgroup>
                    <thead>
                      <tr>
                        <th>번호</th>
                        <th>관리자 아이디</th>
                        <th>사용자 이름</th>
                        <th>허용아이피</th>
                        <th>등록일</th>
                        <th>수정</th>
                        <th>삭제</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                  // 검색 조건을 적용한다.
                  $searchStr .= $search_key ? " AND (mem_id LIKE '%".$search_key."%' or ip like '%".$search_key."%' )" : null;
                	$order = $order?$order:"desc"; 		
                	$query = "SELECT SQL_CALC_FOUND_ROWS * FROM gn_admin_allowip WHERE 1=1 $searchStr";
                	$res	    = mysql_query($query);
                	$totalCnt	=  mysql_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                  $orderQuery .= " ORDER BY idx DESC $limitStr";            	
                	$i = 1;
                	$c=0;
                	$query .= "$orderQuery";
                	$res = mysql_query($query);
                    while($row = mysql_fetch_array($res)) {                       	
                  ?>
                      <tr>
                        <td><?=$number--?></td>
                        <td><?php echo $row['mem_id']?></td>
                        <td><?php echo $row['name']?></td>
                        <td><?php echo $row['ip']?></td>
                        <td><?php echo $row['regdate']?></td>
                        <td> <a href="admin_allowip_detail.php?idx=<?=$row['idx']?>">수정</a> </td>
                        <td> <a href="javascript:del_member_info('<?=$row['idx']?>')">삭제</a> </td>                        
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
                  <input type="hidden" id="totalcnt"  name="totalcnt" value="<?=$totalCnt?>">
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
          </div><!-- /.row -->
        </section><!-- /.content -->
        <!-- Footer -->
        <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>   
      </div><!-- /content-wrapper -->
<script language="javascript">
  $('.use_sec').click(function(e) {
       var status = $(this).val();
        var msg;
       var type = 0;
       if(status == 'Y'){
            // if($('#totalcnt').val() == '0')
            // {
            //     alert("아이피를 등록해야 이용할수 있습니다.");
            //     e.preventDefault();
            //     return false;
            // }
            msg = confirm('보안접쇽을  사용하시겠습니까?');
            type = 1;
        }else{
            msg = confirm('보안접쇽을 해지하시겠습니까?');
        }
        if(msg)
        {
          $.ajax({
              type:"POST",
              url:"/admin/ajax/admin_allowip_save.php",
              data:{
                  mode:"type",
                  use_secure: status
              },
              success:function(data){
                  location.reload();
              },
              error: function(){
                  alert('설정 실패');
              }
          });
        }
        else
        {          
          e.preventDefault();
        }
    });
//계정 삭제
function del_member_info(idx){
  var msg = confirm('정말로 삭제하시겠습니까?');
  if(msg){
    $.ajax({
      type:"POST",
      url:"/admin/ajax/admin_allowip_delete.php",
      data:{idx:idx},
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
</script>
<div id='open_recv_div' class="open_1">
	<div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
    	
    	<li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>         
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">
		
    </div>
</div>
