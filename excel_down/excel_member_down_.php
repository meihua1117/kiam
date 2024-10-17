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
		->setCellValue("B1", "아이디")
		->setCellValue("C1", "이름")
		->setCellValue("D1", "전화번호")
		->setCellValue("E1", "구분")
		->setCellValue("F1", "기부폰")
		->setCellValue("G1", "유료건수")
		->setCellValue("H1", "부가기능")
		->setCellValue("I1", "총결제")
		->setCellValue("J1", "접속일")
		->setCellValue("K1", "탈퇴회원")
		->setCellValue("L1", "소속")
		->setCellValue("M1", "추천인");
	$h = 2;
	while ($row = mysqli_fetch_array($result)) {
		// =====================  유료결제건 시작 ===================== 
		$sql = "select phone_cnt from tjd_pay_result where buyer_id = '" . $row['mem_id'] . "' and end_date > '$date_today' order by end_date desc limit 1";
		$res_result = mysqli_query($self_con, $sql);
		$buyPhoneCnt = mysqli_fetch_row($res_result);
		mysqli_free_result($res_result);

		if ($buyPhoneCnt == 0) {
			$buyMMSCount = 0;
		} else {
			$buyMMSCount = $buyPhoneCnt[0];
		}
		// ===================== 유료결제건 끝 ===================== 

		// =====================  총결제금액 시작 ===================== 
		$sql = "select sum(TotPrice) totPrice from tjd_pay_result where buyer_id = '" . $row['mem_id'] . "'";
		$res_result = mysqli_query($self_con, $sql);
		$totPriceRow = mysqli_fetch_row($res_result);
		mysqli_free_result($res_result);

		$totPrice = $totPriceRow[0];
		// ===================== 총결제금액 끝 =====================                     	

		// 부가서비스 이용 여부 확인
		// tjd_pay_result.fujia_status
		if ($row['fujia_date2'] >= date("Y-m-d H:i:s")) {
			$add_opt = "사용";
		} else {
			$add_opt = "미사용";
		}


		$activeWorksheet
			->setCellValue("A$h", $number--)
			->setCellValue("B$h", $row['mem_id'])
			->setCellValue("C$h", $row['mem_name'])
			->setCellValue("D$h", str_replace("-", "", $row['mem_phone']) == $row['sendnum'] || $row['sendnum'] == "" ? str_replace("-", "", $row['mem_phone']) : $row['sendnum'])
			->setCellValue("E$h", (str_replace("-", "", $row['mem_phone']) == str_replace("-", "", $row['sendnum']) && $row['sendnum'] != "") ? "앱폰" : "가입폰")
			->setCellValue("F$h", number_format($row['tcnt']))
			->setCellValue("G$h", number_format($buyMMSCount))
			->setCellValue("H$h", $add_opt)
			->setCellValue("I$h", number_format($totPrice))
			->setCellValue("J$h", $row['login_date'])
			->setCellValue("K$h", $row['is_leave'] == 'Y' ? "탈퇴회원" : "가입회원")
			->setCellValue("L$h", $row['site'])
			->setCellValue("M$h", $row['recommend_id']);
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