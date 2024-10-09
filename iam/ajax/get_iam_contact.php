
<?
include_once "../../lib/rlatjd_fun.php";
// if($cur_win != "my_info")
// {
//     echo '';
//     exit;
// }

$search_str = $_GET['search_str'];
$card_owner = $_GET['card_owner'];
$search_range = $_GET['search_range'];
$card_master = $_GET['card_master'];
$phone_count = $_GET['phone_count'];
$paper_yn = $_GET['paper_yn'];


if($search_str !== "") {
    $contact_sql_msg = "and (name like '%$search_str%' or recv_num like '%$search_str%')";
} else {
    $contact_sql_msg = "";
}

if($paper_yn){
    $contact_sql_msg .= " and paper_yn=1";
}
else{
    $contact_sql_msg .= "";
}

$sql9="select count(idx) from Gn_MMS_Receive_Iam where mem_id = '$card_owner' and grp = '아이엠' $contact_sql_msg";
$result9=mysql_query($sql9);
$comment_row9=mysql_fetch_array($result9);
$row_num9 = $comment_row9[0];

$list = 10; //한 페이지에 보여줄 개수
$block_ct = 10; //블록당 보여줄 페이지 개수

if($_GET['page']) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$block_num = ceil($page/$block_ct); // 현재 페이지 블록 구하기
$block_start = (($block_num - 1) * $block_ct) + 1; // 블록의 시작번호
$block_end = $block_start + $block_ct - 1; //블록 마지막 번호
$total_page = ceil($row_num9 / $list); // 페이징한 페이지 수 구하기
if($block_end > $total_page) $block_end = $total_page; //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
$total_block = ceil($total_page/$block_ct); //블럭 총 개수
$start_num = ($page-1) * $list; //시작번호 (page-1)에서 $list를 곱한다.

if((int)$search_range == 1) {
    $contact_sql_msg = $contact_sql_msg. " order by name";
} else if((int)$search_range == 2) {
    $contact_sql_msg = $contact_sql_msg. " order by name desc";
} else if((int)$search_range == 3) {
    $contact_sql_msg = $contact_sql_msg. " order by idx desc";
} else if((int)$search_range == 4) {
    $contact_sql_msg = $contact_sql_msg. " order by idx";
} else {
    $contact_sql_msg = $contact_sql_msg. " order by idx desc";
}
$body = '<div class="contact-list">';
$body .= '<ul>';

$sql10="select idx,name,reg_date,recv_num,paper_yn,paper_seq from Gn_MMS_Receive_Iam where mem_id = '$card_owner' and grp = '아이엠' $contact_sql_msg limit $start_num, $list";
$result10=mysql_query($sql10) or die(mysql_error());
//file_put_contents("iamlog.txt", $sql10 . "\n", FILE_APPEND);
while($row10=mysql_fetch_array($result10)){

    if((int)$row10['recv_num'] > 10) {
        $contact_phone1 = substr($row10['recv_num'], 0, 3);
        $contact_phone2 = substr($row10['recv_num'], 3, 4);
        $contact_phone3 = substr($row10['recv_num'], 7, 4);

        $contact_phone = $contact_phone1."-".$contact_phone2."-".$contact_phone3;
    } else {
        $contact_phone1 = substr($row10['recv_num'], 0, 3);
        $contact_phone2 = substr($row10['recv_num'], 3, 3);
        $contact_phone3 = substr($row10['recv_num'], 6, 4);

        $contact_phone = $contact_phone1."-".$contact_phone2."-".$contact_phone3;
    }
    
    $body .=  '<li class="list-item">';
    $body .=  '     <div class="item-wrap">';
    $body .=  '          <div class="thumb">';
    $body .=  '              <div class="thumb-inner">';
    $body .=  '                 <img src="/iam/img/profile-img.svg" style="margin-top:5px">';
    $body .=  '             </div>';
    $body .=  '         </div>';
    if($row10['paper_yn']){
        $sql_paper_info = "select seq,img_url from Gn_Member_card where seq='{$row10['paper_seq']}'";
        $res_paper_info = mysql_query($sql_paper_info);
        $row_paper_info = mysql_fetch_array($res_paper_info);
        $body .=  '         <div class="info" onclick="edit_paper('.$row_paper_info['seq'].')">';
    }
    else{
        $body .=  '         <div class="info">';
    }
    $body .=  '             <div class="upper">';
    $body .=  '                     <span class="name">';
    if($_SESSION['iam_member_id'] == $card_owner && $phone_count > 0) {
        if($row10['name']) 
            $body .= $row10['name'];
        else
            $body .= $contact_phone3;                                    
    }else{
        if($row10['name'])
            $body .= iconv_substr($row10['name'], 0, 1, "utf-8")."**";
        else
            $body .= $contact_phone3;                                    
    }
    $body .=    '</span>';
    $body .=  '</div>';
    $body .=  '<div class="downer">'. $row10['reg_date'] . '</div>';
    $body .=  '</div>';
    if($row10['paper_yn']){
        $body .=  '          <div class="thumb" onclick="show_comment(`'.$row_paper_info['seq'].'`)">';
        $body .=  '              <div class="thumb-inner">';
        $body .=  '                 <img src="/iam/img/menu/icon_my_stroy.png" style="height: 42px;width: 42px;">';
        $body .=  '             </div>';
        $body .=  '         </div>';
    }
    $body .=  '               <div class="number">';
    $body .=  '                    <div class="downer">';
                    
    $sql7="select count(idx) from Gn_Iam_Name_Card use index(card_phone) where card_phone = '$contact_phone'";
    $result7=mysql_query($sql7);
    $card_phone_count=mysql_fetch_array($result7);
    if((int)$card_phone_count[0] == 0) {
        $body .=  '<span style="color:red">OFF</span>';
    }else{
        $body .=  '<span style="color:green">ON</span>';
    }
                    
    $body .=  '                    </div>';
    $body .=  '                    <div class="upper">';
    if($_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master)
        $body .=  '<a href="tel:'. $contact_phone. '">'. $contact_phone. '</a>';
    else
        $body .=  iconv_substr($contact_phone, 0, 6, "utf-8")."**-****";
    
    $body .=  '                   </div>';
    $body .=  '                </div>';

    if($_SESSION['iam_member_id'] == $card_owner && $_SESSION['iam_member_id'] == $card_master) {
        $body .=  '            <div class="check">';
        $body .=  '               <input type="checkbox" name="contact_chk" id="inputItem'. $row10['idx']. '" class="contacts checkboxes input css-checkbox"';
        $body .=  '                    onclick="contact_chk_count()" value="' . $row10['idx']. '">';
        $body .=  '              <label for="inputItem'. $row10['idx']. '" class="css-label cb0"></label>';
        $body .=  '                <input type="hidden" name="contact_idx'. $row10['idx']. '"';
        $body .=  '        id="contact_idx'. $row10['idx']. '"';
        $body .=  '                   value="' . $row10['recv_num']. '">';
        $body .=  '           </div>';
    }
    $body .=  '            </div>';
    $body .=  '        </li>';
}

$body .=  '</ul>';
$body .=  '</div>';
$body .=  '<div class="pagination">';
$body .=  '<ul>';

if($page <= 1) { //만약 page가 1보다 크거나 같다면 빈값
} else {
    $pre = $page-1; //pre변수에 page-1을 해준다 만약 현재 페이지가 3인데 이전버튼을 누르면 2번페이지로 갈 수 있게 함
    $body .=  '<li class="arrow"><a href="javascript:getIamContact(\''. $card_owner. '\', \''. $card_master. '\',\''. $search_range. '\',\''. $phone_count. '\',\''. $pre. '\',\''.$paper_yn.'\')"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>';
}
for($i=$block_start; $i<=$block_end; $i++){
    if($page == $i) { //만약 page가 $i와 같다면
        $body .= '<li class="active"><span>'. $i. '</span></li>'; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
    } else {
        $body .= '<li><a href="javascript:getIamContact(\''. $card_owner. '\', \''. $card_master. '\',\''. $search_range. '\',\''. $phone_count. '\',\''. $i. '\',\''.$paper_yn.'\')";>'. $i. '</a></li>'; //현재 페이지에 해당하는 번호에 굵은 빨간색을 적용한다
    }
}
if($block_num >= $total_block) { //만약 현재 블록이 블록 총개수보다 크거나 같다면 빈 값
} else {
    $next = $page + 1; //next변수에 page + 1을 해준다.
    $body .= '<li class="arrow"><a href="javascript:getIamContact(\''. $card_owner. '\', \''. $card_master. '\',\''. $search_range. '\',\''. $phone_count. '\',\''. $next. '\',\''.$paper_yn.'\')"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>'; //다음글자에 next변수를 링크한다. 현재 4페이지에 있다면 +1하여 5페이지로 이동하게 된다.
}
if($_SESSION['iam_member_id'] == $card_owner) {
    $body .= '    <li style="float:right">';
    $body .= '        <a style="background-image: url(/iam/img/main/icon-kakaoimg.png);border:none !important;border-radius: 0%;height: 27px; width: 27px; background-size: 27px 27px;" href="javascript:contact_sms();"></a>';
    $body .= '    </li>';
    $body .= '   <li style="float:right;">';
    $body .= '        <a style="background-image: url(/iam/img/star/icon-bin.png);border:none !important;border-radius: 0%;height: 27px; width: 27px; background-size: 27px 27px;" href="javascript:contact_del();"></a>';
    $body .= '    </li>';
}
$body .= '</ul>';
$body .= '</div>';

echo $body;
?>

