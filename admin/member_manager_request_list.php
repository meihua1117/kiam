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
   location.href = '?<?=$nowPage?>&nowPage='+pgNum;
  }
</script>   
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
            사업자신청관리
            <small>사업자신청회원을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">사업자신청관리</li>
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
              <form method="get" name="search_form" id="search_form">
              <div class="box-tools">
                <div class="input-group" style="width: 250px;">
                  <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/이름/휴대폰">
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
                      <col width="100px">
                      <col width="100px">
                      <col width="150px">
                      <col width="100px">

                     </colgroup>
                    <thead>
                      <tr>
                        <th>번호</th>
                        <th>회원정보</th>
                        <th>신청자이름</th>
                        <th>신청자직급</th>
                        <th>신청자매출</th>
                        <th>신청자소속</th>
                        <th>희망직급</th>
                        <th>신청일시</th>
                        <th>변경</th>
                        <th>승인일시</th>
                        
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                	
                    // 검색 조건을 적용한다.
                    $searchStr .= $search_key ? " AND (a.mem_id LIKE '%".$search_key."%' or a.mem_phone like '%".$search_key."%' or a.mem_name like '%".$search_key."%' or b.sendnum like '%".$search_key."%')" : null;
                	
                	$order = $order?$order:"desc"; 		
                	
                	$query = "
                        	SELECT 
                        	    SQL_CALC_FOUND_ROWS 
                        	    a.mem_code, a.mem_group, a.mem_id, a.mem_name, a.mem_nick, a.mem_phone, a.mem_id, a.web_pwd,
                        	    a.login_date, a.visited, a.limit_sms, a.level, a.fujia_date1, a.fujia_date2, a.ext_grp, a.service_type, 
                        	    a.service_type, a.service_want_type, a.service_edit_time, a.request_type 
                        	FROM Gn_Member a
                        	WHERE 1=1 
                        	and mem_leb='22'
                        	and business_type='B'
                	              $searchStr";
                	              
                	$res	    = mysqli_query($self_con,$query);
                	$totalCnt	=  mysqli_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                    $orderQuery .= "
                    	ORDER BY a.mem_code DESC
                    	$limitStr
                    ";            	
                	
                	$i = 1;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con,$query);
                    while($row = mysqli_fetch_array($res)) {                       	
                    	$sql = "select * from Gn_Member where recommend_id='$row[mem_id]'";
                    	$res_result = mysqli_query($self_con,$sql);
                    	$sInfo = mysqli_fetch_array($res_result);
                    	mysqli_free_result($res_result);                        
                        
                    	$sql = "select count(mem_id) cnt from Gn_Member where recommend_id='$row[mem_id]'";
                    	$res_result = mysqli_query($self_con,$sql);
                    	$sCnt = mysqli_fetch_array($res_result);
                    	mysqli_free_result($res_result);
                    	
                    	
                    	
                    	$sql = "select count(buyer_id) cnt 
                    	          from tjd_pay_result p
                    	      left join Gn_Member gm
                    	             on p.buyer_id = gm.mem_id 
                	              where recommend_id = '".$row['mem_id']."' 
                	                and end_date > '$date_today' 
                	                and end_status='Y' 
                	           order by end_date desc limit 1";
                    	$res_result = mysqli_query($self_con,$sql);
                    	$rCnt = mysqli_fetch_array($res_result);            
                       if($row['service_type'] == 1) {
                           $mem_level = "소비자";
                       } else if($row['service_type'] == 2) {
                            $mem_level = "직원";
                       } else if($row['service_type'] == 3) {
                            $mem_level = "일반 대리점";
                       } else if($row['service_type'] == 4) {
                            $mem_level = "지사 대리점";
                       } else if($row['service_type'] == 5) {
                            $mem_level = "총판 대리점";
                       } else {
                            $mem_level = "무료회원";
                       }                   	        	
                       
                       if($row['service_want_type'] == 1) {
                           $mem_want_level = "소비자";
                       } else if($row['service_want_type'] == 2) {
                            $mem_want_level = "직원";
                       } else if($row['service_want_type'] == 3) {
                            $mem_want_level = "일반 대리점";
                       } else if($row['service_want_type'] == 4) {
                            $mem_want_level = "지사 대리점";
                       } else if($row['service_want_type'] == 5) {
                            $mem_want_level = "총판 대리점";
                       } else {
                            $mem_want_level = "무료회원";
                       }                   	        	                       

                  ?>
                      <tr>
                        <td><?=$number--?></td>
                        <td>
                            <a href="member_manager_detail.php?mem_code=<?=$row['mem_code']?>&sendnum=<?=$row['sendnum']?>">수정</a> / 
                            <a href="javascript:del_member_info('<?=$row['mem_code']?>&sendnum=<?=$row['sendnum']?>')">탈퇴</a></td>
                        <td><?=$row['mem_name']?></td>
                        <td>
                            <?php echo $mem_level;?>    
                            <!--
                            <?php if($_SESSION['one_member_admin_id'] == "onlyonemaket"){?>
                            <?=$row['mem_id']?>
                            <?Php } else {?>
                            <a href="javascript:loginGo('<?=$row['mem_id']?>', '<?=$row['web_pwd']?>', '<?=$row['mem_code']?>');"><?=$row['mem_id']?></a>
                            <?php }?>
                            -->
                        </td>
                        <td>
                            (건수/수당)
                        </td>
                        <td>
                            <?php echo $sInfo[mem_name];?>
                        </td>
                        <td>
                            <?php echo $mem_want_level;?>
                        </td>
                        <td><?=$row['business_date']?></td>
                        <td>
                            <?php if(str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""){?>
                            <!--
                            <select name="mem_leb" id="mem_leb<?=$row['mem_code']?>">
                                <option value="22" <?php echo $row['mem_leb'] == "22"?"selected":""?>>일반</option>
                                <option value="50" <?php echo $row['mem_leb'] == "50"?"selected":""?>>사업자</option>
                            </select>
                            -->
                            <select name="service_type" id="service_type<?=$row['service_type']?>">
                                <option value="1" <?php echo $row['mem_leb'] == "1"?"selected":""?>>소비자</option>
                                <option value="2" <?php echo $row['mem_leb'] == "2"?"selected":""?>>직원</option>
                                <option value="3" <?php echo $row['mem_leb'] == "3"?"selected":""?>>일반 대리점</option>
                                <option value="4" <?php echo $row['mem_leb'] == "4"?"selected":""?>>지사 대리점</option>
                                <option value="5" <?php echo $row['mem_leb'] == "5"?"selected":""?>>총판 대리점</option>
                            </select>                            
                            <input type="button" name="변경" value=" 변경 " onclick="changeLevel('<?=$row['mem_code']?>')">
                            
                            <?php }?>
                        </td>                          
                        <td><?=$row['service_edit_time']?></td>
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
      </div><!-- /.content-wrapper -->

    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />        
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />                
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>	

<script language="javascript">
function loginGo(mem_id, mem_pw, mem_code) {
    $('#one_id').val(mem_id);
    $('#mem_pass').val(mem_pw);
    $('#mem_code').val(mem_code);
    $('#login_form').submit();
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

</script>
      <!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      