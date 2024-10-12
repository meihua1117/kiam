<?
include_once "../lib/rlatjd_fun.php";

$recv_nums = $_POST['deny_add_recv'];
$mem_id = $_POST['mem_id'];
$chanel = $_POST['reg_chanel'];
$type = $_POST['type'];
$k = $u = 0;
if(strpos($recv_nums, ",") !== false){
    $recv_nums_arr = explode(",", $recv_nums);
}
else if(strpos($recv_nums, "\n") !== false){
    $recv_nums_arr = explode("\n", $recv_nums);
}
else{
    $recv_nums_arr[0] = $recv_nums;
}

// echo count($recv_nums_arr); exit;
for($c = 0; $c < count($recv_nums_arr); $c++){
    $recv_num=str_replace(array("-"," ",","),"",$recv_nums_arr[$c]);

    $is_zero=substr($recv_num,0,1);
    $recv_num=$is_zero?"0".$recv_num:$recv_num;
    $recv_num = preg_replace("/[^0-9]/i", "", $recv_num);
    if(!check_cellno($recv_num)){
        continue;
    }
    $send_num=str_replace(array("-"," ",","),"",$_POST['deny_add_send']);
    $is_zero=substr($send_num,0,1);
    $send_num=$is_zero?"0".$send_num:$send_num;
    $send_num = preg_replace("/[^0-9]/i", "", $send_num);
    if(!check_cellno($send_num)){
        echo 4;
        exit;
    }
    $sql_num="select sendnum from Gn_MMS_Number where mem_id ='$mem_id' and sendnum='$send_num' ";
    $resul_num=mysqli_query($self_con,$sql_num);
    $row_num=mysqli_fetch_array($resul_num);
    if(!$row_num['sendnum']){
        echo 2;
        exit;
    }
    if($type == "deny"){
        $sql_search=" and (chanel_type=1 or chanel_type=4)";
        if($chanel == 9){
            $sql_search=" and chanel_type=9";
        }
        if($chanel == 1 || $chanel == 4){
            $sql_search=" and (chanel_type=1 or chanel_type=4)";
        }
        if($chanel == 2){
            $sql_search=" and chanel_type=2";
        }
        
        $sql_s="select idx from Gn_MMS_Deny where recv_num='$recv_num' and send_num='$send_num'".$sql_search;
        $resul_s=mysqli_query($self_con,$sql_s);
        $row_s=mysqli_fetch_array($resul_s);
        if($row_s['idx']){
            continue;
        }
        $deny_info['send_num']=$send_num;
        $deny_info['recv_num']=$recv_num;
        if($_POST['deny_add_idx']){
            $sql="update Gn_MMS_Deny set ";
        }else{
            $sql="insert into Gn_MMS_Deny set ";
            $deny_info['title']="수동입력";
            $deny_info['content']="수동입력";
            $deny_info['status']="B";
            $deny_info['chanel_type']=$chanel;
            $deny_info['mem_id']=$mem_id;
        }
        $i=0;
        foreach($deny_info as $key=>$v){
            $bd=$i==count($deny_info)-1?"":",";
            $sql.=" $key='$v' $bd ";
            $i++;
        }
        if($_POST['deny_add_idx'])
            $sql.=" where idx='{$_POST['deny_add_idx']}' ";
        else
            $sql.=" , reg_date=now() ";
        if(mysqli_query($self_con,$sql) or die(mysqli_error($self_con))){
            $k++;
        }
    }
    else if($type == "undeny"){
        $sql_del = "delete from Gn_MMS_Deny where recv_num='$recv_num' and send_num='$send_num' and chanel_type=9";
        if(mysqli_query($self_con,$sql_del) or die(mysqli_error($self_con))){
            $u++;
        }
    }
}

if($k){
    echo 1;
}
else{
    if($u){
        echo 5;
    }
    else{
        echo 3;
    }
}
exit;
?>