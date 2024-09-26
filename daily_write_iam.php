<?
include_once $path . "lib/rlatjd_fun.php";

$sql = "select * from tjd_pay_result where buyer_id = '{$_SESSION['one_member_id']}' and end_date > '$date_today' and end_status in ('Y','A') order by end_date desc limit 1";
$res_result = mysqli_query($self_con, $sql);
$pay_data = mysqli_fetch_array($res_result);
$rights = 0;
//echo $pay_data['TotPrice'] ;
//echo $pay_data['member_type'] ;
if ($pay_data['TotPrice'] < "55000") {
    $rights = 1;
} else if ($pay_data['TotPrice'] == "55000") {
    $rights = 2;
} else if ($pay_data['TotPrice'] > "55000") {
    $rights = 3;
}
$rights = 3;
$sub_domain = false;
if ($_SERVER['HTTP_HOST'] != "kiam.kr") {
    $query = "select * from Gn_Service where sub_domain like '%" . $_SERVER['HTTP_HOST'] . "'";
    $res = mysqli_query($self_con, $query);
    $domainData = mysqli_fetch_array($res);

    if ($domainData['idx'] != "") {
        $sub_domain = true;
    }
}

$sql = "select * from tjd_pay_result where buyer_id = '{$_SESSION['one_member_id']}' and end_date > '$date_today'  and stop_yn='Y'";
$res_result = mysqli_query($self_con, $sql);
$stop = mysqli_fetch_array($res_result);
if ($stop['stop_yn'] == "Y") {
    if ($_SERVER['PHP_SELF'] == "/sub_5.php" || $_SERVER['PHP_SELF'] == "/sub_6.php" || strstr($_SERVER['PHP_SELF'], "mypage_") == true || strstr($_SERVER['PHP_SELF'], "daily_") == true) {
        echo "<script>location='/';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?php if ($sub_domain == true) { ?>
        <? if ($domainData['site_name']) { ?>
            <title><?php echo $domainData['site_name']; ?></title>
            <meta name="description" content="" <?php echo $domainData['site_name']; ?>"" />
            <meta name="keywords" content="<?php echo $domainData['keywords']; ?>" />
            <meta name="naver-site-verification" content="<?php echo $domainData['naver-site-verification']; ?>" />

        <? } ?>
    <?php } else { ?>
        <title>자동셀링솔루션으로 성공하세요!</title>
        <meta name="description" content="온리원문자, 온리원셀링,PC-스마트폰 연동문자, 대량문자, 장문/포토 모두0.7원, 수신거부/수신동의/없는번호/번호변경 자동관리, 수신처관리" />
        <meta name="keywords" content="온리원문자, 온리원셀링,PC-스마트폰 연동문자, 대량문자, 장문/포토 모두0.7원, 수신거부/수신동의/없는번호/번호변경 자동관리, 수신처관리" />

    <?php } ?>
    <!--
    <link rel="shortcut icon" href="logo.ico">
-->
    <link href='<?= $path ?>css/nanumgothic.css' rel='stylesheet' type='text/css' />
    <link href='<?= $path ?>css/main.css' rel='stylesheet' type='text/css' />
    <link href='/css/sub_4_re.css' rel='stylesheet' type='text/css' />
    <link href='<?= $path ?>css/responsive.css' rel='stylesheet' type='text/css' /><!-- 2019.11 반응형 CSS -->
    <link href='<?= $path ?>css/font-awesome.min.css' rel='stylesheet' type='text/css' /><!-- 2019.11 반응형 CSS -->
    <script language="javascript" src="<?= $path ?>js/jquery-1.7.1.min.js"></script>
    <script language="javascript" src="<?= $path ?>js/jquery.carouFredSel-6.2.1-packed.js"></script>
    <script type="text/javascript" src="/jquery.lightbox_me.js"></script>
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
    <script>
        $(function() {
            $('.main_link').hover(function() {
                    $('.sub_menu').hide();
                    var index = $(".main_link").index(this);
                    $(".main_link:eq(" + index + ")").parent().find("ul").show();
                },
                function() {
                    $('.sub_menu').hide();
                }
            );
            $('.main_link').on("hover", function() {
                $('.sub_menu').hide();
                var index = $(".main_link").index(this);
                $(".main_link:eq(" + index + ")").parent().find("ul").show();
                //       $(this).closest("ul").show();
                //$(this).closest("ul").css("background","yellow");
                //console.log($(this).closest('ul'));
            });
            $('.head_right_2').on("mouseout", function() {
                //$('.sub_menu').delay(5000).hide(0);
            });
            $('.main_link').on("click", function() {
                $('.sub_menu').hide();
                var index = $(".main_link").index(this);
                $(".main_link:eq(" + index + ")").parent().find("ul").show();
                //       $(this).closest("ul").show();
                //$(this).closest("ul").css("background","yellow");
                //console.log($(this).closest('ul'));
            });
            $('.head_left, .head_right_1, .container').on("mouseover", function() {
                $('.sub_menu').hide();
            });

            $('.sub_menu').on("mouseleave", function() {
                $('.sub_menu').hide();
            });
        });
    </script>
</head>
<?
$path = "./";
extract($_REQUEST);
if (!$_SESSION['one_member_id']) {


?>
    <script language="javascript">
        location.replace('/ma.php');
    </script>
<?
    exit;
}
$sql = "select * from Gn_Member  where mem_id='" . $_SESSION['one_member_id'] . "'";
$sresul_num = mysqli_query($self_con, $sql);
$data = mysqli_fetch_array($sresul_num);
//print_r($data);



$sql = "select * from Gn_MMS_Group where  mem_id='" . $_SESSION['one_member_id'] . "' and grp='아이엠'";
$sresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$krow = mysqli_fetch_array($sresult);

$sql = "select count(*) cnt from Gn_MMS_Receive where  mem_id='" . $_SESSION['one_member_id'] . "' and grp_id='$krow[idx]'";
$sresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
$skrow = mysqli_fetch_array($sresult);
?>
<script>
    function copyHtml() {
        //oViewLink = $( "ViewLink" ).innerHTML;
        ////alert ( oViewLink.value );
        //window.clipboardData.setData("Text", oViewLink);
        //alert ( "주소가 복사되었습니다. \'Ctrl+V\'를 눌러 붙여넣기 해주세요." );
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
</style>

<div class="big_sub" style="max-width: 777px; padding: 20px 0">

    <div class="midle_div">
        <div class="m_body">

            <form name="sub_4_form" id="sub_4_form" action="mypage.proc.php" method="post" enctype="multipart/form-data">

                <input type="hidden" name="mode" value="<?= $gd_id ? "daily_updat" : "daily_save"; ?>" />
                <input type="hidden" name="gd_id" value="<?= $gd_id; ?>" />
                <input type="hidden" name="total_count" id="total_count" value="<?= $row['total_count']; ?>" />
                <input type="hidden" name="iam" value="1" />



                <div class="a1" style="margin-top:15px; margin-bottom:15px">
                    <li>데일리메시지 세트 만들기</li>
                    <li style="float:right;"></li>
                    <p style="clear:both"></p>
                </div>
                <div>
                    <div class="p1">
                        <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <th class="w200">[발송폰선택]</th>
                                <td>
                                    <select name="send_num">
                                        <option value="<?= str_replace("-", "", $data['mem_phone']) ?>"><?php echo str_replace("-", "", $data['mem_phone']); ?></option>
                                        <?php
                                        $query = "select * from Gn_MMS_Number where mem_id='{$_SESSION['one_member_id']}' order by sort_no asc, user_cnt desc , idx desc";
                                        $resul = mysqli_query($self_con, $query);
                                        while ($korow = mysqli_fetch_array($resul)) {
                                        ?>
                                            <option value="<?= str_replace("-", "", $korow['sendnum']) ?>" <?php echo $row['send_num'] == str_replace("-", "", $korow['sendnum']) ? "selected" : "" ?>><?php echo str_replace("-", "", $korow['sendnum']); ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[주소록선택]</th>
                                <td>
                                    <input type="hidden" name="group_idx" placeholder="" id="address_idx" value="<?= $krow['idx'] ?>" readonly style="width:100px" />
                                    <input type="text" name="address_name" placeholder="" id="address_name" value="아이엠" readonly style="width:100px" />
                                    선택건수<span id="address_cnt"><?php echo $skrow['cnt']; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[일발송량]</th>
                                <td><input type="text" name="daily_cnt" id="daily_cnt" style="width:60px;" value="100" />
                                    데일리 발송은 고객의 폰에서 일발송량 100건 이하로 발송해야 합니다.
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">[문자제목]</th>
                                <td><input type="text" name="title" itemname='제목' required placeholder="제목" style="width:80%;" value=" 새로 만든 모바일 명함이니 저장부탁해요" /></td>
                            </tr>
                            <tr>
                                <th class="w200">[문자내용]</th>
                                <td>
                                    <textarea readonly style="width:200px; height:200px;" id="txt" name="txt" itemname='내용' id='txt' required placeholder="내용" onkeydown="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);" onkeyup="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();" onfocus="textCounter(sub_4_form.txt,'wenzi_cnt',2000,0);type_check();"><?= $_REQUEST['msg'] ?></textarea>
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
                    <tr>
                        <th class="w200">[발송시간선택]</th>
                        <td>
                            <select name="htime" style="width:50px;">
                                <?
                                for ($i = 9; $i < 22; $i++) {
                                    $iv = $i < 10 ? "0" . $i : $i;
                                    $selected = $row['htime'] == $iv ? "selected" : "";
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
                                    $selected = $row['mtime'] == $iv ? "selected" : "";
                                ?>
                                    <option value="<?= $iv ?>" <?= $selected ?>><?= $iv ?></option>
                                <?
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="w200">[발송일자선택]<br><br>※발송일자는 폰주소록건수/100건으로 자동계산하여 발송일자가 세팅되며 삭제기능으로 발송일자를 개별적으로 제외시킬 수 있습니다.</th>
                        <td>
                            <div style="width:50%;text-align:left;margin:0px;">
                                <ul id="date_list">
                                    <?php
                                    $day = ceil($skrow['cnt'] / 100);
                                    for ($i = 1; $i <= $day; $i++) {
                                        $today = date("Y-m-d", strtotime("+$i day"));
                                    ?>
                                        <li id="<?php echo $today; ?>"><?php echo $today; ?><a href="javascript:removeDate('<?php echo $today; ?>')">[삭제]</a></li>
                                    <?php } ?>

                                </ul>
                            </div>
                        </td>
                    </tr>
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
<script>
    function newpop() {
        var win = window.open("mypage_pop_address_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");

    }
    $(function() {
        $('#searchBtn').on("click", function() {
            newpop();
        });
    })
    $(function() {
        $('#cancleBtn').on("click", function() {
            location = "mypage_landing_list.php";
        });

        $('#saveBtn').on("click", function() {
            if ($('#gd_id').val() == "") {
                alert('주소록을 선택해 주세요.');
                return;
            }

            if ($('#title').val() == "") {
                alert('제목을 입력해주세요.');
                return;
            }

            if ($('#content').val() == "") {
                alert('내용을 입력해주세요.');
                return;
            }
            if ($('#daily_cnt').val() > 150) {
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
            sub_4_form.action = "mypage.proc.php";
            sub_4_form.target = '';


            $('#sub_4_form').submit();
        });
    })
</script>

<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>
<script language="javascript">
    jQuery(function($) {
        $.datepicker.regional['ko'] = {
            closeText: '닫기',
            prevText: '이전달',
            nextText: '다음달',
            currentText: 'X',
            monthNames: ['1월(JAN)', '2월(FEB)', '3월(MAR)', '4월(APR)', '5월(MAY)', '6월(JUN)',
                '7월(JUL)', '8월(AUG)', '9월(SEP)', '10월(OCT)', '11월(NOV)', '12월(DEC)'
            ],
            monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월',
                '7월', '8월', '9월', '10월', '11월', '12월'
            ],
            dayNames: ['일', '월', '화', '수', '목', '금', '토'],
            dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
            dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
            weekHeader: 'Wk',
            dateFormat: 'yy-mm-dd',
            firstDay: 0,
            isRTL: false,
            showMonthAfterYear: true,
            yearSuffix: ''
        };
        $.datepicker.setDefaults($.datepicker.regional['ko']);

        $('#term, #search_email_date').datepicker({
            showOn: 'button',
            buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
            buttonImageOnly: true,
            buttonText: "달력",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            startDate: '-0m',
            yearRange: 'c-99:c+99',
            minDate: 0,
            maxDate: '',
            onSelect: function(dateText) {
                if ($('#' + dateText).html() == null)
                    $('#date_list').append('<li id="' + dateText + '">' + dateText + "<a href=\"javascript:removeDate('" + dateText + "')\">[삭제]</a></li>");
                else {
                    alert('이미 선택된 날짜입니다.');
                }

                //alert("Selected date: " + dateText + "; input's current value: " + this.value);
            }
        });
    });

    function removeDate(id) {
        $('#' + id).remove();
    }

    function type_check() {}
</script>