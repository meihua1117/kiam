
<?
include_once "../../lib/rlatjd_fun.php";

$search_key = $_GET['search_key'];
if($search_key != "")
    $search_sql = " and (m_id like '%".$search_key."%' or name like '%".$search_key."%')";
$sql_list_count = "select count(*) from Gn_event_request where req_id='{$_SESSION['iam_member_id']}' $search_sql order by request_idx desc";
$res_list_count = mysqli_query($self_con, $sql_list_count);
$row_list_count = mysqli_fetch_array($res_list_count);
$req_count = $row_list_count[0];
$body = "<script>$('#alarm_count_text').html('총 <span style=\"color:#99cc00\">".$req_count."</span>개의 신청내역');</script>";

if($req_count == 0){
    $body .= "<div class=\"content_area\" style=\"text-align:center\">".
                "<img src=\"/iam/img/menu/no_alarm.png\" ><br>".
                "<a style=\"text-decoration-line:none;font-weight:bold\">해당 알림이 없습니다.</a>".
              "</div>";
    echo $body;
}else{
    $sql_list = "select * from Gn_event_request where req_id='{$_SESSION['iam_member_id']}' $search_sql order by request_idx desc";
    $result=mysqli_query($self_con, $sql_list);
    while($row=mysqli_fetch_array($result)){
        $sql_event_data = "select event_title, m_id from Gn_event where event_idx={$row['event_idx']}";
        $res_event_data = mysqli_query($self_con, $sql_event_data);
        $row_event_data = mysqli_fetch_array($res_event_data);
        if($row['req_yn'] == "Y"){
            $status = "<a style=\"text-decoration-line:none;font-size:10px\">신청완료</a>";
            $button = "<li><a onclick=\"cancel_req('".$row['request_idx']."');\">취소하기</a></li>";
        }else{
            $status = "<a style=\"text-decoration-line:none;font-size:10px;color:red;\">신청취소</a>";
            $button = "<li><a onclick=\"allow_req('".$row['request_idx']."')\">신청하기</a></li>";
        }
        $sql_mem = "select mem_phone from Gn_Member where mem_id='{$row_event_data['m_id']}'";
        $res_mem = mysqli_query($self_con, $sql_mem);
        $row_mem = mysqli_fetch_array($res_mem);
        $body .= "<div class=\"content_area\" style=\"margin-left:15px;margin-right:15px;\">".
                    "<div class=\"content-item\" style=\"position:relative;box-shadow:none;border-bottom:none\">".
                        "<div style=\"display:flex;justify-content: space-between;\">".
                            "<div style=\"margin-top:5px\">".
                                "<a style=\"text-decoration-line:none;font-size:10px;margin-right:20px\">".
                                    $row['regdate'].
                                "</a>".
                                $status.
                            "</div>".
                            "<div class=\"dropdown\" style=\"\">".
                                "<button class=\"btn-link dropdown-toggle westory_dropdown\" type=\"button\" data-toggle=\"dropdown\" style=\"margin-top:0px;height:24px\">".
                                    "<img src=\"/iam/img/menu/icon_dot.png\" style=\"height:24px\">".
                                "</button>".
                                "<ul class=\"dropdown-menu comunity\">".
                                    $button.
                                    "<li><a onclick=\"del_req('".$row['request_idx']."')\">삭제하기</a></li>".
                                "</ul>".
                            "</div>".
                        "</div>".
                        
                        "<div style=\"font-weight:bold\">".$row_event_data['event_title']."</div>".
                        "<div style=\"display:flex;justify-content: space-between;\">".
                            "<a style=\"text-decoration-line:none;font-size:12px;\">".$row['name']."&nbsp;&nbsp;".str_replace("-", "", $row['mobile'])."</a>".
                            "<a style=\"text-decoration-line:none;font-size:12px;\">"."발신&nbsp;&nbsp;".$row_mem['mem_phone']."</a>".
                        "</div>".
                    "</div>".
                "</div>";
        /*$body .= '<tr>';
        $body .= '    <td class="iam_table">'.$row['m_id'].'</td>';
        $body .= '    <td class="iam_table">'.$row['name'].'</td>';
        $body .= '    <td class="iam_table">'.$row_event_data['event_title'].'</td>';
        $body .= '    <td class="iam_table">'.str_replace("-", "", $row['mobile']).'</td>';
        $body .= '    <td class="iam_table">'.str_replace("-", "", $row_mem['mem_phone']).'</td>';
        $body .= '    <td class="iam_table">'.$row['regdate'].'</td>';
        if($row['req_yn'] == "Y"){
            $body .= '    <td class="iam_table" onclick="cancel_req('.$row['request_idx'].')" style="cursor:pointer;">취소</td>';
        }
        else{
            $body .= '    <td class="iam_table" onclick="allow_req('.$row['request_idx'].')" style="cursor:pointer;">신청</td>';
        }
        
        $body .= '<tr>';*/
    }
    echo $body;
}
?>