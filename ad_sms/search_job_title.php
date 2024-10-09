<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";


$keyword = $_REQUEST['keyword'];


$jobs = array();
$query = "SELECT job_title FROM gn_job_titles WHERE job_title Like '%{$keyword}%' GROUP BY job_title";

$result = mysql_query($query);
while($row = mysql_fetch_array($result)) {
    $jobs[] = $row['job_title'];
}

echo json_encode(array('status'=>'1','jobs'=>$jobs));
?>