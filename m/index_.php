<?php
include $_SERVER['DOCUMENT_ROOT']."/m/include/header.inc.php";
if($_GET['key'] && $_GET['key'] == session_id()) {
	echo "<script>console.log('". $_GET['key']."');</script>";
	echo "<script>window.location = '/m/index.php';</script>";
}
?>

<main id="main" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
	<section id="main-slider"><!-- 메인 슬라이드 영역 시작 -->
		<div id="mainSlider">
		<?$query = "select *
                from Gn_Ad_Manager where ad_position='T' and use_yn='Y' and  CURDATE() between send_start_date and send_end_date";
    		$res = mysqli_query($self_con,$query);
    		while($data = mysqli_fetch_array($res)) {?>
				<a href="<?php echo $data['move_url'];?>" target="_blank" class="slider-item" style="background-image: url(<?php echo $data['img_url'];?>);">
					<div class="outer-wrap">
						<div class="inner-wrap">
							<p class="sub-title"><!--<?php echo $data['title'];?>--></p>
						</div>
					</div>
				</a>
			<?}?>
		</div>
		<div class="slider-arrows"></div>
	</section><!-- 메인 슬라이드 영역 끝 -->

	<section id="main-menus"><!-- 메인화면 메뉴 영역 시작 -->
		<div class="menu-list clearfix">
			<a href="/iam/" class="menu-item">
				<div class="outer">
					<div class="inner">
						<span class="icon-wrap">
							<img src="img/main/icon-1.png">
						</span>
						<p class="item-name">아이엠</p>
						<p class="item-sub">1인모바일홈피</p>
					</div>
				</div>
			</a>
			<a href="javascript:goOnlyOneApp()" class="menu-item">
				<div class="outer" >
					<div class="inner">
						<div class="star"></div>
						<span class="icon-wrap">
							<img src="img/main/icon-5.png">
						</span>
						<p class="item-name">문자발송</p>
						<p class="item-sub">폰-PC연동문자</p>
					</div>
				</div>
			</a>
			<a href="javascript:goCallbackApp()" class="menu-item">
				<div class="outer">
					<div class="inner">
						<span class="icon-wrap">
							<img src="img/main/icon-6.png">
						</span>
						<p class="item-name">콜백문자</p>
						<p class="item-sub">대상맞춤콜백전송</p>
					</div>
				</div>
			</a>
			
			<a href="/sub_2.php" class="menu-item">
				<div class="outer">
					<div class="inner">
						<span class="icon-wrap">
							<img src="img/main/icon-7.png">
						</span>
						<p class="item-name">
							디비수집
						</p>
						<p class="item-sub">타겟디비자동수집</p>
					</div>
				</div>
			</a>

			<a href="/sub_8.php" class="menu-item">
				<div class="outer">
					<div class="inner">
						<span class="icon-wrap">
							<img src="img/main/icon-13.png">
						</span>
						<p class="item-name">셀링자동화<p>
						<p class="item-sub">통합셀링플랫폼</p>

						</p>
					</div>
				</div>
			</a>
            <!--
			<a href="javascript:goCallbackCamerapApp()" class="menu-item">
				<div class="outer">
					<div class="inner">
						<span class="icon-wrap">
							<img src="img/main/icon-9.png">
						</span>
						<p class="item-name">
							원스텝문자
						</p>
						<p class="item-sub">단계별자동홍보</p>
					</div>
				</div>
			</a>
			-->
			
			<a href="/sub_12.php" class="menu-item">
				<div class="outer">
					<div class="inner">
						<span class="icon-wrap">
							<img src="img/main/icon-9.png">
						</span>
						<p class="item-name">
							원스텝문자
						</p>
						<p class="item-sub">단계별자동홍보</p>
					</div>
				</div>
			</a>
			
                    
			<!--<a href="/sub_9.php#Introduce" class="menu-item">
				<div class="outer">
					<div class="inner">
						<span class="icon-wrap">
							<img src="img/main/icon-2.png">
						</span>
						<p class="item-name">메시지카피</p>
						<p class="item-sub">무스팸고회신</p>
					</div>
				</div>
			</a>
			<a href="/sub_13.php" class="menu-item">
				<div class="outer">
					<div class="inner">
						<span class="icon-wrap">
							<img src="img/main/icon-3.png">
						</span>
						<p class="item-name">온리원쇼핑</p>
						<p class="item-sub">작은가게비밀</p>
					</div>
				</div>
			</a>
			<a href="/m/agree.php" class="menu-item">
				<div class="outer">
					<div class="inner">
						<span class="icon-wrap">
							<img src="img/main/icon-8.png">
						</span>
						<p class="item-name">
							SNS홍보
						</p>
						<p class="item-sub">인플루언서제휴</p>
					</div>
				</div>
			</a>-->
		</div>
	</section><!-- // 메인화면 메뉴 영역 끝 -->

	<div class="banner-wrap"><!-- 하단 베너 영역 -->
	<?	$query = "select *
                from Gn_Ad_Manager where ad_position='B' and use_yn='Y' and  CURDATE() between send_start_date and send_end_date limit 1";
    	$res = mysqli_query($self_con,$query);
    	while($data = mysqli_fetch_array($res)) {?>
				<a href="<?php echo $data['move_url'];?>" target="_blank">
					<img src="<?php echo $data['img_url'];?>">
				</a>
		<?}?>
	</div><!-- // 하단 베너 영역 -->
</main><!-- // 컨텐츠 영역 시작 -->
<?php include $_SERVER['DOCUMENT_ROOT']."/m/include/menu.inc.php"; ?>
<?php include $_SERVER['DOCUMENT_ROOT']."/m/include/footer.inc.php"; ?>
<script>
$(function(){
	$('#mainSlider').slick({
		appendArrows: $('.slider-arrows'),
		prevArrow: '<button class="arrows prev"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
        nextArrow: '<button class="arrows next"><i class="fa fa-angle-right" aria-hidden="true"></i></button>',
	});
});
setInterval(function(){
	$('.next').click();
}, 3000);
function goOnlyOneApp() {
    //goOnlyOneCallback('사용자아이디'); 
    try {
        AppScript.goOnlyOneApp('<?php echo $_SESSION[one_member_id];?>');
    } catch(e) {
        openAndroid();
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

   
function goCallbackApp() {
    //goOnlyOneCallback('사용자아이디'); 
    try {
        AppScript.goCallbackApp('<?php echo $_SESSION[one_member_id];?>');
    } catch(e) {
        openAndroid();
    }
}

function goCallbackCamerapApp() {
    //goOnlyOneCallback('사용자아이디'); 
    try {
        AppScript.goCallbackCamerapApp('<?php echo $_SESSION[one_member_id];?>');
    } catch(e) {
        openAndroid();
    }
}
/*
/*

function openAndroid(){
    var userAgent = navigator.userAgent.toLowerCase();
    var visitedAt = (new Date()).getTime();
    
    if ( userAgent.match(/chrome/) ) { 
        setTimeout(function() {
            location.href = 'https://url.kr/JX9P7u';
        }, 1000);
        
        location.href = 'intent://onlyone#Intent;scheme=onlyoneapp;package=mms5.onepagebook.com.onlyonesms;S.browser_fallback_url=http%3A%2F%2Furl.kr%2FJX9P7u;end';
    }else{
        setTimeout(function() {
            if((new Date()).getTime() - visitedAt < 2000) {
                location.href = "https://url.kr/JX9P7u";
            }
        }, 500);
        
        var iframe = document.createElement( 'iframe' );
        iframe.style.visivility = 'hidden';
        iframe.src = 'onlyone://onlyoneapp';
        document.body.appendChild(iframe);
        document.body.removeChild(iframe);
    }
}
*/


function openAndroid(){
    var userAgent = navigator.userAgent.toLowerCase();
    var visitedAt = (new Date()).getTime();
    
    if ( userAgent.match(/chrome/) ) { 
        setTimeout(function() {
            location.href = 'https://url.kr/JX9P7u';
        }, 1000);
        
        location.href = 'intent://onlyone#Intent;scheme=onlyoneapp;package=mms5.onepagebook.com.onlyonesms;S.browser_fallback_url=http%3A%2F%2Furl.kr%2FJX9P7u;end';
    }else{
        setTimeout(function() {
            if((new Date()).getTime() - visitedAt < 2000) {
                location.href = "https://url.kr/JX9P7u";
            }
        }, 500);
        
        var iframe = document.createElement( 'iframe' );
        iframe.style.visivility = 'hidden';
        iframe.src = 'onlyone://onlyoneapp';
        document.body.appendChild(iframe);
        document.body.removeChild(iframe);
    }
}
</script>
