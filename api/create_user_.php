<?php
header('Content-Type: text/html; charset=utf-8');
header('Access-Control-Allow-Origin: *');
include_once $_SERVER['DOCUMENT_ROOT']."/lib/db_config.php";
$id = trim($_REQUEST["id"]);
$passwd = trim($_REQUEST["passwd"]);
$name = trim($_REQUEST["name"]);
$phone = trim($_REQUEST["phone"]);
$email = trim($_REQUEST["email"]);
$sex = trim($_REQUEST["sex"]);
$birth = trim($_REQUEST["birth"]);
$addr = trim($_REQUEST["addr"]);
$zy = trim($_REQUEST["zy"]);
$homepy = trim($_REQUEST["homepy"]);
//아이디 유무 판단
$query = "insert into Gn_Member set mem_id='$id',
                                                  mem_leb='22',
                                                  web_pwd=password('$passwd'),
                                                  mem_pass=md5('$passwd'),
                                                  mem_name='{$name}',
                                                  mem_nick='{$name}',
                                                  mem_phone='{$phone}',
                                                  mem_birth='{$birth}',
                                                  first_regist=now() ,
                                                  login_date=now() ,
                                                  mem_check=now(),
                                                  mem_add1='{$addr}',
                                                  mem_email='{$email}',
                                                  mem_sex='{$sex}',
                                                  zy='{$zy}',
                                                  mem_sch='{$homepy}',
                                                  site = 'kiam',
                                                  site_iam = 'kiam',
                                                  recommend_id='iam1',
                                                  join_ip='{$_SERVER['REMOTE_ADDR']}'";
mysql_query($query);
?>