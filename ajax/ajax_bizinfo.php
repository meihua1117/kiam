<?
header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');
date_default_timezone_set('Asia/Seoul');
include_once "../lib/rlatjd_fun.php";

// $host = "175.118.126.178";
// $dbname = "ilearning3";
// $dbpass = "happy2022days";
// $dbuser = "ilearning3_";
// $GLOBALS['con'] = mysqli_connect($host, $dbuser, $dbpass, $dbname);
// if($GLOBALS['con']->connect_error) {
//     exit;
// }

$reply_content = array();
$reply_content_jiwon = array();
$reply_content_edu = array();
$reply_content_pub = array();
$reply_content_sanrim = array();
$reply_content_hist = array();

$time = time()-(86400*365);
$comp_date = date("Y-m-d", $time);

$today = date('Y-m-d')." 00:00:00";
$today1 = date('Y-m-d H:i:s');
$sql_search = "";
$sql_order = "";
$reply_index = 0;
$reply_index_jiwon = 0;
$reply_index_edu = 0;
$reply_index_pub = 0;
$reply_index_other = 0;
$page_num = 1;
$sql_limit = "";

$sql_share = "select * from share_contents_mng where share_obj='장수군청산림과'";
$res_share = mysql_query($sql_share);
$row_share = mysql_fetch_array($res_share);

if($row_share['end_date'] < $today){
    $sql_update = "update share_contents_mng set share_state=2 where share_obj='장수군청산림과'";
    mysql_query($sql_update);
}
else{
    $sql_update = "update share_contents_mng set share_state=1 where share_obj='장수군청산림과'";
    mysql_query($sql_update);
}

if($row_share['share_state'] == 2){
    exit;
}

$sql_key_search_work = get_search_key($row_share['work_key']);
$sql_key_search_public = get_search_key($row_share['public_key']);
$sql_key_search_edu = get_search_key($row_share['edu_key']);
$sql_key_search_other = get_search_key($row_share['other_key']);

if(isset($_POST['biz_jiwon_list'])){//지원사업정보 api
    $sql_jiwon = "select * from get_crawler_bizinfo where web_type='지원사업' and info_source='기업마당' and allow_state=1 and allow_state_biz=1 and reg_date>'{$comp_date}'".$sql_key_search_work;

    $sql_order = " order by reg_date desc limit 0, 20";
    if(isset($_POST['page_num']) || isset($_POST['search_key']) || isset($_POST['exception']) || isset($_POST['regdate_order']) || isset($_POST['showcnt_order'])){
        $sql_order = " order by reg_date desc";
        if(isset($_POST['exception'])){
            if($_POST['exception'] == 1){
                $sql_search .= " and work_status=1";
            }
            else if($_POST['exception'] == 2){
                $sql_search .= "";
            }
        }
        
        if(isset($_POST['regdate_order'])){
            if($_POST['regdate_order'] == "asc"){
                $sql_order = " order by reg_date asc";
            }
            else if ($_POST['regdate_order'] == "desc"){
                $sql_order = " order by reg_date desc";
            }
        }
        
        if(isset($_POST['showcnt_order'])){
            if($_POST['showcnt_order'] == "asc"){
                $sql_order = "order by show_cnt asc";
            }
            else if ($_POST['showcnt_order'] == "desc"){
                $sql_order = "order by show_cnt desc";
            }
        }
        
        if(isset($_POST['page_num'])){
            $_POST['page_num'] ? $page_num = $_POST['page_num']:$page_num = 1;
        }
        if(isset($_POST['search_key'])){
            $search_key = $_POST['search_key'];
            $sql_search .= " and (work_name like '%".$search_key."%' or org_name like '%".$search_key."%')";
        }
        
        $start = ($page_num - 1) * 20;
        $sql_limit = " limit {$start}, 20";
    }

    $sql = $sql_jiwon.$sql_search;
    // echo $sql; exit;
    $res_cnt = mysql_query($sql);
    $total_cnt = mysql_num_rows($res_cnt);

    $sql .= $sql_order.$sql_limit;
    $res_jiwon = mysql_query($sql);
    $number = $total_cnt - ($page_num - 1) * 20;

    while($row_jiwon = mysql_fetch_array($res_jiwon)){
        $arr_replay['id'] = $row_jiwon['id'];
        $arr_replay['org_name'] = $row_jiwon['org_name'];
        $arr_replay['work_name'] = $row_jiwon['work_name'];
        $arr_replay['symbol'] = $row_jiwon['symbol'];
        $arr_replay['reg_date'] = $row_jiwon['reg_date'];
        $arr_replay['req_date'] = $row_jiwon['req_date'];
        $arr_replay['show_cnt'] = $row_jiwon['show_cnt'];
        $arr_replay['detail_link'] = $row_jiwon['detail_link'];
        $reply_content[$reply_index++] = $arr_replay;
    }
    echo json_encode(array('total_cnt'=>$total_cnt, 'number'=>$number, 'page_num'=>$page_num, 'jiwon_res'=>$reply_content));
}
else if(isset($_POST['biz_edu_list'])){//행사교육정보 api
    $sql_edu = "select * from get_crawler_bizinfo where web_type='행사교육' and info_source='기업마당' and allow_state=1 and allow_state_biz=1 and reg_date>'{$comp_date}'".$sql_key_search_edu;

    $sql_order = " order by reg_date desc limit 0, 20";
    if(isset($_POST['page_num']) || isset($_POST['search_key']) || isset($_POST['exception']) || isset($_POST['regdate_order']) || isset($_POST['showcnt_order'])){
        $sql_order = " order by reg_date desc";
        if(isset($_POST['exception'])){
            if($_POST['exception'] == 1){
                $sql_search .= " and work_status=1";
            }
            else if($_POST['exception'] == 2){
                $sql_search .= "";
            }
        }
        
        if(isset($_POST['regdate_order'])){
            if($_POST['regdate_order'] == "asc"){
                $sql_order = " order by reg_date asc";
            }
            else if ($_POST['regdate_order'] == "desc"){
                $sql_order = " order by reg_date desc";
            }
        }
        
        if(isset($_POST['showcnt_order'])){
            if($_POST['showcnt_order'] == "asc"){
                $sql_order = "order by show_cnt asc";
            }
            else if ($_POST['showcnt_order'] == "desc"){
                $sql_order = "order by show_cnt desc";
            }
        }
        
        if(isset($_POST['page_num'])){
            $_POST['page_num'] ? $page_num = $_POST['page_num']:$page_num = 1;
        }
        if(isset($_POST['search_key'])){
            $search_key = $_POST['search_key'];
            $sql_search .= " and (work_name like '%".$search_key."%' or org_name like '%".$search_key."%')";
        }
        
        $start = ($page_num - 1) * 20;
        $sql_limit = " limit {$start}, 20";
    }

    $sql = $sql_edu.$sql_search;
    
    $res_cnt = mysql_query($sql);
    $total_cnt = mysql_num_rows($res_cnt);

    $sql .= $sql_order.$sql_limit;
    $res_edu = mysql_query($sql);
    $number = $total_cnt - ($page_num - 1) * 20;

    while($row_edu = mysql_fetch_array($res_edu)){
        $arr_replay['id'] = $row_edu['id'];
        $arr_replay['org_name'] = $row_edu['org_name'];
        $arr_replay['region_name'] = $row_edu['region_name'];
        $arr_replay['work_type'] = $row_edu['work_type'];
        $arr_replay['work_name'] = $row_edu['work_name'];
        $arr_replay['symbol'] = $row_edu['symbol'];
        $arr_replay['reg_date'] = $row_edu['reg_date'];
        $arr_replay['show_cnt'] = $row_edu['show_cnt'];
        $arr_replay['detail_link'] = $row_edu['detail_link'];
        $reply_content[$reply_index++] = $arr_replay;
    }
    echo json_encode(array('total_cnt'=>$total_cnt, 'number'=>$number, 'page_num'=>$page_num, 'edu_res'=>$reply_content));
}
else if(isset($_POST['nara_public_list'])){//입찰공고정보 api
    $sql_pub = "select * from get_crawler_bizinfo where web_type='입찰공고' and info_source='나라장터' and allow_state=1 and allow_state_biz=1 and reg_date>'{$comp_date}'".$sql_key_search_public;

    $sql_order = " order by reg_date desc limit 0, 20";
    if(isset($_POST['page_num']) || isset($_POST['search_key']) || isset($_POST['exception']) || isset($_POST['regdate_order']) || isset($_POST['enddate_order'])){
        $sql_order = " order by reg_date desc";
        if(isset($_POST['exception'])){
            if($_POST['exception'] == 1){
                $sql_search .= " and work_status=1";
            }
            else if($_POST['exception'] == 2){
                $sql_search .= "";
            }
        }
        
        if(isset($_POST['regdate_order'])){
            if($_POST['regdate_order'] == "asc"){
                $sql_order = " order by reg_date asc";
            }
            else if ($_POST['regdate_order'] == "desc"){
                $sql_order = " order by reg_date desc";
            }
        }
        
        // if(isset($_POST['enddate_order'])){
        //     if($_POST['enddate_order'] == "asc"){
        //         $sql_order = "order by end_date asc";
        //     }
        //     else if ($_POST['enddate_order'] == "desc"){
        //         $sql_order = "order by end_date desc";
        //     }
        // }

        if(isset($_POST['showcnt_order'])){
            if($_POST['showcnt_order'] == "asc"){
                $sql_order = "order by show_cnt asc";
            }
            else if ($_POST['showcnt_order'] == "desc"){
                $sql_order = "order by show_cnt desc";
            }
        }
        
        if(isset($_POST['page_num'])){
            $_POST['page_num'] ? $page_num = $_POST['page_num']:$page_num = 1;
        }
        if(isset($_POST['search_key'])){
            $search_key = $_POST['search_key'];
            $sql_search .= " and (work_name like '%".$search_key."%' or work_type like '%".$search_key."%' or req_obj like '%".$search_key."%')";
        }
        
        $start = ($page_num - 1) * 20;
        $sql_limit = " limit {$start}, 20";
    }

    $sql = $sql_pub.$sql_search;
    
    $res_cnt = mysql_query($sql);
    $total_cnt = mysql_num_rows($res_cnt);

    $sql .= $sql_order.$sql_limit;
    $res_pub = mysql_query($sql);
    $number = $total_cnt - ($page_num - 1) * 20;

    while($row_pub = mysql_fetch_array($res_pub)){
        $arr_replay['id'] = $row_pub['id'];
        $arr_replay['org_name'] = $row_pub['org_name'];
        $arr_replay['work_name'] = $row_pub['work_name'];
        $arr_replay['work_type'] = $row_pub['work_type'];
        $arr_replay['req_obj'] = $row_pub['req_obj'];
        $arr_replay['reg_date'] = $row_pub['reg_date'];
        $arr_replay['end_date'] = $row_pub['end_date'];
        $arr_replay['show_cnt'] = $row_pub['show_cnt'];
        $arr_replay['detail_link'] = $row_pub['detail_link'];
        $reply_content[$reply_index++] = $arr_replay;
    }
    echo json_encode(array('total_cnt'=>$total_cnt, 'number'=>$number, 'page_num'=>$page_num, 'public_res'=>$reply_content));
}
else if(isset($_POST['sanrim_list'])){//기타정보 api
    $sql_sanrim = "select * from get_crawler_bizinfo where web_type='산림정보' and allow_state=1 and allow_state_biz=1 and reg_date>'{$comp_date}'".$sql_key_search_other;

    $sql_order = " order by reg_date desc limit 0, 20";
    if(isset($_POST['page_num']) || isset($_POST['search_key']) || isset($_POST['exception']) || isset($_POST['regdate_order']) || isset($_POST['showcnt_order'])){
        $sql_order = " order by reg_date desc";
        if(isset($_POST['exception'])){
            if($_POST['exception'] == 1){
                $sql_search .= " and work_status=1";
            }
            else if($_POST['exception'] == 2){
                $sql_search .= "";
            }
        }
        
        if(isset($_POST['regdate_order'])){
            if($_POST['regdate_order'] == "asc"){
                $sql_order = " order by reg_date asc";
            }
            else if ($_POST['regdate_order'] == "desc"){
                $sql_order = " order by reg_date desc";
            }
        }
        
        if(isset($_POST['showcnt_order'])){
            if($_POST['showcnt_order'] == "asc"){
                $sql_order = "order by show_cnt asc";
            }
            else if ($_POST['showcnt_order'] == "desc"){
                $sql_order = "order by show_cnt desc";
            }
        }
        
        if(isset($_POST['page_num'])){
            $_POST['page_num'] ? $page_num = $_POST['page_num']:$page_num = 1;
        }
        if(isset($_POST['search_key'])){
            $search_key = $_POST['search_key'];
            $sql_search .= " and (work_name like '%".$search_key."%' or org_name like '%".$search_key."%')";
        }
        
        $start = ($page_num - 1) * 20;
        $sql_limit = " limit {$start}, 20";
    }

    $sql = $sql_sanrim.$sql_search;
    // echo $sql; exit;
    $res_sanrim = mysql_query($sql);
    $total_cnt = mysql_num_rows($res_sanrim);
    $number = $total_cnt - ($page_num - 1) * 20;

    $sql .= $sql_order.$sql_limit;
    $res_sanrim = mysql_query($sql);

    while($row_sanrim = mysql_fetch_array($res_sanrim)){
        $arr_replay['id'] = $row_sanrim['id'];
        $arr_replay['org_name'] = $row_sanrim['org_name'];
        $arr_replay['work_name'] = $row_sanrim['work_name'];
        $arr_replay['reg_date'] = $row_sanrim['reg_date'];
        $arr_replay['show_cnt'] = $row_sanrim['show_cnt'];
        $arr_replay['detail_link'] = $row_sanrim['detail_link'];
        $reply_content[$reply_index++] = $arr_replay;
    }
    echo json_encode(array('total_cnt'=>$total_cnt, 'number'=>$number, 'page_num'=>$page_num, 'sanrim_res'=>$reply_content));
}

else if(isset($_POST['admin_contents'])){
    $sql_key_search = " and ((web_type='지원사업'".$sql_key_search_work.") or (web_type='행사교육'".$sql_key_search_edu.") or (web_type='입찰공고'".$sql_key_search_public.") or (web_type='산림정보'".$sql_key_search_other."))";

    $allow_state_biz = 1;
    if(isset($_POST['ready'])){
        $allow_state_biz = 0;
    }
    $sql_admin = "select * from get_crawler_bizinfo where allow_state=1 and allow_state_biz={$allow_state_biz} and reg_date>'{$comp_date}'".$sql_key_search;

    $sql_order = " order by reg_date desc limit 0, 20";
    if(isset($_POST['page_num']) || isset($_POST['search_key']) || isset($_POST['exception']) || isset($_POST['regdate_order']) || isset($_POST['showcnt_order'])){
        $sql_order = " order by reg_date desc";
        if(isset($_POST['exception'])){
            if($_POST['exception'] == 1){
                $sql_search .= " and work_status=1";
            }
            else if($_POST['exception'] == 2){
                $sql_search .= "";
            }
        }

        if(isset($_POST['regdate_order'])){
            if($_POST['regdate_order'] == "asc"){
                $sql_order = " order by reg_date asc";
            }
            else if ($_POST['regdate_order'] == "desc"){
                $sql_order = " order by reg_date desc";
            }
        }

        if(isset($_POST['enddate_order'])){
            if($_POST['enddate_order'] == "asc"){
                $sql_order = "order by end_date asc";
            }
            else if ($_POST['enddate_order'] == "desc"){
                $sql_order = "order by end_date desc";
            }
        }
        
        if(isset($_POST['showcnt_order'])){
            if($_POST['showcnt_order'] == "asc"){
                $sql_order = "order by show_cnt asc";
            }
            else if ($_POST['showcnt_order'] == "desc"){
                $sql_order = "order by show_cnt desc";
            }
        }

        if(isset($_POST['page_num'])){
            $_POST['page_num'] ? $page_num = $_POST['page_num']:$page_num = 1;
        }
        
        if(isset($_POST['search_key'])){
            $search_key = $_POST['search_key'];
            $sql_search .= " and (work_name like '%".$search_key."%' or info_source like '%".$search_key."%' or org_name like '%".$search_key."%' or reg_date like '%".$search_key."%' or end_date like '%".$search_key."%')";
        }
        
        $start = ($page_num - 1) * 20;
        $sql_limit = " limit {$start}, 20";
    }

    $sql = $sql_admin.$sql_search;
    $res_admin = mysql_query($sql);
    $total_cnt = mysql_num_rows($res_admin);
    $number = $total_cnt - ($page_num - 1) * 20;

    $sql .= $sql_order.$sql_limit;
    // echo $sql; exit;
    $res_admin = mysql_query($sql);

    while($row_admin = mysql_fetch_array($res_admin)){
        $arr_replay['id'] = $row_admin['id'];
        $arr_replay['info_source'] = $row_admin['info_source'];
        $arr_replay['web_type'] = $row_admin['web_type'];
        $arr_replay['org_name'] = $row_admin['org_name'];
        $arr_replay['region_name'] = $row_admin['region_name'];
        $arr_replay['work_type'] = $row_admin['work_type'];
        $arr_replay['work_name'] = $row_admin['work_name'];
        $arr_replay['reg_date'] = $row_admin['reg_date'];
        $arr_replay['end_date'] = $row_admin['end_date'];
        $arr_replay['req_date'] = $row_admin['req_date'];
        $arr_replay['allow_state'] = $row_admin['allow_state'];
        $arr_replay['allow_state_biz'] = $row_admin['allow_state_biz'];
        $arr_replay['show_cnt'] = $row_admin['show_cnt'];
        $arr_replay['detail_link'] = $row_admin['detail_link'];
        $reply_content[$reply_index++] = $arr_replay;
    }
    echo json_encode(array('total_cnt'=>$total_cnt, 'number'=>$number, 'page_num'=>$page_num, 'admin_res'=>$reply_content));
}
else if(isset($_POST['change_contents'])){
    $id = $_POST['id'];
    if(isset($_POST["updat"])){
        $region_name = $_POST['region_name'];
        $work_name = $_POST['work_name'];
        $detail_link = $_POST['detail_link'];
        $org_name = $_POST['org_name'];
        $req_date = $_POST['req_date'];
        $show_cnt = $_POST['show_cnt'];
        $reg_date = $_POST['reg_date'];

        $sql_update = "update get_crawler_bizinfo set region_name='{$region_name}', work_name='{$work_name}', detail_link='{$detail_link}', org_name='{$org_name}', req_date='{$req_date}', show_cnt='{$show_cnt}', reg_date='{$reg_date}' where id={$id}";
        $res = mysql_query($sql_update) or die(mysql_error());
        echo 1;
        exit;
    }
    if(isset($_POST['del'])){
        $sql_del = "delete from get_crawler_bizinfo where id={$id}";
        $res = mysql_query($sql_del) or die(mysql_error());
        echo 1;
        exit;
    }
    if(isset($_POST['change_status'])){
        $sql_state = "select allow_state_biz from get_crawler_bizinfo where id={$id}";
        $res_state = mysql_query($sql_state);
        $row_state = mysql_fetch_array($res_state);
        if($row_state['allow_state_biz'] == 1){
            $sql_update = "update get_crawler_bizinfo set allow_state_biz=0 where id={$id}";
            mysql_query($sql_update);
            echo 0;
            exit;
        }
        else{
            $sql_update = "update get_crawler_bizinfo set allow_state_biz=1 where id={$id}";
            mysql_query($sql_update);
            echo 1;
            exit;
        }
    }
    $sql_key_search = "";
    $admin_key = $row_share['work_key'].",".$row_share['public_key'].",".$row_share['edu_key'].",".$row_share['other_key'];
    if($admin_key != ""){
        if(strpos($admin_key, ",") !== false){
            $admin_key_arr = explode(",", $admin_key);
            for($i = 0; $i < count($admin_key_arr); $i++){
                if($i == count($admin_key_arr) - 1){
                    $sql_key_search .= " work_name like '%".$admin_key_arr[$i]."%'";
                }
                else{
                    $sql_key_search .= " work_name like '%".$admin_key_arr[$i]."%' or";
                }   
            }
        }
        else{
            $admin_key_arr[0] = $admin_key;
            $sql_key_search .= " work_name like '%".$admin_key_arr[0]."%'";
        }
        $sql_key_search = " and (".$sql_key_search.")";
    }

    $sql_admin = "select * from get_crawler_bizinfo where id={$id} and allow_state=1 and reg_date>'{$comp_date}'".$sql_key_search;
    $res_admin = mysql_query($sql_admin);
    $row_admin = mysql_fetch_array($res_admin);
    $arr_replay['info_source'] = $row_admin['info_source'];
    $arr_replay['web_type'] = $row_admin['web_type'];
    $arr_replay['org_name'] = $row_admin['org_name'];
    $arr_replay['region_name'] = $row_admin['region_name'];
    $arr_replay['work_type'] = $row_admin['work_type'];
    $arr_replay['work_name'] = $row_admin['work_name'];
    $arr_replay['reg_date'] = $row_admin['reg_date'];
    $arr_replay['end_date'] = $row_admin['end_date'];
    $arr_replay['req_date'] = $row_admin['req_date'];
    $arr_replay['allow_state'] = $row_admin['allow_state'];
    $arr_replay['allow_state_biz'] = $row_admin['allow_state_biz'];
    $arr_replay['show_cnt'] = $row_admin['show_cnt'];
    $arr_replay['detail_link'] = $row_admin['detail_link'];
    $reply_content[0] = $arr_replay;
    
    echo json_encode(array('admin_res'=>$reply_content));
}
else if(isset($_POST['ready_con'])){
    $sql_key_search = " and ((web_type='지원사업'".$sql_key_search_work.") or (web_type='행사교육'".$sql_key_search_edu.") or (web_type='입찰공고'".$sql_key_search_public.") or (web_type='산림정보'".$sql_key_search_other."))";

    $sql_admin = "select * from get_crawler_bizinfo where allow_state=1 and allow_state_biz=0 and reg_date>'{$comp_date}'".$sql_key_search;
    // echo $sql_admin; exit;
    $res = mysql_query($sql_admin);
    $total_cnt = mysql_num_rows($res);
    echo $total_cnt;
}
else if(isset($_POST['show_cnt'])){
    $id = $_POST['id'];
    $sql = "UPDATE get_crawler_bizinfo set
			show_cnt_today=(case when show_date>'{$today}' then show_cnt_today else 0 end),
			show_date='{$today1}'
			where id='{$id}'
		";
    mysql_query($sql);
    $sql = "UPDATE get_crawler_bizinfo set
        show_cnt_today=show_cnt_today+1,
        show_cnt=show_cnt+1
        where id='{$id}'";
    mysql_query($sql);
    echo 1;
}
else if(isset($_POST['showcnt_list'])){
    $cnt_all = $cnt_today = $cnt_jiwon = $cnt_public = $cnt_edu = $cnt_sanrim = $cnt_jiwon_today = $cnt_public_today = $cnt_edu_today = $cnt_sanrim_today = 0;
    $sql_all = "select sum(show_cnt) as cnt from get_crawler_bizinfo";
    $cnt_all = get_cnt($sql_all);

    $sql_today = "select sum(show_cnt_today) as cnt from get_crawler_bizinfo where show_date>'{$today}'";
    $cnt_today = get_cnt($sql_today);

    $sql_jiwon = "select sum(show_cnt) as cnt from get_crawler_bizinfo where web_type='지원사업'";
    $cnt_jiwon = get_cnt($sql_jiwon);

    $sql_public = "select sum(show_cnt) as cnt from get_crawler_bizinfo where web_type='입찰공고'";
    $cnt_public = get_cnt($sql_public);

    $sql_edu = "select sum(show_cnt) as cnt from get_crawler_bizinfo where web_type='행사교육'";
    $cnt_edu = get_cnt($sql_edu);

    $sql_sanrim = "select sum(show_cnt) as cnt from get_crawler_bizinfo where web_type='산림정보'";
    $cnt_sanrim = get_cnt($sql_sanrim);

    $sql_jiwon_today = "select sum(show_cnt_today) as cnt from get_crawler_bizinfo where web_type='지원사업' and show_date>'{$today}'";
    $cnt_jiwon_today = get_cnt($sql_jiwon_today);

    $sql_public_today = "select sum(show_cnt_today) as cnt from get_crawler_bizinfo where web_type='입찰공고' and show_date>'{$today}'";
    $cnt_public_today = get_cnt($sql_public_today);

    $sql_edu_today = "select sum(show_cnt_today) as cnt from get_crawler_bizinfo where web_type='행사교육' and show_date>'{$today}'";
    $cnt_edu_today = get_cnt($sql_edu_today);

    $sql_sanrim_today = "select sum(show_cnt_today) as cnt from get_crawler_bizinfo where web_type='산림정보' and show_date>'{$today}'";
    $cnt_sanrim_today = get_cnt($sql_sanrim_today);

    echo json_encode(array('cnt_all'=>$cnt_all, 'cnt_today'=>$cnt_today, 'cnt_jiwon'=>$cnt_jiwon, 'cnt_public'=>$cnt_public, 'cnt_edu'=>$cnt_edu, 'cnt_sanrim'=>$cnt_sanrim, 'cnt_jiwon_today'=>$cnt_jiwon_today, 'cnt_public_today'=>$cnt_public_today, 'cnt_edu_today'=>$cnt_edu_today, 'cnt_sanrim_today'=>$cnt_sanrim_today));
}
else if(isset($_POST['manner'])){
    $sel_type = "00";
    if(isset($_POST['sel_type'])){
        $sel_type = $_POST['sel_type'];
    }
    if(($sel_type == "00") || isset($_POST['mypage'])){
        $sql_key_search = " and ((web_type='지원사업'".$sql_key_search_work.") or (web_type='행사교육'".$sql_key_search_edu.") or (web_type='입찰공고'".$sql_key_search_public.") or (web_type='산림정보'".$sql_key_search_other."))";
    }
    else if($sel_type == "01"){
        $sql_key_search = " and web_type='지원사업'".$sql_key_search_work;
    }
    else if($sel_type == "02"){
        $sql_key_search = " and web_type='입찰공고'".$sql_key_search_public;
    }
    else if($sel_type == "03"){
        $sql_key_search = " and web_type='행사교육'".$sql_key_search_edu;
    }
    else if($sel_type == "04"){
        $sql_key_search = " and web_type='산림정보'".$sql_key_search_other;
    }

    $sql_manner = "select * from get_crawler_bizinfo where allow_state=1 and allow_state_biz=1 and reg_date>'{$comp_date}'".$sql_key_search;
    $sql_order = " order by reg_date desc limit 0, 20";

    
    if(isset($_POST['page_num']) || isset($_POST['search_key']) || isset($_POST['regdate_order']) || isset($_POST['showcnt_order'])){
        $sql_order = " order by reg_date desc";

        if(isset($_POST['regdate_order'])){
            if($_POST['regdate_order'] == "asc"){
                $sql_order = " order by reg_date asc";
            }
            else if ($_POST['regdate_order'] == "desc"){
                $sql_order = " order by reg_date desc";
            }
        }
        
        if(isset($_POST['showcnt_order'])){
            if($_POST['showcnt_order'] == "asc"){
                $sql_order = "order by show_cnt asc";
            }
            else if ($_POST['showcnt_order'] == "desc"){
                $sql_order = "order by show_cnt desc";
            }
        }
        
        if(isset($_POST['page_num'])){
            $_POST['page_num'] ? $page_num = $_POST['page_num']:$page_num = 1;
        }
        
        if(isset($_POST['search_key'])){
            $search_key = get_search_key($_POST['search_key']);
            $sql_search .= $search_key;
        }
        
        $start = ($page_num - 1) * 20;
        // $sql_search .= " and work_name like '%".$search_key."%'";
        $sql_limit = " limit {$start}, 20";

        if(isset($_POST['mypage'])){
            $sql_limit = " limit {$start}, 3";
        }
    }

    $sql = $sql_manner.$sql_search;
    $res_manner = mysql_query($sql);
    $total_cnt = mysql_num_rows($res_manner);
    $number = $total_cnt - ($page_num - 1) * 20;

    $sql .= $sql_order.$sql_limit;
    // echo $sql; exit;
    $res_manner = mysql_query($sql);

    while($row_manner = mysql_fetch_array($res_manner)){
        $arr_replay['id'] = $row_manner['id'];
        $arr_replay['info_source'] = $row_manner['info_source'];
        $arr_replay['org_name'] = $row_manner['org_name'];
        $arr_replay['work_name'] = $row_manner['work_name'];
        $arr_replay['reg_date'] = $row_manner['reg_date'];
        $arr_replay['show_cnt'] = $row_manner['show_cnt'];
        $arr_replay['detail_link'] = $row_manner['detail_link'];
        $reply_content[$reply_index++] = $arr_replay;
    }
    echo json_encode(array('total_cnt'=>$total_cnt, 'number'=>$number, 'page_num'=>$page_num, 'manner_res'=>$reply_content));
}
else if(isset($_POST['searchmain'])){
    $sql_search = "";
    $state_biz = "";
    if(isset($_POST['search_key'])){
        $search_key = $_POST['search_key'];
        $sql_search .= " and work_name like '%".$search_key."%'";
        $state_biz = " and allow_state_biz=1";
    }
    if(isset($_POST['main_page'])){
        $state_biz = "";
    }
    
    $sql_order = " order by reg_date desc limit 0, 3";

    //지원사업 통합검색
    $sql_jiwon = "select * from get_crawler_bizinfo where web_type='지원사업' and info_source='기업마당' and allow_state=1 and allow_state_biz=1 and reg_date>'{$comp_date}'".$sql_key_search_work;

    $sql = $sql_jiwon.$sql_search;
    $res_cnt = mysql_query($sql);
    $total_cnt_jiwon = mysql_num_rows($res_cnt);

    $sql .= $sql_order;
    $res_jiwon = mysql_query($sql);
    while($row_jiwon = mysql_fetch_array($res_jiwon)){
        $arr_replay['id'] = $row_jiwon['id'];
        $arr_replay['org_name'] = $row_jiwon['org_name'];
        $arr_replay['work_name'] = $row_jiwon['work_name'];
        $arr_replay['detail_link'] = $row_jiwon['detail_link'];
        $reply_content_jiwon[$reply_index_jiwon++] = $arr_replay;
    }

    //행사교육 통합검색
    $sql_edu = "select * from get_crawler_bizinfo where web_type='행사교육' and allow_state=1 and allow_state_biz=1 and reg_date>'{$comp_date}'".$sql_key_search_edu;

    $sql = $sql_edu.$sql_search;
    $res_cnt = mysql_query($sql);
    $total_cnt_edu = mysql_num_rows($res_cnt);

    $sql .= $sql_order;
    // echo $sql;exit;
    $res_edu = mysql_query($sql);

    while($row_edu = mysql_fetch_array($res_edu)){
        $arr_replay['id'] = $row_edu['id'];
        $arr_replay['org_name'] = $row_edu['org_name'];
        $arr_replay['work_name'] = $row_edu['work_name'];
        $arr_replay['detail_link'] = $row_edu['detail_link'];
        $reply_content_edu[$reply_index_edu++] = $arr_replay;
    }

    //입찰공고 통합검색
    $sql_pub = "select * from get_crawler_bizinfo where web_type='입찰공고' and info_source='나라장터' and allow_state=1 and allow_state_biz=1 and reg_date>'{$comp_date}'".$sql_key_search_public;

    $sql = $sql_pub.$sql_search;
    $res_cnt = mysql_query($sql);
    $total_cnt_pub = mysql_num_rows($res_cnt);

    $sql .= $sql_order;
    $res_pub = mysql_query($sql);
    while($row_pub = mysql_fetch_array($res_pub)){
        $arr_replay['id'] = $row_pub['id'];
        $arr_replay['org_name'] = $row_pub['org_name'];
        $arr_replay['work_name'] = $row_pub['work_name'];
        $arr_replay['detail_link'] = $row_pub['detail_link'];
        $reply_content_pub[$reply_index_pub++] = $arr_replay;
    }

    //기타정보 통합검색
    $sql_sanrim = "select * from get_crawler_bizinfo where web_type='산림정보' and allow_state=1 and allow_state_biz=1 and reg_date>'{$comp_date}'".$sql_key_search_other;

    $sql = $sql_sanrim.$sql_search;
    $res_sanrim = mysql_query($sql);
    $total_cnt_sanrim = mysql_num_rows($res_sanrim);

    $sql .= $sql_order;
    $res_sanrim = mysql_query($sql);
    while($row_sanrim = mysql_fetch_array($res_sanrim)){
        $arr_replay['id'] = $row_sanrim['id'];
        $arr_replay['org_name'] = $row_sanrim['org_name'];
        $arr_replay['work_name'] = $row_sanrim['work_name'];
        $arr_replay['detail_link'] = $row_sanrim['detail_link'];
        $reply_content_sanrim[$reply_index_other++] = $arr_replay;
    }

    $total_cnt_search = $total_cnt_jiwon + $total_cnt_edu + $total_cnt_pub + $total_cnt_sanrim;

    echo json_encode(array('total_cnt'=>$total_cnt_search, 'total_jiwon'=>$total_cnt_jiwon, 'total_edu'=>$total_cnt_edu, 'total_pub'=>$total_cnt_pub, 'total_sanrim'=>$total_cnt_sanrim, 'jiwon_res'=>$reply_content_jiwon, 'edu_res'=>$reply_content_edu, 'public_res'=>$reply_content_pub, 'sanrim_res'=>$reply_content_sanrim));
}
else if(isset($_POST['show_history'])){
    $sql_id_search = "";
    $ids = $_POST['ids'];
    if(strpos($ids, " ") !== false){
        $id = explode(" ", $ids);
    }
    else{
        $id[0] = $ids;
    }

    $cnt = 0;
    if(count($id) > 10){
        $cnt = 10;
    }
    else{
        $cnt = count($id);
    }

    for($j = 0; $j < $cnt; $j++){
        $sql = "select * from get_crawler_bizinfo where id={$id[$j]}";
        // echo $sql; exit;
        $res_hist = mysql_query($sql);
        while($row_hist = mysql_fetch_array($res_hist)){
            $arr_replay['reg_date'] = $row_hist['reg_date'];
            $arr_replay['work_name'] = $row_hist['work_name'];
            $arr_replay['detail_link'] = $row_hist['detail_link'];
            $reply_content_hist[$j] = $arr_replay;
        }
    }
    
    echo json_encode(array('hist_res'=>$reply_content_hist));
}

function get_search_key($key){
    $sql_key_search = "";
    if($key != ""){
        if(strpos($key, ",") !== false){
            $key_arr = explode(",", $key);
            for($i = 0; $i < count($key_arr); $i++){
                if($i == count($key_arr) - 1){
                    $sql_key_search .= " work_name like '%".$key_arr[$i]."%'";
                }
                else{
                    $sql_key_search .= " work_name like '%".$key_arr[$i]."%' or";
                }   
            }
        }
        else{
            $key_arr[0] = $key;
            $sql_key_search .= " work_name like '%".$key_arr[0]."%'";
        }
        $sql_key_search = " and (".$sql_key_search.")";
    }
    return $sql_key_search;
}

function get_cnt($sql){
    $res_cnt = mysql_query($sql);
    if($res_cnt != null){
        $row_cnt = mysql_fetch_array($res_cnt);
        return $row_cnt['cnt'];
    }
    else{
        return 0;
    } 
}
?>