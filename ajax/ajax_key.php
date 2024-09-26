<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
if($mode == "creat"){
    $query = "insert into Gn_Search_Key set key_type  ='',key_content ='',key_id=''";
    mysqli_query($self_con, $query);
}else if($mode == "edit") {
    if($key_id == "gpt_answer_example" || $key_id == "gpt_question_example" || $key_id == "daily_msg_contents" || $key_id == "gwc_req_alarm"){
        $key_content = htmlspecialchars($key_content);
    }
    $query = "update  Gn_Search_Key set key_type  ='$key_type',key_content ='$key_content',key_id='$key_id' WHERE no='$no'";
    mysqli_query($self_con, $query);
    echo "<script>alert('저장되었습니다');location='/admin/iam_search_key.php';</script>";
}
?>