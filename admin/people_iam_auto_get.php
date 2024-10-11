<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
?>

<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    function goPage(pgNum) {
        location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&type=<?php echo $type;?>";
    }
    $(function() {
        var contHeaderH = $(".main-header").height();
        var navH = $(".navbar").height();
        if(navH != contHeaderH)
            contHeaderH += navH - 50;
        $(".content-wrapper").css("margin-top",contHeaderH);
        var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 246;
        if(height < 375)
            height = 375;
        $(".box-body").css("height",height);
    });
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
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
    .wrapper{height:100%;overflow:auto;}
    .content-wrapper{min-height : 80% !important;}
    .box-body{overflow:auto;padding:0px !important}
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
                IAM 자동생성 관리
                <small>아이엠 자동생성을 관리합니다.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">아이엠디비수집관리</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
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
            </div>
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <table class="table table-bordered table-striped" id="gm_iam">
                            <colgroup>
                                <col width="50px">
                                <col width="100px">
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
                            </colgroup>
                            <thead>
                            <tr>
                            <th>NO</th>
                            <th>구분</th>
                            <th>신청ID</th>
                            <th>IAMID</th>
                            <th>이름</th>
                            <th>이미지</th>
                            <th>전화</th>
                            <th>콘수</th>
                            <th>소속</th>
                            <th>주소</th>
                            <th>소속/추천인</th>
                            <th>IAMlink</th>
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
                                $searchStr .= $peopleid ? " AND (d.mem_id LIKE '%".$peopleid."%')" : null;
                                // $searchStr .= $people_search_key ? "AND ( d.mem_phone like '%".$people_search_key."%' or d.recommend_id like '%".$rec_id."%' or d.mem_name like '%".$people_search_key."%' or d.site like  '%" .$people_search_key."%' or b.card_company like '%".$people_search_key."%')" : null;
                                
                                $order = $order?$order:"desc";
                                // $query = "select a.get_cnt, a.web_type, b.*, d.recommend_id, d.site from crawler_iam_info a INNER JOIN Gn_Iam_Name_Card b on a.iam_link=b.card_short_url inner join Gn_Member d on a.mem_id=d.mem_id where 1=1 $searchStr";
                                $query = "select * from crawler_iam_info where 1=1 $searchStr";
                                $res	    = mysqli_query($self_con,$query);
                                $totalCnt	=  mysqli_num_rows($res);
                                $limitStr   = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number		= $totalCnt - ($nowPage - 1) * $pageCnt;
                                $i = 1;
                                $query .= "$orderQuery";
                                $excel_sql = "";
                                $excel_sql = $query;
                                $query .= "$limitStr";
                                // echo $query; exit;
                                $res = mysqli_query($self_con,$query);
                                while($row=mysqli_fetch_array($res)){
                                    $mem_sql = "select mem_code, recommend_id, site from Gn_Member where mem_id='{$row['mem_id']}'";
                                    $mem_res = mysqli_query($self_con,$mem_sql);
                                    $mem_row = mysqli_fetch_array($mem_res);
                                    $no++;

                                    $sql_card = "select card_name, main_img1, card_phone, card_company, card_addr, card_short_url, req_data from Gn_Iam_Name_Card where card_short_url='{$row[iam_link]}'";
                                    $res_card = mysqli_query($self_con,$sql_card);
                                    $row_card = mysqli_fetch_array($res_card);
                                ?>
                                <tr>
                                    <td><?=$no?></td>
                                    <td><?=$row['web_type']?></td>
                                    <td><?=$row['mem_id']?></td>
                                    <td><?=$row['mem_id']?></td>
                                    <td><?=$row_card['card_name']?></td>
                                    <td>
                                        <div>
                                            <a href="<?=$row_card['main_img1']?>" target="_blank">
                                                <img class="zoom" src="<?=$row_card['main_img1']?>">
                                            </a>
                                        </div>
                                    </td>
                                    <td><?=$row_card['card_phone']?></td>
                                    <td><?=$row['get_cnt']?></td>
                                    <td><?=$row_card['card_company']?></td>
                                    <td><?=$row_card['card_addr']?></td>
                                    <!-- <td></td> -->
                                    <td><?=$row['site']?>/<?=$mem_row['recommend_id']?></td>
                                    <td><a href="<?='/?'.$row_card['card_short_url'].$mem_row['mem_code']?>" target="_blank"><?='/?'.$row_card['card_short_url'].$mem_row['mem_code']?></a></td>
                                    <!-- <td></td> -->
                                    <!-- <td>Y</td> -->
                                    <td><button onclick="delete_people('<?=$row['card_short_url']?>', '<?=$row['web_type']?>')">삭제</td>
                                    <td><?=$row_card['req_data']?></td>
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
    function delete_people(val, type){
        if(confirm("정말 삭제하시겠습니까?")){
            $.ajax({
                type:"POST",
                url:"delete_people.php",
                dataType:"json",
                data:{url:val, type:type},
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