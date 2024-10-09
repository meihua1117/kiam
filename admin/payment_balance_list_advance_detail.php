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
$searchStr .= $search_key ? " AND (a.buyer_id LIKE '%".$search_key."%' or b.mem_id like '%".$search_key."%' or b.mem_name like '%".$search_key."%' or a.TotPrice='$search_key' ) " : null;
$searchStr .=" and balance_date= '$search_year$search_month'";
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
  location.href = '?nowPage='+pgNum+'&search_leb=<?=$search_leb?>&search_key=<?=$search_key?>';
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
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='payment_balance_advance_list.php'"><i class="fa fa-download"></i> 전체보기</button>             
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='?type=2'"> 지급완료</button>
              <button class="btn btn-primary pull-right" style="margin-right: 5px;" onclick="location='?type=1'"> 지급대기</button>
              <form method="get" name="search_form" id="search_form" class="form-inline">
                <div class="box-tools">
                  <div class="input-group">
                    <div class="form-group"></div>
                    <div class="form-group">  
                      <select name="search_year">
                          <?php for($i=$search_year-4;$i<=$search_year;$i++){?>
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
                      <input type="hidden" name="mem_id" value="<?php echo $mem_id;?>">
                      <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="이름/아이디">
                    </div>  
                    <div class="input-group-btn">
                      <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </div>            
              </form>
            </div>            
          </div>
          <div>
            수당합계 : <span id="total_share"></span>
            지급합계 : <span id="total_balance"></span>
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
                      <col width="60px">
                      <col width="100px">
                      <col width="60px">
                      <col width="100px">
                      <col width="100px">
                      <col width="100px">
					            <col width="150px">
                      <col width="60px">
                    </colgroup>
                    <thead>
                      <tr>
                        <th style="text-align:center">번호</th>
                        <th style="text-align:center">판매자<br>직급/이름/ID</th>
                        <th style="text-align:center">구매자<br>직급/이름/ID</th>
                        <th style="text-align:center">상품명</th>
                        <th style="text-align:center">전화번호</th>
                        <th style="text-align:center">구매(월)</th>
                        <th style="text-align:center">구매금액</th>
                        <th style="text-align:center">배당율</th>
                        <th style="text-align:center">정산액</th>
                        <th style="text-align:center">지급액</th>
                        <th style="text-align:center">미지급액</th>
                        <th style="text-align:center">지급일시</th>
                        <th style="text-align:center">삭제</th>
                        <!--<th>환급일자<br>환급액</th> -->
                      </tr>
                    </thead>
                    <tbody>
                  <?
                    $query = "Select * from Gn_Member where mem_id='$mem_id'";
                    $cres = mysql_query($query);
                    $crow = mysql_fetch_array($cres);                                              
                	  $query = "SELECT 
                                  a.pay_no, 
                                  a.bid,
                                  a.mem_type,
                                  b.mem_id,
                                  b.mem_name,
                                  b.mem_phone,
                                  a.price,
                                  (a.price/1.1*0.01*a.share_per) total_price,
                                  a.balance_date,
                                  a.balance_confirm_date,
                                  a.balance_yn,
                                  a.share_per,
                                  a.branch_share_per,
                                  a.seller_id,
                                  a.branch_id,
                                  b.service_type,
                                  'seller' type
                              FROM Gn_Member b
                              INNER JOIN tjd_pay_result_balance a
                                    on b.mem_id =a.mem_id
                              WHERE 1=1 
                                    $searchStr
                                    and seller_id='$mem_id'
                              UNION SELECT 
                                  a.pay_no, 
                                  a.bid,
                                  a.mem_type,
                                  b.mem_id,
                                  b.mem_name,
                                  b.mem_phone,
                                  a.price,
                                  (a.price/1.1*0.01*a.branch_share_per) total_price,
                                  a.balance_date,
                                  a.branch_balance_confirm_date as balance_date,
                                  a.branch_balance_yn as balance_yn,
                                  a.share_per,
                                  a.branch_share_per,
                                  a.seller_id,
                                  a.branch_id,
                                  b.service_type,
                                  'branch' type
                              FROM Gn_Member b
                              INNER JOIN tjd_pay_result_balance a
                                    on b.mem_id =a.mem_id
                              WHERE 1=1 
                                    $searchStr
                                    and branch_id='$mem_id'";
                    $res	    = mysql_query($query);
                    $number = $totalCnt	=  mysql_num_rows($res);	
                    $orderQuery .= " ORDER BY mem_id ,pay_no desc";   
                	  $i = 1;
                    $query .= $orderQuery;
                	  $res = mysql_query($query);
                    while($row = mysql_fetch_array($res)) {                       	
                        if($row[total_price] == 500000) {
                            $query = "Select * from tjd_pay_result_delaer where m_id='$row[mem_id]'";
                            $sres = mysql_query($query);
                            $srow = mysql_fetch_array($sres);                            
                            if(substr($row['date'],0,10) != substr($srow['regtime'], 0,10)) {
                                $row[total_price] = 0;
                            }
                        }
                        if($row['type'] == 'seller'){
                            //$cmem_level = "리셀러";
                          $cmem_id = $row[seller_id];
                          $mem_query = "select mem_name,service_type from Gn_Member where mem_id='$row[seller_id]'";  
                        }else {
                            //$cmem_level = "분양자";
                          $cmem_id = $row[branch_id];
                          $mem_query = "select mem_name,service_type from Gn_Member where mem_id='$row[branch_id]'";  
                        }
                        $mem_res = mysql_query($mem_query);
                        $mem_row = mysql_fetch_array($mem_res);
                        $cmem_name = $mem_row[mem_name];
                        if($mem_row['service_type'] == 2) {
                          $cmem_level = "리셀러";
                        } else if($mem_row['service_type'] == 3) {
                          $cmem_level = "분양자";
                        } else {
                          $cmem_level = "이용자";
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
                        $share_fee = $row[total_price];
                        if($row['balance_yn'] == "Y") 
                           $balance_fee = $share_fee;
                        $total_share += $share_fee;
                        $total_balance += $balance_fee;
                  ?>
                      <tr style="text-align:center">
                        <td><?=$number--?></td>
                        <td><?=$cmem_level."/".$cmem_name."/".$cmem_id?></td>
                        <td><?=$mem_level."/".$row['mem_name']."/".$row['mem_id']?></td>
                        <td><?=$row['mem_type']?></td>
                        <td><?=str_replace("-", "",$row['mem_phone'])?></td>
                        <td><?=substr($row[balance_date],0,10)?></td>
                        <td><?=number_format($row['price'])?>원</td>
                        <td><?=$row['type'] == 'seller'?$row['share_per']:$row['branch_share_per']?>%</td>
                        <td><?=number_format($share_fee)?> 원</td>
                        <td><?=number_format($balance_fee)?> 원 </td>
                        <td><?=number_format($share_fee - $balance_fee)?> 원</td>
                        <td><?=$row['balance_confirm_date']?></td>
                        <td><A href="javascript:void(delete_row('<?php echo $row['bid']?>'))">삭제</a></td>
                 <!--       <td>
                            <form method="post" name="ssForm<?=$i?>" id="ssForm<?=$i?>" action="ajax/payment_per_save.php">
                            <input type="hidden" name="no" value="<?php echo $row['no']?>" >
                            <input type="text" name="share_per" id="share_per<?=$i?>" value="<?=$row[share_per]?>"  style="width:70px;">
                            <input type="text" name="branch_share_per" id="branch_share_per<?=$i?>" value="<?=$row[branch_share_per]?>"  style="width:70px;">
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
                    	//echo drawPagingAdminNavi($totalCnt, $nowPage);
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
function delete_row(bid) {
    if(confirm('삭제하시겠습니까?')) {
        location = "ajax/payment_balance_advance_delete.php?mem_id=<?php echo $_GET['mem_id'];?>&search_year=<?php echo $search_year;?>&search_month=<?php echo $search_month;?>&bid="+bid;
    }
}    
$("#total_share").html('<?php echo number_format($total_share);?>');
$("#total_balance").html('<?php echo number_format($total_balance);?>');
</script>
<!-- Footer -->
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      
