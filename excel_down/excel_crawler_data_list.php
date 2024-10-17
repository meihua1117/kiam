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
	$excel_sql = str_replace("\'", "'", $_POST["excel_sql"]);
	$result = mysqli_query($self_con, $excel_sql) or die(mysqli_error($self_con));
	$totalCnt = mysqli_num_rows($result);
	$number			= $totalCnt;
	$activeWorksheet
		->setCellValue("A1", "번호")
		->setCellValue("B1", "회원아이디")
		->setCellValue("C1", "검색어")
		->setCellValue("D1", "웹종류")
		->setCellValue("E1", "폰번호")
		->setCellValue("F1", "이메일")
		->setCellValue("G1", "대표자")
		->setCellValue("H1", "상호")
		->setCellValue("I1", "업종")
		->setCellValue("J1", "주소")
		->setCellValue("K1", "웹주소")
		->setCellValue("L1", "수집일");
	$h = 2;
	$phonelist = array();
	$maillist = array();
	while ($row = mysqli_fetch_array($result)) {
		$number--;
		if ($row['cell'] == "") {
			if ($row['email'] != "" && array_search($row['email'], $maillist) === false) {

				array_push($maillist, $row['email']);
				$activeWorksheet
					->setCellValue("A$h", $number)
					->setCellValue("B$h", $row['user_id'])
					->setCellValue("C$h", $row['keyword'])
					->setCellValue("D$h", $row['data_type'])
					->setCellValue("E$h", $row['cell'])
					->setCellValue("F$h", $row['email'])
					->setCellValue("G$h", $row['ceo'])
					->setCellValue("H$h", $row['company_name'])
					->setCellValue("I$h", $row['company_type'])
					->setCellValue("J$h", strip_tags($row['address']))
					->setCellValue("K$h", strip_tags($row['url']))
					->setCellValue("L$h", $row['regdate']);
				$h++;
			}
		} else if (array_search($row['cell'], $phonelist) === false) {
			if ($row['email'] != "" && array_search($row['email'], $maillist) === false) {
				array_push($maillist, $row['email']);
			}
			array_push($phonelist, $row['cell']);
			$activeWorksheet
				->setCellValue("A$h", $number)
				->setCellValue("B$h", $row['user_id'])
				->setCellValue("C$h", $row['keyword'])
				->setCellValue("D$h", $row['data_type'])
				->setCellValue("E$h", $row['cell'])
				->setCellValue("F$h", $row['email'])
				->setCellValue("G$h", $row['ceo'])
				->setCellValue("H$h", $row['company_name'])
				->setCellValue("I$h", $row['company_type'])
				->setCellValue("J$h", strip_tags($row['address']))
				->setCellValue("K$h", strip_tags($row['url']))
				->setCellValue("L$h", $row['regdate']);
			$h++;
		}
	}

	$activeWorksheet->setTitle("디비수집리스트");
	$spreadsheet->setActiveSheetIndex(0);

	$filename = "crawler_data_list.xlsx";
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
