<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit','4500M');
if(strlen($_SESSION[one_member_id]) > 0) {
    $path="../";
	include_once $path."lib/rlatjd_fun.php";
	$excel_sql = str_replace("\'", "'", $_POST["excel_sql"]);
    $result = mysql_query($excel_sql) or die(mysql_error());
    $totalCnt = mysql_num_rows($result);
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
					->setCellValue("D1", "소속")
					->setCellValue("E1", "IAM소속")
					->setCellValue("F1", "발신번호")
					->setCellValue("G1", "소유자명")
					->setCellValue("H1", "발신내용")
					->setCellValue("I1", "발신시간")
					->setCellValue("J1", "수신번호")
					->setCellValue("K1", "발송건수")
					->setCellValue("L1", "회신수");
	$h=2;			
	while($row=mysql_fetch_array($result)){
		$sql_n="select memo from Gn_MMS_Number where sendnum='$row[send_num]' ";
		$resul_n=mysql_query($sql_n);
		$row_n=mysql_fetch_array($resul_n);

		$sql_cs="select count(idx) as cnt from Gn_MMS_status where idx='$row[idx]' and status='0'";
		$resul_cs=mysql_query($sql_cs);
		$row_cs=mysql_fetch_array($resul_cs);
		$success_cnt = $row_cs[0];

		$sql_s="select * from Gn_MMS_status where idx='$row[idx]' ";
		$resul_s=mysql_query($sql_s);
		$row_s=mysql_fetch_array($resul_s);
		
		$sql_sn="select * from Gn_MMS where idx='$row[idx]' ";
		$resul_sn=mysql_query($sql_sn);
		$row_sn=mysql_fetch_array($resul_sn);											
		$recv_cnt=explode(",",$row_sn[recv_num]);
		$total_cnt = count($recv_cnt);
		
		$sql="select count(seq) as cnt from call_app_log where api_name='receive_sms' and LENGTH(recv_num) >= 10 and  send_num='$row[send_num]' and recv_num in ($recv_num_in) and recv_num like '01%'  and regdate >= '$date' and sms not like '[%'";
		$kresult = mysql_query($sql) or die(mysql_error());
		$krow=mysql_fetch_array($kresult);
		$intRowCount=$krow[cnt];	
		
		$reg_date_1hour = strtotime("$row[reg_date] +1hours"); 
		
		$status = "";
		if($row[reservation]) {
			$status = "예약";
		}
		if($success_cnt==0){
			if(time() > $reg_date_1hour && $row[up_date] == "") {
				if($row[reservation] > date("Y-m-d H:i:s")){
				}else{
					$status .= "실패";
				}
			}else{
				if(time() > $reg_date_1hour && $row_s[up_date] == "") {
					$status .= "발송실패";
				}else{
					$status .= "발송중";
				}
			}
		}else{
			$status = $success_cnt."/".($total_cnt-$success_cnt);
		}
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue("A$h",$number--)
					->setCellValue("B$h",$row['mem_id'])
					->setCellValue("C$h",$row['mem_name'])
					->setCellValue("D$h",$row['site'])
					->setCellValue("E$h",$row['site_iam'])
					->setCellValue("F$h",$row['send_num'])
					->setCellValue("G$h",$row_n['memo'])
					->setCellValue("H$h",$row['content'])
					->setCellValue("I$h",substr($row['reg_date'],0,16))
					->setCellValue("J$h",$row['recv_num'])
					->setCellValue("K$h",$status)
					->setCellValue("L$h",$intRowCount);
		$h++;		
	}
	$objPHPExcel->getActiveSheet()->setTitle("메시지수발신내역");
	$objPHPExcel->setActiveSheetIndex(0);

	$filename="onemarket_msg.xls";
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
