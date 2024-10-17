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
  if (!$_REQUEST['excel_sql'])
    $path = "../";
  $excel_sql = str_replace("`", "'", $_REQUEST['excel_sql']);

  if ($_POST['ids']) {
    $ids = $_POST['ids'];
    $pos = strpos($excel_sql, 'order by');
    $excel_sql = substr($excel_sql, 0, $pos) . ' and seq in(' . $ids . ') ' . substr($excel_sql, $pos);
  }
  //   var_dump($excel_sql);
  //   exit;

  $result = mysqli_query($self_con, $excel_sql) or die(mysqli_error($self_con));
  $sort_no = mysqli_num_rows($result);

  $activeWorksheet
    ->setCellValue("A1", "번호")
    ->setCellValue("B1", "발신번호")
    ->setCellValue("C1", "수신일시")
    ->setCellValue("D1", "문자내용")
    ->setCellValue("E1", "수신번호")
    ->setCellValue("F1", "변경된번호")
    ->setCellValue("G1", "그룹명")
    ->setCellValue("H1", "분류");
  $h = 2;
  while ($row = mysqli_fetch_array($result)) {
    $activeWorksheet
      ->setCellValue("A$h", $sort_no)
      ->setCellValue("B$h", $row['dest'])
      ->setCellValue("C$h", $row['reservation_time'])
      ->setCellValue("D$h", $row['msg_text'])
      ->setCellValue("E$h", $row['ori_num'])
      ->setCellValue("F$h", $row['chg_num'])
      ->setCellValue("G$h", $row['grp_name'])
      ->setCellValue("H$h", $msg_flag_arr[$row['msg_flag']]);
    $h++;
    $sort_no--;
  }
  $activeWorksheet->setTitle("원마케팅문자 로그기록");
  $spreadsheet->setActiveSheetIndex(0);

  $filename = "onemarket_log.xlsx";
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
}
?>
