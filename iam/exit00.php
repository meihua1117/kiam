<?php 
include "inc/header.inc.php";
?>
<style>
input[type=checkbox] {display:none;margin-left:7px;}
.desc li {
    margin-bottom: 5px;
    font-size: 12px;
    line-height: 18px;
}    
.input-wrap a {
    float: right;
    width: 65px;
    display: block;
    margin-left: 5px;
    padding: 7px 5px;
    font-size: 11px;
    color: #fff;
    line-height: 14px;
    background-color: #ccc;
    text-align: center;
}
ul li {
    cursor: auto !important;
}
</style>
<main id="register" class="common-wrap" style="margin-top: 86px"><!-- 컨텐츠 영역 시작 -->
    <input type = "hidden" name = "link" id = "link" value="<?=$link?>">
    <div class="inner-wrap">
        <form name="leave_form" action="" method="post">
            <section class="input-field">
                <a ><h3 class="title">회원탈퇴</h3></a>
                <div class="utils clearfix"></div>
                <div class="contents form-wrap">
                    <div class="attr-row is-account">
                        <div class="attr-name">비밀번호확인</div>
                        <div class="attr-value">
                            <div class="input-wrap">
                                <input type="password" class="input" name="leave_pwd" id="leave_pwd">
                            </div>
                        </div>
                    </div>
                    <div class="attr-row is-account">
                        <div class="attr-name">탈퇴사유</div>
                        <div class="attr-value">
                            <div class="input-wrap">
                                <input type="text" class="input" name="leave_liyou" id="leave_liyou">
                            </div>
                        </div>
                    </div>
                    <div class="desc-wrap">
                        <div class="inner">
                            <ul>[탈퇴시 삭제 사항]
                                <li>① 회원탈퇴시 아이디, 이름, 폰번호, 저장된 주소록, 텍스트메시지, 이미지, 수발신 기록, 수신거부, 수신동의정보가 삭제됩니다.</li>
                                <li>② 회원탈퇴시 수당지급정보와 이후 수당지급이 중단됩니다. </li>
                                <li>③ 회원탈퇴시 e프로필정보와 e프로필수발신 정보 및 대화정보가 삭제됩니다.</li>
                                <li>④ 회원탈퇴시 원퍼널솔루션의 수신동의자 정보와 랜딩정보, 예약메시지정보가 모두 삭제됩니다. </li>
                            </ul></br>
                            <ul>[탈퇴 세부 안내]
                                <li>① 폰에 설치된 '아이엠'앱을 삭제합니다.</li>
                                <li>② 탈퇴신청 후 1개월까지는 고객 정보가 보관됩니다. </li>
                                <li>③ 탈퇴 후 정보는 1개월 이내에 복구 가능하며 복구비용을 납부해야 합니다.</li>
                                <li>④ 탈퇴후 1개월 이내 복구 요청시 이메일로 복구요청을 해주세요. (개발자 계정 : 1pagebook@naver.com) </li>
                            </ul>
                        </div>
                    </div>
                    <div class="button-wrap">
                        <a href="javascript:history.back(-1);" class="button is-grey">고민중입니다</a>
                        <a href="#" onclick="member_leave()" class="button is-pink">회원탈퇴합니다</a>
                    </div>
                </div>
            </section>
        </form>
    </div>
</main><!-- // 컨텐츠 영역 끝 -->
<div id="ajax_div" style="display:none"></div>
<script language="javascript">
    $(function() {
        $(document).ajaxStop(function() {
            $("#ajax-loading").delay(10).hide(1);
        });
    });
    function member_leave(){
        if($('#leave_pwd').val() == "") {
            alert('비밀번호를 입력해주세요.');
            return;
        }
        if($('#leave_liyou').val() == "") {
            alert('탈퇴사유를 입력해주세요.');
            return;
        }
        if(confirm('탈퇴하시겠습니까?')){
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
</script>
