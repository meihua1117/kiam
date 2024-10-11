<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 아이엠 정보 수정
*/
$memid = $_GET['memid'];
$query_check = "select iam_making from Gn_Iam_automem where memid = '$memid'";
$res = mysqli_query($self_con,$query_check);
$data = mysqli_fetch_array($res);
$iam_address = $data['iam_making'];
$query="update Gn_Iam_automem set iam_apply = 1 where memid = '$memid'";
mysqli_query($self_con,$query);
$query_card="update Gn_Iam_Name_Card set phone_display = 'Y',up_data=now() where mem_id = '$memid'";
mysqli_query($self_con,$query_card);
$query_card="select card_short_url from Gn_Iam_Name_Card where mem_id = '$memid' and group_id is NULL";
$result = mysqli_query($self_con,$query_card);
while($row = mysqli_fetch_array($result)){
    $card_short_url = $row['card_short_url'];
    $update_query = "update Gn_Iam_Contents set public_display = 'Y',up_data=now() where westory_ card_url = '$card_short_url'";
    mysqli_query($self_con,$update_query);
}
echo "<script>alert('고객님의 아이엠이 자동생성되었습니다.아래 [확인]버튼을 클릭하면 생성된 아이엠을 볼수 있습니다.');location='$iam_address';</script>";
?>