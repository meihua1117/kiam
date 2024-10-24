<?php
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";

	$sql = "select * from Gn_app_version where seq =1";
	$result = mysqli_query($self_con,$sql);
	$row = mysqli_fetch_array($result);
	$result = array();
	$result['app_code'] = $row['app_code'];
	$result['app_version'] = $row['app_version'];
	echo json_encode($result);
?>