<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);
// 오늘날짜
$date_today=date("Y-m-d");
if(isset($_POST['send_id']) && isset($_POST['receive_id'])){
    $send_id = $_POST['send_id'];
    $receive_id = $_POST['receive_id'];
    $share_point = $_POST['share_point'];

    $sql_chk_mem = "select mem_id from Gn_Member where mem_id='{$receive_id}'";
    $result_mem = mysqli_query($self_con, $sql_chk_mem);
    $row_mem = mysqli_fetch_array($result_mem);
    if($row_mem['mem_id'] == NULL){
        echo 0;
        exit;
    }

    $sql_send="select * from Gn_Member where mem_id='{$send_id}' and site != '' ";
    $resul_send=mysqli_query($self_con, $sql_send);
    $data_send=mysqli_fetch_array($resul_send);

    // $get_point = "select current_point from Gn_Item_Pay_Result where buyer_id='{$send_id}' and point_val!=0 and pay_status='Y' order by pay_date desc limit 1";
    // echo $get_point; exit;
    $get_point = "select mem_point, mem_cash from Gn_Member where mem_id='{$send_id}'";
    $result_point = mysqli_query($self_con, $get_point);
    $row_point = mysqli_fetch_array($result_point);
    $method_send = $data_send['mem_id']."/".$data_send['mem_name'];

    $sql_recv="select * from Gn_Member where mem_id='{$receive_id}' and site != '' ";
    $resul_recv=mysqli_query($self_con, $sql_recv);
    $data_recv=mysqli_fetch_array($resul_recv);
    $method_recv = $data_recv['mem_id']."/".$data_recv['mem_name'];

    if($share_point){
        $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$send_id}', buyer_tel='{$data_send['mem_phone']}', item_name='씨드포인트전송', item_price=$share_point, pay_date=now(), pay_percent=90, pay_status='Y', VACT_InputName='{$data_send['mem_name']}', pay_method='$method_recv', seller_id='{$receive_id}', point_val=1, type='shareuse', current_cash={$data_send['mem_cash']}, current_point={$row_point['mem_point']} - $share_point";
    mysqli_query($self_con, $sql);

    $sql_update = "update Gn_Member set mem_point=mem_point-{$share_point} where mem_id='{$send_id}'";
    mysqli_query($self_con, $sql_update);

    // $sql_check = "select * from Gn_Item_Pay_Result where buyer_id='{$receive_id}' and point_val!=0 and pay_status='Y' order by pay_date desc limit 1";
    $sql_check = "select mem_point from Gn_Member where mem_id='{$receive_id}'";
    $res_chk = mysqli_query($self_con, $sql_check);
    $row_chk = mysqli_fetch_array($res_chk);
    
    // if(mysqli_num_rows($res_chk) == 0){
        $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$receive_id}', buyer_tel='{$data_recv['mem_phone']}', item_name='씨드포인트수신', item_price=$share_point, pay_date=now(), pay_percent=90, pay_status='Y', VACT_InputName='{$data_recv['mem_name']}', pay_method='$method_send', seller_id='{$send_id}', point_val=1, type='sharebuy', current_cash={$data_recv['mem_cash']}, current_point={$row_chk['mem_point']}+{$share_point}";
    mysqli_query($self_con, $sql);

    $sql_update1 = "update Gn_Member set mem_point=mem_point+{$share_point} where mem_id='{$receive_id}'";
    mysqli_query($self_con, $sql_update1);
    }
    if($share_cash){
        $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$send_id}', buyer_tel='{$data_send['mem_phone']}', item_name='캐시포인트전송', item_price=$share_cash, pay_date=now(), pay_percent=90, pay_status='Y', VACT_InputName='{$data_send['mem_name']}', pay_method='$method_recv', seller_id='{$receive_id}', point_val=1, type='shareuse', current_point={$data_send['mem_point']}, current_cash={$row_point['mem_cash']} - $share_cash";
        mysqli_query($self_con, $sql);
    
        $sql_update = "update Gn_Member set mem_cash=mem_cash-{$share_cash} where mem_id='{$send_id}'";
        mysqli_query($self_con, $sql_update);
    
        // $sql_check = "select * from Gn_Item_Pay_Result where buyer_id='{$receive_id}' and point_val!=0 and pay_status='Y' order by pay_date desc limit 1";
        $sql_check = "select mem_cash from Gn_Member where mem_id='{$receive_id}'";
        $res_chk = mysqli_query($self_con, $sql_check);
        $row_chk = mysqli_fetch_array($res_chk);
        
        // if(mysqli_num_rows($res_chk) == 0){
        $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$receive_id}', buyer_tel='{$data_recv['mem_phone']}', item_name='캐시포인트수신', item_price=$share_cash, pay_date=now(), pay_percent=90, pay_status='Y', VACT_InputName='{$data_recv['mem_name']}', pay_method='$method_send', seller_id='{$send_id}', point_val=1, type='sharebuy', current_point={$data_recv['mem_point']}, current_cash={$row_chk['mem_cash']}+{$share_cash}";
        mysqli_query($self_con, $sql);
    
        $sql_update1 = "update Gn_Member set mem_cash=mem_cash+{$share_cash} where mem_id='{$receive_id}'";
        mysqli_query($self_con, $sql_update1);
    }
    
    // }
    // else{
    //     $get_point = "select current_point from Gn_Item_Pay_Result where buyer_id='{$receive_id}' and point_val!=0 and pay_status='Y' order by pay_date desc limit 1";
    //     // echo $get_point; exit;
    //     $result_point = mysqli_query($self_con, $get_point);
    //     $row_point = mysqli_fetch_array($result_point);

    //     $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$receive_id}', buyer_tel='{$data_recv['mem_phone']}', item_name='포인트수신', item_price=$share_point, pay_date=now(), pay_percent=90, pay_status='Y', VACT_InputName='{$data_recv['mem_name']}', pay_method='$method_send', seller_id='{$send_id}', point_val=1, type='sharebuy', current_point={$row_point['current_point']} + $share_point";
    //     mysqli_query($self_con, $sql);
    // }

    echo 1;
    
}
?>