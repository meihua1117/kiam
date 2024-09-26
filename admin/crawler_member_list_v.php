<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_v.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
var mem_id="";
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
$(function(){
});
 
//계정 삭제
function del_member_info(mem_code){

	var msg = confirm('정말로 삭제하시겠습니까?');

	if(msg){

			$.ajax({
				type:"POST",
				url:"/admin/ajax/crawler_user_leave.php",
				data:{cmid:mem_code},
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
    location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&type=<?php echo $type;?>";
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
</style>
    <div class="loading_div" ><img src="/images/ajax-loader.gif"></div>
    <div class="wrapper">

      <!-- Top 메뉴 -->
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>      
      
      <!-- Left 메뉴 -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            디버회원관리
            <small>디버회원관리을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">디버회원관리</li>
          </ol>
        </section>
        
        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
        <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />        
        <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />        
        <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />        
        </form>                

        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12" style="padding-bottom:20px">
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='?type=2'"> 정규회원</button>
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='?type=1'"> 체험회원</button>
                              
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 250px;">
                  <input type="hidden" name="type" value="<?php echo $type;?>">
                  <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/이름">
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
                      <col width="70px">
                      <col width="70px">
                      <col width="50px">
                      <col width="220px">
                      <col width="40px">
                      <col width="120px">
                      <col width="120px">
                     </colgroup>
                    <thead>
                      <tr>
                        <th>번호</th>
                        <th>회원아이디</th>
                        <th>이름</th>
                        <th>승인건수</th>
                        <th>사용여부</th>
                        <th>잔량건수</th>
                        <th>종료일시</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                    // 검색 조건을 적용한다.
                    $searchStr .= $search_key ? " AND (user_id LIKE '%".$search_key."%' or user_name like '%".$search_key."%' )" : null;
                	
                	$order = $order?$order:"desc"; 		
                	if($type == 1)
                	    $searchStr .= " and use_cnt = '300' ";
                	else if($type == 2)
                	    $searchStr .= " and use_cnt > '300' ";
                	                	
                	$query = "
                        	SELECT 
                        	    SQL_CALC_FOUND_ROWS 
                        	    *
                        	FROM crawler_member
                        	WHERE 1=1 
                	              $searchStr";
                	              
                	$res	    = mysqli_query($self_con, $query);
                	$totalCnt	=  mysqli_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                    $orderQuery .= "
                    	ORDER BY cmid DESC
                    	$limitStr
                    ";            	
                	
                	$i = 1;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con, $query);
                    while($row = mysqli_fetch_array($res)) {                       	
                       
                  ?>
                      <tr>
                        <td><?=$number--?></td>
                        <td>
                            <?=$row['user_id']?>
                        </td>
                        <td><?=$row['user_name']?></td>
                        <td><?=$row['use_cnt']?></td>

                            <td><?=$row['status']?></td>
                        <td>
                            <?=$row['use_cnt']-$row['monthly_cnt']?></td>
                            <!--기부폰-->
                        <td><?=$row['term']?></td>
 
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
      </div><!-- /content-wrapper -->

    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />        
        <input type="hidden" name="one_member_id" id="one_member_id" value="" />        
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />                
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>	

<script language="javascript">
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
      <!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      