<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

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
      <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>      

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
             아이엠 회원관리
            <small>아이엠회원을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">아이엠 회원관리</li>
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
          
          <div class="row">
            
              <div class="box">
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped">
                     <colgroup>
                      <col width="50px">
                      <col width="50px">
                      <col width="80px">
                      <col width="60px">
                      <col width="100px">
                      <col width="100px">
                      <col width="70px">
                      <col width="130px">
                      <col width="50px">
                      <col width="100px">
                      <col width="100px">
                      <col width="50px">					  
                      <col width="50px">					  
                 </colgroup>
                    <thead>               
                                      
                      <tr>
                        <th>NO</th>
                        <th>수정<br>탈퇴</th>
                        <th>아이디</th>
                        <th>이름</th>
                        <th>소속<br>직책</th>
                        <th>폰번호</th>
                        <th>EMAIL</th>
                        <th>ADDRESS</th>
                        <th>카드<br>갯수</th>
                        <th>공개<br>여부</th>
                        <th>접속<br>시간</th>
                        <th>공유ID<br>승인건수</th>
                        <th>샘플<br>선택</th>
                      </tr>
                    </thead>
                    
                    <? // 검색 조건을 적용한다.
                    $searchStr .= $search_key ? " AND (a.mem_id LIKE '%".$search_key."%' or a.card_phon like '%".$search_key."%' or a.card_name like '%".$search_key."%' or a.card_company like  '%" : null;
                    
                    if($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == $HTTP_HOST) {
                        $do = explode(".", $HTTP_HOST);
                        $searchStr .= " and a.site = '".$do[0]."'";
                    }
                        
                        
                    if($case == "1") {
                        $searchStr .= " and (REPLACE(a.card_phone,'-','')=REPLACE(b.sendnum, '-','') or b.sendnum is null)";
                    } else if($case == "2") {
                        $searchStr .= " and (REPLACE(a.card_phone,'-','')!=REPLACE(b.sendnum,'-','') and b.sendnum is not null)";
                    }
                	
                	$order = $order?$order:"desc"; 		
                	
                	$query = "
                        	SELECT 
                        	    SQL_CALC_FOUND_ROWS 
                        	  * 
                        	FROM Gn_Iam_Name_Card a
                        	INNER JOIN Gn_Member b
                        	       on b.mem_id =a.mem_id
                        	WHERE group_id = 0 $searchStr";
                	              
                	$res	    = mysqli_query($self_con,$query);
                	$totalCnt	=  mysqli_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                    $orderQuery .= "
                    	ORDER BY a.mem_code DESC
                    	$limitStr
                    ";      
          ?> 
          
          
          <tr>
                        <td><?=$number--?></td>
                        <td>
                            <a href="member_manager_detail.php?mem_code=<?=$row['mem_code']?>&sendnum=<?=$row['sendnum']?>">수정</a> / 
                            <a href="javascript:del_member_info('<?=$row['mem_code']?>&sendnum=<?=$row['sendnum']?>')">탈퇴</a></td>
                        <td>
                            <?php if($_SESSION['one_member_admin_id'] == "onlyonemaket"){?>
                            <?=$row['mem_id']?>
                            <?Php } else {?>
                            <a href="javascript:loginGo('<?=$row['mem_id']?>', '<?=$row['web_pwd']?>', '<?=$row['mem_code']?>');"><?=$row['mem_id']?></a>
                            <?php }?>
                        </td>
                        
                        

                <!--     <tr>
                        <td><?=$number--?></td>
                        <td>
                            <a href="member_detail.php?mem_code=<?=$row['mem_code']?>&sendnum=<?=$row['sendnum']?>">수정</a> / 
                            <a href="javascript:del_member_info('<?=$row['mem_code']?>')">탈퇴</a></td>
                        <td><?=$row['mem_id']?></td>
                        <td><?=$row['card_name']?></td>
                        <td><?=$row['card_company']?>/<?=$row['card_position']?></td>
                        <td><?=$row['card_phone']?></td>
                        <td><?=$row['card_email']?></td>
                        <td><?=$row['card_addr']?></td>
                        <td><?=$row['']?></td>
                        <td><?=$row['phone_display']?></td>
                        <td><?=$row['req_data']?></td>
                        <td><?=$row['site']?></td>-->
                        
 			
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            
      
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

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

function resetRow(cmid) {
    if(confirm('초기화 하시겠습니까?')) {

    $.ajax({
		type:"POST",
		url:"/admin/ajax/crawler_user_change.php",
		dataType:"json",
		data:{mode:"reset",cmid:cmid},
		success:function(data){
			alert('초기화 되었습니다.되었습니다.');
		},
		error: function(){
		  alert('초기화 실패');
		}
	});	        
    }
}
</script>
      <!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      