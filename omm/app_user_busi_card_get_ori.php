<?php

include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/common_func.php";

$userId = strtolower(trim($_REQUEST["id"]));
$useType = trim($_REQUEST["useType"]);

if($useType == '1') {

	//insert image

	if(isset($_POST['idx']) && $_POST['idx']>0){
		$idx=time()."_".rand(1000,9999);
		
		$file = explode(".", $_FILES['photo']['tmp_name']);
		$filename = $_FILES['photo']['name'];
		$filePath = $_FILES['photo']['tmp_name'];
		$exif = exif_read_data($_FILES['photo']['tmp_name']);
		if (!empty($exif['Orientation'])) {
			$imageResource = imagecreatefromjpeg($filePath); // provided that the image is jpeg. Use relevant function otherwise
			switch ($exif['Orientation']) {
				case 3:
				$image = imagerotate($imageResource, 180, 0);
				break;
				case 6:
				$image = imagerotate($imageResource, -90, 0);
				break;
				case 8:
				$image = imagerotate($imageResource, 90, 0);
				break;
				default:
				$image = $imageResource;
			} 
		}

		imagejpeg($image, $filename, 90);

		$comment = trim($_REQUEST["comment"]);
		$file_path='../upload/'.$idx.'.'.$file[1]; //이미지화일명은 인덱스번호로 지정
		if(move_uploaded_file($filePath, $file_path)) {
			
			$sql="insert into Gn_Member_card (mem_id , img_url, comment	, create_time) values ('$userId' ,'".($idx.'.'.$file[1])."' , '$comment' , now())";
			mysqli_query($self_con,$sql);
			
			$select_user = "select * from Gn_Member_card where mem_id = '$userId' and type = 0";
			$resul_p = mysqli_query($self_con,$select_user);
			$row_p = mysqli_fetch_array($resul_p);
			if($row_p[mem_id]) {
				$result = "true";
				$img_url = $row_p[img_url];
				echo "{\"result\":\"$result\",\"img_url\":\"$img_url\"}";
			} else {
				$result = "error";
				$img_url = "none";
				echo "{\"result\":\"$result\",\"img_url\":\"$img_url\"}";
			}
		} else{
			echo "{\"result\":\"error}";
		}

	}else {
		echo "{\"result\":\"error}";
	}
}else if($useType == '2') {
	//get image
	$select_user = "select * from Gn_Member_card where mem_id = '$userId' and type = 0";
	$resul_p = mysqli_query($self_con,$select_user);
	$row_p = mysqli_fetch_array($resul_p);
	if($row_p[mem_id]) {
		$result = "true";
		$img_url = $row_p[img_url];
		echo "{\"result\":\"$result\",\"img_url\":\"$img_url\"}";
	} else {
		$result = "error";
		$img_url = "none";
		echo "{\"result\":\"$result\",\"img_url\":\"$img_url\"}";
	}

}else if($useType == '3') {
	//delete img
	$select_user = "select * from Gn_Member_card where mem_id = '$userId' and type = 1";
	$resul_p = mysqli_query($self_con,$select_user);
	$row_p = mysqli_fetch_array($resul_p);
	if($row_p[mem_id]) {
		$update_sql = "update Gn_Member_card set type=2 where mem_id = '$userId'";
		mysqli_query($self_con,$update_sql);
		$resul_p = mysqli_query($self_con,$select_user);
		$row_p = mysqli_fetch_array($resul_p);
		if($row_p[mem_id]) {
			$result = "true";
			$img_url = $row_p[img_url];
			echo "{\"result\":\"$result\",\"img_url\":\"$img_url\"}";
		} else {
			$result = "error";
			$img_url = "none";
			echo "{\"result\":\"$result\",\"img_url\":\"$img_url\"}";
		}
	} else {
		$result = "error";
		$img_url = "none";
		echo "{\"result\":\"$result\",\"img_url\":\"$img_url\"}";
	}
}

?>