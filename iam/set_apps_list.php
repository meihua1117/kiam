<?
	include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
?>
<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<?
if($HTTP_HOST != "kiam.kr") {
	$query = "select * from Gn_Iam_Service where sub_domain like '%".$HTTP_HOST."'";
    $res = mysqli_query($self_con,$query);
    $domainData = mysqli_fetch_array($res);
    if(date("Y-m-d") >= $domainData['contract_end_date']) {
        @header('Content-Type: text/html; charset=UTF-8');
        echo "<script>alert('본 아이엠은 계약기간이 종료되었습니다. 관리자에게 문의해주세요.');hisotory.go(-1);</script>";
        exit;
    }
}

if(!$mem_id) {
	$mem_id = trim($_SESSION['iam_member_id']);
}
if($_GET['type']){
	$type = $_GET['type'];
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
            background: rgb(251,251,251);
        }
	</style>
</head>
<body >
<div id="wrap" class="common-wrap">
	<main id="star" class="common-wrap"  >
	<h3 style="margin:10px;">앱설치 회원리스트</h3>
		<section id="middle"  >
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane  friends-tab-content active in" id="friends" role="tabpanel" aria-labelledby="friends-tab">
					<div class="box-body">
						<?
							$site_info_friends = explode(".",$HTTP_HOST);
							$site_name_friends = $site_info_friends[0];
							if($site_name_friends == "www"){
								$site_name_friends = "kiam";
							}
							$search_str2 = $_GET['search_str2'];
							if(is_null($search_str2)) {
								$search_str2 = "";
							}
						?>
						<div class="search-box clearfix J2" id="friends_search">
							<?if($_SESSION['iam_member_id'] == $mem_id) {?>
							<div class="row" style='margin-left: 8px;margin-bottom: 0px;margin-top: 7px; '>
								<div class="left">
									<div id="friends_chk_count" class="selects">0개 선택됨</div>
								</div>
							</div>
							<div class="row" style="overflow:hidden;">
								<div class="search J_search" style="display:inline;width:60%;margin-right:20px;">
									<button type="button" onclick="friends_submit();" class="submit" style="top: 3px;background: #f9f9f9;font-size: 17px;"><i class="fa fa-search" aria-hidden="true"></i></button>
									<input type="text" name="search_str2" id="search_str2" class="input" value="<?=$search_str?>" onkeyup="enterkey('friends_submit');" style="font-size:12px;height: 25px;background:#f9f9f9;width:50%;outline:none;" placeholder="검색어를 입력해주세요.">
								</div>
								<div style="float:right;padding-top:5px;padding-left:20px;">
									<input type="checkbox" name="cbG01" id="cbG01" class="css-checkbox" onclick='groupCheckClick(this);'>
									<label for="cbG01" class="css-label cb0">전체선택</label>
								</div>
							</div>         
							<?}?>
						</div>
						<div class="inner">
							<div class="contact-list">
								<ul>
									<?
									if($_SESSION['iam_member_subadmin_id'] != "" && $_SESSION['iam_member_subadmin_domain'] == $HTTP_HOST) {
										$do = explode(".", $HTTP_HOST);
										$searchStr .= " and a.site_iam = '$do[0]'";
									}

									$searchStr1 = "";
									if($search_str2 != ""){
										$searchStr1 .= " and (a.mem_id = '$search_str2' or a.mem_name = '$search_str2')";
									}

									$sql5 = "SELECT count(a.mem_code) FROM Gn_Member a LEFT JOIN Gn_MMS_Number b on b.mem_id =a.mem_id WHERE a.is_leave='N'".$searchStr." and (REPLACE(a.mem_phone,'-','')=REPLACE(b.sendnum, '-','') and b.sendnum is not null and b.sendnum != '') ORDER BY a.mem_code desc";

									$result5=mysqli_query($self_con,$sql5);
									$comment_row5=mysqli_fetch_array($result5);
									$row_num5 = $comment_row5[0];

									$list2 = 10; //한 페이지에 보여줄 개수
									$block_ct2 = 10; //블록당 보여줄 페이지 개수

									if($_GET['page2']) {
										$page2 = $_GET['page2'];
									} else {
										$page2 = 1;
									}

									$block_num2 = ceil($page2/$block_ct2); // 현재 페이지 블록 구하기
									$block_start2 = (($block_num2 - 1) * $block_ct2) + 1; // 블록의 시작번호
									$block_end2 = $block_start2 + $block_ct2 - 1; //블록 마지막 번호
									$total_page2 = ceil($row_num5 / $list2); // 페이징한 페이지 수 구하기
									if($block_end2 > $total_page2) $block_end2 = $total_page2; //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
									$total_block2 = ceil($total_page2/$block_ct2); //블럭 총 개수
									$start_num2 = ($page2-1) * $list2; //시작번호 (page-1)에서 $list를 곱한다.

									$limit_str = "limit " . $start_num2 . ", " . $list2;

									$sql6 = "SELECT SQL_CALC_FOUND_ROWS a.mem_code, a.mem_id, a.mem_name, a.mem_phone, a.site,a.site_iam, a.recommend_id,a.mem_type, a.is_leave FROM Gn_Member a LEFT JOIN Gn_MMS_Number b on b.mem_id =a.mem_id WHERE a.is_leave='N'".$searchStr.$searchStr1." and (REPLACE(a.mem_phone,'-','')=REPLACE(b.sendnum, '-','') and b.sendnum is not null and b.sendnum != '') ORDER BY a.mem_code desc ".$limit_str;
// echo $sql6;
									$all_mem_ids = "";
									$sql6_all = "SELECT SQL_CALC_FOUND_ROWS a.mem_id FROM Gn_Member a LEFT JOIN Gn_MMS_Number b on b.mem_id =a.mem_id WHERE a.is_leave='N'".$searchStr." and (REPLACE(a.mem_phone,'-','')=REPLACE(b.sendnum, '-','') and b.sendnum is not null and b.sendnum != '') ORDER BY a.mem_code desc ";
									$res_all = mysqli_query($self_con,$sql6_all) or die(mysqli_error($self_con));
									$k = 1;
									while($row_all = mysqli_fetch_array($res_all)){
										if($k == $row_num5){
											$all_mem_ids .= $row_all[mem_id];
										}
										else{
											$all_mem_ids .= $row_all[mem_id].",";
										}
										$k++;
									}
									// echo $sql6;

									$result6=mysqli_query($self_con,$sql6) or die(mysqli_error($self_con));
									while($row6=mysqli_fetch_array($result6)){
										$diplay_sql="select main_img1, card_phone as friends_phone, card_short_url as friends_url, mem_id from Gn_Iam_Name_Card where mem_id = '$row6[mem_id]' order by idx asc limit 1";
										$diplay_result=mysqli_query($self_con,$diplay_sql) or die(mysqli_error($self_con));
										$diplay_row=mysqli_fetch_array($diplay_result);
										$friends_main_img = $diplay_row[main_img1];
										if(!$friends_main_img) {
											$friends_main_img = "img/profile_img.png";
										}
										if(!$diplay_row[friends_url]){
											$href = "";
										}
										else{
											$href = "/?".$diplay_row[friends_url].$row6['mem_code'];
										}?>
										<li class="list-item">
											<div class="item-wrap">
												<div class="thumb">
													<div class="thumb-inner">
														<a href="<?=$href?>">
															<img src="<?=$friends_main_img?>" id="friends_logo" class="friends_logo" style="max-height:40px;height:100%;object-fit:cover;">
														</a>
													</div>
												</div>
												<div class="info">
													<div class="upper">
														<span class="name">
															<?=$row6['mem_name']?>
														</span>
														<span class="company"><?=$row6[mem_id]?></span>
													</div>
													<div class="downer">
														<a href="tel:<?=$row6[mem_phone]?>"><?=$row6[mem_phone]?></a>
													</div>
												</div>
												<div class="check">
													<input type="checkbox" name="friends_chk" id="inputItem<?=$row6['mem_code']?>" class="friends checkboxes input css-checkbox ####<?=$row6['mem_code']?>" onclick='friends_chk_count() ' value="<?=$row6['mem_code']?>" data-name = "<?=$row6[mem_id]?>">
													<label for="inputItem<?=$row6['mem_code']?>" class="css-label cb0" data-name = "<?=$row6[mem_id]?>"></label>
													<input type="hidden" name="friends_idx<?=$row6['mem_code']?>" id="friends_idx<?=$row6['mem_code']?>" value="<?=$row6['mem_phone']?>">
												</div>
											</div>
										</li>
									<?}?>
								</ul>
							</div>

							<div class="pagination">
								<ul>
									<?
										if($page2 <= 1) { //만약 page가 1보다 크거나 같다면 빈값
										} else {
											$pre2 = $page2-1; //pre변수에 page-1을 해준다 만약 현재 페이지가 3인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함
											echo "<li class='arrow'><a href='?type=$type&search_str2=$search_str2&page2=$pre2'><i class='fa fa-angle-left' aria-hidden='true'></i></a></li>";
										}
										for($i=$block_start2; $i<=$block_end2; $i++){
											if($page2 == $i) { //만약 page가 $i와 같다면
												echo "<li class='active'><span>$i</span></li>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
											} else {
												echo "<li><a href='?type=$type&search_str2=$search_str2&page2=$i'>$i</a></li>"; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
											}
										}
										if($block_num2 >= $total_block2) { //만약 현재 블록이 블록 총개수보다 크거나 같다면 빈 값
										} else {
										    $next2 = $page2 + 1; //next변수에 page + 1을 해준다.
											echo "<li class='arrow'><a href='?type=$type&search_str2=$search_str2&page2=$next2'><i class='fa fa-angle-right' aria-hidden='true'></i></a></li>"; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
										}
									?>								
								</ul>
							</div>						
						</div>
					</div>
					<div class="box-footer" style="margin-top:0px;" >
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
		<button type="button" class="btn btn-secondary" style="background:#ccc;padding:10px 30px;"  onclick = "window.close();">확인</button>
	</div>
</div>
<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
<script>
	function enterkey(v1) {
		if (window.event.keyCode == 13) {
			// 엔터키가 눌렸을 때 실행할 내용
			friends_submit();
		}
	}
    //프렌즈 검색
	function friends_submit(val) {
		location.href = "?type=<?=$type?>&search_str2=" + $("#search_str2").val();
	}
    //프렌즈 체크박스
    function friends_chk_count() {
		var index = 0;
		var id_cnt = 0;
		var count = 0;
		var id_array = [];
		var name_array = [];
		<?if($type == "callback"){?>
		var id_list = opener.$("#app_set_mbs_id_call").val();
		<?}
		else{?>
		var id_list = opener.$("#app_set_mbs_id_daily").val();
		<?}?>
		if(id_list != ""){
			id_array = id_list.split(",");
			index += id_array.length;
		}

		for (var i = 0; i < $("input[name=friends_chk]:checked").length; i++) {
			var str = $("input[name=friends_chk]:checked").eq(i).data("name");
			console.log(str);
			var isExist = false;
			for(var j = 0 ; j < id_array.length ;j++){
				if(id_array[j] == str || str == '<?=$_SESSION['iam_member_id']?>'){
					isExist = true;
					break;
				}
			}
			if(!isExist) {
				id_array[index] = str.trim();
				index++;
				count++;
			}
		}

		console.log(id_array.toString());

		<?if($type == "callback"){?>
		opener.$("#app_set_mbs_id_call").val(id_array.toString());
		opener.$('#app_set_mbs_count_call').val(id_array.length  + "건");
		<?}
		else{?>
		opener.$("#app_set_mbs_id_daily").val(id_array.toString());
		opener.$('#app_set_mbs_count_daily').val(id_array.length  + "건");
		<?}?>

        $("#friends_chk_count").text(id_array.length + "개 선택됨");
    }

	function groupCheckClick(e){
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
		// friends_chk_count();
		<?if($type == "callback"){?>
		opener.$("#app_set_mbs_id_call").val('<?=$all_mem_ids?>');
		opener.$('#app_set_mbs_count_call').val(<?=$row_num5?>  + "건");
		<?}
		else{?>
		opener.$("#app_set_mbs_id_daily").val('<?=$all_mem_ids?>');
		opener.$('#app_set_mbs_count_daily').val(<?=$row_num5?>  + "건");
		<?}?>

		$("#friends_chk_count").text(<?=$row_num5?> + "개 선택됨");
	}

	function send_profile(val){
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
		if(val == "card_send"){
			if(id_list != ""){
				id_array = id_list.split(",");
				index += id_array.length;
			}
		}
		else if(val == "contents_send"){
			if(id_list_con != ""){
				id_array = id_list_con.split(",");
				index += id_array.length;
			}
		}
		else if(val == "notice_send"){
			if(id_list_notice != ""){
				id_array = id_list_notice.split(",");
				index += id_array.length;
			}
		}
		var count = 0;
		for (var i = 0; i < $("input[name=friends_chk]:checked").length; i++) {
			var str = $("input[name=friends_chk]:checked").eq(i).val();
			var name = $("input[name=friends_chk]:checked").eq(i).data("name");
			var isExist = false;
			for(var j = 0 ; j < id_array.length ;j++){
				if(id_array[j] == str || str == '<?=$_SESSION['iam_member_id']?>'){
					isExist = true;
					break;
				}
			}
			if(!isExist) {
				id_array[index] = str;
				name_array[index] = name;
				index++;
				count++;
			}
		}
		// return;
		if(val == "card_send"){
			var oldCount = opener.$('#card_send_id_count').data('num');
			opener.$('#card_send_id').val(id_array.toString());
			opener.$('#card_send_id_count').data('num', count + oldCount);
			opener.$('#card_send_id_count').val((count + oldCount)  + "건");
		}
		else if(val == "contents_send"){
			var oldCount = opener.$('#contents_send_id_count').data('num');
			opener.$('#contents_send_id').val(id_array.toString());
			opener.$('#contents_send_id_count').data('num', count + oldCount);
			opener.$('#contents_send_id_count').val((count + oldCount)  + "건");
		}
		else if(val == "notice_send"){
			var oldCount = opener.$('#notice_send_id_count').data('num');
			opener.$('#notice_send_id').val(id_array.toString());
			opener.$('#notice_send_id_count').data('num', count + oldCount);
			opener.$('#notice_send_id_count').val((count + oldCount)  + "건");
		}
		else{
			var oldCount = opener.$('#contents_share_id_count').data('num');
			opener.add_share_id(id_array.toString() , name_array.toString());
			opener.$('#contents_share_id_count').data('num', count + oldCount);
			opener.$('#contents_share_id_count').val((count + oldCount)  + "건");
		}
		window.close();
	}
</script>
</body>
</html>