<?
$path = "./";
include_once "_head.php";
if (!$_SESSION[one_member_id]) {
?>
    <script language="javascript">
        location.replace('/ma.php');
    </script>
<?
    exit;
}
$sql = "select * from Gn_Member  where mem_id='" . $_SESSION[one_member_id] . "'";
$sresul_num = mysql_query($sql);
$data = mysql_fetch_array($sresul_num);
?>
<script type="text/javascript" src="/js/mms_send.js"></script>
<style>
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
        padding: 20px;
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
        margin-top: 20px;
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
<div class="big_div">
    <div class="big_sub">
        <?php //include "mypage_step_navi.php";
        ?>
        <div class="m_div">
            <?php include "mypage_left_menu.php"; ?>
            <div class="m_body">
                <form name="pay_form" action="" method="post" class="my_pay">
                    <input type="hidden" name="page" value="<?= $page ?>" />
                    <input type="hidden" name="page2" value="<?= $page2 ?>" />
                    <div class="a1" style="margin-top:50px; margin-bottom:15px">
                        <li style="float:left;">
                            <div class="popup_holder popup_text">사업자공급관리 신청내역
                                <!-- <div class="popupbox" style="display:none; height: 56px;width: 220px;left: 178px;top: -37px;">예약문자가 이벤트 신청고객에게 발송된 결과를 보여줍니다.<br><br>
                                        <a class = "detail_view" href="https://url.kr/1aHAGx" target="_blank">[자세히 보기]</a>
                                    </div> -->
                            </div>
                        </li>
                        <li style="float:right;">
                            <select name="chanel" id="chanel" class="form-control input-sm" onchange="pay_form.submit()" style="width:100px;font-size: 15px;padding: 6.5px;">
                                <option value="0" <?= $_REQUEST[chanel] == "0" ? "selected" : "" ?>>전체</option>
                                <option value="1" <?= $_REQUEST[chanel] == "1" ? "selected" : "" ?>>지도</option>
                                <option value="2" <?= $_REQUEST[chanel] == "2" ? "selected" : "" ?>>G쇼핑</option>
                                <option value="3" <?= $_REQUEST[chanel] == "3" ? "selected" : "" ?>>N쇼핑</option>
                            </select>
                            <input type="text" name="search_text" placeholder="카드명/주소" id="search_text" value="<?= $_REQUEST[search_text] ?>" style="height:30px;" />
                            <a href="javascript:pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>

                        </li>
                        <p style="clear:both"></p>
                    </div>
                    <div>
                        <a href="calliya.php">전체리스트보기</a> &nbsp;|&nbsp;
                        <a href="calliya_req.php" style="color:#f00">신청리스트보기</a>
                        <select name="cnt_per_page" id="cnt_per_page" class="form-control input-sm pull-right" onchange="pay_form.submit()" style="width:100px;">
                            <option value="20" <?= $_REQUEST[cnt_per_page] == "20" ? "selected" : "" ?>>20개씩</option>
                            <option value="50" <?= $_REQUEST[cnt_per_page] == "50" ? "selected" : "" ?>>50개씩</option>
                            <option value="100" <?= $_REQUEST[cnt_per_page] == "100" ? "selected" : "" ?>>100개씩</option>
                        </select>
                    </div>
                    <div>
                        <div class="p1">
                        </div>
                        <div>
                            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="width:3%;">NO</td>
                                    <!-- <td style="width:5%;">유저ID</td> -->
                                    <td style="width:7%;">IAM ID</td>
                                    <td style="width:5%;">채널</td>
                                    <td style="width:10%;">카드명</td>
                                    <!-- <td style="width:7%;">카드링크</td> -->
                                    <td style="width:10%;">IMAGE</td>
                                    <td style="width:15%;">주소</td>
                                    <td style="width:8%;">폰번호</td>
                                    <td style="width:7%;">결제여부</td>
                                    <td style="width:6%;">등록일자</td>
                                    <td style="width:5%;">컨텐<br>츠수</td>
                                    <td style="width:5%;">조회<br>건수</td>
                                    <td style="width:5%;">업체<br>사용</td>
                                    <td style="width:5%;">취소</td>
                                </tr>
                                <?
                                $sql = "select main_img1 from Gn_Iam_Info where mem_id = 'obmms02'";
                                $result = mysql_query($sql) or die(mysql_error());
                                $row = mysql_fetch_array($result);
                                $default_img =  $row['main_img1'];


                                // $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                // $startPage = $nowPage?$nowPage:1;
                                // $pageCnt = 20;
                                // 검색 조건을 적용한다.
                                if ($_REQUEST[search_text]) {
                                    $searchStr .= " AND (ca_1.card_name LIKE '%" . $_REQUEST[search_text] . "%' or ca_1.card_addr like '%" . $_REQUEST[search_text] . "%' )";
                                }

                                switch ($_REQUEST[chanel]) {
                                    case 0:
                                        $searchStr .= "";
                                        break;
                                    case 1:
                                        $searchStr .= " AND ca_1.ai_map_gmarket=1 ";
                                        break;
                                    case 2:
                                        $searchStr .= " AND ca_1.ai_map_gmarket=2 AND ca_1.card_title='상품소개해요' ";
                                        break;
                                    case 3:
                                        $searchStr .= " AND ca_1.ai_map_gmarket=2 AND ca_1.card_title!='상품소개해요' ";
                                        break;
                                }

                                $count_query = "select count(idx) from Gn_Iam_Name_Card ca_1 WHERE worker_service_state=1 AND req_worker_id !='' AND req_worker_id='$_SESSION[one_member_id]' AND group_id is NULL AND admin_shopping!=0 $searchStr";
                                //$count_result = mysql_query($count_query);
                                //$count_row = mysql_fetch_array($count_result);
                                $redisCache = new RedisCache();
                                //$redisCache->set_debug(true);
                                $count_row = $redisCache->get_query_to_data($count_query);
                                $intRowCount    =  $count_row[0];

                                if ($intRowCount) {
                                    if (!$_REQUEST[cnt_per_page])
                                        $intPageSize = 20;
                                    else
                                        $intPageSize = $_REQUEST[cnt_per_page];
                                    if ($_POST[page]) {
                                        $page = (int)$_POST[page];
                                        $sort_no = $intRowCount - ($intPageSize * $page - $intPageSize);
                                    } else {
                                        $page = 1;
                                        $sort_no = $intRowCount;
                                    }
                                    if ($_POST[page2])
                                        $page2 = (int)$_POST[page2];
                                    else
                                        $page2 = 1;
                                    $int = ($page - 1) * $intPageSize;
                                    $intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);

                                    $query = "SELECT * FROM Gn_Iam_Name_Card ca_1";
                                    $query .= " WHERE worker_service_state=1 AND req_worker_id !='' AND req_worker_id='$_SESSION[one_member_id]' AND group_id is NULL AND admin_shopping!=0 $searchStr";
                                    $limitStr = " LIMIT $int,$intPageSize";
                                    $number    = $totalCnt - ($nowPage - 1) * $pageCnt;
                                    if (!$orderField)
                                        $orderField = "req_data";
                                    $orderQuery .= " ORDER BY $orderField $dir $limitStr";
                                    $i = 1;
                                    $c = 0;
                                    $query .= $orderQuery;
                                    // $res = mysql_query($query);                                    
                                    // while($row = mysql_fetch_array($res)) {
                                    $cache_list = $redisCache->get_query_to_array($query);
                                    //print_r($redisCache->get_debug_info());
                                    for ($i = 0; $i < count($cache_list); $i++) {
                                        $row = $cache_list[$i];
                                        $mem_sql = "select mem_code from Gn_Member where mem_id='$row[mem_id]'";
                                        $mem_res = mysql_query($mem_sql);
                                        $mem_row = mysql_fetch_array($mem_res);

                                        $fquery = "select count(*) from Gn_Iam_Friends where friends_card_idx = " . $row['idx'];
                                        $fresult = mysql_query($fquery);
                                        $frow = mysql_fetch_array($fresult);
                                        //$friend_count	=  $frow[0];

                                        $sql_pay = "select sum(TotPrice) totPrice, date from tjd_pay_result where buyer_id = '" . $row['mem_id'] . "' and end_status='Y'";
                                        $res_result = mysql_query($sql_pay);
                                        $totPriceRow = mysql_fetch_row($res_result);
                                        $totPrice = $totPriceRow[0];

                                        $cquery = "select count(*) from Gn_Iam_Contents where westory_card_url = " . "'$row[card_short_url]'";
                                        $cresult = mysql_query($cquery);
                                        $crow = mysql_fetch_array($cresult);

                                        if ($row['ai_map_gmarket'] == 1) {
                                            $chanel = "지도";
                                        } else if ($row['ai_map_gmarket'] == 2 && $row['card_title'] == "상품소개해요") {
                                            $chanel = "G쇼핑";
                                        } else if ($row['ai_map_gmarket'] == 2 && $row['card_title'] != "상품소개해요") {
                                            $chanel = "N쇼핑";
                                        }
                                ?>
                                        <tr>
                                            <td><?= $sort_no ?></td>
                                            <!-- <td><?= $row[idx] ?></td> -->
                                            <td><?= $row[mem_id] ?></td>
                                            <td><?= $chanel ?></td>
                                            <td style="font-size:12px;">
                                                <?= $row[card_name] ?>
                                            </td>
                                            <!-- <td><a href="http://obmms.net/iam/?<?= strip_tags($row['card_short_url'] . $mem_row[mem_code]) ?>" target="_blank"><?= $row['card_short_url'] ?></a></td> -->
                                            <td>
                                                <div style="">
                                                    <?
                                                    if ($row[main_img1]) {
                                                        $thumb_img =  $row[main_img1];
                                                    } else {
                                                        $thumb_img =  $default_img;
                                                    }
                                                    ?>
                                                    <a href="http://kiam.kr/?<?= strip_tags($row['card_short_url'] . $mem_row[mem_code]) ?>" target="_blank">
                                                        <img class="zoom" src="<?= $thumb_img ?>" style="width:50px;">
                                                    </a>
                                                </div>
                                            </td>
                                            <td><?= $row[card_addr] ?></td>
                                            <td>
                                                <?= $row[card_phone] ?>
                                            </td>
                                            <td><?= $totPrice ? $totPrice : "0" ?></td>
                                            <td style="font-size:12px;"><?= $row[req_data] ?></td>
                                            <td style="font-size:12px;"><?= $crow[0] ?></td>
                                            <td><?= $row[iam_click] ?></td>
                                            <!-- <td><?= $row[req_worker_id] ?></td> -->
                                            <td style="font-size:12px;">
                                                <label class="switch">
                                                    <input type="checkbox" class="chkclick" name="cardclick" id="card_click_<?= $row['idx']; ?>_<?= $row[mem_id] ?>" <?php echo $row['org_use_state'] == "1" ? "checked" : ""; ?>>
                                                    <span class="slider round" name="status_round" id="card_click_<?= $row['idx']; ?>_<?= $row[mem_id] ?>"></span>
                                                </label>
                                            </td>
                                            <td>
                                                <a href="javascript:cancelset('<?= $row[idx] ?>', '<?= $row[mem_id] ?>')" style="cursor:pointer;">취소</a>
                                            </td>
                                        </tr>
                                    <?
                                        $c++;
                                        $sort_no--;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="14">
                                            <?
                                            page_f($page, $page2, $intPageCount, "pay_form");
                                            ?>
                                        </td>
                                    </tr>
                                <?
                                } else {
                                ?>
                                    <tr>
                                        <td colspan="14">
                                            검색된 내용이 없습니다.
                                        </td>
                                    </tr>
                                <?
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<span class="tooltiptext-bottom" id="tooltiptext_add_deny_multi" style="display:none;">
    <table id="contents_key" style="width:100%;">
        <tbody>
            <tr>
                <td class="iam_table" style="width: 20%;border-bottom-color: white;">휴대폰</td>
                <td class="iam_table" style="border-bottom-color: white;width:70%;"><input type="text" id="mem_phone" style="width:100%;"></td>
            </tr>
        </tbody>
    </table>
    <table id="contents_key" style="width:100%;">
        <tbody>
            <tr>
                <td class="iam_table" style="width: 20%;border-bottom-color: white;">주소</td>
                <td class="iam_table" style="border-bottom-color: white;width:70%;"><input type="text" id="mem_add1" style="width:100%;"></td>
            </tr>
        </tbody>
    </table>
    <table id="contents_key" style="width:100%;">
        <tbody>
            <tr>
                <td class="iam_table" style="width: 20%;border-bottom-color: white;">계좌</td>
                <td class="iam_table" style="border-bottom-color: white;width: 20%;"><input type="text" id="bank_name" style="width:100%;" placeholder="은행"></td>
                <td class="iam_table" style="border-bottom-color: white;width: 30%;"><input type="text" id="bank_owner" style="width:100%;" placeholder="계좌번호"></td>
                <td class="iam_table" style="border-bottom-color: white;width: 20%;"><input type="text" id="bank_account" style="width:100%;" placeholder="예금주"></td>
            </tr>
        </tbody>
    </table>
    <table id="contents_key" style="width:100%;">
        <tbody>
            <tr>
                <td class="iam_table" style="width: 20%;">이메일</td>
                <td class="iam_table" style="width: 70%;"><input type="text" id="mem_email" style="width:100%;"></td>
            </tr>
        </tbody>
    </table>
    <input type="hidden" name="mem_id" id="mem_id">
    <input type="hidden" name="data_state" id="data_state" value="0">
    <input type="hidden" name="req_id" id="req_id">
    <input type="hidden" name="card_idx" id="card_idx">
    <input type="hidden" name="check_id" id="check_id">
    <div class="button_app">
        <a href="javascript:cancel_set()" class="btn login_signup" style="width: 40%;background-color: #82c736;border-radius: 3px;padding: 5px;color: white;">취소</a>
        <a href="javascript:save_mem_info()" class="btn login_signup" style="width: 40%;background-color: #82c736;border-radius: 3px;color: white;padding: 5px;">저장</a>
    </div>
</span>
<div id="tutorial-loading"></div>
<script>
    $(function() {
        $('.chkclick').change(function() {
            var msg = "신청하기 전 필독하세요!!\n\n1. 사용하기 클릭 : [사용하기]는 본사에서 제공한 업체  IAM을 해당 업체 대표가 사용하겠다는 의견을 밝힌 경우 사용하기 버튼을 클릭합니다. 클릭과 동시에 추천인과 소속은 소개자 정보로 변경됩니다. \n2. 본사의 확인 조치 : 본사는 사용하기 버튼이 클릭된 경우 해당 업체 대표에게 아래의 사항을 확인합니다. \n 1) 리셀러가 제공한 IAM을 확인했는가 여부\n 2) IAM에서 해당 업체의 상품 판매 여부 \n 3) 상품 판매시 할인 이벤트 진행과 고객에게 포인트 지급 여부 \n 4) IAM 이용료 결제시 상품이용 권리의 이해 여부 \n3. 미사용 전환 : 본사의 확인과정에서 업체 대표가 위 2항에 대해 잘못된 정보 제공에 의한 사용 혹은 의사결정에 오해가 있을 경우 소개 사업자와 협의를 통해 미사용으로 전환합니다. 미사용 전환시 추천과 소속도 이전 상태로 복원됩니다.\n4. 사용하기 재 설정 : 미사용전환이 된 경우 소개 사업자는 해당 업체 대표와 최종 미팅을 통해 사용하기 재설정을 할 수 있습니다.";

            if ($(this).prop("checked")) {
                if (confirm(msg)) {
                    var mem_id1 = $(this).attr('id');
                    var arr = mem_id1.split('_');
                    var mem_id = arr[3];
                    var card_idx = arr[2];
                    $.ajax({
                        type: "POST",
                        url: "/admin/ajax/worker_share_reg.php",
                        dataType: "json",
                        data: {
                            mode: "check_info_exist",
                            mem_id: mem_id
                        },
                        success: function(data) {
                            console.log(data);
                            if (data.mem_phone) {
                                $("#mem_phone").val(data.mem_phone);
                                $("#data_state").val(1);
                            }
                            if (data.mem_add1) {
                                $("#mem_add1").val(data.mem_add1);
                                $("#data_state").val(1);
                            }
                            if (data.bank_name) {
                                $("#bank_name").val(data.bank_name);
                                $("#data_state").val(1);
                            }
                            if (data.bank_owner) {
                                $("#bank_owner").val(data.bank_owner);
                                $("#data_state").val(1);
                            }
                            if (data.bank_account) {
                                $("#bank_account").val(data.bank_account);
                                $("#data_state").val(1);
                            }
                            if (data.mem_email) {
                                $("#mem_email").val(data.mem_email);
                                $("#data_state").val(1);
                            }
                            $("#card_idx").val(card_idx);
                            $("#check_id").val(mem_id1);
                            $("#mem_id").val(mem_id);
                            $("#req_id").val('<?= $_SESSION[one_member_id] ?>');
                        },
                        error: function() {
                            alert('삭제 실패');
                        }
                    });
                    $("#tooltiptext_add_deny_multi").show();
                    $("#tutorial-loading").show();
                } else {
                    $(this).prop("checked", false);
                }
            } else {
                if (confirm("업체사용을 해지 하시겠습니까?")) {
                    var mem_id1 = $(this).attr('id');
                    var arr = mem_id1.split('_');
                    var mem_id = arr[3];
                    var card_idx = arr[2];
                    $.ajax({
                        type: "POST",
                        url: "/admin/ajax/worker_share_reg.php",
                        dataType: "json",
                        data: {
                            mode: "uncheck_use_org",
                            mem_id: mem_id,
                            card_idx: card_idx
                        },
                        success: function(data) {
                            console.log(data);
                            alert("해지 되었습니다.");
                            // location.reload();
                        },
                        error: function() {
                            alert('삭제 실패');
                        }
                    });
                } else {
                    $(this).prop("checked", true);
                }
            }
        });
    });

    function cancelset(idx, mem_id) {
        var msg1 = "취소하게 되면 본 리스트는 신청리스트에서 삭제되고 전체리스트로 이동합니다. 삭제하시겠습니까?";
        var state = $("input[id=card_click_" + idx + "_" + mem_id + "]").prop("checked");
        if (state) {
            alert("이미 판매자가 사용중이기때문에 판매자, 본사와 협의한 후에 취소할수 있습니다.");
            return;
        } else {
            if (!confirm(msg1)) {
                return;
            } else {
                $.ajax({
                    type: "POST",
                    url: "/admin/ajax/worker_share_reg.php",
                    data: {
                        mode: "cancel_req_id",
                        card_idx: idx
                    },
                    success: function(data) {
                        if (data == "1") {
                            alert("취소 되었습니다.");
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('삭제 실패');
                    }
                });
            }
        }
    }

    function cancel_set() {
        var check_id = $("#check_id").val();
        $("input[id=" + check_id + "]").prop("checked", false);
        $("#tooltiptext_add_deny_multi").hide();
        $("#tutorial-loading").hide();
    }

    function save_mem_info() {
        var data_state = $("#data_state").val();
        var mem_id = $("#mem_id").val();
        var mem_phone = $("#mem_phone").val();
        var mem_add1 = $("#mem_add1").val();
        var bank_name = $("#bank_name").val();
        var bank_owner = $("#bank_owner").val();
        var bank_account = $("#bank_account").val();
        var mem_email = $("#mem_email").val();
        var req_id = $("#req_id").val();
        var card_idx = $("#card_idx").val();

        if (data_state == "1") {
            alert('이미 입력된 정보가 있습니다. 현재 입력 정보로 변경됩니다.');
        }

        $.ajax({
            type: "POST",
            url: "/admin/ajax/worker_share_reg.php",
            data: {
                mode: "update_member_info",
                mem_id: mem_id,
                mem_phone: mem_phone,
                mem_add1: mem_add1,
                bank_name: bank_name,
                bank_owner: bank_owner,
                bank_account: bank_account,
                mem_email: mem_email,
                req_id: req_id,
                card_idx: card_idx
            },
            success: function(data) {
                if (data == "1") {
                    alert("저장 되었습니다.");
                    location.reload();
                }
            },
            error: function() {
                alert('삭제 실패');
            }
        });
    }
</script>
<script language="javascript" src="./js/rlatjd_fun.js?m=<?php echo time(); ?>"></script>
<script language="javascript" src="./js/rlatjd.js?m=<?php echo time(); ?>"></script>
<div id='open_pop_div' class="open_1">
    <div class="open_2" onmousedown="down_notice(open_pop_div,event)" onmousemove="move(event)" onmouseup="up()">
        <li class="open_2_1 group_title_open">그룹 전화번호</li>
        <li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_pop_div)"><img src="images/div_pop_01.jpg" /></a></li>
    </div>
    <div class="open_3" style="width:500px;">
        <iframe id="pop_iframe" src="" width="100%" height="650" frameborder="0" scrolling="auto"></iframe>
    </div>
</div>
<?
include_once "_foot.php";
?>