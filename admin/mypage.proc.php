<?
include_once "./lib/rlatjd_fun.php";
include_once "./lib/class.image.php";
extract($_REQUEST);
function IntervalDays($CheckIn,$CheckOut){ 
    $CheckInX = explode("-", $CheckIn); 
    $CheckOutX =  explode("-", $CheckOut); 
    $date1 =  mktime(0, 0, 0, $CheckInX[1],$CheckInX[2],$CheckInX[0]); 
    $date2 =  mktime(0, 0, 0, $CheckOutX[1],$CheckOutX[2],$CheckOutX[0]); 
    $interval =($date2 - $date1)/(3600*24); 

    // returns numberofdays 
    return  $interval ; 

} 
if($mode == "land_save") {
    $tempFile = $_FILES[file][tmp_name];
    if($tempFile) {
        $file_arr=explode(".",$_FILES[file][name]);
        $tmp_file_arr=explode("/",$tempFile);
        $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
        
        if(move_uploaded_file($_FILES['file']['tmp_name'], "upload/".$file_name))
        {
            $handle = new Image("upload/".$file_name, 800);
            $handle->resize();        
        }       
    }
    
    $sql="select * from Gn_event where event_name_eng='$pcode'";
    $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
    $event_data = $row=mysqli_fetch_array($result);      
    //$pcode = $event_data['event_name_eng'];
    $sp = $event_data['pcode'];
    //print_r($event_data);
    //echo "pcode=$pcode&sp=$sp&landing_idx=$landing_idx";
    
    $transUrl = str_replace("http", "https", $transUrl);
    $sql = "insert into Gn_landing set title='$title',
                                       description='$description',
                                       content='$ir1',
                                       file='$file_name',
                                       alarm_sms_yn='$alarm_sms_yn',
                                       movie_url='$movie_url',
                                       reply_yn='$reply_yn',
                                       request_yn='$request_yn',
                                       lecture_yn='$lecture_yn',
                                       footer_content='$ir2',
                                       pcode='$pcode',
                                       read_cnt='0',
                                       regdate=NOW(),
                                       short_url='$transUrl',
                                       m_id='$_SESSION[one_member_id]'
                                       
           ";
    $result=mysqli_query($self_con,$sql);
    
    $landing_idx = mysqli_insert_id($self_con);
    
    $transUrl = urlencode("http://kiam.kr/event/event.html?pcode=$pcode&sp=$sp&landing_idx=$landing_idx");
    $transUrl = get_short_url($transUrl);
    $sql = "update Gn_landing set short_url='$transUrl' where landing_idx='$landing_idx'";
    $result=mysqli_query($self_con,$sql);      
    
    //$sql="select uni_id,mem_id from Gn_MMS where mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE() limit 1;";
    //$result=mysqli_query($self_con,$sql);
    //$row=mysqli_fetch_array($result);
    echo "<script>alert('저장되었습니다.');location='mypage_landing_list.php';</script>";
    exit;
} else if($mode == "land_updat") {
    $tempFile = $_FILES[file][tmp_name];
    if($tempFile) {
        $file_arr=explode(".",$_FILES[file][name]);
        $tmp_file_arr=explode("/",$tempFile);
        $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
        
        if(move_uploaded_file($_FILES['file']['tmp_name'], "upload/".$file_name))
        {
            $handle = new Image("upload/".$file_name, 800);
            $handle->resize();
            $addQuery = " file='$file_name',";
        }       
    }
        
    $sql = "update  Gn_landing set title='$title',
                                       description='$description',
                                       content='$ir1',
                                       $addQuery
                                       alarm_sms_yn='$alarm_sms_yn',
                                       movie_url='$movie_url',
                                       reply_yn='$reply_yn',
                                       lecture_yn='$lecture_yn',
                                       request_yn='$request_yn',
                                       footer_content='$ir2',
                                       pcode='$pcode',
                                       regdate=NOW()
                               where   landing_idx='$landing_idx'
           ";
    $result=mysqli_query($self_con,$sql);    
    echo "<script>alert('저장되었습니다.');location='mypage_landing_list.php';</script>";
    exit;   
} else if($mode == "land_updat_status") {
    $sql = "update Gn_landing  set status_yn='$status'
                               where   landing_idx='$landing_idx'
           ";
    $result=mysqli_query($self_con,$sql);            
    echo "<script>alert('삭제되었습니다.');location='mypage_landing_list.php';</script>";
    exit;       
} else if($mode == "land_del") {
    if(is_array($landing_idx) == true) {
        $idx = explode(",".$landing_idx);
        
        $idx_array = "'".implode("','", $idx)."'";
        
        $sql = "delete   from   Gn_landing 
                                   where   landing_idx in ($idx_array) and m_id ='$_SESSION[one_member_id]'
               ";
        $result=mysqli_query($self_con,$sql);    
    } else {
        $sql = "delete   from  Gn_landing 
                                   where   landing_idx='$landing_idx' and m_id ='$_SESSION[one_member_id]'
               ";
        //echo $sql;
        $result=mysqli_query($self_con,$sql);            
    }
    echo "<script>alert('삭제되었습니다.');location='mypage_landing_list.php';</script>";
    exit;       
} else if($mode == "event_check") {
    $sql="select event_name_eng from Gn_event where event_name_eng='$event_name_eng'";
    $result=mysqli_query($self_con,$sql);
    $row=mysqli_fetch_array($result);    
    
    if($row[event_name_eng] == "") {
        echo '{"result":"success","msg":"사용 가능합니다."}'; 
    } else {
        echo '{"result":"fail","msg":"중복된 값이 있습니다."}';
    }
    
    exit;
} else if($mode == "event_del") {

        $sql = "delete   from  Gn_event
                                   where   event_idx='$event_idx' and m_id ='$_SESSION[one_member_id]'
               ";
        //echo $sql;
        $result=mysqli_query($self_con,$sql);            
    echo "<script>alert('삭제되었습니다.');location='mypage_event_list.php';</script>";
    exit;           
} else if($mode == "event_save") {
    
    $sql="select * from Gn_event where event_name_eng='$event_name_eng'";
    $eresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));                                   
    $erow = mysqli_fetch_array($eresult);               
    if($erow[event_idx] != "") {
        echo "<script>alert('중복되는 이벤트 코드가 있습니다.');location='mypage_link_list.php';</script>";
        exit;
    }
    
    $sql="select * from Gn_event where pcode='$pcode'";
    $eresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));                                   
    $erow = mysqli_fetch_array($eresult);               
    if($erow[event_idx] != "") {
        echo "<script>alert('중복되는 신청경로가 있습니다.');location='mypage_link_list.php';</script>";
        exit;
    }   
    $event_info = implode(",", $_POST['event_info']);

    $sql = "insert into Gn_event set event_name_kor='$event_name_kor',
                                     event_name_eng='$event_name_eng',
                                     event_title='$event_title',
                                     event_info='$event_info',
                                     event_desc='$event_desc',
                                     event_sms_desc='$event_sms_desc',
                                     event_type='$event_type',
                                     pcode='$pcode',
                                     mobile='$mobile',
                                     regdate=NOW(), 
                                     ip_addr='$_SERVER[REMOTE_ADDR]',
                                     m_id='$_SESSION[one_member_id]'
                                       
           ";
    $result=mysqli_query($self_con,$sql);
    
    $event_idx = mysqli_insert_id($self_con);
    
    $transUrl = urlencode("http://kiam.kr/event/event.html?pcode=$pcode&sp=$event_name_eng");
    $transUrl = get_short_url($transUrl);
    $sql = "update Gn_event set short_url='$transUrl' where event_idx='$event_idx '";
    $result=mysqli_query($self_con,$sql);          
        
    //$sql="select uni_id,mem_id from Gn_MMS where mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE() limit 1;";
    //$result=mysqli_query($self_con,$sql);
    //$row=mysqli_fetch_array($result);
    echo "<script>alert('저장되었습니다.');location='mypage_link_list.php';</script>";
    exit;
} else if($mode == "event_update") {
    $event_info = implode(",", $_POST['event_info']);
    $sql = "update Gn_event set event_name_kor='$event_name_kor',
                                     event_name_eng='$event_name_eng',
                                     event_title='$event_title',
                                     event_info='$event_info',
                                     event_desc='$event_desc',
                                     event_sms_desc='$event_sms_desc',
                                     event_type='$event_type',
                                     pcode='$pcode',
                                     mobile='$mobile'
                                where event_idx='$event_idx'
           ";
    $result=mysqli_query($self_con,$sql);
    $transUrl = "http://kiam.kr/event/event.html?pcode=$pcode&sp=$event_name_eng";
    $transUrl = get_short_url($transUrl);
    
    $sql = "update Gn_event set short_url='$transUrl' where event_idx='$event_idx '";
    $result=mysqli_query($self_con,$sql);          
    //$sql="select uni_id,mem_id from Gn_MMS where mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE() limit 1;";
    //$result=mysqli_query($self_con,$sql);
    //$row=mysqli_fetch_array($result);
    echo "<script>alert('저장되었습니다.');location='mypage_link_list.php';</script>";
    exit;    
} else if($mode == "sms_save") {

    if($event_idx == "") {
        $sql="select * from Gn_event where event_name_eng='$event_name_eng'";
        $eresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));                                   
        $erow = mysqli_fetch_array($eresult);           
        $event_idx = $erow['event_idx'];
    }
    $sql = "insert into Gn_event_sms_info set event_idx='$event_idx',
                                     event_name_eng='$event_name_eng',
                                     reservation_title='$reservation_title',
                                     reservation_desc='$reservation_desc',
                                     mobile='$mobile',
                                     regdate=NOW(),
                                     m_id='$_SESSION[one_member_id]'
           ";
    $result=mysqli_query($self_con,$sql);
        
    $sms_idx = mysqli_insert_id($self_con);
    //$sql="select uni_id,mem_id from Gn_MMS where mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE() limit 1;";
    //$result=mysqli_query($self_con,$sql);
    //$row=mysqli_fetch_array($result);
    //echo "<script>alert('저장되었습니다.');location='mypage_reservation_list.php';</script>";
    echo "<script>alert('저장되었습니다.');location='mypage_reservation_create.php?sms_idx=$sms_idx';</script>";
    exit;    
} else if($mode == "sms_update") {

    $sql = "update Gn_event_sms_info set event_idx='$event_idx',
                                     event_name_eng='$event_name_eng',
                                     reservation_title='$reservation_title',
                                     reservation_desc='$reservation_desc',
                                     mobile='$mobile',
                                     regdate=NOW(),
                                     m_id='$_SESSION[one_member_id]'
                               Where sms_idx='$sms_idx'
           ";
    $result=mysqli_query($self_con,$sql);
        
    //$sql="select uni_id,mem_id from Gn_MMS where mem_id='$_SESSION[one_member_id]' and send_num='$sendnum' AND substr(reg_date, 1, 10) = CURDATE() limit 1;";
    //$result=mysqli_query($self_con,$sql);
    //$row=mysqli_fetch_array($result);
    echo "<script>alert('저장되었습니다.');location='mypage_link_list.php';</script>";
    exit;
} else if($mode == "step_update") {
    $tempFile = $_FILES[image][tmp_name];
    $tempFile1 = $_FILES[image1][tmp_name];
    $tempFile2 = $_FILES[image2][tmp_name];
    $addQuery = "";
    if($tempFile) {
        $file_arr=explode(".",$_FILES[image][name]);
        $tmp_file_arr=explode("/",$tempFile);
        $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
        
        if(move_uploaded_file($_FILES['image']['tmp_name'], "upload/".$file_name))
        {
            $handle = new Image("upload/".$file_name, 800);
            $handle->resize();
            $addQuery .= " image='$file_name',";
            
            $size = getimagesize("upload/".$file_name);         
            if($size[0] > 640) {
                  $ysize = $size[1] * (640 / $size[0]);
                thumbnail("upload/".$file_name,"adjunct/mms/thum/".$file_name,"",640,$ysize,"");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name;
            } else if($size[1] > 480) {
                $xsize = $size[0] * (480/ $size[1]);
                thumbnail("upload/".$file_name,"adjunct/mms/thum/".$file_name,"",$xsize,480,"");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name;
            } else {
                copy("upload/".$file_name,"adjunct/mms/thum/".$file_name);
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name;
            }               
        }       
    }
    
    if($tempFile1) {
        $file_arr=explode(".",$_FILES[image1][name]);
        $tmp_file_arr=explode("/",$tempFile1);
        $file_name1=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];   
        
        if(move_uploaded_file($_FILES['image1']['tmp_name'], "upload/".$file_name1))
        {
            $handle = new Image("upload/".$file_name1, 800);
            $handle->resize();
            $addQuery .= " image1='$file_name1',";
            
            $size = getimagesize("upload/".$file_name1);            
            if($size[0] > 640) {
                  $ysize = $size[1] * (640 / $size[0]);
                thumbnail("upload/".$file_name1,"adjunct/mms/thum/".$file_name1,"",640,$ysize,"");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name1;
            } else if($size[1] > 480) {
                $xsize = $size[0] * (480/ $size[1]);
                thumbnail("upload/".$file_name1,"adjunct/mms/thum/".$file_name1,"",$xsize,480,"");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name1;
            } else {
                copy("upload/".$file_name1,"adjunct/mms/thum/".$file_name1);
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name1;
            }               
        }       
    }   
    
    if($tempFile2) {
        $file_arr=explode(".",$_FILES[image2][name]);
        $tmp_file_arr=explode("/",$tempFile2);
        $file_name2=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];   
        
        if(move_uploaded_file($_FILES['image2']['tmp_name'], "upload/".$file_name2))
        {
            $handle = new Image("upload/".$file_name2, 800);
            $handle->resize();           
            $addQuery .= " image2='$file_name2',";
            
            $size = getimagesize("upload/".$file_name2);            
            if($size[0] > 640) {
                  $ysize = $size[1] * (640 / $size[0]);
                thumbnail("upload/".$file_name2,"adjunct/mms/thum/".$file_name2,"",640,$ysize,"");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name2;
            } else if($size[1] > 480) {
                $xsize = $size[0] * (480/ $size[1]);
                thumbnail("upload/".$file_name2,"adjunct/mms/thum/".$file_name2,"",$xsize,480,"");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name2;
            } else {
                copy("upload/".$file_name2,"adjunct/mms/thum/".$file_name2);
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name2;
            }               
        }       
    }       
    $send_time = sprintf("%02d",$send_time_hour).":".sprintf("%02d",$send_time_min);
    if($send_deny_msg == "on"){
        $deny = "Y";
    }
    else{
        $deny = "";
    }
    $sql = "update      Gn_event_sms_step_info set title='$title',
                                                    content='$content',
                                                    $addQuery
                                                    send_day='$send_day',
                                                    send_time='$send_time',
                                                    step='$step',
                                                    send_deny='$deny'
                               where   sms_detail_idx='$sms_detail_idx'
           ";
    $result=mysqli_query($self_con,$sql);    
    echo "<script>alert('저장되었습니다.');location='mypage_reservation_create.php?sms_idx=$sms_idx';</script>";
    exit;    
} else if($mode == "step_add") {
    $tempFile = $_FILES[image][tmp_name];
    $tempFile1 = $_FILES[image1][tmp_name];
    $tempFile2 = $_FILES[image2][tmp_name];
    $addQuery = "";
    if($tempFile) {
        $file_arr=explode(".",$_FILES[image][name]);
        $tmp_file_arr=explode("/",$tempFile);
        $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
        
        if(move_uploaded_file($_FILES['image']['tmp_name'], "upload/".$file_name))
        {
            $handle = new Image("upload/".$file_name, 800);
            $handle->resize();
            $addQuery .= " image='$file_name',";
            
            $size = getimagesize("upload/".$file_name);         
            if($size[0] > 640) {
                  $ysize = $size[1] * (640 / $size[0]);
                thumbnail("upload/".$file_name,"adjunct/mms/thum/".$file_name,"",640,$ysize,"");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name;
            } else if($size[1] > 480) {
                $xsize = $size[0] * (480/ $size[1]);
                thumbnail("upload/".$file_name,"adjunct/mms/thum/".$file_name,"",$xsize,480,"");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name;
            } else {
                copy("upload/".$file_name,"adjunct/mms/thum/".$file_name);
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name;
            }               
        }       
    }
    
    if($tempFile1) {
        $file_arr=explode(".",$_FILES[image1][name]);
        $tmp_file_arr=explode("/",$tempFile1);
        $file_name1=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];   
        
        if(move_uploaded_file($_FILES['image1']['tmp_name'], "upload/".$file_name1))
        {
            $handle = new Image("upload/".$file_name1, 800);
            $handle->resize();
            $addQuery .= " image1='$file_name1',";
            
            $size = getimagesize("upload/".$file_name1);            
            if($size[0] > 640) {
                  $ysize = $size[1] * (640 / $size[0]);
                thumbnail("upload/".$file_name1,"adjunct/mms/thum/".$file_name1,"",640,$ysize,"");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name1;
            } else if($size[1] > 480) {
                $xsize = $size[0] * (480/ $size[1]);
                thumbnail("upload/".$file_name1,"adjunct/mms/thum/".$file_name1,"",$xsize,480,"");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name1;
            } else {
                copy("upload/".$file_name1,"adjunct/mms/thum/".$file_name1);
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name1;
            }               
        }       
    }   
    
    if($tempFile2) {
        $file_arr=explode(".",$_FILES[image2][name]);
        $tmp_file_arr=explode("/",$tempFile2);
        $file_name2=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];   
        
        if(move_uploaded_file($_FILES['image2']['tmp_name'], "upload/".$file_name2))
        {
            $handle = new Image("upload/".$file_name2, 800);
            $handle->resize();
            $addQuery .= " image2='$file_name2',";
            
            $size = getimagesize("upload/".$file_name2);            
            if($size[0] > 640) {
                  $ysize = $size[1] * (640 / $size[0]);
                thumbnail("upload/".$file_name2,"adjunct/mms/thum/".$file_name2,"",640,$ysize,"");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name2;
            } else if($size[1] > 480) {
                $xsize = $size[0] * (480/ $size[1]);
                thumbnail("upload/".$file_name2,"adjunct/mms/thum/".$file_name2,"",$xsize,480,"");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name2;
            } else {
                copy("upload/".$file_name2,"adjunct/mms/thum/".$file_name2);
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/".$file_name2;
            }               
        }       
    }           
       
    if($send_deny_msg == "on"){
        $deny = "Y";
    }
    else{
        $deny = "";
    }
       
    $send_time = sprintf("%02d",$send_time_hour).":".sprintf("%02d",$send_time_min);
    $sql = "insert into  Gn_event_sms_step_info set title='$title',
                                                    content='$content',
                                                    $addQuery
                                                    send_day='$send_day',
                                                    send_time='$send_time',
                                                    sms_idx='$sms_idx',
                                                    step='$step',
                                                    send_deny='$deny',
                                                    regdate=NOW()
           ";
    $result=mysqli_query($self_con,$sql);    
    
    echo "<script>alert('저장되었습니다.');location='mypage_reservation_create.php?sms_idx=$sms_idx';</script>";
    exit;     
} else if($mode == "request_add") {
    $m_id= $_SESSION[one_member_id];
    $sql="insert into Gn_event_request set landing_idx='$landing_idx',
                                           event_idx='$event_idx',
                                           event_code='$pcode',
                                           m_id='$m_id',
                                           name='$name',
                                           mobile='$mobile',
                                           email='$email',
                                           sex='$sex',
                                           addr='$addr',
                                           birthday='$birthday',
                                           consult_date='$consult_date',
                                           join_yn='$join_yn',                                         
                                           job='$job',
                                           pcode='$pcode',
                                           sp='$sp',
                                           ip_addr='$ipcheck',
                                           regdate=now()
                    
    ";
    $res1=mysqli_query($self_con,$sql);
    
    
    $request_idx = mysqli_insert_id($self_con);
    $recv_num = $mobile;
    $mem_id = $event_data['m_id'];
    $sql="select * from Gn_event_sms_info where event_idx='$event_idx'";
    $lresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));               
    while($lrow=mysqli_fetch_array($lresult)) {
        $mem_id = $lrow['m_id'];    
        $sms_idx = $lrow['sms_idx'];        
        
        $send_num = $lrow['mobile'];
        
        //알람등록
        $sql="select * from Gn_event_sms_info where sms_idx='$sms_idx'";
        $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));                
        $row=mysqli_fetch_array($result);
        
        $reg = time();
        
        $sql="select * from Gn_event_sms_step_info where sms_idx='$sms_idx'";
        $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        $k = 0;
        while($row=mysqli_fetch_array($result)) {
            // 시간 확인
            $k++;
            $uni_id=$reg.sprintf("%02d",$k);
            $send_day = $row['send_day'];
            $send_time = $row['send_time'];
            
            if($send_time == "") $send_time = "09:30";
            if($send_time == "00:00") $send_time = "09:30";
            if($send_day == "0") 
                $reservation = "";
            else
                $reservation = date("Y-m-d $send_time:00",strtotime("+$send_day days"));

            $jpg = $jpg1 = $jpg2 = '';
            if($row['image'])
                $jpg = "http://www.kiam.kr/adjunct/mms/thum/".$row['image'];
            if($row['image1'])
                $jpg1 = "http://www.kiam.kr/adjunct/mms/thum/".$row['image1'];
            if($row['image2'])
                $jpg2 = "http://www.kiam.kr/adjunct/mms/thum/".$row['image2'];
            
            sendmms(3, $mem_id, $send_num, $recv_num, $reservation, $row[title], $row[content], $jpg, $jpg1, $jpg2, 'Y', $row[sms_idx], $row[sms_detail_idx], $request_idx, "", $row[send_deny]);    
            
            $query = "insert into Gn_MMS_Agree set mem_id='$mem_id',
                                            send_num='$send_num',
                                            recv_num='$recv_num',
                                            content='$row[content]',
                                            title='$row[title]',
                                            jpg='$jpg',
                                            reg_date=NOW(),
                                            reservation='$reservation',
                                            sms_idx='$row[sms_idx]',
                                            sms_detail_idx='$row[sms_detail_idx]',
                                            request_idx='$request_idx'
                                            
                                            
            ";
            //echo $query."<BR>";
            mysqli_query($self_con,$query) or die(mysqli_error($self_con));                              
            
            
        }
    }

    if($join_yn=='Y') {
        //회원가입
          $sql="select mem_id from Gn_Member where mem_id='$mobile'";
          $res=mysqli_query($self_con,$sql);
          $row=mysqli_fetch_array($res);             
          if($row[mem_id] == "") {
              $passwd = substr($mobile,-4);
              $query = "insert into Gn_Member set mem_id='$mobile',
                                                  mem_leb='22',
                                                  web_pwd=password('$passwd'),
                                                  mem_pass=md5('$passwd'),
                                                  mem_name='$name',
                                                  mem_nick='$name',
                                                  mem_phone='$mobile',
                                                  zy='$job',
                                                  first_regist=now() ,
                                                  mem_check=now(),
                                                  mem_add1='$addr',
                                                  mem_email='$email',
                                                  mem_sex='$sex',
                                                  join_ip='$_SERVER[REMOTE_ADDR]'
              ";
              mysqli_query($self_con,$query);
            
            
          }
    }   
    
    echo "<script>alert('신청되었습니다.');location='mypage_request_list.php';</script>";
    exit;        
} else if($mode == "request_update") {
    $sql = "update Gn_event_request set name='$name',
                                        mobile='$mobile',
                                        email='$email',
                                        job='$job',
                                        sp='$sp',
                                       sex='$sex',
                                       addr='$addr',
                                       birthday='$birthday',
                                       consult_date='$consult_date',
                                       join_yn='$join_yn',                                      
                                        edit_id='$_SESSION[one_member_id]',
                                        edit_date=NOW()
                                  where request_idx ='$request_idx'
           ";
    $result=mysqli_query($self_con,$sql);        
    echo "<script>alert('저장되었습니다.');location='mypage_request_list.php';</script>";
    exit;     
} else if($mode == "request_del") {
    $sql = "delete from Gn_event_request where request_idx ='$request_idx' and sp='$org_event_code'";
    $result=mysqli_query($self_con,$sql);            
    echo '{"result":"success"}';
    exit;
} else if($mode=="reservation") {
 
    
    $recv_num = $mobile;
    $mem_id = $event_data['m_id'];
    $sql="select * from Gn_event_sms_info where event_idx='$event_idx'";
    //echo $sql;
    $lresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));               
    $time = 60 - date("i");
    //echo date("H:i:00", strtotime("+$time min"));

    $interval = IntervalDays(date("Y-m-d", time()), $reservation_date);
    
    while($lrow=mysqli_fetch_array($lresult)) {
        $mem_id = $lrow['m_id']; 
        $sms_idx = $lrow['sms_idx'];        
        
        $send_num = $lrow['mobile'];
        
        //알람등록
        $sql="select * from Gn_event_sms_info where sms_idx='$sms_idx'";
        //echo $sql;
        $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));                
        $row=mysqli_fetch_array($result);
        
        $reg = time();
        $sql="select * from Gn_event_sms_step_info where sms_idx='$sms_idx'";
        //echo $sql;
        
        $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        $k = 0;
        while($row=mysqli_fetch_array($result)) {
            // 시간 확인
            $k++;
            $send_day = $row['send_day'];
            $send_time = $row['send_time'];
            
            if($send_time == "") $send_time = "09:30";
            if($send_time == "00:00") $send_time = "09:30";
            if($send_day == "0") 
                $reservation = "";
            else
                $reservation = date("Y-m-d $send_time:00",strtotime("+$send_day days"));
                
            $jpg = $jpg1 = $jpg2 = '';
            if($row['image'])
                $jpg = "http://www.kiam.kr/adjunct/mms/thum/".$row['image'];
            if($row['image1'])
                $jpg1 = "http://www.kiam.kr/adjunct/mms/thum/".$row['image1'];
            if($row['image2'])
                $jpg2 = "http://www.kiam.kr/adjunct/mms/thum/".$row['image2'];
                                    
            for($m = 0; $m < count($mobile);$m++ ) {
                sendmms(3, $mem_id, $send_num, $mobile[$m], $reservation, $row[title], $row[content], $jpg, $jpg1, $jpg2, 'Y', $row[sms_idx], $row[sms_detail_idx], $request_idx[$m], "", $row[send_deny]);
                
                $query = "insert into Gn_MMS_Agree set mem_id='$mem_id',
                                                 send_num='$send_num',
                                                 recv_num='$mobile[$m]',
                                                 content='$row[content]',
                                                 title='$row[title]',
                                                 jpg='$jpg',
                                                 reg_date=NOW(),
                                                 reservation='$reservation',
                                                 sms_idx='$row[sms_idx]',
                                                 sms_detail_idx='$row[sms_detail_idx]',
                                                 request_idx='$request_idx[$m]'
                                                 
                                                 
                ";
                //echo $query."<BR>";
                mysqli_query($self_con,$query) or die(mysqli_error($self_con));              
            }
            
            
        }
    }
    echo "<script>alert('예약되었습니다.');location='mypage_request_list.php';</script>";
    exit;
} else if($mode == "sms_detail_del") {
    $sql = "delete from Gn_event_sms_step_info where sms_detail_idx ='$sms_detail_idx' and sms_idx ='$sms_idx'";
    $result=mysqli_query($self_con,$sql);            
    echo '{"result":"success"}';
    exit;
} else if($mode == "sms_detail_info") {
    $sql = "select * from Gn_event_sms_step_info where sms_detail_idx ='$sms_detail_idx' and sms_idx ='$sms_idx'";
    $result=mysqli_query($self_con,$sql);            
    $row=mysqli_fetch_array($result);
    $info[result] = "success";
    $info[data] = $row;
    echo json_encode($info);
    exit;   
} else if($mode == "reservation_del") {
    if(is_array($landing_idx) == true) {
        $idx = explode(",".$sms_idx);
        
        $idx_array = "'".implode("','", $idx)."'";
        
        $sql = "delete   from   Gn_event_sms_info 
                                   where   sms_idx in ($idx_array) and m_id ='$_SESSION[one_member_id]'
               ";
        $result=mysqli_query($self_con,$sql);    
    } else {
        $sql = "delete   from  Gn_event_sms_info 
                                   where   sms_idx='$sms_idx' and m_id ='$_SESSION[one_member_id]'
               ";
        //echo $sql;
        $result=mysqli_query($self_con,$sql);            
    }
    echo "<script>alert('삭제되었습니다.');location='mypage_landing_list.php';</script>";
    exit;       
} else if($mode == "request_event_add") {
    
    for($i=0; $i < count($request_idx); $i++) {
        $idx =  $request_idx[$i];
        $query = "
        insert into Gn_event_request (m_id, event_idx, event_code, name, mobile, email, job, birthday, sex, addr, consult_date, join_yn, pcode, sp, ip_addr, regdate)
        select m_id, '$event_idx_', '$event_pcode_', name, mobile, email, job, birthday, sex, addr, consult_date, join_yn, '$event_pcode_', '$sp_', ip_addr, now() from Gn_event_request where request_idx='$idx'
        ";
        mysqli_query($self_con,$query);    
    }
    
    
    $event_idx=$_POST['event_idx_'];
    
    $recv_num = $mobile;
    $mem_id = $m_id;
    
    $sql="select * from Gn_event_sms_info where event_idx='$event_idx'";
    //echo $sql;
    $lresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));               
    $time = 60 - date("i");
    //echo date("H:i:00", strtotime("+$time min"));

    $interval = IntervalDays(date("Y-m-d", time()), $reservation_date);
    
    while($lrow=mysqli_fetch_array($lresult)) {
        $mem_id = $lrow['m_id']; 
        $sms_idx = $lrow['sms_idx'];        
        
        $send_num = $lrow['mobile'];
        
        //알람등록
        $sql="select * from Gn_event_sms_info where sms_idx='$sms_idx'";
        //echo $sql;
        $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));                
        $row=mysqli_fetch_array($result);
        
        $reg = time();
        $sql="select * from Gn_event_sms_step_info where sms_idx='$sms_idx'";
        //echo $sql;
        
        $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        $k = 0;
        while($row=mysqli_fetch_array($result)) {
            // 시간 확인
            $k++;
            $send_day = $row['send_day'];
            $send_time = $row['send_time'];
            //echo $row['send_day']."=".$row['send_time'];
            
            if($send_time == "") $send_time = "09:30";
            if($send_time == "00:00") $send_time = "09:30";
            if($send_day == "0") 
                $reservation = "";
            else
                $reservation = date("Y-m-d $send_time:00",strtotime("+$send_day days"));
                
            //echo "<BR>".$send_day."===".$send_time."<BR>";    
            //echo $reservation."<BR>";    
            $jpg = $jpg1 = $jpg2 = '';
            if($row['image'])
                $jpg = "http://www.kiam.kr/adjunct/mms/thum/".$row['image'];
            if($row['image1'])
                $jpg1 = "http://www.kiam.kr/adjunct/mms/thum/".$row['image1'];
            if($row['image2'])
                $jpg2 = "http://www.kiam.kr/adjunct/mms/thum/".$row['image2'];
                                    
            for($m = 0; $m < count($mobile);$m++ ) {
                sendmms(3, $mem_id, $send_num, $mobile[$m], $reservation, $row[title], $row[content], $jpg, $jpg1, $jpg2, 'Y', $row[sms_idx], $row[sms_detail_idx], $request_idx[$m], "", $row[send_deny]);
                
                $query = "insert into Gn_MMS_Agree set mem_id='$mem_id',
                                                 send_num='$send_num',
                                                 recv_num='$mobile[$m]',
                                                 content='$row[content]',
                                                 title='$row[title]',
                                                 jpg='$jpg',
                                                 reg_date=NOW(),
                                                 reservation='$reservation',
                                                 sms_idx='$row[sms_idx]',
                                                 sms_detail_idx='$row[sms_detail_idx]',
                                                 request_idx='$request_idx[$m]'
                                                 
                                                 
                ";
                //echo $query."<BR>";
                mysqli_query($self_con,$query) or die(mysqli_error($self_con));              
            }
            
            
        }
    }
    //exit;
    echo "<script>alert('등록되었습니다.');location='mypage_request_list.php';</script>";    
    exit;
} else if($mode == "address_event_add") {
    
        $query = "
        insert into Gn_event_address (mem_id, event_idx, address_idx, regdate) values
        ('$_SESSION[one_member_id]','$event_idx','$address_idx',now())
        ";    
    
    print_r($_POST);
    exit;
    for($i=0; $i < count($request_idx); $i++) {
        $idx =  $request_idx[$i];
        $query = "
        insert into Gn_event_request (m_id, event_idx, event_code, name, mobile, email, job, birthday, sex, addr, consult_date, join_yn, pcode, sp, ip_addr, regdate)
        select m_id, '$event_idx_', '$sp_', name, mobile, email, job, birthday, sex, addr, consult_date, join_yn, '$sp_', '$event_pcode_', ip_addr, now() from Gn_event_request where request_idx='$idx'
        ";
        mysqli_query($self_con,$query);    
    }
    
    
    $event_idx=$_POST['event_idx_'];
    
    $recv_num = $mobile;
    $mem_id = $m_id;
    
    $sql="select * from Gn_event_sms_info where event_idx='$event_idx'";
    //echo $sql;
    $lresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));               
    $time = 60 - date("i");
    //echo date("H:i:00", strtotime("+$time min"));

    $interval = IntervalDays(date("Y-m-d", time()), $reservation_date);
    
    while($lrow=mysqli_fetch_array($lresult)) {
        $mem_id = $lrow['m_id']; 
        $sms_idx = $lrow['sms_idx'];        
        
        $send_num = $lrow['mobile'];
        
        //알람등록
        $sql="select * from Gn_event_sms_info where sms_idx='$sms_idx'";
        //echo $sql;
        $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));                
        $row=mysqli_fetch_array($result);
        
        $reg = time();
        $sql="select * from Gn_event_sms_step_info where sms_idx='$sms_idx'";
        //echo $sql;
        
        $result=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
        $k = 0;
        while($row=mysqli_fetch_array($result)) {
            // 시간 확인
            $k++;
            $send_day = $row['send_day'];
            $send_time = $row['send_time'];
            
            if($send_time == "") $send_time = "09:30";
            if($send_time == "00:00") $send_time = "09:30";
            if($send_day == "0") 
                $reservation = "";
            else
                $reservation = date("Y-m-d $send_time:00",strtotime("+$send_day days"));
                
            $jpg = $jpg1 = $jpg2 = '';
            if($row['image'])
                $jpg = "http://www.kiam.kr/adjunct/mms/thum/".$row['image'];
            if($row['image1'])
                $jpg1 = "http://www.kiam.kr/adjunct/mms/thum/".$row['image1'];
            if($row['image2'])
                $jpg2 = "http://www.kiam.kr/adjunct/mms/thum/".$row['image2'];
                                    
            for($m = 0; $m < count($mobile);$m++ ) {
                sendmms(3, $mem_id, $send_num, $mobile[$m], $reservation, $row[title], $row[content], $jpg, $jpg1, $jpg2, 'Y', $row[sms_idx], $row[sms_detail_idx], $request_idx[$m], "", $row[send_deny]);
                
                $query = "insert into Gn_MMS_Agree set mem_id='$mem_id',
                                                 send_num='$send_num',
                                                 recv_num='$mobile[$m]',
                                                 content='$row[content]',
                                                 title='$row[title]',
                                                 jpg='$jpg',
                                                 reg_date=NOW(),
                                                 reservation='$reservation',
                                                 sms_idx='$row[sms_idx]',
                                                 sms_detail_idx='$row[sms_detail_idx]',
                                                 request_idx='$request_idx[$m]'
                                                 
                                                 
                ";
                //echo $query."<BR>";
                mysqli_query($self_con,$query) or die(mysqli_error($self_con));              
            }
            
            
        }
    }
    echo "<script>alert('등록되었습니다.');location='mypage_request_list.php';</script>";    
    exit;    
} else if($mode == "lecture_save") {
    $file_arr=explode(".",$_FILES[review_img1][name]);
    $tempFile = $_FILES[review_img1][tmp_name];
    $tmp_file_arr=explode("/",$tempFile);
    $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
    
    if(move_uploaded_file($_FILES['review_img1']['tmp_name'], "upload/lecture/".$file_name))
    {
        $handle = new Image("upload/lecture/".$file_name, 800);
        $handle->resize();
        $addQuery .= " review_img1='$file_name',";
    }   
    
    $file_arr=explode(".",$_FILES[review_img2][name]);
    $tempFile = $_FILES[review_img2][tmp_name];
    $tmp_file_arr=explode("/",$tempFile);
    $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
    
    if(move_uploaded_file($_FILES['review_img2']['tmp_name'], "upload/lecture/".$file_name))
    {
        $handle = new Image("upload/lecture/".$file_name, 800);
        $handle->resize();
        $addQuery .= " review_img2='$file_name',";
    }       
    
    $file_arr=explode(".",$_FILES[review_img3][name]);
    $tempFile = $_FILES[review_img3][tmp_name];
    $tmp_file_arr=explode("/",$tempFile);
    $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
    
    if(move_uploaded_file($_FILES['review_img3']['tmp_name'], "upload/lecture/".$file_name))
    {
        $handle = new Image("upload/lecture/".$file_name, 800);
        $handle->resize();
        $addQuery .= " review_img3='$file_name',";
    }       
    
    $file_arr=explode(".",$_FILES[review_img4][name]);
    $tempFile = $_FILES[review_img4][tmp_name];
    $tmp_file_arr=explode("/",$tempFile);
    $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
    
    if(move_uploaded_file($_FILES['review_img4']['tmp_name'], "upload/lecture/".$file_name))
    {
        $handle = new Image("upload/lecture/".$file_name, 800);
        $handle->resize();
        $addQuery .= " review_img4='$file_name',";
    }       
    
    $file_arr=explode(".",$_FILES[review_img5][name]);
    $tempFile = $_FILES[review_img5][tmp_name];
    $tmp_file_arr=explode("/",$tempFile);
    $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
    
    if(move_uploaded_file($_FILES['review_img5']['tmp_name'], "upload/lecture/".$file_name))
    {
        $handle = new Image("upload/lecture/".$file_name, 800);
        $handle->resize();
        $addQuery .= " review_img5='$file_name',";
    }           
            
    $lecture_day = implode(",",$lecture_day);
    $sql = "insert into Gn_lecture set event_idx='$event_idx',
                                       event_code='$event_code',
                                       category='$category',
                                       start_date='$start_date',
                                       end_date='$end_date',
                                       lecture_day='$lecture_day',
                                       lecture_start_time='$lecture_start_time',
                                       lecture_end_time='$lecture_end_time',
                                       lecture_info='$lecture_info',
                                       lecture_url='$lecture_url',
                                       instructor='$instructor',
                                       target='$target',
                                       area='$area',
                                       max_num='$max_num',
                                       $addQuery
                                       review_title1='$review_title1',
                                       review_title2='$review_title2',
                                       review_title3='$review_title3',
                                       review_title4='$review_title4',
                                       review_title5='$review_title5',                                     
                                       fee='$fee',
                                       mem_id='$_SESSION[one_member_id]',
                                       status='N',
                                       regdate=NOW()
           ";
    $result=mysqli_query($self_con,$sql);    
    echo "<script>alert('등록되었습니다.');location='mypage_lecture_list.php';</script>";    
    exit;       
} else if($mode == "lecture_save_event") {
    $lecture_day = implode(",",$lecture_day);
    $sql = "insert into Gn_lecture set event_idx='$event_idx',
                                       event_code='$event_code',
                                       category='$category',
                                       start_date='$start_date',
                                       end_date='$end_date',
                                       lecture_day='$lecture_day',
                                       lecture_start_time='$lecture_start_time',
                                       lecture_end_time='$lecture_end_time',
                                       lecture_url='$lecture_url',
                                       lecture_info='$lecture_info',
                                       instructor='$instructor',
                                       target='$target',
                                       area='$area',
                                       max_num='$max_num',
                                       fee='$fee',
                                       mem_id='$_SESSION[one_member_id]',
                                       status='N',
                                       regdate=NOW()
           ";
    $result=mysqli_query($self_con,$sql);    
    echo "<script>alert('등록되었습니다.');location='/event/event.html?pcode=$pcode&sp=$sp&landing_idx=$landing_idx';</script>";    
    exit;    
} else if($mode == "lecture_update") {

    $file_arr=explode(".",$_FILES[review_img1][name]);
    $tempFile = $_FILES[review_img1][tmp_name];
    $tmp_file_arr=explode("/",$tempFile);
    $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
    
    
    if($review_img1_del == "Y" && $_FILES[review_img1][name] == "") $addQuery .= "review_img1='',";
    
    if(move_uploaded_file($_FILES['review_img1']['tmp_name'], "upload/lecture/".$file_name))
    {
        $handle = new Image("upload/lecture/".$file_name, 800);
        $handle->resize();
        $addQuery .= " review_img1='$file_name',";
    }   
    
    $file_arr=explode(".",$_FILES[review_img2][name]);
    $tempFile = $_FILES[review_img2][tmp_name];
    $tmp_file_arr=explode("/",$tempFile);
    $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
    
    if($review_img2_del == "Y" && $_FILES[review_img2][name] == "") $addQuery .= "review_img2='',";
    
    if(move_uploaded_file($_FILES['review_img2']['tmp_name'], "upload/lecture/".$file_name))
    {
        $handle = new Image("upload/lecture/".$file_name, 800);
        $handle->resize();
        $addQuery .= " review_img2='$file_name',";
    }       
    
    $file_arr=explode(".",$_FILES[review_img3][name]);
    $tempFile = $_FILES[review_img3][tmp_name];
    $tmp_file_arr=explode("/",$tempFile);
    $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
    
    if($review_img3_del == "Y" && $_FILES[review_img3][name] == "") $addQuery .= "review_img3='',";
    
    if(move_uploaded_file($_FILES['review_img3']['tmp_name'], "upload/lecture/".$file_name))
    {
        $handle = new Image("upload/lecture/".$file_name, 800);
        $handle->resize();
        $addQuery .= " review_img3='$file_name',";
    }       
    
    $file_arr=explode(".",$_FILES[review_img4][name]);
    $tempFile = $_FILES[review_img4][tmp_name];
    $tmp_file_arr=explode("/",$tempFile);
    $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
    
    if($review_img4_del == "Y" && $_FILES[review_img4][name] == "") $addQuery .= "review_img4='',";
    
    if(move_uploaded_file($_FILES['review_img4']['tmp_name'], "upload/lecture/".$file_name))
    {
        $handle = new Image("upload/lecture/".$file_name, 800);
        $handle->resize();
        $addQuery .= " review_img4='$file_name',";
    }       
    
    $file_arr=explode(".",$_FILES[review_img5][name]);
    $tempFile = $_FILES[review_img5][tmp_name];
    $tmp_file_arr=explode("/",$tempFile);
    $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
    
    if($review_img5_del == "Y" && $_FILES[review_img5][name] == "") $addQuery .= "review_img5='',";
    
    if(move_uploaded_file($_FILES['review_img5']['tmp_name'], "upload/lecture/".$file_name))
    {
        $handle = new Image("upload/lecture/".$file_name, 800);
        $handle->resize();
        $addQuery .= " review_img5='$file_name',";
    }           
        
            
    $lecture_day = implode(",",$lecture_day);
    $sql = "update  Gn_lecture set event_idx='$event_idx',
                                       event_code='$event_code',
                                       category='$category',
                                       start_date='$start_date',
                                       end_date='$end_date',
                                       lecture_day='$lecture_day',
                                       lecture_start_time='$lecture_start_time',
                                       lecture_end_time='$lecture_end_time',
                                       lecture_url='$lecture_url',
                                       lecture_info='$lecture_info',
                                       instructor='$instructor',
                                       target='$target',
                                       area='$area',
                                       max_num='$max_num',
                                       $addQuery
                                       review_title1='$review_title1',
                                       review_title2='$review_title2',
                                       review_title3='$review_title3',
                                       review_title4='$review_title4',
                                       review_title5='$review_title5',
                                       fee='$fee'
                                where  lecture_id='$lecture_id'
                                       
           ";
    $result=mysqli_query($self_con,$sql);  
    echo "<script>alert('수정되었습니다.');location='mypage_lecture_list.php';</script>";    
    exit;    
} else if($mode == "lecture_del") {
    $sql = "delete from  Gn_lecture 
                                where  lecture_id='$lecture_id'
                                       
           ";
    $result=mysqli_query($self_con,$sql);  
    echo "<script>alert('삭제되었습니다.');location='mypage_lecture_list.php';</script>";    
    exit;   
} else if($mode == "review_save") {
    $file_arr=explode(".",$_FILES[image][name]);
    $tempFile = $_FILES[image][tmp_name];
    $tmp_file_arr=explode("/",$tempFile);
    $file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    
    
    if(move_uploaded_file($_FILES['image']['tmp_name'], "upload/review/".$file_name))
    {
        $handle = new Image("upload/review/".$file_name, 800);
        $handle->resize();
        $addQuery .= " image='$file_name',";
    }   
        
    $lecture_day = implode(",",$lecture_day);
    $sql = "insert into Gn_review set lecture_id='$lecture_id',
                                       score='$score',
                                       content='$content',
                                       profile='$profile',
                                       mem_id='$_SESSION[one_member_id]',
                                       status='N',
                                       $addQuery
                                       regdate=NOW()
           ";
    $result=mysqli_query($self_con,$sql);    
    echo "<script>alert('등록되었습니다.');location='mypage_review_list.php';</script>";    
    exit;                   
} else if($mode == "review_update") {
    $lecture_day = implode(",",$lecture_day);
    $sql = "update Gn_review set  
                                       score='$score',
                                       content='$content',
                                       profile='$profile' 
                                where  review_id='$review_id'
                                       
           ";
    $result=mysqli_query($self_con,$sql);  
    echo "<script>alert('수정되었습니다.');location='mypage_review_list.php';</script>";    
    exit;  
} else if($mode == "daily_del") {
    if($gd_id > 0)  {
        $sql = "delete from  Gn_daily 
                                    where  gd_id='$gd_id'
                                           
               ";
        $result=mysqli_query($self_con,$sql);      
        
        $query = "delete from Gn_daily_date where gd_id='$gd_id';";
        mysqli_query($self_con,$query);    
            
        $query = "delete from Gn_MMS where gd_id='$gd_id' ";
        mysqli_query($self_con,$query);        
    }
    
    echo "<script>alert('삭제되었습니다.');location='daily_list.php';</script>";    
    exit;      
} else if($mode == "daily_update") {
        $reg = time();
        $uni_id=$reg.sprintf("%02d",$k);    
        $date = explode(",", $send_date);
        $end_date = max($date);
        $start_date = min($date);
        
        $query = "
        update Gn_daily set mem_id='$_SESSION[one_member_id]', 
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
        //print_r($recv_num_set);
        
        
        for($i=0;$i <count($date);$i++) {
            $reservation = $date[$i]." ".$htime.":".$mtime.":00";
            sendmms(3, $_SESSION[one_member_id], $send_num, $recv_num_set[$i], $reservation, $title, $txt, $upimage_str, $upimage_str1, $upimage_str2, 'Y', "", "", "", $gd_id);
        }
                    
        for($i=0;$i <count($date);$i++) {
            $query = "insert into Gn_daily_date set gd_id='$gd_id',
                                                    send_date='$date[$i]',
                                                    recv_num='$recv_num_set[$i]'";
            mysqli_query($self_con,$query);    
            //echo $query."<BR>";;
        }
                
                
    echo "<script>alert('수정되었습니다.');location='daily_list.php';</script>";    
    exit;  
} else if($mode == "daily_save") {
        $reg = time();
        $uni_id=$reg.sprintf("%02d",$k);    
        $date = explode(",", $send_date);
        $end_date = max($date);
        $start_date = min($date);
        
        $query = "
        insert into Gn_daily set mem_id='$_SESSION[one_member_id]', 
                                 iam='$iam',
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
                                 reg_date=NOW(),
                                 htime='$htime',
                                 mtime='$mtime'
                                 ;
                                 
        ";
        mysqli_query($self_con,$query);    
        //echo $query."<BR>";
        $gd_id = mysqli_insert_id($self_con);
        

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
        //print_r($recv_num_set);
        
        
        for($i=0;$i <count($date);$i++) {
            $reservation = $date[$i]." ".$htime.":".$mtime.":00";
            sendmms(3, $_SESSION[one_member_id], $send_num, $recv_num_set[$i], $reservation, $title, $txt, $upimage_str, $upimage_str1, $upimage_str2, 'Y', "", "", "", $gd_id);
        }
                    
        for($i=0;$i <count($date);$i++) {
            $query = "insert into Gn_daily_date set gd_id='$gd_id',
                                                    send_date='$date[$i]',
                                                    recv_num='$recv_num_set[$i]'";
            mysqli_query($self_con,$query);    
            //echo $query."<BR>";;
        }
                
        
        echo "<script>alert('등록되었습니다.');location='daily_list.php';</script>";    
        exit;
        
}


















//코칭정보쓰기
else if($mode == "new_coaching_info") {

// print_r($_POST);
//     exit;
 


$coach_id = $_POST['coach_id'];
$coach_mem_code = $_POST['coach_mem_code'];

$arr = split("\_", $_POST['coty_id']);


$coty_id = $arr[0];
$coty_mem_code = $arr[2];


$coaching_title = $_POST['coaching_title'];
$coaching_content = $_POST['coaching_content'];
$coaching_date = date($_POST['start_date']);
$coaching_time  = $_POST['coaching_time'];

$coach_value = $_POST['coach_value'];
$coach_comment = $_POST['coach_comment'];
$home_work = $_POST['home_work'];


$search_text = $coty_id.$coaching_title.$coaching_content.$home_work.$coach_comment;
$agreement = 0;

//업데이트일때 아이디 
$update_coaching_id = $_POST['update_coaching_id'];



//왼료 된 코칭인가 검사
// $sql="select count(coach_id) as cnt from gn_coaching_info where coty_id='".$coty_id."' and coach_id='".$coach_id."' and coaching_status = 2";

// $result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
// $row=mysqli_fetch_array($result);
// $finsihed_coaching_count=$row[cnt];
// if($finsihed_coaching_count > 0){
//    echo "<script>alert('[추가오류]  완료된 코칭입니다!');location='mypage_coaching_list.php';</script>";
//     exit;
// }



//코칭회차 결정, 코칭타임 총합
$sql="select SUM(coaching_time) as time_sum,count(coach_id) as cnt from gn_coaching_info where coty_id='".$coty_id."' and coach_id='".$coach_id."'";

$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$row=mysqli_fetch_array($result);
$coaching_turn=$row[cnt] + 1;
$coaching_time_sum=$row[time_sum];

//echo "<script>alert('".$coaching_time_sum." coaching time.');</script>";



//이 계열의 과제의 총 분수
$sql="select cont_time from gn_coaching_apply where coty_id='".$coty_id."' ";
$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$row=mysqli_fetch_array($result);
$coty_cont_time = $row[cont_time] * 60; //계약 분


if(($coaching_time_sum + $coaching_time) > $coty_cont_time){
    // echo "<script>alert('총함 : ".($coaching_time_sum + $coaching_time)."');</script>";
    // echo "<script>alert('계약분 : ".($coty_cont_time)."');</script>";
    echo "<script>alert('코칭시간을 정확히 입력하세요.');window.history.back(); </script>";
exit;
}
    // echo "<script>alert('코칭타임 성공');</script>";
    // exit;









//코칭회차와 해당하 코티의 유효기간과 비교 
// 같으면 완성된 코칭

$coaching_status = 1; //진행중

$sql="select count(coach_id) as cnt from gn_coaching_apply where coty_id='".$coty_id."' and cont_term='".$coaching_turn."'";
$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$row=mysqli_fetch_array($result);
$fishined_coaching_count = $row[cnt];



// echo "<script>alert('".$coaching_turn." coaching turn.');</script>";

$coaching_file = "";

if ($_FILES['coaching_file']['name']!="") {

    $target_path = "/cauching_uploads/";  
    $target_path = $target_path.basename( $coach_id."_".$_FILES['coaching_file']['name']);   
    $coaching_file = $target_path;

    $target_path = $_SERVER['DOCUMENT_ROOT'].$target_path;
      
    if(move_uploaded_file($_FILES['coaching_file']['tmp_name'], $target_path)) { 
        $handle = new Image($target_path, 800);
        $handle->resize();
        echo "<script>alert('".$target_path." File uploaded successfully!');</script>";
    } else{
        echo "<script>alert('".$target_path." Sorry, file not uploaded, please try again!');</script>";
    } 
}




if($update_coaching_id){
    $sql="update gn_coaching_info set coty_id='$coty_id',
                                    coty_mem_code='$coty_mem_code',
                                    coaching_date='$coaching_date',
                                    coaching_time='$coaching_time',
                                    coaching_title='$coaching_title',
                                    coaching_content='$coaching_content',
                                    coaching_file='$coaching_file',
                                    coach_value='$coach_value',
                                    home_work='$home_work',
                                    coach_comment='$coach_comment',
                                    search_text='$search_text'
                                     where coaching_id = $update_coaching_id
                                    ";
}else{
    //크리트
    $sql="insert into gn_coaching_info set coty_id='$coty_id',
                                    coty_mem_code='$coty_mem_code',

                                    coach_id='$coach_id',
                                    coach_mem_code='$coach_mem_code',
                                    
                                    coaching_turn='$coaching_turn',

                                    coaching_date='$coaching_date',
                                    coaching_time='$coaching_time',
                                    coaching_title='$coaching_title',
                                    coaching_content='$coaching_content',
                                    coaching_file='$coaching_file',
                                    coach_value='$coach_value',
                                    home_work='$home_work',
                                    reg_date=now(),
                                    coach_comment='$coach_comment',
                                    search_text='$search_text',
                                    coaching_status = '$coaching_status',
                                    past_time_sum='$coaching_time_sum',
                                    agree='0'";
}

//     //coaching_turn 코칭회차 계산논리 확정                                    
//     //coach_value 코치자기평가                                    
//     //coach_view 이용안함                                     
//     //ready-del 이용안함    
//     //coach_comment 추가 //코치의견                                 
//     //coach_rating 추가  // 코치평가                              
//     //    coaching_find 코칭정보조회,이용안함                                

// echo $sql;
// exit;

    $result=mysqli_query($self_con,$sql);
    $coaching_info_idx = mysqli_insert_id($self_con);

    // //완성된 코칭이라면 
    // if($fishined_coaching_count==1){
    //     $coaching_status = 2;
    //     $sql_update="update gn_coaching_info set coaching_status=2 where coty_id='".$coty_id."' and coach_id='".$coach_id."'";
    //     mysqli_query($self_con,$sql_update) or die(mysqli_error($self_con));
    //     echo "<script>alert('".$coaching_turn." coaching turn. Finished');</script>";
    // }


    echo "<script>location='mypage_coaching_list.php';</script>";
    exit; 
        
}
else if($mode == "coaching_info_del"){


    $coaching_id = $_POST['coaching_id'];
    $sql = "delete from gn_coaching_info where coaching_id=$coaching_id";
    
    echo $sql;
    $result=mysqli_query($self_con,$sql);  



    // /echo "<script>alert('삭제되었습니다.');location='mypage_coaching_list.php';</script>";    
    exit;   
}
else if($mode == "coaching_info_coty_comment"){


    $coty_value = $_POST['coty_value'];
    $coty_comment = $_POST['coty_comment'];
    $coaching_id = $_POST['coaching_id'];
     $sql="update gn_coaching_info set coty_value='$coty_value',
                                    coty_comment='$coty_comment',
                                    agree=1,coaching_status=2 
                                     where coaching_id = $coaching_id
                                    ";
    
    echo $sql;
    $result=mysqli_query($self_con,$sql);  
    exit;   
}
else if($mode == "coaching_info_site_comment"){


    $site_value = $_POST['site_value'];
    $site_comment = $_POST['site_comment'];
    $coaching_id = $_POST['coaching_id'];
     $sql="update gn_coaching_info set site_value='$site_value',
                                    site_comment='$site_comment'
                                     where coaching_id = $coaching_id
                                    ";
    
    echo $sql;
    $result=mysqli_query($self_con,$sql);  
    exit;   
}



//코치신청
else if($mode == "req_coach") {

    $sql="select * from Gn_Member  where mem_id='$_SESSION[one_member_id]' ";
    $result_num=mysqli_query($self_con,$sql);
    $data=mysqli_fetch_array($result_num);   

    $sql="insert into gn_coach_apply set mem_code='".$data['mem_code']."',  reg_date=now() , agree= 0, coach_type=0";


    echo $sql;
    $result=mysqli_query($self_con,$sql);
        
    $coach_apply_idx = mysqli_insert_id($self_con);
    echo "<script>alert('저장되었습니다.');location='mypage_coaching_list.php';</script>";
    exit; 
        
}




//코티가 코칭신청 (수강신청)
else if($mode == "req_coaching") {

    $cont_term = $_POST['cont_term'];
    $cont_time = $_POST['cont_time'];
    // $start_datetime ="2000-01-01";
    // $end_datetime = "2000-01-01";
    $coaching_price = $_POST['coaching_price'];



    $sql="select * from Gn_Member  where mem_id='$_SESSION[one_member_id]' ";
    $result_num=mysqli_query($self_con,$sql);
    $data=mysqli_fetch_array($result_num);   

    $sql="insert into gn_coaching_apply set mem_code='".$data['mem_code']."' , reg_date=now() ,cont_term='".$cont_term."' ,cont_time='".$cont_time."' ,coaching_price='".$coaching_price."', agree =0 ";

    $result=mysqli_query($self_con,$sql);
        
    $coach_apply_idx = mysqli_insert_id($self_con);
    echo "<script>alert('저장되었습니다.');location='mypage_coaching_list.php';</script>";
    exit; 
        
}

?>