<?php
include $_SERVER['DOCUMENT_ROOT']."/m/include/header.inc.php";
if($_GET['key'] && $_GET['key'] == session_id()) {
	echo "<script>console.log('". $_GET['key']."');</script>";
	echo "<script>window.location = '/m/index.php';</script>";
}
?>
<style>
	.btmbanner_list .area_right {
        position: relative;
        overflow: hidden;
        height: 80px;
        padding: 0 60px 0 45px;
    }
    .btmbanner_list .area_right .btmbr_list {
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    .btmbanner_list .area_right .btmbr_list .btmbrl_obj {
        position: relative;
        width: 200%;
        height: 100%;
    }
    .btmbanner_list .area_right .btmbr_list .btmbrl_obj .btmbrl_item {
        float: left;
        height: 100%;
        padding-top: 7px;
    }
    .btmbanner_list .area_right .btmbr_list .btmbrl_obj .btmbrl_item a {
        position: relative;
        display: block;
        height: 100%;
        padding: 0 17px;
		text-align: center;
    }
    .btmbanner_list .area_right .btmbr_ctlbtns {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
    }
    .btmbanner_list .area_right .btmbr_ctlbtns a {
        position: absolute;
        top: 20px;
        width: 14px;
        height: 23px;
        background: no-repeat 0 0;
    }
    .btmbanner_list .area_right .btmbr_ctlbtns .ctl_prev {
        left: 15px;
        background-image: url(/iam/img/btn_slide_pre.png);
    }
    .btmbanner_list .area_right .btmbr_ctlbtns .ctl_next {
        right: 25px;
        background-image: url(/iam/img/btn_slide_next.png);
    }

	#tutorial-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:150;text-align:center;display:none;background-color: grey;opacity: 0.7;}

	.tooltiptext-bottom {
		width: 70%;
		font-size:15px;
		background-color: white;
		color: black;
		text-align: left;
		position: absolute;
		z-index: 200;
		top: 7%;
		left: 15%;
	}

	.title_app{
		text-align: center;
		background-color: rgb(130,199,54);
		padding: 10px;
		font-size: 20px;
		color: white;
	}
	.desc_app{
		padding: 15px;
	}
	.button_app{
		text-align: center;
		padding: 10px;
	}
</style>
<main id="main" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
	<section id="main-slider"><!-- 메인 슬라이드 영역 시작 -->
		<div id="mainSlider">
		<?
			$query = "select * from Gn_App_Home_Manager where ad_position='R' and use_yn='Y' and site_iam='{$site_iam}' order by display_order asc";
    		$res = mysqli_query($self_con,$query);
    		while($data = mysqli_fetch_array($res)) {
				if($is_pay_version){
					$url_top = $data['move_url'];
				}
				else{
					$url_top = "#";
				}?>
				<a href="<?=$url_top;?>" target="_self" class="slider-item" style="background-image: url(<?=$data['img_url'];?>);">
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
            <?
				// if($_SESSION['iam_member_id'] == ""){
				// 	$url = "https://kiam.kr";
				// }else{
				// 	$sql="select site_iam from Gn_Member where mem_id = '{$_SESSION['iam_member_id']}'";
				// 	$resul=mysqli_query($self_con,$sql);
				// 	$row=mysqli_fetch_array($resul);
				// 	if($row[0] == "kiam")
				// 		$url = "https://kiam.kr";
				// 	else if($row[0] != "")
				// 		$url = "https://".$row[0].".kiam.kr";
				// }
			$query = "select * from Gn_App_Home_Manager where ad_position='M' and use_yn='Y' and site_iam='{$site_iam}' order by display_order asc";
			$res = mysqli_query($self_con,$query);
			while($data = mysqli_fetch_array($res)) {
				if(strpos($data[move_url], "/iam/daily_write_iam.php") !== false){
					$href = "javascript:go_move_url('".$_SESSION['iam_member_id']."', '".$data[move_url]."')";
				}
				else{
					$href = $data[move_url];
				}
			?>
			<a href="<?=$href?>" class="menu-item">
				<div class="outer">
					<div class="inner">
						<span class="icon-wrap">
							<img src="<?=$data['img_url'];?>" style="width:24px;">
						</span>
						<p class="item-name"><?=$data['title'];?></p>
					</div>
				</div>
			</a>
			<?}?>
			<!-- <a href="javascript:goOnlyOneApp()" class="menu-item">
				<div class="outer" >
					<div class="inner">
						<div class="star"></div>
						<span class="icon-wrap">
							<img src="img/menu/icon_phone.png" style="width:36px;">
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
							<img src="img/menu/icon_callback.png" style="width:36px;">
						</span>
						<p class="item-name">콜백문자</p>
						<p class="item-sub">대상맞춤콜백전송</p>
					</div>
				</div>
			</a>
			<?if($is_pay_version){
				$href_db1 = "/sub_2.php";
				$href_db2 = "/sub_8.php";
				$href_db3 = "/sub_12.php";
			}
			else{
				$href_db1 = "#";
				$href_db2 = "#";
				$href_db3 = "#";
			}?>
			<a href="<?=$href_db1?>" class="menu-item">
				<div class="outer">
					<div class="inner">
						<span class="icon-wrap">
							<img src="img/menu/icon_dber.png" style="width:36px;">
						</span>
						<p class="item-name">디비수집</p>
						<p class="item-sub">타겟디비자동수집</p>
					</div>
				</div>
			</a>

			<a href="<?=$href_db2?>" class="menu-item">
				<div class="outer">
					<div class="inner">
						<span class="icon-wrap">
							<img src="img/menu/icon_selling.png" style="width:36px;">
						</span>
						<p class="item-name">셀링자동화</p>
						<p class="item-sub">통합셀링플랫폼</p>
					</div>
				</div>
			</a>
			<a href="<?=$href_db3?>" class="menu-item">
				<div class="outer">
					<div class="inner">
						<span class="icon-wrap">
							<img src="img/menu/icon_namecard.png" style="width:36px;">
						</span>
						<p class="item-name">원스텝문자</p>
						<p class="item-sub">단계별자동홍보</p>
					</div>
				</div>
			</a> -->
		</div>
	</section><!-- // 메인화면 메뉴 영역 끝 -->

	<section id="main-menus"><!-- 메인화면 아이엠 마켓 영역 시작 -->
		<div class="menu-list clearfix">
            <?
			$query = "select * from Gn_App_Home_Manager where ad_position='I' and use_yn='Y' and site_iam='{$site_iam}' order by display_order asc";
			$res = mysqli_query($self_con,$query);
			if(mysqli_num_rows($res)){
				$sql_title = "select market_title from Gn_App_Home_Manager where market_title!='' and site_iam='{$site_iam}'";
				$res_title = mysqli_query($self_con,$sql_title);
				$row_title = mysqli_fetch_array($res_title);
			?>
			<h4 style="padding: 25px 25px 0px;"><?=$row_title[0]?$row_title[0]:'IAM마켓'?></h4>
			<?
			}
			while($data = mysqli_fetch_array($res)) {
				if($data[title] == "굿마켓"){
					$href = "javascript:go_market_url('".$_SESSION['iam_member_id']."', '".$data[move_url]."')";
				}
				else{
					$href = $data[move_url];
				}
			?>
			<a href="<?=$href?>" class="menu-item">
				<div class="outer">
					<div class="inner">
						<span class="icon-wrap">
							<img src="<?=$data['img_url'];?>" style="width:24px;">
						</span>
						<p class="item-name"><?=$data['title'];?></p>
					</div>
				</div>
			</a>
			<?}?>
		</div>
	</section><!-- // 메인화면 아이엠 마켓 영역 끝 -->

	<section id="main-slider1"><!-- 메인 슬라이드 아이엠 카드 영역 시작 -->
		<?
		$query = "select * from Gn_App_Home_Manager where ad_position='C' and use_yn='Y' and site_iam='{$site_iam}' order by display_order asc";
		$res = mysqli_query($self_con,$query);
		if(mysqli_num_rows($res)){
			$sql_title = "select card_title from Gn_App_Home_Manager where card_title!='' and site_iam='{$site_iam}'";
			$res_title = mysqli_query($self_con,$sql_title);
			$row_title = mysqli_fetch_array($res_title);	
		?>
		<h4 style="padding: 25px 25px 0px;"><?=$row_title[0]?$row_title[0]:'IAM카드'?></h4>
		<div class="btmbanner_list" style="margin-top: 2px;">
			<div class="layout">
				<div class="area_right">
					<div class="btmbr_list">
						<div class="btmbrl_obj" style="left: 0px;">
							<?
							while($data = mysqli_fetch_array($res)) {
								?>
								<div class="btmbrl_item">
									<a href="<?=$data[move_url]?>" class="menu-item">
										<div class="outer">
											<div class="inner">
												<span class="icon-wrap">
													<img src="<?=$data['img_url'];?>" style="width:24px;">
												</span>
												<p class="item-name" style="font-size: 12px;"><?=$data['title'];?></p>
											</div>
										</div>
									</a>
								</div>
							<?}?>
						</div>
					</div>
					<div class="btmbr_ctlbtns">
						<a class="ctl_prev" data-control="prev"></a>
						<a class="ctl_next" data-control="next"></a>
					</div>
				</div>
				<script>		
					(function () {
						var param = '.btmbanner_list',
							obj = '.btmbrl_item',
							btn = param+' .btmbr_ctlbtns',
							interval = 3000,
							speed = 300,
							viewSize = 1,
							moreSize = 0,
							dir = 'x',
							data = 1,
							auto = false,
							hover = false,
							method = '',
							op1 = false;

						stateScrollObj(param,obj,btn,interval,speed,viewSize,moreSize,dir,data,auto,hover,method,op1);
					})();
				</script>
			</div>
		</div>
		<?}?>
	</section><!-- 메인 슬라이드 아이엠 카드 영역 끝 -->

	<section id="main-menus"><!-- 메인화면 아이엠 공지사항 영역 시작 -->
		<div class="menu-list clearfix" style="margin-bottom: 20px;">
            <?
			$notice_arr = array();
			$data_arr = array();
			$i = 0;

			if($_SESSION['iam_member_id']){
				$mem_type = $member_iam[service_type];
				if($mem_type == 3){//분양자
					$sql_notice = "select seller_id, pay_date, message from Gn_Item_Pay_Result where buyer_id='{$_SESSION['iam_member_id']}' and item_name='공지사항전송' order by pay_date desc limit 1";
					$res_notice = mysqli_query($self_con,$sql_notice);
					$row_notice = mysqli_fetch_array($res_notice);

					if($row_notice[seller_id] != ''){
						$data['title'] = $row_notice['seller_id'];
						$data['date'] = $row_notice['pay_date'];
						$data['domain'] = $member_iam['site_iam'];
						$msg1 = explode("\n", $row_notice['message']);
						for($j = 0; $j < count($msg1); $j++){
							$msg .= "<p>".$msg1[$j]."</p>";
						}
						$data['detail'] = $msg;
						$data_arr[$i] = $data;
						$i++;
					}
				}
				else if($mem_type == 2){//리셀러
					$sql_notice = "select seller_id, pay_date, message from Gn_Item_Pay_Result where buyer_id='{$_SESSION['iam_member_id']}' and item_name='공지사항전송' order by pay_date desc limit 1";
					$res_notice = mysqli_query($self_con,$sql_notice);
					$row_notice = mysqli_fetch_array($res_notice);

					if($row_notice[seller_id] != ''){
						$data['title'] = $row_notice['seller_id'];
						$data['date'] = $row_notice['pay_date'];
						$data['domain'] = $member_iam['site_iam'];
						$msg1 = explode("\n", $row_notice['message']);
						for($j = 0; $j < count($msg1); $j++){
							$msg .= "<p>".$msg1[$j]."</p>";
						}
						$data['detail'] = $msg;
						$data_arr[$i] = $data;
						$i++;
					}
					
					$sql_service = "select mem_id from Gn_Service where sub_domain like '%".$member_iam[site]."%'";
					$res_service = mysqli_query($self_con,$sql_service);
					$row_service = mysqli_fetch_array($res_service);

					if($row_service['mem_id'] != $_SESSION['iam_member_id']){
						$sql_notice = "select seller_id, pay_date, message from Gn_Item_Pay_Result where buyer_id='{$row_service['mem_id']}' and item_name='공지사항전송' order by pay_date desc limit 1";
						$res_notice = mysqli_query($self_con,$sql_notice);
						$row_notice = mysqli_fetch_array($res_notice);

						if($row_notice[seller_id] != ''){
							$data['title'] = $row_notice['seller_id'];
							$data['date'] = $row_notice['pay_date'];
							$data['domain'] = $member_iam['site_iam'];
							$msg1 = explode("\n", $row_notice['message']);
							for($j = 0; $j < count($msg1); $j++){
								$msg .= "<p>".$msg1[$j]."</p>";
							}
							$data['detail'] = $msg;
							$data_arr[$i] = $data;
							$i++;
						}
					}
				}
				else{//일반회원
					$sql_service = "select mem_id from Gn_Iam_Service where sub_domain like '%".$member_iam[site_iam]."%'";
					$res_service = mysqli_query($self_con,$sql_service);
					$row_service = mysqli_fetch_array($res_service);

					$sql_notice = "select seller_id, pay_date, message from Gn_Item_Pay_Result where buyer_id='{$row_service['mem_id']}' and item_name='공지사항전송' order by pay_date desc limit 1";
					$res_notice = mysqli_query($self_con,$sql_notice);
					$row_notice = mysqli_fetch_array($res_notice);

					if($row_notice[seller_id] != ''){
						$data['title'] = $row_notice['seller_id'];
						$data['date'] = $row_notice['pay_date'];
						$data['domain'] = $member_iam['site_iam'];
						$msg1 = explode("\n", $row_notice['message']);
						for($j = 0; $j < count($msg1); $j++){
							$msg .= "<p>".$msg1[$j]."</p>";
						}
						$data['detail'] = $msg;
						$data_arr[$i] = $data;
						$i++;
					}
					
					$sql_service = "select mem_id from Gn_Service where sub_domain like '%".$member_iam[site_iam]."%'";
					$res_service = mysqli_query($self_con,$sql_service);
					$row_service = mysqli_fetch_array($res_service);

					$sql_notice = "select seller_id, pay_date, message from Gn_Item_Pay_Result where buyer_id='{$row_service['mem_id']}' and item_name='공지사항전송' order by pay_date desc limit 1";
					$res_notice = mysqli_query($self_con,$sql_notice);
					$row_notice = mysqli_fetch_array($res_notice);

					if($row_notice[seller_id] != ''){
						$data['title'] = $row_notice['seller_id'];
						$data['date'] = $row_notice['pay_date'];
						$data['domain'] = $member_iam['site_iam'];
						$msg1 = explode("\n", $row_notice['message']);
						for($j = 0; $j < count($msg1); $j++){
							$msg .= "<p>".$msg1[$j]."</p>";
						}
						$data['detail'] = $msg;
						$data_arr[$i] = $data;
						$i++;
					}
				}
			}
			$cnt = 4 - $i * 1;
			$sql_admin = "select title, date, content from tjd_sellerboard order by date desc limit ".$cnt;
			$res_admin = mysqli_query($self_con,$sql_admin);
			while($row_admin = mysqli_fetch_array($res_admin)){
				$data['title'] = $row_admin['title'];
				$data['date'] = $row_admin['date'];
				$data['domain'] = "kiam";
				$data['detail'] = $row_admin['content'];
				$data_arr[$i] = $data;
				$i++;
			}
			if(count($data_arr)){
				$sql_title = "select notice_title from Gn_App_Home_Manager where notice_title!='' and site_iam='{$site_iam}'";
				$res_title = mysqli_query($self_con,$sql_title);
				$row_title = mysqli_fetch_array($res_title);
			?>
			<h4 style="padding: 25px 25px 0px;"><?=$row_title[0]?$row_title[0]:'IAM공지'?><a href="https://<?=$HTTP_HOST?>/?cur_win=unread_notice" style="float: right;color: #5ceb5c;">more</a></h4>
			<?
			}
			for($j = 0; $j < count($data_arr); $j++) {
			?>
			<div style="padding:15px;position:relative;">
				<li style="position:absolute;font-weight: 500;" onclick="show_msg('<?=$data_arr[$j]['detail']?>')"><?=cut_str($data_arr[$j]['title'], 12)?></li>
				<p style="position:absolute;left:55%;"><?=$data_arr[$j]['domain']?></p>
				<p style="position:absolute;right:5%;"><?=substr($data_arr[$j]['date'], 0, 10)?></p>
			</div>
			<?}?>
		</div>
	</section><!-- // 메인화면 아이엠 공지사항 영역 끝 -->

	<div class="banner-wrap"><!-- 하단 베너 영역 -->
	<?	$query = "select * from Gn_App_Home_Manager where ad_position='B' and use_yn='Y' and site_iam='{$site_iam}' order by display_order asc";
    	$res = mysqli_query($self_con,$query);
    	while($data = mysqli_fetch_array($res)) {?>
				<a href="<?php echo $data['move_url'];?>" target="_self">
					<img src="<?php echo $data['img_url'];?>">
				</a>
		<?}?>
	</div><!-- // 하단 베너 영역 -->

	<div class="banner-wrap"><!-- 하단 전환하기 영역 -->
	<?	$query = "select * from Gn_App_Home_Manager where ad_position='E' and use_yn='Y' and site_iam='{$site_iam}' order by display_order asc";
    	$res = mysqli_query($self_con,$query);
    	while($data = mysqli_fetch_array($res)) {?>
				<a href="javascript:change_site('<?=$data['idx']?>')">
					<img src="<?php echo $data['img_url'];?>">
				</a>
		<?}?>
	</div><!-- // 하단 전환하기 영역 -->
</main><!-- // 컨텐츠 영역 시작 -->
<span class="tooltiptext-bottom" id="notice_msg" style="display:none;overflow-x: auto; overflow-y: auto;height: 90%;">
	<p class="title_app">공지내용<span onclick="cancel_msg()" style="float:right;cursor:pointer;">X</span></p>
	<div style="padding:10px;">
		<p style="font-size:16px;color:#6e6c6c;border:1px solid;padding:10px;word-break: break-all;" id="notice_detail">
			
		</p>
	</div>
</span>
<div id="tutorial-loading"></div>
<?php include $_SERVER['DOCUMENT_ROOT']."/m/include/menu.inc.php"; ?>
<?php include $_SERVER['DOCUMENT_ROOT']."/m/include/footer.inc.php"; ?>
<script>
$(function(){
	$('#mainSlider').slick({
		appendArrows: $('.slider-arrows'),
		prevArrow: '<button class="arrows prev"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
        nextArrow: '<button class="arrows next"><i class="fa fa-angle-right" aria-hidden="true"></i></button>'
	});
	$('#mainSlider1').slick({
		appendArrows: $('.slider-arrows'),
		prevArrow: '<button class="arrows prev"><i class="fa fa-angle-left" aria-hidden="true"></i></button>',
        nextArrow: '<button class="arrows next"><i class="fa fa-angle-right" aria-hidden="true"></i></button>'
	});
});
setInterval(function(){
	$('.next').click();
}, 3000);
function goCallbackApp() {
    var navCase = navigator.userAgent.toLocaleLowerCase();
	if(navCase.search("android") > -1){
		try {
			AppScript.goCallbackApp('<?php echo $_SESSION['one_member_id'];?>');
		} catch(e) {
			openAndroid();
		}
	}else{
		var iam_mem_id = "<?=$_SESSION['one_member_id'];?>";
		if(iam_mem_id == "")
			alert("휴대폰에서 이용해주세요.");
	}
}
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
function change_site(idx){
	var msg = "고급기능은 콜백, 데일리외에 폰문자, 스탭문자, 디비수집등의 자동홍보기능을 담고 있는 메인앱으로 변경됩니다. 메인 앱에서는 일부기능에 유료기능이 있습니다. 변경하시겠습니까?";

	if(confirm(msg)){
		$.ajax({
          type:"POST",
          url:"/admin/ajax/app_home_ajax.php",
          data:{
              mode:'change_site', 
              idx:idx
          },
          success:function(data){
              alert('전환되었습니다.');
              location.reload();
          }
      })
	}
}

function go_move_url(mem_id, url){
	if(mem_id == ''){
		alert("로그인후에 이용해 주세요");
		return;
	}
	location.href = url;
}

function go_market_url(mem_id, url){
	if(mem_id == ''){
		alert("굿마켓은 굿웰스클럽 회원들이 이용하는 멤버쉽 마켓입니다. 이 마켓을 이용하시려면 마이페이지에서 굿웰스클럽의 멤버쉽을 신청하셔야 합니다.");
		return;
	}
	else{
		$.ajax({
			type:"POST",
			url:'/ajax/get_mem_address.php',
			data:{mode:'check_gwc_member', mem_id:mem_id},
			dataType:'json',
			success:function(data){
				if(data == "0"){
					alert('굿마켓은 굿웰스클럽 회원들이 이용하는 멤버쉽 마켓입니다. 이 마켓을 이용하시려면 마이페이지에서 굿웰스클럽의 멤버쉽을 신청하셔야 합니다.');
					return;
				}
				else{
					location.href = url;
				}
			}
		})
	}
	location.href = url;
}

function show_msg(msg){
	$("#notice_detail").html(msg);
	$("#notice_msg").show();
    $("#tutorial-loading").show();
	$('body,html').animate({
		scrollTop: 0 ,
		}, 100
	);
}

function cancel_msg(){
    $("#notice_msg").hide();
    $("#tutorial-loading").hide();
}
</script>
