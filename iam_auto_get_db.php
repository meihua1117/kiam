<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include_once/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

$sql = "SELECT id, round_num, state_flag, iam_count FROM crawler_gm_seller_info ORDER BY id DESC LIMIT 1";
$result = mysqli_query($self_con,$sql);
while($res = mysqli_fetch_array($result)){
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
</script>