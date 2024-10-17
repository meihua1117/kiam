<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit', '2500M');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
$excel_sql = $_POST['excel_sql'];
$excel_sql = str_replace("`", "'", $excel_sql);
$result = mysqli_query($self_con, $excel_sql) or die(mysqli_error($self_con));

$activeWorksheet
	->setCellValue("A1", "No")
	->setCellValue("B1", "신청창키워드")
	->setCellValue("C1", "신청내용")
	->setCellValue("D1", "링크")
	->setCellValue("E1", "조회수")
	->setCellValue("F1", "신청채널")
	->setCellValue("G1", "발송폰번호")
	->setCellValue("H1", "등록일");
$h = 2;
$No = 1;
while ($row = mysqli_fetch_array($result)) {
	$activeWorksheet
		->setCellValue("A$h", $No++)
		->setCellValue("B$h", $row['event_name_eng'])
		->setCellValue("C$h", $row['event_name_kor'])
		->setCellValue("D$h", $row['short_url'])
		->setCellValue("E$h", $row['read_cnt'])
		->setCellValue("F$h", $row['pcode'])
		->setCellValue("G$h", $row['mobile'])
		->setCellValue("H$h", $row['regdate']);
	$h++;
}
$activeWorksheet->setTitle("고객신청 리스트 ");
$spreadsheet->setActiveSheetIndex(0);

$filename = "mypage_link_list.xlsx";
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
?>