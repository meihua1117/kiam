<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit','4500M');
if(strlen($_SESSION['one_member_id']) > 0) {
	$excel_sql=base64_decode($_POST['excel_sql']);
	$excel_sql=str_replace("`","'",$excel_sql);
    $result = mysqli_query($self_con,$excel_sql) or die(mysqli_error($self_con));
    $totalCnt = mysqli_num_rows($result);
    $number			= $totalCnt;
    require_once("Classes/PHPExcel.php");
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()
		->setCreator("excel")
      	->setLastModifiedBy("excel")
      	->setTitle("Office 2007 XLSX Test Document")
      	->setSubject("Office 2007 XLSX Test Document")
      	->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
      	->setKeywords("office 2007 openxml php")
      	->setCategory("excel file");
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue("A1", "번호")
		->setCellValue("B1", "소속")
		->setCellValue("C1", "추천인")
		->setCellValue("D1", "아이디")
		->setCellValue("E1", "이용상품")
		->setCellValue("F1", "이용건수")
		->setCellValue("G1", "이름")
		->setCellValue("H1", "전화번호")
		->setCellValue("I1", "결제종류")
		->setCellValue("J1", "금액(원)")
		->setCellValue("K1", "개수")
		->setCellValue("L1", "기간")
		->setCellValue("M1", "이용정지")
		->setCellValue("N1", "상태")
		->setCellValue("O1", "가입일")
		->setCellValue("P1", "결제일/종료일");
	$h=2;
    while($row=mysqli_fetch_array($result)){
		$cardstr =$pay_type[$row['payMethod']];
		$payment_status = "";
		if($row['end_status'] == "N") $payment_status = "결제대기";
		else if($row['end_status'] == "Y") $payment_status = "결제완료";
		else if($row['end_status'] == "A") $payment_status = "후불결제";
		else if($row['end_status'] == "E") $payment_status = "기간만료";
			
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A$h",$number--)
					->setCellValue("B$h",$row['site'])
					->setCellValue("C$h",$row['recommend_id'])
					->setCellValue("D$h",$row['buyer_id'])
					->setCellValue("E$h",$row['member_type'])
					->setCellValue("F$h",number_format($row['max_cnt']))
					->setCellValue("G$h",$row['mem_name'])
					->setCellValue("H$h",str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""?str_replace("-", "",$row['mem_phone']):$row['sendnum'])
					->setCellValue("I$h",$cardstr)
					->setCellValue("J$h",$row['TotPrice'])
					->setCellValue("K$h",number_format($row['add_phone']))
					->setCellValue("L$h",number_format($row['month_cnt']))
					->setCellValue("M$h", $row['stop_yn'])
					->setCellValue("N$h", $payment_status)
					->setCellValue("O$h", $row['first_regist'])
					->setCellValue("P$h", $row['date'] . "/". $row['end_date']);
			$h++;		
		}

      $objPHPExcel->getActiveSheet()->setTitle("솔루션결제내역");
      $objPHPExcel->setActiveSheetIndex(0);

      $filename="paymnet.xls";
      header('Content-type: application/vnd.ms-excel; charset=utf-8');
      header("Content-Disposition: attachment; filename=$filename");
      header('Cache-Control: max-age=0');
      header('Cache-Control: max-age=1');
      header('Content-Description: PHP4 Generated Data');
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header('Pragma: public');
       
      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      $objWriter->save('php://output');
      exit;	
}		  
?>
