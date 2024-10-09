
<?
include_once "../../lib/rlatjd_fun.php";

$body = "";
$body .= '<div class="modal-content" style = "margin-right:0px;">';
$body .= '<div class="modal-header" style="border:none;background: #5bd540;display: flex;justify-content: space-between;">';
$body .= '    <button type="button" style="background-color: transparent" onclick="backIntro();">';
$body .= '        <h3 style="color: #ffffff"><</h3>';
$body .= '    </button>';
$body .= '    <label style="font-size:18px;color: #ffffff;">아이엠 주요 동영상</label>';
$body .= '    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick = "clickZoomOut()">';
$body .= '        <img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">';
$body .= '    </button>';
$body .= '</div>';

$vid_array = array();
$vid_sql = "select * from gn_video where type = 'iam' order by no";
$vid_res = mysql_query($vid_sql);
$vid_row = mysql_fetch_array($vid_res);

$body .= '<div class="modal-body" style="background-color: #e5e5e5;">';
$body .= '    <div style="position: absolute;top:10px;right:10px" id = "btnZoomIn" onclick = "clickZoomIn();">';
$body .= '        <img src = "/iam/img/main/icon-enlarge.png" style="width:20px">';
$body .= '    </div>';
$body .= '    <div>';
$body .= '        <div style="padding-top: 2px;" >';
$body .= '            <div style="background-color: #ffffff;border-radius: 10px;padding-bottom: 2px" id="zoomOutVideo">';
$body .= '                <iframe style="width:100%;height:150px;border-radius: 10px;" id="intro_video" src="'. $vid_row[link] .'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>';
$body .= '                </iframe>';
$body .= '            </div>';
$body .= '        </div>';
$body .= '        <div style="padding-top: 15px;">';
$body .= '            <div>';
$body .= '                <a href="javascript:changeVideo(\''. $vid_row[link] .'\');">';
$body .= '                    <p class = "mypage_list" style="font-size:14px;background-color: #ffffff;border-radius: 10px">'. $vid_row[title]. '</p>';
$body .= '                </a>';
$body .= '            </div>';
$body .= '        </div>';
        while($vid_row = mysql_fetch_array($vid_res)){
$body .= '            <div style="padding-top: 1px;">';
$body .= '                <div>';
$body .= '                    <a href="javascript:changeVideo(\''. $vid_row[link] .'\');">';
$body .= '                        <p class = "mypage_list" style="font-size:14px;background-color: #ffffff;border-radius: 10px">'. $vid_row[title]. '</p>';
$body .= '                    </a>';
$body .= '                </div>';
$body .= '            </div>';
        }
$body .= '    </div>';
$body .= '</div>';
$body .= '</div>';

echo $body;
?>