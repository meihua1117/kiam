<?include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";?>
<?
// echo "<script>console.log('console : ". $_SESSION[one_member_id]. "');</script>"

$mode = $_POST['mode'];
$card_idx = $_POST['card_idx'];
$mem_id = $_POST['mem_id'];
$favorite = $_POST['favorite'];
$phone_display = $_POST['phone_display'];

if(!$mem_id){
  echo '로그아웃 되었습니다. 다시 로그인을 해주세요.';
  exit;
}

if(!$card_idx) {
  echo '잘못된 접근입니다. 다시시도 해주세요.';
  exit;
}

if($mode == "display") {
  // Julian mem_id에 달린 카드 모두 비공개로 설정한다.
  $sql = "select count(*) from Gn_Iam_Service where profile_idx = '$card_idx'";
  $res = mysql_query($sql);
  $row = mysql_fetch_array($res);
  $result = $row[0];
  if($result > 0)
    $phone_display = "Y";
  //해당한 카드 비공개로 설정한다.
  $sql="update Gn_Iam_Name_Card set phone_display = '$phone_display' where idx = '$card_idx'";
  mysql_query($sql) or die(mysql_error());
  //해당한 카드안의 콘텐츠들을 비공개로 설정한다.
  $sql = "select card_short_url from Gn_Iam_Name_Card where idx = '$card_idx'";
  $res = mysql_query($sql);
  $row = mysql_fetch_array($res);
  $card_short_url = $row['card_short_url'];
  $update_query = "update Gn_Iam_Contents set public_display = '$phone_display',up_data=now() where westory_card_url = '$card_short_url'";
  mysql_query($update_query);

  $sql="select * from Gn_Iam_Name_Card where idx = '$card_idx'";
  $result = mysql_query($sql) or die(mysql_error());
  $data = mysql_fetch_array($result);
  echo json_encode($data);
} else {
  $sql="update Gn_Iam_Name_Card set favorite = 0 where mem_id = '$mem_id'";
  $result = mysql_query($sql) or die(mysql_error());
  $sql2="update Gn_Iam_Name_Card set favorite = '$favorite' where idx = '$card_idx' and mem_id = '$mem_id'";
  $result2 = mysql_query($sql2) or die(mysql_error());
  echo $favorite;
}
?>
