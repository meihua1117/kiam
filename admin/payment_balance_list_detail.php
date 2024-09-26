<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$search_year = $search_year?$search_year:date("Y");
$search_month = $search_month?sprintf("%02d",$search_month):sprintf("%02d",date("m"));
/*
결제 금액에서 부가세 빼고
-사업자에게 일반 사용자 결제금액 1만원의 50%, 정산지속 기간 5년간
-사업자A가 추천한 사업자 B의 가입비용 50만원의 50%, 정산기간 1회
==> 지급일
기본: 지급액 입력시 자동나오게
수정: 날짜 수정도 가능하게

*/
$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
$startPage = $nowPage?$nowPage:1;
$pageCnt = 20;

// 검색 조건을 적용한다.
$searchStr .= $search_key ? " AND (a.buyer_id LIKE '%".$search_key."%' or b.mem_phone like '%".$search_key."%' or b.mem_name like '%".$search_key."%' or a.TotPrice='$search_key' ) " : null;
if($search_start_date && $search_end_date) {
    $searchStr .= " AND date >= '$search_start_date' and date <= '$search_end_date'";
} 
$search_start_date = $search_year."-".sprintf("%02d", $search_month)."-01";
$search_end_date = date('Y-m', strtotime('+1 month', strtotime("$search_start_date")))."-01";

//$searchStr .=" and a.date >= '$search_start_date' and date <= '$search_end_date'";
$searchStr .=" and date between '$search_start_date 00:00:00' and '$search_end_date 00:00:00'";
//$searchStr .=" and buyer_id='$mem_id'";
//$searchStr .=" and DATE_FORMAT(date, '%Y-%m') = '$search_year-$search_month'";

$order = $order?$order:"desc"; 		

$total_share = 0;
$total_balance = 0;
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
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
            사업자별 정산상세내역
            <small>사업자별 정산상세내역을 볼수 있습니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">정산상세보기</li>
          </ol>
        </section>
        <!-- Main content -->
        <section class="content">
          <div class="row">
            <div class="col-xs-12" style="padding-bottom:20px">
            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='crawler_member_detail.php'"><i class="fa fa-download"></i> 전체보기</button>             
            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='?type=2'"> 지급완료</button>
            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='?type=1'"> 지급대기</button>
              <form method="get" name="search_form" id="search_form" class="form-inline">
                <div class="box-tools">
                  <div class="input-group"  >
                    <div class="form-group"></div>
                    <div class="form-group">  
                      <select name="search_year">
                          <?php for($i=2019;$i<2023;$i++){?>
                              <option value="<?=$i?>"  <?php echo $i==$search_year?"selected":""?>><?=$i?></option>
                          <?php } ?>
                      </select>
                      <select name="search_month">
                          <?php for($i=1;$i<13;$i++){?>
                              <option value="<?=$i?>" <?php echo sprintf("%02d",$i)==$search_month?"selected":""?>><?=$i?></option>
                          <?php } ?>
                      </select>                      
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
          <div>
            수당합계 : <span id="total_share"><?php echo number_format($total_share);?></span>
            지급합계 : <span id="total_balance"><?php echo number_format($total_balance);?></span>
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
                      <col width="200px">
                      <col width="100px">
                      <col width="150px">
                      <col width="150px">
                      <col width="150px">
                      <col width="200px">
                     </colgroup>
                    <thead>
                      <tr>
                        <th>번호</th>
                        <th>판매자<br>ID직급</th>
                        <th>구매자ID</th>
                        <th>구매자명</th>
                        <th>구매자<br>직급</th>
                        <th>전화번호</th>
                        <th>구매(월)</th>
                        <th>구매<br>금액</th>
                        <th>정산액</th>
                        <!--<th>세부내역</th>-->
                        <th>지급액</th>
                        <th>미지급액</th>
                        <th>지급<br>일시</th>
                      
                <!--        <th>환급일자<br>환급액</th> -->

                      </tr>
                    </thead>
                    <tbody>
                  <?
                	$query = "SELECT 
                        	    a.no, 
                        	    b.mem_id,
                        	    b.mem_name,
                        	    a.end_status,
                        	    b.mem_phone,
                        	    a.TotPrice,
                        	    (a.TotPrice/1.1*0.01*a.share_per) total_price,
                        	    a.add_phone,
                        	    a.month_cnt,
                        	    a.date,
                        	    a.end_date,
                        	    a.balance_date,
                        	    a.balance_yn,
                        	    a.share_per,
                        	    a.branch_share_per,
                        	    b.service_type
                        	FROM Gn_Member b
                        	INNER JOIN tjd_pay_result a
                        	       on b.mem_id =a.buyer_id
                        	WHERE 1=1 
                	              $searchStr
                	              and share_id='$mem_id'
                	              and end_status='Y'
                        	UNION SELECT 
                        	    a.no, 
                        	    b.mem_id,
                        	    b.mem_name,
                        	    a.end_status,
                        	    b.mem_phone,
                        	    a.TotPrice,
                        	    (a.TotPrice/1.1*0.01*a.branch_share_per) total_price,
                        	    a.add_phone,
                        	    a.month_cnt,
                        	    a.date,
                        	    a.end_date,
                        	    a.branch_balance_date as balance_date,
                        	    a.branch_balance_yn as balance_yn,
                        	    a.share_per,
                        	    a.branch_share_per,
                        	    b.service_type
                        	FROM Gn_Member b
                        	INNER JOIN tjd_pay_result a
                        	       on b.mem_id =a.buyer_id
                        	WHERE 1=1 
                	              $searchStr
                	              and branch_share_id='$mem_id'   
                	              and end_status='Y'             	              
                                ";
                	$res	    = mysqli_query($self_con, $query);
                	$totalCnt	=  mysqli_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                    $orderQuery .= "
                    	ORDER BY no DESC
                    	$limitStr
                    ";            	
              
                	$i = 1;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con, $query);
                    while($row = mysqli_fetch_array($res)) {                       	
                        if($row['total_price'] == 500000) {
                            $query = "Select * from tjd_pay_result_delaer where m_id='{$row['mem_id']}'";
                            $sres = mysqli_query($self_con, $query);
                            $srow = mysqli_fetch_array($sres);                            
                            if(substr($row['date'],0,10) != substr($srow['regtime'], 0,10)) {
                                $row['total_price'] = 0;
                            }
                        }
                        
                       if($row['service_type'] == 2) {
                           $mem_level = "리셀러";
                       } else if($row['service_type'] == 3) {
                            $mem_level = "분양자";
                       } else if($row['service_type'] == 1) {
                            $mem_level = "이용자";
                       } else {
                            $mem_level = "FREE";
                       }                           
                       $balance_fee = 0;
                       $share_fee = $row['total_price'];
                       if($row['balance_yn'] == "Y") 
                           $balance_fee = $share_fee;
                  ?>
                      <tr>
                        <td><?=$number--?></td>
                        <td><?=$row['recommend_id']?></td>
                        <td><?=$row['mem_id']?></td>
                        <td><?=$row['mem_name']?></td>
                        <td><?php echo $mem_level;?></td>
                        <td>
                            <?=str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""?str_replace("-", "",$row['mem_phone']):$row['sendnum']?>
                        </td>
                        <td><?=substr($row['date'],0,10)?></td>
                        <td><?=$row['TotPrice']?></td>
                        <td><?=number_format($share_fee)?> 원</td>
                        <td><?=number_format($balance_fee)?> </td>
                        <td><?=number_format($share_fee - $balance_fee)?> </td>
                        <td><?=$row['balance_date']?></td>
                 <!--       <td>
                            <form method="post" name="ssForm<?=$i?>" id="ssForm<?=$i?>" action="ajax/payment_per_save.php">
                            <input type="hidden" name="no" value="<?php echo $row['no']?>" >
                            <input type="text" name="share_per" id="share_per<?=$i?>" value="<?=$row['share_per']?>"  style="width:70px;">
                            <input type="text" name="branch_share_per" id="branch_share_per<?=$i?>" value="<?=$row['branch_share_per']?>"  style="width:70px;">
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="payment_save('#ssForm<?=$i?>');return false;"><i class="fa fa-download"></i> 변경</button>
                            </form>
                        </td> -->

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
