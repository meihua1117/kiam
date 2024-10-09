<?php 
include "inc/header.inc.php";
extract($_GET);

$gpt_qu = get_search_key('gpt_question_example');
$gpt_an = get_search_key('gpt_answer_example');
$gpt_qu_arr = explode("||", $gpt_qu);
$gpt_an_arr = explode("||", $gpt_an);

// if(!$member_iam[gpt_chat_api_key]){
//     echo '<script>alert("회원정보에서 본인의 API 키를 입력해주세요."); location.href="mypage.php";</script>';
// }
?>
<link rel="stylesheet" href="/iam/css/button.css">
<link rel="stylesheet" href="/iam/css/style_gwc.css">

<script src="js/remarkable.js"></script>
<script src="js/jquery.cookie.min.js"></script>
<script src="js/highlight.min.js"></script>
<script src="js/layer.min.js" type="application/javascript"></script>
<script src="js/chat.js"></script>
<style>
    .wset {
        background: #f33e31;
        border: 1px solid #f33e31;
        color: #fff !important;
    }
    .sound_only {
        display: inline-block;
        position: absolute;
        top: 0;
        left: 0;
        margin: 0 !important;
        padding: 0 !important;
        width: 1px !important;
        height: 1px !important;
        font-size: 0 !important;
        line-height: 0 !important;
        overflow: hidden;
    }
    table th {
        background-color: #f8f8f8;
        white-space: nowrap;
        text-overflow: ellipsis;
        line-height: 1.3em;
    }
    .fc_999 {
        color: #999999 !important;
    }
    .padt3 {
        padding-top: 3px !important;
    }
    .btn_small:hover{
        background:lightgrey;
    }
    #content_title:after {
        display: none;
    }
    .chat_btn{
        color: white;
        border-radius: 7px;
        background-color: red;
        font-size: 25px;
        margin-top: 15px;
        padding: 7px 25px;
    }
    #answer_side, #answer_side1, #answer_side2{
        width: 90%;
        height: 500px;
        background-color: white;
        /* margin-right: auto; */
        margin-left: 10px;
        border-radius: 10px;
        margin-top: 12px;
        padding: 35px 20px 10px 20px;
        overflow: auto;
        text-align: left;
        position: relative;
    }
    .search_keyword {
        position: relative;
        width: 88%;
        margin-left: 10px;
        margin-top: 10px;
    }
    .search_keyword textarea {
        width: 99%;
        height: 58px;
        padding: 17px 60px 0 25px;
        border-width: 0;
        border-radius: 15px;
        border: 2px solid transparent;
        background-color: #fff;
        outline-width: 0;
        box-shadow: 0 5px 10px -5px rgb(0 0 0 / 30%);
        -webkit-transition: border-color 1000ms ease-out;
        transition: border-color 1000ms ease-out;
    }
    .send_ask{
        position: absolute;
        top: 0;
        right: 0;
        width: 58px;
        height: 92%;
        left: 92%;
        background-color:white;
        border-radius:20px;
    }
    #gpt_req_list_title{
        float: left;
        padding: 7px;
        margin-left: 40px;
        background-color: #f18484;
        border-radius: 10px;
    }
    .history{
        position: absolute;
        bottom: 25%;
        right: 20px;
    }
    .gpt_act{
        position: relative;
        height: 35px;
    }
    .newpane, .newpane:hover{
        background-color: black;
        color: white;
        padding: 4px;
        border-radius: 10px;
        position: absolute;
        bottom: 15%;
        right: 20px;
    }
    @media only screen and (max-width: 720px) {
        .send_ask{
            position: absolute;
            top: 0;
            right: 0;
            width: 50px;
            height: 92%;
            left: 92%;
            background-color:white;
            border-radius:20px;
        }
    }
    @media only screen and (max-width: 600px) {
        .send_ask{
            position: absolute;
            top: 0;
            right: 0;
            width: 50px;
            height: 92%;
            left: 91%;
            background-color:white;
            border-radius:20px;
        }
        .chat_btn{
            color: white;
            border-radius: 7px;
            background-color: red;
            font-size: 19px;
            margin-top: 15px;
            padding: 7px 25px;
        }
    }
    @media only screen and (max-width: 450px) {
        #answer_side, #answer_side1, #answer_side2{
            width: 85%;
            height: 500px;
            background-color: white;
            /* margin-right: auto; */
            margin-left: 10px;
            border-radius: 10px;
            margin-top: 12px;
            padding: 35px 20px 10px 20px;
            overflow: auto;
            text-align: left;
            position: relative;
        }
        .search_keyword {
            position: relative;
            width: 84%;
            margin-left: 10px;
            margin-top: 10px;
        }
        .send_ask{
            position: absolute;
            top: 0;
            right: 0;
            width: 50px;
            height: 92%;
            left: 87%;
            background-color:white;
            border-radius:20px;
        }
        .history{
            position: absolute;
            bottom: 25%;
            right: 10px;
        }
        .newpane{
            background-color: black;
            color: white;
            padding: 4px;
            border-radius: 10px;
            position: absolute;
            bottom: 15%;
            right: 10px;
        }
        .chat_btn{
            color: white;
            border-radius: 7px;
            background-color: red;
            font-size: 17px;
            margin-top: 15px;
            padding: 7px 25px;
        }
    }
    .chat_answer{
        word-break: break-all;
        word-wrap: break-word;
        white-space: pre-wrap;
    }
    .article-title{
        border-bottom: 1px solid lightgrey;
        margin-bottom: 15px;
    }
    .article-content{
        display: grid;
        margin-bottom: 15px;
    }
    .hided{
        display: none;
    }
    .copy_msg{
        position: absolute;
        right: 10px;
        top: 10px;
    }
</style>
<div id="container" class="sub_wrap" style="margin-top: 85px;text-align:center;">
    <div id="intro_side" style="position:relative;">
        <button onclick="show_chat()" class="chat_btn" style="position:absolute;right: 10px;">+ AI와 대화하기</button>
        <img src="/iam/img/ai_chat_img.png" style="width: 100%;">
        <button onclick="show_chat()" class="chat_btn">+ AI와 대화하기</button>
    </div>

    <div id="chat_side" hidden>
        <!-- <div id="content_title" style="padding-bottom:20px;">
            <span style="font-size: 25px;color: red;">텍스트 생성 "알지(ALJI)" 인공지능</span>
        </div> -->

        <div style="background-color: lightgrey;padding: 1px 0px 7px 0px;font-size: 15px;width: 100%;position:relative;">
            <!-- <p><img src="/iam/img/arji_intro_title.png" style="width: 22px;margin-right: 3px;">"알지(ALJI)" 인공지능에게 무엇이든 물어보세요.<br>구체적으로 질문할수록 "알지 AI" 답변이 정교해집니다.</p> -->
            <p id="gpt_req_list_title" hidden>질문답변목록</p>
            <input type="checkbox" id="keep" checked style="display:none;">
            <ul id="answer_side" hidden>
                <a class="copy_msg" href="javascript:copy_msg()"><img src="/iam/img/gpt_res_copy.png" style="height:20px;"></a>
            </ul>
            <ul id="answer_side1">
                <?for($i = 0; $i < count($gpt_qu_arr); $i++){?>
                <li class="article-title" id="q<?=$i?>" onclick="show('<?=$i?>')"><img src="/iam/img/chat_Q.png" style="width:30px;margin-right: 10px;"><span class="chat_title"><?=htmlspecialchars_decode($gpt_qu_arr[$i])?></span><i id="down<?=$i?>" class="fa fa-angle-down" style="font-size: 20px;font-weight: bold;margin-left: 10px;"></i><i id="up<?=$i?>" class="fa fa-angle-up" style="font-size: 20px;font-weight: bold;margin-left: 10px;display:none;"></i></li>
                <li class="article-content hided" id="a<?=$i?>"><img src="/iam/img/chat_A.png" style="width:30px;"><span class="chat_answer" style="margin-left: 35px;"><?=htmlspecialchars_decode($gpt_an_arr[$i])?></span></li>
                <?}?>
            </ul>
            <ul id="answer_side2" hidden>
            </ul>
            <!-- <div class="gpt_act"> -->
            <a class="history" href="javascript:show_req_history('<?=$_SESSION[iam_member_id]?>');"><img src="/iam/img/gpt_req_list.png" style="height: 30px;"></a>
            <a class="newpane" href="javascript:show_new_chat();"><span style="font-size: 4px;">NEW</a>
            <!-- </div> -->
            <div class="search_keyword">
                <input type="hidden" name="key" id="key" value="<?=$member_iam[gpt_chat_api_key]?>">
                <!-- <input type="search" class="search_input" autocomplete="off" name="question" id="question" value="" title="질문을 입력하세요" placeholder="알지AI에게 구체적으로 질문해보세요" onclick="check_login('<?=$_SESSION[iam_member_id]?>')"> -->
                <textarea class="search_input" autocomplete="off" name="question" id="question" value="" title="질문을 입력하세요" placeholder="알지AI에게 구체적으로 질문해보세요" onclick="check_login('<?=$_SESSION[iam_member_id]?>')"></textarea>
                <button type="button" onclick="send_post('<?=$_SESSION[iam_member_id]?>')" class="send_ask"><img src="/iam/img/send_ask.png" alt="전송"></button>
            </div>
        </div>
    </div>
    <!-- // 메시지 상세 보기 팝업 -->
    <div id="intro_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;z-index:2000;">
        <div class="modal-dialog" style="margin: 100px auto;width: fit-content;">
            <!-- Modal content-->
            <div class="modal-content">
                <div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <img src="/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">
                    </button>
                </div>
                <div class = "modal-title" style="width:100%;font-size:18px;text-align: center;background:#82C836;color:white;">
                    <label style="padding:7px 0px">알림</label>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;box-shadow: none;">
                        <p>텍스트 생성형 GPT 3.5 버전 인공지능입니다.<br>
                        로그인해야 자신의 질문과 답변을 관리하고 추가 기능을 이용할수 있습니다.<br>
                        계정이 없으면 회원가입과 앱설치를 하세요.<br>
                        어떤 질문이든 척척 답해주는 서비스에 즐거운 시간되세요.</p>
                    </div>
                </div>
                <div class="modal-footer" style="text-align: center;display:flex;padding:0px;margin-top: 10px;">
                    <button type="button" class="btn-default" style="width:50%;padding:15px 0px" onclick="location.href='onlysong.kiam.kr/event/automember.php?pcode=aimem20221005171502231&eventidx=6557&recommend_id=onlysong'">회원가입</button>
                    <button type="button" style="width:50%;background:#82C836;color:white;padding:15px 0px" onclick="location.href='login.php'">로그인</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var win_hg = $(window).height();
            var side_hg = win_hg - 240;
            var api_state = '<?=$member_iam[gpt_chat_api_key]?>';
            $("#answer_side").css('height', side_hg+'px');
            $("#answer_side1").css('height', side_hg+'px');
            $("#answer_side2").css('height', side_hg+'px');
			var textarea = document.getElementById("question");
            var limit = 110; //height limit

            textarea.oninput = function() {
                textarea.style.height = "";
                textarea.style.height = Math.min(textarea.scrollHeight + 4, limit) + "px";
            };

            $("#question").on('keydown', function (event) {
                if(api_state == ''){
                    alert("회원정보에서 본인의 API 키를 입력해주세요.");
                    location.href="mypage.php";
                }
                if (event.keyCode == 13) {
                    if(event.shiftKey){
                        $("#kw-target").html($("#kw-target").html() + "\n");
                        event.stopPropagation();
                    }
                    else{
                        send_post('<?=$_SESSION[iam_member_id]?>');
                    }
                }
            });
		});

        function check_login(id){
            if(id == ''){
                $("#intro_modal").modal('show');
            }
            else{
                return;
            }
        }

        function show_chat(){
            $("#intro_side").hide();
            $("#chat_side").show();
            // location.href="gpt_chat.php?chat=Y";
        }

        function show_new_chat(){
            $("#answer_side").hide();
            $("#gpt_req_list_title").hide();
            $("#answer_side1").show();
            $("#answer_side2").hide();
        }

        // function send_ask(){
        //     var ask = $("#question").val();
        //     $.ajax({
        //         type:"POST",
        //         url:'ajax/gpt-run.php',
        //         data:{prompt:ask},
        //         dataType:'text',
        //         success:function(data){
        //            $("#answer_side").append(data);
        //            $("#ajax-loading").hide();
        //         }
        //     })
        // }

        function show(val){
            if($('li[id=a'+val+']').hasClass('hided')){
                $('li[id=a'+val+']').removeClass('hided');
                $('i[id=down'+val+']').css('display', 'none');
                $('i[id=up'+val+']').css('display', 'inline-block');
            }
            else{
                $('li[id=a'+val+']').addClass('hided');
                $('i[id=down'+val+']').css('display', 'inline-block');
                $('i[id=up'+val+']').css('display', 'none');
            }
        }

        function show_req_history(mem_id){
            if(mem_id == ''){
                location.href = "login.php";
                return;
            }
            $.ajax({
                type:"POST",
                url:"/iam/ajax/manage_gpt_chat.php",
                data:{mem_id:"<?=$_SESSION[iam_member_id]?>", method:'show_req_list'},
                dataType: 'html',
                success:function(data){
                    // console.log(data);
                    $("#answer_side").hide();
                    $("#answer_side1").hide();
                    $("#gpt_req_list_title").show();
                    $("#answer_side2").html(data);
                    $("#answer_side2").show();
                }
            });
        }

        function copy_msg(){
            var value = $("#answer_side").text().trim();
            console.log(value.trim());
            // return;
            var aux1 = document.createElement("input");
            // 지정된 요소의 값을 할당 한다.
            aux1.setAttribute("value", value);
            // bdy에 추가한다.
            document.body.appendChild(aux1);
            // 지정된 내용을 강조한다.
            aux1.select();
            // 텍스트를 카피 하는 변수를 생성
            document.execCommand("copy");
            // body 로 부터 다시 반환 한다.
            document.body.removeChild(aux1);
            alert("복사되었습니다. 원하는 곳에 붙여 넣으세요.");
        }

        function del_list(id){
            $.ajax({
                type:"POST",
                url:"/iam/ajax/manage_gpt_chat.php",
                data:{method:'del_req_list', id:id},
                dataType: 'json',
                success:function(data){
                    if(data.result == "1"){
                        alert('삭제 되었습니다.');
                        show_req_history(id);
                    }
                    else{
                        alert('삭제실패 되었습니다.');
                    }
                }
            });
        }
    </script>
</div>
<div id="ajax_div" style="display:none"></div>