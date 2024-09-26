<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";


$keyword = $_REQUEST['keyword'];


$jobs = array();
$query = "SELECT job_name FROM gn_jobs WHERE job_name Like '%{$keyword}%'";

$result = mysqli_query($self_con, $query);
while($row = mysqli_fetch_array($result)) {
    $jobs[] = $row['job_name'];
}

echo json_encode(array('status'=>'1','jobs'=>$jobs));
?>