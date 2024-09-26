<?
include_once "rlatjd_fun.php";
if(!$_REQUEST[sql_serch])exit;
$sql="select * from ABS_sell where (sell_state='G' or (sell_state in('D', 'Q', 'T', 'W', 'Z') and sell_pack='Y')) order by sell_pack asc, sell_sign_date desc";
$resul=mysql_query($sql);
$intRowCount=mysql_num_rows($resul);
if(!$intRowCount)exit;
	$i=0;
	while($row=mysql_fetch_array($resul))
		{
			$sql_s="select * from ABS_sbag where sbag_oto_key='$row[sell_oto_key]'";
			$resul_s=mysql_query($sql_s) or die(mysql_error());
			while($row_s=mysql_fetch_array($resul_s))
				{
					$sell_serial[$i]=$row[sell_serial];
					$sell_buyer_post[$i]=$row[sell_buyer_post];
					$sell_buyer_name[$i]=$row[sell_buyer_name];
					$sell_buyer_phone[$i]=$row[sell_buyer_phone];
					$sell_buyer_mobile[$i]=$row[sell_buyer_mobile];
					$sell_buyer_address[$i]=$row[sell_buyer_address];
					$sell_delivery_comment1[$i]=$row[sell_delivery_comment1];
					$sell_delivery_comment2[$i]=$row[sell_delivery_comment2];
					$sell_admin_comment[$i]=$row[sell_admin_comment];
					$sbag_goods_name[$i]=$row_s[sbag_goods_name];
					$sbag_goods_sell_unit[$i]=$row_s[sbag_goods_sell_unit];
					$sbag_goods_unit_price[$i]=$row_s[sbag_goods_unit_price];
					$sbag_qty[$i]=$row_s[sbag_qty];
					$sbag_goods_sum[$i]=$row_s[sbag_goods_sum];
					$sell_cybmn_d[$i]=$row[sell_cybmn_d];
					$sell_receiver_name[$i]=$row[sell_receiver_name];
					$sell_receiver_phone[$i]=$row[sell_receiver_phone];
					$sell_receiver_mobile[$i]=$row[sell_receiver_mobile];
					$sell_receiver_address[$i]=$row[sell_receiver_address];
					$i++;
				}
		}
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
            ->setCellValue("A1", "택배사번호")
            ->setCellValue("B1", "운송장번호")
            ->setCellValue("C1", "주문번호")
            ->setCellValue("D1", "우편번호")
            ->setCellValue("E1", "받는사람")
            ->setCellValue("F1", "수취인전화번호")
            ->setCellValue("G1", "수취인핸드폰번호")
            ->setCellValue("H1", "수취인주소")
            ->setCellValue("I1", "고객메모1")
            ->setCellValue("J1", "고객메모2")
            ->setCellValue("K1", "관리자메모")			
            ->setCellValue("L1", "상품명")
            ->setCellValue("M1", "수량")
            ->setCellValue("N1", "옵션")
            ->setCellValue("O1", "부피")
            ->setCellValue("P1", "소계")
            ->setCellValue("Q1", "1묶음수량")
            ->setCellValue("R1", "연결상품")
            ->setCellValue("S1", "결제금액")
            ->setCellValue("T1", "적립금")
            ->setCellValue("U1", "주말여부")
            ->setCellValue("V1", "택배사명")
            ->setCellValue("W1", "택배사코드")
            ->setCellValue("X1", "주문자명")
            ->setCellValue("Y1", "주문자전화번호")
            ->setCellValue("Z1", "주문자휴대폰")
            ->setCellValue("AA1", "주문자주소");				
		for($i=0,$h=2; $i<count($sell_serial); $i++,$h++)
			{             
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A$h", "")
            ->setCellValue("B$h", "")
            ->setCellValue("C$h", $sell_serial[$i])
            ->setCellValue("D$h", $sell_buyer_post[$i])
            ->setCellValue("E$h", $sell_buyer_name[$i])
            ->setCellValue("F$h", $sell_buyer_phone[$i])
            ->setCellValue("G$h", $sell_buyer_mobile[$i])
            ->setCellValue("H$h", $sell_buyer_address[$i])
            ->setCellValue("I$h", $sell_delivery_comment1[$i])
            ->setCellValue("J$h", $sell_delivery_comment2[$i])
            ->setCellValue("K$h", $sell_admin_comment[$i])			
            ->setCellValue("L$h", $sbag_goods_name[$i])
            ->setCellValue("M$h", $sbag_goods_sell_unit[$i])
            ->setCellValue("N$h", "")
            ->setCellValue("O$h", "")
            ->setCellValue("P$h", $sbag_goods_unit_price[$i])
            ->setCellValue("Q$h",$sbag_qty[$i])
            ->setCellValue("R$h", "")
            ->setCellValue("S$h", $sbag_goods_sum[$i])
            ->setCellValue("T$h", $sell_cybmn_d[$i])
            ->setCellValue("U$h", "")
            ->setCellValue("V$h", "")
            ->setCellValue("W$h", "")
            ->setCellValue("X$h", $sell_receiver_name[$i])
            ->setCellValue("Y$h", $sell_receiver_phone[$i])
            ->setCellValue("Z$h", $sell_receiver_mobile[$i])
            ->setCellValue("AA$h",$sell_receiver_address[$i]);		  
			  }
// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle("뷰티모리_전체운송장_다운로드");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$filename="뷰티모리_전체운송장_다운로드_". date("Ymd"). ".xls";
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
