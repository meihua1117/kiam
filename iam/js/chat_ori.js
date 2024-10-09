/*
//Timer*/
var contextarray = [];
$(document).ready(function () {
    
    $("#question").on('keyup', function () {
        text_height()
    });
    // $("#question").on('keydown', function (event) {
    //     if (event.keyCode == 13) {
    //         if(event.shiftKey){
    //             $("#kw-target").html($("#kw-target").html() + "\n");
    //             event.stopPropagation();
    //         }
    //         else{
    //             send_post();
    //         }
    //     }
    // });
    $("#ai-btn").click(function () {
        send_post();
        return false;
    });
    $("#clean").click(function () {
        $("#answer_side").html("");
        contextarray = [];
        layer.msg("출시가 완료되었습니다!");
        return false;
    });
    $("#showlog").click(function () {
    		let btnArry = ['읽다'];
        layer.open({
            type: 1
            ,title: '완전한 대화 기록'
            ,area: ['80%', '80%']
            ,shade: 0.5
            ,scrollbar: true
            ,offset: [
                ($(window).height() * 0.1)
                ,($(window).width() * 0.1)
            ]
            ,content: '<iframe src="chat.txt?' + new Date().getTime()+ '" style="width: 100%; height: 100%;"></iframe>'
            ,btn: btnArry
        });
        return false;
    });
});

    function text_height(){
        if($("#question").height() >= 80){
            $(".send_ask").css('height', '95%');
        }
        else{
            $(".send_ask").css('height', '92%');
        }
    }
    
    function articlewrapper(question,answer,str){
        $("#answer_side").append('<li class="article-title" id="q'+answer+'"><img src="/iam/img/chat_Q.png" style="width:30px;margin-right: 10px;"><span class="chat_title"></span></li>');
        let str_ = ''
        let i = 0
        let timer = setInterval(()=>{
            if(str_.length<question.length){
                str_ += question[i++]
                $("#q"+answer).children('span').text(str_+'_')//인쇄할 때 커서 추가
            }else{
                clearInterval(timer)
                $("#q"+answer).children('span').text(str_)//인쇄할 때 커서 추가
            }
        },5)
        $("#answer_side").append('<li class="article-content" id="'+answer+'"><img src="/iam/img/chat_A.png" style="width:30px;"><span class="chat_answer" style="margin-left: 35px;"></span></li>');
          if(str == null || str == ""){
              str="서버가 응답하는 데 시간이 걸리면 나중에 다시 시도할 수 있습니다.";
          }
        let str2_ = ''
        let i2 = 0
        let timer2 = setInterval(()=>{
            if(str2_.length<str.length){
                str2_ += str[i2++]
                $("#"+answer).children('span').text(str2_+'_')//인쇄할 때 커서 추가
            }else{
                clearInterval(timer2)
                $("#"+answer).children('span').text(str2_)//인쇄할 때 커서 추가
		
            }

            $('#answer_side').animate({
                scrollTop: 10000 ,
                }, 10
            );
        },25)
    }
    
    function send_post(mem_id) {
        $("#answer_side1").hide();
        $("#answer_side2").hide();
        $("#answer_side").show();
        var prompt = $("#question").val();
        if (prompt == "") {
            layer.msg("질문을 입력 해주세요", { icon: 3 });
            return;
        }

        var loading = layer.msg('잠시만 기다려주세요', {
            icon: 16,
            shade: 0.4,
            time:false //취소 자동 닫기
        });
        $.ajax({
            cache: true,
            type: "POST",
            url: "ajax/message.php",
            data: {
                mem_id:mem_id,
                message: prompt,
                context:$("#keep").prop("checked")?JSON.stringify(contextarray):'[]',
            },
            dataType: "json",
            success: function (results) {
                layer.close(loading);
                $("#question").val("");
                $("#question").css("height", "58px");
                $(".send_ask").css("height", "92%");
                // layer.msg("처리 성공!");
                contextarray.push([prompt, results.raw_message]);
                articlewrapper(prompt,randomString(16),results.raw_message);
            }
        });
    }

    function randomString(len) {
        len = len || 32;
        var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';    /****혼란스러운 문자는 기본적으로 제거됩니다oOLl,9gq,Vv,Uu,I1****/
        var maxPos = $chars.length;
        var pwd = '';
        for (i = 0; i < len; i++) {
            pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
        }
        return pwd;
    }
// });
