<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit','4500M');
if(strlen($_SESSION['one_member_id']) > 0) {
      $path="../";
      $date_today=date("Y-m-d");
      $mem_id = $_REQUEST['mem_id'];
      include_once $path."lib/rlatjd_fun.php";
		$excel_sql="select *
                    	          from Gn_Member gm
                    	      left join tjd_pay_result p
                    	             on p.buyer_id = gm.mem_id 
                	              where recommend_id = '".$mem_id."' 
                	                and end_status='Y' 
                	           order by end_date desc";
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
					->setCellValue("B1", "아이디")
					->setCellValue("C1", "이름")
					->setCellValue("D1", "예약시작일")
					->setCellValue("E1", "종료일")
					->setCellValue("F1", "지불수단");
      $h=2;			
      while($row=mysqli_fetch_array($result))
		{
        	// 부가서비스 이용 여부 확인
        	// tjd_pay_result.fujia_status
        	$add_opt = $pay_type[$row['payMethod']];
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A$h",$number--)
					->setCellValue("B$h",$row['mem_id'])
					->setCellValue("C$h",$row['mem_name'])
					->setCellValue("D$h",$row['date'])
					->setCellValue("E$h",$row['end_date'])
					->setCellValue("F$h",$add_opt);
			$h++;		
		}
      $msg=$_REQUEST[status]==1?"발신내역":"수신내역";
      $msg2=$_REQUEST[status]==1?"send":"recv";
      $objPHPExcel->getActiveSheet()->setTitle("원마케팅문자 ".$msg);
      $objPHPExcel->setActiveSheetIndex(0);

      $filename="onemarket_".$msg2.".xls";
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
