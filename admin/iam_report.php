<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header.inc.php";
extract($_GET);
// 오늘날짜
$date_today=date("Y-m-d");
$path="./";
?>
<script type="text/javascript" src="/jquery.lightbox_me.js"></script>
<script type="text/javascript" src="/js/rlatjd_admin.js"></script>
<script language="javascript" src="<?=$path?>js/rlatjd_fun.js?m=<?php echo time();?>"></script>
<script language="javascript" src="<?=$path?>js/rlatjd.js?m=<?php echo  time();?>"></script>
<script>
    function goPage(pgNum) {
        location.href = '?nowPage='+pgNum+'&search_start_date=<?=$search_start_date?>&search_end_date=<?=$search_end_date?>&search_key=<?=$search_key?>';
    }
    function post_delete(post_idx){
        if(confirm('댓글을 삭제하시겠습니까?')) {
            $.ajax({
                type:"POST",
                url:"/iam/ajax/add_post.php",
                dataType:"json",
                data:{
                    post_idx : post_idx,
                    mode : 'del'
                },
                success:function(data){
                    location.reload();
                }
            });
        }
    }
    function multi_post_delete(){
        if(confirm('댓글을 삭제하시겠습니까?')) {
            var post_idx = [];
            var index = 0;
            $('input[type=checkbox]').each(function(){
                if($(this).prop("checked")) {
                     post_idx[index++] = $(this).val();
                }
            });
            $.ajax({
                type:"POST",
                url:"/iam/ajax/add_post.php",
                dataType:"json",
                data:{
                    post_idx : post_idx,
                    mode : 'multi_del'
                },
                success:function(data){
                    location.reload();
                }
            });
        }
    }
    function addcomma(str) {
        str = String(str);
        return str.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, '$1,');
    }

    function uncomma(str) {
        str = String(str);
        return str.replace(/[^\d]+/g, '');
    }
    $(function(){
        $('#check_all').on("click",function(){
            $('input[type=checkbox]').prop("checked",$(this).prop("checked"));
        });

        $('.switch').on("change", function() {
            var formData = new FormData();
            var idx = $(this).find("input[type=checkbox]").attr('con_idx');
            var status = $(this).find("input[type=checkbox]").is(":checked")==true?"1":"0";
            var mem_id = $(this).find("input[type=checkbox]").attr('data');
            if(status == "1"){
                var url = "/iam/ajax/contents.proc.php";
                formData.append("post_type", "block_contents");
                formData.append("block_contents_id", idx);
                formData.append("mem_id", mem_id);
            }
            else{
                var url = "/iam/ajax/ajax.v1.php";
                formData.append("type", "remove_one_block_data");
                formData.append("block_type", '2');
                formData.append("block_idx", idx);
                formData.append("mem_id", mem_id);
            }
            $.ajax({
                type:"POST",
                url:url,
                data:formData,
                contentType: false,
                processData: false,
                success:function(data){
                    //alert('신청되었습니다.');location.reload();
                    location.reload();
                }
            })
        });
    })

    function set_answer(post_id, mem_id, phone){
        $("#post_id").val(post_id);
        $("#mem_id").val(mem_id);
        $("#reporter_phone").val(phone);
        $("#notice_send_modal_mem").modal('show');
    }

    function set_answer_desc(){
        var mem_id=$("#mem_id").val();
        var post_id=$("#post_id").val();
        var content=$("#answer_desc").val();
        var phone=$("#reporter_phone").val();

        if(!content){
            alert('신고 답변을 입력해주세요.');
            return;
        }
        $.ajax({
            type:"POST",
            url:'/iam/ajax/report.proc.php',
            // dataType:'json',
            data:{
                type:'reg_post_response',
                mem_id:mem_id,
                post_id:post_id,
                content:content,
                phone:phone
            },
            success:function(data){
                alert('저장되었습니다.');
                // location.reload();
            },
            error: function(data){
				alert(data);
            }
        })
    }
    $(function() {
        var contHeaderH = $(".main-header").height();
        var navH = $(".navbar").height();
        if(navH != contHeaderH)
            contHeaderH += navH - 50;
        $(".content-wrapper").css("margin-top",contHeaderH);
        var height = window.outerHeight - contHeaderH - $(".content-header").height() - $("#toolbox").height() -$("#list_paginate").height()- 196;
        if(height < 375)
            height = 375;
        $(".box-body").css("height",height);
    });
</script>
<style>
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    input, select, textarea {
        vertical-align: middle;
        border: 1px solid #CCC;
    }
    user agent stylesheet
    input[type="checkbox" i] {
        background-color: initial;
        cursor: default;
        -webkit-appearance: checkbox;
        box-sizing: border-box;
        margin: 3px 3px 3px 4px;
        padding: initial;
        border: initial;
    }
    input:checked + .slider {
        background-color: #2196F3;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }
    input:checked + .slider {
        background-color: #2196F3;
    }
    .slider.round {
        border-radius: 34px;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }
    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }
    .slider.round:before {
        border-radius: 50%;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }
    thead tr th{position: sticky; top: 0; background: #ebeaea;z-index:10;}
    .wrapper{height:100%;overflow:auto;}
    .content-wrapper{min-height : 80% !important;}
    .box-body{overflow:auto;padding:0px !important}
</style>
<div class="wrapper">
    <!-- Top 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_header_menu.inc.php";?>
    <!-- Left 메뉴 -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_left_menu.inc.php";?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>콘텐츠신고관리<small>신고를 관리합니다.</small></h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">콘텐츠신고관리</li>
            </ol>
        </section>
        <form name="login_form" id="login_form" action="/admin/ajax/login_user.php" method="post" target="_blank">
            <input type="hidden" name="one_id"   id="one_id"   value="<?=$data['mem_id']?>" />
            <input type="hidden" name="mem_pass" id="mem_pass" value="<?=$data['web_pwd']?>" />
            <input type="hidden" name="mem_code" id="mem_code" value="<?=$data['mem_code']?>" />
        </form>
        <!-- Main content -->
        <section class="content">
            <div class="row" id="toolbox">
                <div class="col-xs-12" style="padding-bottom:20px">
                    <form method="get" name="search_form" id="search_form " class="form-inline">
                        <div class="box-tools">
                            <div class="input-group"  >
                                <div class="form-group">
                                    <select name="search_type" style="height: 30px">
                                        <option value="">전체</option>
                                        <option value="mem_id" <?=$_REQUEST['search_type']=='mem_id'?"selected":"";?>>아이디</option>
                                        <option value="reporter_phone" <?=$_REQUEST['search_type']=='reporter_phone'?"selected":"";?>>휴대폰</option>
                                        <option value="content" <?=$_REQUEST['search_type']=='content'?"selected":"";?>>내용</option>
                                    </select>
                                    <input type="date" style="height: 30px" name="search_start_date" placeholder="" id="search_start_date" value="<?=$_REQUEST['search_start_date']?>"/> ~
                                    <input type="date" style="height: 30px" name="search_end_date" placeholder="" id="search_end_date" value="<?=$_REQUEST['search_end_date']?>"/>
                                </div>
                                <div class="form-group">
                                    <input type="text" style="margin-left:5px" name="search_key" id="search_key" class="form-control input-sm pull-right" value="<?=$_REQUEST['search_key']?>">
                                </div>
                                <div class="input-group-btn">
                                    <button style="margin-left:5px" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
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
                                <col width="7%">
                                <col width="5%">
                                <col width="5%">
                                <col width="8%">
                                <col width="5%">
                                <col width="5%">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                                <col width="5%">
                                <col width="10%">
                                <col width="10%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="check_all" id = "check_all" value="">번호</th>
                                    <th>신고자<br>ID/IP</th>
                                    <th>신고자<br>유저이름</th>
                                    <th>신고자<br>휴대폰</th>
                                    <th>게시자<br>ID/이름</th>
                                    <th>게시자<br>폰번호</th>
                                    <th>게시자<br>콘링크</th>
                                    <th>콘텐츠<br>신고일시</th>
                                    <th>콘텐츠<br>신고항목</th>
                                    <th>콘텐츠<br>신고내용</th>
                                    <th>콘텐츠<br>감춤여부</th>
                                    <th>콘텐츠<br>신고답변</th>
                                    <th>콘텐츠<br>답변일시</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                $startPage = $nowPage?$nowPage:1;
                                $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                $searchStr = "";
                                if($search_key) {
                                    if ($search_type == "") {
                                        $searchStr .= " AND (mem_id like '%" . $search_key . "%' or reporter_phone like '%" . $search_key . "%' or content like '%" . $search_key . "%') ";
                                    }else{
                                        $searchStr .= " AND ".$search_type." LIKE '%" . $search_key . "%' ";
                                    }
                                }
                                if($search_start_date && $search_end_date)
                                    $searchStr .= " AND reg_date >= '$search_start_date' and reg_date <= '$search_end_date'";
                                $order = $order?$order:"desc";
                                $query = "SELECT SQL_CALC_FOUND_ROWS p.*, c.mem_id as con_mem_id, c.contents_title from Gn_Iam_Post p inner join Gn_Iam_Contents c on p.content_idx=c.idx where p.type=1 and p.lock_status='N' $searchStr";
                                $excel_sql=$query;
                                $excel_sql=str_replace("'","`",$excel_sql);
                                $res	    = mysqli_query($self_con,$query);
                                $totalCnt	=  mysqli_num_rows($res);
                                $limitStr       = " LIMIT ".(($startPage-1)*$pageCnt).", ".$pageCnt;
                                $number			= $totalCnt - ($nowPage - 1) * $pageCnt;
                                $orderQuery .= " ORDER BY reg_date DESC $limitStr ";
                                $i = 0;
                                $query .= $orderQuery;
                                $res = mysqli_query($self_con,$query);
                                while($row = mysqli_fetch_array($res)) {
                                    $sql_mem = "select mem_name from Gn_Member where mem_id='{$row['mem_id']}'";
                                    $res_mem = mysqli_query($self_con,$sql_mem);
                                    $row_mem = mysqli_fetch_array($res_mem);

                                    $sql_con_mem = "select mem_name, mem_phone from Gn_Member where mem_id='{$row['con_mem_id']}'";
                                    $res_con_mem = mysqli_query($self_con,$sql_con_mem);
                                    $row_con_mem = mysqli_fetch_array($res_con_mem);

                                    $sql_hide_state = "select count(*) as cnt from Gn_Iam_Info where mem_id='{$row['mem_id']}' and block_contents like '%{$row['content_idx']}%'";
                                    $res_hide_state = mysqli_query($self_con,$sql_hide_state);
                                    $row_hide_state = mysqli_fetch_array($res_hide_state);

                                    $sql_response = "select * from Gn_Iam_Post_Response where post_idx='{$row['id']}' and type=1";
                                    $res_response = mysqli_query($self_con,$sql_response);
                                    $row_response = mysqli_fetch_array($res_response);

                                    // $con_link = "https://kiam.kr/iam/contents.php?contents_idx=".$row['content_idx'];
                                ?>
                                    <tr>
                                        <td><input type="checkbox" name="<?='post_'.$row['id']?>" id = "<?='post_'.$row['id']?>" value="<?=$row['id']?>"><?=$number--?></td>
                                        <td><?=$row['mem_id']?></td>    
                                        <td><?=$row_mem['mem_name']?></td>
                                        <td><?=str_replace("-", "",$row['reporter_phone'])?></td>
                                        <td><?=$row['con_mem_id']?>/<?=$row_con_mem['mem_name']?></td>
                                        <td><?=str_replace("-", "",$row_con_mem['mem_phone'])?></td>
                                        <td><a href = "<?='/iam/contents.php?contents_idx='.$row['content_idx']?>" target = "_blank"><?=$row['contents_title']?></a></td>
                                        <td><?=$row['reg_date']?></td>
                                        <td><?=$row['title']?></td>
                                        <td>
                                            <a href="javascript:void(0)" onclick="show_recv('show_content','<?=$i?>','댓글내용')"><?=str_substr($row['content'],0,30,'utf-8')?></a>
                                            <input type="hidden" name="show_content" value="<?=$row['content']?>"/>
                                        </td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" name="status" id="stauts_<?php echo $row['id'];?>" value="<?php echo $row['id'];?>" data="<?php echo $row['mem_id'];?>" con_idx="<?php echo $row['content_idx'];?>" <?php echo $row_hide_state['cnt']=="1"?"checked":""?>>
                                                <span class="slider round" name="status_round" id="stauts_round_<?php echo $row['id'];?>"></span>
                                            </label>
                                        </td>
                                        <td>
                                            <?if($row_response['contents'] != ''){?>
                                            <a href="javascript:void(0)" onclick="show_recv('show_content1','<?=$i?>','댓글내용')"><?=str_substr($row_response['contents'],0,30,'utf-8')?></a>
                                            <input type="hidden" name="show_content1" value="<?=$row_response['contents']?>"/>
                                            <?}
                                            else{?>
                                            <a href="javascript:set_answer('<?=$row['id']?>', '<?=$row['mem_id'];?>', '<?=str_replace("-", "",$row['reporter_phone'])?>')">신고답변</a>
                                            <?}?>
                                        </td>
                                        <td>
                                            <?=$row_response['reg_date']?>
                                        </td>
                                    </tr>
                                <?$i++;
                                }
                                if($i == 0) {?>
                                    <tr>
                                        <td colspan="12" style="text-align:center;background:#fff">등록된 내용이 없습니다.</td>
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
            </div>
        </section><!-- /.content -->
    </div><!-- /content-wrapper -->
    <!-- Footer -->
    <?include_once $_SERVER['DOCUMENT_ROOT']."/admin/include/admin_footer.inc.php";?>
    <form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
    </form>
    <iframe name="excel_iframe" style="display:none;"></iframe>
    <!-- // 공지사항 전송 팝업 -->
    <div id="notice_send_modal_mem" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:768px;">
            <div class="modal-content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <img src="/iam/img/menu/icon_close.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
                    </button>
                </div>
                <div class="modal-header" style="background:#f5f5f5;border:none">
                    <textarea id="answer_desc" style="width: 100%;border: 1px solid #b5b5b5;height: 100px;margin-bottom: 15px;" placeholder="신고자에 대한 답변내용 입력하기"></textarea>
                    <input type="hidden" name="post_id" id="post_id">
                    <input type="hidden" name="mem_id" id="mem_id">
                    <input type="hidden" name="reporter_phone" id="reporter_phone">
                </div>
                <div class="modal-footer" style="text-align: center;display:flex;padding:0px;">
                    <button type="button" style="width:100%;background:#c6c8cb;color:#6e6d6d;padding:10px 0px;border:none;"  onclick="set_answer_desc()">콘텐츠 신고 답변 저장하기</button>
                </div>
            </div>
        </div>
    </div>
</div><!--wrapper-->
<div id='open_recv_div' class="open_1">
    <div class="open_2" onmousedown="down_notice(open_recv_div,event)" onmousemove="move(event)" onmouseup="up()">
        <li class="open_recv_title open_2_1"></li>
        <li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_recv_div)"><img src="/images/div_pop_01.jpg" /></a></li>
    </div>
    <div class="open_recv open_3" style="width:300px;overflow:auto;word-break:break-all;">

    </div>
</div>

