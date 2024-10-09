<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$date_month=date("Y-m");
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script>
    var mem_id="";
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
        location.href = '?<?=$nowPage?>&nowPage='+pgNum+"&search_key=<?php echo $_GET['search_key'];?>&type=<?php echo $type;?>&orderField=<?=$orderField;?>&dir=<?=$dir;?>";
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
    function load_iam() {
        $('#btn_get').attr("disabled", true);
        $('#btn_get').html("<i class='fa fa-download'></i> 아이엠생성중...");
        $.ajax({
            type:"POST",
            url:"iam_auto_making_ajax.php",
            success:function(data){
                alert('아이엠이 자동생성되었습니다.');
                location.reload();
            },error: function (request, status, error) {
                alert('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
            }
        });
        return false;
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
<div class="wrapper" style="display: flex;overflow: initial">
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                N샵 IAM 자동생성관리
                <small>아이엠 네임카드를 자동으로 생성합니다.</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">N샵 자동생성관리</li>
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
                <div class="col-xs-12" style="padding-bottom:20px">
                    <?
                    $count_query = "select count(*) from Gn_Iam_automem where status=1";
                    $count_res = mysql_query($count_query);
                    $count_res = mysql_fetch_array($count_res);
                    $count = $count_res[0];
                    if($count == 0) {
                        ?>
                        <button class="btn btn-primary pull-right" style="margin-right: 5px;" id="btn_get" onclick="load_iam()">
                            <i class="fa fa-download"></i> 아이엠생성하기
                        </button>
                        <?
                    }else{
                    ?>
                        <button class="btn btn-primary pull-right" style="margin-right: 5px; " id="btn_get" onclick="load_iam()" disabled>
                            <i class="fa fa-download"></i> 아이엠생성중...
                        </button>
                    <?}?>


                    <form method="get" name="search_form" id="search_form">
                        <div class="box-tools">
                            <div class="input-group" style="width: 250px;">
                                <input type="text" name="search_key" id="search_key" class="form-control input-sm pull-right" placeholder="아이디/이름" value="<?php echo $search_key;?>">
                                <div class="input-group-btn">
                                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">

                <div class="box">
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <colgroup>
                                <col width="60px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="60px">
                                <col width="70px">
                                <col width="70px">
                                <col width="70px">
                                <col width="70px">
                                <col width="70px">
                                <col width="70px">
                            </colgroup>
                            <thead>
                            <tr>
                                <th>NO</th>
                                <th>출처</th>
                                <th>아이디</th>
                                <th>이름</th>
                                <th>아이엠링크</th>
                                <th>대표<br>이미지</th>
                                <th>폰번호</th>
                                <th><a href="?orderField=row_cn&dir=<?=$dir?>" class="sort-by">컨텐츠개수</th>
                                <th>상호명</th>
                                <th>주소</th>
                                <th>홈피주소</th>
                                <th>신청링크</th>
                                <th>추천인</th>
                                <th>신청</th>
                                <th>수정/삭제</th>
                                <th>등록일</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?
                            $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                            $startPage = $nowPage?$nowPage:1;
                            $pageCnt = 20;

                            // 검색 조건을 적용한다.

                            $order = $order?$order:"desc";
                            
                            $searchStr .= $search_key ? " AND (memid LIKE '%".$search_key."%' or mem_name like '%".$search_key."%'  )" : null;

                            $query = "
                              SELECT 
                                  SQL_CALC_FOUND_ROWS 
                                  a.*
                              FROM Gn_Iam_automem a
                              WHERE 1=1 
                              $searchStr
                              ";

                            $res	    = mysql_query($query);
                            $totalCnt	=  mysql_num_rows($res);

                            $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                            $number			= $totalCnt - ($nowPage - 1) * $pageCnt;
                            $orderQuery .= "
                          ORDER BY no DESC
                          $limitStr
                        ";

                            $i = 1;
                            $c=0;
                            $query .= "$orderQuery";
                            $res = mysql_query($query);
                            while($row = mysql_fetch_array($res)) {
                                $sql_f="select * from Gn_Iam_friends where mem_id='{$row['mem_id']}' ";
                                $resul_f=mysql_query($sql_f);
                                $row_f=mysql_fetch_array($resul_f);

                                $sql_c="select * from Gn_Iam_contents where mem_id='{$row['mem_id']}' ";
                                $resul_c=mysql_query($sql_c);
                                $row_c=mysql_fetch_array($resul_c);
                                
                                $sql_c="select count(*) cnt from Gn_Iam_Contents where mem_id='{$row['memid']}' ";
                                $resul_c=mysql_query($sql_c);
                                $row_cn=mysql_fetch_array($resul_c);                                

                                if($i == 1){
                                    $sql_tmp="update Gn_Iam_Name_Card set sample_click = 'Y' where mem_id='{$_SESSION['one_member_id']}' ";
                                    $resul_tmp=mysql_query($sql_tmp);
                                }

                                $mem_sql = "select mem_code from Gn_Member where mem_id='{$row['mem_id']}'";
                                $res_mem = mysql_query($mem_sql);
                                $row_mem = mysql_fetch_array($res_mem);
                                ?>
                                <tr>
                                    <th><?=$number--?></th>
                                    <th><?=$row['db_source']?></th>
                                    <th><?=$row['memid']?></th>
                                    <th><?=$row['mem_name']?></th>
                                    <th>
                                       <div style="overflow-x:hidden;width:100px;">
                                       <a href="<?=$row['iam_making'].$row_mem['mem_code']?>" target="_blank"><?=$row['iam_making'].$row_mem['mem_code']?></a> </div>
                                    </th>
                                    <th>
                                        <div style="">
                                            <?
                                            if($row[image1]){
                                                $thumb_img =  $row[image1];
                                            }else{
                                                //$thumb_img =  $default_img;
                                            }
                                            ?>

                                            <a href="<?=$thumb_img?>" target="_blank">

                                                <img class="zoom" src="<?=$thumb_img?>" style="width:50px;">
                                            </a>
                                        </div>
                                    </th>
                                    <th><?=$row['profile_telno']?></th>
                                    <th><?=number_format($row_cn[0])?></th>
                                    <th><?=$row['profile_company']?></th>
                                    <th>
                                        <div style="overflow:hidden;width:100px;">
                                            <?=$row['profile_address']?></a>
                                        </div>
                                    </th>

                                    <th>
                                        <div style="overflow-x:hidden;width:100px;">
                                            <a href="<?=strstr("http" ,$row['profile_homepage'])?$row['profile_homepage']:"https:".$row['profile_homepage']?>" target="_blank"><?=$row['profile_homepage']?></a> </div>
                                    </th>
                                    <th>
                                        <div style="overflow-x:hidden;width:100px;">
                                            <a href="javascript:iam_making_db('<?=$row['memid']?>')"><?=$row['apply_link']?></a>
                                        </div>
                                    </th>
                                    <th><?=$row['recommend_id']?></th>
                                    <th><?=$row['iam_apply']==0?'N':'Y'?></th>
                                    <td>
                                        <a href="iam_auto_change.php?memid=<?=$row['memid']?>">수정</a> /
                                        <a href="javascript:del_making_db('<?=$row['memid']?>')">삭제</a>
                                    </td>
                                    <th><?=$row['reg_date']?></th>

                                </tr>
                                <?
                                $c++;
                                $i++;
                            }
                            if($i == 1) {
                                ?>
                                <tr>
                                    <td colspan="10" style="text-align:center;background:#fff">
                                        등록된 내용이 없습니다.
                                    </td>
                                </tr>
                                <?
                            }
                            ?>
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
    function changeLevel(mem_code) {
        var mem_leb = $('#mem_leb'+mem_code+" option:selected").val();
        var data = {"mode":"change","mem_code":"'+mem_code+'","mem_leb":"'+mem_leb+'"};
        $.ajax({
            type:"POST",
            url:"/admin/ajax/user_level_change.php",
            dataType:"json",
            data:{mode:"change",mem_code:mem_code,mem_leb:mem_leb},
            success:function(data){
                alert('변경이 완료되었습니다.');
            },
            error: function(){
                alert('초기화 실패');
            }
        });
    }

    function loginGo(mem_id, mem_pw, mem_code) {
        $('#one_id').val(mem_id);
        $('#mem_pass').val(mem_pw);
        $('#mem_code').val(mem_code);
        $('#login_form').submit();
    }

    function resetRow(cmid) {
        if(confirm('초기화 하시겠습니까?')) {
            $.ajax({
                type:"POST",
                url:"/admin/ajax/crawler_user_change.php",
                dataType:"json",
                data:{mode:"reset",cmid:cmid},
                success:function(data){
                    alert('초기화 되었습니다.되었습니다.');
                },
                error: function(){
                    alert('초기화 실패');
                }
            });
        }
    }
    setInterval(function(){
        if($('#btn_get').prop("disabled") ==  true) {
            $.ajax({
                type: "POST",
                url: "get_iam_crawling_status.php",
                dataType: "json",
                success: function (data) {
                    console.log(data.status);
                    if (data.status == 0) {
                       location.reload();
                    }
                },
                error: function () {
                    //alert('초기화 실패');
                }
            });
        }
    }, 1000);
</script>
<!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      