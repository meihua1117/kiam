
<?
include_once "../../lib/rlatjd_fun.php";

$mem_id = $_SESSION[iam_member_id];

$sql_list_count = "select count(*) from Gn_event_request where req_id ='{$mem_id}' order by request_idx desc";
$res_list_count = mysql_query($sql_list_count);
$row_list_count = mysql_fetch_array($res_list_count);

$row_num5 = $row_list_count[0];

if(!$row_num5){
    $body = '<tr>
                <td colspan="7" style="text-align:center;">
                    신청항목이 없습니다.
                </td>
            </tr>';
    echo $body;
    exit;
}

$sql_list = "select * from Gn_event_request where req_id ='{$mem_id}' order by request_idx desc";

// $list2 = 10; //한 페이지에 보여줄 개수
// $block_ct2 = 10; //블록당 보여줄 페이지 개수

// if($_GET['page2']) {
//     $page2 = $_GET['page2'];
// } else {
//     $page2 = 1;
// }

$block_num2 = ceil($page2/$block_ct2); // 현재 페이지 블록 구하기
$block_start2 = (($block_num2 - 1) * $block_ct2) + 1; // 블록의 시작번호
$block_end2 = $block_start2 + $block_ct2 - 1; //블록 마지막 번호
$total_page2 = ceil($row_num5 / $list2); // 페이징한 페이지 수 구하기
if($block_end2 > $total_page2) $block_end2 = $total_page2; //만약 블록의 마지박 번호가 페이지수보다 많다면 마지박번호는 페이지 수
$total_block2 = ceil($total_page2/$block_ct2); //블럭 총 개수
$start_num2 = ($page2-1) * $list2; //시작번호 (page-1)에서 $list를 곱한다.

$limit_str = "limit " . $start_num2 . ", " . $list2;
$sql6 = $sql_list.$limit_str;
$result6=mysql_query($sql_list);
while($row6=mysql_fetch_array($result6)){
    $sql_event_data = "select event_title, m_id from Gn_event where event_idx={$row6['event_idx']}";
    $res_event_data = mysql_query($sql_event_data);
    $row_event_data = mysql_fetch_array($res_event_data);

    $sql_mem = "select mem_phone from Gn_Member where mem_id='{$row_event_data[m_id]}'";
    $res_mem = mysql_query($sql_mem);
    $row_mem = mysql_fetch_array($res_mem);
        
    $body .= '<tr>';
    $body .= '    <td class="iam_table">'.$row6[m_id].'</td>';
    $body .= '    <td class="iam_table">'.$row6[name].'</td>';
    $body .= '    <td class="iam_table">'.$row_event_data[event_title].'</td>';
    $body .= '    <td class="iam_table">'.str_replace("-", "", $row6[mobile]).'</td>';
    $body .= '    <td class="iam_table">'.str_replace("-", "", $row_mem[mem_phone]).'</td>';
    $body .= '    <td class="iam_table">'.$row6[regdate].'</td>';
    if($row6[req_yn] == "Y"){
        $body .= '    <td class="iam_table" onclick="cancel_req('.$row6[request_idx].')" style="cursor:pointer;">취소</td>';
    }
    else{
        $body .= '    <td class="iam_table" onclick="allow_req('.$row6[request_idx].')" style="cursor:pointer;">신청</td>';
    }
    
    $body .= '<tr>';
}
echo $body;
?>