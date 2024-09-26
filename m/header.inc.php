<?php
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
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
	<script language="javascript" src="/js/jquery-1.7.1.min.js"></script>
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
								$query = "select * from Gn_Ad_Manager where ad_position='L' and use_yn='Y' order by display_order desc limit 1";
								$res = mysqli_query($self_con, $query);
								while($data = mysqli_fetch_array($res)) {
								?>
								<a href="<?=$data['move_url']?>"><img src="<?=$data['img_url'];?>" alt="<?=$data['title'];?>"></a>
								<?}?>
							</h1>
						</div>
					</div>
				</div>
			</div>
		</header><!-- // 헤더 끝 -->
