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
    $sql_insert = "INSERT Gn_Iam_Service (main_domain, sub_domain, mem_cnt, iamcard_cnt, 
                                        my_card_cnt, send_content, communications_vendors, privacy, 
                                        email, company_name, brand_name, business_number, 
                                        contract_start_date, contract_end_date, mem_id, mem_name, 
                                        owner_name, owner_cell, manager_name, manager_tel, 
                                        fax, status, address,web_theme, 
                                        head_logo_type,head_logo,head_logo_text, home_link,
                                        footer_logo_type, footer_logo, footer_logo_text, footer_link, 
                                        share_price, month_price, regdate, 
                                        main_img1, main_img2, main_img3, 
                                        keywords, kakao, pay_link, profile_idx, 
                                        consultation_request, kakao_api_key, service_type, service_price, 
                                        service_price_exp, ai_card_point, ai_point_start, ai_point_end, 
                                        auto_member_point, card_send_point, contents_send_point, autodata_point, 
                                        automem_point_start, automem_point_end, card_point_start, card_point_end, 
                                        contents_point_start, contents_point_end, autodata_point_start, autodata_point_end, 
                                        self_share_card, callback_set_point, callback_point_start, callback_point_end, 
                                        daily_set_point, daily_point_start, daily_point_end, point_state, 
                                        auto_join_event_idx, daily_msg_event_idx, duplicate_idx, dup_up_state) 
                                        (SELECT '', '', mem_cnt, iamcard_cnt, 
                                        my_card_cnt, send_content, communications_vendors, 
                                        privacy, email, company_name, '', 
                                        business_number, contract_start_date, contract_end_date, '', 
                                        mem_name, owner_name, owner_cell, manager_name, 
                                        manager_tel, fax, status, address, web_theme, 
                                        head_logo_type,head_logo,head_logo_text, home_link,
                                        footer_logo_type, footer_logo, footer_logo_text, footer_link,
                                        share_price, month_price, regdate, 
                                        main_img1, main_img2, main_img3,  
                                        keywords, kakao, pay_link, profile_idx, 
                                        consultation_request, kakao_api_key, service_type, 
                                        service_price, service_price_exp, ai_card_point, 
                                        ai_point_start, ai_point_end, auto_member_point, 
                                        card_send_point, contents_send_point, autodata_point, 
                                        automem_point_start, automem_point_end, card_point_start, 
                                        card_point_end, contents_point_start, contents_point_end, 
                                        autodata_point_start, autodata_point_end, self_share_card, 
                                        callback_set_point, callback_point_start, callback_point_end, 
                                        daily_set_point, daily_point_start, daily_point_end, 
                                        point_state, auto_join_event_idx, daily_msg_event_idx, 
                                        '{$id_arr[$i]}', 1 FROM Gn_Iam_Service where idx='{$id_arr[$i]}')";
        mysqli_query($self_con, $sql_insert) or die(mysqli_error($self_con));
}
echo json_encode(array("result"=>"ok"));
exit;
?>