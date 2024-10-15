<?
    include_once $path."lib/rlatjd_fun.php";

    $sql = "select * from tjd_pay_result where buyer_id = '{$_SESSION['one_member_id']}' and end_date > '$date_today' and end_status in ('Y','A') order by end_date desc limit 1";
    $res_result = mysqli_query($self_con,$sql);
    $pay_data = mysqli_fetch_array($res_result);
    $rights = 0;
    //echo $pay_data['TotPrice'] ;
    //echo $pay_data['member_type'] ;
    if($pay_data['TotPrice'] < "55000") {
        $rights = 1;    
    } else if($pay_data['TotPrice'] == "55000") {
        $rights = 2;
    } else if($pay_data['TotPrice'] > "55000") {
        $rights = 3;
    }
    $rights = 3;
    $use_domain = false;
    $sub_domain = false;
    if($HTTP_HOST != "kiam.kr") {
        $query = "select * from Gn_Service where sub_domain like '%".$HTTP_HOST."'";
        $res = mysqli_query($self_con,$query);
        $domainData = mysqli_fetch_array($res);
        
        if($domainData['idx'] != "") {
            $sub_domain = true;
            $curdate = strtotime(date('Y-m-d',time()));
            $startdate = strtotime($domainData['contract_start_date']);
            $enddate = strtotime($domainData['contract_end_date']);
            if($domainData['status'] == 'Y' && $curdate >= $startdate &&  $curdate <= $enddate)
            {
                $use_domain = true;            
            }

        }
        
    }
    
    $sql = "select * from tjd_pay_result where buyer_id = '{$_SESSION['one_member_id']}' and end_date > '$date_today'  and stop_yn='Y'";
    $res_result = mysqli_query($self_con,$sql);
    $stop = mysqli_fetch_array($res_result);    
    if($stop[stop_yn] == "Y") {
        if($_SERVER['PHP_SELF'] == "/sub_5.php" || $_SERVER['PHP_SELF'] == "/sub_6.php" || strstr($_SERVER['PHP_SELF'], "mypage_") == true || strstr($_SERVER['PHP_SELF'], "daily_") == true) {
            if(strstr($_SERVER['PHP_SELF'], "mypage_payment") == true) {} else {
                echo "<script>location='/';</script>";
                exit;
            }
        }
    }
	$sql = "select * from Gn_Ad_Manager where ad_position = 'H' and use_yn ='Y'";
    $res_result = mysqli_query($self_con,$sql);
    $ad_data = mysqli_fetch_array($res_result); 	
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <?php if($sub_domain == true) {?>
    		    	<?if($domainData['site_name']){?>
    		    		 <title><?php echo $domainData['site_name'];?></title>
    <meta name="description" content=""<?php echo $domainData['site_name'];?>"" />
    <meta name="keywords" content="<?php echo $domainData['keywords'];?>" />	
    <meta name="naver-site-verification" content="<?php echo $domainData['naver-site-verification'];?>"/>
    	
    		    	<?}?>
    		    <?php } else {?>		
    			    <title>온리원셀링-자동셀링솔루션으로 성공하세요!</title>
    <meta name="description" content="온리원문자, 온리원셀링,PC-스마트폰 연동문자, 대량문자, 장문/포토 모두0.7원, 수신거부/수신동의/없는번호/번호변경 자동관리, 수신처관리" />
    <meta name="keywords" content="온리원문자, 온리원셀링,PC-스마트폰 연동문자, 대량문자, 장문/포토 모두0.7원, 수신거부/수신동의/없는번호/번호변경 자동관리, 수신처관리" />
    
    			<?php } ?>   
<!--
    <link rel="shortcut icon" href="logo.ico">
-->    			
    <link href='<?=$path?>css/nanumgothic.css' rel='stylesheet' type='text/css'/>
    <link href='<?=$path?>css/main.css' rel='stylesheet' type='text/css'/>
    <link href='/css/sub_4_re.css' rel='stylesheet' type='text/css'/>
    <link href='<?=$path?>css/responsive.css' rel='stylesheet' type='text/css'/><!-- 2019.11 반응형 CSS -->
    <link href='<?=$path?>css/font-awesome.min.css' rel='stylesheet' type='text/css'/><!-- 2019.11 반응형 CSS -->
    <script language="javascript" src="<?=$path?>js/jquery-1.7.1.min.js"></script>
    <script language="javascript" src="<?=$path?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
    <script type="text/javascript" src="/jquery.lightbox_me.js"></script>
	<script type="text/javascript" src="/jquery.cookie.js"></script>	
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-3E40Q09QGE"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-3E40Q09QGE');
    </script>    
    <script>
    $(function(){
        $('.main_link').hover(function() {
            $('.sub_menu').hide();
            var index = $(".main_link").index(this);
            $(".main_link:eq("+index+")").parent().find("ul").show();        
        },
        function() {
            $('.sub_menu').hide();
        }
        );
        $('.main_link').on("hover",function(){
            $('.sub_menu').hide();
            var index = $(".main_link").index(this);
            $(".main_link:eq("+index+")").parent().find("ul").show();
    //       $(this).closest("ul").show();
           //$(this).closest("ul").css("background","yellow");
           //console.log($(this).closest('ul'));
        });
        $('.head_right_2').on("mouseout",function() {
           //$('.sub_menu').delay(5000).hide(0);
        });
        $('.main_link').on("click",function(){
            $('.sub_menu').hide();
           var index = $(".main_link").index(this);
           $(".main_link:eq("+index+")").parent().find("ul").show();
    //       $(this).closest("ul").show();
           //$(this).closest("ul").css("background","yellow");
           //console.log($(this).closest('ul'));
        });    
        $('.head_left, .head_right_1, .container').on("mouseover",function() {
            $('.sub_menu').hide();
        });
        
        $('.sub_menu').on("mouseleave",function() {
            $('.sub_menu').hide();			
        });
		$('.b_exit').on("click",function() {
			$('.ad_header').hide();
        });
    });
    </script>
</head>
<body>
<!--	<? //if($_COOKIE['ad'] != 'hide'){?> -->
 <?php   if($HTTP_HOST != "dbstar.kiam.kr") { ?>
		<div class="ad_header">
			<a href="<?echo $ad_data[move_url]?>"><div class="ad_banner ad_img_banner" style="background-image: url('<?echo $ad_data['img_url']?>')"></div></a>
			<div id="b_exit" class="b_exit"><img src="http://kiam.kr/images/bt_close.gif"></div>
		</div>
<!--	<?php  //} ?>  --> 
	<header style="background: #D8D8D8;">
        <div class="h_div" style="background-color: #D8D8D8;height: 35px;">
			<div class="d_head_right_1" style="overflow-x:auto; white-space: nowrap; margin-left:20px;line-height:25px;color:#535353;
">                
				<a href="https://j.mp/34fC8kB" target="_blank">원격강연회</a>
				|
				<a href="https://www.youtube.com/channel/UCw5r6QTl6JTbzIQlUhIiOjA" target="_blank">온리원TV</a>
				|
				<a href="https://oog.kiam.kr/pages/page_4243.php" target="_blank">코칭과정</a>
				|
				<a href="https://oog.kiam.kr/pages/page_4240.php" target="_blank">사업권소개</a>
				|
				<a href="http://kiam.kr/event/event.html?pcode=&sp=&landing_idx=1216" target="_blank">강연이벤트</a>
				|
				<a href="http://mannayo.kiam.kr/" target="_blank">만나요</a>&nbsp;
			</div>
    		<p style="clear:both;"></p>
        </div>
    </header>
      <?php 
  } 
  ?>
    <header class="big_h_f" id="header">
        <div class="h_div" style="background-color: #24303e;">
        	<div class="head_left" style="width: 225px;">
    		<? if(strpos($HTTP_HOST, "onefestival") !== false){?>
    			<a href="./"><img src="images/onnury_logo.jpg" /></a>			
    		<? }else{ ?>
    		    <?php if($sub_domain == true) {?>
    		    
    		    	<?if($domainData['logo']){?>
    		    		<a href="./"><img src="<?=$domainData['logo']?>" style="width:252px;height:70px"/></a>			
    		    	<?}?>
    		    <?php } else {?>		
    			    <a href="./"><img src="images/only_m_lo_03.jpg"  style="width:252px;height:70px"/></a>
    			<?php } ?>
    		<? } ?>
    		</div>
            <div class="head_right">
            	<div class="head_right_1">                
                    <form  name="login_form" action="ajax/login.php" method="post" target="login_iframe" onsubmit="return login_check(login_form)">
                    <!--<a href="https://play.google.com/store/apps/details?id=mms.onepagebook.com.onlyonesms" target="_blank">앱설치</a> |-->
                    
					<?
						$server_name = $_SERVER['SERVER_NAME'];
						if($server_name == 'mcube.kiam.kr')
							echo '<a href="https://www.notion.so/knowhowseller/519fdb2e68d34d1f9c9624c90844363d" target="_blank">이용매뉴얼</a> |';
						// else
						// 	echo '<a href="https://oog.kiam.kr/pages/page_3208.php" target="_blank">이용매뉴얼</a> |';
					?>    


        
                    <?php if($sub_domain == true) {
                            if($HTTP_HOST != "dbstar.kiam.kr"){
                        ?>
					    <a href="/sub_14.php">이용정책(필독)</a> |                 
                        <a href="/cliente_list.php?status=1">공지사항</a> |                   
                        <?}?>
                        <?if($domainData['']){?>
                        
                    <?}?>
                    <?php } else {?>
					<a href="https://oog.kiam.kr/pages/page_3208.php" target="_blank">이용매뉴얼</a> |                  
                    <a href="http://kiam.kr/cliente_list.php?status=1">공지사항</a> |
                    <a href="https://pf.kakao.com/_jVafC/chat" target="_blank">카톡상담</a> |
                    <?php } ?>

    		
                    <?if(!$_SESSION['one_member_id']){?><a href="join.php">회원가입</a> |
                    <a href="id_pw.php">아이디/비밀번호찾기</a>&nbsp;
                    <iframe name="login_iframe" style="display:none;"></iframe>
                	<input type="text" name="one_id" itemname='아이디' placeholder="아이디" required style="width:100px;"  />
                    <input type="password" name="one_pwd" itemname='비밀번호' placeholder="비밀번호" style="width:100px;" required />				
                    <input type="image" src="images/main_top_button_03.jpg"/>
    				<?}else{?>
    				<a href="mypage.php">마이페이지</a>&nbsp;
    				<!--| <a href="mypage_link_list.php">원스텝문자</a>&nbsp;-->
                    <?
                    if($_SESSION['one_member_id'] == "db"){
                    ?>
                      | <a href="/admin/crawler_member_list_v.php">관리자</a>&nbsp;				
                    <?php } ?>
                    <?
                    if($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == $HTTP_HOST){
                    ?>
                      | <a href="/admin/member_list.php">관리자</a>&nbsp;				
                    <?php } ?>                
                    <?
                    if($_SESSION['one_member_id'] == "obmms01"){
                    ?>
                       | <a href="permit_number.php">승인처리</a>&nbsp;
                    <?
                    }
                    if($_SESSION['one_member_admin_id'] != ""){
                    ?>
                    <?if($_SESSION['one_member_admin_id'] == "emi0542" || $_SESSION['one_member_admin_id'] == "gwunki"){?>
                    | <a href="/admin/member_list_con.php">관리자</a>&nbsp;   
                    <?}else{?>
                    | <a href="/admin/member_list.php">관리자</a>&nbsp;
                    <?}?>
                    <!--| <a href="/pc_messenger.php">PC문자메신져</a>&nbsp;-->
                    <?
                    }
                    //if($_SESSION['one_mem_lev'] == "50"){
                    if($_SESSION['one_member_id'] == "lecturem") {
                    ?>
                      | <a href="/admin/lecture_list.php">관리자</a>&nbsp;				
                    <?php } ?>
                    <!--| <a href="/sub_7.php">추천인리스트</a>&nbsp;-->
                    <?
                    //}
                    ?>
                    <span style="background-color:#43515e;padding:2px 20px 2px 5px;"><?=$member_1['mem_name']?> 님 환영합니다.</span> <a href="javascript:void(0)" onclick="logout()"><img src="images/main_top_button_logout_03.jpg" /></a>
                    <?}?>
                    </form>                               
                </div>
            </div>
    		<p style="clear:both;"></p>
            
			<? if($HTTP_HOST == "moodoga.kiam.kr"){?>
             <style>
			    .header-gnb { position: relative; padding-bottom: 20px; left: 25%;}
			    .header-gnb:after {content: '';position: absolute; top: 38px; left: 50%; width: 100vw; height: 0px; background-color: #fff; opacity: 0; visibility: hidden; z-index: 1;
            	-webkit-box-shadow: 0 5px 5px rgba(0,0,0,0.15);
	            box-shadow: 0 5px 5px rgba(0,0,0,0.15);
	            -webkit-transform: translateX(-50%);
	            -ms-transform: translateX(-50%);
	            -o-transform: translateX(-50%);
	            transform: translateX(-50%); }
			</style>
             <nav class="header-gnb"><!-- Menu GNB -->
                <div class="gnb-container" style="align:center;">
                    <ul>
                        <li class="menu-item">
                            <a href="sub_5.php">휴대폰등록</a>
                        <li class="menu-item">
                            <a href="sub_6.php">문자발송</a>
                        </li>   
                        <li class="menu-item">
                            <a href="sub_4_return_.php">발신내역</a>
                        </li>
                        <li class="menu-item">
                            <a href="sub_4.php?status=5&status2=3">수신내역</a>
                        </li>
                        <li class="menu-item">
                            <a href="sub_4.php?status=6">수신여부</a>
                        </li>
                        <li class="menu-item">
                            <a href="pay.php">결제안내</a>  
                        </li>
                    </ul>
                </div>
                
            </nav><!-- // Menu GNB -->
            <?} else if($HTTP_HOST == "nuguna.kiam.kr") {?>
                <style>
                 .header-gnb { position: relative; padding-bottom: 20px; left: 25%;}
                 .header-gnb:after {content: '';position: absolute; top: 38px; left: 50%; width: 100vw; height: 0px; background-color: #fff; opacity: 0; visibility: hidden; z-index: 1;
                -webkit-box-shadow: 0 5px 5px rgba(0,0,0,0.15);
                box-shadow: 0 5px 5px rgba(0,0,0,0.15);
                -webkit-transform: translateX(-50%);
                -ms-transform: translateX(-50%);
                -o-transform: translateX(-50%);
                transform: translateX(-50%); }
			</style>
                <nav class="header-gnb" ><!-- Menu GNB -->
                <div class="gnb-container" style="text-align:center;font-size:100px;">
                    <ul>
                        <li class="menu-item">
                            <a href="sub_1.php">무료폰문자소개</a> 
                            <ul class="submenu">
                                <li class="submenu-item"><a href="sub_1.php">온리원문자소개</a></li>
                                <li class="submenu-item"><a href="sub_11.php">온리원콜백소개</a></li>
                                <li class="submenu-item"><a href="sub_10.php#Introduce" >아이엠소개</a></li>
                                </ul>
                            </li>  

                        <li class="menu-item">
                            <a href="sub_10.php">아이엠카드</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="/index.php" target="_blank">아이엠접속</a></li>
                                <li class="submenu-item"><a href="https://url.kr/JX9P7u" target="_biank">아이엠설치</a></li>
                            </ul>
                        </li>
                        
                        <li class="menu-item">
                            <a href="sub_1.php">온리원문자</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="sub_5.php">휴대폰등록관리</a></li>
                                <li class="submenu-item"><a href="sub_6.php">문자발송하기</a></li>
                                <li class="submenu-item"><a href="sub_4_return_.php">발신내역관리</a></li>
                                <li class="submenu-item"><a href="sub_4.php?status=5&status2=3">수신내역관리</a></li>
                                <li class="submenu-item"><a href="sub_4.php?status=6">수신여부관리</a></li>
                            </ul>                                       
                        </li>
                        <li class="menu-item">
                            <a href="pay.php">결제안내</a>  
                            <ul class="submenu">
                                <li class="submenu-item"><a href="/pay.php">솔루션결제</a></li> 
                               <li class="submenu-item"><a href="/sub_14.php">이용정책(필독)</a></li>									  
                            </ul>            
                        </li>
                    </ul>
                </div>
                
            </nav><!-- // Menu GNB -->

            <?php } else if($HTTP_HOST == "dbstar.kiam.kr") {?>
                <style>
			 .header-gnb { position: relative; padding-bottom: 20px; left: 25%;}
			 .header-gnb:after {content: '';position: absolute; top: 38px; left: 50%; width: 100vw; height: 0px; background-color: #fff; opacity: 0; visibility: hidden; z-index: 1;
	-webkit-box-shadow: 0 5px 5px rgba(0,0,0,0.15);
	        box-shadow: 0 5px 5px rgba(0,0,0,0.15);
	-webkit-transform: translateX(-50%);
	    -ms-transform: translateX(-50%);
	     -o-transform: translateX(-50%);
	        transform: translateX(-50%); }
			</style>

                <nav class="header-gnb" ><!-- Menu GNB -->
                <div class="gnb-container" style="text-align:center;font-size:100px;">
                    <ul>
                        
                        <li class="menu-item" >
                            <a href="http://kiam.kr/app/201004OnlyOneDB.exe">디버설치</a>
                        </li>
                        <li class="menu-item" >
                            <a href="/mypage_link_list.php">고객신청관리</a>
                        </li>   
                        <li class="menu-item" >
                            <a href="/mypage_landing_list.php">랜딩페이지</a>
                        </li>   

                        <li class="menu-item">
                            <a href="/sub_6_dbstar.php">포토문자발송</a>
                        </li>
                    </ul>
                </div>
                
            </nav><!-- // Menu GNB -->


            <?}
            else {?>



            <nav class="header-gnb" ><!-- Menu GNB -->
                <div class="gnb-container" style="text-align:center;font-size:100px;">
                    <ul>
                        <li class="menu-item" id="msub8">
                            <a href="sub_8.php">솔루션시작</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="sub_8.php">셀링솔루션소개</a></li>
                                <li class="submenu-item"><a href="https://oog.kiam.kr/pages/page_3208.php" target="_blank">매뉴얼따라하기</a></li>
                            </ul>
                        </li> 
                        <li class="menu-item" id="msub10">
                            <a href="sub_10.php">아이엠카드</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="sub_10.php" >아이엠소개</a></li>
                                <li class="submenu-item"><a href="/?cur_win=best_sample" target="_blank">아이엠샘플</a></li>
                                <li class="submenu-item"><a href="/index.php" target="_blank">아이엠접속</a></li>
                            </ul>
                        </li>
                        
                        <li class="menu-item" id="msub2">
                            <a href="sub_2.php">디비수집</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="sub_2.php">디버알아보기</a></li>
                                <li class="submenu-item"><a href="http://kiam.kr/app/201004OnlyOneDB.exe" >디버설치하기</a></li>								
                                <li class="submenu-item"><a href="sub_2.php#uses" target="_blank">디비수집하기</a></li>
                            </ul>
                        </li>
                        <li class="menu-item" id="msub15">
                            <a href="sub_11.php">콜백문자</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="sub_11.php">콜백알아보기</a></li>
                                <li class="submenu-item"><a href="https://www.onestore.co.kr/userpoc/apps/view?pid=0000738690" target="_blank">콜백설치하기</a></li>								
                                <li class="submenu-item"><a href="https://url.kr/rCNfjS" target="_blank">콜백이용하기</a></li>
                            </ul>
                        </li>   
                        <li class="menu-item" id="msub1">
                            <a href="sub_1.php">폰문자발송</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="sub_1.php">폰문자소개</a></li>
                                <li class="submenu-item"><a href="sub_5.php">휴대폰등록</a></li>
                                <li class="submenu-item"><a href="sub_9.php#Introduce">메시지카피</a></li>
                                <li class="submenu-item"><a href="sub_6.php">폰문자발송</a></li>
                                <li class="submenu-item"><a href="daily_list.php">데일리발송</a></li>
                                <li class="submenu-item"><a href="sub_4_return_.php">발신내역보기</a></li>
                                <li class="submenu-item"><a href="sub_4.php?status=5&status2=3">수신내역보기</a></li>
                                <li class="submenu-item"><a href="sub_4.php?status=6">수신여부보기</a></li>

                            <!--    <li class="submenu-item"><a href="sub_4.php?status=5&status2=3">수신내역관리</a></li>
                                <li class="submenu-item"><a href="sub_4.php?status=6">수신여부관리</a></li>
                                <li class="submenu-item"><a href="https://www.onestore.co.kr/userpoc/apps/view?pid=0000738690">셀링어플설치</a></li>			-->					

                            </ul>                                       
                        </li>
                        <li class="menu-item" id="msub12">
                            <a href="/sub_12.php">원스텝발송</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="/sub_12.php">원스텝소개</a></li>
                                <li class="submenu-item"><a href="mypage_landing_list.php">랜딩페이지</a></li>
                                <li class="submenu-item"><a href="/mypage_link_list.php">고객신청창</a></li>
                                <li class="submenu-item"><a href="/mypage_reservation_list.php">스텝예약관리</a></li>
                                <li class="submenu-item"><a href="/mypage_request_list.php">신청고객관리</a></li>
                                <li class="submenu-item"><a href="/mypage_wsend_list.php">발송내역관리</a></li>
                        <!--        <li class="submenu-item"><a href="/mypage_send_list.php">스텝발송결과</a></li>  -->
                            </ul>                   
                        </li>
                        <li class="menu-item" id="msub13">
                            <a href="/sub_13.php">쇼핑채널</a>
                            <ul class="submenu">
                                <li class="submenu-item"><a href="http://onlyonemall.net" target="_blank">온리원쇼핑몰</a></li>
                                <li class="submenu-item"><a href="/sub_13.php" >국내제휴쇼핑</a></li>
                                 <li class="submenu-item"><a href="" target="_blank">해외제휴쇼핑</a></li>
                                 <li class="submenu-item"><a href="http://kims3925.onlyonemall.net/" target="_blank">바자회쇼핑몰</a></li>
                            </ul>                   
                        </li>
                        <li class="menu-item" id="mpay">
                            <a href="pay.php">결제안내</a>  
                        </li>
                    </ul>
                </div>                
            </nav><!-- // Menu GNB -->
            <?php }?>

            <a href="#" id="menuToggle"><!-- 모바일 메뉴 토글 버튼 -->
                <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="bars" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-bars fa-w-14 fa-7x"><path fill="currentColor" d="M436 124H12c-6.627 0-12-5.373-12-12V80c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12zm0 160H12c-6.627 0-12-5.373-12-12v-32c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12zm0 160H12c-6.627 0-12-5.373-12-12v-32c0-6.627 5.373-12 12-12h424c6.627 0 12 5.373 12 12v32c0 6.627-5.373 12-12 12z" class=""></path></svg>
            </a><!-- // 모바일 메뉴 토글 버튼 -->

        </div>
    </header>    
	<div class="big_1 head_breadcrumb" style="display:none;width:100%;position:absolute;">
		<div class="m_head_div" id="sub1" style="position:relative;">
			<div class="left_sub_menu">
				<a href="./">홈</a> >
				<a href="sub_1.php">온리원문자</a> >
				<a href="?status=<?=$_REQUEST['status']?>"><?=$left_str?></a>
			</div>
			<div style="position:absolute;left:530px;">
			   <a href="sub_1.php">폰문자소개</a> ㅣ<a href="sub_5.php">휴대폰등록</a> ㅣ <a href="sub_6.php">문자발송</a> ㅣ <a href="sub_4_return_.php">발신내역</a> ㅣ <a href="sub_4.php?status=5&status2=3">수신내역</a> ㅣ <a href="sub_4.php?status=6">수신여부</a>
			</div>
			<p style="clear:both;"></p>
		</div>
		<div class="m_head_div" id="sub10" style="position:relative;">    
			<div class="left_sub_menu">
			  <a href="./">홈</a> >
			  <a href="sub_10.php">아이엠</a> 
			</div>
			<div style="position:absolute;left:200px;">     
			  <a href="sub_10.php">아이엠소개</a> ㅣ <a href="/?cur_win=best_sample">아이엠샘플</a> ㅣ <a href="https://www.onestore.co.kr/userpoc/apps/view?pid=0000738690">아이엠설치</a> ㅣ<a href="/index.php" target="_blank">아이엠접속</a> ㅣ <a href="https://oog.kiam.kr/pages/page_3221.php">아이엠매뉴얼</a>
			</div>
			
			<p style="clear:both;"></p>
		  </div>
		 <div class="m_head_div" id="sub2" style="position:relative;">
			
			<div class="left_sub_menu">
			  <a href="./">홈</a> >
			  <a href="sub_2.php">온리원디버</a> >
			  <a href="?status=<?=$_REQUEST['status']?>"><?=$left_str?></a>
			</div>
			<div style="position:absolute;left:320px;">     
			  <a href="sub_2.php">디버알아보기</a> ㅣ <a href="http://kiam.kr/app/201004OnlyOneDB.exe">디버설치하기</a>ㅣ <a href="https://url.kr/IYUGDV">디버수집하기</a> 
			</div>
			
			<p style="clear:both;"></p>
		 </div>
		 <div class="m_head_div" id="sub15" style="position:relative;">    
			<div class="left_sub_menu">
			  <a href="./">홈</a> >
			  <a href="sub_11.php">온리원콜백</a> >
			  <a href="?status=<?=$_REQUEST['status']?>"><?=$left_str?></a>
			</div>
			<div style="position:absolute;left:430px;">     
			  <a href="sub_11.php">콜백알아보기</a> ㅣ <a href="https://www.onestore.co.kr/userpoc/apps/view?pid=0000738690">콜백설치하기</a>ㅣ <a href="https://url.kr/IYUGDV">콜백이용하기</a> 
			</div>
			
			<p style="clear:both;"></p>
		  </div>
		 <div class="m_head_div" id="sub12">
        	<div class="left_sub_menu">
                <a href="./">홈</a> > 
                <a href="/sub_12.php">원스텝발송</a>
            </div>
            <div class="right_sub_menu">&nbsp;
                <a href="/sub_12.php">원스텝소개</a> | 
                <a href="mypage_landing_list.php">랜딩페이지</a> | 
                <a href="/mypage_link_list.php">고객신청창</a> | 
                <a href="/mypage_reservation_list.php">스텝예약관리</a> | 
                <a href="/daily_list.php">데일리발송</a> | 
                <a href="/mypage_request_list.php">신청고객관리</a> | 
          <!--      <a href="/mypage_oldrequest_list.php">기존고객관리</a> | newcode -->
                <a href="/mypage_wsend_list.php">발송예정내역</a> | 
                <a href="/mypage_send_list.php">발송결과내역</a> 
            </div>
            <p style="clear:both;"></p>
    	</div>
		<div class="m_head_div" id="sub13">  
			<div class="left_sub_menu">
				<a href="./">홈</a> > 
				<a href="sub_13.php">온리원쇼핑</a>
			</div>
			<div class="right_sub_menu">
				<!--
				<a href="sub_1.php">온리원문자</a> &nbsp;|&nbsp; 
				<a href="sub_2.php">온리원디버</a> &nbsp;|&nbsp;-->
				<a href="http://onlyonemall.net/">온리원쇼핑몰</a> | <a href="sub_13.php">국내제휴쇼핑</a> ㅣ<a href="/?cCT6LD1no7">해외제휴쇼핑</a> | <a href="http://kims3925.onlyonemall.net/">바자회쇼핑몰</a>
			</div>                    
			
			<p style="clear:both;"></p>
		  </div>
		  <div class="m_head_div" id="sub11" style="position:relative;">    
			<div class="left_sub_menu">
			  <a href="./">홈</a> >
			  <a href="sub_11.php">온리원콜백</a> 
			</div>
			<div style="position:absolute;left:430px;">     
			  <a href="sub_11.php">콜백알아보기</a> ㅣ <a href="https://www.onestore.co.kr/userpoc/apps/view?pid=0000738690">콜백설치하기</a> ㅣ <a href="https://url.kr/rCNfjS">콜백이용하기</a> 
			</div>
			
			<p style="clear:both;"></p>
		  </div>
		  <div class="m_head_div" id="sub8" style="position:relative;">    
			<div class="left_sub_menu">
			  <a href="./">홈</a> >
			  <a href="sub_8.php">솔루션소개</a> >
			  <a href="?status=<?=$_REQUEST['status']?>"><?=$left_str?></a>
			</div>
			<div style="position:absolute;left:140px;">
			  <!--
			  <a href="sub_1.php">온리원문자</a> &nbsp;|&nbsp;
			  <a href="sub_2.php">온리원디버</a> &nbsp;|&nbsp;
			  -->
			  <a href="sub_8.php#Introduce">셀링솔루션소개</a> ㅣ <a href="https://oog.kiam.kr/pages/page_3208.php">매뉴얼따라하기</a>
			</div>
			
			<p style="clear:both;"></p>
		  </div>
		  <div class="m_head_div" id="head_pay" style="position:relative;">
            <div class="left_sub_menu">
                <a href="./">홈</a> >
                <a href="pay.php">결제안내</a>
            </div>
            <!--<div class="right_sub_menu">
                <a href="sub_5.php">휴대폰등록</a> ㅣ <a href="sub_6.php">문자발송</a> ㅣ <a href="sub_4_return_.php">발신내역</a> ㅣ <a href="sub_4.php?status=5&status2=3">수신내역</a> ㅣ <a href="sub_4.php?status=6">수신여부</a>
            </div>-->
            <p style="clear:both;"></p>
        </div>
	</div>

<script>
function pop_hide() {
	$(".popupbox").hide();
	clearTimeout(tid);
    $('.big_1').show();
	$('.m_head_div').hide();
}
$('#mpay').mouseover(function(){
	pop_hide();
	$('#head_pay').show();
});

$('#msub12').mouseover(function(){
	pop_hide();
	$('#sub12').show();
});
$('#msub13').mouseover(function(){
	pop_hide();
	$('#sub13').show();
});
$('#msub15').mouseover(function(){
	pop_hide();
	$('#sub15').show();
});
$('#msub1').mouseover(function(){
	pop_hide();
	$('#sub1').show();
});
$('#msub10').mouseover(function(){
	pop_hide();
	$('#sub10').show();
});
$('#msub8').mouseover(function(){
	pop_hide();
	$('#sub8').show();
});
$('#msub2').mouseover(function(){
	pop_hide();
	$('#sub2').show();
});
$('.big_1').mouseleave(function(){
    $('.big_1').hide();
});

</script>