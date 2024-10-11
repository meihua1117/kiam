<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
if($_FILES["upimage".$_REQUEST['k']]['name'])
{
    // echo "upimage".$_REQUEST['k'];
	$tempFile = $_FILES["upimage".$_REQUEST['k']][tmp_name];
	$file_arr=explode(".",$_FILES["upimage".$_REQUEST['k']]['name']);
	$tmp_file_arr=explode("/",$tempFile);
	$img_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];
	if(move_uploaded_file($_FILES['upimage'.$_REQUEST['k']]['tmp_name'],     "../adjunct/mms/thum/".$img_name))
	{
		$size = getimagesize("../adjunct/mms/thum/".$img_name);
		/*
		if($size[0] > 1280) {
			  $ysize = $size[1] * (1280 / $size[0]);
		    thumbnail("adjunct/mms/thum/".$img_name,"adjunct/mms/thum1/".$img_name,"",1280,$ysize,"");
		}
		if($size[1] > 960) {
			$xsize = $size[0] * (960/ $size[1]);
		    thumbnail("adjunct/mms/thum/".$img_name,"adjunct/mms/thum1/".$img_name,"",$xsize,960,"");
		}	
		*/	
		
		if($size[0] > 640) {
			  $ysize = $size[1] * (640 / $size[0]);
		    thumbnail("../adjunct/mms/thum/".$img_name,"../adjunct/mms/thum1/".$img_name,"",640,$ysize,"");
		    $show_img = "/adjunct/mms/thum1/".$img_name;
		} else if($size[1] > 480) {
			$xsize = $size[0] * (480/ $size[1]);
		    thumbnail("../adjunct/mms/thum/".$img_name,"../adjunct/mms/thum1/".$img_name,"",$xsize,480,"");
		    $show_img = "/adjunct/mms/thum1/".$img_name;
		} else {
		    $show_img = "/adjunct/mms/thum/".$img_name;
		}				
		 
		//thumbnail("adjunct/mms/thum/".$img_name,"adjunct/mms/thum1/".$img_name,"",330,500,"");
		?>
        <script language="javascript">
		window.parent.document.getElementsByName('<?php echo $_REQUEST['target']?>')[<?=$_REQUEST[i]?>].value='<?=$show_img?>';
		window.parent.type_check();
		</script>
        <?
	}
}
?>
