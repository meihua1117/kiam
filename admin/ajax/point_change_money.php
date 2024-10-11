<?
include_once "../../lib/rlatjd_fun.php";
$method = $_POST['method'];
$date = date("Y-m-d");
if ($method == 'change_status') {
    // 회원의 캐시정보를 차감한다.
    $memSql = "SELECT * From Gn_Member WHERE mem_id = '".$_POST['mem_id']."'";
    $mem_res = mysqli_query($self_con,$memSql);
    $mem_info = mysqli_fetch_array($mem_res);
    $mem_cash = $mem_info['mem_cash'] * 1;
    $TotPrice = $_POST['TotPrice'] * 1;

    if ($_POST['status'] == 'Y') {
        $changed_cash = $mem_cash - $TotPrice;
        if ($changed_cash < 0) {
            echo '금액초과';
            exit;
        }
        $sql = "UPDATE tjd_pay_result SET end_status = 'Y', applDate = '".$date."' WHERE `no` = '".$_POST['index']."'";
    } else {
        $changed_cash = $mem_cash + $TotPrice;
        $sql = "UPDATE tjd_pay_result SET end_status = 'N', applDate = '' WHERE `no` = '".$_POST['index']."'";
    }
    $mem_cash_update_sql = "UPDATE Gn_Member SET mem_cash = '".$changed_cash."' WHERE  mem_id = '".$_POST['mem_id']."'";
    $res = mysqli_query($self_con,$sql);
    $mem_cash_update_sql_res = mysqli_query($self_con,$mem_cash_update_sql);
    echo $res;
    exit;
}
?>