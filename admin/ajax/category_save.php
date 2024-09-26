<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$idx = $_POST["idx"]; 
if($_POST['mode'] == "inser") {
    
        
    $query="insert into Gn_Service set `service_name`          ='$service_name', 
                                  `domain`      ='$domain', 
                                  `sub_domain` ='$sub_domain', 
                                  `company_name` ='$company_name', 
                                  `manage_cell`   ='$manage_cell', 
                                  `manage_name`      ='$manage_name', 
                                  ceo_name = '$ceo_name',
                                  address ='$address',
                                  branch_type= '$branch_type',
                                  phone_cnt='$phone_cnt',
                                  branch_rate='$branch_rate',
                                  price='$price',
                                  logo='$logo',
                                  member_cnt='$member_cnt',
                                  main_default_yn='$main_default_yn',
                                  main_image='$main_image',
                                  footer_image='$footer_image',
                                  `status`          ='$status', 
                                  `regdate`         =NOW() 
                                 ";
    mysqli_query($self_con, $query);	
} else if($_POST['mode'] == "save") {

    
    $query="update      tjd_board_category set `category_text`          ='$category_text'
                                  
                         WHERE category='$category'
                                 ";
    mysqli_query($self_con, $query);	
    //echo $query;
    echo '{"result":"success","msg":"변경되었습니다."}';
    exit    ;
} else if($_POST['mode'] == "del") {

    $query="delete  from    Gn_Service 
                         WHERE idx='$idx'
                                 ";
    mysqli_query($self_con, $query);	
}
echo "<script>alert('저장되었습니다.');location='/admin/board_category.php';</script>";
exit;
?>