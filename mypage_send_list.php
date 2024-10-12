<?
$path="./";
include_once "_head.php";
$logs = new Logs("iamlog.txt", false);
if(!$_SESSION['one_member_id']){
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
?>
    <script type="text/javascript" src="/js/mms_send.js"></script>
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
                                <div class="popup_holder popup_text">예약메시지 발송내역
                                    <div class="popupbox" style="display:none; height: 56px;width: 220px;left: 178px;top: -37px;">예약문자가 이벤트 신청고객에게 발송된 결과를 보여줍니다.<br><br>
                                        <a class = "detail_view" href="https://tinyurl.com/2p94y2kw" target="_blank">[자세히 보기]</a>
                                    </div>
                                </div>
                            </li>
                            <li style="float:right;">
                            </li>
                            <p style="clear:both"></p>
                        </div>
                        <div class="p1">
                            <select name="search_key" class="select">
                                <option value="send_num" <?if($_REQUEST['search_key'] == "send_num") echo "selected";?>>발송번호</option>
                                <option value="recv_num" <?if($_REQUEST['search_key'] == "recv_num") echo "selected";?>>수신번호</option>
                                <option value="sms.event_name_eng"   <?if($_REQUEST['search_key'] == "sms.event_name_eng") echo "selected";?>>수신키워드</option>
                            </select>
                            <input type="text" name="search_text" placeholder="" id="search_text" value="<?=$_REQUEST['search_text']?>" style="height:30px;"/>
                            <input type="date" name="startdate" value="<?=$_REQUEST[startdate]?>" style="padding: 6px">
                            <input type="date" name="enddate" value="<?=$_REQUEST[enddate]?>" style="padding: 6px">
                            <a href="javascript:pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
                            <div style="float:right;">
							<div class="popup_holder" style="display:inline-block"> <!--Parent-->
								<input type="button" value="발송예정내역" class="button" onclick="location.href='mypage_wsend_list.php'">
							</div>							
						</div>
                        </div>
                        <div >
                            <a href="?">전체내역</a> &nbsp;|&nbsp; 
							<a href="?channel=2" style="color:<?=$_REQUEST['channel']==2?"#f00":""?>">오토회원가입신청</a>&nbsp;|&nbsp;
							<a href="?channel=3" style="color:<?=$_REQUEST['channel']==3?"#f00":""?>">고객신청</a>&nbsp;|&nbsp;
							<a href="?channel=4" style="color:<?=$_REQUEST['channel']==4?"#f00":""?>">신청스텝예약</a>&nbsp;|&nbsp;
							<a href="?channel=8" style="color:<?=$_REQUEST['channel']==8?"#f00":""?>">새디비예약</a>
					    </div>
                        <div>
                            <div class="p1">
                            </div>
                            <div>
                                <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="width:7%;"><label><input type="checkbox" onclick="check_all(this,'fs_idx');" />번호</label></td>
                                        <td style="width:7%;">발송자명</td>
                                        <td style="width:10%;">발송번호</td>
                                        <td style="width:10%;">수신번호</td>
                                        <td style="width:5%;">수신자명</td>
                                        <td style="width:5%;">세트</td>
                                        <td style="width:5%;">회차</td>
                                        <td style="width:8%;">문자제목</td>
                                        <td style="width:8%;">문자내용</td>
                                        <td style="width:3%;"><?=$_REQUEST['status2']=='2'?"예약일시":"첨부파일"?></td>
                                        <td style="width:6%;">신청키워드</td>
                                        <td style="width:10%;">신청일시</td>
                                        <td style="width:7%;">발송일시</td>
                                        <td style="width:7%;">성공실패</td>
										<td style="width:7%;">
											<a href="javascript:fs_multi_del()">삭제</a>
										</td>
                                    </tr>
                                    <?
                                    $logs->add_log("start");
                                    $sql_serch= " 1=1 ";
                                    $sql_serch.=" and mms.mem_id ='{$_SESSION['one_member_id']}' and sms_detail_idx is not null";
                                    $sql_serch .=" and (mms.result = 0 or (mms.result=1 and reservation < now())) ";
                                    $startdate = $_REQUEST['startdate'];
                                    $enddate = $_REQUEST['enddate'];

                                    if($startdate)
                                        $sql_serch .= " AND mms.up_date >= '$startdate 00:00:00'";
                                    if($enddate)
                                        $sql_serch .= " AND mms.up_date <= '$enddate 23:59:59'";

                                    if( $_REQUEST['search_text'])
                                    {
                                        $sql_serch.=" and (".$_REQUEST['search_key']." like '%{$_REQUEST['search_text']}%') ";
                                    }
                                    if($_REQUEST['channel'])
                                        $sql_serch .= " and type='$_REQUEST[channel]' ";
                                    // 상태 검색 추가
                                    if($_REQUEST['result'] == 1) {
                                        $sql_serch .= " and result = 0 and up_date is not null ";
                                    } elseif($_REQUEST['result'] == 2) {
                                        $sql_serch .= " and result = 1 and up_date is null ";
                                    } elseif($_REQUEST['result'] == 3) {
                                        $sql_serch .= " and result = 3";
                                    }
                                    $sql="select count(*) as cnt from Gn_MMS mms left join Gn_event_sms_info sms 
									on sms.sms_idx=mms.sms_idx where $sql_serch ";
                                    $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                                    $row=mysqli_fetch_array($result);
                                    mysqli_free_result($result);
                                    $logs->add_log("middle");
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
                                        $order_status="desc";
                                    if($_REQUEST['order_name'])
                                        $order_name=$_REQUEST['order_name'];
                                    else
                                        $order_name="reg_date";
                                    $intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);
                                    $sql="select mms.*,sms.event_name_eng sp from Gn_MMS mms left join Gn_event_sms_info sms 
									on sms.sms_idx=mms.sms_idx where $sql_serch  order by $order_name $order_status limit $int,$intPageSize";
                                    $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                                    $logs->add_log($sql);
                                    if($intRowCount)
                                    {
                                        $c=0;
                                        while($row=mysqli_fetch_array($result))
                                        {
                                            $sql_n="select mem_name from Gn_Member where mem_id='{$row['mem_id']}' ";
                                            $resul_n=mysqli_query($self_con,$sql_n);
                                            $row_n=mysqli_fetch_array($resul_n);
                                            $memo = $row_n[0];

                                            $sql_n="select mem_name from Gn_Member where mem_phone='{$row['recv_num']}'";
                                            $resul_n=mysqli_query($self_con,$sql_n);
                                            $row_n=mysqli_fetch_array($resul_n);
                                            $rname = $row_n[0];

                                            $recv_cnt=explode(",",$row['recv_num']);
                                            $sql_cs="select count(idx) as cnt from Gn_MMS_status where idx='{$row['idx']}' and status='0'";
                                            $resul_cs=mysqli_query($self_con,$sql_cs);
                                            $row_cs=mysqli_fetch_array($resul_cs);
                                            $success_cnt = $row_cs[0];

                                            $sql_sn="select * from Gn_MMS where idx='{$row['idx']}' ";
                                            $resul_sn=mysqli_query($self_con,$sql_sn);
                                            $row_sn=mysqli_fetch_array($resul_sn);
                                            $recv_cnt=explode(",",$row_sn['recv_num']);

                                            $total_cnt = count($recv_cnt);
                                            $sql_sn="select count(*) as cnt from Gn_event_sms_step_info where sms_idx='$row[sms_idx]' ";
                                            $sresul=mysqli_query($self_con,$sql_sn);
                                            $crow=mysqli_fetch_array($sresul);
                                            $total_cnt_ = $crow['cnt'];

                                            $sql_sn="select step from Gn_event_sms_step_info where sms_detail_idx='$row[sms_detail_idx]' ";
                                            $sresul=mysqli_query($self_con,$sql_sn);
                                            $crow=mysqli_fetch_array($sresul);
                                            if($success_cnt > $total_cnt )
                                                $success_cnt = $total_cnt;
                                            ?>
                                            <tr>
                                                <td><label><input type="checkbox" name="fs_idx" value="<?=$row['idx']?>" /><?=$sort_no?></label></td>
                                                <td><?=$memo?></td>
                                                <td><?=$row['send_num']?></td>
                                                <td style="font-size:12px;">
                                                    <a href="javascript:show_recv('show_recv_num','<?=$c?>','수신번호')"><?=str_substr($row['recv_num'],0,14,'utf-8')?> <?=$row['reservation']?"<br>".$row['reservation']:""?></a>
                                                    <!--span style="color:#F00;">(<?=count($recv_cnt)?>)</span--><input type="hidden" name="show_recv_num" value="<?=$row['recv_num']?>"/>
                                                </td>
                                                <td><?
                                                    if($rname != '')
                                                        echo $rname;
                                                    else{
                                                        $group_idx = $row['request_idx'] * -1;
                                                        $sql_group="select * from Gn_MMS_GROUP where idx='$group_idx' ";
                                                        $gresult=mysqli_query($self_con,$sql_group);
                                                        $grow=mysqli_fetch_array($gresult);
                                                        echo $grow['grp'];
                                                    }
                                                    ?></td>
                                                <td><?=$total_cnt_?></td>
                                                <td><?=$crow[step]?></td>
                                                <td>
                                                    <a href="javascript:void(0)" onclick="show_recv('show_title','<?=$c?>','문자제목')"><?=str_substr($row['title'],0,14,'utf-8')?></a>
                                                    <input type="hidden" name="show_title" value="<?=$row['title']?>"/>
                                                </td>
                                                <td style="font-size:12px;">
                                                    <a href="javascript:void(0)" onclick="show_recv('show_content','<?=$c?>','문자내용')"><?=str_substr($row['content'],0,30,'utf-8')?></a><input type="hidden" name="show_content" value="<?=$row['content']?>"/>
                                                </td>
                                                <?if($_REQUEST['status2']=='2'){?>
                                                    <td style="width:5%;"><?if($row['up_date']!=''&&$row[result]==0){?>완료<?}elseif($row['up_date']==''&&$row[result]==1){?>발송실패<?}else{?>실패<?}?></td>
                                                <?}?>
                                                <td>
                                                    <?if ($_REQUEST['status2']==2){ echo substr($row['reservation'],0,16); }else{?>
                                                        <a href="javascript:void(0)" onclick="show_recv('show_jpg','<?=$c?>','첨부파일')"><?=str_substr($row['jpg'],0,20,'utf-8')?></a><input type="hidden" name="show_jpg" value="<?=$row['jpg']?>"/>
                                                        <a href="javascript:void(0)" onclick="show_recv('show_jpg1','<?=$c?>','첨부파일')"><?=str_substr($row['jpg1'],0,20,'utf-8')?></a><input type="hidden" name="show_jpg1" value="<?=$row['jpg1']?>"/>
                                                        <a href="javascript:void(0)" onclick="show_recv('show_jpg2','<?=$c?>','첨부파일')"><?=str_substr($row['jpg2'],0,20,'utf-8')?></a><input type="hidden" name="show_jpg2" value="<?=$row['jpg2']?>"/>
                                                    <?}?>
                                                </td>
                                                <td><?=$row['sp']?></td>
                                                <td style="font-size:12px;"><?=substr($row['reg_date'],0,16)?></td>
                                                <td style="font-size:12px;"><?
                                                    if($row['up_date'] == ""){
                                                        echo "미수신";
                                                    }
                                                    else{
                                                        echo substr($row['up_date'],0,16);
                                                    }
                                                ?></td>
                                                <td><?
                                                    if($row['up_date'] == ""){
                                                        echo "실패";
                                                    }
                                                    else{
                                                        $statistic = ($success_cnt) .'/'. ($total_cnt-$success_cnt);
                                                        echo $statistic;
                                                    }
                                                ?></td>
												<td>
													<a href="javascript:fs_del_num('<?=$row['idx']?>')">삭제</a>
												</td>
                                            </tr>
                                            <?
                                            $c++;
                                            $sort_no--;
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="14">
                                                <?
                                                page_f($page,$page2,$intPageCount,"pay_form");
                                                ?>
                                            </td>
                                        </tr>
                                        <?
                                    }
                                    else
                                    {
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
                    </form>
                </div>
            </div>
        </div>
    </div>
    <Script>
	function fs_multi_del(){
		if(confirm('삭제하시겠습니까?'))
		{
		 	var check_array = $(".list_table").children().find(":checkbox");
            var no_array = [];
            var index = 0;
            check_array.each(function(){
                if($(this).prop("checked") && $(this).val() > 0)
                    no_array[index++] = $(this).val();
            });
            console.log(no_array);
			$.ajax({
				 type:"POST",
				 url:"/ajax/ajax_session.php",
				 data:{
						fs_del_num_s:no_array.toString()
					  },
				 success:function(data){$("#ajax_div").html(data)}
			})
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
        function showInfo() {
            if($('#outLayer').css("display") == "none") {
                $('#outLayer').show();
            } else {
                $('#outLayer').hide();
            }
        }

    </Script>


    <script>
        //회원가입체크
        function join_check(frm,modify)
        {
            if(!wrestSubmit(frm))
                return  false;
            var id_str="";
            var app_pwd="";
            var web_pwd="";
            var phone_str="";
            if(document.getElementsByName('pwd')[0])
                app_pwd=document.getElementsByName('pwd')[0].value;
            if(document.getElementsByName('pwd')[1])
                web_pwd=document.getElementsByName('pwd')[1].value;
            if(frm.id)
                id_str=frm.id.value;
            var msg=modify?"수정하시겠습니까?":"등록하시겠습니까?";
            var email_str=frm.email_1.value+"@"+frm.email_2.value+frm.email_3.value;
            if(!modify)
                phone_str=frm.mobile_1.value+"-"+frm.mobile_2.value+"-"+frm.mobile_3.value;
            var birth_str=frm.birth_1.value+"-"+frm.birth_2.value+"-"+frm.birth_3.value;
            var is_message_str=frm.is_message.checked?"Y":"N";

            var bank_name = frm.bank_name.value;
            var bank_account = frm.bank_account.value;
            var bank_owner = frm.bank_owner.value;

            if(confirm(msg))
            {
                $.ajax({
                    type:"POST",
                    url:"ajax/ajax.php",
                    data:{
                        join_id:id_str,
                        join_nick:frm.nick.value,
                        join_pwd:app_pwd,
                        join_web_pwd:web_pwd,
                        join_name:frm.name.value,
                        join_email:email_str,
                        join_phone:phone_str,
                        join_add1:frm.add1.value,
                        join_zy:frm.zy.value,
                        join_birth:birth_str,
                        join_is_message:is_message_str,
                        join_modify:modify,
                        bank_name:bank_name,
                        bank_account:bank_account,
                        bank_owner:bank_owner
                    },
                    success:function(data){$("#ajax_div").html(data)}
                })
            }
        }

        function monthly_remove(no) {
            if(confirm('정기결제 해지신청하시겠습니까?')) {
                $.ajax({
                    type:"POST",
                    url:"ajax/ajax_add.php",
                    data:{
                        mode:"monthly",
                        no:no
                    },
                    success:function(data){alert('신청되었습니다.');location.reload();}
                })

            }
        }
    </script>
    <script language="javascript" src="./js/rlatjd_fun.js?m=<?php echo time();?>"></script>
    <script language="javascript" src="./js/rlatjd.js?m=<?php echo time();?>"></script>
    <div id='open_pop_div' class="open_1">
        <div class="open_2" onmousedown="down_notice(open_pop_div,event)" onmousemove="move(event)" onmouseup="up()">
            <li class="open_2_1 group_title_open">그룹 전화번호</li>
            <li class="open_2_2"><a href="javascript:void(0)" onClick="close_div(open_pop_div)"><img src="images/div_pop_01.jpg" /></a></li>
        </div>
        <div class="open_3" style="width:500px;">
            <iframe id="pop_iframe" src="" width="100%" height="650" frameborder="0" scrolling="auto"></iframe>
        </div>
    </div>
<?
$logs->add_log("end");
$logs->write_to_file();
include_once "_foot.php";
?>