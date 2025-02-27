<?
include_once "./lib/rlatjd_fun.php";
include_once "./lib/class.image.php";
extract($_REQUEST);
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
    $sql = "SELECT * FROM Gn_event use index(event_name_eng) WHERE event_name_eng='{$pcode}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $event_data = $row = mysqli_fetch_array($result);
    $sp = $event_data['pcode'];
    $transUrl = str_replace("http:", "https:", $transUrl);
    $sql = "INSERT INTO Gn_landing SET title='{$title}',
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
    $sql = "UPDATE Gn_landing SET short_url='{$transUrl}' WHERE landing_idx='{$landing_idx}'";
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
        $sql = "SELECT * FROM Gn_event WHERE event_name_eng='{$pcode}'";
        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $event_data = $row = mysqli_fetch_array($result);
        $sp = $event_data['pcode'];
        $transUrl = "https://" . $HTTP_HOST . "/event/event.html?pcode=$pcode&sp=$sp&landing_idx=$landing_idx";
        $transUrl = get_short_url($transUrl);
        $add = "short_url='{$transUrl}',";
    }

    $content  = str_replace("'", "\"", $ir1);
    $sql = "UPDATE Gn_landing SET title='{$title}',
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
                               WHERE   landing_idx='{$landing_idx}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    echo "<script>location='mypage_landing_list.php';</script>";
    exit;
} else if ($mode == "land_updat_status") {
    $sql = "UPDATE Gn_landing SET status_yn='{$status}' WHERE landing_idx='{$landing_idx}'";
    $result = mysqli_query($self_con, $sql);
    echo "<script>alert('삭제되었습니다.');location='mypage_landing_list.php';</script>";
    exit;
} else if ($mode == "land_del") {
    if (is_array($landing_idx) == true) {
        $idx = explode(",", $landing_idx);

        $idx_array = "'" . implode("','", $idx) . "'";

        $sql = "DELETE FROM Gn_landing WHERE landing_idx in ($idx_array) and m_id ='{$_SESSION['one_member_id']}'";
        $result = mysqli_query($self_con, $sql);
    } else {
        $sql = "DELETE FROM Gn_landing WHERE landing_idx='{$landing_idx}' and m_id ='{$_SESSION['one_member_id']}'";
        $result = mysqli_query($self_con, $sql);
    }
    echo "<script>alert('삭제되었습니다.');location='mypage_landing_list.php';</script>";
    exit;
} else if ($mode == "event_check") {
    $sql = "SELECT event_name_eng FROM Gn_event WHERE event_name_eng='{$event_name_eng}'";
    $result = mysqli_query($self_con, $sql);
    $row = mysqli_fetch_array($result);

    if ($row['event_name_eng'] == "") {
        echo json_encode(array("result" => "success", "msg" => "사용 가능합니다."));
    } else {
        echo json_encode(array("result" => "fail", "msg" => "중복된 값이 있습니다."));
    }

    exit;
} else if ($mode == "event_del") {
    $sql = "delete FROM Gn_event WHERE event_idx='{$event_idx}' and m_id ='{$_SESSION['one_member_id']}'";
    //echo $sql;
    $result = mysqli_query($self_con, $sql);
    echo "<script>alert('삭제되었습니다.');location='mypage_event_list.php';</script>";
    exit;
} else if ($mode == "event_save") {
    if ($_POST['event_info'] != "")
        $event_info = implode(",", $_POST['event_info']);
    else
        $event_info = "";
    $event_name_eng = $pcode = generateRandomString(10);
    $sql = "INSERT INTO Gn_event SET event_name_kor='{$event_name_kor}',
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
                                     event_req_link='{$event_req_link}'";
    if ($step_idx1 != "")
        $sql .= " ,sms_idx1='{$step_idx1}'";
    if ($step_idx2 != "")
        $sql .= " ,sms_idx2='{$step_idx2}'";
    if ($step_idx3 != "")
        $sql .= " ,sms_idx3='{$step_idx3}'";
    if ($stop_event_idx != "")
        $sql .= " ,stop_event_idx='{$stop_event_idx}'";
    if ($reserv_type != "")
        $sql .= " ,reserv_type='{$reserv_type}'";
    if ($file_cnt != "")
        $sql .= " ,file_cnt='{$file_cnt}'";
    $result = mysqli_query($self_con, $sql);
    $event_idx = mysqli_insert_id($self_con);

    $transUrl = "https://" . $HTTP_HOST . "/event/event.html?pcode=$pcode&sp=$event_name_eng";
    $transUrl = get_short_url($transUrl);
    $sql = "UPDATE Gn_event SET short_url='{$transUrl}' WHERE event_idx='{$event_idx}'";
    $result = mysqli_query($self_con, $sql);
    //echo "<script>location='mypage_link_list.php';</script>";
    echo "<script>alert('성공적으로 저장되었습니다.');location.replace('/mypage_link_write.php?event_idx={$event_idx}');</script>";
    exit;
} else if ($mode == "event_update") {
    if (isset($_POST['event_info']))
        $event_info = implode(",", $_POST['event_info']);
    else
        $event_info = "";
    $sql = "UPDATE Gn_event SET event_name_kor='{$event_name_kor}',
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
                                stop_event_idx='{$stop_event_idx}'";
    if ($reserv_type != "")
        $sql .= " ,reserv_type='{$reserv_type}'";
    if ($file_cnt != "")
        $sql .= " ,file_cnt='{$file_cnt}'";
    $sql .= " WHERE event_idx='{$event_idx}'";
    $result = mysqli_query($self_con, $sql);
    $transUrl = "https://" . $HTTP_HOST . "/event/event.html?pcode=$pcode&sp=$event_name_eng";
    $transUrl = get_short_url($transUrl);
    $sql = "UPDATE Gn_event SET short_url='{$transUrl}' WHERE event_idx='$event_idx '";
    $result = mysqli_query($self_con, $sql);
    //echo "<script>location='mypage_link_list.php';</script>";
    echo "<script>alert('성공적으로 저장되었습니다.');location.replace('/mypage_link_write.php?event_idx={$event_idx}');</script>";
    exit;
} else if ($mode == "sms_save") {
    if ($event_idx == "") {
        $sql = "SELECT * FROM Gn_event WHERE event_name_eng='{$event_name_eng}'";
        $eresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $erow = mysqli_fetch_array($eresult);
        $event_idx = $erow['event_idx'];
    }
    if ($event_idx == "")
        $event_idx = 0;
    if ($reserv_type == 1) {
        $ai_file1 = $_FILES['ai_file1']['tmp_name'];
        $ai_file2 = $_FILES['ai_file2']['tmp_name'];
        $ai_file3 = $_FILES['ai_file3']['tmp_name'];
        $fquery = "";
        if ($ai_file1) {
            $file_arr = explode(".", $_FILES['ai_file1']['name']);
            $tmp_file_arr = explode("/", $ai_file1);
            $file_name = "ai_ms_".date("Ymds") . "." . $file_arr[count($file_arr) - 1];
            $upload_file = "upload/" . $file_name;
            if (move_uploaded_file($_FILES['ai_file1']['tmp_name'], $upload_file)) {
                uploadFTP($upload_file);
                $fquery .= " ,ai_file1='{$upload_file}' ";
            }
        }
        if ($ai_file2) {
            $file_arr = explode(".", $_FILES['ai_file2']['name']);
            $tmp_file_arr = explode("/", $ai_file1);
            $file_name = "ai_ms_".date("Ymds") . "." . $file_arr[count($file_arr) - 1];
            $upload_file = "upload/" . $file_name;
            if (move_uploaded_file($_FILES['ai_file2']['tmp_name'], $upload_file)) {
                uploadFTP($upload_file);
                $fquery .= " ,ai_file2='{$upload_file}' ";
            }
        }
        if ($ai_file3) {
            $file_arr = explode(".", $_FILES['ai_file3']['name']);
            $tmp_file_arr = explode("/", $ai_file1);
            $file_name = "ai_ms_".date("Ymds") . "." . $file_arr[count($file_arr) - 1];
            $upload_file = "upload/" . $file_name;
            if (move_uploaded_file($_FILES['ai_file3']['tmp_name'], $upload_file)) {
                uploadFTP($upload_file);
                $fquery .= " ,ai_file3='{$upload_file}' ";
            }
        }
        $sql = "INSERT INTO Gn_aievent_ms_info SET event_idx='{$event_idx}',
                                     event_name_eng='{$event_name_eng}',
                                     reservation_title='{$reservation_title}',
                                     reservation_desc='{$reservation_desc}',
                                     mobile='{$mobile}',
                                     regdate=NOW(),
                                     ai_step='{$ai_step}',
                                     ai_day='{$ai_day}',
                                     ai_hour='{$ai_hour}',
                                     ai_prompt='{$ai_prompt}',
                                     m_id='{$_SESSION['one_member_id']}'
                                     $fquery";
        $result = mysqli_query($self_con, $sql);
        $sms_idx = mysqli_insert_id($self_con);
        if ($mb_id_copy != "") {
            $sql_step_info = "INSERT INTO Gn_aievent_message(sms_idx, step, send_day, send_time, title, content, image, image1, image2, regdate, send_deny) 
                                    (SELECT {$sms_idx}, step, send_day, send_time, title, content, image, image1, image2, now(), send_deny FROM Gn_aievent_message WHERE sms_idx={$ori_sms_idx})";
            mysqli_query($self_con, $sql_step_info);
        }
    } else {
        $sql = "INSERT INTO Gn_event_sms_info SET event_idx='{$event_idx}',
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
    }
    echo "<script>location='mypage_reservation_create.php?sms_idx=$sms_idx&reserv_type=$reserv_type';</script>";
    exit;
} else if ($mode == "sms_update") {
    if ($reserv_type == 1) {
        $ai_file1 = $_FILES['ai_file1']['tmp_name'];
        $ai_file2 = $_FILES['ai_file2']['tmp_name'];
        $ai_file3 = $_FILES['ai_file3']['tmp_name'];
        $fquery = "";
        if ($ai_file1) {
            $file_arr = explode(".", $_FILES['ai_file1']['name']);
            $tmp_file_arr = explode("/", $ai_file1);
            $file_name = "ai_ms_".date("Ymds") . "." . $file_arr[count($file_arr) - 1];
            $upload_file = "upload/" . $file_name;
            if (move_uploaded_file($_FILES['ai_file1']['tmp_name'], $upload_file)) {
                uploadFTP($upload_file);
                $fquery .= " ,ai_file1='{$upload_file}' ";
            }
        }else{
            $fquery .= " ,ai_file1='{$ai_file1_txt}' ";
        }
        if ($ai_file2) {
            $file_arr = explode(".", $_FILES['ai_file2']['name']);
            $tmp_file_arr = explode("/", $ai_file1);
            $file_name = "ai_ms_".date("Ymds") . "." . $file_arr[count($file_arr) - 1];
            $upload_file = "upload/" . $file_name;
            if (move_uploaded_file($_FILES['ai_file2']['tmp_name'], $upload_file)) {
                uploadFTP($upload_file);
                $fquery .= " ,ai_file2='{$upload_file}' ";
            }
        }else{
            $fquery .= " ,ai_file2='{$ai_file2_txt}' ";
        }
        if ($ai_file3) {
            $file_arr = explode(".", $_FILES['ai_file3']['name']);
            $tmp_file_arr = explode("/", $ai_file1);
            $file_name = "ai_ms_".date("Ymds") . "." . $file_arr[count($file_arr) - 1];
            $upload_file = "upload/" . $file_name;
            if (move_uploaded_file($_FILES['ai_file3']['tmp_name'], $upload_file)) {
                uploadFTP($upload_file);
                $fquery .= " ,ai_file3='{$upload_file}' ";
            }
        }else{
            $fquery .= " ,ai_file3='{$ai_file3_txt}' ";
        }
        
        $sql = "UPDATE Gn_aievent_ms_info SET event_idx='{$event_idx}',
                                     event_name_eng='{$event_name_eng}',
                                     reservation_title='{$reservation_title}',
                                     reservation_desc='{$reservation_desc}',
                                     mobile='{$mobile}',
                                     regdate=NOW(),
                                     ai_step='{$ai_step}',
                                     ai_day='{$ai_day}',
                                     ai_hour='{$ai_hour}',
                                     ai_prompt='{$ai_prompt}',
                                     m_id='{$_SESSION['one_member_id']}'
                                     $fquery
                               WHERE sms_idx='{$sms_idx}'";
        $result = mysql_query($sql);


        ///////////////////////////////////////////////////////////////////Generate and save ai message///////////////////////////////////////////////////////////////////////////
        
        $url = "http://112.170.57.176:8080/get_ai_story.php";
        $data = array(
            'prompt' => $ai_prompt,
            'profile' => '',
            'round' => $ai_step
        );
        
        // URL-encoded 쿼리 문자열로 변환
        $postFields = $data;
    
        // cURL 세션 초기화
        $ch = curl_init($url);
    
        // cURL 옵션 설정
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_TIMEOUT, 300000);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        
        // 요청 실행 및 응답 받기
        $response = curl_exec($ch);
        
        // 오류 체크
        if (curl_errno($ch)) {
            //echo 'cURL error: ' . curl_error($ch);
            return "";
        } 
        // cURL 세션 닫기
        curl_close($ch); 
        $filter_response = str_replace("'", " ", $response);
        $array_ai_data = explode('/////', $filter_response);
        
        try {
            $step = 1;
            
            foreach($array_ai_data as $ai_message){
                $sql_ai_text = "INSERT INTO gn_aievent_message SET sms_idx='{$sms_idx}',
                                         step='{$step}',
                                         title='{$reservation_title}',
                                         content='{$ai_message}',
                                         send_day='{$ai_day}',
                                         send_time='{$ai_hour}',
                                         regdate=NOW()";
                
                
                mysql_query($sql_ai_text);
                $step++; 
    
            }
            
        } catch (Exception $ex) {
            //echo $ex;
        }

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        if ($mb_id_copy != "") {
            $sql_chk = "SELECT count(sms_detail_idx) as cnt FROM Gn_aievent_message WHERE sms_idx={$sms_idx}";
            $res_chk = mysqli_query($self_con, $sql_chk);
            $row_chk = mysqli_fetch_array($res_chk);
            if ($row_chk['cnt'] != 0) {
                $sql_del = "DELETE FROM Gn_aievent_message WHERE sms_idx={$sms_idx}";
                mysqli_query($self_con, $sql_del);
            }
            $sql_step_info = "INSERT INTO Gn_aievent_message(sms_idx, step, send_day, send_time, title, content, image, image1, image2, regdate, send_deny) 
                                    (SELECT '{$sms_idx}', step, send_day, send_time, title, content, image, image1, image2, now(), send_deny FROM Gn_aievent_message WHERE sms_idx={$ori_sms_idx})";
            mysqli_query($self_con, $sql_step_info);
        }
    } else {
        $sql = "UPDATE Gn_event_sms_info SET event_idx='{$event_idx}',
                                     event_name_eng='{$event_name_eng}',
                                     reservation_title='{$reservation_title}',
                                     reservation_desc='{$reservation_desc}',
                                     mobile='{$mobile}',
                                     regdate=NOW(),
                                     m_id='{$_SESSION['one_member_id']}'
                               WHERE sms_idx='{$sms_idx}'";
        $result = mysqli_query($self_con, $sql);

        if ($mb_id_copy != "") {
            $sql_chk = "SELECT count(sms_detail_idx) as cnt FROM Gn_event_sms_step_info WHERE sms_idx={$sms_idx}";
            $res_chk = mysqli_query($self_con, $sql_chk);
            $row_chk = mysqli_fetch_array($res_chk);
            if ($row_chk['cnt'] != 0) {
                $sql_del = "DELETE FROM Gn_event_sms_step_info WHERE sms_idx={$sms_idx}";
                mysqli_query($self_con, $sql_del);
            }
            $sql_step_info = "INSERT INTO Gn_event_sms_step_info(sms_idx, step, send_day, send_time, title, content, image, image1, image2, regdate, send_deny) 
                                    (SELECT '{$sms_idx}', step, send_day, send_time, title, content, image, image1, image2, now(), send_deny FROM Gn_event_sms_step_info WHERE sms_idx={$ori_sms_idx})";
            mysqli_query($self_con, $sql_step_info);
        }
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
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name;
            } else if ($size[1] > 480) {
                $xsize = $size[0] * (480 / $size[1]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name, "", $xsize, 480, "");
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name;
            } else {
                copy($upload_file, "adjunct/mms/thum/" . $file_name);
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name;
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
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name1;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name1;
            } else if ($size[1] > 480) {
                $xsize = $size[0] * (480 / $size[1]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name1, "", $xsize, 480, "");
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name1;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name1;
            } else {
                copy($upload_file, "adjunct/mms/thum/" . $file_name1);
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name1;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name1;
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
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name2;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name2;
            } else if ($size[1] > 480) {
                $xsize = $size[0] * (480 / $size[1]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name2, "", $xsize, 480, "");
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name2;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name2;
            } else {
                copy($upload_file, "adjunct/mms/thum/" . $file_name2);
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name2;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name2;
            }
        }
    }
    if ($send_deny_msg == "on") {
        $deny = "Y";
    } else {
        $deny = "";
    }
    $send_time = sprintf("%02d", $send_time_hour) . ":" . sprintf("%02d", $send_time_min);
    $sql = "UPDATE  Gn_event_sms_step_info SET title='{$title}',
                                                content='{$content}',
                                                $addQuery
                                                send_day='{$send_day}',
                                                send_time='{$send_time}',
                                                step='{$step}',
                                                send_deny='{$deny}'
                               WHERE   sms_detail_idx='{$sms_detail_idx}'";
    $result = mysqli_query($self_con, $sql);
    if ($send_time == "") $send_time = "09:30";
    if ($send_time == "00:00") $send_time = "09:30";
    $curDate =  new DateTime();
    //기존고객퍼널발송 마감일 수정
    $sql_old_req = "SELECT * FROM Gn_event_oldrequest WHERE sms_idx={$sms_idx}";
    $res_old_req = mysqli_query($self_con, $sql_old_req);
    while ($row_old_req = mysqli_fetch_array($res_old_req)) {
        $start_date = $row_old_req['start_date'];
        $send_time_array = explode(":", $send_time);
        $reserveDate = new DateTime($start_date);
        $reserveDate->setTime($send_time_array[0], $send_time_array[1]);
        $reserveDate->modify('+' . $send_day . ' day');

        $reserveDateOld = new DateTime($row_old_req['end_date']);
        if ($reserveDateOld < $reserveDate) {
            $sql = "UPDATE Gn_event_oldrequest SET end_date = '{$reserveDate->format('Y-m-d H:i:s')}' WHERE idx={$row_old_req['idx']}";
            mysqli_query($self_con, $sql);
        }
    }
    //신청창퍼널발송마감일 수정
    $sql_event = "SELECT event_idx FROM Gn_event WHERE sms_idx1={$sms_idx} OR sms_idx2={$sms_idx} OR sms_idx3={$sms_idx} ";
    $res_event = mysqli_query($self_con, $sql_event) or die(mysqli_error($self_con));
    while ($row_event = mysqli_fetch_array($res_event)) {
        $sql_req = "SELECT request_idx,regdate,step_end_time FROM Gn_event_request WHERE event_idx = {$row_event['event_idx']} AND target <> ''";
        $res_req = mysqli_query($self_con, $sql_req);
        while ($row_req = mysqli_fetch_array($res_req)) {
            $start_date = $row_req['regdate'];
            $send_time_array = explode(":", $send_time);
            $reserveDate = new DateTime($start_date);
            $reserveDate->setTime($send_time_array[0], $send_time_array[1]);
            $reserveDate->modify('+' . $send_day . ' day');
            $reserveDateOld = new DateTime($row_req['step_end_time']);
            if ($reserveDateOld < $reserveDate) {
                $sql = "UPDATE Gn_event_request SET step_end_time = '{$reserveDate->format('Y-m-d H:i:s')}' WHERE request_idx={$row_req['request_idx']}";
                mysqli_query($self_con, $sql);
            }
        }
    }
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
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name;
            } else if ($size[1] > 480) {
                $xsize = $size[0] * (480 / $size[1]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name, "", $xsize, 480, "");
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name;
            } else {
                copy($upload_file, "adjunct/mms/thum/" . $file_name);
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name;
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
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name1;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name1;
            } else if ($size[1] > 480) {
                $xsize = $size[0] * (480 / $size[1]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name1, "", $xsize, 480, "");
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name1;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name1;
            } else {
                copy($upload_file, "adjunct/mms/thum/" . $file_name1);
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name1;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name1;
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
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name2;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name2;
            } else if ($size[1] > 480) {
                $xsize = $size[0] * (480 / $size[1]);
                thumbnail($upload_file, "adjunct/mms/thum/" . $file_name2, "", $xsize, 480, "");
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name2;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name2;
            } else {
                copy($upload_file, "adjunct/mms/thum/" . $file_name2);
                //$show_img = "http://www.kiam.kr/adjunct/mms/thum/" . $file_name2;
                $show_img = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $file_name2;
            }
        }
    }

    if ($send_deny_msg == "on") {
        $deny = "Y";
    } else {
        $deny = "";
    }
    $send_time = sprintf("%02d", $send_time_hour) . ":" . sprintf("%02d", $send_time_min);
    $sql = "INSERT INTO  Gn_event_sms_step_info SET title='{$title}',
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
    $sql = "INSERT INTO Gn_event_request SET landing_idx='{$landing_idx}',
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
                                           regdate=now(),
                                           step_end_time=now(),
                                           target='3'";
    $res1 = mysqli_query($self_con, $sql);
    $request_idx = mysqli_insert_id($self_con);
    $recv_num = $mobile;
    $mem_id = $event_data['m_id'];

    $sql = "SELECT sms_idx1, sms_idx2, sms_idx3, mobile FROM Gn_event WHERE event_idx='{$event_idx}'";
    $eresult = mysqli_query($self_con, $sql);
    $erow = mysqli_fetch_array($eresult);
    $send_num = $erow['mobile'];

    $sql = "SELECT m_id,sms_idx FROM Gn_event_sms_info WHERE sms_idx='{$erow['sms_idx1']}' or sms_idx='{$erow['sms_idx2']}' or sms_idx='{$erow['sms_idx3']}'";
    $lresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    while ($lrow = mysqli_fetch_array($lresult)) {
        $mem_id = $lrow['m_id'];
        $sms_idx = $lrow['sms_idx'];

        //알람등록
        $sel_step = 0;
        if ($_REQUEST['step_num'] != "") {
            $sql = "SELECT * FROM Gn_event_sms_step_info WHERE sms_idx='{$sms_idx}' and step>={$_REQUEST['step_num']}";
            $sel_step = 1;
            $sql_step_start = "SELECT send_day FROM Gn_event_sms_step_info WHERE sms_idx='{$sms_idx}' and step>={$_REQUEST['step_num']} order by send_day asc limit 1";
            $res_step_start = mysqli_query($self_con, $sql_step_start);
            $row_step_start = mysqli_fetch_array($res_step_start);
            $start_dif = $row_step_start['send_day'];
        } else {
            $sql = "SELECT * FROM Gn_event_sms_step_info WHERE sms_idx='{$sms_idx}'";
        }

        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $step_end_time = date("Y-m-d H:i:s");
        while ($row = mysqli_fetch_array($result)) {
            // 시간 확인
            $send_day = $row['send_day'];
            if ($sel_step) {
                $send_day = (int)$send_day - (int)$start_dif;
            }
            $send_time = $row['send_time'];

            if ($send_time == "") $send_time = "09:30";
            if ($send_time == "00:00") $send_time = "09:30";
            if ($send_day == "0") {
                $reservation = "";
                $jpg = $jpg1 = $jpg2 = '';
                if ($row['image'])
                    $jpg = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image'];
                if ($row['image1'])
                    $jpg1 = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image1'];
                if ($row['image2'])
                    $jpg2 = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image2'];
                sendmms(3, $mem_id, $send_num, $recv_num, $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, 'Y', $row['sms_idx'], $row['sms_detail_idx'], $request_idx, "", $row['send_deny']);

                $query = "INSERT INTO Gn_MMS_Agree SET mem_id='{$mem_id}',
                                                    send_num='{$send_num}',
                                                    recv_num='{$recv_num}',
                                                    content='{$row['content']}',
                                                    title='{$row['title']}',
                                                    jpg='{$jpg}',
                                                    jpg1='',
                                                    jpg2='',
                                                    up_date=NOW(),
                                                    reg_date=NOW(),
                                                    reservation='{$reservation}',
                                                    sms_idx='{$row['sms_idx']}',
                                                    sms_detail_idx='{$row['sms_detail_idx']}',
                                                    request_idx='{$request_idx}'";
                mysqli_query($self_con, $query) or die(mysqli_error($self_con));
            } else {
                $reservation = date("Y-m-d $send_time:00", strtotime("+$send_day days"));
                if ($step_end_time < $reservation)
                    $step_end_time = $reservation;
            }
        }
        $sql = "UPDATE Gn_event_request SET step_end_time='{$step_end_time}' WHERE request_idx = {$request_idx}";
        mysqli_query($self_con, $sql);
    }
    if ($join_yn == 'Y') {
        //회원가입
        $site_info = explode(".", $HTTP_HOST);
        $site_name = $site_info[0];
        if ($site_name == "www")
            $site_name = "kiam";
        $sql = "SELECT mem_id FROM Gn_Member WHERE mem_id='{$mobile}' and site != ''";
        $res = mysqli_query($self_con, $sql);
        $row = mysqli_fetch_array($res);
        if ($row['mem_id'] == "") {
            $passwd = substr($mobile, -4);
            //password()
            $query = "INSERT INTO Gn_Member SET mem_id='{$mobile}',
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
    $sql = "UPDATE Gn_event_request SET name='{$name}',
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
                                        edit_date=NOW()";
    if (isset($_REQUEST['sms_idx']) && $_REQUEST['sms_idx'] != "")
        $sql .= ",sms_idx='{$sms_idx}'";
    if (isset($_REQUEST['step_num']) && $_REQUEST['step_num'] != "")
        $sql .= ",sms_step_num='{$step_num}'";
    $sql .= " WHERE request_idx ='{$request_idx}'";
    $result = mysqli_query($self_con, $sql);
    echo "<script>location='mypage_request_list.php';</script>";
    exit;
} else if ($mode == "request_except_update") {
    $sql = "SELECT target FROM Gn_event_request WHERE request_idx ='{$request_idx}'";
    $res = mysqli_query($self_con, $sql);
    $row = mysqli_fetch_assoc($res);
    if ($except_status == "Y") {
        if ($row['target'] == '')
            $target = 100;
        else
            $target = 100 + $row['target'] * 1;
    } else {
        if ($row['target'] == 100)
            $target = '';
        else
            $target = $row['target'] * 1 - 100;
    }
    $sql = "UPDATE Gn_event_request SET target = '{$target}' WHERE request_idx ='{$request_idx}'";
    $result = mysqli_query($self_con, $sql);
    echo json_encode(array("result" => "success"));
    exit;
} else if ($mode == "request_del") {
    $sql = "DELETE FROM Gn_event_request WHERE request_idx ='{$request_idx}' and sp='{$org_event_code}'";
    $result = mysqli_query($self_con, $sql);
    echo json_encode(array("result" => "success"));
    exit;
} else if ($mode == "oldrequest_del") {
    $idxs = explode(",", $idx); // mms_agree_idx
    $org_event_codes = explode(",", $org_event_code); // mms_agree_idx
    $idx_count = count($idxs);
    for ($i = 0; $i < $idx_count; $i++) {
        $sql = "DELETE FROM Gn_MMS WHERE or_id='$idxs[$i]' and up_date is null ";
        mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

        $sql = "DELETE FROM Gn_event_oldrequest WHERE idx ='$idxs[$i]'";
        $result = mysqli_query($self_con, $sql);
    }
    echo json_encode(array("result" => "success"));
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
    $mms_idx = "";
    $mms_agree_idx = "";
    $grpId = $group_idx * -1;
    $event_idx = $event_idx_event;
    $query = "INSERT INTO Gn_event_oldrequest SET sms_idx = '{$step_idx}',
                    mem_id='{$_SESSION['one_member_id']}',
                    send_num = '{$send_num}',
                    reservation_title = '{$reservation_title}',
                    address_idx = '{$group_idx}',
                    addr_start_index = '{$start_index}',
                    addr_end_index = '{$end_index}',
                    start_date=NOW(),
                    status = 'Y',
                    reg_date = NOW()";
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));
    $or_id = mysqli_insert_id($self_con);

    $start_index -= 1;

    $sql = "SELECT recv_num FROM Gn_MMS_Receive WHERE grp_id = '{$group_idx}' limit {$start_index}, {$cnt}";
    $gresult = mysqli_query($self_con, $sql);

    $num_arr = array();
    while ($mms_receive = mysqli_fetch_array($gresult)) {
        array_push($num_arr, $mms_receive['recv_num']);
    }
    $recv_num = implode(",", $num_arr);
    $mem_id = $event_data['m_id'];
    $time = 60 - date("i");
    $reservation = "";
    $sql = "SELECT m_id,reservation_title FROM Gn_event_sms_info WHERE sms_idx='{$step_idx}'";
    $lresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    while ($lrow = mysqli_fetch_array($lresult)) {
        $mem_id = $lrow['m_id'];
        $sms_idx = $step_idx;
        $reservation_title = $lrow['reservation_title'];
        $sql = "SELECT * FROM Gn_event_sms_step_info WHERE sms_idx='{$sms_idx}'";
        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

        while ($row = mysqli_fetch_array($result)) {
            $send_day = $row['send_day'];
            $jpg = $jpg1 = $jpg2 = '';
            if ($row['image'])
                $jpg = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image'];
            if ($row['image1'])
                $jpg1 = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image1'];
            if ($row['image2'])
                $jpg2 = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image2'];
            if ($send_day == "0") {
                $reservation = "";
                sendmms(8, $mem_id, $send_num, $recv_num, $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, "Y", $row['sms_idx'], $row['sms_detail_idx'], "", "", "", $or_id);
            } else {
                $send_time = $row['send_time'];
                if ($send_time == "") $send_time = "09:30";
                if ($send_time == "00:00") $send_time = "09:30";
                $reservation = date("Y-m-d $send_time:00", strtotime("+$send_day days"));
            }
        }
    }

    if ($reservation == "")
        $query = "UPDATE Gn_event_oldrequest SET end_date=NOW() WHERE idx = '{$or_id}'";
    else
        $query = "UPDATE Gn_event_oldrequest SET end_date='{$reservation}' WHERE idx = '{$or_id}'";
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));

    echo "예약되었습니다.";
    exit;
} else if ($mode == "sms_detail_del") {
    $sql = "DELETE FROM Gn_event_sms_step_info WHERE sms_detail_idx ='{$sms_detail_idx}' and sms_idx ='{$sms_idx}'";
    $result = mysqli_query($self_con, $sql);
    echo json_encode(array("result" => "success"));
    exit;
} else if ($mode == "sms_detail_info") {
    $sql = "SELECT * FROM Gn_event_sms_step_info WHERE sms_detail_idx ='{$sms_detail_idx}' and sms_idx ='{$sms_idx}'";
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
        $sql = "DELETE FROM Gn_event_sms_info WHERE sms_idx in ($idx_array) and m_id ='{$_SESSION['one_member_id']}'";
        $result = mysqli_query($self_con, $sql);
    } else {
        if ($reserv_type == 1)
            $sql = "DELETE FROM Gn_aievent_ms_info WHERE sms_idx='{$sms_idx}' and m_id ='{$_SESSION['one_member_id']}'";
        else
            $sql = "DELETE FROM Gn_event_sms_info WHERE sms_idx='{$sms_idx}' and m_id ='{$_SESSION['one_member_id']}'";
        $result = mysqli_query($self_con, $sql);
    }
    echo "<script>alert('삭제되었습니다.');location='mypage_landing_list.php';</script>";
    exit;
} else if ($mode == "request_event_add") {
    for ($i = 0; $i < count($request_idx); $i++) {
        $idx =  $request_idx[$i];
        $query = "INSERT INTO Gn_event_request (m_id, event_idx, event_code, name, mobile, email, job, birthday, sex, addr, consult_date, join_yn, pcode, sp, ip_addr, regdate, sms_idx, sms_step_num,step_end_time,target)
                    SELECT m_id, '{$event_idx_}', '{$event_pcode_}', name, mobile, email, job, birthday, sex, addr, consult_date, join_yn, '{$event_pcode_}', '{$sp_}', ip_addr, now(), '{$sms_idx}', '{$step_num}',now(),target FROM Gn_event_request WHERE request_idx='{$idx}'";
        mysqli_query($self_con, $query);
    }
    $event_idx = $_POST['event_idx_'];
    $recv_num = $mobile;
    $mem_id = $m_id;

    //고객신청그룹에 설정된 퍼널예약들을 불러들인다.
    $sql = "SELECT sms_idx1, sms_idx2, sms_idx3, mobile FROM Gn_event WHERE event_idx='{$event_idx}'";
    $eresult = mysqli_query($self_con, $sql);
    $erow = mysqli_fetch_array($eresult);
    $send_num = $erow['mobile'];

    $sql = "SELECT m_id,sms_idx FROM Gn_event_sms_info WHERE sms_idx='{$erow['sms_idx1']}' or sms_idx='{$erow['sms_idx2']}' or sms_idx='{$erow['sms_idx3']}'";
    //echo $sql;
    $lresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $time = 60 - date("i");
    //echo date("H:i:00", strtotime("+$time min"));
    while ($lrow = mysqli_fetch_array($lresult)) {
        $mem_id = $lrow['m_id'];
        $sms_idx = $lrow['sms_idx'];
        //알람등록
        $sel_step = 0;
        if ($_REQUEST['step_num'] != "") {
            $sql = "SELECT * FROM Gn_event_sms_step_info WHERE sms_idx='{$sms_idx}' and step>={$_REQUEST['step_num']}";
            $sel_step = 1;
            $sql_step_start = "SELECT send_day FROM Gn_event_sms_step_info WHERE sms_idx='{$sms_idx}' and step>={$_REQUEST['step_num']} order by send_day asc limit 1";
            $res_step_start = mysqli_query($self_con, $sql_step_start);
            $row_step_start = mysqli_fetch_array($res);
            $start_dif = $row_step_start['send_day'];
        } else {
            $sql = "SELECT * FROM Gn_event_sms_step_info WHERE sms_idx='{$sms_idx}'";
        }
        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $step_end_time = date("Y-m-d H:i:s");
        while ($row = mysqli_fetch_array($result)) {
            // 시간 확인
            $send_day = $row['send_day'];
            if ($sel_step) {
                $send_day = (int)$send_day - (int)$start_dif;
            }
            $send_time = $row['send_time'];
            if ($send_time == "") $send_time = "09:30";
            if ($send_time == "00:00") $send_time = "09:30";
            if ($send_day == "0") {
                $reservation = "";
            } else {
                $reservation = date("Y-m-d $send_time:00", strtotime("+$send_day days"));
                if ($step_end_time < $reservation)
                    $step_end_time = $reservation;
            }
            $jpg = $jpg1 = $jpg2 = '';
            if ($row['image'])
                $jpg = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image'];
            if ($row['image1'])
                $jpg1 = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image1'];
            if ($row['image2'])
                $jpg2 = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image2'];

            for ($m = 0; $m < count($mobile); $m++) {
                sendmms(3, $mem_id, $send_num, $mobile[$m], $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, 'Y', $row['sms_idx'], $row['sms_detail_idx'], $request_idx[$m], "", $row['send_deny']);
                $query = "INSERT INTO Gn_MMS_Agree SET mem_id='{$mem_id}',
                                                send_num='{$send_num}',
                                                recv_num='$mobile[$m]',
                                                content='{$row['content']}',
                                                title='{$row['title']}',
                                                jpg='{$jpg}',
                                                jpg1='',
                                                jpg2='',
                                                up_date=NOW(),
                                                reg_date=NOW(),
                                                reservation='{$reservation}',
                                                sms_idx='{$row['sms_idx']}',
                                                sms_detail_idx='{$row['sms_detail_idx']}',
                                                request_idx='$request_idx[$m]'";
                mysqli_query($self_con, $query) or die(mysqli_error($self_con));
            }
        }
    }
    echo "<script>alert('등록되었습니다.');location='mypage_request_list.php';</script>";
    exit;
} else if ($mode == "address_event_add") {
    $query = "INSERT INTO Gn_event_address (mem_id, event_idx, address_idx, regdate) values ('{$_SESSION['one_member_id']}','{$event_idx}','{$address_idx}',now())";
    print_r($_POST);
    exit;
    for ($i = 0; $i < count($request_idx); $i++) {
        $idx =  $request_idx[$i];
        $query = "INSERT INTO Gn_event_request (m_id, event_idx, event_code, name, mobile, email, job, birthday, sex, addr, consult_date, join_yn, pcode, sp, ip_addr, regdate,step_end_time,target)
                    SELECT m_id, '{$event_idx_}', '{$sp_}', name, mobile, email, job, birthday, sex, addr, consult_date, join_yn, '{$sp_}', '{$event_pcode_}', ip_addr, now(),now(),target FROM Gn_event_request WHERE request_idx='{$idx}'";
        mysqli_query($self_con, $query);
    }
    $event_idx = $_POST['event_idx_'];
    $recv_num = $mobile;
    $mem_id = $m_id;

    $sql = "SELECT sms_idx1, sms_idx2, sms_idx3, mobile FROM Gn_event WHERE event_idx='{$event_idx}'";
    $eresult = mysqli_query($self_con, $sql);
    $erow = mysqli_fetch_array($eresult);
    $send_num = $erow['mobile'];

    $sql = "SELECT m_id,sms_idx,mobile FROM Gn_event_sms_info WHERE sms_idx='{$erow['sms_idx1']}' or sms_idx='{$erow['sms_idx2']}' or sms_idx='{$erow['sms_idx3']}'";
    $lresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $time = 60 - date("i");
    while ($lrow = mysqli_fetch_array($lresult)) {
        $mem_id = $lrow['m_id'];
        $sms_idx = $lrow['sms_idx'];
        $send_num = $lrow['mobile'];
        //알람등록
        $sql = "SELECT * FROM Gn_event_sms_step_info WHERE sms_idx='{$sms_idx}'";
        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        $step_end_time = date("Y-m-d H:i:s");
        while ($row = mysqli_fetch_array($result)) {
            // 시간 확인
            $send_day = $row['send_day'];
            $send_time = $row['send_time'];

            if ($send_time == "") $send_time = "09:30";
            if ($send_time == "00:00") $send_time = "09:30";
            if ($send_day == "0") {
                $reservation = "";
            } else {
                $reservation = date("Y-m-d $send_time:00", strtotime("+$send_day days"));
                if ($step_end_time < $reservation)
                    $step_end_time = $reservation;
            }
            $jpg = $jpg1 = $jpg2 = '';
            if ($row['image'])
                $jpg = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image'];
            if ($row['image1'])
                $jpg1 = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image1'];
            if ($row['image2'])
                $jpg2 = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image2'];
            for ($m = 0; $m < count($mobile); $m++) {
                sendmms(3, $mem_id, $send_num, $mobile[$m], $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, 'Y', $row['sms_idx'], $row['sms_detail_idx'], $request_idx[$m], "", $row['send_deny']);

                $query = "INSERT INTO Gn_MMS_Agree SET mem_id='{$mem_id}',
                                                 send_num='{$send_num}',
                                                 recv_num='$mobile[$m]',
                                                 content='{$row['content']}',
                                                 title='{$row['title']}',
                                                 jpg='{$jpg}',
                                                 jpg1='',
                                                 jpg2='',
                                                 up_date=NOW(),
                                                 reg_date=NOW(),
                                                 reservation='{$reservation}',
                                                 sms_idx='{$row['sms_idx']}',
                                                 sms_detail_idx='{$row['sms_detail_idx']}',
                                                 request_idx='$request_idx[$m]'";
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
    $sql = "INSERT INTO Gn_lecture SET event_idx='{$event_idx}',
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
    $sql = "INSERT INTO Gn_lecture SET event_idx='{$event_idx}',
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
    $sql = "UPDATE  Gn_lecture SET event_idx='{$event_idx}',
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
                                WHERE  lecture_id='{$lecture_id}'";
    $result = mysqli_query($self_con, $sql);
    echo "<script>alert('수정되었습니다.');location='mypage_lecture_list.php';</script>";
    exit;
} else if ($mode == "lecture_del") {
    $sql = "DELETE FROM  Gn_lecture WHERE  lecture_id='{$lecture_id}'";
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
    $sql = "INSERT INTO Gn_review SET lecture_id='{$lecture_id}',
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
    $sql = "UPDATE Gn_review SET score='{$score}',content='{$content}',profile='{$profile}' WHERE  review_id='{$review_id}'";
    $result = mysqli_query($self_con, $sql);
    echo "<script>alert('수정되었습니다.');location='mypage_review_list.php';</script>";
    exit;
} else if ($mode == "daily_del") {
    if ($gd_id > 0) {
        $sql = "DELETE FROM Gn_daily WHERE  gd_id='{$gd_id}'";
        $result = mysqli_query($self_con, $sql);

        $query = "DELETE FROM Gn_daily_date WHERE gd_id='{$gd_id}'";
        mysqli_query($self_con, $query);

        $query = "DELETE FROM Gn_MMS WHERE gd_id='{$gd_id}' ";
        mysqli_query($self_con, $query);

        $query = "DELETE FROM gn_mail WHERE gd_id='{$gd_id}' ";
        mysqli_query($self_con, $query);
    }

    echo "<script>alert('삭제되었습니다.');location='daily_list.php';</script>";
    exit;
} else if ($mode == "daily_update") {
    $date = explode(",", $send_date);
    $end_date = max($date);
    $start_date = min($date);
    $query_step_add = "";
    $step_idx = "";
    if ($total_count == "")
        $total_count = 0;

    if (!$daily_link) {
        $sql_card = "SELECT card_short_url FROM Gn_Iam_Name_Card WHERE mem_id='{$_SESSION['one_member_id']}' order by idx asc limit 1";
        $res_card = mysqli_query($self_con, $sql_card);
        $row_card = mysqli_fetch_array($res_card);

        $sql_mem = "SELECT mem_code FROM Gn_Member WHERE mem_id='{$_SESSION['one_member_id']}'";
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
            $sql_sms = "SELECT title, content, image, image1, image2, send_deny FROM Gn_event_sms_step_info WHERE sms_idx='{$step_sms_idx}' order by step asc limit 1";
            $res_sms = mysqli_query($self_con, $sql_sms);
            $row_sms = mysqli_fetch_array($res_sms);

            $title = $row_sms['title'];
            $txt = $row_sms['content'];
            $deny = $row_sms['send_deny'];
            $upimage_str = $row_sms['image'] ? "https://" . $HTTP_HOST . '/upload/' . $row_sms['image'] : '';
            $upimage_str1 = $row_sms['image1'] ? "https://" . $HTTP_HOST . '/upload/' . $row_sms['image1'] : '';
            $upimage_str2 = $row_sms['image2'] ? "https://" . $HTTP_HOST . '/upload/' . $row_sms['image2'] : '';

            if (isset($_POST['step_count']))
                $step_count = $_POST['step_count'];
            else
                $step_count = 1;
            $daily_link = "";
        }
        $query_step_add = "step_sms_idx='{$step_sms_idx}',
                        weekend_status='{$set_weekend}',
                        step_count='{$step_count}',";
        if ($max_count != "")
            $query_step_add .= "max_count='{$max_count}',";
    }

    $query = "UPDATE Gn_daily SET mem_id='{$_SESSION['one_member_id']}', 
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
                            WHERE gd_id='{$gd_id}'";
    mysqli_query($self_con, $query);

    if ($gd_id > 0) {
        $query = "DELETE FROM Gn_daily_date WHERE gd_id='{$gd_id}';";
        mysqli_query($self_con, $query);

        $query = "DELETE FROM Gn_MMS WHERE gd_id='{$gd_id}' ";
        mysqli_query($self_con, $query);

        $query = "DELETE FROM gn_mail WHERE gd_id='{$gd_id}' ";
        mysqli_query($self_con, $query);
    }
    $k = 0;
    $kk = 0;
    $txt .= "\n" . $daily_link;
    $sql = "SELECT * FROM Gn_MMS_Receive WHERE grp_id = '{$group_idx}' ";
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
        $query = "INSERT INTO Gn_daily_date SET gd_id='{$gd_id}', send_date='$date[$i]', recv_num='$recv_num_set[$i]'";
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
        $sql_card = "SELECT card_short_url FROM Gn_Iam_Name_Card WHERE mem_id='{$_POST['mem_id']}' order by idx asc limit 1";
        $res_card = mysqli_query($self_con, $sql_card);
        $row_card = mysqli_fetch_array($res_card);

        $sql_mem = "SELECT mem_code FROM Gn_Member WHERE mem_id='{$_POST['mem_id']}'";
        $res_mem = mysqli_query($self_con, $sql_mem);
        $row_mem = mysqli_fetch_array($res_mem);

        $daily_link = "https://" . $HTTP_HOST . '/?' . $row_card[0] . $row_mem[0];
    }
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

        $sql_cnt = "SELECT min(send_day) as step_count FROM Gn_event_sms_step_info WHERE sms_idx='{$step_sms_idx}' and send_day >= '{$step_count}'";
        $res_cnt = mysqli_query($self_con, $sql_cnt);
        $row_cnt = mysqli_fetch_array($res_cnt);

        $sql_sms = "SELECT title, content, image, image1, image2, send_deny, send_time FROM Gn_event_sms_step_info WHERE sms_idx='{$step_sms_idx}' and send_day = '{$row_cnt['step_count']}' order by step asc";
        // $sql_sms = "SELECT title, content, image, image1, image2 FROM Gn_event_sms_step_info WHERE sms_idx='{$step_sms_idx}' order by step asc limit 1";
        $res_sms = mysqli_query($self_con, $sql_sms);
        if (mysqli_num_rows($res_sms)) {
            while ($row_sms = mysqli_fetch_array($res_sms)) {
                $send_time = $row_sms['send_time'];
                //if (isset($_POST['step_count'])) {
                if ($row_cnt['step_count'] > 0) {
                    $time_arr = explode(":", $send_time);
                    $htime = trim($time_arr[0]);
                    $mtime = trim($time_arr[1]);
                }
                $title = $row_sms['title'];
                $txt = $row_sms['content'];
                $deny = $row_sms['send_deny'];
                $upimage_str = $row_sms['image'] ? "https://" . $HTTP_HOST . '/upload/' . $row_sms['image'] : '';
                $upimage_str1 = $row_sms['image1'] ? "https://" . $HTTP_HOST . '/upload/' . $row_sms['image1'] : '';
                $upimage_str2 = $row_sms['image2'] ? "https://" . $HTTP_HOST . '/upload/' . $row_sms['image2'] : '';

                $step_idx = $step_sms_idx;
                $daily_link = "";

                $query_step_add = "step_sms_idx='{$step_sms_idx}',
                                        weekend_status='{$set_weekend}',
                                        step_count='{$step_count}',";
                if ($max_count != "")
                    $query_step_add .= "max_count='{$max_count}',";

                $query = "INSERT INTO Gn_daily SET 
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
                $sql = "SELECT * FROM " . $table . " WHERE grp_id = '{$group_idx}' ";
                $sresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
                while ($srow = mysqli_fetch_array($sresult)) {
                    $sql_chk = "SELECT idx FROM Gn_MMS_Deny WHERE send_num='{$send_num}' and recv_num='{$srow['recv_num']}' and (chanel_type=9 or chanel_type=4)";
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
                    if ($step_count == 0) {
                        $reservDate = new DateTime($reservation); // DateTime 객체로 변환
                        $reservDate->modify('+' . $row_cnt['step_count'] . ' day'); // 10일 더하기
                        $reservation = $reservDate->format('Y-m-d H:i:s'); // 원하는 포맷으로 출력
                    }
                    sendmms(6, $_SESSION['one_member_id'], $send_num, $recv_num_set[$i], $reservation, $title, $txt, $upimage_str, $upimage_str1, $upimage_str2, 'Y', "", "", "", $gd_id, $deny);
                }

                for ($i = 0; $i < count($date); $i++) {
                    $query = "INSERT INTO Gn_daily_date SET gd_id='{$gd_id}',
                                                                send_date='{$date[$i]}',
                                                                recv_num='{$recv_num_set[$i]}'";
                    mysqli_query($self_con, $query);
                }
            }
        }
    } else {
        $query = "INSERT INTO Gn_daily SET mem_id='{$_SESSION['one_member_id']}', 
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
        $sql = "SELECT * FROM " . $table . " WHERE grp_id = '{$group_idx}' ";
        $sresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
        while ($srow = mysqli_fetch_array($sresult)) {
            $sql_chk = "SELECT idx FROM Gn_MMS_Deny WHERE send_num='{$send_num}' and recv_num='{$srow['recv_num']}' and (chanel_type=9 or chanel_type=4)";
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
            $query = "INSERT INTO Gn_daily_date SET gd_id='{$gd_id}',send_date='{$date[$i]}',recv_num='{$recv_num_set[$i]}'";
            mysqli_query($self_con, $query);
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
    $sql_1 = "SELECT * FROM `gn_coaching_info` WHERE coaching_turn = (  SELECT MAX( coaching_turn ) AS max_c_turn FROM  `gn_coaching_info`  WHERE `coach_id` = '{$insert_coach_id}' AND `coty_id` = '{$insert_coty_id}' )  AND `coach_id` = '{$insert_coach_id}' AND `coty_id` = '{$insert_coty_id}';";
    $res_1 = mysqli_query($self_con, $sql_1);
    $coaching = mysqli_fetch_array($res_1);
    $max_coaching_date = $coaching['coaching_date'];
    $min_date = date('Y-m-d H:i:s', strtotime($max_coaching_date));
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
    $sql = "SELECT * FROM gn_coaching_apply a inner join Gn_Member b on a.mem_code = b.mem_code WHERE coty_id='{$insert_coty_id}' and coach_id='{$insert_coach_id}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $g_coty_row = mysqli_fetch_array($result);

    //코치 정보 얻기
    $sql = "SELECT * FROM gn_coach_apply a inner join Gn_Member b on a.mem_code = b.mem_code WHERE coach_id='{$insert_coach_id}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $g_coach_row = mysqli_fetch_array($result);
    $insert_search_text =  $insert_search_text . " | " . $g_coach_row['mem_name'] . " | " . $g_coty_row['mem_name'];
    //코칭정보테이블에서 이미 등록된 코칭이 있는가 체크
    $sql = "SELECT *,count(coaching_id) as cnt FROM gn_coaching_info WHERE coty_id='{$insert_coty_id}' and coach_id='{$insert_coach_id}'";
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
        $sql = "SELECT * FROM gn_coaching_info WHERE coty_id='{$insert_coty_id}' and coach_id='{$insert_coach_id}' and coaching_turn = '1'";
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
    $sql = "SELECT sum(coaching_time) as time_sum FROM gn_coaching_info WHERE coty_id='{$insert_coty_id}' and coach_id='{$insert_coach_id}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $row = mysqli_fetch_array($result);
    $insert_coaching_time_sum = $row['time_sum']; //이 계열의 진행한 코칭의 총 시간
    //잔여시간
    $remain_time = $coty_cont_time - $insert_coaching_time_sum;
    if ($update_coaching_id) {
        $sql = "SELECT coaching_time FROM gn_coaching_info WHERE coaching_id='" . $update_coaching_id . "'";
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
        //업뎃 UPDATE Gn_landing SET short_url='{$transUrl}' WHERE landing_idx='{$landing_idx}'
        $sql = "UPDATE gn_coaching_info SET coty_id='{$insert_coty_id}',
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
                                        WHERE coaching_id = $update_coaching_id";
        //coaching_time='{$insert_coaching_time}',
    } else {
        //크리트
        $sql = "INSERT INTO gn_coaching_info SET coty_id='{$insert_coty_id}',
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
    $sql = "SELECT * FROM gn_coaching_info WHERE coaching_id=$coaching_id";
    $result_num = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $coaching_info_data = mysqli_fetch_array($result_num);
    $coty_id = $coaching_info_data['coty_id'];
    $coach_id = $coaching_info_data['coach_id'];
    $current_coaching_turn = $coaching_info_data['coaching_turn'];
    $sql = "SELECT *,count(*) as cnt FROM gn_coaching_info WHERE coty_id='" . $coty_id . "' and coach_id='" . $coach_id . "' ";
    $result_num = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $coaching_info_data = mysqli_fetch_array($result_num);
    //코칭개수과 코칭턴이 같을때 즉 마지막 코칭이라면
    if ($coaching_info_data['cnt'] ==  $current_coaching_turn) {
        $sql = "DELETE FROM gn_coaching_info WHERE coaching_id=$coaching_id";
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
    $sql = "UPDATE gn_coaching_info SET coty_value='{$coty_value}',
                                    coty_comment='{$coty_comment}',
                                    agree=1,coaching_status=2 
                                     WHERE coaching_id = $coaching_id";
    echo $sql;
    $result = mysqli_query($self_con, $sql);
    exit;
} else if ($mode == "update_coaching_info_site_comment") {
    $site_value = $_POST['site_value'];
    $site_comment = $_POST['site_comment'];
    $coaching_id = $_POST['coaching_id'];
    $sql = "UPDATE gn_coaching_info SET site_value='{$site_value}',
                                    site_comment='{$site_comment}'
                                     WHERE coaching_id = $coaching_id";
    //echo $sql;
    $result = mysqli_query($self_con, $sql);
    exit;
}
//코치신청
else if ($mode == "create_coach_apply") {
    $sql = "SELECT * FROM Gn_Member  WHERE mem_id='{$_SESSION['one_member_id']}' and site != ''";
    $result_num = mysqli_query($self_con, $sql);
    $data = mysqli_fetch_array($result_num);
    $sql = "INSERT INTO gn_coach_apply SET mem_code='" . $data['mem_code'] . "',  reg_date=now() , agree= 0, coach_type=0";
    //echo $sql;
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
    $sql = "SELECT * FROM Gn_Member  WHERE mem_id='{$_SESSION['one_member_id']}' and site != ''";
    $result_num = mysqli_query($self_con, $sql);
    $data = mysqli_fetch_array($result_num);
    $sql = "INSERT INTO gn_coaching_apply SET mem_code='" . $data['mem_code'] . "' , reg_date=now() ,cont_term='" . $cont_term . "',want_coaching='" . $want_coaching . "' ,cont_time='" . $cont_time . "' ,coaching_price='" . $coaching_price . "', agree =0 ";
    $result = mysqli_query($self_con, $sql);
    $coach_apply_idx = mysqli_insert_id($self_con);
    echo "<script>location='mypage_coaching_list.php';</script>";
    exit;
}
//코티가 코칭신청 (수강신청)
else if ($mode == "read_coaching_apply") {
    $coty_id = $_POST['coty_id'];
    $coach_id = $_POST['coach_id'];
    $sql = "SELECT * FROM gn_coaching_apply a inner join Gn_Member b on a.mem_code=b.mem_code WHERE a.coty_id='" . $coty_id . "' ";
    $result_num = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    $data = mysqli_fetch_array($result_num);

    $sql = "SELECT * FROM gn_coaching_info WHERE coty_id='" . $coty_id . "' and coach_id='" . $coach_id . "' ";

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
    $sql = "SELECT count(*) as cnt FROM gn_coaching_info WHERE coty_id='" . $coty_id . "' and coach_id='" . $coach_id . "' ";
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
    $query = "DELETE FROM Gn_MMS WHERE or_id='{$idx}' and up_date is null ";
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));

    $query = "UPDATE Gn_event_oldrequest SET sms_idx = '{$step_idx}',
                                            reservation_title = '{$reservation_title}',
                                            address_idx = '{$group_idx}',
                                            addr_start_index = '{$start_index}',
                                            addr_end_index = '{$end_index}',
                                            start_date=NOW(),
                                            status = 'Y',
                                            reg_date = NOW() WHERE idx = $idx";
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));

    $start_index -= 1;

    $sql = "SELECT recv_num FROM Gn_MMS_Receive WHERE grp_id = '{$group_idx}' limit $start_index, $cnt"; //test
    $gresult = mysqli_query($self_con, $sql);

    $num_arr = array();
    while ($mms_receive = mysqli_fetch_array($gresult)) {
        array_push($num_arr, $mms_receive['recv_num']);
    }
    $recv_num = implode(",", $num_arr);

    $sql = "SELECT m_id FROM Gn_event_sms_info WHERE sms_idx='{$step_idx}'";
    $lresult = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    while ($lrow = mysqli_fetch_array($lresult)) {
        $mem_id = $lrow['m_id'];
        $sms_idx = $step_idx;
        $sql = "SELECT * FROM Gn_event_sms_step_info WHERE sms_idx='{$sms_idx}'";
        $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));

        while ($row = mysqli_fetch_array($result)) {
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
                $jpg = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image'];
            if ($row['image1'])
                $jpg1 = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image1'];
            if ($row['image2'])
                $jpg2 = "https://" . $HTTP_HOST . "/adjunct/mms/thum/" . $row['image2'];
            sendmms(8, $mem_id, $mobile, $recv_num, $reservation, $row['title'], $row['content'], $jpg, $jpg1, $jpg2, "Y", $row['sms_idx'], $row['sms_detail_idx'], "", "", "", $idx);
        }
    }

    if ($reservation == "")
        $query = "UPDATE Gn_event_oldrequest SET end_date=NOW() WHERE idx = '{$idx}'";
    else
        $query = "UPDATE Gn_event_oldrequest SET end_date='{$reservation}' WHERE idx = '{$idx}'";
    mysqli_query($self_con, $query) or die(mysqli_error($self_con));

    echo "<script>location='mypage_oldrequest_list.php';</script>"; //test
    exit;
} else if ($mode == "review_img_del") {
    //코티가 코칭신청 (수강신청)
    $lecture_id = $_POST['lecture_id'];
    $no = $_POST['review_img_no'];

    $sql = "UPDATE  Gn_lecture SET review_img" . $no . "='' WHERE lecture_id='{$lecture_id}'";
    $result = mysqli_query($self_con, $sql) or die(mysqli_error($self_con));
    echo "삭제되었습니다.";
    exit;
}
