<?
$path = "./";
include_once "_head.php";
if (!$_SESSION[one_member_id]) {
?>
    <script language="javascript">
        location.replace('/ma.php');
    </script>
<?
    exit;
}

//Julian 2020-07-04 Problem 19 / 1 , repair top menu 

$sql = "select * from Gn_Member  where mem_id='" . $_SESSION[one_member_id] . "'";
$sresul_num = mysqli_query($self_con,$sql);
$member = $data = mysqli_fetch_array($sresul_num);
if ($_REQUEST[rday1]) {
    $start_time = strtotime($_REQUEST[rday1]);
    $sql_serch .= " and unix_timestamp(date) >=$start_time ";
}
if ($_REQUEST[rday2]) {
    $end_time = strtotime($_REQUEST[rday2]);
    $sql_serch .= " and unix_timestamp(date) <= $end_time ";
}

?>
<div class="big_sub">
    <?php include_once "mypage_base_navi.php"; ?>
    <div class="m_div sub_4c">
        <form name="pay_form" action="" method="GET" class="my_pay" enctype="multipart/form-data">

            <input type="hidden" name="page" value="<?= $page ?>" />
            <input type="hidden" name="page2" value="<?= $page2 ?>" />


            <div style="background-color:#FFF;padding:20px;">

                <div class="a1">

                    <li style="float:left;">사업자 추천정보</li>
                    <li style="float:right;"></li>
                    <p style="clear:both"></p>
                </div> <br>


                <div class="sub_4_1_t7">
                    <div style="float:left;">
                        <select name="search_type">
                            <option value="">전체</option>
                            <option value="22">일반회원</option>
                            <option value="50">사업자회원</option>
                        </select>
                        <input type="date" name="rday1" placeholder="" id="rday1" value="<?= $_REQUEST[rday1] ?>" /> ~
                        <input type="date" name="rday2" placeholder="" id="rday2" value="<?= $_REQUEST[rday2] ?>" />
                    </div>
                    <div style="float:right;">
                        <select name="lms_select">
                            <option value="">선택</option>
                            <?
                            $select_lms_arr = array("mem_name" => "회원명", "mem_id" => "아이디");
                            foreach ($select_lms_arr as $key => $v) {
                                $selected = $_REQUEST[lms_select] == $key ? "selected" : "";
                            ?>
                                <option value="<?= $key ?>" <?= $selected ?>><?= $v ?></option>
                            <?
                            }
                            ?>

                        </select>
                        <input type="text" name="lms_text" value="<?= $_REQUEST[lms_text] ?>" />

                        <a href="javascript:void(0)" onclick="pay_form.submit();"><img src="images/sub_button_703.jpg" /></a>
                    </div>
                    <p style="clear:both;"></p>
                </div>
                <table class="list_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td><label>번호</label></td>
                        <td>회원등급</td>
                        <td>이름</td>
                        <td>아이디</td>
                        <td>휴대폰번호</td>
                        <td>가입일</td>
                        <td>결제일</td>
                        <td>해지일</td>
                        <!--<td>납부내역 보기</td>
                                    <td>회원구분</td>-->
                        <!--<td>등록일시</td>-->
                    </tr>
                    <?
                    $nowPage = $_REQUEST['page'] ? $_REQUEST['page'] : 1;
                    $startPage = $_REQUEST[page] ? $_REQUEST[page] : 1;
                    $pageCnt = 20;



                    $search_type =  $_REQUEST['search_type'];
                    $rday1 =  $_REQUEST['rday1'];
                    $rday2 =  $_REQUEST['rday2'];
                    $lms_select =  $_REQUEST['lms_select'];
                    $lms_text =  $_REQUEST['lms_text'];

                    $searchStr = "";
                    if ($lms_text && $lms_select) {
                        $searchStr .= " AND gm." . $lms_select . " LIKE '%" . $lms_text . "%'";
                    }

                    if ($search_type) {
                        $searchStr .= " AND gm.mem_leb =" . $search_type;
                    }

                    if ($rday1) {
                        $start_date = date($rday1);
                        $searchStr .= " AND gm.first_regist > '" . $start_date . "'";
                    }
                    if ($rday2) {
                        $end_date = date($rday2);
                        $searchStr .= " AND gm.first_regist < '" . $end_date . "'";
                    }


                    // 검색 조건을 적용한다.
                    // $searchStr .= $search_key ? " AND (gm.mem_id LIKE '%".$search_key."%' or gm.mem_phone like '%".$search_key."%' or a.mem_name like '%".$search_key."%' or b.sendnum like '%".$search_key."%')" : null;
                    // //$searchStr .= " and end_status='Y'";






                    $order = $order ? $order : "desc";

                    $query = "select * from Gn_Member gm left join tjd_pay_result p on p.buyer_id = gm.mem_id 
                	                        where recommend_id = '" . $_SESSION[one_member_id] . "' $searchStr";



                    $res        = mysqli_query($self_con,$query);
                    $totalCnt    =  mysqli_num_rows($res);

                    $limitStr       = " LIMIT " . (($startPage - 1) * $pageCnt) . ", " . $pageCnt;
                    $number            = $totalCnt - ($nowPage - 1) * $pageCnt;

                    $intRowCount = $totalCnt;

                    $intPageSize = 20;

                    if ($_REQUEST[page]) {
                        $page = (int)$_REQUEST[page];
                        $sort_no = $intRowCount - ($intPageSize * $page - $intPageSize);
                    } else {
                        $page = 1;
                        $sort_no = $intRowCount;
                    }
                    if ($_REQUEST[page2])
                        $page2 = (int)$_REQUEST[page2];
                    else
                        $page2 = 1;
                    $int = ($page - 1) * $intPageSize;

                    $intPageCount = (int)(($intRowCount + $intPageSize - 1) / $intPageSize);



                    $orderQuery .= "
                                    	ORDER BY gm.mem_code DESC
                                    	$limitStr
                                    ";

                    $i = 1;
                    $query .= "$orderQuery";
                    $res = mysqli_query($self_con,$query);
                    while ($row = mysqli_fetch_array($res)) {
                        if ($row['mem_leb'] == "22")
                            $mem_leb = "일반회원";
                        else if ($row['mem_leb'] == "50")
                            $mem_leb = "사업자회원";

                        $new_val = "";
                        if ($row['service_type'] == 0) {
                            $new_val = "FREE";
                        } else if ($row['service_type'] == 1) {
                            $new_val = "이용자";
                        } else if ($row['service_type'] == 2) {
                            $new_val = "리셀러";
                        } else if ($row['service_type'] == 3) {
                            $new_val = "분양자";
                        }

                    ?>
                        <tr>
                            <td><?= $number-- ?></td>
                            <td><?= $new_val ?></td>
                            <td><?= $row[mem_name] ?></td>
                            <td><?= $row[mem_id] ?></td>
                            <td><?= $row[mem_phone] ?></td>

                            <!-- //Julian 2020-07-04 Problem 19 / 3 -->
                            <td><?= $row['first_regist'] ?></td>
                            <td><?= $row['date'] ?></td> <!-- //Julian 2020-07-04 Problem 19 / 3 결제일 ??? -->
                            <td><?= $row['cancel_completetime'] ?></td>
                            <!--<td><a href="#">납부내역보기</a></td>
                                    <td><?= $mem_leb; ?></td>-->
                        </tr>
                    <?
                        $i++;
                    }
                    if ($i == 1) {
                    ?>
                        <tr>
                            <td colspan="10">등록된 내용이 없습니다.</td>
                        </tr>
                    <?
                    }
                    ?>
                    <tr>
                        <td colspan="10">
                            <?
                            page_f($page, $page2, $intPageCount, "pay_form");
                            ?>

                        </td>
                    </tr>
                </table>

            </div>

            <input type="hidden" name="order_name" value="<?= $order_name ?>" />
            <input type="hidden" name="order_status" value="<?= $order_status ?>" />

        </form>

        <form name="excel_down_form" action="" target="excel_iframe" method="post">
            <input type="hidden" name="grp_id" value="" />
            <input type="hidden" name="box_text" value="" />
            <input type="hidden" name="excel_sql" value="<?= $excel_sql ?>" />
        </form>
        <iframe name="excel_iframe" style="display:none;"></iframe>
    </div>
</div>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.4/themes/base/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.0/jquery-ui.min.js"></script>
<?
mysqli_close($self_con);
include_once "_foot.php";
?>