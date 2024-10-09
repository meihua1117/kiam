<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");

$sql = "SELECT id, round_num, state_flag, iam_count FROM crawler_gm_seller_info_ad ORDER BY id DESC LIMIT 1";
$result = mysql_query($sql);
while($res = mysql_fetch_array($result)){
    $id = $res['id'];
}
$round_num = (int)$id + 1;

$state = null;
$sql_state = "SELECT round_num, state_flag, market_count FROM crawler_gm_status_info_ad ORDER BY id DESC LIMIT 1";
$result_state = mysql_query($sql_state);
while($res = mysql_fetch_array($result_state)){
    $round = $res['round_num'];
    $state = $res['state_flag'];
    $iam_count = $res['market_count'];
}
?>

<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    var mem_id="";
    $( document ).ready( function() {
        <?php
        $sql_member = "SELECT a.mem_id FROM Gn_Member a INNER JOIN crawler_gm_seller_info_ad b on SUBSTRING_INDEX(a.mem_id, '_', -1)=b.store_id WHERE b.round_num={$round}";
        $result_member = mysql_query($sql_member);
        if((mysql_num_rows($result_member) == 0) && ($state == 0) && ($state != null)){
        ?>
        $.ajax({
            type: "POST",
            url: "iam_auto_gmarket_ad.php",
            dataType: "json",
            data: {round:<?php echo $round;?>},
            success: function (data) {
                alert("아이엠이 자동생성되었습니다.");
                location.reload();
            }
        });
        <?php    
        }
        ?>
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
                    '** 샘플 아이엠 보기 http://kiam.kr/?cur_win=best_sample' +
                    '[운영자님의 아이엠 만들기 절차]'+
                    '1. 내 아이엠자동생성하기'+
                    '운영자님의 샵에 노출된 공개정보를 크롤링하여 자동으로 운영자님의 샵에 대한 기본적인 아이엠이 생성됩니다.'+
                    '2. 내 아이엠 꾸미기 : 아래 샘플 아이엠처럼 멋지게 운영자님의 아이엠을 수정보완하시면 됩니다.' +
                    '3. 내 아이엠 홍보하기 : 내 아이엠 주소를 지인, 이메일, 블로그등으로 홍보하시면 됩니다.' +
                    '자!그러면 운영자님의 아이엠을 자동생성해보실래요?'+
                    '운영자님의 네이버샵에 노출된 사업자정보, 샵주소, 상품제목, 상품이미지 등을 크롤링하여 자동으로 생성하는 시스템입니다. ' +
                    '생성후에 생성된 아이엠 링크 정보를 통해 운영자님의 아이엠을 확인하고 수정할수 있습니다. ' +
                    '확인하고 나서 필요가 없다고 생각되면 취소를 통해 삭제할수 있습니다.'+
                    '[네이버 샵의 아이엠 자동생성하기]'+
                    'http://kiam.kr/admin/iam_auto_make_check.php?memid='+memid
            },
            success:function (data){
                //alert('발송 성공: ' + phone_number + ':' + memid);
            },error: function(){
                alert('발송 실패');
            }
        })
    }
    //아이엠 수동 신청
    function iam_making_db(memid){
        $.ajax({
            type:"GET",
            url:"iam_auto_make_check.php?memid="+memid+"&from:form",
            cache: false,
            success:function(){
                //$("#ajax_div").html(data);
                alert('아이엠 신청이 처리되었습니다.');
                location.reload();
            },
            error: function(){
                alert('아이엠 생성 실패');
            }
        });
    }
    //계정 삭제
    function del_making_db(memid){
        var msg = confirm('정말로 삭제하시겠습니까?');
        if(msg){
            $.ajax({
                type:"POST",
                url:"iam_auto_delete.php",
                data:{memid:memid},
                success:function(){
                    //$("#ajax_div").html(data);
                    alert('삭제되었습니다.');
                    location.reload();
                },
                error: function(){
                    alert('삭제 실패');
                }
            });

        }else{
            return false;
        }
    }

    //주소록 다운
    function excel_down_(){
        $("#excel_down_form").submit();
        return false;
    }


    function goPage(pgNum) {
        location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&type=<?php echo $type;?>";
    }

    //주소록 다운
    function excel_down_p_group(pno,one_member_id){
        $($(".loading_div")[0]).show();
        $($(".loading_div")[0]).css('z-index',10000);
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yy = today.getFullYear().toString().substr(2,2);
        if(dd<10) {
            dd='0'+dd
        }
        if(mm<10) {
            mm='0'+mm
        }

        $.ajax({
            type:"POST",
            dataType : 'json',
            url:"/ajax/ajax_session_admin.php",
            data:{
                group_create_ok:"ok",
                group_create_ok_nums:pno,
                group_create_ok_name:pno.substr(3,8)+'_'+''+ mm+''+dd,
                one_member_id:one_member_id
            },
            success:function(data){
                $($(".loading_div")[0]).hide();
                $('#one_member_id').val(one_member_id);
                parent.excel_down('/excel_down/excel_down_.php?down_type=1&one_member_id='+one_member_id,data.idx);
            }

        });
    }
    function interval_func(){
        round = $("#round").val();
        market = $("#market").val();
        delay_time = $("#delay_time").val();
        // document.cookie = market;
        if((round == '') || (market == '') || (market == 0) || (delay_time == '')){
            alert("마켓개수/지연시간을 설정 하세요!");
            return;
        }
        console.log(round, market, delay_time);
        $.ajax({
            type:"POST",
            dataType:"json",
            data:{round:round, market:market, delay_time:delay_time},
            url:"https://www.goodhow.com/crawler/Crawler_gmarket",
            //url:"http://localhost:8088",
            success: function(data){
                console.log(data);
            }
        });
        $('#crawling_start').attr('disabled', true);
    }
</script>
<style>
    .loading_div {
        display:none;
        position: fixed;
        left: 50%;
        top: 50%;
        display: none;
        z-index: 1000;
    }
</style>
<div class="loading_div" ><img src="/images/ajax-loader.gif"></div>
<!-- Top 메뉴 -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
<div class="wrapper" style="display:block; overflow: initial;">
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                아이엠디비수집관리
                <small>아이엠 디비를 자동으로 수집합니다.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">아이엠디비수집관리</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="20%">
                                <col width="20%">
                                <col width="10%">
                                <col width="10%">
                                <col width="20%">
                                <col width="20%">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>사이트</th>
                                <th>회차번호</th>
                                <th>마켓개수</th>
                                <th>지연시간</th>
                                <th>상태</th>
                                <th>동작</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>GMarket</th>
                                <?php
                                if($state == 0){
                                ?>
                                    <th><input type="number" name="round_num" id="round" disabled style="margin-right:30px;" value=<?=$round_num?>></th>
                                    <th><input type="number" name="market_num" id="market" style="margin-right:30px;" value=<?=$iam_count;?>></th>
                                    <th><input type="number" name="delay_time" id="delay_time" style="margin-right:30px;" value=0></th>
                                    <th><input type="text" name="status" id="status" disabled style="margin-right:30px;"></th>
                                    <th>
                                    <button class="btn btn-primary" style="margin-right: 31px;" id="crawling_start" onclick="interval_func()">
                                        <i class="fa fa-download"></i> 아이엠생성
                                    </button>
                                    <!-- <button class="btn btn-primary" style="margin-right: 31px;" id="crawling_stop" onclick="stop_signal()">
                                        중지
                                    </button> -->
                                    </th>
                                <?}
                                else{
                                ?>
                                    <th><input type="number" name="round_num" id="round" disabled style="margin-right:30px;" value=<?=$round?>></th>
                                    <th><input type="number" name="market_num" id="market" style="margin-right:30px;" value=<?=$iam_count;?>></th>
                                    <th><input type="number" name="delay_time" id="delay_time" style="margin-right:30px;" value=0></th>
                                    <th><input type="text" name="status" id="status" disabled style="margin-right:30px;"></th>
                                    <th>
                                    <button class="btn btn-primary" style="margin-right: 31px;" id="crawling_start" disabled>
                                        <i class="fa fa-download"></i> 아이엠생성중...
                                    </button>
                                    <!-- <button class="btn btn-primary" style="margin-right: 31px;" id="crawling_stop" onclick="stop_signal()">
                                        중지
                                    </button> -->
                                    </th>
                                <?}?>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered table-striped" style="margin-top: 30px" id="gm_iam">
                            <colgroup>
                                <col width="60px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="50px">
                                <col width="50px">
                                <col width="200px">
                                <col width="100px">
                                <col width="100px">
                                <col width="60px">
                                <col width="150px">
                                <col width="70px">
                            </colgroup>
                            <thead>
                            <tr>
                            <th>NO</th>
                            <th>출처</th>
                            <th>아이디</th>
                            <th>이름</th>
                            <th>이메일</th>
                            <th>아이엠링크</th>
                            <th>주소</th>
                            <th>상호명</th>
                            <th>폰번호</th>
                            <th>사업자번호</th>
                            <th>마켓링크</th>
                            <th>등급</th>
                            <th>회차번호</th>
                            </tr>
                            </thead>
                            <tbody id="iam_body">
                            <?php
                            $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                            $startPage = $nowPage?$nowPage:1;
                            $pageCnt = 20;

                            $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                            $number = $totalCnt - ($nowPage - 1) * $pageCnt;

                            // $query = "select * from crawler_gm_seller_info";
                            $query = "select a.*, b.card_short_url, b.mem_id from crawler_gm_seller_info_ad a inner join Gn_Iam_Name_Card b on a.store_id=SUBSTRING_INDEX(b.mem_id, '_', -1) where a.round_num={$round}";
                            // echo $query; exit;
                            $res = mysql_query($query);
                            $totalCnt = mysql_num_rows($res);

                            $query1 = $query.$limitStr;
                            $res1 = mysql_query($query1);
                            $no = 0;
                            while($row=mysql_fetch_array($res1))
                            {
                                $mem_sql = "select mem_code from Gn_Member where mem_id='{$row['mem_id']}'";
                                $res_mem = mysql_query($mem_sql);
                                $row_mem = mysql_fetch_array($res_mem);
                                $no++;
                            ?>
                                <tr>
                                    <th><?=$no?></th>
                                    <th>gmarket</th>
                                    <th>gmarket_<?=$row['store_id']?></th>
                                    <th><?=$row['daepyoja']?></th>
                                    <th><?=$row['email']?></th>
                                    <th><div style="overflow-x:hidden;width:100px;">
                                        <a href="<?='/?'.$row['card_short_url'].$row_mem['mem_code']?>" target="_blank"><?='/?'.$row['card_short_url'].$row_mem['mem_code']?></a> </div></th>
                                    <th><?=$row['sojaeji']?></th>
                                    <th><?=$row['sangho']?></th>
                                    <th><?=$row['phonenum']?></th>
                                    <th><?=$row['workernum']?></th>
                                    <th><?=$row['store_link']?></th>
                                    <th><?=$row['level']?></th>
                                    <th><?=$round?></th>
                                </tr>
                            <?}?>
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->

            <div class="row">
                <div class="col-sm-5">
                    <div class="dataTables_info" id="example1_info" role="status" aria-live="polite">총 <?=$totalCnt?> 건</div>
                </div>
                <div class="col-sm-7">
                    <?
                    echo drawPagingAdminNavi($totalCnt, $nowPage);
                    ?>
                </div>
            </div><!-- /.row -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

<form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
    <input type="hidden" name="grp_id" value="" />
    <input type="hidden" name="box_text" value="" />
    <input type="hidden" name="one_member_id" id="one_member_id" value="" />
    <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
</form>
<iframe name="excel_iframe" style="display:none;"></iframe>

<script language="javascript">
    let round = 0;
    $( document ).ready( function() {});
    function changeLevel(mem_code) {
        var mem_leb = $('#mem_leb'+mem_code+" option:selected").val();
        var data = {"mode":"change","mem_code":"'+mem_code+'","mem_leb":"'+mem_leb+'"};
        $.ajax({
            type:"POST",
            url:"/admin/ajax/user_level_change.php",
            dataType:"json",
            data:{mode:"change",mem_code:mem_code,mem_leb:mem_leb},
            success:function(data){
                //console.log(data);
                //location = "/";
                //location.reload();
                alert('변경이 완료되었습니다.');
            },
            error: function(){
                alert('초기화 실패');
            }
        });

//    alert(mem_code);
    }

    function loginGo(mem_id, mem_pw, mem_code) {
        $('#one_id').val(mem_id);
        $('#mem_pass').val(mem_pw);
        $('#mem_code').val(mem_code);
        $('#login_form').submit();
    }
    setInterval(function(){
        round = $("#round").val();
        market = $("#market").val();
        if($('#crawling_start').prop("disabled") == true){
            $.ajax({
                type: "POST",
                url: "get_db_crawling_status_gm.php",
                dataType: "json",
                data:{get:true, round:round},
                success: function (data) {
                    console.log(data.count, data.status, data.round_num);
                    count = market + '/' + data.count;
                    $("#status").val(count);
                    round = data.round_num;
                    if(data.status == 0){
                        $.ajax({
                            type: "POST",
                            url: "iam_auto_gmarket_ad.php",
                            dataType: "json",
                            data: {round:round},
                            success: function (data) {
                                alert("아이엠이 자동생성되었습니다.");
                                location.reload();
                            }
                        });
                        $('#crawling_start').attr('disabled', false);
                    }
                }
            });
        }
    }, 5000);
</script>
<!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      