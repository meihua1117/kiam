
<?
include_once "../../lib/rlatjd_fun.php";

/*$mem_id = $_SESSION['iam_member_id'];
$sql_list_count = "select count(*) from Gn_Member_card where mem_id='{$mem_id}'";
$res_list_count = mysqli_query($self_con,$sql_list_count);
$row_list_count = mysqli_fetch_array($res_list_count);

$row_num5 = $row_list_count[0];

if(!$row_num5){
    $body = '<p style="text-align: center;font-size: 17px;font-weight: 900;">종이명함이 없습니다.</p>';
    echo $body;
    exit;
}
?>
 <div style="float:right;">
    <a href="javascript:app_camera_paper();">명함촬영</a>
 </div>
<?

$sql_list = "select * from Gn_Member_card where mem_id='{$mem_id}' order by create_time desc";

$result6=mysqli_query($self_con,$sql_list);
while($row6=mysqli_fetch_array($result6)){
    $body .= '<div class="paper_list" id="paper_list_'.$row6['seq'].'">';
    $body .= '    <div style="float:right;">';
    $body .= '        <a href="javascript:edit_paper('.$row6['seq'].')" class="controls" style="font-size: 20px;margin-right: 5px;"><i class="fa fa-pencil-square-o" aria-hidden="false" style="padding: 3px;border-radius: 5px;"></i></a>';
    $body .= '        <a href="javascript:delete_paper('.$row6['seq'].')" class="controls" style="font-size: 20px;">X</a>';
    $body .= '    </div>';
    $body .= '    <img src="'.$row6['img_url'].'" onclick="show_paper_img(`'.$row6['img_url'].'`)">';
    $body .= '</div>';
}
echo $body;*/
$search_str = $_GET['search_str'];
$card_owner = $_GET['card_owner'];
$search_range = $_GET['search_range'];
$card_master = $_GET['card_master'];
$phone_count = $_GET['phone_count'];

if ($search_str !== "") {
    $paper_sql_msg = "and (name like '%$search_str%' or recv_num like '%$search_str%')";
} else {
    $paper_sql_msg = "";
}

$paper_sql_msg .= " and paper_yn=1 order by display_top desc";

$sql9 = "select count(idx) from Gn_MMS_Receive_Iam where mem_id = '$card_owner' and grp = '아이엠' $paper_sql_msg";
$result9 = mysqli_query($self_con, $sql9);
$comment_row9 = mysqli_fetch_array($result9);
$row_num9 = $comment_row9[0];

$list = 10; //한 페이지에 보여줄 개수
$block_ct = 10; //블록당 보여줄 페이지 개수

if ($_GET['page']) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$block_num = ceil($page / $block_ct); // 현재 페이지 블록 구하기
$block_start = (($block_num - 1) * $block_ct) + 1; // 블록의 시작번호
$block_end = $block_start + $block_ct - 1; //블록 마지막 번호
$total_page = ceil($row_num9 / $list); // 페이징한 페이지 수 구하기
if ($block_end > $total_page) $block_end = $total_page; //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
$total_block = ceil($total_page / $block_ct); //블럭 총 개수
$start_num = ($page - 1) * $list; //시작번호 (page-1)에서 $list를 곱한다.

if ((int)$search_range == 1) {
    $paper_sql_msg = $paper_sql_msg . " ,name";
} else if ((int)$search_range == 2) {
    $paper_sql_msg = $paper_sql_msg . " ,name desc";
} else if ((int)$search_range == 3) {
    $paper_sql_msg = $paper_sql_msg . " ,idx desc";
} else if ((int)$search_range == 4) {
    $paper_sql_msg = $paper_sql_msg . " ,idx";
} else {
    $paper_sql_msg = $paper_sql_msg . " ,idx desc";
}
$body = '<div class="contact-list">';
$body .= '<ul>';

$sql10 = "select idx,name,reg_date,recv_num,paper_yn,paper_seq from Gn_MMS_Receive_Iam where mem_id = '$card_owner' and grp = '아이엠' $paper_sql_msg limit $start_num, $list";
$result10 = mysqli_query($self_con, $sql10) or die(mysqli_error($self_con));
//file_put_contents("iamlog.txt", $sql10 . "\n", FILE_APPEND);
while ($row10 = mysqli_fetch_array($result10)) {
    if ((int)$row10['recv_num'] > 10) {
        $contact_phone1 = substr($row10['recv_num'], 0, 3);
        $contact_phone2 = substr($row10['recv_num'], 3, 4);
        $contact_phone3 = substr($row10['recv_num'], 7, 4);
        $contact_phone = $contact_phone1 . "-" . $contact_phone2 . "-" . $contact_phone3;
    } else {
        $contact_phone1 = substr($row10['recv_num'], 0, 3);
        $contact_phone2 = substr($row10['recv_num'], 3, 3);
        $contact_phone3 = substr($row10['recv_num'], 6, 4);
        $contact_phone = $contact_phone1 . "-" . $contact_phone2 . "-" . $contact_phone3;
    }

    $body .=  '<li class="list-item">';
    $body .=  '     <div class="item-wrap">';
    $body .=  '          <div class="thumb" style="width:5%">';
    $body .=  '              <div class="thumb-inner">';
    $body .=  '                 <img src="/iam/img/profile-img.svg" style="margin-top:5px">';
    $body .=  '             </div>';
    $body .=  '         </div>';
    if ($row10['paper_yn']) {
        $sql_paper_info = "select seq,img_url,job,org_name,mobile from Gn_Member_card where seq='{$row10['paper_seq']}'";
        $res_paper_info = mysqli_query($self_con, $sql_paper_info);
        $row_paper_info = mysqli_fetch_array($res_paper_info);
        $contact_phone = $row_paper_info['mobile'];
        $contact_phone = substr($contact_phone, 0, 3) . '-' . substr($contact_phone, 3, 4) . '-' . substr($contact_phone, 7);
        $body .=  '         <div class="info" style = "width:60%" onclick="edit_paper(' . $row_paper_info['seq'] . ')">';
    } else {
        $body .=  '         <div class="info" style = "width:60%">';
    }
    $body .=  '             <div class="upper">';
    $body .=  '                     <span class="name" style="font-size:12px;font-weight:500;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 1;overflow: hidden;text-overflow: ellipsis;" id="paper_org_' . $row_paper_info['seq'] . '">'.$row_paper_info['org_name'].'</span>';
    $body .=  '</div>';
    $body .=  '<div class="downer" id="paper_name_' . $row_paper_info['seq'] . '" style="font-size:16px;font-weight:bold;display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp: 3;overflow: hidden;text-overflow: ellipsis;">';
    if ($_SESSION['iam_member_id'] == $card_owner && $phone_count > 0) {
        if ($row10['name'])
            $body .= $row10['name'] . " , " . $row_paper_info['job'];
        else
            $body .= $contact_phone3;
    } else {
        if ($row10['name'])
            $body .= iconv_substr($row10['name'], 0, 1, "utf-8") . "**";
        else
            $body .= $contact_phone3;
    }
    $body .= '</div>';
    $body .=  '</div>';
    if ($row10['paper_yn']) {
        $body .=  '          <div class="thumb" style="width:5%" onclick="show_paper_img(`' . $row_paper_info['img_url'] . '`)">';
        $body .=  '              <div class="thumb-inner">';
        $body .=  '                 <img src="/iam/img/menu/icon_my_stroy.png" style="height: 24px;width: 24px;">';
        $body .=  '             </div>';
        $body .=  '         </div>';

        $body .=  '          <div class="thumb paper-date" style="width:15%">';
        $body .=  '              <div class="thumb-inner">';
        $reg_dates = explode(" ",$row10['reg_date']);
        $body .=  '			<span style="font-size:12px;font-weight:bold;display:inline-block;width:max-content;margin-top:1px">'.$reg_dates[0].'</span>';
        $body .=  '			<span style="font-size:12px;font-weight:bold;display:inline-block;width:max-content;margin-top:1px">'.$reg_dates[1].'</span>';
        $body .=  '              </div>';
        $body .=  '         </div>';
    }
    $body .=  '               <div class="number" style="width:15%">';
    $body .=  '                    <div class="downer">';

    $sql7 = "select count(idx) from Gn_Iam_Name_Card use index(card_phone) where card_phone = '$contact_phone'";
    $result7 = mysqli_query($self_con, $sql7);
    $card_phone_count = mysqli_fetch_array($result7);
    if ((int)$card_phone_count[0] == 0) {
        $body .=  '<span style="color:red">OFF</span>';
    } else {
        $body .=  '<span style="color:green">ON</span>';
    }

    $body .=  '                    </div>';
    $body .=  '                    <div class="upper" style="margin-top:0px;font-weight:bold">';
    if ($_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master)
        $body .=  '<a href="tel:' . $contact_phone . '">' . $contact_phone . '</a>';
    else
        $body .=  iconv_substr($contact_phone, 0, 6, "utf-8") . "**-****";

    $body .=  '                   </div>';
    $body .=  '                </div>';

    if ($_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) {
        $body .=  '            <div class="check" style="width:5%">';
        $body .=  '               <input type="checkbox" name="paper_chk" id="inputItem' . $row10['idx'] . '" class="paper checkboxes input css-checkbox"';
        $body .=  '                    onclick="paper_chk_count()" value="' . $row10['idx'] . '">';
        $body .=  '              <label for="inputItem' . $row10['idx'] . '" class="css-label cb0"></label>';
        $body .=  '                <input type="hidden" name="paper_idx' . $row10['idx'] . '"';
        $body .=  '        id="paper_idx' . $row10['idx'] . '"';
        $body .=  '                   value="' . $row10['recv_num'] . '">';
        $body .=  '           </div>';
    }
    $body .=  '            </div>';
    $body .=  '        </li>';
}

$body .=  '</ul>';
$body .=  '</div>';
$body .=  '<div class="pagination">';
$body .=  '<ul>';

if ($page <= 1) { //만약 page가 1보다 크거나 같다면 빈값
} else {
    $pre = $page - 1; //pre변수에 page-1을 해준다 만약 현재 페이지가 3인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함
    $body .=  '<li class="arrow"><a href="javascript:getIamPaper(\'' . $card_owner . '\', \'' . $card_master . '\',\'' . $search_range . '\',\'' . $phone_count . '\',\'' . $pre . '\')"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>';
}
for ($i = $block_start; $i <= $block_end; $i++) {
    if ($page == $i) { //만약 page가 $i와 같다면
        $body .= '<li class="active"><span>' . $i . '</span></li>'; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
    } else {
        $body .= '<li><a href="javascript:getIamPaper(\'' . $card_owner . '\', \'' . $card_master . '\',\'' . $search_range . '\',\'' . $phone_count . '\',\'' . $i . '\')";>' . $i . '</a></li>'; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
    }
}
if ($block_num >= $total_block) { //만약 현재 블록이 블록 총개수보다 크거나 같다면 빈 값
} else {
    $next = $page + 1; //next변수에 page + 1을 해준다.
    $body .= '<li class="arrow"><a href="javascript:getIamPaper(\'' . $card_owner . '\', \'' . $card_master . '\',\'' . $search_range . '\',\'' . $phone_count . '\',\'' . $next . '\')"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>'; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
}
if ($_SESSION['iam_member_id'] == $card_owner) {
    $body .= '    <li style="float:right">';
    $body .= '        <a style="background-image: url(/iam/img/main/icon-kakaoimg.png);border:none !important;border-radius: 0%;height: 27px; width: 27px; background-size: 27px 27px;" href="javascript:paper_sms();"></a>';
    $body .= '    </li>';
    $body .= '   <li style="float:right;">';
    $body .= '        <a style="background-image: url(/iam/img/star/icon-bin.png);border:none !important;border-radius: 0%;height: 27px; width: 27px; background-size: 27px 27px;" href="javascript:paper_del();"></a>';
    $body .= '    </li>';
}
$body .= '</ul>';
$body .= '</div>';

echo $body;
?>
