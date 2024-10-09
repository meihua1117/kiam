<?php
include_once "../lib/class.image.php";
$uploadLink ="/naver_editor/upload_editor/";
$uploadDir = "..$uploadLink";

if(isset($_FILES["upload"])){

	$folder = $uploadDir. date("Y-m-d") . "/";
	if(!file_exists($folder))
			mkdir($folder, 0777, true);
	
	$ext = ".".end((explode(".", $_FILES["upload"]["name"]))); 
	$upload_filename = time().rand(10000, 99999).$ext;
	
	$upload_file = $folder. $upload_filename;
	$success=move_uploaded_file($_FILES["upload"]["tmp_name"], $upload_file);
	if( $success){
		$handle = new Image($upload_file, 800);
        $handle->resize();

		$uploadLink = $uploadLink.date("Y-m-d") . "/".$upload_filename;
		$json["uploaded"]=true;
		$json["url"]=$uploadLink;
		echo json_encode($json);
	}
}
if(!$success){
		$json["uploaded"]=false;
		$json["error"]=array("message"=>"이미지 업로드 오류");
		echo json_encode($json);
}

?>