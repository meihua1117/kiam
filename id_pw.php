<?
$path="./";
include_once "_head.php";
?>
<div class="big_sub">
	<div class="big_1">
    	<div class="m_div">
        	<div class="left_sub_menu">
                <a href="./">홈</a> > 
                <a href="id_pw.php">아이디/비밀번호 찾기</a>
            </div>
            <div class="right_sub_menu">&nbsp;</div>
            <p style="clear:both;"></p>
    	</div>
    </div>
    <div class="m_div" style="padding-bottom:50px;">
		<div><img src="images/sub_btn_03.jpg" /></div>
        <div class="id_pw">
        	<div class="a1" style="float:left;">
            	<div class="b1">아이디 찾기</div>
                <div class="b2">
                	<div class="c1">회원유형</div>
                	<div class="c2">아이디가 고객님의 등록 정보로 발송됩니다.</div>
                    <div class="c3">
                    <form name="id_form" action="" method="post">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="width:15%;">이름</td>
                                <td><input type="text" name="mem_name" required itemname='이름' /></td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:10px 0 10px 0;">
                                	<div>
                                        <label>
                                            <input type="radio" name="search_type" checked  value="phone" onClick="show_cencle('email_phone',0,1,'id_mobile','id_email','0','phone')"/>
                                            휴대폰으로 아이디를 검색합니다.
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="radio" name="search_type" value="email" onClick="show_cencle('email_phone',1,0,'id_email','id_mobile','0','email')" />
                                            이메일로 아이디를 검색합니다.
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr class="email_phone">
                                <td>휴대폰</td>
                                <td>
                                <input type="text" name="mobile_1" class="id_mobile" required itemname='휴대폰' maxlength="4" style="width:70px;"/> -
                                <input type="text" name="mobile_2" class="id_mobile" required itemname='휴대폰' maxlength="4" style="width:70px;"/> -
                                <input type="text" name="mobile_3" class="id_mobile" style="width:70px;" required itemname='휴대폰' maxlength="4"/>
                    			</td>
                            </tr>
                            <tr class="email_phone" style="display:none;">
                                <td>이메일</td>
                                <td>
                                <input type="text" name="email_1" class="id_email" itemname='이메일' style="width:70px;"/> @ 
                                <input type="text" name="email_2" class="id_email" id='id_email' itemname='이메일' style="width:70px;"/>
                                <select name="email_3" itemname='이메일' onchange="inmail(this.value,'id_email')" style="background-color:#c8edfc;">
                                <?
                                foreach($email_arr as $key=>$v)
                                {
                                ?>
                                <option value="<?=$key?>"><?=$v?></option>                            
                                <?
                                }
                                ?>
                                </select>
                    			</td>
                            </tr>                                                        
                            <tr>
                                <td colspan="2" style="text-align:center;padding-top:40px">
                                <input type="hidden" name="serch_type" value="phone" />
                                <a href="javascript:void(0)" onClick="search_id_pw(id_form)"><img src="images/sub_btn_07.jpg"></a> &nbsp;
                                <a href="id_pw.php"><img src="images/sub_btn_09.jpg"></a>                                 
                                </td>
                            </tr>
                        </table>
                     </form>   
                    </div>                    
                </div>                 
            </div>
            <div class="a1" style="float:right;">
            	<div class="b1">비밀번호 찾기</div>
                <div class="b2">
                	<div class="c1">회원유형</div>
                	<div class="c2">임시비밀번호가 고객님의 등록정보로 발송됩니다.</div>
                    <div class="c3">
                    <form name="pw_form" action="" method="post">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="width:15%;">이름</td>
                                <td><input type="text" name="mem_name" required itemname='이름' /></td>
                            </tr>
                            <tr>
                            	<td>아이디</td>
                            	<td><input type="text" name="mem_id" required itemname='아이디' /></td>                                
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:10px 0 10px 0;">
                                	<div>
                                        <label>
                                            <input type="radio" name="search_type" checked value="phone" onClick="show_cencle('email_phone',2,3,'pw_mobile','pw_email','1','phone')" /> 휴대폰으로 아이디를 검색합니다.
                                        </label>
                                    </div>
                                    <div>
                                        <label>
                                            <input type="radio" name="search_type" value="email" onClick="show_cencle('email_phone',3,2,'pw_email','pw_mobile','1','email')" /> 이메일로 아이디를 검색합니다.
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr class="email_phone">
                                <td>휴대폰</td>
                                <td>
                    <input type="text" name="mobile_1" required itemname='휴대폰' class="pw_mobile" maxlength="4" style="width:70px;"/> -
                    <input type="text" name="mobile_2" required itemname='휴대폰' class="pw_mobile" maxlength="4" style="width:70px;"/> -
                    <input type="text" name="mobile_3" style="width:70px;" class="pw_mobile" required itemname='연락처' maxlength="4"/>
                    			</td>
                            </tr>
                            <tr class="email_phone" style="display:none;">
                                <td>이메일</td>
                                <td>
                                <input type="text" name="email_1" itemname='이메일' class="pw_email" style="width:70px;" /> @ 
                                <input type="text" name="email_2" id='pw_email' class="pw_email" itemname='이메일' style="width:70px;" />
                                <select name="email_3" itemname='이메일' onchange="inmail(this.value,'pw_email')" style="background-color:#c8edfc;">
                                <?
                                foreach($email_arr as $key=>$v)
                                {
                                ?>
                                <option value="<?=$key?>"><?=$v?></option>                            
                                <?
                                }
                                ?>
                                </select>
                    			</td>
                            </tr>                            
                            <tr>
                                <td colspan="2" style="text-align:center;padding-top:40px">
                                <input type="hidden" name="serch_type" value="phone" />
                                <a href="javascript:void(0)" onClick="search_id_pw(pw_form)"><img src="images/sub_btn_07.jpg"></a> &nbsp;
                                <a href="id_pw.php"><img src="images/sub_btn_09.jpg"></a>                                
                                </td>
                            </tr>
                        </table>
                    </form>    
                    </div>                                    
                </div>       
            </div>
            <p style="clear:both;"></p>
        </div>    
        <div class="id_pw">
            <div class="b1" onclick="showInfo()">회원탈퇴(클릭)</div>
            <form name="leave_form" action="" method="post">
                <div id="outLayer" style="display:none">
                    <div style="margin-bottom:5px">회원탈퇴를 신청하시면 즉시 해제되며 다시 로그인 할수 없습니다.회원탈퇴를 신청하시려면 아래 작성후 회원탈퇴를 클릭해주세요.</div>
                    <div style="margin-bottom:5px; color:red;">결제상품이 있으시면 반드시 사이트 상단 마이페이지 >> 결제정보에서 해지신청을 해주세요!!</div></br>
                    <div class="desc-wrap" style="text-align: left;">
                        <div class="inner">
                            <ul>[탈퇴시 삭제 사항]
                                <li style="text-align:left">① 회원탈퇴시 아이디, 이름, 폰번호, 저장된 주소록, 텍스트메시지, 이미지, 수발신 기록, 수신거부, 수신동의정보가 삭제됩니다.</li>
                                <l style="text-align:left"i>② 회원탈퇴시 수당지급정보와 이후 수당지급이 중단됩니다. </li>
                                <li style="text-align:left">③ 회원탈퇴시 e프로필정보와 e프로필수발신 정보 및 대화정보가 삭제됩니다.</li>
                                <li style="text-align:left">④ 회원탈퇴시 원스텝솔루션의 수신동의자 정보와 랜딩정보, 예약메시지정보가 모두 삭제됩니다. </li>
                            </ul></br>
                            <ul>[탈퇴 세부 안내]
                                <li style="text-align:left">① 폰에 설치된 '아이엠'앱을 삭제합니다.</li>
                                <li style="text-align:left">② 탈퇴신청 후 1개월까지는 고객 정보가 보관됩니다. </li>
                                <li style="text-align:left">③ 탈퇴 후 정보는 1개월 이내에 복구 가능하며 복구비용을 납부해야 합니다.</li>
                                <li style="text-align:left">④ 탈퇴후 1개월 이내 복구 요청시 이메일로 복구요청을 해주세요. (개발자 계정 : 1pagebook@naver.com) </li>
                            </ul>
                        </div>
                    </div>
                    <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0" style="font-size: 12px">
                        <tr>
                            <td>아이디</td>
                            <td><input type="id" name="id" required itemname='아이디' /></td>
                        </tr>
                        <tr>
                            <td>비밀번호</td>
                            <td><input type="password" name="leave_pwd" required itemname='비밀번호' /></td>
                        </tr>
                        <tr>
                            <td>탈퇴사유</td>
                            <td><input type="text" name="leave_liyou" required itemname='탈퇴사유' style="width:90%;" /></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:center;padding:30px;">
                                <a href="javascript:void(0)" onclick="member_leave(leave_form)"><img src="images/sub_mypage_17.jpg" /></a>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
               
    </div>
</div>    
<script>
    $(function(){
        $('input[type=radio]').bind("click",function(){
           $('.pw_mobile').val('');
           $('.pw_email').val('');
           $('.id_mobile').val('');
           $('.id_email').val('');           
        });
    })
    function showInfo() {
        if($('#outLayer').css("display") == "none") {
            $('#outLayer').show();
        } else {
            $('#outLayer').hide();
        }
    }
</script>
<?
include_once "_foot.php";
?>