<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_GET);
// 오늘날짜
$date_month = $_REQUEST['search_year'].$_REQUEST['search_month'];
$nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
$startPage = $nowPage?$nowPage:1;
$pageCnt = 20;
$edate = date("Y-m-d", strtotime( $_REQUEST['search_year']."-".$_REQUEST['search_month']." +1 month"));
$sdate = date("Y-m-d", strtotime( $_REQUEST['search_year']."-".$_REQUEST['search_month'].""));
$query = "SELECT r.no, r.date,r.end_date,r.buyer_id,r.member_type,r.share_id,r.branch_share_id,r.share_per,r.branch_share_per,r.TotPrice 
            FROM tjd_pay_result_month m 
            inner join tjd_pay_result r on m.pay_idx = r.idx
            where r.date < '$edate' and r.end_date >= '$sdate' and m.regdate >= '$sdate' and m.regdate < '$edate' and r.share_id is not null and m.pay_yn='Y'";
$orderQuery .= " ORDER BY r.no DESC";       
$query .= $orderQuery;
$res = mysqli_query($self_con, $query);
$managers = array();
while($row = mysqli_fetch_array($res)) {                       	
    $query = "select count(bid) from tjd_pay_result_balance where pay_no='{$row['no']}' AND balance_date='$date_month'";
    $sres = mysqli_query($self_con, $query);
    $srow = mysqli_fetch_array($sres);
    if($srow[0] == 0) {
        $query = "insert into tjd_pay_result_balance set pay_no='{$row['no']}',
                                                            mem_id='{$row['buyer_id']}',
                                                            seller_id='{$row['share_id']}',
                                                            branch_id='${row['branch_share_id']}',
                                                            mem_type='{$row['member_type']}',
                                                            share_per='{$row['share_per']}',
                                                            branch_share_per='{$row['branch_share_per']}',
                                                            price='{$row['TotPrice']}',
                                                            balance_date='$date_month',
                                                            regdate=NOW()";
        mysqli_query($self_con, $query);        
    }
    $manager_query = "select count(idx) from Gn_Service where mem_id='{$row['share_id']}'";
    $manager_res = mysqli_query($self_con, $manager_query);
    $manager_row = mysqli_fetch_array($manager_res);
    if($manager_row[0] > 0)
        array_push($managers,$row['share_id']);
}

foreach(array_unique($managers) as $manager){
    if($manager){
        $mem_query = "select mem_id,balance_per from Gn_Member where recommend_id='$manager'";
        $mem_res = mysqli_query($self_con, $mem_query);
        while($mem_row = mysqli_fetch_array($mem_res)){
                $manager_query = "select count(idx) from Gn_Service where mem_id='{$mem_row['mem_id']}'";
                $manager_res = mysqli_query($self_con, $manager_query);
                $manager_row = mysqli_fetch_array($manager_res);
                if($manager_row[0] > 0){
                    $balance_query = "select sum(t.total_price) from 
                                        (SELECT a.pay_no, b.mem_id,(a.price/1.1*0.01*a.share_per) total_price
                                            FROM Gn_Member b INNER JOIN tjd_pay_result_balance a on b.mem_id =a.mem_id
                                            WHERE balance_date= '$date_month' and seller_id='{$mem_row['mem_id']}'
                                        UNION SELECT a.pay_no, b.mem_id,(a.price/1.1*0.01*a.branch_share_per) total_price
                                            FROM Gn_Member b INNER JOIN tjd_pay_result_balance a on b.mem_id =a.mem_id
                                            WHERE balance_date= '$date_month' and branch_id='{$mem_row['mem_id']}' ORDER BY mem_id ,pay_no desc) t";
                    $balance_res = mysqli_query($self_con, $balance_query);
                    $balance_row = mysqli_fetch_array($balance_res);
                    $price = $balance_row[0];
                    if($price == "")
                        $price = 0;
                    $balance = $mem_row['balance_per'];
                    //$price = $price * $balance / 100;
                    $query = "insert into tjd_pay_result_balance set pay_no=0,
                                                                    mem_id='{$mem_row['mem_id']}',
                                                                    seller_id='$manager',
                                                                    mem_type='분양자정산',
                                                                    share_per='$balance',
                                                                    price='$price',
                                                                    balance_date='$date_month',
                                                                    regdate=NOW()";
                mysqli_query($self_con, $query) or die(mysqli_error($self_con));
            }
        }
    }
}
echo "<script>alert('생성되었습니다.');location='/admin/payment_balance_advance_list.php?search_year=".$_REQUEST['search_year']."&search_month=".$_REQUEST['search_month']."';</script>";
?> 
                     