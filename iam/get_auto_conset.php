<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

if(isset($_POST['id'])){
    $id = $_POST['id'];
    
    $sql = "select * from auto_update_contents where id={$id}";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);

    if($row['state_flag'] == 2){
        echo json_encode(array('state'=>$row['state_flag']));
    }
    else{
        $sql_short = "select card_short_url from Gn_Iam_Name_Card where idx={$row['card_idx']}";
        $res_short = mysqli_query($self_con,$sql_short);
        $row_short = mysqli_fetch_array($res_short);
        $short_url = $row_short['card_short_url'];
        echo json_encode(array("id"=>$row['id'], "web_type"=>$row['web_type'], "web_address"=>$row['web_address'], "keyword"=>$row['keyword'], "state_flag"=>$row['state_flag'], "get_time"=>$row['get_time'], "start_date"=>$row['start_date'], "end_date"=>$row['end_date'], "card_url"=>$short_url));
    }
}
?>