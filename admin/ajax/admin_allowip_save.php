<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$idx = $_POST["idx"]; 

if($mode == "save")
{
    if($idx == "") {
        $query="insert into gn_admin_allowip set 
                                    `ip`          ='{$ip}', 
                                    `mem_id`      ='{$mem_id}', 
                                    `name`      ='{$name}', 
                                    regdate=NOW()
                                        ";
        mysqli_query($self_con,$query);	
    } else {
        $query="update gn_admin_allowip set 
                                    `ip`          ='{$ip}', 
                                    `mem_id`      ='{$mem_id}', 
                                    `name`      ='{$name}', 
                                    regdate=NOW()
                                WHERE idx='{$idx}'
                                        ";
        mysqli_query($self_con,$query);		
    }
    echo "<script>alert('저장되었습니다.');location='/admin/admin_allowip_list.php';</script>";
}
else if($mode == "type")
{
    $issecure = $_POST["use_secure"];
    $query="update gn_conf set 
                    `secure_connect`          = '{$issecure}', 
                    regdate=NOW()
                    ";
    mysqli_query($self_con,$query);

}
exit;

?>