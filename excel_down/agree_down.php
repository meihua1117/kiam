<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit','2500M');
if(strlen($_SESSION['one_member_id']) > 0) {
	if(!$_REQUEST[excel_sql] && !$_REQUEST['down_type'])
	exit;
	$path="../";
	include_once $path."lib/rlatjd_fun.php";
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
	if($_REQUEST['down_type']==1)
	{
	$excel_sql=str_replace("`","'",$_REQUEST[excel_sql]);
	$result = mysqli_query($self_con,$excel_sql) or die(mysqli_error($self_con));
	$sort_no=mysqli_num_rows($result);	
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue("A1", "번호")
	            ->setCellValue("B1", "발신번호")
				->setCellValue("C1", "수신번호")
				->setCellValue("D1", "문자제목")
				->setCellValue("E1", "문자내용")
				->setCellValue("F1", "첨부파일")
				->setCellValue("G1", "등록경로")
				->setCellValue("H1", "등록일");	
		$h=2;			
		while($row=mysqli_fetch_array($result))
		{
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A$h",$sort_no)
					->setCellValue("B$h",$row['send_num'])
					->setCellValue("C$h",$row['recv_num'])
					->setCellValue("D$h",$row['title'])
					->setCellValue("E$h",$row['content'])
					->setCellValue("F$h",$row['jpg'])
					->setCellValue("G$h",$row['status'])
					->setCellValue("H$h",$row['reg_date']);
			$h++;
			$sort_no--;
		}
		$objPHPExcel->getActiveSheet()->setTitle("원마케팅문자 수신동의전화번호");
		$objPHPExcel->setActiveSheetIndex(0);
		$filename="onemarket_agree.xls";	
	}
	else if($_REQUEST['down_type']==2)
	{
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue("A1", "발신번호")
				->setCellValue("B1", "수신번호")
				->setCellValue("C1", "문자제목")
				->setCellValue("D1", "문자내용");		
		$objPHPExcel->getActiveSheet()->setTitle("원마케팅문자 수신동의등록 샘플");
		$objPHPExcel->setActiveSheetIndex(0);
		$filename="onemarket_deny_sample.xls";	
	}
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
