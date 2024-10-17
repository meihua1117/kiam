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
  $sql_serch = " 1=1 ";
  $sql_serch .= " and idx ='{$_REQUEST['idx']}' ";
  if ($_REQUEST['serch_fs_text'] != "") {
    $sql_serch .= " and recv_num like '%{$_REQUEST['serch_fs_text']}%' ";
  }
  if ($_REQUEST['search_fs_status'] != "") {
    $sql_serch .= " and status='$_REQUEST[search_fs_status]' ";
  }

  $sql_table = " Gn_MMS_status ";

  $excel_sql = "select * from $sql_table where $sql_serch ";
  $result = mysqli_query($self_con, $excel_sql) or die(mysqli_error($self_con));
  $sort_no = mysqli_num_rows($result);

  $activeWorksheet
    ->setCellValue("A1", "번호")
    ->setCellValue("B1", "발신번호")
    ->setCellValue("C1", "수신번호")
    ->setCellValue("D1", "전송일시")
    ->setCellValue("E1", "발송여부");
  $h = 2;
  while ($row = mysqli_fetch_array($result)) {
    $activeWorksheet
      ->setCellValue("A$h", $sort_no)
      ->setCellValue("B$h", $row['send_num'])
      ->setCellValue("C$h", $row['recv_num'])
      ->setCellValue("D$h", $row['regdate'])
      ->setCellValue("E$h", ($row['status'] == "0" ? "성공" : "실패"));

    $h++;
    $sort_no--;
  }
  $msg = $_REQUEST['status'] == 1 ? "발신내역" : "수신내역";
  $msg2 = $_REQUEST['status'] == 1 ? "send" : "recv";
  $activeWorksheet->setTitle("원마케팅문자 " . $msg);
  $spreadsheet->setActiveSheetIndex(0);

  $filename = "onemarket_" . $msg2 . ".xls";
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
