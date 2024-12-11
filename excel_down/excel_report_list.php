<?
include_once "../lib/rlatjd_fun.php";
set_time_limit(0);
ini_set('memory_limit', '4500M');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();
$activeWorksheet = $spreadsheet->getActiveSheet();
function getExcelTag($w)
{
    if ($w < 26) {
        $tag = chr(65 + $w);
    } elseif ($w < 702) {
        $tag = chr(64 + ($w / 26)) . chr(65 + $w % 26);
    } else {
        $tag = chr(64 + (($w - 26) / 676)) . chr(65 + ((($w - 26) % 676) / 26)) . chr(65 + $w % 26);
    }
    return $tag;
}
if (strlen($_SESSION['one_member_id']) > 0 || strlen($_SESSION['iam_member_id']) > 0) {
    $path = "../";
    $excel_sql = base64_decode($_POST['excel_sql']);
    $repo_id =  $_POST['index'];
    $h = 1;
    $w = 5;

    $activeWorksheet
        ->setCellValue("A$h", "번호")
        ->setCellValue("B$h", "응답일시")
        ->setCellValue("C$h", "아이디")
        ->setCellValue("D$h", "이름")
        ->setCellValue("E$h", "휴대폰");
    /*$activeWorksheet->mergeCellsByColumnAndRow(0, 1, 0, 2);
    $activeWorksheet->mergeCellsByColumnAndRow(1, 1, 1, 2);
    $activeWorksheet->mergeCellsByColumnAndRow(2, 1, 2, 2);
    $activeWorksheet->mergeCellsByColumnAndRow(3, 1, 3, 2);
    $activeWorksheet->mergeCellsByColumnAndRow(4, 1, 4, 2);*/
    $activeWorksheet->mergeCells("A1:A2");
    $activeWorksheet->mergeCells("B1:B2");
    $activeWorksheet->mergeCells("C1:C2");
    $activeWorksheet->mergeCells("D1:D2");
    $activeWorksheet->mergeCells("E1:E2");
    $form_arr = array();
    $item_arr = array();
    $sql1 = "select * from gn_report_form1 where form_id=$repo_id and item_type <> 2 order by item_order";
    $res1 = mysqli_query($self_con, $sql1);

    while ($row1 = mysqli_fetch_array($res1)) {
        array_push($form_arr, $row1);
        $sql2 = "select count(id) from gn_report_form2 where form_id=$repo_id and item_id = {$row1['id']}";
        $res2 = mysqli_query($self_con, $sql2);
        $row2 = mysqli_fetch_array($res2);
        $tag = getExcelTag($w);
        //$activeWorksheet->mergeCellsByColumnAndRow($w, $h, $w + $row2[0] - 1, $h);
        $activeWorksheet->mergeCells($w, $h, $w + $row2[0] - 1, $h);
        if ($row1['item_title']) {
            $tag_title = htmlspecialchars($row1['item_title']) . "=>" . htmlspecialchars($row1['item_req']);
        } else {
            $tag_title = htmlspecialchars(htmlspecialchars($row1['item_req']));
        }
        if ($tag_title)
            $activeWorksheet->setCellValue("$tag$h", $tag_title);
        $w += $row2[0];
    }
    $h = 2;
    $w = 5;
    foreach ($form_arr as $form) {
        $sql2 = "select * from gn_report_form2 where form_id=$repo_id and item_id = {$form['id']} order by id";
        $res2 = mysqli_query($self_con, $sql2);
        while ($row2 = mysqli_fetch_array($res2)) {
            $row2['item_type'] = $form['item_type'];
            array_push($item_arr, $row2);
            $tag = getExcelTag($w);
            $activeWorksheet->setCellValue("$tag$h", htmlspecialchars($row2['tag_name']));
            $w++;
        }
    }
    $h = 3;
    $result = mysqli_query($self_con, $excel_sql);
    while ($repo_row = mysqli_fetch_array($result)) {
        $conts = json_decode($repo_row['cont'], true);
        $activeWorksheet->setCellValue("A$h", $h - 2);
        $activeWorksheet->setCellValue("B$h", $repo_row['reg_date']);
        $activeWorksheet->setCellValue("C$h", $repo_row['userid']);
        $activeWorksheet->setCellValue("D$h", htmlspecialchars($repo_row['name']));
        $activeWorksheet->setCellValue("E$h", $repo_row['phone']);
        $w = 5;
        foreach ($item_arr as $item) {
            foreach ($conts as $cont) {
                foreach ($cont as $key => $value) {
                    if ($item['tag_id'] == $key) {
                        $repo_value = htmlspecialchars($value);
                        break;
                    }
                }
            }

            $tag = getExcelTag($w++);
            if ($item['item_type'] == 0 || $item['item_type'] == 3) {
                $val = $repo_value;
            } else {
                $val = ($repo_value == 1 ? "예" : "");
            }
            $activeWorksheet->setCellValue("$tag$h", $val);
        }
        $h++;
    }
    $activeWorksheet->setTitle("리포트내역" . $repo_id);
    $spreadsheet->setActiveSheetIndex(0);
    $filename = "report_" . $repo_id . ".xlsx";
    $spreadsheet->getProperties()->setCreator('onlyone')
		->setLastModifiedBy('onlyone')
		->setTitle('Office 2007 XLSX Onlyone Document')
		->setSubject('Office 2007 XLSX Onlyone Document')
		->setDescription('Onlyone document for Office 2007 XLSX.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Onlyone contact file');
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
	header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header('Pragma: public'); // HTTP/1.0

	$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
	$writer->save('php://output');
    exit;
}
?>