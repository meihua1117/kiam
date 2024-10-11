
<?
include_once "../../lib/rlatjd_fun.php";

$start_date = $_GET['start_date'];
$end_date = $_GET['end_date'];

$body = '<div class="modal-dialog" style="margin: 100px auto;width: fit-content;">';
$body .= '<div class="modal-content">';
$body .= '    <div>';
$body .= '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">';
$body .= '            <img src="/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">';
$body .= '        </button>';
$body .= '    </div>';
$body .= '    <div class = "modal-title" style="width:100%;font-size:18px;text-align: center;background:#82C836;color:white;">';
$body .= '        <label>회원가입 메시지 리스트</label>';
$body .= '    </div>';
$body .= '    <div class="modal-header">';
$body .= '        <div class="container" style="display:inline-block;margin-top: 20px;">';
$body .= '            <input type="date" placeholder="시작일" id="search_start_date1" value="'. $start_date .'" style="border: 1px solid black;width:130px;"><span style="margin-left: 3px;">~</span>';
$body .= '            <input type="date" placeholder="종료일" id="search_end_date1" value="'. $end_date .'" style="border: 1px solid black;width:130px;">';
$body .= '            <button onclick="search_auto()" class="btn-link"><img src ="/iam/img/menu/icon_bottom_search.png" style="width:24px"></button>';
$body .= '        </div>';
$body .= '    </div>';
$body .= '    <div class="modal-body">';
$body .= '        <div class="container" style="margin-top: 20px;text-align: center;">';
$body .= '            <table style="width:100%">';
$body .= '            <thead>';
$body .= '            <th class="iam_table" style="width:5%;">No</th>';
$body .= '            <th class="iam_table" style="width:22%;">제목</th>';
$body .= '            <th class="iam_table" style="width:15%;">보기/링크</th>';
$body .= '            <th class="iam_table" style="width:15%;">이미지</th>';
$body .= '            <th class="iam_table">조회/가입</th>';
$body .= '            <th class="iam_table">등록일</th>';
$body .= '            <th class="iam_table">수정/삭제</th>';
$body .= '            <th class="iam_table">이용</th>';
$body .= '            </thead>';
$body .= '            <tbody id="contents_side1">';

                $sql = "select * from Gn_event where m_id='{$_SESSION['iam_member_id']}' and event_name_kor='단체회원자동가입및아이엠카드생성' order by regdate desc";
                $res = mysqli_query($self_con,$sql);
                $i = 0;
                while($row = mysqli_fetch_array($res)){
                    $pop_url = '/event/automember.php?pcode='.$row['pcode'].'&eventidx='.$row['event_idx'];
                    $id_sql = "select count(event_id) as cnt from Gn_Member where event_id={$row['event_idx']} and mem_type='A'";
                    $res_id = mysqli_query($self_con,$id_sql);
                    $row_id = mysqli_fetch_array($res_id);
                    if($row_id['cnt'] != null){
                        $cnt_join = $row_id['cnt'];
                    }else{
                        $cnt_join = 0;
                    }
                    $i++;
                    $sql_service = "select auto_join_event_idx from Gn_Iam_Service where mem_id='{$row['m_id']}'";
                    $res_service = mysqli_query($self_con,$sql_service);
                    $row_service = mysqli_fetch_array($res_service);
                    if($row["event_idx"] == $row_service['auto_join_event_idx']){
                        $checked_auto = "checked";
                    }else{
                        $checked_auto = "";
                    }
                
$body .= '                <tr>';
$body .= '                    <td class="iam_table">'. $i .'</td>';
$body .= '                    <td class="iam_table">'. $row['event_title'] .'</td>';
$body .= '                    <td class="iam_table"><a onclick="newpop(\''.$pop_url.'\')">미리보기</a>/<a onclick="copyHtml(\''. $row['short_url'].'\')">링크복사</a></td>';
$body .= '                    <td class="iam_table">'; 
if($row['object'] != "")
{ 
    $body .= '<img class="zoom" src="'. $row['object'].'" style="width:90%;">';
}
$body .= '                    </td>';
$body .= '                    <td class="iam_table">'. $row['read_cnt'] .'/'.$cnt_join.'</td>';
$body .= '                    <td class="iam_table">'. $row['regdate'].'</td>';
$body .= '                    <td class="iam_table"><a onclick="edit_ev('. $row['event_idx'].')">수정</a>/<a onclick="delete_ev('.$row['event_idx'].')">삭제</a></td>';
                        $class_name = ($_SESSION['iam_member_id'] == "iam1")?"auto_switch_copy":"auto_switch";
$body .= '                        <td class="iam_table">';
$body .= '                            <label class="'. $class_name.'">';
$body .= '                                <input type="checkbox" name="auto_status" id="auto_stauts_'. $row['event_idx'].'" value="'.$row['event_idx'].'" '.$checked_auto.'>';
$body .= '                                <span class="slider round" name="auto_status_round" id="auto_stauts_round_'. $row['event_idx'].'"></span>';
$body .= '                            </label>';
$body .= '                            <input type="hidden" name="auto_service_id" id="auto_service_id" value="'. $row['m_id'] .'">';
$body .= '                        </td>';

$body .= '                </tr>';
                }
$body .= '            </tbody>';
$body .= '            </table>';
$body .= '        </div>';
$body .= '    </div>';
$body .= '</div>';
$body .= '</div>';

echo $body;
?>