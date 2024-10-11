<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$idx = $_POST["idx"]; 
if($_POST['mode'] == "inser") {
    $logo = gcUploadRename($_FILES['logo']["name"],$_FILES["logo"]['tmp_name'],$_FILES["logo"]['size'], "data/site");    
    $main_image = gcUploadRename($_FILES['main_image']["name"],$_FILES["main_image"]['tmp_name'],$_FILES["main_image"]['size'], "data/site");    
    $footer_image = gcUploadRename($_FILES['footer_image']["name"],$_FILES["footer_image"]['tmp_name'],$_FILES["footer_image"]['size'], "data/site");    
        
    $query="insert into Gn_Service set `service_name`          ='$service_name', 
                                  `domain`      ='$domain', 
                                  `sub_domain` ='$sub_domain', 
                                  `company_name` ='$company_name', 
                                  `manage_cell`   ='$manage_cell', 
                                  `manage_name`      ='$manage_name',
								  `communications_vendors` ='$communications_vendors',
								  `privacy` ='$privacy',
								  `fax` ='$fax',
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
                                  
                                  contract_start_date='$contract_start_date',
                                  contract_end_date='$contract_end_date',
                                  mem_id='$mem_id',
                                  mem_name='$mem_name',
                                  site_name='$site_name',
                                  `naver-site-verification`='".$_POST['naver-site-verification']."',
                                  keywords='$keywords',
								  kakao='$kakao',
								  Consultation Request='$ConsultationRequest',                          
                                  
                                  `status`          ='$status', 
                                  `regdate`         =NOW() 
                                 ";
    mysqli_query($self_con,$query);	
} else if($_POST['mode'] == "updat") {
    $logo = gcUploadRename($_FILES['logo']["name"],$_FILES["logo"]['tmp_name'],$_FILES["logo"]['size'], "data/site");    
    $main_image = gcUploadRename($_FILES['main_image']["name"],$_FILES["main_image"]['tmp_name'],$_FILES["main_image"]['size'], "data/site");    
    $footer_image = gcUploadRename($_FILES['footer_image']["name"],$_FILES["footer_image"]['tmp_name'],$_FILES["footer_image"]['size'], "data/site");    
    $addQuery = "";
    if($logo)
        $addQuery .= "logo='$logo',";
    if($main_image)
        $addQuery .= "main_image='$main_image',";
    if($footer_image)
        $addQuery .= "footer_image='$footer_image',";
    
    $query="update      Gn_Service set `service_name`          ='$service_name', 
                                  `domain`      ='$domain', 
                                  `company_name` ='$company_name', 
                                  `sub_domain` ='$sub_domain', 
                                  `manage_cell`   ='$manage_cell', 
                                  `manage_name`      ='$manage_name',
								  `communications_vendors` ='$communications_vendors',
								  `privacy` ='$privacy',
								  `fax` ='$fax', 
                                  ceo_name = '$ceo_name',
                                  address ='$address',
                                  branch_type= '$branch_type',
                                  phone_cnt='$phone_cnt',
                                  main_default_yn='$main_default_yn',
                                  branch_rate='$branch_rate',
                                  member_cnt='$member_cnt',

                                  contract_start_date='$contract_start_date',
                                  contract_end_date='$contract_end_date',
                                  mem_id='$mem_id',
                                  mem_name='$mem_name',
                                  site_name='$site_name',
                                  `naver-site-verification`='".$_POST['naver-site-verification']."',
                                  keywords='$keywords', 
								  kakao='$kakao',
								  Consultation Request='$ConsultationRequest',                                 
                                  price='$price',
                                  $addQuery
                                  `status`          ='$status'
                                  
                         WHERE idx='$idx'
                                 ";
    mysqli_query($self_con,$query);	
} else if($_POST['mode'] == "del") {

    $query="delete  from    Gn_Service 
                         WHERE idx='$idx'
                                 ";
    mysqli_query($self_con,$query);	
}
echo "<script>alert('저장되었습니다.');location='/admin/service_list.php';</script>";
exit;
?>