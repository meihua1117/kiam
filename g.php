<?
header("Content-type:text/html;charset=utf-8");
include_once "lib/db_config.php";
$sql = "select * from Gn_MMS where uni_id='{$_REQUEST['u']}' ";
$resul = mysqli_query($self_con, $sql);
$row = mysqli_fetch_array($resul);

$mem_sql = "SELECT site_iam FROM Gn_Member WHERE mem_id='{$row['mem_id']}'";
$mem_res = mysqli_query($self_con, $mem_sql);
$mem_row = mysqli_fetch_assoc($mem_res);

if($mem_row['site_iam'] == "kiam")
	$HTTP_HOST = "kiam.kr";
else
	$HTTP_HOST = $mem_row['site_iam'].".kiam.kr";
	//$HTTP_HOST = str_replace("www.", "", $_SERVER['HTTP_HOST']);
if ($_REQUEST['mode'] == "inser") {
	$sql_d = "select idx from Gn_MMS_Agree where send_num='{$row['send_num']}' and recv_num='{$_REQUEST['n']}' ";
	$resul_d = mysqli_query($self_con, $sql_d);
	$row_d = mysqli_fetch_array($resul_d);
	if ($row_d['idx'] != null) {
		$deny_info['send_num'] = $row['send_num'];
		$deny_info['recv_num'] = $_REQUEST['n'];
		$deny_info['mem_id'] = $row['mem_id'];
		$deny_info['title'] = $row['title'];
		$deny_info['content'] = substr(htmlspecialchars($row['content']), 0, 20) . "...";
		$deny_info['jpg'] = $row['jpg'];
		$deny_info['jpg1'] = $row['jpg1'];
		$deny_info['jpg2'] = $row['jpg2'];
		if ($row['up_date'] == "")
			$row['up_date'] = 'now()';
		$deny_info['up_date'] = $row['up_date'];
		$deny_info['status'] = 'A';
		$sql = "insert into Gn_MMS_Agree set ";
		foreach ($deny_info as $key => $v)
			$sql .= " $key='$v' , ";
		$sql .= " reg_date=now() ";
		$sql = mb_convert_encoding($sql, 'UTF-8', 'auto');
		$res = mysqli_query($self_con,	$sql);
	}
	$user_id = $row['mem_id'];
	if ($_REQUEST['u']) { ?>
		<script>
			alert('귀하가 <?= date("Y"); ?>년<?= date("m"); ?>월<?= date("d"); ?>일에 요청하신 수신동의가 정상적으로 처리 되었습니다.');
			location.href = 'https://<?= $HTTP_HOST ?>';
		</script>
	<?
	} else {
	?>
		<script>
			alert('수신동의가 정상적으로 처리되었습니다.');
			location.href = 'https://<?= $HTTP_HOST ?>';
		</script>
	<?
	}
} else {
	?>
	<script>
		if (confirm('무료 문자 수신동의를 하시겠습니까?'))
			location.href = '<?= $PHP_SELF ?>?u=<?= $_REQUEST['u'] ?>&n=<?= $_REQUEST['n'] ?>&mode=inser';
		else
			location.href = 'https://<?= $HTTP_HOST ?>';
	</script>
<? } ?>