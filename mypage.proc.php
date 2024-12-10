<?
include_once "./lib/rlatjd_fun.php";
include_once "./lib/class.image.php";
extract($_REQUEST);
function IntervalDays($CheckIn, $CheckOut)
{
    $CheckInX = explode("-", $CheckIn);
    $CheckOutX =  explode("-", $CheckOut);
    $date1 =  mktime(0, 0, 0, $CheckInX[1], $CheckInX[2], $CheckInX[0]);
    $date2 =  mktime(0, 0, 0, $CheckOutX[1], $CheckOutX[2], $CheckOutX[0]);
    $interval = ($date2 - $date1) / (3600 * 24);
    return  $interval;
}
if ($mode == "land_save") {
    //업로드링크를 설정한다.
    $uploadLink = "/naver_editor/upload_editor/";
    $uploadDir = ".$uploadLink";
    if ($_FILES['file']) {
        //업로드폴더가 없다면 폴더를 만든다.
        $folder = $uploadDir . date("Y-m-d") . "/";
        if (!file_exists($folder))
            mkdir($folder, 0777, true);

        $tmp_name = str_replace(" ", "", basename($_FILES['file']['name']));
        $tmp_name = str_replace("'", "", $tmp_name);
        $tmp_name = str_replace('"', "", $tmp_name);

        $upload_filename = date("Ymds") . "_" . $tmp_name;
        $upload_file = $folder . $upload_filename;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_file)) {
            $handle = new Image($upload_file, 800);
            $handle->resize();
            uploadFTP($upload_file);
            $uploadLink = $uploadLink . date("Y-m-d") . "/" . $upload_filename;
            $addQuery = " file='{$uploadLink}',";
        }
    }

    if ($_FILES['thumbnail']) {
        $folder = $uploadDir . date("Y-m-d") . "/";
        if (!file_exists($folder))
            mkdir($folder, 0777, true);

        $ext = "." . end((explode(".", $_FILES["thumbnail"]["name"])));
        $upload_filename = "thumb_" . time() . rand(10000, 99999) . $ext;

        $upload_file = $folder . $upload_filename;
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_file)) {
            $handle = new Image($upload_file, 800);
            $handle->resize();
            uploadFTP($upload_file);
            $uploadLink = $uploadLink . date("Y-m-d") . "/" . $upload_filename;
            $thumbQuery = " img_thumb='{$uploadLink}',";
        }
    }

    $content  = str_replace("'", "\"", $ir1);
    $sql = "select * from Gn_event use index(event_name_eng) where event_name_eng='{$pcode}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $event_data = $row = mysqli_fetch_array($result);
    $sp = $event_data['pcode'];
    $transUrl = str_replace("http:", "https:", $transUrl);
    $sql = "insert into Gn_landing set title='{$title}',
                                       description='{$description}',
                                       content='{$content}',
                                       $addQuery
                                       alarm_sms_yn='{$alarm_sms_yn}',
                                       movie_url='{$movie_url}',
                                       reply_yn='{$reply_yn}',
                                       request_yn='{$request_yn}',
                                       lecture_yn='{$lecture_yn}',
                                       footer_content='{$ir2}',
                                       pcode='{$pcode}',
                                       read_cnt='0',
                                       regdate=NOW(),
                                       short_url='{$transUrl}',
                                       $thumbQuery
                                       m_id='{$_SESSION['one_member_id']}'";
    $result = mysqli_query($self_con, $sql);
    $landing_idx = mysqli_insert_id($self_con);
    $transUrl = "https://" . $HTTP_HOST . "/event/event.html?pcode=$pcode&sp=$sp&landing_idx=$landing_idx";
    $transUrl = get_short_url($transUrl);
    $sql = "update Gn_landing set short_url='{$transUrl}' where landing_idx='{$landing_idx}'";
    $result = mysqli_query($self_con, $sql);
    echo "<script>location='mypage_landing_list.php';</script>";
    exit;
} else if ($mode == "land_updat") {
    //업로드링크를 설정한다.
    $uploadLink = "/naver_editor/upload_editor/";
    $uploadDir = ".$uploadLink";
    if ($_FILES['file']['name']) {
        //업로드폴더가 없다면 폴더를 만든다.
        $folder = $uploadDir . date("Y-m-d") . "/";
        if (!file_exists($folder))
            mkdir($folder, 0777, true);

        $tmp_name = str_replace(" ", "", basename($_FILES['file']['name']));
        $tmp_name = str_replace("'", "", $tmp_name);
        $tmp_name = str_replace('"', "", $tmp_name);

        $upload_filename = time() . rand(10000, 99999) . "_" . $tmp_name;

        $upload_file = $folder . $upload_filename;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $upload_file)) {
            $handle = new Image($upload_file, 800);
            $handle->resize();
            uploadFTP($upload_file);
            $fileLink = $uploadLink . date("Y-m-d") . "/" . $upload_filename;
            $addQuery = " file='{$fileLink}',";
        }
    } else if ($file_name == "") {
        $addQuery = " file='',";
    }

    if ($_FILES['thumbnail']['name']) {
        $folder = $uploadDir . date("Y-m-d") . "/";
        if (!file_exists($folder))
            mkdir($folder, 0777, true);

        $ext = "." . end((explode(".", $_FILES["thumbnail"]["name"])));
        $upload_filename = "thumb_" . time() . rand(10000, 99999) . $ext;

        $upload_file = $folder . $upload_filename;
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $upload_file)) {
            $handle = new Image($upload_file, 800);
            $handle->resize();
            uploadFTP($upload_file);
            $fileLink = $uploadLink . date("Y-m-d") . "/" . $upload_filename;
            $thumbQuery = " img_thumb='{$fileLink}',";
        }
    } else if ($thumb_name == "") {
        $thumbQuery = " img_thumb='',";
    }
    $add = "";
    if ($pcode) {
        $sql = "select * from Gn_event where event_name_eng='{$pcode}'";
        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $event_data = $row = mysqli_fetch_array($result);
        $sp = $event_data['pcode'];
        $transUrl = "https://" . $HTTP_HOST . "/event/event.html?pcode=$pcode&sp=$sp&landing_idx=$landing_idx";
        $transUrl = get_short_url($transUrl);
        $add = "short_url='{$transUrl}',";
    }

    $content  = str_replace("'", "\"", $ir1);
    $sql = "update Gn_landing set title='{$title}',
                                       description='{$description}',
                                       content='{$content}',
                                       $addQuery
                                       alarm_sms_yn='{$alarm_sms_yn}',
                                       movie_url='{$movie_url}',
                                       reply_yn='{$reply_yn}',
                                       lecture_yn='{$lecture_yn}',
                                       request_yn='{$request_yn}',
                                       footer_content='{$ir2}',
                                       pcode='{$pcode}',
                                       $add
                                       $thumbQuery
                                       regdate=NOW()
                               where   landing_idx='{$landing_idx}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    echo "<script>location='mypage_landing_list.php';</script>";
    exit;
} else if ($mode == "land_updat_status") {
    $sql = "update Gn_landing set status_yn='{$status}' where landing_idx='{$landing_idx}'";
    $result = mysqli_query($self_con, $sql);
    echo "<script>alert('삭제되었습니다.');location='mypage_landing_list.php';</script>";
    exit;
} else if ($mode == "land_del") {
    if (is_array($landing_idx) == true) {
        $idx = explode(",", $landing_idx);

        $idx_array = "'" . implode("','", $idx) . "'";

        $sql = "delete from Gn_landing where landing_idx in ($idx_array) and m_id ='{$_SESSION['one_member_id']}'";
        $result = mysqli_query($self_con, $sql);
    } else {
        $sql = "delete from Gn_landing where landing_idx='{$landing_idx}' and m_id ='{$_SESSION['one_member_id']}'";
        $result = mysqli_query($self_con, $sql);
    }
    echo "<script>alert('삭제되었습니다.');location='mypage_landing_list.php';</script>";
    exit;
} else if ($mode == "event_check") {
    $sql = "select event_name_eng from Gn_event where event_name_eng='{$event_name_eng}'";
    $result = mysqli_query($self_con, $sql);
    $row = mysqli_fetch_array($result);

    if ($row['event_name_eng'] == "") {
        echo '{"result":"success","msg":"사용 가능합니다."}';
    } else {
        echo '{"result":"fail","msg":"중복된 값이 있습니다."}';
    }

    exit;
} else if ($mode == "event_del") {
    $sql = "delete   from  Gn_event  where   event_idx='{$event_idx}' and m_id ='{$_SESSION['one_member_id']}'";
    //echo $sql;
    $result = mysqli_query($self_con, $sql);
    echo "<script>alert('삭제되었습니다.');location='mypage_event_list.php';</script>";
    exit;
} else if ($mode == "event_save") {
    /*$sql="select * from Gn_event where event_name_eng='{$event_name_eng}'";
    $eresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));                                   
    $erow = mysqli_fetch_array($eresult);               
    if($erow['event_idx'] != "") {
        echo "<script>alert('중복되는 이벤트 코드가 있습니다.');location='mypage_link_list.php';</script>";
        exit;
    }
    $sql="select * from Gn_event where pcode='{$pcode}'";
    $eresult=mysqli_query($self_con,$sql) or die(mysqli_error($self_con));                                   
    $erow = mysqli_fetch_array($eresult);               
    if($erow['event_idx'] != "") {
        echo "<script>alert('중복되는 신청경로가 있습니다.');location='mypage_link_list.php';</script>";
        exit;
    }*/
    $event_info = implode(",", $_POST['event_info']);
    $event_name_eng = $pcode = generateRandomString(10);
    $sql = "insert into Gn_event set event_name_kor='{$event_name_kor}',
                                     event_name_eng='{$event_name_eng}',
                                     event_title='{$event_title}',
                                     event_info='{$event_info}',
                                     event_desc='{$event_desc}',
                                     event_sms_desc='{$event_sms_desc}',
                                     event_type='{$event_type}',
                                     pcode='{$pcode}',
                                     mobile='{$mobile}',
                                     regdate=NOW(), 
                                     ip_addr='{$_SERVER['REMOTE_ADDR']}',
                                     m_id='{$_SESSION['one_member_id']}',
                                     event_req_link='{$event_req_link}',
                                     sms_idx1='{$step_idx1}',
                                     sms_idx2='{$step_idx2}',
                                     sms_idx3='{$step_idx3}',
                                     stop_event_idx='{$stop_event_idx}'";
    $result = mysqli_query($self_con, $sql);
    $event_idx = mysqli_insert_id($self_con);

    $transUrl = "https://" . $HTTP_HOST . "/event/event.html?pcode=$pcode&sp=$event_name_eng";
    $transUrl = get_short_url($transUrl);
    $sql = "update Gn_event set short_url='{$transUrl}' where event_idx='$event_idx '";
    $result = mysqli_query($self_con, $sql);
    echo "<script>location='mypage_link_list.php';</script>";
    exit;
} else if ($mode == "event_update") {
    $event_info = implode(",", $_POST['event_info']);
    $sql = "update Gn_event set event_name_kor='{$event_name_kor}',
                                event_title='{$event_title}',
                                event_info='{$event_info}',
                                event_desc='{$event_desc}',
                                event_sms_desc='{$event_sms_desc}',
                                event_type='{$event_type}',
                                pcode='{$pcode}',
                                mobile='{$mobile}',
                                event_req_link='{$event_req_link}',
                                sms_idx1='{$step_idx1}',
                                sms_idx2='{$step_idx2}',
                                sms_idx3='{$step_idx3}',
                                stop_event_idx='{$stop_event_idx}'
                        where event_idx='{$event_idx}'";
    $result = mysqli_query($self_con, $sql);
    $transUrl = "https://" . $HTTP_HOST . "/event/event.html?pcode=$pcode&sp=$event_name_eng";
    $transUrl = get_short_url($transUrl);
    $sql = "update Gn_event set short_url='{$transUrl}' where event_idx='$event_idx '";
    $result = mysqli_query($self_con, $sql);
    echo "<script>location='mypage_link_list.php';</script>";
    exit;
} else if ($mode == "sms_save") {
    if ($event_idx == "") {
        $sql = "select * from Gn_event where event_name_eng='{$event_name_eng}'";
        $eresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $erow = mysqli_fetch_array($eresult);
        $event_idx = $erow['event_idx'];
    }
    if ($event_idx == "")
        $event_idx = 0;
    $sql = "insert into Gn_event_sms_info set event_idx='{$event_idx}',
                                     event_name_eng='{$event_name_eng}',
                                     reservation_title='{$reservation_title}',
                                     reservation_desc='{$reservation_desc}',
                                     mobile='{$mobile}',
                                     regdate=NOW(),
                                     m_id='{$_SESSION['one_member_id']}'";
    $result = mysqli_query($self_con, $sql);

    $sms_idx = mysqli_insert_id($self_con);

    if ($mb_id_copy != "") {
        $sql_step_info = "INSERT INTO Gn_event_sms_step_info(sms_idx, step, send_day, send_time, title, content, image, image1, image2, regdate, send_deny) 
                                    (SELECT {$sms_idx}, step, send_day, send_time, title, content, image, image1, image2, now(), send_deny FROM Gn_event_sms_step_info WHERE sms_idx={$ori_sms_idx})";
        mysqli_query($self_con, $sql_step_info);
    }

    echo "<script>location='mypage_reservation_create.php?sms_idx=$sms_idx';</script>";
    exit;
} else if ($mode == "sms_update") {

    $sql = "update Gn_event_sms_info set event_idx='{$event_idx}',
                                     event_name_eng='{$event_name_eng}',
                                     reservation_title='{$reservation_title}',
                                     reservation_desc='{$reservation_desc}',
                                     mobile='{$mobile}',
                                     regdate=NOW(),
                                     m_id='{$_SESSION['one_member_id']}'
                               Where sms_idx='{$sms_idx}'
           ";
    $result = mysqli_query($self_con, $sql);

    if ($mb_id_copy != "") {
        $sql_chk = "select count(sms_detail_idx) as cnt from Gn_event_sms_step_info where sms_idx={$sms_idx}";
        $res_chk = mysqli_query($self_con, $sql_chk);
        $row_chk = mysqli_fetch_array($res_chk);
        if ($row_chk['cnt'] != 0) {
            $sql_del = "delete from Gn_event_sms_step_info where sms_idx={$sms_idx}";
            mysqli_query($self_con, $sql_del);
        }
        $sql_step_info = "INSERT INTO Gn_event_sms_step_info(sms_idx, step, send_day, send_time, title, content, image, image1, image2, regdate, send_deny) 
                                    (SELECT '{$sms_idx}', step, send_day, send_time, title, content, image, image1, image2, now(), send_deny FROM Gn_event_sms_step_info WHERE sms_idx={$ori_sms_idx})";
        mysqli_query($self_con, $sql_step_info);
    }
    echo "<script>location='mypage_reservation_list.php';</script>";
    exit;
} else if ($mode == "step_update") {
    $tempFile = $_FILES['image']['tmp_name'];
    $tempFile1 = $_FILES['image1']['tmp_name'];
    $tempFile2 = $_FILES['image2']['tmp_name'];
    $addQuery = "";
    if ($tempFile) {
        $file_arr = explode(".", $_FILES['image']['name']);
        $tmp_file_arr = explode("/", $tempFile);
        $file_name = date("Ymds") . "_" . $tmp_file_arr[count($tmp_file_arr) - 1] . "." . $file_arr[count($file_arr) - 1];
        $upload_file = "upload/" . $file_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
            $handle = new Image($upload_file, 800);
            $handle->resize();
            uploadFTP($upload_file);
            $addQuery .= " image='{$file_name}',";

            $size = getimagesize($upload_file);
            if ($size[0] > 640) {
                $ysize = $size[1] * (640 / $size[0]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name, "", 640, $ysize, "");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name;
            } else if ($size[1] > 480) {
                $xsize = $size[0] * (480 / $size[1]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name, "", $xsize, 480, "");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name;
            } else {
                copy($upload_file, "adjunct/mms/thum/" . $file_name);
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name;
            }
        }
    }

    if ($tempFile1) {
        $file_arr = explode(".", $_FILES['image1']['name']);
        $tmp_file_arr = explode("/", $tempFile1);
        $file_name1 = date("Ymds") . "_" . $tmp_file_arr[count($tmp_file_arr) - 1] . "." . $file_arr[count($file_arr) - 1];
        $upload_file = "upload/" . $file_name1;
        if (move_uploaded_file($_FILES['image1']['tmp_name'], $upload_file)) {
            $handle = new Image($upload_file, 800);
            $handle->resize();
            uploadFTP($upload_file);
            $addQuery .= " image1='{$file_name1}',";

            $size = getimagesize($upload_file);
            if ($size[0] > 640) {
                $ysize = $size[1] * (640 / $size[0]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name1, "", 640, $ysize, "");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name1;
            } else if ($size[1] > 480) {
                $xsize = $size[0] * (480 / $size[1]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name1, "", $xsize, 480, "");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name1;
            } else {
                copy($upload_file, "adjunct/mms/thum/" . $file_name1);
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name1;
            }
        }
    }

    if ($tempFile2) {
        $file_arr = explode(".", $_FILES['image2']['name']);
        $tmp_file_arr = explode("/", $tempFile2);
        $file_name2 = date("Ymds") . "_" . $tmp_file_arr[count($tmp_file_arr) - 1] . "." . $file_arr[count($file_arr) - 1];
        $upload_file = "upload/" . $file_name2;
        if (move_uploaded_file($_FILES['image2']['tmp_name'], $upload_file)) {
            $handle = new Image($upload_file, 800);
            $handle->resize();
            uploadFTP($upload_file);
            $addQuery .= " image2='{$file_name2}',";

            $size = getimagesize($upload_file);
            if ($size[0] > 640) {
                $ysize = $size[1] * (640 / $size[0]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name2, "", 640, $ysize, "");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name2;
            } else if ($size[1] > 480) {
                $xsize = $size[0] * (480 / $size[1]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name2, "", $xsize, 480, "");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name2;
            } else {
                copy($upload_file, "adjunct/mms/thum/" . $file_name2);
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name2;
            }
        }
    }
    if ($send_deny_msg == "on") {
        $deny = "Y";
    } else {
        $deny = "";
    }
    $send_time = sprintf("%02d", $send_time_hour) . ":" . sprintf("%02d", $send_time_min);
    $sql = "update  Gn_event_sms_step_info set title='{$title}',
                                                content='{$content}',
                                                $addQuery
                                                send_day='{$send_day}',
                                                send_time='{$send_time}',
                                                step='{$step}',
                                                send_deny='{$deny}'
                               where   sms_detail_idx='{$sms_detail_idx}'";
    $result = mysqli_query($self_con, $sql);
    echo "<script>location='mypage_reservation_create.php?sms_idx=$sms_idx';</script>";
    exit;
} else if ($mode == "step_add") {
    $tempFile = $_FILES['image']['tmp_name'];
    $tempFile1 = $_FILES['image1']['tmp_name'];
    $tempFile2 = $_FILES['image2']['tmp_name'];
    $addQuery = "";
    if ($tempFile) {
        $file_arr = explode(".", $_FILES['image']['name']);
        $tmp_file_arr = explode("/", $tempFile);
        $file_name = date("Ymds") . "_" . $tmp_file_arr[count($tmp_file_arr) - 1] . "." . $file_arr[count($file_arr) - 1];
        $upload_file = "upload/" . $file_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
            $handle = new Image($upload_file, 800);
            $handle->resize();
            uploadFTP($upload_file);
            $addQuery .= " image='{$file_name}',";

            $size = getimagesize($upload_file);
            if ($size[0] > 640) {
                $ysize = $size[1] * (640 / $size[0]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name, "", 640, $ysize, "");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name;
            } else if ($size[1] > 480) {
                $xsize = $size[0] * (480 / $size[1]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name, "", $xsize, 480, "");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name;
            } else {
                copy($upload_file, "adjunct/mms/thum/" . $file_name);
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name;
            }
        }
    }

    if ($tempFile1) {
        $file_arr = explode(".", $_FILES['image1']['name']);
        $tmp_file_arr = explode("/", $tempFile1);
        $file_name1 = date("Ymds") . "_" . $tmp_file_arr[count($tmp_file_arr) - 1] . "." . $file_arr[count($file_arr) - 1];
        $upload_file = "upload/" . $file_name1;
        if (move_uploaded_file($_FILES['image1']['tmp_name'], $upload_file)) {
            $handle = new Image($upload_file, 800);
            $handle->resize();
            uploadFTP($upload_file);
            $addQuery .= " image1='{$file_name1}',";

            $size = getimagesize($upload_file);
            if ($size[0] > 640) {
                $ysize = $size[1] * (640 / $size[0]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name1, "", 640, $ysize, "");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name1;
            } else if ($size[1] > 480) {
                $xsize = $size[0] * (480 / $size[1]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name1, "", $xsize, 480, "");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name1;
            } else {
                copy($upload_file, "adjunct/mms/thum/" . $file_name1);
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name1;
            }
        }
    }

    if ($tempFile2) {
        $file_arr = explode(".", $_FILES['image2']['name']);
        $tmp_file_arr = explode("/", $tempFile2);
        $file_name2 = date("Ymds") . "_" . $tmp_file_arr[count($tmp_file_arr) - 1] . "." . $file_arr[count($file_arr) - 1];
        $upload_file = "upload/" . $file_name2;
        if (move_uploaded_file($_FILES['image2']['tmp_name'], $upload_file)) {
            $handle = new Image($upload_file, 800);
            $handle->resize();
            uploadFTP($upload_file);
            $addQuery .= " image2='{$file_name2}',";

            $size = getimagesize($upload_file);
            if ($size[0] > 640) {
                $ysize = $size[1] * (640 / $size[0]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name2, "", 640, $ysize, "");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name2;
            } else if ($size[1] > 480) {
                $xsize = $size[0] * (480 / $size[1]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name2, "", $xsize, 480, "");
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name2;
            } else {
                copy($upload_file, "adjunct/mms/thum/" . $file_name2);
                $show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name2;
            }
        }
    }

    if ($send_deny_msg == "on") {
        $deny = "Y";
    } else {
        $deny = "";
    }
    $send_time = sprintf("%02d", $send_time_hour) . ":" . sprintf("%02d", $send_time_min);
    $sql = "insert into  Gn_event_sms_step_info set title='{$title}',
                                                    content='{$content}',
                                                    $addQuery
                                                    send_day='{$send_day}',
                                                    send_time='{$send_time}',
                                                    sms_idx='{$sms_idx}',
                                                    step='{$step}',
                                                    send_deny='{$deny}',
                                                    regdate=NOW()";
    $result = mysqli_query($self_con, $sql);

    echo "<script>location='mypage_reservation_create.php?sms_idx=$sms_idx';</script>";
    exit;
} else if ($mode == "request_add") {
    // echo $sms_idx."::".$step_num; exit;
    if (isset($_REQUEST['event_name_eng_event']) && $_REQUEST['event_name_eng_event'] != "") {
        $sp = $_REQUEST['event_name_eng_event'];
    }
    if (isset($_REQUEST['event_code']) && $_REQUEST['event_code'] != "") {
        $pcode = $_REQUEST['event_code'];
    }
    // exit;
    $m_id = $_SESSION['one_member_id'];
    $sql = "insert into Gn_event_request set landing_idx='{$landing_idx}',
                                           event_idx='{$event_idx}',
                                           event_code='{$pcode}',
                                           m_id='{$m_id}',
                                           name='{$name}',
                                           mobile='{$mobile}',
                                           email='{$email}',
                                           sex='{$sex}',
                                           addr='{$addr}',
                                           birthday='{$birthday}',
                                           consult_date='{$consult_date}',
                                           join_yn='{$join_yn}',                                         
                                           job='{$job}',
                                           pcode='{$pcode}',
                                           sp='{$sp}',
                                           ip_addr='{$ipcheck}',
                                           sms_idx='{$sms_idx}',
                                           sms_step_num='{$step_num}',
                                           regdate=now()";
    $res1 = mysqli_query($self_con, $sql);


    $request_idx = mysqli_insert_id($self_con);
    $recv_num = $mobile;
    $mem_id = $event_data['m_id'];

    $sql = "select sms_idx1, sms_idx2, sms_idx3, mobile from Gn_event where event_idx='{$event_idx}'";
    $eresult = mysqli_query($self_con, $sql);
    $erow = mysqli_fetch_array($eresult);
    $send_num = $erow['mobile'];

    $sql = "select * from Gn_event_sms_info where sms_idx='{$erow['sms_idx1']}' or sms_idx='{$erow['sms_idx2']}' or sms_idx='{$erow['sms_idx3']}'";
    $lresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    while ($lrow = mysqli_fetch_array($lresult)) {
        $mem_id = $lrow['m_id'];
        $sms_idx = $lrow['sms_idx'];

        //알람등록
        $sql = "select * from Gn_event_sms_info where sms_idx='{$sms_idx}'";
        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $row = mysqli_fetch_array($result);

        $reg = time();

        $sel_step = 0;
        if ($_REQUEST['step_num'] != "") {
            $sql = "select * from Gn_event_sms_step_info where sms_idx='{$sms_idx}' and step>={$_REQUEST['step_num']}";
            $sel_step = 1;
            $sql_step_start = "select send_day from Gn_event_sms_step_info where sms_idx='{$sms_idx}' and step>={$_REQUEST['step_num']} order by send_day asc limit 1";
            $res_step_start = mysqli_query($self_con, $sql_step_start);
            $row_step_start = mysqli_fetch_array($res_step_start);
            $start_dif = $row_step_start['send_day'];
        } else {
            $sql = "select * from Gn_event_sms_step_info where sms_idx='{$sms_idx}'";
        }

        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $k = 0;
        while ($row = mysqli_fetch_array($result)) {
            // 시간 확인
            $k++;
            //http://www.kiam.kr/adjunct/mms/thum/
            $uni_id = $reg . sprintf("%02d", $k);


            $send_day = $row['send_day'];
            if ($sel_step) {
                $send_day = (int)$send_day - (int)$start_dif;
            }
            $send_time = $row['send_time'];

            if ($send_time == "") $send_time = "09:30";
            if ($send_time == "00:00") $send_time = "09:30";
            if ($send_day == "0")
                $reservation = "";
            else
                $reservation = date("Y-m-d $send_time:00", strtotime("+$send_day days"));

            $jpg = $jpg1 = $jpg2 = '';
            if ($row['image'])
                $jpg = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image'];
            if ($row['image1'])
                $jpg1 = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image1'];
            if ($row['image2'])
                $jpg2 = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image2'];

            sendmms(3, $mem_id, $send_num, $recv_num, $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, 'Y', $row['sms_idx'], $row['sms_detail_idx'], $request_idx, "", $row['send_deny']);

            $query = "insert into Gn_MMS_Agree set mem_id='{$mem_id}',
                                                send_num='{$send_num}',
                                                recv_num='{$recv_num}',
                                                content='{$row['content']}',
                                                title='{$row['title']}',
                                                jpg='{$jpg}',
                                                reg_date=NOW(),
                                                reservation='{$reservation}',
                                                sms_idx='{$row['sms_idx']}',
                                                sms_detail_idx='{$row['sms_detail_idx']}',
                                                request_idx='{$request_idx}'";
            //echo $query."<BR>";
            mysqli_query($self_con, $query) or die(mysqli_error($self_con));
        }
    }
    if ($join_yn == 'Y') {
        //회원가입
        $site_info = explode(".", $HTTP_HOST);
        $site_name = $site_info[0];
        if ($site_name == "www")
            $site_name = "kiam";
        $sql = "select mem_id from Gn_Member where mem_id='{$mobile}' and site != ''";
        $res = mysqli_query($self_con, $sql);
        $row = mysqli_fetch_array($res);
        if ($row['mem_id'] == "") {
            $passwd = substr($mobile, -4);
            //password()
            $query = "insert into Gn_Member set mem_id='{$mobile}',
                                                mem_leb='22',
                                                web_pwd=md5('{$passwd}'),
                                                mem_pass=md5('{$passwd}'),
                                                mem_name='{$name}',
                                                mem_nick='{$name}',
                                                mem_phone='{$mobile}',
                                                zy='{$job}',
                                                first_regist=now() ,
                                                mem_check=now(),
                                                mem_add1='{$addr}',
                                                mem_email='{$email}',
                                                mem_sex='{$sex}',
                                                site = '{$site_name}',
                                                site_iam = '{$site_name}',
                                                join_ip='{$_SERVER['REMOTE_ADDR']}'";
            mysqli_query($self_con, $query);
        }
    }

    echo "<script>alert('신청되었습니다.');location='mypage_request_list.php';</script>";
    exit;
} else if ($mode == "request_update") {

    if (isset($_REQUEST['event_name_eng_event']) && $_REQUEST['event_name_eng_event'] != "") {
        $sp = $_REQUEST['event_name_eng_event'];
    }
    if (isset($_REQUEST['event_code']) && $_REQUEST['event_code'] != "") {
        $pcode = $_REQUEST['event_code'];
    }

    $sql = "update Gn_event_request set name='{$name}',
                                        event_idx='{$event_idx}',
                                        event_code='{$pcode}',
                                        mobile='{$mobile}',
                                        email='{$email}',
                                        job='{$job}',
                                        sp='{$sp}',
                                        sex='{$sex}',
                                        addr='{$addr}',
                                        birthday='{$birthday}',
                                        consult_date='{$consult_date}',
                                        join_yn='{$join_yn}',                                      
                                        edit_id='{$_SESSION['one_member_id']}',
                                        edit_date=NOW(),
                                        sms_idx='{$sms_idx}',
                                        sms_step_num='{$step_num}'
                                  where request_idx ='{$request_idx}'";
    $result = mysqli_query($self_con, $sql);
    echo "<script>location='mypage_request_list.php';</script>";
    exit;
} else if ($mode == "request_del") {
    $sql = "delete from Gn_event_request where request_idx ='{$request_idx}' and sp='{$org_event_code}'";
    $result = mysqli_query($self_con, $sql);
    echo json_encode(array("result"=>"success"));
    exit;
} else if ($mode == "oldrequest_del") {
    $idxs = explode(",", $idx); // mms_agree_idx
    $org_event_codes = explode(",", $org_event_code); // mms_agree_idx
    $idx_count = count($idxs);
    for ($i = 0; $i < $idx_count; $i++) {
        $sql = "delete from Gn_MMS where or_id='$idxs[$i]' and up_date is null ";
        mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

        $sql = "delete from Gn_event_oldrequest where idx ='$idxs[$i]'";
        $result = mysqli_query($self_con, $sql);
    }
    echo '{"result":"success"}';
    exit;
} else if ($mode == "old_customer_reservation") {
    if ($group_idx == "") {
        echo "주소록을 선택해주세요.";
        exit;
    }
    if ($reservation_title == "") {
        echo "예약메시지를 등록해주세요.";
        exit;
    }
    if ($start_index == "") {
        echo "시작건수를 입력해주세요.";
        exit;
    }
    if ($start_index < 1 || !preg_match("/[0-9]{1,3}/", $start_index, $m)) {
        echo "시작건수를 정확히 입력해주세요.";
        exit;
    }
    if ($end_index == "") {
        echo "종료건수를 입력해주세요.";
        exit;
    }
    if (!preg_match("/[0-9]{1,3}/", $end_index, $m)) {
        echo "종료건수를 정확히 입력해주세요.";
        exit;
    }
    $cnt = $end_index - $start_index + 1;
    if ($cnt < 0 || $cnt > 500) {
        echo "시작건수와 종료건수를 다시 확인해주세요.";
        exit;
    }
    $k = 0;
    $mms_idx = "";
    $mms_agree_idx = "";
    $grpId = $group_idx * -1;
    $event_idx = $event_idx_event;
    $fp = fopen("mypage.proc.log","w+");
    $query = "insert into Gn_event_oldrequest set sms_idx = '{$step_idx}',
                    mem_id='{$_SESSION['one_member_id']}',
                    send_num = '{$send_num}',
                    reservation_title = '{$reservation_title}',
                    address_idx = '{$group_idx}',
                    addr_start_index = '{$start_index}',
                    addr_end_index = '{$end_index}',
                    start_date=NOW(),
                    status = 'Y',
                    reg_date = NOW()";
    fwrite($fp,$query."\r\n");
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));
    $or_id = mysqli_insert_id($self_con);

    $start_index -= 1;

    $sql = "SELECT recv_num FROM Gn_MMS_Receive WHERE grp_id = '{$group_idx}' limit {$start_index}, {$cnt}";
    fwrite($fp,$sql."\r\n");
    $gresult = mysqli_query($self_con, $sql);

    $num_arr = array();
    while ($mms_receive = mysqli_fetch_array($gresult)) {
        array_push($num_arr, $mms_receive['recv_num']);
    }
    $recv_num = implode(",", $num_arr);

    $mem_id = $event_data['m_id'];
    $time = 60 - date("i");
    $reservation = "";
    $interval = IntervalDays(date("Y-m-d", time()), $reservation_date);
    $sql = "select * from Gn_event_sms_info where sms_idx='{$step_idx}'";
    $lresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    while ($lrow = mysqli_fetch_array($lresult)) {
        $mem_id = $lrow['m_id'];
        $sms_idx = $lrow['sms_idx'];
        $reservation_title = $lrow['reservation_title'];
        $reg = time();
        $sql = "select * from Gn_event_sms_step_info where sms_idx='{$sms_idx}'";
        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

        while ($row = mysqli_fetch_array($result)) {
            $k++;
            $send_day = $row['send_day'];
            $send_time = $row['send_time'];

            if ($send_time == "") $send_time = "09:30";
            if ($send_time == "00:00") $send_time = "09:30";
            if ($send_day == "0")
                $reservation = "";
            else
                $reservation = date("Y-m-d $send_time:00", strtotime("+$send_day days"));

            $jpg = $jpg1 = $jpg2 = '';
            if ($row['image'])
                $jpg = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image'];
            if ($row['image1'])
                $jpg1 = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image1'];
            if ($row['image2'])
                $jpg2 = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image2'];

            sendmms(8, $mem_id, $send_num, $recv_num, $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, "Y", $row['sms_idx'], $row['sms_detail_idx'], "", "", "", $or_id);
        }
    }

    if ($reservation == "")
        $query = "update Gn_event_oldrequest set end_date=NOW() where idx = '{$or_id}'";
    else
        $query = "update Gn_event_oldrequest set end_date='{$reservation}' where idx = '{$or_id}'";
        fwrite($fp,$query."\r\n");
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));

    echo "예약되었습니다.";
    exit;
} else if ($mode == "sms_detail_del") {
    $sql = "delete from Gn_event_sms_step_info where sms_detail_idx ='{$sms_detail_idx}' and sms_idx ='{$sms_idx}'";
    $result = mysqli_query($self_con, $sql);
    echo '{"result":"success"}';
    exit;
} else if ($mode == "sms_detail_info") {
    $sql = "select * from Gn_event_sms_step_info where sms_detail_idx ='{$sms_detail_idx}' and sms_idx ='{$sms_idx}'";
    $result = mysqli_query($self_con, $sql);
    $row = mysqli_fetch_array($result);
    $info['result'] = "success";
    $info['data'] = $row;
    echo json_encode($info);
    exit;
} else if ($mode == "reservation_del") {
    if (is_array($landing_idx) == true) {
        $idx = explode(",", $sms_idx);

        $idx_array = "'" . implode("','", $idx) . "'";
        $sql = "delete from Gn_event_sms_info where sms_idx in ($idx_array) and m_id ='{$_SESSION['one_member_id']}'";
        $result = mysqli_query($self_con, $sql);
    } else {
        $sql = "delete from Gn_event_sms_info where sms_idx='{$sms_idx}' and m_id ='{$_SESSION['one_member_id']}'";
        //echo $sql;
        $result = mysqli_query($self_con, $sql);
    }
    echo "<script>alert('삭제되었습니다.');location='mypage_landing_list.php';</script>";
    exit;
} else if ($mode == "request_event_add") {

    for ($i = 0; $i < count($request_idx); $i++) {
        $idx =  $request_idx[$i];
        $query = "
        insert into Gn_event_request (m_id, event_idx, event_code, name, mobile, email, job, birthday, sex, addr, consult_date, join_yn, pcode, sp, ip_addr, regdate, sms_idx, sms_step_num)
        select m_id, '{$event_idx_}', '{$event_pcode_}', name, mobile, email, job, birthday, sex, addr, consult_date, join_yn, '{$event_pcode_}', '{$sp_}', ip_addr, now(), '{$sms_idx}', '{$step_num}' from Gn_event_request where request_idx='{$idx}'";
        mysqli_query($self_con, $query);
    }
    $event_idx = $_POST['event_idx_'];
    $recv_num = $mobile;
    $mem_id = $m_id;

    //고객신청그룹에 설정된 스텝예약들을 불러들인다.
    $sql = "select sms_idx1, sms_idx2, sms_idx3, mobile from Gn_event where event_idx='{$event_idx}'";
    $eresult = mysqli_query($self_con, $sql);
    $erow = mysqli_fetch_array($eresult);
    $send_num = $erow['mobile'];

    $sql = "select * from Gn_event_sms_info where sms_idx='{$erow['sms_idx1']}' or sms_idx='{$erow['sms_idx2']}' or sms_idx='{$erow['sms_idx3']}'";
    //echo $sql;
    $lresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $time = 60 - date("i");
    //echo date("H:i:00", strtotime("+$time min"));

    $interval = IntervalDays(date("Y-m-d", time()), $reservation_date);

    while ($lrow = mysqli_fetch_array($lresult)) {
        $mem_id = $lrow['m_id'];
        $sms_idx = $lrow['sms_idx'];

        //알람등록
        $sql = "select * from Gn_event_sms_info where sms_idx='{$sms_idx}'";
        //echo $sql;
        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $row = mysqli_fetch_array($result);

        $reg = time();

        $sel_step = 0;
        if ($_REQUEST['step_num'] != "") {
            $sql = "select * from Gn_event_sms_step_info where sms_idx='{$sms_idx}' and step>={$_REQUEST['step_num']}";
            $sel_step = 1;
            $sql_step_start = "select send_day from Gn_event_sms_step_info where sms_idx='{$sms_idx}' and step>={$_REQUEST['step_num']} order by send_day asc limit 1";
            $res_step_start = mysqli_query($self_con, $sql_step_start);
            $row_step_start = mysqli_fetch_array($res);
            $start_dif = $row_step_start['send_day'];
        } else {
            $sql = "select * from Gn_event_sms_step_info where sms_idx='{$sms_idx}'";
        }
        //echo $sql;

        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $k = 0;
        while ($row = mysqli_fetch_array($result)) {
            // 시간 확인
            $k++;
            //http://www.kiam.kr/adjunct/mms/thum/
            $send_day = $row['send_day'];
            if ($sel_step) {
                $send_day = (int)$send_day - (int)$start_dif;
            }
            $send_time = $row['send_time'];
            //echo $row['send_day']."=".$row['send_time'];

            if ($send_time == "") $send_time = "09:30";
            if ($send_time == "00:00") $send_time = "09:30";
            if ($send_day == "0")
                $reservation = "";
            else
                $reservation = date("Y-m-d $send_time:00", strtotime("+$send_day days"));

            //echo "<BR>".$send_day."===".$send_time."<BR>";    
            //echo $reservation."<BR>";    
            $jpg = $jpg1 = $jpg2 = '';
            if ($row['image'])
                $jpg = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image'];
            if ($row['image1'])
                $jpg1 = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image1'];
            if ($row['image2'])
                $jpg2 = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image2'];

            for ($m = 0; $m < count($mobile); $m++) {
                sendmms(3, $mem_id, $send_num, $mobile[$m], $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, 'Y', $row['sms_idx'], $row['sms_detail_idx'], $request_idx[$m], "", $row['send_deny']);

                $query = "insert into Gn_MMS_Agree set mem_id='{$mem_id}',
                                                 send_num='{$send_num}',
                                                 recv_num='$mobile[$m]',
                                                 content='{$row['content']}',
                                                 title='{$row['title']}',
                                                 jpg='{$jpg}',
                                                 reg_date=NOW(),
                                                 reservation='{$reservation}',
                                                 sms_idx='{$row['sms_idx']}',
                                                 sms_detail_idx='{$row['sms_detail_idx']}',
                                                 request_idx='$request_idx[$m]'";
                //echo $query."<BR>";
                mysqli_query($self_con, $query) or die(mysqli_error($self_con));
            }
        }
    }
    //exit;
    echo "<script>alert('등록되었습니다.');location='mypage_request_list.php';</script>";
    exit;
} else if ($mode == "address_event_add") {
    $query = "insert into Gn_event_address (mem_id, event_idx, address_idx, regdate) values ('{$_SESSION['one_member_id']}','{$event_idx}','{$address_idx}',now())";
    print_r($_POST);
    exit;
    for ($i = 0; $i < count($request_idx); $i++) {
        $idx =  $request_idx[$i];
        $query = "insert into Gn_event_request (m_id, event_idx, event_code, name, mobile, email, job, birthday, sex, addr, consult_date, join_yn, pcode, sp, ip_addr, regdate)
        select m_id, '{$event_idx_}', '{$sp_}', name, mobile, email, job, birthday, sex, addr, consult_date, join_yn, '{$sp_}', '{$event_pcode_}', ip_addr, now() from Gn_event_request where request_idx='{$idx}'";
        mysqli_query($self_con, $query);
    }
    $event_idx = $_POST['event_idx_'];
    $recv_num = $mobile;
    $mem_id = $m_id;

    $sql = "select sms_idx1, sms_idx2, sms_idx3, mobile from Gn_event where event_idx='{$event_idx}'";
    $eresult = mysqli_query($self_con, $sql);
    $erow = mysqli_fetch_array($eresult);
    $send_num = $erow['mobile'];

    $sql = "select * from Gn_event_sms_info where sms_idx='{$erow['sms_idx1']}' or sms_idx='{$erow['sms_idx2']}' or sms_idx='{$erow['sms_idx3']}'";
    //echo $sql;
    $lresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $time = 60 - date("i");
    $interval = IntervalDays(date("Y-m-d", time()), $reservation_date);

    while ($lrow = mysqli_fetch_array($lresult)) {
        $mem_id = $lrow['m_id'];
        $sms_idx = $lrow['sms_idx'];

        $send_num = $lrow['mobile'];

        //알람등록
        $sql = "select * from Gn_event_sms_info where sms_idx='{$sms_idx}'";
        //echo $sql;
        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $row = mysqli_fetch_array($result);

        $reg = time();
        $sql = "select * from Gn_event_sms_step_info where sms_idx='{$sms_idx}'";
        //echo $sql;

        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $k = 0;
        while ($row = mysqli_fetch_array($result)) {
            // 시간 확인
            $k++;
            $send_day = $row['send_day'];
            $send_time = $row['send_time'];

            if ($send_time == "") $send_time = "09:30";
            if ($send_time == "00:00") $send_time = "09:30";
            if ($send_day == "0")
                $reservation = "";
            else
                $reservation = date("Y-m-d $send_time:00", strtotime("+$send_day days"));

            $jpg = $jpg1 = $jpg2 = '';
            if ($row['image'])
                $jpg = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image'];
            if ($row['image1'])
                $jpg1 = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image1'];
            if ($row['image2'])
                $jpg2 = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image2'];

            for ($m = 0; $m < count($mobile); $m++) {
                sendmms(3, $mem_id/*$_SESSION['one_member_id']*/, $send_num, $mobile[$m], $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, 'Y', $row['sms_idx'], $row['sms_detail_idx'], $request_idx[$m], "", $row['send_deny']);

                $query = "insert into Gn_MMS_Agree set mem_id='{$mem_id}',
                                                 send_num='{$send_num}',
                                                 recv_num='$mobile[$m]',
                                                 content='{$row['content']}',
                                                 title='{$row['title']}',
                                                 jpg='{$jpg}',
                                                 reg_date=NOW(),
                                                 reservation='{$reservation}',
                                                 sms_idx='{$row['sms_idx']}',
                                                 sms_detail_idx='{$row['sms_detail_idx']}',
                                                 request_idx='$request_idx[$m]'";
                //echo $query."<BR>";
                mysqli_query($self_con, $query) or die(mysqli_error($self_con));
            }
        }
    }
    echo "<script>alert('등록되었습니다.');location='mypage_request_list.php';</script>";
    exit;
} else if ($mode == "lecture_save") {
    $file_arr = explode(".", $_FILES['review_img1']['name']);
    $tempFile = $_FILES['review_img1']['tmp_name'];
    $tmp_file_arr = explode("/", $tempFile);
    $file_name = date("Ymds") . "_" . $tmp_file_arr[count($tmp_file_arr) - 1] . "." . $file_arr[count($file_arr) - 1];
    $upload_file = "upload/lecture/" . $file_name;
    if (move_uploaded_file($_FILES['review_img1']['tmp_name'], $upload_file)) {
        $handle = new Image($upload_file, 800);
        $handle->resize();
        uploadFTP($upload_file);
        $addQuery .= " review_img1='{$file_name}',";
    }

    $file_arr = explode(".", $_FILES['review_img2']['name']);
    $tempFile = $_FILES['review_img2']['tmp_name'];
    $tmp_file_arr = explode("/", $tempFile);
    $file_name = date("Ymds") . "_" . $tmp_file_arr[count($tmp_file_arr) - 1] . "." . $file_arr[count($file_arr) - 1];
    $upload_file = "upload/lecture/" . $file_name;
    if (move_uploaded_file($_FILES['review_img2']['tmp_name'], $upload_file)) {
        $handle = new Image($upload_file, 800);
        $handle->resize();
        uploadFTP($upload_file);
        $addQuery .= " review_img2='{$file_name}',";
    }

    $file_arr = explode(".", $_FILES['review_img3']['name']);
    $tempFile = $_FILES['review_img3']['tmp_name'];
    $tmp_file_arr = explode("/", $tempFile);
    $file_name = date("Ymds") . "_" . $tmp_file_arr[count($tmp_file_arr) - 1] . "." . $file_arr[count($file_arr) - 1];
    $upload_file = "upload/lecture/" . $file_name;
    if (move_uploaded_file($_FILES['review_img3']['tmp_name'], $upload_file)) {
        $handle = new Image($upload_file, 800);
        $handle->resize();
        uploadFTP($upload_file);
        $addQuery .= " review_img3='{$file_name}',";
    }

    $file_arr = explode(".", $_FILES['review_img4']['name']);
    $tempFile = $_FILES['review_img4']['tmp_name'];
    $tmp_file_arr = explode("/", $tempFile);
    $file_name = date("Ymds") . "_" . $tmp_file_arr[count($tmp_file_arr) - 1] . "." . $file_arr[count($file_arr) - 1];
    $upload_file = "upload/lecture/" . $file_name;
    if (move_uploaded_file($_FILES['review_img4']['tmp_name'], $upload_file)) {
        $handle = new Image($upload_file, 800);
        $handle->resize();
        uploadFTP($upload_file);
        $addQuery .= " review_img4='{$file_name}',";
    }

    $file_arr = explode(".", $_FILES['review_img5']['name']);
    $tempFile = $_FILES['review_img5']['tmp_name'];
    $tmp_file_arr = explode("/", $tempFile);
    $file_name = date("Ymds") . "_" . $tmp_file_arr[count($tmp_file_arr) - 1] . "." . $file_arr[count($file_arr) - 1];
    $upload_file = "upload/lecture/" . $file_name;
    if (move_uploaded_file($_FILES['review_img5']['tmp_name'], $upload_file)) {
        $handle = new Image($upload_file, 800);
        $handle->resize();
        uploadFTP($upload_file);
        $addQuery .= " review_img5='{$file_name}',";
    }

    $lecture_day = implode(",", $lecture_day);
    $sql = "insert into Gn_lecture set event_idx='{$event_idx}',
                                       event_code='{$event_code}',
                                       category='{$category}',
                                       start_date='{$start_date}',
                                       end_date='{$end_date}',
                                       lecture_day='{$lecture_day}',
                                       lecture_start_time='{$lecture_start_time}',
                                       lecture_end_time='{$lecture_end_time}',
                                       lecture_info='{$lecture_info}',
                                       lecture_url='{$lecture_url}',
                                       instructor='{$instructor}',
                                       target='{$target}',
                                       area='{$area}',
                                       max_num='{$max_num}',
                                       $addQuery
                                       review_title1='{$review_title1}',
                                       review_title2='{$review_title2}',
                                       review_title3='{$review_title3}',
                                       review_title4='{$review_title4}',
                                       review_title5='{$review_title5}',                                     
                                       fee='{$fee}',
                                       mem_id='{$_SESSION['one_member_id']}',
                                       status='N',
                                       regdate=NOW()";
    $result = mysqli_query($self_con, $sql);
    echo "<script>alert('등록되었습니다.');location='mypage_lecture_list.php';</script>";
    exit;
} else if ($mode == "lecture_save_event") {
    $lecture_day = implode(",", $lecture_day);
    $sql = "insert into Gn_lecture set event_idx='{$event_idx}',
                                       event_code='{$event_code}',
                                       category='{$category}',
                                       start_date='{$start_date}',
                                       end_date='{$end_date}',
                                       lecture_day='{$lecture_day}',
                                       lecture_start_time='{$lecture_start_time}',
                                       lecture_end_time='{$lecture_end_time}',
                                       lecture_url='{$lecture_url}',
                                       lecture_info='{$lecture_info}',
                                       instructor='{$instructor}',
                                       target='{$target}',
                                       area='{$area}',
                                       max_num='{$max_num}',
                                       fee='{$fee}',
                                       mem_id='{$_SESSION['one_member_id']}',
                                       status='N',
                                       regdate=NOW()";
    $result = mysqli_query($self_con, $sql);
    echo "<script>alert('등록되었습니다.');location='/event/event.html?pcode=$pcode&sp=$sp&landing_idx=$landing_idx';</script>";
    exit;
} else if ($mode == "lecture_update") {

    $file_arr = explode(".", $_FILES['review_img1']['name']);
    $tempFile = $_FILES['review_img1']['tmp_name'];
    $tmp_file_arr = explode("/", $tempFile);
    $file_ext = strtolower(end(explode('.', $_FILES['review_img1']['name'])));
    $file_name = $_POST['lecture_id'] . "_" . date("YmdHis") . "_1." . $file_ext;
    if ($review_img1_del == "Y" && $_FILES['review_img1']['name'] == "") $addQuery .= "review_img1='',";
    $upload_file = "upload/lecture/" . $file_name;
    if (move_uploaded_file($_FILES['review_img1']['tmp_name'], $upload_file)) {
        $handle = new Image($upload_file, 800);
        $handle->resize();
        uploadFTP($upload_file);
        $addQuery .= " review_img1='{$file_name}',";
    }

    $file_arr = explode(".", $_FILES['review_img2']['name']);
    $tempFile = $_FILES['review_img2']['tmp_name'];
    $tmp_file_arr = explode("/", $tempFile);
    $file_name = $_POST['lecture_id'] . "_" . date("YmdHis") . "_2." . $file_ext; //$file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    

    if ($review_img2_del == "Y" && $_FILES['review_img2']['name'] == "") $addQuery .= "review_img2='',";
    $upload_file = "upload/lecture/" . $file_name;
    if (move_uploaded_file($_FILES['review_img2']['tmp_name'], $upload_file)) {
        $handle = new Image($upload_file, 800);
        $handle->resize();
        uploadFTP($upload_file);
        $addQuery .= " review_img2='{$file_name}',";
    }

    $file_arr = explode(".", $_FILES['review_img3']['name']);
    $tempFile = $_FILES['review_img3']['tmp_name'];
    $tmp_file_arr = explode("/", $tempFile);
    $file_name = $_POST['lecture_id'] . "_" . date("YmdHis") . "_3." . $file_ext; //$file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    

    if ($review_img3_del == "Y" && $_FILES['review_img3']['name'] == "") $addQuery .= "review_img3='',";
    $upload_file = "upload/lecture/" . $file_name;
    if (move_uploaded_file($_FILES['review_img3']['tmp_name'], $upload_file)) {
        $handle = new Image($upload_file, 800);
        $handle->resize();
        uploadFTP($upload_file);
        $addQuery .= " review_img3='{$file_name}',";
    }

    $file_arr = explode(".", $_FILES['review_img4']['name']);
    $tempFile = $_FILES['review_img4']['tmp_name'];
    $tmp_file_arr = explode("/", $tempFile);
    $file_name = $_POST['lecture_id'] . "_" . date("YmdHis") . "_4." . $file_ext; //$file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    

    if ($review_img4_del == "Y" && $_FILES['review_img4']['name'] == "") $addQuery .= "review_img4='',";
    $upload_file = "upload/lecture/" . $file_name;
    if (move_uploaded_file($_FILES['review_img4']['tmp_name'], $upload_file)) {
        $handle = new Image($upload_file, 800);
        $handle->resize();
        uploadFTP($upload_file);
        $addQuery .= " review_img4='{$file_name}',";
    }

    $file_arr = explode(".", $_FILES['review_img5']['name']);
    $tempFile = $_FILES['review_img5']['tmp_name'];
    $tmp_file_arr = explode("/", $tempFile);
    $file_name = $_POST['lecture_id'] . "_" . date("YmdHis") . "_5." . $file_ext; //$file_name=date("Ymds")."_".$tmp_file_arr[count($tmp_file_arr)-1].".".$file_arr[count($file_arr)-1];    

    if ($review_img5_del == "Y" && $_FILES['review_img5']['name'] == "") $addQuery .= "review_img5='',";
    $upload_file = "upload/lecture/" . $file_name;
    if (move_uploaded_file($_FILES['review_img5']['tmp_name'], $upload_file)) {
        $handle = new Image($upload_file, 800);
        $handle->resize();
        uploadFTP($upload_file);
        $addQuery .= " review_img5='{$file_name}',";
    }


    $lecture_day = implode(",", $lecture_day);
    $sql = "update  Gn_lecture set event_idx='{$event_idx}',
                                       event_code='{$event_code}',
                                       category='{$category}',
                                       start_date='{$start_date}',
                                       end_date='{$end_date}',
                                       lecture_day='{$lecture_day}',
                                       lecture_start_time='{$lecture_start_time}',
                                       lecture_end_time='{$lecture_end_time}',
                                       lecture_url='{$lecture_url}',
                                       lecture_info='{$lecture_info}',
                                       instructor='{$instructor}',
                                       target='{$target}',
                                       area='{$area}',
                                       max_num='{$max_num}',
                                       $addQuery
                                       review_title1='{$review_title1}',
                                       review_title2='{$review_title2}',
                                       review_title3='{$review_title3}',
                                       review_title4='{$review_title4}',
                                       review_title5='{$review_title5}',
                                       fee='{$fee}'
                                where  lecture_id='{$lecture_id}'";
    $result = mysqli_query($self_con, $sql);
    echo "<script>alert('수정되었습니다.');location='mypage_lecture_list.php';</script>";
    exit;
} else if ($mode == "lecture_del") {
    $sql = "delete from  Gn_lecture where  lecture_id='{$lecture_id}'";
    $result = mysqli_query($self_con, $sql);
    echo "<script>alert('삭제되었습니다.');location='mypage_lecture_list.php';</script>";
    exit;
} else if ($mode == "review_save") {
    $file_arr = explode(".", $_FILES['image']['name']);
    $tempFile = $_FILES['image']['tmp_name'];
    $tmp_file_arr = explode("/", $tempFile);
    $file_name = date("Ymds") . "_" . $tmp_file_arr[count($tmp_file_arr) - 1] . "." . $file_arr[count($file_arr) - 1];
    $upload_file = "upload/review/" . $file_name;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_file)) {
        $handle = new Image($upload_file, 800);
        $handle->resize();
        uploadFTP($upload_file);
        $addQuery .= " image='{$file_name}',";
    }

    $lecture_day = implode(",", $lecture_day);
    $sql = "insert into Gn_review set lecture_id='{$lecture_id}',
                                       score='{$score}',
                                       content='{$content}',
                                       profile='{$profile}',
                                       mem_id='{$_SESSION['one_member_id']}',
                                       status='N',
                                       $addQuery
                                       regdate=NOW()";
    $result = mysqli_query($self_con, $sql);
    echo "<script>alert('등록되었습니다.');location='mypage_review_list.php';</script>";
    exit;
} else if ($mode == "review_update") {
    $lecture_day = implode(",", $lecture_day);
    $sql = "update Gn_review set score='{$score}',content='{$content}',profile='{$profile}' where  review_id='{$review_id}'";
    $result = mysqli_query($self_con, $sql);
    echo "<script>alert('수정되었습니다.');location='mypage_review_list.php';</script>";
    exit;
} else if ($mode == "daily_del") {
    if ($gd_id > 0) {
        $sql = "delete from  Gn_daily where  gd_id='{$gd_id}'";
        $result = mysqli_query($self_con, $sql);

        $query = "delete from Gn_daily_date where gd_id='{$gd_id}'";
        mysqli_query($self_con, $query);

        $query = "delete from Gn_MMS where gd_id='{$gd_id}' ";
        mysqli_query($self_con, $query);

        $query = "delete from gn_mail where gd_id='{$gd_id}' ";
        mysqli_query($self_con, $query);
    }

    echo "<script>alert('삭제되었습니다.');location='daily_list.php';</script>";
    exit;
} else if ($mode == "daily_update") {
    $reg = time();
    $uni_id = $reg . sprintf("%02d", $k);
    $date = explode(",", $send_date);
    $end_date = max($date);
    $start_date = min($date);
    $query_step_add = "";
    $step_idx = "";
    if ($total_count == "")
        $total_count = 0;

    if (!$daily_link) {
        $sql_card = "select card_short_url from Gn_Iam_Name_Card where mem_id='{$_SESSION['one_member_id']}' order by idx asc limit 1";
        $res_card = mysqli_query($self_con, $sql_card);
        $row_card = mysqli_fetch_array($res_card);

        $sql_mem = "select mem_code from Gn_Member where mem_id='{$_SESSION['one_member_id']}'";
        $res_mem = mysqli_query($self_con, $sql_mem);
        $row_mem = mysqli_fetch_array($res_mem);

        $daily_link = "https://" . $HTTP_HOST . '/?' . $row_card[0] . $row_mem[0];
    }

    if ($send_deny_msg == "on") {
        $deny = "Y";
    } else {
        $deny = "";
    }

    if ($step_daily == "Y") {
        if ($set_msg_mode == "0") {
            $step_sms_idx = 0;
            $step_count = 0;
        } else {
            $step_idx = $step_sms_idx;
            $sql_sms = "select title, content, image, image1, image2, send_deny from Gn_event_sms_step_info where sms_idx='{$step_sms_idx}' order by step asc limit 1";
            $res_sms = mysqli_query($self_con, $sql_sms);
            $row_sms = mysqli_fetch_array($res_sms);

            $title = $row_sms['title'];
            $txt = $row_sms['content'];
            $deny = $row_sms['send_deny'];
            $row_sms['image'] ? $upimage_str = 'https://kiam.kr/upload/' . $row_sms['image'] : '';
            $row_sms['image1'] ? $upimage_str1 = 'https://kiam.kr/upload/' . $row_sms['image1'] : '';
            $row_sms['image2'] ? $upimage_str2 = 'https://kiam.kr/upload/' . $row_sms['image2'] : '';

            if (isset($_POST['step_count'])) $step_count = $_POST['step_count'];
            else $step_count = 1;
            $daily_link = "";
        }
        $query_step_add = "step_sms_idx='{$step_sms_idx}',
                        weekend_status='{$set_weekend}',
                        max_count='{$max_count}',
                        step_count='{$step_count}',";
    }

    $query = "update Gn_daily set mem_id='{$_SESSION['one_member_id']}', 
                                 send_num='{$send_num}',
                                 group_idx='{$group_idx}',
                                 total_count='{$total_count}',
                                 title='{$title}',
                                 content='{$txt}',
                                 link='{$daily_link}',
                                 daily_cnt='{$daily_cnt}',
                                 start_date='{$start_date}',
                                 end_date='{$end_date}',
                                 jpg='{$upimage_str}',
                                 jpg1='{$upimage_str1}',
                                 jpg2='{$upimage_str2}',
                                 status='Y',
                                 send_deny='{$deny}',
                                 $query_step_add
                                 reg_date=NOW()
                            where gd_id='{$gd_id}'";
    mysqli_query($self_con, $query);

    if ($gd_id > 0) {
        $query = "delete from Gn_daily_date where gd_id='{$gd_id}';";
        mysqli_query($self_con, $query);

        $query = "delete from Gn_MMS where gd_id='{$gd_id}' ";
        mysqli_query($self_con, $query);

        $query = "delete from gn_mail where gd_id='{$gd_id}' ";
        mysqli_query($self_con, $query);
    }
    $k = 0;
    $kk = 0;
    $txt .= "\n" . $daily_link;
    $sql = "select * from Gn_MMS_Receive where grp_id = '{$group_idx}' ";
    $sresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    while ($srow = mysqli_fetch_array($sresult)) {
        if ($kk == $daily_cnt) {
            $k++;
            $kk = 0;
        }
        if ($kk > 0)
            $recv_num_set[$k] .= "," . $srow['recv_num'];
        else
            $recv_num_set[$k] = $srow['recv_num'];

        if ($srow['email'] != "") {
            if (strlen($recv_mail_set[$k]) == 0)
                $recv_mail_set[$k] = $srow['email'];
            else
                $recv_mail_set[$k] .= "," . $srow['email'];
        }
        $kk++;
    }

    if ($mail_sender != "") {
        for ($i = 0; $i < count($date); $i++) {
            if (strlen($recv_mail_set[$i]) == 0)
                continue;
            $reservation = $date[$i] . " " . $htime . ":" . $mtime . ":00";
            sendemail($reservation, $recv_mail_set[$i], $mail_sender, $mail_title, $mail_content, $mail_file, $gd_id);
        }
    }

    for ($i = 0; $i < count($date); $i++) {
        $reservation = $date[$i] . " " . $htime . ":" . $mtime . ":00";
        sendmms(6, $_SESSION['one_member_id'], $send_num, $recv_num_set[$i], $reservation, $title, $txt, $upimage_str, $upimage_str1, $upimage_str2, 'Y', "", "", "", $gd_id, $deny);
    }

    for ($i = 0; $i < count($date); $i++) {
        $query = "insert into Gn_daily_date set gd_id='{$gd_id}', send_date='$date[$i]', recv_num='$recv_num_set[$i]'";
        mysqli_query($self_con, $query);
    }
    echo "<script>alert('수정되었습니다.');location='daily_list.php';</script>";
    exit;
} else if ($mode == "daily_save") {
    $event_idx = 0;
    $query_step_add = "";
    $step_idx = "";
    if ($iam == "")
        $iam = 0;
    if ($total_count == "")
        $total_count = 0;
    if (isset($_POST['mem_id'])) {
        $_SESSION['one_member_id'] = $_POST['mem_id'];
    }
    if (!$daily_link) {
        $sql_card = "select card_short_url from Gn_Iam_Name_Card where mem_id='{$_POST['mem_id']}' order by idx asc limit 1";
        $res_card = mysqli_query($self_con, $sql_card);
        $row_card = mysqli_fetch_array($res_card);

        $sql_mem = "select mem_code from Gn_Member where mem_id='{$_POST['mem_id']}'";
        $res_mem = mysqli_query($self_con, $sql_mem);
        $row_mem = mysqli_fetch_array($res_mem);

        $daily_link = "https://" . $HTTP_HOST . '/?' . $row_card[0] . $row_mem[0];
    }
    $reg = time();
    $uni_id = $reg . sprintf("%02d", $k);
    $date = explode(",", $send_date);
    $end_date = max($date);
    $start_date = min($date);

    if ($send_deny_msg == "on") {
        $deny = "Y";
    } else {
        $deny = "";
    }

    if (isset($_POST['set_msg_mode']) && $set_msg_mode == "1") {
        if (isset($_POST['step_count']))
            $step_count = $_POST['step_count'];
        else
            $step_count = 0;

        $sql_cnt = "select min(send_day) as step_count from Gn_event_sms_step_info where sms_idx='{$step_sms_idx}' and send_day >= '{$step_count}'";
        $res_cnt = mysqli_query($self_con, $sql_cnt);
        $row_cnt = mysqli_fetch_array($res_cnt);

        $sql_sms = "select title, content, image, image1, image2, send_deny, send_time from Gn_event_sms_step_info where sms_idx='{$step_sms_idx}' and send_day = '{$row_cnt['step_count']}' order by step asc";
        // $sql_sms = "select title, content, image, image1, image2 from Gn_event_sms_step_info where sms_idx='{$step_sms_idx}' order by step asc limit 1";
        $res_sms = mysqli_query($self_con, $sql_sms);
        if (mysqli_num_rows($res_sms)) {
            while ($row_sms = mysqli_fetch_array($res_sms)) {
                $send_time = $row_sms['send_time'];
                if (isset($_POST['step_count'])) {
                    $time_arr = explode(":", $send_time);
                    $htime = trim($time_arr[0]);
                    $mtime = trim($time_arr[1]);
                }
                $title = $row_sms['title'];
                $txt = $row_sms['content'];
                $deny = $row_sms['send_deny'];
                $row_sms['image'] ? $upimage_str = 'https://kiam.kr/upload/' . $row_sms['image'] : '';
                $row_sms['image1'] ? $upimage_str1 = 'https://kiam.kr/upload/' . $row_sms['image1'] : '';
                $row_sms['image2'] ? $upimage_str2 = 'https://kiam.kr/upload/' . $row_sms['image2'] : '';

                $step_idx = $step_sms_idx;
                $daily_link = "";

                $query_step_add = "step_sms_idx='{$step_sms_idx}',
                                        weekend_status='{$set_weekend}',
                                        max_count='{$max_count}',
                                        step_count='{$step_count}',";
                $query = "insert into Gn_daily set 
                                        mem_id='{$_SESSION['one_member_id']}', 
                                        iam='{$iam}',
                                        send_num='{$send_num}',
                                        group_idx='{$group_idx}',
                                        total_count='{$total_count}',
                                        title='{$title}',
                                        content='{$txt}',
                                        link='{$daily_link}',
                                        daily_cnt='{$daily_cnt}',
                                        start_date='{$start_date}',
                                        end_date='{$end_date}',
                                        jpg='{$upimage_str}',
                                        jpg1='{$upimage_str1}',
                                        jpg2='{$upimage_str2}',
                                        status='Y',
                                        reg_date=NOW(),
                                        htime='{$htime}',
                                        mtime='{$mtime}',
                                        send_deny='{$deny}', 
                                        $query_step_add 
                                        event_idx='{$event_idx}'";
                //echo $query . "<BR>";
                mysqli_query($self_con, $query);
                $gd_id = mysqli_insert_id($self_con);
                $txt .= "\n" . $daily_link;
                if ($iam) {
                    $table = "Gn_MMS_Receive_Iam";
                } else {
                    $table = "Gn_MMS_Receive";
                }

                $k = 0;
                $kk = 0;
                $sql = "select * from " . $table . " where grp_id = '{$group_idx}' ";
                $sresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
                while ($srow = mysqli_fetch_array($sresult)) {
                    $sql_chk = "select idx from Gn_MMS_Deny where send_num='{$send_num}' and recv_num='{$srow['recv_num']}' and (chanel_type=9 or chanel_type=4)";
                    $res_chk = mysqli_query($self_con, $sql_chk);
                    if (mysqli_num_rows($res_chk)) {
                        continue;
                    }
                    if ($kk == $daily_cnt) {
                        $k++;
                        $kk = 0;
                    }
                    if ($kk > 0)
                        $recv_num_set[$k] .= "," . $srow['recv_num'];
                    else
                        $recv_num_set[$k] = $srow['recv_num'];

                    if ($srow['email'] != "") {
                        if (strlen($recv_mail_set[$k]) == 0)
                            $recv_mail_set[$k] = $srow['email'];
                        else
                            $recv_mail_set[$k] .= "," . $srow['email'];
                    }
                    $kk++;
                }
                //print_r($recv_num_set);

                if ($mail_sender != "") {
                    for ($i = 0; $i < count($date); $i++) {
                        if (strlen($recv_mail_set[$i]) == 0)
                            continue;
                        $reservation = $date[$i] . " " . $htime . ":" . $mtime . ":00";
                        sendemail($reservation, $recv_mail_set[$i], $mail_sender, $mail_title, $mail_content, $mail_file, $gd_id);
                    }
                }

                for ($i = 0; $i < count($date); $i++) {
                    $reservation = $date[$i] . " " . $htime . ":" . $mtime . ":00";
                    sendmms(6, $_SESSION['one_member_id'], $send_num, $recv_num_set[$i], $reservation, $title, $txt, $upimage_str, $upimage_str1, $upimage_str2, 'Y', "", "", "", $gd_id, $deny);
                }

                for ($i = 0; $i < count($date); $i++) {
                    $query = "insert into Gn_daily_date set gd_id='{$gd_id}',
                                                                send_date='{$date[$i]}',
                                                                recv_num='{$recv_num_set[$i]}'";
                    mysqli_query($self_con, $query);
                    //echo $query . "<BR>";;
                }
            }
        }
    } else {
        $query = "insert into Gn_daily set mem_id='{$_SESSION['one_member_id']}', 
                                    iam='{$iam}',
                                    send_num='{$send_num}',
                                    group_idx='{$group_idx}',
                                    total_count='{$total_count}',
                                    title='{$title}',
                                    content='{$txt}',
                                    link='{$daily_link}',
                                    daily_cnt='{$daily_cnt}',
                                    start_date='{$start_date}',
                                    end_date='{$end_date}',
                                    jpg='{$upimage_str}',
                                    jpg1='{$upimage_str1}',
                                    jpg2='{$upimage_str2}',
                                    status='Y',
                                    reg_date=NOW(),
                                    htime='{$htime}',
                                    mtime='{$mtime}',
                                    send_deny='{$deny}',
                                    $query_step_add
                                    event_idx='{$event_idx}'";
        //echo $query . "<BR>";
        mysqli_query($self_con, $query);
        $gd_id = mysqli_insert_id($self_con);

        $txt .= "\n" . $daily_link;

        if ($iam) {
            $table = "Gn_MMS_Receive_Iam";
        } else {
            $table = "Gn_MMS_Receive";
        }

        $k = 0;
        $kk = 0;
        $sql = "select * from " . $table . " where grp_id = '{$group_idx}' ";
        $sresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        while ($srow = mysqli_fetch_array($sresult)) {
            $sql_chk = "select idx from Gn_MMS_Deny where send_num='{$send_num}' and recv_num='{$srow['recv_num']}' and (chanel_type=9 or chanel_type=4)";
            $res_chk = mysqli_query($self_con, $sql_chk);
            if (mysqli_num_rows($res_chk)) {
                continue;
            }
            if ($kk == $daily_cnt) {
                $k++;
                $kk = 0;
            }
            if ($kk > 0)
                $recv_num_set[$k] .= "," . $srow['recv_num'];
            else
                $recv_num_set[$k] = $srow['recv_num'];

            if ($srow['email'] != "") {
                if (strlen($recv_mail_set[$k]) == 0)
                    $recv_mail_set[$k] = $srow['email'];
                else
                    $recv_mail_set[$k] .= "," . $srow['email'];
            }
            $kk++;
        }
        //print_r($recv_num_set);

        if ($mail_sender != "") {
            for ($i = 0; $i < count($date); $i++) {
                if (strlen($recv_mail_set[$i]) == 0)
                    continue;
                $reservation = $date[$i] . " " . $htime . ":" . $mtime . ":00";
                sendemail($reservation, $recv_mail_set[$i], $mail_sender, $mail_title, $mail_content, $mail_file, $gd_id);
            }
        }

        for ($i = 0; $i < count($date); $i++) {
            $reservation = $date[$i] . " " . $htime . ":" . $mtime . ":00";
            sendmms(6, $_SESSION['one_member_id'], $send_num, $recv_num_set[$i], $reservation, $title, $txt, $upimage_str, $upimage_str1, $upimage_str2, 'Y', "", "", "", $gd_id, $deny);
        }

        for ($i = 0; $i < count($date); $i++) {
            $query = "insert into Gn_daily_date set gd_id='{$gd_id}',send_date='{$date[$i]}',recv_num='{$recv_num_set[$i]}'";
            mysqli_query($self_con, $query);
            //echo $query . "<BR>";;
        }
    }

    echo "<script>alert('등록되었습니다.');location='daily_list.php';</script>";
    exit;
}
//코치가 코칭정보 입력 *******************************************************************
else if ($mode == "create_coaching_info") {
    $insert_coach_id = $_POST['coach_id'];
    $insert_coach_mem_code = $_POST['coach_mem_code'];
    $arr = explode("\_", $_POST['coty_id']);
    $insert_coty_id = $arr[0];
    $insert_coty_mem_code = $arr[2];
    $insert_coaching_title = $_POST['coaching_title'];
    $insert_coaching_content = $_POST['coaching_content'];
    $insert_coaching_date = date($_POST['start_date'] . " " . $_POST['start_hour'] . ":" . $_POST['start_min']);
    $sql_1 = "Select * FROM `gn_coaching_info` WHERE coaching_turn = (  SELECT MAX( coaching_turn ) AS max_c_turn FROM  `gn_coaching_info`  WHERE `coach_id` = '{$insert_coach_id}' AND `coty_id` = '{$insert_coty_id}' )  AND `coach_id` = '{$insert_coach_id}' AND `coty_id` = '{$insert_coty_id}';";
    $res_1 = mysqli_query($self_con, $sql_1);
    $coaching = mysqli_fetch_array($res_1);
    $max_coaching_date = $coaching['coaching_date'];
    $min_date = date('Y-m-d H:i:s', strtotime($max_coaching_date));
    //echo "<br>";
    //코칭날자
    if (strtotime($min_date) > strtotime($insert_coaching_date)) {
        echo "<script>alert('코칭시간 오류! 마지막으로 진행한 코칭시간 : " . date('Y-m-d H:i:s', strtotime($min_date)) . "');window.history.back(); </script>";
        exit;
    }
    $insert_coaching_time  = $_POST['coaching_time'];
    $insert_coach_value = $_POST['coach_value'];
    $insert_coach_comment = $_POST['coach_comment'];
    $insert_home_work = $_POST['home_work'];
    $insert_search_text = $insert_coty_id . " | " . $insert_coaching_title . " | " . $insert_coaching_content . " | " . $insert_home_work . " | " . $insert_coach_comment;
    //업데이트일때 아이디
    $update_coaching_id = $_POST['update_coaching_id'];
    //코티 정보 얻기
    $sql = "select * from gn_coaching_apply a inner join Gn_Member b on a.mem_code = b.mem_code where coty_id='{$insert_coty_id}' and coach_id='{$insert_coach_id}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $g_coty_row = mysqli_fetch_array($result);

    //코치 정보 얻기
    $sql = "select * from gn_coach_apply a inner join Gn_Member b on a.mem_code = b.mem_code where coach_id='{$insert_coach_id}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $g_coach_row = mysqli_fetch_array($result);
    $insert_search_text =  $insert_search_text . " | " . $g_coach_row['mem_name'] . " | " . $g_coty_row['mem_name'];
    //코칭정보테이블에서 이미 등록된 코칭이 있는가 체크
    $sql = "select *,count(coaching_id) as cnt from gn_coaching_info where coty_id='{$insert_coty_id}' and coach_id='{$insert_coach_id}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $row = mysqli_fetch_array($result);
    $count = $row['cnt']; //코칭회차 결정
    if ($count == 0) { //첫 코칭정보등록
        //코칭회차 등록
        $insert_coaching_turn = 1;
        //시작시간 등록
        $insert_start_date = $insert_coaching_date;
        //끝시간
        $insert_end_date = date('Y-m-d H:i:s', strtotime('+' . $g_coty_row['cont_term'] . ' day', strtotime(date($insert_start_date))));
    } else {
        //코칭회차
        $insert_coaching_turn = $count + 1;
        //코칭회차가 1이 데이터 얻기
        $sql = "select * from gn_coaching_info where coty_id='{$insert_coty_id}' and coach_id='{$insert_coach_id}' and coaching_turn = '1'";
        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $row = mysqli_fetch_array($result);

        //시작시간
        $insert_start_date = $row['start_date'];
        //끝시간
        $insert_end_date = $row['end_date'];
    }
    //코칭날자
    if ($insert_end_date < $insert_coaching_date) {
        echo "<script>alert('기간초과! 코칭종료시간 : " . $insert_end_date . "');window.history.back(); </script>";
        exit;
    }
    //잔여시간 초과 체크 --------
    $coty_cont_time = $g_coty_row['cont_time'] * 60; //계약 분
    $sql = "select sum(coaching_time) as time_sum from gn_coaching_info where coty_id='{$insert_coty_id}' and coach_id='{$insert_coach_id}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $row = mysqli_fetch_array($result);
    $insert_coaching_time_sum = $row['time_sum']; //이 계열의 진행한 코칭의 총 시간
    //잔여시간
    $remain_time = $coty_cont_time - $insert_coaching_time_sum;
    if ($update_coaching_id) {
        $sql = "select coaching_time from gn_coaching_info where coaching_id='" . $update_coaching_id . "'";
        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $row = mysqli_fetch_array($result);
        $update_coaching_time = $row['coaching_time']; // 갱신할 코칭의 코칭타임

        $remain_time +=   $update_coaching_time;
    }
    //새로 창조하는 코칭일때 잔여시간을 초과하면 
    if (($remain_time - $insert_coaching_time) < 0) {
        echo "<script>alert('시간초과! 잔여시간 " . $remain_time . "분');window.history.back(); </script>";
        exit;
    }
    //코칭 화일 보관 -----------
    $insert_coaching_file = "";
    if ($_FILES['coaching_file']['name'] != "") {
        $target_path = "/cauching_uploads/";
        $save_file_name = $insert_coach_id . "_" . $insert_coty_id . "_" . $insert_coaching_turn . "_file";
        $real_file_name = (basename($_FILES['coaching_file']['name']));
        $real_file_ext = pathinfo($real_file_name, PATHINFO_EXTENSION);
        $save_file_name = $save_file_name . "." . $real_file_ext;
        $target_path = $target_path . $save_file_name;
        $insert_coaching_file = $target_path;
        $target_path = $_SERVER['DOCUMENT_ROOT'] . $target_path;
        if (move_uploaded_file($_FILES['coaching_file']['tmp_name'], $target_path)) {
            $handle = new Image("cauching_uploads/" . $save_file_name, 800);
            $handle->resize();
            uploadFTP("cauching_uploads/" . $save_file_name);
            //echo "<script>alert('".$target_path." File uploaded successfully!');</script>";
        } else {
            //echo "<script>alert('".$target_path." Sorry, file not uploaded, please try again!');</script>";
        }
    }
    //-------------------------
    $coaching_status = 1; //진행중
    if ($update_coaching_id) {
        //업뎃 update Gn_landing set short_url='{$transUrl}' where landing_idx='{$landing_idx}'
        $sql = "update gn_coaching_info set coty_id='{$insert_coty_id}',
                                        coty_mem_code='{$insert_coty_mem_code}',
                                        coaching_date='{$insert_coaching_date}',
                                        coaching_time='{$insert_coaching_time}',
                                        coaching_title='{$insert_coaching_title}',
                                        coaching_content='{$insert_coaching_content}',
                                        coaching_file='{$insert_coaching_file}',
                                        coach_value='{$insert_coach_value}',
                                        home_work='{$insert_home_work}',
                                        coach_comment='{$insert_coach_comment}',
                                        search_text='{$insert_search_text}',
                                        start_date='{$insert_start_date}',
                                        end_date='{$insert_end_date}'
                                        where coaching_id = $update_coaching_id";
        //coaching_time='{$insert_coaching_time}',
    } else {
        //크리트
        $sql = "insert into gn_coaching_info set coty_id='{$insert_coty_id}',
                                        coty_mem_code='{$insert_coty_mem_code}',
                                        coach_id='{$insert_coach_id}',
                                        coach_mem_code='{$insert_coach_mem_code}',
                                        coaching_turn='{$insert_coaching_turn}',
                                        coaching_date='{$insert_coaching_date}',
                                        coaching_time='{$insert_coaching_time}',
                                        coaching_title='{$insert_coaching_title}',
                                        coaching_content='{$insert_coaching_content}',
                                        coaching_file='{$insert_coaching_file}',
                                        coach_value='{$insert_coach_value}',
                                        home_work='{$insert_home_work}',
                                        reg_date=now(),
                                        coach_comment='{$insert_coach_comment}',
                                        search_text='{$insert_search_text}',
                                        coaching_status = '{$coaching_status}',
                                        past_time_sum='{$insert_coaching_time_sum}',
                                        agree='0',
                                        start_date='{$insert_start_date}',
                                        end_date='{$insert_end_date}'";
    }
    $result = mysqli_query($self_con, $sql);
    $coaching_info_idx = mysqli_insert_id($self_con);
    echo "<script>location='mypage_coaching_list.php';</script>";
    exit;
} else if ($mode == "delete_coaching_info") {
    $coaching_id = $_POST['coaching_id'];
    $sql = "select * from gn_coaching_info where coaching_id=$coaching_id";
    $result_num = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $coaching_info_data = mysqli_fetch_array($result_num);
    $coty_id = $coaching_info_data['coty_id'];
    $coach_id = $coaching_info_data['coach_id'];
    $current_coaching_turn = $coaching_info_data['coaching_turn'];
    $sql = "select *,count(*) as cnt from gn_coaching_info where coty_id='" . $coty_id . "' and coach_id='" . $coach_id . "' ";
    $result_num = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $coaching_info_data = mysqli_fetch_array($result_num);
    //코칭개수과 코칭턴이 같을때 즉 마지막 코칭이라면
    if ($coaching_info_data['cnt'] ==  $current_coaching_turn) {
        $sql = "delete from gn_coaching_info where coaching_id=$coaching_id";
        $result = mysqli_query($self_con, $sql);

        echo "삭제되었습니다.";
    } else {

        echo "중간 코칭정보는 삭제할수 없습니다.";
    }
    // /echo "<script>alert('삭제되었습니다.');location='mypage_coaching_list.php';</script>";
    exit;
} else if ($mode == "update_coaching_info_coty_comment") {
    $coty_value = $_POST['coty_value'];
    $coty_comment = $_POST['coty_comment'];
    $coaching_id = $_POST['coaching_id'];
    $sql = "update gn_coaching_info set coty_value='{$coty_value}',
                                    coty_comment='{$coty_comment}',
                                    agree=1,coaching_status=2 
                                     where coaching_id = $coaching_id";
    echo $sql;
    $result = mysqli_query($self_con, $sql);
    exit;
} else if ($mode == "update_coaching_info_site_comment") {
    $site_value = $_POST['site_value'];
    $site_comment = $_POST['site_comment'];
    $coaching_id = $_POST['coaching_id'];
    $sql = "update gn_coaching_info set site_value='{$site_value}',
                                    site_comment='{$site_comment}'
                                     where coaching_id = $coaching_id
                                    ";

    echo $sql;
    $result = mysqli_query($self_con, $sql);
    exit;
}
//코치신청
else if ($mode == "create_coach_apply") {
    $sql = "select * from Gn_Member  where mem_id='{$_SESSION['one_member_id']}' and site != ''";
    $result_num = mysqli_query($self_con, $sql);
    $data = mysqli_fetch_array($result_num);
    $sql = "insert into gn_coach_apply set mem_code='" . $data['mem_code'] . "',  reg_date=now() , agree= 0, coach_type=0";
    echo $sql;
    $result = mysqli_query($self_con, $sql);
    $coach_apply_idx = mysqli_insert_id($self_con);
    echo "<script>location='mypage_coaching_list.php';</script>";
    exit;
}
//코티가 코칭신청 (수강신청)
else if ($mode == "create_coaching_apply") {
    $cont_term = $_POST['cont_term'];
    $cont_time = $_POST['cont_time'];
    $want_coaching = $_POST['coaching_want'];
    $coaching_price = $_POST['coaching_price'];
    $sql = "select * from Gn_Member  where mem_id='{$_SESSION['one_member_id']}' and site != ''";
    $result_num = mysqli_query($self_con, $sql);
    $data = mysqli_fetch_array($result_num);
    $sql = "insert into gn_coaching_apply set mem_code='" . $data['mem_code'] . "' , reg_date=now() ,cont_term='" . $cont_term . "',want_coaching='" . $want_coaching . "' ,cont_time='" . $cont_time . "' ,coaching_price='" . $coaching_price . "', agree =0 ";
    $result = mysqli_query($self_con, $sql);
    $coach_apply_idx = mysqli_insert_id($self_con);
    echo "<script>location='mypage_coaching_list.php';</script>";
    exit;
}
//코티가 코칭신청 (수강신청)
else if ($mode == "read_coaching_apply") {
    $coty_id = $_POST['coty_id'];
    $coach_id = $_POST['coach_id'];
    $sql = "select * from gn_coaching_apply a inner join Gn_Member b on a.mem_code=b.mem_code where a.coty_id='" . $coty_id . "' ";
    $result_num = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $data = mysqli_fetch_array($result_num);

    $sql = "select * from gn_coaching_info where coty_id='" . $coty_id . "' and coach_id='" . $coach_id . "' ";

    $result_num = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $coaching_info_data = mysqli_fetch_array($result_num);

    $currentTime = date("Y-m-d H:i:s");

    if ($currentTime < $coaching_info_data['start_date']) {
        $coaching_status = "대기";
    } else if ($currentTime > $coaching_info_data['start_date'] && $currentTime < $coaching_info_data['end_date']) {
        $coaching_status = "진행중";
    } else if ($currentTime > $coaching_info_data['end_date']) {
        $coaching_status = "종료";
    }
    if (!$coaching_info_data['end_date']) {
        $coaching_status = "대기";
    }
    $sql = "select count(*) as cnt from gn_coaching_info where coty_id='" . $coty_id . "' and coach_id='" . $coach_id . "' ";
    $result_num = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $coaching_info_data_count = mysqli_fetch_array($result_num);
    echo json_encode(array("coaching_apply_data" => $data, "coaching_info_data" => $coaching_info_data, "coaching_status" => $coaching_status, "coaching_info_data_count" => $coaching_info_data_count));
    exit;
} else if ($mode == "reservation_update") {

    if ($start_index == "") {
        echo "<script>alert('시작건수를 입력해주세요.');location='mypage_oldrequest_list.php';</script>";
        exit;
    }
    if ($start_index < 1 || !preg_match("/[0-9]{1,3}/", $start_index, $m)) {
        echo "<script>alert('시작건수를 정확히 입력해주세요.');location='mypage_oldrequest_list.php';</script>";
        exit;
    }
    if ($end_index == "") {
        echo "<script>alert('종료건수를 입력해주세요.');location='mypage_oldrequest_list.php';</script>";
        exit;
    }
    if (!preg_match("/[0-9]{1,3}/", $end_index, $m)) {
        echo "<script>alert('종료건수를 정확히 입력해주세요.');location='mypage_oldrequest_list.php';</script>";
        exit;
    }
    $cnt = $end_index - $start_index + 1;
    if ($cnt < 0 || $cnt > 500) {
        echo "<script>alert('시작건수와 종료건수를 다시 확인해주세요.');location='mypage_oldrequest_list.php';</script>";
        exit;
    }

    //기존에 설정되어 있던 예약문자들을 삭제한다.
    $query = "delete from Gn_MMS where or_id='{$idx}' and up_date is null ";
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));


    $query = "update Gn_event_oldrequest set sms_idx = '{$step_idx}',
                                            reservation_title = '{$reservation_title}',
                                            address_idx = '{$group_idx}',
                                            addr_start_index = '{$start_index}',
                                            addr_end_index = '{$end_index}',
                                            start_date=NOW(),
                                            status = 'Y',
                                            reg_date = NOW() where idx = $idx";
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));

    $start_index -= 1;

    $sql = "SELECT recv_num FROM Gn_MMS_Receive WHERE grp_id = '{$group_idx}' limit $start_index, $cnt"; //test
    $gresult = mysqli_query($self_con, $sql);

    $num_arr = array();
    while ($mms_receive = mysqli_fetch_array($gresult)) {
        array_push($num_arr, $mms_receive['recv_num']);
    }
    $recv_num = implode(",", $num_arr);

    $sql = "select * from Gn_event_sms_info where sms_idx='{$step_idx}'";
    $lresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    while ($lrow = mysqli_fetch_array($lresult)) {
        $mem_id = $lrow['m_id'];
        $sms_idx = $lrow['sms_idx'];
        $reg = time();
        $sql = "select * from Gn_event_sms_step_info where sms_idx='{$sms_idx}'";
        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

        while ($row = mysqli_fetch_array($result)) {
            $k++;
            $send_day = $row['send_day'];
            $send_time = $row['send_time'];

            if ($send_time == "") $send_time = "09:30";
            if ($send_time == "00:00") $send_time = "09:30";
            if ($send_day == "0")
                $reservation = "";
            else
                $reservation = date("Y-m-d $send_time:00", strtotime("+$send_day days"));

            $jpg = $jpg1 = $jpg2 = '';
            if ($row['image'])
                $jpg = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image'];
            if ($row['image1'])
                $jpg1 = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image1'];
            if ($row['image2'])
                $jpg2 = "http://www.kiam.kr/adjunct/mms/thum/" . $row['image2'];

            sendmms(8, $mem_id, $mobile, $recv_num, $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, "Y", $row['sms_idx'], $row['sms_detail_idx'], "", "", "", $idx);
        }
    }

    if ($reservation == "")
        $query = "update Gn_event_oldrequest set end_date=NOW() where idx = '{$idx}'";
    else
        $query = "update Gn_event_oldrequest set end_date='{$reservation}' where idx = '{$idx}'";
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));

    echo "<script>location='mypage_oldrequest_list.php';</script>"; //test
    exit;
}
//코티가 코칭신청 (수강신청)
else if ($mode == "review_img_del") {

    $lecture_id = $_POST['lecture_id'];
    $no = $_POST['review_img_no'];

    $sql = "update  Gn_lecture set review_img" . $no . "='' where lecture_id='{$lecture_id}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    echo "삭제되었습니다.";
    exit;
}
