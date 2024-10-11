<?
include_once $_SERVER['DOCUMENT_ROOT'] . "/lib/rlatjd_fun.php";
if ($_SESSION['iam_member_id']) {
    $sql = "select * from tjd_pay_result where buyer_id = '{$_SESSION['iam_member_id']}' and end_date > '$date_today' and end_status in ('Y','A') order by end_date desc limit 1";
    $res_result = mysqli_query($self_con,$sql);
    $pay_data = mysqli_fetch_array($res_result);
}
$sub_domain = false;
if ($HTTP_HOST == "kiam.kr")
    $host = "www.kiam.kr";
else
    $host = $HTTP_HOST;
$query = "select * from Gn_Iam_Service where sub_domain like '%{$host}'";
$res = mysqli_query($self_con,$query);
$domainData = mysqli_fetch_array($res);
if ($HTTP_HOST != "kiam.kr") {
    if ($domainData['idx'] != "") {
        $sub_domain = true;
    }
}
if ($domainData['status'] == "N" || $pay_data['stop_yn'] == "Y") {
    echo "<script>location='/';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko" xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?
    if ($sub_domain == true) {
        if ($domainData['site_name']) { ?>
            <title><?php echo $domainData['site_name']; ?></title>
            <meta name="description" content="" <?php echo $domainData['site_name']; ?>"" />
            <meta name="keywords" content="<?php echo $domainData['keywords']; ?>" />
            <meta name="naver-site-verification" content="<?php echo $domainData['naver-site-verification']; ?>" />
        <?
        }
    } else { ?>
        <title>자동셀링솔루션으로 성공하세요!</title>
        <meta name="description" content="온리원문자, 온리원셀링,PC-스마트폰 연동문자, 대량문자, 장문/포토 모두0.7원, 수신거부/수신동의/없는번호/번호변경 자동관리, 수신처관리" />
        <meta name="keywords" content="온리원문자, 온리원셀링,PC-스마트폰 연동문자, 대량문자, 장문/포토 모두0.7원, 수신거부/수신동의/없는번호/번호변경 자동관리, 수신처관리" />
    <? } ?>
    <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
    <link href='<?= $path ?>css/nanumgothic.css' rel='stylesheet' type='text/css' />
    <link href='<?= $path ?>css/main.css' rel='stylesheet' type='text/css' />
    <link href='/css/sub_4_re.css' rel='stylesheet' type='text/css' />
    <link href='<?= $path ?>css/responsive.css' rel='stylesheet' type='text/css' /><!-- 2019.11 반응형 CSS -->
    <link href='<?= $path ?>css/font-awesome.min.css' rel='stylesheet' type='text/css' /><!-- 2019.11 반응형 CSS -->
    <script language="javascript" src="<?= $path ?>js/jquery-3.1.1.min.js"></script>
    <script language="javascript" src="<?= $path ?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
    <script type="text/javascript" src="/jquery.lightbox_me.js"></script>
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-3E40Q09QGE"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-3E40Q09QGE');
    </script>
</head>
<?
$path = "./";
extract($_REQUEST);
$msg_title = "";
$sql_daily_msg_title = "select key_content from Gn_Search_Key where key_id='daily_msg_title'";
$res_title = mysqli_query($self_con,$sql_daily_msg_title);
$row_title = mysqli_fetch_array($res_title);

$sql_daily_msg_content = "select key_content from Gn_Search_Key where key_id='daily_msg_contents'";
$res_content = mysqli_query($self_con,$sql_daily_msg_content);
$row_content = mysqli_fetch_array($res_content);

// if(!$_REQUEST[msg_title]){
//     $msg_title = " 새로 만든 모바일 명함이니 저장부탁해요";
// }
// else{
//     $msg_title = $_REQUEST[msg_title];
// }
if (!$_REQUEST[daily_cnt]) {
    $daily_cnt = 50;
} else {
    $daily_cnt = $_REQUEST[daily_cnt];
}

if ($_REQUEST[prelink]) {
    $prelink = $_REQUEST[prelink];
} else {
    $prelink = '';
}

$cur_time = time() + 86400;
$cur_date = date("Y-m-d", $cur_time);

if (!$_SESSION['iam_member_id']) { ?>
    <script language="javascript">
        location.replace('/');
    </script>
<?
    exit;
}
$sql = "select * from Gn_Member  where mem_id='" . $_SESSION['iam_member_id'] . "'";
$sresul_num = mysqli_query($self_con,$sql);
$data = mysqli_fetch_array($sresul_num);

$sql = "select * from Gn_MMS_Group where  mem_id='" . $_SESSION['iam_member_id'] . "' and grp='아이엠'";
$sresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$krow = mysqli_fetch_array($sresult);

$sql = "select count(*) cnt from Gn_MMS_Receive_Iam where  mem_id='" . $_SESSION['iam_member_id'] . "' and grp_id='{$krow['idx']}'";
$sresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$skrow = mysqli_fetch_array($sresult);

$count = 0;
$sql1 = "select recv_num from Gn_MMS_Receive_Iam where  mem_id='" . $_SESSION['iam_member_id'] . "' and grp_id='{$krow['idx']}'";
$sresult1 = mysqli_query($self_con,$sql1) or die(mysqli_error($self_con));
while ($srow1 = mysqli_fetch_array($sresult1)) {
    $sql_chk = "select idx from Gn_MMS_Deny where send_num='{$_REQUEST['send_num']}' and recv_num='{$srow1[0]}' and (chanel_type=9 or chanel_type=4)";
    $res_chk = mysqli_query($self_con,$sql_chk);
    if (mysqli_num_rows($res_chk)) {
        $count++;
    }
}
$skrow['cnt'] = $skrow['cnt'] * 1 - $count;

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

    function removeDate(id) {
        $('#' + id).remove();
    }

    function type_check() {}

    function newpop() {
        var win = window.open("mypage_pop_address_list.php", "event_pop",
            "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
    }
    $(function() {
        <? if ($_GET[show_modal] == "Y") { ?>
            $("#deny_num_multi").val('<?= $_GET[deny_nums] ?>');
            $("#btn_type").val('<?= $_GET[type] ?>');
            $("#tooltiptext_add_deny_multi").show();
            $("#tutorial-loading").show();
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
            window.close();
        });
        // $("#search_email_date").on("change",function(){
        //     $('#date_list').append('<li id="'+$(this).val()+'">'+$(this).val()+"<a href=\"javascript:removeDate('"+$(this).val()+"')\">[삭제]</a></li>");
        // });
        $('#saveBtn').on("click", function() {
            var grp_id = $("#address_idx").val();
            var send_num = $("#send_num").val();
            console.log(grp_id);
            $.ajax({
                type: "POST",
                url: "/ajax/get_deny_count.php",
                data: {
                    grp_id: grp_id,
                    send_num: send_num,
                    iam: true
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
                        all_set_month[count] = send_month_arr[1];
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
                    $("#send_daily_modal").modal("show");
                }
            });
        });

        // $('#saveBtn').on("click", function() {

        // });
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
        if ($('#daily_cnt').val() > 100) {
            alert('일발송량의 최대수는 100입니다.');
            return;
        }
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
        $('#total_count').val($('#address_cnt').text());
        sub_4_form.action = "/mypage.proc.php";
        sub_4_form.target = '';
        $('#sub_4_form').submit();
    }

    function apply() {
        var daily_cnt = $("#daily_cnt").val();
        var start_send = $("#start_send").val();
        var htime = $("#htime").val();
        var mtime = $("#mtime").val();
        var send_num = $("#send_num").val();
        if (start_send == '') {
            alert("발송시작일을 선택해 주세요.");
            return;
        }
        location.href = '<? if (strpos($_SERVER['REQUEST_URI'], "&show_modal") !== false) {
                                $link = explode("&show_modal", $_SERVER['REQUEST_URI']);
                                echo trim($link[0]);
                            } else if (strpos($_SERVER['REQUEST_URI'], "&deny_nums") !== false) {
                                $link = explode("&deny_nums", $_SERVER['REQUEST_URI']);
                                echo trim($link[0]);
                            } else if (strpos($_SERVER['REQUEST_URI'], "&daily_cnt") !== false) {
                                $link = explode("&daily_cnt", $_SERVER['REQUEST_URI']);
                                echo trim($link[0]);
                            } else {
                                echo $_SERVER['REQUEST_URI'];
                            } ?>' + '&daily_cnt=' + daily_cnt + '&start_send=' + start_send + '&htime=' + htime + '&mtime=' + mtime + '&send_num=' + send_num;
    }

    function deny_add_multi() {
        $("#tooltiptext_add_deny_multi").show();
        $("#tutorial-loading").show();
    }

    function add_deny_multi() {
        var recv_nums = $("#deny_num_multi").val();
        var send_num = $("#send_num").val();
        var mem_id = '<?= $_SESSION['iam_member_id'] ?>';

        var type = $("#btn_type").val();
        $.ajax({
            type: "POST",
            url: "/ajax/add_deny_multi_event.php",
            data: {
                deny_add_send: send_num,
                deny_add_recv: recv_nums,
                mem_id: mem_id,
                reg_chanel: 9,
                type: type
            },
            success: function(data) {
                var link = '';
                var window_width = $(window).width();
                if (window_width < 850) {
                    var link = '<? if (strpos($_SERVER['REQUEST_URI'], "&show_modal") !== false) {
                                    $link = explode("&show_modal", $_SERVER['REQUEST_URI']);
                                    echo trim($link[0]);
                                } else if (strpos($_SERVER['REQUEST_URI'], "&deny_nums") !== false) {
                                    $link = explode("&deny_nums", $_SERVER['REQUEST_URI']);
                                    echo trim($link[0]);
                                } else if (strpos($_SERVER['REQUEST_URI'], "&daily_cnt") !== false) {
                                    $link = explode("&daily_cnt", $_SERVER['REQUEST_URI']);
                                    echo trim($link[0]);
                                } else {
                                    echo $_SERVER['REQUEST_URI'];
                                } ?>';
                }
                if (data == 1) {
                    alert("메시지 발송제한 등록이 완료되었습니다.");
                    // location.reload();
                    location.href = link;
                } else if (data == 2) {
                    alert('발신번호는 등록된 번호가 아닙니다.');
                } else if (data == 3) {
                    alert('수신번호를 확인해 주세요.');
                    // location.reload();
                    location.href = link;
                } else if (data == 4) {
                    alert('정확한 번호가 아닙니다.[발신번호error]');
                } else if (data == 5) {
                    alert('제외리스트에서 해제 되었습니다.');
                    // location.reload();
                    location.href = link;
                }
                // $($(".loading_div")[0]).hide();
                $("#ajax_div").html(data);
            }
        });
    }

    function clear_nums() {
        $("#deny_num_multi").val('');
    }

    function get_addr_list(val) {
        var window_width = $(window).width();
        // alert(window_width);
        if (window_width < 850) {
            var pre_link = '<?= urlencode("https://" . $HTTP_HOST . $_SERVER['REQUEST_URI']) ?>';
        }

        var send_num = $("#send_num").val();
        if (val == "deny") {
            $("#btn_type").val("deny");
        } else {
            $("#btn_type").val("undeny");
        }

        if (window_width < 850) {
            window.open('/group_detail_for_adddeny_app.php?phone=' + send_num + '&mem_id=<?= $_SESSION['iam_member_id'] ?>&type=' + val + '&pre_link=' + pre_link, "_self", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=500,height=600");
        } else {
            window.open('/group_detail_for_adddeny.php?phone=' + send_num + '&mem_id=<?= $_SESSION['iam_member_id'] ?>&type=' + val, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=500,height=600");
        }
    }

    function cancel_set() {
        $("#tooltiptext_add_deny_multi").hide();
        $("#tutorial-loading").hide();
    }

    function check_apply() {
        var daily_cnt = '<?= $_REQUEST[daily_cnt] ?>';
        if (!daily_cnt) {
            alert('먼저 [적용]을 클릭하고 하단 발송일을 확인해주세요.');
        }
    }

    function show_datelist() {
        var daily_cnt = '<?= $_REQUEST[daily_cnt] ?>';
        if (!daily_cnt) {
            alert('먼저 [적용]을 클릭하고 하단 발송일을 확인해주세요.');
            return;
        }

        $("#send_daily_date_list").show();
        $("#tutorial-loading").show();
    }

    function cancel_date_list() {
        $("#send_daily_date_list").hide();
        $("#tutorial-loading").hide();
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
        text-align: center;
        font-size: 15px;
    }

    .list_table1 input[type=text] {
        width: 600px;
        height: 30px;
    }

    .tooltiptext-bottom {
        width: 80%;
        font-size: 15px;
        background-color: white;
        color: black;
        text-align: left;
        position: absolute;
        z-index: 200;
        top: 5%;
        left: 8%;
    }

    .title_app {
        text-align: center;
        background-color: rgb(130, 199, 54);
        padding: 10px;
        font-size: 20px;
        color: white;
        font-weight: 900;
    }

    .desc_app {
        padding: 15px;
    }

    .button_app {
        text-align: center;
        padding: 10px;
    }

    .send_datelist {
        font-size: 15px;
        display: inline-table;
        margin: 3px;
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

<div class="big_sub" style="max-width: 777px; padding: 20px 0">
    <div class="midle_div">
        <div class="m_body">
            <form name="sub_4_form" id="sub_4_form" action="mypage.proc.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="mode" value="daily_save" />
                <input type="hidden" name="gd_id" value="<?php echo $gd_id; ?>" />
                <input type="hidden" name="total_count" id="total_count" value="<?php echo $row[total_count]; ?>" />
                <input type="hidden" name="iam" value="1" />
                <input type="hidden" name="mem_id" value="<?= $_SESSION['iam_member_id'] ?>" />
                <div class="a1" style="margin-top:15px; margin-bottom:15px;font-size: 20px;font-weight: 900;margin-left: 15px;">
                    <li>데일리메시지 세트 만들기</li>
                    <li style="float:right;"></li>
                    <p style="clear:both"></p>
                </div>
                <div>
                    <div class="p1">
                        <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <input type="hidden" name="send_num" id="send_num" value="<?= str_replace("-", "", $data[mem_phone]) ?>">
                            <!-- <tr>
                                <th class="w200">[발송폰선택]</th>
                                <td>
                                    <select name="send_num" id="send_num">
                                        <option value="<?= str_replace("-", "", $data[mem_phone]) ?>">
                                            <?php echo str_replace("-", "", $data['mem_phone']); ?></option>
                                        <?
                                        $query = "select * from Gn_MMS_Number where mem_id='{$_SESSION['iam_member_id']}' order by sort_no asc, user_cnt desc , idx desc";
                                        $resul = mysqli_query($self_con,$query);
                                        while ($korow = mysqli_fetch_array($resul)) {
                                            if ($row['send_num']) {
                                                $send_num = $row['send_num'];
                                                if ($_REQUEST['send_num']) {
                                                    $send_num = $_REQUEST['send_num'];
                                                }
                                            } else {
                                                $send_num = $_REQUEST['send_num'];
                                            } ?>
                                        <option value="<?= str_replace("-", "", $korow[sendnum]) ?>"
                                            <?php echo $send_num == str_replace("-", "", $korow[sendnum]) ? "selected" : "" ?>>
                                            <?php echo str_replace("-", "", $korow[sendnum]); ?></option>
                                        <?  } ?>
                                    </select>
                                </td>
                            </tr> -->
                            <tr>
                                <th class="w200">[주소록선택]</th>
                                <td>
                                    <input type="hidden" name="group_idx" placeholder="" id="address_idx" value="<?= $krow['idx'] ?>" readonly style="width:100px" />
                                    <input type="text" name="address_name" placeholder="" id="address_name" value="아이엠" readonly style="width:100px" />
                                    선택건수<span id="address_cnt"><?php echo $skrow['cnt']; ?></span>
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
                                    <input type="date" name="start_send" id="start_send" style="width:20%;" value="<?= $_GET[start_send] ? $_GET[start_send] : $cur_date ?>" />
                                    <select name="htime" id="htime" style="width:50px;">
                                        <?
                                        for ($i = 9; $i < 20; $i++) {
                                            $iv = $i < 10 ? "0" . $i : $i;
                                            $htime = $_GET[htime] ? $_GET[htime] : '15';
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
                                            $mtime = $row[mtime] ? $row[mtime] : $_GET[mtime];
                                            $selected = $mtime == $iv ? "selected" : "";
                                        ?>
                                            <option value="<?= $iv ?>" <?= $selected ?>><?= $iv ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[일발송량]</th>
                                <td><input type="text" name="daily_cnt" id="daily_cnt" style="width:60px;" value="<?= $daily_cnt ?>" onkeyup="if(this.value >100)this.value=50;" />
                                    <a href="javascript:apply();" style="text-decoration-line: blink;background-color: #5f72e6;padding: 3px;color: white;border-radius: 5px;">적용</a>
                                    <a href="javascript:show_datelist();" style="text-decoration-line: blink;background-color: red;padding: 3px;color: white;border-radius: 5px;">발송일보기</a>
                                    데일리 발송은 고객의 폰에서 일발송량 50건 이하로 설정합니다.
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[문자제목]</th>
                                <td><input type="text" name="title" onclick="check_apply()" itemname='제목' required placeholder="제목" style="width:80%;" value='<?= $row_title[0] ?>' /></td>
                            </tr>
                            <tr>
                                <th class="w200">[문자내용]</th>
                                <td>
                                    <textarea style="width:200px; height:200px;" id="txt" name="txt" itemname='내용' id='txt' required placeholder="내용" onkeydown="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);" onkeyup="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();" onfocus="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();">
                                        <?= strtr($row_content[0], "*", "&") ?>
                                    </textarea>
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[링크주소]</th>
                                <td><input type="text" name="daily_link" value="<?= $prelink ?>" onclick="check_apply()" itemname='링크주소' required placeholder="링크주소 입력" style="width:80%;" value='' /><br>이 주소가 설정되지 않으면 이용자의 IAM주소를 가져옵니다.</td>
                            </tr>
                            <tr>
                                <th class="w200">[수신거부]</th>
                                <td><input type="checkbox" id="send_deny_msg" name="send_deny_msg" onclick="deny_msg_click(this,0)" style="float:left;">
                                    <div class="deny_msg_span" style="float:left;">OFF</div>
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[이미지입력1]</th>
                                <td>
                                    <input type="file" name="upimage" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=&target=upimage_str';sub_4_form.target='excel_iframe';sub_4_form.submit();" />
                                    <?php if ($row['jpg'] != "") { ?>
                                        <img src="<?php echo $row['jpg']; ?>" style="width:200px">
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[이미지입력2]</th>
                                <td>
                                    <input type="file" name="upimage1" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=1&target=upimage_str1';sub_4_form.target='excel_iframe';sub_4_form.submit();" />
                                    <?php if ($row['jpg1'] != "") { ?>
                                        <img src="<?php echo $row['jpg1']; ?>" style="width:200px">
                                    <?php } ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[이미지입력3]</th>
                                <td>
                                    <input type="file" name="upimage2" onChange="onUploadImgChange(this);sub_4_form.action='up_image_.php?i=0&k=2&target=upimage_str2';sub_4_form.target='excel_iframe';sub_4_form.submit();" />
                    </div>
                    <?php if ($row['jpg2'] != "") { ?>
                        <img src="<?php echo $row['jpg2']; ?>" style="width:200px">
                    <?php } ?>
                    </td>
                    </tr>
                    <!-- <tr>
                        <th class="w200">[발송시간선택]</th>
                        <td>
                            <select name="htime" style="width:50px;">
                                <?
                                for ($i = 9; $i < 22; $i++) {
                                    $iv = $i < 10 ? "0" . $i : $i;
                                    $selected = $row[htime] == $iv ? "selected" : "";
                                ?>
                                <option value="<?= $iv ?>" <?= $selected ?>><?= $iv ?></option>
                                <?
                                }
                                ?>
                            </select>
                            <select name="mtime" style="width:50px;">
                                <?
                                for ($i = 0; $i < 31; $i += 30) {
                                    $iv = $i == 0 ? "00" : $i;
                                    $selected = $row[mtime] == $iv ? "selected" : "";
                                ?>
                                <option value="<?= $iv ?>" <?= $selected ?>><?= $iv ?></option>
                                <?
                                }
                                ?>
                            </select>
                        </td>
                    </tr> -->
                    <!-- <tr>
                        <th class="w200">[발송일자선택]</th>
                        <td>
                            <div style="width:50%;text-align:left;margin:0px;">
                                <? if ($_REQUEST['daily_cnt']) { ?>
                                <ul id="date_list">
                                    <?php
                                    if (isset($_REQUEST['daily_cnt'])) {
                                        $day = ceil($skrow['cnt'] / $_REQUEST['daily_cnt']);
                                    } else {
                                        $day = ceil($skrow['cnt'] / 50);
                                    }
                                    for ($i = 1; $i <= $day; $i++) {
                                        $start_day = strtotime($_GET['start_send']) + (86400 * $i);
                                        $today = date("Y-m-d", $start_day);
                                    ?>
                                    <li id="<?php echo $today; ?>"><?php echo $today; ?><a
                                            href="javascript:removeDate('<?php echo $today; ?>')">[삭제]</a></li>
                                    <?php } ?>
                                </ul>
                                <? } ?>
                            </div>
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
                    <div><input type="hidden" name="upimage_str" value="<?php echo $row['jpg']; ?>" /></div>
                    <div><input type="hidden" name="upimage_str1" value="<?php echo $row['jpg1']; ?>" /></div>
                    <div><input type="hidden" name="upimage_str2" value="<?php echo $row['jpg2']; ?>" /></div>
                </div>
                <input type="hidden" name="send_date" id="send_date" value="" />
                <div class="p1" style="text-align:center;margin-top:20px;">
                    <input type="button" value="취소" class="button" id="cancleBtn">
                    <input type="button" value="발송하기" class="button" id="saveBtn">
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
</div>
<span class="tooltiptext-bottom" id="tooltiptext_add_deny_multi" style="display:none;">
    <p class="title_app">제외 대상 추가<span onclick="cancel_set()" style="float:right;cursor:pointer;">X</span></p>
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
<div id="tutorial-loading"></div>
<div id="ajax_div"></div>
<span class="tooltiptext-bottom" id="send_daily_date_list" style="display:none;">
    <p class="title_app">발송일자선택<span onclick="cancel_date_list()" style="float:right;cursor:pointer;">X</span></p>
    <table class="table table-bordered" style="width: 97%;">
        <tbody>
            <tr class="hide_spec">
                <td>
                    <div style="">
                        <ul id="date_list" style="margin-left:10px;padding:0px;">
                            <?php
                            if (isset($_REQUEST['daily_cnt'])) {
                                $day = ceil($skrow['cnt'] / $_REQUEST['daily_cnt']);
                            } else {
                                $day = ceil($skrow['cnt'] / 50);
                            }
                            for ($i = 1; $i <= $day; $i++) {
                                $start_day = strtotime($_GET['start_send']) + (86400 * $i);
                                $today = date("Y-m-d", $start_day);
                            ?>
                                <li class="send_datelist" id="<?php echo $today; ?>" week='<?= $week; ?>'><?php echo $today; ?><a href="javascript:removeDate('<?php echo $today; ?>')">[삭제]</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</span>
<div id="send_daily_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;display:none;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="border:none;background-color: rgb(130,199,54)">
                <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">데일리 발송하기</div>
                <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -12px;cursor:pointer;">X</a>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;box-shadow: none;">
                    <!-- <p style="font-size:16px;color:#6e6c6c">
                        일 발송량을 <span id="daily_cnt_modal"></span> 으로 설정하셨습니다.<br>
                        확인을 클릭하시면 내일부터 발송됩니다.
                    </p> -->
                    <p style="font-size:16px;color:#6e6c6c">일 발송량을 <span id="daily_cnt_modal">0</span>으로 설정 하셨습니다.<br><span id="calc_per_month"></span><br>데일리발송은 한달 문자발송 총건수에 포함됩니다.<br>각기 다른 2000명 수신처제한에 걸리지 않는지 확인해주세요.<br>확인을 클릭하면 발송일부터 발송됩니다.</p>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center;">
                <a class="btn btn-default btn-submit" data-dismiss="modal" style="border-radius: 5px;width:49%;font-size:15px;background-color: #ff0066;color:white;">수정하기</a>
                <a class="btn btn-default btn-submit" style="border-radius: 5px;width:49%;font-size:15px;background-color: #ff0066;color:white;" onclick="send_daily()" target="_blank">발송하기</a>
            </div>
        </div>
    </div>
</div>
<!--link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script-->

</html>