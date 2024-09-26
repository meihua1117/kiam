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
var panelGroupState = function() {
	var lastScrollTop = 0;
    $(window).scroll(function(){
		var $this = $(this);
        st = $this.scrollTop();
        navbar = $('.panel-group');
		if ( st > 140 ) {
            navbar.addClass('scrolled');
        } else {
            navbar.removeClass('scrolled awake');
        }
        if ( navbar.hasClass('scrolled') && st > 240 ) {
            if (st > lastScrollTop){
                navbar.removeClass('awake');
                navbar.addClass('sleep');
            } else {
                navbar.addClass('awake');
                navbar.removeClass('sleep');
            }
            lastScrollTop = st;
        }
    });
    $('.panel-group')
		.mouseenter(function() {
            var $this = $(this);
            $this.addClass('awake');
            $this.removeClass('sleep');
        })
        .mouseleave(function() {
            var $this = $(this);
            $this.addClass('sleep');
            $this.removeClass('awake');
        });
};