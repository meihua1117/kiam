<?php 
include "inc/header.inc.php";
if($_SESSION[iam_member_id] == "") {
    echo "<script>location='/';</script>";
}
if($member_iam[service_type] < 2){
    echo "<script>alert('리포트추가 권한이 없습니다.');location='/';</script>";
}
$sql_serch=" user_id ='$_SESSION[iam_member_id]' ";
if($_REQUEST[sday]){
    $sql_serch.=" and reg_date >= '{$_REQUEST[sday]}' ";
}
if($_REQUEST[eday]){
    $sql_serch.=" and reg_date <= '{$_REQUEST[eday]}' ";
}
$report_sql = "select * from gn_report_form where $sql_serch order by id desc";
$report_res = mysqli_query($self_con,$report_sql);
?>
<style>
input[type=checkbox] {
    font-family: '나눔고딕','Nanum Gothic','맑은고딕','Malgun Gothic','gulim','arial', 'Dotum', 'AppleGothic', sans-serif;
    font-weight: 400;
    letter-spacing: -0.05em;
    vertical-align: middle;
}
th{
    padding: 8px 2px !important;
    line-height: 1em;
    font-weight: 600;
    background: #cccccc;
    border-top: 1px solid #e4e5e7;
    text-align: center;
    border-left: 1px solid #e4e5e7;
    border-bottom: 1px solid #e4e5e7;
    vertical-align: middle;
}
td{
    padding: 8px 2px !important;
    line-height: 1em;
    background: #f1f1f1;
    border-top: 1px solid #e4e5e7;
    text-align: center;
    border-left: 1px solid #e4e5e7;
    vertical-align: middle;
    border-bottom: 1px solid #e4e5e7;
}
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
</style>
<link href='/css/main.css' rel='stylesheet' type='text/css'/>
<link href='/css/responsive.css' rel='stylesheet' type='text/css'/>
<main id="register" class="common-wrap" style=""><!-- 컨텐츠 영역 시작 -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="inner-wrap">
                    <h2 class="title"></h2>
                    <div class="mypage_menu">
                        <div style="display:flex;margin: 0px;">
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=shared_receive&modal=Y')" title = "<?=$MENU['IAM_MENU']['M7_TITLE'];?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">콘수신</p>
                                <label class="label label-sm" id = "share_recv_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=shared_send&modal=Y')" title = "<?=$MENU['IAM_MENU']['M8_TITLE'];?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">콘전송</p>
                                <label class="label label-sm" id = "share_send_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=unread_post')" title = "<?='댓글알림'?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">댓글수신</p>
                                <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="/iam/mypage_post_lock.php" title = "<?='댓글알림'?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">댓글차단해지</p>
                                <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=request_list')" title = "<?='신청알림'?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">이벤트신청</p>
                                <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                        </div>
                        <div style="display:flex;margin: 0px 35px;float: right;">
                            <?if($_SESSION[iam_member_subadmin_id] == $_SESSION[iam_member_id]){?>
                                <a class="btn  btn-link" title = "<?='공지알림';?>" href="/?cur_win=unread_notice&box=send&modal=Y" style="display:flex;padding:6px 3px">
                                    <p style="font-size:14px;color:black">공지전송</p>
                                    <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                </a>
                                <a class="btn  btn-link" title = "<?='공지알림';?>" href="/?cur_win=unread_notice&modal=Y" style="display:flex;padding:6px 3px">
                                    <p style="font-size:14px;color:black">공지수신</p>
                                    <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                </a>
                            <?}else{?>
                                <a class="btn  btn-link" title = "<?='공지알림';?>" href="javascript:iam_mystory('cur_win=unread_notice')" style="display:flex;padding:6px 3px">
                                    <p style="font-size:14px;color:black">공지</p>
                                    <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                </a>
                            <?}?>
                            <?if($is_pay_version){?>
                                <a class="btn  btn-link" title = "" href="/iam/mypage_refer.php" style="display:flex;padding:6px 3px">
                                    <p style="font-size:14px;color:black">추천</p>
                                    <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                </a>
                                <a class="btn  btn-link" title = "" href="/iam/mypage_payment.php" style="display:flex;padding:6px 3px">
                                    <p style="font-size:14px;color:black">결제</p>
                                    <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                </a>
                                <a class="btn  btn-link" title = "" href="/iam/mypage_payment_item.php" style="display:flex;padding:6px 3px">
                                    <p style="font-size:14px;color:black">판매</p>
                                    <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                </a>
                            <?}?>
                            <?if($member_iam[service_type] < 2){
                                $report_link = "/iam/mypage_report_list.php";
                            }else{
                                $report_link = "/iam/mypage_report.php";
                            }
                            ?>
                            <a class="btn  btn-link" title = "" href="<?=$report_link?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:#99cc00">리포트</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" title = "" href="/?cur_win=unread_notice&req_provide=Y" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공급사신청</p>
                                <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                        </div>
                    </div>
                    <br>
                    <form name="pay_form1" action="" method="post" class="my_pay" enctype="multipart/form-data" style="margin-top: 10px">
                        <div style="text-align: center;margin-top: 25px;">
                            <h2 class="title">사업자 리포트정보</h2>
                        </div>
                        <br>
                        <div class="p1">
                            <input type="date" name="sday" value="<?=$_REQUEST[sday]?>"/> ~
                            <input type="date" name="eday" value="<?=$_REQUEST[eday]?>"/>
                            <a onclick="pay_form1.submit();"><img src="/images/sub_mypage_11.jpg" /></a>
                            <a style="background-color: white;color: black;padding: 3px;border: 1px solid #000000;padding: 6px 5px;cursor: pointer" onclick="cloneMultiRow();">복제하기</a>
                            <a style="background-color: white;color: black;padding: 3px;border: 1px solid #000000;padding: 6px 5px;cursor: pointer" onclick="deleteMultiRow();">선택삭제</a>
                            <a style="background-color: white;color: black;padding: 3px;border: 1px solid #000000;padding: 6px 5px;cursor: pointer" onclick="sendReport();">전송하기</a>
                            <a href="mypage_report_reg.php" style="background-color: white;color: black;padding: 3px;border: 1px solid #000000;padding: 6px 5px;cursor: pointer">새로등록</a>
                            <a href="mypage_report_list.php" style="background-color: #92d050;color: white;padding: 3px;border: 1px solid #92d050;padding: 6px 5px;cursor: pointer">제출내역</a>
                        </div>
                        <div>
                            <table class="list_table" id="report_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <th scope="col"  style="width:4%;">
                                        <input type="checkbox" class="check" id="check_all" value="0">
                                    </th>
                                    <th style="width:5%;">번호</th>
                                    <th style="width:20%;">타이틀/설명글</th>
                                    <th style="width:25%;">상세글</th>
                                    <th style="width:10%;">조회/응답</th>
                                    <th style="width:10%;">등록일</th>
                                    <th style="width:5%;">답변</th>
                                    <th style="width:10%;">관리</th>
                                    <th style="width:5%;">상태</th>
                                    <th style="width:10%;">미리보기</th>
                                </tr>
                                <?
                                $index = 1;
                                while($report_row = mysqli_fetch_array($report_res)){?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="check" id="check_one" value="<?=$report_row[id]?>">
                                    </td>
                                    <td><?=$index?></td>
                                    <td>
                                        <a href="javascript:show_more('<?='title'.$index?>')"><?=cut_str($report_row['title'], 10)?></a>
                                        <input type="hidden" id=<?='title'.$index?> value="<?=htmlspecialchars($report_row[title])?>">
                                        <br>
                                        <a href="javascript:show_more('<?='desc'.$index?>')"><?=cut_str($report_row['descript'], 10)?></a>
                                        <input type="hidden" id=<?='desc'.$index?> value="<?=htmlspecialchars($report_row[descript])?>">
                                    </td>
                                    <td style="padding-top:1px !important">
                                        <textarea id="<?='detail'.$report_row['id']?>" style="width:100%;"><?=$report_row['detail']?></textarea>
                                        <br>
                                        <button type="button" class="btn-default" style="margin-top:5px;padding:5px 10px;border:1px solid #ccc;cursor:pointer" onclick="save_detail(<?=$report_row['id']?>);">저장</button>
                                    </td>
                                    <?
                                    $sql = "select count(idx) from gn_report_table where repo_id=$report_row[id]";
                                    $res = mysqli_query($self_con,$sql);
                                    $row = mysqli_fetch_array($res);
                                    $count = $row[0];
                                    if($count == null)
                                        $count = 0;
                                    ?>
                                    <td><?=$report_row['visit']?>/<?=$count?></td>
                                    <td><?=$report_row['reg_date']?></td>
                                    <td><a href = "report_result.php?repo=<?=$report_row[id]?>" target="_blank">답변</a></td>
                                    <td>
                                        <?if($count==0){?>
                                        <a href="mypage_report_reg.php?index=<?=$report_row['id']?>">수정</a>
                                        <?}?>
                                    </td>
                                    <td><?=$report_row['status']==0?"비노출":"노출";?></td>
                                    <?
                                    $link_pre = "/iam/report_preview.php?repo=$report_row[id]";
                                    $link = $report_row['short_url'];
                                    ?>
                                    <td>
                                        <input type="button" value="미리보기" class="button" onclick="previewReport('<?=$link_pre?>')">
                                        <input type="button" value="링크복사" class="button copyLinkBtn" data-link="<?=$link?>">
                                    </td>
                                </tr>
                                <?
                                $index++;
                                }?>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main><!-- // 컨텐츠 영역 끝 -->
<div id="ajax_div" style="display:none"></div>
<div class="modal fade" id="send_report" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >
                    <img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
                <label style="font-size:18px;margin-top:10px">리포트포맷 전송하기</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;box-shadow: none;width: 100%;">
                    <table class="table table-bordered" style="width: 97%;">
                        <tbody>
                        <tr class="hide_spec">
                            <td class="bold" id="remain_count" data-num="" style="width:70px;padding:5px;">전송하기<br>
                                <textarea name="req_send_id_count" id="req_send_id_count" style="width:90%; height:50px;color: red;font-size: 12px" readonly="" data-num="0" placeholder="0건"></textarea></td>
                            <td colspan="2" style="padding:5px;">
                                <div>
                                    <textarea name="req_send_id" id="req_send_id" style="border: solid 1px #b5b5b5;width:97%; height:100px;" data-num="0" placeholder="전송할 친구 아이디를 입력하세요.<컴마로 구분>수신자가 리셀러이상 사업자여야 합니다."></textarea>
                                    <input type="hidden" name="send_report_idx" id="send_report_idx" value="">
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center">
                <button type="button" class="btn btn-secondary" id="btn_cancel" data-dismiss="modal" onclick="send_Cancel();">취소하기</button>
                <button type="button" class="btn btn-primary" id="btn_ok" onclick="send_Report()">전송하기</button>
            </div>
        </div>
    </div>
</div>
<div id="show_detail_more" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <!-- Modal content-->
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal">
                    <img src="/iam/img/menu/icon_close_white.png" style="" class="close" data-dismiss="modal">
                </button>
            </div>
            <div class = "modal-title" style="">
                <label style=""></label>
            </div>
            <div class="modal-body">
                <div class="container" style="box-shadow: none;width: 100%;">
                    <p style="font-size:16px;color:#6e6c6c" id="contents_detail">
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="javascript">
    $(function() {
        $(document).ajaxStop(function() {
            $("#ajax-loading").delay(10).hide(1);
        });
        $('.check').on("click",function(){
            if($(this).prop("id") == "check_all"){
                if($(this).prop("checked"))
                    $('.check').prop("checked",true);
                else
                    $('.check').prop("checked",false);
            }else if($(this).prop("id") == "check_one"){
                if(!$(this).prop("checked"))
                    $('#check_all').prop("checked",false);
            }
        });
        $('.copyLinkBtn').bind("click", function() {
            var url = $(this).data("link");
            url +='%26cache=' + Math.floor(Math.random() * 4);
            var aux1 = document.createElement("input");
            // 지정된 요소의 값을 할당 한다.
            aux1.setAttribute("value", url);
            // bdy에 추가한다.
            document.body.appendChild(aux1);
            // 지정된 내용을 강조한다.
            aux1.select();
            // 텍스트를 카피 하는 변수를 생성
            document.execCommand("copy");
            // body 로 부터 다시 반환 한다.
            document.body.removeChild(aux1);
            alert("URL이 복사되었습니다. 원하는 곳에 붙여 넣으세요.");
        });
        $("#req_send_id").keyup(function(){
            var point = $(this).val();
            var arr = point.split(",");
            var cnt = arr.length;
            if(point.indexOf(",") == -1 && point == ""){
                cnt = 0;
            }
            $("#req_send_id_count").val(cnt + "건");
            $('#req_send_id_count').data('num', cnt);
        });
    });
    function deleteMultiRow() {
        if(confirm('삭제하시겠습니까?')) {
            var check_array = $("#report_table").children().find(".check");
            var no_array = [];
            var index = 0;
            check_array.each(function(){
                if($(this).prop("checked") && $(this).val() > 0)
                    no_array[index++] = $(this).val();
            });
            if(index == 0){
                alert("삭제할 리포트를 선택해주세요.");
                return;
            }
            $.ajax({
                type:"POST",
                url:"/ajax/ajax.report.php",
                dataType:"json",
                data:{
                    method:"del",
                    index:no_array.toString()
                },
                success: function(data){
                    if(data.result == "success"){
                        alert('성공적으로 삭제 되었습니다.');
                        window.location.reload();
                    }
                }
            })
        }
    }
    function cloneMultiRow() {
        if(confirm('복제하시겠습니까?')) {
            var check_array = $("#report_table").children().find(".check");
            var no_array = [];
            var index = 0;
            check_array.each(function(){
                if($(this).prop("checked") && $(this).val() > 0)
                    no_array[index++] = $(this).val();
            });
            if(index == 0){
                alert("복제할 리포트를 선택해주세요.");
                return;
            }
            $.ajax({
                type:"POST",
                url:"/ajax/ajax.report.php",
                dataType:"json",
                data:{
                    method:"clone",
                    index:no_array.toString()
                },
                success: function(data){
                    if(data.result == "success"){
                        alert('성공적으로 복제 되었습니다.');
                        window.location.reload();
                    }
                }
            })
        }
    }
    function previewReport(str){
        window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
    }
    function sendReport() {
        var check_array = $("#report_table").children().find(".check");
        var no_array = [];
        var index = 0;
        check_array.each(function(){
            if($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });
        if(index == 0){
            alert("전송할 리포트를 선택해주세요.\r\n수신자는 리셀러이상 사업자여야 합니다.");
            return;
        }
        $("#send_report_idx").val(no_array.join(","));
        $("#send_report").modal("show");
    }
    function show_more(str){
        $("#contents_detail").html($("#"+str).val());
        $("#show_detail_more").modal("show");
    }
    function send_Cancel(){
        $("#send_report").modal("hide");
        $("#req_send_id_count").val("0건");
        $('#req_send_id').val("");
        $("#send_report_idx").val("");
    }
    function send_Report(){
        var send_ids = $("#req_send_id").val();
        var repo_idx = $("#send_report_idx").val();

        if(send_ids == ""){
            alert("아이디를 입력하세요.");
            $('#req_send_id').focus();
            return;
        }
        $.ajax({
            type:"POST",
            url:"/ajax/ajax.report.php",
            dataType:"json",
            data:{
                method:"send_report",
                send_ids:send_ids,
                repo_idx:repo_idx
            },
            success:function (data) {
                console.log(data);
                alert("전송되었습니다.");
                send_Cancel();
            }
        });
    }
    function save_detail(idx){
        $.ajax({
            type:"POST",
            url:"/ajax/ajax.report.php",
            dataType:"json",
            data:{
                method:"save_report_detail",
                id:idx,
                cont:$("#detail" + idx).val()
            },
            success:function (data) {
            }
        });
    }
</script>
