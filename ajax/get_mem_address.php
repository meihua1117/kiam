<?
include_once "../lib/rlatjd_fun.php";
$mem_id = $_POST['mem_id'];
$gwc_req_alarm = str_replace("\n", "<br>", get_search_key('gwc_req_alarm'));
if($_POST['mode'] == "get_phone_num"){
  $sql_phone = "select mem_phone from Gn_Member where mem_id='$mem_id'";
  $res_phone = mysqli_query($self_con,$sql_phone);
  $row_phone = mysqli_fetch_array($res_phone);
  echo $row_phone[0];
}
else if($_POST['mode'] == "get_user_info"){
  $sql = "select mem_name, site, site_iam, mem_phone, service_type from Gn_Member where mem_id='$mem_id'";
  $res = mysqli_query($self_con,$sql);
  $row = mysqli_fetch_array($res);
  echo '{"mem_name":"'.$row['mem_name'].'", "site":"'.$row['site'].'", "site_iam":"'.$row['site_iam'].'", "mem_phone":"'.$row['mem_phone'].'", "service_type":"'.$row['service_type'].'"}';
}
else if($_POST['mode'] == "reseller_state"){
  $sql_chk = "select count(a.mem_code) as cnt from Gn_Member a inner join Gn_Iam_Service b on a.mem_id=b.mem_id where a.service_type>=2 and a.mem_id='{$_POST[mem_id]}'";
  $res_chk = mysqli_query($self_con,$sql_chk);
  $row_chk = mysqli_fetch_array($res_chk);
  if($row_chk[0]){
    echo 1;
  }
  else{
    if($_POST[mem_id] == 'obmms02'){
      echo 1;
    }
    else{
      echo 0;
    }
  }
}
else if($_POST['mode'] == "cur_gwcleb_state"){
  $sql_chk = "select gwc_leb from Gn_Member where mem_id='{$_POST[mem_id]}'";
  $res_chk = mysqli_query($self_con,$sql_chk);
  $row_chk = mysqli_fetch_array($res_chk);
  if($row_chk[0] == "1" || $row_chk[0] == "4"){
    echo 1;
  }
  else{
    echo 0;
  }
}
else if($_POST['mode'] == "check_gwc_member"){
  $sql_chk = "select count(mem_code) as cnt from Gn_Member where gwc_leb!=0 and gwc_state!=0 and mem_id='{$_POST[mem_id]}'";
  $res_chk = mysqli_query($self_con,$sql_chk);
  $row_chk = mysqli_fetch_array($res_chk);

  $sql_cont_cnt = "select count(*) from Gn_Iam_Contents_Gwc where mem_id='{$_SESSION[iam_member_id]}' and ori_store_prod_idx!=0";
  $res_cnt = mysqli_query($self_con,$sql_cont_cnt);
  $row_cnt = mysqli_fetch_array($res_cnt);
  if($row_chk[0]){
    echo '{"res":"1", "cont_cnt":"'.$row_cnt[0].'"}';
  }
  else{
    echo '{"res":"0", "msg":"'.$gwc_req_alarm.'"}';
  }
}
else if($_POST['mode'] == "check_deliver_id"){
  $sql_mem = "select mem_code, mem_id, mem_name, mem_phone, mem_email, mem_add1, bank_name, bank_owner, bank_account from Gn_Member where mem_id='{$_POST['deliver_id']}'";
  $res_mem = mysqli_query($self_con,$sql_mem);
  $row = mysqli_fetch_array($res_mem);
  if($row['mem_id']){
    echo '{"result":1, "mem_code":"'.$row['mem_code'].'", "mem_id":"'.$row['mem_id'].'", "mem_name":"'.$row['mem_name'].'", "mem_phone":"'.$row['mem_phone'].'", "mem_email":"'.$row['mem_email'].'", "mem_add1":"'.$row['mem_add1'].'", "bank_name":"'.$row['bank_name'].'", "bank_owner":"'.$row['bank_owner'].'", "bank_account":"'.$row['bank_account'].'"}';
  }
  else{
    echo '{"result":0}';
  }
}
else{
  $sql = "select mem_add1 from Gn_Member where mem_id='$mem_id'";
  $res = mysqli_query($self_con,$sql);
  $row = mysqli_fetch_array($res);
  if($row['mem_add1'])
    echo '{"address":"'.$row['mem_add1'].'"}';
}
?>