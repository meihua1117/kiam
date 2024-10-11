<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

if(isset($_POST['memid'])){
    $mem_id = $_POST['memid'];
    $sql = "select TotPrice, payMethod from tjd_pay_result where buyer_id='{$mem_id}' order by no desc limit 1";
    $result = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($result);

    if($row['payMethod'] == "BANK"){
        echo 1;
    }
    else{
        $sql_update = "update Gn_Member set mem_point=mem_point+{$row['TotPrice']} where mem_id='{$mem_id}'";
        // echo $sql_insert; exit;
        mysqli_query($self_con,$sql_update);
    }
}
echo 1;
?>