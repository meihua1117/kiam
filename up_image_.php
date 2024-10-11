<?
include_once "lib/rlatjd_fun.php";
if($_FILES["upimage".$_REQUEST['k']]['name']){
    $img_name=date("dmYHis").str_replace(" ","",basename($_FILES["upimage".$_REQUEST['k']]["name"]));	
    $img_name=str_replace("'","",$img_name);	
    $img_name=str_replace('"',"",$img_name);	
    $img_name=basename($img_name);	
    if(move_uploaded_file($_FILES['upimage'.$_REQUEST['k']]['tmp_name'], "adjunct/mms/thum/".$img_name)){
	$size = getimagesize("adjunct/mms/thum/".$img_name);
	if($size[0] > 640) {
	    $ysize = $size[1] * (640 / $size[0]);
	    thumbnail("adjunct/mms/thum/".$img_name,"adjunct/mms/thum1/".$img_name,"",640,$ysize,"");
	    $show_img = "adjunct/mms/thum1/".$img_name;
	} else if($size[1] > 480) {
	    $xsize = $size[0] * (480/ $size[1]);
	    thumbnail("adjunct/mms/thum/".$img_name,"adjunct/mms/thum1/".$img_name,"",$xsize,480,"");
	    $show_img = "adjunct/mms/thum1/".$img_name;
	} else {
	    $show_img = "adjunct/mms/thum/".$img_name;
	}				
	?>
        <script language="javascript">
	    window.parent.document.getElementsByName('<?php echo $_REQUEST['target']?>')[<?=$_REQUEST[i]?>].value='<?=$show_img?>';
	    window.parent.type_check();
	</script>
        <?
    }
}
?>
