<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";


$keyword = $_REQUEST['keyword'];


$jobs = array();
$query = "SELECT job_name FROM gn_jobs WHERE job_name Like '%{$keyword}%' GROUP BY job_name";

$result = mysql_query($query);
while($row = mysql_fetch_array($result)) {
    $jobs[] = $row['job_name'];
}

echo json_encode(array('status'=>'1','jobs'=>$jobs));
?>