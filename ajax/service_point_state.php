<?
include_once "../lib/rlatjd_fun.php";

if(isset($_POST['point_stop'])){
    $mem_id = $_POST['service_id'];

    $sql="update Gn_Iam_Service set point_state=0 where mem_id='{$mem_id}'";
    mysqli_query($self_con,$sql);
    echo 1;
}
else if(isset($_POST['point_use'])){
    $mem_id = $_POST['service_id'];

    $sql="update Gn_Iam_Service set point_state=1 where mem_id='{$mem_id}'";
    mysqli_query($self_con,$sql);
    echo 1;
}
?>