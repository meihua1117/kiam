<?
include_once "../lib/rlatjd_fun.php";

$grp_id = $_POST['grp_id'];
$send_num = $_POST['send_num'];

$count = 0;
if($_POST['iam']){
    $table = "Gn_MMS_Receive_Iam";
}
else{
    $table = "Gn_MMS_Receive";
}
$sql_recv_num = "select recv_num from ".$table." where grp_id='{$grp_id}'";
$res_recv_num = mysqli_query($self_con, $sql_recv_num);
while($row_recv_num = mysqli_fetch_array($res_recv_num)){
    $sql_chk = "select idx from Gn_MMS_Deny where send_num='{$send_num}' and recv_num='{$row_recv_num[0]}' and (chanel_type=9 or chanel_type=4)";
    $res_chk = mysqli_query($self_con, $sql_chk);
    if(mysqli_num_rows($res_chk)){
        $count++;
    }
}

echo $count;
?>