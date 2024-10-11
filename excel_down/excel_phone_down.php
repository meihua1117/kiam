<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit','4500M');
if(strlen($_SESSION['one_member_id']) > 0) {
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
				->setCellValue("B1", "아이디")
				->setCellValue("C1", "소유자명")
				->setCellValue("D1", "전화번호")
				->setCellValue("E1", "소그룹명")
				->setCellValue("F1", "고객이름")
				->setCellValue("G1", "전화번호")
				->setCellValue("H1", "등록일");
	$h=2;
	while($row=mysqli_fetch_array($result)){
		$sql="select mem_id, mem_name from Gn_Member where mem_id='$row[mem_id]'";
		$sresul=mysqli_query($self_con,$sql);
		$srow=mysqli_fetch_array($sresul);
		if($srow['mem_id'] == "")
		{
			$sql="select m.mem_id, mem_name from Gn_Member m inner join Gn_MMS_Number n on m.mem_id=n.mem_id where n.sendnum='$row[dest]'";
			$sresul=mysqli_query($self_con,$sql);
			$srow=mysqli_fetch_array($sresul);
		}

		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A$h",$number--)
					->setCellValue("B$h",$srow['mem_id'])
					->setCellValue("C$h",$srow['mem_name'])
					->setCellValue("D$h",$row['dest'])
					->setCellValue("E$h",$row['grp'])
					->setCellValue("F$h",$row['msg_text'])
					->setCellValue("G$h",$row['msg_url'])
					->setCellValue("H$h",$row['reservation_time']);
		$h++;
	}
	$objPHPExcel->getActiveSheet()->setTitle("통합주소록");
	$objPHPExcel->setActiveSheetIndex(0);

	$filename="통합주소록.xls";
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
