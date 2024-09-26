<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);
// 오늘날짜
$date_today=date("Y-m-d");
if(isset($_POST['get'])){
    $mem_id = $_POST['memid'];
    $item_type = $_POST['mem_type'];
    $sql = "select * from gn_iam_item where mem_id='{$mem_id}' and item_type='{$item_type}' order by id desc limit 1";
    $result = mysqli_query($self_con, $sql);
    $row = mysqli_fetch_array($result);

    echo '{"name":"'.$row['item_name'].'", "point":'.$row['current_point'].', "count":'.$row['current_cnt'].', "item_point":"'.$row['item_point'].'", "payMethod":"'.$row['pay_type'].'"}';
}
?>