<?php
include "inc/header.inc.php";
if ($_SESSION['iam_member_id'] == "") {
    echo "<script>location='/';</script>";
}
$link  = $_GET["link"];
?>
<style>
    input[type=checkbox] {
        display: none;
        margin-left: 7px;
    }

    .desc li {
        margin-bottom: 5px;
        font-size: 12px;
        line-height: 18px;
    }

    .input-wrap a {
        float: right;
        width: 65px;
        display: block;
        margin-left: 5px;
        padding: 7px 5px;
        font-size: 11px;
        color: #fff;
        line-height: 14px;
        background-color: #ccc;
        text-align: center;
    }

    .check-wrap .check~label:before {
        content: '';
        position: absolute;
        top: 3px;
        left: 0;
        width: 18px;
        height: 18px;
        background-color: #fff;
        border: 1px solid #ccc;
    }

    .check-wrap .check~label {
        position: relative;
        display: inline-block;
        padding-left: 25px;
        line-height: 24px;
    }

    .check-wrap .check:checked~label:after {
        content: '\f00c';
        position: absolute;
        top: 1px;
        left: 2px;
        color: #fff;
        font-family: 'Fontawesome';
        font-size: 13px;
    }

    .check-wrap .check:checked~label:before {
        background-color: #ff0066;
        border-color: #ff0066;
    }

    .lselect {
        float: left;
        width: 70px;
        height: 28px;
        background-color: #fff;
        border: 1px solid #ccc;
        font-size: 12px;
        line-height: 16px;
    }
</style>
<main id="register" class="common-wrap" style="margin-top: 86px"><!-- 컨텐츠 영역 시작 -->
    <input type="hidden" name="link" id="link" value="<?= $link ?>">
    <div class="container" style="<?= $_GET[gwc_req] == "Y" ? "display:none" : "" ?>">
        <div id='order_address' style='left:0px; top:0px; width:320px; height:600px; position:absolute;  z-index:1000; display:none; background-color:white;'></div>
        <div class="row">
            <div class="col-12">
                <div class="inner-wrap">
                    <!-- <h2 class="title" style="text-align: left;">마이페이지</h2>
                    <br> -->
                    <form name="edit_form" id="edit_form" method="post" enctype="multipart/form-data">
                        <div style="text-align: center">
                            <h2 class="title">회원정보수정</h2>
                        </div>
                        <input type="hidden" name="join_modify" value="<?php echo $member_iam['mem_code'] ?>">
                        <section class="input-field">
                            <h3 class="title">기본정보수정</h3>
                            <div class="utils clearfix"></div>
                            <div class="form-wrap">
                                <div class="attr-row is-account">
                                    <div class="attr-name">아이디(필수)</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <?php echo $member_iam['mem_id']; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">회원구분</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <?
                                            $mem_leb = "";
                                            if ($member_iam['mem_leb'] == "50") {
                                                $mem_leb = "사업회원";
                                            } else if ($member_iam['mem_leb'] == "22") {
                                                $mem_leb = "일반회원";
                                            } else if ($member_iam['mem_leb'] == "21") {
                                                $mem_leb = "강사회원";
                                            } else if ($member_iam['mem_leb'] == "60") {
                                                $mem_leb = "홍보회원";
                                            }
                                            if ($member_iam['service_type'] == "0") {
                                                $mem_leb .= " / FREE";
                                            } else if ($member_iam['service_type'] == "1") {
                                                $mem_leb .= " / 이용자";
                                            } else if ($member_iam['service_type'] == "2") {
                                                $mem_leb .= " / 리셀러";
                                            } else if ($member_iam['service_type'] == "3") {
                                                $mem_leb .= " / 분양자";
                                            }
                                            if ($member_iam['gwc_state']) {
                                                if ($member_iam['gwc_leb'] == "4") {
                                                    $mem_leb .= " / 굿웰스-일반회원";
                                                } else if ($member_iam['gwc_leb'] == "1") {
                                                    $mem_leb .= " / 굿웰스-굿슈머";
                                                } else if ($member_iam['gwc_leb'] == "2") {
                                                    $mem_leb .= " / 굿웰스-공급사";
                                                } else if ($member_iam['gwc_leb'] == "3") {
                                                    $mem_leb .= " / 굿웰스-센터";
                                                }
                                            }
                                            $spec_arr = explode(",", $member_iam['special_type']);
                                            foreach ($spec_arr as $spec_leb) {
                                                if ($spec_leb == 1)
                                                    $mem_leb .= " / 판매자";
                                                else if ($spec_leb == 2)
                                                    $mem_leb .= " / 전문가";
                                                else if ($spec_leb == 3)
                                                    $mem_leb .= " / 구인자";
                                                else if ($spec_leb == 4)
                                                    $mem_leb .= " / 구직자";
                                                else if ($spec_leb == 5)
                                                    $mem_leb .= " / 리포터";
                                                else if ($spec_leb == 6)
                                                    $mem_leb .= " / 아티스트";
                                            }
                                            echo $mem_leb;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row is-account">
                                    <div class="attr-name">폰번호</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <?php echo $member_iam['mem_phone']; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row is-phone">
                                    <div class="attr-name">성명/성별</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="text" class="input" name="name" id="name" value="<?php echo $member_iam['mem_name']; ?>">
                                            <div class="check-wrap">
                                                <input type="checkbox" class="check" id="mem_sex_m" name="mem_sex" value="m" <?php echo $member_iam['mem_sex'] == "m" ? "checked" : ""; ?>>
                                                <label for="mem_sex_m">남자</label>
                                            </div>
                                            <div class="check-wrap">
                                                <input type="checkbox" class="check" id="mem_sex_f" name="mem_sex" value="f" <?php echo $member_iam['mem_sex'] == "f" ? "checked" : ""; ?>>
                                                <label for="mem_sex_f">여자</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">프로필사진</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="file" name="profile" id="profile" accept=".jpg,.jpeg,.png,.gif,.svc" />
                                            <? if ($member_iam[profile] != "") {
                                                if (strstr($member_iam[profile], "kiam")) {
                                                    $member_iam[profile] = str_replace("http://kiam.kr", "", $member_iam[profile]);
                                                    $member_iam[profile] = str_replace("http://www.kiam.kr", "", $member_iam[profile]);
                                                    //$image_link = $cdn_ssl.$member_iam[profile];
                                                }
                                                if (!strstr($member_iam[profile], "http") && $member_iam[profile]) {
                                                    $image_link = $cdn_ssl . $member_iam[profile];
                                                } else {
                                                    $image_link = $member_iam[profile];
                                                }
                                                $image_link = cross_image($image_link);
                                            } else {
                                                $image_link = "img/profile_img.png";
                                            } ?>
                                            <img style='width:50%' src='<?= $image_link ?>' id="profile_img">
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">생년월일</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <select name="birth_1" class="lselect" itemname='생년'>
                                                <option value="">년</option>
                                                <? for ($i = date("Y"); $i > 1899; $i--) {
                                                    $selected = $iam_birth_arr[0] == $i ? "selected" : ""; ?>
                                                    <option value="<?= $i ?>" <?= $selected ?>><?= $i ?></option>
                                                <? } ?>
                                            </select>
                                            <select name="birth_2" class="lselect" itemname='월' style="display:none">
                                                <option value="">월</option>
                                                <? for ($i = 1; $i < 13; $i++) {
                                                    $k = $i < 10 ? "0" . $i : $i;
                                                    $selected = $iam_birth_arr[1] == $k ? "selected" : ""; ?>
                                                    <option value="<?= $k ?>" <?= $selected ?>><?= $k ?></option>
                                                <? } ?>
                                            </select>
                                            <select name="birth_3" class="lselect" itemname='일' style="display:none">
                                                <option value="">일</option>
                                                <? for ($i = 1; $i < 32; $i++) {
                                                    $k = $i < 10 ? "0" . $i : $i;
                                                    $selected = $iam_birth_arr[2] == $k ? "selected" : ""; ?>
                                                    <option value="<?= $k ?>" <?= $selected ?>><?= $k ?></option>
                                                <? } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">소속/직책</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="text" name="zy" id="zy" class="input" placeholder="회사명, 단체명, 직장명" value="<?php echo $member_iam['zy']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">집주소</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <?
                                            // 광역시도 목록
                                            // $province_list = array();
                                            // $query = "SELECT province FROM gn_cities group by province";
                                            // $res = mysqli_query($self_con,$query);
                                            // while($row = mysqli_fetch_array($res)) {
                                            //     $province_list[] = $row['province'];
                                            // }
                                            // $member_address = explode(" ", $member_iam['mem_add1']);
                                            // if(count($member_address) == 4){
                                            //     $provice1 = $member_address[0];
                                            //     $city1 = $member_address[1] . " " . $member_address[2];
                                            //     $town1 = $member_address[3];
                                            // }
                                            // else{
                                            //     $provice1 = $member_address[0];
                                            //     $city1 = $member_address[1];
                                            //     $town1 = $member_address[2];
                                            // }
                                            // $city_list = array();
                                            // if(isset($provice1)) {
                                            //     $query = "SELECT city FROM gn_cities WHERE province = '{$provice1}' group by city ";
                                            //     $res = mysqli_query($self_con,$query);
                                            //     while($row = mysqli_fetch_array($res))
                                            //         $city_list[] = $row['city'];
                                            // }

                                            // $town_list = array();
                                            // if(isset($city1)) {
                                            //     $query = "SELECT town FROM gn_cities WHERE city = '{$city1}' and province = '{$provice1}' group by town";
                                            //     $res = mysqli_query($self_con,$query);
                                            //     while($row = mysqli_fetch_array($res))
                                            //         $town_list[] = $row['town'];
                                            // }
                                            ?>
                                            <?php //if($code == "KR") {
                                            ?>
                                            <!-- <select itemname="주소" id = "value_region_province">
                                                <option value="">-시/도-</option>
                                                <? foreach ($province_list as $province) { ?>
                                                    <option value="<?= $province ?>" <? if ($province == $provice1) {
                                                                                        echo 'selected';
                                                                                    } ?>><?= $province ?></option>
                                                <? } ?>
                                            </select>
                                            <select id="value_region_city" itemname="주소">
                                                <option value="">-군/구-</option>
                                                <? foreach ($city_list as $city) { ?>
                                                    <option value="<?= $city ?>" <? if ($city == $city1) {
                                                                                    echo 'selected';
                                                                                } ?>><?= $city ?></option>
                                                <? } ?>
                                            </select>
                                            <select id="value_region_town" itemname="주소">
                                                <option value="">-읍/면/동-</option>
                                                <? foreach ($town_list as $town) { ?>
                                                    <option value="<?= $town ?>" <? if ($town1 == $town) {
                                                                                    echo 'selected';
                                                                                } ?>><?= $town ?></option>
                                                <? } ?>
                                            </select>
                                            <input type="hidden" name="add1" id="add1" required  class="input" placeholder="지역통계를 위해 읍,면,동까지 입력"  value="<?php echo $member_iam['mem_add1']; ?>"> -->
                                            <?php //} else {
                                            ?>
                                            <input type="text" name="zip" id="zip" required class="input" placeholder="우편번호" value="<?php echo $member_iam['mem_zip']; ?>" style="width: 35%;margin-bottom: 5px;">
                                            <button type="button" onclick="win_zip('edit_form', 'zip', 'add1', 'add2', 'add3', 'b_addr_jibeon');" class="btn_small grey" style="background: #888;color: white;padding: 3px;">주소검색</button>
                                            <input type="text" name="add1" id="add1" required class="input" placeholder="도로명" value="<?php echo $member_iam['mem_add1']; ?>">
                                            <?php //}
                                            ?>
                                        </div>
                                        <div class="input-wrap" style="margin-top: 5px;">
                                            <input type="text" name="add2" id="add2" class="input" placeholder="집주소 상세정보를 입력하세요." value="<?php echo $member_iam['mem_add2']; ?>">
                                            <input type="text" name="add3" class="frm_input frm_address" readonly="" hidden>
                                            <input type="hidden" name="b_addr_jibeon" value="R">
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">회사주소</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="text" name="com_add1" id="com_add1" class="input" placeholder="회사주소를 입력하세요." value="<?php echo $member_iam['com_add1']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row is-mail">
                                    <div class="attr-name">이메일주소</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="text" style="width:135px" class="input mail-first" name="email_1" itemname='이메일' id="email_1" value="<?php echo $iam_email_arr[0]; ?>">
                                            <span class="bridge">@</span>
                                            <input type="text" style="width:calc( 100% - 235px );" class="input mail-second" name="email_2" id='email_2' itemname='이메일' value="<?php echo $iam_email_arr[1]; ?>">
                                            <select class="select" name="email_3" itemname='이메일' onchange="inmail(this.value,'email_2')">
                                                <?
                                                foreach ($email_arr as $key => $v) {
                                                ?>
                                                    <option value="<?= $key ?>"><?= $v ?></option>
                                                <?
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row is-mail">
                                    <div class="attr-name">입금계좌</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type='text' class="input mail-first" name="bank_name" id="bank_name" placeholder="은행" style="width: 30%;" value="<?= $member_iam['bank_name']; ?>"><input type='text' class="input mail-first" name="bank_account" id="bank_account" placeholder="계좌번호" style="width: 30%;" value="<?= $member_iam['bank_account']; ?>"><input type='text' class="input mail-first" name="bank_owner" id="bank_owner" placeholder="예금주" style="width: 30%;" value="<?= $member_iam['bank_owner']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">API입력</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="text" name="gpt_chat_api_key" id="gpt_chat_api_key" class="input" placeholder="알지(챗GPT)사용을 위한 본인의 API를 입력하세요." value="<?php echo $member_iam['gpt_chat_api_key']; ?>">
                                            ※ API 받으러가기 : <button onclick="window.open('https://tinyurl.com/3hzsk9p7', '_blank')" style="border: 1px solid blue;">Click here!!</button><button onclick='alert("1. 알지 GPT는 자신의 API 키로 이용할 수 있습니다.\n2. 혹시 사용하다 알지 GPT 작동이 안되면 API 키 이용한도 초과된 것이므로, 유료 API 키를 입력하거나, 다른 명의의 API키를 다시 발급받아 입력하세요.");' style="border-radius: 15px;margin-left: 10px;border: 1px solid;">?</button>
                                        </div>
                                    </div>
                                </div>
                                <? if ($_SESSION['iam_member_subadmin_id']) { ?>
                                    <div class="attr-row">
                                        <div class="attr-name">추천링크</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <?
                                                $query = "select * from Gn_Iam_Name_Card where group_id is NULL and mem_id  = '$member_iam[mem_id]' ORDER BY idx";
                                                $result = mysqli_query($self_con,$query);
                                                $row = mysqli_fetch_array($result);
                                                $card_url = $row['card_short_url'];
                                                ?>
                                                <span id="sHtml" style="display:none"><?= 'http://' . $HTTP_HOST . '/?' . $card_url ?></span>
                                                <input type="button" name="" value="복사하기" onclick="copyHtml()">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="attr-row">
                                        <div class="attr-name">추천아이디</div>
                                        <div class="attr-value">
                                            <div class="input-wrap">
                                                <input type="hidden" name="nick" itemname='닉네임' value="<?= $member_iam['mem_name'] ?>" />
                                                <?= $member_iam[recommend_id] ?>
                                            </div>
                                        </div>
                                    </div>
                                <? } else { ?>
                                    <input type="hidden" name="nick" itemname='닉네임' value="<?= $member_iam['mem_name'] ?>" />
                                <? } ?>
                                <? echo $member_iam[keywords]; ?>
                                <div class="attr-row">
                                    <div class="attr-name">관심키워드</div>
                                    <div class="attr-value">
                                        <div class="input-wrap">
                                            <input type="text" name="keywords" id="keywords" class="input" placeholder="관심 키워드를 콤마로 구분하여 입력합니다. 타회원이 검색한 키워드와 매칭시 아이엠카드나 콘텐츠가 노출됩니다." value="<?php echo $member_iam['keywords']; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="attr-row">
                                    <div class="attr-name">소식받기</div>
                                    <div class="attr-value">
                                        <div class="check-wrap">
                                            <input type="checkbox" class="check" id="is_message" name="is_message" <?= $member_iam[is_message] == "Y" ? "checked" : "" ?>>
                                            <label for="is_message">온리원그룹의 소식 받기</label>
                                        </div>
                                        <div class="desc">
                                            <ul>
                                                <li>※ 아이엠, 셀링솔루션, 셀링대회, 제휴업체, 셀링교육, 마케팅지원과 온리원그룹 활동 및 사업소식을 전달합니다. </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="attr-row">
                                    <div class="attr-name">굿웰스클럽<br>회원 신청</div>
                                    <div class="attr-value">
                                        <div class="check-wrap">
                                            굿웰스클럽은 모든 회원이 공급과 소비를 동시에 할수 있는 1인 플랫폼과 홍보와 판매를 할수 있는 자동 플랫폼을 제공하여 부의 균형을 해결하고자 합니다. [자세히보기 클릭]
                                        </div>
                                        <div class="desc">
                                        <ul style="display:flex;margin-top:10px;flex-wrap:wrap">
                                            [신청하기]
                                            <input type="checkbox" class="gwc_chk" id="chk1" style="display:block;" <?= $member_iam[gwc_req_leb] == "1" ? 'checked' : ''; ?>> 가입회원
                                            <input type="checkbox" class="gwc_chk" id="chk2" style="display:block;" <?= $member_iam[gwc_req_leb] == "2" ? 'checked' : ''; ?>> 소비회원
                                            <input type="checkbox" class="gwc_chk" id="chk3" style="display:block;" <?= $member_iam[gwc_req_leb] == "3" ? 'checked' : ''; ?>> 공급회원
                                            <input type="checkbox" class="gwc_chk" id="chk4" style="display:block;" <?= $member_iam[gwc_req_leb] == "4" ? 'checked' : ''; ?>> 굿슈머
                                            <input type="checkbox" class="gwc_chk" id="chk5" style="display:block;" <?= $member_iam[gwc_req_leb] == "5" ? 'checked' : ''; ?>> 팀리더
                                            <input type="checkbox" class="gwc_chk" id="chk6" style="display:block;" <?= $member_iam[gwc_req_leb] == "6" ? 'checked' : ''; ?>> 그룹리더
                                            <input type="checkbox" class="gwc_chk" id="chk7" style="display:block;" <?= $member_iam[gwc_req_leb] == "7" ? 'checked' : ''; ?>> 지역리더
                                            <input type="checkbox" class="gwc_chk" id="chk8" style="display:block;" <?= $member_iam[gwc_req_leb] == "8" ? 'checked' : ''; ?>> 국가리더
                                            <input type="checkbox" class="gwc_chk" id="chk9" style="display:block;" <?= $member_iam[gwc_req_leb] == "9" ? 'checked' : ''; ?>> 국제리더
                                        </ul>
                                        </div>
                                    </div>
                                </div> -->
                                <input type="hidden" name="gwc_leb" id="gwc_leb" value="<?= $member_iam[gwc_req_leb] ?>">
                            </div>
                        </section>
                        <div class="button-wrap">
                            <a href="javascript:history.back(-1);" class="button is-grey">취소</a>
                            <a href="javascript:save_form(edit_form,'<?= $member_iam['mem_code'] ?>')" class="button is-pink">정보수정</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="inner-wrap">
            <form name="web_pwd_form" action="" method="post">
                <section class="input-field">
                    <h3 class="title">비밀번호수정</h3>
                    <div class="utils clearfix"></div>
                    <div class="form-wrap">
                        <div class="attr-row">
                            <div class="attr-name">기존비밀번호</div>
                            <div class="attr-value">
                                <div class="input-wrap">
                                    <input type="password" class="input" name="old_pwd" id="old_pwd" itemname='기존 웹비밀번호' required />
                                </div>
                            </div>
                        </div>

                        <div class="attr-row">
                            <div class="attr-name">새비밀번호</div>
                            <div class="attr-value">
                                <div class="input-wrap">
                                    <input type="password" class="input" name="new_pwd" id="new_pwd" itemname='새 웹비밀번호' required onkeyup="pwd_check('1')" onblur="pwd_check('1')" />
                                </div>
                            </div>
                        </div>

                        <div class="attr-row">
                            <div class="attr-name">새비번확인</div>
                            <div class="attr-value">
                                <div class="input-wrap">
                                    <input type="password" class="input" name="pwd_cfm" id="pwd_cfm" itemname='새비밀번호확인' required onblur="pwd_cfm_check('1')" />
                                    <input type="hidden" name="pwd_status" class="input" required itemname='새비밀번호확인' />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="button-wrap">
                    <a href="javascript:history.back(-1);" class="button is-grey">취소</a>
                    <a href="javascript:void(0)" onclick="pwd_change(web_pwd_form,'1')" class="button is-pink">비번수정</a>
                </div>
            </form>
        </div>
        <div class="inner-wrap">
            <section class="input-field">
                <a href="javascript:void(0)" onclick="showInfoOut()">
                    <h3 class="title">회원탈퇴(클릭)</h3>
                </a>
                <div class="utils clearfix"></div>
                <div class="contents form-wrap" style="display:none">
                    <div class="attr-row is-account">
                        <div class="attr-name">아이디</div>
                        <div class="attr-value">
                            <div class="input-wrap">
                                <input type="test" class="input" name="leave_id" id="leave_id">
                            </div>
                        </div>
                    </div>
                    <div class="attr-row is-account">
                        <div class="attr-name">비밀번호확인</div>
                        <div class="attr-value">
                            <div class="input-wrap">
                                <input type="password" class="input" name="leave_pwd" id="leave_pwd">
                            </div>
                        </div>
                    </div>
                    <div class="attr-row is-account">
                        <div class="attr-name">탈퇴사유</div>
                        <div class="attr-value">
                            <div class="input-wrap">
                                <input type="text" class="input" name="leave_liyou" id="leave_liyou">
                            </div>
                        </div>
                    </div>
                    <div class="desc-wrap">
                        <div class="inner">
                            <ul>[탈퇴시 삭제 사항]
                                <li>① 회원탈퇴시 아이디, 이름, 폰번호, 저장된 주소록, 텍스트메시지, 이미지, 수발신 기록, 수신거부, 수신동의정보가 삭제됩니다.</li>
                                <li>② 회원탈퇴시 수당지급정보와 이후 수당지급이 중단됩니다. </li>
                                <li>③ 회원탈퇴시 e프로필정보와 e프로필수발신 정보 및 대화정보가 삭제됩니다.</li>
                                <li>④ 회원탈퇴시 원스텝솔루션의 수신동의자 정보와 랜딩정보, 예약메시지정보가 모두 삭제됩니다. </li>
                            </ul></br>
                            <ul>[탈퇴 세부 안내]
                                <li>① 폰에 설치된 '아이엠'앱을 삭제합니다.</li>
                                <li>② 탈퇴신청 후 1개월까지는 고객 정보가 보관됩니다. </li>
                                <li>③ 탈퇴 후 정보는 1개월 이내에 복구 가능하며 복구비용을 납부해야 합니다.</li>
                                <li>④ 탈퇴후 1개월 이내 복구 요청시 이메일로 복구요청을 해주세요. (개발자 계정 : 1pagebook@naver.com) </li>
                            </ul>
                        </div>
                    </div>
                    <div class="button-wrap">
                        <a href="/iam/" class="button is-grey">고민중입니다</a>
                        <a href="#" onclick="member_leave()" class="button is-pink">회원탈퇴합니다</a>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div id="intro_gwc_req" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
            <!-- Modal content-->
            <div class="modal-content">
                <div>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <img src="/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
                    </button> -->
                </div>
                <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#82C836;color:white;">
                    <label style="padding:15px 0px"></label>
                </div>
                <div class="modal-body">
                    <img src="img/icon_gm_apply.PNG" onclick="save_form(edit_form,'<?= $member_iam['mem_code'] ?>')">
                </div>
            </div>
        </div>
    </div>
    <div id="go_page_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <img src="/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
                    </button>
                </div>
                <div class="modal-title" style="width:100%;font-size:18px;text-align: center;background:#82C836;color:white;">
                    <label style="padding:15px 0px"></label>
                </div>
                <div class="modal-body">
                    <p>우선 구매할 상품을 생각하지 못했으면 캐시 포인트를 먼저 구입하시고 판매하기를 할수 있습니다. 단, 캐시 포인트는 1개월 이내에 상품구입에 사용하셔야 합니다.</p>
                </div>
                <div class="modal-footer" style="text-align: center">
                    <button type="button" class="btn btn-primary" onclick="go_page('cash')">캐시포인트로 구입</button>
                    <button type="button" class="btn btn-primary" onclick="go_page('shopping')">쇼핑하기</button>
                </div>
            </div>
        </div>
    </div>
</main><!-- // 컨텐츠 영역 끝 -->
<div id="ajax_div" style="display:none"></div>
<script language="javascript">
    var gwc_req = '<?= $_GET[gwc_req] ?>';
    var show_modal = '<?= $_GET[show_modal] ?>';
    $(document).ready(function() {
        if (show_modal == "Y" && gwc_req == "Y") {
            gwc_tab();
        }
        if (show_modal != "Y" && gwc_req == "Y") {
            $("#intro_gwc_req").modal('show');
        }
    });

    function showInfoOut() {
        $('.contents').show();
    }
    $(function() {
        $(document).ajaxStop(function() {
            $("#ajax-loading").delay(10).hide(1);
        });
        $('#mem_sex_m').on("click", function() {
            $('#mem_sex_f').prop("checked", false);
        });
        $('#mem_sex_f').on("click", function() {
            $('#mem_sex_m').prop("checked", false);
        });
        $("#profile").on("change", function() {
            $("#profile_img").prop("src", window.URL.createObjectURL($("#profile")[0].files[0]));
        });
        $('.gwc_chk').on('click', function() {
            alert("<?= str_replace("\n", '\n', $gwc_req_alarm) ?>");
            // $('.gwc_chk').prop("checked", false);
            var state = $(this).prop('checked');
            var id = $(this).attr('id');
            var order = id.substring(3, 4);
            if (id == "chk4") {
                $.ajax({
                    type: "POST",
                    url: "/ajax/get_mem_address.php",
                    data: {
                        mode: 'reseller_state',
                        mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data != "1") {
                            $('.gwc_chk').prop("checked", false);
                            alert("본 회원은 리셀러 이상 등급이어야 신청이 가능합니다");
                            return;
                        }
                    }
                });
            }
            if (order > 4) {
                $.ajax({
                    type: "POST",
                    url: "/ajax/get_mem_address.php",
                    data: {
                        mode: 'cur_gwcleb_state',
                        mem_id: '<?= $_SESSION['iam_member_id'] ?>'
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data != "1") {
                            $('.gwc_chk').prop("checked", false);
                            alert("본 회원은 공급회원 이상 등급이어야 신청이 가능합니다");
                            location.reload();
                        }
                    }
                });
            }
            $('.gwc_chk').prop("checked", false);
            $("#gwc_leb").val(order);
            $(this).prop('checked', true);
        })
    });

    function save_form(frm, modify) {
        if ($('#name').val() == "") {
            alert('성명을 입력해 주세요.');
            return;
        }
        if (gwc_req != "Y") {
            /* if($('#zy').val() == "") {
                alert('소속을 입력해 주세요.');
                return;                                                
            } */

            if ($('#add1').val() == "") {
                alert('주소를 입력해 주세요.');
                return;
            }

            /* if($('#add2').val() == "") {
                alert('주소 상세정보를 입력해 주세요.');
                return;                                                
            }        
            
            if($('#com_add1').val() == "") {
                alert('회사주소 정보를 입력해 주세요.');
                return;                                                
            }

            if($('#bank_name').val() == "") {
                alert('은행 정보를 입력해 주세요.');
                return;                                                
            }
            if($('#bank_owner').val() == "") {
                alert('예금주 정보를 입력해 주세요.');
                return;                                                
            }
            if($('#bank_account').val() == "") {
                alert('계좌번호를 입력해 주세요.');
                return;                                                
            }

            if($('#email_1').val() == "") {
                alert('이메일을 입력해 주세요.');
                return;                                                
            }        
            if($('#email_2').val() == "") {
                alert('이메일을 입력해 주세요.');
                return;                                                
            } */
            var msg = modify ? "수정하시겠습니까?" : "등록하시겠습니까?";
        } else {
            var msg = "굿마켓 가입하시겠습니까?";
        }
        var form = $('#edit_form')[0];
        var formData = new FormData(form);
        formData.append("profile", $("#profile")[0].files[0])
        formData.append("gwc_req", gwc_req);
        if (confirm(msg)) {
            $.ajax({
                type: "POST",
                url: "/ajax/ajax.member.php",
                processData: false,
                contentType: false,
                data: formData,
                success: function(data) {
                    $("#ajax_div").html(data);
                    if ($("#link").val() != "") {
                        if ($("#link").val() == "pay_point") {
                            history.go(-1);
                        } else {
                            location.href = $("#link").val() + ".php";
                        }
                    }
                }
            });
        }
    }

    function chk_sms() {
        if ($('input[name=mobile_1]').val() == "" || $('input[name=mobile_2]').val() == "" || $('input[name=mobile_3]').val() == "") {
            alert('인증받으실 전화번호를 입력해주세요.')
            return;
        }
        $.ajax({
            type: "POST",
            url: "/ajax/join.proc.php",
            cache: false,
            dataType: "json",
            data: {
                mode: "send_sms",
                rphone: $('input[name=mobile_1]').val() + "-" + $('input[name=mobile_2]').val() + "-" + $('input[name=mobile_3]').val()
            },
            success: function(data) {
                if (data.result == "success") {
                    $('#check_rnum').val("Y");

                } else {
                    $('#check_rnum').val("");
                }
                alert(data.msg);
            }
        })
    }
    $(function() {
        $('#checkAll').on("change", function() {
            if ($('#checkAll').prop("checked") == true) {
                $("#checkPersonal").prop("checked", true);
                $("#checkTerms").prop("checked", true);
                $("#checkReceive").prop("checked", true);
                $("#checkThirdparty").prop("checked", true);
            } else {
                $("#checkPersonal").prop("checked", false);
                $("#checkTerms").prop("checked", false);
                $("#checkReceive").prop("checked", false);
                $("#checkThirdparty").prop("checked", false);
            }
        })
    });

    function id_check(frm, frm_str) {
        if (!frm.id.value) {
            frm.id.focus();
            return
        }
        var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
        if (!pattern.test(frm.id.value)) {
            alert('영문 소문자와 숫자만 사용이 가능합니다.');
            frm.id_status.value = ''
            frm.id.value = ''
            frm.id.focus();
            return;
        }

        $.ajax({
            type: "POST",
            url: "/ajax/ajax.php",
            data: {
                id_che: frm.id.value,
                id_che_form: frm_str
            },
            success: function(data) {
                $("#ajax_div").html(data);
            }
        });
    }

    function inmail(v, id) {
        $("#" + id).val(v);
    }

    function searchManagerInfo() {
        var winw_pop = window.open('searchManagerInfo.php', 'popup', 'width=624, height=744, toolbar=no, menubar=no, scrollbars=yes, resizable=yes,scrolling=yes');
        if (winw_pop == null) {
            alert('팝업 차단을 해제 해 주세요.');
        } else {
            winw_pop.focus();
        }

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
                $("#ajax_div").html(data);
                alert('저장되었습니다.');
            }
        });
        return false;
    }

    function showInfo() {
        if ($('#outLayer').css("display") == "none") {
            $('#outLayer').show();
        } else {
            $('#outLayer').hide();
        }
    }

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

    //비밀번호 보안등급
    function pwd_check(i) {}
    //비밀번호 재확인
    function pwd_cfm_check(i) {
        if ($('#pwd_cfm').val() != $('#new_pwd').val()) {
            alert("두번 입력한 비밀번호가 틀립니다.");
            return;
        }
    }

    function pwd_change(frm, i) {
        if ($('#pwd_cfm').val() != $('#new_pwd').val()) {
            alert("두번 입력한 비밀번호가 틀립니다.");
            return;
        }
        if (confirm('변경하시겠습니까?')) {
            $.ajax({
                type: "POST",
                url: "/ajax/ajax_session.php",
                data: {
                    pwd_change_old_pwd: $('#old_pwd').val(),
                    pwd_change_new_pwd: $('#new_pwd').val(),
                    pwd_change_status: i
                },
                success: function(data) {
                    console.log(data);
                    $("#ajax_div").html(data)
                }
            });
        }
    }

    $("#value_region_province").on('change', function() {
        var province = $(this).val();
        $("#value_region_city").html('<option value="">-시/군/구-</option>');
        $("#value_region_town").html('<option value="">-읍/면/동-</option>');
        $("#add1").val("");
        $.post('/location.php', {
            'type': 'cities',
            'location': province
        }, function(res) {
            if (res.status == '1') {
                var locations = res.locations;
                var html = '<option value="">-시/군/구-</option>';
                for (var i = 0; i < locations.length; i++) {
                    var location = locations[i];
                    html += '<option value="' + location + '">' + location + '</option>';
                }
                $("#value_region_city").html(html);
            }
        }, 'json');
    });

    $("#value_region_city").on('change', function() {
        var city = $(this).val();
        $("#value_region_town").html('<option value="">-읍/면/동-</option>');
        $("#add1").val("");
        $.post('/location.php', {
            'type': 'towns',
            'location': city
        }, function(res) {
            if (res.status == '1') {
                var locations = res.locations;
                var html = '<option value="">-읍/면/동-</option>';
                for (var i = 0; i < locations.length; i++) {
                    var location = locations[i];
                    html += '<option value="' + location + '">' + location + '</option>';
                }
                $("#value_region_town").html(html);
            }
        }, 'json');
    });

    $("#value_region_town").on('change', function() {
        if ($(this).val() != "") {
            var address = $("#value_region_province").val() + " " + $("#value_region_city").val() + " " + $(this).val();
            $("#add1").val(address);
        }
    });

    function member_leave() {
        if ($('#leave_pwd').val() == "") {
            alert('비밀번호를 입력해주세요.');
            return;
        }
        if ($('#leave_liyou').val() == "") {
            alert('탈퇴사유를 입력해주세요.');
            return;
        }
        if (confirm('탈퇴하시겠습니까?')) {
            $.ajax({
                type: "POST",
                url: "/ajax/ajax_session.php",
                data: {
                    member_leave_pwd: $('#leave_pwd').val(),
                    member_leave_liyou: $('#leave_liyou').val()
                },
                success: function(data) {
                    $("#ajax_div").html(data);

                }
            });
        }
    }

    function go_page(val) {
        if (val == "cash") {
            location.href = "https://kiam.kr/iam/contents_gwc.php?contents_idx=54065&gwc=Y";
        } else {
            gwc_tab();
        }
    }

    // 5자리 우편번호 도로명 우편번호 창
    function win_zip(frm_name, frm_zip, frm_addr1, frm_addr2, frm_addr3, frm_jibeon) {
        var url = "https://<?= $HTTP_HOST ?>/iam/zip.php?frm_name=" + frm_name + "&frm_zip=" + frm_zip + "&frm_addr1=" + frm_addr1 + "&frm_addr2=" + frm_addr2 + "&frm_addr3=" + frm_addr3 + "&frm_jibeon=" + frm_jibeon;
        // win_open(url, "winZip", "483", "600", "yes");
        $("#order_address").html("<iframe id='ALLAT_MOBILE_FRAME' name='ALLAT_MOBILE_FRAME' src='" + url + "' frameborder=0 width=100%px height=100%px scrolling=no></iframe>");
        document.getElementById("order_address").style.width = "100%";
        document.getElementById("order_address").style.height = "100%";
        document.getElementById("order_address").style.left = 0 + "px";
        document.getElementById("order_address").style.top = 0 + "px";
        document.getElementById("order_address").style.display = "block";
        $('body,html').animate({
            scrollTop: 0,
        }, 100);
    }

    function show_modal() {
        alert("1. 알지 GPT는 자신의 API 키로 이용할 수 있습니다.\n2. 혹시 사용하다 알지 GPT 작동이 안되면 API 키 이용한도 초과된 것이므로, 유료 API 키를 입력하거나, 다른 명의의 API키를 다시 발급받아 입력하세요.");
    }
</script>