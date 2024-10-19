<?
$path = "./";
include_once "_head.php";
if (!$_SESSION['one_member_id']) {
    $chk = false;

    //business_type = B
    //service_type = 1
?>
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

            $('#rday1').datepicker({
                showOn: 'button',
                buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
                buttonImageOnly: true,
                buttonText: "달력",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                yearRange: 'c-99:c+99',
                minDate: '',
                maxDate: ''
            });
            $('#rday2').datepicker({
                showOn: 'button',
                buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
                buttonImageOnly: true,
                buttonText: "달력",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                yearRange: 'c-99:c+99',
                minDate: '',
                maxDate: ''
            });

            $('#krday1, #sday1').datepicker({
                showOn: 'button',
                buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
                buttonImageOnly: true,
                buttonText: "달력",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                yearRange: 'c-99:c+99',
                minDate: '',
                maxDate: ''
            });
            $('#krday2, #sday2').datepicker({
                showOn: 'button',
                buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
                buttonImageOnly: true,
                buttonText: "달력",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                yearRange: 'c-99:c+99',
                minDate: '',
                maxDate: ''
            });
        });
    </script>
    <script language="javascript">
        location.replace('/ma.php');
    </script>
<?
    exit;
}
$sql = "select * from Gn_Member  where mem_id='{$_SESSION['one_member_id']}'";
$sresul_num = mysqli_query($self_con, $sql);
$member = $data = mysqli_fetch_array($sresul_num);


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
</style>
<div class="big_sub">
    <?php include_once "mypage_base_navi.php"; ?>
    <div class="m_div">
        <div class="join">
            <!--//마이페이지 결제정보-->
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

                    $('#rday1').datepicker({
                        showOn: 'button',
                        buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
                        buttonImageOnly: true,
                        buttonText: "달력",
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        yearRange: 'c-99:c+99',
                        minDate: '',
                        maxDate: ''
                    });
                    $('#rday2').datepicker({
                        showOn: 'button',
                        buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
                        buttonImageOnly: true,
                        buttonText: "달력",
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        yearRange: 'c-99:c+99',
                        minDate: '',
                        maxDate: ''
                    });

                    $('#krday1, #sday1').datepicker({
                        showOn: 'button',
                        buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
                        buttonImageOnly: true,
                        buttonText: "달력",
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        yearRange: 'c-99:c+99',
                        minDate: '',
                        maxDate: ''
                    });
                    $('#krday2, #sday2').datepicker({
                        showOn: 'button',
                        buttonImage: 'http://www.admong.com/skin/board/cs_dental/img/calendar.gif',
                        buttonImageOnly: true,
                        buttonText: "달력",
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        yearRange: 'c-99:c+99',
                        minDate: '',
                        maxDate: ''
                    });
                });
            </script>
            <div class="ad_layer_info">
                <div class="layer_in">
                    <span class="layer_close close"><img src="/images/close_button_05.jpg"></span>
                    <div class="pop_title">
                        사업자 정산관리
                    </div>
                    <div class="info_text">
                        <p>

                        </p>
                    </div>

                </div>
            </div>

            <form name="pay_form" action="" method="post" class="my_pay">
                <input type="hidden" name="order_name" value="<?= $order_name ?>" />
                <input type="hidden" name="order_status" value="<?= $order_status ?>" />
                <input type="hidden" name="page" value="<?= $page ?>" />
                <input type="hidden" name="page2" value="<?= $page2 ?>" />
                <div class="a1">
                    <li style="float:left;">사업자정산관리</li>
                    <li style="float:right;"></li>
                    <p style="clear:both"></p>
                </div>
                <div>
                    <div class="p1">
                        <?
                        extract($_REQUEST);
                        $date_today = date("Y-m-d");
                        $search_year = $search_year ? $search_year : date("Y");
                        $search_month = $search_month ? sprintf("%02d", $search_month) : sprintf("%02d", date("m"));
                        /*
결제 금액에서 부가세 빼고
-사업자에게 일반 사용자 결제금액 1만원의 50%, 정산지속 기간 5년간
-사업자A가 추천한 사업자 B의 가입비용 50만원의 50%, 정산기간 1회
==> 지급일
기본: 지급액 입력시 자동나오게
수정: 날짜 수정도 가능하게

*/
                        $nowPage = $_REQUEST['nowPage'] ? $_REQUEST['nowPage'] : 1;
                        $startPage = $nowPage ? $nowPage : 1;


                        // 검색 조건을 적용한다.
                        $searchStr .= $search_key ? " AND (a.buyer_id LIKE '%" . $search_key . "%' or b.mem_phone like '%" . $search_key . "%' or b.mem_name like '%" . $search_key . "%' or a.TotPrice='$search_key' ) " : null;
                        if ($search_start_date && $search_end_date) {
                            $searchStr .= " and balance_date= '$search_year$search_month'";
                        }
                        $search_start_date = $search_year . "-" . sprintf("%02d", $search_month) . "-01";
                        $search_end_date = date('Y-m', strtotime('+1 month', strtotime("$search_start_date"))) . "-01";

                        //$searchStr .=" and a.date >= '$search_start_date' and date <= '$search_end_date'";
                        //$searchStr .=" and balance_date between '$search_start_date 00:00:00' and '$search_end_date 00:00:00'";

                        $searchStr .= " and balance_date ='" . substr(str_replace("-", "", $search_start_date), 0, 6) . "'";
                        //$searchStr .=" and buyer_id='$mem_id'";
                        //$searchStr .=" and DATE_FORMAT(date, '%Y-%m') = '$search_year-$search_month'";

                        $order = $order ? $order : "desc";

                        $total_share = 0;
                        $total_balance = 0;
                        ?>
                        <select name="search_year">
                            <?php for ($i = 2019; $i < 2023; $i++) { ?>
                                <option value="<?= $i ?>" <?php echo $i == $search_year ? "selected" : "" ?>><?= $i ?></option>
                            <?php } ?>
                        </select>
                        <select name="search_month">
                            <?php for ($i = 1; $i < 13; $i++) { ?>
                                <option value="<?= $i ?>" <?php echo sprintf("%02d", $i) == $search_month ? "selected" : "" ?>><?= $i ?></option>
                            <?php } ?>
                        </select>
                        <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
                    </div>
                    <div>
                        <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>번호</td>
                                <td>구매자ID</td>
                                <td>구매자명/직급</td>
                                <td>전화번호</td>
                                <td>구매(월)</td>
                                <td>정산액</td>
                                <td>지급액</td>
                                <td>미지급액</td>
                                <td>지급일시</td>
                            </tr>
                            <?

                            $pageCnt = 20;
                            $query = "
                        	SELECT 
                        	    a.pay_no, 
                        	    b.mem_id,
                        	    b.mem_name,
                        	    b.mem_phone,
                        	    a.price,
                        	    (a.price/1.1*0.01*a.share_per) total_price,
                        	    a.balance_date,
                        	    a.balance_confirm_date,
                        	    a.balance_yn,
                        	    a.share_per,
                        	    a.branch_share_per,
                        	    b.service_type
                        	FROM Gn_Member b
                        	INNER JOIN tjd_pay_result_balance a
                        	       on b.mem_id =a.mem_id
                        	WHERE 1=1 
                	              $searchStr
                	              and seller_id='{$_SESSION['one_member_id']}'
                        	UNION SELECT 
                        	    a.pay_no, 
                        	    b.mem_id,
                        	    b.mem_name,
                        	    b.mem_phone,
                        	    a.price,
                        	    (a.price/1.1*0.01*a.branch_share_per) total_price,
                        	    a.balance_date,
                        	    a.branch_balance_confirm_date as balance_date,
                        	    a.branch_balance_yn as balance_yn,
                        	    a.share_per,
                        	    a.branch_share_per,
                        	    b.service_type
                        	FROM Gn_Member b
                        	INNER JOIN tjd_pay_result_balance a
                        	       on b.mem_id =a.mem_id
                        	WHERE 1=1 
                	              $searchStr
                	              and branch_id='{$_SESSION['one_member_id']}'   
                	              ";
                            $res        = mysqli_query($self_con, $query);
                            $totalCnt    =  mysqli_num_rows($res);

                            $limitStr       = " LIMIT " . (($startPage - 1) * $pageCnt) . ", " . $pageCnt;
                            $number            = $totalCnt - ($nowPage - 1) * $pageCnt;

                            $orderQuery .= " ORDER BY pay_no DESC";

                            $i = 1;
                            $query .= "$orderQuery";
                            $res = mysqli_query($self_con, $query);
                            while ($row = mysqli_fetch_array($res)) {
                                if ($row['total_price'] == 500000) {
                                    $query = "Select * from tjd_pay_result_delaer where m_id='{$row['mem_id']}'";
                                    $sres = mysqli_query($self_con, $query);
                                    $srow = mysqli_fetch_array($sres);
                                    if (substr($row['date'], 0, 10) != substr($srow['regtime'], 0, 10)) {
                                        $row['total_price'] = 0;
                                    }
                                }

                                if ($row['service_type'] == 2) {
                                    $mem_level = "리셀러";
                                } else if ($row['service_type'] == 3) {
                                    $mem_level = "분양자";
                                } else if ($row['service_type'] == 1) {
                                    $mem_level = "이용자";
                                } else {
                                    $mem_level = "FREE";
                                }
                                $balance_fee = 0;
                                $share_fee = $row['total_price'];
                                if ($row['balance_yn'] == "Y")
                                    $balance_fee = $share_fee;
                            ?>
                                <tr>
                                    <td><?= $number-- ?></td>
                                    <td><?= $row['mem_id'] ?></td>
                                    <td><?= $row['mem_name'] ?>/<?php echo $mem_level; ?></td>
                                    <td>
                                        <?= str_replace("-", "", $row['mem_phone']) == $row['sendnum'] || $row['sendnum'] == "" ? str_replace("-", "", $row['mem_phone']) : $row['sendnum'] ?>
                                    </td>

                                    <!-- //Julian 2020-07-04 Problem 18 , 1 -->
                                    <td><?= number_format($row['price']) ?> 원</td>

                                    <td><?= number_format($share_fee) ?> 원</td>
                                    <td><?= number_format($balance_fee) ?> </td>
                                    <td><?= number_format($share_fee - $balance_fee) ?> </td>


                                    <!-- //Julian 2020-07-04 Problem 18 , 2 -->
                                    <td><? if ($row['balance_confirm_date']) {
                                            echo $row['balance_date'];
                                        } else {
                                            echo "";
                                        } ?></td>
                                </tr>
                            <?
                                $sort_no--;
                            }
                            ?>

                        </table>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <Script>
        $(function() {
            $('#allchk').bind("change", function() {
                if ($(this).is(":checked") == true) {
                    $('.checkbox').prop("checked", true);
                } else {
                    $('.checkbox').prop("checked", false);
                }
            });
        });

        function save() {
            var mem_code = "";
            var org_mem_code = "";

            $('input[name=org_mem_code').each(function() {
                if (org_mem_code)
                    org_mem_code += "," + $(this).val();
                else
                    org_mem_code = $(this).val();
            });

            $('.checkbox:checked').each(function() {
                if (mem_code)
                    mem_code += "," + $(this).val();
                else
                    mem_code = $(this).val();
            });

            $.ajax({
                type: "POST",
                url: "ajax/ajax_save.php",
                data: {
                    mode: "save_business",
                    org_mem_code: org_mem_code,
                    mem_code: mem_code,
                },
                success: function(data) {
                    if (data.result == "fail") {
                        alert(data.msg);
                    } else {
                        alert('변경되었습니다.');
                        location = 'mypage_payment.php';
                        return;
                    }
                }
            });
        }

        function change_message(form) {
            if (form.intro_message.value == "") {
                alert('정보를 입력해주세요.');
                form.intro_message.focus();
                return false;
            }

            $.ajax({
                type: "POST",
                url: "ajax/ajax.php",
                data: {
                    mode: "intro_message",
                    intro_message: form.intro_message.value
                },
                success: function(data) {
                    if (data.result == "fail") {
                        alert(data.msg);
                    } else {
                        alert('신청되었습니다.');
                        location = 'mypage_payment.php';
                        return;
                    }
                }
            });

        }

        function showInfo() {
            if ($('#outLayer').css("display") == "none") {
                $('#outLayer').show();
            } else {
                $('#outLayer').hide();
            }
        }
    </Script>
    <?
    include_once "_foot.php";
    ?>

    <script>
        //회원가입체크
        function join_check(frm, modify) {
            if (!wrestSubmit(frm))
                return false;
            var id_str = "";
            var app_pwd = "";
            var web_pwd = "";
            var phone_str = "";
            if (document.getElementsByName('pwd')[0])
                app_pwd = document.getElementsByName('pwd')[0].value;
            if (document.getElementsByName('pwd')[1])
                web_pwd = document.getElementsByName('pwd')[1].value;
            if (frm.id)
                id_str = frm.id.value;
            var msg = modify ? "수정하시겠습니까?" : "등록하시겠습니까?";
            var email_str = frm.email_1.value + "@" + frm.email_2.value + frm.email_3.value;
            if (!modify)
                phone_str = frm.mobile_1.value + "-" + frm.mobile_2.value + "-" + frm.mobile_3.value;
            var birth_str = frm.birth_1.value + "-" + frm.birth_2.value + "-" + frm.birth_3.value;
            var is_message_str = frm.is_message.checked ? "Y" : "N";

            var bank_name = frm.bank_name.value;
            var bank_account = frm.bank_account.value;
            var bank_owner = frm.bank_owner.value;

            if (confirm(msg)) {
                $.ajax({
                    type: "POST",
                    url: "ajax/ajax.php",
                    data: {
                        join_id: id_str,
                        join_nick: frm.nick.value,
                        join_pwd: app_pwd,
                        join_web_pwd: web_pwd,
                        join_name: frm.name.value,
                        join_email: email_str,
                        join_phone: phone_str,
                        join_add1: frm.add1.value,
                        join_zy: frm.zy.value,
                        join_birth: birth_str,
                        join_is_message: is_message_str,
                        join_modify: modify,
                        bank_name: bank_name,
                        bank_account: bank_account,
                        bank_owner: bank_owner
                    },
                    success: function(data) {
                        $("#ajax_div").html(data)
                    }
                })
            }
        }

        function monthly_remove(no) {
            if (confirm('정기결제 해지신청하시겠습니까?')) {
                $.ajax({
                    type: "POST",
                    url: "ajax/ajax_add.php",
                    data: {
                        mode: "monthly",
                        no: no
                    },
                    success: function(data) {
                        alert('신청되었습니다.');
                        location.reload();
                    }
                })

            }
        }
    </script>