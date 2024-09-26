<?include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

$mem_id = $_POST['mem_id'];

$sql_mem_code = "select mem_code from Gn_Member where mem_id='$mem_id'";
$res_code = mysqli_query($self_con, $sql_mem_code);
$row_code = mysqli_fetch_array($res_code);

$sql_card_url = "select card_short_url from Gn_Iam_Name_Card where mem_id='$mem_id' order by idx asc limit 1";
$res_url = mysqli_query($self_con, $sql_card_url);
$row_url = mysqli_fetch_array($res_url);

echo '{"short_url":"'.$row_url[0].'", "mem_code":"'.$row_code[0].'"}';
exit;
?>
