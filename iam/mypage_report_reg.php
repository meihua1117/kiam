<?php
include "inc/header.inc.php";
if ($_SESSION['iam_member_id'] == "") {
    echo "<script>location='/';</script>";
}
if ($member_iam['service_type'] < 2) {
    echo "<script>alert('리포트추가 권한이 없습니다.');location='/';</script>";
}
$index = 0;
if (isset($_GET["index"]))
    $index  = $_GET["index"];
if ($index != 0) {
    $sql = "select * from gn_report_form where id = $index";
    $res = mysqli_query($self_con, $sql);
    $row_form = mysqli_fetch_array($res);
    if ($row_form['request_yn'] == 'Y' && $row_form['pcode']) {
        $erq_sql = "select * from Gn_event where event_idx = {$row_form['pcode']}";
        $erq_res = mysqli_query($self_con, $erq_sql);
        $erq_row = mysqli_fetch_array($erq_res);
    }
}
?>
<style>
    .key {
        background-color: #bfbfbf !important;
        color: white !important;
    }

    .m-signature-pad {
        height: 130px;
        padding: 12px 0 0;
        margin: 7px 0 12px;
        position: relative;
    }

    .marb3 {
        margin-bottom: 3px !important;
    }

    .kbw-signature {
        width: 100%;
        height: 100px;
        background-color: #f1f1f1;
        display: inline-block;
        border: 1px solid #eee;
        -ms-touch-action: none;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    .switch_repo_status {
        position: relative;
        display: inline-block;
        width: 55px;
        height: 28px;
        text-align: center;
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

    input:checked+.slider:before {
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
        height: 20px;
        width: 20px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .button_delete {
        padding: 3px;
        border: 1px solid #bfbfbf;
        cursor: pointer
    }
</style>
<script src="js/jquery-ui-1.10.3.custom.js"></script>
<script src="js/jquery-signature.js"></script>
<script src="js/jquery.report_form.js"></script>
<main id="register" class="common-wrap" style="margin-top: 86px"><!-- 컨텐츠 영역 시작 -->
    <input type="hidden" name="link" id="link" value="<?= $link ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="inner-wrap">
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
                    <form name="edit_form" id="edit_form" method="post" enctype="multipart/form-data">
                        <div style="text-align: center;margin-top:20px">
                            <h2 class="title">리포트 포맷 등록하기</h2>
                        </div>
                        <section class="input-field">
                            <h3 class="title">리포트 타이틀정보 입력</h3>
                            <div class="form-wrap">
                                <div class="attr-row">
                                    <div class="attr-name">타이틀</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="text" name="repo_title" id="repo_title" class="input" placeholder="리포트 타이틀 입력" value="<?= htmlspecialchars($row_form['title']) ?>">
                                            <input type="hidden" value="<?= $index ?>" name="repo_index">
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">설명글</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <textarea name="repo_desc" id="repo_desc" class="input" style="height: 60px;" wrap="hard" placeholder=""><?= $row_form['descript'] ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <section class="input-field repo_format">
                            <h3 class="title">리포트 포맷 선택</h3>
                            <? if ($index != 0) {
                                $sql = "select * from gn_report_form1 where form_id = $index order by item_order";
                                $res = mysqli_query($self_con, $sql);
                                while ($row = mysqli_fetch_array($res)) {
                            ?>
                                    <div class="form-wrap" data-index="<?= $row['item_order'] ?>">
                                        <input type="radio" name="item_type_<?= $row['item_order'] ?>" value="0" onclick="chk_item_type(this,0);" <?= $row['item_type'] == 0 ? "checked" : ""; ?>>
                                        <label>제시형</label>
                                        <input type="radio" name="item_type_<?= $row['item_order'] ?>" value="1" onclick="chk_item_type(this,1);" <?= $row['item_type'] == 1 ? "checked" : ""; ?>>
                                        <label>설문형</label>
                                        <input type="radio" name="item_type_<?= $row['item_order'] ?>" value="3" onclick="chk_item_type(this,3);" <?= $row['item_type'] == 3 ? "checked" : ""; ?>>
                                        <label>입력형</label>
                                        <input type="radio" name="item_type_<?= $row['item_order'] ?>" value="2" onclick="chk_item_type(this,2);" <?= $row['item_type'] == 2 ? "checked" : ""; ?>>
                                        <label>설명형</label>
                                        <div class="attr-row" style="margin-top: 5px">
                                            <div class="attr-name">항목명</div>
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <input type="text" name="repo_item" class="input" placeholder="항목 입력" value="<?= htmlspecialchars($row['item_title']) ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="attr-row repo_req" style="margin-top: 5px;">
                                            <div class="attr-name">질문</div>
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <input type="text" name="repo_req" id="repo_req" class="input" placeholder="질문내용 입력" value="<?= htmlspecialchars($row['item_req']) ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="jesi-content">
                                            <?
                                            $repo_sql = "select * from gn_report_form2 where form_id=$index and item_id={$row['id']} order by id";
                                            $repo_res = mysqli_query($self_con, $repo_sql);
                                            while ($repo_row = mysqli_fetch_array($repo_res)) {
                                                if ($row['item_type'] == 0) { ?>
                                                    <div class="attr-row" style="margin-top: 5px">
                                                        <div class="attr-value">
                                                            <div class="input-wrap">
                                                                <input type="text" name="repo_item_key" class="input key" placeholder="제시어 입력(예시:이름)" value="<?= htmlspecialchars($repo_row['tag_name']) ?>">
                                                            </div>
                                                        </div>
                                                        <div class="attr-value">
                                                            <div class="input-wrap" style="display: flex">
                                                                <input type="text" name="repo_item_value" class="input" placeholder="" value="">
                                                                <input type="button" value="삭제" onclick="jesi_del(this)" class="button_delete">
                                                            </div>
                                                        </div>
                                                    </div>
                                                <? } else if ($row['item_type'] == 1) { ?>
                                                    <div class="attr-row" style="margin-top: 5px">
                                                        <div class="attr-value">
                                                            <div class="input-wrap" style="display: flex">
                                                                <input type="text" name="repo_item_key" class="input" placeholder="옵션" value="<?= htmlspecialchars($repo_row['tag_name']) ?>">
                                                                <input type="button" value="삭제" onclick="jesi_del(this)" class="button_delete">
                                                            </div>
                                                        </div>
                                                    </div>
                                                <? } else if ($row['item_type'] == 3) { ?>
                                                    <div class="attr-row" style="margin-top: 5px">
                                                        <div class="attr-value" style="display: block;width: 100%">
                                                            <div class="input-wrap">
                                                                <input type="text" name="repo_item_key" class="input key" placeholder="세부질문내용 입력" value="<?= htmlspecialchars($repo_row['tag_name']) ?>">
                                                            </div>
                                                        </div>
                                                        <div class="attr-value" style="display: block;width: 100%;margin-top: 5px">
                                                            <div class="input-wrap" style="display: flex">
                                                                <input type="text" name="repo_item_value" class="input" placeholder="" value="">
                                                                <input type="button" value="삭제" onclick="jesi_del(this)" class="button_delete">
                                                            </div>
                                                        </div>
                                                    </div>
                                                <? } else { ?>
                                                    <div class="attr-row" style="margin-top: 5px">
                                                        <div class="attr-value">
                                                            <div class="input-wrap">
                                                                <textarea class="input" placeholder="내용 입력" style='height: 200px'><?= $repo_row['tag_name'] ?></textarea>
                                                                <div style='display: flex;margin-top:10px'>
                                                                    <input type="radio" name="repo_item_img_type" value="img" <?= $repo_row['tag_link'] ? 'checked' : ''; ?> onclick="change_img_type(this);">
                                                                    <label>이미지 파일등록</label>
                                                                    <input type="radio" name="repo_item_img_type" value="link" <?= !$repo_row['tag_link'] ? 'checked' : ''; ?> style="margin-left:10px" onclick="change_img_type(this);">
                                                                    <label>웹주소/유튜브주소 입력</label>
                                                                </div>
                                                                <div style="margin-top:10px;<?= $repo_row['tag_link'] ? 'display: flex' : 'display:none' ?>" class="img_file">
                                                                    <input type="text" name="repo_item_link" class="input" placeholder="화면클릭시 이동할 웹페이지주소를 입력하세요." value="<?= htmlspecialchars($repo_row['tag_link']) ?>">
                                                                    <input type="file" name="repo_item_file" class="input" style="padding:2px;width:33%" accept=".jpg,.jpeg,.png,.gif,.svc" onchange="uploadImage(this)" data-file="<?= $repo_row['tag_img'] ?>">
                                                                    <input type="button" value="삭제" onclick="jesi_del(this)" class="button_delete">
                                                                </div>
                                                                <div style="margin-top: 10px;<?= $repo_row['tag_link'] ? 'display: none' : '' ?>" class="img_link">
                                                                    <input type="text" name="repo_item_value" class="input" placeholder="링크주소 입력 http://" value="<?= htmlspecialchars($repo_row['tag_img']) ?>">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <? }
                                            } ?>
                                        </div>
                                        <div style="text-align: center;margin-top: 5px">
                                            <input type="button" onclick="jesi_add(this);" value="추가하기">
                                        </div>
                                        <div style="text-align: right;margin-top: 5px">
                                            <a class="content-utils" onclick="item_up(this)">
                                                <img src="/iam/img/menu/icon_arrow_up.png" style="width:20px;height:20px;margin-right:2px;">
                                            </a>
                                            <a class="content-utils" onclick="item_down(this)">
                                                <img src="/iam/img/menu/icon_arrow_down.png" style="width:20px;height:20px;margin-right:2px;">
                                            </a>
                                            <a class="content-utils" onclick="clone_item(this)">
                                                <img src="/iam/img/menu/icon_clone_white.png" style="width:20px;height:20px;margin-right:2px;">
                                            </a>
                                            <a class="content-utils" onclick="del_item(this)">
                                                <img src="/iam/img/menu/icon_trash_white.png" style="width:20px;height:20px;margin-right:2px;">
                                            </a>
                                        </div>
                                    </div>
                                <?    }
                            } else { ?>
                                <div class="form-wrap" data-index="0">
                                    <input type="radio" name="item_type_0" value="0" checked onclick="chk_item_type(this,0);">
                                    <label>제시형</label>
                                    <input type="radio" name="item_type_0" value="1" onclick="chk_item_type(this,1);">
                                    <label>설문형</label>
                                    <input type="radio" name="item_type_0" value="3" onclick="chk_item_type(this,3);">
                                    <label>입력형</label>
                                    <input type="radio" name="item_type_0" value="2" onclick="chk_item_type(this,2);">
                                    <label>설명형</label>
                                    <div class="attr-row" style="margin-top: 5px">
                                        <div class="attr-name">항목명</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="text" name="repo_item" class="input" placeholder="항목 입력" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="attr-row repo_req" style="margin-top: 5px;">
                                        <div class="attr-name">질문</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="text" name="repo_req" id="repo_req" class="input" placeholder="질문내용 입력" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="jesi-content">
                                        <div class="attr-row" style="margin-top: 5px">
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <input type="text" name="repo_item_key" class="input key" placeholder="제시어 입력(예시:이름)" value="">
                                                </div>
                                            </div>
                                            <div class="attr-value">
                                                <div class="input-wrap" style="display: flex">
                                                    <input type="text" name="repo_item_value" class="input" placeholder="" value="">
                                                    <input type="button" value="삭제" class="button_delete" onclick="jesi_del(this)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="text-align: center;margin-top: 5px">
                                        <input type="button" onclick="jesi_add(this);" value="추가하기">
                                    </div>
                                    <div style="text-align: right;margin-top: 5px">
                                        <a class="content-utils" onclick="item_up(this)">
                                            <img src="/iam/img/menu/icon_arrow_up.png" style="width:20px;height:20px;margin-right: 2px;">
                                        </a>
                                        <a class="content-utils" onclick="item_down(this)">
                                            <img src="/iam/img/menu/icon_arrow_down.png" style="width:20px;height:20px;margin-right:2px;">
                                        </a>
                                        <a class="content-utils" onclick="clone_item(this)">
                                            <img src="/iam/img/menu/icon_clone_white.png" style="width:20px;height:20px;margin-right:2px;">
                                        </a>
                                        <a class="content-utils" onclick="del_item(this)">
                                            <img src="/iam/img/menu/icon_trash_white.png" style="width:20px;height:20px;margin-right:2px;">
                                        </a>
                                    </div>
                                </div>
                            <? } ?>
                        </section>
                        <div>
                            <label>리포트서명</label>
                            <label class="switch_repo_status" style="margin:0 25px;">
                                <input type="checkbox" name="status" id="stauts_logo" value="<?php echo $row_form['id']; ?>" <?php echo $row_form['sign_visible'] == 1 ? "checked" : "" ?>>
                                <span class="slider round" name="status_round" id="stauts_round"></span>
                            </label>
                        </div>
                        <div id="signature-pad" class="m-signature-pad">
                            <p class="marb3"><strong class="blink">아래 박스안에 서명을 남겨주세요.</strong><button type="button" id="clear" class="btn_ssmall bx-white">서명 초기화</button></p>
                            <div id="sign"></div>
                            <textarea name="signatureJSON" id="signatureJSON" style="display: none" readonly=""></textarea>
                        </div>
                        <section class="input-field" style="margin-top: 10px;">
                            <div class="form-wrap">
                                <div class="attr-row">
                                    <div class="attr-name" style="width: 100px">제출모드</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="radio" name="request_yn" id="request_n" value="N" <?=($row_form['request_yn'] == "N" || $row_form['request_yn'] == "")? "checked" : "" ?>>기본정보형
                                            <input type="radio" name="request_yn" id="request_y" value="Y" <?=$row_form['request_yn'] == "Y" ? "checked" : "" ?>>상세정보형
                                            <input type="text" class="input request" name="event_name_eng" placeholder="" id="event_name_eng" value="<?= $erq_row['event_name_eng'] ?>" readonly style="width:100px;<?=($row_form['request_yn'] == "N" || $row_form['request_yn'] == "") ? "display:none":""?>" />
                                            <input type="hidden" name="event-idx" id="event-idx" value="<?= $erq_row['event_idx']; ?>" />
                                            <input type="hidden" name="event_code" id="event_code" value="<?= $erq_row['event_name_eng'] ?>" /><!--event_name_eng-->
                                            <input type="hidden" name="pcode" id="pcode" value="<?= $erq_row['pcode'] ?>" />
                                            <input type="button" value="조회" class="button request" style="padding: 5px 10px;font-size: 12px;<?=($row_form['request_yn'] == "N" || $row_form['request_yn'] == "") ? "display:none":""?>" id="searchBtn">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <div class="button-wrap">
                            <a href="javascript:save_format(<?= $index ?>)" class="button is-pink">저장하기</a>
                            <a href="javascript:location.href='mypage_report.php';" class="button is-grey">목록보기</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main><!-- // 컨텐츠 영역 끝 -->
<div id="ajax_div" style="display:none"></div>
<script language="javascript">
    $(function() {
        if ($('.switch_repo_status').find("input[type=checkbox]").is(":checked") == true)
            $("#signature-pad").show();
        else
            $("#signature-pad").hide();
        $(document).ajaxStop(function() {
            $("#ajax-loading").delay(10).hide(1);
        });
        $('.switch_repo_status').on("change", function() {
            var status = $(this).find("input[type=checkbox]").is(":checked") == true ? 1 : 0;
            if (status == 1)
                $("#signature-pad").show();
            else
                $("#signature-pad").hide();
        });
        $('#searchBtn').on("click", function() {
            window.open("/mypage_pop_link_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
        });
        $("input[name=request_yn]").on("change", function() {
            if($(this).val() == "N")
                $(".request").hide();
            else
                $(".request").show();
        });
    });

    function save_format(index) {
        if ($('#repo_title').val() == "") {
            alert('리포트 타이틀을 입력해주세요.');
            $('#repo_title').focus();
            return;
        }
        /*if ($('#repo_desc').val() == "") {
            alert('리포트 설명글을 입력해주세요.');
            $('#repo_desc').focus();
            return;
        }*/
        var jsonObj = {};
        jsonObj["title"] = $('#repo_title').val();
        jsonObj["desc"] = $('#repo_desc').val();
        jsonObj["sign"] = $('.switch_repo_status').find("input[type=checkbox]").is(":checked") == true ? 1 : 0;
        jsonObj["item"] = [];
        jsonObj["req_yn"] = $('input[name=request_yn]:checked').val();
        jsonObj["event_code"] = $('#event_code').val();
        jsonObj["pcode"] = $('#pcode').val();
        jsonObj["event_idx"] = $('#event-idx').val();

        $(".repo_format").find(".form-wrap").each(function() {
            var index = $(this).data("index");
            var item_type = $(this).find("input[name=item_type_" + index + "]:checked").val();
            var item_title = $(this).find("input[name=repo_item]").val();
            var item_req = $(this).find("input[name=repo_req]").val();
            var item = {};
            item["order"] = index;
            item["type"] = item_type;
            item["title"] = item_title;
            item["req"] = item_req;
            item["key"] = [];
            if (item_type != 2) {
                $(this).find(".attr-row").find("input[name=repo_item_key]").each(function() {
                    item["key"].push($(this).val());
                });
            } else {
                $(this).find(".jesi-content").find(".attr-row").each(function() {
                    var repo = {};
                    repo["desc"] = $(this).find("textarea").val();
                    if ($(this).find(".img_file").is(':visible')) {
                        repo["link"] = $(this).find("input[name=repo_item_link]").val();
                        repo["img"] = $(this).find("input[name=repo_item_file]").data('file');
                    } else {
                        repo["link"] = '';
                        repo["img"] = $(this).find("input[name=repo_item_value]").val();
                    }
                    item["key"].push(repo);
                });
            }
            jsonObj["item"].push(item);
        });
        var method = (index != 0 ? "edit_format" : "create_format");
        var msg = index != 0 ? "수정하시겠습니까?" : "등록하시겠습니까?";
        if (confirm(msg)) {
            $.ajax({
                type: "POST",
                url: "/ajax/ajax.report.php",
                dataType: "json",
                data: {
                    "index": index,
                    "method": method,
                    "data": JSON.stringify(jsonObj)
                },
                success: function(data) {
                    alert("성공적으로 등록되었습니다.");
                    location.href = "mypage_report.php";
                },
                error: function(request, status, error) {
                    console.log('code: ' + request.status + "\n" + 'message: ' + request.responseText + "\n" + 'error: ' + error);
                }
            });
        }
    }

    function jesi_add(obj) {
        var parent = $(obj).parents(".form-wrap");
        var order = parent.data("index");
        var jesi_type = parent.find("input[name^=item_type_]:checked").val();
        var jesi_row = parent.find(".jesi-content");
        var content = "";
        if (jesi_type == 0) {
            content = "<div class=\"attr-row\" style=\"margin-top: 5px\">" +
                "<div class=\"attr-value\">" +
                "<div class=\"input-wrap\">" +
                "<input type=\"text\" name=\"repo_item_key\" class=\"input key\" placeholder=\"제시어 입력(예시:이름)\" value=\"\">" +
                "</div>" +
                "</div>" +
                "<div class=\"attr-value\">" +
                "<div class=\"input-wrap\" style=\"display: flex\">" +
                "<input type=\"text\" name=\"repo_item_value\" class=\"input\" placeholder=\"\" value=\"\">" +
                "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">" +
                "</div>" +
                "</div>" +
                "</div>";
        } else if (jesi_type == 1) {
            content = "<div class=\"attr-row\" style=\"margin-top: 5px\">" +
                "<div class=\"attr-value\">" +
                "<div class=\"input-wrap\" style=\"display: flex\">" +
                "<input type=\"text\" name=\"repo_item_key\" class=\"input\" placeholder=\"옵션\" value=\"\">" +
                "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">" +
                "</div>" +
                "</div>" +
                "</div>";
        } else if (jesi_type == 3) {
            content = "<div class=\"attr-row\" style=\"margin-top: 5px\">" +
                "<div class=\"attr-value\" style=\"display:block;width:100%\">" +
                "<div class=\"input-wrap\">" +
                "<input type=\"text\" name=\"repo_item_key\" class=\"input key\" placeholder=\"세부질문내용 입력\" value=\"\">" +
                "</div>" +
                "</div>" +
                "<div class=\"attr-value\" style=\"display:block;width:100%;margin-top: 5px\">" +
                "<div class=\"input-wrap\" style=\"display: flex\">" +
                "<input type=\"text\" name=\"repo_item_value\" class=\"input\" placeholder=\"\" value=\"\">" +
                "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">" +
                "</div>" +
                "</div>" +
                "</div>";
        } else {
            content = "<div class=\"attr-row\" style=\"margin-top: 5px\">" +
                "<div class=\"attr-value\">" +
                "<div class=\"input-wrap\">" +
                "<textarea class=\"input\" placeholder=\"내용 입력\" style='height: 200px'></textarea>" +
                "<div style=\"display: flex;margin-top:10px\">" +
                "<input type=\"radio\" name=\"repo_item_img_type\" value=\"img\" checked onclick=\"change_img_type(this);\">" +
                "<label>이미지 파일등록</label>" +
                "<input type=\"radio\" name=\"repo_item_img_type\" value=\"link\" style=\"margin-left:10px\" onclick=\"change_img_type(this);\">" +
                "<label>웹주소/유튜브주소 입력</label>" +
                "</div>" +
                "<div style=\"margin-top:10px;display: flex\" class=\"img_file\">" +
                "<input type=\"text\" name=\"repo_item_link\" class=\"input\" placeholder=\"화면클릭시 이동할 웹페이지주소를 입력하세요.\" > " +
                "<input type=\"file\" name=\"repo_item_file\" class=\"input\" style=\"padding:2px;width:33%\" accept=\".jpg,.jpeg,.png,.gif,.svc\" onchange=\"uploadImage(this)\">" +
                "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">" +
                "</div>" +
                "<div style=\"margin-top: 10px;display: none\" class=\"img_link\">" +
                "<input type=\"text\" name=\"repo_item_value\" class=\"input\" placeholder=\"링크주소 입력 http://\" >" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";
        }
        jesi_row.append(content);
    }

    function chk_item_type(obj, jesi_type) {
        var parent = $(obj).parents(".form-wrap");
        var jesi_row = parent.find(".jesi-content");
        jesi_row.empty();
        var content = "";
        if (jesi_type == 0) {
            content = "<div class=\"attr-row\" style=\"margin-top: 5px\">" +
                "<div class=\"attr-value\">" +
                "<div class=\"input-wrap\">" +
                "<input type=\"text\" name=\"repo_item_key\" class=\"input key\" placeholder=\"제시어 입력(예시:이름)\" value=\"\">" +
                "</div>" +
                "</div>" +
                "<div class=\"attr-value\">" +
                "<div class=\"input-wrap\" style=\"display: flex\">" +
                "<input type=\"text\" name=\"repo_item_value\" class=\"input\" placeholder=\"\" value=\"\">" +
                "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">" +
                "</div>" +
                "</div>" +
                "</div>";
            $(".repo_image").hide();
        } else if (jesi_type == 1) {
            content = "<div class=\"attr-row\" style=\"margin-top: 5px\">" +
                "<div class=\"attr-value\">" +
                "<div class=\"input-wrap\" style=\"display: flex\">" +
                "<input type=\"text\" name=\"repo_item_key\" class=\"input\" placeholder=\"옵션\" value=\"\">" +
                "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">" +
                "</div>" +
                "</div>" +
                "</div>";
            $(".repo_image").hide();
        } else if (jesi_type == 3) {
            content = "<div class=\"attr-row\" style=\"margin-top: 5px\">" +
                "<div class=\"attr-value\" style=\"display:block;width:100%\">" +
                "<div class=\"input-wrap\">" +
                "<input type=\"text\" name=\"repo_item_key\" class=\"input key\" placeholder=\"세부질문내용 입력\" value=\"\">" +
                "</div>" +
                "</div>" +
                "<div class=\"attr-value\" style=\"display:block;width:100%;margin-top: 5px\">" +
                "<div class=\"input-wrap\" style=\"display: flex\">" +
                "<input type=\"text\" name=\"repo_item_value\" class=\"input\" placeholder=\"\" value=\"\">" +
                "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">" +
                "</div>" +
                "</div>" +
                "</div>";
            $(".repo_image").hide();
        } else {
            content = "<div class=\"attr-row\" style=\"margin-top: 5px\">" +
                "<div class=\"attr-value\">" +
                "<div class=\"input-wrap\">" +
                "<textarea class=\"input\" placeholder=\"내용 입력\" style='height: 200px'></textarea>" +
                "<div style=\"display: flex;margin-top:10px\">" +
                "<input type=\"radio\" name=\"repo_item_img_type\" value=\"img\" checked onclick=\"change_img_type(this);\">" +
                "<label>이미지 파일등록</label>" +
                "<input type=\"radio\" name=\"repo_item_img_type\" value=\"link\" style=\"margin-left:10px\" onclick=\"change_img_type(this);\">" +
                "<label>웹주소/유튜브주소 입력</label>" +
                "</div>" +
                "<div style=\"margin-top: 10px;display: flex\" class=\"img_file\">" +
                "<input type=\"text\" name=\"repo_item_link\" class=\"input\" placeholder=\"화면클릭시 이동할 웹페이지주소를 입력하세요.\" > " +
                "<input type=\"file\" name=\"repo_item_file\" class=\"input\" style=\"padding:2px;width:33%\" accept=\".jpg,.jpeg,.png,.gif,.svc\" onchange=\"uploadImage(this)\">" +
                "<input type=\"button\" value=\"삭제\" onclick=\"jesi_del(this)\" class=\"button_delete\">" +
                "</div>" +
                "<div style=\"margin-top: 10px;display: none\" class=\"img_link\">" +
                "<input type=\"text\" name=\"repo_item_value\" class=\"input\" placeholder=\"링크주소 입력 http://\" >" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";
            $(".repo_image").show();
        }
        jesi_row.append(content);
    }

    function jesi_del(obj) {
        $(obj).parents(".attr-row").remove();
    }

    function change_img_type(obj) {
        var parent = $(obj).parents(".attr-row");
        if ($(obj).val() == "img") {
            parent.find(".img_file").css("display", "flex");
            parent.find(".img_link").hide();
        } else {
            parent.find(".img_file").css("display", "none");
            parent.find(".img_link").show();
        }
    }

    function uploadImage(obj) {
        var parent = $(obj).parents(".attr-row");
        //var item_val = parent.find("input[name=repo_item_value]");
        var item_file = parent.find("input[name=repo_item_file]")[0];
        var formData = new FormData();
        formData.append('method', 'uploadImage');
        formData.append('uploadFile', item_file.files[0]);
        $.ajax({
            type: "POST",
            url: "/ajax/ajax.report.php",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                //item_val.val(data);
                $(obj).attr('data-file', data);
            }
        });
    }

    function clone_item(obj) {
        var parent = $(obj).parents(".form-wrap");
        var order = parent.data("index");
        var section = $(obj).parents("section");
        $.fn.reverse = [].reverse;
        section.find('.form-wrap').reverse().each(function() {
            var index = $(this).data('index');
            var newIndex = index + 1;
            if (index > order) {
                $(this).data("index", newIndex);
                $(this).find("input[name^=item_type_]").each(function() {
                    $(this).attr("name", "item_type_" + newIndex);
                });
            }
        });
        var child = parent.clone();
        var childOrder = order + 1;
        child.data("index", childOrder);
        child.find("input[name^=item_type_]").each(function() {
            $(this).attr("name", "item_type_" + childOrder);
        });
        child.insertAfter(parent);
    }

    function del_item(obj) {
        var parent = $(obj).parents(".form-wrap");
        var order = parent.data("index");
        var section = $(obj).parents("section");
        var count = 0;
        if (order == 0) {
            section.find('.form-wrap').each(function() {
                var index = $(this).data('index');
                if (index > order) {
                    count++;
                }
            });
        }
        if (count == 0 && order == 0) {
            alert("삭제할수 없습니다.");
            return;
        }
        section.find('.form-wrap').each(function() {
            var index = $(this).data('index');
            var newIndex = index - 1;
            if (index > order) {
                $(this).data("index", newIndex);
                $(this).find("input[name^=item_type_]").each(function() {
                    $(this).attr("name", "item_type_" + newIndex);
                });
            }
        });
        parent.remove();
    }

    function item_up(obj) {
        var parent = $(obj).parents(".form-wrap");
        var order = parent.data("index");
        if (order == 0) {
            alert("이동할수 없습니다.");
            return;
        }
        parent.find("input[name^=item_type_]").each(function() {
            $(this).attr("name", "item_type_" + "_");
        });
        var dst = null;
        var section = $(obj).parents("section");
        section.find('.form-wrap').each(function() {
            var index = $(this).data('index');
            if (index == order - 1) {
                dst = $(this);
                dst.data("index", order);
                $(this).find("input[name^=item_type_]").each(function() {
                    $(this).attr("name", "item_type_" + order);
                });
            }
        });
        var child = parent.clone();
        var childOrder = order - 1;
        child.data("index", childOrder);
        child.find("input[name^=item_type_]").each(function() {
            $(this).attr("name", "item_type_" + childOrder);
        });
        child.insertBefore(dst);
        parent.remove();
    }

    function item_down(obj) {
        var parent = $(obj).parents(".form-wrap");
        var order = parent.data("index");
        var section = $(obj).parents("section");
        var count = 0;
        section.find('.form-wrap').each(function() {
            var index = $(this).data('index');
            if (index > order) {
                count++;
            }
        });
        if (count == 0) {
            alert("이동할수 없습니다.");
            return;
        }
        parent.find("input[name^=item_type_]").each(function() {
            $(this).attr("name", "item_type_" + "_");
        });
        var dst = null;
        section.find('.form-wrap').each(function() {
            var index = $(this).data('index');
            if (index == order + 1) {
                dst = $(this);
                dst.data("index", order);
                $(this).find("input[name^=item_type_]").each(function() {
                    $(this).attr("name", "item_type_" + order);
                });
            }
        });
        var child = parent.clone();
        var childOrder = order + 1;
        child.data("index", childOrder);
        child.find("input[name^=item_type_]").each(function() {
            $(this).attr("name", "item_type_" + childOrder);
        });
        child.insertAfter(dst);
        parent.remove();
    }
</script>