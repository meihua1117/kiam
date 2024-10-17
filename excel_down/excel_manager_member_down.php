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
   $date_today = date("Y-m-d");
   $mem_id = $_REQUEST['mem_id'];
   $excel_sql = "select * from Gn_Member gm left join tjd_pay_result p on p.buyer_id = gm.mem_id 
                	      where recommend_id = '$mem_id' and end_status='Y' order by end_date desc";
   $result = mysqli_query($self_con, $excel_sql) or die(mysqli_error($self_con));
   $totalCnt = mysqli_num_rows($result);
   $number         = $totalCnt;

   $activeWorksheet
      ->setCellValue("A1", "번호")
      ->setCellValue("B1", "아이디")
      ->setCellValue("C1", "이름")
      ->setCellValue("D1", "예약시작일")
      ->setCellValue("E1", "종료일")
      ->setCellValue("F1", "지불수단");
   $h = 2;
   while ($row = mysqli_fetch_array($result)) {
      // 부가서비스 이용 여부 확인
      // tjd_pay_result.fujia_status
      $add_opt = $pay_type[$row['payMethod']];
      $activeWorksheet
         ->setCellValue("A$h", $number--)
         ->setCellValue("B$h", $row['mem_id'])
         ->setCellValue("C$h", $row['mem_name'])
         ->setCellValue("D$h", $row['date'])
         ->setCellValue("E$h", $row['end_date'])
         ->setCellValue("F$h", $add_opt);
      $h++;
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