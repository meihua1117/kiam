<?
if (!$_REQUEST['box_text'])
	exit;
$path = "../";
set_time_limit(0);
ini_set('memory_limit', '2500M');
include_once $path . "lib/rlatjd_fun.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$box_s = str_replace("`", "'", $_REQUEST['box_text']);
$sql_db = "select seq,dest,callback,msg_flag,msg_text,msg_url,reservation_time,grp from sm_data where dest in ($box_s) ";
$resul_db = mysqli_query($self_con, $sql_db);
$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet
	->setCellValue("A1", "seq")
	->setCellValue("B1", "dest")
	->setCellValue("C1", "callback")
	->setCellValue("D1", "msg_flag")
	->setCellValue("E1", "msg_text")
	->setCellValue("F1", "msg_url")
	->setCellValue("G1", "reservation_time")
	->setCellValue("H1", "grp");
$h = 2;
while ($row_db = mysqli_fetch_array($resul_db)) {
	$activeWorksheet
		->setCellValue("A$h", $row_db['seq'])
		->setCellValue("B$h", $row_db['dest'])
		->setCellValue("C$h", $row_db['callback'])
		->setCellValue("D$h", $row_db['msg_flag'])
		->setCellValue("E$h", $row_db['msg_text'])
		->setCellValue("F$h", $row['msg_url'])
		->setCellValue("G$h", $row['reservation_time'])
		->setCellValue("H$h", $row['grp']);
	$h++;
}
$msg = "등록된 번호";
$msg2 = "Number";
$activeWorksheet->setTitle("원마케팅문자 " . $msg);
$spreadsheet->setActiveSheetIndex(0);

$filename = "onemarket_" . $msg2 . ".xlsx";
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
