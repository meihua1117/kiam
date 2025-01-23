<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit', '2500M');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
$request_ids = $_POST['box_text'];
if($request_ids == ''){
	$excel_sql = $_POST['excel_sql'];
	$excel_sql = str_replace("`", "'", $excel_sql);
}
else{

	$excel_sql = "SELECT * FROM Gn_event_request WHERE request_idx IN ({$request_ids})";
}
$result = mysqli_query($self_con, $excel_sql) or die(mysqli_error($self_con));

$activeWorksheet
	->setCellValue("A1", "No")
	->setCellValue("B1", "신청창키워드")
	->setCellValue("C1", "신청자이름")
	->setCellValue("D1", "성별")
	->setCellValue("E1", "휴대폰번호")
	->setCellValue("F1", "주소")
	->setCellValue("G1", "이메일")
	->setCellValue("H1", "생년월일")
	->setCellValue("I1", "직업")
	->setCellValue("J1", "신청제목")
	->setCellValue("K1", "기타정보")
	->setCellValue("L1", "회원가입")
	->setCellValue("M1", "등록일");
$h = 2;
$No = 1;
while ($row = mysqli_fetch_array($result)) {
	$sql_event_data = "select event_title from Gn_event where event_idx={$row['event_idx']}";
	$res_event_data = mysqli_query($self_con, $sql_event_data);
	$row_event_data = mysqli_fetch_array($res_event_data);
	$activeWorksheet
		->setCellValue("A$h", $No++)
		->setCellValue("B$h", $row['sp'])
		->setCellValue("C$h", $row['name'])
		->setCellValue("D$h", $row['sex'])
		->setCellValue("E$h", $row['mobile'])
		->setCellValue("F$h", $row['addr'])
		->setCellValue("G$h", $row['email'])
		->setCellValue("H$h", $row['birthday'])
		->setCellValue("I$h", $row['job'])
		->setCellValue("J$h", $row_event_data['event_title'])
		->setCellValue("K$h", $row['other'])
		->setCellValue("L$h", $row['join_yn'])
		->setCellValue("M$h", $row['regdate']);
	$h++;
}
$activeWorksheet->setTitle("신청고객 리스트 ");
$spreadsheet->setActiveSheetIndex(0);

$filename = "mypage_request_list.xlsx";
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
