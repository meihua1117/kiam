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
   location.href = '?<?=$nowPage?>&nowPage='+pgNum+'&search_leb=<?=$search_leb?>&search_start_date=<?=$search_start_date?>&search_end_date=<?=$search_end_date?>&search_key=<?=$search_key?>';
  }
  
function payment_save(fm) {
    if(confirm('상태를 변경하시겠습니까?')) {
        $(fm).submit();
        return false;
    }
}  
function addcomma(str) {
    str = String(str);
    return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
}

function uncomma(str) {
    str = String(str);
    return str.replace(/[^\d]+/g, '');
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
            디버결제관리
            <small>디버결제를 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">디버결제관리</li>
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
              
              <form method="get" name="search_form" id="search_form" class="form-inline">
              <div class="box-tools">
                <div class="input-group"  >
                  <div class="form-group">
                  <select name="search_status" class="form-control" >
                      <option value="">결제상태</option>
                      <option value="Y">결제완료</option>
                      <option value="N">결제대기</option>
                      <option value="A">후불결제</option>
                  </select>
                  </div>
                  <div class="form-group">
                  <select name="search_leb" class="form-control" >
                      <option value="">전체</option>
                      <option value="50">사업자</option>
                      <option value="22">이용자</option>
                  </select>
                  </div>
                  <div class="form-group">  
                      <input type="text" name="search_start_date" placeholder="" id="search_start_date" value="<?=$_REQUEST['search_start_date']?>"/> ~
                      <input type="text"  name="search_end_date" placeholder="" id="search_end_date" value="<?=$_REQUEST['search_end_date']?>"/>                  
                  </div>
                  <div class="form-group">
                      <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="이름/아이디/금액">
                  </div>
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
                      <col width="60px">
                      <col width="100px">
                      <col width="140px">
                      <col width="130px">
                      <col width="200px">
                     </colgroup>
                    <thead>
                      <tr>
                        <th>번호</th>
                        <th>아이디</th>
                        <th>구분</th>
                        <th>이름</th>
                        <th>전화번호</th>
                        <th>결제종류</th>
                        <th>금액</th>
                        <th>기간</th>
                        <th>상태</th>
                        <th>가입일</th>
                        <th>결제일/종료일</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                	$startPage = $nowPage?$nowPage:1;
                	$pageCnt = 20;
                	
                    // 검색 조건을 적용한다.
                    $searchStr .= $search_key ? " AND (a.buyer_id LIKE '%".$search_key."%' or b.mem_phone like '%".$search_key."%' or b.mem_name like '%".$search_key."%' or a.TotPrice='$search_key' ) " : null;
                    if($search_start_date && $search_end_date) {
                        $searchStr .= " AND date >= '$search_start_date' and date <= '$search_end_date'";
                    } 
                    if($search_leb ) {
                        $searchStr .= " AND mem_leb='$search_leb'";
                    }
                    if($search_status) {
                        $searchStr .= " AND end_status='$search_status'";
                    }
                	
                	$order = $order?$order:"desc"; 		
                	
                	$query = "
                        	SELECT 
                        	    SQL_CALC_FOUND_ROWS 
                        	    *
                        	FROM tjd_pay_result_db a
                        	LEFT JOIN Gn_Member b
                        	       on b.mem_id =a.buyer_id
                        	WHERE 1=1 
                	              $searchStr";
                	              
                	$res	    = mysqli_query($self_con, $query);
                	$totalCnt	=  mysqli_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                    $orderQuery .= "
                    	ORDER BY a.no DESC
                    	$limitStr
                    ";            	
                	
                	$i = 1;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con, $query);
                    while($row = mysqli_fetch_array($res)) {                       	
                  ?>
                      <tr>
                        <td><?=$number--?></td>
                        <td><?=$row['mem_id']?></td>
                        <td><?=$row['member_type']?></td>
                        <td><?=$row['mem_name']?></td>
                        <td>
                            <?=str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""?str_replace("-", "",$row['mem_phone']):$row['sendnum']?>
                        </td>
                        <td><?=$pay_type[$row['payMethod']]?></td>
                        <td>
                        	<input type="text" name="price" id="price<?=$i?>" value="<?=$row['TotPrice']?>" onchange="$('#price_<?=$i?>').val(this.value)" style="width:70px;">
                        	원</td>
                        <td><?=number_format($row['month_cnt'])?> 개월 <A href="javascript:deleteRow('<?php echo $row['no']?>');" style="border:1px solid #000;padding:10px;">삭제</A></td>
                        <td>
                            <form method="post" name="ssForm<?=$i?>" id="ssForm<?=$i?>" action="ajax/crawler_payment_save.php">
                            <input type="hidden" name="no" value="<?php echo $row['no']?>" >
                            <input type="hidden" name="price" id="price_<?=$i?>" value="<?=$row['TotPrice']?>" >
                            <select name="payment_day">
                                <option value="" >결제일</option>
                                <option value="5" <?php echo $row['payment_day'] == "5"?"selected":""?>>5</option>
                                <option value="10" <?php echo $row['payment_day'] == "10"?"selected":""?>>10</option>
                                <option value="15" <?php echo $row['payment_day'] == "15"?"selected":""?>>15</option>
                                <option value="25" <?php echo $row['payment_day'] == "25"?"selected":""?>>25</option>
                                
                            </select>
                            <select name="end_status">
                                <option value="N" <?php echo $row['end_status'] == "N"?"selected":""?>>결제대기</option>
                                <option value="Y" <?php echo $row['end_status'] == "Y"?"selected":""?>>결제완료</option>
                                <option value="A" <?php echo $row['end_status'] == "A"?"selected":""?>>후불결제</option>
                                
                            </select>
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="payment_save('#ssForm<?=$i?>');return false;"><i class="fa fa-download"></i> 변경</button>
                            </form>
                            
                        </td>
                        <td><?=$row['first_regist']?></td>
                        <td><?=$row['date']?> / <?=$row['end_date']?> </td>
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
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />                
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>	

<script language="javascript">
function deleteRow(no) {
    if(confirm('삭제하시겠습니까?')) {
      $.ajax({
            type:"POST",
            url:"/admin/ajax/payment_delete.php",
            data:{
                no:no
            },
            success:function(data){
                alert('정확히 삭제되었습니다.');
                location.reload();
            }
        })
    }
}
function loginGo(mem_id, mem_pw, mem_code) {
    $('#one_id').val(mem_id);
    $('#mem_pass').val(mem_pw);
    $('#mem_code').val(mem_code);
    $('#login_form').submit();
}
</script>
      <!-- Footer -->
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                
<script language="javascript">
jQuery(function($){
 $.datepicker.regional['ko'] = {
  closeText: '닫기',
  prevText: '이전달',
  nextText: '다음달',
  currentText: 'X',
  monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)',
  '7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
  monthNamesShort: ['1월','2월','3월','4월','5월','6월',
  '7월','8월','9월','10월','11월','12월'],
  dayNames: ['일','월','화','수','목','금','토'],
  dayNamesShort: ['일','월','화','수','목','금','토'],
  dayNamesMin: ['일','월','화','수','목','금','토'],
  weekHeader: 'Wk',
  dateFormat: 'yy-mm-dd',
  firstDay: 0,
  isRTL: false,
  showMonthAfterYear: true,
  yearSuffix: ''};
 $.datepicker.setDefaults($.datepicker.regional['ko']);

    $('#search_start_date').datepicker({
        showOn: 'button',
  buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
  buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });
    $('#search_end_date').datepicker({
        showOn: 'button',
  buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
  buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });	
});
</script>      
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      
