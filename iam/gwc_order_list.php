<?
include "inc/header.inc.php";
if ($_SESSION['iam_member_id'] == "") {
    echo "<script>location='/';</script>";
}
extract($_GET);
set_gwc_delivery_state();
?>
<script src="js/shop.js"></script>
<script>
    let data = [];
</script>
<link rel="stylesheet" href="/iam/css/button.css">
<link rel="stylesheet" href="/iam/css/style_gwc.css">
<link rel='stylesheet' href='/plugin/toastr/css/toastr.css' />
<script src='/plugin/toastr/js/toastr.min.js'></script>
<div id='contents_page' style='left:0px; top:0px; width:320px; height:600px; position:absolute;  z-index:1000; display:none; background-color:white;'></div>
<div id="container" class="sub_wrap">
    <div id="content_title" style="padding-bottom:20px;">
        <span>전체주문내역</span>
        <a href="gwc_order_change_list.php" class="btn_small grey" style="float:right;color:white!important;">취소/반품/교환내역</a>
    </div>
    <form method="get" name="search_form" id="search_form " class="form-inline" style="display:flex; margin-bottom: 10px">
        <select name="search_title" class="form-control input-sm " style="margin-right:5px;width: 80px;">
            <option value="">전체</option>
            <option value="1" <? if ($_REQUEST['search_title'] == "1") echo "selected" ?>>결제일</option>
            <option value="2" <? if ($_REQUEST['search_title'] == "2") echo "selected" ?>>상품명</option>
        </select>
        <input type="date" name="search_start_date" style="border: 2px solid lightgrey;width: 115px;">~<input type="date" name="search_end_date" style="border: 2px solid lightgrey;width: 115px;">
        <input type="text" name="search_key" id="search_key" style="width:100px" class="form-control input-sm pull-right" placeholder="" value="<?= $_REQUEST['search_title'] == "2" ? $search_key : '' ?>">
        <button style="margin-left:5px" class="btn btn-sm btn-default pull-right"><i class="fa fa-search"></i></button>
    </form>
    <div class="tbl_head02 tbl_wrap">
        <table>
            <colgroup>
                <col class="w100">
                <col>
                <col class="w100">
                <col class="w140">
            </colgroup>
            <thead>
                <tr>
                    <th scope="col">주문일자</th>
                    <th scope="col">상품정보</th>
                    <th scope="col">배송정보</th>
                    <!--th scope="col">반품/교환</th-->
                </tr>
            </thead>
            <tbody>
                <?
                $searchStr = '';
                if ($search_title == 1) {
                    $join_str = "";
                    if ($search_start_date && $search_end_date)
                        $searchStr .= " AND a.reg_date >= '$search_start_date' and a.reg_date <= '$search_end_date'";
                } else if ($search_title == 2) {
                    if ($search_key) {
                        $join_str = "INNER JOIN Gn_Iam_Contents_Gwc c on a.contents_idx = c.idx ";
                        $searchStr .= " AND c.contents_title like '%$search_key%'";
                    }
                } else {
                    $join_str = "";
                    $searchStr = '';
                }

                $sql = "select a.*,DATEDIFF(CURRENT_DATE(), a.delivery_set_date) AS pass_days from Gn_Gwc_Order a " . $join_str . " where a.mem_id='{$_SESSION['iam_member_id']}' and a.page_type=0 " . $searchStr . " order by a.reg_date desc";
                $result = mysql_query($sql);
                $cart_count = mysql_num_rows($result);
                for ($i = 0; $row = mysql_fetch_array($result); $i++) {

                    if (strpos($row['order_options'], "gallery>>") == 0) {
                        $sql = " select * from Gn_Iam_Contents where idx = '$row[contents_idx]' ";
                        $shop = "gallery";
                    } else {
                        $sql = " select * from Gn_Iam_Contents_Gwc where idx = '$row[contents_idx]' ";
                        $shop = "gwc";
                    }
                    $res = mysql_query($sql);
                    $row_con = mysql_fetch_array($res);

                    if (strpos($row_con['contents_img'], ",") !== false) {
                        $img_arr = explode(",", $row_con['contents_img']);
                        $img = $img_arr[0];
                    } else {
                        $img = $row_con['contents_img'];
                    }

                    if (!$row_con['contents_title']) {
                        $sql_title = "select member_type from tjd_pay_result where no={$row['tjd_idx']}";
                        $res_title = mysql_query($sql_title);
                        $row_title = mysql_fetch_array($res_title);
                        $row_con['contents_title'] = $row_title['member_type'];
                    }
                    $sql_delivery = "select * from delivery_list where id='{$row['delivery']}'";
                    $res_delivery = mysql_query($sql_delivery);
                    $row_delivery = mysql_fetch_array($res_delivery);
                    $show_link = "http://" . $HTTP_HOST . "/iam/gwc_order_pay.php?contents_idx=" . $row['contents_idx'] . "&contents_cnt=" . $row['contents_cnt'] . "&contents_price=" . $row['contents_price'] . "&contents_salary=" . $row['salary_price'] . "&seller_id=" . $row['seller_id'] . "&order_option=" . $row['order_option'] . "&admin=M&order_id=" . $row['id'] . "&mem_id=" . $row['mem_id'] . "&use_point_val=" . $row['use_point'] . "&pay_method=" . $row['payMethod'];
                    if ($shop == "gallery")
                        $show_link .= "&shop=gallery";
                    if ($row['delivery_state'] == 1) {
                        $delivery_state = "상품준비중";
                    }
                    if ($row['delivery_state'] == 2) {
                        $delivery_state = "배송중";
                    }
                    if ($row['delivery_state'] == 3) {
                        $delivery_state = "배송완료";
                    }
                ?>
                    <script>
                        data.push('<?= $row['gwc_order_option_content'] ?>');
                        // console.log(data);
                    </script>
                    <tr class="rows">
                        <td class="tac">
                            <p class="bold"><?= substr($row['reg_date'], 0, 10); ?></p>
                            <p class="padt5" onclick="gotoDetail(
                                                            '<?= $HTTP_HOST ?>',
                                                            '<?= $row['id'] ?>',
                                                            '<?= $row['contents_idx'] ?>',
                                                            '<?= $row['contents_cnt'] ?>',
                                                            '<?= $row['contents_price'] ?>',
                                                            '<?= $row['salary_price'] ?>',
                                                            '<?= $row['seller_id'] ?>',
                                                            '<?= $row['order_option'] ?>',
                                                            '<?= $row['use_point'] ?>',
                                                            '<?= $row['payMethod'] ?>',
                                                            '<?= $row['mem_id'] ?>',
                                                            data['<?= $i ?>'],
                                                            '<?= $row['order_option'] ?>'
                                                            )">
                                <a class="btn_small grey">상세보기</a>
                            </p>
                            <p class="bold"><?= number_format($row['contents_price']); ?>원</p>
                        </td>
                        <td>
                            <div class="ini_wrap">
                                <table class="wfull">
                                    <tr>
                                        <td class="vat tal">
                                            <img onclick="show_prod_page('<?= $row['contents_idx'] ?>', '<?= $HTTP_HOST ?>')" src="<?= $img ?>" style="max-width:60px;">
                                        </td>
                                        <td class="vat tal" style="padding-left: 10px;">
                                            <?
                                            $title = $row_con['contents_title'];
                                            if ($shop == "gallery") {
                                                $gallery_options = explode(">>", $row['order_option']);
                                                if ($gallery_options[1] == "download")
                                                    $title .= "(다운로드)";
                                                else
                                                    $title .= "(원본 : " . $gallery_options[2] . "  " . $gallery_options[3] . ")";
                                            }
                                            echo $title;
                                            ?>
                                            <p class="padt3 fc_999">
                                                <?
                                                $gwc_order_option_content = json_decode($row['gwc_order_option_content'], true);
                                                if (count($gwc_order_option_content) > 0) {
                                                    foreach ($gwc_order_option_content as $data) {
                                                        echo $data['name'] . ' : ' . $data['number'] . '개 (+' . $data['opt_price'] . '원), ';
                                                    }
                                                }
                                                ?>
                                            </p>
                                            <p class="padt3 fc_999">주문번호 : <?= $row['pay_order_no']; ?> / 수량 : <?= $row['contents_cnt']; ?>개 / 배송비 : <?= number_format($row['salary_price']); ?>원</p>
                                            <? if ($gallery_options[1] == "download") {
                                                if ($row['pass_days'] < 16) {
                                                    //$download_link = str_replace("wm.", ".", $img);
                                                    $download_link = "download.php?id=".base64_encode($img);
                                            ?>
                                                    <div style="margin-top: 10px;">
                                                        <a href="<?= $download_link ?>" onclick="toastr.info('다운로드 시작중...');" style="border-radius:10px;background-color: #daf684;height:auto !important;padding:5px 25px;border:none">다운로드 받기</a>
                                                    </div>
                                                <?  } else { ?>
                                                    <div style="margin-top: 10px;display:flex;justify-content: flex-end;">
                                                        <a href="#" style="border-radius:10px;background-color: #e3dede;height:auto !important;padding:5px 25px;border:none" disabled>다운로드 완료</a>
                                                        <p style="padding:5px 25px;">15일간 다운로드 가능</p>
                                                    </div>
                                            <?  }
                                            } ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                        <td class="tar">
                            <p style="font-size: 12px;color: blue;"><?= $delivery_state ?></p><br>
                            <? if ($gallery_options[1] != "download") { ?>
                                <a href="<?= $row_delivery['delivery_link']; ?>" class="btn_small grey">배송정보</a>
                            <? } ?>
                        </td>
                        <!--td class="tac">
                            <p style="padding: 5px;"><a href="javascript:edit_order('1', '<?= $row['id'] ?>')" class="btn_small" style="border: 1px solid lightgrey;padding: 2px;font-size: 11px;color:black!important;<?= $row['prod_state'] == 1 ? 'background-color:lightgrey;' : '' ?>">주문취소<i class="fa fa-angle-right" style="font-weight: bold;margin-left: 10px;"></i></a></p>
                            <p style="padding: 5px;"><a href="javascript:edit_order('2', '<?= $row['id'] ?>')" class="btn_small" style="border: 1px solid lightgrey;padding: 2px;font-size: 11px;color:black!important;<?= $row['prod_state'] == 2 ? 'background-color:lightgrey;' : '' ?>">반품신청<i class="fa fa-angle-right" style="font-weight: bold;margin-left: 10px;"></i></a></p>
                            <p style="padding: 5px;"><a href="javascript:edit_order('3', '<?= $row['id'] ?>')" class="btn_small" style="border: 1px solid lightgrey;padding: 2px;font-size: 11px;color:black!important;<?= $row['prod_state'] == 3 ? 'background-color:lightgrey;' : '' ?>">교환신청<i class="fa fa-angle-right" style="font-weight: bold;margin-left: 10px;"></i></a></p>
                        </td-->
                    </tr>
                <?
                }
                if ($i == 0)
                    echo '<tr><td colspan="4" class="empty_list">자료가 없습니다.</td></tr>';
                ?>
            </tbody>
        </table>
    </div>
    <div id="edit_order_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:500px;">
            <div class="modal-content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <img src="/iam/img/menu/icon_close.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
                    </button>
                </div>
                <div class="modal-title" style="width:100%;font-size:18px;text-align: left;color:white;border-bottom: 1px solid #c8c9c8;background-color: grey;">
                    <label style="padding:7px 20px">취소사유를 선택해주세요.</label>
                </div>
                <div class="modal-header" style="text-align:left;">
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title" value="상품이 마음에 들지 않음(단순변심)" id="mind" style="vertical-align: middle;">
                        <label for="mind">상품이 마음에 들지 않음(단순변심)</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title" value="다른 상품 추가 후 재주문 예정" id="undetail" style="vertical-align: middle;">
                        <label for="undetail">다른 상품 추가 후 재주문 예정</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title" value="상품의 옵션 선택을 잘못함" id="description" style="vertical-align: middle;">
                        <label for="description">상품의 옵션 선택을 잘못함</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title" value="다른 사이트의 가격이 더 저렴함" id="break" style="vertical-align: middle;">
                        <label for="break">다른 사이트의 가격이 더 저렴함</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title" value="8" id="other" style="vertical-align: middle;">
                        <label for="other">기타</label>
                    </div>
                    <input type="hidden" name="edit_type" id="edit_type" value="">
                    <input type="hidden" name="od_id" id="od_id" value="">
                </div>
                <div class="modal-body" id="other_detail" style="text-align:center;" hidden>
                    <textarea id="report_other_msg" maxlength="250" style="width: 100%;border: 1px solid #b5b5b5;height: 100px;" placeholder="추가설명이 필요하면 입력해 주세요."></textarea>
                    <p id="other_desc_letter" style="text-align: right;">0/250</p>
                </div>
                <div class="modal-footer" style="text-align: center;padding:5px;">
                    <button type="button" class="btn-link" style="width: 35%;background: white;color: #6e6a6a;padding: 10px 0px;border: 1px solid #99cc00;border-radius: 10px;" onclick="close_modal()">취소하기</button>
                    <button type="button" class="btn-link" style="width: 35%;background: #99cc00;color: white;padding: 10px 0px;border-radius: 10px;border: 1px solid #99cc00;" onclick="req_report('')">신청하기</button>
                </div>
            </div>
        </div>
    </div>
    <div id="edit_order_modal1" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:500px;">
            <div class="modal-content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <img src="/iam/img/menu/icon_close.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
                    </button>
                </div>
                <div class="modal-title" style="width:100%;font-size:18px;text-align: left;color:white;border-bottom: 1px solid #c8c9c8;background-color: grey;">
                    <label style="padding:7px 20px">어떤 문제가 있나요?</label>
                </div>
                <div class="modal-header" style="text-align:left;">
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title1" value="상품이 마음에 들지 않음" id="mind1" style="vertical-align: middle;">
                        <label for="mind1">상품이 마음에 들지 않음</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title1" value="상품의 구성품/부속품이 들어있지 않음" id="nodetail" style="vertical-align: middle;">
                        <label for="nodetail">상품의 구성품/부속품이 들어있지 않음</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title1" value="상품이 설명과 다름" id="difdesc" style="vertical-align: middle;">
                        <label for="difdesc">상품이 설명과 다름</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title1" value="상품이 파손되어 배송됨" id="break1" style="vertical-align: middle;">
                        <label for="break1">상품이 파손되어 배송됨</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title1" value="상품 결함/기능에 이상이 있음" id="failprod" style="vertical-align: middle;">
                        <label for="failprod">상품 결함/기능에 이상이 있음</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title1" value="판매자로부터 품절 안내 받음" id="sellerintro" style="vertical-align: middle;">
                        <label for="sellerintro">판매자로부터 품절 안내 받음</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title1" value="다른 상품이 배송됨" id="otherprod" style="vertical-align: middle;">
                        <label for="otherprod">다른 상품이 배송됨</label>
                    </div>
                    <div style="padding: 5px;">
                        <input type="radio" name="report_title1" value="8" id="other1" style="vertical-align: middle;">
                        <label for="other1">기타</label>
                    </div>
                    <input type="hidden" name="edit_type1" id="edit_type1" value="">
                    <input type="hidden" name="od_id1" id="od_id1" value="">
                </div>
                <div class="modal-body" id="other_detail1" style="text-align:center;" hidden>
                    <textarea id="report_other_msg1" maxlength="250" style="width: 100%;border: 1px solid #b5b5b5;height: 100px;" placeholder="추가설명이 필요하면 입력해 주세요."></textarea>
                    <p id="other_desc_letter1" style="text-align: right;">0/250</p>
                </div>
                <div class="modal-footer" style="text-align: center;padding:5px;">
                    <button type="button" class="btn-link" style="width: 35%;background: white;color: #6e6a6a;padding: 10px 0px;border: 1px solid #99cc00;border-radius: 10px;" onclick="close_modal()">취소하기</button>
                    <button type="button" class="btn-link" style="width: 35%;background: #99cc00;color: white;padding: 10px 0px;border-radius: 10px;border: 1px solid #99cc00;" onclick="req_report('1')">신청하기</button>
                </div>
            </div>
        </div>
    </div>
    <div id="req_detail_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false" style="top: 100px;">
        <div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:300px;">
            <div class="modal-content">
                <div class="modal-title" style="width:100%;font-size:18px;text-align: center;color:black;border-bottom: 1px solid #c8c9c8;">
                    <label style="padding:7px 20px;font-weight: 100;"><img src="/iam/img/menu/req_info.png" style="width:25px;margin-bottom: 5px;margin-right: 7px;"></img>교환, 환불 신청안내</label>
                </div>
                <div class="modal-body" style="text-align:center;">
                    <p style="text-align:center;">
                        교환, 환불 가능한 기간이 지났습니다.<br>
                        교환, 환불은 배송완료 후<br>
                        7일 이내에 가능합니다.
                    </p>
                </div>
                <div class="modal-footer" style="padding:5px;">
                    <div style="display: flex;justify-content: center;width: 100%">
                        <button type="button" class="btn-link" style="width: 50%;background: #99cc00;color: white;padding: 7px 0px;border-radius: 10px;border: 1px solid #99cc00;" onclick="close_modal()">확인</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./js/gwc_order_list.js"></script>
</div>
<div id="ajax_div" style="display:none"></div>