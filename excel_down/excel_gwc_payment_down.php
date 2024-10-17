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
	$excel_sql = $_POST['excel_sql'];
	$excel_sql = str_replace("`", "'", $excel_sql);
	$result = mysqli_query($self_con, $excel_sql) or die(mysqli_error($self_con));
	$totalCnt = mysqli_num_rows($result);
	$number			= $totalCnt;
	$activeWorksheet
		->setCellValue("A1", "번호")
		->setCellValue("B1", "주문일시")
		->setCellValue("C1", "주문번호")
		->setCellValue("D1", "주문상품")
		->setCellValue("AC1", "상품코드")
		->setCellValue("E1", "공급사")
		->setCellValue("F1", "유통사")
		->setCellValue("G1", "판매자명")
		->setCellValue("H1", "판매자ID")
		->setCellValue("I1", "추천인명")
		->setCellValue("J1", "추천인ID")
		->setCellValue("K1", "구매자명")
		->setCellValue("L1", "구매자ID")
		->setCellValue("M1", "최저가")
		->setCellValue("N1", "공급가")
		->setCellValue("AD1", "옵션정보")
		->setCellValue("O1", "상품건수")
		->setCellValue("P1", "구매금액")
		->setCellValue("Q1", "배송비")
		->setCellValue("R1", "이용포인트")
		->setCellValue("S1", "무통장입금")
		->setCellValue("S1", "카드결제")
		->setCellValue("T1", "총결제액")
		->setCellValue("U1", "결제상태")
		->setCellValue("V1", "당월건수")
		->setCellValue("W1", "누적건수")
		->setCellValue("X1", "금월금액")
		->setCellValue("Y1", "누적금액")
		->setCellValue("Z1", "배송회사")
		->setCellValue("AA1", "운송장번호")
		->setCellValue("AB1", "주문변겅");
	$h = 2;
	while ($row = mysqli_fetch_array($result)) {
		$sql_mem = "select recommend_id from Gn_Member where mem_id='{$row['buyer_id']}'";
		$res_mem = mysqli_query($self_con, $sql_mem);
		$row_mem = mysqli_fetch_array($res_mem);
		$recommend_id = $row_mem['recommend_id'];

		$sql_order = "select * from Gn_Gwc_Order where tjd_idx='{$row['no']}'";
		$res_order = mysqli_query($self_con, $sql_order);
		$row_order = mysqli_fetch_array($res_order);

		if ($row['yutong_name'] == 1) {
			$gong = "";
			$yt_name = "웹빙몰";
		} else {
			$gong = $row['provider_name'];
			$yt_name = "온리원";
		}
		$sql_seller = "select mem_name from Gn_Member where mem_id='{$row['seller_id']}'";
		$res_seller = mysqli_query($self_con, $sql_seller);
		$row_seller = mysqli_fetch_array($res_seller);
		$seller_data = $row_seller[0] . "/\n" . $row['seller_id'];

		$sql_recommend = "select mem_name from Gn_Member where mem_id='{$recommend_id}'";
		$res_recommend = mysqli_query($self_con, $sql_recommend);
		$row_recommend = mysqli_fetch_array($res_recommend);
		$recommend_data = $row_recommend[0] . "/\n" . $recommend_id;

		$sql_cont_data = "select idx, contents_sell_price, send_provide_price, send_salary_price, contents_img, product_code from Gn_Iam_Contents_Gwc where idx='{$row_order['contents_idx']}'";
		$res_cont_data = mysqli_query($self_con, $sql_cont_data);
		$row_cont_data = mysqli_fetch_array($res_cont_data);
		$price_data = $row_cont_data['contents_sell_price'] . "/\n" . $row_cont_data['send_provide_price'];

		$price_data1 = ($row_cont_data['contents_sell_price'] * 1) * ($row_order['contents_cnt'] * 1) . "/\n" . $row_cont_data['send_salary_price'];

		if (strpos($row_cont_data['contents_img'], ",") !== false) {
			$img_link1 = explode(",", $row_cont_data['contents_img']);
			$img_link = trim($img_link1[0]);
		} else {
			$img_link = $row_cont_data['contents_img'];
		}

		$sql_order_point = "select use_point from Gn_Gwc_Order where tjd_idx='{$row['no']}'";
		$res_order_point = mysqli_query($self_con, $sql_order_point);
		$row_order_point = mysqli_fetch_array($res_order_point);

		$price_data2 = $row_order_point[0] . "/\n" . ($row['TotPrice'] * 1 - $row_order_point[0] * 1);

		if ($row['payMethod'] == "BANK" || $row['payMethod'] == "BANKPOINT") {
			$card_price = 0;
			$bank_price = $row['TotPrice'] * 1 - $row_order_point[0] * 1;
		} else {
			$bank_price = 0;
			$card_price = $row['TotPrice'] * 1 - $row_order_point[0] * 1;
		}

		$sql_item_cnt_month = "select SUM(contents_cnt) as month_cnt from Gn_Gwc_Order where contents_idx='{$row_cont_data['idx']}' and reg_date > '{$date_today}' and page_type=0";
		$res_item_cnt_month = mysqli_query($self_con, $sql_item_cnt_month);
		$row_item_cnt_month = mysqli_fetch_array($res_item_cnt_month);

		$sql_item_cnt_all = "select SUM(contents_cnt) as all_cnt from Gn_Gwc_Order where contents_idx='{$row_cont_data['idx']}' and page_type=0";
		$res_item_cnt_all = mysqli_query($self_con, $sql_item_cnt_all);
		$row_item_cnt_all = mysqli_fetch_array($res_item_cnt_all);

		$month_cnt = $row_item_cnt_month[0] ? $row_item_cnt_month[0] : "0";
		$cnt_data = $month_cnt . "/\n" . $row_item_cnt_all[0];

		$sql_price_month = "select SUM(TotPrice) as month_price from tjd_pay_result where gwc_cont_pay=1 and date > '{$date_today}' and buyer_id='{$row['buyer_id']}' and end_status='Y'";
		$res_price_month = mysqli_query($self_con, $sql_price_month);
		$row_price_month = mysqli_fetch_array($res_price_month);

		$sql_price_all = "select SUM(TotPrice) as all_price from tjd_pay_result where gwc_cont_pay=1 and buyer_id='{$row['buyer_id']}' and end_status='Y'";
		$res_price_all = mysqli_query($self_con, $sql_price_all);
		$row_price_all = mysqli_fetch_array($res_price_all);

		$month_money = $row_price_month[0] ? $row_price_month[0] : "0";
		$money_data = $month_money . "/\n" . $row_price_all[0];

		$prod_state = '주문';
		if ($row_order['prod_state'] == '1') {
			$prod_state = "취소";
		} else if ($row_order['prod_state'] == '2') {
			$prod_state = "반품";
		} else if ($row_order['prod_state'] == '3') {
			$prod_state = "교환";
		}

		$payment_status = "";
		if ($row['end_status'] == "N") $payment_status = "결제대기";
		else if ($row['end_status'] == "Y") $payment_status = "결제완료";
		else if ($row['end_status'] == "A") $payment_status = "후불결제";
		else if ($row['end_status'] == "E") $payment_status = "기간만료";

		if ($row_order['delivery']) {
			$sql_delivery = "select * from delivery_list where id='{$row_order['delivery']}'";
			$res_delivery = mysqli_query($self_con, $sql_delivery);
			$row_delivery = mysqli_fetch_array($res_delivery);
			$del_name = $row_delivery['delivery_name'];
		} else {
			$del_name = "";
		}
		$order_option = json_decode($row_order['gwc_order_option_content'], true);
		foreach ($order_option as $value) {
			$order_option_value = $value['name'] . ' : ' . $value['number'] . '개, (' . $value['opt_price'] . ' 원)';
		}

		$activeWorksheet
			->setCellValue("A$h", $number--)
			->setCellValue("B$h", $row['date'])
			->setCellValue("C$h", $row['idx'])
			->setCellValue("D$h", $row['member_type'])
			->setCellValue("E$h", $gong)
			->setCellValue("F$h", $yt_name)
			->setCellValue("G$h", $row_seller[0])
			->setCellValue("H$h", $row['seller_id'])
			->setCellValue("I$h", $row_recommend[0])
			->setCellValue("J$h", $recommend_id)
			->setCellValue("L$h", $row['VACT_InputName'])
			->setCellValue("K$h", $row['buyer_id'])
			->setCellValue("M$h", $row_cont_data['contents_sell_price'])
			->setCellValue("N$h", $row_cont_data['send_provide_price'])
			->setCellValue("O$h", $row_order['contents_cnt'])
			->setCellValue("P$h", ($row_cont_data['contents_sell_price'] * 1) * ($row_order['contents_cnt'] * 1))
			->setCellValue("Q$h", $row_cont_data['send_salary_price'])
			->setCellValue("R$h", $row_order_point[0])
			->setCellValue("S$h", $bank_price)
			->setCellValue("S$h", $card_price)
			->setCellValue("T$h", $row['TotPrice'])
			->setCellValue("U$h", $payment_status)
			->setCellValue("V$h", $month_cnt)
			->setCellValue("W$h", $row_item_cnt_all[0])
			->setCellValue("X$h", $month_money)
			->setCellValue("Y$h", $row_price_all[0])
			->setCellValue("Z$h", $del_name)
			->setCellValue("AA$h", $row_order['delivery_no'])
			->setCellValue("AB$h", $prod_state)
			->setCellValue("AC$h", $row_cont_data['product_code'])
			->setCellValue("AD$h", $row_order['gwc_order_option_content']);
		$h++;
	}

	$activeWorksheet->setTitle("굿마켓결제내역");
	$spreadsheet->setActiveSheetIndex(0);

	$filename = "gwcpayment.xls";
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
