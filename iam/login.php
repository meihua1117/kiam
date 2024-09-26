<?php
include "inc/header.inc.php";
$contents_idx = $_GET['contents_idx'];
?>
<main id="login" class="common-wrap" style="margin-top: 86px"><!-- 컨텐츠 영역 시작 -->
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="inner-wrap">
					<h2 class="title">로그인 하기</h2>
					<form name="login_form" action="ajax/login.php" method="post" onsubmit="return login_check(login_form)">
						<input type="hidden" name="contents_idx" id="contents_idx" value="<?= $contents_idx ?>">
						<input type="hidden" name="for_report" id="for_report" value="">
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
							<?
							if ($HTTP_HOST != "kiam.kr") {
								$join_link = get_join_link("http://" . $HTTP_HOST, $_GET['recommend_id']);
							} else {
								$join_link = get_join_link("http://www.kiam.kr", $_GET['recommend_id']);
							}
							?>
							<a href="<?= $join_link ?>">회원가입</a>
							<a href="search_id.php">아이디/비밀번호 찾기</a>
						</div>
					</form>
					<div class="banner-wrap">
						<?
						$query = "select * from Gn_Ad_Manager where ad_position='B' and use_yn='Y' and  CURDATE() between send_start_date and send_end_date limit 1";
						$res = mysqli_query($self_con,$query);
						while ($data = mysqli_fetch_array($res)) {
						?>
							<a href="<?= $data['move_url']; ?>" target="_blank">
								<img src="<?= $data['img_url']; ?>">
							</a>
						<? } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</main><!-- // 컨텐츠 영역 시작 -->
<script>
	$(function() {
		const data = localStorage.getItem('mId');
		if (data) {
			$("#one_id").val(data);
			$("#for_report").val(data);
			localStorage.removeItem('mId');
		}
	});
</script>