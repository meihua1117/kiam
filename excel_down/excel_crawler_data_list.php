<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit','4500M');
if(strlen($_SESSION['one_member_id']) > 0) {
  	$path="../";
	include_once $path."lib/rlatjd_fun.php";
	$excel_sql = str_replace("\'", "'", $_POST["excel_sql"]);
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
      $h=2;	
	  $phonelist = array();	
	  $maillist = array();	
      while($row=mysqli_fetch_array($result))
		{		 
			$number--;   
			if($row['cell'] == "")
			{
				if($row['email'] != "" && array_search($row['email'], $maillist) === false)
				{

					array_push($maillist, $row['email']);
					$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A$h",$number)
					->setCellValue("B$h",$row['user_id'])
					->setCellValue("C$h",$row['keyword'])
					->setCellValue("D$h",$row['data_type'])
					->setCellValue("E$h",$row['cell'])
					->setCellValue("F$h",$row['email'])
					->setCellValue("G$h",$row['ceo'])
					->setCellValue("H$h",$row['company_name'])
					->setCellValue("I$h",$row['company_type'])
					->setCellValue("J$h",strip_tags($row['address']))
					->setCellValue("K$h",strip_tags($row['url']))
					->setCellValue("L$h",$row['regdate']);	
					$h++;	
				}
			
			}
			else if(array_search($row['cell'], $phonelist) === false)
			{
				if($row['email'] != "" && array_search($row['email'], $maillist) === false)
				{
					array_push($maillist, $row['email']);
				}
				array_push($phonelist, $row['cell']);
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("A$h",$number)
				->setCellValue("B$h",$row['user_id'])
				->setCellValue("C$h",$row['keyword'])
				->setCellValue("D$h",$row['data_type'])
				->setCellValue("E$h",$row['cell'])
				->setCellValue("F$h",$row['email'])
				->setCellValue("G$h",$row['ceo'])
				->setCellValue("H$h",$row['company_name'])
				->setCellValue("I$h",$row['company_type'])
				->setCellValue("J$h",strip_tags($row['address']))
				->setCellValue("K$h",strip_tags($row['url']))
				->setCellValue("L$h",$row['regdate']);
				$h++;	
			}	
		}

      $objPHPExcel->getActiveSheet()->setTitle("디비수집리스트");
      $objPHPExcel->setActiveSheetIndex(0);

      $filename="crawler_data_list.xls";
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
