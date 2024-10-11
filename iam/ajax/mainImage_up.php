<?include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";?>
<?include_once $_SERVER['DOCUMENT_ROOT']."/lib/class.image.php";?>
<?php
# 설정파일을 php자체 에서도 바꿀 수 있음(이 파일 위해서만), 1은 on을 의미, display_errors는 디버그
$up_dir = make_folder_month(2);
if($up_dir != ''){
    $uploaddir = '../..'.$up_dir;
} else {
    $uploaddir = '../../upload/';
    $up_dir = "/upload/";
}
$mode = $_POST['mode'];

$date_file_name1 = date('dmYHis').str_replace(" ", "", basename($_FILES["uploadFile1"]["name"]));
$date_file_name1 = str_replace("'", "", $date_file_name1);
$date_file_name1 = str_replace('"', "", $date_file_name1);
$date_file_name2 = date('dmYHis').str_replace(" ", "", basename($_FILES["uploadFile2"]["name"]));
$date_file_name2 = str_replace("'", "", $date_file_name2);
$date_file_name2 = str_replace('"', "", $date_file_name2);
$date_file_name3 = date('dmYHis').str_replace(" ", "", basename($_FILES["uploadFile3"]["name"]));
$date_file_name3 = str_replace("'", "", $date_file_name3);
$date_file_name3 = str_replace('"', "", $date_file_name3);
$video_name = basename($_FILES["uploadVideo"]["name"]);
$pos = strrpos($video_name,".");
$video_name = substr($video_name,$pos);
$date_video_name = date('dmYHis').$video_name;
$date_video_name = str_replace("'", "", $date_video_name);
$date_video_name = str_replace('"', "", $date_video_name);

$uploadfile1 = $uploaddir.basename($date_file_name1);
$uploadfile2 = $uploaddir.basename($date_file_name2);
$uploadfile3 = $uploaddir.basename($date_file_name3);
$uploadVideo = $uploaddir.basename($date_video_name);
# name값이 userfile인 input에 들어있는 name이 파일이름이 되게 해라(uploaddir에 추가)
# basename은 파일 이름이 myapp\black.png 일때 black만 뽑아내서 파일이름이 되게 함
if($mode == "up") {
  //Julian added
  $sql_1 = "select * from Gn_Iam_Name_Card where idx = '$_POST[name_card_idx]'";
  $result_1=mysqli_query($self_con,$sql_1);
  $cardInfo=mysqli_fetch_array($result_1);
  $img_url1 = $cardInfo['main_img1'];
  $img_url2 = $cardInfo['main_img2'];
  $img_url3 = $cardInfo['main_img3'];
  $video_link = $cardInfo['video'];
  if(move_uploaded_file($_FILES['uploadFile1']['tmp_name'], $uploadfile1)){
    $img_url1 = "http://www.kiam.kr".$up_dir.basename($date_file_name1);
    $handle = new Image($uploadfile1, 800);
    $handle->resize();
    uploadFTP($uploadfile1);
  }else if($_POST['uploadFile1_link'] != ""){
    $img_url1 = $_POST['uploadFile1_link'];
  }
  if(move_uploaded_file($_FILES['uploadFile2']['tmp_name'], $uploadfile2)){
    $img_url2 = "http://www.kiam.kr".$up_dir.basename($date_file_name2);
    $handle = new Image($uploadfile2, 800);
    $handle->resize();
    uploadFTP($uploadfile2);
  }else if($_POST['uploadFile2_link'] != ""){
    $img_url2 = $_POST['uploadFile2_link'];
  }

  if(move_uploaded_file($_FILES['uploadFile3']['tmp_name'], $uploadfile3)){
    $img_url3 = "http://www.kiam.kr".$up_dir.basename($date_file_name3);
    $handle = new Image($uploadfile3, 800);
    $handle->resize();
    uploadFTP($uploadfile3);
  }else if($_POST['uploadFile3_link'] != ""){
    $img_url3 = $_POST['uploadFile3_link'];
  }
  if(move_uploaded_file($_FILES['uploadVideo']['tmp_name'], $uploadVideo)){
    $video_link = "http://www.kiam.kr".$up_dir.basename($date_video_name);
    uploadFTP($uploadVideo);
  }else if($_POST['uploadVideo_link'] != ""){
    $video_link = $_POST['uploadVideo_link'];
  }
  if(!empty($_POST['name_card_idx']) && $_POST['name_card_idx'] != 0){
    $sql_chk = "select idx from Gn_Iam_Name_Card where share_send_card='{$_POST['name_card_idx']}'";
    $res_chk = mysqli_query($self_con,$sql_chk);
    $cnt_chk = mysqli_num_rows($res_chk);
    if($cnt_chk){
	while($row_chk = mysqli_fetch_array($res_chk)){
    	    $sql_new = "update Gn_Iam_Name_Card set main_img1 = '$img_url1', main_img2 = '$img_url2', main_img3 = '$img_url3',video = '$video_link',up_data=now() where idx = '$row_chk[idx]'";
    	    mysqli_query($self_con,$sql_new) or die(mysqli_error($self_con));
	}
    }
  }
  $sql_new = "update Gn_Iam_Name_Card set main_img1 = '$img_url1', main_img2 = '$img_url2', main_img3 = '$img_url3',video = '$video_link',up_data=now() where idx = '$_POST[name_card_idx]'";
  mysqli_query($self_con,$sql_new) or die(mysqli_error($self_con));
  echo "$_POST[name_card_idx]"." - Gn_Iam_Name_Card is changed.";
  exit;
} else if($mode=="del"){
  $img_name = $_POST['img_name'];
  $msg = '대표이미지가 삭제 되었습니다.';
  if($img_name == "main_img1") {
    $sql="update Gn_Iam_Name_Card set main_img1 = '', up_data = now() where idx = '$_POST[name_card_idx]'";
  } else if($img_name == "main_img2") {
    $sql="update Gn_Iam_Name_Card set main_img2 = '', up_data = now() where idx = '$_POST[name_card_idx]'";
  } else if($img_name == "main_img3") {
    $sql="update Gn_Iam_Name_Card set main_img3 = '', up_data = now() where idx = '$_POST[name_card_idx]'";
  }else if($img_name == "video") {
    $sql="update Gn_Iam_Name_Card set video = '', up_data = now() where idx = '$_POST[name_card_idx]'";
    $msg = '영상슬라이드가 삭제 되었습니다.';
  }
  $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
  echo $msg;
  exit;
} else if($mode=="slide"){
  $sql = "update Gn_Iam_Name_Card set video_status = '{$_POST['status']}', up_data = now() where idx = '$_POST[name_card_idx]'";
  $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
  echo "success";
  exit;
}
?>
