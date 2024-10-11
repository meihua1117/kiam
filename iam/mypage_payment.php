<?php 
include "inc/header.inc.php";
if($_SESSION['iam_member_id'] == "") {
    echo "<script>location='/iam/';</script>";
}
$sql_serch=" buyer_id ='{$_SESSION['iam_member_id']}' ";
//$sql_serch.=" and (iam_pay_type !='' and iam_pay_type !='0') ";
$content_sql_serch = " buyer_id ='{$_SESSION['iam_member_id']}' and (point_val=0 or (point_val=1 and point_percent!='' and type='use'))";
$point_sql_serch = " buyer_id ='{$_SESSION['iam_member_id']}' and ((point_val=1 and point_percent is null) or (point_val=2 and receive_state=1)) and pay_status='Y'";
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

if($_REQUEST[content_search_condition]){
    if ($_REQUEST[content_search_condition] == 'all') {

    } else if ($_REQUEST[content_search_condition] == 'goodMarket'){
        $content_sql_serch.=" and gwc_cont_pay = 1";
    } else if ($_REQUEST[content_search_condition] == 'calliya'){
        $content_sql_serch.=" and gwc_cont_pay = 0 and item_name LIKE '%서비스콘텐츠%'";
    } else if ($_REQUEST[content_search_condition] == 'withyou'){
        $content_sql_serch.=" and gwc_cont_pay = 0 and item_name LIKE '%IAM 몰%'";
    }
}

if($_REQUEST[content_search_text]){
    $content_sql_serch.=" and item_name LIKE '%".$_REQUEST[content_search_text]."%'";
}

if($_REQUEST[content_search_date]){
    $content_search_startdate = $_REQUEST[content_search_startdate];
    $content_search_enddate = $_REQUEST[content_search_enddate];

    if($content_search_startdate){
        $start_time=strtotime($content_search_startdate);
        $content_sql_serch.=" and unix_timestamp(pay_date) >=$start_time ";
    }
    if($content_search_enddate){
        $end_time=strtotime($content_search_enddate);
        $content_sql_serch.=" and unix_timestamp(pay_date) <= $end_time ";
    }
}


if($_REQUEST[point_search_date]){
    $pay_time=strtotime($_REQUEST[point_search_date]);
    $point_sql_serch.=" and unix_timestamp(pay_date) >=$pay_time ";
}
if($_REQUEST[point_search]){
    $point_search = $_REQUEST[point_search];
    $point_search_value = $_REQUEST[point_search_value];
    if ($point_search == 'mem_name') {
        $point_sql_serch.=" and VACT_InputName LIKE '%".$point_search_value."%'";
    } elseif ($point_search == 'mem_id') {
        $point_sql_serch.=" and pay_method LIKE '%".$point_search_value."%'";
    } else {
        $point_sql_serch.=" and item_name LIKE '%".$point_search_value."%'";
    }
}

if($_REQUEST[point_type] == "S"){
    $point_sql_serch.=" and site is not null ";
}else if($_REQUEST[point_type] == "C"){
    $point_sql_serch.=" and site is null ";
}
$sql="select count(no) as cnt from tjd_pay_result where $sql_serch ";
$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$row=mysqli_fetch_array($result);
$intRowCount=$row['cnt'];

$content_sql="select count(no) as cnt from Gn_Item_Pay_Result where $content_sql_serch ";
$content_result = mysqli_query($self_con,$content_sql) or die(mysqli_error($self_con));
$content_row=mysqli_fetch_array($content_result);
$contentRowCount=$content_row['cnt'];

$point_sql="select count(p.no) as cnt from Gn_Item_Pay_Result p where $point_sql_serch ";
$point_result = mysqli_query($self_con,$point_sql) or die(mysqli_error($self_con));
$point_row=mysqli_fetch_array($point_result);
$pointRowCount=$point_row['cnt'];

if (!$_POST['lno'])
    $intPageSize =10;
else
    $intPageSize = $_POST['lno'];
if($_POST['page']){
    $page=(int)$_POST['page'];
    $sort_no=$intRowCount-($intPageSize*$page-$intPageSize);
}else{
    $page=1;
    $sort_no=$intRowCount;
}
if($_POST[content_page]){
    $content_page=(int)$_POST[content_page];
    $content_sort_no=$contentRowCount-($intPageSize*$content_page-$intPageSize);
}else{
    $content_page=1;
    $content_sort_no=$contentRowCount;
}
if($_POST[point_page]){
    $point_page=(int)$_POST[point_page];
    $point_sort_no=$pointRowCount-($intPageSize*$point_page-$intPageSize);
}else{
    $point_page=1;
    $point_sort_no=$pointRowCount;
}

if($_POST['page2'])
    $page2=(int)$_POST['page2'];
else
    $page2=1;
if($_POST[content_page2])
    $content_page2=(int)$_POST[content_page2];
else
    $content_page2=1;
if($_POST[point_page2])
    $point_page2=(int)$_POST[point_page2];
else
    $point_page2=1;

$int=($page-1)*$intPageSize;
$cont=($content_page-1)*$intPageSize;
$pt=($point_page-1)*$intPageSize;
if($_REQUEST['order_status'])
    $order_status=$_REQUEST['order_status'];
else
    $order_status="desc";
if($_REQUEST['order_name'])
    $order_name=$_REQUEST['order_name'];
else
    $order_name="end_status";

if($_REQUEST[content_order_status])
    $content_order_status=$_REQUEST[content_order_status];
else
    $content_order_status="desc";
if($_REQUEST[content_order_name])
    $content_order_name=$_REQUEST[content_order_name];
else
    $content_order_name="pay_date";

$intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);
$contPageCount=(int)(($contentRowCount+$intPageSize-1)/$intPageSize);
$pointPageCount=(int)(($pointRowCount+$intPageSize-1)/$intPageSize);
$sql="select * from tjd_pay_result where $sql_serch order by $order_name $order_status limit $int,$intPageSize";
$result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));

//$content_sql="select p.*,mem_name from Gn_Item_Pay_Result p inner join Gn_Member m on p.seller_id = m.mem_id  where $content_sql_serch order by $content_order_name $content_order_status limit $cont,$intPageSize";
$content_sql="select p.* from Gn_Item_Pay_Result p where $content_sql_serch order by $content_order_name $content_order_status limit $cont,$intPageSize";
$content_result=mysqli_query($self_con,$content_sql) or die(mysqli_error($self_con));

$point_sql="select * from Gn_Item_Pay_Result where $point_sql_serch order by $content_order_name $content_order_status limit $pt,$intPageSize";
$point_result=mysqli_query($self_con,$point_sql) or die(mysqli_error($self_con));

$mid = date("YmdHis").rand(10,99);
?>
<link href='/css/main.css' rel='stylesheet' type='text/css'/>
<link href='/css/responsive.css' rel='stylesheet' type='text/css'/>
<link href='./css/mypage_payment.css' rel='stylesheet' type='text/css'/>

<main id="register" class="common-wrap" style=""><!-- 컨텐츠 영역 시작 -->
    <div class="container">
        <div class="inner-wrap">
            <h2 class="title"></h2>
            <div class="mypage_menu">
                <div style="display:flex;float: right">
                    <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=shared_receive&modal=Y')" title = "<?=$MENU['IAM_MENU']['M7_TITLE'];?>" style="display:flex;padding:6px 3px">
                        <p style="font-size:14px;color:black">콘수신</p>
                        <label class="label label-sm" id = "share_recv_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                    </a>
                    <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=shared_send&modal=Y')" title = "<?=$MENU['IAM_MENU']['M8_TITLE'];?>" style="display:flex;padding:6px 3px">
                        <p style="font-size:14px;color:black">콘전송</p>
                        <label class="label label-sm" id = "share_send_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                    </a>
                    <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=unread_post')" title = "<?='댓글알림'?>" style="display:flex;padding:6px 3px">
                        <p style="font-size:14px;color:black">댓글수신</p>
                        <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                    </a>
                    <a class="btn  btn-link" href="/iam/mypage_post_lock.php" title = "<?='댓글알림'?>" style="display:flex;padding:6px 3px">
                        <p style="font-size:14px;color:black">댓글차단해지</p>
                        <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                    </a>
                    <a class="btn  btn-link" href="javascript:iam_mystory('cur_win=request_list')" title = "<?='신청알림'?>" style="display:flex;padding:6px 3px">
                        <p style="font-size:14px;color:black">이벤트신청</p>
                        <label class="label label-sm" id = "share_post_count" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                    </a>
                </div>
                <div style="display:flex;float: right;">
                    <?if($_SESSION['iam_member_subadmin_id'] == $_SESSION['iam_member_id']){?>
                    <a class="btn  btn-link" title = "<?='공지알림';?>" href="/?cur_win=unread_notice&box=send&modal=Y" style="display:flex;padding:6px 3px">
                        <p style="font-size:14px;color:black">공지전송</p>
                        <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                    </a>
                    <a class="btn  btn-link" title = "<?='공지알림';?>" href="/?cur_win=unread_notice&modal=Y" style="display:flex;padding:6px 3px">
                        <p style="font-size:14px;color:black">공지수신</p>
                        <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                    </a>
                    <?}else{?>
                    <a class="btn  btn-link" title = "<?='공지알림';?>" href="javascript:iam_mystory('cur_win=unread_notice')" style="display:flex;padding:6px 3px">
                        <p style="font-size:14px;color:black">공지</p>
                        <label class="label label-sm" id = "notice" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                    </a>
                    <?}?>
                    <?if($is_pay_version){?>
                    <a class="btn  btn-link" title = "" href="/iam/mypage_refer.php" style="display:flex;padding:6px 3px">
                        <!--img src="/iam/img/menu/icon_alarm_sold.png" style="height: 22px;"-->
                        <p style="font-size:14px;color:black">추천</p>
                        <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                    </a>
                    <a class="btn  btn-link" title = "" href="/iam/mypage_payment.php" style="display:flex;padding:6px 3px">
                        <!--img src="/iam/img/menu/icon_alarm_sold.png" style="height: 22px;"-->
                        <p style="font-size:14px;color:#99cc00">결제</p>
                        <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                    </a>
                    <a class="btn  btn-link" title = "" href="/iam/mypage_payment_item.php" style="display:flex;padding:6px 3px">
                        <!--img src="/iam/img/menu/icon_alarm_sold.png" style="height: 22px;"-->
                        <p style="font-size:14px;color:black">판매</p>
                        <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                    </a>
                    <?}?>
                    <?if($member_iam[service_type] < 2){
                        $report_link = "/iam/mypage_report_list.php";
                    }else{
                        $report_link = "/iam/mypage_report.php";
                    }
                    ?>
                    <a class="btn  btn-link" title = "" href="<?=$report_link?>" style="display:flex;padding:6px 3px">
                        <p style="font-size:14px;color:black">리포트</p>
                        <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                    </a>
                    <a class="btn  btn-link" title = "" href="/?cur_win=unread_notice&req_provide=Y" style="display:flex;padding:6px 3px">
                        <p style="font-size:14px;color:black">공급사신청</p>
                        <label class="label label-sm" id = "sell_service_contents" style="background: #ff3333;border-radius: 50%;padding: 2px 5px;margin-left: -5px;font-size:10px"></label>
                    </a>
                </div>
            </div>
            <br>
            <form name="pay_form1" action="" method="post" class="my_pay" enctype="multipart/form-data">
                <input type="hidden" name="order_name" value="<?=$order_name?>"  />
                <input type="hidden" name="order_status" value="<?=$order_status?>"/>
                <input type="hidden" name="content_order_name" value="<?=$content_order_name?>"  />
                <input type="hidden" name="content_order_status" value="<?=$content_order_status?>"/>
                <input type="hidden" name="page" value="<?=$page?>" />
                <input type="hidden" name="page2" value="<?=$page2?>" />
                <input type="hidden" name="content_page" value="<?=$content_page?>" />
                <input type="hidden" name="content_page2" value="<?=$content_page2?>" />
                <input type="hidden" name="point_page" value="<?=$point_page?>" />
                <input type="hidden" name="point_page2" value="<?=$point_page2?>" />
                <div style="margin-top: 70px;">
                    <h2 class="title text-center">플랫폼 결제 내역</h2>
                    <div class="title_separator"></div>
                </div>
                <br>
                <div class="p1">
                    <select name="search_date" class="form-sort" id="platform_payment">
                        <option value="all">전체보기</option>
                        <option value="date" <? if($_REQUEST[search_date] == 'date') { echo ' selected';}?>>결제일</option>
                        <option value="end_date" <? if($_REQUEST[search_date] == 'end_date') { echo ' selected';}?>>만료일</option>
                    </select>
<!--                            <a href="mypage_payment.php" class="a_btn_2">전체보기</a>-->
                    <!-- <?
                    $search_date=array("date"=>"결제일","end_date"=>"만료(해지)일");
                    foreach($search_date as $key=>$v){
                        $checked=$_REQUEST[search_date]==$key?"checked":"";
                        ?>
                        <label><input name="search_date" type="radio" value="<?=$key?>" <?=$checked?> /><?=$v?></label>
                    <?
                    }
                    ?> -->
                    <input type="date" name="rday1" placeholder="" id="rday1" value="<?=$_REQUEST[rday1]?>" class="form-sort"/> ~
                    <input type="date" name="rday2" placeholder="" id="rday2" value="<?=$_REQUEST[rday2]?>" class="form-sort"/>
                    <a class="form-search" onclick="pay_form1.submit();"><i class="fa fa-search"></i></a>
                </div>
                <div>
                    <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="width:6%;">번호</td>
                            <td style="width:12%;">상품종류</td>
<!--                                    <td style="width:8%;"><a href="javascript:void(0)" onclick="order_sort(pay_form1,'iam_card_cnt',pay_form1.order_status.value)">카드갯수<? if($_REQUEST['order_name']=="iam_card_cnt"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>-->
<!--                                    <td style="width:8%;"><a href="javascript:void(0)" onclick="order_sort(pay_form1,'iam_share_cnt',pay_form1.order_status.value)">전송건수--><?// if($_REQUEST['order_name']=="iam_share_cnt"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?><!--</a></td>-->
                            <td style="width:15%;"><a href="javascript:void(0)" onclick="order_sort(pay_form1,'date',pay_form1.order_status.value)">결제일<? if($_REQUEST['order_name']=="date"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                            <td style="width:15%;"><a href="javascript:void(0)" onclick="order_sort(pay_form1,'end_date',pay_form1.order_status.value)">만료(해지)일<? if($_REQUEST['order_name']=="end_date"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                            <td style="width:6%;"><a href="javascript:void(0)" onclick="order_sort(pay_form1,'month_cnt',pay_form1.order_status.value)">개월수<? if($_REQUEST['order_name']=="month_cnt"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                            <td style="width:10%;"><a href="javascript:void(0)" onclick="order_sort(pay_form1,'payMethod',pay_form1.order_status.value)">결제방식<? if($_REQUEST['order_name']=="payMethod"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                            <td style="width:10%;"><a href="javascript:void(0)" onclick="order_sort(pay_form1,'TotPrice',pay_form1.order_status.value)">결제금액<? if($_REQUEST['order_name']=="TotPrice"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                            <td style="width:12%;"><a href="javascript:void(0)" onclick="order_sort(pay_form1,'end_status',pay_form1.order_status.value)">상태<? if($_REQUEST['order_name']=="end_status"){echo $_REQUEST['order_status']=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                        </tr>
<?
                        if($intRowCount){
                            while($row=mysqli_fetch_array($result)){
?>
                                <tr >
                                    <td style=""><?=$sort_no?></td>
                                    <td style=""><?="IAM-".$row[iam_pay_type]?></td>
<!--                                            <td style="">--><?//=$row[iam_card_cnt]?><!--</td>-->
<!--                                            <td style="">--><?//=$row[iam_share_cnt]?><!--</td>-->
                                    <td style="font-size:11px;"><?=$row[date]?></td>
                                    <td style="font-size:11px;"><?=$row['end_date']?></td>
                                    <?if($row['month_cnt'] < 120){?>
                                        <td style=""><?=$row['month_cnt']?>개월</td>
                                    <?}else{?>
                                        <td style="">정기</td>
                                    <?}?>
                                    <td style=""><?=$pay_type[$row[payMethod]]?></td>
                                    <td style=""><?=number_format($row[TotPrice])?>원</td>
                                    <td style="">
                                        <?=$pay_result_status[$row['end_status']]?>
                                        <?php if($row['monthly_yn'] == "Y") {?>
                                            <div style="border:1px solid #000;padding:3px;background:#D8D8D8; font-size:10px" >
                                                <?if($row['monthly_status'] == "N"){?>
                                                    <a href="javascript:monthly_remove('<?php echo $row['no'];?>', '<?php echo $row['member_type'];?>')">정기결제해지</a>
                                                <?}else if($row['monthly_status'] == "R"){?>
                                                    <a href="javascript:;;">해지대기</a>
                                                <?}else if($row['monthly_status'] == "Y"){?>
                                                    <a href="javascript:;;">해지완료</a>
                                                <?}?>
                                            </div>
                                            <span class="popbutton pop_view pop_right" style="border: 1px solid #000;padding: 1px 3px;border-radius: 30px;font-size: 10px;bottom: 1px;cursor: pointer;">?</span>
                                        <?php }?>
                                    </td>
                                </tr>
                                <?
                                $sort_no--;
                            }
                            ?>
                            <tr>
                                <td colspan="11">
                                    <?
                                    page_f($page,$page2,$intPageCount,"pay_form1");
                                    ?>
                                </td>
                            </tr>
                        <?}else{?>
                            <tr>
                                <td colspan="11">
                                    검색된 내용이 없습니다.
                                </td>
                            </tr>
                        <?}?>
                    </table>
                </div>
                <br>
                <h2 class="title text-center">상품 구매 내역</h2>
                <div class="title_separator"></div>
                <br>
                <div class="p1">
                    <select name="content_search_condition" class="form-sort" id="content_search_date">
                        <option value="all" <? if($_REQUEST[content_search_condition] == 'all') { echo ' selected';}?>>전체보기</option>
                        <option value="goodMarket" <? if($_REQUEST[content_search_condition] == 'goodMarket') { echo ' selected';}?>>굿마켓</option>
                        <option value="calliya" <? if($_REQUEST[content_search_condition] == 'calliya') { echo ' selected';}?>>콜이야</option>
                        <option value="withyou" <? if($_REQUEST[content_search_condition] == 'withyou') { echo ' selected';}?>>위드유</option>
                    </select>
<!--                            <label>결제일</label>-->
                    <input type="date" class="form-sort" name="content_search_startdate" value="<?=$_REQUEST[content_search_startdate]?>"/>~
                    <input type="date" class="form-sort" name="content_search_enddate" value="<?=$_REQUEST[content_search_enddate]?>"/>
                    <input type="text" class="form-sort" name="content_search_text" placeholder="상품명" value="<?=$_REQUEST[content_search_text]?>">
                    <a class="form-search" onclick="pay_form1.submit()"><i class="fa fa-search"></i></a>
                </div>
                <div>
                    <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="width:6%;">번호</td>
                            <td style="width: 10%">구매채널</td>
                            <!-- <td style="width:10%;"><a href="javascript:void(0)" onclick="content_order_sort(pay_form1,'pay_method',pay_form1.content_order_status.value)">결제방식<? if($_REQUEST[content_order_name]=="pay_method"){echo $_REQUEST[content_order_status]=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td> -->
                            <td style="width:12%;">상품명</td>
                            <td style="width:8%;">아이디<br>이름</td>
                            <td style="width:12%;">연락처</td>
                            <td style="width:15%;"><a href="javascript:void(0)" onclick="content_order_sort(pay_form1,'pay_date',pay_form1.content_order_status.value)">결제일<? if($_REQUEST[content_order_name]=="pay_date"){echo $_REQUEST[content_order_status]=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                            <td style="width:10%;">구매확인<br>확인일시</td>
                            <td style="width:12%;">판매확인<br>배송상태</td>
                        </tr>
                        <?
                        if($contentRowCount){
                            while($row=mysqli_fetch_array($content_result)){
                                if($row[point_val] == 0){
                                    if($row[pay_method] == "CARD"){
                                        $method = "카드결제";
                                    }
                                    else{
                                        $method = "무통장결제";
                                    }
                                    $sql_mem_data = "select mem_id, mem_name, mem_phone from Gn_Member where mem_id='{$row['buyer_id']}'";
                                    $res_mem_data = mysqli_query($self_con,$sql_mem_data);
                                    $row_mem_data = mysqli_fetch_array($res_mem_data);
                                }
                                else{
                                    $method = "포인트결제";
                                    $sql_mem_data = "select mem_id, mem_name, mem_phone from Gn_Member where mem_id='{$row['seller_id']}'";
                                    $res_mem_data = mysqli_query($self_con,$sql_mem_data);
                                    $row_mem_data = mysqli_fetch_array($res_mem_data);
                                }

                                if($row[pay_method] == "CARD"){
                                    $card = "card";
                                }
                                else{
                                    $card = "point";
                                }

                                $seller_no = $row['no'] * 1 + 1;
                                ?>
                                <tr >
                                    <td style=""><?=$content_sort_no?></td>
                                    <!-- <td style=""><?=$method?></td> -->
                                    <td>
                                        <?
                                        if ($row[gwc_cont_pay] == '1') {
                                            echo '굿마켓';
                                        } else {
                                            if (strpos($row[item_name],'서비스콘텐츠') == 0) {
                                                echo '콜이야';
                                            } else {
                                                if (strpos($row[item_name],'IAM 몰') == 0) {
                                                    echo '위드유';
                                                }
                                            }
                                        }
                                        ?></td>
                                    <td style=""><?=$row[item_name]?></td>
                                    <td style=""><?=$row_mem_data['mem_id']?><br><?=$row_mem_data['mem_name']?></td>
                                    <td style=""><?=$row[buyer_tel]?></td>
                                    <td style="font-size:11px;"><?=$row[pay_date]?></td>
                                    <td style="font-size:11px;">
                                        <?php
                                        if($row['gwc_cont_pay'] == 0){
                                        if($row[apply_buyer_date] == ''){
                                        ?>
                                        <label class="switch">
                                            <input type="checkbox" name="auto_status" id="auto_stauts_<?php echo $row['no'];?>_<?=$card?>" value="<?php echo $row['no']."/".$seller_no;?>">
                                            <span class="slider round" name="auto_status" id="auto_stauts_<?php echo $row['no'];?>_<?=$card?>"></span>
                                        </label>
                                        <?}else{?>
                                        <label class="switch">
                                            <input type="checkbox" name="auto_status" id="auto_stauts_<?php echo $row['no'];?>_<?=$card?>" value="<?php echo $row['no']."/".$seller_no;?>" checked>
                                            <span class="slider round" name="auto_status" id="auto_stauts_<?php echo $row['no'];?>_<?=$card?>"></span>
                                        </label><br>
                                        <? echo $row[apply_buyer_date];}}?>
                                    </td>
                                    <td style="font-size:11px;">
                                        <?php
                                        if($row['gwc_cont_pay'] == 0){
                                        if($row[apply_seller_date] == ''){
                                            echo "확인대기";
                                        }
                                        else{
                                            echo "확인<br>".$row[apply_seller_date];
                                        }
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?
                                $content_sort_no--;
                            }
                            ?>
                            <tr>
                                <td colspan="11">
                                    <?sub_page_f($content_page,$content_page2,$contPageCount,"pay_form1");?>
                                    </td>
                            </tr>
                        <?
                        }else{
                            ?>
                            <tr>
                                <td colspan="11">
                                    검색된 내용이 없습니다.
                                </td>
                            </tr>
                        <?
                        }
                        ?>
                    </table>
                </div>
                <br>
                <h2 class="title text-center">포인트 충전/결제 내역</h2>
                <div class="title_separator"></div>
                <br>
                <div class="p1">
                    <div class="d-flex align-items-center">
                        <label class="label-title">캐시포인트</label>
                        <span id="mem_cash" class="label-exp"><?=number_format($Gn_cash)?> P</span>
                        <span class="label-body">※모든 상품결제 사용가능</span>
                        <?if($is_pay_version){?>
                            <a class="pointshare_btn" data-toggle="modal" data-target="#mutong_settlement">지금 충전하기</a>
                        <?}?>
                    </div>
                    <div class="d-flex align-items-center">
                        <label class="label-title">씨드포인트</label>
                        <span id="mem_point" class="label-exp"><?=number_format($Gn_point)?> P</span>
                        <span class="label-body">※기능결제시만 사용가능</span>
                        <a class="pointshare_btn" data-toggle="modal" data-target="#sharepoint_modal">타인 쉐어하기</a>
                    </div>
                    <div class="d-flex align-items-center">
                        <label class="label-title">적립포인트</label>
                        <span id="mem_point" class="label-exp">0 P</span>
                        <span class="label-body">※방문상품결제 사용가능</span>
                        <a class="pointshare_btn" data-toggle="modal" data-target="#point_change">포인트 전환하기</a>
                    </div>
                </div>
                <div class="p1">
                    <input type="date" name="point_search_date" placeholder="" class="form-sort" id="point_search_date" value="<?=$_REQUEST[point_search_date]?>"/>
                    <select name="point_type" class="form-sort" id="point_type">
                        <option value="A"<? if($_REQUEST[point_type] == 'A') { echo ' selected';}?>>전체보기</option>
                        <option value="S"<? if($_REQUEST[point_type] == 'S') { echo ' selected';}?>>판매포인트</option>
                        <option value="C"<? if($_REQUEST[point_type] == 'C') { echo ' selected';}?>>충전포인트</option>
                    </select>
                    <!-- <label>결제일</label> -->
                    <!-- <select name="point_type"  onchange="" class="form-sort">
                        <option value="A" <? echo $_REQUEST[point_type] == "A"? "selected" : ""?>>전체</option>
                        <option value="S" <? echo $_REQUEST[point_type] == "S"? "selected" : ""?>>판매포인트</option>
                        <option value="C" <? echo $_REQUEST[point_type] == "C"? "selected" : ""?>>충전포인트</option>
                    </select> -->
                    <select name="point_search" class="form-sort">
                        <option value=""<? if($_REQUEST[point_search] == '') { echo ' selected';}?>>선택</option>
                        <option value="mem_name"<? if($_REQUEST[point_search] == 'mem_name') { echo ' selected';}?>>회원명</option>
                        <option value="mem_id"<? if($_REQUEST[point_search] == 'mem_id') { echo ' selected';}?>>아이디</option>
                        <option value="item_name"<? if($_REQUEST[point_search] == 'item_name') { echo ' selected';}?>>상품명</option>
                    </select>
                    <input type="text" class="form-sort" name="point_search_value" value="<?=$_REQUEST[point_search_value]?>">
                    <a class="form-search" onclick="pay_form1.submit()"><i class="fa fa-search"></i></a>
                </div>
                <div>
                    <table class="list_table table table-bordered" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="width:6%;">번호</td>
                            <td style="width:6%;">유형</td>
                            <td style="width:12%;">상품명</td>
                            <td style="width:8%;">상세정보</td>
                            <td style="width:15%;"><a href="javascript:void(0)" onclick="content_order_sort(pay_form1,'pay_date',pay_form1.content_order_status.value)">결제일<? if($_REQUEST[content_order_name]=="pay_date"){echo $_REQUEST[content_order_status]=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                            <td style="width:10%;"><a href="javascript:void(0)" onclick="content_order_sort(pay_form1,'item_price',pay_form1.content_order_status.value)">결제포인트<? if($_REQUEST[content_order_name]=="item_price"){echo $_REQUEST[content_order_status]=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td>
                            <!-- <td style="width:10%;"><a href="javascript:void(0)" onclick="content_order_sort(pay_form1,'apply_buyer_date',pay_form1.content_order_status.value)">구매확인<? if($_REQUEST[content_order_name]=="apply_buyer_date"){echo $_REQUEST[content_order_status]=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td> -->
                            <!-- <td style="width:10%;"><a href="javascript:void(0)" onclick="content_order_sort(pay_form1,'apply_seller_date',pay_form1.content_order_status.value)">판매확인<? if($_REQUEST[content_order_name]=="apply_seller_date"){echo $_REQUEST[content_order_status]=="desc"?'▼':'▲';}else{ echo '▼'; }?></a></td> -->
                        </tr>
                        <?
                        if($pointRowCount){
                            while($row=mysqli_fetch_array($point_result)){
                                if(($row['type'] == "service") || ($row['type'] == "buy")){
                                    $type = "충전";

                                } else if($row['type'] == "use"){
                                    $type = "결제";
                                    if(strpos($row['item_name'], "서비스콘텐츠") !== false || strpos($row['item_name'], "IAM몰") !== false){
                                        $sql_member = "select mem_name,mem_phone from Gn_Member where mem_id='{$row['pay_method']}'";
                                        $res_member = mysqli_query($self_con,$sql_member);
                                        $row_member = mysqli_fetch_array($res_member);
                                        $row[pay_method] = $row_member['mem_name'] . "<br>" . $row_member['mem_phone'];
                                    }
                                } else if($row['type'] == "minus"){
                                    $type = "차감";
                                } else if($row['type'] == "servicebuy"){
                                    $type = "판매";
                                    if(strpos($row['item_name'], "서비스콘텐츠") !== false || strpos($row['item_name'], "IAM몰") !== false){
                                        $sql_member = "select mem_name, mem_phone from Gn_Member where mem_id='{$row['pay_method']}'";
                                        $res_member = mysqli_query($self_con,$sql_member);
                                        $row_member = mysqli_fetch_array($res_member);
                                        $row[pay_method] = $row_member['mem_name'] . "<br>" . $row_member['mem_phone'];
                                    }
                                } else if($row['type'] == "cardsend" || $row['type'] == "contentssend"){
                                    $type = "전송";
                                    $sql_member = "select mem_name, mem_id from Gn_Member where mem_id='{$row['pay_method']}'";
                                    $res_member = mysqli_query($self_con,$sql_member);
                                    $row_member = mysqli_fetch_array($res_member);
                                    $row[pay_method] = $row_member['mem_id'] . "<br>" . $row_member['mem_name'];
                                } else if($row['type'] == "contentsrecv"){
                                    $type = "수신";
                                    $sql_member = "select mem_name,mem_phone from Gn_Member where mem_id='{$row['pay_method']}'";
                                    $res_member = mysqli_query($self_con,$sql_member);
                                    $row_member = mysqli_fetch_array($res_member);
                                    $row[pay_method] = $row_member['mem_name'] . "<br>" . $row_member['mem_phone'];
                                    $row[item_price] = 0;
                                } else if($row['type'] == "group_card"){
                                    $type = "결제";
                                } else {
                                    $type = "쉐어";
                                }
                                ?>
                                <tr >
                                    <td style=""><?=$point_sort_no?></td>
                                    <td style=""><?=$type?></td>
                                    <td style=""><?=$row[item_name]?></td>
                                    <td style=""><?=$row[pay_method]?></td>
                                    <td style="font-size:11px;"><?=$row[pay_date]?></td>
                                    <td style=""><?=number_format($row[item_price])?>P</td>
                                    <!-- <td style="font-size:11px;"><?=$row[apply_buyer_date]?></td> -->
                                    <!-- <td style="font-size:11px;"><?=$row[apply_seller_date]?></td> -->
                                </tr>
                                <?
                                $point_sort_no--;
                            }
                            ?>
                            <td colspan="11">
                                <?point_page_f($point_page,$point_page2,$pointPageCount,"pay_form1");?>
                            </td>
                        <?
                        }
                        else
                        {
                            ?>
                            <tr>
                                <td colspan="11">
                                    검색된 내용이 없습니다.
                                </td>
                            </tr>
                        <?
                        }
                        ?>
                    </table>
                </div>
            </form>
        </div>
    </div>
</main><!-- // 컨텐츠 영역 끝 -->
<!-- <div id="ajax_div" style="display:none"></div> -->
<div id="modalwindow" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document" style="margin-top: 200px;max-width:360px;margin-left:auto;margin-right:auto;">
        <div class="modal-content">
            <div class="modal-header" style="border:none;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ><img src = "img/icon_close_black.svg"></button>
                <h3 style="text-align: center;">정기결제해지</h3>
            </div>
            <div class="modal-body">
                <div class="center_text">
                    <h5>정기결제일 1주일 전 해지 시 당월 적용되며, 1주일 이내 해지 시 익월 적용됩니다</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="detail_intro_modal" class="modal fade" tabindex="-1" role="dialog" style="overflow-x: auto; overflow-y: auto;">
    <div class="modal-dialog" style="width: 100%;max-width:768px;">
        <!-- Modal content-->
        <div class="modal-content" style="">
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
        <div class="modal-content" style="">
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
</body>
<script src="./js/mypage_payment.js"></script>
<script>

</script>
