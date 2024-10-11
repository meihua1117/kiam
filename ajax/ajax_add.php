<?
include_once "../lib/rlatjd_fun.php";
if($_POST['mode'] == "monthly") {
   	$no=$_POST[no];
	
    $sql="update tjd_pay_result set monthly_status='R', cancel_requesttime = now() where no='$no'";
    mysqli_query($self_con,$sql);   
    echo "success"; 
    exit;	
}else if($_POST['mode'] == "monthly_db") {
    $no=$_POST[no];
    $sql="update tjd_pay_result_db set monthly_status='R', cancel_requesttime = now() where no='$no'";
    mysqli_query($self_con,$sql);   
    echo "success"; 
    exit;	
}else if($_POST['mode'] == "get_status"){
    $mem_id = $_SESSION['one_member_id'];
    $no = $_POST[no];
    $sql_res_data = "select idx, orderNumber, date from tjd_pay_result where no='$no'";
    $res_data = mysqli_query($self_con,$sql_res_data);
    $row_data = mysqli_fetch_array($res_data);

    $sql_month_res = "select count(*) as months from tjd_pay_result_month where pay_idx='{$row_data['idx']}' and order_number like '$row_data[orderNumber]%' and regdate >= '{$row_data[date]}'";
    $res_month = mysqli_query($self_con,$sql_month_res);
    $row_month = mysqli_fetch_array($res_month);
    $rest_month = 36 - $row_month['months'] * 1;

    $sql_penalty = "select penalty_money from gn_penalty_list where month='{$row_month[months]}'";
    $res_penalty = mysqli_query($self_con,$sql_penalty);
    $row_penalty = mysqli_fetch_array($res_penalty);
    $money = number_format($row_penalty[penalty_money]);
    echo json_encode(array("date"=>$row_data[date],"months"=>$rest_month,"penalty"=>$money));
}
?>