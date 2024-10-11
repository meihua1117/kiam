<?php include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";?>
<?php
# 설정파일을 php자체 에서도 바꿀 수 있음(이 파일 위해서만), 1은 on을 의미, display_errors는 디버그

$mode = $_POST['mode'];
$contact_idx = $_POST['contact_idx'];

// echo $_SESSION[one_member_iam_leb];
// // echo ($_SESSION[one_member_id] == "" || $_SESSION[one_member_iam_leb] == "");
// exit;
if($mode == "del") {
  // echo count($_POST['friends_idx']);
  // echo $_POST['friends_idx'][1];
  // echo $_POST['friends_idx'][2];

  if($_SESSION['iam_member_id'] == "") {
    echo '로그아웃 되었습니다. 다시 로그인을 해주세요.';
    exit;
  }

  if(count($_POST['contact_idx']) == 0) {
    echo '삭제할 프렌즈를 선택해 주세요';
    exit;
  }

  for($i=0; $i<count($_POST['contact_idx']); $i++) {
    $contact_idx = $_POST['contact_idx'][$i];
    // exit;
    $sql="delete from Gn_MMS_Receive_Iam where idx = $contact_idx and mem_id = '$_SESSION[iam_member_id]'";
    $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
  }

  echo '연락처가 삭제 되었습니다.';
  exit;
}



// print_r($_FILES['uploadFile1']['name']);
// echo '</pre>';
// print_r(basename($_FILES['uploadFile1']['name']));
// echo '</pre>';
// print_r($uploadfile1);
// echo '</pre>';
// print_r($uploadfile2);
// echo '</pre>';
// print_r($uploadfile3);
// echo '</pre>';
// print_r($uploadfile4);
// echo '</pre>';
// print_r($_FILES);
// echo '</pre>';
?>
