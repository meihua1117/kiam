
<?
include_once "../../lib/rlatjd_fun.php";

$search_key = $_GET['search_key'];
if($search_key != "")
    $search_sql = " and (userid like '%".$search_key."%' or name like '%".$search_key."%')";
$sql = "select * from gn_report_table where userid = '{$_SESSION['iam_member_id']}' order by idx";
$repo_res = mysqli_query($self_con, $sql);
$count = 0;
$body = "";
while($repo_row = mysqli_fetch_array($repo_res)){
    $sql = "select id,short_url,title,status from gn_report_form where id = {$repo_row['repo_id']}";
    $form_res = mysqli_query($self_con, $sql);
    $form_row = mysqli_fetch_array($form_res);
    if($form_row['status'] == 1){
        $count ++;
        $pre_link = "/iam/report_preview.php?repo={$form_row['id']}";
        $link = $form_row['short_url'];
        $rep_link = "/iam/report_view.php?repo=".$form_row['id']."&idx=".$repo_row['idx'];
        $body .= "<div class=\"content_area\" style=\"margin-left:15px;margin-right:15px;\">".
                    "<div class=\"content-item\" style=\"position:relative;box-shadow:none;border-bottom:none\">".
                        "<div style=\"display:flex;justify-content: space-between;\">".
                            "<div style=\"margin-top:5px\">".
                                "<a style=\"text-decoration-line:none;font-size:10px;margin-right:20px\">".
                                    $repo_row['reg_date'].
                                "</a>".
                            "</div>".
                        "</div>".
                        "<div style=\"font-weight:bold\">".$form_row['title']."</div>".
                        "<div style=\"display:flex;justify-content: space-between;margin-top:10px\">".
                            "<input type=\"button\" style=\"width:30%\" value=\"미리보기\" class=\"button\" onclick=\"previewReport('".$pre_link."')\">".
                            "<input type=\"button\" style=\"width:30%\" value=\"링크복사\" class=\"button copyReportLink\" onclick=\"copyReportLink();\" data-link=\"".$link."\" >".
                            "<input type=\"button\" style=\"width:30%\" value=\"답변보기\" class=\"button copyReportLink\" onclick=\"location.href='".$rep_link."'\" data-link=\"".$link."\" >".
                        "</div>".
                    "</div>".
                "</div>";
    }else{
        continue;
    }
}

if($count == 0){
    $body = "<div class=\"content_area\" style=\"text-align:center\">".
                "<img src=\"/iam/img/menu/no_alarm.png\" ><br>".
                "<a style=\"text-decoration-line:none;font-weight:bold\">해당 알림이 없습니다.</a>".
              "</div>";
}
$body .= "<script>$('#alarm_count_text').html('총 <span style=\"color:#99cc00\">".$count."</span>개의 신청내역');</script>";
echo $body;
?>