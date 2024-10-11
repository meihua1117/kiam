<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
$date = date("Y-m-d H:i:s");
// echo $position; exit; 
if($mode == "status"){
    $sql_update = "update Gn_App_Home_Manager set use_yn='{$status}' where idx='{$id}'";
    $res = mysqli_query($self_con,$sql_update);
}
else if($mode == "del"){
    $sql_del = "delete from Gn_App_Home_Manager where idx='{$idx}'";
    $res = mysqli_query($self_con,$sql_del);
}
else if($mode == "menu_desc"){
    $sql_chk = "select idx from Gn_App_Home_Manager where site_iam='{$site}' and ad_position is null";
    $res_chk = mysqli_query($self_con,$sql_chk);
    $row_chk = mysqli_fetch_array($res_chk);

    if($row_chk[idx]){
        $sql_update = "update Gn_App_Home_Manager set menu_desc='{$txt}', up_date='{$date}' where idx='{$row_chk[idx]}'";
        $res = mysqli_query($self_con,$sql_update);
    }
    else{
        $sql_insert = "insert into Gn_App_Home_Manager set menu_desc='{$txt}', site_iam='{$site}', reg_date='{$date}'";
        $res = mysqli_query($self_con,$sql_insert);
    }
}
else if($mode == "market_desc"){
    $sql_chk = "select idx from Gn_App_Home_Manager where site_iam='{$site}' and ad_position is null";
    $res_chk = mysqli_query($self_con,$sql_chk);
    $row_chk = mysqli_fetch_array($res_chk);

    if($row_chk[idx]){
        $sql_update = "update Gn_App_Home_Manager set market_desc='{$txt}', up_date='{$date}' where idx='{$row_chk[idx]}'";
        $res = mysqli_query($self_con,$sql_update);
    }
    else{
        $sql_insert = "insert into Gn_App_Home_Manager set market_desc='{$txt}', site_iam='{$site}', reg_date='{$date}'";
        $res = mysqli_query($self_con,$sql_insert);
    }
}
else if($mode == "card_desc"){
    $sql_chk = "select idx from Gn_App_Home_Manager where site_iam='{$site}' and ad_position is null";
    $res_chk = mysqli_query($self_con,$sql_chk);
    $row_chk = mysqli_fetch_array($res_chk);

    if($row_chk[idx]){
        $sql_update = "update Gn_App_Home_Manager set card_desc='{$txt}', up_date='{$date}' where idx='{$row_chk[idx]}'";
        $res = mysqli_query($self_con,$sql_update);
    }
    else{
        $sql_insert = "insert into Gn_App_Home_Manager set card_desc='{$txt}', site_iam='{$site}', reg_date='{$date}'";
        $res = mysqli_query($self_con,$sql_insert);
    }
}
else if($mode == "market_title"){
    $sql_chk = "select idx from Gn_App_Home_Manager where site_iam='{$site}' and ad_position is null";
    $res_chk = mysqli_query($self_con,$sql_chk);
    $row_chk = mysqli_fetch_array($res_chk);

    if($row_chk[idx]){
        $sql_update = "update Gn_App_Home_Manager set market_title='{$txt}', up_date='{$date}' where idx='{$row_chk[idx]}'";
        $res = mysqli_query($self_con,$sql_update);
    }
    else{
        $sql_insert = "insert into Gn_App_Home_Manager set market_title='{$txt}', site_iam='{$site}', reg_date='{$date}'";
        $res = mysqli_query($self_con,$sql_insert);
    }
}
else if($mode == "card_title"){
    $sql_chk = "select idx from Gn_App_Home_Manager where site_iam='{$site}' and ad_position is null";
    $res_chk = mysqli_query($self_con,$sql_chk);
    $row_chk = mysqli_fetch_array($res_chk);

    if($row_chk[idx]){
        $sql_update = "update Gn_App_Home_Manager set card_title='{$txt}', up_date='{$date}' where idx='{$row_chk[idx]}'";
        $res = mysqli_query($self_con,$sql_update);
    }
    else{
        $sql_insert = "insert into Gn_App_Home_Manager set card_title='{$txt}', site_iam='{$site}', reg_date='{$date}'";
        $res = mysqli_query($self_con,$sql_insert);
    }
}
else if($mode == "notice_title"){
    $sql_chk = "select idx from Gn_App_Home_Manager where site_iam='{$site}' and ad_position is null";
    $res_chk = mysqli_query($self_con,$sql_chk);
    $row_chk = mysqli_fetch_array($res_chk);

    if($row_chk[idx]){
        $sql_update = "update Gn_App_Home_Manager set notice_title='{$txt}', up_date='{$date}' where idx='{$row_chk[idx]}'";
        $res = mysqli_query($self_con,$sql_update);
    }
    else{
        $sql_insert = "insert into Gn_App_Home_Manager set notice_title='{$txt}', site_iam='{$site}', reg_date='{$date}'";
        $res = mysqli_query($self_con,$sql_insert);
    }
}
else if($mode == "change_site"){
    $sql_data = "select * from Gn_App_Home_Manager where idx='{$idx}'";
    $res_data = mysqli_query($self_con,$sql_data);
    $data = mysqli_fetch_array($res_data);

    $sql_chk_service = "select idx from Gn_Service where sub_domain='{$data[move_url]}'";
    $res_chk_service = mysqli_query($self_con,$sql_chk_service);
    $row_chk_service = mysqli_fetch_array($res_chk_service);

    if($row_chk_service[idx]){
        $site1 = explode("//", $data[move_url]);
        $site2 = explode(".", trim($site1[1]));
        $site = trim($site2[0]);

        $sql_update = "update Gn_Member set site='{$site}' where mem_id='{$_SESSION['iam_member_id']}'";
        $res = mysqli_query($self_con,$sql_update);
    }
    else{
        $sql_site = "select mem_id from Gn_Iam_Service where sub_domain='{$data[move_url]}'";
        $res_site = mysqli_query($self_con,$sql_site);
        $row_site = mysqli_fetch_array($res_site);

        $mem_site = "select site from Gn_Member where mem_id='{$row_site['mem_id']}'";
        $res_site = mysqli_query($self_con,$mem_site);
        $row_site = mysqli_fetch_array($res_site);

        $sql_update = "update Gn_Member set site='{$row_site[site]}' where mem_id='{$_SESSION['iam_member_id']}'";
        $res = mysqli_query($self_con,$sql_update);
    }
}
else if($mode == "set_show"){
    $sql_update = "update Gn_Iam_Service set admin_app_home='{$set_type}' where sub_domain like '%".$domain."%'";
    $res = mysqli_query($self_con,$sql_update);
}

echo $res;
exit;
?>