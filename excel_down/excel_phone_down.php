<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit', '4500M');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
if (strlen($_SESSION['one_member_id']) > 0) {
	$path = "../";
	$excel_sql = $_POST['excel_sql'];
	$excel_sql = str_replace("`", "'", $excel_sql);
	$result = mysqli_query($self_con, $excel_sql) or die(mysqli_error($self_con));
	$totalCnt = mysqli_num_rows($result);
	$number			= $totalCnt;

	$activeWorksheet
		->setCellValue("A1", "번호")
		->setCellValue("B1", "아이디")
		->setCellValue("C1", "소유자명")
		->setCellValue("D1", "전화번호")
		->setCellValue("E1", "소그룹명")
		->setCellValue("F1", "고객이름")
		->setCellValue("G1", "전화번호")
		->setCellValue("H1", "등록일");
	$h = 2;
	while ($row = mysqli_fetch_array($result)) {
		$sql = "select mem_id, mem_name from Gn_Member where mem_id='{$row['mem_id']}'";
		$sresul = mysqli_query($self_con, $sql);
		$srow = mysqli_fetch_array($sresul);
		if ($srow['mem_id'] == "") {
			$sql = "select m.mem_id, mem_name from Gn_Member m inner join Gn_MMS_Number n on m.mem_id=n.mem_id where n.sendnum='{$row['dest']}'";
			$sresul = mysqli_query($self_con, $sql);
			$srow = mysqli_fetch_array($sresul);
		}

		$activeWorksheet
			->setCellValue("A$h", $number--)
			->setCellValue("B$h", $srow['mem_id'])
			->setCellValue("C$h", $srow['mem_name'])
			->setCellValue("D$h", $row['dest'])
			->setCellValue("E$h", $row['grp'])
			->setCellValue("F$h", $row['msg_text'])
			->setCellValue("G$h", $row['msg_url'])
			->setCellValue("H$h", $row['reservation_time']);
		$h++;
	}
	$activeWorksheet->setTitle("통합주소록");
	$spreadsheet->setActiveSheetIndex(0);

	$filename = "통합주소록.xlsx";
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
