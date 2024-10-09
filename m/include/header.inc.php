<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";

$sql_iam = "select site_iam from Gn_Member where mem_id='{$_SESSION[iam_member_id]}'";
$res_iam = mysql_query($sql_iam);
$row_iam = mysql_fetch_array($res_iam);

$site_iam = $row_iam[0];

if($site_iam == "kiam"){
	$domain1 = "www";
}
else{
	$domain1 = $site_iam;
}
$domain = $domain1.".kiam.kr";
$sql_service = "select admin_app_home from Gn_Iam_Service where sub_domain like '%".$domain."%'";
$res_service = mysql_query($sql_service);
$row_service = mysql_fetch_array($res_service);

if($row_service[admin_app_home]){
	$site_iam = $row_iam[0];
}
else{
	$site_iam = "kiam";
}

if(!$_SESSION[iam_member_id]){
	$site_iam = "kiam";
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>온리원 셀링</title>
    <link rel="shortcut icon" href="img/common/icon-os.ico">
	<link rel="stylesheet" href="/m/css/notokr.css">
	<link rel="stylesheet" href="/m/css/font-awesome.min.css">
	<link rel="stylesheet" href="/m/css/style.css">
	<link rel="stylesheet" href="/m/css/grid.min.css">
	<link rel="stylesheet" href="/m/css/slick.min.css">
	<link rel="stylesheet" href="/iam/css/iam.css">
	<script language="javascript" src="/js/jquery-1.7.1.min.js"></script>
	<script language="javascript" src="/js/rlatjd.js"></script>
	<script>
		function goOnlyOneApp() {
			//goOnlyOneCallback('사용자아이디'); 
			var navCase = navigator.userAgent.toLocaleLowerCase();
			if(navCase.search("android") > -1){
				try {
					AppScript.goOnlyOneApp('<?php echo $_SESSION[one_member_id];?>');
				} catch(e) {
					openAndroid();
				}
			}else{
				// var iam_mem_id = "<?=$_SESSION[one_member_id];?>";
				// if(iam_mem_id == "")
					alert("휴대폰에서 이용해주세요.");
			}
		}
		function goCallbackCamerapApp() {
			//goOnlyOneCallback('사용자아이디'); 
			var navCase = navigator.userAgent.toLocaleLowerCase();
			if(navCase.search("android") > -1){
				try {
					AppScript.goCallbackCamerapApp('<?php echo $_SESSION[one_member_id];?>');
				} catch(e) {
					openAndroid();
				}
			}else{
				// var iam_mem_id = "<?=$_SESSION[one_member_id];?>";
				// if(iam_mem_id == "")
					alert("휴대폰에서 이용해주세요.");
			}
		}
		function openAndroid(){
			var userAgent = navigator.userAgent.toLowerCase();
			if ( userAgent.match(/chrome/) ) { 
				location.href = 'intent://onlyone#Intent;scheme=onlyoneapp;package=mms5.onepagebook.com.onlyonesms;end';
			}else{
				var iframe = document.createElement( 'iframe' );
				iframe.style.visivility = 'hidden';
				iframe.src = 'onlyone://onlyoneapp';
				document.body.appendChild(iframe);
				document.body.removeChild(iframe);
			}
		}
	</script>
</head>
<body>
	<div id="wrap" class="common-wrap">
		<header id="header"><!-- 헤더 시작 -->
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="inner-wrap">
							<?if($is_pay_version){?>
							<a href="#" id="toggleMenu">
								<i class="fa fa-bars" aria-hidden="true"></i>
							</a>
							<?}?>
							<h1 class="logo">
								<?
								$query = "select * from Gn_App_Home_Manager where ad_position='L' and use_yn='Y' and site_iam='{$site_iam}' order by display_order desc limit 1";
								$res = mysql_query($query);
								while($data = mysql_fetch_array($res)) {
								?>
								<a href="<?=$data[move_url]?>"><img src="<?=$data['img_url'];?>" alt="<?=$data['title'];?>" style="height:30px"></a>
								<?}?>
							</h1>
							<a href="javascript:goOnlyOneApp()" style="position: absolute;right: 0;top: 13px;">
								<img src="./img/menu/ft_menu_edit.png" style="width:25px;">
							</a>
						</div>
					</div>
				</div>
			</div>
		</header><!-- // 헤더 끝 -->
