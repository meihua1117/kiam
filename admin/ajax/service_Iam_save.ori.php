<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }

  $name_count_sql="select count(idx) from Gn_Iam_Name_Card where card_short_url = '$randomString'";
  $name_count_result=mysql_query($name_count_sql);
  $name_count_row=mysql_fetch_array($name_count_result);

  if((int)$name_count_row[0]) {
    generateRandomString();
  } else {
    return $randomString;
  }
}
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);

$idx = $_POST["idx"];
if($_POST['mode'] == "create") {

    $head_logo = gcUploadRename($_FILES['head_logo']["name"],$_FILES["head_logo"][tmp_name],$_FILES["head_logo"][size], "data/site");
    $namecard_logo = gcUploadRename($_FILES['namecard_logo']["name"],$_FILES["namecard_logo"][tmp_name],$_FILES["namecard_logo"][size], "data/site");
    $main_img1 = gcUploadRename($_FILES['main_img1']["name"],$_FILES["main_img1"][tmp_name],$_FILES["main_img1"][size], "data/site");
    $main_img2 = gcUploadRename($_FILES['main_img2']["name"],$_FILES["main_img2"][tmp_name],$_FILES["main_img2"][size], "data/site");
    $main_img3 = gcUploadRename($_FILES['main_img3']["name"],$_FILES["main_img3"][tmp_name],$_FILES["main_img3"][size], "data/site");
    $footer_logo = gcUploadRename($_FILES['footer_logo']["name"],$_FILES["footer_logo"][tmp_name],$_FILES["footer_logo"][size], "data/site");

    $card_name = $pro_name;
    $card_phone = $pro_tel;
    $card_email = $pro_email;

    $card_addr1 = $pro_address;
    $card_company = $company_name;
    $card_position = $pro_group;
    $story_myinfo = $pro_self;
    $story_online1 = $pro_weblink1;
    $story_online2 = $pro_weblink2;

    $story_myinfo = $story_data;
    $story_company = $story_data2;
    $story_career = $story_data3;
    $profile_logo = $namecard_logo;

    $domain = $sub_domain;
    $parse = parse_url($sub_domain);
    $site = explode(".", $parse['host']);
    $site[0] = "iam_".$site[0];
    $query = "update Gn_Member set site = '$site[0]' where mem_id = '$mem_id'";
    mysql_query($query);
    $short_url = generateRandomString();
    $name_card_sql="insert into Gn_Iam_Name_Card (mem_id, card_short_url, card_name, card_phone, card_email, card_addr, profile_logo, req_data,up_data, card_position,story_online1,story_online2,card_company,story_online1_text,story_online2_text,domain,story_myinfo,story_company,story_career,main_img1,main_img2,main_img3)
        values ('$mem_id', '$short_url', '$card_name', '$card_phone', '$card_email', '$card_addr1', '$img_url', now(), now(),'$card_position','$story_online1','$story_online2','$card_company','','','$domain','$story_myinfo','$story_company','$story_career','$main_img1','$main_img2','$main_img3')";
    $name_card_result = mysql_query($name_card_sql) or die(mysql_error());
    $profile_idx = mysql_insert_id();
    $query="insert into Gn_Iam_Service set `main_domain`      ='$main_domain',
                                  `sub_domain` ='$sub_domain',
                                  mem_cnt ='$mem_cnt',
                                  iamcard_cnt ='$iamcard_cnt',
                                  send_content ='$send_content',
                                  my_card_cnt ='$my_card_cnt',
								  `communications_vendors` ='$communications_vendors',
                                  `privacy` ='$privacy',
                                  `email` ='$email',
                                  `company_name` ='$company_name',
                                  brand_name='$brand_name',
                                  business_number='$business_number',
                                  contract_start_date='$contract_start_date',
                                  contract_end_date='$contract_end_date',
                                  mem_id='$mem_id',
                                  mem_name='$mem_name',
                                  `owner_name`      ='$owner_name',
                                  `owner_cell`   ='$owner_cell',
                                  manager_name= '$manager_name',
                                  manager_tel= '$manager_tel',
                                  `fax` ='$fax',
                                  `status`          ='$status',
                                  address ='$address',
                                  web_theme='$web_theme',
                                  home_link='$home_link',
                                  head_logo='$head_logo',
                                  namecard_logo='$namecard_logo',
                                  pro_name='$pro_name',
                                  pro_group='$pro_group',
                                  pro_self='$pro_self',
                                  pro_tel='$pro_tel',
                                  pro_address='$pro_address',
                                  pro_email='$pro_email',
                                  pro_weblink1='$pro_weblink1',
                                  pro_weblink2='$pro_weblink2',
                                  story_data='$story_data',
                                  story_data2='$story_data2',
                                  story_data3='$story_data3',
                                  profile_idx='$profile_idx',
                                  footer_logo='$footer_logo',
                                  footer_link='$footer_link',
                                  share_price='$share_price',
                                  month_price='$month_price',
                                  main_img1='$main_img1',
                                  main_img2='$main_img2',
                                  main_img3='$main_img3',
                                  keywords='$keywords',
								  kakao='$kakao',
                                  pay_link='$pay_link',
                                  `regdate`=NOW() ";
    mysql_query($query);
}
elseif($_POST['mode'] == "insert") {
 
    $head_logo = gcUploadRename($_FILES['head_logo']["name"],$_FILES["head_logo"][tmp_name],$_FILES["head_logo"][size], "data/site");    
    $namecard_logo = gcUploadRename($_FILES['namecard_logo']["name"],$_FILES["namecard_logo"][tmp_name],$_FILES["namecard_logo"][size], "data/site");    
    $main_img1 = gcUploadRename($_FILES['main_img1']["name"],$_FILES["main_img1"][tmp_name],$_FILES["main_img1"][size], "data/site");    
    $main_img2 = gcUploadRename($_FILES['main_img2']["name"],$_FILES["main_img2"][tmp_name],$_FILES["main_img2"][size], "data/site");    
    $main_img3 = gcUploadRename($_FILES['main_img3']["name"],$_FILES["main_img3"][tmp_name],$_FILES["main_img3"][size], "data/site");   
    $cont_img1 = gcUploadRename($_FILES['cont_img1']["name"],$_FILES["cont_img1"][tmp_name],$_FILES["cont_img1"][size], "data/site");    
    $cont_img2 = gcUploadRename($_FILES['cont_img2']["name"],$_FILES["cont_img2"][tmp_name],$_FILES["cont_img2"][size], "data/site");    
    $cont_img3 = gcUploadRename($_FILES['cont_img3']["name"],$_FILES["cont_img3"][tmp_name],$_FILES["cont_img3"][size], "data/site");    
    $cont_img4 = gcUploadRename($_FILES['cont_img4']["name"],$_FILES["cont_img4"][tmp_name],$_FILES["cont_img4"][size], "data/site");    
    $cont_img5 = gcUploadRename($_FILES['cont_img5']["name"],$_FILES["cont_img5"][tmp_name],$_FILES["cont_img5"][size], "data/site");    
    $cont_img6 = gcUploadRename($_FILES['cont_img6']["name"],$_FILES["cont_img6"][tmp_name],$_FILES["cont_img6"][size], "data/site");     
    $footer_logo = gcUploadRename($_FILES['footer_logo']["name"],$_FILES["footer_logo"][tmp_name],$_FILES["footer_logo"][size], "data/site");   
  
   
    $card_name = $pro_name;
    $card_phone = $pro_tel;
    $card_email = $pro_email;
    

    $card_addr1 = $pro_address;
    $card_company = $company_name;
    $card_position = $pro_group;
    $story_myinfo = $pro_self;
    $story_online1 = $pro_weblink1;
    $story_online2 = $pro_weblink2;
    
    $story_myinfo = $story_data;
    $story_company = $story_data2;
    $story_career = $story_data3;    
    $profile_logo = $namecard_logo;
    
    $domain = $sub_domain;
    if($profile_idx == "" ||$profile_idx == 0) {
        $short_url = generateRandomString();
        
        $name_card_sql="insert into Gn_Iam_Name_Card (mem_id, card_short_url, card_name, card_phone, card_email, card_addr, profile_logo, req_data,up_data, card_position,story_online1,story_online2,card_company,story_online1_text,story_online2_text,domain,story_myinfo,story_company,story_career,main_img1,main_img2,main_img3) 
        values ('$mem_id', '$short_url', '$card_name', '$card_phone', '$card_email', '$card_addr1', '$img_url', now(), now(),'$card_position','$story_online1','$story_online2','$card_company','','','$domain','$story_myinfo','$story_company','$story_career','$main_img1','$main_img2','$main_img3')";
        $name_card_result = mysql_query($name_card_sql) or die(mysql_error());                    
        $profile_idx = mysql_insert_id();        
    } else {
        $name_card_sql="update Gn_Iam_Name_Card set card_name='$card_name', 
                                                    card_phone='$card_phone', 
                                                    card_email='$card_email', 
                                                    card_addr='$card_addr1', 
                                                    profile_logo='$namecard_logo', 
                                                    up_data=NOW(), 
                                                    card_position='$card_position',
                                                    story_online1='$story_online1',
                                                    story_online2='$story_online2',
                                                    card_company='$card_company',
                                                    domain='$domain',
                                                    story_myinfo='$story_myinfo',
                                                    story_company='$story_company',
                                                    story_career='$story_career',
                                                    up_data=now(),
                                                    main_img1='$main_img1',
                                                    main_img2='$main_img2',
                                                    main_img3='$main_img3'
                                               where idx='$profile_idx' ";
        $name_card_result = mysql_query($name_card_sql) or die(mysql_error());              
    }
    
        
    if($content1_idx == "" ||$content1_idx == 0) {
        $query = "insert into Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img1}',
                                                  contents_title='$cont_title1',
                                                  contents_url='$cont_link1',
                                                  contents_url_title='$cont_exp1',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  card_short_url='$short_url',
                                                  westory_card_url='$short_url',
                                                  req_data=now(),
                                                  up_data=now()
                                                  ";
        mysql_query($query);	
        $content1_idx = mysql_insert_id();        
    } else {
        $query = "update Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img1}',
                                                  contents_title='$cont_title1',
                                                  contents_url='$cont_link1',
                                                  contents_url_title='$cont_exp1',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  up_data=now()
                                                  where idx ='$content1_idx'
                                                  ";
        mysql_query($query);	        
    }
    
    if($content2_idx == "" ||$content2_idx == 0) {
        $query = "insert into Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img2}',
                                                  contents_title='$cont_title2',
                                                  contents_url='$cont_link2',
                                                  contents_url_title='$cont_exp2',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  card_short_url='$short_url',
                                                  westory_card_url='$short_url',
                                                  req_data=now(),
                                                  up_data=now()
                                                  ";
        mysql_query($query);	
        $content2_idx = mysql_insert_id();                
    } else {
        $query = "update Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img2}',
                                                  contents_title='$cont_title2',
                                                  contents_url='$cont_link2',
                                                  contents_url_title='$cont_exp2',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  up_data=now()
                                                  where idx ='$content2_idx'
                                                  ";
        mysql_query($query);	        
    }
    
    if($content3_idx == "" ||$content3_idx == 0) {
        $query = "insert into Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img3}',
                                                  contents_title='$cont_title3',
                                                  contents_url='$cont_link3',
                                                  contents_url_title='$cont_exp3',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  card_short_url='$short_url',
                                                  westory_card_url='$short_url',
                                                  req_data=now(),
                                                  up_data=now()
                                                  ";
        mysql_query($query);	
        $content3_idx = mysql_insert_id();                        
    } else {
        $query = "update Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img3}',
                                                  contents_title='$cont_title3',
                                                  contents_url='$cont_link3',
                                                  contents_url_title='$cont_exp3',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  up_data=now()
                                                  where idx ='$content3_idx'
                                                  ";
        mysql_query($query);	        
    }
    
    if($content4_idx == "" ||$content4_idx == 0) {
        $query = "insert into Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img4}',
                                                  contents_title='$cont_title4',
                                                  contents_url='$cont_link4',
                                                  contents_url_title='$cont_exp4',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  card_short_url='$short_url',
                                                  westory_card_url='$short_url',
                                                  req_data=now(),
                                                  up_data=now()
                                                  ";
        mysql_query($query);	
        $content4_idx = mysql_insert_id();                                
    } else {
        $query = "update Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img4}',
                                                  contents_title='$cont_title4',
                                                  contents_url='$cont_link4',
                                                  contents_url_title='$cont_exp4',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  up_data=now()                                              
                                                  where idx ='$content4_idx'
                                                  ";
        mysql_query($query);	        
    }
    
    if($content5_idx == "" ||$content5_idx == 0) {
        $query = "insert into Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img5}',
                                                  contents_title='$cont_title5',
                                                  contents_url='$cont_link5',
                                                  contents_url_title='$cont_exp5',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  card_short_url='$short_url',
                                                  westory_card_url='$short_url',
                                                  req_data=now(),
                                                  up_data=now()
                                                  ";
        mysql_query($query);	
        $content5_idx = mysql_insert_id();
    } else {
        $query = "update Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img5}',
                                                  contents_title='$cont_title5',
                                                  contents_url='$cont_link5',
                                                  contents_url_title='$cont_exp5',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  up_data=now()
                                                  where idx ='$content5_idx'
                                                  ";
        mysql_query($query);	        
    }
    
    if($content6_idx == "" ||$content6_idx == 0) {
        $query = "insert into Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img6}',
                                                  contents_title='$cont_title6',
                                                  contents_url='$cont_link6',
                                                  contents_url_title='$cont_exp6',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  card_short_url='$short_url',
                                                  westory_card_url='$short_url',
                                                  req_data=now(),
                                                  up_data=now()
                                                  ";
        mysql_query($query);	
        $content6_idx = mysql_insert_id();        
    } else {
        $query = "update Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img6}',
                                                  contents_title='$cont_title6',
                                                  contents_url_title='$cont_exp6',
                                                  contents_url='$cont_link6',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  up_data=now()
                                                  where idx ='$content6_idx'
                                                  ";
        mysql_query($query);	        
    }
    
        
    $query="insert into Gn_Iam_Service set `main_domain`      ='$main_domain', 
                                  `sub_domain` ='$sub_domain', 
                                  mem_cnt ='$mem_cnt',
                                  iamcard_cnt ='$iamcard_cnt',
                                  send_content ='$send_content',
                                  my_card_cnt ='$my_card_cnt',
								  `communications_vendors` ='$communications_vendors',
                                  `privacy` ='$privacy',
                                  `email` ='$email',
                                  `company_name` ='$company_name', 
                                  brand_name='$brand_name',
                                  business_number='$business_number',
                                  contract_start_date='$contract_start_date',
                                  contract_end_date='$contract_end_date',
                                  mem_id='$mem_id',
                                  mem_name='$mem_name',
                                  `owner_name`      ='$owner_name',
                                  `owner_cell`   ='$owner_cell', 
                                  manager_name= '$manager_name',
                                  manager_tel= '$manager_tel',
                                  `fax` ='$fax',
                                  `status`          ='$status', 
                                  address ='$address',
                                  web_theme='$web_theme',
                                  home_link='$home_link',
                                  head_logo='$head_logo',
                                  namecard_logo='$namecard_logo',
                                  pro_name='$pro_name',
                                  pro_group='$pro_group',
                                  pro_self='$pro_self',
                                  pro_tel='$pro_tel',
                                  pro_address='$pro_address',
                                  pro_email='$pro_email',
                                  pro_weblink1='$pro_weblink1',
                                  pro_weblink2='$pro_weblink2',
                                  story_data='$story_data',
                                  story_data2='$story_data2',
                                  story_data3='$story_data3',

                                  cont_title1='$cont_title1',
                                  cont_img1='$cont_img1',
                                  cont_link1='$cont_link1',
                                  cont_exp1='$cont_exp1',
                                  cont_title2='$cont_title2',
                                  cont_img2='$cont_img2',
                                  cont_link2='$cont_link2',
                                  cont_exp2='$cont_exp2',
                                  cont_title3='$cont_title3',
                                  cont_img3='$cont_img3',
                                  cont_link3='$cont_link3',
                                  cont_exp3='$cont_exp3',
                                  cont_title4='$cont_title4',
                                  cont_img4='$cont_img4',
                                  cont_link4='$cont_link4',
                                  cont_exp4='$cont_exp4',
                                  cont_title5='$cont_title5',
                                  cont_img5='$cont_img5',
                                  cont_link5='$cont_link5',
                                  cont_exp5='$cont_exp5',
                                  cont_title6='$cont_title6',
                                  cont_img6='$cont_img6',
                                  cont_link6='$cont_link6',
                                  cont_exp6='$cont_exp6',
                                  content1_idx='$content1_idx',
                                  content2_idx='$content2_idx',
                                  content3_idx='$content3_idx',
                                  content4_idx='$content4_idx',
                                  content5_idx='$content5_idx',
                                  content6_idx='$content6_idx',
                                  profile_idx='$profile_idx',
                                  footer_logo='$footer_logo',
                                  footer_link='$footer_link',
                                  share_price='$share_price',
                                  month_price='$month_price',
                                  main_img1='$main_img1',
                                  main_img2='$main_img2',
                                  main_img3='$main_img3',
                                  keywords='$keywords',
								  kakao='$kakao',
                                  pay_link='$pay_link',
                                  `regdate`         =NOW() ";
    mysql_query($query);	
                        
} else if($_POST['mode'] == "update") {
    
    $query = "select *
                from Gn_Iam_Service where idx='$idx'";
    $res = mysql_query($query);
    $data = mysql_fetch_array($res);    

    $head_logo = gcUploadRename($_FILES['head_logo']["name"],$_FILES["head_logo"][tmp_name],$_FILES["head_logo"][size], "data/site");    
    $namecard_logo = gcUploadRename($_FILES['namecard_logo']["name"],$_FILES["namecard_logo"][tmp_name],$_FILES["namecard_logo"][size], "data/site");    
    $main_img1 = gcUploadRename($_FILES['main_img1']["name"],$_FILES["main_img1"][tmp_name],$_FILES["main_img1"][size], "data/site");    
    $main_img2 = gcUploadRename($_FILES['main_img2']["name"],$_FILES["main_img2"][tmp_name],$_FILES["main_img2"][size], "data/site");    
    $main_img3 = gcUploadRename($_FILES['main_img3']["name"],$_FILES["main_img3"][tmp_name],$_FILES["main_img3"][size], "data/site");    
    $cont_img1 = gcUploadRename($_FILES['cont_img1']["name"],$_FILES["cont_img1"][tmp_name],$_FILES["cont_img1"][size], "data/site");    
    $cont_img2 = gcUploadRename($_FILES['cont_img2']["name"],$_FILES["cont_img2"][tmp_name],$_FILES["cont_img2"][size], "data/site");    
    $cont_img3 = gcUploadRename($_FILES['cont_img3']["name"],$_FILES["cont_img3"][tmp_name],$_FILES["cont_img3"][size], "data/site");    
    $cont_img4 = gcUploadRename($_FILES['cont_img4']["name"],$_FILES["cont_img4"][tmp_name],$_FILES["cont_img4"][size], "data/site");    
    $cont_img5 = gcUploadRename($_FILES['cont_img5']["name"],$_FILES["cont_img5"][tmp_name],$_FILES["cont_img5"][size], "data/site");    
    $cont_img6 = gcUploadRename($_FILES['cont_img6']["name"],$_FILES["cont_img6"][tmp_name],$_FILES["cont_img6"][size], "data/site");    
    $footer_logo = gcUploadRename($_FILES['footer_logo']["name"],$_FILES["footer_logo"][tmp_name],$_FILES["footer_logo"][size], "data/site");   
    
    
    $main_img1 = $main_img1?$main_img1:$data['main_img1'];
    $main_img2 = $main_img2?$main_img2:$data['main_img2'];
    $main_img3 = $main_img3?$main_img3:$data['main_img3'];
        
    $cont_img1 = $cont_img1?$cont_img1:$data['cont_img1'];
    $cont_img2 = $cont_img2?$cont_img2:$data['cont_img2'];
    $cont_img3 = $cont_img3?$cont_img3:$data['cont_img3'];
    $cont_img4 = $cont_img4?$cont_img4:$data['cont_img4'];
    $cont_img5 = $cont_img5?$cont_img5:$data['cont_img5'];
    $cont_img6 = $cont_img6?$cont_img6:$data['cont_img6'];
    $namecard_logo = $namecard_logo?$namecard_logo:$data['namecard_logo'];
    
    $card_name = $pro_name;
    $card_phone = $pro_tel;
    $card_email = $pro_email;
    

    $card_addr1 = $pro_address;
    $card_company = $company_name;
    $card_position = $pro_group;
    $story_myinfo = $pro_self;
    $story_online1 = $pro_weblink1;
    $story_online2 = $pro_weblink2;
    
    $story_myinfo = $story_data;
    $story_company = $story_data2;
    $story_career = $story_data3;    
    $profile_logo = $namecard_logo;
    
    $domain = $sub_domain;
    if($profile_idx == "" ||$profile_idx == 0) {
        $short_url = generateRandomString();
        
        $name_card_sql="insert into Gn_Iam_Name_Card (mem_id, card_short_url, card_name, card_phone, card_email, card_addr, profile_logo, req_data,up_data, card_position,story_online1,story_online2,card_company,story_online1_text,story_online2_text,domain,story_myinfo,story_company,story_career,main_img1,main_img2,main_img3) 
        values ('$mem_id', '$short_url', '$card_name', '$card_phone', '$card_email', '$card_addr1', '$img_url', now(), now(),'$card_position','$story_online1','$story_online2','$card_company','','','$domain','$story_myinfo','$story_company','$story_career','$main_img1','$main_img2','$main_img3')";
        $name_card_result = mysql_query($name_card_sql) or die(mysql_error());                    
        $profile_idx = mysql_insert_id();        
    } else {
        $name_card_sql="update Gn_Iam_Name_Card set card_name='$card_name', 
                                                    card_phone='$card_phone', 
                                                    card_email='$card_email', 
                                                    card_addr='$card_addr1', 
                                                    profile_logo='$namecard_logo', 
                                                    up_data=NOW(), 
                                                    card_position='$card_position',
                                                    story_online1='$story_online1',
                                                    story_online2='$story_online2',
                                                    card_company='$card_company',
                                                    domain='$domain',
                                                    story_myinfo='$story_myinfo',
                                                    story_company='$story_company',
                                                    story_career='$story_career',
                                                    main_img1='$main_img1',
                                                    main_img2='$main_img2',
                                                    main_img3='$main_img3'
                                               where idx='$profile_idx' ";
        $name_card_result = mysql_query($name_card_sql) or die(mysql_error());              
    }
    
        
  
        
    /*if($content1_idx == "" ||$content1_idx == 0) {
        $query = "insert into Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img1}',
                                                  contents_title='$cont_title1',
                                                  contents_url='$cont_link1',
                                                  contents_url_title='$cont_exp1',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  card_short_url='$short_url',
                                                  westory_card_url='$short_url',
                                                  req_data=now(),
                                                  up_data=now()
                                                  ";
        mysql_query($query);	
        $content1_idx = mysql_insert_id();        
    } else {
        $query = "update Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img1}',
                                                  contents_title='$cont_title1',
                                                  contents_url='$cont_link1',
                                                  contents_url_title='$cont_exp1',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  up_data=now()
                                                  where idx ='$content1_idx'
                                                  ";
        mysql_query($query);	        
    }
    
    if($content2_idx == "" ||$content2_idx == 0) {
        $query = "insert into Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img2}',
                                                  contents_title='$cont_title2',
                                                  contents_url='$cont_link2',
                                                  contents_url_title='$cont_exp2',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  card_short_url='$short_url',
                                                  westory_card_url='$short_url',
                                                  req_data=now(),
                                                  up_data=now()
                                                  ";
        mysql_query($query);	
        $content2_idx = mysql_insert_id();                
    } else {
        $query = "update Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img2}',
                                                  contents_title='$cont_title2',
                                                  contents_url='$cont_link2',
                                                  contents_url_title='$cont_exp2',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  up_data=now()
                                                  where idx ='$content2_idx'
                                                  ";
        mysql_query($query);	        
    }
    
    if($content3_idx == "" ||$content3_idx == 0) {
        $query = "insert into Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img3}',
                                                  contents_title='$cont_title3',
                                                  contents_url='$cont_link3',
                                                  contents_url_title='$cont_exp3',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  card_short_url='$short_url',
                                                  westory_card_url='$short_url',
                                                  req_data=now(),
                                                  up_data=now()
                                                  ";
        mysql_query($query);	
        $content3_idx = mysql_insert_id();                        
    } else {
        $query = "update Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img3}',
                                                  contents_title='$cont_title3',
                                                  contents_url='$cont_link3',
                                                  contents_url_title='$cont_exp3',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  up_data=now()
                                                  where idx ='$content3_idx'
                                                  ";
        mysql_query($query);	        
    }
    
    if($content4_idx == "" ||$content4_idx == 0) {
        $query = "insert into Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img4}',
                                                  contents_title='$cont_title4',
                                                  contents_url='$cont_link4',
                                                  contents_url_title='$cont_exp4',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  card_short_url='$short_url',
                                                  westory_card_url='$short_url',
                                                  req_data=now(),
                                                  up_data=now()
                                                  ";
        mysql_query($query);	
        $content4_idx = mysql_insert_id();                                
    } else {
        $query = "update Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img4}',
                                                  contents_title='$cont_title4',
                                                  contents_url='$cont_link4',
                                                  contents_url_title='$cont_exp4',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  up_data=now()                                              
                                                  where idx ='$content4_idx'
                                                  ";
        mysql_query($query);	        
    }
    
    if($content5_idx == "" ||$content5_idx == 0) {
        $query = "insert into Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img5}',
                                                  contents_title='$cont_title5',
                                                  contents_url='$cont_link5',
                                                  contents_url_title='$cont_exp5',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  card_short_url='$short_url',
                                                  westory_card_url='$short_url',
                                                  req_data=now(),
                                                  up_data=now()
                                                  ";
        mysql_query($query);	
        $content5_idx = mysql_insert_id();
    } else {
        $query = "update Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img5}',
                                                  contents_title='$cont_title5',
                                                  contents_url='$cont_link5',
                                                  contents_url_title='$cont_exp5',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  up_data=now()
                                                  where idx ='$content5_idx'
                                                  ";
        mysql_query($query);	        
    }
    
    if($content6_idx == "" ||$content6_idx == 0) {
        $query = "insert into Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img6}',
                                                  contents_title='$cont_title6',
                                                  contents_url='$cont_link6',
                                                  contents_url_title='$cont_exp6',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  card_short_url='$short_url',
                                                  westory_card_url='$short_url',
                                                  req_data=now(),
                                                  up_data=now()
                                                  ";
        mysql_query($query);	
        $content6_idx = mysql_insert_id();        
    } else {
        $query = "update Gn_Iam_Contents set mem_id = '$mem_id',
                                                  contents_type='1',
                                                  contents_img='{$sub_domain}/{$cont_img6}',
                                                  contents_title='$cont_title6',
                                                  contents_url_title='$cont_exp6',
                                                  contents_url='$cont_link6',
                                                  contents_user_display='Y',
                                                  contents_footer_display='Y',
                                                  contents_westory_display='Y',
                                                  up_data=now()
                                                  where idx ='$content6_idx'
                                                  ";
        mysql_query($query);	        
    }*/
 
    $addQuery = "";
    if($head_logo)
        $addQuery .= "head_logo='$head_logo',";
    if($namecard_logo)
        $addQuery .= "namecard_logo='$namecard_logo',";
    if($main_img1)
        $addQuery .= "main_img1='$main_img1',";
    if($main_img2)
        $addQuery .= "main_img2='$main_img2',";
    if($main_img3)
        $addQuery .= "main_img3='$main_img3',";
    if($cont_img1)
        $addQuery .= "cont_img1='$cont_img1',";
    if($cont_img2)
        $addQuery .= "cont_img2='$cont_img2',";
    if($cont_img3)
        $addQuery .= "cont_img3='$cont_img3',";
    if($cont_img4)
        $addQuery .= "cont_img4='$cont_img4',";
    if($cont_img5)
        $addQuery .= "cont_img5='$cont_img5',";
    if($cont_img6)
        $addQuery .= "cont_img6='$cont_img6',";
    if($footer_logo)
        $addQuery .= "footer_logo='$footer_logo',";
    
    $query="update      Gn_Iam_Service set `main_domain`      ='$main_domain', 
                                  `sub_domain` ='$sub_domain', 
                                  mem_cnt ='$mem_cnt',
                                  iamcard_cnt ='$iamcard_cnt',
                                  send_content ='$send_content',
                                  my_card_cnt ='$my_card_cnt',
								  `communications_vendors` ='$communications_vendors',
                                  `privacy` ='$privacy',
                                  `email` ='$email',
                                  `company_name` ='$company_name', 
                                  brand_name='$brand_name',
                                  business_number='$business_number',
                                  contract_start_date='$contract_start_date',
                                  contract_end_date='$contract_end_date',
                                  mem_id='$mem_id',
                                  mem_name='$mem_name',
                                  `owner_name`      ='$owner_name',
                                  `owner_cell`   ='$owner_cell', 
                                  manager_name= '$manager_name',
                                  manager_tel= '$manager_tel',
                                  `fax` ='$fax',
                                  `status`          ='$status', 
                                  address ='$address',
                                  web_theme='$web_theme',
                                  home_link='$home_link',
                                  pro_name='$pro_name',
                                  pro_group='$pro_group',
                                  pro_self='$pro_self',
                                  pro_tel='$pro_tel',
                                  pro_address='$pro_address',
                                  pro_email='$pro_email',
                                  pro_weblink1='$pro_weblink1',
                                  pro_weblink2='$pro_weblink2',
                                  story_data='$story_data',
                                  story_data2='$story_data2',
                                  story_data3='$story_data3',

                                  cont_title1='$cont_title1',
                                  cont_link1='$cont_link1',
                                  cont_exp1='$cont_exp1',
                                  cont_title2='$cont_title2',
                                  cont_link2='$cont_link2',
                                  cont_exp2='$cont_exp2',
                                  cont_title3='$cont_title3',
                                  cont_link3='$cont_link3',
                                  cont_exp3='$cont_exp3',
                                  cont_title4='$cont_title4',
                                  cont_link4='$cont_link4',
                                  cont_exp4='$cont_exp4',
                                  cont_title5='$cont_title5',
                                  cont_link5='$cont_link5',
                                  cont_exp5='$cont_exp5',
                                  cont_title6='$cont_title6',
                                  cont_link6='$cont_link6',
                                  cont_exp6='$cont_exp6',
                                  
                                  content1_idx='$content1_idx',
                                  content2_idx='$content2_idx',
                                  content3_idx='$content3_idx',
                                  content4_idx='$content4_idx',
                                  content5_idx='$content5_idx',
                                  content6_idx='$content6_idx',
                                  profile_idx='$profile_idx',
                                  
                                  footer_link='$footer_link',
                                  share_price='$share_price',
                                  month_price='$month_price',
                                  keywords='$keywords',
								  kakao='$kakao',
                                  pay_link='$pay_link',
                                  $addQuery
                                  `status` ='$status'
                                  
                         WHERE idx='$idx'
                                 ";                    
    mysql_query($query);	
} else if($_POST['mode'] == "delete") {

    $query="delete  from    Gn_Iam_Service 
                         WHERE idx='$idx'
                                 ";
    mysql_query($query);	
}
echo "<script>alert('저장되었습니다.');location='/admin/service_Iam_list.php';</script>";
exit;
?>