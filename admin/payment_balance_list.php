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
$pageCnt = 100;

// 검색 조건을 적용한다.
$searchStr .= $search_key ? " AND (a.buyer_id LIKE '%".$search_key."%' or b.mem_phone like '%".$search_key."%' or b.mem_name like '%".$search_key."%' or a.TotPrice='$search_key' ) " : null;
if($search_start_date && $search_end_date) {
    $searchStr .= " AND date >= '$search_start_date' and date <= '$search_end_date'";
} 
$search_start_date = $search_year."-".sprintf("%02d", $search_month)."-01";
$search_end_date = date('Y-m', strtotime('+1 month', strtotime("$search_start_date")))."-01";

//$searchStr .=" and a.date >= '$search_start_date' and date <= '$search_end_date'";
$searchStr .=" and date between '$search_start_date 00:00:00' and '$search_end_date 00:00:00'";
//$searchStr .=" and DATE_FORMAT(date, '%Y-%m') = '$search_year-$search_month'";

$order = $order?$order:"desc"; 		

$total_share = 0;
$total_balance = 0;
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
            사업자 정산관리
            <small>사업자 정산을 관리합니다.</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">사업자정산관리</li>
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


            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='crawler_member_detail.php'"><i class="fa fa-download"></i> 전체보기</button>
              
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='?type=2'"> 지급완료</button>
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='?type=1'"> 지급대기</button>



  <!--
            <form method="get" name="search_form" id="search_form" class="form-inline">
              <div class="box-tools">
                <div class="input-group"  >
                  <div class="form-group">
                  </div>
                  <div class="form-group">  
                      <input type="text" name="search_start_date" placeholder="" id="search_start_date" value="<?=$_REQUEST['search_start_date']?>"/> ~
                      <input type="text"  name="search_end_date" placeholder="" id="search_end_date" value="<?=$_REQUEST['search_end_date']?>"/>                  
                  </div>
                  <div class="form-group">
                      <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="이름/아이디/금액">
                  </div>
-->



            <form method="get" name="search_form" id="search_form" class="form-inline">
              <div class="box-tools">
                <div class="input-group"  >
                  <div class="form-group">
  
                  </div>
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
                      <col width="60px">
                      <col width="100px">
                      <col width="140px">
                      <col width="300px">
                     </colgroup>
                    <thead>
                      <tr>
                        <th>번호</th>
                        <th>상세보기</th>
                        <th>사업자ID<br>
                        <th>사업자직급</th>
                        <th>추천자ID</th>
                        <th>전화번호</th>
                        <th>정산(월)</th>
                        <th>정산액</th>
                        <!--<th>세부내역</th>-->
                        <th>지급액</th>
                        <th>지급여부</th>
                        <th>지급일</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?

                	
                	$query = "
                        	SELECT 
                        	    SQL_CALC_FOUND_ROWS 
                        	    b.mem_id,
                        	    b.mem_name,
                        	    b.mem_phone,
                        	    c.price as total_price,
                        	    c.payment_date,
                        	    b.service_type
                        	FROM Gn_Member b
                            INNER JOIN (   select 
                                              bb.recommend_id, 
                                              DATE_FORMAT(aa.date, '%Y%m') payment_date,
                                              sum(TotPrice) price 
                                         from tjd_pay_result aa 
                                    left join Gn_Member bb
                                           on bb.mem_id = aa.buyer_id 
                                        where 1=1 
                                        $searchStr
                                        and end_status='Y' 
                                        AND recommend_id <> ''
                                        group by bb.recommend_id, payment_date
                                    ) c
                                   on b.mem_id = c.recommend_id 
                        	WHERE 1=1 
                	              and b.service_type > 0
                	              ";
                	$res	    = mysqli_query($self_con,$query);
                	$totalCnt	=  mysqli_num_rows($res);	
                	
                	$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                	$number			= $totalCnt - ($nowPage - 1) * $pageCnt;                      
                	
                    $orderQuery .= "
                    	ORDER BY b.mem_id DESC
                    	$limitStr
                    ";            	
            	    $i = 1;
                	$query .= "$orderQuery";
                	$res = mysqli_query($self_con,$query);
                    while($row = mysqli_fetch_array($res)) {                       	
                        //if($row['total_price'] == 500000) {
                        //    $query = "Select * from tjd_pay_result_delaer where m_id='{$row['mem_id']}'";
                        //    $sres = mysqli_query($self_con,$query);
                        //    $srow = mysqli_fetch_array($sres);                            
                        //    if(substr($row['date'],0,10) != substr($srow['regtime'], 0,10)) {
                        //        $row['total_price'] = 0;
                        //    }
                        //}
                        
                       if($row['service_type'] == 2) {
                           $mem_level = "리셀러";
                       } else if($row['service_type'] == 3) {
                            $mem_level = "분양자";
                       } else if($row['service_type'] == 1) {
                            $mem_level = "이용자";
                       } else {
                            $mem_level = "FREE";
                       }                           
                       $branch_share_fee = 0;
                       
                        $query = " select 
                                          sum(aa.TotPrice/1.1*0.01*aa.share_per) price,
                                          aa.share_per,
                                          aa.balance_yn,
                                          aa.balance_date
                                     from tjd_pay_result aa 
                                left join Gn_Member bb
                                       on bb.mem_id = aa.buyer_id 
                                    where 1=1 
                                    and end_status='Y' 
                                    AND aa.share_id='{$row['mem_id']}'
                                    ";
                                    //echo $query."<BR>";
                       $sres = mysqli_query($self_con,$query);
                       $prow = mysqli_fetch_array($sres);
                       
                       $row['balance_yn']  ="N";
                       $row['balance_date'] = "";
                       $srow = "";
                       // 분양자일경우 리셀러의 20프로
                       if($mem_level == "분양자") {
                        
                            $query = " select 
                                              sum(aa.TotPrice/1.1*0.01*aa.branch_share_per) price,
                                              aa.branch_share_per ,
                                              aa.branch_balance_yn,
                                              aa.branch_balance_date
                                         from tjd_pay_result aa 
                                    left join Gn_Member bb
                                           on bb.mem_id = aa.buyer_id 
                                        where 1=1 
                                        $searchStr
                                        and end_status='Y' 
                                        AND aa.branch_share_id='{$row['mem_id']}'
                                        ";
                           $sres = mysqli_query($self_con,$query);
                           $srow = mysqli_fetch_array($sres);
                           
                           $branch_share_fee = $srow['price'];
                       }                  

                       //echo $prow['balance_yn']."=".$srow['branch_balance_yn']."<BR>";


                       if($prow['balance_yn'] =="Y") {
                          $row['balance_yn'] ="Y";
                          $row['balance_date'] = $prow['balance_date'];
                       }                       
                       if($srow['branch_balance_yn'] =="Y") {
                         $row['balance_yn'] ="Y";
                         $row['balance_date'] = $srow['branch_balance_date'];
                       }                       
                       $balance_fee = 0;
                       
                       
                       
                       $share_fee = number_format(($prow['price'])+$branch_share_fee);
                       if($row['balance_yn'] == "Y") 
                           $balance_fee = number_format(($prow['price'])+$branch_share_fee);
                           
                       $sum_share_fee += $prow['price'];
                       if($row['balance_yn'] == "Y") 
                       $sum_balance_fee += $prow['price'];
                       if($share_fee>0) {
                  ?>
                      <tr>
                        <td><?=$number--?></td>
                        <td><a href="payment_balance_list_detail.php?mem_id=<?=$row['mem_id']?>&search_year=<?php echo $search_year;?>&search_month=<?php echo $search_month;?>"><!--<?=$row['mem_id']?>-->상세보기</a></td>
                        <td><?=$row['mem_name']?></td>
                        <td><?php echo $mem_level;?></td>

                      
                        <td><?=$row['recommend_id']?></td>
                        <td>
                            <?=str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""?str_replace("-", "",$row['mem_phone']):$row['sendnum']?>
                        </td>
                        <td><?=$row['payment_date']?></td>
                        <td><?=$share_fee?> 원</td>
                        <td><?=$balance_fee?> </td>
                        <td>
                            <form method="post" name="ssForm<?=$i?>" id="ssForm<?=$i?>" action="ajax/payment_balance_save.php">
                            <input type="hidden" name="no" value="<?php echo $row['no']?>" >
                            <input type="hidden" name="mem_id" value="<?php echo $row['mem_id']?>" >
                            <input type="hidden" name="balance_date" value="<?php echo $search_year."-".$search_month?>" >
                            <select name="end_status">
                                <option value="N" <?php echo $row['balance_yn'] == "N"?"selected":""?>>지급대기</option>
                                <option value="Y" <?php echo $row['balance_yn'] == "Y"?"selected":""?>>지급완료</option>
                            </select>
                            <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="payment_save('#ssForm<?=$i?>');return false;"><i class="fa fa-download"></i> 변경</button>
                            </form>
                        </td>
                        <td><?=$row['balance_date']?></td>
                      </tr>
                    <?
                    }
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
            <div style="float:right;margin-right:20px;">
            / 지급 합계 : <?php echo number_format($sum_balance_fee);?>           
            </div>            
            <div style="float:right;margin-right:20px;">
            수당 합계 : <?php echo number_format($sum_share_fee);?>
            </div>
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
    </div>
<!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      
