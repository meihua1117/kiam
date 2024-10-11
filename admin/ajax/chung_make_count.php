<?
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
extract($_POST);

if(isset($_POST['mem_id']) && isset($_POST['share_point']) && $_POST['share_point']){
    $mem_id = $_POST['mem_id'];
    $point = $_POST['share_point'] * 1;

    $type = $_POST['changetype'];

    $sql="select * from Gn_Member where mem_id='$mem_id' and site != '' ";
    $result=mysqli_query($self_con,$sql);
    if(!mysqli_num_rows($result)){
	echo 4; exit;
    }
    $data=mysqli_fetch_array($result);

    if($type == "cha"){
        $sql_chk = "select * from Gn_Item_Pay_Result where point_val!=0 and buyer_id='{$mem_id}' and pay_status='Y'";
        $res_chk = mysqli_query($self_con,$sql_chk);
        if(mysqli_num_rows($res_chk) == 0){
            echo 2; exit;
        }
        else{
            // $get_point = "select current_point from Gn_Item_Pay_Result where buyer_id='{$mem_id}' and point_val!=0 and pay_status='Y' order by pay_date desc limit 1";
            // echo $get_point; exit;
            // $result_point = mysqli_query($self_con,$get_point);
            // $row_point = mysqli_fetch_array($result_point);
            if($data['mem_point'] * 1 < $point){
                echo 3; exit;
            }
            else{
                $current_point = $data['mem_point'] * 1 - $point;
                $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_id}', buyer_tel='{$data['mem_phone']}', item_name='씨드포인트차감', item_price={$point}, pay_percent=90, current_point={$current_point}, current_cash={$data['mem_cash']}, pay_status='Y', VACT_InputName='{$data['mem_name']}', type='minus', seller_id='{$_SESSION['one_member_id']}', pay_method='관리자씨드차감', pay_date=now(), point_val=1";
                // echo $sql; exit;
                mysqli_query($self_con,$sql);
                $sql_update = "update Gn_Member set mem_point={$current_point} where mem_id='{$mem_id}'";
                mysqli_query($self_con,$sql_update);
                echo 1; exit;
            }
        }
    }
    else{
        // $sql_chk = "select * from Gn_Item_Pay_Result where point_val!=0 and buyer_id='{$mem_id}' and pay_status='Y'";
        // $res_chk = mysqli_query($self_con,$sql_chk);
        // if(mysqli_num_rows($res_chk) == 0){
        $current_point = $data['mem_point'] * 1 + $point;
        $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_id}', buyer_tel='{$data['mem_phone']}', item_name='씨드포인트충전', item_price={$point}, pay_percent=90, current_point={$current_point}, current_cash={$data['mem_cash']}, pay_status='Y', VACT_InputName='{$data['mem_name']}', type='buy', seller_id='{$_SESSION['one_member_id']}', pay_method='관리자씨드충전', pay_date=now(), point_val=1";
        // echo $sql; exit;
        mysqli_query($self_con,$sql);
        $sql_update = "update Gn_Member set mem_point={$current_point} where mem_id='{$mem_id}'";
        mysqli_query($self_con,$sql_update);
        echo 1; exit;
        // }
        // else{
        //     $get_point = "select current_point from Gn_Item_Pay_Result where buyer_id='{$mem_id}' and point_val!=0 and pay_status='Y' order by pay_date desc limit 1";
        //     // echo $get_point; exit;
        //     $result_point = mysqli_query($self_con,$get_point);
        //     $row_point = mysqli_fetch_array($result_point);
    
        //     $current_point = $row_point['current_point'] * 1 + $point;
        //     $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_id}', buyer_tel='{$data['mem_phone']}', item_name='포인트충전', item_price={$point}, pay_percent=90, current_point={$current_point}, pay_status='Y', VACT_InputName='{$data['mem_name']}', type='buy', seller_id='{$_SESSION['one_member_id']}', pay_method='관리자충전', pay_date=now(), point_val=1";
        //     // echo $sql; exit;
        //     mysqli_query($self_con,$sql);
        //     echo 1; exit;
        // }
    }
}
if(isset($_POST['mem_id']) && isset($_POST['share_cash']) && $_POST['share_cash']){
    $mem_id = $_POST['mem_id'];
    $cash = $_POST['share_cash'] * 1;

    $type = $_POST['changetype'];

    $sql="select * from Gn_Member where mem_id='$mem_id' and site != '' ";
    $result=mysqli_query($self_con,$sql);
    if(!mysqli_num_rows($result)){
        echo 4; exit;
    }
    $data=mysqli_fetch_array($result);

    if($type == "chacash"){
        $sql_chk = "select * from Gn_Item_Pay_Result where point_val!=0 and buyer_id='{$mem_id}' and pay_status='Y'";
        $res_chk = mysqli_query($self_con,$sql_chk);
        if(mysqli_num_rows($res_chk) == 0){
            echo 2; exit;
        }
        else{
            // $get_point = "select current_point from Gn_Item_Pay_Result where buyer_id='{$mem_id}' and point_val!=0 and pay_status='Y' order by pay_date desc limit 1";
            // echo $get_point; exit;
            // $result_point = mysqli_query($self_con,$get_point);
            // $row_point = mysqli_fetch_array($result_point);
            if($data['mem_cash'] * 1 < $cash){
                echo 3; exit;
            }
            else{
                $current_cash = $data['mem_cash'] * 1 - $cash;
                $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_id}', buyer_tel='{$data['mem_phone']}', item_name='캐시포인트차감', item_price={$cash}, pay_percent=90, current_point={$data[mem_point]}, current_cash={$current_cash}, pay_status='Y', VACT_InputName='{$data['mem_name']}', type='minus', seller_id='{$_SESSION['one_member_id']}', pay_method='관리자캐시차감', pay_date=now(), point_val=1";
                // echo $sql; exit;
                mysqli_query($self_con,$sql);
                $sql_update = "update Gn_Member set mem_cash={$current_cash} where mem_id='{$mem_id}'";
                mysqli_query($self_con,$sql_update);
                echo 1; exit;
            }
        }
    }
    else{
        // $sql_chk = "select * from Gn_Item_Pay_Result where point_val!=0 and buyer_id='{$mem_id}' and pay_status='Y'";
        // $res_chk = mysqli_query($self_con,$sql_chk);
        // if(mysqli_num_rows($res_chk) == 0){
        $current_cash = $data['mem_cash'] * 1 + $cash;
        $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_id}', buyer_tel='{$data['mem_phone']}', item_name='캐시포인트충전', item_price={$cash}, pay_percent=90, current_point={$data[mem_point]}, current_cash={$current_cash}, pay_status='Y', VACT_InputName='{$data['mem_name']}', type='buy', seller_id='{$_SESSION['one_member_id']}', pay_method='관리자캐시충전', pay_date=now(), point_val=1";
        // echo $sql; exit;
        mysqli_query($self_con,$sql);
        $sql_update = "update Gn_Member set mem_cash={$current_cash} where mem_id='{$mem_id}'";
        mysqli_query($self_con,$sql_update);
        echo 1; exit;
        // }
        // else{
        //     $get_point = "select current_point from Gn_Item_Pay_Result where buyer_id='{$mem_id}' and point_val!=0 and pay_status='Y' order by pay_date desc limit 1";
        //     // echo $get_point; exit;
        //     $result_point = mysqli_query($self_con,$get_point);
        //     $row_point = mysqli_fetch_array($result_point);
    
        //     $current_point = $row_point['current_point'] * 1 + $point;
        //     $sql = "insert into Gn_Item_Pay_Result set buyer_id='{$mem_id}', buyer_tel='{$data['mem_phone']}', item_name='포인트충전', item_price={$point}, pay_percent=90, current_point={$current_point}, pay_status='Y', VACT_InputName='{$data['mem_name']}', type='buy', seller_id='{$_SESSION['one_member_id']}', pay_method='관리자충전', pay_date=now(), point_val=1";
        //     // echo $sql; exit;
        //     mysqli_query($self_con,$sql);
        //     echo 1; exit;
        // }
    }
}
?>