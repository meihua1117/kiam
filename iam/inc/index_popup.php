<!--대표이미지 팝업-->
<div id="popup" class="main_image_popup" style="overflow-y:auto">
    <!-- 팝업 시작 -->
    <div class="popup-wrap form-wrap" id="slider" style="max-width: 768px !important;">
        <input type="hidden" id="slider_card_idx" value="">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="closePopup">
            <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
        </button>
        <div style="background:#99cc00; width:100%;text-align:center;border-radius:12px 12px 0px 0px">
            <h4 style="color:white;padding:15px;">대표 이미지 게시하기</h4>
        </div>
        <div style="margin-left:auto;margin-right:auto;width:215px;position:relative">
            <? if ($video_upload_status == 'Y') { ?>
                <p style="position:fixed;right:5px;top:95px">이미지/영상</p>
                <label class="step_switch" style="position:fixed;right: 10px;" id="video_slide_box">
                    <input type="checkbox" id="video_slide_state" value="" style="display: none" <?= $cur_card['video_status'] == 'V' ? 'checked' : '' ?>>
                    <span class="slider round" id="video_slide_round"></span>
                </label>
            <? } ?>
            <p style="color:#555;margin:10px 20px 0px 20px;font-size:10px;font-weight: 400;">-대표이미지는 한 개 이상 꼭 등록해주세요.</p>
            <p style="color:#555;margin:0px 20px;font-size:10px;font-weight: 400;">-이미지는 4:3 비율이 최적 사이즈입니다.</p>
        </div>
        <div style="display:<?= $cur_card['video_status'] == 'V' ? 'none' : 'flex' ?>;<?= $video_upload_status == 'Y' ? 'margin-top:35px' : '' ?>" id="main_slide_image">
            <div class="attr-row">
                <div class="attr-value">
                    <div style="width:100px;background:white;font-size:14px;border-radius: 5px;padding: 2px;margin-left:10px;">
                        <div>
                            <input type="radio" name="main_type1" value="f">파일 등록
                        </div>
                        <div>
                            <input type="radio" name="main_type1" value="u" id="main_type1">이미지주소
                        </div>
                    </div>
                    <? if ($main_img1 == str_replace("http://www.kiam.kr", $cdn_ssl, $main_card_row['main_img1'])) {
                        if ($request_short_url != $main_card_row['card_short_url'])
                            $main_img1_del_status = false;
                        else
                            $main_img1_del_status = true;
                    } else
                        $main_img1_del_status = true;
                    ?>
                    <div class="input-wrap" style="width:100%;text-align:center;">
                        <input type="file" class="input" style="display:none" name="main_upload1" id="main_upload1" accept=".jpg,.jpeg,.png,.gif,.svc">
                        <div class="img-rounded" id="main_upload_img1" style="border:1px solid #ddd;position:relative;padding-bottom: 80%;margin:10px auto;background-image:url('<?= cross_image($main_img1) ?>'); background-size: cover;background-position: top;">
                            <img src="/iam/img/menu/icon_main_close.png" style="position:absolute;top:-5px;right:-5px;cursor:pointer;width:24px" onclick="mainImageDel('main_img1','<?= $main_img1_del_status ?>');">
                        </div>
                    </div>
                </div>
            </div>
            <div class="attr-row">
                <div class="attr-value">
                    <div style="width:100px;background:white;font-size:14px;border-radius: 5px;padding: 2px;margin-left:10px;">
                        <div>
                            <input type="radio" name="main_type2" value="f">파일 등록
                        </div>
                        <div>
                            <input type="radio" name="main_type2" value="u" id="main_type2">이미지주소
                        </div>
                    </div>
                    <? if ($main_img2 == str_replace("http://www.kiam.kr", $cdn_ssl, $main_card_row['main_img2'])) {
                        if ($request_short_url != $main_card_row['card_short_url'])
                            $main_img2_del_status = false;
                        else
                            $main_img2_del_status = true;
                    } else
                        $main_img2_del_status = true;
                    ?>
                    <div class="input-wrap" style="width:100%;text-align:center;">
                        <input type="file" class="input" style="display:none" name="main_upload2" id="main_upload2" accept=".jpg,.jpeg,.png,.gif,.svc">
                        <div class="img-rounded" id="main_upload_img2" style="border:1px solid #ddd;position:relative;padding-bottom: 80%;margin:10px auto;background-image:url('<?= cross_image($main_img2) ?>'); background-size: cover;background-position: top;">
                            <img src="/iam/img/menu/icon_main_close.png" style="position:absolute;top:-5px;right:-5px;cursor:pointer;width:24px" onclick="mainImageDel('main_img2','<?= $main_img2_del_status ?>');">
                        </div>
                    </div>
                </div>
            </div>
            <div class="attr-row">
                <div class="attr-value">
                    <div style="width:100px;background:white;font-size:14px;border-radius: 5px;padding: 2px;margin-left:10px;">
                        <div>
                            <input type="radio" name="main_type3" value="f">파일 등록
                        </div>
                        <div>
                            <input type="radio" name="main_type3" value="u" id="main_type3">이미지주소
                        </div>
                    </div>
                    <? if ($main_img3 == str_replace("http://www.kiam.kr", $cdn_ssl, $main_card_row['main_img3'])) {
                        if ($request_short_url != $main_card_row['card_short_url'])
                            $main_img3_del_status = false;
                        else
                            $main_img3_del_status = true;
                    } else
                        $main_img3_del_status = true;
                    ?>
                    <div class="input-wrap" style="width:100%;text-align:center;">
                        <input type="file" class="input" style="display:none" name="main_upload3" id="main_upload3" accept=".jpg,.jpeg,.png,.gif,.svc">
                        <div class="img-rounded" id="main_upload_img3" style="border:1px solid #ddd;position:relative;padding-bottom: 80%;margin:10px auto;background-image:url('<?= cross_image($main_img3) ?>'); background-size: cover;background-position: top;">
                            <img src="/iam/img/menu/icon_main_close.png" style="position:absolute;top:-5px;right:-5px;cursor:pointer;width:24px" onclick="mainImageDel('main_img3','<?= $main_img3_del_status ?>');">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="display:<?= $cur_card['video_status'] == 'V' ? 'flex' : 'none' ?>;<?= $video_upload_status == 'Y' ? 'margin-top:35px' : '' ?>" id="main_slide_video">
            <div class="attr-row">
                <div class="attr-value">
                    <div style="width:100px;background:white;font-size:14px;border-radius: 5px;padding: 2px;margin-left:10px;">
                        <div>
                            <input type="radio" name="main_type_v" value="f">파일 등록
                        </div>
                        <div>
                            <input type="radio" name="main_type_v" value="u" id="main_type_v" <?= $cur_card['video'] ? 'checked' : '' ?>>영상주소
                        </div>
                    </div>
                    <div class="input-wrap" style="width:100%;text-align:center;">
                        <input type="file" accept="video/*" id="videoInput" style="display: none">
                        <div class="img-rounded" id="main_upload_video" style="border:1px solid #ddd;position:relative;margin:10px auto;">
                            <img src="/iam/img/menu/icon_main_close.png" style="position:absolute;top:-5px;right:-5px;cursor:pointer;width:24px;z-index:10" onclick="mainImageDel('video',true);">
                            <video src="<?= $cur_card['video'] ?>" type="video/mp4" autoplay muted loop style="width:100%;" id="main_video_preview">
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="padding:5px">
            <input type="text" class="input" style="font-size: 12px;display: none;width:100%;border:1px solid #ddd;padding: 10px 10px;" name="main_upload1_link" id="main_upload1_link" placeholder='사이트에서 찾은 이미지에서 "이미지 주소 복사" 후 주소를 입력하세요.' value='<?= $main_img1 ?>'>
            <input type="text" class="input" style="font-size: 12px;display: none;width:100%;border:1px solid #ddd;padding: 10px 10px;" name="main_upload2_link" id="main_upload2_link" placeholder='사이트에서 찾은 이미지에서 "이미지 주소 복사" 후 주소를 입력하세요.' value='<?= $main_img2 ?>'>
            <input type="text" class="input" style="font-size: 12px;display: none;width:100%;border:1px solid #ddd;padding: 10px 10px;" name="main_upload3_link" id="main_upload3_link" placeholder='사이트에서 찾은 이미지에서 "이미지 주소 복사" 후 주소를 입력하세요.' value='<?= $main_img3 ?>'>
            <input type="text" class="input" style="font-size: 12px;<?= $cur_card['video'] ? '' : 'display: none;' ?>width:100%;border:1px solid #ddd;padding: 10px 10px;" name="main_upload_video_link" id="main_upload_video_link" placeholder='사이트에서 찾은 동영상에서 "링크 주소 복사" 후 주소를 입력하세요.' value='<?= $cur_card['video'] ?>'>
        </div>
        <div class="button-wrap" style="padding:0px;display:flex">
            <a href="#" class="btn btn-default btn-left" id="closePopup" style="padding:10px 0px;width:50%;">취소하기</a>
            <a href="javascript:mainImageUpload();" class="btn btn-active btn-right" style="padding:10px 0px;width:50%;margin-left:0px">게시하기</a>
        </div>
    </div>
    <div class="popup-overlay"></div>
</div><!-- // 팝업 끝 -->
<!-- =============================================================================================== -->
<!--데일리 발송 팝업-->
<div id="popup" class="daily_popup">
    <!-- 팝업 시작 -->
    <div class="popup-wrap" id="dailysend">
        <div class="text-wrap">
            <h3>내 아이엠을 내 폰 지인 모두에게 보내기!</h3><br><br>
            새명함이 나오면 지인들께 보내고<br>
            싶은데 방법이 마땅치 않지요?<br><br>

            ①데일리발송기능과 ②내 폰안의 무료 문자를<br>
            이용하여 내 폰주소록의 모든 지인에게<br>
            매일매일 자동으로 발송해보세요!<br>
            <br><br>
            <h3>내 아이엠을 보내는 절차!</h3><br><br>

            <a href="/iam/join.php">첫째 회원가입 먼저 해야지요(클릭)</a><br>
            <a href="https://www.onestore.co.kr/userpoc/apps/view?pid=0000738690">둘째 내 폰의 문자를 보내려면 앱을
                설치해야지요!(클릭)</a><br>
            셋째 데일리발송을 시작해요!<br>
            ※ 아이엠을 보내는 기능은 무료이지만 일반 메시지를 보내는 것은 유료입니다.</h3>
        </div>
        <div class="button-wrap">
            <? if ($_SESSION['iam_member_id']) { ?>
                <a id="closePopup" class="buttons is-cancel">다음에보내기</a>
                <a id="daily_popup_content" target="_blank" onclick="daily_send_pop_close()" class="buttons is-save">시작하기</a>
            <? } else { ?>
                <a id="closePopup" class="buttons is-cancel">다음에보내기</a>
                <a href="/iam/login.php" target="_blank" class="buttons is-save" onclick="daily_send_pop_close()">시작하기</a>
            <? } ?>
        </div>
    </div>
    <div class="popup-overlay"></div>
</div><!-- // 팝업 끝 -->
<!--전체서비스 모달-->
<div id="total_service_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" role="document" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal">
                </button>
            </div>
            <div class="modal-title">
                <label>전체서비스</label>
            </div>
            <div class="modal-body" style="padding:0px;border-bottom-left-radius: 12px;border-bottom-right-radius: 12px;">
                <table class="table table-borderless">
                    <tbody>
                        <?
                        $menu_site = explode(".", $HTTP_HOST);
                        if ($menu_site[0] == "www")
                            $menu_host = "kiam";
                        else
                            $menu_host = $menu_site[0];
                        if ($domainData['admin_iam_menu'] == 0) {
                            $menu_query = "select * from Gn_Iam_Menu where site_iam='kiam' and menu_type='TR' and use_yn = 'y' order by display_order";
                        } else {
                            $menu_query = "select * from Gn_Iam_Menu where site_iam='{$menu_host}' and menu_type='TR' and use_yn = 'y' order by display_order";
                        }
                        $menu_res = mysql_query($menu_query);
                        $odd = 0;
                        while ($menu_row = mysql_fetch_array($menu_res)) {
                            $func = str_replace("card_link", $request_short_url . $card_owner_code, $menu_row['move_url']);
                            $func = str_replace("prewin", $cur_win, $func);
                            $func = str_replace("card_name", $cur_card['card_name'], $func);
                            if (!strstr($func, "javascript"))
                                $target = "target=\"_blank\"";
                            else
                                $target = "";
                            if ($menu_row['page_type'] == "payment" && !$is_pay_version)
                                $func = "";
                            if ($menu_row['page_type'] == "login" && !$_SESSION['iam_member_id'])
                                $func = "/iam/login.php";
                            if ($menu_row['page_type'] == "payment" && $is_pay_version) {
                                if (strstr($func, "pay.php") && $_SESSION['iam_member_subadmin_id'] && $domainData['pay_link']) {
                                    $func = $domainData['pay_link'];
                                }
                            }
                            $html = "";
                            if ($func != "") {
                                $func = str_replace("domainData[kakao]", $domainData['kakao'], $func);
                                if (strstr($func, "mypage_report") && $member_iam['service_type'] < 2)
                                    $func = str_replace("mypage_report.php", "mypage_report_list.php", $func);
                                if ($odd == 0) {
                                    $html = "<tr>";
                                }
                                $html .= "<td style=\"border:none;background:white;padding:8px !important;text-align:left;\"><a href=\"" . $func . "\" style=\"text-decoration-line: none;margin-left:5px;\" $target>" .
                                    "<img src=\"" . $menu_row['img_url'] . "\" title=\"" . $menu_row['menu_desc'] . "\" style=\"height: 20px\">&nbsp" .
                                    $menu_row['title'] . "</a></td>";
                                if ($odd == 1) {
                                    $html .= "</tr>";
                                }
                                $odd++;
                                $odd = $odd % 2;
                            }
                            echo $html;
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- ./콜백메시지 설정리스트 모달 -->
<div id="callback_list_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: auto;">
        <!-- Modal content-->
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" style="opacity:2 !important">
                    <img src="/iam/img/menu/icon_close_white.png" style="width:24px;opacity:2 !important" class="close" data-dismiss="modal">
                </button>
            </div>
            <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                <label>콜백 메시지 리스트</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;text-align: center;overflow:auto;max-width:900px;">
                    <table style="width:860px">
                        <thead>
                            <th class="iam_table" style="width:30px;">No</th>
                            <th class="iam_table" style="width:100px;">타이틀</th>
                            <th class="iam_table" style="width:100px;">메시지</th>
                            <th class="iam_table" style="width:100px;">이미지</th>
                            <th class="iam_table" style="width:75px;">보기/링크</th>
                            <th class="iam_table" style="width:55px;">조회수</th>
                            <th class="iam_table" style="width:75px;">유지/해지</th>
                            <th class="iam_table" style="width:85px;">등록일</th>
                            <!--th class="iam_table" style="width:100px;">발송횟수</th-->
                            <th class="iam_table" style="width:65px;">노출여부</th>
                            <th class="iam_table" style="width:75px;">수정/삭제</th>
                            <? if ($_SESSION['iam_member_id'] == "iam1") { ?>
                                <th class="iam_table">샘플</th>
                            <? } ?>
                        </thead>
                        <tbody id="callback_msg_set_list">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ./콜백메시지 설정리스트 수정 모달 -->
<div id="callback_list_edit_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <!-- Modal content-->
        <div class="modal-content" id="edit_callback_modal_content">
            <div>
                <button type="button" class="close" data-dismiss="modal" style="opacity:2 !important">
                    <img src="/iam/img/menu/icon_close_white.png" style="width:24px;opacity:2 !important" class="close" data-dismiss="modal">
                </button>
            </div>
            <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                <label>콜백 메시지</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <table style="width:100%">
                        <form method="post" id="dForm_call_edit" name="dForm_call_edit" action="/ajax/edit_event_callback.php" enctype="multipart/form-data">
                            <tbody id="edit_event">
                                <tr>
                                    <input type="hidden" name="save" value="save">
                                    <input type="hidden" id="call_event_idx" name="call_event_idx" value="">
                                    <th class="iam_table" style="width:85px">아이디</th>
                                    <td class="iam_table"><?= $_SESSION['iam_member_id']; ?></td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:85px">요청메시지 제목</th>
                                    <td class="iam_table">
                                        <textarea style="width:100%;height: 50px;resize:vertical;" name="event_title_call" id="event_title_call" value=""></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:85px">요청메시지 내용</th>
                                    <td class="iam_table">
                                        <textarea style="width:100%;min-height: 120px;resize:vertical;" name="event_desc_call" id="event_desc_call" value=""></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:85px">콜백 타이틀</th>
                                    <td class="iam_table">
                                        <textarea style="width:100%;height: 50px;resize:vertical;" name="call_event_title" id="call_event_title" value=""></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:85px">콜백 메시지</th>
                                    <td class="iam_table"><textarea style="width:100%;min-height: 120px;resize:vertical;" name="call_event_desc" id="call_event_desc" value=""></textarea></td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:85px">링크주소</th>
                                    <td class="iam_table"><input type="text" style="width:100%;" name="call_iam_link" id="call_iam_link" value=""></td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:85px">이미지</th>
                                    <td class="iam_table"><input type="file" name="call_event_img" style="width:200px;"><span id="call_img_event"></span> </td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:85px">메시지<br>단축주소</th>
                                    <td class="iam_table"><input type="text" style="width:100%;" name="call_short_url" id="call_short_url" value=""></td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:85px">조회수</th>
                                    <td class="iam_table"><input type="text" style="width:100%;" name="call_read_cnt" id="call_read_cnt" value=""></td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:85px">등록일시</th>
                                    <td class="iam_table"><input type="text" style="width:100%;" name="call_regdate1" id="call_regdate1" value=""></td>
                                </tr>
                        </form>
                        <tr>
                            <th class="iam_table" style="width:85px;">발송하기</th>
                            <td class="iam_table"><button style="float:left;padding: 5px;" onclick="app_set_list('self', 'callback')">셀프폰 발송하기</button><button style="float:left;padding: 5px;margin-left:10px;" onclick="app_set_list('push', 'callback')">푸시형 전송하기</button></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="container" id="app_sets_member_call" style="margin-top: 20px;text-align: center;display:none;">
                    <h2 style="text-align: center;" id="send_type_title_call"></h2>
                    <input type="hidden" name="send_type_call" id="send_type_call">
                    <table class="table table-bordered" style="margin-bottom:0px">
                        <tbody>
                            <tr class="hide_spec">
                                <td class="bold" id="remain_count" data-num="" style="width:70px;">전송하기<br>
                                    <textarea name="app_set_mbs_count_call" id="app_set_mbs_count_call" style="width:100%; height:50px;color: red;font-size: 12px" readonly="" data-num="0" placeholder="0건"></textarea>
                                </td>
                                <td colspan="2">
                                    <div style="display:flex">
                                        <textarea name="app_set_mbs_id_call" id="app_set_mbs_id_call" style="border: solid 1px #b5b5b5;width:100%; height:100px;" data-num="0" placeholder="전송할 친구 아이디를 입력하세요.<컴마로 구분>"></textarea>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="display:flex;width:100%">
                        <button type="button" class="btn btn-default btn-left" onclick="cancel_app_list('callback')">취소</button>
                        <button type="button" class="btn btn-active btn-right" onclick="send_msg_applist('callback')">전송하기</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="padding:0px;display:flex">
                <button type="button" class="btn btn-default btn-left" onclick="$('#callback_list_edit_modal').modal('hide');">뒤로가기</button>
                <button type="button" class="btn btn-active btn-right" onclick="save_call_edit_ev()">저장</button>
            </div>
        </div>
    </div>
</div>
<!-- QR코드 모달 -->
<div id="qr_code_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <!-- Modal content-->
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" style="opacity:2 !important">
                    <img src="/iam/img/menu/icon_close_white.png" style="width:24px;opacity:2 !important" class="close" data-dismiss="modal">
                </button>
            </div>
            <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#99cc00;color:white;">
                <label>QR 코드만들기</label>
            </div>
            <div class="modal-body">
                <div class="container" id="qr_code" style="text-align: center;align-items:center;display:flex;justify-content: center;">

                </div>
            </div>
        </div>
    </div>
</div>
<!--카드 편집 모달-->
<div id="card_con_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" role="document" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal">
                </button>
            </div>
            <div class="modal-title">
                <label>카드 편집하기</label>
            </div>
            <div class="modal-header" style="padding:0px;border-bottom-left-radius: 12px;border-bottom-right-radius: 12px;">
                <?
                $card_status = $cur_card['phone_display'] == 'Y' ? 'N' : 'Y';
                $card_title = $cur_card['phone_display'] == 'Y' ? $MENU['IAM_CARD_M']['CM2_TITLE'] : $MENU['IAM_CARD_M']['CM1_TITLE'];
                ?>
                <div class="modal_list_div" onclick="card_phone_display('<?= $cur_card['idx'] ?>', '<?= $_SESSION['iam_member_id'] ?>','<?= $card_status ?>');" title="<?= $card_title ?>">
                    <? if ($cur_card['phone_display'] == 'Y') { ?>
                        <img src="/iam/img/menu/icon_card_unlock.png" style="height: 24px">
                        <p><?= $MENU['IAM_CARD_M']['CM2']; ?></p>
                    <? } else { ?>
                        <img src="/iam/img/menu/icon_card_lock.png" style="height: 24px">
                        <p><?= $MENU['IAM_CARD_M']['CM1']; ?></p>
                    <? } ?>
                </div>
                <? if (($card_owner_site == $user_site || $card_owner_site == $bunyang_site)) { ?>
                    <div class="modal_list_div" onclick="$('#card_con_modal').modal('hide');open_cont_order_popup('<?= $cur_win ?>', '<?= $cur_card['card_short_url'] ?>', '<?= $group_card_url ?>');" title="카드 콘텐츠 편집하기">
                        <img src="/iam/img/menu/icon_card_con_edit.png" style="height: 24px">
                        <p>카드 콘텐츠 편집</p>
                    </div>
                <? } ?>
                <?
                $post_display_status = $post_display == 0 ? 1 : 0;
                $post_img = $post_display == 0 ? "/iam/img/menu/icon_post_show.png" : "/iam/img/menu/icon_post_hide.png";
                $post_txt = $post_display == 0 ? $MENU['IAM_CARD_M']['CM7'] : $MENU['IAM_CARD_M']['CM8'];
                ?>
                <div class="modal_list_div" onclick="showCardLike('<?= $post_display_status ?>','<?= $request_short_url ?>');" title="<?= $MENU['IAM_CARD_M']['CM7_TITLE']; ?>">
                    <img src="<?= $post_img ?>" style="height: 24px">
                    <p><?= $post_txt ?></p>
                </div>
                <div class="modal_list_div" onclick="$('#card_con_modal').modal('hide');showSNSModal('<?= $cur_win ?>','Y');" title="<?= $MENU['IAM_CARD_M']['CM7_TITLE']; ?>">
                    <img src="/iam/img/menu/icon_share_black.png" style="height: 24px">
                    <p><?= $MENU['IAM_CARD_M']['CM17']; ?></p>
                </div>
                <div class="modal_list_div" onclick="$('#card_con_modal').modal('hide');show_manage_auto(0);" title="콘텐츠 오토데이트 관리">
                    <img src="/iam/img/menu/icon_auto_date.png" style="height: 24px;">
                    <p>오토 데이트 기능</p>
                </div>
                <? if (($card_owner_site == $user_site || $card_owner_site == $bunyang_site)) {
                    if ($is_pay_version) { ?>
                        <div class="modal_list_div" onclick="$('#card_con_modal').modal('hide');open_iam_mall_popup('<?= $_SESSION['iam_member_id'] ?>',2,'<?= $cur_card['idx'] ?>');" title="<?= $MENU['IAM_CARD_M']['CM13_TITLE']; ?>">
                            <img src="/iam/img/menu/icon_shop.png" style="height: 24px">
                            <p><?= $MENU['IAM_CARD_M']['CM13']; ?></p>
                        </div>
                    <? }
                    if ($first_card_idx != $cur_card['idx']) { ?>
                        <div class="modal_list_div" onclick="namecard_del('<?= $cur_card['idx'] ?>');" title="<?= $MENU['IAM_CARD_M']['CM11_TITLE']; ?>">
                            <img src="/iam/img/menu/icon_card_del.png" style="height: 24px">
                            <p><?= $MENU['IAM_CARD_M']['CM11']; ?></p>
                        </div>
                <?  }
                } ?>
            </div>
        </div>
    </div>
</div>
<!--인물검색으로 아이엠 만들기 팝업창 -->
<div id="people_iam_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" role="document" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal">
                </button>
            </div>
            <div class="modal-title">
                <label>AI로 자동 IAM 만들기</label>
            </div>
            <div class="modal-header" style="padding:0px">
                <div class="container" style="margin-top:30px;">
                    <p style="margin:0px 10px;font-size:15px;font-weight:700;padding:10px 0px;text-align:center;border:1px solid #dddddd">웹페이지 주소만 입력하면 IAM카드가 <br>자동으로 생성되는 서비스입니다.</p>
                </div>
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <a href="https://www.youtube.com/playlist?list=PLP7cr8I5HQ8iLO-oGYvCOFygjKYnjYO28" target="_blank" style="font-size:15px; color:#99cc00;">AI로 자동 아이엠 만들기 영상보러가기</a>
                </div>
                <div class="container" style="margin-top:10px;">
                    <p style="margin:0px 10px;font-size:15px;font-weight:700;">포인트충전</p>
                </div>
                <div class="container" style="margin-top: 5px;">
                    <div style="margin-top:10px;display: flex;justify-content: space-around;font-size:18px;font-weight:700;">
                        <? if ($is_pay_version) { ?>
                            <button class="people_iam settlement_btn" style="padding:20px 0px" onclick="point_chung()">포인트로<br>충전하기</button>
                        <? } ?>
                        <p style="padding:20px 0px" id="point_show_share">보유포인트<br><a style="color:red;text-decoration-line: none;"><?= number_format($Gn_point); ?>P</a></p>
                        <p class="people_iam settlement_btn sharepoint" data="webpage" style="padding:20px 0px;text-align:center; cursor:pointer;">포인트<br>쉐어하기</p>
                    </div>
                    <div style="margin:30px 0px;display: flex;justify-content: space-around;">
                        <button type="button" class="btn btn-default btn-submit" style="border-radius: 20px;width:40%;font-size:14px;font-weight:700;" onclick="show_contents()">이용내역보기</button>
                        <button type="button" class="btn btn-default btn-submit" style="border-radius: 20px;width:40%;font-size:14px;font-weight:700;" onclick="location.href='mypage_payment.php'">포인트내역보기</button>
                    </div>
                </div>
                <div class="container" style="margin: 20px 0px;">
                    <p style="font-size:15px;border: 1px solid #dddddd;margin:0px 10px;padding:0px 10px">
                        <br>
                        [확인하세요]<br>
                        1. 방법확인 : AI로 자동 IAM 만들기 영상으로 방법을 확인하세요.<br>
                        2. 검색확인 : 네이버에서 인물, 지도, 쇼핑, 블로그, 유튜브 등에서 검색어를 입력하고 웹페이지 콘텐츠정보와 콘텐츠수를 확인하세요.<br>
                        3. 수집건수 : 한 개의 웹주소당 유튜브 30건까지, 나머지는 200건까지 등록됩니다.<br>
                        4. 차감포인트 : 자동 IAM 1회 사용시 <?= number_format($point_ai) ?> 포인트가 차감됩니다.<br>
                        <br>
                    </p>
                </div>

            </div>
            <div class="modal-footer" style="padding:0px;display:flex">
                <button type="button" class="btn btn-default btn-left" style="width:50%;border-radius:0px;padding:10px 0px;" data-dismiss="modal">취소하기</button>
                <button type="button" class="btn btn-active btn-right" style="width:50%;border-radius:0px;margin-left:0px;padding:10px 0px;" id="cont_modal_btn_ok" onclick="settlement('make')">자동 IAM 만들기</button>
            </div>
        </div>
    </div>
</div>
<!--포인트 충전 팝업창 -->
<div id="point_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" role="document" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal">
                </button>
            </div>
            <div class="modal-title">
                <label>포인트 충전</label>
            </div>
            <div class="modal-header" style="padding:0px">
                <div class="container" style="margin-top:30px;">
                    <p style="margin:0px 10px;font-size:15px;font-weight:700;padding:10px 0px;text-align:center;border:1px solid #dddddd">캐시포인트 : 현금결제로 포인트 충전합니다.<br>씨드포인트 : 캐시포인트를 씨드로 전환하여 <br>AI자동기능 등을 이용할 수 있습니다.</p>
                </div>
                <div class="container" style="margin-top:10px;">
                    <p style="margin:0px 10px;font-size:15px;font-weight:700;">포인트충전</p>
                </div>
                <div class="container" style="margin-top: 5px;">
                    <div style="margin-top:10px;display: flex;justify-content: space-around;font-size:18px;font-weight:700;">
                        <? if ($is_pay_version) { ?>
                            <button class="people_iam settlement_btn" style="padding:20px 0px" onclick="point_chung()">포인트로<br>충전하기</button>
                        <? } ?>
                        <p style="padding:20px 0px" id="point_show_share">보유포인트<br><a style="color:red;text-decoration-line: none;"><?= number_format($Gn_point); ?>P</a></p>
                        <p class="people_iam settlement_btn sharepoint" data="webpage" style="padding:20px 0px;text-align:center; cursor:pointer;">포인트<br>쉐어하기</p>
                    </div>
                    <div style="margin:30px 0px;display: flex;justify-content: space-around;">
                        <button type="button" class="btn btn-default btn-submit" style="border-radius: 20px;width:40%;font-size:14px;font-weight:700;" onclick="location.href='mypage_payment.php'">포인트내역보기</button>
                    </div>
                </div>
                <div class="container" style="margin: 20px 0px;">
                    <p style="font-size:15px;border: 1px solid #dddddd;margin:0px 10px;padding:0px 10px">
                        <br>
                        [확인하세요]<br>
                        1. 방법확인 : AI로 자동 IAM 만들기 영상으로 방법을 확인하세요.<br>
                        2. 검색확인 : 네이버에서 인물, 지도, 쇼핑, 블로그, 유튜브 등에서 검색어를 입력하고 웹페이지 콘텐츠정보와 콘텐츠수를 확인하세요.<br>
                        3. 수집건수 : 한 개의 웹주소당 유튜브 30건까지, 나머지는 200건까지 등록됩니다.<br>
                        4. 차감포인트 : 자동 IAM 1회 사용시 <?= number_format($point_ai) ?> 포인트가 차감됩니다.<br>
                        <br>
                    </p>
                </div>

            </div>
            <div class="modal-footer" style="padding:0px;display:flex">
                <button type="button" class="btn btn-default btn-left" style="width:100%;border-radius:0px;padding:10px 0px;" data-dismiss="modal">취소하기</button>
            </div>
        </div>
    </div>
</div>
<div id="auto_settlement_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-y:auto">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>오토데이트 포인트 이용 안내</label>
            </div>
            <div class="modal-header">
                <div class="container" style="margin-top: 20px;">
                    <div class="container" style="margin-top: 20px;">
                        <p style="font-size:16px;color:black;">
                            콘텐츠 오토데이트 기능을 사용하게 되면 선택한 IAM카드에서 발생하는 트래픽 비용으로 매월 1100원의 포인트가 차감됩니다.<br>
                            포인트가 부족할 경우 오토데이트가 중지됩니다. 미리 포인트를 충분히 충전해두시기 바랍니다.
                        </p>
                    </div>
                    <div class="container" style="margin-top: 20px;">
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td class="iam_table" style="width: 40%;">충전포인트</td>
                                    <td class="iam_table"><input type="number" name="auto_point" id="auto_point" placeholder="충전할 포인트를 입력하세요." value="0"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="container" style="margin-top: 20px;text-align:center;column-count: 2;">
                        <div>
                            <input type="radio" name="pay_type" id="card_type_auto" style="vertical-align:middle;">
                            <label for="card_type_auto" value="card" style="font-size: 17px;">카드결제</label>
                        </div>
                        <div>
                            <input type="radio" name="pay_type" id="bank_type_auto" style="vertical-align:middle;">
                            <label for="bank_type_auto" value="bank" style="font-size: 17px;">무통장결제</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button type="button" class="btn btn-default btn-submit btn-left" style="border-radius: 5px;width:35%;font-size:15px;" onclick="goback('settlment_auto')">뒤로가기</button>
                <button type="button" class="btn btn-default btn-submit btn-right" style="border-radius: 5px;width:35%;font-size:15px;" onclick="settlement('finish', document.pay_form, 'auto')">결제하기</button>
            </div>
        </div>
    </div>
</div>
<div id="settlement_finish_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-y:auto">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>결제완료</label>
            </div>
            <div class="modal-header">
                <div class="container" style="margin-top: 20px;">
                    <p style="font-size:20px;text-align:center;"><?= $_SESSION['iam_member_id']; ?>님은 <span id="finish_name"></span> 상품을 구매하여 해당 포인트가 충전되었으므로 아래에서 IAM자동만들기 버튼을 클릭하시면 됩니다. 만들기 전에 먼저 영상을 꼭 보시기 바랍니다.</p>
                </div>
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <a href="#" style="font-size:22px;">인물검색 IAM 만들기 영상(1분)</a>
                </div>
                <div class="container" style="margin-top: 20px;">
                    <div style="margin-left:14px;margin-bottom:20px;">
                        <button class="people_iam" href="#" style="width:40%;" onclick="settlement('set')">결제하기</button>
                        <input type="text" disabled value="0P" id="finish_point" style="background-color:#aaaaaa;width:57%;text-align:center;font-size: 22px;">
                    </div>
                    <button class="people_iam" href="#" style="margin-left:15px;width:95.3%;" onclick="settlement('make')">자동 IAM만들기</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-submit" data-dismiss="modal" style="border-radius: 5px;width:42%;font-size:15px;" onclick="show_contents()">결제/이용내역보기</button>
            </div>
        </div>
    </div>
</div>
<div id="mutong_settle" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>무통장 결제 안내</label>
            </div>
            <div class="modal-header">
                <div class="container" style="margin-top: 20px;box-shadow: none;">
                    <p style="font-size:16px;color:#6e6c6c">
                        [입금 계좌 안내]<br>
                        스텐다드차타드은행 617-20-109431<br>
                        온리원연구소(구,SC제일은행)
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center;">
                <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:100%;font-size:15px;background-color: #ff3b30;color:white;" onclick="window.open('https://pf.kakao.com/_jVafC/chat');">입금 후 카톡창에 남기기</button>
            </div>
        </div>
    </div>
</div>
<div id="auto_making_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>AI로 자동 아이엠 카드 만들기</label>
            </div>
            <div class="modal-header">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <div style="margin-top:10px;column-count: 3;text-align:center;">
                        <div onclick="newaccount()" style="width:150px;">
                            <input type="radio" name="make" id="new" style="vertical-align: middle;" checked>
                            <label for="new" value="newaccount" style="font-size:17px;">계정생성</label>
                        </div>
                        <div onclick="myIDmake()" style="width: 150px;">
                            <input type="radio" name="make" id="myid" style="vertical-align: middle;">
                            <label for="myid" value="myidmake" style="font-size:17px;">카드추가</label>
                        </div>
                        <div id="my_id_select" onclick="selectcard()" style="width: 140px;">
                            <input type="radio" name="make" id="selcard" style="vertical-align: middle;">
                            <label for="selcard" value="cardadd" style="font-size:17px;">카드선택</label>
                        </div>
                    </div>
                    <div id="newmake" style="margin-top:15px;">
                        <input type="text" name="ID" id="newID" placeholder="ID 입력" style="border: 1px solid black;">
                        <input type="button" id="checkdup" value="중복확인" style="border: 1px solid black;" onclick="id_check1()">
                        <input type="text" name="pwd" id="pwd" placeholder="비번 입력" style="border: 1px solid black;">
                        <div id="id_html" style="font-weight:normal; font-size:13px;margin-top: 12px;" hidden><img src="/images/check.gif"> 사용 가능하신 아이디입니다.</div>
                    </div>
                    <div id="mineid" style="margin-top:15px; display:none;">
                        <!-- <input type="text" name="ID" id="inputID" placeholder="ID 입력" style="border: 1px solid black;width:20%;"> -->
                        <!-- <input type="text" name="ID" id="inputselfID" placeholder="자신의 아이디를 입력하세요." style="border: 1px solid black;width:57%;"> -->
                    </div>
                    <div id="cardsel" style="margin-top:15px; display:none;">
                        <?
                        $sql5 = "select card_short_url,phone_display, card_title from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' order by req_data asc";
                        $result5 = mysql_query($sql5);
                        $i = 0;
                        while ($row5 = mysql_fetch_array($result5)) {
                        ?>
                            <input type="radio" id="multi_westory_card_url_<?= $row5['card_short_url'] ?>" name="multi_westory_card_url" class="we_story_radio we_story_<?= $row5['card_short_url'] ?>" value="<?= $row5['card_short_url'] ?>" <? if (
                                                                                                                                                                                                                                                    $row5['phone_display'] == "N"
                                                                                                                                                                                                                                                ) {
                                                                                                                                                                                                                                                    echo "onclick='locked_card_click();'";
                                                                                                                                                                                                                                                } ?>>
                            <span <? if ($row5['phone_display'] == "N") {
                                        echo "class='locked' title='비공개카드'";
                                    } ?>>
                                <?= $i + 1 ?>번(<?= $row5['card_title'] ?>)
                            </span>
                        <?
                            $i++;
                        }
                        ?>
                    </div>
                    <div style="margin-top:43px;text-align:right;position:relative;">
                        <div onclick="set_auto_update()" style="position:absolute;top: -30px;right: 40px;">
                            <input type="checkbox" name="autoupdate" id="updat">
                            <label for="updat" style="font-size:17px;margin-top:-6px;">콘텐츠 오토데이트 사용</label>
                        </div>
                        <img src="/iam/img/autoupdate-detail.png" style="width:25px;position:absolute;top: -33px;right: 5px;" onclick="show_update_popup()">
                    </div>
                    <div id="auto_update_contents" class="contents_auto" style="display:none;">
                        <h3 style="text-align: center;">콘텐츠 오토데이트</h3>
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td class="iam_table" style="width: 22.8%;border-bottom-color: white;"><label>이용가능한 포인트</label></td>
                                    <td class="iam_table" style="border-bottom-color: white;"><input type="text" value="<?= $Gn_point; ?>" disabled id="usable_point" style="width: 100%;"></td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td class="iam_table" style="width: 22.8%;">
                                        <div onclick="show_hour()">
                                            <input type="checkbox" name="auto_upload_time" id="contents_auto_upload_time">
                                            <label for="contents_auto_upload_time" style="margin-top: -7px;">업로드 시간 선택</label>
                                        </div>
                                    </td>
                                    <td class="iam_table"><input type="text" placeholder="업로드할 시간을 1일 세번까지 선택합니다." id="upload_time" disabled style="width: 100%;"></td>
                                </tr>
                            </tbody>
                        </table>
                        <div id="24_hours" style="display:none" onclick="limit_sel_hour()">
                            <?
                            for ($i = 1; $i < 25; $i++) {
                            ?>
                                <input type="checkbox" name="select_hour" id="<?= $i ?>hour">
                                <label for="<?= $i ?>hour" value="<?= $i ?>" style="margin-top: -7px;"><?= $i ?></label>
                            <? } ?>
                        </div>
                        <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:20%;font-size:15px;margin-top:3px;" onclick="set_auto_update('hide')">취소</button>
                        <button type="button" class="btn btn-default btn-submit start" style="border-radius: 5px;width:20%;font-size:15px;margin-top:3px;" onclick="start_auto_update()">확인</button>
                        <button type="button" class="btn btn-default btn-submit edit" style="border-radius: 5px;width:20%;font-size:15px;margin-top:3px;display:none;" onclick="edit_auto_update()">확인</button>
                    </div>
                    <table style="width:100%; margin-top:10px;">
                        <tbody>
                            <tr>
                                <td class="iam_table" style="width: 22.8%;border-bottom-color: white;">수집분야설정</td>
                                <td class="iam_table" style="border-bottom-color: white;">
                                    <div class="get_type_name">
                                        <div style="width:auto;display:inline-grid;justify-items: center;" onclick="show_keyword('news')">
                                            <input type="radio" name="web_type" id="newsid" style="vertical-align: middle;" checked>
                                            <label for="newsid" value="news" style="font-size:17px;">뉴스</label>
                                        </div>
                                        <div style="width: auto;display:inline-grid;justify-items: center;" onclick="show_keyword('map')">
                                            <input type="radio" name="web_type" id="mapid" style="vertical-align: middle;">
                                            <label for="mapid" value="map" style="font-size:17px;">지도</label>
                                        </div>
                                        <div style="width: auto;display:inline-grid;justify-items: center;" onclick="show_keyword('navershop')">
                                            <input type="radio" name="web_type" id="navershop" style="vertical-align: middle;">
                                            <label for="navershop" value="navers" style="font-size:17px;">N쇼핑</label>
                                        </div>
                                        <div style="width: auto;display:inline-grid;justify-items: center;" onclick="show_keyword('gmarket')">
                                            <input type="radio" name="web_type" id="gmarketid" style="vertical-align: middle;">
                                            <label for="gmarketid" value="gmarket" style="font-size:17px;">G쇼핑</label>
                                        </div>
                                        <div style="width: auto;display:inline-grid;justify-items: center;" onclick="show_keyword('blog')">
                                            <input type="radio" name="web_type" id="blogid" style="vertical-align: middle;">
                                            <label for="blogid" value="blog" style="font-size:17px;">블로그</label>
                                        </div>
                                        <div style="width: auto;display:inline-grid;justify-items: center;" onclick="show_keyword('youtube')">
                                            <input type="radio" name="web_type" id="youtubeid" style="vertical-align: middle;">
                                            <label for="youtubeid" value="youtube" style="font-size:17px;">유튜브</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table id="web_address" style="width:100%;display:none;">
                        <tbody>
                            <tr>
                                <td class="iam_table" style="width: 22.8%;border-bottom-color: white;">웹주소입력</td>
                                <td class="iam_table" style="border-bottom-color: white;"><input type="text" placeholder="생성하고 싶은 페이지 주소를 넣으세요" id="people_web_address" style="width: 100%;"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table id="contents_key" style="width:100%;">
                        <tbody>
                            <tr>
                                <td class="iam_table" style="width: 22.8%;border-bottom-color: white;">콘텐츠 키워드</td>
                                <td class="iam_table" style="border-bottom-color: white;"><input type="text" placeholder="콘텐츠 검색시 필요한 키워드를 입력하세요" id="people_contents_key" style="width: 100%;"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table id="contents_time" style="width:100%;">
                        <tbody>
                            <tr>
                                <td class="iam_table" style="width: 22.8%;border-bottom-color: white;">콘텐츠 수집기간</td>
                                <td class="iam_table" style="border-bottom-color: white;"><input type="date" id="people_contents_start_date" style="width: 40%;border:1px solid;" value="<?= date('2010-01-01') ?>">&nbsp;&nbsp;~&nbsp;&nbsp;<input type="date" id="people_contents_end_date" style="width: 40%;border:1px solid;" value="<?= date('Y-m-d') ?>"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td class="iam_table" style="width: 22.8%;">콘텐츠수 입력</td>
                                <td class="iam_table"><input type="number" placeholder="카드에 넣고 싶은 콘텐츠갯수 지정(최대 <?= $Gn_contents_limit; ?>개/유튜브 30개)" id="people_contents_cnt" min="1" max="<?= $Gn_contents_limit; ?>" style="width: 100%;"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div id="info_profile">
                        <table id="phonenum" style="width:100%;">
                            <tbody>
                                <tr>
                                    <td class="iam_table" style="width: 22.8%;border-top-color: white;">폰번호 입력</td>
                                    <td class="iam_table" style="border-top-color: white;"><input type="text" placeholder="휴대폰, 일반전화 중에 선택 입력" id="phone_num" style="width: 100%;"></td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="mem_name" style="width:100%;">
                            <tbody>
                                <tr>
                                    <td class="iam_table" style="width: 22.8%;border-bottom-color: white;border-top-color: white;">카드 이름</td>
                                    <td class="iam_table" style="border-bottom-color: white;border-top-color: white;"><input type="text" placeholder="카드 이름을 넣으세요" id="blog_mem_name" style="width: 100%;"></td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="mem_company" style="width:100%;">
                            <tbody>
                                <tr>
                                    <td class="iam_table" style="width: 22.8%;border-bottom-color: white;">프로필 소속</td>
                                    <td class="iam_table" style="border-bottom-color: white;"><input type="text" placeholder="프로필 소속을 넣으세요" id="blog_mem_zy" style="width: 100%;"></td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="mem_address" style="width:100%;">
                            <tbody>
                                <tr>
                                    <td class="iam_table" style="width: 22.8%;">프로필 주소</td>
                                    <td class="iam_table"><input type="text" placeholder="프로필 주소를 넣으세요" id="blog_mem_address" style="width: 100%;"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;border: 1px solid black;">
                    <p style="font-size:16px;color:#6e6c6c">
                        [입력지침]<br>
                        1. 인물, 지도, 지마켓, 유튜브의 웹주소를 복사하여 붙여넣으세요.<br>
                        2. 콘텐츠수는 웹에 있는 콘텐츠 정보를 확인한 후 입력하세요.<br>
                        3. 새계정 만들기에서는 꼭 휴대폰을 입력해야 모든 기능이 사용가능해요.<br>
                        4. 내 ID로 만들기는 현재 아이디에 새로운 카드로 만들어집니다.<br>
                        5. 카드선택하기는 이미 만들어진 카드에 콘텐츠를 추가합니다.
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="justify-content: space-around;">
                <button type="button" class="btn btn-default btn-submit btn-left" style="border-radius: 5px;font-size:15px;" onclick="goback('making')">뒤로가기</button>
                <button type="button" class="btn btn-active btn-submit btn-right" style="border-radius: 5px;font-size:15px;" onclick="start_making()" id="startmaking">자동 카드 만들기 시작</button>
            </div>
        </div>
    </div>
</div>
<div id="settlement_contents_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>IAM 만들기 결제/이용내역</label>
            </div>
            <div class="modal-header">
                <div class="container" style="display:inline-block;">
                    <input type="date" placeholder="시작일" id="search_start_date" value="<?= $_REQUEST['search_start_date'] ?>" style="border: 1px solid black;width:130px;"><span style="margin-left: 3px;">~</span>
                    <input type="date" placeholder="종료일" id="search_end_date" value="<?= $_REQUEST['search_end_date'] ?>" style="border: 1px solid black;width:130px;">
                    <button onclick="search_people('search')"><i class="fa fa-search"></i></button>
                    <button type="button" class="btn btn-primary" id="btn_del_ai" onclick="del_ai_list('all')" style="float:right;">전체삭제</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <table style="width:100%">
                        <thead>
                            <th class="iam_table" style="width:20%;">일시</th>
                            <th class="iam_table" style="width:9%;">활동</th>
                            <th class="iam_table" style="width:16%;">분야</th>
                            <th class="iam_table" style="width:12%;">해당 아이디</th>
                            <th class="iam_table" style="width:25%;">링크주소</th>
                            <th class="iam_table" style="width:10%;">잔여포인트</th>
                            <th class="iam_table" style="width:10%;">삭제</th>
                        </thead>
                        <tbody id="contents_side">

                        </tbody>
                    </table>
                </div>
                <div class="container" style="text-align:center;">
                    <button style="border-radius: 5px;width:20%;font-size:15px;margin-top: 10px;" onclick="goback('more')">뒤로가기</button>
                    <button style="border-radius: 5px;width:20%;font-size:15px;margin-top: 10px;" onclick="search_people('more')">더보기</button>
                </div>
                <div class="container" style="margin-top: 20px;border: 1px solid black;">
                    <p style="font-size:16px;color:#6e6c6c">
                        [주의사항]<br>
                        1. 잔여액을 출금요청시 구매할때 할인 받은 금액을 이용건수에서 차감하고 지불합니다.<br>
                        2. 아이엠을 자동으로 생성한 이후에는 해당 생성분에 대해서는 환불이 되지 않으므로 생성전에 웹정보를 상세히 확인하기바랍니다.<br>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="finish_login" class="modal fade" tabindex="-1" role="dialog" style="overflow-y:auto">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>IAM 생성완료</label>
            </div>
            <div class="modal-header">
                <div class="container" style="margin-top: 20px;">
                    <p style="font-size:20px;text-align:center;"><span id="people_mem_id" style="vertical-align: top;font-weight: 900;"></span>님의 자동아이엠이 생성되었습니다.<br>아이엠을 편집, 이용하려면 로그인을 클릭하고 앞에서 입력한 아이디와 비번을 입력 하세요.<br>감사합니다.</p>
                </div>
            </div>
            <div class="modal-footer" style="display:flex;text-align:center;">
                <button class="people_iam btn-left" style="margin-left:70px;width:30.3%;" onclick="location='/index.php'">취소</button>
                <button class="people_iam btn-right" onclick="location='/iam/login.php'" style="margin-left:70px;width:30.3%;">로그인</button>
            </div>
        </div>
    </div>
</div>
<div id="intro_auto_update" class="modal fade" tabindex="-1" role="dialog" style="overflow-y:auto">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>콘텐츠 오토데이트 기능 소개</label>
            </div>
            <div class="modal-header">
                <div class="container" style="margin-top: 20px;">
                    <p style="font-size:20px;text-align:center;">이 기능은 위 페이지에 새로운 콘텐츠가 업로드되면 매일 지정한 시간에 IAM 카드에 자동으로 업데이트 되는 기능입니다. 이 기능은 매일 업데이트되는 콘텐츠로 인해 트래픽 비용이 발생하기때문에 이용자의 포인트에서 유료결제로 운영됩니다.</p>
                </div>
            </div>
            <div class="modal-footer" style="display:flex;text-align:center;">
                <button class="people_iam" style="margin-left:70px;width:30.3%;" data-dismiss="modal" onclick="show_making()">확인</button>
                <? if ($is_pay_version) { ?>
                    <button class="people_iam btn-right" onclick="point_chung1()" style="margin-left:70px;width:30.3%;">충전하기</button>
                <? } ?>
            </div>
        </div>
    </div>
</div>
<div id="finish_login_own" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="margin: 100px auto;">

        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>IAM 생성완료</label>
            </div>
            <div class="modal-header">
                <div class="container" style="margin-top: 20px;">
                    <p style="font-size:20px;text-align:center;">본인의 아이디로 자동아이엠이 생성되었습니다.<br>현재 로그인 상태이므로 생성된 아이엠을 수정하거나 이용해보세요.</p>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center;">
                <button class="people_iam" onclick="go_iamlink()" style="width:41%;background-color: azure;">생성된 아이엠으로 가기</button>
            </div>
        </div>
    </div>
</div>
<div id="finish_login_cardsel" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>IAM 생성완료</label>
            </div>
            <div class="modal-header">
                <div class="container" style="margin-top: 20px;">
                    <p style="font-size:20px;text-align:center;">카드에 콘텐츠가 추가되었습니다.<br>현재 로그인 상태이므로 생성된 콘텐츠를 수정하거나 이용해보세요.</p>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center;">
                <button class="btn btn-active" onclick="go_contentslink()" style="width:100%;border-radius: 0px 0px 12px 12px">확인</button>
            </div>
        </div>
    </div>
</div>
<div id="sharepoint_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>포인트 쉐어하기</label>
            </div>
            <div class="modal-header">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <table style="width:100%;">
                        <tbody>
                            <tr>
                                <td class="iam_table" style="width: 22.8%;border-bottom-color: white;">쉐어ID</td>
                                <td class="iam_table" style="border-bottom-color: white;"><input type="text" id="share_id" placeholder="아이디를 입력하세요." style="width: 100%;"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td class="iam_table" style="width: 22.8%;border-bottom-color: white;">쉐어 캐시포인트</td>
                                <td class="iam_table" style="border-bottom-color: white;"><input type="number" id="share_cash" style="width: 100%;"></td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:100%">
                        <tbody>
                            <tr>
                                <td class="iam_table" style="width: 22.8%;">쉐어 씨드포인트</td>
                                <td class="iam_table"><input type="number" id="share_point" style="width: 100%;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="container" style="margin-top: 20px;border: 1px solid black;">
                    <p style="font-size:16px;color:#6e6c6c">
                        1. 자신의 계정에 있는 포인트를 다른 ID로 쉐어하는 기능입니다.<br>
                        2. 쉐어하려는 ID와 포인트를 입력하세요.<br>
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="display: flex;justify-content: space-around;">
                <input type="hidden" id="place_before">
                <button type="button" class="btn btn-default btn-left" style="border-radius: 5px;width:50%;font-size:15px;" onclick="goback('share')">취소</button>
                <button type="button" class="btn btn-active btn-right" style="border-radius: 5px;width:50%;font-size:15px;" onclick="start_sharing()" id="start_share">쉐어하기</button>
            </div>
        </div>
    </div>
</div>
<div id="mutong_settlement" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>캐시포인트 충전</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;box-shadow: none;">
                    <p style="font-size:16px;color:#6e6c6c">
                        회원님의 보유포인트 안내<br>
                        캐시포인트: <?= number_format($Gn_cash_point) ?> P<br>
                        씨드포인트: <?= number_format($Gn_point) ?> P<br>
                        캐시P는 현금과 같아서 모든 결제에 이용가능합니다.<br>
                        씨드P는 IAM의 내부 기능이용에만 사용합니다.
                    </p>
                </div>
                <div class="container" style="margin-top: 20px;box-shadow: none;">
                    <p style="font-size:16px;color:#6e6c6c">
                        결제금액:<input type="number" id="money_point" style="border: 1px solid;">
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="display: flex;justify-content: space-around;">
                <button type="button" class="btn btn-default btn-submit btn-left" onclick="card_settle('<?= $_GET['cur_win'] ?>')" target="_blank">카드 결제</a>
                    <button type="button" class="btn btn-active btn-submit btn-right" onclick="bank_settle()" target="_blank">무통장 결제</a>
            </div>
        </div>
    </div>
</div>
<div id="cashtoseed_settlement" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>씨드포인트 충전</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;box-shadow: none;">
                    <p style="font-size:16px;color:#6e6c6c">
                        회원님의 보유 캐시포인트는 <?= number_format($Gn_cash_point) ?> P 입니다.<br>
                        회원님의 보유 씨드포인트는 <?= number_format($Gn_point) ?> P 입니다.<br>
                        캐시포인트 부족시 충전하시기 바랍니다.
                    </p>
                </div>
                <div class="container" style="margin-top: 20px;box-shadow: none;">
                    <p style="font-size:16px;color:#6e6c6c">
                        결제금액:<input type="number" id="money_point_cashtoseed">
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="display: flex;justify-content: space-around;">
                <button type="button" class="btn btn-default btn-submit btn-left" onclick="cashtoseed_chung('<?= $_GET['cur_win'] ?>')">씨드포인트 충전</button>
                <button type="button" class="btn btn-active btn-submit btn-right" data-toggle="modal" data-target="#mutong_settlement" data-dismiss="modal">캐시포인트 충전</button>
            </div>
        </div>
    </div>
</div>
<div id="point_settlement" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>포인트 충전/결제</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;box-shadow: none;">
                    <p style="font-size:16px;color:#6e6c6c">
                        회원님의 보유 씨드포인트는 <?= number_format($Gn_point) ?> P 입니다.<br>
                        포인트 부족시 충전하시기 바랍니다.
                    </p>
                </div>
                <div class="container" style="margin-top: 20px;box-shadow: none;">
                    <p style="font-size:16px;color:#6e6c6c">
                        포인트결제 <input type="number" id="settle_point" value="<?= $price_service ?>" readonly>P
                        <input type="hidden" id="point_pay_type" value=""><!--0,4:서비스구매,1:아이엠몰 구매,2:카드몰구매,3:콘텐츠몰구매,5:카드전송,6:콘텐츠전송,7:메시지세트구매,8:그룹콘전송,9:공지전송-->
                        <input type="hidden" id="point_pay_data" value=""><!--구매할 상품 정보 array str-->
                        <input type="hidden" id="sell_con_title" value="<?= $name_service ?>">
                        <input type="hidden" id="sell_con_url" value="<?= $contents_url ?>">
                        <input type="hidden" id="sell_con_id" value="<?= $sellerid_service ?>">
                        <input type="hidden" id="contents_send_type" value="<?= $sellerid_service ?>">
                        <input type="hidden" id="card_send_mode" value="">
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="display: flex;justify-content: space-around;">
                <button type="button" class="btn btn-default btn-submit btn-left" data-toggle="modal" data-target="#cashtoseed_settlement" data-dismiss="modal">충전하기</button>
                <button type="button" class="btn btn-active btn-submit btn-right" onclick="point_settle()">지금결제하기</button>
            </div>
        </div>
    </div>
</div>
<div id="point_settlement_cash" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>포인트 충전/결제</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;box-shadow: none;">
                    <p style="font-size:16px;color:#6e6c6c">
                        회원님의 보유 캐시포인트는 <?= number_format($Gn_cash_point) ?> P 입니다.<br>
                        포인트 부족시 충전하시기 바랍니다.
                    </p>
                    <input type="hidden" id="point_pay_type_cash" value=""><!--0:서비스구매 1:아이엠몰 구매 2:카드몰구매3:콘텐츠몰구매-->
                    <input type="hidden" id="point_pay_data_cash" value=""><!--구매할 상품 정보 array str-->
                </div>
                <div class="container" style="margin-top: 20px;box-shadow: none;">
                    <p style="font-size:16px;color:#6e6c6c">
                        포인트결제 <input type="number" id="settle_point_cash" style="border: 1px solid;" value="" readonly>P
                        <input type="hidden" id="settle_point_cash1" value="">
                        <input type="hidden" id="sale_cnt" value="">
                        <input type="hidden" id="send_salary_price1" value="">
                        <input type="hidden" id="sell_con_title_cash" value="<?= $name_service ?>">
                        <input type="hidden" id="sell_con_url_cash" value="<?= $contents_url ?>">
                        <input type="hidden" id="sell_con_id_cash" value="<?= $sellerid_service ?>">
                        <input type="hidden" id="contents_send_type_cash" value="<?= $sellerid_service ?>">
                        <input type="hidden" id="settle_buyer" value="<?= $_SESSION['iam_member_id'] ?>">
                        <input type="number" id="item_count" name="item_count" value="1" min="0" style="width:40px;margin-left:10px;">
                    </p>
                </div>
                <div class="container" id="salary_price_show" style="margin-top: 20px;" hidden>
                    <p style="font-size:16px;color:#6e6c6c">
                        배송료 : <span id="send_salary_price_show"></span>
                    </p>
                </div>
                <div class="container" id="state_sale_cnt" style="margin-top: 20px;border: 1px solid black;" hidden>
                    <p style="font-size:16px;color:#6e6c6c">
                        오늘 이벤트 적립 가능한 건수는 <span id="con_sale_cnt"></span>개입니다. 적립가능한 <span id="con_sale_cnt1"></span>개를 먼저 구매하고 나머지는 추가 구매해주세요. 감사합니다.
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="display: flex;justify-content: space-around;">
                <button type="button" class="btn btn-default btn-submit btn-left" data-toggle="modal" data-target="#mutong_settlement" data-dismiss="modal" target="_blank">충전하기</button>
                <button type="button" class="btn btn-active btn-submit btn-right" onclick="point_settle_cash();" target="_blank">지금결제하기</button>
            </div>
        </div>
    </div>
</div>
<!--아이엠 몰 팝업-->
<div id="iam_mall_popup" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y:auto">
    <div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:768px;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label id="iam_mall_text">아이엠 몰 등록하기</label>
            </div>
            <div class="modal-body">
                <input type="hidden" id="iam_mall_type" value="">
                <input type="hidden" id="iam_mall_link" value="">
                <input type="hidden" id="iam_mall_method" value="creat">
                <input type="hidden" id="iam_mall_idx" value="">
                <table class='table table-bordered'>
                    <colgroup>
                        <col width="20%">
                        <col width="80%">
                    </colgroup>
                    <tr class="bold">
                        <td>상품제목</td>
                        <td>
                            <input type="text" id="iam_mall_title" style="width: 100%;">
                        </td>
                    </tr>
                    <tr class="bold">
                        <td>상품부제목</td>
                        <td>
                            <input type="text" id="iam_mall_sub_title" style="width: 100%;">
                        </td>
                    </tr>
                    <tr class="bold">
                        <td>상품썸네일</td>
                        <td>
                            <div class="input-wrap">
                                <input type="radio" name="iam_mall_img_type" value="f" checked>파일가져오기
                                <input type="radio" name="iam_mall_img_type" value="u" id="main_type1">이미지주소
                            </div>
                            <div class="input-wrap" style="margin-top:10px">
                                <input type="file" id="iam_mall_img" style="height: 24px;width: 100%;" accept=".jpg,.jpeg,.png,.gif">
                                <input type="text" id="iam_mall_img_link" style="height: 24px;width:100%;display:none" placeholder="예시 https://www.abcdef.jpg (png/gif)">
                            </div>
                            <img id="iam_mall_img_preview" style="width:80%">
                        </td>
                    </tr>
                    <tr class="bold">
                        <td>상세설명</td>
                        <td>
                            <textarea name="iam_mall_desc" placeholder="<?= $MENU['IAM_CONTENTS']['CONTS14']; ?>" id="iam_mall_desc" style="border:none;min-height:190px;overflow:auto;width:100%;resize: vertical;"></textarea>
                        </td>
                    </tr>
                    <tr class="bold">
                        <td>검색키워드</td>
                        <td>
                            <input type="text" id="iam_mall_keyword" style="height: 24px;width:100%;">
                        </td>
                    </tr>
                    <tr class="bold price">
                        <td>상품정가</td>
                        <td>
                            <input type="text" id="iam_mall_price" style="width: 100%;height: 24px;" placeholder="<?= $MENU['IAM_CONTENTS']['CONTS12']; ?>">
                        </td>
                    </tr>
                    <tr class="bold price">
                        <td>판매가격</td>
                        <td>
                            <input type="text" id="iam_mall_sell_price" style="width: 100%;height: 24px;" placeholder="<?= $MENU['IAM_CONTENTS']['CONTS13']; ?>">
                        </td>
                    </tr>
                    <tr class="bold">
                        <td>노출상태</td>
                        <td>
                            <input type="checkbox" id="iam_mall_display" checked>&nbsp;&nbsp;&nbsp;노출</input>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer" style="display: flex;justify-content: space-around;">
                <button type="button" class="btn btn-default btn-submit btn-left" onclick="close_iam_mall_popup()">취소</button>
                <button type="button" class="btn btn-active btn-submit btn-right" onclick="create_iam_mall(0)">등록</button>
            </div>
        </div>
    </div>
</div><!-- // 팝업 끝 -->
<!-- 감추기 리스트 팝업 -->
<div id="block_list_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="width: 100%;max-width:768px;">

        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="location.reload();">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>감추기 리스트 보기</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <table class="table table-bordered">
                        <colgroup>
                            <col width="20%">
                            <col width="20%">
                            <col width="25%">
                            <col width="20%">
                            <col width="15%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="bold" style="text-align: center;">유형</th>
                                <th class="bold" style="text-align: center;">이름</th>
                                <th class="bold" style="text-align: center;">아이디</th>
                                <th class="bold" style="text-align: center;">프로필</th>
                                <th class="bold" style="background:#337ab7;color:white;text-align: center;" onclick="remove_block_all_item()">전체해제</th>
                            </tr>
                        </thead>
                        <tbody id="block_list_body">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- // 콘텐츠 신고항목 팝업 -->
<div id="contents_report_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:500px;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title" style="color:black;border-bottom: 1px solid #c8c9c8;">
                <label>신고 항목 선택하기</label>
            </div>
            <div class="modal-header" style="text-align:left;">
                <span style="font-size:15px;margin-bottom:15px;">아래 항목을 선택하거나 하단에 직접 입력하기에 신고내용을 입력해주세요.</span>
                <div>
                    <input type="checkbox" name="report_title" value="1" id="sex">
                    <label for="sex">성관련 정보</label>
                </div>
                <div>
                    <input type="checkbox" name="report_title" value="2" id="force">
                    <label for="force">폭력물 정보</label>
                </div>
                <div>
                    <input type="checkbox" name="report_title" value="3" id="worry">
                    <label for="worry">괴롭힘 정보</label>
                </div>
                <div>
                    <input type="checkbox" name="report_title" value="4" id="ownkill">
                    <label for="ownkill">자살 자해 정보</label>
                </div>
                <div>
                    <input type="checkbox" name="report_title" value="3" id="wrong">
                    <label for="wrong">왜곡 거짓 정보</label>
                </div>
                <div>
                    <input type="checkbox" name="report_title" value="3" id="spam">
                    <label for="spam">불법 스팸성 정보</label>
                </div>
                <div>
                    <input type="checkbox" name="report_title" value="3" id="unaccept">
                    <label for="unaccept">무허가 판매 정보</label>
                </div>
                <div>
                    <input type="checkbox" name="report_title" value="3" id="dislike">
                    <label for="dislike">혐오 발언 정보</label>
                </div>
                <input type="hidden" name="content_idx_report" id="content_idx_report">
            </div>
            <div class="modal-body" style="text-align:center;">
                <span style="font-size:15px;">신고내용 설명하기</span>
                <textarea id="report_desc_msg" style="width: 100%;border: 1px solid #b5b5b5;height: 100px;margin-bottom: 14px;" placeholder="신고내용을 입력해주세요. 특정 게시자를 괴롭히는 무작위 신고를 제한하기 위해 연락처를 기재하는 경우 답변을 전송해드립니다."></textarea>
                <textarea id="reporter_phone_num" style="width: 100%;border: 1px solid #b5b5b5;height: 30px;margin-bottom: 14px;" placeholder="연락처(휴대폰)을 입력해주시면 답변 결과를 문자로 보내드립니다."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-active btn-center" style="width: 100%;background: #c8c9c8;color: #6e6a6a;padding: 10px 0px;border-radius:0px 0px 6px 6px" onclick="set_report()">신고내용 전송하기</button>
            </div>
        </div>
    </div>
</div>
<!-- // 카드전송 팝업 -->
<div id="card_send_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>내 아이엠카드 전송하기</label>
            </div>
            <div class="modal-header">
                <div class="container" style="margin-top: 20px;">
                    <p style="font-size:14px;text-align:center;font-weight:bold">내가 만든 아이엠 카드를 지인에게 전송할 수 있습니다. 본 서비스는 유료이므로 결제 후에 이용할 수 있습니다.</p>
                </div>
                <div class="container" style="margin-top: 20px;border: 1px solid black;">
                    <p style="font-size:14px;color:#6e6c6c">
                        [결제 전 확인사항]<br>
                        1. 아이엠 카드 전송 한 건당 <?= $card_send_point ?> 포인트 입니다.<br>
                        2. 전송할 대상을 프렌즈에서 클릭하거나 아이디를 직접 입력해서 카드를 전송 할 수 있습니다.<br>
                        3. 전송 했으나 수신자가 승인을 거부하면 전송이 되지 않고, 포인트 차감도 되지 않습니다.<br>
                        4. 캐시포인트는 모든 결제에 사용할수 있으나 씨드포인트는 내부 기능에서만 사용합니다.
                    </p>
                </div>
                <div class="container" style="margin-top: 20px;">
                    <div style="width:100%;margin-top:60px;display: flex;justify-content: space-around;">
                        <? if ($is_pay_version) { ?>
                            <button class="people_iam settlement_btn" href="#" onclick="point_chung()">포인트<br>충전하기</button>
                        <? } ?>
                        <p id="point_show_share">보유씨드포인트<br><a style="color:red"><?= number_format($Gn_point); ?>P</a></p>
                        <p class="people_iam settlement_btn sharepoint" data="card" style="text-align:center; cursor:pointer;">포인트<br>쉐어하기</p>
                    </div>
                    <button class="btn btn-active" style="margin:30px 0px;width:100%;border-radius:0px" onclick="settlement('card_send')">내 아이엠카드 전송하기</button>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center;">
                <button type="button" class="btn btn-default btn-submit btn-left" style="border-radius: 5px;font-size:15px;" onclick="send_list('card')">전송리스트보기</button>
                <button type="button" class="btn btn-active btn-submit btn-right" style="border-radius: 5px;font-size:15px;" href="mypage_payment.php">포인트내역보기</button>
            </div>
        </div>
    </div>
</div>
<!-- // 콘텐츠전송 팝업 -->
<div id="contents_send_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>내 콘텐츠 전송하기</label>
            </div>
            <div class="modal-header">
                <div class="container" style="margin-top: 20px;">
                    <p style="font-size:14px;text-align:center;font-weight:bold">내가 만든 콘텐츠를 지인에게 전송할 수 있습니다. 본 서비스는 유료이므로 결제 후에 이용할 수 있습니다.</p>
                </div>
                <div class="container" style="margin-top: 20px;border: 1px solid black;">
                    <p style="font-size:14px;color:#6e6c6c">
                        [결제 전 확인사항]<br>
                        1. 콘텐츠 전송 한 건당 <?= $contents_send_point ?> 포인트 입니다.<br>
                        2. 전송할 대상을 프렌즈에서 클릭하거나 아이디를 직접 입력해서 콘텐츠를 전송 할 수 있습니다.<br>
                        3. 알림형 전송과 수신함 전송 두가지 방법이 있습니다.<br>
                        4. 캐시포인트는 모든 결제에 사용할수 있으나 씨드포인트는 내부 기능에서만 사용합니다.
                    </p>
                </div>
                <div class="container" style="margin-top: 20px;">
                    <div style="margin-top:60px;display: flex;justify-content: space-around;">
                        <? if ($is_pay_version) { ?>
                            <button class="people_iam settlement_btn" href="#" onclick="point_chung()">포인트<br>충전하기</button>
                        <? } ?>
                        <p id="point_show_share">보유씨드포인트<br><a style="color:red"><?= number_format($Gn_point); ?>P</a></p>
                        <p class="people_iam settlement_btn sharepoint" data="contents" style="text-align:center; cursor:pointer;">포인트<br>쉐어하기</p>
                    </div>
                    <button class="btn btn-active" style="margin:30px 0px;width:100%;border-radius:0px" style="width:100%;" onclick="settlement('contents_send')">내 콘텐츠 전송하기</button>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center;">
                <button type="button" class="btn btn-default btn-submit btn-left" style="font-size:15px;" onclick="send_list('contentssend')">전송리스트보기</button>
                <button type="button" class="btn btn-active btn-submit btn-right" style="font-size:15px;" href="mypage_payment.php">포인트내역보기</button>
            </div>
        </div>
    </div>
</div>
<!-- // 카드전송 할 친구선택 팝업 -->
<div id="card_send_modal_mem" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>내 아이엠카드 전송하기</label>
            </div>
            <div class="modal-header">
                <div style="display:flex;justify-content: space-between;">
                    <div>
                        <input type="radio" name="card_send_mode" id="duplicate" value="0">
                        <span for="duplicate">복제전송</span>
                        <input type="radio" name="card_send_mode" id="share" value="<?= $cur_card['idx'] ?>">
                        <span for="share">공유전송</span>
                    </div>
                    <div>
                        <input type="radio" name="card_send_type" value="cont" checked>
                        <span for="cont">기존회원</span>
                        <input type="radio" name="card_send_type" value="link">
                        <span for="link">신규회원</span>
                    </div>
                </div>
                <textarea id="alarm_msg" style="width: 100%;border: 1px solid #b5b5b5;height: 100px;margin-bottom: 14px;" placeholder="수신자가 보는 메시지를 입력하세요."></textarea>
            </div>
            <div class="modal-body" id="card_send_body">
                <table class="table table-bordered">
                    <tbody>
                        <tr class="hide_spec">
                            <td class="bold" id="remain_count" data-num="" style="width:70px;">전송하기<br>
                                <textarea name="card_send_id_count" id="card_send_id_count" style="width:100%; height:50px;color: red;font-size: 12px" readonly="" data-num="0" placeholder="0건"></textarea>
                            </td>
                            <td colspan="2">
                                <div style="display:flex">
                                    <textarea name="card_send_id" id="card_send_id" style="border: solid 1px #b5b5b5;width:85%; height:100px;" data-num="0" placeholder="전송할 친구 아이디를 입력하세요.<컴마로 구분>"></textarea>
                                    <button type="button" class="btn btn-info" onclick="show_share_user_list('card_send')" style="font-size:12px; padding:2px 20px;float:right;"> 전송할 친구<br>선택하기 </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer" style="text-align: center;display:flex;padding:0px;">
                <button type="button" class="btn-default btn-left" data-dismiss="modal" onclick="location.reload();">취소하기</button>
                <button type="button" class="btn-active btn-right" onclick="card_send_settle('<?= $card_title ?>')">전송하기</button>
            </div>
        </div>
    </div>
</div>
<!--카드링크전송 팝업-->
<div id="card_send_modal_link" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>링크전송기능 소개</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;">
                    <p style="font-size:14px;color:#6e6c6c">
                        링크전송은 IAM 아이디가 없는 가족, 지인에게 해당 카드를 간편 가입으로 볼수 있도록 합니다.<br>
                        아래 링크복사를 클릭하여 카톡, 문자 등으로 보내시면 됩니다.
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;display:flex;padding:0px;margin-top: 20px;">
                <button type="button" class="btn-default btn-left" data-dismiss="modal" onclick="location.reload();">취소하기</button>
                <button type="button" class="btn-active btn-right" onclick="copy_card_link()">복사하기</button>
            </div>
        </div>
    </div>
</div>
<!--카드링크전송 팝업-->
<div id="card_send_modal_start" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>IAM 카드 전송합니다.</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;">
                    <p style="font-size:14px;color:#6e6c6c">
                        <?= $card_mem_row['mem_name'] ?>님이 IAM카드를 전송합니다.<br>
                        카드를 받으시려면 승인을 클릭하고 회원가입하면 화면에 보여지는 <?= $card_mem_row['mem_name'] ?>님이 보내드린 카드를 공유받아 사용할수 있습니다.
                    </p>
                    <br>
                    <p style="font-size:14px;color:#6e6c6c;font-weight:bold"><?= base64_decode(urldecode($_GET['smsg'])) ?></p>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;display:flex;padding:0px;margin-top: 20px;">
                <button type="button" class="btn-default btn-left" data-dismiss="modal" onclick="$('#card_send_modal_start').modal('hide')">취소하기</button>
                <button type="button" class="btn-active btn-right" onclick="$('#card_send_modal_start').modal('hide');show_login()">승인하기</button>
            </div>
        </div>
    </div>
</div>
<!-- // 콘텐츠전송 할 친구선택 팝업 -->
<div id="contents_send_modal_mem" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>내 콘텐츠 전송하기</label>
            </div>
            <div class="modal-header">
                <div class="container" style="text-align: left;padding:8px;font-size:15px;">
                    <input type="radio" name="con_send_type" id="alarm_con_send" value="1" checked style="vertical-align: top;">알림형전송
                    <input type="radio" name="con_send_type" id="no_alarm_con_send" value="2" style="vertical-align: top;margin-left:5px;">수신함전송
                </div>
                <textarea id="alarm_msg1" style="width: 100%;border: 1px solid #b5b5b5;height: 100px;margin-bottom: 14px;" placeholder="수신자가 보는 메시지를 입력하세요."></textarea>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr class="hide_spec">
                            <td class="bold" id="remain_count" data-num="" style="width:70px;">전송하기<br>
                                <textarea name="contents_send_id_count" id="contents_send_id_count" style="width:100%; height:50px;color: red;font-size: 12px" readonly="" data-num="0" placeholder="0건"></textarea>
                            </td>
                            <td colspan="2">
                                <div style="display:flex">
                                    <textarea name="contents_send_id" id="contents_send_id" style="border: solid 1px #b5b5b5;width:85%; height:100px;" data-num="0" placeholder="전송할 친구 아이디를 입력하세요.<컴마로 구분>"></textarea>
                                    <button type="button" class="btn btn-info" onclick="show_share_user_list('contents_send')" style="font-size:12px; padding:2px 20px;float:right;"> 전송할 친구<br>선택하기 </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-default btn-left" data-dismiss="modal" onclick="location.reload();">취소하기</button>
                <button type="button" class="btn-active btn-right" onclick="contents_send_settle()">전송하기</button>
            </div>
        </div>
    </div>
</div>
<!-- // 공지사항 전송 팝업 -->
<div id="notice_send_modal_mem" class="modal fade" tabindex="-1" role="dialog" aria-hidden="false">
    <div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:768px;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>공지사항 전송하기</label>
            </div>
            <div class="modal-header" style="background:#f5f5f5;border:none">
                <textarea id="notice_title" style="width: 100%;border: 1px solid #b5b5b5;height: 30px;margin-bottom: 15px;" placeholder="공지 제목을 입력하세요."></textarea>
                <textarea id="notice_desc" style="width: 100%;border: 1px solid #b5b5b5;height: 100px;margin-bottom: 15px;" placeholder="공지 내용을 입력하세요."></textarea>
                <textarea id="notice_link" style="width: 100%;border: 1px solid #b5b5b5;height: 30px;margin-bottom: 15px;" placeholder="연결하려는 웹주소를 입력하세요."></textarea>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr class="hide_spec">
                            <td class="bold" id="remain_count" data-num="" style="width:70px;">전송하기<br>
                                <textarea name="notice_send_id_count" id="notice_send_id_count" style="width:100%; height:50px;color: red;font-size: 12px" readonly="" data-num="0" placeholder="0건"></textarea>
                            </td>
                            <td colspan="2">
                                <div style="display:flex">
                                    <textarea name="notice_send_id" id="notice_send_id" style="border: solid 1px #b5b5b5;width:85%; height:100px;" data-num="0" placeholder="전송할 친구 아이디를 입력하세요.<컴마로 구분>"></textarea>
                                    <button type="button" class="btn btn-info" onclick="show_share_user_list('notice_send')" style="font-size:12px; padding:2px 20px;float:right;"> 전송할 친구<br>선택하기 </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-default btn-left" style="padding:10px 0px" data-dismiss="modal" onclick="location.reload();">취소하기</button>
                <button type="button" class="btn-active btn-right" style="padding:10px 0px" onclick="notice_send_settle()">전송하기</button>
            </div>
        </div>
    </div>
</div>
<!-- // 카드전송 전송리스트 팝업 -->
<div id="card_send_list_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>전송 카드 리스트</label>
            </div>
            <div class="modal-header" style="background:#f5f5f5;border:none">
                <div class="container" style="text-align: right;">
                    <button type="button" class="btn btn-primary" id="btn_del_card" onclick="delete_all('card')">전체삭제</button>
                </div>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <table style="width:100%">
                        <thead>
                            <th class="iam_table" style="width:20%;">일시</th>
                            <th class="iam_table" style="width:35%;">링크주소</th>
                            <th class="iam_table" style="width:12%;">아이디/이름</th>
                            <th class="iam_table" style="width:9%;">승인</th>
                            <th class="iam_table" style="width:15%;">잔여포인트</th>
                            <th class="iam_table" style="width:10%;">삭제</th>
                        </thead>
                        <tbody id="card_send_side">

                        </tbody>
                    </table>
                </div>
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <button style="border-radius: 5px;width:20%;font-size:15px;margin-top: 10px;" onclick="search_send_list('card', 'more')">더보기</button>
                </div>
                <div class="container" style="margin-top: 20px;border: 1px solid black;">
                    <p style="font-size:16px;color:#6e6c6c">
                        [주의사항]<br>
                        1. 잔여액을 출금요청시 구매할때 할인 받은 금액을 차감하고 지불합니다.<br>
                        2. 카드를 전송한 이후에는 환불이 되지 않으므로 전송전에 상세히 확인하기바랍니다.<br>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- // 컨텐츠전송 전송리스트 팝업 -->
<div id="contents_send_list_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px 30px;width: auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>전송 콘텐츠 리스트</label>
            </div>
            <div class="modal-header" style="background:#f5f5f5;border:none">
                <div class="container" style="display:inline-block;">
                    <input type="text" placeholder="검색어(아이디, 메시지) 입력" id="search_key_recv_con" value="<?= $_REQUEST['search_key'] ?>" style="padding:5px 20px;border-radius:15px;border: 1px solid #d7d7d7;width:300px;">
                    <button onclick="search_send_list('contentssend','','search')" class="btn-link"><img src="/iam/img/menu/icon_bottom_search.png" style="width:24px;height:24px;"></button>
                    <button type="button" class="btn btn-link" style="float:right;color:rgb(130,199,54)" id="btn_del_send" onclick="delete_all('send')">전체 삭제</button>
                </div>
            </div>
            <div class="modal-body popup-bottom" style="background:#f5f5f5">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <table style="width:100%">
                        <thead>
                            <th class="iam_table" style="width:15%;">일시</th>
                            <th class="iam_table" style="width:10%;">구분</th>
                            <th class="iam_table" style="width:25%;">내용</th>
                            <th class="iam_table" style="width:20%;">대상</th>
                            <th class="iam_table" style="width:15%;">포인트</th>
                            <th class="iam_table" style="width:10%;">삭제</th>
                        </thead>
                        <tbody id="contents_send_side">

                        </tbody>
                    </table>
                </div>
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <button style="border-radius: 5px;width:20%;font-size:15px;margin-top: 10px;font-weight:700;background:#d7d7d7;" onclick="search_send_list('contentssend', 'more')">더보기</button>
                </div>
                <div class="container" style="margin-top: 50px;">
                    <p style="font-size:15px;border: 2px solid #d7d7d7;font-weight:500;padding:20px;">
                        [주의사항]<br>
                        1. 삭제를 하면 전송페이지에서 해당 콘텐츠가 삭제되어 더 이상 볼 수 없습니다.<br>
                        2. 컨텐츠를 전송한 이후에는 환불이 되지 않으므로 전송전에 상세히 확인하기바랍니다.<br>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- // 메시지 상세 보기 팝업 -->
<div id="show_detail_more" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;z-index:2000;">
    <div class="modal-dialog" style="margin: 100px auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label></label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;box-shadow: none;">
                    <p style="font-size:16px;color:#6e6c6c" id="contents_detail">
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- // 수신컨텐츠 리스트 팝업 -->
<div id="contents_receive_list_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px 30px;width: auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>수신 콘텐츠 리스트</label>
            </div>
            <div class="modal-header" style="background:#f5f5f5;border:none">
                <div class="container" style="text-align: right;">
                    <button type="button" class="btn btn-link" style="color:rgb(130,199,54)" onclick="delete_all('recv')">전체 삭제</button>
                </div>
            </div>
            <div class="modal-body popup-bottom" style="background:#f5f5f5">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <table style="width:100%">
                        <thead>
                            <th class="iam_table" style="width:20%;">일시</th>
                            <th class="iam_table" style="width:35%;">링크주소</th>
                            <th class="iam_table" style="width:20%;">전송자</th>
                            <th class="iam_table" style="width:10%;">가리기</th>
                        </thead>
                        <tbody id="contents_recv_side">

                        </tbody>
                    </table>
                </div>
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <button style="border-radius:5px;width:20%;font-size:15px;font-weight:700;background:#d7d7d7;margin-top:10px;" onclick="search_send_list('contentsrecv', 'more')">더보기</button>
                </div>
                <div class="container" style="margin-top: 50px;">
                    <p style="font-size:15px;border: 2px solid #d7d7d7;font-weight:500;padding:20px;">
                        [주의사항]<br>
                        삭제를 하면 수신페이지에서 해당 콘텐츠가 삭제되어 더 이상 볼 수 없습니다.<br>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- // 카드수신 알림 팝업 -->
<div id="card_recv_popup" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">

        <div class="modal-content" style="width: 350px;">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>수신 카드 승인</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;">
                    <p style="font-size:16px;color:#6e6c6c" id="recv_msg">

                    </p>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center">
                <a class="btn btn-secondary" id="btn_cancel" href="<?= $href_url ?>" style="background-color: #dacece;" target="_blank">미리보기</a>
                <a class="btn btn-secondary" id="btn_refuse" href="javascript:refuse(<?= $res_no ?>);" style="background-color: #dacece;">승인거부</a>
                <button type="button" class="btn btn-primary" onclick="receive_card('<?= $res_no ?>')">지금 승인하기</button>
            </div>
        </div>
    </div>
</div>
<!-- // 콘텐츠수신 알림 팝업 -->
<div id="contents_recv_popup" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
        <div class="modal-content" style="width: 350px;">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>수신 콘텐츠 알림</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;">
                    <p style="font-size:16px;color:#6e6c6c" id="recv_con_msg">

                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-left" onclick="receive_contents('<?= $con_res_no ?>', '')">닫기</button>
                <button type="button" class="btn btn-active btn-right" onclick="receive_contents('<?= $con_res_no ?>', '<?= $con_href_url ?>')">확인</button>
            </div>
        </div>
    </div>
</div>
<!-- // 공지사항 알림 팝업 -->
<div id="notice_recv_popup" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
        <div class="modal-content" style="width: 350px;">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>공지사항 알림</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;">
                    <p style="font-size:16px;color:#6e6c6c" id="recv_notice_msg">
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center">
                <button type="button" class="btn btn-active" style="width:100%;border-radius:0px 0px 6px 6px !important" onclick="receive_notice('<?= $notice_res_no ?>', '<?= $notice_href_url ?>')">확인</button>
            </div>
        </div>
    </div>
</div>
<!-- // 수신공지사항 리스트 팝업 -->
<div id="notice_receive_list_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px 30px;width: auto;">

        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>수신 공지 리스트</label>
            </div>
            <div class="modal-header" style="background:#f5f5f5;border:none">
                <div class="container" style="text-align: right;">
                    <button type="button" class="btn btn-link" style="color:rgb(130,199,54)" onclick="notice_delete_all('recv')">전체 삭제</button>
                </div>
            </div>
            <div class="modal-body popup-bottom" style="background:#f5f5f5">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <table style="width:100%">
                        <thead>
                            <th class="iam_table" style="width:20%;">일시</th>
                            <th class="iam_table" style="width:15%;">내용</th>
                            <th class="iam_table" style="width:35%;">링크주소</th>
                            <th class="iam_table" style="width:12%;">전송자</th>
                            <th class="iam_table" style="width:10%;">가리기</th>
                        </thead>
                        <tbody id="notice_recv_side">

                        </tbody>
                    </table>
                </div>
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <button style="border-radius: 5px;width:20%;font-size:15px;margin-top: 10px;font-weight:700;background:#d7d7d7;" onclick="notice_send_recv_list('noticerecv', 'more')">더보기</button>
                </div>
                <div class="container" style="margin-top: 50px;">
                    <p style="font-size:15px;border: 2px solid #d7d7d7;font-weight:500;padding:20px;">
                        [주의사항]<br>
                        삭제를 하면 수신페이지에서 해당 콘텐츠가 삭제되어 더 이상 볼 수 없습니다.<br>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- // 전송공지사항 리스트 팝업 -->
<div id="notice_send_list_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px 30px;width: auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>전송 공지 리스트</label>
            </div>
            <div class="modal-header" style="background:#f5f5f5;border:none">
                <div class="container">
                    <input type="text" placeholder="검색어(아이디, 메시지) 입력" id="search_key_recv_notice" value="<?= $_REQUEST['search_key'] ?>" style="padding:5px 20px;border-radius:15px;border: 1px solid #d7d7d7;width:300px;">
                    <button onclick="notice_send_recv_list('noticesend','','search')" class="btn-link">
                        <img src="/iam/img/menu/icon_bottom_search.png" style="width:24px;height:24px;">
                    </button>
                    <button type="button" class="btn btn-link" style="float:right;color:rgb(130,199,54)" onclick="notice_delete_all('send')">전체삭제</button>
                </div>
            </div>
            <div class="modal-body popup-bottom" style="background:#f5f5f5">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <table style="width:100%">
                        <thead>
                            <th class="iam_table" style="width:20%;">일시</th>
                            <th class="iam_table" style="width:15%;">내용</th>
                            <th class="iam_table" style="width:35%;">링크주소</th>
                            <th class="iam_table" style="width:12%;">대상</th>
                            <th class="iam_table" style="width:10%;">가리기</th>
                        </thead>
                        <tbody id="notice_send_side">

                        </tbody>
                    </table>
                </div>
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <button style="border-radius: 5px;width:20%;font-size:15px;margin-top: 10px;font-weight:700;background:#d7d7d7;" onclick="notice_send_recv_list('noticesend', 'more')">더보기</button>
                </div>
                <div class="container" style="margin-top: 50px;">
                    <p style="font-size:15px;border: 2px solid #d7d7d7;font-weight:500;padding:20px;">
                        [주의사항]<br>
                        삭제를 하면 수신페이지에서 해당 공지글이 삭제되어 더 이상 볼 수 없습니다.<br>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="auto_list_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
</div>
<!-- ./오토회원 설정리스트 수정 모달 -->
<div id="auto_list_edit_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
        <div class="modal-content" id="edit_modal_content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>회원가입 메시지 리스트</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <table style="width:100%">
                        <form method="post" id="dForm_edit" name="dForm_edit" action="/ajax/edit_event.php" enctype="multipart/form-data">
                            <tbody id="edit_event">
                                <tr>
                                    <input type="hidden" id="location_iam" name="location_iam" value="location_iam">
                                    <input type="hidden" id="save" name="save" value="save">
                                    <input type="hidden" id="event_idx" name="event_idx" value="">
                                    <th class="iam_table" style="width:20%;">아이디</th>
                                    <td class="iam_table"><?= $_SESSION['iam_member_id']; ?></td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:20%;">이벤트타이틀</th>
                                    <td class="iam_table">
                                        <textarea style="width:100%;height: 50px;" name="event_title" id="event_title" value=""></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:20%;">이벤트메시지</th>
                                    <td class="iam_table"><textarea style="width:100%;height: 100px;" name="event_desc" id="event_desc" value=""></textarea></td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:20%;">카드링크</th>
                                    <td class="iam_table">
                                        <input type="text" id="card_short_url" hidden>
                                        <div id="cardsel1" onclick="limit_selcard1()" style="margin-top:15px;">
                                            <?
                                            $sql5 = "select card_short_url,phone_display, card_title from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' order by req_data asc";
                                            $result5 = mysql_query($sql5);
                                            $i = 0;
                                            while ($row5 = mysql_fetch_array($result5)) {
                                                if ($i == 0) {
                                                    $hidden = "hidden";
                                                } else {
                                                    $hidden = "";
                                                }
                                            ?>
                                                <input type="checkbox" id="multi_westory_card_url1_<?= $i + 1 ?>" name="multi_westory_card_url1" class="we_story_radio1" value="<?= $i + 1 ?>" <? if (
                                                                                                                                                                                                    $row5['phone_display'] == "N"
                                                                                                                                                                                                ) {
                                                                                                                                                                                                    echo "onclick='locked_card_click();'";
                                                                                                                                                                                                } ?> <?= $hidden ?>>
                                                <span <? if ($row5['phone_display'] == "N") {
                                                            echo "class='locked' title='비공개카드'";
                                                        } ?> <?= $hidden ?>>
                                                    <?= $i + 1 ?>번(<?= $row5['card_title'] ?>)
                                                </span>
                                            <?
                                                $i++;
                                            }
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                                <!-- <input type="text" style="width:100%;" name="card_short_url" id="card_short_url" value="" > -->
                                <tr>
                                    <th class="iam_table" style="width:20%;">버튼타이틀</th>
                                    <td class="iam_table"><input type="text" style="width:100%;" name="btn_title" id="btn_title" value=""></td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:20%;">버튼링크</th>
                                    <td class="iam_table"><input type="text" style="width:100%;" name="btn_link" id="btn_link" value=""></td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:20%;">단축주소</th>
                                    <td class="iam_table"><input type="text" style="width:100%;" name="short_url" id="short_url" value=""></td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:20%;">조회수</th>
                                    <td class="iam_table"><input type="text" style="width:100%;" name="read_cnt" id="read_cnt" value=""></td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:20%;">이미지</th>
                                    <td class="iam_table"><input type="file" name="autojoin_img" style="width:200px;"><span id="autojoin_img_event"></span></td>
                                </tr>
                                <tr id="step_info_tr">
                                    <th class="iam_table" style="width:20%;">스텝문자정보</th>
                                    <td class="iam_table">
                                        <input type="text" style="width:45%;" name="step_title" id="step_title" value="" disabled>
                                        <input type="text" style="width:10%;" name="step_cnt" id="step_cnt" value="" disabled>
                                        <input type="text" style="width:30%;" name="step_phone" id="step_phone" value="" disabled>
                                        <br><br>
                                        <p id="step_info" style="display:inline-block;"></p>
                                        적용상황
                                        <label class="step_switch" id="step_apply">
                                            <input type="checkbox" name="step_allow_state" id="step_allow_state" value="">
                                            <span class="slider round" name="step_status_round" id="step_status_round"></span>
                                        </label>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="iam_table" style="width:20%;">등록일시</th>
                                    <td class="iam_table"><input type="text" style="width:100%;" name="regdate1" id="regdate1" value=""></td>
                                </tr>
                            </tbody>
                    </table>
                    </form>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;display:flex;padding:0px;">
                <button type="button" class="btn-default" style="width:50%;padding:10px 0px" onclick="goback('auto_list')">뒤로가기</button>
                <button type="button" style="width:50%;background:#99cc00;color:white;padding:10px 0px" onclick="save_edit_ev()">저장</button>
            </div>
        </div>
    </div>
</div>
<!-- ./튜터리얼 모달 -->
<div id="tutorial_popup" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
        <div class="modal-content" style="width: 300px;">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>환영합니다!</label>
            </div>
            <div class="modal-body">
                <div class="container" style="text-align: center;">
                    <img src="/iam/img/smile.png" class="contents_img" style="width:auto; margin-bottom:10px;">
                    <p style="font-size:16px;color:#6e6c6c" id="recv_msg">
                        지금부터 나의 IAM 카드를<br>
                        간편하게 만들어 볼까요~<br><br>
                        시작하기를 누르면 <br>
                        나의 멋진 IAM카드가 <br>
                        만들어 집니다.<br>
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center">
                <button type="button" class="btn btn-active" style="width:100%;border-radius:0px 0px 12px 12px" onclick="start_tutorial()">시작하기</button>
            </div>
        </div>
    </div>
</div>
<!-- ./튜터리얼 홈추가 모달 -->
<div id="tutorial_addhome_popup" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">

        <div class="modal-content" style="width: 300px;">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="start_app_install()">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>홈추가 하기</label>
            </div>
            <div class="modal-body">
                <div class="container" style="text-align: center;">
                    <img src="/iam/img/smile.png" class="contents_img" style="width:auto; margin-bottom:10px;">
                    <p style="font-size:16px;color:#6e6c6c" id="recv_msg">
                        홈추가하기를 하시면<br>
                        폰 바탕화면에<br>
                        아이엠 바로가기 아이콘이<br>
                        생성됩니다.<br><br>
                        언제든 쉽게 아이엠에<br>
                        들어갈 수 있어요.<br>
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center">
                <button type="button" class="btn btn-primary" style="background-color:#50d050" onclick="addMainBtn('<?= str_replace("'", "", $cur_card['card_name']) ?>','?<?= $request_short_url . $card_owner_code ?>')">확인</button>
            </div>
        </div>
    </div>
</div>
<!-- ./튜터리얼 앱설치 모달 -->
<div id="install-modalwindow" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10%;width : 80%;max-width:600px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>앱 설치하기 안내</label>
            </div>
            <div class="modal-body">
                <div>
                    <div class="a1">
                        ※ 앱을 설치하면 콜백문자, 대량문자, 포털디비수집, 365일 자동메시지발동, 자동예약메시지기능, IAM기능 등 통합적인 모든 기능을 사용할 수 있습니다.<br><br>
                        ※ 현재 앱설치는 안드로이드폰만 가능, IAM은 아이폰에서 [홈화면추가]로 이용 가능합니다.<br><br>
                        ※ IAM 앱설치가 완료되면 앱을 통해 문자를 수신하고, 이후에도 온리원 플랫폼에서 보내는 문자는 IAM앱을 통해서 수신됩니다.
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border:none;text-align: center">
                <button type="button" class="btn btn-active btn-left" onclick="location.href='https://play.google.com/store/apps/details?id=mms.onepagebook.com.onlyonesms'">IAM앱 설치하기</button>
                <button type="button" class="btn btn-default btn-right" onclick="install_cancel()">아이엠 카드 완성하기</button>
            </div>
        </div>
    </div>
</div>
<div id="group_contents_list_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:768px">

        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="location.reload()">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>멤버 콘텐츠 업로드 리스트</label>
            </div>
            <div class="modal-body">
                <div class="container" style="text-align: center;">
                    <table style="width:100%">
                        <thead>
                            <th class="iam_table" style="width:15%;">일시</th>
                            <th class="iam_table" style="width:15%;">이름</th>
                            <th class="iam_table" style="width:15%;">아이디</th>
                            <th class="iam_table" style="width:25%;">미리보기</th>
                            <th class="iam_table" style="width:15%;">노출</th>
                            <th class="iam_table" style="width:10%;">전체삭제</th>
                        </thead>
                        <tbody id="group_contents_list">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="we_con_guide" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:768px">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="location.reload()">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>회원들의 아이엠 보기</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <img src="/iam/img/smile.png" class="contents_img" style="width:auto; margin-bottom:10px;">
                    <p style="font-size:16px;color:#6e6c6c">
                        다른 회원의 아이엠을 본 후<br>
                        다시 내 아이엠으로 돌아가려면<br>
                        PC에서는 새창뜨기된 다른 회원의<br>
                        아이엠창을 닫아주시고, 폰에서는<br>
                        <뒤로가기>를 해주세요. <br>
                            더 이상 이 알림창을 보지 않기를<br>
                            원하시면 아래 탭을 클릭해주세요.<br>
                    </p>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center">
                <button type="button" class="btn btn-active" style="width:100%;border-radius:0px 0px 12px 12px" onclick="stop_westory_guide()">더 이상 보지 않기</button>
            </div>
        </div>
    </div>
</div>
<div id="show_navermap_address" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:500px">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>네이버 지도 주소</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <p style="font-size:16px;color:#6e6c6c" id="naver_map_address">
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="show_navermap_distance" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:500px">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>네이버 지도 거리 계산</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <input type="hidden" id="map_pos" name="map_pos">
                    출발점 : <input type='text' id="start_pos" name="start_pos" style="width:80%;font-size: 17px;margin-bottom: 10px;"><br>
                    도착점 : <input type='text' id="end_pos" name="end_pos" style="width:80%;font-size: 17px;margin-bottom: 10px;"><br>
                    거리 : <input type='text' id="distance_val" name="distance_val" readonly style="width:30%;font-size: 17px;margin-bottom: 10px;"><span style="font-size: 20px;margin-left: 5px;">Km</span><br>
                    <button onclick="calc_distance()" style="padding: 5px;font-size: 15px;background-color: #5bd540;color: white;">계산하기</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="show_navermap_busitime" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:500px">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>영업시간</label>
            </div>
            <div class="modal-body">
                <div class="container" style="text-align: center;">
                    <p id="timedata" style="font-size: 17px;">

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="set_reduction_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:650px">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>상품 할인율 설정하기</label>
            </div>
            <div class="modal-body">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <input type="hidden" id="card_idx_percent">
                    <table style="width:100%">
                        <thead>
                            <th class="iam_table" style="width:24%;">구분</th>
                            <th class="iam_table" style="width:24%;">할인율(%)</th>
                            <th class="iam_table" style="width:24%;">건수입력</th>
                            <th class="iam_table" style="width:24%;">저장</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="iam_table">이벤트형</td>
                                <td class="iam_table"><input type="number" id="reduction_percent_event"></td>
                                <td class="iam_table"><input type="number" id="reduction_percent_cnt"></td>
                                <td class="iam_table"><button type="button" id="event_type_setbtn" class="btn btn-primary" style="background-color:#99cc00;border-color: white;" onclick="save_set_reduce('event')">저장하기</button></td>
                            </tr>
                            <tr>
                                <td class="iam_table">고정형</td>
                                <td class="iam_table"><input type="number" id="reduction_percent_fixed"></td>
                                <td class="iam_table">X</td>
                                <td class="iam_table"><button type="button" id="fixed_type_setbtn" class="btn btn-primary" style="background-color:#99cc00;border-color: white;" onclick="save_set_reduce('fixed')">저장하기</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center">
                <!--button data-dismiss="modal" type="button" class="btn btn-primary" style="background-color:#50d050;border-color: white;">취소하기</button-->
                <!-- <button type="button" class="btn btn-primary" style="background-color:#50d050;border-color: white;" onclick="save_set_reduce()">저장하기</button> -->
            </div>
        </div>
    </div>
</div>
<!-- // 종이명함 정보입력 팝업 -->
<div id="paper_list_edit_modal" class="modal fade" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
        <div class="modal-content" id="edit_modal_content" style="text-align: center;">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>종이명함 정보입력</label>
            </div>
            <div class="modal-body">
                <input type="hidden" name="paper_seq" id="paper_seq" value="">
                <div class="container" style="margin-top: 20px;text-align: center;">
                    <table style="width:100%">
                        <tbody id="edit_event">
                            <tr>
                                <th class="iam_table" style="width:20%;">이름</th>
                                <td class="iam_table"><input type="text" style="width:100%;" name="paper_name" id="paper_name" value=""></td>
                            </tr>
                            <tr>
                                <th class="iam_table" style="width:20%;">직책</th>
                                <td class="iam_table"><input type="text" style="width:100%;" name="paper_job" id="paper_job" value=""></td>
                            </tr>
                            <tr>
                                <th class="iam_table" style="width:20%;">기관명</th>
                                <td class="iam_table"><input type="text" style="width:100%;" name="paper_org" id="paper_org" value=""></td>
                            </tr>
                            <tr>
                                <th class="iam_table" style="width:20%;">주소</th>
                                <td class="iam_table"><input type="text" style="width:100%;" name="paper_addr" id="paper_addr" value=""></td>
                            </tr>
                            <tr>
                                <th class="iam_table" style="width:20%;">전화1</th>
                                <td class="iam_table"><input type="text" style="width:100%;" name="paper_phone1" id="paper_phone1" value=""></td>
                            </tr>
                            <tr>
                                <th class="iam_table" style="width:20%;">전화2</th>
                                <td class="iam_table"><input type="text" style="width:100%;" name="paper_phone2" id="paper_phone2" value=""></td>
                            </tr>
                            <tr>
                                <th class="iam_table" style="width:20%;">휴대폰</th>
                                <td class="iam_table"><input type="text" name="paper_mobile" id="paper_mobile" style="width:200px;"><span id="autojoin_img_event"></span></td>
                            </tr>
                            <tr>
                                <th class="iam_table" style="width:20%;">팩스폰</th>
                                <td class="iam_table"><input type="text" name="paper_fax" id="paper_fax" style="width:200px;"><span id="autojoin_img_event"></span></td>
                            </tr>
                            <tr>
                                <th class="iam_table" style="width:20%;">이멜1</th>
                                <td class="iam_table"><input type="text" name="paper_email1" id="paper_email1" style="width:200px;"><span id="autojoin_img_event"></span></td>
                            </tr>
                            <tr>
                                <th class="iam_table" style="width:20%;">이멜2</th>
                                <td class="iam_table"><input type="text" name="paper_email2" id="paper_email2" style="width:200px;"><span id="autojoin_img_event"></span></td>
                            </tr>
                            <tr>
                                <th class="iam_table" style="width:20%;">메모</th>
                                <td class="iam_table"><textarea style="width:100%;height: 50px;" name="paper_memo" id="paper_memo" value=""></textarea></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div style="margin: 0px auto 15px auto;">
                <a href="javascript:show_comment()" style="background-color: grey;color: white;padding: 5px;cursor:pointer;">텍스트보기</a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-default btn-left" onclick="cancel_paper()">취소</button>
                <button type="button" class="btn-active btn-right" onclick="save_paper()">저장</button>
            </div>
        </div>
    </div>
</div>
<div id="show_paper_comment" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:500px">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>명함정보</label>
            </div>
            <div class="modal-body">
                <div class="container" style="text-align: left;">
                    <p id="comment_data" style="font-size: 17px;">
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="show_paper_image" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:500px">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label></label>
            </div>
            <div class="modal-body">
                <div class="container" style="text-align: center;" id="paper_image_link">

                </div>
            </div>
        </div>
    </div>
</div>

<div id="req_up_mem_leb" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:500px">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label></label>
            </div>
            <div class="modal-body popup-bottom">
                <div class="container" style="text-align: left;">
                    <input type="hidden" id="map_pos" name="map_pos">
                    휴대폰 <input type='text' id="req_mem_phone" name="req_mem_phone" style="width:80%;font-size: 17px;margin-bottom: 10px;"><br>
                    주소 <input type='text' id="req_mem_addr" name="req_mem_addr" style="width:80%;font-size: 17px;margin-bottom: 10px;margin-left: 14px;"><br>
                    계좌 <input type='text' id="req_mem_bank_name" name="req_mem_bank_name" style="width:26%;font-size: 17px;margin-bottom: 10px;margin-left: 14px;" placeholder="은행"><input type='text' id="req_mem_bank_account" name="req_mem_bank_account" style="width:26%;font-size: 17px;margin-bottom: 10px;" placeholder="계좌번호"><input type='text' id="req_mem_bank_owner" name="req_mem_bank_owner" style="width:26%;font-size: 17px;margin-bottom: 10px;" placeholder="예금주"><br>
                    이메일 <input type='text' id="req_mem_email" name="req_mem_email" style="width:80%;font-size: 17px;margin-bottom: 10px;">
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;display:flex;padding:0px;margin-top: 10px;">
                <button type="button" class="btn-default btn-left" onclick="cancel_req_modal()">취소</button>
                <button type="button" class="btn-active btn-right" onclick="save_req_mem_leb()">저장</button>
            </div>
        </div>
    </div>
</div>

<div id="show_gwc_order_option" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:570px">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label id="gwc_con_name_modal">무통장 결제 안내</label>
            </div>
            <div class="modal-body popup-bottom">
                <input type="hidden" name="gwc_ori_price" id="gwc_ori_price" val='1'>
                <input type="hidden" name="gwc_ori_opt_price" id="gwc_ori_opt_price" val='0'>
                <input type="hidden" name="gwc_ori_sup_price" id="gwc_ori_sup_price" val='0'>
                <input type="hidden" name="gwc_conts_idx" id="gwc_conts_idx" val='1'>
                <input type="hidden" name="seller_id" id="seller_id" val=''>
                <input type="hidden" name="page_type" id="page_type" val=''>
                <div class="container" id="gwc_price_option" style="text-align: left;margin-bottom: 25px;display:inline-flex;">
                    <img src="" id="gwc_con_img_modal" style="width: 30%;">
                    <div id="common_gwc_prod">
                        <div style="margin-left:20px;font-size:18px;margin-top: 75px;"><span id="gwc_conts_price" style="color: deeppink;"></span>원<span id="gwc_conts_salary" style="font-size: 14px;"></span><span id="gwc_conts_over_salary" style="font-size: 14px;"></span></div>
                        <div class="control_number">
                            <button type="button" class="btn_small grey" onclick="change_count(0)" style="font-weight: bold;font-size: 15px;border-radius: 12px;padding: 0px 8px;margin-left: 20px;">-</button>
                            <input type="text" name="conts_cnt" id="conts_cnt" value="1" title="수량설정" style="width:30px;padding:5px 7px;height:35px">
                            <button type="button" class="btn_small grey" onclick="change_count(1)" style="font-weight: bold;font-size: 15px;border-radius: 12px;">+</button>
                        </div>
                    </div>
                    <div id="cash_gwc_prod" style="display:none;">
                        <p style="margin-left:20px;">최소 3만원 ~ 최대 무제한<br>
                            <li style="margin-left:20px;">가끔 팡팡 보너스 기대</li>
                            <li style="margin-left:20px;">결제할떄 너무 편해요</li>
                        </p>
                        <div style="margin-left:20px;font-size:18px;margin-top: 15px;"><input type="number" id="gwc_conts_price" style="color: deeppink;width:100px;" step="10000" min="10000" value="30000">원</div>
                    </div>
                </div>
                <div class="container" id="gwc_order_option" style="text-align: left;">

                </div>
                <div class="gwc_order_option_number"></div>
            </div>
            <div class="modal-footer" style="text-align: center;display:flex;padding:0px;margin-top: 10px;">
                <button type="button" class="btn-default btn-left" onclick="cancel_order_modal()">취소</button>
                <button type="button" class="btn-active btn-right" onclick="save_order_option()">확인</button>
            </div>
        </div>
    </div>
</div>

<div id="gwc_pay_result_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:500px">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>굿마켓 상품 내계정으로 가져오기 안내</label>
            </div>
            <div class="modal-body" style="background-color:#e5e3e3;">
                <input type="hidden" name="gwc_ori_price" id="gwc_ori_price" val='1'>
                <input type="hidden" name="gwc_conts_idx" id="gwc_conts_idx" val='1'>
                <input type="hidden" name="seller_id" id="seller_id" val=''>
                <input type="hidden" name="page_type" id="page_type" val=''>
                <input type="hidden" name="all_get_cnt" id="all_get_cnt" val=''>
                <input type="hidden" name="pre_month_money" id="pre_month_money" val=''>
                <input type="hidden" name="pre_month_cnt" id="pre_month_cnt" val=''>
                <input type="hidden" name="pre_get_cnt" id="pre_get_cnt" val=''>
                <input type="hidden" name="this_get_cnt" id="this_get_cnt" val=''>
                <input type="hidden" name="this_month_cnt" id="this_month_cnt" val=''>
                <input type="hidden" name="this_month_money" id="this_month_money" val=''>
                <input type="hidden" name="show_modal" id="show_modal" val='N'>
                <div class="container" style="text-align: left;">
                    <h3><?= $member_iam['mem_name'] . "님"; ?></h3>
                </div>
                <div class="container" style="text-align: left;margin: 15px 0px;background-color: white;border-radius: 7px;padding: 10px;display:inline-flex;box-shadow: -3px 5px 7px rgb(0 0 0 / 30%);">
                    <p style="width:70%;">구매금액(전월 / 금월)</p>
                    <span id="pay_money_month">1231</span>
                </div>
                <div class="container" style="text-align: left;background-color: white;border-radius: 7px;padding: 10px;box-shadow: -3px 5px 7px rgb(0 0 0 / 30%);">
                    <p style="display:inline-flex;width: 100%;margin: 5px 0px;">
                        <span style="width:80%;">상품가져오기 가능 전체 건수</span>
                        <span id="possible_cnt_month">4</span>
                    </p>
                    <p style="display:inline-flex;width: 100%;margin: 5px 0px;">
                        <span style="width:80%;">현재 가져온 상품 건수</span>
                        <span id="get_cnt_month">6</span>
                    </p>
                    <p style="display:inline-flex;width: 100%;margin: 5px 0px;">
                        <span style="width:80%;">상품가져오기 가능 잔여 건수</span>
                        <span id="rest_cnt_month">4</span>
                    </p>
                    <p style="margin-top: 10px;color: darkgrey;">※1계정당 굿마켓 상품가져오기 최대 건수는 20건입니다.</p>
                </div>
                <div class="container" style="text-align: center;margin-top:15px;background-color: white;border-radius: 7px;padding: 10px;box-shadow: -3px 5px 7px rgb(0 0 0 / 30%);">
                    <p style="font-size: 17px;">굿마켓 상품등록안내</p>
                    <img src="/iam/img/gwc_prod_intro.png" style="margin-top: 5px;border: 1px solid lightgrey;">
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;background-color: #e5e3e3;padding:7px;">
                <button type="button" style="width:100%;background:#99cc00;color:white;padding:10px 0px" onclick="close_gwc_intro()">잘 확인했습니다.</button>
            </div>
        </div>
    </div>
</div>

<div id="gwc_category_list_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:780px">
        <div class="modal-content" style="border-radius:12px;">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>카테고리</label>
            </div>
            <div class="modal-body">
                <div class="container" id="gwc_category_list" style="text-align: left;">
                    <div class="cate_list_div">
                        <h4 class="cate_list_title">패션잡화/화장품</h4>
                        <p><a href="javascript:show_gwc_cate_prod('934329')">패션소품/액세서리</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('934722')">주얼리/보석/시계</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('935615')">신발/운동화/구두</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('935757')">가방/지갑/벨트</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('936099')">화장품/바디/헤어</a></p>
                    </div>
                    <div class="cate_list_div">
                        <h4 class="cate_list_title">의류/언더웨어</h4>
                        <p><a href="javascript:show_gwc_cate_prod('937019')">여성의류(상의)</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('937056')">여성의류(하의/원피스)</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('937141')">남성의류/정장</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('937226')">여성언더웨어</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('937314')">남성언더웨어</a></p>
                    </div>
                    <div class="cate_list_div">
                        <h4 class="cate_list_title">출산/유아동/완구/도서</h4>
                        <p><a href="javascript:show_gwc_cate_prod('937339')">출산/유아용품/임부복</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('937416')">신생아/유아동의류</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('937427')">유아동신발/잡화/패션소품</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('937435')">완구/장난감/교구/도서</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('937473')">문구/사무/팬시</a></p>
                    </div>
                    <div class="cate_list_div">
                        <h4 class="cate_list_title">식품/건강/스포츠/자동차</h4>
                        <p><a href="javascript:show_gwc_cate_prod('1029774')">식품/농수축산물</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('974442')">선물세트</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('1002328')">건강/헬스/성인용품</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('1034733')">스포츠/레저</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('1036309')">자동차용품</a></p>
                    </div>
                    <div class="cate_list_div">
                        <h4 class="cate_list_title">휴대폰/컴퓨터/가전/산업</h4>
                        <p><a href="javascript:show_gwc_cate_prod('1036310')">휴대폰/액세서리</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('1036445')">컴퓨터/소모품</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('1034893')">가전/디지털제품</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('1037573')">원단/공구/산업/매장용품</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('1037592')">택배/포장/운반용품</a></p>
                    </div>
                    <div class="cate_list_div">
                        <h4 class="cate_list_title">생활/가구/취미/이벤트용품</h4>
                        <p><a href="javascript:show_gwc_cate_prod('1037708')">생활/욕실/청소/수납</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('1037798')">식기/조리기구/주방</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('1047845')">침구/홈패션/인테리어</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('1047846')">가구/DIY</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('1047943')">꽃/취미/애완/이벤트용품</a></p>
                    </div>
                    <div class="cate_list_div">
                        <h4 class="cate_list_title">데일리 식생활</h4>
                        <p><a href="javascript:show_gwc_cate_prod('2626887')">농산물</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('2626890')">밀키트</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('2626889')">수산물</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('2626888')">가공식품</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('2626891')">축산물</a></p>
                    </div>
                    <div class="cate_list_div">
                        <h4 class="cate_list_title">웰빙 상품</h4>
                        <p><a href="javascript:show_gwc_cate_prod('2626894')">건강식품</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('2626892')">반려동물</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('2626893')">명인상품</a></p>
                    </div>
                    <div class="cate_list_div">
                        <h4 class="cate_list_title">특별 상품</h4>
                        <p><a href="javascript:show_gwc_cate_prod('1268514')">굿마켓추천상품</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('2477701')">굿마켓베스트상품</a></p>
                        <p><a href="javascript:show_gwc_cate_prod('2806831')">토탈 건강/뷰티/미용기기</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="req_seller_mem_leb" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;z-index:1060">
    <div class="modal-dialog" style="margin: 100px auto;width: 100%;max-width:500px">
        <div class="modal-content">
            <div class="modal-title">
            </div>
            <div class="modal-body">
                <div class="container" style="text-align: left;">
                    <input type="hidden" id="map_pos" name="map_pos">
                    폰번호 <input type='text' id="seller_mem_phone" name="seller_mem_phone" style="width:50%;font-size: 17px;margin-bottom: 10px;margin-left: 13px;"><a style="float: right;width: 80px;display: block;margin-left: 5px;padding: 7px 5px;font-size: 11px;color: #fff;line-height: 14px;background-color: #99cc00;text-align: center;border-radius: 10px;" href="javascript:get_sms()">인증번호받기</a><br>
                    <p style="margin-left: 50px;">※ 입력한 폰번호는 수정되지 않습니다. 다시 한번 확인하세요.</p><br>
                    인증번호 <input type='text' id="seller_rnum" name="seller_rnum" style="width:50%;font-size: 17px;margin-bottom: 10px;"><a style="float: right;width: 80px;display: block;margin-left: 5px;padding: 7px 5px;font-size: 11px;color: #fff;line-height: 14px;background-color: #99cc00;text-align: center;border-radius: 10px;" href="javascript:chk_sms()">인증번호확인</a><br>
                    <span id="check_sms"></span><br>
                    주소 <input type='text' id="seller_mem_addr" name="seller_mem_addr" style="width:80%;font-size: 17px;margin-bottom: 10px;margin-left: 26px;"><br>
                    계좌 <input type='text' id="seller_mem_bank_name" name="seller_mem_bank_name" style="width:26%;font-size: 17px;margin-bottom: 10px;margin-left: 26px;" placeholder="은행"><input type='text' id="seller_mem_bank_account" name="seller_mem_bank_account" style="width:26%;font-size: 17px;margin-bottom: 10px;" placeholder="계좌번호"><input type='text' id="seller_mem_bank_owner" name="seller_mem_bank_owner" style="width:26%;font-size: 17px;margin-bottom: 10px;" placeholder="예금주"><br>
                    이메일 <input type='text' id="seller_mem_email" name="seller_mem_email" style="width:80%;font-size: 17px;margin-bottom: 10px;margin-left: 14px;">
                    <input type="hidden" id="mem_special_type">
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;display:flex;padding:0px;margin-top: 10px;">
                <button type="button" class="btn-left btn-default" style="width:50%;padding:10px 0px" onclick="cancel_seller_modal()">취소</button>
                <button type="button" class="btn-right btn-active" onclick="save_seller_mem_leb()">저장</button>
            </div>
        </div>
    </div>
</div>
<!--intro 팝업-->
<div id="intro-modalwindow" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1100;top: 20px;height: 100%">
    <div class="modal-dialog modal-sm" role="document" style="margin-bottom: 30px;width:90%;max-width : 500px;margin-left:auto;margin-right:auto;">
        <div class="modal-content" style="margin-right:0px;">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <div>
                    <img src='/iam/img/menu/icon_intro_white.png' style="width:24px;vertical-align:bottom">
                    <label>안내</label>
                </div>
            </div>
            <div class="modal-body popup-bottom" style="background-color: #e5e5e5;">
                <div>
                    <div style="padding-top: 2px;">
                        <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px">
                            <div style="display: flex">
                                <img src="/iam/img/menu/icon_intro_mycon.png" style="width: 24px;height:24px;margin-left: 30px;margin-top: 30px;" id="intro_img">
                                <label style="font-size:14px;margin-left: 10px; margin-top: 30px" id="intro_title">test</label>
                            </div>
                            <label style="font-size:14px;font-weight:300;margin-left: 10px;margin-top: 10px" id="intro_desc">desc</label>
                            <label style="font-size:14px;font-weight:300;margin-left: 10px;margin-top: 10px;margin-bottom: 10px" id="intro_link_text">상세페이지:<a href="#"></a></label>
                        </div>
                    </div>
                    <div style="padding-top: 5px;">
                        <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex" onclick="showVideo()">
                            <img src="/iam/img/menu/icon_intro_video.png" style="width: 24px;height:24px;margin-left: 10px;margin-top: 7px">
                            <label style="font-size:14px;background-color: #ffffff;border-radius: 10px;margin:8px 10px 8px 0px">아이엠 주요 동영상</label>
                        </div>
                    </div>
                    <div style="padding-top: 5px;">
                        <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex" onclick="showNews()">
                            <img src="/iam/img/menu/icon_intro_news.png" style="width: 24px;height:24px;margin-left: 10px;margin-top: 7px">
                            <label style="font-size:14px;background-color: #ffffff;border-radius: 10px;margin:8px 10px 8px 0px">아이엠 소식</label>
                        </div>
                    </div>
                    <div style="padding-top: 5px;">
                        <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex" onclick="openIntroKakao('<?= $domainData['kakao'] ?>')">
                            <img src="/iam/img/menu/icon_intro_kakao.png" style="width: 24px;height:24px;margin-left: 10px;margin-top: 7px">
                            <label style="font-size:14px;background-color: #ffffff;border-radius: 10px;margin:8px 10px 8px 0px">카톡창으로 문의하기</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--동영상 팝업-->
<div id="video-modalwindow" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1100;">
    <div class="modal-dialog modal-sm" id="video-modal-dialog" role="document" style="width:100%;max-width : 300px;margin-right:0px;max-height: 80%;top : 50px">
    </div>
</div>
<!--아이엠 뉴스 팝업-->
<div id="news-modalwindow" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1100;">
    <div class="modal-dialog modal-sm" role="document" style="width:90%;max-width : 500px;margin-left:auto;margin-right:auto;max-height: 80%;top : 50px">
        <div class="modal-content" style="margin-right:0px;">
            <div class="modal-title" style="display: flex;justify-content: space-between;">
                <button type="button" style="background-color: transparent" onclick="backIntro();">
                    <h3 style="color: #ffffff">
                        << /h3>
                </button>
                <span style="text-align: center">
                    <label style="color: #ffffff;font-size:18px">아이엠 소식</label>
                    <label style="color: #ffffff;font-size:16px">업데이트 및 교육정보 등을 안내합니다</label>
                </span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="margin-top:0px">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-body popup-bottom" style="background-color: #e5e5e5;max-height:400px;overflow-y:auto">
                <div>
                    <div class="container" style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;">
                        <button type="button" data-toggle="collapse" aria-expanded="true" data-target="#news_go" style="background-color: #ffffff;margin: 8px 0px">
                            <p style="font-size:14px;font-weight:bold">&#9660;바로가기</p>
                        </button>
                        <div id="news_go" class="collapse in" aria-expanded="true">
                            <a href="/iam">
                                <p style="font-size:14px;background-color: #ffffff;border-radius: 10px;margin-left: 10px;">아이엠</p>
                            </a>
                            <a href="?cur_win=best_sample">
                                <p style="font-size:14px;background-color: #ffffff;border-radius: 10px;margin-left: 10px;margin-top: 5px">샘플보기</p>
                            </a>
                            <a href="#">
                                <p style="font-size:14px;background-color: #ffffff;border-radius: 10px;margin-left: 10px;margin-top: 5px">유튜브 채널</p>
                            </a>
                            <a href="<?= $domainData['kakao'] ?>">
                                <p style="font-size:14px;background-color: #ffffff;border-radius: 10px;margin-left: 10px;margin-top: 5px;margin-bottom: 5px">카카오톡 오픈채팅</p>
                            </a>
                        </div>
                    </div>
                    <div class="dropdown" style="background-color: #ffffff;border-radius: 10px;margin-top: 2px;padding-bottom: 2px;">
                        <button type="button" class="dropdown-toggle" data-toggle="dropdown" style="background-color: #ffffff;margin: 8px 15px">
                            <p style="font-size:14px;font-weight:bold" id="news_kind">&#9660;전체</p>
                        </button>
                        <div id="news_kind" class="dropdown-menu" style="background-color: #ffffff;float:left;position: absolute;left: 0px !important;width: 18px;">
                            <? foreach ($iam_notice_arr as $key => $v) { ?>
                                <p style="font-size:14px;" class="dropdown-item" onclick="showNewsContent('<?= $key ?>','<?= $v ?>')"><?= $v ?></p>
                            <? } ?>
                        </div>
                    </div>
                    <?
                    $news_sql = "select * from tjd_sellerboard where category=10 and important_yn='Y' order by date desc";
                    $news_res = mysql_query($news_sql);
                    while ($news_row = mysql_fetch_array($news_res)) { ?>
                        <div style="padding-top: 1px;background-color: #ffffff;border-radius: 10px;margin-top: 2px" class="news_content <?= 'news_kind_' . $news_row['fl'] ?>">
                            <div style="display: flex">
                                <p style="font-size:14px;margin-top:2px;margin-left: 10px;margin-right: 10px"><?= $iam_notice_arr[$news_row['fl']] ?></p>
                                <p style="font-size:14px"><?= $news_row['date'] ?></p>
                            </div>
                            <div style="text-align: center">
                                <p style="font-size:18px"><?= $news_row['title'] ?></p><br>
                            </div>
                            <div style="text-align: left;margin-left: 10px">
                                <?= htmlspecialchars_decode($news_row['content']) ?>
                            </div>
                        </div>
                    <?  }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--notice 팝업-->
<div id="notice-modalwindow" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1100;top: 20px;height: 100%;overflow-x: hidden;overflow-y: auto;">
    <div class="modal-dialog" role="document" style="margin-bottom: 30px;width:90%;max-width : 500px;margin-left:auto;margin-right:auto;">
        <div class="modal-content" style="margin-right:0px;">
            <div style="position:absolute;right : 10px;bottom:10px;z-index:100;">
                <button type="button" style="background:transparent" onclick="openSampleModal()">
                    <img src="/iam/img/menu/icon_news_sample.png" style="width:30px">
                </button>
            </div>
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <div>
                    <img src='/iam/img/menu/icon_news_white.png' style="width:24px;vertical-align:bottom">
                    <label>회원님들의 콘텐츠 업로드</label>
                </div>
            </div>
            <div class="modal-body popup-bottom" style="padding:5px 15px 15px 15px;background-color: #e5e5e5;overflow-y:auto">
                <div style="padding-top: 2px;">
                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex">
                        <div>
                            <img src="/iam/img/main/masterimage.png" style="width: 50px;margin: 10px;">
                        </div>
                        <div>
                            <p style="margin-left:10px;margin-top:10px;font-size:14px">desc</p>
                            <p style="margin-left:10px;margin-top:10px;margin-bottom:10px;font-size:14px">상세페이지:<a href="#"></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--sample 팝업-->
<div id="sample-modalwindow" class="modal fade" tabindex="-1" role="dialog" style="z-index: 1100;top: 20px;height: 100%;overflow-x: hidden;overflow-y: auto;">
    <div class="modal-dialog" role="document" style="margin-bottom: 30px;width:90%;max-width : 500px;margin-left:auto;margin-right:auto;">
        <div class="modal-content" style="margin-right:0px;">
            <div style="position:absolute;right : 10px;bottom:10px;z-index:100;">
                <button type="button" style="background:transparent" onclick="openNoticeModal()">
                    <img src="/iam/img/main/icon-notice.png" style="width:30px">
                </button>
            </div>
            <div style="position:absolute;left : 10px;top:14px;z-index:100;">
                <button type="button" style="background:transparent;color:white" onclick="backNotice()">
                    < </button>
            </div>
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <div>
                    <img src='/iam/img/menu/icon_sample_white.png' style="width:24px;vertical-align:bottom">
                    <label>베스트 샘플IAM</label>
                </div>
            </div>
            <div class="modal-body" style="background-color: #e5e5e5;overflow-y:auto;padding-top:0px">
                <div style="padding-top: 2px;">
                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex">
                        <div>
                            <img src="/iam/img/main/masterimage.png" style="width: 24px;margin: 10px;">
                        </div>
                        <div>
                            <h4 style="margin-left: 10px;margin-top: 10px">desc</h4>
                            <h4 style="margin-left: 10px;margin-top: 10px;margin-bottom: 10px">상세페이지:<a href="#"></a></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--마이프로필 팝업-->
<div id="mypage-modalwindow" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 0px;width:70%;max-width : 500px;min-height:100%;margin-right:0px !important;margin-left : auto !important;">
        <div class="modal-content" style="margin-right:0px;">
            <div class="modal-header" style="border:none;background: #f5f5f5;">
                <div style="margin-top:20px;margin-left:15px;">
                    <img src="/iam/img/common/logo-2.png" style="height:35px;padding-top: 0px !important">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <img src="/iam/img/main/close.png" style="width:24px;margin-top: -20px;" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>

                <? if ($_SESSION['iam_member_id']) { ?>
                    <div style="margin-top:30px;margin-left:15px;display:flex;position:relative">
                        <? if ($member_iam['profile']) { ?>
                            <div style="width:70px;height:70px;border-radius: 50%;overflow: hidden;margin:0px !important">
                                <img src="<?= cross_image($member_iam['profile']) ?>" style="width:100%;height:100%;object-fit: cover;">
                            </div>
                        <?  } else { ?>
                            <div style="background:<?= $profile_color ?>;padding:5px 0px;width:70px;height:70px;border-radius: 50%;overflow:hidden;text-align:center;margin:0px !important">
                                <a class="profile_font" style="color:white;width:100%;height:100%;object-fit: cover;"><?= mb_substr($member_iam['mem_name'], 0, 3, "utf-8") ?></a>
                            </div>
                        <?  } ?>
                        <img src="/iam/img/menu/icon_profile_edit.png" style="position:absolute;left: 45px;top: 45px;cursor:pointer" onclick="<?='location.href=\'https://'.$_SESSION['site_iam'].'.kiam.kr/iam/mypage.php\''?>">
                        <div style="margin-left:20px;">
                            <h4><?= $member_iam['mem_name'] ?></h4>
                            <div style="display:flex;margin-top:10px">
                                <h5 style="padding:5px 0px"><?= $member_iam['mem_id'] ?></h5>
                                <?
                                $mem_leb = "일반";
                                /*if ($member_iam['mem_leb'] == "22")
                                    $mem_leb = "일반";
                                else if ($member_iam['mem_leb'] == "50")
                                    $mem_leb = "사업";
                                else if ($member_iam['mem_leb'] == "21")
                                    $mem_leb = "강사";
                                else if ($member_iam['mem_leb'] == "60")
                                    $mem_leb = "홍보";*/
                                if ($member_iam['service_type'] == "0") {
                                    $mem_leb = "FREE";
                                } else if ($member_iam['service_type'] == "1") {
                                    $mem_leb = "이용자";
                                } else if ($member_iam['service_type'] == "2") {
                                    $mem_leb = "리셀러";
                                } else if ($member_iam['service_type'] == "3") {
                            	    $mem_leb = "분양자";
                                }
                                ?>
                                <p style="font-size:10px;margin-left:20px;background:#99cc00;border-radius:20px;color:white;padding:5px 10px"><?= $mem_leb ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="sns_item" style="display:flex;justify-content: space-around;margin-top:10px;text-align:center;width:100%">
                        <div style="background: #99cc00;color:white;padding:4px 2px;border-radius:15px;">
                            <a href="<?= "javascript:go_my_con_page('" . $_SESSION['iam_member_id'] . "','" . $_SESSION['site_iam'] . "')" ?>" style="font-size:14px;font-weight:500;padding:10px;">내 IAM가기</a>
                        </div>
                        <div style="background: #f5f5f5;border:2px solid #d7d7d7;color:black;padding:4px 2px;border-radius:15px;">
                            <a href="javascript:logout('iam')" style="font-size:14px;font-weight:500;padding:10px;"><?= $MENU['TOP_MENU']['LOGOUT']; ?></a>
                        </div>
                    </div>
                <? } else { ?>
                    <div style="margin-top:30px;margin-left:0px;">
                        <div style="text-align: center">
                            <h4 style="color: black;text-align:left">아이엠을 이용하시려면 <strong>로그인</strong>이 필요해요.</h4>
                        </div>
                        <div style="display:flex;justify-content: space-between;;margin-top:30px">
                            <div class="center_text" style="width:40%;">
                                <div class="sns_item" style="background: #f5f5f5;border: 2px solid #d7d7d7;color:black;padding: 2px; border-radius: 15px;margin-top:0px;width:100%">
                                    <a href="/iam/login.php" style="font-size:14px;font-weight:500"><?= $MENU['TOP_MENU']['LOGIN']; ?></a>
                                </div>
                            </div>
                            <div class="center_text" style="width:40%;">
                                <div class="sns_item" style="background: #f5f5f5;border: 2px solid #d7d7d7;color:black;padding: 2px; border-radius: 15px;margin-top:0px;width:100%">
                                    <?
                                    if ($HTTP_HOST != "kiam.kr") {
                                        $join_link = get_join_link("http://" . $HTTP_HOST, $card_owner);
                                    } else {
                                        $join_link = get_join_link("http://www.kiam.kr", $card_owner);
                                    }
                                    ?>
                                    <a href="<?= $join_link ?>" style="font-size:14px;font-weight:500"><?= $MENU['TOP_MENU']['JOIN']; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <? } ?>
            </div>
            <div class="modal-body">
                <?
                if ($domainData['admin_iam_menu'] == 0) {
                    $menu_query = "select * from Gn_Iam_Menu where site_iam='kiam' and menu_type='BR' and use_yn = 'y' order by display_order";
                } else {
                    $menu_query = "select * from Gn_Iam_Menu where site_iam='{$menu_host}' and menu_type='BR' and use_yn = 'y' order by display_order";
                }
                $menu_res = mysql_query($menu_query);
                $menu_idx = 0;
                while ($menu_row = mysql_fetch_array($menu_res)) {
            	    $func = str_replace("HOST", "https://" . ($_SESSION['site_iam'] == "kiam" ? "www" : $_SESSION['site_iam']) . ".kiam.kr", $menu_row['move_url']);
                    $func = str_replace("card_link", $request_short_url . $card_owner_code, $func);
                    $func = str_replace("prewin", $cur_win, $func);
                    $func = str_replace("card_name", $cur_card['card_name'], $func);
                    $func = str_replace("id", $_SESSION['iam_member_id'], $func);
                    $func = str_replace("card_owner_code", $card_owner_code, $func);
                    if ($menu_row['page_type'] == "payment" && !$is_pay_version) {
                        $func = "";
                    } else if ($menu_row['page_type'] == "sub_admin" && !$_SESSION['iam_member_subadmin_id']) {
                        $func = "";
                    } else if (strstr($func, "iam_mall_popup") && !$mall_reg_state) {
                        $func = "";
                    }
                    $html = "";
                    if ($func != "") {
                        $html = "<div style=\"padding-top: 0px;display:flex;cursor:pointer\" onclick=\"" . $func . "\">";
                        if ($menu_idx == 0)
                            $img_style = "margin: 8px 2px 0px 2px;height:24px;";
                        else
                            $img_style = "margin-top:8px;height:24px;";
                        $html .= "<img src=\"" . $menu_row['img_url'] . "\" style=\"" . $img_style . "\">" .
                            "<div style=\"width:100%\">" .
                            "<h3 class = \"mypage_list\">" . $menu_row['title'] . "</h3>" .
                            "</div>";
                        $html .= "</div>";
                    }
                    echo $html;
                    $menu_idx++;
                }
                ?>
                <?/*if($is_pay_version){?>
                <div style="padding-top: 0px;display:flex;cursor:pointer" onclick="location.href='/iam/mypage.php'">
                    <img src="/iam/img/main/icon_iam_member.png" style="margin: 8px 2px 0px 2px;height:20px;">
                    <div style="width:100%">
                        <h3 class = "mypage_list"><?='회원정보';?></h3>
                    </div>
                </div>
                <div style="padding-top: 0px;display:flex;border-bottom:1px solid #ddd;cursor:pointer" onclick="location.href='/?cur_win=unread_notice'">
                    <img src="/iam/img/menu/icon_setting.png" style="margin-top:8px;height:24px;">
                    <div style="width:100%">
                        <h3 class = "mypage_list"><?=$MENU['TOP_MENU']['MYPAGE'];?></h3>
                    </div>
                </div>
                <?}?>
                <?if($_SESSION['iam_member_subadmin_id']) {?>
                <div style="padding-top: 0px;display:flex;cursor:pointer" onclick="location.href='/iama/service_Iam_admin.php'" title="<?=$MENU['TOP_MENU']['ADMIN_TITLE'];?>">
                    <img src="/iam/img/menu/icon_service_manager.png" style="margin-top:8px;height:24px;">
                    <div style="width:100%">
                        <h3 class = "mypage_list"><?=$MENU['TOP_MENU']['ADMIN'];?></h3>
                    </div>
                </div>
                <div style="padding-top: 0px;display:flex;cursor:pointer" onclick="show_automember_list()" title="오토회원가입리스트">
                    <img src="/iam/img/menu/icon_automem_list.png" style="margin-top:8px;height:24px;">
                    <div style="width:100%">
                        <h3 class = "mypage_list"><?="오토회원가입리스트"?></h3>
                    </div>
                </div>
                <div style="padding-top: 0px;display:flex;border-bottom:1px solid #ddd;cursor:pointer" onclick="send_notice()" title="공지사항 전송하기">
                    <img src="/iam/img/menu/icon_notice_send.png" style="margin-top:8px;height:24px;">
                    <div style="width:100%">
                        <h3 class = "mypage_list"><?="공지사항 전송하기"?></h3>
                    </div>
                </div>
                <?}?>
                <div style="padding-top: 0px;display:flex;cursor:pointer" onclick="location.href='/iam/mypage_payment.php'" title="결제정보">
                    <img src="/iam/img/menu/icon_manage_payment.png" style="margin-top:8px;height:24px;">
                    <div style="width:100%">
                        <h3 class = "mypage_list"><?="결제정보"?></h3>
                    </div>
                </div>
                <div style="padding-top: 0px;display:flex;cursor:pointer" onclick="location.href='/iam/mypage_post_lock.php'" title="댓글관리">
                    <img src="/iam/img/menu/icon_manage_post.png" style="margin-top:8px;height:24px;">
                    <div style="width:100%">
                        <h3 class = "mypage_list"><?="댓글관리"?></h3>
                    </div>
                </div>
                <div style="padding-top: 0px;display:flex;border-bottom:1px solid #ddd;cursor:pointer" onclick="location.href='/iam/mypage_payment_item.php'" title="판매내역">
                    <img src="/iam/img/menu/icon_manage_selling.png" style="margin-top:8px;height:24px;">
                    <div style="width:100%">
                        <h3 class = "mypage_list"><?="판매내역"?></h3>
                    </div>
                </div>
                <?if($mall_reg_state){?>
                <div style="padding-top: 0px;display:flex;" onclick="open_iam_mall_popup('<?=$_SESSION['iam_member_id']?>',1,'<?=$card_owner_code?>');">
                    <img src="/iam/img/menu/icon_shop.png" style="margin-top:8px;height:24px;">
                    <div style="width:100%">
                        <h3 class = "mypage_list">홍보대사 몰에 등록</h3>
                    </div>
                </div>
                <?}?>
                <div style="padding-top: 0px;display:flex;cursor:pointer" onclick="iam_mystory('cur_win=my_story')">
                    <img src="/iam/img/menu/icon_folder.png" style="margin-top:8px;height:24px;">
                    <div style="width:100%">
                        <h3 class = "mypage_list"><?=$MENU['IAM_MENU']['M5'];?></h3>
                    </div>
                </div>
                <div style="padding-top: 0px;display:flex;cursor:pointer" onclick="create_auto_card();">
                    <img src="/iam/img/menu/icon_ai.png" style="margin-top:8px;height:24px;">
                    <div style="width:100%">
                        <h3 class = "mypage_list"><?="AI로 자동 카드 만들기"?></h3>
                    </div>
                </div>
                <?*/ ?>
            </div>
        </div>
    </div>
</div>
<!--아이엠 공유 팝업-->
<div id="sns-modalwindow" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 200px;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header" style="border:none;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="/iam/img/icon_close_black.svg"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="card_share_preview">
                <div class="center_text">
                    <div class="sns_item" onclick="daily_send_pop()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon1.png">
                        </div>
                        <div class="sns_item_text">데일리<br>문자발송</div>
                    </div>
                    <div class="sns_item" onclick="sns_sendSMS()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon2.png">
                        </div>
                        <div class="sns_item_text">문자<br>보내기</div>
                    </div>
                    <? if ($_SESSION['iam_member_id'] && $_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master && $is_pay_version) { ?>
                        <div class="sns_item" onclick="open_iam_mall_popup('<?= $_SESSION['iam_member_id'] ?>',2,'<?= $card_owner_code ?>');">
                            <div class="sns_icon_div">
                                <img class="sns_icon" src="/iam/img/menu/icon_shopping.png">
                            </div>
                            <div class="sns_item_text">재능마켓<br>등록</div>
                        </div>
                    <? } else { ?>
                        <!--div class="sns_item" onclick="share_callback()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src = "/iam/img/sns_icon8.png">
                        </div>
                        <div class="sns_item_text">콜백<br>공유</div>
                    </div-->
                    <? } ?>
                </div>
                <div class="center_text">
                    <div class="sns_item" onclick="sns_shareKakaoTalk()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon3.png">
                        </div>
                        <div class="sns_item_text">카톡<br>공유</div>
                    </div>
                    <div class="sns_item" onclick="sns_shareFaceBook()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon5.png">
                        </div>
                        <div class="sns_item_text">페북<br>공유</div>
                    </div>
                    <div class="sns_item" onclick="sns_copyContacts()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon4.png">
                        </div>
                        <div class="sns_item_text">주소<br>복사</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--콘텐츠 공유 팝업-->
<div id="sns-modalwindow_contents" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 200px;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header" style="border:none;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="/iam/img/icon_close_black.svg"></button>
            </div>
            <div class="modal-body">
                <div class="center_text">
                    <div class="sns_item" onclick="contents_send_pop()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon1.png">
                        </div>
                        <div class="sns_item_text">데일리<br>문자발송</div>
                    </div>
                    <div class="sns_item" onclick="contents_sendSMS()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon2.png">
                        </div>
                        <div class="sns_item_text">문자<br>보내기</div>
                    </div>
                    <?/*if(($cur_win == "my_info" ||$cur_win =="my_story") && $is_pay_version){?>
                    <div class="sns_item" onclick="open_iam_mall_popup('<?=$_SESSION['iam_member_id']?>',3,j_idx);">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src = "/iam/img/menu/icon_shopping.png">
                        </div>
                        <div class="sns_item_text" >IAM몰<br>등록</div>
                    </div>
                    <?}else{?>
                    <div class="sns_item" onclick="share_callback()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src = "/iam/img/sns_icon8.png">
                        </div>
                        <div class="sns_item_text" >콜백<br>공유</div>
                    </div>
                    <?}*/ ?>
                </div>
                <div class="center_text">
                    <div class="sns_item" onclick="contents_shareKakaoTalk()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon3.png">
                        </div>
                        <div class="sns_item_text">카톡<br>공유</div>
                    </div>
                    <div class="sns_item" onclick="contents_shareFaceBook()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon5.png">
                        </div>
                        <div class="sns_item_text">페북<br>공유</div>
                    </div>
                    <div class="sns_item" onclick="contents_copyContacts()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon4.png">
                        </div>
                        <div class="sns_item_text">주소<br>복사</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--콘텐츠 공유 팝업-->
<div id="sns-modalwindow_mall" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 200px;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header" style="border:none;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><img src="/iam/img/icon_close_black.svg"></button>
            </div>
            <input type="hidden" id="mall_share_link" value="">
            <input type="hidden" id="mall_share_img" value="">
            <input type="hidden" id="mall_share_title" value="">
            <input type="hidden" id="mall_share_desc" value="">
            <div class="modal-body">
                <div class="center_text">
                    <div class="sns_item" onclick="mall_send_pop()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon1.png">
                        </div>
                        <div class="sns_item_text">데일리<br>문자발송</div>
                    </div>
                    <div class="sns_item" onclick="mall_sendSMS()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon2.png">
                        </div>
                        <div class="sns_item_text">문자<br>보내기</div>
                    </div>
                    <!--div class="sns_item" onclick="share_callback()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src = "/iam/img/sns_icon8.png">
                        </div>
                        <div class="sns_item_text" >콜백<br>공유</div>
                    </div-->
                </div>
                <div class="center_text">
                    <div class="sns_item" onclick="mall_shareKakaoTalk()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon3.png">
                        </div>
                        <div class="sns_item_text">카톡<br>공유</div>
                    </div>
                    <div class="sns_item" onclick="mall_shareFaceBook()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon5.png">
                        </div>
                        <div class="sns_item_text">페북<br>공유</div>
                    </div>
                    <div class="sns_item" onclick="mall_copyContacts()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon4.png">
                        </div>
                        <div class="sns_item_text">주소<br>복사</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--그룹 공유 팝업-->
<div id="group_share_modalwindow" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 200px;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header" style="border:none;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/icon_close_black.svg">
                </button>
            </div>
            <div class="modal-body">
                <div class="center_text">
                    <div class="sns_item" onclick="group_daily_send_pop()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon1.png">
                        </div>
                        <div class="sns_item_text">데일리<br>문자발송</div>
                    </div>
                    <div class="sns_item" onclick="group_sendSMS()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon2.png">
                        </div>
                        <div class="sns_item_text">문자<br>보내기</div>
                    </div>
                    <!--div class="sns_item" onclick="share_callback()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src = "/iam/img/sns_icon8.png">
                        </div>
                        <div class="sns_item_text" >콜백<br>공유</div>
                    </div-->
                </div>
                <div class="center_text">
                    <div class="sns_item" onclick="group_shareKakaoTalk()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon3.png">
                        </div>
                        <div class="sns_item_text">카톡<br>공유</div>
                    </div>
                    <div class="sns_item" onclick="group_shareFaceBook()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon5.png">
                        </div>
                        <div class="sns_item_text">페북<br>공유</div>
                    </div>
                    <div class="sns_item" onclick="group_copyContacts()">
                        <div class="sns_icon_div">
                            <img class="sns_icon" src="/iam/img/sns_icon4.png">
                        </div>
                        <div class="sns_item_text">주소<br>복사</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--연락처 팝업-->
<div id="pleaselogin" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 40%;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>IAM 연락처 보려면!</label>
            </div>
            <div class="modal-body">
                <div class="center_text">
                    <div class="login_text">
                        타인의 IAM에서는 볼 수 없으며<br>
                        앱설치 후 내 계정에서만 볼 수 있습니다.<br>
                        뒤로 가기하면 내 계정으로 이동됩니다.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-default btn-left" onclick="location.href='iam/join.php';">회원가입</button>
                <button type="button" class="btn-active btn-right" onclick="location.href='iam/login.php';">로그인</button>
            </div>
        </div>
    </div>
</div>
<!--프렌즈 팝업-->
<div id="pleaseloginfriends" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 40%;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>IAM 프렌즈 보려면!</label>
            </div>
            <div class="modal-body">
                <div class="center_text">
                    <div class="login_text">
                        IAM의 공개프렌즈와 나의 프렌즈는<br>
                        타인의 IAM에서는 볼 수 없으며<br>
                        앱설치 후 내 계정에서만 볼 수 있습니다.<br>
                        뒤로 가기하면 내 계정으로 이동됩니다.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-default btn-left" onclick="location.href='iam/join.php';">회원가입</button>
                <button type="button" class="btn-active btn-right" onclick="location.href='iam/login.php';">로그인</button>
            </div>
        </div>
    </div>
</div>
<!--앱설치 팝업-->
<div id="pleaseinstall" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 40%;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>IAM 프렌즈 보려면!</label>
            </div>
            <div class="modal-body">
                <div class="center_text">
                    <div class="login_bold">로그인후<br>연락처와 프렌즈<br>정보를 연동하기 위해서<br>온리원앱을 설치해야 합니다.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-active" style="width:100%;border-radius:0px 0px 12px 12px" onclick="location.href='https://play.google.com/store/apps/details?id=mms.onepagebook.com.onlyonesms';">IAM앱설치하기</button>
            </div>
        </div>
    </div>
</div>
<!--다중 콘텐츠 게시팝업-->
<div id="multi_contents_add_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="display: flex">
                <h2 class="modal-title">다중 콘텐츠박스 만들기</h2>
                <a href="javascript:add_contents_row();"><img src="/iam/img/star/icon-profile.png" style="position: absolute; right: 10px; top : 20px;" width="25" height="25" alt=""></a>
            </div>
            <div class="modal-body">
                <table class='table table-bordered' id="multi_contents_table">
                    <input type="hidden" value="<?= $cur_win == 'my_info' ? $request_short_url : $group_card_url ?>" id="multi_card_short_url">
                    <input type="hidden" value="<?= $gkind ?>" id="multi_contents_group">
                    <tr data-num="1">
                        <td class="bold" width="100px">콘텐츠 1번</td>
                        <td colspan="2">
                            <input type="text" id="multi_contents" style="width:100%;">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer" style="text-align: center">
                <button type="button" class="btn btn-secondary" id="multi_btn_cancel" data-dismiss="modal">취소하기</button>
                <button type="button" class="btn btn-primary" id="multi_btn_ok" onclick="multi_get_contents()">게시하기</button>
            </div>
        </div>
    </div>
</div>
<!--카드 콘텐츠 편집팝업-->
<div id="contents_order_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document" style="width:100%;max-width:768px;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>카드 콘텐츠 편집하기</label>
            </div>
            <div class="modal-body" style="border-bottom:1px solid #b5b5b5;height:52px">
                <div class="attr-value" style="display: flex;flex-wrap: wrap;justify-content: space-between;">
                    <div>
                        <input type="radio" name="cont_order_type" value="2" <? if ($cur_card['cont_order_type'] == 2) echo "checked" ?> onclick="click_cont_order_type(2);"><span>콘텐츠 등록일순</span>
                    </div>
                    <div>
                        <input type="radio" name="cont_order_type" value="3" <? if ($cur_card['cont_order_type'] == 3) echo "checked" ?> onclick="click_cont_order_type(3);"><span>원문 게시일순</span>
                    </div>
                    <div>
                        <input type="radio" name="cont_order_type" value="4" <? if ($cur_card['cont_order_type'] == 4) echo "checked" ?> onclick="click_cont_order_type(4);"><span>제목 가나다순</span>
                    </div>
                    <div>
                        <input type="radio" name="cont_order_type" value="1" <? if ($cur_card['cont_order_type'] == 1) echo "checked" ?> onclick="click_cont_order_type(1);"><span>콘텐츠 수동편집</span>
                    </div>
                </div>
            </div>
            <div class="modal-body popup-bottom" id="cont_order_box_body" <? if ($cur_card['cont_order_type'] != 1) echo "style='display:none'" ?>>
                <div id="cont_order_box" style="width: 100%;height: auto;min-height: 200px;display:flex;flex-wrap: wrap;align-content: flex-start;">
                    <?
                    if ($cur_win == "my_info" && $cur_card['card_short_url']) {
                        $reorder_card_url = $cur_card['card_short_url'];
                    } else if ($cur_win == "group-con" && $group_card_url) {
                        $reorder_card_url = $group_card_url;
                    }
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-default btn-left" data-dismiss="modal">취소하기</button>
                <button type="button" class="btn-active btn-right" onclick="change_cont_order_type('<?= $reorder_card_url ?>')">변경하기</button>
            </div>
        </div>
    </div>
</div>
<!--개별 콘텐츠 게시팝업-->
<div id="contents_add_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document" style="width:auto;max-width:768px;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label><?= $MENU['IAM_CONTENTS']['TOP_TITLE']; ?></label>
            </div>
            <div style="display:flex;justify-content: space-between;border-top:1px solid #ddd">
                <div style="margin-top:5px;margin-left:10px;display:flex;">
                    <? if ($member_iam['profile']) { ?>
                        <div style="width: 30px;height: 30px;border-radius: 50%;overflow: hidden;">
                            <img src="<?= cross_image($member_iam['profile']) ?>" style="width:100%;height:100%;object-fit: cover;">
                        </div>
                    <? } else { ?>
                        <div style="background:<?= $profile_color ?>;padding:3px 0px;width: 30px;height: 30px;border-radius: 50%;overflow: hidden;">
                            <a style="color:white;width:100%;height:100%;object-fit: cover;font-size:10px"><?= mb_substr($member_iam['mem_name'], 0, 3, "utf-8") ?></a>
                        </div>
                    <?  } ?>
                    <div style="padding:5px;margin-left:5px">
                        <?
                        if ($_GET['req_provide'] != 'Y') {
                            echo $member_iam['mem_id'] . "/" . $cur_card['card_name'];
                        } else {
                            echo $member_iam['mem_id'] . "/" . $member_iam['mem_name'];
                        }
                        ?>
                    </div>
                </div>
                <div style="padding:5px;margin-right:10px" id="contents_date"><?= date("Y-m-d") ?></div>
            </div>
            <? if ($_GET['req_provide'] != 'Y') { ?>
                <div class="modal-header" style="display:flex;justify-content: space-between;border:none">
                    <button type="button" class="btn btn-active" id="btn_add_one_cont" style="margin-bottom:10px; margin-top:10px;" onclick="click_one_content_add()">한개등록</button>
                    <button type="button" class="btn btn-default" id="btn_add_multi_cont" style="margin-bottom:10px;margin-top:10px;" onclick="click_multi_contents_add();">다수등록</button>
                    <button type="button" class="btn btn-default" id="btn_add_web_cont" style="margin-bottom:10px; margin-top:10px;" onclick="click_web_content_add()">자동등록</button>
                    <button type="button" class="btn btn-default" id="btn_add_shop_cont" style="margin-bottom:10px; margin-top:10px;" onclick="click_shop_content_add()">상품등록</button>
                </div>
                <? if ($video_upload_status == 'Y') { ?>
                    <div id="cont_media_type_box" style="float:left;margin-left:20px;margin-top:5px">
                        <label class="step_switch" id="cont_media_slide">
                            <input type="checkbox" id="cont_media_type" value="" style="display: none">
                            <span class="slider round" id="cont_media_slide"></span>
                        </label>
                        <p>이미지/영상</p>
                    </div>
                <? } ?>
                <button onclick="show_chat('<?= $member_iam['gpt_chat_api_key'] ?>')" class="chat_btn" style="margin-top:5px">+ AI와 대화하기</button>
            <? } else { ?>
                <div class="modal-header" style="justify-content: space-between;border:none;">
                    <h4 style="margin-bottom:5px;border-left:5px solid red;">공급사정보</h4>
                    <form method="post" id="req_provider_form" name="req_provider_form" action="/iam/ajax/product_mng.php" enctype="multipart/form-data">
                        <input type="hidden" id="mode" name="mode" value="req_provider">
                        <input type="hidden" id="gwc_worker_state" name="gwc_worker_state" value="<?= $member_iam['gwc_worker_state'] ? '1' : '0' ?>">
                        <div style="display:flex;margin-top:10px;">
                            공급사명:<input type="text" name="provider_name" id="provider_name" value="<?= $member_iam['gwc_provider_name'] ?>" style="width: 200px;height: 15px;padding: 10px;margin-left: 45px;">
                        </div>
                        <div style="display:<?= $member_iam['gwc_worker_state'] ? 'flex' : 'none' ?>;margin-top:10px;" id="worker_no_side">
                            사업자등록번호:<input type="text" name="worker_no" id="worker_no" value="<?= $member_iam['gwc_worker_no'] ?>" style="width: 200px;height: 15px;padding: 10px;margin-left: 6px;">
                        </div>
                        <div style="display:<?= $member_iam['gwc_worker_state'] ? 'flex' : 'none' ?>;margin-top:10px;" id="worker_img_side">
                            사업자등록증:<input type="file" name="worker_img" id="worker_img" value="<?= $member_iam['gwc_worker_img'] ?>" style="width: 200px;margin-left: 20px;">
                        </div>
                        <? if ($member_iam['gwc_worker_img']) { ?>
                            <img src="<?= "https://www.kiam.kr" . $member_iam['gwc_worker_img'] ?>" style="width:80px;margin-left:100px;">
                        <? } ?>
                        <div style="margin-top: 10px;width: 300px;text-align: left;">
                            <input type="checkbox" name="gwc_worker_state_" id="gwc_worker_state_" <?= $member_iam['gwc_worker_state'] ? 'checked' : '' ?> style="vertical-align: text-top;margin-left:100px;" onclick="gwc_worker()"><span style="margin-left:7px;">사업자</span>
                            <a href="javascript:save_req_provider();" id="save_req_provider_side" style="background-color: black;color: white;padding: 5px;border-radius: 7px;margin: 5px;cursor: pointer;float:right;">저장</a>
                        </div>
                    </form>
                </div>
                <h4 style="margin:5px 0px 5px 17px;border-left:5px solid red;">상품정보</h4>
            <? } ?>
            <?
            if ($member_iam['gwc_leb'] >= 1 && $member_iam['gwc_state'] == "1") {
            ?>
                <input type="hidden" name="gwc_con_state" id="gwc_con_state" value="0">
            <? } ?>
            <div class="modal-contents_title one_cont" style="margin:5px 15px">
                <input type="text" id="contents_title" placeholder="<?= $MENU['IAM_CONTENTS']['CONTS6']; ?>" style="width:100%;text-align:left;font-size:13px;font-weight:500;height:40px;margin-top:10px">
            </div>
            <div class="modal-contents_desc one_cont" style="margin:5px 15px">
                <textarea name="contents_desc" placeholder="<?= $MENU['IAM_CONTENTS']['CONTS14']; ?>" id="contents_desc" style="min-height:150px;overflow:auto;font-size:13px;margin-top:10px"></textarea>
            </div>
            <? if ($is_artist) { ?>
                <div class="service" style="margin:0px 10px">
                    <div style="display: flex;justify-content: space-evenly;">
                        <div><input type="radio" name="art_type" value="11"><label style="margin: 5px 1px;">회화</label></div>
                        <div><input type="radio" name="art_type" value="12"><label style="margin: 5px 1px;">판화</label></div>
                        <div><input type="radio" name="art_type" value="13"><label style="margin: 5px 1px;">조형</label></div>
                        <div><input type="radio" name="art_type" value="14"><label style="margin: 5px 1px;">사진</label></div>
                        <div><input type="radio" name="art_type" value="15"><label style="margin: 5px 1px;">AI아트</label></div>
                        <div><input type="radio" name="art_type" value="16"><label style="margin: 5px 1px;">기타</label></div>
                        <div><input type="radio" name="art_type" value="0"><label style="margin: 5px 1px;">비작품</label></div>
                    </div>
                </div>
            <? } ?>
            <div class="modal-body">
                <table class='table'>
                    <input type="hidden" id="post_type" placeholder="" />
                    <input type="hidden" value="" id="card_short_url" />
                    <input type="hidden" id="contents_order" />
                    <input type="hidden" id="contents_add_multi" value='0' />
                    <input type="hidden" id="contents_group" value='<?= $gkind ?>' />
                    <input type="hidden" id="contents_type" value='' />
                    <input type="hidden" id="new_open_url" value='<?= $new_open_url ?>' />
                    <div class="one_cont">
                        <div style="padding:0px;display:flex">
                            <button type="button" class="btn btn-cont-inactive" style="width:50%;border-radius:0px;" id="cont_modal_btn_image" onclick="click_cont_image();">파일로 이미지등록</button>
                            <button type="button" class="btn btn-cont-inactive" style="width:50%;border-radius:0px;margin-left:0px" id="cont_modal_btn_web" onclick="click_cont_web();">주소로 이미지등록</button>
                            <button type="button" class="btn btn-cont-inactive" style="display:none;width:50%;border-radius:0px;" id="cont_modal_btn_video" onclick="click_cont_video();">파일로 동영상등록</button>
                            <button type="button" class="btn btn-cont-inactive" style="display:none;width:50%;border-radius:0px;margin-left:0px" id="cont_modal_btn_web_video" onclick="click_cont_web_video();">주소로 동영상등록</button>
                        </div>
                    </div>
                    <? if ($_GET['req_provide'] != 'Y') { ?>
                        <div class="web_cont">
                            <label style="font-size:15px"><?= $MENU['IAM_CONTENTS']['TITLE']; ?></label>
                        </div>
                    <? } ?>
                    <tr class="one_cont multi_cont">
                        <td style="border:none">
                            <div id="cont_modal_image_type_file">
                                <div style="display:flex">
                                    <button type="button" class="btn btn-default" style="width:100%;border-radius:0px;background-color: #dddddd;" onclick="$('#cont_modal_image').click();">+이미지 파일추가</button>
                                </div>
                                <input type="file" name="cont_modal_image" id="cont_modal_image" class="input" accept=".jpg,.jpeg,.png,.gif" multiple style="display:none">
                            </div>
                            <div class="develop" id="cont_modal_image_type_link">
                                <div style="display:flex">
                                    <input type="text" id="cont_modal_image_url" placeholder='사이트에서 찾은 이미지에서 "이미지 주소 복사" 후 주소를 입력하세요.' style="width:100%;font-size:12px">
                                    <input type="button" value="추가" onclick="add_one_web_image()">
                                </div>
                            </div>
                            <div class="develop" id="cont_modal_video_type_file">
                                <div style="display:flex">
                                    <button type="button" class="btn btn-default" style="width:100%;border-radius:0px;background-color: #dddddd;" onclick="$('#cont_modal_video').click();">+동영상 파일추가</button>
                                </div>
                                <input type="file" name="cont_modal_video" id="cont_modal_video" class="input" accept="video/*" style="display:none">
                            </div>
                            <div class="develop" id="cont_modal_video_type_link">
                                <div style="display:flex">
                                    <input type="text" id="cont_modal_video_url" placeholder='사이트에서 찾은 동영상에서 "동영상 주소 복사" 후 주소를 입력하세요.' style="width:100%;font-size:12px">
                                </div>
                            </div>
                            <div id="preview_img" style="width: 100%;height: auto;display:flex;flex-wrap: wrap;align-content: flex-start;">
                            </div>
                            <div class="img-rounded" id="preview_video" style="border:1px solid #ddd;position:relative;margin:10px auto;display:none">
                                <img src='/iam/img/menu/icon_main_close.png' style='position:absolute;top:-5px;right:-5px;cursor:pointer;width:24px;z-index:10' id="clear_preview_cont_video">
                                <video src='' type='video/mp4' autoplay muted loop style='width:100%;' id='cont_video_preview'></video>
                            </div>
                            <label style="font-size:10px;color:grey;font-weight:100" class="develop" id="one-cont-image-desc">*하나의 콘텐츠에 여러장의 이미지가 등록됩니다.<br>예:)10개 이미지 등록해서 한개 콘텐츠에 10개의 이미지가 노출</label>
                            <label style="font-size:10px;color:grey;font-weight:100" class="develop" id="multi-cont-image-desc">*등록한 이미지 개수만큼 콘텐츠가 등록됩니다.<br>예:)10개 이미지 등록시 10개 콘텐츠가 생성<br>*내용추가가 필요시 이미지 등록 후 편집창에서 하세요.</label>
                            <? if ($_GET['req_provide'] == 'Y') { ?>
                                <p id="img_set_intro" style="padding-top: 10px;font-weight: bold;color: red;">첫번째 이미지는 반드시 정사각형 사이즈여야 합니다.</p>
                            <? } ?>
                        </td>
                    </tr>
                    <? if ($_GET['req_provide'] != 'Y') { ?>
                        <tr class="web_cont">
                            <td style="border:0px">
                                <div id="web_link_list">
                                    <div>
                                        <input type="text" id="web_url" name="web_url" placeholder="웹주소를 입력하세요.">
                                    </div>
                                </div>
                                <div style="display: flex;flex-wrap: wrap;margin-top: 5px">
                                    <label style="font-size:10px;color:grey;font-weight:100" id="contents_box_more">*<?= mb_substr($contents_box_alarm, 0, 27, "utf-8") ?></label>
                                    <label style="font-size:10px;color:grey;font-weight:100;display: none" id="contents_box_all">*<?= $contents_box_alarm ?></label>
                                    <label style="font-size:10px;color:grey;font-weight:100" id="contents_box_alarm_more" data-more="close">
                                        <span class="popbutton pop_view pop_right" style="border: 2px solid grey;padding: 0px 5px;border-radius: 10px;font-size: 10px;cursor: pointer;">?</span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr class="web_cont">
                            <td style="text-align:center;border:0px;padding-top: 25px;">
                                <a href="javascript:add_web_link();">
                                    <p style="border:1px solid #ddd;padding:10px;font-size:14px">+웹 주소 추가</p>
                                </a>
                            </td>
                        </tr>
                    <? } ?>
                    <tr id="contents_link">
                        <td style="border:none">
                            <input type="text" id="contents_url" placeholder="<?= $MENU['IAM_CONTENTS']['CONTS9']; ?>" style="width:100%;font-size:12px"><br>
                            <div style="margin-top: 10px;">
                                <input type="radio" name="contents_open" id="inline_open" value="1"><label for="inline_open" style="margin: 15px 10px;">내부열기</label>
                                <input type="radio" name="contents_open" id="new_open" value="2" checked><label for="new_open" style="margin: 15px 10px;">새창열기</label><br>
                            </div>
                        </td>
                    </tr>
                    <? if ($_GET['req_provide'] != 'Y') { ?>
                        <tr class="youtube">
                            <td>
                                <input type="text" id="contents_iframe" placeholder="<?= $MENU['IAM_CONTENTS']['CONTS10']; ?>">
                            </td>
                        </tr>
                    <? }
                    if (($member_iam['gwc_leb'] >= 1 && $member_iam['gwc_state'] == "1") || $_SESSION['iam_member_id'] == 'iamstore' || $_GET['req_provide'] == 'Y') {
                    ?>
                        <tr class="service" id="prod_cms">
                            <td style="border:none">
                                <div class="cont_edit_title_div">
                                    <p class="cont_edit_title">상품코드</p><input type="text" id="product_code" placeholder="상품의 코드정보를 입력해주세요."><a style="background-color: black;color: white;padding: 5px;border-radius: 7px;margin: 5px;cursor:pointer;" href="javascript:automake_prod_code()">자동생성</a>
                                </div><br>
                                <div class="cont_edit_title_div">
                                    <p class="cont_edit_title">상품모델</p><input type="text" id="product_model_name" placeholder="상품모델명을 입력하세요(중복업로드 불가)"><a style="background-color: black;color: white;padding: 5px;border-radius: 7px;margin: 5px;cursor:pointer;" href="javascript:automake_prod_model()">자동생성</a><a id="confirm_model" style="background-color: black;color: white;padding: 5px;border-radius: 7px;margin: 5px;cursor:pointer;" href="javascript:check_dup_model_name()">중복확인</a><a style="border: 1px solid black;padding:0px 5px;cursor: pointer;border-radius: 10px;margin:10px 3px; height: 20px;" href="javascript:show_gwc_model_info()">?</a>
                                </div><br>
                                <div class="cont_edit_title_div">
                                    <p class="cont_edit_title">상품분류</p><input type="text" id="product_seperate" placeholder="상품분류정보 입력하세요.(예시) 의류>여성의류>가디건>조끼"><a style="border: 1px solid black;padding:0px 5px;cursor: pointer;border-radius: 10px;margin:10px 3px; height: 20px;" href="javascript:show_gwc_seperate_info()">?</a>
                                </div>
                            </td>
                        </tr>
                    <? } ?>
                    <tr class="service nogallery">
                        <td style="border:none;">
                            <div class="cont_edit_title_div">
                                <p class="cont_edit_title">시중가격</p><input type="number" id="contents_price" placeholder="해당상품의 시중가를 입력하세요." style="width:77%;font-size:13px">
                            </div>
                        </td>
                    </tr>
                    <tr class="service nogallery">
                        <td style="border:none">
                            <div class="cont_edit_title_div">
                                <p class="cont_edit_title" id="min_price">할인비율</p>
                                <input type="number" id="contents_percent" placeholder="" style="width:17%;font-size:13px"><span style="line-height: 40px;">%</span>
                                <input type="number" id="contents_sell_price" placeholder="할인비율만큼 결제가격이 표기됩니다." style="width:50%;font-size:13px;margin-left:10px;">
                            </div>
                        </td>
                    </tr>
                    <tr class="service gallery">
                        <td style="border:none">
                            <div>
                                <label style="font-size:15px">원본 작품 사이즈와 가격</label>
                            </div>
                        </td>
                    </tr>
                    <tr class="service gallery">
                        <td style="border:none">
                            <div class="cont_edit_title_div">
                                <p class="cont_edit_title" style="width: 20%;text-align:center;background:#7f7f7f;color:white">규격</p>
                                <p class="cont_edit_title" style="margin-left:10px;width: 30%;text-align:center;background:#7f7f7f;color:white">사이즈</p>
                                <p class="cont_edit_title" style="margin-left:10px;width: 30%;text-align:center;background:#7f7f7f;color:white">가격</p>
                            </div>
                            <div class="cont_edit_title_div" style="margin-top: 5px;">
                                <input type="text" id="gallery_format" placeholder="A2" style="width: 20%;text-align:right">
                                <input type="text" id="gallery_size" placeholder="420X594" style="margin-left:10px;width: 30%;text-align:right">
                                <input type="number" id="gallery_price" placeholder="99,000,000" style="margin-left:10px;width:30%;text-align:right">
                                <span style="margin: 10px 0px 0px 10px;">원</span>
                            </div>
                        </td>
                    </tr>
                    <tr class="service gallery">
                        <td style="border:none">
                            <div>
                                <label style="font-size:15px">원본 작품 할인율</label>
                            </div>
                        </td>
                    </tr>
                    <tr class="service gallery">
                        <td style="border:none">
                            <div class="cont_edit_title_div">
                                <p style="padding: 11px 15px;border: 1px solid grey;background:#7f7f7f;color:white">할인</p>
                                <input type="number" id="gallery_discount" placeholder="" style="width:15%;margin-left:10px;margin-right:10px;font-size:13px"><span style="line-height: 40px;">%</span>
                                <input type="number" id="gallery_sell_price" placeholder="할인비율만큼 가격이 표기됩니다." style="width:50%;font-size:13px;margin-left:10px;">
                            </div>
                            <label style="font-size:15px;color:grey;font-weight:100">※원본 작품에는 보증서와 작품 싸인 필수. 액자와 배송비 별도</label>
                        </td>
                    </tr>
                    <tr class="service gallery">
                        <td style="border:none">
                            <div>
                                <label style="font-size:15px">Web 이미지 다운로드 가격</label>
                            </div>
                        </td>
                    </tr>
                    <tr class="service gallery">
                        <td style="border:none">
                            <div class="cont_edit_title_div">
                                <p style="padding: 11px 15px;border: 1px solid grey;background:#7f7f7f;color:white">가격</p>
                                <input type="number" id="gallery_download_price" placeholder="11,000" style="width:30%;font-size:13px;margin-left:10px;">
                                <span style="margin: 10px 0px 0px 10px;">원</span>
                            </div>
                        </td>
                    </tr>
                    <?
                    if (($member_iam['gwc_leb'] >= 1 && $member_iam['gwc_state'] == "1") || $_SESSION['iam_member_id'] == 'iamstore' || $_GET['req_provide'] == 'Y') {
                    ?>
                        <tr class="service" id="prod_provide">
                            <td style="border:none">
                                <div class="cont_edit_title_div">
                                    <p class="cont_edit_title">공급가격</p><input type="number" id="send_provide_price" placeholder="할인가에서 공급가를 뺀 금액이 판매자의 수수료가 됩니다(세전)" style="width:77%;font-size:12px">
                                </div>
                            </td>
                        </tr>
                        <tr class="service" id="prod_manufact">
                            <td style="border:none">
                                <div class="cont_edit_title_div">
                                    <p class="cont_edit_title">생산원가</p><input type="number" id="prod_manufact_price" placeholder="온리원에서 배송처에 입금하는 금액(할인가의 10%이상)" style="width:77%;font-size:12px">
                                </div>
                            </td>
                        </tr>
                        <tr class="service" id="prod_salary">
                            <td style="border:none">
                                <div class="cont_edit_title_div">
                                    <p class="cont_edit_title">배송요금</p><input type="number" id="send_salary_price" placeholder="배송료를 입력하세요." style="width:77%;font-size:12px">
                                </div>
                                <p style="margin-top:10px;color:blue;font-size:10px">결제가격 : 최저가격에 배송요금이 합산됩니다.</p>
                            </td>
                        </tr>
                    <? } ?>
                    <? if ($_GET['req_provide'] != 'Y') { ?>
                        <? if (!strstr($Gn_mem_row['special_type'], "1") && !strstr($Gn_mem_row['special_type'], "6")) { ?>
                            <tr class="service">
                                <td class="bold" style="text-align:center;border:none">
                                    <div style="display: flex;justify-content: space-evenly;">
                                        <div style="padding:5px;font-size:12px;background:black;color:white;width:40%;cursor:pointer" onclick="selling_request(1)"> 판매자신청</div>
                                        <div style="padding:5px;font-size:12px;background:black;color:white;width:40%;cursor:pointer" onclick="selling_request(6)"> 작가신청</div>
                                    </div>
                                    <div style="font-size:12px;" id="post_content_selling_request" data-status="N"></div>
                                </td>
                            </tr>
                        <? } else { ?>
                            <div style="font-size:12px;display:none;" id="post_content_selling_request" data-status="Y"></div>
                    <? }
                    } ?>
                    <tr class="one_cont service" id="edit_busitime_side" hidden>
                        <td style="display: flex;">
                            <input type="hidden" id="cur_con_idx_time">
                            <button type="button" class="btn btn-primary" id="edit_busitime_btn" onclick="edit_busitime()">영업시간 수정하기</button>
                            <p style="margin-top:10px; margin-left:20px;color:blue;">프로필 수정페이지로 이동합니다.</p>
                        </td>
                    </tr>
                </table>
                <table class='table table-bordered'>
                    <? if ($_GET['req_provide'] != 'Y') { ?>
                        <tr>
                            <td colspan="3">
                                <div style="display:flex">
                                    <div style="display:flex">
                                        <input type="checkbox" id="edit_content_radio_spec" class="radio" style="width:20px">
                                        <label style="font-size:14px;padding:15px 10px;">고급설정</label>
                                    </div>
                                    <? if ($jungo_market_view != "N") { ?>
                                        <div style="display:flex;margin-left:10px" id="market_radio">
                                            <input type="checkbox" id="edit_content_radio_market" class="radio" style="width:20px">
                                            <label style="font-size:14px">중고마켓등록</label>
                                            <label class="blink" style="font-size:14px;margin-left:10px;color:#b5b5b5;font-weight:100">※ 중고마켓등록 체크시 중고마켓 페이지에만 등록됩니다.</label>
                                        </div>
                                    <? } ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="hide_spec develop">
                            <td style="width:120px" style="font-size:13px;" title="<?= $MENU['IAM_CONTENTS']['TITLE1_TITLE']; ?>"><?= $MENU['IAM_CONTENTS']['TITLE1']; ?></td>
                            <td colspan="2" style="font-size:13px;">
                                <div class="attr-value" style="display:flex;flex-wrap: wrap;">
                                    <script type="text/javascript">
                                        $card_selected = [];
                                    </script>
                                    <?
                                    if ($cur_win != "group-con")
                                        $sql5 = "select card_short_url,card_title from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' order by req_data asc";
                                    else
                                        $sql5 = "select card_short_url,card_title from Gn_Iam_Name_Card where group_id = '$gkind' order by req_data asc";
                                    $result5 = mysql_query($sql5);
                                    $i = 0;
                                    while ($row5 = mysql_fetch_array($result5)) {
                                    ?>
                                        <div title="<?= $row5['card_title'] ?>">
                                            <input type="checkbox" onchange="onChangeCardCheck(this)" class="my_info_check my_info_<?= $row5['card_short_url'] ?>" value="<?= $row5['card_short_url'] ?>">
                                            <? if (!$_SESSION['iam_member_subadmin_id'] && !$pay_status) {
                                                echo ($i + 2);
                                            } else {
                                                echo ($i + 1);
                                            }
                                            echo $MENU['IAM_CONTENTS']['CONTS3'] . "(" . $row5['card_title'] . ")"; ?>
                                        </div>
                                        <?
                                        // 오픈한 카드와 같으면
                                        if ($request_short_url == $row5['card_short_url'] || $group_card_url == $row5['card_short_url']) {
                                        ?>
                                            <script type="text/javascript">
                                                $card_selected.push("<?= $row5['card_short_url'] ?>");
                                            </script>
                                    <? }
                                        $i++;
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="hide_spec develop">
                            <td style="font-size:13px;" title="<?= $MENU['IAM_CONTENTS']['TITLE2_TITLE']; ?>"><?= $MENU['IAM_CONTENTS']['TITLE2']; ?></td>
                            <td colspan="2" style="font-size:13px;">
                                <input type="checkbox" id="contents_westory_display" onchange="reset_westory_tr()" checked><?= $MENU['IAM_CONTENTS']['CONTS2']; ?>
                            </td>
                        </tr>
                        <tr id="westory_tr" class="hide_spec develop">
                            <td style="font-size:13px;" title="<?= $MENU['IAM_CONTENTS']['TITLE3_TITLE']; ?>"><?= $MENU['IAM_CONTENTS']['TITLE3']; ?></td>
                            <td colspan="2" style="font-size:13px;">
                                <div class="attr-value" style="display: flex">
                                    <?
                                    if ($cur_win != "group-con")
                                        $sql5 = "select card_short_url,phone_display from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' order by req_data asc";
                                    else
                                        $sql5 = "select card_short_url,phone_display from Gn_Iam_Name_Card where group_id = '$gkind' order by req_data asc";
                                    $result5 = mysql_query($sql5);
                                    $i = 0;
                                    while ($row5 = mysql_fetch_array($result5)) {
                                    ?>
                                        <div class="we_story_div we_story_div_<?= $row5['card_short_url'] ?>">
                                            <input type="radio" id="westory_card_url" name="westory_card_url" class="we_story_radio we_story_<?= $row5['card_short_url'] ?>" value="<?= $row5['card_short_url'] ?>" <? if ($row5['phone_display'] == "N") {
                                                                                                                                                                                                                        echo "onclick='locked_card_click();'";
                                                                                                                                                                                                                    } ?>>
                                            <span <? if ($row5['phone_display'] == "N") {
                                                        echo "class='locked' title='비공개카드'";
                                                    } ?>>
                                                <? if (!$pay_status && !$_SESSION['iam_member_subadmin_id']) {
                                                    echo ($i + 2);
                                                } else {
                                                    echo ($i + 1);
                                                } ?><?= $MENU['IAM_CONTENTS']['CONTS1']; ?>
                                            </span>
                                        </div>
                                    <?
                                        $i++;
                                    } ?>
                                </div>
                                <script type="text/javascript">
                                    arrayToInput();

                                    function onChangeCardCheck(cb) {
                                        if (cb.checked) {
                                            if ($card_selected.indexOf(cb.value) > -1) {
                                                //In the array!
                                            } else {
                                                $card_selected.push(cb.value);
                                            }
                                        } else {
                                            if ($card_selected.indexOf(cb.value) > -1) {
                                                $card_selected.splice($card_selected.indexOf(cb.value), 1);
                                            }
                                            if ($("input[name=westory_card_url]:checked").val() == cb.value) {
                                                $("input[name=westory_card_url]:checked").prop("checked", false);
                                            }
                                        }
                                        arrayToInput();
                                    }

                                    function arrayToInput() {
                                        $("#card_short_url").val("");
                                        $(".we_story_div").css("display", "none");
                                        for (var i = 0; i < $card_selected.length; i++) {
                                            if (i == 0) {
                                                $("#card_short_url").val($card_selected[i]);
                                            } else {
                                                $("#card_short_url").val($("#card_short_url").val() + "," + $card_selected[i]);
                                            }
                                            $(".we_story_div_" + $card_selected[i]).css("display", "block");
                                        }
                                        if (!$("input[name=westory_card_url]:checked").val()) {
                                            $("input[name=westory_card_url]").each(function() {
                                                if ($card_selected.indexOf($(this).val()) > -1) {
                                                    $(this).prop("checked", true);
                                                    return;
                                                }
                                            });
                                        }
                                    }
                                </script>
                            </td>
                        </tr>
                        <tr class="hide_spec develop">
                            <td style="font-size:13px;"><?= "랜딩 모드"; ?> </td>
                            <td colspan="2" style="font-size:13px;">
                                <input type="checkbox" id="landing_mode" checked><?= "다수 이미지 등록시 전체가 세로로 펼쳐집니다(랜딩페이지형)." ?>
                            </td>
                        </tr>
                        <tr class="hide_spec develop">
                            <td style="font-size:13px;"><?= "SNS 업로드"; ?> </td>
                            <td colspan="2" style="font-size:13px;">
                                <input type="checkbox" id="fb_upload_check"><?= "페이스북에 자동 업로드" ?>
                            </td>
                        </tr>
                        <tr class="hide_spec develop">
                            <td style="font-size:13px;"><?= "제외 키워드"; ?> </td>
                            <td colspan="2" style="font-size:13px;">
                                <textarea name="except_keyword" placeholder="제외 키워드를 입력해주세요.(입력한 키워드가 타인의 콘텐츠의 제목/설명/관심키워드와 매칭되면 그 사람에게 본 콘텐츠가 노출되지 않습니다.)" id="except_keyword"></textarea>
                            </td>
                        </tr>
                    <? } else { ?>
                        <tr class="hide_spec">
                            <td style="width:120px" style="font-size:13px;" title="카테고리선택">카테고리선택</td>
                            <td colspan="2" style="font-size:13px;">
                                <div class="attr-value" style="display:flex;flex-wrap: wrap;">
                                    <?
                                    $sql5 = "select card_short_url,card_title from Gn_Iam_Name_Card where mem_id = 'iamstore' and idx not in(934328, 2477701, 1274691, 1268514) order by req_data asc";
                                    $result5 = mysql_query($sql5);
                                    $i = 0;
                                    while ($row5 = mysql_fetch_array($result5)) {
                                    ?>
                                        <input type="radio" name="gwc_card_url" class="my_info_check my_info_<?= $row5['card_short_url'] ?>" value="<?= $row5['card_short_url'] ?>">
                                        <? //if(!$_SESSION['iam_member_subadmin_id'] && !$pay_status){
                                        //echo($i+2);
                                        //}else{
                                        echo ($i + 1);
                                        //}
                                        echo $MENU['IAM_CONTENTS']['CONTS3'] . "(" . $row5['card_title'] . ")"; ?>
                                    <? $i++;
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <div class="modal-header" style="justify-content: space-between;border:none;">
                            <h4 style="margin-bottom:5px;border-left:5px solid red;">배송정보</h4>
                            <input type="hidden" id="check_deliver_id_state" name="check_deliver_id_state" value="N">
                            <input type="hidden" id="deliver_id_code" name="deliver_id_code" value="">
                            <input type="checkbox" id="same_gonggupsa" name="same_gonggupsa" onclick="self_deliver()" style="vertical-align: text-top;margin-right:5px;">공급사와 동일
                            <div style="display:flex;margin-top:10px;">
                                아이디:<input type="text" name="deliver_id" id="deliver_id" value="" style="width: 200px;height: 15px;padding: 10px;margin-left: 20px;"><a href="javascript:check_deliver_id();" id="check_deliver_id" style="background-color: #99cc00;color: white;padding: 2px 5px;margin: -1px 5px;cursor: pointer;">확인</a>
                            </div>
                            <div style="display:flex;margin-top:10px;">
                                이름:<input type="text" name="deliver_name" id="deliver_name" value="" style="width: 200px;height: 15px;padding: 10px;margin-left: 33px;" readonly>
                            </div>
                            <div style="display:flex;margin-top:10px;">
                                핸드폰:<input type="text" name="deliver_phone" id="deliver_phone" value="" style="width: 200px;height: 15px;padding: 10px;margin-left: 20px;" readonly>
                            </div>
                            <div style="display:flex;margin-top:10px;">
                                주소:<input type="text" name="deliver_addr" id="deliver_addr" value="" style="width: 200px;height: 15px;padding: 10px;margin-left: 33px;" readonly>
                            </div>
                            <div style="display:flex;margin-top:10px;">
                                이메일:<input type="text" name="deliver_email" id="deliver_email" value="" style="width: 200px;height: 15px;padding: 10px;margin-left: 20px;" readonly>
                            </div>
                            <div style="display:flex;margin-top:10px;">
                                입금계좌:<input type="text" name="deliver_bank" id="deliver_bank" value="" style="width: 70px;height: 15px;padding: 10px;margin-left: 8px;" readonly>
                                <input type="text" name="deliver_owner" id="deliver_owner" value="" style="width: 70px;height: 15px;padding: 10px;" readonly>
                                <input type="text" name="deliver_account" id="deliver_account" value="" style="width: 70px;height: 15px;padding: 10px;" readonly>
                            </div>
                        </div>
                    <? } ?>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-left" id="cont_modal_btn_cancel" data-dismiss="modal"><?= $MENU['IAM_CONTENTS']['BTN_1']; ?></button>
                <button type="button" class="btn btn-active btn-right" id="cont_modal_btn_ok" onclick="post_contents('<?= $Gn_mem_row['service_type'] ?>')"><?= $MENU['IAM_CONTENTS']['BTN_2']; ?></button>
            </div>
        </div>
    </div>
</div>
<!--GPT chat modal-->
<div id="gpt_chat_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="margin: 30px auto;width: 100%;max-width:700px">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label id="gwc_con_name_modal">콘텐츠 창작AI 알지(ALJI)</label>
            </div>
            <div class="modal-body" style="background-color:#e5e3e3;">
                <div class="container" style="text-align: center;">
                    <p><img src="/iam/img/arji_intro_title.png" style="width: 22px;margin-right: 3px;">"알지(ALJI)" 인공지능에게 무엇이든 물어보세요.<br>구체적으로 질문할수록 "알지 AI" 답변이 정교해집니다.</p>
                    <p id="gpt_req_list_title" hidden>질문답변목록</p>
                    <ul id="answer_side" hidden>
                        <a class="copy_msg" href="javascript:copy_msg()"><img src="/iam/img/gpt_res_copy.png" style="height:20px;"></a>
                    </ul>
                    <ul id="answer_side1">
                        <?
                        $gpt_qu = get_search_key('gpt_question_example');
                        $gpt_an = get_search_key('gpt_answer_example');
                        $gpt_qu_arr = explode("||", $gpt_qu);
                        $gpt_an_arr = explode("||", $gpt_an);
                        for ($i = 0; $i < count($gpt_qu_arr); $i++) {
                        ?>
                            <li class="article-title" id="q<?= $i ?>" onclick="show('<?= $i ?>')"><img src="/iam/img/chat_Q.png" style="width:30px;margin-right: 10px;"><span class="chat_title"><?= htmlspecialchars_decode($gpt_qu_arr[$i]) ?></span><i id="down<?= $i ?>" class="fa fa-angle-down" style="font-size: 20px;font-weight: bold;margin-left: 10px;"></i><i id="up<?= $i ?>" class="fa fa-angle-up" style="font-size: 20px;font-weight: bold;margin-left: 10px;display:none;"></i></li>
                            <li class="article-content hided" id="a<?= $i ?>"><img src="/iam/img/chat_A.png" style="width:30px;"><span class="chat_answer" style="margin-left: 35px;"><?= htmlspecialchars_decode($gpt_an_arr[$i]) ?></span></li>
                        <? } ?>
                    </ul>
                    <ul id="answer_side2" hidden>
                    </ul>
                    <div class="gpt_act">
                        <a class="history" href="javascript:show_req_history();"><img src="/iam/img/gpt_req_list.png" style="height: 25px;"></a>
                        <a class="newpane" href="javascript:show_new_chat();"><span style="font-size: 5px;">NEW</a>
                    </div>
                    <div class="search_keyword">
                        <input type="hidden" name="key" id="key" value="<?= $member_iam['gpt_chat_api_key'] ?>">
                        <textarea class="search_input" autocomplete="off" name="question" id="question" value="" title="질문을 입력하세요" placeholder="알지AI에게 질문해보세요" onclick="check_login('<?= $_SESSION['iam_member_id'] ?>')"></textarea>
                        <button type="button" onclick="send_post('<?= $_SESSION['iam_member_id'] ?>')" class="send_ask"><img src="/iam/img/send_ask.png" alt="전송"></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;background-color: #e5e3e3;padding:7px;">
                <button type="button" style="width:50%;background:#99cc00;color:white;padding:10px 0px" onclick="send_chat()">보내기</button>
            </div>
        </div>
    </div>
</div>
<!--튜토리얼 다중 콘텐츠 게시팝업-->
<div id="tutorial_contents_add_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document" style="width:95%;max-width:768px;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>한번에 여러 콘텐츠 만들기</label>
            </div>
            <div class="modal-body">
                <table class='table'>
                    <input type="hidden" value="" id="card_short_url">
                    <input type="hidden" id="contents_order" />
                    <input type="hidden" id="contents_add_multi" value='0' />
                    <tr class="common" id="image_file">
                        <td colspan="2" style="border:none">
                            <input type="file" name="contents_img_tuto" id="contents_img_tuto" class="input" accept=".jpg,.jpeg,.png,.gif" multiple>
                            <div id="preview_img_tuto" style="border: 1px solid #b5b5b5;width: 100%;height: auto;min-height: 200px;display:flex;flex-wrap: wrap;align-content: flex-start;">
                                <p id="desc_con_upload" style="opacity: 0.7;">휴대폰 또는 PC에 있는 이미지를 업로드하면 동시에 콘텐츠가 만들어집니다(10개까지)*</p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer" style="text-align: center">
                <button type="button" class="btn-default btn-left" id="btn_cancel" data-dismiss="modal" onclick="cancel_modal()"><?= $MENU['IAM_CONTENTS']['BTN_1']; ?></button>
                <button type="button" class="btn-active btn-right" id="btn_ok" onclick="post_contents_tuto('<?= $Gn_mem_row['service_type'] ?>')"><?= $MENU['IAM_CONTENTS']['BTN_2']; ?></button>
            </div>
        </div>
    </div>
</div>
<!--내 콘텐츠로 가져오기 팝업-->
<div id="contents_get_modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document" style="width:90%;max-width:768px;margin-left:auto;margin-right:auto">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>이 콘텐츠 내 카드에 가져오기</label>
            </div>
            <div class="modal-body">
                <table class='table table-bordered'>
                    <input type="hidden" value="" id="contents_get_card_url">
                    <input type="hidden" value="" id="contents_gwc_state">
                    <tr>
                        <td>
                            <div class="attr-value" style="display:flex;flex-wrap: wrap;">
                                <?
                                $sql5 = "select card_short_url,card_title from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' order by req_data asc";
                                $result5 = mysql_query($sql5);
                                $i = 0;
                                while ($row5 = mysql_fetch_array($result5)) {
                                ?>
                                    <div title="<?= $row5['card_title'] ?>">
                                        <input type="checkbox" onchange="onChangeCardGetCheck(this)" class="contents_get_check" value="<?= $row5['card_short_url'] ?>">
                                        <? echo (($i + 1) . $MENU['IAM_CONTENTS']['CONTS3'] . "(" . $row5['card_title'] . ")"); ?>
                                    </div>
                                <?
                                    $i++;
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer" style="text-align: center;display:flex;padding:0px">
                <button type="button" class="btn btn-default btn-left" style="width:50%;border-radius:0px;" id="contents_get_cancel" data-dismiss="modal" onclick="location.reload();">취소하기</button>
                <button type="button" class="btn btn-active btn-right" style="width:50%;margin-left:0px" id="contents_get_ok" onclick="get_shared_contents()">가져오기</button>
            </div>
        </div>
    </div>
</div>
<!--서비스형 콘텐츠 게시팝업-->
<div id="service_contents_popup" class="modal fade" tabindex="-1" role="dialog">
    <input type="hidden" id="content_idx" value="">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10%;width : 80%;max-width:600px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>판매자 신청하기</label>
            </div>
            <div class="modal-body">
                <div>
                    <div class="login_text">
                        서비스콘텐츠에서는 본사의 결제시스템으로 판매를 하므로 결제, 판매, 홍보에 대한 수수료 납부가 필요합니다.<br>
                        그래서 아래에 판매자신청 버튼을 클릭하셔야 서비스콘텐츠를 사용할수 있습니다.
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border:none;display: flex;text-align: center">
                <button type="button" class="btn btn-active btn-center btn-payment" onclick="hide_service_contents_popup();" id="service_contents_popup_ok">확인</button>
            </div>
        </div>
    </div>
</div>
<!--개별카드 만들기 클릭시 결제 팝업-->
<div id="payment_popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10%;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>
                    <? if ($domainData['service_type'] == 0)
                        echo "무료회원 아이엠 결제안내";
                    elseif ($domainData['service_type'] == 1)
                        echo "유료회원 아이엠 결제안내";
                    elseif ($domainData['service_type'] == 2)
                        echo "단체회원 아이엠 결제안내";
                    elseif ($domainData['service_type'] == 3)
                        echo "체험회원 아이엠 결제안내";
                    ?>
                </label>
            </div>
            <div class="modal-body">
                <div class="login_text" style="padding:0px 10px 0px 20px;">
                    <? if ($domainData['service_type'] == 0 || $domainData['service_type'] == 3) { ?>
                        <div style="text-indent: -10px;">1.본 플랫폼 무료회원은 아이엠 카드 <?= $domainData['iamcard_cnt'] ?>개까지 생성 가능합니다.</div>
                        <div style="text-indent: -10px;margin-top:10px">2.본 플랫폼의 1번 카드가 공유되며,홍보용 유용콘텐츠나 알림 수신이 동의처리 됩니다.</div>
                        <div style="text-indent: -10px;margin-top:10px">3.유료 결제로 전환시 2번은 미반영 됩니다.</div>
                    <? } elseif ($domainData['service_type'] == 1) { ?>
                        <div style="text-indent: -10px;">1.내 아이엠 만들기는 유료결제를 하셔야 가능합니다.</div>
                        <div style="text-indent: -10px;margin-top:10px">2.유료회원은 다중 콘텐츠 생성,데일리 문자발송,회원태그로 맞춤 콘텐츠 전송하기 등 유용한 기능을 사용할 수 있습니다.</div>
                    <? } elseif ($domainData['service_type'] == 2) { ?>
                        <div style="text-indent: -10px;">1.본 플랫폼 제공자는 플랫폼 개발 연구소와 협의 하에 회원들에게 11,000원 베이직 상품을 지원합니다.</div>
                        <div style="text-indent: -10px;margin-top:10px">2.내 아이엠 만들기는 서버 이용료로 매달 소액결제가 되야 가능합니다.</div>
                        <div style="text-indent: -10px;margin-top:10px">3.본 플랫폼 회원은 아이엠 카드 <?= $domainData['iamcard_cnt'] ?>개까지 생성 가능합니다.</div>
                        <div style="text-indent: -10px;margin-top:10px">4.본 플랫폼의 1번 카드가 공유되며,홍보용 유용콘텐츠나 알림 수신이 동의처리 됩니다.</div>
                    <? } ?>
                </div>
            </div>
            <div class="modal-footer" style="border:none;display: flex;text-align: center">
                <? if (!$is_pay_version) { ?>
                    <button type="button" class="btn btn-payment btn-left" onclick="location.href='/iam/pay.php'">결제 미리보기</button>
                    <button type="button" class="btn btn-active btn-right" onclick="hide_pay_popup(<?= $domainData['service_type'] ?>);">무료 이용하기</button>
                <? } else { ?>
                    <button type="button" class="btn btn-active btn-center" onclick="hide_pay_popup(<?= $domainData['service_type'] ?>);">무료 이용하기</button>
                <? } ?>
            </div>
        </div>
    </div>
</div>
<!--댓글 팝업-->
<!--div id="post_popup" class="modal fade" tabindex="-1" role="dialog">
    <input type = "hidden" id = "content_idx" value = "">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10%;width : 80%;max-width:600px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header" style="border:none;background-color: rgb(130,199,54);border-top-left-radius: 5px;border-top-right-radius: 5px;">
                <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">
                    댓글 안내 사항
                </div>
            </div>
            <div class="modal-body">
                <div>
                    <div class="login_text">
                        1.댓글 준수 사항<br>
                        &#8226;긍정적이고, 따뜻하며, 이해심 깊고, 배려있는 어투를 사용합니다.<br>
                        &#8226;댓글은 콘텐츠 내용과 관련이 있어야 하며, 명확하고, 간략해야 합니다.<br>
                        &#8226;다른 이들과 그들의 의견을 존중합니다.<br>
                        &#8226;인신공격, 비판적이거나 회의적인 글, 다른 개인이나 단체에 대한
적대적인 글을 게시해서는 안됩니다.<br>
                        &#8226;외설적이거나 저속한 말, 비속어 또는 비속어를 암시하는 말 및
줄임말, 욕설, 징종차별적인 비방을 해서는 안됩니다.<br>
                        &#8226;외부 웹사이트로 이동 링크는 게시하지 않습니다.<br>
                        &#8226;다른 출처에서 복사해 붙여넣기를 한다면, 해당 출처를 명시해야 합니다.<br>
                        &#8226;저작권 보호를 받는 자료는 게시할 수 없습니다.<br>
                        &#8226;상업적 홍보나 광고는 게시할 수 없습니다.<br><br>
                        2. 댓글 작성 지침<br>
                        &#8226;아이엠 로그인을 해주세요.<br>
                        &#8226;댓글은 300자 이내로 등록하실 수 있습니다.<br>
                        &#8226;최근 24시간 내 댓글 20개, 답글 수 40개로 제한됩니다.<br>
                        &#8226;콘텐츠 당 작성 가능한 댓글은 최대 3개입니다.<br>
                        &#8226;댓글/답글은 60초 내에 한 개만 등록할 수 있습니다.<br>
                    </div>
                </div>
            </div>
            <div class="modal-footer"  style="border:none;text-align: center">
                <div >
                    <a href="javascript:hide_post_popup()" class="btn login_signup" style="width: 90%;background-color: #ff0066">확인</a>
                </div>
            </div>
        </div>
    </div>
</div-->
<!--다중콘텐츠 박스 만들기 무료회원 팝업-->
<div id="mc_payment_popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10%;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>자동 다중 콘텐츠 생성기능!</label>
            </div>
            <div class="modal-body">
                <div>
                    <div class="login_text">
                        웹주소를 입력만 하면 자동으로 콘텐츠가 생성되며,<br>
                        여러 웹주소를 동시에 올려 바로 콘텐츠를 만들 수 있어<br>
                        빠르게 콘텐츠를 업로드하고 공유할 수 있습니다.<br>
                        유료결제시 사용가능합니다.
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border:none;display: flex;text-align: center">
                <? if ($is_pay_version) { ?>
                    <div style="width:50%;"><!--payment-->
                        <a href="/iam/pay.php" class="btn login_signup" style="width: 90%;background-color: #ff0066">결제 미리보기</a>
                    </div>
                <? } ?>
                <div style="width:50%;">
                    <a href="javascript:hide_pay_popup()" class="btn login_signup" id="mc_payment_popup_close" style="width: 90%;background-color: #bbbbbb">나중에 하기</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!--체험회원 첫 로긴시 팝업-->
<div id="exp_start_popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10%;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>체험회원 아이엠 사용안내</label>
            </div>
            <div class="modal-body">
                <div>
                    <div class="login_text">
                        1. 본 플랫폼 제공자는 플랫폼 개발 연구소와 협의 하에 회원들에게 베이직 상품을 <?= $domainData['service_price'] ?>일간 지원합니다.<br>
                        2. <?= $domainData['service_price'] ?>일 후에는 무료회원으로 전환되어 소속사의 1번 카드가 공유되고, 유료기능 사용에 제한이 생깁니다.<br>
                        그 때 다시 유료결제 진행하여 사용하시면 됩니다.<br>
                        3. 본 플랫폼 회원은 아이엠 카드 5개까지 생성 가능합니다.
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border:none;text-align: center">
                <a href="javascript:hide_exp_popup()" class="btn login_signup" id="payment_exp_start_popup_close" style="width: 90%;background-color: #ff0066">잘 확인했습니다</a>
            </div>
        </div>
    </div>
</div>
<!--체험회원 종료 1주일전 팝업-->
<div id="exp_mid_popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10%;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>무료회원 전환 안내</label>
            </div>
            <div class="modal-body">
                <div>
                    <div class="login_text">
                        1. <?= $domainData['service_price'] ?>일간 지원된 유료권한 종료일이 1주일 남았습니다.<br>
                        2. 1주일 후에는 무료회원으로 전환되어 소속사의 1번 카드가 공유되고, 유료기능 사용에 제한이 생깁니다.<br>
                        3. 유료결제 진행으로 편리하게 사용하시길 바랍니다.
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border:none;text-align: center">
                <a href="javascript:hide_exp_popup()" class="btn login_signup" id="payment_exp_mid_popup_close" style="width: 90%;background-color: #ff0066">잘 확인했습니다</a>
            </div>
        </div>
    </div>
</div>
<!--체험회원 종료 팝업-->
<div id="exp_limit_popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10%;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>무료회원 전환 안내</label>
            </div>
            <div class="modal-body">
                <div>
                    <div class="login_text">
                        1. <?= $domainData['service_price'] ?>일간 지원된 유료권한이 종료되었습니다.<br>
                        2. 고객님은 무료회원으로 전환되어 소속사의 1번 카드가 공유되고, 유료기능 사용에 제한이 생깁니다.<br>
                        3. 유료결제 진행으로 편리하게 사용하시길 바랍니다.
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border:none;display: flex;justify-content: center;text-align: center">
                <? if ($is_pay_version) { ?>
                    <div style="width:50%;"><!--payment-->
                        <a href="/iam/pay.php" class="btn login_signup" style="width: 90%;background-color: #ff0066">결제 미리보기</a>
                    </div>
                <? } ?>
                <div style="width:50%;">
                    <a href="javascript:hide_exp_popup()" class="btn login_signup" id="payment_exp_limit_popup_close" style="width: 90%;background-color: #bbbbbb">나중에 하기</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!--카드 만들기 팝업-->
<div id="create_card_popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="margin: 100px auto;max-width:300px;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>카드 만들기 선택</label>
            </div>
            <div style="text-align: center;height:45px;">
                <label style="font-size:10px;font-weight:lighter;margin-top:15px">쉽고 간편하게 나만의 멀티명함을 만들어보세요</label>
            </div>
            <div class="modal-body" style="padding:0px">
                <div>
                    <div class="login_text">
                        <div style="display: flex;border-bottom:1px solid #ddd;height:52px;">
                            <input type="radio" name="create_card_method" onclick="start_card_tutorial('first')" style="margin-left:15px">
                            <h5 style="margin-top:18px;">처음으로 카드 만들기</h5>
                        </div>
                        <div style="display: flex;border-bottom:1px solid #ddd;height:52px;">
                            <input type="radio" name="create_card_method" value="new" style="margin-left:15px" onclick="start_card_tutorial('new')">
                            <h5 style="margin-top:18px;">새 카드 만들기</h5>
                        </div>
                        <div style="display: flex;border-bottom:1px solid #ddd;height:52px;">
                            <input type="radio" name="create_card_method" value="link" style="margin-left:15px" onclick="start_card_tutorial('link')">
                            <h5 style="margin-top:18px;">링크 카드 만들기</h5>
                        </div>
                        <div id="create_card_info" style="display:none;padding: 20px 15px 0px 15px;">
                            <input type="text" id="create_link_card_title" placeholder="카드제목을 작성하세요." style="border: 1px solid grey;width:100%;border-radius: 5px;font-size:14px;">
                            <input type="text" id="create_link_card_link" placeholder="새 창으로 연결될 페이지 링크주소를 입력하세요." style="border: 1px solid grey;width:100%;border-radius: 5px;font-size:14px;margin-top: 8px;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="margin-top:52px">
                <button type="button" class="btn btn-default btn-left" onclick="hide_create_card_popup();">취소</button>
                <button type="button" class="btn btn-active btn-right" onclick="create_card_from_popup();">만들기</button>
            </div>
        </div>
    </div>
</div>
<!--그룹카드 만들기 팝업-->
<div id="create_group_card_popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10%;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>신규 카드만들기</label>
            </div>
            <div class="modal-body">
                <div>
                    <div class="login_text">
                        <input type="text" name="group_card_title" id="group_card_title" style="width:100%;border:1px solid;line-height:30px;font-size:20px" placeholder="카드제목을 입력하세요">
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border:none;text-align: center;padding-top:0px">
                <? if ($is_pay_version) { ?>
                    <div style="width:100%;"><!--payment-->
                        <a href="javascript:;;" onclick="check_group_card_info()" class="btn login_signup" style="width: 100%;background-color: #ff0066">결제하기</a>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
</div>
<!--그룹카드 편집 팝업-->
<div id="edit_group_card_popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10%;max-width:300px;margin-left:auto;margin-right:auto;">
        <div class="modal-content" style="border-radius:0px">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>카드 편집하기</label>
            </div>
            <div class="modal-body">
                <div>
                    <div class="login_text">
                        <input type="text" name="edit_group_card_title" id="edit_group_card_title" style="width:100%;border:1px solid;line-height:30px;font-size:14px" placeholder="카드제목을 입력하세요" value="<?= $group_card_row['card_title'] ?>">
                        <input type="hidden" id="edit_group_card_idx" name="edit_group_card_idx" value="<?= $group_card_url ?>">
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;padding:0px;display:flex">
                <a onclick="$('#edit_group_card_popup').modal('hide')" class="btn btn-link" style="padding:10px 0px;width:50%;border-radius:0px;margin-left:0px">취소</a>
                <? if ($is_pay_version) { ?>
                    <a onclick="edit_group_card_info()" class="btn btn-active" style="padding:10px 0px;width:50%;margin-left:0px">확인</a>
                <? } ?>
            </div>
        </div>
    </div>
</div>
<!--신규카드 만들기 팝업-->
<div id="create_new_card_popup" class="modal fade" tabindex="-1" role="dialog" style="overflow-y:auto">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10px;min-width:400px;width:50%;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>신규 카드만들기</label>
            </div>
            <div class="modal-body">
                <div>
                    <div class="login_text">
                        <div class="attr-value">
                            <a href="javascript:;;" onclick="show_card_list();" class="btn login_signup" style="width: 100%;background-color: #337ab7">다른 카드정보 가져오기
                            </a>
                        </div>
                        <div class="attr-value" id="create_card_list">
                            <?
                            $create_card_sql = "select card_short_url,card_title from Gn_Iam_Name_Card where group_id is NULL and mem_id = '{$_SESSION['iam_member_id']}' order by req_data asc";
                            $create_card_res = mysql_query($create_card_sql);
                            $i = 0;
                            while ($create_card_row = mysql_fetch_array($create_card_res)) {
                                $i++;
                            ?>
                                <input type="radio" id="create_card_url" name="create_card_url" value="<?= $create_card_row['card_short_url'] ?>">
                                <span>
                                    <? if ($create_card_row['card_title']) {
                                        echo ($create_card_row['card_title']);
                                    } else {
                                        echo ($i . "번");
                                    } ?>
                                </span>
                            <? } ?>
                        </div>
                        <div>
                            <img src="/iam/img/common/logo-2.png" id="create_card_logo" style="width:180px;height:50px">
                            <input type="file" class="input" name="create_card_logo_input" id="create_card_logo_input" accept=".jpg,.jpeg,.png,.gif,.svc">
                        </div>
                        <div style="margin-top:1px;">
                            <input type="text" id="create_card_title" class="card-input" style="width:100%" placeholder="카드제목">
                        </div>
                        <div style="margin-top:1px;">
                            <input type="text" id="create_card_name" class="card-input" style="width:100%" placeholder="성명">
                        </div>
                        <div style="margin-top:1px;">
                            <input type="text" id="create_card_company" class="card-input" style="width:100%" placeholder="소속/직책">
                        </div>
                        <div style="margin-top:1px;">
                            <input type="text" id="create_card_position" class="card-input" style="width:100%" placeholder="자기소개">
                        </div>
                        <div style="margin-top:1px;">
                            <input type="text" id="create_card_phone" class="J_card_number card-input" style="width:100%" placeholder="휴대폰번호">
                        </div>
                        <div style="margin-top:1px;">
                            <input type="text" id="create_card_address" class="J_card_address card-input" style="width:100%" placeholder="직장주소">
                        </div>
                        <div style="margin-top:1px;">
                            <input type="email" id="create_card_email" class="card-input" style="width:100%" placeholder="이메일주소">
                        </div>
                        <div style="margin-top:1px;vertical-align:middle;display: flex;">
                            <input type="text" id="create_card_online1_text" class="card-input" style="width:48%" placeholder="사이트이름">
                            <input type="text" id="create_card_online1" class="card-input" style="margin-left:5px;width:48%" placeholder="사이트주소">
                            <input type="checkbox" id="create_card_online1_check" class="radio" style="width:20px">
                        </div>
                        <div style="margin-top:1px;vertical-align:middle;display: flex;">
                            <input type="text" id="create_card_online2_text" class="card-input" style="width:48%" placeholder="사이트이름">
                            <input type="text" id="create_card_online2" class="card-input" style="margin-left:5px;width:48%" placeholder="사이트주소">
                            <input type="checkbox" id="create_card_online2_check" class="radio" style="width:20px">
                        </div>
                        <div style="margin-top:10px;vertical-align:middle;display: flex;">
                            <input type="checkbox" id="create_card_radio_spec" class="radio" style="width:20px">
                            <h4 style="margin-top: 17px;">고급설정</h4>
                        </div>
                        <div id="create_card_special" style="margin-bottom:0px;display:none">
                            <textarea id="create_card_keyword" class="card-input" name="create_card_keyword" style="text-align:left;width:100%;font-size: 12px;" placeholder="아이엠에서 나를 검색할수 있는 단어 (30개 이내)로 입력하고, 입력시 [,]으로 구분하세요. (예시 : 강사,마케터,변호사,대안학교,노래방, 공부방 등)"><?= $cur_card['card_keyword'] ?></textarea>
                            <input type="text" id="create_card_next_iam_link" class="J_card_address card-input" style="width:100%" placeholder="카드제목 클릭시 새창으로 열릴 다른 IAM주소 입력">
                        </div>
                        <input type="hidden" id="main_img1_link">
                        <input type="hidden" id="main_img2_link">
                        <input type="hidden" id="main_img3_link">
                        <input type="hidden" id="story_title1">
                        <input type="hidden" id="story_myinfo">
                        <input type="hidden" id="story_title2">
                        <input type="hidden" id="story_company">
                        <input type="hidden" id="story_title3">
                        <input type="hidden" id="story_career">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="cancel_create_card();" class="btn btn-default btn-left">취소</a>
                    <button type="button" onclick="create_new_card();" class="btn btn-active btn-right">등록</a>
            </div>
        </div>
    </div>
</div>
<!--custome notice 팝업-->
<div id="custom_notice_popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 100px;min-width:400px;width:50%;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label id="custom_notice_title"></label>
            </div>
            <div class="modal-body">
                <div style="margin-top:10px;" id="custom_notice_desc">
                </div>
            </div>
            <div class="modal-footer" style="border:none;display: flex;justify-content: center;text-align: center">

            </div>
        </div>
    </div>
</div>
<!--custome notice 팝업-->
<div id="mall_pay_popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 100px;min-width:400px;width:50%;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label id="mall_pay_text"></label>
            </div>
            <div class="modal-body" style="text-align: center">
                <div class="mall_iam" style="display:flex">
                    <input type="text" id="mall_iam_id" class="card-input" style="width:48%" placeholder="새 ID 입력">
                    <input type="password" id="mall_iam_pwd" class="card-input" style="width:48%" placeholder="비번 입력">
                </div>
                <div class="mall_card" style="display:flex">
                    <input type="text" id="mall_card_id" class="card-input" style="width:90%" placeholder="카드를 추가할 ID를 입력하세요">
                </div>
                <div class="mall_content" style="display:flex">
                    <input type="text" id="mall_content_id" class="card-input" style="width:48%" placeholder="기존 ID입력">
                    <select id="mall_content_card" style="border:1px solid #858585;width:48%;margin-left:2%" class="card-input">
                        <option value="" selected>카드번호</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="border:none;text-align: center">
                <a href="javascript:;;" class="btn login_signup" style="width: 100%;background-color: #ff3530;border-radius:0px 0px 6px 6px;" id="mall_pay_btn">결제하기</a>
            </div>
        </div>
    </div>
</div>
<!--그룹/게시물 만들기 팝업-->
<div id="create_group_popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10px;width:95%;max-width:768px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>유익과 행복을 나누는 커넥팅</label>
            </div>
            <div class="modal-body popup-bottom" style="background-color: #e5e5e5;overflow-y:auto">
                <div style="padding-top: 2px;" onclick="show_group_list()">
                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex">
                        <div style="border-radius: 50%;width: 50px;height: 50px;overflow: hidden;margin: 10px;">
                            <img src="/iam/img/main/icon-writing.png" style="width: 50px;height:50px;">
                        </div>
                        <div>
                            <label style="margin-left: 10px;margin-top: 10px;cursor:pointer;font-size:14px;">게시물 작성하기</label>
                            <p style="margin-left: 10px;margin-top: 10px;margin-bottom: 10px;font-size:12px;">
                                가입한 그룹에 게시해보세요.
                            </p>
                        </div>
                    </div>
                </div>
                <div id="create_group_notice3" style="padding-top: 2px;display:none" onclick="$('#create_group_popup').modal('hide');">
                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex">
                        <div style="border-radius: 50%;width: 50px;height: 50px;overflow: hidden;margin: 10px;">
                        </div>
                        <div>
                            <p style="margin-left: 10px;margin-top: 10px;margin-bottom: 10px;font-size:12px;">
                                참여 그룹이 있을 때 게시물 작성하기가 됩니다.<br>추천 그룹이나 찾아보기를 통해 관심가는 그룹에 참여해보세요.
                            </p>
                        </div>
                    </div>
                </div>
                <div style="padding-top: 2px;" onclick="create_iam_group()">
                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex">
                        <div style="border-radius: 50%;width: 50px;height: 50px;overflow: hidden;margin: 10px;">
                            <img src="/iam/img/main/icon-makegp.png" style="width: 50px;height:50px;">
                        </div>
                        <div>
                            <label style="margin-left: 10px;margin-top: 10px;cursor:pointer;font-size:14px;">그룹 만들기</label>
                            <p style="margin-left: 10px;margin-top: 10px;margin-bottom: 10px;font-size:12px;">
                                공개 또는 비공개 그룹을 만들어보세요.
                            </p>
                        </div>
                    </div>
                </div>
                <div id="create_group_notice1" style="padding-top: 2px;display:none" onclick="">
                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex">
                        <div style="border-radius: 50%;width: 50px;height: 50px;overflow: hidden;margin: 10px;">
                        </div>
                        <div>
                            <p style="margin-left: 10px;margin-top: 10px;margin-bottom: 10px;font-size:12px;">
                                독립도메인 구축한 분이 그룹 만들기가 가능합니다.<br>독립 도메인 구축하러 가기.
                            </p>
                        </div>
                    </div>
                </div>
                <div id="create_group_notice2" style="padding-top: 2px;display:none" onclick="">
                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display: flex">
                        <div style="border-radius: 50%;width: 50px;height: 50px;overflow: hidden;margin: 10px;">
                        </div>
                        <div>
                            <p style="margin-left: 10px;margin-top: 10px;margin-bottom: 10px;cursor:pointer;font-size:12px;">
                                이미 그룹을 만드셨습니다. 주제 키워드별 카드를 추가 생성해 보세요.<br>나의 그룹페이지로 가기.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--그룹 만들기 팝업-->
<div id="create_edit_group_popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10px;width:95%;max-width:768px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>그룹 만들기 및 수정</label>
            </div>
            <div class="modal-body" style="background-color: #e5e5e5;border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;overflow-y:auto">
                <input id="ce_group_id" type="hidden" value="">
                <div style="padding-top: 2px;">
                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;padding-top: 10px;">
                        <h4 style="margin-left: 10px;">이 름</h4>
                        <input id="ce_group_name" style="border:1px solid grey;border-radius : 5px;margin: 5px 10px;width:93%;font-size:20px">
                    </div>
                </div>
                <div style="padding-top: 2px;" onclick="" class="">
                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;padding-top: 10px;">
                        <h4 style="margin-left: 10px;">공개 범위</h4>
                        <div class="input-group mt-3 mb-3" style="display:flex;border:1px solid grey;border-radius : 5px;margin: 5px 10px;width:93%;font-size:20px">
                            <input id="ce_group_public" class="form-control" style="border:none" placeholder="공개 범위 선택" value="공개">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;left:0px !important;padding:0px 10px">
                                    <li style="border-bottom:1px solid grey;border-radius : 2px;" onclick="set_group_public_status(0)">
                                        <div style="display:flex;padding:5px 10px;justify-content: space-between;">
                                            <div style="display:flex;">
                                                <img src="/iam/img/icon_unlock.svg" style="width:30px;float:left">
                                                <div style="margin-left:10px;">
                                                    <h4>공개</h4>
                                                    <h5>누구나 그룹 멤버와 게시물을 볼 수 있습니다.</h5>
                                                </div>
                                            </div>
                                            <div class="input-group-text" style="font-size: 20px">
                                                <input type="radio" name="ce_group_public" value="Y" checked>
                                            </div>
                                        </div>
                                    </li>
                                    <li style="border-bottom:1px solid grey;border-radius : 2px;" onclick="set_group_public_status(1)">
                                        <div style="display:flex;padding:5px 10px;justify-content: space-between;">
                                            <div style="display:flex;">
                                                <img src="/iam/img/icon_unlock.svg" style="width:30px;float:left">
                                                <div style="margin-left:10px;">
                                                    <h4>비공개</h4>
                                                    <h5>멤버만 그룹 멤버와 게시물을 볼 수 있습니다.</h5>
                                                </div>
                                            </div>
                                            <div class="input-group-text" style="font-size: 20px">
                                                <input type="radio" name="ce_group_public" value="N">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="padding-top: 2px;" onclick="" class="">
                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;padding-top: 10px;">
                        <h4 style="margin-left: 10px;">회원 콘텐츠 업로드</h4>
                        <div class="input-group mt-3 mb-3" style="display:flex;border:1px solid grey;border-radius : 5px;margin: 5px 10px;width:93%;font-size:20px">
                            <input id="ce_group_upload" class="form-control" style="border:none" placeholder="자동 업로드 여부" value="자동">
                            <div class="input-group-prepend">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                <ul class="dropdown-menu namecard-dropdown " style="background: white; color : black;left:0px !important;padding:0px 10px">
                                    <li style="border-bottom:1px solid grey;border-radius : 2px;" onclick="set_group_upload_status(0)">
                                        <div style="display:flex;padding:5px 10px;justify-content: space-between;">
                                            <div style="display:flex;">
                                                <div>
                                                    <h4>자동 업로드</h4>
                                                    <h5>회원의 콘텐츠가 자동으로 업로드 됩니다.</h5>
                                                </div>
                                            </div>
                                            <div class="input-group-text" style="font-size: 20px">
                                                <input type="radio" name="ce_group_upload" value="Y" checked>
                                            </div>
                                        </div>
                                    </li>
                                    <li style="border-bottom:1px solid grey;border-radius : 2px;" onclick="set_group_upload_status(1)">
                                        <div style="display:flex;padding:5px 10px;justify-content: space-between;">
                                            <div style="display:flex;">
                                                <div>
                                                    <h4>수동으로 승인</h4>
                                                    <h5>회원의 콘텐츠 업로드를 수동으로 승인합니다.</h5>
                                                </div>
                                            </div>
                                            <div class="input-group-text" style="font-size: 20px">
                                                <input type="radio" name="ce_group_upload" value="N">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="padding-top: 2px;" onclick="" class="">
                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;padding-top: 10px;">
                        <h4 style="margin-left: 10px;">설명 추가</h4>
                        <div class="input-group mt-3 mb-3" style="display:flex;margin: 5px 10px;width:93%;font-size:20px">
                            <textarea id="ce_group_desc" class="form-control" style="border:1px solid grey;border-radius : 5px;resize: vertical;min-height:40px" placeholder="사람들이 그룹에 대해 알수 있도록 그룹을 소개해주세요."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background-color: #e5e5e5;text-align: center">
                <a href="javascript:save_group()" class="btn btn-primary" style="width: 93%;">완료</a>
            </div>
        </div>
    </div>
</div>
<!--게시물 만들기 팝업-->
<div id="create_group_cont_popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10px;width:95%;max-width:768px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>게시물 작성하기</label>
            </div>
            <div class="modal-body popup-bottom" style="background-color: #e5e5e5;" id="group_list_popup">
                <div style="padding-top: 2px;">
                    <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px;display:flex;justify-content: space-between;">
                        <div style="display: flex">
                            <div style="border-radius: 10%;width: 50px;height: 50px;overflow: hidden;margin: 10px;">
                                <img src="/upload/18082021105048191213_07.jpg" style="width: 50px;height:50px;">
                            </div>
                            <h4 style="margin-top: auto;margin-bottom: auto">
                                가입한 그룹에 게시해보세요.
                            </h4>
                        </div>
                        <div class="dropdown " style="margin-right: 10px;margin-top: auto;margin-bottom: auto;">
                            <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" style="margin-top:0px">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu comunity" style="border: 1px solid #ccc;padding:0px;">
                                <li><a onclick="" style="padding:3px 3px 0px 3px !important;">이 콘텐츠 게시자 보기</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--추천 콘텐츠 팝업-->
<div id="recommend_contents_popup" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 10px;width:95%;max-width:768px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>회원님을 위한 추천 콘텐츠</label>
            </div>
            <div class="modal-body" style="border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;">
                <?
                $g_cont_sql = "select * from Gn_Iam_Contents where group_id in (" . $other_group . ") and sample_display='Y' order by sample_order desc";
                $g_cont_res = mysql_query($g_cont_sql);
                $g_index = 1;
                while ($g_cont_row = mysql_fetch_array($g_cont_res)) {
                    $g_card_sql = "select mem_id,card_short_url,main_img1,card_name,group_id from Gn_Iam_Name_Card c where c.idx = '$g_cont_row[card_idx]'";
                    $g_card_res = mysql_query($g_card_sql);
                    $g_card_row = mysql_fetch_array($g_card_res);

                    $sql_mem_g = "select mem_code from Gn_Member where mem_id='{$g_card_row['mem_id']}'";
                    $res_mem_g = mysql_query($sql_mem_g);
                    $row_mem_g = mysql_fetch_array($res_mem_g);

                    if (!$g_cont_row['contents_img'])
                        $g_cont_images = null;
                    else
                        $g_cont_images = explode(",", $g_cont_row['contents_img']);
                    for ($i = 0; $i < count($g_cont_images); $i++) {
                        if (strstr($g_cont_images[$i], "kiam")) {
                            $g_cont_images[$i] = str_replace("http://kiam.kr", "", $g_cont_images[$i]);
                            $g_cont_images[$i] = str_replace("http://www.kiam.kr", "", $g_cont_images[$i]);
                            //$g_cont_images[$i] = $cdn_ssl . $g_cont_images[$i];
                        }
                        if (!strstr($g_cont_images[$i], "http") && $g_cont_images[$i]) {
                            $g_cont_images[$i] = $cdn_ssl . $g_cont_images[$i];
                        }
                    }
                ?>
                    <div class="group_sample_content-item" id="group_sample_contents_list" style="margin-bottom: 20px;box-shadow: 2px 3px 3px 1px #eee;border: 1px solid #ccc;">
                        <div class="user-item" style="position: relative;display: flex;padding: 4px;border: none;border-bottom: 1px solid #dddddd;padding-top: 12px;padding-bottom: 12px;">
                            <a href="<?= '/?' . $g_card_row['card_short_url'] . $row_mem_g['mem_code'] . '&cur_win=group-con&gkind=' . $g_card_row['group_id'] ?>" class="img-box" style="padding: 4px;">
                                <div class="user-img" style="width: 38px;height: 38px;border-radius: 50%;overflow: hidden;">
                                    <img src="<?= $g_card_row['main_img1'] ? cross_image($g_card_row['main_img1']) : '/iam/img/common/logo-2.png' ?>" alt="" style="width: 100%;height: 100%;object-fit: cover;">
                                </div>
                            </a>
                            <div class="wrap" style="width: 50%;display: flex;flex-direction: column;padding: 4px 6px;">
                                <a href="<?= '/?' . $g_card_row['card_short_url'] . $row_mem_g['mem_code'] . '&cur_win=group-con&gkind=' . $g_card_row['group_id'] ?>" class="user-name" style="font-size: 15px;font-weight: 700;">
                                    <?= $g_card_row['card_name'] ?>
                                </a>
                                <? if ($g_cont_row['contents_title'] != "") { ?>
                                    <div class="title">
                                        <div class="text" style="text-align : left;font-weight: bold;font-size: 25px;margin: auto 10px;overflow: hidden;text-overflow: clip;height: 75px;"><?= $g_cont_row['contents_title'] ?></div>
                                    </div>
                                <? } ?>
                                <a href="<?= '/?' . $g_card_row['card_short_url'] . $row_mem_g['mem_code'] . '&cur_win=group-con&gkind=' . $g_card_row['group_id'] ?>" class="date" style="font-size: 13px;color: rgb(153, 153, 153);">
                                    <?= $g_cont_row['req_data'] ?>
                                </a>
                            </div>
                            <div class="media-inner" style="position: absolute; right: 2px; top: 0px; width: 30%;height: 100%;text-align: center;background:<?= $color[$g_index % 4]; ?>">
                                <? if ((int)$g_cont_row['contents_type'] == 1 || (int)$g_cont_row['contents_type'] == 3) {
                                    if (count($g_cont_images) > 0) {
                                        if ($g_cont_row['contents_url']) { ?>
                                            <a onclick="showIframeModal('<?= $g_cont_row['contents_url'] ?>')" target="_blank">
                                                <? if (count($g_cont_images) == 1) { ?>
                                                    <img src="<?= cross_image($g_cont_images[0]) ?>" style="height : 100%"></a>
                                        <? } else { ?>
                                            <img src="<?= cross_image($g_cont_images[0]) ?>" style="height : 100%" onclick=""></a>
                                        <? } ?>
                                    <? } else { ?>
                                        <? if (count($g_cont_images) == 1) { ?>
                                            <img src="<?= cross_image($g_cont_images[0]) ?>" style="height : 100%">
                                        <? } else { ?>
                                            <img src="<?= cross_image($g_cont_images[0]) ?>" style="height : 100%" onclick="">
                                        <? } ?>
                                    <?
                                        }
                                    }
                                } else if ((int)$g_cont_row['contents_type'] == 2) {
                                    $img_cnt = count($g_cont_images);
                                    $vid_array = explode(" ", $g_cont_row['contents_iframe']);
                                    $vid_array[2] = "height=100%";
                                    $vid_array[1] = "width=100%";
                                    $vid_data = implode(' ', $vid_array);
                                    if ($img_cnt == 0) {
                                        echo $vid_data;
                                    } else { ?>
                                    <div onclick="play_list<?= $g_cont_row['idx']; ?>();" id="vidwrap_list<?= $g_cont_row['idx']; ?>" style="position:relative;">
                                        <? if (count($g_cont_images) == 1) { ?>
                                            <img src="<?= cross_image($g_cont_images[0]) ?>" style="height : 110px;">
                                            <? if ($g_cont_row['contents_img']) { ?>
                                                <img src="/iam/img/movie_play.png" style="position: absolute; z-index: 50; left: 40%; width: 50px; top: 40%;">
                                            <? } ?>
                                        <? } else { ?>
                                            <img src="<?= cross_image($g_cont_images[0]) ?>" style="height : 100%" onclick="">
                                        <? } ?>
                                    </div>
                                    <script type="text/javascript">
                                        function play_list<?= $g_cont_row['idx']; ?>() {
                                            document.getElementById('vidwrap_list<?= $g_cont_row['idx']; ?>').innerHTML = "<?= $vid_data ?>";
                                        }
                                    </script>
                                <?
                                    }
                                } else if ((int)$g_cont_row['contents_type'] == 4) {
                                    $vid_data = $g_cont_row['source_iframe'];
                                ?>
                                <div>
                                    <iframe src="<?= $vid_data ?>" width="100%" height="100%"></iframe>
                                </div>
                            <? } ?>
                            </div>
                        </div>
                    </div>
                <?
                    $g_index++;
                } ?>
            </div>
        </div>
    </div>
</div>
<!--그룹 정보 팝업-->
<div id="group_info_popup" class="modal fade" tabindex="-1" role="dialog" style="overflow-y:auto">
</div>
<!--간편가입 팝업-->
<div id="simple_login_popup" class="modal fade" tabindex="-1" role="dialog" style="overflow-y:auto">
    <div class="modal-dialog" role="document" style="margin-top: 10px;width:95%;max-width:768px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <img src="/iam/img/menu/icon_close_white.png" class="close" data-dismiss="modal" aria-hidden="true">
                </button>
            </div>
            <div class="modal-title">
                <label>회원가입</label>
            </div>
            <div class="modal-body">
                <div class="container" style="padding:0px">
                    <div class="simple_login_popup_div">
                        <p style="margin-right:5px;font-size:15px;font-weight:700;width: 65px;padding-top: 10px;">이름</p>
                        <input type="text" style="font-size:14px;width:calc(100% - 65px);" id="simple_mem_name" placeholder="이름을 입력하세요">
                    </div>
                    <div class="simple_login_popup_div">
                        <p style="margin-right:5px;font-size:15px;font-weight:700;width: 65px;padding-top: 10px;">아이디</p>
                        <input type="text" id="simple_mem_id" style="width:calc(100% - 165px);font-size:14px;" placeholder="소문자로 입력하세요">
                        <input type="button" id="simple_checkup" value="ID 중복확인" data-check="N" onclick="simple_id_check()">
                    </div>
                    <div id="simple_id_html" class="simple_login_popup_div" style="font-size:13px;display:none;padding-top:10px;margin-left:65px">
                        <img src="/images/check.gif" style="height:18px"> 사용 가능하신 아이디입니다.
                    </div>
                    <div class="simple_login_popup_div">
                        <p style="margin-right:5px;font-size:15px;font-weight:700;width: 65px;padding-top: 10px;">휴대폰</p>
                        <input type="text" style="font-size:14px;width: calc(100% - 65px);" id="simple_mem_phone" placeholder="-없이 번호를 입력하세요">
                    </div>
                    <div class="simple_login_popup_div">
                        <p style="margin-right:5px;font-size:15px;font-weight:700;width: 65px;padding-top: 10px;">비번</p>
                        <input type="text" style="font-size:14px;width: calc(100% - 65px);" id="simple_mem_pwd" placeholder="" readonly>
                    </div>
                    <div style="margin-top: 10px;">
                        <p class="notice">서비스 이용을 위한 이용약관, 개인정보 처리방침 등 필수, 선택 등의 항목에 동의해주세요.</p>
                    </div>
                    <div style="display:flex;justify-content: space-between;border:1px solid #ddd;margin-top:10px">
                        <div>
                            <label class="title" id="agreement-field" style="margin:10px;cursor:pointer">약관 동의하기*</label>
                        </div>
                        <div style="padding: 10px;">
                            <input type="checkbox" class="check" id="checkAll_title" style="height:auto !important">
                            <label for="checkAll_title">모두 동의</label>
                        </div>
                    </div>
                    <div class="agreement-wrap" style="display: none;border:1px solid #ddd;padding:5px" id="agreement-wrap">
                        <div class="agreement-item is-checkall">
                            <div class="check-wrap">
                                <input type="checkbox" class="check" id="checkAll" required itemname='약관동의' style="height:auto !important">
                                <label for="checkAll"> <strong>모두동의</strong> </label>
                            </div>
                        </div>
                        <div class="agreement-item" style="margin-top:8px;display:flex;justify-content: space-between;">
                            <div class="check-wrap" style="margin:5px 0px">
                                <input type="checkbox" class="check checkAgree" id="checkPersonal" style="height:auto !important">
                                <label for="checkPersonal">개인정보수집동의</label>
                            </div>
                            <div style="border:1px solid #ddd;border-radius:10px;margin-right:15px;padding:5px">
                                <a href="/m/privacy.php" target="_blank">전문보기</a>
                            </div>
                        </div>
                        <div class="agreement-item" style="margin-top:2px;display:flex;justify-content: space-between;">
                            <div class="check-wrap" style="margin:5px 0px">
                                <input type="checkbox" class="check checkAgree" id="checkTerms" style="height:auto !important">
                                <label for="checkTerms">회원이용약관</label>
                            </div>
                            <div style="border:1px solid #ddd;border-radius:10px;margin-right:15px;padding:5px">
                                <a href="/m/terms.php" target="_blank">전문보기</a>
                            </div>
                        </div>
                        <div class="agreement-item" style="margin-top:5px">
                            <div class="check-wrap">
                                <input type="checkbox" class="check checkAgree" id="checkReceive" style="height:auto !important">
                                <label for="checkReceive">메시지수신동의</label>
                            </div>
                            <div class="desc">
                                <label style="margin:0px">① 메시지 종류 : 아이엠 프로필 및 솔루션의 기능개선 메시지정보, 앱체크정보, 회원관리정보, 공익정보, 유익정보, 회원프로필정보를 발송합니다.</label>
                                <label style="margin:0px">② 메시지 발송 방법 : 고객님이 설치한 문자앱을 통해 고객님 폰의 문자를 활용하여 고객님의 계정에서 볼수 있게 합니다.</label>
                            </div>
                        </div>
                        <div class="agreement-item" style="margin-top:5px">
                            <div class="check-wrap">
                                <input type="checkbox" class="check checkAgree" id="checkThirdparty" style="height:auto !important">
                                <label for="checkThirdparty">개인정보 제3자 제공 동의</label>
                            </div>
                            <div class="desc">
                                <label style="margin:0px">① 제공받는 자 : 본 서비스를 개발하는 온리원계열사, 본 서비스 제공을 지원하는 협업사, 상품을 제공하는 쇼핑몰 관계사, 기타 본서비스 제공에 필요한 기관</label>
                                <label style="margin:0px">② 개인정보 이용 목적 : 서비스 제공을 위한 고객정보의 활용, 서비스 정보의 제공, e프로필서비스의 공유, 회원간의 품앗이 정보공유 등</label>
                                <label style="margin:0px">③ 개인정보의 항목 : 개인정보 제공에 동의한 내용</label>
                                <label style="margin:0px">④ 보유 및 이용 기간 :본 서비스를 이용하는 기간</label>
                                <label style="margin:0px">⑤ 제공 동의에 거부시 본 서비스가 제공되지 않습니다.</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="$('#simple_login_popup').modal('hide');" class="btn btn-default btn-left">취소</a>
                    <button type="button" onclick="simple_login();" class="btn btn-active btn-right">등록</a>
            </div>
        </div>
    </div>
</div>
<!--당일 콘텐츠 팝업-->
<div id="today_content-modalwindow" style="z-index: 1100;position: fixed;width: 100%;max-width: 768px;bottom:-80px;margin:0px auto;display: none">
    <div class="modal-content" style="border-radius: 15px;width: 80%;margin: 0px auto">
        <div class="modal-header" style="padding-bottom: 10px;border:none;border-radius: 15px;display: flex;justify-content: space-between;">
            <span id="today_content_desc">소속의 회원명님의</span>
            <button type="button" class="close" onclick="hideTodayContentModal()">
                <img src="/iam/img/main/close.png" style="width:20px" class="close">
            </button>
        </div>
        <div class="modal-body" style="background-color: #ffffff;border-bottom-left-radius: 15px;border-bottom-right-radius: 15px;">
        </div>
    </div>
</div>