<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");

$sql = "SELECT id, round_num, state_flag, iam_count FROM crawler_gm_seller_info ORDER BY id DESC LIMIT 1";
$result = mysqli_query($self_con, $sql);
while($res = mysqli_fetch_array($result)){
    $id = $res['id'];
}
$round_num = (int)$id + 1;

$state = null;
$sql_state = "SELECT round_num, state_flag, market_count FROM crawler_gm_status_info ORDER BY id DESC LIMIT 1";
$result_state = mysqli_query($self_con, $sql_state);
while($res = mysqli_fetch_array($result_state)){
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
        $sql_member = "SELECT a.mem_id FROM Gn_Member a INNER JOIN crawler_gm_seller_info b on SUBSTRING_INDEX(a.mem_id, '_', -1)=b.store_id WHERE b.round_num={$round}";
        $result_member = mysqli_query($self_con, $sql_member);
        if((mysqli_num_rows($result_member) == 0) && ($state == 0) && ($state != null)){
        ?>
        $.ajax({
            type: "POST",
            url: "iam_auto_gmarket.php",
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
    .zoom {
    width:50px;
    transition: transform .2s; /* Animation */
    }
    .zoom:hover {
    transform: scale(4); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    border:1px solid #0087e0;
    box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
    }

    .zoom-2x {
    transition: transform .2s; /* Animation */
    }

    .zoom-2x:hover {
    transform: scale(2); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
    border:1px solid #0087e0;
    box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
    }
</style>
<div class="loading_div" ><img src="/images/ajax-loader.gif"></div>
<div class="wrapper">

    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>

    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                네이버지도업체 주소 IAM 자동생성 관리
                <small>주소 아이엠을 관리합니다.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">아이엠디비수집관리</li>
            </ol>
        </section>

        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
            <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />
            <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />
            <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />
        </form>

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <tbody>
                            <form method="get" name="search_people" id="search_people">
                                <div class="box-tools">
                                    <div class="input-group" style="display:inline-flex;width: 30%;">
                                        <input type="text" name="peopleid" id="peopleid" class="form-control input-sm pull-right" placeholder="아이디" style="width:150px;">
                                        <input type="text" name="people_search_key" id="people_search_key" style="width:250px" class="form-control input-sm pull-right" placeholder="이름/추천인/소속">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            </tbody>
                        </table>

                        <table class="table table-bordered table-striped" style="margin-top: 30px" id="gm_iam">
                            <colgroup>
                                <col width="50px">
                                <col width="100px">
                                <col width="100px">
                                <col width="80px">
                                <col width="100px">
                                <col width="100px">
                                <col width="50px">
                                <col width="150px">
                                <col width="150px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="150px">
                            </colgroup>
                            <thead>
                            <tr>
                            <th>NO</th>
                            <th>신청ID</th>
                            <th>IAMID</th>
                            <th>이름</th>
                            <th>이미지</th>
                            <th>전화</th>
                            <th>콘수</th>
                            <!-- <th>소속</th> -->
                            <th>주소</th>
                            <!-- <th>N링크</th> -->
                            <th>소속/추천인</th>
                            <th>IAMlink</th>
                            <!-- <th>신청링크</th> -->
                            <!-- <th>신청</th> -->
                            <th>삭제</th>
                            <th>등록일시</th>
                            </tr>
                            </thead>
                            <tbody id="iam_body">
                            <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr .= $peopleid ? " AND (c.mem_id LIKE '%".$peopleid."%')" : null;
                                $searchStr .= $people_search_key ? "AND ( c.mem_phone like '%".$people_search_key."%' or c.recommend_id like '%".$rec_id."%' or c.mem_name like '%".$people_search_key."%' or c.site like  '%" .$people_search_key."%' or a.card_company like '%".$people_search_key."%')" : null;
                                
                                $order = $order?$order:"desc";
                                $query = "select b.*, c.get_cnt, d.recommend_id, d.site from crawler_map_info a INNER JOIN Gn_Iam_Name_Card b on a.iam_link=b.card_short_url INNER JOIN crawler_map_status_info c on a.id=c.info_id inner join Gn_Member d on c.mem_id=d.mem_id where 1=1 $searchStr";
                                $res	    = mysqli_query($self_con, $query);
                                $totalCnt	=  mysqli_num_rows($res);
                                $limitStr   = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number		= $totalCnt - ($nowPage - 1) * $pageCnt;
                                $i = 1;
                                $query .= "$orderQuery";
                                $excel_sql = "";
                                $excel_sql = $query;
                                $query .= "$limitStr";
                                // echo $query; exit;
                                $res = mysqli_query($self_con, $query);
                                while($row=mysqli_fetch_array($res)){
                                    $no++;
                                ?>
                                <tr>
                                    <td><?=$no?></td>
                                    <td><?=$row['mem_id']?></td>
                                    <td><?=$row['mem_id']?></td>
                                    <td><?=$row['card_name']?></td>
                                    <td>
                                        <div >
                                            <a href="<?=$row['main_img1']?>" target="_blank">
                                                <img class="zoom" src="<?=$row['main_img1']?>">
                                            </a>
                                        </div>
                                    </td>
                                    <td><?=$row['card_phone']?></td>
                                    <td><?=$row['get_cnt']?></td>
                                    <td><?=$row['card_addr']?></td>
                                    <!-- <td></td> -->
                                    <td><?=$row['site']?>/<?=$row['recommend_id']?></td>
                                    <td><a href="<?='/?'.$row['card_short_url']?>" target="_blank"><?='/?'.$row['card_short_url']?></a></td>
                                    <!-- <td></td> -->
                                    <!-- <td>Y</td> -->
                                    <td><button onclick="delete_map('<?=$row['card_short_url']?>')">삭제</td>
                                    <td><?=$row['req_data']?></td>
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
    </div><!-- /content-wrapper -->

<form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
    <input type="hidden" name="grp_id" value="" />
    <input type="hidden" name="box_text" value="" />
    <input type="hidden" name="one_member_id" id="one_member_id" value="" />
    <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
</form>
<iframe name="excel_iframe" style="display:none;"></iframe>

<script language="javascript">
    function delete_map(val){
        if(confirm("정말 삭제하시겠습니까?")){
            $.ajax({
                type:"POST",
                url:"delete_map.php",
                dataType:"json",
                data:{url:val},
                success:function(data){
                    if(data == 1){
                        alert("삭제 되었습니다.");
                        location.reload();
                    }
                    else{
                    }
                }
            })
        }
    }
</script>
<!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      