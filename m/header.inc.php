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
    <link rel="shortcut icon" href="img/common/icon_os.ico">
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
								<a href="/m/"><img src="/iam/img/common/logo-2.png" alt="온리원셀링 로고 이미지"></a>
							</h1>
						</div>
					</div>
				</div>
			</div>
		</header><!-- // 헤더 끝 -->
