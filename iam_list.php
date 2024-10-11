
<script language="javascript">
location.replace('/ma.php');
</script>
<?
exit;
}
extract($_REQUEST);
$tmpmemid = $_SESSION[one_member_id];
$sql="select * from Gn_Member  where mem_id='".$_SESSION[one_member_id]."'";
$sresul_num=mysqli_query($self_con,$sql);
$data=mysqli_fetch_array($sresul_num);
?>
<script>
function copyHtml(){
    var trb = $.trim($('#sHtml').html());
    var IE=(document.all)?true:false;
    if (IE) {
        if(confirm("이 링크를 복사하시겠습니까?")) {
            window.clipboardData.setData("Text", trb);
        }
    } else {
            temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", trb);
    }
}
</script>
<style>
    .pop_right {
        position: relative;
        right: 2px;
        display: inline;
        margin-bottom: 6px;
        width: 5px;
    }

    .button2 {
        cursor: pointer;
    }
</style>
<div class="big_sub">
    <?php include_once "mypage_base_navi.php";?>
    <div class="m_div sub_4c">
        

        <div class="m_body">


            <form name="pay_form" action="" method="post" class="my_pay">

            <input type="hidden" name="page" value="<?=$page?>" />
            <input type="hidden" name="page2" value="<?=$page2?>" />        
            


            <div class="bnt main-buttons" style="background-color:  #086A87; padding-bottom:10px;">
                <div class="wrap2">
                    <br>
                    <a class="button2" target="" 
                        <? 


                        $sql="select service_type from Gn_Member where mem_id='".$_SESSION[one_member_id]."'";
                        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                        $row=mysqli_fetch_array($result);
                        $service_type = $row[service_type];

                        

                        $sql="select count(coach_id) as cnt from gn_coach_apply a inner join Gn_Member b on b.mem_code = a.mem_code where b.mem_id='".$_SESSION[one_member_id]."'";


                        //echo $sql;

                        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                        $row=mysqli_fetch_array($result);
                        $coach_apply_count=$row[cnt];

                       // echo "service_type=".$service_type;

                        if($service_type == 1 || $service_type == 3 ){
                            if($coach_apply_count > 0){
                            ?>
                                onclick="onCoachDuplicated()"
                            <?
                            }else{
                            ?>
                                onclick="onCoach()"
                            <?
                            }
                            
                        }else{
                        ?>   
                            onclick="onCoachServiceNotAllow()"
                        <?
                        } 
                        ?> 
                    >
                    코치신청하기<br>가르치고싶어요!</a>
                    <a class="button2" target="" 
                        <? 
                        $sql="select count(coach_id) as cnt from gn_coaching_apply a inner join Gn_Member b on b.mem_code = a.mem_code where b.mem_id='".$_SESSION[one_member_id]."' and a.agree = 0";

                        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                        $row=mysqli_fetch_array($result);
                        $coaching_apply_count=$row[cnt];

                        if($coaching_apply_count > 0){
                        ?>
                            onclick="onCotyDuplicated()"
                        <?
                        }else{
                        ?>
                            onclick="onCoty()"
                        <?
                        }
                        ?>
                    >수강신청하기<br>배우고싶어요!</a>
                </div>
            </div> 



            <? if($coach_apply_count > 0){ ?>
            <div class="a1" style="margin-top:15px; margin-bottom:15px">
                <li style="float:left;">코칭정보관리리스트 </li>
                <li style="float:right;"></li>
                <p style="clear:both"></p>
            </div>
            <div>
                <div class="p1">
                    <style type="text/css">
                        #search_text
                        {
                            height: 37px;
                            margin-bottom: 3px;
                            width: 350px;
                            padding-left: 10px;
                        }
                    </style>
                    <input class="search_text" type="text" name="search_text" placeholder="검색값: 코티이름,날짜,제목,내용,파일명" id="search_text" value="<?=$_REQUEST[search_text]?>" />
                    <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="/images/sub_mypage_11.jpg" /></a>
                    <div style="float:right">

                        <? 
                        $sql="select *,count(coach_id) as cnt from gn_coach_apply a inner join Gn_Member b on b.mem_code = a.mem_code where b.mem_id='".$_SESSION[one_member_id]."' and agree = 1";

                        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                        $row=mysqli_fetch_array($result);
                        $intRowCount=$row[cnt];

                        if($intRowCount > 0){
                            $coach_id = $row[coach_id];
                        ?>
                        코티:
                        <select id="coty_info_select_id" style="height:35px;">
                          <? 
                          $sql="select * from gn_coaching_apply a inner join Gn_Member b on b.mem_code = a.mem_code where a.coach_id= ".$coach_id." order by reg_date desc";
                          $coaching_res = mysqli_query($self_con,$sql);







                          while($coaching_data = mysqli_fetch_array($coaching_res)) { 


                                 $sql="select sum(coaching_time) as sum from gn_coaching_info where coty_id=".$coaching_data[coty_id]." order by coaching_turn desc";

                                $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                                $row=mysqli_fetch_array($result);
                                $last_time_sum=$row[sum];

                                if( ($coaching_data[cont_time] * 60) != $last_time_sum){

                               

                            ?>
                            <option value="<?=$coaching_data["coty_id"] ?>_<?=$coach_id?>_<?=$coaching_data["mem_name"] ?>_<?=$coaching_data["mem_code"] ?>"  
                              ><?=$coaching_data["mem_id"] ?> / <?=$coaching_data["mem_name"] ?></option>

                              
                          <?} }?>
                        </select>

                        <input type="button" value="코칭신청보기" class="btn btn-primary" onclick="onCotyInfo()">

                        <? } ?>



                        <input type="button" value="코칭정보입력" class="btn btn-success" 

                        <?
                        if($intRowCount > 0){
                        ?>
                            onclick="location='mypage_coaching_write.php'"
                        <?
                        }else{
                        ?>
                            style="display: none;" 
                            onclick="disabledCoachAlert()"
                        <?
                        }
                        ?>
                        >



                    </div>
                </div>
                <div>
                    <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="width:4%;">No</td>
                            <td style="width:3%;">코칭<br>회차</td>
                            <td style="width:6%;">코티<br>이름</td>
                            <td style="width:5%;">계약<br>기간</td>
                            <!-- <td style="width:8%;">코칭<br>기간</td> -->
                            <td style="width:8%;">코칭<br>일시</td>
                            <td style="width:4%;">코칭<br>시간</td>
                            <td style="width:5%;">잔여<br>일시</td>
                            <td style="width:10%;">코칭<br>제목</td>
                            <td style="width:10%;">코칭<br>내용</td>
                            <td style="width:8%;">첨부<br>파일</td>
                            <td style="width:8%;">코칭<br>평가</td>
                            <td style="width:8%;">등록<br>일시</td>
                            <td style="width:6%;">코칭<br>조회</td>
                            <td style="width:5%;">대기<br>승인</td>
                            <td style="width:4%;">수정<br>삭제</td>
                        </tr>

                        <?
                        // $date_today = date("Y-m-d");
                        // $convertedTime = date('Y-m-d H:i:s',strtotime('-3 day',strtotime($date_today)));

                        // $sql1 = "update gn_coaching_info set agree = 1, site_value=3  where reg_date < '$date_today'";

                        // echo $sql1;
                        // $sql1_res = mysqli_query($self_con,$sql1);
                        // echo $convertedTime."  이전에 등록한 코칭정보가 자동승인이 되었습니다.<p>";


                        //필터코드 구현

                        $sql_serch="b.mem_id = '$_SESSION[one_member_id]'";
                        if($_REQUEST[search_text])
                        {
                            $search_text = $_REQUEST[search_text];
                            $sql_serch.=" and (search_text like '%$search_text%')";
                            $sql_serch.=" or (coaching_date like '%$search_text%')";
                            $sql_serch.=" or (reg_date like '%$search_text%')";
                            $sql_serch.=" or (coaching_file like '%$search_text%')";
                        }
            

                        $sql="select count(coaching_id) as cnt from gn_coaching_info a inner join Gn_Member b on b.mem_code = a.coach_mem_code where $sql_serch";

                        //echo $sql;
                        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                        $row=mysqli_fetch_array($result);
                        $intRowCount=$row[cnt];
                        if($intRowCount)
                        {
                            if (!$_POST[lno])
                                $intPageSize =20;
                            else
                                $intPageSize = $_POST[lno];
                        if($_POST[page])
                        {
                            $page=(int)$_POST[page];
                            $sort_no=$intRowCount-($intPageSize*$page-$intPageSize);
                        }
                        else
                        {
                            $page=1;
                            $sort_no=$intRowCount;
                        }
                        if($_POST[page2])
                            $page2=(int)$_POST[page2];
                        else
                            $page2=1;

                        $int=($page-1)*$intPageSize;
                        if($_REQUEST[order_status])
                            $order_status=$_REQUEST[order_status];
                        else
                            $order_status="desc";
                        if($_REQUEST[order_name])
                            $order_name=$_REQUEST[order_name];
                        else
                        $order_name="reg_date";
                        $intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);
                        $sql="select * from gn_coaching_info a inner join Gn_Member b on b.mem_code = a.coach_mem_code where $sql_serch order by $order_name $order_status limit $int,$intPageSize"; 


                        //echo $sql;

                        $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                        while($coaching_info_data=mysqli_fetch_array($result))
                        {
                            $sql_num="select * from gn_coaching_apply a left join Gn_Member b on a.mem_code = b.mem_code where a.coty_id='$coaching_info_data[coty_id]' ";
                            $resul_num=mysqli_query($self_con,$sql_num);
                            $coaching_data=mysqli_fetch_array($resul_num); 
                        ?>
                        <tr>
                            <!-- <td><input type="checkbox" name=""></td>  -->
                            <td><?=$sort_no?></td>
                            <td style="font-size:12px;"><?=$coaching_info_data[coaching_turn]?></td>
                            <td style="font-size:12px;"><?=$coaching_data[mem_name]?></td>
                            <td style="font-size:12px;color:red;"><?=$coaching_data[cont_term]?>일<br><?=$coaching_data[cont_time]?>:00</td>

                            <td style="font-size:12px;display: none;">
                                <?
                                echo date('Y-m-d',strtotime($coaching_info_data[start_date]));
                                ?>
                                 ~ 
                                <? 
                                echo date('Y-m-d',strtotime($coaching_info_data[end_date]));
                                ?>
                                    

                                </td>
                            <td style="font-size:12px;"><?=$coaching_info_data[coaching_date]?></td>
                            <td style="font-size:12px;"><?=$coaching_info_data[coaching_time]?>분</td>

                            <td style="font-size:12px;color:red;">

                                <?
                                // 잔여일시 계산

                                // $sql_startdate="select coaching_date from gn_coaching_info where coty_id='$coaching_info_data[coty_id]' and coach_id='$coaching_info_data[coach_id]' and coaching_turn= 1 ";
                                // $resul_num=mysqli_query($self_con,$sql_startdate);
                                // $startdate_data=mysqli_fetch_array($resul_num);


                                // $enddate = date('Y-m-d H:i:s',strtotime('+'.$coaching_data[cont_term].' day',strtotime($startdate_data[coaching_date])));

                                // $currentTime = date("Y-m-d H:i:s");

                                // if($enddate < $currentTime){
                                //     echo 0;
                                // }else{
                                //     $date1 = strtotime($currentTime);
                                //     $date2 = strtotime( $enddate);
                                //     $diff = floor(abs($date2 - $date1)/3600 / 24) + 1;


                                //     //echo $diff >$coaching_data[cont_term]?$coaching_data[cont_term]:$diff;
        
                                // }

                                $date1 = strtotime($coaching_info_data[end_date]);
                                $date1 = strtotime(date('Y-m-d', $date1));
                                //echo $date1."일<br>";

                                $date2 = strtotime($coaching_info_data[coaching_date]);
                                $date2 = strtotime(date('Y-m-d', $date2));
                                //echo $date2."일<br>";

                                $diff = floor(abs($date1 - $date2)/3600 / 24);

                                echo $diff ;


                                    echo "일<br>";
                                    // 잔여시간
                                    $remain_tatal_min =  ($coaching_data[cont_time] * 60) - $coaching_info_data[past_time_sum] - $coaching_info_data[coaching_time];
                                    $remain_hour = floor($remain_tatal_min / 60);
                                    $remain_min = $remain_tatal_min % 60;
                                    if($remain_min < 10){
                                        $remain_min = "0".$remain_min;
                                    }
                                    echo $remain_hour.":".$remain_min;

                                ?>
                            </td>

                            <td style="font-size:12px;"><?=$coaching_info_data[coaching_title]?></td>
                            <td style="font-size:12px;"><?=$coaching_info_data[coaching_content]?></td>
                            <td style="font-size: 12px;">
                                <a href="<?=$coaching_info_data[coaching_file]?>" style="color: blue;">
                                   <? if($coaching_info_data[coaching_file]){
                                        echo "파일";
                                    } ?>
                                </a>
                            </td>
                            <td style="font-size:12px;" title="회차점수/누적점수" >
                                
                           
                               <?

                                $sql_1="select SUM(coty_value) as coty_sum,SUM(coach_value) as coach_sum,SUM(site_value) as site_sum from gn_coaching_info where coty_id='$coaching_info_data[coty_id]' and coach_id='$coaching_info_data[coach_id]' and coaching_turn <= '$coaching_info_data[coaching_turn]'";
                                //echo $sql_1;
                                $res_1=mysqli_query($self_con,$sql_1);
                                $sum=mysqli_fetch_array($res_1);


                                //누적 합점
                                $save_value = ($sum[coty_sum] + $sum[coach_sum] + $sum[site_sum]);
                                //누적 만점
                                $full_value = (15 * $coaching_info_data[coaching_turn]);
                                //누적 평균- 백분율
                                $save_aver_value = ($save_value / $full_value ) * 100;

                                //턴 점수 
                                $turn_aver_value =(($coaching_info_data[coach_value] + $coaching_info_data[coty_value]+$coaching_info_data[site_value]) / 15) * 100;
                                
                                echo (round($turn_aver_value*10)/10)." / ".(round($save_aver_value*10)/10)."";






                                ?>

                            </td>
                            <td style="font-size:12px;"><?=$coaching_info_data[reg_date]?></td>
                            <td style="font-size:12px;"><a  style="color:blue;" href='mypage_coaching_info_view.php?coaching_id=<?php echo $coaching_info_data['coaching_id'];?>'>보기</a></td>
                            <td style="font-size:12px;"><?=$coaching_info_data[agree]==0?"<label class='label label-warning label-sm'>대기</label>":"<label class='label label-primary label-sm'>승인</label>"?></td>
                            <td style="font-size:12px;">
                                <? 


                                $sql_1 = "Select * FROM `gn_coaching_info` WHERE coaching_turn = (  SELECT MAX( coaching_turn ) AS max_c_turn FROM  `gn_coaching_info`  WHERE `coach_id` = '$coaching_info_data[coach_id]' AND `coty_id` = '$coaching_info_data[coty_id]' )  AND `coach_id` = '$coaching_info_data[coach_id]' AND `coty_id` = '$coaching_info_data[coty_id]';";
                                $res_1=mysqli_query($self_con,$sql_1);
                                $coaching=mysqli_fetch_array($res_1);

                                //echo $coaching[coaching_turn];
                                $max_coaching_turn = $coaching[coaching_turn];


                                if($coaching_info_data[agree]==0 && $max_coaching_turn == $coaching_info_data[coaching_turn]){ 

                                    ?>
                                <a class="label label-success label-sm" href='mypage_coaching_write.php?coaching_id=<?php echo $coaching_info_data['coaching_id'];?>' >수정</a><BR>
                                <a class="label label-danger label-sm" href="javascript:;;" onclick="removeRow('<?php echo $coaching_info_data['coaching_id'];?>')">삭제</a>
                                <? } ?>
                            </td>
                           
                        </tr>
                        <?
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
            <? } ?>


            </form>



            <? 


            $sql="select count(coach_id) as cnt from gn_coaching_apply a inner join Gn_Member b on b.mem_code = a.mem_code where b.mem_id='".$_SESSION[one_member_id]."' and a.agree = 1";
            $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
            $row=mysqli_fetch_array($result);
            $coaching_apply_agree_count=$row[cnt];

            if($coaching_apply_agree_count > 0){ ?>

            <div class="a1" style="margin-top:15px; margin-bottom:15px">
                <li style="float:left;">코칭정보조회리스트 </li>
                <li style="float:right;"></li>
                <p style="clear:both"></p>
            </div>
            <div>
                <!--<div class="p1">
                    <input type="text" name="search_text" placeholder="" id="search_text" value="<?=$_REQUEST[search_text]?>" />
                    <a href="javascript:void(0)" onclick="pay_form.submit()"><img src="/images/sub_mypage_11.jpg" /></a>
                </div> -->
                <div>
                    <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="width:4%;">No</td>
                            <td style="width:3%;">코칭<br>회차</td>
                            <td style="width:6%;">코치<br>이름</td>
                            <td style="width:5%;">계약<br>기간</td>
                            <!-- <td style="width:8%;">코칭<br>기간</td> -->
                            <td style="width:8%;">코칭<br>일시</td>
                            <td style="width:4%;">코칭<br>시간</td>
                            <td style="width:5%;">잔여<br>일시</td>
                            <td style="width:10%;">코칭<br>제목</td>
                            <td style="width:10%;">코칭<br>내용</td>
                            <td style="width:10%;">첨부<br>파일</td>
                            <td style="width:8%;">코칭<br>평가</td>
                            <td style="width:8%;">등록<br>일시</td>
                            <td style="width:6%;">코칭<br>조회</td>
                            <td style="width:5%;">대기<br>승인</td>
                        </tr>

                        <?

                        //필터코드 후에 구현

                        $sql_serch="b.mem_id = '$_SESSION[one_member_id]'";
                        if($_REQUEST[search_text])
                        {
                            $search_text = $_REQUEST[search_text];
                            $sql_serch.=" and (search_text like '%$search_text%')";
                        }

                        $sql="select count(coaching_id) as cnt from gn_coaching_info a inner join Gn_Member b on b.mem_code = a.coty_mem_code where $sql_serch";

                        $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                        $row=mysqli_fetch_array($result);
                        $intRowCount=$row[cnt];
                        if($intRowCount)
                        {
                            if (!$_POST[lno])
                                $intPageSize =20;
                            else
                                $intPageSize = $_POST[lno];
                        if($_POST[page])
                        {
                            $page=(int)$_POST[page];
                            $sort_no=$intRowCount-($intPageSize*$page-$intPageSize);
                        }
                        else
                        {
                            $page=1;
                            $sort_no=$intRowCount;
                        }
                        if($_POST[page2])
                            $page2=(int)$_POST[page2];
                        else
                            $page2=1;

                        $int=($page-1)*$intPageSize;
                        if($_REQUEST[order_status])
                            $order_status=$_REQUEST[order_status];
                        else
                            $order_status="desc";
                        if($_REQUEST[order_name])
                            $order_name=$_REQUEST[order_name];
                        else
                        $order_name="reg_date";
                        $intPageCount=(int)(($intRowCount+$intPageSize-1)/$intPageSize);
                        $sql="select * from gn_coaching_info a inner join Gn_Member b on b.mem_code = a.coty_mem_code where $sql_serch order by $order_name $order_status limit $int,$intPageSize"; 
                        $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
                        while($coaching_info_data=mysqli_fetch_array($result))
                        {
                            $sql_num="select * from gn_coaching_apply a left join Gn_Member b on a.mem_code = b.mem_code where a.coty_id='$coaching_info_data[coty_id]' ";
                            $resul_num=mysqli_query($self_con,$sql_num);
                            $coaching_data=mysqli_fetch_array($resul_num);

                            $sql_num="select * from gn_coach_apply a left join Gn_Member b on a.mem_code = b.mem_code where a.coach_id='$coaching_info_data[coach_id]' ";
                            $resul_num=mysqli_query($self_con,$sql_num);
                            $coach_data=mysqli_fetch_array($resul_num); 
                        ?>
                        <tr>
                            <!-- <td><input type="checkbox" name=""></td>  -->
                            <td><?=$sort_no?></td>
                            <td style="font-size:12px;"><?=$coaching_info_data[coaching_turn]?></td>
                            <td style="font-size:12px;"><?=$coach_data[mem_name]?></td>
                            <td style="font-size:12px;color:red;"><?=$coaching_data[cont_term]?>일<br><?=$coaching_data[cont_time]?>:00</td>
                            <!-- 코칭기간 -->
                            <td style="font-size:12px;display: none;">
                                <?
                                echo date('Y-m-d',strtotime($coaching_info_data[start_date]));
                                ?>
                                 ~ 
                                <? 
                                echo date('Y-m-d',strtotime($coaching_info_data[end_date]));
                                ?>
                            </td>
                            <td style="font-size:12px;"><?=$coaching_info_data[coaching_date]?></td>
                            <td style="font-size:12px;"><?=$coaching_info_data[coaching_time]?>분</td>

                            <td style="font-size:12px;color:red;">

                               <?
                                // 잔여일시 계산

                                // $sql_startdate="select coaching_date from gn_coaching_info where coty_id='$coaching_info_data[coty_id]' and coach_id='$coaching_info_data[coach_id]' and coaching_turn= 1 ";
                                // $resul_num=mysqli_query($self_con,$sql_startdate);
                                // $startdate_data=mysqli_fetch_array($resul_num);


                                // $enddate = date('Y-m-d H:i:s',strtotime('+'.$coaching_data[cont_term].' day',strtotime($startdate_data[coaching_date])));

                                // $currentTime = date("Y-m-d H:i:s");

                                // if($enddate < $currentTime){
                                //     echo 0;
                                // }else{
                                //     $date1 = strtotime($currentTime);
                                //     $date2 = strtotime( $enddate);
                                //     $diff = floor(abs($date2 - $date1)/3600 / 24) + 1;


                                //     echo $diff >$coaching_data[cont_term]?$coaching_data[cont_term]:$diff;
        
                                // }


                                 $date1 = strtotime($coaching_info_data[end_date]);
                                $date1 = strtotime(date('Y-m-d', $date1));
                                //echo $date1."일<br>";

                                $date2 = strtotime($coaching_info_data[coaching_date]);
                                $date2 = strtotime(date('Y-m-d', $date2));
                                //echo $date2."일<br>";

                                $diff = floor(abs($date1 - $date2)/3600 / 24);

                                echo $diff ;

                                    echo "일<br>";
                                // 잔여시간
                                    $remain_tatal_min =  ($coaching_data[cont_time] * 60) - $coaching_info_data[past_time_sum] - $coaching_info_data[coaching_time];
                                    $remain_hour = floor($remain_tatal_min / 60);
                                    $remain_min = $remain_tatal_min % 60;
                                    if($remain_min < 10){
                                        $remain_min = "0".$remain_min;
                                    }


                                    echo $remain_hour.":".$remain_min;



                                ?>
                            </td>

                            <td style="font-size:12px;"><?=$coaching_info_data[coaching_title]?></td>
                            <td style="font-size:12px;"><?=$coaching_info_data[coaching_content]?></td>
                             <td style="font-size: 12px;">
                                <a href="<?=$coaching_info_data[coaching_file]?>" style="color: blue;">
                                    <? if($coaching_info_data[coaching_file]){
                                        echo "파일";
                                    } ?>
                                </a>
                            </td>

                           <td style="font-size:12px;" title="회차점수/누적점수" >
                                
                           
                               <?

                                $sql_1="select SUM(coty_value) as coty_sum,SUM(coach_value) as coach_sum,SUM(site_value) as site_sum from gn_coaching_info where coty_id='$coaching_info_data[coty_id]' and coach_id='$coaching_info_data[coach_id]' and coaching_turn <= '$coaching_info_data[coaching_turn]'";
                                //echo $sql_1;
                                $res_1=mysqli_query($self_con,$sql_1);
                                $sum=mysqli_fetch_array($res_1);


                                //누적 합점
                                $save_value = ($sum[coty_sum] + $sum[coach_sum] + $sum[site_sum]);
                                //누적 만점
                                $full_value = (15 * $coaching_info_data[coaching_turn]);
                                //누적 평균- 백분율
                                $save_aver_value = ($save_value / $full_value ) * 100;

                                //턴 점수 
                                $turn_aver_value =(($coaching_info_data[coach_value] + $coaching_info_data[coty_value]+$coaching_info_data[site_value]) / 15) * 100;
                                echo (round($turn_aver_value*10)/10)." / ".(round($save_aver_value*10)/10)."";

                                ?>

                            
                                
                           <br>

                                <button type="button" class="btn btn-sm  

                                <? if($coaching_info_data[coty_comment] != ""){ echo "btn-primary"; }else{ echo "btn-success";}?>

                                " data-toggle="modal" data-target="#myModal_<?=$coaching_info_data[coaching_id]?>" style="width: 50px;height: 20px;padding: 1px;"  


                                    >
                                    <? if($coaching_info_data[coty_comment] != ""){ echo "평가함"; }else{ echo "평가";}?> 
                               </button>


                                <div class="modal inmodal" id="myModal_<?=$coaching_info_data[coaching_id]?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content animated bounceInRight">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                <h3 class="modal-title"><?=$coaching_info_data[coaching_title]?></h3>
                                                <small class="font-bold"><?=$coaching_info_data[coaching_content]?></small>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row" ><div class="col-md-3" style="font-size: 16px;">코티평가</div> <div class="col-md-9">
                                                    <input type="email" class="coty_value_input form-control" placeholder="1=훌륭해요 2=좋았어요 3=괜찮아요 4=보완해요 5=대안필요" value="<?=$coaching_info_data[coty_value]?>"   <? if($coaching_info_data[coty_comment] != ""){ echo disabled; }?> ></div>

                                                </div>

                                                    <br>
                                                         <? if($coaching_info_data[coty_comment] == ""){  ?>
                                                          5=훌륭해요, 4=좋았어요, 3=괜찮아요, 2=보완해요, 1=대안필요
                                                          
                                                    <br>
                                                <br>
                                            <? } ?>
                                                <div class="row" ><div class="col-md-3" style="font-size: 16px;">코티의견</div> <div class="col-md-9">
                                                    <input type="email" class="coty_comment_input form-control"  value="<?=$coaching_info_data[coty_comment]?>"  <? if($coaching_info_data[coty_comment] != ""){ echo disabled; }?>></div></div>
                                                <br>
                                                 <? if($coaching_info_data[coty_comment] == ""){  ?>
                                                저장을 누르면 더 이상 수정이 되지 않습니다. 
                                                <br>
                                                최종확인했으면 저장하세요.
                                                <? } ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-white" data-dismiss="modal">취소</button>
                                                <? if($coaching_info_data[coty_comment] == ""){  ?>
                                                
                                                <button type="button" class="btn btn-primary" onclick="onCotyComment('<?=$coaching_info_data[coaching_id]?>')">저장</button>
                                                <? } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </td>
                            <td style="font-size:12px;"><?=$coaching_info_data[reg_date]?></td>
                            <td style="font-size:12px;"><a class="" style="color:blue;" href='mypage_coaching_info_view.php?coaching_id=<?php echo $coaching_info_data['coaching_id'];?>'>보기</a></td>
                             <td style="font-size:12px;"><?=$coaching_info_data[agree]==0?"<label class='label label-warning label-sm'>대기</label>":"<label class='label label-primary label-sm'>승인</label>"?></td>
                           
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
                        <?
                        }
                        else
                        {
                        ?>
                        <tr>
                            <td colspan="15">
                                검색된 내용이 없습니다.
                            </td>
                        </tr>
                        <?
                        }
                        ?>
                    </table>
                </div>
            </div>

            <? } ?>


            <? if($coaching_apply_count == 0 && $coach_apply_count == 0){ ?>
            <div style="text-align: center; font-size: 16px;margin-top: 20px;">
                코치는 사업자만 신청할수 있어요. 수강신청은 누구나 할수 있습니다.
            </div>

            <? } ?>



        </div>


    </div>
</div> 

<script>
    $(function() {
        $('.area_view').on('click', function(){
            if($(this).text().length > 10) {
                if($(this).css("overflow-y") == "hidden") {
                    $(this).css("overflow-y","auto");
                    $(this).css("height","80px");

                } else {
                    $(this).css("overflow-y","hidden");
                    $(this).css("height","18px");
                }
            }
        });
        $('.copyLinkBtn').bind("click", function() {
            var trb = $(this).data("link");
            var IE=(document.all)?true:false;
            if (IE) {
                if(confirm("이 링크를 복사하시겠습니까?")) {
                    window.clipboardData.setData("Text", trb);
                }
            } else {
                    temp = prompt("Ctrl+C를 눌러 클립보드로 복사하세요", trb);
            }
        });
        $('.switch').on("change", function() {
            var no = $(this).find("input[type=checkbox]").val();
            var status = $(this).find("input[type=checkbox]").is(":checked")==true?"Y":"N";
            $.ajax({
                 type:"POST",
                 url:"mypage.proc.php",
                 data:{
                     mode:"land_updat_status",
                     landing_idx:no,
                     status:status,
                     },
                 success:function(data){
                    //alert('신청되었습니다.');location.reload();
                 }
                })

            //console.log($(this).find("input[type=checkbox]").is(":checked"));
            //console.log($(this).find("input[type=checkbox]").val());
        });
    })
    function viewEvent(str){
        window.open(str, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=200,left=200,width=1000,height=1000");

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

    function removeRow(no) {
        if(confirm('삭제하시겠습니까?')) {
            $.ajax({
                 type:"POST",
                 url:"mypage.proc.php",
                 data:{
                     mode:"delete_coaching_info",
                     coaching_id:no
                     },
                 success:function(data){
                    alert(data);
                    location.reload();

                    }
                })

        }
    }

    function removeAll() {
        var no ="";
        if(confirm('삭제하시겠습니까?')) {
            $.ajax({
                 type:"POST",
                 url:"mypage.proc.php",
                 data:{
                     mode:"lecture_del",
                     landing_idx:no
                     },
                 success:function(data){
                    alert('삭제되었습니다.');
                    location.reload();
                    }
                })

        }
    }
</script>



<!-- 쥴리언 코드 -->
<script type="text/javascript">
    
    function onCotyInfo(){

        var str = $("#coty_info_select_id").val();
        var coty_id = str.split('_')[0];
        var coach_id = str.split('_')[1];


        $.ajax({
            type:"POST",
            url:"/mypage.proc.php",
            data:{mode:"read_coaching_apply",coty_id:coty_id,coach_id:coach_id},
            dataType: 'html',
            success:function(data){
                var coty_data = JSON.parse(data).coaching_apply_data;
                var coaching_info_data = JSON.parse(data).coaching_info_data;
                var coaching_status = JSON.parse(data).coaching_status;
                var coaching_info_data_count = JSON.parse(data).coaching_info_data_count;
                $("#modal-coty-info").modal("show");
                $("#modal_coty_name").html(coty_data.mem_name);
                $("#modal_coty_id").html(coty_data.mem_id);
                $("#modal_coty_phone").html(coty_data.mem_phone);
                $("#modal_coty_email").html(coty_data.mem_email);
                $("#modal_coty_cont_term").html(coty_data.cont_term +"일");
                $("#modal_coty_cont_time").html(coty_data.cont_time + "시간");
                $("#modal_coty_coaching_price").html((coty_data.coaching_price/10000) + "만원");
                $("#modal_coty_want_coaching").html(coty_data.want_coaching);
                $("#modal_coty_coaching_turn").html(coaching_info_data_count[0]);

                $("#modal_coty_coaching_start_date").html("");
                $("#modal_coty_coaching_start_date").html(coaching_info_data.start_date);
                $("#modal_coty_coaching_end_date").html("");
                $("#modal_coty_coaching_end_date").html(coaching_info_data.end_date);
                $("#modal_coty_coaching_status").html("");
                $("#modal_coty_coaching_status").html(coaching_status);

            },
            error: function(){
                alert('error');
            }
        });

    }

    function onCoty(){
      // window.open('mypage_coaching_apply.php', "SingleSecondaryWindowName",
      //        "resizable,scrollbars,status")
        $("#modal-coty-apply").modal("show");
    }

    function onCotyDuplicated(){
        showMessageModal("신청 확인", "<p>고객님께서는 이미 수강신청을 하셨습니다. <br/> 송금계좌 : 우리은행 1002-435-097802 송조은 <br/> 코칭문의 : 사이트 상/하단 카카오 상담 이용</p>");
    }

    function onCoachServiceNotAllow(){
        showMessageModal("신청 오류", "<p>사업자가 아니므로 사업자가 된 이후에 신청해주세요.</p>");
    }

    function onCoach(){
        $("#modal-alert").modal("show");
    }
    function onCoachDuplicated(){
        showMessageModal("신청 오류", "<p>이미 신청되어있습니다. 담당자와 상담해 주세요.</p>");
    }

    function onCoachApply(){
        $.ajax({
            type:"POST",
            url:"/mypage.proc.php",
            data:{mode:"create_coach_apply"},
            dataType: 'html',
            success:function(data){
                $("#modal-alert").modal("hide");
                showMessageModal("신청 성공", "<p>코치로 신청되었습니다. 감사합니다. </p>");

            },
            error: function(){
                alert('정상적으로 신청되지 않았습니다.');
                console.log("Error is dected");
            }
        });
    }
    function onCotyApply(){
         var coaching_type = $('input[name="coaching_type"]:checked').val();
          var cont_term= 0;
          var cont_time= 0;
          var coaching_price = 0;

          var coaching_want = $("#coaching_want").val();

          switch(coaching_type){
            case "1":cont_term = 3;cont_time = 2; coaching_price = 50000;break;
            case "2":cont_term = 7;cont_time = 4; coaching_price = 90000;break;
            case "3":cont_term = 11;cont_time = 6; coaching_price = 130000;break;
            case "4":cont_term = 15;cont_time = 8; coaching_price = 170000;break;
            case "5":cont_term = 19;cont_time = 10; coaching_price = 210000;break;
            case "6":cont_term = 25;cont_time = 12; coaching_price = 250000;break;
            case "7":cont_term = 30;cont_time = 14; coaching_price = 280000;break;
            case "8":cont_term = 60;cont_time = 30; coaching_price = 500000;break;
            default:
              alert('코칭형태 정확히 선택하세요.');
              return;
              break;
          }


          $.ajax({
              type:"POST",
              url:"/mypage.proc.php",
              data:{mode:"create_coaching_apply",cont_time:cont_time,cont_term:cont_term,coaching_price:coaching_price,coaching_want:coaching_want},
              dataType: 'html',
              success:function(data){
                //alert("교육과정 신청되었습니다.담당자와 상담후에 진행하세요. 감사합니다.");
                

                  $("#modal-coty-apply").modal("hide");
                  showMessageModal("신청 성공", "<h4>고객님, 수강신청이 접수되었습다.<h4> <p>계좌안내 : 우리은행 1002-435-097802 송조은 <br/><br/> 위의 계좌로 코칭비용을 입금하시면 담당코치 배정 후 연락드리겠습니다. </p>");

              },
              error: function(){
               alert('정상적으로 신청되지 않았습니다.');
                console.log("Error is dected");
              }
            });
    }

    function onCoachingProcessView(){
        window.open('https://oog.kiam.kr/pages/page_4243.php', "SingleSecondaryWindowName","resizable,scrollbars,status")
    }

    //코티 평가
    function onCotyComment(coaching_id){
        var selector = "#myModal_"+coaching_id;
        var coty_value_input = $(selector + " .coty_value_input").val();
        var coty_comment_input = $(selector + " .coty_comment_input").val();

        if(coty_value_input.trim()=="" || coty_comment_input.trim() == ""){
            alert("값을 정확히 입력해주세요.");
            return;
        }

        $.ajax({
            type:"POST",
            url:"/mypage.proc.php",
            data:{mode:"update_coaching_info_coty_comment",coty_value:coty_value_input, coty_comment:coty_comment_input,coaching_id:coaching_id},
            dataType: 'html',
            success:function(data){
                alert('평가를 진행하였습니다.');
                $(selector).modal("hide");
                messageModalClosed();
            },
            error: function(){
                 alert('정상적으로 신청되지 않았습니다.');
                $(selector).modal("hide");
            }
        });
    }
    function showMessageModal(title,content){
        $("#modal-message").modal("show");
                $("#modal-message .modal-title").html(title);
                $("#modal-message .modal-body").html(content);
    }
    function messageModalClosed(){
        location.reload();
    }
    function disabledCoachAlert(){
        showMessageModal("오류", "<p>아직 코칭 허가가 되지 않았습니다. 담당자와 상담해 주세요.</p>");
    }
</script>



<!-- 코칭신청정보모달 -->
<div class="modal fade" id="modal-coty-info">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="modal-title" style="text-align: center;">코칭신청정보</h3>
            </div>
            <div class="modal-body">
          
                <div>
                    <table class="table table-bordered">
                                <tbody>
                                  
                                    <tr><td style="color:#00a1d4;">코티이름</td><td id="modal_coty_name"></td><td style="color:#00a1d4;">코티ID</td><td id="modal_coty_id"></td></tr>
                                    <tr><td style="color:#00a1d4;">전화번호</td><td id="modal_coty_phone"></td><td style="color:#00a1d4;">이메일</td><td id="modal_coty_email"></td></tr>
                                    <tr><td style="color:#00a1d4;">계약기간</td><td id="modal_coty_cont_term"></td ><td style="color:#00a1d4;">계약시간</td><td id="modal_coty_cont_time"></td></tr>

                                    <tr><td style="color:#00a1d4;">코칭비용</td><td id="modal_coty_coaching_price"></td><td style="color:#00a1d4;">희망코칭</td><td id="modal_coty_want_coaching"></td></tr>
                                    <tr><td style="color:#00a1d4;">코칭시작</td><td id="modal_coty_coaching_start_date" style="color:red;"></td><td style="color:#00a1d4;">코칭종료</td><td id="modal_coty_coaching_end_date" style="color:red;"></td></tr>
                                    <tr><td style="color:#00a1d4;">진행상태</td><td id="modal_coty_coaching_status"></td><td style="color:#00a1d4;">코칭회차</td><td id="modal_coty_coaching_turn"></td></tr>
                                 </tbody>
                            </table>
                </div>

            </div>
            <div class="modal-footer" style="text-align: center;">
                <a style="color:white;" class="btn btn-danger" data-dismiss="modal"> 종 료 </a>
            </div>
        </div>
    </div>
</div>



<!-- 코치신청모달 -->
<div class="modal fade" id="modal-alert">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">온리원셀링 코치신청하기</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success m-b-0">
                    <h4>온리원셀링 코치신청하기</h4>
                    <p>온리원 셀링의 코치로 활동하시겠습니까? <br> 코치활동에 대해서 자세히 보시려면 아래에서 확인하세요.</p>
                </div>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <a href="javascript:;" class="btn btn-warning" data-dismiss="modal">코치활동 엿보기</a>
                <a href="javascript:;" class="btn btn-danger" onclick="onCoachApply()">코치활동 신청하기</a>
                <a href="javascript:;" class="btn btn-default" data-dismiss="modal">다음에 신청하기</a>
            </div>
        </div>
    </div>
</div>


<!-- 코티신청모달 -->
<div class="modal fade" id="modal-coty-apply">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">코티신청하기</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success m-b-0">
                    <p>온리원셀링 자동솔루션에 대해서 코칭을 받고 싶으신가요? <br>
                    배우는 과정에 대해 자세히 보시려면 아래에서 확인하세요.</p>
                </div>

                <div>
                    <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>유효기간</th>
                                        <th>코칭시간</th>
                                        <th>코칭비용</th>
                                        <th>신청하기</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  
                                  <tr><td>3일</td><td>2시간</td><td>5만원</td><td><input type="radio" name="coaching_type" value="1"></td></tr>
                                    <tr><td>7일</td><td>4시간</td><td>9만원</td><td><input type="radio" name="coaching_type" value="2"></td></tr>
                                    <tr><td>11일</td><td>6시간</td><td>13만원</td><td><input type="radio" name="coaching_type" value="3"></td></tr>
                                    <tr><td>15일</td><td>8시간</td><td>17만원</td><td><input type="radio" name="coaching_type" value="4"></td></tr>
                                    <tr><td>19일</td><td>10시간</td><td>21만원</td><td><input type="radio" name="coaching_type" value="5"></td></tr>
                                    <tr><td>25일</td><td>12시간</td><td>25만원</td><td><input type="radio" name="coaching_type" value="6"></td></tr>
                                    <tr><td>30일</td><td>14시간</td><td>28만원</td><td><input type="radio" name="coaching_type" value="7"></td></tr>
                                    <tr><td>60일</td><td>30시간</td><td>50만원</td><td><input type="radio" name="coaching_type" value="8"></td></tr>
                                    <tr><td>협의</td><td>협의</td><td>협의</td><td><input type="radio" name="coaching_type" value="9"></td></tr>

                                 </tbody>
                            </table>

                             <tr>
                                <td>코칭비용 송금처</td>
								<td>우리은행 1002-435-097802</td>
					         </tr>
					        <tr>
                                <td>코칭희망내용</td>
                                   
                                <td><input id="coaching_want" type="text" name="coaching_want" required itemname='내용' style="width:100%;height:70px;padding: 5px;" placeholder="코칭받고 싶은 내용을 입력하세요.&#13;&#10;[예시] 디버검색기술,원스텝문자예약기능 등"/></td>
                            </tr>
                </div>

            </div>
            <div class="modal-footer" style="text-align: center;">
                <a  style="color:white;" class="btn btn-primary" onclick="onCoachingProcessView()">코칭과정보기</a>
                <a style="color:white;" class="btn btn-success" onclick="onCotyApply()">코칭신청하기</a>
                <a style="color:white;" class="btn btn-warning" data-dismiss="modal">다음에 보기</a>
            </div>
        </div>
    </div>
</div>

<!-- 메시지 모달 -->
<div class="modal fade" id="modal-message">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="text-align: center;">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body" style="text-align: center;">
                    
            </div>
            <div class="modal-footer" style="text-align: center;">
                <a href="javascript:;" class="btn btn-default" onclick="messageModalClosed()">종료</a>
            </div>
        </div>
    </div>
</div>




<link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
<script src="admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="/admin/bootstrap/js/bootstrap.min.js"></script>


<?
mysqli_close($self_con);
include_once "_foot.php";
?>
