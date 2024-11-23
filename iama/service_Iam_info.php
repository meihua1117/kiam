<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
if($HTTP_HOST != "kiam.kr") {
    //$query = "select * from Gn_Iam_Service where sub_domain like '%".$HTTP_HOST."'";
    $query = "select * from Gn_Iam_Service where sub_domain = 'http://".$HTTP_HOST."'";
    $res = mysqli_query($self_con, $query);
    $domainData = mysqli_fetch_array($res);
    if($domainData['mem_id'] !=$_SESSION['iam_member_id']) {
        echo "<script>location='/';</script>";
        exit;
    }
        

    for($i = 1; $i <= 6; $i++)
    {
      $temp = $domainData['content'.$i.'_idx'];
      
      $query = "select * from Gn_Iam_Contents where idx='$temp'";
      $res2 = mysqli_query($self_con, $query);
      $data2 = mysqli_fetch_array($res2);
  
      $domainData['content'.$i.'_title'] = $data2['contents_title'];
      $domainData['content'.$i.'_img'] = $data2['contents_img'];
      $domainData['content'.$i.'_link'] = $data2['contents_url'];
      $domainData['content'.$i.'_exp'] = $data2['contents_desc'];
    }

    $query = "select * from Gn_Iam_Name_Card where idx='$domainData[profile_idx]'";
    $res2 = mysqli_query($self_con, $query);
    $data2 = mysqli_fetch_array($res2);
    
    $domainData['namecard_logo'] = $data2['profile_logo'];
    $domainData['profile_name'] = $data2['card_name'];
    $domainData['profile_phone'] = $data2['card_phone'];
    $domainData['profile_add'] = $data2['card_addr'];
    $domainData['profile_email'] = $data2['card_email'];
    $domainData['profile_rank'] = $data2['card_position'];
    $domainData['profile_info'] = $data2['story_myinfo'];
    $domainData['profile_site1'] = $data2['story_online1'];
    $domainData['profile_site2'] = $data2['story_online2'];
    
    $site = explode(".", $domainData['sub_domain']);
    $site[0] = str_replace("http://","", $site[0]);
    $query = "select count(*) from Gn_Member where site='$site[0]'";
    $res = mysqli_query($self_con, $query);
    $row = mysqli_fetch_array($res);    
    $mem_cnt = $row[0];

    $query = "select count(*) from Gn_Member where site='$site[0]' and first_regist >= '".date("Y-m-d 00:00:00")."'";
    $res = mysqli_query($self_con, $query);
    $row = mysqli_fetch_array($res);    
    $new_cnt = $row[0];    

    $query = "select count(*) from Gn_Member where site='$site[0]' and ext_recm_id = '{$domainData['mem_id']}'";
    $res = mysqli_query($self_con, $query);
    $row = mysqli_fetch_array($res);    
    $recommend_cnt = $row[0];        
    
    
    $query = "select count(*) from Gn_Iam_Name_Card a inner join  Gn_Member b on a.mem_id = b.mem_id where a.group_id = 0 and b.site='$site[0]' ";
    $res = mysqli_query($self_con, $query);
    $row = mysqli_fetch_array($res);    
    $card_cnt = $row[0];            
    
    $query = "select count(*) from Gn_Iam_Name_Card a inner join  Gn_Member b on a.mem_id = b.mem_id  where a.group_id = 0 and b.site='$site[0]' and a.req_data >= '".date("Y-m-d 00:00:00")."'";
    $res = mysqli_query($self_con, $query);
    $row = mysqli_fetch_array($res);    
    $card_new_cnt = $row[0];                
    
    $query = "select sum(iam_share) from Gn_Iam_Name_Card a inner join  Gn_Member b on a.mem_id = b.mem_id  where a.group_id = 0 and b.site='$site[0]' ";
    $res = mysqli_query($self_con, $query);
    $row = mysqli_fetch_array($res);    
    $card_share_cnt = $row[0];                    



    $query = "select count(*) from Gn_Iam_Contents a inner join  Gn_Member b on a.mem_id = b.mem_id where b.site='$site[0]' ";
    $res = mysqli_query($self_con, $query);
    $row = mysqli_fetch_array($res);    
    $contents_cnt = $row[0];            
    
    $query = "select count(*) from Gn_Iam_Contents a inner join  Gn_Member b on a.mem_id = b.mem_id  where b.site='$site[0]' and a.req_data >= '".date("Y-m-d 00:00:00")."'";
    $res = mysqli_query($self_con, $query);
    $row = mysqli_fetch_array($res);    
    $contents_new_cnt = $row[0];                
    
    $query = "select sum(iam_share) from Gn_Iam_Contents a inner join  Gn_Member b on a.mem_id = b.mem_id  where b.site='$site[0]' ";
    $res = mysqli_query($self_con, $query);
    $row = mysqli_fetch_array($res);    
    $contents_share_cnt = $row[0];                    
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>아이엠카드 관리자</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/admin/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="/admin/dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/admin/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="/admin/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="/admin/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="/admin/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="/admin/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="/admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
    .loading_div{position:fixed;left:50%;top:50%;display:none;z-index:1000;}    
    .open_1{position:absolute;z-index:10;background-color:#FFF;display:none;border:1px solid #000}
    .open_2{padding-left:5px;height:30px;cursor:move;}
    .open_2_1{float:left;line-height:30px;font-size:16px;font-weight:bold;}
    .open_2_2{float:right;}
    .open_2_2 a:link, 
    .open_2_2 a:visited,
    .open_2_2 a:active{text-decoration:none; color:#FFF; }
    .open_2_2 a:hover{text-decoration:none;color:#FF0;}
    .open_3{padding:10px;}  
    .box-body th {background:#ddd;}

    .loading_div {
    display:none;
    position: fixed;
    left: 50%;
    top: 50%;
    display: none;
    z-index: 1000;
}
#open_recv_div li{list-style: none;}


.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #ddd!important;
}


#open_recv_div li{list-style: none;}
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
input, select, textarea {
    vertical-align: middle;
    border: 1px solid #CCC;
}
/* user agent stylesheet */
input[type="checkbox" i] {
    background-color: initial;
    cursor: default;
    -webkit-appearance: checkbox;
    box-sizing: border-box;
    margin: 3px 3px 3px 4px;
    padding: initial;
    border: initial;
}
input:checked + .slider {
    background-color: #2196F3;
}
.switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}
input:checked + .slider {
    background-color: #2196F3;
}
.slider.round {
    border-radius: 34px;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
}
input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
}
.slider.round:before {
    border-radius: 50%;
}
.slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;

}

.disagree{
     background: #ffd5d5!important;   
    }
    th a.sort-by { 
	padding-right: 18px;
	position: relative;
}
a.sort-by:before,
a.sort-by:after {
	border: 4px solid transparent;
	content: "";
	display: block;
	height: 0;
	right: 5px;
	top: 50%;
	position: absolute;
	width: 0;
}
a.sort-by:before {
	border-bottom-color: #666;
	margin-top: -9px;
}
a.sort-by:after {
	border-top-color: #666;
	margin-top: 1px;
}




.zoom {
  transition: transform .2s; /* Animation */
}

.zoom:hover {
  transform: scale(4); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
  border:1px solid #0087e0;
  box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
}

.zoom-2x {
  transition: transform .2s; /* Animation */
}

.zoom-2x:hover {
  transform: scale(2); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
  border:1px solid #0087e0;
  box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
}


    </style>
    <!-- jQuery 2.1.4 -->
    <script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>    

    
    
  </head>
  <body class="hold-transition skin-blue sidebar-mini"><script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
var mem_id="";
function page_view(mem_id) {
    $('.ad_layer1').lightbox_me({
    	centered: true,
    	onLoad: function() {
    		$.ajax({
    			type:"POST",
    			url:"/admin/ajax/member_list_page1.php",
    			data:{mem_id:mem_id},
    			dataType: 'html',
    			success:function(data){
    				$('#phone_list').html(data);
    			},
    			error: function(){
    			  alert('로딩 실패');
    			}
    		});			    
    	}
    });
    $('.ad_layer1').css({"overflow-y":"auto", "height":"300px"});
}
$(function(){
});

//폰정보 수정
function modify_phone_info(){

	var phno = $("#pno").val();

	if(!phno){
		alert('폰 정보가 없습니다.');
		return false;
	}else{
		$.ajax({
			type:"POST",
			url:"ajax/modify_phoneinfo.php",
			data:{
				pno:phno,
				name:$("#detail_name").val(),
				company:$("#detail_company").val(),
				rate:$("#detail_rate").val()
			},
			success:function(data){
				location.reload();
			},
			error: function(){
			  alert('수정 실패');
			}
		});		
	}
}

//계정 삭제
function del_service(idx){

	var msg = confirm('정말로 삭제하시겠습니까?');

	if(msg){

			$.ajax({
				type:"POST",
				url:"/admin/ajax/service_save.php",
				data:{mode:"delete",idx:idx},
				success:function(){
					alert('삭제되었습니다.');
					location.reload();
				},
				error: function(){
				  alert('삭제 실패');
				}
			});		

	}else{
		return false;
	}
}

//주소록 다운
function excel_down_(){
	$("#excel_down_form").submit();
	return false;
}


function goPage(pgNum) {
    location.href = '?1&nowPage='+pgNum+"&search_key=";
}
</script>   
<style>
.loading_div {
    display:none;
    position: fixed;
    left: 50%;
    top: 50%;
    display: none;
    z-index: 1000;
}
th{
    width:100px;
}
</style>


<div class="loading_div" ><img src="/images/ajax-loader.gif"></div>
    <div class="wrapper" style="margin-left: auto;margin-right: auto;position: relative;max-width: 410px">
      <!-- Top 메뉴 -->
      <? include "header.php";?>
      <form method="post" id="dForm" name="dForm" action="service_Iam_save.php"  enctype="multipart/form-data">
        <input type="hidden" name="idx" value="<?=$domainData['idx']?>" />
        <input type="hidden" name="mode" value="<?=$domainData['idx']?"update":"insert"?>" />
        
        <input type="hidden" name="profile_idx" value="<?=$domainData['profile_idx']?>" />
          <div class="row">
              <div class="box">
                <div class="box-body">
                  <table id="detail1" class="table table-bordered table-striped">
                    <tbody >
                     <tr>
                       <th>
                       <input type='button' style="background-color:blue;color:white;" value='아이엠정보'/></th>
                       <td colspan="2" >
                        <h5 style="width:280px;">※아래 항목에 아이엠정보를 입력하면 이용자의 아이엠에 여기에 입력한 정보가 해당 회원의 아이엠 디폴트로 나타납니다. 해당 정보는 관리자가 직접 입력, 수정할수 있습니다. </h5></td>
                     </tr>


                    <tr>
                      <th style="width:180px;">홈페이지제목</th>
                        <td>
                            <input type="text" style="width:280px;" name="web_theme" id="web_theme" value="<?=$domainData['web_theme']?>" > 
                            </td>            
                      </tr>                  


                    <tr>
                      <th >아이엠로고</th>
                        <td >
                            <input type="file" name="head_logo">
                            <?php if($domainData['head_logo'] != "") {?>
                            <img src="<?=$domainData['head_logo']?>" style="width:120px">
                            <?php }?>
                        </td>
                      </tr>                  

                    <tr>
                        <th>홈아이콘링크</th>
                        <td>
                            <input type="text" style="width:280px;" name="home_link" id="home_link" value="<?=$domainData['home_link']?>" > 
                            </td>            
                      </tr>

                      <tr>             
                      <th >대표디폴트이미지1</th>
                        <td >
                            <input type="file" name="main_img1">
                            <?php if($domainData['main_img1'] != "") {?>
                            <img src="<?=$domainData['main_img1']?>" style="width:120px">
                            <?php }?>
                        </td>
                      </tr>   
                      <tr>               
                      <th >대표디폴트이미지2</th>
                        <td >
                            <input type="file" name="main_img2">
                            <?php if($domainData['main_img2'] != "") {?>
                            <img src="<?=$domainData['main_img2']?>" style="width:120px">
                            <?php }?>
                        </td>   
                      </tr>  
                      <tr>             
                      <th >대표디폴트이미지3</th>
                        <td >
                            <input type="file" name="main_img3">
                            <?php if($domainData['main_img3'] != "") {?>
                            <img src="<?=$domainData['main_img3']?>" style="width:120px">
                            <?php }?>
                        </td>                           
                     </tr>  
                     <tr>             
                      <th >명함로고</th>
                        <td >
                            <input type="file" name="namecard_logo">
                            <?php if($domainData['namecard_logo'] != "") {?>
                            <img src="<?=$domainData['namecard_logo']?>" style="width:120px">
                            <?php }?>
                        </td>
                      </tr>  

                     <tr>
                      <th>프로필<br>이름</th>
                        <td>
                            <input type="text" style="width:280px;" name="pro_name" id="pro_name" value="<?=$domainData['pro_name']?>" > 
                        </td>
                      </tr>
                      <tr>
                        <th>프로필<br>휴대폰</th>
                        <td>
                            <input type="text" style="width:280px;" name="pro_tel" id="pro_tel" value="<?=$domainData['pro_tel']?>" > 
                        </td>
                        </tr>
                         
                        <tr>
                        <th>프로필<br>소속직책</th>
                        <td>
                            <input type="text" style="width:280px;" name="pro_group" id="pro_group" value="<?=$domainData['pro_group']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>프로필<br>자기소개</th>
                        <td>
                            <input type="text" style="width:280px;" name="pro_self" id="pro_self" value="<?=$domainData['pro_self']?>" > 
                        </td>
                        </tr>
                         
                        <tr>
                        <th>프로필<br>이메일</th>
                        <td>
                            <input type="text" style="width:280px;" name="pro_email" id="pro_email" value="<?=$domainData['pro_email']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>프로필<br>주소</th>
                        <td>
                            <input type="text" style="width:280px;" name="pro_address" id="pro_address" value="<?=$domainData['pro_address']?>" > 
                        </td>
                        </tr>
                         
                        <tr>
                        <th>프로필<br>홈피1</th>
                        <td>
                            <input type="text" style="width:280px;" name="pro_weblink1" id="pro_weblink1" value="<?=$domainData['pro_weblink1']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>프로필<br>홈피2</th>
                        <td>
                            <input type="text" style="width:280px;" name="pro_weblink2" id="pro_weblink2" value="<?=$domainData['pro_weblink2']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>keywords</th>
                        <td>
                            <input type="text" style="width:280px; height:100px;" name="keywords" id="keywords" value="<?=$domainData['keywords']?>" > 
                        </td>
                      </tr>

                      <tr>                  
                      <th>마이스토리<br>자기소개</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="story_data" id="story_data" value="<?=$domainData['story_data']?>" > 
                            </td>            
                      </tr>      

                      <tr>
                      <th>마이스토리<br>기관소개</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="story_data2" id="story_data2" value="<?=$domainData['story_data2']?>" > 
                            </td>            
                      </tr>     

                      <tr>
                      <th>마이스토리<br>이력소개</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="story_data3" id="story_data3" value="<?=$domainData['story_data3']?>" > 
                            </td>            
                      </tr>                  


    
                        <tr>
                        <th>컨텐츠1번 제목</th>
                        <td>
                            <input type="text" style="width:280px;" name="cont_title1" id="cont_title1" value="<?=$domainData['cont_title1']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠1번 이미지</th>
                        <td>
                        <input type="file" name="cont_img1">
                            <?php if($domainData['cont_img1'] != "") {?>
                            <img src="<?=$domainData['cont_img1']?>" style="width:120px">
                            <?php }?>
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠1번 링크</th>
                        <td>
                            <input type="text" style="width:280px;" name="cont_link1" id="cont_link1" value="<?=$domainData['cont_link1']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠1번 설명</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="cont_exp1" id="cont_exp1" value="<?=$domainData['cont_exp1']?>" > 
                        </td>
                        </tr>
                        <tr>
                        <th>컨텐츠2번 제목</th>
                        <td>
                            <input type="text" style="width:280px;" name="cont_title2" id="cont_title2" value="<?=$domainData['cont_title2']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠2번 이미지</th>
                        <td>
                        <input type="file" name="cont_img2">
                            <?php if($domainData['cont_img2'] != "") {?>
                            <img src="<?=$domainData['cont_img2']?>" style="width:120px">
                            <?php }?>
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠2번 링크</th>
                        <td>
                            <input type="text" style="width:280px;" name="cont_link2" id="cont_link2" value="<?=$domainData['cont_link2']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠2번 설명</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="cont_exp2" id="cont_exp2" value="<?=$domainData['cont_exp2']?>" > 
                        </td>
                        </tr>    

                        <tr>
                        <th>컨텐츠3번 제목</th>
                        <td>
                            <input type="text" style="width:280px;" name="cont_title3" id="cont_title3" value="<?=$domainData['cont_title3']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠3번 이미지</th>
                        <td>
                        <input type="file" name="cont_img3">
                            <?php if($domainData['cont_img3'] != "") {?>
                            <img src="<?=$domainData['cont_img3']?>" style="width:120px">
                            <?php }?>
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠3번 링크</th>
                        <td>
                            <input type="text" style="width:280px;" name="cont_link3" id="cont_link3" value="<?=$domainData['cont_link3']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠3번 설명</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="cont_exp3" id="cont_exp3" value="<?=$domainData['cont_exp3']?>" > 
                        </td>
                        </tr>


                        <tr>
                        <th>컨텐츠4번 제목</th>
                        <td>
                            <input type="text" style="width:280px;" name="cont_title4" id="cont_title4" value="<?=$domainData['cont_title4']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠4번 이미지</th>
                        <td>
                        <input type="file" name="cont_img4">
                            <?php if($domainData['cont_img4'] != "") {?>
                            <img src="<?=$domainData['cont_img4']?>" style="width:120px">
                            <?php }?>
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠4번 링크</th>
                        <td>
                            <input type="text" style="width:280px;" name="cont_link4" id="cont_link4" value="<?=$domainData['cont_link4']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠4번 설명</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="cont_exp4" id="cont_exp4" value="<?=$domainData['cont_exp4']?>" > 
                        </td>
                        </tr>    

                        <tr>
                        <th>컨텐츠5번 제목</th>
                        <td>
                            <input type="text" style="width:280px;" name="cont_title5" id="cont_title5" value="<?=$domainData['cont_title5']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠5번 이미지</th>
                        <td>
                        <input type="file" name="cont_img5">
                            <?php if($domainData['cont_img5'] != "") {?>
                            <img src="<?=$domainData['cont_img5']?>" style="width:120px">
                            <?php }?>
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠5번 링크</th>
                        <td>
                            <input type="text" style="width:280px;" name="cont_link5" id="cont_link5" value="<?=$domainData['cont_link5']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠5번 설명</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="cont_exp5" id="cont_exp5" value="<?=$domainData['cont_exp5']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠6번 제목</th>
                        <td>
                            <input type="text" style="width:280px;" name="cont_title6" id="cont_title6" value="<?=$domainData['cont_title6']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠6번 이미지</th>
                        <td>
                        <input type="file" name="cont_img6">
                            <?php if($domainData['cont_img6'] != "") {?>
                            <img src="<?=$domainData['cont_img6']?>" style="width:120px">
                            <?php }?>
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠6번 링크</th>
                        <td>
                            <input type="text" style="width:280px;" name="cont_link6" id="cont_link6" value="<?=$domainData['cont_link6']?>" > 
                        </td>
                        </tr>

                        <tr>
                        <th>컨텐츠6번 설명</th>
                        <td>
                            <input type="text" style="width:280px;height:100px" name="cont_exp6" id="cont_exp6" value="<?=$domainData['cont_exp6']?>" > 
                        </td>
                        </tr>    

                    <tr>
                        <th >푸터로고</th>
                        <td >
                            <input type="file" name="footer_logo">
                            <?php if($domainData['footer_logo'] != "") {?>
                            <img src="<?=$domainData['footer_logo']?>" style="width:120px">
                            <?php }?>
                        </td>
                       </tr>

                       <tr>
                        <th>푸터링크</th>
                        <td>
                            <input type="text" style="width:280px;" name="footer_link" id="footer_link" value="<?=$domainData['footer_link']?>" > 
                            </td>            
                       </tr>

                       <tr>
                        <th>KaKao Iink</th>
                        <td colspan="3">
                            <input type="text" style="width:280px;" name="kakao" id="kakao" value="<?=$domainData['kakao']?>" > 
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            <div class="box-footer" style="text-align:center">
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="form_save();return false;"><i class="fa fa-save"></i> 저장</button>
                <button class="btn btn-primary" style="margin-right: 5px;" onclick="location='service_Iam_list.php';return false;"><i class="fa fa-list"></i> 목록</button>
            </div>            
          </div>
      </form>
<script language="javascript">
function form_save() {
    if($('#domain').val() == "") {
        alert('도메인을 입력해주세요.');
        return;
    }    
    $('#dForm').submit();
}    
</script>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>                
<script language="javascript">
jQuery(function($){
 $.datepicker.regional['ko'] = {
  closeText: '닫기',
  prevText: '이전달',
  nextText: '다음달',
  currentText: 'X',
  monthNames: ['1월(JAN)','2월(FEB)','3월(MAR)','4월(APR)','5월(MAY)','6월(JUN)',
  '7월(JUL)','8월(AUG)','9월(SEP)','10월(OCT)','11월(NOV)','12월(DEC)'],
  monthNamesShort: ['1월','2월','3월','4월','5월','6월',
  '7월','8월','9월','10월','11월','12월'],
  dayNames: ['일','월','화','수','목','금','토'],
  dayNamesShort: ['일','월','화','수','목','금','토'],
  dayNamesMin: ['일','월','화','수','목','금','토'],
  weekHeader: 'Wk',
  dateFormat: 'yy-mm-dd',
  firstDay: 0,
  isRTL: false,
  showMonthAfterYear: true,
  yearSuffix: ''};
 $.datepicker.setDefaults($.datepicker.regional['ko']);

    $('#contract_start_date').datepicker({
        showOn: 'button',
        buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
        buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });
    $('#contract_end_date').datepicker({
        showOn: 'button',
        buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
        buttonImageOnly: true,
        buttonText: "달력",
        changeMonth: true,
	    changeYear: true,
        showButtonPanel: true,
        yearRange: 'c-99:c+99',
        minDate: '',
        maxDate: ''
    });	
});
</script>   
      <!-- Footer -->
      <footer class="main-footer">
        <div class="pull-right hidden-xs">
          <b>Version</b> 1.0.0
        </div>
        <strong>Copyright &copy; 2016 Onlyone All rights reserved.
      </footer>
      <!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
      <div class="control-sidebar-bg"></div>
      <div id='ajax_div'></div>
    </div><!-- ./wrapper -->
  </body>
</html>      