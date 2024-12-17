<?
header("Content-type:text/html;charset=utf-8");
include_once "lib/db_config.php";
$HTTP_HOST = str_replace("www.", "", $_SERVER['HTTP_HOST']);
if ($_REQUEST['mode'] == "inser") {
    $sql = "select * from Gn_MMS where uni_id='{$_REQUEST['u']}' ";
    echo $sql."<br>";
    $resul = mysqli_query($self_con, $sql);
    $row = mysqli_fetch_array($resul);
    if ($row['send_num'] == $_REQUEST['n']) { ?>
        <script>
            alert('셀프폰 거부 등록입니다.');
            location.href = 'https://<?=$HTTP_HOST?>';
        </script>
        <?
        exit;
    }
    $chanel = 1;
    $search_str = " and (chanel_type=1 or chanel_type=4)";
    if ($row['type'] == 2 || $row['type'] == 3 || $row['type'] == 4) {
        $chanel = 2;
        $search_str = " and chanel_type=2";
    }
    if ($row['type'] == 6 || $row['type'] == 8) {
        $chanel = 4;
        $search_str = " and (chanel_type=1 or chanel_type=4)";
    }
    if ($row['type'] == 1) {
        $chanel = 1;
        $search_str = " and (chanel_type=1 or chanel_type=4)";
    }
    $k = 0;

    $sql_d = "select idx from Gn_MMS_Deny where send_num='{$row['send_num']}' and recv_num='{$_REQUEST['n']}'" . $search_str;
    echo $sql_d."<br>";
    $resul_d = mysqli_query($self_con, $sql_d);
    $row_d = mysqli_fetch_array($resul_d);
    if ($row_d['idx'] != null) {
        $deny_info['send_num'] = $row['send_num'];
        $deny_info['recv_num'] = $_REQUEST['n'];
        $deny_info['mem_id'] = $row['mem_id'];
        $deny_info['title'] = $row['title'];
        $deny_info['content'] = substr(htmlspecialchars($row['content']), 0, 20) . "...";
        $deny_info['jpg'] = $row['jpg'];
        $deny_info['status'] = 'A';
        $deny_info['chanel_type'] = $chanel;
        $sql = "insert into Gn_MMS_Deny set ";
        foreach ($deny_info as $key => $v)
            $sql .= " $key='$v' , ";
        $sql .= " reg_date=now(),up_date=now() ";
        echo $sql."<br>";
        $sql = mb_convert_encoding($sql, 'UTF-8', 'auto');
        mysqli_query($self_con, $sql);
        echo "query done<br>";
        $k = 1;
    }
    $user_id = $row['mem_id'];
    $now_num = $_REQUEST['n'];
    $send_num = $row['send_num'];
    // [새 번호]가 현재로그에 있는지 확인
    $query = "select now_num, old_nums from Gn_MMS_Receive_Change_Log where now_num = '{$now_num}'  ";
    echo $query."<br>";
    $resultA = mysqli_query($self_con, $query);
    $rowA = mysqli_fetch_array($resultA);
    if ($rowA[0]) {
        $msg = substr(htmlspecialchars($row['content']), 0, 20) . "...";
        $now_num = $rowA['now_num'];
        $sql_s = "select idx from Gn_MMS_Deny where send_num='{$send_num}' and recv_num='{$now_num}'  and chanel_type!=9 ";
        $resul_s = mysqli_query($self_con, $sql_s);
        $row_s = mysqli_fetch_array($resul_s);
        if ($row_s['idx'] == "") {
            $sql_insert = "insert into Gn_MMS_Deny set send_num='{$send_num}',
                                                       recv_num='{$now_num}',
                                                       reg_date=now(),
                                                       mem_id='{$userId}',
                                                       title='번호 변경 및 수신 불가',
                                                       content='{$msg}',
                                                       status='A',
                                                       chanel_type='{$chanel}',
                                                       up_date=now() ";
            $sql_insert = mb_convert_encoding($sql_insert, 'UTF-8', 'auto');
                                                       echo "76:".$sql_insert."<br>";
            mysqli_query($self_con, $sql_insert);
            $k = 1;
        }
        $info = explode(",", $rowA['old_nums']);
        for ($kk = 0; $kk < count($info); $kk++) {
            $now_num = $info[$kk];
            $sql_s = "select idx from Gn_MMS_Deny where send_num='{$send_num}' and recv_num='{$now_num}'  and chanel_type!=9 ";
            $resul_s = mysqli_query($self_con, $sql_s);
            $row_s = mysqli_fetch_array($resul_s);
            if ($row_s['idx'] == "") {
                $sql_insert = "insert into Gn_MMS_Deny set send_num='{$send_num}',
                                                           recv_num='{$now_num}',
                                                           reg_date=now(),
                                                           mem_id='{$userId}',
                                                           title='번호 변경 및 수신 불가',
                                                           content='{$msg}',
                                                           status='A',
                                                           chanel_type='{$chanel}',
                                                           up_date=now() ";
                $sql_insert = mb_convert_encoding($sql_insert, 'UTF-8', 'auto');
                                                           echo "97:".$sql_insert."<br>";
                mysqli_query($self_con, $sql_insert);
                $k = 1;
            }
        }
    }


    // [새 번호]가 현재로그에 있는지 확인
    $query = "select now_num, old_nums from Gn_MMS_Receive_Change_Log where  old_nums like '$now_num%' ";
    echo $query."<br>";
    $resultA = mysqli_query($self_con, $query);
    $rowA = mysqli_fetch_array($resultA);
    if ($rowA[0]) {
        $msg = substr(htmlspecialchars($row['content']), 0, 20) . "...";
        $now_num = $rowA['now_num'];
        $sql_s = "select idx from Gn_MMS_Deny where send_num='{$send_num}' and recv_num='{$now_num}'  and chanel_type!=9 ";
        $resul_s = mysqli_query($self_con, $sql_s);
        $row_s = mysqli_fetch_array($resul_s);
        if ($row_s['idx'] == "") {
            $sql_insert = "insert into Gn_MMS_Deny set send_num='{$send_num}',
                                                       recv_num='{$now_num}',
                                                       reg_date=now(),
                                                       mem_id='{$userId}',
                                                       title='번호 변경 및 수신 불가',
                                                       content='{$msg}',
                                                       status='A',
                                                       chanel_type='{$chanel}',
                                                       up_date=now() ";
            $sql_insert = mb_convert_encoding($sql_insert, 'UTF-8', 'auto');
                                                       echo "125:".$sql_insert."<br>";
            mysqli_query($self_con, $sql_insert);
            $k = 1;
        }
        $info = explode(",", $rowA['old_nums']);
        for ($kk = 0; $kk < count($info); $kk++) {
            $now_num = $info[$kk];
            $sql_s = "select idx from Gn_MMS_Deny where send_num='{$send_num}' and recv_num='{$now_num}'  and chanel_type!=9 ";
            $resul_s = mysqli_query($self_con, $sql_s);
            $row_s = mysqli_fetch_array($resul_s);
            if ($row_s['idx'] == "") {
                $sql_insert = "insert into Gn_MMS_Deny set send_num='{$send_num}',
                                                           recv_num='{$now_num}',
                                                           reg_date=now(),
                                                           mem_id='{$userId}',
                                                           title='번호 변경 및 수신 불가',
                                                           content='{$msg}',
                                                           status='A',
                                                           chanel_type='{$chanel}',
                                                           up_date=now() ";
                $sql_insert = mb_convert_encoding($sql_insert, 'UTF-8', 'auto');
                                                           echo "145:".$sql_insert."<br>";
                mysqli_query($self_con, $sql_insert);
                $k = 1;
            }
        }
    }
    if ($_REQUEST['u']) {
        if (!$k) {
        ?>
            <script>
                alert('이미 등록되어 있습니다.');
                //location.href = 'https://<?=$HTTP_HOST?>';
            </script>
        <?
        } else {
        ?>
            <script>
                alert('귀하가 <?= date("Y"); ?>년<?= date("m"); ?>월<?= date("d"); ?>일에 요청하신 수신동의가 정상적으로 수신거부 되었습니다.');
                //location.href = 'https://<?=$HTTP_HOST?>';
            </script>
        <?  }
    } else {
        ?>
        <script>
            alert('새로 받은 문자부터 수신거부가능합니다.');
            //location.href = 'https://<?=$HTTP_HOST?>';
        </script>
    <?
    }
} else {
    ?>
    <script>
        if (confirm('문자 수신거부를 하실 경우 이벤트 및 정보를 받을 수 없습니다. 무료 문자 수신거부를 등록하시겠습니까?'))
            location.href = '<?= $PHP_SELF ?>?u=<?= $_REQUEST['u'] ?>&n=<?= $_REQUEST['n'] ?>&mode=inser';
        else
            location.href = 'https://<?=$HTTP_HOST?>';
    </script>
<?}?>