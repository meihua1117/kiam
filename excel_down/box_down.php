<?
if(!$_REQUEST[box_text])
exit;
$path="../";
set_time_limit(0);
ini_set('memory_limit','2500M');
include_once $path."lib/rlatjd_fun.php";
$box_s=str_replace("`","'",$_REQUEST[box_text]);
$sql_db="select seq,dest,callback,msg_flag,msg_text,msg_url,reservation_time,grp from sm_data where dest in ($box_s) ";
$resul_db=mysql_query($sql_db);

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
			->setCellValue("A1", "seq")
			->setCellValue("B1", "dest")
			->setCellValue("C1", "callback")
			->setCellValue("D1", "msg_flag")
			->setCellValue("E1", "msg_text")
			->setCellValue("F1", "msg_url")
			->setCellValue("G1", "reservation_time")
			->setCellValue("H1", "grp");
$h=2;			
while($row_db=mysql_fetch_array($resul_db))
{
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue("A$h",$row_db[seq])
			->setCellValue("B$h",$row_db[dest])
			->setCellValue("C$h",$row_db[callback])
			->setCellValue("D$h",$row_db[msg_flag])
			->setCellValue("E$h",$row_db[msg_text])
			->setCellValue("F$h",$row[msg_url])
			->setCellValue("G$h",$row[reservation_time])
			->setCellValue("H$h",$row[grp]);
	$h++;		
}
$msg="등록된 번호";
$msg2="Number";
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
?>
