<?php include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";?>
<?php
# 설정파일을 php자체 에서도 바꿀 수 있음(이 파일 위해서만), 1은 on을 의미, display_errors는 디버그
$uploaddir = '../../upload/';

$mode = $_POST['mode'];
$mem_id = $_POST['mem_id'];
$card_idx = $_POST['card_idx'];

$story_title1 = str_replace("'", "\'", $_POST['story_title1']);
$story_title2 = str_replace("'", "\'", $_POST['story_title2']);
$story_title3 = str_replace("'", "\'", $_POST['story_title3']);
$story_title4 = str_replace("'", "\'", $_POST['story_title4']);

$story_myinfo = str_replace("'", "\'", $_POST['story_myinfo']);
$story_company = str_replace("'", "\'", $_POST['story_company']);
$story_career = str_replace("'", "\'", $_POST['story_career']);
$story_online1_text = $_POST['story_online1_text'];
$story_online2_text = $_POST['story_online2_text'];

$story_online1 = $_POST['story_online1'];
$story_online2 = $_POST['story_online2'];

$online1_check = $_POST['online1_check'];
$online2_check = $_POST['online2_check'];
# name값이 userfile인 input에 들어있는 name이 파일이름이 되게 해라(uploaddir에 추가)
# basename은 파일 이름이 myapp\black.png 일때 black만 뽑아내서 파일이름이 되게 함

// echo $card_name;
# 배열 같은게 각 줄마다 나와서 보기좋게 함

if(!$mem_id){
  echo '로그아웃 되었습니다. 다시 로그인을 해주세요.';
  exit;
}

if(!$card_idx) {
  echo '잘못된 접근입니다. 다시시도 해주세요.';
  exit;
}
$sql2="update Gn_Iam_Name_Card set story_title1 = '$story_title1', story_title2 = '$story_title2', story_title3 = '$story_title3', story_myinfo = '$story_myinfo',".
    " story_company = '$story_company', story_career = '$story_career',up_data=now() where idx = '$card_idx' and mem_id = '$mem_id'";
$result2 = mysqli_query($self_con,$sql2) or die(mysqli_error($self_con));
echo '스토리 등록이 완료 되었습니다.';
// echo '{"result":"success","msg":"'.$img_url1.'|'.$img_url2.'|'.$img_url3.'|'.$img_url4.'"}';
exit;
?>
