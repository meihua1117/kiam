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
		->setCellValue("D1", "소속")
		->setCellValue("E1", "IAM소속")
		->setCellValue("F1", "발신번호")
		->setCellValue("G1", "소유자명")
		->setCellValue("H1", "발신내용")
		->setCellValue("I1", "발신시간")
		->setCellValue("J1", "수신번호")
		->setCellValue("K1", "발송건수")
		->setCellValue("L1", "회신수");
	$h = 2;
	while ($row = mysqli_fetch_array($result)) {
		$sql_n = "select memo from Gn_MMS_Number where sendnum='{$row['send_num']}' ";
		$resul_n = mysqli_query($self_con, $sql_n);
		$row_n = mysqli_fetch_array($resul_n);

		$sql_cs = "select count(idx) as cnt from Gn_MMS_status where idx='{$row['idx']}' and status='0'";
		$resul_cs = mysqli_query($self_con, $sql_cs);
		$row_cs = mysqli_fetch_array($resul_cs);
		$success_cnt = $row_cs[0];

		$sql_s = "select * from Gn_MMS_status where idx='{$row['idx']}' ";
		$resul_s = mysqli_query($self_con, $sql_s);
		$row_s = mysqli_fetch_array($resul_s);

		$sql_sn = "select * from Gn_MMS where idx='{$row['idx']}' ";
		$resul_sn = mysqli_query($self_con, $sql_sn);
		$row_sn = mysqli_fetch_array($resul_sn);
		$recv_cnt = explode(",", $row_sn['recv_num']);
		$total_cnt = count($recv_cnt);

		$sql = "select count(seq) as cnt from call_app_log where api_name='receive_sms' and LENGTH(recv_num) >= 10 and  send_num='{$row['send_num']}' and recv_num in ($recv_num_in) and recv_num like '01%'  and regdate >= '$date' and sms not like '[%'";
		$kresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
		$krow = mysqli_fetch_array($kresult);
		$intRowCount = $krow['cnt'];

		$reg_date_1hour = strtotime("{$row['reg_date']} +1hours");

		$status = "";
		if ($row['reservation']) {
			$status = "예약";
		}
		if ($success_cnt == 0) {
			if (time() > $reg_date_1hour && $row['up_date'] == "") {
				if ($row['reservation'] > date("Y-m-d H:i:s")) {
				} else {
					$status .= "실패";
				}
			} else {
				if (time() > $reg_date_1hour && $row_s['up_date'] == "") {
					$status .= "발송실패";
				} else {
					$status .= "발송중";
				}
			}
		} else {
			$status = $success_cnt . "/" . ($total_cnt - $success_cnt);
		}
		$activeWorksheet
			->setCellValue("A$h", $number--)
			->setCellValue("B$h", $row['mem_id'])
			->setCellValue("C$h", $row['mem_name'])
			->setCellValue("D$h", $row['site'])
			->setCellValue("E$h", $row['site_iam'])
			->setCellValue("F$h", $row['send_num'])
			->setCellValue("G$h", $row_n['memo'])
			->setCellValue("H$h", $row['content'])
			->setCellValue("I$h", substr($row['reg_date'], 0, 16))
			->setCellValue("J$h", $row['recv_num'])
			->setCellValue("K$h", $status)
			->setCellValue("L$h", $intRowCount);
		$h++;
	}
	$activeWorksheet->setTitle("메시지수발신내역");
	$spreadsheet->setActiveSheetIndex(0);

	$filename = "onemarket_msg.xlsx";
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