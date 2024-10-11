<?
$path="./";
include_once "_head.php";
if(!$_SESSION['one_member_id'])
{
?>
<script language="javascript">
location.replace('/ma.php');
</script>
<?
exit;
}
$sql="select * from Gn_Member  where mem_id='".$_SESSION['one_member_id']."'";
$sresul_num=mysqli_query($self_con,$sql);
$data=mysqli_fetch_array($sresul_num);	
$mem_phone = str_replace("-","",$data['mem_phone']);	
?>
<script>
function copyHtml(){
    var trb = $.trim($('#sHtml').html());
    var IE=(document.all)?true:false;
    if (IE) {
        if(confirm("이 소스코드를 복사하시겠습니까?")) {
            window.clipboardData.setData("Text", trb);
        } 
    } else {
            temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", trb);
    }
}
$(function(){
	$(".popbutton").click(function(){
		$('.ad_layer_info').lightbox_me({
			centered: true,
			onLoad: function() {
			}
		});
	})
});
</script>
<style>
.pop_right {
    position: relative;
    right: 2px;
    display: inline;
    margin-bottom: 6px;
    width: 5px;
}    
.tooltiptext-bottom {
    width: 420px;
    font-size:15px;
    background-color: white;
    color: black;
    text-align: left;
    position: absolute;
    z-index: 200;
    top: 25%;
    left: 35%;
}

.title_app{
    text-align: center;
    background-color: rgb(130,199,54);
    padding: 10px;
    font-size: 20px;
    color: white;
}
@media only screen and (max-width: 450px) {
    .tooltiptext-bottom{
        width: 80%;
        left:8%;
    }
}
#tutorial-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:150;text-align:center;display:none;background-color: grey;opacity: 0.7;}
</style>
<div class="big_div">
    <div class="big_sub">
    <?php include "mypage_step_navi.php";?>
        <div class="m_div">
            <?php include "mypage_left_menu.php";?>
            <div class="m_body">
                <form name="pay_form" action="" method="post" class="my_pay">
                    <input type="hidden" name="page" value="<?=$page?>" />
                    <input type="hidden" name="page2" value="<?=$page2?>" />
                    <div class="a1" style="margin-top:50px; margin-bottom:15px">
                        <li style="float:left;">
                            <div class="popup_holder popup_text">고객관리 리스트
                                <div class="popupbox" style="height: 56px;width: 215px;left: 180px;top: -37px;display:none">이벤트 신청그룹창을 통해 신청한 고객데이터를 조회하는 기능입니다.<br><br>
                                    <a class = "detail_view" style="color: blue;" href="https://tinyurl.com/bddum95m" target="_blank">[자세히 보기]</a>
                                </div>
                            </div>
                        </li>
                        <li style="float:right;"></li>
                        <p style="clear:both"></p>
                    </div>
                    <div>
                        <div class="p1">
                            <select name="search_key" class="select">
                                <option value="" <?if($_REQUEST[search_key] == "") echo "selected"?>>전체</option>
                                <option value="name" <?if($_REQUEST[search_key] == "name") echo "selected"?>>신청자이름</option>
                                <option value="mobile" <?if($_REQUEST[search_key] == "mobile") echo "selected"?>>신청폰번호</option>
                            </select>
                            <input type="text" name="search_text" placeholder="" id="search_text" value="<?=$_REQUEST[search_text]?>"/>
                            <input type="text" name="sp" placeholder="" id="event_code" value="<?=$_REQUEST[sp]?>" readonly style="background:#efefef"/>
                            <input type="button" value="신청창 조회" class="button " id="searchBtn">
                            <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
                            <div style="float:right;">
                                <div class="popup_holder" style="display:inline-block"> <!--Parent-->
                                    <input type="button" value="그룹추가" class="button " id="eventAddBtn">
                                    <!--<div class="popupbox" style="height: 75px;width: 280px;bottom: 37px;display:none;">이벤트에 신청한 고객 명단 중에서 특정 고객을 선택해 다른 이벤트에 연결하여 예약문자를 수동으로 연결시켜주는 기능입니다.<br><br><!--Child--> 
                                        <!--<a class = "detail_view" href="https://url.kr/NJEhYk" target="_blank">[자세히 보기]</a>
                                    </div>-->
                                </div>
                                <div class="popup_holder" style="display:inline-block"> <!--Parent-->
                                    <input type="button" value="신청자추가" class="button" onclick="location.href='mypage_request_edit.php'">
                                    <div class="popupbox" style="height: 65px;width: 170px;bottom: 37px;display:none;">이벤트에 수동으로 고객정보를 입력하여 추가하는 기능입니다.<br><br><!--Child-->
                                        <a class = "detail_view" href="https://tinyurl.com/yp8paxze" target="_blank">[자세히 보기]</a>
                                    </div>
                                </div>
                                <div class="popup_holder" style="display:inline-block"> <!--Parent-->
                                    <input type="button" value="새디비발송" class="button" onclick="location.href='mypage_oldrequest_list.php'">
                                </div>
                                <div class="popup_holder" style="display:inline-block"> <!--Parent-->
                                    <input type="button" value="발송관리" class="button" onclick="location.href='mypage_wsend_list.php'">
                                </div>
                                <div class="popup_holder" style="display:inline-block"> <!--Parent-->
                                    <input type="button" value="선택삭제" class="button" id="stepAddBtn" onclick="deleteMultiRow()">
                                </div>
                            </div>
                        </div>
                        <div>
                            <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="width:2%;"><input type="checkbox" name="allChk" id="allChk" value="<?php echo $row['event_idx'];?>"></td>
                                    <td style="width:5%;">No</td>
                                    <td style="width:7%;">신청자<br>[보기]</td>
                                    <td style="width:9%;">신청자<br>폰번호</td>
                                    <td style="width:9%;">신청창제목</td>
                                    <td style="width:10%">발송세트문자<br>회차/발송건수</td>
                                    <td style="width:6%">중단세트문자<br>ON/OFF</td>
                                    <td style="width:8%">발송폰번호</td>
                                    <td style="width:7%;">신청일자<br>시간</td>
                                    <td style="width:7%;">추가<br>신청정보</td>
                                    <td style="width:7%;">수정/삭제</td>
                                </tr>
                            <?
                            $sql_serch=" m_id ='{$_SESSION['one_member_id']}' ";
                            if($_REQUEST[search_date])
                            {
                                if($_REQUEST[rday1])
                                {
                                    $start_time=strtotime($_REQUEST[rday1]);
                                    $sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) >=$start_time ";
                                }
                                if($_REQUEST[rday2])
                                {
                                    $end_time=strtotime($_REQUEST[rday2]);
                                    $sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) <= $end_time ";
                                }
                            }
                            if($_REQUEST[search_key] && $_REQUEST[search_text])
                            {
                                $search_text = $_REQUEST[search_text];
                                $sql_serch.=" and ".$_REQUEST[search_key]." like '%$search_text%'";
                            }
                            if($_REQUEST['sp'])
                            {
                                $sp = $_REQUEST[sp];
                                $sql_serch.=" and sp ='$sp'";
                            }

                            $sql="select count(request_idx) as cnt from Gn_event_request where $sql_serch ";
                            $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                            $row=mysqli_fetch_array($result);
                            $intRowCount=$row['cnt'];
                            if (!$_POST['lno'])
                                $intPageSize =20;
                            else
                                $intPageSize = $_POST['lno'];
                            if($_POST['page'])
                            {
                                $page=(int)$_POST['page'];
                                $sort_no=$intRowCount-($intPageSize*$page-$intPageSize);
                            }
                            else
                            {
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
                                $order_status="desc";
                            if($_REQUEST['order_name'])
                                $order_name=$_REQUEST['order_name'];
                            else
                                $order_name="request_idx";
                            $intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);
                            if($intRowCount)
                            {
                                $sql="select * from Gn_event_request where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
                                $excel_sql = "select * from Gn_event_request where $sql_serch order by $order_name $order_status";
                                $excel_sql = str_replace("'","`",$excel_sql);
                                $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                                while($row=mysqli_fetch_array($result))
                                {
                                    $sql_event_data = "select * from Gn_event where event_idx={$row['event_idx']}";
                                    $res_event_data = mysqli_query($self_con,$sql_event_data);
                                    $row_event_data = mysqli_fetch_array($res_event_data);

                                    if(strpos($row_event_data['event_info'], "other") !== false){
                                        $event_other_txt = $row['other'];
                                    }
                                    else{
                                        $event_other_txt = "";
                                    }
                            ?>
                                <tr>
                                    <td><input type="checkbox" class="check" name="event_idx" value="<?php echo $row['request_idx'];?>" data-name="<?=$row['name']?>" data-mobile="<?=$row[mobile]?>"  data-email="<?=$row['email']?>" data-job="<?=$row[job]?>"  data-event_code="<?=$row[event_code]?>"  data-counsult_date="<?=$row[counsult_date]?>" data-sp="<?=$row[sp]?>" data-request_idx="<?php echo $row['request_idx'];?>"></td>
                                    <td><?=$sort_no?></td>
                                    <td style="font-size:12px;"><?=$row['name']?><br>
                                        <a onclick="window.open('mypage_pop_activity_list.php?request_idx='+'<?=$row[request_idx]?>','','top=300,left=300,width=800,height=500,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no, status=no')" >[보기]</a>
                                    </td>
                                    <td><?=$row[mobile]?></td>
                                    <td><a onclick="window.open('/event/event.html?pcode=<?=$row_event_data[pcode]?>&sp=<?=$row_event_data[event_name_eng]?>','','toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=600');"> <?=$row_event_data[event_title]?></a><br><a onclick="window.open('mypage_pop_member_list.php?eventid='+'<?=$row[event_idx]?>','','top=300,left=300,width=800,height=500,toolbar=no,menubar=no,scrollbars=yes, resizable=yes,location=no, status=no')" >[신청자보기]</a></td></td>
                                    <td>
                                        <?
                                            if($row_event_data['sms_idx1'] != 0)
                                            {
                                                $sql = "select reservation_title from Gn_event_sms_info where sms_idx='$row_event_data[sms_idx1]'";
                                                $res = mysqli_query($self_con,$sql);
                                                $sms_row = mysqli_fetch_array($res);
                                                $sql = "select count(*) from Gn_event_sms_step_info where sms_idx='$row_event_data[sms_idx1]'";
                                                $res = mysqli_query($self_con,$sql);
                                                $step_row = mysqli_fetch_array($res);
                                                $sql = "select count(*) from Gn_MMS where sms_idx='$row_event_data[sms_idx1]' and request_idx='$row[request_idx]' and result=0";
                                                $res = mysqli_query($self_con,$sql);
                                                $send_row = mysqli_fetch_array($res);
                                                echo "<a onclick=\"javascript:window.open('/mypage_reservation_create.php?sms_idx=$row_event_data[sms_idx1]','','toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=600');\">$sms_row[0]<br><strong>($step_row[0]/$send_row[0])</strong></a>";
                                            }
                                        ?>
                                    </td>
                                    <td><?
                                            if($row_event_data['stop_event_idx'] != 0)
                                            {
                                                $sql = "select event_title from Gn_event where event_idx='$row_event_data[stop_event_idx]'";
                                                $res = mysqli_query($self_con,$sql);
                                                $sms_row = mysqli_fetch_array($res);
                                                echo "$sms_row[0]";
                                            }else
                                            {
                                                echo "<strong>OFF</strong>";
                                            }
                                        ?>
                                    </td>
                                    <td><?=$row_event_data[mobile]?></td>
                                    <td><?=$row['regdate']?></td>
                                    <td>
                                        <div class="popup_holder popup_text">신청정보
                                            <div class="popupbox" style="height: 120px;width: 170px;left: 63px;top: -100px;display:none;">
                                            [신청정보보기]<br>
                                                &nbsp;>성별:<? if($row[sex] == "m") echo " 남자"; else if($row[sex] == "f") echo " 여자"; else echo " "; ?><br>
                                                &nbsp;>출생년도:<?=$row[birthday];?><br>
                                                &nbsp;>소속/직업:<?=$row[job];?><br>
                                                &nbsp;>이메일:<?=$row['email'];?><br>
                                                &nbsp;>거주주소:<?=$row[addr];?><br>
                                                &nbsp;>가입여부:<?=$row[join_yn];?><br>
                                                &nbsp;>기타정보:<?=$row[consult_date];?><br>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mypage_request_edit.php?request_idx=<?php echo $row['request_idx'];?>">수정</a> /
                                        <a href="javascript:deleteRow('<?php echo $row['request_idx'];?>','<?php echo $row['sp'];?>')">삭제</a>
                                    </td>
                                </tr>
                            <?
                                    $sort_no--;
                                }
                            ?>
                            <tr>
                                <td colspan="15">
                                    <?
                                    page_f($page,$page2,$intPageCount,"pay_form");
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="15" style="text-align: right;">
                                    <div class="popup_holder" style="display:inline-block"> <!--Parent-->
                                        <input type="button" value="엑셀 다운받기" class="button" onclick="excel_down('/excel_down/excel_mypage_request_list.php');return false;" style="cursor: pointer">
                                    </div>
                                </td>
                            </tr>
                            <?
                            }else{
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
            </form><form id="excel_down_form" name="excel_down_form"  target="excel_iframe" method="post">
                    <input type="hidden" name="grp_id" value="" />
                    <input type="hidden" name="box_text" value="" />
                    <input type="hidden" name="excel_sql" value="<?=$excel_sql?>" />
                </form>

                <iframe name="excel_iframe" style="display:none;"></iframe>
        </div>
    </div>
    <span class="tooltiptext-bottom" id="event_other" style="display:none;">
        <p class="title_app">답변 내용<span onclick="cancel()" style="float:right;cursor:pointer;">X</span></p>
        <table class="table table-bordered" style="width: 97%;">
            <tbody>
                <tr class="hide_spec">
                    <textarea name="set_event_other_req" id="set_event_other_req" style="border:none;width:90%; height:100px;font-size: 12px;padding:20px;" disabled></textarea></td>
                </tr>
            </tbody>
        </table>
    </span>
    <div id="tutorial-loading"></div>
    <!-- <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script> -->
    <Script>
    function newpop(){
        var win = window.open("mypage_pop_link_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
    }
    $(function() {
        $('#searchBtn').on("click", function() {
            newpop();
        });
        $('#searchEventBtn').on("click", function() {
            newpop();
        });
        $('#searchstepBtn').on("click", function() {
            var win = window.open("mypage_pop_message_list_for_addstep.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
        });
    })
    $(document).on("keypress", "#search_text", function(e) {
        if (e.which == 13) {
            pay_form.submit();
        }
    });
    function newpop(){
        var win = window.open("mypage_pop_link_list.php", "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
    }
    function show_txt_detail(txt){
        $("#set_event_other_req").val(txt);
        $("#event_other").show();
        $("#tutorial-loading").show();
        $('body,html').animate({
            scrollTop: 200 ,
            }, 100
        );
    }
    function cancel(){
        $("#event_other").hide();
        $("#tutorial-loading").hide();
    }
    $(function(){
        $('#popBtn').on("click",function(){
            newpop()
        });
        $('#allChk').on("change",function(){
            $('input[name=event_idx]').prop("checked", $(this).is(":checked"));
        })

        $('#eventAddBtn').on("click", function() {
            var cnt = 0;
            var event_idx = "";
            var html = "";
            $('input[name=event_idx]').each(function() {
               if($(this).is(":checked") == true) {
                   cnt++;
                   if(event_idx != "") event_idx += ",";
                   event_idx += $(this).val();
                   html+= '<tr>';
                   html+= '    <td>';
                   html+= '        <input type="hidden" id="request_idx[]" name="request_idx[]" value="'+$(this).data("request_idx")+'" style="width:135px;">';
                   html+= '        <input type="text" id="name[]" name="name[]" value="'+$(this).data("name")+'" style="width:135px;">';
                   html+= '    </td>';
                   html+= '    <td>';
                   html+= '        <input type="text" id="mobile[]" name="mobile[]" value="'+$(this).data("mobile")+'" style="width:135px;">';
                   html+= '    </td>';
                   html+= '    <td>';
                   html+= '        <input type="text" id="email[]" name="email[]" value="'+$(this).data("email")+'" style="width:135px;">';
                   html+= '    </td>';
                   html+= '    <td>';
                   html+= '        <input type="text" id="job[]" name="job[]" value="'+$(this).data("job")+'" style="width:135px;">';
                   html+= '    </td>';
                   html+= '    <td>';
                   html+= '        <input type="text" id="sp[]" name="sp[]" value="'+$(this).data("sp")+'" style="width:135px;">';
                   html+= '    </td>';
                   html+= '</tr>';
               }
            });
            $('#event_receive_info').html(html);
            if(cnt == 0) {
                alert('이벤트추가하실 신청자를 선택해주세요.');
                return;
            }
            var phoneno = $(this).siblings().eq(0).find("input").val();
            $('.ad_layer5').lightbox_me({
                centered: true,
                onLoad: function() {
                }
            });
        });

        $('#popCloseBtn').on("click", function() {
            $('.lb_overlay, .ad_layer4').hide();
        });
        $('#popSaveBtn').on("click", function() {
            if($('#event_idx').val() == "") {
                alert('예약문자를 선택해주세요.')
                return;
            }
            if($('#reservation_date').val() == "") {
                alert('예약문자를 선택해주세요.')
                return;
            }
            $('#addForm').submit();
        });
        $('#popEventCloseBtn').on("click", function() {
            $('.lb_overlay, .ad_layer5').hide();
        });
        $('#popstepCloseBtn').on("click", function() {
            $('.lb_overlay, .6').hide();
        });
        $('#popEventSaveBtn').on("click", function() {
            if($('#event_idx').val() == "") {
                alert('이벤트를 선택해주세요.')
                return;
            }
            if($('#reservation_date').val() == "") {
                alert('예약문자를 선택해주세요.')
                return;
            }
            $('#addFormEvent').submit();
        });
        $('#popstepSaveBtn').on("click", function() {
            if($('#event_idx').val() == "") {
                alert('이벤트를 선택해주세요.')
                return;
            }
            if($('#step_sms_title').val() == "") {
                alert('스텝문자를 선택해주세요.')
                return;
            }
            $('#addFormstep').submit();
        });
    })
    function deleteRow(request_id, org_event_code) {
        if(confirm('삭제하시겠습니까?')) {
            $.ajax({
                 type:"POST",
                 url:"mypage.proc.php",
                 data:{
                     mode : "request_del",
                     request_idx: request_id,
                     org_event_code : org_event_code
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
    function deleteMultiRow() {
        var check_array = $(".list_table").children().find(".check");
        var no_array = [];
        var index = 0;
        check_array.each(function(){
            if($(this).prop("checked") && $(this).val() > 0)
                no_array[index++] = $(this).val();
        });

        if(no_array.length == 0){
            alert("삭제할 신청창을 선택하세요.");
            return;
        }
        if(confirm('삭제하시겠습니까?')) {
            $.ajax({
                type:"POST",
                url:"/admin/ajax/delete_func.php",
                dataType:"json",
                data:{admin:0, delete_name:"mypage_request_list", id:no_array.toString()},
                success: function(data){
                    console.log(data);
                    if(data == 1){
                        alert('삭제 되었습니다.');
                        window.location.reload();
                    }
                }
        })
        }
    }
    function removeAll() {
        var no ="";
        if(confirm('모든 페이지 데이타를 모두 삭제합니다.  삭제하시겠어요?')) {
            $.ajax({
                type:"POST",
                url:"/admin/ajax/delete_func.php",
                data:{admin:0, delete_name:"mypage_request_list", mem_id:'<?=$_SESSION['one_member_id']?>'},
                success:function(){
                    alert('삭제되었습니다.');
                    location.reload();
                },
                error: function(){
                    alert('삭제 실패');
                }
            });
        }
    }
    function showInfo() {
        if($('#outLayer').css("display") == "none") {
            $('#outLayer').show();
        } else {
            $('#outLayer').hide();
        }
    }
    function newMessageEvent(){ // test 메시지조회
        // var cnt = 0;
        // $('input[name=event_idx]').each(function() {
        //     if($(this).is(":checked") == true) {
        //         cnt++;
        //     }
        // });
        // if(cnt == 0) {
        //     alert('스텝문자 추가하실 신청자를 선택해주세요.');
        //     return;
        // }

        // var req_idx_arr = new Array();
        // var i = 0;
        // $("input[name=event_idx]:checked").each(function(){
        //     req_idx_arr[i] = $(this).attr('data-request_idx');
        //     i++;
        // });
        // var win = window.open("../mypage_pop_message_list_for_addstep.php?req_idx="+req_idx_arr.join(","), "event_pop", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");
    }

    search_text
    </Script>
    <link href='/css/sub_4_re.css' rel='stylesheet' type='text/css'/>
    <script type="text/javascript" src="jquery.lightbox_me.js"></script>
    <script type="text/javascript" src="/js/mms_send.js"></script>
    <script type="text/javascript" src="/plugin/tablednd/js/jquery.tablednd.0.7.min.js"></script>
    <style>
    .ad_layer4 {
        width: 903px;
        height: 498px;
        background-color: #fff;
        border: 2px solid #24303e;
        position: relative;
        box-sizing: border-box;
        padding: 30px 30px 50px 30px;

        display: none;
    }
    .ad_layer5 {
        width: 903px;
        height: 498px;
        background-color: #fff;
        border: 2px solid #24303e;
        position: relative;
        box-sizing: border-box;
        padding: 30px 30px 50px 30px;

        display: none;
    }
    .ad_layer6 {
        width: 903px;
        height: 498px;
        background-color: #fff;
        border: 2px solid #24303e;
        position: relative;
        box-sizing: border-box;
        padding: 30px 30px 50px 30px;

        display: none;
    }
    .ui-widget-content {
        border: none !important;
    background: #ffffff/*{bgColorContent}*/ url(images/ui-bg_flat_75_ffffff_40x100.png)/*{bgImgUrlContent}*/ 50%/*{bgContentXPos}*/ 50%/*{bgContentYPos}*/ repeat-x/*{bgContentRepeat}*/;
        color: #222222/*{fcContent}*/;
    }
    </style>
        <div class="ad_layer5">
            <div class="layer_in">
                <span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>
                <form method="post" name="addFormEvent" id="addFormEvent"  action="mypage.proc.php" enctype="multipart/form-data">
                    <input type="hidden" name="mode" value="request_event_add">
                    <input type="hidden" name="m_id" value="<?php echo $_SESSION['one_member_id'];?>">
                    <div class="pop_title">
                        신규 신청창 수동추가
                    </div>
                    <div class="info_box">
                        <table class="info_box_table" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <th class="w200">신청내용</td>
                                    <td style="height:35px;text-align:left;" >
                                        <input type="hidden" id="event_idx_event" name="event_idx_" value="" style="width:95px;">
                                        <input type="text" id="event_name_eng_event" name="sp_" value="" style="width:95px;">
                                        <input type="hidden" id="event_pcode_event" name="event_pcode_" value="" style="width:95px;">
                                        <input type="button" value="고객신청그룹 조회" class="button " id="searchEventBtn">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pop_title">
                        신규신청자 정보
                    </div>
                    <div class="info_box">
                        <table class="info_box_table" cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="w200">신청자이름</th>
                                    <th class="w200">신청자휴대폰</th>
                                    <th class="w200">이메일</th>
                                    <th class="w200">직업</th>
                                    <th class="w200">신청채널</th>
                                </tr>
                            </thead>
                            <tbody id="event_receive_info">
                                <tr>
                                    <td>
                                        <input type="text" id="name[]" name="name[]" value="" style="width:135px;">
                                    </td>
                                    <td>
                                        <input type="text" id="mobile[]" name="mobile[]" value="" style="width:135px;">
                                    </td>
                                    <td>
                                        <input type="text" id="email[]" name="email[]" value="" style="width:135px;">
                                    </td>
                                    <td>
                                        <input type="text" id="job[]" name="job[]" value="" style="width:135px;">
                                    </td>
                                    <td>
                                        <input type="text" id="sp[]" name="sp[]" value="" style="width:135px;">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="ok_box">
                        <input type="button" value="취소" class="button "  id="popEventCloseBtn">
                        <input type="button" value="저장" class="button" id="popEventSaveBtn">
                    </div>
                </form>
            </div>
        </div>
	</div>	
</div>
<?
include_once "_foot.php";
?>