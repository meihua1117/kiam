<?php
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";
	
	$userid = isset($_REQUEST['userid']) ? $_REQUEST['userid'] : "";
	
	$sql = "select card_short_url,card_title from Gn_Iam_Name_Card where group_id = 0 and mem_id ='{$userid}' order by req_data asc";
	$result = mysqli_query($self_con,$sql);
	$row = mysqli_fetch_array($result);

	$result = array();
	$result['card_short_url'] = $row['card_short_url'];
	$result['card_title'] = $row['card_title'];
	
	echo json_encode($result);
	
	
?>
