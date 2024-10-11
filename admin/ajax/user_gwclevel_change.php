<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 레벨 수정
*/
extract($_POST);

$mem_code = $_POST["mem_code"]; 

if($_POST[mode] == "change_leb"){
    $gwc_leb = $_POST["gwc_leb"]; 
    // $sql_chk = "select gwc_state from Gn_Member where mem_code='{$mem_code}'";
	// $res_chk = mysqli_query($self_con,$sql_chk);
	// $row_chk = mysqli_fetch_array($res_chk);

    // $gwc_state = $row_chk[0];
    
    $sql_update = "update Gn_Member set gwc_req_leb='{$gwc_leb}', gwc_leb='{$gwc_leb}', gwc_state='1', gwc_req_date=NOW() where mem_code='{$mem_code}'";
    $res = mysqli_query($self_con,$sql_update);
    
    echo $res;
}
else if($_POST[mode] == "multi_change_leb"){
    $id = $_POST["id"];
    $state = $_POST['state'];
    if(strpos($id, ",") !== false){
        $id_arr = explode(",", $id);
        for($i = 0; $i < count($id_arr); $i++){
            $sql_update = "update Gn_Member set gwc_state='{$state}' where mem_code='{$id_arr[$i]}'";
            mysqli_query($self_con,$sql_update);
        }
    }
    else{
        $sql_update = "update Gn_Member set gwc_state='{$state}' where mem_code='{$id}'";
        mysqli_query($self_con,$sql_update);
    }
    echo 1;
}
else if($_POST[mode] == "change_state"){
    $state = $_POST['status'];

    $sql_update = "update Gn_Member set gwc_state='{$state}', gwc_accept_date=NOW() where mem_code='{$mem_code}'";
    $res = mysqli_query($self_con,$sql_update);
    
    echo $res;
}
else if($_POST[mode] == "accep_req"){
    $sql_chk = "select gwc_state from Gn_Member where mem_code='{$mem_code}'";
	$res_chk = mysqli_query($self_con,$sql_chk);
	$row_chk = mysqli_fetch_array($res_chk);

    $gwc_state = $row_chk[0];

    $sql_update = "update Gn_Member set gwc_state='{$gwc_state}', gwc_leb=gwc_req_leb, gwc_req_date=NOW() where mem_code='{$mem_code}'";
    $res = mysqli_query($self_con,$sql_update);
    
    echo $res;
}
else if($_POST[mode] == "change_info"){
    $info = $_POST['info'];

    $sql_update = "update crawler_data_supply set info='{$info}' where seq='{$mem_code}'";
    $res = mysqli_query($self_con,$sql_update);
    
    echo $res;
}
else if($_POST[mode] == "change_per"){
    if($_POST['type'] == "service"){
        $sql_update = "update Gn_Member set gwc_service_per='{$_POST['service_val']}' where mem_id='{$_POST['mem_id']}'";
    }
    else{
        $sql_update = "update Gn_Member set gwc_center_per='{$_POST['center_val']}' where mem_id='{$_POST['mem_id']}'";
    }
    $res = mysqli_query($self_con,$sql_update);

    echo $res;
}
?>