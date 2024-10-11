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
			->setCellValue("C1", "신청자이름")
			->setCellValue("D1", "성별")
			->setCellValue("E1", "휴대폰번호")
			->setCellValue("F1", "주소")
			->setCellValue("G1", "이메일")
			->setCellValue("H1", "생년월일")
			->setCellValue("I1", "직업")
			->setCellValue("J1", "신청제목")
			->setCellValue("K1", "신청채널")
			->setCellValue("L1", "회원가입")
			->setCellValue("M1", "등록일");
$h=2;
$No = 1;
while($row=mysqli_fetch_array($result)){
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue("A$h",$No++)
				->setCellValue("B$h",$row[sp])
				->setCellValue("C$h",$row['name'])
				->setCellValue("D$h",$row[sex])
				->setCellValue("E$h",$row[mobile])
				->setCellValue("F$h",$row[addr])
				->setCellValue("G$h",$row['email'])
				->setCellValue("H$h",$row[birthday])
				->setCellValue("I$h",$row[job])
				->setCellValue("J$h",$row[consult_date])
				->setCellValue("K$h",$row[event_code])
				->setCellValue("L$h",$row[join_yn])
				->setCellValue("M$h",$row['regdate']);
	$h++;		
}
$objPHPExcel->getActiveSheet()->setTitle("신청고객 리스트 ");
$objPHPExcel->setActiveSheetIndex(0);

$filename="mypage_request_list.xls";
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
