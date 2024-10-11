<?php
include "inc/header.inc.php";
if ($_SESSION['iam_member_id'] == "") {
    echo "<script>location='/';</script>";
}
if ($member_iam['service_type'] < 2) {
    echo "<script>alert('리포트추가 권한이 없습니다.');location='/';</script>";
}
$sql_serch = " user_id ='{$_SESSION['iam_member_id']}' ";
if ($_REQUEST['sday']) {
    $sql_serch .= " and reg_date >= '{$_REQUEST['sday']}' ";
}
if ($_REQUEST['eday']) {
    $sql_serch .= " and reg_date <= '{$_REQUEST['eday']}' ";
}
$report_sql = "select * from gn_report_form where $sql_serch order by id desc";
$report_res = mysqli_query($self_con,$report_sql);
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

    /* 스텝바 제거를 위한 CSS */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .num3 {
        width: 29% !important;
    }

    input[type=number],
    #channel {
        -moz-appearance: textfield;
        width: calc(87% + 33px);
        border-radius: 0px;
        height: 32px !important;
        /* Firefox에서 스텝바 제거 */
    }

    @media (max-width:600px) {
        .num3 {
            width: 27.4% !important;
        }

        input[type=number],
        #channel {
            width: calc(81% + 33px);
        }
    }

    .notice {
        border: 1px solid #000;
        padding: 0px 3px;
        border-radius: 30px;
        font-size: 10px;
        cursor: pointer;
    }
    .table_textarea{
        resize: none;
        overflow:hidden;
    }
</style>
<link href='/css/main.css' rel='stylesheet' type='text/css' />
<link href='/css/responsive.css' rel='stylesheet' type='text/css' />
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<main id="register" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
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
                                <p style="font-size:14px;color:#99cc00">리포트</p>
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
                            <h2 class="title">사업자 리포트정보</h2>
                        </div>
                        <br>
                        <div class="p1">
                            <input type="date" name="sday" value="<?= $_REQUEST['sday'] ?>" /> ~
                            <input type="date" name="eday" value="<?= $_REQUEST['eday'] ?>" />
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
                                    <th scope="col" style="width:4%;">
                                        <input type="checkbox" class="check" id="check_all" value="0">
                                    </th>
                                    <th style="width:5%;">번호</th>
                                    <th style="width:15%;">타이틀/설명글</th>
                                    <th style="width:18%;">광고상세정보</th>
                                    <th style="width:10%;">클릭/응답</th>
                                    <th style="width:10%;">등록일</th>
                                    <th style="width:5%;">응답</th>
                                    <th style="width:10%;">관리</th>
                                    <th style="width:5%;">상태</th>
                                    <th style="width:10%;">미리보기</th>
                                </tr>
                                <?
                                $index = 1;
                                while ($report_row = mysqli_fetch_array($report_res)) { ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="check" id="check_one" value="<?= $report_row['id'] ?>">
                                        </td>
                                        <td><?= $index ?></td>
                                        <td>
                                            <a href="javascript:show_more('<?= 'title' . $index ?>')"><?= cut_str($report_row['title'], 10) ?></a>
                                            <input type="hidden" id=<?= 'title' . $index ?> value="<?= htmlspecialchars($report_row['title']) ?>">
                                            <br>
                                            <a href="javascript:show_more('<?= 'desc' . $index ?>')"><?= cut_str($report_row['descript'], 10) ?></a>
                                            <input type="hidden" id=<?= 'desc' . $index ?> value="<?= htmlspecialchars($report_row['descript']) ?>">
                                        </td>
                                        <td style="padding-top:1px !important">
                                            <textarea id="<?= 'detail' . $report_row['id'] ?>" class = "table_textarea" style="width:100%;"><?= $report_row['detail'] ?></textarea>
                                            <br>
                                            <button type="button" class="btn-default" style="margin-top:5px;padding:5px 10px;border:1px solid #ccc;cursor:pointer" onclick="show_detail(<?= $report_row['id'] ?>);">상세보기</button>
                                            <button type="button" class="btn-default" style="margin-top:5px;padding:5px 10px;border:1px solid #ccc;cursor:pointer" onclick="save_detail_idx(<?= $report_row['id'] ?>);">저장</button>
                                        </td>
                                        <?
                                        $sql = "select count(idx) from gn_report_table where repo_id={$report_row['id']}";
                                        $res = mysqli_query($self_con,$sql);
                                        $row = mysqli_fetch_array($res);
                                        $count = $row[0];
                                        if ($count == null)
                                            $count = 0;
                                        ?>
                                        <td><?=(($report_row['display_count'] + $report_row['send_count']) > 0 ? $report_row['display_count'] + $report_row['send_count']." / ": ""). $report_row['visit']." / ". $count ?><br>
                                            <span id="<?= 'detail_' . $report_row['id'] ?>"><?= $report_row['channel'] ?></span><br><br>
                                    <?if($report_row['price_per_reply'] != 0){
                                        $style_detail = "cursor:pointer;background:blue;color:white;border:1px solid #ddd";
                                    }else{
                                        $style_detail = "cursor:pointer;background:white;border:1px solid #ddd";
                                    }?>
                                    <span id="<?='static_'.$report_row['id']?>" onclick="show_statistic(<?= $report_row['id'] ?>);" style="<?=$style_detail?>">상세보기</span>
                                        </td>
                                        <td><?= $report_row['reg_date'] ?></td>
                                        <td><a href="report_result.php?repo=<?= $report_row['id'] ?>" target="_blank">답변</a></td>
                                        <td>
                                            <? if ($count == 0) { ?>
                                                <a href="mypage_report_reg.php?index=<?= $report_row['id'] ?>">수정</a>
                                            <? } ?>
                                        </td>
                                        <td><?= $report_row['status'] == 0 ? "비노출" : "노출"; ?></td>
                                        <?
                                        $link_pre = "/iam/report_preview.php?repo={$report_row['id']}";
                                        $link = $report_row['short_url'];
                                        ?>
                                        <td>
                                            <input type="button" value="미리보기" class="button" onclick="previewReport('<?= $link_pre ?>')">
                                            <input type="button" value="링크복사" class="button copyLinkBtn" data-link="<?= $link ?>">
                                        </td>
                                    </tr>
                                <?
                                    $index++;
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
<div class="modal fade" id="send_report" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
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
                                    <textarea name="req_send_id_count" id="req_send_id_count" style="width:90%; height:50px;color: red;font-size: 12px" readonly="" data-num="0" placeholder="0건"></textarea>
                                </td>
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
                <button type="button" class="btn-left btn-default" id="btn_cancel" data-dismiss="modal" onclick="send_Cancel();">취소하기</button>
                <button type="button" class="btn-right btn-active" id="btn_ok" onclick="send_Report()">전송하기</button>
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
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal">
                </button>
            </div>
            <div class="modal-title">
                <label></label>
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
<div class="modal fade" id="report_statistic" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 50px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
                <label style="font-size:18px;margin-top:10px">통계정보 입력 및 결과보기</label>
                <input type="hidden" id="statistic_repo_id">
            </div>
            <div class="modal-body" style="padding:0px">
                <div class="container" style="box-shadow: none;width: 100%;padding:0px !important">
                    <table class="table table-bordered" style="width: 100%;margin-bottom:0px" id="statistic_content">
                        <tr>
                            <td class="bold" style="vertical-align: middle;padding:0px !important;width:25%">광고채널명칭</td>
                            <td style="padding:2px !important;text-align: left;">
                                <input type="text" id="channel" value="">&nbsp;&nbsp;&nbsp;&nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td class="bold" style="vertical-align: middle;padding:0px !important">전송/노출건수<span class="notice" onclick="show_notice('sn_count');">?</span></td>
                            <td style="padding:2px !important;text-align: left;">
                                <input type="number" class="num3" id="send_count" value="">&nbsp;+
                                <input type="number" class="num3" id="display_count" value="">&nbsp;=
                                <input type="number" class="num3" id="sn_count" value="" readonly style="background: #ddd;">&nbsp;건
                            </td>
                        </tr>
                        <tr>
                            <td class="bold" style="vertical-align: middle;padding:0px !important">링크클릭건수<span class="notice" onclick="show_notice('click_count');">?</span></td>
                            <td style="padding:2px !important;text-align: left;">
                                <input type="number" class="num3" id="click_count1" value="" readonly style="background: #ddd;">&nbsp;+
                                <input type="number" class="num3" id="click_count2" value="">&nbsp;=
                                <input type="number" class="num3" id="click_count" value="" readonly style="background: #ddd;">&nbsp;건
                            </td>
                        </tr>
                        <tr>
                            <td class="bold" style="vertical-align: middle;padding:0px !important">신청응답건수<span class="notice" onclick="show_notice('reply_count');">?</span></td>
                            <td style="padding:2px !important;text-align: left;">
                                <input type="number" class="num3" id="reply_count1" value="" readonly style="background: #ddd;">&nbsp;+
                                <input type="number" class="num3" id="reply_count2" value="">&nbsp;=
                                <input type="number" class="num3" id="reply_count" value="" readonly style="background: #ddd;">&nbsp;건
                            </td>
                        </tr>
                        <tr>
                            <td class="bold" style="vertical-align: middle;padding:0px !important">광고홍보비용<span class="notice" onclick="show_notice('ads_price');">?</span></td>
                            <td style="padding:2px !important;text-align: left;">
                                <input type="number" id="ads_price" value="">&nbsp;원
                            </td>
                        </tr>
                        <tr>
                            <td class="bold" style="vertical-align: middle;padding:0px !important">광고관리비용<span class="notice" onclick="show_notice('manage_price');">?</span></td>
                            <td style="padding:2px !important;text-align: left;">
                                <input type="number" id="manage_price" value="">&nbsp;원
                            </td>
                        </tr>
                        <tr>
                            <td class="bold" style="vertical-align: middle;padding:0px !important">응답건별단가<span class="notice" onclick="show_notice('price_per_reply');">?</span></td>
                            <td style="padding:2px !important;text-align: left;">
                                <input type="number" id="price_per_reply" value="">&nbsp;원
                            </td>
                        </tr>
                        <tr>
                            <td class="bold" style="vertical-align: middle;padding:0px !important">총매출금액<span class="notice" onclick="show_notice('total_payment');">?</span></td>
                            <td style="padding:2px !important;text-align: left;">
                                <input type="number" class="num3" id="total_reply_price" value="" readonly style="background: #ddd;">&nbsp;+
                                <input type="number" class="num3" id="sale_discount" value="">&nbsp;=
                                <input type="number" class="num3" id="total_payment" value="" readonly style="background: #ddd;">&nbsp;원
                            </td>
                        </tr>
                        <tr>
                            <td class="bold" style="vertical-align: middle;padding:0px !important">노출대비클릭율<span class="notice" onclick="show_notice('send_vs_click');">?</span></td>
                            <td style="padding:2px !important;text-align: left;">
                                <input type="number" id="send_vs_click" value="" readonly style="background: #ddd;">&nbsp;%
                            </td>
                        </tr>
                        <tr>
                            <td class="bold" style="vertical-align: middle;padding:0px !important">클릭대비응답율<span class="notice" onclick="show_notice('click_vs_reply');">?</span></td>
                            <td style="padding:2px !important;text-align: left;">
                                <input type="number" id="click_vs_reply" value="" readonly style="background: #ddd;">&nbsp;%
                            </td>
                        </tr>
                        <tr>
                            <td class="bold" style="vertical-align: middle;padding:0px !important">광고비당응답단가<span class="notice" onclick="show_notice('price_per_ads');">?</span></td>
                            <td style="padding:2px !important;text-align: left;">
                                <input type="number" id="price_per_ads" value="" readonly style="background: #ddd;">&nbsp;원
                            </td>
                        </tr>
                        <tr>
                            <td class="bold" style="vertical-align: middle;padding:0px !important">광고순수익금<span class="notice" onclick="show_notice('profit');">?</span></td>
                            <td style="padding:2px !important;text-align: left;">
                                <input type="number" id="profit" value="" readonly style="background: #ddd;">&nbsp;원
                            </td>
                        </tr>
                        <tr>
                            <td class="bold" style="vertical-align: middle;padding:0px !important">광고수익비율</td>
                            <td style="padding:2px !important;text-align: left;">
                                <input type="number" id="profit_ads" value="" readonly style="background: #ddd;">&nbsp;%
                            </td>
                        </tr>
                        <tr>
                            <td class="bold" style="vertical-align: middle;padding:0px !important">광고순수익비율<span class="notice" onclick="show_notice('profit_total_ads');">?</span></td>
                            <td style="padding:2px !important;text-align: left;">
                                <input type="number" id="profit_total_ads" value="" readonly style="background: #ddd;">&nbsp;%
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;" id="copy_check">
                <button type="button" class="btn-default btn-left" style="border-bottom-left-radius:0px !important" onclick="copy_to_img();">이미지복사</button>
                <button type="button" class="btn-active btn-right" style="border-bottom-right-radius:0px !important" onclick="copy_to_txt();">텍스트복사</button>
            </div>
            <div class="modal-footer" style="text-align: center">
                <button type="button" class="btn-active btn-left" onclick="show_copy_check();">복사하기</button>
                <button type="button" class="btn-default btn-right" onclick="save_statistic();">저장하기</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="report_detail_modal" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 50px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#99cc00;color:white">
                <label style="font-size:18px;margin-top:10px">광고상세정보</label>
                <input type="hidden" id="detail_repo_id">
            </div>
            <div class="modal-body" style="padding:0px">
                <div class="container" style="box-shadow: none;width: 100%;padding:0px !important">
                    <textarea style="width: 100%;height: 300px;overflow: auto;resize: none;border-radius: 0px;" id="report_detail"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center">
                <button type="button" class="btn-active btn-left" onclick="save_detail();">저장하기</button>
                <button type="button" class="btn-default btn-right" data-dismiss="modal">닫기</button>
            </div>
        </div>
    </div>
</div>
<div id="result"></div>
<script language="javascript">
    $(function() {
        $(document).ajaxStop(function() {
            $("#ajax-loading").delay(10).hide(1);
        });
        $('.check').on("click", function() {
            if ($(this).prop("id") == "check_all") {
                if ($(this).prop("checked"))
                    $('.check').prop("checked", true);
                else
                    $('.check').prop("checked", false);
            } else if ($(this).prop("id") == "check_one") {
                if (!$(this).prop("checked"))
                    $('#check_all').prop("checked", false);
            }
        });
        $("#price_per_reply").keyup(function() {
            var price_per_reply = Number($(this).val());
            var reply_count = Number($("#reply_count").val());
            $("#total_reply_price").val(price_per_reply * reply_count);
            var sale_discount = Number($("#sale_discount").val());
            var total_payment = price_per_reply * reply_count + sale_discount;
            $("#total_payment").val(total_payment);
            var ads_price = Number($("#ads_price").val());
            var manage_price = Number($("#manage_price").val());
            $("#profit").val(total_payment - ads_price - manage_price);
            var profit_ads = (total_payment - ads_price - manage_price) / ads_price * 100;
            if (ads_price == 0)
                profit_ads = 0;
            else
                profit_ads = profit_ads.toFixed(1);
            $("#profit_ads").val(profit_ads);

            var profit_total_ads = (total_payment - ads_price - manage_price) / (ads_price + manage_price) * 100;
            if ((ads_price + manage_price) == 0)
                profit_total_ads = 0;
            else
                profit_total_ads = profit_total_ads.toFixed(1);
            $("#profit_total_ads").val(profit_total_ads);
                    
        });
        $("#sale_discount").keyup(function() {
            var price_per_reply = Number($("#price_per_reply").val());
            var reply_count = Number($("#reply_count").val());
            $("#total_reply_price").val(price_per_reply * reply_count);
            var sale_discount = Number($(this).val());
            var total_payment = price_per_reply * reply_count + sale_discount;
            $("#total_payment").val(total_payment);
            var ads_price = Number($("#ads_price").val());
            var manage_price = Number($("#manage_price").val());
            $("#profit").val(total_payment - ads_price - manage_price);
            var profit_ads = (total_payment - ads_price - manage_price) / ads_price * 100;
            if (ads_price == 0)
                profit_ads = 0;
            else
                profit_ads = profit_ads.toFixed(1);
            $("#profit_ads").val(profit_ads);
                    
            var profit_total_ads = (total_payment - ads_price - manage_price) / (ads_price + manage_price) * 100;
            if ((ads_price + manage_price) == 0)
                profit_total_ads = 0;
            else
                profit_total_ads = profit_total_ads.toFixed(1);
            $("#profit_total_ads").val(profit_total_ads);       
        });
        $("#send_count").keyup(function() {
            var send_count = Number($(this).val());
            var display_count = Number($("#display_count").val());
            var sn_count = send_count + display_count;
            $("#sn_count").val(sn_count);
            var send_vs_click = $("#click_count").val() / sn_count * 100;
            if (sn_count == 0)
                send_vs_click = 0;
            else
                send_vs_click = send_vs_click.toFixed(1);
            $("#send_vs_click").val(send_vs_click);
        });
        $("#display_count").keyup(function() {
            var display_count = Number($(this).val());
            var send_count = Number($("#send_count").val());
            var sn_count = send_count + display_count;
            $("#sn_count").val(sn_count);
            var send_vs_click = $("#click_count").val() / sn_count * 100;
            if (sn_count == 0)
                send_vs_click = 0;
            else
                send_vs_click = send_vs_click.toFixed(1);
            $("#send_vs_click").val(send_vs_click);
        });
        $("#ads_price").keyup(function() {
            var ads_price = Number($(this).val());
            var price_per_ads = ads_price / Number($("#reply_count").val());
            if (Number($("#reply_count").val()) == 0)
                price_per_ads = 0;
            else
                price_per_ads = price_per_ads.toFixed(1);
            $("#price_per_ads").val(price_per_ads);
            
            var total_payment = $("#total_payment").val();
            var manage_price = Number($("#manage_price").val());
            $("#profit").val(total_payment - ads_price - manage_price);
            var profit_ads = (total_payment - ads_price - manage_price) / ads_price * 100;
            if (ads_price == 0)
                profit_ads = 0;
            else
                profit_ads = profit_ads.toFixed(1);

            var profit_total_ads = (total_payment - ads_price - manage_price) / (ads_price + manage_price) * 100;
            if ((ads_price + manage_price) == 0)
                profit_total_ads = 0;
            else
                profit_total_ads = profit_total_ads.toFixed(1);
            $("#profit_total_ads").val(profit_total_ads);
            $("#profit_ads").val(profit_ads);
        });
        /*$("#total_payment").keyup(function() {
            var total_payment = Number($(this).val());
            var ads_price = Number($("#ads_price").val());
            var manage_price = Number($("#manage_price").val());
            $("#profit").val(total_payment - ads_price - manage_price);
            var profit_ads = (total_payment - ads_price - manage_price) / ads_price * 100;
            if (ads_price == 0)
                profit_ads = 0;
            else
                profit_ads = profit_ads.toFixed(1);
            $("#profit_ads").val(profit_ads);

            var profit_total_ads = (total_payment - ads_price - manage_price) / (ads_price + manage_price) * 100;
            if ((ads_price + manage_price) == 0)
                profit_total_ads = 0;
            else
                profit_total_ads = profit_total_ads.toFixed(1);
            $("#profit_total_ads").val(profit_total_ads);
        });*/
        $("#manage_price").keyup(function() {
            var manage_price = Number($(this).val());
            var total_payment = Number($("#total_payment").val());
            var ads_price = Number($("#ads_price").val());
            $("#profit").val(total_payment - ads_price - manage_price);
            var profit_ads = (total_payment - ads_price - manage_price) / ads_price * 100;
            if (ads_price == 0)
                profit_ads = 0;
            else
                profit_ads = profit_ads.toFixed(1);
            $("#profit_ads").val(profit_ads);

            var profit_total_ads = (total_payment - ads_price - manage_price) / (ads_price + manage_price) * 100;
            if ((ads_price + manage_price) == 0)
                profit_total_ads = 0;
            else
                profit_total_ads = profit_total_ads.toFixed(1);
            $("#profit_total_ads").val(profit_total_ads);
        });
        $("#click_count2").keyup(function() {
            var click_count2 = Number($(this).val());
            var click_count1 = Number($("#click_count1").val());
            var click_count = click_count1 + click_count2;
            $("#click_count").val(click_count);
            var send_count = Number($("#send_count").val());
            var display_count = Number($("#display_count").val());
            var sn_count = send_count + display_count;
            var send_vs_click = Number($("#click_count").val()) / sn_count * 100;
            if (sn_count == 0)
                send_vs_click = 0;
            else
                send_vs_click = send_vs_click.toFixed(1);
            $("#send_vs_click").val(send_vs_click);

            var reply_count2 = Number($("#reply_count2").val());
            var reply_count1 = Number($("#reply_count1").val());
            var reply_count = reply_count1 + reply_count2;
            var click_vs_reply = reply_count / click_count * 100;
            if (click_count == 0)
                click_vs_reply = 0;
            else
                click_vs_reply = click_vs_reply.toFixed(1);
            $("#click_vs_reply").val(click_vs_reply);
        });
        $("#reply_count2").keyup(function() {
            var reply_count2 = Number($(this).val());
            var reply_count1 = Number($("#reply_count1").val());
            var reply_count = reply_count1 + reply_count2;
            $("#reply_count").val(reply_count);

            var click_count2 = Number($("#click_count2").val());
            var click_count1 = Number($("#click_count1").val());
            var click_count = click_count1 + click_count2;
            var click_vs_reply = reply_count / click_count * 100;
            if (click_count == 0)
                click_vs_reply = 0;
            else
                click_vs_reply = click_vs_reply.toFixed(1);
            $("#click_vs_reply").val(click_vs_reply);

            var ads_price = Number($("#ads_price").val());
            var price_per_ads = ads_price / reply_count;
            if (reply_count == 0)
                price_per_ads = 0;
            else
                price_per_ads = price_per_ads.toFixed(1);
            $("#price_per_ads").val(price_per_ads);

            var price_per_reply = Number($("#price_per_reply").val());
            $("#total_reply_price").val(price_per_reply * reply_count);
            var sale_discount = Number($("#sale_discount").val());
            var total_payment = price_per_reply * reply_count + sale_discount;

            $("#total_payment").val(total_payment);
            var ads_price = Number($("#ads_price").val());
            var manage_price = Number($("#manage_price").val());
            $("#profit").val(total_payment - ads_price - manage_price);
            var profit_ads = (total_payment - ads_price - manage_price) / ads_price * 100;
            if (ads_price == 0)
                profit_ads = 0;
            else
                profit_ads = profit_ads.toFixed(1);
            $("#profit_ads").val(profit_ads);

            var profit_total_ads = (total_payment - ads_price - manage_price) / (ads_price + manage_price) * 100;
            if ((ads_price + manage_price) == 0)
                profit_total_ads = 0;
            else
                profit_total_ads = profit_total_ads.toFixed(1);
            $("#profit_total_ads").val(profit_total_ads);
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
        $("#req_send_id").keyup(function() {
            var point = $(this).val();
            var arr = point.split(",");
            var cnt = arr.length;
            if (point.indexOf(",") == -1 && point == "") {
                cnt = 0;
            }
            $("#req_send_id_count").val(cnt + "건");
            $('#req_send_id_count').data('num', cnt);
        });
    });

    function show_detail(repo_id){
        $("#detail_repo_id").val(repo_id);
        var detail = $("#detail" + repo_id).val();
        $("#report_detail").val(detail);
        $("#report_detail_modal").modal("show");
    };
    function show_notice(key) {
        var msg = "";
        if (key == "sn_count")
            msg = "아웃바운드는 폰문자, 웹문자, 콜백, 메일, 카톡 등으로 전송한 건수를 입력하고, 인바운드(SNS)는 페북, 구글, 네이버등에서 타겟 고객에게 노출된 건수를 입력합니다.";
        else if (key == "click_count")
            msg = "광고 메시지, 콘텐츠에 포함되어 있는 링크를 클릭한 건수를 말하고 자동입력됩니다. 수동입력은 해당 콘텐츠 외에 클릭이 발생한 경우에 입력하고 둘의 합계를 링크클릭으로 봅니다.";
        else if (key == "reply_count")
            msg = "신청응답은 자동+입력으로 이루어지고, 자동은 유저가 신청이나 응답을 하여 자동으로 입력되는 공간과 전화 문자 등을 통해 신청이 이루어진 부분은 입력공간에 입력을 하면 두 정보가 통합되어 나타나게 됩니다. ";
        else if (key == "ads_price")
            msg = "광고비로 사용된 금액으로 문자, 메일, 페북, 구글 등에 광고비로 사용된 금액을 입력합니다.";
        else if (key == "manage_price")
            msg = "광고홍보채널에 사용된 비용 외에 광고홍보를 수행하는 과정에서 사용된 인건비, 외부 수수료 등을 입력합니다.";
        else if (key == "total_payment")
            msg = "광고후에 발생한 총 매출액을 말하며,총응답매출액(응답건수*응답별단가)과 판매수수료(광고후 실제판매에 대한 수수료)를 더한 값이 매출총금액입니다.판매수수료는 광고주와 협약한 수수료를 받은 날에 입력하면 자동으로 총매출액이 산정됩니다.";
        else if (key == "send_vs_click")
            msg = "링크클릭건수÷전송(노출)건수×100=노출대비클릭율로 초기 메시지나 콘텐츠의 전송(노출)건수 대비 고객이 클릭한 비율입니다.";
        else if (key == "click_vs_reply")
            msg = "신청응답건수÷클릭건수×100=클릭대비신청응답율로 링크를 클릭한 고객 중에 신청한 분들의 비율입니다.";
        else if (key == "price_per_ads")
            msg = "광고홍보비용÷신청응답건수=광고비당 응답단가로서 광고홍보에 사용된 비용(예 : 10만원)을 신청응답한 고객수에 따른 객단가(신청자 1인에 대한 광고비단가)를 말합니다.";
        else if (key == "profit")
            msg = "총매출액수-(광고홍보비용+홍보관리비용)=광고순이익금으로 광고비와 광고관리비용을 총매출액에서 뺀 나머지 순수익을 말합니다.";
        else if (key == "profit_total_ads")
            msg = "순이익÷(광고홍보비용+홍보관리비용)×100=광고순이익비율로 광고에 사용된 모든 비용을 순이익에 대해 비율로 환산한 내용입니다.";
        else if (key == "price_per_reply")
            msg = "응답건별 단가는 해당 이벤트에 신청응답을 한 건당 단가를 기록합니다.이 값은 신청응답건과 곱하여 총응답 매출액이 됩니다.";
        if (msg != "")
            alert(msg);
    }

    function copy_to_img() {
        var repo_id = $("#statistic_repo_id").val();
        html2canvas(document.getElementById('statistic_content')).then(function(canvas) {
            // Append the canvas to the body or a specific element
            document.getElementById('result').appendChild(canvas);
            // If you want to save the image
            let link = document.createElement('a');
            link.download = 'report_' + repo_id + '.png';
            link.href = canvas.toDataURL();
            link.click();
        });
    }

    function copy_to_txt() {
        var repo_id = $("#statistic_repo_id").val();
        let csvContent = '\uFEFF';
        // Loop through each row in the table
        $('#statistic_content tbody tr').each(function() {
            const row = [];
            $(this).find('td').each(function() {
                let cellText = '';
                $(this).contents().each(function() {
                    if (this.nodeType === Node.TEXT_NODE) {
                        cellText += $(this).text().trim();
                    } else if (this.nodeType === Node.ELEMENT_NODE && $(this).is('input')) {
                        cellText += $(this).val().trim();
                    }
                });
                //row.push(cellText);
                row.push('="' + cellText.replace(/"/g, '""') + '"');
            });
            csvContent += row.join(',') + '\n'; // Join cells with comma and add new line
        });

        // Create a temporary link element to trigger download
        const blob = new Blob([csvContent], {
            type: 'text/csv;charset=utf-8;'
        });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'report_' + repo_id + '.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function show_copy_check() {
        $("#copy_check").show();
    }

    function show_statistic(repo_id) {
        $("#copy_check").hide();
        $.ajax({
            type: "POST",
            url: "/ajax/ajax.report.php",
            dataType: "json",
            data: {
                method: "get_statistic",
                index: repo_id
            },
            success: function(data) {
                if (data.result == "success") {
                    $("#statistic_repo_id").val(repo_id);
                    $("#channel").val(data.channel);
                    $("#send_count").val(data.send_count);
                    $("#display_count").val(data.display_count);
                    $("#sn_count").val(Number(data.send_count) + Number(data.display_count));
                    $("#ads_price").val(data.ads_price);
                    $("#manage_price").val(data.manage_price);
                    $("#click_count1").val(data.click_count);
                    $("#click_count2").val(data.click_count_manual);
                    var click_count = Number(data.click_count) + Number(data.click_count_manual);
                    $("#click_count").val(click_count);
                    $("#reply_count1").val(data.reply_count);
                    $("#reply_count2").val(data.reply_count_manual);
                    var reply_count = Number(data.reply_count) + Number(data.reply_count_manual);
                    $("#reply_count").val(reply_count);
                    $("#price_per_reply").val(data.price_per_reply);
                    $("#total_reply_price").val(Number(data.price_per_reply) * reply_count);
                    $("#sale_discount").val(data.sale_discount);
                    $("#total_payment").val(Number(data.price_per_reply) * reply_count + Number(data.sale_discount));
                    var send_vs_click = click_count / (Number(data.send_count) + Number(data.display_count)) * 100;
                    if (Number(data.send_count) + Number(data.display_count) == 0)
                        send_vs_click = 0;
                    else
                        send_vs_click = send_vs_click.toFixed(1);
                    $("#send_vs_click").val(send_vs_click);
                    var click_vs_reply = reply_count / click_count * 100;
                    if (click_count == 0)
                        click_vs_reply = 0;
                    else
                        click_vs_reply = click_vs_reply.toFixed(1);
                    $("#click_vs_reply").val(click_vs_reply);
                    var price_per_ads = data.ads_price / reply_count;
                    if (reply_count == 0)
                        price_per_ads = 0;
                    else
                        price_per_ads = price_per_ads.toFixed(1);
                    $("#price_per_ads").val(price_per_ads);
                    var profit = Number(data.price_per_reply) * reply_count + Number(data.sale_discount) - Number(data.ads_price) - Number(data.manage_price);
                    $("#profit").val(profit);
                    var profit_ads = profit / data.ads_price * 100;
                    if (data.ads_price == 0)
                        profit_ads = 0;
                    else
                        profit_ads = profit_ads.toFixed(1);
                    $("#profit_ads").val(profit_ads);
                    var profit_total_ads = profit / (Number(data.ads_price) + Number(data.manage_price)) * 100;
                    if ((Number(data.ads_price) + Number(data.manage_price)) == 0)
                        profit_total_ads = 0;
                    else
                        profit_total_ads = profit_total_ads.toFixed(1);
                    $("#profit_total_ads").val(profit_total_ads);
                    $("#report_statistic").modal("show");
                }
            }
        })
    }

    function close_statistic() {
        $("#report_statistic").modal("hide");
    }

    function save_statistic() {
        var repo_id = $("#statistic_repo_id").val();
        var send_count = $("#send_count").val();
        var channel = $("#channel").val();
        var display_count = Number($("#display_count").val());
        var ads_price = $("#ads_price").val();
        var manage_price = $("#manage_price").val();
        var price_per_reply = $("#price_per_reply").val();
        var sale_discount = Number($("#sale_discount").val());
        var click_count_manual = $("#click_count2").val();
        var reply_count_manual = $("#reply_count2").val();
        $.ajax({
            type: "POST",
            url: "/ajax/ajax.report.php",
            dataType: "json",
            data: {
                method: "save_statistic",
                index: repo_id,
                channel: channel,
                send_count: send_count,
                display_count: display_count,
                ads_price: ads_price,
                manage_price: manage_price,
                click_count_manual: click_count_manual,
                sale_discount:sale_discount,
                price_per_reply:price_per_reply,
                reply_count_manual: reply_count_manual
            },
            success: function(data) {
                if (data.result == "success") {
                    alert("성공적으로 저장되었습니다.");
                    $("#report_statistic").modal("hide");
                    $("#detail_" + repo_id).html(channel);
                    if(price_per_reply != 0){
                        $("#static_" + repo_id).css('background-color', 'blue');
                        $("#static_" + repo_id).css('color', 'white');
                    }else{
                        $("#static_" + repo_id).css('background-color', 'white');
                        $("#static_" + repo_id).css('color', 'black');
                    }

                }
            }
        })
    }

    function deleteMultiRow() {
        if (confirm('삭제하시겠습니까?')) {
            var check_array = $("#report_table").children().find(".check");
            var no_array = [];
            var index = 0;
            check_array.each(function() {
                if ($(this).prop("checked") && $(this).val() > 0)
                    no_array[index++] = $(this).val();
            });
            if (index == 0) {
                alert("삭제할 리포트를 선택해주세요.");
                return;
            }
            $.ajax({
                type: "POST",
                url: "/ajax/ajax.report.php",
                dataType: "json",
                data: {
                    method: "del",
                    index: no_array.toString()
                },
                success: function(data) {
                    if (data.result == "success") {
                        alert('성공적으로 삭제 되었습니다.');
                        window.location.reload();
                    }
                }
            })
        }
    }

    function cloneMultiRow() {
        if (confirm('복제하시겠습니까?')) {
            var check_array = $("#report_table").children().find(".check");
            var no_array = [];
            var index = 0;
            check_array.each(function() {
                if ($(this).prop("checked") && $(this).val() > 0)
                    no_array[index++] = $(this).val();
            });
            if (index == 0) {
                alert("복제할 리포트를 선택해주세요.");
                return;
            }
            $.ajax({
                type: "POST",
                url: "/ajax/ajax.report.php",
                dataType: "json",
                data: {
                    method: "clone",
                    index: no_array.toString()
                },
                success: function(data) {
                    if (data.result == "success") {
                        alert('성공적으로 복제 되었습니다.');
                        window.location.reload();
                    }
                }
            })
        }
    }

    function previewReport(str) {
        window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
    }

    function sendReport() {
        var check_array = $("#report_table").children().find(".check");
        var no_array = [];
        var index = 0;
        check_array.each(function() {
            if ($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });
        if (index == 0) {
            alert("전송할 리포트를 선택해주세요.\r\n수신자는 리셀러이상 사업자여야 합니다.");
            return;
        }
        $("#send_report_idx").val(no_array.join(","));
        $("#send_report").modal("show");
    }

    function show_more(str) {
        $("#contents_detail").val($("#" + str).val());
        $("#show_detail_more").modal("show");
    }

    function send_Cancel() {
        $("#send_report").modal("hide");
        $("#req_send_id_count").val("0건");
        $('#req_send_id').val("");
        $("#send_report_idx").val("");
    }

    function send_Report() {
        var send_ids = $("#req_send_id").val();
        var repo_idx = $("#send_report_idx").val();

        if (send_ids == "") {
            alert("아이디를 입력하세요.");
            $('#req_send_id').focus();
            return;
        }
        $.ajax({
            type: "POST",
            url: "/ajax/ajax.report.php",
            dataType: "json",
            data: {
                method: "send_report",
                send_ids: send_ids,
                repo_idx: repo_idx
            },
            success: function(data) {
                console.log(data);
                alert("전송되었습니다.");
                send_Cancel();
            }
        });
    }

    function save_detail(){
        var idx = $("#detail_repo_id").val();
        $.ajax({
            type: "POST",
            url: "/ajax/ajax.report.php",
            dataType: "json",
            data: {
                method: "save_report_detail",
                id: idx,
                cont: $("#report_detail").val()
            },
            success: function(data) {
                alert("성공적으로 저장되었습니다.");
                $("#report_detail_modal").modal("hide");
                $("#detail" + idx).val($("#report_detail").val());
            }
        });
    }
    function save_detail_idx(idx){
        $.ajax({
            type: "POST",
            url: "/ajax/ajax.report.php",
            dataType: "json",
            data: {
                method: "save_report_detail",
                id: idx,
                cont: $("#detail"+idx).val()
            },
            success: function(data) {
                alert("성공적으로 저장되었습니다.");
                //$("#report_detail_modal").modal("hide");
                //$("#detail" + idx).val($("#report_detail").val());
            }
        });
    }
</script>