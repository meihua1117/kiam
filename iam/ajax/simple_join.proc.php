<?php
include $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
# 설정파일을 php자체 에서도 바꿀 수 있음(이 파일 위해서만), 1은 on을 의미, display_errors는 디버그
$uploaddir = '../../upload/';

$mode = $_POST['mode'];
$rnum = $_POST['rnum'];
$id_che = trim($_POST['id']);
$simple_phone = $_POST['simple_phone'];

# name값이 userfile인 input에 들어있는 name이 파일이름이 되게 해라(uploaddir에 추가)
# basename은 파일 이름이 myapp\black.png 일때 black만 뽑아내서 파일이름이 되게 함

// echo $card_name;
# 배열 같은게 각 줄마다 나와서 보기좋게 함

// if(!$rnum){
//   echo '잘못된 접근입니다. 다시 시도 해주세요.';
//   exit;
// }

if($mode == "join") {
  if(!$id_che) {
    echo '잘못된 접근입니다. 다시 시도 해주세요.';
    exit;
  }
  if($simple_phone == "--" || $simple_phone == "") {
    echo '잘못된 접근입니다. 다시 시도 해주세요.';
    exit;
  }

  $sql="select mem_id from Gn_Member where mem_phone='$simple_phone'";
  $resul=mysqli_query($self_con,$sql);
  $row=mysqli_fetch_array($resul);

  if($row[mem_id]) {
    echo '이미 가입되어있는 회원입니다.';
    exit;
  } else {
    $memid = explode('-',$simple_phone);
    $mem_id = $memid[0].$memid[1].$memid[2];
    $mem_pass = md5($mem_id);
    $ip=$_SERVER['REMOTE_ADDR'];

    $sql2="insert into Gn_Member (mem_leb, mem_id, mem_pass, web_pwd, mem_phone, first_regist, join_ip, iam_leb) values ('22', '$id_che', '$mem_pass', password('$mem_id'), '$simple_phone', now(), '$ip', 1)";
    $result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));

    $_SESSION[one_member_id]=$mem_id;
    $_SESSION[one_member_iam_leb]=1;

    echo '회원가입 되었습니다.';
    exit;
  }
} else if($mode == "id_check") {
  $sql="select mem_id from Gn_Member where mem_id='$id_che'";
  $resul=mysqli_query($self_con,$sql);
  $row=mysqli_fetch_array($resul);
  if($row[mem_id]) {
    echo '0';
    exit;
  } else {
    echo '1';
    exit;
  }
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
