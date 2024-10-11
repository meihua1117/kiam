
<?
include_once "../../lib/rlatjd_fun.php";

$gkind = $_GET['gkind'];
$site_iam = $_GET['site_iam'];

$group_sql = "select * from gn_group_info where idx='$gkind'";
$group_res = mysqli_query($self_con,$group_sql);
$group_row = mysqli_fetch_array($group_res);

$group_count_sql = "select * from gn_group_member where group_id='$gkind'";
$group_count_res = mysqli_query($self_con,$group_count_sql);
$group_mem_count = mysqli_num_rows($group_count_res);

$body = '<div class="modal-dialog modal-sm" role="document" style="margin-top: 10px;width:95%;max-width:768px;margin-left:auto;margin-right:auto;">';
$body .= '<div class="modal-content">';
$body .= '    <div>';
$body .= '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >';
$body .= '            <img src = "/iam/img/menu/icon_close_white.png" style="width:24px" class="close" data-dismiss="modal" aria-hidden="true">';
$body .= '        </button>';
$body .= '    </div>';
$body .= '    <div class="modal-title" style="text-align:center; font-size:25px;font-weight:500;height:45px;background:#82c836;color:white">';
$body .= '        <label style="font-size:18px;margin-top:10px" id="group_info_title">'.$group_row['name'] .'</label>';
$body .= '    </div>';
$body .= '    <div class="modal-body" style="background-color: #e5e5e5;border-bottom-left-radius: 5px;border-bottom-right-radius: 5px;">';
$body .= '        <div style="padding-top: 2px;background:white;padding-bottom:15px">';
$body .= '            <div style="display:flex;justify-content: space-between;margin-left:10px;">';
$body .= '                <h4 style="margin-top:10px">정보</h4>';
if($_SESSION['iam_member_id'] == $group_row[manager]){
    $body .= '                <button type="button" class= "btn btn-link" style= "margin-right:10px" onclick = "edit_group_info(\''. $group_row[idx]. '\',\''. $group_row['name'] .'\',\''. $group_row[description] .'\',\''. $group_row[public_status] .'\',\''. $group_row[upload_status].'\')">';
    $body .= '                    <img src = "/iam/img/main/dots3.png" style="width:15px">';
    $body .= '                </button>';
}
$body .= '            </div>';
$body .= '            <h5 style="margin-left:10px;margin-top:5px">'. $group_row[description] .'</h5>';
$body .= '            <h4 style="margin-left:10px;margin-top:15px">';
$body .= ($group_row[public_status] == "Y")?'공개그룹':'비공개그룹';
$body .= '</h4>';
$body .= '            <h4 style="margin-left:10px;margin-top:5px">';
$body .= '                <small>';

                        if($group_row[public_status] == "Y")
                            $body .= '누구나 그룹 멤버와 게시물을 볼수 있습니다.';
                        else
                            $body .= '멤버만 그룹 멤버와 게시물을 볼수 있습니다.';

$body .= '                </small>';
$body .= '            </h4>';
$body .= '            <h4 style="margin-left:10px;margin-top:15px">';
$body .= $group_row[upload_status] == "Y"?'자동업로드':'수동업로드';
$body .= '</h4>';
$body .= '            <h4 style="margin-left:10px;margin-top:5px">';
$body .= '                <small>';
                    
                        if($group_row[upload_status] == "Y")
                            $body .= '회원의 콘텐츠가 자동으로 업로드 됩니다.';
                        else
                            $body .= '회원의 콘텐츠 업로드를 수동으로 승인합니다.';
                    
$body .= '                </small>';
$body .= '            </h4>';
if($_SESSION['iam_member_id'] == $group_row[manager]){
    $body .= '                <div style="display:flex;justify-content: space-between;margin-left:10px;margin-top:15px">';
    $body .= '                    <h4 style="">'. "총 멤버 ".$group_mem_count."명" .'</h4>';
    $body .= '                    <button type="button" class = "btn btn-link" onclick = "open_all_member_1('. $group_row[idx] .')">모두 보기</button>';
    $body .= '               </div>';
}else{
    $body .= '               <div style="display:flex;justify-content: space-between;margin-left:10px;margin-top:15px">';
    $body .= '    <h4 style="">'. "총 멤버 ".$group_mem_count."명" .'</h4>';
    $body .= '                    <button type="button" class = "btn btn-link" onclick = "open_all_member_2('. $group_row[idx] .')">모두 보기</button>';
    $body .= '                </div>';
}
$body .= '            <p style="outline : 1px solid black;margin:1px 10px"/>';
            
$f_sql = "select mem_id, mem_name, profile from Gn_Member where site_iam = '$site_iam' and mem_id in (select mem_id from gn_group_member where group_id='$group_row[idx]')";
$f_res = mysqli_query($self_con,$f_sql);
$f_count = mysqli_num_rows($f_res);
if($f_count > 0){
    $body .= '                <div style = "display:flex;margin-left:20px;margin-top:10px">';
    
    $f_index = 0;
    $f_name = "";
    while($f_row = mysqli_fetch_array($f_res)){
        if($f_name == "" && $f_row[mem_id] != $_SESSION['iam_member_id'])
            $f_name = $f_row['mem_name'];
        if($f_index++ < 12){
            $body .= '                        <div style="border:1px solid #ddd;border-radius: 50%;width: 30px;height: 30px;overflow: hidden;margin-left: -10px;">';
            $body .= '                            <img src="'. cross_image($f_row[profile]) .'" style="width: 100%;height:100%;">';
            $body .= '                        </div>';
    }
    }
    $body .= '                </div>';
    $body .= '                <h4 style="margin-left:10px;margin-top:5px;margin-bottom :5px"><small>'. $f_name."님 외 친구 ".($f_count-1)."명이 멤버입니다." .'</small></h4>';
}
$body .= '        </div>';
$body .= '    </div>';
$body .= '</div>';
$body .= '</div>';
echo $body;
?>