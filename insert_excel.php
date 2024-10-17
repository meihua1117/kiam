<?
include_once "lib/rlatjd_fun.php";
require $_SERVER['DOCUMENT_ROOT'] . '/excel_down/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if (!$_SESSION['one_member_id']) { ?>
	<script language="javascript">
		location.replace("<?= $_SERVER['HTTP_REFERER'] ?>")
	</script>
	<?
	exit;
}
if ($_FILES['excel_file']['tmp_name']) {
	$file_type= pathinfo($_FILES['excel_file']['name'], PATHINFO_EXTENSION);
	if ($file_type =='xls') {
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();	
	}
	elseif ($file_type =='xlsx') {
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
	}
	else {
		?>
		<script language="javascript">
			alert('처리할 수 있는 엑셀 파일이 아닙니다.');
		</script>
		<?
		exit;
	}
	
	$spreadsheet = $reader->load($_FILES['excel_file']['tmp_name']);	
	$spreadData = $spreadsheet-> getActiveSheet()->toArray(null,true,true,true);
	$excel_rows = count($spreadData);
	/*$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('utf-8');
	$data->read($_FILES['excel_file']['tmp_name']);
	error_reporting(E_ALL ^ E_NOTICE);
	$excel_rows = $data->sheets[0]['numRows'];
	*/
	if ($excel_rows > 10001) {
	?>
		<script language="javascript">
			alert('한 번에 수신번호를 10,000건 이하로 올려 주시기 바랍니다.');
			window.parent.location.reload();
		</script>
	<?
		exit;
	}
} else {
	?>
	<script language="javascript">
		alert('선택한 파일이 없습니다.');
	</script>
	<?
	exit;
}
$error_arr = array();
if ($_REQUEST['status'] == "old") {
	if (!$_FILES['excel_file']['tmp_name'])
		exit;
	$sql = "select * from Gn_MMS_Group where idx in({$_POST['old_group']})";
	$resul = mysqli_query($self_con, $sql);
	while ($row = mysqli_fetch_array($resul)) {
		$cnt = 0;
		for ($i = 2; $i <= $excel_rows; $i++) {
			//$is_zero = substr($data->sheets[0]['cells'][$i][3], 0, 1);
			//$v = $is_zero ? "0" . $data->sheets[0]['cells'][$i][3] : $data->sheets[0]['cells'][$i][3];
			$is_zero = substr($spreadData[$i][3], 0, 1);
			$v = $is_zero ? "0" . $spreadData[$i][3] : $spreadData[$i][3];
			$v = preg_replace("/[^0-9]/", "", $v);
			if (!check_cellno($v)) {
				array_push($error_arr, "{$v} 은/는 정확한 번호가 아닙니다.(업로드실패)");
				continue;
			}
			$sql_c = "select idx from Gn_MMS_Receive where mem_id='{$_SESSION['one_member_id']}' and grp_id='{$row['idx']}' and recv_num='$v' ";
			$resul_c = mysqli_query($self_con, $sql_c);
			$row_c = mysqli_fetch_array($resul_c);
			if ($row_c['idx']) {
				array_push($error_arr, "{$v} 은/는 중복번호입니다.(업로드실패)");
				continue;
			}

			$sql_i = "insert into Gn_MMS_Receive set mem_id = '{$_SESSION['one_member_id']}',grp_id='{$row['idx']}', grp = '{$row['grp']}', grp_2 = '" . $data->sheets[0]['cells'][$i][1] . "', name = '" . $data->sheets[0]['cells'][$i][2] . "' , recv_num = '$v', email = '" . $data->sheets[0]['cells'][$i][4] . "' ,reg_date=now()";
			mysqli_query($self_con, $sql_i);
			$cnt++;
		}
		$sql_u = "update Gn_MMS_Group set count=count+$cnt where idx='{$row['idx']}' ";
		mysqli_query($self_con, $sql_u);
	}
} else if ($_REQUEST['status'] == "new") {
	$group_name = htmlspecialchars($_POST['new_group']);
	$sql_s = "select idx from Gn_MMS_Group where grp='$group_name' and mem_id='{$_SESSION['one_member_id']}'";
	$resul_s = mysqli_query($self_con, $sql_s);
	$row_s = mysqli_fetch_array($resul_s);
	if ($row_s['idx']) {
	?>
		<script language="javascript">
			alert('해당 그룹명은 이미 존재합니다.\n\n다른 그룹명으로 사용해주세요.');
			window.parent.location.reload();
		</script>
<?
		exit;
	}
	$sql = "insert Gn_MMS_Group set mem_id = '{$_SESSION['one_member_id']}', grp = '$group_name', reg_date = now()";
	mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

	if ($_FILES['excel_file']['tmp_name']) {
		$sql_s = "select idx from Gn_MMS_Group where grp='$group_name' and mem_id='{$_SESSION['one_member_id']}' ";
		$resul_s = mysqli_query($self_con, $sql_s);
		$row_s = mysqli_fetch_array($resul_s);
		$cnt = 0;
		for ($i = 2; $i <= $excel_rows; $i++) {
			$is_zero = substr($spreadData[$i][3], 0, 1);
			$v = $is_zero ? "0" . $spreadData[$i][3] : $spreadData[$i][3];
			$v = preg_replace("/[^0-9]/", "", $v);
			if (!check_cellno($v)) {
				array_push($error_arr, "{$v} 은/는 정확한 번호가 아닙니다.(업로드실패)");
				continue;
			}

			$sql_c = "select idx from Gn_MMS_Receive where mem_id='{$_SESSION['one_member_id']}' and grp_id='{$row_s['idx']}' and recv_num='$v' ";
			$resul_c = mysqli_query($self_con, $sql_c);
			$row_c = mysqli_fetch_array($resul_c);
			if ($row_c['idx']) {
				array_push($error_arr, "{$v} 은/는 중복번호입니다.(업로드실패)");
				continue;
			}

			$sql_i = "insert into Gn_MMS_Receive set mem_id = '{$_SESSION['one_member_id']}',grp_id='{$row_s['idx']}', grp = '$group_name', grp_2 = '" . $data->sheets[0]['cells'][$i][1] . "', recv_num = '$v', name = '" . $data->sheets[0]['cells'][$i][2] . "' ,email = '" . $data->sheets[0]['cells'][$i][4] . "' ,reg_date=now() ";
			mysqli_query($self_con, $sql_i);
			$cnt++;
		}
		$sql_u = "update Gn_MMS_Group set count=$cnt where idx='{$row_s['idx']}' ";
		mysqli_query($self_con, $sql_u);
	}
} else if ($_REQUEST['status'] == "deny") {
	if (!$_FILES['excel_file']['tmp_name'])
		exit;
	$cnt = 0;
	for ($i = 2; $i <= $excel_rows; $i++) {
		$send_num = preg_replace("/[^0-9]/", "", $spreadData[$i][1]);
		$is_zero = substr($send_num, 0, 1);
		$send_num = $is_zero ? "0" . $send_num : $send_num;

		if (!check_cellno($send_num)) {
			array_push($error_arr, "{$send_num} 은/는 정확한 번호가 아닙니다.(업로드실패)");
			continue;
		}

		$recv_num = preg_replace("/[^0-9]/", "", $spreadData[$i][2]);
		$is_zero = substr($recv_num, 0, 1);
		$recv_num = $is_zero ? "0" . $recv_num : $recv_num;

		if (!check_cellno($recv_num)) {
			array_push($error_arr, "{$recv_num} 은/는 정확한 번호가 아닙니다.(업로드실패)");
			continue;
		}


		$sql_num = "select sendnum from Gn_MMS_Number where mem_id ='{$_SESSION['one_member_id']}' and sendnum='$send_num' ";
		$resul_num = mysqli_query($self_con, $sql_num);
		$row_num = mysqli_fetch_array($resul_num);
		if (!$row_num['sendnum']) {
			array_push($error_arr, "{$send_num} 은/는 등록된번호가 아닙니다.(업로드실패)");
			continue;
		}

		$sql_s = "select idx from Gn_MMS_Deny where mem_id='{$_SESSION['one_member_id']}' and recv_num='$recv_num' and send_num='$send_num' ";
		$resul_s = mysqli_query($self_con, $sql_s);
		$row_s = mysqli_fetch_array($resul_s);
		if ($row_s['idx']) {
			array_push($error_arr, "발신번호{$send_num} 수신번호 {$recv_num} 은/는 이미 등록되었습니다.(업로드실패)");
			continue;
		}

		$sql_i = "insert into Gn_MMS_Deny set ";
		$deny_info['mem_id'] = $_SESSION['one_member_id'];
		$deny_info['send_num'] = $send_num;
		$deny_info['recv_num'] = $recv_num;
		$deny_info['title'] = $data->sheets[0]['cells'][$i][3];
		$deny_info['content'] = $data->sheets[0]['cells'][$i][4];
		$deny_info['status'] = "B";
		foreach ($deny_info as $key => $v)
			$sql_i .= " $key='$v' , ";
		$sql_i .= " reg_date=now() ";
		mysqli_query($self_con, $sql_i) or die(mysqli_error($self_con));
		$cnt++;
	}
}
$error_str = implode("\\n", $error_arr);
?>
<script language="javascript">
	alert("<?= $cnt ?> 행 등록완료되었습니다.\n\n<?= $error_str ?>");
	window.parent.location.reload();
</script>