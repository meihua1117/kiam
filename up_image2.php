<?
include_once "lib/rlatjd_fun.php";
if($_FILES[upimage]['name'][$_REQUEST[i]])
{
	$tempFile = $_FILES[upimage]['tmp_name'][$_REQUEST[i]];
	$file_arr=explode(".",$_FILES[upimage]['name'][$_REQUEST[i]]);
	$tmp_file_arr=explode("/",$tempFile);
	$img_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];	
	if(move_uploaded_file($_FILES['upimage']['tmp_name'][$_REQUEST[i]], "adjunct/mms/thum/".$img_name))
	{
		thumbnail("adjunct/mms/thum/".$img_name,"adjunct/mms/thum1/".$img_name,"",330,500,"");
		$show_img = "http://www.kiam.kr/adjunct/mms/thum1/".$img_name;
		?>
        <script language="javascript" src="js/jquery-1.7.1.min.js"></script>
        <script language="javascript">
		window.parent.document.getElementsByName('upimage_str')[<?=$_REQUEST[i]?>].value='<?=$show_img?>';
		$(window.parent.$(".img_view")[<?=$_REQUEST[i]?>]).html("<img src='<?=$show_img?>' />");
		</script>
        <?
	}
}
?>
