<?
$path = "./";
include_once $path . "lib/rlatjd_fun.php";
?>
<!DOCTYPE HTML>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=yes" />
	<title>업로드파일</title>
	<link href='/css/nanumgothic.css' rel='stylesheet' type='text/css' />
</head>
<?
$up_cnt = 10;
if (!$_SESSION['one_member_id']) {
?>
	<script language="javascript">
		alert('로그아웃되었습니다.로그인후 다시 시도해주세요.')
		window.parent.location.replace("/");
	</script>
<?
}
//삭제파일
if ($_REQUEST['del']) {
?>
	<script language="javascript">
		var upload_arry = window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid').value.split("\n");
		var upload_arry2 = window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid_2').value.split("\n");
		upload_arry.splice(<?= $_GET['arry_no'] ?>, 1);
		upload_arry2.splice(<?= $_GET['arry_no'] ?>, 1);
		window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid').value = upload_arry.join("\n");
		window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid_2').value = upload_arry2.join("\n");
	</script>
<?
	//@unlink($_SERVER['DOCUMENT_ROOT']."/".$thum."/".$_REQUEST['up_path']."/".$_REQUEST['name']);
	//@unlink($_SERVER['DOCUMENT_ROOT']."/".$thum."1/".$_REQUEST['up_path']."/".$_REQUEST['name']);
	//@unlink($_SERVER['DOCUMENT_ROOT']."/".$thum."2/".$_REQUEST['up_path']."/".$_REQUEST['name']);	
}
?>
<script language="javascript">
	if (window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid').value)
		var hid_cnt = parseInt(window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid').value.split("\n").length);
	else
		var hid_cnt = 0;
	var xz_cnt = parseInt('<?= $up_cnt ?>') - hid_cnt;
</script>

<body style="margin:0; padding:0; font-size:12px;">
	<form name="uploadform" method="post" action="upload.php" enctype="multipart/form-data">
		<input type="hidden" name="up_path" value="<?= $_REQUEST['up_path'] ?>" />
		<input type="hidden" name="category_id" value="<?= $_REQUEST['category_id'] ?>" />
		<input type="hidden" name="frm" value="<?= $_REQUEST['frm'] ?>" />
		<input type="file" name="uploadfile1" style="width:60px; height:25px; position:absolute;cursor:hand;left:5px;top:0px;filter:alpha(opacity=0);opacity:0;" hidefocus="hidefocus" onChange="if(xz_cnt<1){alert('첨부가능 개수를 초과했습니다.')}else{uploadform.submit();}" />
		<div>
			<div style="float:left;"><img src="images/email_34.gif"></div>
			<div style="float:left; margin-top:5px;">추가첨부 가능개수: <span id='up_cnt' style="color:#F00"></span></div>
			<p style="clear:both;"></p>
		</div>
		<?
		if ($_FILES['uploadfile1']['name']) {
			if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum/" . $_REQUEST['up_path'])) {
				mkdir($_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum/" . $_REQUEST['up_path']);
				mkdir($_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum1/" . $_REQUEST['up_path']);
				mkdir($_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum2/" . $_REQUEST['up_path']);
			}
			$tempFile = $_FILES['uploadfile1']['tmp_name'];
			$file_arr = explode(".", $_FILES['uploadfile1']['name']);
			$tmp_file_arr = explode("/", $tempFile);
			$img_name = $member_1['mem_code'] . "_" . date("Ymds") . "_" . $tmp_file_arr[count($tmp_file_arr) - 1] . "." . $file_arr[count($file_arr) - 1];
			$fileParts = pathinfo($img_name);
			if (in_array($_REQUEST['category_id'], $file_yx_arr)) {
				if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
					move_uploaded_file($tempFile, $_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum/" . $_REQUEST['up_path'] . "/" . $img_name);
					thumbnail($_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum/" . $_REQUEST['up_path'] . "/" . $img_name, $_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum1/" . $_REQUEST['up_path'] . "/" . $img_name, "", 900, 900, "");
					thumbnail($_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum/" . $_REQUEST['up_path'] . "/" . $img_name, $_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum2/" . $_REQUEST['up_path'] . "/" . $img_name, "", 150, 150, "");
				} else
					move_uploaded_file($tempFile, $_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum/" . $_REQUEST['up_path'] . "/" . $img_name);
		?>
				<script language="javascript">
					var file_name = "<?= $_FILES['uploadfile1']['name'] ?>";
					var file_name_2 = "<?= $img_name ?>";
					var file_name_arr_1 = [];
					var file_name_arr_2 = [];
					if (window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid').value) {
						var op_hid_arr_1 = window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid').value.split("\n");
						var op_hid_arr_2 = window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid_2').value.split("\n");
						for (var k = 0; k < op_hid_arr_1.length; k++) {
							file_name_arr_1.push(op_hid_arr_1[k]);
							file_name_arr_2.push(op_hid_arr_2[k]);
						}
					}
					file_name_arr_1.push(file_name);
					file_name_arr_2.push(file_name_2);
					window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid').value = file_name_arr_1.join("\n");
					window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid_2').value = file_name_arr_2.join("\n");
				</script>
				<?
			} else {
				if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
					move_uploaded_file($tempFile, $_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum/" . $_REQUEST['up_path'] . "/" . $img_name);
					thumbnail($_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum/" . $_REQUEST['up_path'] . "/" . $img_name, $_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum1/" . $_REQUEST['up_path'] . "/" . $img_name, "", 900, 900, "");
					thumbnail($_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum/" . $_REQUEST['up_path'] . "/" . $img_name, $_SERVER['DOCUMENT_ROOT'] . "/adjunct/board/thum2/" . $_REQUEST['up_path'] . "/" . $img_name, "", 150, 150, "");
				?>
					<script language="javascript">
						var file_name = "<?= $_FILES['uploadfile1']['name'] ?>";
						var file_name_2 = "<?= $img_name ?>";
						var file_name_arr_1 = [];
						var file_name_arr_2 = [];
						if (window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid').value) {
							var op_hid_arr_1 = window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid').value.split("\n");
							var op_hid_arr_2 = window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid_2').value.split("\n");
							for (var k = 0; k < op_hid_arr_1.length; k++) {
								file_name_arr_1.push(op_hid_arr_1[k]);
								file_name_arr_2.push(op_hid_arr_2[k]);
							}
						}
						file_name_arr_1.push(file_name);
						file_name_arr_2.push(file_name_2);
						window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid').value = file_name_arr_1.join("\n");
						window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid_2').value = file_name_arr_2.join("\n");
					</script>
				<?
				} else {
				?>
					<script language="javascript">
						alert('업로드 가능한 파일이 아닙니다.')
					</script>
		<?
				}
			}
		}
		?>
		<div id='<?= $_REQUEST['frm'] ?>_img_html' style="text-align:left;"></div>
		<script language="javascript">
			var img_str = "";
			var fileTypes = 'jpg,jpeg,gif,png';
			if (window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid').value) {
				var arr_img = window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid_2').value.split("\n");
				var arr_memo = window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_memo_hid').value.split("\n");
				for (var i = 0; i < arr_img.length; i++) {
					var arr_img2 = arr_img[i].split(".");
					if (fileTypes.indexOf(arr_img2[arr_img2.length - 1].toLocaleLowerCase()) == -1)
						var img_title = "/images/file_xk.jpg";
					else
						var img_title = "/adjunct/board/thum2/<?= $_REQUEST['up_path'] ?>/" + arr_img[i];
					var memo = arr_memo[i] ? arr_memo[i] : "";
					img_str += '<div style="float:left;margin-right:2px;"><a href="javascript:void(0)" style="background-color:#FFF;border:1px solid #CCC;position:absolute;" onclick="if(confirm(\'삭제하시겠습니까?\')){location.replace(\'upload.php?arry_no=' + i + '&del=sss&up_path=<?= $_REQUEST['up_path'] ?>&frm=<?= $_REQUEST['frm'] ?>&category_id=<?= $_REQUEST['category_id'] ?>&name=' + arr_img[i] + '\');}">x</a><img src="' + img_title + '" width="40" height="40" /><div><input type="text" tabindex="' + (i + 1) + '" value="' + memo + '" name="adjunct_memo" style="width:40px;height:10px;"/></div></div>';
				}
				document.getElementById('<?= $_REQUEST['frm'] ?>_img_html').innerHTML = img_str;
			}
			window.onload = function() {
				if (window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid').value)
					hid_cnt = parseInt(window.parent.document.getElementById('<?= $_REQUEST['frm'] ?>_img_hid').value.split("\n").length);
				else
					hid_cnt = 0;
				xz_cnt = parseInt('<?= $up_cnt ?>') - hid_cnt;
				document.getElementById('up_cnt').innerHTML = xz_cnt;
			}
		</script>
	</form>
</body>

</html>