<?php include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";?>
<?php
$date = date("Y-m-d H:i:s");
$mode = $_POST['type'];
$mem_id = $_POST['mem_id'];
$cont_idx = $_POST['cont_idx'];
$post_id = $_POST['post_id'];
$content = $_POST['content'];
$phone = $_POST['phone'];

$report_title = $_POST['report_title'];
$report_desc = $_POST['report_desc'];
$report_phone = $_POST['report_phone'];

if($mode == "report_reg"){
  if(!$cont_idx || !$mem_id) {
    echo '잘못된 접근입니다. 다시시도 해주세요.';
    exit;
  }
  $sql_cont = "select mem_id from Gn_Iam_Contents where idx='{$cont_idx}'";
  $res_cont = mysqli_query($self_con, $sql_cont);
  $row_cont = mysqli_fetch_array($res_cont);

  $sql2="insert into Gn_Iam_Post set mem_id='{$mem_id}', reporter_phone='{$report_phone}', content_idx='{$cont_idx}', reg_date='{$date}', title='{$report_title}', content='{$report_desc}', type=1";
  $result2 = mysqli_query($self_con, $sql2) or die(mysqli_error($self_con));
  echo $result2;
}
else if($mode == "reg_post_response"){
  if(!$post_id || !$mem_id) {
    echo '잘못된 접근입니다. 다시시도 해주세요.';
    exit;
  }
  $sql = "insert into Gn_Iam_Post_Response set post_idx='{$post_id}', contents='{$content}', reg_date='{$date}', mem_id='{$mem_id}', type=1";
  $res = mysqli_query($self_con, $sql);

  $send_num = "01083904260";
  sendmms(5, $mem_id, $send_num, $phone, "", "신고답변문자", $content, "", "", "", "Y");
  
  echo $res;
}

exit;
?>
