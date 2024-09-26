<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 아이엠 정보 수정
*/
extract($_POST);

$mem_id = $_POST['mem_id'];
$memid = $_POST['memid'];
$mem_name = $_POST['mem_name'];
$db_source = $_POST['db_source'];
$info = $_POST['info'];
$company = $_POST['company'];
$rID = $_POST['rID'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$homepage = $_POST['homepage'];
$address = $_POST['address'];
$apply_link = $_POST['apply_link'];
$query="update Gn_Iam_automem set memid = '$memid',
                                    mem_name = '$mem_name',
                                    db_source = '$db_source',
                                    profile_self_info = '$info',
                                    profile_company = '$company',
                                    recommend_id = '$rID',
                                    profile_telno = '$phone',
                                    profile_email = '$email',
                                    profile_homepage = '$homepage',
                                    profile_address = '$address',
                                    apply_link = '$apply_link',
                                    reg_date=now()
        where memid = '$mem_id'";
mysqli_query($self_con, $query);

echo json_encode(array("result"=>$result));
?>