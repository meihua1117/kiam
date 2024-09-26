// a태그 링크가 #일 때 이벤트 제거
$('a[href="#"]').click(function(e){
	e.preventDefault();
});
// end

// Aside 토글 스크립트
$('#toggleMenu').on('click', function(){
	$('body').addClass('active-aside');
});
$('#closeAside').on('click', function(){
	$('body').removeClass('active-aside');
});
// end

// Aside 서브메뉴 토클 스크립트
$('.has-submenu > a').on('click', function(){
	$(this).parents('.has-submenu').toggleClass('active');
	$(this).siblings('.submenu').stop().slideToggle(300);
});
// end

// 설정 팝업 스크립트
$('#activeSetting').on('click', function(){
	$('body').addClass('active-popup');
});
$('#closeSetting').on('click', function(){
	$('body').removeClass('active-popup');
});
// end