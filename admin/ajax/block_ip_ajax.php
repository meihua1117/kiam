<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

if($mode=="plus")
{
    $query = "select ip from gn_block_ip where ip='{$ip}'";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);
    if($row[0] == "")
    {
        $query = "insert into gn_block_ip (ip) values('{$ip}')";
        $res = mysqli_query($self_con,$query);
        echo $ip. "가 차단되었습니다.";
    }
    else
    {
        echo "이미 등록되었습니다";
    }
}
else if($mode == "minus")
{
    $query = "delete from gn_block_ip where ip='{$ip}'";
    $res = mysqli_query($self_con,$query);

    $query = "delete from gn_hist_login where ip='{$ip}'";
    $res = mysqli_query($self_con,$query);    
    echo $ip. "가 차단리스트에서 해지되었습니다.";
}else if($mode == "del")
{
    $query = "delete from gn_hist_login";
    $res = mysqli_query($self_con,$query);
    echo "전체 삭제되었습니다.";   
}


?>