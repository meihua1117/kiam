<?
@header("Content-type: text/html; charset=utf-8");
date_default_timezone_set('Asia/Seoul');
$date_today1=date("Y-m-d H:i:s");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$end_date = NULL;
$info_source = $_POST['info_source'];
if($_POST['info_type'] == 1){
    $info_type = "지원사업";
}
else if($_POST['info_type'] == 2){
    $info_type = "입찰공고";
}
else if($_POST['info_type'] == 3){
    $info_type = "행사교육";
}
else if($_POST['info_type'] == 4){
    $info_type = "기타정보";
}
// $info_type = $_POST['info_type'];
$work_name = $_POST['work_name'];
$detail_link = $_POST['detail_link'];
$reg_date = $_POST['reg_date'];
$end_date = $_POST['end_date'];
$org_name = $_POST['org_name'];
$show_cnt = $_POST['show_cnt'];

if(isset($_POST['updat'])){
    $sql = "update get_crawler_bizinfo set info_source='{$info_source}', web_type='{$info_type}', work_name='{$work_name}', reg_date='{$reg_date}', end_date='{$end_date}', org_name='{$org_name}', show_cnt={$show_cnt}, detail_link='{$detail_link}' where id={$_POST['updat']}";
    // echo $sql; exit;
    mysql_query($sql);
    echo 2;
}
else if(isset($_POST['check'])){
    $id = $_POST['id'];
    $sql_recom = "select * from get_crawler_bizinfo where id='{$id}'";
	$res = mysql_query($sql_recom);
	$row = mysql_fetch_array($res);
	$info_source = $row['info_source'];
	$web_type = $row['web_type'];

	if($info_source == "국립산림과학원"){
		$info_source = "산림과학원";
	}
	$sql_reg = "select * from reg_biz_contents where info_source='{$info_source}' and info_type='{$web_type}'";
	// echo $sql_reg;
	$res_reg = mysql_query($sql_reg);
    $row_reg = mysql_num_rows($res_reg);
    if($row_reg == 0){
        echo 0;
    }
    else{
        echo $id;
    }
}
?>