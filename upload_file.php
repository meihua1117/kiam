<?
$attachDir = "attachment/";
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
if($_FILES['file_attach'][name]){
	$size = filesize($_FILES['file_attach']['tmp_name']);
	if($size > 3 * 1024 * 1024) {
		?>
		<script language="javascript">
			alert("3M이하 파일을 선택해 주세요");
			window.parent.document.getElementsByName('file_attach')[0].value = "";
		</script>
		<?
	}
	else
	{
		$folder = $attachDir. date("Y-m-d") . "/";
		if(!file_exists($folder))
			mkdir($folder, 0777, true);
		$tmp_name=date("dmYHis"). "__".str_replace(" ","",basename($_FILES['file_attach']['name']));	
		$tmp_name=str_replace("'","",$tmp_name);	
		$tmp_name=str_replace('"',"",$tmp_name);
		$tmp_name=basename($tmp_name);
		$upload_file = $folder .$tmp_name;
		if(move_uploaded_file($_FILES['file_attach']['tmp_name'], $upload_file)){
			?>
			<script language="javascript">
				window.parent.document.getElementsByName('<?php echo $_REQUEST['target']?>')[0].value='<?=$upload_file?>';
			</script>
			<?			
		}
		else{
			?>
			<script language="javascript">
				alert("파일 업로드가 실패하였습니다.");
			</script>
			<?
		}			
	
	}
}


?>
