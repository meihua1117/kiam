
<?
include_once "../../lib/rlatjd_fun.php";

$mem_id = $_SESSION[iam_member_id];

$qu = htmlspecialchars($_POST[question]);
$an = htmlspecialchars($_POST[answer]);

$sql_insert = "insert into Gn_Gpt_Req_List set mem_id='{$mem_id}', gpt_question='{$qu}', gpt_answer='{$an}', reg_date=now()";
$res = mysql_query($sql_insert);
echo $res;
?>