<?
include_once "../lib/rlatjd_fun.php";

if(isset($_POST['get']) && isset($_POST['id'])){
  $mem_id = $_POST['id'];
  $sql_memcode = "select mem_code from Gn_Member where mem_id='{$mem_id}'";
  $res_memcode = mysqli_query($self_con,$sql_memcode);
  $row_memcode = mysqli_fetch_array($res_memcode);

  if($_POST['get'] == "contents"){
    $sql = "select iam_link from crawler_iam_info where mem_id='{$mem_id}' order by id desc limit 1";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    echo '{"url":"'.$row['iam_link'].'", "mem_code":"'.$row_memcode['mem_code'].'"}';
  }
  else{
    $sql = "select card_short_url from Gn_Iam_Name_Card where group_id = 0 and mem_id='{$mem_id}' order by idx desc limit 1";
    $res = mysqli_query($self_con,$sql);
    $row = mysqli_fetch_array($res);
    echo '{"url":"'.$row['card_short_url'].'", "mem_code":"'.$row_memcode['mem_code'].'"}';
  }
}
?>