<?
$path="./";
include_once "_head.php";
if(!$_SESSION['one_member_id']){
    $chk = false;
?>
    <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>
    <script language="javascript">
        location.replace('/ma.php');
    </script>
<?
    exit;
}
// $sql="select * from Gn_Member  where mem_id='".$_SESSION['one_member_id']."'";
// $sresul_num=mysqli_query($self_con,$sql);
// $member = $data=mysqli_fetch_array($sresul_num);
?>
<script>
$(function(){
    $(".popbutton").click(function(){
        $('.ad_layer_info').lightbox_me({
            centered: true,
            onLoad: function() {}
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
</style>
<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
<div class="big_sub">
    <?php include_once "mypage_base_navi.php";?>
    <div class="m_div">
        <div class="join">
        <!--//마이페이지 결제정보-->
        <?
        $sql_serch=" buyer_id ='{$_SESSION['one_member_id']}' ";
        if($_REQUEST[search_date]){
            if($_REQUEST[rday1]){
                $start_time=strtotime($_REQUEST[rday1]);
                $sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) >=$start_time ";
            }
            if($_REQUEST[rday2]){
                $end_time=strtotime($_REQUEST[rday2]);
                $sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) <= $end_time ";
            }
        }
        $sql="select count(no) as cnt from tjd_pay_result where $sql_serch ";
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
            $order_status="desc";
        if($_REQUEST['order_name'])
            $order_name=$_REQUEST['order_name'];
        else
            $order_name="date";
        $intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);
        $sql="select * from tjd_pay_result where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
        $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
?>
            <div class="ad_layer_info">
                <div class="layer_in">
                    <span class="layer_close close" ><img src="/images/close_button_05.jpg"></span>
                    <div class="pop_title">정기결제해지</div>
                    <div class="info_text">
                        <p>정기결제일 1주일 전 해지 시 당월 적용되며, 1주일 이내 해지 시 익월 적용됩니다</p>
                    </div>
                </div>
            </div>
            <form name="pay_form" action="" method="post" class="my_pay">
                <input type="hidden" name="order_name" value="<?=$order_name?>"  />
                <input type="hidden" name="order_status" value="<?=$order_status?>"/>
                <input type="hidden" name="page" value="<?=$page?>" />
                <input type="hidden" name="page2" value="<?=$page2?>" />
                <div class="a1">
                    <li style="float:left;">구매상품정보 및 정기결제해지관리</li>
                    <li style="float:right;"></li>
                    <p style="clear:both"></p>
                </div>
                <div>
                    <div class="p1">
                        <a href="mypage_payment.php" class="a_btn_2">전체보기</a>
<?
                        $search_date=array("date"=>"결제일","end_date"=>"만료(해지)일");
                        foreach($search_date as $key=>$v){
                            $checked=$_REQUEST[search_date]==$key?"checked":"";
?>
                            <label><input name="search_date" type="radio" value="<?=$key?>" <?=$checked?> /><?=$v?></label>
<?
                        }
?>
                        <input type="date" name="rday1" placeholder="" id="rday1" value="<?=$_REQUEST[rday1]?>"/> ~
                        <input type="date" name="rday2" placeholder="" id="rday2" value="<?=$_REQUEST[rday2]?>"/>
                        <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>
                    </div>
                    <div>
                        <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td style="width:6%;">번호</td>
                                <td style="width:6%;">회원등급</td>
                                <td style="width:15%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'date',pay_form.order_status.value)">결제일<? if($_REQUEST['order_name']=="date"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                                <td style="width:15%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'end_date',pay_form.order_status.value)">만료(해지)일<? if($_REQUEST['order_name']=="end_date"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                                <td style="width:6%"><a href="javascript:void(0)" onclick="order_sort(pay_form,'month_cnt',pay_form.order_status.value)">개월수<? if($_REQUEST['order_name']=="month_cnt"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                                <td style="width:8%"><a href="javascript:void(0)" onclick="order_sort(pay_form,'fujia_status',pay_form.order_status.value)">결제상품<? if($_REQUEST['order_name']=="fujia_status"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                                <td style="width:10%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'payMethod',pay_form.order_status.value)">결제방식<? if($_REQUEST['order_name']=="payMethod"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                                <td style="width:9%;">결제한<br />폰 수</td>
                                <td style="width:9%;">등록된<br />건 수</td>
                                <td style="width:10%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'TotPrice',pay_form.order_status.value)">결제금액<? if($_REQUEST['order_name']=="TotPrice"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                                <td style="width:12%;"><a href="javascript:void(0)" onclick="order_sort(pay_form,'end_status',pay_form.order_status.value)">상태<? if($_REQUEST['order_name']=="end_status"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                            </tr>
                            <?
                            if($intRowCount){
                                while($row=mysqli_fetch_array($result)){
                                    $sql="select service_type from Gn_Member  where mem_id='{$row['buyer_id']}'";
                                    $sresul_num=mysqli_query($self_con,$sql);
                                    $srow=mysqli_fetch_array($sresul_num);
                                    if($srow['service_type'] == "0") $service_type = "FREE";
                                    else  if($srow['service_type'] == "1") $service_type = "이용자";
                                    else  if($srow['service_type'] == "2") $service_type = "리셀러";
                                    else  if($srow['service_type'] == "3") $service_type = "분양자";?>
                                    <tr>
                                        <td><?=$sort_no?></td>
                                        <td><?=$service_type?></td>
                                        <td style="font-size:12px;"><?=$row[date]?></td>
                                        <td style="font-size:12px;"><?=$row['end_date']?></td>
                                        <?if($row['month_cnt'] < 120){?>
                                            <td><?=$row['month_cnt']?>개월</td>
                                        <?}else{?>
                                            <td>정기</td>
                                        <?}?>
                                        <td>문자</td>
                                        <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                                        <td><?=$row['add_phone']?></td>
                                        <td><?=$row[phone_cnt]?></td>
                                        <td><?=number_format($row[TotPrice])?>원</td>
                                        <td>
                                            <?=$pay_result_status[$row['end_status']]?>
                                            <?if($row['monthly_yn'] == "Y") {?>
                                            <div style="border:1px solid #000;padding:3px;background:#D8D8D8; font-size:10px" >
                                                <?if($row['monthly_status'] == "N"){?>
                                                    <a href="javascript:void(monthly_remove('<?php echo $row['no'];?>', '<?php echo $row['member_type'];?>'))">정기결제해지</a>
                                                <?}else if($row['monthly_status'] == "R"){?>
                                                    <a href="javascript:void()">해지대기</a>
                                                <?}else if($row['monthly_status'] == "Y"){?>
                                                    <a href="javascript:void()">해지완료</a>
                                                <?}?>
                                            </div>
                                            <span class="popbutton pop_view pop_right">?</span>
                                            <?}?>
                                        </td>
                                    </tr>
                                    <?
                                        $sort_no--;
                                }

                                $sql_serch=" buyer_id ='{$_SESSION['one_member_id']}' ";
                                if($_REQUEST[search_date]){
                                    if($_REQUEST[rday1]){
                                        $start_time=strtotime($_REQUEST[rday1]);
                                        $sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) >=$start_time ";
                                    }
                                    if($_REQUEST[rday2]){
                                        $end_time=strtotime($_REQUEST[rday2]);
                                        $sql_serch.=" and unix_timestamp({$_REQUEST[search_date]}) <= $end_time ";
                                    }
                                }
                                $sql="select count(no) as cnt from tjd_pay_result_db where $sql_serch ";
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
                                    $order_status="desc";
                                if($_REQUEST['order_name'])
                                    $order_name=$_REQUEST['order_name'];
                                else
                                    $order_name="end_status";
                                $intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);
                                $sql="select * from tjd_pay_result_db where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
                                $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                                while($row=mysqli_fetch_array($result)){
                                    /*$num_arr=array();
                                    $sql_num="select sendnum from Gn_MMS_Number where mem_id='{$row['buyer_id']}' and end_date='{$row['end_date']}' ";
                                    $resul_num=mysqli_query($self_con,$sql_num);
                                    while($row_num=mysqli_fetch_array($resul_num))
                                        array_push($num_arr,$row_num[sendnum]);*/
                                    $sql="select mem_leb from Gn_Member  where mem_id='{$row['buyer_id']}'";
                                    $sresul_num=mysqli_query($self_con,$sql);
                                    $srow=mysqli_fetch_array($sresul_num);

                                    if($srow['mem_leb'] == "22") $mem_leb = "일반회원";
                                    else  if($srow['mem_leb'] == "50") $mem_leb = "사업회원";
                                    else $mem_leb = "";
                                ?>
                                    <tr>
                                        <td><?=$sort_no?></td>
                                        <td><?=$mem_leb?></td>
                                        <td style="font-size:12px;"><?=$row[date]?></td>
                                        <td style="font-size:12px;"><?=$row['end_date']?></td>
                                        <td><?=$row['month_cnt']?>개월</td>
                                        <td>디버</td>
                                        <td><?=$pay_type[$row[payMethod]]?$pay_type[$row[payMethod]]:"카드"?></td>
                                        <td><?=$row['add_phone']?></td>
                                        <td><?=$row[phone_cnt]?></td>
                                        <td><?=number_format($row[TotPrice])?>원</td>
                                        <td>
                                            <?=$pay_result_status[$row['end_status']]?>
                                            <?if($row['monthly_yn'] == "Y" && $row['end_status'] == "Y") {?>
                                                <div style="border:1px solid #000;padding:3px; background:#D8D8D8; font-size:10px;" >
                                                    <?
                                                    if($row['monthly_status'] == "N"){?>
                                                        <a href="javascript:void(monthly_remove('<?php echo $row['no'];?>'))">정기결제해지</a>
                                                    <?}else if($row['monthly_status'] == "R"){?>
                                                        <a href="javascript:void()">해지대기</a>
                                                    <?}else if($row['monthly_status'] == "Y"){?>
                                                        <a href="javascript:void()">해지완료</a>
                                                    <?}?>
                                                </div>
                                                <span class="popbutton pop_view pop_right">?</span>
                                            <?}?>
                                        </td>
                                    </tr>
                                <?  $sort_no--;
                                }?>
                                <tr>
                                    <td colspan="11">
                                        <?page_f($page,$page2,$intPageCount,"pay_form");?>
                                    </td>
                                </tr>
                        <?  }else{?>
                                <tr>
                                    <td colspan="11">
                                        검색된 내용이 없습니다.
                                    </td>
                                </tr>
                        <?  }?>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="detail_intro_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="width: 100%;max-width:768px;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border:none;background-color: rgb(130,199,54);border-top-right-radius: 5px;border-top-left-radius: 5px;">
                    <div>
                        <button type="button" class="close" data-dismiss="modal" style="opacity: 2">
                            <img src = "/iam/img/main/close_white.png" style="width:20px">
                        </button>
                    </div>
                    <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">위약금 항목과 기간별 위약금 정산 안내</div>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;text-align: center;width:100%">
                        <table class="table table-bordered">
                            <colgroup>
                                <col width="20%">
                                <col width="80%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="bold"  colspan="2" style = "text-align: left;font-size: 14px;">
                                        회원님의 첫 결제는 <span id="first_date"></span> 입니다.<br>
                                        잔여개월 수는 <span id="rest_month"></span>개월이고, 위약금은 <span id="penalty"></span>원입니다.<br>
                                        아래 계좌로 입금하시고 카카오상담창에 해지신청을 해주세요.<br>
                                        스텐다드차타드은행 617-20-109431 온리원연구소- (구,SC제일은행)
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="display:flex">
                    <button type="button" class="btn btn-default btn-submit" style="margin-left:auto;margin-right:auto;border-radius: 5px;width:40%;font-size:15px;background: #82c736;color: white" onclick="show_table()">위약금 도표 보기</button>
                    <button type="button" class="btn btn-default btn-submit" style="margin-left:auto;margin-right:auto;border-radius: 5px;width:40%;font-size:15px;background: #82c736;color: white" onclick="go_kakao()">카카오 상담창</button>
                </div>
            </div>
        </div>
    </div>
    <!-- 위약금 도표 팝업 -->
    <div id="penalty_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
        <div class="modal-dialog" style="width: 100%;max-width:768px;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header" style="border:none;background-color: rgb(130,199,54);border-top-right-radius: 5px;border-top-left-radius: 5px;">
                    <div>
                        <button type="button" class="close" data-dismiss="modal" style="opacity: 2">
                            <img src = "/iam/img/main/close_white.png" style="width:20px">
                        </button>
                    </div>
                    <div class="login_bold" style="margin-bottom: 0px;color: #ffffff;font-size: 22px;text-align: center">위약금 항목과 기간별 위약금 정산 안내</div>
                </div>
                <div class="modal-body">
                    <div class="container" style="margin-top: 20px;text-align: center;width:100%">
                        <p>
                            ※ 3년 약정 결제 해지에 대한 개월별 위약금 안내입니다.
                        </p>
                        <table class="table table-bordered">
                            <colgroup>
                                <col width="24%">
                                <col width="24%">
                                <col width="24%">
                                <col width="24%">
                                <col width="24%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="bold" style = "text-align: center;font-size: 14px;vertical-align: middle;background-color: #cbc5c5;">
                                        기간
                                    </th>
                                    <th class="bold" colspan="3" style = "text-align: center;vertical-align: middle;font-size: 14px;padding: 0px;">
                                        <table class="table table-bordered" style="margin: 0px;border: none;text-align: center;background-color: #cbc5c5;">
                                            <colgroup>
                                                <col width="32.5%">
                                                <col width="33%">
                                                <col width="32.5%">
                                            </colgroup>
                                            <thead>
                                                <tr>
                                                    <th colspan="3" style="text-align: center;">
                                                        위약금항목
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="border-bottom: none;">등록비</td>
                                                    <td style="border-bottom: none;">관리비</td>
                                                    <td style="border-bottom: none;">이용료</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </th>
                                    <th class="bold" style = "text-align: center;font-size: 14px;vertical-align: middle;background-color: #cbc5c5;">
                                        위약금
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                $sql_penalty = "select * from gn_penalty_list";
                                $res_penalty = mysqli_query($self_con,$sql_penalty);
                                while($row_penalty = mysqli_fetch_array($res_penalty)){
                                ?>
                                <tr>
                                    <td><?=$row_penalty[month]?>개월</td>
                                    <td><?=number_format($row_penalty[reg_money])?></td>
                                    <td><?=number_format($row_penalty[manage_money])?></td>
                                    <td><?=number_format($row_penalty[use_money])?></td>
                                    <td><?=number_format($row_penalty[penalty_money])?></td>
                                </tr>
                                <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="display:flex">
                    <button type="button" class="btn btn-default btn-submit" style="margin-left:auto;margin-right:auto;border-radius: 5px;width:40%;font-size:15px;background: #82c736;color: white" onclick="close_table()">잘 확인했습니다</button>
                </div>
            </div>
        </div>
    </div>
<script>
function monthly_remove(no, pay_type) {
    if(pay_type == "year-professional"){
        $.ajax({
            type:"POST",
            url:"ajax/ajax_add.php",
            dataType:'json',
            data:{
                mode:"get_status",
                no:no
            },
            success:function(data){
                $("#first_date").text(data.date);
                $("#rest_month").text(data.months);
                $("#penalty").text(data.penalty);
                $("#detail_intro_modal").modal('show');
            }
        });
        
    }
    else{
        if(confirm('정기결제 해지신청하시겠습니까?')) {
            $.ajax({
                type:"POST",
                url:"ajax/ajax_add.php",
                data:{
                    mode:"monthly",
                    no:no
                },
                success:function(data){
                    alert('해지신청되었습니다.담당자가 처리하면 해지완료가 됩니다.');
                    location.reload();
                }
            });
        }
    }
}
function monthly_remove_db(no) {
    if(confirm('정기결제 해지신청하시겠습니까?')) {
        $.ajax({
            type:"POST",
            url:"ajax/ajax_add.php",
            data:{
                mode:"monthly_db",
                no:no
            },
            success:function(data){
                alert('해지신청되었습니다.담당자가 처리하면 해지완료가 됩니다.');
                location.reload();
            }
        });
    }
}
function show_table(){
    $("#penalty_modal").modal('show');
}

function go_kakao(){
    window.open(
    'https://pf.kakao.com/_jVafC/chat',
    '_blank' // <- This is what makes it open in a new window.
    );
}

function close_table(){
    $("#penalty_modal").modal('hide');
}
</script>
<?include_once "_foot.php";?>
