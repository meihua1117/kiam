<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit','2500M');
$excel_sql=$_POST['excel_sql'];
$excel_sql=str_replace("`","'",$excel_sql);
$result = mysqli_query($self_con,$excel_sql) or die(mysqli_error($self_con));
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
			->setCellValue("A1", "No")
			->setCellValue("B1", "신청창키워드")
			->setCellValue("C1", "신청내용")
			->setCellValue("D1", "링크")
			->setCellValue("E1", "조회수")
			->setCellValue("F1", "신청채널")
			->setCellValue("G1", "발송폰번호")
			->setCellValue("H1", "등록일");
$h=2;
$No = 1;
while($row=mysqli_fetch_array($result)){
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("A$h",$No++)
				->setCellValue("B$h",$row[event_name_eng])
				->setCellValue("C$h",$row[event_name_kor])
				->setCellValue("D$h",$row[short_url])
				->setCellValue("E$h",$row[read_cnt])
				->setCellValue("F$h",$row[pcode])
				->setCellValue("G$h",$row[mobile])
				->setCellValue("H$h",$row['regdate']);
	$h++;		
}
$objPHPExcel->getActiveSheet()->setTitle("고객신청 리스트 ");
$objPHPExcel->setActiveSheetIndex(0);

$filename="mypage_link_list.xls";
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
?>
