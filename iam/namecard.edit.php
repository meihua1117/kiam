<?include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";?>
<?
$card_idx = $_GET['card_num'];
$sql="select * from Gn_Iam_Name_Card where idx = '$card_idx' and mem_id = '{$_SESSION['iam_member_id']}'";
$result=mysqli_query($self_con,$sql);
$row=mysqli_fetch_array($result);
$card_link = $row['card_short_url'];
$card_title = $row['card_title'];
$card_name = $row['card_name'];
$card_company = $row['card_company'];
$card_position = $row['card_position'];
$card_phone = $row['card_phone'];
$card_email = $row['card_email'];
$card_addr = $row['card_addr'];
$card_map = $row['card_map'];
$card_keyword = $row['card_keyword'];
$next_iam_link = $row[next_iam_link];
$profile_logo = $row['profile_logo'];

$story_online1_text = $row['story_online1_text'];
$story_online2_text = $row['story_online2_text'];
$story_online1 = $row['story_online1'];
$online1_check = $row['online1_check'];
$story_online2 = $row['story_online2'];
$online2_check = $row['online2_check'];
//$card_phone_ = explode('-',$card_phone);
$sql="select mem_name, zy, mem_phone, mem_email, mem_add1,mem_code from Gn_Member where mem_id = '{$_SESSION['iam_member_id']}'";
$result=mysqli_query($self_con,$sql);
$row=mysqli_fetch_array($result);
$member = $_GET['member'];
if($member == 'on') {
	//$card_idx = $row['idx'];
	$card_name = $row['mem_name'];
    if($row[zy])
	    $card_company = $row[zy];
	// $card_position = $row['card_position'];
	$card_phone = $row['mem_phone'];
    if($row['mem_email'])
	    $card_email = $row['mem_email'];
    if($row[mem_add1])
	    $card_addr = $row[mem_add1];
	// $profile_logo = $row['profile_logo'];
	//$card_phone_ = explode('-',$card_phone);
}
$card_email_array = explode('@',$card_email);
?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>명함등록</title>
    <link rel="stylesheet" href="css/notokr.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/grid.min.css">
    <link rel="stylesheet" href="css/slick.min.css">
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/main.js"></script>
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/rlatjd_fun.js"></script>
</head>

<body>
    <div id="wrap" class="common-wrap">
        <main id="register" class="common-wrap">
            <!-- 컨텐츠 영역 시작 -->
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="inner-wrap">
                            <h2 class="title">아이엠 카드정보 수정하기</h2>

                            <section class="input-field">
                                <h3 class="title">아이엠 카드정보 수정입력</h3>
                                <div class="utils clearfix">
                                    <span class="notice">해당정보는 회원가입 정보에서 가져옵니다. <br> 혹시 가져오지 못하는 경우 우측 [회원정보가져오기 아이콘]을
                                        클릭하세요. 그리고 자신의 정보를 수정하거나 빠진정보를 채워넣으세요.</span>
                                </div>
                                <button type="button" style="background:#D8D8D8;float:right"
                                    onclick="javascript:location.href='?card_num=<?=$card_idx?>&member=on'">회원정보가져오기
                                </button>
                                <br>
                                <div class="form-wrap">
                                    <form name="namecard_form" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="check_rnum" id="check_rnum" value="Y">
                                        <input type="hidden" name="mode" id="mode" value="edit">
                                        <input type="hidden" name="mem_id" id="mem_id"
                                            value="<?=$_SESSION['iam_member_id']?>">
                                        <input type="hidden" name="card_idx" id="card_idx" value="<?=$card_idx?>">
                                        <div class="attr-row">
                                            <div class="attr-name">카드제목</div>
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <input type="text" id="card_title" name="card_title" class="input" value="<?=$card_title?>"  maxlength="10">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="attr-row">
                                            <div class="attr-name">성명</div>
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <input type="text" id="card_name" name="card_name" class="input" value="<?=addslashes(htmlspecialchars($card_name))?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="attr-row">
                                            <div class="attr-name">소속/직책</div>
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <input type="text" class="input" id="card_company"
                                                        name="card_company" placeholder="소속/직책을 '/'로 나누어 입력하세요" value="<?=$card_company?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="attr-row">
                                            <div class="attr-name">자기소개</div>
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <input type="text" class="input" id="card_position" required
                                                        name="card_position" placeholder="자신을 한문장으로 소개해봐요" value="<?=$card_position?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="attr-row is-phone">
                                            <div class="attr-name">휴대폰번호</div>
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <input type="text" id="card_phone" name="card_phone" class="input" style="width: 100%" value="<?=$card_phone?>">
                                                </div>
                                            </div>
                                        </div>
                                        <!--div class="attr-row is-mail">
                                            <div class="attr-name">이메일</div>
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <input type="text" id="card_email" name="card_email" class="input" value="<?=$card_email?>">
                                                </div>
                                            </div>
                                        </div-->
                                        <div class="attr-row is-mail">
                                            <div class="attr-name">이메일주소</div>
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <input type="text" style="width:135px" class="input mail-first" name="email_1" required itemname='이메일' id="email_1" value="<?=$card_email_array[0]?>">
                                                    <span class="bridge">@</span>
                                                    <input type="text" style="width:calc( 100% - 235px );"  class="input mail-second" name="email_2" id='email_2' itemname='이메일' required  value="<?php echo $card_email_array[1];?>">
                                                    <select class="select" name="email_3" itemname='이메일' onchange="inmail(this.value,'email_2')">
                                                        <?
                                                        foreach($email_arr as $key=>$v)
                                                        {
                                                            ?>
                                                            <option value="<?=$key?>"><?=$v?></option>
                                                        <?
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="attr-row">
                                            <div class="attr-name">직장주소</div>
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <input type="text" id="card_addr" name="card_addr" class="input"
                                                        placeholder="약도생성에 필요하므로 정확하게 입력하세요" value="<?=$card_addr?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="attr-row">
                                            <div class="attr-name">관심키워드</div>
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <textarea style="height:60px" id="card_keyword" name="card_keyword"
                                                        class="input"
                                                        placeholder="아이엠에서 나를 검색할수 있는 단어 (30개 이내)로 입력하고, 입력시 [,]으로 구분하세요. (예시 : 강사,마케터,변호사,대안학교,노래방, 공부방 등"><?=$card_keyword?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="attr-row">
                                            <div class="attr-name">사이트입력</div>
                                            <input type="hidden" name="story_title4" id="story_title4" value="온라인정보">
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <div class="input-row">
                                                        <input type="text" class="input" name="story_online1_text"
                                                            id="story_online1_text" value="<?=$online1_check=='Y'?$story_online1_text:""?>"
                                                            style="width:40%" placeholder="사이트이름">
                                                        <input type="text" class="input" name="story_online1"
                                                            id="story_online1" value="<?=$online1_check=='Y'?$story_online1:""?>"
                                                            style="width:50%" placeholder="사이트주소 http:// 또는 https://">
                                                        <input type="checkbox" id="radio_01" name="radio_01"
                                                            class="radio" <?if($online1_check=='Y') echo "checked"?>>

                                                    </div>
                                                    <div class="input-row">
                                                        <input type="text" class="input" name="story_online2_text"
                                                            id="story_online2_text" value="<?=$online2_check=='Y'?$story_online2_text:""?>"
                                                            style="width:40%" placeholder="사이트이름">
                                                        <input type="text" class="input" name="story_online2"
                                                            id="story_online2" value="<?=$online2_check=='Y'?$story_online2:""?>"
                                                            style="width:50%" placeholder="사이트주소 http:// 또는 https://">
                                                        <input type="checkbox" id="radio_02" name="radio_01"
                                                            class="radio" <?if($online2_check=='Y') echo "checked"?>>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="attr-row">
                                            <div class="attr-name">명함로고파일</div>
                                            <input type="file" class="input" id="profile_logo" name="profile_logo"
                                                placeholder="파일" value="<?=$profile_logo?>">
                                            <?=$profile_logo?>
                                            <button type="button" style="background:#D8D8D8"
                                                onclick="javascript:ImageDel('<?=$profile_logo?>');">삭제</button>
                                        </div>
                                        <div class="attr-row">
                                            <div class="attr-name">다른IAM링크</div>
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <input type="text" id="next_iam_link" name="next_iam_link" class="input" value="<?=$next_iam_link?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="attr-row">
                                            <div class="attr-name">IAM앱설치하기</div>
                                            <div class="attr-value">
                                                <div class="input-wrap">
                                                    <textarea style="height:70px" class="input" name="keywords"
                                                        id="keywords"
                                                        placeholder="IAM앱을 설치하면 콜백문자, 대량문자, 포털디비수집, 365일 자동메시지발송, 자동예약메시지기능, 아이엠기능 등을 사용할수 있습니다."></textarea>
                                                </div>
                                            </div>
                                            <div class="utils clearfix">
                                                <a href="https://play.google.com/store/apps/details?id=mms.onepagebook.com.onlyonesms" target="_blank"
                                                    class="button">IAM앱<br>설치하기</a>
                                            </div
                                        </div>
                                    </form>
                                </div>
                            </section>

                            <div class="button-wrap">
                                <a href="javascript:history.back()" class="button is-grey">다음에수정</a>
                                <a href="javascript:namecard_check('<?=$card_link.$row['mem_code']?>');" class="button is-pink">아이엠수정</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main><!-- // 컨텐츠 영역 시작 -->
    </div>
    <script>
    function inmail(v,id)
    {
        $("#"+id).val(v);
    }
    function chk_sms() {
        if ($('input[name=card_phone]').val() == "") {
            alert('인증받으실 전화번호를 입력해주세요.')
            return;
        }
        $.ajax({
            type: "POST",
            url: "ajax/namecard.phone.proc.php",
            cache: false,
            dataType: "json",
            data: {
                mode: "send_sms",
                rphone: $('input[name=card_phone]').val()
                //rphone: $('input[name=card_phone1]').val() + "-" + $('input[name=card_phone2]').val() + "-" + $('input[name=card_phone3]').val()
            },
            success: function(data) {
                if (data.result == "success")
                    $('#check_rnum').val("Y");
                else
                    $('#check_rnum').val("");

                alert(data.msg);
            }
        })
    }

    function namecard_check(card_link) {
        var formData = new FormData();

        if (!$('#card_name').val()) {
            alert("성명을 입력하세요.");
            $('#card_name').focus();
            return false;
        }

        if (!$('#card_company').val()) {
            alert("소속과 직책을 입력하세요.");
            $('#card_company').focus();
            return false;
        }

        if (!$('#card_position').val()) {
            alert("자기를 한문장으로 소개해보세요");
            $('#card_position').focus();
            return false;
        }

        // if($('input[name=card_phone1]').val() == "" || $('input[name=card_phone2]').val() == "" || $('input[name=card_phone3]').val() == "") {
        //     alert('휴대폰번호를 입력하세요.')
        //     return false;
        // }

        // if(!$('#card_email').val()) {
        // 	alert("이메일을 입력하세요.");
        // 	$('#card_email').focus();
        // 	return false;
        // }
        //
        // if(!$('#card_addr').val()) {
        // 	alert("주소를 입력하세요.");
        // 	$('#card_addr').focus();
        // 	return false;
        // }

        // if(!$('#profile_logo').val()) {
        // 	alert("명함로고(파일이미지)를 첨부하세요.");
        // 	$('#profile_logo').focus();
        // 	return false;
        // }

        formData.append('mode', $('#mode').val());
        formData.append('mem_id', $('#mem_id').val());
        formData.append('card_idx', $('#card_idx').val());
        formData.append('card_title', $('#card_title').val());
        formData.append('card_name', $('#card_name').val());
        formData.append('card_company', $('#card_company').val());
        formData.append('card_position', $('#card_position').val());
        //formData.append('card_phone', $('#card_phone1').val() + "-" + $('#card_phone2').val() + "-" + $('#card_phone3').val());
        formData.append('card_phone', $('#card_phone').val());
        formData.append('card_email', $('#email_1').val() + "@" + $('#email_2').val());
        formData.append('card_addr', $('#card_addr').val());
        formData.append('card_map', $('#card_map').val());
        formData.append('card_keyword', $('#card_keyword').val());
        formData.append('next_iam_link', $('#next_iam_link').val());

        formData.append('story_title4', $('#story_title4').val());
        formData.append('story_online1_text', $('#story_online1_text').val());
        formData.append('story_online2_text', $('#story_online2_text').val());
        formData.append('story_online1', $('#story_online1').val());
        formData.append('story_online2', $('#story_online2').val());

        if ($('#radio_01').is(":checked")) {
            formData.append('online1_check', "Y");
        } else {
            formData.append('online1_check', "N");
        }
        if ($('#radio_02').is(":checked")) {
            formData.append('online2_check', "Y");
        } else {
            formData.append('online2_check', "N");
        }

        if ($('#profile_logo')[0].files.length) {
            formData.append('uploadFile', $('#profile_logo')[0].files[0]);
        }


        // for (var value of formData.values()) {
        //   console.log(value);
        // }

        $.ajax({
            type: "POST",
            url: "ajax/namecard.proc.php",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
		alert("명함수정이 완료 되었습니다.");
                location.href = "/index.php?"+card_link;
            }
        })
    }

    //대표이미지 삭제
    function ImageDel(img_name) {
        var formData = new FormData();

        formData.append('mode', 'img_del');
        formData.append('mem_id', $('#mem_id').val());
        formData.append('card_idx', $('#card_idx').val());
        formData.append('img_name', img_name);

        $.ajax({
            type: "POST",
            url: "ajax/namecard.proc.php",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                console.log(data);
                // alert(data);
                location.reload();
            }
        })
    }
    </script>
</body>

</html>