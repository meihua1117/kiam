<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit', '2500M');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

if (strlen($_SESSION['one_member_id']) > 0) {
	if (!$_REQUEST['excel_sql'] && !$_REQUEST['down_type'])
		exit;
	$path = "../";
	$spreadsheet = new Spreadsheet();
	$activeWorksheet = $spreadsheet->getActiveSheet();
	if ($_REQUEST['down_type'] == 1) {
		$excel_sql = str_replace("`", "'", $_REQUEST['excel_sql']);
		$result = mysqli_query($self_con, $excel_sql) or die(mysqli_error($self_con));
		$sort_no = mysqli_num_rows($result);
		$activeWorksheet->setCellValue("A1", "번호")
			->setCellValue("B1", "발신번호")
			->setCellValue("C1", "수신번호")
			->setCellValue("D1", "문자제목")
			->setCellValue("E1", "문자내용")
			->setCellValue("F1", "첨부파일")
			->setCellValue("G1", "등록경로")
			->setCellValue("H1", "등록일");
		$h = 2;
		while ($row = mysqli_fetch_array($result)) {
			$activeWorksheet->setCellValue("A$h", $sort_no)
				->setCellValue("B$h", $row['send_num'])
				->setCellValue("C$h", $row['recv_num'])
				->setCellValue("D$h", $row['title'])
				->setCellValue("E$h", $row['content'])
				->setCellValue("F$h", $row['jpg'])
				->setCellValue("G$h", $row['status'])
				->setCellValue("H$h", $row['reg_date']);
			$h++;
			$sort_no--;
		}
		$activeWorksheet->setTitle("원마케팅문자 수신동의전화번호");
		$spreadsheet->setActiveSheetIndex(0);
		$filename = "onemarket_agree.xls";
	} else if ($_REQUEST['down_type'] == 2) {
		$activeWorksheet->setCellValue("A1", "발신번호")
			->setCellValue("B1", "수신번호")
			->setCellValue("C1", "문자제목")
			->setCellValue("D1", "문자내용");
		$activeWorksheet->setTitle("원마케팅문자 수신동의등록 샘플");
		$spreadsheet->setActiveSheetIndex(0);
		$filename = "onemarket_deny_sample.xls";
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
