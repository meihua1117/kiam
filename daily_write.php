<?
$path = "./";
include_once "_head.php";
extract($_REQUEST);
if (!$_SESSION['one_member_id']) {
?>
    <script language="javascript">
        location.replace('/ma.php');
    </script>
<?
    exit;
}
$sql = "select * from Gn_Member  where mem_id='{$_SESSION['one_member_id']}'";
$sresul_num = mysqli_query($self_con, $sql);
$data = mysqli_fetch_array($sresul_num);
$gd_id = $_GET['gd_id'];
$sql = "select * from Gn_daily  where gd_id='{$gd_id}'";
$sresul_num = mysqli_query($self_con, $sql);
$row = mysqli_fetch_array($sresul_num);
if ($row[0]) {
    $sql = "select * from Gn_MMS_Group where idx='{$row['group_idx']}'";
    $sresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $krow = mysqli_fetch_array($sresult);
}
if ($row['daily_cnt']) {
    $daily_cnt = $row['daily_cnt'];
} else {
    if (!$_REQUEST['daily_cnt']) {
        $daily_cnt = 50;
    } else {
        $daily_cnt = $_REQUEST['daily_cnt'];
    }
}

$sql_daily_msg_title = "select key_content from Gn_Search_Key where key_id='daily_msg_title'";
$res_title = mysqli_query($self_con, $sql_daily_msg_title);
$row_title = mysqli_fetch_array($res_title);

$sql_daily_msg_content = "select key_content from Gn_Search_Key where key_id='daily_msg_contents'";
$res_content = mysqli_query($self_con, $sql_daily_msg_content);
$row_content = mysqli_fetch_array($res_content);
?>
<script>
    function copyHtml() {
        var trb = $.trim($('#sHtml').html());
        var IE = (document.all) ? true : false;
        if (IE) {
            if (confirm("이 소스코드를 복사하시겠습니까?")) {
                window.clipboardData.setData("Text", trb);
            }
        } else {
            temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", trb);
        }
    }

    function newpop() {
        var send_num = $("#send_num").val();
        var win = window.open("mypage_pop_address_list.php?send_num_daily=" + send_num, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
    }
    $(function() {
        <? if ($row['send_deny'] == "Y") { ?>
            $('#send_deny_msg').prop("checked", true);
            $('.deny_msg_span').html('ON');
            $('.deny_msg_span').css('color', '#00F');
        <? } else { ?>
            $('#send_deny_msg').prop("checked", false);
            $('.deny_msg_span').html('OFF');
            $('.deny_msg_span').css('color', '#F00');
        <? } ?>
        $(".popbutton").click(function() {
            $('.ad_layer_info').lightbox_me({
                centered: true,
                onLoad: function() {}
            });
        });
        $('#searchBtn').on("click", function() {
            newpop();
        });
        $('#cancleBtn').on("click", function() {
            location = "daily_list.php";
        });
        $("#search_email_date").on("change", function() {
            $('#date_list').append('<li id="' + $(this).val() + '">' + $(this).val() + "<a href=\"javascript:removeDate('" + $(this).val() + "')\">[삭제]</a></li>");
        });

        $('#saveBtn').on("click", function() {
            var grp_id = $("#address_idx").val();
            var send_num = $("#send_num").val();
            $.ajax({
                type: "POST",
                url: "/ajax/get_deny_count.php",
                data: {
                    grp_id: grp_id,
                    send_num: send_num,
                    iam: false
                },
                success: function(data) {
                    $("#daily_cnt_modal").text($("#daily_cnt").val());
                    var start_send = $("#start_send").val();
                    var daily_cnt = $("#daily_cnt").val();
                    var addr_cnt = $("#address_cnt").text();
                    var send_date_list = '';
                    var grp_month = new Array;
                    var all_set_month = new Array;
                    var count = 0;
                    var count1 = 0;

                    // addr_cnt = addr_cnt * 1 - data * 1;
                    $('#date_list li').each(function() {
                        send_date_list = $(this).text().replace("[삭제]", "");
                        send_month_arr = send_date_list.split("-");
                        all_set_month['count'] = send_month_arr[1];
                        if (!count1) {
                            grp_month[count1] = send_month_arr[1];
                            count1++;
                        } else {
                            if (grp_month[count1 - 1] != send_month_arr[1]) {
                                grp_month[count1] = send_month_arr[1];
                                count1++;
                            }
                        }
                        count++;
                    });

                    var str = '';
                    for (i = 0; i < grp_month.length; i++) {
                        if (grp_month[i].substring(0, 1) == '0') {
                            var month = grp_month[i].substring(1, 2);
                        } else {
                            var month = grp_month[i];
                        }
                        str += month + "월 ";
                        month_cnt_arr = all_set_month.toString().split(grp_month[i]);
                        if (i == grp_month.length - 1) {
                            if (daily_cnt == addr_cnt) {
                                var rest_cnt = 0;
                            } else {
                                var rest_cnt = daily_cnt - addr_cnt % daily_cnt;
                            }
                        } else {
                            var rest_cnt = 0;
                        }
                        send_cnt_month = (month_cnt_arr.length - 1) * daily_cnt - rest_cnt;
                        if (send_cnt_month > addr_cnt) {
                            send_cnt_month = addr_cnt;
                        }
                        if (send_cnt_month > 2000) {
                            var over_cnt = send_cnt_month * 1 - 2000;
                            str += send_cnt_month + "건(" + over_cnt + "건 초과)<br>";
                        } else {
                            if (addr_cnt * 1 < daily_cnt) {
                                str += addr_cnt + "건<br>";
                            } else {
                                str += send_cnt_month + "건<br>";
                            }

                        }
                    }

                    $("#calc_per_month").html(str);
                    $("#tooltiptext_card_edit").show();
                    $("#tutorial-loading").show();
                    $('body,html').animate({
                        scrollTop: 0,
                    }, 100);
                }
            });
        });
    })

    function send_daily() {
        if ($('#gd_id').val() == "") {
            alert('주소록을 선택해 주세요.');
            return;
        }

        if ($('#title').val() == "") {
            alert('제목을 입력해주세요.');
            return;
        }

        if ($('#txt').val() == "") {
            alert('내용을 입력해주세요.');
            return;
        }
        //if($('#daily_cnt').val() > 100) {
        //    alert('일발송량의 최대수는 100입니다.');
        //    return;
        //}
        var send_date = "";
        $('#date_list li').each(function() {
            if (send_date == "")
                send_date = $(this).text().replace("[삭제]", "");
            else
                send_date += "," + $(this).text().replace("[삭제]", "");
        });
        $('#send_date').val(send_date);
        if ($('#send_date').val() == "") {
            alert('발송일을 선택해주세요.');
            return;
        }

        if ($("#is_send_mail").val() == "Y") {
            $('#mail_sender').val($('#txt_mail_address').val());
            $('#mail_title').val($('#txt_mail_title').val());
            $('#mail_content').val($('#txt_mail_content').val());
            $('#mail_file').val($('#link_attachment').val());
        }

        $('#total_count').val($('#address_cnt').text());
        sub_4_form.action = "mypage.proc.php";
        sub_4_form.target = '';
        $('#sub_4_form').submit();
    }

    function removeDate(id) {
        $('#' + id).remove();
    }

    function type_check() {}

    function apply() {
        var msg_mode = $("input[name=set_msg_mode]:checked").val();
        if (msg_mode == "1" && $("#step_msg_info").val() == 0) {
            alert("스텝메시지를 선택하세요.");
            return;
        }
        msg_mode = $("#step_sms_idx").val();

        var week_end_state = $("input[name=set_weekend]:checked").val();
        var daily_cnt = $("#daily_cnt").val();
        var start_send = $("#start_send").val();
        var htime = $("#htime").val();
        var mtime = $("#mtime").val();
        var send_num = $("#send_num").val();
        var max_count = $("#max_count").val();
        if (start_send == '') {
            alert("발송시작일을 선택해 주세요.");
            return;
        }
        <?
        if ($gd_id == "") {
        ?>
            // location.href = '<?= $_SERVER['PHP_SELF'] ?>' + '?daily_cnt=' + daily_cnt + '&address_cnt=' + $("#address_cnt").text() + '&address_name=' + $("#address_name").val() + '&address_idx=' + $("#address_idx").val() + '&start_send=' + start_send + '&htime=' + htime + '&mtime=' + mtime + '&send_num=' + send_num;
            location.href = '<? if (strpos($_SERVER['REQUEST_URI'], "?daily_cnt") !== false) {
                                    $link = explode("?daily_cnt", $_SERVER['REQUEST_URI']);
                                    echo trim($link[0]);
                                } else {
                                    echo $_SERVER['REQUEST_URI'];
                                } ?>' + '?daily_cnt=' + daily_cnt + '&address_cnt=' + $("#address_cnt").text() + '&address_name=' + $("#address_name").val() + '&address_idx=' + $("#address_idx").val() + '&start_send=' + start_send + '&htime=' + htime + '&mtime=' + mtime + '&send_num=' + send_num + '&week_end=' + week_end_state + '&msg_mode=' + msg_mode + '&max_count=' + max_count;
        <? } else { ?>
            // location.href = '<?= $_SERVER['REQUEST_URI'] ?>' + '&daily_cnt=' + daily_cnt + '&start_send=' + start_send + '&htime=' + htime + '&mtime=' + mtime + '&send_num=' + send_num;
            location.href = '<? if (strpos($_SERVER['REQUEST_URI'], "&daily_cnt") !== false) {
                                    $link = explode("&daily_cnt", $_SERVER['REQUEST_URI']);
                                    echo trim($link[0]);
                                } else {
                                    echo $_SERVER['REQUEST_URI'];
                                } ?>' + '&daily_cnt=' + daily_cnt + '&start_send=' + start_send + '&htime=' + htime + '&mtime=' + mtime + '&send_num=' + send_num + '&week_end=' + week_end_state + '&msg_mode=' + msg_mode + '&max_count=' + max_count;
        <? } ?>
    }

    function cancel_set() {
        $("#tooltiptext_card_edit").hide();
        $("#tutorial-loading").hide();
    }

    function show_mail_box() {
        if ($('#date_list > li').length == 0) {
            alert("적용버튼을 눌러 발송일자를 선택하여야 합니다.");
            return;
        }
        var coord = $("#btn_mail").position();
        $("#modal_mail").css({
            "top": coord.top,
            "left": coord.left + 100
        });
        $("#modal_mail").show();
        $("#tutorial-loading").show();
    }

    function hide_mail_box() {
        $("#modal_mail").hide();
        $("#tutorial-loading").hide();
    }

    function onSave(frm) {
        if (login_check(frm)) {
            var addr = $("#txt_mail_address").val();
            if (!addr.match(/^[\w.%+\-]+@[\w.\-]+\.[A-Za-z]{2,3}$/)) {
                alert("유효한 메일형식이 아닙니다.");
                $("#txt_mail_address").focus();
                return;
            }
            if ($("#chk_save").prop("checked")) {
                $.ajax({
                    type: "POST",
                    url: "ajax/mail_msg_func.php",
                    data: {
                        mode: "add",
                        mail_addr: $("#txt_mail_address").val(),
                        mail_title: $("#txt_mail_title").val(),
                        mail_content: $("#txt_mail_content").val(),
                        attach_file: $("#link_attachment").val()
                    },
                    success: function(data) {
                        //alert('저장되었습니다.');
                    }
                });
            }
            $("#is_send_mail").val("Y");
            hide_mail_box();
        }

    }

    function onSaveMailAddress() {
        if ($("#txt_mail_address").val() == "") {
            alert('메일주소를 입력해 주세요');
            $("#txt_mail_address").focus();
            return;
        }
        var addr = $("#txt_mail_address").val();
        if (!addr.match(/^[\w.%+\-]+@[\w.\-]+\.[A-Za-z]{2,3}$/)) {
            alert("유효한 메일형식이 아닙니다.");
            $("#txt_mail_address").focus();
            return;
        }

        if (confirm("메일을 등록하시겠습니까?")) {
            $.ajax({
                type: "POST",
                url: "ajax/mail_address_func.php",
                data: {
                    mode: "add",
                    mail_addr: $("#txt_mail_address").val()
                },
                success: function(data) {
                    alert("메일이 등록되었습니다.");
                }
            });
        }

    }

    function onLoadMailAddresses() {
        window.open("mail_address_list.php", "", "scrollbars=yes,resizable=yes,top=200,left=200,width=500,height=600");
    }

    function deny_add_multi() {
        $("#tooltiptext_add_deny_multi").show();
        $("#tutorial-loading").show();
    }

    function add_deny_multi() {
        var recv_nums = $("#deny_num_multi").val();
        var send_num = $("#send_num").val();
        var mem_id = '<?= $_SESSION['one_member_id'] ?>';

        var type = $("#btn_type").val();
        $.ajax({
            type: "POST",
            url: "/ajax/add_deny_multi.php",
            data: {
                deny_add_send: send_num,
                deny_add_recv: recv_nums,
                mem_id: mem_id,
                reg_chanel: 9,
                type: type
            },
            success: function(data) {
                // $($(".loading_div")[0]).hide();
                $("#ajax_div").html(data);
            }
        });
    }

    function clear_nums() {
        $("#deny_num_multi").val('');
    }

    function get_addr_list(val) {
        var send_num = $("#send_num").val();
        if (val == "deny") {
            $("#btn_type").val("add_deny");
        } else {
            $("#btn_type").val("unadd_deny");
        }

        window.open('/group_detail_for_adddeny.php?phone=' + send_num + '&mem_id=<?= $_SESSION['one_member_id'] ?>&type=' + val, "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=500,height=600");
    }

    function cancel_set1() {
        $("#tooltiptext_add_deny_multi").hide();
        $("#tutorial-loading").hide();
    }

    function cancel_date_list() {
        $("#send_daily_date_list").hide();
        $("#tutorial-loading").hide();
    }

    function check_apply() {
        var daily_cnt = '<?= $_REQUEST['daily_cnt'] ?>';
        if (!daily_cnt) {
            alert('먼저 [적용]을 클릭하고 하단 발송일을 확인해주세요.');
        }
    }

    function get_steplist() {
        var msg_mode = $("input[name=set_msg_mode]:checked").val();
        if (msg_mode != "1") {
            alert("스텝메시지를 선택하세요.");
            return;
        }

        var win = window.open("../mypage_pop_message_list_for_daily.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
    }

    function show_datelist() {
        var daily_cnt = '<?= $_REQUEST['daily_cnt'] ?>';
        if (!daily_cnt) {
            alert('먼저 [적용]을 클릭하고 하단 발송일을 확인해주세요.');
            return;
        }

        $("#send_daily_date_list").show();
        $("#tutorial-loading").show();
    }

    function set_write_mode() {
        $(".msg_info").show();
    }

    function set_step_msg() {
        $(".msg_info").hide();
    }
</script>
<style>
    .pop_right {
        position: relative;
        right: 2px;
        display: inline;
        margin-bottom: 6px;
        width: 5px;
    }

    .ui-widget-content {
        border: 0px !important;
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

    .tooltiptext-bottom {
        width: 320px;
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

    .send_datelist {
        font-size: 15px;
        display: inline-table;
    }
</style>
<div class="big_sub">
    <?php include "mypage_step_navi.php"; ?>
    <div class="m_div">
        <?php include "mypage_left_menu.php"; ?>
        <div class="m_body">
            <form name="sub_4_form" id="sub_4_form" action="mypage.proc.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="mode" value="<?= $gd_id ? "daily_update" : "daily_save"; ?>" />
                <input type="hidden" name="gd_id" value="<?= $gd_id; ?>" />
                <input type="hidden" name="step_daily" value="Y" />
                <input type="hidden" name="total_count" id="total_count" value="<?= $_GET['address_cnt'] ? $_GET['address_cnt'] : $row['total_count']; ?>" />

                <!-- 메일 발송관련 -->
                <input type="hidden" id="mail_sender" name="mail_sender">
                <input type="hidden" id="mail_title" name="mail_title">
                <input type="hidden" id="mail_content" name="mail_content">
                <input type="hidden" id="mail_file" name="mail_file">
                <input type="hidden" id="mem_id" name="mem_id" value="<?= $_SESSION['one_member_id']; ?>">
                <div class="a1" style="margin-top:50px; margin-bottom:15px">
                    <li style="float:left;">
                        <div class="popup_holder popup_text"> 데일리발송 세트만들기
                            <div class="popupbox" style="height: 64px;width: 260px;color: black;left: 195px;top: -37px;">디비를 매일 발송가능한 숫자로 나누어 매일 발송할 수 있도록 자동화 등록하는 기능입니다.<br><br>
                                <a class="detail_view" style="color: blue;" href="https://tinyurl.com/34vmnw4c" target="_blank">[자세히 보기]</a>
                            </div>
                        </div>
                    </li>
                    <li style="float:right;"></li>
                    <p style="clear:both"></p>
                </div>
                <div>
                    <div class="p1">
                        <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <th class="w200">[발송폰선택]</th>
                                <td>
                                    <select name="send_num" id="send_num">
                                        <option value="<?= str_replace("-", "", $data['mem_phone']) ?>"><?= str_replace("-", "", $data['mem_phone']); ?></option>
                                        <?php
                                        $query = "select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' order by sort_no asc, user_cnt desc , idx desc";
                                        $resul = mysqli_query($self_con, $query);
                                        while ($korow = mysqli_fetch_array($resul)) {
                                            if ($row['send_num']) {
                                                $send_num = $row['send_num'];
                                                if ($_REQUEST['send_num']) {
                                                    $send_num = $_REQUEST['send_num'];
                                                }
                                            } else {
                                                $send_num = $_REQUEST['send_num'];
                                            }
                                        ?>
                                            <option value="<?= str_replace("-", "", $korow['sendnum']) ?>" <?= $send_num == str_replace("-", "", $korow['sendnum']) ? "selected" : "" ?>><?= str_replace("-", "", $korow['sendnum']); ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[주소록선택]</th>
                                <td>
                                    <input type="hidden" name="group_idx" placeholder="" id="address_idx" value="<?= $_GET['address_idx'] ? $_GET['address_idx'] : $row['group_idx']; ?>" readonly style="width:100px" />
                                    <input type="text" name="address_name" placeholder="" id="address_name" value="<?= $_GET['address_name'] ? $_GET['address_name'] : $krow['grp'] ?>" readonly style="width:100px" />
                                    <input type="button" value="주소록 조회" class="button " id="searchBtn">
                                    [선택건수]<span id="address_cnt"><?= $_GET['address_cnt'] ? $_GET['address_cnt'] : $row['total_count']; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[최대발송량]</th>
                                <td>
                                    <input type="number" name="max_count" placeholder="" id="max_count" value="<?= $_GET['max_count'] ? $_GET['max_count'] : $row['max_count'] ?>" min='0' style="width:100px;height: 25px;" />
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[제외폰 등록]</th>
                                <td>
                                    <a href="javascript:deny_add_multi();" style="text-decoration-line: blink;background-color: #5f72e6;padding: 5px;color: white;border-radius: 5px;">발송 제외 폰 등록하기</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[발송시작일]</th>
                                <td>
                                    <?
                                    if ($row['start_date']) {
                                        $date = $row['start_date'];
                                    } else if ($_GET['start_send']) {
                                        $date = $_GET['start_send'];
                                    } else {
                                        $cur_time = time() + 86400;
                                        $date = date("Y-m-d", $cur_time);
                                    }
                                    ?>
                                    <input type="date" name="start_send" id="start_send" style="width:20%;" value="<?= $date ?>" />
                                    <select name="htime" id="htime" style="width:50px;">
                                        <?
                                        for ($i = 9; $i < 20; $i++) {
                                            $iv = $i < 10 ? "0" . $i : $i;
                                            if ($row['htime']) {
                                                $htime = $row['htime'];
                                            } else if ($_GET['htime']) {
                                                $htime = $_GET['htime'];
                                            } else {
                                                $htime = '15';
                                            }
                                            // $htime = $row['htime']?$row['htime']:$_GET['htime'];
                                            $selected = $htime == $iv ? "selected" : "";
                                        ?>
                                            <option value="<?= $iv ?>" <?= $selected ?>><?= $iv ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                    <select name="mtime" id="mtime" style="width:50px;">
                                        <?
                                        for ($i = 0; $i < 60; $i += 10) {
                                            $iv = $i == 0 ? "00" : $i;
                                            $mtime = $row['mtime'] ? $row['mtime'] : $_GET['mtime'];
                                            $selected = $mtime == $iv ? "selected" : "";
                                        ?>
                                            <option value="<?= $iv ?>" data="<?= $mtime ?>" <?= $selected ?>><?= $iv ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[메시지유형]</th>
                                <td>
                                    <?
                                    if ($row['step_sms_idx']) {
                                        $check_step0 = "";
                                        $check_step1 = "checked";
                                        $sql_step_title = "select reservation_title, sms_idx from Gn_event_sms_info where sms_idx='{$row['step_sms_idx']}'";
                                        $res_step_title = mysqli_query($self_con, $sql_step_title);
                                        $row_step_title = mysqli_fetch_array($res_step_title);
                                        $step_title = $row_step_title[0];

                                        $step_idx = $row_step_title['sms_idx'];

                                        $hide_msg = "hidden";
                                    } else {
                                        $check_step0 = "checked";
                                        $check_step1 = "";

                                        $step_title = '';
                                        $step_idx = 0;
                                        $hide_msg = "";
                                    }

                                    if (isset($_GET['msg_mode']) && $_GET['msg_mode'] != '0') {
                                        $check_step0 = "";
                                        $check_step1 = "checked";
                                        $sql_step_title = "select reservation_title, sms_idx from Gn_event_sms_info where sms_idx='{$_GET['msg_mode']}'";
                                        $res_step_title = mysqli_query($self_con, $sql_step_title);
                                        $row_step_title = mysqli_fetch_array($res_step_title);
                                        $step_title = $row_step_title[0];

                                        $step_idx = $row_step_title['sms_idx'];
                                        $hide_msg = "hidden";
                                    }
                                    ?>
                                    <input type="radio" id="write_msg" name="set_msg_mode" value="0" onclick="set_write_mode()" style="float:left;" <?= $check_step0 ?>>
                                    <div style="float:left;">메시지입력</div>
                                    <input type="radio" id="step_msg" name="set_msg_mode" value="1" onclick="set_step_msg()" style="float:left;" <?= $check_step1 ?>>
                                    <div style="float:left;">스텝메시지</div>
                                    <input type="text" id="step_msg_info" name="step_msg_info" style="float:left;width:30%;height:20px;margin:0 10px;" value="<?= $step_title ?>" readonly>
                                    <input type="hidden" id="step_sms_idx" name="step_sms_idx" value="<?= $step_idx ?>">
                                    <a href="javascript:get_steplist();" style="text-decoration-line: blink;background-color: #5f72e6;padding: 3px;color: white;border-radius: 5px;">조회</a>
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[일발송량]</th>
                                <td>
                                    <input type="text" name="daily_cnt" id="daily_cnt" style="width:60px;height:20px;" value="<?= $daily_cnt ?>" />
                                    <?
                                    if ($row['weekend_status']) {
                                        $check_week0 = "";
                                        $check_week1 = "checked";
                                    } else {
                                        $check_week0 = "checked";
                                        $check_week1 = "";
                                    }

                                    if (isset($_GET['week_end']) && $_GET['week_end'] == 1) {
                                        $check_week0 = "";
                                        $check_week1 = "checked";
                                    }
                                    ?>
                                    <input type="radio" id="write_msg" name="set_weekend" value="0" style="float:left;" <?= $check_week0 ?>>
                                    <div style="float:left;">주말제외</div>
                                    <input type="radio" id="step_msg" name="set_weekend" value="1" style="float:left;" <?= $check_week1 ?>>
                                    <div style="float:left;margin-right:10px;">주말포함</div>
                                    <a href="javascript:apply();" style="text-decoration-line: blink;background-color: #5f72e6;padding: 3px;color: white;border-radius: 5px;">적용</a>
                                    <a href="javascript:show_datelist();" style="text-decoration-line: blink;background-color: red;padding: 3px;color: white;border-radius: 5px;">발송일보기</a>
                                    각 달에 각기 다른 2000명 수신처제한에 걸리지 않게 세팅하세요.
                                </td>
                            </tr>
                            <?
                            $hide_msg = "";
                            if ((isset($_GET['msg_mode']) && $_GET['msg_mode'] != '0') || $row['step_sms_idx'] != 0) {
                                $hide_msg = "hidden";
                            } ?>
                            <tr class="msg_info" <?= $hide_msg ?>>
                                <th class="w200">[메시지제목]</th>
                                <td><input type="text" name="title" onclick="check_apply()" itemname='문자 제목' required placeholder="문자 제목 입력" style="width:40%;" value="<?= $row['title'] ? $row['title'] : $row_title[0] ?>" /></td>
                            </tr>
                            <tr class="msg_info" <?= $hide_msg ?>>
                                <th class="w200">[메시지내용]</th>
                                <td>
                                    <textarea style="width:40%; height:200px;" id="txt" name="txt" itemname='내용' id='txt' required placeholder="보내고 싶은 메시지를 입력하세요" onkeydown="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);" onkeyup="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();" onfocus="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();"><?= $row['content'] ? $row['content'] : $row_content[0] ?></textarea>
                                    <input type="button" id="btn_mail" name="btn_mail" value="메일입력" title="선택한 그룹에 메일 등록이 된 디비에만 이메일도 발송됩니다" style="vertical-align:top;margin-bottom:3px;height:28px;" onclick="show_mail_box()" />
                                </td>
                            </tr>
                            <tr class="msg_info" <?= $hide_msg ?>>
                                <th class="w200">[링크주소]</th>
                                <td><input type="text" name="daily_link" onclick="check_apply()" itemname='링크주소' required placeholder="링크주소 입력" style="width:40%;" value="<?= $row['link'] ?>" /><br>이 주소가 설정되지 않으면 이용자의 IAM주소를 가져옵니다.</td>
                            </tr>
                            <tr class="msg_info" <?= $hide_msg ?>>
                                <th class="w200">[수신거부]</th>
                                <td><input type="checkbox" id="send_deny_msg" name="send_deny_msg" onclick="deny_msg_click(this,0)" style="float:left;">
                                    <div class="deny_msg_span" style="float:left;">OFF</div>
                                </td>
                            </tr>
                            <tr class="msg_info" <?= $hide_msg ?>>
                                <th class="w200">[이미지1]</th>
                                <td>
                                    <input type="file" name="upimage" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=&target=upimage_str';sub_4_form.target='excel_iframe';sub_4_form.submit();" />
                                    <?php if ($row['jpg'] != "") { ?>
                                        <img src="<?= $row['jpg']; ?>" style="width:200px">
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr class="msg_info" <?= $hide_msg ?>>
                                <th class="w200">[이미지2]</th>
                                <td>
                                    <input type="file" name="upimage1" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=1&target=upimage_str1';sub_4_form.target='excel_iframe';sub_4_form.submit();" />
                                    <?php if ($row['jpg1'] != "") { ?>
                                        <img src="<?= $row['jpg1']; ?>" style="width:200px">
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr class="msg_info" <?= $hide_msg ?>>
                                <th class="w200">[이미지3]</th>
                                <td>
                                    <input type="file" name="upimage2" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=2&target=upimage_str2';sub_4_form.target='excel_iframe';sub_4_form.submit();" />
                    </div>
                    <?php if ($row['jpg2'] != "") { ?>
                        <img src="<?= $row['jpg2']; ?>" style="width:200px">
                    <?php } ?>
                    </td>
                    </tr>
                    <!-- <tr>
                        <th class="w200">[발송시간선택]</th>
                        <td>
                        <select name="htime" style="width:50px;"  >
                            <?
                            for ($i = 9; $i < 20; $i++) {
                                $iv = $i < 10 ? "0" . $i : $i;
                                $selected = $row['htime'] == $iv ? "selected" : "";
                            ?>
                                <option value="<?= $iv ?>" <?= $selected ?>><?= $iv ?></option>
                                <?
                            }
                                ?>
                        </select>
                        <select name="mtime" style="width:50px;"  >
                            <?
                            for ($i = 0; $i < 31; $i += 30) {
                                $iv = $i == 0 ? "00" : $i;
                                $selected = $row['mtime'] == $iv ? "selected" : "";
                            ?>
                                <option value="<?= $iv ?>" <?= $selected ?>><?= $iv ?></option>
                                <?
                            }
                                ?>
                        </select>
                        </td>
                    </tr> -->
                    </table>
                </div>
                <div class="a2" style="display:none">
                    <div class="b2" style="float:left">이미지 미리보기</div>
                    <div style="float:right">
                        <button id="show1" onclick="showImage('');return false;">1</button>
                        <button id="show2" onclick="showImage('1');return false;">2</button>
                        <button id="show3" onclick="showImage('2');return false;">3</button>
                    </div>
                    <div id="preview_wrapper" class="img_view" style="display:inline-block;width:100%;">
                        <div id="preview_fake" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale);">
                            <img id="preview" onload="onPreviewLoad(this)" />
                        </div>
                    </div>
                    <img id="preview_size_fake" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image);visibility:hidden; height:0;" />
                    <div><input type="hidden" name="upimage_str" value="<?= $row['jpg']; ?>" /></div>
                    <div><input type="hidden" name="upimage_str1" value="<?= $row['jpg1']; ?>" /></div>
                    <div><input type="hidden" name="upimage_str2" value="<?= $row['jpg2']; ?>" /></div>
                </div>
                <input type="hidden" name="send_date" id="send_date" value="" />
                <div class="p1" style="text-align:center;margin-top:20px;">
                    <input type="button" value="취소" class="button" id="cancleBtn">
                    <? if ($row['iam'] != 1) { ?>
                        <input type="button" value="저장" class="button" id="saveBtn">
                    <? } ?>
                </div>
        </div>
        </form>
    </div>
    <form name="excel_down_form" action="" target="excel_iframe" method="post">
        <input type="hidden" name="grp_id" id="grp_id" value="" />
        <input type="hidden" name="box_text" value="" />
        <input type="hidden" name="excel_sql" value="" />
    </form>
    <iframe name="excel_iframe" style="display:none"></iframe>
    <span class="tooltiptext-bottom" id="tooltiptext_card_edit" style="display:none;">
        <p class="title_app">데일리 발송하기<a href="javascript:cancel_set()" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -12px;cursor:pointer;">X</a></p><br>
        <p class="desc_app">일 발송량을 <span id="daily_cnt_modal">0</span>으로 설정 하셨습니다.<br><span id="calc_per_month"></span><br>데일리발송은 한달 문자발송 총건수에 포함됩니다.<br>각기 다른 2000명 수신처제한에 걸리지 않는지 확인해주세요.<br>확인을 클릭하면 발송일부터 발송됩니다.</p>
        <div class="button_app">
            <a href="javascript:cancel_set()" class="btn login_signup" style="width: 40%;background-color: #bbbbbb;padding: 5px;">수정</a>
            <a href="javascript:send_daily()" class="btn login_signup" style="width: 40%;background-color: #ff0066;color: white;padding: 5px;">확인</a>
        </div>
    </span>
    <span class="tooltiptext-bottom" id="modal_mail" style="display:none;">
        <p class="title_app">메일 정보 입력 <span onclick="hide_mail_box()" style="float:right;cursor:pointer;">X</span></p>
        <form name="mail_form" action="ajax/mail_send.php" method="post" enctype="multipart/form-data">
            <div><input type="hidden" id="link_attachment" name="link_attachment" value="" /></div>
            <input type="hidden" id="is_send_mail" name="is_send_mail" value="N" />
            <table class="table table-bordered" style="width: 97%;">
                <tbody>
                    <tr class="hide_spec">
                        <td colspan="3" style="padding:20 20px;">
                            <div>
                                <input type="text" name="txt_mail_address" id="txt_mail_address" style="border: solid 1px #b5b5b5;width:78%;height:34px" itemname='발신 이메일' required placeholder="발신 이메일 입력"></input>
                                <a href="javascript:onLoadMailAddresses()"><img src="./images/icon-open.png" style="width:32px;height: 32px;"></a>
                                <a href="javascript:onSaveMailAddress()"><img src="./images/icon-save.png" style="width:32px;height: 32px;"></a>
                            </div>
                        </td>
                    </tr>

                    <tr class="hide_spec">
                        <td colspan="3" style="padding:20 20px;">
                            <div>
                                <input type="text" name="txt_mail_title" id="txt_mail_title" style="border: solid 1px #b5b5b5;width:100%;height:34px" itemname='메일 제목' required placeholder="메일 제목"></input>
                            </div>
                        </td>
                    </tr>

                    <tr class="hide_spec">
                        <td colspan="3" style="padding:20 20px;">
                            <div>
                                <textarea name="txt_mail_content" id="txt_mail_content" style="border: solid 1px #b5b5b5;width:100%; height:150px;" itemname='메시지' data-num="0" required placeholder="메시지 입력"></textarea>
                            </div>
                        </td>
                    </tr>
                    <tr class="hide_spec">
                        <td colspan="3" style="padding:20 20px;">
                            <div>
                                <span>★메일 주소가 등록된 디비에만 이메일이 발송됩니다.</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:20%">[파일등록]</td>
                        <td style="width:50%">
                            <input type="file" name="file_attach" style="width:200px" onChange="mail_form.action='upload_file.php?target=link_attachment';mail_form.target='excel_iframe';mail_form.submit();" />
                        </td>
                        <td style="width: 30%;">
                            <div style="float: left;"><label><input type="checkbox" style="padding:5px" id="chk_save" name="chk_save" />메시지 저장</label></div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="margin: 10px;width:100%">
                <span style="text-align:center;width:45%;display:inline-block"><a href="javascript:void(0)" style="color:red;padding-right:20px" onclick="window.open('mail_msg_list.php','mail_msg_list','top=100,left=120,width=1145,height=632,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no, status=no')">
                        메시지 불러오기</a></span>
                <span style="text-align:center;width:45%;display:inline-block"><a href="javascript:void(0)" style="width:50%;color:red;padding-right:20px" onclick="onSave(document.mail_form)">
                        발송창으로 가기 </a></span>
            </div>
        </form>
    </span>
    <span class="tooltiptext-bottom" id="tooltiptext_add_deny_multi" style="display:none;">
        <p class="title_app">제외 대상 추가<span onclick="cancel_set1()" style="float:right;cursor:pointer;">X</span></p>
        <table class="table table-bordered" style="width: 97%;">
            <tbody>
                <input type="hidden" name="btn_type" id="btn_type" value="add_deny">
                <div style="text-align:center;">
                    <button class="people_iam_show" style="font-size: 15px;margin: 0px;margin-bottom: 15px;background-color: #4ab4ff;border-radius:0px;width: 150px;margin-top: 15px;color: white;border: none;padding: 5px;" onclick="get_addr_list('undeny')">제외리스트<br>보기/해제</button>
                    <button class="people_iam_show" style="font-size: 15px;margin: 0px;margin-bottom: 15px;background-color: rgb(9 117 193);border-radius:0px;width: 150px;margin-top: 15px;color: white;border: none;padding: 5px;" onclick="get_addr_list('deny')">내 주소록에서<br>제외대상 추가</button>
                </div>
                <h3 style="margin-left:20px;">
                    제외번호 입력
                </h3>
                <tr class="hide_spec">
                    <td colspan="2" style="padding:0 20px;">
                        <div>
                            <textarea name="deny_num_multi" id="deny_num_multi" style="border: solid 1px #b5b5b5;width:100%; height:150px;" data-num="0" placeholder="전화번호(쉼표, 엔터키로 구분)"></textarea>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="padding:10px;">
            <p style="font-size:16px;color:#6e6c6c;border:1px solid;padding:10px;">
                1. 제외 대상등록 : 주소록가져오기 또는 수동입력으로 제외대상을 등록하세요.<br><br>
                2. 제외 해제설정 : 제외리스트/해제 클릭 후 번호를 선택해서 해제하세요.
            </p>
        </div>
        <div class="button_app">
            <a href="javascript:clear_nums()" class="btn login_signup" style="width: 40%;background-color: #82c736;border-radius: 3px;padding: 5px;color: white;">취소</a>
            <a href="javascript:add_deny_multi()" class="btn login_signup" style="width: 40%;background-color: #82c736;border-radius: 3px;color: white;padding: 5px;">등록</a>
        </div>
    </span>
    <span class="tooltiptext-bottom" id="send_daily_date_list" style="display:none;">
        <p class="title_app">발송일자선택<span onclick="cancel_date_list()" style="float:right;cursor:pointer;">X</span></p>
        <table class="table table-bordered" style="width: 97%;">
            <tbody>
                <tr class="hide_spec">
                    <td>
                        <div>
                            <ul id="date_list" style="margin-left:10px;padding:0px;">
                                <?php
                                echo "start=".json_encode($row);
                                if (isset($_GET['address_cnt'])) {
                                    if (isset($_GET['max_count']) && $_GET['max_count']) {
                                        $day = ceil($_GET['max_count'] / $_REQUEST['daily_cnt']);
                                    } else {
                                        $day = ceil($_GET['address_cnt'] / $_REQUEST['daily_cnt']);
                                    }
                                } else {
                                    if ($row['max_count']) {
                                        $day = ceil($row['max_count'] / $_REQUEST['daily_cnt']);
                                    } else {
                                        $day = ceil($row['total_count'] / $_REQUEST['daily_cnt']);
                                    }
                                }
                                echo $day;
                                for ($i = 0; $i < $day; $i++) {
                                    $start_day = strtotime($_GET['start_send']) + (86400 * $i);
                                    $today = date("Y-m-d", $start_day);
                                    if (!$_GET['week_end']) {
                                        $week = date("l", $start_day);
                                        if ($week == "Saturday" || $week == "Sunday") {
                                            $day++;
                                            continue;
                                        }
                                    }
                                ?>
                                    <li class="send_datelist" id="<?= $today; ?>" week='<?= $week; ?>'><?= $today; ?><a href="javascript:removeDate('<?= $today; ?>')">[삭제]</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </span>
    <div id="ajax_div"></div>
    <div id="tutorial-loading"></div>
</div>
<!--link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script-->
<? include_once "_foot.php"; ?>