<?
@header("Content-type: text/html; charset=utf-8");
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
/*
* Comment : 관리자 회원 정보 수정
*/

$cur_time = date("Y-m-d H:i:s");
$cur_time1 = date("YmdHis");
extract($_POST);

if($mode == "creat"){
    $sql_create = "insert into gn_reg_customer set mem_id='{$mem_id}', name='{$name}', phone1='{$phone1}', phone2='{$phone2}', email='{$email}', birthday='{$birthday}', work_type='{$work_type}', company_name='{$company_name}', job='{$job}', company_addr='{$company_addr}', home_addr='{$home_addr}', link='{$link}', memo='{$memo}', reg_date='{$cur_time}'";
    $res = mysqli_query($self_con, $sql_create);

    echo $res;
}
else if($mode == "edit"){
    if($type == "reg_cust_edit" || $type == "member_reg_edit"){
        $sql_update = "update gn_reg_customer set name='{$name}', phone1='{$phone1}', phone2='{$phone2}', email='{$email}', birthday='{$birthday}', work_type='{$work_type}', company_name='{$company_name}', job='{$job}', company_addr='{$company_addr}', home_addr='{$home_addr}', link='{$link}', memo='{$memo}' where id='{$idx}'";
        $res = mysqli_query($self_con, $sql_update);
    }
    else if($type == "req_cust_edit" || $type == "member_req_edit"){
        $sql_update = "update Gn_event_request set name='{$name}', mobile='{$phone1}', mobile1='{$phone2}', email='{$email}', birthday='{$birthday}', work_type='{$work_type}', company_name='{$company_name}', job='{$job}', addr='{$company_addr}', addr1='{$home_addr}', memo='{$memo}' where request_idx='{$idx}'";
        $res = mysqli_query($self_con, $sql_update);
    }
    else if($type == "get_cust_edit" || $type == "member_get_edit"){
        $sql_update = "update crawler_data_supply set ceo='{$name}', cell='{$phone1}', cell1='{$phone2}', email='{$email}', birthday='{$birthday}', company_type='{$work_type}', company_name='{$company_name}', address='{$company_addr}', address1='{$home_addr}', memo='{$memo}' where seq='{$idx}'";
        $res = mysqli_query($self_con, $sql_update);
    }

    if($list_idx){
        $sql_update_mem = "update Gn_Member set mem_name='{$name}', mem_phone='{$phone1}', mem_phone1='{$phone2}', mem_email='{$email}', mem_birth='{$birthday}', com_type='{$work_type}', zy='{$company_name}', mem_job='{$job}', com_add1='{$company_addr}', mem_add1='{$home_addr}', mem_memo='{$memo}' where mem_code='{$list_idx}'";
        $res = mysqli_query($self_con, $sql_update_mem);
    }
    echo $res;
}
else if($mode == "edit_table"){
    if($type == "member_reg_edit" || $type == "member_req_edit" || $type == "member_get_edit"){
        if($type == "member_reg_edit") $prefix = "reg_";
        if($type == "member_req_edit") $prefix = "req_";
        if($type == "member_get_edit") $prefix = "get_";

        $sql_mem_data = "select * from Gn_Member where mem_code='{$idx}'";
        $res_mem_data = mysqli_query($self_con, $sql_mem_data);
        $row_mem_data = mysqli_fetch_array($res_mem_data);

        $recommend_link = "https://".$HTTP_HOST."/ma.php?mem_code=".$row_mem_data['mem_code'];

        echo '{"'.$prefix.'name":"'.$row_mem_data['mem_name'].'", "'.$prefix.'phone1":"'.$row_mem_data['mem_phone'].'", "'.$prefix.'phone2":"'.$row_mem_data['mem_phone1'].'", "'.$prefix.'email":"'.$row_mem_data['mem_email'].'", "'.$prefix.'birthday":"'.$row_mem_data['mem_birth'].'", "'.$prefix.'work_type":"'.$row_mem_data['com_type'].'", "'.$prefix.'company_name":"'.$row_mem_data['zy'].'", "'.$prefix.'job":"'.$row_mem_data['mem_job'].'", "'.$prefix.'company_addr":"'.$row_mem_data['com_add1'].'", "'.$prefix.'home_addr":"'.$row_mem_data['mem_add1'].'", "'.$prefix.'link":"'.$recommend_link.'", "'.$prefix.'memo":"'.$row_mem_data['mem_memo'].'"}';
    }
    else if($type == "reg_cust_edit"){
        $sql_mem_data = "select * from gn_reg_customer where id='{$idx}'";
        $res_mem_data = mysqli_query($self_con, $sql_mem_data);
        $row_mem_data = mysqli_fetch_array($res_mem_data);

        echo '{"reg_name":"'.$row_mem_data['name'].'", "reg_phone1":"'.$row_mem_data['phone1'].'", "reg_phone2":"'.$row_mem_data['phone2'].'", "reg_email":"'.$row_mem_data['email'].'", "reg_birthday":"'.$row_mem_data['birthday'].'", "reg_work_type":"'.$row_mem_data['work_type'].'", "reg_company_name":"'.$row_mem_data['company_name'].'", "reg_job":"'.$row_mem_data['job'].'", "reg_company_addr":"'.$row_mem_data['company_addr'].'", "reg_home_addr":"'.$row_mem_data['home_addr'].'", "reg_link":"'.$row_mem_data['link'].'", "reg_memo":"'.$row_mem_data['memo'].'"}';
    }
    else if($type == "req_cust_edit"){
        $sql_mem_data = "select a.*, b.short_url from Gn_event_request a inner join Gn_event b on a.event_idx=b.event_idx where a.request_idx='{$idx}'";
        $res_mem_data = mysqli_query($self_con, $sql_mem_data);
        $row_mem_data = mysqli_fetch_array($res_mem_data);

        echo '{"req_name":"'.$row_mem_data['name'].'", "req_phone1":"'.$row_mem_data['mobile'].'", "req_phone2":"'.$row_mem_data['mobile1'].'", "req_email":"'.$row_mem_data['email'].'", "req_birthday":"'.$row_mem_data['birthday'].'", "req_work_type":"'.$row_mem_data['work_type'].'", "req_company_name":"'.$row_mem_data['company_name'].'", "req_job":"'.$row_mem_data['job'].'", "req_company_addr":"'.$row_mem_data['addr'].'", "req_home_addr":"'.$row_mem_data['addr1'].'", "req_link":"'.$row_mem_data['short_url'].'", "req_memo":"'.$row_mem_data['memo'].'"}';
    }
    else if($type == "get_cust_edit"){
        $sql_mem_data = "select * from crawler_data_supply where seq='{$idx}'";
        // echo '{"sql":"'.$sql_mem_data.'"}'; exit;
        $res_mem_data = mysqli_query($self_con, $sql_mem_data);
        $row_mem_data = mysqli_fetch_array($res_mem_data);

        if(strpos($row_mem_data['cell'], '<span') !== false){
            $cell_arr = explode('<span', $row_mem_data['cell']);
        }

        echo '{"get_name":"'.$row_mem_data['ceo'].'", "get_phone1":"'.$cell_arr[0].'", "get_phone2":"'.$row_mem_data['cell1'].'", "get_email":"'.$row_mem_data['email'].'", "get_birthday":"'.$row_mem_data['birthday'].'", "get_work_type":"'.$row_mem_data['company_type'].'", "get_company_name":"'.$row_mem_data['company_name'].'", "get_job":"'.$row_mem_data['data_type'].'", "get_company_addr":"'.$row_mem_data['address'].'", "get_home_addr":"'.$row_mem_data['address1'].'", "get_link":"'.$row_mem_data['url'].'", "get_memo":"'.$row_mem_data['memo'].'"}';
    }
}
else if($mode == "move"){
    if($type == "req_cust"){
        $sql_move = "insert into gn_reg_customer (mem_id, name, phone1, phone2, email, birthday, work_type, company_name, job, company_addr, home_addr, link, reg_date, memo) (select a.m_id, a.name, a.mobile, a.mobile1, a.email, a.birthday, a.work_type, a.company_name, a.job, a.addr, a.addr1, b.short_url, '{$cur_time}', memo from Gn_event_request a inner join Gn_event b on a.event_idx=b.event_idx where a.request_idx='{$idx}')";
        mysqli_query($self_con, $sql_move);

        $sql_del = "delete from Gn_event_request where request_idx='{$idx}'";
        $res = mysqli_query($self_con, $sql_del);

        echo $res;
    }
    else if($type == "get_cust"){
        $sql_crawler_data_supply = "select * from crawler_data_supply where seq='{$idx}'";
        $res_crawler_data_supply = mysqli_query($self_con, $sql_crawler_data_supply);
        $row_crawler_data_supply = mysqli_fetch_array($res_crawler_data_supply);

        if(strpos($row_mem_data['cell'], '<span') !== false){
            $cell_arr = explode('<span', $row_mem_data['cell']);
        }

        $sql_move = "insert into gn_reg_customer set mem_id='{$row_crawler_data_supply['user_id']}', name='{$row_crawler_data_supply['ceo']}', phone1='{$cell_arr[0]}', phone2='{$row_crawler_data_supply['cell1']}', email='{$row_crawler_data_supply['email']}', birthday='{$row_crawler_data_supply['birthday']}', work_type='{$row_crawler_data_supply['company_type']}', company_name='{$row_crawler_data_supply['company_name']}', job='{$row_crawler_data_supply['data_type']}', company_addr='{$row_crawler_data_supply['address']}', home_addr='{$row_crawler_data_supply['address1']}', link='{$row_crawler_data_supply['url']}', reg_date='{$cur_time}', memo='{$row_crawler_data_supply['memo']}'";
        mysqli_query($self_con, $sql_move);

        $sql_del = "delete from crawler_data_supply where seq='{$idx}'";
        $res = mysqli_query($self_con, $sql_del);

        echo $res;
    }
}
else if($mode == "del"){
    if($delete_name == "reg_cust_list"){
        $table = "gn_reg_customer";
        $identify = "id";
    }
    else if($delete_name == "req_cust_list"){
        $table = "Gn_event_request";
        $identify = "request_idx";
    }
    else if($delete_name == "get_cust_list"){
        $table = "crawler_data_supply";
        $identify = "seq";
    }

    if(strpos($id, ",") !== false){
        $ids_arr = explode(",", $id);
        for($i = 0; $i < count($ids_arr); $i++){
            $sql_del = "delete from ".$table." where ".$identify."='{$ids_arr[$i]}'";
            $res = mysqli_query($self_con, $sql_del);
        }
    }
    else{
        $sql_del = "delete from ".$table." where ".$identify."='{$id}'";
        $res = mysqli_query($self_con, $sql_del);
    }
    echo $res;
}
?>