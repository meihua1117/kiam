<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit','4500M');
function getExcelTag($w){
    if ($w < 26) {
        $tag = chr(65 + $w);
    } elseif ($w < 702) {
        $tag = chr(64 + ($w / 26)).chr(65 + $w % 26);
    } else {
        $tag = chr(64 + (($w - 26) / 676)).chr(65 + ((($w - 26) % 676) / 26)).chr(65 + $w % 26);
    }
    return $tag;
}
if(strlen($_SESSION['one_member_id']) > 0 || strlen($_SESSION['iam_member_id']) > 0) 
{
    $path="../";
    include_once $path."lib/rlatjd_fun.php";
    $excel_sql= base64_decode($_POST['excel_sql']);
    $repo_id =  $_POST['index'];
    require_once("Classes/PHPExcel.php");
    $objPHPExcel = new PHPExcel();
    $h=1;
    $w=5;
    $objPHPExcel->getProperties()
        ->setCreator("excel")
        ->setLastModifiedBy("excel")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("excel file");
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("A$h", "번호")
        ->setCellValue("B$h", "응답일시")
        ->setCellValue("C$h", "아이디")
        ->setCellValue("D$h", "이름")
        ->setCellValue("E$h", "휴대폰");
    $objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow(0,1,0,2);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow(1,1,1,2);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow(2,1,2,2);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow(3,1,3,2);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow(4,1,4,2);
    $form_arr = array();
    $item_arr = array();
    $sql1 = "select * from gn_report_form1 where form_id=$repo_id and item_type <> 2 order by item_order";
    $res1 = mysql_query($sql1);

    while($row1 = mysql_fetch_array($res1)){
        array_push($form_arr,$row1);
        $sql2 = "select count(id) from gn_report_form2 where form_id=$repo_id and item_id = $row1[id]";
        $res2 = mysql_query($sql2);
        $row2 = mysql_fetch_array($res2);
        $tag = getExcelTag($w);
        $objPHPExcel->setActiveSheetIndex(0)->mergeCellsByColumnAndRow($w,$h,$w+$row2[0]-1,$h);
        if($row1['item_title']){
            $tag_title = htmlspecialchars($row1['item_title'])."=>".htmlspecialchars($row1['item_req']);
        }else{
            $tag_title = htmlspecialchars(htmlspecialchars($row1['item_req']));
        }
        if($tag_title)
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$tag$h", $tag_title);
        $w += $row2[0];
    }
    $h=2;
    $w=5;
    foreach($form_arr as $form){
        $sql2 = "select * from gn_report_form2 where form_id=$repo_id and item_id = $form[id] order by id";
        $res2 = mysql_query($sql2);
        while($row2 = mysql_fetch_array($res2)){
            $row2['item_type'] = $form[item_type];
            array_push($item_arr,$row2);
            $tag = getExcelTag($w);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$tag$h", htmlspecialchars($row2['tag_name']));
            $w++;
        }
    }
    $h=3;
    $result = mysql_query($excel_sql);
    while($repo_row=mysql_fetch_array($result)){
        $conts = json_decode($repo_row['cont'],true);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$h",$h-2);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("B$h",$repo_row['reg_date']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("C$h",$repo_row['userid']);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("D$h",htmlspecialchars($repo_row['name']));
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue("E$h",$repo_row['phone']);
        $w = 5;
        foreach($item_arr as $item){
            foreach($conts as $cont){
                foreach($cont as $key => $value)
                {
                    if($item['tag_id'] == $key){
                        $repo_value = htmlspecialchars($value);
                        break;
                    }
                }
            }
            
            $tag = getExcelTag($w++);
            if($item[item_type] == 0 || $item[item_type] == 3){
                $val = $repo_value;
            }else{
                $val = ($repo_value == 1?"예":"");
            }
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("$tag$h",$val);
        }
        $h++;
    }
    $objPHPExcel->getActiveSheet()->setTitle("리포트내역".$repo_id);
    $objPHPExcel->setActiveSheetIndex(0);
    $filename="report_".$repo_id.".xls";
    header('Content-type: application/vnd.ms-excel; charset=utf-8');
    header("Content-Disposition: attachment; filename=$filename");
    header('Cache-Control: max-age=0');
    header('Content-Description: PHP4 Generated Data');
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header('Pragma: public');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}		  
?>
