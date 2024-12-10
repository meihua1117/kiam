<?
$path="./";
include_once "_head.php";
extract($_REQUEST);
if(!$_SESSION['one_member_id']){
?>
<script language="javascript">
    location.replace('/ma.php');
</script>
<?
exit;
}
if(!isset($_REQUEST['sms_idx'])){ $sms_idx = 0; }
if(!isset($_REQUEST['get_idx'])){ $get_idx = 0; }
$sql="select * from Gn_Member  where mem_id='".$_SESSION['one_member_id']."'";
$sresul_num=mysqli_query($self_con,$sql);
$data=mysqli_fetch_array($sresul_num);

if($data['intro_message'] =="") {
    $data['intro_message'] = "안녕하세요\n
                            \n
                            귀하의 휴대폰으로\n
                            기부문자발송을 시작합니다.\n
                            \n
                            협조해주셔서 감사합니다^^
                            ";
}
$mem_phone = str_replace("-","",$data['mem_phone']);
$sql="select * from Gn_event_sms_info  where sms_idx='".$sms_idx."'";
$sresul_num=mysqli_query($self_con,$sql);
$row=mysqli_fetch_array($sresul_num);	

if($sms_idx){
    $sql="select * from Gn_event_sms_info  where sms_idx='".$sms_idx."'";
    $sresul_num=mysqli_query($self_con,$sql);
    $row=mysqli_fetch_array($sresul_num);
}
if($get_idx){
    $sql="select * from Gn_event_sms_info  where sms_idx='".$get_idx."'";
    $sresul_num=mysqli_query($self_con,$sql);
    $row=mysqli_fetch_array($sresul_num);
}
?>
<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
<script src="/iam/js/layer.min.js" type="application/javascript"></script>
<script src="/iam/js/chat.js"></script>
<script>
    function newpop(){
        var win = window.open("mypage_pop_link_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
    }
    $(function() {
        $('#searchBtn').on("click", function() {
            newpop();
        });
        /*$(".popbutton").click(function(){
            $('.ad_layer_info').lightbox_me({
                centered: true,
                onLoad: function() {
                }
            });
        })*/
    });
</script>
<style>
    .w200 {width:200px;}
    .list_table1 tr:first-child td{border-top:1px solid #CCC;}
    .list_table1 tr:first-child th{border-top:1px solid #CCC;}
    .list_table1 td{height:40px;border-bottom:1px solid #CCC;}
    .list_table1 th{height:40px;border-bottom:1px solid #CCC;}
    .list_table1 input[type=text]{width:600px;height:30px;}
    .info_box_table input[type=text]{width:600px;height:30px;}
    .info_box_table th{height:40px;border-bottom:1px solid #CCC;width:200px !important;}
    .get_type_name{
        /* column-count: 6; */
        text-align:left;
        display:flex;
    }
    .iam_table{
        border: 1px solid black;
        border-collapse: collapse;
        padding:3px;
        text-align: center;
    }
    .zoom {
        transition: transform .2s; /* Animation */
    }

    .zoom:hover {
        transform: scale(4); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        border:1px solid #0087e0;
        box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
    }

    .zoom-2x {
        transition: transform .2s; /* Animation */
    }

    .zoom-2x:hover {
        transform: scale(2); /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
        border:1px solid #0087e0;
        box-shadow: 1px 1px 1px 0px rgba(0, 0, 0, 0.5);
    }
    .del_img_btn{
        border:1px solid;
        border-radius:3px;
        background-color:#efefef;
        padding:5px;
    }
</style>
    <div class="big_sub">
        <?include "mypage_step_navi.php";?>
        <div class="m_div">
            <?include "mypage_left_menu.php";?>
            <div class="m_body">
                <input type="hidden" name="page" value="<?=$page?>" />
                <input type="hidden" name="page2" value="<?=$page2?>" />
                <div class="a1" style="margin-top:50px; margin-bottom:15px">
                    <li style="float:left;">
                        <div class="popup_holder popup_text">스텝예약메시지 세트 만들기
                            <div class="popupbox" style="display:none;height: 60px;width: 230px;left: 230px;top: -37px;">예약문자를 주기적으로 보내기 위한 예약문자의 세트를 만드는 기능입니다.<br>
                                <a class = "detail_view" style="color: blue;" href="https://tinyurl.com/yh4e3n5y" target="_blank">[자세히 보기]</a>
                            </div>
                        </div>
		            </li>
                    <li style="float:right;"></li>
                    <p style="clear:both"></p>
                </div>
                <form name="sform" id="sform" action="mypage.proc.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="mode" value="<?php echo $sms_idx?"sms_update":"sms_save";?>" />
                    <input type="hidden" name="sms_idx" value="<?php echo $sms_idx;?>" />
                    <input type="hidden" name="event_idx" id="event_idx" value="<?php echo $row['event_idx'];?>" />
                    <div class="p1">
                        <table class="list_table1" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <th class="w200">예약메시지 세트제목</th>
                                <td><input type="text" name="reservation_title" placeholder="" id="reservation_title" value="<?=$row['reservation_title']?>"/> </td>
                            </tr>
                            <tr>
                                <th class="w200">예약메시지 세트설명</th>
                                <td>
                                    <input type="text" name="reservation_desc" placeholder="" id="reservation_desc" value="<?=$row['reservation_desc']?>"/> </td>
                            </tr>
                            <?if($get_idx){?>
                            <tr>
                                <th class="w200">스텝문자세트 가져오기</th>
                                <td>
                                    <input type="hidden" id="event_idx_event" name="event_idx_event" value="<?=$row['event_idx']?>" style="width:250px;">
                                    <input type="hidden" id="mb_id_copy" name="mb_id_copy" value="<?=$_SESSION['one_member_id']?>" style="width:250px;">
                                    <input type="text" name="mb_id" id="mb_id" value="<?=$_SESSION['one_member_id']?>" style="width:250px; height: 27px;">
                                    <input type="hidden" id="ori_sms_idx" name="ori_sms_idx" value="<?=$get_idx?>" style="width:95px;">
                                    <input type="button" value="스텝문자세트 조회" class="button " id="searchEventBtn" onclick="newMessageEvent()">
                                </td>
                            </tr>
                            <?}?>
                        </table>
                    </div>
                    <div style="text-align:center;margin-top:10px">
                        <input type="button" value="저장" class="button " id="saveBtn">
                        <input type="button" value="취소" class="button" id="cancleBtn">
                    </div>
                </form>
        <?
                // if($sms_idx != "")  {
                    if($get_idx){ $sms_idx = $get_idx; }
                    $sql_cnt="select count(step) as cnt from Gn_event_sms_step_info where sms_idx ='{$sms_idx}'";
                    $result_cnt = mysqli_query($self_con,$sql_cnt) or die(mysqli_error($self_con));
                    $row_cnt=mysqli_fetch_array($result_cnt);
                    $intstepCount=$row_cnt['cnt'];
                    if($intstepCount){
                        $show = "hidden";
                    }
                    else{
                        $show = "";
                    }
        ?>
                    <form name="pay_form" action="" method="post" class="my_pay">
                        <input type="hidden" name="page" value="<?=$page?>" />
                        <input type="hidden" name="page2" value="<?=$page2?>" />
                        <div class="a1">
                            <li style="float:left;">예약메시지 리스트</li>
                            <li style="float:right;"></li>
                            <p style="clear:both"></p>
                        </div>
                        <div>
                            <div class="p1" style="float:right;">
                                <input type="button" value="AI로 추가하기" class="button popbutton12" <?=$show?>>
                                <input type="button" value="발송회차 추가하기" class="button popbutton4">
                            </div>
                            <div>
                                <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="width:2%;"></td>
                                        <td style="width:6%;">회차</td>
                                        <td style="width:6%;">발송일시</td>
                                        <td style="width:20%;">메시지제목</td>
                                        <td style="width:40%;">메시지내용</td>
                                        <td style="width:15%;">이미지</td>
                                        <td style="width:15%;">수정/삭제</td>
                                    </tr>
            <?

                                    $sql_serch=" sms_idx ='{$sms_idx}' ";
                                    if($_REQUEST['search_date']){
                                        if($_REQUEST['rday1']){
                                            $start_time=strtotime($_REQUEST['rday1']);
                                            $sql_serch.=" and unix_timestamp({$_REQUEST['search_date']}) >=$start_time ";
                                        }
                                        if($_REQUEST['rday2']){
                                            $end_time=strtotime($_REQUEST['rday2']);
                                            $sql_serch.=" and unix_timestamp({$_REQUEST['search_date']}) <= $end_time ";
                                        }
                                    }
                                    $sql="select count(step) as cnt from Gn_event_sms_step_info where $sql_serch ";
                                    $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                                    $row=mysqli_fetch_array($result);
                                    $intRowCount=$row['cnt'];
                                    if (!$_POST['lno'])
                                        $intPageSize =20;
                                    else
                                       $intPageSize = $_POST['lno'];
                                    if($_POST['page']){
                                        $page=(int)$_POST['page'];
                                        $sort_no=$intRowCount-($intPageSize*$page-$intPageSize);
                                    }else{
                                        $page=1;
                                        $sort_no=$intRowCount;
                                    }
                                    if($_POST['page2'])
                                        $page2=(int)$_POST['page2'];
                                    else
                                        $page2=1;
                                    $int=($page-1)*$intPageSize;
                                    if($_REQUEST['order_status'])
                                        $order_status=$_REQUEST['order_status'];
                                    else
                                        $order_status="asc";
                                    if($_REQUEST['order_name'])
                                        $order_name=$_REQUEST['order_name'];
                                    else
                                        $order_name="step";
                                    $intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);
                                    if($intRowCount){
                                        $sql="select * from Gn_event_sms_step_info where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
                                        $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                                        $c=0;
                                        while($row=mysqli_fetch_array($result)){
            ?>
                                        <tr>
                                            <td><?=$sort_no?></td>
                                            <td style="font-size:12px;"><?=$row['step']?></td>
                                            <td style="font-size:12px;"><?=$row['send_day']?>일후</td>
                                            <td><?=$row['title']?></td>
                                            <td>
                                                <a href="javascript:void(0)" onclick="show_recv('show_content','<?=$c?>','문자내용')"><?=str_substr($row['content'],0,190,'utf-8')?></a><input type="hidden" name="show_content" value="<?=$row['content']?>"/>
                                            </td>
                                            <td>
                                                <?if($row['image']) {?>
                                                    <img class="zoom" src="/upload/<?=$row['image']?>" style="max-height:50px">
                                                <?}
                                                if($row['image1']) {?>
                                                    <img class="zoom" src="/upload/<?=$row['image1']?>" style="max-height:50px">
                                                <?}
                                                if($row['image2']) {?>
                                                    <img class="zoom" src="/upload/<?=$row['image2']?>" style="max-height:50px">
                                                <?}?>
                                            </td>
                                            <td>
                                                <a href="javascript:editRow('<?=$row['sms_detail_idx'];?>','<?=$row['sms_idx'];?>')">수정</a>/
                                                <a href="javascript:deleteRow('<?=$row['sms_detail_idx'];?>','<?=$row['sms_idx'];?>')">삭제</a>
                                            </td>
                                        </tr>
            <?
                                            $c++;
                                            $sort_no--;
                                        }
            ?>
                                        <tr>
                                            <td colspan="10">
                                                <?page_f($page,$page2,$intPageCount,"pay_form");?>
                                            </td>
                                        </tr>
            <?
                                    }else{
            ?>
                                        <tr>
                                            <td colspan="10">
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
            <?  //}?>
            </div>  <!--mbody-->
        </div> <!--mdiv-->
<Script>
function editRow(sms_detail_idx, sms_idx) {
    $.ajax({
        type:"POST",
        url:"mypage.proc.php",
		dataType:"json",
		data:{
            mode : "sms_detail_info",
            sms_detail_idx: sms_detail_idx,
            sms_idx: sms_idx
        },
		success:function(data){
            $('.ad_layer4').lightbox_me({
                centered: true,
                onLoad: function() {
                    $('#mode').val('step_update');
                    $('#sms_detail_idx').val(data.data.sms_detail_idx);
                    var send_time  = (data.data.send_time).split(":");
                    $('#step').val(data.data.step);
                    $('#send_day').val(data.data.send_day);
                    if(data.data.send_day != "0")
                        $('#timeArea').show();
                    $('#send_time_hour').val(send_time[0]);
                    $('#send_time_min').val(send_time[1]);
                    $('#title').val(data.data.title);
                    $('#content').val(data.data.content);
                    // $('#send_num').val(data.data.mobile);
                    $('#image1').html('');
                    $('#image2').html('');
                    $('#image3').html('');
                    if(data.data.send_deny == "Y"){
                        $('#send_deny_msg').prop("checked", true);
                        $('.deny_msg_span').html('ON');
                        $('.deny_msg_span').css('color', '#00F');
                    }
                    else{
                        $('#send_deny_msg').prop("checked", false);
                        $('.deny_msg_span').html('OFF');
                        $('.deny_msg_span').css('color', '#F00');
                    }
                    if(data.data.image1)
                        $('#image2').html('<img class="zoom" src="/upload/'+data.data.image1+'" style="width:80px">&nbsp;&nbsp;<a class="del_img_btn" href="javascript:delete_img(`image2`, '+data.data.sms_detail_idx+')">삭제</a>');
                    if(data.data.image2)
                        $('#image3').html('<img class="zoom" src="/upload/'+data.data.image2+'" style="width:80px">&nbsp;&nbsp;<a class="del_img_btn" href="javascript:delete_img(`image3`, '+data.data.sms_detail_idx+')">삭제</a>');
                    if(data.data.image)
                        $('#image1').html('<img class="zoom" src="/upload/'+data.data.image+'" style="width:80px">&nbsp;&nbsp;<a class="del_img_btn" href="javascript:delete_img(`image1`, '+data.data.sms_detail_idx+')">삭제</a>');
                }
            });
        }
    });
    return false;
}    
function delete_img(val, idx){
    if(confirm("이미지를 삭제 하시겠습니까?")){
        $.ajax({
            type:"POST",
            url:"/ajax/step_sms_send.php",
            dataType:"json",
            data:{delete_img:true, img:val, sms_detail_idx:idx},
            success:function(data){
                console.log(data);
                $("span[id="+val+"]").html('');
                alert('이미지가 삭제 되었습니다.');
            }
        })
    }
}
function deleteRow(sms_detail_idx, sms_idx) {
    if(confirm('삭제하시겠습니까?')) {
    	$.ajax({
            type:"POST",
            url:"mypage.proc.php",
            data:{
                mode : "sms_detail_del",
                sms_detail_idx: sms_detail_idx,
    			sms_idx: sms_idx
            },
            success:function(data){
                $("#ajax_div").html(data);
    		 	alert('삭제되었습니다.');
    		 	location.reload();
            }
        });
        return false;
    }
}
function change_message(form) {
    if(form.intro_message.value == "") {
        alert('정보를 입력해주세요.');
		form.intro_message.focus();
		return false;
	}
	$.ajax({
        type:"POST",
		url:"ajax/ajax.php",
		data:{
            mode : "intro_message",
			intro_message: form.intro_message.value
        },
        success:function(data){
            $("#ajax_div").html(data);
		 	alert('저장되었습니다.');
        }
    });
    return false;
}
</script>
<style>
.popup_holder{position:relative;}
.popupbox{
	z-index: 1;
	text-align: left;
	font-size: 12px;
	font-weight: normal;
	background: white;
	border-radius: 3px;
	padding: 10px;
	border: none;
	position: absolute;
	box-shadow:  0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12);
}
</style>
<script>
$(function() {
    $('#saveBtn').on("click",function() {   
        if($('#reservation_title').val() == "") {
            alert('예약메시지 제목을 입력해주세요.');
            return;
        }
        $('#sform').submit();
    });
    $('#cancleBtn').on("click",function() {
        location.href = "mypage_reservation_list.php";
    });
	$(".popbutton4").click(function(){
        <?if($sms_idx){?>
		$('.ad_layer4').lightbox_me({
			centered: true,
			onLoad: function() {
                $('#mode').val('step_add');
                $('#sms_detail_idx').val('');
                $('#step').val('');
                $('#day').val('');
                $('#send_time_hour').val('');
                $('#send_time_min').val('');
                $('#title').val('');
                $('#content').val('');
                $('#image1').html('');
                $('#image2').html('');
                $('#image3').html('');
			}
		});
        <?}else{?>
        alert("스텝예약메시지 세트정보를 먼저 입력하시고 저장을 클릭해주세요.");
        <?}?>
	});
	$('#send_day').on("change", function(){
	    if($(this).val() == "0") {
	        $('#timeArea').hide();
	    } else {
	        $('#timeArea').show();
	    }
	});
	$('#send_day').on("keyup", function(){
	    if($(this).val() == "0") {
	        $('#timeArea').hide();
	    } else {
	        $('#timeArea').show();
	    }
	});	
	$('#popSaveBtn').on("click", function() {
	    if($('#step').val() == "") {
	        alert('순서를 입력하세요.');
	        return;
	    }
	    if($('#send_day').val() == "") {
	        alert('발송일시를 입력하세요.');
	        return;
	    }	    
	    //if($('#send_time_hour').val() == "") {
	    //    alert('발송일시를 입력하세요.');
	    //    return;
	    //}	    
	    //
	    //if($('#send_time_min').val() == "") {
	    //    alert('발송일시를 입력하세요.');
	    //    return;
	    //}	    	    	    
	    
	    if($('#title').val() == "") {
	        alert('제목을 입력하세요.');
	        return;
	    }	    	    
	    if($('#content').val() == "") {
	        alert('내용을 입력하세요.');
	        return;
	    }	    	    	    
	    $('#addForm').submit();
	});
	
	$('#popCloseBtn').on("click", function() {
	    $('.lb_overlay, .ad_layer4').hide();
        location.reload();
	});

    $('.popbutton12').on("click", function() {
        <?if($sms_idx){?>
	    $('#auto_making_modal').modal("show");
        <?}else{?>
        alert("스텝예약메시지 세트정보를 먼저 입력하시고 저장을 클릭해주세요.");
        <?}?>
	});
});

function start_making(){
    var web_type = $('input[name=web_type]:checked').attr('id');
    console.log(web_type);
    if(web_type == undefined){
        alert('수집분야를 설정 하세요.');
        return;
    }
    switch(web_type){
        case 'newsid':
            start_making_web('news');
            break;
        case 'blogid':
            start_making_web('blog');
            break;
        case 'youtubeid':
            start_making_web('youtube');
            break;
        default:
            console.log("select type!");
            break;
    }
}

//goodhow 크롤링 서버에 요청 보내기, 상태값 얻어 오기
function start_making_web(type) {
    var slt = 0;
    var url = '';
    var mem_id_status = '';
    var count_interval = 0;
    var blog_link = 0;
    var contents_keyword = '';
    var sms_idx = <?=$sms_idx?>;
    address = $("#people_web_address").val();
    if($("#people_contents_start_date").val() != ""){
        start_date = $("#people_contents_start_date").val().replace(/-/g, "");
        end_date = $("#people_contents_end_date").val().replace(/-/g, "");
    }
    contents_cnt = $("#people_contents_cnt").val();
    send_time = $("#send_time").val();
    contents_keyword = $("#people_contents_key").val();
    if(type == 'youtube'){
        if(contents_cnt == ""){
            alert('갯수를 입력하세요.');
            return;
        }
    }
    else{
        if(contents_cnt == "" || contents_keyword == ""){
            alert('키워드/갯수를 입력하세요.');
            return;
        }
    }
    
    url = "https://www.goodhow.com/crawler/crawler/ai_step_mms.php";
    if(type == 'youtube'){
        if((address.substring(0, 26) == "https://www.youtube.com/c/") || (address.substring(0, 32) == "https://www.youtube.com/channel/") || (address.substring(0, 44) == "https://www.youtube.com/results?search_query") || (address.substring(0, 29) == "https://www.youtube.com/user/")){
            start_date = end_date = "";
            url = "https://www.goodhow.com/crawler/crawler/ai_step_mms.php";
        }
        else{
            alert("웹주소 형식이 틀립니다. 옳바른 형식으로 이용 해주세요.");
            return;
        }
    }

    console.log(sms_idx, type, address, contents_cnt, send_time, contents_keyword, start_date, end_date);
    $.ajax({
        type:"POST",
        dataType:"json",
        data:{sms_idx:sms_idx, type:type, address:address, contents_cnt:contents_cnt, send_time:send_time, contents_keyword:contents_keyword, start_date:start_date, end_date:end_date},
        url:url,
        success: function(data){
            console.log(data);
            if(data == 1){
                alert("추가되었습니다.");
                location.reload();
            }
            else{
                alert("진행중 오류가 발생 하였습니다.");
                location.reload();
            }
        }
    });
    $("#startmaking").attr('disabled', true);
}

function goback(){
    $('#auto_making_modal').modal("hide");
}

//수집분야별에 따르는 설정값 입력창 현시
function show_keyword(val){
    if(val == 'news'){
        $("#contents_key").attr('style', 'width:100%;display:inline-table;');
        $("#contents_time").attr('style', 'width:100%;display:inline-table;');
        $("#web_address").attr('style', 'width:100%;display:none;');
        $("#sendtime").attr('style', 'width:100%;display:inline-table;');
    }
    else if(val == 'blog'){
        alert("특정 블로그에서 키워드와 매칭되는 게시물 크롤링을 원하시면 웹주소입력란에 다음의 블로그 주소를 입력하세요.\n 예시 : https://blog.naver.com/abcd123\n\n웹주소에 입력을 안하면 전체 블로그에서 키워드와 매칭되는 게시물을 크롤링합니다.");
        $("#contents_key").attr('style', 'width:100%;display:inline-table;');
        $("#contents_time").attr('style', 'width:100%;display:inline-table;');
        $("#web_address").attr('style', 'width:100%;display:inline-table;');
        $("#sendtime").attr('style', 'width:100%;display:inline-table;');
    }
    else if(val == 'youtube'){
        $("#contents_key").attr('style', 'width:100%;display:none;');
        $("#contents_time").attr('style', 'width:100%;display:none;');
        $("#web_address").attr('style', 'width:100%;display:inline-table;');
        $("#sendtime").attr('style', 'width:100%;display:inline-table;');
    }
}

function newMessageEvent(){ // test 메시지조회
    var win = window.open("../mypage_pop_message_list_for_copylist.php?mode=change", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");    
}

function open_change_page(ori_sms_idx){
	location.href="/mypage_reservation_create.php?get_idx="+ori_sms_idx;
}
//gpt chat script
var contextarray = [];
function show_chat(api){
    $("#gpt_chat_modal").modal('show');
}
$(document).ready(function () {
    var textarea = document.getElementById("question");
    var limit = 110; //height limit
    var api_state = '<?=$member_1['gpt_chat_api_key']?>';

    textarea.oninput = function() {
        textarea.style.height = "";
        textarea.style.height = Math.min(textarea.scrollHeight, limit) + "px";
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
                send_post('<?=$_SESSION['iam_member_id']?>');
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

function show_new_chat(){
    $("#answer_side").hide();
    $("#gpt_req_list_title").hide();
    $("#answer_side1").show();
    $("#answer_side2").hide();
}
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

function show_req_history(){
    $.ajax({
        type:"POST",
        url:"/iam/ajax/manage_gpt_chat.php",
        data:{mem_id:"<?=$_SESSION['iam_member_id']?>", method:'show_req_list'},
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
                show_req_history();
            }
            else{
                alert('삭제실패 되었습니다.');
            }
        }
    });
}

function articlewrapper(question,answer,str){
    $("#answer_side").html('<li class="article-title" id="q'+answer+'"><img src="/iam/img/chat_Q.png" style="width:30px;margin-right: 10px;"><span class="chat_title"></span></li>');
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

// function send_post(mem_id) {
//     $("#answer_side1").hide();
//     $("#answer_side2").hide();
//     $("#answer_side").show();
//     $("#ajax-loading").show();
//     var prompt = $("#question").val();
//     if (prompt == "") {
//         alert('질문을 입력해 주세요.');
//         return;
//     }

//     $.ajax({
//         cache: true,
//         type: "POST",
//         url: "/iam/ajax/message.php",
//         data: {
//             mem_id:mem_id,
//             message: prompt,
//             context:$("#keep").prop("checked")?JSON.stringify(contextarray):'[]',
//         },
//         dataType: "json",
//         success: function (results) {
//             $("#ajax-loading").hide();
//             $("#question").val("");
//             $("#question").css("height", "58px");
//             // $(".send_ask").css("height", "98%");
//             contextarray.push([prompt, results.raw_message]);
//             articlewrapper(prompt,randomString(16),results.raw_message);
//         }
//     });
// }

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

function send_chat(){
    var title = $("#answer_side span.chat_title").text();
    var detail = $("#answer_side span.chat_answer").text();
    if(title == ""){
        alert('질문해주세요.');
        return;
    }
    $("#title").val(title);
    $("#content").val(detail);
    $("#gpt_chat_modal").modal('hide');
}//gpt chat
</script>      
<link href='/css/sub_4_re.css' rel='stylesheet' type='text/css'/>
<script type="text/javascript" src="jquery.lightbox_me.js"></script>
<script type="text/javascript" src="/js/mms_send.js"></script>
<script type="text/javascript" src="/plugin/tablednd/js/jquery.tablednd.0.7.min.js"></script>
<style>
.ad_layer4 {
    width: 903px;
    height: auto;
    background-color: #fff;
    border: 2px solid #24303e;
    position: relative;
    box-sizing: border-box;
    padding: 30px 30px 50px 30px;
    display: none;
}    
#ajax-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:9000;text-align:center;display:none;background-color: #fff;opacity: 0.8;}
#ajax-loading img{position:absolute;top:50%;left:50%;width:120px;height:120px;margin:-60px 0 0 -60px;}
.chat_btn{
    color: white;
    border-radius: 7px;
    background-color: red;
    font-size: 12px;
    border-color: red;
    padding: 4px 0px;
    margin-right: 3px;
    position: absolute;
    right: 45px;
}
#answer_side, #answer_side1, #answer_side2{
    width: 90%;
    height: 400px;
    background-color: white;
    margin-right: auto;
    margin-left: auto;
    border-radius: 10px;
    margin-top: 12px;
    padding: 35px 30px 10px 30px;
    overflow: auto;
    text-align: left;
    position: relative;
}
.search_keyword {
    position: relative;
    width: 99%;
    margin: 0 auto;
    margin-top: 10px;
}
.search_keyword textarea {
    width: 78%;
    height: 50px;
    padding: 17px 60px 0 25px;
    border-width: 0;
    border-radius: 15px;
    font-size: 15px;
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
    right: 60px;
    width: 58px;
    height: 100%;
    background-color:white;
    border-radius:20px;
    border: none;
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
    top: 5px;
    left: 80px;
}
.gpt_act{
    position: relative;
    height: 35px;
}
.newpane, .newpane:hover{
    background-color: black;
    color: white !important;
    padding: 4px;
    border-radius: 10px;
    position: absolute;
    top: 5px;
    right: 80px;
}
@media only screen and (max-width: 720px) {
    .send_ask{
        position: absolute;
        top: 0;
        right: 60px;
        width: 50px;
        height: 100%;
        background-color:white;
        border-radius:20px;
        border: none;
    }
}
@media only screen and (max-width: 600px) {
    .send_ask{
        position: absolute;
        top: 0;
        right: 60px;
        width: 50px;
        height: 100%;
        background-color:white;
        border-radius:20px;
        border: none;
    }
    .chat_btn{
        color: white;
        border-radius: 7px;
        background-color: red;
        font-size: 15px;
        padding: 5px 20px;
        position: absolute;
        right: 45px;
    }
}
@media only screen and (max-width: 450px) {
    .send_ask{
        position: absolute;
        top: 0;
        right: 60px;
        width: 50px;
        height: 98%;
        background-color:white;
        border-radius:20px;
    }
    .history{
        position: absolute;
        top: 5px;
        left: 35px;
    }
    .newpane{
        background-color: black;
        color: white !important;
        padding: 4px;
        border-radius: 10px;
        position: absolute;
        top: 5px;
        right: 40px;
    }
    .chat_btn{
        color: white;
        border-radius: 7px;
        background-color: red;
        font-size: 15px;
        padding: 5px 20px;
        position: absolute;
        right: 45px;
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
    font-size: 15px;
    text-align: left;
}
.article-content{
    display: grid;
    margin-bottom: 15px;
    font-size: 15px;
    text-align: left;
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
	<div class="ad_layer4">
		<div class="layer_in">
			<span class="layer_close close" onclick="location.reload()"><img src="/images/close_button_05.jpg"></span>	
			<div class="pop_title">
				예약메시지
			</div>
			<div class="info_box">
                <button onclick="show_chat('<?=$member_1['gpt_chat_api_key']?>')" class="chat_btn">AI와 대화하기</button>
			    <form method="post" name="addForm" id="addForm"  action="mypage.proc.php" enctype="multipart/form-data">
                    <input type="hidden" name="sms_idx" value="<?php echo $sms_idx;?>">
                    <input type="hidden" name="mode" id="mode" value="step_add">
                    <input type="hidden" name="sms_detail_idx" id="sms_detail_idx">
                    <table class="info_box_table" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <th class="w200">순서</th>
                                <td>
                                    <input type="text" id="step" name="step" value="" style="width:70px;">
                                </td>
                            </tr>
                            <tr>
                                <th class="w200">발송일시</th>
                                <td>
                                    <input type="text" id="send_day" name="send_day" value="" style="width:70px;" maxlength="3">일후(0이면 신청 후 즉시 발송)
                                    <span id="timeArea" style="display:none">
                                    <input type="text" id="send_time_hour" name="send_time_hour" value="" style="width:70px;" maxlength="2"> 시
                                    <input type="text" id="send_time_min" name="send_time_min" value="" style="width:70px;" maxlength="2"> 분 (10분단위로 설정가능)
                                    </span>
                                    <div id="display_day"></div>
                                </td>
                            </tr>
                            <tr>
                                <th>메시지제목</th>
                                <td><input type="text" id="title" name="title" value="" ></td>
                            </tr>
                            <tr>
                                <th>메시지내용</th>
                                <td>
                                    <textarea name="content" itemname="내용" id="content" required="" placeholder="메시지내용을 입력하세요" style="background-color: rgb(200, 237, 252);width:300px;height:200px;"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>수신거부</th>
                                <td><input type="checkbox" id="send_deny_msg" name="send_deny_msg" onclick="deny_msg_click(this,0)" style="float:left;"><div class="deny_msg_span" style="float:left;">OFF</div></td>
                            </tr>
                            <tr>
                                <th>이미지1</th>
                                <td><input type="file" id="file" name="image" value=""  accept="image/*" ><span id="image1"></span></td>
                            </tr>
                            <tr>
                                <th>이미지2</th>
                                <td><input type="file" id="file" name="image1" value=""  accept="image/*" ><span id="image2"></span></td>
                            </tr>
                            <tr>
                                <th>이미지3</th>
                                <td><input type="file" id="file" name="image2" value=""  accept="image/*" ><span id="image3"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </form>
			</div>
			<div class="ok_box">
                <input type="button" value="취소" class="button "  id="popCloseBtn">
                <input type="button" value="저장" class="button" id="popSaveBtn">				
			</div>
		</div>
	</div>

    <div id="auto_making_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto; display:none;">
        <div class="modal-dialog" style="margin: 100px auto;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border:none;background-color: rgb(130,199,54)">
                    <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">AI로 예약메시지 만들기
                    </div>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;text-align: center;width: 100%;">
                        <table style="width:100%;">
                            <tbody>
                            <tr>
                                <td class="iam_table" style="width: 22.8%;border-bottom-color: white;">수집분야설정</td>
                                <td class="iam_table" style="border-bottom-color: white;"><div class="get_type_name">
                                        <div style="width:64px;" onclick="show_keyword('news')">
                                            <input type="radio" name="web_type" id="newsid" style="vertical-align: top;" checked>
                                            <label for="newsid" value="news" style="font-size:17px;">뉴스</label>
                                        </div>
                                        <div style="width: 70px;" onclick="show_keyword('blog')">
                                            <input type="radio" name="web_type" id="blogid" style="vertical-align: top;">
                                            <label for="blogid" value="blog" style="font-size:17px;">블로그</label>
                                        </div>
                                        <div style="width: 70px;" onclick="show_keyword('youtube')">
                                            <input type="radio" name="web_type" id="youtubeid" style="vertical-align: top;">
                                            <label for="youtubeid" value="youtube" style="font-size:17px;">유튜브</label>
                                        </div>
                                    </div></td>
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
                                <td class="iam_table" style="border-bottom-color: white;"><input type="date" id="people_contents_start_date" style="width: 40%;border:1px solid;" value="<?=date('2010-01-01')?>">&nbsp;&nbsp;~&nbsp;&nbsp;<input type="date" id="people_contents_end_date" style="width: 40%;border:1px solid;" value="<?=date('Y-m-d')?>"></td>
                            </tr>
                            </tbody>
                        </table>
                        <table style="width:100%">
                            <tbody>
                            <tr>
                                <td class="iam_table" style="width: 22.8%;">콘텐츠수 입력</td>
                                <td class="iam_table"><input type="number" placeholder="카드에 넣고 싶은 콘텐츠갯수 지정(최대 <?=$Gn_contents_limit;?>개/유튜브 30개)" id="people_contents_cnt" min="1" max="<?=$Gn_contents_limit;?>" style="width: 100%;"></td>
                            </tr>
                            </tbody>
                        </table>
                        <table id="sendtime" style="width:100%;" >
                            <tbody>
                            <tr>
                                <td class="iam_table" style="width: 22.8%;border-top-color: white;">발송시간</td>
                                <td class="iam_table" style="border-top-color: white;"><input type="text" placeholder="휴대폰, 일반전화 중에 선택 입력" id="send_time" style="width: 100%;" value="00:00"></td>
                            </tr>
                            </tbody>
                        </table>
                        <!-- </form> -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:49%;font-size:15px;" onclick="goback()">취소</button>
                    <button type="button" class="btn btn-default btn-submit" style="border-radius: 5px;width:49%;font-size:15px;" onclick="start_making()" id="startmaking">추가하기</button>
                </div>
            </div>
        </div>
    </div>
    <!--GPT chat modal-->
    <div id="gpt_chat_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="margin: 30px auto;width: 100%;max-width:700px">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="background: #5bd540;border-top-left-radius: 5px;border-top-right-radius: 5px;">
                    <div class="login_bold" id="gwc_con_name_modal" style="margin-bottom: 0px;color: #ffffff;font-size: 17px;text-align: center">콘텐츠 창작AI 알지(ALJI)</div>
                    <a data-dismiss="modal" style="float:right;color:white;font-size: 20px;font-weight: bold;margin-top: -27px;cursor:pointer;">X</a>
                </div>
                <div class="modal-body" style="background-color:#e5e3e3;">
                    <div class="container" style="text-align: center;width: 100%;">
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
                            for($i = 0; $i < count($gpt_qu_arr); $i++){
                            ?>
                            <li class="article-title" id="q<?=$i?>" onclick="show('<?=$i?>')"><img src="/iam/img/chat_Q.png" style="width:30px;margin-right: 10px;"><span class="chat_title"><?=htmlspecialchars_decode($gpt_qu_arr[$i])?></span><i id="down<?=$i?>" class="fa fa-angle-down" style="font-size: 20px;font-weight: bold;margin-left: 10px;"></i><i id="up<?=$i?>" class="fa fa-angle-up" style="font-size: 20px;font-weight: bold;margin-left: 10px;display:none;"></i></li>
                            <li class="article-content hided" id="a<?=$i?>"><img src="/iam/img/chat_A.png" style="width:30px;"><span class="chat_answer" style="margin-left: 35px;"><?=htmlspecialchars_decode($gpt_an_arr[$i])?></span></li>
                            <?}?>
                        </ul>
                        <ul id="answer_side2" hidden>
                        </ul>
                        <div class="gpt_act">
                            <a class="history" href="javascript:show_req_history();"><img src="/iam/img/gpt_req_list.png" style="height: 25px;"></a>
                            <a class="newpane" href="javascript:show_new_chat();"><span style="font-size: 5px;">NEW</a>
                        </div>
                        <div class="search_keyword">
                            <input type="hidden" name="key" id="key" value="<?=$member_1['gpt_chat_api_key']?>">
                            <textarea class="search_input" autocomplete="off" name="question" id="question" value="" title="질문을 입력하세요" placeholder="알지AI에게 질문해보세요" onclick="check_login('<?=$_SESSION['iam_member_id']?>')"></textarea>
                            <button type="button" onclick="send_post('<?=$_SESSION['iam_member_id']?>')" class="send_ask"><img src="/iam/img/send_ask.png" alt="전송"></button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="text-align: center;background-color: #e5e3e3;padding:7px;">
                    <button type="button" style="width:50%;background:#82C836;color:white;padding:10px 0px;border: none;" onclick="send_chat()">보내기</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="ajax-loading"><img src="/iam/img/ajax-loader.gif"></div>
<?
include_once "_foot.php";
?>