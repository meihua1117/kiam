<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
?>
<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<?
if ($HTTP_HOST != "kiam.kr") {
	$query = "select * from Gn_Iam_Service where sub_domain like '%" . $HTTP_HOST . "'";
	$res = mysqli_query($self_con,$query);
	$domainData = mysqli_fetch_array($res);
	$sql = "select orderNumber from tjd_pay_result where buyer_id = '{$domainData['mem_id']}' and stop_yn='Y'";
	$stop_res = mysqli_query($self_con,$sql);
	$stop_row = mysqli_fetch_assoc($stop_res);
	if ($stop_row['orderNumber']) {
		echo "<script>window.open(" . "'/payment_pop.php?index={$stop_row['orderNumber']}'" . ", \"notice_pop\", \"toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=600,height=350\");</script>";
	}
	/*if(date("Y-m-d") >= $domainData['contract_end_date']) {
        @header('Content-Type: text/html; charset=UTF-8');
        echo "<script>alert('본 아이엠은 계약기간이 종료되었습니다. 관리자에게 문의해주세요.');hisotory.go(-1);</script>";
        exit;
    }*/
}

if (!$mem_id) {
	$mem_id = trim($_SESSION['iam_member_id']);
}

if (isset($_GET['sendtype'])) {
	$type = $_GET['sendtype'];
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta property="og:title" content="공개프로필 정보리스트">
	<title>공개프로필 정보리스트</title>
	<link rel="shortcut icon" href="img/common/iconiam.ico">
	<link rel="stylesheet" href="css/notokr.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/new_style.css">
	<link rel="stylesheet" href="css/grid.min.css">
	<link rel="stylesheet" href="css/slick.min.css">
	<link rel="stylesheet" href="css/style_j.css">
	<link rel='stylesheet' href='../plugin/toastr/css/toastr.css' />

	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="js/slick.min.js"></script>
	<script src="js/main.js"></script>
	<script src='../plugin/toastr/js/toastr.min.js'></script>
	<style>
		#star #middle .tab-content .tab-pane {
			display: block;
		}

		#star #middle .tab-content .tab-pane .contact-list .list-item {
			padding: 5px 15px;
			border: 1px solid #eee;
			margin-top: 5px;
			margin-bottom: 5px;
			background: rgb(251, 251, 251);
		}
	</style>
</head>

<body>
	<div id="wrap" class="common-wrap">
		<main id="star" class="common-wrap">
			<h3 style="margin:10px;">공개프로필 정보리스트</h3>
			<section id="middle">
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane  friends-tab-content active in" id="friends" role="tabpanel" aria-labelledby="friends-tab">
						<div class="box-body">
							<?
							$site_info_friends = explode(".", $HTTP_HOST);
							$site_name_friends = $site_info_friends[0];
							if ($site_name_friends == "www") {
								$site_name_friends = "kiam";
							}
							$show_all = "N";
							if (isset($_GET['show_all'])) {
								$show_all = $_GET['show_all'];
							}
							if ($card_num !== "") {
								$page_search2 = $G_card['card_short_url'];
							} else {
								$page_search2 = "";
							}
							$search_range2 = $_GET['search_range2'];
							$search_str2 = $_GET['search_str2'];
							$search_type = $_GET['search_type'];
							if (is_null($search_str2)) {
								$search_str2 = "";
							}
							if (!$search_type) {
								if ($_SESSION['iam_member_id'] == $mem_id) {
									$search_type = 2;
								} else {
									$search_type = 1;
								}
							}
							?>
							<div class="search-box clearfix J2" id="friends_search">
								<? if ($_SESSION['iam_member_id'] == $mem_id) { ?>
									<div class="row" style='margin-left: 8px;margin-bottom: 0px;margin-top: 7px; '>
										<div class="left">
											<div class="buttons">
												<a href="javascript:friends_range('1');" class="button" <? if ((int)$search_range2 == 1) { ?>style="background-color: #000;" <? } else { ?>style="background-color: #fff;color:black;" <? } ?>>가</a>
												<a href="javascript:friends_range('3');" class="button" <? if ((int)$search_range2 == 3) { ?>style="background-color: #000;" <? } else { ?>style="background-color: #fff;color:black;" <? } ?>><i class="fa fa-history" aria-hidden="true"></i></a>
												<input type="hidden" name="search_range2" id="search_range2" value="<?= $search_range2 ?>">
											</div>
											<div id="friends_chk_count" class="selects">0개 선택됨</div>
										</div>
										<div class="right">
											<select name="search_type" id="search_type" style="font-size:12px;margin-right:20px; margin-right: 20px;border: 1px solid #ddd;background: #fff;height: 33px;border-radius: 5px;" onchange="friends_submit('<?= $show_all ?>')">
												<option value="1" <? if ((int)$search_type == 1) { ?>selected <? } ?>>나의 프렌즈</option>
												<option value="2" <? if ((int)$search_type == 2) { ?>selected <? } ?>>공개 프로필</option>
												<option value="3" <? if ((int)$search_type == 3) { ?>selected <? } ?>>소속 회원</option>
												<option value="4" <? if ((int)$search_type == 4) { ?>selected <? } ?>>조인 회원</option>
											</select>
										</div>
									</div>
									<div class="row" style="overflow:hidden;">
										<div class="search J_search" style="display:inline;width:60%;margin-right:20px;">
											<button type="button" onclick="friends_submit('<?= $show_all ?>');" class="submit" style="top: 3px;background: #f9f9f9;font-size: 17px;"><i class="fa fa-search" aria-hidden="true"></i></button>
											<input type="text" name="search_str2" id="search_str2" class="input" value="<?= $search_str ?>" onkeyup="enterkey('friends_submit');" style="font-size:12px;height: 25px;background:#f9f9f9;width:50%;outline:none;" placeholder="검색어를 입력해주세요.">
										</div>
										<div style="float:right;padding-top:5px;padding-left:20px;">
											<?php
											if ($search_type == 3 || $search_type == 4) {
												if ($show_all == "N") {
													$style_list = "";
													$style_page = "";
											?>
													<a href="javascript:friends_submit('Y');"><img src="img/main/icon_all_gray.png" style="width: 20px"></a>
													<img src="img/star/icon-profile.png" style="width: 20px" onclick="friends_add();">
												<? } else {
													$style_list = "height: 700px;overflow: auto;margin-bottom: 20px;";
													$style_page = "display:none;"; ?>
													<a href="javascript:friends_submit('N');"><img src="img/main/icon_all_green.png" style="width: 20px"></a>
													<img src="img/star/icon-profile.png" style="width: 20px" onclick="friends_add();">
											<? }
											} ?>
											<? if ($search_type == 2) { ?>
												<img src="img/star/icon-profile.png" style="width: 20px" onclick="friends_add();">
											<? } ?>
											<input type="checkbox" name="cbG01" id="cbG01" class="css-checkbox" onclick='groupCheckClick(this);'>
											<label for="cbG01" class="css-label cb0">전체선택</label>
										</div>
									</div>
								<? } ?>
							</div>
							<div class="inner">
								<div class="contact-list" style="<?= $style_list ?>">
									<ul>
										<?
										if ($search_str2 !== "") {
											if ((int)$search_type == 2) {
												$friends_sql_msg = "and (card_name like '%$search_str2%' or card_phone like '%$search_str2%' or card_keyword like '%$search_str2%')";
											} else if ((int)$search_type == 3 || (int)$search_type == 4) {
												$friends_sql_msg = "and (a.card_title like '%$search_str2%' or a.card_name like '%$search_str2%' or a.card_company like '%$search_str2%' or a.card_position like '%$search_str2%' or a.card_keyword like '%$search_str2%')";
											} else {
												$friends_sql_msg = "and (friends_name like '%$search_str2%' or friends_phone like '%$search_str2%')";
											}
										} else {
											$friends_sql_msg = "";
										}

										if ((int)$search_type == 2) {
											$sql5 = "select count(idx) from Gn_Iam_Name_Card where group_id is NULL and phone_display = 'Y' $friends_sql_msg";
										} else if ((int)$search_type == 3) {
											$sql5 = "select count(idx) from Gn_Iam_Name_Card a inner join Gn_Member b on a.mem_id=b.mem_id where a.group_id is NULL and a.phone_display = 'Y' and b.site='$site_name_friends' $friends_sql_msg";
										} else if ((int)$search_type == 4) {
											$sql5 = "select count(idx) from Gn_Iam_Name_Card a inner join Gn_Member b on a.mem_id=b.mem_id where a.group_id is NULL and a.phone_display = 'Y' and b.recommend_id='{$_SESSION['iam_member_id']}' and $friends_sql_msg";
										} else {
											$sql5 = "select count(idx) from Gn_Iam_Friends where mem_id = '$mem_id' $friends_sql_msg";
										}

										$result5 = mysqli_query($self_con,$sql5);
										$comment_row5 = mysqli_fetch_array($result5);
										$row_num5 = $comment_row5[0];

										$list2 = 10; //한 페이지에 보여줄 개수
										$block_ct2 = 10; //블록당 보여줄 페이지 개수

										if ($_GET['page2']) {
											$page2 = $_GET['page2'];
										} else {
											$page2 = 1;
										}

										$block_num2 = ceil($page2 / $block_ct2); // 현재 페이지 블록 구하기
										$block_start2 = (($block_num2 - 1) * $block_ct2) + 1; // 블록의 시작번호
										$block_end2 = $block_start2 + $block_ct2 - 1; //블록 마지막 번호
										$total_page2 = ceil($row_num5 / $list2); // 페이징한 페이지 수 구하기
										if ($block_end2 > $total_page2) $block_end2 = $total_page2; //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
										$total_block2 = ceil($total_page2 / $block_ct2); //블럭 총 개수
										$start_num2 = ($page2 - 1) * $list2; //시작번호 (page-1)에서 $list를 곱한다.

										if ((int)$search_range2 == 1) {
											$friends_sql_msg = $friends_sql_msg . " order by friends_name";
										} else if ((int)$search_range2 == 2) {
											$friends_sql_msg = $friends_sql_msg . " order by friends_name desc";
										} else if ((int)$search_range2 == 3) {
											$friends_sql_msg = $friends_sql_msg . " order by idx desc";
										} else if ((int)$search_range2 == 4) {
											$friends_sql_msg = $friends_sql_msg . " order by idx";
										} else {
											$friends_sql_msg = $friends_sql_msg . " order by idx desc";
										}

										if ($show_all == "N") {
											$limit_str = "limit " . $start_num2 . ", " . $list2;
										} else {
											$limit_str = "";
										}

										if ((int)$search_type == 2) {
											$sql6 = "select idx as friends_card_idx, card_short_url as friends_url, card_name as friends_name,card_company as friends_company,
                                            card_phone as friends_phone,mem_id as profile_mem_id from Gn_Iam_Name_Card where group_id is NULL and phone_display = 'Y' $friends_sql_msg " . $limit_str;
										} else if ((int)$search_type == 3) {
											$sql6 = "select a.idx as friends_card_idx, a.mem_id as profile_mem_id, a.card_short_url as friends_url, a.card_name as friends_name,
                                                    a.card_company as friends_company, a.card_phone as friends_phone from Gn_Iam_Name_Card a inner join Gn_Member b on a.mem_id=b.mem_id where a.group_id is NULL and phone_display = 'Y' and b.site='$site_name_friends' $friends_sql_msg " . $limit_str;
										} else if ((int)$search_type == 4) {
											$sql6 = "select a.idx as friends_card_idx, a.mem_id as profile_mem_id, a.card_short_url as friends_url, a.card_name as friends_name,
                                                    a.card_company as friends_company, a.card_phone as friends_phone from Gn_Iam_Name_Card a inner join Gn_Member b on a.mem_id=b.mem_id where b.recommend_id='{$_SESSION['iam_member_id']}' and a.group_id is NULL and phone_display = 'Y' $friends_sql_msg " . $limit_str;
										} else {
											$sql6 = "select f.*, c.mem_id as profile_mem_id from Gn_Iam_Friends f inner join Gn_Iam_Name_Card c on f.friends_card_idx = c.idx where f.mem_id = '$mem_id' $friends_sql_msg " . $limit_str;
										}

										$result6 = mysqli_query($self_con,$sql6) or die(mysqli_error($self_con));
										while ($row6 = mysqli_fetch_array($result6)) {
											$diplay_sql = "select main_img1 , card_name as friends_name, card_company as friends_company, card_phone as friends_phone, phone_display, mem_id from Gn_Iam_Name_Card where idx = '$row6[friends_card_idx]'";
											$diplay_result = mysqli_query($self_con,$diplay_sql) or die(mysqli_error($self_con));
											$diplay_row = mysqli_fetch_array($diplay_result);
											$friends_main_img = $diplay_row['main_img1'];
											if (!$friends_main_img) {
												$friends_main_img = "img/profile_img.png";
											} ?>
											<li class="list-item">
												<div class="item-wrap">
													<div class="thumb">
														<div class="thumb-inner">
															<? if ($_SESSION['iam_member_id'] == $mem_id) {
																if ((int)$search_type != 1) { ?>
																	<a href="/?<?= $row6['friends_url'] ?>">
																		<img src="<?= $friends_main_img ?>" id="friends_logo" class="friends_logo" style="max-height:40px;height:100%;object-fit:cover;">
																	</a>
																<? } else { ?>
																	<a href="http://<?= $row6['friends_url'] ?>">
																		<img src="<?= $friends_main_img ?>" id="friends_logo" class="friends_logo" style="max-height:40px;height:100%;object-fit:cover;">
																	</a>
																	<? }
															} else {
																if ($diplay_row['phone_display'] == "Y") {
																	if ((int)$search_type != 1) { ?>
																		<a href="/?<?= $row6['friends_url'] ?>">
																			<img src="<?= $friends_main_img ?>" id="friends_logo" class="friends_logo" style="max-height:40px;height:100%;object-fit:cover;">
																		</a>
																	<? } else { ?>
																		<a href="http://<?= $row6['friends_url'] ?>">
																			<img src="<?= $friends_main_img ?>" id="friends_logo" class="friends_logo" style="max-height:40px;height:100%;object-fit:cover;">
																		</a>
																	<? }
																} else { ?>
																	<a href="javascript:alert('비공개 아이엠 입니다.');">
																		<img src="<?= $friends_main_img ?>" id="friends_logo" class="friends_logo" style="max-height:40px;height:100%;object-fit:cover;">
																	</a>
															<? }
															} ?>
														</div>
													</div>
													<div class="info">
														<div class="upper">
															<span class="name">
																<? if ($diplay_row['phone_display'] == "Y") {
																	if ($_SESSION['iam_member_id'] == $mem_id) { ?>
																		<?= $diplay_row['friends_name'] ?>
																	<? } else { ?>
																		<?= iconv_substr($diplay_row['friends_name'], 0, 1, "utf-8") . "**" ?>
																	<? }
																} else {
																	if ($_SESSION['iam_member_id'] == $mem_id) { ?>
																		<?= $diplay_row['friends_name'] ?>
																	<? } else { ?>
																		<?= iconv_substr($diplay_row['friends_name'], 0, 1, "utf-8") . "**" ?>
																<? }
																} ?>
															</span>
															<span class="company"><?= $diplay_row['friends_company'] ?></span>
														</div>
														<div class="downer">
															<? if ($diplay_row['phone_display'] == "Y") {
																if ($_SESSION['iam_member_id'] == $mem_id) { ?>
																	<a href="tel:<?= $diplay_row['friends_phone'] ?>"><?= $diplay_row['friends_phone'] ?></a>
																<? } else { ?>
																	<?= iconv_substr($diplay_row['friends_phone'], 0, 6, "utf-8") . "**-****" ?>
																<? }
															} else {
																if ($_SESSION['iam_member_id'] == $mem_id) { ?>
																	<a href="tel:<?= $diplay_row['friends_phone'] ?>"><?= $diplay_row['friends_phone'] ?></a>
																<? } else { ?>
																	<?= iconv_substr($diplay_row['friends_phone'], 0, 6, "utf-8") . "**-****" ?>
															<? }
															} ?>
														</div>
													</div>
													<? if ($_SESSION['iam_member_id'] == $mem_id) {
														$mem_sql = "select mem_name from Gn_Member where mem_id = '$row6[profile_mem_id]'";
														$mem_result = mysqli_query($self_con,$mem_sql);
														$mem_row = mysqli_fetch_array($mem_result);
														if ((int)$search_type != 1) {
															$myFriends_sql = "select count(idx) from Gn_Iam_Friends where mem_id = '$mem_id' and friends_card_idx = '$row6[friends_card_idx]'";
															$myFriends_result = mysqli_query($self_con,$myFriends_sql);
															$myFriends_row = mysqli_fetch_array($myFriends_result);
															$myFriends_row_num = $myFriends_row[0];
															if ($myFriends_row_num) { ?>
																<div class="chat" style="text-align: right;width:20px">
																	<img src="/iam/img/star/icon-frenz2.PNG" />
																</div>
															<? } ?>
															<div class="check">
																<input type="checkbox" name="friends_chk" id="inputItem<?= $row6['friends_card_idx'] ?>" class="friends checkboxes input css-checkbox ####<?= $row6['profile_mem_id'] ?>" onclick='friends_chk_count() ' value="<?= $row6['profile_mem_id'] ?>" data-name="<?= $mem_row['mem_name'] ?>">
																<label for="inputItem<?= $row6['friends_card_idx'] ?>" class="css-label cb0"></label>
																<input type="hidden" name="friends_idx<?= $row6['friends_card_idx'] ?>" id="friends_idx<?= $row6['friends_card_idx'] ?>" value="<?= $diplay_row['friends_phone'] ?>">
															</div>
														<? } else { ?>
															<div class="check">
																<input type="checkbox" name="friends_chk" id="inputItem<?= $row6['friends_card_idx'] ?>" class="friends checkboxes input css-checkbox ####<?= $row6['profile_mem_id'] ?>" onclick='friends_chk_count() ' value="<?= $row6['profile_mem_id'] ?>" data-name="<?= $mem_row['mem_name'] ?>">
																<label for="inputItem<?= $row6['friends_card_idx'] ?>" class="css-label cb0"></label>
																<input type="hidden" name="friends_idx<?= $row6['idx'] ?>" id="friends_idx<?= $row6['idx'] ?>" value="<?= $diplay_row['friends_phone'] ?>">
															</div>
													<? }
													} ?>
												</div>
											</li>
										<? } ?>
									</ul>
								</div>

								<div class="pagination" style="<?= $style_page ?>">
									<ul>
										<?
										if ($page2 <= 1) { //만약 page가 1보다 크거나 같다면 빈값
										} else {
											$pre2 = $page2 - 1; //pre변수에 page-1을 해준다 만약 현재 페이지가 3인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함
											echo "<li class='arrow'><a href='?sendtype=$type&search_type=$search_type&search_str2=$search_str2&search_range2=$search_range2&page2=$pre2'><i class='fa fa-angle-left' aria-hidden='true'></i></a></li>";
										}
										for ($i = $block_start2; $i <= $block_end2; $i++) {
											if ($page2 == $i) { //만약 page가 $i와 같다면
												echo "<li class='active'><span>$i</span></li>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
											} else {
												echo "<li><a href='?sendtype=$type&search_type=$search_type&search_str2=$search_str2&search_range2=$search_range2&page2=$i'>$i</a></li>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
											}
										}
										if ($block_num2 >= $total_block2) { //만약 현재 블록이 블록 총개수보다 크거나 같다면 빈 값
										} else {
											$next2 = $page2 + 1; //next변수에 page + 1을 해준다.
											echo "<li class='arrow'><a href='?sendtype=$type&search_type=$search_type&search_str2=$search_str2&search_range2=$search_range2&page2=$next2'><i class='fa fa-angle-right' aria-hidden='true'></i></a></li>"; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
										}
										?>
									</ul>
								</div>
							</div>
						</div>
						<div class="box-footer" style="margin-top:0px;">
							<div class="inner clearfix">
								<div class="right">
									<!-- <a href="#"><i class="icon arrow"></i></a> -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</main>
		<div style="text-align:center;">
			<button type="button" class="btn btn-secondary" style="background:#ccc;padding:10px 30px;" onclick="window.close();"> 취 소 </button>
			<button type="button" class="btn btn-primary" style="padding:10px 30px;" onclick="send_profile('<?= $type ?>')"> 전 송 </button>
		</div>
	</div>
	<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
	<script>
		function enterkey(v1) {
			if (window.event.keyCode == 13) {
				// 엔터키가 눌렸을 때 실행할 내용
				friends_submit('<?= $show_all ?>');
			}
		}
		//프렌즈 정렬
		function friends_range(v1) {
			if (Number($("#search_range2").val()) == 1 && Number(v1) == 1) {
				location.href = "?sendtype=<?= $type ?>&show_all=<?= $show_all ?>&search_type=<?= $search_type ?>&search_range2=2&search_str2=<?= $search_str2 ?>"
			} else if (Number($("#search_range2").val()) == 2 && Number(v1) == 1) {
				location.href = "?sendtype=<?= $type ?>&show_all=<?= $show_all ?>&search_type=<?= $search_type ?>&search_range2=1&search_str2=<?= $search_str2 ?>"
			} else if (Number($("#search_range2").val()) == 3 && Number(v1) == 3) {
				location.href = "?sendtype=<?= $type ?>&show_all=<?= $show_all ?>&search_type=<?= $search_type ?>&search_range2=4&search_str2=<?= $search_str2 ?>"
			} else if (Number($("#search_range2").val()) == 4 && Number(v1) == 3) {
				location.href = "?sendtype=<?= $type ?>&show_all=<?= $show_all ?>&search_type=<?= $search_type ?>&search_range2=3&search_str2=<?= $search_str2 ?>"
			} else {
				location.href = "?sendtype=<?= $type ?>&show_all=<?= $show_all ?>&search_type=<?= $search_type ?>&search_range2=" + v1 + "&search_str2=<?= $search_str2 ?>"
			}
		}
		//프렌즈 검색
		function friends_submit(val) {
			location.href = "?sendtype=<?= $type ?>&show_all=" + val + "&search_type=" + $("#search_type").val() + "&search_str2=" + $("#search_str2").val();
		}
		//프렌즈 체크박스
		function friends_chk_count() {
			$("#friends_chk_count").text($("input[name=friends_chk]:checked").length + "개 선택됨");
		}

		function groupCheckClick(e) {
			var checkboxes = $(".friends.checkboxes");
			if (e.checked) {
				for (var i = 0; i < checkboxes.length; i++) {
					$(checkboxes[i]).prop("checked", true);
				}
			} else {
				for (var i = 0; i < checkboxes.length; i++) {
					$(checkboxes[i]).prop("checked", false);
				}
			}
			friends_chk_count();
		}

		function send_profile(val) {
			// var id_list = opener.$('#contents_share_id').find("a");
			var id_list = opener.$('#card_send_id').val();
			var id_list_con = opener.$('#contents_send_id').val();
			var id_list_notice = opener.$('#notice_send_id').val();
			var index = 0;
			var id_array = [];
			var name_array = [];
			// id_list.each(function () {
			// 	id_array[index++] = $(this).data("id");
			// });
			if (val == "card_send") {
				if (id_list != "") {
					id_array = id_list.split(",");
					index += id_array.length;
				}
			} else if (val == "contents_send") {
				if (id_list_con != "") {
					id_array = id_list_con.split(",");
					index += id_array.length;
				}
			} else if (val == "notice_send") {
				if (id_list_notice != "") {
					id_array = id_list_notice.split(",");
					index += id_array.length;
				}
			}
			var count = 0;
			for (var i = 0; i < $("input[name=friends_chk]:checked").length; i++) {
				var str = $("input[name=friends_chk]:checked").eq(i).val();
				var name = $("input[name=friends_chk]:checked").eq(i).data("name");
				var isExist = false;
				for (var j = 0; j < id_array.length; j++) {
					if (id_array[j] == str || str == '<?= $_SESSION['iam_member_id'] ?>') {
						isExist = true;
						break;
					}
				}
				if (!isExist) {
					id_array[index] = str;
					name_array[index] = name;
					index++;
					count++;
				}
			}
			// return;
			if (val == "card_send") {
				var oldCount = opener.$('#card_send_id_count').data('num');
				opener.$('#card_send_id').val(id_array.toString());
				opener.$('#card_send_id_count').data('num', count + oldCount);
				opener.$('#card_send_id_count').val((count + oldCount) + "건");
			} else if (val == "contents_send") {
				var oldCount = opener.$('#contents_send_id_count').data('num');
				opener.$('#contents_send_id').val(id_array.toString());
				opener.$('#contents_send_id_count').data('num', count + oldCount);
				opener.$('#contents_send_id_count').val((count + oldCount) + "건");
			} else if (val == "notice_send") {
				var oldCount = opener.$('#notice_send_id_count').data('num');
				opener.$('#notice_send_id').val(id_array.toString());
				opener.$('#notice_send_id_count').data('num', count + oldCount);
				opener.$('#notice_send_id_count').val((count + oldCount) + "건");
			} else {
				var oldCount = opener.$('#contents_share_id_count').data('num');
				opener.add_share_id(id_array.toString(), name_array.toString());
				opener.$('#contents_share_id_count').data('num', count + oldCount);
				opener.$('#contents_share_id_count').val((count + oldCount) + "건");
			}
			window.close();
		}
	</script>
</body>

</html>