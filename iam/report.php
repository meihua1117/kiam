<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
$currentUrl = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$_GET['repo'] = getQueryParam('repo', $currentUrl);
$repo_id = $_GET['repo'];
$params = explode("&", $repo_id);
$repo_id = $params[0];
$meta_img = "";
$sql = "select * from gn_report_form where id=$repo_id";
$res = mysqli_query($self_con, $sql);
$row = mysqli_fetch_array($res);
if ($row['status'] == 0) {
    echo "<script>alert('노출승인되지 않은 리포트입니다.');location='/';</script>";
}
$sql = "update gn_report_form set visit = visit + 1 where id=$repo_id";
mysqli_query($self_con, $sql);
$url_refer = str_replace("&", "###", $_SERVER['REQUEST_URI']);
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
        border: 1px solid #ddd;
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

    .button-wrap {
        margin-top: 30px;
        font-size: 0;
        text-align: center;
        margin-bottom: 30px;
    }

    .button {
        display: inline-block;
        width: 120px;
        margin: 0 5px;
        padding: 5px 0;
        color: #fff;
        font-size: 16px;
        font-weight: 500;
        line-height: 20px;
        text-align: center;
    }

    .is-pink {
        background-color: #92d050;
    }

    .is-grey {
        background-color: #bbb;
    }

    .ids {
        padding: 16px 20px;
        border-bottom: 1px solid #ddd;
    }

    .ids_radio {
        margin: 0px !important;
        height: 14px !important;
    }

    .autologin .check:checked~label:before {
        background-color: #ff0066;
        border-color: #ff0066;
    }

    .autologin .check~label:before {
        content: '';
        position: absolute;
        top: 2px;
        left: 0;
        width: 18px;
        height: 18px;
        background-color: #fff;
        border: 1px solid #ccc;
    }

    .autologin .check:checked~label:after {
        content: '\f00c';
        position: absolute;
        top: 2px;
        left: 2px;
        color: #fff;
        font-family: 'Fontawesome';
        font-size: 13px;
    }

    .login-button {
        width: 100%;
        padding: 12px 0;
        background-color: #ff0066;
        border: 0;
        color: #fff;
        font-size: 18px !important;
        font-weight: 500 !important;
    }
</style>
<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
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
    <link rel="stylesheet" href="css/iam.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/main.js"></script>
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <link rel='stylesheet' href='../plugin/toastr/css/toastr.css' />
    <script src='../plugin/toastr/js/toastr.min.js'></script>
    <script src="js/jquery-ui-1.10.3.custom.js"></script>
    <script src="js/jquery-signature.js"></script>
    <script src="js/jquery.report_form.js"></script>
    <script src="js/jquery.ui.touch-punch-improved.js"></script>
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
                    $sql1 = "select * from gn_report_form1 where form_id={$repo_id} order by item_order";
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
                                $sql2 = "select * from gn_report_form2 where form_id={$repo_id} and item_id = {$row1['id']} order by id";
                                $res2 = mysqli_query($self_con, $sql2);
                                while ($row2 = mysqli_fetch_array($res2)) {
                                    if ($row1['item_type'] == 0) {
                                ?>
                                        <div class="report_item_body">
                                            <div class="report_item_tag">
                                                <?= $row2['tag_name'] ?>
                                            </div>
                                            <div class="report_item_tag_val">
                                                <input type="text" class="report_item_input" name="<?= $row2['tag_id'] ?>">
                                            </div>
                                        </div>
                                    <?  } else if ($row1['item_type'] == 1) { ?>
                                        <div class="report_item_body">
                                            <div class="report_item_tag1">
                                                <input type="checkbox" class="report_item_check" style="margin: 2px 0px" name="<?= $row2['tag_id'] ?>">
                                                <?= $row2['tag_name'] ?>
                                            </div>
                                        </div>
                                    <?  } else if ($row1['item_type'] == 3) { ?>
                                        <div class="report_item_body">
                                            <div class="report_item_tag2">
                                                <?= $row2['tag_name'] ?>
                                            </div>
                                            <div class="report_item_tag2" style="background-color: transparent;margin-top: 5px;padding: 0px">
                                                <textarea class="report_item_input" name="<?= $row2['tag_id'] ?>" style="border: none;height: auto"><?= $repo_row[$row2['tag_id']] ?></textarea>
                                            </div>
                                        </div>
                                    <?  } else {
                                        $pre_style = "";
                                        if ($row2['tag_name'] != "")
                                            $pre_style = "white-space: pre-line";
                                    ?>
                                        <div class="report_item_body" style="min-height: 100px">
                                            <div class="report_item_tag1" style="vertical-align: top;<?= $pre_style ?>;padding:0px;border:none">
                                                <?= $row2['tag_name'] ?>
                                                <br>
                                                <?
                                                $link = $row2['tag_link'];
                                                $img = $row2['tag_img'];
                                                $pos = strpos($row2['tag_img'], "youtu");
                                                //$pos_img = stripos($row2['tag_img'], ".jpg") + stripos($row2['tag_img'], ".jpeg") + stripos($row2['tag_img'], ".png") + stripos($row2['tag_img'], ".gif") + stripos($row2['tag_img'], ".svc");
                                                if ($pos >= 0) {
                                                    if (strpos($row2['tag_img'], "youtu.be") != false) {
                                                        $img = str_replace("youtu.be/", "www.youtube.com/embed/", $img);
                                                    } else if (strpos($row2['tag_img'], "playlist") != false) {
                                                        $code = substr($row2['tag_img'], strpos($row2['tag_img'], "playlist") + 14);
                                                        $img = "https://www.youtube.com/embed/?listType=playlist&list=$code";
                                                    } else if (strpos($row2['tag_img'], "watch?v=") != false) {
                                                        $img = str_replace("watch?v=", "embed/", $img);
                                                    } else if (strpos($row2['tag_img'], "shorts/") != false) {
                                                        $img = str_replace("shorts/", "embed/", $img);
                                                    }
                                                }
                                                if ($pos) { ?>
                                                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px">
                                                        <iframe style="width:100%;height:300px;border-radius: 10px;" src="<?= $link ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                                                        </iframe>
                                                    </div>
                                                <? } else if ($img == "") { ?>
                                                    <a href="<?= $link ?>" target="_blank"><?= $link ?></a>
                                                <? } else if ($link == "") { ?>
                                                    <img src="<?= $img ?>" style="width:100%">
                                                <? } else { ?>
                                                    <img src="<?= $img ?>" style="width:100%" onclick="window.open('<?= $link ?>')">
                                                <? } ?>
                                                <?/*else if ($img == "") { ?>
                                                    <a href="<?= $link ?>" target="_blank"><?= cut_str($link, 30) ?></a>
                                                <? } else if ($pos_img > 0) {
                                                    if ($meta_img == "") {
                                                        $meta_img = $link; ?>
                                                        <script>
                                                            // PHP 변수를 JavaScript로 전달
                                                            var ogImageUrl = "<?= $meta_img; ?>";
                                                            // 새로운 메타 태그 생성
                                                            var metaTag = document.createElement('meta');
                                                            metaTag.setAttribute('property', 'og:image');
                                                            metaTag.setAttribute('content', ogImageUrl);

                                                            // 메타 태그를 <head> 섹션에 추가
                                                            document.head.appendChild(metaTag);
                                                        </script>
                                                    <?  } ?>
                                                    <img src="<?= $link ?>" style="width:100%">
                                                    <!--meta property="og:image" content="<?= $link ?>"-->
                                                <? } */ ?>
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
                    <? } ?>
                    <div>
                        <hr style="text-align:center;border: solid 1px #92D050; width: 20%;">
                    </div>
                    <form id="dform1" name="dform1" method="post" action="<?= $SERVER['PHP_SELF'] ?>" onsubmit="return checkForm()">
                        <div class="report_item">
                            <input type="radio" name="mem_type" id="no_mem" <? if ($_SESSION['iam_member_id'] == "") echo "checked" ?>>
                            <label for="no_mem" style="font-size:17px;">비회원</label>
                            <input type="radio" name="mem_type" id="iam_mem" style="margin-left:10px;" <? if ($_SESSION['iam_member_id'] != "") echo "checked" ?>>
                            <label for="iam_mem" style="font-size:17px;">IAM기존회원</label>
                            <br>
                            <span class="main_info" id="no_txt"><text color="blue">신청정보의 수정/취소를 위해서는 아이디(6-10자)를 꼭 입력해요. 비번은 휴대폰 뒷 4자리로 자동 설정됩니다.</color></span>
                        </div>
                        <div class="report_item">
                            <div class="report_item_div" style="padding-left: 0px">
                                <div class="report_item_body" id="div_account">
                                    <div class="report_item_tag" style="width: 15%;background-color: #ffffff;color: #000000;text-align: left">아이디</div>
                                    <div class="report_item_tag_val" style="width: 85%">
                                        <div class="input-wrap">
                                            <input type="text" id="id" name="id" value="<?= $_SESSION['iam_member_id'] ?>" placeholder="영문 또는 숫자 6-10자로 입력하세요." style="width:100%; height:40px; border:1px #cacaca solid;font-size: 14px;" />
                                            <input type="button" class="button is-grey" style="margin: 5px;color: #000000" value="중복확인" onClick="id_check('dform1')" />
                                            <span id='id_html' style="width: 60px;"></span>
                                            <input type="hidden" name="id_status" id="id_status" value="" itemname='아이디중복확인' required />
                                            &nbsp;&nbsp; <p id="id_chk_str" style="display: inline-block;">※ 중복확인 꼭 클릭해주세요.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="report_item_body">
                                    <div class="report_item_tag" style="width: 15%;background-color: #ffffff;color: #000000;text-align: left">이름</div>
                                    <div class="report_item_tag_val" style="width: 85%">
                                        <input type="text" name="name" style="width:100%; height:40px; border:1px #cacaca solid;font-size: 14px;" required id="name" value="" />
                                    </div>
                                </div>
                                <div class="report_item_body">
                                    <div class="report_item_tag" style="width: 15%;background-color: #ffffff;color: #000000;text-align: left">휴대폰</div>
                                    <div class="report_item_tag_val" style="width: 85%">
                                        <input type="tel" name="mobile" style="width:100%; height:40px; border:1px #cacaca solid;font-size: 14px;" id="tel" onkeyup="checkPhoneNumber()" value="" placeholder="'-'를 빼고 입력(예 : 01012345678)" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="button-wrap">
                        <a href="javascript:save_format(<?= $repo_id ?>)" class="button is-pink">제출하기</a>
                        <a onclick="closeReport();return false;" class="button is-grey">취소하기</a>
                        <!--a href="javascript:signin();" class="button" style="background-color: #84dbd8" id="signBtn">확인하기</a-->
                    </div>
                    <div id="ajax_div" style="display:none"></div>
            </section>
        </main>
    </div>
</body>
<div id="id-modalwindow" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1100;top: 20px;height: 100%;overflow-x: hidden;overflow-y: auto;">
    <div class="modal-dialog" role="document" style="margin-bottom: 30px;width:330px;max-width : 500px;margin-left:auto;margin-right:auto;">
        <div class="modal-content" style="margin-right:0px;">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <div>
                    <label>내 아이디 선택</label>
                </div>
            </div>
            <div class="ids" style="text-align:center">아이디를 선택하세요.</div>
            <div class="modal-body popup-bottom" style="overflow-y:auto;padding:0px !important">
                <div class="ids">
                    <input type="radio" class="ids_radio"><label style="margin-left: 10px;">내 아이디</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-active btn-center" onclick="check_ids(<?= $repo_id ?>);">확인</button>
            </div>
        </div>
    </div>
</div>
<div id="login-modal" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1100;top: 20px;height: 100%;overflow-x: hidden;overflow-y: auto;">
    <div class="modal-dialog" role="document" style="margin-bottom: 30px;width:330px;max-width : 500px;margin-left:auto;margin-right:auto;">
        <div class="modal-content" style="margin-right:0px;">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <div>
                    <label>로그인 하기</label>
                </div>
            </div>
            <div class="modal-body popup-bottom" style="overflow-y:auto;padding:0px !important">
                <div class="ids" style="border-bottom: none;">
                    <div class="input-wrap">
                        <input type="text" class="input" style="width: 100%;" placeholder="아이디" name="one_id" id="one_id">
                    </div>
                    <div class="input-wrap" style="margin-top: 10px;">
                        <input type="password" class="input" style="width: 100%;" placeholder="비밀번호" name="one_pwd" id="one_pwd">
                    </div>
                    <div class="input-wrap" style="margin-top: 10px;height:44px;">
                        <div class="autologin" style="float: left;position:relative">
                            <input type="checkbox" class="check" id="autoLogin" checked style="visibility: hidden;">
                            <label for="autoLogin" style="margin-left: 5px;">자동로그인</label>
                        </div>
                        <div class="utils" style="float: right;">
                            <a href="#" class="personal">개인정보처리방침</a>
                        </div>
                    </div>
                    <div class="input-wrap" style="margin-top: 20px;">
                        <input type="button" class="login-button" value="로그인" onclick="login();">
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: center;border-top:none;">
                <div class="find-info">
                    <a href="join.php">회원가입</a> |
                    <a href="search_id.php">아이디/비밀번호 찾기</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#id").bind("keyup", function() {
        $(this).val($(this).val().toLowerCase());
    });

    function closeReport() {
        var new_window = open(location, '_self');
        new_window.close();
        return false;
    }

    function login() {
        if ($("#one_id").val() == "") {
            alert("아이디를 입력해주세요.");
            $("#one_id").focus();
        } else if ($("#one_pwd").val() == "") {
            alert("비밀번호를 입력해주세요.");
            $("#one_pwd").focus();
        }
        $.ajax({
            type: "POST",
            url: "/iam/ajax/login.ajax.php",
            dataType: "json",
            data: {
                one_id: $("#one_id").val(),
                one_pwd: $("#one_pwd").val()
            },
            success: function(data) {
                if(data.result == "success"){
                    $("#login-modal").modal("hide");
                    load_userinfo();
                }else if(data.message == "payment_error"){
                    window.open("/payment_pop.php?index="+data.code+"&type=user", "notice_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=600,height=350");
                }else if(data.message == "leave_error"){
                    alert("탈퇴한 회원아이디입니다.");
                }else if(data.message == "login_error"){
                    alert(data.code);
                }else if(data.message == "login_error_over"){
                    alert(data.code);
                    window.location.replace("search_id.php");
                }
            },
            error: function() {
                alert('로그인 실패');
            }
        });
    }

    function save_format(repo_id) {
        if ($("#no_mem").prop('checked')) {
            if ($("#no_mem").prop('checked') && $("#id_html").text() == '') {
                alert('아이디 중복확인을 해주세요.');
                return false;
            }
            if (!$("#id").val()) {
                alert("아이디를 입력해주세요.");
                $("#id").focus();
                return;
            }
            if (!$("#name").val()) {
                alert("이름을 입력해주세요.");
                $("#name").focus();
                return;
            }
            if (!$("#tel").val()) {
                alert("폰번호를 입력해주세요.");
                $("#tel").focus();
                return;
            }
            $.ajax({
                type: "POST",
                url: "/ajax/ajax.report.php",
                dataType: "json",
                data: {
                    method: "auto_login",
                    id: $("#id").val(),
                    name: $("#name").val(),
                    phone: $("#tel").val(),
                    repo: repo_id
                },
                success: function(data) {
                    $.ajax({
                        type: "POST",
                        url: "/admin/ajax/login_iamuser.php",
                        data: {
                            one_id: data.id,
                            mem_pass: data.pass,
                            mem_code: data.code
                        },
                        success: function() {
                            reg_report(repo_id);
                        },
                        error: function() {
                            alert('초기화 실패');
                        }
                    });
                }
            });
        } else {
            if (!$("#name").val()) {
                alert("이름을 입력해주세요.");
                $("#name").focus();
                return;
            }
            if (!$("#tel").val()) {
                alert("폰번호를 입력해주세요.");
                $("#tel").focus();
                return;
            }
            if ('<?= $_SESSION['iam_member_id'] ?>' == "") {
                $.ajax({
                    type: "POST",
                    url: "/ajax/ajax.report.php",
                    dataType: "json",
                    data: {
                        method: "get_matching_ids",
                        name: $("#name").val(),
                        phone: $("#tel").val()
                    },
                    success: function(data) {
                        $(".modal-body").empty();
                        var mCount = 0;
                        $.each(data, function(index, item) {
                            mCount++;
                            var html = "<div class=\"ids\">";
                            html += "<input type=\"radio\" name=\"mId\" class=\"ids_radio\" value=\"" + item.mem_id + "\">";
                            html += "<label style=\"margin-left: 10px;\">" + item.mem_id + "</label>";
                            html += "</div>";
                            $(".modal-body").append(html);
                        });
                        if (mCount == 0) {
                            var html = "<div class=\"ids\">";
                            //html += "<input type=\"radio\" name=\"mId\" class=\"ids_radio\" value=\"" + item.mem_id + "\">";
                            html += "<label style=\"margin-left: 10px;\">" + "입력한 이름과 휴대폰번호에 일치되는 아이디가 없습니다. 정확한 이름과 폰번호를 입력해주세요" + "</label>";
                            html += "</div>";
                            $(".modal-body").append(html);
                        }
                        $("#id-modalwindow").modal("show");
                    },
                    error: function(request, status, error) {
                        console.log('code: ' + request.status + "\n" + 'message: ' + request.responseText + "\n" + 'error: ' + error);
                    }
                });
            } else {
                reg_report(repo_id);
            }
        }
    }

    function check_ids(repo_id) {
        var count = $('input[name=mId]').length;
        if (count == 0) {
            $("#id-modalwindow").modal("hide");
        } else {
            var id = $("input[name=mId]:checked").val();
            if (id == undefined) {
                alert("아이디를 선택해주세요.");
                return;
            } else {
                localStorage.setItem('mId', id);
                $("#id").val(id);
                reg_report(repo_id);
            }
        }
    }

    function reg_report(repo_id) {
        var jsonObj = {};
        jsonObj["index"] = repo_id;
        var id = $("#id").val();
        var name = $('input[name =name]').val();
        var phone = $('input[name =mobile]').val();
        var sign = $('#signatureJSON').val();
        jsonObj["id"] = id;
        jsonObj["name"] = name;
        jsonObj["phone"] = phone;
        jsonObj["item"] = [];
        $(".report_item_input").each(function() {
            var item = {};
            item["key"] = $(this).prop("name");
            item["value"] = $(this).val();
            jsonObj["item"].push(item);
        });
        $(".report_item_check").each(function() {
            var item = {};
            item["key"] = $(this).prop("name");
            item["value"] = $(this).prop("checked") == true ? 1 : 0;
            jsonObj["item"].push(item);
        });
        var logout = '<?= $_SESSION['iam_member_id'] ?>' == "";
        var msg = "등록하시겠습니까?";
        if ($("#iam_mem").prop('checked') && logout)
            msg = "선택한 아이디로 로그인할까요? 비번은 직접 입력해야합니다.";
        if (confirm(msg)) {
            $.ajax({
                type: "POST",
                url: "/ajax/ajax.report.php",
                dataType: "json",
                data: {
                    method: "reg_report",
                    data: JSON.stringify(jsonObj),
                    sign: sign
                },
                success: function(data) {
                    clearLocalStorage();
                    if ($("#iam_mem").prop('checked') && logout) {
                        location.href = "/iam/login.php";
                    } else {
                        alert("성공적으로 등록되었습니다.");
                        location.href = "mypage_report_list.php";
                    }
                },
                error: function(request, status, error) {
                    console.log('code: ' + request.status + "\n" + 'message: ' + request.responseText + "\n" + 'error: ' + error);
                }
            });
        }
    }
    //아이디 중복확인
    function id_check(frm_str) {
        if (!$("#id").val()) {
            alert("아이디를 입력해주세요.");
            $("#id").focus();
            return;
        }
        if ($("#id").val().length < 4 || $("#id").val().length > 15) {
            alert('아이디는 4자~15자를 입력해주세요.');
            $("#id").focus();
            return;
        }
        var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
        if (!pattern.test($("#id").val())) {
            document.getElementById('id_html').innerHTML = '올바른 아이디 형식이 아닙니다.';
            $("#id_status").val('');
            $("#id").val('');
            $("#id").focus();
            return
        } else
            document.getElementById('id_html').innerHTML = '';
        $.ajax({
            type: "POST",
            url: "/ajax/ajax.php",
            data: {
                id_che: $("#id").val(),
                id_che_form: frm_str
            },
            success: function(data) {
                $("#ajax_div").html(data);
            }
        })
    }

    function load_userinfo() {
        $.ajax({
            type: "GET",
            url: "/ajax/event.userinfo.php",
            cache: false,
            dataType: "json",
            success: function(data) {
                if (data.result == "success") {
                    $("#id").val('<?= $_SESSION['iam_member_id'] ?>');
                    $('input[name =name]').val(data.card_name);
                    $('input[name =mobile]').val(data.card_phone);
                } else {
                    /*var data = saveRepoDataToLocalStorage();
                    data = CryptoJS.enc.Utf8.parse(data);
                    var ciphertext = CryptoJS.enc.Base64.stringify(data);
                    location.href = "/m/login.php?refer2=" + '<?= $url_refer ?>' + "///" + ciphertext;*/
                    $("#login-modal").modal("show");
                }
            }
        });
    }

    function checkPhoneNumber() {
        if ($('#tel').val().length >= 3) {
            if ($('#tel').val().substring(0, 3) != '010' && $('#tel').val().substring(0, 3) != '011' && $('#tel').val().substring(0, 3) != '016' && $('#tel').val().substring(0, 3) != '017' && $('#tel').val().substring(0, 3) != '018' && $('#tel').val().substring(0, 3) != '019') {
                alert('전화번호 첫번째 자리는 010, 011, 016, 017, 018, 019만 가능합니다.');
                $('#tel').val("");
                $('#tel').focus();

            }
        }
    }

    function gotoLogin(id, pass, code) {
        $.ajax({
            type: "POST",
            url: "/admin/ajax/login_iamuser.php",
            data: {
                one_id: id,
                mem_pass: pass,
                mem_code: code
            },
            success: function() {
                location.href = "/iam/mypage_report_list.php";
            },
            error: function() {
                alert('초기화 실패');
            }
        });
        return false;
    }
    $(function() {
        $("input[name=mem_type]").on("change", function() {
            if ($("#no_mem").prop('checked')) {
                $("#no_txt").show();
                $('input[name =name]').val('');
                $('input[name =mobile]').val('');
                $("#div_account").show();
                $("#signBtn").show();
            } else {
                $("#no_txt").hide();
                $("#div_account").hide();
                $("#signBtn").hide();
                load_userinfo();
            }
        });
        if ($("#no_mem").prop('checked')) {
            $("#no_txt").show();
            $('input[name =name]').val('');
            $('input[name =mobile]').val('');
            $("#div_account").show();
            $("#signBtn").show();
        } else {
            $("#no_txt").hide();
            $("#div_account").hide();
            $("#signBtn").hide();
        }
        if ('<?= $_SESSION['iam_member_id'] ?>' != "") {
            $("#no_txt").hide();
            $("#div_account").hide();
            $("#signBtn").hide();
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has("data")) {
                var ciphertext = urlParams.get("data");
                const decodedString = CryptoJS.enc.Base64.parse(ciphertext).toString(CryptoJS.enc.Utf8);
                loadRepoDataFromParam(decodedString);
            } else {
                loadRepoDataFromLocalStorage();
            }
            load_userinfo();
        }
        $('#sign').signature('enable').signature('draw', $('#signatureJSON').val());
    });
    // 비로그인 상태에서 정보를 저장하는 함수
    function saveRepoDataToLocalStorage() {
        var repo_id = <?= $repo_id ?>;
        var jsonObj = {};
        jsonObj["index"] = repo_id;
        var id = $("#id").val();
        var name = $('input[name =name]').val();
        var phone = $('input[name =mobile]').val();
        var sign = $('#signatureJSON').val();
        jsonObj["id"] = id;
        jsonObj["name"] = name;
        jsonObj["phone"] = phone;
        jsonObj["sign"] = sign;
        jsonObj["item"] = [];
        $(".report_item_input").each(function() {
            var item = {};
            item["key"] = $(this).prop("name");
            item["value"] = $(this).val();
            jsonObj["item"].push(item);
        });
        $(".report_item_check").each(function() {
            var item = {};
            item["key"] = $(this).prop("name");
            item["value"] = $(this).prop("checked") == true ? 1 : 0;
            jsonObj["item"].push(item);
        });
        // 로컬 스토리지에 데이터를 저장합니다.
        localStorage.setItem('repoData', JSON.stringify(jsonObj));
        return JSON.stringify(jsonObj);
    }

    // 로그인 후에 정보를 가져오는 함수
    function loadRepoDataFromLocalStorage() {
        // 로컬 스토리지에서 데이터를 가져옵니다.
        const data = localStorage.getItem('repoData');
        // 가져온 데이터가 있는 경우, 파싱하여 반환합니다.
        if (data) {
            var jsonObj = JSON.parse(data);
            var id = $("#id").val();
            $('input[name =name]').val(jsonObj["name"]);
            $('input[name =mobile]').val(jsonObj["phone"]);
            $('#signatureJSON').html(jsonObj["sign"]);
            $(".report_item_input").each(function() {
                var key = $(this).prop("name");
                var item = jsonObj["item"].find(item => item['key'] === key);
                if (item)
                    $(this).val(item['value']);
            });
            $(".report_item_check").each(function() {
                var key = $(this).prop("name");
                var item = jsonObj["item"].find(item => item['key'] === key);
                if (item) {
                    var value = item["value"] == 1 ? true : false;
                    $(this).prop("checked", value);
                }
            });
        }
        // 가져온 데이터가 없는 경우, null을 반환합니다.
        return null;
    }
    // 로컬 스토리지에서 저장된 내용을 삭제하는 함수
    function clearLocalStorage() {
        localStorage.removeItem('repoData');
    }

    function loadRepoDataFromParam(data) {
        // 가져온 데이터가 있는 경우, 파싱하여 반환합니다.
        if (data) {
            var jsonObj = JSON.parse(data);
            var id = $("#id").val();
            $('input[name =name]').val(jsonObj["name"]);
            $('input[name =mobile]').val(jsonObj["phone"]);
            $('#signatureJSON').html(jsonObj["sign"]);
            $(".report_item_input").each(function() {
                var key = $(this).prop("name");
                var item = jsonObj["item"].find(item => item['key'] === key);
                if (item)
                    $(this).val(item['value']);
            });
            $(".report_item_check").each(function() {
                var key = $(this).prop("name");
                var item = jsonObj["item"].find(item => item['key'] === key);
                if (item) {
                    var value = item["value"] == 1 ? true : false;
                    $(this).prop("checked", value);
                }
            });
        }
        // 가져온 데이터가 없는 경우, null을 반환합니다.
        return null;
    }
</script>

</html>