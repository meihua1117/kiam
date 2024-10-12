<?
$path = "./";
include_once "_head.php";
$sql = "select * from Gn_Member where mem_id='{$_SESSION['one_member_id']}' and site != ''";
$result = mysqli_query($self_con,$sql) or die(mysqli_error($self_con));
$member = mysqli_fetch_array($result);
$phone = explode("-", $member['mem_phone']);
$email = explode("@", $member['mem_email']);

if ($_REQUEST['status'] == 1 && $member_1['mem_id'] != 'obmms02') {
?>
    <script language="javascript">
        location.replace('/ma.php');
    </script>
<?
    exit;
}
if (!$_SESSION['one_member_id']) {
?>
    <script language="javascript">
        location.replace('/ma.php');
    </script>
<?
    exit;
}
switch ($_REQUEST['status']) {
    case 1:
        $left_str = "공지사항";
        break;
    case 2:
        $left_str = "Q&#38;A";
        break;
    case 3:
        $left_str = "사용후기";
        break;
    case 5:
        $left_str = "관리자 매뉴얼";
        break;
}
if ($_REQUEST['one_no'])
    $up_path = $row_no['up_path'];
else
    $up_path = "one_" . $_REQUEST['status'];
?>
<div class="big_main">
    <div class="big_1">
        <div class="m_div">
            <div class="left_sub_menu">
                <a href="./">홈</a> >
                <a href="cliente_list.php?status=1">고객센터</a> >
                <a href="cliente_list.php?status=<?= $_REQUEST['status'] ?>"><?= $left_str ?></a>
            </div>
            <div class="right_sub_menu">
                <a href="cliente_list.php?status=1">공지사항</a> &nbsp;|&nbsp;
                <a href="cliente_list.php?status=2">Q&#38;A</a> &nbsp;|&nbsp;
                <a href="cliente_list.php?status=3">사용후기</a> &nbsp;|&nbsp;
                <a href="cliente_list.php?status=5">관리자 매뉴얼</a>
            </div>
            <p style="clear:both;"></p>
        </div>
    </div>
    <div class="m_div" style="padding-bottom:50px;">
        <div><img src="images/client_<?= $_REQUEST['status'] ?>_1.jpg" /></div>
        <div class="client">
            <div class="a1"><?= $left_str ?> 글쓰기</div>
            <div>
                <form name="board_write_form" action="" method="post">
                    <table class="write_table" width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>제목</td>
                            <td><input type="text" style="width:90%;" name="title" value="<?= $row_no['title'] ?>" required itemname='제목' /></td>
                        </tr>
                        <?
                        if ($_REQUEST['status'] == 2) {
                        ?>
                            <tr>
                                <td>분류</td>
                                <td>
                                    <?
                                    foreach ($fl_arr as $key => $v) {
                                        $checked = $row_no['fl'] == $key ? "checked" : "";
                                    ?>
                                        <label><input type="radio" value="<?= $key ?>" name="fl" <?= $checked ?> /><?= $v ?></label> &nbsp;
                                    <?
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?
                        }
                        if ($_REQUEST['status'] != 1) {
                        ?>
                            <tr>
                                <td>아이디</td>
                                <td>
                                    <?php echo $_SESSION['one_member_id']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>이름</td>
                                <td>
                                    <?php echo $member['mem_name']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>연락처</td>
                                <td>
                                    <input type="text" name="mobile_1" required itemname='연락처' maxlength="4" style="width:70px;" value="<?= $phone[0] ?>" /> -
                                    <input type="text" name="mobile_2" required itemname='연락처' maxlength="4" style="width:70px;" value="<?= $phone[1] ?>" /> -
                                    <input type="text" name="mobile_3" style="width:70px;" required itemname='연락처' maxlength="4" value="<?= $phone[2] ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td>이메일</td>
                                <td>
                                    <input type="text" name="email_1" required itemname='이메일' style="width:70px;" value="<?= $email[0] ?>" /> @
                                    <input type="text" name="email_2" id='email_2' itemname='이메일' required style="width:70px;" value="<?= $email[1] ?>" />
                                    <select name="email_3" itemname='이메일' onchange="inmail(this.value,'email_2')" style="background-color:#c8edfc;">
                                        <?
                                        foreach ($email_arr as $key => $v) {
                                        ?>
                                            <option value="<?= $key ?>"><?= $v ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>비밀글</td>
                                <td><input type="checkbox" name="status_1" <?= $row_no['status_1'] == "Y" ? "checked" : "" ?> /></td>
                            </tr>
                        <?
                        }
                        ?>
                        <tr>
                            <td colspan="2" style="background-color:#FFF">
                                <script language="javascript" src="naver_editor/js/HuskyEZCreator.js" charset="utf-8"></script>
                                <textarea name="ir1" id="ir1" rows="10" cols="100" style="width:100%; height:200px; min-width:645px; display:none;"><?= $row_no['content'] ?></textarea>
                                <script language="javascript" src="naver_editor/js/naver_editor.js" charset="utf-8"></script>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="background-color:#FFF">
                                <iframe src="upload.php?up_path=<?= $up_path ?>&frm=board_write_form" name="upload_iframe" frameborder="0" width="100%" scrolling="no" height="100" style="margin:0; padding:0;"></iframe>
                                <input type="hidden" name="board_write_form_img_hid" id="board_write_form_img_hid" value="<?= $row_no['adjunct_1'] ?>" />
                                <input type="hidden" name="board_write_form_img_hid_2" id="board_write_form_img_hid_2" value="<?= $row_no['adjunct_2'] ?>" />
                                <input type="hidden" itemname='이미지메모' name="board_write_form_memo_hid" id="board_write_form_memo_hid" value="<?= $row_no['adjunct_memo'] ?>" />
                                <input type="hidden" name="up_path" value="<?= $up_path ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align:right;">
                                <a href="javascript:void(0)" onclick="board_save(board_write_form,'<?= $row_no['no'] ?>','<?= $_REQUEST['status'] ?>')"><img src="images/client_2_3.jpg" /></a>
                                <a href="cliente_list.php?status=<?= $_REQUEST['status'] ?>"><img src="images/client_2_4.jpg" /></a>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<?
include_once "_foot.php";
?>