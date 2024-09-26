<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 알림 정보 수정
*/
extract($_POST);
$uploaddir = '../../upload/';
$no = $_POST["no"];
if($_POST['mode'] == "create") {
    $icon_file_name = date('dmYHis').str_replace(" ", "", basename($_FILES["img"]["name"]));
    $icon_file_name = str_replace("'", "", $icon_file_name);
    $icon_file_name = str_replace('"', "", $icon_file_name);
    $uploadfile = $uploaddir.basename($icon_file_name);
    if(move_uploaded_file($_FILES['img']['tmp_name'], $uploadfile)){
        $img_url = "http://www.kiam.kr/upload/".basename($icon_file_name);
        uploadFTP($uploadfile);
    }
    $sql="insert into gn_alert (`type`, title, pos, img,`desc`,link) values ('$type', '$title','$pos', '$img_url','$desc','$link')";
    mysql_query($sql) or die(mysql_error());
}
else if($_POST['mode'] == "update") {
    $icon_file_name = str_replace(" ", "", basename($_FILES["img"]["name"]));
    if($icon_file_name != "") {
        $icon_file_name = date('dmYHis').$icon_file_name;
        $icon_file_name = str_replace("'", "", $icon_file_name);
        $icon_file_name = str_replace('"', "", $icon_file_name);
        $uploadfile = $uploaddir.basename($icon_file_name);
        if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadfile)) {
            $img_url = "http://www.kiam.kr/upload/" . basename($icon_file_name);
            uploadFTP($uploadfile);
        }
        $sql="update gn_alert set title='$title', pos = '$pos', img = '$img_url',`desc` = '$desc',link = '$link' where no = '$no'";
    }else{
        $sql="update gn_alert set title='$title', pos = '$pos', `desc` = '$desc',link = '$link' where no = '$no'";
    }
    mysql_query($sql) or die(mysql_error());
} else if($_POST['mode'] == "delete") {
    $query="delete  from  gn_alert WHERE no='$no'";
    mysql_query($query);	
} else if($_POST['mode'] == "get"){
    $pos = $_POST['pos'];
    if($pos == "")
	$pos = "my_info";
    $sql = "select * from gn_alert where pos = '$pos'";
    $row = mysql_fetch_array(mysql_query($sql));
    if($row['pos'] != "") {
        echo json_encode(array("title" => $row[title], "img" => $row[img], "desc" => $row[desc], "link" => $row[link]));
    }
    exit;
}
if($type == "iam")
    echo "<script>alert('저장되었습니다.');location='/admin/iam_alert_list.php';</script>";
else
    echo "<script>alert('저장되었습니다.');location='/admin/selling_alert_list.php';</script>";
exit;
?>