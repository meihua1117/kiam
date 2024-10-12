<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit','4500M');
if(strlen($_SESSION['one_member_id']) > 0) {
      if(!$_REQUEST[excel_sql])
      $path="../";
      include_once $path."lib/rlatjd_fun.php";
      $excel_sql=str_replace("`","'",$_REQUEST[excel_sql]);

      if($_POST[ids]){
        $ids=$_POST[ids];
        $pos = strpos($excel_sql, 'order by');
        $excel_sql = substr($excel_sql, 0, $pos) . ' and seq in(' . $ids .') ' . substr($excel_sql, $pos);
      }
    //   var_dump($excel_sql);
    //   exit;
    
      $result = mysqli_query($self_con,$excel_sql) or die(mysqli_error($self_con));
      $sort_no = mysqli_num_rows($result);
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
                  ->setCellValue("B1", "발신번호")
      			->setCellValue("C1", "수신일시")
                  ->setCellValue("D1", "문자내용")
                  ->setCellValue("E1", "수신번호")
                  ->setCellValue("F1", "변경된번호")			
                  ->setCellValue("G1", "그룹명")			
      			->setCellValue("H1", "분류");			
      $h=2;			
      while($row=mysqli_fetch_array($result))
      {	
      $objPHPExcel->setActiveSheetIndex(0)
                  ->setCellValue("A$h",$sort_no)
                  ->setCellValue("B$h",$row[dest])
                  ->setCellValue("C$h",$row[reservation_time])
                  ->setCellValue("D$h",$row[msg_text])
                  ->setCellValue("E$h",$row[ori_num])
                  ->setCellValue("F$h",$row[chg_num])
                  ->setCellValue("G$h",$row[grp_name])			
                  ->setCellValue("H$h",$msg_flag_arr[$row['msg_flag']]);
      	$h++;
      	$sort_no--;
      }
      $objPHPExcel->getActiveSheet()->setTitle("원마케팅문자 로그기록");
      $objPHPExcel->setActiveSheetIndex(0);

      $filename="onemarket_log.xls";
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
