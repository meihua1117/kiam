<?
include_once "../lib/rlatjd_fun.php";
$site = $member_1['site_iam'];
$iam_type = $member_1['iam_type'];
if($site){
    if($site == "kiam")
        $href = "https://www.kiam.kr/";
    else
        $href = "https://".$site.".kiam.kr/";
}else{
    $href = "/";
}
?>
<div class="big_h_f">   
    <?if($sub_domain == true) {?>
        <div class="midle_div foot_1">
            <a href="terms.php">이용약관</a>
            <a href="privacy.php">개인정보정책</a>
        </div>
    <?}else{?>
        <div class="midle_div foot_1">
            <a href="http://research.kiam.kr" target="_blank">공급사소개</a>
            <a href="cliente_list.php?status=1">공지사항</a>
            <!--
<a href="https://oog.kiam.kr/pages/page_3214.php" target="_blank">FAQ</a>
            -->
            <a href="terms.php">이용약관</a>
            <a href="privacy.php">개인정보정책</a>
            <a href="https://pf.kakao.com/_jVafC/chat">카카오상담센터</a>
        </div>
    <?}?>
</div>
<?if($sub_domain == true) {?>
    <div class="m_div foot_2">
        <div>사이트명:<?=$domainData['site_name'];?> &nbsp; 회사명:<?=$domainData['company_name'];?> &nbsp; <?=$domainData['business_number'];?>  &nbsp; &nbsp; 대표:<?=$domainData['ceo_name'];?>  &nbsp; 주소:<?=$domainData['address'];?>  &nbsp; 대표전화:<?=$domainData['manage_cell'];?>   &nbsp; &nbsp; <?=$domainData['fax'];?>   &nbsp; &nbsp; 개인정보책임자:<?=$domainData['privacy'];?>  &nbsp;  &nbsp; <?=$domainData['email'];?>  &nbsp; 통신판매신고번호:<?=$domainData['communications_vendors'];?>   &nbsp; 도메인:<?=$domainData['domain'];?>   &nbsp; </div>
        <div><a href="/onebook_serch.php?status=1" target="_blank">고객센터</a>이용시간:10:00-18:00(12:00~13:00 점심시간)  &nbsp; Copyright©<?=$domainData['service_name'];?>.All Rights Reserved. 호스팅: (주)카페24</div>
    </div>
<?}else{?>
    <div class="m_div foot_2">
        <div>사이트명:온리원플랫폼 &nbsp; 회사명:(주)온리원셀링 &nbsp;공급사:온리원연구소 &nbsp; 사업자번호:119-86-03213 &nbsp; 대표:송조은 &nbsp; 주소:충청북도 영동군 용화면 민주지산로 765
            &nbsp; 개인정보책임자:송조은 &nbsp; 이메일:1pagebook@naver.com  &nbsp; 통신판매신고번호:2019-경기광명-0162  &nbsp; 도메인:kiam.kr  &nbsp;
        <!--<a href="http://pf.kakao.com/_jVafC/chat">카카오상담</a>/<a href="/onebook_serch.php?status=1" target="_blank"> &nbsp; 고객센터</a>이용시간:10:00-18:00(12:00~13:00 점심시간)-->&nbsp; Copyright©온리원셀링.All Rights Reserved. 호스팅: (주)카페24</div>
    </div>
<?}
    include_once $path . "open_div.php";
?>
    <aside id="mobileMenu"><!-- 모바일 메뉴 -->
        <div class="inner">
            <div class="upper">
                <form name="login_form" action="ajax/login.php" method="post" target="login_iframe" onsubmit="return login_check(login_form)">
                    <div class="utils">
                    <?
                    if($HTTP_HOST != "dbstar.kiam.kr" && $HTTP_HOST != "mts.kiam.kr"){?>
			            <a href="https://tinyurl.com/hb2pp6n2" target="_blank">이용매뉴얼</a> |
		            <?}
		            if($sub_domain == true) {
			            if($HTTP_HOST != "dbstar.kiam.kr" && $HTTP_HOST != "mts.kiam.kr"){?>
                            <a href="/sub_14.php">이용정책(필독)</a> |
                            <a href="/cliente_list.php?status=1" target="_blank">공지사항</a>
                	        <?if($domainData['kakao']){?>
                	            <a href="<?=$domainData['kakao']?>" target="_blank">카톡상담</a>
                	        <?}
                	    }
            	    }else {?>
                        <a href="https://pf.kakao.com/_jVafC/chat" target="_blank">카톡상담</a>
                        <a href="/sub_14.php">이용정책(필독)</a> |
                        <a href="/cliente_list.php?status=1" target="_blank">공지사항</a>
		            <?}
		            if(!$_SESSION['one_member_id']){ 
		        	if ($HTTP_HOST != "kiam.kr") {
		                    $join_link = get_join_link("http://" . $HTTP_HOST, "","");
		                } else {
		                    $join_link = get_join_link("http://www.kiam.kr", "","");
		                }
		                //$join_link = "join.php";?>
                        <!--a href="join.php">회원가입</a-->
                        <a href="<?=$join_link?>">회원가입</a>
                        <a href="id_pw.php">아이디/비밀번호찾기</a>
                    </div>
                    <iframe name="login_iframe" style="display:none;"></iframe>
                    <input type="text" name="one_id" itemname='아이디' placeholder="아이디" required style="width:100px;"  />
                    <input type="password" name="one_pwd" itemname='비밀번호' placeholder="비밀번호" style="width:100px;" required />
                    <input type="image" src="images/main_top_button_03.jpg"/>
                    <?}else{?>
                        <a href="mypage.php">마이페이지</a>
                        <?if($_SESSION['one_member_id'] == "db"){ ?>
                            <a href="/admin/crawler_member_list_v.php">관리자</a>
                        <?}?>
                        <?if($_SESSION['one_member_id'] == "sungmheo"){ ?>
                            <a href="/admin/gwc_payment_list.php">관리자</a>
                        <?}?>
                        <?if($_SESSION['one_member_subadmin_id'] != "" && $_SESSION['one_member_subadmin_domain'] == $HTTP_HOST){ ?>
                            <a href="/admin/member_list.php">관리자</a>
                        <?}?>
                        <?if($_SESSION['one_member_id'] == "obmms01"){ ?>
                            <a href="permit_number.php">승인처리</a>
                        <? }?>
                        <? if($_SESSION['one_member_admin_id'] != ""){ ?>
                            <a href="/admin/member_list.php">관리자</a>
                        <? }?>
                        <? if($_SESSION['one_member_id'] == "lecturem") { ?>
                            <a href="/admin/lecture_list.php">관리자</a>
                        <?} ?>
                        </div>
                    </div>
                    <div class="login-only">
                        <span><?=$member_1['mem_nick']?> 님 환영합니다.</span>
                        <a href="javascript:void(0)" onclick="logout()">
                            <img src="images/main_top_button_logout_03.jpg" />
                        </a>
                    </div>
                    <? } ?>
                </form>        
             <!-- 무도가 Menu GNB //-->
            <?
            if($HTTP_HOST == "kookmin.kiam.kr") {?>
                <div class="mobile-gnb">
                    <ul>
                          <li class="menu-item" >
                              <a href="sub_11.php">콜백문자소개</a>
                          </li>
  
                          <li class="menu-item" >
                              <a href="https://play.google.com/store/apps/details?id=mms.onepagebook.com.onlyonesms" target="_biank">IAM앱설치</a>
                          </li>
  
                          <li class="menu-item" >
                              <a href="https://oog.kiam.kr/pages/page_3220.php?PHPSESSID=ce6790f1c27761635ff3d138a25994d6" target="_biank">콜백이용안내</a>
                          </li>
  
                      </ul>
                  </div>
   
              <?}
            else if($HTTP_HOST == "nuguna.kiam.kr") {?>
                <div class="mobile-gnb">
                   <ul>
                          <li class="menu-item" >
                                <a href="sub_1.php">폰문자소개</a>
                          </li>
                          <li class="menu-item">
                              <a href="sub_10.php">아이엠카드</a>
                              <ul class="submenu">
                                  <li class="submenu-item"><a href="<?=$href?>" target="_blank">아이엠접속</a></li>
                                  <li class="submenu-item"><a href="https://tinyurl.com/3ucs2vbt" target="_blank">아이엠설치</a></li>
                              </ul>
                          </li>
  
                          <li class="menu-item">
                              <a href="sub_1.php">폰문자발송</a>
                              <ul class="submenu">
                                  <li class="submenu-item"><a href="sub_5.php">휴대폰등록관리</a></li>
                                  <li class="submenu-item"><a href="sub_6.php">문자발송하기</a></li>
                                  <li class="submenu-item"><a href="sub_4_return_.php">발신내역관리</a></li>
                                  <li class="submenu-item"><a href="sub_4.php?status=5&status2=3">수신내역관리</a></li>
                                  <li class="submenu-item"><a href="sub_4.php?status=6">수신여부관리</a></li>
                              </ul>
                          </li>
                          <!--li class="menu-item">
                              <a href="pay.php">결제안내</a>
                              <ul class="submenu">
                                  <li class="submenu-item"><a href="/pay.php">솔루션결제</a></li>
                                 <li class="submenu-item"><a href="/sub_14.php">이용정책(필독)</a></li>
                              </ul>
                          </li-->
                      </ul>
                  </div>  
                <!-- // 누구나 Menu GNB -->
            <?}
            else if($HTTP_HOST == "dbstar.kiam.kr") {?>
            <div class="mobile-gnb">
                <ul>
                    <!--li class="menu-item">
                        <a href="#">아이엠</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="sub_10.php#Introduce" >아이엠소개</a></li>
                            <li class="submenu-item"><a href="/?cur_win=best_sample" target="_blank">아이엠샘플</a></li>
                            <li class="submenu-item"><a href="https://tinyurl.com/3ucs2vbt" target="_blank">아이엠설치</a></li>
                            <li class="submenu-item"><a href="/index.php" target="_blank">아이엠접속</a></li>
                            <li class="submenu-item"><a href="https://tinyurl.com/xwcvtxy4" target="_blank">아이엠매뉴얼</a></li>
                        </ul>
                    </li-->
                    <li class="menu-item" >
                        <a href="/app/onlyonedber.msi">디버설치</a>
                    </li>
                    <li class="menu-item" >
                        <a href="/mypage_link_list.php">고객신청관리</a>
                    </li>
                    <li class="menu-item" >
                        <a href="/mypage_landing_list.php">랜딩페이지</a>
                    </li>
                    <li class="menu-item" >
                        <a href="sub_6_dbstar.php">포토문자</a>
                     </li>
                     <!--li class="menu-item">
                        <a href="#">웹문자</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="/sub_16.php">웹문자소개</a></li>
                            <li class="submenu-item"><a href="/sub_17.php">웹문자접속</a></li>
                        </ul>
                    </li-->
                    <li class="menu-item">
                        <a href="#">국제문자</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="http://www.smsallline.com/home/login" target="_blank">국제문자</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <?}
            else if($HTTP_HOST == "mts.kiam.kr") {?>
            <div class="mobile-gnb">
                <ul>
                    <li class="menu-item">
                        <a href="#">솔루션시작</a>
                        <ul class="submenu">
                        <li class="submenu-item"><a href="sub_8.php">셀링솔루션소개</a></li>
                        <li class="submenu-item"><a href="https://tinyurl.com/hb2pp6n2" target="_blank">매뉴얼따라하기</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">아이엠</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="sub_10.php#Introduce" >아이엠소개</a></li>
                            <li class="submenu-item"><a href="/?cur_win=best_sample" target="_blank">아이엠샘플</a></li>
                            <li class="submenu-item"><a href="https://tinyurl.com/3ucs2vbt" target="_blank">아이엠설치</a></li>
                            <li class="submenu-item"><a href="<?=$href?>" target="_blank">아이엠접속</a></li>
                            <li class="submenu-item"><a href="https://tinyurl.com/557nca2b" target="_blank">아이엠매뉴얼</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">디비수집</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="sub_2.php">디버알아보기</a></li>
                            <li class="submenu-item"><a href="https://url.kr/DgTj4M">디버설치하기</a></li>
                            <li class="submenu-item"><a href="https://url.kr/IYUGDV">디비수집하기</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">콜백문자</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="sub_11.php#Introduce">콜백알아보기</a></li>
                            <li class="submenu-item"><a href="https://www.onestore.co.kr/userpoc/apps/view?pid=0000738690" target="_blank">콜백설치하기</a></li>
                            <li class="submenu-item"><a href="https://url.kr/rCNfjS">콜백이용하기</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">폰문자</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="sub_1.php">폰문자소개</a></li>
                            <li class="submenu-item"><a href="sub_5.php">휴대폰등록</a></li>
                            <li class="submenu-item"><a href="sub_9.php#Introduce">메시지카피</a></li>
                            <li class="submenu-item"><a href="sub_6.php">폰문자발송</a></li>
                            <li class="submenu-item"><a href="/daily_list.php">데일리발송</a></li>
                            <li class="submenu-item"><a href="sub_4_return_.php">발신내역보기</a></li>
                            <li class="submenu-item"><a href="sub_4.php?status=5&status2=3">수신내역보기</a></li>
                            <li class="submenu-item"><a href="sub_4.php?status=6">수신여부보기</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">스텝문자</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="/sub_12.php">원스텝소개</a></li>
                            <?php if($pay_data['onestep1'] != "ON" && $iam_type != 2) {?>
                                <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">랜딩페이지</a></li>
                                <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">고객신청창</a></li>
                                <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">스텝예약관리</a></li>
                                <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">신청고객관리</a></li>
                                <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">기존고객관리</a></li>
                                <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">발송내역관리</a></li>
                            <?php }else{?>
                                <li class="submenu-item"><a href="mypage_landing_list.php">랜딩페이지</a></li>
                                <li class="submenu-item"><a href="/mypage_link_list.php">고객신청창</a></li>
                                <li class="submenu-item"><a href="/mypage_reservation_list.php">스텝예약관리</a></li>
                                <li class="submenu-item"><a href="/mypage_request_list.php">신청고객관리</a></li>
                                <li class="submenu-item"><a href="/mypage_oldrequest_list.php">기존고객관리</a></li>
                                <li class="submenu-item"><a href="/mypage_wsend_list.php">발송내역관리</a></li>
                            <?php }?>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">웹문자</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="http://mk5.kr" target="_blank">웹문자</a></li>
                            <!--
                            <li class="submenu-item"><a href="/sub_16.php">웹문자소게</a></li>
                            <li class="submenu-item"><a href="/sub_17.php">웹문자접속</a></li>
                            -->
                        </ul>
                    </li>
                    <!--
                        <li class="menu-item">
                        <a href="#">국제문자</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="http://www.smsallline.com/home/login" target="_blank">국제문자</a></li>
                        </ul>
                    </li> 
                    -->
            </div>
            <?}
            else if($HTTP_HOST == "iam.kiam.kr") {?>
            <div class="mobile-gnb">
                <ul>
                    <li class="menu-item" >
                        <a href="sub_10.php">아이엠소개</a>
                    </li>
                    <li class="menu-item" >
                        <a href="/?cur_win=best_sample" target="_blank">아이엠샘플</a>
                    </li>
                    <li class="menu-item" >
                        <a href="https://tinyurl.com/3ucs2vbt" target="_blank">아이엠설치</a>
                    </li>

                    <li class="menu-item">
                        <a href="<?=$href?>">아이엠접속</a>

                    <li class="menu-item">
                        <a href="https://tinyurl.com/557nca2b" target="_blank">아이엠매뉴얼</a>
                    </li>

                    </li>
                </ul>
            </div>
            <?}
            else if($HTTP_HOST == "db.kiam.kr") {?>
            <div class="mobile-gnb">
                <ul>
                    <li class="menu-item" >
                        <a href="sub_2.php">온리원디버소개</a>
                    </li>
                    <li class="menu-item" >
                        <a href="https://url.kr/1ZK4Aq" target="_blank">디비수집영상</a>
                    </li>
                    <li class="menu-item" >
                        <a href="https://url.kr/dkLIEv">디버설치안내</a>
                    </li>
                   <li class="menu-item">
                        <a href="https://tinyurl.com/3puyvnpd" target="_blank">디버매뉴얼보기</a>
                   </li>
                </ul>
            </div>
            <?}
            else if($HTTP_HOST == "psms.kiam.kr") {?>
            <div class="mobile-gnb">
                <ul>
                    <li class="menu-item" >
                        <a href="sub_1.php">폰문자소개</a>
                    </li>
                    <li class="menu-item" >
                        <a href="sub_5.php">휴대폰등록</a>
                    </li>
                    <li class="menu-item" >
                        <a href="sub_6.php">폰문자발송</a>
                    </li>
                    <li class="menu-item" >
                        <a href="sub_11.php">콜백문자발송</a>
                    </li>
                    <li class="menu-item">
                        <a href="daily_list.php" >원스텝발송</a>
                    </li>
                    <li class="menu-item">
                        <a href="sub_4_return_.php" >발신내역보기</a>
                    </li>
                    <li class="menu-item">
                        <a href="sub_4.php?status=5&status2=3" >수신내역보기</a>
                    </li>
                    <li class="menu-item">
                        <a href="sub_4.php?status=6" >수신여부보기</a>
                    </li>
                    <li class="menu-item">
                        <a href="https://url.kr/fJaxTN6" target="_blank">매뉴얼보기</a>
                    </li>
                </ul>
            </div>
            <?}
            else {?>
            <div class="mobile-gnb">
                <ul>
                    <li class="menu-item">
                        <a href="#">솔루션시작</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="sub_8.php">셀링솔루션소개</a></li>
                            <li class="submenu-item"><a href="https://tinyurl.com/hb2pp6n2" target="_blank">매뉴얼따라하기</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">아이엠</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="sub_10.php#Introduce" >아이엠소개</a></li>
                            <li class="submenu-item"><a href="/?cur_win=best_sample" target="_blank">아이엠샘플</a></li>
                            <li class="submenu-item"><a href="https://tinyurl.com/3ucs2vbt" target="_blank">아이엠설치</a></li>
                            <li class="submenu-item"><a href="<?=$href?>" target="_blank">아이엠접속</a></li>
                            <li class="submenu-item"><a href="https://tinyurl.com/xwcvtxy4" target="_blank">아이엠매뉴얼</a></li>
                            <?
                            $sql_chk = "select count(a.mem_code) as cnt from Gn_Member a inner join Gn_Iam_Service b on a.mem_id=b.mem_id where a.service_type>=2 and a.mem_id='{$_SESSION['one_member_id']}'";
                            $res_chk = mysql_query($sql_chk);
                            $row_chk = mysql_fetch_array($res_chk);
                            if($row_chk[0] || $_SESSION['one_member_id'] == 'obmms02'){
                            ?>
                                <li class="submenu-item"><a href="calliya.php" target="_blank">콜이야</a></li>
                            <?}?>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">디비수집</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="sub_2.php">디버알아보기</a></li>
                            <li class="submenu-item"><a href="https://url.kr/DgTj4M">디버설치하기</a></li>
                            <li class="submenu-item"><a href="https://tinyurl.com/2p8ehzsm">디비수집하기</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">콜백문자</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="sub_11.php#Introduce">콜백알아보기</a></li>
                            <li class="submenu-item"><a href="https://www.onestore.co.kr/userpoc/apps/view?pid=0000738690" target="_blank">콜백설치하기</a></li>
                            <li class="submenu-item"><a href="https://tinyurl.com/3teh9ez5">콜백이용하기</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">폰문자</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="sub_1.php">폰문자소개</a></li>
                            <li class="submenu-item"><a href="sub_5.php">휴대폰등록</a></li>
                            <li class="submenu-item"><a href="sub_9.php#Introduce">메시지카피</a></li>
                            <li class="submenu-item"><a href="sub_6.php">폰문자발송</a></li>
                            <li class="submenu-item"><a href="/daily_list.php">데일리발송</a></li>
                            <li class="submenu-item"><a href="sub_4_return_.php">발신내역보기</a></li>
                            <li class="submenu-item"><a href="sub_4.php?status=5&status2=3">수신내역보기</a></li>
                            <li class="submenu-item"><a href="sub_4.php?status=6">수신여부보기</a></li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">스텝문자</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="/sub_12.php">원스텝소개</a></li>
                            <?php if($pay_data['onestep1'] != "ON" && $iam_type != 2) {?>
                                <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">랜딩페이지</a></li>
                                <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">고객신청창</a></li>
                                <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">스텝예약관리</a></li>
                                <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">신청고객관리</a></li>
                                <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">기존고객관리</a></li>
                                <li class="submenu-item"><a href="#" onclick="alert('결제 후 사용가능합니다.');">발송내역관리</a></li>
                            <?php }else{?>
                                <li class="submenu-item"><a href="mypage_landing_list.php">랜딩페이지</a></li>
                                <li class="submenu-item"><a href="/mypage_link_list.php">고객신청창</a></li>
                                <li class="submenu-item"><a href="/mypage_reservation_list.php">스텝예약관리</a></li>
                                <li class="submenu-item"><a href="/mypage_request_list.php">신청고객관리</a></li>
                                <li class="submenu-item"><a href="/mypage_oldrequest_list.php">기존고객관리</a></li>
                                <li class="submenu-item"><a href="/mypage_wsend_list.php">발송내역관리</a></li>
                            <?php }?>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="#">웹문자</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="http://mk5.kr" target="_blank">웹문자</a></li>
                            <!--
                            <li class="submenu-item"><a href="/sub_16.php">웹문자소게</a></li>
                            <li class="submenu-item"><a href="/sub_17.php">웹문자접속</a></li>
                            -->
                        </ul>
                    </li>
                    <!--
                    <li class="menu-item">
                        <a href="#">국제문자</a>
                        <ul class="submenu">
                            <li class="submenu-item"><a href="http://www.smsallline.com/home/login" target="_blank">국제문자</a></li>
                        </ul>
                    </li>
                    -->
                    <?if($is_pay_version){?>
                    <li class="menu-item">
                        <a href="#">결제안내</a>
                        <ul class="submenu">
                           <li class="submenu-item"><a href="/pay.php">솔루션결제</a></li>
                        </ul>
                    </li>
                    <?}?>
                </ul>
            </div>
            <?php }?>
        </div>        
    </aside><!-- // 모바일 메뉴 -->
    <div id="overlay"></div><!-- 모바일 메뉴 오버레이 -->

<script>
// 모바일 메뉴 토글 스크립트
$('#menuToggle').on('click', function(e){
    e.preventDefault();
    $('body').toggleClass('mb-menu-on');
});
$('#overlay').on('click', function(){
    $('body').removeClass('mb-menu-on');
});
// end

// 모바일 메뉴 서브메뉴 토글 스크립트
    $('#mobileMenu .menu-item > a').on('click', function(e){
        var menuItem = $(this).parents('.menu-item');
        if( menuItem.hasClass('show') == true ) {
            $('.menu-item').removeClass('show');
        } else {
            $('.menu-item').removeClass('show');
            menuItem.toggleClass('show');
        }
    });
// end
var tid;
	$(".popup_holder").hover(function(){
		$(".popupbox").hide();
		clearTimeout(tid);
		$(this).children(".popupbox").show();
		//$(this).children(".popupbox").css("height","50px");
		//$(this).children(".popupbox").css("width","200px");
	},function (e) {
		tid = setTimeout(function() {
			$(e.currentTarget).children(".popupbox").hide();
		}, 200);
   });
   $(".popup_text").hover(function(){
		$(this).css("color","#0f7bef");
		$(this).children(".popupbox").css("color","black");
	},function (e) {
		$(".popup_text").css("color","black");
   });
</script>
<style>
.popup_holder
{
	position:relative;
}
.popupbox
{	
	display:none;
	z-index: 1;
	text-align: left;
	font-size: 12px;
	font-weight: normal;
	background: white;
	border-radius: 3px;
	padding: 10px;
	border: none;
	position: absolute;
	box-shadow:  0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);
}
</style>
</body>
</html>
<script language="javascript" src="<?=$path?>js/rlatjd_fun.js?m=<?=time();?>"></script>
<script language="javascript" src="<?=$path?>js/rlatjd.js?m=<?= time();?>"></script>
<script language="javascript" >
function show_login() {
    $('.ad_layer_login').lightbox_me({
      centered: true,
      onLoad: function() {
      }
    });
}
</script>
<div class="ad_layer_login">
    <div class="layer_in">
        <span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>
        <div class="pop_title"></div>
        <div class="info_text" >
            <p>
                온리원셀링을 찾아주셔서 감사드립니다!<br/>
                온리원셀링과 온리원디버는 로그인후 사용이 가능합니다.
            </p>
            <div class="bnt2">
                <div class="wrap">
                    <a href="/join.php" class="button" target="_blank" >회원 가입 하기</a>
                    <a href="javascript:login_form.one_id.focus();$('.lb_overlay').hide();$('.ad_layer_login').hide();" class="button" target="_blank">로그인 하기</a>
                </div>
            </div>
        </div>
    </div>
</div>