
<?
include_once "../../lib/rlatjd_fun.php";

$mem_id = $_SESSION['iam_member_id'];
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
echo $body;
?>