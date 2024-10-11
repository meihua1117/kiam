<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
$repo_id = $_GET['repo'];

$sql = "select * from gn_report_form where id=$repo_id";
$res = mysqli_query($self_con,$sql);
$row = mysqli_fetch_array($res);
if($row['status'] == 0){
    echo "<script>alert('노출승인되지 않은 리포트입니다.');location='/';</script>";
}
$sql = "update gn_report_form set visit = visit + 1 where id=$repo_id";
mysqli_query($self_con,$sql);
$url_refer = str_replace("&", "###", $_SERVER['REQUEST_URI']);
?>
<style>
.report_item{
    width: 100%;
    margin-top: 10px;
    padding-left: 30px;
    padding-right: 20px;
    display: table;
}
.report_item:last-child {
    margin-bottom: 20px;
}
.report_item_title{
    writing-mode: vertical-lr;
    text-orientation: upright;
    color: #ffffff;
    background-color: #7f7f7f;
    font-size: 16px;
    margin-bottom: 5px;
    padding: 5px 8px;
    display: table-cell;
    text-align: center;
}
.report_item_div{
    width: 100%;
    padding-left: 10px;
    display: table-cell;
    vertical-align:top;
}
.report_item_body:last-child {
    margin-bottom: 0;
}
.report_item_body{
    width: 100%;
    display: table;
    margin-bottom: 5px;
}
.report_item_tag{
    display: table-cell;
    font-size: 15px;
    background-color: #bfbfbf;
    width: 30%;
    text-align: center;
}
.report_item_tag1{
    display: table-cell;
    font-size: 15px;
    width: 100%;
    text-align: left;
    border: 1px solid #ddd;
    padding: 5px 10px;
}
.report_item_tag1:first-line {
    line-height: 0px;
}
.report_item_tag2{
    display: block;
    font-size: 15px;
    width: 100%;
    text-align: left;
    border: 1px solid #ddd;
    padding: 5px 10px;
    background-color: #bfbfbf;
}
.report_item_tag_val{
    display: table-cell;
    padding-left: 5px;
    font-size: 15px;
    width: 70%;
}
.report_item_input{
    width: 100%;
    height: 28px;
    padding: 5px 10px;
    background-color: #fff;
    border: 1px solid #ccc;
    font-size: 12px;
    line-height: 16px;
}
.report_item_container{
    width: 100%;
    height: 100%;
    padding: 5px 10px;
    background-color: #fff;
    border: 1px solid #ccc;
    font-size: 12px;
}
.kbw-signature {
    width: 100%;
    height: 100px;
    background-color: #f1f1f1;
    display: inline-block;
    border: 1px solid #eee;
    -ms-touch-action: none;
}
.button-wrap {
    margin-top: 30px;
    font-size: 0;
    text-align: center;
    margin-bottom: 30px;
}
.button {
    display: inline-block;
    width: 120px;
    margin: 0 5px;
    padding: 5px 0;
    color: #fff;
    font-size: 16px;
    font-weight: 500;
    line-height: 20px;
    text-align: center;
}
.is-pink {
    background-color: #92d050;
}
.is-grey {
    background-color: #bbb;
}
</style>
<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<!--오픈그래프 (웹페이지 미리보기 -페이스북, 카카오톡)-->
	<meta property="og:title" content="<?=$row['title']?>">  <!--제목-->
	<meta property="og:description" content="<?=$row['descript']?>">  <!--내용-->
	<!--오픈그래프 끝-->
	<title>아이엠 하나이면 홍보와 소통이 가능하다</title>
	<link rel="shortcut icon" href="img/common/iconiam.ico">
	<link rel="stylesheet" href="css/notokr.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/new_style.css">
	<link rel="stylesheet" href="css/grid.min.css">
	<link rel="stylesheet" href="css/slick.min.css">
	<link rel="stylesheet" href="css/style_j.css">
	<!-- ########## TODO COMMENT FOR TEST  패치할떄 해제해야함 ###########  -->
	<!--script src="//developers.kakao.com/sdk/js/kakao.min.js" charset="utf-8"></script-->
	<script src="js/jquery-3.1.1.min.js"></script>
	<script src="js/slick.min.js"></script>
	<script src="js/main.js"></script>
	<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
	<link rel='stylesheet' href='../plugin/toastr/css/toastr.css' />
	<script src='../plugin/toastr/js/toastr.min.js'></script>
    <script src="js/jquery-ui-1.10.3.custom.js"></script>
    <script src="js/jquery-signature.js"></script>
    <script src="js/jquery.report_form.js"></script>
    <script src="js/jquery.ui.touch-punch-improved.js"></script>
</head>
<body>
	<div id="wrap" class="common-wrap">
		<main id="star" class="common-wrap"><!-- 컨텐츠 영역 시작 -->
			<section id="bottom">
				<div class="content-item">
					<div style="margin-top: 20px;margin-left: 20px">
                        <h2 class="title"><?=$row['title']?></h2>
					</div>
                    <div style="margin-top: 10px;margin-left: 20px;margin-right: 20px;border-bottom: 1px solid #ddd;">
                        <h4 class="desc" style="white-space: pre-line;"><?=$row['descript']?></h4>
                    </div>
                    <form id="dform1" name="dform1" method="post" action="<?=$SERVER['PHP_SELF']?>" onsubmit="return checkForm()">
                        <div class="report_item">
                            <input type="radio" name="mem_type" id="no_mem" <?if($_SESSION['iam_member_id'] == "") echo "checked"?>>
                            <label for="no_mem" style="font-size:17px;">비회원</label>
                            <input type="radio" name="mem_type" id="iam_mem" style="margin-left:10px;" <?if($_SESSION['iam_member_id'] != "") echo "checked"?>>
                            <label for="iam_mem" style="font-size:17px;">IAM기존회원</label>
                            <br>
                            <span class="main_info" id="no_txt">비회원 신청시 확인코드와 휴대폰 뒷 4자리를 비번으로 신청정보를 수정 및 취소할 수 있습니다.</span>
                        </div>
                        <div class="report_item">
                            <div class="report_item_div" style="padding-left: 0px">
                                <div class="report_item_body" id="div_account">
                                    <div class="report_item_tag" style="width: 15%;background-color: #ffffff;color: #000000;text-align: left">확인<br>코드</div>
                                    <div class="report_item_tag_val" style="width: 85%">
                                        <div class="input-wrap">
                                            <input type="text" id="id" name="id" value="<?=$_SESSION['iam_member_id']?>" placeholder="6-15자로 입력하세요." style="width:100%; height:40px; border:1px #cacaca solid;font-size: 14px;" />
                                            <input type="button" class="button is-grey" style="margin: 5px;color: #000000" value="중복확인"  onClick="id_check('dform1')" />
                                            <span id='id_html' style="width: 60px;"></span>
                                            <input type="hidden" name="id_status" id="id_status" value="" itemname='아이디중복확인' required />
                                            &nbsp;&nbsp; <p id="id_chk_str" style="display: inline-block;">※ 확인코드 중복확인을 클릭해주세요.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="report_item_body">
                                    <div class="report_item_tag" style="width: 15%;background-color: #ffffff;color: #000000;text-align: left">이름</div>
                                    <div class="report_item_tag_val" style="width: 85%">
                                        <input type="text" name="name" style="width:100%; height:40px; border:1px #cacaca solid;font-size: 14px;" id="name" value="" />
                                    </div>
                                </div>
                                <div class="report_item_body">
                                    <div class="report_item_tag" style="width: 15%;background-color: #ffffff;color: #000000;text-align: left">휴대폰</div>
                                    <div class="report_item_tag_val" style="width: 85%">
                                        <input type="tel" name="mobile" style="width:100%; height:40px; border:1px #cacaca solid;font-size: 14px;"
                                                   id="tel" onkeyup="checkPhoneNumber()" value="" placeholder="'-'를 빼고 입력(예 : 01012345678)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?
                    $sql1 = "select * from gn_report_form1 where form_id=$repo_id order by item_order";
                    $res1 = mysqli_query($self_con,$sql1);
                    while($row1 = mysqli_fetch_array($res1)){?>
                        <div class="report_item">
                            <?if($row1['item_title'] != ""){?>
                            <div class="report_item_title">
                                <?=$row1['item_title']?>
                            </div>
                            <?}?>
                            <div class="report_item_div" <?if($row1['item_title'] == ""){echo "style='padding-left:0px'";}?>>
                            <?if($row1['item_type'] == 1 || $row1['item_type'] == 2){?>
                                <div class="report_item_body">
                                    <div class="report_item_tag" style="width: 100%;text-align: left;padding:5px 10px">
                                        <?=$row1['item_req']==""?"서비스상품을 선택해주세요.":$row1['item_req'];?>
                                    </div>
                                </div>
                            <?}
                            $sql2 = "select * from gn_report_form2 where form_id=$repo_id and item_id = $row1[id] order by id";
                            $res2 = mysqli_query($self_con,$sql2);
                            while($row2 = mysqli_fetch_array($res2)){
                                if($row1['item_type'] == 0){
                            ?>
                                <div class="report_item_body">
                                    <div class="report_item_tag">
                                        <?=$row2['tag_name']?>
                                    </div>
                                    <div class="report_item_tag_val">
                                        <input type="text" class="report_item_input" name="<?=$row2['tag_id']?>">
                                    </div>
                                </div>
                            <?  }else if($row1['item_type'] == 1){?>
                                <div class="report_item_body">
                                    <div class="report_item_tag1">
                                        <input type="checkbox" class="report_item_check" style="margin: 2px 0px" name="<?=$row2['tag_id']?>">
                                        <?=$row2['tag_name']?>
                                    </div>
                                </div>
                            <?  }else if($row1['item_type'] == 3){?>
                                    <div class="report_item_body">
                                        <div class="report_item_tag2" style="">
                                            <?=$row2['tag_name']?>
                                        </div>
                                        <div class="report_item_tag2" style="background-color: transparent;margin-top: 5px;padding: 0px">
                                            <textarea class="report_item_input" name="<?=$row2['tag_id']?>" style="border: none;height: auto"><?=$repo_row[$row2['tag_id']]?></textarea>
                                        </div>
                                    </div>
                                <?  }else{
                                    $pre_style = "";
                                    if($row2['tag_name'] != "")
                                        $pre_style = "white-space: pre-line";
                                ?>
                                <div class="report_item_body" style="min-height: 100px">
                                    <div class="report_item_tag1" style="vertical-align: top;<?=$pre_style?>">
                                        <?echo $row2['tag_name']?>
                                        <br>
                                        <?
                                        $link = $row2['tag_link'];
                                        $pos = strpos($row2['tag_link'],"youtu");
                                        $pos_img = stripos($row2['tag_link'],".jpg") + stripos($row2['tag_link'],".jpeg") +stripos($row2['tag_link'],".png") +stripos($row2['tag_link'],".gif") +stripos($row2['tag_link'],".svc");
                                        if($pos >= 0){
                                            if(strpos($row2['tag_link'],"youtu.be") != false ){
                                                $link = str_replace("youtu.be/","www.youtube.com/embed/",$link);
                                            }else if(strpos($row2['tag_link'],"playlist") != false){
                                                $code = substr($row2['tag_link'],strpos($row2['tag_link'],"playlist") + 14);
                                                $link = "https://www.youtube.com/embed/?listType=playlist&list=$code";
                                            }else if(strpos($row2['tag_link'],"watch?v=") != false){
                                                $link = str_replace("watch?v=", "embed/",$link);
                                            }
                                        }
                                        if($pos == false && $pos_img == false){?>
                                            <a href="<?=$link?>" target="_blank"><?=cut_str($link,30)?></a>
                                        <?}else if($pos_img > 0){?>
                                            <img src="<?=$link?>" style = "width:100%">
                                        <?}else{?>
                                            <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px">
                                                <iframe style="width:100%;height:300px;border-radius: 10px;" src="<?=$link?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                                                </iframe>
                                            </div>
                                        <?}?>
                                    </div>
                                </div>
                            <?  }
                            }?>
                            </div>
                        </div>
                    <?}?>
                    <?if($row['sign_visible'] == 1){?>
                    <div class="report_item">
                        <div id="signature-pad" class="m-signature-pad">
                            <p class="marb3"><strong class="blink">아래 박스안에 서명을 남겨주세요.</strong><button type="button" id="clear" class="btn_ssmall bx-white">서명 초기화</button></p>
                            <div id="sign"></div>
                            <textarea name="signatureJSON" id="signatureJSON" style="display: none" readonly=""></textarea>
                        </div>
                    </div>
                    <?}?>
                    <div class="button-wrap">
                        <a href="javascript:save_format(<?=$repo_id?>)" class="button is-pink">제출하기</a>
                        <a onclick="closeReport();return false;" class="button is-grey">취소하기</a>
                        <!--a href="javascript:signin();" class="button" style="background-color: #84dbd8" id="signBtn">확인하기</a-->
                    </div>
                    <div id="ajax_div" style="display:none"></div>
			</section>
		</main>
	</div>
</body>
<script>
    function closeReport(){
        var new_window = open(location, '_self');
        new_window.close();
        return false;
    }
    function save_format(repo_id) {
        if($("#no_mem").prop('checked')){
            if($("#no_mem").prop('checked') && $("#id_html").text() == ''){
                alert('아이디 중복확인을 해주세요.');
                return false;
            }
            $.ajax({
                type:"POST",
                url:"/ajax/ajax.report.php",
                dataType:"json",
                data:{
                    method:"auto_login",
                    id:$("#id").val(),
                    name:$("#name").val(),
                    phone:$("#tel").val(),
                    repo:repo_id
                },
                success:function(data){
                    $.ajax({
                        type:"POST",
                        url:"/admin/ajax/login_iamuser.php",
                        data:{
                            one_id:data.id,
                            mem_pass:data.pass,
                            mem_code:data.code
                        },
                        success:function(){
                            reg_report(repo_id);
                        },
                        error: function(){
                            alert('초기화 실패');
                        }
                    });
                }
            });
        }else{
            reg_report(repo_id);
        }
    }
    function reg_report(repo_id){
        var jsonObj = {};
        jsonObj["index"] = repo_id;
        var id = $("#id").val();
        var name = $('input[name =name]').val();
        var phone = $('input[name =mobile]').val();
        jsonObj["id"] = id;
        jsonObj["name"] = name;
        jsonObj["phone"] = phone;
        var sign = $('#signatureJSON').val();
        jsonObj["item"] = [];
        $(".report_item_input").each(function(){
            var item = {};
            item ["key"] = $(this).prop("name");
            item ["value"] = $(this).val();
            jsonObj["item"].push(item);
        });
        $(".report_item_check").each(function(){
            var item = {};
            item ["key"] = $(this).prop("name");
            item ["value"] = $(this).prop("checked")==true?1:0;
            jsonObj["item"].push(item);
        });
        if(confirm("등록하시겠습니까")){
            $.ajax({
                type:"POST",
                url:"/ajax/ajax.report.php",
                dataType:"json",
                data:{
                    method:"reg_report",
                    data:JSON.stringify(jsonObj),
                    sign:sign
                },
                success:function(data){
                    alert("성공적으로 등록되었습니다.");
                    location.href="mypage_report_list.php";
                }
            });
        }
    }
    //아이디 중복확인
    function id_check(frm_str) {
        if (!$("#id").val()) {
            alert("아이디를 입력해주세요.");
            $("#id").focus();
            return;
        }
        if ($("#id").val().length < 4) {
            alert('아이디는 4자 이상 사용이 가능합니다.');
            $("#id").focus();
            return;
        }
        var pattern = /(^([a-z0-9]+)([a-z0-9_]+$))/;
        if (!pattern.test($("#id").val())) {
            document.getElementById('id_html').innerHTML = '올바른 회원아이디 형식이 아닙니다.';
            $("#id_status").val('');
            $("#id").val('');
            $("#id").focus();
            return
        }
        else
            document.getElementById('id_html').innerHTML = '';
        $.ajax({
            type: "POST",
            url: "/ajax/ajax.php",
            data: {
                id_che: $("#id").val(),
                id_che_form: frm_str
            },
            success: function (data) {
                $("#ajax_div").html(data)
            }
        })
    }
    function load_userinfo() {
        $.ajax({
            type: "GET",
            url: "/ajax/event.userinfo.php",
            cache: false,
            dataType: "json",
            success: function (data) {
                if (data.result == "success") {
                    $("#id").val('<?=$_SESSION['iam_member_id']?>');
                    $('input[name =name]').val(data.card_name);
                    $('input[name =mobile]').val(data.card_phone);
                }else {
                    location.href = "/m/login.php?refer2=<?=$url_refer?>";
                }
            }
        });
    }
    function checkPhoneNumber() {
        if ($('#tel').val().length >= 3){
            if ($('#tel').val().substring(0, 3) != '010' && $('#tel').val().substring(0, 3) != '011' && $('#tel').val().substring(0, 3) != '016' && $('#tel').val().substring(0, 3) != '017' && $('#tel').val().substring(0, 3) != '018' && $('#tel').val().substring(0, 3) != '019') {
                alert('전화번호 첫번째 자리는 010, 011, 016, 017, 018, 019만 가능합니다.');
                $('#tel').val("");
                $('#tel').focus();

            }
        }
    }
    /*function signin(){
        if($("#no_mem").prop('checked') && $("#id_html").text() == ''){
            alert('아이디 중복확인을 해주세요.')
            return false;
        }
        $.ajax({
            type:"POST",
            url:"/ajax/ajax.report.php",
            dataType:"json",
            data:{
                method:"auto_login",
                id:$("#id").val(),
                name:$("#name").val(),
                phone:$("#tel").val()
            },
            success:function(data){
                alert("성공적으로 등록되었습니다.");
                gotoLogin(data.id,data.pass,data.code);
            }
        });
    }*/
    function gotoLogin(id, pass, code) {
        $.ajax({
            type:"POST",
            url:"/admin/ajax/login_iamuser.php",
            data:{
                one_id:id,
                mem_pass:pass,
                mem_code:code
            },
            success:function(){
                location.href = "/iam/mypage_report_list.php";
            },
            error: function(){
                alert('초기화 실패');
            }
        });
        return false;
    }
    $(function(){
        $("input[name=mem_type]").on("change", function (){
            if($("#no_mem").prop('checked')){
                $("#no_txt").show();
                $('input[name =name]').val('');
                $('input[name =mobile]').val('');
                $("#div_account").show();
                $("#signBtn").show();
            }
            else{
                $("#no_txt").hide();
                $("#div_account").hide();
                $("#signBtn").hide();
                load_userinfo();
            }
        });
        if('<?=$_SESSION['iam_member_id']?>' != ""){
            $("#no_txt").hide();
            $("#div_account").hide();
            $("#signBtn").hide();
            load_userinfo();
        }
    });

</script>
</html>
