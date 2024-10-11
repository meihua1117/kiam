<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

if($mode == "save")
{
    $seq = $_POST["seq"]; 
    
    if($seq == "") {
        $query="insert into Gn_app_version set 
                                    `app_code`  ='$app_code', 
                                    `app_version` ='$app_version', 
                                    createAt=NOW()
                                     ";                      
        mysqli_query($self_con,$query);	
    } else {
        $query="update Gn_app_version set 
                                    `app_code`  ='$app_code', 
                                    `app_version` ='$app_version', 
                                    createAt=NOW()
                             WHERE seq='$seq'
                                     ";
        mysqli_query($self_con,$query);		
    }
    echo "<script>alert('저장되었습니다.');location='/admin/app_manage_list.php';</script>";
}
else if($mode == "type")
{
    if($type != 0)
    {
        $query="update Gn_app_version set type = 0 where type = '$type'";
        mysqli_query($self_con,$query);
        $query="update Gn_app_version set 
                        `type`          ='$type', 
                        createAt=NOW()
                        WHERE seq='$seq'
                        ";
        mysqli_query($self_con,$query);
    }
    else{
        $query="update Gn_app_version set 
                        `type`          = 0, 
                        createAt=NOW()
                        WHERE seq='$seq'
                        ";
        mysqli_query($self_con,$query);
    }

}

exit;

?>