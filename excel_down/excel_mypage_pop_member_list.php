<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit', '2500M');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
$excel_sql = base64_decode($_POST['excel_sql']);
$result = mysqli_query($self_con, $excel_sql) or die(mysqli_error($self_con));

$activeWorksheet
	->setCellValue("A1", "No")
	->setCellValue("B1", "고객명")
	->setCellValue("C1", "휴대폰번호")
	->setCellValue("D1", "그룹제외")
	->setCellValue("E1", "신청일자");
$h = 2;
$No = 1;
if (isset($_POST['eventid'])) {
	$event_idx = $_POST['eventid'];
}else{
	$event_idx = 0;
}
while ($row = mysqli_fetch_array($result)) {
	$sql = "select count(sms_idx) from Gn_event_sms_info where event_idx='{$event_idx}' and mobile='{$row['mobile']}'";
	$stop_res = mysqli_query($self_con, $sql);
	$stop_row = mysqli_fetch_array($stop_res);
	if ($stop_row[0] > 0)
		$stop_status = "Yes";
	else
		$stop_status = "No";
	$activeWorksheet
		->setCellValue("A$h", $No++)
		->setCellValue("B$h", $row['name'])
		->setCellValue("C$h", $row['mobile'])
		->setCellValue("D$h", $stop_status)
		->setCellValue("E$h", $row['regdate']);
	$h++;
}
$activeWorksheet->setTitle("신청고객 리스트 ");
$spreadsheet->setActiveSheetIndex(0);

$filename = "mypage_pop_member_list.xlsx";
$spreadsheet->getProperties()->setCreator('onlyone')
	->setLastModifiedBy('onlyone')
	->setTitle('Office 2007 XLSX Onlyone Document')
	->setSubject('Office 2007 XLSX Onlyone Document')
	->setDescription('Onlyone document for Office 2007 XLSX.')
	->setKeywords('office 2007 openxml php')
	->setCategory('Onlyone contact file');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=' . $filename);
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
