<?
include_once "lib/rlatjd_fun.php";
if($_FILES[upimage]['name'])
{
	$tempFile = $_FILES[upimage]['tmp_name'];
	$file_arr=explode(".",$_FILES[upimage]['name']);
	$tmp_file_arr=explode("/",$tempFile);
	$img_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];	
	if(move_uploaded_file($_FILES['upimage']['tmp_name'], "adjunct/mms/thum/".$img_name))
	{
		//thumbnail("adjunct/mms/thum/".$img_name,"adjunct/mms/thum1/".$img_name,"",330,500,"");
		$show_img = "http://www.kiam.kr/adjunct/mms/thum/".$img_name;
		?>
        <script language="javascript">
		window.parent.document.getElementsByName('upimage_str')[<?=$_REQUEST[i]?>].value='<?=$show_img?>';
		window.parent.type_check();
		</script>
        <?
	}
}
?>
