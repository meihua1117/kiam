var contextarray = [];

var defaults = {
    html: false,        // Enable HTML tags in source
    xhtmlOut: false,        // Use '/' to close single tags (<br />)
    breaks: false,        // Convert '\n' in paragraphs into <br>
    langPrefix: 'language-',  // CSS language prefix for fenced blocks
    linkify: true,         // autoconvert URL-like texts to links
    linkTarget: '',           // set target to open link in
    typographer: true,         // Enable smartypants and other sweet transforms
    _highlight: true,
    _strict: false,
    _view: 'html'
};
defaults.highlight = function (str, lang) {
    if (!defaults._highlight || !window.hljs) { return ''; }

    var hljs = window.hljs;
    if (lang && hljs.getLanguage(lang)) {
        try {
            return hljs.highlight(lang, str).value;
        } catch (__) { }
    }

    try {
        return hljs.highlightAuto(str).value;
    } catch (__) { }

    return '';
};
/* mdHtml = new window.Remarkable('full', defaults);

mdHtml.renderer.rules.table_open = function () {
    return '<table class="table table-striped">\n';
};

mdHtml.renderer.rules.paragraph_open = function (tokens, idx) {
    var line;
    if (tokens[idx].lines && tokens[idx].level === 0) {
        line = tokens[idx].lines[0];
        return '<p class="line" data-line="' + line + '">';
    }
    return '<p>';
};

mdHtml.renderer.rules.heading_open = function (tokens, idx) {
    var line;
    if (tokens[idx].lines && tokens[idx].level === 0) {
        line = tokens[idx].lines[0];
        return '<h' + tokens[idx].hLevel + ' class="line" data-line="' + line + '">';
    }
    return '<h' + tokens[idx].hLevel + '>';
}; */
function getCookie(name) {
    var cookies = document.cookie.split(';');
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();
        if (cookie.indexOf(name + '=') === 0) {
            return cookie.substring(name.length + 1, cookie.length);
        }
    }
    return null;
}

function isMobile() {
    const userAgent = navigator.userAgent.toLowerCase();
    const mobileKeywords = ['iphone', 'ipod', 'ipad', 'android', 'windows phone', 'blackberry', 'nokia', 'opera mini', 'mobile'];
    for (let i = 0; i < mobileKeywords.length; i++) {
        if (userAgent.indexOf(mobileKeywords[i]) !== -1) {
            return true;
        }
    }
    return false;
}

function insertPresetText() {
    $("#question").val($('#preset-text').val());
    autoresize();
}

function initcode() {
    console['\x6c\x6f\x67']("\u672c\u7ad9\u4ee3\u7801\u4fee\u6539\u81ea\x68\x74\x74\x70\x3a\x2f\x2f\x67\x69\x74\x68\x75\x62\x2e\x63\x6f\x6d\x2f\x64\x69\x72\x6b\x31\x39\x38\x33\x2f\x63\x68\x61\x74\x67\x70\x74");
}

function copyToClipboard(text) {
    var input = document.createElement('textarea');
    input.innerHTML = text;
    document.body.appendChild(input);
    input.select();
    var result = document.execCommand('copy');
    document.body.removeChild(input);
    return result;
}

function copycode(obj) {
    copyToClipboard($(obj).closest('code').clone().children('button').remove().end().text());
    layer.msg("复制完成！");
}

function autoresize() {
    var textarea = $('#question');
    var width = textarea.width();
    var content = (textarea.val() + "a").replace(/\\n/g, '<br>');
    var div = $('<div>').css({
        'position': 'absolute',
        'top': '-99999px',
        'border': '1px solid red',
        'width': width,
        'font-size': '15px',
        'line-height': '20px',
        'white-space': 'pre-wrap'
    }).html(content).appendTo('body');
    var height = div.height();
    var rows = Math.ceil(height / 20);
    div.remove();
    textarea.attr('rows', rows);
    $("#article-wrapper").height(parseInt($(window).height()) - parseInt($("#fixed-block").height()) - parseInt($(".layout-header").height()) - 80);
}

function text_height(){
    if($("#question").height() >= 80){
        $(".send_ask").css('height', '95%');
    }
    else{
        $(".send_ask").css('height', '92%');
    }
}

$(document).ready(function () {
    // initcode();
    // autoresize();
    $("#question").on('keyup', function () {
        text_height()
    });

    $("#question").on('keydown', function (event) {
        if (event.keyCode == 13 && event.ctrlKey) {
            send_post();
            return false;
        }
    });

    // $(window).resize(function () {
    //     autoresize();
    // });

    // $('#question').on('input', function () {
    //     autoresize();
    // });

    // $("#ai-btn").click(function () {
    //     if ($("#question").is(':disabled')) {
    //         clearInterval(timer);
    //         $("#question").val("");
    //         $("#question").attr("disabled", false);
    //         autoresize();
    //         $("#ai-btn").html('<i class="iconfont icon-wuguan"></i>发送');
    //         if (!isMobile()) $("#question").focus();
    //     } else {
    //         send_post();
    //     }
    //     return false;
    // });

    // $("#clean").click(function () {
    //     $("#article-wrapper").html("");
    //     contextarray = [];
    //     layer.msg("清理完毕！");
    //     return false;
    // });

    // $("#showlog").click(function () {
    //     let btnArry = ['已阅'];
    //     layer.open({ type: 1, title: '全部对话日志', area: ['80%', '80%'], shade: 0.5, scrollbar: true, offset: [($(window).height() * 0.1), ($(window).width() * 0.1)], content: '<iframe src="chat.txt?' + new Date().getTime() + '" style="width: 100%; height: 100%;"></iframe>', btn: btnArry });
    //     return false;
    // });
});
    function send_post() {
        $("#answer_side1").hide();
        $("#answer_side2").hide();
        $("#answer_side").show();
        // if (($('#key').length) && ($('#key').val().length != 51)) {
        //     layer.msg("请输入正确的API-KEY", { icon: 5 });
        //     return;
        // }

        var prompt = '';
        $("[id=question]").each(function(){
    	    if($(this).val())
    		prompt = $(this).val();
        });

        if (prompt == "") {
            layer.msg("질문을 입력 해주세요", { icon: 5 });
            return;
        }

        var loading = layer.msg('잠시만 기다려주세요', {
            icon: 16,
            shade: 0.4,
            time: false //취소 자동 닫기
        });

        function streaming() {
            var es = new EventSource("/iam/ajax/message.php");
            var isstarted = true;
            var alltext = "";
            var isalltext = false;
            es.onerror = function (event) {
                layer.close(loading);
                var errcode = getCookie("errcode");
                console.log(errcode);
                switch (errcode) {
                    case "invalid_api_key":
                        layer.msg("비법적 API-KEY이다");
                        break;
                    case "context_length_exceeded":
                        layer.msg("질문과 문맥의 길이가 한도를 초과하였습니다. 다시 질문하세요");
                        break;
                    case "rate_limit_reached":
                        layer.msg("동시 접속자가 너무 많습니다. 나중에 다시 시도하십시오.");
                        break;
                    case "access_terminated":
                        layer.msg("부정 사용, API-KEY 차단");
                        break;
                    case "no_api_key":
                        layer.msg("API-KEY가 제공되지 않음");
                        break;
                    case "insufficient_quota":
                        layer.msg("API-KEY 잔액 부족");
                        break;
                    case "account_deactivated":
                        layer.msg("계정이 비활성화됨");
                        break;
                    case "model_overloaded":
                        layer.msg("OpenAI 모델에 과부하가 걸렸어요. 다시 요청하세요");
                        break;
                    case null:
                        layer.msg("OpenAI 알수없는 오류");
                        break;
                    default:
                        layer.msg("OpenAI 서버 장애, 오류 유형：" + errcode);
                }
                es.close();
                if (!isMobile()) $("#question").focus();
                return;
            }
            // console.log(es);
            es.onmessage = function (event) {
                if (isstarted) {
                    layer.close(loading);
                    // $("#question").val("请耐心等待AI把话说完……");
                    $("#question").attr("disabled", true);
                    // autoresize();
                    // $("#ai-btn").html('<i class="iconfont icon-wuguan"></i>中止');
                    // layer.msg("处理成功！");
                    isstarted = false;
                    answer = randomString(16);
                    $("#answer_side").append('<li class="article-title" id="q' + answer + '" style="text-align:left;"><img src="/iam/img/chat_Q.png" style="width:30px;margin-right: 10px;"><span class="chat_title"></span></li>');
                    for (var j = 0; j < prompt.length; j++) {
                        $("#q" + answer).children('span').text($("#q" + answer).children('span').text() + prompt[j]);
                    }
                    $("#answer_side").append('<li class="article-content" id="' + answer + '" style="text-align:left;"><img src="/iam/img/chat_A.png" style="width:30px;"><span class="chat_answer" style="margin-left: 35px;"></span></li>');
                    let str_ = '';
                    let i = 0;
                    timer = setInterval(() => {
                        let newalltext = alltext;
                        let islastletter = false;
                        //때때로 서버에서 \\n을 줄 바꿈으로 잘못 반환하기도 하는데, 특히 컨텍스트를 포함하는 질문의 경우 이 줄 코드는 처리될 수 있습니다.
                        if (newalltext.split("\n\n").length == newalltext.split("\n").length) {
                            newalltext = newalltext.replace(/\\n/g, '\n');
                        }
                        if (str_.length < newalltext.length) {
                            str_ += newalltext[i++];
                            strforcode = str_ + "_";
                            if ((str_.split("```").length % 2) == 0) strforcode += "\n```\n";
                        } else {
                            if (isalltext) {
                                clearInterval(timer);
                                strforcode = str_;
                                islastletter = true;
                                $("#question").val("");
                                $("#question").attr("disabled", false);
                                // autoresize();
                                // $("#ai-btn").html('<i class="iconfont icon-wuguan"></i>发送');
                                if (!isMobile()) $("#question").focus();
                            }
                        }
                        //let arr = strforcode.split("```");
                        //for (var j = 0; j <= arr.length; j++) {
                        //    if (j % 2 == 0) {
                        //        arr[j] = arr[j].replace(/\n\n/g, '\n');
                        //        arr[j] = arr[j].replace(/\n/g, '\n\n');
                        //        arr[j] = arr[j].replace(/\t/g, '\\t');
                        //        arr[j] = arr[j].replace(/\n {4}/g, '\n\\t');
                        //        arr[j] = $("<div>").text(arr[j]).html();
                        //    }
                        //}

                        //var converter = new showdown.Converter();
                        //newalltext = converter.makeHtml(arr.join("```"));
                        // newalltext = mdHtml.render(strforcode);
                        //newalltext = newalltext.replace(/\\t/g, '&nbsp;&nbsp;&nbsp;&nbsp;');
                        // let str2_ = ''
                        // let i2 = 0
                        // if(str2_.length<newalltext.length){
                        //     str2_ += newalltext[i2++]
                        //     $("#"+answer).children('span').html(str2_+'_')//인쇄할 때 커서 추가
                        // }else{
                        //     $("#"+answer).children('span').html(str2_)//인쇄할 때 커서 추가
                        // }
                        $("#" + answer).children('span').html(strforcode);
                        // if (islastletter) MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
                        //if (document.querySelector("[id='" + answer + "']" + " pre code")) document.querySelectorAll("[id='" + answer + "']" + " pre code").forEach(el => { hljs.highlightElement(el); });
                        // $("#" + answer).each(function () {
                        //     $(this).html("<button onclick='copycode(this);' class='codebutton'>复制</button>" + $(this).html());
                        // });
                        document.getElementById("answer_side").scrollTop = 100000;
                    }, 100);
                }
                if (event.data == "[DONE]") {
                    isalltext = true;
                    contextarray.push([prompt, alltext]);
                    contextarray = contextarray.slice(-5); //최대 토큰 제한을 초과하지 않도록 최근 5번의 대화만 맥락으로 유지
                    es.close();
                    $.ajax({
                        cache: true,
                        type: "POST",
                        url: "/iam/ajax/reg_gpt_list.php",
                        data: {
                            question: prompt,
                            answer: alltext,
                        },
                        dataType: "json",
                        success: function (results) {
                            
                        }
                    });
                    return;
                }
                var json = eval("(" + event.data + ")");
                if (json.choices[0].delta.hasOwnProperty("content")) {
                    if (alltext == "") {
                        alltext = json.choices[0].delta.content.replace(/^\n+/, ''); //응답 메시지에서 가끔 첫 번째 줄 바꿈을 지웁니다.
                    } else {
                        alltext += json.choices[0].delta.content;
                    }
                }
            }
        }


        $.ajax({
            cache: true,
            type: "POST",
            url: "/iam/ajax/setsession.php",
            data: {
                message: prompt,
                context: (!($("#keep").length) || ($("#keep").prop("checked"))) ? JSON.stringify(contextarray) : '[]',
                key: ($("#key").length) ? ($("#key").val()) : '',
            },
            dataType: "json",
            success: function (results) {
                streaming();
            }
        });


    }

    function randomString(len) {
        len = len || 32;
        var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';    /****默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1****/
        var maxPos = $chars.length;
        var pwd = '';
        for (i = 0; i < len; i++) {
            pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
        }
        return pwd;
    }
