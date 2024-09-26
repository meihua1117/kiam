<?
$path="./";
include_once "_head.php";
$code = whois_ascc($whois_api_key, $_SERVER['REMOTE_ADDR']);
$link  = $_GET["link"];

?>
<style>
    .inner ul li {
        text-align : left !important;
    }
</style>
<div class="big_sub">
   <?php include "mypage_base_navi.php";?>
    <input type = "hidden" name = "link" id = "link" value="<?=$link?>">
    <div id='order_address' style='left:0px; top:0px; width:320px; height:600px;  z-index:1000; display:none; background-color:white;'></div>
   <div class="m_div" id="m_div_mp">
        <div class="join">
            <form name="leave_form" action="" method="post">
                <div class="a1">
                    <li style="float:left;"><a href="javascript:void(0)" >회원탈퇴</a></li>
                    <li style="float:right;"></li>
                    <p style="clear:both"></p>
                </div>
            <div id="outLayer" style="">
                <div style="margin-bottom:5px">회원탈퇴를 신청하시면 즉시 해제되며 다시 로그인 할수 없습니다.회원탈퇴를 신청하시려면 아래 작성후 회원탈퇴를 클릭해주세요.</div>
                <div style="margin-bottom:5px; color:red;">결제상품이 있으시면 반드시 사이트 상단 마이페이지 >> 결제정보에서 해지신청을 해주세요!!</div></br>
                <div class="desc-wrap" style="text-align: left;">
                    <div class="inner">
                        <ul>[탈퇴시 삭제 사항]
                            <li>① 회원탈퇴시 아이디, 이름, 폰번호, 저장된 주소록, 텍스트메시지, 이미지, 수발신 기록, 수신거부, 수신동의정보가 삭제됩니다.</li>
                            <li>② 회원탈퇴시 수당지급정보와 이후 수당지급이 중단됩니다. </li>
                            <li>③ 회원탈퇴시 e프로필정보와 e프로필수발신 정보 및 대화정보가 삭제됩니다.</li>
                            <li>④ 회원탈퇴시 원스텝솔루션의 수신동의자 정보와 랜딩정보, 예약메시지정보가 모두 삭제됩니다. </li>
                        </ul></br>
                        <ul>[탈퇴 세부 안내]
                            <li>① 폰에 설치된 '아이엠'앱을 삭제합니다.</li>
                            <li>② 탈퇴신청 후 1개월까지는 고객 정보가 보관됩니다. </li>
                            <li>③ 탈퇴 후 정보는 1개월 이내에 복구 가능하며 복구비용을 납부해야 합니다.</li>
                            <li>④ 탈퇴후 1개월 이내 복구 요청시 이메일로 복구요청을 해주세요. (개발자 계정 : 1pagebook@naver.com) </li>
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
                        <td>탈퇴사유</td>
                        <td><input type="text" name="leave_liyou" required itemname='탈퇴사유' style="width:90%;" /></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align:center;padding:30px;">
                            <a href="javascript:void(0)" onclick="member_leave(leave_form)"><img src="images/sub_mypage_17.jpg" /></a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
   </div>
</div><!--end big_sub-->
<?
include_once "_foot.php";
?>

<script>
.ajaxStop(function() {
    $("#ajax-loading").delay(10).hide(1);
});
</script>

<style>
ul, li(.desc-wrap.inner li){
    text-align:left;
}
</style>