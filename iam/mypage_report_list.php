<?php
include "inc/header.inc.php";
if ($_SESSION['iam_member_id'] == "") {
    echo "<script>location='/';</script>";
}
if(isset($_GET["for_report"])){
    echo "<script>alert('신청되었습니다.');</script>";
}
$sql = "select * from gn_report_form where status = 1 order by id desc";
$form_res = mysqli_query($self_con,$sql);
?>
<style>
    input[type=checkbox] {
        font-family: '나눔고딕', 'Nanum Gothic', '맑은고딕', 'Malgun Gothic', 'gulim', 'arial', 'Dotum', 'AppleGothic', sans-serif;
        font-weight: 400;
        letter-spacing: -0.05em;
        vertical-align: middle;
    }

    th {
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

    td {
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
<link href='/css/main.css' rel='stylesheet' type='text/css' />
<link href='/css/responsive.css' rel='stylesheet' type='text/css' />
<main id="register" class="common-wrap" style=""><!-- 컨텐츠 영역 시작 -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="inner-wrap">
                    <h2 class="title"></h2>
                    <div class="mypage_menu">
                        <div style="display:flex;margin: 0px;">
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=shared_receive&modal=Y')" title="<?= $MENU['IAM_MENU']['M7_TITLE']; ?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">콘수신</p>
                                <label class="label label-sm" id="share_recv_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=shared_send&modal=Y')" title="<?= $MENU['IAM_MENU']['M8_TITLE']; ?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">콘전송</p>
                                <label class="label label-sm" id="share_send_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=unread_post')" title="<?= '댓글알림' ?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">댓글수신</p>
                                <label class="label label-sm" id="share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="/iam/mypage_post_lock.php" title="<?= '댓글알림' ?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">댓글차단해지</p>
                                <label class="label label-sm" id="share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=request_list')" title="<?= '신청알림' ?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">이벤트신청</p>
                                <label class="label label-sm" id="share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                        </div>
                        <div style="display:flex;margin: 0px 35px;float: right;">
                            <? if ($_SESSION['iam_member_subadmin_id'] == $_SESSION['iam_member_id']) { ?>
                                <a class="btn  btn-link" title="<?= '공지알림'; ?>" href="/?cur_win=unread_notice&box=send&modal=Y" style="display:flex;padding:6px 3px">
                                    <p style="font-size:14px;color:black">공지전송</p>
                                    <label class="label label-sm" id="notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                </a>
                                <a class="btn  btn-link" title="<?= '공지알림'; ?>" href="/?cur_win=unread_notice&modal=Y" style="display:flex;padding:6px 3px">
                                    <p style="font-size:14px;color:black">공지수신</p>
                                    <label class="label label-sm" id="notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                </a>
                            <? } else { ?>
                                <a class="btn  btn-link" title="<?= '공지알림'; ?>" href="javascript:iam_mystory('cur_win=unread_notice')" style="display:flex;padding:6px 3px">
                                    <p style="font-size:14px;color:black">공지</p>
                                    <label class="label label-sm" id="notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                </a>
                            <? } ?>
                            <? if ($is_pay_version) { ?>
                                <a class="btn  btn-link" title="" href="/iam/mypage_refer.php" style="display:flex;padding:6px 3px">
                                    <p style="font-size:14px;color:black">추천</p>
                                    <label class="label label-sm" id="sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                </a>
                                <a class="btn  btn-link" title="" href="/iam/mypage_payment.php" style="display:flex;padding:6px 3px">
                                    <p style="font-size:14px;color:black">결제</p>
                                    <label class="label label-sm" id="sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                </a>
                                <a class="btn  btn-link" title="" href="/iam/mypage_payment_item.php" style="display:flex;padding:6px 3px">
                                    <p style="font-size:14px;color:black">판매</p>
                                    <label class="label label-sm" id="sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                                </a>
                            <? } ?>
                            <? if ($member_iam['service_type'] < 2) {
                                $report_link = "/iam/mypage_report_list.php";
                            } else {
                                $report_link = "/iam/mypage_report.php";
                            }
                            ?>
                            <a class="btn  btn-link" title="" href="<?= $report_link ?>" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:#82c836">리포트</p>
                                <label class="label label-sm" id="sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                            <a class="btn  btn-link" title="" href="/?cur_win=unread_notice&req_provide=Y" style="display:flex;padding:6px 3px">
                                <p style="font-size:14px;color:black">공급사신청</p>
                                <label class="label label-sm" id="sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                            </a>
                        </div>
                    </div>
                    <br>
                    <form name="pay_form1" action="" method="post" class="my_pay" enctype="multipart/form-data" style="margin-top: 10px">
                        <div style="text-align: center;margin-top: 25px;">
                            <h2 class="title">신청내역보기</h2>
                        </div>
                        <br>
                        <div class="p1">
                            <? if ($member_iam['service_type'] >= 2) { ?>
                                <a href="mypage_report.php" style="background-color: #92d050;color: white;padding: 3px;border: 1px solid #92d050;padding: 6px 5px;cursor: pointer">등록내역</a>
                            <? } ?>
                        </div>
                        <div>
                            <table class="list_table" id="report_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <th style="width:10%;">번호</th>
                                    <th style="width:20%;">전송자이름/ID</th>
                                    <th style="width:20%;">리포트제목</th>
                                    <th style="width:20%;">미리보기<br>링크주소</th>
                                    <th style="width:20%;">제출일</th>
                                    <th style="width:10%;">답변</th>
                                </tr>
                                <?
                                $index = 1;
                                while ($form_row = mysqli_fetch_array($form_res)) {
                                    $sql = "select count(idx) from gn_report_table where userid = '{$_SESSION['iam_member_id']}' and repo_id={$form_row['id']}";
                                    $repo_res = mysqli_query($self_con,$sql);
                                    $repo_row = mysqli_fetch_array($repo_res);
                                    if ($repo_row[0] > 0) {
                                        $sql = "select mem_name from Gn_Member where mem_id = '{$form_row['user_id']}'";
                                        $mem_res = mysqli_query($self_con,$sql);
                                        $mem_row = mysqli_fetch_array($mem_res);
                                        $mem_name = $mem_row['mem_name'];
                                        $sql = "select idx from gn_report_table where userid = '{$_SESSION['iam_member_id']}' and repo_id={$form_row['id']}";
                                        $repo_res = mysqli_query($self_con,$sql);
                                        $repo_row = mysqli_fetch_array($repo_res);
                                ?>
                                        <tr>
                                            <td><?= $index ?></td>
                                            <td><?= $mem_name ?>/<?= $form_row['user_id'] ?></td>
                                            <td><a href="javascript:show_more('<?= str_replace("\n", "<br>", $form_row['title']) ?>')"><?= cut_str($form_row['title'], 10) ?></a></td>
                                            <?
                                            $pre_link = "report_preview.php?repo={$form_row['id']}";
                                            $link = $form_row['short_url'];
                                            ?>
                                            <td>
                                                <input type="button" value="미리보기" class="button" onclick="previewReport('<?= $pre_link ?>')">
                                                <br>
                                                <input type="button" value="링크복사" class="button copyLinkBtn" data-link="<?= $link ?>">
                                            </td>
                                            <td><?= $form_row['reg_date'] ?></td>
                                            <td><a href="report_view.php?repo=<?= $form_row['id'] ?>&idx=<?= $repo_row['idx'] ?>" target="_self">보기</a></td>
                                        </tr>
                                <?
                                        $index++;
                                    }
                                } ?>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main><!-- // 컨텐츠 영역 끝 -->
<div id="ajax_div" style="display:none"></div>
<div id="show_detail_more" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="border:none;background-color: rgb(130,199,54)">
                <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -12px;cursor:pointer;">X</a>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;box-shadow: none;width: 100%;">
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
        $('.copyLinkBtn').bind("click", function() {
            var url = $(this).data("link");
            url += '%26cache=' + Math.floor(Math.random() * 4);
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
    });

    function previewReport(str) {
        window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
    }

    function show_more(str) {
        $("#contents_detail").html(str);
        $("#show_detail_more").modal("show");
    }
</script>