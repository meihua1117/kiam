<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/
extract($_POST);
$id_arr = array();
if(strpos($id, ",") !== false){
    $id_arr = explode(",", $id);
}
else{
    $id_arr[0] = $id;
}

for($i = 0; $i < count($id_arr); $i++){
    $query = "select * from Gn_Iam_Service where idx={$id_arr[$i]}";
    $res = mysqli_query($self_con,$query);
    $row = mysqli_fetch_array($res);

    $sql = "select count(idx) from Gn_Service where sub_domain = '{$row['sub_domain']}'";
    $res = mysqli_query($self_con,$sql);
    $old_row = mysqli_fetch_array($res);

    if($old_row[0] == 0){
        $query="insert into Gn_Service set `service_name`          ='$row[web_theme]', 
                                    `domain`      ='{$row['main_domain']}', 
                                    `sub_domain` ='{$row['sub_domain']}', 
                                    `company_name` ='{$row['company_name']}', 
                                    `manage_cell`   ='{$row['owner_cell']}', 
                                    `manage_name`      ='{$row['owner_name']}',
                                    `communications_vendors` ='{$row['communications_vendors']}',
                                    `privacy` ='{$row['privacy']}',
                                    `fax` ='{$row['fax']}',
                                    ceo_name = '{$row['owner_name']}',
                                    address ='{$row['address']}',
                                    price='{$row['month_price']}',
                                    logo='{$row['head_logo']}',
                                    main_url='{$row['home_link']}',
                                    member_cnt='{$row['mem_cnt']}',
                                    main_image='{$row['main_img1']}',
                                    footer_image='{$row['footer_logo']}',
                                    contract_start_date='{$row['contract_start_date']}',
                                    contract_end_date='{$row['contract_end_date']}',
                                    mem_id='{$row['mem_id']}',
                                    mem_name='{$row['mem_name']}',
                                    site_name='{$row['brand_name']}',
                                    keywords='{$row['keywords']}',
                                    kakao='{$row['kakao']}',
                                    `status`          ='Y', 
                                    `regdate`         =NOW() ";
        mysqli_query($self_con,$query) or die(mysqli_error($self_con));
        $query="update Gn_Iam_Service set ai_card_point = 0,
                                        auto_member_point = 0,
                                        card_send_point = 0,
                                        contents_send_point = 0,
                                        autodata_point = 0,
                                        callback_set_point = 0,
                                        daily_set_point = 0,
                                        ai_point_start = NOW(),
                                        ai_point_end = '$row[contract_end_date]',
                                        automem_point_start = NOW(),
                                        automem_point_end = '$row[contract_end_date]',
                                        card_point_start = NOW(),
                                        card_point_end = '$row[contract_end_date]',
                                        contents_point_start = NOW(),
                                        contents_point_end = '$row[contract_end_date]',
                                        autodata_point_start = NOW(),
                                        autodata_point_end = '$row[contract_end_date]',
                                        callback_point_start = NOW(),
                                        callback_point_end = '$row[contract_end_date]',
                                        daily_point_start = NOW(),
                                        daily_point_end  = '$row[contract_end_date]'
                                    where idx = {$row['idx']}";
        mysqli_query($self_con,$query) or die(mysqli_error($self_con));
        $domain = $row['sub_domain'];
        $domain_arr = explode(".", $domain);
        $site = $domain_arr[0];
        $site = str_replace("http://","",$site);
        $query="update Gn_Member set site = '$site' where mem_id = '{$row['mem_id']}'";
        mysqli_query($self_con,$query) or die(mysqli_error($self_con));
    }
}
echo json_encode(array("result"=>"ok"));
exit;
?>