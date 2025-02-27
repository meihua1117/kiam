<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
$repo_id = $_GET['repo'];
$sql = "select * from gn_report_form where id=$repo_id";
$res = mysqli_query($self_con, $sql);
$row = mysqli_fetch_array($res);
?>
<style>
    .report_item {
        width: 100%;
        margin-top: 10px;
        padding-left: 20px;
        padding-right: 20px;
        display: table;
    }

    .report_item:last-child {
        margin-bottom: 20px;
    }

    .report_item_title {
        writing-mode: vertical-lr;
        text-orientation: upright;
        color: #ffffff;
        background-color: #7f7f7f;
        font-size: 16px;
        margin-bottom: 5px;
        padding: 5px 8px;
        display: table-cell;
        text-align: center;
    }

    .report_item_div {
        width: 100%;
        padding-left: 10px;
        display: table-cell;
        vertical-align: top;
    }

    .report_item_body:last-child {
        margin-bottom: 0;
    }

    .report_item_body {
        width: 100%;
        display: table;
        margin-bottom: 0px;
    }

    .report_item_tag {
        display: table-cell;
        font-size: 15px;
        background-color: #bfbfbf;
        width: 30%;
        padding-left: 10px;
    }

    .report_item_tag1 {
        display: table-cell;
        font-size: 15px;
        width: 100%;
        text-align: left;
        padding: 5px 10px;
    }

    .report_item_tag1:first-line {
        line-height: 0px;
    }

    .report_item_tag2 {
        display: block;
        font-size: 15px;
        width: 100%;
        text-align: left;
        border: 1px solid #ddd;
        padding: 5px 10px;
        background-color: #bfbfbf;
    }

    .report_item_tag_val {
        display: table-cell;
        padding-left: 5px;
        font-size: 15px;
        width: 70%;
    }

    .report_item_input {
        width: 100%;
        height: 28px;
        padding: 5px 10px;
        background-color: #fff;
        border: 1px solid #ccc;
        font-size: 12px;
        line-height: 16px;
    }

    .report_item_container {
        width: 100%;
        height: 100%;
        padding: 5px 10px;
        background-color: #fff;
        border: 1px solid #ccc;
        font-size: 12px;
    }

    .kbw-signature {
        width: 100%;
        height: 100px;
        background-color: #f1f1f1;
        display: inline-block;
        border: 1px solid #eee;
        -ms-touch-action: none;
    }

    #apply {
        max-width: 600px;
        margin: 0 auto;
        align: center;
    }

    #apply .inner-wrap>.title {
        padding: 20px 0 20px 0;
        margin-bottom: 35px;
        font-size: 24px;
        font-weight: 1500;
        text-align: center;
        color: #FAFAFA;
        line-height: 30px;
        font-style: normal;
        background-color: #848484;
    }

    #apply .inner-wrap .input-field .title {
        position: relative;
        padding: 10px 20px;
        background-color: #e5e5e5;
        border-radius: 15px;
        font-size: 16px;
        font-weight: 500;
        line-height: 20px;
        margin-top: 20px;
        margin-bottom: 10px;
    }

    #apply .inner-wrap .input-field .form-wrap {
        padding: 15px 10px;
        background-color: #fff;
        border: 1px solid #eee;
    }

    #apply .inner-wrap .input-field .form-wrap .attr-row {
        display: table;
        width: 100%;
        margin-bottom: 10px;
    }

    #apply .inner-wrap .input-field .form-wrap .attr-row>* {
        display: table-cell;
        vertical-align: middle;
    }

    #apply .inner-wrap .input-field .form-wrap .attr-row .attr-name {
        width: 15%;
        font-size: 14px;
        font-weight: 500;
    }

    #apply .inner-wrap .input-field .form-wrap .attr-row .attr-value {
        width: -webkit-calc(100% - 15%);
        width: calc(100% - 15%);
        padding-left: 5px;
    }

    #apply .inner-wrap {
        padding: 0px 0 40px 0 !important;
    }

    #apply .inner-wrap .agreement-field .agreement-wrap {
        padding: 15px 10px;
        background-color: #fff;
        border: 1px solid #eee;
        font-size: 24px;
    }

    #apply .inner-wrap .agreement-field .agreement-wrap .agreement-item:last-child {
        margin-bottom: 0;
    }

    #apply .inner-wrap .agreement-field .agreement-wrap .agreement-item {
        margin-bottom: 10px;
        font-size: 18px;
    }

    #apply .inner-wrap .agreement-field .agreement-wrap .agreement-item .check-wrap {
        float: left;
    }

    #apply .inner-wrap .agreement-field .agreement-wrap .agreement-item a {
        float: right;
        display: block;
        width: 100px;
        background-color: #bbb;
        color: #fff;
        font-size: 12px;
        line-height: 20px;
        text-align: center;
    }

    #apply .inner-wrap .button-wrap {
        margin-top: 30px;
        font-size: 32px;
        text-align: center;
    }

    #apply .inner-wrap .button-wrap .button {
        display: inline-block;
        width: 340px;
        height: 40px !important;
        margin: 0 5px;
        padding: 2px 0;
        color: #fff;
        font-size: 32px;
        font-weight: 500;
        line-height: 20px;
        text-align: center;
    }
</style>
<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!--오픈그래프 (웹페이지 미리보기 -페이스북, 카카오톡)-->
    <meta property="og:title" content="<?= $row['title'] ?>"> <!--제목-->
    <meta property="og:description" content="<?= $row['descript'] ?>"> <!--내용-->
    <!--오픈그래프 끝-->
    <title>아이엠 하나이면 홍보와 소통이 가능하다</title>
    <link rel="shortcut icon" href="img/common/iconiam.ico">
    <link rel="stylesheet" href="css/notokr.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/new_style.css">
    <link rel="stylesheet" href="css/grid.min.css">
    <link rel="stylesheet" href="css/slick.min.css">
    <link rel="stylesheet" href="css/style_j.css">
    <!-- ########## TODO COMMENT FOR TEST  패치할떄 해제해야함 ###########  -->
    <!--script src="//developers.kakao.com/sdk/js/kakao.min.js" charset="utf-8"></script-->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/main.js"></script>
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <link rel='stylesheet' href='../plugin/toastr/css/toastr.css' />
    <script src='../plugin/toastr/js/toastr.min.js'></script>
    <script src="js/jquery-ui-1.10.3.custom.js"></script>
    <script src="js/jquery-signature.js"></script>
    <script src="js/jquery.report_form.js"></script>
</head>

<body>
    <div id="wrap" class="common-wrap" style="padding:0px">
        <main id="star" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
            <section id="bottom">
                <div class="content-item">
                    <div style="margin-top: 20px;margin-left: 20px">
                        <h2 class="title"><?= $row['title'] ?></h2>
                    </div>
                    <div style="margin-top: 10px;margin-left: 20px;margin-right: 20px;">
                        <h4 class="desc" style="white-space: pre-line;"><?= $row['descript'] ?></h4>
                    </div>
                    <?
                    $sql1 = "select * from gn_report_form1 where form_id=$repo_id order by item_order";
                    $res1 = mysqli_query($self_con, $sql1);
                    while ($row1 = mysqli_fetch_array($res1)) {
                        if ($row1['item_req']) { ?>
                            <div style="width:100%;margin-top:20px">
                                <div class="report_item_tag" style="width: 100%;text-align: left;padding:5px 10px;display:block">
                                    <?= $row1['item_req']; ?>
                                </div>
                            </div>
                        <? } ?>
                        <div class="report_item">
                            <? if ($row1['item_title']) {
                                $style = ""; ?>
                                <div class="report_item_title">
                                    <?= $row1['item_title'] ?>
                                </div>
                            <? } else {
                                $style = "padding-left:0px;";
                            } ?>
                            <div class="report_item_div" style="<?= $style ?>">
                                <?
                                $sql2 = "select * from gn_report_form2 where form_id=$repo_id and item_id = {$row1['id']} order by id";
                                $res2 = mysqli_query($self_con, $sql2);
                                while ($row2 = mysqli_fetch_array($res2)) {
                                    if ($row1['item_type'] == 0) {
                                ?>
                                        <div class="report_item_body">
                                            <div class="report_item_tag">
                                                <?= $row2['tag_name'] ?>
                                            </div>
                                            <div class="report_item_tag_val">
                                                <input type="text" class="report_item_input">
                                            </div>
                                        </div>
                                    <?  } else if ($row1['item_type'] == 1) { ?>
                                        <div class="report_item_body">
                                            <div class="report_item_tag1">
                                                <input type="checkbox" style="margin: 2px 0px">
                                                <?= $row2['tag_name'] ?>
                                            </div>
                                        </div>
                                    <?  } else if ($row1['item_type'] == 3) { ?>
                                        <div class="report_item_body">
                                            <div class="report_item_tag2">
                                                <?= $row2['tag_name'] ?>
                                            </div>
                                            <div class="report_item_tag2" style="background-color: transparent;margin-top: 5px;padding: 0px">
                                                <input type="text" class="report_item_input" style="border: none">
                                            </div>
                                        </div>
                                    <?  } else {
                                        $pre_style = "";
                                        if ($row2['tag_name'] != "")
                                            $pre_style = "white-space: pre-line";
                                    ?>
                                        <div class="report_item_body" style="min-height: 100">
                                            <div class="report_item_tag1" style="vertical-align: top;<?= $pre_style ?>;padding:0px;border:none">
                                                <?= $row2['tag_name'] ?>
                                                <br>
                                                <?
                                                $img = $row2['tag_img'];
                                                $link = $row2['tag_link'];
                                                $pos = strpos($row2['tag_img'], "youtu");
                                                //$pos_img = stripos($row2['tag_img'],".jpg") + stripos($row2['tag_img'],".jpeg") +stripos($row2['tag_img'],".png") +stripos($row2['tag_img'],".gif") +stripos($row2['tag_img'],".svc");
                                                if ($pos >= 0) {
                                                    if (strpos($row2['tag_img'], "youtu.be") != false) {
                                                        $img = str_replace("youtu.be/", "www.youtube.com/embed/", $img);
                                                    } else if (strpos($row2['tag_img'], "playlist") != false) {
                                                        $code = substr($row2['tag_img'], strpos($row2['tag_img'], "playlist") + 14);
                                                        $img = "https://www.youtube.com/embed/?listType=playlist&list=$code";
                                                    } else if (strpos($row2['tag_img'], "watch?v=") != false) {
                                                        $img = str_replace("watch?v=", "embed/", $img);
                                                    }
                                                }
                                                if ($pos) { ?>
                                                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;">
                                                        <iframe style="width:100%;height:300px;border-radius: 10px;" src="<?= $img ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                                                        </iframe>
                                                    </div>
                                                <? } else if ($img == "") { ?>
                                                    <a href="<?= $link ?>" target="_blank"><?= $link ?></a>
                                                <? } else if ($link == "") { ?>
                                                    <img src="<?= $img ?>" style="width:100%">
                                                <? } else { ?>
                                                    <img src="<?= $img ?>" style="width:100%" onclick="window.open('<?= $link ?>')">
                                                <? } ?>
                                            </div>
                                        </div>
                                <?  }
                                } ?>
                            </div>
                        </div>
                    <? } ?>
                    <? if ($row['sign_visible'] == 1) { ?>
                        <div class="report_item">
                            <div id="signature-pad" class="m-signature-pad">
                                <p class="marb3"><strong class="blink">아래 박스안에 서명을 남겨주세요.</strong><button type="button" id="clear" class="btn_ssmall bx-white">서명 초기화</button></p>
                                <div id="sign"></div>
                                <textarea name="signatureJSON" id="signatureJSON" style="display: none" readonly=""></textarea>
                            </div>
                        </div>
                    <? }
                    if ($row['request_yn'] == 'Y' && $row['pcode']) { //상세보기형이면
                        $sql = "SELECT * FROM Gn_event WHERE event_idx='{$row['pcode']}'";
                        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
                        $event_data = $row = mysqli_fetch_array($result);
                    ?>
                        <div id="apply" class="common-wrap" style="text-align:center;margin-top: 20px;max-width:600px;">
                            <!-- 신청하기 -->
                            <div class="container" style="width: 100%;">
                                <div class="row" style="margin: 0px;">
                                    <div class="col-12">
                                        <div class="inner-wrap">
                                            <h2 class="title">
                                                <? if ($event_data['event_title'] != "") { ?>
                                                    <?= $event_data['event_title']; ?>
                                                <? } else { ?>
                                                    신청하기
                                                <? } ?>
                                            </h2>
                                            <section class="input-field">
                                                <h3 class="title">기본정보 입력</h3>
                                                <? if (strstr($event_data['event_info'], "join")) { ?>
                                                    <div class="utils clearfix">
                                                        <input type="radio" name="mem_type" id="no_mem" checked>
                                                        <label for="no_mem" value="none_mem" style="font-size:17px;">비회원</label>
                                                        <input type="radio" name="mem_type" id="iam_mem" style="margin-left:10px;">
                                                        <label for="iam_mem" value="iam_mem_ori" style="font-size:17px;">IAM기존회원</label>
                                                        <br>
                                                        <span class="main_info" id="no_txt">
                                                            신청정보의 수정/취소를 위해서는 확인코드(6-10자)를 꼭 입력해주세요.
                                                            비번은 휴대폰 뒷4자리로 자동 설정됩니다.
                                                        </span>
                                                        <!-- <span class="main_info" id="iam_txt" hidden>계정을 입력하시면 자동으로 입력됩니다.</span> -->
                                                    </div>
                                                <?  }
                                                if (strstr($event_data['event_info'], "join")) { ?>
                                                    <input type="hidden" name="join_yn" value="Y">
                                                    <br>
                                                <? } else { ?>
                                                    <input type="hidden" name="join_yn" value="N">
                                                <? } ?>
                                                <div class="form-wrap">
                                                    <? if (strstr($event_data['event_info'], "join")) { ?>
                                                        <div id="div_account" style="display:block;">
                                                            <div class="attr-row ">
                                                                <div class="attr-name">신청확인코드</div>
                                                                <div class="attr-value">
                                                                    <div class="input-wrap">
                                                                        <input type="text" id="id" name="id" value="<?= $_POST['id'] ?>" placeholder="영문 또는 숫자 6-10자로 입력하세요." style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;" />
                                                                        <input type="button" style="margin: 5px;" value="중복확인" class="button is-grey" onClick="id_check(dform1,'dform1')" />
                                                                        <span id='id_html' style="width: 60px;"></span>
                                                                        <input type="hidden" name="id_status" value="<?= $_POST['id_status'] ?>" itemname='확인코드중복확인' required />
                                                                        &nbsp;&nbsp; <p id="id_chk_str" style="display: inline-block;">※중복확인 꼭 클릭하세요</p>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <? } ?>
                                                    <div class="attr-row is-account">
                                                        <div class="attr-name">이름</div>
                                                        <div class="attr-value">
                                                            <div class="input-wrap">
                                                                <input type="text" name="name" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
                                                                    id="name" value="<?= $_POST['name'] ?>" />

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="attr-row is-phone">
                                                        <div class="attr-name">휴대폰</div>
                                                        <div class="attr-value">
                                                            <div class="input-wrap">
                                                                <? if (strstr($event_data['event_info'], "sms")) {
                                                                    $width = "50%";
                                                                } else {
                                                                    $width = "90%";
                                                                } ?>
                                                                <input type="tel" name="mobile" style="width:<?= $width ?>; height:40px; border:1px #cacaca solid;font-size: 14px;"
                                                                    id="tel" onblur="checkPhon()" value="<?= $_POST['mobile'] ?>" placeholder="'-'를 빼고 입력(예 : 01012345678)" />
                                                                <? if (strstr($event_data['event_info'], "sms")) { ?>
                                                                    <input type="button" value="인증번호 받기" class="button" onclick="chk_sms()" style=" height:42px; border:1px #cacaca solid;font-size: 14px;">
                                                                <? } ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <? if (strstr($event_data['event_info'], "sms")) { ?>
                                                        <div id="phone_verify" style="display: block;">
                                                            <div class="attr-row is-phone">
                                                                <div class="attr-name">인증번호</div>
                                                                <div class="attr-value">
                                                                    <div class="input-wrap">
                                                                        <input type="text" name="rnum" id="rnum" itemname='인증번호' maxlength="10" style="width:50%; height:40px; border:1px #cacaca solid;font-size: 14px;" />
                                                                        <input type="button" value="인증번호 확인" class="button" onclick="chk_sms1()" style=" height:42px; border:1px #cacaca solid;font-size: 14px;">
                                                                        <span id="check_sms"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <? }
                                                    if (strstr($event_data['event_info'], "sex")) { ?>
                                                        <div class="attr-row">
                                                            <div class="attr-name">성별</div>
                                                            <div class="attr-value">
                                                                <div class="input-wrap" style="font-size: 14px;">
                                                                    <input type="radio" name="sex" value="m" checked> 남
                                                                    <input type="radio" name="sex" value="f"> 여
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <? }
                                                    if (strstr($event_data['event_info'], "email")) { ?>
                                                        <div class="attr-row is-mail">
                                                            <div class="attr-name">이메일</div>
                                                            <div class="attr-value">
                                                                <div class="input-wrap">
                                                                    <input type="text" onblur="email_check()" name="email" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
                                                                        id="email" value="<?= $_POST['email'] ?>" onKeyUp="func_check('mem_email',this.value)" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <? }
                                                    if (strstr($event_data['event_info'], "job")) { ?>
                                                        <div class="attr-row">
                                                            <div class="attr-name">소속/직업</div>
                                                            <div class="attr-value">
                                                                <div class="input-wrap">
                                                                    <input type="text" name="job" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
                                                                        id="job" value="<?= $_POST['job'] ?>" placeholder="/로 소속과 직업 분리 입력해요" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <? }
                                                    if (strstr($event_data['event_info'], "address")) { ?>
                                                        <div class="attr-row">
                                                            <div class="attr-name">주소</div>
                                                            <div class="attr-value">
                                                                <div class="input-wrap">
                                                                    <input type="text" name="addr" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
                                                                        id="addr" value="" placeholder="이벤트에 필요하니 정확히 입력해요" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <? }
                                                    if (strstr($event_data['event_info'], "birth")) { ?>

                                                        <div class="attr-row">
                                                            <div class="attr-name">출생년도</div>
                                                            <div class="attr-value">
                                                                <div class="input-wrap">
                                                                    <input type="text" name="birthday" style="width:90%; height:40px; border:1px #cacaca solid;font-size: 14px;"
                                                                        id="birthday" value="" placeholder="출생년도만 입력하세요" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <? }
                                                    if (strstr($event_data['event_info'], "other")) { ?>
                                                        <div class="attr-row">
                                                            <div class="attr-name">기타</div>
                                                            <div class="attr-value">
                                                                <div class="input-wrap">
                                                                    <textarea name="other" style="width:90%; height:40px; border:1px #cacaca solid; font-size:14px;"
                                                                        id="other" value="" placeholder="<?= $event_data['event_req_link'] ?>"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <? } ?>
                                                    <div class="attr-row is-account">
                                                        <div class="attr-name">파일등록<span style="margin-left:10px;font-weight: bold;cursor:pointer" onclick="add_file_tab();">+</span></div>
                                                        <div class="attr-value"><?= "(pdf,csv,jpg,png,txt,json,xlsx 파일 " . $event_data['file_cnt'] . "개까지 업로드 가능)" ?></div>
                                                    </div>
                                                    <? for ($i = 1; $i <= $event_data['file_cnt']; $i++) { ?>
                                                        <div class="attr-row is-account ai" style="display: none;" id="<?= 'ai_file_div' . $i ?>">
                                                            <div style="display: flex;justify-content: center;">
                                                                <span style="margin-right: 10px;"><?= "파일" . $i ?></span>
                                                                <input type="file" id="<?= 'ai_file' . $i ?>" name="<?= 'ai_file' . $i ?>" accept=".jpg,.jpeg,.png,.csv,.pdf,.txt,.json,.xlsx" style="height:auto !important;border:none;padding: 0px;border-radius: unset;">
                                                                <span style="font-weight: bold;cursor:pointer" onclick="del_file_tab(<?= $i ?>);">X</span>
                                                            </div>
                                                        </div>
                                                    <? } ?>
                                                    <div class="attr-row is-account">
                                                        <div class="attr-name">신청<br>행사</div>
                                                        <div class="attr-value">
                                                            <div class="input-wrap" style="font-size: 14px;">
                                                                <?= $event_data['event_name_kor']; ?>
                                                                <input type="hidden" name="sp" style="width:90%; height:40px; border:1px #cacaca solid;" id="sp" value="<?= $event_data['pcode'] ?>"
                                                                    <?= $readonly ?> />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="apply inner-wrap agreement-field">
                                                        <div class="agreement-wrap">
                                                            <div class="agreement-item" style="height: 20px;">
                                                                <div class="check-wrap">
                                                                    <input type="checkbox" name="agree" id="agree" value="Y">
                                                                    <label for="agree" style="font-size: 14px;">개인정보이용동의</label>
                                                                </div>
                                                                <a href="#" onclick="newpop_('terms.php')">전문보기</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <? if ($event_data['event_desc']) { ?>
                                                        <div class="agreement-field">
                                                            <div class="agreement-wrap">
                                                                <div class="agreement-item">
                                                                    <?= nl2br($event_data['event_desc']); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <? } ?>
                                                    <div class="button-wrap">
                                                        <input src="/event/pop_btn_regist3.png" type="image" class="button is-grey" value="신청하기" onclick="send_request();" />
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- // 신청하기 끝 -->
                    <? } ?>
            </section>
        </main>
    </div>
</body>
<script>
    function add_file_tab() {
        if ($("#ai_file_div1").css('display') == "none")
            $("#ai_file_div1").show();
        else if ($("#ai_file_div2").css('display') == "none")
            $("#ai_file_div2").show();
        else if ($("#ai_file_div3").css('display') == "none")
            $("#ai_file_div3").show();
    }

    function del_file_tab(idx) {
        if (idx == 3) {
            $("#ai_file_div3").hide();
            $("#ai_file3").val('');
        } else if (idx == 2) {
            if ($("#ai_file_div3").css('display') == "none") {
                $("#ai_file_div2").hide();
                $("#ai_file2").val('');
            } else {
                if ($("#ai_file3")[0].files.length > 0) {
                    var file = $("#ai_file3")[0].files[0];
                    var dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    $("#ai_file2")[0].files = dataTransfer.files;
                }
                $("#ai_file3").val('');
                $("#ai_file_div3").hide();
            }
        } else {
            if ($("#ai_file_div2").css('display') == "none") {
                $("#ai_file_div1").hide();
                $("#ai_file1").val('');
            } else {
                if ($("#ai_file_div3").css('display') == "none") {
                    if ($("#ai_file2")[0].files.length > 0) {
                        var file = $("#ai_file2")[0].files[0];
                        var dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        $("#ai_file1")[0].files = dataTransfer.files;
                    }
                    $("#ai_file2").val('');
                    $("#ai_file_div2").hide();
                } else {
                    if ($("#ai_file2")[0].files.length > 0) {
                        var file = $("#ai_file2")[0].files[0];
                        var dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        $("#ai_file1")[0].files = dataTransfer.files;
                    }
                    if ($("#ai_file3")[0].files.length > 0) {
                        var file = $("#ai_file3")[0].files[0];
                        var dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        $("#ai_file2")[0].files = dataTransfer.files;
                    }
                    $("#ai_file3").val('');
                    $("#ai_file_div3").hide();
                }
            }
        }
    }
</script>

</html>