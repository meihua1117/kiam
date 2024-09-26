<?
$path="./";
include_once "_head.php";
if(!$_SESSION['one_member_id']){
?>
    <script language="javascript">
        location.replace('/ma.php');
    </script>
    <?
    exit;
}
$sql="select * from Gn_Member  where mem_id='".$_SESSION['one_member_id']."'";
$sresul_num=mysqli_query($self_con, $sql);
$data=mysqli_fetch_array($sresul_num);
?>
    <script type="text/javascript" src="/js/mms_send.js"></script>
    <div class="big_div">
        <div class="big_sub">
            <?php //include "mypage_step_navi.php";?>
            <div class="m_div">
                <?php include "mypage_left_menu.php";?>
                <div class="m_body">
                    <form name="pay_form" action="" method="post" class="my_pay">
                        <input type="hidden" name="page" value="<?=$page?>" />
                        <input type="hidden" name="page2" value="<?=$page2?>" />
                        <div class="a1" style="margin-top:50px; margin-bottom:15px">
                            <li style="float:left;">
                                <div class="popup_holder popup_text">사업자공급관리 전체내역
                                    <!-- <div class="popupbox" style="display:none; height: 56px;width: 220px;left: 178px;top: -37px;">예약문자가 이벤트 신청고객에게 발송된 결과를 보여줍니다.<br><br>
                                        <a class = "detail_view" href="https://url.kr/1aHAGx" target="_blank">[자세히 보기]</a>
                                    </div> -->
                                </div>
                            </li>
                            <li style="float:right;">
                                <select name="chanel" id="chanel" class="form-control input-sm" onchange="pay_form.submit()" style="width:100px;font-size: 15px;padding: 6.5px;">
                                    <option value="0" <?=$_REQUEST['chanel'] == "0"?"selected":""?>>전체</option>
                                    <option value="1" <?=$_REQUEST['chanel'] == "1"?"selected":""?>>지도</option>
                                    <option value="2" <?=$_REQUEST['chanel'] == "2"?"selected":""?>>G쇼핑</option>
                                    <option value="3" <?=$_REQUEST['chanel'] == "3"?"selected":""?>>N쇼핑</option>
                                </select>
                                <input type="text" name="search_text" placeholder="카드명/주소" id="search_text" value="<?=$_REQUEST['search_text']?>" style="height:30px;"/>
                                <a href="javascript:pay_form.submit()"><img src="images/sub_mypage_11.jpg" /></a>

                            </li>
                            <p style="clear:both"></p>
                        </div>
                        <div >
                            <a href="calliya.php" style="color:#f00">전체리스트보기</a> &nbsp;|&nbsp; 
							<a href="calliya_req.php">신청리스트보기</a>
                            <select name="cnt_per_page" id="cnt_per_page" class="form-control input-sm pull-right" onchange="pay_form.submit()" style="width:100px;">
                                <option value="20" <?=$_REQUEST['cnt_per_page'] == "20"?"selected":""?>>20개씩</option>
                                <option value="50" <?=$_REQUEST['cnt_per_page'] == "50"?"selected":""?>>50개씩</option>
                                <option value="100" <?=$_REQUEST['cnt_per_page'] == "100"?"selected":""?>>100개씩</option>
                            </select>
					    </div>
                        <div>
                            <div class="p1">
                            </div>
                            <div>
                                <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="width:5%;">NO</td>
                                        <td style="width:7%;">IAM ID</td>
                                        <td style="width:7%;">채널</td>
                                        <td style="width:7%;">카드명</td>
                                        <!-- <td style="width:7%;">카드링크</td> -->
                                        <td style="width:10%;">IMAGE</td>
                                        <td style="width:15%;">주소</td>
                                        <td style="width:8%;">폰번호</td>
                                        <td style="width:7%;">결제여부</td>
                                        <td style="width:6%;">등록일자</td>
                                        <td style="width:5%;">컨텐<br>츠수</td>
                                        <td style="width:5%;">조회<br>건수</td>
                                        <td style="width:7%;">신청<br>관리</td>
                                    </tr>
                                    <?
                                    $sql = "select main_img1 from Gn_Iam_Info where mem_id = 'obmms02'";
                                    $result=mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
                                    $row=mysqli_fetch_array($result);
                                    $default_img =  $row['main_img1'];
    
    
                                    // $nowPage= $_REQUEST['nowPage']?$_REQUEST['nowPage']:1;
                                    // $startPage = $nowPage?$nowPage:1;
                                    // $pageCnt = 20;
                                    // 검색 조건을 적용한다.
                                    $searchStr = "";
                                    if($_REQUEST['search_text']){
                                        $searchStr .= " AND (ca_1.card_name LIKE '%".$_REQUEST['search_text']."%' or ca_1.card_addr like '%".$_REQUEST['search_text']."%' )";
                                    }

                                    switch($_REQUEST['chanel']){
                                        case 0:
                                            $searchStr .= "";
                                        break;
                                        case 1:
                                            $searchStr .= " AND ca_1.ai_map_gmarket=1 ";
                                        break;
                                        case 2:
                                            $searchStr .= " AND ca_1.ai_map_gmarket=2 AND ca_1.card_title='상품소개해요' ";
                                        break;
                                        case 3:
                                            $searchStr .= " AND ca_1.ai_map_gmarket=2 AND ca_1.card_title!='상품소개해요' ";
                                        break;
                                    }
    
                                    $count_query = "select count(idx) from Gn_Iam_Name_Card ca_1 WHERE worker_service_state=1 AND req_worker_id ='' AND group_id is NULL AND admin_shopping!=0 $searchStr";
                                    //$count_result = mysqli_query($self_con, $count_query);
                                    //$count_row = mysqli_fetch_array($count_result);
                                    $redisCache = new RedisCache();
                                    //$redisCache->set_debug(true);
                                    $count_row = $redisCache -> get_query_to_data($count_query);
                                    $intRowCount	=  $count_row[0];
    
                                    if($intRowCount){
                                        if (!$_REQUEST['cnt_per_page'])
                                            $intPageSize =20;
                                        else
                                            $intPageSize = $_REQUEST['cnt_per_page'];
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
                                        $intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);

                                        $query = "SELECT * FROM Gn_Iam_Name_Card ca_1";
                                        $query .= " WHERE worker_service_state=1 AND req_worker_id ='' AND group_id is NULL AND admin_shopping!=0 $searchStr";
                                        $limitStr = " LIMIT $int,$intPageSize";
                                        $number	= $totalCnt - ($nowPage - 1) * $pageCnt;
                                        if(!$orderField)
                                            $orderField = "req_data";
                                        $orderQuery .= " ORDER BY $orderField $dir $limitStr";
                                        $i = 1;
                                        $c=0;
                                        $query .= $orderQuery;
                                        // $res = mysqli_query($self_con, $query);                                    
                                        // while($row = mysqli_fetch_array($res)) {
                                        $cache_list = $redisCache -> get_query_to_array($query);
                                        //print_r($redisCache->get_debug_info());
                                        for($i=0 ; $i < count($cache_list); $i++)
                                        {
                                            $row = $cache_list[$i];

                                            $mem_sql = "select mem_code from Gn_Member where mem_id='{$row['mem_id']}'";
                                            $mem_res = mysqli_query($self_con, $mem_sql);
                                            $mem_row = mysqli_fetch_array($mem_res);
        
                                            $fquery = "select count(*) from Gn_Iam_Friends where friends_card_idx = ".$row['idx'];
                                            $fresult = mysqli_query($self_con, $fquery);
                                            $frow = mysqli_fetch_array($fresult);
                                            //$friend_count	=  $frow[0];

                                            $sql_pay = "select sum(TotPrice) totPrice, date from tjd_pay_result where buyer_id = '".$row['mem_id']."' and end_status='Y'";
                                            $res_result = mysqli_query($self_con, $sql_pay);
                                            $totPriceRow = mysqli_fetch_row($res_result);
                                            $totPrice = $totPriceRow[0];
        
                                            $cquery = "select count(*) from Gn_Iam_Contents where westory_card_url = '{$row['card_short_url']}'";
                                            $cresult = mysqli_query($self_con, $cquery);
                                            $crow = mysqli_fetch_array($cresult);
                                            
                                            if($row['ai_map_gmarket'] == 1){
                                                $chanel = "지도";
                                            }
                                            else if($row['ai_map_gmarket'] == 2 && $row['card_title'] == "상품소개해요"){
                                                $chanel = "G쇼핑";
                                            }
                                            else if($row['ai_map_gmarket'] == 2 && $row['card_title'] != "상품소개해요"){
                                                $chanel = "N쇼핑";
                                            }
                                            ?>
                                            <tr>
                                                <td><?=$sort_no?></td>
                                                <td><?=$row['mem_id']?></td>
                                                <td><?=$chanel?></td>
                                                <td style="font-size:12px;">
                                                    <?=$row['card_name']?>
                                                </td>
                                                <!-- <td><a href="http://obmms.net/iam/?<?=strip_tags($row['card_short_url'].$mem_row[mem_code])?>" target="_blank"><?=$row['card_short_url']?></a></td> -->
                                                <td>
                                                    <div >
                                                        <?
                                                        if($row['main_img1']){
                                                            $thumb_img =  $row['main_img1'];
                                                        }else{
                                                            $thumb_img =  $default_img;
                                                        }
                                                        ?>
                                                        <a href="http://kiam.kr/?<?=strip_tags($row['card_short_url'].$mem_row['mem_code'])?>" target="_blank">
                                                            <img class="zoom" src="<?=$thumb_img?>" style="width:50px;">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td><?=$row['card_addr']?></td>
                                                <td>
                                                    <?=$row['card_phone']?>
                                                </td>
                                                <td><?=$totPrice?$totPrice:"0"?></td>
                                                <td style="font-size:12px;"><?=$row['req_data']?></td>
                                                <td style="font-size:12px;"><?=$crow[0]?></td>
                                                <td><?=$row['iam_click']?></td>
												<td>
                                                    <a href="javascript:show_alarm_1('<?=$row['idx']?>')" style="cursor:pointer;">신청</a>
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
    <script>
        function show_alarm_1(idx){
            var msg = "신청하기 전 필독하세요!!\n\n1. 신청하기 클릭 : 자신이 원하는 업체 IAM을 선택하고 [신청]을 클릭한 후 아이디를 입력하여 저장하면 자신의 리스트로 이동합니다. \n2. 신청건수 제한 : 소개 사업자는 신청리스트에 답을수 있는 IAM이 총5건으로 제한됩니다. 단, 사용하기로 확정된 업체 IAM은 제한건수와 무관합니다.\n3. 신청후 소개기간 : 소개 사업자는 신청후 15일 이내에 업체 대표가 사용하겠다는 의견을 받아 사용하기를 클릭해야 합니다. 15일이 지나면 자동으로 해당 업체의 IAM이 신청리스트에서 사라져 전체리스트로 이동합니다.\n4. 재신청하기 : 신청후 15일이 지나 신청리스트에서 사라지면 다른 소개사업자가 신청하기 전에 다시 신청해야 합니다. 만약 타 소개 사업자가 신청하게 되면 자신의 소속과 추천으로 동기화가 되지 않으므로 주의해야 합니다.";

            if(confirm(msg)){
                save_req_id(idx);
            }
        }

        function save_req_id(idx){
            var req_id='<?=$_SESSION['one_member_id']?>';

            $.ajax({
                type:"POST",
                url:"/admin/ajax/worker_share_reg.php",
                data:{
                    mode:"reg_req_id",
                    req_id:req_id,
                    card_idx:idx
                },
                success:function(data){
                    if(data == "1"){
                        alert("신청되었습니다.");
                        location.reload();
                    }
                    else if(data == "0"){
                        alert("리셀러 사업자가 아닙니다. 다시 확인해 주세요.");
                        return;
                    }
                    else if(data == "2"){
                        alert("최대 5개까지만 입력이 가능하므로 이미 신청된 5개 중에 한개 이상을 취소하거나 업체사용확인이 된 후에 추가 신청해야 합니다.");
                        return;
                    }
                },
                error: function(){
                    alert('삭제 실패');
                }
            });
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
include_once "_foot.php";
?>