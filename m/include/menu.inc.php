<?
if($member_1){
	$card_sql = "select * from Gn_Iam_Name_Card where group_id is NULL and mem_id = '$member_1[mem_id]' order by req_data asc";
}else{
	if ($HTTP_HOST != "kiam.kr") //분양사사이트이면
		$query = "select * from Gn_Iam_Service where sub_domain like 'http://" . $HTTP_HOST . "'";
	else
		$query = "select * from Gn_Iam_Service where sub_domain like 'http://www.kiam.kr'";
	$res = mysql_query($query);
	$domainData = mysql_fetch_array($res);
	$first_card_idx = $domainData['profile_idx'];//분양사의 1번 카드아이디
	$card_sql = "select * from Gn_Iam_Name_Card where idx = '$first_card_idx'";
}
$result = mysql_query($card_sql);
$card_row = mysql_fetch_array($result);
$card_url = $card_row[card_short_url];//분양사이트 1번 네임카드 url
$card_name = str_replace("'","",$card_row['card_name']);
$mem_sql = "select mem_code from Gn_Member where mem_id='$card_row[mem_id]'";
$mem_res = mysql_query($mem_sql);
$mem_row = mysql_fetch_array($mem_res);
$card_url.=$mem_row[mem_code];
?>
<aside id="aside" style="overflow:scroll;"><!-- 사이브 메뉴 시작 -->
	<div class="aside-header">
		<div class="inner-wrap clearfix">
			<div class="logo">
				<a href="/m/"><img src="/iam/img/common/logo-2.png" style="height:20px"></a>
			</div>
			<div class="util">
				<!--<a href="#" id="activeSetting" class="setting"><i class="fa fa-cog" aria-hidden="true"></i></a> <!-- 설정아이콘-->
				<a href="#" id="closeAside"><i class="fa fa-times" aria-hidden="true"></i></a>
			</div>
			<?php if($_SESSION[one_member_id] != "") { ?>
			<div class="downer">
				<a href="/iam/mypage.php" class="badge">마이페이지</a>
			</div>
			<?}?>
		</div>
	</div>
	<nav class="aside-menu">
		<ul>
			<?php if($_SESSION[one_member_id] == "") {?>
			<li class="menu-item">
				<a href="/m/login.php">아이엠 로그인</a>
			</li>
			<li class="menu-item">
				<a href="/m/join.php">회원가입</a>
			</li>
			<li class="menu-item">
				<a onclick = "addMainBtn('<?=$card_name?>','?<?=$card_url?>')">폰 홈화면 추가</a>
			</li>
			<li class="menu-item">
				<a href="/m/search_id.php">아이디 / 비번찾기</a>
			</li>
			<?php }else{?>
			<li class="menu-item">
				<a href="/m/ajax/logout.php">아이엠 로그아웃</a>
			</li>
			<li class="menu-item">
				<a onclick = "addMainBtn('<?=$card_name?>','?<?=$card_url?>')">폰 홈화면 추가</a>
			</li>
			<?
				$mem_sql = "select site_iam from Gn_Member where mem_id='$_SESSION[one_member_id]'";
				$mem_res = mysql_query($mem_sql);
				$mem_row = mysql_fetch_array($mem_res);
				$site = $mem_row[site_iam];
				if($site){
					if($site == "kiam")
						$href = "http://www.kiam.kr/index.php";
					else
						$href = "http://".$site.".kiam.kr/index.php";
				}else{
					$href = "/index.php";
				}
			?>
			<li class="menu-item">
				<a href="<?=$href?>">아이엠바로가기</a>
			</li>
			<?php }?>
			<li class="menu-item">
				<a href="https://sites.google.com/view/onlyselling2/%ED%99%88" target="_blank">매뉴얼 보기</a>
			</li>
			<li class="menu-item">
				<a href="/m/terms.php">서비스약관</a>
			</li>
			<li class="menu-item">
				<a href="/privacy.php">개인정보정책</a>
			</li>
			<li class="menu-item has-submenu">
				<a href="#">고객센터</a>
				<ul class="submenu">
					<li class="submenu-item"><a href="/cliente_list.php?status=1">공지사항</a></li>
					<!-- li class="submenu-item"><a href="/cliente_list.php?status=4">FAQ</a></!-->
					<li class="submenu-item"><a href="https://accounts.kakao.com/login?continue=http%3A%2F%2Fpf.kakao.com%2F_jVafC%2Fchat">카카오상담</a></li>
					<!-- li class="submenu-item"><a href="/cliente_list.php?status=2">1:1상담</a></!-->
				</ul>
			</li>
			<li class="menu-item">
				<a href="/iam/exit.php">회원탈퇴</a>
			</li>
		</ul>
	</nav>
</aside><!-- // 사이브 메뉴 끝 -->

<div class="popup-layer" id="popupSetting"><!-- 설정 레이어 시작 -->
	<div class="popup-header">
		<div class="inner-wrap clearfix">
			<div class="logo">
				<img src="/iam/img/common/logo-2.png">
			</div>
			<?php if($_SESSION[one_member_id] != "") {?>
			<div class="util">
				<a href="#" id="closeSetting"><i class="fa fa-times" aria-hidden="true"></i></a>
			</div>
			<?php }?>
			<div class="downer">
				<span class="badge">설정</span>
				<span class="version">버전정보 Ver. 1.0.12</span>
			</div>
		</div>
	</div>
	<nav class="popup-menu">
		<ul>
			<?php if($_SESSION[one_member_id] != "") {?>
			<li class="menu-item">
				<span class="menu-name">아이엠 사용하기</span>
				<div class="menu-toggle">
					<div class="toggle-wrap">
						<input type="checkbox" class="check" id="toggle_01" checked>
						<label for="toggle_01"></label>
					</div>
				</div>
			</li>
			<li class="menu-item">
				<span class="menu-name">온리원문자앱 사용하기</span>
				<div class="menu-toggle">
					<div class="toggle-wrap">
						<input type="checkbox" class="check" id="toggle_02" checked>
						<label for="toggle_02"></label>
					</div>
				</div>
			</li>
			<li class="menu-item">
				<span class="menu-name">온리원콜백앱 사용하기</span>
				<div class="menu-toggle">
					<div class="toggle-wrap">
						<input type="checkbox" class="check" id="toggle_03" checked>
						<label for="toggle_03"></label>
					</div>
				</div>
			</li>
			<li class="menu-item">
				<span class="menu-name">온리원쇼핑몰/마이몰 사용하기</span>
				<div class="menu-toggle">
					<div class="toggle-wrap">
						<input type="checkbox" class="check" id="toggle_04" checked>
						<label for="toggle_04"></label>
					</div>
				</div>
			</li>
			<li class="menu-item">
				<span class="menu-name">원스텝 홍보솔루션 사용하기</span>
				<div class="menu-toggle">
					<div class="toggle-wrap">
						<input type="checkbox" class="check" id="toggle_05" checked>
						<label for="toggle_05"></label>
					</div>
				</div>
			</li>
			<li class="menu-item">
				<span class="menu-name">온리원디버솔루션 사용하기</span>
				<div class="menu-toggle">
					<div class="toggle-wrap">
						<input type="checkbox" class="check" id="toggle_06" checked>
						<label for="toggle_06"></label>
					</div>
				</div>
			</li>

			<?php }?>
		</ul>
	</nav>
</div><!-- 설정 레이어 끝 -->
<div id="ajax_div" style="display:none"></div>
<script>
	$(function(){
		$('#toggle_01').on("change",function(){
			$.ajax({
				 type:"POST",
				 url:"ajax/settings.php",
				 data:{
						"exe_1":$(this).prop("checked")==true?"Y":"N"
					  },
				 success:function(data){
					}
				});
		});

		$('#toggle_02').on("change",function(){
			$.ajax({
				 type:"POST",
				 url:"ajax/settings.php",
				 data:{
						"exe_2":$(this).prop("checked")==true?"Y":"N"
					  },
				 success:function(data){
					}
				});
		});

		$('#toggle_03').on("change",function(){
			$.ajax({
				 type:"POST",
				 url:"ajax/settings.php",
				 data:{
						"exe_3":$(this).prop("checked")==true?"Y":"N"
					  },
				 success:function(data){
					}
				});
		});

		$('#toggle_04').on("change",function(){
			$.ajax({
				 type:"POST",
				 url:"ajax/settings.php",
				 data:{
						"exe_4":$(this).prop("checked")==true?"Y":"N"
					  },
				 success:function(data){
					}
				});
		});

		$('#toggle_05').on("change",function(){
			$.ajax({
				 type:"POST",
				 url:"ajax/settings.php",
				 data:{
						"exe_5":$(this).prop("checked")==true?"Y":"N"
					  },
				 success:function(data){
					}
				});
		});

		$('#toggle_06').on("change",function(){
			$.ajax({
				 type:"POST",
				 url:"ajax/settings.php",
				 data:{
						"exe_6":$(this).prop("checked")==true?"Y":"N"
					  },
				 success:function(data){
					}
				});
		});
	})

	function member_leave()
	{
		if($('#leave_pwd').val() == "") {
			alert('비밀번호를 입력해주세요.');
			return;
		}
		if($('#leave_liyou').val() == "") {
			alert('탈퇴사유를 입력해주세요.');
			return;
		}
		if(confirm('탈퇴하시겠습니까?'))
		{
			$.ajax({
				 type:"POST",
				 url:"/ajax/ajax_session.php",
				 data:{
						member_leave_pwd:$('#leave_pwd').val(),
						member_leave_liyou:$('#leave_liyou').val()
					  },
					  success:function(data){
						$("#ajax_div").html(data);
					  }
			});
		}
	}
	function addMainBtn(title,link) {
		var filter = "win16|win32|win64|mac";
		if(navigator.platform){
			if(0 > filter.indexOf(navigator.platform.toLowerCase())){
			}else{
				//alert('모바일에서만 추가가 가능합니다.');
				var bookmarkURL = window.location.href;
				var bookmarkTitle = document.title;
				var triggerDefault = false;
				if (window.sidebar && window.sidebar.addPanel) {
					// Firefox version < 23
					window.sidebar.addPanel(bookmarkTitle, bookmarkURL, '');
				} else if ((window.sidebar && (navigator.userAgent.toLowerCase().indexOf('firefox') > -1)) || (window.opera && window.print)) {
					// Firefox version >= 23 and Opera Hotlist
					var $this = $(this);
					$this.attr('href', bookmarkURL);
					$this.attr('title', bookmarkTitle);
					$this.attr('rel', 'sidebar');
					$this.off(e);
					triggerDefault = true;
				} else if (window.external && ('AddFavorite' in window.external)) {
					// IE Favorite
					window.external.AddFavorite(bookmarkURL, bookmarkTitle);
				} else {
					// WebKit - Safari/Chrome
					alert((navigator.userAgent.toLowerCase().indexOf('mac') != -1 ? 'Cmd' : 'Ctrl') + '+D 키를 눌러 즐겨찾기에 등록하실 수 있습니다.');
				}
				//return triggerDefault;
				return;
			}
		}
		if(link.search("https://") == -1){ 
			var url = 'https://kiam.kr/'+link; 
		}else{ 
			var url = link; 
		}
		var encodeTitle = encodeURIComponent(title);
		var linkUrl = encodeURIComponent(url);
		var iconUrl = "https://kiam.kr/iam/img/favicon_iam.png";
		setIcon = encodeURIComponent(iconUrl);
		var sm_naver_customUrlScheme = "naversearchapp://addshortcut?url=" + linkUrl + "&icon=" + setIcon + "&title=" + encodeTitle + "&serviceCode=nstore&version=7";

		var userAgent = navigator.userAgent.toLowerCase();
		var iPhone = userAgent.indexOf("iphone");
		var iPad = userAgent.indexOf("ipad");
		var IOS = iPhone + iPad;
		if (IOS == -2) {
			alert(title + '을(를) 홈화면에 추가합니다.\n\n네이버앱이 없는 고객 네이버앱 설치페이지로 이동됩니다.!!');
			window.open(sm_naver_customUrlScheme);
		} else {
			alert("IOS는 직접 홈버튼추가를 사용하셔야합니다.");
		}
	}
</script>