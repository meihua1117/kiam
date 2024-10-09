<?
include_once "../lib/rlatjd_fun.php";

//아이디 유무 판단
if($_POST['id'])
{
	// echo $_POST['id']; exit;
  $id_che=trim($_POST['id']);
  $sql="select * from Gn_Member where mem_id='$id_che'";
  $resul=mysql_query($sql);
  $row=mysql_fetch_array($resul);
  if($row['mem_id'])
  {
    $sql_name_card = "select card_short_url from Gn_Iam_Name_Card where mem_id='{$row['mem_id']}' order by req_data asc limit 1";
    $res_card = mysql_query($sql_name_card);
    $row_card = mysql_fetch_array($res_card);
    $iam_link = "http://kiam.kr/?".$row_card[0].$row['mem_code']."&type=image";
		echo '{"result":"0", "iam_link":"'.$iam_link.'"}';
	}
	else{
		echo '{"result":"1"}';
	}
}
?>