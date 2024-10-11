<?include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";?>
<?php
# 설정파일을 php자체 에서도 바꿀 수 있음(이 파일 위해서만), 1은 on을 의미, display_errors는 디버그

$mode = $_POST['mode'];
$card_idx = $_POST['card_idx'];
$friends_url = $_POST['card_url'];

if($mode == "del") {
  if($_SESSION['iam_member_id'] == "") {
    echo '로그아웃 되었습니다. 다시 로그인을 해주세요.';
    exit;
  }
  if(count($_POST['friends_idx']) == 0) {
    echo '삭제할 프렌즈를 선택해 주세요';
    exit;
  }
  for($i=0; $i<count($_POST['friends_idx']); $i++) {
    $friends_idx = $_POST['friends_idx'][$i];
    // exit;
    $sql="delete from Gn_Iam_Friends where idx = $friends_idx and mem_id = '{$_SESSION['iam_member_id']}'";
    $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
  }
  echo '프렌즈가 삭제 되었습니다.';
  exit;
} 
else if($mode == "add_one"){
  if($_SESSION['iam_member_id'] == "" ) {
    echo '로그아웃 되었습니다. 다시 로그인을 해주세요.';
    exit;
  }
  if(!$card_idx){
    echo '잘못된 접근입니다. 다시시도 해주세요';
    exit;
  }
  $sql5="select count(idx) from Gn_Iam_Friends where mem_id = '{$_SESSION['iam_member_id']}' and friends_card_idx = '$card_idx'";
  $result5=mysqli_query($self_con,$sql5);
  $comment_row5=mysqli_fetch_array($result5);

  if($comment_row5[0]) {
    echo '추가했던 프렌즈입니다.';
    exit;
  }
  $sql3="select count(idx) from Gn_Iam_Friends where mem_id = '{$_SESSION['iam_member_id']}'";
  $result3=mysqli_query($self_con,$sql3);
  $comment_row=mysqli_fetch_array($result3);

  if((int)$comment_row[0] >= 5 && (int)$_SESSION[one_member_iam_leb] == 1) {
    echo '간편회원은 프렌즈를 5명까지만 등록할수 있습니다. 더 등록하시려면 정회원으로 가입해 주세요.';
    exit;
  }
//프렌즈 카드정보
  $sql="select mem_id, card_short_url, card_name, card_company, card_position, card_phone, main_img1 from Gn_Iam_Name_Card where idx = '$card_idx'";
  $result=mysqli_query($self_con,$sql);
  $row=mysqli_fetch_array($result);

  $mem_id = $row['mem_id'];
  $friends_url = 'kiam.kr/?' .$row[card_short_url];
  $friends_name = $row[card_name];
  $friends_company = $row[card_company];
  $friends_position = $row[card_position];
  $friends_phone = $row[card_phone];
  $friends_img = $row[main_img1];

  //프렌즈 카드 저장
  $sql4="insert into Gn_Iam_Friends (mem_id, friends_card_idx, friends_name, friends_company, friends_position, friends_phone, friends_img, friends_url, req_data) 
  values ('{$_SESSION['iam_member_id']}', '$card_idx', '$friends_name', '$friends_company', '$friends_position', '$friends_phone', '$friends_img', '$friends_url', now())";
  $result4 = mysqli_query($self_con,$sql4) or die(mysqli_error($self_con));



  //내정보 얻기
    $sql5="select idx, mem_id, main_img1, card_short_url, card_name, card_company, card_position, card_phone from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' LIMIT 1";
    $result5=mysqli_query($self_con,$sql5);

    $row5 = mysqli_fetch_array($result5);


    // echo $sql5."<br>";
    // printr($rows);

    $my_card_idx = $row5['idx'];
    $my_mem_id = $row5['mem_id'];
    $my_friends_url = 'kiam.kr/?' .$row5[card_short_url];
    $my_friends_name = $row5[card_name];
    $my_friends_company = $row5[card_company];
    $my_friends_position = $row5[card_position];
    $my_friends_phone = $row5[card_phone];
    $my_friends_img = $row5[main_img1];


    
    $sql8="select count(idx) from Gn_Iam_Friends where mem_id = '$mem_id' and friends_card_idx = '$my_card_idx'";
    $result8=mysqli_query($self_con,$sql8);
    $comment_row8=mysqli_fetch_array($result8);

    if(!$comment_row8[0]) {
      $sql7="insert into Gn_Iam_Friends (mem_id, friends_card_idx, friends_name, friends_company, friends_position, friends_phone, friends_img, friends_url, req_data) 
      values ('$mem_id', '$my_card_idx', '$my_friends_name', '$my_friends_company', '$my_friends_position', '$my_friends_phone', '$my_friends_img', '$my_friends_url', now())";
      $result7 = mysqli_query($self_con,$sql7) or die(mysqli_error($self_con));
    }

    echo $friends_name.'님 프렌즈로 추가 되었습니다.';
    exit;
  


    
}
else if($mode == "add_multi"){
  if($_SESSION['iam_member_id'] == "") {
    echo '로그아웃 되었습니다. 다시 로그인을 해주세요.';
    exit;
  }

  if(count($_POST['friends_idx']) == 0) {
    echo '등록할 프렌즈를 선택해 주세요';
    exit;
  }

  for($i=0; $i<count($_POST['friends_idx']); $i++) {
    $card_idx = $_POST['friends_idx'][$i];

    
    $sql5="select count(idx) from Gn_Iam_Friends where mem_id = '{$_SESSION['iam_member_id']}' and friends_card_idx = '$card_idx'";
    $result5=mysqli_query($self_con,$sql5);
    $comment_row5=mysqli_fetch_array($result5);

    if($comment_row5[0]) {
      // echo "이미추가<br>";
      continue;
    }




    //프렌즈 카드정보
    $sql="select mem_id, card_short_url, card_name, card_company, card_position, card_phone, main_img1 from Gn_Iam_Name_Card where idx = '$card_idx'";
    $result=mysqli_query($self_con,$sql);
    $row=mysqli_fetch_array($result);

    $mem_id = $row['mem_id'];
    $friends_url = 'kiam.kr/?' .$row[card_short_url];
    $friends_name = $row[card_name];
    $friends_company = $row[card_company];
    $friends_position = $row[card_position];
    $friends_phone = $row[card_phone];
    $friends_img = $row[main_img1];

    if($mem_id == $_SESSION['iam_member_id']){
      // echo "me<br>";
      continue;
    }


    //프렌즈 카드 저장
    $sql4="insert into Gn_Iam_Friends (mem_id, friends_card_idx, friends_name, friends_company, friends_position, friends_phone, friends_img, friends_url, req_data) 
    values ('{$_SESSION['iam_member_id']}', '$card_idx', '$friends_name', '$friends_company', '$friends_position', '$friends_phone', '$friends_img', '$friends_url', now())";
    $result4 = mysqli_query($self_con,$sql4) or die(mysqli_error($self_con));


  

    //내정보 얻기
    $sql5="select idx, mem_id, main_img1, card_short_url, card_name, card_company, card_position, card_phone from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' LIMIT 1";
    $result5=mysqli_query($self_con,$sql5);

    $row5 = mysqli_fetch_array($result5);


    // echo $sql5."<br>";
    // printr($rows);

    $my_card_idx = $row5['idx'];
    $my_mem_id = $row5['mem_id'];
    $my_friends_url = 'kiam.kr/?' .$row5[card_short_url];
    $my_friends_name = $row5[card_name];
    $my_friends_company = $row5[card_company];
    $my_friends_position = $row5[card_position];
    $my_friends_phone = $row5[card_phone];
    $my_friends_img = $row5[main_img1];


    
    $sql8="select count(idx) from Gn_Iam_Friends where mem_id = '$mem_id' and friends_card_idx = '$my_card_idx'";
    $result8=mysqli_query($self_con,$sql8);
    $comment_row8=mysqli_fetch_array($result8);

    if(!$comment_row8[0]) {
      $sql7="insert into Gn_Iam_Friends (mem_id, friends_card_idx, friends_name, friends_company, friends_position, friends_phone, friends_img, friends_url, req_data) 
      values ('$mem_id', '$my_card_idx', '$my_friends_name', '$my_friends_company', '$my_friends_position', '$my_friends_phone', '$my_friends_img', '$my_friends_url', now())";
      $result7 = mysqli_query($self_con,$sql7) or die(mysqli_error($self_con));

    }
      




    //$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
  }

  // echo count($_POST['friends_idx'])."명 추가되었습니다.";
  echo "친구로 추가되었습니다.";

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