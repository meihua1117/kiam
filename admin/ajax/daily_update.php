<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/

$cur_time = date("Y-m-d H:i:s");
$cur_time1 = date("YmdHis");
extract($_POST);

if($mode == "daily_update")
{
    $reg = time();
    $uni_id=$reg.sprintf("%02d",$k);
    $date = explode(",", $send_date);
    $end_date = max($date);
    $start_date = min($date);

    if($send_deny_msg == "on"){
        $deny = "Y";
    }
    else{
        $deny = "";
    }
    
    $query = "
    update Gn_daily set mem_id='{$_SESSION['one_member_id']}', 
                                send_num='$send_num',
                                group_idx='$group_idx',
                                total_count='$total_count',
                                title='$title',
                                content='$txt',
                                daily_cnt='$daily_cnt',
                                start_date='$start_date',
                                end_date='$end_date',
                                jpg='$upimage_str',
                                jpg1='$upimage_str1',
                                jpg2='$upimage_str2',
                                status='Y',
                                send_deny='$deny',
                                reg_date=NOW()
                                /*
                                ,
                                htime='$htime',
                                mtime='$mtime'
                                */
                        where gd_id='$gd_id'
                                
    ";
    mysqli_query($self_con,$query);
    
    if($gd_id > 0)  {
        $query = "delete from Gn_daily_date where gd_id='$gd_id';";
        mysqli_query($self_con,$query);
        
        $query = "delete from Gn_MMS where gd_id='$gd_id' ";
        mysqli_query($self_con,$query);

        $query = "delete from gn_mail where gd_id='$gd_id' ";
        mysqli_query($self_con,$query);  
    }
    $k = 0;
    $kk = 0;
    $sql="select * from Gn_MMS_Receive where grp_id = '$group_idx' ";
    $sresult = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    while($srow=mysqli_fetch_array($sresult)) {
        if($kk == $daily_cnt) { 
            $k++;
            $kk = 0;
        }                
        if($kk > 0)
            $recv_num_set[$k] .= ",".$srow['recv_num'];
        else
            $recv_num_set[$k] = $srow['recv_num'];
        $kk++;
    }
    for($i=0;$i <count($date);$i++) {
        $reservation = $date[$i]." ".$htime.":".$mtime.":00";
        sendmms(6, $_SESSION['one_member_id'], $send_num, $recv_num_set[$i], $reservation, $title, $txt, $upimage_str, $upimage_str1, $upimage_str2, 'Y', "", "", "", $gd_id, $deny);
    }
                
    for($i=0;$i <count($date);$i++) {
        $query = "insert into Gn_daily_date set gd_id='$gd_id', send_date='$date[$i]', recv_num='$recv_num_set[$i]'";
        mysqli_query($self_con,$query);    
    }
    echo "<script>alert('수정되었습니다.');location='daily_msg_list_service.php';</script>";
}

exit;

?>