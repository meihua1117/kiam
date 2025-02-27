<?
$path = "./";
include_once "_head.php";
if (!$_SESSION['one_member_id']) {
?>
    <script language="javascript">
        location.replace('/ma.php');
    </script>
<?
    exit;
}
if (isset($_GET['event_idx'])) {
    $sql = "select * from Gn_event  where event_idx='{$_GET['event_idx']}'";
    $sresul_num = mysqli_query($self_con, $sql);
    $row = mysqli_fetch_array($sresul_num);
} else {
    $row['reserv_type'] = $member_1['ai_status'];
}
if($row['reserv_type'])
    $sql = "select reservation_title from Gn_aievent_ms_info where sms_idx='{$row['sms_idx1']}'";
else
    $sql = "select reservation_title from Gn_event_sms_info where sms_idx='{$row['sms_idx1']}'";
$sms_res = mysqli_query($self_con, $sql);
$sms_info = mysqli_fetch_array($sms_res);
$reservation_title1 = $sms_info[0];

if($row['reserv_type'])
    $sql = "select reservation_title from Gn_aievent_ms_info where sms_idx='{$row['sms_idx2']}'";
else
    $sql = "select reservation_title from Gn_event_sms_info where sms_idx='{$row['sms_idx2']}'";
$sms_res = mysqli_query($self_con, $sql);
$sms_info = mysqli_fetch_array($sms_res);
$reservation_title2 = $sms_info[0];

if($row['reserv_type'])
    $sql = "select reservation_title from Gn_aievent_ms_info where sms_idx='{$row['sms_idx3']}'";
else
    $sql = "select reservation_title from Gn_event_sms_info where sms_idx='{$row['sms_idx3']}'";
$sms_res = mysqli_query($self_con, $sql);
$sms_info = mysqli_fetch_array($sms_res);
$reservation_title3 = $sms_info[0];

$sql = "select event_title from Gn_event where event_idx='{$row['stop_event_idx']}'";
$sms_res = mysqli_query($self_con, $sql);
$sms_info = mysqli_fetch_array($sms_res);
$stop_title = $sms_info[0];

?>
<script>
    $(function() {
        $(".popbutton").click(function() {
            $('.ad_layer_info').lightbox_me({
                centered: true,
                onLoad: function() {}
            });
        })

    });
</script>
<style>
    .pop_right {
        position: relative;
        right: 2px;
        display: inline;
        margin-bottom: 6px;
        width: 5px;
    }

    .w200 {
        width: 200px
    }

    .list_table1 tr:first-child td {
        border-top: 1px solid #CCC;
    }

    .list_table1 tr:first-child th {
        border-top: 1px solid #CCC;
    }

    .list_table1 td {
        height: 40px;
        border-bottom: 1px solid #CCC;
    }

    .list_table1 th {
        height: 40px;
        border-bottom: 1px solid #CCC;
    }

    .list_table1 input[type=text] {
        width: 600px;
        height: 30px;
    }

    .ad_layer1 {
        width: 484px;
        height: 200px;
        background-color: #fff;
        border: 2px solid #24303e;
        position: relative;
        box-sizing: border-box;
        padding: 30px 30px 50px 30px;
        display: none;
    }

    .ad_layer2 {
        width: 484px;
        height: 200px;
        background-color: #fff;
        border: 2px solid #24303e;
        position: relative;
        box-sizing: border-box;
        padding: 30px 30px 50px 30px;
        display: none;
    }

    .tooltiptext-bottom {
        width: 420px;
        font-size: 15px;
        background-color: white;
        color: black;
        text-align: left;
        position: absolute;
        z-index: 200;
        top: 25%;
        left: 35%;
    }

    .title_app {
        text-align: center;
        background-color: rgb(130, 199, 54);
        padding: 10px;
        font-size: 20px;
        color: white;
    }

    .desc_app {
        padding: 15px;
    }

    .button_app {
        text-align: center;
        padding: 10px;
    }

    @media only screen and (max-width: 450px) {
        .tooltiptext-bottom {
            width: 80%;
            left: 8%;
        }
    }

    #tutorial-loading {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 150;
        text-align: center;
        display: none;
        background-color: grey;
        opacity: 0.7;
    }
</style>

<div class="big_sub">
    <?php //include "mypage_step_navi.php"; 
    ?>
    <div class="m_div">
        <?php include "mypage_left_menu.php"; ?>
        <div class="m_body">
            <form name="sform" id="sform" action="mypage.proc.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="mode" value="<?= $_GET['event_idx'] ? "event_update" : "event_save"; ?>" />
                <input type="hidden" name="event_idx" value="<?= $_GET['event_idx']; ?>" />
                <input type="hidden" name="event_req_link" id="event_req_link" value="<?= $row['event_req_link'] ? $row['event_req_link'] : ''; ?>" />
                <input type="hidden" name="pcode" id="pcode" value="<?= $row['pcode'] ? $row['pcode'] : ''; ?>" />
                <input type="hidden" name="shortUrl" id="shortUrl" value="<?= $row['short_url']; ?>" />
                <div class="a1" style="margin-top:50px; margin-bottom:15px">
                    <li style="float:left;">
                        <div class="popup_holder popup_text">고객신청그룹 만들기
                            <div id="_popupbox" class="popupbox" style="height: 75px;width: 260px;left: 160px;top: -37px;display:none;">자신의 이벤트 상품이나 서비스를 소개하거나 상세페이지로 만든 랜딩페이지를 리스트로 보는 기능입니다.<br><br>
                                <a class="detail_view" style="color: blue;" href="https://url.kr/ldQyeR" target="_blank">[자세히 보기]</a>
                            </div>
                        </div>
                    </li>
                    <li style="float:right;"></li>
                    <p style="clear:both"></p>
                </div>
                <div>
                    <div class="p1">
                        <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="3px">
                            <tr>
                                <th class="w200">예약구분</th>
                                <td>
                                    <select name="reserv_type" id="reserv_type" style="padding: 5px;width: 150px;">
                                        <option value="0" <?= $row['reserv_type'] == 0 ? "selected" : ""; ?>>수동예약</option>
                                        <? if ($member_1['ai_status']) { ?>
                                            <option value="1" <?= $row['reserv_type'] != 0 ? "selected" : ""; ?>>AI예약</option>
                                        <? } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">신청그룹제목</th>
                                <td>
                                    <input type="text" name="event_title" style="width: 250px;" placeholder="" id="event_title" value="<?= $row['event_title'] ?>" /> (※ 카테고리 분류)
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">제목추가설명</th>
                                <td><input type="text" name="event_name_kor" placeholder="" id="event_name_kor" value="<?= $row['event_name_kor'] ?>" /> </td>
                            </tr>
                            <tr>
                                <th class="w200">신청항목선택</th>
                                <td>
                                    <input type="checkbox" name="event_info[]" placeholder="" id="event_info0" value="sms" <?php if (strstr($row['event_info'], "sms")) echo "checked"; ?> /> 문자인증
                                    <input type="checkbox" name="event_info[]" placeholder="" id="event_info1" value="email" <?php if (strstr($row['event_info'], "email")) echo "checked"; ?> /> 이메일
                                    <input type="checkbox" name="event_info[]" placeholder="" id="event_info2" value="sex" <?php if (strstr($row['event_info'], "sex")) echo "checked"; ?> /> 성별
                                    <input type="checkbox" name="event_info[]" placeholder="소속과 직업을 '/'로 구분하여 입력하세요" id="event_info3" value="job" <?php if (strstr($row['event_info'], "job")) echo "checked"; ?> /> 소속/직업
                                    <input type="checkbox" name="event_info[]" placeholder="" id="event_info4" value="address" <?php if (strstr($row['event_info'], "address")) echo "checked"; ?> /> 주소
                                    <input type="checkbox" name="event_info[]" placeholder="통계정보이니 년도만(예시:1995) 입력하세요" id="event_info5" value="birth" <?php if (strstr($row['event_info'], "birth")) echo "checked"; ?> /> 출생년도
                                    <input type="checkbox" name="event_info[]" placeholder="참여하고 싶은 강연제목 입력" id="event_info6" value="consult_date" <?php if (strstr($row['event_info'], "consult_date")) echo "checked"; ?> /> 신청강좌명
                                    <input type="checkbox" name="event_info[]" placeholder="" id="event_info7" value="join" <?php if (strstr($row['event_info'], "join")) echo "checked"; ?> /> 회원가입
                                    <input type="checkbox" name="event_info[]" placeholder="" id="event_info8" value="other" <?php if (strstr($row['event_info'], "other")) echo "checked"; ?> /> 기타
                                    <br>
                                    <div style="display: flex;">
                                        <input type="checkbox" name="event_info[]" placeholder="" id="event_info9" value="file" <?php if ($row['file_cnt'] > 0) echo "checked"; ?> /> 파일첨부
                                        <? $file_staus = $row['file_cnt'] > 0 ? "" : "display:none"; ?>
                                        <input type="number" name="file_cnt" id="file_cnt" style="width:35px;<?= $file_staus ?>" max="3" min="1" placeholder="" id="file_cnt" value="<?= $row['file_cnt'] * 1 ?>" />
                                        <p id="file_cnt_for" style="<?= $file_staus ?>">(신청자가 pdf,csv,jpg,png,txt,json,xlsx 파일 3개까지 업로드 가능)</p>
                                    </div>
                                    <br>
                                    (※ 이름과 휴대전화는 자동선택됩니다)
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">발송폰번호</th>
                                <td>
                                    <select name="mobile" id="mobile" style="padding: 5px;width: 150px;">
                                        <option value="<?= str_replace("-", "", $member_1['mem_phone']) ?>"><?= str_replace("-", "", $member_1['mem_phone']); ?></option>
                                        <?php
                                        $query = "select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' order by sort_no asc, user_cnt desc , idx desc";
                                        $resul = mysqli_query($self_con, $query);
                                        while ($korow = mysqli_fetch_array($resul)) {
                                            if ($row['mobile']) {
                                                $send_num = str_replace("-", "", $row['mobile']);
                                            }
                                        ?>
                                            <option value="<?= str_replace("-", "", $korow['sendnum']) ?>" <?= $send_num == str_replace("-", "", $korow['sendnum']) ? "selected" : "" ?>><?= str_replace("-", "", $korow['sendnum']); ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr id="step1">
                                <th class="w200">퍼널문자연결1</td>
                                <td style="height:35px;text-align:left;">
                                    <input type="text" id="reservation_title1" name="reservation_title1" value="<?= $reservation_title1 ?>" readonly style="width:250px; height: 27px;">
                                    <input type="hidden" id="step_idx1" name="step_idx1" value="<?= $row['sms_idx1'] ?>" style="width:95px;">
                                    <input type="button" value="퍼널예약관리 조회" class="button " id="searchEventBtn1" data-ai="<?= $row['reserv_type'] ?>">
                                    <a class="btn  btn-link" title="" href="javascript:onAddStep()" style="padding:10px 3px">
                                        <span style="font-size:24px">+</span>
                                    </a>
                                </td>
                            </tr>
                            <tr id="step2" style="<?= $row['sms_idx2'] == '0' ? 'display:none' : '' ?>">
                                <th class="w200">퍼널문자연결2</td>
                                <td style="height:35px;text-align:left;">
                                    <input type="text" id="reservation_title2" name="reservation_title2" value="<?= $reservation_title2 ?>" readonly style="width:250px; height: 27px;">
                                    <input type="hidden" id="step_idx2" name="step_idx2" value="<?= $row['sms_idx2'] ?>" style="width:95px;">
                                    <input type="button" value="퍼널예약관리 조회" class="button " id="searchEventBtn2">
                                    <a class="btn  btn-link" title="" href="javascript:onDelStep(2)" style="padding:10px 3px">
                                        <span style="font-size:24px;padding-top:10px">x</span>
                                    </a>
                                </td>
                            </tr>
                            <tr id="step3" style="<?= $row['sms_idx3'] == '0' ? 'display:none' : '' ?>">
                                <th class="w200">퍼널문자연결3</td>
                                <td style="height:35px;text-align:left;">
                                    <input type="text" id="reservation_title3" name="reservation_title3" value="<?= $reservation_title3 ?>" readonly style="width:250px; height: 27px;">
                                    <input type="hidden" id="step_idx3" name="step_idx3" value="<?= $row['sms_idx2'] ?>" style="width:95px;">
                                    <input type="button" value="퍼널예약관리 조회" class="button " id="searchEventBtn3">
                                    <a class="btn  btn-link" title="" href="javascript:onDelStep(3)" style="padding:10px 3px">
                                        <span style="font-size:24px;padding-top:10px">x</span>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">중단신청그룹</th>
                                <td>
                                    <input type="text" name="stop_title" readonly placeholder="" id="stop_title" style="width:270px" value="<?= $stop_title ?>" />
                                    <input type="hidden" id="stop_event_idx" name="stop_event_idx" value="<?= $row['stop_event_idx'] ?>" style="width:95px;">
                                    <a class="btn  btn-link" title="" href="javascript:onDelStop()" style="padding:10px 3px">
                                        <span style="font-size:24px;padding-top:10px">x</span></a>
                                    <input type="button" value="발송중신청그룹 조회" class="button " id="searchBtn">
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">신청정보입력</th>
                                <td>
                                    <textarea name="event_desc" placeholder="" id="event_desc" style="width:100%;height:200px"><?= $row['event_desc'] ?></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="p1" style="text-align:center;margin-top:20px;">
                    <input type="button" value="저장" class="button" id="saveBtn">
                    <input type="button" value="미리보기" class="button" id="previewBtn" <?= isset($_GET['event_idx']) ? "" : "disabled" ?>>
                    <input type="button" value="나가기" class="button" id="cancleBtn">
                </div>
        </div>
        </form>
    </div>
</div>
<span class="tooltiptext-bottom" id="event_other" style="display:none;">
    <p class="title_app">기타 문의 내용 입력<span onclick="cancel()" style="float:right;cursor:pointer;">X</span></p>
    <table class="table table-bordered" style="width: 97%;">
        <tbody>
            <tr class="hide_spec">
                <td class="bold" id="remain_count" data-num="" style="width:70px;padding:5px;">
                    <p style="padding: 10px;">추가 문의 내용을 입력하세요.</p><br>
                    <textarea name="set_event_other_req" id="set_event_other_req" style="width:100%; height:150px;font-size: 12px"><?= $row['event_req_link'] ?></textarea>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="button_app">
        <a href="javascript:clear_set()" class="btn login_signup" style="width: 40%;background-color: #dbd9d9;border-radius: 3px;padding: 5px;">취소하기</a>
        <a href="javascript:set_event_other()" class="btn login_signup" style="width: 40%;background-color: #82c736;border-radius: 3px;color: white;padding: 5px;">등록하기</a>
    </div>
</span>
<div id="tutorial-loading"></div>
<script>
    function copyHtml(url) {
        var IE = (document.all) ? true : false;
        if (IE) {
            if (confirm("이 소스코드를 복사하시겠습니까?")) {
                window.clipboardData.setData("Text", url);
            }
        } else {
            temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", url);
        }

    }
    $(function() {
        $('#_popupbox').mouseout(function() {
            $(this).hide();
        });
        $(".popbutton1").click(function() {
            $('.ad_layer1').lightbox_me({
                centered: true,
                onLoad: function() {}
            });
        })


        $(".popbutton2").click(function() {
            $('.ad_layer2').lightbox_me({
                centered: true,
                onLoad: function() {}
            });
        })

        $('#searchBtn').on("click", function() {
            window.open("mypage_pop_event_list_for_stop.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
        });

        $('#searchEventBtn1').on("click", function() {
            var ai_type = $(this).data('ai');
            newMessageEvent(1, ai_type);
        });

        $('#searchEventBtn2').on("click", function() {
            newMessageEvent(2, ai_type);
        });

        $('#searchEventBtn3').on("click", function() {
            newMessageEvent(3, ai_type);
        });

        $('#cancleBtn').on("click", function() {
            location = "mypage_link_list.php";
        });

        $('#saveBtn').on("click", function() {
            if ($('#mobile').val() == "") {
                alert('연락처를 입력해주세요');
                return;
            }
            $('#sform').submit();
        });
        $('#previewBtn').on("click", function() {
            let str = $("#shortUrl").val();
            window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
        });
        $('#event_info0').click(function() {
            if (this.checked) {
                $('#event_info7').attr("checked", "checked");
            } else {
                $('#event_info7').removeAttr("checked");
            }
        });
        $("#event_info8").click(function() {
            if (this.checked) {
                $("#event_other").show();
                $("#tutorial-loading").show();
            }
        })
        $("#event_info9").click(function() {
            if (this.checked) {
                $("#file_cnt").show();
                $("#file_cnt").val(1);
                $("#file_cnt_for").show();
            } else {
                $("#file_cnt").hide();
                $("#file_cnt").val(0);
                $("#file_cnt_for").hide();
            }
        })
    })

    function newMessageEvent(type, ai_type) { // test 메시지조회
        var win = window.open("mypage_pop_message_list_for_event.php?addindex=" + type + "&ai_type=" + ai_type, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
    }

    function onAddStep() {
        if ($("#step2").css('display') == "none")
            $("#step2").css('display', 'show');
        else
            $("#step3").css('display', 'show');
    }

    function onDelStep(idx) {
        if (idx == 3) {
            $("#step3").css('display', 'none');
            $("#reservation_title3").val('');
            $("#step_idx3").val('');
        } else if (idx == 2) {
            $("#reservation_title2").val($("#reservation_title3").val());
            $("#step_idx2").val($("#step_idx2").val());
            $("#step3").css('display', 'none');
            $("#reservation_title3").val('');
            $("#step_idx3").val('');
        }
    }

    function onDelStop() {
        $("#stop_title").val("");
        $("#stop_event_idx").val("");
    }

    function cancel() {
        $("#event_other").hide();
        $("#tutorial-loading").hide();
    }

    function set_event_other() {
        var txt = $("#set_event_other_req").val();
        $("#event_req_link").val(txt);
        cancel();
    }

    function clear_set() {
        $("#set_event_other_req").val('');
    }

    function newpop() {
        var win = window.open("mypage_pop_link_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
    }

    var tid;
    $(".popup_holder").hover(function() {
        $(".popupbox").hide();
        clearTimeout(tid);
        $(this).children(".popupbox").show();
    }, function(e) {
        tid = setTimeout(function() {
            //$(e.currentTarget).children(".popupbox").hide();
        }, 3000);
    });
    $(".popup_text").hover(function() {
        $(this).css("color", "#0f7bef");
        $(this).children(".popupbox").css("color", "black");
    }, function(e) {
        $(".popup_text").css("color", "black");
    });
</script>
<style>
    .popup_holder {
        position: relative;
    }

    .popupbox {
        z-index: 1;
        text-align: left;
        font-size: 12px;
        font-weight: normal;
        background: white;
        border-radius: 3px;
        padding: 10px;
        border: none;
        position: absolute;
        box-shadow: 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);
    }
</style>

<div class="ad_layer1">
    <div class="layer_in">
        <span class="layer_close close"><img src="/images/close_button_05.jpg"></span>
        <div class="pop_title">
        </div>
        <div class="info_text">
            <p>
                <span><br>신청경로를 채널별로 다르게 작성하시면 유입채널을 구분할 수 있습니다.<br> 예시 : event_m(문자) event_b(블로그) event_c(카페) event_k(카톡) </span>
            </p>
        </div>

    </div>
</div>
<div class="ad_layer2">
    <div class="layer_in">
        <span class="layer_close close"><img src="/images/close_button_05.jpg"></span>
        <div class="pop_title">
        </div>
        <div class="info_text">
            <p>
                <span>이벤트명 (영문)은 중복될 수 없습니다. 예약문자 설정할 때 이벤트명 (영문)이 중복되면 원하는 문자가 발송되지 않습니다.</span>
            </p>
        </div>

    </div>
</div>