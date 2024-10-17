<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit', '4500M');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
//$writer = new Xlsx($spreadsheet);
//$writer->save('hello world.xlsx');
//exit;
if (strlen($_SESSION['one_member_id']) > 0) {
	if (!$_REQUEST['grp_id'] && !$_REQUEST['down_type'])
		exit;
	$path = "../";
	//include_once $path . "lib/rlatjd_fun.php";
	//require_once("Classes/PHPExcel.php");
	/*$objPHPExcel = new PHPExcel();
	$objPHPExcel->getProperties()
		->setCreator("excel")
		->setLastModifiedBy("excel")
		->setTitle("Office 2007 XLSX Test Document")
		->setSubject("Office 2007 XLSX Test Document")
		->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		->setKeywords("office 2007 openxml php")
		->setCategory("excel file");
	*/
	if ($_REQUEST['down_type'] == 1) {
		$sql_serch = " mem_id ='{$_SESSION['one_member_id']}' and grp_id = '{$_REQUEST['grp_id']}'";
		$sql = "select recv_num,grp_2,name,recv_num,email from Gn_MMS_Receive where $sql_serch order by idx asc";
		$result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
		$activeWorksheet->setCellValue("A1", "소그룹명")
			->setCellValue("B1", "이름")
			->setCellValue("C1", "전화번호")
			->setCellValue("D1", "이메일")
			->setCellValue("E1", "상태");
		$h = 2;
		while ($row = mysqli_fetch_array($result)) {
			$is_zelo = substr($row['recv_num'], 0, 1);
			$v = $is_zelo ? "0" . $row['recv_num'] : $row['recv_num'];
			$status_arr = array();
			$sql_deny = "select idx,recv_num from Gn_MMS_Deny where recv_num='$v' and mem_id='{$_SESSION['one_member_id']}' ";
			$resul_deny = mysqli_query($self_con, $sql_deny);
			$row_deny = mysqli_fetch_array($resul_deny);
			if ($row_deny['idx'])
				array_push($status_arr, "수신거부");

			//$sql_etc="select seq,dest,msg_flag from sm_log where ori_num='$v' order by seq desc limit 0,1 ";
			$sql_etc = "select seq,dest,msg_flag from sm_log where ori_num='$v' and mem_id='{$_SESSION['one_member_id']}' order by seq desc limit 0,1 ";


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
			$activeWorksheet->setCellValue("A$h", $row['grp_2'])
				->setCellValue("B$h", $row['name'])
				->setCellValue("C$h", $row['recv_num'])
				->setCellValue("D$h", $row['email'])
				->setCellValue("E$h", $status_s);
			$h++;
		}

		mysqli_free_result($result);

		$activeWorksheet->setTitle("원마케팅문자 그룹전화번호");
		$spreadsheet->setActiveSheetIndex(0);
		$filename = "onemarket_group_" . iconv("utf8", "euckr", $_REQUEST['grp_id']) . ".xls";
	} else if ($_REQUEST['down_type'] == 2) {
		$activeWorksheet->setCellValue("A1", "소그룹명")
			->setCellValue("B1", "이름")
			->setCellValue("C1", "전화번호")
			->setCellValue("D1", "이메일");
		$activeWorksheet->setTitle("원마케팅문자 그룹전화번호 등록샘플");
		$spreadsheet->setActiveSheetIndex(0);
		$filename = "onemarket_group_sample.xls";
	}
	$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		->setLastModifiedBy('Maarten Balliauw')
		->setTitle('Office 2007 XLSX Test Document')
		->setSubject('Office 2007 XLSX Test Document')
		->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Test result file');
	/*header('Content-type: application/vnd.ms-excel; charset=utf-8');
	header("Content-Disposition: attachment; filename=$filename");
	header('Cache-Control: max-age=0');
	header('Cache-Control: max-age=1');
	header('Content-Description: PHP4 Generated Data');
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header('Pragma: public');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');*/
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="01simple.xlsx"');
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
