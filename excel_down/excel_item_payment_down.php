<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit','4500M');
if(strlen($_SESSION[one_member_id]) > 0) {

	$path="../";
      include_once $path."lib/rlatjd_fun.php";
	  $excel_sql=$_POST['excel_sql'];
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
					->setCellValue("E1", "상품명")
					->setCellValue("F1", "금액")
					->setCellValue("G1", "이름")
					->setCellValue("H1", "전화번호")
					->setCellValue("I1", "결제종류")
					->setCellValue("J1", "상태")
					->setCellValue("K1", "가입일")
					->setCellValue("L1", "결제일")
					->setCellValue("M1", "배당");
      $h=2;			
      while($row=mysqli_fetch_array($result))
		{
			$cardstr = "카드";
			$cardstr = $pay_type[$row[pay_method]];
			$payment_status = "";
			if($row['pay_status'] == "N") $payment_status = "결제대기";
			else if($row['pay_status'] == "Y") $payment_status = "결제완료";
			else if($row['pay_status'] == "A") $payment_status = "후불결제";
			else if($row['pay_status'] == "E") $payment_status = "기간만료";
			
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A$h",$number--)
					->setCellValue("B$h",$row['site'])
					->setCellValue("C$h",$row['recommend_id'])
					->setCellValue("D$h",$row['mem_id'])
					->setCellValue("E$h",$row['item_name'])
					->setCellValue("F$h",$row['item_price'])
					->setCellValue("G$h",$row['mem_name'])
					->setCellValue("H$h",str_replace("-", "",$row['mem_phone'])==$row['sendnum']||$row['sendnum']==""?str_replace("-", "",$row['mem_phone']):$row['sendnum'])
					->setCellValue("I$h",$cardstr)
					->setCellValue("J$h",$payment_status)
					->setCellValue("K$h",$row['first_regist'])
					->setCellValue("L$h",$row['pay_date'])
					->setCellValue("M$h",$row['pay_percent']);
			$h++;		
		}

      $objPHPExcel->getActiveSheet()->setTitle("상품결제내역");
      $objPHPExcel->setActiveSheetIndex(0);

      $filename="item_payment.xls";
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
