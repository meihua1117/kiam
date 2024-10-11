<?php
include $_SERVER['DOCUMENT_ROOT']."/m/include/header.inc.php";
?>
<main id="login" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="inner-wrap">
					<h2 class="title">로그인 하기</h2>
					<form name="login_form" action="ajax/login.php" method="post" onsubmit="return login_check(login_form)">
						
						<?
						$pos = strpos($_REQUEST['refer2'],"///");
						if($pos !== false){
							$data = substr($_REQUEST['refer2'],$pos + 3);
							$link = substr($_REQUEST['refer2'],0,$pos);
						}else{
							$link = $_REQUEST['refer2'];
							$data = "";
						}
						?>
						<input type="hidden" name="refer2" id="refer2" value="<?=$link?>">
						<input type="hidden" name="data" id="data" value="<?=$data?>">
						<div class="login-form">
							<div class="input-wrap">
								<input type="text" class="input" placeholder="아이디" name="one_id" id="one_id">
							</div>
							<div class="input-wrap">
								<input type="password" class="input" placeholder="비밀번호" name="one_pwd" id="one_pwd">
							</div>
							<div class="utils clearfix">
								<div class="autologin">
									<input type="checkbox" class="check" id="autoLogin" checked>
									<label for="autoLogin">자동로그인</label>
								</div>
								<a href="#" class="personal">개인정보처리방침</a>
							</div>
							<div class="button-wrap">
								<input type="submit" class="login-button" value="로그인">
							</div>
						</div>
						<div class="find-info">
							<a href="join.php">회원가입</a>
							<a href="search_id.php">아이디/비밀번호 찾기</a>
						</div>
					</form>
					<div class="banner-wrap">
						<?
						$query = "select * from Gn_Ad_Manager where ad_position='B' and use_yn='Y' and  CURDATE() between send_start_date and send_end_date limit 1";
						$res = mysqli_query($self_con,$query);
						while($data = mysqli_fetch_array($res)) {
						?>
							<a href="<?php echo $data['move_url'];?>" target="_blank">
								<img src="<?php echo $data['img_url'];?>">
							</a>
						<? } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main><!-- // 컨텐츠 영역 시작 -->
<?php include $_SERVER['DOCUMENT_ROOT']."/m/include/menu.inc.php"; ?>
<?php include $_SERVER['DOCUMENT_ROOT']."/m/include/footer.inc.php"; ?>
