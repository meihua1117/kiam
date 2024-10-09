<?php 
include_once $_SERVER['DOCUMENT_ROOT']."/lib/rlatjd_fun.php";
include_once $_SERVER['DOCUMENT_ROOT']."/admin/mn_common.php";

function check_join_tables($tables, $join_table) {
    if($join_table == '') {
        return false;
    }
    foreach($tables as $table) {
        if($table['table'] == $join_table) {
            return false;
        }
    }
    return true;
}

$searchParam = $_REQUEST['searchParam'];
$searchParam = str_replace('\\', '', $searchParam);
$searchParam = json_decode($searchParam, 1);
$ret = array('status'=>'1', 'count'=>'0');
$joined_tables = array();
$search = array();
$from_table = "";

$keyList = array();
$wheres = array();      
foreach($searchParam as $param) {
    $from = isset($param['from']) ? $param['from'] : '1800-01-01';
    $to = isset($param['to']) ? $param['to'] :'2500-01-01';
    $value = isset($param['value']) ? $param['value'] : "";
    $from_login_count = isset($param['from_count']) ? $param['from_count'] : "0";
    $from_login_count = $from_login_count == '' ? '0' : $from_login_count;
    $to_login_count = isset($param['to_count']) ? $param['to_count'] : "10000000000000";
    $to_login_count = $to_login_count == '' ? '0' : $to_login_count;

    $key = $param['key'];
    $details = $param['details'];

    if($key == "keyword") {
        $values = explode(",", $value);
        $likes = array();
        foreach($values as $keyword) {
            $likes[] = "Gn_Iam_Name_Card.card_keyword like '%{$keyword}%'";
        }
        $like = implode(" AND ", $likes);
    }else {
        $like = "";
    }

    $join_tables = array(
        'phone'=>array('Gn_Member', '', ''),
        'member'=>array('Gn_Member', 'Gn_MMS_Number', 'Gn_MMS_Number.mem_id =Gn_Member.mem_id'),
        'product'=>array('tjd_pay_result', 'Gn_Member', 'Gn_Member.mem_id =tjd_pay_result.buyer_id'),
        'level'=>array('Gn_Member', '', ''),
        'selling_level'=>array('Gn_Member', '', ''),
        'iam_level'=>array('Gn_Member', 'Gn_Iam_Service', 'Gn_Iam_Service.mem_id = Gn_Member.mem_id'),
        'selling'=>array('Gn_Member', '', ''),
        'iam_sell'=>array('Gn_Iam_Service', 'Gn_Member', 'Gn_Member.mem_id = Gn_Iam_Service.mem_id'),
        'region'=>array('Gn_Member', '', ''),
        'age'=>array('Gn_Member', '', ''),
        'keyword'=>array('Gn_Member', 'Gn_Iam_Name_Card', 'Gn_Iam_Name_Card.mem_id = Gn_Member.mem_id'),
        'recommend'=>array('(SELECT *, COUNT(*) as cnt FROM Gn_Member GROUP BY recommend_id) AS Gn_Member', '', ''),
        'login_count'=>array('Gn_Member', '', ''));
    $search_conditions = array(
        'phone'=>array(
            'all'=>"1=1",
            'number'=>"FIND_IN_SET(REPLACE(Gn_Member.mem_phone, '-', ''), '{$value}')"
        ),
        'member'=>array(
            'all'=>"1=1",
            'owner'=>"REPLACE(Gn_Member.mem_phone,'-','') = REPLACE(Gn_MMS_Number.sendnum, '-','') and Gn_MMS_Number.sendnum is not null and Gn_MMS_Number.sendnum != ''",
            'add'=>"REPLACE(Gn_Member.mem_phone,'-','') != REPLACE(Gn_MMS_Number.sendnum,'-','') and Gn_MMS_Number.sendnum is not null and Gn_MMS_Number.sendnum != ''"
        ),
        'product'=>array(
            'all'=>"1=1",
            'standard'=>"tjd_pay_result.member_type = 'standard'",
            'professional'=>"tjd_pay_result.member_type = 'professional'",
            'enterprise'=>"tjd_pay_result.member_type = 'enterprise'",
            'year-professional'=>"tjd_pay_result.member_type = 'year-professional'",
            'dber'=>"tjd_pay_result.member_type = 'dber'"
        ),
        'level'=>array(
            'all'=>"1=1",
            'normal'=>"Gn_Member.mem_leb = 22",
            'work'=>"Gn_Member.mem_leb = 50",
            'speaker'=>"Gn_Member.mem_leb = 21",
            'painter'=>"Gn_Member.mem_leb = 60"
        ),
        'selling_level'=>array(
            'all'=>"1=1",
            'normal'=>"Gn_Member.service_type = 0",
            'reseller'=>"Gn_Member.service_type = 1",
            'seller'=>"Gn_Member.service_type = 2",
            'seller_domain'=>"Gn_Member.site like '%{$value}%'"
        ),
        'iam_level'=>array(
            'all'=>"1=1",
            'free'=>"Gn_Member.mem_iam_type='0'",
            'pay'=>"Gn_Member.mem_iam_type = '1'",
            'reseller'=>"Gn_Iam_Service.manager_name = '리셀러'",
            'seller_start'=>"Gn_Iam_Service.manager_name = '분양자' and Gn_Iam_Service.status = 'Y'",
            'seller_stop'=>"Gn_Iam_Service.manager_name = '분양자' and Gn_Iam_Service.status <> 'Y'",
            // 'seller_domain'=>"Gn_Iam_Service.sub_domain = '{$value}'",
            'seller_domain'=>"Gn_Member.site_iam like '%{$value}%'",
            'person'=>"Gn_Member.mem_iam_type = '2'",
            'work'=>"Gn_Member.mem_iam_type = '3'",
            'company'=>"Gn_Member.mem_iam_type = '4'",
            'stage'=>"Gn_Member.mem_iam_type = '5'"
        ),
         'selling'=>array(
            'all'=>"1=1",
            'normal'=>"Gn_Member.service_type = 0",
            'reseller'=>"Gn_Member.service_type = 1",
            'seller'=>"Gn_Member.service_type = 2"
        ),
        /*'iam_sell'=>array(
            'all'=>"1=1",
            'start'=>"a.status = 'Y'",
            'stop'=>"a.status <> 'Y'",
            'special'=>"b.sub_domain = '{$value}'"
        ),*/
        'region'=>array(
            'all'=>"1=1",
            'korea'=>"Gn_Member.mem_add1 like '%대한민국%'",
            'province'=>"Gn_Member.mem_add1 like '%{$value}%'",
            'city'=>"Gn_Member.mem_add1 like '%{$value}%'",
            'town'=>"Gn_Member.mem_add1 like '%{$value}%'"
        ),
        'age'=>array(
            'all'=>"1=1",
            'special'=>"Gn_Member.mem_birth >= '{$from}' and Gn_Member.mem_birth < '{$to}'"
        ),
        'keyword'=>array(
            'all'=>"1=1",
            'special'=>"{$like}"
        ),
        'recommend'=>array(
            'id'=>"Gn_Member.recommend_id = '{$value}'",
            'all'=>"1=1",
            'count'=>"Gn_Member.cnt >= {$from} and Gn_Member.cnt < {$to}"
        ),
        'login_count'=>array(
            'period'=>"Gn_Member.login_date >= '{$from}' and Gn_Member.login_date <= '{$to}' AND Gn_Member.visited >= {$from_login_count} and Gn_Member.visited <= {$to_login_count}",
            'count'=>"Gn_Member.visited >= {$from_login_count} and Gn_Member.visited <= {$to_login_count} AND Gn_Member.login_date >= '{$from}' and Gn_Member.login_date <= '{$to}'"
    ));

    if($key == '') {
        $ret['status'] = '0';
        echo json_encode($ret);
        return;
    }
    if($details == '') {
        $ret['status'] = '0';
        echo json_encode($ret);
        return;
    }

    /***** =============== select count ============= *******/
    /*if($key == "recommend" && $details[0] == "count" && $from == 0) {
        $query = "SELECT recommend_id FROM Gn_Member GROUP BY recommend_id";
        $res  = mysql_query($query);
        $recommends = array();
        while($row = mysql_fetch_array($res)) {
            $recommends[] = $row['recommend_id'];
        }
        $in = implode("','", $recommends);
        $search[$key] = "Gn_Member.mem_id not in ('{$in}')";
        $from_table = 'Gn_Member';
    }else if($key == "recommend" && $details[0] == "id"){
        $from_table = 'Gn_Member';
        $search[$key] = "Gn_Member.recommend_id = '{$value}'";
    }else */{
        $conditions = $search_conditions[$key];
        $join_table = $join_tables[$key];
        if(!in_array($key, $keyList))
        {
            $wheres = array();
            array_push($keyList, $key);
        }
        foreach($details as $detail) {
            if(array_key_exists($detail, $conditions)) {
                $wheres[] = $conditions[$detail];
                //array_push($wheres, $conditions[$detail]);
            }
        }
        
        if(count($wheres) == 1 )
            $where = $wheres[0];
        else{
            $combine = ") OR (";
            if($key == "region")
                $combine = ") AND (";
            $where = "(".implode($combine, $wheres).")";
        }
        //$where = count($wheres) == 1 ? $wheres[0] : "(".implode(") OR (", $wheres).")";
        $search[$key] = $where;
        $from_table = ($from_table != 'Gn_Member' & $from_table != '') ? $from_table : $join_table[0];
        if(check_join_tables($joined_tables, $join_table[1])) {
            $join = array();
            $join['table'] = $join_table[1];
            $join['on'] = $join_table[2];
            if($key == "product" && $join_table[1] != '') {
                $join['dir'] = 'INNER';
            }else {
                $join['dir'] = 'LEFT';
            }
            $joined_tables[] = $join;
        }

    }
}


$query = "";
if($from_table != '') {
    $query = "SELECT count(*) FROM {$from_table} ";
}

if(count($joined_tables) > 0) {
    $join_query = "";
    foreach($joined_tables as $join) {
        if($join['table'] == 'Gn_Member') {
            $join_query = " {$join['dir']} JOIN {$join['table']} ON {$join['on']} ".$join_query;
        }else {
            $join_query .= " {$join['dir']} JOIN {$join['table']} ON {$join['on']}";
        }
    }
    $query.= $join_query;
}

if(count($search) > 0) {
    $where = implode(") AND (", $search);
    $where = "(".$where.")";
    $query .= " WHERE {$where}";
}

//file_put_contents("query.txt", "condition:".print_r($searchParam, 1), FILE_APPEND);
//file_put_contents("query.txt", $query."\n", FILE_APPEND);
$res = mysql_query($query);
$row = mysql_fetch_row($res);
$ret['count'] = $row[0];
echo json_encode($ret);
?>