<?php

$ip = $_SERVER['REMOTE_ADDR'];
$userid_selling = $_SESSION['one_member_id'];
$userid_iam = $_SESSION['iam_member_id'];
$url = $_SERVER['REQUEST_URI'];
$curtime = date("Y-m-d H:i:s");
$pasttime = date("Y-m-d H:i:s", strtotime("-1 second"));

// $ignore_arr = array('115.161', '122.202', '223.38', '223.32', '122.32', '211.234', '121.190', '223.39', '223.33', '223.62', '203.226', '175.202', '223.57', '175.223', '175.252', '210.125', '211.246', '110.70', '39.7', '114.200', '117.111', '211.36', '106.102', '61.43', '125.188', '211.234', '106.101');
// $ignore_arr1 = array('118.235.8', '118.235.9');
// $comp_ip1 = explode('.', $ip);
// $comp_ip = trim($comp_ip1[0]).".".trim($comp_ip1[1]);
// $comp_ip_ = trim($comp_ip1[0]).".".trim($comp_ip1[1]).".".trim($comp_ip1[2]);

$query = "select ip from gn_block_ip where ip='$ip'";
$res = mysql_query($query);
$row = mysql_fetch_array($res);
if($row[0] != "")
    exit;
//로그인 이력에 없으면 아이피 차단
/*$sql_chk = "select count(*) as cnt from gn_hist_login where ip='{$ip}' and success='Y'";
$res_chk = mysql_query($sql_chk);
$row_chk = mysql_fetch_array($res_chk);
if(!$row_chk[0]){
    // if(!in_array($comp_ip, $ignore_arr) && !in_array($comp_ip_, $ignore_arr1)){
        $query = "insert into gn_block_ip (ip, type) values('$ip', 2)";
        $res = mysql_query($query);
    // }
}*/
    
if(strpos($url, "obmms.net") !== false || $url[0] == '/')
{
    //접속로그에 넣는다.
    $query = "insert into gn_hist_access set 
                ip='$ip',
                userid_selling='$userid_selling',
                userid_iam='$userid_iam',
                url='$url',
                regdate='$curtime'
            ";
    mysql_query($query);
}


 




?>