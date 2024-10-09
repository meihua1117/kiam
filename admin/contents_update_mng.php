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
        location.href = '?nowPage='+pgNum+'&search_start_date=<?=$search_start_date?>&search_end_date=<?=$search_end_date?>&settle_type=<?=$settle_type?>&search_key=<?=$search_key?>';
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
                콘텐츠UP 관리
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">콘텐츠UP 관리</li>
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
                                    <div class="input-group" style="display:inline-flex;">
                                        <input type="date" placeholder="시작일" name="search_start_date" value="<?=$_REQUEST[search_start_date]?>" style="margin-right:5px;border: 1px solid black;width:125px;">
                                        <input type="date" placeholder="종료일" name="search_end_date" value="<?=$_REQUEST[search_end_date]?>" style="margin-right:5px;border: 1px solid black;width:125px;">
                                        <select title="" name="settle_type" data-plugin-selectTwo onchange="" style="width:90px;margin-right:5px;">
                                            <option value="">전체</option>
                                            <option value="1" <?if($_REQUEST['settle_type'] == "1") echo "selected"?>>신청</option>
                                            <option value="2" <?if($_REQUEST['settle_type'] == "2") echo "selected"?>>결제</option>
                                            <option value="3" <?if($_REQUEST['settle_type'] == "3") echo "selected"?>>중지</option>
                                        </select>
                                        <input type="text" name="search_key" id="search_key" style="width:150px" class="form-control input-sm pull-right" placeholder="아이디">
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
                                <col width="150px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                                <col width="100px">
                            </colgroup>
                            <thead>
                            <tr>
                            <th>NO</th>
                            <th>아이디</th>
                            <th>회원이름</th>
                            <th>일시</th>
                            <th>구분</th>
                            <th>카드번호/제목</th>
                            <th>수집분야</th>
                            <th>결제횟수</th>
                            <th>결제포인트</th>
                            <th>누적포인트</th>
                            <th>잔여포인트</th>
                            </tr>
                            </thead>
                            <tbody id="iam_body">
                            <?php

                            $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                            $startPage = $nowPage?$nowPage:1;
                            $pageCnt = 20;

                            $searchStr .= $search_key ? " AND a.mem_id LIKE '%".$search_key."%'" : null;

                            if($search_start_date && $search_end_date)
                                    $searchStr .= " AND a.reg_date >= '$search_start_date' and a.reg_date <= '$search_end_date'";
                            if($settle_type)
                                $searchStr .= " AND a.state='{$settle_type}'";

                            $sql = "select a.*, b.card_title, c.mem_name, c.mem_point from contents_update_history a inner join Gn_Iam_Name_Card b on a.card_idx=b.idx inner join Gn_Member c on a.mem_id=c.mem_id where 1=1";
                            $res = mysql_query($sql);
                            $totalCnt = mysql_num_rows($res);

                            $limitStr = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                            $number = $totalCnt - ($nowPage - 1) * $pageCnt;
                            $orderQuery .= " ORDER BY id $limitStr ";
                            // $no = 0;
                            
                            $query = $sql.$searchStr.$orderQuery;
                            // echo $query; exit;
                            $result = mysql_query($query);
                            while($row = mysql_fetch_array($result)){
                                $no++;
                                switch ($row['web_type']){
                                    case 'peopleid':
                                        $web_type_con = "인물";
                                        break;
                                    case 'mapid':
                                        $web_type_con = "지도";
                                        break;
                                    case 'gmarketid':
                                        $web_type_con = "지마켓";
                                        break;
                                    case 'blogid':
                                        $web_type_con = "블로그";
                                        break;
                                    case 'youtubeid':
                                        $web_type_con = "유투브";
                                        break;
                                    default:
                                        $web_type_con = " ";
                                        break;
                                }
                                if($row['state'] == 1){
                                    $state = "신청";
                                }
                                else if($row['state'] == 2){
                                    $state = "결제";
                                }
                                else if($row['state'] == 3){
                                    $state = "중지";
                                }
                            ?>
                            <tr>
                                <td><?=$row['id']?></td>
                                <td><?=$row['mem_id']?></td>
                                <td><?=$row['mem_name']?></td>
                                <td><?=$row['reg_date']?></td>
                                <td><?=$state?></td>
                                <td><?=$row['card_idx']?>/<?=$row['card_title']?></td>
                                <td><?=$web_type_con?></td>
                                <td><?=$row['settle_cnt']?>회</td>
                                <td><?=$row['point']?></td>
                                <td><?=$row['used_point']?></td>
                                <td><?=$row['mem_point']?></td>
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
                    <?=drawPagingAdminNavi($totalCnt, $nowPage);?>
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
</script>
<!-- Footer -->
<?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>      