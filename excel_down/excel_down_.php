<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit', '2500M');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
if (strlen($_REQUEST['one_member_id']) > 0) {
	if (!$_REQUEST['down_type'])
		exit;
	$path = "../";
	if ($_REQUEST['down_type'] == 1) {
		//$sql_serch=" mem_id ='{$_REQUEST['one_member_id']}' and grp_id = '{$_REQUEST['grp_id']}'";	
		$sql_serch = " mem_id ='{$_REQUEST['one_member_id']}' ";
		$sql = "select recv_num,grp_2,name,recv_num from Gn_MMS_Receive where $sql_serch ";
		$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
		$activeWorksheet
			->setCellValue("A1", "소그룹명")
			->setCellValue("B1", "이름")
			->setCellValue("C1", "전화번호")
			->setCellValue("D1", "상태");
		$h = 2;
		while ($row = mysqli_fetch_array($result)) {
			$is_zelo = substr($row['recv_num'], 0, 1);
			$v = $is_zelo ? "0" . $row['recv_num'] : $row['recv_num'];
			$status_arr = array();
			$sql_deny = "select idx,recv_num from Gn_MMS_Deny where recv_num='$v' and mem_id='{$_REQUEST['one_member_id']}' ";
			$resul_deny = mysqli_query($self_con, $sql_deny);
			$row_deny = mysqli_fetch_array($resul_deny);
			if ($row_deny['idx'])
				array_push($status_arr, "수신거부");

			//$sql_etc="select seq,dest,msg_flag from sm_log where ori_num='$v' order by seq desc limit 0,1 ";
			$sql_etc = "select seq,dest,msg_flag from sm_log where ori_num='$v' and mem_id='{$_REQUEST['one_member_id']}' order by seq desc limit 0,1 ";


			$resul_etc = mysqli_query($self_con, $sql_etc);
			$row_etc = mysqli_fetch_array($resul_etc);
			if ($row_etc['seq']) {
				if ($row_etc['msg_flag'] == 1)
					array_push($status_arr, "번호변경"); //번호변경
				if ($row_etc['msg_flag'] == 2)
					array_push($status_arr, "없는번호"); //없는번호
				if ($row_etc['msg_flag'] == 3)
					array_push($status_arr, "수신불가"); //수신불가			
			}
			$status_s = implode(",", $status_arr);
			$activeWorksheet
				->setCellValue("A$h", $row['grp_2'])
				->setCellValue("B$h", $row['name'])
				->setCellValue("C$h", $row['recv_num'])
				->setCellValue("D$h", $status_s);
			$h++;
		}

		mysqli_free_result($result);

		$activeWorksheet->setTitle("원마케팅문자 그룹전화번호");
		$spreadsheet->setActiveSheetIndex(0);
		$filename = "onemarket_group_" . iconv("utf8", "euckr", $_REQUEST['grp_id']) . ".xls";
	} else if ($_REQUEST['down_type'] == 2) {
		$activeWorksheet
			->setCellValue("A1", "소그룹명")
			->setCellValue("B1", "이름")
			->setCellValue("C1", "전화번호");
		$activeWorksheet->setTitle("원마케팅문자 그룹전화번호 등록샘플");
		$spreadsheet->setActiveSheetIndex(0);
		$filename = "onemarket_group_sample.xls";
	}

	$spreadsheet->getProperties()->setCreator('onlyone')
		->setLastModifiedBy('onlyone')
		->setTitle('Office 2007 XLSX Onlyone Document')
		->setSubject('Office 2007 XLSX Onlyone Document')
		->setDescription('Onlyone document for Office 2007 XLSX.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Onlyone contact file');
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
	header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header('Pragma: public'); // HTTP/1.0

	$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');
	exit;
}
?>
