<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include_once/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

$sql = "SELECT id, round_num, state_flag, iam_count FROM crawler_gm_seller_info ORDER BY id DESC LIMIT 1";
$result = mysql_query($sql);
while($res = mysql_fetch_array($result)){
    $id = $res['id'];
    $round = $res['round_num'];
    $state = $res['state_flag'];
    $iam_count = $res['iam_count'];
}
$round_num = (int)$id + 1;
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    var mem_id="";
    $( document ).ready( function() {
        
    });
    function page_view(mem_id) {
        $('.ad_layer1').lightbox_me({
            centered: true,
            onLoad: function() {
                $.ajax({
                    type:"POST",
                    url:"/admin/ajax/member_list_page1.php",
                    data:{mem_id:mem_id},
                    dataType: 'html',
                    success:function(data){
                        $('#phone_list').html(data);
                    },
                    error: function(){
                        alert('로딩 실패');
                    }
                });
            }
        });
        $('.ad_layer1').css({"overflow-y":"auto", "height":"300px"});
    }
    $(function(){
    });
    function sendIamInvite(memid, phone_number,rDay,user_cnt,go_max_cnt,go_memo2,go_cnt1,go_cnt2,sendnum){
        $.ajax({
            type:"POST",
            url:"../ajax/sendmmsPrc.2020.php",
            data:{
                invite : 1,
                send_title:'아이엠 지원사업 안내',
                send_num:phone_number,
                send_rday:rDay,
                send_htime:0,
                send_mtime:0,
                send_type:1,
                send_agree_msg:'N',
                send_go_num:[sendnum],
                send_save_mms:0,
                send_ssh_check : 0,
                send_deny_wushi_0:'ok',
                send_deny_wushi_1:'ok',
                send_deny_wushi_2:'ok',
                send_go_user_cnt:[user_cnt],
                send_go_max_cnt:[go_max_cnt],
                send_go_memo2:[go_memo2],
                send_go_cnt1:[go_cnt1],
                send_go_cnt2:[go_cnt2],
                send_txt:'안녕하세요? 스마트샵 운영자님!이번에 저희 협회에서 네이버샵 운영자 대상으로 아이엠을 활용하여 샵을 홍보하는 시스템을 지원하고 있습니다.' +
                    '아래 아이엠의 샘플링크를 클릭하면 아이엠이 어떤 모습인지 볼수 있습니다. 운영자님도 자신의 샵을 소개하는 아이엠을 만들고 싶다면 아래 절차를 따라 신청하시면 됩니다.' +
                    '** 샘플 아이엠 보기 http://kiam.kr/iam/index_sample.php' +
                    '[운영자님의 아이엠 만들기 절차]'+
                    '1. 내 아이엠자동생성하기'+
                    '운영자님의 샵에 노출된 공개정보를 크롤링하여 자동으로 운영자님의 샵에 대한 기본적인 아이엠이 생성됩니다.'+
                    '2. 내 아이엠 꾸미기 : 아래 샘플 아이엠처럼 멋지게 운영자님의 아이엠을 수정보완하시면 됩니다.' +
                    '3. 내 아이엠 홍보하기 : 내 아이엠 주소를 지인, 이메일, 블로그등으로 홍보하시면 됩니다.' +
                    '자!그러면 운영자님의 아이엠을 자동생성해보실래요?'+
                    '운영자님의 네이버샵에 노출된 사업자정보, 샵주소, 상품제목, 상품이미지 등을 크롤링하여 자동으로 생성하는 시스템입니다. ' +
                    '생성후에 생성된 아이엠 링크 정보를 통해 운영자님의 아이엠을 확인하고 수정할수 있습니다. ' +
                    '확인하고 나서 �