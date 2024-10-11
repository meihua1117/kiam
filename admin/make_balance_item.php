<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_GET);
// 오늘날짜
$date_today=date("Ymd");
$date_month=date("Ym");
$date_month = $_REQUEST['search_year'].$_REQUEST['search_month'];
$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
$startPage = $nowPage?$nowPage:1;
$pageCnt = 20;
$date = date("Y-m-d", strtotime( $_REQUEST['search_year']."-".$_REQUEST['search_month']." +1 month"));
$end_date = date("Y-m-d", strtotime( $_REQUEST['search_year']."-".$_REQUEST['search_month'].""));
                	
$query = "SELECT * FROM Gn_Item_Pay_Result where pay_date < '$date' and pay_status = 'Y' and point_val=0 and gwc_cont_pay=0";
$res	    = mysqli_query($self_con,$query);
$totalCnt	=  mysqli_num_rows($res);
$limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
$number			= $totalCnt - ($nowPage - 1) * $pageCnt;
$orderQuery .= "
	ORDER BY no DESC
";            	
$i = 1;
$query .= "$orderQuery";
$res = mysqli_query($self_con,$query);
while($row = mysqli_fetch_array($res)) {                       	
    $query = "select bid from Gn_Item_Pay_Result_Balance where pay_no='$row[no]' AND balance_date='$date_month'";
    $sres = mysqli_query($self_con,$query);
    $srow = mysqli_fetch_array($sres);
    if($srow[0] == "") {
        $query = "insert into Gn_Item_Pay_Result_Balance set pay_no='$row[no]',
                                                         mem_id='{$row['buyer_id']}',
                                                         seller_id='$row[seller_id]',
                                                         item_name='$row[item_name]',
                                                         share_per='$row[pay_percent]',
                                                         price='$row[item_price]',
                                                         balance_date='$date_month',
                                                         pay_date='$row[pay_date]',
                                                         regdate=NOW()
                                                         ";
        mysqli_query($self_con,$query);
    }
    $i++;
}
echo "<script>alert('생성되었습니다.');location.href='/admin/payment_item_balance_list.php?search_year=$_REQUEST[search_year]&search_month=$_REQUEST[search_month]';</script>";
?> 
                     